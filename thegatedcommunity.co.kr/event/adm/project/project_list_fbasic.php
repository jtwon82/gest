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
		else
		{
			f.search_key.value=b;
			f.search_val.value=a;
			f.submit();
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
				<h1> Project 리스트</h1>
				<!-- 본문 -->
				<div class="content">

					<div class="tableSearch">
						<form name="search" method="get">
						<table>
							<tr>
								<th width="10%">검색</th>
								<td>
									<select name="search_key" class="dSelect">
										<option value="">--- 카테고리 ---</option>
										<option value="campaign" <?=($search_key=="campaign" ? " selected":"")?>>CAMPAIGN</option>
										<option value="web" <?=($search_key=="web" ? " selected":"")?>>WEB</option>
										<option value="ad" <?=($search_key=="ad" ? " selected":"")?>>AD</option>
										<option value="sns" <?=($search_key=="sns" ? " selected":"")?>>SNS</option>
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
							if($search_key){
								$where.=" and cate like '%$search_key%'";
							}
							if($search_val){
								$where.=" and title like '%$search_val%'";
							}
							if($sdate) $where.=" and a.reg_dates between '$sdate' and '$edate' ";

							$order = "order by a.reg_date desc";
							if($sort1=="like_cnt"){
								$order = "order by like_cnt $sort2";
							}
							if($sort1=="a.reg_date"){
								$order = "order by a.reg_date $sort2";
							}

?>
					<!-- Button -->
					<div id="btn_top">
						<span class="button blue"><a href="contract_excel.php?search=<?=urlencode($where)?>">엑셀다운</a></span>
						<span class="button blue"><a href="project_write_fbasic.php">등록</a></span>
						<span class="button black"><a href="#" onclick="check_del(document.contract,'idx[]');return false;">선택삭제</a></span>
					</div>

					<!-- 리스트 -->
					<div class="tableStyle1">
					<form name="contract" id="contract" method="post" action="contract_pro_fbasic.php">
					<input type="hidden" name="mode" value="" />
						<table>
							<thead>
							<tr>
								<th width="3%"><input type="checkbox" style="border:0" onClick="check_all(this,'idx[]')"></th>
								<th width="4%">번호</th>
								<th width="13%">등록일 <a href="?<?=$param?>&sort1=a.reg_date&sort2=<?=($sort1=='a.reg_date'&&$sort2=="desc"?"":"desc")?>"><?=($sort1=='a.reg_date'&&$sort2=="desc"?"▼":"▲")?></a></th>
								<th width="10%">카테고리</th>
								<th width="10%">클라이언트</th>
								<th width="10%">설명</th>
								<th width="10%">이미지</th>
								<th width="*"> 제목</th>
							</tr>
							</thead>
							<tbody>
						<?

							if(!$page) $page=1;
							$list_num=15;

							$start_num=($page-1)*$list_num;

							$count=db_result("select count(*) from tbl_project_fbasic a $where");

							$i=0;
							$sql = "
								select a.*
								from tbl_project_fbasic a
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
								<td><?=$pList['cate']?></td>
								<td><?=$pList['client']?></td>
								<td><?=$pList['descript']?></td>
								<td><a href="../../data/project/<?=$pList['upfile']?>" target="_blank"><?=$pList['orgname']?></a></td>
								<td><a href="project_write_fbasic.php?idx=<?=$pList['idx']?>"><?=$pList['title']?></a></td>
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