$(document).ready(function() {
	$(".faq table tr.question").click(function (e) { 
		e.preventDefault();
		$(".faq table tr.question").removeClass("active");
		$(this).addClass("active");
		var thisNum = $(this).data("num");
		$(".faq table tr.answer").hide();
		$(".faq table tr.answer"+thisNum).show();
	});
})