<?
	include "../../event/common/function.php";
	include "../../event/common/db.php";
//	include "../../event/common/config.php";

		$access_token= $_SESSION[access_token];
		
		$headers[] = "Authorization: Bearer ". $access_token;
		$apiURL = "https://kapi.kakao.com/v2/user/me";
		$response = httpPost2($apiURL, '', $headers);

		$uInfo = json_decode($response, true);
		$uInfo2[id]		=$uInfo[id];
		#$uInfo2[email]	=$uInfo[kakao_account][email];
		$uInfo2[access_token]	=$access_token;

		debugToEcho(__LINE__, json_encode( array('mode'=>$mode, 'result'=>'o', 'uInfo'=>$uInfo2 ) ) );

?>

