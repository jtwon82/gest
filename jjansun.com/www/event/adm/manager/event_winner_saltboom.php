<?
	include("../inc/head.php");
?>
<script type="text/javascript">
<!--
	$(document).ready(function(){
		$(".datepicker").datepicker({dateFormat:'yy-mm-dd'});
	});
	function setData(action, idx, fld, value){
		$.ajax({
			type: 'POST',
			url: 'event_winner_saltboom_pro.php',
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
				<h1>솔트두배</h1>
				<!-- 본문 -->
				<div class="content">

					<!-- 리스트 -->
					<div class="tableStyle1">
					<!-- Button -->
					<div id="btn_top">
<!-- 						<span class="button black"><a href="#" onclick="check_del(document.popup,'idx[]');return false;">선택삭제</a></span> -->
					</div>
					<form name="popup" id="popup" method="post" action="event_winner_cnt_pro.php">
					<input type="hidden" name="mode" value="" />
						<table>
							<thead>
							<tr>
								<th width="3%"><input type="checkbox" style="border:0" onClick="check_all(this,'idx[]')"></th>
								<th width="10%">구분</th>
								<th width="10%">이름</th>
								<th width="">배수</th>
							</tr>
							</thead>
							<tbody>
						<?
							$i=0;
							$pRs=db_query("select b.codes, a.title data2, a.val gamename, b.title, b.val from tbl_common a join tbl_common b on a.codes=b.pcodes where b.title ='saltboom'");
							while($pList=db_fetch($pRs)){
								$pList['idx']= $pList['codes'];
						?>
							<tr>
								<td><input name="idx[]" type="checkbox" value="<?=$pList['idx']?>" /></td>
								<td><input type="text" class='dInput w50 ' name="data2" value="<?=$pList['data2']?>" onblur="setData('UPDATE', '<?=$pList['idx']?>', this.name, this.value)" readonly></td>
								<td><input type="text" class='dInput w150' name="gamename" value="<?=$pList['gamename']?>" onblur="setData('UPDATE', '<?=$pList['idx']?>', this.name, this.value)" readonly ></td>
								<td><input type="number" class='dInput ' name="val" value="<?=$pList['val']?>" onblur="setData('UPDATE', '<?=$pList['idx']?>', this.name, this.value)"> 배</td>
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
