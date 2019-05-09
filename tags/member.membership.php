<?php

// member:membership
function agileware_magic_tags_callback_member_membership( $value ) {
	if ( ! is_user_logged_in() ) {
		return $value;
	} else {
		$membership_legal = membership_check();
		$value            = implode( ',', [ $membership_legal ] );

	}

	return $value;
}