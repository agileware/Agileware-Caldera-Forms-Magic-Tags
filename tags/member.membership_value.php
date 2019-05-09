<?php

// member:membership_value
function agileware_magic_tags_callback_member_membership_value( $value ) {
	global $civicrmForm;

	// Initializing CiviCRM.
	civi_wp()->initialize();

	// Get logged in Contact Id
	$loggedInContact = CRM_Core_Session::singleton()->getLoggedInContactID();

	if ( $loggedInContact ) {

		// Get all current membership statues.
		$membershipStatues = cf_getCurrentMembershipStatues();

		if ( count( $membershipStatues ) ) {

			// Get current memberships of logged in contact
			$memberships = cf_getMembershipsByStatues( $membershipStatues );

			if ( count( $memberships ) ) {

				// Consider only first membership and its type.
				$currentMembership       = $memberships[0];
				$currentMembershipTypeId = $currentMembership['membership_type_id'];

				// Go through all fields and check if any of them using Priceset.
				$fields = $civicrmForm['fields'];

				// Get all possible option values of the form.
				$optionValues = cf_getOptionValuesFromFields( $fields );

				if ( count( $optionValues ) ) {

					// get price field options of selected membership type and option fields.
					$priceFieldOptions = cf_getPriceFieldOptionsOfForm( $currentMembershipTypeId, $optionValues );

					if ( count( $priceFieldOptions ) ) {
						foreach ( $priceFieldOptions as $priceFieldOption ) {

							// If option field is active return the id.
							if ( cf_isPriceFieldActive( $priceFieldOption ) ) {
								return $priceFieldOption['id'];
							}
						}
					}
				}
			}
		}
	}

	return $value;
}