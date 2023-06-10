<?
	include("../inc/head.php");
?>
<script type="text/javascript">
<!--
	$(document).ready(function(){
		$(".datepicker").datepicker({dateFormat:'yy-mm-dd'});

		$(".val").on("change", function () {
			var thiss = this;
			if (thiss.files && thiss.files[0]) {
				var file = thiss.files[0];
				var idx= $(thiss).data('idx');

				var fd = new FormData();
				fd.append('mode', 'UPDATE');
				fd.append('idx', idx);
				fd.append('file', file);
				$.ajax({
					url: "event_winner_gift_pro.php",
					type: "POST",
					data: fd,
					dataType: "json",processData: false,contentType: false
					,beforeSend: function( xhr ) {
					}
					,success : function( xhr ) {
					}
				}).done(function (req) {
					location.reload();
				});
			}
		});
		
		$(".freviewimg").hover(function(){
			$(this).append("<img src='"+$(this).data('imgsrc')+"' width='200' style='border:1px solid black; padding:10px; position:absolute; background:white;'/>");
		},function(){
			$(this).find('img').remove();
		});;
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
				<h1>경품관리</h1>
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
								<th width="20%">이벤트구분</th>
								<th width="20%">gift</th>
								<th width="">날짜</th>
							</tr>
							</thead>
							<tbody>
						<?
							$i=0;
							$pRs=db_query("
								select c.codes, a.title data2, c.title win_type, c.val win_img
								from tbl_common a join tbl_common b on a.codes=b.pcodes and instr(a.title,'use')=1
								join tbl_common c on b.codes=c.pcodes and b.title='gift'");
							while($pList=db_fetch($pRs)){
								$pList['idx']= $pList['codes'];
						?>
							<tr>
								<td><input name="idx[]" type="checkbox" value="<?=$pList['idx']?>" /></td>
								<td><input type="text" class='dInput w50 ' name="data2" value="<?=$pList['data2']?>" readonly></td>
								<td><input type="text" class='dInput w50 ' name="data2" value="<?=$pList['win_type']?>" readonly></td>
								<td><input type="file" class='dInput val' name="val" data-idx='<?=$pList['idx']?>' value="<?=$pList['val']?>" >
									<br><img src='/upfile/<?=$pList['win_img']?>' width=50 height=50>
									<a data-imgsrc="/upfile/<?=$pList['win_img']?>" class='freviewimg' >IMG</a>
								</td>
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
