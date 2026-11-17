<?php
/**
 * 新しい CSS と JS。旧 /nig/css と /nig/js は header.php から出さない。
 * home.js と home.css はフロントテンプレートだけ。
 */

add_filter(
	'body_class',
	function ( $classes ) {
		if ( nig_renewal_current_lang() === 'en' && ! in_array( 'en-US', $classes, true ) ) {
			$classes[] = 'en-US';
		}
		return $classes;
	}
);

add_action(
	'wp_enqueue_scripts',
	function () {
		wp_deregister_script( 'jquery' );
		wp_register_script( 'jquery', get_theme_file_uri( '/js/jquery-3.7.1.min.js' ), array(), '3.7.1', false );
		wp_enqueue_script( 'jquery' );

		wp_enqueue_style( 'nig-renewal', get_theme_file_uri( '/css/style.css' ), array(), '20261117' );
		wp_enqueue_style( 'nig-renewal-add', get_theme_file_uri( '/css/add.css' ), array( 'nig-renewal' ), '20261117' );
		wp_enqueue_style( 'nig-renewal-modaal', get_theme_file_uri( '/css/modaal.min.css' ), array(), '20261117' );
		wp_enqueue_style( 'nig-renewal-fa', get_theme_file_uri( '/css/all.min.css' ), array(), '6.7.2' );

		wp_enqueue_script( 'nig-renewal-modaal', get_theme_file_uri( '/js/modaal.min.js' ), array( 'jquery' ), '20261117', true );
		wp_enqueue_script( 'nig-renewal-common', get_theme_file_uri( '/js/common.js' ), array( 'jquery', 'nig-renewal-modaal' ), '20261117', true );

		if ( nig_renewal_wrap_lower_content() ) {
			wp_enqueue_style( 'nig-renewal-legacy', get_theme_file_uri( '/css/legacy-content.css' ), array( 'nig-renewal' ), '20261117' );
		}
		if ( is_category() ) {
			global $_hpRootDir;
			wp_enqueue_script( 'nig-renewal-year-select', $_hpRootDir . 'js/yearSelect.js', array( 'jquery' ), '20261117', true );
		}
		if ( is_page_template( 'original-frontPage.php' ) ) {
			wp_enqueue_style( 'nig-renewal-swiper', get_theme_file_uri( '/css/swiper-bundle.min.css' ), array(), '20261117' );
			wp_enqueue_style( 'nig-renewal-home', get_theme_file_uri( '/css/home.css' ), array( 'nig-renewal' ), '20261117' );
			wp_enqueue_script( 'nig-renewal-swiper', get_theme_file_uri( '/js/swiper-bundle.min.js' ), array(), '20261117', true );
			wp_enqueue_script( 'nig-renewal-dualtab', get_theme_file_uri( '/js/jquery.dualtab.js' ), array( 'jquery' ), '20261117', true );
			wp_enqueue_script( 'nig-renewal-home', get_theme_file_uri( '/js/home.js' ), array( 'jquery', 'nig-renewal-swiper', 'nig-renewal-dualtab' ), '20261117', true );
		}
	},
	20
);
