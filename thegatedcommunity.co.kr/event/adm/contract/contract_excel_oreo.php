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
								<th width="11%">참여일</th>
								<th width="7%">참여구분</th>
								<th width="7%">당첨구분</th>
								<th width="7%">이름</th>
								<th width="10%">전화번호</th>
								<th width="15%">주소</th>
								<th width="*">ssn ID</th>
	</tr>
						<?
							#$count=db_result("select count(*) from event_oreo_1601 $search");

							$i=0;
							$pRs=db_query("
								select *, case when title='mobile' then '모바일' else '웹' end titles
								, case when win_type='lose' then '꽝'
									when win_type='gift' then '모바일쿠폰'
									when win_type='addr' then '기프티박스'
									end win_types
								from event_oreo_1601 a
								$search
								order by reg_date desc 
								");
							while($pList=db_fetch($pRs)){
						?>
							<tr>
								<td>="<?=$pList['reg_date']?>"</td>
								<td>="<?=$pList['titles']?>"</td>
								<td>="<?=$pList['win_types']?>"</td>
								<td>="<?=$pList['uname']?>"</td>
								<td>="<?=$pList['pno1']?>-<?=$pList['pno2']?>-<?=$pList['pno3']?>"</td>
								<td>="<?=$pList['addr1']?> <?=$pList['addr2']?>"</td>
								<td>="<?=$pList['ssn']?>"</td>
							</tr>
						<?
								$i++;
							}
						?>
</table>
</body>
</html>