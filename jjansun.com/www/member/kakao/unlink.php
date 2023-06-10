<?
	include "../../event/common/function.php";
	include "../../event/common/db.php";
	include "../../event/common/config.php";


	$client_id = "$kakao_clientid"; // 위에서 발급받은 Client ID 입력
	$client_secret = "$kakao_secret";
	$redirectURI = urlencode("https://www.jjansun.com/member/kakao/unlink.php"); //자신의 Callback URL 입력
	$state = "RAMDOM_STATE";
	$apiURL = "https://kauth.kakao.com/oauth/authorize?client_id=".$client_id."&redirect_uri=".$redirectURI."&response_type=code";

	$code			=anti_injection($_REQUEST['code']);

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
		
		$headers[] = "Authorization: Bearer ". $access_token;
		$apiURL = "https://kapi.kakao.com/v1/user/unlink";
		$response = httpPost2($apiURL, '', $headers);

		$uInfo = json_decode($response, true);
		#print_r($uInfo);exit;

//		$mInfo_friend = db_select_assoc("select * from tbl_member where userid='$uInfo[id]' ");	// 초대한친구
//		db_query("insert into tbl_member_invitehistory_leave(reg_date, reg_dates, userid, userid_friend, cnt) select reg_date, reg_dates, userid, userid_friend, cnt from tbl_member_invitehistory where userid='$uInfo[id]' ");
//		db_query("insert into tbl_member_invitehistory_leave(reg_date, reg_dates, userid, userid_friend, cnt) select reg_date, reg_dates, userid, userid_friend, cnt from tbl_member_invitehistory where userid_friend='$uInfo[id]' ");
//		db_query("delete from tbl_member_invitehistory where userid='$uInfo[id]' ");
//		db_query("delete from tbl_member_invitehistory where userid_friend='$uInfo[id]' ");

		$sql = "
		insert into tbl_member_leave(userid, ssn, sns_regist, member_level, uname, reg_date, reg_dates, reg_ip)
			select userid, ssn, sns_regist, member_level, uname, reg_date, reg_dates, reg_ip from tbl_member where userid='$uInfo[id]'
		";
		db_query($sql);

		db_query("delete from tbl_event_winneruser where ssn in (select ssn from tbl_member where userid='$uInfo[id]') ");

		$sql = "delete from tbl_member where userid='$uInfo[id]' ";
		db_query($sql);

		script("location.replace('/member/logout.php')","안전하게 탈퇴 되었습니다. 모든 회원정보는 삭제되었습니다. 다음에 좋은 모습으로 다시 뵙겠습니다~^^");

	}
	else{
		header("Location: $apiURL");
	}

?>

