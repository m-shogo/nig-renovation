<?php
/**
 * Template Name: 終了したセミナー・研究会テンプレート
 */
global $post;
$locale = get_locale();
$lang = explode('_', $locale)[0];

$current_year = (string) get_query_var('year');
if ($current_year === '' && preg_match('#/date/(\d{4})/#', (string) $_SERVER['REQUEST_URI'], $matches)) {
  $current_year = $matches[1];
}

$seminar_archive_lang = function_exists('nig_get_seminar_archive_lang') ? nig_get_seminar_archive_lang() : '';
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

$paged = max(1, (int) get_query_var('paged'), (int) get_query_var('page'));
$seminar_past_query_args = [
  'post_type' => 'seminar',
  'post_status' => 'publish',
  'posts_per_page' => 10,
  'paged' => $paged,
  'meta_query' => [
    [
      'key' => 'seminar_display_until',
      'value' => nig_get_jst_today_ymd(),
      'compare' => '<',
      'type' => 'NUMERIC',
    ],
  ],
  'meta_key' => 'seminar_event_start',
  'orderby' => 'meta_value_num',
  'order' => 'DESC',
];
if ($seminar_archive_lang !== '') {
  $seminar_past_query_args['lang'] = $seminar_archive_lang;
}
$seminar_past_query = new WP_Query($seminar_past_query_args);

get_header(); ?>
<main id="global_contents" class="global_contents" itemscope itemprop="mainContentOfPage">
  <?php if (!post_password_required($post->ID)) :  ?>
    <section>
      <?php get_template_part('visual'); ?>
      <div class="content_inner">
        <div class="gc_main _oneColumn">
          <div class="block-editor_wrap">
            <?php the_content(); ?>
          </div>
          <?php if (!empty($seminar_years)): ?>
            <div class="seminarCategory">
              <div class="module_select-01">
                <div class="wrap">
                  <select id="seminar-past-year-archive-select" name="seminar_past_year_archive" onchange="if(this.value){window.location.href=this.value;}">
                    <option value="" selected disabled>
                      <?php echo $lang === 'en' ? 'Past Seminars and Meetings' : '終了したセミナー・研究会'; ?>
                    </option>
                    <option value="<?php echo esc_url($lang === 'en' ? '/en/seminar-past/' : '/seminar-past/'); ?>" <?php selected($current_year, ''); ?>>ALL</option>
                    <?php foreach ($seminar_years as $seminar_year): ?>
                      <option value="<?php echo esc_url(($lang === 'en' ? '/en/seminar/' : '/seminar/') . 'date/' . $seminar_year . '/'); ?>" <?php selected($current_year, $seminar_year); ?>>
                        <?php echo esc_html($seminar_year); ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
            </div>
          <?php endif; ?>
          <div class="permissionText">
            <div class="wrap">
            <div class="text"><?php echo $lang === 'en' ? 'Information that is restricted to internal use cannot be accessed from outside the organization.' : '所内のみに公開している情報につきましては、所外からのアクセスはできません'; ?></div>
          </div>
        </div>
          <?php
          get_template_part('list', 'seminar', [
            'posts' => $seminar_past_query->posts,
            'pagination_query' => $seminar_past_query,
          ]);
          ?>
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
