<?php

#############################
##	変数設定
#############################
global $post , $NowLanguage , $_hpRootDir;

$_cat = array(
	//	'rh'	=> 'reserch-highlights',	//	Resarch Highlights
		'rh'	=> 'research-highlights_ja',	//	Resarch Highlights
		'sm'	=> 'seminar_ja',			//	Seminar
		'if'	=> 'information_ja',		//	Information
		'ev'	=> 'events_ja',			//	Events
		'nt'	=> 'prizes-and-awards_ja'		//	Prizes & Awards(News&Topics)
	);

//	共通記事読込み
$today = date("Y/m/d");
$chtime = time() - (86400);


function writeost( $category ,$dFlg = false ,  $argss = "" , $autoicon = false){

	global $post;
	global $chtime , $_hpRootDir;
	
	$week = array('日','月','火','水','木','金','土');

	$thumbnailsize = array(64,64);
	$cd =  explode( ',' , $category );
	if( count( $cd ) >= 2 ){
	
		$cid = '';
		foreach( $cd as $v ){
			$categoryData = get_category_by_slug( $v );
			
			if( is_object( $categoryData ) && $categoryData->term_id )	$cid .= !$cid ? $categoryData->term_id : ','.$categoryData->term_id;
		}
		$args =	array( 
			'posts_per_page'	=> 5,
			'cat'		=> $cid,
			'orderby'			=> 'date',
			'order'	 			=> 'DESC'
		);
	
	}else{
		
		$args =	array( 
			'posts_per_page'	=> 5,
			'category_name'		=> $category,
			'orderby'			=> 'date',
			'order'	 			=> 'DESC'
		);
	}
	if( is_array( $argss ) ) $args = $argss;

	$myposts = query_posts( $args );
	
	if ( is_array($myposts)  ){
		foreach($myposts as $post){
			//	開催予定日 取得
			$d = get_post_custom_values('holdday');
			$newIcon = '';
			if( isset( $d[0] ) && $d[0] ){
				$t = strtotime( $d[0] );
//				$holdDay = sprintf( '<b class="hday">[%s(%s)]</b>' , date( "Y年n月j日" , $t) , $week[date("w" , $t)]);
				$holdDay = sprintf( '%s' , date( "Y/m/d" , $t));
				if( $autoicon ){
					//	新着アイコン
					$holdDay .= _plusEndIcon( $d[0] , 1 );
				}
			}else{

				//	新着情報追加
				$newIcon = _plusNewIcon( $post->post_date );
				$holdDay = '';
			}

			
			//	サムネイル取得
			if(  has_post_thumbnail() ){
				 $thumnail = get_the_post_thumbnail( $post->ID , $thumbnailsize );
			}else{
				$thumnail = '';
			} 
			
			setup_postdata($post);
			$icon = get_post_meta($post->ID,'_custom_icon',true);
			if( $icon )	$icon = sprintf('<span class="icon"><img src="'.$_hpRootDir.'images/index/ja/icon_%s.jpg" /></span>' , $icon);
			
	?>
						<dt><?php if(!$dFlg){  the_time('Y/m/d'); }else{echo $holdDay;}echo $newIcon.$icon;?></dt>
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
				<li><a href="https://www.nig.ac.jp/nig/ja/about-nig/support-us"><img src="<?php echo $_hpRootDir; ?>images/index/slide_20170802_j.jpg" alt="寄附のお願い" /></a></li>
				<!--<li><a href="https://www.nig.ac.jp/nig/ja/phd-program/taiken#haru"><img src="<?php echo $_hpRootDir; ?>images/index/slide_20180130.jpg" alt="総合研究大学院大学 遺伝学専攻 遺伝研 体験入学" /></a></li>-->
				<!--<li><a href="https://www.nig.ac.jp/nig/ja/career-development/2019nig-postdoc"><img src="<?php echo $_hpRootDir; ?>images/index/slide_20160523_01_j.jpg" alt="国立遺伝学研究所 博士研究員募集" /></a></li>-->                                                     
                <li><a href="https://shigen.nig.ac.jp/mouse/researchSupportingUnit/" target="_blank"><img src="<?php echo $_hpRootDir; ?>images/index/slide_20170522.jpg" alt="マウス研究支援ユニット" /></a></li>
				<!--<li><a href="http://www.rois.ac.jp/rep/rep.html"><img src="<?php echo $_hpRootDir; ?>images/index/slide_20171115.jpg" alt="遺伝研でサバティカルしませんか" /></a></li>-->
                <li><a href="https://www.nig.ac.jp/nig/ja/phd-program/main-page-top/main-page"><img src="<?php echo $_hpRootDir; ?>images/index/slide_20160523.jpg" alt="総合研究大学院大学 遺伝学専攻 大学院生募集" /></a></li>
                <li><a href="https://www.nig.ac.jp/nig/ja/research-infrastructure-collaboration/nig-collaboration-grant"><img src="<?php echo $_hpRootDir; ?>images/index/slide_20161213_j.jpg" alt="公募型共同研究・研究会「NIG-JOINT」" /></a></li>
                <li><a href="https://www.nig.ac.jp/nig/ja/career-development/jobs-at-nig"><img src="<?php echo $_hpRootDir; ?>images/index/slide_20160721.jpg" alt="遺伝研の人材募集" /></a></li>
                <li><img src="<?php echo $_hpRootDir; ?>images/index/slide_20151009.jpg" alt="国立遺伝学研究所" /></li>
				<!--<li><a href="https://www.nig.ac.jp/nig/ja/phd-program/taiken#haru"><img src="<?php echo $_hpRootDir; ?>images/index/slide_20170110.jpg" alt="総合研究大学院大学 遺伝学専攻 遺伝研 体験入学" /></a></li>-->
                <!--<li><a href="https://www.nig.ac.jp/nig-daigakuin/"><img src="<?php echo $_hpRootDir; ?>images/index/slide_20180308.jpg" alt="国立遺伝学研究所 大学院一日体験会2018 生命科学の未来を切り拓くのは君だ" /></a></li>-->
                <!--<li><a href="https://www.nig.ac.jp/kouenkai/" target="_blank"><img src="<?php echo $_hpRootDir; ?>images/index/slide_20160826.jpg" alt="国立遺伝学研究所 公開講演会2016 出張!!遺伝研" /></a></li>-->
                <!--<li><a href="https://www.nig.ac.jp/kouenkai/" target="_blank"><img src="<?php echo $_hpRootDir; ?>images/index/slide_20170901.jpg" alt="国立遺伝学研究所 公開講演会2017 出張!!遺伝研" /></a></li>-->
                <!--<li><a href="https://www.nig.ac.jp/nig/ja/information_ja20161028"><img src="<?php echo $_hpRootDir; ?>images/index/slide_20161108.jpg" alt="名誉教授 太田朋子博士　文化勲章 受章" /></a></li>-->
				<!--<li><a href="https://www.nig.ac.jp/koukai/" target="_blank"><img src="<?php echo $_hpRootDir; ?>images/index/slide_20180222.jpg" alt="国立遺伝学研究所 一般公開 2017年4月7日（土）" /></a></li>-->
                <!--<li><img src="<?php echo $_hpRootDir; ?>images/index/slide04.jpg" alt="国立遺伝学研究所" /></li>--> 
                <!--<li><img src="<?php echo $_hpRootDir; ?>images/index/slide01.jpg" alt="国立遺伝学研究所" /></li>-->
				<!--<li><img src="<?php echo $_hpRootDir; ?>images/index/slide02.jpg" alt="国立遺伝学研究所" /></li>-->
				<!--<li><img src="<?php echo $_hpRootDir; ?>images/index/slide03.jpg" alt="国立遺伝学研究所" /></li>-->
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
					<h2><a href="<?php echo home_url('/').'?category_name='.$_cat['rh']; ?>"><img src="<?php echo $_hpRootDir; ?>images/index/ja/h2_highlights.gif" alt="Research Highlighes" /></a></h2>
					<div class="_hightAdust">
						<dl class="news">
<?php
//###############################################
//	Resarch Highlights
//-----------------------------------------------

//writeost($_cat['rh']/*.','.$_cat['nt']*/ );
writeost($_cat['rh']);

//###############################################
?>			
						</dl>
					</div>
					<div class="more"><a href="<?php echo home_url('/').'?category_name='.$_cat['rh']; ?>"><img src="<?php echo $_hpRootDir; ?>images/index/more.gif" alt="More" width="43" height="20" class="over" /></a></div>
				</div>
	
				<div class="menu">
					<h2><img src="<?php echo $_hpRootDir; ?>images/index/ja/h2_seminar.gif" alt="Seminar" /></h2>
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
					<div class="more"><a href="<?php echo home_url('/').'?category_name='.$_cat['sm']; ?>"><img src="<?php echo $_hpRootDir; ?>images/index/more.gif" alt="More" width="43" height="20" class="over" /></a></div>
*/
?>			
					</div>
					<div class="more"><a href="<?php echo $_hpRootDir.'ja/seminer-ja/?gamen=seminar_data_list' ?>"><img src="<?php echo $_hpRootDir; ?>images/index/seminar.gif" alt="More" width="60" height="20" class="over" /></a> <a href="<?php echo $_hpRootDir.'ja/research/nig-meetings' ?>"><img src="<?php echo $_hpRootDir; ?>images/index/meetings.gif" alt="More" width="60" height="20" class="over" /></a></div>
				</div>
			
				<div class="menu">
					<h2><a href="<?php echo home_url('/').'?category_name='.$_cat['if']; ?>"><img src="<?php echo $_hpRootDir; ?>images/index/ja/h2_information.gif" alt="Information" /></a></h2>
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
					<h2><a href="<?php echo home_url('/').'?category_name='.$_cat['ev']; ?>"><img src="<?php echo $_hpRootDir; ?>images/index/ja/h2_events.gif" alt="Events" /></a></h2>
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
					<h2><a href="https://www.facebook.com/pages/%E5%9B%BD%E7%AB%8B%E9%81%BA%E4%BC%9D%E5%AD%A6%E7%A0%94%E7%A9%B6%E6%89%80/242392782455048" target="_blank"><img src="<?php echo $_hpRootDir; ?>images/index/h2_facebook.gif" alt="Facebook" /></a></h2>
					<div class="_hightAdust" style="overflow:visible;">
<?php
//###############################################
//	facebook
//-----------------------------------------------

//writeost($_cat['nt']);

//###############################################
?>

<div class="fb-page" data-href="https://www.facebook.com/pages/%E5%9B%BD%E7%AB%8B%E9%81%BA%E4%BC%9D%E5%AD%A6%E7%A0%94%E7%A9%B6%E6%89%80/242392782455048" data-height="360px" data-small-header="true" data-adapt-container-width="true" data-hide-cover="true" data-show-facepile="false" data-show-posts="true" style="height:360px;"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/pages/%E5%9B%BD%E7%AB%8B%E9%81%BA%E4%BC%9D%E5%AD%A6%E7%A0%94%E7%A9%B6%E6%89%80/242392782455048"><a href="https://www.facebook.com/pages/%E5%9B%BD%E7%AB%8B%E9%81%BA%E4%BC%9D%E5%AD%A6%E7%A0%94%E7%A9%B6%E6%89%80/242392782455048">国立遺伝学研究所</a></blockquote></div></div>


					</div>
					<div class="more"><br /></div>
				</div>
			
				<div class="menu">
					<h2><img src="<?php echo $_hpRootDir; ?>images/index/ja/h2_links.gif" alt="Links" /></h2>
					<div class="_hightAdust">
<?php
$argspage = array(
	'post_type'		=> 'page',
	'attachment'		=> 'toplink-page_ja'
	
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
//				$key = isset( $d[1] ) ? $d[1] : $d[0];
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