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
	
	var mobile = false;
	var mobileKeyWords = new Array('iPhone', 'iPod', 'iPad', 'BlackBerry', 'Android', 'Windows CE', 'LG', 'MOT', 'SAMSUNG', 'SonyEricsson');
	for (var word in mobileKeyWords){
		if (navigator.userAgent.match(mobileKeyWords[word]) != null){
			mobile=true;
			break;
		}
	}
	

	gameStart= function (){
		//iniGame(result);

		E.ChargeChance({'empty':'empty'
			, chance_type:'use'
			, chance_type2:'use2'
			, user_type: 5
			, callback:function(req){
				if(req.chance_info)	$(".my em:eq(1)").html(parseInt(req.chance_info.chance_cnt).toLocaleString());
				if (req.result=='o')
				{
					JS.RESULT({ 'empty':'empty'
						, thumb:'use_thumb2.jpg'
						, user_type:'use2', user_type2:''
						, loseAfter: function(req){
							console.log( 'lose', req );
							result= 0;
							iniGame(result);

						}
						, winAfter: function(req){
							console.log( 'win', req, window.user_req);

							result= window.user_req.result;

							$.ajax({
								type: 'POST',
								url: '_exec.php',
								data: {
									'mode' : 'WIN_TOKEN'
									, chance_type2:'use2'
									, 'TOKEN' : window.user_req.result
								},
								dataType:"json",
								success: function(req) {
									if (req.result=='x')
									{
										E.AlertMsg('modal-alert','알림',req.msg);
									}
									else {
										if (req.result=='gift')result= 1;
										else if (req.result=='gift2')result= 2;
										else if (req.result=='gift3')result= 3;
										else if (req.result=='gift4')result= 4;
										else if (req.result=='gift5')result= 5;
										else if (req.result=='gift6')result= 6;
										else result= 0;

										$("#popup_gift").find(".giftImg").attr("src","/upfile/"+ req.win_img);

										iniGame(result);
									}
								}
							});
						}
					});
				}
				else{
					if (req.res=='use_chance_empty')
					{
						E.AlertMsg('modal-alert','알림','솔트가 부족합니다.<br>적립게임으로 솔트를 모아주세요');
					}
					else{
						E.AlertMsg('modal-alert','알림','이미 참여 하셨습니다.');
					}
				}
			}
		});
	}

	// $(".join").on("click",function(){
	// 	iniGame(result);
	// });
	
	var result;
	var rotationPos = new Array(30, 72, 101, 168, 242, 303, 347);

	
	iniGame = function(num){
		$("#intro").hide();
		result = num;
		TweenLite.killTweensOf($(".board_on"));
		TweenLite.to($(".board_on"), 0, {css:{rotation:rotationPos[result]}});
		TweenLite.from($(".board_on"), 5, {css:{rotation:-4000}, onComplete:endGame, ease:Sine.easeOut});
	}




	TweenLite.to($(".board_on"), 60, {css:{rotation:1800}, ease: Linear.easeNone});

	function endGame(){
		if(result==0)$("#popup_gift").find(".giftImg").attr("src","images/use2/popup_gift" + result + ".png")
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