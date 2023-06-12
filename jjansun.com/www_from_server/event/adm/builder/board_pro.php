<?
	include "../../common/function.php";
	include "../../common/db.php";
	include "../session.php";

	if($_POST['mode']=="write"){

		$bbs_index=db_select("select b_id from tbl_board_config order by b_id desc limit 1");

		//게시판코드 생성
		$b_id=($bbs_index['b_id']) ? $bbs_index['b_id']+1 : "001";

		$field['b_id']=sprintf("%03d",$b_id);
		$field['b_name']=anti_injection($_POST['b_name']);
		$field['b_type']=anti_injection($_POST['b_type']);
		$field['skin']=anti_injection($_POST['skin']);
		$field['list_num']=anti_injection($_POST['list_num']);
		$field['block_num']=anti_injection($_POST['block_num']);
		$field['title_num']=anti_injection($_POST['title_num']);
		$field['file_num']=anti_injection($_POST['file_num']);
		$field['link_num']=anti_injection($_POST['link_num']);
		$field['form']=@implode("|",$_POST['form']);
		$field['category_yn']=$_POST['category_yn'];
		$field['category']=anti_injection($_POST['category']);
		$field['thumbnail']=$_POST['thumbnail'];
		if($field['thumbnail']=="Y"){
			$field['thumbsize']=$_POST['thumbwidth']."/".$_POST['thumbheight'];
			$field['thumbtype']=$_POST['thumbtype'];
		}
		$field['editor']=$_POST['editor'];
		$field['fileuse']=$_POST['fileuse'];
		$field['linkuse']=$_POST['linkuse'];
		$field['reply']=$_POST['reply'];
		$field['comment']=$_POST['comment'];
		$field['secret']=$_POST['secret'];
		$field['viewlist']=$_POST['viewlist'];

		//사용권한
		$field['auth_list']=@implode("|",$_POST['auth_list']);
		$field['auth_view']=@implode("|",$_POST['auth_view']);
		$field['auth_write']=@implode("|",$_POST['auth_write']);
		$field['auth_reply']=@implode("|",$_POST['auth_reply']);
		$field['auth_comment']=@implode("|",$_POST['auth_comment']);

		db_insert("tbl_board_config",$field);
		move_page("board_list.php");
	}

	if($_POST['mode']=="modify"){

		$field['b_name']=anti_injection($_POST['b_name']);
		$field['b_type']=anti_injection($_POST['b_type']);
		$field['skin']=anti_injection($_POST['skin']);
		$field['list_num']=anti_injection($_POST['list_num']);
		$field['block_num']=anti_injection($_POST['block_num']);
		$field['title_num']=anti_injection($_POST['title_num']);
		$field['file_num']=anti_injection($_POST['file_num']);
		$field['link_num']=anti_injection($_POST['link_num']);
		$field['form']=@implode("|",$_POST['form']);
		$field['category_yn']=$_POST['category_yn'];
		$field['category']=anti_injection($_POST['category']);
		$field['thumbnail']=$_POST['thumbnail'];
		if($field['thumbnail']=="Y"){
			$field['thumbsize']=$_POST['thumbwidth']."/".$_POST['thumbheight'];
			$field['thumbtype']=$_POST['thumbtype'];
		}
		$field['editor']=$_POST['editor'];
		$field['fileuse']=$_POST['fileuse'];
		$field['linkuse']=$_POST['linkuse'];
		$field['reply']=$_POST['reply'];
		$field['comment']=$_POST['comment'];
		$field['secret']=$_POST['secret'];
		$field['viewlist']=$_POST['viewlist'];

		//사용권한
		$field['auth_list']=@implode("|",$_POST['auth_list']);
		$field['auth_view']=@implode("|",$_POST['auth_view']);
		$field['auth_write']=@implode("|",$_POST['auth_write']);
		$field['auth_reply']=@implode("|",$_POST['auth_reply']);
		$field['auth_comment']=@implode("|",$_POST['auth_comment']);

		db_update("tbl_board_config", $field, "idx='".$_POST['idx']."'");
		move_page("board_list.php");
	}

	if($_POST['mode']=="change"){
		$idx=$_POST['idx'];
		db_update("tbl_board_config",$field,"idx='".$idx."'");
		echo "succ";
	}

	if($_POST['mode']=="check_del"){
		foreach($_POST['idx'] as $idx){
			db_query("delete from tbl_board_config where idx='".$idx."'");
		}
		move_page("board_list.php");
	}

	if($_GET['mode']=="del"){
		db_query("delete from tbl_board_config where idx='".$_GET['idx']."'");
		move_page("board_list.php");
	}
?>