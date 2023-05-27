<?
	include "../../common/function.php";
	include "../../common/db.php";
	include "../session.php";

	if($_POST['mode']=="write"){
		$field['name'] = anti_injection($_POST['name']);
		$field['userid'] = anti_injection($_POST['userid']);
		$field['passwd'] = db_password(anti_injection($_POST['passwd']));
		$field['email']=anti_injection($_POST['email1'])."@".anti_injection($_POST['email2']);
		$field['title']=anti_injection($_POST['title']);
//		$field['area']=anti_injection($_POST['area']);
//		$field['education']=anti_injection($_POST['education']);
//		$field['job']=anti_injection($_POST['job']);
//		$field['company']=anti_injection($_POST['company']);
//		$field['facebook']=anti_injection($_POST['facebook']);
//		$field['twitter']=anti_injection($_POST['twitter']);
//		$field['content']=$_POST['content'];
		$field['member_level']=anti_injection($_POST['member_level']);
		$field['regdate']=date('Y-m-d h:i:s');

		if(db_count("tbl_member","userid='$field[userid]'")>0){
			msg_page("이미 가입된 아이디입니다.");
		}

		if(!trim($field['name']) || !trim($field['userid']) || !trim($field['passwd'])){
			msg_page("필수 입력사항이 누락되었습니다.");
		}

		db_insert("tbl_member",$field);
		move_page("member_list.php");
	}

	if($_POST['mode']=="modify"){
		$field['name']=anti_injection($_POST['name']);
		$field['email']=anti_injection($_POST['email']);
		$field['title']=anti_injection($_POST['title']);
//		$field['area']=anti_injection($_POST['area']);
//		$field['education']=anti_injection($_POST['education']);
//		$field['job']=anti_injection($_POST['job']);
//		$field['company']=anti_injection($_POST['company']);
//		$field['facebook']=anti_injection($_POST['facebook']);
//		$field['twitter']=anti_injection($_POST['twitter']);
//		$field['content']=$_POST['content'];
		$field['member_level']=anti_injection($_POST['member_level']);
		$field['update_time']=date('Y-m-d h:i:s');
		db_update("tbl_member", $field, "idx='".$_POST['idx']."'");

		if($_POST['passwd']){
			db_query("update tbl_member set passwd=password('". anti_injection($_POST['passwd'])."') where idx='".$_POST['idx']."'");
		}

		move_page("member_list.php");
	}

	if($_POST['mode']=="change"){
		$idx=$_POST['idx'];
		$field[$_POST['type']]=$_POST['val'];

		db_update("tbl_member",$field,"idx='".$idx."'");
		echo "succ";
	}

	if($_POST['mode']=="check_del"){
		foreach($_POST['idx'] as $idx){
			db_query("update tbl_member set member_level='', passwd='', email='', title='', area='', education='', job='', company='', facebook='', twitter='', content='', careerpath1='', careerpath2='', careerpath3='', careerpath4='', careerpath5='', careerpath6='', visit_num='', visit_time='', visit_ip='' where idx='".$idx."'");
		}
		move_page("member_list.php");
	}

	if($_GET['mode']=="del"){
		db_query("update tbl_member set member_level='', passwd='', email='', title='', area='', education='', job='', company='', facebook='', twitter='', content='', careerpath1='', careerpath2='', careerpath3='', careerpath4='', careerpath5='', careerpath6='', visit_num='', visit_time='', visit_ip='' where idx='".$_GET['idx']."'");
		move_page("member_list.php");
	}

	//아이디 중복체크
	if($_POST['mode']=="uniq"){
		$type=$_POST['type'];
		$val =iconv("UTF-8","EUC-KR",$_POST["val"]);
		$count=db_count("tbl_member","$type='$val'");
		echo $count;
	}
?>