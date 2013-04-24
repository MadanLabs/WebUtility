function creaCookie(nome, valore, durata) {
	var scadenza = new Date();
	var adesso = new Date();
	scadenza.setTime(adesso.getTime() + (parseInt(durata) * 60000));
	document.cookie = nome + '=' + escape(valore) + '; expires=' + scadenza.toGMTString() + '; path=/';
}

function readCookie(name) {
    name += '=';
    for (var ca = document.cookie.split(/;\s*/), i = ca.length - 1; i >= 0; i--)
        if (!ca[i].indexOf(name))
            return ca[i].replace(name, '');
}

function setPage(linkId) {
	var obj = $("#" + linkId);
	link_w = obj.width();
	link_l = obj.position().left;
	tip_l = link_l + Math.round(link_w / 2 - 8);
	$("#menu").append("<div id='tip' style='left: " + tip_l + "px;'></div>");
}

function goTo(linkId) {
	var obj = $("#" + linkId);
	var tip_l = $("#tip").position().left;
	var link_l = obj.position().left;
	var link_w = obj.width();
	var diff = link_l - tip_l + Math.round(link_w / 2 - 8);
	$("#tip").stop().animate({
		left: "+=" + diff
	});
}

function openDialog(url, cat) {
	var margin_h = ($(window).height() - 300) / 2;
	$("body").prepend("<div id='overall'><div id='innerel'><div id='close' title='Chiudi' onClick='closeDialog();'></div><div id='text'><center><img src='images/loader.gif' style='margin-top: 100px;' /></center></div></div></div>");
	getContent(url, cat);
	$("#innerel").css("margin-top", margin_h);
}

function closeDialog() {
	$("#overall").fadeOut(300);
}

function getContent(url,cat) {
	var cb = "a";
	var color = readCookie("color");
	if(color == "undefined") color = "black";
	$.post("resources/get_article.php", { link: url, category: cat }, function(data) {
		$("#text").html(data + "<div></div><a href='" + url + "' target='_blank'><button style='float: right; margin-right: 5px;' class='" + readCookie('color') + "'>Vai alla news</button></a><div class='clear'></div>");
	});
}

$(function() {
	var cat = ["vg","tecn","soccer","music"];
	$.each(cat, function(index, value) {
		$.post("resources/get_news.php", { category: value }, function(data) {
			$("#" + value).find(".content").html(data);
		});
	});
});