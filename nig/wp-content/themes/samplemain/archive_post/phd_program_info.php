

<?php
global $post, $categoryData;
$args = array(
    'post_type' => 'post', // 投稿タイプ
    'category_name' => 'whats-new-2', // カテゴリ名（スラッグ）
    'posts_per_page' => 4 // 表示件数
);
$new_query = new WP_Query($args);
?>

<?php if ($new_query->have_posts()) : ?>

        <?php while ($new_query->have_posts()) : $new_query->the_post(); ?>
             <?php 
		if( $categoryData->slug != 'seminar'){
			$icon = get_post_meta($post->ID,'_custom_icon',true);
			if( $icon ) {
                switch ($icon) {
                    case 'new':
                        $icon = '<span class="icon_text icon_new">新着</span>';
                        break;
                    case 'past':
                        $icon = '<span class="icon_text icon_past">終了</span>';
                        break;
                    case 'pressrelease':
                        $icon = '<span class="icon_text icon_pressrelease">プレスリリース</span>';
                        break;
                    case 'recruitment':
                        $icon = '<span class="icon_text icon_recruitment">募集</span>';
                        break;
                    case 'award':
                        $icon = '<span class="icon_text icon_award">受賞</span>';
                        break;
                    case 'publish':
                        $icon = '<span class="icon_text icon_publish">出版</span>';
                        break;
                    case 'collaborations':
                        $icon = '<span class="icon_text icon_collaborations">コラボレーション</span>';
                        break;
                    case 'jobs':
                        $icon = '<span class="icon_text icon_jobs">募集</span>';
                        break;
                    default:
                        $icon = '';
                }
            }
		}else{
			$icon = '';
		}	
		//	開催予定日 取得
		$d = get_post_custom_values('holdday');
		if( isset( $d[0] ) && $d[0] ){
			$t = strtotime($d[0]);
			$holdDay = sprintf( '%s<br />' ,date( "l,F n,Y" , $t) );
		}else{
			$holdDay = '';
		}
		//	新着チェック
		$newIcon = _plusNewIcon( $post->post_date );
	
	
	
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
