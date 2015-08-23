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
    <title>Photoruction -図面-</title>
    <link rel="stylesheet" type="text/css" href="css/jquery-ui.min.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap-select.min.css">
    <link rel="stylesheet" type="text/css" href="css/roboto.min.css">
    <link rel="stylesheet" type="text/css" href="css/material-fullpalette.min.css">
    <link rel="stylesheet" type="text/css" href="css/ripples.min.css">
    <link rel="stylesheet" type="text/css" href="css/non-responsive.css">
    <link rel="stylesheet" type="text/css" href="css/chat.css">
    <link rel="stylesheet" type="text/css" href="css/common.css">
    <link rel="stylesheet" href="css/plan.css">
</head>

<body>
<!--   ナビゲーション-->
<?= navigationSelect(PLAN) ?>

<!--    本体部分-->
<div class="container">
    <div class="row">
        <!--       アルバム一覧-->
        <div class="col-xs-3">

            <div id="planMenu">
                <h2 class="planTitle">図面一覧</h2>
                <button class="btn btn-danger btn-sm" id="addNewPlan" data-toggle="modal" data-target="#addPlanWindow">
                    図面新規登録
                </button>
                <div id="btnMenu">
                    <span class="glyphicon glyphicon-edit iconBtn" id="editBtn" data-toggle="modal"
                          data-target="#editPlan"></span>
                    <span class="glyphicon glyphicon-trash iconBtn" id="deleteBtn" data-toggle="modal"
                          data-target="#deletePlan"></span>
                </div>
            </div>
            <ul id="planList">
                <li>RF</li>
                <li>3F+720</li>
                <li>3F</li>
                <li>2F</li>
                <li>1F</li>
                <li>BF</li>
                <li>基礎</li>
            </ul>
        </div>

        <!--        プレビュー-->
        <div class="col-xs-9">

            <div id="planContents">
                <img src="img/sample/1F.jpg" class="img-responsive">
            </div>

            <!--            通り芯描画領域。スケジュールの関係で一旦封印-->
            <!--            <div id="planContents">-->
            <!--                <div id="planMenu" class="form-inline">-->
            <!--                    <button id="add" class="btn btn-default btn-sm">追加</button>-->
            <!--                    <button id="delete" class="btn btn-default btn-sm">削除</button>-->
            <!--                    <button id="move" class="btn btn-default btn-sm">移動</button>-->
            <!--                    <button id="select" class="btn btn-default btn-sm">選択</button>-->
            <!--                    <button id="save" class="btn btn-default btn-sm">保存</button>-->
            <!--                    <button id="clear" class="btn btn-default btn-sm">クリア</button>-->
            <!--                    <label for="rotate" class="menuLabel">回転:-->
            <!--                        <input name="rotate" id="rotate" class="form-control" type="number" min="0" max="90"-->
            <!--                               value="45"/>度-->
            <!--                    </label>-->
            <!--                    <label for="shinName" class="menuLabel">名前:-->
            <!--                        <input id="shinName" name="shinName" class="form-control" type="text"/>-->
            <!--                    </label>-->
            <!--                    <label for="direction" class="menuLabel">方向:</label>-->
            <!--                    <label class="checkbox-inline"><input type="radio" name="direction" id="xDirection"-->
            <!--                                                  value="x" checked="checked">X</label>-->
            <!--                    <label class="checkbox-inline"><input type="radio" name="direction" id="yDirection"-->
            <!--                                                   value="y">Y</label>-->
            <!--                </div>-->
            <!--                <section>-->
            <!--                    <div id="drowarea"></div>-->
            <!--                </section>-->
            <!--            </div>-->
        </div>

    </div>
</div>

<!--図面追加用のモーダルウインドウ-->
<div class="modal fade" id="addPlanWindow">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">閉じる</span>
                </button>
                <h4 class="modal-title">最大５枚まで同時に登録できます。</h4>
            </div>
            <div class="modal-body form-horizontal">
                <div class="form-group">
                    <label for="inputEmail" class="col-lg-1 control-label">１枚目</label>

                    <div class="col-lg-2">
                        <input type="text" readonly="" class="form-control floating-label"
                               placeholder="図面ファイル">
                        <input type="file" id="inputFile" multiple="">
                    </div>
                    <div class="col-lg-4">
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
                    <div class="col-lg-5">
                        <input type="text" class="form-control" placeholder="図面タイトル">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail" class="col-lg-1 control-label">２枚目</label>

                    <div class="col-lg-2">
                        <input type="text" readonly="" class="form-control floating-label"
                               placeholder="図面ファイル">
                        <input type="file" id="inputFile" multiple="">
                    </div>
                    <div class="col-lg-4">
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
                    <div class="col-lg-5">
                        <input type="text" class="form-control" placeholder="図面タイトル">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail" class="col-lg-1 control-label">３枚目</label>

                    <div class="col-lg-2">
                        <input type="text" readonly="" class="form-control floating-label"
                               placeholder="図面ファイル">
                        <input type="file" id="inputFile" multiple="">
                    </div>
                    <div class="col-lg-4">
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
                    <div class="col-lg-5">
                        <input type="text" class="form-control" placeholder="図面タイトル">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail" class="col-lg-1 control-label">４枚目</label>

                    <div class="col-lg-2">
                        <input type="text" readonly="" class="form-control floating-label"
                               placeholder="図面ファイル">
                        <input type="file" id="inputFile" multiple="">
                    </div>
                    <div class="col-lg-4">
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
                    <div class="col-lg-5">
                        <input type="text" class="form-control" placeholder="図面タイトル">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail" class="col-lg-1 control-label">５枚目</label>

                    <div class="col-lg-2">
                        <input type="text" readonly="" class="form-control floating-label"
                               placeholder="図面ファイル">
                        <input type="file" id="inputFile" multiple="">
                    </div>
                    <div class="col-lg-4">
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
                    <div class="col-lg-5">
                        <input type="text" class="form-control" placeholder="図面タイトル">
                    </div>
                </div>

            </div>
            <div class="modal-footer">

                <button class="btn btn-info btn-sm" id="editOkBtn">登録</button>
                </form>

                <button class="btn btn-danger btn-sm" data-dismiss="modal">キャンセル</button>
            </div>
        </div>
    </div>
</div>

<!--削除確認モーダルウインドウ-->
<div class="modal fade" id="deletePlan">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">閉じる</span>
                </button>
                <h4 class="modal-title">図面を削除しますか</h4>
            </div>
            <div class="modal-body" id="dropArea">
                photoructionから図面を削除してもよろしいですか？<br>
                ※図面を削除しても写真は削除されません
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger btn-sm" id='deleteOkBtn'>削除</button>
                <button class="btn btn-default btn-sm" data-dismiss="modal">閉じる</button>
            </div>
        </div>
    </div>
</div>

<!--編集用モーダルウインドウ-->
<div class="modal fade" id="editPlan">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">閉じる</span>
                </button>
                <h4 class="modal-title">編集</h4>
            </div>
            <div class="modal-body form-horizontal" id="dropArea">
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
<script src="js/plan.js"></script>
</body>

</html>