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
				<h1>탈퇴회원관리</h1>
				<!-- 본문 -->
				<div class="content">

					<!-- Button -->
					<div id="btn_top">
						<span class="button black"><a href="#" onclick="check_del(document.member,'idx[]');return false;">선택삭제</a></span>
					</div>

					<!-- 리스트 -->
					<div class="tableStyle1">
					<form name="member" id="member" method="post" action="out_pro.php">
					<input type="hidden" name="mode" value="" />
						<table>
							<thead>
							<tr>
								<th width="3%"><input type="checkbox" style="border:0" onClick="check_all(this,'idx[]')"></th>
								<th width="5%">번호</th>
								<th width="9%">이름</th>
								<th width="9%">아이디</th>
								<th width="9%">회원등급</th>
								<th width="9%">가입일</th>
								<th width="9%">탈퇴일</th>
								<th>탈퇴사유</th>
								<th width="15%">관리</th>
							</tr>
							</thead>
							<tbody>
						<?
							$page=$_GET['page'];

							if(!$page) $page=1;
							$list_num=15; $page_num=10;

							$start_num=($page-1)*$list_num;

							$count=db_result("select count(idx) from tbl_member_del $where");

							$i=0;
							$pRs=db_query("select * from tbl_member_del $where order by regdate desc limit $start_num, $list_num");
							while($pList=db_fetch($pRs)){
								$num = ($page-1) * $list_num + $i;
								$sortnum = $count-$num;
						?>
							<tr>
								<td><input name="idx[]" type="checkbox" value="<?=$pList['idx']?>" /></td>
								<td><?=$sortnum?></td>
								<td><?=$pList['name']?></td>
								<td><?=$pList['userid']?></td>
								<td>
									<?
										$mList=db_select("select * from tbl_member_level where level_code='".$pList['member_level']."'");
										echo $mList['level_name'];
									?>
								</td>
								<td><?=date("Y-m-d",$pList['regdate'])?></td>
								<td><?=date("Y-m-d",$pList['regdate'])?></td>
								<td class="left"><?=$pList['content']?></td>
								<td>
								<?
									if($pList['state']==1){
								?>
									<span class="button blue"><a href="out_pro.php?mode=out&userid=<?=$pList['userid']?>" onclick="return really_msg('탈퇴완료 처리하시겠습니까?');">회원탈퇴</a></span>
									<span class="button"><a href="out_pro.php?mode=cancel&idx=<?=$pList['idx']?>&userid=<?=$pList['userid']?>">탈퇴철회</a></span>
								<?
									}else{
								?>
									<span>탈퇴처리완료</span>
								<?
									}
								?>
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