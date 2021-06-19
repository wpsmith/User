<?php
/**
 * User Class
 *
 * Sets the current user and is usable very early,
 * before WordPress sets the current user.
 *
 * You may copy, distribute and modify the software as long as you track changes/dates in source files.
 * Any modifications to or software including (via compiler) GPL-licensed code must also be made
 * available under the GPL along with build & install instructions.
 *
 * @package    WPS\WP
 * @author     Travis Smith <t@wpsmith.net>
 * @copyright  2015-2019 Travis Smith
 * @license    http://opensource.org/licenses/gpl-2.0.php GNU Public License v2
 * @link       https://github.com/wpsmith/WPS
 * @version    1.0.0
 * @since      0.1.0
 */

namespace WPS\WP\Users;

use WPS\Core\Singleton;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( __NAMESPACE__ . '\User' ) ) {
	/**
	 * Class User
	 *
	 * @package WPS\WP
	 */
	abstract class User extends Singleton {

		/**
		 * User.
		 *
		 * @var \WP_User
		 */
		public \WP_User $user;

		/**
		 * Super Users
		 *
		 * @var array Array of usernames.
		 */
		public array $super_users;

		/**
		 * User constructor.
		 *
		 * @param array $args Array of super users.
		 */
		protected function __construct( $args = array() ) {
			parent::__construct( $args );

			\add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ), 0 );
		}

		/**
		 * Sets the super users.
		 *
		 * @param array $super_users
		 */
		public function set_super_users( array $super_users ) {
			$this->super_users = $super_users;
		}

		/**
		 * Abstract function to implement.
		 */
		abstract public function plugins_loaded();

		/**
		 * Determines whether the user is a super user.
		 *
		 * @param string|int|\WP_User $user User to be checked.
		 *
		 * @return bool Whether the user is a super user.
		 */
		public function is_super_user( \WP_User|int|string $user ): bool {
			$user = $this->get_user( $user );

			return (
				in_array( $user->user_email, $this->super_users, true ) ||
				in_array( $user->user_login, $this->super_users, true ) ||
				in_array( $user->ID, $this->super_users, true )
			);
		}

		/**
		 * Gets the user by email, ID, or login.
		 *
		 * @param string|int|\WP_User $user User.
		 *
		 * @return string|int|bool|\WP_User The WP_User object or false if User cannot be found.
		 */
		public function get_user( \WP_User|int|string $user ): \WP_User|bool|int|string {
			if ( is_a( $user, 'WP_User' ) ) {
				return $user;
			} elseif ( is_numeric( $user ) ) {
				$user = get_user_by( 'ID', $user );
			} elseif ( is_string( $user ) ) {
				if ( is_email( $user ) ) {
					$user = get_user_by( 'email', $user );
				} else {
					$user = get_user_by( 'login', $user );
				}
			}

			return $user;
		}

	}
}
