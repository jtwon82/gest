<?
	include("../inc/head.php");
	$dong=anti_injection($_POST['dong']);
?>

<body id="pop_body">
<div id="adm_wrap">
	<!-- content -->
	<table style="width:100%;">
		<tr>
			<td id="pop_area">
				<h1>우편번호검색</h1>
				<!-- 본문 -->
				<div class="content">

					<div class="tableSearch">
						<form method="post" name="search" onsubmit="return formChk(this)">
						<input type="hidden" name="pole_type" id="poleType" value="" />
						<table>
							<tr>
								<th style="height:35px;padding:10px;">
									<span>ㆍ검색하실 동(읍,면)명을 입력하세요.</span>
									<input type="text" name="dong" class="dInput req" title="검색키워드" style="width:200px;ime-mode:active" value="<?=$dong?>" />
									<span class="button black"><input type="submit" value="검색" onclick="fnCheckInput();" /></span>
								</th>
							</tr>
						</table>
						</form>
					</div>

					<!-- 리스트 -->
					<div class="tableStyle1">
					<form name="group" id="group" method="post" action="group_pro.php">
					<input type="hidden" name="mode" value="" />
					<h2>♦ 주소검색결과 ♦</h2>
						<table>
							<thead>
							<tr>
								<th width="17%">우편번호</th>
								<th>주소</th>
							</tr>
							</thead>
							<tbody>
						<?
							if($dong){
								$rs=db_query("select * from tbl_zipcode where GUGUN like '%$dong%' or DONG like '%$dong%'");
								$cnt=db_count("tbl_zipcode","GUGUN like '%$dong%' or DONG like '%$dong%'");
								while($pList=db_fetch($rs)){
									$zipaddress=$pList["SIDO"]." ".$pList["GUGUN"]." ".$pList["DONG"];
									$zipcode=$pList['ZIPCODE'];
						?>
							<tr>
								<td><?=$zipcode?></td>
								<td style="text-align:left;padding-left:5px;"><a href="#" onclick="insertAddress('<?=$zipcode?>','<?=$zipaddress?>');return false;"><?=$zipaddress?> <?=$pList['BUNJI']?></a></td>
							</tr>
						<?
								}
							}

							if(!$dong || $cnt<1){
						?>
							<tr>
								<td colspan="2" align="center">검색된 데이터가 없습니다</td>
							</tr>
						<?
							}
						?>
							</tbody>
						</table>
					</form>
					<p style="margin-top:15px;text-align:center;"><span class="button black"><input type="button" value="닫기" onclick="self.close();" /></span></p>
					</div>
				</div>
				<!-- end -->
			</td>
		</tr>
	</table>
</div>
</body>
</html>