<?
	include "../../common/function.php";
	include "../../common/db.php";
	include "../../common/config.php";
	include "../session.php";

	$path = "../../../data/content";





	if($_POST['mode']=="write"){

		$field['userno'] = $_SESSION['LOGIN_NO'];
		$field['gubun'] =anti_injection(join($_POST['gubun'],','));
		$field['descript'] = anti_injection($_POST['descript']);
		$field['sdate'] = anti_injection($_POST['sdate']);
		$field['edate'] = anti_injection($_POST['edate']);
		$field['title'] = anti_injection($_POST['title']);
		$field['gift_info'] = anti_injection($_POST['gift_info']);
		$field['content'] = $_POST['content'];
		$field['landing'] = anti_injection($_POST['landing']);
		$field['tag'] = anti_injection($_POST['tag']);
		$field['vod'] = anti_injection($_POST['vod']);
		$field['url'] = anti_injection($_POST['url']);
		$field['state'] = anti_injection($_POST['state']);
		$field['reg_date'] =date('Y-m-d H:i:s');
		$field['collect_date'] =date('Y-m-d H:i:s');
		$field['reg_dates'] =date('Y-m-d');

		db_insert("tbl_content",$field);

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

				db_insert("tbl_content_file",$upfield);

				//상세 이미지 썸네일
				thumbnail($path."/".$upfile, $path."/view_".$upfile, 730, 2000, 4);

				//리스트 이미지 썸네일
				if($i==0) thumbnail($path."/".$upfile, $path."/list_".$upfile, 300, 1000, 4);
			}

		}

		move_page("content_list.php");
	}


	if($_POST['mode']=="modify"){

		$field['gubun'] =anti_injection(join($_POST['gubun'],','));
		$field['descript'] = anti_injection($_POST['descript']);
		$field['sdate'] = anti_injection($_POST['sdate']);
		$field['edate'] = anti_injection($_POST['edate']);
		$field['title'] = anti_injection($_POST['title']);
		$field['gift_info'] = anti_injection($_POST['gift_info']);
		$field['content'] = $_POST['content'];
		$field['landing'] = anti_injection($_POST['landing']);
		$field['tag'] = anti_injection($_POST['tag']);
		$field['vod'] = anti_injection($_POST['vod']);
		$field['url'] = anti_injection($_POST['url']);
		$field['state'] = anti_injection($_POST['state']);
		$field['update_date'] =date('Y-m-d H:i:s');

		db_update("tbl_content", $field, "idx='".$_POST['idx']."'");

/* ┏━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┓
	┃ 이미지등록	┃
	┗━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┛*/

		//첨부파일 개별 삭제
		for($i=0; $i<count($_POST['filedel']); $i++){
			$fList=db_select("select filename from tbl_content_file where idx='".$_POST['idx']."' and sortnum='".$_POST['filedel'][$i]."'");
			file_delete($fList['filename'], $path);
			db_query("delete from tbl_content_file where bidx='".$_POST['idx']."' and sortnum='".$_POST['filedel'][$i]."'");
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

				db_query("delete from tbl_content_file where bidx='".$_POST['idx']."' and sortnum='".($i+1)."'");
				db_insert("tbl_content_file",$upfield);
			}
		}

		move_page("content_write.php?idx=".$_POST['idx']);
	}


	if($_GET['mode']=="del"){

		db_query("update tbl_content set state=5 where idx='".$_GET['idx']."'");
		move_page("content_list.php");
	}


	if($_POST['mode']=="check_del"){

		foreach($_POST['idx'] as $idx){
			db_query("update tbl_content set state=5 where idx='".$idx."'");
		}

		move_page("content_list.php");
	}
?>