<?php

// member:renewal
function agileware_magic_tags_callback_member_renewal( $value ) {
	if ( ! is_user_logged_in() ) {
		return $value;
	} else {
		$membership_legal = renewal_membership_check();
		$value            = implode( ',', [ $membership_legal ] );

	}

	return $value;
}