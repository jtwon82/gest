<?
	include "../../common/function.php";
	include "../../common/db.php";
	include "../session.php";

	if($_POST['mode']=="write"){

		$field['reg_date']=db_select("select now()",0);
		$field['reg_datess']=db_select("select left(now(),10)",0);
		$field['title']=anti_injection($_POST['title']);
		$field['cate']=anti_injection($_POST['cate']);
		$field['client']=anti_injection($_POST['client']);
		$field['desc']=anti_injection($_POST['desc']);
		$field['title']=anti_injection($_POST['title']);
		$field['content']=addslashes($_POST['content']);

		if($_FILES['upfile']['name']){
			$upfile = file_upload($_FILES['upfile']['tmp_name'], $_FILES['upfile']['name'], "../../data/project",2);
			$field['upfile']=$upfile;
			$field['orgname']=$_FILES['upfile']['name'];
		}

		db_insert("tbl_fbasic_project",$field);
		move_page("project_list_fbasic.php");
	}

	if($_POST['mode']=="modify"){

		$field['title']=anti_injection($_POST['title']);
		$field['uname']=anti_injection($_POST['uname']);
		$field['pno1']=anti_injection($_POST['pno1']);
		$field['email']=anti_injection($_POST['email']);
		$field['url']=anti_injection($_POST['url']);
		$field['youtube_code']=anti_injection($_POST['youtube_code']);
		$field['content']=anti_injection($_POST['content']);
		$field['state']=addslashes($_POST['state']);

		if($_FILES['upfile']['name']){
			$upfile = file_upload($_FILES['upfile']['tmp_name'], $_FILES['upfile']['name'], "../../data/online",2);
			$field['upfile']=$upfile;
			$field['orgname']=$_FILES['upfile']['name'];
		}

		db_update("tbl_online", $field, "idx='".$_POST['idx']."'");
		move_page("contract_list.php");
	}

	if($_POST['mode']=="change"){
		$idx=$_POST['idx'];
		$field[$_POST['type']]=$_POST['val'];

		db_update("tbl_online",$field,"idx='".$idx."'");
		echo "succ";
	}

	if($_POST['mode']=="check_del"){
		foreach($_POST['idx'] as $idx){
			db_query("delete from tbl_online where idx='".$idx."'");
		}

		move_page("contract_list.php");
	}

	if($_GET['mode']=="del"){
		db_query("delete from tbl_online where idx='".$_GET['idx']."'");
		move_page("contract_list.php");
	}
?>