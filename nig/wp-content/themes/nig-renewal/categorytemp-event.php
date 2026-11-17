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

if(get_query_var('year')) {
	$post_year = get_query_var('year');
} else {
	$post_year = '';
}

?>
	<div id="contentsWrapper" class="mainWrapp">
		<div class="autoWrapp _<?php echo $menuColor; ?>">
			
			<div class="categoryTemp__titleBlock">
				<div class="categoryName_title"><?php $category = get_the_category(); $cat_name = $category[0]->name; echo $cat_name; ?></div>
				<div class="yearSelectWrap">
					<ul>
						<li class="_first_list-item">
							<?php if($post_year != ''): ?>
								<?php echo $post_year ?><span class="ja-year">年</span>
                            <?php else:?>
                                Year
							<?php endif; ?>
						</li>
						<?php
							$getmenu = 'yearly';
							wp_get_cat_archives('type='.$getmenu, $categoryID);
						?>
					</ul>
					<div class="yearSelectButton"><span class="_rotateArrow">▼</span></div>
				</div>
			</div>
			
			
		
			<div id="leftSlot">
				<ul id="list">
<?php
/*
	$getmenu = 'yearly';
	wp_get_cat_archives('type='.$getmenu, $categoryID);
*/

$_myDB = new dbengine();

$_myDB->my_get_year_archives($categoryID);


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
			if( $icon ) {
                if($NowLanguage['load'] == 'ja') {
                    //日本語版
                    switch ($icon) {

                        case 'past':
                            $icon = '<span class="icon_text icon_past">終了</span>';
                            break;
                        case 'pressrelease':
                            $icon = '<span class="icon_text icon_pressrelease">プレスリリース</span>';
                            break;
                        case 'recruitment':
                            $icon = '<span class="icon_text icon_recruitment">募集</span>';
                            break;
                        case 'award':
                            $icon = '<span class="icon_text icon_award">受賞</span>';
                            break;
                        case 'publish':
                            $icon = '<span class="icon_text icon_publish">出版</span>';
                            break;
                        case 'collaborations':
                            $icon = '<span class="icon_text icon_collaborations">共同研究</span>';
                            break;
                        case 'jobs':
                            $icon = '<span class="icon_text icon_jobs">募集</span>';
                            break;
 						case 'new':
                        	$icon = '<span class="icon_text icon_new icon_automatic">新着</span>';
                        	break;                           
                        default:
                            $icon = '';
                    }
                } else {
                    //英語版
                    switch ($icon) {
						case 'new':
                        	$icon = '<span class="icon_text icon_new icon_automatic">New</span>';
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
            }
		}else{
			$icon = '';
		}	
		//	開催予定日 取得
		$d = get_post_custom_values('holdday');
		$endIcon =  '';
		if( isset( $d[0] ) && $d[0] ){

			$holdDay = $d[0];
			$endIcon = _plusEndIcon( $holdDay );
			
		}else{
			$holdDay = '';
		}
		
		//	終了チェック
		
	
?>


				<div class="listlink_box">
                <?php if($NowLanguage['load'] == 'ja') { ?>
					<div class="date"><?php echo $holdDay; echo $endIcon.$icon;?>
<?php
$post_time = get_the_time('U');
$days = 31; //New!を表示させる日数
$last = time() - ($days * 24 * 60 * 60);
if ($post_time > $last) {
echo '<span class="icon_text icon_new icon_automatic icon_event_none">新着</span>';
}
?></div>

                <?php } else { ?>
					<div class="date"><?php echo $holdDay; echo $endIcon.$icon;?>
<?php
$post_time2 = get_the_time('U');
$days = 31; //New!を表示させる日数
$last = time() - ($days * 24 * 60 * 60);
if ($post_time2 > $last) {
echo '<span class="icon_text icon_new icon_automatic icon_event_none">New</span>';
}
?></div>

                <?php } ?>

					<p><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>
					
				</div>




<?php 
	}
//	wp_reset_query();

}
?>

	<?php /*if(function_exists('wp_pagenavi')) { wp_pagenavi(); }*/ ?>
                <?php /*if($post_year == ''): //年アーカイブの場合表示しない*/?>
                
                <div class="new_pagination">
                    <?php echo paginate_links( array ( 
                        'type' => 'list',
                        'prev_text' => '◀',
                        'next_text' => '▶︎'
                        )); 
                    ?>
                </div>
                <?php /*endif;*/?>
			</div>
			<br style="clear:both">
		</div>
	
	</div>

<?php get_footer($NowLanguage['load']);//年アーカイブの場合表示しない ?>