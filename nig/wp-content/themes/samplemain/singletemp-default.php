<?php

global $menuColor , $root_page , $categoryData , $_hpRootDir , $NowLanguage ;
global $notCate;
$d = get_post_custom_values('holdday');
if( isset( $d[0] ) && $d[0] ){
	$holdDay = sprintf( '<div class="data">%s</div>' , $d[0]  );
}else{
	$holdDay = '';
}


?>
	<div class="mainWrapp <?php echo $menuColor; ?>">
		<div id="bodies" class="autoWrapp">
			<div id="breadcrumb" class="<?php echo $menuColor; ?>">
				<ul>
					<li class="next"><a href="<?php echo home_url('/'); ?>">Home</a></li>
<?php
	// ループ後のリスト戻りリンク用に初期化（$root_page が配列でない場合も未定義を防ぐ）
	$pl = array();
	if( is_array($root_page)){
		global $JumpHeadLink;
		$c = 0;
		foreach($root_page as $plkey => $pl){
			$c++;
			$n = ( count( $root_page ) != $c ) ? ' class="next"' : ''; 
			if( isset( $JumpHeadLink[$pl['post_name']] )){
				echo sprintf( '			<li%s>%s</li>%s' ,$n, $pl['post_title'] , PHP_EOL );
			}else{
				if( $pl['link'] )	echo sprintf( '			<li%s><a href="%s">%s</a></li>%s' ,$n, $pl['link'] , $pl['post_title'] , PHP_EOL );
				else				echo sprintf( '			<li%s>%s</li>%s' ,$n, $pl['post_title'] , PHP_EOL );
			}
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
	$getmenu = 'yearly';
	// カテゴリ未設定投稿ではアーカイブ出力をスキップ
	$category_slug = ( isset( $categoryData ) && is_object( $categoryData ) ) ? $categoryData->slug : '';
	$category_term_id = ( isset( $categoryData ) && is_object( $categoryData ) ) ? (int) $categoryData->term_id : 0;
	
	if( $category_slug == 'research-highlights' || $category_slug == 'prizes-and-awards')	$getmenu = 'yearly';
	if ( $category_term_id ) {
		wp_get_cat_archives('type='.$getmenu, $category_term_id);
	}
?>				</ul>
			</div>
		
			<div id="rightSlot">
				<?php echo $holdDay;?>
				<?php the_date(); ?>
				<h2><?php the_title(); _plusTitle();  ?></h2>
                <?php if( get_field('subtitle') ): ?>
                    <div class="custom-Subtitle"><?php echo get_field('subtitle') ;?></div>
                <?php endif ;?>
				<div class="box">
<?php 

		the_content();

?>

				</div>



			</div>
			<br style="clear:both">
		</div>
        
        <?php 
            $link_return = '';
            // if(get_the_category()) {
            //     $cat_return = get_the_category(); 
            //     $cat_return_id = $cat_return[0]->cat_ID; 
            //     $link_return = get_category_link($cat_return_id);
            // }
            // パン屑ループ外のため、最後の $pl が未定義の場合がある
            if( isset( $pl['link'] ) && $pl['link'] ) {
                $link_return = $pl['link'];
            }
        ?>
        <?php if(!empty($link_return)) :?>
            <?php if(  $NowLanguage['load'] == 'ja' ) :?>
            <a class="listReturnButton" href="<?php echo $link_return; ?>">リストに戻る</a>
            <?php else: ?>
            <a class="listReturnButton" href="<?php echo $link_return; ?>">Back</a>
                <?php endif;?>
        <?php endif;?>
	</div>