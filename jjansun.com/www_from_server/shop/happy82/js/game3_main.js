$(document).ready(function() {
	
	//게임 스타트
	$("#intro .start").click(function(){
		//alert("이미 참여하였습니다. \n내일 다시 도전하세요!");
		//gameStart();
		E.RegisterChk({ 'empty':'empty'
			, loginType:'game3'
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
				, user_type:'game3', user_type2:score
				, loseAfter: function(req){
					console.log( 'lose' );
				}
				, winAfter: function(req){

					E.ChargeChance({'empty':'empty'
						, chance_type:'add'
						, chance_type2:'game3'
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
	
	var mobile = false;
	var mobileKeyWords = new Array('iPhone', 'iPod', 'iPad', 'BlackBerry', 'Android', 'Windows CE', 'LG', 'MOT', 'SAMSUNG', 'SonyEricsson');
	for (var word in mobileKeyWords){
		if (navigator.userAgent.match(mobileKeyWords[word]) != null){
			mobile=true;
			break;
		}
	}

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
					cardReady();
				} , 1000);
			} , 1000);
		} , 1000);
	}

	var previewTime = 3000; //3초
	var setCardNumber = new Array;
	var clickEn = false;
	var openCardCount = 0;
	var user_selectCard = new Array;
	var correctNum = 0;
	var gameComplete = false;
	var levelComNum = ["",16];
	var thisLevel = 1;
	
	
	function gameIni(){
		console.log("gameIni");
		$("#countdown").text("20");
	}
	gameIni();

	function cardReady(){
		console.log("cardReady");
		$(".cardContainer").hide()
		$(".cardContainer.l"+thisLevel).show()
		$(".cardContainer.l"+thisLevel+" .card").css("opacity",1);
		$(".cardContainer.l"+thisLevel+" .card").css("opacity",1);
		$(".cardContainer .card").show();
		TweenLite.to($(".cardContainer.l"+thisLevel+" .card").find("span.b"), 0, {css:{scaleX:0, zIndex:1}, ease: Linear.easeNone});
		TweenLite.to($(".cardContainer.l"+thisLevel+" .card").find("span.a"), 0, {css:{scaleX:1}, ease: Linear.easeNone, delay:0});
		
		if(thisLevel == 1){
			var array1 = [0, 1, 2, 3, 4, 5, 6, 7, 0, 1, 2, 3, 4, 5, 6, 7];
			for (var i in array1) {
				array1.sort(function() {
					return Math.random() - Math.random()
				});
			};
			array1.push(0);
		}

		setCardNumber = new Array;
		for(i=0; i<levelComNum[thisLevel]; i++){
			if(thisLevel ==1){
				setCardNumber.push(array1[i]);
				$(".cardContainer.l1 .card").eq(i).find("span.b").html('<img src="images/game3/game_card_front'+array1[i]+'.jpg">');
			}
			
			TweenLite.to($(".cardContainer.l"+thisLevel+" .card").eq(i).find("span.b"), 0, {css:{scaleX:0, zIndex:1}, ease: Linear.easeNone});
			TweenLite.to($(".cardContainer.l"+thisLevel+" .card").eq(i).find("span.a"), 0, {css:{scaleX:1}, ease: Linear.easeNone});
		}
		
		
		console.log(setCardNumber)
		
		setTimeout( function(){
			preview();
		} , 1000);
	}
	
	function preview(){
		console.log("preview");
		for(i=0; i<levelComNum[thisLevel]; i++){
			TweenLite.to($(".cardContainer.l"+thisLevel+" .card").eq(i).find("span.b"), 0, {css:{scaleX:0, zIndex:2}, ease: Linear.easeNone});
			TweenLite.to($(".cardContainer.l"+thisLevel+" .card").eq(i).find("span.a"), 0.2, {css:{scaleX:0}, ease: Linear.easeNone});
			TweenLite.to($(".cardContainer.l"+thisLevel+" .card").eq(i).find("span.b"), 0.2, {css:{scaleX:1}, ease: Linear.easeNone, delay:0.2});
		}
		
		setTimeout( function(){
			gamePlay();
		} , previewTime);
	}
	
	function gamePlay(){
		console.log("gamePlay");
		for(i=0; i<levelComNum[thisLevel]; i++){
			TweenLite.to($(".cardContainer.l"+thisLevel+" .card").eq(i).find("span.b"), 0.2, {css:{scaleX:0, zIndex:1}, ease: Linear.easeNone});
			TweenLite.to($(".cardContainer.l"+thisLevel+" .card").eq(i).find("span.a"), 0.2, {css:{scaleX:1}, ease: Linear.easeNone, delay:0.2});
		}
		correctNum = 0;
		openCardCount = 0;
		user_selectCard = new Array;
		gameComplete = false;
		clickEn = true;
		
		timeStart();
	}
	
	function timeStart(){
		current_time = Date.parse(new Date());
		deadline = new Date(current_time + time_in_minutes * 60 * 1000);
		run_clock('clockdiv', deadline);
	}
	
	function timeStop(){
		$("#pause").click();
	}
	
	
	if(mobile){
		$(".cardContainer .card").bind("touchstart",function(e){
			e.preventDefault();
			if(clickEn==true && gameComplete == false){
				if(openCardCount<2){
					console.log("touch");
					openCard($(this).index());
				}
			}
		});
	}else{
		$(".cardContainer .card").click(function() {
			if(clickEn==true && gameComplete == false){
				if(openCardCount<2){
					console.log("click");
					openCard($(this).index());
				}
			}
		});
	}
	
	
	function openCard(num){
		if($(".cardContainer.l"+thisLevel+" .card"+num+" span.b").css("z-index") == 1){
			clickEn = false;
			openCardCount++
			user_selectCard.push(num);
			TweenLite.to($(".cardContainer.l"+thisLevel+" .card"+num+" span.b"), 0, {css:{scaleX:0, zIndex:2}, ease: Linear.easeNone});
			TweenLite.to($(".cardContainer.l"+thisLevel+" .card"+num+" span.a"), 0.1, {css:{scaleX:0}, ease: Linear.easeNone});
			TweenLite.to($(".cardContainer.l"+thisLevel+" .card"+num+" span.b"), 0.1, {css:{scaleX:1}, ease: Linear.easeNone, delay:0.05, onComplete:checkCard});
		}
	}
	function checkCard(){
		if(openCardCount == 2){
			$(".cardContainer .check").hide();
			if(setCardNumber[user_selectCard[0]] == setCardNumber[user_selectCard[1]]){
				setTimeout( function(){
					$(".cardContainer.l"+thisLevel+" .card"+user_selectCard[0]).css("opacity",0);
					$(".cardContainer.l"+thisLevel+" .card"+user_selectCard[1]).css("opacity",0);
					correctCard(user_selectCard[0]);
					correctCard(user_selectCard[1]);
				} , 200 );
			}else{
				setTimeout( function(){
					closeCard(user_selectCard[0]);
					closeCard(user_selectCard[1]);
				} , 200 );
			}
			setTimeout( function(){$(".cardContainer .check").fadeOut(100);} , 600 );
		}else{
			clickEn = true;
		}
	}
	
	function closeCard(num){
		TweenLite.to($(".cardContainer.l"+thisLevel+" .card"+num+" span.b"), 0.1, {css:{scaleX:0, zIndex:1}, ease: Linear.easeNone});
		TweenLite.to($(".cardContainer.l"+thisLevel+" .card"+num+" span.a"), 0.1, {css:{scaleX:1}, ease: Linear.easeNone, delay:0.05, onComplete:closeCardEnd});
	}
	
	function closeCardEnd(){
		openCardCount = 0;
		user_selectCard = new Array;
		clickEn = true;
	}
	
	
	function correctCard(){
		openCardCount = 0;
		user_selectCard = new Array;
		clickEn = true;
		
		correctNum++
		if(correctNum==levelComNum[thisLevel]){
			complete();
		}
		
	}
	
	function complete(){
		if(!gameComplete){
			timeStop();
			gameComplete = true;
			setTimeout( function(){
				result(correctNum*5);
			} , 1000 );
		}
	}
	
	function timeOver(){
		if(!gameComplete){
			console.log("timeOver");
			gameComplete = true;
			setTimeout( function(){
				result(correctNum*5);
			} , 1000 );
		}
	}
	

	
	var time_in_minutes = 0.34; //20초(0.34분)
	var current_time;
	var deadline;

	function time_remaining(endtime) {
		var t = Date.parse(endtime) - Date.parse(new Date());
		var seconds = Math.floor((t / 1000) % 60);
		var minutes = Math.floor((t / 1000 / 60) % 60);
		var hours = Math.floor((t / (1000 * 60 * 60)) % 24);
		var days = Math.floor(t / (1000 * 60 * 60 * 24));
		minutes = minutes.toString();
		minutesDigit = minutes.length;
		if (minutesDigit == 1)minutes = "0" + minutes
		seconds = seconds.toString();
		
		secondsDigit = seconds.length;
		if (secondsDigit == 1)seconds = "0" + seconds
		return {
			'total' : t,
			'days' : days,
			'hours' : hours,
			'minutes' : minutes,
			'seconds' : seconds
		};
	}

	var timeinterval;
	function run_clock(id, endtime) {
		var clock = document.getElementById(id);
		function update_clock() {
			var t = time_remaining(endtime);
			//$("#countdown").html(t.minutes+":"+t.seconds);
			$("#countdown").html(t.seconds);
			if (t.total <= 0) {
				//alert("end");
				clearInterval(timeinterval);
				timeOver();
			}
		}

		update_clock();
		timeinterval = setInterval(update_clock, 1000);
	}

	

	var paused = false;
	var time_left;

	function pause_clock() {
		if (!paused) {
			paused = true;
			clearInterval(timeinterval);
			time_left = time_remaining(deadline).total;
		}
	}

	function resume_clock() {
		if (paused) {
			paused = false;
			deadline = new Date(Date.parse(new Date()) + time_left);
			run_clock('clockdiv', deadline);
		}
	}

	document.getElementById('pause').onclick = pause_clock;
	document.getElementById('resume').onclick = resume_clock;
	
})