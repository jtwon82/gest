			
			<!-- container s-->
			<div id="container" class="sub board list">
				<h2><span>즉석당첨<em>많은분들이 실시간 즉석당첨에 당첨되고 있습니다.</em></span></h2>
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
				<div id="list" class="thumb_news">
						<form name="board" id="board" method="post" action="board_pro.php">
						<input type="hidden" name="mode" value="" />
						<input type="hidden" name="b_id" value="<?=$b_id?>" />
						<input type="hidden" name="b_code" value="<?=$b_code?>" />
					<ul class="add_item_wrap">
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
									$files='';
									for($filei=0; $filei<$file_num; $filei++){
										$ff=db_select("select * from tbl_board_file where b_idx='".$pList['idx']."' and sortnum='".($filei+1)."'");
										if($ff['filename']){
											$files[] = "<img src='/data/filestream.php?Path=bbs/$b_id&File=$ff[filename]' width=100>";
										}
									}
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
									$secret_icon="<img src=\"/images/icon_lock.gif\" />";
								}else{
									$secret_icon="";
								}

								$param = "b_id=$b_id&idx=$idx&b_code=$pList[b_code]&page=$page&search_key=$search_key&search_val=$search_val&mode=view";

								$href = "board_view.php?$param";
								$subject = $reply_icon.$secret_icon." <a href=\"$href\">".$title."</a> ".$comment_icon.$new_icon;
								$url = "&b_id=$b_id".($pList['b_code'] ? "&b_code=$pList[b_code]" : "");
						?>
                        <li>
                            <div class="img"><a href="<?=$href?>"><?=$files[0]?></a></div>
                            <div class="txt_wrap">
                                <a href="<?=$pList[link]?>">
                                    <span class="tt">
                                        <div class="txt_dot1"><?=$subject?></div>
                                    </span>
                                </a>
                                <span class="txt">
                                    <span class="txt_dot2">
								<!--	<?=$pList[content]?> -->
                                    </span>
                                </span>
                                <div class="info">
                                    <div class="left">
                                        <span class="name"><?=$name?></span>
                                        <span class="date"><?=$regdate?></span>
                                    </div>
                                    <div class="right">
                                        <ul class="hash_list">
                                            <li><a href="<?=$pList[link]?>" target="_blank" >당첨되기</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </li>
						<?
								$i++;
							}

							if($i==0){
						?>
                        <li>
                            <div class="img"></div>
                            <div class="txt_wrap">
                                등록된 내용이 없습니다.
                            </div>
                        </li>
						<?
							}
						?>
                    </ul>
						</tbody>
					</table>
						</form>
						<?
							if($_SESSION['USER'][LOGIN_LEVEL]==100){
						?>
							<div class="btnWrite"><a href="board_write.php?b_id=<?=$b_id?>&b_code=<?=$b_code?>&mode=write&page=<?=$page?>">글쓰기</a></div>
						<?
							}
						?>
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
			

