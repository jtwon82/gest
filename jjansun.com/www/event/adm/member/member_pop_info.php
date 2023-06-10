<?
	include("../inc/head.php");

	if($_GET['idx']){
		$pList=db_select("select * from tbl_member where idx='".$_GET['idx']."'");
		list($email1, $email2)=explode("@",$pList['email']);
	}
?>

<body id="pop_body">
<div id="adm_wrap">
	<!-- content -->
	<table style="width:100%;">
		<tr>
			<td id="pop_area">
				<h1>회원상세정보</h1>

				<div style="border-bottom:1px solid #d0d0d0;margin:0 10px 20px 10px;padding:0 0 5px 10px;font-size:16px;font-weight:bold;">
					<p><span style="color:#c01258;"><?=$pList['name']?>(<?=$pList['userid']?>)</span> 님 회원정보</p>
				</div>

				<!-- 본문 -->
				<div class="content">

					<div class="tableStyle2">
					<form name="member" id="member" method="post" action="member_pro.php" onSubmit="joinCheck();return false;">
					<input type="hidden" name="mode" value="<?=($_GET['idx'] ? "modify" : "write")?>" />
					<input type="hidden" name="idx" value="<?=$_GET['idx']?>" />
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
								<td><?=$pList['name']?></td>
								<th>아이디</th>
								<td><?=$pList['userid']?></td>
							</tr>
							<tr>
								<th>이메일</th>
								<td><?=$pList['email']?>
								<th>회원등급</th>
								<td>
									<?
										$mList=db_select("select * from tbl_member_level where level_code='".$pList['member_level']."'");
										echo $mList['level_name'];
									?>
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
								<td colspan="3"><?=$pList['title']?></td>
							</tr>
							<tr>
								<th>지역</th>
								<td><?=$pList['area']?></td>
								<th>학력</th>
								<td><?=$pList['education']?></td>
							</tr>
							<tr>
								<th>직업</th>
								<td><?=$pList['job']?></td>
								<th>직장</th>
								<td><?=$pList['company']?></td>
							</tr>
							<tr>
								<th>페이스북</th>
								<td><?=$pList['facebook']?></td>
								<th>트위터</th>
								<td><?=$pList['twitter']?></td>
							</tr>
							<tr>
								<th>내용</th>
								<td colspan="3"><?=$pList['content']?></td>
							</tr>
						</table>


						<h2>♦ 커리어 패스 ♦</h2>
						<table>
							<colgroup>
							<col width="100%">
							</colgroup>
						<?
							if($pList['careerpath1']==""){
						?>
							<tr>
								<td colspan="4" class="center emp3">미설정</td>
							</tr>
						<?
							}else{
						?>
							<tr>
								<td class="center emp3" height="50">
									<?=$careerPathArray1[$pList['careerpath1']]?> ►
									<?=$careerPathArray2[$pList['careerpath2']]?> ►
									<?=$careerPathArray3[$pList['careerpath3']]?> ►
									<?=$careerPathArray4[$pList['careerpath4']]?> ►
									<?=$careerPathArray5[$pList['careerpath5']]?> ►
									<?=$careerPathArray6[$pList['careerpath6']]?>
								</td>
							</tr>
						<?
							}
						?>
								</td>
							</tr>
						</table>

						<h2>♦ 회원 이용 정보 ♦</h2>
						<table>
							<colgroup>
							<col width="15%">
							<col width="35%">
							<col width="15%">
							<col width="35%">
							</colgroup>
							<tr>
								<th>가입일자</th>
								<td><?=date("Y-m-d H:i:s",$pList['regdate'])?> <p class="emp3">최근접속시간:<?=($pList['visit_time'] ? date("Y-m-d H:i:s",$pList['visit_time']) : "")?></p></td>
								<th>방문횟수</th>
								<td><?=$pList['visit_num']?>회 <p class="emp3">접속IP:<?=$pList['visit_ip']?></p></td>
							</tr>
						</table>
					</form>
					</div>

					<p style="margin-top:15px;text-align:center;"><span class="button black"><input type="button" value="닫기" onclick="self.close();" /></span></p>

				</div>
				<!-- end -->
			</td>
		</tr>
	</table>
</div>
</body>
</html>
