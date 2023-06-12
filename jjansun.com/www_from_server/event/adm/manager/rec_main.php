<?
	include("../inc/head.php");

	$isTitle	= 1;
	$subtitle	= "코드관리";

	$pcode		= $_REQUEST[pcode]?$_REQUEST[pcode]:1;

?>

<body>
<div id="adm_wrap">
	<!-- content -->
	<table style="width:100%;">
		<tr>
			<td id="left_area">
				<!-- 좌측메뉴 -->
				<? include "../inc/left.php"; ?>
			</td>
			<td id="content_area">
				<h1>카테고리 관리</h1>
				<!-- 본문 -->
				<div class="content">


<style>
	.tableT {padding:3px 0px 0px 5px;height:20px;background-color:#666666;border-top:1px solid #484747;border-left:1px solid #484747;border-right:1px solid #484747;}
</style>
<SCRIPT language="javascript">
<!--
	//카테고리 추가
	function addCategory(area){
		var cu_title, frameId;
		var oForm = document.theForm;
		var iselse = getIframe("iFrm_type"+ area).getValue('iselse');
		var oTitle;

		if(iselse!="") { return; }

		if(document.getElementById("addCategoryT1_"+ area, "").style.display == "inline"){
			oTitle = eval("oForm.cu_title"+ area);
		}else{
			oTitle = eval("oForm.cu_titleta"+ area);
		}

		cu_title = oTitle.value; frameId = 'iFrm_type'+ area;

		if(cu_title==""){
			alert("카테고리를 입력하세요"); oTitle.focus();
			return;
		}

		oForm.d1.value = encodeURIComponent('act=addct&area='+ area +'&val='+ (cu_title) +'&pcode='+ eval("oForm.code"+ (area-1)).value +'&loop='+ (oTitle.tagName=="INPUT"?0:cu_title.split("\n").length-1)) ;

		oForm.target = 'iFrm_temp';
		oForm.action = 'rec_proc.php';
		oForm.submit();

		oTitle.value='';
	}
	// 관리자전용
	function changeFolder(v){
		var t;

		clearSubDepth(1);

		if(v == 'on'){
			t = "/rec_list.php?code=0&area=1";
			$("#btn_category1").css('display','none');
			$("#btn_category2").css('display','inline');

			document.getElementById("up_title1", "").value = "Root";
			document.getElementById("code0", "").value = "0";
		}else{
			t = "/rec_list.php?code=1&area=1";
			$("#btn_category1").css('display','inline');
			$("#btn_category2").css('display','none');

			document.getElementById("up_title1", "").value = "Root";
			document.getElementById("code0", "").value = "1";
		}
		getIframe("iFrm_type1").location.href = t;
	}
	function ifrsrc(id, src){
		getIframe(id).location.href=src;
	}
	function clearSubDepth(area){
		try{
			for(var i=(area+1); i<100; i++) $('#div'+ i).css('display','none');
		}catch(e){}
	}
	function getIframe(frameID){
		return document.getElementById(frameID, "").contentWindow;
	}
	function chkKeyDown(c, s){
		var code = event.keyCode;
		if(code == c) eval(s);
	}
	function iframeReload(frameID){
		getIframe(frameID).location.reload();
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
</SCRIPT>

	<form name="theForm" method="post">
	<input type="hidden" name="d1" id="d1" value="">
	<input type="hidden" name="code0" id="code0" value="<?=$pcode?>">

<?for($i=1; $i<=20; $i++){?>
	<input type="hidden" size="5" name="code<?=$i?>" id="code<?=$i?>" value=""/>
	<div id="div<?=$i?>" style="display:<?=($i==1?"inline":"none")?>;float:left; width:400px; border:1px solid #cccccc">
		<div class="tableT">
			<div style="float:left;">‡
				<input type="text" name="up_title<?=$i?>" id="up_title<?=$i?>" value="Root" maxlength="15" readonly style="height:18px;width:80px;ime-mode:active;border:0;background:transparent;color:#FF9900;font-weight:bold;font-size:11px;">
			</div>

			<div style="float:right;">
				<div style="float:left; display:inline;" id="addCategoryT1_<?=$i?>">
					<div style="float:left;">
						<input type="text" name="cu_title<?=$i?>" id="cu_title<?=$i?>" maxlength="200" align="absmiddle" style="background:transparent; border: 1px dotted #cccccc; width:120px;color:white;" onkeydown="chkKeyDown(13, 'addCategory(<?=$i?>)');"/></div>
					<a href="javascript:addCategory(<?=$i?>);"><font style="color: white;">Add</font>&nbsp;</a>
				</div>
			</div>

		</div>
		<div>
			<iframe name="iFrm_type<?=$i?>" id="iFrm_type<?=$i?>" width="100%" height="300" frameborder="0" hspace="0" vspace="0" scrolling="yes" <?=($i==1?" src='rec_list.php?code=".$pcode."&area=1'":"")?>></iframe>
		</div>
	</div>
<?}?>
</form>
<iframe name="iFrm_temp" width="0" height="0"></iframe>


