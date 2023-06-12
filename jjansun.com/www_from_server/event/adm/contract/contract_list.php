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
	if(!$edate) $edate= date('Y-m-d');

?>
<script language="javascript">
$(document).ready(function(){
	$(".sdate").datepicker({dateFormat:'yy-mm-dd'});
	$(".edate").datepicker({dateFormat:'yy-mm-dd'});
	
		$('.giftsendok').on('click', function(){
			var action= 'mode=update_giftsendok&chk='+ this.value +'&checkbox='+ (this.checked?'Y':'null');
			$("#iframe").attr('src', 'contract_pro.php?'+ action) ;
		});
	$(".tableStyle1 table tr").on({
	'mouseover':function(){
		$(this).css('background-color','#ffffcc');
	}, 'mouseout':function(){
		$(this).css('background-color','#ffffff');
	}});
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
	function check_giftsendok(f){
		f.mode.value='giftsendok';
		f.submit();
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
				<h1>응모내역</h1>
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
												<option value="game" <?=($search_key2=="game" ? " selected":"")?>>game</option>
												<option value="use" <?=($search_key2=="use" ? " selected":"")?>>use</option>
											</select>
											<select name="search_key1" class="dSelect">
												<option value="">--- 당첨구분 ---</option>
												<option value="lose" <?=($search_key1=="lose" ? " selected":"")?>>꽝</option>
												<option value="losex" <?=($search_key1=="losex" ? " selected":"")?>>당첨</option>
												<option value="gift" <?=($search_key1=="gift" ? " selected":"")?>>gift</option>
												<option value="gift2" <?=($search_key1=="gift2" ? " selected":"")?>>gift2</option>
												<option value="gift3" <?=($search_key1=="gift3" ? " selected":"")?>>gift3</option>
												<option value="gift4" <?=($search_key1=="gift4" ? " selected":"")?>>gift4</option>
												<option value="gift5" <?=($search_key1=="gift4" ? " selected":"")?>>gift5</option>
												<option value="gift6" <?=($search_key1=="gift4" ? " selected":"")?>>gift6</option>
												<option value="gift7" <?=($search_key1=="gift4" ? " selected":"")?>>gift7</option>
												<option value="gift8" <?=($search_key1=="gift4" ? " selected":"")?>>gift8</option>
												<option value="gift9" <?=($search_key1=="gift4" ? " selected":"")?>>gift9</option>
											</select>
											<select name="search_key" class="dSelect">
												<option value="">--- 검색키 ---</option>
												<option value="m.uname" <?=($search_key=="m.uname" ? " selected":"")?>>이름</option>
												<option value="pno" <?=($search_key=="pno" ? " selected":"")?>>전화번호</option>
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
							$where ="where 1=1 ";
							if($search_key2){
								$where.=" and a.event_gubun like '$search_key2%'";
							}
							if($search_key1=='losex'){
								$where.=" and a.win_type in('gift','gift2','gift3','gift4','gift5','gift6','gift7')";
							} else if ($search_key1){
								$where.=" and a.win_type = '$search_key1'";
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
							if($sdate) $where.=" and a.reg_dates between '$sdate' and '$edate' ";

							#echo $where;


?>
					<!-- Button -->
					<div id="btn_top">
						<span class="button blue"><a href="contract_excel.php?search=<?=urlencode($where)?>">엑셀다운</a></span>
						<!-- <span class="button blue"><a href="contract_write.php">등록</a></span> -->
						<span class="button blue"><a href="#" onclick="check_giftsendok(document.contract);return false;">선택 경품발송ok</a></span>
						<span class="button black"><a href="#" onclick="check_del(document.contract,'idx[]');return false;">선택 삭제</a></span>
					</div>

					<!-- 리스트 -->
					<div class="tableStyle1">
					<form name="contract" id="contract" method="post" action="contract_pro.php">
					<input type="hidden" name="mode" value="" />
						<table>
							<thead>
							<tr>
								<th width="3%"><input type="checkbox" style="border:0" onClick="check_all(this,'idx[]')"></th>
								<th width="3%">번호</th>
								<th width="9%">참여일</th>
								<th width="7%">ip</th>
								<th width="5%">이벤트구분</th>
								<th width="7%">current</th>
								<th width="5%">유입구분</th>
								<th width="10%">이름</th>
								<th width="7%">전화번호</th>
								<th width="7%">win_type</th>
								<th width="7%">reason</th>
								<th width="7%">giftname</th>
								<th width="*">발송여부</th>
								<th width="10%">ssn ID</th>
							</tr>
							</thead>
							<tbody>
						<?

							if(!$page) $page=1;
							$list_num=100; $page_num=10;

							$start_num=($page-1)*$list_num;

							$from = "from tbl_event a left join tbl_chance b on a.chk=b.chk
							left join tbl_member m on a.ssn=m.ssn left join (
								select a.codes, a.title gamecode, a.val gamename, c.title giftcode, c.val upfile, c.val2 giftname
								from tbl_common a join tbl_common b on a.codes=b.pcodes 
								join tbl_common c on b.codes=c.pcodes 
								where a.codes in(27,28,29,98) and b.title ='gift'
							) g on a.event_gubun=g.gamecode and a.win_type=g.giftcode
							left join tbl_event_giftsendok ok on a.ssn=ok.ssn and a.chk=ok.chk
							left join tbl_event_joiner j on a.chk=j.chk
							";

							$count=db_result("select count(*) $from $where");

							$i=0;
							$sql = "
								select a.*
									, b.current, m.pno pno1, m.uname uname, m.member_type, g.giftname, ok.checkbox ok_checkbox
									, j.win_giftname
								$from
								$where
								order by a.idx desc 
								limit $start_num, $list_num
							";
							echo "<textarea>$sql</textarea>";
							$pRs=db_query($sql);
							while($pList=db_fetch($pRs)){
								$num = ($page-1) * $list_num + $i;
								$sortnum = $count-$num;
						?>
							<tr>
								<td><input name="idx[]" type="checkbox" value="<?=$pList['idx']?>" /></td>
								<td><?=$sortnum?></td>
								<td><?=$pList['reg_date']?></td>
								<td><?=$pList['reg_ip']?></td>
								<td><?=$pList['event_gubun']?></td>
								<td><?=$pList['current']?></td>
								<td><?=$pList['referer']?></td>
								<td><?=$pList['uname']?>(<?=$pList['member_type']?>)</td>
								<td><?=$pList['pno1']?></td>
								<td><?=$pList['win_type']?></td>
								<td><?=$pList['reason']?></td>
								<td><?=$pList['win_giftname']?></td>
								<td><input name='chk[]' type="checkbox" class='giftsendok' value="<?=$pList['chk']?>" <?=$pList['ok_checkbox']?"checked":""?>/></td>
								<td><?=$pList['ssn']?></td>
							</tr>
						<?
								$i++;
							}
							if($i==0)echo"<tr><td colspan='13'>검색내역이 없습니다.";
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
<iframe name='iframe' id='iframe' style='display:none;'></iframe>
</body>
</html>