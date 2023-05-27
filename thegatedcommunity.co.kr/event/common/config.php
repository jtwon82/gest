<?
/* ┏━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┓
	┃ 사용자 정의 변수 모음																																							 ┃
	┃ 업데이트:2012.01.10																																								 ┃
	┗━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┛*/
	ini_set('error_reporting', !E_NOTICE & !E_WARNING);

	#사이트 환경 정보
	#$SITE_INFO=db_select("select * from tbl_site_config");

	//마스터 관리자
	$MASTER_UID = "admin";
	$MASTER_IP = "211.36.33.211";

	//페이징 함수
	$page_first_page="<img src=\"../images/board/pageNum_first.png\" />";
	$page_post_start="<img src=\"../images/board/pageNum_prev.png\" />";
	$page_next_start="<img src=\"../images/board/pageNum_next.png\" />";
	$page_last_page="<img src=\"../images/board/pageNum_last.png\" />";

	if(!$list_num) $list_num=10;
	if(!$page_num) $page_num=10;
	$start_num=($page-1)*$list_num;

	//사이트 경로 설정
	$DOCUMENT_ROOT=$_SERVER['DOCUMENT_ROOT']; //루트경로
	$return_url=$_SERVER['REQUEST_URI'];
	$current_url=urlencode("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']); //현재페이지 경로

	//페이스북 정보
	$facebook_appId = "646310392104881"; // App를 등록하고 나면 알수 있는 App ID
	$facebook_secret = "b26694db10dc722eeb2c82efa14d44a9"; // App를 등록하고 나면 알수 있는 App Secret

	//트위터 정보
	$consumer_key = "OeZWVPgnDOmKBvrcZc53yRDa3"; // App를 등록하고 나면 알수 있는 App ID
	$consumer_secret = "cpg6cGuyjgwEKgCEFGd8k5cLpNdOQwf9Mol8houcHDckJbOhE4"; // App를 등록하고 나면 알수 있는 App Secret

/* ┏━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┓
	┃ 사용자 정의 배열 모음																																							 ┃
	┃ 업데이트:2012.01.10																																								 ┃
	┗━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┛*/

	$deny_ip = array("");

?>