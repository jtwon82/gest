<?
	include("../inc/head.php");

	if($_GET['idx']){
		$pList=db_select("select * from tbl_popup where idx='".$_GET['idx']."'");
		$content=$pList['content'];
	}
?>

<script type="text/javascript" src="../../ckeditor/ckeditor.js"></script>
<script type="text/javascript">
$(function(){
	CKEDITOR.replace( 'content', {
		filebrowserUploadUrl: "http://<?=$_SERVER['HTTP_HOST']?>/ckeditor/samples/upload.php",
		height:'350px'
	});
});
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
				<h1>팝업관리</h1>
				<!-- 본문 -->
				<div class="content">

					<!-- 상세페이지 -->
					<form name="frmWrite" id="frmWrite" method="post" action="popup_pro.php" onsubmit="return formChk(this);">
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
								<th>노출여부</th>
								<td><input type="radio" name="open" value="Y" checked="checked"<?=($pList['open']=="Y" ? " checked":"")?>/> 노출 &nbsp; <input type="radio" name="open" value="N"<?=($pList['open']=="N" ? " checked":"")?>/> 중단</td>
								<th>팝업유형</th>
								<td><input type="radio" name="style" value="W" checked="checked"<?=($pList['style']=="W" ? " checked":"")?>/> 윈도우팝업 &nbsp; <input type="radio" name="style" value="L"<?=($pList['style']=="L" ? " checked":"")?>/> 레이어팝업</td>
							</tr>
							<tr>
								<th>팝업위치</th>
								<td>Top : <input title="팝업위치" name="ptop" id="ptop" type="text" class="dInput req" style="width: 60px;" value="<?=($pList['ptop'] ? $pList['ptop'] : 0)?>" /> Left : <input title="팝업 위치" name="pleft" type="text" class="dInput req" style="width: 60px;" title="팝업 위치" value="<?=($pList['pleft'] ? $pList['pleft'] : 0)?>" /></td>
								<th>팝업창크기</th>
								<td>가로 : <input title="팝업창 크기" name="pwidth" id="pwidth" type="text" class="dInput req" style="width: 60px;" value="<?=$pList['pwidth']?>" /> 세로 : <input title="팝업창 크기" name="pheight" id="pheight" type="text" class="dInput req" style="width: 60px;" value="<?=$pList['pheight']?>" /></td>
							</tr>
							<tr>
								<th>제목</th>
								<td colspan="3"><input title="제목" name="title" type="text" class="dInput" style="width: 50%;" value="<?=$pList['title']?>" /></td>
							</tr>
							<tr>
								<th>내용</th>
								<td colspan="3"><textarea name="content" id="content" class="req" title="내용" style="width:100%;"><?=$pList['content']?></textarea></td>
							</tr>
						</table>
					</div>

					<!-- Button -->
					<div id="btn_bott">
						<span class="button blue"><input type="submit" value="저장" /></span>
						<span class="button black"><a href="popup_list.php?page=<?=$page?>">목록</a></span>
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