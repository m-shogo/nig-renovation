<?php
/**
 * 投稿ページテンプレート
 *
 * @package WordPress
 * @subpackage Trais Template
 * @since Trais Template
 */

global $NowLanguage;
global $post;

//	カテゴリIDを取得

if($post->post_type == '_recruitment'){

	get_template_part( 'singletemp' , 'recruitment' );

}else{

	## 17/07/26 追加　メインカテゴリ選択 #########################
	$_coiceCate = get_post_meta($post->ID, '_choiceCategory', true);
	
	$cd = get_the_category();
	if ( ! is_array( $cd ) ) {
		$cd = array();
	}
	$_choiceArrayNum = 0;
	global $notCate;
	//	カテゴリ複数選択の場合はメインカテゴリ選択
	$notCate = array();
	
	if( count( $cd ) > 1 ){
		
		$_orArray = 1000000;
		foreach( $cd as $aID => $v){
			if( $_coiceCate ){	//	選択カテゴリがある場合
				if( (isset( $v->cat_ID ) && $v->cat_ID == $_coiceCate) ||
					 (isset( $v->term_id ) && $v->term_id == $_coiceCate) ){
					
					$_choiceArrayNum = $aID;
				
				}
			
			}else{	//	選択カテゴリが無い場合
				$c = _defaultMainCategoryOrder( $v->slug );
				
				if( $_orArray > $c ){
					$_choiceArrayNum = $aID;
					$_orArray = $c;
				}
			}
		}
		foreach( $cd as $aID => $v){
			if( $_choiceArrayNum != $aID ){
				$notCate[] = isset( $v->cat_ID ) ? $v->cat_ID : $v->term_id;
			}
		}
	
	}
	
	// カテゴリ未設定の CPT（top_slider / top_pickup 等）では null になり得る
	$categoryData = isset( $cd[$_choiceArrayNum] ) ? $cd[$_choiceArrayNum] : null;
	$menuColor = '';
	$root_page = array();

	if ( is_object( $categoryData ) ) {
		$menuColor = get_menue_color( $categoryData->slug );

		ps_get_most_root_category(get_category($categoryData->term_id) ,$root_page);
		
		
		if( function_exists('icl_get_languages' )){
			$pages = icl_get_languages();
		}
		
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
				if( $value['ID'] == $categoryData->cat_ID ){
					$root_page[$key]['link'] = get_page_link( $myposts[0]->ID);
				}
			}
		
		}
		*/
		
		//	パン屑追加
		include_once( get_template_directory() .'/my_custom/getCategoryLinks.php' );
		if ( isset( $root_page[0]['post_name'] ) ) {
			$root_page = get_category_menus( $root_page[0]['post_name'] , $root_page);
		}
		wp_reset_query();
	}
	
	
	
	//	共通ヘッダー読込み
	get_header( $NowLanguage['load'] );
	
	if( is_object( $categoryData ) && ( $categoryData->slug == 'events' ||  $categoryData->slug == 'events_ja' ) ){
		get_template_part( 'singletemp' , 'event' );
	
	}else{
		get_template_part( 'singletemp' , 'default' );
	}
	
	/*
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
		
		if( $categoryData->slug == 'research-highlights' || $categoryData->slug == 'prizes-and-awards')	$getmenu = 'yearly';
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
	<div id="liPrev">
	<?php if(get_adjacent_post(true, '', true)) {?>
	<?php previous_post_link('%link', '&laquo; previous', TRUE); ?>
	<?php }else{ ?>
		&laquo; previous
	<?php } ?>
	</div>
	
	<div id="liNext">
	<?php if(get_adjacent_post(true, '', false)) {?>
	<?php next_post_link('%link ', 'next &raquo;', TRUE); ?>
	<?php }else{  ?>
	    next &raquo;
	<?php } ?>	
	</div>
	
				</div>
				<br style="clear:both">
			</div>
		</div>
	
	
	<?php 
	
	*/get_footer($NowLanguage['load']);
	
}	
?>