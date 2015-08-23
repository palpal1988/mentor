<?php
include("common.php");

//データベースに接続
$pdo = db_con();
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Photoruction -アルバム-</title>
    <link rel="stylesheet" type="text/css" href="css/jquery-ui.min.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap-select.min.css">
    <link rel="stylesheet" type="text/css" href="css/roboto.min.css">
    <link rel="stylesheet" type="text/css" href="css/material-fullpalette.min.css">
    <link rel="stylesheet" type="text/css" href="css/ripples.min.css">
    <link rel="stylesheet" type="text/css" href="css/non-responsive.css">
    <link rel="stylesheet" type="text/css" href="css/chat.css">
    <link rel="stylesheet" type="text/css" href="css/common.css">
    <link rel="stylesheet" href="css/album.css">
</head>

<body>
<!--   ナビゲーション-->
<?= navigationSelect(ALBUM) ?>

<!--    本体部分-->
<div class="container">
    <div class="row">
        <!--       アルバム一覧-->
        <div class="col-md-3">

            <div id="albumMenu">
                <h2 class="albumTitle">アルバム一覧</h2>

                <div id="btnMenu">
                    <span class="glyphicon glyphicon-download-alt iconBtn" id="downloadBtn"></span>
                    <span class="glyphicon glyphicon-edit iconBtn" id="editBtn" data-toggle="modal"
                          data-target="#editAlbum"></span>
                    <span class="glyphicon glyphicon-trash iconBtn" id="deleteBtn" data-toggle="modal"
                          data-target="#deleteAlbum"></span>
                </div>
            </div>
            <ul id="albumList">
                <li>2015-07-22 配筋検査</li>
                <li>2015-07-15 消防検査</li>
                <li>2015-06-22 安全大会</li>
                <li>2015-04-22 社内中間検査</li>
            </ul>
        </div>

        <!--        プレビュー-->
        <div class="col-md-9">

            <h2 class="albumTitle">プレビュー</h2>
            <img src="img/sample/sample_album.jpg" alt="プレビュー">

        </div>

    </div>
</div>

<!--削除確認モーダルウインドウ-->
<div class="modal fade" id="deleteAlbum">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">閉じる</span>
                </button>
                <h4 class="modal-title">アルバムを削除しますか</h4>
            </div>
            <div class="modal-body">
                photoructionからアルバムを削除してもよろしいですか？<br>
                ※アルバムを削除しても写真は削除されません。
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger btn-sm" id='deleteOkBtn'>削除</button>
                <button class="btn btn-default btn-sm" data-dismiss="modal">閉じる</button>
            </div>
        </div>
    </div>
</div>
<!--編集用モーダルウインドウ-->
<div class="modal fade" id="editAlbum">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">閉じる</span>
                </button>
                <h4 class="modal-title">編集</h4>
            </div>
            <div class="modal-body form-horizontal">
                <div class="form-group">
                    <label for="inputEmail" class="col-xs-3 control-label">図面差替え</label>

                    <div class="col-xs-9">
                        <input type="text" readonly="" class="form-control floating-label"
                               placeholder="図面ファイルの選択">
                        <input type="file" id="inputFile" multiple="">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputFile" class="col-xs-3 control-label">階</label>

                    <div class="col-xs-9">
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
                    <label for="textArea" class="col-xs-3 control-label">図面タイトル</label>

                    <div class="col-xs-9">
                        <input type="text" class="form-control" placeholder="図面タイトル">
                    </div>
                </div>
            </div>
            <div class="modal-footer">

                <button class="btn btn-info btn-sm" id="editOkBtn">編集</button>
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
<!--チャット用-->
<!--<script src="js/chat.js"></script>-->
<script src="js/common.js"></script>
<script>
    $(document).ready(function () {
        c_init();
    });
</script>
<script src="js/album.js"></script>
</body>

</html>