			
			<!-- container s-->
			<div id="container" class="sub board list">
				<h2><span>게시판<em>짠순이 회원님들이 자유로이 커뮤니케이션 하실 수 있는 공간입니다.</em></span></h2>
				<div id="category">
					<ul>
						<li class='<?=$b_code==""?"active":""?>'><a href='?b_id=<?=$b_id?>&b_code='>전체</a></li>
								<?
									if($bbs_category_yn=="Y"){
								?>
									<?
										$cateArr=@explode("|",$bbs_category);
										foreach($cateArr as $ckey=>$cval){
											$sel=((string)$b_code==(string)$cval ? "active" : "");
											echo "<li class='$sel'><a href='?b_id=$b_id&b_code=$cval'>$cval</a></li>";
										}
									?>
								<?
									}
								?>
					</ul>
				</div>
				<div id="list">
						<form name="board" id="board" method="post" action="board_pro.php">
						<input type="hidden" name="mode" value="" />
						<input type="hidden" name="b_id" value="<?=$b_id?>" />
						<input type="hidden" name="b_code" value="<?=$b_code?>" />
					<table>
						<colgroup><col width="5%"><col width="5%"><col width="*"><col width="10%"><col width="15%"><col width="10%"></colgroup>
						<tbody>
							<tr>
								<th>No.</th>
<!-- 								<th>공지</th> -->
								<th>카테고리</th>
								<th>제목</th>
								<th>글쓴이</th>
								<th>날짜</th>
								<th>조회수</th>
							</tr>
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
								#$sortnum=($notice=='Y' ? "<img src=\"".$skin_img."/icon_notice.gif\" />" : $sortnum);
								$b_code_name=($notice=='Y' ? "공지" : "");

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
									$reply_icon="<span class='reply'><img src='/images/ico_reply.png'></span>";
								}else{
									$reply_icon="";
								}

								//글제목
								if($bbs_secret=="Y" && $secret=="Y"){ //비밀글일때
									$secret_icon="<img src=\"/images/icon_lock.gif\" />";
								}else{
									$secret_icon="";
								}

								$param = "b_id=$b_id&idx=$idx&b_code=$pList[b_code]&page=$page&search_key=$search_key&search_val=$search_val&mode=view";

								$subject=$reply_icon.$secret_icon." <a href=\"board_view.php?$param\">".$title."</a> ".$comment_icon.$new_icon;
								$url="&b_id=$b_id".($pList['b_code'] ? "&b_code=$pList[b_code]" : "");
						?>
							<tr>
								<td><?=$sortnum?></td>
<!-- 								<td class="<?=$notice=='Y'?"notice":""?>"><?=$b_code_name?></td> -->
								<td><?=$pList[b_code]?></td>
								<td style="text-align:left;padding-left:10px;"><?=$subject?></td>
								<td><?=$name?></td>
								<td><?=$regdate?></td>
								<td><?=$hit?></td>
							</tr>
						<?
								$i++;
							}

							if($i==0){
						?>
							<tr>
								<td colspan="7" >등록된 내용이 없습니다.</td>
							</tr>
						<?
							}
						?>
						</tbody>
					</table>
						</form>
					<div class="btnWrite"><a href="board_write.php?b_id=<?=$b_id?>&b_code=<?=$b_code?>&mode=write&page=<?=$page?>">글쓰기</a></div>
<!-- 					<div class="btnWrite"><a href="#" onclick="check_del(document.board,'idx[]');return false;">선택삭제</a></div> -->
					<div class="paging">
							<? page_list($page, $count, $list_num, $block_num, $url) ?>
					</div>
					<div class="search">
						<form name="search" id="search" method="get">
						<input type="hidden" name="b_id" value="<?=$b_id?>" />
						<input type="hidden" name="b_code" value="<?=$b_code?>" />
						<span>
							<select name="search_key" id="search_key">
								<option value="title" <?=($search_key=="title" ? " selected":"")?>>제목</option>
								<option value="content" <?=($search_key=="content" ? " selected":"")?>>내용</option>
								<option value="name" <?=($search_key=="name" ? " selected":"")?>>이름</option>
							</select>
						</span>
						<span><input type="text" name="search_val" value="<?=$search_val?>"/><em><a href="javascript:;" onclick="document.search.submit();"><img src="/images/main_event_search.jpg" alt="검색"></a></em></span>
						</form>
					</div>
				</div>
			</div>
			<!-- container e-->
			

