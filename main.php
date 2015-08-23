<?php
include("common.php");

//-----------------------------------------イニシャライズ------------------------------------------
//データベースに接続
$pdo = db_con();

//クエリによってアイコンが変化するための対応
$defaultTypeIcon = "glyphicon glyphicon-th-large";
$defaultSortIcon = "glyphicon glyphicon-sort-by-attributes";
$defaultTypeNum = "typeNum1";
$defaultSortNum = "sortNum1";

//表示形式
if (isset($_GET['type'])) {

    switch ($_GET['type']) {
        case 1:
            $typeIcon = $defaultTypeIcon;
            $typeNum = $defaultTypeNum;
            break;
        case 2:
            $typeIcon = "glyphicon glyphicon-th-list";
            $typeNum = "typeNum2";
            break;
        case 3:
            $typeIcon = "glyphicon glyphicon-th";
            $typeNum = "typeNum3";
            break;
    }
} else {
    $typeIcon = $defaultTypeIcon;
    $typeNum = $defaultTypeNum;
}

//ソート
if (isset($_GET['sort'])) {

    switch ($_GET['sort']) {
        case 1:
            $sortIcon = $defaultSortIcon;
            $sortNum = $defaultSortNum;
            break;
        case 2:
            $sortIcon = "glyphicon glyphicon-sort-by-attributes-alt";
            $sortNum = "sortNum2";
            break;
    }
} else {
    $sortIcon = $defaultSortIcon;
    $sortNum = $defaultSortNum;
}
//-----------------------------------------イニシャライズここまで------------------------------------------


//------------------------POST処理------------------------
if (!isset($_POST["post_flg"])) {
    //echo "パラメータが無いので登録処理は無し";
} else {
    //------------------------写真アップロードの処理------------------------
    if ($_POST["post_flg"] == 1) {

        foreach ($_FILES['addPhoto']['error'] as $key => $value) {
            if ($value == UPLOAD_ERR_OK) {
                //元のファイルネーム
                $fileName = $_FILES['addPhoto']['name'][$key];
                //一意のファイルネーム
                $uqFileName = uniqid('p') . '.' . getExt($fileName);
                $tmpName = $_FILES['addPhoto']['tmp_name'][$key];

                if (is_uploaded_file($tmpName)) {
                    if (move_uploaded_file($tmpName, "photo/" . $uqFileName)) {
                        chmod("photo/" . $uqFileName, 0644);
//                $stmt = $pdo->prepare("INSERT INTO photo (p_id, p_fileName, p_date, p_resistDate, p_title, p_koujiName, p_koujiShu,p_class,p_subClass,p_koushuYobi,p_place,p_period,p_infoYobi,p_photographer,p_company,p_description,p_floor,p_xStreet,p_yStreet,p_starFlg,p_blackBoardFlg)VALUES(NULL, :p_fileName, sysdate(), sysdate(), NULL , NULL ,NULL ,NULL ,NULL ,NULL ,NULL ,NULL ,:p_photographer,NULL ,NULL ,NULL ,NULL ,NULL ,0,0)");
                        $stmt = $pdo->prepare("INSERT INTO photo (p_id, p_fileName, p_date ,p_resistDate,p_photographer,p_starFlg,p_blackBoardFlg )VALUES(NULL, :p_fileName, sysdate(), sysdate(),:p_photographer,0,0)");
                        $stmt->bindValue(':p_fileName', $uqFileName);
                        $stmt->bindValue(':p_photographer', '中島貴春');
                        $status = $stmt->execute();

                        if ($status == false) {

                            echo "SQLエラー";
                            exit;

                        }
                        createThumbnail($uqFileName);
                    }
                }
            }
        }
        header("Location:main.php");

        //------------------------写真削除------------------------
    } else if ($_POST["post_flg"] == 2) {

        if (isset($_POST['selectedNo'])) {
            $queryStr = 'DELETE FROM photo WHERE p_id IN(';
            //クエリ分に選択されたIDを足していく

            foreach ($_POST['selectedNo'] as $val) {
                $queryStr .= $val . ',';
            }

            //最後にいらない,を削除
            $queryStr = substr($queryStr, 0, -1) . ")";
            //SQLの処理
            $stmt = $pdo->query($queryStr);

            header("Location: main.php?deleted=1");
        }

        //------------------------ダウンロード処理------------------------
    } else if ($_POST["post_flg"] == 3 && isset($_POST['selectedNo'])) {

        // Zipクラスロ
        $zip = new ZipArchive();
// Zipファイル名
        $zipFileName = 'photos.zip';
// Zipファイル一時保存ディレクトリ
        $zipTmpDir = './tmp/';

// Zipファイルオープン
        $result = $zip->open($zipTmpDir . $zipFileName, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE);
        if ($result !== true) {
            // 失敗した時の処理
        }

// ここでDB等から画像イメージ配列を取ってくる
        $image_data_array = array();
        $queryStr = 'SELECT p_fileName FROM photoruction.photo WHERE p_id IN (';
        foreach ($_POST['selectedNo'] as $val) {
            $queryStr .= $val . ',';
        }
        $queryStr = substr($queryStr, 0, -1) . ')';

        $stmt = $pdo->prepare($queryStr);
        $flg = $stmt->execute();

        if ($flg == true) {
            while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                array_push($image_data_array, './photo/' . $result['p_fileName']);
            }
        }

// 処理制限時間を外す
        set_time_limit(0);

        foreach ($image_data_array as $filepath) {

            $filename = basename($filepath);

            // 取得ファイルをZipに追加していく
            $zip->addFromString($filename, file_get_contents($filepath));

        }

        $zip->close();

// ストリームに出力
        header('Content-Type: application/zip; name="' . $zipFileName . '"');
        header('Content-Disposition: attachment; filename="' . $zipFileName . '"');
        header('Content-Length: ' . filesize($zipTmpDir . $zipFileName));
        echo file_get_contents($zipTmpDir . $zipFileName);

// 一時ファイルを削除しておく
        unlink($zipTmpDir . $zipFileName);
        exit();

//------------------------アップデート処理------------------------
    } else if ($_POST["post_flg"] == 4 && isset($_POST['selectedNo'])) {

        //念のめ初期化
        $queryStr = "(";

        foreach ($_POST['selectedNo'] as $val) {
            $queryStr .= $val . ",";
        }
        $queryStr = substr($queryStr, 0, -1) . ')';

        $e_p_title = $_POST['e_p_title'];
        $e_p_koujiShu = $_POST['e_p_koujiShu'];
        $e_p_class = $_POST['e_p_class'];
        $e_p_subClass = $_POST['e_p_subClass'];
        $e_p_place = $_POST['e_p_place'];
        $e_p_period = $_POST['e_p_period'];
        $e_p_koujiStatus = $_POST['e_p_koujiStatus'];
        $e_p_infoYobi = $_POST['e_p_infoYobi'];
        $e_p_photographer = $_POST['e_p_photographer'];


        $stmt = $pdo->prepare("UPDATE photo SET p_title=:e_p_title, p_koujiShu=:e_p_koujiShu, p_class=:e_p_class, p_subClass=:e_p_subClass, p_place=:e_p_place, p_period=:e_p_period, p_place=:e_p_place, p_koujiStatus=:e_p_koujiStatus, p_infoYobi=:e_p_infoYobi, p_photographer=:e_p_photographer WHERE p_id IN {$queryStr}");

        $stmt->bindValue(':e_p_title', $e_p_title);
        $stmt->bindValue(':e_p_koujiShu', $e_p_koujiShu);
        $stmt->bindValue(':e_p_class', $e_p_class);
        $stmt->bindValue(':e_p_subClass', $e_p_subClass);
        $stmt->bindValue(':e_p_place', $e_p_place);
        $stmt->bindValue(':e_p_period', $e_p_period);
        $stmt->bindValue(':e_p_koujiStatus', $e_p_koujiStatus);
        $stmt->bindValue(':e_p_infoYobi', $e_p_infoYobi);
        $stmt->bindValue(':e_p_photographer', $e_p_photographer);

        $status = $stmt->execute();

        if ($status == false) {
            echo "SQLエラー";
            exit;
        }
//        ２重送信の防止防止
        header("Location: main.php?type=1");

    }

}
//---------------------------------------POSTの処理はここまで-------------------------------------------------


//---------------------------------------データ表示SQL--------------------------------------
//検索がないのであれば全てを表示する。検索クエリがあれば、それに引っかかったものを取得する
//昇順・降順の設定
if ($sortNum == "sortNum1") {
    $sortSql = "DESC";
} else {
    $sortSql = "ASC";
}
if (isset($_GET['search'])) {
//検索した場合
    $sw = $_GET['search'];
    $stmt = $pdo->prepare("SELECT * FROM photo WHERE p_title LIKE ('{$sw}') OR p_photographer LIKE('{$sw}') ORDER BY p_date {$sortSql}");
} else {
//検索してない場合
    $stmt = $pdo->prepare("SELECT * FROM photo ORDER BY p_date {$sortSql}");
}
//SQL実行
$flag = $stmt->execute();
//データ表示
$view = "";
if ($flag == false) {
    $view = "SQLエラー";
} else {
    while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {

        $defaultType = <<<EOS
                    <div class="col-xs-3 photoFrame_1 shadow-z-1">
                        <div class="photoImg_1">
                            <a href="photo.php?photo={$result['p_id']}"><img src="photoS/s_{$result['p_fileName']}" alt="{$result['p_title']}" class="img-responsive"></a>
                        </div>
                        <div class="photoDescription_1">
                            <p>写真タイトル : {$result['p_title']}</p>

                            <p>撮影日 : {$result['p_date']}</p>

                            <p>工事種 : {$result['p_koujiShu']}</p>

                            <p>立会者 : {$result['p_photographer']}</p>

                        </div>

                        <div class="form-group checkboxArea">
                            <div class="checkbox photoCheckBoxWrapper">
                            <label>
                                <input type="checkbox" class="photoCheckBox" name="selectedNo[]" value="{$result['p_id']}">
                                </label>
                            </div>
                        </div>
                    </div>

EOS;

        if (isset($_GET['type'])) {

            switch ($_GET['type']) {
                case 1:
                    $view .= $defaultType;
                    break;

                case 2:
                    $view .= <<<EOS
                    <div class="col-xs-4 photoFrame_2 shadow-z-1">
                        <div class="photoImg_2">
                            <a href="photo.php?photo={$result['p_id']}"><img src="photoS/s_{$result['p_fileName']}" alt="{$result['p_title']}" class="img-responsive"></a>
                        </div>
                        <div class="photoDescription_2">
                            <p>写真タイトル : {$result['p_title']}</p>
                        </div>
                        <div class="form-group checkboxArea">
                            <div class="checkbox photoCheckBoxWrapper">
                            <label>
                                <input type="checkbox" class="photoCheckBox" name="selectedNo[]" value="{$result['p_id']}">
                                </label>
                            </div>
                        </div>

                    </div>
EOS;
                    break;

                case 3:
                    $view .= <<<EOS
                    <div class="col-xs-12 photoFrame_3 shadow-z-1">
                        <div class="form-group checkboxArea">
                            <div class="checkbox photoCheckBoxWrapper">
                            <label>
                                <input type="checkbox" class="photoCheckBox" name="selectedNo[]" value="{$result['p_id']}">
                                </label>
                            </div>
                        </div>
                        <div class="photoImg_3">
                            <a href="photo.php?photo={$result['p_id']}"><img src="photoS/s_{$result['p_fileName']}" alt="{$result['p_title']}" class="img-responsive"></a>
                        </div>
                        <div class="photoDescription_3">
                            <p>写真タイトル : {$result['p_title']}</p>
                            <p>立会者 : </p>
                            <p>撮映階 : </p>
                            <p>X通り :　　　　Y通り : </p>
                        </div>
                        <div class="photoDescription_3">
                            <p>工種 : </p>
                            <p>施工状況 : </p>
                            <p>施工状況詳細 : </p>
                            <p>撮影日 : </p>
                        </div>
                        <div class="photoDescription_3">
                            <p>撮影情報予備 : </p>
                        </div>
                    </div>
EOS;
                    break;

            }
        } else {
            $view .= $defaultType;
        }


    }

}
//---------------------------------------データ表示SQLここまで--------------------------------------

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Photoruction -写真一覧-</title>
    <link rel="stylesheet" type="text/css" href="css/jquery-ui.min.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap-select.min.css">
    <link rel="stylesheet" type="text/css" href="css/roboto.min.css">
    <link rel="stylesheet" type="text/css" href="css/material-fullpalette.min.css">
    <link rel="stylesheet" type="text/css" href="css/ripples.min.css">
    <link rel="stylesheet" type="text/css" href="css/non-responsive.css">
    <link rel="stylesheet" type="text/css" href="css/chat.css">
    <link rel="stylesheet" type="text/css" href="css/common.css">
    <link rel="stylesheet" type="text/css" href="css/photoinspector.css">
    <link rel="stylesheet" href="css/main.css">
</head>

<body>
<!--   ナビゲーション-->
<?= navigationSelect(PHOTO_LIST) ?>

<!--    本体部分-->
<div class="container">
    <div class="row main-row">
        <!--       フォトインスペクタ-->
        <div class="col-xs-3 test1">
            <div id="photoinspectorMenu">
                <button id="photoinspectorBtn" class="btn btn-info" data-toggle="modal"
                        data-target="#photoinspectorWindow">工事種→階→撮影日
                </button>
                <span class="glyphicon glyphicon-info-sign iconBtn" data-toggle="modal"
                      data-target="#infoPhotoinspector"></span></div>
            <div id="dl-menu" class="dl-menuwrapper">
                <!--                <button class="dl-trigger">Open Menu</button>-->
                <ul class="dl-menu dl-menuopen dl-menu-toggle">
                    <li>
                        <div class="menuItem">

                            全ての写真
                        </div>
                        <ul class="dl-submenu">

                            <li>
                                <div class="menuItem">
                                    <div class="checkbox folderCheckBoxWrapper">
                                        <label>
                                            <input type="checkbox" class="folderCheckBox" name="selectedNo[]"
                                                   value="{$result['p_id']}">
                                        </label>
                                    </div>
                                    1F
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <div class="menuItem">
                            <div class="checkbox folderCheckBoxWrapper">
                                <label>
                                    <input type="checkbox" class="folderCheckBox" name="selectedNo[]"
                                           value="{$result['p_id']}">
                                </label>
                            </div>
                            仮設工事
                            <a href="#"><img src="img/icon/right.png" class="rightIcon"></a>
                        </div>
                        <ul class="dl-submenu">

                            <li>
                                <div class="menuItem">
                                    <div class="checkbox folderCheckBoxWrapper">
                                        <label>
                                            <input type="checkbox" class="folderCheckBox" name="selectedNo[]"
                                                   value="{$result['p_id']}">
                                        </label>
                                    </div>
                                    1F
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <div class="menuItem">
                            <div class="checkbox folderCheckBoxWrapper">
                                <label>
                                    <input type="checkbox" class="folderCheckBox" name="selectedNo[]"
                                           value="{$result['p_id']}">
                                </label>
                            </div>
                            土工事
                            <a href="#"><img src="img/icon/right.png" class="rightIcon"></a>
                        </div>
                        <ul class="dl-submenu">

                            <li>
                                <div class="menuItem">
                                    <div class="checkbox folderCheckBoxWrapper">
                                        <label>
                                            <input type="checkbox" class="folderCheckBox" name="selectedNo[]"
                                                   value="{$result['p_id']}">
                                        </label>
                                    </div>
                                    1F
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <div class="menuItem">
                            <div class="checkbox folderCheckBoxWrapper">
                                <label>
                                    <input type="checkbox" class="folderCheckBox" name="selectedNo[]"
                                           value="{$result['p_id']}">
                                </label>
                            </div>
                            地業工事
                            <a href="#"><img src="img/icon/right.png" class="rightIcon"></a>
                        </div>
                        <ul class="dl-submenu">

                            <li>
                                <div class="menuItem">
                                    <div class="checkbox folderCheckBoxWrapper">
                                        <label>
                                            <input type="checkbox" class="folderCheckBox" name="selectedNo[]"
                                                   value="{$result['p_id']}">
                                        </label>
                                    </div>
                                    1F
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <div class="menuItem">
                            <div class="checkbox folderCheckBoxWrapper">
                                <label>
                                    <input type="checkbox" class="folderCheckBox" name="selectedNo[]"
                                           value="{$result['p_id']}">
                                </label>
                            </div>
                            鉄筋工事
                            <a href="#"><img src="img/icon/right.png" class="rightIcon"></a>
                        </div>
                        <ul class="dl-submenu">

                            <li>
                                <div class="menuItem">
                                    <div class="checkbox folderCheckBoxWrapper">
                                        <label>
                                            <input type="checkbox" class="folderCheckBox" name="selectedNo[]"
                                                   value="{$result['p_id']}">
                                        </label>
                                    </div>
                                    RF
                                </div>
                            </li>
                            <li>
                                <div class="menuItem">
                                    <div class="checkbox folderCheckBoxWrapper">
                                        <label>
                                            <input type="checkbox" class="folderCheckBox" name="selectedNo[]"
                                                   value="{$result['p_id']}">
                                        </label>
                                    </div>
                                    2F
                                </div>
                            </li>
                            <li>
                                <div class="menuItem">
                                    <div class="checkbox folderCheckBoxWrapper">
                                        <label>
                                            <input type="checkbox" class="folderCheckBox" name="selectedNo[]"
                                                   value="{$result['p_id']}">
                                        </label>
                                    </div>
                                    1F
                                </div>
                            </li>
                            <li>
                                <div class="menuItem">
                                    <div class="checkbox folderCheckBoxWrapper">
                                        <label>
                                            <input type="checkbox" class="folderCheckBox" name="selectedNo[]"
                                                   value="{$result['p_id']}">
                                        </label>
                                    </div>
                                    B1F
                                </div>
                            </li>
                            <li>
                                <div class="menuItem">
                                    <div class="checkbox folderCheckBoxWrapper">
                                        <label>
                                            <input type="checkbox" class="folderCheckBox" name="selectedNo[]"
                                                   value="{$result['p_id']}">
                                        </label>
                                    </div>
                                    基礎
                                    <a href="#"><img src="img/icon/right.png" class="rightIcon"></a>
                                </div>
                                <ul class="dl-submenu">

                                    <li>
                                        <div class="menuItem">
                                            <div class="checkbox folderCheckBoxWrapper">
                                                <label>
                                                    <input type="checkbox" class="folderCheckBox" name="selectedNo[]"
                                                           value="{$result['p_id']}">
                                                </label>
                                            </div>
                                            2015-07-15
                                        </div>
                                    </li>
                                    <li>
                                        <div class="menuItem">
                                            <div class="checkbox folderCheckBoxWrapper">
                                                <label>
                                                    <input type="checkbox" class="folderCheckBox" name="selectedNo[]"
                                                           value="{$result['p_id']}">
                                                </label>
                                            </div>
                                            2015-07-14
                                        </div>
                                    </li>
                                    <li>
                                        <div class="menuItem">
                                            <div class="checkbox folderCheckBoxWrapper">
                                                <label>
                                                    <input type="checkbox" class="folderCheckBox" name="selectedNo[]"
                                                           value="{$result['p_id']}">
                                                </label>
                                            </div>
                                            2015-07-13
                                        </div>
                                    </li>
                                    <li>
                                        <div class="menuItem">
                                            <div class="checkbox folderCheckBoxWrapper">
                                                <label>
                                                    <input type="checkbox" class="folderCheckBox" name="selectedNo[]"
                                                           value="{$result['p_id']}">
                                                </label>
                                            </div>
                                            2015-07-12
                                        </div>
                                    </li>
                                    <li>
                                        <div class="menuItem">
                                            <div class="checkbox folderCheckBoxWrapper">
                                                <label>
                                                    <input type="checkbox" class="folderCheckBox" name="selectedNo[]"
                                                           value="{$result['p_id']}">
                                                </label>
                                            </div>
                                            2015-07-11
                                        </div>
                                    </li>
                                    <li>
                                        <div class="menuItem">
                                            <div class="checkbox folderCheckBoxWrapper">
                                                <label>
                                                    <input type="checkbox" class="folderCheckBox" name="selectedNo[]"
                                                           value="{$result['p_id']}">
                                                </label>
                                            </div>
                                            2015-07-10
                                        </div>
                                    </li>
                                    <li>
                                        <div class="menuItem">
                                            <div class="checkbox folderCheckBoxWrapper">
                                                <label>
                                                    <input type="checkbox" class="folderCheckBox" name="selectedNo[]"
                                                           value="{$result['p_id']}">
                                                </label>
                                            </div>
                                            2015-07-08
                                        </div>
                                    </li>
                                    <li>
                                        <div class="menuItem">
                                            <div class="checkbox folderCheckBoxWrapper">
                                                <label>
                                                    <input type="checkbox" class="folderCheckBox" name="selectedNo[]"
                                                           value="{$result['p_id']}">
                                                </label>
                                            </div>
                                            2015-07-07
                                        </div>
                                    </li>
                                    <li>
                                        <div class="menuItem">
                                            <div class="checkbox folderCheckBoxWrapper">
                                                <label>
                                                    <input type="checkbox" class="folderCheckBox" name="selectedNo[]"
                                                           value="{$result['p_id']}">
                                                </label>
                                            </div>
                                            2015-07-06
                                        </div>
                                    </li>
                                    <li>
                                        <div class="menuItem">
                                            <div class="checkbox folderCheckBoxWrapper">
                                                <label>
                                                    <input type="checkbox" class="folderCheckBox" name="selectedNo[]"
                                                           value="{$result['p_id']}">
                                                </label>
                                            </div>
                                            2015-07-05
                                        </div>
                                    </li>
                                    <li>
                                        <div class="menuItem">
                                            <div class="checkbox folderCheckBoxWrapper">
                                                <label>
                                                    <input type="checkbox" class="folderCheckBox" name="selectedNo[]"
                                                           value="{$result['p_id']}">
                                                </label>
                                            </div>
                                            2015-07-04
                                        </div>
                                    </li>
                                    <li>
                                        <div class="menuItem">
                                            <div class="checkbox folderCheckBoxWrapper">
                                                <label>
                                                    <input type="checkbox" class="folderCheckBox" name="selectedNo[]"
                                                           value="{$result['p_id']}">
                                                </label>
                                            </div>
                                            2015-07-03
                                        </div>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <div class="menuItem">
                            <div class="checkbox folderCheckBoxWrapper">
                                <label>
                                    <input type="checkbox" class="folderCheckBox" name="selectedNo[]"
                                           value="{$result['p_id']}">
                                </label>
                            </div>
                            コンクリート工事
                            <a href="#"><img src="img/icon/right.png" class="rightIcon"></a>
                        </div>
                        <ul class="dl-submenu">

                            <li>
                                <div class="menuItem">
                                    <div class="checkbox folderCheckBoxWrapper">
                                        <label>
                                            <input type="checkbox" class="folderCheckBox" name="selectedNo[]"
                                                   value="{$result['p_id']}">
                                        </label>
                                    </div>
                                    1F
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <div class="menuItem">
                            <div class="checkbox folderCheckBoxWrapper">
                                <label>
                                    <input type="checkbox" class="folderCheckBox" name="selectedNo[]"
                                           value="{$result['p_id']}">
                                </label>
                            </div>
                            鉄骨工事
                            <a href="#"><img src="img/icon/right.png" class="rightIcon"></a>
                        </div>
                        <ul class="dl-submenu">

                            <li>
                                <div class="menuItem">
                                    <div class="checkbox folderCheckBoxWrapper">
                                        <label>
                                            <input type="checkbox" class="folderCheckBox" name="selectedNo[]"
                                                   value="{$result['p_id']}">
                                        </label>
                                    </div>
                                    1F
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <div class="menuItem">
                            <div class="checkbox folderCheckBoxWrapper">
                                <label>
                                    <input type="checkbox" class="folderCheckBox" name="selectedNo[]"
                                           value="{$result['p_id']}">
                                </label>
                            </div>
                            防水工事
                            <a href="#"><img src="img/icon/right.png" class="rightIcon"></a>
                        </div>
                        <ul class="dl-submenu">

                            <li>
                                <div class="menuItem">1F</div>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <div class="menuItem">
                            <div class="checkbox folderCheckBoxWrapper">
                                <label>
                                    <input type="checkbox" class="folderCheckBox" name="selectedNo[]"
                                           value="{$result['p_id']}">
                                </label>
                            </div>
                            ALC工事
                            <a href="#"><img src="img/icon/right.png" class="rightIcon"></a>
                        </div>
                        <ul class="dl-submenu">

                            <li>
                                <div class="menuItem">
                                    <div class="checkbox folderCheckBoxWrapper">
                                        <label>
                                            <input type="checkbox" class="folderCheckBox" name="selectedNo[]"
                                                   value="{$result['p_id']}">
                                        </label>
                                    </div>
                                    1F
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <div class="menuItem">
                            <div class="checkbox folderCheckBoxWrapper">
                                <label>
                                    <input type="checkbox" class="folderCheckBox" name="selectedNo[]"
                                           value="{$result['p_id']}">
                                </label>
                            </div>
                            石工事
                            <a href="#"><img src="img/icon/right.png" class="rightIcon"></a>
                        </div>
                        <ul class="dl-submenu">

                            <li>
                                <div class="menuItem">
                                    <div class="checkbox folderCheckBoxWrapper">
                                        <label>
                                            <input type="checkbox" class="folderCheckBox" name="selectedNo[]"
                                                   value="{$result['p_id']}">
                                        </label>
                                    </div>
                                    1F
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <div class="menuItem">
                            <div class="checkbox folderCheckBoxWrapper">
                                <label>
                                    <input type="checkbox" class="folderCheckBox" name="selectedNo[]"
                                           value="{$result['p_id']}">
                                </label>
                            </div>
                            タイル工事
                            <a href="#"><img src="img/icon/right.png" class="rightIcon"></a>
                        </div>
                        <ul class="dl-submenu">

                            <li>
                                <div class="menuItem">
                                    <div class="checkbox folderCheckBoxWrapper">
                                        <label>
                                            <input type="checkbox" class="folderCheckBox" name="selectedNo[]"
                                                   value="{$result['p_id']}">
                                        </label>
                                    </div>
                                    1F
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <div class="menuItem">
                            <div class="checkbox folderCheckBoxWrapper">
                                <label>
                                    <input type="checkbox" class="folderCheckBox" name="selectedNo[]"
                                           value="{$result['p_id']}">
                                </label>
                            </div>
                            屋根及び樋工事
                            <a href="#"><img src="img/icon/right.png" class="rightIcon"></a>
                        </div>
                        <ul class="dl-submenu">

                            <li>
                                <div class="menuItem">
                                    <div class="checkbox folderCheckBoxWrapper">
                                        <label>
                                            <input type="checkbox" class="folderCheckBox" name="selectedNo[]"
                                                   value="{$result['p_id']}">
                                        </label>
                                    </div>
                                    1F
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <div class="menuItem">
                            <div class="checkbox folderCheckBoxWrapper">
                                <label>
                                    <input type="checkbox" class="folderCheckBox" name="selectedNo[]"
                                           value="{$result['p_id']}">
                                </label>
                            </div>
                            金属工事
                            <a href="#"><img src="img/icon/right.png" class="rightIcon"></a>
                        </div>
                        <ul class="dl-submenu">

                            <li>
                                <div class="menuItem">
                                    <div class="checkbox folderCheckBoxWrapper">
                                        <label>
                                            <input type="checkbox" class="folderCheckBox" name="selectedNo[]"
                                                   value="{$result['p_id']}">
                                        </label>
                                    </div>
                                    1F
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <div class="menuItem">
                            <div class="checkbox folderCheckBoxWrapper">
                                <label>
                                    <input type="checkbox" class="folderCheckBox" name="selectedNo[]"
                                           value="{$result['p_id']}">
                                </label>
                            </div>
                            左官工事
                            <a href="#"><img src="img/icon/right.png" class="rightIcon"></a>
                        </div>
                        <ul class="dl-submenu">

                            <li>
                                <div class="menuItem">
                                    <div class="checkbox folderCheckBoxWrapper">
                                        <label>
                                            <input type="checkbox" class="folderCheckBox" name="selectedNo[]"
                                                   value="{$result['p_id']}">
                                        </label>
                                    </div>
                                    1F
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <div class="menuItem">
                            <div class="checkbox folderCheckBoxWrapper">
                                <label>
                                    <input type="checkbox" class="folderCheckBox" name="selectedNo[]"
                                           value="{$result['p_id']}">
                                </label>
                            </div>
                            建具工事
                            <a href="#"><img src="img/icon/right.png" class="rightIcon"></a>
                        </div>
                        <ul class="dl-submenu">

                            <li>
                                <div class="menuItem">
                                    <div class="checkbox folderCheckBoxWrapper">
                                        <label>
                                            <input type="checkbox" class="folderCheckBox" name="selectedNo[]"
                                                   value="{$result['p_id']}">
                                        </label>
                                    </div>
                                    1F
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <div class="menuItem">
                            <div class="checkbox folderCheckBoxWrapper">
                                <label>
                                    <input type="checkbox" class="folderCheckBox" name="selectedNo[]"
                                           value="{$result['p_id']}">
                                </label>
                            </div>
                            CW工事
                            <a href="#"><img src="img/icon/right.png" class="rightIcon"></a>
                        </div>
                        <ul class="dl-submenu">

                            <li>
                                <div class="menuItem">
                                    <div class="checkbox folderCheckBoxWrapper">
                                        <label>
                                            <input type="checkbox" class="folderCheckBox" name="selectedNo[]"
                                                   value="{$result['p_id']}">
                                        </label>
                                    </div>
                                    1F
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <div class="menuItem">
                            <div class="checkbox folderCheckBoxWrapper">
                                <label>
                                    <input type="checkbox" class="folderCheckBox" name="selectedNo[]"
                                           value="{$result['p_id']}">
                                </label>
                            </div>
                            塗装工事
                            <a href="#"><img src="img/icon/right.png" class="rightIcon"></a>
                        </div>
                        <ul class="dl-submenu">

                            <li>
                                <div class="menuItem">
                                    <div class="checkbox folderCheckBoxWrapper">
                                        <label>
                                            <input type="checkbox" class="folderCheckBox" name="selectedNo[]"
                                                   value="{$result['p_id']}">
                                        </label>
                                    </div>
                                    1F
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <div class="menuItem">
                            <div class="checkbox folderCheckBoxWrapper">
                                <label>
                                    <input type="checkbox" class="folderCheckBox" name="selectedNo[]"
                                           value="{$result['p_id']}">
                                </label>
                            </div>
                            内装工事
                            <a href="#"><img src="img/icon/right.png" class="rightIcon"></a>
                        </div>
                        <ul class="dl-submenu">

                            <li>
                                <div class="menuItem">
                                    <div class="checkbox folderCheckBoxWrapper">
                                        <label>
                                            <input type="checkbox" class="folderCheckBox" name="selectedNo[]"
                                                   value="{$result['p_id']}">
                                        </label>
                                    </div>
                                    1F
                                </div>
                            </li>
                        </ul>
                    </li>
            </div>

        </div>

        <!--        写真一覧-->
        <div class="col-xs-9 test2">

            <!--               写真操作メニュー-->
            <div class="row">
                <!--                ボタン類-->
                <div class="col-xs-12 text-right" id="btnMenu">
                    <!--                    写真追加ボタン-->
                    <span class="glyphicon glyphicon-picture iconBtn" id="addBtn" data-toggle="modal"
                          data-target="#updatePhoto"></span>
                    <!--             削除ボタン-->
                    <div class="selectedNeed">
                        <span class="glyphicon glyphicon-trash iconBtn" id="deleteBtn" data-toggle="modal"
                              data-target="#deletePhoto"></span>
                    </div>
                    <!--                    編集ボタン-->
                    <div class="selectedNeed">
                        <span class="glyphicon glyphicon-edit iconBtn" id="editBtn" data-toggle="modal"
                              data-target="#editPhoto"></span>
                    </div>
                    <!--ダウンロードボタン-->
                    <div class="selectedNeed">
                        <span class="glyphicon glyphicon-download-alt iconBtn" id="downloadBtn"></span>
                    </div>
                    <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>" style="display: none">
                        <input type="hidden" name="post_flg" value="3">
                        <input type="submit" name="downloadPhoto" id="downloadPhoto" style="display: none;">
                    </form>
                    <!--                    ソートボタン-->
                    <span class="<?= $sortIcon . " " . $sortNum . " iconBtn" ?>" id="sortBtn"></span>
                    <!--                    表示切り替え-->
                    <!--                    <select name="listType" id="listType" class="input-sm">-->
                    <!--                        <option value="1">中</option>-->
                    <!--                        <option value="2">大</option>-->
                    <!--                        <option value="3">小</option>-->
                    <!--                    </select>-->
                    <span class="<?= $typeIcon . " " . $typeNum . " iconBtn" ?>" id="typeBtn"></span>
                    <!--                    更新ボタン-->
                    <span class="glyphicon glyphicon-refresh iconBtn" id="refleshBtn"></span>

                    <div class="selectedNeed">
                        <span class="glyphicon glyphicon-list-alt iconBtn" data-toggle="modal"
                              data-target="#newAlbumWindow" id="albumBtn"></span>
                    </div>


                    <!--                    詳細検索-->
                    <!--                    <span class="glyphicon glyphicon-search iconBtn" id="detailSearchBtn"></span>-->
                    <!--                検索-->
                    <div id="searchMenu" class="form-inline">
                        <input type="text" class="form-control" name="searchForm" id="searchForm"
                               placeholder="Search">
                        <button class="btn btn-info btn-sm" id="searchBtn">Search</button>
                        <!--                </div>-->
                    </div>
                </div>
            </div>

            <!--                写真一覧画面-->
            <div class="row">
                <div class="col-xs-12" id="photoList">
                    <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>" id="photoOperation"
                          class="form-inline">
                        <?= $view ?>
                        <input type="hidden" name="post_flg" id="checkBoxEvent" value="2">
                        <input type="submit" name="submitChecked" id="submitChecked" style="display:none">
                </div>
            </div>
        </div>
    </div>
</div>


<!--フォトインスペクタ用のモーダルウインドウ-->
<div class="modal fade" id="photoinspectorWindow">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">閉じる</span>
                </button>
                <h4 class="modal-title">フォトインスペクタ</h4>
            </div>
            <div class="modal-body">
                表示する写真の構成を選択してください。<a href="" data-dismiss="modal" data-toggle="modal"
                                      data-target="#infoPhotoinspector">使い方はこちら</a>

                <div class="selectWrapper" id="firstFoloderWrap">
                    <label for="firstFolder">第１階層：</label>
                    <select name="firstFolder" id="firstFolder" class="selectpicker">
                        <option value="1-1">撮影年月日</option>
                        <option value="1-2">写真大分類</option>
                        <option value="1-3">写真区分</option>
                        <option value="1-4" selected>工種</option>
                        <option value="1-5">種別</option>
                        <option value="1-6">細別</option>
                        <option value="1-7">撮影箇所</option>
                        <option value="1-8">撮影時期</option>
                        <option value="1-9">施工状況</option>
                        <option value="1-10">施工状況詳細</option>
                        <option value="1-11">立会者</option>
                        <option value="1-12">階</option>
                        <option value="1-13">x軸通り芯</option>
                        <option value="1-14">y軸通り芯</option>
                    </select>
                </div>
                <div class="selectWrapper" id="secondFoloderWrap">
                    <label for="secondFolder">第２階層：</label>
                    <select name="secondFolder" id="secondFolder" class="selectpicker">
                        <option value="2-1">撮影年月日</option>
                        <option value="2-2">写真大分類</option>
                        <option value="2-3">写真区分</option>
                        <option value="2-4" selected>工種</option>
                        <option value="2-5">種別</option>
                        <option value="2-6">細別</option>
                        <option value="2-7">撮影箇所</option>
                        <option value="2-8">撮影時期</option>
                        <option value="2-9">施工状況</option>
                        <option value="2-10">施工状況詳細</option>
                        <option value="2-11">立会者</option>
                        <option value="2-12">階</option>
                        <option value="2-13">x軸通り芯</option>
                        <option value="2-14">y軸通り芯</option>
                    </select>
                    <button class="btn btn-danger btn-sm minusBtn" id="secondMinus">−</button>
                </div>
                <div class="selectWrapper" id="thirdFoloderWrap">
                    <label for="thirdFolder">第３階層：</label>
                    <select name="thirdFolder" id="thirdFolder" class="selectpicker">
                        <option value="3-1">撮影年月日</option>
                        <option value="3-2">写真大分類</option>
                        <option value="3-3">写真区分</option>
                        <option value="3-4" selected>工種</option>
                        <option value="3-5">種別</option>
                        <option value="3-6">細別</option>
                        <option value="3-7">撮影箇所</option>
                        <option value="3-8">撮影時期</option>
                        <option value="3-9">施工状況</option>
                        <option value="3-10">施工状況詳細</option>
                        <option value="3-11">立会者</option>
                        <option value="3-12">階</option>
                        <option value="3-13">x軸通り芯</option>
                        <option value="3-14">y軸通り芯</option>
                    </select>
                    <button class="btn btn-danger btn-sm minusBtn" id="thirdMinus">−</button>
                </div>
                <div class="selectWrapper" id="fourthFoloderWrap">
                    <label for="fourthFolder">第４階層：</label>
                    <select name="fourthFolder" id="fourthFolder" class="selectpicker">
                        <option value="4-1">撮影年月日</option>
                        <option value="4-2">写真大分類</option>
                        <option value="4-3">写真区分</option>
                        <option value="4-4" selected>工種</option>
                        <option value="4-5">種別</option>
                        <option value="4-6">細別</option>
                        <option value="4-7">撮影箇所</option>
                        <option value="4-8">撮影時期</option>
                        <option value="4-9">施工状況</option>
                        <option value="4-10">施工状況詳細</option>
                        <option value="4-11">立会者</option>
                        <option value="4-12">階</option>
                        <option value="4-13">x軸通り芯</option>
                        <option value="4-14">y軸通り芯</option>
                    </select>
                    <button class="btn btn-danger btn-sm minusBtn" id="fourthMinus">−</button>
                </div>
                <div class="selectWrapper" id="fifthFoloderWrap">
                    <label for="fifthFolder">第５階層：</label>
                    <select name="fifthFolder" id="fifthFolder" class="selectpicker">
                        <option value="4-1">撮影年月日</option>
                        <option value="4-2">写真大分類</option>
                        <option value="4-3">写真区分</option>
                        <option value="4-4" selected>工種</option>
                        <option value="4-5">種別</option>
                        <option value="4-6">細別</option>
                        <option value="4-7">撮影箇所</option>
                        <option value="4-8">撮影時期</option>
                        <option value="4-9">施工状況</option>
                        <option value="4-10">施工状況詳細</option>
                        <option value="4-11">立会者</option>
                        <option value="4-12">階</option>
                        <option value="4-13">x軸通り芯</option>
                        <option value="4-14">y軸通り芯</option>
                    </select>
                    <button class="btn btn-danger btn-sm minusBtn" id="fifthMinus">−</button>
                </div>
                <div class="selectWrapper">
                    <button class="btn btn-primary" id="folderPlus">＋</button>
                </div>


            </div>
            <div class="modal-footer">

                <button class="btn btn-info btn-sm" id='photoinspectorOkBtn'>OK</button>
                <button class="btn btn-danger btn-sm" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<!--フォトインスペクタマニュアルモーダルウインドウ-->
<div class="modal fade" id="infoPhotoinspector">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">閉じる</span>
                </button>
                <h4 class="modal-title">フォトインスペクタとは？</h4>
            </div>
            <div class="modal-body" id="dropArea">
                <p>フォルダ構造を変化させることで、必要な写真を簡単に見つけることができます。</p>

                <p class="inspectorDes">例）鉄筋工事を撮影した写真のうち3階で撮影日ごとに確認したかったら・・・</p>
                <img src="img/photoinspector1.png" width="550px">

                <p class="inspectorDes">例2）2階で誰がどの場所を撮影したのか確認したかったら・・・</p>
                <img src="img/photoinspector2.png" width="550px">
            </div>
            <div class="modal-footer">
                <button class="btn btn-success btn-sm" data-dismiss="modal" data-toggle="modal"
                        data-target="#photoinspectorWindow">使用する
                </button>
                <button class="btn btn-danger btn-sm" data-dismiss="modal">閉じる</button>
            </div>
        </div>
    </div>
</div>


<!--編集用のモーダルウインドウ-->
<div class="modal fade" id="editPhoto">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">閉じる</span>
                </button>
                <h4 class="modal-title">編集(<span id="countPhoto"></span>枚の写真を選択中)</h4>
            </div>
            <div class="modal-body">

                <!--                タブ-->
                <div class="btn-group btn-group-justified">
                    <a href="#photoInfo1" data-toggle="tab" class="btn btn-default">情報１</a>
                    <a href="#photoInfo2" data-toggle="tab" class="btn btn-default">情報２</a>
                    <a href="#photoPlace" data-toggle="tab" class="btn btn-default">位置情報</a>
                    <a href="#photoReference" data-toggle="tab" class="btn btn-default">参考図</a>
                </div>

                <!--                タブの中身-->
                <!--                情報１-->
                <div id="myTabContent" class="tab-content">
                    <div class="tab-pane fade active in form-horizontal" id="photoInfo1">
                        <div class="form-group">
                            <label for="inputEmail" class="col-lg-2 control-label">写真タイトル</label>

                            <div class="col-lg-10">
                                <input type="text" class="form-control" placeholder="写真タイトル">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail" class="col-lg-2 control-label">撮影箇所</label>

                            <div class="col-lg-10">
                                <select class="pcombobox">
                                    <option value="" selected>選択してください</option>
                                    <option value="1">柱</option>
                                    <option value="2">梁</option>
                                    <option value="2">壁</option>
                                    <option value="2">スラブ</option>
                                    <option value="2">床</option>
                                    <option value="2">階段</option>
                                    <option value="2">パラペット</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail" class="col-lg-2 control-label">写真区分</label>

                            <div class="col-lg-10">
                                <select class="pcombobox">
                                    <option value="" selected>選択してください</option>
                                    <option value="1">着手前及び完成写真</option>
                                    <option value="2">施工状況写真</option>
                                    <option value="3">安全管理写真</option>
                                    <option value="4">使用材料写真</option>
                                    <option value="5">品質管理写真</option>
                                    <option value="6">出来形管理写真</option>
                                    <option value="7">災害写真</option>
                                    <option value="8">事故写真</option>
                                    <option value="9">その他</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputEmail" class="col-lg-2 control-label">工種</label>

                            <div class="col-lg-10">
                                <select class="pcombobox">
                                    <option value="" selected>選択してください</option>
                                    <option value="1">鉄筋工事</option>
                                    <option value="2">コンクリート工事</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputEmail" class="col-lg-2 control-label">種別</label>

                            <div class="col-lg-10">
                                <select class="pcombobox">
                                    <option value="" selected>選択してください</option>
                                    <option value="1">鉄筋工事</option>
                                    <option value="2">コンクリート工事</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputEmail" class="col-lg-2 control-label">細別</label>

                            <div class="col-lg-10">
                                <select class="pcombobox">
                                    <option value="" selected>選択してください</option>
                                    <option value="1">鉄筋工事</option>
                                    <option value="2">コンクリート工事</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputEmail" class="col-lg-2 control-label">撮影時期</label>

                            <div class="col-lg-10">
                                <select class="pcombobox">
                                    <option value="" selected>選択してください</option>
                                    <option value="1">着工前</option>
                                    <option value="1">施工中</option>
                                    <option value="1">打設前</option>
                                    <option value="1">打設後</option>
                                    <option value="1">処理後</option>
                                    <option value="1">補修後</option>
                                    <option value="1">確認時</option>
                                    <option value="1">完了時</option>
                                    <option value="1">搬入前</option>
                                    <option value="1">搬入時</option>
                                    <option value="1">搬出前</option>
                                    <option value="1">搬出時</option>
                                    <option value="1">一本締め後</option>
                                    <option value="1">本締め後</option>
                                    <option value="1">測定時</option>
                                    <option value="1">試験中</option>
                                    <option value="1">検査中</option>
                                    <option value="1">保管中</option>
                                    <option value="1">撤去後</option>
                                    <option value="1">廃棄時</option>
                                </select>
                            </div>
                        </div>
                        <!---->
                        <!--                        <div class="form-group">-->
                        <!--                            <label for="textArea" class="col-lg-2 control-label">撮影情報予備</label>-->
                        <!---->
                        <!--                            <div class="col-lg-10">-->
                        <!--                                <textarea class="form-control" rows="2" id="textArea"></textarea>-->
                        <!--                                <span class="help-block">詳細な情報はこちらに記載下さい</span>-->
                        <!--                            </div>-->
                        <!---->
                        <!--                        </div>-->
                        <div class="form-group">
                            <label for="inputEmail" class="col-lg-2 control-label">撮影年月日</label>

                            <div class="col-lg-10">
                                <input type="text" class="form-control datepicker" placeholder="撮影年月日">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail" class="col-lg-2 control-label">立会者</label>

                            <div class="col-lg-10">
                                <select class="pcombobox">
                                    <option value="" selected>選択してください</option>
                                    <option value="1">中島貴春</option>
                                    <option value="2">藤田雄太</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail" class="col-lg-2 control-label">工事名</label>

                            <div class="col-lg-10">
                                <select class="pcombobox">
                                    <option value="" selected>選択してください</option>
                                    <option value="1">赤羽一丁目一番地再開発計画</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!--情報２-->
                    <div class="tab-pane fade form-horizontal" id="photoInfo2">
                        <div class="form-group">
                            <label for="inputEmail" class="col-lg-2 control-label">写真大分類</label>

                            <div class="col-lg-10">
                                <select class="pcombobox">
                                    <option value="" selected>選択してください</option>
                                    <option value="1">工事</option>
                                    <option value="1">測量</option>
                                    <option value="2">調査</option>
                                    <option value="2">地質</option>
                                    <option value="2">広報</option>
                                    <option value="2">設計</option>
                                    <option value="2">その他</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputEmail" class="col-lg-2 control-label">工種区分予備</label>

                            <div class="col-lg-10">
                                <input type="text" class="form-control" placeholder="工種区分予備">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail" class="col-lg-2 control-label">施工管理値</label>

                            <div class="col-lg-10">
                                <input type="text" class="form-control" placeholder="施工管理値">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail" class="col-lg-2 control-label">受注者説明文</label>

                            <div class="col-lg-10">
                                <input type="text" class="form-control" placeholder="受注者説明文">
                            </div>
                        </div>
                    </div>
                    <!--                    位置情報-->
                    <div class="tab-pane fade form-horizontal" id="photoPlace">
                        <p style="text-align: center">写真を撮影した場所を図面上でクリックしてください</p>
                        <img src="img/sample/1F.jpg" width="800"/>

                        <div class="form-group">
                            <label for="inputEmail" class="col-lg-2 control-label">撮影階</label>

                            <div class="col-lg-10">
                                <select class="pcombobox">
                                    <option value="" selected>選択してください</option>
                                    <option value="1">RF</option>
                                    <option value="2">3F+720</option>
                                    <option value="2">3F</option>
                                    <option value="2">2F</option>
                                    <option value="2">1F</option>
                                    <option value="2">BF</option>
                                    <option value="2">基礎</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail" class="col-lg-2 control-label">X通り</label>

                            <div class="col-lg-10">
                                <input type="text" class="form-control" placeholder="X通り">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail" class="col-lg-2 control-label">Y通り</label>

                            <div class="col-lg-10">
                                <input type="text" class="form-control" placeholder="Y通り">
                            </div>
                        </div>

                    </div>
                    <!--                    参考図-->
                    <div class="tab-pane fade form-horizontal" id="photoReference">
                        <div class="form-group">
                            <label for="inputEmail" class="col-lg-2 control-label">参考図タイトル</label>

                            <div class="col-lg-10">
                                <input type="text" class="form-control" placeholder="参考図タイトル">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputFile" class="col-lg-2 control-label">参考図ファイル</label>

                            <div class="col-lg-10">
                                <input type="text" readonly="" class="form-control floating-label"
                                       placeholder="ファイルの選択">
                                <input type="file" id="inputFile" multiple="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="textArea" class="col-lg-2 control-label">付加情報予備</label>

                            <div class="col-lg-10">
                                <textarea class="form-control" rows="2" id="textArea"></textarea>
                                <span class="help-block">参考図を説明する内容があれば記載下さい</span>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
            <div class="modal-footer">

                <button class="btn btn-info btn-sm" id="editOkBtn">OK</button>
                </form>

                <button class="btn btn-danger btn-sm" data-dismiss="modal">キャンセル</button>
            </div>
        </div>
    </div>
</div>

<!--アップロード用のモーダルウインドウ-->
<div class="modal fade" id="updatePhoto">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">閉じる</span>
                </button>
                <h4 class="modal-title">写真のアップロード</h4>
            </div>
            <div class="modal-body" id="dropArea">
                Photoructionにアップロードする写真を選択します。一度に複数の写真を選択できます。このページの任意の場所に写真をドラッグ アンド ドロップしてアップロードすることもできます。
            </div>
            <div class="modal-footer">

                <button class="btn btn-info btn-sm" id='addOkBtn'>写真の選択</button>
                <form class="form-inline" method="post" action="<?= $_SERVER['PHP_SELF'] ?>"
                      enctype="multipart/form-data" id="send_file" style="display: none">
                    <input type="file" accept="image/*" name="addPhoto[]" id="addPhoto"
                           onchange="$('#submitPhoto').click()" style="display: none" multiple>
                    <input type="hidden" name="post_flg" value="1">
                    <input type="submit" name="submitPhoto" id="submitPhoto" style="display:none">
                </form>

                <button class="btn btn-danger btn-sm" data-dismiss="modal">閉じる</button>
            </div>
        </div>
    </div>
</div>

<!--削除確認モーダルウインドウ-->
<div class="modal fade" id="deletePhoto">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">閉じる</span>
                </button>
                <h4 class="modal-title"><span id="countPhoto"></span>枚の写真を削除しますか？</h4>
            </div>
            <div class="modal-body" id="dropArea">
                photoructionから写真を削除してもよろしいですか？<br>
                ※写真に付加された情報、参考図も削除されます。
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger btn-sm" id='deleteOkBtn'>削除</button>
                <button class="btn btn-default btn-sm" data-dismiss="modal">閉じる</button>
            </div>
        </div>
    </div>
</div>


<!--新規アルバム作成ウインドウ-->
<div class="modal fade" id="newAlbumWindow">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">閉じる</span>
                </button>
                <h4 class="modal-title">新しくアルバムを作成します。</h4>
            </div>
            <div class="modal-body">
                <div id="myTabContent" class="tab-content">
                    <div class="tab-pane fade active in form-horizontal" id="firstPage">
                        <div class="form-group">
                            <label for="textArea" class="col-xs-2 control-label">タイトル</label>
                            <div class="col-xs-10">
                                <input type="text" class="form-control" placeholder="タイトル">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="select" class="col-lg-2 control-label">テンプレート</label>

                            <div class="col-lg-10">
                                <select class="form-control" id="select">
                                    <option>テンプレート1</option>
                                    <option>テンプレート2</option>
                                    <option>テンプレート3</option>
                                </select>
                            </div>
                        </div>
                        <div class="text-center"><img src="img/sample/sample_album.jpg" width="500px"/></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-info btn-sm" data-dismiss="modal">作成</button>
                    <button class="btn btn-danger btn-sm" data-dismiss="modal">キャンセル</button>
                </div>
            </div>
        </div>
    </div>
</div>


<!--<!--チャットウインドウ-->-->
<!--<div id="chatContainer">-->
<!--    <div id="chatWindow">-->
<!--        <div id="message">-->
<!--            <ul id="board">-->
<!---->
<!--            </ul>-->
<!---->
<!--        </div>-->
<!--        <div id="field">-->
<!--            <input name="nameText" id="nameText" type="text" placeholder="Name" class="form-control">-->
<!--            <textarea name="msg" id="msg" cols="30" rows="4" placeholder="Add Comment..."-->
<!--                      class="form-control"></textarea>-->
<!--            <button name="button" class="btn btn-primary btn-block" onClick="clickSend()">send message!</button>-->
<!--        </div>-->
<!--    </div>-->
<!---->
<!--    <div id="notification">-->
<!--        <button id="toggleBtn" class="btn btn-danger btn-block">Message Board</button>-->
<!--    </div>-->
<!--</div>-->
<script src="js/modernizr.custom.js"></script>
<script src="js/jquery-2.1.4.min.js"></script>
<script src="js/jquery-ui.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-select.min.js"></script>
<script src="js/jquery.dlmenu.js"></script>
<script src="js/ripples.min.js"></script>
<script src="js/material.min.js"></script>
<script src="http://cdn.mlkcca.com/v2.0.0/milkcocoa.js"></script>
<script src="js/chat.js"></script>
<script src="js/common.js"></script>
<script>
    $(document).ready(function () {
        c_init();
    });
</script>
<script src="js/main.js"></script>
</body>

</html>