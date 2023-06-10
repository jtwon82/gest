<?
	if($mode=="") $mode=anti_injection($_REQUEST['mode']);
	if($b_id=="") $b_id=anti_injection($_REQUEST['b_id']);

	$count=db_result("select count(idx) from tbl_board_config where b_id='".$b_id."'");

	if($count<1){
		msg_page("게시판 정보가 잘못 되었습니다.");
	}else{
		$bList=db_select("select * from tbl_board_config where b_id='".$b_id."'");
		$bbs_name=$bList['b_name'];
		$bbs_type=$bList['b_type'];
		$bbs_skin=$bList['skin'];
		$list_num=$bList['list_num'];
		$block_num=$bList['block_num'];
		$title_num=$bList['title_num'];
		$file_num=$bList['file_num'];
		$link_num=$bList['link_num'];
		$bbs_category_yn=$bList['category_yn'];
		$bbs_category=$bList['category'];
		$bbs_thumbnail=$bList['thumbnail'];
		if($bbs_thumbnail=="Y"){
			list($bbs_thumbwidth,$bbs_thumbheight)=@explode("/",$bList['thumbsize']);
			$bbs_thumbtype=$bList['thumbtype'];
		}
		$bbs_editor=$bList['editor'];
		$bbs_fileuse=$bList['fileuse'];
		$bbs_linkuse=$bList['linkuse'];
		$bbs_form=$bList['form'];
		$bbs_reply=$bList['reply'];
		$bbs_comment=$bList['comment'];
		$bbs_secret=$bList['secret'];
		$bbs_viewlist=$bList['viewlist'];
		$auth_list=$bList['auth_list'];
		$auth_view=$bList['auth_view'];
		$auth_write=$bList['auth_write'];
		$auth_reply=$bList['auth_reply'];
		$auth_comment=$bList['auth_comment'];
	}

	//지정된 폴더, 파일 불러오기
	$bbs_dir	=$_SERVER[DOCUMENT_ROOT] ."/data/bbs/".$b_id;  //게시판 저장경로
	$skin_dir	=$_SERVER[DOCUMENT_ROOT] ."/board";  //게시판 스킨 폴더
	$skin_img	="/images";  //게시판 스킨 이미지 폴더
	$bbs_page	=$_SERVER['PHP_SELF'];
?>