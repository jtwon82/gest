<?
	include $_SERVER[DOCUMENT_ROOT]."/event/common/config.php";

	function get_server_cpu_usage() {

		$load = sys_getloadavg();
		return $load[0];
	}
	function get_server_memory_usage() {

		$free         = shell_exec('free');
		$free         = (string) trim($free);
		$free_arr     = explode("\n", $free);
		$mem          = explode(" ", $free_arr[1]);
		$mem          = array_filter($mem);
		$mem          = array_merge($mem);
		$memory_usage = $mem[2] / $mem[1] * 100;

		$report['total']         = $mem[1];
		$report['used']          = $mem[2];
		$report['usage_percent'] = $memory_usage;

		return $report;
	}
	function get_server_storage_usage() {
		$ds                    = disk_total_space("/");
		$df                    = disk_free_space("/");
		$disk['free']          = $df;
		$disk['free_GB']          = formatBytes($df,8);
		$disk['use']           = $ds - $df;
		$disk['use_GB']           = formatBytes($ds - $df,8);
		$disk['entire']        = $ds;
		$disk['entire_GB']        = formatBytes($ds,8);
		$disk['usage_percent'] = $df / $ds * 100;
		return $disk;
	}
	function formatBytes($size, $precision = 2)
	{
		$base = log($size, 1024);
		$suffixes = array('', 'K', 'M', 'G', 'T');   

		return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
	}
	function debug($line, $string){
		error_log("[". date('Y-m-d H:i:s'). "] [".$line."] ". $string."\n", 3, ROOT ."/../logs/db_log.log.".date('Ymd'));
	}
	function debugToEcho($line, $string, $info="debug"){
		global $_SESSION;
		$ssn= $_SESSION['USER']['LOGIN_SSN'];

		if($info=='debug')	debug($line, $ssn ." ". $string);
		echo $string;
	}
	function getPlayList($ssn){
		$sql_game= "
			select chance_type2, count(1)cnt FROM tbl_chance a where ssn='$ssn' and chance_type ='add' and reg_dates = left(now(),10)
			group by chance_type2
		";
		$sql_game="
			select data2 chance_type2, count(1)cnt from tbl_event_CHKER where instr(data2, 'game')>0 and left(reg_date,10)=left(now(),10) and ssn='$ssn'
			group by data2
		";
		$sql_use= "
			select chance_type2, count(1)cnt FROM tbl_chance a where ssn='$ssn' and chance_type ='use' and reg_dates = left(now(),10)
			group by chance_type2
		";
		return array('game_list'=>db_select_list($sql_game), 'use_list'=>db_select_list($sql_use) );
	}

	function getCoupangAdList(){
//		$sql = "
//			SELECT * FROM (
//				SELECT * FROM (
//					SELECT w.reg_dates, w.ssn, win_type, concat('*',substr(m.uname,-1))uname 
//					FROM tbl_event_winneruser w join tbl_member m on w.ssn=m.ssn
//					WHERE win_type IN('gift2','gift3','gift4','gift5')
//				)a
//				union all select reg_dates, ssn, win_type, concat('*',substr(uname,-1))uname from tbl_event_winneruser_fake
//			)a order by 1 desc
//			limit 10
//		";
//		$pRs=db_query($sql);
//		while($pList=db_fetch($pRs,'assoc')){
//			$winner_list[] = $pList;
//		}
//		$list[winner_list]= $winner_list;

		$sql= "select content, link from tbl_board where b_id='022' and delete_date is null
			/*and datediff(now(),reg_date)<20 */
			order by rand() limit 4";
		$pRs=db_query($sql);
		while($pList=db_fetch($pRs,'assoc')){
			$coupangadlist[] = $pList;
		}
		$list[coupangadlist]= $coupangadlist;

		$sql= "select content, link from tbl_board where b_id='022' and delete_date is null
			/*and datediff(now(),reg_date)<20 */
			order by rand() limit 4";
		$pRs=db_query($sql);
		while($pList=db_fetch($pRs,'assoc')){
			$coupangadlist2[] = $pList;
		}
		$list[coupangadlist2]= $coupangadlist2;

		return $list;
	}

	function getMillisecond()
	{
		list($microtime,$timestamp) = explode(' ',microtime());
		$time = $timestamp.substr($microtime, 2, 3);

		return $time;
	}
	function br2nl($str) {
		$str = preg_replace("/(\r\n|\n|\r)/", "", $str);
		return preg_replace("=&lt;br */?&gt;=i", "\n", $str);
	} 
	function getSkinList(){
		$list = getFileList($_SERVER[DOCUMENT_ROOT]."/board");
		foreach($list as $k => $v ){
			if($v[type]=='dir'){
				$ex=explode("/", $v[name]);
				$row[] = $ex[count($ex)-2];
			}
		}
		return $row;
	}
	function getFileList($dir){
		$retval = array();

		if(substr($dir, -1) != "/") {
			$dir .= "/";
		}

		// open pointer to directory and read list of files
		$d = @dir($dir) or die("getFileList: Failed opening directory {$dir} for reading");
		while(FALSE !== ($entry = $d->read())) {
			// skip hidden files
			if($entry{0} == ".") continue;

			if(is_dir("{$dir}{$entry}")) {
				$tmp = array(
				'name' => "{$dir}{$entry}/",
				'type' => filetype("{$dir}{$entry}"),
				'size' => 0,
				'lastmod' => filemtime("{$dir}{$entry}")
				);
			} elseif(is_readable("{$dir}{$entry}")) {
				$tmp = array(
				'name' => "{$dir}{$entry}",
				'type' => mime_content_type("{$dir}{$entry}"),
				'size' => filesize("{$dir}{$entry}"),
				'lastmod' => filemtime("{$dir}{$entry}")
				);
			}
			$retval[] = $tmp;
		}
		$d->close();

		return $retval;
	}

	function chkLogin($auth, $return_url="/main"){
		global $_SESSION;

		if ($auth=="member"){
			$userno			= anti_injection($_SESSION['USER']['LOGIN_NO']);
			if(!$userno){
				msg_page("회원전용 메뉴입니다. 로그인을 해주세요~", $return_url);
			}
		}
		else if ($_SESSION['USER'][LOGIN_LEVEL]<$auth){
			msg_page("회원전용 등급이 부족합니다 짠순이를 열심히 이용해주세요~", $return_url);
		}
		else{
			#echo $auth;

		}
	}
	function httpPost2($url, $params="", $headers=""){
		$postData = '';
		foreach($params as $k => $v) 
		{ 
			$postData .= $k . '='.$v.'&'; 
		}
		$postData = rtrim($postData, '&');

		$ch = curl_init();  

		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch, CURLOPT_HEADER, false); 
		curl_setopt($ch, CURLOPT_POST, count($postData));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);    

		if ($headers)
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$output=curl_exec($ch);

		curl_close($ch);
		return $output;
	}

	function charge_chance($reg_ip, $ssn, $CHK, $chance_type, $chance_type2='', $chance_cnt=1){
		if ($ssn==''){
			return '';
		}
		$chance_info = info_user_chance($ssn);

		// 찬스를 디비에서 가져와서 쓰자. 
//		$current_result= db_result("select current from tbl_chance_current where chance_type='$chance_type'");
//		if($current_result)$chance_cnt= $current_result;

		if ( $chance_type == 'xxxxxx' ){
		
		}else if ( $chance_type == 'login' ){
			if ( $chance_info[login_today] < 1 ){
				insert_user_chance2($reg_ip, $ssn, $CHK, $chance_type, $chance_type2, $chance_cnt);

				$chance_info = info_user_chance($ssn);

				return ( array('result'=>'o', 'chance_info'=>$chance_info) );
			}
			else {
				return ( array('result'=>'x', 'res'=>'login_chance_empty', 'chance_info'=>$chance_info) );
			}

		}else if ( $chance_type == 'gift' ){
			#if ( $chance_info[gift_today] <= 9 ) {
				insert_user_chance2($reg_ip, $ssn, $CHK, $chance_type, $chance_type2, $chance_cnt);

				$chance_info = info_user_chance($ssn);

				return ( array('result'=>'o', 'chance_info'=>$chance_info) );
			#}
			#else{
			#	return ( array('result'=>'x', 'res'=>'gift_today_already', 'chance_info'=>$chance_info) );
			#}

		}else if ( $chance_type == 'use' ){
			if ( $chance_info[chance_cnt] >= ($chance_cnt*-1) ){
				insert_user_chance2($reg_ip, $ssn, $CHK, $chance_type, $chance_type2, $chance_cnt);

				$chance_info = info_user_chance($ssn);

				return ( array('result'=>'o', 'chance_info'=>$chance_info) );
			}
			else {
				return ( array('result'=>'x', 'res'=>'use_chance_empty', 'chance_info'=>$chance_info) );
			}

		}else if ( $chance_type == 'sns' ){
			if ( $chance_info[sns_today] < 1000 ) {
				insert_user_chance2($reg_ip, $ssn, $CHK, $chance_type, $chance_type2, $chance_cnt);

				$chance_info = info_user_chance($ssn);

				return ( array('result'=>'o', 'chance_info'=>$chance_info) );
			}
			else {
				return ( array('result'=>'x', 'res'=>'sns_today_already', 'chance_info'=>$chance_info) );
			}
		
		}else if ( $chance_type == 'add' ){
			if ( $chance_info[chance_cnt] >= 0 ){
				insert_user_chance2($reg_ip, $ssn, $CHK, $chance_type, $chance_type2, $chance_cnt);

				$chance_info = info_user_chance($ssn);

				return ( array('result'=>'o', 'chance_info'=>$chance_info) );
			}
			else {
				return ( array('result'=>'x', 'res'=>'use_chance_empty', 'chance_info'=>$chance_info) );
			}

		}else if ( $chance_type == 'quiz' ){
			if ( $chance_info[quiz_today] < 1 ) {
				insert_user_chance2($reg_ip, $ssn, $CHK, $chance_type, $chance_type2, $chance_cnt);

				$chance_info = info_user_chance($ssn);

				return ( array('result'=>'o', 'chance_info'=>$chance_info) );
			}
			else{
				return ( array('result'=>'x', 'res'=>'quiz_today_already', 'chance_info'=>$chance_info) );
			}

		}else if ( $chance_type == 'regist' ){
			if ( $chance_info[regist_chance] < 1 ){
				insert_user_chance2($reg_ip, $ssn, $CHK, $chance_type, $chance_type2, $chance_cnt);

				$chance_info = info_user_chance($ssn);

				return ( array('result'=>'o', 'chance_info'=>$chance_info) );
			}
			else {
				return ( array('result'=>'x', 'res'=>'regist_chance_empty', 'chance_info'=>$chance_info) );
			}
		
		}else if ( $chance_type == 'detail' ){
			if ( $chance_info[detail_chance] < 1 ){
				insert_user_chance2($reg_ip, $ssn, $CHK, $chance_type, $chance_type2, $chance_cnt);

				$chance_info = info_user_chance($ssn, $CHK);

				return ( array('result'=>'o', 'chance_info'=>$chance_info) );
			}
			else {
				return ( array('result'=>'x', 'msg'=>'duplicate_detail', 'chance_info'=>$chance_info) );
			}
		
		}else if ( $chance_type == 'modify' ){
			if ( $chance_info[modify_chance] < 1 ){
				insert_user_chance2($reg_ip, $ssn, $CHK, $chance_type, $chance_type2, $chance_cnt);

				$chance_info = info_user_chance($ssn);

				return ( array('result'=>'o', 'chance_info'=>$chance_info) );
			}
			else {
				return ( array('result'=>'x', 'msg'=>'duplicate_modify', 'chance_info'=>$chance_info) );
			}
		
		}else if ( $chance_type == 'write_event' ){
			if ( $chance_info[writeevent_today] < 1000 ){
				insert_user_chance2($reg_ip, $ssn, $CHK, $chance_type, $chance_type2, $chance_cnt);

				$chance_info = info_user_chance($ssn);

				return ( array('result'=>'o', 'chance_info'=>$chance_info) );
			}
			else {
				return ( array('result'=>'x', 'res'=>'write_event_chance_empty', 'chance_info'=>$chance_info) );
			}
		
		}else if ( $chance_type == 'verify' ){
			if ( $chance_info[verify_today] < 1000 ){
				insert_user_chance2($reg_ip, $ssn, $CHK, $chance_type, $chance_type2, $chance_cnt);

				$chance_info = info_user_chance($ssn);

				return ( array('result'=>'o', 'chance_info'=>$chance_info) );
			}
			else {
				return ( array('result'=>'x', 'res'=>'verify_chance_empty', 'chance_info'=>$chance_info) );
			}
		
		}else if ( $chance_type == 'snsinto' ){
			if ( $chance_info[snsinto_today] < 100 ){
				insert_user_chance2($reg_ip, $ssn, $CHK, $chance_type, $chance_type2, $chance_cnt);

				$chance_info = info_user_chance($ssn);

				return ( array('result'=>'o', 'chance_info'=>$chance_info) );
			}
			else {
				return ( array('result'=>'x', 'res'=>'snsinto_chance_empty', 'chance_info'=>$chance_info) );
			}
		

		} else {
			return ( array('result'=>'x', 'res'=>'empty_chance_type') );
		}
	}
	function info_user_chance($ssn, $CHK=''){
		$sql = "
			SELECT 0 emtpy, m.ssn, m.reg_date , concat(left(uname,1),'*',right(uname,1)) uname, pno, m.profile_image, m.member_type
				, IFNULL(SUM(total_chance),0)total_chance
				, IFNULL(SUM(use_chance),0)use_chance
				, IFNULL(SUM(login_today),0)login_today
				, IFNULL(SUM(gift_today),0)gift_today
				, IFNULL(SUM(sns_today),0)sns_today
				, IFNULL(SUM(add_today),0)add_today
				, IFNULL(SUM(use_today),0)use_today
				, IFNULL(SUM(login_today) + SUM(gift_today) + SUM(sns_today) + SUM(add_today) + SUM(use_today) ,0)chance_today_cnt
				, IFNULL(SUM(total_chance) + SUM(use_chance) ,0)chance_cnt
			FROM (
				SELECT b.ssn
				, IFNULL(SUM(CASE WHEN chance_type in('login','sns','add') THEN current END),0)total_chance
				, IFNULL(SUM(CASE WHEN chance_type='use' THEN current END),0) use_chance
				, IFNULL(SUM(CASE WHEN a.reg_dates=b.reg_dates AND chance_type='login' THEN current END),0)login_today
				, IFNULL(SUM(CASE WHEN a.reg_dates=b.reg_dates AND chance_type='gift' THEN current END),0)gift_today
				, IFNULL(SUM(CASE WHEN a.reg_dates=b.reg_dates AND chance_type='sns' THEN current END),0)sns_today
				, IFNULL(SUM(CASE WHEN a.reg_dates=b.reg_dates AND chance_type='add' THEN current END),0)add_today
				, IFNULL(SUM(CASE WHEN a.reg_dates=b.reg_dates AND chance_type='use' THEN current END),0)use_today
				FROM tbl_chance a RIGHT JOIN (SELECT LEFT(NOW(),10)reg_dates, '$ssn' ssn) b ON a.ssn=b.ssn
			) a, tbl_member m
			WHERE a.ssn=m.ssn
		";
		$rs = db_select_assoc($sql);
		return $rs;
	}
	function insert_user_chance2($reg_ip, $ssn, $chk, $chance_type, $chance_type2, $chance_cnt=0){
		$sql = "INSERT INTO tbl_chance
				(reg_ip, reg_date, reg_dates, ssn, ssn_p, chk, chance_type, chance_type2, current)
			VALUES
				('$reg_ip', now(), left(now(),10), '$ssn', right('$ssn',1), '$chk', '$chance_type', '$chance_type2', '$chance_cnt')
			ON DUPLICATE KEY
				UPDATE update_date = now(), current_over = current_over + $chance_cnt
			";
		db_query($sql);
	}
	function get_files($idx){
		$sql="
			select 
				filename
			from tbl_content_file
			where bidx = {$idx}
			union
			select '/images/popup_thumb1.jpg' filename
			limit 1
			";
		$fileRs =db_query($sql);
		while($fileRow =db_fetch($fileRs)){
			$fileRow[image_path] = "/data/content/{$fileRow[filename]}";
			$files[] = $fileRow;
		}
		return $files;
	}

	function form_submit($field, $action, $method){
		foreach($field as $k => $v){
			$inputs .= "<input type='hidden' name='$k' value='$v'/>";
		}
		echo "<form name='form1' method='$method' action='$action'>$inputs</form>";
		echo "<script>form1.submit();</script>";
	}

	function unset_cookie($cookie_name) {
		if (isset($_COOKIE[$cookie_name])) {
			unset($_COOKIE[$cookie_name]);
			setcookie($cookie_name, null, -1, '/');
		} else { return false; }
	}

	function getGnbActive($bool){
		if( $bool ){
			return "active";
		} else {
		}
	}

	// 한페이지에 보여줄 행, 현재페이지, 총페이지수, URL
	function get_paging_jjansoon($write_pages, $cur_page, $total_page, $url, $add="")
	{
		//$url = preg_replace('#&amp;page=[0-9]*(&amp;page=)$#', '$1', $url);
		$url = preg_replace('#&amp;page=[0-9]*#', '', $url) . '&amp;page=';

		$str = '';
		if ($cur_page > 1) {
			#$str .= '<a href="'.$url.'1'.$add.'" class="pg_page pg_start">처음</a>'.PHP_EOL;
			$str .= '<em><a href="'.$url.'1'.$add.'"><img src="/images/paging_prev.png" alt="이전"></a></em>'.PHP_EOL;
		}

		$start_page = ( ( (int)( ($cur_page - 1 ) / $write_pages ) ) * $write_pages ) + 1;
		$end_page = $start_page + $write_pages - 1;

		if ($end_page >= $total_page) $end_page = $total_page;

		if ($start_page > 1) $str .= '<a href="'.$url.($start_page-1).$add.'" class="pg_page pg_prev">이전</a>'.PHP_EOL;

		if ($total_page > 1) {
			for ($k=$start_page;$k<=$end_page;$k++) {
				if ($cur_page != $k){
					#$str .= '<a href="'.$url.$k.$add.'" class="pg_page">'.$k.'<span class="sound_only">페이지</span></a>'.PHP_EOL;
					$str .= '<em class=""><a href="'.$url.$k.$add.'">'.$k.'</a></em>'.PHP_EOL;
				}else
					$str .= '<span class="sound_only">열린</span><strong class="pg_current">'.$k.'</strong><span class="sound_only">페이지</span>'.PHP_EOL;
			}
		}

		if ($total_page > $end_page){
			#$str .= '<a href="'.$url.($end_page+1).$add.'" class="pg_page pg_next">다음</a>'.PHP_EOL;
			$str .= '<em class=""><a href="'.$url.($end_page+1).$add.'"><img src="/images/paging_next.png" alt="다음"></a></em>'.PHP_EOL;
		}

		if ($cur_page < $total_page) {
			$str .= '<a href="'.$url.$total_page.$add.'" class="pg_page pg_end">맨끝</a>'.PHP_EOL;
		}

		if ($str)
			return "<nav class=\"pg_wrap\"><span class=\"pg\">{$str}</span></nav>";
		else
			return "";
	}

	function httpPost($url,$params){
		$postData = '';
		foreach($params as $k => $v) 
		{ 
			$postData .= $k . '='.$v.'&'; 
		}
		$postData = rtrim($postData, '&');

		$ch = curl_init();  

		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch, CURLOPT_HEADER, false); 
		curl_setopt($ch, CURLOPT_POST, count($postData));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);    

		$output=curl_exec($ch);

		curl_close($ch);
		return $output;
	}

	function crypto_rand_secure($min, $max)
	{
		$range = $max - $min;
		if ($range < 1) return $min; // not so random...
		$log = ceil(log($range, 2));
		$bytes = (int) ($log / 8) + 1; // length in bytes
		$bits = (int) $log + 1; // length in bits
		$filter = (int) (1 << $bits) - 1; // set all lower bits to 1
		do {
			$rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
			$rnd = $rnd & $filter; // discard irrelevant bits
		} while ($rnd > $range);
		return $min + $rnd;
	}

	function getToken2($length)
	{
		$token = "";
		$codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$codeAlphabet.= "0123456789";
		$max = strlen($codeAlphabet); // edited

		for ($i=0; $i < $length; $i++) {
			$token .= $codeAlphabet[crypto_rand_secure(0, $max-1)];
		}

		return $token;
	}
	function getTokenA($length = 60) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	function getToken($length = 60) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length-1; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString. rand(0,9);
	}

	function getUserIp(){
		$result = '';
		if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
			$result = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$result = $_SERVER['REMOTE_ADDR'];
		}
		#$result = explode(",", $result);
		#return $result[0];
		return $result;
	}

	function getUserIpIsAttac(){
		$result = '';
		if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
			$result = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$result = $_SERVER['REMOTE_ADDR'];
		}
		$result = explode(",", $result);
		return $result[1]?"true":"false";
	}

	function gArrFetch($st){
		$a=0;
		if(is_string($st)) $st = mysql_query($st);
		if($st)
		while($rs = mysql_fetch_array($st)){
			$rowList[($a++)] = $rs;
		}
		return $rowList;
	}

	function gAt($list, $at, $char1=","){
		if($at>=0){
			$tmp1 = explode($char1, $list);
			if(count($tmp1)<$at+1)
				return "";
			else
				return $tmp1[$at];
		}else{
			return "";
		}
	}
	function gAtCnt($l, $c=","){
		$t1 = explode($c, $l);
		return count($t1)-1;
	}
	function gAtFind($l, $v, $ag=','){
		$rt = -1;
		$split1=explode(',', $l);
		for($a=0; $a<count($split1); $a++){
			if($split1[$a]==$v) $rt=$a;
		}
		return $rt;
	}
	function gAtData($data, $list, $v, $dfval='', $c=","){
		$rt = gAt($data, gAtFind($list, $v, $c), $c);
		$rt = ($rt!=''?$rt:$dfval);
		return $rt;
	}
	function script($str, $msg=''){
		if($msg)echo "<script>alert('$msg');</script>";
		echo "<script type='text/javascript'>$str</script>";
	}
	function gCodeList($codes){
		return gArrFetch("select * from tbl_common where pcodes = ". $codes ." order by step ");
	}
/* ┏━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┓
	┃ 데이터베이스 관련 함수																														   ┃
	┃ 업데이트:2012.01.10																																   ┃
	┗━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┛*/


	//DB 접속
	function db_connect($db_host, $db_user, $db_pass, $db_name){
		$result = @mysql_connect($db_host, $db_user, $db_pass) or die(mysql_error());
		mysql_select_db($db_name) or die(mysql_error());
		mysql_query("set names utf8");
		return $result;
	}

	//SQL 쿼리 실행 함수
	function db_query($sql){

		$rs = mysql_query($sql);
		if(mysql_error()){
			#echo "DB ERROR <textarea>$sql</textarea>";exit;
			#echo ROOT ."/../logs/db_log.log";
			//error_log($sql ."\n", 3, ROOT ."/../logs/db_log.log");
			debug('db_query()', $sql);
			if($_SERVER['REMOTE_ADDR']==$MASTER_IP) echo $sql;
		}
		return $rs;
	}
	function db_select_assoc($sql,$id=-1){
		$rs = db_query($sql);
		if($id>-1)
		{
			$rs=@db_fetch($rs,'assoc');
			return $rs[$id];
		}
		else
		{
			return @db_fetch($rs,'assoc');
		}
	}
	function db_select_list($sql){
		$list=array();
		$rs = db_query($sql);
		while($pList=db_fetch_assoc($rs)){
			$list[] = $pList;
		}
		return $list;
	}
	//데이터를 배열로 가져오기
	function db_fetch_assoc($rs){
		return @mysql_fetch_assoc($rs);
	}

	//데이터를 배열로 가져오기
	function db_select($sql,$id=-1){
		$rs = db_query($sql);
		if($id>-1)
		{
			$rs=@db_fetch($rs);
			return $rs[$id];
		}
		else
		{
			return @db_fetch($rs);
		}
	}

	//데이터를 배열로 가져오기
	function db_fetch($rs, $type='array'){
		if ($type=='array'){
			return @mysql_fetch_array($rs);
		}else{
			return @mysql_fetch_assoc($rs);
		}
	}

	function db_result($sql){
		$rs = db_query($sql);
		if($rs) return @mysql_result($rs,0,0);
	}

	//데이터 카운트 리턴 함수
	function db_count($table, $where="", $field="*"){
		$rs= db_select("select count(".$field.") from ".$table.($where? " where ".$where : ""));
		return $rs[0];
	}

	//데이터 자동 인서트
	function db_insert($table, $data){
		if(!$table or !is_array($data)) return false;
		$columns = implode(', ',array_keys($data));
		$values = implode("', '",array_values($data));
		db_query("insert into $table ($columns) values ('$values')");
	}

	//데이터 자동 업데이트
	function db_update($table, $data, $where=""){
		if(!$table or !is_array($data)) return false;
		foreach($data as $key=>$val){
			$str[] = $key."='".$val."'";
		}
		if($where) $where=" where $where";
		db_query("update $table set ".implode(", ",$str)." $where");
	}

	//패스워드 암호화
	function db_password($val){
		return db_result("select password('$val')");
	}


/* ┏━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┓
	┃ 개발자 정의 함수																																	   ┃
	┃ 업데이트:2012.01.10																																   ┃
	┗━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┛*/

	//인젝션 공격 방지
	function  anti_injection($string){
		if ($string){
			$str =  trim($string);
			$str =  str_replace("'","&#039;",$str);
			$str =  str_replace("<xmp","<x-xmp",$str);
			$str =  str_replace("javascript","x-javascript",$str);
			$str =  str_replace("script","x-script",$str);
			$str =  str_replace("iframe","x-iframe",$str);
			$str =  str_replace("document","x-document",$str);
			$str =  str_replace("vbscript","x-vbscript",$str);
			$str =  str_replace("applet","x-applet",$str);
			$str =  str_replace("embed","x-embed",$str);
			$str =  str_replace("object","x-object",$str);
			$str =  str_replace("frame","x-frame",$str);
			$str =  str_replace("frameset","x-frameset",$str);
			$str =  str_replace("layer","x-layer",$str);
			$str =  str_replace("bgsound","x-bgsound",$str);
			$str =  str_replace("alert","x-alert",$str);

			$str =  str_replace("onblur","x-onblur",$str);
			$str =  str_replace("onchange","x-onchange",$str);
			$str =  str_replace("onclick","x-onclick",$str);
			$str =  str_replace("ondblclick","x-ondblclick",$str);
			$str =  str_replace("onerror","x-onerror",$str);
			$str =  str_replace("onfocus","x-onfocus",$str);
			$str =  str_replace("onload","x-onload",$str);
			$str =  str_replace("onmouse","x-onmouse",$str);
			$str =  str_replace("onscroll","x-onscrol",$str);
			$str =  str_replace("onsubmit'","x-onsubmit",$str);
			$str =  str_replace("onunload","x-onunload",$str);

			return $str;

		}else{
			return $string;
		}
	}

	//페이지 이동 스크립트
	function move_page($url, $second="0"){
		echo"<meta http-equiv=\"refresh\" content=\"$second; url=$url\">";
		exit;
	}

	//메세지 팝업 후 이동
	function msg($msg){
		echo "<script type='text/javascript'>alert('$msg');</script>";
		exit;
	}

	//메세지 팝업 후 이동
	function msg_page($msg, $url=""){
		if($url==""){
			$url="history.go(-1)";
		}else if($url=="close"){
			$url="self.close()";
		}else{
			$url="document.location.href='$url'";
		}

		if( $msg!='' ){
			$msg = "alert(\"$msg\");";
		}

		echo "<script type='text/javascript'>$msg $url;</script>";
		exit;
	}

	//페이지 닫고 본창 이동
	function close_page($url){
		echo "<script type='text/javascript'>opener.location.href='$url';windlow.close();</script>";
	}

	//파일 업로드 (tmp 파일, 파일명, 저장할 디렉토리, 파일명 변경 여부->2:변경)
	function file_upload($file, $file_name, $path, $change="2"){

		$file_check = explode(".", $file_name);
		$ext = strtolower($file_check[count($file_check)-1]);					// 파일 확장자 구하기

		$org_name = str_replace(".".$ext, "", $file_name);
		$org_name = str_replace(" ", "_", $org_name);

		$file_list = array ('html','htm','php','phtml','php3','php4','php5','asp','jsp', 'exe', 'js','cgi','inc','pl'); // 금지 파일 항목

		// 금지 파일인지 아닌지 확인 시작
		if(in_array($ext, $file_list)){
			echo "<script>alert('등록 가능한 파일이 아닙니다.');history.go(-1);</script>";
			exit;
		}

		if(!is_dir($path)){
			mkdir($path, 0777);
			chmod("$path", 0777);
		}

		$tmp_filename = ($change==1 ? $org_name."_".rand().".".$ext : time()."_".rand().".".$ext);

		$i = 1;
		while(file_exists($path."/".$tmp_filename)){
			$tmp_filename = ($change==1 ? $org_name."_".$i.".".$ext : time().".".$ext);
			$i++;
		}


		if(!move_uploaded_file($file, "$path/$tmp_filename")){
			echo "파일 업로드 Error!! 시스템 관리자에게 문의해주세요";
			exit;
		}

		//		if(file_exists($file)) {
		//			$data = file_get_contents($file);
		//			$fp = fopen("$path/$tmp_filename", 'w+');
		//			if(is_resource($fp)){
		//				fputs($fp, $data);
		//			}
		//			fclose($fp);
		//		}
		return $tmp_filename;
	}
	//파일 업로드 (tmp 파일, 파일명, 저장할 디렉토리, 파일명 변경 여부->2:변경)
	function file_upload2($file, $path, $change="2"){

		$file_name = $_FILES[$file][name];

		$file_check = explode(".", $file_name);
		$ext = strtolower($file_check[count($file_check)-1]);					// 파일 확장자 구하기

		$org_name = str_replace(".".$ext, "", $file_name);
		$org_name = str_replace(" ", "_", $org_name);

		$file_list = array ('html','htm','php','phtml','php3','php4','php5','asp','jsp', 'exe', 'js','cgi','inc','pl'); // 금지 파일 항목

		// 금지 파일인지 아닌지 확인 시작
		if(in_array($ext, $file_list)){
			#echo "<script>alert('등록 가능한 파일이 아닙니다.');history.go(-1);</script>";
			return( array('result'=>'not_able_file') );
			exit;
		}

		if(!is_dir($path)){
			mkdir($path, 0777);
			chmod("$path", 0777);
		}

		$tmp_filename = ($change==1 ? $org_name.".".$ext : time().".".$ext);

		$i = 1;
		while(file_exists($path."/".$tmp_filename)){
			$tmp_filename = ($change==1 ? $org_name."_".$i.".".$ext : time().".".$ext);
			$i++;
		}

		if(!move_uploaded_file($_FILES[$file][tmp_name], "$path/$tmp_filename")){
			#echo "파일 업로드 Error!! 시스템 관리자에게 문의해주세요";
			return( array('result'=>'not_able_file2') );
			exit;
		}
		return( array('result'=>'o', 'orgname'=>$file_name, 'filename'=>$tmp_filename) );
	}

	//파일 삭제
	function file_delete($file_name, $path){
		if(is_file($path."/".$file_name)){
			@unlink($path."/".$file_name);
		}
	}

	//배열값 확인하기
	function print_array($arr){
		echo "<pre>"; print_r($arr); echo "</pre>";
		exit;
	}


	// 페이징 처리 함수
	function page_list($page="1", $count, $list_num="15", $page_num="10", $url="", $first_page="", $post_start="", $next_start="", $last_page="", $link_color="#6EB3DB") {
		global $page_first_page;
		global $page_post_start;
		global $page_next_start;
		global $page_last_page;

		$link=$_SERVER['PHP_SELF'];
		if(!$first_page) $first_page= $page_first_page;
		if(!$post_start) $post_start=$page_post_start;
		if(!$next_start) $next_start=$page_next_start;
		if(!$last_page) $last_page=$page_last_page;

		// 1. 전체 페이지 계산
		$total_page  = ceil($count / $list_num);

		$start_page = @(((int)(($page-1)/$page_num))*$page_num)+1;
		$temp_pnum = $page_num - 1 ;
		$end_page = $start_page + $temp_pnum;

		if ($end_page >= $total_page) $end_page = $total_page;
		if ($page > 1) {
			$link_str .= " <em><a href='".$link."?".$url.($url?"&":"")."page=1' class='arrow'>".$first_page."</a></em> ";
		}

		if ($start_page > 1) {
			$link_str .= " <em><a href='".$link."?".$url.($url?"&":"")."page=".($start_page-1)."' class='arrow'>".$post_start."</a></em> ";
		}

		if ($total_page > 1) {
			for ($i=$start_page;$i<=$end_page;$i++) {
				if ($page != $i) {
					$link_str .= " <em><a href='".$link."?".$url.($url?"&":"")."page=".$i."' accesskey='$i'>$i</a></em> ";
				} else {
					$link_str .= " <em><a class='active' style='color:red;'>$i</a></em> ";
				}
			}
		}else{
			$link_str .= " <em><a class='active'>1</a></em> ";
		}

		if ($total_page > $end_page) {
			$link_str .= " <em><a href='".$link."?".$url.($url?"&":"")."page=".($end_page+1)."' class='arrow' accesskey='".($end_page+1)."'>".$next_start."</a></em> ";
		}

		if ($page < $total_page) $link_str .= " <em><a href='".$link."?".$url.($url?"&":"")."page=".$total_page."' class='arrow'>".$last_page."</a></em> ";

		if($count>0) echo $link_str;
	}
	function page_list1($page="1", $count, $list_num="15", $page_num="10", $url="", $first_page="", $post_start="", $next_start="", $last_page="", $link_color="#6EB3DB") {
		global $page_first_page;
		global $page_post_start;
		global $page_next_start;
		global $page_last_page;

		$link=$_SERVER['PHP_SELF'];
		if(!$first_page) $first_page= $page_first_page;
		if(!$post_start) $post_start=$page_post_start;
		if(!$next_start) $next_start=$page_next_start;
		if(!$last_page) $last_page=$page_last_page;

		// 1. 전체 페이지 계산
		$total_page  = ceil($count / $list_num);

		$start_page = @(((int)(($page-1)/$page_num))*$page_num)+1;
		$temp_pnum = $page_num - 1 ;
		$end_page = $start_page + $temp_pnum;

		if ($end_page >= $total_page) $end_page = $total_page;
		if ($page > 1) {
		#	$link_str .= " <em><a href='".$link."?".$url.($url?"&":"")."page=1' class='arrow'>".$first_page."</a></em> ";
		}

		if ($start_page > 1) {
			$link_str .= " <em><a href='".$link."?".$url.($url?"&":"")."page=".($start_page-1)."' class='arrow'>".$post_start."</a></em> ";
		}

		if ($total_page > 1) {
			for ($i=$start_page;$i<=$end_page;$i++) {
				if ($page != $i) {
					$link_str .= " <em><a href='".$link."?".$url.($url?"&":"")."page=".$i."'>$i</a></em> ";
				} else {
					$link_str .= " <em class='active'><a>$i</a></em> ";
				}
			}
		}else{
			$link_str .= " <em class='active'><a>1</a></em> ";
		}

		if ($total_page > $end_page) {
			$link_str .= " <em><a href='".$link."?".$url.($url?"&":"")."page=".($end_page+1)."' class='arrow'>".$next_start."</a></em> ";
		}

		if ($page < $total_page){
		#	$link_str .= " <em><a href='".$link."?".$url.($url?"&":"")."page=".$total_page."' class='arrow'>".$last_page."</a></em> ";
		}

		if($count>0) echo $link_str;
	}
	function page_listJjansun($page="1", $count, $list_num="15", $page_num="10", $url="", $first_page="", $post_start="", $next_start="", $last_page="", $link_color="#6EB3DB") {
		global $page_first_page;
		global $page_post_start;
		global $page_next_start;
		global $page_last_page;

		$link=$_SERVER['PHP_SELF'];
		if(!$first_page) $first_page= $page_first_page;
		if(!$post_start) $post_start=$page_post_start;
		if(!$next_start) $next_start=$page_next_start;
		if(!$last_page) $last_page=$page_last_page;

		// 1. 전체 페이지 계산
		$total_page  = ceil($count / $list_num);

		$start_page = @(((int)(($page-1)/$page_num))*$page_num)+1;
		$temp_pnum = $page_num - 1 ;
		$end_page = $start_page + $temp_pnum;

		if ($end_page >= $total_page) $end_page = $total_page;
		if ($page > 1) {
		#	$link_str .= " <em><a href='".$link."?".$url.($url?"&":"")."page=1' class='arrow'>".$first_page."</a></em> ";
		}

		if ($start_page > 1) {
			$link_str[first]= " <li><a href='javascript: ".str_replace('{page}',($start_page-1),$url)."' class='arrow'><<</a></li> ";
		}

		$i=$start_page;
		if ($total_page > 1) {
			for ($i=$start_page;$i<=$end_page;$i++) {
				if ($page != $i) {
					$pages .= " <li><a href='javascript:".str_replace('{page}',$i,$url).";'>$i</a></li> ";
				} else {
					$pages .= " <li><a href='javascript:;' class='current'>$i</a></li> ";
				}
			}
		}else{
			$pages .= " <li><a href='javascript:;' class='current'>$i</a></li> ";
		}
		$link_str[pages]= $pages;

		if ($total_page > $end_page) {
			$link_str[last]= " <li><a href='javascript: ".str_replace('{page}',($end_page+1),$url)."' class='arrow'>>></a></li> ";
		}

		if ($page < $total_page){
		#	$link_str .= " <em><a href='".$link."?".$url.($url?"&":"")."page=".$total_page."' class='arrow'>".$last_page."</a></em> ";
		}

		return $link_str;
	}
	function page_list_topevent($page="1", $count, $list_num="15", $page_num="10", $url="", $first_page="", $post_start="", $next_start="", $last_page="", $link_color="#6EB3DB") {
		global $page_first_page;
		global $page_post_start;
		global $page_next_start;
		global $page_last_page;

		$link=$_SERVER['PHP_SELF'];
		if(!$first_page) $first_page= $page_first_page;
		if(!$post_start) $post_start=$page_post_start;
		if(!$next_start) $next_start=$page_next_start;
		if(!$last_page) $last_page=$page_last_page;

		// 1. 전체 페이지 계산
		//		시작 페이지와 현재 페이지가 같으면 없앰
		//		마지막 페이지 현재페이지가 작으면 나오게
		$total_page  = ceil($count / $list_num);

		$start_page = @(((int)(($page-1)/$page_num))*$page_num)+1;
		$temp_pnum = $page_num - 1 ;
		$end_page = $start_page + $temp_pnum;

		$link_str[page] = $page;
		$link_str[total_page] = $total_page;
		if ($end_page >= $total_page) $end_page = $total_page;
		if ($page > 1) {
			$link_str[first] = str_replace('{page}',$page-1,$url); //"BoardList(".($page-1).")";
		} else {
			$link_str[first] = '';
		}

		if ($page < $total_page){
			$link_str[last] = str_replace('{page}',$page+1,$url); //"BoardList(".($page+1).")";
		} else {
			$link_str[last] = '';
		}

		return $link_str;
	}



	//값이 없을 시 기본값 설정
	function default_set($val, $default){
		if($val){
			return $val;
		}else{
			return $default;
		}
	}

	# 사용법 : hangul("자를 문자열", 원하는 문자열 길이);
	function is_han($text) {
		$text = ord($text);
		if($text >= 0xa1 && $text <= 0xfe)
		return 1;
	}
	function is_alpha($char) {
		$char = ord($char);
		if($char >= 0x61 && $char <= 0x7a)		return 1;
		if($char >= 0x41 && $char <= 0x5a)		return 2;
	}

	# 함수명 : 문자열 자르기 함수 3개 함수로 구성
	function cutstr($str, $len, $checkmb=true, $tail='...') {

		//$checkmb : 이 값을 true로 하면 한글을 영문2자와 같이 취급한다. false 는 한글 한글자는 한글자.
		preg_match_all('/[\xEA-\xED][\x80-\xFF]{2}|./', $str, $match);
		$m    = $match[0];
		$slen = strlen($str);  // length of source string
		$tlen = strlen($tail); // length of tail string
		$mlen = count($m);    // length of matched characters

		if ($slen <= $len) return $str;
		if (!$checkmb && $mlen <= $len) return $str;

		$ret  = array();
		$count = 0;

		for ($i=0; $i < $len; $i++) {
			$count += ($checkmb && strlen($m[$i]) > 1)?2:1;
			if ($count + $tlen > $len) break;
			$ret[] = $m[$i];
		}
		return join('', $ret).$tail;
	}

	//첨부파일 다운로드 (파일경로, 파일명, 다운시표시파일명)
	function file_down($Path,$File,$Org=""){

		$Org=($Org ? $Org : $File);
		$DownFile =$Path."/".$File;
		$Org=iconv("UTF-8","EUC-KR",$Org);

		Header("Cache-Control: cache, must-revalidate, post-check=0, pre-check=0");
		Header("Content-type: application/x-msdownload");
		Header("Content-Length: ".(string)(filesize($DownFile)));
		Header("Content-Disposition: attachment; filename=".$Org."");
		Header("Content-Description: PHP5 Generated Data");
		Header("Content-Transfer-incoding: euc_kr");
		Header("Content-Transfer-Encoding: binary");
		Header("Pragma: no-cache");
		Header("Expires: 0");
		Header("Content-Description: File Transfer");

		if (is_file($DownFile)) {
			$fp = fopen($DownFile, "rb");

			if (!fpassthru($fp)) fclose($fp);
			clearstatcache();
		} else {
			ErrorMessage("해당파일이나 경로가 존재하지 않습니다.");
			exit();
		}
	}

	//파일 사이즈를 GB,MB,KB 에 맞추어서 변환해서 리턴
	function get_filesize($size) {
		if(!$size) return "0 Byte";

		if($size >= 1073741824) {
			$size = sprintf("%0.3fGB",$size / 1073741824);
		} elseif($size >= 1048576) {
			$size = sprintf("%0.2fMB",$size / 1048576);
		} elseif($size >= 1024)  {
			$size = sprintf("%0.1fKB",$size / 1024);
		} else {
			$size = $size."Byte";
		}

		return $size;
	}

	//메일발송
	function sendMail($subject,$body,$fromEmail, $toEmail ){

		global $success ;

		$subject = stripslashes($subject);
		$body = stripslashes($body);

		//스크립트 종료할때까지
		set_time_limit(0);

		//메일의 헤더와 내용
		$additional_headers="from:".$fromEmail."\n";
		$additional_headers.="reply-to : " . $fromEmail . "\n";
		$additional_headers.="content-type:text/html;";

		if(mail($toEmail, encode_2047($subject), $body, $additional_headers)) $success ++;
	}

	//제목에 2047인코딩하기
	function encode_2047($subject) {
			return '=?utf-8?b?'.base64_encode($subject).'?=';
	}

	//썸네일로 만드는 함수
	function thumbnail($file, $save_filename, $max_width=100, $max_height=100, $sizeChg=1,$Orientation=1){

		$img_info=@getimagesize($file);//이미지 사이즈를 확인합니다.

		//이미지 타입을 이용해 변수를 재지정해줍니다.
		//------------------------------------------------------
		// Imagetype Constants
		//------------------------------------------------------
		// 1 IMAGETYPE_GIF
		// 2 IMAGETYPE_JPEG
		// 3 IMAGETYPE_PNG
		// 4 IMAGETYPE_SWF
		// 5 IMAGETYPE_PSD
		// 6 IMAGETYPE_BMP
		// 7 IMAGETYPE_TIFF_II (intel byte order)
		// 8 IMAGETYPE_TIFF_MM (motorola byte order)
		// 9 IMAGETYPE_JPC
		// 10 IMAGETYPE_JP2
		// 11 IMAGETYPE_JPX
		// 12 IMAGETYPE_JB2
		// 13 IMAGETYPE_SWC
		// 14 IMAGETYPE_IFF
		// 15 IMAGETYPE_WBMP
		// 16 IMAGETYPE_XBM
		//------------------------------------------------------


		if($img_info[2]==1)		{	$src_img=ImageCreateFromGIF($file);	}
		elseif($img_info[2]==2)	{	$src_img=ImageCreateFromJPEG($file);	}
		elseif($img_info[2]==3)	{	$src_img=ImageCreateFromPNG($file);	}
		elseif($img_info[2]==4)	{	$src_img=ImageCreateFromWBMP($file);	}
		else return false;

		#$exif = exif_read_data($src_img);
		#debug(__LINE__, $exif);

		#$img_info = getImageSize($src_img);//원본이미지의 정보를 얻어옵니다
		$img_width = $img_info[0];
		$img_height = $img_info[1];

		switch($Orientation){
			case 3:				$src_img = imagerotate($src_img,180,0);		$img_width=$img_info[0];	$img_height=$img_info[1];	break;
			case 6:				$src_img = imagerotate($src_img,-90,0);		$img_width=$img_info[1];	$img_height=$img_info[0];	break;
			case 8:				$src_img = imagerotate($src_img,90,0);		$img_width=$img_info[1];	$img_height=$img_info[0];	break;
		}

		$crt_width=$max_width;  //생성되면 이미지 사이즈
		$crt_height=$max_height;

		//1.가로 세로 원본비율을 맞추고, 남은 영역에 색채워서 정해진 크기로 생성
		if($sizeChg==1){
			if(($img_width/$max_width) == ($img_height/$max_height)){ //원본과 썸네일의 가로세로비율이 같은경우
				$dst_x = 0;
				$dst_y = 0;
				$dst_width=$max_width;
				$dst_height=$max_height;
			}
			elseif(($img_width/$max_width) < ($img_height/$max_height)){ //세로에 기준을 둔경우
				$dst_x= ($max_width - $img_width*($max_height/$img_height) ) / 2;
				$dst_y = 0;

				$dst_width=$max_height*($img_width/$img_height);
				$dst_height=$max_height;
			}
			else{ //가로에 기준을 둔경우
				$dst_x= 0;
				$dst_y = ($max_height - $img_height*($max_width/$img_width) ) / 2;

				$dst_width=$max_width;
				$dst_height=$max_width*($img_height/$img_width);
			}
			$dst_x= 0;
			$dst_y = 0;

			$dst_width=$max_width;
			$dst_height=$max_width*($img_height/$img_width);

		//2.가로 세로 원본비율을 맞추고, 남은 영역없이 이미지만 컷 생성
		}else if($sizeChg==2){

			if(($img_width/$max_width) == ($img_height/$max_height)){ //원본과 썸네일의 가로세로비율이 같은경우
				$dst_width=$max_width;
				$dst_height=$max_height;
			}
			elseif(($img_width/$max_width) < ($img_height/$max_height)){ //세로에 기준을 둔경우
				$dst_width=$max_height*($img_width/$img_height);
				$dst_height=$max_height;
			}
			else{//가로에 기준을 둔경우
				$dst_width=$max_width;
				$dst_height=$max_width*($img_height/$img_width);
			}

			$dst_x= 0;
			$dst_y = 0;

			$crt_width=$dst_width;
			$crt_height=$dst_height;


		//3.가로 세로 원본비율을 맞추지 않고, 정해진 크기대로 생성
		}else if($sizeChg==3){

			$dst_width=$max_width;
			$dst_height=$max_height;

			$dst_x= 0;
			$dst_y = 0;

		//4.가로 세로 최대 크기만 지정하고 그 안에서 원본비율대로 생성 (가로기준)
		}else if($sizeChg==4){

			$dst_x = 0;
			$dst_y = 0;

			if($img_width>$max_width){
				$dst_x = 0;
				$dst_y = 0;
				$dst_width=$max_width;
				$dst_height=$img_height*$max_width/$img_width;
			}else{
				$dst_x = 0;
				$dst_y = 0;
				$dst_width=$img_width;
				$dst_height=$img_height;
			}

			$crt_width=$dst_width;
			$crt_height=$dst_height;
		}

		$dst_img = imagecreatetruecolor($crt_width, $crt_height); //타겟이미지를 생성합니다

		$white = imagecolorallocate($dst_img,255,255,255);
		imagefill($dst_img, 0, 0, $white);

		ImageCopyResized($dst_img, $src_img, $dst_x, $dst_y, 0, 0, $dst_width, $dst_height, $img_width, $img_height); //타겟이미지에 원하는 사이즈의 이미지를 저장합니다
		ImageInterlace($dst_img);

		switch ($img_info[2]){
			case "1" : ImageGIF($dst_img,  $save_filename); break;
			case "2" : ImageJPEG($dst_img,  $save_filename); break;
			case "3" :
				imagealphablending($dst_img, false);
				imagecopyresampled($dst_img, $src_img, $dst_x, $dst_y, 0, 0, $dst_width, $dst_height, $img_width, $img_height); //(생성이미지,원소스이미지,시작점X,시작점Y,원본소스상 시작점X,원본소스상 시작점Y,생성이미지너비, 생성이미지높이,원이미지너비,원이미지높이)
				imagesavealpha($dst_img, true);
				ImagePNG($dst_img,  $save_filename,0);
				break;

			case "4" : ImageWBMP($dst_img,  $save_filename); break;
		}

		ImageDestroy($dst_img);
		ImageDestroy($src_img);
	}

	//SNS URL 줄이기
	function getShortURL($url) {

		//bit.ly API 정보
		$bitlyLogin = "o_76fpgc1vst";
		$bitlyApikey = "R_14140bd21aa2f1b8a057dd4f70c3eb8b";
		$bitlyLogin = "o_6pe8a6ftjs";
		$bitlyApikey = "8d5437768ea504d8f6693d2d9cc72c27b2aa91b0";

		$url = 'http://api.bitly.com/v3/shorten?login='.$bitlyLogin.'&apiKey='.$bitlyApikey.'&longUrl='.urlencode($url).'&format=txt';

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

		$APICall = curl_exec($ch);

		curl_close($ch);

		return $APICall;
	}

	//회원 로그인 여부 체크
	function loginAuth(){
		if(!$_SESSION['LOGIN_ID']){
			msg_page("회원만 이용 가능합니다. 로그인 후 이용하세요.","../member/login.php");
		}
	}

	//회원 로그인 여부 체크
	function loginReAuth(){
		if(!$_SESSION['LOGIN_ID']){
			msg_page("회원만 이용 가능합니다. 로그인 후 이용하세요.","../member/login.php?reurl=".urlencode("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
		}
	}

	//회원 로그인 여부 체크
	function loginAuthMsg(){
		if(!$_SESSION['LOGIN_ID']){
			msg("회원만 이용 가능합니다. 로그인 후 이용하세요.");
		}
	}

	//회원정보
	function infoMem($userno,$column=""){
		if($column==""){
			$meminfo=db_select("select * from tbl_member where idx='".$userno."'");
			return $meminfo;
		}else{
			$meminfo=@db_result("select $column from tbl_member where idx='".$userno."'");
			return $meminfo;
		}
	}

	//셀렉트 박스
	function selectArray($name,$arr,$val,$class="",$title=""){ //폼요소명, 배열명, 현재선택값

		global ${$arr};

		echo "<select name=\"".$name."\" id=\"".$name."\" class=\"select $class\" title=\"$title\">\n";
		echo "<option value=''>::선택::</option>\n";
		foreach(${$arr} as $xkey=>$xval){
			$sel=($val==$xkey ? "selected" : "");
			echo "<option value=\"".$xkey."\" $sel>".$xval."</option>\n";
		}
		reset(${$arr});
		echo "</select>";
	}
?>