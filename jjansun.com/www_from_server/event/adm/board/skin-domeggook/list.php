<?
//	$sdate= date('Y-m-d');

	$list_num=300;
	$startnum=($page-1)*$list_num;

	$from= "
		from (
			select idx, etc1, reg_date, title, tag, etc2, etc3, etc4 from tbl_board where b_id='".$b_id."' $search_keyword and reg_dates=left('$sdate',10)
		) a left join (
			select idx, etc1, reg_date, title, tag, etc2, etc3, etc4 from tbl_board where b_id='".$b_id."' $search_keyword and reg_dates=left(DATE_ADD('$sdate',interval -1 day),10)
		) b
		on a.etc1=b.etc1
	";

	$where= "where b.etc1 is null ";

	$count=db_result("select count(a.idx) $from $where");
//	$sql= "select a.*
//			, (select etc4 old_etc4 from tbl_board aa where b_id='".$b_id."' and aa.reg_dates=left(now(),10) and aa.etc1=a.etc1)
//		from tbl_board a $where order by notice desc, ref desc, re_step desc, idx desc limit $startnum, $list_num";
	$sql = "
		select a.*, (select count(1) from tbl_board aa where b_id='019' and aa.etc1=a.etc1) moved $from $where order by 3 desc
	";
	$pRs=db_query($sql);
	$totalpage  = ceil($count / $list_num);

?>
<script language="javascript">
	$(document).ready(function(){
		$(".sdate").datepicker({dateFormat:'yy-mm-dd'});
		$(".edate").datepicker({dateFormat:'yy-mm-dd'});
	});
</script>
<script src="http://code.jquery.com/ui/1.10.4/jquery-ui.min.js"></script>
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
									<select name="search_key" class="dSelect">
										<option value="title"<?=($search_key=="title" ? " selected":"")?>>제목</option>
										<option value="content"<?=($search_key=="content" ? " selected":"")?>>내용</option>
										<option value="name"<?=($search_key=="name" ? " selected":"")?>>작성자</option>
									</select>
											<input type="text" name="sdate" class='dInput sdate' value='<?=$sdate?>'/> ~
											<input type="text" name="edate" class='dInput edate' value='<?=$edate?>'/>
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

					<div style="padding:10px 0;">
						<span class="help">제목은 본문의 100자 까지만 표시됩니다.</span>
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
								<th width="3%">번호</th>
								<th width="8%">작성일자</th>
								<th width="8%">cate</th>
								<th width="8%">code</th>
								<th width="8%">img</th>
								<th width="8%">price</th>
								<th width="8%">moved</th>
								<th>제목</th>
								<th width="15%">관리</th>
							</tr>
							</thead>
							<tbody>
						<?
							$i=0;
							while($pList=db_fetch($pRs)){
								$num = ($page-1) * $list_num + $i;
								$sortnum = $count-$num;

								$idx=$pList['idx'];
								$title=cutstr($pList['title'],100);
								$notice=$pList['notice'];
								$secret=$pList['secret'];
								$re_level=$pList['re_level'];
								$comment=$pList['comment'];


								{
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
								}
						?>
							<tr>
								<td><input name="idx[]" type="checkbox" value="<?=$pList['idx']?>" /></td>
								<td><?=$sortnum?></td>
								<td><?=$pList['reg_date']?></td>
								<td><a href='https://domemedb.domeggook.com/index/item/supplyList.php?sf=no&enc=utf8&fromOversea=0&mode=search&sw=<?=$pList['etc1']?>' target="_blank"><?=$pList['etc1']?></a></td>
								<td><?=substr($pList['tag'],-5)?></td>
								<td id='drag' dragable='true'><img src='<?=$pList['etc2']?>' width=100></td>
								<td><?=$pList['etc3']?></td>
								<td><?=$pList['moved']?></td>
								<td style="text-align:left;padding-left:10px;"><?=$pList['title']?> [<a href='https://search.shopping.naver.com/search/all?query=<?=$pList['title']?>' target="_blank">LINK</a>]</td>
								<td>
									<span class="button"><a href="board_pro.php?<?=$param?>&mode=copyto&idx=<?=$pList['idx']?>"  target="iframe">OK로</a></span>
									<span class="button"><a href="board_view.php?<?=$param?>&mode=modify">보기</a></span>
									<span class="button"><a href="board_write.php?<?=$param?>&mode=modify">수정</a></span>
									<span class="button"><a href="board_pro.php?<?=$param?>&mode=del&idx=<?=$pList['idx']?>" onclick="return really()">삭제</a></span>
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
<script type="text/javascript">
<!--
    $(function(){
        $('#drag').on({
            //드래그 시작시 요소 id 저장
            'dragstart':function(e){
                e.originalEvent.dataTransfer.setData('text',e.target.id);
                console.log('drag', 'start');
            },
            //드래그 종료
            'dragend':function(e){
				console.log('drag', 'end');
            }
        });
        $('#drop').on({
            'dragenter':function(e){
                console.log('drag', 'enter');
            },
            'dragleave':function(e){
				console.log('drag', 'level');
            },
            //브라우저 표중 동작 취소
            'dragover':function(e){
                e.preventDefault();
            },
            'drop':function(e){
                $(e.target).append($('#'+e.originalEvent.dataTransfer.getData('text')));
                e.preventDefault();
				console.log('drag', 'drop');
            }
        });
    });
//-->
</script>

<iframe name="iframe" id="iframe"></iframe>
</body>
</html>