<?
	include("../inc/head.php");

	$member_level=anti_injection($_GET['member_level']);
	$search_key=anti_injection($_GET['search_key']);
	$search_val=anti_injection($_GET['search_val']);
	$sdate=anti_injection($_GET['sdate']);
	$edate=anti_injection($_GET['edate']);
	$scontact=anti_injection($_GET['scontact']);
	$econtact=anti_injection($_GET['econtact']);

	$searchSql[] = "userid <> '".$MASTER_UID."' and member_level>0";
	if($member_level) $searchSql[] = "member_level = '$member_level'";
	if($search_key && $search_val) $searchSql[] = "$search_key like '%$search_val%'";
	if($sdate) $searchSql[] = "regdate >= '".($sdate)."'";
	if($edate) $searchSql[] = "regdate <= '".($edate)."'";
	if($scontact) $searchSql[] = "visit_time > '".($scontact)."'";
	if($econtact) $searchSql[] = "visit_time < '".($econtact)."'";

	if($searchSql) $where = " where ".implode(" and ",$searchSql);
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
								<td><input type="text" name="sdate" id="sdate" class="dInput req cal" title="검색시작일" value="<?=$sdate?>" style="width:60px;" /> <img src="../images/icon_calendar.gif" style="vertical-align:middle" class="cbtn" /> ~ <input type="text" name="edate" id="edate" class="dInput req cal" title="검색종료일" value="<?=$edate?>" style="width:60px;" /> <img src="../images/icon_calendar.gif" style="vertical-align:middle" class="cbtn" /></td>
								<th>접속일자</th>
								<td><input type="text" name="scontact" id="scontact" class="dInput req cal" title="검색시작일" value="<?=$scontact?>" style="width:60px;" /> <img src="../images/icon_calendar.gif" style="vertical-align:middle" class="cbtn" /> ~ <input type="text" name="econtact" id="econtact" class="dInput req cal" title="검색종료일" value="<?=$econtact?>" style="width:60px;" /> <img src="../images/icon_calendar.gif" style="vertical-align:middle" class="cbtn" /></td>
							</tr>
<SCRIPT LANGUAGE="JavaScript">
<!--
	$("#sdate").datepicker({dateFormat:'yy-mm-dd'});
	$("#edate").datepicker({dateFormat:'yy-mm-dd'});
//-->
</SCRIPT>
						</table>
						<p style="text-align:center;margin-top:-10px;"><span class="button red"><input type="submit" value="검색결과보기" /></span> <span class="button"><input type="button" value="전체보기" onclick="location.href='member_list.php';" /></span></p>
						</form>
					</div>

					<!-- Button -->
					<div id="btn_top">
						<span class="button"><a href="member_excel.php?search=<?=urlencode($where)?>">EXCEL</a></span>
						<span class="button blue"><a href="member_write.php">회원등록</a></span>
						<!-- <span class="button black"><a href="#" onclick="check_del(document.member,'idx[]');return false;">선택삭제</a></span> -->
					</div>

					<!-- 리스트 -->
					<div class="tableStyle1">
					<form name="member" id="member" method="post" action="member_pro.php">
					<input type="hidden" name="mode" value="" />
						<table>
							<thead>
							<tr>
								<!-- <th width="3%"><input type="checkbox" style="border:0" onClick="check_all(this,'idx[]')"></th> -->
								<th width="5%">번호</th>
								<th>이름</th>
								<th width="15%">아이디</th>
								<th width="11%">회원등급</th>
								<th width="15%">이메일</th>
								<th width="10%">방문횟수</th>
								<th width="10%">최근접속일</th>
								<th width="10%">가입일</th>
								<th width="9%">관리</th>
							</tr>
							</thead>
							<tbody>
						<?
							$page=$_GET['page'];

							if(!$page) $page=1;
							$list_num=15; $page_num=10;

							$start_num=($page-1)*$list_num;

							$sql = "select count(idx) from tbl_member $where";

							$count=db_result($sql);

							$i=0;
							$pRs=db_query("select * from tbl_member $where order by regdate desc limit $start_num, $list_num");
							while($pList=db_fetch($pRs)){
								$num = ($page-1) * $list_num + $i;
								$sortnum = $count-$num;
						?>
							<tr>
								<!-- <td><input name="idx[]" type="checkbox" value="<?=$pList['idx']?>" /></td> -->
								<td><?=$sortnum?></td>
								<td><a href="#" onclick="memberPopup('<?=$pList['idx']?>');"><?=$pList['name']?></a></td>
								<td><?=$pList['userid']?></td>
								<td>
									<?
										$mList=db_select("select * from tbl_member_level where level_code='".$pList['member_level']."'");
										echo $mList['level_name'];
									?>
								</td>
								<td><?=$pList['email']?></td>
								<td><?=$pList['visit_num']?>회</td>
								<td><?=$pList['visit_time']?></td>
								<td><?=$pList['regdate']?></td>
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
</body>
</html>