<?
	include "../event/common/function.php";
	include "../event/common/db.php";
	include "../event/common/config.php";
	include("../event/adm/board/board_config.php");

	$page			=anti_injection($_GET['page']);
	$search_key		=anti_injection($_GET['search_key']);
	$search_val		=anti_injection($_GET['search_val']);
	$b_code			=anti_injection($_GET['b_code']);

	if($search_key && $search_val) $search_keyword=" and $search_key like '%$search_val%'";
	if($b_code!="") $search_keyword.=" and b_code = '$b_code'";

	if(!$page) $page=1;
	$list_num=20;
	$startnum=($page-1)*$list_num;

	$count=db_result("select count(idx) from tbl_board where b_id='".$b_id."' $search_keyword");
	$pRs=db_query("select * from tbl_board where b_id='".$b_id."' $search_keyword order by notice desc, ref desc limit $startnum, $list_num");
	$totalpage  = ceil($count / $list_num);

?>

			<? include "../head.php"; ?>
			<? include "../head.gnb.php"; ?>
			
			<? include "./{$bbs_skin}/list.php";?>
			
			<? include "../tail.popup.php"; ?>
			<? include "../tail.php"; ?>
