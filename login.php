<?php
/**
 * Created by PhpStorm.
 * User: Photoruction
 * Date: 15/07/11
 * Time: 9:51
 */

include('common.php');

//セッション開始
session_start();
//エラーメッセージの格納
$errorMsg = '';

//ログインボタンが押された時の処理
if (isset($_POST['post_flg'])) {
    //ユーザーIDをチェックする
    if (empty($_POST['userId'])) {
        $errorMsg = "メールアドレスが未入力です";
    } else if (empty($_POST['password'])) {
        $errorMsg = "パスワードが未入力です";
    }

    //ユーザーIDとパスワードが入力されていたら認証を開始する
    if (!empty($_POST['userId']) && !empty($_POST['password'])) {
        $pdo = db_con();
        //DB文字コードを指定
        $stmt = $pdo->query('SET NAMES utf8');
        $stmt = $pdo->prepare('SELECT * FROM user WHERE email=:userId');
        $stmt->bindValue(':userId', $_POST['userId']);
        $status = $stmt->execute();

        if ($status == true) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $userPassword = $row['password'];
            }

            if (isset($userPassword)) {
                if ($userPassword == $_POST['password']) {
                    session_regenerate_id(true);
                    $_SESSION['USERID'] = $_POST['userId'];
                    header('Location:main.php');
                    exit;
                } else {
                    $errorMsg = 'ユーザーIDもしくはパスワードに誤りがあります';
                }
            } else {
                $errorMsg = 'ユーザーIDもしくはパスワードに誤りがあります';
            }
        }
    } else {
        //未入力なら何もしない
    }
}


?>
<!doctype html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Photoruction</title>
    <link rel="stylesheet" type="text/css" href="css/jquery-ui.min.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap-select.min.css">
    <link rel="stylesheet" type="text/css" href="css/roboto.min.css">
    <link rel="stylesheet" type="text/css" href="css/material-fullpalette.min.css">
    <link rel="stylesheet" type="text/css" href="css/ripples.min.css">
    <link rel="stylesheet" type="text/css" href="css/non-responsive.css">
    <link rel="stylesheet" type="text/css" href="css/chat.css">
    <link rel="stylesheet" type="text/css" href="css/common.css">
    <link rel="stylesheet" type="text/css" href="css/login.css">
</head>

<body>

<div class="container" id="toplogo">
    <div class="col-xs-12 text-center">
        <img src="img/logo/toplogo.jpg" alt="Photoruction ~革命的な工事写真管理ツール~"/>
    </div>
</div>

<div class="container" id="loginArea">
    <div id="loginForm" class="col-xs-6 col-xs-offset-3 shadow-z-1">
        <!--        <div class="col-xs-5">-->
        <!--            <div id="app_logo">-->
        <!--                <img src="img/logo/applogo.jpg" alt="Photoruction">-->
        <!--            </div>-->
        <!--        </div>-->
        <div class="col-xs-12">
            <div  id="myTabContent" class="tab-content">
                <div class="tab-pane fade active in" id="loginTab">
                    <p style="float: right;"><a href="#createUser" data-toggle="tab">アカウントの新規登録はこちら</a></p>
                    <h4 style="float: left; font-weight: bold; margin-top: 30px;">ログイン</h4>

                    <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>">
                        <select class="form-control">
                            <option value="1" selected>赤羽１丁目１番地再開発計画</option>
                        </select>
                        <input type="text" name="userId" id="userId" placeholder="メールアドレス" class="form-control"/>
                        <input type="password" name="password" id="password" placeholder="パスワード" class="form-control"/>
                        <p>パスワードをお忘れの場合</p>
                        <div class="form-group checkboxArea">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="selectedNo[]" value="{$result['p_id']}">&nbsp;次回から入力を省略
                                </label>
                            </div>
                        </div>
                        <input type="hidden" name="post_flg" value="1"/>
                        <button type="submit" class="btn btn-info" style="float: right;">
                            ログイン
                        </button>
                        <p><?= $errorMsg ?></p>
                    </form>
                </div>

                <div  class="tab-pane fade" id="createUser">
                    <p style="float: right;"><a href="#loginTab" data-toggle="tab">ログインはこちら</a></p>
                    <h4 style="float: left; font-weight: bold; margin-top: 30px;">アカウント新規登録</h4>

                    <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>">
                        <input type="text" name="userName" id="userId" placeholder="名前" class="form-control"/>
                        <input type="text" name="userKana" id="password" placeholder="名前かな" class="form-control"/>
                        <input type="text" name="password" id="password" placeholder="メールアドレス" class="form-control"/>
                        <input type="text" name="password" id="password" placeholder="会社名" class="form-control"/>
                        <input type="password" name="password" id="password" placeholder="パスワード" class="form-control"/>
                        <input type="password" name="password" id="password" placeholder="パスワード（再確認）"
                               class="form-control"/>
                        <div class="form-group checkboxArea">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="selectedNo[]" value="{$result['p_id']}">&nbsp;photoructionの利用規約に同意します。
                                </label>
                            </div>
                        </div>
                        <input type="hidden" name="post_flg" value="1"/>
                        <button type="submit" class="btn btn-info" style="float: right;">
                            アカウントの作成
                        </button>
                        <p><?= $errorMsg ?></p>
                    </form>
                </div>

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

<script>

    $(document).ready(function () {
        $.material.init();
    });

</script>
<script src="js/common.js"></script>
</body>

</html>