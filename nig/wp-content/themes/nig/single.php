<?php
$locale = get_locale();
$lang = explode("_", $locale)[0];
$front_page_id = get_option('page_on_front');
$lang_path = '';
if ($lang !== 'ja') {
  $lang_path = '/' . $lang;
}
global $post;
// URLタイプのリダイレクト処理
if(get_field('post_type') === 'url'):
  $postType_url = get_field('postType_url');
  
  // URLのバリデーション
  if($postType_url && filter_var($postType_url, FILTER_VALIDATE_URL)):
    // 外部URLの場合はesc_url_raw()でサニタイズしてからリダイレクト
    $redirect_url = esc_url_raw($postType_url);
    wp_redirect($redirect_url);
    exit;
  else:
    // URLが無効な場合は404ページへリダイレクト
    wp_safe_redirect(home_url('/404/'));
    exit;
  endif;
endif;

// ファイルタイプのリダイレクト処理
if(get_field('post_type') === 'file'):
  $postType_file = get_field('postType_file');
  
  if($postType_file):
    // 配列の場合はurlキーから取得、文字列の場合はそのまま使用
    $postType_url = is_array($postType_file) ? $postType_file['url'] : $postType_file;
    
    // URLのバリデーション
    if($postType_url && filter_var($postType_url, FILTER_VALIDATE_URL)):
      // 外部URLの場合はesc_url_raw()でサニタイズしてからリダイレクト
      $redirect_url = esc_url_raw($postType_url);
      wp_redirect($redirect_url);
      exit;
    else:
      // URLが無効な場合は404ページへリダイレクト
      wp_safe_redirect(home_url('/404/'));
      exit;
    endif;
  else:
    // ファイルが設定されていない場合は404ページへリダイレクト
    wp_safe_redirect(home_url('/404/'));
    exit;
  endif;
endif;

get_header();

$toc_items = array();
$content_with_toc_ids = '';
$table_of_contents = get_field('table_of_contents');

if (!post_password_required($post->ID)) {
  $content_with_toc_ids = apply_filters('the_content', get_the_content());

  if (function_exists('nig_prepare_toc_content')) {
    $toc_data = nig_prepare_toc_content($content_with_toc_ids);
    if (isset($toc_data['items']) && is_array($toc_data['items'])) {
      $toc_items = $toc_data['items'];
    }
    if (isset($toc_data['content']) && is_string($toc_data['content'])) {
      $content_with_toc_ids = $toc_data['content'];
    }
  }
}
?>
<main id="global_contents" class="global_contents" itemscope itemprop="mainContentOfPage">
  <?php if (!post_password_required($post->ID)) :  ?>
    <section>
      <?php get_template_part('visual'); ?>
      <div class="content_inner">
        <div class="gc_main _oneColumn">
          <article class="js-tooltip-scope">
            <div class="block-editor_wrap js-tooltip-content">
              <?php
              $img = get_field('image');
              $thumb = wp_get_attachment_image_src($img, '');
              if ($img) : ?>
                <div class="labTitle _only-SP">
                  <div class="labimage"><img src="<?php echo esc_url($thumb[0]); ?>" alt="<?php the_title(); ?>"></div>
                </div>
              <?php endif; ?>
            <?php if ($table_of_contents && !empty($toc_items) && function_exists('nig_render_article_toc')) : ?>
              <?php nig_render_article_toc($toc_items, $lang); ?>
            <?php endif; ?>
              <?php echo $content_with_toc_ids; ?>
            </div>
            <?php nig_render_tooltip_data_script(); ?>
          </article>
          <ul class="module_pager-02">
            <?php
            $prev_post = get_previous_post(); //前の記事
            $next_post = get_next_post(); //次の記事
            $postType_name = get_post_type_object(get_post_type())->label; //投稿タイプの名前
            $postType = esc_html(get_post_type_object(get_post_type())->name);
            ?>
            <li class="back"><a href="<?php if (get_post_type() === 'post'): $postTopId = get_option('page_for_posts'); ?><?php echo esc_url(home_url($lang_path . '/' . get_permalink($postTopId) . '/')); ?><?php else: ?><?php echo esc_url(home_url($lang_path . '/' . $postType . '/')); ?><?php endif; ?>"><span><?php echo $lang === 'en' ? ''. $postType .' List Back' : ''. $postType_name .'一覧に戻る'; ?></span><i class="fa-regular fa-rotate-left"></i></a></li>
          </ul>
        </div>
      </div>
    </section>
  <?php else: ?>
    <section>
      <?php get_template_part('visual'); ?>
      <div class="content_inner">
        <div class="module_password">
          <?php echo get_the_password_form(); ?>
        </div>
      </div>
    </section>
  <?php endif; ?>
</main>
<?php get_footer(); ?>
