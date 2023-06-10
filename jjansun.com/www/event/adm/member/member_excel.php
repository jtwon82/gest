<?
	include "../../common/function.php";
	include "../../common/db.php";
	include "../../common/config.php";
	include "../session.php";

	$filedate = date("Y-m-d");
	$filename = iconv("UTF-8", "EUC-KR", $site_name." 회원데이터($filedate)");

	header( "Content-type: application/vnd.ms-excel; charset=utf-8" );
	header( "Content-Disposition: attachment; filename=$filename.xls" );
	header( "Content-Description: PHP4 Generated Data" );

	$search = stripslashes($_GET['search']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<table width="100%" border="1" cellpadding="0" cellspacing="0">
	<tr>
		<th bgcolor="#C5DBF7">번호</th>
		<th bgcolor="#C5DBF7">아이디</th>
		<th bgcolor="#C5DBF7">이름</th>
		<th bgcolor="#C5DBF7">회원등급</th>
		<th bgcolor="#C5DBF7">이메일</th>
		<th bgcolor="#C5DBF7">가입일</th>
	</tr>
<?
	$i=1;
	$pRs = db_query("select  *  from tbl_member $search order by regdate desc ");
	while ($pList = db_fetch($pRs)){
?>
	<tr>
		<td align="center"><?=$i?></td>
		<td align="center"><?=$pList['userid']?></td>
		<td align="center"><?=$pList['name']?></td>
		<td align="center">
			<?
				$mList=db_select("select * from tbl_member_level where level_code='".$pList['member_level']."'");
				echo $mList['level_name'];
			?>
		</td>
		<td align="center"><?=$pList['email']?></td>
		<td align="center"><?=date("Y-m-d", $pList['regdate'])?></td>
	</tr>
<?
		$i++;
	}
?>
</table>
</body>
</html>