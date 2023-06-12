<?
	include "../event/common/function.php";
	include "../event/common/db.php";
	include "../event/common/config.php";
	include("../event/adm/board/board_config.php");

	$page			= anti_injection($_GET['page']);
	$search_key		= anti_injection($_GET['search_key']);
	$search_val		= anti_injection($_GET['search_val']);

	if($_GET['idx']){
		db_query("update tbl_board set hit=hit+1 where idx='".$_GET['idx']."'");
		$pList=db_select("select * from tbl_board where idx='".$_GET['idx']."'");
		
		//첨부파일
		if($bbs_fileuse=="Y"){
			$fileCnt=db_result("select filename from tbl_board_file where b_idx='".$_GET['idx']."'");
			$file_icon=($fileCnt>0 ? "<img src=\"".$skin_img."/bt_file.gif\" />" : "");
			$files='';
			for($filei=0; $filei<$file_num; $filei++){
				$ff=db_select("select * from tbl_board_file where b_idx='".$pList['idx']."' and sortnum='".($filei+1)."'");
				if($ff['filename']){
					$files[] = "/data/filestream.php?Path=bbs/$b_id&File=$ff[filename]";
				}
			}
			$pList[files]= $files;
		}

		$content=$pList['content'];
		$b_code=$pList['b_code'];
		if(!$pList){
			msg_page("잘못된 접근입니다.");
		}
	}

//print_r($pList);
$title= $pList[title];
$image= $pList[files][0];

?>

			<? include "../head.php"; ?>
			<? include "../head.gnb.php"; ?>

			<? include "./{$bbs_skin}/view.php"; ?>

			<? include "../tail.popup.php"; ?>
			<? include "../tail.php"; ?>
