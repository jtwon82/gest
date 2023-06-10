<?
	include("../inc/head.php");
	include("./board_config.php");

	$page=anti_injection($_GET['page']);
	$search_key=anti_injection($_GET['search_key']);
	$search_val=anti_injection($_GET['search_val']);
	$search_key2=anti_injection($_GET['search_key2']);
	$search_val2=anti_injection($_GET['search_val2']);
	$search_key3=anti_injection($_GET['search_key3']);
	$search_val3=anti_injection($_GET['search_val3']);
	$search_key4=anti_injection($_GET['search_key4']);
	$search_val4=anti_injection($_GET['search_val4']);
	$b_code=anti_injection($_GET['b_code']);

	$sdate=anti_injection($_GET['sdate']);
	$edate=anti_injection($_GET['edate']);
	if(!$sdate) $sdate= date('Y-m-d', strtotime('-0 day'));
	if(!$edate) $edate= date('Y-m-d');

	if($search_key=='etc3'){
		if($search_key && $search_val) $search_keyword.=" and $search_key > $search_val";
	} else {
		if($search_key && $search_val) $search_keyword.=" and $search_key like '%$search_val%'";
	}

	if($search_key2 && $search_val2) $search_keyword.=" and $search_key2 like '%$search_val2%'";
	if($search_key3 && $search_val3) $search_keyword.=" and $search_key3 like '%$search_val3%'";
	if($search_key4 && $search_val4) $search_keyword.=" and $search_key4 like '%$search_val4%'";
	if($b_code!="") $search_keyword.=" and b_code = '$b_code'";

	$search_keyword.=" and delete_date is null ";

	$param= "&b_id=$b_id&b_code=$b_code&idx=$idx&page=$page&search_key=$search_key&search_val=$search_val";

	if(!$page) $page=1;
	$list_num=200;
	$startnum=($page-1)*$list_num;

	$count=db_result("select count(idx) from tbl_board where b_id='".$b_id."' $search_keyword");
	$sql= "select * from tbl_board where b_id='".$b_id."' $search_keyword order by notice desc, ref desc, re_step desc, idx desc limit $startnum, $list_num";
	#echo $sql;
	$pRs=db_query($sql);
	$totalpage  = ceil($count / $list_num);

	if($bbs_skin){
		include "./".$bbs_skin."/list.php";
		exit;
	}
?>
<body>
<div id="adm_wrap">
	<!-- content -->
	<table style="width:100%;">
		<tr>
			<td id="left_area">
				<!-- 좌측메뉴 -->
				<? include "../inc/left_{$_SESSION[LOGIN_ID]}.php"; ?>
			</td>
			<td id="content_area">
				<h1><?=$bbs_name?></h1>
				<!-- 본문 -->
				<div class="content">

					<div class="tableSearch">
						<form name="search" method="get">
						<input type="hidden" name="b_id" value="<?=$b_id?>" />
						<table>
							<tr>
								<th width="10%">검색</th>
								<td>
								<?
									if($bbs_category_yn=="Y"){
								?>
									<select name="b_code" class="dSelect">
										<option value="">선택</option>
									<?
										$cateArr=@explode("|",$bbs_category);
										foreach($cateArr as $ckey=>$cval){
											$sel=((string)$b_code==(string)$ckey ? "selected" : "");
											echo "<option value=\"$ckey\" $sel>$cval</option>\n";
										}
									?>
									</select>
								<?
									}
								?>
									<select name="search_key" class="dSelect">
										<option value="title"<?=($search_key=="title" ? " selected":"")?>>제목</option>
										<option value="content"<?=($search_key=="content" ? " selected":"")?>>내용</option>
										<option value="name"<?=($search_key=="name" ? " selected":"")?>>작성자</option>
									</select>
									<input type="text" name="search_val" id="search_val" class="dInput req" title="검색어" value="<?=$search_val?>" style="width:100px;" />
									<span class="button red"><input type="submit" value="검색결과보기" /></span>
									<span class="button"><input type="button" value="전체보기" onclick="location.href='board_list.php?b_id=<?=$b_id?>';" /></span>
								</td>
							</tr>
						</table>
						</form>
					</div>

					<!-- Button -->
					<div id="btn_top">
						<span class="button blue"><a href="board_write.php?b_id=<?=$b_id?>&b_code=<?=$b_code?>&mode=write&page=<?=$page?>">글쓰기</a></span>
						<span class="button black"><a href="#" onclick="check_del(document.board,'idx[]');return false;">선택삭제</a></span>
					</div>

					<!-- 리스트 -->
					<div class="tableStyle1">
					<form name="board" id="board" method="post" action="board_pro.php">
					<input type="hidden" name="mode" value="" />
					<input type="hidden" name="b_id" value="<?=$b_id?>" />
					<input type="hidden" name="b_code" value="<?=$b_code?>" />
						<table>
							<thead>
							<tr>
								<th width="3%"><input type="checkbox" style="border:0" onClick="check_all(this,'idx[]')"></th>
								<th width="5%">번호</th>
								<th width="11%">작성일자</th>
								<th width="8%">작성자</th>
								<th>제목</th>
								<th width="11%">삭제날짜</th>
								<th width="11%">관리</th>
							</tr>
							</thead>
							<tbody>
						<?
							$i=0;
							while($pList=db_fetch($pRs)){
								$num = ($page-1) * $list_num + $i;
								$sortnum = $count-$num;

								$idx=$pList['idx'];
								$name=$pList['name'];
								$title=cutstr($pList['title'],110);
								$hit=$pList['hit'];
								$notice=$pList['notice'];
								$secret=$pList['secret'];
								$re_level=$pList['re_level'];
								$comment=$pList['comment'];
								$regdate=$pList['reg_date'];

								//공지글일 경우
								$sortnum=($notice=='Y' && $re_level==0 ? "Notice" : $sortnum);

								//첨부파일
								if($bbs_fileuse=="Y"){
									$fileCnt=db_result("select filename from tbl_board_file where b_idx='".$idx."'");
									$file_icon=($fileCnt>0 ? "<img src=\"".$skin_img."/bt_file.gif\" />" : "");
								}

								//코멘트
								if($bbs_comment=="Y" && $comment>0){
									$comment_icon="<span style=\"color:#500824;\">[".$comment."]</span>";
								}else{
									$comment_icon="";
								}

								//리플 아이콘
								if($re_level>0){
									$reply_padding=$re_level*7;
									$reply_icon="<span style=\"padding-left:".$reply_padding."px;\">↘</span>";
								}else{
									$reply_icon="";
								}

								//글제목
								if($bbs_secret=="Y" && $secret=="Y"){ //비밀글일때
									$secret_icon="<img src=\"../images/icon_lock.gif\" />";
								}else{
									$secret_icon="";
								}

								$subject=$reply_icon.$secret_icon." <a href=\"board_view.php?b_id=$b_id&idx=$idx&b_code=$b_code&page=$page&search_key=$search_key&search_val=$search_val&mode=view\">".$title."</a> ".$comment_icon.$new_icon;
								$url="&b_id=$b_id".($pList['b_code'] ? "&b_code=$pList[b_code]" : "");
						?>
							<tr>
								<td><input name="idx[]" type="checkbox" value="<?=$pList['idx']?>" /></td>
								<td><?=$sortnum?></td>
								<td><?=$regdate?></td>
								<td><?=$name?></td>
								<td style="text-align:left;padding-left:10px;"><?=$subject?></td>
								<td><?=$pList[delete_date]?></td>
								<td>
									<span class="button"><a href="board_write.php?b_id=<?=$b_id?>&b_code=<?=$b_code?>&idx=<?=$idx?>&mode=modify&page=<?=$page?>&search_key=<?=$search_key?>&search_val=<?=$search_val?>">수정</a></span>
									<span class="button"><a href="board_pro.php?mode=del&b_id=<?=$b_id?>&b_code=<?=$b_code?>&idx=<?=$pList['idx']?>" onclick="return really()">삭제</a></span>
								</td>
							</tr>
						<?
								$i++;
							}

							if($i==0){
						?>
							<tr>
								<td colspan="7">등록된 내용이 없습니다.</td>
							</tr>
						<?
							}
						?>
							</tbody>
						</table>
					</form>
					</div>

					<!-- paging -->
					<div id="paging">
						<p>
							<? page_list($page, $count, $list_num, $block_num, $url) ?>
						</p>
					</div>
				</div>
				<!-- end -->
			</td>
		</tr>
	</table>
</div>
</body>
</html>