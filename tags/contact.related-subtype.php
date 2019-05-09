<?php

// contact:related_subtype
function agileware_magic_tags_callback_contact_related_subtype( $value ) {
	if ( ! is_user_logged_in() ) {
		return $value;
	}
	$related = get_related_contact_subtype( 11 );
	if ( ! empty( $related ) ) {
		$value = $related['contact_sub_type'][0];
	} else {
		$value = "NOT FOUND";
	}

	return $value;
}