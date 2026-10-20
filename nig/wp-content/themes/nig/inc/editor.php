<?php

/**
 * --------------------------------------------------------------------------------
 * 
 * エディターの設定
 * 
 * --------------------------------------------------------------------------------
 */

// ==========================================================================
// カスタム投稿タイプの記事一覧に作成者情報を表示
// http://bashalog.c-brains.jp/14/02/21-153032.php
// ==========================================================================
// カスタム投稿タイプの記事一覧に投稿者の項目を追加する
function custom_author_columns($columns)
{
  $columns['author'] = '作成者';
  return $columns;
}
function custom_author_column($column, $post_id)
{
  if ('author' == $column) {
    $value = get_the_term_list($post_id, 'author');
    echo esc_attr($value);
  }
}
add_filter('manage_posts_columns', 'custom_author_columns');
add_action('manage_posts_custom_column', 'custom_author_column', 10, 2);

// ==========================================================================
// 【管理画面】カテゴリーにチェックを入れた後も順番を維持する
// ==========================================================================
function keep_admin_posts_category_order($args, $post_id = null)
{
  $args['checked_ontop'] = false;
  return $args;
}
add_action('wp_terms_checklist_args', 'keep_admin_posts_category_order');

// ==========================================================================
// 自動整形を無効設定（固定ページ）
// ==========================================================================
add_filter('the_content', 'wpautop_filter', 9);
function wpautop_filter($content)
{
  global $post;
  $remove_filter = false;
  $arr_types     = array('page'); //自動整形を無効にする投稿タイプを記述

  // $postオブジェクトが存在するかチェック
  if (!$post || !isset($post->ID)) {
    return $content;
  }

  $post_type     = get_post_type($post->ID);
  if (in_array($post_type, $arr_types)) {
    $remove_filter = true;
  }
  if ($remove_filter) {
    remove_filter('the_content', 'wpautop');
    remove_filter('the_excerpt', 'wpautop');
  }

  return $content;
}

// ==========================================================================
// 【管理画面】ページの属性で非公開などを親に選択できるようにする
// ==========================================================================
// add_filter('page_attributes_dropdown_pages_args', 'add_dropdown_pages');
// add_filter('quick_edit_dropdown_pages_args', 'add_dropdown_pages');
// function add_dropdown_pages($add_dropdown_pages, $post = NULL)
// {
//   $add_dropdown_pages['post_status'] = array('publish', 'future', 'draft', 'pending', 'private',); // 公開済、予約済、下書き、承認待ち、非公開、を選択
//   return $add_dropdown_pages;
// }

// ==========================================================================
// 【管理画面】リビジョン上限設定
// ==========================================================================

function set_revision_store_number($num, $post)
{
  if ('page' == $post->post_type) {
    $num = 20;
  }
  return $num;
}
add_filter('wp_revisions_to_keep', 'set_revision_store_number', 10, 2);

// ==========================================================================
// アイキャッチ画像の設定　※案件毎に設定
// ==========================================================================
add_theme_support('post-thumbnails');
add_image_size('page_img', 1380, 300, true);     //固定ページメインビジュアル
add_image_size('post_img', 360, 240, true);      //投稿一覧サムネイル
add_image_size('top_main_pc', 2760, 1296, true); //トップメインビジュアルPC
add_image_size('top_main_sp', 750, 976, true);   //トップメインビジュアルSP
add_image_size('top_img', 750, 999, false);      //トップ画像
add_image_size('targettop_main_pc', 2760, 832, true); //対象者トップメインビジュアルPC
add_image_size('targettop_main_sp', 750, 512, true);  //対象者トップメインビジュアルSP

// ==========================================================================
// ビジュアルモードとテキストモードを切り替えることで、spanやstyleなどのタグが自動で削除されてしまうのを防止
// https://blog.yuhiisk.com/archive/2017/05/11/tiny-mce-before-init-setting.html#:~:text=WordPress%E3%81%A7%E8%A8%98%E4%BA%8B%E3%82%92%E7%B7%A8%E9%9B%86,%E3%81%93%E3%81%A8%E3%81%A7%E5%9B%9E%E9%81%BF%E3%81%A7%E3%81%8D%E3%81%BE%E3%81%99%E3%80%82
// ==========================================================================
function my_tiny_mce_before_init($init_array)
{
  $init_array['valid_elements']          = '*[*]';
  $init_array['extended_valid_elements'] = '*[*]';

  return $init_array;
}
add_filter('tiny_mce_before_init', 'my_tiny_mce_before_init');

// ==========================================================================
// ボタンのブロックスタイルを削除
// ==========================================================================
function hide_button_block_all_style_ui()
{
  echo <<<HTML
  <style>
  /* ▼ 上部ツールバー内「ブロックスイッチャー」非表示（ボタンブロック限定） */
  .block-editor-block-contextual-toolbar [aria-label="ボタン"] {
    display: none !important;
  }

  /* ▼ サイドバー「スタイル」タブ非表示（タブボタンと中身） */
  .block-editor-block-inspector [role="tab"][aria-label="スタイル"],
  .block-editor-block-inspector [id^="tabs-"][id\$="-styles-view"],
  .block-editor-block-inspector [aria-labelledby^="tabs-"][aria-labelledby\$="-styles"] {
    display: none !important;
  }

  </style>
  HTML;
}
//add_action('admin_head', 'hide_button_block_all_style_ui');

// ==========================================================================
//カラーパレット 追加
// ==========================================================================
function add_custom_color_palette()
{
  add_theme_support('editor-color-palette', array(
    array(
      'name' => 'ボーダー',
      'slug' => 'border',
      'color' => '#009F7F',
    ),
    array(
      'name' => 'グレー',
      'slug' => 'gray',
      'color' => '#F0F0F0',
    ),
  ));
}
add_action('after_setup_theme', 'add_custom_color_palette');
