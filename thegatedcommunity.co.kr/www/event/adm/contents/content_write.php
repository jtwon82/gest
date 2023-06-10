<?
	include("../inc/head.php");

	if($_GET['idx']){
		$pList=db_select("select * from tbl_content where idx='".$_GET['idx']."'");
		$cate=db_query("select * from tbl_category where idx not in (10,11) order by od ");
		if($pList['idx']=="") msg_page("삭제되었거나 존재하지 않는 글입니다.");
		$content=$pList['content'];
	}

?>


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
				<h1>컨텐츠등록</h1>
				<!-- 본문 -->
				<div class="content">

					<!-- 상세페이지 -->
					<form name="frmWrite" id="frmWrite" method="post" enctype="multipart/form-data" action="content_pro.php" onsubmit="return formChk(this);">
					<input type="hidden" name="mode" value="<?=($_GET['idx'] ? "modify" : "write")?>" />
					<input type="hidden" name="idx" value="<?=$_GET['idx']?>" />
					<div class="tableStyle2">

						<h2>♦ 기본정보 ♦</h2>
						<table>
							<colgroup>
							<col width="15%">
							<col width="*">
							</colgroup>
							<tr>
								<th>제목</th>
								<td><input type="text" name="title" id="title" class="dInput req" title="제목" style="width:80%;" value="<?=$pList['title']?>" /></td>
							</tr>
							<tr style='display:none1;'>
								<th>카테고리</th>
								<td><select name="cate">
									<option value=''>선택하세요</option>
									<?while($list=db_fetch($cate)){?>
									<option value="<?=$list['idx']?>"><?=$list['title']?></option>
									<?}?>
									</select>
									<SCRIPT LANGUAGE="JavaScript">frmWrite.cate.value='<?=$pList['cate']?>';</SCRIPT>
								</td>
							</tr>
							<tr>
								<th>내용</th>
								<td><textarea name="content" id="content" style="width:95%; height:200px;" class="dInput req"><?=$pList['content']?></textarea></td>
							</tr>
						</table>

						<h2>♦ 이미지 / 동영상 ♦</h2>
						<table>
							<colgroup>
							<col width="15%">
							<col width="*">
							</colgroup>
							<tr>
						<?
							for($i=0; $i<3; $i++){
						?>
							<tr>
								<th>이미지<?=$i+1?></th>
								<td><input class="dInput " name="filename[]" type="file" id="filename<?=$i+1?>" style="width: 98%; height: 19px;" />
								<?
									$imgList=db_select("select * from tbl_content_file where bidx='".$_GET['idx']."' and sortnum='".($i+1)."'");
									if($imgList['filename']){
										echo "<span style=\"padding:3px 0;display:inline-block;color:#333;\">".$imgList['orgname']."</span>&nbsp;&nbsp;<input type=\"checkbox\" name=\"filedel[]\" id=\"dfile$i\" class=\"radio\" value=\"".($i+1)."\" /> <label for=\"dfile$i\">삭제</label>";
									}
								?>
								</td>
							</tr>
						<?
							}
						?>
							<tr>
								<th>동영상URL</th>
								<td><input type="text" name="vod" id="vod" class="dInput" style="width:80%;" value="<?=$pList['vod']?>" /></td>
							</tr>
							<tr>
								<th>태그</th>
								<td><input type="text" name="tag" id="tag" class="dInput req" style="width:80%;" value="<?=$pList['tag']?>" /></td>
							</tr>
						</table>
					</div>

					<!-- Button -->
					<div id="btn_bott">
						<span class="button blue"><input type="submit" value="저장" /></span>
						<span class="button black"><a href="content_list.php?page=<?=$page?>&search_key=<?=$search_key?>&searcy_val=<?=$searcy_val?>">목록</a></span>
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