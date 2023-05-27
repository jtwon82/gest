<?
	#include "../common/db.php";
	#include "../common/function.php";
	#include "../common/config.php";

	//첨부파일 다운로드 (파일경로, 파일명, 실제파일명)
	$Path="../../upload/marley/".$_GET['Path'];
	$File=$_GET['File'];
	$Org=($_GET['Org'] ? $_GET['Org'] : $File);

	$DownFile =$Path."/".$File;
	$Org=iconv("UTF-8","CP949", $Org);

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
?>