<?php

/**
 * Papi actions functions.
 *
 * @package Papi
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Delete the property value from the database.
 *
 * @param string $type
 * @param string $slug
 * @param int $post_id
 */

function papi_action_delete_value( $type, $slug, $post_id ) {
	do_action( 'papi/delete_value/' . $type, $slug, $post_id );
}

/**
 * Fire the 'papi/include' action, where plugins should include files.
 */

function papi_action_include() {
	do_action( 'papi/include' );
}
