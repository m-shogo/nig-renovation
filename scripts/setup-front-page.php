<?php
/**
 * 英日のフロントページを作り、TOP 用 ACF だけを入れる。
 * docker compose exec -u www-data wordpress wp eval-file /scripts/setup-front-page.php --path=/var/www/html/nig
 */

if ( ! function_exists( 'acf_import_field_group' ) ) {
	fwrite( STDERR, "ACF が有効ではありません\n" );
	exit( 1 );
}

$json = get_stylesheet_directory() . '/acf/top.json';
$groups = json_decode( file_get_contents( $json ), true );
if ( ! is_array( $groups ) ) {
	fwrite( STDERR, "acf/top.json を読めません\n" );
	exit( 1 );
}
foreach ( $groups as $group ) {
	acf_import_field_group( $group );
	echo "acf {$group['title']}\n";
}

function nig_renewal_ensure_front_page( $lang, $title ) {
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
	$id = wp_insert_post( array(
		'post_type'   => 'page',
		'post_status' => 'publish',
		'post_title'  => $title,
		'post_name'   => 'top',
	) );
	if ( ! $id || is_wp_error( $id ) ) {
		fwrite( STDERR, "ページを作れません\n" );
		exit( 1 );
	}
	update_post_meta( $id, '_wp_page_template', 'original-frontPage.php' );
	return (int) $id;
}

$en = nig_renewal_ensure_front_page( 'en', 'National Institute of Genetics' );
do_action( 'wpml_set_element_language_details', array(
	'element_id'           => $en,
	'element_type'         => 'post_page',
	'trid'                 => apply_filters( 'wpml_element_trid', false, $en, 'post_page' ),
	'language_code'        => 'en',
	'source_language_code' => null,
) );

$ja = nig_renewal_ensure_front_page( 'ja', '国立遺伝学研究所' );
$trid = apply_filters( 'wpml_element_trid', false, $en, 'post_page' );
do_action( 'wpml_set_element_language_details', array(
	'element_id'           => $ja,
	'element_type'         => 'post_page',
	'trid'                 => $trid,
	'language_code'        => 'ja',
	'source_language_code' => 'en',
) );

update_option( 'show_on_front', 'page' );
update_option( 'page_on_front', $en );
echo "en=$en ja=$ja front=page\n";
