<?php
/**
 * 固定ページテンプレート
 *
 * @package WordPress
 * @subpackage Trais Template
 * @since Trais Template
 */
 // ルート階層
global $_hpRootDir,$NowLanguage;

global $post;

// 参照渡しで埋めるパン屑用配列を初期化
$root_page = array();

 //	パン屑リスト取得
if( is_page() ){
	ps_get_root_page( $post , $root_page );
}


$_masterPage = $root_page[count( $root_page )-1];

//	共通ヘッダー読込み
get_header( $NowLanguage['load'] );
?>
	<div class="mainWrapp bgColorGl">
		<div id="bodies" class="autoWrapp">
			<div id="breadcrumb" class="bgColorGl">
				<ul>
					<li class="next"><a href="<?php echo home_url('/'); ?>">Home</a></li>
					<li>search</li>
				</ul>
			</div>
		</div>
	</div>


	<div id="contentsWrapper" class="mainWrapp">
		<div class="autoWrapp _bgColorGl">
<script>
  (function() {
    var cx = '005078379537134023689:h70-hh20rbw';
    var gcse = document.createElement('script');
    gcse.type = 'text/javascript';
    gcse.async = true;
    gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +
        '//www.google.com/cse/cse.js?cx=' + cx;
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(gcse, s);
  })();
</script>
<gcse:searchresults-only></gcse:searchresults-only>

		</div>
	</div>


<?php get_footer($NowLanguage['load']); ?>