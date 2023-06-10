<?
	include "../../common/function.php";
	include "../../common/db.php";
	include "../session.php";

	if($_POST['mode']=="check_del"){
		foreach($_POST['idx'] as $idx){
			db_query("delete from tbl_member_del where idx='".$idx."'");
		}
		move_page("out_list.php");
	}

	if($_GET['mode']=="out"){
		//회원정보 삭제
		db_query("update tbl_member set member_level='', passwd='', email='', title='', area='', education='', job='', company='', facebook='', twitter='', content='', careerpath1='', careerpath2='', careerpath3='', careerpath4='', careerpath5='', careerpath6='', visit_num='', visit_time='', visit_ip='' where userid='".$_GET['userid']."'");

		//탈퇴신청 상태변경
		db_query("update tbl_member_del set state=2 where userid='".$_GET['userid']."'");
		move_page("out_list.php");
	}

	if($_GET['mode']=="cancel"){

		//탈퇴신청 상태변경
		db_query("update tbl_member set member_level=500 where userid='".$_GET['userid']."'");

		db_query("delete from member_del where idx='".$_GET['idx']."'");
		msg_page("회원탈퇴가 철회되어 일반회원으로 복귀되었습니다.","out_list.php");
	}
?>