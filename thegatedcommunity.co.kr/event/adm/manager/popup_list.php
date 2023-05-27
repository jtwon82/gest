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
				<h1>팝업관리</h1>
				<!-- 본문 -->
				<div class="content">

					<!-- Button -->
					<div id="btn_top">
						<span class="button blue"><a href="popup_write.php">등록</a></span>
						<span class="button black"><a href="#" onclick="check_del(document.popup,'idx[]');return false;">선택삭제</a></span>
					</div>

					<!-- 리스트 -->
					<div class="tableStyle1">
					<form name="popup" id="popup" method="post" action="popup_pro.php">
					<input type="hidden" name="mode" value="" />
						<table>
							<thead>
							<tr>
								<th width="3%"><input type="checkbox" style="border:0" onClick="check_all(this,'idx[]')"></th>
								<th width="7%">번호</th>
								<th>제목</th>
								<th width="10%">팝업유형</th>
								<th width="10%">노출여부</th>
								<th width="10%">등록일</th>
								<th width="15%">관리</th>
							</tr>
							</thead>
							<tbody>
						<?
							$page=$_GET['page'];

							if(!$page) $page=1;
							$list_num=15; $page_num=10;

							$start_num=($page-1)*$list_num;

							$count=db_result("select count(idx) from tbl_popup");

							$i=0;
							$pRs=db_query("select * from tbl_popup order by regdate desc limit $start_num, $list_num");
							while($pList=db_fetch($pRs)){
								$num = ($page-1) * $list_num + $i;
								$sortnum = $count-$num;
						?>
							<tr>
								<td><input name="idx[]" type="checkbox" value="<?=$pList['idx']?>" /></td>
								<td><?=$sortnum?></td>
								<td><?=$pList['title']?></td>
								<td>
									<select class="dSelect" name="style" onChange="popup_set('<?=$pList['idx']?>', 'style', this.value)">
										<option value="W"<?=($pList['style']=="W" ? " selected" : "")?>>윈도우팝업</option>
										<option value="L"<?=($pList['style']=="L" ? " selected" : "")?>>레이어팝업</option>
									</select>
								</td>
								<td>
									<select class="dSelect" name="open" onChange="popup_set('<?=$pList['idx']?>', 'open', this.value)">
										<option value="Y"<?=($pList['open']=="Y" ? " selected" : "")?>>노출</option>
										<option style="background:#dcdcdc" value="N"<?=($pList['open']=="N" ? " selected" : "")?>>중단</option>
									</select>
								</td>
								<td><?=date("Y-m-d",$pList['regdate'])?></td>
								<td>
									<span class="button"><a href="#" onclick="window.open('../../common/window_pop.php?idx=<?=$pList['idx']?>','pop<?=$pList['idx']?>','toolbar=no,width=<?=$pList['pwidth']?>,height=<?=$pList['pheight']+25?>,left=<?=$pList['left']?>,top=<?=$pList['top']?>');return false;">보기</a></span>
									<span class="button"><a href="popup_write.php?idx=<?=$pList['idx']?>">수정</a></span>
									<span class="button"><a href="popup_pro.php?mode=del&idx=<?=$pList['idx']?>" onclick="return really();">삭제</a></span>
								</td>
							</tr>
						<?
								$i++;
							}
							if($i==0){
						?>
							<tr>
								<td colspan="7">등록된 내용이 없습니다.</td>
							</tr>
						<?
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