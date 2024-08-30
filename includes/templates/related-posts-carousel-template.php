<?php
/**
 * This is item of Carousel Related Posts.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<li class="splide__slide">
	<article class="related-post">
		<a href="<?php the_permalink() ?>" class="post-link">
			<div class="related-post-inner-wrapper">
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="feature-image-wrapper">
						<div class="featured-img" style="background-image: url(<?php the_post_thumbnail_url('full'); ?>)"></div>
					</div>
				<?php endif; ?>
				<div class="related-post-info">
					<h4 class="related-post-title"><?php the_title() ?></h4>
					<div class="description">
						<?php echo wp_trim_words(wp_strip_all_tags(get_the_content()), 12); ?>
					</div>
				</div>
			</div>
		</a>
	</article>
</li>