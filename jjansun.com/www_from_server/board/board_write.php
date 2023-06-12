<?
	include "../event/common/function.php";
	include "../event/common/db.php";
	include "../event/common/config.php";
	include("../event/adm/board/board_config.php");

	$mode			= anti_injection($_REQUEST['mode']);
	$page			= anti_injection($_REQUEST['page']);
	$b_code			= anti_injection($_REQUEST['b_code']);
	$search_key		= anti_injection($_REQUEST['search_key']);
	$search_val		= anti_injection($_REQUEST['search_val']);
	$idx			= anti_injection($_REQUEST['idx']);

	if($mode=="modify"){
		$pList=db_select("select * from tbl_board where idx='".$idx."'");
		$b_code=$pList['b_code'];
		$title=$pList['title'];
		$name=$pList['name'];
		$content=$pList['content'];
		$re_level=$pList['re_level'];
		if($_SESSION['USER'][LOGIN_NO]!=$pList[userno]){
			msg_page("잘못된 접근입니다.");
		}
	}
	if(!$pList){
		$pList[name]=$_SESSION['USER'][LOGIN_NAME];
	}

	if(!$name) $name=$SITE_INFO['manager'];
?>


			<? include "../head.php"; ?>
			<? include "../head.gnb.php"; ?>

<link rel="stylesheet" href="//code.jquery.com/ui/1.10.0/themes/base/jquery-ui.css" />
<script src="//code.jquery.com/ui/1.10.0/jquery-ui.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.js"></script>
<SCRIPT LANGUAGE="JavaScript">
<!--
	$.datepicker.regional['ko'] = {
		closeText: '닫기',prevText: '이전달',nextText: '다음달',currentText: '오늘',
		monthNames: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
		monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
		dayNames: ['일','월','화','수','목','금','토'],
		dayNamesShort: ['일','월','화','수','목','금','토'],
		dayNamesMin: ['일','월','화','수','목','금','토'],
		buttonImageOnly: false,weekHeader: 'Wk',dateFormat: 'yy-mm-dd',firstDay: 0,
		isRTL: false,duration:200,showAnim:'show',showMonthAfterYear:false
	};
	$.datepicker.setDefaults($.datepicker.regional['ko']);
//-->
</SCRIPT>
			<? include "./{$bbs_skin}/write.php";?>

			<? include "../tail.popup.php"; ?>
			<? include "../tail.php"; ?>
