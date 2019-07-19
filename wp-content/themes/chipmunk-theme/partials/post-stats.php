<?php $collections = wp_get_post_terms( get_the_ID(), ( get_post_type() == 'post' ? 'category' : 'resource-collection' ) ); ?>

<?php if ( isset( $collections_args ) && $collections_args['display'] and $collections ) : ?>
	<li class="stats__item">
		<i class="icon icon_tag"></i>

		<?php echo chipmunk_display_collections( $collections, $collections_args ); ?>
	</li>
<?php endif; ?>

<?php if ( ! chipmunk_theme_option( 'disable_resource_date' ) ) : ?>
	<li class="stats__item" title="<?php esc_attr_e( 'Published', 'chipmunk' ); ?>: <?php echo get_post_time( 'j. F Y', false, get_the_ID(), true ); ?>">
		<i class="icon icon_clock"></i>

		<?php echo get_post_time( 'j. M', false, get_the_ID(), true ); ?>
	</li>
<?php endif; ?>

<?php if ( ! chipmunk_theme_option( 'disable_views' ) ) : ?>
	<li class="stats__item" title="<?php esc_attr_e( 'Views', 'chipmunk' ); ?>">
		<i class="icon icon_eye"></i>

		<?php echo chipmunk_format_number( chipmunk_get_views( get_the_ID() ) ); ?>
	</li>
<?php endif; ?>

<?php if ( ! chipmunk_theme_option( 'disable_upvotes' ) and get_post_type() == 'resource' ) : ?>
	<?php $upvote_button = chipmunk_upvote_button( get_the_ID(), 'stats__button' ); ?>
	<?php $upvote_counter = chipmunk_upvote_counter( get_the_ID() ); ?>

	<?php if ( $wp_query->current_post == 0 || is_search() ) : ?>
		<li class="stats__item"><?php echo $upvote_button; ?></li>
	<?php else : ?>
		<?php if ( chipmunk_theme_option( 'display_resource_as' ) != 'tile' ) : ?>
			<li class="stats__item stats__item_sided"><?php echo $upvote_button; ?></li>
		<?php else : ?>
			<li class="stats__item" title="<?php esc_attr_e( 'Upvotes', 'chipmunk' ); ?>"><?php echo $upvote_counter; ?></li>
		<?php endif; ?>
	<?php endif; ?>
<?php endif; ?>
