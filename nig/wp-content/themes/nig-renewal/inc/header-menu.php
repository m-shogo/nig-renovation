<?php
/**
 * 今の hdmenu / hdmenu_ja を、新しいヘッダーの HTML に出す。
 * 項目の追加・削除・並べ替えはしない。
 */

function nig_renewal_current_lang() {
	if ( defined( 'ICL_LANGUAGE_CODE' ) && ICL_LANGUAGE_CODE ) {
		return ICL_LANGUAGE_CODE;
	}
	return 'en';
}

function nig_renewal_header_menu_name() {
	return nig_renewal_current_lang() === 'ja' ? 'hdmenu_ja' : 'hdmenu';
}

function nig_renewal_lang_url() {
	$target = nig_renewal_current_lang() === 'ja' ? 'en' : 'ja';
	if ( function_exists( 'icl_get_languages' ) ) {
		$langs = icl_get_languages( 'skip_missing=0' );
		if ( ! empty( $langs[ $target ]['url'] ) ) {
			return $langs[ $target ]['url'];
		}
	}
	return $target === 'ja' ? home_url( '/ja/' ) : home_url( '/' );
}

function nig_renewal_access_url() {
	$lang = nig_renewal_current_lang();
	$page = get_page_by_path( 'about-nig/access' );
	if ( $page ) {
		$id = apply_filters( 'wpml_object_id', $page->ID, get_post_type( $page ), true, $lang );
		if ( $id ) {
			$url = get_permalink( $id );
			if ( $url ) {
				return $url;
			}
		}
	}
	return home_url( $lang === 'ja' ? '/about-nig/access_ja' : '/about-nig/access' );
}

function nig_renewal_menu_children( $menu_name ) {
	$menu = wp_get_nav_menu_object( $menu_name );
	if ( ! $menu ) {
		return array();
	}
	$items = wp_get_nav_menu_items( $menu->term_id );
	if ( ! $items ) {
		return array();
	}
	$children = array();
	foreach ( $items as $item ) {
		$parent = (int) $item->menu_item_parent;
		if ( ! isset( $children[ $parent ] ) ) {
			$children[ $parent ] = array();
		}
		$children[ $parent ][] = $item;
	}
	return $children;
}

function nig_renewal_menu_label( $item ) {
	$text = preg_replace( '#<img\b[^>]*>#i', '', (string) $item->title );
	$text = wp_strip_all_tags( $text );
	$text = html_entity_decode( $text, ENT_QUOTES, 'UTF-8' );
	$text = trim( preg_replace( '/\s+/u', ' ', $text ) );
	return $text;
}

function nig_renewal_menu_href( $item ) {
	$url = isset( $item->url ) ? trim( (string) $item->url ) : '';
	if ( $url === '' || $url === '#' ) {
		return '#';
	}
	return $url;
}

function nig_renewal_item_target( $item ) {
	if ( ! empty( $item->target ) ) {
		return $item->target;
	}
	$host = wp_parse_url( nig_renewal_menu_href( $item ), PHP_URL_HOST );
	$site = wp_parse_url( home_url(), PHP_URL_HOST );
	if ( $host && $site && strcasecmp( $host, $site ) !== 0 ) {
		return '_blank';
	}
	return '';
}

function nig_renewal_anchor( $item, $class ) {
	$target = nig_renewal_item_target( $item );
	$extra  = $target ? ' target="' . esc_attr( $target ) . '" rel="noopener noreferrer"' : '';
	return '<a class="' . esc_attr( $class ) . '" href="' . esc_url( nig_renewal_menu_href( $item ) ) . '"' . $extra . '><span>' . esc_html( nig_renewal_menu_label( $item ) ) . '</span></a>';
}

function nig_renewal_render_branch( $item, $children ) {
	$kids = isset( $children[ (int) $item->ID ] ) ? $children[ (int) $item->ID ] : array();
	if ( $kids ) {
		echo '<li>';
		echo '<button class="gnl_button mm_button-01" type="button"><span>開閉</span></button>';
		echo nig_renewal_anchor( $item, 'gnl_title mm_title-01' );
		echo '<div class="gnl_wrapper mm_wrapper-02"><div class="gnl_inner mm_inner-02"><ul>';
		foreach ( $kids as $kid ) {
			echo '<li>' . nig_renewal_anchor( $kid, 'gnl_title mm_title-02' ) . '</li>';
		}
		echo '</ul></div></div></li>';
		return;
	}
	echo '<li>' . nig_renewal_anchor( $item, 'gnl_title mm_title-01' ) . '</li>';
}

function nig_renewal_render_panel_list( $item, $children ) {
	$kids = isset( $children[ (int) $item->ID ] ) ? $children[ (int) $item->ID ] : array();
	echo nig_renewal_anchor( $item, 'mm_title' );
	echo '<ul>';
	foreach ( $kids as $kid ) {
		nig_renewal_render_branch( $kid, $children );
	}
	echo '</ul>';
}

function nig_renewal_render_sp_menu( $children ) {
	$tops = isset( $children[0] ) ? $children[0] : array();
	foreach ( $tops as $item ) {
		$kids = isset( $children[ (int) $item->ID ] ) ? $children[ (int) $item->ID ] : array();
		if ( $kids ) {
			echo '<li class="_hasChild">';
			echo '<button class="gnl_button mm_button" type="button"><span>開閉</span></button>';
			echo nig_renewal_anchor( $item, 'gnl_title mm_title' );
			echo '<div class="gnl_wrapper mm_wrapper-01"><div class="gnl_inner mm_inner-01"><div class="gnl_title">';
			echo nig_renewal_anchor( $item, '' );
			echo '</div>';
			echo '<ul>';
			foreach ( $kids as $kid ) {
				nig_renewal_render_branch( $kid, $children );
			}
			echo '</ul></div></div></li>';
		} else {
			echo '<li>' . nig_renewal_anchor( $item, 'gnl_title mm_title' ) . '</li>';
		}
	}
}

function nig_renewal_render_pc_menu( $children ) {
	$tops = isset( $children[0] ) ? $children[0] : array();
	foreach ( $tops as $item ) {
		echo '<button class="title" type="button"><span>' . esc_html( nig_renewal_menu_label( $item ) ) . '</span></button>';
		echo '<div class="menuList" style="display: none;"><div class="menuListInner"><div class="mm_wrapper-01"><div class="mm_inner-01">';
		nig_renewal_render_panel_list( $item, $children );
		echo '</div></div></div></div>';
	}
}
