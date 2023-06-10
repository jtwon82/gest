<?
	include("../inc/head.php");

	if($_GET['idx']){
		$pList=db_select("select * from tbl_member where idx='".$_GET['idx']."'");
		list($jumin1, $jumin2)=explode("-",$pList['jumin']);
		list($email1, $email2)=explode("@",$pList['email']);
		list($tel1, $tel2, $tel3)=explode("-",$pList['tel']);
		list($hp1, $hp2, $hp3)=explode("-",$pList['hp']);
		list($birthy, $birthm, $birthd)=explode("-",$pList['birth']);
	}
?>
<script type="text/javascript">
function validForm(editor) {
	if($tx('pwidth').value == ""){
		alert('팝업창크기를 입력하세요');
		$tx('pwidth').focus();
		return false;
	}

	if($tx('pheight').value == ""){
		alert('팝업창크기를 입력하세요');
		$tx('pheight').focus();
		return false;
	}

	/* 본문 내용이 입력되었는지 검사하는 부분 */
	var _validator = new Trex.Validator();
	var _content = editor.getContent();
	if(!_validator.exists(_content)) {
		alert('내용을 입력하세요');
		return false;
	}

	return true;
}
</script>
<body>
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
				<h1>메일관리</h1>
				<!-- 본문 -->
				<div class="content">
					<form name="frmWrite" id="frmWrite" method="post" action="member_pro.php" onsubmit="return formChk(this);">
					<input type="hidden" name="mode" value="<?=($_GET['idx'] ? "modify" : "write")?>" />
					<input type="hidden" name="idx" value="<?=$_GET['idx']?>" />

					<div class="tableSearch">
						<h2>♦ 수신그룹 지정 ♦</h2>
						<table>
							<tr>
								<th width="15%">메일수신동의</th>
								<td>
									<input type="radio" name="email_yn" id="em1" value="1" /> <label for="em1">전체회원</label>&nbsp;
									<input type="radio" name="email_yn" id="em2" value="2" /> <label for="em2">수신동의회원</label>
								</td>
							</tr>
							<tr>
								<th width="15%">회원등급</th>
								<td>
									<?
										$mRs=db_query("select * from tbl_member_level order by idx");
										while($mList=db_fetch($mRs)){
											$sel=($mList['level_code']==0 ? "checked" : "");
											echo "<input type=\"checkbox\" name=\"auth_view[]\" value=\"".$mList['level_code']."\" $sel> ".($mList['level_code']==0 ? "<label style=\"color:#C01258\" />모든회원</label>" : $mList['level_name'])."&nbsp;&nbsp;";
										}
									?>
								</td>
							</tr>
							<tr>
								<th>성별</th>
								<td><input type="radio" name="gender" id="gen1" value="1" /> <label for="gen1">남성</label> <input type="radio" name="gender" id="gen2" value="2" /> <label for="gen2">여성</label></td>
							</tr>
							<tr>
								<th>가입일자</th>
								<td><input type="text" name="sdate" id="sdate" class="dInput req cal" title="검색시작일" value="<?=$sdate?>" style="width:60px;" /> <img src="../images/icon_calendar.gif" style="vertical-align:middle" class="cbtn" /> ~ <input type="text" name="edate" id="edate" class="dInput req cal" title="검색종료일" value="<?=$edate?>" style="width:60px;" /> <img src="../images/icon_calendar.gif" style="vertical-align:middle" class="cbtn" /></td>
							</tr>
						</table>
					</div>

					<div class="tableSearch">
						<h2>♦ 발송정보 입력 ♦</h2>
						<table>
							<colgroup>
							<col width="15%">
							<col width="35%">
							<col width="15%">
							<col width="35%">
							</colgroup>
							<tr>
								<th>보내는사람 이름</th>
								<td><input type="text" name="name" class="dInput" value="<?=$pList['name']?>" /></td>
								<th>보내는사람 이메일</th>
								<td class="font_bold"><input type="text" name="name" class="dInput" value="<?=$pList['name']?>" /></td>
							</tr>
							<tr>
								<th>제목</th>
								<td colspan="3"><input type="text" name="jumin1" class="dInput" style="width:80%;" value="<?=$jumin1?>" maxlength="6" /></td>
							</tr>
							<tr>
								<td colspan="4" style="padding:10px;">
									<? include "../../editor/editor.html"; ?>
								</td>
							</tr>
						</table>
					</div>

					<!-- Button -->
					<div id="btn_bott">
						<span class="button blue"><input type="button" value="저장" onclick="fnCheckInput();" /></span>
						<span class="button black"><a href="member_list.php?page=<?=$page?>&skey=<?=$skey?>&sval=<?=$sval?>">목록</a></span>
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