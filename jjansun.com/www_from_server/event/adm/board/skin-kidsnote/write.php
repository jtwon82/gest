<script type="text/javascript" src="../../../ckeditor/ckeditor.js"></script>
<script type="text/javascript">
$(function(){
//	CKEDITOR.replace( 'content', {
//		filebrowserUploadUrl: "http://<?=$_SERVER['HTTP_HOST']?>/ckeditor/samples/upload.php",
//		height:'350px'
//	});
});
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
					<form name="frmWrite" id="frmWrite" method="post" enctype="multipart/form-data" action="board_pro.php" onsubmit="return formChk(this);">
					<input type="hidden" name="mode" value="<?=$mode?>" />
					<input type="hidden" name="idx" value="<?=$idx?>" />
					<input type="hidden" name="b_id" value="<?=$b_id?>" />

					<input type="hidden" name="page" value="<?=$page?>" />
					<input type="hidden" name="search_key" id="search_key" value="<?=$search_key?>" />
					<input type="hidden" name="search_val" id="search_val" value="<?=$search_val?>" />
					<div class="tableStyle2">
						<table>
							<colgroup>
							<col width="20%">
							<col width="*">
							</colgroup>
							<tr>
								<th>제목</th>
								<td><input type="text" name="title" id="title" class="dInput " title="제목" value="<?=$title?>" style="width:90%;" /></td>
							</tr>
							<tr>
								<th>name</th>
								<td><input type="text" name="name" id="name" class="dInput " title="이름" value="<?=$pList['name']?>" /></td>
							</tr>
							<tr>
								<th>link</th>
								<td><input type="text" name="link" id="link" class="dInput " title="이름" value="<?=$pList['link']?>" /></td>
							</tr>
							<tr>
								<th>내용</th>
								<td><textarea name="content" id="content" class="dInput req" title="내용" style="width:100%;height:200px;"><?=$pList['content']?></textarea></td>
							</tr>
						</table>
					</div>

					<!-- Button -->
					<div id="btn_bott">
						<span class="button blue"><input type="submit" value="저장" /></span>
						<span class="button black"><a href="board_list.php?b_id=<?=$b_id?>&page=<?=$page?>&search_key=<?=$search_key?>&search_val=<?=$search_val?>">목록</a></span>
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
