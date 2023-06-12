$(document).ready(function() {
	
	//게임 스타트
	$("#intro .start").click(function(){
		result = 0; // 0: 꽝 1:스타벅스 2:롯데리아 3:베라 4:비타오백 5:gs만원 6:바나나우유
		//gameStart();
		E.RegisterChk({ 'empty':'empty'
			, loginType:'use2'
			, beforeLogin: function(req){
				E.AlertMsg('modal-alert', '알림', '로그인 후 이용 가능합니다.' );
			}
			, callback: function(req){
				JS.loginCallback(req);
			}
		});
	}); 

	//재도전_새로고침
	$($("#popup_gift .btn")).click(function (e) { 
		location.reload();
	}); 
	 
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	//---------------------------------------------------------------------------------------------------------
	var typeNum = 6; //종류
	var result;
	var resultArr; //결과배열
	var speed = 5; //속도(작을수록빠름)

	//실패
	var arr1 = [1, 1, 3]; 
	var arr2 = [2, 2, 4]; 
	var arr3 = [5, 5, 3]; 
	var arr4 = [2, 6, 5]; 
	var arr5 = [1, 3, 2]; 
	var arr6 = [2, 2, 1]; 
	var arr7 = [6, 6, 5]; 
	var arr8 = [4, 6, 2]; 
	var arr9 = [2, 3, 4]; 
	var arr10 = [5, 4, 1]; 

	//당첨
	var win1 = [1, 1, 1] // 스타벅스
	var win2 = [2, 2, 2] // 롯데리아
	var win3 = [3, 3, 3] // 베라
	var win4 = [4, 4, 4] // 비타오백
	var win5 = [5, 5, 5] // GS만원
	var win6 = [6, 6, 6] // 바나나우유

	var failAll = [];
	failAll.push(arr1, arr2, arr3, arr4, arr5, arr6, arr7, arr8, arr9, arr10);

	var randomArr = failAll[Math.floor(Math.random() * failAll.length)];
	console.log(randomArr);

	
	gameStart= function (){
		E.ChargeChance({'empty':'empty'
			, chance_type:'use'
			, chance_type2:'use2'
			, user_type: 5
			, callback:function(req){
				if(req.chance_info)	$(".my em").html(parseInt(req.chance_info.chance_cnt).toLocaleString());
				if (req.result=='o')
				{
					JS.RESULT({ 'empty':'empty'
						, user_type:'use2', user_type2:''
						, loseAfter: function(req){
							console.log( 'lose', req );
							result= 0;

							gameStartNext();

						}
						, winAfter: function(req){
							console.log( 'win', req, window.user_req);

							result= window.user_req.result;

							$.ajax({
								type: 'POST',
								url: '_exec.php',
								data: {
									'mode' : 'WIN_TOKEN'
									, 'TOKEN' : window.user_req.result
								},
								dataType:"json",
								success: function(req) {
									if (req.result=='gift')result= 1;
									else if (req.result=='gift2')result= 2;
									else if (req.result=='gift3')result= 3;
									else if (req.result=='gift4')result= 4;
									else if (req.result=='gift5')result= 5;
									else if (req.result=='gift6')result= 6;
									else result= 0;

									gameStartNext();
								}
							});
						}
					});
				}
				else{
					if (req.res=='use_chance_empty')
					{
						E.AlertMsg('modal-alert','알림','솔트가 부족합니다.');
					}
					else{
						E.AlertMsg('modal-alert','알림','이미 참여 하셨습니다.');
					}
				}
			}
		});
	}
	gameStartNext= function(){
		$("#intro").hide();
		if(result == 0){
			resultArr = randomArr; //결과
		}else if(result == 1){
			resultArr = win1; //스타벅스
		}else if(result == 2){
			resultArr = win2; //롯데리아
		}else if(result == 3){
			resultArr = win3; //베라
		}else if(result == 4){
			resultArr = win4; //비타오백
		}else if(result == 5){
			resultArr = win5; //GS만원
		}else if(result == 6){
			resultArr = win6; //바나나우유
		}
		
		$("#game .wrap li .disc spandGame").find("span").hide();
		TweenLite.killTweensOf($("#wrap #game li").find(".disc"));
		spin(0,resultArr[0]);
		spin(1,resultArr[1]);
		spin(2,resultArr[2]);
		setTimeout(function() {
			endGame();
		}, 7500);
	}
 
	function spin(num,resultArr){
		var totalNum = 80;
		var thisNum = 0;
		$("#wrap #game li").eq(num).find(".disc").append('<span class="c'+resultArr+'"></span>');
		var thisType = resultArr;
		for(var i=0; i<totalNum;i++){
			thisType++
			if(thisType>typeNum)thisType=1;
			$("#wrap #game li").eq(num).find(".disc").append('<span class="c'+thisType+'"></span>');
			thisNum++
		}
		$("#wrap #game li").eq(num).find(".disc").css("top",-($("#wrap #game li").height()*thisNum))
		TweenLite.to($("#wrap #game li").eq(num).find(".disc"), speed+(num*1.5), {css : {top:0},delay:0});
		
	}
	

	function endGame(){
		$("#popup_gift").find(".giftImg").attr("src","images/use3/popup_gift" + result + ".png")
		setTimeout(function() {
			openPopup("popup_gift");
			if(result == 0){
				$("#popup_gift .retry").show();
				$("#popup_gift .confirm").hide();
			}
		}, 1000);
	}

	function openPopup(id){
		closePopup();
		$("#"+id).stop().fadeIn();
		$("body").css("overflow-y","hidden");
	}
	
	function closePopup(){
		$(".popup").fadeOut();
		$("body").css("overflow-y","auto");
	}
	

})