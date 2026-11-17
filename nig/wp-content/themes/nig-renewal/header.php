<?php
global $page, $paged, $post, $cat;
global $_hpRootDir;

if ( is_home() || is_front_page() ) {
	$title = get_bloginfo( 'name', true ) . '::' . get_bloginfo( 'description', 'display' );
} elseif ( is_single() || is_page() ) {
	$title = get_the_title();
} elseif ( is_category() ) {
	$dat   = get_category( $cat );
	$title = $dat ? $dat->name : '';
} elseif ( is_search() ) {
	$title = 'Search';
} elseif ( is_archive() ) {
	$title = 'archive';
} else {
	$title = 'notfound';
}

if ( $paged >= 2 || $page >= 2 ) {
	$title .= ' | ' . sprintf( __( 'Page %s', '' ), max( $paged, $page ) );
}
if ( ! ( is_home() || is_front_page() ) ) {
	$title .= '::' . get_bloginfo( 'name', true );
}

$lang          = nig_renewal_current_lang();
$logo          = $lang === 'ja' ? 'logo.svg' : 'logo-en.svg';
$search_label  = $lang === 'ja' ? '検索する' : 'Search';
$access_label  = $lang === 'ja' ? 'アクセス' : 'Access';
$switch_label  = $lang === 'ja' ? 'English' : 'Japanese';
$search_action = trailingslashit( $_hpRootDir ) . 'googlesearch_ja';
$menu_children = nig_renewal_menu_children( nig_renewal_header_menu_name() );
$body_extra    = array();
if ( $lang === 'en' ) {
	$body_extra[] = 'en-US';
}
?>
<!DOCTYPE html>
<html lang="<?php echo esc_attr( $lang ); ?>" dir="ltr">
<head>
<script async src="https://www.googletagmanager.com/gtag/js?id=G-7LG8DRXTLP"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'G-7LG8DRXTLP');
</script>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width">
<?php if ( $lang === 'ja' ) : ?>
<meta name="keywords" content="ゲノム,遺伝子,RNAI,バイオインフォマティックス,大学院,大学院大学,進化,DDBJ,エピジェネティックス,発生,博士研究員,募集,Postdoctoral Fellow,ゼブラフィッシュ,ショウジョウバエ,イネ,zebrafish,drosophila,oryza,rice,バイオリソース">
<meta name="description" content="遺伝学の中核拠点として生命システムの解明を目指す先端研究や共同利用・共同研究を推進しています。また、総研大 先端学術院 遺伝学コースを併設し大学院教育を行っています。">
<title><?php wp_title( '|', true, 'right' ); ?>国立遺伝学研究所</title>
<?php else : ?>
<meta name="keywords" content="genome,gene,RNAi,bioinformatics,Ph.D,graduate school,genetics,evolution,DDBJ,epigenetics,development,zebrafish,drosophila,rice,oriza,postdoctoral fellow,bioresource">
<meta name="description" content="NIG is the research institute on genetics. Our mission is to provide infrastructure and opportunities for international collaboration, to train young scientists, and to build new frontiers of life sciences.">
<title><?php echo esc_html( $title ); ?></title>
<?php endif; ?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,700;1,400&family=Zen+Kaku+Gothic+New:wght@400;500;700&display=swap" rel="stylesheet">
<link rel="icon" href="<?php echo esc_url( get_template_directory_uri() . '/images/common/favicon.ico' ); ?>">
<link rel="icon" type="image/svg+xml" href="<?php echo esc_url( get_template_directory_uri() . '/images/common/favicon.svg' ); ?>">
<script>
(function() {
  var MOBILE_BREAKPOINT = 768;
  function setViewport() {
    var meta = document.querySelector("meta[name='viewport']");
    if (!meta) return;
    if (window.innerWidth <= MOBILE_BREAKPOINT) {
      meta.setAttribute('content', 'width=device-width, initial-scale=1, viewport-fit=cover');
    } else {
      meta.setAttribute('content', 'width=1280');
    }
  }
  var timer = null;
  window.addEventListener('DOMContentLoaded', setViewport, false);
  window.addEventListener('resize', function() {
    clearTimeout(timer);
    timer = setTimeout(setViewport, 150);
  }, false);
  window.addEventListener('orientationchange', setViewport, false);
})();
</script>
<?php wp_head(); ?>
</head>
<body <?php body_class( $body_extra ); ?>>
<div class="headerNavigation">
  <div class="headerNavigationInner">
    <header id="global_header" class="global_header">
      <div class="inner">
        <?php if ( is_front_page() || is_home() ) : ?>
        <h1 class="gh_logo"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo esc_url( get_template_directory_uri() . '/images/common/' . $logo ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"></a></h1>
        <?php else : ?>
        <p class="gh_logo"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo esc_url( get_template_directory_uri() . '/images/common/' . $logo ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"></a></p>
        <?php endif; ?>
        <button type="button" class="gh_menu" id="gh_menu"><span class="icon"></span></button>
      </div>
    </header>
    <nav id="global_navigation" class="global_navigation">
      <ul class="gn_links-01 module_menu-01">
        <?php nig_renewal_render_sp_menu( $menu_children ); ?>
        <li><a class="gnl_title mm_title" href="<?php echo esc_url( nig_renewal_lang_url() ); ?>"><span><?php echo esc_html( $switch_label ); ?></span></a></li>
      </ul>
      <div class="gn_search module_search-01">
        <form class="gns_form ms_form" role="search" method="get" action="<?php echo esc_url( $search_action ); ?>">
          <input class="ms_input" type="search" value="<?php echo esc_attr( isset( $_GET['q'] ) ? (string) $_GET['q'] : '' ); ?>" name="q" placeholder="<?php echo esc_attr( $search_label ); ?>">
          <button type="submit" class="ms_button"><span></span></button>
        </form>
      </div>
      <div class="submenu">
        <button id="search" class="search" type="button"><i class="fa-light fa-magnifying-glass"></i><span><?php echo esc_html( $search_label ); ?></span></button>
        <a href="<?php echo esc_url( nig_renewal_access_url() ); ?>"><span><?php echo esc_html( $access_label ); ?></span></a>
        <a href="<?php echo esc_url( nig_renewal_lang_url() ); ?>"><span><?php echo esc_html( $switch_label ); ?></span></a>
      </div>
    </nav>
    <div class="module_menu-01">
      <div class="inner">
        <?php nig_renewal_render_pc_menu( $menu_children ); ?>
      </div>
    </div>
  </div>
</div>
<div class="overlay" id="overlay"></div>
<div class="gh_search module_search-pc">
  <div class="module_search-01">
    <form class="ms_form" role="search" method="get" action="<?php echo esc_url( $search_action ); ?>">
      <input class="ms_input" type="search" name="q" placeholder="<?php echo esc_attr( $search_label ); ?>">
      <button class="ms_button" type="submit"><span></span></button>
    </form>
  </div>
  <div id="gns_close" class="close"><button id="searchClose" type="button"></button></div>
</div>
<div class="global_wrapper">
<?php if ( nig_renewal_wrap_lower_content() ) : ?>
<div id="global_contents">
<?php nig_renewal_render_lower_heading(); ?>
<?php endif; ?>
