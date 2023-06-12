<?
	include("../inc/head.php");

	$sdate= date('Y-m-d');
?>
<script type="text/javascript">
<!--
	$(document).ready(function(){
		$(".datepicker").datepicker({dateFormat:'yy-mm-dd'});
	});
	function setData(action, idx, fld, value){
		$.ajax({
			type: 'POST',
			url: 'event_winner_cnt_pro.php',
			data: {
				'mode' : action,
				'idx' : idx,
				'fld' : fld,
				'value' : value
			},
			dataType:"json",
			success: function(req) {
				console.log( req );
			}
		});
	}
//-->
</script>
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
				<h1>일자별당첨자</h1>
				<!-- 본문 -->
				<div class="content">

					<!-- 리스트 -->
					<div class="tableStyle1">
					<table>
							<thead>
							<tr>
								<th width="3%"> </th>
								<th width="5%">이벤트구분</th>
								<th width="10%">날짜</th>
								<th width="">세션</th>
								<th width="">당첨제한(분)</th>
								<th width="">당첨확율</th>
								<th width="">스타벅스커피</th>
								<th width="">Npay5000</th>
								<th width="">아이스크림</th>
								<th width="">Npay1000</th>
								<th width="">gs10000</th>
								<th width="">바나나우유</th>
								<th width="">경품7</th>
								<th width="">경품8</th>
								<th width="">경품9</th>
								<th width="">경품A</th>
								<th width="2%"> </th>
							</tr>
							</thead>
							<tbody>
							<tr>
							<form name="form" id="form" action="event_winner_cnt_pro.php" method="post">
							<input type="hidden" name="mode" value="INSERT">
								<td> </td>
								<td><select name="event_gubun">
								<?
									#$listUse= db_select_assoc("select * from tbl_common a where pcodes=17");
							$pRs=db_query("select * from tbl_common a where pcodes=17");
							while($row=db_fetch($pRs)){
										echo "<option value='".$row[title]."'>".$row[title]."(".$row[val].")</option>";
									}
								?>
								</select></td>
								<td><input type="text" class='dInput datepicker' name="reg_dates" value="<?=$sdate?>"></td>
								<td><input type="text" class='dInput w50' name="ssn" value="30"></td>
								<td><input type="text" class='dInput w50' name="winner" value="200"></td>
								<td><input type="text" class='dInput w50' name="pct" value="40"></td>
								<td>
									<input type="text" class='dInput w50' name="gift" value="0">개<br><=
									<input type="text" class='dInput w50' name="gift_pct" value="10"></td>
								<td>
									<input type="text" class='dInput w50' name="gift2" value="0">개<br><=
									<input type="text" class='dInput w50' name="gift2_pct" value="20"></td>
								<td>
									<input type="text" class='dInput w50' name="gift3" value="0">개<br><=
									<input type="text" class='dInput w50' name="gift3_pct" value="30"></td>
								<td>
									<input type="text" class='dInput w50' name="gift4" value="0">개<br><=
									<input type="text" class='dInput w50' name="gift4_pct" value="40"></td>
								<td>
									<input type="text" class='dInput w50' name="gift5" value="0">개<br><=
									<input type="text" class='dInput w50' name="gift5_pct" value="50"></td>
								<td>
									<input type="text" class='dInput w50' name="gift6" value="0">개<br><=
									<input type="text" class='dInput w50' name="gift6_pct" value="100"></td>
								<td>
									<input type="text" class='dInput w50' name="gift7" value="0">개<br><=
									<input type="text" class='dInput w50' name="gift7_pct" value="0"></td>
								<td>
									<input type="text" class='dInput w50' name="gift8" value="0">개<br><=
									<input type="text" class='dInput w50' name="gift8_pct" value="0"></td>
								<td>
									<input type="text" class='dInput w50' name="gift9" value="0">개<br><=
									<input type="text" class='dInput w50' name="gift9_pct" value="0"></td>
								<td>
									<input type="text" class='dInput w50' name="giftA" value="0">개<br><=
									<input type="text" class='dInput w50' name="giftA_pct" value="0"></td>
								<td><span class="button black"><a href="#" onclick="document.form.submit();">추가</a></span></td>
							</form>
							</tr>
					</table>

					<!-- Button -->
					<div id="btn_top">
						<span class="button black"><a href="#" onclick="check_del(document.popup,'idx[]');return false;">선택삭제</a></span>
					</div>
					<form name="popup" id="popup" method="post" action="event_winner_cnt_pro.php">
					<input type="hidden" name="mode" value="" />
						<table>
							<thead>
							<tr>
								<th width="3%"><input type="checkbox" style="border:0" onClick="check_all(this,'idx[]')"></th>
								<th width="5%">이벤트구분</th>
								<th width="10%">날짜</th>
								<th width="">세션</th>
								<th width="">당첨제한(분)</th>
								<th width="">당첨확율</th>
								<th width="">스타벅스커피</th>
								<th width="">Npay5000</th>
								<th width="">아이스크림</th>
								<th width="">Npay1000</th>
								<th width="">gs10000</th>
								<th width="">바나나우유</th>
								<th width="">경품7</th>
								<th width="">경품8</th>
								<th width="">경품9</th>
								<th width="">경품A</th>
								<th width="2%"> </th>
							</tr>
							</thead>
							<tbody>
						<?
							$page=$_GET['page'];

							if(!$page) $page=1;
							$list_num=15; $page_num=10;

							$start_num=($page-1)*$list_num;

							#$count=db_result("select count(idx) from tbl_gift_config ");

							$i=0;
							$pRs=db_query("select * from tbl_gift_config order by reg_dates desc ");
							while($pList=db_fetch($pRs)){
								$num = ($page-1) * $list_num + $i;
								$sortnum = $count-$num;
								$idx = $pList['idx'];
						?>
							<tr bgcolor='<?=$pList[reg_dates]=='0000-01-01'?'#FFFFCC':''?>'>
								<td><input name="idx[]" type="checkbox" value="<?=$pList['idx']?>" /></td>
								<td><input type="text" class='dInput w50 ' name="event_gubun" value="<?=$pList['event_gubun']?>" onblur="setData('UPDATE', '<?=$pList['idx']?>', this.name, this.value)"></td>
								<td><input type="text" class='dInput  datepicker' name="reg_dates" value="<?=$pList['reg_dates']?>" onblur="setData('UPDATE', '<?=$pList['idx']?>', this.name, this.value)"></td>
								<td><input type="text" class='dInput w50' name="ssn" value="<?=$pList['ssn']?>" onblur="setData('UPDATE', '<?=$pList['idx']?>', this.name, this.value)"></td>
								<td><input type="text" class='dInput w50' name="winner" value="<?=$pList['winner']?>" onblur="setData('UPDATE', '<?=$pList['idx']?>', this.name, this.value)"></td>
								<td><input type="text" class='dInput w50' name="pct" value="<?=$pList['pct']?>" onblur="setData('UPDATE', '<?=$pList['idx']?>', this.name, this.value)"></td>
								<td>
									<input type="text" class='dInput w20' name="gift" value="<?=$pList['gift']?>" onblur="setData('UPDATE', '<?=$pList['idx']?>', this.name, this.value)">개 <br><=
									<input type="text" class='dInput w20' name="gift_pct" value="<?=$pList['gift_pct']?>" onblur="setData('UPDATE', '<?=$pList['idx']?>', this.name, this.value)"></td>
								<td>
									<input type="text" class='dInput w20' name="gift2" value="<?=$pList['gift2']?>" onblur="setData('UPDATE', '<?=$pList['idx']?>', this.name, this.value)">개 <br><=
									<input type="text" class='dInput w20' name="gift2_pct" value="<?=$pList['gift2_pct']?>" onblur="setData('UPDATE', '<?=$pList['idx']?>', this.name, this.value)"></td>
								<td>
									<input type="text" class='dInput w20' name="gift3" value="<?=$pList['gift3']?>" onblur="setData('UPDATE', '<?=$pList['idx']?>', this.name, this.value)">개 <br><=
									<input type="text" class='dInput w20' name="gift3_pct" value="<?=$pList['gift3_pct']?>" onblur="setData('UPDATE', '<?=$pList['idx']?>', this.name, this.value)"></td>
								<td>
									<input type="text" class='dInput w20' name="gift4" value="<?=$pList['gift4']?>" onblur="setData('UPDATE', '<?=$pList['idx']?>', this.name, this.value)">개 <br><=
									<input type="text" class='dInput w20' name="gift4_pct" value="<?=$pList['gift4_pct']?>" onblur="setData('UPDATE', '<?=$pList['idx']?>', this.name, this.value)"></td>
								<td>
									<input type="text" class='dInput w20' name="gift5" value="<?=$pList['gift5']?>" onblur="setData('UPDATE', '<?=$pList['idx']?>', this.name, this.value)">개 <br><=
									<input type="text" class='dInput w20' name="gift5_pct" value="<?=$pList['gift5_pct']?>" onblur="setData('UPDATE', '<?=$pList['idx']?>', this.name, this.value)"></td>
								<td>
									<input type="text" class='dInput w20' name="gift6" value="<?=$pList['gift6']?>" onblur="setData('UPDATE', '<?=$pList['idx']?>', this.name, this.value)">개 <br><=
									<input type="text" class='dInput w20' name="gift6_pct" value="<?=$pList['gift6_pct']?>" onblur="setData('UPDATE', '<?=$pList['idx']?>', this.name, this.value)"></td>
								<td>
									<input type="text" class='dInput w20' name="gift7" value="<?=$pList['gift7']?>" onblur="setData('UPDATE', '<?=$pList['idx']?>', this.name, this.value)">개 <br><=
									<input type="text" class='dInput w20' name="gift7_pct" value="<?=$pList['gift7_pct']?>" onblur="setData('UPDATE', '<?=$pList['idx']?>', this.name, this.value)"></td>
								<td>
									<input type="text" class='dInput w20' name="gift8" value="<?=$pList['gift8']?>" onblur="setData('UPDATE', '<?=$pList['idx']?>', this.name, this.value)">개 <br><=
									<input type="text" class='dInput w20' name="gift8_pct" value="<?=$pList['gift8_pct']?>" onblur="setData('UPDATE', '<?=$pList['idx']?>', this.name, this.value)"></td>
								<td>
									<input type="text" class='dInput w20' name="gift9" value="<?=$pList['gift9']?>" onblur="setData('UPDATE', '<?=$pList['idx']?>', this.name, this.value)">개 <br><=
									<input type="text" class='dInput w20' name="gift9_pct" value="<?=$pList['gift9_pct']?>" onblur="setData('UPDATE', '<?=$pList['idx']?>', this.name, this.value)"></td>
								<td>
									<input type="text" class='dInput w20' name="giftA" value="<?=$pList['giftA']?>" onblur="setData('UPDATE', '<?=$pList['idx']?>', this.name, this.value)">개 <br><=
									<input type="text" class='dInput w20' name="giftA_pct" value="<?=$pList['giftA_pct']?>" onblur="setData('UPDATE', '<?=$pList['idx']?>', this.name, this.value)"></td>
								<td> </td>
							</tr>
						<?
								$i++;
							}
							if($i==0){
						?>
							<tr>
								<td colspan="11">등록된 내용이 없습니다.</td>
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
							<? #page_list($page, $count, $list_num, $page_num, $url) ?>
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
