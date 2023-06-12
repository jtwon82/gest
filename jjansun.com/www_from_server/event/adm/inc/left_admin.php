
<div class="left_menu">
	<ul>
<?if($_SESSION[LOGIN_LEVEL]<=100){?>
		<li class='manager'>
			<p class="depth1S"><a href="../manager/event_winner_cnt.php">운영관리</a></p>
			<ul class="depth2">
				<li><a href="../manager/event_winner_cnt.php">ㆍ일자별당첨자</a></li>
				<li><a href="../manager/event_winner_gift.php">ㆍ경품관리</a></li>
				<li><a href="../manager/event_winner_saltboom.php">ㆍ솔트두배</a></li>
				<li><a href="../manager/popup_list.php">ㆍ팝업관리</a></li>
				<li><a href="../manager/banner_list.php">ㆍ배너</a></li>
				<li><a href="../manager/site_info.php">ㆍ사이트정보관리</a></li>
				<li><a href="../manager/rec_main.php" >ㆍ코드관리</a></li>
			</ul>
		</li>
		<li class="member ">
			<p class="depth1S"><a href="../member/member_list.php">회원관리</a></p>
			<ul class="depth2">
				<li><a href="../member/level_list.php">ㆍ권한관리</a></li>
				<li><a href="../member/member_list.php">ㆍ회원목록</a></li>
				<li><a href="../member/out_list.php">ㆍ탈퇴회원관리</a></li>
			</ul>
		</li>
<?}?>
		<li class="contract ">
			<p class="depth1S"><a href="../contract/contract_list.php">응모내역</a></p>
			<ul class="depth2">
				<li><a href="../contract/contract_list.php">ㆍ응모내역</a></li>
				<li><a href="../contract/contract_list_game13.php">ㆍ선착순1000</a></li>
			</ul>
		</li>
		<li class="count">
			<p class="depth1"><a href="../count/stats_byday.php">일자통계</a></p>
			<ul class="depth2">
				<li><a href="../count/stats_byday.php">ㆍ 일자통계</a></li>
				<li><a href="../count/stats_weekwinner.php">ㆍ 주간랭킹</a></li>
				<li><a href="../count/coupangurl_list.php">ㆍ 쿠팡URL</a></li>
				<li><a href="../count/pno_list.php">ㆍ 전화번호 통계</a></li>
				<li><a href="../count/path_list.php">ㆍ 검색엔진별 통계</a></li>
				<li><a href="../count/period_list.php">ㆍ 기간별 통계</a></li>
			</ul>
		</li>
		<li class="contents ">
			<p class="depth1S"><a href="../contents/content_list.php">컨텐츠관리</a></p>
			<ul class="depth2">
				<li><a href="../contents/content_list.php">ㆍ컨텐츠 목록</a></li>
				<li><a href="../contents/content_dom_list.php">ㆍ컨텐츠 자동수집</a></li>
			</ul>
		</li>
		<li class="board ">
			<p class="depth1"><a href="../board/board_list.php?b_id=<?=db_result("select b_id from tbl_board_config order by idx")?>">게시판관리</a></p>
			<ul class="depth2">
			<?
				$bRs=db_query("select * from tbl_board_config order by idx");
				while($bList=db_fetch($bRs)){
					$list_type=($bList['b_type']==3 ? "online" : "board");
			?>
				<li><a href="../board/<?=$list_type?>_list.php?b_id=<?=$bList['b_id']?>">ㆍ<?=$bList['b_name']?></a></li>
			<?
				}
			?>
			</ul>
		</li>
		<li class="builder ">
			<p class="depth1"><a href="../builder/board_list.php">빌더관리</a></p>
			<ul class="depth2">
				<li><a href="../builder/board_list.php">ㆍBUILDER</a></li>
			</ul>
		</li>
	</ul>
</div>