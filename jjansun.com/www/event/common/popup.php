<?
	$today = date("Y-m-d");
	$result = mysql_query("select * from tbl_popup where open = 'Y' order by regdate desc");

	if($result){
		$i = 1;
		while($row = mysql_fetch_array($result)){
			$idx = trim($row["idx"]);
			$style = trim($row['style']);
			$top = trim($row['ptop']);
			$left = trim($row['pleft']);
			$width = trim($row["pwidth"]);
			$height = trim($row["pheight"])+25;
			$content = $row['content'];
			if($style=="L"){     //레이어 팝업일때
?>
<div id='popup<?=$idx?>' style='position:absolute; left:<?=$left?>px; top:<?=$top?>px; width:<?=$width?>px; height:<?=$height?>px; z-index:10000; visibility: hidden;'>
	<table width="<?=$width?>" height="<?=$height?>" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="border:1pt solid #403e4b">
		<tr>
			<td valign="top" colspan="2"><?=$content?></td>
		</tr>
		<tr>
			<td height="28" style="background:#6d6d6d;padding-left:20px">
				<a style="cursor:pointer" onclick="setCookie('pop<?=$idx?>','done',1); document.all.popup<?=$idx?>.style.visibility='hidden';"><strong><font color="#ffffff">하루동안 이 창을 열지 않음</a>
			</td>
			<td align="right" style="background:#6d6d6d;padding-right:20px">
				<a style="cursor:pointer" onclick="document.all.popup<?=$idx?>.style.visibility='hidden'"><strong><font color="#ffffff">닫기</font></strong></a>
			</td>
		</tr>
	</table>
</div>
<script>if(getCookie( 'pop<?=$idx?>' ) != 'done' ) document.all.popup<?=$idx?>.style.visibility='visible';</script>
<?
			}else{    //윈도우 팝업일 때
?>
<script language="javascript">
	if(getCookie("pop<?=$idx?>") != "done"){
		window.open('../common/window_pop.php?idx=<?=$idx?>','pop<?=$idx?>','toolbar=no,screenX=100,screenY=80,width=<?=$width?>,height=<?=$height?>,left=<?=$left?>,top=<?=$top?>');
	}
</script>
<?
			}
		}
	}
?>