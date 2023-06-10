<?
	include "./event/common/function.php";
	$start = getMillisecond();
	include "./event/common/db.php";
	include "./event/common/config.php";



	debug(__LINE__, json_encode( array('REQUEST'=>$_REQUEST) ));

/////// DB에 들어갈 값들을 변환합니다.

	$mode			= anti_injection($_REQUEST[mode]);
	$idx			= anti_injection($_REQUEST[idx]);
	$b_idx			= anti_injection($_REQUEST[b_idx]);
	$userno			= anti_injection($_SESSION['USER']['LOGIN_NO']);
	$ssn			= anti_injection($_SESSION['USER']['LOGIN_SSN']);
	$CHK			= anti_injection($_COOKIE['CHK']);
	$referer		= anti_injection(base64_decode($_COOKIE['from'])); if ($referer=='') $referer='direct';
	$mobile			= anti_injection($_REQUEST['mobile']);
	$uname			= anti_injection($_REQUEST['uname']);
	$title			= anti_injection($_REQUEST['title']);
	$contents		= anti_injection($_REQUEST['contents']);
	$content		= anti_injection($_REQUEST['content']);
	$pno			= anti_injection($_REQUEST['pno']);
	$pno1			= anti_injection($_REQUEST['pno1']);
	$pno2			= anti_injection($_REQUEST['pno2']);
	$pno3			= anti_injection($_REQUEST['pno3']);
	$user_type		= anti_injection($_REQUEST['user_type']);
	$user_type2		= anti_injection($_REQUEST['user_type2']);
	$addr1			= anti_injection($_REQUEST['addr1']);
	$addr2			= anti_injection($_REQUEST['addr2']);
	$addr3			= anti_injection($_REQUEST['addr3']);
	$reg_ip			= anti_injection(getUserIp());
	$url			= anti_injection($_REQUEST['url']);
	$pageno			= anti_injection($_REQUEST['pageno']);
	$page			= anti_injection($_REQUEST['page']);
	$chance_type	= anti_injection($_REQUEST['chance_type']);
	$chance_type2	= anti_injection($_REQUEST['chance_type2']);
	$agree			= anti_injection($_REQUEST['agree']);
	$agree2			= anti_injection($_REQUEST['agree2']);
	$share_desc		= anti_injection($_REQUEST['share_desc']);
	$answer1		= anti_injection($_REQUEST['answer1']);
	$answer2		= anti_injection($_REQUEST['answer2']);

	$bucket_txt		= anti_injection($_REQUEST['bucket_txt']);
	$hash_tag_txt	= anti_injection($_REQUEST['hash_tag_txt']);
	$sns_url		= anti_injection($_REQUEST['sns_url']);
	$oreo_name		= anti_injection($_REQUEST['oreo_name']);
	$selectType		= anti_injection($_REQUEST['selectType']);
	$passwd		= anti_injection($_REQUEST['passwd']);
	$reply		= anti_injection($_REQUEST['reply']);
	$selected		= anti_injection($_REQUEST['selected']);

	$letterType		= anti_injection($_REQUEST['letterType']);
	$to_name		= anti_injection($_REQUEST['to_name']);
	$from_name		= anti_injection($_REQUEST['from_name']);
	$letter_txt		= anti_injection($_REQUEST['letter_txt']);
	$rcver_name		= anti_injection($_REQUEST['rcver_name']);
	$rcver_pno		= anti_injection($_REQUEST['rcver_pno']);

	$tmp_chk		= anti_injection($_REQUEST[tmp_chk]);
	$userType		= anti_injection($_REQUEST[userType]);
	$sns_type		= anti_injection($_REQUEST['sns_type']);
	$tmpdata		= anti_injection($_REQUEST['tmpdata']);
	$returnUrl		= anti_injection($_SESSION['returnUrl']);

	$b_id			= anti_injection($_REQUEST['b_id']);

	$folder			= explode("/",$_SERVER['PHP_SELF']);$folder=$folder[1];
	$info[1]		= array('name'=>'jjansun', 'sdate'=>'2022-11-07', 'edate'=>'2122-12-31');

/////// DB에 들어갈 값들을 정리합니다.	db_query

	switch ($mode) {

		
		Case "BLOGIDCHK_INSERT" :
			$title= anti_injection($_REQUEST['title']);
			if($title){
				$etc1	=explode('/',$title);$etc1=$etc1[3];

				$sql= "select idx from tbl_board where b_id='$b_id' and etc1='$etc1'";
				$select= db_select($sql);

				if(!$select[idx]){

					$field['b_id']		=anti_injection($b_id);
					$field['userno']		=9;
					$field['title']		=addslashes(cutstr($_REQUEST['title'],100));
					$field['content']	=addslashes($_POST['content']);
					$field['link']		=anti_injection($_POST['link']);
					$field['notice']	=($_POST['notice'] ? $_POST['notice'] : "N");
					$field['secret']	=($_POST['secret'] ? $_POST['secret'] : "N");
					$field['ip']		=$_SERVER["REMOTE_ADDR"];
					$field['reg_date']	=date('Y-m-d H:i:s');
					$field['reg_dates']	=date('Y-m-d');
					$field['etc1']	=explode('/',$field['title']);$field['etc1']=$field['etc1'][3];

					//글번호 생성
					$rList = db_select("select ref from tbl_board order by ref desc");
					$ref = ($rList['ref'] ? $rList['ref']+1 : "1");
					$field['ref'] = $ref;

					db_insert("tbl_board",$field);	 
					$idx		=mysql_insert_id();
				}

				echo json_encode( array('mode'=>$mode, 'select'=>$select, 'idx'=>$idx) );
			}
			else{
				echo json_encode( array('mode'=>$mode, 'select'=>$select, 'idx'=>$idx) );
			}
			break;


	//LOGIN
		Case "REGIST_SNS_XXXXXXXXXXX" :
			$ssn	= getToken(15);

			$field['ssn']			=$ssn;
			$field['reg_date']		=date("Y-m-d H:i:s");
			$field['userid']		=anti_injection($_POST['userid']);
			$field['email']			=anti_injection($_POST['email']);
			$field['uname']			=anti_injection($_POST['uname']);
			$field['uage']			=anti_injection($_POST['uage']);
			$field['usex']			=anti_injection($_POST['usex']);
			$field['profile_image']	=anti_injection($_POST['profile_image']);
			$field['birthday']		=anti_injection($_POST['birthday']);
			$field['birthday']		=anti_injection($_POST['birthday']);
			$field['sns_regist']	=anti_injection($_POST['sns_regist']);
			$field['member_level']	=200;
			$field['member_type']	=1;	// 프론트 솔트레벨
			$field['visit_date']	=date("Y-m-d H:i:s");
			setCookie('ymd',base64_encode(date("Ymd")),0);
			setCookie('clear','clear',0);
			setCookie('chance_cnt',0,0);

			db_insert("tbl_member", $field);
			$idx		=mysql_insert_id();
			
			//setcookie('SSN', $ssn, time()+(60*60*24*365), '/', 0 );
			$_SESSION[SSN]= $ssn;
			/* 세션처리 */
			$_SESSION['USER']['LOGIN_SSN'] = $ssn;
			$_SESSION['USER']['LOGIN_NO'] = $idx;
			$_SESSION['USER']['LOGIN_ID'] = $field['userid'];
			$_SESSION['USER']['LOGIN_NAME'] = $field['uname'];
			$_SESSION['USER']['LOGIN_EMAIL'] = $field['email'];
			$_SESSION['USER']['LOGIN_LEVEL'] = $field['member_level'];
			$_SESSION['USER']['LOGIN_TYPE'] = $field['member_type'];
			$_SESSION['USER']['LOGIN_DATE'] = date("Y-m-d");

			$chance= 100 * $field['member_type'];
			charge_chance($reg_ip, $ssn, $ssn, 'login', 'login', $chance);

			if( $returnUrl ){
				echo msg_page('', $returnUrl);
			}
			else{
				debugToEcho(__LINE__, json_encode( array('mode'=>$mode, 'result'=>'o') ) );
			}
		break;

		Case "MEMBER_OUT" :
			$uInfo = db_select_assoc("select * from tbl_member where ssn='$ssn'");

			db_query("insert into tbl_member_invitehistory_leave(reg_date, userid, userid_friend, cnt) select reg_date, userid, userid_friend, cnt from tbl_member_invitehistory where userid='$uInfo[userid]' ");
			db_query("insert into tbl_member_invitehistory_leave(reg_date, userid, userid_friend, cnt) select reg_date, userid, userid_friend, cnt from tbl_member_invitehistory where userid_friend='$uInfo[userid]' ");
			db_query("delete from tbl_member_invitehistory where userid='$uInfo[userid]' ");
			db_query("delete from tbl_member_invitehistory where userid_friend='$uInfo[userid]' ");

			$sql = "
			insert into tbl_member_leave(userid, ssn, sns_regist, member_level, uname, reg_date, reg_dates, reg_ip)
				select userid, ssn, sns_regist, member_level, uname, reg_date, reg_dates, reg_ip from tbl_member where userid='$uInfo[userid]'
			";
			db_query($sql);

			db_query("delete from tbl_event_winneruser where ssn in (select ssn from tbl_member where userid='$uInfo[userid]') ");

			$sql = "delete from tbl_member where userid='$uInfo[userid]' ";
			db_query($sql);

			debugToEcho(__LINE__, json_encode( array('mode'=>$mode, 'result'=>'o') ) );
		break;

		Case "LOGIN" :
			$field['is_new']		=anti_injection($_POST['is_new']);
			$field['userid']		=anti_injection($_POST['userid']);
			$field['uname']			=anti_injection($_POST['uname']);
			$field['email']			=anti_injection($_POST['email']);
			$field['passwd']		=anti_injection($_POST['passwd']);
			$field['sns_regist']	=anti_injection($_POST['sns_regist']);

			if ($field['email']=='' || $field['userid']==''){
				debugToEcho(__LINE__, json_encode( array('mode'=>$mode, 'result'=>'x', 'msg'=>'empty requird value' ) ) );
				exit;
			}

			setCookie('ymd',base64_encode(date("Ymd")),0);
			setCookie('clear','clear',0);
			setCookie('chance_cnt',0,0);

			if( $field['sns_regist']!='' ){
				$mInfo = db_select_assoc("select * from tbl_member where userid='{$field[userid]}' and sns_regist='{$field[sns_regist]}'");
			}
			else{
				$mInfo = db_select_assoc("select * from tbl_member where userid='{$field[userid]}'");
			}
			$userid= $mInfo[userid];
			$ssn= $mInfo[ssn];
			#print_r($mInfo);exit;

			$chance= 100 * $mInfo['member_type'];
			charge_chance($reg_ip, $ssn, $ssn, 'login', 'login', $chance);

			#echo "$_COOKIE[referer], $ssn";exit;

			if ( $_COOKIE[referer]!="" && $_COOKIE[referer]!=$ssn ){	// 친구에게 포인트지급하기  신규여부 플래그 : && $field['is_new']=='is_new' 
				$CHK= $_COOKIE[referer];
				$chance_type2= 'fromsns';
				$chance= 200;
				#echo "$reg_ip, $ssn, $CHK, 'add', $chance_type2, $chance";exit;

				// fromsns
				$action_type= 'FROMSNS';
				$mInfo_friend = db_select_assoc("select * from tbl_member where ssn='$CHK'");	// 초대한친구
				$tbl_member_invitehistory = db_select_assoc("select count(1) cnt FROM tbl_member_invitehistory
						where userid='$userid' and userid_friend='$mInfo_friend[userid]' ");
				if($tbl_member_invitehistory[cnt]<1){
					#charge_chance($reg_ip, $CHK, $ssn, 'add', $chance_type2, $chance);

					db_query("insert into tbl_member_invitehistory(userid, userid_friend, cnt, reg_date)
						values('$userid', '$mInfo_friend[userid]', 1, now()) ON DUPLICATE KEY UPDATE update_date=now(), cnt=cnt+values(cnt) ");
				}
			}

			$loginchk= getToken(15);
			if($ssn==''){}else{
				db_query("INSERT INTO tbl_loginchk (reg_ip, reg_date, ssn, loginchk) VALUES('$reg_ip', now(), '$ssn', '$loginchk');");
			}


			if( $mInfo[sns_regist] ){

				//setcookie('SSN', $ssn, time()+(60*60*24*365), '/', 0 );
				$_SESSION[SSN]= $ssn;
				$_SESSION[loginchk]= $loginchk;
				/* 세션처리 */
				$_SESSION['USER']['LOGIN_SSN']		= $mInfo['ssn'];
				$_SESSION['USER']['LOGIN_NO']		= $mInfo['idx'];
				$_SESSION['USER']['LOGIN_ID']		= $mInfo['userid'];
				$_SESSION['USER']['LOGIN_NAME']		= $mInfo['uname'];
				$_SESSION['USER']['LOGIN_EMAIL']	= $mInfo['email'];
				$_SESSION['USER']['LOGIN_LEVEL']	= $mInfo['member_level'];
				$_SESSION['USER']['LOGIN_TYPE']		= $mInfo['member_type'];
				$_SESSION['USER']['LOGIN_DATE']		= date("Y-m-d");

				$visit[visit_date]			= date("Y-m-d H:i:s");
				db_update("tbl_member", $visit, "idx='{$mInfo['idx']}'");

				#if( $returnUrl!='' )
					echo msg_page('', $returnUrl);
				#else
				#	debugToEcho(__LINE__, json_encode( array('mode'=>$mode, 'result'=>'o' ) ) );
			}
			else{
				$passwd = db_password($field[passwd]);

				if( $mInfo && $mInfo[passwd]==$passwd ){

					//setcookie('SSN', $ssn, time()+(60*60*24*365), '/' );
					$_SESSION[SSN]= $ssn;
					$_SESSION[loginchk]= $loginchk;
					/* 세션처리 */
					$_SESSION['USER']['LOGIN_SSN']		= $mInfo['ssn'];
					$_SESSION['USER']['LOGIN_NO']		= $mInfo['idx'];
					$_SESSION['USER']['LOGIN_ID']		= $mInfo['userid'];
					$_SESSION['USER']['LOGIN_NAME']		= $mInfo['uname'];
					$_SESSION['USER']['LOGIN_EMAIL']	= $mInfo['email'];
					$_SESSION['USER']['LOGIN_LEVEL']	= $mInfo['member_level'];
					$_SESSION['USER']['LOGIN_TYPE']		= $mInfo['member_type'];
					$_SESSION['USER']['LOGIN_DATE']		= date("Y-m-d");

					$visit[visit_date]			= date("Y-m-d H:i:s");
					db_update("tbl_member", $visit, "idx='{$mInfo['idx']}'");

					//debugToEcho(__LINE__, json_encode( array('mode'=>$mode, 'result'=>'o' ) ) );
					echo msg_page('', $returnUrl);
				}
				else{
					debugToEcho(__LINE__, json_encode( array('mode'=>$mode, 'result'=>'x' ) ) );
				}
			}

		break;


		Case "REGISTER_CHK":
			$sql = "select count(*)c FROM tbl_member WHERE ssn='$ssn' ";
			$rs = db_select($sql);
			if ( !isset($_SESSION[USER][LOGIN_ID]) || $rs[c] == 0 ){
				debugToEcho(__LINE__, json_encode( array('mode'=>$mode, 'result'=>'x') ) );

			} else {
				$agree = db_select("select * FROM tbl_member WHERE ssn='$ssn' ");

				$infoM = info_user_chance($ssn);
				debugToEcho(__LINE__, json_encode( array('mode'=>$mode, 'result'=>'o', 'agree'=>$agree[agree], 'chance_info'=>$infoM) ) );
			}
		break;





	// COUPON REGIST
		Case "COUPON_REGIST":
			$action_type		= anti_injection($_REQUEST[action_type]);
			$subdata		= anti_injection($_REQUEST[subdata]);

			db_query("update tbl_member set member_type=member_type+1 WHERE ssn='$ssn' and not exists (select ssn from tbl_member_typehistory a where a.ssn='$ssn' and action_type='$action_type' and subdata='$subdata')");

			db_query("insert into tbl_member_typehistory(ssn, action_type, subdata, cnt, reg_date) values('$ssn', '$action_type', '$subdata', 1, now()) ON DUPLICATE KEY UPDATE update_date=now(), cnt=cnt+values(cnt) ");

			$member_typehistory= db_select_assoc("select cnt from tbl_member_typehistory where ssn='$ssn' and action_type='$action_type' and subdata='$subdata' ");

			$mInfo = db_select_assoc("select member_type from tbl_member where ssn='$ssn'");

			$_SESSION[USER][LOGIN_TYPE]= $mInfo[member_type];
			setCookie('clear','clear',0);
			setCookie('chance_cnt',0,0);
			//	$_COOKIE[member_type]= $mInfo[member_type];

			debugToEcho(__LINE__, json_encode( array('mode'=>$mode, 'result'=>'o', 'member_typehistory'=>$member_typehistory) ) ); 
		break;





	// ETC
		Case "GET_COMMON_INFO":
			$clear= $_COOKIE[clear];
			if( $clear=='clear' || rand()%100>90 || $_REQUEST[clear]=='clear'){

				{	// 배너
					if(!$page) $page=1;
					$list_num=8;
					$page_num=5;

					$start_num=($page-1)*$list_num;

					$where ="where 1=1 and open='Y' ";
					$form= "FROM tbl_banner a ";

					$count=db_result("select count(*) c $form $where");

					$i=0;
					$sql = "
						select
							*
						$form
						$where
						order by order_by desc
						limit $start_num, $list_num
					";
					#echo $sql;
					$pRs=db_query($sql);
					$i=0;
					while($pList=db_fetch($pRs,'assoc')){
						$num = ($page-1) * $list_num + $i;
						$sortnum = $count-$num;
						$pList[sortnum]= $sortnum;
						$list_banner[] = $pList;
						$i++;
					}
				}

				$sql= "select rnk, reg_date, /*concat('**',right(uname,1))*/ uname, concat('010****',right(pno,4))pno
							, win_giftname win_type_str, b.codes
				from (
					select @rn := if(@keyword = ssn , @rn+1, if(@keyword := ssn, 1, 1)) rnk
						, m.*
					from (
						select a.idx ,m.uname ,m.pno ,a.reg_date ,a.ssn ,a.chk , b.data2 gamecode ,a.win_type, a.win_giftname
						from tbl_event_joiner a join tbl_member m on a.ssn=m.ssn left join tbl_event_CHKER b on a.chk=b.chk
						where a.win_type in('gift','gift1','gift2','gift3','gift4','gift5','gift6')
						order by idx desc
					)m, (select @rn := 0, @keyword := null) r
					order by m.idx desc
				)a left join (
					select a.codes, a.title gamecode, a.val gamename, c.title giftcode, c.val upfile, c.val2 giftname
					from tbl_common a join tbl_common b on a.codes=b.pcodes 
					join tbl_common c on b.codes=c.pcodes 
					where a.codes in(27,28,29,98) and b.title ='gift' 
				)b on a.gamecode=b.gamecode and a.win_type=b.giftcode
				where rnk <4";
				$list_gift= db_select_list($sql);

				$sql= "select content, link, etc1,etc2 from tbl_board where b_id='022' and delete_date is null
					and etc1!='big'
					order by rand() limit 4
					";
				$pRs=db_query($sql);
				while($pList=db_fetch($pRs,'assoc')){
					$coupangadlist[] = $pList;
				}

				$sql= "select content, link, etc1,etc2 from tbl_board where b_id='022' and delete_date is null
					and etc1='big'
					order by rand() limit 4
					";
				$pRs=db_query($sql);
				while($pList=db_fetch($pRs,'assoc')){
					$coupangadlist[] = $pList;
				}

				$list_coupangad[coupangadlist]= $coupangadlist;

				$sql= "select content, link from tbl_board where b_id='022' and delete_date is null
					/*and datediff(now(),reg_date)<20 */
					order by rand() limit 4";
				$pRs=db_query($sql);
				while($pList=db_fetch($pRs,'assoc')){
					$eventlist[] = $pList;
				}
				$list_eventad[eventlist]= $eventlist;

				$list_notice= db_select_list("select idx, reg_date, reg_dates, notice, name, title, content
					from tbl_board where b_id='008' and notice='Y' and delete_date is null
					order by notice desc, ref desc, re_step desc, idx desc  limit 3");

				$sql = "
					select
						a.reg_dates, a.event_gubun
						, sum(gift)gift, sum(gift2)gift2, sum(gift3)gift3, sum(gift4)gift4, sum(gift5)gift5, sum(gift5)gift5, sum(gift6)gift6, sum(gift7)gift8, sum(gift9)gift9
					from (
						select -1 g, a.reg_dates , a.event_gubun
							, ifnull(sum(b.web_gift + b.mob_gift),0)*-1 gift
							, ifnull(sum(b.web_gift2 + b.mob_gift2),0)*-1 gift2
							, ifnull(sum(b.web_gift3 + b.mob_gift3),0)*-1 gift3
							, ifnull(sum(b.web_gift4 + b.mob_gift4),0)*-1 gift4
							, ifnull(sum(b.web_gift5 + b.mob_gift5),0)*-1 gift5
							, ifnull(sum(b.web_gift6 + b.mob_gift6),0)*-1 gift6
							, ifnull(sum(b.web_gift7 + b.mob_gift7),0)*-1 gift7
							, ifnull(sum(b.web_gift8 + b.mob_gift8),0)*-1 gift8
							, ifnull(sum(b.web_gift9 + b.mob_gift9),0)*-1 gift9
							, ifnull(sum(b.web_giftA + b.mob_giftA),0)*-1 giftA
						from (select * from dumy a,(select title event_gubun from tbl_common where pcodes=17) b ) a left join tbl_event_sum b on a.reg_dates =b.reg_dates and a.event_gubun=b.event_gubun
						where a.reg_dates = left(now(),10)
						group by a.reg_dates , a.event_gubun
						union all
						SELECT 
							0 g, left(now(),10) reg_dates, event_gubun 
							, gift, gift2, gift3, gift4, gift5, gift6, gift7, gift8, gift9, giftA
						FROM tbl_gift_config where reg_dates = left(now(),10)
						UNION ALL
						SELECT 
							1 g, left(now(),10) reg_dates , event_gubun
							, gift, gift2, gift3, gift4, gift5, gift6, gift7, gift8, gift9, giftA
						FROM tbl_gift_config where reg_dates ='0000-01-01'
					)a
					group by reg_dates, event_gubun 
				";
				$list_giftremain= db_select_list($sql);

				$list_common= json_encode( array('list_banner'=>$list_banner
					, 'list_gift'=>$list_gift, 'list_coupangad'=>$list_coupangad, 'list_eventad'=>$list_eventad, 'list_notice'=>$list_notice, 'list_giftremain'=>$list_giftremain) );

				file_put_contents("/home/jjansun/www/upfile/list_common.json", $list_common);
			}
			else{
				$list_common = file_get_contents("/home/jjansun/www/upfile/list_common.json");
			}
			debugToEcho(__LINE__, $list_common ,'info');
		break;



		Case "CHARGE_CHANCE":
			// 솔트 두배
			$sql= "select a.title gamecode, a.val gamename, b.title codename, b.val from tbl_common a join tbl_common b on a.codes=b.pcodes where b.title ='saltboom' and b.val>1";
			$list_salt[list_salt_2x]= db_select_list($sql);

			#$sql= "select count(1)join_cnt from tbl_chance where chance_type ='add' and chance_type2 like 'game13%' and `current` =100";
			$sql= "select count(DISTINCT b.userid)join_cnt from tbl_chance a join tbl_member b on a.ssn=b.ssn where chance_type ='add' and chance_type2 like 'game13%' and `current` =100";
			$list_salt[joincnt_game13]= db_select($sql);

			if ( !isset($_SESSION[USER][LOGIN_ID]) ){
				debugToEcho(__LINE__, json_encode( array('mode'=>$mode, 'result'=>'x', 'chance_info'=>$infoM
					, 'list_salt'=>$list_salt, 'play_list'=>$play_list, 'chking'=>$chking
					, 'end'=>getMillisecond()-$start) ) ,'info');
			}
			else{
				if ( $chance_type == 'eeeee' ){
				}else if ( $chance_type == 'getInfo' ){

					$clear= $_COOKIE[clear];
					if($clear=='clear' || rand()%100>1){ //$clear=='more' && rand()%100>90){
						setCookie('clear','',0);

						#$param			= array( 'mode'=>'CLEAR' );
						//httpPost("http://www.jjansun.com/_exec.php", $param);

						$infoM = info_user_chance($ssn);
						$infoM[uname_origin]= db_select_assoc("select uname from tbl_member where ssn='$ssn' ");$infoM[uname_origin]=$infoM[uname_origin][uname];
						$infoM[pno_origin]= db_select_assoc("select pno from tbl_member where ssn='$ssn' ");$infoM[pno_origin]=$infoM[pno_origin][pno];
						$infoM[birthday_origin]= db_select_assoc("select birthday from tbl_member where ssn='$ssn' ");$infoM[birthday_origin]=$infoM[birthday_origin][birthday];
						$infoM[usex_origin]= db_select_assoc("select usex from tbl_member where ssn='$ssn' ");$infoM[usex_origin]=$infoM[usex_origin][usex];

						// 누적솔트 10000 레벨업
						/*if($infoM[total_chance]>10000){
							db_query("update tbl_member set member_type=member_type+1
								WHERE ssn='$ssn' and not exists (select ssn from tbl_member_typehistory a where a.ssn='$ssn' and action_type='TOTAL_CHANCE_10000' )");

							db_query("insert into tbl_member_typehistory(ssn, action_type, subdata, cnt, reg_date)
								values('$ssn', 'TOTAL_CHANCE_10000', '', 1, now()) ON DUPLICATE KEY UPDATE update_date=now(), cnt=cnt+values(cnt) ");
						}*/
						// 누적솔트 50000 레벨업
						if($infoM[total_chance]>50000){
							db_query("update tbl_member set member_type=member_type+1
								WHERE ssn='$ssn' and not exists (select ssn from tbl_member_typehistory a where a.ssn='$ssn' and action_type='TOTAL_CHANCE_50000' )");

							db_query("insert into tbl_member_typehistory(ssn, action_type, subdata, cnt, reg_date)
								values('$ssn', 'TOTAL_CHANCE_50000', '', 1, now()) ON DUPLICATE KEY UPDATE update_date=now(), cnt=cnt+values(cnt) ");
						}
						// 누적솔트 100000 레벨업
						if($infoM[total_chance]>100000){
							db_query("update tbl_member set member_type=member_type+1
								WHERE ssn='$ssn' and not exists (select ssn from tbl_member_typehistory a where a.ssn='$ssn' and action_type='TOTAL_CHANCE_100000' )");

							db_query("insert into tbl_member_typehistory(ssn, action_type, subdata, cnt, reg_date)
								values('$ssn', 'TOTAL_CHANCE_100000', '', 1, now()) ON DUPLICATE KEY UPDATE update_date=now(), cnt=cnt+values(cnt) ");
						}
						// 누적솔트 500000 레벨업
						if($infoM[total_chance]>500000){
							db_query("update tbl_member set member_type=member_type+1
								WHERE ssn='$ssn' and not exists (select ssn from tbl_member_typehistory a where a.ssn='$ssn' and action_type='TOTAL_CHANCE_500000' )");

							db_query("insert into tbl_member_typehistory(ssn, action_type, subdata, cnt, reg_date)
								values('$ssn', 'TOTAL_CHANCE_500000', '', 1, now()) ON DUPLICATE KEY UPDATE update_date=now(), cnt=cnt+values(cnt) ");
						}
						// 누적솔트 1000000 레벨업
						if($infoM[total_chance]>1000000){
							db_query("update tbl_member set member_type=member_type+1
								WHERE ssn='$ssn' and not exists (select ssn from tbl_member_typehistory a where a.ssn='$ssn' and action_type='TOTAL_CHANCE_1000000' )");

							db_query("insert into tbl_member_typehistory(ssn, action_type, subdata, cnt, reg_date)
								values('$ssn', 'TOTAL_CHANCE_1000000', '', 1, now()) ON DUPLICATE KEY UPDATE update_date=now(), cnt=cnt+values(cnt) ");
						}
						// 누적솔트 5000000 레벨업
						if($infoM[total_chance]>5000000){
							db_query("update tbl_member set member_type=member_type+1
								WHERE ssn='$ssn' and not exists (select ssn from tbl_member_typehistory a where a.ssn='$ssn' and action_type='TOTAL_CHANCE_5000000' )");

							db_query("insert into tbl_member_typehistory(ssn, action_type, subdata, cnt, reg_date)
								values('$ssn', 'TOTAL_CHANCE_5000000', '', 1, now()) ON DUPLICATE KEY UPDATE update_date=now(), cnt=cnt+values(cnt) ");
						}
						// 누적솔트 10000000 레벨업
						if($infoM[total_chance]>10000000){
							db_query("update tbl_member set member_type=member_type+1
								WHERE ssn='$ssn' and not exists (select ssn from tbl_member_typehistory a where a.ssn='$ssn' and action_type='TOTAL_CHANCE_10000000' )");

							db_query("insert into tbl_member_typehistory(ssn, action_type, subdata, cnt, reg_date)
								values('$ssn', 'TOTAL_CHANCE_10000000', '', 1, now()) ON DUPLICATE KEY UPDATE update_date=now(), cnt=cnt+values(cnt) ");
						}

						$sql= "select reg_date
								, case when action_type='UPDATE_PROFILE_SUCC' then '개인정보 업데이트'
									when action_type='TOTAL_CHANCE_10000' then '누적 10,000이상'
									when action_type='TOTAL_CHANCE_50000' then '누적 50,000이상'
									end action_type_str
							FROM tbl_member_typehistory a where ssn='$ssn' and action_type in('UPDATE_PROFILE_SUCC','TOTAL_CHANCE_10000','TOTAL_CHANCE_50000')";
						$list_levelup= db_select_list($sql);
						$list_salt[list_levelup]= $list_levelup;

						$sql= "select reg_date
								, `current`
								, case when chance_type='login' then 'add' else chance_type end chance_type
						, case when chance_type2 like 'use%' then '즉석당첨'
							when chance_type2 like 'game%' then '적립게임'
							when chance_type2 like 'eventadd%' then '추가적립'
							when chance_type2 like 'coupangad%' then '추가적립'
							when chance_type2 like 'login%' then '로그인'
							when chance_type2 like 'fromsns%' then '친구초대'
							when chance_type2 like 'admin%' then '관리자'
							else chance_type2 end chance_type2
							FROM tbl_chance a where ssn='$ssn' and chance_type in('login','add','use') order by reg_date desc limit 5";
						$list_salt_get= db_select_list($sql);
						$list_salt[list_salt_get]= $list_salt_get;

						// 0: 꽝 1:스타벅스 2:롯데리아 3:베라 4:비타오백 5:gs만원 6:바나나우유
						$sql= "select a.reg_date ,chance_type, chance_type2 , gamename ,`current`
							, b.giftname win_type_str
						FROM tbl_chance a join tbl_event_joiner j
						on a.chk =j.chk left join (
							select a.codes, a.title gamecode, a.val gamename, c.title giftcode, c.val upfile, c.val2 giftname
							from tbl_common a join tbl_common b on a.codes=b.pcodes 
							join tbl_common c on b.codes=c.pcodes 
							where a.codes in(27,28,29,98) and b.title ='gift' 
						) b on a.chance_type2=b.gamecode and j.win_type=b.giftcode
						where a.ssn='$ssn' and chance_type in('use') order by a.reg_date desc limit 5";
						$list_salt_use= db_select_list($sql);
						$list_salt[list_salt_use]= $list_salt_use;

						#$sql= "select a.title gamecode, a.val gamename, b.title codename, b.val from tbl_common a join tbl_common b on a.codes=b.pcodes where b.title ='saltboom' and b.val>1";
						#$list_salt[list_salt_2x]= db_select_list($sql);

						$play_list= getPlayList($ssn);

						{	// 스코어 랭킹
							$sql_scoreM= "
								select gamecode, rank, date_format(a.reg_dates,'%m.%d')reg_dates, score, right(pno,4) pno, b.uname
								from ranking_score_award a left join tbl_member b on a.ssn=b.ssn
								where a.ssn='$ssn' and wk=week(now(),7)
								order by case when gamecode='jjansun_week' then 1 else 2 end
								limit 2
							";
							#$scoreM= db_select_assoc($sql_scoreM);
							#if($scoreM==false){
								#$scoreM= db_select_list($sql_scoreM);
								
								$pRs=db_query($sql_scoreM);
								while($pList=db_fetch($pRs,'assoc')){
									$scoreM[$pList[gamecode]] = $pList;
								}
							#}
						}
						$result= json_encode( array('mode'=>$mode, 'result'=>'o', 'chance_info'=>$infoM
							, 'scoreM'=>$scoreM, 'list_salt'=>$list_salt, 'play_list'=>$play_list, 'chking'=>$chking
							, 'end'=>getMillisecond()-$start) );
						#session_write_close();
						file_put_contents("/home/jjansun/www/upfile/{$ssn}.json", $result);
					}
					else{
						$result = file_get_contents("/home/jjansun/www/upfile/{$ssn}.json");
					}

					#debug(__LINE__, $_SESSION[loginchk]);

					debugToEcho(__LINE__, $result, 'info');

				}else if ( $chance_type == 'use' ){
					#if ($chance_type2=='use1')$user_type=100;
					#if ($chance_type2=='use2')$user_type=100;
					#if ($chance_type2=='use3')$user_type=100;
					if (strpos($chance_type2, 'use')>-1)$user_type=200;

					$chance_info= charge_chance($reg_ip, $ssn, $CHK, 'use', $chance_type2, $user_type*-1);
					debugToEcho(__LINE__, json_encode( array('mode'=>$mode, 'result'=>$chance_info[result]
						, 'res'=>$chance_info[res], 'chance_info'=>$chance_info[chance_info]
						, 'end'=>getMillisecond()-$start, 'chance_type'=>$chance_type)  ) );

					setCookie('clear','clear',0);
					setCookie('chance_cnt',$chance_info[chance_info][chance_cnt],0);
				
				}else if ( $chance_type == 'add' ){
					#if ($chance_type2=='coupangad')$user_type=300;
					debug(__LINE__, strpos($chance_type2, 'coupangad'));
					if (strpos($chance_type2, 'coupangad')>-1){	$CHK= $chance_type2;	$user_type=50;	}
					else if (strpos($chance_type2, 'eventadd')>-1){	$CHK= $chance_type2;	$user_type=50;	}
					else if (strpos($chance_type2, 'snsshare')>-1){	$CHK= $chance_type2;	$user_type=100;	}
					else if (strpos($chance_type2, 'fromsns')>-1){	$CHK= $chance_type2;	$user_type=100;	}
					else if (strpos($chance_type2, 'game')>-1){
						$sql= "select b.codes, a.title data2, b.title, b.val saltboom
							from tbl_common a join tbl_common b on a.codes=b.pcodes
							where b.title ='saltboom' and a.title='$chance_type2'";
						$saltboom= db_select($sql);
						if($saltboom[saltboom]>1)	$user_type= $user_type*$saltboom[saltboom];

						if($CHK==''){
							$CHK= anti_injection($_REQUEST[CHK]);
						}
						debug(__LINE__, $CHK);
					}

					$chance_info= charge_chance($reg_ip, $ssn, $CHK, 'add', $chance_type2, $user_type);
					debugToEcho(__LINE__, json_encode( array('mode'=>$mode, 'result'=>$chance_info[result]
						, 'res'=>$chance_info[res], 'chance_info'=>$chance_info[chance_info]
						, 'saltboom'=>$saltboom[saltboom]
						, 'score'=>$user_type*$saltboom[saltboom]
						, 'chance_type'=>$chance_type
						, 'end'=>getMillisecond()-$start
						)  ) );

					setCookie('clear','clear',0);
					setCookie('chance_cnt',$chance_info[chance_info][chance_cnt],0);
				
					#}else if ( $chance_type == 'sns' ){	// sns로 바꾸기
					#	$chance_info= charge_chance($reg_ip, $ssn, $CHK, 'sns', $chance_type2, 100);
					#	debugToEcho(__LINE__, json_encode( array('mode'=>$mode, 'result'=>$chance_info[result], 'res'=>$chance_info[res], 'chance_info'=>$chance_info[chance_info], 'end'=>getMillisecond()-$start, 'chance_type'=>$chance_type)  ) );

				} else {
					debugToEcho(__LINE__, json_encode( array('mode'=>$mode, 'result'=>'x', 'end'=>getMillisecond()-$start) ) );
				}
			}
		break;

		case "UPDATE_PROFILE":
			if($user_type=='img'){
				$Orientation		= anti_injection($_REQUEST['Orientation']);
				$path=	$_SERVER[DOCUMENT_ROOT] ."/upfile";

				$info= db_select("SELECT filename FROM tbl_member WHERE ssn='$ssn' ");
				file_delete($info[filename], $path);
				file_delete("thumb_".$info[filename], $path);

				$file_info= file_upload2('file', $path);

				thumbnail($path."/".$file_info[filename], $path."/"."thumb_$file_info[filename]",100,100,2,$Orientation);

				db_query("update tbl_member set update_date=now(), profile_image ='//www.jjansun.com/upfile/thumb_$file_info[filename]', filename='$file_info[filename]' where ssn='$ssn'");
				#debugToEcho(__LINE__, json_encode( array('mode'=>$mode, 'result'=>'o', 'data'=>$_REQUEST) ) );
			}
			else if($user_type=='uname'){
				db_query("update tbl_member set update_date=now(), uname='$uname' where ssn='$ssn'");
				#debugToEcho(__LINE__, json_encode( array('mode'=>$mode, 'result'=>'o', 'data'=>$_REQUEST) ) );
			}
			else if($user_type=='pno'){
				db_query("update tbl_member set update_date=now(), pno='$pno' where ssn='$ssn'");
				#debugToEcho(__LINE__, json_encode( array('mode'=>$mode, 'result'=>'o', 'data'=>$_REQUEST) ) );
			}
			else if($user_type=='birthday'){
				$birthday			= anti_injection($_REQUEST['birthday']);
				db_query("update tbl_member set update_date=now(), birthday='$birthday' where ssn='$ssn'");
				#debugToEcho(__LINE__, json_encode( array('mode'=>$mode, 'result'=>'o', 'data'=>$_REQUEST) ) );
			}
			else if($user_type=='usex'){
				$usex			= anti_injection($_REQUEST['usex']);
				db_query("update tbl_member set update_date=now(), usex='$usex' where ssn='$ssn'");
				#debugToEcho(__LINE__, json_encode( array('mode'=>$mode, 'result'=>'o', 'data'=>$_REQUEST) ) );
			}


			$action_type= $mode;
			$subdata= $user_type;

			db_query("insert into tbl_member_typehistory(ssn, action_type, subdata, cnt, reg_date) values('$ssn', '$action_type', '$subdata', 1, now()) ON DUPLICATE KEY UPDATE update_date=now(), cnt=cnt+values(cnt) ");

			$tbl_member_typehistory = db_select_assoc("select count(1) cnt FROM tbl_member_typehistory
					where ssn='$ssn' and action_type='$action_type' and subdata in('usex','birthday','pno','uname') ");

			if($tbl_member_typehistory[cnt]>=4){
				db_query("update tbl_member set member_type=member_type+1
					WHERE ssn='$ssn' and not exists (select ssn from tbl_member_typehistory a where a.ssn='$ssn' and action_type='{$action_type}_SUCC' )");

				db_query("insert into tbl_member_typehistory(ssn, action_type, subdata, cnt, reg_date) values('$ssn', '{$action_type}_SUCC', '', 1, now()) ON DUPLICATE KEY UPDATE update_date=now(), cnt=cnt+values(cnt) ");
			}

			$member_typehistory= db_select_assoc("select cnt from tbl_member_typehistory
					where ssn='$ssn' and action_type='{$action_type}_SUCC' ");

			$mInfo = db_select_assoc("select member_type from tbl_member where ssn='$ssn'");
			$_SESSION[USER][LOGIN_TYPE]= $mInfo[member_type];
			setCookie('clear','clear',0);
			setCookie('chance_cnt',0,0);

			
				debugToEcho(__LINE__, json_encode( array('mode'=>$mode, 'result'=>'o', 'data'=>$_REQUEST, 'member_typehistory'=>$member_typehistory) ) );
		break;

















	// GAME
		Case "CHKSTART" :
			if( !isset($_SESSION[USER][LOGIN_ID]) || $ssn=='' ){
				debugToEcho(__LINE__, json_encode( array('mode'=>$mode, 'result'=>'9') ) );
			}
			else{
				$sql = "SELECT CASE 
						WHEN LEFT(NOW(),10)<'{$info[1][sdate]}' THEN 'before'
						WHEN LEFT(NOW(),10)>'{$info[1][edate]}' THEN 'end'
						WHEN LEFT(NOW(),10) BETWEEN '{$info[1][sdate]}' AND '{$info[1][edate]}' THEN 
							CASE WHEN COUNT(*) >= 50000 THEN 'x' ELSE 'o' END 
						END c
					from tbl_event 
					where ssn = '$ssn'
					and reg_dates = left(now(), 10)
					";
				$rs = db_select($sql);

				if ( $rs[c]=='o' ){

					$chk = getToken(15);

					$play_list= getPlayList($ssn);

					#db_query("insert into tbl_event_CHKER(ssn, chk, reg_date, reg_ip) values('$ssn', '$chk', now(), '$reg_ip')");
					db_query("insert into tbl_event_CHKER(ssn, chk, reg_date, reg_ip, data2) values('$ssn', '$chk', now(), '$reg_ip', '$user_type')");

					setcookie('CHK', ($chk), 0);

					debugToEcho(__LINE__, json_encode( array('mode'=>$mode, 'result'=>'o', 'chk'=>$chk, 'play_list'=>$play_list) ) );
				}
				else {
					debugToEcho(__LINE__, json_encode( array('mode'=>$mode, 'result'=>$rs[c]) ) );
				}
			}
		break;


		Case "CHKWINNER" :

			if ($referer==''){
				$referer='direct';
			}

			if( !isset($_SESSION[USER][LOGIN_ID]) || $ssn=='' ){
				debugToEcho(__LINE__, json_encode( array('mode'=>$mode, 'result'=>'9') ) );

			}
			else if( isset($_COOKIE['CHK']) ){
				$pno = db_select("SELECT pno, uname, reg_ip, userid, ifnull(email,'')email FROM tbl_member WHERE ssn='$ssn' ");
				$pno1=$pno[pno];
				$uname=$pno[uname];
				$reg_ip=$pno[reg_ip];
				$userid=$pno[userid];
				$email=$pno[email];

				$attack = getUserIpIsAttac();

				$sql= "
					select CASE
						when instr('$user_type','game') then 'gift$user_type'
						WHEN	/* 30% 안에 들고 */
								(RAND()*100) <= pct
							THEN
								CASE
									WHEN left(NOW(),10)>'{$info[1][edate]}' then 'end'
									WHEN 'true'='$attack' then 'lose_attack'
									WHEN ''='$userid' or ''='$email' then 'lose_noneuserid'
									/*WHEN ''='$pno1' then 'lose_nonepno'*/
									WHEN EXISTS( SELECT 1 from tbl_event
										where reg_dates=left(NOW(), 10) and ssn='$ssn' GROUP BY ssn HAVING COUNT(1)>1000 ) THEN 'lose_overjoin'
									WHEN EXISTS( SELECT 1 FROM tbl_event_CHKER
										WHERE chk='$CHK' AND reg_date < DATE_ADD( NOW() , INTERVAL - 60 SECOND ) ) THEN 'lose_oversec'	/* 60초안에 시작 */
									WHEN EXISTS( SELECT 1 FROM tbl_event_CHKER
										WHERE ssn='$ssn' AND reg_date < DATE_ADD( NOW() , INTERVAL - 60 SECOND )
										GROUP BY ssn HAVING COUNT(*)>1000 ) THEN 'lose_over1' /* 60초안에 시도한횟수 */
									WHEN EXISTS( SELECT 1 FROM tbl_event_CHKER
										WHERE win_type IS NOT NULL and instr(win_type,'giftgame')<1 AND TIMESTAMPDIFF(minute, reg_date, now()) < winner ) THEN 'lose_overwin'	/* winner 안에 당첨 */
									WHEN EXISTS( SELECT 1 FROM tbl_event_CHKER
										WHERE win_type IS NOT NULL and instr(win_type,'giftgame')<1 AND reg_date > DATE_ADD( NOW() , INTERVAL -30 SECOND ) ) THEN 'lose_overwin30'	/* 30초안에 당첨된 */
									WHEN EXISTS( SELECT 1 FROM tbl_event_winneruser
										WHERE reg_dates between left(DATE_ADD( NOW() , INTERVAL -3 day ),10) and left(now(),10) and ssn='$ssn' and instr(win_type, 'giftgame')<1
										GROUP BY reg_dates HAVING SUM(win_cnt)>=1 ) THEN 'lose_overssn'
									WHEN EXISTS( SELECT 1 FROM tbl_event_winneruser
										WHERE reg_dates between left(DATE_ADD( NOW() , INTERVAL -3 day ),10) and left(now(),10) and reg_ip='$reg_ip' and instr(win_type, 'giftgame')<1
										GROUP BY reg_dates HAVING SUM(win_cnt)>=1 ) THEN 'lose_overregip'
									WHEN EXISTS( SELECT 1 FROM tbl_event_winneruser
										WHERE reg_dates between left(DATE_ADD( NOW() , INTERVAL -3 day ),10) and left(now(),10) and pno='$pno1' and instr(win_type, 'giftgame')<1
										GROUP BY reg_dates HAVING SUM(win_cnt)>=1 ) THEN 'lose_overregip'
									/*WHEN EXISTS( SELECT 1 FROM tbl_event_winneruser
										WHERE reg_dates between left(DATE_ADD( NOW() , INTERVAL -1 day ),10) and left(now(),10) and instr(win_type, 'giftgame')<1
										GROUP BY reg_dates HAVING SUM(win_cnt)>=1 ) THEN 'lose_overpno2'
									WHEN EXISTS( SELECT 1 FROM tbl_event_winneruser
										WHERE reg_dates=left(now(),10)  AND ssn='$ssn'
										GROUP BY reg_dates HAVING SUM(win_cnt)>=1 ) THEN 'lose_overssn'*/
									/*AND win_type!='gift' and event_gubun='$user_type' AND pno='{$pno1}{$pno2}{$pno3}' */
									when idx<=gift_pct and b.gift < c.gift then 'gift'
									when idx<=gift2_pct and b.gift2 < c.gift2 then 'gift2'
									when idx<=gift3_pct and b.gift3 < c.gift3 then 'gift3'
									when idx<=gift4_pct and b.gift4 < c.gift4 then 'gift4'
									when idx<=gift5_pct and b.gift5 < c.gift5 then 'gift5'
									when idx<=gift6_pct and b.gift6 < c.gift6 then 'gift6'
									when idx<=gift7_pct and b.gift7 < c.gift7 then 'gift7'
									when idx<=gift8_pct and b.gift8 < c.gift8 then 'gift8'
									when idx<=gift9_pct and b.gift9 < c.gift9 then 'gift9'
									when idx<=giftA_pct and b.giftA < c.giftA then 'giftA'
									else 'lose_pct'
								END
							ELSE 'lose_else' END c
					FROM (
							SELECT (RAND()*100) idx
						) a, (
							SELECT
								ifnull(sum(web_gift + mob_gift),0) gift
								, ifnull(sum(web_gift2 + mob_gift2),0) gift2
								, ifnull(sum(web_gift3 + mob_gift3),0) gift3
								, ifnull(sum(web_gift4 + mob_gift4),0) gift4
								, ifnull(sum(web_gift5 + mob_gift5),0) gift5
								, ifnull(sum(web_gift6 + mob_gift6),0) gift6
								, ifnull(sum(web_gift7 + mob_gift7),0) gift7
								, ifnull(sum(web_gift8 + mob_gift8),0) gift8
								, ifnull(sum(web_gift9 + mob_gift9),0) gift9
								, ifnull(sum(web_giftA + mob_giftA),0) giftA
							FROM tbl_event_sum 
							WHERE reg_dates=left(NOW(), 10) and event_gubun ='$user_type'
						) b, (
							select * from (
								SELECT 
									0 g, winner ,pct
									, gift_pct, gift2_pct, gift3_pct, gift4_pct, gift5_pct, gift6_pct, gift7_pct, gift8_pct, gift9_pct, giftA_pct
									, gift, gift2, gift3, gift4, gift5, gift6, gift7, gift8, gift9, giftA
								FROM tbl_gift_config where reg_dates = left(now(),10) and event_gubun ='$user_type'
								UNION ALL
								/*SELECT 1, 100, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0*/
								SELECT 
									1 g, winner ,pct
									, gift_pct, gift2_pct, gift3_pct, gift4_pct, gift5_pct, gift6_pct, gift7_pct, gift8_pct, gift9_pct, giftA_pct
									, gift, gift2, gift3, gift4, gift5, gift6, gift7, gift8, gift9, giftA
								FROM tbl_gift_config where reg_dates ='0000-01-01' and event_gubun ='$user_type'
							)a /*where g= case when DATE_FORMAT(now(),'%H') BETWEEN 0 AND 9 then 1 else 0 end*/
							UNION ALL SELECT 2, 100, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 limit 1
						) c
				";
				$rs = db_select($sql);
				if($rs[c]=='' || empty($rs[c]) || is_null($rs[c])
						//	|| $pno1=='' || empty($pno1) || is_null($pno1) 
						//	|| $uname=='' || empty($uname) || is_null($uname) 
					){
					$rs[c]='lose_empty';
				}
				#print_r($rs);exit;

				if( $rs[c]=='end' ){
					debugToEcho(__LINE__, json_encode( array('mode'=>$mode, 'result'=>'end') ) );
				}
				else if( $rs[c]=='x' ){

					debugToEcho(__LINE__, json_encode( array('mode'=>$mode, 'result'=>'x') ) );
				}
				else{
					$reason = $rs[c];
					if(strpos($user_type,'use')==0){
						$sql = "
							select c.codes, a.title data2, c.title win_type, c.val win_img, c.val2 win_giftname
							from tbl_common a join tbl_common b on a.codes=b.pcodes and instr(a.title,'$user_type')=1
							join tbl_common c on b.codes=c.pcodes and c.title='$reason'
							where a.pcodes=17
						";
						$reason_rs= db_select($sql);

						debug(__LINE__, json_encode( array('reason_rs'=>$reason_rs, 'reason'=>$reason) ));
					}

					if ( strpos($rs[c], 'lose')>-1 ){
						$sql = "insert into tbl_event(event_gubun, reg_date, reg_dates, ssn, chk, win_type, reason, mobile, reg_ip, referer, uname, pno1, share_desc )
							values('$user_type', now(), left(now(), 10), '$ssn', '$CHK', 'lose', '$reason', '$mobile', '$reg_ip', '$referer', '$uname', '$pno1', '$snsType' )";
						db_query($sql);

						$blob_idx= mysql_insert_id();
						#db_query("INSERT INTO tbl_event_sharesns(pidx, ssn, chk, reg_date, reg_dates, share_desc) VALUES('$blob_idx', '$ssn', '$chk', now(), left(now(),10), '$snsType') ON DUPLICATE KEY UPDATE cnt=cnt+1");

						$uname	= base64_encode($uname);
						$pno1	= base64_encode($pno1);
						$sql = "insert into tbl_event_joiner(event_gubun, reg_date, reg_dates, ssn, chk, win_type, reason, win_giftname, mobile, reg_ip, referer, uname, pno1, share_desc )
							values('$user_type', now(), left(now(), 10), '$ssn', '$CHK', 'lose', '$reason_rs[win_img]', '$reason_rs[win_giftname]', '$mobile', '$reg_ip', '$referer', '$uname', '$pno1', '$snsType' )";
						#db_query($sql);

						$chance_info= info_user_chance($ssn);

						$giftToken= "lose";

						#debugToEcho(__LINE__, json_encode( array('mode'=>$mode, 'result'=>'lose', 'losetype'=>$rs[c], 'chance_info'=>$chance_info ) ) );
					}
					else{
						setcookie(date('Ymd'), base64_encode($rs[c]), 0);
						$sql = "insert into tbl_event(event_gubun, reg_date, reg_dates, ssn, chk, win_type, reason, mobile, reg_ip, referer, uname, pno1, share_desc )
							values('$user_type', now(), left(now(), 10), '$ssn', '$CHK', '$reason', '$reason', '$mobile', '$reg_ip', '$referer', '$uname', '$pno1', '$snsType' )";
						db_query($sql);

						$blob_idx= mysql_insert_id();
						#db_query("INSERT INTO tbl_event_sharesns(pidx, ssn, chk, reg_date, reg_dates, share_desc) VALUES('$blob_idx', '$ssn', '$chk', now(), left(now(),10), '$snsType') ON DUPLICATE KEY UPDATE cnt=cnt+1");

						$uname	= base64_encode($uname);
						$pno1	= base64_encode($pno1);
						$sql = "insert into tbl_event_joiner(event_gubun, reg_date, reg_dates, ssn, chk
							, win_type, reason, win_giftname, mobile, reg_ip, referer, uname, pno1, share_desc )
							values('$user_type', now(), left(now(), 10), '$ssn', '$CHK'
							, '$reason', '$reason_rs[win_img]', '$reason_rs[win_giftname]', '$mobile', '$reg_ip', '$referer', '$uname', '$pno1', '$snsType' )";
						db_query($sql);

						$giftToken= getToken(15);
						$sql = "update tbl_event_CHKER set win_type = '".$rs[c]."', gift = '".$giftToken."', data2='$user_type' where chk = '$CHK' ";
						db_query($sql);

						$chance_info= info_user_chance($ssn);

						#debugToEcho(__LINE__, json_encode( array('mode'=>$mode, 'result'=>$giftToken, 'CHK'=>$CHK, 'timestamp'=>time(), 'chance_info'=>$chance_info) ) );
					}

					if(strpos($user_type,'game')==0){	// 
						$sql = "insert into ranking_score(reg_date, reg_dates, wk, gamecode, ssn, chk, score) values(now(), left(now(),10), week(now(),7), 'jjansun_week', '$ssn', '$CHK', '$user_type2')";
						#db_query($sql);

						$sql= "
							select sum(rank)chking from (	
								select * from (
									select rank, score from ranking_score_award where rank<=5 and gamecode ='jjansun_week' and wk=week(now(),7)
									union all
									select 0, '$user_type2'+0 score
								) a
								order by score desc
								limit 5
							) aa
						";
						$chking= db_select($sql);
						$myScore= db_select("select max(score)myScore from ranking_score_award where ssn='$ssn' and gamecode ='jjansun_week' and wk=week(now(),7) ");
						if( $chking[chking]!=15 || $myScore[myScore]<$user_type2 ){

							httpPost("http://www.jjansun.com/_exec.php", array( 'mode'=>'CLEAR' ));

							$chking='chking';
						}
					}
					
					debugToEcho(__LINE__, json_encode( array('mode'=>$mode, 'result'=>$giftToken, 'empty'=>$rs[c], 'CHK'=>$CHK, 'timestamp'=>time(), 'chance_info'=>$chance_info) ) );
				}
			}
			else{
				debugToEcho(__LINE__, json_encode( array('mode'=>$mode, 'result'=>'9') ) );
			}
		break;

		Case "WIN_TOKEN" :
			$TOKEN		= anti_injection($_REQUEST['TOKEN']);

			if ($TOKEN=='' || $ssn=='' ){
				debugToEcho(__LINE__, json_encode( array('mode'=>$mode, 'result'=>'x', 'msg'=>'wrong approach' ) ) );
			}
			else{
				$sql= "
					select b.* from (
						select data2, win_type from tbl_event_CHKER where gift='$TOKEN'
					)a left join(
						select a.title data2, c.title win_type, c.val win_img
						from tbl_common a join tbl_common b on a.codes=b.pcodes and instr(a.title,'use')=1
						join tbl_common c on b.codes=c.pcodes and b.title='gift'
					)b
					on a.data2=b.data2 and a.win_type=b.win_type
				";
				$rs= db_select($sql);

				debugToEcho(__LINE__, json_encode( array('mode'=>$mode, 'result'=>$rs[win_type], 'win_img'=>$rs[win_img]) ) );
			}
		break;

		Case "JOIN_GIFTf" :
				$pno = db_select("SELECT pno, uname FROM tbl_member WHERE ssn='$ssn' ");
				$pno1=$pno[pno];
				$uname=$pno[uname];

			$gift = base64_decode($_COOKIE[date('Ymd')]);

			$sql = "select CASE WHEN '$gift'='gift' THEN 0 ELSE IFNULL(SUM(win_cnt),0) END win_cnt from tbl_event_winneruser
					where reg_dates=left(now(),10) and event_gubun='$user_type' AND ssn='$ssn' AND pno='{$pno1}{$pno2}{$pno3}' AND win_type='$gift' ";
			$rs = db_select($sql);

			if ( $rs[win_cnt]>=3 ) {
				$sql = "UPDATE tbl_event_winneruser SET over_cnt=over_cnt+1 
					where reg_dates=left(now(),10) and event_gubun='$user_type' and reg_ip='$reg_ip' AND ssn='$ssn' AND pno='{$pno1}{$pno2}{$pno3}' AND win_type='$gift' ";
				db_query($sql);

				debugToEcho(__LINE__, json_encode( array('mode'=>$mode, 'result'=>'limit3') ) );
			}
			else{
				$sql = "select case when count(*)>0 then 1 else 0 end c 
						from tbl_event_CHKER 
						where chk = '$CHK'
						and reg_date > DATE_ADD( NOW( ) , INTERVAL -30 MINUTE )
						and win_ok is null
						";
				$rs = db_select($sql);

				if ( $rs[c]==1 ){
					$udata = "$ssn.$CHK.$uname.$pno1.$pno2.$pno3.$addr1.$addr2.$snsType.$user_type.$user_type2";
					$sql = "update tbl_event_CHKER set data1 = '$udata' where chk = '$CHK' ";
					db_query($sql);

					debugToEcho(__LINE__, json_encode( array('mode'=>$mode, 'result'=>'o') ) );
				}
				else{
					debugToEcho(__LINE__, json_encode( array('mode'=>$mode, 'result'=>'x', 'timestamp'=>time(), 'chk'=>$CHK) ) );
				}
			}
		break;

		Case "JOIN_GIFT" :
				$pno = db_select("SELECT pno, uname FROM tbl_member WHERE ssn='$ssn' ");
				$pno1=$pno[pno];
				$uname=$pno[uname];

			$gift = base64_decode($_COOKIE[date('Ymd')]);

			$udata = "$ssn.$CHK.$uname.$pno1.$pno2.$pno3.$addr1.$addr2.$snsType.$user_type.$user_type2";
			$sql = "select case when count(*)>0 then 1 else 0 end c 
					from tbl_event_CHKER 
					where chk = '$CHK'
					and reg_date > DATE_ADD( NOW( ) , INTERVAL -30 MINUTE )
					and data1 = '$udata'
					and win_type = '$gift'
					and win_ok is null
					";
			$rs = db_select($sql);

			if( $rs[c]==1 && $ssn && $CHK ){

				db_query("update tbl_event_CHKER set win_ok='1' where chk = '$CHK'");

				//$referer = anti_injection(base64_decode($referer));
				//if ($referer==''){
				//	$referer='direct';
				//}

				$sql = "update tbl_event set update_date = now(), pno1='$pno1', uname='$uname' where ssn='$ssn' and chk='$CHK' AND reg_dates BETWEEN LEFT(DATE_ADD(NOW(),INTERVAL -1 DAY),10) AND LEFT(NOW(),10) ";
				db_query($sql);

				db_query("INSERT INTO tbl_event_winnerhistory(reg_date, reg_dates, ssn, uname, pno) select now(), left(now(),11), '$ssn', '$uname', '{$pno1}{$pno2}{$pno3}' ON DUPLICATE KEY UPDATE update_date=now(), cnt=cnt+1 ");

				/*if( $gift=='gift' ){
					$infoM = charge_chance($reg_ip, $ssn, $CHK, 'gift', 5);
				}
				else{
					$infoM[chance_info] = info_user_chance($ssn);
				}*/
				$infoM[chance_info] = info_user_chance($ssn);
				$sql = "
					INSERT INTO tbl_event_winneruser(reg_date, reg_dates, event_gubun, reg_ip, ssn, chk, pno, win_type, win_cnt)
						SELECT now(), left(now(),10), '$user_type', '$reg_ip', '$ssn', '$CHK', '$pno1', '$gift', 1
					ON DUPLICATE KEY UPDATE win_cnt = win_cnt+1, update_date = now()
				";
				db_query($sql);

				#if (strpos($gift, 'game')<0){
					db_query("INSERT INTO tbl_event_sum(reg_dates, event_gubun, {$mobile}_{$gift}) VALUES(LEFT(NOW(),10), '$user_type', 1) ON DUPLICATE KEY UPDATE {$mobile}_{$gift} = {$mobile}_{$gift} + 1;");
				#}

				db_query("UPDATE tbl_member SET pno='$pno1', uname='$uname' WHERE ssn='$ssn'");

				setcookie('CHK', "", 0);
				setcookie(date('Ymd'), "", 0);
				
				#$param			= array( 'mode'=>'SEND_SMS', 'CHK'=>$CHK );
				#$SEND_RESULT	= httpPost("http://db-studio.dbins-promy.com:8081/_exec.php", $param);
				#db_query("insert into tbl_event_result(ssn, chk, pno1, win_type, reg_date, reg_dates) values('$ssn', '$CHK', '$pno1', '$gift', now(), left(now(),10))");

				debugToEcho(__LINE__, json_encode( array('mode'=>$mode, 'result'=>'o', 'chance_info'=>$infoM[chance_info]) ) );
			}
			else{
				debugToEcho(__LINE__, json_encode( array('mode'=>$mode, 'result'=>'x', 'timestamp'=>time(), 'chk'=>$CHK) ) );
			}
		break;
















	// LIST
		Case "BOARD_LIST":
			if(!$page) $page=1;
			$list_num=10;
			$page_num=5;

			$start_num=($page-1)*$list_num;

			if ($user_type=='salt'){
				$where ="where a.ssn='$ssn' and chance_type in('login','add','use') ";
				$form= "FROM tbl_chance a left join tbl_event_joiner j
				on a.chk =j.chk left join (
							select a.codes, a.title gamecode, a.val gamename, c.title giftcode, c.val upfile, c.val2 giftname
							from tbl_common a join tbl_common b on a.codes=b.pcodes 
							join tbl_common c on b.codes=c.pcodes 
							where a.codes in(27,28,29,98) and b.title ='gift'
				)b on chance_type2=b.gamecode and j.win_type =b.giftcode ";
			}
			else if ($user_type=='gift'){
				$where ="where a.ssn='$ssn' and chance_type in('use') ";
				$form= "FROM tbl_chance a join tbl_event_joiner j
				on a.chk =j.chk left join (
							select a.codes, a.title gamecode, a.val gamename, c.title giftcode, c.val upfile, c.val2 giftname
							from tbl_common a join tbl_common b on a.codes=b.pcodes 
							join tbl_common c on b.codes=c.pcodes 
							where a.codes in(27,28,29,98) and b.title ='gift'
				)b on chance_type2=b.gamecode and j.win_type =b.giftcode ";
			}

			$count=db_result("select count(*) c $form $where");

			$i=0;
			$sql = "
				select
					a.*
						, win_giftname win_type_str, b.gamename
						, case when chance_type='login' then 'add' else chance_type end chance_type
						, case when chance_type2 like 'use%' then '즉석당첨'
							when chance_type2 like 'game%' then '적립게임'
							when chance_type2 like 'eventadd%' then '추가적립'
							when chance_type2 like 'coupangad%' then '추가적립'
							when chance_type2 like 'login%' then '로그인'
							when chance_type2 like 'fromsns%' then '친구초대'
							when chance_type2 like 'admin%' then '관리자'
							else chance_type2 end chance_type2
				$form
				$where
				order by a.reg_date desc 
				limit $start_num, $list_num
			";
			#echo $sql;
			#debug(__LINE__, json_encode( array('sql'=>$sql) ));
			$pRs=db_query($sql);
			$i=0;
			while($pList=db_fetch($pRs,'assoc')){
				$num = ($page-1) * $list_num + $i;
				$sortnum = $count-$num;
				$pList[sortnum]= $sortnum;
				$row[] = $pList;
				$i++;
			}

			// 좌우 버튼만 있는함수
			$paging = page_listJjansun($page, $count, $list_num, $page_num, "B.BoardList({page})");

			debugToEcho(__LINE__, json_encode( array('mode'=>$mode, 'result'=>'o', 'list'=>$row, 'count'=>$count, 'paging'=>$paging) ) ,'info');
		break;

		Case "BANNER_LIST":
			if(!$page) $page=1;
			$list_num=8;
			$page_num=5;

			$start_num=($page-1)*$list_num;

			$where ="where 1=1 and open='Y' ";
			$form= "FROM tbl_banner a ";

			$count=db_result("select count(*) c $form $where");

			$i=0;
			$sql = "
				select
					*
				$form
				$where
				order by order_by desc
				limit $start_num, $list_num
			";
			#echo $sql;
			$pRs=db_query($sql);
			$i=0;
			while($pList=db_fetch($pRs,'assoc')){
				$num = ($page-1) * $list_num + $i;
				$sortnum = $count-$num;
				$pList[sortnum]= $sortnum;
				$row[] = $pList;
				$i++;
			}

			// 좌우 버튼만 있는함수
			#$paging = page_listJjansun($page, $count, $list_num, $page_num, "B.BoardList({page})");

			debugToEcho(__LINE__, json_encode( array('mode'=>$mode, 'result'=>'o', 'list'=>$row, 'count'=>$count, 'paging'=>$paging, 'end'=>getMillisecond()-$start) ) ,'info');
		break;
		
		Case "BOARD_LIST_CUSTOMER":
			if(!$page) $page=1;
			$list_num=	5;
			$page_num=	5;

			$start_num=($page-1)*$list_num;

				$form= "FROM tbl_board a ";
				$where ="where b_id='$b_id' and delete_date is null";

			$count=db_result("select count(*) c $form $where");

			$i=0;
			$sql = "
				select
					*
				$form
				$where
				order by notice desc, ref desc, re_step desc, idx desc
				limit $start_num, $list_num
			";
			#echo $sql;
			$pRs=db_query($sql);
			$i=0;
			while($pList=db_fetch($pRs,'assoc')){
				$num = ($page-1) * $list_num + $i;
				$sortnum = $count-$num;

				$re_count=db_result("select count(*) re_count from tbl_board_comment a where b_idx='$pList[idx]' ");
				$pList[sortnum]= $sortnum;
				$pList[re_count]= $re_count;
				$row[] = $pList;
				$i++;
			}

			// 좌우 버튼만 있는함수
			$paging = page_listJjansun($page, $count, $list_num, $page_num, "B.BoardListCustomer({page})");

			debugToEcho(__LINE__, json_encode( array('mode'=>$mode, 'result'=>'o', 'list'=>$row, 'count'=>$count, 'paging'=>$paging) ) ,'info');
		break;
		
		Case "BOARD_LIST_NOTICE":
			if(!$page) $page=1;
			$list_num=	5;
			$page_num=	5;

			$start_num=($page-1)*$list_num;

				$form= "FROM tbl_board a ";
				$where ="where b_id='$b_id' ";

			$count=db_result("select count(*) c $form $where");

			$i=0;
			$sql = "
				select
					*
				$form
				$where
				order by a.reg_date desc 
				limit $start_num, $list_num
			";
			#echo $sql;
			$pRs=db_query($sql);
			$i=0;
			while($pList=db_fetch($pRs,'assoc')){
				$num = ($page-1) * $list_num + $i;
				$sortnum = $count-$num;

				$re_count=db_result("select count(*) re_count from tbl_board_comment a where b_idx='$pList[idx]' ");
				$pList[sortnum]= $sortnum;
				$pList[re_count]= $re_count;
				$row[] = $pList;
				$i++;
			}

			// 좌우 버튼만 있는함수
			$paging = page_listJjansun($page, $count, $list_num, $page_num, "B.BoardListNotice({page})");

			debugToEcho(__LINE__, json_encode( array('mode'=>$mode, 'result'=>'o', 'list'=>$row, 'count'=>$count, 'paging'=>$paging) ) ,'info');
		break;
		
		Case "BOARD_LIST_INVITE":
			if(!$page) $page=1;
			$list_num=	100;
			$page_num=	5;

			$start_num=($page-1)*$list_num;

				$form= "FROM ranking_score_award a join tbl_member m on a.ssn=m.userid ";
				$where ="where gamecode='jjansun_invite' and wk = week(now(),7) and a.ssn!='' ";

			$count=db_result("select count(*) c $form $where");

			$i=0;
			$sql = "
				select
					a.*
					, ifnull(m.uname,'[탈퇴한회원]')uname
					, m.profile_image
					, m.member_type
				$form
				$where
				order by rank 
				limit $start_num, $list_num
			";
			#echo $sql;
			$pRs=db_query($sql);
			$i=0;
			while($pList=db_fetch($pRs,'assoc')){
				$num = ($page-1) * $list_num + $i;
				$sortnum = $count-$num;

				$pList[sortnum]= $sortnum;
				$row[] = $pList;
				$i++;
			}

			// 좌우 버튼만 있는함수
			$paging = page_listJjansun($page, $count, $list_num, $page_num, "B.BoardListRankInvite({page})");

			$jjansun_info = json_decode(file_get_contents("/home/jjansun/www/upfile/jjansun_info.json"),true);

			debugToEcho(__LINE__, json_encode( array('mode'=>$mode, 'result'=>'o', 'list'=>$row, 'count'=>$count, 'paging'=>$paging, 'update_date'=>$jjansun_info[update_date], 'end'=>getMillisecond()-$start) ) ,'info');
		break;
		
		Case "BOARD_LIST_WEEKRANK":
			if(!$page) $page=1;
			$list_num=	100;
			$page_num=	5;

			$start_num=($page-1)*$list_num;

				$form= "FROM ranking_score_award a join tbl_member m on a.ssn=m.ssn ";
				$where ="where gamecode='jjansun_week' and wk = week(now(),7) and a.ssn!='' ";

			$count=db_result("select count(*) c $form $where");

			$i=0;
			$sql = "
				select
					a.*
					, ifnull(m.uname,'[탈퇴한회원]')uname
					, m.profile_image
					, m.member_type
				$form
				$where
				order by rank 
				limit $start_num, $list_num
			";
			#echo $sql;
			$pRs=db_query($sql);
			$i=0;
			while($pList=db_fetch($pRs,'assoc')){
				$num = ($page-1) * $list_num + $i;
				$sortnum = $count-$num;

				$pList[sortnum]= $sortnum;
				$row[] = $pList;
				$i++;
			}

			// 좌우 버튼만 있는함수
			$paging = page_listJjansun($page, $count, $list_num, $page_num, "B.BoardListRankWeek({page})");

			$jjansun_info = json_decode(file_get_contents("/home/jjansun/www/upfile/jjansun_info.json"),true);

			debugToEcho(__LINE__, json_encode( array('mode'=>$mode, 'result'=>'o', 'list'=>$row, 'count'=>$count, 'paging'=>$paging, 'update_date'=>$jjansun_info[update_date], 'end'=>getMillisecond()-$start) ) ,'info');
		break;
		
		Case "BOARD_LIST_ACCUMULATE":
			if(!$page) $page=1;
			$list_num=	100;
			$page_num=	5;

			$start_num=($page-1)*$list_num;

				$form= "FROM ranking_score_award a join tbl_member m on a.ssn=m.ssn ";
				$where ="where gamecode='jjansun_accumulate' and wk = week(now(),7) ";

			$count=db_result("select count(*) c $form $where");

			$i=0;
			$sql = "
				select
					a.*, ifnull(m.uname,'[탈퇴한회원]')uname
					, m.profile_image
					, m.member_type
				$form
				$where
				order by rank  
				limit $start_num, $list_num
			";
			$pRs=db_query($sql);
			$i=0;
			while($pList=db_fetch($pRs,'assoc')){
				$num = ($page-1) * $list_num + $i;
				$sortnum = $count-$num;

				$pList[sortnum]= $sortnum;
				$row[] = $pList;
				$i++;
			}

			// 좌우 버튼만 있는함수
			$paging = page_listJjansun($page, $count, $list_num, $page_num, "B.BoardListRankAccumulate({page})");

			$jjansun_info = json_decode(file_get_contents("/home/jjansun/www/upfile/jjansun_info.json"),true);

			debugToEcho(__LINE__, json_encode( array('mode'=>$mode, 'result'=>'o', 'list'=>$row, 'sql'=>$sql, 'count'=>$count, 'paging'=>$paging, 'update_date'=>$jjansun_info[update_date], 'end'=>getMillisecond()-$start) ) ,'info');
		break;


		Case "BOARD_NEXT_PREV":
			$direct		= anti_injection($_REQUEST[direct]);

			$where = "where 1=1 and a.chk=b.chk AND a.isBlobData='Y' AND a.publicType='Y' ";
			$order = "order by ";
			if($direct=='prev'){
				$where .= "and a.idx>$idx";
				$order .= "a.idx ";
			}
			if($direct=='next'){
				$where .= "and a.idx<$idx";
				$order .= "a.idx desc";
			}

			$sql = "
				SELECT a.idx, b.blobname $form, event_dblife_blob b
				$where
				$order
				limit 1
			";

			$pRs = db_query($sql);
			while($pList=db_fetch($pRs, 'assoc')){
				$row[] = $pList;
			}
			debugToEcho(__LINE__, json_encode( array('mode'=>$mode, 'result'=>'o', 'list'=>$row) ) );
		break;





		Case "LIST_REPLY":
			$clear		= anti_injection($_REQUEST['clear']);

			$g= "list";
			if(!$page) $page=1;

				$list_num=5;
				$page_num=5;
				$start_num=($page-1)*$list_num;

				if($clear=='clear'){
					$_SESSION['USER']['scroll_idx']=0;
				}else{
					$_SESSION['USER']['scroll_idx']=(!$_SESSION['USER']['scroll_idx'] ? $list_num : $_SESSION['USER']['scroll_idx']+$list_num);
				}
				$start_num= $_SESSION['USER']['scroll_idx'];
				$page= $start_num;


			#if($clear=='more' && rand()%100>90){
			if(TRUE){
				$Minfo = db_select("select pno FROM tbl_member WHERE ssn='$ssn' ");

				$where ="where b_idx='$b_idx' ";

				//$count=db_result("select count(*) c from tbl_board_comment a $where");

				$i=0;
				$sql = "
					select
						a.*
						, case when a.ssn='$ssn' then 'Y' else 'N' end is_me
						, case when exists (select ssn from tbl_member m where m.ssn='$ssn') then 'Y' else 'N' end is_loginok
						, (select profile_image from tbl_member m where m.ssn=a.ssn) profile_image
					from tbl_board_comment a
					$where
					and re_level=0
					order by notice desc, ref desc, re_step desc, idx desc
					limit {$start_num}, {$list_num}
				";
				#echo "<textarea>$sql</textarea>";
				$pRs=db_query($sql);
				while($pList=db_fetch($pRs,'assoc')){

					$comment= db_select_list("select a.*
						, case when a.ssn='$ssn' then 'Y' else 'N' end is_me
						, case when exists (select ssn from tbl_member m where m.ssn='$ssn') then 'Y' else 'N' end is_loginok
						, (select profile_image from tbl_member m where m.ssn=a.ssn) profile_image
						from tbl_board_comment a where ref='$pList[ref]' and re_level!=0 order by notice desc, ref desc, re_step desc, idx desc");
					$pList[comments]= $comment;
					$pList[reply_cnt]= count($comment);
					$pList[title]= stripslashes($pList[title]);

					$rows[] = $pList;
				}

				// 좌우 버튼만 있는함수
				#$paging = page_list_onlynpbtn($page, $count, $list_num, $page_num, "window.boardLoad({page})", "", "<img src='images/section2_list_arrow_prev.png'>", "<img src='images/section2_list_arrow_next.png'>", "");

				$end = getMillisecond() - $start;
				$result= json_encode( array('mode'=>$mode, 'result'=>'o', 'list'=>$rows, 'count'=>$count, 'end'=>$end) );
				session_write_close();
				#file_put_contents("/home/dongsuh/www.oreo-event.com/upfile/{$g}_{$page}.json", $result);
			}
			else{
				$result = file_get_contents("/home/dongsuh/www.oreo-event.com/upfile/{$g}_{$page}.json");
			}
			debugToEcho(__LINE__,$result);
		break;

		Case "INSERT_REPLY":
			$chk_decode= json_decode(base64_decode($CHK),true);

			$Minfo = db_select("select * FROM tbl_member WHERE ssn='$ssn' ");
			$uname= $Minfo[uname];
			$pno= $Minfo[pno];

			$content	= $contents;

			if($Minfo[ssn]=='' ){	//invalid_info
				debugToEcho(__LINE__, json_encode( array('mode'=>$mode, 'result'=>'invalid_ssn') ) );
			}
			//	else if($content=='' ){			//invalid_content
			//		debugToEcho(__LINE__, json_encode( array('mode'=>$mode, 'result'=>'invalid_content') ) );
			//	}
			else{
				//	$count= db_result("SELECT COUNT(1) FROM tbl_board WHERE b_id='$b_id' AND reg_dates=LEFT(NOW(),10)  ");
				//	if($count>0){
				//		debugToEcho(__LINE__, json_encode( array('mode'=>$mode, 'result'=>'limit_join') ) );
				//	}
				//	else{

					if ($reply=='reply') {
						$org = db_select("select ref, re_level, re_step, passwd, notice from tbl_board_comment where idx='".$idx."'");

						//댓글 순서 한 칸씩 밀기
						db_query("update tbl_board_comment set re_step=re_step-1 where ref='".$org['ref']."' and re_step>'".$org['re_level']."'");

						$passwd=			substr($pno, -4,4);
						$content=			addslashes($content);
						$title=				cutstr($content,100);

						//글저장
						$field[passwd]=		$passwd;
						$field[notice]=		$org['notice'];
						$field[ref]=		$org['ref'];
						$field[re_level]=	$org['re_level'] + 1;
						$field[re_step]=	$org['re_step'] - 1;

						db_query("insert into tbl_board_comment(b_idx, reg_date, ssn, name, passwd, title, content, ip, ref, re_level, re_step, notice)
							values('$b_idx', now(), '$ssn', '$uname', '$field[passwd]', '$title', '$content', '$reg_ip', '$field[ref]', '$field[re_level]', '$field[re_step]', '$field[notice]')");

						debugToEcho(__LINE__, json_encode( array('mode'=>$mode, 'result'=>'o') ) );
					}
					else{
						//글번호 생성
						$rList = db_select("select ref from tbl_board_comment order by ref desc");
						$ref = ($rList['ref'] ? $rList['ref']+1 : "1");

						$passwd=			substr($pno, -4,4);
						$content=			addslashes($content);
						$title=				cutstr($content,100);

						db_query("insert into tbl_board_comment(b_idx, reg_date, ssn, name, passwd, title, content, ip, ref)
							values('$b_idx', now(), '$ssn', '$uname', '$passwd', '$title', '$content', '$reg_ip', '$ref')");

						debugToEcho(__LINE__, json_encode( array('mode'=>$mode, 'result'=>'o') ) );
					}
			//	}
			}
		break;

		Case "MODIFY_REPLY":
			$contents=			addslashes($contents);
			$title=				cutstr($contents,100);
			db_query("update tbl_board_comment set title='$title', content='$contents' where idx='$idx' ");
			debugToEcho(__LINE__, json_encode( array('mode'=>$mode, 'result'=>'o', 'contents'=>stripslashes($contents)) ) );
		break;






	// ETC
		Case "CLEAR":
			db_query("update ranking_score_award set ssn='' where wk=WEEK(now(),7) ");
			$sql= "
								INSERT INTO ranking_score_award( reg_dates, wk, gamecode, ssn, rank, score)
									SELECT reg_dates, wk, gamecode, ssn, rank, score
									FROM (
										select a.*
											, IF(@pre <> gamecode, @rank := 1, @rank := @rank+1) rank, @pre := gamecode pre
										from (
											select left(now(),10)reg_dates ,week(now(),7)wk, 'jjansun_invite' gamecode,userid_friend ssn
												,count(userid ) score
											from tbl_member_invitehistory group by userid_friend 
										)a join tbl_member m on a.ssn=m.userid, ( SELECT @rank :=0 )b
										order by score desc
									) aa
								ON DUPLICATE KEY UPDATE ssn=aa.ssn, score=aa.score, reg_dates=aa.reg_dates;
			";
			db_query($sql);

			$sql= "
								INSERT INTO ranking_score_award( reg_dates, wk, gamecode, ssn, rank, score)
									SELECT reg_dates, wk, gamecode, ssn, rank, score
									FROM (
										select a.*
											, IF(@pre <> gamecode, @rank := 1, @rank := @rank+1) rank, @pre := gamecode pre
										from (
											SELECT b.reg_dates ,week(now(),7)wk , 'jjansun_week' gamecode ,a.ssn
											, IFNULL(SUM(CASE WHEN chance_type in('','add') THEN current END),0)score
											FROM tbl_chance a, (SELECT LEFT(NOW(),10)reg_dates, '$ssn' ssn) b
											where week(reg_date,7)= WEEK(now(),7) and chance_type in('','add') and instr(chance_type2,'game')>0
											and not EXISTS ( select ssn from tbl_member m where m.ssn=a.ssn and email in('','') )
											group by a.ssn
											having IFNULL(SUM(CASE WHEN chance_type in('','add') THEN current END),0)>0	
											order by score desc
										)a join tbl_member m on a.ssn=m.ssn, ( SELECT @rank :=0 )b
									) aa
								ON DUPLICATE KEY UPDATE ssn=aa.ssn, score=aa.score, reg_dates=aa.reg_dates;
			";
			db_query($sql);

			$sql="
								INSERT INTO ranking_score_award( reg_dates, wk, gamecode, ssn, rank, score)
									SELECT reg_dates, wk, gamecode, ssn, rank, score
									FROM (
										select a.*
											, IF(@pre <> gamecode, @rank := 1, @rank := @rank+1) rank, @pre := gamecode pre
										from (
											SELECT b.reg_dates ,week(now(),7)wk , 'jjansun_accumulate' gamecode ,a.ssn
											, IFNULL(SUM(CASE WHEN chance_type in('','add') THEN current END),0)score
											FROM tbl_chance a, (SELECT LEFT(NOW(),10)reg_dates, '$ssn' ssn) b
											where instr(chance_type2,'game')>0
											and not EXISTS ( select ssn from tbl_member m where m.ssn=a.ssn and email in('','') )
											group by a.ssn
											having IFNULL(SUM(CASE WHEN chance_type in('','add') THEN current END),0)>0	
											order by score desc
										)a join tbl_member m on a.ssn=m.ssn, ( SELECT @rank :=0 )b
									) aa
								ON DUPLICATE KEY UPDATE ssn=aa.ssn, score=aa.score, reg_dates=aa.reg_dates;
			";
			db_query($sql);

					#if(true){
					#if(rand()%100>1){ //$clear=='more' && rand()%100>90){
						$result= json_encode( array('result'=>'o', 'update_date'=>date("Y-m-d H:i:s"), 'end'=>getMillisecond()-$start) );
						session_write_close();
						file_put_contents("/home/jjansun/www/upfile/jjansun_info.json", $result);
					#}
					#else{
					#	$result = file_get_contents("/home/jjansun/www/upfile/jjansun_info.json");
					#}

			debugToEcho(__LINE__, json_encode( array('mode'=>$mode, 'result'=>'o', 'end'=>getMillisecond()-$start) ) );
		break;

		Case "DELETE_REPLY":
			$count=	db_result("select count(*) c from tbl_board_comment where idx='$idx' ");
			if($count==1){
				db_query("delete from tbl_board_comment where idx='$idx'  ");

				debugToEcho(__LINE__, json_encode( array('mode'=>$mode, 'result'=>'o') ) );
			}
			else{
				debugToEcho(__LINE__, json_encode( array('mode'=>$mode, 'result'=>'x') ) );
			}
		break;

		Case "SYSTEM_INFO":
			echo json_encode( array('get_server_cpu_usage'=>get_server_cpu_usage()
				, 'get_server_memory_usage'=>get_server_memory_usage()
				, 'get_server_storage_usage'=>get_server_storage_usage()
			) );
		break;





	}
	include "./event/common/dbclose.php";



?>