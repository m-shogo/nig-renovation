<?php

/**
 * --------------------------------------------------------------------------------
 * 
 * フロントのスタイル・JS設定
 * 
 * --------------------------------------------------------------------------------
 */

// ==========================================================================
// CSS・JS読み込み
// ※特定の条件下で読み込みたい場合は、一番最後の引数（無名関数の戻り値）に条件を記載（CSS・JS共通）
// ==========================================================================

// JavaScript
class EnqueueScript
{
  private $handle;
  private $src;
  private $deps;
  private $ver;
  private $in_footer;
  private $attribute;
  private ?Closure $conditional_branch_to_load; // 型指定

  public function __construct(
    $handle = '',
    $src = false,
    $deps = [],
    $ver = false,
    $in_footer = false,
    $attribute = false,
    ?Closure $conditional_branch_to_load = null
  ) {
    $this->handle = $handle;
    $this->src = $src;
    $this->deps = $deps;
    $this->ver = $ver;
    $this->in_footer = $in_footer;
    $this->attribute = $attribute;
    $this->enqueue_styles();
    $this->add_attribute();
    $this->conditional_branch_to_load = $conditional_branch_to_load;
  }

  private function enqueue_styles()
  {
    add_action('wp_enqueue_scripts', function () {
      if (is_callable($this->conditional_branch_to_load)) {
        if (($this->conditional_branch_to_load)($this)) { // 直接呼び出し
          wp_enqueue_script($this->handle, $this->src, $this->deps, $this->ver, $this->in_footer);
        }
      } else {
        wp_enqueue_script($this->handle, $this->src, $this->deps, $this->ver, $this->in_footer);
      }
    });
  }

  private function add_attribute()
  {
    if (empty($this->attribute)) {
      return false;
    }

    add_action('script_loader_tag', function ($tag, $handle) {
      if ($handle === $this->handle) {
        return str_replace(' src=', " $this->attribute src=", $tag);
      }
      return $tag;
    }, 10, 2);
  }
}

// CSS
class EnqueueStyle
{
  private $handle;
  private $src;
  private $deps;
  private $ver;
  private $media;
  private ?Closure $conditional_branch_to_load; // 型指定

  public function __construct(
    $handle = '',
    $src = false,
    $deps = [],
    $ver = false,
    $media = 'all',
    ?Closure $conditional_branch_to_load = null // 型指定
  ) {
    $this->handle = $handle;
    $this->src = $src;
    $this->deps = $deps;
    $this->ver = $ver;
    $this->media = $media;
    $this->enqueue_styles();
    $this->conditional_branch_to_load = $conditional_branch_to_load;
  }

  private function enqueue_styles()
  {
    add_action('wp_enqueue_scripts', function () {
      if (is_callable($this->conditional_branch_to_load)) {
        if (($this->conditional_branch_to_load)($this)) {
          wp_enqueue_style($this->handle, $this->src, $this->deps, $this->ver, $this->media);
        }
      } else {
        wp_enqueue_style($this->handle, $this->src, $this->deps, $this->ver, $this->media);
      }
    });
  }
}

// jQueryのバージョン指定
function replace_default_jquery() {
  // デフォルトの jQuery を登録解除
  wp_deregister_script('jquery');
  
  // カスタム jQuery を登録
  wp_register_script('jquery', get_stylesheet_directory_uri() . '/js/jquery-3.7.1.min.js', [], null, true);

  // カスタム jQuery をキューに追加
  wp_enqueue_script('jquery');
}
add_action('wp_enqueue_scripts', 'replace_default_jquery');

// JavaScript
new EnqueueScript('swiper-script', get_theme_file_uri('/js/swiper-bundle.min.js'), [], null, true, 'defer');
new EnqueueScript('modaal-script', get_theme_file_uri('/js/modaal.min.js'), [], '0.4.4', true, 'defer');
new EnqueueScript('dualtab-script', get_theme_file_uri('/js/jquery.dualtab.js'), array('jquery'), null, true, 'defer');
new EnqueueScript('common-script', get_theme_file_uri('/js/common.js'), array('jquery'), '1.0.0', true, 'defer');
new EnqueueScript('home-script', get_theme_file_uri('/js/home.js'), array('jquery'), '1.0.0', true, 'defer', function () {
  return is_page_template('original-frontPage.php');
});
new EnqueueScript('target-script', get_theme_file_uri('/js/target.js'), array('jquery'), '1.0.0', true, 'defer', function () {
  return is_page_template('original-targetTop.php');
});
new EnqueueScript('yubinbango-script', get_theme_file_uri('/js/yubinbango.js'), [], null, true, 'defer', function () {
  return is_page_template('page-form.php');
});
new EnqueueScript('formidable-script', get_theme_file_uri('/js/form.js'), array('jquery'), '1.0.0', true, 'defer', function () {
  return is_page_template('page-form.php');
});
// CSS
new EnqueueStyle('css-style', get_stylesheet_uri(), [], null);
new EnqueueStyle('swiper-style', get_theme_file_uri('/css/swiper-bundle.min.css'), [], null, 'all');
new EnqueueStyle('modaal-style', get_theme_file_uri('/css/modaal.min.css'), [], '0.4.4', 'all');
new EnqueueStyle('fontawesome-style', get_theme_file_uri('/css/all.min.css'), [], '6.7.2', 'all');
new EnqueueStyle('flexible-table-block'); // プラグインで登録済みのスタイルをenqueue（テンプレートでテーブルクラスを直接使用しているため必要）
//new EnqueueStyle('common-style', get_theme_file_uri('/css/style.css'), [], filemtime(get_theme_file_path('/css/style.css')), 'all');
new EnqueueStyle('home-style', get_theme_file_uri('/css/home.css'), [], null, 'all', function () {
  return is_page_template('original-frontPage.php');
});
new EnqueueStyle('target-style', get_theme_file_uri('/css/target.css'), [], null, 'all', function () {
  return is_page_template('original-targetTop.php');
});
new EnqueueStyle('laboratories-style', get_theme_file_uri('/css/laboratories.css'), [], null, 'all', function () {
  return is_page_template('original-laboratoriesTop.php');
});

// ==========================================================================
// ビジュアルエディタ用CSSの設定
// ==========================================================================
add_action('after_setup_theme', function () {
  // ブロックエディタ用スタイル機能をテーマに追加
  add_theme_support('editor-styles');
  // ブロックエディタ用CSSの読み込み
  add_editor_style('/css/editor-style.css');
});

// ==========================================================================
// global-stylesのインラインCSS出力を排除する
// ※ブロックエディタ不使用の場合のみ
// ==========================================================================
// add_action('wp_enqueue_scripts', 'remove_global_styles');
// function remove_global_styles()
// {
//   wp_dequeue_style('global-styles');
// }

// ==========================================================================
// ビジュアルエディタ用CSSキャッシュクリア
// https://qiita.com/m_t_of/items/22d5227000a9b2602729
// ==========================================================================
// function extend_tiny_mce_before_init($mce_init)
// {
//   $mce_init['cache_suffix'] = 'v=' . time();
//   return $mce_init;
// }
//add_filter( 'tiny_mce_before_init', 'extend_tiny_mce_before_init' );

// ==========================================================================
// 絵文字用スクリプト・スタイルの削除
// ==========================================================================
function disable_emoji()
{
  remove_action('wp_head', 'print_emoji_detection_script', 7);
  remove_action('admin_print_scripts', 'print_emoji_detection_script');
  remove_action('wp_print_styles', 'print_emoji_styles');
  remove_action('admin_print_styles', 'print_emoji_styles');
  remove_filter('the_content_feed', 'wp_staticize_emoji');
  remove_filter('comment_text_rss', 'wp_staticize_emoji');
  remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
}

add_action('init', 'disable_emoji');

// ==========================================================================
// WordPressヘッダーの不要なdns-prefetchを削除する
// https://on-ze.com/archives/6018
// ==========================================================================
remove_action('wp_head', 'wp_resource_hints', 2);


// ==========================================================================
// headに出力される不要なコードの削除
// https://cocorograph.co/knowledge/how-to-delete-wordpress-unnecessary-tags/
// ==========================================================================
// generatorを非表示にする
remove_action('wp_head', 'wp_generator');
// EditURIを非表示にする
remove_action('wp_head', 'rsd_link');
// wlwmanifestを非表示にする
remove_action('wp_head', 'wlwmanifest_link');
// 短縮URLを非表示にする
remove_action('wp_head', 'wp_shortlink_wp_head');
// 投稿の RSS フィードリンクを非表示にする
remove_action('wp_head', 'feed_links', 2);
// コメントフィードを非表示にする
remove_action('wp_head', 'feed_links_extra', 3);

// ==========================================================================
// パスワード保護ページを一覧から除外
// ==========================================================================
function not_password_output($query)
{
  if (!is_admin() && $query->is_main_query()) {
    if ($query->is_archive() || $query->is_category() || $query->is_home()) {
      $query->set('has_password', false);
    }
  }
}
add_action('pre_get_posts', 'not_password_output');

// ==========================================================================
// カテゴリスラッグクラスをbodyクラスに含める
// ==========================================================================
function add_page_slug_class_name($classes)
{
  if (is_page()) {
    $page      = get_post(get_the_ID());
    $classes[] = $page->post_name;

    $parent_id = $page->post_parent;
    if (0 == $parent_id) {
      $classes[] = get_post($parent_id)->post_name;
    } else {
      $progenitor_detail = get_ancestors($page->ID, 'page', 'post_type');
      $progenitor_id = array_pop($progenitor_detail);
      $classes[]     = get_post($progenitor_id)->post_name . '-child';
    }
  }

  return $classes;
}
add_filter('body_class', 'add_page_slug_class_name');

// ==========================================================================
// 自動で設定されるファビコンを削除 WordPress5.4以降
// ==========================================================================
function wp_favicon_delete()
{
  exit;
}
add_action("do_faviconico", "wp_favicon_delete");

// ==========================================================================
// body id取得
// ==========================================================================
function my_body_id()
{
  $post_obj =  $GLOBALS['wp_the_query']->get_queried_object();
  $slug = '';
  if (is_front_page()) {
    $slug = 'top';
    if (is_page() && get_post(get_the_ID())->post_name) {
      $slug = $post_obj->post_name;
    }
  } elseif (is_category()) {
    $slug = $post_obj->taxonomy . '-' . $post_obj->category_nicename;
  } elseif (is_tax()) {
    $slug = $post_obj->taxonomy . '-' . $post_obj->slug;
  } elseif (is_tag()) {
    $slug = $post_obj->slug;
  } elseif (is_year()) {
    $nendo = preg_replace('/[^0-9a-zA-Z]/', '', get_archive_title());
    $slug = 'archive-' . $nendo;
  } elseif (is_singular()) {
    $slug = $post_obj->post_name;
  } elseif (is_home()) {
    $slug = 'archive-' . $post_obj->post_name;
  } elseif (is_archive()) {
    $slug = 'archive-' . get_post_type();
  } elseif (is_search()) {
    $slug  = $GLOBALS['wp_the_query']->posts ? 'search-results' : 'search-no-results';
  } elseif (is_404()) {
    $slug = 'error404';
  }
  $body_id = esc_attr($slug);
  echo ($body_id) ? 'id="' . $body_id . '"' : '';
}

// ==========================================================================
// echo get_archive_title(); 余計な文字を削除
// https://wemo.tech/1161
// ==========================================================================
function get_archive_title()
{
  if (is_tag()) {
    return single_tag_title("", false);
  }
  if (get_post_type() === 'post' && is_home()) { //投稿トップ
    return single_post_title('', false);
  } elseif (get_post_type() === 'post' && is_single() && is_singular('post') && !is_date()) { // シングルタイトル
    $postType_name = esc_html(get_post_type_object(get_post_type())->labels->singular_name);
    return $postType_name;
  } elseif (get_post_type() === 'post' && is_category()) { // カテゴリトップのタイトル
    return single_term_title('', false);
  }
  if (get_post_type() && !is_date() && !is_tax()) { // カスタム投稿まとめ
    $postType_name = esc_html(get_post_type_object(get_post_type())->label);
    return $postType_name;
  } elseif (get_post_type() && is_tax()) { // カテゴリトップのタイトル
    return single_term_title('', false);
  }
  //アーカイブページじゃない場合、 false を返す
  //if (!is_archive()) return false;
  //日付アーカイブページなら
  if (is_date()) {
    if (is_year()) {
      // 年度にした場合
      $url = $_SERVER["REQUEST_URI"];
      $year = array_filter(explode("/", $url), 'strlen');
      if (!is_paged()) {
        $date_name = end($year) . '' . single_term_title('', false);
      } else {
        $year = array_reverse($year);
        $date_name = $year[2] . '' . single_term_title('', false);
      }
      // 年
      // $date_name = get_query_var('year').'年 '.single_term_title('',false);
    } elseif (is_month()) {
      $date_name = get_query_var('year') . '' . get_query_var('monthnum') . '月';
    } else {
      $date_name = get_query_var('year') . '' . get_query_var('monthnum') . '月' . get_query_var('day') . '日';
    }
    //日付アーカイブページかつ、投稿タイプアーカイブページでもある場合
    //    if (is_post_type_archive()) {
    //      return $date_name."".post_type_archive_title('',false);
    //    }
    return $date_name;
  }
  //投稿タイプのアーカイブページなら
  if (is_post_type_archive()) {
    return post_type_archive_title('', false);
  }
  //投稿者アーカイブページなら
  if (is_author()) {
    return "投稿者" . get_queried_object()->data->display_name;
  }
  //それ以外(カテゴリ・タグ・タクソノミーアーカイブページ)
  return single_term_title('', false);
}

// ==========================================================================
// 年度別アーカイブリスト作成
// ==========================================================================
// function get_archives_by_fiscal_year($args = '')
// {
//   global $wpdb;

//   $defaults = array(
//     'post_type' => 'post',
//     'post_status' => 'publish',
//     'limit' => '',
//   );

//   $r = wp_parse_args($args, $defaults);

//   $post_type = isset($r['post_type']) ? $r['post_type'] : 'post';
//   $limit = isset($r['limit']) ? absint($r['limit']) : '';

//   if (!empty($limit)) {
//     $limit = 'LIMIT ' . $limit;
//   } else {
//     $limit = '';
//   }

//   $sql = $wpdb->prepare(
//     "SELECT YEAR(ADDDATE(post_date, INTERVAL -3 MONTH)) AS `year`, COUNT(ID) AS `posts`
//          FROM $wpdb->posts
//          WHERE post_type = %s AND post_status = 'publish'
//          GROUP BY YEAR(ADDDATE(post_date, INTERVAL -3 MONTH))
//          ORDER BY post_date DESC
//          $limit",
//     $post_type
//   );

//   $arcresults = (array) $wpdb->get_results($sql);
//   return $arcresults;
// }

// ==========================================================================
// 年別アーカイブリストを表示（年度を使用しない場合は下記使用）
// ==========================================================================
function get_archives_by_year($post_type = 'post')
{
  $cat = get_the_category();
  $cat_id = '';
  $cat_slug = '';
  if (isset($cat[0]->cat_ID)) {
    $cat_id = $cat[0]->cat_ID;
  }
  if (isset($cat[0]->slug)) {
    $cat_slug = $cat[0]->slug;
  }
  $year = NULL; // 年の初期化
  $args = array( // クエリの作成
    'post_type' => $post_type, // 投稿タイプの指定
    'orderby' => 'date', // 日付順で表示
    'posts_per_page' => -1 // すべての投稿を表示
  );
  $the_query = new WP_Query($args);
  if ($the_query->have_posts()) { // 投稿があれば表示
    while ($the_query->have_posts()) : $the_query->the_post(); // ループの開始
      if ($year != get_the_date('Y')) { // 同じ年でなければ表示
        $year = get_the_date('Y'); // 年の取得
        $postTopId = get_option('page_for_posts');
        $lang = explode('_', get_locale())[0];
        $year_label = $lang === 'en' ? $year : $year . '年';
        echo '<li><a class="lnl_title mm_title-01" href="' . get_permalink($postTopId) . '' . $year . '/' . '"><span>' . esc_html($year_label) . '</span></a></li>'; // 年別アーカイブリストの表示
      }
    endwhile; // ループの終了
    wp_reset_postdata(); // クエリのリセット
  }
}

// ==========================================================================
// 指定したスラッグのページが親かどうか判定
// ==========================================================================
function is_parent($slug)
{
  global $post;
  $result = false;

  if (!empty($post->post_parent)) {
    $post_data = get_post($post->post_parent);
    if ($slug == $post_data->post_name) {
      $result = true;
    }
  }

  return $result;
}

// ==========================================================================
// メールアドレスっぽい文字列を検出してエンティティ化
// ==========================================================================
function obfuscate_emails_in_content($content)
{
  // メールアドレスっぽい文字列を検出してエンティティ化
  return preg_replace_callback(
    '/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/',
    function ($matches) {
      return antispambot($matches[0]);
    },
    $content
  );
}
add_filter('the_content', 'obfuscate_emails_in_content');

// ==========================================================================
// 特定の拡張子のリンクを外部リンクとして出力（target="_blank"を付与）
// ==========================================================================
function add_blank_target_to_file_links($content)
{
  // 外部リンクとして開く拡張子の一覧
  $extensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'xlsm', 'ppt', 'pptx', 'zip', 'docm', 'png', 'jpg', 'jpeg', 'gif', 'webp', 'svg', 'avif', 'mp4', 'mp3', 'webm', 'mov', 'avi'];
  $pattern = '/\.(' . implode('|', $extensions) . ')$/i';

  return preg_replace_callback(
    '/<a\s([^>]*)href=["\']([^"\']*)["\']([^>]*)>/i',
    function ($matches) use ($pattern) {
      $href = $matches[2];
      $path = parse_url($href, PHP_URL_PATH);

      // hrefのパスが対象の拡張子に一致する場合のみ処理
      if ($path && preg_match($pattern, $path)) {
        $before = $matches[1];
        $after  = $matches[3];

        // 既にtargetが設定されている場合はスキップ
        if (stripos($before . $after, 'target=') === false) {
          $after .= ' target="_blank" rel="noreferrer external"';
        }
        return '<a ' . $before . 'href="' . $href . '"' . $after . '>';
      }
      return $matches[0];
    },
    $content
  );
}
add_filter('the_content', 'add_blank_target_to_file_links');

// ==========================================================================
// head内ページタイトル自動出力制限
// ==========================================================================
add_filter('document_title_separator', function() {
  return '|';
});
remove_theme_support('title-tag');
remove_action('wp_head', '_wp_render_title_tag', 1);
remove_action('wp_head', 'wp_title', 1);