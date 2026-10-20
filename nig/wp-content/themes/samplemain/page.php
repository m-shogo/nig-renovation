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
// $icon / $noicon はショートコード側で省略されることがあるためデフォルトを付与
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
		
		//	カスタムアイコン
		if( trim($icon) ){
			
			$icons = array();	
			foreach( explode( ',',  $icon ) as $v ){
				if( trim( $v ) )	$icons[] = trim($v);
			}
			
			if( !empty( $icons )){
				
				$args['meta_query'] =	array(
						'key'=>'_custom_icon',
						'value'=> $icons,
						'compare'=>'IN'
						);
			}
		}
	
		$myposts = query_posts( $args );
		if( $title ){
			$reStr .= '		<h2>'.$title.'</h2>';
		}
		
		if ( is_array($myposts)  ){
		
			$week = array('日','月','火','水','木','金','土');

			foreach($myposts as $post){
				setup_postdata($post);
				if( isset( $categoryData ) && $categoryData->slug != 'seminar' && !$noicon){
					$icon = get_post_meta($post->ID,'_custom_icon',true);
					if( $icon ){
					
						if(  $NowLanguage['load'] == 'ja' ) {
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
                                    $icon = '<span class="icon_text icon_collaborations">コラボレーション</span>';
                                    break;
                                case 'jobs':
                                    $icon = '<span class="icon_text icon_jobs">募集</span>';
                                    break;
                                default:
                                    $icon = '';
                            }
                        } else {
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
		'view'	=> '',
		'icon'	=> '',
		'noicon'	=> ''
	), $atts));
	return readPickup($c , $ti , $view , $icon , $noicon);

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



