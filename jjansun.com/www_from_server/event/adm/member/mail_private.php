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
	if($tx('from_name').value == ""){
		alert('보내는 사람 이름을 입력하세요');
		$tx('from_name').focus();
		return false;
	}

	if($tx('from_email').value == ""){
		alert('보내는 이메일을 입력하세요');
		$tx('from_name').focus();
		return false;
	}

	if($tx('to_email').value == ""){
		alert('받는 사람을 입력하세요');
		$tx('to_email').focus();
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
					<form name="frmWrite" id="frmWrite" method="post" action="mail_pro.php" onsubmit="return formChk(this);">
					<input type="hidden" name="mode" value="1" />
					<div class="tableSearch">
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
								<th>받는사람</th>
								<td colspan="3" style="line-height:30px;height:55px;">
									<input type="text" name="to_email" id="to_email" class="dInput" style="width:80%;" value="" /> <span class="button red small"><input type="button" value="회원검색" onclick="memSearch();" /></span><br />
									<span class="help" style="line-height:25px;margin:0;">받는사람이 복수일 경우 구분자는 ';' 을 입력하세요.</span>
								</td>
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
						<span class="button black"><a href="mail_list.php">목록</a></span>
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