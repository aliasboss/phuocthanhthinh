displayNotification = function () {
    $.get(url + '/get-notification', {}, function (res) {
        var data = JSON.parse(res);
        if (data['show']) {
//            $("#modal_notification .modal-body").html(data['content']);
//            $("#modal_notification .modal-header h3").html(data['title']);
//
//            $("#modal_notification").modal('show');
            $('#modal-content-demo .wrapper').html(data['content']);
            $('#modal-content-demo #modal-header').html(data['title']);

            
            $('#modal-content-demo').apFullscreenModal({
                openSelector: '#open-modal',                
                autoOpen: true
              });
        }
    });
};

getGiaVangCaMau = function () {
    $.post(url + '/banggiavangcamau', {}, function (res) {
        var data = JSON.parse(res);
        $("#v999_m").html(data['v999']['m']);
        $("#v999_b").html(data['v999']['b']);

        $("#v980_m").html(data['v980']['m']);
        $("#v980_b").html(data['v980']['b']);

        $("#v960_m").html(data['v960']['m']);
        $("#v960_b").html(data['v960']['b']);

        $("#v750_m").html(data['v750']['m']);
        $("#v750_b").html(data['v750']['b']);

        $("#v680_m").html(data['v680']['m']);
        $("#v680_b").html(data['v680']['b']);

        $("#v610_m").html(data['v610']['m']);
        $("#v610_b").html(data['v610']['b']);

        $("#v585_m").html(data['v585']['m']);
        $("#v585_b").html(data['v585']['b']);

        $("#sjc_m").html(data['sjc']['m']);
        $("#sjc_b").html(data['sjc']['b']);

        var currentDate = new Date();
        $("#time_update").html("Cập nhật vào lúc: " + currentDate.toLocaleString());
    });
};


getGiaTheGioi = function () {
    $.post(url + '/giathegioi', {}, function (res) {
        var obj = jQuery.parseJSON(res);
        var ask = parseFloat(obj.Ask);
        var bid = parseFloat(obj.Bid);
        var spread_val = parseFloat(obj.Spread);
        $("#ask").html(ask.toFixed(2));
        $("#bid").html(bid.toFixed(2));
        $("#spread_val").html(spread_val.toFixed(1));

        if (obj.SwapLong > 0) {
            $("#css_buy").removeClass('down');
            $("#css_buy").removeClass('no-change');
            $("#css_buy").addClass('up');
        } else if (obj.SwapLong < 0) {
            $("#css_buy").removeClass('up');
            $("#css_buy").removeClass('no-change');
            $("#css_buy").addClass('down');
        } else {
            $("#css_buy").addClass('no-change');
        }
        if (obj.SwapShort > 0) {
            $("#css_sell").removeClass('down');
            $("#css_sell").removeClass('no-change');
            $("#css_sell").addClass('up');
        } else if (obj.SwapShort < 0) {
            $("#css_sell").removeClass('up');
            $("#css_sell").removeClass('no-change');
            $("#css_sell").addClass('down');
        } else {
            $("#css_sell").addClass('no-change');
        }

    });
};


displayGold = function () {
    $.post(url + '/displaygold', {}, function (res) {
        $('.giavang-maiquyen').html(res);
        var giavangmua = [];
        var giavangban = [];
//            var giangoaite = [];
        var magiavang = ["95", "FAN", "BK", "SJC", "US"];
//            var mangoaite = ["GBP","JPY","CHF","CAD","AUD","EUR","TWD","SGD","USD","MYR","KRW","HKD","NZD","CNY","THB"];
        giavangmua[$(".giavang-maiquyen table:eq(0) td:eq(9)").html()] = parseInt($(".giavang-maiquyen table:eq(0) td:eq(10)").html());
        giavangban[$(".giavang-maiquyen table:eq(0) td:eq(9)").html()] = parseInt($(".giavang-maiquyen table:eq(0) td:eq(11)").html());
        giavangmua[$(".giavang-maiquyen table:eq(0) td:eq(12)").html()] = $(".giavang-maiquyen table:eq(0) td:eq(13)").html();
        giavangban[$(".giavang-maiquyen table:eq(0) td:eq(12)").html()] = $(".giavang-maiquyen table:eq(0) td:eq(14)").html();
        giavangmua[$(".giavang-maiquyen table:eq(0) td:eq(15)").html()] = $(".giavang-maiquyen table:eq(0) td:eq(16)").html();
        giavangban[$(".giavang-maiquyen table:eq(0) td:eq(15)").html()] = $(".giavang-maiquyen table:eq(0) td:eq(17)").html();
        giavangmua[$(".giavang-maiquyen table:eq(0) td:eq(18)").html()] = $(".giavang-maiquyen table:eq(0) td:eq(19)").html();
        giavangban[$(".giavang-maiquyen table:eq(0) td:eq(18)").html()] = $(".giavang-maiquyen table:eq(0) td:eq(20)").html();
        giavangmua[$(".giavang-maiquyen table:eq(0) td:eq(21)").html()] = $(".giavang-maiquyen table:eq(0) td:eq(22)").html();
        giavangban[$(".giavang-maiquyen table:eq(0) td:eq(21)").html()] = $(".giavang-maiquyen table:eq(0) td:eq(23)").html();
//            
//            
//            
        var index;
        var gia_ban = 0;
        var gia_mua = 0;
        for (index = 0; index < magiavang.length; index++) {
            //console.log(magiavang[index]);
            gia_mua = giavangmua[magiavang[index]] * 1;
            gia_ban = giavangban[magiavang[index]] * 1;
            $("#M_" + magiavang[index]).html(gia_mua.format());
            $("#B_" + magiavang[index]).html(gia_ban.format());
        }

        var currentDate = new Date();
        $("#waiting").html("Giá được cập nhật lúc: " + currentDate.toLocaleString());
    });
};

displayCurrence = function () {
    $.post(url + '/displaycurrence', {}, function (res) {
        $('.giangoaite-maiquyen').html(res);

        var giangoaite = [];
        var mangoaite = ["GBP", "JPY", "CHF", "CAD", "AUD", "EUR", "TWD", "SGD", "MYR", "KRW", "HKD", "NZD", "CNY", "THB", "RUB", "IDR", "PHP", "LAK", "KHR", "USD"];

        var flagMa = 15;
        var flagGia = 17;
        for (index = 0; index < mangoaite.length; index++) {
            giangoaite[$(".giangoaite-maiquyen table:eq(0) td:eq(" + flagMa.toString() + ")").html()] = $(".giangoaite-maiquyen table:eq(0) td:eq(" + flagGia.toString() + ")").html();
            flagMa = flagMa + 5;
            flagGia = flagGia + 5;
        }
        var gia = 0;
        for (index = 0; index < mangoaite.length; index++) {
            gia = giangoaite[mangoaite[index]] * 1;
            $("#" + mangoaite[index]).html(gia.format());
        }

        //var currentDate = new Date();
        //$("#waiting").html("Giá được cập nhật lúc: "+currentDate.toLocaleString());
    });
};

fix_bang_gia_ca_mau = function () {
    var document_w = $(document).width();
    var window_w = $(window).width();
    if (document_w < 550 || window_w < 550) {
        $(".table-bang-gia-ca-mau td").css("font-size", "15px");
    }
};

show_modal_login = function () {
    var strModal = $(".modal-body").html();
    if (strModal.length > 30) {
        $("#modal_login").modal('show');
    }
};

function dieuchinhgia(gia) {
    //console.log(typeof gia);
    if (gia.indexOf(',') > 0) {
        gia = gia.replace(",", "");
        var giaN = gia * 1;
        giaN = giaN / 10;
        return giaN.format();
    }



    return Math.round(gia * 1);
}


displayGoldTE = function () {
    $.post(url + '/get-gia-vang', {}, function (res) {
        $('.giavang-maiquyen').html(res);

        $(".giavang-maiquyen table:eq(0)").width(400);

        $(".giavang-maiquyen table:eq(1)").width(400);

        var str_bk_ban = $(".giavang-maiquyen table:eq(1) td:eq(7)").html();
        var str_bk_mua = $(".giavang-maiquyen table:eq(1) td:eq(6)").html();
        var str_bp_ban = $(".giavang-maiquyen table:eq(1) td:eq(5)").html();
        var str_bp_mua = $(".giavang-maiquyen table:eq(1) td:eq(4)").html();
        var str_95_ban = $(".giavang-maiquyen table:eq(1) td:eq(3)").html();
        var str_95_mua = $(".giavang-maiquyen table:eq(1) td:eq(2)").html();
        var str_sjc_ban = $(".giavang-maiquyen table:eq(1) td:eq(11)").html();
        var str_sjc_mua = $(".giavang-maiquyen table:eq(1) td:eq(10)").html();

        $("#M_FAN").html(str_bp_mua.trim());
        $("#B_FAN").html(str_bp_ban.trim());

        $("#M_BK").html(str_bk_mua.trim());
        $("#B_BK").html(str_bk_ban.trim());

        $("#M_95").html(str_95_mua.trim());
        $("#B_95").html(str_95_ban.trim());

        $("#M_SJC").html(str_sjc_mua.trim());
        $("#B_SJC").html(str_sjc_ban.trim());

        var currentDate = new Date();
        $("#waiting").html("Giá được cập nhật lúc: " + currentDate.toLocaleString());
    });
};

displayCurrenceTE = function () {
    $.post(url + '/get-ngoai-te', {}, function (res) {
        $('.giangoaite-maiquyen').html(res);
        $(".giangoaite-maiquyen table:eq(0)").width(400);

        $(".giangoaite-maiquyen table:eq(2)").width(500);

        var gia = new Array();

        gia["AUD"] = $(".giangoaite-maiquyen table:eq(2) tr:eq(2) .price1").html();//AUD
        gia["CAD"] = $(".giangoaite-maiquyen table:eq(2) tr:eq(3) .price1").html();//CAD
        gia["CHF"] = $(".giangoaite-maiquyen table:eq(2) tr:eq(4) .price1").html();//CHF
        gia["EUR"] = $(".giangoaite-maiquyen table:eq(2) tr:eq(5) .price1").html();//EUR
        gia["GBP"] = $(".giangoaite-maiquyen table:eq(2) tr:eq(6) .price1").html();//GBP
        gia["JPY"] = $(".giangoaite-maiquyen table:eq(2) tr:eq(7) .price1").html();//JPY
        gia["NZD"] = $(".giangoaite-maiquyen table:eq(2) tr:eq(8) .price1").html();//NZD

        var flag = $(".giangoaite-maiquyen table:eq(2) tr:eq(9) th:eq(0)").html();
        if (typeof (flag) === "undefined")
            gia["K18"] = $(".giangoaite-maiquyen table:eq(2) tr:eq(9) td:eq(1)").html();
        else
            gia["K18"] = $(".giangoaite-maiquyen table:eq(2) tr:eq(9) td:eq(0)").html();

        gia["HKD"] = $(".giangoaite-maiquyen table:eq(2) tr:eq(10) .price1").html();//HKD
        gia["SGD"] = $(".giangoaite-maiquyen table:eq(2) tr:eq(11) .price1").html();
        gia["THB"] = $(".giangoaite-maiquyen table:eq(2) tr:eq(12) .price1").html();
        gia["CNY"] = $(".giangoaite-maiquyen table:eq(2) tr:eq(13) .price1").html();
        gia["KRW"] = $(".giangoaite-maiquyen table:eq(2) tr:eq(14) .price1").html();
        gia["MYR"] = $(".giangoaite-maiquyen table:eq(2) tr:eq(15) .price1").html();
        gia["TWD"] = $(".giangoaite-maiquyen table:eq(2) tr:eq(16) .price1").html();
        gia["BND"] = $(".giangoaite-maiquyen table:eq(2) tr:eq(17) .price1").html();
        gia["IDR"] = $(".giangoaite-maiquyen table:eq(2) tr:eq(18) .price1").html();

        $("#AUD").html(dieuchinhgia(gia["AUD"]));
        $("#CAD").html(dieuchinhgia(gia["CAD"]));
        $("#CHF").html(dieuchinhgia(gia["CHF"]));
        $("#EUR").html(dieuchinhgia(gia["EUR"]));
        $("#GBP").html(dieuchinhgia(gia["GBP"]));
        $("#JPY").html(dieuchinhgia(gia["JPY"]));
        $("#K18").html(dieuchinhgia(gia["K18"]));
        $("#HKD").html(dieuchinhgia(gia["HKD"]));
        $("#SGD").html(dieuchinhgia(gia["SGD"]));
        $("#THB").html(dieuchinhgia(gia["THB"]));
        $("#CNY").html(dieuchinhgia(gia["CNY"]));
        $("#KRW").html(dieuchinhgia(gia["KRW"]));
        $("#MYR").html(dieuchinhgia(gia["MYR"]));
        $("#TWD").html(dieuchinhgia(gia["TWD"]));
        $("#M_US").html(dieuchinhgia(gia["K18"]));

    });
};





Number.prototype.format = function (n, x) {
    var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
    return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&,');
};