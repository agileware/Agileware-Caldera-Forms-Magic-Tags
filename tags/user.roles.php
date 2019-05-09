<?php

// user:roles
function agileware_magic_tags_callback_user_roles( $value ) {
	if ( ! is_user_logged_in() ) {
		return $value;
	} else {
		$user = get_userdata( get_current_user_id() );

		$value = implode( ',', $user->roles );
	}

	return $value;
}