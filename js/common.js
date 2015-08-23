/**
 * Created by NakajimaTakaharu on 15/08/16.
 */

function c_init(){

    $.widget("my.comboboxex", {
        _create: function () {
            this.wrapper = $("<span>")
                .addClass("custom-combobox")
                .insertAfter(this.element);
            this.element.hide();
            this._createAutocomplete();
            this._createShowAllButton();
        },

        _createAutocomplete: function () {
            var select = this.element;
            var selected = select.children(":selected"),
                value = selected.val() ? selected.text() : "";

            this.input = $("<input>")
                .appendTo(this.wrapper)
                .val(value)
                .attr({
                    title: "",
                    placeholder: "自由記入もしくは右矢印から選択できます"
                })
                .addClass("custom-combobox-input form-control")
                // Combobox の内容をオートコンプリートのリストする
                .autocomplete({
                    delay: 0,
                    minLength: 0,
                    source: $.proxy(this, "_source")
                })
                .tooltip();

            this._on(this.input, {
                autocompleteselect: function (event, ui) {
                    ui.item.option.selected = true;
                    this._trigger("select", event, {
                        item: ui.item.option
                    });
                },
                autocompletechange: "_removeIfInvalid"
            });
        },

        // request に一致するものを返す
        _source: function (request, response) {
            var matcher = new RegExp($.ui.autocomplete.escapeRegex(request.term), "i");
            response(this.element.children("option").map(function () {
                    var text = $(this).text();
                    if (this.value && ( !request.term || matcher.test(text) ))
                        return {
                            label: text,
                            value: text,
                            option: this
                        };
                })
            );
            if (!request.term) {
                // 空入力なのでコンボボックスの選択をクリア
                this.element.val("");
            }
        },

        _removeIfInvalid: function (event, ui) {
            // 一覧の項目を選択している場合 ui.item は選択値がはいっている
            // その場合は何もしないでリターン
            if (ui.item) {
                return;
            }

            // リスト内を検索 (大文字小文字区別しない)
            var value = this.input.val(),
                valueLowerCase = value.toLowerCase(),
                valid = false;
            this.element.children("option").each(function () {
                if ($(this).text().toLowerCase() === valueLowerCase) {
                    this.selected = valid = true;
                    return false;
                }
            });

            // 見つかった場合、何もしないでリターン
            if (valid) {
                return;
            }
        },

        _destroy: function () {
            this.wrapper.remove();
            this.element.show();
        },

        _createShowAllButton: function () {
            var input = this.input;
            var wasOpen = false;

            $("<a>")
                .attr("tabIndex", -1)
                .appendTo(this.wrapper)
                .button({
                    icons: {
                        primary: "ui-icon-triangle-1-s"
                    },
                    text: false
                })
                .mousedown(function () {
                    wasOpen = input.autocomplete("widget").is(":visible");
                })
                .removeClass("ui-widget ui-widget-header ui-widget-content ui-corner-all")
                .addClass("custom-combobox-toggle ")
                .click(function (e) {

                    input.focus();
                    // すでに一覧表示されていたら閉じる
                    if (wasOpen) {
                        return;
                    }

                    // 空き文字列でリスト検索することで一覧表示
                    input.autocomplete("search", "");
                });
        }

    });

// See http://stackoverflow.com/questions/2435964/jqueryui-how-can-i-custom-format-the-autocomplete-plug-in-results for more info.
// 入力文字列とコンボボックスのリスト内の項目が一致する部分をハイライト
    $.extend($.ui.autocomplete.prototype, {
        _renderItem: function (ul, item) {
            var re = new RegExp("^" + this.term);
            var t = item.label.replace(re, "<span style='font-weight:bold;color:#169586;'>" +
                this.term +
                "</span>");
            return $("<li></li>")
                .data("item.autocomplete", item)
                .append("<a>" + t + "</a>")
                .appendTo(ul);
        }
    });

// コンボボックスを拡張
    $(".pcombobox").comboboxex();

//データピッカー

    $( ".datepicker" ).datepicker({
        dateFormat: 'yy-mm-dd',
        yearSuffix: '年',
        showMonthAfterYear: true,
        monthNames: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
        dayNames: ['日', '月', '火', '水', '木', '金', '土'],
        dayNamesMin: ['日', '月', '火', '水', '木', '金', '土'],
        hideIfNoPrevNext: true,
        firstDay:1
    });

//マテリアルデザインの初期化
    $.material.init();
}
