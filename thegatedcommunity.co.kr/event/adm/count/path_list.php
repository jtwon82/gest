<?
	include("../inc/head.php");
	$sdate=$_GET['sdate'];
	$edate=$_GET['edate'];

	$search_key=$_GET['search_key'];
	$search_val=$_GET['search_val'];
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

<SCRIPT LANGUAGE="JavaScript">
<!--
	$("#sdate").datepicker({dateFormat:'yy-mm-dd'});
	$("#edate").datepicker({dateFormat:'yy-mm-dd'});
//-->
</SCRIPT>						</table>
						</form>
					</div>

					<!-- 리스트 -->
					<div class="tableStyle1">
						<table>
							<thead>
							<tr>
								<th width="10%">순위</th>
								<th width="20%">접속경로</th>
								<th width="12%">방문자수</th>
								<th width="12%">비율</th>
								<th>그래프</th>
							</tr>
							</thead>
							<tbody>
						<?
							if($sdate) $searchSql[]="date>'".strtotime($sdate)."'";
							if($edate) $searchSql[]="date<'".strtotime($edate." +1 day")."'";
							if($searchSql) $where = " where ".implode(" and ",$searchSql);

							$i=1;
							$Rs=db_query("select site, count(idx) as cnt from tbl_counter $where group by site order by cnt desc");
							$total=db_result("select count(idx) from tbl_counter $where");
							while($pList=db_fetch($Rs)){
								$gwidth=500*$pList['cnt']/$total;
								$percent=$pList['cnt']/$total*100;
						?>
							<tr>
								<td><?=$i?></td>
								<td><?=$pList['site']?></td>
								<td><?=$pList['cnt']?></td>
								<td><?=round($percent)?>%</td>
								<td style="text-align:left;"><span style="width:<?=$gwidth?>px;height:10px;background:#910d42;display:inline-block;"></span></td>
							</tr>
						<?
								$i++;
							}
						?>
							<tr style="font-weight:bold;height:40px;">
								<td style="color:#C11258">합계</td>
								<td style="color:#C11258"></td>
								<td style="color:#C11258"><?=db_result("select count(idx) from tbl_counter $where")?></td>
								<td style="color:#C11258">100%</td>
								<td></td>
							</tr>
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