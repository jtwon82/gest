<?
	include "../../common/function.php";
	include "../../common/db.php";
	include "../../common/config.php";
	include "../session.php";

	$filedate = date("Y-m-d").'-'.rand();
	$filename = iconv("UTF-8", "EUC-KR", $site_name." 응모데이타($filedate)");

	header( "Content-type: application/vnd.ms-excel; charset=utf-8" );
	header( "Content-Disposition: attachment; filename=$filename.xls" );
	header( "Content-Description: PHP4 Generated Data" );

	$search = stripslashes($_GET['search']);
	#echo $search;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<table width="100%" border="1" cellpadding="0" cellspacing="0">
	<tr>
								<th width="9%">참여일</th>
								<th width="7%">ip</th>
								<th width="10%">이름</th>
								<th width="7%">전화번호</th>
								<th width="10%">userid</th>
								<th width="10%">ssn ID</th>
	</tr>
						<?
							#$count=db_result("select count(*) from event_oreo_1601 $search");

							$from = "from tbl_member a ";
							$i=0;
							$pRs=db_query("
								select *
								$from
								$search
								order by a.idx desc 
								");
							while($pList=db_fetch($pRs)){
						?>
							<tr>
								<td><?=$pList['reg_date']?></td>
								<td><?=$pList['reg_ip']?></td>
								<td><?=$pList['uname']?>(<?=$pList['member_type']?>)</td>
								<td>="<?=$pList['pno']?>"</td>
								<td><?=$pList['userid']?></td>
								<td><?=$pList['ssn']?></td>
							</tr>
						<?
								$i++;
							}
						?>
</table>
</body>
</html>