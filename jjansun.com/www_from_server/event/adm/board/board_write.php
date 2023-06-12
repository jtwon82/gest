<?
	include("../inc/head.php");
	include("./board_config.php");

	$mode = $_REQUEST['mode'];
	$page = $_REQUEST['page'];
	$b_code = $_REQUEST['b_code'];
	$search_key = $_REQUEST['search_key'];
	$search_val = $_REQUEST['search_val'];
	$idx = $_REQUEST['idx'];

	if($mode=="modify"){
		$pList=db_select("select * from tbl_board where idx='".$idx."'");
		$b_code=$pList['b_code'];
		$title=$pList['title'];
		$name=$pList['name'];
		$content=$pList['content'];
		$re_level=$pList['re_level'];
	}

	if(!$name) $name=$SITE_INFO['manager'];

	if($bbs_skin){
		include "./".$bbs_skin."/write.php";
		exit;
	}
?>

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
						<?
							if($bbs_category_yn=="Y" && $mode!="reply"){
						?>
							<tr>
								<th>분류</th>
								<td>
									<select name="b_code" id="b_code" title="분류" class="dSelect req">
										<option value="">선택</option>
									<?
										$cateArr=@explode("|",$bbs_category);
										foreach($cateArr as $ckey=>$cval){
											$sel=($cval==$cval ? "selected" : "");
											echo "<option value=\"$cval\" $sel>$cval</option>\n";
										}
									?>
									</select>
								</td>
							</tr>
						<?
							}
						?>
							<tr>
								<th>제목</th>
								<td><input type="text" name="title" id="title" class="dInput " title="제목" value="<?=$title?>" style="width:90%;" /></td>
							</tr>
						<?
							if($bbs_type=="1"){
						?>
							<tr>
								<th>등록옵션</th>
								<td>
								<?
									if($bbs_type=="1"){
										if($mode!="reply" && $re_level<1){
								?>
									<input type="checkbox" name="notice" value="Y" <?=($pList['notice']=="Y" ? "checked" : "")?> /> 공지글 &nbsp;
								<?
										}
										if($bbs_secret=="Y"){
								?>
									<input type="checkbox" name="secret" value="Y" <?=($pList['secret']=="Y" ? "checked" : "")?> /> 비밀글
								<?
										}
									}
								?>
								</td>
							</tr>
						<?
							}
						?>
							<tr>
								<th>이름</th>
								<td><input type="text" name="name" id="name" class="dInput req" title="이름" value="<?=$name?>" /></td>
							</tr>
							<tr>
								<th>내용</th>
								<td><textarea name="content" id="content" class="req" title="내용" style="width:100%;height:200px;"><?=$pList['content']?></textarea></td>
							</tr>
						<?
							if($bbs_fileuse=="Y"){
								for($i=0; $i<$file_num; $i++){
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
