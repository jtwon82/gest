
			<!-- container s-->
			<div id="container" class="sub board view">
				<h2><span>고객센터<em> </em></span></h2>
				<div class="input">
					<form name="frmWrite" id="frmWrite" method="post" enctype="multipart/form-data" action="board_pro.php">
					<input type="hidden" name="mode" value="<?=($_GET['idx'] ? "modify" : "write")?>" />
					<input type="hidden" name="idx" value="<?=$_GET['idx']?>" />
					<input type="hidden" name="b_id" value="<?=$b_id?>" />
					<ul>
						<?
							if($bbs_category_yn=="Y" && $mode!="reply"){
						?>
						<li>
							<label class="txt">카테고리 </label>
							<span><?=$pList[b_code]?></span>
						</li>
						<?
							}
						?>
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
							<span class="comment"><?=nl2br($content)?></span>
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
							<span>
								<?
									$ff=db_select("select * from tbl_board_file where b_idx='".$_GET['idx']."' and sortnum='".($i+1)."'");
									if($ff['filename']){
								?><a href="#" onclick="FileDown('bbs/<?=$b_id?>','<?=$ff['filename']?>','<?=$ff['orgname']?>');return false;"><?=$ff['orgname']?></a> (<?=get_filesize($ff['filesize'])?>)<?
									}
									else{
										echo "선택파일 없음";
									}
								?>
							</span>
						</li>
						<?
								}
							}
						?>
						<li>
							<label class="txt">조회수</label>
							<span><?=$pList['hit']?></span>
						</li>
					</ul>
				</div>
				<div class="btnContainer">
						<?
							if($_SESSION['USER'][LOGIN_NO]==$pList[userno]){
						?>
								<span class="btn1"><a href="board_write.php?b_id=<?=$b_id?>&b_code=<?=$pList[b_code]?>&idx=<?=$_GET[idx]?>&mode=modify&page=<?=$page?>&search_key=<?=$search_key?>&search_val=<?=$search_val?>">수정</a></span>
								<span class="btn1"><a href="board_pro.php?b_id=<?=$b_id?>&mode=del&idx=<?=$_GET['idx']?>&b_code=<?=$b_code?>&page=<?=$page?>&search_key=<?=$search_key?>&search_val=<?=$search_val?>" onclick="return confirm('삭제 하시겠습니까?');">삭제</a></span>
						<?
							}
						?>
						<?
							if($bbs_reply=="Y"){
						?>
								<span class="btn1"><a href="#" onclick="send_re.submit();return false;">답글</a></span>
						<?
							}
						?>
					<span class="btn1"><a href="javascript:;" onclick="window.history.go(-1);">리스트보기</a></span>
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
		location.replace("/event/common/filedown.php?Path="+Path+"&File="+File+"&Org="+Org);

	}

</script>