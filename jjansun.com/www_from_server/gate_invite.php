<?
	include "./event/common/function.php";
	$start = getMillisecond();
	include "./event/common/db.php";
	include "./event/common/config.php";
	session_unset(); // 모든 세션변수를 언레지스터 시켜줌
	session_destroy(); // 세션해제함

	if($_REQUEST['ssn'])setCookie('referer',$_REQUEST['ssn'],0);
	include "./event/common/counter.php";



//		$ssn= $_COOKIE['SSN'];
//		$chance_type2= 'fromsns';
//		$user_type= 200;
//		$CHK= getToken(15);
//	#echo "$reg_ip, $ssn, $CHK, 'add', $chance_type2, $user_type";exit;
//	if( $_REQUEST['ssn']!='' && $_REQUEST['ssn']!=$_SESSION[SSN] ){
//		$chance_info= charge_chance($reg_ip, $ssn, $CHK, 'add', $chance_type2, $user_type);
//
////		$param			= array( 'mode'=>'CHARGE_CHANCE', 'chance_type'=>'add', 'chance_type2'=>'fromsns' );
////		$SEND_RESULT	= httpPost2("http://www.jjansun.com/_exec.php", $param);
//	}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
	<meta http-equiv="Cache-Control" content="no-cache">
	<meta name="format-detection" content="telephone=no, address=no, email=no">
	<title>짠순이게임방</title>
	<meta name="title" content="짠순이게임방" />
	<meta name="description" content="게임도 하고 솔트도 모아서 즉석 당첨의 기회를! 재테크, 이벤트, 경품, 치매예방" />
	<meta name="keywords" content="즉석당첨, 재테크, 치매예방, 짠순이, 게임, 스낵게임, 웹게임">
	<meta property="og:title" content="짠순이게임방">
	<meta property="og:image" content="http://jjansun.com/images/thumb.jpg">
	<meta property="og:description" content="게임도 하고 솔트도 모아서 즉석 당첨의 기회를! 재테크, 이벤트, 경품, 치매예방">
	<meta property="og:url" content="http://www.jjansun.com/gate.php?ssn=<?=$ssn?>">
	<meta name="twitter:card" content="summary">
	<meta name="twitter:title" content="짠순이게임방">
	<meta name="twitter:image" content="http://jjansun.com/images/thumb.jpg">
	<meta name="twitter:description" content="게임도 하고 솔트도 모아서 즉석 당첨의 기회를! 재테크, 이벤트, 경품, 치매예방">
	</head>
	<body>
<script type="text/javascript">
<!--
	location.replace("/");
//-->
</script>
	</body>
</html>