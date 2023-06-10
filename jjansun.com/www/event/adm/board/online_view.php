<?
include("../inc/head.php");
include("./board_config.php");

$page = anti_injection($_GET['page']);
$search_key = anti_injection($_GET['search_key']);
$search_val = anti_injection($_GET['search_val']);

if($_GET['idx']){
	$pList=db_select("select * from tbl_online where idx='".$_GET['idx']."'");
	$content=$pList['content'];
}
?>
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
				<? include "../inc/left.php"; ?>
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
							<col width="35%">
							<col width="15%">
							<col width="*">
							</colgroup>
							<tr>
								<th>제목</th>
								<td colspan="3"><?=$pList['title']?></td>
							</tr>
							<tr>
								<th>이름</th>
								<td><?=$pList['name']?></td>
								<th>IP</th>
								<td><?=$pList['ip']?></td>
							</tr>
							<tr>
								<th>작성일</th>
								<td><?=date("Y-m-d H:i:s", $pList['regdate'])?></td>
								<th>조회수</th>
								<td><?=$pList['hit']?></td>
							</tr>
						<?
							$fileRs=db_query("select * from tbl_board_file where b_idx='".$_GET['idx']."' order by sortnum");
							while($fileList=db_fetch($fileRs)){
						?>
							<tr>
								<th>첨부파일</th>
								<td colspan="3" class="file_down"><a href="#" onclick="FileDown('bbs','<?=$fileList['filename']?>','<?=$fileList['orgname']?>');return false;"><?=$fileList['orgname']?></a> (<?=get_filesize($fileList['filesize'])?>)</td>
							</tr>
						<?
							}
						?>
							<tr>
								<th>내용</th>
								<td colspan="3" style="height:300px;vertical-align:top;">
									<?=nl2br($content)?>
								</td>
							</tr>
						</table>
					</div>

					<!-- Button -->
					<div id="btn_bott">
						<span class="button"><a href="board_list.php?b_id=<?=$b_id?>&page=<?=$page?>&search_key=<?=$search_key?>&search_val=<?=$search_val?>">목록</a></span>
						<span class="button black"><a href="#" onclick="send_re.submit();return false;">답글</a></span>
						<span class="button black"><a href="board_write.php?b_id=<?=$b_id?>&idx=<?=$_GET['idx']?>&mode=modify&page=<?=$page?>&search_key=<?=$search_key?>&search_val=<?=$search_val?>">수정</a></span>
						<span class="button blue"><a href="board_pro.php?b_id=<?=$b_id?>&mode=del&idx=<?=$_GET['idx']?>&page=<?=$page?>&search_key=<?=$search_key?>&search_val=<?=$search_val?>">삭제</a></span>
					</div>
					</form>
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
	<input name="page" type="hidden" id="page" value="<?=$_GET['page']?>" />
	<input name="search_key" type="hidden" id="search_key" value="<?=$_GET['search_key']?>" />
	<input name="search_val" type="hidden" id="search_val" value="<?=$_GET['search_val']?>" />
</form>

<!-- DEL -->
<form name="board_del" action="save.php" method="post">
	<input name="mode" type="hidden" id="num" value="del" />
	<input name="num" type="hidden" id="num" value="<?=$num?>" />
	<input name="b_id" type="hidden" id="b_id" value="<?=$b_id?>" />
	<input name="page" type="hidden" id="page" value="<?=$page?>" />
	<input name="search_key" type="hidden" id="search_key" value="<?=$_GET['search_key']?>" />
	<input name="search_val" type="hidden" id="search_val" value="<?=$_GET['search_val']?>" />
</form>

</body>
</html>