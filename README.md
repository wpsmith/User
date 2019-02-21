# User

This package contains two classes:
1. CurrentUser
1. [HideUser](https://wpsmith.net/2019/hiding-an-user-in-the-wordpress-admin/)

## Set Current User

WordPress does not set the current user until right before the `init` hook. This can be problematic sometimes.
Sometimes, you may need to set the current user before WordPress is ready to set the current user (e.g., on `after_setup_theme`, `muplugins_loaded`, `registered_taxonomy` or `registered_post_type` hooks). So this class enables you to do that.

For the current user:
```php
$the_current_user = \WPS\WP\User\CurrentUser::get_instance()->get_current_user();
```

Determining whether a user is the current user by User ID, email or username:
```php
// By ID.
\WPS\WP\User\CurrentUser::get_instance()->is_current_user( 2 )

// By Email.
\WPS\WP\User\CurrentUser::get_instance()->is_current_user( 'email@domain.com' )

// By username/user login
\WPS\WP\User\CurrentUser::get_instance()->is_current_user( 'myusername' )
```

You can also use this to set "super" users.
```php
$super_users = array(
    'username1',
    'email@domain.com',
    3,
);
\WPS\WP\User\CurrentUser::get_instance()->is_current_a_super_user()
```

## Hide User

Sometimes it is good to hide a user from other users so that user won't be deleted or modified accidentally by another administrator.

To hide a user or set of users:

```php
\WPS\WP\User\HideUser::get_instance( array(
    'hidden_user1',
    'hidden_user2',
) );
```