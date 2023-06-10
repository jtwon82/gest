$(document).ready(function() {
	
	//게임 스타트
	$("#intro .start").click(function(){
		//alert("이미 참여하였습니다. \n내일 다시 도전하세요!");
		//gameStart();
		E.RegisterChk({ 'empty':'empty'
			, loginType:'game9'
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
				, thumb:'thumb_game9.jpg'
				, user_type:'game9', user_type2:score
				, loseAfter: function(req){
					console.log( 'lose' );
				}
				, winAfter: function(req){

					E.ChargeChance({'empty':'empty'
						, chance_type:'add'
						, chance_type2:'game9'
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
	
	
	
	var clickEn = false;
	var thisLevel = 1;
	var score = 0;
	var life = 3;
	var selectBox;
	var count;
	var retry = false;
	var array1 = [];
	var array2 = [];
	var array3 = [];
	var array4 = [];
	var array5 = [];

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
		clickEn = false;
		thisLevel = 1;
		life = 3;
		score = 0;
		$("#game .notice").show();
		$("#txt").hide();
		boxRnd();
	}

	for(i=0; i<16; i++){
		$(".boxContainer").append('<div class="box box'+i+'"><span></span></div>')
	}

	function boxRnd(){
		clickEn = false;
		var boxLength = $('.boxContainer .box').length -1;
		var num = 0;
		$(".boxContainer .box").removeClass("on");
		$("#game .notice").text("블럭이 나오는 순서를 기억하세요!");
		$(".boxContainer .box span").text("");
		$("#txt").hide();
		//레벨1
		if(thisLevel == 1){
			count = 0;
			num = 0;
			selectBox = 5;
			if(!retry){
				array1 = [];
				for(i=0; i<selectBox; i++){
					var rnd = Math.round(Math.random()* boxLength);
					if(array1.indexOf(rnd) === -1){
						array1.push(rnd);
					}else{
						i--
					}
				}
			}
			checkInterval = setInterval(function(){
				$(".boxContainer .box").eq(array1[num]).addClass("on");
				if(num < selectBox){
					$(".boxContainer .box").eq(array1[num]).children("span").text(num+1);
					num ++;
					
				}else{
					$(".boxContainer .box").removeClass("on");
					clearInterval(checkInterval);
					clickEn = true;
					$("#game .notice").text("블럭이 나온 순서대로 클릭하세요!");
				}
			},1000);
			
		 }
		 //레벨2
		 if(thisLevel == 2){
			$("#game .state .txt").text("레벨 2");
			$("#game .notice").text("블럭이 나오는 순서를 기억하세요!");
			count = 0;
			num = 0;
			selectBox = 6;
			if(!retry){
				array2 = [];
				for(i=0; i<selectBox; i++){
					var rnd = Math.round(Math.random()* boxLength);
					if(array2.indexOf(rnd) === -1){
						array2.push(rnd);
					}else{
						i--
					}
				}
			}
			checkInterval = setInterval(function(){
				$(".boxContainer .box").eq(array2[num]).addClass("on");
				if(num < selectBox){
					$(".boxContainer .box").eq(array2[num]).children("span").text(num+1);
					num ++;
				}else{
					$(".boxContainer .box").removeClass("on");
					clearInterval(checkInterval);
					clickEn = true;
					$("#game .notice").text("블럭이 나온 순서대로 클릭하세요!");
				}
			},1000);
			
		 }
		 //레벨3
		 if(thisLevel == 3){
			$("#game .state .txt").text("레벨 3");
			$("#game .notice").text("블럭이 나오는 순서를 기억하세요!");
			count = 0;
			num = 0;
			selectBox = 7;
			if(!retry){
				array3 = [];
				for(i=0; i<selectBox; i++){
					var rnd = Math.round(Math.random()* boxLength);
					if(array3.indexOf(rnd) === -1){
						array3.push(rnd);
					}else{
						i--
					}
				}
			}
			checkInterval = setInterval(function(){
				$(".boxContainer .box").eq(array3[num]).addClass("on");
				if(num < selectBox){
					$(".boxContainer .box").eq(array3[num]).children("span").text(num+1);
					num ++;
				}else{
					$(".boxContainer .box").removeClass("on");
					clearInterval(checkInterval);
					clickEn = true;
					$("#game .notice").text("블럭이 나온 순서대로 클릭하세요!");
				}
			},1000);
			
		 }
		 //레벨4
		 if(thisLevel == 4){
			$("#game .state .txt").text("레벨 4");
			$("#game .notice").text("블럭이 나오는 순서를 기억하세요!");
			count = 0;
			num = 0;
			selectBox = 8;
			if(!retry){
				array4 = [];
				for(i=0; i<selectBox; i++){
					var rnd = Math.round(Math.random()* boxLength);
					if(array4.indexOf(rnd) === -1){
						array4.push(rnd);
					}else{
						i--
					}
				}
			}
			checkInterval = setInterval(function(){
				$(".boxContainer .box").eq(array4[num]).addClass("on");
				if(num < selectBox){
					$(".boxContainer .box").eq(array4[num]).children("span").text(num+1);
					num ++;
				}else{
					$(".boxContainer .box").removeClass("on");
					clearInterval(checkInterval);
					clickEn = true;
					$("#game .notice").text("블럭이 나온 순서대로 클릭하세요!");
				}
			},1000);
			
		 }
		 //레벨5
		 if(thisLevel == 5){
			$("#game .state .txt").text("레벨 5");
			$("#game .notice").text("블럭이 나오는 순서를 기억하세요!");
			count = 0;
			num = 0;
			selectBox = 9;
			if(!retry){
				array5 = [];
				for(i=0; i<selectBox; i++){
					var rnd = Math.round(Math.random()* boxLength);
					if(array5.indexOf(rnd) === -1){
						array5.push(rnd);
					}else{
						i--
					}
				}
			}
			checkInterval = setInterval(function(){
				$(".boxContainer .box").eq(array5[num]).addClass("on");
				if(num < selectBox){
					$(".boxContainer .box").eq(array5[num]).children("span").text(num+1);
					num ++;
				}else{
					$(".boxContainer .box").removeClass("on");
					clearInterval(checkInterval);
					clickEn = true;
					$("#game .notice").text("블럭이 나온 순서대로 클릭하세요!");
				}
			},1000);
			
		 }
		 
		
	}

	$(".boxContainer .box").click(function(){
		if(clickEn){
			if($(this).find("span").text()-1 == count){
				// console.log("correct")
				$(this).addClass("on");
				count ++;
				if(count == selectBox){
					retry = false;
					if(thisLevel == 1){
						score = score + 10;
						$("#score em").text(score);
						nextGame();
					}else if(thisLevel == 2){
						score = score + 20;
						$("#score em").text(score);
						nextGame();
					}else if(thisLevel == 3){
						score = score + 30;
						$("#score em").text(score);
						nextGame();
					}else if(thisLevel == 4){
						score = score + 40;
						$("#score em").text(score);
						nextGame();
					}else if(thisLevel == 5){
						score = score + 50;
						$("#score em").text(score);
						$("#txt").show();
						$("#txt span").attr("class","complete");
						setTimeout(function(){
							completeGame();
						}, 1500);
					}
					
				}
			}else{
				// console.log("incorrect")
				retry = true;
				clickEn = false;
				life --;
				if(life == 2){
					$("#life .life1").addClass("empty");
					$("#txt").show();
					$("#txt span").attr("class","again");
					setTimeout(function(){
						boxRnd();
					}, 1500);
				};
				if(life == 1){
					$("#life .life2").addClass("empty");
					$("#txt").show();
					$("#txt span").attr("class","again");
					setTimeout(function(){
						boxRnd();
					}, 1500);
				};
				if(life == 0){
					$("#life .life3").addClass("empty");
					setTimeout(function(){
						endGame();
					}, 1500);
				};
				
			}
		}
		
	});

	
	function nextGame(){
		thisLevel++;
		// console.log("현재레벨",thisLevel);
		$("#txt").show();
		$("#txt span").attr("class","win");
		$("#game #character").attr("class","win");
		setTimeout(function(){
			boxRnd();
			$("#game #character").attr("class","");
		}, 1500);
	}
	
	function endGame(){
		console.log("endGame");
		clearInterval(checkInterval);
		$(".boxContainer .box").removeClass("on");
		$("#game .notice").hide();
		$("#txt").hide();
		$(".boxContainer .box span").hide();
		setTimeout( function(){
			result(score);
		} , 500 );
	}
	
	function completeGame(){
		console.log("completeGame");
		clearInterval(checkInterval);
		$(".boxContainer .box").removeClass("on");
		$("#game .notice").hide();
		$(".boxContainer .box span").hide();
		setTimeout( function(){
			$("#txt").hide();
			result(score);
		} , 500 );
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
})