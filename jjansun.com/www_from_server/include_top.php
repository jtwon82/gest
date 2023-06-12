<?
	session_start();
	include "./event/common/function.php";
	include "./event/common/db.php";
	include "./event/common/counter.php";

	if (!$_SERVER[WINDIR] && $_SERVER[SERVER_PORT] != 443){
		header('Location: https://'.$_SERVER['HTTP_HOST'].'/'. $_SERVER[QUERY_STRING]);
	}

	if (!$_SERVER[SERVER_NAME]=='weespk.iptime.org' && !preg_match('/www/', $_SERVER['HTTP_HOST']) == true) {
		header("location: https://www.".$_SERVER['HTTP_HOST'].$REQUEST_URI);
	}

	if( $_SESSION['USER']['LOGIN_DATE']!='' && $_SESSION['USER']['LOGIN_DATE'] != date("Y-m-d") ){
		header("location: https://".$_SERVER['HTTP_HOST']."/member/logout.php");
	}

	$sql= "
		select case when '$_SESSION[loginchk]'=loginchk then 'o' else 'x' end loginchk
		from tbl_loginchk l
		where idx=(select max(idx) from tbl_loginchk l where ssn='$_SESSION[SSN]')
		";
	$loginchk= db_select($sql);
	
	if($loginchk[loginchk]=='x'){
		#echo $_SESSION[SSN];exit;
		msg_page("다른 브라우저에서 로그인 했습니다.", "/member/logout.php");
	}

	$chkpage= array("/mypage.html","/more_addchannel.html","/more_coupon.html");
	if($_SESSION[SSN]==''){
		if(in_array($_SERVER[PHP_SELF], $chkpage)){
			$_SESSION[returnUrl]= $_SERVER[HTTP_REFERER];
			msg_page("로그인후 이용가능합니다.", "/login.html");
			exit;
		}
	}


?>