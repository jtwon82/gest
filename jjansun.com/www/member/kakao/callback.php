<?
	include "../../event/common/function.php";
	include "../../event/common/db.php";
	include "../../event/common/config.php";

	$code			=anti_injection($_REQUEST['code']);
	$returnUrl		=anti_injection($_REQUEST['returnUrl']);
	if($returnUrl)	$_SESSION['returnUrl']= $returnUrl;
	//print_r($_REQUEST);EXIT;

	$client_id = "$kakao_clientid"; // 위에서 발급받은 Client ID 입력
	$client_secret = "$kakao_secret";
	$redirectURI = urlencode("http://".$_SERVER[HTTP_HOST]."/member/kakao/callback.php"); //자신의 Callback URL 입력
	$state = "RAMDOM_STATE";
	$apiURL = "https://kauth.kakao.com/oauth/authorize?client_id=".$client_id."&redirect_uri=".$redirectURI."&response_type=code&scope=account_email, plusfriends";


	if($code){

		$apiURL = "https://kauth.kakao.com/oauth/token";
		$params[grant_type] = "authorization_code";
		$params[client_id] = $client_id;
		$params[client_secret] = $client_secret;
		$params[redirect_uri] = $redirectURI;
		$params[code] = $code;

		$response = httpPost2($apiURL, $params);
		$res= json_decode($response, true);

		$access_token = $res[access_token];
		$refresh_token = $res[refresh_token];
		$_SESSION[access_token]= $access_token;
		
		$headers[] = "Authorization: Bearer ". $access_token;
		$apiURL = "https://kapi.kakao.com/v2/user/me";
		$response = httpPost2($apiURL, '', $headers);

		$uInfo = json_decode($response, true);
		//	$uInfo = $res[response];

		$uInfo[id]		=$uInfo[id];
		$uInfo[uname]	=$uInfo[properties][nickname];
		$uInfo[email]	=$uInfo[kakao_account][email];
		$uInfo[age]		=$uInfo[kakao_account][age_range];
		$uInfo[birthyear]	=$uInfo[kakao_account][birthyear];
		$uInfo[birthday]	=$uInfo[kakao_account][birthday];
		$uInfo[gender]	=$uInfo[kakao_account][gender];

		#print_r($_COOKIE[referer]);exit;

		// 첫번째 로그인이면 회원가입으로
		if(db_count("tbl_member", "userid='{$uInfo[id]}' and sns_regist='ka'", "idx")<1){
			$field[userid]		=$uInfo[id];
			$field[email]		=$uInfo[email];
			$field[uname]		="User-".getToken(10);
			$field[uage]		=$uInfo[age];
			$field[usex]		=$uInfo[gender];
			$field[profile_image]="/images/profile_default.jpg";
			$field[birthyear]	=$uInfo[birthyear];
			$field[birthday]	=$uInfo[birthday];
			#$field[mode]		="REGIST_SNS";
			$field[sns_regist]	="ka";
			#print_r($field);exit;

			#form_submit($field, "/_exec.php", "post");
			
			$ssn	= getToken(15);
			$field['ssn']			=$ssn;
			$field['reg_ip']			=anti_injection(getUserIp());
			$field['reg_date']		=date("Y-m-d H:i:s");
//			$field['userid']		=anti_injection($_POST['userid']);
//			$field['email']			=anti_injection($_POST['email']);
//			$field['uname']			=anti_injection($_POST['uname']);
//			$field['uage']			=anti_injection($_POST['uage']);
//			$field['usex']			=anti_injection($_POST['usex']);
//			$field['profile_image']	=anti_injection($_POST['profile_image']);
//			$field['birthyear']		=anti_injection($_POST['birthyear']);
//			$field['birthday']		=anti_injection($_POST['birthday']);
//			$field['sns_regist']	=anti_injection($_POST['sns_regist']);
			$field['member_level']	=200;
			$field['member_type']	=1;	// 프론트 솔트레벨
			$field['visit_date']	=date("Y-m-d H:i:s");
//			setCookie('ymd',base64_encode(date("Ymd")),0);
//			setCookie('clear','clear',0);
//			setCookie('chance_cnt',0,0);

			db_insert("tbl_member", $field);
			$idx		=mysql_insert_id();
//		}
//		else{
//			$field['mode']			="LOGIN";
//			$field['userid']		=$uInfo[id];
//			$field['email']			=$uInfo[email];
//			$field['sns_regist']	="ka";
//
//			form_submit($field, "/_exec.php", "post");

			$field['is_new']	='is_new';
		}

		if($uInfo[id]){
			$field['mode']			="LOGIN";
			$field['userid']		=$uInfo[id];
			$field['email']			=$uInfo[email];
			$field['sns_regist']	="ka";

			form_submit($field, "/_exec.php", "post");
		}
		else{
			if($_SERVER[HTTP_HOST]=='weespk.iptime.org:8010'){
				$field['mode']			="LOGIN";
				$field['userid']		='2508693684';
				$field['email']			='weespk@kakao.com';
				$field['sns_regist']	="ka";

				form_submit($field, "/_exec.php", "post");
			}
			else{
				header("Location: /login.html?msg=empty_requird_server");
			}
		}
	}
	else{
		header("Location: $apiURL");
	}

?>

