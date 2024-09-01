<?php
/**
 * Related Posts Settings Form and fields.
 */

 if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div id="wpbody">
	<div id="wpbody-content">
		<h1 class="wp-heading-inline">Related Posts Setting</h1>
		<div id="related-posts-settings-area">
			<form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
				<?php
				// Improvement #5: optimize function call( get_option() ) by not calling multiple times.
				$option_max_posts = (int) get_option( 'related_posts_max_posts' );
				$option_template_style = sanitize_text_field( get_option( 'related_posts_style' ) );
				$max_posts = $option_max_posts ? $option_max_posts : 5;
				$t_style = $option_template_style ? $option_template_style : 'style_vertical_list';

				wp_nonce_field('rbrp_form');
				?>
				<input type="hidden" name="action" value="related_posts_settings_action">
				<table>
					<tbody>
						<tr>
							<td><h4 class="settings-title">Max Related Posts</h4></td>
							<td style="width: 500px; padding: 10px;">
								<!-- // Improvement #5: escaping attr -->
								<input type="range" min="1" max="20" step="1" value="<?php esc_attr_e( $max_posts ); ?>" data-orientation="horizontal" name="max-posts">
							</td>
						</tr>
						<tr>
							<td>
								<h4 class="settings-title">Choose Style:</h4>
							</td>
							<td>
								<ul>
									<li>
										<!-- // Improvement #6: escaping attr -->
										<input type="radio" name="rp-template" id="rp-template-1" value="style_vertical_list" <?php esc_attr_e ( 'style_vertical_list' == $t_style ) ? 'checked' : '' ; ?>/>
										<!-- // Improvement #7: escaping URL -->
										<label for="rp-template-1"><img src="<?php echo esc_url( PLUGIN_ASSETS . 'images/related-post-preview-1.png' ); ?>" /></label>
									</li>
									<li>
										<!-- // Improvement #8: escaping attr -->
										<input type="radio" name="rp-template" id="rp-template-2" value="style_carousel" <?php esc_attr_e( ( 'style_carousel' == $t_style ) ? 'checked' : '' ); ?>/>
										<!-- // Improvement #9: escaping URL -->
										<label for="rp-template-2"><img src="<?php echo esc_url( PLUGIN_ASSETS . 'images/related-post-carousel-preview.png' ); ?>" /></label>
									</li>
								</ul>
							</td>
						</tr>
					</tbody>
				</table>
				<?php submit_button('Save Settings'); ?>
			</form>
		</div>
	</div>
</div>