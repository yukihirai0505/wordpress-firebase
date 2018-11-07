<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Yabami
 * @subpackage Yabami/includes/rest-api
 * @author     Yabaiwebyasan <yabaiwebyasan@gmail.com>
 */
abstract class Yabami_Rest_Controller {
	protected $namespace = 'yabami/v1';
	protected $rest_base;

	static function ok( $data = 'ok' ) {
		$response       = new WP_REST_Response();
		$response->set_status( 200 );
		$response->set_data( array(
			'data' => $data
		) );

		return $response;
	}

	static function bad( $data = 'bad' ) {
		$response = new WP_REST_Response();
		$response->set_status( 400 );
		$response->set_data( array(
			'data' => $data
		) );

		return $response;
	}
}
