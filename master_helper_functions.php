<?php

function is_civi_enable() {
	return function_exists( 'civicrm_initialize' );
}

function membership_check() {

	if ( is_civi_enable() && civicrm_initialize() ) {

		try {

			$cid = CRM_Core_Session::singleton()->getLoggedInContactID();

			$memberships = civicrm_api3( 'Membership', 'get', array( 'contact_id' => $cid ) );

			if ( $memberships["count"] == 0 ) {
				return 0;
			} else {
				foreach ( $memberships["values"] as $membership ) {

					$membership_status = $membership['membership_name'];

					return $membership_status;
					break;
				}
			}
		} catch ( CiviCRM_API3_Exception $e ) {
			if ( isset( $cid ) ) {
				error_log( 'Unable to obtain membership for ' . $cid );
			}
		}
	}
}

function contact_subtype_check() {

	if ( is_civi_enable() && civicrm_initialize() ) {

		try {

			$cid = CRM_Core_Session::singleton()->getLoggedInContactID();

			$contacts = civicrm_api3( 'Contact', 'get', array( 'contact_id' => $cid ) );

			if ( $contacts["count"] == 0 ) {
				return 0;
			} else {
				foreach ( $contacts["values"][2]["contact_sub_type"] as $contact ) {
					if ( $contact ) {
						return $contact;
						break;
					}
				}
			}
		} catch ( CiviCRM_API3_Exception $e ) {
			if ( isset( $cid ) ) {
				error_log( 'Unable to obtain contact subtype for ' . $cid );
			}
		}
	}
}

function get_related_contact_subtype( $relationshipTypeId ) {
	if ( ! is_civi_enable() ) {
		return null;
	}
	$helper = CiviCRM_Caldera_Forms::instance()->helper;
	civicrm_initialize();
	$contact = $helper->current_contact_data_get();
	if ( isset( $contact ) && is_array( $contact ) ) {
		$contactId    = $contact['contact_id'];
		$relationship = civicrm_api3( 'Relationship', 'get', [
			'sequential'           => 1,
			'contact_id_a'         => $contactId,
			'contact_id_b'         => $contactId,
			'relationship_type_id' => $relationshipTypeId,
			'options'              => [ 'or' => [ [ "contact_id_a", "contact_id_b" ] ] ],
		] );
		if ( count( $relationship ) > 0 ) {
			$relationship   = $relationship['values'][0];
			$otherContactId = ( $relationship['contact_id_a'] == $contactId ) ? $relationship['contact_id_b'] : $relationship['contact_id_a'];
			$contact        = $helper->get_civi_contact( $otherContactId );

			return $contact;
		}
	}

	return null;
}

function renewal_membership_check() {

	if ( is_civi_enable() && civicrm_initialize() ) {

		try {

			$cid = CRM_Core_Session::singleton()->getLoggedInContactID();

			$memberships = civicrm_api3( 'Membership', 'get', array( 'contact_id' => $cid ) );

			if ( $memberships["count"] == 0 ) {
				return 0;
			} else {
				foreach ( $memberships["values"] as $membership ) {

					$membership_end_date    = new DateTime( $membership['end_date'] );
					$today_date             = new DateTime( 'now' );
					$three_month_from_today = $today_date->modify( '+3 month' );
					$is_renewal             = ( $three_month_from_today >= $membership_end_date );
					if ( $is_renewal ) {
						return $is_renewal;
						break;
					}
				}
			}
		} catch ( CiviCRM_API3_Exception $e ) {
			if ( isset( $cid ) ) {
				error_log( 'Unable to obtain membership for ' . $cid );
			}
		}
	}
}

/**
 * Get price field values based on given membership type and set of option values.
 * It only returns active price set and price field values.
 *
 * @param $currentMembershipTypeId
 * @param $optionValues
 *
 * @return array
 * @throws CiviCRM_API3_Exception
 */
function cf_getPriceFieldOptionsOfForm( $currentMembershipTypeId, $optionValues ) {
	if ( ! is_civi_enable() ) {
		return [];
	}
	$priceFieldOptions = civicrm_api3( 'PriceFieldValue', 'get', [
		'sequential'                            => 1,
		'membership_type_id'                    => $currentMembershipTypeId,
		'is_active'                             => 1,
		'price_field_id.is_active'              => 1,
		'return'                                => [ "price_field_id.expire_on", "price_field_id.active_on" ],
		'price_field_id.price_set_id.is_active' => 1,
		'id'                                    => [ 'IN' => $optionValues ],
	] );

	$priceFieldOptions = $priceFieldOptions['values'];

	return $priceFieldOptions;
}

/**
 * Get set of option values from form fields.
 *
 * @param $fields
 *
 * @return array
 */
function cf_getOptionValuesFromFields( $fields ) {
	foreach ( $fields as $fieldId => $field ) {
		$fieldConfig = $field['config'];
		if ( isset( $fieldConfig['auto_type'] ) && strpos( $fieldConfig['auto_type'], 'price_field' ) !== false ) {
			$optionValues = $fieldConfig['option'];
			$optionValues = array_keys( $optionValues );
		}
	}

	return $optionValues;
}

/**
 * Check if given price field value is active or not.
 *
 * @param $priceFieldOption
 *
 * @return bool
 * @throws Exception
 */
function cf_isPriceFieldActive( $priceFieldOption ) {
	$currentDate = new DateTime();
	$activeOn    = ( isset( $priceFieldOption['price_field_id.active_on'] ) ) ? $priceFieldOption['price_field_id.active_on'] : null;
	$expireOn    = ( isset( $priceFieldOption['price_field_id.expire_on'] ) ) ? $priceFieldOption['price_field_id.expire_on'] : null;

	$isAfterStartDate = false;
	$isBeforeEndDate  = false;

	if ( ! $activeOn ) {
		$isAfterStartDate = true;
	} else {
		$activeOn = DateTime::createFromFormat( 'Y-m-d H:i:s', $activeOn );
		if ( $currentDate >= $activeOn ) {
			$isAfterStartDate = true;
		}
	}

	if ( ! $expireOn ) {
		$isBeforeEndDate = true;
	} else {
		$expireOn = DateTime::createFromFormat( 'Y-m-d H:i:s', $expireOn );
		if ( $expireOn <= $expireOn ) {
			$isBeforeEndDate = true;
		}
	}

	return ( $isBeforeEndDate && $isAfterStartDate );
}

/**
 * Get all current membership statues.
 *
 * @return array
 * @throws CiviCRM_API3_Exception
 */
function cf_getCurrentMembershipStatues() {
	if ( ! is_civi_enable() ) {
		return [];
	}
	$membershipStatues = civicrm_api3( 'MembershipStatus', 'get', [
		'sequential'        => 1,
		'is_current_member' => 1,
	] );

	$membershipStatues = $membershipStatues['values'];
	$membershipStatues = array_column( $membershipStatues, 'name' );

	return $membershipStatues;
}

/**
 * Get logged in member's memberships of provided statues.
 *
 * @param $membershipStatues
 *
 * @return array
 * @throws CiviCRM_API3_Exception
 */
function cf_getMembershipsByStatues( $membershipStatues ) {
	if ( ! is_civi_enable() ) {
		return [];
	}
	$memberships = civicrm_api3( 'Membership', 'get', [
		'sequential' => 1,
		'contact_id' => "user_contact_id",
		'status_id'  => array(
			'IN' => $membershipStatues,
		),
	] );
	$memberships = $memberships['values'];

	return $memberships;
}

/**
 * Caldera forms hook to store the rendering form in Global.
 *
 * @param $form
 */
function cfc_get_membership_form_config( $form ) {
	global $civicrmForm;
	$civicrmForm = $form;
}