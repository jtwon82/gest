<?
	include "../../common/function.php";
	include "../../common/db.php";
	include "../session.php";

	$mode			= anti_injection($_REQUEST[mode]);
	$idx			= anti_injection($_REQUEST[idx]);
	$fld			= anti_injection($_REQUEST[fld]);
	$value			= anti_injection($_REQUEST[value]);

	switch ($mode) {

		Case "UPDATE" :
			$sql = "UPDATE tbl_common SET $fld='$value' WHERE codes='$idx' ";
			db_query($sql);
		break;

	}


//	db_update("tbl_gift_config",$field);
//	move_page("site_info.php");
?>