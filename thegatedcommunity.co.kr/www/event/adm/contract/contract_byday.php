<?
	include("../inc/head.php");

	$search_key=anti_injection($_GET['search_key']);
	$search_val=anti_injection($_GET['search_val']);
	$sdate=anti_injection($_GET['sdate']);
	$edate=anti_injection($_GET['edate']);

	if(!$sdate) $sdate= date('Y-m-d', strtotime('-60 day'));
	if(!$edate) $edate= date('Y-m-d');
?>
<script language="javascript">
$(document).ready(function(){
	$(".sdate").datepicker({dateFormat:'yy-mm-dd'});
	$(".edate").datepicker({dateFormat:'yy-mm-dd'});
});
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
				<? @include "contract_byday_".$_SESSION[LOGIN_ID].".php";?>
			</td>
		</tr>
	</table>
</div>
</body>
</html>