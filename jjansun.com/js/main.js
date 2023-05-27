$(document).ready(function() {

//	window.swiper0 = new Swiper("#banner .swiper-container", {
//		slidesPerView : 1,
//		loop : true,
//		observer : true,
//		observeParents : true,
//		autoplay: {
//			delay: 5000,
//			disableOnInteraction: false,
//		},
//		pagination : {
//			el : "#banner .swiper-pagination",
//			clickable : true,
//		},
//	});
	var rndSlide1 = Math.round(Math.random()*7+1);
	var swiper1 = new Swiper("#section1 .swiper-container", {
		slidesPerView : 2,
		spaceBetween : 10,
		loop : true,
		observer : true,
		initialSlide: rndSlide1,
		observeParents : true,
		autoplay: {
			delay: 6000,
			disableOnInteraction: false,
		},
		pagination : {
			el : "#section1 .swiper-pagination",
			clickable : true,
		},
		navigation : {
			nextEl : "#section1 .swiper-button-next",
			prevEl : "#section1 .swiper-button-prev",
		},
	});

	var rndSlide2 = Math.round(Math.random()*2+1);
	var swiper2 = new Swiper("#section2 .swiper-container", {
		slidesPerView : 2,
		spaceBetween : 10,
		loop : true,
		observer : true,
		initialSlide: rndSlide2,
		observeParents : true,
		autoplay: {
			delay: 7000,
			disableOnInteraction: false,
		},
		pagination : {
			el : "#section2 .swiper-pagination",
			clickable : true,
		},
		navigation : {
			nextEl : "#section2 .swiper-button-next",
			prevEl : "#section2 .swiper-button-prev",
		},
	});
	
})
	function sliderReload(slider){
		setTimeout(function(){
			slider.update();
			slider.slideTo(0);
		},100);
	}