<?
	include "../../event/common/function.php";
	include "../../event/common/db.php";
	include "../../event/common/config.php";
	include_once("./facebook.php");

	/* Remove no longer needed request tokens */
	unset($_SESSION['USER']["fb_".$facebook_appId."_access_token"]);
	unset($_SESSION['USER']["fb_".$facebook_appId."_user_id"]);

	// Create our Application instance (replace this with your appId and secret).
	$facebook = new Facebook(array(
		'appId'  => $facebook_appId,
		'secret' => $facebook_secret,
		'cookie' => true,
	));

	// Get User ID
	$user = $facebook->getUser(); // App에 로그인이 되어 있는지 확인하기 위해서 유저의 정보를 요청합니다.

	// Login or logout url will be needed depending on current user state.
	if ($user) { // 만일 유저정보가 있다면 해당정보 디비에 입력
print_r($user);exit;
		try {
			// Proceed knowing you have a logged in user who's authenticated.
			$user_profile = $facebook->api('/me'); // 유저 프로필을 가져 옵니다.
			$_mb_facebook_access_token = "fb_".$facebook_appId."_access_token"; // 토큰ID
			$_mb_facebook_user_id = "fb_".$facebook_appId."_user_id"; // 유저ID
			// 페이스북에서는 로그인정보(access_token, user_id)가 fb_App ID_access_token 형식으로 세션에 저장됩니다.

			//첫 로그인시 회원가입
			if(db_count("tbl_member","email='".$user_profile[email]."'","idx")<1){

				$_SESSION['USER']['FB_NAME'] = $user_profile["name"];
				$_SESSION['USER']['FB_EMAIL'] = $user_profile["email"];

				move_page("../joinus.php");

			}else{

				$rs = db_select("select * from tbl_member where email = '".$user_profile[email]."'");

				//로그인 가능 여부
				$login_auth=db_result("select login from tbl_member_level where level_code='$rs[member_level]'");
				if($login_auth=="Y"){

					$_SESSION['USER']['LOGIN_NO'] = $rs["idx"];
					$_SESSION['USER']['LOGIN_ID'] = $rs["userid"];
					$_SESSION['USER']['LOGIN_NAME'] = $rs["name"];
					$_SESSION['USER']['LOGIN_EMAIL'] = $rs["email"];
					$_SESSION['USER']['LOGIN_LEVEL'] = $rs["member_level"];

					//방문횟수 증가
					db_query("update tbl_member set visit_time='".time()."', visit_ip='".$_SERVER['REMOTE_ADDR']."', visit_num=visit_num+1 where idx='".$rs['idx']."'");
					move_page("../../main/main.php");

				}else{
					msg_page("회원접속 권한이 없습니다.","/");
				}
			}

		} catch (FacebookApiException $e) {
			//error_log($e);
			$user = null;

			$loginUrl = $facebook->getLoginUrl( array(
			'scope' => 'public_profile,email'
			));
		}
	} else { // 만일 유저정보가 없다면 페이스북 로그인 URL을 생성
		$loginUrl = $facebook->getLoginUrl( array(
			'scope' => 'public_profile,email'
		));
		//$loginUrl="http://jungle.tendencydev.co.kr/member/joinus.php";
	}

	// 로그인이동
	header('Location: '.$loginUrl);
	exit;
?>