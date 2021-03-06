<?php

/**
 * Papi filters functions.
 *
 * @package Papi
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Format the value of the property before it's returned to the theme.
 *
 * @param string $type
 * @param mixed $value
 * @param string $slug
 * @param int $post_id
 *
 * @return mixed
 */

function papi_filter_format_value( $type, $value, $slug, $post_id ) {
	return apply_filters( 'papi/format_value/' . $type, $value, $slug, $post_id );
}

/**
 * This filter is applied after the value is loaded in the database.
 *
 * @param string $type
 * @param mixed $value
 * @param string $slug
 * @param int $post_id
 *
 * @return mixed
 */

function papi_filter_load_value( $type, $value, $slug, $post_id ) {
	return apply_filters( 'papi/load_value/' . $type, $value, $slug, $post_id );
}

/**
 * Get all registered page type directories.
 *
 * @return array
 */

function papi_filter_settings_directories() {
	$directories = apply_filters( 'papi/settings/directories', [] );

	if ( is_string( $directories ) ) {
		return [$directories];
	}

	if ( ! is_array( $directories ) ) {
		return [];
	}

	return array_filter( $directories, function ( $directory ) {
		return is_string( $directory );
	} );
}

/**
 * Get the only page type that will be used for the given post type.
 *
 * @param string $post_type
 *
 * @return string
 */

function papi_filter_settings_only_page_type( $post_type ) {
	$page_type = apply_filters( 'papi/settings/only_page_type_' . $post_type, '' );

	if ( ! is_string( $page_type ) ) {
		return '';
	}

	return str_replace( '.php', '', $page_type );
}

/**
 * Change column title for page type column.
 *
 * @param string $post_type
 *
 * @return string
 */

function papi_filter_settings_page_type_column_title( $post_type ) {
	return apply_filters( 'papi/settings/column_title_' . $post_type, __( 'Page Type', 'papi' ) );
}

/**
 * Load page type from post query string.
 *
 * @return string
 */

function papi_filter_settings_page_type_from_post_qs() {
	return apply_filters( 'papi/settings/page_type_from_post_qs', 'from_post' );
}

/**
 * Show page type in add new page view for the given post type.
 *
 * @param string $post_type
 * @param string|object $page_type
 *
 * @return bool
 */

function papi_filter_settings_show_page_type( $post_type, $page_type ) {
	if ( is_object( $page_type ) && method_exists( $page_type, 'get_id' ) ) {
		$page_type = $page_type->get_id();
	}

	$value = apply_filters( 'papi/settings/show_page_type_' . $post_type, $page_type );

	if ( $value === $page_type ) {
		return true;
	}

	if ( ! is_bool( $value ) ) {
		return false;
	}

	return $value;
}

/**
 * Get standard page description for the given post type.
 *
 * @param string $post_type
 *
 * @return string
 */

function papi_filter_settings_standard_page_description( $post_type ) {
	$post_type_obj = get_post_type_object( $post_type );
	$name          = 'Page';

	if ( ! is_null( $post_type_obj ) && isset( $post_type_obj->labels ) ) {
		$name = $post_type_obj->labels->singular_name;
	}

	return apply_filters( 'papi/settings/standard_page_description_' . $post_type, sprintf( __( '%s with WordPress standard fields', 'papi' ), $name ) );
}

/**
 * Get standard page name for the given post type.
 *
 * @param string $post_type
 *
 * @return string
 */

function papi_filter_settings_standard_page_name( $post_type ) {
	$post_type_obj = get_post_type_object( $post_type );
	$name          = 'Page';

	if ( ! is_null( $post_type_obj ) && isset( $post_type_obj->labels ) ) {
		$name = $post_type_obj->labels->singular_name;
	}

	return apply_filters( 'papi/settings/standard_page_name_' . $post_type, sprintf( __( 'Standard %s', 'papi' ), $name ) );
}

/**
 * Show standard page type on the given post type.
 *
 * @param string $post_type
 *
 * @return bool
 */

function papi_filter_settings_show_standard_page_type( $post_type ) {
	return apply_filters( 'papi/settings/show_standard_page_type_' . $post_type, true ) === true;
}

/**
 * Get standard page thumbnail for the given post type.
 *
 * @param string $post_type
 *
 * @return string
 */

function papi_filter_settings_standard_page_thumbnail( $post_type ) {
	return apply_filters( 'papi/settings/standard_page_thumbnail_' . $post_type, '' );
}

/**
 * Get the default sort order that is 1000.
 *
 * @return int
 */

function papi_filter_settings_sort_order() {
	return intval( apply_filters( 'papi/settings/sort_order', 1000 ) );
}

/**
 * This filter is applied before the value is saved in the database.
 *
 * @param string $type
 * @param mixed $value
 * @param string $slug
 * @param int $post_id
 *
 * @return mixed
 */

function papi_filter_update_value( $type, $value, $slug, $post_id ) {
	return apply_filters( 'papi/update_value/' . $type, $value, $slug, $post_id );
}
