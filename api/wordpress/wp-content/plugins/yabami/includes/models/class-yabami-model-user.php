<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Yabami
 * @subpackage Yabami/includes/models
 * @author     Yabaiwebyasan <yabaiwebyasan@gmail.com>
 */
class Yabami_Model_User extends Yabami_Model {

	public function __construct() {
		$this->table_name = 'user';
		$this->columns    = array(
			'`user`.`uid`',
			'`user`.`alis_user_id`'
		);
	}
}
