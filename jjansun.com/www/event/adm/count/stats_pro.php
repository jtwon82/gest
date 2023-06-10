<?
	include "../../common/function.php";
	include "../../common/db.php";
	include "../session.php";

		$sdate=	anti_injection($_POST['sdate']);
		$edate=	anti_injection($_POST['edate']);

	if($_POST['mode']=="update_byday"){
		db_query("
			INSERT INTO tbl_event_sum
						(reg_dates, event_gubun
						, web_lose, web_gift, web_gift2, web_gift3, web_gift4, web_gift5, web_gift6
						, mob_lose, mob_gift, mob_gift2, mob_gift3, mob_gift4, mob_gift5, mob_gift6)
			SELECT *
			FROM (SELECT
					j.reg_dates, event_gubun
					, SUM(CASE WHEN mobile='web' and win_type='lose' THEN 1 ELSE 0 END)    web_lose
					, SUM(CASE WHEN mobile='web' and win_type='gift' THEN 1 ELSE 0 END)    web_gift
					, SUM(CASE WHEN mobile='web' and win_type='gift2' THEN 1 ELSE 0 END)    web_gift2
					, SUM(CASE WHEN mobile='web' and win_type='gift3' THEN 1 ELSE 0 END)    web_gift3
					, SUM(CASE WHEN mobile='web' and win_type='gift4' THEN 1 ELSE 0 END)    web_gift4
					, SUM(CASE WHEN mobile='web' and win_type='gift5' THEN 1 ELSE 0 END)    web_gift5
					, SUM(CASE WHEN mobile='web' and win_type='gift6' THEN 1 ELSE 0 END)    web_gift6
					, SUM(CASE WHEN mobile='mob' and win_type='lose' THEN 1 ELSE 0 END)    mob_lose
					, SUM(CASE WHEN mobile='mob' and win_type='gift' THEN 1 ELSE 0 END)    mob_gift
					, SUM(CASE WHEN mobile='mob' and win_type='gift2' THEN 1 ELSE 0 END)    mob_gift2
					, SUM(CASE WHEN mobile='mob' and win_type='gift3' THEN 1 ELSE 0 END)    mob_gift3
					, SUM(CASE WHEN mobile='mob' and win_type='gift4' THEN 1 ELSE 0 END)    mob_gift4
					, SUM(CASE WHEN mobile='mob' and win_type='gift5' THEN 1 ELSE 0 END)    mob_gift5
					, SUM(CASE WHEN mobile='mob' and win_type='gift6' THEN 1 ELSE 0 END)    mob_gift6
				  FROM tbl_event j
				  WHERE j.reg_dates BETWEEN '$sdate' AND '$edate'
				  GROUP BY j.reg_dates, event_gubun
				  ) a
			ON DUPLICATE KEY UPDATE 
				web_lose=a.web_lose, web_gift=a.web_gift, web_gift2=a.web_gift2, web_gift3=a.web_gift3, web_gift4=a.web_gift4, web_gift5=a.web_gift5, web_gift6=a.web_gift6
				, mob_lose=a.mob_lose, mob_gift=a.mob_gift, mob_gift2=a.mob_gift2, mob_gift3=a.mob_gift3, mob_gift4=a.mob_gift4, mob_gift5=a.mob_gift5, mob_gift6=a.mob_gift6
		;");
	}
















	if($_POST['mode']=="update_uvpn"){
		$sql = "
		INSERT INTO event_oreoday_sum
					(reg_dates,
					 uv,
					 pv)
		SELECT *
		FROM (SELECT
				a.reg_dates,
				COUNT(DISTINCT reg_ip)    uv,
				COUNT(reg_ip)    pv
			  FROM event_oreoday a
			  WHERE reg_dates BETWEEN '$sdate' and '$edate'
			  GROUP BY a.reg_dates) a
		ON DUPLICATE KEY UPDATE uv = a.uv, pv = a.pv;
		";
		db_query($sql);
	}
	if($_POST['mode']=="update_sharesns"){
		$sql = "
		INSERT INTO event_oreoday_sum(reg_dates, kakao, facebook, twitter)
		   SELECT * FROM (
			  SELECT reg_dates
			  , SUM(CASE WHEN share_desc='kakao' THEN cnt END) kcnt
			  , SUM(CASE WHEN share_desc='facebook' THEN cnt END) fcnt
			  , SUM(CASE WHEN share_desc='twitter' THEN cnt END) tcnt
			  FROM event_oreoday_sharesns where reg_dates between '$sdate' and '$edate'
			  GROUP BY reg_dates
		   )a
		   ON DUPLICATE KEY UPDATE kakao=a.kcnt, facebook=a.fcnt, twitter=a.tcnt
		;";
		db_query($sql);
	}
	
	if($_POST['mode']=="update_byday_referer"){
		db_query("
		INSERT INTO event_oreoday_sum_referer
					(reg_dates
					,referer, uv, pv, cnt
					)
		SELECT *
		FROM (SELECT
				j.reg_dates, referer,
				COUNT(DISTINCT reg_ip) uv,
				COUNT(reg_ip) pv,
				COUNT(reg_ip) cnt
			  FROM event_oreoday j
			  WHERE j.reg_dates BETWEEN '$sdate' and '$edate'
			  GROUP BY j.reg_dates, referer) a
		ON DUPLICATE KEY UPDATE
			  uv = a.uv, pv = a.pv
		;
		;");
	}
?>