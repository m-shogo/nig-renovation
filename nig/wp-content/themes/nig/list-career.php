<?php
global $post, $posts, $wp_query;
$lang = explode('_', get_locale())[0];
if (is_front_page() || is_page() || is_archive('career')): ?>
  <div class="module_newsList-01 _career">
    <?php foreach ($posts as $post): setup_postdata($post); ?>
      <article>
        <?php
        // post_typeによるリンクや属性を取得
        $link_attrs = get_post_link_attributes();
        ?>
        <a href="<?php echo esc_url($link_attrs['url']); ?>"<?php echo $link_attrs['targetAttr']; ?>>
          <div class="head">
            <p class="date"><time datetime="<?php the_time('Y-m-d'); ?>"><?php the_time('Y.m.d'); ?></time></p>
            <?php
            // jobs-past が付いていれば「募集終了」、なければ「募集中」を表示
            $postType = esc_html(get_post_type_object(get_post_type())->name);
            $postType_cat = $postType . '_cat';
            $is_jobs_past = has_term('jobs-past', $postType_cat, $post);

            $lang01 = $lang === 'en' ? 'Recruitment closed' : '募集終了';
            $lang02 = $lang === 'en' ? 'Currently recruiting' : '募集中';

            $jobs_status_label = $is_jobs_past ? $lang01 : $lang02;
            $jobs_status_class = $is_jobs_past ? 'jobs-past' : 'jobs-open';
            ?>
            <p class="category _jobs-status">
              <span class="label <?php echo esc_html($jobs_status_class); ?>"><?php echo esc_html($jobs_status_label); ?></span>
            </p>
          </div>
          <div class="body">
            <h3 class="title"><?php echo strip_tags(get_the_title()); ?></h3>
          </div>
        </a>
      </article>

    <?php endforeach;
    wp_reset_postdata(); ?>
  </div>
<?php else: ?>
  <?php if (have_posts()): ?>
    <div class="module_newsList-01 _career">
      <?php while (have_posts()) : the_post(); ?>
        <article>
          <?php
          // post_typeによるリンクや属性を取得
          $link_attrs = get_post_link_attributes();
          ?>
          <a href="<?php echo esc_url($link_attrs['url']); ?>"<?php echo $link_attrs['targetAttr']; ?>>
            <div class="head">
              <p class="date"><time datetime="<?php the_time('Y-m-d'); ?>"><?php the_time('Y.m.d'); ?></time></p>
              <?php
              // jobs-past が付いていれば「募集終了」、なければ「募集中」を表示
              $postType = esc_html(get_post_type_object(get_post_type())->name);
              $postType_cat = $postType . '_cat';
              $is_jobs_past = has_term('jobs-past', $postType_cat, $post);
              $jobs_status_label = $is_jobs_past ? ($lang === 'en' ? 'Recruitment closed' : '募集終了') : ($lang === 'en' ? 'Currently recruiting' : '募集中');
              $jobs_status_class = $is_jobs_past ? 'jobs-past' : 'jobs-open';
              ?>
              <p class="category _jobs-status">
                <span class="label <?php echo esc_html($jobs_status_class); ?>"><?php echo esc_html($jobs_status_label); ?></span>
              </p>
            </div>
            <div class="body">
              <h2 class="title"><?php echo strip_tags(get_the_title()); ?></h2>
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
    <p>ただいま募集していません。</p>
  <?php endif; ?>
<?php endif; ?>