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
				$max_posts = get_option('related_posts_max_posts') ? get_option('related_posts_max_posts') : 5;
				$t_style = get_option('related_posts_style') ? get_option('related_posts_style') : 'style_vertical_list';

				wp_nonce_field('rbrp_form');
				?>
				<input type="hidden" name="action" value="related_posts_settings_action">
				<table>
					<tbody>
						<tr>
							<td><h4 class="settings-title">Max Related Posts</h4></td>
							<td style="width: 500px; padding: 10px;">
								<input type="range" min="1" max="20" step="1" value="<?php echo $max_posts; ?>" data-orientation="horizontal" name="max-posts">
							</td>
						</tr>
						<tr>
							<td>
								<h4 class="settings-title">Choose Style:</h4>
							</td>
							<td>
								<ul>
									<li>
										<input type="radio" name="rp-template" id="rp-template-1" value="style_vertical_list" <?php echo ( 'style_vertical_list' == $t_style ) ? 'checked' : '' ; ?>/>
										<label for="rp-template-1"><img src="<?php echo PLUGIN_ASSETS . 'images/related-post-preview-1.png' ?>" /></label>
									</li>
									<li>
										<input type="radio" name="rp-template" id="rp-template-2" value="style_carousel" <?php echo ( 'style_carousel' == $t_style ) ? 'checked' : '' ; ?>/>
										<label for="rp-template-2"><img src="<?php echo PLUGIN_ASSETS . 'images/related-post-carousel-preview.png' ?>" /></label>
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