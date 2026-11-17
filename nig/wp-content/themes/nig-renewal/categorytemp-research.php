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
                    <div class="categoryName_title"><?php echo $categoryData->cat_name; ?></div>
				<div class="yearSelectWrap">
                    
                        <?php 
                            if(get_query_var('year')) {
                                $post_year = get_query_var('year');
                            } else {
                                $post_year = '';
                            };
                        ?>
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
	// $getmenu = 'yearly';
	// wp_get_cat_archives('type='.$getmenu, $categoryID);
?>		
				</ul>
			</div>
		
			<div id="rightSlot">

<?php
//	カテゴリ内容
	$week = array('日','月','火','水','木','金','土');
/*
    //年度絞り込み時、全件表示
    if($post_year != '') {
        $args = array(
            'posts_per_page'   => -1,
            'category'   => $categoryID,
            'order' => 'DESC',
            'date_query' => array(
                array(
                'year' => $post_year,
                ),
            ),
        );
            
        $posts = get_posts($args);
    }
*/
                
if (is_array( $posts )){
	foreach($posts as $post){
	
		
		setup_postdata($post);
		if( $categoryData->slug != 'seminar'){
			$icon = get_post_meta($post->ID,'_custom_icon',true);
			if( $icon ){
                if($NowLanguage['load'] == 'ja') {
                    //日本語版
                    switch ($icon) {
                        case 'new':
                            $icon = '<span class="icon_text icon_new">新着</span>';
                            break;
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
                        default:
                            $icon = '';
                    }
                } else {
                    //英語版
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
            }	
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
				<div class="listlink_box aaa">
										
					<div class="date"><?php the_time('Y/m/d'); echo $icon.$newIcon;?></div>
					<p><a href="<?php the_permalink(); ?>"><?php echo $holdDay; the_title(); ?></a></p>
					
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
	
	</div>

<?php get_footer($NowLanguage['load']); ?>
