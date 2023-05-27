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
								<th width="10%">날짜</th>
								<th width="5%">업체명</th>
								<th width="5%">성함</th>
								<th width="5%">직함</th>
								<th width="5%">부서명</th>
								<th width="8%">연락처</th>
								<th width="8%">이메일</th>
								<th width="5%">신청자유형</th>
								<th width="10%">희망시간</th>
								<th width="*">주소</th>
	</tr>
						<?
							#$count=db_result("select count(*) from event_oreo_1601 $search");

							$i=0;
							$pRs=db_query("
								select a.*
								from tbl_online_community a
								$search
								order by reg_date desc 
								");
							while($pList=db_fetch($pRs)){
						?>
							<tr>
								<td><?=$pList['reg_date']?></td>
								<td><?=$pList['business_name']?></td>
								<td><?=$pList['uname']?></td>
								<td><?=$pList['stratum_name']?></td>
								<td><?=$pList['department_name']?></td>
								<td><?=$pList['pno1']?></td>
								<td><?=$pList['email']?></td>
								<td><?=$pList['userType']?></td>
								<td><?=$pList['selMonth']?>월 <?=$pList['selDay']?>일 <?=$pList['selHour']?>시</td>
								<td><?=$pList['addr1']?> <?=$pList['addr2']?></td>
							</tr>
						<?
								$i++;
							}
						?>
</table>
</body>
</html>