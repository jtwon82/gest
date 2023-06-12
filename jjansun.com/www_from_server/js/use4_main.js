$(document).ready(function() {
	
	//게임 스타트
	$("#intro .start").click(function(){
		result = 0; // 0: 꽝 1:스타벅스 2:롯데리아 3:베라 4:비타오백 5:gs만원 6:바나나우유
		//gameStart();
		E.RegisterChk({ 'empty':'empty'
			, loginType:'use4'
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
	var result;

	function cardShow(){
		TweenLite.from($(".card1"), 0.6, {css : {alpha:0},delay:0.05,ease:Sine.easeOut});
		TweenLite.from($(".card2"), 0.6, {css : {alpha:0},delay:0.1,ease:Sine.easeOut});
		TweenLite.from($(".card3"), 0.6, {css : {alpha:0},delay:0.15,ease:Sine.easeOut});
		TweenLite.from($(".card4"), 0.6, {css : {alpha:0},delay:0.2,ease:Sine.easeOut});
		TweenLite.from($(".card5"), 0.6, {css : {alpha:0},delay:0.25,ease:Sine.easeOut});
		TweenLite.from($(".card6"), 0.6, {css : {alpha:0},delay:0.3,ease:Sine.easeOut});
		TweenLite.from($(".card7"), 0.6, {css : {alpha:0},delay:0.35,ease:Sine.easeOut});
		TweenLite.from($(".card8"), 0.6, {css : {alpha:0},delay:0.4,ease:Sine.easeOut});
		TweenLite.from($(".card9"), 0.6, {css : {alpha:0},delay:0.45,ease:Sine.easeOut});
		TweenLite.from($(".card10"), 0.6, {css : {alpha:0},delay:0.5,ease:Sine.easeOut});

	}

	gameStart= function (){
		//$("#intro").hide();
		//cardShow();
		//setTimeout(function(){
		//	$("#game .wrap .txt").fadeIn();
		//}, 700);

		E.ChargeChance({'empty':'empty'
			, chance_type:'use'
			, chance_type2:'use4'
			, user_type: 5
			, callback:function(req){
				if(req.chance_info)	$(".my em:eq(1)").html(parseInt(req.chance_info.chance_cnt).toLocaleString());
				if (req.result=='o')
				{
					JS.RESULT({ 'empty':'empty'
						, thumb:'use_thumb4.jpg'
						, user_type:'use4', user_type2:''
						, loseAfter: function(req){
							//console.log( 'lose', req );

							result= 0;

							$("#intro").hide();
							cardShow();
							setTimeout(function(){
								$("#game .wrap .txt").fadeIn();
							}, 700);

						}
						, winAfter: function(req){
							//console.log( 'win', req, window.user_req);

							result= window.user_req.result;

							$("#intro").hide();
							cardShow();
							setTimeout(function(){
								$("#game .wrap .txt").fadeIn();
							}, 700);

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
	var clickEn = true;
	$(".card").click(function(){
		if(clickEn){
			$(this).addClass("click");
			$(this).siblings().removeClass("click");
			$(".txt").hide();
			$(".select").fadeIn();
		}
		
	});
	
	$(".select").click(function(){
		clickEn = false;
		$.ajax({
			type: 'POST',
			url: '_exec.php',
			data: {
				'mode' : 'WIN_TOKEN'
				, chance_type2:'use4'
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

					$(".card.click").animate({
						top: '47%', left: '37%'
					}, 800);
					$(".txt").hide();
					$(".select").hide();
					$(".card.click .back em").append('<img src="images/use4/gift'+ result +'.png">')
					setTimeout(function(){
						$(".card.click .front").hide();
						$(".card.click .back").show();
						$(".card.click").addClass("spin");
					}, 1000);
					setTimeout(function(){
						endGame();
					}, 1100);
				}
			}
		});
	});
 
	function endGame(){
		$("#popup_gift").find(".giftImg").attr("src","images/use4/popup_gift" + result + ".png")
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