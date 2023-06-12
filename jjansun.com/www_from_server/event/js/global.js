
/* ┏━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┓
	┃ 개발자 정의 함수																																									 ┃
	┃ 업데이트:2012.01.10																																								 ┃
	┗━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┛*/

	//폼 필수 입력사항 체크
	function formChk(obj){
		 var res=true;
		$(obj).find(".req:visible").each(function(){
			if($(this).val().replace(/\s+/g,"").length==0){
				alert("필수입력사항("+$(this).attr("title")+")을 모두 입력해 주세요.");
				$(this).focus();
				res=false;
				return false;
			}
		});
		return res;
	}

	//개별 폼 체크
	function inputChk(obj, msg){
		if (obj.value.replace(/\s+/g,'').length == 0){
			alert(msg);
			obj.focus();
			return false;
		}
		return true;
	}


	//개별 폼 체크(경고창X)
	function inputChk2(obj, msg){
		if (obj.value.replace(/\s+/g,'').length == 0){
			$(obj).parent().prev().children(".warning").text(msg).css("height","15px");
			obj.focus();
			return false;
		}
		return true;
	}

	//폼 필수 입력사항 체크
	$(function(){
		$(".nec").keyup(function(){
			if($(this).val()!=""){
				$(this).parent().prev().children(".warning").text("").css("height","0px");
			}
		});
	});

	//라디오버튼 폼 체크 (폼네임,출력 메시지)
	function radioChk(formname, msg){
		if($("input[name='"+formname+"']:checked").length<1){
			alert(msg);
			return false;
		}
		return true;
	}

	//숫자만 입력
	function onlyNumber(el){
		if((event.keyCode<48 && event.keyCode!==13)||(event.keyCode>57)){
			event.returnValue=false;
			alert('숫자만 입력 가능합니다.');
		}
	}

	//이메일 선택
	$(function(){
		$(".selMail").change(function(){
			if($(this).val()==""){
				$(this).prev().val("");
				$(this).prev().attr("readonly",false);
				$(this).prev().focus();
			}else{
				$(this).prev().val($(this).val());
				$(this).prev().attr("readonly",true);
			}
		});
	});

	//체크박스 전체 선택
	function check_all(obj, val){
		if($(obj).is(":checked")==true){
			$("input:checkbox[name='"+val+"']").each(function(){
				this.checked=true;
			});
		}else{
			$("input:checkbox[name='"+val+"']").each(function(){
				this.checked=false;
			});
		}
	}

	//체크박스 일괄 삭제
	function check_del(form,el){
		if($("input:checkbox[name='"+el+"']:checked").length<1){
			alert("최소 하나 이상의 글을 선택해 주세요."); return;}
		else{
			if(confirm("선택하신 글을 모두 삭제하시겠습니까?")){
				form.mode.value="check_del";
				form.submit();
			}
		}
	}

	// 정보 삭제시 삭제 여부 확인
	function really(){
		if (confirm("정말로 삭제하시겠습니까?")) return true;
		return false;
	}

	// 메세지 출력 후 실행
	function really_msg(msg){
		if (confirm(msg)) return true;
		return false;
	}

	//첨부파일 다운로드(파일경로,파일명)
	function FileDown(Path, File, Org){
		x=screen.availWidth/2-150
		y=screen.availHeight/2-100
		window.open("/event/common/filedown.php?Path="+Path+"&File="+File+"&Org="+Org,'', 'Left=' + x + ',Top=' + y + ',Width=0, Height=0,menubar=no,directories=no,resizable=no,status=no,scrollbars=no');
	}

	//커서자동이동
	function AutoMove(obj,next,cnt){
		if($(obj).val().length==cnt) document.getElementById(next).focus();
	}

	//콤마 붙이기
	function setComma(str){
		str = ""+str+"";
		var retValue = "";

		for(i=0; i<str.length; i++){
			if(i > 0 && (i%3)==0) {
				retValue = str.charAt(str.length - i -1) + "," + retValue;
			}else {
				retValue = str.charAt(str.length - i -1) + retValue;
			}
		}
		return retValue;
	}

	//팝업관련
	function getCookie(name) {
		var Found = false
		var start, end
		var i = 0

		// cookie 문자열 전체를 검색
		while(i <= document.cookie.length) {
			 start = i
			 end = start + name.length
			 // name과 동일한 문자가 있다면
			 if(document.cookie.substring(start, end) == name) {
				 Found = true
				 break
			 }
			 i++
		}

		// name 문자열을 cookie에서 찾았다면
		if(Found == true) {
			start = end + 1
			end = document.cookie.indexOf(";", start) 			// 마지막 부분이라는 것을 의미(마지막에는 ";"가 없다)
			if(end < start)
				end = document.cookie.length 			// name에 해당하는 value값을 추출하여 리턴한다.
			return document.cookie.substring(start, end)
		}
		// 찾지 못했다면
		return ""
	}

	function setCookie( name, value, expiredays ) {
		var todayDate = new Date();
		todayDate.setDate( todayDate.getDate() + expiredays );
		document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";"
	}

	//마이페이지 메뉴 열기
	$(function(){
		$("#mypageBtn").click(function(){
			if($("#mypageLayer").is(":visible")){
				$("#mypageLayer").slideUp(300);
			}else{
				$("#mypageLayer").slideDown(300);
			}
		})
	});

	//카테고리닫기
	$(function(){
		$("body").on("click",function(){
			if($("#mypageLayer").css("height")=="212px"){
				setTimeout('$("#mypageLayer").slideUp(300);','200');
			}
			if($("#categoryLayer").css("height")=="150px"){
				setTimeout('$("#categoryLayer").slideUp(300);','200');
			}
		});
	});

	//컨텐츠 삭제제
	function conDel(idx){
		if(confirm("정말 삭제하시겠습니까?")){
			location.href="../mypage/content_pro.php?idx="+idx+"&mode=del";
		}
		return false;
	}

	//다른글 보기 슬라이드
	$(function(){
		$("#right p>a").toggle(function(){
			$("#right").animate({'width':'230px'},{complete:function(){
				$("#right p>a>img").attr("src","../images/mypage/right_close.png");
				$(".otherTitle, .otherContents").show();
			}});
			},function(){
				$("#right").animate({'width':'0px'});
				$("#right p>a>img").attr("src","../images/mypage/right_open.png");
				$(".otherTitle, .otherContents").hide();
			}
		);
	});