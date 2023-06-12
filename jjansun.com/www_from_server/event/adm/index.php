<?
	if ($_SERVER[SERVER_PORT] != 443){
		header('Location: https://'.$_SERVER['HTTP_HOST'].'/event/adm/'. $_SERVER[QUERY_STRING]);
	}
?><script language=javascript>

 window.location.href="./login.php";

</script>