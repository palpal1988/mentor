<?php
include("common.php");
//データベースに接続
//$pdo = db_con();
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Photoruction -プロジェクト情報-</title>
    <link rel="stylesheet" type="text/css" href="css/jquery-ui.min.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap-select.min.css">
    <link rel="stylesheet" type="text/css" href="css/roboto.min.css">
    <link rel="stylesheet" type="text/css" href="css/material-fullpalette.min.css">
    <link rel="stylesheet" type="text/css" href="css/ripples.min.css">
    <link rel="stylesheet" type="text/css" href="css/non-responsive.css">
    <link rel="stylesheet" type="text/css" href="css/chat.css">
    <link rel="stylesheet" type="text/css" href="css/common.css">
    <link rel="stylesheet" type="text/css" href="css/info.css">
</head>

<body>
<!--   ナビゲーション-->
<?= navigationSelect(ETC) ?>

<!--    本体部分-->
<div class="container">
    <div class="row">
        <!--        タイトル部分-->
        <div class="con-xs-12 text-center">
            <img src="img/logo/toplogo.jpg" alt="Photoruction ~革命的な工事写真管理ツール~"/>
        </div>
        <!--      プロジェクト情報-->
        <div id="infoWindow">

            <div class="col-xs-6" id="projectWindow">
                <div class="shadow-z-1" id="memberWindowInner">
                    <div class="col-xs-12 text-center">
                        <h4>プロジェクト</h4>
                    </div>

                    <table class="table table-hover">
                        <tr>
                            <td>プロジェクト名</td>
                            <td>赤羽一丁目一番地再開発計画</td>
                        </tr>
                        <tr>
                            <td>請負者</td>
                            <td>株式会社中島工務店</td>
                        </tr>
                        <tr>
                            <td>工期</td>
                            <td>2015年8月1日〜2015年4月1日</td>
                        </tr>
                        <tr>
                            <td>発注者</td>
                            <td>株式会社赤羽ビル</td>
                        </tr>
                        <tr>
                            <td>写真の枚数</td>
                            <td>1203枚</td>
                        </tr>
                        <tr>
                            <td>アルバムの数</td>
                            <td>8冊</td>
                        </tr>
                        <tr>
                            <td>登録図面</td>
                            <td>38枚</td>
                        </tr>
                    </table>

                </div>
            </div>

            <!--        参加者情報-->
            <div class="col-xs-6" id="memberWindow">
                <div class="shadow-z-1" id="memberWindowInner">
                    <div class="col-xs-12 text-center">
                        <h4>参加者</h4>
                    </div>
                    <table class="table table-hover">
                        <tr class="active">
                            <th>ユーザー名</th>
                            <th>会社名</th>
                        </tr>
                        <tr>
                            <td>中島　貴春</td>
                            <td>株式会社中島工務店</td>
                        </tr>
                        <tr>
                            <td>中島　貴春</td>
                            <td>株式会社中島工務店</td>
                        </tr>
                        <tr>
                            <td>中島　貴春</td>
                            <td>株式会社中島工務店</td>
                        </tr>
                        <tr>
                            <td>中島　貴春</td>
                            <td>株式会社中島工務店</td>
                        </tr>
                        <tr>
                            <td>中島　貴春</td>
                            <td>株式会社中島工務店</td>
                        </tr>
                        <tr>
                            <td>中島　貴春</td>
                            <td>株式会社中島工務店</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="js/modernizr.custom.js"></script>
<script src="js/jquery-2.1.4.min.js"></script>
<script src="js/jquery-ui.min.js"></script>
<!--通り芯描画のためのプラグイン-->
<!--<script src="js/kinetic-v5.1.0.min.js"></script>-->
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-select.min.js"></script>
<script src="js/jquery.dlmenu.js"></script>
<script src="js/ripples.min.js"></script>
<script src="js/material.min.js"></script>
<script src="http://cdn.mlkcca.com/v2.0.0/milkcocoa.js"></script>

<!--チャット用-->
<!--<script src="js/chat.js"></script>-->
<script src="js/common.js"></script>
<script>
    $(document).ready(function () {
        c_init();
    });
</script>
</body>

</html>