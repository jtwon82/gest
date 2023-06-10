$(document).ready(function() {
	
	//게임 스타트
	$("#intro .start").click(function(){
		//alert("이미 참여하였습니다. \n내일 다시 도전하세요!");
		//gameStart();
		E.RegisterChk({ 'empty':'empty'
			, loginType:'game10'
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
			$("#popup_score em").text(score);
			$("#popup_score").fadeIn();
			
			JS.RESULT({ 'empty':'empty'
				, thumb:'thumb_game10.jpg'
				, user_type:'game10', user_type2:score
				, loseAfter: function(req){
					console.log( 'lose' );
				}
				, winAfter: function(req){

					E.ChargeChance({'empty':'empty'
						, chance_type:'add'
						, chance_type2:'game10'
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
	
	
	var score = 0;
	var bgT = new TimelineLite();
	var state = 20;
	var down_interval;
	var time1 = new TimelineLite();
	
	$("#score em").text(score);
	clearInterval(down_interval);

	gameStart= function(){
		countdown();
	}

	function countdown(){
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
		makeBg();
		$("#bee").show();
		clickEnable = true;
		time_countdown();
		down_interval = setInterval(down, 70);
		hitTestBGStart();
		hitTestStart();
	}

	function down(){
		$("#bee").css("top",state + "%");
		state = state+3;
		//console.log(state);
		if(state >= 85){
			$("#wrap #game #bee span").attr("class","clash");
			$("#bee").css("top",74 + "%");
			endGame();
		}
	}

	function up(){
		$("#bee").css("top",state + "%");
		state = state - 20;
		if(state <= 0){
			state = 0;
		}
		$("#bee").css("top",state + "%")
	}
	
	$('#game').on('mousedown touchstart', function(e) {
		e.preventDefault();
		if(clickEnable)up();
	}).on('mouseup touchend', function(e) {
		//e.preventDefault();
	});

	function makeBg(){
		var bgWidth = $("#bgContainer span.n0").width()-1;
		for(var i = 1; i < 10; i++){
			$("#bgContainer").append('<span class="'+"n"+i+'"><img src="images/game10/game_bg.jpg"></span>');
			$("#bgContainer span.n"+i).css("left",bgWidth*i);
		}
		setTimeout( function(){
			for(var k = 0; k < 10; k++){
				var rnd = Math.round(Math.random()*3);
				$("#bgContainer span.n"+k).append('<em class="rock obj"><div class="hit"></div></em>');
				if(rnd == 0){
					$("#bgContainer span.n"+k).append('<em class="treasure item1 obj n'+0+'"></em>');
					$("#bgContainer span.n"+k).append('<em class="treasure item1 obj n'+1+'"></em>');
					$("#bgContainer span.n"+k).append('<em class="treasure item1 obj n'+2+'"></em>');
					$("#bgContainer span.n"+k).append('<em class="treasure item2 obj n'+3+'"></em>');
				}else if(rnd == 1){
					$("#bgContainer span.n"+k).append('<em class="treasure item1 obj n'+0+'"></em>');
					$("#bgContainer span.n"+k).append('<em class="treasure item2 obj n'+1+'"></em>');
					$("#bgContainer span.n"+k).append('<em class="treasure item1 obj n'+2+'"></em>');
					$("#bgContainer span.n"+k).append('<em class="treasure item1 obj n'+3+'"></em>');
				}else{
					$("#bgContainer span.n"+k).append('<em class="treasure item2 obj n'+0+'"></em>');
					$("#bgContainer span.n"+k).append('<em class="treasure item1 obj n'+1+'"></em>');
					$("#bgContainer span.n"+k).append('<em class="treasure item1 obj n'+2+'"></em>');
					$("#bgContainer span.n"+k).append('<em class="treasure item1 obj n'+3+'"></em>');
				}
				if(k == 0){
					$("#bgContainer span.n"+k).find("em.treasure.n"+0).css({"left":Math.round(Math.random()*0 + 30)+"%", "top":Math.round(Math.random()*20 + 25)+"%"}); // 35 / 20~45
					$("#bgContainer span.n"+k).find("em.treasure.n"+1).css({"left":Math.round(Math.random()*10 + 35)+"%", "top":Math.round(Math.random()*23 + 55)+"%"}); // 35~45 / 50~78
					$("#bgContainer span.n"+k).find("em.treasure.n"+2).css({"left":Math.round(Math.random()*15 + 50)+"%", "top":Math.round(Math.random()*15 + 25)+"%"}); // 50~65 / 20~45
					$("#bgContainer span.n"+k).find("em.treasure.n"+3).css({"left":Math.round(Math.random()*20 + 70)+"%", "top":Math.round(Math.random()*23 + 55)+"%"}); // 70~90 / 50~78
					$("#bgContainer span.n"+k).find("em.rock").css({"left":Math.round(Math.random()*13 + 56)+"%", "top":Math.round(Math.random()*30 + 40)+"%"}); 
				}else{
					$("#bgContainer span.n"+k).find("em.treasure.n"+0).css({"left":Math.round(Math.random()*15 + 10)+"%", "top":Math.round(Math.random()*20 + 25)+"%"}); // 10~25 / 20~45
					$("#bgContainer span.n"+k).find("em.treasure.n"+1).css({"left":Math.round(Math.random()*10 + 35)+"%", "top":Math.round(Math.random()*23 + 55)+"%"}); // 35~45 / 50~78
					$("#bgContainer span.n"+k).find("em.treasure.n"+2).css({"left":Math.round(Math.random()*15 + 50)+"%", "top":Math.round(Math.random()*20 + 25)+"%"}); // 50~65 / 20~45
					$("#bgContainer span.n"+k).find("em.treasure.n"+3).css({"left":Math.round(Math.random()*20 + 70)+"%", "top":Math.round(Math.random()*23 + 55)+"%"}); // 70~90 / 50~78
					$("#bgContainer span.n"+k).find("em.rock").css({"left":Math.round(Math.random()*40 + 30)+"%", "top":Math.round(Math.random()*30 + 40)+"%"}); 
				}
				
			}
		} ,100);
		

		$("#bgContainer").css("left","0%");
		bgT = new TimelineLite();
		bgT.to($("#bgContainer"), remitTime, {css : {"left": "-2300%"},delay:0,ease : Linear.easeNone});
	}
	
	var remitTime = 30;
	var time_interval;
	var remitTime10 = false;

	function time_countdown(){
		clearInterval(time_interval);
		time1.pause();
		TweenLite.killTweensOf(time1);
		time1 = new TimelineLite();
		$("#bar span").css("width", "100%");
		time1.to($("#bar span"), remitTime, {css : {"width":"0%"},delay:0,ease : Linear.easeNone});
		time_interval = setInterval(function () {
			remitTime = Number(remitTime-1);
			if(remitTime < 11){
				if(!remitTime10){
					remitTime10 = true;
					$("#bonus").show();
					setTimeout( function(){
						$("#bonus").hide();
					} , 3000);
				}
			}

			if(remitTime <= 0){
				console.log("timeOver!")
				remitTime = 0;
				clearInterval(time_interval);
				clearInterval(down_interval);
				endGame();
			}
			$("#timer em").text(remitTime);
		}, 1000);
	}

	var thisBg = "n0";
	var hitTestBGInterval;
	function hitTestBGStart(){
		hitTestBGInterval = setInterval(function () {
			$("#bgContainer span").each(function(index) {
				if(collision($("#bee .hit"), $(this))){
					if($(this).hasClass("n0"))thisBg = "n0";
					if($(this).hasClass("n1"))thisBg = "n1";
					if($(this).hasClass("n2"))thisBg = "n2";
					if($(this).hasClass("n3"))thisBg = "n3";
					if($(this).hasClass("n4"))thisBg = "n4";
					if($(this).hasClass("n5"))thisBg = "n5";
					if($(this).hasClass("n6"))thisBg = "n6";
					if($(this).hasClass("n7"))thisBg = "n7";
					if($(this).hasClass("n8"))thisBg = "n8";
					if($(this).hasClass("n9"))thisBg = "n9";
				}
				//console.log("현재배경="+thisBg);
			});
		}, 200);
	}
	var hitTestInterval;
	function hitTestStart(){
		hitTestInterval = setInterval(function () {
			$("#bgContainer span."+thisBg+" .rock").each(function(index) {
				if(collision($("#bee .hit"), $(this))){
					$("#wrap #game #bee span").attr("class","clash");
					endGame();					
				}
			});
			$("#bgContainer span."+thisBg+" .treasure").each(function(index) {
				if(collision($("#bee .hit"), $(this))){
					if($(this).hasClass("item1")){
						score = score+3;
					}else{
						score = score+10;
					}
					$(this).remove();
					$("#wrap #game #bee span").attr("class","get");
					if(remitTime<11)score = score + 2;
					$("#score em").text(score);
					setTimeout(function(){
						if(clickEnable)$("#wrap #game #bee span").attr("class","");
					}, 300 );
				}
			});
		}, 100);
	}

	function hitTestStop(){
		clearInterval(hitTestBGInterval);
		clearInterval(hitTestInterval);
	}

	function collision($div1, $div2) {
		var x1 = $div1.offset().left;
		var y1 = $div1.offset().top;
		var h1 = $div1.outerHeight(true);
		var w1 = $div1.outerWidth(true);
		var b1 = y1 + h1;
		var r1 = x1 + w1;
		var x2 = $div2.offset().left;
		var y2 = $div2.offset().top;
		var h2 = $div2.outerHeight(true);
		var w2 = $div2.outerWidth(true);
		var b2 = y2 + h2;
		var r2 = x2 + w2;
		if (b1 < y2 || y1 > b2 || r1 < x2 || x1 > r2)
			return false;
		return true;
	}
	
	function endGame(){
		clickEnable = false;
		clearInterval(time_interval);
		clearInterval(down_interval);
		$("#bonus").hide();
		hitTestStop();
		bgT.pause();
		TweenLite.killTweensOf(bgT);
		time1.pause();
		TweenLite.killTweensOf(time1);
		setTimeout( function(){
			result(score);
		} , 500 );
	}

	
	
	
	
	
	
	
	
})