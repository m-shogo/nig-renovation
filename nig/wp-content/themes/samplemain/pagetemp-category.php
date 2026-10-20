<?php
/**
 * 投稿ページテンプレート
 *
 * @package WordPress
 * @subpackage Trais Template
 * @since Trais Template
 */
global $NowLanguage,$_hpRootDir , $target_category , $post;


//	カテゴリIDを取得

	$categoryData = get_category_by_slug( $target_category );
	// get_category_by_slug は失敗時 false を返すためガードし、term_id を使用
	if ( ! $categoryData ) {
		return;
	}
	$categoryID = $categoryData->term_id;
	//	カテゴリタイトル
//	$categorySlug = single_cat_title();
//	$categoryData = get_category($categoryID);
	// 参照渡しで埋めるパン屑用配列を初期化
	$root_page = array();
	ps_get_most_root_category($categoryData,$root_page);
	$menuColor = get_menue_color( $root_page[0]['post_name'] );

//	パン屑追加
include_once( get_template_directory() .'/my_custom/getCategoryLinks.php' );
$root_page = get_category_menus( $root_page[0]['post_name'] , $root_page);

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
			}
			else			echo sprintf( '			<li>%s</li>%s'  , $pl['post_title'] , PHP_EOL );
		}
	}
?>
				</ul>
			</div>
		</div>
	</div>

	<div id="contentsWrapper" class="mainWrapp">
		<div class="autoWrapp _<?php echo $menuColor; ?>">
		
			<div id="leftSlot">
				<ul id="list">
<?php
//	$getmenu = 'monthly';
	$getmenu = 'yearly';
//	if( $categoryData->slug == 'research-highlights' || $categoryData->slug == 'honors-and-awards')	$getmenu = 'yearly';
	wp_get_cat_archives('type='.$getmenu, $categoryID);
?>		
				</ul>
			</div>
		
			<div id="rightSlot">
<?php
	 the_content(); 
	 
?>
			</div>
			<br style="clear:both">
		</div>
	
	</div>

