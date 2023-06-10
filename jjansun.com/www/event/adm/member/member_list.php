<?
	include("../inc/head.php");

	$reg_ip= anti_injection(getUserIp());
	$member_level=anti_injection($_GET['member_level']);
	$search_key=anti_injection($_GET['search_key']);
	$search_val=anti_injection($_GET['search_val']);
	$sdate=anti_injection($_GET['sdate']);
	$edate=anti_injection($_GET['edate']);
	$scontact=anti_injection($_GET['scontact']);
	$econtact=anti_injection($_GET['econtact']);

	$chance_type=anti_injection($_GET['chance_type']);
	if($chance_type){
		$ssn=anti_injection($_GET['ssn']);
		$value=anti_injection($_GET['value']);
		$chance_type2=anti_injection($_GET['chance_type2']);
		$CHK			= getToken(15);

		if($chance_type=='use')$value=$value*-1;

		#$chance_info= charge_chance($reg_ip, $ssn, $CHK, 'add', $chance_type, $chance_type2, $value);
		insert_user_chance2($reg_ip, $ssn, $CHK, $chance_type, $chance_type2, $value);
		#$infoM = info_user_chance($ssn);
		#print_r($infoM);
		exit;
	}


	$searchSql[] = "userid <> '".$MASTER_UID."' and member_level>0";
	if($member_level) $searchSql[] = "member_level = '$member_level'";
	if($search_key && $search_val) $searchSql[] = "$search_key like '%$search_val%'";
	if($sdate) $searchSql[] = "reg_date >= '".($sdate)."'";
	if($edate) $searchSql[] = "reg_date <= '".($edate)."'";
	if($scontact) $searchSql[] = "visit_time > '".($scontact)."'";
	if($econtact) $searchSql[] = "visit_time < '".($econtact)."'";

	if($searchSql) $where = " where ".implode(" and ",$searchSql);
?>
<SCRIPT LANGUAGE="JavaScript">
<!--
	$(".sdate").datepicker({dateFormat:'yy-mm-dd'});
	$(".edate").datepicker({dateFormat:'yy-mm-dd'});
	function chancePlusMinus(chance_type, chance_type2, ssn, value){
		console.log( chance_type, chance_type2, ssn, value);
		if (value)
		{
			var src= '?chance_type='+chance_type+'&chance_type2='+chance_type2+'&ssn='+ssn+'&value='+value+'';
			$("#iframe").attr('src',src);
			location.reload();
		}
	}
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
				<h1>회원관리</h1>
				<!-- 본문 -->
				<div class="content">

					<div class="tableSearch">
						<form name="search" method="get">
						<table>
							<tr>
								<th width="15%">회원등급</th>
								<td width="35%">
									<select name="member_level" class="dSelect">
										<option value="">전체</option>
								<?
									$mRs=db_query("select * from tbl_member_level where level_code>0 order by idx");
									while($mList=db_fetch($mRs)){
										$sel=($member_level==$mList['level_code'] ? "selected" : "");
										echo "<option value=\"".$mList['level_code']."\" $sel>".$mList['level_name']."</option>\n;";
									}
								?>
									</select>
								</td>
								<th width="15%">키워드검색</th>
								<td>
									<select name="search_key" class="dSelect">
										<option value="name"<?=($search_key=="name" ? " selected":"")?>>이름</option>
										<option value="userid"<?=($search_key=="userid" ? " selected":"")?>>아이디</option>
										<option value="tel"<?=($search_key=="tel" ? " selected":"")?>>이메일</option>
									</select>
									<input type="text" name="search_val" id="search_val" class="dInput req" title="검색어" value="<?=$search_val?>" style="width:100px;" />
								</td>
							</tr>
							<tr>
								<th>가입일자</th>
								<td><input type="text" name="sdate" id="sdate" class="dInput req cal sdate" title="검색시작일" value="<?=$sdate?>" style="width:60px;" /> <img src="../images/icon_calendar.gif" style="vertical-align:middle" class="cbtn" /> ~ <input type="text" name="edate" id="edate" class="dInput req cal edate" title="검색종료일" value="<?=$edate?>" style="width:60px;" /> <img src="../images/icon_calendar.gif" style="vertical-align:middle" class="cbtn" /></td>
								<th>접속일자</th>
								<td><input type="text" name="scontact" id="scontact" class="dInput req cal" title="검색시작일" value="<?=$scontact?>" style="width:60px;" /> <img src="../images/icon_calendar.gif" style="vertical-align:middle" class="cbtn" /> ~ <input type="text" name="econtact" id="econtact" class="dInput req cal" title="검색종료일" value="<?=$econtact?>" style="width:60px;" /> <img src="../images/icon_calendar.gif" style="vertical-align:middle" class="cbtn" /></td>
							</tr>
						</table>
						<p style="text-align:center;margin-top:-10px;"><span class="button red"><input type="submit" value="검색결과보기" /></span> <span class="button"><input type="button" value="전체보기" onclick="location.href='member_list.php';" /></span></p>
						</form>
					</div>

					<!-- Button -->
					<div id="btn_top">
						<!-- <span class="button"><a href="member_excel.php?search=<?=urlencode($where)?>">EXCEL</a></span> -->
						<span class="button blue"><a href="member_write.php">회원등록</a></span>
						<span class="button black"><a href="#" onclick="check_del(document.member,'idx[]');return false;">선택삭제</a></span>
					</div>

					<!-- 리스트 -->
					<div class="tableStyle1">
					<form name="member" id="member" method="post" action="member_pro.php">
					<input type="hidden" name="mode" value="" />
						<table>
							<thead>
							<tr>
								<th width="3%"><input type="checkbox" style="border:0" onClick="check_all(this,'idx[]')"></th>
								<th width="5%">번호</th>
								<th width="8%">아이디</th>
								<th>닉네임</th>
								<th>연락처</th>
								<th>생년월일</th>
								<th>성별</th>
								<th width="5%">현재</th>
								<th width="5%">누적</th>
								<th width="5%">솔트추가</th>
								<th width="5%">(lv)</th>
								<th width="10%">이메일</th>
								<th width="8%">최근접속일</th>
								<th width="8%">가입일</th>
								<th width="8%">IP</th>
								<th width="8%">ssn</th>
								<th width="9%">관리</th>
							</tr>
							</thead>
							<tbody>
						<?
							$page=$_GET['page'];

							if(!$page) $page=1;
							$list_num=200; $page_num=10;

							$start_num=($page-1)*$list_num;

							$form = "from tbl_member m join tbl_member_level l on m.member_level=l.level_code and l.level_code=200
							join (	select ssn
									, sum(`current`) salt_current
									, sum(case when !instr(chance_type2,'use')>0 then `current` end ) salt_accum 
								from tbl_chance group by ssn) c on m.ssn=c.ssn ";

							$sql = "select count(*) $form $where";

							$count=db_result($sql);

							$i=0;
							$pRs=db_query("select *, m.idx, left(visit_date,16)visit_date, left(reg_date,16)reg_date 
								$form $where order by reg_date desc limit $start_num, $list_num");
							while($pList=db_fetch($pRs)){
								$num = ($page-1) * $list_num + $i;
								$sortnum = $count-$num;
						?>
							<tr>
								<td><input name="idx[]" type="checkbox" value="<?=$pList['idx']?>" /></td>
								<td><?=$sortnum?></td>
								<td><a href="#" onclick="memberPopup('<?=$pList['idx']?>');"><?=$pList['userid']?></a></td>
								<td><?=$pList['uname']?></td>
								<td><?=$pList['pno']?></td>
								<td><?=$pList['birthday']?></td>
								<td><?=$pList['usex']?></td>
								<td><?=number_format($pList['salt_current'])?></td>
								<td><?=number_format($pList['salt_accum'])?></td>
								<td><span class="button"><a href="javascript: chancePlusMinus('add','admin','<?=$pList['ssn']?>',prompt('추가할 솔트',1000));">A</a></span>
									<span class="button"><a href="javascript: chancePlusMinus('use','admin','<?=$pList['ssn']?>',prompt('삭제할 솔트',1000));">M</a></span>
									</td>
								<td><?=$pList['member_type']?></td>
								<td><?=$pList['email']?></td>
								<td><?=$pList['visit_date']?></td>
								<td><?=$pList['reg_date']?></td>
								<td><?=$pList['reg_ip']?></td>
								<td><?=$pList['ssn']?></td>
								<td>
									<span class="button"><a href="member_write.php?idx=<?=$pList['idx']?>">수정</a></span>
									<span class="button"><a href="member_pro.php?mode=del&idx=<?=$pList['idx']?>" onclick="return really()">삭제</a></span>
								</td>
							</tr>
						<?
								$i++;
							}
						?>
							</tbody>
						</table>
					</form>
					</div>

					<!-- paging -->
					<div id="paging">
						<p>
							<? page_list($page, $count, $list_num, $page_num, "member_level=$member_level&search_key=$search_key&search_val=$search_val&sdate=$sdate&edate=$edate&scontact=$scontact") ?>
						</p>
					</div>
				</div>
				<!-- end -->
			</td>
		</tr>
	</table>
</div>
<iframe name="iframe" id="iframe" style="display:none;"></iframe>
</body>
</html>