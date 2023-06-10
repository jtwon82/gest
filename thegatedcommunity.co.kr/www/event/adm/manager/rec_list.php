<?
	include("../inc/head.php");

	$isTitle	= 0;
	$subtitle	= "코드관리";

	$code		= $_REQUEST[code]?$_REQUEST[code]:0;
	$area		= ($_REQUEST[area]);
	$iselse		= ($_REQUEST[iselse]);

	$sql = "
		select *
		from tbl_common b
		where pcodes = ". $code ."
		order by step
	";

	#$st			= db_select($sql);
	$rs			= gArrFetch($sql);
	$iRows		= count($rs);

?>
<body>
<div id="adm_wrap">
<style>
	*{font-size:11px;font-family:돋움; margin:0;padding:0;}
</style>
<script language="javascript">
<!--
	function moveStep(act, s, e){
		var f = document.theForm;
		var tmp1;
		switch(act){
		case 'u':
			if(s==0){alert("더이상 올라갈 수 없습니다"); return;}
			tmp1 = -1;
		break;
		case 'd':
			if(s==e){alert("더이상 내려갈 수 없습니다"); return;}
			tmp1 = 1;
		break;
		}
		swapVal(eval("f.code"+ s), eval("f.code"+ (s+tmp1)));
		swapVal(eval("f.title"+ s), eval("f.title"+ (s+tmp1)));
		swapVal(eval("f.value"+ s), eval("f.value"+ (s+tmp1)));
		eval("f.step"+ s).value = s +'_'+ eval("f.code"+ s).value +'_'+ eval("f.title"+ s).value;
		eval("f.step"+ (s+tmp1)).value = (s+tmp1) +'_'+ eval("f.code"+ (s+tmp1)).value +'_'+ eval("f.title"+ (s+tmp1)).value;
		f.s1.value=eval("f.step"+ s).value;
		f.s2.value=eval("f.step"+ (s+tmp1)).value;

		f.d1.value=encodeURIComponent("act=swaporder&step1="+ f.s1.value +"&step2="+ f.s2.value);
		f.target="tmpIfrm";
		f.action="rec_proc.php";
		f.submit();
	}
	// input 상자의 내용을 변경한다.
	function swapVal(o1, o2){
		var tmp1;
		tmp1 = o1.value;
		o1.value = o2.value;
		o2.value = tmp1;
	}
	function runProc(act, n, o){
		var f = document.theForm;
		try{
			var code = eval("f.step"+ n).value.split("_")[1];
			var names = eval("f.title"+ n).value;
			var area = parseInt(f.area.value);
		}catch(e){}
		var tmp1, tmp2;

		parent.clearSubDepth(area);

		switch(act){
		case 'v':
			parent.getObj("up_title"+ (area+1), "").value = names;
			parent.getObj('div'+ (area+1), "").style.display='';
			parent.ifrsrc("iFrm_type"+(area+1),"rec_list.php?code="+code+"&area="+(area+1));
			parent.getObj("pcode"+ area, "").value = code;
			parent.getObj("code"+ area, "").value = code;
			break;
		case 'd':
			if(confirm("정말로 삭제하시겠습니가?")){
				f.d1.value=encodeURIComponent("act=ditem&item="+ code +"&area="+ area +"&n="+ n);
				f.target="tmpIfrm";
				f.action="rec_proc.php";
				f.submit();
			}
			break;
		case 'm':
			if(o.value != o.defaultValue){
				f.d1.value=encodeURIComponent("act=uitem&code="+ code +"&title="+ o.value +"&gubun="+ (o.name.substr(0, 1)=="t"?"title":"value"));
				f.target="tmpIfrm";
				f.action="rec_proc.php";
				f.submit();
			}
			break;
		}
	}
	function getValue(fname){
		return getObj(fname, "").value;
	}
	function getObj(obj,nodeObject){ 

		var doc = document;
		if(nodeObject=="parent")
			doc = parent.document;

		if(typeof obj == 'object'){
			return obj
		}else if(doc.getElementById && doc.getElementById(obj)){ 
			return doc.getElementById(obj); // 대부분의 브라우저 
		}else if (doc.getElementsByName && doc.getElementsByName(obj)){ 
			return doc.getElementsByName(obj); //
		}else if (doc.all && doc.all(obj)){ 
			return doc.all(obj); // IE4와 5.0 
		}else if (doc.layers && doc.layers[obj]){ 
			return doc.layers[obj];  // Netscape 4.x 
		}else{ 
			return false; 
		} 
	}
//-->
</script>

<body cellpadding="0" cellspacing="0" >

	<table cellpadding="0" cellspacing="0" border="0" width="100%" style="border:1px solid #eeeeee" align="center">
	<form name="theForm" method="post">
		<!-- Hidden From Start -->
		<input type="hidden" name="d1" id="d1" value="">
		<input type="hidden" name="s1" id="s1" value="">
		<input type="hidden" name="s2" id="s2" value="">
		<input type="hidden" name="area" id="area" value="<?=$area?>">
		<input type="hidden" name="iselse" id="iselse" value="<?=$iselse?>">
		<!-- Hidden From End   -->
		<tr align="center" style="background:#eeeeee" height="23" style="font-size:11px;font-family:돋움;">
			<td width="35">스텝</td>
			<td width="1" bgcolor="#FFFFFF"></td>
 			<td width="25">코드</td>
			<td width="1" bgcolor="#FFFFFF"></td>
			<td width="100">제목</td>
			<td width="1" bgcolor="#FFFFFF"></td>
 			<td width="100">값 </td>
			<td width="1" bgcolor="#FFFFFF"></td>
			<td width="25">액션</td>
		</tr>
<?if($iRows<1){ ?>
		<tr>
				<td height="30" colspan="9">
				<div align="center">검색된 데이타가 없습니다.
				</td>
		</tr>
<?}?>
<?for($i=0; $i<$iRows; $i++){?>
		<tr><td colspan="11" bgcolor="#eeeeee" height="1"></td></tr>
		<tr height="23">
			<td align="center">
				<input type="hidden" name="step<?=$i?>" id="step<?=$i?>" value="<?=$rs[$i][step]?>_<?=$rs[$i][codes]?>_<?=$rs[$i][title]?>_<?=$rs[$i][val]?>" size="15"/>
				<a href="javascript: moveStep('u', <?=$i?>, <?=$iRows-1?>);" style="font-size:15px;">↑</a>
				<a href="javascript: moveStep('d', <?=$i?>, <?=$iRows-1?>);" style="font-size:15px;">↓</a>
			</td>
			<td bgcolor="#eeeeee"></td>
			<td style="background:#eeeeee;">
				<input type="text" name="code<?=$i?>" id="code<?=$i?>" value="<?=$rs[$i][codes]?>" readonly onfocus="setFocus('title<?=$i?>');" style="width:25px;ime-mode:active;background-color:#FFFFFF;background:transparent;">
 			</td>
			<td bgcolor="#eeeeee"></td>
			<td align="center">
				<input type="text" name="title<?=$i?>" id="title<?=$i?>" onblur="runProc('m', '<?=$i?>', this);" value="<?=$rs[$i][title]?>" maxlength="200" style="width:100%;">
			</td>
			<td bgcolor="#eeeeee"></td>
			<td align="center">
				<input type="text" name="value<?=$i?>" id="value<?=$i?>" onblur="runProc('m', '<?=$i?>', this);" style="width:100%;" value="<?=$rs[$i][val]?>"/>
 			</td>
			<td bgcolor="#eeeeee"></td>
			<td align="center">
				<a href="javascript: runProc('d', '<?=$i?>', this);">D</a>
				<?
			if($area<20){
				?><a href="javascript: runProc('v', '<?=$i?>', this);">V</a></td>
		<?}?>
		</tr>
<?} ?>
	</form>
	</table>
	<iframe name="tmpIfrm" width="0" height="0"></iframe>
<SCRIPT LANGUAGE="JavaScript">
<!--
	$("body").css({'background':'none'});
//-->
</SCRIPT>
</body>