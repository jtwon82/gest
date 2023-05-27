<?
	include("../inc/head.php");

	$search_key=anti_injection($_GET['search_key']);
	$search_val=anti_injection($_GET['search_val']);
	$search_key1=anti_injection($_GET['search_key1']);
	$search_val1=anti_injection($_GET['search_val1']);
	$search_key2=anti_injection($_GET['search_key2']);
	$search_val2=anti_injection($_GET['search_val2']);
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
				<h1>OREO 응모내역</h1>
				<!-- 본문 -->
				<div class="content">

					<div class="tableSearch">
						<form name="search" method="get">
						<table>
							<tr>
								<th width="10%">검색</th>
								<td>
											<select name="search_key2" class="dSelect">
												<option value="">--- 참여구분 ---</option>
												<option value="mobile" <?=($search_key2=="mobile" ? " selected":"")?>>모바일</option>
												<option value="web" <?=($search_key2=="web" ? " selected":"")?>>웹</option>
											</select>
											<select name="search_key1" class="dSelect">
												<option value="">--- 당첨구분 ---</option>
												<option value="lose" <?=($search_key1=="lose" ? " selected":"")?>>꽝</option>
												<option value="gift" <?=($search_key1=="gift" ? " selected":"")?>>모바일쿠폰</option>
												<option value="addr" <?=($search_key1=="addr" ? " selected":"")?>>기프티박스</option>
											</select>
											<select name="search_key" class="dSelect">
												<option value="">--- 검색키 ---</option>
												<option value="uname" <?=($search_key=="uname" ? " selected":"")?>>이름</option>
												<option value="pno" <?=($search_key=="pno" ? " selected":"")?>>전화번호</option>
												<option value="addr1" <?=($search_key=="addr1" ? " selected":"")?>>addr</option>
												<option value="ssn" <?=($search_key=="ssn" ? " selected":"")?>>ssn</option>
												<option value="reg_ip" <?=($search_key=="reg_ip" ? " selected":"")?>>reg_ip</option>
											</select>
											<input type="text" name="search_val" id="search_val" class="dInput req" title="검색어" value="<?=$search_val?>" style="width:100px;" />
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
							$where ="where 1=1 and reg_dates > '2016-06-31' ";
							if($search_key2){
								$where.=" and title = '$search_key2'";
							}
							if($search_key1){
								$where.=" and win_type = '$search_key1'";
							}
							if($search_key){
								if($search_key=='pno'){
									$where.=" and concat(pno1,pno2,pno3) like '%$search_val%'";
								}
								else if($search_key=='addr1'){
									$where.=" and concat(addr1,addr2) like '%$search_val%'";
								}
								else{
									$where.=" and $search_key like '%$search_val%'";
								}
							}
							if($sdate) $where.=" and reg_dates between '$sdate' and '$edate' ";


?>
					<!-- Button -->
					<div id="btn_top">
						<span class="button blue"><a href="contract_excel_oreo.php?search=<?=urlencode($where)?>">엑셀다운</a></span><!-- 
						<span class="button blue"><a href="contract_write.php">등록</a></span> -->
						<span class="button black"><a href="#" onclick="check_del(document.contract,'idx[]');return false;">선택삭제</a></span>
					</div>

					<!-- 리스트 -->
					<div class="tableStyle1">
					<form name="contract" id="contract" method="post" action="contract_pro.php">
					<input type="hidden" name="mode" value="" />
						<table>
							<thead>
							<tr>
								<th width="3%"><input type="checkbox" style="border:0" onClick="check_all(this,'idx[]')"></th>
								<th width="4%">번호</th>
								<th width="11%">참여일</th>
								<th width="7%">참여구분</th>
								<th width="7%">당첨구분</th>
								<th width="7%">이름</th>
								<th width="10%">전화번호</th>
								<th width="30%">주소</th>
								<th width="10%">유입</th>
								<th width="10%">ip</th>
								<th width="*">ssn ID</th>
							</tr>
							</thead>
							<tbody>
						<?

							if(!$page) $page=1;
							$list_num=15; $page_num=10;

							$start_num=($page-1)*$list_num;

							$count=db_result("select count(*) from event_oreo_1601 $where");

							$i=0;
							$sql = "
								select *
									, case when title='mobile' then '모바일' else '웹' end titles
									, case when win_type='lose' then '꽝'
										when win_type='gift' then '모바일쿠폰'
										when win_type='addr' then '기프티박스'
										end win_types
									, case when referer is null or referer='' then 'direct' else referer end str_referer
								from event_oreo_1601 a
								$where
								order by reg_date desc 
								limit $start_num, $list_num
							";
							$pRs=db_query($sql);
							while($pList=db_fetch($pRs)){
								$num = ($page-1) * $list_num + $i;
								$sortnum = $count-$num;
						?>
							<tr>
								<td><input name="idx[]" type="checkbox" value="<?=$pList['idx']?>" /></td>
								<td><?=$sortnum?></td>
								<td><?=$pList['reg_date']?></td>
								<td><?=$pList['titles']?></td>
								<td><?=$pList['win_types']?></td>
								<td><?=$pList['uname']?></td>
								<td><?=$pList['pno1']?>-<?=$pList['pno2']?>-<?=$pList['pno3']?></td>
								<td><?=$pList['addr1']?> <?=$pList['addr2']?></td>
								<td><?=$pList['str_referer']?></td>
								<td><a href="javascript:;" onclick="document.search.search_key.value='reg_ip';document.search.search_val.value='<?=$pList['reg_ip']?>';document.search.submit();"><?=$pList['reg_ip']?></td>
								<td><?=$pList['ssn']?></td>
							</tr>
						<?
								$i++;
							}
							if($i==0)echo"<tr><td colspan='11'>검색내역이 없습니다.";
						?>
							</tbody>
						</table>
					</form>
					</div>

					<!-- paging -->
					<div id="paging">
						<p>
							<? page_list($page, $count, $list_num, $page_num, "search_key=$search_key&search_val=$search_val&search_key1=$search_key1&search_val1=$search_val1&search_key2=$search_key2&search_val2=$search_val2&sdate=$sdate&edate=$edate") ?>
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