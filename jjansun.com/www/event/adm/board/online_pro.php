<?
	include "../../common/db.inc";
	include "../../common/function.inc";
	include "../../common/config.inc";
	include "../session.php";

	if($_POST['mode']=="write"){

		$field['name']=anti_injection($_POST['name']);
		$field['tel']=anti_injection($_POST['tel1'])."-".anti_injection($_POST['tel2'])."-".anti_injection($_POST['tel3']);
		$field['hp']=anti_injection($_POST['hp1'])."-".anti_injection($_POST['hp2'])."-".anti_injection($_POST['hp3']);
		$field['email']=anti_injection($_POST['email1'])."@".anti_injection($_POST['email2']);
		$field['addr']=anti_injection($_POST['addr']);
		$field['company']=anti_injection($_POST['company']);
		$field['part']=anti_injection($_POST['part']);
		$field['category']=anti_injection($_POST['category']);
		$field['title']=anti_injection($_POST['title']);
		$field['content']=anti_injection($_POST['content']);
		$field['memo']=anti_injection($_POST['memo']);
		$field['regdate']=time();

		if($_FILES['filename']['name']){
			$upfile = file_upload($_FILES['filename']['tmp_name'], $_FILES['filename']['name'], "../../data/online",2);
			$field['filename']=$upfile;
			$field['orgname']=$_FILES['filename']['name'];
		}
		db_insert("tbl_online",$field);
		move_page("online_list.php");
	}

	if($_POST['mode']=="modify"){

		$field['name']=anti_injection($_POST['name']);
		$field['tel']=anti_injection($_POST['tel1'])."-".anti_injection($_POST['tel2'])."-".anti_injection($_POST['tel3']);
		$field['hp']=anti_injection($_POST['hp1'])."-".anti_injection($_POST['hp2'])."-".anti_injection($_POST['hp3']);
		$field['email']=anti_injection($_POST['email1'])."@".anti_injection($_POST['email2']);
		$field['addr']=anti_injection($_POST['addr']);
		$field['company']=anti_injection($_POST['company']);
		$field['part']=anti_injection($_POST['part']);
		$field['category']=anti_injection($_POST['category']);
		$field['title']=anti_injection($_POST['title']);
		$field['content']=anti_injection($_POST['content']);
		$field['memo']=anti_injection($_POST['memo']);

		if($_FILES['filename']['name']){
			$upfile = file_upload($_FILES['filename']['tmp_name'], $_FILES['filename']['name'], "../../data/online",2);
			$field['filename']=$upfile;
			$field['orgname']=$_FILES['filename']['name'];
		}

		db_update("tbl_online", $field, "idx='".$_POST['idx']."'");
		move_page("online_list.php?b_id=".$_POST['b_id']);
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
		move_page("online_list.php?b_id=".$_POST['b_id']);
	}

	if($_GET['mode']=="del"){
		db_query("delete from tbl_online where idx='".$_GET['idx']."'");
		move_page("online_list.php?b_id=".$_GET['b_id']);
	}

	if($_POST['mode']=="reply"){

		$field['reply']=anti_injection($_POST['reply']);
		db_update("tbl_online", $field, "idx='".$_POST['idx']."'");

		$pList=db_select("select * from tbl_online where idx='".$_POST['idx']."'");

		include "../../mail_skin/customer.php";

		$mail_content = str_replace("{url}",  "http://".$_SERVER['HTTP_HOST'] , $mail_content);
		$mail_content = str_replace("{name}", $pList['name'], $mail_content);
		$mail_content = str_replace("{content}", nl2br($field['reply']), $mail_content);

		$from_info = $SITE_INFO['company']."<".$SITE_INFO['email'].">";
		$email = infoMem($pList['userno'],"email");

		sendMail("[".$SITE_INFO['company']."] 문의주신 내용에 대한 답변입니다.", $mail_content, $from_info, $email);

		move_page("online_list.php?b_id=".$_POST['b_id']);
	}
?>