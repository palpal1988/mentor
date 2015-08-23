/**
 * Created by Photoruction on 15/07/05.
 * main.php
 */



$(document).ready(function () {
    $("#editBtn").attr("data-content", "編集").popover({
        trigger: 'hover',
        placement: 'top'
    });

    $("#deleteBtn").attr("data-content", "選択削除").popover({
        trigger: 'hover',
        placement: 'top'
    });

    $("#downloadBtn").attr("data-content","ダウンロード").popover({
        trigger:'hover',
        placement:'top'
    });

    $('#albumList>li').on('click', function (e) {
        $('#albumList>li').css('background-color', '#fff');
        $(this).css('background-color', '#afd9ee');
    });
});