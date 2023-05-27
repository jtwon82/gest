<?
	include "./event/common/function.php";
	include "./event/common/db.php";
	include "./event/common/config.php";


	/////// DB에 들어갈 값들을 변환합니다.
	$mode			= anti_injection($_REQUEST['mode']);
	$page			= anti_injection($_REQUEST['page']);
	$idx			= anti_injection($_REQUEST['idx']);

	$chs			= anti_injection($_SESSION['chs']);
	$chk			= anti_injection($_COOKIE['chk']);
	
	$mobile			= anti_injection($_REQUEST['mobile']);
	$business_name	= anti_injection($_REQUEST['business_name']);
	$uname			= anti_injection($_REQUEST['uname']);
	$stratum_name	= anti_injection($_REQUEST['stratum_name']);
	$department_name= anti_injection($_REQUEST['department_name']);
	$addr1          = anti_injection($_REQUEST['addr1']);
	$addr2          = anti_injection($_REQUEST['addr2']);
	$email	        = anti_injection($_REQUEST['email']);
	
	$pno1	        = anti_injection($_REQUEST['pno1']);
	$pno2           = anti_injection($_REQUEST['pno2']);
	$pno3           = anti_injection($_REQUEST['pno3']);
	$userType		= anti_injection($_REQUEST['userType']);
	$selMonth		= anti_injection($_REQUEST['selMonth']);
	$selDay		    = anti_injection($_REQUEST['selDay']);
	$selHour		= anti_injection($_REQUEST['selHour']);
	
	$referer		= anti_injection(base64_decode($_COOKIE['from']));
	$reg_ip			= anti_injection(getUserIp());

	$upfolder		= "";
	$folder			= explode("/",$_SERVER['PHP_SELF']);
	$folder			= 'univ20';
	$info[1]		= array('name'=>'EV1', 'edate'=>'2019-');
	$info[2]		= array('name'=>'EV2', 'edate'=>'2019-');
	$info[3]		= array('name'=>'EV3', 'edate'=>'2019-');

	/////// DB에 들어갈 값들을 정리합니다. 
	switch ($mode) {

		Case "REGIST":
			$sql = "INSERT INTO tbl_online_community(reg_date, reg_dates, reg_ip
                        , mobile, business_name, uname, stratum_name, department_name, addr1, addr2, pno1, email, userType, selMonth, selDay, selHour)
                    VALUES(now(), left(now(),10), '$reg_ip', '$mobile', '$business_name', '$uname', '$stratum_name', '$department_name', '$addr1', '$addr2', '$pno1', '$email', '$userType', '$selMonth', '$selDay', '$selHour')
                ";
			
			db_query($sql);
			
			echo json_encode( array('result'=>'regist') );
		break;

		Case "CLEAR" :
			$sql = "
					";
			#db_query($sql);
		break;



	}
	include "./event/common/dbclose.php";


?>