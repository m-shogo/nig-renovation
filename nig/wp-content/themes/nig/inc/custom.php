<?php

/**
 * --------------------------------------------------------------------------------
 * 
 * カスタム投稿タイプ・タクソノミーの設定
 * 
 * --------------------------------------------------------------------------------
 */

// ==========================================================================
// カスタムポストの設定
// ==========================================================================
function nig_post_type_labels($ja_label, $en_label)
{
  $is_english = get_locale() === 'en_US';
  $label = $is_english ? $en_label : $ja_label;

  return array(
    'name' => $label,
    'singular_name' => $label,
    'menu_name' => $label,
    'all_items' => $label,
  );
}

function add_custom_post()
{
  $args = array(
    'label' => 'Our Research',
    'labels' => nig_post_type_labels('Our Research', 'Our Research'),
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true,
    'query_var' => true,
    'capability_type' => 'post',
    'hierarchical' => false,
    'menu_position' => 5,
    'has_archive' => true,
    'show_in_rest' => true,
    'rewrite' => array('with_front' => false),
    'supports' => array(
      'title',
      'thumbnail',
      'editor',
      'revisions',
    )
  );
  register_post_type('highlights', $args);

  $args = array(
    'label' => get_locale() === 'en_US' ? 'Research Interview' : '研究インタビュー',
    'labels' => nig_post_type_labels('研究インタビュー', 'Research Interview'),
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true,
    'query_var' => true,
    'capability_type' => 'post',
    'hierarchical' => false,
    'menu_position' => 5,
    'has_archive' => true,
    'show_in_rest' => true,
    'rewrite' => array('with_front' => false),
    'supports' => array(
      'title',
      'thumbnail',
      'editor',
      'revisions',
    )
  );
  register_post_type('interviews', $args);

  $args = array(
    'label' => get_locale() === 'en_US' ? 'Student Message' : '学生メッセージ',
    'labels' => nig_post_type_labels('学生メッセージ', 'Student Message'),
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true,
    'query_var' => true,
    'capability_type' => 'post',
    'hierarchical' => false,
    'menu_position' => 5,
    'has_archive' => true,
    'show_in_rest' => true,
    'rewrite' => array('with_front' => false),
    'supports' => array(
      'title',
      'thumbnail',
      'editor',
      'revisions',
    )
  );
  register_post_type('student-message', $args);

  $args = array(
    'label' => get_locale() === 'en_US' ? 'Career' : '人材・キャリア',
    'labels' => nig_post_type_labels('人材・キャリア', 'Career'),
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true,
    'query_var' => true,
    'capability_type' => 'post',
    'hierarchical' => false,
    'menu_position' => 5,
    'has_archive' => true,
    'show_in_rest' => true,
    'rewrite' => array('with_front' => false),
    'supports' => array(
      'title',
      'thumbnail',
      'editor',
      'revisions',
    )
  );
  register_post_type('career', $args);

}
add_action('init', 'add_custom_post');

// ==========================================================================
// カスタムタクソノミーの設定
// ==========================================================================
function add_custom_taxonomy()
{
  register_taxonomy(
    'highlights_cat',
    'highlights',
    array(
      'label' => 'カテゴリー',
      'hierarchical' => true,
      'public' => true,
      'show_admin_column' => true,
      // show_in_rest を false にして Gutenberg 標準のカテゴリーパネルを無効化
      'show_in_rest'      => false,
      // 標準のタクソノミーメタボックスを無効化（独自のラジオメタボックスを add_meta_box で登録する）
      // ※ タクソノミーの meta_box_cb はブロックエディタでは __back_compat_meta_box 扱いとなり表示されないため
      'meta_box_cb'       => false,
      'rewrite' => array('slug' => 'category', 'with_front' => false)
    )
  );

  register_taxonomy(
    'interviews_cat',
    'interviews',
    array(
      'label' => 'カテゴリー',
      'hierarchical' => true,
      'public' => true,
      'show_admin_column' => true,
      'show_in_rest'      => true,
      'rewrite' => array('slug' => 'category', 'with_front' => false)
    )
  );

  register_taxonomy(
    'student-message_cat',
    'student-message',
    array(
      'label' => 'カテゴリー',
      'hierarchical' => true,
      'public' => true,
      'show_admin_column' => true,
      'show_in_rest'      => true,
      'rewrite' => array('slug' => 'category', 'with_front' => false)
    )
  );

  register_taxonomy(
    'career_cat',
    'career',
    array(
      'label' => 'カテゴリー',
      'hierarchical' => true,
      'public' => true,
      'show_admin_column' => true,
      'show_in_rest'      => true,
      'rewrite' => array('slug' => 'category', 'with_front' => false)
    )
  );

}
add_action('init', 'add_custom_taxonomy');

// ==========================================================================
// 特定の投稿タイプのエディターを非表示にする
// ==========================================================================
// add_action('init', function () {
//   // エディターを無効化するカスタム投稿タイプのリスト
//   $post_types_without_editor = array(
//     'XXXXX', // カスタム投稿名
//   );

//   // 各投稿タイプに対してエディターを削除
//   foreach ($post_types_without_editor as $post_type) {
//     remove_post_type_support($post_type, 'editor');
//   }
// }, 99);

// ==========================================================================
// フロントページのエディターを非表示にする
// ==========================================================================
add_action('admin_init', function () {
  // フロントページのIDを取得
  $front_page_id = get_option('page_on_front');

  // 現在の編集画面がフロントページか判定
  global $pagenow;
  if ($pagenow === 'post.php' && isset($_GET['post']) && intval($_GET['post']) === intval($front_page_id)) {
    remove_post_type_support('page', 'editor');
  }
});

// ==========================================================================
// seminar 一覧表示用メタの更新
// ==========================================================================
function nig_get_jst_today_ymd()
{
  return (new DateTimeImmutable('now', new DateTimeZone('Asia/Tokyo')))->format('Ymd');
}

function nig_parse_date_to_ymd($date_string)
{
  $date_string = trim((string) $date_string);
  if ($date_string === '') {
    return '';
  }

  $timezone = new DateTimeZone('Asia/Tokyo');
  $normalized = str_replace('.', '/', $date_string);
  $parts = preg_split('#[/-]#', $normalized);

  if (count($parts) === 3 && strlen($parts[0]) === 4) {
    $year = (int) $parts[0];
    $first = (int) $parts[1];
    $second = (int) $parts[2];

    if (checkdate($first, $second, $year)) {
      $date = DateTimeImmutable::createFromFormat('!Y/n/j', sprintf('%04d/%d/%d', $year, $first, $second), $timezone);
      return $date ? $date->format('Ymd') : '';
    }

    if (checkdate($second, $first, $year)) {
      $date = DateTimeImmutable::createFromFormat('!Y/n/j', sprintf('%04d/%d/%d', $year, $second, $first), $timezone);
      return $date ? $date->format('Ymd') : '';
    }
  }

  $date = date_create_immutable($date_string, $timezone);
  return $date ? $date->format('Ymd') : '';
}

function nig_get_seminar_event_start_ymd($post_id)
{
  if (!function_exists('get_field')) {
    return '';
  }

  return nig_parse_date_to_ymd(get_field('date', $post_id));
}

function nig_get_seminar_event_start_year($post_id)
{
  $event_start = nig_get_seminar_event_start_ymd($post_id);
  return $event_start !== '' ? substr($event_start, 0, 4) : '';
}

function nig_get_seminar_display_until_ymd($post_id)
{
  if (!function_exists('get_field')) {
    return '';
  }

  $enddate = nig_parse_date_to_ymd(get_field('enddate', $post_id));
  if ($enddate !== '') {
    return $enddate;
  }

  $date2 = get_field('date2', $post_id);
  if (!empty($date2) && is_array($date2)) {
    $last_date = '';
    foreach ($date2 as $date_item) {
      if (!is_array($date_item)) {
        continue;
      }

      $candidate = nig_parse_date_to_ymd($date_item['date'] ?? '');
      if ($candidate !== '') {
        $last_date = $candidate;
      }
    }

    if ($last_date !== '') {
      return $last_date;
    }
  }

  return nig_get_seminar_event_start_ymd($post_id);
}

function nig_update_seminar_query_meta($post_id)
{
  $post_id = (int) $post_id;
  if ($post_id <= 0 || get_post_type($post_id) !== 'seminar') {
    return;
  }

  $display_until = nig_get_seminar_display_until_ymd($post_id);
  $event_start = nig_get_seminar_event_start_ymd($post_id);

  if ($display_until !== '') {
    update_post_meta($post_id, 'seminar_display_until', $display_until);
  } else {
    delete_post_meta($post_id, 'seminar_display_until');
  }

  if ($event_start !== '') {
    update_post_meta($post_id, 'seminar_event_start', $event_start);
  } else {
    delete_post_meta($post_id, 'seminar_event_start');
  }
}

function nig_save_seminar_query_meta($post_id)
{
  if (wp_is_post_revision($post_id)) {
    return;
  }

  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return;
  }

  nig_update_seminar_query_meta($post_id);
}
add_action('acf/save_post', 'nig_save_seminar_query_meta', 20);

function nig_backfill_seminar_query_meta_once()
{
  if (!function_exists('get_field')) {
    return;
  }

  $current_version = (int) get_option('nig_seminar_query_meta_version', 0);
  if ($current_version >= 1) {
    return;
  }

  $seminar_ids = get_posts([
    'post_type' => 'seminar',
    'post_status' => 'any',
    'posts_per_page' => -1,
    'fields' => 'ids',
    'suppress_filters' => true,
  ]);

  foreach ($seminar_ids as $seminar_id) {
    nig_update_seminar_query_meta($seminar_id);
  }

  update_option('nig_seminar_query_meta_version', 1, false);
}
add_action('init', 'nig_backfill_seminar_query_meta_once', 20);

function nig_query_targets_seminar($query)
{
  if (!($query instanceof WP_Query)) {
    return false;
  }

  if ($query->is_tax('seminar_cat') || $query->is_post_type_archive('seminar')) {
    return true;
  }

  $post_type = $query->get('post_type');
  if (is_array($post_type)) {
    return in_array('seminar', $post_type, true);
  }

  return $post_type === 'seminar';
}

function nig_get_seminar_archive_lang()
{
  return get_locale() === 'en_US' ? 'ja' : '';
}

// ==========================================================================
// 投稿タイプ表示件数指定
// ==========================================================================
function hwl_home_pagesize($query)
{
  if (is_admin() || !$query->is_main_query())
    return;
  if (is_post_type_archive('interviews') || is_post_type_archive('student-message')) {
    $query->set('posts_per_page', 18);
    return;
  }

  if ($query->is_year() && nig_query_targets_seminar($query)) {
    $today = nig_get_jst_today_ymd();
    $year = (string) $query->get('year');
    $meta_query = $query->get('meta_query');
    $seminar_lang = nig_get_seminar_archive_lang();

    if (!is_array($meta_query)) {
      $meta_query = [];
    }

    $meta_query[] = [
      'key' => 'seminar_display_until',
      'value' => $today,
      'compare' => '<',
      'type' => 'NUMERIC',
    ];

    if ($year !== '' && ctype_digit($year)) {
      $meta_query[] = [
        'key' => 'seminar_event_start',
        'value' => [$year . '0101', $year . '1231'],
        'compare' => 'BETWEEN',
        'type' => 'NUMERIC',
      ];
    }

    // Native year archives filter by post_date, but seminar year pages should follow event dates.
    $query->set('year', '');
    $query->set('posts_per_page', 10);
    if ($seminar_lang !== '') {
      $query->set('lang', $seminar_lang);
    }
    $query->set('meta_query', $meta_query);
    $query->set('meta_key', 'seminar_event_start');
    $query->set('orderby', 'meta_value_num');
    $query->set('order', 'DESC');
    return;
  }

  if ($query->is_post_type_archive('seminar') || $query->is_tax('seminar_cat')) {
    $today = nig_get_jst_today_ymd();
    $meta_query = $query->get('meta_query');
    $seminar_lang = nig_get_seminar_archive_lang();

    if (!is_array($meta_query)) {
      $meta_query = [];
    }

    $meta_query[] = [
      'key' => 'seminar_display_until',
      'value' => $today,
      'compare' => '>=',
      'type' => 'NUMERIC',
    ];

    $query->set('posts_per_page', 10);
    if ($seminar_lang !== '') {
      $query->set('lang', $seminar_lang);
    }
    $query->set('meta_query', $meta_query);
    $query->set('meta_key', 'seminar_event_start');
    $query->set('orderby', 'meta_value_num');
    $query->set('order', 'ASC');
    return;
  }
}
add_action('pre_get_posts', 'hwl_home_pagesize', 1);

// ==========================================================================
// 投稿一覧（/information/）からセミナーカテゴリーを除外
// ==========================================================================
/**
 * 投稿ページ（is_home）から「seminars」カテゴリーを除外する
 *
 * 対象:
 * - メインクエリ
 * - フロントエンドのみ
 * - 投稿ページ（/information/）
 *
 * カテゴリーアーカイブ（is_category）は除外対象外。
 * 「seminars」カテゴリーを直接開いた場合は通常通り記事を表示する。
 */
function nig_exclude_seminars_from_post_archive($query)
{
  if (is_admin() || !$query->is_main_query()) {
    return;
  }

  if (!$query->is_home()) {
    return;
  }

  $seminars_id = nig_get_seminars_category_id();
  if (!$seminars_id) {
    return;
  }

  $query->set('category__not_in', [$seminars_id]);
}
add_action('pre_get_posts', 'nig_exclude_seminars_from_post_archive');

// ==========================================================================
// 「投稿」の名称変更
// ==========================================================================
function change_post_menu_label()
{
  global $menu;
  global $submenu;
  $name = 'News＆Events';
  if (isset($menu[5])) {
    $menu[5][0] = $name;
  }
  if (isset($submenu['edit.php'][5])) {
    $submenu['edit.php'][5][0] = $name . '一覧';
  }
  if (isset($submenu['edit.php'][10])) {
    $submenu['edit.php'][10][0] = '新しい' . $name;
  }
  if (isset($submenu['edit.php'][16])) {
    $submenu['edit.php'][16][0] = 'タグ';
  }
}

function change_post_object_label()
{
  global $wp_post_types;
  $name = 'News＆Events';
  $labels = &$wp_post_types['post']->labels;
  $labels->name = $name;
  $labels->singular_name = $name;
  $labels->add_new = _x('追加', $name);
  $labels->add_new_item = $name . 'の新規追加';
  $labels->edit_item = $name . 'の編集';
  $labels->new_item = '新規新' . $name;
  $labels->view_item = $name . 'を表示';
  $labels->search_items = $name . 'を検索';
  $labels->not_found = '記事が見つかりませんでした';
  $labels->not_found_in_trash = 'ゴミ箱に記事は見つかりませんでした';
}

add_action('init', 'change_post_object_label');
add_action('admin_menu', 'change_post_menu_label');

// ==========================================================================
// WordPress 投稿が0件でもpost_typeを取得
// ==========================================================================
function get_current_post_type()
{
  $post_type = get_post_type();

  if (empty($post_type)) {
    // 投稿が 0 件の時
    if (is_category()) {
      $tax = get_taxonomy('category');
      $post_type = $tax->object_type[0];
    } else
        if (is_tax()) {
      // category, taxonomy get_query_var( 'post_type' ) は常に null
      // 投稿が 1件 でもあれば　get_post_type() で取得可能
      $term = get_query_var('taxonomy');
      $tax = get_taxonomy($term);
      $post_type = $tax->object_type[0];
    } else
          if (is_archive()) {
      // 投稿が 0 件の時 get_post_type() は false を返すので get_query_var() で取得する
      $post_type = get_query_var('post_type');
    } else
            if (is_home()) {
      // 投稿の一覧で投稿が0件の時は 'post' を設定
      $post_type = 'post';
    }
  }
  return $post_type;
}

// ==========================================================================
// エスケープ除外用変数（特定のHTMLタグとそれぞれのタグで許可される属性を定義）
// 【使い方例】
// $allowed_html = get_allowed_html();
// $user_input = '<a href="https://example.com" onclick="alert(1)">Click me!</a>';
// $sanitized_output = wp_kses($user_input, $allowed_html);
// ==========================================================================
function get_allowed_html()
{
  return array(
    'a' => array(
      'href' => array(),
      'title' => array()
    ),
    'br' => array(),
    'em' => array(),
    'strong' => array()
  );
}

// ==========================================================================
// カスタム投稿のスラッグからラベルを取得する
// ==========================================================================
function get_post_type_label_by_slug($slug)
{
  $post_types = get_post_types(array(), 'objects'); // 全ての投稿タイプオブジェクトを取得
  foreach ($post_types as $post_type) {

    if (is_array($post_type->rewrite) && $post_type->rewrite['slug'] === $slug) {
      return $post_type->label; // その投稿タイプのラベルを返す
    }
  }
  return null; // マッチするものがなければnullを返す
}

// ==========================================================================
// 特定のカスタム投稿タイプに「固定表示」機能を追加＆表示件数の指定（通常のアーカイブページのみ）
// ==========================================================================
/**
 * カスタム投稿タイプに「先頭固定表示」機能を追加するクラス
 *
 * 通常の投稿における「この投稿を先頭に固定表示」と同等の機能を
 * カスタム投稿タイプのアーカイブページで実現します。
 *
 * 【機能概要】
 * - 投稿編集画面のサイドバーに「先頭に固定する」チェックボックスを追加
 * - クイック編集からも固定表示の切り替えが可能
 * - アーカイブページで固定投稿を先頭に表示（ページネーション対応）
 *
 * 【対象ページ】
 * - カスタム投稿タイプのアーカイブページのみ
 * - タクソノミーアーカイブ、年別アーカイブは対象外
 *
 * 【使用方法】
 * new CustomStickyPosts('post_type_name');           // デフォルト9件/ページ
 * new CustomStickyPosts('post_type_name', 12);       // 12件/ページ
 */
class CustomStickyPosts
{
  /** @var string 固定表示フラグ用メタキー（投稿単位） */
  private const META_KEY = '_is_sticky';

  /** @var string 対象の投稿タイプ */
  private $post_type;

  /** @var int 1ページあたりの表示件数 */
  private $posts_per_page;

  /** @var array|null 固定投稿IDのキャッシュ（同一リクエスト内で再利用） */
  private $sticky_posts_cache = null;

  /** @var int 現在のページ番号 */
  private $current_paged = 1;

  /** @var WP_Query|null the_postsフィルターで処理する対象クエリ */
  private $target_query = null;

  /**
   * コンストラクタ
   *
   * @param string $post_type      対象のカスタム投稿タイプ名
   * @param int    $posts_per_page 1ページあたりの表示件数（デフォルト: 9）
   */
  public function __construct($post_type, $posts_per_page = 9)
  {
    $this->post_type = $post_type;
    $this->posts_per_page = $posts_per_page;
    $this->init();
  }

  /**
   * WordPressのアクション・フィルターを登録
   *
   * @return void
   */
  private function init()
  {
    // ========================================
    // 投稿編集画面
    // ========================================
    add_action('add_meta_boxes', [$this, 'add_sticky_meta_box']);
    add_action("save_post_{$this->post_type}", [$this, 'save_sticky_status']);

    // ========================================
    // フロントエンド（アーカイブページ）
    // ========================================
    add_action('pre_get_posts', [$this, 'modify_archive_query']);
    add_filter('the_posts', [$this, 'merge_sticky_posts'], 10, 2);
    add_filter('found_posts', [$this, 'adjust_pagination'], 10, 2);

    // ========================================
    // 管理画面一覧（クイック編集）
    // ========================================
    add_filter("manage_{$this->post_type}_posts_columns", [$this, 'add_sticky_column']);
    add_action("manage_{$this->post_type}_posts_custom_column", [$this, 'render_sticky_column'], 10, 2);
    add_action('admin_head', [$this, 'hide_sticky_column_css']);
    add_action('quick_edit_custom_box', [$this, 'add_quick_edit_sticky_field'], 10, 2);
    add_action('admin_footer', [$this, 'quick_edit_javascript']);
    add_action('save_post', [$this, 'save_quick_edit_sticky']);
  }

  // ============================================================================
  // 投稿編集画面: メタボックス関連
  // ============================================================================

  /**
   * 投稿編集画面のサイドバーに「先頭に固定する」メタボックスを追加
   *
   * @return void
   */
  public function add_sticky_meta_box()
  {
    add_meta_box(
      'sticky_meta_box',
      '先頭に固定する',
      [$this, 'render_sticky_meta_box'],
      $this->post_type,
      'side',
      'high'
    );
  }

  /**
   * メタボックスの内容（チェックボックス）を出力
   *
   * @param WP_Post $post 現在編集中の投稿オブジェクト
   * @return void
   */
  public function render_sticky_meta_box($post)
  {
    wp_nonce_field('sticky_meta_box', 'sticky_meta_box_nonce');
    $is_sticky = get_post_meta($post->ID, self::META_KEY, true);
?>
    <label>
      <input type="checkbox" name="sticky" value="1" <?php checked($is_sticky, '1'); ?>>
      <?php esc_html_e('先頭固定表示', 'textdomain'); ?>
    </label>
  <?php
  }

  /**
   * 投稿保存時に固定表示状態をメタデータとして保存
   *
   * @param int $post_id 保存される投稿のID
   * @return void
   */
  public function save_sticky_status($post_id)
  {
    // 自動保存時はスキップ
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
      return;
    }
    // 権限チェック
    if (!current_user_can('edit_post', $post_id)) {
      return;
    }
    // nonceチェック（CSRF対策）
    if (!isset($_POST['sticky_meta_box_nonce']) || !wp_verify_nonce($_POST['sticky_meta_box_nonce'], 'sticky_meta_box')) {
      return;
    }

    $is_sticky = isset($_POST['sticky']) ? 1 : 0;
    update_post_meta($post_id, self::META_KEY, $is_sticky);
  }

  // ============================================================================
  // フロントエンド: アーカイブページのクエリ制御
  // ============================================================================

  /**
   * 処理対象のアーカイブページかどうかを判定
   *
   * 以下の条件をすべて満たす場合にtrueを返す:
   * - フロントエンド（管理画面ではない）
   * - メインクエリである
   * - 対象投稿タイプのアーカイブページ
   * - タクソノミーアーカイブではない
   * - 年別アーカイブではない
   *
   * @param WP_Query $query クエリオブジェクト
   * @return bool 対象アーカイブページの場合true
   */
  private function is_target_archive($query)
  {
    return !is_admin() &&
      $query->is_main_query() &&
      is_post_type_archive($this->post_type) &&
      !is_tax() &&
      !is_year();
  }

  /**
   * アーカイブページのメインクエリを変更
   *
   * 固定投稿を除外し、後のthe_postsフィルターで先頭にマージするための準備を行う
   * 2ページ目以降はオフセットを調整してページネーションを正しく機能させる
   * ※ここでは表示順は変えない
   *
   * @param WP_Query $query クエリオブジェクト
   * @return void
   */
  public function modify_archive_query($query)
  {
    if (!$this->is_target_archive($query)) {
      return;
    }

    $this->current_paged = get_query_var('paged') ?: 1;
    $sticky_posts = $this->get_sticky_posts();

    // 固定投稿を通常クエリから除外（後でマージするため）
    $query->set('post__not_in', $sticky_posts);
    $query->set('posts_per_page', $this->posts_per_page);

    // 2ページ目以降: 固定投稿分を考慮してオフセットを調整
    if ($this->current_paged > 1) {
      $offset = ($this->current_paged - 1) * $this->posts_per_page - count($sticky_posts);
      $query->set('offset', max(0, $offset));
    }

    // the_postsフィルターで処理する対象クエリとして保存
    $this->target_query = $query;
  }

  /**
   * 固定投稿を通常の投稿リストの先頭にマージ
   *
   * modify_archive_queryで保存した対象クエリに対してのみ実行される
   * クエリオブジェクトの参照比較により、別のクエリでの誤実行を防止
   * 
   * pre_get_posts では投稿配列そのものを操作できないため、
   * the_posts フィルターで最終的な配列を組み替える
   *
   * @param array    $posts 取得された投稿の配列
   * @param WP_Query $query クエリオブジェクト
   * @return array マージ後の投稿配列
   */
  public function merge_sticky_posts($posts, $query)
  {
    // 対象クエリでなければ処理しない（参照比較で厳密にチェック）
    if (
      $query !== $this->target_query ||
      !$query->is_post_type_archive($this->post_type)
    ) {
      return $posts;
    }

    // 多重実行防止のためリセット
    $this->target_query = null;

    $sticky_posts_ids = $this->get_sticky_posts();

    if (empty($sticky_posts_ids)) {
      return $posts;
    }

    // 現在のページに表示すべき固定投稿を取得
    $sticky_posts = get_posts([
      'post_type' => $this->post_type,
      'post__in' => $sticky_posts_ids,
      'orderby' => 'post__in',
      'posts_per_page' => $this->posts_per_page,
      'offset' => ($this->current_paged - 1) * $this->posts_per_page,
    ]);

    // 固定投稿 + 通常投稿をマージし、表示件数で切り詰め
    return array_slice(
      array_merge($sticky_posts, $posts),
      0,
      $this->posts_per_page
    );
  }

  /**
   * ページネーションの総投稿数を調整
   *
   * 固定投稿はクエリから除外されているため、
   * ページネーション計算のために固定投稿数を加算する
   *
   * @param int      $found_posts 見つかった投稿の総数
   * @param WP_Query $query       クエリオブジェクト
   * @return int 調整後の総数
   */
  public function adjust_pagination($found_posts, $query)
  {
    if ($this->is_target_archive($query)) {
      $found_posts += count($this->get_sticky_posts());
    }
    return $found_posts;
  }

  /**
   * 固定投稿のIDリストを取得（キャッシュ付き）
   *
   * 同一リクエスト内で複数回呼び出されるため、結果をキャッシュ
   * suppress_filtersで自身のフィルターによる無限ループを防止
   *
   * Bogo対応: suppress_filtersでBogoのロケールフィルターが無効化されるため、
   * 現在のロケール（_localeメタ）で明示的に絞り込む
   *
   * @return array 固定投稿のIDの配列
   */
  private function get_sticky_posts()
  {
    if ($this->sticky_posts_cache !== null) {
      return $this->sticky_posts_cache;
    }

    $this->sticky_posts_cache = get_posts([
      'post_type' => $this->post_type,
      'meta_query' => [
        'relation' => 'AND',
        [
          'key' => self::META_KEY,
          'value' => '1',
        ],
        [
          'key' => '_locale',
          'value' => get_locale(),
        ],
      ],
      'posts_per_page' => -1,
      'fields' => 'ids',
      'suppress_filters' => true,
    ]);

    return $this->sticky_posts_cache;
  }

  // ============================================================================
  // 管理画面一覧: クイック編集機能
  // ============================================================================

  /**
   * 投稿一覧に固定表示ステータス列を追加
   *
   * この列はCSSで非表示にするが、クイック編集のJavaScriptが
   * 固定表示状態を取得するために必要
   *
   * @param array $columns 列の配列
   * @return array 列を追加した配列
   */
  public function add_sticky_column($columns)
  {
    $columns['sticky'] = '先頭固定ステータス';
    return $columns;
  }

  /**
   * 固定表示ステータス列の内容を出力
   *
   * data-sticky属性に固定状態を持たせ、JavaScriptから参照可能にする
   *
   * @param string $column_name 列名
   * @param int    $post_id     投稿ID
   * @return void
   */
  public function render_sticky_column($column_name, $post_id)
  {
    if ($column_name !== 'sticky') {
      return;
    }
    $is_sticky = get_post_meta($post_id, self::META_KEY, true);
    echo '<span class="sticky-status" data-sticky="' . esc_attr($is_sticky ? '1' : '0') . '"></span>';
  }

  /**
   * 固定表示ステータス列をCSSで非表示
   *
   * 列自体は必要だが、ユーザーに見せる必要はないため非表示にする
   *
   * @return void
   */
  public function hide_sticky_column_css()
  {
    $screen = get_current_screen();
    if ($screen && $screen->post_type === $this->post_type) {
      echo '<style>.column-sticky { display: none; }</style>';
    }
  }

  /**
   * クイック編集フォームに固定表示チェックボックスを追加
   *
   * @param string $column_name 列名
   * @param string $post_type   投稿タイプ
   * @return void
   */
  public function add_quick_edit_sticky_field($column_name, $post_type)
  {
    if ($column_name !== 'sticky' || $post_type !== $this->post_type) {
      return;
    }
    wp_nonce_field('quick_edit_sticky', 'quick_edit_sticky_nonce');
  ?>
    <fieldset class="inline-edit-col-right">
      <div class="inline-edit-col">
        <label class="inline-edit-sticky">
          <input type="checkbox" name="sticky" value="1">
          <span class="checkbox-title"><?php esc_html_e('この投稿を先頭に固定表示', 'textdomain'); ?></span>
        </label>
      </div>
    </fieldset>
  <?php
  }

  /**
   * クイック編集展開時にチェックボックスの状態を復元するJavaScript
   *
   * WordPressのinlineEditPost.editをオーバーライドして、
   * 投稿行のdata-sticky属性からチェック状態を取得・反映する
   *
   * @return void
   */
  public function quick_edit_javascript()
  {
    $screen = get_current_screen();
    if (!$screen || $screen->post_type !== $this->post_type) {
      return;
    }
  ?>
    <script>
      jQuery(function($) {
        var $inlineEdit = inlineEditPost.edit;
        inlineEditPost.edit = function(id) {
          $inlineEdit.apply(this, arguments);
          var postId = typeof id === 'object' ? parseInt(this.getId(id)) : id;
          var $row = $('#post-' + postId);
          var isSticky = $row.find('.sticky-status').data('sticky');
          var $editRow = $('#edit-' + postId);
          $editRow.find('input[name="sticky"]').prop('checked', isSticky === 1 || isSticky === '1');
        };
      });
    </script>
<?php
  }

  /**
   * クイック編集からの保存処理
   *
   * @param int $post_id 保存される投稿のID
   * @return void
   */
  public function save_quick_edit_sticky($post_id)
  {
    // 自動保存時はスキップ
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
      return;
    }
    // 権限チェック
    if (!current_user_can('edit_post', $post_id)) {
      return;
    }
    // 対象投稿タイプのみ処理
    if (get_post_type($post_id) !== $this->post_type) {
      return;
    }
    // nonceチェック（CSRF対策）
    if (!isset($_POST['quick_edit_sticky_nonce']) || !wp_verify_nonce($_POST['quick_edit_sticky_nonce'], 'quick_edit_sticky')) {
      return;
    }

    $is_sticky = isset($_POST['sticky']) ? 1 : 0;
    update_post_meta($post_id, self::META_KEY, $is_sticky);
  }
}

/**
 * カスタム投稿タイプに固定表示機能を追加
 *
 * 使用例:
 *   new CustomStickyPosts('event');        // 'event'投稿タイプ、9件/ページ
 *   new CustomStickyPosts('news', 12);     // 'news'投稿タイプ、12件/ページ
 *
 * @return void
 */
function initialize_custom_sticky_posts()
{
  new CustomStickyPosts('highlights', 15);
  // new CustomStickyPosts('sample', 10); // 他の投稿タイプを追加する場合
}
add_action('init', 'initialize_custom_sticky_posts');

// ==========================================================================
// 固定表示を考慮して投稿を取得する関数（サブループ用）
// ==========================================================================
/**
 * 固定表示投稿を先頭にして投稿を取得する（サブループ用）
 *
 * CustomStickyPostsクラスはメインクエリ専用のため、
 * サブループ（トップページの新着一覧など）ではこの関数を使用する
 *
 * 【使用例】
 * $posts = get_posts_with_sticky_first('news', 5);
 * $posts = get_posts_with_sticky_first('event', 10, ['tax_query' => [...]]);
 *
 * @param string $post_type       投稿タイプ
 * @param int    $posts_per_page  表示件数（デフォルト: 10）
 * @param array  $additional_args 追加のWP_Query引数（tax_queryなど）
 * @return array WP_Postオブジェクトの配列（固定投稿が先頭）
 */
function get_posts_with_sticky_first($post_type, $posts_per_page = 10, $additional_args = [])
{
  $meta_key = '_is_sticky';
  $current_locale = get_locale();

  // 共通の引数・日付降順で取得（必要に応じて変更可）
  // Bogo対応: suppress_filtersでBogoのロケールフィルターが無効化されるため、
  // 現在のロケール（_localeメタ）で明示的に絞り込む
  $base_args = [
    'post_type' => $post_type,
    'orderby' => 'date',
    'order' => 'DESC',
    'suppress_filters' => true,
  ];

  $locale_meta_query = [
    'key' => '_locale',
    'value' => $current_locale,
  ];

  // 固定投稿を取得（表示件数で制限）
  $sticky_args = array_merge($base_args, $additional_args, [
    'posts_per_page' => $posts_per_page,
    'meta_query' => [
      'relation' => 'AND',
      [
        'key' => $meta_key,
        'value' => '1',
      ],
      $locale_meta_query,
    ],
  ]);
  $sticky_posts = get_posts($sticky_args);
  $sticky_count = count($sticky_posts);

  // 固定投稿がない場合は通常の取得のみ
  if ($sticky_count === 0) {
    $normal_args = array_merge($base_args, $additional_args, [
      'posts_per_page' => $posts_per_page,
      'meta_query' => [$locale_meta_query],
    ]);
    return get_posts($normal_args);
  }

  // 固定投稿だけで表示件数に達している場合
  if ($sticky_count >= $posts_per_page) {
    return $sticky_posts;
  }

  // 通常投稿を取得（固定投稿を除外、残り件数分のみ）
  $sticky_ids = wp_list_pluck($sticky_posts, 'ID');
  $normal_args = array_merge($base_args, $additional_args, [
    'posts_per_page' => $posts_per_page - $sticky_count,
    'post__not_in' => $sticky_ids,
    'meta_query' => [$locale_meta_query],
  ]);
  $normal_posts = get_posts($normal_args);

  // 固定投稿 + 通常投稿をマージ
  return array_merge($sticky_posts, $normal_posts);
}

// ==========================================================================
// post_typeによるリンクや属性を取得する関数
// ==========================================================================
/**
 * post_typeに基づいてURLとtarget属性を取得する関数
 * 
 * @param int|null $post_id 投稿ID（省略時は現在の投稿ID）
 * @return array URL、target属性を含む配列 ['url' => string, 'targetAttr' => string]
 */
function get_post_link_attributes($post_id = null)
{
  // 投稿IDが指定されていない場合は現在の投稿IDを取得
  if ($post_id === null) {
    $post_id = get_the_ID();
  }

  // ACFフィールド「投稿選択」からpost_typeを取得（ラジオボタンなので必ず値が入っている）
  $post_type = get_field('post_type', $post_id);

  // post_typeに応じてURLとtarget属性を設定
  $postType_url = '';
  $targetAttr = '';

  if ($post_type === 'url') {
    // リンクタイプ：外部リンクを使用
    $postType_url = get_field('postType_url', $post_id);
    // postType_targetがチェックされている場合は新しいタブで開く
    $postType_target = get_field('postType_target', $post_id);
    if ($postType_target) {
      $targetAttr = ' target="_blank" rel="noreferrer external"';
    }
  } elseif ($post_type === 'file') {
    // ファイルタイプ：PDFファイルのURLを取得
    $postType_file = get_field('postType_file', $post_id);
    if ($postType_file) {
      // 配列の場合はurlキーから取得、そうでなければそのまま使用
      $postType_url = is_array($postType_file) ? $postType_file['url'] : $postType_file;
      $targetAttr = ' target="_blank" rel="noreferrer external"';
    } else {
      // ファイルが設定されていない場合はパーマリンクを使用
      $postType_url = get_permalink($post_id);
    }
  } else {
    // 記事タイプ（post）またはpost_typeが未設定の場合：パーマリンクを使用
    $postType_url = get_permalink($post_id);
  }

  // URLが空の場合はパーマリンクをフォールバック
  if (empty($postType_url)) {
    $postType_url = get_permalink($post_id);
  }
  return [
    'url' => $postType_url,
    'targetAttr' => $targetAttr
  ];
}


// ==========================================================================
// セミナーカテゴリーのIDを取得
// ==========================================================================
/**
 * 「セミナー」カテゴリーの term_id を取得する
 *
 * デフォルトの category タクソノミーから slug = 'seminars' のカテゴリーを検索する。
 * カテゴリーが存在しない場合は 0 を返すため、呼び出し側で
 * category__in / category__not_in / cat などに渡してもエラーにならない。
 *
 * @return int カテゴリーの term_id（存在しない場合は 0）
 */
function nig_get_seminars_category_id()
{
  $category = get_category_by_slug('seminars');
  return $category ? (int) $category->term_id : 0;
}

// ==========================================================================
// Bogo
// ==========================================================================
// カスタム投稿タイプ対応
function my_localizable_post_types( $localizable ) {
	$localizable[] = 'highlights';
  $localizable[] = 'interviews';
  $localizable[] = 'student-message';
  $localizable[] = 'career';
  $localizable[] = 'seminar';
	return $localizable;
}
add_filter( 'bogo_localizable_post_types', 'my_localizable_post_types', 10, 1 );

// 国旗アイコンを削除
add_filter( 'bogo_use_flags','bogo_use_flags_false');
function bogo_use_flags_false(){
 return false;
}

// タクソノミーのアーカイブが404になる場合（リライトルールの設定）
function myUrlRewrite($rules){
  $myRule = array();
  // 年別アーカイブ用
  $myRule["^seminar/date/([0-9]{4})/?$"] = 'index.php?post_type=seminar&year=$matches[1]';
  $myRule["^seminar/category/([^/]*)/date/([0-9]{4})/?$"] = 'index.php?seminar_cat=$matches[1]&year=$matches[2]';
  $myRule["^en/seminar/date/([0-9]{4})/?$"] = 'index.php?post_type=seminar&year=$matches[1]&lang=en';
  $myRule["^en/seminar/category/([^/]*)/date/([0-9]{4})/?$"] = 'index.php?seminar_cat=$matches[1]&year=$matches[2]&lang=en';
  // 英語カテゴリー：ページネーション用
  $myRule["^en/highlights/category/([^/]+)/page/([0-9]{1,})/?$"] = 'index.php?post_type=highlights&highlights_cat=$matches[1]&paged=$matches[2]&lang=en';
  $myRule["^en/interviews/category/([^/]+)/page/([0-9]{1,})/?$"] = 'index.php?post_type=interviews&interviews_cat=$matches[1]&paged=$matches[2]&lang=en';
  $myRule["^en/student-message/category/([^/]+)/page/([0-9]{1,})/?$"] = 'index.php?post_type=student-message&student-message_cat=$matches[1]&paged=$matches[2]&lang=en';
  $myRule["^en/career/category/([^/]+)/page/([0-9]{1,})/?$"] = 'index.php?career_cat=$matches[1]&paged=$matches[2]&lang=en';
  $myRule["^en/seminar/category/([^/]+)/page/([0-9]{1,})/?$"] = 'index.php?seminar_cat=$matches[1]&paged=$matches[2]&lang=en';
  // カテゴリー用
  // ※送り先は index.php、クエリ変数はカスタムタクソノミー名（highlights_cat 等）を使う
  $myRule["^en/highlights/category/([^/]*)/?$"] = 'index.php?post_type=highlights&highlights_cat=$matches[1]&lang=en';
  $myRule["^en/interviews/category/([^/]*)/?$"] = 'index.php?post_type=interviews&interviews_cat=$matches[1]&lang=en';
  $myRule["^en/student-message/category/([^/]*)/?$"] = 'index.php?post_type=student-message&student-message_cat=$matches[1]&lang=en';
  $myRule["^en/career/category/([^/]*)/?$"] = 'index.php?career_cat=$matches[1]&lang=en';
  $myRule["^en/seminar/category/([^/]*)/?$"] = 'index.php?seminar_cat=$matches[1]&lang=en';

  return array_merge( $myRule, $rules );
}
add_filter('rewrite_rules_array', 'myUrlRewrite' );

// lang クエリ変数を WordPress に認識させる（Bogo の登録より確実にするため）
add_filter('query_vars', function($vars) {
  if (!in_array('lang', $vars)) {
    $vars[] = 'lang';
  }
  return $vars;
});

// highlights のアーカイブ（/highlights/）に直接アクセスされた場合は
// /highlights/category/latest-research/ にリダイレクトする
function redirect_highlights_archive() {
  // 投稿タイプ highlights のアーカイブのみ対象。
  // ※英語のカテゴリーURL（/en/highlights/category/...）はリライトで post_type=highlights が付与され
  //   is_post_type_archive('highlights') が true になるため、is_tax('highlights_cat') で明示的に除外し
  //   カテゴリーページ自身への無限リダイレクトを防ぐ
  if (is_post_type_archive('highlights') && !is_tax('highlights_cat')) {
    // 言語別のアーカイブURL（Bogo がプレフィックスを付与）に category パスを連結
    $redirect_url = get_post_type_archive_link('highlights') . 'category/latest-research/';
    wp_safe_redirect($redirect_url, 301);
    exit;
  }
}
add_action('template_redirect', 'redirect_highlights_archive');

// ==========================================================================
// 【一回限りの処理】highlights の既存記事に latest-research カテゴリーを一括付与
// joint-research カテゴリーを持つ記事は対象外。
// 実行方法: 管理者でログインした状態で、サイトURLに ?run_assign_latest_research=1 を付けてアクセスする。
// 実行が完了したら、この関数と add_action は削除すること。
// ==========================================================================
function assign_latest_research_to_existing_highlights() {
  // 管理者のみ実行可、かつ専用のクエリパラメータが付いている場合のみ動作
  if (!is_user_logged_in() || !current_user_can('manage_options')) {
    return;
  }
  if (empty($_GET['run_assign_latest_research'])) {
    return;
  }

  // 付与対象の latest-research ターム（無ければ作成）
  $target_term = get_term_by('slug', 'latest-research', 'highlights_cat');
  if (!$target_term) {
    $inserted = wp_insert_term('latest-research', 'highlights_cat', array('slug' => 'latest-research'));
    if (is_wp_error($inserted)) {
      wp_die('latest-research タームの作成に失敗しました: ' . esc_html($inserted->get_error_message()));
    }
    $target_term_id = (int) $inserted['term_id'];
  } else {
    $target_term_id = (int) $target_term->term_id;
  }

  // highlights の全記事を取得（全ステータス・全言語）
  // suppress_filters を true にして Bogo の言語フィルターを無効化し、言語に関係なく全件取得する
  $highlights_posts = get_posts(array(
    'post_type'        => 'highlights',
    'posts_per_page'   => -1,
    'post_status'      => 'any',
    'suppress_filters' => true,
    'fields'           => 'ids',
  ));

  $assigned_count = 0; // 付与した件数
  $skipped_count  = 0; // joint-research を持つためスキップした件数

  foreach ($highlights_posts as $post_id) {
    // joint-research カテゴリーを持つ記事はスキップ
    if (has_term('joint-research', 'highlights_cat', $post_id)) {
      $skipped_count++;
      continue;
    }
    // 既存のカテゴリーを残したまま（append = true）latest-research を追加
    wp_set_object_terms($post_id, array($target_term_id), 'highlights_cat', true);
    $assigned_count++;
  }

  wp_die(sprintf(
    'latest-research の一括付与が完了しました。付与: %d 件 / スキップ(joint-research): %d 件。<br>処理が終わったら custom.php の assign_latest_research_to_existing_highlights を削除してください。',
    $assigned_count,
    $skipped_count
  ));
}
add_action('init', 'assign_latest_research_to_existing_highlights');

// ==========================================================================
// highlights のカテゴリー(highlights_cat)をラジオボタン（単一選択）で表示する
// 新規投稿では latest-research を初期選択にする
// ※ ラジオボタンは常に1つ選択されるため「1件以上必須」も自然に満たせる
// ※ タクソノミーの meta_box_cb はブロックエディタでは表示されないため、
//    独自メタボックスとして add_meta_box() で登録する
// ==========================================================================
/**
 * highlights の編集画面に独自のカテゴリー（ラジオボタン）メタボックスを登録する
 *
 * @return void
 */
function highlights_add_cat_radio_meta_box()
{
  add_meta_box(
    'highlights_cat_radio',
    'カテゴリー',
    'highlights_cat_radio_meta_box',
    'highlights',
    'side',
    'default',
    array('taxonomy' => 'highlights_cat')
  );
}
add_action('add_meta_boxes', 'highlights_add_cat_radio_meta_box');

/**
 * highlights_cat をラジオボタン（単一選択）で表示するメタボックスのコールバック
 * 新規投稿時は latest-research を初期選択（無ければ先頭ターム）
 *
 * @param WP_Post $post 編集中の投稿オブジェクト
 * @param array   $box  メタボックス情報（args.taxonomy にタクソノミー名が入る）
 * @return void
 */
function highlights_cat_radio_meta_box($post, $box)
{
  $taxonomy = $box['args']['taxonomy'];

  $terms = get_terms(array(
    'taxonomy'   => $taxonomy,
    'hide_empty' => false,
    'orderby'    => 'id',
    'order'      => 'ASC',
  ));

  if (is_wp_error($terms) || empty($terms)) {
    echo '<p>カテゴリーが登録されていません。</p>';
    return;
  }

  $selected_terms = wp_get_object_terms($post->ID, $taxonomy, array('fields' => 'ids'));
  if (!empty($selected_terms)) {
    // 既存の選択を優先
    $selected = (int) $selected_terms[0];
  } else {
    // 新規投稿: latest-research を初期選択（無ければ先頭ターム）
    $default_term = get_term_by('slug', 'latest-research', $taxonomy);
    $selected = $default_term ? (int) $default_term->term_id : (int) $terms[0]->term_id;
  }

  echo '<ul>';
  foreach ($terms as $term) {
    printf(
      '<li><label><input type="radio" name="tax_input[%s][]" value="%d"%s> %s</label></li>',
      esc_attr($taxonomy),
      $term->term_id,
      checked($selected, (int) $term->term_id, false),
      esc_html($term->name)
    );
  }
  echo '</ul>';
}

// ==========================================================================
// highlights のクイック編集: チェックボックスをラジオボタン（単一選択）に変換
// ==========================================================================
/**
 * highlights 一覧画面のクイック編集で highlights_cat をラジオボタンに変換する
 *
 * @return void
 */
function highlights_cat_quick_edit_radio()
{
  $screen = get_current_screen();
  if (!$screen || $screen->post_type !== 'highlights') {
    return;
  }

  $tax = esc_js('highlights_cat');
?>
  <script>
    jQuery(function ($) {
      var $inlineEdit = inlineEditPost.edit;
      inlineEditPost.edit = function (id) {
        $inlineEdit.apply(this, arguments);
        var postId = typeof id === 'object' ? parseInt(this.getId(id)) : id;
        var $editRow = $('#edit-' + postId);
        var selector = 'input[name="tax_input[<?php echo $tax; ?>][]"]';
        // value="0" の隠し入力を削除
        $editRow.find(selector + '[value="0"]').remove();
        var $radios = $editRow.find(selector);
        $radios.attr('type', 'radio');
        // 複数チェック済みなら先頭1つのみ残す
        var $checked = $radios.filter(':checked');
        if ($checked.length > 1) {
          $checked.not(':first').prop('checked', false);
        }
      };
    });
  </script>
<?php
}
add_action('admin_footer', 'highlights_cat_quick_edit_radio');
// =============================================================================
// セミナーカテゴリー表示・並び順（ACF seminars_date / seminars_start / seminars_end）
// =============================================================================

/**
 * seminars_date を Ymd で比較して降順、同一日程は seminars_start を「時刻として」降順に並べ替える。
 * 並び順は開始時刻を 24 時間表記の H:i（ゼロ埋め）に揃えた文字列を strcmp で比較する（分を数値化して比較しない）。
 *
 * @param WP_Post|null $first_post_object 投稿オブジェクト。
 * @param WP_Post|null $second_post_object 投稿オブジェクト。
 * @return int strcmp 準拠の比較結果。
 */
function nig_compare_posts_by_seminars_meta($first_post_object, $second_post_object)
{
  $seminars_first_date_raw = (string) get_post_meta((int) $first_post_object->ID, 'seminars_date', true);
  $seminars_second_date_raw = (string) get_post_meta((int) $second_post_object->ID, 'seminars_date', true);
  $date_compare = strcmp($seminars_second_date_raw, $seminars_first_date_raw);
  if ($date_compare !== 0) {
    return $date_compare;
  }
  $seminars_first_start_clock_key = nig_seminars_meta_clock_compare_string_for_sort_order(
    (int) $first_post_object->ID,
    'seminars_start'
  );
  $seminars_second_start_clock_key = nig_seminars_meta_clock_compare_string_for_sort_order(
    (int) $second_post_object->ID,
    'seminars_start'
  );
  return strcmp($seminars_second_start_clock_key, $seminars_first_start_clock_key);
}

/**
 * 投稿配列をセミナー用メタ（seminars_date → seminars_start）で降順に並べ替える。
 *
 * @param array &$posts_sorted_by_reference WP_Post の配列（参照渡しで上書き）。
 */
function nig_sort_posts_by_seminars_meta(array &$posts_sorted_by_reference)
{
  usort($posts_sorted_by_reference, 'nig_compare_posts_by_seminars_meta');
}

/**
 * セミナーカテゴリーアーカイブ（is_category seminars）のメインクエリ結果を
 * seminars_date 降順 → 同日は seminars_start 降順に並べ替える。
 *
 * @param array     $posts 取得済み投稿配列。
 * @param \WP_Query $query 対象クエリ。
 * @return array 並べ替え後の投稿配列。
 */
function nig_sort_seminars_category_main_query_posts($posts, $query)
{
  if (is_admin() || !$query->is_main_query()) {
    return $posts;
  }
  if (!$query->is_category('seminars')) {
    return $posts;
  }
  if (!is_array($posts) || $posts === array()) {
    return $posts;
  }
  nig_sort_posts_by_seminars_meta($posts);
  return $posts;
}
add_filter('the_posts', 'nig_sort_seminars_category_main_query_posts', 10, 2);

/**
 * seminars_date の保存値（通常 return_format の Ymd）を、ACF フィールド定義の display_format で画面表示用に整形する。
 *
 * @param int    $target_post_id 投稿 ID（フィールド定義取得に使用）。
 * @param string $stored_yyyymmdd  post_meta 上の値（数字 8 桁に正規化済みでも可）。
 * @return string|false 表示用文字列。不正な日付のとき false。
 */
function nig_format_seminars_acf_display_date_string($target_post_id, $stored_yyyymmdd)
{
  $stored_digits_only = preg_replace('/\D/', '', (string) $stored_yyyymmdd);
  if ($stored_digits_only === '' || strlen($stored_digits_only) !== 8) {
    return false;
  }
  // !Ymd で日付を厳密に解釈する（月・日の桁あふれで補正させない）
  $parsed_seminar_datetime = \DateTimeImmutable::createFromFormat('!Ymd', $stored_digits_only);
  if ($parsed_seminar_datetime === false) {
    return false;
  }

  // ACF の Date Picker と同じく、管理画面で設定した display_format（例: Y/m/d）を PHP date と同様のトークンで解釈する
  $acf_date_display_pattern = 'Y/m/d';
  if (function_exists('get_field_object')) {
    $acf_seminars_date_field = get_field_object('seminars_date', (int) $target_post_id);
    if (
      is_array($acf_seminars_date_field)
      && !empty($acf_seminars_date_field['display_format'])
    ) {
      $acf_date_display_pattern = (string) $acf_seminars_date_field['display_format'];
    }
  }

  return $parsed_seminar_datetime->format($acf_date_display_pattern);
}

/**
 * get_post_meta の時刻文字列を DateTime に解釈する（過去の g:i 保存や H:i:s も吸収）。
 *
 * @param string $seminars_time_raw メタ値。
 * @return \DateTimeImmutable|null 解釈失敗時は null。
 */
function nig_parse_seminars_clock_string_to_immutable($seminars_time_raw)
{
  $trimmed_time = trim(sanitize_text_field((string) $seminars_time_raw));
  if ($trimmed_time === '') {
    return null;
  }
  $parse_pattern_candidates = array(
    'H:i:s',
    'H:i',
    'h:i:s',
    'h:i',
    'G:i:s',
    'G:i',
    'g:i:s',
    'g:i',
  );
  foreach ($parse_pattern_candidates as $parse_pattern_single) {
    $parsed_candidate_clock = \DateTimeImmutable::createFromFormat($parse_pattern_single, $trimmed_time);
    if ($parsed_candidate_clock !== false) {
      return $parsed_candidate_clock;
    }
  }
  // フォールバック: 時:分[:秒] を 24 時間の壁時計として解釈（先頭ゼロなしの互換）
  if (preg_match('/^(\d{1,2}):(\d{2})(?::(\d{2}))?$/', $trimmed_time, $hour_minute_capture)) {
    $hour_part = (int) $hour_minute_capture[1];
    $minute_part = (int) $hour_minute_capture[2];
    if ($hour_part >= 0 && $hour_part <= 23 && $minute_part >= 0 && $minute_part <= 59) {
      return (new \DateTimeImmutable('2000-01-01'))->setTime($hour_part, $minute_part, 0);
    }
  }
  return null;
}

/**
 * 同一日内の並び替え用に、開始時刻をstrcmp可能なキーへ正規化する（常に H:i・24時間・ゼロ埋め）。
 *
 * @param int    $target_post_id メタ参照用投稿 ID（後方互換のため残す。キー算出では未使用）。
 * @param string $acf_meta_field_name seminars_start 等。
 * @return string 比較用文字列。解釈できない場合は空文字。
 */
function nig_seminars_meta_clock_compare_string_for_sort_order($target_post_id, $acf_meta_field_name)
{
  $parsed_seminars_clock_immutable = nig_parse_seminars_clock_string_to_immutable(
    (string) get_post_meta((int) $target_post_id, $acf_meta_field_name, true)
  );
  if ($parsed_seminars_clock_immutable === null) {
    return '';
  }
  return $parsed_seminars_clock_immutable->format('H:i');
}

/**
 * 解釈済み時刻を、ACF Time Picker の return_format（なければ display_format、両方なければ H:i）で画面表示用に整形する。
 *
 * @param int                 $target_post_id フィールド定義取得用。
 * @param string              $acf_time_field_name seminars_start / seminars_end。
 * @param \DateTimeImmutable  $parsed_seminars_clock_immutable 一覧用に解釈済みの時刻。
 * @return string 表示文字列。
 */
function nig_format_seminars_clock_for_acf_return_format_display($target_post_id, $acf_time_field_name, \DateTimeImmutable $parsed_seminars_clock_immutable)
{
  $acf_time_output_pattern = 'H:i';
  if (function_exists('get_field_object')) {
    $acf_time_field_definition = get_field_object($acf_time_field_name, (int) $target_post_id);
    if (is_array($acf_time_field_definition)) {
      if (!empty($acf_time_field_definition['return_format'])) {
        $acf_time_output_pattern = (string) $acf_time_field_definition['return_format'];
      } elseif (!empty($acf_time_field_definition['display_format'])) {
        $acf_time_output_pattern = (string) $acf_time_field_definition['display_format'];
      }
    }
  }
  return $parsed_seminars_clock_immutable->format($acf_time_output_pattern);
}

/**
 * セミナーカテゴリーかつ日程メタがある場合、リスト見出し用の文言を組み立てる。
 * 日付部分は ACF seminars_date の display_format（例: Y/m/d）に追従する。
 *
 * @param WP_Post|null $target_post_obj 対象投稿（ループ時は明示指定推奨）。
 * @return string 表示用文言。データ不足や非対象投稿のときは空文字。
 */
function nig_build_seminars_list_heading(WP_Post $target_post_obj)
{
  // 親テーマ・子テーマのいずれでも slug seminars を正とする
  if (!has_category('seminars', $target_post_obj)) {
    return '';
  }
  // ACF 保存値はフィールドグループにより post_meta と一致（フォールバックで get_post_meta のみ）
  $seminars_date_yyyymmdd = preg_replace('/\D/', '', (string) get_post_meta((int) $target_post_obj->ID, 'seminars_date', true));
  if ($seminars_date_yyyymmdd === '' || strlen($seminars_date_yyyymmdd) !== 8) {
    return '';
  }
  $display_date_fragment = nig_format_seminars_acf_display_date_string((int) $target_post_obj->ID, $seminars_date_yyyymmdd);
  if ($display_date_fragment === false || $display_date_fragment === '') {
    return '';
  }
  $parsed_seminars_start_immutable = nig_parse_seminars_clock_string_to_immutable(
    (string) get_post_meta((int) $target_post_obj->ID, 'seminars_start', true)
  );
  $parsed_seminars_end_immutable = nig_parse_seminars_clock_string_to_immutable(
    (string) get_post_meta((int) $target_post_obj->ID, 'seminars_end', true)
  );

  // リストタイトル行の先頭: 日程 + （開始〜終了が揃っているときのみ）時間レンジ（ACF の return_format に合わせる）
  $schedule_fragment = '';
  if ($parsed_seminars_start_immutable !== null && $parsed_seminars_end_immutable !== null) {
    $seminars_start_for_heading = nig_format_seminars_clock_for_acf_return_format_display(
      (int) $target_post_obj->ID,
      'seminars_start',
      $parsed_seminars_start_immutable
    );
    $seminars_end_for_heading = nig_format_seminars_clock_for_acf_return_format_display(
      (int) $target_post_obj->ID,
      'seminars_end',
      $parsed_seminars_end_immutable
    );
    $schedule_fragment = "{$seminars_start_for_heading}-{$seminars_end_for_heading}";
  }

  // 時間を含めずに日付のみのときは単一スペースにまとめる
  $combined_prefix = trim("{$display_date_fragment} {$schedule_fragment}");
  $stripped_plain_title = wp_strip_all_tags(get_the_title($target_post_obj));
  return trim(($combined_prefix !== '' ? "{$combined_prefix} " : '') . '<br>' .  $stripped_plain_title);
}

/**
 * セミナー用リストの投稿日タイムエリアで使う datetime 属性（日付のみ）と、画面表示用文字列。
 *
 * @param WP_Post|null $target_post_obj 対象投稿。
 * @return array{0:?string,1:?string} [0] が datetime 属性用 YYYY-MM-DD、[1] が ACF の display_format に従った日付表示。非セミナー／データ不足時は両方 null。
 */
function nig_get_seminars_list_datetime_parts(WP_Post $target_post_obj)
{
  if (!has_category('seminars', $target_post_obj)) {
    return array(null, null);
  }
  $seminars_date_yyyymmdd = preg_replace('/\D/', '', (string) get_post_meta((int) $target_post_obj->ID, 'seminars_date', true));
  if ($seminars_date_yyyymmdd === '' || strlen($seminars_date_yyyymmdd) !== 8) {
    return array(null, null);
  }
  $formatted_for_display = nig_format_seminars_acf_display_date_string((int) $target_post_obj->ID, $seminars_date_yyyymmdd);
  if ($formatted_for_display === false || $formatted_for_display === '') {
    return array(null, null);
  }
  $year_digits = substr($seminars_date_yyyymmdd, 0, 4);
  $month_digits = substr($seminars_date_yyyymmdd, 4, 2);
  $day_digits = substr($seminars_date_yyyymmdd, 6, 2);
  $datetime_attr_candidate = "{$year_digits}-{$month_digits}-{$day_digits}";
  return array($datetime_attr_candidate, $formatted_for_display);
}