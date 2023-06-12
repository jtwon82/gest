<?
	include("../inc/head.php");

	if($_GET['idx']){
		$pList=db_select("select * from tbl_online o where o.idx='".$_GET['idx']."'");
	}
?>
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
				<h1>견적문의</h1>
				<!-- 본문 -->
				<div class="content">

					<!-- 상세페이지 -->
					<form name="contract" id="contract" method="post" enctype="multipart/form-data" action="contract_pro.php" onsubmit="return formChk(this);">
					<input type="hidden" name="mode" value="<?=($_GET['idx'] ? "modify" : "write")?>" />
					<input type="hidden" name="idx" value="<?=$_GET['idx']?>" />
					<div class="tableStyle2">
						<table>
							<colgroup>
							<col width="15%">
							<col width="35%">
							<col width="15%">
							<col width="*">
							</colgroup>
							<tr>
								<th>신청인성함</th>
								<td><input type="type" name="name" class="dInput req" title="신청인성함" value="<?=$pList['name']?>" /></td>
								<th>신청구분</th>
								<td>
									<select class="dSelect" name="type">
									<?
										foreach($requestArray as $xkey=>$xval){
											$sel=($xkey==$pList['type'] ? "selected" : "");
											echo "<option value=\"$xkey\" $sel>$xval[0]</option>\n";
										}
										reset($requestArray);
									?>
									</select>
								</td>
							</tr>
							<tr>
								<th>휴대전화</th>
								<td><input type="type" name="hp" class="dInput" value="<?=$pList['hp']?>" /></td>
								<th>일반전화</th>
								<td><input type="type" name="tel" class="dInput" value="<?=$pList['tel']?>" /></td>
							</tr>
							<tr>
								<th>회사명</th>
								<td><input type="type" name="company" class="dInput" style="width: 220px" value="<?=$pList['company']?>" />
								<th>이메일주소</th>
								<td><input type="type" name="email" class="dInput" style="width: 180px" value="<?=$pList['email']?>" /></td>
							</td>
							</tr>
							<tr>
								<th>업종</th>
								<td><input type="type" name="kind" class="dInput" style="width: 220px" value="<?=$pList['kind']?>" /></td>
								<th>지역</th>
								<td><input type="type" name="area" class="dInput" style="width: 180px" value="<?=$pList['area']?>" /></td>
							</tr>
							<tr>
								<th>홈페이지 종류</th>
								<td><input type="type" name="category" class="dInput" style="width: 220px" value="<?=$pList['category']?>" /></td>
								<th>참고사이트</th>
								<td>http://<input type="type" name="site" class="dInput" style="width: 270px" value="<?=$pList['site']?>" /></td>
							</tr>
							<tr>
								<th>희망제작가격</th>
								<td><input type="type" name="price" class="dInput" style="width: 220px" value="<?=$pList['price']?>" /></td>
								<th>첨부파일</th>
								<td>
									<input title="첨부파일" name="upfile" type="file" style="width:97%;height: 19px;background: #FFF;" class="dInput" title="첨부파일" />
									<a href="#" onclick="FileDown('online','<?=$pList['upfile']?>','<?=$pList['orgname']?>');return false;"><?=$pList['orgname']?></a>
								</td>
							<tr>
							<tr>
								<th>전하실 말씀</th>
								<td colspan="3"><textarea title="" name="content" cols="" rows="" style="width:95%; height:200px;" class="dInput"><?=$pList['content']?></textarea></td>
							</tr>
						</table>
					</div>

					<div class="tableStyle2">
						<table>
							<colgroup>
							<col width="15%">
							<col width="35%">
							<col width="15%">
							<col width="*">
							</colgroup>
							<tr>
								<th>유입경로</th>
								<td class="font_bold"><a href="<?=($pList['referer']=="북마크/주소직접입력" ? "#" : $pList['referer']."\" target=\"_blank\"")?>" ><?=$pList['site'];?> / <?=$pList['keyword'];?></a></td>
								<th>IP</th>
								<td class="font_bold"><?=$pList['ip'];?></td>
							<tr>
							<tr>
								<th>처리상황 메모</th>
								<td colspan="3"><textarea title="" name="memo" cols="" rows="" style="background:#f8e2ea;width:95%; height:100px;" class="dInput"><?=$pList['memo']?></textarea></td>
							</tr>
						</table>
					</div>

					<!-- Button -->
					<div id="btn_bott">
						<span class="button blue"><input type="submit" value="저장" /></span>
						<span class="button black"><a href="contract_list.php?page=<?=$page?>&skey=<?=$skey?>&sval=<?=$sval?>">목록</a></span>
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