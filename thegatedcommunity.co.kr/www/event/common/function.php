<?

	function getUserIp(){
		if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
			return $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			return $_SERVER['REMOTE_ADDR'];
		}
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
		$result = mysql_connect($db_host, $db_user, $db_pass) or die(mysql_error());
		mysql_select_db($db_name) or die(mysql_error());
		mysql_query("set names utf8");
		return $result;
	}

	//SQL 쿼리 실행 함수
	function db_query($sql){

		$rs = mysql_query($sql);
		if(mysql_error()){
			echo "DB ERROR";
			if($_SERVER['REMOTE_ADDR']==$MASTER_IP) echo $sql;
		}
		return $rs;
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
	function db_fetch($rs){
		return @mysql_fetch_array($rs);
	}
	//데이터를 배열로 가져오기
	function db_fetch_assoc($rs){
		return @mysql_fetch_assoc($rs);
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

		echo "<script type='text/javascript'>alert('$msg');$url;</script>";
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

		$tmp_filename = ($change==1 ? $org_name.".".$ext : time().".".$ext);

		$i = 1;
		while(file_exists($path."/".$tmp_filename)){
			$tmp_filename = ($change==1 ? $org_name."_".$i.".".$ext : time().".".$ext);
			$i++;
		}

		if(!move_uploaded_file($file, "$path/$tmp_filename")){
			echo "파일 업로드 Error!! 시스템 관리자에게 문의해주세요";
			exit;
		}
		return $tmp_filename;
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
			$link_str .= " <a href='".$link."?".$url.($url?"&":"")."page=1' class='arrow'>".$first_page."</a> ";
		}

		if ($start_page > 1) {
			$link_str .= " <a href='".$link."?".$url.($url?"&":"")."page=".($start_page-1)."' class='arrow'>".$post_start."</a> ";
		}

		if ($total_page > 1) {
			for ($i=$start_page;$i<=$end_page;$i++) {
				if ($page != $i) {
					$link_str .= " <a href='".$link."?".$url.($url?"&":"")."page=".$i."'><span>$i</span></a> ";
				} else {
					$link_str .= " [<a class='on'>$i</a>] ";
				}
			}
		}else{
			$link_str .= "<a class='on'>1</a>";
		}

		if ($total_page > $end_page) {
			$link_str .= " <a href='".$link."?".$url.($url?"&":"")."page=".($end_page+1)."' class='arrow'>".$next_start."</a> ";
		}

		if ($page < $total_page) $link_str .= " <a href='".$link."?".$url.($url?"&":"")."page=".$total_page."' class='arrow'>".$last_page."</a> ";

		if($count>0) echo $link_str;
	}
	function page_list_bodynatur($page="1", $count, $list_num="15", $page_num="10") {

		$link=$_SERVER['PHP_SELF'];

		// 1. 전체 페이지 계산
		$total_page  = ceil($count / $list_num);

		$start_page = @(((int)(($page-1)/$page_num))*$page_num)+1;
		$temp_pnum = $page_num - 1 ;
		$end_page = $start_page + $temp_pnum;

		if ($end_page >= $total_page) $end_page = $total_page;
		if ($page > 1) {
			$link_str .= "<em><a href='javascript:VIDEO.online_list(1, VIDEO.list_process)' class='arrow'><<</a></em> ";
		}

		if ($start_page > 1) {
			$link_str .= " <em><a href='javascript:VIDEO.online_list(".($start_page-1).", VIDEO.list_process)' class='arrow'><</a></em> ";
		}

		if ($total_page > 1) {
			for ($i=$start_page;$i<=$end_page;$i++) {
				if ($page != $i) {
					$link_str .= " <em><a href='javascript:VIDEO.online_list(".$i.", VIDEO.list_process)'>$i</a></em> ";
				} else {
					$link_str .= " <em class='active'><a >$i</a></em> ";
				}
			}
		}else{
			$link_str .= "<em class='active'><a >1</a></em>";
		}

		if ($total_page > $end_page) {
			$link_str .= " <em><a href='javascript:VIDEO.online_list(".($end_page+1).", VIDEO.list_process)' class='arrow'>></a></em> ";
		}

		if ($page < $total_page) $link_str .= " <em><a href='javascript:VIDEO.online_list(".$total_page.", VIDEO.list_process)' class='arrow'>>></a></em> ";

		if($count>0) return ($link_str);
	}
	function page_list_topevent($page="1", $count, $list_num="15", $page_num="10") {

		$total_page  = ceil($count / $list_num);

		$start_page = @(((int)(($page-1)/$page_num))*$page_num)+1;
		$temp_pnum = $page_num - 1 ;
		$end_page = $start_page + $temp_pnum;

		#return "$start_page $temp_pnum $end_page";

		if ($end_page >= $total_page) $end_page = $total_page;
		if ($page > 1) {
			$link_str[first] .= "BoardList(".($page-1).")";
		}

		if ($page < $total_page){
			$link_str[last] = "BoardList(".($page+1).")";
		}
		

		if($count>0) return $link_str; else return 'xx';
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
	function thumbnail($file, $save_filename, $max_width=100, $max_height=100, $sizeChg=1){

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


		if($img_info[2]==1) $src_img=ImageCreateFromGIF($file);
		elseif($img_info[2]==2) $src_img=ImageCreateFromJPEG($file);
		elseif($img_info[2]==3) $src_img=ImageCreateFromPNG($file);
		elseif($img_info[2]==4) $src_img=ImageCreateFromWBMP($file);
		else return false;

		$img_info = getImageSize($file);//원본이미지의 정보를 얻어옵니다
		$img_width = $img_info[0];
		$img_height = $img_info[1];

		$crt_width=$max_width;  //생성되면 이미지 사이즈
		$crt_height=$max_height;

		//1.가로 세로 원본비율을 맞추고, 남은 영역에 색채워서 정해진 크기로 생성
		if($sizeChg==1){
			/*
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
			*/
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