<?
	include "../../common/function.php";
	include "../../common/db.php";
	include "../session.php";

	if($_POST['mode']=="write"){

		$level_index=db_select("select level_code from tbl_member_level order by level_code desc limit 1");

		//비회원 0, 최고관리자 10, 회원 100부터
		$level_code=($level_index['level_code']>=500) ? $level_index['level_code']+10 : 500;
		$field['level_code']=$level_code;
		$field['level_name']=anti_injection($_POST['level_name']);
		$field['login']=$_POST['login'];

		db_insert("tbl_member_level",$field);
		move_page("level_list.php");
	}

	if($_POST['mode']=="modify"){
		$field['level_name']=anti_injection($_POST['level_name']);
		$field['login']=$_POST['login'];

		db_update("tbl_member_level", $field, "idx='".$_POST['idx']."'");
		move_page("level_list.php");
	}

	if($_POST['mode']=="check_del"){
		foreach($_POST['idx'] as $idx){
			db_query("delete from tbl_member_level where idx='".$idx."'");
		}
		move_page("level_list.php");
	}

	if($_GET['mode']=="del"){
		db_query("delete from tbl_member_level where idx='".$_GET['idx']."'");
		move_page("level_list.php");
	}
?>