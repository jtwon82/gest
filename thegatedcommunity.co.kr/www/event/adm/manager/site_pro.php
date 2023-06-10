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
	$field['oreo_ssn']=anti_injection($_POST['oreo_ssn']);
	$field['oreo_winner']=anti_injection($_POST['oreo_winner']);
	$field['oreo_pct']=anti_injection($_POST['oreo_pct']);
	$field['oreo_daywinner']=anti_injection($_POST['oreo_daywinner']);
	$field['oreo_daywinner2']=anti_injection($_POST['oreo_daywinner2']);
	$field['ritz_ssn']=anti_injection($_POST['ritz_ssn']);
	$field['ritz_winner']=anti_injection($_POST['ritz_winner']);
	$field['ritz_pct']=anti_injection($_POST['ritz_pct']);
	$field['ritz_daywinner']=anti_injection($_POST['ritz_daywinner']);
	$field['ritz_daywinner2']=anti_injection($_POST['ritz_daywinner2']);
	$field['top_ssn']=anti_injection($_POST['top_ssn']);
	$field['top_winner']=anti_injection($_POST['top_winner']);
	$field['top_pct']=anti_injection($_POST['top_pct']);
	$field['top_daywinner']=anti_injection($_POST['top_daywinner']);

	db_update("tbl_site_config",$field);
	move_page("site_info.php");
?>