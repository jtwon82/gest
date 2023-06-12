<?
	include("../inc/head.php");
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
				<h1>BUILDER</h1>
				<!-- 본문 -->
				<div class="content">

					<!-- Button -->
					<div id="btn_top">
						<span class="button blue"><a href="board_write.php">등록</a></span>
						<span class="button black"><a href="#" onclick="check_del(document.board,'idx[]');return false;">선택삭제</a></span>
					</div>

					<!-- 리스트 -->
					<div class="tableStyle1">
					<form name="board" id="board" method="post" action="board_pro.php">
					<input type="hidden" name="mode" value="" />
						<table>
							<thead>
							<tr>
								<th width="3%"><input type="checkbox" style="border:0" onClick="check_all(this,'idx[]')"></th>
								<th width="7%">번호</th>
								<th width="13%">게시판코드</th>
								<th>게시판명</th>
								<th width="15%">스킨</th>
								<th width="15%">게시판형태</th>
								<th width="15%">등록글수</th>
								<th width="17%">관리</th>
							</tr>
							</thead>
							<tbody>
						<?
							$page=$_GET['page'];

							if(!$page) $page=1;
							$list_num=50; $page_num=10;
							$start_num=($page-1)*$list_num;

							$count=db_result("select count(idx) from tbl_board_config");

							$i=0;
							$pRs=db_query("select * from tbl_board_config order by idx desc limit $start_num, $list_num");
							while($pList=db_fetch($pRs)){
								$num = ($page-1) * $list_num + $i;
								$sortnum = $count-$num;
						?>
							<tr>
								<td><input name="idx[]" type="checkbox" value="<?=$pList['idx']?>" /></td>
								<td><?=$sortnum?></td>
								<td><?=$pList['b_id']?></td>
								<td><?=$pList['b_name']?></td>
								<td><?=$pList['skin']?></td>
								<td><?=$bbsTypeArray[$pList['b_type']][0]?></td>
								<td><?=db_result("select count(idx) from tbl_board where b_id='".$pList['b_id']."'")." 개";?></td>
								<td>
									<span class="button"><a href="../board/board_list.php?b_id=<?=$pList['b_id']?>">보기</a></span>
									<span class="button"><a href="board_write.php?idx=<?=$pList['idx']?>">수정</a></span>
									<span class="button"><a href="board_pro.php?mode=del&idx=<?=$pList['idx']?>" onclick="return really()">삭제</a></span>
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