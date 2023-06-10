$(document).ready(function() {
	
	//게임 스타트
	$("#intro .start").click(function(){
		result = 0; // 0: 꽝 1:스타벅스 2:롯데리아 3:베라 4:비타오백 5:gs만원 6:바나나우유
		//gameStart();
		E.RegisterChk({ 'empty':'empty'
			, loginType:'use1'
			, beforeLogin: function(req){
				E.AlertMsg('modal-alert', '알림', '로그인 후 이용 가능합니다.' );
			}
			, callback: function(req){
				JS.loginCallback(req);
			}
		});
	});

	//재도전_새로고침
	$($(".retry, .confirm")).click(function (e) { 
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
		//scratchStart();

		E.ChargeChance({'empty':'empty'
			, chance_type:'use'
			, chance_type2:'use1'
			, user_type: 5
			, callback:function(req){
				if(req.chance_info)	$(".my em:eq(1)").html(parseInt(req.chance_info.chance_cnt).toLocaleString());
				if (req.result=='o')
				{
					JS.RESULT({ 'empty':'empty'
						, thumb:'use_thumb1.jpg'
						, user_type:'use1', user_type2:''
						, loseAfter: function(req){
							//console.log( 'lose', req );

							result= 0;
							scratchStart();

						}
						, winAfter: function(req){
							//console.log( 'win', req, window.user_req);

							result= window.user_req.result;
							scratchStart();

						}
					});
				}
				else{
					if (req.res=='use_chance_empty')
					{
						E.AlertMsg('modal-alert','알림','솔트가 부족합니다.<br>적립게임으로 솔트를 모아주세요!');
					}
					else{
						E.AlertMsg('modal-alert','알림','이미 참여 하셨습니다.');
					}
				}
			}
		});
	}

	
	// function gameReady(){
	
		
	// }
	
	var result; //꽝=lose, 당첨=win
	var scratchEnd = false;
	var resultBg;
	
	

	function scratchStart(){
		$("#intro").hide();
		$.ajax({
			type: 'POST',
			url: '_exec.php',
			data: {
				'mode' : 'WIN_TOKEN'
				, chance_type2:'use1'
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

					if(result == 0)resultBg = "images/use1/scratch_pad_0.png";
					if(result == 1)resultBg = "/upfile/"+req.win_img;//"images/use1/scratch_pad_1.png";
					if(result == 2)resultBg = "/upfile/"+req.win_img;//"images/use1/scratch_pad_2.png";
					if(result == 3)resultBg = "/upfile/"+req.win_img;//"images/use1/scratch_pad_3.png";
					if(result == 4)resultBg = "/upfile/"+req.win_img;//"images/use1/scratch_pad_4.png";
					if(result == 5)resultBg = "/upfile/"+req.win_img;//"images/use1/scratch_pad_5.png";
					if(result == 6)resultBg = "/upfile/"+req.win_img;//"images/use1/scratch_pad_6.png";
					$('.scratchpad').wScratchPad({
						size : 20,
						bg : resultBg,
						fg : 'images/use1/scratch_top.png',
						scratchMove : function(e, percent) {
							if (percent > 60 && scratchEnd == false) {
								this.clear();
								this.scratch = false;
								if(result == 0){
									$(".retry").fadeIn();
									$(".confirm").hide();
								}else{
									$(".confirm").fadeIn();
									$(".retry").hide();
								}
							}
						}
					});
				}
			}
		});

	}
	
	
	
	
	
	
	
	
	
})