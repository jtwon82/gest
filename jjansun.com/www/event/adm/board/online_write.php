<?
	include("../inc/head.php");
	include("./board_config.php");

	$mode = $_REQUEST['mode'];
	$page = $_REQUEST['page'];
	$search_key = $_REQUEST['search_key'];
	$search_val = $_REQUEST['search_val'];
	$idx = $_REQUEST['idx'];

	if($idx){
		$pList=db_select("select * from tbl_online where idx='".$idx."'");
		$title=$pList['title'];
		$name=$pList['name'];
		list($tel1,$tel2,$tel3)=explode("-",$pList['tel']);
		list($hp1,$hp2,$hp3)=explode("-",$pList['hp']);
		list($email1,$email2)=explode("@",$pList['email']);
		$content=$pList['content'];
	}
?>
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
</script>
<body onload="">
<div id="adm_wrap">
	<!-- content -->
	<table style="width:100%;">
		<tr>
			<td id="left_area">
				<!-- 좌측메뉴 -->
				<? include "../inc/left.php"; ?>
			</td>
			<td id="content_area">
				<h1><?=$bbs_name?></h1>
				<!-- 본문 -->
				<div class="content">

					<!-- 상세페이지 -->
					<form name="contract" id="contract" method="post" enctype="multipart/form-data" action="online_pro.php" onsubmit="return formChk(this);">
					<input type="hidden" name="mode" value="<?=($_GET['idx'] ? "modify" : "write")?>" />
					<input type="hidden" name="idx" value="<?=$_GET['idx']?>" />
					<input type="hidden" name="b_id" value="<?=$_GET['b_id']?>" />
					<div class="tableStyle2">
						<table>
							<colgroup>
							<col width="15%">
							<col width="*">
							</colgroup>
							<tr>
								<th>이름</th>
								<td><input type="text" name="name" class="dInput req" title="이름" value="<?=$pList['name']?>" /></td>
							</tr>
							<tr>
								<th>이메일</th>
								<td>
									<input class="dInput" name="email1" type="text" id="email1" style="width:90px" title="이메일" value="<?=$email1?>" /> @
									<input class="dInput" name="email2" type="text" id="email2" style="width:120px" title="이메일" value="<?=$email2?>" />
									<select id="email3" name="email3" title="이메일" class="dSelect selMail">
										<option value="">직접입력</option>
									<?
										foreach($emailArray as $xkey=>$xval){
											$sel=($email2==$xkey ? "selected" : "");
											echo "<option value=\"$xkey\" $sel>$xval</option>\n";
										}
										reset($emailArray);
									?>
									</select>
								</td>
							</tr>
						<? if(strpos($bbs_form,"addr")!==false){?>
							<tr>
								<th>주소</th>
								<td>
									<input class="dInput" name="addr" type="text" id="addr" style="width:90%;" title="주소" value="<?=$pList['addr']?>" />
								</td>
							</tr>
						<? } ?>
						<? if(strpos($bbs_form,"company")!==false){?>
							<tr>
								<th>회사</th>
								<td>
									<input class="dInput" name="company" type="text" id="company" style="width: 230px;" title="회사" value="<?=$pList['company']?>" />
								</td>
							</tr>
						<? } ?>
						<? if(strpos($bbs_form,"part")!==false){?>
							<tr>
								<th>부서</th>
								<td>
									<input class="dInput" name="part" type="text" id="part" style="width: 230px;" title="부서" value="<?=$pList['part']?>" />
								</td>
							</tr>
						<? } ?>
						<?
							if($bbs_category_yn=="Y"){
						?>
							<tr>
								<th>분류</th>
								<td>
									<select name="category" class="dSelect">
										<option value="">선택</option>
									<?
										$cateArr=@explode("|",$bbs_category);
										foreach($cateArr as $ckey=>$cval){
											$sel=($ckey==$pList['category'] ? "selected" : "");
											echo "<option value=\"$ckey\" $sel>$cval</option>\n";
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
								<td><input type="text" name="title" class="dInput" style="width:90%" value="<?=$pList['title']?>" /></td>
							</tr>
						<? if(strpos($bbs_form,"filename")!==false){?>
							<tr>
								<th>첨부파일</th>
								<td class="file_down">
									<input class="dInput" name="filename" type="file" id="filename" style="width: 90%;height:19px;" title="첨부파일" />
								<?
									if($pList['filename']){
								?>
									<br /><a href="#" onclick="FileDown('online','<?=$pList['filename']?>','<?=$pList['orgname']?>');return false;"><?=$pList['orgname']?></a>
								<?
									}
								?>
								</td>
							</tr>
						<? } ?>
							<tr>
								<th>문의내용</th>
								<td><textarea title="" name="content" cols="" rows="" style="width:95%; height:200px;" class="dInput"><?=$pList['content']?></textarea></td>
							</tr>
						</table>
					</div>

					<!-- <div class="tableStyle2">
						<table>
							<colgroup>
							<col width="15%">
							<col width="*">
							</colgroup>
							<tr>
								<th>처리상황 메모</th>
								<td><textarea title="" name="memo" cols="" rows="" style="background:#f8e2ea;width:95%; height:100px;" class="dInput"><?=$pList['memo']?></textarea></td>
							</tr>
						</table>
					</div> -->

					<!-- Button -->
					<div id="btn_bott">
						<span class="button blue"><input type="submit" value="저장" /></span>
						<span class="button black"><a href="online_list.php?page=<?=$page?>&b_id=<?=$b_id?>&search_key=<?=$search_key?>&search_val=<?=$search_val?>">목록</a></span>
					</div>
					</form>


					<form name="reply" id="reply" method="post" enctype="multipart/form-data" action="online_pro.php" onsubmit="return formChk(this);">
					<input type="hidden" name="mode" value="reply" />
					<input type="hidden" name="idx" value="<?=$_GET['idx']?>" />
					<input type="hidden" name="b_id" value="<?=$_GET['b_id']?>" />

					<div class="tableStyle2" style="margin-top:30px;">
						<table>
							<colgroup>
							<col width="15%">
							<col width="*">
							</colgroup>
							<tr>
								<th>답변</th>
								<td><textarea title="답변" name="reply" cols="" rows="" style="width:95%; height:200px;" class="dInput req"><?=$pList['reply']?></textarea></td>
							</tr>
						</table>
					</div>

					<!-- Button -->
					<div id="btn_bott">
						<span class="button red"><input type="submit" value="메일로 답변 전송하기" /></span>
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