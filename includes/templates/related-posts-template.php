<?php
/**
 * This is list item Template of related posts.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<article class="related-post">
	<a href="<?php the_permalink() ?>" class="post-link">
		<div class="related-post-inner-wrapper">
			<?php if ( has_post_thumbnail() ) : ?>
				<div class="featured-img">
					<?php the_post_thumbnail('thumbnail'); ?>
				</div>
			<?php endif; ?>
			<div class="related-post-info">
				<div class="related-post-cats">
					<?php echo $this->get_category_list($query->post_id()); ?>
				</div>
				<h4 class="related-post-title"><?php the_title() ?></h4>
				<div class="description">
					<?php echo wp_trim_words(wp_strip_all_tags(get_the_content()), 12); ?>
				</div>
			</div>
		</div>
	</a>
</article>