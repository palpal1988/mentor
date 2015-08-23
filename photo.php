<?php
include("common.php");


//データベースに接続
$pdo = db_con();

//DB文字コードを指定
$stmt = $pdo->query('SET NAMES utf8');

//アップデートの場合
//if (isset($_GET['updata'])) {
//
//    $title = $_POST['title'];
//    $photographer = $_POST['photographer'];
//    $koujiShu = $_POST['koujiShu'];
//    $freeText = $_POST['freeText'];
//    $resistDate = $_POST['resistDate'];
//
//    $stmt = $pdo->prepare('UPDATE photo SET title=:title, photographer=:photographer, koujiShu=:koujiShu, freeText=:freeText, resistDate=:resistDate WHERE id=:id');
//    $stmt->bindValue(':title', $title);
//    $stmt->bindValue(':photographer', $photographer);
//    $stmt->bindValue(':koujiShu', $koujiShu);
//    $stmt->bindValue(':freeText', $freeText);
//    $stmt->bindValue(':resistDate', $resistDate);
//    $stmt->bindValue(':id', $_GET['pid']);
//
//    $status = $stmt->execute();
//
//    if ($status == false) {
//        echo "SQLエラー";
//        exit;
//    }
//    //２重送信の防止防止
//    header("Location: photo.php?pid=" . $_GET['pid'] . "&updated=1");
//}
//対象のidの写真を選択する
$stmt = $pdo->prepare('SELECT * FROM photo WHERE p_id=:p_id');
$stmt->bindValue(':p_id', $_GET['photo']);
$stats = $stmt->execute();

while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $p_fileName = $result['p_fileName'];
    $p_date = $result['p_date'];
    $p_resistDate = $result['p_resistDate'];
    $p_title = $result['p_title'];
    $p_koujiName = $result['p_koujiName'];
    $p_koujiShu = $result['p_koujiShu'];
    $p_class = $result['p_class'];
    $p_subClass = $result['p_subClass'];
    $p_koushuYobi = $result['p_koushuYobi'];
    $p_place = $result['p_place'];
    $p_period = $result['p_period'];
    $p_koujiStatus = $result['p_koujiStatus'];
    $p_infoYobi = $result['p_infoYobi'];
    $p_photographer = $result['p_photographer'];
    $p_company = $result['p_company'];
    $p_description = $result['p_description'];
    $p_floor = $result['p_floor'];
    $p_xStreet = $result['p_xStreet'];
    $p_yStreet = $result['p_yStreet'];
    $p_starFlg = $result['p_starFlg'];
    $p_blackBoardFlg = $result['p_blackBoardFlg'];
}
?>


<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Photoruction -写真-</title>
    <link rel="stylesheet" type="text/css" href="css/jquery-ui.min.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap-select.min.css">
    <link rel="stylesheet" type="text/css" href="css/roboto.min.css">
    <link rel="stylesheet" type="text/css" href="css/material-fullpalette.min.css">
    <link rel="stylesheet" type="text/css" href="css/ripples.min.css">
    <link rel="stylesheet" type="text/css" href="css/non-responsive.css">
    <link rel="stylesheet" type="text/css" href="css/chat.css">
    <link rel="stylesheet" type="text/css" href="css/common.css">
    <link href="css/photo.css" rel="stylesheet">
    <link rel="stylesheet" href="css/main.css">
</head>

<body>
<!--   ナビゲーション-->
<?= navigationSelect(PHOTO_LIST) ?>

<!--    本体部分-->
<div class="container">
    <!--    写真部分-->
    <div class="row">
        <div class="col-xs-8">
            <div class="col-xs-3">前の写真</div>
            <div class="col-xs-6 text-center"><p><a href="main.php">写真一覧へ戻る</a></p></div>
            <div class="col-xs-3 text-right"><p><a href="main.php">次の写真</a></p></div>
            <img src="photo/<?= $p_fileName ?>" class="img-responsive"/>
        </div>
    </div>
    <!--    メニュー部分/-->
    <div id="wrapMenu">
        <div id="menu">

            <div class="btn-group btn-group-justified">
                <a href="#menuPhotoInfo1" data-toggle="tab" class="btn btn-default">情報１</a>
                <a href="#menuPhotoInfo2" data-toggle="tab" class="btn btn-default">情報２</a>
                <a href="#menuPhotoReference" data-toggle="tab" class="btn btn-default" >編集</a>

                <div class="btn-group">
                    <a href="bootstrap-elements.html" data-target="#" class="btn btn-default dropdown-toggle"
                       data-toggle="dropdown">
                        操作
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="" data-toggle="modal" data-target="#editPhoto">編集</a></li>
                        <li><a href="javascript:void(0)">黒板無</a></li>
                        <li><a href="javascript:void(0)">URL共有</a></li>
                        <li><a href="javascript:void(0)">Exif情報</a></li>
                        <li><a href="javascript:void(0)">削除</a></li>
                    </ul>
                </div>
            </div>

            <div id="menuInner">
                <div id="myTabContent" class="tab-content">
                    <div class="tab-pane fade active in form-horizontal" id="menuPhotoInfo1">
                        <p>写真タイトル：<?= $p_title ?></p>

                        <p>撮影箇所：</p>

                        <p>写真区分：</p>

                        <p>施工状況：</p>

                        <p>施工状況詳細：</p>

                        <p>撮影時期：</p>

                        <p>撮影情報予備：</p>

                        <p>撮影年月日：</p>

                        <p>立会者：</p>

                        <p>工事名：</p>

                        <p>工事名：</p>

                        <p>撮影階：<?= $p_floor ?></p>

                        <p>X通り：<?= $p_xStreet ?></p>

                        <p>Y通り：<?= $p_yStreet ?></p>

                        <div id="plan">
                            <img src="img/sample/1F.jpg" class="img-responsive">
                        </div>
                    </div>
                    <!--情報２-->
                    <div class="tab-pane fade form-horizontal" id="menuPhotoInfo2">

                        <p>写真大分類：</p>

                        <p>工種：</p>

                        <p>種別：</p>

                        <p>細別：</p>

                        <p>工種区分予備：</p>

                        <p>施工管理値：</p>

                        <p>受注者説明文：</p>

                    </div>
                    <!--                    参考図-->
                    <div class="tab-pane fade form-horizontal" id="menuPhotoReference">
                        <p>参考図タイトル：</p>

                        <p>付加情報予備：</p>

                    </div>
                </div>

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
                <h4 class="modal-title">編集</h4>
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
                            <label for="inputEmail" class="col-lg-2 control-label">施工状況</label>

                            <div class="col-lg-10">
                                <select class="pcombobox">
                                    <option value="" selected>選択してください</option>
                                    <option value="1">鉄筋工事</option>
                                    <option value="2">コンクリート工事</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputEmail" class="col-lg-2 control-label">施工状況詳細</label>

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

                        <div class="form-group">
                            <label for="textArea" class="col-lg-2 control-label">撮影情報予備</label>

                            <div class="col-lg-10">
                                <textarea class="form-control" rows="2" id="textArea"></textarea>
                                <span class="help-block">詳細な情報はこちらに記載下さい</span>
                            </div>

                        </div>
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
    $(document).ready(function(){
        c_init();
    });
</script>
<script src="js/photo.js"></script>
</body>

</html>