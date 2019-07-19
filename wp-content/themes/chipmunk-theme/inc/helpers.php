<?php
/**
 * Theme specific helpers.
 *
 * @package WordPress
 * @subpackage Chipmunk
 */

if ( ! function_exists( 'chipmunk_theme_option' ) ) :
/**
 * Get menu items
 */
function chipmunk_theme_option( $name, $default = false ) {
	global $customizer;

	return $customizer->theme_option( $name, $default );
}
endif;


if ( ! function_exists( 'chipmunk_check_requirements' ) ) :
/**
 * Checks if the technical requirements are met.
 */
function chipmunk_check_requirements() {
	global $wp_version;

	$php_min_version = '5.3.0';
	$wp_min_version = '4.0';
	$php_current_version = phpversion();
	$errors = array();

	if ( version_compare( $php_min_version, $php_current_version, '>' ) ) {
		$errors[] = sprintf(
			__( 'Chipmunk requires PHP %1$s or greater. You have %2$s.', 'chipmunk' ),
			$php_min_version,
			$php_current_version
		);
	}

	if ( version_compare( $wp_min_version, $wp_version, '>' ) ) {
		$errors[] = sprintf(
			__( 'Chipmunk requires WordPress %1$s or greater. You have %2$s.', 'chipmunk' ),
			$wp_min_version,
			$wp_version
		);
	}

	return $errors;
}
endif;


if ( ! function_exists( 'chipmunk_get_socials' ) ) :
/**
 * Get posts
 */
function chipmunk_get_socials() {
	global $customizer;
	$socials = array();

	foreach ( $customizer->get_socials() as $social ) {
		$value = chipmunk_theme_option( strtolower( $social ) );

		if ( $value ) {
			$socials[$social] = $value;
		}
	}

	return $socials;
}
endif;


if ( ! function_exists( 'chipmunk_og_title' ) ) :
/**
 * Create title for post and pages OG tags
 */
function chipmunk_og_title() {
	if ( ! chipmunk_theme_option( 'disable_og_branding' ) ) {
		$title = sprintf( esc_html__( '%s on %s', 'chipmunk' ), get_the_title(), get_bloginfo( 'name' ) );
	}
	else {
		$title = get_the_title();
	}

	return $title;
}
endif;


if ( ! function_exists( 'chipmunk_meta_description' ) ) :
/**
 * Create meta description for post and pages
 */
function chipmunk_meta_description() {
	global $post;

	if ( is_front_page() ) {
		$description = get_bloginfo( 'description' );
	}

	elseif ( is_single() or is_page() ) {
		$description = chipmunk_custom_excerpt( $post->post_content, $post->post_excerpt );
		$description = strip_tags( $description );
		$description = str_replace( '"', '\'', $description );
	}

	return isset( $description ) ? $description : '';
}
endif;


if ( ! function_exists( 'chipmunk_custom_excerpt' ) ) :
/**
 * Custom excerpt function
 */
function chipmunk_custom_excerpt( $text, $excerpt ) {
	if ( $excerpt ) return $excerpt;

	$text = strip_shortcodes( $text );
	$text = apply_filters( 'the_content', $text );
	$text = str_replace( ']]>', ']]&gt;', $text );
	$text = strip_tags( $text );

	$excerpt_length = apply_filters( 'excerpt_length', 55 );
	$excerpt_more = apply_filters( 'excerpt_more', ' ' . '[...]' );
	$words = preg_split( "/[\n
		 ]+/", $text, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY );

	if ( count( $words ) > $excerpt_length ) {
		array_pop( $words );
		$text = implode( ' ', $words );
		$text = $text . $excerpt_more;
	}
	else {
		$text = implode( ' ', $words );
	}

	$text = str_replace( '"', '\'', strip_tags( $text ) );

	return apply_filters( 'wp_trim_excerpt', $text );
}
endif;


if ( ! function_exists( 'chipmunk_get_menu_items' ) ) :
/**
 * Get menu items
 */
function chipmunk_get_menu_items( $location ) {
	if ( ( $locations = get_nav_menu_locations() ) && isset( $locations[$location] ) ) {
		$menu = wp_get_nav_menu_object( $locations[$location] );

		if ( $menu ) {
			return wp_get_nav_menu_items( $menu->term_id );
		}
	}

	return false;
}
endif;


if ( ! function_exists( 'chipmunk_get_posts' ) ) :
/**
 * Get posts
 */
function chipmunk_get_posts( $limit = -1, $paged = false, $term = null ) {
	$args = array(
		'post_type'       => 'post',
		'posts_per_page'  => $limit,
		'paged'           => $paged,
	);
	$tax_args = array();

	// Apply taxonomy options
	if ( isset( $term ) ) {
		$tax_args['tax_query'] = array(
			array(
				'taxonomy'          => $term->taxonomy,
				'field'             => 'id',
				'terms'             => $term->term_id,
				'include_children'  => false
			),
		);
	}

	$query = new WP_Query( array_merge( $args, $tax_args ) );
	return $query;
}
endif;


if ( ! function_exists( 'chipmunk_get_related_posts' ) ) :
/**
 * Get posts
 */
function chipmunk_get_related_posts( $post_id ) {
	$args = array(
		'posts_per_page'  => 3,
		'post_type'       => 'post',
		'post__not_in'    => array( $post_id ),
		'orderby'         => 'rand',
	);

	$tags = get_the_terms( $post_id, 'post_tag' );
	$collections = get_the_terms( $post_id, 'category' );

	if ( ! empty( $tags ) ) {
		$args['tax_query'] = array(
			array(
				'taxonomy'    => 'post_tag',
				'field'       => 'term_id',
				'terms'       => array_map( 'chipmunk_map_terms', $tags ),
				'operator'    => 'IN',
			),
		);
	}
	elseif ( ! empty( $collections ) ) {
		$args['tax_query'] = array(
			array(
				'taxonomy'    => 'category',
				'field'       => 'term_id',
				'terms'       => array_map( 'chipmunk_map_terms', $collections ),
				'operator'    => 'IN',
			),
		);
	}

	$query = new WP_Query( $args );
	return $query;
}
endif;


if ( ! function_exists( 'chipmunk_get_resources' ) ) :
/**
 * Get resources
 */
function chipmunk_get_resources( $limit = -1, $paged = false, $term = null ) {
	$args = array(
		'post_type'       => 'resource',
		'posts_per_page'  => $limit,
		'paged'           => $paged,
	);

	$sort_args = array();
	$tax_args = array();

	// Apply sorting options
	if ( isset( $_GET['sort'] ) and ! chipmunk_theme_option( 'disable_sorting' ) ) {
		$sort_params = explode( '-', $_GET['sort'] );
		$sort_orderby = $sort_params[0];
		$sort_order = $sort_params[1];
	}
	else {
		$sort_orderby = chipmunk_theme_option( 'default_sort_by' );
		$sort_order = chipmunk_theme_option( 'default_sort_order' );
	}

	switch ( $sort_orderby ) {
		case 'date':
			$sort_args = array(
				'orderby'   => 'date',
			);
			break;
		case 'name':
			$sort_args = array(
				'orderby'   => 'title',
			);
			break;
		case 'views':
			$sort_args = array(
				'orderby'   => 'meta_value_num date',
				'meta_key'  => '_' . THEME_SLUG . '_post_view_count',
			);
			break;
		case 'upvotes':
			$sort_args = array(
				'orderby'   => 'meta_value_num date',
				'meta_key'  => '_' . THEME_SLUG . '_post_upvote_count',
			);
			break;
	}

	$sort_args['order'] = $sort_order;

	// Apply taxonomy options
	if ( is_tax() and isset( $term ) ) {
		$tax_args['tax_query'] = array(
			array(
				'taxonomy'          => $term->taxonomy,
				'field'             => 'id',
				'terms'             => $term->term_id,
				'include_children'  => false,
			),
		);
	}

	// Apply tag filters
	else if ( isset( $_GET['tag'] ) and ! empty( $_GET['tag'] ) and ! chipmunk_theme_option( 'disable_filters' ) ) {
		$tax_args['tax_query'] = array(
			array(
				'taxonomy'          => 'resource-tag',
				'field'             => 'slug',
				'terms'             => $_GET['tag'],
			),
		);
	}

	$query = new WP_Query( array_merge( $args, $sort_args, $tax_args ) );
	return $query;
}
endif;


if ( ! function_exists( 'chipmunk_get_latest_resources' ) ) :
/**
 * Get latest resources
 */
function chipmunk_get_latest_resources( $limit = -1, $paged = false ) {
	$args = array(
		'post_type'       => 'resource',
		'posts_per_page'  => $limit,
		'paged'           => $paged,
		'orderby'         => 'date',
		'order'           => 'DESC',
	);

	$query = new WP_Query( $args );
	return $query;
}
endif;


if ( ! function_exists( 'chipmunk_get_featured_resources' ) ) :
/**
 * Get featured resources
 */
function chipmunk_get_featured_resources( $limit = -1, $paged = false ) {
	$args = array(
		'post_type'       => 'resource',
		'posts_per_page'  => $limit,
		'paged'           => $paged,
		'meta_query'      => array(
			'featured'        => array(
				'key'             => '_' . THEME_SLUG . '_resource_is_featured',
				'value'           => 'on',
			),
			'views'           => array(
				'key'             => '_' . THEME_SLUG . '_post_view_count',
			),
		),
		'orderby'         => 'rand',
	);

	$query = new WP_Query( $args );
	return $query;
}
endif;


if ( ! function_exists( 'chipmunk_get_popular_resources' ) ) :
/**
 * Get popular resources
 */
function chipmunk_get_popular_resources( $limit = -1, $paged = false ) {
	$args = array(
		'post_type'       => 'resource',
		'posts_per_page'  => $limit,
		'paged'           => $paged,
		'meta_key'        => '_' . THEME_SLUG . '_post_view_count',
		'orderby'         => 'meta_value_num',
		'order'           => 'DESC',
	);

	$query = new WP_Query( $args );
	return $query;
}
endif;


if ( ! function_exists( 'chipmunk_get_related_resources' ) ) :
/**
 * Get related resources
 */
function chipmunk_get_related_resources( $post_id ) {
	$args = array(
		'posts_per_page'  => 3,
		'post_type'       => 'resource',
		'post__not_in'    => array( $post_id ),
		'orderby'         => 'rand',
	);

	$tags = get_the_terms( $post_id, 'resource-tag' );
	$collections = get_the_terms( $post_id, 'resource-collection' );

	if ( ! empty( $tags ) ) {
		$args['tax_query'] = array(
			array(
				'taxonomy'    => 'resource-tag',
				'field'       => 'term_id',
				'terms'       => array_map( 'chipmunk_map_terms', $tags ),
				'operator'    => 'IN',
			),
		);
	}
	elseif ( ! empty( $collections ) ) {
		$args['tax_query'] = array(
			array(
				'taxonomy'    => 'resource-collection',
				'field'       => 'term_id',
				'terms'       => array_map( 'chipmunk_map_terms', $collections ),
				'operator'    => 'IN',
			),
		);
	}

	$query = new WP_Query( $args );
	return $query;
}
endif;


if ( ! function_exists( 'chipmunk_get_users' ) ) :
/**
 * Get users
 */
function chipmunk_get_users( $limit = -1 ) {
	$args = array(
		'role__in' => array( 'Administrator', 'Editor', 'Author' ),
		'number'   => $limit,
		'orderby'  => 'ID',
		'order'    => 'ASC',
	);

	$query = new WP_User_Query( $args );
	return $query;
}
endif;


if ( ! function_exists( 'chipmunk_conditional_markup' ) ) :
/**
 * Conditional Markup
 */
function chipmunk_conditional_markup( $condition, $tagTrue, $tagFalse, $class, $content ) {
	if ( $condition ) {
		return "<$tagTrue class='$class'>$content</$tagTrue>";
	}
	else {
		return "<$tagFalse class='$class'>$content</$tagFalse>";
	}
}
endif;


if ( ! function_exists( 'chipmunk_display_collections' ) ) :
/**
 * Conditionally display post collections
 */
function chipmunk_display_collections( $collections, $args = array() ) {
	$args = array_merge( array(
		'type'     => 'link',
		'quantity' => -1,
	), $args );

	$output = '';
	$count = count( $collections );

	if ( $args['quantity'] > 0 && $args['quantity'] < $count ) {
		shuffle( $collections );
	}

	foreach ( $collections as $key => $collection ) {
		if ( $args['quantity'] < 0 || $args['quantity'] > $key ) {
			if ( $args['type'] == 'link' ) {
				$output .= '<a href="' . esc_url( get_term_link( $collection->term_id ) ) . '" class="stats__tag" title="' . esc_attr( $collection->name ) . '">' . esc_html( chipmunk_truncate_string( $collection->name, 25 ) ) . '</a>';
			}

			if ( $args['type'] == 'text' ) {
				$output .= '<span class="stats__tag">' . esc_html( chipmunk_truncate_string( $collection->name, 25 ) ) . '</span>';
			}
		}
	}

	if ( $args['quantity'] > 0 && $args['quantity'] < $count && $args['type'] == 'link' ) {
		$output .= '<span class="stats__tag stats__tag_dimmed">' . sprintf( esc_html__( '+%d more', 'chipmunk' ), $count - 1 ) . '</span>';
	}

	return $output;
}
endif;


if ( ! function_exists( 'chipmunk_external_link' ) ) :
/**
 * Create external links
 */
function chipmunk_external_link( $url ) {
	if ( ! chipmunk_theme_option( 'disable_ref' ) ) {
		$title = str_replace( '-', '', sanitize_title( get_bloginfo( 'name' ) ) );
		$prefix = ( preg_match( '(\&|\?)', $url ) === 1 ) ? '&ref=' : '?ref=';

		return $url . $prefix . $title;
	}

	return $url;
}
endif;


if ( ! function_exists( 'chipmunk_map_terms' ) ) :
/**
 * Map terms with term ID
 */
function chipmunk_map_terms( $term ) {
	return $term->term_id;
}
endif;


if ( ! function_exists( 'chipmunk_truncate_string' ) ) :
/**
 * Truncate long strings
 */
function chipmunk_truncate_string( $str, $chars, $to_space = true, $replacement = '&hellip;' ) {
	$str = strip_tags( $str );

	if ( $chars > strlen( $str ) ) {
		return $str;
	}

	$str = substr( $str, 0, $chars );
	$space_pos = strrpos( $str, ' ' );

	if ( $to_space && $space_pos >= 0 ) {
		$str = substr( $str, 0, strrpos( $str, ' ' ) );
	}

	return( $str . $replacement );
}
endif;


if ( ! function_exists( 'chipmunk_format_number' ) ) :
/**
 * Utility function to format the numbers,
 * appending "K" if one thousand or greater,
 * "M" if one million or greater,
 * and "B" if one billion or greater (unlikely).
 *
 * @param Number $precision - how many decimal points to display (1.25K)
 */
function chipmunk_format_number( $number, $precision = 1 ) {
	if ( $number >= 1000 && $number < 1000000 ) {
		$formatted = number_format( $number / 1000, $precision ) . 'K';
	}
	elseif ( $number >= 1000000 && $number < 1000000000 ) {
		$formatted = number_format( $number / 1000000, $precision ) . 'M';
	}
	elseif ( $number >= 1000000000 ) {
		$formatted = number_format( $number / 1000000000, $precision ) . 'B';
	}
	else {
		$formatted = $number; // Number is less than 1000
	}

	$formatted = preg_replace( '/\.[0]+([KMB]?)$/i', '$1', $formatted );
	return $formatted;
}
endif;


if ( ! function_exists( 'chipmunk_hex_to_rgb' ) ) :
/**
 * Utility function to convert hex colors to RGB arrays
 *
 * @param String $color - Hex color value
 */
function chipmunk_hex_to_rgb( $color ) {
	$color = preg_replace( '/[^abcdef]/i', '', $color );

	if ( strlen( $color ) == 6 ) {
		list( $r, $g, $b ) = str_split( $color, 2 );

		return array( hexdec( $r ), hexdec( $g ), hexdec( $b ) );
	}

	return false;
}
endif;


if ( ! function_exists( 'chipmunk_get_fonts_url' ) ) :
/**
 * Parse Google Fonts url
 */
function chipmunk_get_fonts_url( $font_names = array() ) {
	$font_families = array();

	foreach ( $font_names as $font ) {
		if ( ! array_key_exists( $font, $font_families ) ) {
			$font_families[$font] = "{$font}:400,700";
		}
	}

	$query_args = array(
		'family' => urlencode( implode( '|', array_values( $font_families ) ) ),
		'subset' => urlencode( 'latin,latin-ext' ),
	);

	return esc_url( add_query_arg( $query_args, '//fonts.googleapis.com/css' ) );
}
endif;


if ( ! function_exists( 'chipmunk_get_ip' ) ) :
/**
 * Utility to retrieve IP address
 */
function chipmunk_get_ip() {
	if ( isset( $_SERVER['HTTP_CLIENT_IP'] ) && ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	}
	elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) && ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	else {
		$ip = ( isset( $_SERVER['REMOTE_ADDR'] ) ) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
	}

	$ip = filter_var( $ip, FILTER_VALIDATE_IP );
	$ip = ( $ip === false ) ? '0.0.0.0' : $ip;
	return $ip;
}
endif;


if ( ! function_exists( 'chipmunk_get_current_page' ) ) :
/**
 * Get current page attribute
 */
function chipmunk_get_current_page() {
	if ( get_query_var( 'paged' ) ) {
		return get_query_var( 'paged' );
	}
	elseif ( get_query_var( 'page' ) ) {
		return get_query_var( 'page' );
	}
	else {
		return 1;
	}
}
endif;
