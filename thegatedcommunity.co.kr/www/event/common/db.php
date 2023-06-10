<?
	session_start();
	header("Content-type:text/html; charset=utf-8");
	header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
	
	#DB 접속 정보
	//husw-0728.cafe24.com/WebMysql
	#$db_host='event-newmedia.co.kr:3306';
    if($_SERVER[SERVER_NAME]=='dev.co.kr'){
    	$db_host='45.115.155.51';
    } else {
	   $db_host='10.6.105.2';
    }
	$db_name='community';
	$db_id='community';
	$db_pw='community1132@';
	$connect = db_connect($db_host, $db_id, $db_pw, $db_name);

	
	if(isset($_COOKIE['SSN'])){
		$_SESSION[SSN] = $_COOKIE['SSN'];
	}else{
		$vv = md5(time() . rand());
		setcookie('SSN', $vv, time()+(60*60*24*365) );
		$_SESSION[SSN] = $vv;
	}
?>
