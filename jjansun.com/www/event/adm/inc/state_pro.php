<?
	include "../../common/function.php";
	include "../../common/db.php";
	include "./../session.php";

	if($_POST['mode']=="change"){
		$idx=$_POST['idx'];
		$field[$_POST['type']]=$_POST['val'];

		db_update($_POST['table'],$field,"idx='".$idx."'");
		echo "succ";
	}
 ?>