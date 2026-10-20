<?php
$locale = get_locale();
$lang = explode("_", $locale)[0];
$front_page_id = get_option('page_on_front');
$lang_path = '';
if ($lang !== 'ja') {
  $lang_path = '/' . $lang;
}
global $post;
?>
<?php if (get_current_post_type() === 'post'||is_category()): ?>
  <nav class="local_navigation" id="local_navigation">
    <div class="module_category-01">
      <div class="wrap">
        <a href="<?php echo $lang === 'en' ? '/en' : ''; ?>/<?php $postTopId = get_option( 'page_for_posts'); echo get_post_field('post_name', $postTopId); ?>/"><span>All</span></a>
        <?php
        // 親カテゴリーのものだけを一覧で取得
        $args = array(
          'post_type' => 'post', // 投稿タイプの指定
          'order' => 'ASC',
          'hide_empty' => true, // 投稿がないカテゴリを出すかどうか
          'parent' => 0,
        );
        $categories = get_categories( $args );
        ?>
        <?php foreach( $categories as $category ) : ?>
          <a href="<?php echo get_category_link( $category->term_id ); ?>"><span><?php echo $category->name; ?></span></a>
        <?php endforeach; ?>
      </div>
    </div>
  </nav>
<?php else: // その他カスタム投稿 ?>
  <?php
  // 投稿タイプやタクソノミー（分類）の取得
  $postType = '';
  if (is_post_type_archive() || is_single()) {
    $current_post_type = get_post_type();
    $post_type_object = get_post_type_object($current_post_type);
    $postType = $post_type_object ? esc_html($post_type_object->name) : '';
  } elseif (is_tax()) {
    $taxonomy = get_query_var('taxonomy');
    $tax_object = get_taxonomy($taxonomy);
    $postType = ($tax_object && isset($tax_object->object_type[0])) ? $tax_object->object_type[0] : '';
  }

  // 投稿タイプが取得できない場合、URLから推測
  if (!$postType) {
    $current_url = $_SERVER['REQUEST_URI'];
    $url_parts = explode('/', trim($current_url, '/'));
    $url_segment = isset($url_parts[0]) ? $url_parts[0] : '';
    
    if ($url_segment === 'news') {
      $postType = 'post';
    } elseif (post_type_exists($url_segment)) {
      $postType = $url_segment;
    } else {
      $postType = 'post'; // デフォルト
    }
  }

  $postType_cat = $postType . '_cat';
  $postType_tag = $postType . '_tag';

  // 投稿タイプのオブジェクトを取得
  $post_type_obj = get_post_type_object($postType);
  //var_dump($post_type_obj); // デバッグ用
  // ラベル（表示名）とスラッグ（URL用の英語名）を取得
  $postType_label = $post_type_obj ? $post_type_obj->label : '投稿';
  $postType_slug = $post_type_obj ? $post_type_obj->name : 'post';
?>
  <nav class="local_navigation" id="local_navigation">
    <div class="module_category-01">
      <div class="wrap">
        <?php if(!is_tax('highlights_cat')): ?>
          <a href="<?php echo $lang === 'en' ? '/en' : ''; ?>/<?php echo $postType; ?>/"><span>All</span></a>
        <?php endif; ?>
        <?php
        $taxonomies = $postType_cat;
        $args = array(
          'orderby' => 'id',
          'order' => 'ASC',
          'hide_empty' => true, // 投稿がないカテゴリを出すかどうか
          'parent' => 0,
        );
        $terms = get_terms( $taxonomies, $args );
        ?>
        <?php foreach( $terms as $term ) : ?>
          <?php $term_link = get_term_link( $term->term_id ); ?>
          <?php if ( ! is_wp_error( $term_link ) ) : ?>
            <a href="<?php echo esc_url( $term_link ); ?>"><span><?php echo esc_html( $term->name ); ?></span></a>
          <?php endif; ?>
        <?php endforeach; ?>
      </div>
    </div>
  </nav>
<?php endif; ?>