$(function() {
	$(".box .title").disableSelection();
	newIndex = "";
	$("#boxes").sortable({
		revert: true,
		handle: ".title",
		start: function(event,ui) {
			$(ui.item).css("opacity","0.6");
		},
		stop: function(event,ui) {
			$(ui.item).css("opacity","1");
			$(".box").each(function() {
				newIndex = newIndex + $(this).attr("id") + "=" + $(this).index() + ";";
			});
			creaCookie("boxs_order",newIndex, 525960);
		}
	});
	$("#icon").click(function() {
		if($("#colorpicker").css("left") == "0px") {
			$("#colorpicker").animate({
				left: "-160px"
			}, 200);
		} else {
			$("#colorpicker").animate({
				left: "0px"
			}, 500);
		}
	});
	$(".news .title").hover(function() {
		var shortitle = $(this).html();
		$(this).html("<marquee>" + $(this).attr("alt") + "</marquee>");
		$(this).attr("alt",shortitle);
	}, function() {
		var longtitle = $(this).find("marquee").html();
		$(this).remove("marquee");
		$(this).html($(this).attr("alt"));
		$(this).attr("alt",longtitle);
	});
	$(".color:not(.active)").on({
		click: function() {
			creaCookie("color",$(this).attr("rel"), 525960);
			$(".title, #menu, .box, .news").removeClass("black red blue green violet").addClass($(this).attr("rel"));
			$("#icon").css("background",$(this).attr("rel"));
			$("#colorpicker").animate({
				left: "-160px"
			}, 200);
			location.reload();
		},
		mouseenter: function() {
			$(this).animate({
				opacity: 1,
				borderColor: "#000"
			}, 750);
		},
		mouseleave: function() {
			$(this).animate({
				opacity: 0.6,
				borderColor: "#888"
			}, 300);
		}
	});
});