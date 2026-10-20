<?php
/**
 * 投稿ページテンプレート
 *
 * @package WordPress
 * @subpackage Trais Template
 * @since Trais Template
 */

global $NowLanguage;

//	カテゴリIDを取得
$cd = get_the_category();
if ( ! is_array( $cd ) ) {
	$cd = array();
}

// カテゴリ未設定時は null（後続テンプレ側でガード）
$categoryData = isset( $cd[0] ) ? $cd[0] : null;
$menuColor = '';
$root_page = array();

if ( is_object( $categoryData ) ) {
	$menuColor = get_menue_color( $categoryData->slug );
	ps_get_most_root_category(get_category($categoryData->term_id) ,$root_page);
}

$d = get_post_custom_values('holdday');
if( isset( $d[0] ) && $d[0] ){
	$holdDay = sprintf( '<div class="data">%s</div>' , $d[0] );
}else{
	$holdDay = '';
}

if( function_exists('icl_get_languages' )){
	$pages = icl_get_languages();
}


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
		if( $value['ID'] == $categoryData->term_id ){
			$root_page[$key]['link'] = get_page_link( $myposts[0]->ID);
		}
	}

}
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
			}else{
				if( $pl['link'] )	echo sprintf( '			<li class="next"><a href="%s">%s</a></li>%s' , $pl['link'] , $pl['post_title'] , PHP_EOL );
				else				echo sprintf( '			<li class="next">%s</li>%s' , $pl['post_title'] , PHP_EOL );
			}
		}
	}
?>
					<li><?php the_title(); ?></li>
				</ul>
			</div>
		</div>
	</div>


	<div id="contentsWrapper" class="mainWrapp">
		<div class="autoWrapp _<?php echo $menuColor; ?>">
		
			<div id="leftSlot">
				<ul id="list">
<?php
	$getmenu = 'yearly';
	
	if( $categoryData->slug == 'research-highlights' || $categoryData->slug == 'honors-and-awards')	$getmenu = 'yearly';
	wp_get_cat_archives('type='.$getmenu, $categoryData->term_id);
?>				</ul>
			</div>
		
			<div id="rightSlot">
				<?php echo $holdDay; ?>
				<h2><?php the_title(); _plusTitle();  ?></h2>
				<div class="box">
<?php 

		the_content();

?>
				</div>
<?php if(get_adjacent_post(true, '', true)) {?>
<?php previous_post_link('%link', '&laquo; previous', TRUE); ?>
<?php }else{ ?>
	&laquo; previous
<?php } ?>

<?php if(get_adjacent_post(true, '', false)) {?>
<?php next_post_link('%link ', 'next &raquo;', TRUE); ?>
<?php }else{  ?>
    next &raquo;　
<?php } ?>	

			</div>
			<br style="clear:both">
		</div>
	</div>


<?php get_footer($NowLanguage['load']) ?>