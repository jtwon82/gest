<?
	include "../../common/function.php";
	include "../../common/db.php";
	include "../session.php";

	$field['title']=anti_injection($_POST['title']);
	$field['company']=anti_injection($_POST['company']);
	$field['tel']=anti_injection($_POST['tel']);
	$field['hp']=anti_injection($_POST['hp']);
	$field['email']=anti_injection($_POST['email']);
	$field['manager']=anti_injection($_POST['manager']);
	$field['join_level']=anti_injection($_POST['join_level']);
	$field['join_del']=anti_injection($_POST['join_del']);

	db_update("tbl_site_config",$field);
	move_page("site_info.php");
?>