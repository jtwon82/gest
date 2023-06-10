<?
	include("../inc/head.php");
	#include("../../calendar/calendar.html");

	$page=anti_injection($_GET['page']);
	$search_key=anti_injection($_GET['search_key']);
	$search_val=anti_injection($_GET['search_val']);
	$otype=anti_injection($_GET['otype']);


	$searchSql[] = "c.state != 5"; //삭제된 데이터

	if($search_key && $search_val) $searchSql[] = "$search_key like '%$search_val%'";
	if($searchSql) $where = " where ".implode(" and ",$searchSql);

	//상품진열순서
	switch($otype){
		case "hit" : $ordersql="c.hit desc";break;
		case "com" : $ordersql="c.comment desc";break;
		case "thank" : $ordersql="c.thankyou desc";break;
		default : $ordersql="c.reg_date desc";
	}
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
				<h1>컨텐츠리스트</h1>
				<!-- 본문 -->
				<div class="content">

					<div class="tableSearch">
						<form name="search" method="get">
						<table>
							<tr>
								<th>검색어</th>
								<td>
									<select name="search_key" class="dSelect">
										<option value="c.title"<?=($search_key=="c.title" ? " selected":"")?>>제목</option>
										<option value="c.tag"<?=($search_key=="c.tag" ? " selected":"")?>>태그</option>
										<option value="m.uname"<?=($search_key=="m.uname" ? " selected":"")?>>작성자명</option>
										<option value="m.userid"<?=($search_key=="m.userid" ? " selected":"")?>>작성자ID</option>
									</select>
									<input type="text" name="search_val" id="search_val" class="dInput req" title="검색어" value="<?=$search_val?>" style="width:100px;" />
								</td>
							</tr>
							<tr>
								<th>정렬순서</th>
								<td>
									<span class="button small<?=($otype==""?" black":"")?>"><a href="content_list.php">최신등록순</a></span>
									<span class="button small<?=($otype=="hit"?" black":"")?>""><a href="content_list.php?otype=hit">조회수</a></span>
								</td>
							</tr>
						</table>
						<p style="text-align:center;margin-top:-10px;"><span class="button red"><input type="submit" value="검색결과보기" /></span> <span class="button"><input type="button" value="전체보기" onclick="location.href='content_list.php';" /></span></p>
						</form>
					</div>

					<!-- Button -->
					<div id="btn_top">
						<span class="button blue"><a href="content_dom_write.php">등록</a></span>
						<span class="button black"><a href="#" onclick="check_del(document.content,'idx[]');return false;">선택삭제</a></span>
					</div>

					<!-- 리스트 -->
					<div class="tableStyle1">
					<form name="content" id="content" method="post" action="content_pro.php">
					<input type="hidden" name="mode" value="" />
						<table>
							<thead>
							<tr>
								<th width="3%"><input type="checkbox" style="border:0" onClick="check_all(this,'idx[]')"></th>
								<th width="5%">번호</th>
								<th>제목</th>
								<th width="8%">상태</th>
								<th width="8%">등록일</th>
								<th width="8%">최종일</th>
								<th width="11%">관리</th>
							</tr>
							</thead>
							<tbody>
						<?
							$page=$_GET['page'];

							if(!$page) $page=1;
							$list_num=15; $page_num=10;
							$start_num=($page-1)*$list_num;

							$count=db_result("select count(c.idx) from tbl_content_dom c $where");

							$i=0;
							$pRs=db_query("select c.* from tbl_content_dom c $where order by $ordersql limit $start_num, $list_num");
							while($pList=db_fetch($pRs)){
								$num = ($page-1) * $list_num + $i;
								$sortnum = $count-$num;

						?>
							<tr>
								<td><input name="idx[]" type="checkbox" value="<?=$pList['idx']?>" /></td>
								<td><?=$sortnum?></td>
								<td style="text-align:left;">
									<?=$pList['title']?>
								</td>
								<td><?=$pList['state']?></td>
								<td><?=$pList['reg_dates']?></td>
								<td><?=$pList['update_dates']?></td>
								<td>
									<span class="button"><a href="content_dom_write.php?idx=<?=$pList['idx']?>">수정</a></span>
									<span class="button"><a href="content_dom_pro.php?mode=del&idx=<?=$pList['idx']?>" onclick="return really()">삭제</a></span>
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
							<? page_list($page, $count, $list_num, $page_num, "search_key=$search_key&search_val=$search_val") ?>
						</p>
					</div>
				</div>
				<!-- end -->
			</td>
		</tr>
	</table>
</div>
<iframe name="iframe" width="0" height="0"></iframe>
</body>
</html>