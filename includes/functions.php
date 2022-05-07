<?php

/**
 * Get the plugin options
 *
 * @return array
 */
function fespmp_get_options() {
    $settings = apply_filters( 'fespmp_settings', false );

    if ( false === $settings ) {
        $settings = get_option( 'fe_subtext_pmp_settings' );
    }

    return $settings;
}

/**
 * Get the activated membership levels for subtext opt in
 *
 * @return array
 */
function fespmp_get_activated_membership_levels() {
    $settings = fespmp_get_options();
    
    if ( ! isset( $settings['activated_membership_levels'] ) || null === $settings['activated_membership_levels'] ) {
        return array();
    }

    return $settings['activated_membership_levels'];
}

function fespmp_is_user_allowed_for_subtext( $current_user = false, $level = false )
{
    if ( false === $current_user ) {
        $current_user = wp_get_current_user();
    }

    $activated_membership_levels = fespmp_get_activated_membership_levels();

    if ( false === $level ) {
        $membership = pmpro_getMembershipLevelsForUser( $current_user->ID );
        $level = $membership[0]->ID;
    }

    return in_array( $level, $activated_membership_levels );
}

function fespmp_is_level_allowed_subtext( $level_id )
{
    return in_array( $level_id, fespmp_get_activated_membership_levels() ) ? 1 : 0;
}
