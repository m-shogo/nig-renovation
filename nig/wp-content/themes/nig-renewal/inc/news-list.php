<?php
/**
 * 旧 listlink_box を、新しい module_newsList-01 の1行にする。
 * ページャーは paginate_links の li に、common.js が求める class を付ける。
 */

function nig_renewal_news_list_open() {
	echo '<div class="module_newsList-01">';
	$GLOBALS['nig_renewal_news_list_open'] = true;
}

function nig_renewal_news_list_close() {
	if ( empty( $GLOBALS['nig_renewal_news_list_open'] ) ) {
		return;
	}
	echo '</div>';
	$GLOBALS['nig_renewal_news_list_open'] = false;
}

function nig_renewal_news_labels( $icon_html ) {
	$icon_html = (string) $icon_html;
	if ( $icon_html === '' || ! preg_match_all( '/<span\b([^>]*)>(.*?)<\/span>/s', $icon_html, $matches, PREG_SET_ORDER ) ) {
		return '';
	}
	$out = '';
	foreach ( $matches as $span ) {
		$text = trim( wp_strip_all_tags( $span[2] ) );
		if ( $text === '' ) {
			continue;
		}
		$class = 'label';
		if ( strpos( $span[1], 'icon_past' ) !== false ) {
			$class .= ' jobs-past';
		}
		$out .= '<span class="' . esc_attr( $class ) . '">' . esc_html( $text ) . '</span>';
	}
	return $out;
}

function nig_renewal_news_article( $args = array(), $echo = true ) {
	$args = wp_parse_args(
		$args,
		array(
			'date'     => '',
			'datetime' => '',
			'icons'    => '',
			'prefix'   => '',
			'title'    => '',
			'url'      => '',
		)
	);

	$date = $args['date'] !== '' ? $args['date'] : get_the_date( 'Y.m.d' );
	$datetime = $args['datetime'] !== '' ? $args['datetime'] : get_the_date( 'Y-m-d' );
	$url = $args['url'] !== '' ? $args['url'] : get_permalink();
	$title = $args['title'] !== '' ? $args['title'] : get_the_title();
	$prefix = wp_kses( $args['prefix'], array( 'br' => array() ) );
	$labels = nig_renewal_news_labels( $args['icons'] );

	$html  = '<article>';
	$html .= '<a href="' . esc_url( $url ) . '">';
	$html .= '<div class="head">';
	$html .= '<p class="date"><time datetime="' . esc_attr( $datetime ) . '">' . esc_html( wp_strip_all_tags( $date ) ) . '</time></p>';
	if ( $labels !== '' ) {
		$html .= '<p class="category _cat">' . $labels . '</p>';
	}
	$html .= '</div>';
	$html .= '<div class="body"><h3 class="title">' . $prefix . esc_html( wp_strip_all_tags( $title ) ) . '</h3></div>';
	$html .= '</a></article>';

	if ( $echo ) {
		echo $html;
	}
	return $html;
}

function nig_renewal_pager_markup( $html ) {
	if ( ! is_string( $html ) || strpos( $html, 'page-numbers' ) === false ) {
		return $html;
	}

	$html = preg_replace_callback(
		'/<li>(\s*)<((?:a|span)\b[^>]*\bclass=")([^"]*)"/',
		function ( $match ) {
			$classes = $match[3];
			$li      = 'li';
			if ( preg_match( '/\bcurrent\b/', $classes ) ) {
				$li = 'li class="current"';
			} elseif ( preg_match( '/\bprev\b/', $classes ) ) {
				$li = 'li class="prev"';
			} elseif ( preg_match( '/\bnext\b/', $classes ) ) {
				$li = 'li class="next"';
			}
			return '<' . $li . '>' . $match[1] . '<' . $match[2] . $classes . '"';
		},
		$html
	);

	return str_replace(
		array( '◀', '▶︎', '▶' ),
		'<i class="fa-solid fa-arrow-right"></i>',
		$html
	);
}
add_filter( 'paginate_links_output', 'nig_renewal_pager_markup' );

function nig_renewal_slash_date( $value ) {
	$text = trim( wp_strip_all_tags( (string) $value ) );
	if ( preg_match( '/^(\d{4})\/(\d{1,2})\/(\d{1,2})$/', $text, $match ) ) {
		return sprintf( '%04d.%02d.%02d', (int) $match[1], (int) $match[2], (int) $match[3] );
	}
	return $text;
}

function nig_renewal_post_pager( $back_url, $back_label ) {
	if ( ! $back_url ) {
		return;
	}
	$lang = function_exists( 'nig_renewal_current_lang' ) ? nig_renewal_current_lang() : 'en';
	$prev = get_previous_post( true );
	$next = get_next_post( true );
	echo '<ul class="module_pager-02">';
	if ( $prev instanceof WP_Post ) {
		echo '<li class="prev"><a href="' . esc_url( get_permalink( $prev ) ) . '"><span>' . esc_html( $lang === 'ja' ? '前へ' : 'previous' ) . '</span></a></li>';
	}
	echo '<li class="back"><a href="' . esc_url( $back_url ) . '"><span>' . esc_html( $back_label ) . '</span><i class="fa-regular fa-rotate-left"></i></a></li>';
	if ( $next instanceof WP_Post ) {
		echo '<li class="next"><a href="' . esc_url( get_permalink( $next ) ) . '"><span>' . esc_html( $lang === 'ja' ? '次へ' : 'next' ) . '</span></a></li>';
	}
	echo '</ul>';
}
