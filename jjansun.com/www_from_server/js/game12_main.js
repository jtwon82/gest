$(document).ready(function() {
	
	//게임 스타트
	$("#intro .start").click(function(){
		//alert("이미 참여하였습니다. \n내일 다시 도전하세요!");
		//gameStart();
		E.RegisterChk({ 'empty':'empty'
			, loginType:'game12'
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
				, thumb:'thumb_game12.jpg'
				, user_type:'game12', user_type2:score
				, loseAfter: function(req){
					console.log( 'lose' );
				}
				, winAfter: function(req){

					E.ChargeChance({'empty':'empty'
						, chance_type:'add'
						, chance_type2:'game12'
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
  var clickEn = false;
	var score = 0;
	var currentNum = 0;
	var remitTime;
  var check_interval;
	var time_interval;
	var time1 = new TimelineLite();
	var imgUrl;
	var numArray = new Array;
	const date = new Date();
	const today = date.getDay(); 
	var correctNum = [1, 3, 2, 3, 1, 2, 4, 2, 3, 1, 3, 2, 3, 1, 4, 2, 4, 3, 1, 1, 4, 1, 3, 1, 2, 2, 4, 3, 1, 4, 2, 3, 3, 1, 2, 4, 4, 1, 2, 4, 1, 2, 1, 4, 3, 3, 2, 4, 2, 3, 1, 4, 3, 3, 1, 2, 2, 4, 3, 1, 2, 4, 3, 1, 2, 1, 4, 1, 3, 3];	

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
					$("#intro").hide()
					gamePlay();
				} , 1000);
			} , 1000);
		} , 1000);
	}

	//요일구분 / 일:0, 월:1, 화:2, 수:3, 목:4, 금:5, 토:6
	if(today == 0){
		numArray = range(1, 10);
	}else if(today == 1){
		numArray = range(11, 20);
	}else if(today == 2){
		numArray = range(21, 30);
	}else if(today == 3){
		numArray = range(31, 40);
	}else if(today == 4){
		numArray = range(41, 50);
	}else if(today == 5){
		numArray = range(51, 60);
	}else if(today == 6){
		numArray = range(61, 70);
	}

	function gamePlay(){
		makeQuestion();
	}

  $("#questionWrap dd").click(function(){
    if(clickEn == true){
      clickEn = false;
      $("#questionWrap dd").removeClass("select");
      $(this).addClass("select");

			clearInterval(time_interval);
			time1.pause();
			TweenLite.killTweensOf(time1);

      //정답확인
      if($("#wrap #questionWrap dd.select").hasClass("a"+correctNum[imgUrl-1])){
        $("#wrap #questionWrap dd.select").children("img").css("border","4px solid #060bf0");
        score = score+10;
        $("#score em").text(score);
      }else{
        $("#wrap #questionWrap dd.select").children("img").css("border","4px solid red");
      }
      
      
      if(currentNum == 10){
        endGame();
      }else{ 
        setTimeout(function(){
          makeQuestion();
          time_countdown();
        }, 1000);
      }

    }
  });

	function range(start, end) {
		numArray = [];
		for (let i = start; i <= end; ++i) {
			numArray.push(i);
		}
		for (var i in numArray) {
			numArray.sort(function() {
				return Math.random() - Math.random()
			});
		};
		//console.log(numArray);
		return numArray;
	} 

	function makeQuestion(){
		clickEn = true;
		$("#questionWrap dd").removeClass("select");
		$("#questionWrap dd img").css("border","");
		time_countdown();
		imgUrl = numArray[currentNum];
		// console.log("문제번호",imgUrl,"순서",currentNum);
		// console.log("정답", correctNum[imgUrl-1]);
		$("#questionWrap dt img").attr("src","https://dbins2.speedgabia.com/jjansun/images/game12/"+"1122"+imgUrl+"1122"+'.png');
		for(var i = 1; i<5; i++){
			$("#questionWrap dd.a"+ i +" img").attr("src","https://dbins2.speedgabia.com/jjansun/images/game12/"+imgUrl+'-'+ i +'.png');
		}
		$("#quizNum").text(currentNum+1+"/10");
		currentNum++;
	}



	function time_countdown(){
		remitTime = 5;
		$("#timer em").text(remitTime);
		clearInterval(time_interval);
		time1.pause();
		TweenLite.killTweensOf(time1);
		time1 = new TimelineLite();
		$("#bar span").css("width", "100%");
		time1.to($("#bar span"), remitTime, {css : {"width":"0%"},delay:0,ease : Linear.easeNone});
		time_interval = setInterval(function () {
			remitTime = Number(remitTime-1);
			if(remitTime < 0){
				remitTime = 0;
				clearInterval(time_interval);
				time1.pause();
				TweenLite.killTweensOf(time1);
				if(currentNum < 10){
					makeQuestion();
				}else{
					endGame();
				}
			}
			$("#timer em").text(remitTime);
		}, 1000);
	}

	function endGame(){
		console.log("CLEAR!");
		clearInterval(time_interval);
		clearInterval(check_interval);
		time1.pause();
		TweenLite.killTweensOf(time1);
		setTimeout( function(){
			result(score);
		} , 1000 );
	}

	
	
	
	
})