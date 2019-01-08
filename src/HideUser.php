<?php
/**
 * HideUser Class
 *
 * Hides the super users.
 *
 * You may copy, distribute and modify the software as long as you track changes/dates in source files.
 * Any modifications to or software including (via compiler) GPL-licensed code must also be made
 * available under the GPL along with build & install instructions.
 *
 * @package    WPS\Users
 * @author     Travis Smith <t@wpsmith.net>
 * @copyright  2015-2018 Travis Smith
 * @license    http://opensource.org/licenses/gpl-2.0.php GNU Public License v2
 * @link       https://github.com/wpsmith/WPS
 * @version    1.0.0
 * @since      0.1.0
 */

namespace WPS\Users;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'HideUser' ) ) {
	/**
	 * Class HideUser
	 *
	 * @package WPS\Users
	 */
	class HideUser extends User {

		/**
		 * Implements the hooks.
		 */
		public function plugins_loaded() {
			add_action( 'pre_user_query', array( $this, 'pre_user_query' ) );
		}

		/**
		 * Remove user from all user queries.
		 *
		 * @global \wpdb $wpdb WordPress database abstraction object.
		 *
		 * @param \WP_User_Query $user_search The current WP_User_Query instance,
		 *                                    passed by reference.
		 */
		public function pre_user_query( $user_search ) {

			/**
			 * @var \WP_User $current_user \WP_User object for the current user.
			 */
			$current_user = wp_get_current_user();
			if ( ! $current_user->exists() ) {
				return;
			}

			foreach( $this->super_users as $user ) {
				if ( $user !== $current_user->user_login ) {
					global $wpdb;
					$user_search->query_where = str_replace(
						'WHERE 1=1',
						"WHERE 1=1 AND {$wpdb->users}.user_login != '$user'",
						$user_search->query_where
					);
				}
			}

		}

	}
}
