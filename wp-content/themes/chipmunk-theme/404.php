<?php
/**
 * Chipmunk: 404 page
 *
 * @package WordPress
 * @subpackage Chipmunk
 */

get_header(); ?>

	<div class="section section_double">
		<div class="container">
			<div class="error-404 row">
				<div class="column column_lg-6 text_center">
					<h3 class="error-404__title heading heading_lg"><?php esc_html_e( '404! The page you are looking for couldn\'t be found.', 'chipmunk' ); ?></h3>
					<a href="<?php echo esc_url( home_url( '/', 'relative' ) ); ?>" class="button button_primary"><?php esc_html_e( 'Bring me to the frontpage', 'chipmunk' ); ?></a>
				</div>

				<div class="column column_lg-6">
					<div class="error-404__image">
						<img src="<?php echo get_template_directory_uri(); ?>/static/dist/images/pic-404.png" alt="" />
					</div>
				</div>
			</div>
			<!-- /.error-404 -->
		</div>
	</div>
	<!-- /.section -->

	<?php get_template_part( 'sections/toolbox' ); ?>

<?php get_footer(); ?>
