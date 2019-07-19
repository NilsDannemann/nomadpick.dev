<?php
/**
 * Chipmunk: Footer
 *
 * Remember to always include the wp_footer() call before the </body> tag
 *
 * @package WordPress
 * @subpackage Chipmunk
 */
?>

<div class="container">
<?php echo do_shortcode('[elementor-template id="769"]'); ?>
</div>

		<?php get_template_part( 'partials/promo' ); ?>

		<?php if ( ! is_front_page() || chipmunk_theme_option( 'disable_homepage_listings' ) ) : ?>
			<?php get_template_part( 'partials/toolbox' ); ?>
		<?php endif; ?>

		<?php get_template_part( 'partials/newsletter' ); ?>
		<?php get_template_part( 'partials/page-bottom' ); ?>
        <?php get_template_part( 'partials/page-foot' ); ?>

		<?php if ( ! chipmunk_theme_option( 'disable_search' ) ) : ?>
			<?php get_template_part( 'partials/search-bar' ); ?>
		<?php endif; ?>
	</div>
	<!-- /.body-bag -->

	<?php if ( ! chipmunk_theme_option( 'disable_submissions' ) ) : ?>
		<?php get_template_part( 'partials/popup' ); ?>
	<?php endif; ?>

	<?php wp_footer(); ?>
</body>
</html>