<?
	include("../inc/head.php");

	if($_GET['idx']){
		$pList=db_select("select * from tbl_online o where o.idx='".$_GET['idx']."'");
	}
?>
<script type="text/javascript">
<!--
	var url_pat = /^((?:https?:)?\/\/)?((?:www|m)\.)?((?:youtube\.com|youtu.be))(\/(?:[\w\-]+\?v=|embed\/|v\/)?)([\w\-]+)(\S+)?$/;
	function getMatchCode(url){
		var regExp = /^.*(youtu\.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=|\&video_id=)([^#\&\?]*).*/;
		var match = url.match(regExp);
		if (match && match[2].length == 11) {
			return match[2];
		} else {
			return '';
		}
	}
	function chkYoutubUrl(f){
		if (!url_pat.test(f.url.value)){
			alert("유투브 URL을 입력해주세요1"); return;
		}
		else{
			var code = getMatchCode(f.url.value);
			console.log(code);
			if (code.length!=11){
				alert("유투브 URL을 입력해주세요2");
			}
			else{
				f.youtube_code.value = code;
			}
		}
	}
//-->
</script>
<body>
<div id="adm_wrap">
	<!-- content -->
	<table style="width:100%;">
		<tr>
			<td id="left_area">
				<!-- 좌측메뉴 -->
				<? include "../inc/left.php"; ?>
			</td>
			<td id="content_area">
				<h1>Project 등록</h1>
				<!-- 본문 -->
				<div class="content">

					<!-- 상세페이지 -->
					<form name="contract" id="contract" method="post" enctype="multipart/form-data" action="contract_pro.php" onsubmit="return formChk(this);">
					<input type="hidden" name="mode" value="<?=($_GET['idx'] ? "modify" : "write")?>" />
					<input type="hidden" name="idx" value="<?=$_GET['idx']?>" />
					<div class="tableStyle2">
						<table>
							<colgroup>
							<col width="15%">
							<col width="35%">
							<col width="15%">
							<col width="*">
							</colgroup>
							<tr>
								<th>영상제목</th>
								<td><input type="type" name="title" class="dInput" style="width:95%;" value="<?=$pList['title']?>" /></td>
								<th>이름</th>
								<td><input type="type" name="uname" class="dInput" style="width:95%;" value="<?=$pList['uname']?>" /></td>
							</tr>
							<tr>
								<th>전화번호</th>
								<td><input type="type" name="pno1" class="dInput" style="width:95%" value="<?=$pList['pno1']?>" /></td>
								<th>이메일주소</th>
								<td><input type="type" name="email" class="dInput" style="width:95%;" value="<?=$pList['email']?>" /></td>
							</tr>
							<tr>
								<th>상태(동영상주소)</th>
								<td><select name="state"><option value="">-- 선택 --</option><option value="1">신청</option><option value="2">대기</option></select>
									(<input type="type" name="url" class="dInput" style="width:73%;" value="<?=$pList['url']?>" onkeyUp="chkYoutubUrl(this.form);"/>)</td>
								<script type="text/javascript">document.contract.state.value="<?=$pList[state]?>";</script>
								<th>유투브 코드<br>(자동추출됩니다)</th>
								<td><input type="type" name="youtube_code" class="dInput" readOnly style="width:95%;" value="<?=$pList['youtube_code']?>" /></td>
							</tr>
							<tr>
								<th>내용</th>
								<td colspan="3"><textarea name="content" cols="" rows="" style="width:95%; height:200px;" class="dInput"><?=$pList['content']?></textarea></td>
							</tr>
						</table>
					</div>

					<!-- Button -->
					<div id="btn_bott">
						<span class="button blue"><input type="submit" value="저장" /></span>
						<span class="button black"><a href="contract_list.php?page=<?=$page?>&skey=<?=$skey?>&sval=<?=$sval?>">목록</a></span>
					</div>
					</form>

				</div>
				<!-- end -->
			</td>
		</tr>
	</table>
</div>
</body>
</html>