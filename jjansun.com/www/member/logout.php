<?
	include "../event/common/function.php";
	include "../event/common/db.php";
	include "../event/common/config.php";

	session_start();
	session_unset(); // 모든 세션변수를 언레지스터 시켜줌
	session_destroy(); // 세션해제함

	unset_cookie('SSN');
	
	move_page("/");
?>