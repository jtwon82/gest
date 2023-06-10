<?
	include("../inc/head.php");

	if($_GET['idx']){
		$pList=db_select("select * from tbl_member_level where idx='".$_GET['idx']."'");
	}
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
				<h1>회원등급</h1>
				<!-- 본문 -->
				<div class="content">

					<!-- 상세페이지 -->
					<form name="level" id="level" method="post" action="level_pro.php" onsubmit="return formChk(this);">
					<input type="hidden" name="mode" value="<?=($_GET['idx'] ? "modify" : "write")?>" />
					<input type="hidden" name="idx" value="<?=$_GET['idx']?>" />
					<div class="tableStyle2">
						<table>
							<colgroup>
							<col width="20%">
							<col width="*">
							</colgroup>
							<tr>
								<th>등급명</th>
								<td><input type="text" name="level_name" class="dInput req" title="등급명" value="<?=$pList['level_name']?>" /></td>
							</tr>
							<tr>
								<th>등급코드</th>
								<td><input type="text" name="level_code" class="dInput" value="<?=$pList['level_code']?>" readonly="readonly" /> <span class="help">등급코드는 자동부여되며 수정할 수 없습니다.</span></td>
							</tr>
							<tr>
								<th>로그인허용</th>
								<td><input type="radio" name="login" id="lo1" value="Y" <?=($pList['login']=="Y" || $pList['login']=="" ? "checked" : "")?> /> <label for="lo1">허용</label> &nbsp;<input type="radio" name="login" id="lo2" value="N" <?=($pList['login']=="N" ? "checked" : "")?> /> <label for="lo2">차단</label></td>
							</tr>
						</table>
					</div>

					<!-- Button -->
					<div id="btn_bott">
						<span class="button blue"><input type="submit" value="저장" /></span>
						<span class="button black"><a href="level_list.php">목록</a></span>
					</div>
					</form>

				</div>
				<!-- end -->
			</td>
		</tr>
	</table>
</div>
</body>
</html>