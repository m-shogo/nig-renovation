<?php
//	埋め込み変数制御

global $page, $paged , $post, $cat;
// ルート階層
global $_hpRootDir;

//	タイトル設定 -----------------------------------


if ( is_home() || is_front_page() ){	//	ホーム
	$title = get_bloginfo( 'name' , true ).'::'.get_bloginfo( 'description', 'display' );
	
}elseif( is_single() || is_page() ){	//	個別記事
	$title = get_the_title();//.'::'.get_bloginfo( 'name' , true );
	
}elseif( is_category() ){		//	カテゴリ
	$dat = get_category($cat);	
	$title = $dat->name;
	
}elseif( is_search() ){		//	検索結果
	$title = 'Search';
	
}elseif( is_archive() ){	//	アーカイブ
	$title = 'archive';	
}else{
	$title = 'notfound';	
}
//	翻訳ページ対象
if( function_exists('icl_get_languages' )){
	$pagedatas = icl_get_languages();
}
//	GET引継ぎ
$plus_GET_str = '';
// $post が無い画面（一部 CPT 等）で post_name 参照しない
$post_name = ( isset( $post ) && is_object( $post ) ) ? $post->post_name : '';
if( 
	$post_name == 'job-opportunities' ||
	$post_name == 'job-outside-nig' ||
	$post_name == 'nig-meetings' ||	
	$post_name == 'seminer' ||
	$post_name == 'seminer-ja' ){
	if(is_array( $_GET )){
		$c = true;
		foreach( $_GET as $k=>$v ){
			$plus_GET_str .= sprintf( '%s%s=%s' , ( $c ? '?' : '&amp;' ) , $k , urlencode($v) );
			$c = false;
		}
	}
}

if( isset( $pagedatas['en']['url'] ) )		$langlink = $pagedatas['en']['url'].$plus_GET_str;
else										$langlink = $_hpRootDir;

	
if ( $paged >= 2 || $page >= 2 )	$title .= ' | ' . sprintf( __( 'Page %s','' ), max( $paged, $page ) );
if ( !(is_home() || is_front_page()) ){	//	ホーム以外
	$title .= '::'.get_bloginfo( 'name' , true );
}

echo sprintf('<?xml version="1.0" encoding="%s"?>'.PHP_EOL , get_bloginfo( 'charset' ) );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--[if lt IE 7 ]><html style="margin-top: 0 !important" class="ie ie6" lang="ja"> <![endif]-->
<!--[if IE 7 ]><html style="margin-top: 0 !important" class="ie ie7" lang="ja"> <![endif]-->
<!--[if IE 8 ]><html style="margin-top: 0 !important" class="ie ie8" lang="ja"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> <!--<![endif]-->
<html lang="ja">
<head>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-7LG8DRXTLP"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-7LG8DRXTLP');
</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Language" content="ja" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta http-equiv="imagetoolbar" content="no" />
<meta name="keywords" CONTENT="ゲノム,遺伝子,RNAI,バイオインフォマティックス,大学院,大学院大学,進化,DDBJ,エピジェネティックス,発生,博士研究員,募集,Postdoctoral Fellow,ゼブラフィッシュ,ショウジョウバエ,イネ,zebrafish,drosophila,oryza,rice,バイオリソース" />
<meta name="description" CONTENT="遺伝学の中核拠点として生命システムの解明を目指す先端研究や共同利用・共同研究を推進しています。また、総研大 先端学術院 遺伝学コースを併設し大学院教育を行っています。" />

	<title><?php wp_title('|', true, 'right'); ?>国立遺伝学研究所</title>
  <!--<title>国立遺伝学研究所</title>-->
<?php wp_head(); ?>    
	<!-- <script src="<?php echo $_hpRootDir; ?>js/jquery-1.11.0.min.js"></script> -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
	<script src="<?php echo $_hpRootDir; ?>js/default.js?20220204v1"></script>
	<script src="<?php echo $_hpRootDir; ?>js/scrollTopControl.js"></script>
    <script src="<?php echo $_hpRootDir; ?>js/footerFixed.js"></script>
    <script src="<?php echo $_hpRootDir; ?>js/yearSelect.js"></script>
    <script src="<?php echo $_hpRootDir; ?>js/navibutton.js"></script>
    <script src="<?php echo $_hpRootDir; ?>js/globalnavi.js?20220105v1"></script>
<!--	<script src="<?php echo $_hpRootDir; ?>js/smoothover.js"></script>	-->
      
<?php
	if( is_home() || is_front_page() ){
?>
	<script src="<?php echo $_hpRootDir; ?>js/jquery.bxslider.js"></script>
	<script src="<?php echo $_hpRootDir; ?>js/index.js?20220204v1"></script>
<!--[if lt IE 8 ]><link rel="stylesheet" type="text/css" href="<?php echo $_hpRootDir; ?>css/ol_index.css" media="screen,print" /> <![endif]-->
<!--[if (gte IE 8)]><link rel="stylesheet" type="text/css" href="<?php echo $_hpRootDir; ?>css/index.css" media="screen,print" /> <![endif]-->
<!--[if !IE]><!-->
     <link rel="stylesheet" type="text/css" href="<?php echo $_hpRootDir; ?>css/index_ja.css?20220204v1" media="screen,print" />
<!--<![endif]-->
<?php
	}
?>

	<link rel="start" href="/" title="TopPage" />
<!--[if lt IE 9 ]>
	<link rel="stylesheet" type="text/css" href="<?php echo $_hpRootDir; ?>css/ol_common.css" media="screen,print" />
	<link rel="stylesheet" type="text/css" href="<?php echo $_hpRootDir; ?>css/ol_navigation.css" media="screen,print" />
<![endif]-->
<!--[if (gte IE 9)]>
	<link rel="stylesheet" type="text/css" href="<?php echo $_hpRootDir; ?>css/common_ja.css" media="screen,print" />
	<link rel="stylesheet" type="text/css" href="<?php echo $_hpRootDir; ?>css/navigation.css" media="screen,print" />
<![endif]-->
<!--[if !IE]><!-->
	<link rel="stylesheet" type="text/css" href="<?php echo $_hpRootDir; ?>css/common_ja.css?20220105v1" media="screen,print" />
	<link rel="stylesheet" type="text/css" href="<?php echo $_hpRootDir; ?>css/navigation.css" media="screen,print" />
	<link rel="stylesheet" type="text/css" href="<?php echo $_hpRootDir; ?>css/add_style.css?20220204v1" media="screen,print" />
<!--<![endif]-->

	<meta name="viewport" content="width=device-width,user-scalable=no,maximum-scale=1" />
    <meta name="viewport" content="width=device-width,user-scalable=yes" />
<!--[if lt IE 9]>
	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
<![endif]-->
<link rel="shortcut icon" type="image/vnd.microsoft.icon" href="<?php echo $_hpRootDir; ?>favicon.ico" />

<link rel="apple-touch-icon" href="<?php echo $_hpRootDir; ?>apple-touch-icon.png" />

<!-- google font -->
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&display=swap" rel="stylesheet">

</head>
<body <?php body_class( 'ja' ); ?> class="ja <?php echo esc_attr( $post_name ); ?>">

<!-- <div id="js-loader" class="loader"></div> -->

<!-- Facebook Page Plugin(JavaScript SDK) -->
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/ja_JP/sdk.js#xfbml=1&version=v2.4";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<!-- /Facebook Page Plugin(JavaScript SDK) -->

<div id="site-base">
	
	<div class="mainWrapp">
		
		<!-- sp時のハンバーガーアイコン -->
		<div class="NaviButton"><span></span><span></span><span></span></div>

		<div id="header" class="autoWrapp">
			<div id="logo">
				<h1>
					<a href="<?php echo home_url('/'); ?>">
						<img src="<?php echo $_hpRootDir; ?>images/common/ja/h1.gif" alt="国立遺伝学研究所" />
					</a>
				</h1>
			</div>

			<div class="header-ChangeButtons">
				<a class="_access" href="<?php echo $_hpRootDir; ?>ja/about-nig/access_ja">
					<img src="<?php echo $_hpRootDir; ?>images/common/header_button_access.svg" alt="アクセス">
				</a>
				<a class="_now" href="###">
					<img src="<?php echo $_hpRootDir; ?>images/common/header_button_jp_now.svg" alt="Japanese">
				</a>
				<a class="_change" href="<?php echo $langlink; ?>">
					<img src="<?php echo $_hpRootDir; ?>images/common/header_button_en.svg" alt="English">
				</a>
			<!-- </div> divの閉じタグが欠けているのが前提で組まれている -->
		</div>
		
	</div>
	<div class="header-spacer"></div>
</div>
<div class="mainWrapp bgGl" id="headMenuWrap">
	<div id="navigation" class="autoWrapp">

		<!-- ハンバーガーメニュー開いた際のアクセスリンクボタンと日英切り替えボタン -->
		<div class="hm-changeButtons">
			<a class="_access" href="<?php echo $_hpRootDir; ?>ja/about-nig/access_ja">
				<img src="<?php echo $_hpRootDir; ?>images/common/header_button_access.svg" alt="アクセス">
			</a>
			<a class="_now" href="###">
				<img src="<?php echo $_hpRootDir; ?>images/common/hm_jp_now.svg" alt="Japanese">
			</a>
			<a class="_change" href="<?php echo $langlink; ?>">
				<img src="<?php echo $_hpRootDir; ?>images/common/hm_en.svg" alt="English">
			</a>
		</div>

		<div class="navSerachWrap">

<?php
	insert_custom_tmpl();
	//	カスタムメニュー読込み
	$hdMenuOption = array(
				'theme_location'  => 'headmenu',
				'menu'            => 'hdmenu_ja',
				'container'       => 'div',
				'container_class' => 'auto-width-wrap',
				'container_id'    => 'navigation',
				'menu_class'      => 'mmm',
				'menu_id'         => '',
				'echo'            => true,
				'fallback_cb'     => 'wp_page_menu',
				'before'          => '',
				'after'           => '',
				'link_before'     => '',
				'link_after'      => '',
				'items_wrap'      => '<ul id="main-nav">%3$s</ul>',
				'depth'           => 0,
				'walker'          => ''
			);
	 wp_nav_menu( $hdMenuOption );
?>


			<div class="navi-SearchBox">
				<form action="<?php bloginfo('url'); ?>/googlesearch_ja" name="search_form" id="search_form">
					<input id="i_search_input" type="text"  name="q" value="<?php echo htmlspecialchars( isset( $_GET['q'] ) ? (string) $_GET['q'] : '' ); ?>" class="_keyword" />
					<input type="image" value="検索" src="<?php echo $_hpRootDir; ?>images/common/search.svg" class="_search" />
				</form>
			</div>
		</div>
	</div>
	

</div>
	

