<?
	//이메일 광고를 통한 접속
	if($_GET['ctype']) $_SESSION['CON_TYPE']=$_GET['ctype'];

	$referer = $_SERVER['HTTP_REFERER'];
	if(!$referer) $referer = $_SESSION['referer'];


	//검색엔진 키워드 저장
	if($referer){

		if(!eregi($_SERVER['HTTP_HOST'], $referer)){
			$refer_key=@parse_url(urldecode($referer));
			$site=$refer_key['host'];

			$keyword=$refer_key['query'];
			$time=time();

			if(eregi("naver.com",$site) || eregi("naver.co.kr",$site) || eregi("naver.kr",$site) || eregi("naver.net",$site)){
				$site = "네이버";
				$refer = explode("&query=",$referer);
				$refer = explode("&",$refer[1]);
				if(count($refer)>1 || eregi("utf8",$referer) || eregi("UTF-8",$referer)){
					$keyword = urldecode($refer[0]);
				}elseif(eregi("blogId=",$referer)){
					$keyword = "블로그";
				}else{
					$keyword = iconv("EUC-KR","UTF-8",urldecode($refer[0]));
				}
			}elseif(eregi("facebook.com",$site) || eregi("facebook.co.kr",$site)){
				$site = "facebook";
				$refer = (eregi("&q=",$referer) ? explode("&q=",$referer) : explode("?q=",$referer));
				$refer = explode("&",$refer[1]);
				$keyword = urldecode($refer[0]);
			}elseif(eregi("google.com",$site) || eregi("google.co.kr",$site)){
				$site = "구글";
				$refer = (eregi("&q=",$referer) ? explode("&q=",$referer) : explode("?q=",$referer));
				$refer = explode("&",$refer[1]);
				$keyword = urldecode($refer[0]);
			}elseif(eregi("daum.net",$site) || eregi("daum.com",$site)){
				$site = "다음";
				$refer = explode("&q=",$referer);
				$refer = explode("&",$refer[1]);
				$keyword = iconv("EUC-KR","UTF-8",urldecode($refer[0]));
			}elseif(eregi("yahoo.com",$site) || eregi("yahoo.co.kr",$site)){
				$site = "야후";
				$refer = (eregi("&p=",$referer) ? explode("&p=",$referer) : explode("?p=",$referer));
				$refer = explode("&",$refer[1]);
				$keyword = urldecode($refer[0]);
			}elseif(eregi("nate.com",$site) || eregi("nate.co.kr",$site) || eregi("nate.kr",$site)){
				$site = "네이트";
				$refer = explode("&q=",$referer);
				$refer = explode("&",$refer[1]);
				$keyword = iconv("EUC-KR","UTF-8",urldecode($refer[0]));
			}elseif(eregi("paran.com",$site) || eregi("paran.co.kr",$site)){
				$site = "파란";
				$refer = explode("&Query=",$referer);
				$refer = explode("&",$refer[1]);
				$keyword = iconv("EUC-KR","UTF-8",urldecode($refer[0]));
			}

			$res="Y";
		}

	//북마크/주소 직접입력
	}else{
		$site = "북마크/주소직접입력";
		$referer = "북마크/주소직접입력";
		$res="Y";
	}

	if($res=="Y"){

		//사용자 OS정보
		function getOS($userAgent) {

			$oses = array (
				'iPhone' => '(iPhone)',
				'Windows 3.11' => 'Win16',
				'Windows 95' => '(Windows 95)|(Win95)|(Windows_95)', // Use regular expressions as value to identify operating system
				'Windows 98' => '(Windows 98)|(Win98)',
				'Windows 2000' => '(Windows NT 5.0)|(Windows 2000)',
				'Windows XP' => '(Windows NT 5.1)|(Windows XP)',
				'Windows 2003' => '(Windows NT 5.2)',
				'Windows Vista' => '(Windows NT 6.0)|(Windows Vista)',
				'Windows 7' => '(Windows NT 6.1)|(Windows 7)',
				'Windows NT 4.0' => '(Windows NT 4.0)|(WinNT4.0)|(WinNT)|(Windows NT)',
				'Windows ME' => 'Windows ME',
				'Open BSD'=>'OpenBSD',
				'Sun OS'=>'SunOS',
				'Linux'=>'(Linux)|(X11)',
				'Safari' => '(Safari)',
				'Macintosh'=>'(Mac_PowerPC)|(Macintosh)',
				'QNX'=>'QNX',
				'BeOS'=>'BeOS',
				'OS/2'=>'OS/2',
				'Search Bot'=>'(nuhk)|(Googlebot)|(Yammybot)|(Openbot)|(Slurp/cat)|(msnbot)|(ia_archiver)'
			);

			foreach($oses as $os=>$pattern){
				if(eregi($pattern, $userAgent)) {
					return $os;
				}
			}
			return 'Unknown';
		}

		$os=getOS($_SERVER['HTTP_USER_AGENT']);

		//사용자 브라우저 정보
		function getBrowser(){
			$u_agent = $_SERVER['HTTP_USER_AGENT'];
			$bname = 'Unknown';
			$platform = 'Unknown';
			$version= "";

			if (preg_match('/linux/i', $u_agent)) {
				$platform = 'linux';
			}
			elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
				$platform = 'mac';
			}
			elseif (preg_match('/windows|win32/i', $u_agent)) {
				$platform = 'windows';
			}

			if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
			{
				$bname = 'Internet Explorer';
				$ub = "MSIE";
			}
			elseif(preg_match('/Firefox/i',$u_agent))
			{
				$bname = 'Mozilla Firefox';
				$ub = "Firefox";
			}
			elseif(preg_match('/Chrome/i',$u_agent))
			{
				$bname = 'Google Chrome';
				$ub = "Chrome";
			}
			elseif(preg_match('/Safari/i',$u_agent))
			{
				$bname = 'Apple Safari';
				$ub = "Safari";
			}
			elseif(preg_match('/Opera/i',$u_agent))
			{
				$bname = 'Opera';
				$ub = "Opera";
			}
			elseif(preg_match('/Netscape/i',$u_agent))
			{
				$bname = 'Netscape';
				$ub = "Netscape";
			}

			// finally get the correct version number
			$known = array('Version', $ub, 'other');
			$pattern = '#(?<browser>' . join('|', $known) .')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
			if (!preg_match_all($pattern, $u_agent, $matches)) {
				// we have no matching number just continue
			}

			// see how many we have
			$i = count($matches['browser']);
			if ($i != 1) {
				if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
					$version= $matches['version'][0];
				}
				else {
					$version= $matches['version'][1];
				}
			}
			else {
				$version= $matches['version'][0];
			}

			if ($version==null || $version=="") {$version="?";}

			return array(
				'userAgent' => $u_agent,
				'name'      => $bname,
				'version'   => $version,
				'platform'  => $platform,
				'pattern'    => $pattern
			);
		}

		$reg_ip			= anti_injection(getUserIp());
		$ua=getBrowser();
		$browser= $ua['name'] . " " . $ua['version'];
		$field['ip'] = $reg_ip;
		$field['site'] = $site;
		$field['keyword'] = str_replace(" ","",$keyword);
		$field['referer'] = $referer;
		$field['os'] = $os;
		$field['browser'] = $browser;
		$field['date']=date('Y-m-d H:i:s');

		if($reg_ip!=$MASTER_IP || $keyword!=""){
			db_insert("tbl_counter",$field);
			$referer_key = mysql_insert_id();
			$_SESSION['referer_key']=$referer_key;
		}
	}

	$_SESSION['referer']=$_SERVER['HTTP_HOST'];
?>