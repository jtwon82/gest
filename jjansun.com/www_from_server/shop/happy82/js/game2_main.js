$(document).ready(function() {
	
	//게임 스타트
	$("#intro .start").click(function(){
		//alert("이미 참여하였습니다. \n내일 다시 도전하세요!");
		//gameStart();
		E.RegisterChk({ 'empty':'empty'
			, loginType:'game2'
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
		console.log("점수="+score);
		if(!practice){
			//실제(솔트 지급)
			//$("#popup_score em").text(score);
			//$("#popup_score").fadeIn();

			JS.RESULT({ 'empty':'empty'
				, user_type:'game2', user_type2:score
				, loseAfter: function(req){
					console.log( 'lose' );
				}
				, winAfter: function(req){

					E.ChargeChance({'empty':'empty'
						, chance_type:'add'
						, chance_type2:'game2'
						, user_type: score
						, callback:function(req){
							if(req.chance_info)	$(".my em").html(parseInt(req.chance_info.chance_cnt).toLocaleString());
							if (req.result=='o')
							{
								$("#popup_score em").text(score);
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
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	//---------------------------------------------------------------------------------------------------------
	
	var score = 0;
	$("#score em").text(score);
	
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
					$("#intro").hide();
					time_countdown();
				} , 1000);
			} , 1000);
		} , 1000);
	}
	
	
	function throwBall(type){
		$('#ball').hide();
		$("#throwBall").show();
		TweenLite.to($('#throwBall'), 0, {css : {"top":"75%", "left":"37.5%", "scale":1 ,"rotation":0, "zIndex":10},delay:0, ease: Linear.easeNone});
		if(type == "goal"){
			TweenLite.to($('#throwBall'), 0.7, {css : {"top":"10%", "left":"37.5%", "scale":0.75, "rotation":200},delay:0, ease: Linear.easeNone});
			TweenLite.to($('#throwBall'), 0, {css : {"zIndex":3},delay:0.6});
			TweenLite.to($('#throwBall'), 0.4, {css : {"top":"55%", "scale":0.85, "rotation":10},delay:0.7, ease: Linear.easeNone});
			TweenLite.to($('#throwBall'), 0.4, {css : {"top":"65%", "rotation":80},delay:1.1, ease: Back.easeOut, onComplete:throwBallEnd});
		}else{
			var rnd = Math.round(Math.random()*1);
			if(rnd == 0){
				TweenLite.to($('#throwBall'), 0.7, {css : {"top":"8%", "left":"30%", "scale":0.70, "rotation":-200},delay:0, ease: Linear.easeNone});
				TweenLite.to($('#throwBall'), 0.3, {css : {"top":"12%", "left":"25%"},delay:0.6, ease: Linear.easeNone});
				TweenLite.to($('#throwBall'), 0.4, {css : {"top":"55%", "left":"10%", "scale":0.75, "rotation":-10},delay:0.7, ease: Linear.easeNone});
				TweenLite.to($('#throwBall'), 0.4, {css : {"top":"65%", "rotation":-80},delay:1.1, ease: Back.easeOut, onComplete:throwBallEnd});
			}else{
				TweenLite.to($('#throwBall'), 0.7, {css : {"top":"7%", "left":"42%", "scale":0.70, "rotation":200},delay:0, ease: Linear.easeNone});
				TweenLite.to($('#throwBall'), 0.3, {css : {"top":"12%", "left":"47%"},delay:0.6, ease: Linear.easeNone});
				TweenLite.to($('#throwBall'), 0.4, {css : {"top":"55%", "left":"65%", "scale":0.75, "rotation":10},delay:0.7, ease: Linear.easeNone});
				TweenLite.to($('#throwBall'), 0.4, {css : {"top":"65%", "rotation":80},delay:1.1, ease: Back.easeOut, onComplete:throwBallEnd});
			}
		}
		setTimeout(function(){
			$("#txt").show();
			if(type == "goal"){
				score = score+5;
				if(remitTime<10)score = score+5;
				$("#score em").text(score);
				$("#txt span").attr("class","goal");
			}else{
				$("#txt span").attr("class","try");
			}
			TweenLite.from($("#txt"), 0.3, {css:{scale:1.4, alpha:0},delay:0, ease: Back.easeOut});
		},1100)
	}
	
	function throwBallEnd(){
		$("#throwBall").fadeOut();
		$("#gauge #bar").removeClass("pause");
		$("#gauge #bar").removeClass("start");
		setTimeout(function(){
			if(remitTime > 0){
				clickEnable = true;
				$('#ball').show();
				$("#txt").hide();
				$("#txt span").attr("class","");
			}
		},200)
	}
	
	var clickEnable;
	$('#ball').on('mousedown touchstart', function(e) {
		e.preventDefault();
		if(clickEnable){
			$("#gauge #bar").removeClass("pause");
			$("#gauge #bar").removeClass("start");
			setTimeout(function(){
				$("#gauge #bar").addClass("start");
				$("#user").removeClass("throw");
			},10)
		}
	}).on('mouseup touchend', function(e) {
		e.preventDefault();
		if(clickEnable){
			clickEnable = false;
			$("#gauge #bar").addClass("pause");
			var pct = Math.round($("#gauge #bar").width() / $('#gauge #bar').parent().width() * 100).toFixed(0);
			//console.log(pct)
			var power;
			if(pct >= 60 && pct < 66){
				throwBall("goal");
				// var rnd = Math.round(Math.random()*3);
				// if(rnd != 0){
				// 	throwBall("goal");
				// }else{
				// 	throwBall("noGoal");
				// }
				
			}else{
				throwBall("noGoal");
			}
		}
	});
	
	function gameOver(){
		clickEnable = false;
		clearInterval(time_interval);
		$("#bonus").hide();
		$("#gameOver").fadeIn();
		setTimeout(function(){
			result(score);
		},1000)
	}
	
	var remitTime = 40;
	var time_interval;
	var remitTime10 = false;
	function time_countdown(){
		clearInterval(time_interval);
		time_interval = setInterval(function () {
			remitTime = Number(remitTime-1);
			if(remitTime < 10){
				if(!remitTime10){
					remitTime10 = true;
					$("#bonus").show();
					// setTimeout(function(){
					// 	$("#bonus").hide();
					// },1000)
				}
			}
			if(remitTime < 0){
				console.log("timeOver!")
				remitTime = 0;
				clearInterval(time_interval);
				gameOver();
			}
			$("#timer em").text(String(remitTime).toMMSS());
		}, 1000);
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