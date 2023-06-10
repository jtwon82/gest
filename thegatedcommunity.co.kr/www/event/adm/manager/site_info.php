<?
	include("../inc/head.php");
	$pList=db_select("select * from tbl_site_config");
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
				<h1>사이트 정보관리</h1>
				<!-- 본문 -->
				<div class="content">

					<!-- 상세페이지 -->
					<form name="frmWrite" id="frmWrite" method="post" action="site_pro.php">
					<div class="tableStyle2">
						<h2>♦ 사이트 기본 설정 ♦</h2>
						<table>
							<colgroup>
							<col width="15%">
							<col width="*">
							</colgroup>
							<tr>
								<th>사이트 URL</th>
								<td class="emp">http://<?=$_SERVER['HTTP_HOST']?></td>
							</tr>
							<tr>
								<th>사이트 타이틀</th>
								<td><input title="사이트 타이틀" name="title" type="text" class="dInput" style="width: 300px;" value="<?=$pList['title']?>" /> <span class="help">브라우저 창의 최상단 제목줄에 표시할 내용</span></td>
							</tr>
							<tr>
								<th>회사명</th>
								<td><input title="사이트 타이틀" name="company" type="text" class="dInput" style="width:150px;" value="<?=$pList['company']?>" /> <span class="help">메일 발송시 발신자명으로 사용</span></td>
							</tr>
							<tr>
								<th>대표전화번호</th>
								<td colspan="3"><input title="대표전화번호" name="tel" type="text" class="dInput" style="width:150px;" value="<?=$pList['tel']?>" /> <span class="help">발신용 SMS 번호로 사용</span></td>
							</tr>
							<tr>
								<th>대표휴대전화</th>
								<td colspan="3"><input title="대표휴대전화" name="hp" type="text" class="dInput" style="width:150px;" value="<?=$pList['hp']?>" /> <span class="help">수신용 SMS 번호로 사용</span></td>
							</tr>
							<tr>
								<th>대표이메일</th>
								<td colspan="3"><input title="대표이메일" name="email" type="text" class="dInput" style="width:150px;" value="<?=$pList['email']?>" /> <span class="help">수신/발신용 기본 메일 주소로 사용</span></td>
							</tr>
							<tr>
								<th>관리자명</th>
								<td colspan="3"><input title="관리자명" name="manager" type="text" class="dInput" style="width:150px;" value="<?=$pList['manager']?>" /> <span class="help">게시판 작성시 기본 이름으로 사용</span></td>
							</tr>
							<tr>
								<td colspan="3"></td>
							</tr>
						</table>
					</div>

					<div class="tableStyle2" style="display:none;">
						<h2>♦ 회원가입 설정 ♦</h2>
						<table>
							<colgroup>
							<col width="15%">
							<col width="*">
							</colgroup>
							<tr>
								<th>신규가입등급</th>
								<td>
									<select name="join_level" class="dSelect">
								<?
									$mRs=db_query("select * from tbl_member_level where level_code>0 order by idx");
									while($mList=db_fetch($mRs)){
										$sel=($pList['join_level']==$mList['level_code'] ? "selected" : "");
										echo "<option value=\"".$mList['level_code']."\" $sel>".$mList['level_name']."</option>\n;";
									}
								?>
									</select>
									<span class="help">회원가입시 기본 설정된 회원등급</span> (회원가입시 관리자 승인이 필요할 경우 "가입신청자"로 설정)
								</td>
							</tr>
							<tr>
								<th>회원탈퇴설정</th>
								<td>
									<input type="radio" name="join_del" id="join1" value="1" <?=($pList['join_del']=="1" ? "checked" : "")?> /><label for="join1" style="display:inline-block;width:110px;padding:5px;">즉시탈퇴</label>
									<span class="help">회원이 탈퇴 신청시 즉시 탈퇴 승인되며 모든 회원 정보 삭제</span><br />
									<input type="radio" name="join_del" id="join2" value="2" <?=($pList['join_del']=="2" ? "checked" : "")?> /><label for="join2" style="display:inline-block;width:110px;padding:5px;">관리자 승인후 탈퇴</label>
									<span class="help">회원탈퇴시 로그인 기능만 제한되며 관리자가 승인 후 회원정보 삭제</span>
								</td>
							</tr>
						</table>
					</div>

					<!-- Button -->
					<div id="btn_bott">
						<span class="button blue"><input type="submit" value="저 장"  style="width:80px" /></span>
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