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

	$sql = "select * from tbl_member_menu where userid='{$_SESSION[LOGIN_ID]}' and depth='contract' ";

	$menu = db_query($sql);
	while($row = db_fetch($menu)){
		break;
	}
?>
<script language="javascript">
	location.replace('<?=$row[href]?>');


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
									<select name="search_key2" class="dSelect">
										<option value="">--- 참여구분 ---</option>
										<option value="mobile" <?=($search_key2=="mobile" ? " selected":"")?>>모바일</option>
										<option value="web" <?=($search_key2=="web" ? " selected":"")?>>웹</option>
									</select>
<!-- 									<select name="search_key1" class="dSelect"> -->
<!-- 										<option value="">--- 당첨구분 ---</option> -->
<!-- 										<option value="lose" <?=($search_key1=="lose" ? " selected":"")?>>꽝</option> -->
<!-- 										<option value="gift" <?=($search_key1=="gift" ? " selected":"")?>>모바일쿠폰</option> -->
<!-- 									</select> -->
									<select name="search_key" class="dSelect">
										<option value="">--- 검색키 ---</option>
										<option value="uname" <?=($search_key=="uname" ? " selected":"")?>>이름</option>
										<option value="pno" <?=($search_key=="pno" ? " selected":"")?>>전화번호</option>
<!-- 										<option value="ssn" <?=($search_key=="ssn" ? " selected":"")?>>ssn</option> -->
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
							if($search_key2){
								$where.=" and mobile = '$search_key2'";
							}
							if($search_key1){
								$where.=" and win_type = '$search_key1'";
							}
							if($search_key){
								if($search_key=='pno'){
									$where.=" and concat(pno1,pno2,pno3) like '%$search_val%'";
								}
								else{
									$where.=" and $search_key like '%$search_val%'";
								}
							}
							if($sdate) $where.=" and a.regdates between '$sdate' and '$edate' ";

							$order = "order by a.regdate desc";
							if($sort1=="like_cnt"){
								$order = "order by like_cnt $sort2";
							}
							if($sort1=="a.regdate"){
								$order = "order by a.regdate $sort2";
							}

?>
					<!-- Button -->
					<div id="btn_top">
						<span class="button blue"><a href="contract_excel.php?search=<?=urlencode($where)?>">엑셀다운</a></span>
						<span class="button blue"><a href="contract_write.php">등록</a></span>
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
								<th width="*">영상 제목</th>
								<th width="10%">이메일</th>
								<th width="10%">이름</th>
								<th width="10%">전화번호</th>
								<th width="10%">코드</th>
								<th width="5%">모바일</th>
								<th width="5%">상태</th>
								<th width="5%">좋아요 <a href="?<?=$param?>&sort1=like_cnt&sort2=<?=($sort1=='like_cnt'&&$sort2=="desc"?"":"desc")?>"><?=($sort1=='like_cnt'&&$sort2=="desc"?"▼":"▲")?></a></th>
								<th width="13%">참여일 <a href="?<?=$param?>&sort1=a.regdate&sort2=<?=($sort1=='a.regdate'&&$sort2=="desc"?"":"desc")?>"><?=($sort1=='a.regdate'&&$sort2=="desc"?"▼":"▲")?></a></th>
							</tr>
							</thead>
							<tbody>
						<?

							if(!$page) $page=1;
							$list_num=15;

							$start_num=($page-1)*$list_num;

							$count=db_result("select count(*) from tbl_online a $where");

							$i=0;
							$sql = "
								select a.*
									, case when state = 5 then '삭제' 
											when state = 2 then '대기'
											when state = 1 then '신청'
										end state_str
									, ifnull(( select COUNT(like_cnt) from tbl_online_like b where a.idx=b.online_idx ),0) like_cnt
								from tbl_online a
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
								<td><a href="contract_write.php?idx=<?=$pList['idx']?>"><?=$pList['title']?></a></td>
								<td><?=$pList['email']?></td>
								<td><?=$pList['uname']?></td>
								<td><?=$pList['pno1']?></td>
								<td><?=$pList['youtube_code']?></td>
								<td><?=$pList['mobile']?></td>
								<td><?=$pList['state_str']?></td>
								<td><?=$pList['like_cnt']?></td>
								<td><?=$pList['regdate']?></td>
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