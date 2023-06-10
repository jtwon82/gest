$(document).ready(function() {
	
	//게임 스타트
	$("#intro .start").click(function(){
		//alert("이미 참여하였습니다. \n내일 다시 도전하세요!");
		//gameStart();
		E.RegisterChk({ 'empty':'empty'
			, loginType:'game8'
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
				, thumb:'thumb_game8.jpg'
				, user_type:'game8', user_type2:score
				, loseAfter: function(req){
					console.log( 'lose' );
				}
				, winAfter: function(req){

					E.ChargeChance({'empty':'empty'
						, chance_type:'add'
						, chance_type2:'game8'
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
	var count = 0;
	var thisRound = 1;
	var rnd;
	var clickEn = false;
	var countDown = 3;
	var timeInterval;
	var thisTime;
	var life = 3;
	var gameEnd = false;

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

	function rnd() {
		$("#computer .hand").removeClass("rock scissor paper");
		
		array1 = ["rock", "scissor", "paper"];
		var rnd = Math.round(Math.random()*2);
		$("#computer .hand").addClass(array1[rnd]);
	}

	function gamePlay() {
		$("#computer .hand").removeClass("rock scissor paper noselect shake");
		$("#user .hand").removeClass("rock scissor paper noselect shake");
		$("#user em").removeClass("select");
		$("#user .hand").attr('id', '');	 
		$("#computer .hand").attr('id', '');	
		$("#score em").text(0);
		$("#sec").hide();
		$(".notice").hide();
		$(".turn").hide();
		$("#wrap #game .nextRound").hide();
		
		life = 3;
		thisRound = 1;
		count ;
		gameEnd = false;
		clickEn = false;
		
		$(".nextRound").text("ROUND "+thisRound);			
		$(".nextRound").slideDown(250);
		setTimeout( function(){
			$(".nextRound").slideUp(250);
			setTimeout(function(){
				$("#game .hand").css("opacity",1);
				timeStart();
			}, 500);
		} , 800 );

		
	}
	

	function nextGame() {
		if(life > 0){
			setTimeout(function(){
				$("#computer .hand").removeClass("rock scissor paper noselect shake");
				$("#user .hand").removeClass("rock scissor paper noselect shake");
				$("#user em").removeClass("select");
				$("#user .hand").attr('id', '');	
				$("#computer .hand").attr('id', '');	
				$("#sec").show();
				$(".notice").show();
			
				count = 0;
				clickEn = true;
				
				timeStart();
			}, 500);
		}
	}

	function nextRound(){
		console.log("nextRound");
		
		life --;
		if(life == 2){
			$("#life .life1").addClass("empty");
		};
		if(life == 1){
			$("#life .life2").addClass("empty");
		};
		if(life == 0){
			$("#life .life3").addClass("empty");
		};


		setTimeout( function(){
			$("#txt").hide();
			$("#txt span").attr("class","");
			$("#game #character").attr("class","");
			if(thisRound < 3){
				thisRound++;
				$("#wrap #game .nextRound").show();
				$(".nextRound").text("ROUND "+thisRound);			
				$(".nextRound").slideDown(250);
				setTimeout( function(){
					$(".nextRound").slideUp(250);
					setTimeout( function(){
						nextGame();
					} , 500 );
				} , 1000 );
			}else{
				endGame();
			}
			
		} , 1500 );
	}

	function nextTurn() {
		clickEn = true;
		timeStart();
		$(".hand").addClass("shake");
		$("#game #sec").show();
		$(".notice").show();
	}

	function timeStart(){
		countDown = 3;
		thisTime = countDown;
		clickEn = true;
		$("#sec").show();
		$(".notice").show();
		$("#sec").text(thisTime);
		clearInterval(timeInterval);
		timeInterval = setInterval(function () {
			thisTime--;
			$("#sec").text(thisTime);
			if(thisTime == 0){
				timeStop();
				rnd();
				userSelect();
			}
		}, 1000);
		 
	}
	
	function timeStop(){
		clickEn = false;
		thisTime = 0;
		$("#sec").text(thisTime);
		clearInterval(timeInterval);
	}

	$("#user .type").click(function(){
		if(clickEn == true){
			$(this).addClass("select");
			$(this).siblings().removeClass("select");
			
			$("#game #sec").hide();
			$(".notice").hide();
			clearInterval(timeInterval);
			timeStop();
			rnd();
			userSelect();
		}
	});
	
	function userSelect(){
		$("#user .hand").removeClass("rock scissor paper");
		$(".hand").removeClass("shake");
		$(".turn").hide();
		console.log(count);
		// 주먹 클릭
		if($(".type.rock").hasClass("select")){
			$("#user .hand").addClass("rock");
			if($("#computer .hand").hasClass("scissor")){
				count++;
				$("#user .hand").attr('id', 'win');
				$("#computer .hand").attr('id', '');
				$("#userTurn").show();
				setTimeout(function(){
					nextTurn();
				}, 1000);
			}else if($("#computer .hand").hasClass("paper")){
				count++;
				$("#computer .hand").attr('id', 'win');
				$("#user .hand").attr('id', '');
				$("#comTurn").show();
				setTimeout(function(){
					nextTurn();
				}, 1000);
			}else if($("#computer .hand").hasClass("rock")){
				// 비겼을때
				if(count < 1){
					setTimeout(function(){
						nextTurn();
					}, 1000);
				// 결과	
				}else if(count >= 1){
					$("#txt").show();
					$("#game #sec").hide();
					$(".notice").hide();
					$(".turn").hide();
					// lose
					if($("#computer .hand").attr('id') == 'win'){
						setTimeout(function(){
							$("#txt span").attr("class","lose");
							$("#game #character").attr("class","lose");
							
						}, 300);
					// win	
					}else if($("#user .hand").attr('id') == 'win'){
						setTimeout(function(){
							$("#txt span").attr("class","win");
							$("#game #character").attr("class","win");
						}, 300);
						score = score + 50;
						$("#score em").text(score);
					}
					nextRound();
				}
				
			}
		// 가위 클릭	
		}else if($(".type.scissor").hasClass("select")){
			$("#user .hand").addClass("scissor");
			if($("#computer .hand").hasClass("paper")){
				count++;
				$("#user .hand").attr('id', 'win');
				$("#computer .hand").attr('id', '');
				$("#userTurn").show();
				setTimeout(function(){
					nextTurn();
				}, 1000);
			}else if($("#computer .hand").hasClass("rock")){
				count++;
				$("#computer .hand").attr('id', 'win');
				$("#user .hand").attr('id', '');	
				$("#comTurn").show();
				setTimeout(function(){
					nextTurn();
				}, 1000);
			}else if($("#computer .hand").hasClass("scissor")){
				// 비겼을때
				if(count < 1){
					setTimeout(function(){
						nextTurn();
					}, 1000);
				// 결과	
				}else if(count >= 1){
					$("#txt").show();
					$("#game #sec").hide();
					$(".notice").hide();
					$(".turn").hide();
					// lose
					if($("#computer .hand").attr('id') == 'win'){
						setTimeout(function(){
							$("#txt span").attr("class","lose");
							$("#game #character").attr("class","lose");
						}, 300);
					// win	
					}else if($("#user .hand").attr('id') == 'win'){
						setTimeout(function(){
							$("#txt span").attr("class","win");
							$("#game #character").attr("class","win");
						}, 300);
						score = score + 50;
						$("#score em").text(score);
					}
					nextRound();
				}
			}
		// 보자기 클릭	
		}else if($(".type.paper").hasClass("select")){
			$("#user .hand").addClass("paper");
			if($("#computer .hand").hasClass("rock")){
				count++;
				$("#user .hand").attr('id', 'win');
				$("#computer .hand").attr('id', '');
				$("#userTurn").show();
				setTimeout(function(){
					nextTurn();
				}, 1000);
			}else if($("#computer .hand").hasClass("scissor")){
				count++;
				$("#computer .hand").attr('id', 'win');
				$("#user .hand").attr('id', '');	
				$("#comTurn").show();
				setTimeout(function(){
					nextTurn();
				}, 1000);
			}else if($("#computer .hand").hasClass("paper")){
				// 비겼을때
				if(count < 1){
					setTimeout(function(){
						nextTurn();
					}, 1000);
				// 결과	
				}else if(count >= 1){
					$("#txt").show();
					$("#game #sec").hide();
					$(".notice").hide();
					$(".turn").hide();
					// lose
					if($("#computer .hand").attr('id') == 'win'){
						setTimeout(function(){
							$("#txt span").attr("class","lose");
							$("#game #character").attr("class","lose");
						}, 300);
					// win	
					}else if($("#user .hand").attr('id') == 'win'){
						setTimeout(function(){
							$("#txt span").attr("class","win");
							$("#game #character").attr("class","win");
						}, 300);
						score = score + 50;
						$("#score em").text(score);
					}
					nextRound();
				}
			}
		}else{
			// 아무것도 선택하지 않았을 때
			$(".hand").addClass("noselect");
			$("#game #sec").hide();
			$("#txt").show();
			$(".notice").hide();
			setTimeout(function(){
				$("#txt span").attr("class","noselect");
			}, 300);
			nextRound();
		}
		

	}

	function endGame(){
		if(!gameEnd){
			console.log("endGame");
			$("#computer .hand").addClass("noselect");
			$("#user .hand").addClass("noselect");
			$("#user .type").removeClass("select");
			$("#txt span").attr("class","");
			$("#game #sec").fadeOut();
			$(".notice").hide();
			gameEnd = true;
			timeStop();
			setTimeout( function(){
				result(score);
			} , 500 );
		}
	}


})