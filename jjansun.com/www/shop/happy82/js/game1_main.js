$(document).ready(function() {
	
	//게임 스타트
	$("#intro .start").click(function(){
		//alert("이미 참여하였습니다. \n내일 다시 도전하세요!");
		//gameStart();
		E.RegisterChk({ 'empty':'empty'
			, loginType:'game1'
			, beforeLogin: function(req){
				E.AlertMsg('modal-alert', '알림', '로그인 후 이용 가능합니다.' );
			}
			, callback: function(req){
				//$("#main .select_wrap").hide();
				//JS.member_info= req.info;
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
				, user_type:'game1', user_type2:score
				, loseAfter: function(req){
					console.log( 'lose' );
				}
				, winAfter: function(req){

					E.ChargeChance({'empty':'empty'
						, chance_type:'add'
						, chance_type2:'game1'
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
//
//	setTimeout(function(){
//		result(1);
//	},1000);

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
	
	
	
	var clickEn = false;
	var cardLength = ["",4, 4, 9, 9, 16];
	var thisLevel = 1;
	var firstPos;
	var array0;
	var array1;
	var thisCnt;

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
					gameReady();
				} , 1000);
			} , 1000);
		} , 1000);
	}
	
	
	$('#start').on('click', function() {
		clearInterval(counter);
		counter = setInterval(timer, 10);
	});
	
	function gameIni(){
		console.log("gameIni");
		$("#timer").text("10:00");
		$("#intro .count span").hide();
	}
	gameIni();
	
	
	function gameReady(){
		console.log("gameReady");
		$(".state .level .txt").text("레벨 "+thisLevel);
		$(".cardContainer").addClass("l"+thisLevel);

		thisCnt = 0;
		
		array0 = new Array();
		array1 = new Array();
		
		var firstNum;
		if(thisLevel == 1){
			firstNum = 1;
		}
		if(thisLevel == 2){
			firstNum = Math.round(Math.random()*1+2);
		}
		if(thisLevel == 3){
			firstNum = 1;
		}
		if(thisLevel == 4){
			firstNum = Math.round(Math.random()*3+1);
		}
		if(thisLevel == 5){
			firstNum = Math.round(Math.random()*5+1);
		}
		
		var thisRndNum = Math.round((Math.random()*2+1)+firstNum);
		for(i=0; i<cardLength[thisLevel]; i++){
			if(thisLevel < 3){
				array0.push(i+firstNum);
				array1.push(i+firstNum);
			}else{
				if(thisLevel > 2){
					thisRndNum =Math.round(Math.random()*2+1+thisRndNum);
					array0.push(thisRndNum);
					array1.push(thisRndNum);
				}else{
					array0.push(i*2+firstNum);
					array1.push(i*2+firstNum);
				}
			}
		}
		for (var i in array1) {
			array1.sort(function() {
				return Math.random() - Math.random()
			});
		};
		
		
		$(".cardContainer").html("");
		for(i=0; i<cardLength[thisLevel]; i++){
			$(".cardContainer").append('<div class="card'+i+' card"></div>')
			$(".cardContainer .card"+i).text(array1[i]);
			var min = Math.min.apply(null, array1);
			if(array1[i] == min)firstPos = i;
		}
		$(".cardContainer").show();
		// if(thisLevel < 3)hintBlink();
		hintBlink();
		
		clickEn = true;
		if(mobile){
			$(".cardContainer .card").bind("touchstart",function(e){
				e.preventDefault();
				if($(this).hasClass('disable') == false && clickEn == true){
					if($(this).text() == array0[thisCnt]){
						thisCnt++;
						correct($(this).index())
						if(thisCnt >= cardLength[thisLevel]) nextLevel();
					}else{
						incorrect($(this).index())
					}
				}
			});
		}else{
			$(".cardContainer .card").bind('click', function() {
				if($(this).hasClass('disable') == false && clickEn == true){
					if($(this).text() == array0[thisCnt]){
						thisCnt++;
						correct($(this).index())
						if(thisCnt >= cardLength[thisLevel]) nextLevel();
					}else{
						incorrect($(this).index())
					}
				}
			});
		}
		$('#start').click();
		
	}
	
	function hintBlink(){
		$(".cardContainer .card"+firstPos).addClass("hint");
	}
	
	function correct(idx){
		$('#add').click();
		$(".cardContainer .card"+idx).removeClass("hint");
		$(".cardContainer .card"+idx).addClass("disable");
		$(".cardContainer .card"+idx).addClass("animated flash");
		setTimeout( function(){
			$(".cardContainer .card"+idx).removeClass("animated flash");
		} , 500 );
	}
	
	function incorrect(idx){
		$(".cardContainer .card"+idx).addClass("animated shake");
		setTimeout( function(){
			$(".cardContainer .card"+idx).removeClass("animated shake");
		} , 500 );
	}
	
	
	
	function nextLevel(){
		console.log("nextLevel");
		$('#stop').click();
		
		$(".cardContainer .card").unbind('touchstart');
		$(".cardContainer .card").unbind('click');
		setTimeout( function(){
			$(".cardContainer").hide();
			$(".cardContainer").removeClass("l"+thisLevel);
			thisLevel++;
			$(".nextLevel").text("레벨 "+thisLevel);
			
			if(thisLevel>5){
				console.log("complete");
				clickEn = false;
				$(".cardContainer").fadeOut();
				$("#game .complete").fadeIn();
				setTimeout( function(){
					result((thisLevel-1) * 20);
				} , 500 );
			}else{
				$(".nextLevel").slideDown(200);
				setTimeout( function(){
					$(".nextLevel").slideUp(200);
					setTimeout( function(){
						gameReady();
					} , 500 );
				} , 800 );
			}
			
		} , 500 );
	}
	
	function timeover(){
		console.log("timeover!")
		clickEn = false;
		$(".cardContainer").fadeOut();
		$("#game .gameover").fadeIn();
		setTimeout( function(){
			result((thisLevel-1) * 20);
		} , 500 );
	}
	
	
	
	
	var initial = 1000;
	var count = initial;
	var counter;
	
	function timer() {
		if (count <= 0) {
			clearInterval(counter);
			return;
		}
		count--;
		displayCount(count);
	}

	function displayCount(count) {
		var res = count / 100;
		document.getElementById("timer").innerHTML = res.toPrecision(count.toString().length);
		if(res <= 0)timeover();
	}
	
	$('#stop').on('click', function() {
		clearInterval(counter);
	});
	
	$('#add').on('click', function() {
		clearInterval(counter);
		var num;
		if(thisLevel == 1 || thisLevel == 2)num = 70;
		if(thisLevel == 3 || thisLevel == 4 || thisLevel == 5)num = 65;
		count = count+num;
		counter = setInterval(timer, 10);
	});

	$('#reset').on('click', function() {
		clearInterval(counter);
		count = initial;
		displayCount(count);
	});

	displayCount(initial);
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
})