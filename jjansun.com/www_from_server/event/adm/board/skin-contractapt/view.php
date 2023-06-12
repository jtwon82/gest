<script type="text/javascript">
function validForm(editor) {
	if($tx('title').value == ""){
		alert('제목을 입력하세요');
		$tx('title').focus();
		return false;
	}

	if($tx('name').value == ""){
		alert('이름을 입력하세요');
		$tx('name').focus();
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
<body onload="">
<div id="adm_wrap">
	<!-- content -->
	<table style="width:100%;">
		<tr>
			<td id="left_area">
				<!-- 좌측메뉴 -->
				<? include "../inc/left_{$_SESSION[LOGIN_ID]}.php"; ?>
			</td>
			<td id="content_area">
				<h1><?=$bbs_name?></h1>
				<!-- 본문 -->
				<div class="content">

					<!-- 상세페이지 -->
					<form name="frmWrite" id="frmWrite" method="post" action="board_pro.php">
					<input type="hidden" name="mode" value="<?=($_GET['idx'] ? "modify" : "write")?>" />
					<input type="hidden" name="idx" value="<?=$_GET['idx']?>" />
					<input type="hidden" name="b_id" value="<?=$b_id?>" />
					<div class="tableStyle2">
						<table>
							<colgroup>
							<col width="15%">
							<col width="*">
							</colgroup>
							<tr>
								<th>제목</th>
								<td ><?=$pList['title']?></td>
							</tr>
							<tr>
								<th>IP</th>
								<td><?=$pList['ip']?></td>
							</tr>
							<tr>
								<th>작성일</th>
								<td><?=$pList['reg_date']?></td>
							</tr>
							<tr>
								<th>내용</th>
								<td style="height:50px;vertical-align:top;">
									<?=nl2br($content)?>
								</td>
							</tr>
							<tr>
								<th>etc1</th>
								<td><?=$pList['etc1']?></td>
							</tr>
						</table>
					</div>

					<!-- Button -->
					<div id="btn_bott">
						<span class="button"><a href="board_list.php?b_id=<?=$b_id?>&b_code=<?=$b_code?>&page=<?=$page?>&search_key=<?=$search_key?>&search_val=<?=$search_val?>">목록</a></span>
					<?
						if($bbs_reply=="Y"){
					?>
						<span class="button black"><a href="#" onclick="send_re.submit();return false;">답글</a></span>
					<?
						}
					?>
						<span class="button black"><a href="board_write.php?b_id=<?=$b_id?>&idx=<?=$_GET['idx']?>&b_code=<?=$b_code?>&mode=modify&page=<?=$page?>&search_key=<?=$search_key?>&search_val=<?=$search_val?>">수정</a></span>
						<span class="button blue"><a href="board_pro.php?b_id=<?=$b_id?>&mode=del&idx=<?=$_GET['idx']?>&b_code=<?=$b_code?>&page=<?=$page?>&search_key=<?=$search_key?>&search_val=<?=$search_val?>" onclick="return really()">삭제</a></span>
					</div>
					</form>

					<?
						if($bbs_comment=="Y"){ //코멘트 기능 사용여부
							include("../board/comments.php");
						}
					?>

					<br>

					<?
						if($_GET['idx']!=''){
					?>
					<iframe src="board_list.php?b_id=<?=$pList['etc1']?>" width="100%" height="600"></iframe>
					<?
						}
					?>

				</div>
				<!-- end -->
			</td>
		</tr>
	</table>
</div>

<!-- REPLY -->
<form name="send_re" action="board_write.php" method="post">
	<input name="mode" type="hidden" id="mode" value="reply" />
	<input name="idx" type="hidden" id="idx" value="<?=$_GET['idx']?>" />
	<input name="b_id" type="hidden" id="b_id" value="<?=$b_id?>" />
	<input name="b_code" type="hidden" id="b_code" value="<?=$b_code?>" />
	<input name="page" type="hidden" id="page" value="<?=$_GET['page']?>" />
	<input name="search_key" type="hidden" id="search_key" value="<?=$_GET['search_key']?>" />
	<input name="search_val" type="hidden" id="search_val" value="<?=$_GET['search_val']?>" />
</form>

<!-- DEL -->
<form name="board_del" action="save.php" method="post">
	<input name="mode" type="hidden" id="num" value="del" />
	<input name="num" type="hidden" id="num" value="<?=$num?>" />
	<input name="b_id" type="hidden" id="b_id" value="<?=$b_id?>" />
	<input name="b_code" type="hidden" id="b_code" value="<?=$b_code?>" />
	<input name="page" type="hidden" id="page" value="<?=$page?>" />
	<input name="search_key" type="hidden" id="search_key" value="<?=$_GET['search_key']?>" />
	<input name="search_val" type="hidden" id="search_val" value="<?=$_GET['search_val']?>" />
</form>

</body>
</html>