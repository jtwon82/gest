<?
	include("../inc/head.php");
?>
<body style="background:#C11258;">

<div class="tableSearch" style="border-top:7px solid #343434;">
	<h2 style="color:#fff;font-size:15px;padding:15px 10px 5px 10px;">회원검색</h2>
	<div style="width:100%;background:#fff;background:#C11258;">
		<form method="post" name="search" onsubmit="return formChk(this)">
		<input type="hidden" name="pole_type" id="poleType" value="" />
		<p style="padding:15px;">
			<span style="display:inline-block;color:#fff;font-size:11px;padding:5px 0;">ㆍ검색할 회원명 또는 ID를 입력하세요.</span>
			<input type="text" name="keyword" class="dInput req" title="검색키워드" style="width:200px;" />
			<span class="button black"><input type="submit" value="회원검색" onclick="fnCheckInput();" /></span>
		</p>
		</form>
		<div id="searchRes">
			<ul>
			<?
				if($_POST['keyword']){
					$rs=db_query("select name, email from tbl_member where member_level >= 300 and userid like '%".$_POST['keyword']."%' or name like '%".$_POST['keyword']."%'");
					$cnt=db_count("tbl_member","member_level >= 300 and userid like '%".$_POST['keyword']."%' or name like '%".$_POST['keyword']."%'");
					while($list=db_fetch($rs)){
			?>
				<li>
					<span style="display:inline-block;width:90px;font-weight:bold;"><?=$list['name']?></span>
					<span style="display:inline-block;width:200px;" class="mailtxt"><?=$list['email']?></span>
					<span class="button small"><input type="button" value="선택" onclick="inputEmail(this)" /></span>
				</li>
			<?
					}

					if($cnt<1) echo "<li style=\"text-align:center;\">검색된 회원이 없습니다.</li>";
				}

				if(!$_POST['keyword']) echo "<li style=\"text-align:center;\">검색된 회원이 없습니다.</li>";
			?>
			</ul>
		</div>
	</div>
	</form>
	<div style="margin-top:15px;text-align:center;"><span class="button black"><input type="button" value="닫기" onclick="self.close();" /></span></div>
</div>
</body>
</html>