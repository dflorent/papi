<?php

/**
 * Unit tests covering property image.
 *
 * @package Papi
 */

class Papi_Property_Image_Test extends Papi_Property_Test_Case {

	public $slug = 'image_test';

	public function test_convert_type() {
		$this->assertEquals( 'object', $this->property->convert_type );
	}

	public function test_default_value() {
		$this->assertEquals( [], $this->property->default_value );
	}

	public function get_value() {
		return 23;
	}

	public function get_expected() {
		return 23;
	}

	public function test_property_options() {
		$this->assertEquals( 'image', $this->property->get_option( 'type' ) );
		$this->assertEquals( 'Image test', $this->property->get_option( 'title' ) );
		$this->assertEquals( 'papi_image_test', $this->property->get_option( 'slug' ) );
	}

	public function test_property_settings() {
		$this->assertFalse( $this->property->get_setting( 'gallery' ) );
	}

}
