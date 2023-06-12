<?
	include "../common/function.php";
	include "../common/db.php";
	include "../common/config.php";

	$idx = $_GET['idx'];

	if(!$idx){
		Script("window.close();");
	}else{

		$sql = "select * from tbl_popup where idx = '$idx'";
		$row = mysql_fetch_array(mysql_query($sql));

		$title  = trim($row["title"]);
		$content  = trim($row["content"]);
		$width  = trim($row["pwidth"]);
		$height  = trim($row["pheight"]);
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="ko" xml:lang="ko">
<head>
<title><?=$title?></title>
<script language="javascript" src="/js/jquery-1.3.2.js"></script>
<script language="javascript" src="/js/global.js"></script>
<link href="../css/basic.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?=$title?></title>
<script>
function fnClose() {
	setCookie( "pop<?=$idx?>", "done" , 1);
	self.close();
}
</script>
<style>
a:hover { color: #00a5b0; text-decoration: none; } /* underline */
</style>
</html>
<body style="background:none">
<div id="winpop"><?=$content?></div>
<div style="background:#6D6D6D; position:absolute; bottom:0; width:100%; height:25px;">
	<p style="position:absolute;bottom:5px;"><a href="#" onclick="fnClose();"><span style="color:#fff;font-weight:bold;">오늘하루 이창을 열지 않음</span></a></p>
	<p style="position:absolute;bottom:5px;right:10px;"><a href="#" onclick="self.close();"><span style="color:#fff;font-weight:bold;">닫기</span></a></p>
</div>
</body>
</html>