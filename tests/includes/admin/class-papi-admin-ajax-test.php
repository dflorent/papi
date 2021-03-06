<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Unit tests covering `Papi_Admin_Ajax` class.
 *
 * @package Papi
 */

class Papi_Admin_Ajax_Test extends WP_UnitTestCase {

	public function setUp() {
		parent::setUp();
		$_GET  = [];
		$_POST = [];
		$this->ajax = new Papi_Admin_Ajax();

		tests_add_filter( 'wp_die_ajax_handler', function () {
			return $this->get_die_handler();
		}, 1, 1 );
	}

	public function tearDown() {
		parent::tearDown();
		unset( $_GET, $_POST, $this->ajax );
	}

	public function get_die_handler() {
		return [$this, 'die_handler'];
	}

	public function die_handler() {

	}

	public function test_actions() {
		$this->assertEquals( 10, has_action( 'init', [$this->ajax, 'add_endpoint'] ) );
		$this->assertEquals( 10, has_action( 'parse_query', [$this->ajax, 'handle_papi_ajax'] ) );
		$this->assertEquals( 10, has_action( 'admin_enqueue_scripts', [$this->ajax, 'ajax_url'] ) );
		$this->assertEquals( 10, has_action( 'papi/ajax/get_property', [$this->ajax, 'get_property'] ) );
		$this->assertEquals( 10, has_action( 'papi/ajax/get_properties', [$this->ajax, 'get_properties'] ) );
	}

	public function test_endpoint() {
		$this->ajax->add_endpoint();
		global $wp_rewrite;
		$this->assertNotNull( $wp_rewrite->extra_rules_top['papi-ajax/([^/]*)/?'] );
		$this->assertEquals( 'index.php?action=$matches[1]', $wp_rewrite->extra_rules_top['papi-ajax/([^/]*)/?'] );
	}

	public function test_ajax_url() {
		$this->ajax->ajax_url();
		$this->expectOutputRegex( '/papi\-ajax/' );
	}

	public function test_handle_papi_ajax_wp_query() {
		global $wp_query;
		$wp_query = null;
		$this->assertNull( $this->ajax->handle_papi_ajax() );
	}

	public function test_handle_papi_ajax_doing_ajax() {
		$this->assertNull( $this->ajax->handle_papi_ajax() );
	}

	public function test_handle_papi_ajax_action() {
		$_GET = [
			'action' => 'get_property',
			'slug'   => 'hello',
			'type'   => 'string'
		];

		$user_id = $this->factory->user->create( [ 'role' => 'administrator' ] );
		wp_set_current_user( $user_id );

		$this->ajax->handle_papi_ajax();
		wp_set_current_user( 0 );

		$this->expectOutputRegex( '/.*\S.*/' );
		$this->expectOutputRegex( '/papi\_hello/' );
	}

	public function test_handle_papi_ajax() {
		$this->assertNull( $this->ajax->handle_papi_ajax() );
		$this->expectOutputRegex( '//' );
	}

	public function test_get_property() {
		if ( ! defined( 'DOING_AJAX' ) ) {
			define( 'DOING_AJAX', true );
		}

		$_GET = [
			'slug' => 'hello',
			'type' => 'string'
		];

		do_action( 'papi/ajax/get_property' );

		$this->expectOutputRegex( '/.*\S.*/' );
		$this->expectOutputRegex( '/papi\_hello/' );
	}

	public function test_get_property_fail() {
		if ( ! defined( 'DOING_AJAX' ) ) {
			define( 'DOING_AJAX', true );
		}

		$_GET = [
			'slug' => 'hello',
			'type' => 'fake'
		];

		do_action( 'papi/ajax/get_property' );

		$this->expectOutputRegex( '/.*\S.*/' );
		$this->expectOutputRegex( '/\{\"error\"\:\"No property found\"\}/' );
	}

	public function test_get_properties() {
		if ( ! defined( 'DOING_AJAX' ) ) {
			define( 'DOING_AJAX', true );
		}

		$property = papi_get_property_type( [
			'slug' => 'name',
			'type' => 'string'
		] );

		$_POST = [
			'properties' => json_encode( [
				$property->get_options(),
				[
					'type'  => 'string',
					'title'	=> 'nyckel'
				]
			] )
		];

		do_action( 'papi/ajax/get_properties' );

		$this->expectOutputRegex( '/.*\S.*/' );
		$this->expectOutputRegex( '/papi\_name/' );
		$this->expectOutputRegex( '/papi\_nyckel/' );
	}

	public function test_get_properties_fail() {
		if ( ! defined( 'DOING_AJAX' ) ) {
			define( 'DOING_AJAX', true );
		}

		$_POST = [
			'properties' => json_encode( [
				[
					'type'  => 'fake',
					'title'	=> 'nyckel'
				]
			] )
		];

		do_action( 'papi/ajax/get_properties' );

		$this->expectOutputRegex( '/.*\S.*/' );
		$this->expectOutputRegex( '/\{\"error\"\:\"No properties found\"\}/' );
	}

	public function test_get_properties_fail_2() {
		if ( ! defined( 'DOING_AJAX' ) ) {
			define( 'DOING_AJAX', true );
		}

		$_POST = [];

		do_action( 'papi/ajax/get_properties' );

		$this->expectOutputRegex( '/.*\S.*/' );
		$this->expectOutputRegex( '/\{\"error\"\:\"No properties found\"\}/' );
	}

	public function test_get_properties_fail_3() {
		if ( ! defined( 'DOING_AJAX' ) ) {
			define( 'DOING_AJAX', true );
		}

		$_POST = [
			'properties' => json_encode( [] )
		];

		do_action( 'papi/ajax/get_properties' );

		$this->expectOutputRegex( '/.*\S.*/' );
		$this->expectOutputRegex( '/\{\"error\"\:\"No properties found\"\}/' );
	}

	public function test_render_error() {
		if ( ! defined( 'DOING_AJAX' ) ) {
			define( 'DOING_AJAX', true );
		}

		$this->ajax->render_error( 'No property found' );
		$this->expectOutputString( '{"error":"No property found"}' );
	}

}
