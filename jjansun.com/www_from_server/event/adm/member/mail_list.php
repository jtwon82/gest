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
				<h1>메일관리</h1>
				<!-- 본문 -->
				<div class="content">

					<!-- Button -->
					<div id="btn_top">
						<span class="button blue"><a href="mail_private.php">개별발송</a></span>
						<span class="button green"><a href="mail_group.php">대량발송</a></span>
						<span class="button black"><a href="#" onclick="check_del(document.mail,'idx[]');return false;">선택삭제</a></span>
					</div>

					<!-- 리스트 -->
					<div class="tableStyle1">
					<form name="mail" id="mail" method="post" action="mail_pro.php">
					<input type="hidden" name="mode" value="check_del" />
						<table>
							<thead>
							<tr>
								<th width="3%"><input type="checkbox" style="border:0" onClick="check_all(this,'idx[]')"></th>
								<th width="5%">번호</th>
								<th width="8%">발송유형</th>
								<th>메일제목</th>
								<th width="24%">발송인</th>
								<th width="7%">전송수</th>
								<th width="9%">발송일</th>
								<th width="7%">관리</th>
							</tr>
							</thead>
							<tbody>
						<?
							$page=$_GET['page'];

							if(!$page) $page=1;
							$list_num=15; $page_num=10;

							$start_num=($page-1)*$list_num;

							$count=db_result("select count(idx) from tbl_member_mail");

							$i=0;
							$pRs=db_query("select * from tbl_member_mail order by reg_date desc limit $start_num, $list_num");
							while($pList=db_fetch($pRs)){
								$num = ($page-1) * $list_num + $i;
								$sortnum = $count-$num;
						?>
							<tr>
								<td><input name="idx[]" type="checkbox" value="<?=$pList['idx']?>" /></td>
								<td><?=$sortnum?></td>
								<td><?=($pList['type']==1 ? "개별발송" : "대량발송")?></td>
								<td><?=$pList['title']?></td>
								<td><?=$pList['from_name']."&lt;".$pList['from_email']."&gt;"?></td>
								<td><?=$pList['mail_cnt']?>건</td>
								<td><?=date("Y-m-d",$pList['reg_date'])?></td>
								<td>
									<span class="button"><a href="mail_pro.php?mode=del&idx=<?=$pList['idx']?>" onclick="return really()">삭제</a></span>
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