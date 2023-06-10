<?
	include "../../common/function.php";
	include "../../common/db.php";
	include "../../common/config.php";
	include "../session.php";

	if($_POST['mode']=="1"){ //개별발송

		$field['type']=1;
		$field['userid']=$_SESSION['LOGIN_ID'];
		$field['from_name']=anti_injection($_POST['from_name']);
		$field['from_email']=anti_injection($_POST['from_email']);
		$from_info=$field['from_name']."<".$field['from_email'].">";
		$field['title']=anti_injection($_POST['title']);
		$field['content']=$_POST['content'];

		$to_info = @explode(";", anti_injection($_POST['to_email']));

		for($i=0; $i<count($to_info); $i++){
			sendMail($field['title'],$field['content'],$from_info,$to_info[$i]);
		}

		$field['mail_cnt']=count($to_info);
		$field['regdate']=time();

		db_insert("tbl_member_mail",$field);
		msg_page($field['mail_cnt']."건의 메일이 발송되었습니다.","mail_list.php");
	}


	if($_POST['mode']=="2"){ //대량발송

		$email_yn=$_POST['email_yn'];
		$mem_level_arr=$_POST['member_level'];
		$gender=$_POST['gender'];
		$sdate=$_POST['sdate'];
		$edate=$_POST['edate'];
		$field['type']=2;
		$field['userid']=$_SESSION['LOGIN_ID'];
		$field['from_name']=anti_injection($_POST['from_name']);
		$field['from_email']=anti_injection($_POST['from_email']);
		$from_info=$field['from_name']."<".$field['from_email'].">";
		$field['title']=anti_injection($_POST['title']);
		$field['content']=anti_injection($_POST['content']);
		$field['regdate']=time();

		$searchSql[] = "member_level >= 300 ";

		if($mem_level_arr){
			if(!in_array("0",$mem_level_arr)){
				for($i=0; $i<count($mem_level_arr); $i++){
					$mem_search .= ($i==0 ? " member_level ='$mem_level_arr[$i]'" : " or member_level ='$mem_level_arr[$i]'");
				}
				$searchSql[]=" (".$mem_search.")";
			}
		}

		if($email_yn) $searchSql[] = "email_yn = '$email_yn'";
		if($gender) $searchSql[] = "gender = '$gender'";
		if($sdate) $searchSql[] = "regdate>'".strtotime($sdate)."'";
		if($edate) $searchSql[] = "regdate < '".strtotime($edate)."'";
		if($searchSql) $where = " where ".implode(" and ",$searchSql);

		$field['mail_cnt']=db_result("select count(email) from tbl_member $where");

		$mcnt=1;
		$Rs=db_query("select email from tbl_member $where");
		while($pList=db_fetch($Rs)){

			if(($mcnt%100) == 0) sleep(5);
			$to_info = $pList['email'];

			sendMail($field['title'],$field['content'],$from_info,$to_info);
			$mcnt++;
		}

		db_insert("tbl_member_mail",$field);
		msg_page($field['mail_cnt']."건의 메일이 발송되었습니다.","mail_list.php");
	}


	if($_GET['mode']=="del"){
		db_query("delete from tbl_member_mail where idx='".$_GET['idx']."'");
		move_page("mail_list.php");
	}


	if($_POST['mode']=="check_del"){

		foreach($_POST['idx'] as $idx){
			db_query("delete from tbl_member_mail where idx='".$idx."'");
		}

		move_page("mail_list.php");
	}
?>