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
<script language="javascript">
$(document).ready(function(){
	$(".sdate").datepicker({dateFormat:'yy-mm-dd'});
	$(".edate").datepicker({dateFormat:'yy-mm-dd'});
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
				<h1>OREO 일자별 통계</h1>
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
								</td>
							</tr>
						</table>
						</form>
					</div>
<?
							$page=anti_injection($_GET['page']);
							$where ="where 1=1 and wdates > '2016-06-31' ";
							if($search_key) $where=" and $search_key like '%$search_val%'";
							if($sdate) $where.=" and wdates between '$sdate' and '$edate' ";
?>
					<!-- Button -->
					<div id="btn_top">
						<span class="button blue"><a href="contract_excel_byday_oreo.php?search=<?=urlencode($where)?>">엑셀다운</a></span><!-- 
						<span class="button blue"><a href="contract_write.php">등록</a></span>
						<span class="button black"><a href="#" onclick="check_del(document.contract,'idx[]');return false;">선택삭제</a></span> -->
					</div>

					<!-- 리스트 -->
					<div class="tableStyle1">
					<form name="contract" id="contract" method="post" action="contract_pro.php">
					<input type="hidden" name="mode" value="" />
						<table>
							<thead>
							<tr>
								<th width="10%">날짜</th>
								<th width="10%">웹 꽝</th>
								<th width="10%">웹 모바일쿠폰</th>
								<th width="10%">웹 기프트박스</th>
								<th width="10%">모바일 꽝</th>
								<th width="10%">모바일 모바일쿠폰</th>
								<th width="10%">모바일 기프트박스</th>
								<th width="10%" style="color:red;">꽝</th>
								<th width="10%" style="color:red;">모바일쿠폰</th>
								<th width="10%" style="color:red;">기프트박스</th>
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
							select case when b.idx=1 then 'Total' else a.wdates end wdatess
								, sum(a.web_lose) web_lose
								, sum(a.web_gift) web_gift
								, sum(a.web_addr) web_addr
								, sum(a.mob_lose) mob_lose
								, sum(a.mob_gift) mob_gift
								, sum(a.mob_addr) mob_addr
								, sum(a.lose) lose
								, sum(a.gift) gift
								, sum(a.addr) addr
							from (
								select *
									, web_lose + mob_lose lose
									, web_gift + mob_gift gift
									, web_addr + mob_addr addr
									from event_oreo_1601_sum
									$where
								)a, ( select idx from dumy where idx<3 ) b
							group by case when b.idx=1 then 'Total' else a.wdates end
								";
							$pRs=db_query($sql);
							while($pList=db_fetch($pRs)){
								$num = ($page-1) * $list_num + $i;
								#$sortnum = $count-$num;
						?>
							<tr>
								<td><?=$pList['wdatess']?></td>
								<td><?=$pList['web_lose']?></td>
								<td><?=$pList['web_gift']?></td>
								<td><?=$pList['web_addr']?></td>
								<td><?=$pList['mob_lose']?></td>
								<td><?=$pList['mob_gift']?></td>
								<td><?=$pList['mob_addr']?></td>
								<td><?=$pList['lose']?></td>
								<td><?=$pList['gift']?></td>
								<td><?=$pList['addr']?></td>
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
</body>
</html>