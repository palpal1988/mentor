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
    <title>Photoruction -プロジェクト管理-</title>
    <link rel="stylesheet" type="text/css" href="css/jquery-ui.min.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap-select.min.css">
    <link rel="stylesheet" type="text/css" href="css/roboto.min.css">
    <link rel="stylesheet" type="text/css" href="css/material-fullpalette.min.css">
    <link rel="stylesheet" type="text/css" href="css/ripples.min.css">
    <link rel="stylesheet" type="text/css" href="css/non-responsive.css">
    <link rel="stylesheet" type="text/css" href="css/chat.css">
    <link rel="stylesheet" type="text/css" href="css/common.css">
    <link rel="stylesheet" href="css/pro_config.css">
</head>

<body>
<!--   ナビゲーション-->
<?= navigationSelect(CONFIG) ?>

<!--    本体部分-->
<div class="container">
    <div class="row">
        <!--      メニュー-->
        <div class="col-xs-3">
            <div id="configMenu">
                <h2 class="configTitle">プロジェクト管理</h2>
            </div>
            <ul id="configList">
                <li href="#config1" data-toggle="tab" href="#config1" data-toggle="tab">参加者管理</li>
                <li href="#config2" data-toggle="tab">全体設定</li>
            </ul>
        </div>

        <!--        設定-->
        <div class="col-xs-9">
            <div id="myTabContent" class="tab-content">
                <!--            参加者管理-->
                <div class="tab-pane fade active in form-horizontal" id="config1">
                    <button class="btn btn-info">更新</button>
                    <button class="btn btn-danger" data-toggle="modal" data-target="#newUser">＋追加</button>
                    <table class="table table-hover">
                        <tr class="active">
                            <th style="width: 19%;">ユーザー名</th>
                            <th style="width: 49%;">会社名</th>
                            <th style="width: 11%;">権限</th>
                            <th style="width: 11%;">参加</th>
                            <th style="width: 10%;">詳細</th>
                        </tr>
                        <tr>
                            <td>中島　貴春</td>
                            <td>株式会社中島工務店</td>
                            <td>
                                <select class="form-control">
                                    <option value="1">全体</option>
                                    <option value="2">個人</option>
                                    <option value="3">管理者</option>
                                </select>
                            </td>
                            <td>
                                <select class="form-control">
                                    <option value="1">参加</option>
                                    <option value="2">不参加</option>
                                </select>
                            </td>
                            <td>
                                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#userDetail">
                                    詳細
                                </button>
                            </td>


                        </tr>
                        <tr>
                            <td>中島　貴春</td>
                            <td>株式会社中島工務店</td>
                            <td>
                                <select class="form-control">
                                    <option value="1">全体</option>
                                    <option value="2">個人</option>
                                    <option value="3">管理者</option>
                                </select>
                            </td>
                            <td>
                                <select class="form-control">
                                    <option value="1">参加</option>
                                    <option value="2">不参加</option>
                                </select>
                            </td>
                            <td>
                                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#userDetail">
                                    詳細
                                </button>
                            </td>

                        </tr>
                        <tr>
                            <td>中島　貴春</td>
                            <td>株式会社中島工務店</td>
                            <td>
                                <select class="form-control">
                                    <option value="1">全体</option>
                                    <option value="2">個人</option>
                                    <option value="3">管理者</option>
                                </select>
                            </td>
                            <td>
                                <select class="form-control">
                                    <option value="1">参加</option>
                                    <option value="2">不参加</option>
                                </select>
                            </td>
                            <td>
                                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#userDetail">
                                    詳細
                                </button>
                            </td>

                        </tr>
                        <tr class="warning">
                            <td>中島　貴春</td>
                            <td>株式会社中島工務店</td>
                            <td>
                                <select class="form-control">
                                    <option value="1">全体</option>
                                    <option value="2">個人</option>
                                    <option value="3" selected>管理者</option>
                                </select>
                            </td>
                            <td>
                                <select class="form-control">
                                    <option value="1">参加</option>
                                    <option value="2">不参加</option>
                                </select>
                            </td>
                            <td>
                                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#userDetail">
                                    詳細
                                </button>
                            </td>

                        </tr>
                        <tr>
                            <td>中島　貴春</td>
                            <td>株式会社中島工務店</td>
                            <td>
                                <select class="form-control">
                                    <option value="1">全体</option>
                                    <option value="2">個人</option>
                                    <option value="3">管理者</option>
                                </select>
                            </td>
                            <td>
                                <select class="form-control">
                                    <option value="1">参加</option>
                                    <option value="2">不参加</option>
                                </select>
                            </td>
                            <td>
                                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#userDetail">
                                    詳細
                                </button>
                            </td>

                        </tr>
                        </tbody>
                    </table>
                </div>
                <!--           プロジェクト設定 -->
                <div class="tab-pane fade" id="config2">
                    <button class="btn btn-info">更新</button>
                    <form class="form-horizontal" id="projectInfoForm">
                        <div class="form-group">
                            <label for="inputEmail" class="col-xs-2 control-label">プロジェクト名</label>

                            <div class="col-xs-10">
                                <input type="text" class="form-control" placeholder="プロジェクト名">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail" class="col-xs-2 control-label">請負者名</label>

                            <div class="col-xs-10">
                                <input type="text" class="form-control" placeholder="請負者名">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail" class="col-xs-2 control-label">撮影年月日</label>

                            <div class="col-xs-5">
                                <input type="text" class="form-control datepicker" placeholder="着工日">
                            </div>
                            <div class="col-xs-5">
                                <input type="text" class="form-control datepicker" placeholder="竣工日">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!--ユーザー追加ウインドウ-->
<div class="modal fade" id="newUser">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">閉じる</span>
                </button>
                <h4 class="modal-title">ユーザーの追加</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    <div class="form-group">
                        <label for="inputEmail" class="col-xs-3 control-label">メールアドレス</label>

                        <div class="col-xs-9">
                            <input type="text" class="form-control" placeholder="追加ユーザのメールアドレス">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail" class="col-xs-3 control-label">権限</label>

                        <div class="col-xs-9">
                            <select class="form-control">
                                <option>全体</option>
                                <option>個人</option>
                                <option>管理者</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="textArea" class="col-xs-3 control-label">コメント</label>

                        <div class="col-lg-9">
                            <textarea class="form-control" rows="8" id="textArea"></textarea>
                            <span class="help-block">メールが送信されますのでコメントがある場合は記載ください</span>
                        </div>

                    </div>
                </form>
            </div>
            <div class="modal-footer">

                <button class="btn btn-info btn-sm">追加</button>
                <button class="btn btn-danger btn-sm" data-dismiss="modal">閉じる</button>
            </div>
        </div>
    </div>
</div>

<!--ユーザー詳細ウインドウ-->
<div class="modal fade" id="userDetail">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">閉じる</span>
                </button>
                <h4 class="modal-title">中島　貴春</h4>
            </div>
            <div class="modal-body">
                <div class="col-xs-3">
                    <p>ふりがな</p>
                </div>
                <div class="col-xs-9">
                    <p>なかじま　たかはる</p>
                </div>
                <div class="col-xs-3">
                    <p>会社名</p>
                </div>
                <div class="col-xs-9">
                    <p>株式会社中島工務店</p>
                </div>
                <div class="col-xs-3">
                    <p>メールアドレス</p>
                </div>
                <div class="col-xs-9">
                    <p>palpalpal1988@gmail.com</p>
                </div>
                <div class="col-xs-3">
                    <p>写真撮影枚数</p>
                </div>
                <div class="col-xs-9">
                    <p>423枚</p>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger btn-sm" data-dismiss="modal">閉じる</button>
            </div>
        </div>
    </div>
</div>

<!--ユーザー削除ウインドウ-->
<div class="modal fade" id="userDelete">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">閉じる</span>
                </button>
                <h4 class="modal-title">ユーザーの削除</h4>
            </div>
            <div class="modal-body">
                このプロジェクトから中島貴春を削除してもよろしいですか<br>
                写真にこのユーザー名が表示されなくなります。表示させてプロジェクトから参加させない場合は、不参加にすることを推奨します。
            </div>
            <div class="modal-footer">

                <button class="btn btn-danger btn-sm">削除</button>
                <button class="btn btn-info btn-sm" data-dismiss="modal">キャンセル</button>
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
<script src="js/pro_config.js"></script>
</body>

</html>