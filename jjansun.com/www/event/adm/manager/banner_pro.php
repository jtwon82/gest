<?
	include "../../common/function.php";
	include "../../common/db.php";
	include "../session.php";

	$bbs_dir= $_SERVER[DOCUMENT_ROOT] ."/upfile";
	if($_POST['mode']=="write"){
		$field['open']=anti_injection($_POST['open']);
		$field['title']=anti_injection($_POST['title']);
		$field['area']=anti_injection($_POST['area']);
		$field['link']=($_POST['link']);
		$field['content']=$_POST['content'];
		$field['reg_date']=date("Y-m-d H:i:s");

		//첨부파일 등록
		//for($i=0; $i<count($_FILES['filename']['name']); $i++){

			if($_FILES['filename']['name'][0]) {
				$upfile = file_upload($_FILES['filename']['tmp_name'][0], $_FILES['filename']['name'][0], $bbs_dir);
				$upfield['b_idx'] = $b_idx;
				$field['filename'] = $upfile;
				#$field['filesize'] = $_FILES['filename']['size'][0];
				$field['orgname'] = $_FILES['filename']['name'][0];
				$upfield['sortnum'] = $i+1;

				//db_insert("tbl_board_file",$upfield);

				//if($bbs_thumbnail=="Y" && $i==0){
				//	thumbnail($bbs_dir."/".$upfile, $bbs_dir."/thumb_".$upfile, $bbs_thumbwidth, $bbs_thumbheight, $bbs_thumbtype);
				//}
			}
		//}

		db_insert("tbl_banner",$field);
		move_page("banner_list.php");
	}

	if($_POST['mode']=="modify"){
		$field['open']=anti_injection($_POST['open']);
		$field['title']=anti_injection($_POST['title']);
		$field['area']=anti_injection($_POST['area']);
		$field['link']=($_POST['link']);
		$field['content']=$_POST['content'];


		//첨부파일 등록
		//for($i=0; $i<count($_FILES['filename']['name']); $i++){
			if($_FILES['filename']['name'][0]) {
				$upfile = file_upload($_FILES['filename']['tmp_name'][0], $_FILES['filename']['name'][0], $bbs_dir);
				$upfield['b_idx'] = $b_idx;
				$field['filename'] = $upfile;
				#$field['filensize'] = $_FILES['filename']['size'][0];
				$field['orgname'] = $_FILES['filename']['name'][0];
				$upfield['sortnum'] = $i+1;

				//db_insert("tbl_board_file",$upfield);

				//if($bbs_thumbnail=="Y" && $i==0){
				//	thumbnail($bbs_dir."/".$upfile, $bbs_dir."/thumb_".$upfile, $bbs_thumbwidth, $bbs_thumbheight, $bbs_thumbtype);
				//}
			}
		//}


		db_update("tbl_banner",$field,"idx='".$_POST['idx']."'");
		move_page("banner_list.php");
	}

	if($_POST['mode']=="change"){
		$idx=$_POST['idx'];
		$field[$_POST['type']]=$_POST['val'];

		db_update("tbl_banner",$field,"idx='".$idx."'");
		echo "succ";
	}

	if($_POST['mode']=="check_del"){
		foreach($_POST['idx'] as $idx){
			db_query("delete from tbl_banner where idx='".$idx."'");
		}
		move_page("banner_list.php");
	}

	if($_GET['mode']=="del"){
		db_query("delete from tbl_banner where idx='".$_GET['idx']."'");
		move_page("banner_list.php");
	}
?>