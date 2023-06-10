<?
	include "../../event/common/function.php";
	include "../../event/common/db.php";
//	include "../../event/common/config.php";


		$access_token= $_SESSION[access_token];
		
		$headers[] = "Authorization: Bearer ". $access_token;
		$apiURL = "https://kapi.kakao.com/v2/user/me";
		$response = httpPost2($apiURL, '', $headers);
		$uInfo = json_decode($response, true);
		$target_id		=$uInfo[id];


		$headers[] = "Authorization: Bearer ". $access_token;
		$params[target_id_type] = 'user_id';
		$params[target_id] = $target_id;
//		$params[channel_public_ids] = '_Eywixj';
		$apiURL = "https://kapi.kakao.com/v1/api/talk/channels";
		$response = httpPost2($apiURL, $params, $headers);
		$uInfo = json_decode($response, true);

		debugToEcho(__LINE__, json_encode( array('mode'=>$mode, 'result'=>'o', 'uInfo'=>$uInfo ) ) );

?>

