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
// 参照渡し用に初期化
$root_page = array();
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
<?php

switch( $categoryData->slug ){
	case 'prizes-and-awards':
	case 'prizes-and-awards_ja':
		get_template_part( 'categorytemp','awards' );
		break;
	case 'events':
	case 'events_ja':
		get_template_part( 'categorytemp','event' );
		break;
	case 'whats-new':
	case 'whats-new_ja':
		get_template_part( 'categorytemp','whatsnew' );
		break;
	case 'information':
	case 'information_ja':
		get_template_part( 'categorytemp','information' );
		break;
	case 'important-notice':
	case 'important-notice_ja':
	case 'important-notice_ja_test':
		get_template_part( 'categorytemp','important' );
		break;
	case 'collaboration-pr':	// 20241004追加 共同研究PRは別カテゴリにする
	case 'collaboration-pr_ja':	// 20241004追加 共同研究PRは別カテゴリにする
		get_template_part( 'categorytemp','collaboration' );
		break;
	case 'phd-program':
	case 'phd-program_ja':
	case 'research-highlights':
	case 'research-highlights_ja':
	case 'nigjob-faculty':
	case 'nigjob-faculty-2':
	case 'nigjob-administration':
	case 'nigjob-administration-2':
	case 'nigjob-Postdoc':
	case 'nigjob-Postdoc-2':
	case 'nigjob-Technical':
	case 'nigjob-Technical-2':
	case 'nigjob-Other':
	case 'nigjob-Other-2':
		get_template_part( 'categorytemp','research' );
		break;
		
	default:
		if(  $NowLanguage['load'] == 'ja' )		get_template_part( 'categorytemp','ja' );
		else									get_template_part( 'categorytemp','en' );
		break;
}
get_footer($NowLanguage['load']);

?>