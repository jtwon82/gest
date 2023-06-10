<!-- 댓글 -->
<div id="comments" style="position:relative;">
	<h2><img src="../images/comm_title.gif" alt="댓글" /></h2>
	<!-- 글쓰기 -->
	<form name="comment" id="comment" method="post" onsubmit="return formChk(this)" action="../board/board_pro.php">
	<input name="mode" type="hidden" value="comment" />
	<input name="idx" type="hidden" id="idx" value="<?=$_GET['idx']?>" />
	<input name="b_id" type="hidden" id="b_id" value="<?=$b_id?>" />
	<input name="b_code" type="hidden" id="b_code" value="<?=$b_code?>" />
	<input name="reurl" type="hidden" id="reurl" value="<?=$current_url?>" />
	<p class="writeBox">
		<label>이름</label>
		<input title="이름" name="cname" type="text" class="dInput req" style="width: 100px;" value="<?=$SITE_INFO['manager']?>" />
	</p>
	<p class="writeBox" style="margin-bottom:25px;">
		<textarea title="내용" id="content" name="content" style="height:51px;width: 90%;" class="req"></textarea>
		<input type="image" src="../images/comm_txt_btn.gif" class="write_btn" style="width:101px;height:71px;" alt="등록하기" />
	</p>
	</form>

	<!-- 리스트 -->
<?
	$cRs=db_query("select * from tbl_board_comment where b_idx='".$_GET['idx']."' order by reg_date desc");
	while($cList=db_fetch($cRs)){
?>
	<dl>
		<dt>
			<strong><?=$cList['name']?></strong><span><?=$cList['reg_date']?></span><a href="board_pro.php?mode=comment_del&b_id=<?=$b_id?>&b_code=<?=$b_code?>&idx=<?=$_GET['idx']?>&cidx=<?=$cList['idx']?>&reurl=<?=$current_url?>" onclick="return really();"><img src="../images/comm_btn_del.gif" alt="글삭제" /></a>
		</dt>
		<dd>
			<?=nl2br($cList['content'])?>
		</dd>
	</dl>
<?
	}
?>
</div>