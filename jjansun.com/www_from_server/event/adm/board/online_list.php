<?
	include("../inc/head.php");
	include("./board_config.php");

	$b_id=anti_injection($_GET['b_id']);
	$search_key=anti_injection($_GET['search_key']);
	$search_val=anti_injection($_GET['search_val']);
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
				<h1><?=$bbs_name?></h1>
				<!-- 본문 -->
				<div class="content">

					<div class="tableSearch">
						<form name="search" method="get">
						<input type="hidden" name="b_id" value="<?=$b_id?>" />
						<table>
							<tr>
								<th width="10%">검색</th>
								<td>
									<select name="search_key" class="dSelect">
										<option value="name"<?=($search_key=="name" ? " selected":"")?>>이름</option>
										<option value="tel"<?=($search_key=="tel" ? " selected":"")?>>전화번호</option>
										<option value="hp"<?=($search_key=="hp" ? " selected":"")?>>휴대폰</option>
										<option value="email"<?=($search_key=="email" ? " selected":"")?>>이메일</option>
										<option value="title"<?=($search_key=="title" ? " selected":"")?>>제목</option>
									</select>
									<input type="text" name="search_val" id="search_val" class="dInput req" title="검색어" value="<?=$search_val?>" style="width:100px;" />
									<span class="button red"><input type="submit" value="검색결과보기" /></span>
									<span class="button"><input type="button" value="전체" onclick="location.href='?b_id=<?=$b_id?>';"/></span>
								</td>
							</tr>
						</table>
						</form>
					</div>

					<!-- Button -->
					<div id="btn_top">
						<span class="button black"><a href="#" onclick="check_del(document.contract,'idx[]');return false;">선택삭제</a></span>
					</div>

					<!-- 리스트 -->
					<div class="tableStyle1">
					<form name="contract" id="contract" method="post" action="online_pro.php?b_id=<?=$b_id?>">
					<input type="hidden" name="mode" value="" />
					<input type="hidden" name="b_id" value="<?=$b_id?>" />
						<table>
							<thead>
							<tr>
								<th width="3%"><input type="checkbox" style="border:0" onClick="check_all(this,'idx[]')"></th>
								<th width="5%">번호</th>
								<th>제목</th>
								<th width="9%">이름</th>
								<th width="8%">처리상황</th>
								<th width="11%">신청일</th>
								<th width="10%">관리</th>
							</tr>
							</thead>
							<tbody>
						<?
							$page=anti_injection($_GET['page']);
							if($search_key) $search_keyword=" where $search_key like '%$search_val%'";

							if(!$page) $page=1;
							$list_num=15; $page_num=10;

							$start_num=($page-1)*$list_num;

							$count=db_result("select count(idx) from tbl_online search_keyword");

							$i=0;
							$pRs=db_query("select * from tbl_online $search_keyword order by reg_date desc limit $start_num, $list_num");
							while($pList=db_fetch($pRs)){
								$num = ($page-1) * $list_num + $i;
								$sortnum = $count-$num;
						?>
							<tr>
								<td><input name="idx[]" type="checkbox" value="<?=$pList['idx']?>" /></td>
								<td><?=$sortnum?></td>
								<td class="left"><?=cutstr($pList['title'],50)?></td>
								<td><?=$pList['name']?></td>
								<td>
									<select class="dSelect" name="state" onChange="state_set('tbl_online','<?=$pList['idx']?>', 'state', this.value)">
										<option value="1"<?=($pList['state']=="1" ? " selected" : "")?>>처리중</option>
										<option style="background:#d0e8ff" value="2"<?=($pList['state']=="2" ? " selected" : "")?>>처리완료</option>
									</select>
								</td>
								<td><?=date("Y-m-d H:i",$pList['reg_date'])?></td>
								<td>
									<span class="button"><a href="online_write.php?b_id=<?=$b_id?>&idx=<?=$pList['idx']?>">보기</a></span>
									<span class="button"><a href="online_pro.php?mode=del&b_id=<?=$b_id?>&idx=<?=$pList['idx']?>" onclick="return really()">삭제</a></span>
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
							<? page_list($page, $count, $list_num, $page_num, "&b_id=$b_id&search_key=$search_key&search_val=$search_val") ?>
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