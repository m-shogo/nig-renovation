<?php
/**
 * 投稿ページテンプレート
 *
 * @package WordPress
 * @subpackage Trais Template
 * @since Trais Template
 */

global $NowLanguage;

$menuColor = 'bgColorGl';
//	カテゴリIDを取得



	//	共通ヘッダー読込み
	get_header( $NowLanguage['load'] );
	
?>
	<div class="mainWrapp <?php echo $menuColor; ?>">
		<div id="bodies" class="autoWrapp">
			<div id="breadcrumb" class="<?php echo $menuColor; ?>">
				<ul>
					<li class="next"><a href="<?php echo home_url('/'); ?>">Home</a></li>
					<li class=""><a href="<?php echo home_url('/'.RECRUITMENT_PAGE_SLAG.'/'); ?>"><?php if( function_exists('RECRUITMENT_getTitle') )	echo RECRUITMENT_getTitle(0); ?></a></li>
				</ul>
			</div>
		</div>
	</div>


	<div id="contentsWrapper" class="mainWrapp">
		<div class="autoWrapp _<?php echo $menuColor; ?>">
		
			<div id="rightSlot">
				<h2><?php the_title(); ?></h2>
				<div class="box ">
<?php 

		the_content();

?>
				</div>

			</div>
			<br style="clear:both">
		</div>
        <?php if(  $NowLanguage['load'] == 'ja' ) :?>
            <a class="listReturnButton" href="<?php echo home_url('/'.RECRUITMENT_PAGE_SLAG.'/'); ?>">リストに戻る</a>
        <?php else: ?>
            <a class="listReturnButton" href="<?php echo home_url('/'.RECRUITMENT_PAGE_SLAG.'/'); ?>">Back</a>
        <?php endif;?>
	</div>
	
<?php


	get_footer($NowLanguage['load']) ;
	
