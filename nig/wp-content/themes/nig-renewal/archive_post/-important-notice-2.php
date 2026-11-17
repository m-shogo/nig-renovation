

<?php
$args = array(
    'post_type' => 'post', // 投稿タイプ
    'category'  => '93', // カテゴリーID, // カテゴリ名（スラッグ）
    'posts_per_page' => 4 // 表示件数
);
$new_query = new WP_Query($args);
?>

<?php if ($new_query->have_posts()) : ?>
   
        <?php while ($new_query->have_posts()) : $new_query->the_post(); ?>
            <?php
            // 親テンプレート未設定時の初期化
            if ( ! isset( $icon ) ) { $icon = ''; }
            if ( ! isset( $newIcon ) ) { $newIcon = ''; }
            if ( ! isset( $holdDay ) ) { $holdDay = ''; }
            ?>
            <div class="listlink_box">
                   <div class="date">
            <?php the_time('Y/m/d'); echo $newIcon.$icon;?></div>
					<p><a href="<?php the_permalink(); ?>"><?php echo $holdDay; the_title(); ?></a></p>
             </div>
           
        <?php endwhile; ?>
        <?php wp_reset_postdata(); ?>
 
<?php else : ?>
    <p>記事がありません。</p>
<?php endif; ?>
