<?php
/**
 * 本番トップの表示から、英日の TOP 用 ACF を入れる。
 * docker compose exec -u www-data wordpress wp eval-file /scripts/import-top-acf.php --path=/var/www/html/nig
 */

if ( ! function_exists( 'update_field' ) ) {
	fwrite( STDERR, "ACF が有効ではありません\n" );
	exit( 1 );
}

add_filter( 'intermediate_image_sizes_advanced', '__return_empty_array' );
add_filter( 'big_image_size_threshold', '__return_false' );

function nig_front_id( $lang ) {
	$pages = get_posts( array(
		'post_type'        => 'page',
		'post_status'      => 'publish',
		'posts_per_page'   => -1,
		'suppress_filters' => true,
		'meta_key'         => '_wp_page_template',
		'meta_value'       => 'original-frontPage.php',
	) );
	foreach ( $pages as $page ) {
		$code = apply_filters( 'wpml_element_language_code', null, array(
			'element_id'   => $page->ID,
			'element_type' => 'post_page',
		) );
		if ( $code === $lang ) {
			return (int) $page->ID;
		}
	}
	fwrite( STDERR, "$lang のフロントページがありません\n" );
	exit( 1 );
}

function nig_html( $url ) {
	$response = wp_remote_get( $url, array( 'timeout' => 60 ) );
	if ( is_wp_error( $response ) ) {
		fwrite( STDERR, $response->get_error_message() . "\n" );
		exit( 1 );
	}
	return wp_remote_retrieve_body( $response );
}

function nig_xpath( $html ) {
	$dom = new DOMDocument();
	libxml_use_internal_errors( true );
	$dom->loadHTML( '<?xml encoding="UTF-8">' . $html );
	libxml_clear_errors();
	return new DOMXPath( $dom );
}

function nig_text( $node ) {
	if ( ! $node ) {
		return '';
	}
	return trim( preg_replace( '/\s+/u', ' ', $node->textContent ) );
}

function nig_one( DOMXPath $xp, DOMNode $ctx, $query ) {
	$list = $xp->query( $query, $ctx );
	return ( $list && $list->length ) ? $list->item( 0 ) : null;
}

function nig_abs( $url ) {
	$url = trim( html_entity_decode( (string) $url ) );
	if ( $url === '' || strpos( $url, 'data:' ) === 0 ) {
		return '';
	}
	if ( strpos( $url, '//' ) === 0 ) {
		return 'https:' . $url;
	}
	if ( preg_match( '#^https?://#', $url ) ) {
		return $url;
	}
	if ( $url[0] !== '/' ) {
		$url = '/' . $url;
	}
	return 'https://www.nig.ac.jp' . $url;
}

function nig_img_src( DOMElement $img ) {
	foreach ( array( 'data-src-img', 'data-src', 'src' ) as $attr ) {
		$src = nig_abs( $img->getAttribute( $attr ) );
		if ( $src ) {
			return $src;
		}
	}
	return '';
}

function nig_img( DOMXPath $xp, DOMNode $ctx, $class = '' ) {
	$query = $class
		? './/img[contains(concat(" ", normalize-space(@class), " "), " ' . $class . ' ")]'
		: './/img';
	$nodes = $xp->query( $query, $ctx );
	$fallback = '';
	foreach ( $nodes as $img ) {
		$src = nig_img_src( $img );
		if ( ! $src ) {
			continue;
		}
		if ( strpos( ' ' . $img->getAttribute( 'class' ) . ' ', ' ewww_webp ' ) === false ) {
			return $src;
		}
		if ( ! $fallback ) {
			$fallback = $src;
		}
	}
	return $fallback;
}

function nig_attach( $url, $parent ) {
	static $cache = array();
	$url = nig_abs( $url );
	if ( ! $url ) {
		return 0;
	}
	if ( isset( $cache[ $url ] ) ) {
		return $cache[ $url ];
	}
	$found = get_posts( array(
		'post_type'        => 'attachment',
		'post_status'      => 'inherit',
		'posts_per_page'   => 1,
		'fields'           => 'ids',
		'suppress_filters' => true,
		'meta_key'         => '_nig_renewal_source',
		'meta_value'       => $url,
	) );
	if ( $found ) {
		$cache[ $url ] = (int) $found[0];
		return $cache[ $url ];
	}
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';
	$tmp = download_url( $url, 60 );
	if ( is_wp_error( $tmp ) ) {
		echo "image fail $url\n";
		$cache[ $url ] = 0;
		return 0;
	}
	$name = basename( (string) parse_url( $url, PHP_URL_PATH ) );
	$id   = media_handle_sideload( array( 'name' => $name, 'tmp_name' => $tmp ), $parent );
	if ( is_wp_error( $id ) ) {
		@unlink( $tmp );
		echo "image fail $url\n";
		$cache[ $url ] = 0;
		return 0;
	}
	update_post_meta( $id, '_nig_renewal_source', $url );
	echo "image $id $name\n";
	$cache[ $url ] = (int) $id;
	return (int) $id;
}

function nig_blank( $anchor ) {
	return ( $anchor && $anchor->getAttribute( 'target' ) === '_blank' ) ? array( 'はい' ) : array();
}

function nig_rows( $html ) {
	$xp = nig_xpath( $html );
	$slider = array();
	foreach ( $xp->query( '//*[contains(concat(" ", normalize-space(@class), " "), " slider-slide ")]' ) as $slide ) {
		$pc = nig_img( $xp, $slide, '_over-TB' );
		$sp = nig_img( $xp, $slide, '_only-SP' );
		if ( $pc || $sp ) {
			$slider[] = array( 'img_pc' => $pc, 'img_sp' => $sp );
		}
	}
	$notices = array();
	foreach ( $xp->query( '//*[@id="top_notice-01"]//ul/li' ) as $li ) {
		$date = nig_text( nig_one( $xp, $li, './/*[contains(concat(" ", normalize-space(@class), " "), " date ")]' ) );
		$ymd  = '';
		if ( preg_match( '/(\d{4})\.(\d{1,2})\.(\d{1,2})/', $date, $m ) ) {
			$ymd = sprintf( '%04d%02d%02d', $m[1], $m[2], $m[3] );
		}
		$anchor = nig_one( $xp, $li, './/a' );
		$title  = nig_text( nig_one( $xp, $li, './/*[contains(concat(" ", normalize-space(@class), " "), " title ")]' ) );
		if ( ! $title ) {
			continue;
		}
		$notices[] = array(
			'date'   => $ymd,
			'label'  => nig_text( nig_one( $xp, $li, './/*[contains(concat(" ", normalize-space(@class), " "), " label ")]' ) ),
			'title'  => $title,
			'url'    => $anchor ? nig_abs( $anchor->getAttribute( 'href' ) ) : '',
			'target' => nig_blank( $anchor ),
		);
	}
	$scientists = array();
	foreach ( $xp->query( '//*[contains(@class,"scientists_swiper-container")]//li[contains(@class,"swiper-slide")]' ) as $li ) {
		$title = nig_text( nig_one( $xp, $li, './/h3' ) );
		if ( ! $title ) {
			continue;
		}
		$anchor = nig_one( $xp, $li, './/a' );
		$scientists[] = array(
			'img'   => nig_img( $xp, $li ),
			'title' => $title,
			'theme' => nig_text( nig_one( $xp, $li, './/*[contains(concat(" ", normalize-space(@class), " "), " theme ")]' ) ),
			'url'   => $anchor ? nig_abs( $anchor->getAttribute( 'href' ) ) : '',
		);
	}
	$cards = array();
	foreach ( $xp->query( '//*[@id="top_scientists-01"]//ul[contains(@class,"list")]/li' ) as $li ) {
		$title = nig_text( nig_one( $xp, $li, './/h3' ) );
		if ( ! $title ) {
			continue;
		}
		$anchor = nig_one( $xp, $li, './/a' );
		$cards[] = array(
			'img'   => nig_img( $xp, $li ),
			'genre' => nig_text( nig_one( $xp, $li, './/*[contains(concat(" ", normalize-space(@class), " "), " genre ")]' ) ),
			'title' => $title,
			'text'  => nig_text( nig_one( $xp, $li, './/*[contains(concat(" ", normalize-space(@class), " "), " text ")]' ) ),
			'url'   => $anchor ? nig_abs( $anchor->getAttribute( 'href' ) ) : '',
		);
	}
	$recommends = array();
	foreach ( $xp->query( '//*[@id="top_recommends-01"]//ul/li' ) as $li ) {
		$title = nig_text( nig_one( $xp, $li, './/*[contains(concat(" ", normalize-space(@class), " "), " title ")]' ) );
		if ( ! $title ) {
			continue;
		}
		$anchor = nig_one( $xp, $li, './/a' );
		$recommends[] = array(
			'img'    => nig_img( $xp, $li ),
			'title'  => $title,
			'url'    => $anchor ? nig_abs( $anchor->getAttribute( 'href' ) ) : '',
			'target' => nig_blank( $anchor ),
		);
	}
	return compact( 'slider', 'notices', 'scientists', 'cards', 'recommends' );
}

function nig_save_repeater( $name, $rows, $post_id ) {
	$field = acf_get_field( $name );
	if ( ! $field || $field['type'] !== 'repeater' ) {
		fwrite( STDERR, "$name が繰り返しフィールドではありません\n" );
		exit( 1 );
	}
	$subs = array();
	foreach ( $field['sub_fields'] as $sub ) {
		$subs[ $sub['name'] ] = $sub['key'];
	}
	for ( $i = 0; $i < 40; $i++ ) {
		foreach ( $subs as $sub_name => $key ) {
			delete_post_meta( $post_id, "{$name}_{$i}_{$sub_name}" );
			delete_post_meta( $post_id, "_{$name}_{$i}_{$sub_name}" );
		}
	}
	$value = array();
	foreach ( array_values( $rows ) as $row ) {
		$stored = array();
		foreach ( $subs as $sub_name => $key ) {
			if ( ! array_key_exists( $sub_name, $row ) ) {
				continue;
			}
			$item = $row[ $sub_name ];
			if ( $item === array() || $item === null || $item === 0 || $item === '0' ) {
				$item = '';
			}
			$stored[ $key ] = $item;
		}
		$value[] = $stored;
	}
	update_post_meta( $post_id, $name, $value );
	update_post_meta( $post_id, '_' . $name, $field['key'] );
}

$sources = array(
	'ja' => 'https://www.nig.ac.jp/',
	'en' => 'https://www.nig.ac.jp/en/',
);
foreach ( $sources as $lang => $url ) {
	$id   = nig_front_id( $lang );
	$rows = nig_rows( nig_html( $url ) );
	echo "$lang page=$id slides=" . count( $rows['slider'] ) . ' notices=' . count( $rows['notices'] ) . ' scientists=' . count( $rows['scientists'] ) . ' cards=' . count( $rows['cards'] ) . ' recommends=' . count( $rows['recommends'] ) . "\n";
	if ( ! $rows['slider'] || ! $rows['notices'] || ! $rows['scientists'] || ! $rows['recommends'] ) {
		fwrite( STDERR, "$lang の取得が足りません\n" );
		exit( 1 );
	}
	$slider = nig_rows_images( $rows['slider'], $id, array( 'img_pc', 'img_sp' ) );
	$scientists = nig_rows_images( $rows['scientists'], $id, array( 'img' ) );
	$cards = nig_rows_images( $rows['cards'], $id, array( 'img' ) );
	$recommends = nig_rows_images( $rows['recommends'], $id, array( 'img' ) );
	nig_save_repeater( 'top_slider-01', $slider, $id );
	update_field( 'top_notice_select', 1, $id );
	nig_save_repeater( 'top_notice-01', $rows['notices'], $id );
	nig_save_repeater( 'top_scientists-01', $scientists, $id );
	nig_save_repeater( 'top_scientists-02', $cards, $id );
	nig_save_repeater( 'top_recommends-01', $recommends, $id );
	echo "$lang saved\n";
}

function nig_rows_images( $rows, $parent, $image_keys ) {
	$out = array();
	foreach ( $rows as $row ) {
		foreach ( $image_keys as $key ) {
			$row[ $key ] = nig_attach( $row[ $key ], $parent );
		}
		$out[] = $row;
	}
	return $out;
}
