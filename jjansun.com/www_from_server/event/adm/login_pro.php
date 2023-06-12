<?
	include("../common/function.php");
	include("../common/db.php");
	include("../common/config.php");

	$uid		= anti_injection(trim($_POST['uid']?$_POST['uid']:$_GET['uid']));
	$pwd		= anti_injection(trim($_POST['pwd']?$_POST['pwd']:$_GET['pwd']));
	$SaveID		= anti_injection(trim($_POST['SaveID']));

	if(!$uid || !$pwd){

		msg_page("로그인에 실패하였습니다.");

	}else{

		$cnt = db_count("tbl_member","member_level<=100 and userid  = '".$uid."'","userid");

		if($cnt >  0 ) {

			$pcnt = db_count("tbl_member","userid = '".$uid."' and passwd = password('".$pwd."')","userid");
			$rs = db_select("select * from tbl_member where userid = '".$uid."' and passwd = password('".$pwd."')");

			if($pcnt > 0){
				$admin_no = trim($rs["idx"]);
				$admin_id = trim($rs["userid"]);
				$admin_name = trim($rs["name"]);
				$admin_email = trim($rs["email"]);
				$admin_level = trim($rs["member_level"]);

				/* 세션처리 */
				$_SESSION['LOGIN_NO'] = $admin_no;
				$_SESSION['LOGIN_ID'] = $admin_id;
				$_SESSION['LOGIN_NAME'] = $admin_name;
				$_SESSION['LOGIN_EMAIL'] = $admin_email;
				$_SESSION['LOGIN_LEVEL'] = $admin_level;

				//방문횟수 증가
				#db_query("update tbl_member set visit_date=now(), visit_ip='".$_SERVER['REMOTE_ADDR']."', visit_num=visit_num+1 where userid='".$_SESSION['LOGIN_ID']."'");

				move_page("/event/adm/manager/site_info.php");

			}else{
				msg_page("로그인 정보가 일치하지 않습니다.1");
			}

		}else{
			msg_page("로그인 정보가 일치하지 않습니다.2");
		}
	}
?>