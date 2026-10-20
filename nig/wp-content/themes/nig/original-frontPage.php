<?php
/**
 * Template Name: フロントテンプレート
 */
$locale = get_locale();
$lang = explode("_", $locale)[0];
if ($lang === 'en') {
    $tm_title = 'Life: To probe, to know, to tremble';
    $link_text01 = 'Search for Research and Collaborative Projects';
    $link_text02 = 'Study at the Graduate School';
    $link_text03 = 'Learn About the Institute of Genetics';
    $link_text04 = 'Consider Industry-Academia Collaboration';
    $notice_title = 'Important Notice';
    $more_text = 'View More';
    $more_text_joint_research = 'Joint Research';
    $more_text_latest_research = 'Latest Research';
    $nopost_text = 'No articles found.';
} else {
    $tm_title = '生命　探り、知り、心躍る';
    $link_text01 = '研究・共同研究を<br class="_only-SP">探したい';
    $link_text02 = '大学院で<br class="_only-SP">学びたい';
    $link_text03 = '遺伝研を<br class="_only-SP">知りたい';
    $link_text04 = '産学連携を<br class="_only-SP">考えたい';
    $notice_title = '重要なお知らせ';
    $more_text = '一覧を見る';
    $more_text_joint_research = '共同研究';
    $more_text_latest_research = '研究成果';
    $nopost_text = '記事はありません。';
}
get_header(); ?>
<div class="top_mainVisual">
    <p class="tm_title"><span><?php echo $tm_title; ?></span></p>
    <div class="slider tm_slide-container">
        <?php if (have_rows('top_slider-01')): ?>
            <ul class="slider-wrapper">
                <?php $count = 0; ?>
                <?php while (have_rows('top_slider-01')): the_row(); ?>
                    <?php
                    $img_pc = get_sub_field('img_pc');
                    $thumb_pc = wp_get_attachment_image_src($img_pc, 'top_main_pc');
                    $alt_pc = isset(get_post($img_pc)->ID) ? get_post_meta(get_post($img_pc)->ID, '_wp_attachment_image_alt', true) : "";
                    $img_sp = get_sub_field('img_sp');
                    $thumb_sp = wp_get_attachment_image_src($img_sp, 'top_main_sp');
                    $alt_sp = isset(get_post($img_sp)->ID) ? get_post_meta(get_post($img_sp)->ID, '_wp_attachment_image_alt', true) : "";
                    $count++;
                    ?>
                    <li class="slider-slide<?php if($count === 1): ?> active<?php endif; ?>">
                        <p class="tm_background"><img src="<?php echo $thumb_sp[0]; ?>" alt="<?php echo $alt_sp; ?>" class="_only-SP"><img src="<?php echo $thumb_pc[0]; ?>" alt="<?php echo $alt_pc; ?>" class="_over-TB"></p>
                    </li>
                <?php endwhile; ?>
                <?php if($count === 1): ?>
                    <li class="slider-slide">
                        <p class="tm_background"><img src="<?php echo $thumb_sp[0]; ?>" alt="<?php echo $alt_sp; ?>" class="_only-SP"><img src="<?php echo $thumb_pc[0]; ?>" alt="<?php echo $alt_pc; ?>" class="_over-TB"></p>
                    </li>
                <?php endif; ?>
            </ul>
        <?php endif; ?>
    </div>
</div>
<main id="global_contents" class="global_contents" itemscope itemprop="mainContentOfPage">
    <div id="top_link-01" class="top_link-01">
        <div class="global_inner">
            <ul class="list">
                <li><a href="<?php echo esc_url(home_url('/')); ?>researchers/"><img src="<?php echo get_template_directory_uri(); ?>/images/home/icon_research.svg" alt="<?php echo strip_tags($link_text01); ?>"><span><?php echo $link_text01; ?></span></a></li>
                <li><a href="<?php echo esc_url(home_url('/')); ?>students/"><img src="<?php echo get_template_directory_uri(); ?>/images/home/icon_education.svg" alt="<?php echo strip_tags($link_text02); ?>"><span><?php echo $link_text02; ?></span></a></li>
                <li><a href="<?php echo esc_url(home_url('/')); ?>public/"><img src="<?php echo get_template_directory_uri(); ?>/images/home/icon_genetic.svg" alt="<?php echo strip_tags($link_text03); ?>"><span><?php echo $link_text03; ?></span></a></li>
                <li><a href="<?php echo esc_url(home_url('/')); ?>business/"><img src="<?php echo get_template_directory_uri(); ?>/images/home/icon_industry.svg" alt="<?php echo strip_tags($link_text04); ?>"><span><?php echo $link_text04; ?></span></a></li>
            </ul>
        </div>
    </div>
    <?php if (get_field('top_notice_select') && have_rows('top_notice-01')): ?>
        <section id="top_notice-01" class="top_notice-01">
            <div class="global_inner">
                <div class="notice">
                    <div class="head">
                        <h2 class="title"><?php echo $notice_title; ?></h2>
                    </div>
                    <div class="body">
                        <ul>
                            <?php while (have_rows('top_notice-01')): the_row(); ?>
                                <?php
                                $date = get_sub_field('date', false);
                                $date = new DateTime($date);
                                $label = get_sub_field('label');
                                $title = get_sub_field('title');
                                $url = get_sub_field('url');
                                $target = get_sub_field('target');
                                $none = get_sub_field('none');
                                ?>
                                <?php if (!$none): ?>
                                    <li>
                                        <?php if ($date): ?><p class="date"><time datetime="<?php echo $date->format('Y-m-d'); ?>"><?php echo $date->format('Y.m.d'); ?></time></p><?php endif; ?>
                                        <?php if ($label): ?><p class="label"><?php echo $label; ?></p><?php endif; ?>
                                        <?php if ($title): ?>
                                            <p class="title">
                                                <a <?php if (!empty($url)): ?>href="<?php echo esc_url($url); ?>" <?php if ($target): ?>target="_blank" <?php endif; ?><?php else: ?> class="_disabled" tabindex="-1" <?php endif; ?>>
                                                    <?php echo $title; ?>
                                                </a>
                                            </p>
                                        <?php endif; ?>
                                    </li>
                                <?php endif; ?>
                            <?php endwhile; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>
    <section id="top_research-01" class="top_research-01">
        <div class="global_inner">
            <h2 class="top_title-01">
                <span class="title-main">Our Research</span>
                <?php if ($lang !== 'en'): ?><span class="title-sub">最新研究成果</span><?php endif; ?>
            </h2>
        </div>
        <div class="main">
            <?php
            // 最新の固定投稿IDを取得
            $sticky_posts = get_option('sticky_posts');
            $latest_sticky_id = null;
            if (!empty($sticky_posts)) {
                $sticky_query = get_posts(array(
                    'suppress_filters' => false, //言語別設定
                    'lang' => $lang, //言語別設定
                    'post_type' => 'highlights',
                    'post__in' => $sticky_posts,
                    'posts_per_page' => 1,
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'has_password' => false,
                    // highlights_cat の joint-research を除外
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'highlights_cat',
                            'field' => 'slug',
                            'terms' => 'joint-research',
                            'operator' => 'NOT IN',
                        ),
                    ),
                ));
                if ($sticky_query) {
                    $latest_sticky_id = $sticky_query[0]->ID;
                }
            }
            ?>
            <?php if ($latest_sticky_id): ?>
                <div class="sticky global_inner">
                    <?php
                    // 最新の固定投稿だけ表示
                    $posts = get_posts(array(
                        'post_type' => 'highlights',
                        'p' => $latest_sticky_id,
                    ));
                    if ($posts):
                        get_template_part('list', 'cardSticky');
                    endif;
                    ?>
                </div>
            <?php endif; ?>
            <div class="list">
                <?php
                $arg = array(
                    'suppress_filters' => false, //言語別設定
                    'lang' => $lang, //言語別設定
                    'post_type' => 'highlights',
                    'posts_per_page' => 9,
                    'has_password' => false,
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'post__not_in' => $latest_sticky_id ? array($latest_sticky_id) : array(), // 最新の固定投稿を除外
                    'ignore_sticky_posts' => 1, // 最新の固定投稿を無視
                    // highlights_cat の joint-research を除外
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'highlights_cat',
                            'field' => 'slug',
                            'terms' => 'joint-research',
                            'operator' => 'NOT IN',
                        ),
                    ),
                );
                $posts = get_posts($arg);
                if ($posts): ?>
                    <?php get_template_part('list', 'card'); ?>
                <?php else: ?>
                    <p class="top_nopost"><?php echo $nopost_text; ?></p>
                <?php endif; ?>
            </div>
        </div>
        <p class="top_button">
            <a href="<?php echo get_post_type_archive_link('highlights'); ?>category/latest-research/" class="top_button-01"><span><?php echo $more_text_latest_research; ?></span></a>
            <a href="<?php echo get_post_type_archive_link('highlights'); ?>category/joint-research/" class="top_button-01"><span><?php echo $more_text_joint_research; ?></span></a>
        </p>
    </section>
    <section id="top_interview-01" class="top_interview-01">
        <div class="global_inner">
            <h2 class="top_title-01">
                <span class="title-main">Interviews</span>
                <?php if ($lang !== 'en'): ?><span class="title-sub">インタビュー</span><?php endif; ?>
            </h2>
            <?php
            $arg = array(
                'suppress_filters' => false, //言語別設定
                'lang' => $lang, //言語別設定
                'posts_per_page' => 4,
                'has_password' => false,
                'post_type' => 'interviews',
                'orderby' => 'date',
                'order' => 'DESC'
            );
            $posts = get_posts($arg);
            if ($posts): ?>
                <?php get_template_part('list', 'interview'); ?>
            <?php else: ?>
                <p class="top_nopost"><?php echo $nopost_text; ?></p>
            <?php endif; ?>
            <p class="top_button"><a href="<?php echo get_post_type_archive_link('interviews'); ?>" class="top_button-01"><span><?php echo $more_text; ?></span></a></p>
        </div>
    </section>
    <section id="top_news-01" class="top_news-01">
        <div class="global_inner">
            <h2 class="top_title-01">
                <span class="title-main">News＆Events</span>
                <?php if ($lang !== 'en'): ?><span class="title-sub">お知らせ</span><?php endif; ?>
            </h2>
        </div>
        <div class="main global_inner">
            <?php
            // 最新の固定投稿IDを取得
            $sticky_posts = get_option('sticky_posts');
            $latest_sticky_id = null;
            if (!empty($sticky_posts)) {
                $sticky_query = get_posts(array(
                    'suppress_filters' => false, //言語別設定
                    'lang' => $lang, //言語別設定
                    'post__in' => $sticky_posts,
                    'posts_per_page' => 1,
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'has_password' => false,
                ));
                if ($sticky_query) {
                    $latest_sticky_id = $sticky_query[0]->ID;
                }
            }
            ?>
            <?php if ($latest_sticky_id): ?>
                <div class="sticky">
                    <?php
                    // 最新の固定投稿だけ表示
                    $posts = get_posts(array(
                        'p' => $latest_sticky_id,
                    ));
                    if ($posts):
                        get_template_part('list', 'newsSticky');
                    endif;
                    ?>
                </div>
            <?php endif; ?>
            <div class="list">
                <div class="module_tab-01">
                    <!-- タブ -->
                    <ul class="module_tab-head">
                        <li><a href="#tab01-01">All</a></li>
                        <?php
                        $counter = 1;
                        $arg = array(
                            'suppress_filters' => false, //言語別設定
                            'lang' => $lang, //言語別設定
                            'parent' => '0', // 親カテゴリーのみ
                            'orderby' => 'description',
                            'order' => 'ASC',
                            'hide_empty' => true, // 投稿がないカテゴリを出すかどうか
                        );
                        $categories = get_categories($arg);
                        foreach ($categories as $category):
                        ?>
                            <?php
                            $counter++;
                            ?>
                            <li class="<?php echo $category->slug; ?>"><a href="#tab01-<?php echo sprintf('%02d', $counter); ?>"><?php echo $category->name; ?></a></li>
                        <?php endforeach; //$categories ?>
                    </ul>
                    <!-- 全て -->
                    <div class="module_tab-body" id="tab01-01">
                        <?php
                        $arg = array(
                            'suppress_filters' => false, //言語別設定
                            'lang' => $lang, //言語別設定
                            'posts_per_page' => 4,
                            'has_password' => false,
                            'orderby' => 'date',
                            'order' => 'DESC',
                            'post__not_in' => $latest_sticky_id ? array($latest_sticky_id) : array(), // 最新の固定投稿を除外
                            'ignore_sticky_posts' => 1, // 最新の固定投稿を無視
                        );
                        $seminars_category_id = nig_get_seminars_category_id();
                        if ($seminars_category_id) {
                            $arg['category__not_in'] = array($seminars_category_id);
                        }
                        $posts = get_posts($arg);
                        if ($posts): ?>
                            <?php get_template_part('list', 'news'); ?>
                        <?php else: ?>
                            <p class="top_nopost"><?php echo $nopost_text; ?></p>
                        <?php endif; ?>
                    </div>
                    <!-- カテゴリ別 -->
                    <?php
                    $counter = 1;
                    $arg = array(
                        'suppress_filters' => false, //言語別設定
                        'lang' => $lang, //言語別設定
                        'parent' => '0', // 親カテゴリーのみ
                        'orderby' => 'description',
                        'order' => 'ASC',
                        'hide_empty' => true, // 投稿がないカテゴリを出すかどうか
                    );
                    $categories = get_categories($arg);
                    foreach($categories as $category):
                    ?>
                    <?php $counter++; ?>
                    <div class="module_tab-body" id="tab01-<?php echo sprintf('%02d', $counter); ?>">
                        <?php
                        $arg = array(
                            'suppress_filters' => false, //言語別設定
                            'lang' => $lang, //言語別設定
                            'cat' => $category->term_id,
                            'posts_per_page' => 4,
                            'has_password' => false,
                            'orderby' => 'date',
                            'order' => 'DESC',
                            'post__not_in' => $latest_sticky_id ? array($latest_sticky_id) : array(), // 最新の固定投稿を除外
                            'ignore_sticky_posts' => 1, // 最新の固定投稿を無視
                        );
                        $posts = get_posts($arg);
                        /** セミナータブは ACF の開催日程で並べる（同日は開始時刻が遅い順で降順） */
                        if (!empty($posts) && isset($category->slug) && $category->slug === 'seminars') {
                            nig_sort_posts_by_seminars_meta($posts);
                        }
                        if ($posts): ?>
                            <?php get_template_part('list', 'news'); ?>
                        <?php else: ?>
                            <p class="top_nopost"><?php echo $nopost_text; ?></p>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; //$categories ?>
                </div><!-- /module_tab-01 -->
            </div>
            <p class="top_button"><a href="<?php echo get_post_type_archive_link('post'); ?>" class="top_button-01"><span><?php echo $more_text; ?></span></a></p>
        </div>
    </section>
    <section id="top_scientists-01" class="top_scientists-01">
        <div class="global_inner">
            <h2 class="top_title-01">
                <span class="title-main">Our Scientists</span>
                <?php if ($lang !== 'en'): ?><span class="title-sub">研究者と研究室</span><?php endif; ?>
            </h2>
        </div>
        <?php if (have_rows('top_scientists-01')): ?>
            <div class="swiper scientists_swiper-container">
                <ul class="swiper-wrapper">
                    <?php while (have_rows('top_scientists-01')): the_row(); ?>
                        <?php
                        $img = get_sub_field('img');
                        $thumb = wp_get_attachment_image_src($img, 'top_img');
                        $url = get_sub_field('url');
                        $title = get_sub_field('title');
                        $theme = get_sub_field('theme');
                        ?>
                        <li class="swiper-slide">
                            <a <?php if (!empty($url)): ?>href="<?php echo esc_url($url); ?>"<?php else: ?> class="_disabled" tabindex="-1" <?php endif; ?>>
                                <?php if ($img) : ?>
                                    <p class="image"><img src="<?php echo $thumb[0]; ?>" alt="<?php echo strip_tags($title); ?>"></p>
                                <?php else : ?>
                                    <p class="image _noImage"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/images/common/noimage_logo_01.svg" alt="<?php echo strip_tags($title); ?>"></p>
                                <?php endif; //$img ?>
                                <?php if ($title): ?><h3 class="title"><?php echo $title; ?></h3><?php endif; ?>
                                <?php if ($theme): ?><p class="theme"><?php echo $theme; ?></p><?php endif; ?>
                            </a>
                        </li>
                    <?php endwhile; ?>
                </ul>
                <div class="swiper-control global_inner">
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                </div>
            </div>
        <?php endif; ?>
        <?php if (have_rows('top_scientists-02')): ?>
            <div class="global_inner">
                <ul class="list">
                    <?php while (have_rows('top_scientists-02')): the_row(); ?>
                        <?php
                        $img = get_sub_field('img');
                        $thumb = wp_get_attachment_image_src($img, 'top_img');
                        $title = get_sub_field('title');
                        $alt = isset(get_post($img)->ID) ? get_post_meta(get_post($img)->ID, '_wp_attachment_image_alt', true) : "";
                        $url = get_sub_field('url');
                        $genre = get_sub_field('genre');
                        $text = get_sub_field('text');
                        ?>
                        <li>
                            <a <?php if (!empty($url)): ?>href="<?php echo esc_url($url); ?>"<?php else: ?> class="_disabled" tabindex="-1" <?php endif; ?>>
                                <div class="head">
                                    <?php if ($img): ?><p class="image"><img src="<?php echo $thumb[0]; ?>" alt="<?php echo $alt; ?>"></p><?php endif; ?>
                                    <div class="titleArea">
                                        <?php if ($genre): ?><p class="genre"><?php echo $genre; ?></p><?php endif; ?>
                                        <?php if ($title): ?><h3 class="title"><?php echo $title; ?></h3><?php endif; ?>
                                    </div>
                                </div>
                                <?php if ($text): ?>
                                    <div class="body">
                                        <p class="text"><?php echo $text; ?></p>
                                    </div>
                                <?php endif; ?>
                            </a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        <?php endif; ?>
    </section>
    <?php if (have_rows('top_recommends-01')): ?>
        <section id="top_recommends-01" class="top_recommends-01">
            <div class="global_inner">
                <h2 class="top_title-01"><span class="title-main">Our <br class="_over-TB">Recommendations</span></h2>
                <ul class="list">
                    <?php while (have_rows('top_recommends-01')): the_row(); ?>
                        <?php
                        $img = get_sub_field('img');
                        $thumb = wp_get_attachment_image_src($img, 'top_img');
                        $title = get_sub_field('title');
                        $alt = isset(get_post($img)->ID) ? get_post_meta(get_post($img)->ID, '_wp_attachment_image_alt', true) : "";
                        $url = get_sub_field('url');
                        $target = get_sub_field('target');
                        ?>
                        <li>
                            <a <?php if (!empty($url)): ?>href="<?php echo esc_url($url); ?>" <?php if ($target): ?>target="_blank" <?php endif; ?><?php else: ?> class="_disabled" tabindex="-1" <?php endif; ?>>
                                <?php if ($img): ?><p class="image"><img src="<?php echo $thumb[0]; ?>" alt="<?php echo $alt; ?>"></p><?php endif; ?>
                                <?php if ($title): ?><p class="title"><?php echo $title; ?></p><?php endif; ?>
                            </a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </section>
    <?php endif; ?>
</main>
<?php get_footer(); ?>
