			<!-- container s-->
			<div id="container" class="sub upload">
					<form name="frmWrite" id="frmWrite" method="post" enctype="multipart/form-data" action="board_pro.php">
					<input type="hidden" name="mode" value="<?=($_GET['idx'] ? "modify" : "write")?>" />
					<input type="hidden" name="idx" value="<?=$_GET['idx']?>" />
					<input type="hidden" name="b_id" value="<?=$b_id?>" />

				<h2><span>BOARD 상세보기</span></h2>
				<div class="input">
					<ul>
						<li>
							<label class="txt">카테고리 <em>(중복선택가능)</em></label>
						<?
							if($bbs_category_yn=="Y" && $mode!="reply"){
						?>
							<select name="b_code" id="b_code" title="분류" class="dSelect req">
								<option value="">선택</option>
							<?
								$cateArr=@explode("|",$bbs_category);
								foreach($cateArr as $ckey=>$cval){
									$sel=((string)$b_code==(string)$ckey ? "selected" : "");
									echo "<option value=\"$ckey\" $sel>$cval</option>\n";
								}
							?>
							</select>
						<?
							}
						?>
						</li>
						<li>
							<label class="txt">이름</label>
							<span><?=$pList['name']?></span>
						</li>
						<li>
							<label class="txt">제목</label>
							<span><?=$pList['title']?></span>
						</li>
						<li>
							<label class="txt">내용</label>
							<span><?=nl2br($content)?></span>
						</li>
						<?
							if($bbs_linkuse=="Y"){
						?>
						<li>
							<label class="txt">링크</label>
							<span><?=$pList['link']?></span>
						</li>
						<?
							}
						?>

						<?
							if($bbs_fileuse=="Y"){
								for($i=0; $i<$file_num; $i++){
						?>
						<li>
							<label class="txt">첨부파일 <?=$i+1?></label>
							<span><input class="dInput " name="filename[]" type="file" id="filename<?=$i+1?>" style="height: 19px;" />
								<?
									$ff=db_select("select * from tbl_board_file where b_idx='".$_GET['idx']."' and sortnum='".($i+1)."'");
									if($ff['filename']){
										echo "<span style=\"padding:3px 0;display:inline-block;color:#333;\">".$ff['orgname']."</span>&nbsp;&nbsp;<input type=\"checkbox\" name=\"filedel[]\" id=\"dfile$i\" class=\"radio\" value=\"".($i+1)."\" /> <label for=\"dfile$i\">삭제</label>";
									}
								?>
							</span>
						</li>
						<?
								}
							}
						?>
					</ul>
				</div>
				<div class="btnContainer">
					<span class="btn2"><a href="javascript:;" onclick="window.history.go(-1);">뒤로가기</a></span>
				</div>
				</form>
			</div>
			<!-- container e-->



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
	//첨부파일 다운로드(파일경로,파일명)
	function FileDown(Path, File, Org){
		var x=screen.availWidth/2-150;
		var y=screen.availHeight/2-100;
//		var filedown = window.open("/event/common/filedown.php?Path="+Path+"&File="+File+"&Org="+Org, 'filedown', 'Left=' + x + ',Top=' + y + ',Width=0, Height=0,menubar=no,directories=no,resizable=no,status=no,scrollbars=no');
//		filedown.addEventListener("load", function() {
//			alert(1);
//		});
		location.replace("/event/common/filedown.php?Path="+Path+"&File="+File+"&Org="+Org);

	}

</script>