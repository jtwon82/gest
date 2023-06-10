<?
	include "../../common/function.php";
	include "../../common/db.php";
	include "../session.php";

	if($_POST['mode']=="write"){

		$field['reg_date']=db_select("select now()",0);
		$field['reg_dates']=db_select("select left(now(),10)",0);
		$field['title']=anti_injection($_POST['title']);
		$field['cate']=anti_injection($_POST['cate']);
		$field['client']=anti_injection($_POST['client']);
		$field['descript']=anti_injection($_POST['descript']);
		$field['title']=anti_injection($_POST['title']);
		$field['contents']=addslashes($_POST['contents']);

		if($_FILES['upfile']['name']){
			$upfile = file_upload($_FILES['upfile']['tmp_name'], $_FILES['upfile']['name'], "../../data/project",2);
			$field['upfile']=$upfile;
			$field['orgname']=$_FILES['upfile']['name'];
		}

		db_insert("tbl_project_fbasic",$field);
		move_page("project_list_fbasic.php");
	}

	if($_POST['mode']=="modify"){

		$del=anti_injection($_POST['del']);

		$field['title']=anti_injection($_POST['title']);
		$field['cate']=anti_injection($_POST['cate']);
		$field['client']=anti_injection($_POST['client']);
		$field['descript']=anti_injection($_POST['descript']);
		$field['title']=anti_injection($_POST['title']);
		$field['contents']=addslashes($_POST['contents']);

		if( $del == 'Y' ){
			$field['upfile']='';
			$field['orgname']='';
		}
		if($_FILES['upfile']['name']){
			$upfile = file_upload($_FILES['upfile']['tmp_name'], $_FILES['upfile']['name'], "../../data/project",2);
			$field['upfile']=$upfile;
			$field['orgname']=$_FILES['upfile']['name'];
		}

		#print_r( $field );
		#print_r( $_POST );

		db_update("tbl_project_fbasic", $field, "idx='".$_POST['idx']."'");
		move_page("project_list_fbasic.php");
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