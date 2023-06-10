$(document).ready(function() {
	
	//게임 스타트
	$("#intro .start").click(function(){
		//alert("이미 참여하였습니다. \n내일 다시 도전하세요!");
		//gameStart();
		E.RegisterChk({ 'empty':'empty'
			, loginType:'game5'
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
				, thumb:'thumb_game5.jpg'
				, user_type:'game5', user_type2:score
				, loseAfter: function(req){
					console.log( 'lose' );
				}
				, winAfter: function(req){

					E.ChargeChance({'empty':'empty'
						, chance_type:'add'
						, chance_type2:'game5'
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
	
	var clickEnable = false;
	var throwEnd = false;
	var swingStart = false;
	var swingResult;
	var gameEnd = false;
	
	var score = 0;
	$("#score em").text(score);
	var strike = 0;
	$("#strike span").attr("class", "s"+strike);

	var ballReamin = 10;
	$("#ballRemain em").text(ballReamin);
		
	
	gameStart=function (){
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
					setTimeout( function(){
						throwBall();
					} , 1000 );
				} , 1000);
			} , 1000);
		} , 1000);
	}
	
	function throwBall(){
		clickEnable = true;
		throwEnd = false;
		
		$("#ball, #zone").show();
		TweenLite.to($("#zone span"), 0, {css:{"scale":1},delay:0, ease: Linear.easeNone});
		$("#ball_fly, #txt").hide();
		
		var rnd = Math.round(Math.random()*2);
		if(rnd == 0){
			//type1
			TweenLite.to($("#ball"), 0, {css:{"top":"30%", "left":"34%" , scale:0.6, rotation:0, alpha:1},delay:0, ease: Linear.easeNone});
			TweenLite.to($("#ball"), 0.8, {css:{"top":"68%", "left":"45%" , scale:1, rotation:800},delay:0.1, ease: Linear.easeNone});
			TweenLite.to($("#ball"), 0.4, {css:{"top":"100%"},delay:0.9, ease: Linear.easeNone, onComplete:throwBallEnd});
		}else if(rnd == 1){
			//type2
			TweenLite.to($("#ball"), 0, {css:{"top":"30%", "left":"34%" , scale:0.6, rotation:-250},delay:0, ease: Linear.easeNone});
			TweenLite.to($("#ball"), 0.4, {css:{"top":"55%", "left":"55%" , scale:0.85, rotation:-200},delay:0.1, ease: Linear.easeNone});
			TweenLite.to($("#ball"), 0.4, {css:{"top":"68%", "left":"45%" , scale:1, rotation:-10},delay:0.5, ease: Linear.easeNone});
			TweenLite.to($("#ball"), 0.4, {css:{"top":"100%", "left":"30%"},delay:0.9, ease: Linear.easeNone, onComplete:throwBallEnd});
		}else{
			//type3
			TweenLite.to($("#ball"), 0, {css:{"top":"30%", "left":"34%" , scale:0.6, rotation:-250},delay:0, ease: Linear.easeNone});
			TweenLite.to($("#ball"), 0.4, {css:{"top":"55%", "left":"35%" , scale:0.85, rotation:-200},delay:0.1, ease: Linear.easeNone});
			TweenLite.to($("#ball"), 0.4, {css:{"top":"68%", "left":"45%" , scale:1, rotation:-10},delay:0.5, ease: Linear.easeNone});
			TweenLite.to($("#ball"), 0.4, {css:{"top":"100%", "left":"65%"},delay:0.9, ease: Linear.easeNone, onComplete:throwBallEnd});
		}
		TweenLite.to($("#zone span"), 0.3, {css:{"scale":0.7},delay:0.7, ease: Linear.easeNone});
		$("#ball").show();
		$("#jjan span").attr("class", "throw");
		swingResult = "strike";
		ballReamin--;
		$("#ballRemain em").text(ballReamin);
	}
	
	function throwBallEnd(){
		throwEnd = true;
		if(!swingStart){
			clickEnable = false;
			$("#ball, #zone").hide();
			if(swingResult == "hit"){
				console.log(">hit");
				$("#jjan span, #sun span").attr("class" ,"hit");
			}
			if(swingResult == "strike"){
				console.log(">strike");
				strike++;
				if(strike>= 3)strike = 3;
				$("#strike span").attr("class", "s"+strike);
				$("#txt span").attr("class","strike");
				$("#jjan span, #sun span").attr("class" ,"strike");
			}
			
			$("#txt").show();
			
			setTimeout( function(){
				$("#jjan span").attr("class" ,"");
				$("#sun span").attr("class", "");
			} , 1500 );
			
			TweenLite.from($("#txt"), 0.4, {css:{scale:1.4, alpha:0},delay:0, ease: Back.easeOut});
			setTimeout( function(){

				if(ballReamin > 0){
					throwBall();
				}else{
					$("#txt").hide();
					if(!gameEnd)result(score);
					gameEnd = true;
				}
				
				// if(strike < 3){
				// 	throwBall();
				// }else{
				// 	gameOver();
				// }
				
			} , 2000 );
		}
		
	}
	
	
	$("#game").on('mousedown touchstart', function(e) {
		if(clickEnable == true)swing();
	});
	
	function swing(){
		swingStart = true;
		clickEnable = false;
		
		if(collision($("#ball .hit"), $("#zone em"))){
			swingResult = "hit";
			$("#ball, #zone").hide();
			$("#ball_fly").show();
			
			var rnd = Math.round(Math.random()*2);
			if(rnd == 0){
				//hit type1
				score = score+10;
				TweenLite.to($("#ball_fly"), 0, {css:{"top":"68%", "left":"45%" , scale:1, rotation:0},delay:0, ease: Linear.easeNone});
				TweenLite.to($("#ball_fly"), 0.8, {css:{"top":"20%", "left":"0%" , scale:0.4, rotation:400},delay:0, ease: Linear.easeNone});
				$("#txt span").attr("class","hit");
			}else if(rnd == 1){
				//hit type2
				score = score+10;
				TweenLite.to($("#ball_fly"), 0, {css:{"top":"68%", "left":"45%" , scale:1, rotation:0},delay:0, ease: Linear.easeNone});
				TweenLite.to($("#ball_fly"), 0.8, {css:{"top":"20%", "left":"90%" , scale:0.4, rotation:-400},delay:0, ease: Linear.easeNone});
				$("#txt span").attr("class","hit");
			}else if(rnd == 2){
				//homerun type1
				score = score+30;
				TweenLite.to($("#ball_fly"), 0, {css:{"top":"68%", "left":"45%" , scale:1, rotation:0},delay:0, ease: Linear.easeNone});
				TweenLite.to($("#ball_fly"), 0.8, {css:{"top":"-20%", "left":"10%" , scale:0.4, rotation:400},delay:0, ease: Linear.easeNone});
				$("#txt span").attr("class","homerun");
			}else{
				//homerun type2
				score = score+30;
				TweenLite.to($("#ball_fly"), 0, {css:{"top":"68%", "left":"45%" , scale:1, rotation:0},delay:0, ease: Linear.easeNone});
				TweenLite.to($("#ball_fly"), 0.8, {css:{"top":"-20%", "left":"80%" , scale:0.4, rotation:400},delay:0, ease: Linear.easeNone});
				$("#txt span").attr("class","homerun");
			}
		}else{
			swingResult = "strike";
			$("#txt span").attr("class","strike");
		}
		
		$("#sun span").attr("class" ,"hit1");
		setTimeout( function(){
			$("#sun span").attr("class" ,"hit2");
		} , 100 );
				
		setTimeout( function(){
			$("#score em").text(score);
			swingStart = false;
			if(throwEnd)throwBallEnd();
		} , 1000 );
		
	}
	
	
	
	
	
	//충돌
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
	
	
	String.prototype.toMMSS = function() {
		var mod;
		var sec_num = parseInt(this, 10);
		var day = Math.floor(sec_num / 86400);
		mod = sec_num % 86400;

		var hours = Math.floor(mod / 3600);
		mod = mod % 3600;

		var minutes = Math.floor(mod / 60);

		var seconds = mod % 60;

		if (minutes < 10)
			minutes = "0" + minutes;
		if (seconds < 10)
			seconds = "0" + seconds;

		var restTimeStr = "";
		if (day > 0)
			restTimeStr = day + "�� ";

		restTimeStr += minutes + ":" + seconds;

		return restTimeStr;
	}
	
	
	
	
})