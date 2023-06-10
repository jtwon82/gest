<?
	include("../inc/head.php");
?>
<script type="text/javascript">
function validForm(editor) {
	if($tx('from_name').value == ""){
		alert('보내는 사람 이름을 입력하세요');
		$tx('from_name').focus();
		return false;
	}

	if($tx('from_email').value == ""){
		alert('보내는 이메일을 입력하세요');
		$tx('from_email').focus();
		return false;
	}

	if($tx('title').value == ""){
		alert('제목을 입력하세요');
		$tx('title').focus();
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
					<form name="frmWrite" id="frmWrite" method="post" action="mail_pro.php">
					<input type="hidden" name="mode" value="2" />
					<div class="tableSearch">
						<h2>♦ 수신그룹 지정 ♦</h2>
						<table>
							<tr>
								<th width="15%">메일수신동의</th>
								<td>
									<input type="radio" name="email_yn" id="em1" value="" checked /> <label for="em1" style="color:#C01258">전체회원</label>&nbsp;
									<input type="radio" name="email_yn" id="em2" value="Y" /> <label for="em2">수신동의회원</label>
								</td>
							</tr>
							<tr>
								<th width="15%">회원등급</th>
								<td>
									<input type="checkbox" name="member_level[]" value="0" checked /> <label style="color:#C01258" />전체회원</label>&nbsp;
									<?
										$mRs=db_query("select * from tbl_member_level where level_code >= 300 order by idx");
										while($mList=db_fetch($mRs)){
											$sel=($mList['level_code']==0 ? "checked" : "");
											echo "<input type=\"checkbox\" name=\"member_level[]\" value=\"".$mList['level_code']."\" $sel> ".($mList['level_code']==0 ? "<label style=\"color:#C01258\" />전체회원</label>" : $mList['level_name'])."&nbsp;&nbsp;";
										}
									?>
								</td>
							</tr>
							<tr>
								<th>성별</th>
								<td>
									<input type="radio" name="gender" id="gen0" value="" checked /> <label for="gen1" style="color:#C01258">전체회원</label>&nbsp;
									<input type="radio" name="gender" id="gen1" value="1" /> <label for="gen1">남성</label>&nbsp;
									<input type="radio" name="gender" id="gen2" value="2" /> <label for="gen2">여성</label>
								</td>
							</tr>
							<tr>
								<th>가입일자</th>
								<td><input type="text" name="sdate" id="sdate" class="dInput req cal" title="검색시작일" value="<?=$sdate?>" style="width:60px;" /> <img src="../images/icon_calendar.gif" style="vertical-align:middle" class="cbtn" /> ~ <input type="text" name="edate" id="edate" class="dInput req cal" title="검색종료일" value="<?=$edate?>" style="width:60px;" /> <img src="../images/icon_calendar.gif" style="vertical-align:middle" class="cbtn" /></td>

<SCRIPT LANGUAGE="JavaScript">
<!--
	$("#sdate").datepicker({dateFormat:'yy-mm-dd'});
	$("#edate").datepicker({dateFormat:'yy-mm-dd'});
//-->
</SCRIPT>							</tr>
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
								<td><input type="text" name="from_name" id="from_name" class="dInput" style="width:50%;" value="<?=$SITE_INFO['manager']?>" /></td>
								<th>보내는사람 이메일</th>
								<td><input type="text" name="from_email" id="from_email" class="dInput" style="width:50%;" value="<?=$SITE_INFO['email']?>" /></td>
							</tr>
							<tr>
								<th>제목</th>
								<td colspan="3"><input type="text" name="title" id="title" class="dInput" style="width:90%;" /></td>
							</tr>
							<tr>
								<td colspan="4" style="padding:10px;">
									<? include "../../editor/editor.html"; ?>
								</td>
							</tr>
						</table>
					</div>

					<p style="border:1px solid #cdcdcd; padding:10px 0;margin:10px 0 20px 0;background:#f7f7f7;"><span class="help">메일 발송시 각 포탈업체의 스팸메일 정책에 의해 발송된 메일이 전달되지 않을 수 있습니다.</span></p>

					<!-- Button -->
					<div id="btn_bott">
						<span class="button blue"><input type="button" value="메일발송" onclick="fnCheckInput();" /></span>
						<span class="button black"><a href="member_list.php?page=<?=$page?>">목록</a></span>
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