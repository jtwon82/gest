<?
	include("../inc/head.php");

	if($_GET['idx']){
		$pList=db_select("select * from tbl_project_fbasic o where o.idx='".$_GET['idx']."'");
	}
?>
<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
<script>tinymce.init({ selector:'textarea[name=contents]' });</script>
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
					<form name="contract" id="contract" method="post" enctype="multipart/form-data" action="project_pro_fbasic.php" onsubmit="return formChk(this);">
					<input type="hidden" name="mode" value="<?=($_GET['idx'] ? "modify" : "write")?>" />
					<input type="hidden" name="idx" value="<?=$_GET['idx']?>" />
					<div class="tableStyle2">
						<table>
							<colgroup>
							<col width="15%">
							<col width="*">
							</colgroup>
							<tr>
								<th>제목</th>
								<td><input type="text" name="title" class="dInput" style="width:95%;" value="<?=$pList['title']?>" /></td>
							</tr>
							<tr>
								<th>카테고리</th>
								<td><select type="text" name="cate">
								<option value=''>-- 카테고리 --</option>
								<option value='campaign'>CAMPAIGN</option>
								<option value='web'>WEB</option>
								<option value='ad'>AD</option>
								<option value='sns'>SNS</option>
								</select><script>contract.cate.value='<?=$pList['cate']?>';</script></td>
							</tr>
							<tr>
								<th>클라이언트</th>
								<td><input type="text" name="client" class="dInput" style="width:95%" value="<?=$pList['client']?>" /></td>
							</tr>
							<tr>
								<th>설명</th>
								<td><input type="text" name="descript" class="dInput" style="width:95%;" value="<?=$pList['descript']?>" /></td>
							</tr>
							<tr>
								<th>파일</th>
								<td><input type="file" name="upfile" class="dInput" style="" value="<?=$pList['upfile']?>" />
									<input type="checkbox" name="del" value="Y">
									<a href="../../data/project/<?=$pList['upfile']?>" target="_blank"><?=$pList['orgname']?></a></td>
							</tr>
							<tr>
								<th>제목</th>
								<td><input type="text" name="title" class="dInput" style="width:95%;" value="<?=$pList['title']?>" /></td>
							</tr>
							<tr>
								<th>내용</th>
								<td colspan="3"><textarea name="contents" cols="" rows="" style="width:95%; height:200px;" class="dInput"><?=stripslashes($pList['contents'])?></textarea></td>
							</tr>
						</table>
					</div>

					<!-- Button -->
					<div id="btn_bott">
						<span class="button blue"><input type="submit" value="저장" /></span>
						<span class="button black"><a href="project_list_fbasic.php?page=<?=$page?>&skey=<?=$skey?>&sval=<?=$sval?>">목록</a></span>
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