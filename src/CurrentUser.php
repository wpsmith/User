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

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( __NAMESPACE__ . '\CurrentUser' ) ) {
	/**
	 * Class User
	 *
	 * @package WPS\WP
	 */
	class CurrentUser extends User {

		/**
		 * Sets the user.
		 */
		public function plugins_loaded() {
			$this->user = $this->set_user();
		}

		/**
		 * Exact copy of pluggable _wp_get_current_user();
		 *
		 * @return \WP_User
		 */
		public function set_user(): \WP_User {
			global $current_user;

			$user_id = \apply_filters( 'determine_current_user', false );
			\remove_action( 'set_current_user', 'bbp_setup_current_user', 10 );

			if ( ! $user_id ) {
				\wp_set_current_user( 0 );

				return $current_user;
			}

			\wp_set_current_user( $user_id );

			if ( function_exists( 'bbp_setup_current_user' ) ) {
				\add_action( 'set_current_user', 'bbp_setup_current_user', 10 );
			}

			return $current_user;
		}

		/**
		 * Determines whether the current user is a super user.
		 *
		 * @return bool Whether the current user is a super user.
		 */
		public function is_current_super_user(): bool {
			$current_user = $this->get_current_user();

			return $this->is_super_user( $current_user );
		}

		/**
		 * Determines whether the user is a current user.
		 *
		 * @return \WP_User The current user.
		 */
		public function get_current_user(): \WP_User {
			if ( function_exists( 'wp_get_current_user' ) ) {
				$current = \wp_get_current_user();

				if ( ! empty( $current ) ) {
					return $current;
				}
			}

			return $this->set_user();
		}

		/**
		 * Determines whether the user is a current user.
		 *
		 * @param string|int|\WP_User $user User to be checked.
		 *
		 * @return bool Whether the user is the current user.
		 */
		public function is_current_user( \WP_User|int|string $user ): bool {
			$user = $this->get_user( $user );
			if ( function_exists( 'wp_get_current_user' ) ) {
				$current = \wp_get_current_user();

				return ( $current->ID === $user->ID );
			}

			$this->set_user();
			global $current_user;

			return ( $current_user->ID === $user->ID );
		}

	}
}
