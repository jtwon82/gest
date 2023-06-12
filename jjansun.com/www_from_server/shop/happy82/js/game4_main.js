$(document).ready(function() {
	
	//게임 스타트
	$("#intro .start").click(function(){
		//alert("이미 참여하였습니다. \n내일 다시 도전하세요!");
		//gameStart();
		E.RegisterChk({ 'empty':'empty'
			, loginType:'game4'
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
				, user_type:'game4', user_type2:score
				, loseAfter: function(req){
					console.log( 'lose' );
				}
				, winAfter: function(req){

					E.ChargeChance({'empty':'empty'
						, chance_type:'add'
						, chance_type2:'game4'
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
					$("#intro").hide()
					gamePlay();
				} , 1000);
			} , 1000);
		} , 1000);
	}
	
	var score = 0;
	var clickEn = false;
	var makeObjNum = 0;
	var makeObjInterval;
	var makeIntervalSpeed  = 500;
	
	function gameIni(){
		console.log("gameIni");
		$("#timer").text("10:00");
	}
	gameIni();


	function gamePlay(){
		console.log("gamePlay");
		makeObj();
		clearInterval(counter);
		counter = setInterval(timer, 10);
	}

	var array1;
	function makeObj(){
		clickEn = true;
		array1 = [1, 2, 3, 4, 5, 6, 7, 8, 9, 1, 2, 3, 4, 5, 6, 7, 8, 9, 1, 2, 3, 4, 5, 6, 7, 8, 9, 1, 2, 3, 4, 5, 6, 7, 8, 9, 1, 2, 3, 4, 5, 6, 7, 8, 9, 1, 2, 3, 4, 5, 6, 7, 8, 9, 1, 2, 3, 4, 5, 6, 7, 8, 9, 1, 2, 3, 4, 5, 6, 7, 8, 9];
		for (var i in array1) {
			array1.sort(function() {
				return Math.random() - Math.random()
			});
		};
		console.log(array1);
		makeObjInterval = setInterval(function () {
			//console.log("make");
			upMole(array1[makeObjNum]);
			makeObjNum++;
			if(makeObjNum >= array1.length){
				clearInterval(makeObjInterval);
			}
		}, makeIntervalSpeed);
	}


	function upMole(num){
		if($("#game .hole"+num+" .mole span").hasClass("up") == false){
			//console.log("up "+num)
			$("#game .hole"+num+" .mole span").addClass("up");
			$("#game .hole"+num+" .mole span").show();
			TweenLite.to($("#game .hole"+num+" .mole span"), 0, {css : {"bottom":"-100%"},delay:0, ease: Linear.easeNone});
			TweenLite.to($("#game .hole"+num+" .mole span"), 0.2, {css : {"bottom":"0%"},delay:0, ease: Linear.easeNone});
			TweenLite.to($("#game .hole"+num+" .mole span"), 0.2, {css : {"bottom":"-100%"},delay:0.4, ease: Linear.easeNone});
			setTimeout( function(){
				$("#game .hole"+num+" .mole span").removeClass("up");
				$("#game .hole"+num+" .mole span").hide();
				
			} , 700 );
		}
	}
	
	if(mobile){
		$("#game .mole span").bind("touchstart",function(e){
			if(clickEn){
				hit($(this).parent().parent().index());
			}
		});
	}else{
		$("#game .mole span").click(function() {
			if(clickEn){
				hit($(this).parent().parent().index());
			}
		});
	}
	$("#game #box").bind("touchstart",function(e){
		e.preventDefault();
	});

	function hit(num){
		var num = num+1;
		console.log("hit "+num);
		$("#game .hole"+num+" .mole span").hide();
		$("#game .hole"+num+" .mole_hit span").show();
		setTimeout( function(){
			$("#game .hole"+num+" .mole span").show();
			$("#game .hole"+num+" .mole_hit span").hide();
		} , 300 );
		score++;
		$("#game .score em").text(score);
	}


	function complete(){
		console.log("complete");
		clearInterval(makeObjInterval);
		clickEn = false;
		$("#stop").click();
		setTimeout( function(){
			result(score*3);
		} , 500 );
	}
	
	function timeover(){
		console.log("timeover!")
		clearInterval(makeObjInterval);
		clickEn = false;
		setTimeout( function(){
			result(score*3);
		} , 500 );
	}
	
	
	
	
	var initial = 3000;
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

	$('#reset').on('click', function() {
		clearInterval(counter);
		count = initial;
		displayCount(count);
	});

	displayCount(initial);
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
})