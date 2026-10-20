<?php
//	埋め込み変数制御


global $page, $paged , $post;
// ルート階層
global $_hpRootDir;

//	タイトル設定 -----------------------------------
?>
	<div id="footWrap" class="bgGl">
		<div class="autoWrapp">
			<ul id="f_icons">
				<li><a href="https://twitter.com/NIG_idenken" target="_blank"><img src="<?php echo $_hpRootDir; ?>images/common/twitter-x-logo.png" alt="X" /></a></li>
				<li><a href="https://www.facebook.com/%E5%9B%BD%E7%AB%8B%E9%81%BA%E4%BC%9D%E5%AD%A6%E7%A0%94%E7%A9%B6%E6%89%80-242392782455048/" target="_blank"><img src="<?php echo $_hpRootDir; ?>images/common/fb_new.png" alt="facebook" /></a></li>
				<li><a href="https://www.youtube.com/user/NIGchannel" target="_blank"><img src="<?php echo $_hpRootDir; ?>images/common/youtube_new.png" alt="youtube" /></a></li>
			</ul>
			<div id="footer">
				<ul id="copyright">
					<li><a href="<?php echo home_url('/'); ?>/sitemap_ja/">サイトマップ</a></li>
					<li><a href="<?php echo home_url('/'); ?>/policy_ja/">サイトポリシー</a></li>
					<li><a href="<?php echo home_url('/'); ?>/about-nig/other_ja/">情報公開</a></li>
				</ul>
				<ul id="adress">
					<li>Copyright &copy; 国立遺伝学研究所</li>
				</ul>
			</div>
		</div>
	</div>
	
</div>

<?php wp_footer(); ?>

<!-- <script>
    //ローダー
    const loader = document.getElementById('js-loader');
    window.addEventListener('load', () => {
    const ms = 400;
    loader.style.transition = 'opacity ' + ms + 'ms';
    
    const loaderOpacity = function(){
        loader.style.opacity = 0;
    }
    const loaderDisplay = function(){
        loader.style.display = "none";
    }
    setTimeout(loaderOpacity, 1);
    setTimeout(loaderDisplay, ms);
    });
</script> -->
</body>


<script src="/nig/js/jquery.bxslider.js"></script>
<script>
$(document).ready(function(){
	$('#slideWrap2 #bxslider').bxSlider({
		auto: true,
		mode: 'fade'
	});
});
</script>


</html>