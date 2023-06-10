$(document).ready(function() {
	
	//게임 스타트
	$("#intro .start").click(function(){
		//alert("이미 참여하였습니다. \n내일 다시 도전하세요!");
		//gameStart();
		E.RegisterChk({ 'empty':'empty'
			, loginType:'game7'
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
				, thumb:'thumb_game7.jpg'
				, user_type:'game7', user_type2:score
				, loseAfter: function(req){
					console.log( 'lose' );
				}
				, winAfter: function(req){

					E.ChargeChance({'empty':'empty'
						, chance_type:'add'
						, chance_type2:'game7'
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
	var gameEnd = false;
	var makeObjInterval;
	var makeIntervalSpeed  = 6;
	var thisMotionObj = 0;
	var maxTime = 60;
	var time1 = new TimelineLite();
	var timeInterval;
	var thisTime;

	var rndWord = new Array();

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
		score = 0;
		thisTime = maxTime;
		$("#timer em").text(thisTime);
		$("#score em").text(score);
		gameEnd = false;
		var array1 = ["찌개", "여기저기", "찐빵", "이것저것", "붕어빵", "속수무책", "자연재해", "이모저모", "이판사판", "아메리카노", "고구마", "날씨예보", "태풍", "번개", "산기슭" , "크리스마스", "닭다리", "해질녘", "수수꽃다리", "안드로메다", "아이스크림", "아까", "강아지", "고양이", "직장인", "이끼", "스키장", "가지가지", "당근", "소금", "이곳저곳", "짠순이", "바가지", "꽃등심", "주식", "사이다", "커피", "태극기", "겨울", "가을", "여름", "푸르스름", "휴양지", "요리조리", "햄버거", "사필귀정", "인과응보", "유비무환", "권모술수", "방울토마토" ,"어리광", "잠꼬대", "기승전결", "고슴도치", "코스모스", "반딧불이", "미세먼지", "쇠똥구리", "호랑나비", "웰시코기", "허수아비"];
		for (var i in array1) {
			array1.sort(function() {
				return Math.random() - Math.random()
			});
		};

		for(i=0; i<array1.length; i++){
			var rnd = Math.round(Math.random()*4);
			$("#game #area").append('<div class="quiz'+i+' quiz rnd'+rnd+' obj"></div>')
		}
		$("#area .quiz").show();
		
		rndWord = array1;

		$("#area .quiz").each(function(index) {
		  $(this).text(rndWord[index]);
		});

		$("#area .quiz").removeClass("active");
		$("#input input").focus();
		motionStartInterval();
		timeStart();
	}



	function motionStartInterval(){
		clearInterval(makeObjInterval);
		motionStart(thisMotionObj);

		makeObjInterval = setInterval(function () {
			thisMotionObj++;
			motionStart(thisMotionObj);
		}, 2500);
	}


	function motionStart(num){
		$("#area .quiz" +num ).animate({right: "100%"}, 7000, "linear", function() {endGamd(num); });
	}

	function endGamd(num){
		$("#area .quiz" +num ).hide();
		$("#area .quiz" +num ).text("");
	}



	$("#input input").keydown(function(key) {
		if (key.keyCode == 13 && $("#input input").val()) {
			checkWord($(this).val());
		}
	});


	function checkWord(str){
		$("#area .quiz").each(function(index) {
			if(str == $(this).text()){
				correctWord(index);
			}
		});
		setTimeout( function(){
			$("#input input").val("");
		} , 100 );

	}

	function correctWord(num){
		score = score + 5;
		$("#score em").text(score);
		$("#area .quiz"+num).addClass("active");
		TweenLite.to($("#area .quiz"+num), 0.3, {css : {"display":"none", alpha:0},delay:0,ease : Sine.easeIn});
		setTimeout(function(){
			$("#area .quiz" +num ).text("");
		},350);
		
	}

	function timeStart(){
		time1.pause();
		TweenLite.killTweensOf(time1);
		time1 = new TimelineLite();
		$("#timer em").text(thisTime);
		
		clearInterval(timeInterval);
		timeInterval = setInterval(function () {
			thisTime--;
			$("#timer em").text(thisTime);
			if(thisTime <= 0)timeOut();
		}, 1000);
	}

	function timeOut(){
		console.log("timeOut")
		timeStop();
		clearInterval(timeInterval);
		$("#timer em").text(0);
		endGame();
	}

	function timeStop(){
		clearInterval(timeInterval);
		TweenLite.killTweensOf(time1);
		time1.pause();
	}

	function endGame(){
		if(!gameEnd){
			console.log("endGame");
			clearInterval(makeObjInterval);
			gameEnd = true;
			$("#input input").blur();
			setTimeout( function(){
				$("#area .quiz").hide();
				result(score);
			} , 500 );
		}
	}

	
})