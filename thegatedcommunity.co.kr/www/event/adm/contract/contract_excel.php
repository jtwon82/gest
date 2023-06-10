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
								<th width="*">영상 제목</th>
								<th width="10%">이메일</th>
								<th width="10%">이름</th>
								<th width="10%">전화번호</th>
								<th width="10%">코드</th>
								<th width="5%">모바일</th>
								<th width="5%">상태</th>
								<th width="5%">좋아요</th>
								<th width="13%">참여일</th>
	</tr>
						<?
							$i=0;
							$pRs=db_query("
								select a.*
									, case when state = 5 then '삭제' 
											when state = 2 then '대기'
											when state = 1 then '신청'
										end state_str
									, ifnull(( select COUNT(like_cnt) from tbl_online_like b where a.idx=b.online_idx ),0) like_cnt
								from tbl_online a
								$search
								order by a.regdate desc 
								");
							while($pList=db_fetch($pRs)){
								$num = ($page-1) * $list_num + $i;
								$sortnum = $count-$num;
						?>
							<tr>
								<td>="<?=$pList['title']?>"</td>
								<td>="<?=$pList['email']?>"</td>
								<td>="<?=$pList['uname']?>"</td>
								<td>="<?=$pList['pno1']?>"</td>
								<td>="<?=$pList['youtube_code']?>"</td>
								<td>="<?=$pList['mobile']?>"</td>
								<td>="<?=$pList['state_str']?>"</td>
								<td>="<?=$pList['like_cnt']?>"</td>
								<td>="<?=$pList['regdate']?>"</td>
							</tr>
						<?
								$i++;
							}
						?>
</table>
</body>
</html>