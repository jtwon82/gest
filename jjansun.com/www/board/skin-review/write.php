
			<!-- container s-->
			<div id="container" class="sub board write">
				<form name="frmWrite" id="frmWrite" method="post" enctype="multipart/form-data" action="board_pro.php" onsubmit="chkSubmit(this);">
				<input type="hidden" name="mode" value="<?=$mode?>" />
				<input type="hidden" name="idx" value="<?=$idx?>" />
				<input type="hidden" name="b_id" value="<?=$b_id?>" />

				<input type="hidden" name="page" value="<?=$page?>" />
				<input type="hidden" name="search_key" id="search_key" value="<?=$search_key?>" />
				<input type="hidden" name="search_val" id="search_val" value="<?=$search_val?>" />
				<h2><span>베스트상품<em>인기있는 상품은 추천합니다. 베스트 리뷰를 보고 확인해보세요.</em></span></h2>
				<div class="input">
					<ul>
						<li>
							<label class="txt">이름</label>
							<span><input readonly="readonly" style="border:none;" type="text" name="name" value="<?=$pList['name']?>" placeholder="이름을 입력해주세요." /></span>
						</li>
						<li>
							<label class="txt">제목</label>
							<span><input type="text" name="title" value="<?=$pList['title']?>" placeholder="제목을 입력해주세요." /></span>
						</li>
						<li>
							<label class="txt">내용</label>
							<span><textarea name="content"><?=$pList['content']?></textarea></span>
						</li>
						<?
							if($bbs_linkuse=="Y"){
						?>
						<li>
							<label class="txt">링크</label>
							<span><input type="text" name="link" placeholder="연결 URL을 입력해주세요." value="<?=$pList['link']?>"/></span>
						</li>
						<?
							}
						?>
						<li>
							<label class="txt">태그</label>
							<span><input type="text" name="etc9" value="<?=$pList['etc9']?>" placeholder="태그를 입력해주세요." /></span>
						</li>
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
										echo "<label>". $ff['orgname']."&nbsp;&nbsp;<input type=\"checkbox\" name=\"filedel[]\" value=\"".($i+1)."\" /> 삭제</label>";
									}
								?>
							</span>
						</li>
						<?
								}
							}
						?>
						<?
							if($bbs_type=="1"){
						?>
							<li>
								<label class="txt">구분</label>
								<span>
								<?
									if($bbs_type=="1"){
										if($mode!="reply" && $re_level<1){
								?>
									<label><input type="checkbox" name="notice" value="Y" <?=($pList['notice']=="Y" ? "checked" : "")?> /> <em></em><p>공지글</p> </label>
								<?
										}
										if($bbs_secret=="Y"){
								?>
									<label><input type="checkbox" name="secret" value="Y" <?=($pList['secret']=="Y" ? "checked" : "")?> /> <em></em><p>비밀글</p> </label>
								<?
										}
									}
								?>
								</span>
							</li>
						<?
							}
						?>
					</ul>
				</div>
				<div class="btnContainer">
					<span class="btn1"><a href="javascript:;" onclick="document.frmWrite.submit();"><?=$mode=='modify'?"수정":"글쓰기"?></a></span>
					<span class="btn2"><a href="javascript:;" onclick="window.history.go(-1);">취소</a></span>
				</div>
				</form>
			</div>
			<!-- container e-->
			
<script type="text/javascript">
$(document).ready(function(){
	$(".sdate").datepicker({dateFormat:'yy-mm-dd'});
	$(".edate").datepicker({dateFormat:'yy-mm-dd'});
});
function chkSubmit(f){
	if (f.name.value=='')
	{
		alert("이름을 입력해주세요.");
		return false;
	}
	if (f.title.value=='')
	{
		alert("제목을 입력해주세요.");
		return false;
	}
	if (f.content.value=='')
	{
		alert("내용을 입력해주세요.");
		return false;
	}
}
</script>