<?php
/**
 * Template Name: 研究活動一覧テンプレート
 */
global $post;
get_header(); ?>
<main id="global_contents" class="global_contents" itemscope itemprop="mainContentOfPage">
  <?php if (!post_password_required($post->ID)) :  ?>
    <section>
      <?php get_template_part('visual'); ?>
      <div class="content_inner">
        <div class="gc_main _oneColumn">
          <div class="block-editor_wrap">

            <?php if (have_rows('labotop_navigation-01')): ?>
                <?php $count = 1; ?>
                <ul class="laboTop_anchor-01">
                    <?php while (have_rows('labotop_navigation-01')): the_row(); ?>
                        <?php
                        $title = get_sub_field('title');
                        ?>
                        <li>
                            <a href="#link<?php echo sprintf('%02d', $count++); ?>"><span><?php echo $title; ?></span></a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php endif; ?>

            <?php if (have_rows('labotop_navigation-01')): ?>
                <?php $count = 1; ?>
                <div class="laboTop_navigation-01">
                    <?php while (have_rows('labotop_navigation-01')): the_row(); ?>
                        <?php
                        $title = get_sub_field('title');
                        $text = get_sub_field('text');
                        ?>
                        <article id="link<?php echo sprintf('%02d', $count++); ?>">
                            <?php if ($title): ?><h2 class="wp-block-heading"><?php echo $title; ?></h2><?php endif; ?>
                            <?php if ($text): ?><p><?php echo $text; ?></p><?php endif; ?>
                            <?php if (have_rows('navigation')): ?>
                                <ul class="navigation">
                                    <?php while (have_rows('navigation')): the_row(); ?>
                                        <?php
                                        $img = get_sub_field('img');
                                        $thumb = wp_get_attachment_image_src($img, 'top_img');
                                        $title = get_sub_field('title');
                                        $alt = isset(get_post($img)->ID) ? get_post_meta(get_post($img)->ID, '_wp_attachment_image_alt', true) : "";
                                        $url = get_sub_field('url');
                                        ?>
                                        <li>
                                            <a <?php if (!empty($url)): ?>href="<?php echo esc_url($url); ?>"<?php else: ?> class="_disabled" tabindex="-1" <?php endif; ?>>
                                                <?php if ($img): ?><p class="image"><img src="<?php echo $thumb[0]; ?>" alt="<?php echo $alt; ?>"></p><?php endif; ?>
                                                <?php if ($title): ?><p class="title"><?php echo $title; ?></p><?php endif; ?>
                                            </a>
                                        </li>
                                    <?php endwhile; ?>
                                </ul>
                            <?php endif; ?>
                        </article>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>

          </div>
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