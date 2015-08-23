/**
 * Created by Photoruction on 15/07/05.
 * main.php
 */



$(document).ready(function () {

    //フォトインスペクタのイニシャライズ
    $('#dl-menu').dlmenu();

    //ボタンの初期化
    switchBtnEffect(judgeCheckBox());


    //---------ポップオーバー-------------
    //追加ボタン
    $("#addBtn").attr("data-content", "写真を追加").popover({
        trigger: 'hover',
        placement: 'top'
    });
    //ソートボタン
    if (queryVal['sort'] == "1" || queryVal['sort'] == undefined) {
        $("#sortBtn").attr("data-content", "撮影日で降順に並べ替え").popover({
            trigger: 'hover',
            placement: 'top'
        });
    } else {
        $("#sortBtn").attr("data-content", "撮影日で昇順に並べ替え").popover({
            trigger: 'hover',
            placement: 'top'
        });
    }

    //更新ボタン
    $("#refleshBtn").attr("data-content", "更新").popover({
        trigger: 'hover',
        placement: 'top'
    });
    //表示切り替えボタン
    $("#typeBtn").attr("data-content", "表示形式の変更").popover({
        trigger: 'hover',
        placement: 'top'
    });

    //削除ボタン、編集ボタン、ダウンロードボタンはswitchBtnEffect関数にまとめてある


    //---------ボタン押下時の切り替え-------------

    //switchBtnEffectに一部処理を委譲
    $("#addOkBtn").on('click', function () {
        $('#addPhoto').click();
    });

    $('#searchBtn').on('click', function (e) {
        queryVal['search'] = encodeURIComponent($('#searchForm').val());
        window.location.href = createUrl();

    });

    $("#typeBtn").on('click', function () {
        if ($(this).hasClass('typeNum1')) {
            queryVal['type'] = 2;
            location.href = createUrl();
        } else if ($(this).hasClass('typeNum2')) {
            queryVal['type'] = 3;
            location.href = createUrl();
        } else if ($(this).hasClass('typeNum3')) {
            queryVal['type'] = 1;
            location.href = createUrl();
        } else {
            queryVal['type'] = 2;
            location.href = createUrl();
        }
    });

    $("#sortBtn").on('click', function () {
        if ($(this).hasClass('sortNum1')) {
            queryVal['sort'] = 2;
            location.href = createUrl();
        } else if ($(this).hasClass('sortNum2')) {
            queryVal['sort'] = 1;
            location.href = createUrl();
        } else {
            queryVal['sort'] = 2;
            location.href = createUrl();
        }
    });


    //-----------------フォトインスペクタ関連------------------
    var folderNameArr = ['zero', 'first', 'second', 'third', 'fourth', 'fifth'];
    var currentFolderNum = 3;

    //最初に最後以外のマイナスおよび第４階層以降のフォルダを消す（イニシャライズ）
    for (i = 2; i <= 5; i++) {
        $('#' + folderNameArr[i] + 'Minus').css('visibility', 'hidden');
    }
    $('#' + folderNameArr[currentFolderNum] + 'Minus').css('visibility', 'visible');
    for (i = currentFolderNum + 1; i <= 5; i++) {
        $('#' + folderNameArr[i] + 'FoloderWrap').css('display', 'none');
    }

    //+ボタンで階層を増やす
    $('#folderPlus').on('click', function (e) {
        e.preventDefault();
        $('#' + folderNameArr[currentFolderNum + 1] + 'FoloderWrap').css('display', 'block');
        $('#' + folderNameArr[currentFolderNum + 1] + 'Minus').css('visibility', 'visible');
        currentFolderNum += 1;

        //+して２階層の場合はマイナスボタンを消さない。また５階層に達した場合は+ボタンを消す。
        if (currentFolderNum != 2) {
            $('#' + folderNameArr[currentFolderNum - 1] + 'Minus').css('visibility', 'hidden');
        }
        if (currentFolderNum == 5) {
            $(this).css('display', 'none');
        }
    });

    //-ボタンで階層を減らす
    $('.minusBtn').on('click', function (e) {
        e.preventDefault();
        //var currentBtnNum=$('.minusBtn').index(this)+2;
        $('#' + folderNameArr[currentFolderNum] + 'Minus').css('visibility', 'hidden');
        $('#' + folderNameArr[currentFolderNum] + 'FoloderWrap').css('display', 'none');
        currentFolderNum -= 1;
        if (currentFolderNum != 1) {
            $('#' + folderNameArr[currentFolderNum] + 'Minus').css('visibility', 'visible');
        }
        if (currentFolderNum == 4) {
            $('#folderPlus').css('display', 'block');
        }
    });


    //----------チェック関連-----------------

    //フォルダーチェックボックスに対してチェックボックスのクリックとフォルダのクリックを区別する

    $(".folderCheckBoxWrapper").on('click',function(e){
        e.stopPropagation();
    });

    //写真に関しては二重にチェックされるの防ぐ
    $(".photoCheckBoxWrapper").on("click", function (e) {
        e.preventDefault();
    });


    //クリックした場合、色を変えてチェックボックスを選択する。
    $("div[class*=photoFrame]").hover(function () {

        $(this).css("background-color", "#afd9ee");

    }, function () {

        var index = $("div[class*=photoFrame]").index(this);
        if ($('input[class="photoCheckBox"]:eq(' + index + ')').prop("checked") == false) {
            $(this).css("background-color", "#fff");
        }

    });


    $("div[class*=photoFrame]").on('click', function (e) {

        //何番目のフレームが選択されたか格納する
        var index = $("div[class*=photoFrame]").index(this);
        var checkBox = $('input[class="photoCheckBox"]:eq(' + index + ')');

        if (checkBox.prop("checked")) {
            $(checkBox).prop("checked", false);
        } else {
            $(checkBox).prop("checked", true);
        }

        //チェックボックスが入っているかどうかチェックしてボタンの有効無効を切り替える
        switchBtnEffect(judgeCheckBox());

        return true;
    });


    //-----------------ドラッグアップロード関連------------------
    //ドラッグエリアの指定
    $('#dropArea').on('dragenter', function (e) {
        e.stopPropagation();
        e.preventDefault();
    }).on('dragover', function (e) {
        e.stopPropagation();
        e.preventDefault();
    }).on('drop', function (e) {
        e.preventDefault();
        var files = e.originalEvent.dataTransfer.files;

        handleFileUpload(files, $(this));
    });

    //余計なイベントを回避する
    $(document).on('dragenter', function (e) {
        e.stopPropagation();
        e.preventDefault();
    }).on('dragover', function (e) {
        e.stopPropagation();
        e.preventDefault();
    }).on('drop', function (e) {
        e.stopPropagation();
        e.preventDefault();
    });


});


//クエリを取得する
var queryVal = GetQueryString();

//削除した場合はアラートを出す
if (queryVal['deleted'] == 1) {
    alert("削除しました");
    //一回しかでないよう、次にロードするときはクエリを消しておく
    delete queryVal["deleted"];
}


//クエリの取得
function GetQueryString() {
    var result = {};
    if (1 < window.location.search.length) {
        // 最初の1文字 (?記号) を除いた文字列を取得する
        var query = window.location.search.substring(1);

        // クエリの区切り記号 (&) で文字列を配列に分割する
        var parameters = query.split('&');

        for (var i = 0; i < parameters.length; i++) {
            // パラメータ名とパラメータ値に分割する
            var element = parameters[i].split('=');

            var paramName = decodeURIComponent(element[0]);
            var paramValue = decodeURIComponent(element[1]);

            // パラメータ名をキーとして連想配列に追加する
            result[paramName] = paramValue;
        }
    }
    return result;
}

//チェックが入っているかどうかを返す。チェックが一つでも入っていればTrue
function judgeCheckBox() {
    var checkBoxAll = $('input[type=checkbox]');
    var judge = false;
    checkBoxAll.each(function () {
        if ($(this).prop('checked') == true) {
            judge = true;
        }
    });
    return judge;
}

//チェックが入っている数を数える
function countCheckBox() {
    var count = 0;
    var checkBoxAll = $('input[type=checkbox]');
    checkBoxAll.each(function () {
        if ($(this).prop('checked') == true) {
            count += 1;
        }
    });

    return count;
}

//クエリをくっつけてURLを生成する
function createUrl() {

    var queryUrl = "?";

    for (var key in queryVal) {
        //console.log('key=',queryVal[key]);
        queryUrl += key + "=" + queryVal[key];
        queryUrl += "&";
    }

    queryUrl = queryUrl.substring(0, queryUrl.length - 1);
    return queryUrl;

}

//ドラッグしてファイル格納する
function handleFileUpload(files, obf) {

    for (var i = 0; i < files.length; i++) {
        var fd = new FormData();
        fd.append('file', files[i]);
        sendFileToServer(fd, i, files.length);
    }

}

//ファイルをアップロードする
function sendFileToServer(formData, crt, length) {
    var uploadURL = 'dragUpload.php';
    $.ajax({
        url: uploadURL,
        type: 'POST',
        contentType: false,
        processData: false,
        cache: false,
        data: formData,
        success: function (data) {
            if (crt == length - 1) {
                window.location.href = createUrl();
            }
        }
    });
}

//選択してないと無効化するボタンの処理。引数がtrueであれば選択できるようにする
function switchBtnEffect(judge) {
    if (judge) {

        //全体
        $('div.selectedNeed > .iconBtn').css('opacity', '1.0').css('cursor', 'pointer');

        //削除ボタン
        $("#deleteBtn").attr("data-content", "選択削除").popover({
            trigger: 'hover',
            placement: 'top'
        });
        //削除確定ボタン
        $("#deleteOkBtn").on('click', function () {
            $("#checkBoxEvent").attr("value", "2");
            $("#submitChecked").click();
        });


        //編集ボタン
        $("#editBtn").attr("data-content", "編集").popover({
            trigger: 'hover',
            placement: 'top'
        }).attr('data-toggle', 'modal').on('click', function () {
            $('#countPhoto').text(countCheckBox());
        });
        //編集確定ボタン
        $('#editOkBtn').on('click', function () {
            $('#checkBoxEvent').attr('value', '4');
            $('#submitChecked').click();
        })


        //ダウンロードボタン
        $("#downloadBtn").attr("data-content", "ダウンロード").popover({
            trigger: 'hover',
            placement: 'top'
        }).on('click', function () {
            $("#checkBoxEvent").attr("value", "3");
            $("#submitChecked").click();
        });

        //アルバムボタン
        $("#albumBtn").attr("data-content", "アルバム作成").popover({
            trigger: 'hover',
            placement: 'top'
        });


    } else {
        //全体
        $('div.selectedNeed > .iconBtn').css('opacity', '0.3').css('cursor', 'default');

        $("#deleteBtn").popover('destroy').off('click').removeAttr('data-toggle');
        ;
        $("#editBtn").popover('destroy').off('click').removeAttr('data-toggle');
        $("#downloadBtn").popover('destroy').off('click');
        $("#albumBtn").popover('destroy').off('click');
    }
}