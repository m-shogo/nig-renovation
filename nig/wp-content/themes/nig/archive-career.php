<?php
$locale = get_locale();
$lang = explode("_", $locale)[0];
$front_page_id = get_option('page_on_front');
$lang_path = '';
if ($lang !== 'ja') {
  $lang_path = '/' . $lang;
}
global $post;
get_header(); ?>
  <main id="global_contents" class="global_contents" itemscope itemprop="mainContentOfPage">
    <section>
      <?php get_template_part( 'visual' ); ?>
      <div class="content_inner">
        <div class="gc_main _oneColumn">
          <?php
            if (empty($taxonomy)) {
              $taxonomy = '_cat';
            }
            $postType = esc_html(get_post_type_object(get_post_type())->name);
            $postType_cat = $postType . $taxonomy;
            $terms = get_terms(array(
              'taxonomy'   => $postType_cat,
              'hide_empty' => false,
            ));
            if ($terms && !is_wp_error($terms)): ?>
              <div class="module_career">
                <div class="block-editor_wrap">
                  <?php foreach ($terms as $term): ?>
                    <?php
                    $parent = $term->parent;
                    if ($parent) {
                      $parent_term = get_term($parent);
                      if (!is_wp_error($parent_term)) {
                        $parent_slug = $parent_term->slug;
                        $parent_slug = ' _' . $parent_slug;
                      }
                    }
                    if($term->slug != 'jobs-past') {
                      echo '<h3 class="wp-block-heading ' . esc_html($term->slug) . ($parent ? $parent_slug : '') . '">' . esc_html($term->name) . '</h3>';
                    }
                    $arg = array(
                      'suppress_filters' => false, //言語別設定
                      'lang' => $lang, //言語別設定
                      'post_type' => 'career',
                      'posts_per_page' => -1,
                      'has_password' => false,
                      'orderby' => 'date',
                      'order' => 'DESC',
                      'tax_query'        => array(
                        // ★ 必須：2つの条件を「かつ」で結合
                        'relation' => 'AND',

                        // ★ 条件①: 現在のタームに属する投稿だけ取得
                        array(
                          'taxonomy' => $postType_cat,
                          'field'    => 'slug',
                          'terms'    => $term->slug,
                          'operator' => 'IN',
                        ),

                        // ★ 条件②: jobs-past が付いている投稿は除外
                        array(
                          'taxonomy' => $postType_cat,
                          'field'    => 'slug',
                          'terms'    => array('jobs-past'),
                          'operator' => 'NOT IN',
                        ),
                      ),
                    );
                    $posts = get_posts($arg);
                    if ($posts): ?>
                        <?php get_template_part( 'list','career' ); ?>
                    <?php else: ?>
                      <?php if($term->slug != 'jobs-past'): ?>
                        <p><?php echo $lang === 'en' ? 'We are not currently hiring.' : 'ただいま募集していません。'; ?></p>
                      <?php endif; ?>
                    <?php endif; ?>
                  <?php endforeach; ?>
                </div>
              </div>
            <?php endif; //$terms ?>
          <div class="block-editor_wrap _career">
            <ul class="module_pager-02">
              <li class="back"><a href="<?php echo $lang === 'en' ? '/en' : ''; ?>/career/category/jobs-past/"><span><?php echo $lang === 'en' ? 'Closed recruitment information.' : '終了した募集情報'; ?></span></a></li>
            </ul>
          </div>
        </div>
      </div>
    </section>
  </main>
<?php get_footer(); ?>