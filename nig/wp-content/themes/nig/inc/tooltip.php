<?php

if (!function_exists('nig_get_tooltip_items')) {
  /**
   * ACF Repeater "tooltip" からツールチップ配列を取得
   */
  function nig_get_tooltip_items($post_id = null, $field_name = 'tooltip')
  {
    if (!function_exists('have_rows')) {
      return [];
    }

    if ($post_id === null) {
      $post_id = get_the_ID();
    }

    if (!$post_id || !have_rows($field_name, $post_id)) {
      return [];
    }

    $items = [];
    $index = 1;

    while (have_rows($field_name, $post_id)) {
      the_row();
      $title = trim((string) get_sub_field('title'));
      $text = trim((string) get_sub_field('text'));

      if ($title === '' || $text === '') {
        continue;
      }

      $items[] = [
        'id' => 'tooltip-' . $index,
        'title' => $title,
        'text' => $text,
      ];
      $index++;
    }

    return $items;
  }
}

if (!function_exists('nig_render_tooltip_data_script')) {
  /**
   * JS用のJSONデータをscriptタグで出力
   */
  function nig_render_tooltip_data_script($post_id = null, $field_name = 'tooltip')
  {
    $items = nig_get_tooltip_items($post_id, $field_name);
    if (empty($items)) {
      return;
    }

    echo '<script type="application/json" class="js-tooltip-data">';
    echo wp_json_encode($items, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE);
    echo '</script>';
  }
}

