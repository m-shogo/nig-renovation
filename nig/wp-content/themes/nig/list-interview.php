<?php
$locale = get_locale();
$lang = explode("_", $locale)[0];
$front_page_id = get_option('page_on_front');
$lang_path = '';
if ($lang !== 'ja') {
  $lang_path = '/' . $lang;
}
global $post, $posts, $wp_query;
if (is_front_page() || is_page()) : ?>
  <div class="module_interviewCard-01">
    <?php foreach ($posts as $post) : setup_postdata($post); ?>
      <article>
        <?php
        // post_typeによるリンクや属性を取得
        $link_attrs = get_post_link_attributes();
        ?>
        <a href="<?php echo esc_url($link_attrs['url']); ?>"<?php echo $link_attrs['targetAttr']; ?>>
          <div class="head">
            <?php
            $img = get_post_thumbnail_id($post->ID);
            $thumb = wp_get_attachment_image_src($img, 'full');
            if ($img) : ?>
              <p class="image"><img src="<?php echo $thumb[0]; ?>" alt="<?php the_title_attribute(); ?>"></p>
            <?php else : ?>
              <p class="image _noImage"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/images/common/noimage_logo_01.svg" alt="<?php the_title_attribute(); ?>"></p>
            <?php endif; //$img 
            ?>
          </div>
          <div class="body">
            <?php
            $title = get_field("title");
            $position = get_field("position");
            $name = get_field("name");
            ?>
            <h3 class="title"><?php the_title(); ?></h3>
            <?php if($name || $position) : ?><div class="name"><span><?php echo esc_html($name); ?></span><span class="position"><?php echo esc_html($position); ?></span></div><?php endif; ?>
            <?php
            get_template_part('template-parts/_label-category', null, [
              'taxonomy' => '_cat',
            ]);
            ?>
          </div>
        </a>
      </article>
    <?php endforeach;
    wp_reset_postdata(); ?>
  </div>
<?php else : ?>
  <?php if (have_posts()) : ?>
    <div class="module_interviewCard-01">
      <?php while (have_posts()) : the_post(); ?>
        <article>
          <?php
          // post_typeによるリンクや属性を取得
          $link_attrs = get_post_link_attributes();
          ?>
          <a href="<?php echo esc_url($link_attrs['url']); ?>"<?php echo $link_attrs['targetAttr']; ?>>
            <div class="head">
              <?php
              $img = get_post_thumbnail_id($post->ID);
              $thumb = wp_get_attachment_image_src($img, 'full');
              if ($img) : ?>
                <p class="image"><img src="<?php echo $thumb[0]; ?>" alt="<?php the_title_attribute(); ?>"></p>
              <?php else : ?>
                <p class="image _noImage"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/images/common/noimage_logo_01.svg" alt="<?php the_title_attribute(); ?>"></p>
              <?php endif; //$img 
              ?>
            </div>
            <div class="body">
              <?php
              $title = get_field("title");
              $position = get_field("position");
              $name = get_field("name");
              ?>
              <h2 class="title"><?php the_title(); ?></h2>
              <?php if($name || $position) : ?><div class="name"><span><?php echo esc_html($name); ?></span><span class="position"><?php echo esc_html($position); ?></span></div><?php endif; ?>
              <?php
              get_template_part('template-parts/_label-category', null, [
                'taxonomy' => '_cat',
              ]);
              ?>
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
    <p><?php echo $lang === 'en' ? 'No articles found.' : '記事はありません。'; ?></p>
  <?php endif; ?>
<?php endif; ?>