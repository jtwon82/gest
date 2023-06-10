<?
	include "../../common/function.php";
	include "../../common/db.php";
	include "../../common/config.php";
	include "../session.php";

	$path = "../../../data/content";





	if($_POST['mode']=="write"){

		$field['state'] = anti_injection($_POST['state']);
		$field['title'] = anti_injection($_POST['title']);
		$field['event_list_url'] = anti_injection($_POST['event_list_url']);
		$field['dom_list'] = anti_injection($_POST['dom_list']);
		$field['dom_title'] = anti_injection($_POST['dom_title']);
		$field['dom_desc'] = anti_injection($_POST['dom_desc']);
		$field['dom_thumb'] = anti_injection($_POST['dom_thumb']);
		$field['dom_url'] = anti_injection($_POST['dom_url']);
		$field['dom_sdate'] = anti_injection($_POST['dom_sdate']);
		$field['dom_edate'] = anti_injection($_POST['dom_edate']);
		$field['dom_fdate'] = anti_injection($_POST['dom_fdate']);
		$field['dom_gift_info'] = anti_injection($_POST['dom_gift_info']);
		$field['reg_date'] =date('Y-m-d H:i:s');
		$field['reg_dates'] =date('Y-m-d');

		db_insert("tbl_content_dom",$field);

		$bidx=mysql_insert_id();

/* ┏━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┓
	┃ 이미지등록	┃
	┗━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┛*/

		//첨부파일 등록
		for($i=0; $i<count($_FILES['filename']['name']); $i++){

			if($_FILES['filename']['name'][$i]) {
				$upfile = file_upload($_FILES['filename']['tmp_name'][$i], $_FILES['filename']['name'][$i], $path);
				$upfield['bidx'] = $bidx;
				$upfield['filename'] = $upfile;
				$upfield['orgname'] = $_FILES['filename']['name'][$i];
				$upfield['sortnum'] = $i+1;

				db_insert("tbl_content_dom_file",$upfield);

				//상세 이미지 썸네일
				thumbnail($path."/".$upfile, $path."/view_".$upfile, 730, 2000, 4);

				//리스트 이미지 썸네일
				if($i==0) thumbnail($path."/".$upfile, $path."/list_".$upfile, 300, 1000, 4);
			}

		}

		move_page("content_dom_list.php");
	}


	if($_POST['mode']=="modify"){

		$field['state'] = anti_injection($_POST['state']);
		$field['title'] = anti_injection($_POST['title']);
		$field['event_list_url'] = anti_injection($_POST['event_list_url']);
		$field['dom_list'] = anti_injection($_POST['dom_list']);
		$field['dom_title'] = anti_injection($_POST['dom_title']);
		$field['dom_desc'] = anti_injection($_POST['dom_desc']);
		$field['dom_thumb'] = anti_injection($_POST['dom_thumb']);
		$field['dom_url'] = anti_injection($_POST['dom_url']);
		$field['dom_sdate'] = anti_injection($_POST['dom_sdate']);
		$field['dom_edate'] = anti_injection($_POST['dom_edate']);
		$field['dom_fdate'] = anti_injection($_POST['dom_fdate']);
		$field['dom_gift_info'] = anti_injection($_POST['dom_gift_info']);
		#$field['update_date'] =date('Y-m-d H:i:s');
		$field['update_dates'] =anti_injection($_POST['update_dates']);

		db_update("tbl_content_dom", $field, "idx='".$_POST['idx']."'");

/* ┏━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┓
	┃ 이미지등록	┃
	┗━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┛*/

		//첨부파일 개별 삭제
		for($i=0; $i<count($_POST['filedel']); $i++){
			$fList=db_select("select filename from tbl_content_dom_file where idx='".$_POST['idx']."' and sortnum='".$_POST['filedel'][$i]."'");
			file_delete($fList['filename'], $path);
			db_query("delete from tbl_content_dom_file where bidx='".$_POST['idx']."' and sortnum='".$_POST['filedel'][$i]."'");
		}

		//첨부파일 수정
		for($i=0; $i<count($_FILES['filename']['name']); $i++){

			if($_FILES['filename']['name'][$i]) {
				$upfile = file_upload($_FILES['filename']['tmp_name'][$i], $_FILES['filename']['name'][$i], $path);
				$upfield['filename'] = $upfile;
				$upfield['orgname'] = $_FILES['filename']['name'][$i];
				$upfield['bidx'] = $_POST['idx'];
				$upfield['sortnum'] = $i+1;

				//상세 이미지 썸네일
				thumbnail($path."/".$upfile, $path."/view_".$upfile, 730, 2000, 4);

				//리스트 이미지 썸네일
				if($i==0) thumbnail($path."/".$upfile, $path."/list_".$upfile, 300, 1000, 4);

				db_query("delete from tbl_content_dom_file where bidx='".$_POST['idx']."' and sortnum='".($i+1)."'");
				db_insert("tbl_content_dom_file",$upfield);
			}
		}

		move_page("content_dom_write.php?idx=".$_POST['idx']);
	}


	if($_GET['mode']=="del"){

		db_query("update tbl_content_dom set state=5 where idx='".$_GET['idx']."'");
		move_page("content_dom_list.php");
	}


	if($_POST['mode']=="check_del"){

		foreach($_POST['idx'] as $idx){
			db_query("update tbl_content_dom set state=5 where idx='".$idx."'");
		}

		move_page("content_dom_list.php");
	}
?>