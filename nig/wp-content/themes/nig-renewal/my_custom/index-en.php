<?php



#############################
##	変数設定
#############################
global $post , $NowLanguage , $_hpRootDir;

$_cat = array(
	//	'rh'	=> 'reserch-highlights',	//	Resarch Highlights
		'rh'	=> 'research-highlights',	//	Resarch Highlights
		'sm'	=> 'seminar',			//	Seminar
		'if'	=> 'information',		//	Information
		'ev'	=> 'events',			//	Events
		'nt'	=> 'prizes-and-awards'		//	Prizes & Awards(News&Topics)
	);

//	共通記事読込み
$today = date("Y/m/d");
$chtime = time() - (86400);


function writeost( $category ,$dFlg = false ,  $argss = "" , $autoicon = false){

	global $post;
	global $chtime;

	$thumbnailsize = array(64,64);
	
	$args =	array( 
		'posts_per_page'	=> 5,
		'category_name'		=> $category,
		'orderby'			=> 'date',
		'order'	 			=> 'DESC'
	);
	
	if( is_array( $argss ) ) $args = $argss;

	$myposts = query_posts( $args );
	
	if ( is_array($myposts)  ){
		foreach($myposts as $post){
		
			//	開催予定日 取得
			$d = get_post_custom_values('holdday');
			$newIcon = '';
			if( isset( $d[0] ) && $d[0] ){
				$t = strtotime( $d[0] );
	//			$holdDay = sprintf( '<b class="hday">[%s]</b>' , date( "l, F j, Y" , $t) );
				$holdDay = sprintf( '%s' , date( "Y/m/d" , $t) );
				if( $autoicon ){
					//	新着アイコン
					$holdDay .= _plusEndIcon( $d[0] , 1 );
				}
			}else{
				//	新着情報追加
				$holdDay = '';
				$newIcon = _plusNewIcon( $post->post_date );
				
			}

			
			//	サムネイル取得
			if(  has_post_thumbnail() ){
				 $thumnail = get_the_post_thumbnail( $post->ID , $thumbnailsize );
			}else{
				$thumnail = '';
			} 
			
			setup_postdata($post);
			$icon = get_post_meta($post->ID,'_custom_icon',true);
			if( $icon )	$icon = sprintf('<span class="icon"><img src="images/index/icon_%s.gif" /></span>' , $icon);
			
	?>
						<dt><?php if(!$dFlg){  the_time('Y/m/d'); }else{ echo $holdDay; } echo $newIcon.$icon;?></dt>
						<dd>
							<a href="<?php the_permalink(); ?>"><?php echo $thumnail;  the_title(); ?></a>
						</dd>
	<?PHP
		}
	}
	wp_reset_query();
	
	return is_array( $myposts ) ? count( $myposts ) : 0;
}
//	共通ヘッダー読込み
get_header( $NowLanguage['load'] );
?>
	
	<div id="headImageWrapp">
		<div id="slideWrap">
			<ul id="bxslider">
                <li><a href="https://www.nig.ac.jp/nig/research-infrastructure-collaboration/nig-collaboration-grant"><img src="<?php echo $_hpRootDir; ?>images/index/slide_20161213_e.jpg" alt="NIG Collaboration Grants: NIG-JOINT" /></a></li>
                <li><img src="<?php echo $_hpRootDir; ?>images/index/slide04.jpg" alt="Natural Institute of Genetics" /></li> 
				<li><img src="<?php echo $_hpRootDir; ?>images/index/slide01.jpg" alt="Natural Institute of Genetics" /></li>
				<!--<li><img src="<?php echo $_hpRootDir; ?>images/index/slide02.jpg" alt="Natural Institute of Genetics" /></li>-->
				<li><img src="<?php echo $_hpRootDir; ?>images/index/slide03.jpg" alt="Natural Institute of Genetics" /></li>
			</ul>
		</div>
	</div>
	
	<div class="mainWrapp bgGl">
		
		<div class="autoWrapp">
<?php
	//	求人情報
	if( function_exists('RECRUITMENT_getTopList') )		RECRUITMENT_getTopList();
?>	
			<div class="container">
				<div class="menu">
					<h2><a href="<?php echo home_url('/').'?category_name='.$_cat['rh']; ?>"><img src="<?php echo $_hpRootDir; ?>images/index/h2_highlights.gif" alt="Research Highlighes" /></a></h2>
					<div class="_hightAdust">
						<dl class="news">
<?php
//###############################################
//	Resarch Highlights
//-----------------------------------------------

writeost($_cat['rh']);

//###############################################
?>			
						</dl>
					</div>
          
					<div class="more"><a href="<?php echo home_url('/').'?category_name='.$_cat['rh']; ?>"><img src="<?php echo $_hpRootDir; ?>images/index/more.gif" alt="More" width="43" height="20" class="over" /></a></div>
				</div>
	
				<div class="menu">
					<h2><img src="<?php echo $_hpRootDir; ?>images/index/h2_seminar.gif" alt="Seminar" /></h2>
					<div class="_hightAdust">
					<script src="<?php echo $_hpRootDir; ?>nig_dynacom/seminar_data_bioweb.js"></script>
					<div id="seminar_data"></div>
<?php
/*
						<dl class="news">
						<dt><hr style="border:none;border-bottom:1px solid #CCC;margin-bottom:0.8em;" /></dt>
//###############################################
//	Seminar
//-----------------------------------------------
$args_sm = array(
	'meta_key'			=> 'holdday',
	'type'				=> 'DATE',
	'meta_compare'		=> '=',
	'meta_value'		=>	$today,
	'posts_per_page'	=> 15,
	'category_name'		=> $_cat['sm'],
	'orderby'			=> 'holdday',
	'order'	 			=> 'DESC'
);
writeost($_cat['sm']  , true, $args_sm);

//###############################################
?>			
						<dt><hr style="border:none;border-bottom:1px solid #CCC;margin:2em 0 0.8em;" /></dt>
<?php
//###############################################
$args_sm['meta_compare'] =  '>';
writeost($_cat['sm']  , true, $args_sm);

//###############################################
?>			
						<dt><hr style="border:none;border-bottom:1px solid #CCC;margin:2em 0 0.8em;" /></dt>
<?php
//###############################################
$args_sm['meta_compare'] =  '<';
$args_sm['posts_per_page'] =  5;
writeost($_cat['sm']  , true, $args_sm);

//###############################################
						</dl>
					<div class="more"><a href="<?php echo $_hpRootDir.'?category_name='.$_cat['sm'].'&amp;lang='.$Language; ?>"><img src="<?php echo $_hpRootDir; ?>images/index/more.gif" alt="More" width="43" height="20" class="over" /></a></div>
*/
?>			
					</div>
					<div class="more"><a href="<?php echo $_hpRootDir.'seminer?gamen=seminar_data_list' ?>"><img src="<?php echo $_hpRootDir; ?>images/index/seminar.gif" alt="More" width="60" height="20" class="over" /></a> <a href="<?php echo $_hpRootDir.'reserch/nig-meetings' ?>"><img src="<?php echo $_hpRootDir; ?>images/index/meetings.gif" alt="More" width="60" height="20" class="over" /></a></div>
				</div>
			
				<div class="menu">
					<h2><a href="<?php echo home_url('/').'?category_name='.$_cat['if']; ?>"><img src="<?php echo $_hpRootDir; ?>images/index/h2_information.gif" alt="Information" /></a></h2>
					<div class="_hightAdust">
						<dl class="news">
<?php
//###############################################
//	Information
//-----------------------------------------------

writeost($_cat['if']);

//###############################################
?>			
						</dl>
					</div>
					<div class="more"><a href="<?php echo home_url('/').'?category_name='.$_cat['if']; ?>"><img src="<?php echo $_hpRootDir; ?>images/index/more.gif" alt="More" width="43" height="20" class="over" /></a></div>
				</div>
			</div>
			<div class="container">
				<div class="menu first">
					<h2><a href="<?php echo home_url('/').'?category_name='.$_cat['ev']; ?>"><img src="<?php echo $_hpRootDir; ?>images/index/h2_events.gif" alt="Events" /></a></h2>
					<div class="_hightAdust">
						<dl class="news">
<?php
//###############################################
//	Events
//-----------------------------------------------
$args_ev = array(
	'meta_key'			=> 'holdday',
	'type'				=> 'DATE',
//	'meta_compare'		=> '>',
//	'meta_value'		=>	$today,
	'posts_per_page'	=> 5,
	'category_name'		=> $_cat['ev'],
	'orderby'			=> 'holdday',
	'order'	 			=> 'DESC'
);

writeost($_cat['ev'] , true , $args_ev, true);

//###############################################
?>			
						</dl>
					</div>
					<div class="more"><a href="<?php echo home_url('/').'?category_name='.$_cat['ev']; ?>"><img src="<?php echo $_hpRootDir; ?>images/index/more.gif" alt="More" width="43" height="20" class="over" /></a></div>
				</div>
			
				<div class="menu">
					<h2><a href="https://www.facebook.com/pages/National-Institute-of-Genetics-Official/126155277477590" target="_blank"><img src="<?php echo $_hpRootDir; ?>images/index/h2_facebook.gif" alt="Facebook" /></a></h2>
					<div class="_hightAdust" style="overflow:visible;">
<?php
//###############################################
//	facebook
//-----------------------------------------------

//writeost($_cat['nt']);

//###############################################
?>
<div class="fb-page" data-href="https://www.facebook.com/pages/National-Institute-of-Genetics-Official/126155277477590?ref=hl" data-height="350px" data-small-header="true" data-adapt-container-width="true" data-hide-cover="true" data-show-facepile="false" data-show-posts="true" style="height:350px;"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/pages/National-Institute-of-Genetics-Official/126155277477590?ref=hl"><a href="https://www.facebook.com/pages/National-Institute-of-Genetics-Official/126155277477590?ref=hl">National Institute of Genetics - Official</a></blockquote></div></div>
					</div>
					<div class="more"><br /></div>
				</div>

				<div class="menu">
					<h2><img src="<?php echo $_hpRootDir; ?>images/index/h2_links.gif" alt="Links" /></h2>
					<div class="_hightAdust">
<?php
$argspage = array(
	'post_type'		=> 'page',
	'attachment'		=> 'toplink-page'
	
);
$myposts = query_posts( $argspage );
if ( is_array($myposts)  ){
	$post = $myposts[0];
	setup_postdata($post);
	//	内容表示
	the_content();
?>
						<dl class="news">
<?php		

	$keys = array();
	$sort = array();
	
	function _sortNum( $array , $num ){
	
		if( isset($array[$num]) ){
			$num++;
			$num = _sortNum( $array , $num );
		}
		
		return $num;
	
	}

	$csKeys = get_post_custom_keys();
	if( is_array( $csKeys )){
		$keys = array();
		foreach( $csKeys as $key ){
			$num = 1000;
			if( !preg_match('/^_/',$key)){

				unset( $d );
				$d = explode('::' , $key);
				
				if( isset( $d[1] ) ){
					$num = (int)$d[0] * 10;
				}
				$num = _sortNum( $keys , $num );
				
				$keys[$num] = $key;
			}
		}
		ksort($keys);
		if( is_array($keys) ){
			foreach($keys as $key){
				$links = get_post_custom_values($key);
				$d = explode('::' , $key);
				if( isset( $d[1] ) ){
					unset($d[0]);
					$key = implode('::',$d);
				}else{
					$key = $d[0];
				}
				
				echo sprintf( '<dt class="nl"><a href="%s" target="_blank">%s</a></dt>%s'  , $links[0], $key , ( isset($links[1]) ? '<dd>'.$links[1].'</dd>' : '' ));
			}
		}
?>
						</dl>
<?php		
	}
	wp_reset_query();
}

?>			
					</div>
					<div class="more"><br /></div>
				</div>
			</div>
			<br style="clear:both" />
			
		</div>
	</div>



<?php get_footer($NowLanguage['load']); ?>