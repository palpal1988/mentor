$(document).ready(function () {

    $("#editBtn").attr("data-content", "編集").popover({
        trigger: 'hover',
        placement: 'top'
    });

    $("#deleteBtn").attr("data-content", "選択削除").popover({
        trigger: 'hover',
        placement: 'top'
    });

    $('#planList>li').on('click', function (e) {
        $('#planList>li').css('background-color', '#fff');
        $(this).css('background-color', '#afd9ee');
    });

    //カスタムコンボボックスのプレイスホルダー変更（全体共通部分）
    $('.custom-combobox > input').attr('placeholder','階（自由入力 or 選択）');

    ////以下全てkinekcti.js関連
    ////追加、削除、移動、選択それぞれのモード
    //const NOMAL_MODE = 99;
    //const ADD_MODE = 0;
    //const DELETE_MODE = 1;
    //const MOVE_MODE = 2;
    //const SELECT_MODE = 3;
    ////初期モードはノーマル
    //var mode = NOMAL_MODE;
    ////移動するさいに使用するフラグ。trueであれば移動可能とする
    //var drragable = false;
    ////選択中のラインを格納する。
    //var curLine = new Array();
    ////線を格納する配列
    //var baseLineArr = new Array();
    ////ステージの作成
    //
    //
    //var stage = new Kinetic.Stage({
    //    container: 'drowarea',
    //    width: 800,
    //    height: 500,
    //});
    ////レイアーの作成
    //var layer = new Kinetic.Layer();
    ////オンマウスでのライン
    //var baseLine = new Kinetic.Line({
    //    //                x: stage.getWidth() / 2,
    //    //                y: stage.getHeight() / 2,
    //    points: [0, 0],
    //    stroke: 'red',
    //    strokeWidth: 2,
    //    lineJoin: 'round',
    //    /*
    //     * line segments with a length of 33px
    //     * with a gap of 10px
    //     */
    //    dash: [20, 5]
    //});
    //
    ////     表示する図面を格納する（todo)
    //var planImg = new Image();
    //planImg.src = "img/sample/1F.jpg";
    //planImg.width = 800;
    //planImg.height = 537;
    //
    ////読み込まれる前に表示すると画像が表示されない現象が起きるため、画像が読み込まれてからレイヤーに追加する
    //$(planImg).load(function () {
    //    var drawingPlan = new Kinetic.Image({
    //        x: 0,
    //        y: 0,
    //        image: planImg,
    //        blurRadius: 20,
    //        draggable: true
    //    });
    //    //                通り芯の初期ラインを追加しておく
    //    layer.add(drawingPlan);
    //
    //    //保存している線を読み込む
    //    if (localStorage.length != 0) {
    //        for (var i = 0; i < localStorage.length; i++) {
    //            var savePoints = JSON.parse(localStorage.getItem(i));
    //            var newLine = new ObjLine(savePoints[0], savePoints[1], i);
    //            baseLineArr.push(newLine);
    //            layer.add(newLine.line);
    //        }
    //    }
    //
    //    layer.add(baseLine);
    //    stage.add(layer);
    //    layer.draw();
    //
    //});
    //
    //
    ////現在選択されている線を削除する
    //function deleteLine() {
    //    for (var i = 0; i < curLine.length; i++) {
    //        baseLineArr.splice($.inArray(curLine[i].id, baseLineArr, 1));
    //        curLine[i].remove();
    //    }
    //    layer.draw();
    //    //    現在選択中の線をからにする。
    //    curLine = [];
    //    console.log("本数" + baseLineArr.length);
    //}
    //
    ////X軸なのかY軸なのかを判断してラインポイント線を返す
    //function xyPoints(curX, curY) {
    //    if ($("input[name='direction']:checked").val() == "x") {
    //        //回転時は全体をこっちのアルゴリズムに合わせる必要がある
    //        //                        return [curX-(stage.getWidth()/2), 0-(stage.getHeight()/2),curX-(stage.getWidth()/2), stage.getHeight()/2];
    //        //                    return [curX, 0, curX, 500];
    //        return [curX, 0, curX, 500];
    //    } else {
    //        return [0, curY, 800, curY];
    //    }
    //}
    //
    //
    ////線の設定をリセットする。他のモードに移る時に使用する
    //function resetLine() {
    //    for (var i = 0; i < baseLineArr.length; i++) {
    //        baseLineArr[i].line.setStroke("blue");
    //        layer.draw();
    //    }
    //    curLine = [];
    //}
    //
    //
    ////線オブジェクトクラス
    //function ObjLine(x, y, lineName) {
    //
    //    var id = baseLineArr.length;
    //    var name = lineName;
    //    var selected = false;
    //    //縦かよこで新規追加時の向きをかえる
    //
    //    this.line = new Kinetic.Line({
    //        points: xyPoints(x, y),
    //        stroke: 'blue',
    //        strokeWidth: 2,
    //        lineJoin: 'round',
    //        dash: [20, 5],
    //    });
    //
    //    //クリックした時の処理
    //    //移動モード:移動フラグじゃないときはtrueにする。
    //    //選択モード:色を変える
    //    var clickFn = function (e) {
    //        switch (mode) {
    //            case MOVE_MODE:
    //                if (drragable == false) {
    //                    drragable = true;
    //                    //現在選択中のラインを参照する。
    //                    curLine[0] = this;
    //                } else {
    //                    drragable = false;
    //                    curLine = [];
    //                }
    //                break;
    //            case SELECT_MODE:
    //
    //                if (selected == false) {
    //                    selected = true;
    //                    this.setStroke("red");
    //                    layer.draw();
    //                    //選択されているところに入れる。
    //                    curLine.push(this);
    //                } else {
    //                    curLine.splice($.inArray(this, curLine), 1);
    //                    selected = false;
    //                    this.setStroke("blue");
    //                    layer.draw();
    //                }
    //                break;
    //        }
    //    };
    //
    //
    //    //マウスオーバーのときの処理
    //    //移動時：色を変える。カーソルをmoveにする
    //    //削除時　色を変える。カーソルをpointerにする
    //    var mouseOverFn = function () {
    //        switch (mode) {
    //            case MOVE_MODE:
    //                this.setStroke("green");
    //                $("#drowarea").css("cursor", "move");
    //                layer.draw();
    //                break;
    //            case SELECT_MODE:
    //                this.setStroke("red");
    //                $("#drowarea").css("cursor", "pointer");
    //                layer.draw();
    //                break
    //        }
    //
    //    };
    //    //マウスアウト時の処理
    //    //移動時：色を元に戻す。カーソルをautoに戻す
    //    //削除時：色を元に戻す。カーソルをautoに
    //    var mouseOutFn = function () {
    //        switch (mode) {
    //            case MOVE_MODE:
    //                if (drragable == false) {
    //                    this.setStroke("blue");
    //                    $("#drowarea").css("cursor", "auto");
    //                    layer.draw();
    //                }
    //                break;
    //            case SELECT_MODE:
    //                $("#drowarea").css("cursor", "auto");
    //                if (selected == false) {
    //                    this.setStroke("blue");
    //                    layer.draw();
    //                    break;
    //                }
    //
    //        }
    //    };
    //
    //    this.line.on('click', clickFn);
    //    this.line.on('mouseover', mouseOverFn);
    //    this.line.on('mouseout', mouseOutFn);
    //
    //}
    //
    ////選択中の時のみ削除できるよう最初は無効にする
    //$("#delete").attr("disabled", "true").css("background-color", "white");
    //
    ////クリックすることでモードを切り替える。
    //$("#add").on('click', function () {
    //    resetLine();
    //    mode = ADD_MODE;
    //    $("#delete").attr("disabled", "true").css("background-color", "white");
    //});
    //
    //$("#delete").on('click', function () {
    //    deleteLine();
    //
    //});
    //
    //$("#move").on('click', function () {
    //    resetLine();
    //    mode = MOVE_MODE;
    //    $("#delete").attr("disabled", "true").css("background-color", "white");
    //});
    //
    //$("#select").on('click', function () {
    //    resetLine();
    //    mode = SELECT_MODE;
    //    $("#delete").removeAttr("disabled").css("background-color", "aliceblue");
    //});
    //
    ////全ての線が入ったbaseLineArrをjSON形式でローカルストレージに保存する
    //$("#save").on('click', function () {
    //    localStorage.clear();
    //    for (var i = 0; i < baseLineArr.length; i++) {
    //        var storagePoints = baseLineArr[i].line.getPoints();
    //        console.log(storagePoints);
    //        localStorage.setItem(i, JSON.stringify(storagePoints));
    //    }
    //    alert("保存しました");
    //});
    //
    //$("#clear").on('click', function () {
    //    localStorage.clear();
    //});
    //
    ////線を回転させる
    //$("#rotate").on('change', function () {
    //    console.log($(this).val());
    //});
    //
    //
    ////描画領域の処理
    //$("#drowarea").on({
    //    //マウスが動いた時
    //    //ADD_MODE:通り芯を出す
    //    'mousemove': function (e) {
    //        var xPoint = e.offsetX;
    //        var yPoint = e.offsetY;
    //
    //        switch (mode) {
    //            case ADD_MODE:
    //                baseLine.setPoints(xyPoints(xPoint, yPoint));
    //                layer.draw();
    //                break;
    //            case MOVE_MODE:
    //                //クリックされていたら移動する。
    //                if (drragable == true) {
    //                    curLine[0].setPoints(xyPoints(xPoint, yPoint));
    //                    layer.draw();
    //                }
    //                break;
    //        }
    //    },
    //    //クリックされたとき
    //    //ADD_MODE:通り芯を配置する。
    //    'click': function (e) {
    //        if (mode == ADD_MODE) {
    //
    //            var xPoint = e.offsetX;
    //            var yPoint = e.offsetY;
    //
    //            var tmpLine = new ObjLine(xPoint, yPoint, "test");
    //            baseLineArr.push(tmpLine);
    //            layer.add(tmpLine.line);
    //            layer.draw();
    //
    //
    //        }
    //    },
    //    //マウスが離れた時
    //    //ADD_MODE：出ている通り芯を消す
    //    'mouseout': function (e) {
    //        if (mode == ADD_MODE) {
    //            baseLine.setPoints([0, 0]);
    //            layer.draw();
    //        }
    //    }
    //
    //});


});