<?php
$locale = get_locale();
$lang = explode("_", $locale)[0];
$front_page_id = get_option('page_on_front');
$lang_path = '';
if ($lang !== 'ja') {
  $lang_path = '/' . $lang;
}
global $post, $posts, $wp_query;
if (is_front_page() || is_page()): ?>
  <div class="module_newsList-01">
    <?php foreach ($posts as $post): setup_postdata($post); ?>
      <article>
        <?php
        // post_typeによるリンクや属性を取得
        $link_attrs = get_post_link_attributes();
        /**
         * セミナーカテゴリー時は公開日ではなく seminars_date で日付・見出し行を構成する。
         * 構成に失敗した場合はこれまでどおり標準の一覧表示にフォールバックする。
         */
        $seminars_heading_text_page = isset($post->ID) ? nig_build_seminars_list_heading($post) : '';
        $seminars_datetime_tuple_page = isset($post->ID) ? nig_get_seminars_list_datetime_parts($post) : array(null, null);
        ?>
        <a href="<?php echo esc_url($link_attrs['url']); ?>"<?php echo $link_attrs['targetAttr']; ?>>
          <div class="head">
            <?php $seminars_datetime_attr_ready = isset($seminars_datetime_tuple_page[0]) ? $seminars_datetime_tuple_page[0] : null; ?>
            <?php $seminars_datetime_display_ready = isset($seminars_datetime_tuple_page[1]) ? $seminars_datetime_tuple_page[1] : null; ?>
            <p class="date">
              <?php if ($seminars_datetime_attr_ready !== null && $seminars_datetime_display_ready !== null): ?>
                <time datetime="<?php echo esc_attr($seminars_datetime_attr_ready); ?>"><?php echo esc_html($seminars_datetime_display_ready); ?></time>
              <?php else: ?>
                <time datetime="<?php the_time('Y-m-d'); ?>"><?php the_time('Y.m.d'); ?></time>
              <?php endif; ?>
            </p>
            <?php
            get_template_part('template-parts/_label-onecategory', null, [
              'taxonomy' => '_cat',
            ]);
            ?>
          </div>
          <div class="body">
            <?php
            $list_news_title_output = $seminars_heading_text_page !== '' ? $seminars_heading_text_page : strip_tags(get_the_title());
            ?>
            <h3 class="title"><?php echo $list_news_title_output; ?></h3>
          </div>
        </a>
      </article>

    <?php endforeach;
    wp_reset_postdata(); ?>
  </div>
<?php else: ?>
  <?php if (have_posts()): ?>
    <div class="module_newsList-01">
      <?php while (have_posts()) : the_post(); ?>
        <article>
          <?php
          // post_typeによるリンクや属性を取得
          $link_attrs = get_post_link_attributes();
          /**
           * アーカイブ・メインクエリでもセミナー投稿は同じルールで表示する。
           */
          $seminars_heading_text_archive = get_the_ID() ? nig_build_seminars_list_heading(get_post()) : '';
          $seminars_datetime_tuple_archive = get_the_ID() ? nig_get_seminars_list_datetime_parts(get_post()) : array(null, null);
          ?>
          <a href="<?php echo esc_url($link_attrs['url']); ?>"<?php echo $link_attrs['targetAttr']; ?>>
            <div class="head">
              <?php $seminars_datetime_attr_archive = isset($seminars_datetime_tuple_archive[0]) ? $seminars_datetime_tuple_archive[0] : null; ?>
              <?php $seminars_datetime_display_archive = isset($seminars_datetime_tuple_archive[1]) ? $seminars_datetime_tuple_archive[1] : null; ?>
              <p class="date">
                <?php if ($seminars_datetime_attr_archive !== null && $seminars_datetime_display_archive !== null): ?>
                  <time datetime="<?php echo esc_attr($seminars_datetime_attr_archive); ?>"><?php echo esc_html($seminars_datetime_display_archive); ?></time>
                <?php else: ?>
                  <time datetime="<?php the_time('Y-m-d'); ?>"><?php the_time('Y.m.d'); ?></time>
                <?php endif; ?>
              </p>
              <?php
              get_template_part('template-parts/_label-onecategory', null, [
                'taxonomy' => '_cat',
              ]);
              ?>
            </div>
            <div class="body">
              <?php
              $list_news_title_archive = $seminars_heading_text_archive !== '' ? $seminars_heading_text_archive : strip_tags(get_the_title());
              ?>
              <h2 class="title"><?php echo $list_news_title_archive; ?></h2>
            </div>
          </a>
        </article>
      <?php endwhile; ?>
    </div>
    <?php
    // ページネーション（アーカイブページのみ）
    if (!is_front_page() && !is_page() && !is_single() && is_main_query() && function_exists('responsive_pagination')) {
      responsive_pagination($wp_query->max_num_pages);
    }
    ?>
  <?php else: ?>
    <p><?php echo $lang === 'en' ? 'There are no announcements.' : 'お知らせはありません。'; ?></p>
  <?php endif; ?>
<?php endif; ?>