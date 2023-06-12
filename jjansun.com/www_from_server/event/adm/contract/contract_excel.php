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
								<th width="10%">참여일</th>
								<th width="7%">ip</th>
								<th width="5%">이벤트구분</th>
								<th width="5%">유입구분</th>
								<th width="10%">이름</th>
								<th width="7%">전화번호</th>
								<th width="7%">win_type</th>
								<th width="7%">reason</th>
								<th width="7%">giftname</th>
								<th width="7%">current</th>
								<th width="*">주소</th>
								<th width="10%">ssn ID</th>
								<th width="10%">chk ID</th>
	</tr>
						<?
							#$count=db_result("select count(*) from event_oreo_1601 $search");

							$from = "from tbl_event a left join tbl_chance b on a.chk=b.chk left join tbl_member m on a.ssn=m.ssn join (
								select a.codes, a.title gamecode, a.val gamename, c.title giftcode, c.val upfile, c.val2 giftname
								from tbl_common a join tbl_common b on a.codes=b.pcodes 
								join tbl_common c on b.codes=c.pcodes 
								where a.codes in(27,28,29,98) and b.title ='gift'
							) g on a.event_gubun=g.gamecode and a.win_type=g.giftcode ";
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
								<td>="<?=$pList['reg_date']?>"</td>
								<td>="<?=$pList['reg_ip']?>"</td>
								<td>="<?=$pList['event_gubun']?>"</td>
								<td>="<?=$pList['referer']?>"</td>
								<td>="<?=$pList['uname']?>(<?=$pList['member_type']?>)"</td>
								<td>="<?=$pList['pno1']?>"</td>
								<td>="<?=$pList['win_type']?>"</td>
								<td>="<?=$pList['reason']?>"</td>
								<td>="<?=$pList['giftname']?>"</td>
								<td>="<?=$pList['current']?>"</td>
								<td>="<?=$pList['addr1']?> <?=$pList['addr2']?>"</td>
								<td>="<?=$pList['ssn']?>"</td>
								<td>="<?=$pList['chk']?>"</td>
							</tr>
						<?
								$i++;
							}
						?>
</table>
</body>
</html>