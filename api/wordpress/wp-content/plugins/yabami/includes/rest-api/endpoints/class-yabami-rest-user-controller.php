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
 * @subpackage Yabami/includes/rest-api/endpoints
 * @author     Yabaiwebyasan <yabaiwebyasan@gmail.com>
 */
class Yabami_Rest_User_Controller extends Yabami_Rest_Controller {
	public function __construct() {
		$this->rest_base = 'user';
	}

	public function register_endpoints() {

		register_rest_route( $this->namespace, '/' . $this->rest_base, array(
			array(
				'methods'  => WP_REST_Server::READABLE,
				'callback' => array( $this, 'get' )
			)
		) );

	}

	public function get( WP_REST_Request $data ) {
		$idTokenString = "hogehoge";
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
		return self::ok( '' );
	}
}
