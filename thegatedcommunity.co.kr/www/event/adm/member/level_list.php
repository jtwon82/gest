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
				<h1>회원등급</h1>
				<!-- 본문 -->
				<div class="content">

					<!-- Button -->
					<div id="btn_top">
						<span class="button blue"><a href="level_write.php">등록</a></span>
					</div>

					<!-- 리스트 -->
					<div class="tableStyle1">
					<form name="contract" id="contract" method="post" action="contract_pro.php">
					<input type="hidden" name="mode" value="" />
						<table>
							<thead>
							<tr>
								<th width="20%">등급명</th>
								<th width="20%">등급코드</th>
								<th width="20%">회원수</th>
								<th width="20%">로그인가능여부</th>
								<th>관리</th>
							</tr>
							</thead>
							<tbody>
						<?
							$pRs=db_query("select * from tbl_member_level order by idx");
							while($pList=db_fetch($pRs)){
								$num = ($page-1) * $list_num + $i;
								$sortnum = $count-$num;

								//등급별 회원수
								$member_cnt=db_result("select count(idx) from tbl_member where userid <> '".$MASTER_UID."' and member_level='".$pList['level_code']."'");
						?>
							<tr>
								<td><?=$pList['level_name']?></td>
								<td><?=$pList['level_code']?></td>
								<td><?=$member_cnt?> 명</td>
								<td><?=($pList['login']=="Y" ? "허용" : "차단")?></td>
								<td>
								<?
									if($pList['level_code']>500){
								?>
									<span class="button"><a href="level_write.php?idx=<?=$pList['idx']?>">수정</a></span>
									<span class="button"><a href="level_pro.php?mode=del&idx=<?=$pList['idx']?>" onclick="return really()">삭제</a></span>
								<?
									}else{
								?>
									<span class="help" style="margin:0">기본등급</span>
								<?
									}
								?>

								</td>
							</tr>
						<?
							}
						?>
							</tbody>
						</table>
					</form>
					</div>

				</div>
				<!-- end -->
			</td>
		</tr>
	</table>
</div>
</body>
</html>