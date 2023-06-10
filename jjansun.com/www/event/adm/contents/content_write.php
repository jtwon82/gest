<?
	include("../inc/head.php");

	if($_GET['idx']){
		$pList=db_select("select * from tbl_content where idx='".$_GET['idx']."'");
		if($pList['idx']=="") msg_page("삭제되었거나 존재하지 않는 글입니다.");
	}

?>
<script type="text/javascript">
<!--
$(document).ready(function(){
	$(".sdate").datepicker({dateFormat:'yy-mm-dd'});
	$(".edate").datepicker({dateFormat:'yy-mm-dd'});
});
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
								<th>수집일</th>
								<td><input type="text" name="collect_date" id="" class="dInput " title="" style="width:80%;" placeholder="예)2018-10-11" value="<?=$pList['collect_date']?>" /></td>
							</tr>
							<tr>
								<th>상태</th>
								<td><select name="state" id="state" class="dInput req"><option value='9'>신규</option><option value='1'>승인</option></select>
									<script>state.value='<?=$pList['state']?>';</script></td>
							</tr>
							<tr>
								<th>제목</th>
								<td><input type="text" name="title" id="title" class="dInput req" title="제목" style="width:80%;" value="<?=$pList['title']?>" /></td>
							</tr>
							<tr style='display:none1;'>
								<th>카테고리</th>
								<td>
									<label><input type="checkbox" id="" name="gubun[]" value="a" <?=gAtFind($pList[gubun],'a')>=0?"checked":""?>><em></em>댓글</label>&nbsp;
									<label><input type="checkbox" id="" name="gubun[]" value="b" <?=gAtFind($pList[gubun],'b')>=0?"checked":""?>><em></em>공유</label>&nbsp;
									<label><input type="checkbox" id="" name="gubun[]" value="c" <?=gAtFind($pList[gubun],'c')>=0?"checked":""?>><em></em>즉석당첨</label>&nbsp;
									<label><input type="checkbox" id="" name="gubun[]" value="d" <?=gAtFind($pList[gubun],'d')>=0?"checked":""?>><em></em>퀴즈</label>&nbsp;
									<label><input type="checkbox" id="" name="gubun[]" value="e" <?=gAtFind($pList[gubun],'e')>=0?"checked":""?>><em></em>100%당첨</label>&nbsp;
									<label><input type="checkbox" id="" name="gubun[]" value="f" <?=gAtFind($pList[gubun],'f')>=0?"checked":""?>><em></em>응모</label>&nbsp;
									<label><input type="checkbox" id="" name="gubun[]" value="g" <?=gAtFind($pList[gubun],'g')>=0?"checked":""?>><em></em>체험단</label>&nbsp;
									<label><input type="checkbox" id="" name="gubun[]" value="h" <?=gAtFind($pList[gubun],'h')>=0?"checked":""?>><em></em>기타</label>
								</td>
							</tr>
							<tr>
								<th>설명</th>
								<td><input type="text" name="descript" id="descript" class="dInput req" title="" style="width:80%;" value="<?=$pList['descript']?>" /></td>
							</tr>
							<tr>
								<th>시작일</th>
								<td><input type="text" name="sdate" id="sdate" class="dInput req sdate" title="" style="width:80%;" placeholder="예)2018-10-11" value="<?=$pList['sdate']?>" /></td>
							</tr>
							<tr>
								<th>종료일</th>
								<td><input type="text" name="edate" id="edate" class="dInput req edate" title="" style="width:80%;" placeholder="예)2018-10-11" value="<?=$pList['edate']?>" /></td>
							</tr>
							<tr>
								<th>경품내용</th>
								<td><input type="text" name="gift_info" id="gift_info" class="dInput req" title="" style="width:80%;" value="<?=$pList['gift_info']?>" /></td>
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
							for($i=0; $i<2; $i++){
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
								<th>랜딩주소</th>
								<td><input type="text" name="landing" id="landing" class="dInput req" style="width:80%;" value="<?=$pList['landing']?>" />
									<a href="<?=$pList['landing']?>" target="pop" onclick="window.open('','pop','width=800,height=800')">LANDING</a></td>
							</tr>
							<tr>
								<th>URL</th>
								<td><input type="text" name="url" id="url" class="dInput" style="width:80%;" value="<?=$pList['url']?>" /></td>
							</tr>
							<tr>
								<th>태그</th>
								<td><input type="text" name="tag" id="tag" class="dInput " style="width:80%;" value="<?=$pList['tag']?>" /></td>
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