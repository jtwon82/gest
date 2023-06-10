<?
	include("../inc/head.php");

	if($_GET['idx']){
		$pList=db_select("select * from tbl_content_dom where idx='".$_GET['idx']."'");
		if($pList['idx']=="") msg_page("삭제되었거나 존재하지 않는 글입니다.");
	}

?>
<script type="text/javascript">
<!--
$(document).ready(function(){
	$(".sdate").datepicker({dateFormat:'yy-mm-dd'});
	$(".edate").datepicker({dateFormat:'yy-mm-dd'});

	state.focus();
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
					<form name="frmWrite" id="frmWrite" method="post" enctype="multipart/form-data" action="content_dom_pro.php" onsubmit="return formChk(this);">
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
								<th>상태</th>
								<td><select name="state" id="state" class="dInput req">
									<option value=''>--선택--</option><option value='9'>신규</option><option value='1'>승인</option>
									</select><script>state.value='<?=$pList['state']?>';</script></td>
							</tr>
							<tr>
								<th>제목</th>
								<td><input type="text" name="title" id="title" class="dInput req" title="제목" style="width:80%;" value="<?=$pList['title']?>" /></td>
							</tr>
							<tr>
								<th>event_list_url</th>
								<td><input type="text" name="event_list_url" id="event_list_url" class="dInput req" title="" style="width:80%;" value="<?=$pList['event_list_url']?>" /></td>
							</tr>
							<tr>
								<th>dom_list</th>
								<td><input type="text" name="dom_list" id="dom_list" class="dInput req" title="" style="width:80%;" value="<?=$pList['dom_list']?>" /></td>
							</tr>
							<tr>
								<th>dom_title</th>
								<td><input type="text" name="dom_title" id="dom_title" class="dInput" title="" style="width:80%;" value="<?=$pList['dom_title']?>" /></td>
							</tr>
							<tr>
								<th>dom_desc</th>
								<td><input type="text" name="dom_desc" id="dom_desc" class="dInput" title="" style="width:80%;" value="<?=$pList['dom_desc']?>" /></td>
							</tr>
							<tr>
								<th>dom_url</th>
								<td><input type="text" name="dom_url" id="dom_url" class="dInput" title="" style="width:80%;" value="<?=$pList['dom_url']?>" /></td>
							</tr>
							<tr>
								<th>dom_sdate</th>
								<td><input type="text" name="dom_sdate" id="dom_sdate" class="dInput" title="" style="width:80%;" value="<?=$pList['dom_sdate']?>" /></td>
							</tr>
							<tr>
								<th>dom_edate</th>
								<td><input type="text" name="dom_edate" id="dom_edate" class="dInput" title="" style="width:80%;" value="<?=$pList['dom_edate']?>" /></td>
							</tr>
							<tr>
								<th>dom_fdate</th>
								<td><input type="text" name="dom_fdate" id="dom_fdate" class="dInput" title="" style="width:80%;" value="<?=$pList['dom_fdate']?>" /></td>
							</tr>
							<tr>
								<th>dom_thumb</th>
								<td><input type="text" name="dom_thumb" id="dom_thumb" class="dInput" title="" style="width:80%;" value="<?=$pList['dom_thumb']?>" /></td>
							</tr>
							<tr>
								<th>dom_gift_info</th>
								<td><input type="text" name="dom_gift_info" id="dom_gift_info" class="dInput" title="" style="width:80%;" value="<?=$pList['dom_gift_info']?>" /></td>
							</tr>
							<tr>
								<th>update_dates</th>
								<td><input type="text" name="update_dates" id="update_dates" class="dInput" title="" style="width:80%;" value="<?=$pList['update_dates']?>" /></td>
							</tr>
						</table>
					</div>

					<!-- Button -->
					<div id="btn_bott">
						<span class="button blue"><input type="submit" value="저장" /></span>
						<span class="button black"><a href="content_dom_list.php?page=<?=$page?>&search_key=<?=$search_key?>&searcy_val=<?=$searcy_val?>">목록</a></span>
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