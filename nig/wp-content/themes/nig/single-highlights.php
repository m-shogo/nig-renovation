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
            <?php if (!empty($toc_items) && function_exists('nig_render_article_toc')) : ?>
              <?php nig_render_article_toc($toc_items, $lang); ?>
            <?php endif; ?>
            <div class="block-editor_wrap js-tooltip-content">
              <?php echo $content_with_toc_ids; ?>
            </div>
            <?php nig_render_tooltip_data_script(); ?>
            <?php
              $img = get_field('comment_image');
              $thumb = wp_get_attachment_image_src($img, '');
              $caption = get_field('comment_caption');
              $title = get_field('comment_title');
              $text = get_field('comment_text');
            ?>
            <?php if($title || $text): ?>
              <div class="module_comment-01">
                <div class="wrap">
                  <div class="inner">
                    <div class="titleBox">
                      <?php if($title): ?>
                        <h3 class="title"><?php echo esc_html($title); ?></h3>
                      <?php endif; ?>
                    </div>
                    <?php if($img): ?>
                      <div class="imageBox">
                        <div class="image">
                          <img src="<?php echo esc_url($thumb[0]); ?>" alt="<?php echo esc_html($caption); ?>">
                        </div>
                        <?php if($caption): ?>
                          <p class="caption"><?php echo esc_html($caption); ?></p>
                        <?php endif; ?>
                      </div>  
                    <?php endif; ?>
                    <?php if($text): ?>
                      <div class="textBox">
                        <div class="text"><?php echo $text; ?></div>
                      </div>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            <?php endif; ?>
          </article>
          <ul class="module_pager-02">
            <?php
            $prev_post = get_previous_post(); //前の記事
            $next_post = get_next_post(); //次の記事
            $postType_name = get_post_type_object(get_post_type())->label; //投稿タイプの名前
            $postType = esc_html(get_post_type_object(get_post_type())->name);

            // 一覧に戻るリンク先を決定する
            // デフォルトは投稿タイプのアーカイブ
            $back_url = esc_url(home_url($lang_path . '/' . $postType . '/'));
            $back_term_name = ''; // 日本語表示用のターム名
            // 現在の記事と同じカテゴリー（highlights_cat）の一覧へ
            $current_terms = get_the_terms(get_the_ID(), 'highlights_cat');
            if ($current_terms && !is_wp_error($current_terms)) {
              $first_term = $current_terms[0]; // 複数ある場合は先頭のカテゴリー
              $back_url = esc_url(home_url($lang_path . '/' . $postType . '/category/' . $first_term->slug . '/'));
              $back_term_name = $first_term->name; // ターム名（日本語ラベルに付与）
            }
            ?>
            <li class="back"><a href="<?php echo $back_url; ?>"><span><?php echo $lang === 'en' ? esc_html($back_term_name) . ' List Back' : esc_html($back_term_name) . ' 一覧に戻る'; ?></span><i class="fa-regular fa-rotate-left"></i></a></li>
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
