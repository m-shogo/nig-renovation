<?php
/**
 * 投稿ページテンプレート
 *
 * @package WordPress
 * @subpackage Trais Template
 * @since Trais Template
 */
//	共通ヘッダー読込み

global $NowLanguage;

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
		
			<div id="leftSlot">
				<h3>Search</h3>
			</div>
		
		<div id="rightSlot">
<?php
//	カテゴリ内容


// WordPress 本体の検索結果配列。未定義時は空配列として扱う
if ( isset( $posts ) && is_array( $posts )){
	foreach($posts as $post){
	
/*		//	サムネイル取得
		if(  has_post_thumbnail() ){
			 $thumnail = get_the_post_thumbnail( $post->ID , $thumbnailsize );
		}else{
			$thumnail = '';
		} */
		
		setup_postdata($post);
		$icon = get_post_meta($post->ID,'_custom_icon',true);
		if( $icon )	$icon = sprintf('<span class="icon"><img src="<?php echo $_hpRootDir; ?>images/index/icon_%s.gif" /></span>' , $icon);
	
	
	
?>
			<div class="box">
				<div class="date"><?php the_time('Y/m/d'); echo $icon;?></div>
				<h3><?php the_title(); ?></h3>
				<div class="texts"><?php the_content(); ?></div>
			</div>
<?php 
	}
}
?>
		<?php if(function_exists('wp_pagenavi')) {echo 'asd'; wp_pagenavi(); } ?>
			</div>
			<br style="clear:both">
		</div>
	
	</div>

<?php get_footer($NowLanguage['load']); ?>