<?PHP


add_filter('getarchives_where', 'custom_archives_where', 10, 2);
add_filter('getarchives_join', 'custom_archives_join', 10, 2);

function custom_archives_join($x, $r) {
  global $wpdb;
  // wp_get_archives 経由では cat が無い呼び出しもある
  $cat_ID = isset( $r['cat'] ) ? $r['cat'] : null;
  if (isset($cat_ID)) {
    return $x . " INNER JOIN $wpdb->term_relationships ON ($wpdb->posts.ID = $wpdb->term_relationships.object_id) INNER JOIN $wpdb->term_taxonomy ON ($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)";
  } else {
    return $x;
  }
}

function custom_archives_where($x, $r) {
  global $wpdb;
  // wp_get_archives 経由では cat が無い呼び出しもある
  $cat_ID = isset( $r['cat'] ) ? $r['cat'] : null;
  if (isset($cat_ID)) {
    return $x . " AND $wpdb->term_taxonomy.taxonomy = 'category' AND $wpdb->term_taxonomy.term_id IN ($cat_ID)";
  } else {
    $x;
  }
}

function wp_get_cat_archives($opts, $cat) {
  $args = wp_parse_args($opts, array('echo' => '1')); // default echo is 1.
  $echo = $args['echo'] != '0'; // remember the original echo flag.
  $args['echo'] = 1;
  $args['cat'] = $cat;

  ob_start();
  wp_get_archives(build_query($args));
  $archives = ob_get_clean();
  $archs = explode('</li>', $archives);
  $links = array();

  foreach ($archs as $archive) {
    $link = preg_replace("/href='([^']+)'/", "href='$1?cat={$cat}'", $archive);
	$link = str_replace('年','/',$link);
 	$link = str_replace('月','',$link);
   array_push($links, $link);
  }
  $result = implode('</li>', $links);

  if ($echo) {
    echo $result;
  } else {
    return $result;
  }
}
?>