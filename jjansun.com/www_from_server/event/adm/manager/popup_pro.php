<?
	include "../../common/function.php";
	include "../../common/db.php";
	include "../session.php";

	if($_POST['mode']=="write"){
		$field['open']=$_POST['open'];
		$field['style']=$_POST['style'];
		$field['ptop']=$_POST['ptop'];
		$field['pleft']=$_POST['pleft'];
		$field['pwidth']=anti_injection($_POST['pwidth']);
		$field['pheight']=anti_injection($_POST['pheight']);
		$field['title']=anti_injection($_POST['title']);
		$field['content']=$_POST['content'];
		$field['regdate']=time();

		db_insert("tbl_popup",$field);
		move_page("popup_list.php");
	}

	if($_POST['mode']=="modify"){
		$field['open']=$_POST['open'];
		$field['style']=$_POST['style'];
		$field['ptop']=$_POST['ptop'];
		$field['pleft']=$_POST['pleft'];
		$field['pwidth']=anti_injection($_POST['pwidth']);
		$field['pheight']=anti_injection($_POST['pheight']);
		$field['title']=anti_injection($_POST['title']);
		$field['content']=anti_injection($_POST['content']);

		db_update("tbl_popup",$field,"idx='".$_POST['idx']."'");
		move_page("popup_list.php");
	}

	if($_POST['mode']=="change"){
		$idx=$_POST['idx'];
		$field[$_POST['type']]=$_POST['val'];

		db_update("tbl_popup",$field,"idx='".$idx."'");
		echo "succ";
	}

	if($_POST['mode']=="check_del"){
		foreach($_POST['idx'] as $idx){
			db_query("delete from tbl_popup where idx='".$idx."'");
		}
		move_page("popup_list.php");
	}

	if($_GET['mode']=="del"){
		db_query("delete from tbl_popup where idx='".$_GET['idx']."'");
		move_page("popup_list.php");
	}
?>