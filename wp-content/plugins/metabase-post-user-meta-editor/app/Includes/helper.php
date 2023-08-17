<?php
namespace Metabase\Includes;

/**
 * Cleans the field value to make  sure that data is well sanitized
 *
 * @param string $key field key.
 * @param mixed  $value field value.
 * @param array  $field fields array collection.
 *
 * @return mixed
 */
function clean( $var ) {
	if ( is_array( $var ) ) {
		return array_map( 'Metabase\Includes\clean', $var );
	} else {
		if ( is_scalar( $var ) ) {
			$var = wp_kses_post( $var );
			$var = stripslashes( $var );
		}
		return $var;
	}
}


/**
 * Generates the highlighted comment HTML for the given comment.
 *
 * @param WP_Comment $comment
 *
 * @return string
 */
function view( $template, $data = array(), $return = false ) {
	$template_path = get_template_path( $template );

	if ( ! is_readable( $template_path ) ) {
		return sprintf( '<!-- Could not read "%s" file -->', $template_path );
	}

	ob_start();

	include $template_path;

	if ( $return ) {
		return ob_get_clean();
	}

	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo ob_get_clean();
}

/**
 * Get the path of PHP template that the comment generator will use.
 *
 * @return string
 */
function get_template_path( $template_name ) {
	$theme_location = get_stylesheet_directory() . '/bookslot/' . $template_name . '.php';
	$template_path  = get_query_template( $theme_location );

	if ( empty( $template_path ) ) {
		$template_path = METABASE_VIEWS . $template_name . '.php';
	}

	if ( $template_path ) {
		return apply_filters( 'bookslot-view-' . $template_name, $template_path );
	}

	return false;
}

function transform( $arr_of_objects, $props ) {
	$transformed = array_reduce(
		$arr_of_objects,
		function ( $acc, $object ) use ( $props ) {
			$item = [];
			foreach ( $props as $prop ) {
				if ( isset( $object->$prop ) ) {
					$item[ $prop ] = $object->$prop;
				}
			}

			$acc[] = $item;
			return $acc;
		},
		array()
	);

	return $transformed;
}

function filter_array_keys( $sarray, $keys ) {
	$rarray = array();
	foreach ( $sarray as $key => $value ) {
		if ( in_array( $key, $keys ) ) {
			$rarray[ $key ] = $value;
		}
	}
	return $rarray;
}

function get_property( $className, $property ) {
	if ( ! class_exists( $className ) ) { return null; }
	if ( ! property_exists( $className, $property ) ) { return null; }

	$vars = get_class_vars( $className );

	return $vars[ $property ];
}
