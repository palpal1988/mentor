/**
 * Created by NakajimaTakaharu on 15/08/08.
 */
var milkcocoa = new MilkCocoa("maxiakrqn3j.mlkcca.com");
/* your-app-id にアプリ作成時に発行されるapp-idを記入します */
var chatDataStore = milkcocoa.dataStore('chat');
var textArea, board;

//通知の数を保存する
var newMsgCount=0;

//点滅させるのに自分が送ったメッセージかどうか判定させる
var blinkFlag=true;

window.onload = function () {
    textArea = document.getElementById("msg");
    board = document.getElementById("board");


    //メッセージボードボタンを押した時の処理。ウィンドウを出したり閉じたりする。
    $("#toggleBtn").on('click', function () {
        if ($("#chatWindow").css("display") == "none") {
            newMsgCount=0;
            $("#toggleBtn").text("Message Board");
            $("#chatWindow").css("visibility","visible");
            $("#chatWindow").fadeIn();

        } else {
            $("#chatWindow").fadeOut();
        }
    });
}

//メッセージを送る時に使用する
function clickSend() {
    var text = textArea.value;
    sendText(text);
}

//着信時に点滅させる
function blink() {
    if(blinkFlag==true){
        $("#toggleBtn").fadeTo(500, 0.25).fadeTo(500, 1).fadeTo(500, 0.25).fadeTo(500, 1);
    }else{
        blinkFlag=true;
    }
}


//データをMilkCocoaに送る
function sendText(text) {
    blinkFlag=false;
    chatDataStore.push({
        message: text,
        name: $("#nameText").val(),
        input_date: currentDate()
    });

    console.log("送信完了!");
    textArea.value = "";
    scrollMove();
}

//スクロールを一番移動する
function scrollMove() {
    var scrollHeight = document.getElementById("board").scrollHeight;
    document.getElementById("board").scrollTop = scrollHeight;
}



//Milkcocoaからデータが来た時に処理する
chatDataStore.on("push", function (data) {
    addText(data.value);
    if($("#chatWindow").css("display")=="none"){
        newMsgCount+=1;
        console.log("test");
        $("#toggleBtn").text("Massage Board"+" ( "+newMsgCount+" ) ");
    }
    blink();
    scrollMove();
});

//文字を追加する
function addText(text) {
    var msgDom = document.createElement("li");
    $(msgDom).addClass("msgBlock");
    msgDom.innerHTML = "<p class='date'>" + text.input_date + "</p><p class='name'>" + text.name + "</p><p class='msgLine'>" + text.message + "</p>";
    $("#board").append(msgDom);
}

//現時刻を表示する
function currentDate() {
    //今日の日付データを変数hidukeに格納
    var hiduke = new Date();

    //年・月・日・曜日を取得する
    var year = hiduke.getFullYear();
    var month = hiduke.getMonth() + 1;
    var week = hiduke.getDay();
    var day = hiduke.getDate();

    var yobi = new Array("日", "月", "火", "水", "木", "金", "土");

    var hour = hiduke.getHours();
    var minute = hiduke.getMinutes();
    var second = hiduke.getSeconds();

    return year + "/" + month + "/" + day + "(" + yobi[week] + ")" + "  " + hour + "時" + minute + "分" + second + "秒";

}


//データの保存や取得の命令を出すため、データストアとの通信を行うDataStorageオブジェクを取得します。
chatDataStore.stream().size(100).sort('desc').next(function (err, data) {
    $.each(data, function (i, v) {
        addText(v.value, 0);
    });
    scrollMove();

    $("#chatWindow").css("display","none");
});





//メッセージを削除する
//function clickDelete() {
//    removeText();
//}

//削除する処理
//function removeText() {
//    chatDataStore.stream().sort('arc').next(function (err, data) {
//        $.each(data, function (i, v) {
//            chatDataStore.remove(v.id);
//        });
//    });
//    $("#board").empty();
//}