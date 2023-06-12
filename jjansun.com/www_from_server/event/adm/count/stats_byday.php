<?
	include("../inc/head.php");

	$search_key=anti_injection($_GET['search_key']);
	$search_val=anti_injection($_GET['search_val']);
	$sdate=anti_injection($_GET['sdate']);
	$edate=anti_injection($_GET['edate']);

	if(!$sdate) $sdate= date('Y-m-d', strtotime('-5 day'));
	#if(!$sdate) $sdate= '2016-10-10';
	if(!$edate) $edate= date('Y-m-d');
?>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

<script language="javascript">
$(document).ready(function(){
	$(".sdate").datepicker({dateFormat:'yy-mm-dd'});
	$(".edate").datepicker({dateFormat:'yy-mm-dd'});


//	var chart = new CanvasJS.Chart("chartContainer", {
//		title:{
//			text: "OREO 일자별 통계"      },
//		axisX: {
//			valueFormatString: "YYYY-MM-DD"
//		},
//		axisY:{
//			includeZero: true
//		},
//		data: [ getData(13), getData(14) ]
//	});
//	chart.render();

	function getData(n){
		var result = {
			type: "line",
			showInLegend: true,
			legendText : $('.table thead tr th:eq('+n+')').html(),
			xValueFormatString: "YYYY-MM-DD",
			dataPoints: getRowData(n)
		};

		return result;
	}
	function getRowData(n){
		var arr = new Array();

		$('.table tbody tr').each(function(id){
			arr.push( { x: date($(this).find('td:first').html()), y: parseInt($(this).find('td:eq('+n+')').html()) } );
		});
		arr.pop();
		return arr;
	}
	function date(d){
		var date1 = d.split('-');
		var newDate = new Date(parseInt(date1[0]), parseInt(date1[1])-1, parseInt(date1[2]));
		return newDate;
	}
});
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
				<h1>일자별 참여</h1>
				<!-- 본문 -->
				<div class="content">

					<div class="tableSearch">
						<form name="search" method="get">
						<table>
							<tr>
								<th width="10%">검색</th>
								<td>
									<input type="text" name="sdate" class='dInput sdate' value='<?=$sdate?>'/> ~
									<input type="text" name="edate" class='dInput edate' value='<?=$edate?>'/>
									<span class="button red"><input type="submit" value="검색결과보기" /></span>
									<span class="button red"><a href="javascript:;" value="데이터최신화" onclick="updateData('stats_pro.php','update_byday');"/>데이터최신화</span>
								</td>
							</tr>
						</table>
						</form>
					</div>
<?
							$page=anti_injection($_GET['page']);
							$where ="where 1=1 and reg_dates > '2016-06-31' ";
							if($search_key) $where=" and $search_key like '%$search_val%'";
							if($sdate) $where.=" and reg_dates between '$sdate' and '$edate' ";
?>
<!-- 					<div id="chartContainer" style="height: 370px; width: 100%;"></div> -->

					<!-- Button -->
					<div id="btn_top">
<!-- 						<span class="button blue"><a href="contract_pro.php?mode=RUN_SUMMERY">통계내기</a></span> -->
<!-- 						<span class="button blue"><a href="contract_excel_byday_oreo.php?search=<?=urlencode($where)?>">엑셀다운</a></span> -->
						<!-- 
						<span class="button blue"><a href="contract_write.php">등록</a></span>
						<span class="button black"><a href="#" onclick="check_del(document.contract,'idx[]');return false;">선택삭제</a></span> -->
					</div>

					<!-- 리스트 -->
					<div class="tableStyle1">
					<form name="contract" id="contract" method="post" action="contract_pro.php">
					<input type="hidden" name="mode" value="" />
						<table class='table'>
							<thead>
							<tr>
								<th width="6%">날짜</th>
								<th width="6%">lose</th>
								<th width="6%">gift</th>
								<th width="6%">gift2</th>
								<th width="6%">gift3</th>
								<th width="6%">gift4</th>
								<th width="6%">gift5</th>
								<th width="6%">gift6</th>
							</tr>
							</thead>
							<tbody>
						<?

							if(!$page) $page=1;
							$list_num=15; $page_num=10;

							$start_num=($page-1)*$list_num;

							#$count=db_result("select count(idx) from tbl_online $where");

							$i=0;
							$sql="
							select case when b.idx=1 then 'Total' else a.reg_dates end reg_datess
								, sum(a.web_lose+a.mob_lose) mob_lose
								, sum(a.web_gift+a.mob_gift) mob_gift
								, sum(a.web_gift2+a.mob_gift2) mob_gift2
								, sum(a.web_gift3+a.mob_gift3) mob_gift3
								, sum(a.web_gift4+a.mob_gift4) mob_gift4
								, sum(a.web_gift5+a.mob_gift5) mob_gift5
								, sum(a.web_gift6+a.mob_gift6) mob_gift6
							from (
								select *
									from tbl_event_sum
									$where
								)a, ( select idx from dumy where idx<3 ) b
							group by case when b.idx=1 then 'Total' else a.reg_dates end
								";
							$pRs=db_query($sql);
							while($pList=db_fetch($pRs)){
								$num = ($page-1) * $list_num + $i;
								#$sortnum = $count-$num;
						?>
							<tr>
								<td><?=$pList['reg_datess']?></td>
								<td><a href="contract_list.php?search_key2=mob&search_key1=lose&search_key=&search_val=&sdate=<?=$pList['reg_datess']?>&edate=<?=$pList['reg_datess']?>"><?=$pList['mob_lose']?></a></td>
								<td><a href="contract_list.php?search_key2=mob&search_key1=gift&search_key=&search_val=&sdate=<?=$pList['reg_datess']?>&edate=<?=$pList['reg_datess']?>"><?=$pList['mob_gift']?></a></td>
								<td><a href="contract_list.php?search_key2=mob&search_key1=gift2&search_key=&search_val=&sdate=<?=$pList['reg_datess']?>&edate=<?=$pList['reg_datess']?>"><?=$pList['mob_gift2']?></a></td>
								<td><a href="contract_list.php?search_key2=mob&search_key1=gift3&search_key=&search_val=&sdate=<?=$pList['reg_datess']?>&edate=<?=$pList['reg_datess']?>"><?=$pList['mob_gift3']?></a></td>
								<td><a href="contract_list.php?search_key2=mob&search_key1=gift4&search_key=&search_val=&sdate=<?=$pList['reg_datess']?>&edate=<?=$pList['reg_datess']?>"><?=$pList['mob_gift4']?></a></td>
								<td><a href="contract_list.php?search_key2=mob&search_key1=gift5&search_key=&search_val=&sdate=<?=$pList['reg_datess']?>&edate=<?=$pList['reg_datess']?>"><?=$pList['mob_gift5']?></a></td>
								<td><a href="contract_list.php?search_key2=mob&search_key1=gift6&search_key=&search_val=&sdate=<?=$pList['reg_datess']?>&edate=<?=$pList['reg_datess']?>"><?=$pList['mob_gift6']?></a></td>
							</tr>
						<?
								$i++;
							}
							if($i==0)echo"<tr><td colspan='10'>검색내역이 없습니다.";
						?>
							</tbody>
						</table>
					</form>
					</div>

					<!-- paging -->
					<div id="paging">
						<p>
							<? #page_list($page, $count, $list_num, $page_num, "search_key=$search_key&search_val=$search_val") ?>
						</p>
					</div>
				</div>
				<!-- end -->
			</td>
		</tr>
	</table>
</div>
<div class="dimmed" style="position: fixed; top: 0px; left: 0px; width: 100%; height: 100%; z-index: 100; opacity: 0.5; background-color: rgb(0, 0, 0); display:none;">
	<div style="margin: 0;position: absolute;top: 50%;left: 50%; font-size:20px; font-weight:bold; color:white;">화면을 업데이트 중입니다.</div>
</div>
</body>
</html>