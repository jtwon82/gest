<?
	include("../inc/head.php");

	if($_GET['idx']){
		$pList=db_select("select * from tbl_member where idx='".$_GET['idx']."'");
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
				<h1>회원관리</h1>
				<!-- 본문 -->
				<div class="content">

					<!-- 상세페이지 -->
					<form name="frmWrite" id="frmWrite" method="post" action="member_pro.php" onsubmit="return formChk(this);">
					<input type="hidden" name="mode" value="<?=($_GET['idx'] ? "modify" : "write")?>" />
					<input type="hidden" name="idx" value="<?=$_GET['idx']?>" />
					<div class="tableStyle2">
						<h2>♦ 회원 기본 정보 ♦</h2>
						<table>
							<colgroup>
							<col width="15%">
							<col width="35%">
							<col width="15%">
							<col width="35%">
							</colgroup>
							<tr>
								<th>이름</th>
								<td><input type="text" name="uname" class="dInput req" title="이름" value="<?=$pList['uname']?>" /></td>
								<th>아이디</th>
								<td><input type="text" name="userid" id="uid" class="dInput req" title="아이디" value="<?=$pList['userid']?>" <?=($_GET['idx'] ? "readonly":"")?>/> (영문, 숫자 4자~12자)</td>
							</tr>
							<tr>
								<th>이메일</th>
								<td>
									<input class="dInput " name="email" type="text" id="email" style="width:150px" title="이메일주소" value="<?=$pList['email']?>" />
								<th>비밀번호</th>
								<td><input type="password" name="passwd" id="pwd" class="dInput" style="width:80px;" maxlength="15" /> <span class="help">임의변경만 가능합니다.</span></td>
							</tr>
							<tr>
								<th>회원등급</th>
								<td colspan="3">
									<select name="member_level" class="dSelect">
								<?
									if(!$_GET['idx']) $pList['member_level']=$SITE_INFO['join_level'];
									$mRs=db_query("select * from tbl_member_level where level_code>0 order by idx");
									while($mList=db_fetch($mRs)){
										$sel=($pList['member_level']==$mList['level_code'] ? "selected" : "");
										echo "<option value=\"".$mList['level_code']."\" $sel>".$mList['level_name']." ($mList[level_code])</option>\n;";
									}
								?>
									</select>
								</td>
							</tr>
						</table>

						<h2>♦ 회원 프로필 ♦</h2>
						<table>
							<colgroup>
							<col width="15%">
							<col width="35%">
							<col width="15%">
							<col width="35%">
							</colgroup>
							<tr>
								<th>제목</th>
								<td colspan="3"><input type="text" name="title" id="title" title="제목" class="dInput" style="width:90%;" value="<?=$pList['title']?>" /></td>
							</tr><!-- 
							<tr>
								<th>지역</th>
								<td><input type="text" name="area" id="area" class="dInput" title="지역" style="width:80%;" value="<?=$pList['area']?>" /></td>
								<th>학력</th>
								<td><input type="text" name="education" id="education" class="dInput" title="학력" style="width:80%;" value="<?=$pList['education']?>" /></td>
							</tr>
							<tr>
								<th>직업</th>
								<td><input type="text" name="job" id="job" class="dInput" title="직업" style="width:80%;" value="<?=$pList['job']?>" /></td>
								<th>직장</th>
								<td><input type="text" name="company" id="company" class="dInput" title="직장" style="width:80%;" value="<?=$pList['company']?>" /></td>
							</tr>
							<tr>
								<th>페이스북</th>
								<td><input type="text" name="facebook" id="facebook" class="dInput" title="페이스북" style="width:80%;" value="<?=$pList['facebook']?>" /></td>
								<th>트위터</th>
								<td><input type="text" name="twitter" id="twitter" class="dInput" title="트위터" style="width:80%;" value="<?=$pList['twitter']?>" /></td>
							</tr>
							<tr>
								<th>내용</th>
								<td colspan="3"><textarea name="content" id="content" style="width:95%; height:200px;" class="dInput req"><?=$pList['content']?></textarea></td>
							</tr> -->
						</table>
					</div>

					<!-- Button -->
					<div id="btn_bott">
						<span class="button blue"><input type="submit" value="저장" /></span>
						<span class="button black"><a href="member_list.php?page=<?=$page?>&skey=<?=$skey?>&sval=<?=$sval?>">목록</a></span>
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