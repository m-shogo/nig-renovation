<?php
/**
 * 投稿ページテンプレート
 *
 * @package WordPress
 * @subpackage Trais Template
 * @since Trais Template
 */
 // ルート階層
global $_hpRootDir,$NowLanguage;

global $post; 

$sideMenu = 'none';

//	共通ヘッダー読込み
get_header( $NowLanguage['load'] );

?>
	<div class="mainWrapp bgColorGl">
		<div id="bodies" class="autoWrapp">
			<div id="breadcrumb" class="bgColorGl">
				<ul>
					<li><a href="<?php echo home_url('/'); ?>">Home</a></li>
				</ul>
			</div>
		</div>
	</div>


	<div id="contentsWrapper" class="mainWrapp">
		<div class="autoWrapp">
		
		
			<div id="leftSlot"></div>

		
			<div id="rightSlot">
				<h2>Notfound</h2>
				<div class="box">
	
				</div>
			</div>
			<br style="clear:both">
		</div>
	
	</div>

<?php get_footer($NowLanguage['load']); ?>