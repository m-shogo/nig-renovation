<?php
/**
 * 投稿ページテンプレート
 *
 * @package WordPress
 * @subpackage Trais Template
 * @since Trais Template
 */
global $NowLanguage;
global $categoryID , $categoryData;
global $posts;
// 参照渡し用に初期化
$root_page = array();
if ( get_query_var( 'year' ) ) {
	$post_year = get_query_var( 'year' );
} else {
	$post_year = '';
}
//	共通ヘッダー読込み
get_header( $NowLanguage['load'] );


if( is_category() ){
	$categoryID = get_query_var('cat');
	//	カテゴリタイトル
//	$categorySlug = single_cat_title();
	$categoryData = get_category($categoryID);

	ps_get_most_root_category($categoryData,$root_page);
	
}

$menuColor = get_menue_color( $root_page[0]['post_name'] );

/*
$argssm = array(
	'suppress_filters' => false,
	'post_type'			=> 'page',
	'posts_per_page'	=> 10,
	'meta_query' => array(
		array(
			'key'		=> 'target_category',
			'value'		=> $categoryData->slug,
			'compare'	=> '='
   			)
		)
	);
$myposts = query_posts($argssm);

if ( !empty($myposts)  ){
	foreach( $root_page as $key=>$value ){
		if( $value['ID'] == $categoryID ){
			$root_page[$key]['link'] = get_page_link( $myposts[0]->ID);
		}
	}
}
*/

//	パン屑追加
include_once( get_template_directory() .'/my_custom/getCategoryLinks.php' );
$root_page = get_category_menus( $root_page[0]['post_name'] , $root_page);

wp_reset_query();

//	共通ヘッダー読込み
get_header( $NowLanguage['load'] );
?>

	<div class="mainWrapp <?php echo $menuColor; ?>">
		<div id="bodies" class="autoWrapp">
			<div id="breadcrumb" class="<?php echo $menuColor; ?>">
				<ul>
					<li class="next"><a href="<?php echo home_url('/'); ?>">Home</a></li>
<?php
	if( is_array($root_page)){
		global $JumpHeadLink;
		
		foreach($root_page as $plkey => $pl){
			if( isset( $JumpHeadLink[$pl['post_name']] )){
				echo sprintf( '			<li class="next">%s</li>%s' , $pl['post_title'] , PHP_EOL );
			}elseif( $plkey ){
				if( $pl['link'] )	echo sprintf( '			<li class="next"><a href="%s">%s</a></li>%s' , $pl['link'] , $pl['post_title'] , PHP_EOL );
				else				echo sprintf( '			<li class="next">%s</li>%s' , $pl['post_title'] , PHP_EOL );
			}else			echo sprintf( '			<li><a href="%s">%s</a></li>%s' , $pl['link'] , $pl['post_title'] , PHP_EOL );
		}
	}
?>
				</ul>
			</div>
		</div>
	</div>
	<div id="contentsWrapper" class="mainWrapp">
		
		<div class="autoWrapp _<?php echo $menuColor; ?>">
			<div class="categoryTemp__titleBlock">
				<div class="categoryName_title">重要なお知らせ</div>
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
			'category_name' => 'important-notices-jp',
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
				<div class="listlink_box bbbb">
					
					<div class="date"><?php the_time('Y/m/d'); echo $newIcon.$icon;?></div>
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