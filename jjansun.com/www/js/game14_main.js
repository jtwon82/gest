$(document).ready(function() {
	
	//게임 스타트
	$("#intro .start").click(function(){
		//alert("이미 참여하였습니다. \n내일 다시 도전하세요!");
		//gameStart();
		E.RegisterChk({ 'empty':'empty'
			, loginType:'game14'
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
				, thumb:'thumb_game14.jpg'
				, user_type:'game14', user_type2:score
				, loseAfter: function(req){
					console.log( 'lose' );
				}
				, winAfter: function(req){

					E.ChargeChance({'empty':'empty'
						, chance_type:'add'
						, chance_type2:'game14'
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
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	//---------------------------------------------------------------------------------------------------------
	var score;
	var posNum;
	var pos;
	var goalPos;
	var power;

	var thisSelect = "ballPosition";
	var clickEnable = true;
	var remitTime = 60;
	var thisTime;
	var timeInterval;
	var time1 = new TimelineLite();
	
	gameStart= function (){
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
					$("#intro").hide();
					gamePlay();
				} , 1000);
			} , 1000);
		} , 1000);
	}

	function gamePlay(){
		score = 0;
		posNum = 2;
		pos = "right";
		$("#score em").text(score);
		movingInterval = setInterval(moving, 500);
		timeStart();
		ballPosition();
	}

	function moving(){
		if(posNum <= 4 && pos == "left"){
			posNum--;
		}else if(posNum >= 0 && pos == "right"){
			posNum++;
		}
		// console.log(posNum)
		if(posNum == 0){
			pos = "right";
			TweenLite.to($("#goalkeeper"), 0.2, {css:{"left":"3%"},delay:0, ease: Linear.easeNone});
		}else if(posNum == 1){
			TweenLite.to($("#goalkeeper"), 0.2, {css:{"left":"14%"},delay:0, ease: Linear.easeNone});
		}else if(posNum == 2){
			TweenLite.to($("#goalkeeper"), 0.2, {css:{"left":"33%"},delay:0, ease: Linear.easeNone});
		}else if(posNum == 3){
			TweenLite.to($("#goalkeeper"), 0.2, {css:{"left":"52%"},delay:0, ease: Linear.easeNone});
		}else if(posNum == 4){
			pos = "left";
			TweenLite.to($("#goalkeeper"), 0.2, {css:{"left":"63%"},delay:0, ease: Linear.easeNone})
		}
	}
	
	
	function ballPosition(){
		thisSelect = "ballPosition";
		$("#game #txt span").attr("class","");
		$("#game #txt").hide();
		$("#gauge #position").removeClass("start pause");
		$("#gauge #power").removeClass("start pause");
		$("#gauge #position").addClass("start");
	}

	function ballPower(){
		thisSelect = "ballPower";
		$("#gauge #power").addClass("start");
	}

	$("#ballArea").bind("click",function(){
		if(clickEnable){
			if(thisSelect == "ballPosition"){
				$("#game #txt span").attr("class","");
				$("#game #txt").hide();
				$("#gauge #position").removeClass("start pause");
				$("#gauge #power").removeClass("start pause");
				$("#gauge #position").addClass("start");
				$("#gauge #position").addClass("pause");

				var pctA = $("#gauge #position").position().left / $("#gauge #position").parent().width() * 100;
				if(pctA <= 20){
					goalPos = 4;
				}else if(pctA >= 21 && pctA < 40){
					goalPos = 23;
				}else if(pctA >= 40 && pctA < 60){
					goalPos = 39;
				}else if(pctA >= 60 && pctA < 80){
					goalPos = 57;
				}else if(pctA >= 80 && pctA <= 100){
					goalPos = 77;
				}
				ballPower();
			}else{
				clickEnable = false;

				$("#gauge #power").addClass("pause");
				var pctB = Math.round($("#gauge #power").width() / $('#gauge #power').parent().width() * 100).toFixed(0);
				if(pctB < 35) power = 41;
				if(pctB >= 35 && pctB < 76) power = 7;
				if(pctB >= 76 && pctB <= 100) power = -35;
				
				var ballScale;
				if(power == 41) ballScale = 0.6;
				if(power == 7) ballScale = 0.5;
				if(power == -35) ballScale = 0.4;

				TweenLite.killTweensOf($("#ball"));
				TweenLite.to($("#ball"), 0, {css:{scale:1, "top":"76%", "left":"39%"},delay:0, ease: Linear.easeNone});
				TweenLite.to($("#ball"), 0.6, {css:{scale:ballScale, rotation:300, "top":power+"%", "left":goalPos+"%"},delay:0, ease: Linear.easeNone, onComplete:throwEnd});
			}
		}
	});
	

	function throwEnd(){
		if(collision($("#ball"), $("#goalkeeper em"))){
			TweenLite.killTweensOf($("#ball"));

			if(goalPos == 6 || goalPos == 23 || goalPos == 39){
				TweenLite.to($("#ball"), 0.7, {css:{scale:0.8, rotation:200, "top":"-10%", "left":"-33%"},delay:0, ease: Linear.easeNone});
			}else{
				TweenLite.to($("#ball"), 0.7, {css:{scale:0.8, rotation:200, "top":"-7%", "left":"120%"},delay:0, ease: Linear.easeNone});
			}
			//console.log("hit");
			$("#game #txt span").attr("class","nogoal");
		}else if(power == 7){
			score = score + 10;
			$("#score em").text(score);
			$("#game #txt span").attr("class","goal");
			$("#ballArea").css("z-index", "99");
			//console.log("goal");
		}else{
			$("#game #txt span").attr("class","nogoal");
		}
		$("#game #txt").show();
		
		setTimeout(function(){
			TweenLite.to($("#ball"), 0, {css:{scale:1, rotation:0, "top":"76%", "left":"39%"},delay:0, ease: Linear.easeNone});
			clickEnable = true;
			$("#ballArea").css("z-index", "100");
			ballPosition();
		},800);
	}

	function timeStart(){
		TweenLite.killTweensOf(time1);
		time1 = new TimelineLite();
		$("#bar span").css("width", "100%");
		time1.to($("#bar span"), remitTime, {css : {"width":"0%"},delay:0,ease : Linear.easeNone});
		thisTime = remitTime;
		$("#timer em").text(thisTime);
		clearInterval(timeInterval);
		timeInterval = setInterval(function () {
			thisTime--;
			$("#timer em").text(thisTime);
			if(thisTime <= 0){
				timeout();
			}
		}, 1000);
		
	}
	
	function timeout(){
		clickEnable = false;
		clearInterval(timeInterval);
		TweenLite.killTweensOf(time1);
		endGame();
		console.log("timeout");
	}


	function endGame(){
		$("#gauge #power").removeClass("start pause");
		$("#gauge #position").removeClass("start pause");
		console.log("CLEAR!");
		clearInterval(movingInterval);
		setTimeout( function(){
			result(score);
		} , 1000);
	}



	//충돌판단
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
	
	
	
	
})