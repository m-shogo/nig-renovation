<?php
$locale = get_locale();
$lang = explode("_", $locale)[0];
get_header(); ?>
  <main id="global_contents" class="global_contents" itemscope itemprop="mainContentOfPage">
    <section>
      <?php get_template_part( 'visual' ); ?>
      <?php if (get_post_type() === 'post'||is_category()): //通常投稿 ?>
        <div class="content_inner">
          <div class="gc_main _oneColumn">
            <?php get_sidebar('archive'); ?>
            <?php get_template_part( 'list','news' ); ?>
          </div>
        </div>
      <?php elseif (get_post_type() === 'career' || is_tax('career_cat')): ?>
        <div class="content_inner">
          <div class="gc_main _oneColumn">
            <div class="block-editor_wrap">
              <h3 class="wp-block-heading"><?php echo get_archive_title(); ?></h3>
            </div>
            <?php get_template_part( 'list','career' ); ?>
          </div>
        </div>
      <?php elseif (get_post_type() === 'interviews' || get_post_type() === 'student-message' || is_tax('interviews_cat') || is_tax('student-message_cat')) : ?>
        <div class="content_inner">
          <div class="gc_main _oneColumn">
            <?php if(get_post_type() === 'interviews'): ?>
              <?php get_sidebar('archive'); ?>
            <?php endif; ?>
            <?php get_template_part( 'list','interview' ); ?>
          </div>
        </div>
      <?php elseif (get_post_type() === 'seminar' || is_tax('seminar_cat')): ?>
        <div class="content_inner">
          <div class="gc_main _oneColumn">
            <?php
            $current_year = (string) get_query_var('year');
            if ($current_year === '' && preg_match('#/date/(\d{4})/#', (string) $_SERVER['REQUEST_URI'], $matches)) {
              $current_year = $matches[1];
            }
            $seminar_years = [];
            $seminar_year_query_args = [
              'post_type' => 'seminar',
              'post_status' => 'publish',
              'posts_per_page' => -1,
              'fields' => 'ids',
              'suppress_filters' => false,
              'meta_query' => [[
                'key' => 'seminar_display_until',
                'value' => nig_get_jst_today_ymd(),
                'compare' => '<',
                'type' => 'NUMERIC',
              ]],
            ];
            $seminar_archive_lang = function_exists('nig_get_seminar_archive_lang') ? nig_get_seminar_archive_lang() : '';
            if ($seminar_archive_lang !== '') {
              $seminar_year_query_args['lang'] = $seminar_archive_lang;
            }

            $seminar_year_post_ids = get_posts($seminar_year_query_args);
            foreach ($seminar_year_post_ids as $seminar_year_post_id) {
              $year = nig_get_seminar_event_start_year($seminar_year_post_id);
              if ($year && !in_array($year, $seminar_years, true)) {
                $seminar_years[] = $year;
              }
            }

            rsort($seminar_years, SORT_STRING);
            ?>
            <div class="seminarCategory">
              <?php if ( !is_year() ) : ?>
                <?php get_sidebar('archive'); ?>
              <?php endif; ?>
              <?php if (!empty($seminar_years)): ?>
                <div class="module_select-01">
                  <div class="wrap">
                    <select id="seminar-year-archive-select" name="seminar_year_archive" onchange="if(this.value){window.location.href=this.value;}">
                      <option value="" selected disabled>
                        <?php echo $lang === 'en' ? 'Past Seminars and Meetings' : '終了したセミナー・研究会'; ?>
                      </option>
                      <option value="<?php echo esc_url($lang === 'en' ? '/en/seminar-past/' : '/seminar-past/'); ?>">ALL</option>
                      <?php foreach ($seminar_years as $seminar_year): ?>
                        <?php
                        $year_url = trailingslashit(get_post_type_archive_link('seminar')) . 'date/' . $seminar_year . '/';
                        ?>
                        <option value="<?php echo esc_url($year_url); ?>" <?php selected($current_year, $seminar_year); ?>>
                          <?php echo esc_html($seminar_year . ''); ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
              <?php endif; ?>
            </div>
            <div class="permissionText">
              <div class="wrap">
                <div class="text"><?php echo $lang === 'en' ? 'Information that is restricted to internal use cannot be accessed from outside the organization.' : '所内のみに公開している情報につきましては、所外からのアクセスはできません'; ?></div>
              </div>
            </div>
            <?php get_template_part( 'list','seminar' ); ?>
          </div>
        </div>
      <?php else: //その他カスタム投稿 ?>
        <div class="content_inner">
          <div class="gc_main _oneColumn">
            <?php if(get_post_type() === 'highlights'): ?>
              <?php get_sidebar('archive'); ?>
            <?php endif; ?>
            <?php get_template_part( 'list','card' ); ?>
          </div>
        </div>
      <?php endif; ?>
    </section>
  </main>
<?php get_footer(); ?>