<?
	include("../inc/head.php");

	if($_GET['idx']){
		$pList=db_select("select * from tbl_banner where idx='".$_GET['idx']."'");
		$content=$pList['content'];
	}
?>

<!-- <script type="text/javascript" src="../../ckeditor/ckeditor.js"></script> -->
<script type="text/javascript">
//$(function(){
//	CKEDITOR.replace( 'content', {
//		filebrowserUploadUrl: "http://<?=$_SERVER['HTTP_HOST']?>/ckeditor/samples/upload.php",
//		height:'350px'
//	});
//});
</script>

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
<div id="adm_wrap">
	<!-- content -->
	<table style="width:100%;">
		<tr>
			<td id="left_area">
				<!-- 좌측메뉴 -->
				<? include "../inc/left.php"; ?>
			</td>
			<td id="content_area">
				<h1>배너관리</h1>
				<!-- 본문 -->
				<div class="content">

					<!-- 상세페이지 -->
					<form name="frmWrite" id="frmWrite" method="post" enctype="multipart/form-data" action="banner_pro.php" onsubmit="return formChk(this);">
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
<!-- 							<tr> -->
<!-- 								<th>노출여부</th> -->
<!-- 								<td colspan="3"> -->
<!-- 									<input type="radio" name="open" value="Y" checked="checked"<?=($pList['open']=="Y" ? " checked":"")?>/> 노출 &nbsp; <input type="radio" name="open" value="N"<?=($pList['open']=="N" ? " checked":"")?>/> 중단</td> -->
<!-- 							</tr> -->
							<tr>
								<th>제목</th>
								<td colspan="3"><input title="제목" name="title" type="text" class="dInput" style="width: 50%;" value="<?=$pList['title']?>" /></td>
							</tr>
							<tr>
								<th>area</th>
								<td colspan="3"><input title="area" name="area" type="text" class="dInput" style="width: 50%;" value="<?=$pList['area']?>" /></td>
							</tr>
						<?
							if(true){
								for($i=0; $i<1; $i++){
						?>
							<tr>
								<th>첨부파일</th>
								<td><input class="dInput " name="filename[]" type="file" id="filename<?=$i+1?>" style="width: 98%; height: 19px;" />
								<?
									$ff=db_select("select * from tbl_board_file where b_idx='".$_GET['idx']."' and sortnum='".($i+1)."'");
									if($ff['filename']){
										echo "<span style=\"padding:3px 0;display:inline-block;color:#333;\">".$ff['orgname']."</span>&nbsp;&nbsp;<input type=\"checkbox\" name=\"filedel[]\" id=\"dfile$i\" class=\"radio\" value=\"".($i+1)."\" /> <label for=\"dfile$i\">삭제</label>";
									}
								?>
								</td>
							</tr>
						<?
								}
							}
						?>
							<tr>
								<th>link</th>
								<td colspan="3"><input title="link" name="link" type="text" class="dInput" style="width: 50%;" value="<?=$pList['link']?>" /></td>
							</tr>
							<tr>
								<th>내용</th>
								<td colspan="3"><textarea name="content" id="content" class="dInput" title="내용" style="width:100%;height:100px;"><?=$pList['content']?></textarea></td>
							</tr>
						</table>
					</div>

					<!-- Button -->
					<div id="btn_bott">
						<span class="button blue"><input type="submit" value="저장" /></span>
						<span class="button black"><a href="banner_list.php?page=<?=$page?>">목록</a></span>
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