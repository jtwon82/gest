

			<!-- container s-->
			<div id="container" class="sub upload">
				<!-- 상세페이지 -->
				<form name="frmWrite" id="frmWrite" method="post" enctype="multipart/form-data" action="board_pro.php" onsubmit="">
				<input type="hidden" name="mode" value="<?=$mode?>" />
				<input type="hidden" name="idx" value="<?=$idx?>" />
				<input type="hidden" name="b_id" value="<?=$b_id?>" />

				<input type="hidden" name="page" value="<?=$page?>" />
				<input type="hidden" name="search_key" id="search_key" value="<?=$search_key?>" />
				<input type="hidden" name="search_val" id="search_val" value="<?=$search_val?>" />
				<h2><span>BOARD 글쓰기</span></h2>
				<div class="input">
					<ul>
						<?
							if($bbs_category_yn=="Y" && $mode!="reply"){
						?>
						<li>
							<label class="txt">카테고리</label>
							<span>
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
							</span>
						</li>
						<?
							}
						?>
						<li>
							<label class="txt">이름</label>
							<span><input readonly="readonly" style="border:none;" type="text" name="name" value="<?=$pList['name']?>" placeholder="이름을 입력해주세요." /></span>
						</li>
						<li>
							<label class="txt">제목</label>
							<span><input type="text" name="title" value="<?=$pList['title']?>" placeholder="[브랜드명] 이벤트 내용 순으로 입력해주세요." /></span>
						</li>
						<li>
							<label class="txt">내용</label>
							<span>
								<textarea name="content" id="content" class="req" title="내용" style="width:100%;"><?=$pList['content']?></textarea>
							</span>
						</li>
						<li class="date">
							<label class="txt">기간</label>
							<span><input type="text" name="sdate" class="sdate" placeholder="예) 20180307" maxlength="8" value="<?=$pList['etc1']?>"/><em>~</em><input type="text" name="edate" class="sdate" placeholder="예) 20180307" maxlength="8" value="<?=$pList['etc2']?>"/><em class="txt1">*숫자 8자리로 입력해주세요.</em></span>
						</li>
						<li>
							<label class="txt">경품</label>
							<span><input type="text" name="etc3" placeholder="경품 내용을 입력해주세요." value="<?=$pList['etc3']?>"/></span>
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
							if($bbs_fileuse=="Y"){
								for($i=0; $i<$file_num; $i++){
						?>
						<li>
							<label class="txt">첨부파일 <?=$i+1?></label>
							<span><input class="dInput " name="filename[]" type="file" id="filename<?=$i+1?>" />
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
					</ul>
				</div>
				<div class="btnContainer">
					<span class="btn1"><a href="javascript:;" onclick="document.frmWrite.submit();">이벤트 올리기</a></span>
					<span class="btn2"><a href="javascript:;" onclick="window.history.go(-1);">취소</a></span>
				</div>
				</form>
			</div>
			<!-- container e-->

<script type="text/javascript" src="../ckeditor/ckeditor.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$(".sdate").datepicker({dateFormat:'yy-mm-dd'});
	$(".edate").datepicker({dateFormat:'yy-mm-dd'});
});
$(function(){
//	CKEDITOR.replace( 'content', {
//		filebrowserUploadUrl: "http://<?=$_SERVER['HTTP_HOST']?>/ckeditor/samples/upload.php",
//		height:'150px',
//		width:'100%'
//	});
});
</script>

