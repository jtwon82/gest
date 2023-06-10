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
				<h1>기간별 통계</h1>
				<!-- 본문 -->
				<div class="content">

					<!-- Button -->
					<div class="tableSearch">
						<form name="search" method="get">
						<table>
							<tr>
								<th width="15%">검색기간</th>
								<td width="35%">
									<span class="button"><input type="button" value="오늘" onclick="location.href='period_list.php?sdate=<?=date("Y-m-d")?>';"/></span>
									<input type="text" name="sdate" id="sdate" class="dInput req cal" title="검색시작일" value="<?=$sdate?>" style="width:60px;" /> <img src="../images/icon_calendar.gif" style="vertical-align:middle" class="cbtn" /> 부터 <input type="text" name="edate" id="edate" class="dInput req cal" title="검색종료일" value="<?=$edate?>" style="width:60px;" /> <img src="../images/icon_calendar.gif" style="vertical-align:middle" class="cbtn" /> 까지
								</td>

<SCRIPT LANGUAGE="JavaScript">
<!--
	$("#sdate").datepicker({dateFormat:'yy-mm-dd'});
	$("#edate").datepicker({dateFormat:'yy-mm-dd'});
//-->
</SCRIPT>								<th width="15%">키워드검색</th>
								<td>
									<select name="search_key" class="dSelect">
										<option value="ip" <?=($search_key=="ip" ? "selected" : "")?>>IP</option>
										<option value="site" <?=($search_key=="site" ? "selected" : "")?>>사이트</option>
									</select>
									<input type="text" name="search_val" class="dInput" value="<?=$search_val?>" />
									&nbsp;<span class="button red"><input type="submit" value="검색결과보기" /></span>
									<span class="button"><input type="button" value="전체" onclick="location.href='period_list.php';"/></span>
								</td>
							</tr>
						</table>
						</form>
					</div>

					<!-- 리스트 -->
					<div class="tableStyle1">
						<table>
							<thead>
							<tr>
								<th width="5%">번호</th>
								<th>접속주소</th>
								<th width="10%">아이피</th>
								<th width="13%">OS</th>
								<th width="10%">브라우저</th>
								<th width="12%">접속시간</th>
							</tr>
							</thead>
							<tbody>
						<?
							$page=$_GET['page'];

							if(!$page) $page=1;
							$list_num=50; $page_num=10;

							$start_num=($page-1)*$list_num;


							if($sdate) $searchSql[]="date>'".strtotime($sdate)."'";
							if($edate) $searchSql[]="date<'".strtotime($edate." +1 day")."'";

							if($search_val) $searchSql[]="$search_key like '%$search_val%'";

							if($searchSql) $where = " where ".implode(" and ",$searchSql);
							$count=db_result("select count(idx) from tbl_counter $where");

							$i=0;
							$Rs=db_query("select * from tbl_counter $where order by date desc limit $start_num, $list_num");
							while($pList=db_fetch($Rs)){
								$num = ($page-1) * $list_num + $i;
								$sortnum = $count-$num;
						?>
							<tr>
								<td><?=$sortnum?></td>
								<td style="text-align:left;padding-left:10px;height:50px;">
									<?=($pList['site']=="북마크/주소직접입력" ? "" : "<span style=\"color:#910d42\">".$pList['site']." / <span style=\"color:#c11258\">".$pList['keyword']."</span><br />")?></span>
									<a href="<?=($pList['referer']=="북마크/주소직접입력" ? "#" : $pList['referer']."\" target=\"_blank\"")?>" ><?=cutstr($pList['referer'],150)?></a>
								</td>
								<td><a href="?search_key=ip&search_val=<?=$pList['ip']?>"><?=$pList['ip']?></a></td>
								<td><?=$pList['os']?></td>
								<td><?=$pList['browser']?></td>
								<td><?=date("Y-m-d H:i:s",$pList['date'])?></td>
							</tr>
						<?
								$i++;
							}
						?>
							</tbody>
						</table>
					</div>
					<!-- paging -->
					<div id="paging">
						<p>
							<? page_list($page, $count, $list_num, $page_num, $url) ?>
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