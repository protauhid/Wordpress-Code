function burnuppblocks_related_posts_slider_shortcode($atts) {
	global $post;

	$atts = shortcode_atts( array(
			'posts_per_page' => 6,
			'title' => 'Related Posts',
	), $atts );

	$categories = wp_get_post_categories($post->ID);
	if (empty($categories)) return '';

	$related = new WP_Query(array(
			'category__in' => $categories,
			'post__not_in' => array($post->ID),
			'posts_per_page' => $atts['posts_per_page'],
	));

	ob_start();
	?>
	<div class="related-posts-slider-wrapper">
			<h3><?php echo esc_html($atts['title']); ?></h3>
			<div class="related-posts-slider">
					<?php while ($related->have_posts()) : $related->the_post(); ?>
							<div class="single-related-post">
									<a href="<?php the_permalink(); ?>">
											<?php if (has_post_thumbnail()) : ?>
													<?php the_post_thumbnail('medium'); ?>
											<?php endif; ?>
											<h4><?php the_title(); ?></h4>
									</a>
							</div>
					<?php endwhile; ?>
			</div>
	</div>
	<?php
	wp_reset_postdata();
	return ob_get_clean();
}
add_shortcode('burnuppblocks_related_posts_slider', 'burnuppblocks_related_posts_slider_shortcode');
function burnuppblocks_enqueue_slick_slider_assets() {
	// Slick CSS
	wp_enqueue_style('slick-css', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css');
	wp_enqueue_style('slick-theme-css', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css');

	// Slick JS
	wp_enqueue_script('slick-js', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', array('jquery'), null, true);

	// Custom Init JS
	wp_add_inline_script('slick-js', "
			jQuery(document).ready(function($){
					$('.related-posts-slider').slick({
							slidesToShow: 3,
							slidesToScroll: 1,
							arrows: true,
							dots: true,
							infinite: true,
							responsive: [
									{
											breakpoint: 768,
											settings: {
													slidesToShow: 2
											}
									},
									{
											breakpoint: 480,
											settings: {
													slidesToShow: 1
											}
									}
							]
					});
			});
	");
}
add_action('wp_enqueue_scripts', 'burnuppblocks_enqueue_slick_slider_assets');

