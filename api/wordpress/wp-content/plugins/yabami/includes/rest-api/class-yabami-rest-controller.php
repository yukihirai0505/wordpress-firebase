<?php

use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Firebase\Auth\Token\Exception\InvalidToken;

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
		$idTokenString = "eyJhbGciOiJSUzI1NiIsImtpZCI6ImZkZjY0MWJmNDY3MTA1YzMyYWRkMDI3MGIyZTEyZDJiZTJhYmNjY2IiLCJ0eXAiOiJKV1QifQ.eyJpc3MiOiJodHRwczovL3NlY3VyZXRva2VuLmdvb2dsZS5jb20vYWxpcy1oYWNrZXItdG9rZW4iLCJuYW1lIjoiWXVraSBIaXJhaSIsInBpY3R1cmUiOiJodHRwczovL2xoNi5nb29nbGV1c2VyY29udGVudC5jb20vLXo1UF9la1FnSUJZL0FBQUFBQUFBQUFJL0FBQUFBQUFBQW9RL3c0b1RoUnVsZW5NL3Bob3RvLmpwZyIsImF1ZCI6ImFsaXMtaGFja2VyLXRva2VuIiwiYXV0aF90aW1lIjoxNTQxNTg2OTcxLCJ1c2VyX2lkIjoiTEs1eEtCbGFuOVNSUU1valROaVNIY25NMVJaMiIsInN1YiI6IkxLNXhLQmxhbjlTUlFNb2pUTmlTSGNuTTFSWjIiLCJpYXQiOjE1NDE1ODY5NzEsImV4cCI6MTU0MTU5MDU3MSwiZW1haWwiOiJ5dWtpaGlyYWkwNTA1QGdtYWlsLmNvbSIsImVtYWlsX3ZlcmlmaWVkIjp0cnVlLCJmaXJlYmFzZSI6eyJpZGVudGl0aWVzIjp7Imdvb2dsZS5jb20iOlsiMTA3MTc2NDE2NjEyMjk5OTc4NzEyIl0sImVtYWlsIjpbInl1a2loaXJhaTA1MDVAZ21haWwuY29tIl19LCJzaWduX2luX3Byb3ZpZGVyIjoiZ29vZ2xlLmNvbSJ9fQ.fDAJ1lYyy21hMhlQeZeeaSbBaQzDh2bQecMFsX_done4WqKwqq7Sn4CPO-CyDZWcDJtAi4hQCUO7HDqGSHV6of8hQ_vYVR9Aw7BakrHMXb0ya0XysJv5lH3esE5LUcRhs5tGuj00DXLIA4iD-G-SuMDIwYLOmaNdtHC77PiCqzevSzjWYRFBa7EgYR4vgCJAHdrTwhAGgVeIzzFM1fxuGd5BKxRyp0Gv-uA0YkMcZMV_AVsLva7Iw1eY9hmZfZwFvnvhSH2ucTK9H1H9_CBxpFlv2xscRf7Myy-ZIgG1sCC3rbFHOZ_o6aN51p_iYtWr4niyikcIKnF9ni0WdF3yzA";
		$serviceAccount = ServiceAccount::fromJsonFile( plugin_dir_path( dirname( __FILE__ ) ) . 'firebase.json' );
		$firebase = (new Factory)
			->withServiceAccount($serviceAccount)
			->create();
		try {
			$verifiedIdToken = $firebase->getAuth()->verifyIdToken($idTokenString);
		} catch (InvalidToken $e) {
			echo $e->getMessage();
		}

		$uid = $verifiedIdToken->getClaim('sub');
		$user = $firebase->getAuth()->getUser($uid);
		var_dump($user);
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
