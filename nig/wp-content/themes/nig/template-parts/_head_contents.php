<meta content="IE=Edge" http-equiv="X-UA-Compatible">
<meta content="width=device-width" name="viewport">
<meta content="telephone=no" name="format-detection">
<link href='//www.google-analytics.com' rel='preconnect dns-prefetch'>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Zen+Kaku+Gothic+New:wght@400;500;700&display=swap" rel="stylesheet">
<?php
  global $post;
  // ページタイトルと説明文を取得
  $page_acf_title = function_exists('get_field') ? get_field('page_title') : null;
  $page_acf_description = function_exists('get_field') ? get_field('page_description') : null;

  // サイトの基本情報を取得
  $site_name = get_bloginfo('name');
  $document_title_default = wp_get_document_title();
  $site_description_default = get_bloginfo('description'); 

  // 表示するタイトルを決定
  if(is_front_page() || is_home()) {
    $display_title = $page_acf_title ? $page_acf_title : $site_name;
  } else {  
    $display_title = $page_acf_title ? $page_acf_title : $document_title_default;
  }

  // 表示する説明文を決定
  $display_description = $page_acf_description ? $page_acf_description : $site_description_default;

  // 投稿ページや固定ページの説明文の設定
  if (is_singular() && $post && !$page_acf_description) {
    $excerpt = $post->post_excerpt ?: wp_strip_all_tags($post->post_content);
    if (empty($excerpt)) {
      // $excerptが空の場合、サイトの説明文を使用
      $display_description = $site_description_default;
    } else {
      // 抜粋を160文字にトリミング
      $display_description = mb_substr(str_replace(["\r\n", "\n", "\r"], ' ', $excerpt), 0, 160, 'UTF-8') . '...';
    }
  }
  // メタタグ出力
  echo "\n<title>" . esc_html($display_title) . "</title>";
  echo "\n<meta name=\"title\" content=\"" . esc_attr($display_title) . "\">";
  echo "\n<meta name=\"description\" content=\"" . esc_attr($display_description) . "\">";

  // Microdata用メタタグ
  echo "\n<meta itemprop=\"name\" content=\"" . esc_attr($display_title) . "\">";
  echo "\n<meta itemprop=\"description\" content=\"" . esc_attr($display_description) . "\">";
?>

<script>
  (function() {
    const MOBILE_BREAKPOINT = 768;

    function getOrCreateViewportMeta() {
      let meta = document.querySelector("meta[name='viewport']");
      if (!meta) {
        meta = document.createElement('meta');
        meta.setAttribute('name', 'viewport');
        document.head.appendChild(meta);
      }
      return meta;
    }

    function setViewport() {
      const meta = getOrCreateViewportMeta();
      if (window.innerWidth <= MOBILE_BREAKPOINT) {
        // 小さな画面 → デバイス幅にフィット（レスポンシブ）
        meta.setAttribute('content', 'width=device-width, initial-scale=1, viewport-fit=cover');
      } else {
        // 大きな画面 → 固定幅 1280px を基準に表示
        meta.setAttribute('content', 'width=1280');
      }
    }

    // リサイズ連打対策（デバウンス）
    let timer = null;

    function onResize() {
      clearTimeout(timer);
      timer = setTimeout(setViewport, 150);
    }

    window.addEventListener('DOMContentLoaded', setViewport, false);
    window.addEventListener('resize', onResize, false);
    window.addEventListener('orientationchange', setViewport, false);
  })();
</script>

<!-- OGP指定 -->
<?php include get_stylesheet_directory() . '/template-parts/_ogp-meta.php'; ?>

<!-- favicon指定 -->
<link rel="icon" type="image/webp" sizes="48x48" href="<?php echo get_template_directory_uri(); ?>/images/common/favicon.ico">
<link rel="icon" type="image/svg+xml" href="<?php echo get_template_directory_uri(); ?>/images/common/favicon.svg">
<link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_template_directory_uri(); ?>/images/common/apple-touch-icon.png">
<link rel="manifest" href="<?php echo get_template_directory_uri(); ?>/images/common/site.webmanifest">
<meta name="theme-color" content="#ffffff">
<style>
  <?php include(get_theme_file_path('/css/style.css')); ?><?php include(get_theme_file_path('/css/add.css')); ?>
</style>