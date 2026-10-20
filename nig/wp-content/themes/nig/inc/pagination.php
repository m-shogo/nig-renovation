<?php

/**
 * --------------------------------------------------------------------------------
 * 
 * ページネーションの設定
 * 
 * --------------------------------------------------------------------------------
 */

// ==========================================================================
// レスポンシブページネーション
// ==========================================================================
function responsive_pagination($pages = '', $range = 4)
{
  $showitems = ($range * 2) + 1;

  global $paged;
  if (empty($paged)) {
    $paged = 1;
  }

  //ページ情報の取得
  if ($pages == '') {
    global $wp_query;
    $pages = $wp_query->max_num_pages;
    if (! $pages) {
      $pages = 1;
    }
  }

  if ( 1 != $pages ) {
    echo '<ul class="module_pager-01" role="menubar" aria-label="Pagination">';
    echo '<li class="prev"><a href="' . get_pagenum_link( $paged - 1 ) . '"><i class="fa-solid fa-arrow-right"></i></a></li>';

//    if ( is_paged() ) {
//      //先頭へ
//      echo '<li class="prev"><a href="' . get_pagenum_link( 1 ) . '"><i class="fa-solid fa-arrow-left"></i></a></li>';
//      //1つ戻る
//      echo '<li class="prev"><a href="' . get_pagenum_link( $paged - 1 ) . '"><i class="fal fa-angle-left"></i></a></li>';
//    }
    //番号つきページ送りボタン
    for ( $i = 1; $i <= $pages; $i ++ ) {
      if ( 1 != $pages && ( ! ( $i >= $paged + $range + 1 || $i <= $paged - $range - 1 ) || $pages <= $showitems ) ) {
        echo ( $paged == $i ) ? '<li class="current"><a class="current">' . $i . '</a></li>' : '<li><a href="' . get_pagenum_link( $i ) . '" class="inactive" >' . $i . '</a></li>';
      }
    }
    echo '<li class="next"><a href="' . get_pagenum_link( $paged + 1 ) . '"><i class="fa-solid fa-arrow-right"></i></a></li>';
//    if ( $paged != $pages ) {
//      //1つ進む
//      //echo '<li class="next"><a href="' . get_pagenum_link( $paged + 1 ) . '"><i class="fal fa-angle-right"></i></a></li>';
//      //最後尾へ
//      echo '<li class="next"><a href="' . get_pagenum_link( $pages ) . '"><i class="fa-solid fa-arrow-right"></i></a></li>';
//    }
    echo '</ul>';
  } 
}

function filter_to_archives_link($link_html, $url, $text, $format, $before, $after)
{
  if ('html' === $format) {
    $link_html = "<li><a href='$url'><span>$text</span></a></li>\n";
  }

  return $link_html;
}

add_filter('get_archives_link', 'filter_to_archives_link', 10, 6);
