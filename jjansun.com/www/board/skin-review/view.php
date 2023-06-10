<style>
.box{background-size: cover; background-repeat: no-repeat; background-position-x: center; }
.box .swiper{width:100%; height:200px; position:relative; overflow: hidden;}
.box .swiper-container {width: 100%; height: 100%; margin:0 auto;}
.box .swiper-container .swiper-wrapper li {padding:50px;}
</style>
			<!-- container s-->
			<div id="container" class="sub board view">
				<h2><span>상품 리뷰모음<em>인기있는 상품의 리뷰를 모았습니다. 베스트 리뷰를 보고 확인해보세요.</em></span></h2>
				<div class="input_review">
					<form name="frmWrite" id="frmWrite" method="post" enctype="multipart/form-data" action="board_pro.php">
					<input type="hidden" name="mode" value="<?=($_GET['idx'] ? "modify" : "write")?>" />
					<input type="hidden" name="idx" value="<?=$_GET['idx']?>" />
					<input type="hidden" name="b_id" value="<?=$b_id?>" />
					<ul>
						<li >
							<span class='review'>
<?
	$cRs=db_query("select * from tbl_board_comment where use_yn='Y' and b_idx='".$_GET['idx']."' order by reg_date desc limit 5");
	while($cList=db_fetch($cRs)){
?>
	<dl >
		<dt>
			<span>[<?=$cList['mall_name']?>] <?=$cList['key_id']?> STAR <?=($cList['star_num'])?></span>
			<span>등록날짜 : <?=substr($cList['reg_date'],0,10)?></span>
		</dt>
		<dd >
				<div class="box">
					<div class="swiper">
						<div class="swiper-container" >
							<div class="swiper-wrapper">
									<?=nl2br($cList['images'])?>
							</div>
						</div>

						<div class="swiper-button-next"><img src="/images/gallery_arrow_next.png"></div>
						<div class="swiper-button-prev"><img src="/images/gallery_arrow_prev.png"></div>
					</div>
				</div>
			
		</dd>
		<dd class="content">
			<?=cutstr(nl2br($cList['content']),150)?> <a href="<?=$pList['link']?>" target="_blank"><font style="color:black;font-weight:bold;">더 보기</font></a>
		</dd>
	</dl>
<?
	}
?>
<script type="text/javascript">
<!--
	$(document).ready(function(){
		var swiper= new Swiper('.swiper .swiper-container', {
			navigation : {
				nextEl : '.swiper .swiper-button-next',
				prevEl : '.swiper .swiper-button-prev',
			},
		});
	});
//-->
</script>
							</span>
							<span class='shop'>
								<dl>
									<dt>

									<a href="<?=$pList['link']?>" target="_blank">
						<?
							if($bbs_fileuse=="Y"){
								for($i=0; $i<$file_num; $i++){
									$ff=db_select("select * from tbl_board_file where b_idx='".$_GET['idx']."' and sortnum='".($i+1)."'");
									if($ff['filename']){
										echo "<img src='/data/filestream.php?Path=bbs/$b_id&File=$ff[filename]' width='100%'>";
									}
								}
							}
						?>
									</a>
								
									<div><?=nl2br($content)?>... <a href="<?=$pList['link']?>" target="_blank"><font style="color:black;font-weight:bold;">더 보기</font></a></div>
									</dt>
								</dl>
							</span>
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
					<span class="btn1"><a href="/board/board_list.php?b_id=<?=$b_id?>" onclick="">리스트보기</a></span>
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
