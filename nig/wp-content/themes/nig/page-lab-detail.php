<?php
/**
 * Template Name: 研究室紹介（詳細）
 */
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
  <?php if (!post_password_required($post->ID)) :  ?>
    <section>
      <?php get_template_part('visual'); ?>
      <div class="content_inner">
        <div class="gc_main _oneColumn">
          <div class="block-editor_wrap">
            <?php
            $img = get_post_thumbnail_id($post->ID);
            $thumb = wp_get_attachment_image_src($img, '');
            if ($img) : ?>
              <div class="labTitle _only-SP">
                <div class="labimage"><img src="<?php echo $thumb[0]; ?>" alt="<?php the_title_attribute(); ?>"></div>
              </div>
            <?php endif; //$img  ?>
            <?php the_content(); ?>
          </div>
          <ul class="module_pager-03">
            <li class="back"><a href="<?php echo esc_url(home_url($lang_path . '/researchers/laboratories/')); ?>"><span><?php echo $lang === 'en' ? 'Laboratories List Back' : '研究室一覧に戻る'; ?></span><i class="fa-regular fa-rotate-left"></i></a></li>
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