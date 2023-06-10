$(document).ready(function() {
	
	//게임 스타트
	$("#intro .start").click(function(){
		//alert("이미 참여하였습니다. \n내일 다시 도전하세요!");
		//gameStart();
		E.RegisterChk({ 'empty':'empty'
			, loginType:'game6'
			, beforeLogin: function(req){
				E.AlertMsg('modal-alert', '알림', '로그인 후 이용 가능합니다.' );
			}
			, callback: function(req){
				$("#main .select_wrap").hide();
				JS.member_info= req.info;
				JS.loginCallback(req);
			}
		});
	});

	//연습하기
	var practice = false;
	$("#intro .test").click(function(){
		practice = true;
		gameStart();
	});
	
	//결과
	function result(score){
		window.score= score;

		console.log("점수="+score);
		if(!practice){
			//실제(솔트 지급)
			//$("#popup_score em").text(score);
			//$("#popup_score").fadeIn();

			JS.RESULT({ 'empty':'empty'
				, thumb:'thumb_game6.jpg'
				, user_type:'game6', user_type2:score
				, loseAfter: function(req){
					console.log( 'lose' );
				}
				, winAfter: function(req){

					E.ChargeChance({'empty':'empty'
						, chance_type:'add'
						, chance_type2:'game6'
						, user_type: score
						, callback:function(req){
							if(req.chance_info){
								//$(".my em:eq(1)").html(parseInt(req.chance_info.chance_cnt).toLocaleString());
								saltUp(Number($('#side em.salt').attr("data-from"))+ (score*req.saltboom));
							}
							if (req.result=='o')
							{
								if (req.saltboom>1)
								{
									//E.AlertMsg('modal-alert','알림',req.saltboom+'배 정립되었습니다.');
									$("#popup_score em").text((score*req.saltboom));
								}
								else{
									$("#popup_score em").text(score);
								}
								$("#popup_score").fadeIn();
							}
							else{
								E.AlertMsg('modal-alert','알림','이미 참여 하셨습니다.');
							}
						}
					});
				}
			});
		}else{
			//연습
			$("#popup_score p").text("연습이 완료되었습니다.");
			$("#popup_score .type .back").hide();
			$("#popup_score .type .reload").show();
			$("#popup_score").fadeIn();
		}
	}

	//연습완료_새로고침
	$($("#popup_score .reload")).click(function (e) { 
		location.reload();
	});
	
	
	//솔트상승 효과
	$('#side em.salt').dynamicNumber({
		group : {
			decimals : '0',
			separator : ',',
		},
		onUpdate : function(n) {
		}
	});
	function saltUp(num) {
		$('#side em.salt').dynamicNumber('go', num);
		setTimeout(() => {
			$('#side em.salt').attr("data-from", num);
		}, 1000);
	}
	
	
	
	
	
	
	
	
	
	
	//---------------------------------------------------------------------------------------------------------

	var mobile = false;
	var mobileKeyWords = new Array('iPhone', 'iPod', 'iPad', 'BlackBerry', 'Android', 'Windows CE', 'LG', 'MOT', 'SAMSUNG', 'SonyEricsson');
	for (var word in mobileKeyWords){
		if (navigator.userAgent.match(mobileKeyWords[word]) != null){
			mobile=true;
			break;
		}
	}

	var gameFirst = true;
	var gameEnd = false;
	var controlEn = false;
	var thisTurn;
	var gameScore = 0;
	var orderSwiper;
	var maxTime = 30;
	var time1 = new TimelineLite();
	var timeInterval;
	var thisTime;
	var feverTime = false;

	gameStart= function(){
		countdown();
	}

	function countdown(){
		clickEnable = true;
		$("#intro .content").hide();
		$("#intro .count span.n3").show();
		setTimeout( function(){
			$("#intro .count span").hide();
			$("#intro .count span.n2").show();
			setTimeout( function(){
				$("#intro .count span").hide();
				$("#intro .count span.n1").show();
				setTimeout( function(){
					$("#intro .count span").hide();
					$("#intro").hide()
					gamePlay();
				} , 1000);
			} , 1000);
		} , 1000);
	}

	
	function gamePlay(){
		controlEn = false;
		thisTurn = 1;
		gameScore = 0;
		comboNum = 0;
		comboTotal = 0;
		$("#combo em").text("");
		$("#score em").text(gameScore);
		$(".orderWrap .swiper-slide").remove();
		$("#stack .stack").remove();
		if(orderSwiper)orderSwiper.destroy();
		
		for(i=1; i<201; i++){
			var rnd = Math.round(Math.random()*3+1);
			$("#game .orderWrap").append('<div class="rnd'+rnd+' order'+i+' swiper-slide"><img src="images/game6/game_order_f'+rnd+'.png"></div>')
		}
		
		orderSwiper = new Swiper(".orderSwiper", {
			slidesPerView : 4,
			touchRatio: 0,
			simulateTouch:false,
		});
		
		thisTime = maxTime;
		$("#timer em").text(thisTime);
		$("#game .feverImg").hide();
		feverTime = false;
		
		
		controlEn = true;
		
		timeStart();
	}
	
	
	
	if(mobile){
		$("#control .left").bind("touchstart",function(e){
			e.preventDefault();
			if(controlEn == true && gameEnd == false){
				if($(".orderWrap .order"+thisTurn).hasClass('rnd1'))correct(1);
				if($(".orderWrap .order"+thisTurn).hasClass('rnd2'))correct(2);
				if($(".orderWrap .order"+thisTurn).hasClass('rnd3'))incorrect();
				if($(".orderWrap .order"+thisTurn).hasClass('rnd4'))incorrect();
			}
		})
		$("#control .right").bind("touchstart",function(e){
			e.preventDefault();
			if(controlEn == true && gameEnd == false){
				if($(".orderWrap .order"+thisTurn).hasClass('rnd1'))incorrect();
				if($(".orderWrap .order"+thisTurn).hasClass('rnd2'))incorrect();
				if($(".orderWrap .order"+thisTurn).hasClass('rnd3'))correct(3);
				if($(".orderWrap .order"+thisTurn).hasClass('rnd4'))correct(4);
			}
		})
	}else{
		$("#control .left").click(function() {
			if(controlEn == true && gameEnd == false){
				if($(".orderWrap .order"+thisTurn).hasClass('rnd1'))correct(1);
				if($(".orderWrap .order"+thisTurn).hasClass('rnd2'))correct(2);
				if($(".orderWrap .order"+thisTurn).hasClass('rnd3'))incorrect();
				if($(".orderWrap .order"+thisTurn).hasClass('rnd4'))incorrect();
			}
		});
		$("#control .right").click(function() {
			if(controlEn && gameEnd == false){
				if($(".orderWrap .order"+thisTurn).hasClass('rnd1'))incorrect();
				if($(".orderWrap .order"+thisTurn).hasClass('rnd2'))incorrect();
				if($(".orderWrap .order"+thisTurn).hasClass('rnd3'))correct(3);
				if($(".orderWrap .order"+thisTurn).hasClass('rnd4'))correct(4);
			}
		});
	}
	
	$(document).keydown(function(event) {
		if (event.keyCode == '37') {
			$("#control .left").click();
		} else if (event.keyCode == '39') {
			$("#control .right").click();
		}
	});
	
	
	
	
	function correct(num){
		console.log("성공");
		controlEn = false;
		$("#game #stack").append('<div class="rnd'+num+' stack'+thisTurn+' stack"><img src="images/game6/game_order_f'+num+'B.png"></div>');
		if(thisTurn == 1){
			TweenLite.to($('.stack'+thisTurn), 0.1, {css : {"top":"60%"},delay:0,ease : Linear.easeNone, onComplete:nextTurn});
		}else{
			TweenLite.to($('.stack'+thisTurn), 0.1, {css : {"top":$(".stack"+Number(thisTurn-1)).position().top-$(".stack1").height()/1},delay:0,ease : Linear.easeNone, onComplete:nextTurn});
		}
		
		comboNum++;
		if(comboNum >= 3)comboPlay();
	}
	function incorrect(){
		console.log("실패");
		$("#game #order").addClass('headShake');
		setTimeout(function(){
			$("#game #order").removeClass('headShake');
		},400);
		timeStop();
		if(thisTime>6){
			thisTime = thisTime-5;
			timeStart();
		}else{
			thisTime = 0;
			timeOut();
		}
		comboNum = 0;
		comboTotal = 0;
	}
	
	function nextTurn(){
		console.log("nextTurn");
		gameScore = gameScore+2;
		if(feverTime)gameScore = gameScore+2;
		$("#score em").text(gameScore);
		if(thisTurn > 2){
			$(".stack").each(function(index) {
				TweenLite.to($('.stack'+Number(index+1)), 0.1, {css : {"top":$(this).position().top+$(".stack1").height()/1},delay:0,ease : Linear.easeNone});
			});
		}
		setTimeout(function(){
			thisTurn++;
			orderSwiper.slideNext();
			controlEn = true;
		},150);
	}
	
	function timeStart(){
		//console.log(pos)
		time1.pause();
		TweenLite.killTweensOf(time1);
		time1 = new TimelineLite();
		$("#timer em").text(thisTime);
		
		clearInterval(timeInterval);
		timeInterval = setInterval(function () {
			thisTime--;
			$("#timer em").text(thisTime);
			if(thisTime <= 10)feverTimeStart();
			if(thisTime <= 0)timeOut();
		}, 1000);
	}

	function timeOut(){
		console.log("timeOut")
		timeStop();
		clearInterval(timeInterval);
		$("#timer em").text(0);
		gameComplete();
	}

	function timeStop(){
		clearInterval(timeInterval);
		TweenLite.killTweensOf(time1);
		time1.pause();
	}
	
	
	
	function feverTimeStart(){
		if(!feverTime){
			feverTime = true;
			$("#game #timer").addClass("fever");
			$("#game .feverImg").show();
			TweenLite.from($("#game .feverImg"), 0.5, {css : {scale:"1.5", alpha : 0},delay:0,ease: Back.easeOut});
			setTimeout(function(){
				$("#game .feverImg").fadeOut();
			},1200);
		}
		
	}
	
	function gameComplete(){
		console.log("게임끝!");
		controlEn = false;
		setTimeout( function(){
			if(!gameEnd)result(gameScore);
			gameEnd = true;
		} , 500 );
	}
	
	//콤보
	var comboNum = 0;
	var comboTotal = 0;
	function comboPlay(){
		comboTotal++;
		if(comboTotal>1){
			$("#combo em").text("X"+comboTotal);
		}else{
			$("#combo em").text("");
		}
		$("#combo").stop().fadeIn(50);
		setTimeout(function(){
			$("#combo").fadeOut(50);
		},350);
		gameScore = gameScore+1;
		if(feverTime)gameScore = gameScore+1;
		$("#score em").text(gameScore);
	}

	
})