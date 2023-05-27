$(document).ready(function() {
	
	var thisMenuNum = $("body").data("name")-1;
	if(thisMenuNum == -1)thisMenuNum = 100;
	$("#gnb li").eq(thisMenuNum).addClass("active");
	var mobileGnbOpen = false;
	var anchor = document.querySelectorAll('#gnb button');
	var gnbClickEnable = true;
	[].forEach.call(anchor, function(anchor) {
		anchor.onclick = function(event) {
			event.preventDefault();
			if (!mobileGnbOpen && gnbClickEnable == true) {
				mobileGnbOpen = true;
				this.classList.add('close');
				$("#gnb").addClass("open");
				$("#gnbFade").stop().fadeIn();
				$("#gnb ul").slideDown(300);
				$("#gnb h1").find("img").attr("src","images/logo2.png");
				$("#gnb ul li").each(function(index) {
					$(this).show();
					TweenLite.to($(this), 0, {css : {marginLeft:0, alpha:1},delay:0});
					TweenLite.from($(this), 0.4, {css : {marginLeft:-300, alpha:0},delay:index/30});
				});
				gnbClickEnable = false;
				setTimeout(function(){ 
					gnbClickEnable = true;
				}, 500);
			} else {
				if(gnbClickEnable){
					mobileGnbOpen = false;
					this.classList.remove('close');
					$("#gnbFade").stop().fadeOut();
					$("#gnb ul").slideUp(300);
					setTimeout(function(){ 
						$("#gnb").removeClass("open"); 
						$("#gnb h1").find("img").attr("src","images/logo.png");
					}, 300);
					$($("#gnb ul li").get().reverse()).each(function(index) {
						TweenLite.to($(this), 0.3, {css : {marginLeft:-300, alpha:0},delay:index/30});
					});
					gnbClickEnable = false;
					setTimeout(function(){ 
						gnbClickEnable = true;
					}, 500);
				}
				
			}
			
		}
	});
	$("#gnbFade").click(function (e) { 
		if (mobileGnbOpen) {
			$("#gnb button").click();
		}
	});
	
	
	
})