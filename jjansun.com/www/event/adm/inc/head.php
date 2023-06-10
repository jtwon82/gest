<?
	include "../../common/function.php";
	include "../../common/db.php";
	include "../../common/config.php";
	include "../session.php";

	//관리자 페이지 이미지
	$page_first_page="<img src=\"../images/icon_prev2.gif\" />";
	$page_post_start="<img src=\"../images/icon_prev.gif\" />";
	$page_next_start="<img src=\"../images/icon_next.gif\" />";
	$page_last_page="<img src=\"../images/icon_next2.gif\" />";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="ko" xml:lang="ko">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=10" />
<meta name="viewport" content="width=device-width, user-scalable=no">
<title>관리자 :::: <?=$SITE_INFO['title']?></title>

<link rel="stylesheet" href="//code.jquery.com/ui/1.10.0/themes/base/jquery-ui.css" />
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script src="//code.jquery.com/ui/1.10.0/jquery-ui.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.js"></script>

<script type="text/javascript" src="../../js/global.js"></script>
<script type="text/javascript" src="../js/admin.js"></script>
<link href="../css/layout.css?<=rand()?>" rel="stylesheet" type="text/css">
<link href="../css/button.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
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
</head>