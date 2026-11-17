<?php
/**
 * 投稿ページテンプレート
 *
 * @package WordPress
 * @subpackage Trais Template
 * @since Trais Template
 */
global $NowLanguage,$_hpRootDir,$posts;
global $categoryID , $categoryData;
global $menuColor;

?>
	<div id="contentsWrapper" class="mainWrapp">
		<div class="autoWrapp _<?php echo $menuColor; ?>">
		
			<div id="leftSlot">
				<ul id="list">
<?php
	$getmenu = 'yearly';
	wp_get_cat_archives('type='.$getmenu, $categoryID);
?>		
				</ul>
			</div>
		
			<div id="rightSlot">

<?php
//	カテゴリ内容
	$week = array('日','月','火','水','木','金','土');

if (is_array( $posts )){
	foreach($posts as $post){
	
		
		setup_postdata($post);
		if( $categoryData->slug != 'seminar'){
			$icon = get_post_meta($post->ID,'_custom_icon',true);
			if( $icon )	$icon = sprintf('<span class="icon"><img src="%simages/index%s/icon_%s.gif" /></span>' ,$_hpRootDir ,(  $NowLanguage['load'] == 'ja' ? '/ja' : '' ) , $icon);
		}else{
			$icon = '';
		}	
		//	開催予定日 取得
		$d = get_post_custom_values('holdday');
		if( isset( $d[0] ) && $d[0] ){
			$t = strtotime($d[0]);
			if(  $NowLanguage['load'] == 'ja' ){
				$holdDay = sprintf( '[%s(%s)]<br />' , date( "Y年n月j日" , $t) , $week[date("w" , $t)]);
			}else{
				$holdDay = sprintf( '%s<br />' ,date( "l,F j,Y" , $t) );
			}
		}else{
			$holdDay = '';
		}
		
		//	新着チェック
		$newIcon = _plusNewIcon( $post->post_date );
		
	
?>
				<div class="listlink_box">
					<div class="date"><?php the_time('Y/m/d'); echo $newIcon.$icon;?></div>
					<p><a href="<?php the_permalink(); ?>"><?php echo $holdDay; the_title(); ?></a></p>
					
				</div>
<?php 
	}
//	wp_reset_query();

}
?>

		<?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); } ?>
	
			</div>
			<br style="clear:both">
		</div>
	
	</div>

<?php get_footer($NowLanguage['load']); ?>