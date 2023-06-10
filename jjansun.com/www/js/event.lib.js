	var mobile= true;
	var kakaokey= 'c567ece6b325ad8e149bd9331e7cbc08';
	$(document).ready(function() {
		JS.INIT();

		if ( Math.random() > .9 ) new Image().src='_exec.php?mode=CLEAR';
	});
	var interval= setInterval(function(){
		// base info
		//
		try{
			Kakao.init(kakaokey);
			clearInterval(interval);
		}catch(e){}
	},500);
	$(".my em:eq(1)").html(parseInt(getCookie('chance_cnt')).toLocaleString());

	var JJANSUN= function(){
	};
	JJANSUN.prototype = {
		test : function(){

		}, INIT : function(){
			
			E.ChargeChance({'empty':'empty'
				, chance_type:'getInfo'
				, callback:function(req){
					window.getInfo= req;
					try{
						if(req.chance_info)	$(".my em:eq(1)").html(parseInt(req.chance_info.chance_cnt).toLocaleString());
						setCookie('chance_cnt',req.chance_info.chance_cnt);
					}catch(e){}

					// main
						var $main= $(".main");
						if ($main.length>0)
						{
							if(req.play_list)
								$(req.play_list.game_list).each(function(){
									if (parseInt(this.cnt)>0)
									{
										$($main).find("#section1").find('.'+this.chance_type2).addClass('complete')
											.click(function(e){
													//e.preventDefault();
													//E.AlertMsg('modal-alert','알림','오늘 이미 참여하셨습니다.');
												}
											);
									}
								});
							if(req.play_list)
								$(req.play_list.use_list).each(function(){
									if (parseInt(this.cnt)>0)
									{
										$($main).find("#section2").find('.'+this.chance_type2).addClass('complete')
											.click(function(e){
													e.preventDefault();
													E.AlertMsg('modal-alert','알림','오늘 이미 참여하셨습니다.');
												}
											);
									}
								});

							var $section1= $("#section1");
							if (req.list_salt){
								$(req.list_salt.list_salt_2x).each(function(){
									var origin_html= $section1.find('.swiper-wrapper .'+this.gamecode+' a').html();
									$section1.find('.swiper-wrapper .'+this.gamecode+' a').html('<p class="x'+this.val+'"></p>'+ origin_html);
								});
							}

							$("#notice ul").empty();
							if (true)
							{
								$.ajax({
									type: 'POST',
									url: '_exec.php',
									data: { 'mode' : 'GET_COMMON_INFO' },
									dataType:"json",
									success: function(req) {
										$(req.list_notice).each(function(){
											//console.log( this );
											var page=1;
											$("#notice ul").append('<li><a href="cs_board_detail.html?idx='+this.idx+'&page='+page+'">'+this.title+'</a></li>');
										});
										//B.BannerList(1);
										if (req.list_banner)
										{
											var $list = $("#banner .swiper-wrapper");
											$list.empty();

											$(req.list_banner).each(function(){
												var html= '';
													html+='<div class="swiper-slide slide2"><a href="'+this.link+'"><img src="upfile/'+this.filename+'"></a></div>';
												
												$list.append(html);
											});
											if (!req.list_banner)
											{
												$list.append('<tr><td colspan=5>데이터가 없습니다.</td></tr>');
											}
											window.swiper0 = new Swiper("#banner .swiper-container", {
												slidesPerView : 1,
												loop : true,
												observer : true,
												observeParents : true,
												autoplay: {
													delay: 5000,
													disableOnInteraction: false,
												},
												pagination : {
													el : "#banner .swiper-pagination",
													clickable : true,
												},
											});
											//sliderReload(window.swiper0);
										}
									}
								});
							}
						}

					// mypage
						var $mypage= $(".mypage");
						if ($mypage.length>0 )
						{

							if ($mypage.data('binding')!='true')
							{
								$mypage.data('binding','true');
								$($mypage).find("#input-file").one("change", function () {
									$(".dimmed").show();
									var thiss = this;
									if (thiss.files && thiss.files[0]) {
										var file = thiss.files[0];

										var reader= new FileReader();
										reader.onload = function() {
											EXIF.getData(file, () => {
												var tags = EXIF.getAllTags( file );

												var fd = new FormData();
												fd.append('mode', 'UPDATE_PROFILE');
												fd.append('user_type', 'img');
												fd.append('file', file);
												fd.append('Orientation', tags.Orientation);
												$.ajax({
													url: "_exec.php",
													type: "POST",
													data: fd,
													dataType: "json",processData: false,contentType: false
													,xhrFields: { 
														withCredentials: true // 클라이언트와 서버가 통신할때 쿠키와 같은 인증 정보 값을 공유하겠다는 설정
													},beforeSend: function( xhr ) {
														//E.AlertMsg('modal-alert','알림','잠시만 기다려주세요.');
													}
													,success : function( xhr ) {
														//$("#modal-alert").modal('hide');
													}
												}).done(function (req) {
													console.log( req );
													if (req.member_typehistory.cnt==1)
													{
														E.AlertMsg('modal-alert','알림','축하합니다. 레벨이 1단계 업그레이드되었습니다.');
													}
													//$(".dimmed").hide();
													//JS.INIT();
													location.reload();
												});

											});
										};
										reader.readAsDataURL( file );
									}
								});
								$($mypage).find(".nickarea a").on('click',function(){
									$(this).hide();
									$($mypage).find(".inputarea em").hide();
									if ($(this).hasClass('modify'))
									{
										$($mypage).find('.nickarea .confirm').show();
										$($mypage).find('.nickarea em').show();
										$($mypage).find('input[name="uname"]').attr('readonly',false).focus();
									}
									else {
										$($mypage).find(".inputarea em").show();
										$($mypage).find('.nickarea .modify').show();
										$($mypage).find('input[name="uname"]').attr('readonly',true);

										var uname= $($mypage).find('input[name="uname"]').val();
										var fd = new FormData();
										fd.append('mode', 'UPDATE_PROFILE');
										fd.append('user_type', 'uname');
										fd.append('uname', uname );
										if (uname=='')
										{
											E.AlertMsg('modal-alert','알림','정확한 값을 입력해주세요.');
											return;
										}
										$.ajax({
											url: "_exec.php",
											type: "POST",
											data: fd,
											dataType: "json",processData: false,contentType: false
										}).done(function (req) {
											console.log( req );
											if (req.member_typehistory.cnt==1)
											{
												E.AlertMsg('modal-alert','알림','축하합니다. 레벨이 1단계 업그레이드되었습니다.');
												setTimeout(function(){location.reload()},1000);
											}
											JS.INIT();
										});
									}
								});
								$($mypage).find(".callarea a").on('click',function(){
									$(this).hide();
									$($mypage).find(".inputarea em").hide();
									if ($(this).hasClass('modify'))
									{
										$($mypage).find('.callarea .confirm').show();
										$($mypage).find('.callarea em').show();
										$($mypage).find('input[name="pno"]').attr('readonly',false).focus();
									}
									else {
										$($mypage).find(".inputarea em").show();
										$($mypage).find('.callarea .modify').show();
										$($mypage).find('input[name="pno"]').attr('readonly',true);

										var pno= $($mypage).find('input[name="pno"]').val()
										var fd = new FormData();
										fd.append('mode', 'UPDATE_PROFILE');
										fd.append('user_type', 'pno');
										fd.append('pno', pno );
										if (pno=='')
										{
											E.AlertMsg('modal-alert','알림','정확한 값을 입력해주세요.');
											return;
										}
										$.ajax({
											url: "_exec.php",
											type: "POST",
											data: fd,
											dataType: "json",processData: false,contentType: false
										}).done(function (req) {
											console.log( req );
											if (req.member_typehistory.cnt==1)
											{
												E.AlertMsg('modal-alert','알림','축하합니다. 레벨이 1단계 업그레이드되었습니다.');
												setTimeout(function(){location.reload()},1000);
											}
											JS.INIT();
										});
									}
								});
								$($mypage).find(".birtharea a").on('click',function(){
									$(this).hide();
									$($mypage).find(".inputarea em").hide();
									if ($(this).hasClass('modify'))
									{
										$($mypage).find('.birtharea .confirm').show();
										$($mypage).find('.birtharea em').show();
										$($mypage).find('input[name="birthday"]').attr('readonly',false).focus();
									}
									else {
										$($mypage).find(".inputarea em").show();
										$($mypage).find('.birtharea .modify').show();
										$($mypage).find('input[name="birthday"]').attr('readonly',true);

										var birthday= $($mypage).find('input[name="birthday"]').val();
										var fd = new FormData();
										fd.append('mode', 'UPDATE_PROFILE');
										fd.append('user_type', 'birthday');
										fd.append('birthday', birthday );
										if (birthday=='')
										{
											E.AlertMsg('modal-alert','알림','정확한 값을 입력해주세요.');
											return;
										}
										$.ajax({
											url: "_exec.php",
											type: "POST",
											data: fd,
											dataType: "json",processData: false,contentType: false
										}).done(function (req) {
											console.log( req );
											if (req.member_typehistory.cnt==1)
											{
												E.AlertMsg('modal-alert','알림','축하합니다. 레벨이 1단계 업그레이드되었습니다.');
												setTimeout(function(){location.reload()},1000);
											}
											JS.INIT();
										});
									}
								});
								$($mypage).find(".sexarea a").on('click',function(){
									$(this).hide();
									$('.sexarea .sexarea_input').hide();
									$($mypage).find(".inputarea em").hide();
									if ($(this).hasClass('modify'))
									{
										$($mypage).find('.sexarea .confirm').show();
										$($mypage).find('.sexarea em').show();
										//	$($mypage).find('input[name="usex"]').attr('readonly',false).focus();
										$('.sexarea .sexarea_input:eq(1)').show();
									}
									else {
										$($mypage).find(".inputarea em").show();
										$($mypage).find('.sexarea .modify').show();
										//	$($mypage).find('input[name="usex"]').attr('readonly',true);
										$('.sexarea .sexarea_input:eq(0)').show();

										var usex= $($mypage).find('input[name="usex"]:checked').val();
										var fd = new FormData();
										fd.append('mode', 'UPDATE_PROFILE');
										fd.append('user_type', 'usex');
										fd.append('usex', usex );
										if (usex=='')
										{
											E.AlertMsg('modal-alert','알림','정확한 값을 입력해주세요.');
											return;
										}
										$.ajax({
											url: "_exec.php",
											type: "POST",
											data: fd,
											dataType: "json",processData: false,contentType: false
										}).done(function (req) {
											console.log( req );
											if (req.member_typehistory.cnt==1)
											{
												E.AlertMsg('modal-alert','알림','축하합니다. 레벨이 1단계 업그레이드되었습니다.');
												setTimeout(function(){location.reload()},1000);
											}
											JS.INIT();
										});
									}
								});
							}

							if(req.chance_info && req.chance_info.profile_image ){ // 
								$($mypage).find('.top .profileImg .img').html('<img src="'+req.chance_info.profile_image+'"/>');
							}
							$($mypage).find('.top input[name="uname"]').val(req.chance_info.uname_origin);
							$($mypage).find('.top input[name="pno"]').val(req.chance_info.pno_origin);
							$($mypage).find('.top input[name="birthday"]').val(req.chance_info.birthday_origin);
							$($mypage).find('.top input[name="usex2"]').val(req.chance_info.usex_origin);
							$($mypage).find('.top input[name="usex"][value="'+req.chance_info.usex_origin+'"]').prop('checked',true);
							$($mypage).find('.top .reg_date').html('<span>가입일</span>'+req.chance_info.reg_date.substring(0,16) );
							$($mypage).find('.top .member_type').html('<span>레벨</span><b>'+req.chance_info.member_type +'</b><a href="mypage_level.html">등급별 혜택</span>');
							$($mypage).find('.top .current b').html(parseInt(req.chance_info.chance_cnt).toLocaleString());
							$($mypage).find('.top .account b').html(parseInt(req.chance_info.total_chance).toLocaleString());

							// 개인정보 업데이트 버튼 삭제
							if (req.chance_info.member_type!='1')
							{
								$(".box .txt1").hide();
							}

							$(".unlink").click(function(){
								if (confirm('* 탈퇴 시 짠순이게임방에서 제공하는 혜택 및 정보를 제공받지 못합니다. \n* 적립되어 있던 솔트 및 랭킹 개인정보 모두 삭제됩니다. \n\n탈퇴하시겠습니까?'))
								{
									Kakao.Auth.login({
										scope: 'account_email, plusfriends',
										success: function (response_auth) {
											Kakao.Auth.setAccessToken(response_auth.access_token);
											
											Kakao.API.request({
												url: '/v1/user/unlink',
											})
											.then(function(response) {
												console.log(response);

												$.ajax({
													type: 'POST',
													url: '_exec.php',
													data: { 'mode' : 'MEMBER_OUT' },
													dataType:"json",
													success: function(req) {
														console.log( req );
														location.replace("/member/logout.php");
													}
												});
											})
											.catch(function(error) {
												console.log(error);
											});

										},
										fail: function (error) {
											console.log("시스템 사정으로 오류가 발생 했습니다. 잠시 후 다시 시도해주세요");
										}
									});
								}
							});
						}

					// mypage.salt
						var $mypage_salt= $(".mypage.salt");
						if ($mypage_salt.length>0)
						{
							B.BoardList(1);
						}
						else if (req.list_salt)
						{
							var html_level= '';
								html_level+= '<tr>';
								html_level+= '	<td class="date">:reg_date</td>';
								html_level+= '	<td class="type">:type</td>';
								html_level+= '</tr>';
							$(".level_detail tbody").empty();
							$(req.list_salt.list_levelup).each(function(id){
								var t= html_level;
								t= t.replace(':reg_date', this.reg_date.substring(0,16));
								t= t.replace(':type', this.action_type_str);
								$(".level_detail tbody").append(t);
							});
							if ($(".level_detail tbody").find('tr').length<1)
							{
								$(".level_detail tbody").append('<tr><td colspan=2>데이터가 없습니다.</td></tr>');
							}

							var html_salt= '';
								html_salt+= '<tr>';
								html_salt+= '	<td class="date">:reg_date</td>';
								html_salt+= '	<td class="acc">:acc</td>';
								html_salt+= '	<td class="type">:type</td>';
								html_salt+= '	<td class="salt">:salt</td>';
								html_salt+= '</tr>';
							$(".salt_detail tbody").empty();
							$(req.list_salt.list_salt_get).each(function(id){
								var t= html_salt;
								t= t.replace(':reg_date', this.reg_date.substring(0,16));
								t= t.replace(':acc', U.getAtList(['소진','적립'],['use','add'],this.chance_type) );
								t= t.replace(':type', this.chance_type2);
								t= t.replace(':salt', this.current);
								$(".salt_detail tbody").append(t);
							});
							if ($(".salt_detail tbody").find('tr').length<1)
							{
								$(".salt_detail tbody").append('<tr><td colspan=4>데이터가 없습니다.</td></tr>');
							}

							var html_salt2= '';
								html_salt2+= '<tr>';
								html_salt2+= '	<td class="date">:reg_date</td>';
								html_salt2+= '	<td class="type">:type</td>';
								html_salt2+= '	<td class="gift">:gift</td>';
								html_salt2+= '</tr>';
							$(".win_detail tbody").empty();
							$(req.list_salt.list_salt_use).each(function(id){
								var t= html_salt2;
								t= t.replace(':reg_date', this.reg_date.substring(0,16));
								t= t.replace(':type', this.gamename);
								t= t.replace(':gift', this.win_type_str);
								$(".win_detail tbody").append(t);
							});
							if ($(".win_detail tbody").find('tr').length<1)
							{
								$(".win_detail tbody").append('<tr><td colspan=4>데이터가 없습니다.</td></tr>');
							}
						}

					// game
						{
							var $game= $("#game");
							if ($game.length>0)
							{
								if (location.href.indexOf('game13.html')>0)
								{
									var t= U.numberFormater(window.getInfo.list_salt.joincnt_game13.join_cnt);
									$('#intro .current b').html(t);
								}

								$("#popup_score .share").click(function(){
									try{
										var score= window.score;
										var uname= window.getInfo.chance_info.uname_origin;
										uname= uname.startsWith('User-')?'친구':uname+'님';
										var thumb= 'https://www.jjansun.com/images/'+window.user_type.thumb;
										var title= uname+'의 점수는 '+score+'점입니다. 친구보다 더 높은 점수를 획득해 보세요.';
									}catch(e){
										var score=0;
										var uname= '친구';
										var thumb= 'https://www.jjansun.com/images/thumb.jpg';
										var title= '지금 짠순이게임방에서 친구보다 더 높은 점수를 획득해 보세요.'
									}

									Kakao.Link.sendDefault({
										objectType: 'feed',
										content:{
											title: title,
											imageUrl:thumb,
											link:{
												mobileWebUrl:'https://www.jjansun.com/gate.php?ssn='+ssn
											}
											//,imageWidth: 900,imageHeight: 900
										},
										buttons :[
											{
												title :'짠순이 즉석당첨 확인하기',
												link :{
													mobileWebUrl :'https://www.jjansun.com/gate.php?ssn='+ssn
												}
											}
										]
									});
								});
							}

						}

					// cs
						{
							var $cs_faq= $(".cs.faq");
							if ($cs_faq.length>0)
							{
							}
							var $cs_board= $(".cs.board");
							if ($cs_board.length>0)
							{
								if (document.referrer.indexOf('cs_board_detail')>0)
								{
									B.BoardListCustomer(getCookie("page"));
								}
								else{
									B.BoardListCustomer(1);
								}
							}
							var $cs_notice= $(".cs.notice");
							if ($cs_notice.length>0)
							{
								if (document.referrer.indexOf('cs_notice_detail')>0)
								{
									B.BoardListNotice(getCookie("page"));
								}
								else{
									B.BoardListNotice(1);
								}
							}
							var $cs_boarddetail= $(".cs.boarddetail");
							if ($cs_boarddetail.length>0)
							{
								reply.load('clear');
							}
						}

					// salt accumulate
						var $accumulate= $(".accumulate");
						if ($accumulate.length>0)
						{
							if (req.play_list){
								$(req.play_list.game_list).each(function(){
									if (parseInt(this.cnt)>0)
									{
										$($accumulate).find('.'+this.chance_type2).addClass('complete')
											//.click(function(e){
											//	e.preventDefault();
											//	E.AlertMsg('modal-alert','알림','오늘 이미 참여하셨습니다.');
											//});
										;
									}
								});
							}

							if (req.list_salt){
								$(req.list_salt.list_salt_2x).each(function(){
									var origin_html= $accumulate.find('.game .'+this.gamecode+' a').html();
									$accumulate.find('.game .'+this.gamecode+' a').html('<p class="x'+this.val+'"></p>'+ origin_html);
								});
							}
						}

					// salt using
						{
							var $intro= $("#intro");
							if ($intro.length>0)
							{
							}
						}

					// rank
						{
							var $rank= $("#sub.rank");

							if ($rank.length>0)
							{
								if (ssn=='')
								{
									$rank.find('.my').hide();
								}
								try{
									if (location.href.indexOf('rank_invite')>0){
										console.log( 'invite' );

									} else if (location.href.indexOf('rank_all')>0){
										$rank.find('.my_rank .num').html(req.scoreM.jjansun_accumulate.rank);
										$rank.find('.my_rank .id').html("<img src='"+req.chance_info.profile_image+"'>"+req.scoreM.jjansun_accumulate.uname);
										$rank.find('.my_rank .salt').html(parseInt(req.scoreM.jjansun_accumulate.score).toLocaleString());
									} else {
										$rank.find('.my_rank .num').html(req.scoreM.jjansun_week.rank);
										$rank.find('.my_rank .id').html("<img src='"+req.chance_info.profile_image+"'>"+req.scoreM.jjansun_week.uname);
										$rank.find('.my_rank .salt').html(parseInt(req.scoreM.jjansun_week.score).toLocaleString());
									}
								}catch(e){}

								try{
									if (new URL(document.referrer).pathname != location.pathname)
									{
										setCookie("page",1,0);
									}
								}catch(e){
									setCookie("page",1,0);
								}
								
									if (location.href.indexOf('rank_invite')>0){
										console.log('invite');
										B.BoardListRankInvite(getCookie("page"));

									} else if (document.referrer.indexOf('rank')>0) {
										if ($rank.find('.active').data('active')=='week')
											B.BoardListRankWeek(getCookie("page"));
										else
											B.BoardListRankAccumulate(getCookie("page"));
									}
									else{
										if ($rank.find('.active').data('active')=='week')
											B.BoardListRankWeek(1);
										else
											B.BoardListRankAccumulate(1);
									}
							}
						}

					// more
						{
							var $more= $("#sub.more");
							if ($more.length>0)
							{
								if (ssn=='')
								{
									$more.find('.share').click(function(){
										E.AlertMsg('modal-alert','알림','카톡 로그인후 진행해주세요.');
									});
								}
								else{
									$more.find('.share').click(function(){
										E.StartBtn({'empty':'empty'
											, user_type: ''
											, end: function(){
												E.AlertMsg('modal-alert','알림','종료 되었습니다.');
											}
											, start: function(req){
												E.ChargeChance({'empty':'empty'
													, chance_type:'add'
													, chance_type2:'snsshare'
													//, user_type: score
													, callback:function(req){
														setTimeout(function(){
															$(".my em:eq(1)").html(parseInt(req.chance_info.chance_cnt).toLocaleString());
														},3000);

														if (req.res=='sns_today_already')
														{
															E.AlertMsg('modal-alert', '알림', '무분별한 SNS공유는 상대방과의 관계지속에 문제가 될수 있습니다.' );
														}
														else{
															Kakao.Link.sendDefault({
																objectType: 'feed',
																content:{
																	title:'짠순이에서 게임하고 솔트모아 즉석당첨에 참여해보세요.',
																	imageUrl:'https://www.jjansun.com/images/thumb.jpg',
																	link:{
																		mobileWebUrl:'https://www.jjansun.com/gate.php?ssn='+ssn
																	}
																	//,imageWidth: 900,imageHeight: 900
																},
																buttons :[
																	{
																		title :'짠순이 즉석당첨 확인하기',
																		link :{
																			mobileWebUrl :'https://www.jjansun.com/gate.php?ssn='+ssn
																		}
																	}
																]
															});
														}
													}
												});
											}
											, after: function(req){
											}
										});
									});
								}

								$.ajax({
									type: 'POST',
									url: '_exec.php',
									data: { 'mode' : 'GET_COMMON_INFO' },
									dataType:"json",
									success: function(req) {

										var coupang_html='';
											coupang_html+='<li class=":etc1">';
											coupang_html+='	<a href=":landing" target="_blank" data-area=":area"><img src=":imgurl" alt=":title"></a>';
											coupang_html+='	<p class="txt"><img src="images/ico_salt.png" alt="솔트"><b>50</b></p>';
											coupang_html+='</li>';

										if(req.list_coupangad){
											var $banner_new= $('.banner_new ul');
											$banner_new.empty();
											$(req.list_coupangad.coupangadlist).each(function(id){
												var src= $(this.content).find('img').attr('src');
												var alt= $(this.content).find('img').attr('alt');
												//$more.find('.salt-area1 .box').append('<a href="'+this.link+'" target="_blank" data-area="'+id+'"><img src="'+src+'" style="width:80px;" alt="'+alt+'"></a>');
												//$more.find('.salt-area1 .box').find('a').css('float','left');
												var t= coupang_html;
													t= t.replace(':landing', this.link);
													t= t.replace(':imgurl', src);
													t= t.replace(':title', alt);
													t= t.replace(':area', id);
													t= t.replace(':etc1', this.etc1);
												$banner_new.append(t);
											});
											$banner_new.find('a').click(function(){
												E.ChargeChance({'empty':'empty'
													, chance_type:'add'
													, chance_type2:'coupangad_'+$(this).data('area')
													, callback:function(req){
														setTimeout(function(){
															$(".my em:eq(1)").html(parseInt(req.chance_info.chance_cnt).toLocaleString());
														},3000);
													}
												});
											});
										}
										/*if(req.list_eventad){
											$(req.list_eventad.eventlist).each(function(id){
												var src= $(this.content).find('img').attr('src');
												var alt= $(this.content).find('img').attr('alt');

												$more.find('.salt-area2 .box').append('<a href="'+this.link+'" target="_blank" data-area="'+id+'"><img src="'+src+'" style="width:80px;" alt="'+alt+'"></a>');
												$more.find('.salt-area2 .box').find('a').css('float','left');
											});
											$more.find('.salt-area2 .box').find('a').click(function(){
												E.ChargeChance({'empty':'empty'
													, chance_type:'add'
													, chance_type2:'eventadd_'+$(this).data('area')
													, callback:function(req){
														console.log( req );

														setTimeout(function(){
															$(".my em:eq(1)").html(parseInt(req.chance_info.chance_cnt).toLocaleString());
														},3000);
													}
												});
											});
										}*/
									}
								});
							}

							var $more_coupon= $("#sub.more.coupon");
							if ($more_coupon.length>0)
							{
								$(".coupon_regist").click(function(){
									$.ajax({
										type: 'POST',
										url: '_exec.php',
										data: { 'mode' : 'COUPON_REGIST' },
										dataType:"json",
										success: function(req) {
											console.log( req );
										}
									});
								});
							}

						}

					// use 실시간당첨자 marquee
						{
							var $winnerContainer= $("#winnerContainer");
							if ($winnerContainer.length>0)
							{
								$($winnerContainer).find('ul').empty();
								$.ajax({
									type: 'POST',
									url: '_exec.php',
									data: { 'mode' : 'GET_COMMON_INFO' },
									dataType:"json",
									success: function(req) {
										$(req.list_gift).each(function(id){
											//$($winnerContainer).find('ul').append('<li>실시간 당첨자 : <span>'+this.reg_date+'/'+this.win_type_str+'/'+this.uname+'/'+this.pno+'</span></li>');
											$($winnerContainer).find('ul').append('<li>실시간 당첨자 : <span>'+this.reg_date.substring(5,16)+' / '+this.win_type_str+' / '+this.uname+'</span></li>');
										});
										$('#winner').vTicker();

										if(req.list_giftremain){
											var map=[];
											map.push('gift6');map.push('gift5');map.push('gift4');map.push('gift3');map.push('gift2');map.push('gift');
											$(req.list_giftremain).each(function(id){
												if (location.pathname.indexOf(this.event_gubun) >-1 )
												{
													var thiss= this;
													$("#remainGift li p").each(function(){
														var i= map.pop();
														$(this).html(thiss[i] +"개");
													});
												}
											});
										}
									}
								});
							}
						}
				}
			});

		}, RESULT : function(req){
			window.user_type= req;
			E.ChkWinner({empty:'empty'
				, lose: function(req){
					window.user_req= req;
					window.user_type.loseAfter.bind()(req);

				}, gift: function(req){
					window.user_req= req;
					E.Gift({empty:'empty'
						,succ: function(req){
							window.user_type.winAfter.bind()(req);
						}
					});
				}, after:function(req){
				}
			});

		}, loginCallback : function(req){
			if (E.loginType.indexOf('game')>-1)
			{
				E.StartBtn({'empty':'empty'
					, user_type: E.loginType
					, end: function(){
						E.AlertMsg('modal-alert','알림','종료 되었습니다.');
					}
					, start: function(req){
						if (req.play_list.game_list)
						{
							var chk_next= true;
							$(req.play_list.game_list).each(function(){
								console.log( this, parseInt(this.cnt), E.loginType );
								if (this.chance_type2==E.loginType)
								{
									if (parseInt(this.cnt)>0)
									{
										chk_next= false;
									}
								}
							});
							if (chk_next)
							{
								top.gameStart();
							}
							else {
								E.AlertMsg('modal-alert','알림','오늘은 이미 참여하셨습니다.<br>내일 다시 도전해 주세요!');
							}
						}
					}
					, after: function(req){
					}
				});
			}
			else if (E.loginType.indexOf('use')>-1)
			{
				E.StartBtn({'empty':'empty'
					, user_type: E.loginType
					, end: function(){
						E.AlertMsg('modal-alert','알림','종료 되었습니다.');
					}
					, start: function(req){
						top.gameStart();
//						if (req.play_list.use_list)
//						{
//							var chk_next= true;
//							$(req.play_list.use_list).each(function(){
//								console.log( this, parseInt(this.cnt), E.loginType );
//								if (this.chance_type2==E.loginType)
//								{
//									if (parseInt(this.cnt)>0)
//									{
//										chk_next= false;
//									}
//								}
//							});
//							if (chk_next)
//							{
//								top.gameStart();
//							}
//							else {
//								E.AlertMsg('modal-alert','알림','오늘 이미 참여하셨습니다.');
//							}
//						}
					}
					, after: function(req){
					}
				});
			}
		}
	
	}; var JS = new JJANSUN();


	var EVENT= function(){
		this.loginType;
		this.chk;
	};
	EVENT.prototype = {
		test : function(){

		}, AlertMsg : function(id, title, msg){
			U.AlertMsg(id, title, msg);

		}, OnLoad : function(option){
			$.ajax({
				type: 'POST',
				url: '_exec.php',
				data: {
					'mode' : 'OnLoad'
				},
				dataType:"json",
				success: function(req) {
				}
			});
			
		}, RegisterChk : function(option){
			if (option.loginType)
			{
				E.loginType=option.loginType;
			}

			$.ajax({
				type: 'POST',// async: false,
				url: '_exec.php',
				data: { 'mode' : 'REGISTER_CHK' },
				dataType:"json",
				success: function(req) {
					try{
						$(".remain em").html(parseInt(req.info.chance_cnt).toLocaleString());
					}catch(e){}

					if (req.result=='x')
					{
						if(option.beforeLogin)
						option.beforeLogin.bind()(req);
					}
					else {
						if(option.callback)
						option.callback.bind()(req);
					}
				}
			});

		}, DoRegister: function(option){
			var f = option.form;
			var pat = /^[ㄱ-ㅎ|가-힣|a-z|A-Z|\*]+$/;
			if (/[^\s]/g.test( f.uname.value ) == 0) {
				alert("이름을 입력해주세요"); $(f.uname).focus(); return;
			}
			if ( !pat.test( f.uname.value ) ) {
				alert("이름은 한글, 영문자로 입력 가능합니다."); $(f.uname).focus(); return;
			}
			if (f.pno.value=='' || f.pno.value.length<10)
			{
				alert("전화번호를 입력해주세요"); f.pno.focus(); return;
			}
			if (!f.agree.checked)
			{
				alert("개인정보 취급방침에 동의 해주세요."); f.agree.focus(); return;
			}

			var fd = new FormData(f);
			fd.append('mode', 'DO_REGISTER');
			$.ajax({
				type: 'POST',// async: false,
				url: '_exec.php',
				data : fd,
				dataType:"json",
					contentType: "application/x-www-form-urlencoded; charset=UTF-8",
					processData: false, contentType: false,
				success: function(req) {
					if (option.func)
					{
						option.func.bind()(req);
					}
				}
			});

		}, ChargeChance : function(option){
			$.ajax({
				type: 'POST', async: true,
				url: '_exec.php',
				data: { 'mode' : 'CHARGE_CHANCE'
					, 'chance_type' : option.chance_type
					, 'chance_type2' : option.chance_type2
					, 'user_type' : option.user_type
					, 'CHK' : window.chk
						},
				dataType:"json",
				success: function(req) {
					option.callback.bind()(req);
				}
			});

		}, StartBtn : function(option){
			$.ajax({
				type: 'POST',// async: false,
				url: '_exec.php',
				data: {
					'mode' : 'CHKSTART',
					'user_type' : option.user_type,
					'mobile' : (mobile?'mob':'web')
				},
				dataType:"json",
				success: function(req) {
					window.chk= req.chk;
					if (req.result=='end')
					{
						if ( option.end )
						{
							option.end.bind()(req);
						}
					}
					else if (req.result=='o')
					{
						if ( option.start )
						{
							option.start.bind()(req);
						}
					}
					else if (req.result=='full_over_today')
					{
						alert( "이미 참여하셨습니다." );
					}
					else if (req.result=='before')
					{
						alert( "이벤트가 시작되지 않았습니다." );
						location.reload();
					}
					else if (req.result=='x')
					{
						alert("과도한 참여로 인하여 차단 되었습니다.");
						location.reload();
					}
					else if (req.result=='lose_over_today')
					{
						alert("공유하기는 하루 10번 참여 가능하며, 매일 00시에 초기화 됩니다.");
					}
					else if (req.result=='deny')
					{
						alert("지금 참여하시는 아이피는 짧은시간에 너무 많은 응모내역이 있습니다.\n서버에 부하가되지 않는 한도 내에서 응모해주시기 바랍니다.");
					}
					else if (req.result=='9')
					{
						alert('정상적으로 이용해주세요.(브라우져를 1개 이상 열어서 참여가 불가능합니다.)\n지속적인 문제 발생시 다른 브라우져를 이용해주세요\n기타 문의사항은 이벤트 문의를 해주세요.');
					}
					if ( option.after )
					{
						option.after.bind()(req);
					}
				 }
			 });

		}, ChkWinner : function(option){
			$.ajax({
				type: 'POST', async: false,
				url: '_exec.php',
				data: {
					'mode' : 'CHKWINNER',
					'user_type' : window.user_type.user_type,
					'user_type2' : window.user_type.user_type2,
					'mobile' : (mobile?'mob':'web')
				},
				dataType:"json",
				success: function(req) {
					if (req.result=='9')
					{
						alert('정상적으로 이용해주세요.(브라우져를 1개 이상 열어서 참여가 불가능합니다.)\n지속적인 문제 발생시 다른 브라우져를 이용해주세요\n기타 문의사항은 이벤트 문의를 해주세요.');
					}
					else if (req.result=='x')
					{
						alert("과도한 참여로 인하여 차단 되었습니다.");
						location.reload();
					}
					else if (req.result=='end')
					{
						alert("이벤트가 종료되었습니다.");
					}
					else if (req.result=='lose_over_today')
					{
						alert("공유하기는 하루 10번 참여 가능하며, 매일 00시에 초기화 됩니다.");
					}
					else if (req.result=='deny')
					{
						alert("지금 참여하시는 아이피는 짧은시간에 너무 많은 응모내역이 있습니다.\n서버에 부하가되지 않는 한도 내에서 응모해주시기 바랍니다.");
					}
					else if (req.result=='lose')
					{
						if ( option.lose )
						{
							option.lose.bind()(req);
						}
					}
					else
					{
						option.gift.bind()(req);
					}
					if ( option.after )
					{
						//option.after.bind()();
					}
				}
			});

		}, Gift : function(option){
			$.ajax({
				type: 'POST', async: false,
				url: '_exec.php',
				data: {'mode' : 'JOIN_GIFTf', 'mobile' : (mobile?'mob':'web')
					, ssn: window.user_req.chance_info.ssn, chk: window.user_req.CHK
					, 'user_type': window.user_type.user_type, 'user_type2': window.user_type.user_type2 },
				dataType:"json",
				success: function(req) {
					if (req.result=='o')
					{
						$.ajax({
							type: 'POST', async: false,
							url: '_exec.php',
							data: {'mode' : 'JOIN_GIFT', 'mobile' : (mobile?'mob':'web')
								, ssn: window.user_req.chance_info.ssn, chk: window.user_req.CHK
								, 'user_type': window.user_type.user_type, 'user_type2': window.user_type.user_type2 },
							dataType:"json",
							success: function(req) {
								if (req.result=='o')
								{
									if ( option.succ )
									{
										option.succ.bind()(req);
									}
								}
								else if ( req.result=='limit3' ){
									alert('최대 당첨횟수를 초과했습니다.\n비정상적인 참여시 기존 당첨이 취소됩니다.');
								}
								else{
									alert('이용에 불편을드려 죄송합니다. 몇가지 오류로 인한 부분을 채크 부탁드립니다.\nCODE('+req.timestamp+')('+req.chk+')\n1. 브라우져를 1개 이상 열어서 참여가 불가능합니다.\n2. 쿠키생성 제한에 걸려 당첨이 누락되었을 수 있습니다.\n3. 지속적인 문제 발생시 다른 브라우져를 이용해주세요.\n4. 당첨 되었지만 해당 메세지가 나온다면 캡쳐 이미지를 이벤트 문의로 보내주세요.');
								}
							}
						});
					}
					else if ( req.result=='limit3' ){
						alert('최대 당첨횟수를 초과했습니다.\n비정상적인 참여시 기존 당첨이 취소됩니다.');
					}
					else{
						alert('이용에 불편을드려 죄송합니다. 몇가지 오류로 인한 부분을 채크 부탁드립니다.\nCODE('+req.timestamp+')('+req.chk+')\n1. 브라우져를 1개 이상 열어서 참여가 불가능합니다.\n2. 쿠키생성 제한에 걸려 당첨이 누락되었을 수 있습니다.\n3. 지속적인 문제 발생시 다른 브라우져를 이용해주세요.\n4. 당첨 되었지만 해당 메세지가 나온다면 캡쳐 이미지를 이벤트 문의로 보내주세요.');
					}
				}
			});


		}
	}; var E = new EVENT();


	
	
	
	
	var BOARD = function(){
		this.opt;
	};
	BOARD.prototype = {
		test : function(){

		}, BoardList : function(p, reload){
			var $win_detail= $(".win_detail");
			$win_detail= $win_detail.length>0?"gift":"salt"
			$.ajax({
				type: 'POST',
				url: '_exec.php',
				data: {
					'mode' : 'BOARD_LIST',
					'page' : (p||1),
					'reload' : (reload||''),
					'user_type': $win_detail
				},
				dataType:"json",
				success: function(req){
					var $list = $(".salt_detail tbody, .win_detail tbody");
					$list.empty();

					$(req.list).each(function(){
						var thiss= this;
						var html= '';
						if ($win_detail=='salt')
						{
							html+='<tr>';
							html+='	<td class="num">'+this.sortnum+'</td>';
							html+='	<td class="date">'+this.reg_date.substring(0,16)+'</td>';
							html+='	<td class="acc">'+ U.getAtList(['소진','적립'],['use','add'],this.chance_type) +'</td>';
							html+='	<td class="type">'+ this.chance_type2 +'</td>';
							html+='	<td class="salt">'+this.current+'</td>';
							html+='</tr>';
						}
						else{
							html+='<tr>';
							html+='	<td class="num">'+this.sortnum+'</td>';
							html+='	<td class="date">'+this.reg_date.substring(0,16)+'</td>';
							html+='	<td class="type">'+this.gamename+'</td>';
							html+='	<td class="gift">'+this.win_type_str+'</td>';
							html+='</tr>';
						}
						
						$list.append(html);
					});
					if (!req.list)
					{
						$list.append('<tr><td colspan=5>데이터가 없습니다.</td></tr>');
					}

					// paging
					$(".detail .numbering").empty();
					//$(".detail .numbering a").prop('href', 'javascript:;');
					$(".detail .numbering").append(req.paging.first);
					$(".detail .numbering").append(req.paging.pages);
					$(".detail .numbering").append(req.paging.last);
				}
			});

		}, BannerList : function(p, reload){
			$.ajax({
				type: 'POST',
				url: '_exec.php',
				data: {
					'mode' : 'BANNER_LIST',
					'page' : (p||1),
					'reload' : (reload||''),
				},
				dataType:"json",
				success: function(req){
					var $list = $("#banner .swiper-wrapper");
					$list.empty();

					$(req.list).each(function(){
						var html= '';
							html+='<div class="swiper-slide slide2"><a href="'+this.link+'"><img src="upfile/'+this.filename+'"></a></div>';
						
						$list.append(html);
					});
					if (!req.list)
					{
						$list.append('<tr><td colspan=5>데이터가 없습니다.</td></tr>');
					}

				}
			});

		}, BoardListCustomer : function(page){
			setCookie("page",page,0);

			$.ajax({
				type: 'POST',
				url: '_exec.php',
				data: {
					'mode' : 'BOARD_LIST_CUSTOMER',
					'page' : (page||1),
					'b_id': '008'
				},
				dataType:"json",
				success: function(req){
					var $list = $(".noticeList");
					$list.empty();

					$(req.list).each(function(){
						var html= '';
							html+='<div class="listBox">';
							html+='	<a href="cs_board_detail.html?idx='+this.idx+'&page='+page+'">';
							html+='	<p class="title">';
							html+='		<span>'+this.sortnum+'</span> '+this.title+'';
							html+='	</p>';
							html+='	<p class="replyNum">';
							html+='		'+this.re_count+'';
							html+='	</p>';
							html+='	<ul class="info">';
							html+='		<li class="name">';
							html+='			'+this.name+'<span>ㅣ</span>';
							html+='		</li>';
							html+='		<li class="view">';
							html+='			view <em>'+this.hit+'</em><span>ㅣ</span>';
							html+='		</li>';
							html+='		<li class="date">';
							html+='			'+this.reg_date.substr(0,16)+'';
							html+='		</li>';
							html+='	</ul> </a>';
							html+='</div>';
						
						$list.append(html);
					});
					if (!req.list)
					{
						$list.append('<tr><td colspan=5>데이터가 없습니다.</td></tr>');
					}

					// paging
					$(".detail .numbering").empty();
					//$(".detail .numbering a").prop('href', 'javascript:;');
					$(".detail .numbering").append(req.paging.first);
					$(".detail .numbering").append(req.paging.pages);
					$(".detail .numbering").append(req.paging.last);
				}
			});

		}, BoardListNotice : function(page){
			setCookie("page",page,0);

			$.ajax({
				type: 'POST',
				url: '_exec.php',
				data: {
					'mode' : 'BOARD_LIST_NOTICE',
					'page' : (page||1),
					'b_id': '007'
				},
				dataType:"json",
				success: function(req){
					var $list = $(".noticeList");
					$list.empty();

					$(req.list).each(function(){
						var html= '';
							html+='<div class="listBox">';
							html+='	<a href="cs_notice_detail.html?idx='+this.idx+'&page='+page+'">';
							html+='	<p class="title">';
							html+='		<span>'+this.sortnum+'</span> '+this.title+'';
							html+='	</p>';
							html+='	<p class="replyNum">';
							html+='		'+this.re_count+'';
							html+='	</p>';
							html+='	<ul class="info">';
							html+='		<li class="name">';
							html+='			'+this.name+'<span>ㅣ</span>';
							html+='		</li>';
							html+='		<li class="view">';
							html+='			view <em>'+this.hit+'</em><span>ㅣ</span>';
							html+='		</li>';
							html+='		<li class="date">';
							html+='			'+this.reg_date.substr(0,16)+'';
							html+='		</li>';
							html+='	</ul> </a>';
							html+='</div>';
						
						$list.append(html);
					});
					if (!req.list)
					{
						$list.append('<tr><td colspan=5>데이터가 없습니다.</td></tr>');
					}

					// paging
					$(".detail .numbering").empty();
					//$(".detail .numbering a").prop('href', 'javascript:;');
					$(".detail .numbering").append(req.paging.first);
					$(".detail .numbering").append(req.paging.pages);
					$(".detail .numbering").append(req.paging.last);
				}
			});
			
		}, BoardListRankInvite : function(page){
			setCookie("page",page,0);

			$.ajax({
				type: 'POST',
				url: '_exec.php',
				data: {
					'mode' : 'BOARD_LIST_INVITE',
					'page' : (page||1),
					'b_id': '008'
				},
				dataType:"json",
				success: function(req){
					var $list = $(".rankInviteList");
					$list.empty();
					console.log( req );

					$(req.list).each(function(){
						var html= '';
							html+='<tr>';
							html+='	<td class="num">'+this.rank+'</td>';
							html+='	<td class="id">'+"<img src='"+this.profile_image+"'>"+this.uname + '<span class="lev' + this.member_type + '"></span>' + '</td>';
							
							html+='	<td class="salt">'+parseInt(this.score).toLocaleString()+'</td>';
							html+='</tr>';
						
						$list.append(html);
					});
					if (!req.list)
					{
						$list.append('<tr><td colspan=5>데이터가 없습니다.</td></tr>');
					}

					// paging
					$(".detail .numbering").empty();
					//$(".detail .numbering a").prop('href', 'javascript:;');
					$(".detail .numbering").append(req.paging.first);
					$(".detail .numbering").append(req.paging.pages);
					$(".detail .numbering").append(req.paging.last);

					$(".update").html('UPDATE : '+req.update_date);
				}
			});
			
		}, BoardListRankWeek : function(page){
			setCookie("page",page,0);

			$.ajax({
				type: 'POST',
				url: '_exec.php',
				data: {
					'mode' : 'BOARD_LIST_WEEKRANK',
					'page' : (page||1),
					'b_id': '008'
				},
				dataType:"json",
				success: function(req){
					var $list = $(".rankWeekList");
					$list.empty();
					console.log( req );

					$(req.list).each(function(){
						var html= '';
							html+='<tr>';
							html+='	<td class="num">'+this.rank+'</td>';
							html+='	<td class="id">'+"<img src='"+this.profile_image+"'>"+this.uname + '<span class="lev' + this.member_type + '"></span>' + '</td>';
							
							html+='	<td class="salt">'+parseInt(this.score).toLocaleString()+'</td>';
							html+='</tr>';
						
						$list.append(html);
					});
					if (!req.list)
					{
						$list.append('<tr><td colspan=5>데이터가 없습니다.</td></tr>');
					}

					// paging
					$(".detail .numbering").empty();
					//$(".detail .numbering a").prop('href', 'javascript:;');
					$(".detail .numbering").append(req.paging.first);
					$(".detail .numbering").append(req.paging.pages);
					$(".detail .numbering").append(req.paging.last);

					$(".update").html('UPDATE : '+req.update_date);
				}
			});

		}, BoardListRankAccumulate : function(page){
			setCookie("page",page,0);

			$.ajax({
				type: 'POST',
				url: '_exec.php',
				data: {
					'mode' : 'BOARD_LIST_ACCUMULATE',
					'page' : (page||1),
					'b_id': '008'
				},
				dataType:"json",
				success: function(req){
					var $list = $(".rankAccumulateList");
					$list.empty();

					$(req.list).each(function(){
						var html= '';
							html+='<tr>';
							html+='	<td class="num">'+this.rank+'</td>';
							html+='	<td class="id">'+"<img src='"+this.profile_image+"'>"+this.uname + '<span class="lev' + this.member_type + '"></span>' + '</td>';
							html+='	<td class="salt">'+parseInt(this.score).toLocaleString()+'</td>';
							html+='</tr>';
						
						$list.append(html);
					});
					if (!req.list)
					{
						$list.append('<tr><td colspan=5>데이터가 없습니다.</td></tr>');
					}

					// paging
					$(".detail .numbering").empty();
					//$(".detail .numbering a").prop('href', 'javascript:;');
					$(".detail .numbering").append(req.paging.first);
					$(".detail .numbering").append(req.paging.pages);
					$(".detail .numbering").append(req.paging.last);

					$(".update").html('UPDATE : '+req.update_date);
				}
			});

		}, BoardListNextPrev : function(opt){
			$.ajax({
				type: 'POST',
				url: '_exec.php',
				data: {
					'mode' : 'BOARD_NEXT_PREV',
					'idx' : opt.idx,
					'direct' : opt.direct
				},
				dataType:"json",
				success: opt.proc
			});
		}

	}; var B = new BOARD();
	
	
	var reply = {e: { }, 
		regist: function () {
			var literal = {
				contents: { selector: $("#contents"), required: { message: "내용을 입력해주세요." } }
			};

			var checker = $.validate.rules(literal, { mode: "modal" });
			if (checker) {
				var fd = new FormData();
				fd.append('mode', 'INSERT_REPLY');
				fd.append('b_idx', b_idx);
				fd.append('reply', '');
				fd.append('contents', $("#contents").val());
				//{ "id": id, "contents": $("#contents").val() }
				$.ajax({
					type: "POST",
					contentType: "application/json; charset=utf-8",
					url: "_exec.php", ///api/notice/regist",
					data: fd,
					dataType:"json",
					contentType: "application/x-www-form-urlencoded; charset=UTF-8",
					processData: false, contentType: false,
					success: function (json) {
						console.log(json);
						if (json != null) {
							if (json.result === "o") {
								reply.load('clear');
								$("#contents").val('');
							}
							else if (json.result=='invalid_ssn')
							{
								E.AlertMsg('modal-alert', '알림', '로그인 후 이용 가능합니다.' );
							}
							else {
								E.AlertMsg('modal-alert', '알림', '오류가 발생했습니다.<br>새로고침 후 다시 시도해주세요~' );
								return false;
							}
						} else {
							E.AlertMsg('modal-alert', '알림', '오류가 발생했습니다.<br>새로고침 후 다시 시도해주세요~' );
							return false;
						}
					},
					error: function (jqxhr, status, error) {
						var err = "[" + jqxhr.status + "] " + jqxhr.statusText;
						E.AlertMsg('modal-alert', '알림', '오류가 발생했습니다.<br>새로고침 후 다시 시도해주세요~' );
					}
				});
			}
			return false;
		},
		like: function (id) {
			$.ajax({
				type: "POST",
				contentType: "application/json; charset=utf-8",
				url: "/api/notice/like",
				data: JSON.stringify({ "id": id }),
				dataType: "json", //async: false,
				success: function (json) {
					if (json != null) {
						if (json.result === "PLUS") {
							$("#like_" + id + " > a").addClass("on");
							$("#like_" + id + " > a > img").attr("src", "/resources/img/sub/board/likeIcon2.png");
						} else {
							$("#like_" + id + " > a").removeClass("on");
							$("#like_" + id + " > a > img").attr("src", "/resources/img/sub/board/unlike.png");
						}

						$("#like_" + id + " > div > span").text(json.count);
					} else {
						E.AlertMsg('modal-alert', '알림', '오류가 발생했습니다.<br>새로고침 후 다시 시도해주세요~' );
						return false;
					}
				},
				error: function (jqxhr, status, error) {
					var err = "[" + jqxhr.status + "] " + jqxhr.statusText;
					E.AlertMsg('modal-alert', '알림', '오류가 발생했습니다.<br>새로고침 후 다시 시도해주세요~' );
				}
			});
		},
		show: function (id) {
			var $area = $("#content_"+ id);
			console.log( $area.find('div.txt').html() );
			var html = "";
			html += '<div class="write_reply">';
			html += '    <textarea placeholder="수정내용">'+ $area.find("div.txt").html().trim().replace(/<br>/gi, "\n") +'</textarea>';
			html += '    <div class="btn_area rere">';
			html += '		<button class="btn_reply_del" ><a href="javascript:reply.hide('+ id +');">취소하기</a></button>';
			html += '		<button class="btn_reply_mod" ><a href="javascript:reply.modify('+ id +');">수정하기</a></button>';
			html += '    </div>';
			html += '</div>';

			$area.find("div.txt").hide();
			$area.find("div.iconList").hide();
			$area.find("div.view_area_info").hide();
			$area.find("div.view_area_info").after(html);

			return false;
		},
		hide: function (id) {
			var $area = $("#content_" + id);
			$area.find("div.txt").show();
			$area.find("div.iconList").show();
			$area.find("div.view_area_info").show();
			$area.find("div.view_area_info").next().remove();
		},
		modify: function (id) {
			var $area = $("#content_" + id);
			var $content = $area.find("div.write_reply > textarea");

			if ($content.val() === "") {
				//alert("내용을 입력해주세요.");
				E.AlertMsg('modal-alert', '알림', '내용을 입력해주세요.' );
				$content.focus();
				return false;
			}
			else {
				var fd = new FormData();
				fd.append('mode', 'MODIFY_REPLY');
				fd.append('b_idx', b_idx);
				fd.append('idx', id);
				fd.append('contents', $content.val());

				$.ajax({
					type: "POST",
					contentType: "application/json; charset=utf-8",
					url: "_exec.php", ///api/notice/",
					data: fd,
					dataType:"json",
					contentType: "application/x-www-form-urlencoded; charset=UTF-8",
					processData: false, contentType: false,
					success: function (json) {
						if (json != null) {
							if (json.result === "o") {
								$area.find("div.txt").html(json.contents).show();
								$area.find("div.iconList").show();
								$area.find("div.view_area_info").show();
								$area.find("div.view_area_info").next().remove();
							} else {
								E.AlertMsg('modal-alert', '알림', '오류가 발생했습니다.<br>새로고침 후 다시 시도해주세요~' );
								return false;
							}
						} else {
							E.AlertMsg('modal-alert', '알림', '오류가 발생했습니다.<br>새로고침 후 다시 시도해주세요~' );
							return false;
						}
					},
					error: function (jqxhr, status, error) {
						var err = "[" + jqxhr.status + "] " + jqxhr.statusText;
						E.AlertMsg('modal-alert', '알림', '오류가 발생했습니다.<br>새로고침 후 다시 시도해주세요~' );
					}
				});
			}
		},
		delete: function (id) {
			if (confirm("삭제하시겠습니까?")) {
				var fd = new FormData();
				fd.append('mode', 'DELETE_REPLY');
				fd.append('b_idx', b_idx);
				fd.append('idx', id);

				$.ajax({
					type: "POST",
					contentType: "application/json; charset=utf-8",
					url: "_exec.php", ///api/notice/",
					data: fd,
					dataType:"json",
					contentType: "application/x-www-form-urlencoded; charset=UTF-8",
					processData: false, contentType: false,
					success: function (json) {
						if (json != null) {
							if (json.result === "o") {
								reply.load('clear');
							} else {
								E.AlertMsg('modal-alert', '알림', '오류가 발생했습니다.<br>새로고침 후 다시 시도해주세요~' );
								return false;
							}
						} else {
							E.AlertMsg('modal-alert', '알림', '오류가 발생했습니다.<br>새로고침 후 다시 시도해주세요~' );
							return false;
						}
					},
					error: function (jqxhr, status, error) {
						var err = "[" + jqxhr.status + "] " + jqxhr.statusText;
						E.AlertMsg('modal-alert', '알림', '오류가 발생했습니다.<br>새로고침 후 다시 시도해주세요~' );
					}
				});
			}
		},
		answer: function (id) {
			var $area = $("#content_" + id);
			var length = $area.next("div.write_box").length;

			if (length === 0) {
				var html = "";
				$area.find("a.btn_comment_re").html("취소");
				html += '<div class="write_box" >';
				html += '    <textarea placeholder="답글을 작성해주세요."></textarea>';
				html += '    <div class="btn_area rere">';
				//	html += '		<div class="btn_wrap"><span class="btn btn_gray" ><a href="javascript:reply.answer_hide('+ id +');">취소하기</a></span></div>';
				html += '		<button class="btn_reply_del" ><a href="javascript:reply.add('+ id +');" >작성하기</a></button>';
				html += '	</div>';
				html += '</div>';
				$area.after(html);
			} else {
				$area.find("a.btn_comment_re").html("답글");
				$area.next("div.write_box").remove();
			}
		},
		add: function (id) {
			var $area = $("#content_" + id);
			var $content = $area.next("div.write_box").find("textarea");
			if ($content.val() === "") {
				//alert("내용을 입력해주세요.");
				E.AlertMsg('modal-alert', '알림', '내용을 입력해주세요.' );
				$content.focus();
				return false;
			}
			else {
				var fd = new FormData();
				fd.append('mode', 'INSERT_REPLY');
				fd.append('b_idx', b_idx);
				fd.append('idx', id);
				fd.append('reply', 'reply');
				fd.append('contents', $content.val());

				$.ajax({
					type: "POST",
					contentType: "application/json; charset=utf-8",
					url: "_exec.php", 
					data: fd,
					dataType:"json",
					contentType: "application/x-www-form-urlencoded; charset=UTF-8",
					processData: false, contentType: false,
					success: function (json) {
						console.log(json);
						if (json != null) {
							if (json.result === "o") {
								reply.load('clear');
							}
							else if (json.result=='invalid_ssn')
							{
								E.AlertMsg('modal-alert', '알림', '로그인 후 이용 가능합니다.<br>오른쪽 상단에 카카오 로그인후 이용해주세요~' );
							}
							else {
								E.AlertMsg('modal-alert', '알림', '오류가 발생했습니다.<br>새로고침 후 다시 시도해주세요~' );
								return false;
							}
						} else {
							E.AlertMsg('modal-alert', '알림', '오류가 발생했습니다.<br>새로고침 후 다시 시도해주세요~' );
							return false;
						}
					},
					error: function (jqxhr, status, error) {
						var err = "[" + jqxhr.status + "] " + jqxhr.statusText;
						E.AlertMsg('modal-alert', '알림', '오류가 발생했습니다.<br>새로고침 후 다시 시도해주세요~' );
					}
				});
			}
		},
		load: function(clear){
			var $list = $(".board_write_wrap .view_area")

			$.ajax({
				type: 'POST', 
				url: '_exec.php',
				data: { 'mode' : 'LIST_REPLY'
					, 'clear':clear||'clear'
					, 'b_idx': b_idx
						},
				dataType:"json",
				beforeSend : function (xhr) {

				},
				success: function(req) {

					if (clear=='clear')
					{
						$list.empty();
						//	$list.append('<span style="align:center;"><i>Loading...</i></span>');
					}
					$(req.list).each(function(id){
						var Thtml= '';

						Thtml +='<div class="content" id="content_'+this.idx+'">';
						Thtml +='	<div class="view_area_info">';
						Thtml +='		<span class="img">:profile</span>';
						//	Thtml +='		<span class="place">:uname</span>';
						Thtml +='		<span class="user">:uname</span>';
						Thtml +='		<div class="editBtnList">';
						Thtml +='			<a href="javascript:reply.answer('+this.idx+')" class="btn_comment_re">답글</a>';
						if(this.is_me=='Y'){
							Thtml +='			<a href="javascript:reply.show('+this.idx+')" class="btn_comment_mod">수정</a>';
							Thtml +='			<a href="javascript:reply.delete('+this.idx+')" class="btn_comment_del">삭제</a>';
						}
						Thtml +='		</div>';
						Thtml +='	</div>';
						Thtml +='	<div class="txt">:content</div>';
						Thtml +='	<div class="iconList">';
						Thtml +='		<div class="reply">';
						Thtml +='			<div class="number">댓글 <span>:reply_cnt</span></div>';
						Thtml +='		</div>';
						//Thtml +='		<div class="like">';
						//Thtml +='			<a href="#"><img src="img/sub/board/unlike.png" alt=""></a>';
						//Thtml +='			<div class="number">LIKE <span>56</span></div>';
						//Thtml +='		</div>';
						Thtml +='		<span class="date">:reg_dates</span>';
						Thtml +='	</div>';
						Thtml +='</div>';

						var html= Thtml;
							html= html.replace(":profile", '<img src="'+this.profile_image+'" onerror="this.src=\'/images/profile_default.jpg\'" alt="'+this.name+'">');
							html= html.replace(":uname", this.name||'jjansun');
							html= html.replace(":content", this.content);
							html= html.replace(":reply_cnt", this.reply_cnt);
							html= html.replace(":reg_dates", this.reg_date.substr(0,10) );
							html= html.replace(":idx", JSON.stringify({'idx':this.idx}));

						var html_cmtArr= new Array();
						$(this.comments).each(function(id){
							var Thtml_cmt= '';
								Thtml_cmt+= '<div class="content view_reply" id="content_'+this.idx+'">';
								Thtml_cmt+= '	<div class="view_area_info">';
								//	Thtml_cmt+= '		<span class="ico"><img src="img/sub/board/replyIcon3.png" alt=""></span>';
								Thtml_cmt+= '		<span class="ico"><img src="images/board/replyIcon3.png" alt=""></span>';
								//Thtml_cmt+= '		<span class="place">:uname_cmt</span>';
								Thtml_cmt+= '		<span class="user">:uname_cmt</span>';
								Thtml_cmt+= '		<div class="editBtnList">';
								if(this.is_loginok=='Y'){
								//	Thtml_cmt+= '			<a href="javascript:reply.answer('+this.idx+')" class="btn_comment_re">답글</a>';
								}
								if(this.is_me=='Y'){
									Thtml_cmt+= '			<a href="javascript:reply.show('+this.idx+')" class="btn_comment_mod">수정</a>';
									Thtml_cmt+= '			<a href="javascript:reply.delete('+this.idx+')" class="btn_comment_del">삭제</a>';
								}
								Thtml_cmt+= '		</div>';
								Thtml_cmt+= '	</div>';
								Thtml_cmt+= '	<div class="txt">:content_cmt</div>';
								Thtml_cmt+= '</div>';
							var html_cmt= Thtml_cmt;
								html_cmt= html_cmt.replace(":uname_cmt", this.name);
								html_cmt= html_cmt.replace(":content_cmt", this.content);
								html_cmt= html_cmt.replace(":reg_dates", this.reg_dates);
								html_cmt= html_cmt.replace(":idx", JSON.stringify({'idx':this.idx}));
								html_cmtArr.push(html_cmt);
						});
						//	html= html.replace(":html_cmt", html_cmtArr.join(''));

						$list.append(html);
						$list.append(html_cmtArr.join(''));
					});
				}
			});
		}
	};

	
	
	
	var UTIL = function(){
		this.opt;
	};
	UTIL.prototype = {
		test : function(){


		}, getAtList : function(data, arr2, value){
			var index= arr2.findIndex((e,i)=> e==value );
			return data[index];

		}, AlertMsg : function(id, title, msg){
			var $id= $("#"+id);
				$id.find(".main_txt").html(title);
				$id.find(".sub_txt").html(msg);
			$("#"+id).modal({escapeClose: false, clickClose: false});
			$(".blocker").css('z-index',501);

		}, is_over_byday : function(Y,M,D){
			var today= new Date(); 
			var endday= new Date();
			endday.setFullYear(Y,M-1,D);
			if( endday < today ){
				return true;
			}else{
				return false;
			}
	
		}, closeDaumPostcode : function (){
			$("#layer").hide();

		}, addressPopup : function(target){
			new daum.Postcode({
				oncomplete: function(data) {
					var fullAddr = data.address;
					var extraAddr = '';
					if(data.addressType === 'R'){
						if(data.bname !== ''){
							extraAddr += data.bname;
						}
						if(data.buildingName !== ''){
							extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
						}
						fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
					}
					
					$(target).find(" input[name=addr1]").val( "("+ data.zonecode + ") " + fullAddr ).focus();
					$(target).find(" input[name=addr2]").focus();
					$("#layer").hide();
				},
				width : '100%',
				height : '100%'
			}).embed( document.getElementById('layer') );

			$("#layer").show();

		}, createBlobData: function(item){
			var getCroppedCanvas = item.cropper('getCroppedCanvas'
				, {aspectRatio:"ignore", width:UTIL.opt.minCropBoxWidth, height:UTIL.opt.minCropBoxHeight});
			var imgData = getCroppedCanvas.toDataURL( "image/png" );
			var blobData = UTIL.processData(imgData);
			item.cropper('destroy');

			return blobData;

		}, chkWord: function(word) {
			var myregex = /씨발|씨발놈|씨발년|썅|썅놈|썅년|망할|망할놈|망할년|개새끼|미친놈|미친새끼|새끼|조까|좆까|ㅈ까|도둑놈들|염병|시발|병신|또라이|애자|개년|개놈|걸레|개또라이|지랄|제기랄|쌍판|육시럴|우라질|씹새끼|십새끼|존나|존나게|졸라|띠바|시바|개객기|돌아이|ㅂㅅ|ㅈㄲ|ㅈㄹ|씌벌|쒸이벌|ㄱㅅㄲ|ㅁㅊ|ㅅㄲ|젼나|졀라|씌벌럼|시바새끼|븅신|10새끼|섹스|빠구리|씹창|창년|창녀|창놈|스섹|색스|69|뒤치기|오르가즘|떡치기|떡치는거|SEX|쎅쓰|파워섹스|정상위|후배위|도둑놈들|일베|메갈|워마드|이명박|박근혜|홍준표|자한당|민주당|정치인|최순실|쥐명박|그네공주|순시리|순siri|문재인|노무현|대통령/gi;
			if( word.match(myregex) ){
				return false;
			}
			return true;

		}, numberFormater : function(num) {
			var regexp = /\B(?=(\d{3})+(?!\d))/g;
			return num.toString().replace(regexp, ',');
		}, processData : function(dataUrl) {
			var binaryString = window.atob(dataUrl.split(',')[1]);
			var arrayBuffer = new ArrayBuffer(binaryString.length);
			var intArray = new Uint8Array(arrayBuffer);
			for (var i = 0, j = binaryString.length; i < j; i++) {
				intArray[i] = binaryString.charCodeAt(i);
			}

			var data = [intArray],
				blob;

			try {
				blob = new Blob(data);
			} catch (e) {
				window.BlobBuilder = window.BlobBuilder ||
					window.WebKitBlobBuilder ||
					window.MozBlobBuilder ||
					window.MSBlobBuilder;
				if (e.name === 'TypeError' && window.BlobBuilder) {
					var builder = new BlobBuilder();
					builder.append(arrayBuffer);
					blob = builder.getBlob(imgType);
				} else {
					alert('브라우져의 버전이 낮습니다.');
				}
			}
			return blob;
		}
	}; var U = new UTIL();





	var SNS = function () {
		this.shareSns=false;
	};
	SNS.prototype = {
		ShareOk : function(e)
		{
			$.ajax({
				type: 'POST',
				url: '_exec.php',
				data: { 'mode' : 'SHARE_SNS', 'chk':E.chk, 'share_desc':e.type, 'mobile':(mobile?'mob':'web') },
				dataType:"json",
				success: function(req) {
					//closePopup();
				}
			});
		}
		, ShareFinish : function()
		{
			this.shareSns = false;
		}
		, Share : function(sns, url, txt)
		{
			var o;
			var _url = encodeURIComponent(url);
			var _txt = encodeURIComponent(txt);
			var _br  = encodeURIComponent('\r\n');
		 
			//	if (S.shareSns) return;
			switch(sns)
			{
				case 'facebook':
				//	window.open('about:blank','pop','width=500,height=700');
				//	setTimeout(function(){		S.ShareOk({'type':sns});				},3000);
					FB.ui({
					  method: 'share',
					  display: 'popup',
					  link: 'https://www.jjansun.com/share.php',
					  href: 'https://www.jjansun.com/share.php',
					  redirect_uri: 'https://www.jjsnaun.com/fb_callback.php',
					  picture : 'https://www.jjansun.com/images/thumb_facebook.jpg',
					  hashtag: '#DB생명 창립 32주년 기념 감사 이벤트'
					}, function(response){
						if (response && !response.error_message) {
							//공유성공
							S.ShareOk( { type:sns } );
						} else {
							
						}
					});
					break;

				case 'twitter':
					window.open('about:blank','pop','width=500,height=700');
					setTimeout(function(){		S.ShareOk({'type':sns});				},100);
					break;

				case 'story':
					window.open('about:blank','pop','width=500,height=700');
					setTimeout(function(){		S.ShareOk({'type':sns});				},100);
					break;

				case 'kakao':
					if (!mobile)
					{
						alert('모바일에서 이용해주세요.');
					}
					else {
						//	S.shareSns= true;
						//	$("#bottom .kakao").empty().html("<a data-snsname='kakao' href=\"javascript:;\" onclick=\"S.Share('kakao',''); _dbase_n_btEvent('b3');\" class=\"kakao1 btn\">카카오톡</a>");
						//	setTimeout(function(){
						//	U.bindKakao();
						//
						//	S.ShareFinish();
						//
						//	winCheck('kakao');
						//	}, 10000);

						setTimeout(function(){		S.ShareOk({'type':sns});				},3000);
					}

					break;
			}
		}
	}; var S = new SNS();



	function getCookie(name) {
		var Found = false
		var start, end
		var i = 0;
		while(i <= document.cookie.length) {
			 start = i
			 end = start + name.length
			 if(document.cookie.substring(start, end) == name) {
				 Found = true
				 break
			 }
			 i++
		}
		if(Found == true) {
			start = end + 1
			end = document.cookie.indexOf(";", start) 			// 마지막 부분이라는 것을 의미(마지막에는 ";"가 없다)
			if(end < start)
				end = document.cookie.length 			// name에 해당하는 value값을 추출하여 리턴한다.
			return document.cookie.substring(start, end)
		}
		return ""
	}

	function setCookie( name, value, expiredays ) {
		var todayDate = new Date();
		todayDate.setDate( todayDate.getDate() + expiredays );
		document.cookie = name + "=" + escape( value ) + "; path=/;"
	}

	function saltUp(num) {
		$('#side em.salt').dynamicNumber('go', num);
		setTimeout(() => {
			$('#side em.salt').attr("data-from", num);
		}, 1000);
	}