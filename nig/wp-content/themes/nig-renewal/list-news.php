<?php
global $posts;
if ( empty( $posts ) ) {
	return;
}
?>
<div class="module_newsList-01">
	<?php foreach ( $posts as $post ) : setup_postdata( $post ); ?>
		<?php
		$categories = get_the_category();
		$has_child  = false;
		foreach ( (array) $categories as $cat ) {
			if ( ! empty( $cat->category_parent ) ) {
				$has_child = true;
				break;
			}
		}
		?>
		<article>
			<a href="<?php the_permalink(); ?>">
				<div class="head">
					<p class="date"><time datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>"><?php echo esc_html( get_the_date( 'Y.m.d' ) ); ?></time></p>
					<?php if ( $categories ) : ?>
						<p class="category _cat">
							<?php foreach ( $categories as $cat ) : ?>
								<?php if ( $has_child && empty( $cat->category_parent ) ) { continue; } ?>
								<?php if ( ! $has_child && ! empty( $cat->category_parent ) ) { continue; } ?>
								<span class="label <?php echo esc_attr( $cat->slug ); ?>"><?php echo esc_html( $cat->name ); ?></span>
							<?php endforeach; ?>
						</p>
					<?php endif; ?>
				</div>
				<div class="body"><h3 class="title"><?php echo esc_html( wp_strip_all_tags( get_the_title() ) ); ?></h3></div>
			</a>
		</article>
	<?php endforeach; wp_reset_postdata(); ?>
</div>
