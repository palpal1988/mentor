$(document).ready(function () {
    $('#configList>li').on('click', function (e) {
        $('#configList>li').css('background-color', '#fff');
        $(this).css('background-color', '#afd9ee');
    });
});