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
  <?php
  $seminar_posts = isset($args['posts']) && is_array($args['posts']) ? $args['posts'] : $posts;
  $pagination_query = isset($args['pagination_query']) && $args['pagination_query'] instanceof WP_Query ? $args['pagination_query'] : null;
  ?>
  <?php if (!empty($seminar_posts)): ?>
  <div class=""></div>
  <div class="module_seminarList-01">
    <?php foreach ($seminar_posts as $post): setup_postdata($post); ?>
      <?php
        $permission = false;
        $permission = get_field('permission');
      ?>
      <article class="<?php echo $permission ? '_permission' : ''; ?>">
        <?php
        // post_typeによるリンクや属性を取得
        $link_attrs = get_post_link_attributes();
        ?>
        <div class="wrap">
          <div class="head">
            <div class="categoryWrap">
              <?php
              get_template_part('template-parts/_label-onecategory', null, [
                'taxonomy' => '_cat',
              ]);
              ?>
              <?php if($permission): ?>
                <p class="category _cat">
                  <span class="label _permission">Intranet Only</span>
                </p>
              <?php endif; ?>
            </div>
            <a href="<?php echo esc_url($link_attrs['url']); ?>" target="_blank">
              <h3 class="title"><?php echo strip_tags(get_the_title()); ?></h3>
            </a>
          </div>
          <div class="body">
            <div class="module_seminar-01">
              <?php
              $seminar_type = get_field('seminar_type');
              $speaker = get_field('speaker');
              $time = get_field('time');
              $date = get_field('date');
              $enddate = get_field('enddate');
              $date2 = get_field('date2');
              $place = get_field('place');
              $others = get_field('others');
              $language = get_field('language');
              $schedule_lines = nig_get_seminar_schedule_lines($date, $time, $enddate, $date2);
              $place_key = is_array($place) ? (string) ($place['value'] ?? '') : (string) $place;
              $place_label = is_array($place) ? (string) ($place['label'] ?? $place_key) : (string) $place;
              $language_label = is_array($language) ? (string) ($language['label'] ?? ($language['value'] ?? '')) : (string) $language;
              $is_others = $place_key === '_others';
              ?>
              <?php if ($seminar_type): ?>
                <div class="wrap">
                  <div class="title">seminar type</div>
                  <div class="content"><?php echo esc_html($seminar_type); ?></div>
                </div>
              <?php endif; ?>
              <?php if ($speaker): ?>
                <div class="wrap">
                  <div class="title">speaker</div>
                  <div class="content"><?php echo esc_html($speaker); ?></div>
                </div>
              <?php endif; ?>
              <?php if (!empty($schedule_lines)): ?>
                <div class="wrap">
                  <div class="title">schedule</div>
                  <div class="content">
                    <?php foreach ($schedule_lines as $schedule_line): ?>
                      <div><?php echo esc_html($schedule_line); ?></div>
                    <?php endforeach; ?>
                  </div>
                </div>
              <?php endif; ?>
              <?php if ($place): ?>
                <div class="wrap">
                  <div class="title">place</div>
                  <div class="content">
                    <?php echo esc_html($place_label); ?>
                    <?php if ($is_others && $others): ?>
                      <br><?php echo esc_html($others); ?>
                    <?php endif; ?>
                  </div>
                </div>
              <?php endif; ?>
              <?php if ($language): ?>
                <div class="wrap">
                  <div class="title">language</div>
                  <div class="content"><?php echo esc_html($language_label); ?></div>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </article>

    <?php endforeach;
    wp_reset_postdata(); ?>
  </div>
  <?php if ($pagination_query && function_exists('responsive_pagination')): ?>
    <?php responsive_pagination($pagination_query->max_num_pages); ?>
  <?php endif; ?>
  <?php else: ?>
    <p><?php echo $lang === 'en' ? 'There are no announcements.' : 'お知らせはありません。'; ?></p>
  <?php endif; ?>
<?php else: ?>
  <?php if (have_posts()): ?>
    <div class="module_seminarList-01">
      <?php while (have_posts()) : the_post(); ?>
        <?php
          $permission = false;
          $permission = get_field('permission');
        ?>
        <article class="<?php echo $permission ? '_permission' : ''; ?>">
          <?php
          // post_typeによるリンクや属性を取得
          $link_attrs = get_post_link_attributes();
          ?>
          <div class="wrap">
            <div class="head">
              <div class="categoryWrap">
                <?php
                get_template_part('template-parts/_label-onecategory', null, [
                  'taxonomy' => '_cat',
                ]);
                ?>
                <?php if($permission): ?>
                  <p class="category _cat">
                    <span class="label _permission">Intranet Only</span>
                  </p>
                <?php endif; ?>
              </div>
              <a href="<?php echo esc_url($link_attrs['url']); ?>" target="_blank">
                <h3 class="title"><?php echo strip_tags(get_the_title()); ?></h3>
              </a>
            </div>
            <div class="body">
              <div class="module_seminar-01">
                <?php
                $seminar_type = get_field('seminar_type');
                $speaker = get_field('speaker');
                $time = get_field('time');
                $date = get_field('date');
                $enddate = get_field('enddate');
                $date2 = get_field('date2');
                $place = get_field('place');
                $others = get_field('others');
                $language = get_field('language');
                $schedule_lines = nig_get_seminar_schedule_lines($date, $time, $enddate, $date2);
                $place_key = is_array($place) ? (string) ($place['value'] ?? '') : (string) $place;
                $place_label = is_array($place) ? (string) ($place['label'] ?? $place_key) : (string) $place;
                $language_label = is_array($language) ? (string) ($language['label'] ?? ($language['value'] ?? '')) : (string) $language;
                $is_others = $place_key === '_others';
                ?>
                <?php if ($seminar_type): ?>
                  <div class="wrap">
                    <div class="title">seminar type</div>
                    <div class="content"><?php echo esc_html($seminar_type); ?></div>
                  </div>
                <?php endif; ?>
                <?php if ($speaker): ?>
                  <div class="wrap">
                    <div class="title">speaker</div>
                    <div class="content"><?php echo esc_html($speaker); ?></div>
                  </div>
                <?php endif; ?>
                <?php if (!empty($schedule_lines)): ?>
                  <div class="wrap">
                    <div class="title">schedule</div>
                    <div class="content">
                      <?php foreach ($schedule_lines as $schedule_line): ?>
                        <div><?php echo esc_html($schedule_line); ?></div>
                      <?php endforeach; ?>
                    </div>
                  </div>
                <?php endif; ?>
                <?php if ($place): ?>
                  <div class="wrap">
                    <div class="title">place</div>
                    <div class="content">
                      <?php echo esc_html($place_label); ?>
                      <?php if ($is_others && $others): ?>
                        <br><?php echo esc_html($others); ?>
                      <?php endif; ?>
                    </div>
                  </div>
                <?php endif; ?>
                <?php if ($language): ?>
                  <div class="wrap">
                    <div class="title">language</div>
                    <div class="content"><?php echo esc_html($language_label); ?></div>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          </div>
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
