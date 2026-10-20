<div class="module_newsList-01">
  <?php foreach ($posts as $post): setup_postdata($post); ?>
    <article>
      <?php
      // post_typeによるリンクや属性を取得
      $link_attrs = get_post_link_attributes();
      ?>
      <a href="<?php echo esc_url($link_attrs['url']); ?>"<?php echo $link_attrs['targetAttr']; ?>>
        <?php
        $img = get_post_thumbnail_id($post->ID);
        $thumb = wp_get_attachment_image_src($img, '');
        if ($img) : ?>
          <p class="image"><img src="<?php echo $thumb[0]; ?>" alt="<?php the_title_attribute(); ?>"></p>
        <?php else : ?>
          <p class="image _noImage"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/images/common/noimage_logo_01.svg" alt="<?php the_title_attribute(); ?>"></p>
        <?php endif; //$img ?>
        <div class="head">
          <p class="date"><time datetime="<?php the_time('Y-m-d'); ?>"><?php the_time('Y.m.d'); ?></time></p>
          <?php
          get_template_part('template-parts/_label-subcategory', null, [
            'taxonomy' => '_cat',
          ]);
          ?>
        </div>
        <div class="body">
          <h3 class="title"><?php the_title(); ?></h3>
          <p class="excerpt"><?php echo mb_substr( get_the_excerpt(), 0, 101 ) . '...'; ?></p>
        </div>
      </a>
    </article>
  <?php endforeach;
  wp_reset_postdata(); ?>
</div>