<?
	include("../inc/head.php");

	if($_GET['idx']){
		$pList=db_select("select * from tbl_board_config where idx='".$_GET['idx']."'");
		list($thumbwidth, $thumbheight)=@explode("/",$pList['thumbsize']);
	}else{

		//새로 등록일 경우 기본값 설정
		$pList['skin']="skin1";
		$pList['reply']="Y";
		$pList['fileuse']="Y";
		$pList['linkuse']="Y";
		$pList['auth_list']="0";
		$pList['auth_view']="0";
		$pList['auth_write']="0";
		$pList['auth_reply']="0";
		$pList['auth_comment']="0";
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
				<h1>BUILDER</h1>
				<!-- 본문 -->
				<div class="content">

					<!-- 상세페이지 -->
					<form name="board" id="board" method="post" action="board_pro.php" onsubmit="return formChk(this);">
					<input type="hidden" name="mode" value="<?=($_GET['idx'] ? "modify" : "write")?>" />
					<input type="hidden" name="idx" value="<?=$_GET['idx']?>" />
					<div class="tableStyle2">
						<h2>♦ 게시판 기본 설정 ♦</h2>
						<table>
							<colgroup>
							<col width="15%">
							<col width="35%">
							<col width="15%">
							<col width="35%">
							</colgroup>
							<tr>
								<th>게시판명</th>
								<td colspan="3"><input type="text" name="b_name" title="게시판명" class="dInput req" value="<?=$pList['b_name']?>" /></td>
							</tr>
							<tr>
								<th>게시판형태</th>
								<td>
									<select name="b_type" class="dSelect" title="게시판형태" onchange="$('tr[id^=pform]').hide();(this.value==2 || this.value==3) ? $('#pform'+this.value).show() : '';">
									<?
										foreach($bbsTypeArray as $xkey=>$xval){
											$sel=($pList['b_type']==$xkey ? "selected" : "");
											echo "<option value=\"$xkey\" $sel>$xval[0]</option>\n;";
										}
										reset($bbsSkinArray);
									?>
									</select>
								</td>
								<th>스킨</th>
								<td>
									<select name="skin" class="dSelect">
									<?
										foreach(getSkinList() as $xkey=>$xval){
											$sel=($pList['skin']==$xval ? "selected" : "");
											echo "<option value=\"$xval\" $sel>$xval</option>\n;";
										}
										reset($bbsSkinArray);
									?>
									</select>
								</td>
							</tr>
							<tr>
								<th>페이지당 글수</th>
								<td><input type="text" name="list_num" class="dInput" style="width:30px;" value="<?=default_set($pList['list_num'],15)?>" /> 개</td>
								<th>페이지 블럭수</th>
								<td><input type="text" name="block_num" class="dInput" style="width:30px;" value="<?=default_set($pList['block_num'],10)?>" /> 개</td>
							</tr>
							<tr>
								<th>제목 글자수</th>
								<td><input type="text" name="title_num" class="dInput" style="width:30px;" value="<?=default_set($pList['title_num'],50)?>" /> 자</td>
								<th>첨부파일 수</th>
								<td><input type="text" name="file_num" class="dInput" style="width:30px;" value="<?=default_set($pList['file_num'],1)?>" /> 개</td>
							</tr>
							<tr>
								<th></th>
								<td></td>
								<th>링크 수</th>
								<td><input type="text" name="link_num" class="dInput" style="width:30px;" value="<?=default_set($pList['link_num'],1)?>" /> 개</td>
							</tr>
						</table>

						<h2>♦ 게시판 기능 설정 ♦</h2>
						<table>
							<colgroup>
							<col width="15%">
							<col width="35%">
							<col width="15%">
							<col width="35%">
							</colgroup>
							<tr id="pform2" style="display:<?=($pList['b_type']==2 ? "":"none")?>">
								<th>썸네일생성</th>
								<td colspan="3">
									<ul style="line-height:20px;">
										<li><input type="checkbox" name="thumbnail" value="Y" <?=($pList['thumbnail']=="Y" ? "checked" : "")?> /> 생성</li>
										<li>가로: <input type="text" name="thumbwidth" title="가로" class="dInput" style="width:30px" value="<?=($thumbwidth ? $thumbwidth:"150")?>" /> / 세로: <input type="text" name="thumbheight" title="세로" class="dInput" style="width:30px" value="<?=($thumbheight?$thumbheight:130)?>" /></li>
										<li><input type="radio" name="thumbtype" value="0" <?=($pList['thumbtype']=="" || $pList['thumbtype']=="0" ? "checked" : "")?> />비율무시+고정사이즈 <input type="radio" name="thumbtype" value="1" <?=($pList['thumbtype']=="1" ? "checked" : "")?> />비율반영+고정사이즈여백 <input type="radio" name="thumbtype" value="2" <?=($pList['thumbtype']=="2" ? "checked" : "")?> />비율반영+고정사이즈컷</li>
									</ul>
								</td>
							</tr>
							<tr id="pform3" style="display:<?=($pList['b_type']==3 ? "":"none")?>">
								<th>항목추가</th>
								<td colspan="3">
									<input type="checkbox" name="form[]" value="addr" <?=(strpos($pList['form'],"addr")!==false ? "checked" : "")?> /> 주소 &nbsp;
									<input type="checkbox" name="form[]" value="company" <?=(strpos($pList['form'],"company")!==false ? "checked" : "")?> /> 회사 &nbsp;
									<input type="checkbox" name="form[]" value="part" <?=(strpos($pList['form'],"part")!==false ? "checked" : "")?> /> 부서 &nbsp;
									<input type="checkbox" name="form[]" value="filename" <?=(strpos($pList['form'],"filename")!==false ? "checked" : "")?> /> 첨부파일
								</td>
							</tr>
							<tr>
								<th>카테고리</th>
								<td colspan="3"><input type="checkbox" name="category_yn" value="Y" <?=($pList['category_yn']=="Y" ? "checked" : "")?> /> 사용 &nbsp;&nbsp;<input type="text" name="category" class="dInput" style="width:60%;" value="<?=$pList['category']?>" /> <span class="help">분류는 | 기호로 구분 (예:공지|질문|답변)</span></td>
							</tr>
							<tr>
								<th>에디터사용</th>
								<td><input type="checkbox" name="editor" value="Y" <?=($pList['editor']=="Y" ? "checked" : "")?> /> 사용</td>
								<th>자료실사용</th>
								<td><input type="checkbox" name="fileuse" value="Y" <?=($pList['fileuse']=="Y" ? "checked" : "")?> /> 사용</td>
							</tr>
							<tr>
								<th>답글기능</th>
								<td><input type="checkbox" name="reply" value="Y" <?=($pList['reply']=="Y" ? "checked" : "")?> /> 사용</td>
								<th>코멘트기능</th>
								<td><input type="checkbox" name="comment" value="Y" <?=($pList['comment']=="Y" ? "checked" : "")?> /> 사용</td>
							</tr>
							<tr>
								<th>비밀글</th>
								<td><input type="checkbox" name="secret" value="Y" <?=($pList['secret']=="Y" ? "checked" : "")?> /> 사용</td>
								<th>보기리스트노출</th>
								<td><input type="checkbox" name="viewlist" value="Y" <?=($pList['viewlist']=="Y" ? "checked" : "")?> /> 사용</td>
							</tr>
							<tr>
								<th></th>
								<td></td>
								<th>링크사용</th>
								<td><input type="checkbox" name="linkuse" value="Y" <?=($pList['linkuse']=="Y" ? "checked" : "")?> /> 사용</td>
							</tr>
						</table>

						<h2>♦ 게시판 권한 설정 ♦</h2>
						<table>
							<colgroup>
							<col width="15%">
							<col width="*">
							</colgroup>
							<tr>
								<th>사용권한</th>
								<td colspan="3">
									<div class="auth_table">
										<table>
											<tr>
												<th>목록보기</th>
											</tr>
											<tr>
												<td class="margin_bot">
												<?
													$mRs=db_query("select * from tbl_member_level order by idx");
													while($mList=db_fetch($mRs)){
														$val_arr=explode("|",$pList['auth_list']);
														$sel=(in_array($mList['level_code'], $val_arr) ? "checked" : "");
														echo "<input type=\"checkbox\" name=\"auth_list[]\" value=\"".$mList['level_code']."\" $sel> ".($mList['level_code']==0 ? "<label style=\"color:#C01258\" />제한없음</label>" : $mList['level_name'])."<br />";
													}
												?>
												</td>
											</tr>
										</table>
									</div>
									<div class="auth_table">
										<table>
											<tr>
												<th>내용보기</th>
											</tr>
											<tr>
												<td class="margin_bot">
												<?
													$mRs=db_query("select * from tbl_member_level order by idx");
													while($mList=db_fetch($mRs)){
														$val_arr=explode("|",$pList['auth_view']);
														$sel=(in_array($mList['level_code'], $val_arr) ? "checked" : "");
														echo "<input type=\"checkbox\" name=\"auth_view[]\" value=\"".$mList['level_code']."\" $sel> ".($mList['level_code']==0 ? "<label style=\"color:#C01258\" />제한없음</label>" : $mList['level_name'])."<br />";
													}
												?>
												</td>
											</tr>
										</table>
									</div>
									<div class="auth_table">
										<table>
											<tr>
												<th>글쓰기</th>
											</tr>
											<tr>
												<td class="margin_bot">
												<?
													$mRs=db_query("select * from tbl_member_level order by idx");
													while($mList=db_fetch($mRs)){
														$val_arr=explode("|",$pList['auth_write']);
														$sel=(in_array($mList['level_code'], $val_arr) ? "checked" : "");
														echo "<input type=\"checkbox\" name=\"auth_write[]\" value=\"".$mList['level_code']."\" $sel> ".($mList['level_code']==0 ? "<label style=\"color:#C01258\" />제한없음</label>" : $mList['level_name'])."<br />";
													}
												?>
												</td>
											</tr>
										</table>
									</div>
									<div class="auth_table">
										<table>
											<tr>
												<th>답변쓰기</th>
											</tr>
											<tr>
												<td class="margin_bot">
												<?
													$mRs=db_query("select * from tbl_member_level order by idx");
													while($mList=db_fetch($mRs)){
														$val_arr=explode("|",$pList['auth_reply']);
														$sel=(in_array($mList['level_code'], $val_arr) ? "checked" : "");
														echo "<input type=\"checkbox\" name=\"auth_reply[]\" value=\"".$mList['level_code']."\" $sel> ".($mList['level_code']==0 ? "<label style=\"color:#C01258\" />제한없음</label>" : $mList['level_name'])."<br />";
													}
												?>
												</td>
											</tr>
										</table>
									</div>
									<div class="auth_table">
										<table>
											<tr>
												<th>코멘트 쓰기</th>
											</tr>
											<tr>
												<td class="margin_bot">
												<?
													$mRs=db_query("select * from tbl_member_level order by idx");
													while($mList=db_fetch($mRs)){
														$val_arr=explode("|",$pList['auth_comment']);
														$sel=(in_array($mList['level_code'], $val_arr) ? "checked" : "");
														echo "<input type=\"checkbox\" name=\"auth_comment[]\" value=\"".$mList['level_code']."\" $sel> ".($mList['level_code']==0 ? "<label style=\"color:#C01258\" />제한없음</label>" : $mList['level_name'])."<br />";
													}
												?>
												</td>
											</tr>
										</table>
									</div>
								</td>
							</tr>
						</table>
					</div>

					<!-- Button -->
					<div id="btn_bott">
						<span class="button blue"><input type="submit" value="저장" /></span>
						<span class="button black"><a href="board_list.php?page=<?=$page?>&skey=<?=$skey?>&sval=<?=$sval?>">목록</a></span>
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