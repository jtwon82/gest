$(document).ready(function() {
	
	//게임 스타트
	$("#intro .start").click(function(){
		//alert("이미 참여하였습니다. \n내일 다시 도전하세요!");
		//gameStart();
		E.RegisterChk({ 'empty':'empty'
			, loginType:'game11'
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
				, thumb:'thumb_game11.jpg'
				, user_type:'game11', user_type2:score
				, loseAfter: function(req){
					console.log( 'lose' );
				}
				, winAfter: function(req){

					E.ChargeChance({'empty':'empty'
						, chance_type:'add'
						, chance_type2:'game11'
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
	var randomArray;
	var randomArray_del;
	var thisItem;
	var itemCorrect;
	var itemNum;
	var makeItemInterval;
	var score = 0;
	var remitTime = 30;
	var thisTime;
	var timeInterval;
	var time1 = new TimelineLite();
	
	$("#score em").text(score);

	gameStart=function (){
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
		score = 0;
		randomArray_del = new Array();
		randomArray = [1,2,3];
		randomItem();
		$("#game .wrap .handObj").show();
		setTimeout(function(){
			$("#game .wrap .handObj").fadeOut();
		}, 1000);
		setTimeout(function(){
			makeItem();
			timeStart();
		}, 500);
	}

	
	
	function randomItem(){
		if(randomArray_del.length>0){
			for(i=0; i<randomArray_del.length;i++){
				var removeItem = randomArray_del[i];
				randomArray = jQuery.grep(randomArray, function(value) { return value != removeItem; });
			}
		}
		thisItem = 0;
		for (var i in randomArray) {
			randomArray.sort(function() {
				return Math.random() - Math.random()
			});
		};
		randomArray.push(0);
		//console.log("random order="+randomArray);
	}
	
	function makeItem(){
		itemNum = 0;
		makeItemInterval = setInterval(function(){
			if(itemNum%Math.round(Math.random()*10+1)==0){ 
				itemCorrect = false;
				if(itemNum==0){
					itemCorrect = true;
				}
			}else{
				itemCorrect = true;
			}
			$("#area").append('<div id="itemNum'+itemNum+'" class="item"><span class=""></span></div>');
			
			if(itemCorrect){
				if(randomArray[thisItem] != 0){
					itemStart(itemNum, randomArray[thisItem]);
					//console.log("index"+itemNum+" = type"+randomArray[thisItem]);
				}else{
					itemStart(itemNum, "trick");
				}
				thisItem++;
			}else{
				itemStart(itemNum, "trick");
				//console.log("index"+itemNum+" = "+"trick");
			}
			
			if(thisItem == randomArray.length){
				randomArray_del.push(0);
				randomItem();
			}
			itemNum++
		},400)
		
	}
	
	function itemStart(itemNum, type){
		var time = 1.2;
		TweenLite.to($("#area #itemNum"+itemNum), 0, {css : {left:"-100%", alpha:1},delay:0,ease : Linear.easeNone});
		TweenLite.to($("#area #itemNum"+itemNum), time, {css : {left:"100%"},delay:0.5,ease : Linear.easeNone});
		TweenLite.to($("#area #itemNum"+itemNum), 0.2, {css : {alpha:0},delay:Number(time+0.5),ease : Linear.easeNone});
		
		if(type != "trick"){
			$("#area #itemNum"+itemNum).find("span").attr("class","item"+type);
		}else{
			$("#area #itemNum"+itemNum).find("span").attr("class","trick");
		}
	}

	function timeStart(){
		clickEn = true;
		TweenLite.killTweensOf(time1);
		time1 = new TimelineLite();
		$("#bar span").css("width", "100%");
		time1.to($("#bar span"), remitTime, {css : {"width":"0%"},delay:0,ease : Linear.easeNone});
		thisTime = remitTime;
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
		clearInterval(makeItemInterval);
		endGame();
		//console.log("timeout");
	}
	
	TweenLite.to($("#game #hand em"), 0, {css : {scale:0.8},delay:0,ease : Linear.easeNone});
	
	
	$("#game").bind('mousedown touchstart', function(e) {
		e.preventDefault();
		if(clickEn){
			//console.log("click")
			clickEn = false;

			$("#game #hand em").attr("class","click");
			TweenLite.to($("#game #hand em"), 0, {css : {scale:0.8, alpha:1},delay:0,ease : Linear.easeNone});
			TweenLite.to($("#game #hand em"), 0.1, {css : {scale:1},delay:0,ease : Linear.easeNone});
			TweenLite.to($("#game #hand em"), 0.1, {css : {alpha:0,scale:1.1},delay:0.2,ease : Linear.easeNone});

			$("#score em").text(score);
			for(i=0; i< itemNum; i++){
				if(collision($("#game #hand .hit"), $("#area #itemNum"+i).find("span"))){
					var hitNum = i;
					if($("#area #itemNum"+hitNum+" span").attr("class") == "item1" || $("#area #itemNum"+hitNum+" span").attr("class") == "item2" || $("#area #itemNum"+hitNum+" span").attr("class") == "item1"){
						clickEff_succ(hitNum);
						return;
					}else if($("#area #itemNum"+hitNum+" span").attr("class") == "trick"){
						clickEff_fail();
						return;
					}
				}
			}
			if(score > 0){
				score= score-1;
			}
			$("#score em").text(score);
			clickEff_end();
		}
	});
	
	
	function clickEff_succ(itemNum){
		var num = $("#area #itemNum"+itemNum).find("span").attr("class").replace(/[^0-9]/g,"");
		//console.log("complete " + "type"+num);
		
		$("#game #hand em").attr("class","correct");
		TweenLite.to($("#game #hand em"), 0, {css : {scale:0.8, alpha:1},delay:0,ease : Linear.easeNone});
		TweenLite.to($("#game #hand em"), 0.1, {css : {scale:1},delay:0,ease : Linear.easeNone});
		TweenLite.to($("#game #hand em"), 0.1, {css : {alpha:0,scale:1.1},delay:0.2,ease : Linear.easeNone, onComplete:clickEff_end});
		TweenLite.to($("#area #itemNum"+itemNum), 0, {css : {alpha:0},delay:0,ease : Linear.easeNone});
		
		score= score+5;
		$("#score em").text(score);
	}
	
	function clickEff_fail(){
		//console.log("fail");
		$("#game #hand em").attr("class","incorrect");
		TweenLite.to($("#game #hand em"), 0, {css : {scale:0.8, alpha:1},delay:0,ease : Linear.easeNone});
		TweenLite.to($("#game #hand em"), 0.1, {css : {scale:1},delay:0,ease : Linear.easeNone});
		TweenLite.to($("#game #hand em"), 0.1, {css : {alpha:0,scale:1.1},delay:0.2,ease : Linear.easeNone, onComplete:clickEff_end});
		if(score > 0){
			score= score-3;
		}
		$("#score em").text(score);
	}
	
	function clickEff_end(){
		clickEn = true;
	}
	
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
	

	function endGame(){
		//console.log("CLEAR!");
		clearInterval(makeItemInterval);
		setTimeout( function(){
			result(score);
		} , 500 );
	}

	
	
	
	
	
	
	
	
})