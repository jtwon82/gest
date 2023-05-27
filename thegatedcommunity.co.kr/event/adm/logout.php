<?
	include("../common/function.php");
	include("../common/db.php");

	#session_unregister($_SESSION['LOGIN_ID']);
	#session_unregister($_SESSION['LOGIN_LEVEL']);
	session_destroy();
	move_page("./");
?>