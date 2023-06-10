<?
	include("../inc/head.php");
	$sdate=$_GET['sdate'];
	$edate=$_GET['edate'];

	$search_key=$_GET['search_key'];
	$search_val=$_GET['search_val'];
?>
<SCRIPT LANGUAGE="JavaScript">
<!--
	$(document).ready(function(){
		$("#sdate").datepicker({dateFormat:'yy-mm-dd'});
		$("#edate").datepicker({dateFormat:'yy-mm-dd'});
	});
//-->
</SCRIPT>
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
				<h1>검색엔진별 통계</h1>
				<!-- 본문 -->
				<div class="content">

					<!-- Button -->
					<div class="tableSearch">
						<form name="search" method="get">
						<table>
							<tr>
								<th width="15%">검색기간</th>
								<td>
									<span class="button"><input type="button" value="오늘" onclick="location.href='?sdate=<?=date("Y-m-d")?>';"/></span>
									<input type="text" name="sdate" id="sdate" class="dInput req cal" title="검색시작일" value="<?=$sdate?>" style="width:60px;" /> <img src="../images/icon_calendar.gif" style="vertical-align:middle" class="cbtn" /> 부터 <input type="text" name="edate" id="edate" class="dInput req cal" title="검색종료일" value="<?=$edate?>" style="width:60px;" /> <img src="../images/icon_calendar.gif" style="vertical-align:middle" class="cbtn" /> 까지
									&nbsp;<span class="button red"><input type="submit" value="검색결과보기" /></span></td>
							</tr>
						</table>
						</form>
					</div>

					<!-- 리스트 -->
					<div class="tableStyle1">
						<table>
							<thead>
							<tr>
								<th width="10%">순위</th>
								<th width="20%">reg_date</th>
								<th width="20%">SSN</th>
								<th width="20%">이름</th>
								<th width="20%">URL</th>
								<th width="12%">COUNT</th>
							</tr>
							</thead>
							<tbody>
						<?
							if($sdate) $searchSql[]="date>'".strtotime($sdate)."'";
							if($edate) $searchSql[]="date<'".strtotime($edate." +1 day")."'";
							if($searchSql) $where = " where ".implode(" and ",$searchSql);

							$i=1;
							$Rs=db_query("select a.reg_date, a.ssn, b.uname, url, sum(cnt) cnt from tbl_counter_coupangad a join tbl_member b on a.ssn=b.ssn $where group by a.ssn, url order by a.idx desc");
							while($pList=db_fetch($Rs)){
								$gwidth=500*$pList['cnt']/$total;
								$percent=$pList['cnt']/$total*100;
						?>
							<tr>
								<td><?=$i?></td>
								<td><?=$pList['reg_date']?></td>
								<td><?=$pList['ssn']?></td>
								<td><?=$pList['uname']?></td>
								<td><?=$pList['url']?></td>
								<td><?=$pList['cnt']?></td>
							</tr>
						<?
								$i++;
							}
						?>
							</tbody>
						</table>
					</div>

				</div>
				<!-- end -->
			</td>
		</tr>
	</table>
</div>
</body>
</html>