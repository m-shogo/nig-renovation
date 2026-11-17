<?php
/**
 * Template Name: フロントテンプレート
 */
$lang = nig_renewal_current_lang();
if ($lang === 'en') {
	$more_text   = 'View More';
	$nopost_text = 'No articles found.';
} else {
	$more_text   = '一覧を見る';
	$nopost_text = '記事はありません。';
}

if ( ! function_exists( 'nig_renewal_render_notice' ) ) {
	function nig_renewal_render_notice( $select, $field, $title, $modifier ) {
		if ( ! get_field( $select ) || ! have_rows( $field ) ) {
			return;
		}
		?>
		<section id="<?php echo esc_attr( $field ); ?>" class="top_notice-01 <?php echo esc_attr( $modifier ); ?>">
			<div class="global_inner">
				<div class="notice">
					<div class="head"><h2 class="title"><?php echo esc_html( $title ); ?></h2></div>
					<div class="body">
						<ul>
							<?php while ( have_rows( $field ) ) : the_row(); ?>
								<?php
								$date  = get_sub_field( 'date', false );
								$label = get_sub_field( 'label' );
								$title_text = get_sub_field( 'title' );
								$url   = get_sub_field( 'url' );
								$none  = get_sub_field( 'none' );
								if ( $none || ! $title_text ) {
									continue;
								}
								$when = $date ? date_create( $date ) : false;
								?>
								<li>
									<?php if ( $when ) : ?><p class="date"><time datetime="<?php echo esc_attr( $when->format( 'Y-m-d' ) ); ?>"><?php echo esc_html( $when->format( 'Y.m.d' ) ); ?></time></p><?php endif; ?>
									<?php if ( $label ) : ?><p class="label"><?php echo esc_html( $label ); ?></p><?php endif; ?>
									<p class="title">
										<?php if ( $url ) : ?>
											<a href="<?php echo esc_url( $url ); ?>"<?php echo get_sub_field( 'target' ) ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>><?php echo esc_html( $title_text ); ?></a>
										<?php else : ?>
											<span class="_disabled"><?php echo esc_html( $title_text ); ?></span>
										<?php endif; ?>
									</p>
								</li>
							<?php endwhile; ?>
						</ul>
					</div>
				</div>
			</div>
		</section>
		<?php
	}
}

$nig_renewal_image = function ( $id, $size ) {
	if ( ! $id ) {
		return '';
	}
	$thumb = wp_get_attachment_image_src( $id, $size );
	if ( ! $thumb ) {
		$thumb = wp_get_attachment_image_src( $id, 'full' );
	}
	return ( $thumb && ! empty( $thumb[0] ) ) ? $thumb[0] : '';
};

get_header();
?>
<div class="top_mainVisual">
	<?php if ( have_rows( 'top_slider-01' ) ) : ?>
		<div class="top_banner swiper">
			<ul class="swiper-wrapper">
				<?php while ( have_rows( 'top_slider-01' ) ) : the_row(); ?>
					<?php
					$img_pc = get_sub_field( 'img_pc' );
					$img_sp = get_sub_field( 'img_sp' );
					$src    = $nig_renewal_image( $img_pc ? $img_pc : $img_sp, 'full' );
					$alt    = $img_pc ? get_post_meta( $img_pc, '_wp_attachment_image_alt', true ) : '';
					if ( ! $src ) {
						continue;
					}
					?>
					<li class="swiper-slide"><img src="<?php echo esc_url( $src ); ?>" alt="<?php echo esc_attr( $alt ); ?>"></li>
				<?php endwhile; ?>
			</ul>
			<div class="swiper-pagination"></div>
		</div>
	<?php endif; ?>
</div>
<main id="global_contents" class="global_contents">
	<?php
	nig_renewal_render_notice( 'top_notice_select', 'top_notice-01', 'Opportunities', 'top_notice-01--opportunities' );
	nig_renewal_render_notice( 'top_important_select', 'top_important-01', 'Important', 'top_notice-01--important' );
	?>

	<section id="top_news-01" class="top_news-01">
		<div class="global_inner">
			<h2 class="top_title-01">
				<span class="title-main">News＆Events</span>
				<?php if ( $lang !== 'en' ) : ?><span class="title-sub">お知らせ</span><?php endif; ?>
			</h2>
		</div>
		<div class="main global_inner">
			<div class="list">
				<div class="module_tab-01">
					<ul class="module_tab-head">
						<li><a href="#tab01-01">All</a></li>
						<?php
						$categories = get_categories( array(
							'suppress_filters' => false,
							'parent'           => 0,
							'orderby'          => 'description',
							'order'            => 'ASC',
							'hide_empty'       => true,
						) );
						$counter = 1;
						foreach ( $categories as $category ) {
							$counter++;
							echo '<li class="' . esc_attr( $category->slug ) . '"><a href="#tab01-' . esc_attr( sprintf( '%02d', $counter ) ) . '">' . esc_html( $category->name ) . '</a></li>';
						}
						?>
					</ul>
					<div class="module_tab-body" id="tab01-01">
						<?php
						$posts = get_posts( array(
							'suppress_filters'    => false,
							'post_type'           => 'post',
							'posts_per_page'      => 4,
							'has_password'        => false,
							'orderby'             => 'date',
							'order'               => 'DESC',
							'ignore_sticky_posts' => 1,
						) );
						if ( $posts ) {
							get_template_part( 'list', 'news' );
						} else {
							echo '<p class="top_nopost">' . esc_html( $nopost_text ) . '</p>';
						}
						?>
					</div>
					<?php
					$counter = 1;
					foreach ( $categories as $category ) {
						$counter++;
						echo '<div class="module_tab-body" id="tab01-' . esc_attr( sprintf( '%02d', $counter ) ) . '">';
						$posts = get_posts( array(
							'suppress_filters'    => false,
							'post_type'           => 'post',
							'cat'                 => $category->term_id,
							'posts_per_page'      => 4,
							'has_password'        => false,
							'orderby'             => 'date',
							'order'               => 'DESC',
							'ignore_sticky_posts' => 1,
						) );
						if ( $posts ) {
							get_template_part( 'list', 'news' );
						} else {
							echo '<p class="top_nopost">' . esc_html( $nopost_text ) . '</p>';
						}
						echo '</div>';
					}
					?>
				</div>
			</div>
			<p class="top_button"><a href="<?php echo esc_url( get_post_type_archive_link( 'post' ) ); ?>" class="top_button-01"><span><?php echo esc_html( $more_text ); ?></span></a></p>
		</div>
	</section>

	<section id="top_scientists-01" class="top_scientists-01">
		<div class="global_inner">
			<h2 class="top_title-01">
				<span class="title-main">Our Scientists</span>
				<?php if ( $lang !== 'en' ) : ?><span class="title-sub">研究者と研究室</span><?php endif; ?>
			</h2>
		</div>
		<?php if ( have_rows( 'top_scientists-01' ) ) : ?>
			<div class="swiper scientists_swiper-container">
				<ul class="swiper-wrapper">
					<?php while ( have_rows( 'top_scientists-01' ) ) : the_row(); ?>
						<?php
						$img   = get_sub_field( 'img' );
						$src   = $nig_renewal_image( $img, 'top_img' );
						$url   = get_sub_field( 'url' );
						$title = get_sub_field( 'title' );
						$theme = get_sub_field( 'theme' );
						?>
						<li class="swiper-slide">
							<?php if ( $url ) : ?><a href="<?php echo esc_url( $url ); ?>"><?php else : ?><span class="_disabled"><?php endif; ?>
								<p class="image<?php echo $src ? '' : ' _noImage'; ?>"><img src="<?php echo esc_url( $src ? $src : get_template_directory_uri() . '/images/common/noimage_logo_01.svg' ); ?>" alt="<?php echo esc_attr( wp_strip_all_tags( (string) $title ) ); ?>"></p>
								<?php if ( $title ) : ?><h3 class="title"><?php echo esc_html( $title ); ?></h3><?php endif; ?>
								<?php if ( $theme ) : ?><p class="theme"><?php echo esc_html( $theme ); ?></p><?php endif; ?>
							<?php if ( $url ) : ?></a><?php else : ?></span><?php endif; ?>
						</li>
					<?php endwhile; ?>
				</ul>
				<div class="swiper-control global_inner">
					<div class="swiper-button-prev"></div>
					<div class="swiper-button-next"></div>
				</div>
			</div>
		<?php endif; ?>
		<?php if ( have_rows( 'top_scientists-02' ) ) : ?>
			<div class="global_inner">
				<ul class="list">
					<?php while ( have_rows( 'top_scientists-02' ) ) : the_row(); ?>
						<?php
						$img   = get_sub_field( 'img' );
						$src   = $nig_renewal_image( $img, 'top_img' );
						$title = get_sub_field( 'title' );
						$url   = get_sub_field( 'url' );
						$genre = get_sub_field( 'genre' );
						$text  = get_sub_field( 'text' );
						?>
						<li>
							<?php if ( $url ) : ?><a href="<?php echo esc_url( $url ); ?>"><?php else : ?><span class="_disabled"><?php endif; ?>
								<div class="head">
									<?php if ( $src ) : ?><p class="image"><img src="<?php echo esc_url( $src ); ?>" alt="<?php echo esc_attr( wp_strip_all_tags( (string) $title ) ); ?>"></p><?php endif; ?>
									<div class="titleArea">
										<?php if ( $genre ) : ?><p class="genre"><?php echo esc_html( $genre ); ?></p><?php endif; ?>
										<?php if ( $title ) : ?><h3 class="title"><?php echo esc_html( $title ); ?></h3><?php endif; ?>
									</div>
								</div>
								<?php if ( $text ) : ?><div class="body"><p class="text"><?php echo esc_html( $text ); ?></p></div><?php endif; ?>
							<?php if ( $url ) : ?></a><?php else : ?></span><?php endif; ?>
						</li>
					<?php endwhile; ?>
				</ul>
			</div>
		<?php endif; ?>
	</section>

	<?php if ( have_rows( 'top_recommends-01' ) ) : ?>
		<section id="top_recommends-01" class="top_recommends-01">
			<div class="global_inner">
				<h2 class="top_title-01"><span class="title-main">Our <br class="_over-TB">Recommendations</span></h2>
				<ul class="list">
					<?php while ( have_rows( 'top_recommends-01' ) ) : the_row(); ?>
						<?php
						$img   = get_sub_field( 'img' );
						$src   = $nig_renewal_image( $img, 'top_img' );
						$title = get_sub_field( 'title' );
						$url   = get_sub_field( 'url' );
						?>
						<li>
							<?php if ( $url ) : ?><a href="<?php echo esc_url( $url ); ?>"<?php echo get_sub_field( 'target' ) ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>><?php else : ?><span class="_disabled"><?php endif; ?>
								<?php if ( $src ) : ?><p class="image"><img src="<?php echo esc_url( $src ); ?>" alt="<?php echo esc_attr( wp_strip_all_tags( (string) $title ) ); ?>"></p><?php endif; ?>
								<?php if ( $title ) : ?><p class="title"><?php echo esc_html( $title ); ?></p><?php endif; ?>
							<?php if ( $url ) : ?></a><?php else : ?></span><?php endif; ?>
						</li>
					<?php endwhile; ?>
				</ul>
			</div>
		</section>
	<?php endif; ?>
</main>
<?php get_footer(); ?>
