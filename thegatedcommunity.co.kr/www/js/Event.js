var UTIL = function(){
	this.youtubePlayer2;
};
UTIL.prototype = {
	test : function(start, complate){
		$("body > div").before('<a href="javascript:;" class="test">test</a>');
		$(".test").click(function(){
			$(start).click();
			setTimeout(function(){
				$(complate).click();
			}, 500);
		});

	}, is_over_byday : function(Y,M,D){
		var today= new Date(); 
		var endday= new Date();
		endday.setFullYear(Y,M-1,D);
		if( endday < today ){
			return true;
		}else{
			return false;
		}

	}, ni : function(obj, len, id){
		if (obj.value.length>=len)
		{
			$("#"+id+"").focus();
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


	}, YoutubePlayer : function(option){

		if (false){
			option.end.bind()();
		}
		else{
			this.youtubePlayer2 = new YoutubePlayer({
				el : 'player',
				videoId : option.url,
				width : option.width,
				height : option.height,
				playerVars : {
					'autoplay' : 1,
					'controls' : 0,
					'rel' : 0,
					'showinfo' : 0,
					'wmode' : 'opaque'
				},
				events : {
					ready : function(e) {
						if (option.ready)
						{
							option.ready.bind()();
						}
					}, end : function(e) {
						if (option.end)
						{
							option.end.bind()();
						}
					}, play : function(e) {
					}, pause : function(e) {
					}, butter : function(e) {
					}, cue : function(e) {
					}, destroy : function(e) {
					}
				}
			});
		}

	}, openPopup : function(id) {
		UTIL.closePopup();
		var popupid = id
		$('#fade').fadeIn();
		var popupleftmargin = ($('#' + popupid).width()) / 2;
		$('#' + popupid).css({'left' : $(window).width() / 2 - popupleftmargin});
		$('#' + popupid).show();
		TweenLite.to($('#' + popupid), 0, {css : {"top":800, alpha:0},delay:0,ease : Linear.easeNone});
		TweenLite.to($('#' + popupid), 0.5, {css : {"top":800 + 50, alpha:1},delay:0.1,ease : Linear.easeNone});
	
	}, closePopup : function() {
		$('#fade').fadeOut(300, function() {});
		$('.popup').fadeOut(500);
		return false
	}
}; var UTIL = new UTIL();


var SITE = function(){
};
SITE.prototype = {
	test : function(){
		
	}, REGIST : function(f){
		var pat = /^[ㄱ-ㅎ|가-힣|a-z|A-Z|\*]+$/;

		if ( f.business_name.value =='' ) {
			alert("업체명/단체명을 입력해주세요."); $(f.business_name).focus(); return;
		}
		if (/[^\s]/g.test( f.uname.value ) == 0) {
			alert("성함을 입력해주세요"); $(f.uname).focus(); return;
		}
		if ( !pat.test( f.uname.value ) ) {
			alert("성함은 한글, 영문자로 입력 가능합니다."); $(f.uname).focus(); return;
		}
		if ( f.stratum_name.value =='' ) {
			alert("직함을 입력해주세요."); $(f.stratum_name).focus(); return;
		}
		if ( f.department_name.value =='' ) {
			alert("부서명을 입력해주세요."); $(f.department_name).focus(); return;
		}
		if ( f.addr1.value=='' ) {
			alert("주소를 입력해주세요."); $(f.addr1).focus(); return;
		}
		if ( f.addr2.value=='' ) {
			alert("주소를 입력해주세요."); $(f.addr2).focus(); return;
		}

		if (f.pno1.value=='' || f.pno1.value.length<3)
		{
			alert("연락처를 입력해주세요."); f.pno1.focus(); return;
		}
		if (f.email.value=='' || f.email.value.length<3)
		{
			alert("이메일을 입력해주세요."); f.email.focus(); return;
		}

		if( f.userType.value==''){
			alert("상담신청자 유형을 선택해주세요."); f.userType.focus(); return;
		}
		if( f.selMonth.value==''){
			alert("상담 희망 시간을 선택해주세요."); f.selMonth.focus(); return;
		}
		if( f.selDay.value==''){
			alert("상담 희망 일자를 선택해주세요."); f.selDay.focus(); return;
		}
		if( f.selHour.value==''){
			alert("상담 희망 시간을 선택해주세요."); f.selHour.focus(); return;
		}

		var fd = new FormData(f);
		fd.append('mode', 'REGIST');
		fd.append('mobile', (mobile?'mobile':'web'));
		$.ajax({
			type: 'POST',
			url: '_exec.php',
			data: fd,
			dataType:"json", contentType: false, processData: false,
			success: function(req) {
				if(req.result=='regist'){
					alert("신청이 완료되었습니다.");
					f.reset();
				}
			}
		});
	}
}; var S = new SITE();

