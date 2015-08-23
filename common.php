<?php
/**
 * Created by PhpStorm.
 * User: photoruction
 * Date: 15/07/05
 * Time: 13:55
 *
 * 共通の設定値を格納するためのファイル
 */


define('DEBUG_MODE',0);

//--------------------変数--------------------
define('TABLE_PHOTO', 'photo');
define('TABLE_USER', 'user');

//フォルダ関連
define('PHOTO_FOLDER', 'photo');
define('THUMNAIL_FOLDER', 'photoS');

//サムネイルの縦横（生成時）
define('THUMNAIL_WIDTH', 200);
define('THUMNAIL_HEIGHT', 200);


//現在のページ
define('PHOTO_LIST',1);
define('ALBUM',2);
define('PLAN',3);
define('CONFIG',4);
define('ETC',99);


//--------------------用語関連--------------------
define('P_TITLE', '写真タイトル');
define('P_DATE', '撮影日');
define('P_RESISTDATE', '更新日');
define('P_KOUJISHU', '工事種目');
define('P_CLASS', '種別');
define('P_SUBCLASS', '細別');
define('P_KOUSHUYOBI', '工種予備');
define('P_PLACE', '撮影部位');
define('P_PERIOD', '撮影時期');
define('P_KOUJISTATUS', '施工状況');
define('P_INFOYOBI', '予備情報');
define('P_PHOTOGRAPHER', '立会者');
define('P_COMPANY', '受注者');
define('P_DESCRIPTION', '説明文');
define('P_FLOOR', '階');
define('P_XSTREET', 'X軸通り芯');
define('P_YSTREET', 'Y軸通り芯');


//デバッグ用
//ini_set("display_errors", On);
//error_reporting(E_ALL);
//--------------------html関連--------------------


function navigationSelect($type){

    $active1="";
    $active2="";
    $active3="";
    $active4="";

    switch($type){
        case PHOTO_LIST:
            $active1=' class="active"';
            break;
        case ALBUM:
            $active2=' class="active"';
            break;
        case PLAN:
            $active3=' class="active"';
            break;
        case CONFIG:
            $active4=' class="active"';
            break;
    }

    $navigation = <<<EOE
<nav class="navbar navbar-inverse">
    <div class="container">

        <!--          ナビゲーションヘッダー-->
        <div class="navbar-header">
            <a href="info.php" class="navbar-brand">
                <span id="siteTitle">赤羽一丁目一番地再開発計画</span>
            </a>
        </div>

        <!--           ナビゲーションボディ-->
        <div class="navbar-collapse collapse navbar-inverse-collapse">
        <ul class="nav navbar-nav">
            <li{$active1}>
                <a href="main.php?type=1">写真一覧</a>
            </li>
            <li{$active2}>
                <a href="album.php">アルバム</a>
            </li>
            <li{$active3}>
                <a href="plan.php">図面</a>
            </li>
            <li{$active4}>
                <a href="pro_config.php">プロジェクト管理</a>
            </li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li class="dropdwon">
                <a href="bootstrap-elements.html" data-target="#" class="dropdown-toggle" data-toggle="dropdown">ログイン：中島 貴春 <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><a href="javascript:void(0)">アカウント設定</a></li>
                    <li><a href="javascript:void(0)">ログアウト</a></li>

                </ul>
            </li>
        </ul>

    </div>
    </div>
</nav>
EOE;

    return $navigation;
}


//PDO

function db_con()
{

    if(DEBUG_MODE==0){
        //プロパティ
        $dbname = "";
        $host = '';
        $id = '';
        $pw = '';

    }else{
        //プロパティ
        $dbname = "";
        $host = 'localhost';
        $id = '';
        $pw = '';
    }

    $pdo = new PDO("mysql:dbname={$dbname};host={$host}", $id, $pw);
    $pdo->query('SET NAMES utf8');
    return $pdo;

}

//引数に画像パスを与えると与えられたファイル名と同じ名前（接頭詞としてsがつく）でサムネイルを作成しサムネイルフォルダへ保存
function createThumbnail($originImg)
{
    $thumnailExt = mb_strtolower(getExt($originImg));

    //拡張子によって作り方を変える
    switch ($thumnailExt) {
        case 'jpg':
            $sImg = imagecreatefromjpeg(PHOTO_FOLDER . "/" . $originImg);
            break;
        case 'jpeg':
            $sImg = imagecreatefromjpeg(PHOTO_FOLDER . "/" . $originImg);
            break;
        case 'png':
            $sImg = imagecreatefrompng(PHOTO_FOLDER . "/" . $originImg);
            break;
        case 'gif':
            $sImg = imagecreatefromgif(PHOTO_FOLDER . "/" . $originImg);
            break;
    }

    //画像の幅と高さを取得する
    $width = imagesx($sImg);
    $height = imagesy($sImg);
    if ($width > $height) {
        $size = $height;
        $x = floor(($width - $height) / 2);
        $y = 0;
        $width = $size;
    } else {
        $side = $width;
        $y = floor(($height - $width) / 2);
        $x = 0;
        $height = $side;
    }

    //サムネイルの大きさを決める
    $thumbnail_width = THUMNAIL_WIDTH;
    $thumbnail_height = THUMNAIL_HEIGHT;
    $thumbnail = imagecreatetruecolor($thumbnail_width, $thumbnail_height);


    imagecopyresized($thumbnail, $sImg, 0, 0, $x, $y, $thumbnail_width, $thumbnail_height, $width, $height);

    switch ($thumnailExt) {
        case 'jpg':
            imagejpeg($thumbnail, THUMNAIL_FOLDER . "/s_" . $originImg);
            break;
        case 'jpeg':
            imagejpeg($thumbnail, THUMNAIL_FOLDER . "/s_" . $originImg);
            break;
        case 'png':
            imagepng($thumbnail, THUMNAIL_FOLDER . "/s_" . $originImg);
            break;
        case 'gif':
            imagegif($thumbnail, THUMNAIL_FOLDER . "/s_" . $originImg);
            break;
    }
}

//拡張子を返してくれる
function getExt($file)
{
    return end(explode('.', $file));
}

?>