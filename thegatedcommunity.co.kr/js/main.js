$(document).ready(function() {
	
	var contentW;
	var sectionW;
	var sectionPosLeft = new Array();
	var bgPos = new Array();
	var thisNum;
	
	
	$("#content .section").each(function(index) {
		contentW = $("#content").width()
		sectionW = contentW/6;
		sectionPosLeft.push(sectionW*index)
		$(this).css({"width":sectionW,"left":sectionW*index});
		bgPos.push($(this).find("span").css("background-position-x"));
	});
	setArrow();
	
	$(".arrow.prev").click(function() {
		if(thisNum > 0){
			thisNum--;
			openSection(thisNum);
			setArrow();
		}
	});
	$(".arrow.next").click(function() {
		if(thisNum < 5){
			thisNum++;
			openSection(thisNum);
			setArrow();
		}
	});
	
	$("#section5 .addr").click(function(){
		UTIL.addressPopup($('#section5'));
	});
	
	function setArrow(){
		if(thisNum == undefined || thisNum == 0){
			$(".arrow.prev").addClass("disabled");
		}else{
			$(".arrow.prev").removeClass("disabled");
		}
		if(thisNum == 5){
			$(".arrow.next").addClass("disabled");
		}else{
			$(".arrow.next").removeClass("disabled");
		}
		
	}
	
	$("#content .section span.bg").click(function() {
		var num = $(this).parent().index();
		openSection(num);
	});
	
	function openSection(num){
		thisNum = num;
		if(thisNum == 0){
			TweenLite.to($("#content .section").eq(0), 0.5, {css : {"left":0,"width":1000},delay:0.001});
			TweenLite.to($("#content .section").eq(1), 0.5, {css : {"left":1000,"width":sectionW},delay:0});
			TweenLite.to($("#content .section").eq(2), 0.5, {css : {"left":sectionW+1000,"width":sectionW},delay:0});
			TweenLite.to($("#content .section").eq(3), 0.5, {css : {"left":sectionW*2+1000,"width":sectionW},delay:0});
			TweenLite.to($("#content .section").eq(4), 0.5, {css : {"left":sectionW*3+1000,"width":sectionW},delay:0});
			TweenLite.to($("#content .section").eq(5), 0.5, {css : {"left":sectionW*4+1000,"width":sectionW},delay:0});
		}
		if(thisNum == 1){
			TweenLite.to($("#content .section").eq(0), 0.5, {css : {"left":0,"width":sectionW},delay:0});
			TweenLite.to($("#content .section").eq(1), 0.5, {css : {"left":sectionW,"width":1000},delay:0.001});
			TweenLite.to($("#content .section").eq(2), 0.5, {css : {"left":sectionW+1000,"width":sectionW},delay:0});
			TweenLite.to($("#content .section").eq(3), 0.5, {css : {"left":sectionW*2+1000,"width":sectionW},delay:0});
			TweenLite.to($("#content .section").eq(4), 0.5, {css : {"left":sectionW*3+1000,"width":sectionW},delay:0});
			TweenLite.to($("#content .section").eq(5), 0.5, {css : {"left":sectionW*4+1000,"width":sectionW},delay:0});
		}
		if(thisNum == 2){
			TweenLite.to($("#content .section").eq(0), 0.5, {css : {"left":-sectionW,"width":sectionW},delay:0});
			TweenLite.to($("#content .section").eq(1), 0.5, {css : {"left":0,"width":sectionW},delay:0});
			TweenLite.to($("#content .section").eq(2), 0.5, {css : {"left":sectionW,"width":1000},delay:0.001});
			TweenLite.to($("#content .section").eq(3), 0.5, {css : {"left":sectionW+1000,"width":sectionW},delay:0});
			TweenLite.to($("#content .section").eq(4), 0.5, {css : {"left":sectionW*2+1000,"width":sectionW},delay:0});
			TweenLite.to($("#content .section").eq(5), 0.5, {css : {"left":sectionW*3+1000,"width":sectionW},delay:0});
		}
		if(thisNum == 3){
			TweenLite.to($("#content .section").eq(0), 0.5, {css : {"left":-sectionW*2,"width":sectionW},delay:0});
			TweenLite.to($("#content .section").eq(1), 0.5, {css : {"left":-sectionW,"width":sectionW},delay:0});
			TweenLite.to($("#content .section").eq(2), 0.5, {css : {"left":0,"width":sectionW},delay:0});
			TweenLite.to($("#content .section").eq(3), 0.5, {css : {"left":sectionW,"width":1000},delay:0.001});
			TweenLite.to($("#content .section").eq(4), 0.5, {css : {"left":sectionW+1000,"width":sectionW},delay:0});
			TweenLite.to($("#content .section").eq(5), 0.5, {css : {"left":sectionW*2+1000,"width":sectionW},delay:0});
		}
		if(thisNum == 4){
			TweenLite.to($("#content .section").eq(0), 0.5, {css : {"left":-sectionW*3,"width":sectionW},delay:0});
			TweenLite.to($("#content .section").eq(1), 0.5, {css : {"left":-sectionW*2,"width":sectionW},delay:0});
			TweenLite.to($("#content .section").eq(2), 0.5, {css : {"left":-sectionW,"width":sectionW},delay:0});
			TweenLite.to($("#content .section").eq(3), 0.5, {css : {"left":0,"width":sectionW},delay:0});
			TweenLite.to($("#content .section").eq(4), 0.5, {css : {"left":sectionW,"width":1000},delay:0.001});
			TweenLite.to($("#content .section").eq(5), 0.5, {css : {"left":sectionW+1000,"width":sectionW},delay:0});
		}
		if(thisNum == 5){
			TweenLite.to($("#content .section").eq(0), 0.5, {css : {"left":contentW-1000-sectionW*5,"width":sectionW},delay:0});
			TweenLite.to($("#content .section").eq(1), 0.5, {css : {"left":contentW-1000-sectionW*4,"width":sectionW},delay:0});
			TweenLite.to($("#content .section").eq(2), 0.5, {css : {"left":contentW-1000-sectionW*3,"width":sectionW},delay:0});
			TweenLite.to($("#content .section").eq(3), 0.5, {css : {"left":contentW-1000-sectionW*2,"width":sectionW},delay:0});
			TweenLite.to($("#content .section").eq(4), 0.5, {css : {"left":contentW-1000-sectionW,"width":sectionW},delay:0});
			TweenLite.to($("#content .section").eq(5), 0.5, {css : {"left":contentW-1000,"width":1000},delay:0.001});
		}
		setArrow();
		$(".arrow").show();
		
		$(".section span.bg h2").fadeIn();
		$(".section").eq(thisNum).find("span.bg h2").hide();
		
		$(".section").find(".content").fadeOut();
		$(".section").eq(thisNum).find(".content").fadeIn();
		
		$(".section .closeContent").hide();
		$(".section").eq(thisNum).find(".closeContent").fadeIn();
		
		$(".section .dimmed").hide();
		$(".section").eq(thisNum).find(".dimmed").fadeIn();
		
	}
	function closeSection(){
		TweenLite.to($("#content .section").eq(0), 0.5, {css : {"left":0,"width":sectionW},delay:0.001});
		TweenLite.to($("#content .section").eq(1), 0.5, {css : {"left":sectionW,"width":sectionW},delay:0});
		TweenLite.to($("#content .section").eq(2), 0.5, {css : {"left":sectionW*2,"width":sectionW},delay:0});
		TweenLite.to($("#content .section").eq(3), 0.5, {css : {"left":sectionW*3,"width":sectionW},delay:0});
		TweenLite.to($("#content .section").eq(4), 0.5, {css : {"left":sectionW*4,"width":sectionW},delay:0});
		TweenLite.to($("#content .section").eq(5), 0.5, {css : {"left":sectionW*5,"width":sectionW},delay:0});
		$(".arrow").hide();
		
		$(".section span.bg h2").fadeIn();
		$(".section").find(".content").fadeOut();
		$(".section .closeContent").hide();
		$(".section .dimmed").hide();
	}
	
	$("h1 a, .closeContent a").click(function() {
		closeSection();
	});
	
	//openSection(3);
	
	
	$("#section3 ul.list li").click(function() {
		$("#photoGallery").fadeIn();
		var num = $(this).index();
		photoGallery.update();
		photoGallery.slideTo(num,0);
	});
	
	var photoGallery = new Swiper('#photoGallery .swiper-container', {
		slidesPerView : 1,
		spaceBetween: 0,
		navigation : {
			nextEl : '#photoGallery .swiper-button-next',
			prevEl : '#photoGallery .swiper-button-prev',
		},
	});
	
	$("#photoGallery .close").click(function() {
		$("#photoGallery").fadeOut();
	});
	
	$(".popup .close, .popup .closeBtn, #fade").click(function() {
		closePopup();
	})
})

//팝업
function openPopup(id) {
	closePopup();
	var popupid = id
	$('#fade').show();
	var popupleftmargin = ($('#' + popupid).width()) / 2;
	$('#' + popupid).css({"top":$(document).scrollTop() + 100, 'left' : $(window).width() / 2 - popupleftmargin});
	$('#' + popupid).show();
}
function closePopup() {
	$('#fade').hide();
	$('.popup').hide();
	return false
}
function resizeFn() {
	$(".popup").each(function(index) {
		var popupleftmargin = ($(this).width()) / 2;
		$(this).css({"left" : $(window).width() / 2 - popupleftmargin});
	});
}
$(window).on('resize', function() {
	resizeFn();
});
resizeFn();
