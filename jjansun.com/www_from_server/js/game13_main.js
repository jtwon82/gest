$(document).ready(function() {
	
	//게임 스타트
	$("#intro .start").click(function(){
		//alert("이미 참여하였습니다. \n내일 다시 도전하세요!");
//		//gameStart();
		E.RegisterChk({ 'empty':'empty'
			, loginType:'game13'
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
				, thumb:'thumb_game13.jpg'
				, user_type:'game13', user_type2:score
				, loseAfter: function(req){
					console.log( 'lose' );
				}
				, winAfter: function(req){

					E.ChargeChance({'empty':'empty'
						, chance_type:'add'
						, chance_type2:'game13'
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
	var score;
	var correctNum = 0;
	var posDragY = new Array;
	var posDragX = new Array;
	var posDropY = new Array;
	var posDropX  = new Array;
	var mixCardArray = [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15];
	var thisDrag;
	var thisDrop;
	var remitTime = 90;
	var thisTime;
	var timeInterval;
	var time1 = new TimelineLite();
	const date = new Date();
	const today = date.getDay(); 

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

	//요일구분 / 일:0, 월:1, 화:2, 수:3, 목:4, 금:5, 토:6
	if(today == 0){
		$("#game").addClass("sun");
	}else if(today == 1){
		$("#game").addClass("mon");
	}else if(today == 2){
		$("#game").addClass("tue");
	}else if(today == 3){
		$("#game").addClass("wed");
	}else if(today == 4){
		$("#game").addClass("thu");
	}else if(today == 5){
		$("#game").addClass("fri");
	}else if(today == 6){
		$("#game").addClass("sat");
	}

	function gamePlay(){
		$("#cardDrag li, #hint").show();
		timeStart();
		cardMix();
		score = 0;
	}

	

	function cardMix(){
		
		console.log("posDropY",posDropY);
		console.log("posDropX",posDropX);
		console.log("posDragY",posDragY);
		console.log("posDragX",posDragX);

		for (var i in mixCardArray) {
			mixCardArray.sort(function() {
				return Math.random() - Math.random()
			});
		};
		for(var i = 0; i < mixCardArray.length; i++){
			$("#cardDrag li").eq(i).addClass("pos"+mixCardArray[i]);

		}
		$('#cardDrop li').each(function(index) {
			posDropY.push($(this).css("top").replace('px', ''));
			posDropX.push($(this).css("left").replace('px', ''));
		});
	
		$('#cardDrag li').each(function(index) {
			posDragY.push($(this).css("top").replace('px', ''));
			posDragX.push($(this).css("left").replace('px', ''));
		});
		console.log(mixCardArray)
	}
	
	$('#cardDrag li').draggable({
		drag : cardDragStart,
		stop : cardDragStop
	});
	
	$('#cardDrop li').droppable({
		drop : handleDropEvent
	});
	
	function cardDragStart(event, ui) {
		TweenLite.to($(this), 0.2, {css : {"scale":"2", "zIndex":"30"},delay:0,ease : Linear.easeNone});

		if( $(this).index() > 9){
			thisDrag = $(this).attr("class").substring(1,3);
		}else{
			thisDrag = $(this).attr("class").substring(1,2);
		}

		console.log("thisDrag",thisDrag);
	}
	
	function cardDragStop(event, ui) {
		if(thisDrag == thisDrop){
			if(thisDrag == thisDrop){
				correctNum++;
				console.log("correctNum",correctNum);
				$('#cardDrag .m'+thisDrag).clone().appendTo("#cardDragDrop");
				$('#cardDrag .m'+thisDrag).hide();
				TweenLite.to($('#cardDragDrop .m'+thisDrag), 0, {css : {"scale":"1","top":posDropY[thisDrop], "left":posDropX[thisDrop]},delay:0,ease: Linear.easeNone});
				if(correctNum >= 16){
					score = 100;
					endGame();
				}
			}
		}else{
			TweenLite.to($(this), 0.2, {css : {"scale":"1", "zIndex":"2","top":posDragY[thisDrag], "left":posDragX[thisDrag]},delay:0,ease : Linear.easeNone});
		}
	}
	
	function handleDropEvent(event, ui) {
		if( $(this).index() > 9){
			thisDrop = $(this).attr("class").substring(1,3);
		}else{
			thisDrop = $(this).attr("class").substring(1,2);
		}
		console.log("thisDrop",thisDrop);
	}

	function timeStart(){
		clickEn = true;
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
		clickEn = false;
		clearInterval(timeInterval);
		TweenLite.killTweensOf(time1);
		endGame();
		console.log("timeout");
	}
	

	function endGame(){
		clearInterval(timeInterval);
		time1.pause();
		TweenLite.killTweensOf(time1);
		console.log("CLEAR!");
		setTimeout( function(){
			result(score);
		} , 1500 );
	}

	
	
	
	
})