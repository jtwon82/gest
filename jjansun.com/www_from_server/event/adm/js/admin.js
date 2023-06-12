
	function updateData(url,mode){
		$(".dimmed").show();
		var sdate = $(".sdate").val();
		var edate = $(".edate").val();

			$.ajax({
				type: 'POST',
				url: url,
				data: {
					'mode' : mode,
					'sdate' : sdate,
					'edate' : edate,
				},
				dataType:"json",
				success: function(req) {
					window.location.reload();
				}
			}); 
		
	}

//기본 상태 변경
function state_set(table, idx, type, val){
	$.post("../inc/state_pro.php",{table:table, mode:"change", idx:idx, type:type, val:val}, function(data) {
		if(data=="succ"){
			alert("변경되었습니다.");
		}
	});
}


//팝업설정 변경
function popup_set(idx, type, val){
	$.post("popup_pro.php",{mode:"change", idx:idx, type:type, val:val}, function(data) {
		if(data=="succ"){
			alert("변경되었습니다.");
		}
	});
}

//상담처리상태 변경
function contract_set(idx, type, val){
	$.post("contract_pro.php",{mode:"change", idx:idx, type:type, val:val}, function(data) {
		if(data=="succ"){
			alert("변경되었습니다.");
		}
	});
}

//회원탈퇴
function member_del(idx){
	$.post("out_pro.php",{mode:"del", idx:idx}, function(data) {
		if(data=="succ"){
			alert("회원정보가 삭제되었습니다.");
		}
	});
}


//회원검색
function memSearch(){
	window.open("member_search.php","memberSearch","width=400px; height=400px; scrollbars=no");
}


//메일발송할 회원 입력
function inputEmail(obj){
	var content;
	if(opener.$('#to_email').val()==""){
		content=$(obj).parents().prev('.mailtxt').text();
	}else{
		content=opener.$('#to_email').val()+';'+$(obj).parents().prev('.mailtxt').text();
	}
	opener.$('#to_email').val(content)
}


//우편번호 결과 값 폼에 입력
function insertAddress(zipcode,zipaddress){

	opener.document.member.zip.value=zipcode;
	opener.document.member.addr1.value=zipaddress;
	opener.document.member.addr2.focus();

	self.close();
}

//회원정보 팝업보기
function memberPopup(idx){
	window.open('../member/member_pop_info.php?idx='+idx,'memberinfo','width=830px; height=700px; scrollbars=yes');
	return false;
}