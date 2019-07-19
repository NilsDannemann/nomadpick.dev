<?php
/**
 * Chipmunk: Single Post
 *
 * @package WordPress
 * @subpackage Chipmunk
 */

chipmunk_set_views( get_the_ID() );
get_header(); ?>

	<div class="section">
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<div class="container">
				<?php get_template_part( 'sections/entry', 'post' ); ?>
			</div>
		<?php endwhile; endif; ?>
	</div>
	<!-- /.section -->

	<?php if ( comments_open() || get_comments_number() ) : ?>
		<div class="section section_theme-light">
			<div class="container">
				<?php comments_template(); ?>
			</div>
		</div>
		<!-- /.section -->
	<?php endif; ?>

	<?php get_template_part( 'sections/posts' ); ?>

<?php get_footer(); ?>
