<?php
/**
 * Template Name: 対象者トップテンプレート
 */
$locale = get_locale();
$lang = explode("_", $locale)[0];
if ($lang === 'en') {
    $more_text = 'View More';
    $nopost_text = 'No articles found.';
} else {
    $more_text = '一覧を見る';
    $nopost_text = '記事はありません。';
}
global $post;
get_header(); ?>
<div class="targetTop_mainVisual">
    <?php
    $tm_title = get_field('targettop_slider-text');
    if($tm_title): ?>
        <p class="tm_title"><?php echo $tm_title; ?></p>
    <?php endif; ?>
    <div class="slider tm_slide-container">
        <?php if (have_rows('targettop_slider-01')): ?>
            <ul class="slider-wrapper">
                <?php $count = 0; ?>
                <?php while (have_rows('targettop_slider-01')): the_row(); ?>
                    <?php
                    $img_pc = get_sub_field('img_pc');
                    $thumb_pc = wp_get_attachment_image_src($img_pc, 'targettop_main_pc');
                    $alt_pc = isset(get_post($img_pc)->ID) ? get_post_meta(get_post($img_pc)->ID, '_wp_attachment_image_alt', true) : "";
                    $img_sp = get_sub_field('img_sp');
                    $thumb_sp = wp_get_attachment_image_src($img_sp, 'targettop_main_sp');
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
    <?php if (have_rows('targettop_pickup-01')): ?>
        <div id="targetTop_pickup-01" class="targetTop_pickup-01">
            <div class="global_inner">
                <ul class="list">
                    <?php while (have_rows('targettop_pickup-01')): the_row(); ?>
                        <?php
                        $img = get_sub_field('img');
                        $thumb = wp_get_attachment_image_src($img, 'top_img');
                        $title = get_sub_field('title');
                        $alt = isset(get_post($img)->ID) ? get_post_meta(get_post($img)->ID, '_wp_attachment_image_alt', true) : "";
                        $text = get_sub_field('text');
                        $url = get_sub_field('url');
                        ?>
                        <li>
                            <a <?php if (!empty($url)): ?>href="<?php echo esc_url($url); ?>"<?php else: ?> class="_disabled" tabindex="-1" <?php endif; ?>>
                                <?php if ($img): ?><p class="image"><img src="<?php echo $thumb[0]; ?>" alt="<?php echo $alt; ?>"></p><?php endif; ?>
                                <div class="textArea">
                                    <?php if ($title): ?><p class="title"><?php echo $title; ?></p><?php endif; ?>
                                    <?php if ($text): ?><p class="text"><?php echo $text; ?></p><?php endif; ?>
                                </div>
                            </a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>
    <section id="targetTop_news-01" class="targetTop_news-01">
        <div class="global_inner">
            <h2 class="targetTop_title-01">
                <span class="title-main">News</span>
                <?php if ($lang !== 'en'): ?><span class="title-sub">お知らせ</span><?php endif; ?>
            </h2>
        </div>
        <div class="global_inner">
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
                    );
                    $seminars_category_id = nig_get_seminars_category_id();
                    if ($seminars_category_id) {
                        $arg['category__not_in'] = array($seminars_category_id);
                    }
                    $posts = get_posts($arg);
                    if ($posts): ?>
                        <?php get_template_part('list', 'news'); ?>
                    <?php else: ?>
                        <p class="targetTop_nopost"><?php echo $nopost_text; ?></p>
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
                    );
                    $posts = get_posts($arg);
                    /** セミナータブは ACF の開催日程で並べる（同日は開始時刻が遅い順で降順） */
                    if (!empty($posts) && isset($category->slug) && $category->slug === 'seminars') {
                        nig_sort_posts_by_seminars_meta($posts);
                    }
                    if ($posts): ?>
                        <?php get_template_part('list', 'news'); ?>
                    <?php else: ?>
                        <p class="targetTop_nopost"><?php echo $nopost_text; ?></p>
                    <?php endif; ?>
                </div>
                <?php endforeach; //$categories ?>
            </div><!-- /module_tab-01 -->
            <p class="targetTop_button"><a href="<?php echo get_post_type_archive_link('post'); ?>" class="targetTop_button-01"><span><?php echo $more_text; ?></span></a></p>
        </div>
    </section>
    <section id="targetTop_navigation-01" class="targetTop_navigation-01">
        <div class="global_inner">
            <div class="titleArea">
                <h2 class="targetTop_title-01">
                    <span class="title-main">For <?php echo $post->post_name; ?></span>
                    <?php if ($lang !== 'en'): ?><span class="title-sub"><?php the_title_attribute(); ?>へ</span><?php endif; ?>
                </h2>
                <?php
                $message = get_field('targettop_message');
                if($message): ?>
                    <p class="message"><?php echo $message; ?></p>
                <?php endif; ?>
            </div>
            <?php if (have_rows('targettop_navigation-01')): ?>
                <div class="navigation-01">
                    <?php while (have_rows('targettop_navigation-01')): the_row(); ?>
                        <?php
                        $title = get_sub_field('title');
                        ?>
                        <article>
                            <?php if ($title): ?><h3 class="title"><?php echo $title; ?></h3><?php endif; ?>
                            <?php if (have_rows('navigation')): ?>
                                <ul class="navigation">
                                    <?php while (have_rows('navigation')): the_row(); ?>
                                        <?php
                                        $url = get_sub_field('url');
                                        $title = get_sub_field('title');
                                        ?>
                                        <li>
                                            <a <?php if (!empty($url)): ?>href="<?php echo esc_url($url); ?>"<?php else: ?> class="_disabled" tabindex="-1" <?php endif; ?>>
                                                <span><?php echo $title; ?></span>
                                            </a>
                                        </li>
                                    <?php endwhile; ?>
                                </ul>
                            <?php endif; ?>
                        </article>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>
            <?php if (have_rows('targettop_navigation-02')): ?>
                <ul class="navigation-02">
                    <?php while (have_rows('targettop_navigation-02')): the_row(); ?>
                        <?php
                        $title = get_sub_field('title');
                        $url = get_sub_field('url');
                        ?>
                        <li>
                            <a <?php if (!empty($url)): ?>href="<?php echo esc_url($url); ?>"<?php else: ?> class="_disabled" tabindex="-1" <?php endif; ?>>
                                <span><?php echo $title; ?></span>
                            </a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        <?php endif; ?>
    </section>
</main>
<?php get_footer(); ?>
