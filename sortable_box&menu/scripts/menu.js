$(function() {
	$("#menu a").hover(function() {
		goTo($(this).attr("id"));
		$("#menu a").removeClass("tp");
		$(this).addClass("tp");
	}, function() {
		$("#menu a").removeClass("tp");
		$("#" + page).addClass("tp");
	});
	$("#menu").mouseleave(function() {
		goTo(page);
	});
	setPage(page);
});