<?
	session_start();
	include "./event/common/function.php";

	if (!preg_match('/www/', $_SERVER['HTTP_HOST']) == true) {
		#header("location: http://www.".$_SERVER['HTTP_HOST'].$REQUEST_URI);
	}

	if( $_SESSION['USER']['LOGIN_DATE']!='' && $_SESSION['USER']['LOGIN_DATE'] != date("Y-m-d") ){
		header("location: http://".$_SERVER['HTTP_HOST']."/member/logout.php");
	}

	#print_r($_SESSION);exit;

	$chkpage= array("/mypage.html");
	if($_SESSION[SSN]==''){
		if(in_array($_SERVER[PHP_SELF], $chkpage)){
			#header("location: /");
			msg_page("로그인후 이용가능합니다.", "/login.html");
		}
	}


?>