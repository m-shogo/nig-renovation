<?php
/**
 * 固定ページテンプレート
 *
 * @package WordPress
 * @subpackage Trais Template
 * @since Trais Template
 */
 // ルート階層
global $NowLanguage;
global $post , $target_category; 

//	ショートコード ##################################
/*
function readPickup( $category , $title ){
	global $post , $_hpRootDir , $NowLanguage;
	if( $category ){
		
		
		$args =	array( 
			'posts_per_page'	=> 50,
			'meta_key'			=> '_custom_pickup',
			'meta_compare'		=> '>',
			'meta_value'		=> '0',
			'category_name'		=> $category,
			'orderby'			=> 'date',
			'order'	 			=> 'DESC'
		);
	
		$myposts = query_posts( $args );
		if( $title ){
			echo '		<h2>'.$title.'</h2>';
		}
		
		if ( is_array($myposts)  ){
		
			$week = array('日','月','火','水','木','金','土');

			foreach($myposts as $post){
		setup_postdata($post);
		if( $categoryData->slug != 'seminar'){
			$icon = get_post_meta($post->ID,'_custom_icon',true);
			if( $icon ){
			
				if(  $NowLanguage['load'] == 'ja' )
					$icon = sprintf('<span class="icon"><img src="%simages/index/ja/icon_%s.jpg" /></span>' ,$_hpRootDir , $icon);
				else
					$icon = sprintf('<span class="icon"><img src="%simages/index/icon_%s.gif" /></span>' ,$_hpRootDir , $icon);
			}
		}else{
			$icon = '';
		}	
		//	開催予定日 取得
		$d = get_post_custom_values('holdday');
		if( isset( $d[0] ) && $d[0] ){
			$t = strtotime($d[0]);
			if(  $NowLanguage['load'] == 'en' )				$holdDay = sprintf( '%s<br />' ,date( "l,F n,Y" , $t) );
			else											$holdDay = sprintf( '%s(%s)<br />' ,date( "Y/m/d" , $t) , $week[date("w",$t)]);
		}else{
			$holdDay = '';
		}

?>
				<div class="listlink_box">
					<div class="date"><?php the_time('Y/m/d'); echo $icon;?></div>
					<p><a href="<?php the_permalink(); ?>"><?php echo $holdDay; the_title(); ?></a></p>
					
				</div>


<?php			
			}
		}
		wp_reset_query();	
	}
}
*/

//	ショートコード ##################################
// page.php 側の定義と引数数を揃え、$icon / $noicon は省略可能にする
function readPickup( $category , $title , $view = '', $icon = '', $noicon = '' ){
	global $post , $_hpRootDir , $NowLanguage;
	global $categoryData;

	$v = intval( $view );
	
	$v = ($v > 0) ? $v : 50;
	
	$reStr = '';
	if( $category ){
		
		
		$args =	array( 
			'posts_per_page'	=> $v,
			'meta_key'			=> '_custom_pickup',
			'meta_compare'		=> '>',
			'meta_value'		=> '0',
			'category_name'		=> $category,
			'orderby'			=> 'date',
			'order'	 			=> 'DESC'
		);
	
		$myposts = query_posts( $args );
		if( $title ){
			$reStr .= '		<h2>'.$title.'</h2>';
		}
		
		if ( is_array($myposts)  ){
		
			$week = array('日','月','火','水','木','金','土');

			foreach($myposts as $post){
				setup_postdata($post);
				if( isset( $categoryData ) && $categoryData->slug != 'seminar'){
					$icon = get_post_meta($post->ID,'_custom_icon',true);
					if( $icon ){
					
						if(  $NowLanguage['load'] == 'ja' )
							$icon = sprintf('<span class="icon"><img src="%simages/index/ja/icon_%s.jpg" /></span>' ,$_hpRootDir , $icon);
						else
							$icon = sprintf('<span class="icon"><img src="%simages/index/icon_%s.gif" /></span>' ,$_hpRootDir , $icon);
					}
				}else{
					$icon = '';
				}	
				//	開催予定日 取得
				$d = get_post_custom_values('holdday');
				if( isset( $d[0] ) && $d[0] ){
					$t = strtotime($d[0]);
					if(  $NowLanguage['now'] == 'en' )				$holdDay = sprintf( '%s<br />' ,date( "l,F n,Y" , $t) );
					else											$holdDay = sprintf( '%s(%s)<br />' ,date( "Y/m/d" , $t) , $week[date("w",$t)]);
				}else{
					$holdDay = '';
				}
				$reStr .='
				<div class="listlink_box">
					<div class="date">'.get_the_time('Y/m/d').$icon.'</div>
					<p><a href="'.get_permalink().'">'.$holdDay.get_the_title().'</a></p>
				</div>
';
	
			}
		}
		wp_reset_query();	
	}
	
	return $reStr;
}


function _pickupCategory($atts) {

	extract(shortcode_atts(array(
		'c' 	=> '',
		'ti'	=> '',
		'view'	=> ''
	), $atts));
	return readPickup($c , $ti , $view);

}
add_shortcode('pickup_category', '_pickupCategory');

######################################################################

//	カスタムフィールド読込み

$target_category = get_post_meta($post->ID, 'target_category', true);

//	共通ヘッダー読込み
get_header( $NowLanguage['load'] );

if( $target_category )	 get_template_part( 'pagetemp' , 'category' );
else{
	$noside = array(/*'seminer'=>1 , 'seminer-ja' =>1 ,*/'seminer-sample'=>1 , 'seminer-sample02' =>1, 'nig-meetings-sample' =>1);

	if( isset( $noside[$post->post_name] ) )	get_template_part( 'pagetemp' , 'noside' );
	else										get_template_part( 'pagetemp' , 'default' );
}

get_footer($NowLanguage['load']);



