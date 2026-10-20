<?php
// 投稿タイプとタクソノミーの対応マッピング
$post_type_taxonomy_map = [
    'post' => [
        'taxonomy' => 'category',
        'field' => 'block_category'
    ],
    'event' => [
        'taxonomy' => 'event_cat',
        'field' => 'block_event_cat'
    ],
];

// 基本設定の取得
$post_type = get_field('block_post_type') ?: 'post';
$posts_per_page = get_field('block_posts_per_page');

// タクソノミーとタームの取得
$taxonomy = '';
$terms = [];

if (isset($post_type_taxonomy_map[$post_type])) {
    $taxonomy = $post_type_taxonomy_map[$post_type]['taxonomy'];
    $field = $post_type_taxonomy_map[$post_type]['field'];
    $raw_terms = get_field($field);

    // タームを配列に正規化
    if (!empty($raw_terms)) {
        if (!is_array($raw_terms)) {
            $raw_terms = [$raw_terms];
        }
        foreach ($raw_terms as $term) {
            if (is_object($term) && isset($term->term_id)) {
                $terms[] = (int)$term->term_id;
            } elseif (is_array($term) && isset($term['term_id'])) {
                $terms[] = (int)$term['term_id'];
            } elseif (is_numeric($term)) {
                $terms[] = (int)$term;
            }
        }
    }
}

// クエリの構築
$args = [
    'post_type' => $post_type,
    'post_status' => 'publish',
    'posts_per_page' => $posts_per_page ?: -1,
];

// タームによる絞り込み
if (!empty($terms)) {
    $args['tax_query'] = [[
        'taxonomy' => $taxonomy,
        'field' => 'term_id',
        'terms' => $terms,
        'operator' => 'IN',
        'include_children' => true,
    ]];
}

$query = new WP_Query($args);

// 投稿一覧ブロックで「カテゴリーが seminars のみ」選択されているときだけ、一覧の並び順を開催日程ベースにする
if (
    $post_type === 'post'
    && $query->have_posts()
    && $taxonomy !== ''
    && !empty($terms)
    && count($terms) === 1
) {
    $block_category_term_candidate = get_term((int)$terms[0], $taxonomy);
    if (
        $block_category_term_candidate
        && !is_wp_error($block_category_term_candidate)
        && $block_category_term_candidate->slug === 'seminars'
    ) {
        $posts_ordered_for_seminars = $query->posts;
        nig_sort_posts_by_seminars_meta($posts_ordered_for_seminars);
        $query->posts = $posts_ordered_for_seminars;
        $query->post_count = count($posts_ordered_for_seminars);
    }
}

// 投稿タイプに応じた出力処理
switch ($post_type) {
    case 'post':
        if ($query->have_posts()) {
            global $wp_query;
            $main_query = $wp_query;
            $wp_query = $query;
            get_template_part('list', 'news');
            $wp_query = $main_query;
        } else {
            $lang = explode('_', get_locale())[0];
            echo '<p>' . ($lang === 'en' ? 'No posts found.' : '該当する投稿はございません。') . '</p>';
        }
        break;

    case 'event':
        if ($query->have_posts()) {
            global $wp_query;
            $main_query = $wp_query;
            $wp_query = $query;
            get_template_part('list', 'card');
            $wp_query = $main_query;
        } else {
            $lang = explode('_', get_locale())[0];
            echo '<p>' . ($lang === 'en' ? 'No posts found.' : '該当する投稿はございません。') . '</p>';
        }
        break;
}

wp_reset_postdata();
