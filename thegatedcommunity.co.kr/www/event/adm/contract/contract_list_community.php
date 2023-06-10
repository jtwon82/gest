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
	$sort1=anti_injection($_GET['sort1']);
	$sort2=anti_injection($_GET['sort2']);

	if(!$sdate) $sdate= date('Y-m-d', strtotime('-5 day'));
	if(!$edate) $edate= date('Y-m-d');
?>
<script language="javascript">


	$(document).ready(function(){
		$(".sdate").datepicker({dateFormat:'yy-mm-dd'});
		$(".edate").datepicker({dateFormat:'yy-mm-dd'});
	});
	function proc(w,a,b){
		var f = document.search;
		if (w=='ssn')
		{
			f.search_key.value='ssn';
			f.search_val.value=a;
			f.submit();
		}
		else if (w=='chk')
		{
			f.search_key.value='ssn';
			f.search_val.value=a;
			f.submit();
		}
		else if (w=='pno')
		{
			f.search_key.value='pno';
			f.search_val.value=a;
			f.submit();
		}
		else if (w=='clear_search')
		{
			f.search_key.value='';
			f.search_key1.value='';
			f.search_key2.value='';
			f.search_val.value='';
		}
	}
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
				<h1> 응모내역</h1>
				<!-- 본문 -->
				<div class="content">

					<div class="tableSearch">
						<form name="search" method="get">
						<table>
							<tr>
								<th width="10%">검색</th>
								<td>
									<select name="search_key1" class="dSelect">
										<option value="">--- 플랫폼구분 ---</option>
										<option value="mobile" <?=($search_key1=="mobile" ? " selected":"")?>>모바일</option>
										<option value="web" <?=($search_key1=="web" ? " selected":"")?>>웹</option>
									</select>
									<select name="search_key" class="dSelect">
										<option value="">--- 검색키 ---</option>
										<option value="business_name" <?=($search_key=="business_name" ? " selected":"")?>>업체명</option>
										<option value="uname" <?=($search_key=="uname" ? " selected":"")?>>성함</option>
										<option value="stratum_name" <?=($search_key=="stratum_name" ? " selected":"")?>>직함</option>
										<option value="department_name" <?=($search_key=="department_name" ? " selected":"")?>>부서명</option>
									</select>
									<input type="text" name="search_val" id="search_val" class="dInput req" title="검색어" value="<?=$search_val?>" style="width:100px;" />
									<input type="text" name="sdate" class='dInput sdate' value='<?=$sdate?>'/> ~
									<input type="text" name="edate" class='dInput edate' value='<?=$edate?>'/>
									<span class="button red"><input type="button" value="초기화" onclick="proc('clear_search')"/><input type="submit" value="검색결과보기" /></span>
								</td>
							</tr>
						</table>
						</form>
					</div>
<?
	$param = "search_key=$search_key&search_val=$search_val&search_key1=$search_key1&search_val1=$search_val1&search_key2=$search_key2&search_val2=$search_val2&sdate=$sdate&edate=$edate";

							$page=anti_injection($_GET['page']);
							$where ="where 1=1";
							
							if($search_key1){
							    $where.=" and mobile = '$search_key1'";
							}
							if($search_key){
								if($search_key=='business_name'){
									$where.=" and business_name like '%$search_val%'";
									
								}else if($search_key=='uname'){
								    $where.=" and uname like '%$search_val%'";
								    
								}else if($search_key=='stratum_name'){
								    $where.=" and stratum_name like '%$search_val%'";
								    
								}else if($search_key=='department_name'){
								    $where.=" and department_name like '%$search_val%'";
								}
								else{
									$where.=" and $search_key like '%$search_val%'";
								}
							}
							if($sdate) $where.=" and a.reg_dates between '$sdate' and '$edate' ";

							$order = "order by a.reg_date desc";
							if($sort1=="a.reg_date"){
								$order = "order by a.reg_date $sort2";
							}

?>
					<!-- Button -->
					<div id="btn_top">
 						<span class="button blue"><a href="contract_excel_community.php?search=<?=urlencode($where)?>">엑셀다운</a></span>
<!-- 						<span class="button blue"><a href="contract_write.php">등록</a></span> -->
						<span class="button black"><a href="#" onclick="check_del(document.contract,'idx[]');return false;">선택삭제</a></span>
					</div>

					<!-- 리스트 -->
					<div class="tableStyle1">
					<form name="contract" id="contract" method="post" action="contract_pro_community.php">
					<input type="hidden" name="mode" value="" />
						<table width="">
							<thead>
							<tr>
								<th width="3%"><input type="checkbox" style="border:0" onClick="check_all(this,'idx[]')"></th>
								<th width="4%">번호</th>
								<th width="10%">날짜</th>
								<th width="5%">업체명</th>
								<th width="5%">성함</th>
								<th width="5%">직함</th>
								<th width="5%">부서명</th>
								<th width="8%">연락처</th>
								<th width="8%">이메일</th>
								<th width="5%">신청자유형</th>
								<th width="10%">희망시간</th>
								<th width="*">주소</th>
<!-- 								<th width="5%">상태</th> -->
<!-- 								<th width="5%">좋아요 <a href="?<?=$param?>&sort1=like_cnt&sort2=<?=($sort1=='like_cnt'&&$sort2=="desc"?"":"desc")?>"><?=($sort1=='like_cnt'&&$sort2=="desc"?"▼":"▲")?></a></th> -->
<!-- 								<th width="13%">참여일 <a href="?<?=$param?>&sort1=a.reg_date&sort2=<?=($sort1=='a.reg_date'&&$sort2=="desc"?"":"desc")?>"><?=($sort1=='a.reg_date'&&$sort2=="desc"?"▼":"▲")?></a></th> -->
							</tr>
							</thead>
							<tbody>
						<?
							if(!$page) $page=1;
							$list_num=15;

							$start_num=($page-1)*$list_num;

							$count=db_result("select count(*) from tbl_online_community a $where");
							
							$i=0;
							$sql = "
								select a.*
								from tbl_online_community a
								$where
								$order 
								limit $start_num, $list_num
							";
							#echo "<textarea>$sql</textarea>";
							$pRs=db_query($sql);
							while($pList=db_fetch($pRs)){
								$num = ($page-1) * $list_num + $i;
								$sortnum = $count-$num;
						?>
							<tr>
								<td><input name="idx[]" type="checkbox" value="<?=$pList['idx']?>" /></td>
								<td><?=$sortnum?></td>
								<td><?=$pList['reg_date']?></td>
								<td><?=$pList['business_name']?></td>
								<td><?=$pList['uname']?></td>
								<td><?=$pList['stratum_name']?></td>
								<td><?=$pList['department_name']?></td>
								<td><?=$pList['pno1']?></td>
								<td><?=$pList['email']?></td>
								<td><?=$pList['userType']?></td>
								<td><?=$pList['selMonth']?>월 <?=$pList['selDay']?>일 <?=$pList['selHour']?>시</td>
								<td><?=$pList['addr1']?> <?=$pList['addr2']?></td>
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
							<? page_list($page, $count, $list_num, $page_num, "$param") ?>
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