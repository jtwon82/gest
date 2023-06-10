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
			$file_info= file_upload2('file', $_SERVER[DOCUMENT_ROOT] ."/upfile");

			$sql = "UPDATE tbl_common SET val='$file_info[filename]' WHERE codes='$idx' ";
			db_query($sql);
		break;

	}


//	db_update("tbl_gift_config",$field);
//	move_page("site_info.php");
?>