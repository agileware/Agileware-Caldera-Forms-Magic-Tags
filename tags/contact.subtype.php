<?php

// contact:subtype
function agileware_magic_tags_callback_contact_subtype( $value ) {
	if ( ! is_user_logged_in() ) {
		return $value;
	}
	$contact_legal = contact_subtype_check();
	$value         = implode( ',', [ $contact_legal ] );

	return $value;
}