<?php
/**
 * 投稿ページテンプレート
 *
 * @package WordPress
 * @subpackage Trais Template
 * @since Trais Template
 */
global $NowLanguage,$_hpRootDir;
global $categoryID , $categoryData;
global $menuColor;
global $posts;

?>
	<div id="contentsWrapper" class="mainWrapp">
		<div class="autoWrapp _<?php echo $menuColor; ?>">
		
			<div id="leftSlot">
				<ul id="list">
<?php
//	$getmenu = 'monthly';
	$getmenu = 'yearly';

//	if( $categoryData->slug == 'research-highlights' || $categoryData->slug == 'prizes-and-awards')	$getmenu = 'yearly';
	wp_get_cat_archives('type='.$getmenu, $categoryID);
?>		
				</ul>
			</div>
		
			<div id="rightSlot">
<?php	 //	カテゴリ説明文
/*				<h2><?php echo $categoryData->name; ?></h2>
	
	if( $categoryData->description != '' ){
		echo sprintf( '<div class="box">%s</div>' , $categoryData->description );	
	}
*/
?>


<?php
//	カテゴリ内容
/*
$posts = query_posts(array(
	"order"				=> 'date DESC',
	"cat"				=> $categoryID
	) );*/

if (is_array( $posts )){
	foreach($posts as $post){
	
/*		//	サムネイル取得
		if(  has_post_thumbnail() ){
			 $thumnail = get_the_post_thumbnail( $post->ID , $thumbnailsize );
		}else{
			$thumnail = '';
		} */
		
		setup_postdata($post);
		if( $categoryData->slug != 'seminar'){
			$icon = get_post_meta($post->ID,'_custom_icon',true);
			if( $icon ) {
                switch ($icon) {
                    case 'new':
                        $icon = '<span class="icon_text icon_new">New</span>';
                        break;
                    case 'past':
                        $icon = '<span class="icon_text icon_past">Past</span>';
                        break;
                    case 'pressrelease':
                        $icon = '<span class="icon_text icon_pressrelease">Press release</span>';
                        break;
                    case 'recruitment':
                        $icon = '<span class="icon_text icon_recruitment">Recruitment</span>';
                        break;
                    case 'award':
                        $icon = '<span class="icon_text icon_award">Award</span>';
                        break;
                    case 'publish':
                        $icon = '<span class="icon_text icon_publish">Publish</span>';
                        break;
                    case 'collaborations':
                        $icon = '<span class="icon_text icon_collaborations">Collaborations</span>';
                        break;
                    case 'jobs':
                        $icon = '<span class="icon_text icon_jobs">Recruitment</span>';
                        break;
                    default:
                        $icon = '';
                }
            }
		}else{
			$icon = '';
		}	
		//	開催予定日 取得
		$d = get_post_custom_values('holdday');
		if( isset( $d[0] ) && $d[0] ){
			$t = strtotime($d[0]);
			$holdDay = sprintf( '%s<br />' ,date( "l,F n,Y" , $t) );
		}else{
			$holdDay = '';
		}
		//	新着チェック
		$newIcon = _plusNewIcon( $post->post_date );
	
	
	
?>
				<div class="box">
					<div class="date"><?php the_time('Y/m/d'); echo $icon.$newIcon;?></div>
					<h2><?php echo $holdDay; the_title(); ?></h2>
					<div class="texts">
<!-- PostContetns -->
					
<?php the_content(); ?>

<!-- PostContetns -->
					</div>
					
				</div>
<?php 
	}
}
?>

		<?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); } ?>
	
			</div>
			<br style="clear:both">
		</div>
	
	</div>

