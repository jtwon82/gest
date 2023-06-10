<?
	//1차 메뉴 활성화
	$cateArray = array("manager","member","contract","contents","board","count","builder");
	$folder=explode("/",$_SERVER['PHP_SELF']);
	$selLeft=array_search($folder[3],$cateArray);
	
?>
<script type="text/javascript">
	$(function(){
		$(".left_menu>ul>li>p").removeClass("depth1S").addClass("depth1");
//		$(".left_menu>ul>li>p").eq("<?=$selLeft?>").addClass("depth1S");
		$(".left_menu>ul>li.<?=$folder[3]?>>p").addClass("depth1S");
		$(".left_menu>ul>li>ul").hide();
		$(".left_menu>ul ").find(".<?=$folder[3]?>").children("ul").show();
	});
	// 상단바 고정
	$(window).on('scroll', function() {
		var headerH = $('.left_menu').height();
		var scrollH = $(window).scrollTop();

		if (scrollH >= headerH) {
			$('.left_menu').addClass('fixed');
		} else {
			$('.left_menu').removeClass('fixed');
		}

		$('.left_menu').css('position', 'fixed');
//		$('.left_menu').css('top', 0);
	});
</script>

<h1 style='background:url(../images/top_bg2.gif)repeat-x; height:80px; text-align:center;'> <span style='position:absolute; top:30px; font-size:20px;'>Admin</span> </h1>
<p style="text-align: left;"><a href="../logout.php"><?=$_SESSION[LOGIN_ID]?></a></p>
<p style="text-align: right;"><a href="../logout.php"><img src="../images/btn_logout.gif" alt="로그아웃"></a></p>
<p style="text-align: center;"><img src="../images/left_menu_t.gif" /></p>
<? include "../inc/left_{$_SESSION[LOGIN_ID]}.php"; ?>
<p style="text-align: center;margin-bottom: 5px;"><img src="../images/left_menu_b.gif" /></p>