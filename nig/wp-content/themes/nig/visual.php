<?php
global $post;
$lang = explode('_', get_locale())[0];
?>
<div class="global_mainVisual">
  <div class="global_inner">
    <?php if (get_current_post_type() && !is_page() && !is_404() && !is_search() || is_post_type_archive() || is_tax()): //投稿 ?>
      <?php
      $postType_name = get_current_post_type();
      $field_name = 'page_img-' . $postType_name; //ビジュアル設定（投稿タイプ）名前に合わせる
      ?>
      <?php if (get_post_type() === 'seminar'): ?>
        <?php if(is_tax('seminar_cat')): ?>
          <h1 class="gm_title"><span><?php echo $lang === 'en' ? 'Current Seminars and Meetings' : '開催中のセミナー・研究会'; ?>（<?php echo get_archive_title(); ?>）</span></h1>
        <?php elseif ( !is_year() ) : ?>
          <h1 class="gm_title"><span><?php echo $lang === 'en' ? 'Current Seminars and Meetings' : '開催中のセミナー・研究会'; ?></span></h1>
        <?php else: ?>
          <h1 class="gm_title"><span><?php echo $lang === 'en' ? 'Past Seminars and Meetings' : '終了したセミナー・研究会'; ?>（<?php echo get_archive_title(); ?>）</span></h1>
        <?php endif; ?>
      <?php elseif (!is_single()): ?>
        <h1 class="gm_title"><span><?php echo get_archive_title(); ?></span></h1>
      <?php else: ?>
        <?php if (get_current_post_type() === 'post'||is_category()): ?>
          <div class="singleTitle">
            <div class="date">
              <time datetime="<?php the_time('Y-m-d'); ?>"><?php the_time('Y.m.d'); ?></time>
              <?php
              get_template_part('template-parts/_label-onecategory', null, [
                'taxonomy' => '_cat',
              ]);
              ?>
            </div>
            <h1 class="gm_title"><span><?php the_title(); ?></span></h1>
          </div>
        <?php elseif (get_post_type() === 'interviews' || get_post_type() === 'student-message'): ?>
          <?php
            $title = get_field("title");
            $position = get_field("position");
            $name = get_field("name");
            $img = get_field('image');
            $thumb = wp_get_attachment_image_src($img, 'full');
          ?>
          <div class="labTitle">
            <div class="interviewTitle">
              <h1 class="gm_title"><span><?php the_title(); ?></span></h1>
              <div class="nameBox">
                <?php if($name || $position) : ?><div class="name"><span><?php echo esc_html($name); ?></span><span class="position"><?php echo esc_html($position); ?></span></div><?php endif; ?>
                <?php if($title) : ?><div class="text"><span><?php echo esc_html($title); ?></span></div><?php endif; ?>
              </div>
            </div>
            <?php if ($img): ?>
              <div class="labimage _over-TB"><img src="<?php echo $thumb[0]; ?>" alt="<?php the_title(); ?>"></div>
            <?php endif; //$img  ?>
          </div>
        <?php else: ?>
          <div class="singleTitle">
            <div class="date">
              <time datetime="<?php the_time('Y-m-d'); ?>"><?php the_time('Y.m.d'); ?></time>
              <?php
              get_template_part('template-parts/_label-category', null, [
                'taxonomy' => '_cat',
              ]);
              ?>
            </div>
            <h1 class="gm_title"><span><?php the_title(); ?></span></h1>
          </div>
        <?php endif; ?>
      <?php endif; ?>
    <?php elseif (is_404()): //404 ?>
      <h1 class="gm_title"><span><?php echo $lang === 'en' ? 'Page not found' : 'お探しのページは<br>見つかりませんでした'; ?></span></h1>
    <?php elseif (is_search()): //検索結果 ?>
      <h1 class="gm_title"><span><?php if (empty(get_search_query())) : ?><?php echo $lang === 'en' ? 'Please enter a search keyword' : '検索キーワードが未入力です'; ?><?php else: ?><?php the_search_query(); ?><?php echo $lang === 'en' ? ' - Search Results' : 'の検索結果'; ?><?php endif; ?></span></h1>
    <?php else: ?>
      <?php if (is_page_template("page-lab-detail.php")): ?>
        <div class="labTitle">
          <h1 class="gm_title"><span><?php the_title(); ?></span></h1>
          <?php
          $title = get_field("title");
          $img = get_post_thumbnail_id($post->ID);
          $thumb = wp_get_attachment_image_src($img, '');
          ?>
          <?php if($title): ?>
            <h2 class="gm_sub_title"><?php echo esc_html($title); ?></h2>
          <?php endif; ?>
          <?php if ($img): ?>
            <div class="labimage _over-TB"><img src="<?php echo $thumb[0]; ?>" alt="<?php the_title_attribute(); ?>"></div>
          <?php endif; //$img  ?>
        </div>
      <?php else: ?>
        <h1 class="gm_title"><span><?php the_title(); ?></span></h1>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</div>
<?php if (get_post_type() === 'seminar' || is_tax('seminar_cat')): ?>
  <?php if ( !is_year() ) : ?>
    <nav class="module_breadCrumb">
      <div class="global_inner">
        <ul class="module_breadCrumb-01" itemprop="Breadcrumb" itemscope="" itemtype="https://schema.org/BreadcrumbList">
          <li itemprop="itemListElement" itemscope="" itemtype="https://schema.org/ListItem">
            <a itemscope="" itemtype="https://schema.org/Thing" itemprop="item" href="<?php echo $lang === 'en' ? '/en/' : '/'; ?>"><span itemprop="name">TOP</span></a><meta itemprop="position" content="1">
        </li>
          <li itemprop="itemListElement" itemscope="" itemtype="https://schema.org/ListItem">
            <span itemprop="name"><?php echo $lang === 'en' ? 'Current Seminars and Meetings' : '開催中のセミナー・研究会'; ?></span>
            <meta itemprop="position" content="2">
          </li>
        </ul>
      </div>
    </nav>
  <?php else: ?>
    <nav class="module_breadCrumb">
      <div class="global_inner">
        <ul class="module_breadCrumb-01" itemprop="Breadcrumb" itemscope="" itemtype="https://schema.org/BreadcrumbList">
          <li itemprop="itemListElement" itemscope="" itemtype="https://schema.org/ListItem">
            <a itemscope="" itemtype="https://schema.org/Thing" itemprop="item" href="<?php echo $lang === 'en' ? '/en/' : '/'; ?>"><span itemprop="name">TOP</span></a><meta itemprop="position" content="1">
        </li>
          <li itemprop="itemListElement" itemscope="" itemtype="https://schema.org/ListItem">
            <span itemprop="name"><?php echo $lang === 'en' ? 'Past Seminars and Meetings' : '終了したセミナー・研究会'; ?></span>
            <meta itemprop="position" content="2">
          </li>
        </ul>
      </div>
    </nav>
  <?php endif; ?>
<?php else: ?>
  <?php get_template_part('breadCrumb'); ?>
<?php endif; ?>
