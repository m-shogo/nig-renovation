<?php
  $locale = get_locale();
  $lang = explode("_", $locale)[0];
  $front_page_id = get_option('page_on_front');
  $lang_path = '';
  $logo = 'logo.svg';
  if ($lang !== 'ja') {
    $lang_path = '/' . $lang;
    $logo = 'logo-'.$lang.'.svg';
  }
if ($lang === 'en') {
    $link_text01 = 'Search for Research and Collaborative Projects';
    $link_text02 = 'Study at the Graduate School';
    $link_text03 = 'Learn About the Institute of Genetics';
    $link_text04 = 'Consider Industry-Academia Collaboration';
} else {
    $link_text01 = '研究・共同研究を<br class="_only-SP">探したい';
    $link_text02 = '大学院で<br class="_only-SP">学びたい';
    $link_text03 = '遺伝研を<br class="_only-SP">知りたい';
    $link_text04 = '産学連携を<br class="_only-SP">考えたい';
}
?>
<?php
$post_url = '';
$target_lang = $lang === 'en' ? 'ja' : 'en';
$default_switch_path = $target_lang === 'en' ? '/en/' : '/';

if (!function_exists('nig_switch_lang_path')) {
  function nig_switch_lang_path($path, $target_lang)
  {
    $path = wp_parse_url($path, PHP_URL_PATH);
    $path = is_string($path) ? $path : '/';
    $path = '/' . ltrim($path, '/');

    if ($target_lang === 'en') {
      return strpos($path, '/en/') === 0 ? $path : '/en' . $path;
    }

    if ($path === '/en') {
      return '/';
    }

    return strpos($path, '/en/') === 0 ? substr($path, 3) : $path;
  }
}

if (!function_exists('nig_switch_lang_url')) {
  function nig_switch_lang_url($url, $target_lang)
  {
    if (!$url) {
      return $url;
    }

    $site_host = wp_parse_url(home_url('/'), PHP_URL_HOST);
    $url_host = wp_parse_url($url, PHP_URL_HOST);
    if ($url_host && $url_host !== $site_host) {
      return $url;
    }

    $path = wp_parse_url($url, PHP_URL_PATH);
    $query = wp_parse_url($url, PHP_URL_QUERY);
    $fragment = wp_parse_url($url, PHP_URL_FRAGMENT);
    $switched_url = nig_build_switch_url(nig_switch_lang_path($path, $target_lang));

    if ($query) {
      $switched_url .= '?' . $query;
    }

    if ($fragment) {
      $switched_url .= '#' . $fragment;
    }

    return $switched_url;
  }
}

if (!function_exists('nig_build_switch_url')) {
  function nig_build_switch_url($path)
  {
    $scheme = is_ssl() ? 'https' : 'http';
    $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : wp_parse_url(site_url('/'), PHP_URL_HOST);
    $path = '/' . ltrim((string) $path, '/');

    return $scheme . '://' . $host . $path;
  }
}

$switch_path = $default_switch_path;
$switch_link = nig_build_switch_url($switch_path);

if (is_single()) {
  $post_url = get_field('post_url');

  if ($post_url) {
    $switch_link = nig_switch_lang_url($post_url, $target_lang);
  } else {
    $archive_link = get_post_type_archive_link(get_post_type());
    if ($archive_link) {
      $switch_path = nig_switch_lang_path($archive_link, $target_lang);
      $switch_link = nig_build_switch_url($switch_path);
    }
  }
} elseif (is_page() && function_exists('bogo_get_post_translations')) {
  // Bogoで翻訳記事の存在をチェック、翻訳がなければ対応言語のTOPへ
  $translations = bogo_get_post_translations(get_queried_object_id());
  $target_locale = ($lang === 'ja') ? 'en_US' : 'ja';
  if (!empty($translations[$target_locale]) && get_post_status($translations[$target_locale]) === 'publish') {
    $switch_link = nig_build_switch_url(wp_make_link_relative(get_permalink($translations[$target_locale])));
  } else {
    $switch_link = nig_build_switch_url(($lang === 'ja') ? '/en/' : '/');
  }
} elseif (is_home() || is_archive() || is_post_type_archive() || is_tax() || is_category() || is_tag()) {
  $switch_path = nig_switch_lang_path(preg_replace('#/page/\d+/?#', '/', $_SERVER['REQUEST_URI']), $target_lang);
  $switch_link = nig_build_switch_url($switch_path);
} else {
  $switch_link = nig_build_switch_url($switch_path);
}
?>
<div class="headerNavigation">
  <div class="headerNavigationInner">
    <header id="global_header" class="global_header" itemscope itemtype="https://schema.org/WPHeader">
      <div class="inner">
        <?php if (is_front_page()) : ?>
            <h1 class="gh_logo"><a href="<?php echo esc_url(home_url('/')); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/common/<?php echo $logo ?>" alt="<?php bloginfo('name'); ?>"></a></h1>
        <?php else: ?>
            <p class="gh_logo"><a href="<?php echo esc_url(home_url('/')); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/common/<?php echo $logo ?>" alt="<?php bloginfo('name'); ?>"></a></p>
        <?php endif; ?>
        <button type="button" class="gh_menu" id="gh_menu"><span class="icon"></span></button>
      </div>
    </header>
    <nav id="global_navigation" class="global_navigation" itemscope itemtype="https://schema.org/SiteNavigationElement">
        <ul class="list">
          <li><a href="<?php echo esc_url(home_url('/')); ?>researchers/"><img src="<?php echo get_template_directory_uri(); ?>/images/home/icon_research.svg" alt="<?php echo strip_tags($link_text01); ?>"><span><?php echo $link_text01; ?></span></a></li>
          <li><a href="<?php echo esc_url(home_url('/')); ?>students/"><img src="<?php echo get_template_directory_uri(); ?>/images/home/icon_education.svg" alt="<?php echo strip_tags($link_text02); ?>"><span><?php echo $link_text02; ?></span></a></li>
          <li><a href="<?php echo esc_url(home_url('/')); ?>public/"><img src="<?php echo get_template_directory_uri(); ?>/images/home/icon_genetic.svg" alt="<?php echo strip_tags($link_text03); ?>"><span><?php echo $link_text03; ?></span></a></li>
          <li><a href="<?php echo esc_url(home_url('/')); ?>business/"><img src="<?php echo get_template_directory_uri(); ?>/images/home/icon_industry.svg" alt="<?php echo strip_tags($link_text04); ?>"><span><?php echo $link_text04; ?></span></a></li>
      </ul>
      <ul class="gn_links-01 module_menu-01">
        <?php if (have_rows('common-menu-02', $front_page_id)): ?>
          <?php while (have_rows('common-menu-02', $front_page_id)): the_row(); ?>
            <?php
            $title01 = get_sub_field('title-01');
            $url01 = get_sub_field('url-01');
            $target01 = get_sub_field('target-01');
            $select01 = get_sub_field('select-01');
            ?>
            <?php if ($select01): ?>
                <li class="_hasChild">
                    <button class="gnl_button mm_button" type="button"><span>開閉</span></button>
                    <a class="gnl_title mm_title" <?php if($url01): ?>href="<?php echo $url01; ?>"<?php endif; ?> <?php if ($target01): ?>target="_blank" <?php endif; ?>><span><?php echo $title01; ?></span></a>
                    <div class="gnl_wrapper mm_wrapper-01">
                        <div class="gnl_inner mm_inner-01">
                            <div class="gnl_title"><a <?php if($url01): ?>href="<?php echo $url01; ?>"<?php endif; ?> <?php if ($target01): ?>target="_blank" <?php endif; ?>><span><?php echo $title01; ?></span></a></div>
                            <ul>
                                <?php if (have_rows('hierarchy-02', $front_page_id)): ?>
                                    <?php while (have_rows('hierarchy-02', $front_page_id)): the_row(); ?>
                                        <?php
                                        $title02 = get_sub_field('title-02');
                                        $url02 = get_sub_field('url-02');
                                        $target02 = get_sub_field('target-02');
                                        $select02 = get_sub_field('select-02'); //4階層目なしの場合削除し、if( $select02 )内の4階層目なし<li>のみwhile内に残す
                                        ?>
                                        <?php if ($select02): ?>
                                            <li>
                                                <button class="gnl_button mm_button-01" type="button"><span>開閉</span></button>
                                                <a class="gnl_title mm_title-01" <?php if($url02): ?>href="<?php echo $url02; ?>"<?php endif; ?> <?php if ($target02): ?>target="_blank" <?php endif; ?>><span><?php echo $title02; ?></span></a>
                                                <div class="gnl_wrapper mm_wrapper-02">
                                                    <div class="gnl_inner mm_inner-02">
                                                        <ul>
                                                            <?php if (have_rows('hierarchy-03', $front_page_id)): //4階層目出力開始 
                                                            ?>
                                                                <?php while (have_rows('hierarchy-03', $front_page_id)): the_row(); ?>
                                                                    <?php
                                                                    $title03 = get_sub_field('title-03');
                                                                    $url03 = get_sub_field('url-03');
                                                                    $target03 = get_sub_field('target-03');
                                                                    ?>
                                                                    <li><a class="gnl_title mm_title-02" href="<?php echo $url03; ?>" <?php if ($target03): ?>target="_blank" <?php endif; ?>><span><?php echo $title03; ?></span></a></li>
                                                                <?php endwhile; ?>
                                                            <?php endif; ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </li>
                                        <?php else: ?>
                                            <li><a class="gnl_title mm_title-01" <?php if($url02): ?>href="<?php echo $url02; ?>"<?php endif; ?> <?php if ($target02): ?>target="_blank" <?php endif; ?>><span><?php echo $title02; ?></span></a></li>
                                        <?php endif; ?>
                                    <?php endwhile; ?>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </li>
            <?php else: ?>
                <li>
                  <a class="gnl_title mm_title" <?php if($url01): ?>href="<?php echo $url01; ?>"<?php endif; ?> <?php if ($target01): ?>target="_blank" <?php endif; ?>><span><?php echo $title01; ?></span></a>
                </li>
            <?php endif; ?>
          <?php endwhile; ?>
        <?php endif; ?>
        <li>
          <a class="gnl_title mm_title" href="<?php echo esc_url($switch_link); ?>"><span><?php echo $lang === 'en' ? 'Japanese' : 'English'; ?></span></a>
        </li>
      </ul>
      <?php if(have_rows('common-submenu-01' , $front_page_id)): //サブメニュー ?>
        <ul class="_sub">
          <?php while(have_rows('common-submenu-01', $front_page_id)): the_row(); ?>
            <?php
              $title = get_sub_field('title');
              $url = get_sub_field('url');
              $target = get_sub_field('target');
            ?>
            <li><a class="title" href="<?php echo $url; ?>" <?php if ($target): ?>target="_blank" <?php endif; ?>><span><?php echo $title; ?></span></a></li>
          <?php endwhile; ?>
        </ul>
      <?php endif; //サブメニュー ?>
      <div class="gn_search module_search-01">
        <form class="gns_form ms_form" role="search" method="get" action="/">
          <input class="ms_input" type="search" value="" name="s" id="s" placeholder="<?php echo $lang === 'en' ? 'Search' : '検索する'; ?>">
          <button type="submit" class="ms_button"><span></span></button>
        </form>
      </div>
      <div class="submenu">
        <button id="search" class="search"><i class="fa-light fa-magnifying-glass"></i><span><?php echo $lang === 'en' ? 'Search' : '検索する'; ?></span></button>
        <a href="<?php echo esc_url($switch_link); ?>"><span><?php echo $lang === 'en' ? 'Japanese' : 'English'; ?></span></a>
      </div>
    </nav>
    <div class="module_menu-01">
      <div class="inner">
        <?php if(have_rows('common-menu-01', $front_page_id )): ?>
          <?php while(have_rows('common-menu-01', $front_page_id )): the_row(); ?>
            <?php
            $title01 = get_sub_field('title-01');
            $url01 = get_sub_field('url-01');
            ?>
            <button class="title"><span><?php echo $title01; ?></span></button>
            <div class="menuList" style="display: none;">
              <div class="menuListInner">
                <div class="mm_wrapper-01">
                  <div class="mm_inner-01">
                    <a href="<?php echo $url01; ?>" class="mm_title"><span><?php echo $title01; ?></span></a>
                    <ul>
                      <?php if(have_rows('hierarchy-02')): //3階層目出力開始 ?>
                        <?php while(have_rows('hierarchy-02')): the_row(); ?>
                          <?php
                          $title02 = get_sub_field('title-02');
                          $url02 = get_sub_field('url-02');
                          $target02 = get_sub_field('target-02');
                          $select02 = get_sub_field('select-02');
                          ?>
                          <?php if ($select02): ?>
                            <li class="_hasChild">
                                <button class="gnl_button mm_button-01" type="button"><span>開閉</span></button>
                                <a class="gnl_title mm_title-01" <?php if($url02): ?>href="<?php echo $url02; ?>"<?php endif; ?> <?php if ($target02): ?>target="_blank" <?php endif; ?>><span><?php echo $title02; ?></span></a>
                                <div class="gnl_wrapper mm_wrapper-02">
                                    <div class="gnl_inner mm_inner-02">
                                        <ul>
                                            <?php if (have_rows('hierarchy-03', $front_page_id)): //4階層目出力開始 
                                            ?>
                                                <?php while (have_rows('hierarchy-03', $front_page_id)): the_row(); ?>
                                                    <?php
                                                    $title03 = get_sub_field('title-03');
                                                    $url03 = get_sub_field('url-03');
                                                    $target03 = get_sub_field('target-03');
                                                    ?>
                                                    <li><a class="gnl_title mm_title-02" href="<?php echo $url03; ?>" <?php if ($target03): ?>target="_blank" <?php endif; ?>><span><?php echo $title03; ?></span></a></li>
                                                <?php endwhile; ?>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                          <?php else: ?>
                            <li><a class="gnl_title mm_title-01" <?php if($url02): ?>href="<?php echo $url02; ?>"<?php endif; ?> <?php if ($target02): ?>target="_blank" <?php endif; ?>><span><?php echo $title02; ?></span></a></li>
                          <?php endif; ?>
                        <?php endwhile; ?>
                      <?php endif; //3階層目出力終了 ?>
                      <?php if(have_rows('submenu')): //サブメニュー ?>
                        <li class="_sub">
                          <ul>
                            <?php while(have_rows('submenu')): the_row(); ?>
                              <?php
                                $title = get_sub_field('title');
                                $url = get_sub_field('url');
                                $target = get_sub_field('target');
                              ?>
                              <li><a class="gnl_title mm_title-01" href="<?php echo $url; ?>" <?php if ($target): ?>target="_blank" <?php endif; ?>><span><?php echo $title; ?></span></a></li>
                            <?php endwhile; ?>
                          </ul>
                        </li>
                      <?php endif; //サブメニュー ?>
                    </ul>
                  </div><!-- /global_inner -->
                </div>
              </div>
            </div>
          <?php endwhile; ?>
        <?php endif; //common-menu-01 ?>
      </div><!-- /inner -->
    </div>
  </div>
</div>
<div class="overlay" id="overlay"></div>
<div class="gh_search module_search-pc">
  <div class="module_search-01">
    <form class="ms_form" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
      <input class="ms_input" type="search" name="s" placeholder="<?php echo $lang === 'en' ? 'Search' : '検索する'; ?>">
      <button class="ms_button" type="submit"><span></span></button>
    </form>
  </div>
  <div id="gns_close" class="close"><button id="searchClose"></button></div>
</div>
