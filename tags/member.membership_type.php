<?php

// member:membership_type
function agileware_magic_tags_callback_member_membership_type( $value ) {
// Get login contact id
	$cid = CRM_Core_Session::singleton()->getLoggedInContactID();
	try {
		$result_membership = civicrm_api3( 'Membership', 'get', array(
			'contact_id'                   => $cid,
			'api.MembershipType.getsingle' => [ 'sequential' => 1, 'id' => "\$value.membership_type_id" ],
		) );
	} catch ( CiviCRM_API3_Exception $e ) {
		$result_membership = [];
	}

	if ( $result_membership['is_error'] == 1 ) {
		return 'No information about the user.';
	}

	$membership_type = array_pop( $result_membership['values'] )['api.MembershipType.getsingle'];
	if ( isset( $membership_type['is_error'] ) ) {
		return 'No information about the membership type';
	}

	return $membership_type['name'];
}