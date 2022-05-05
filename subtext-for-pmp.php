<?php
/**
 * Plugin Name: Connect Paid Memberships Pro to Subtext
 * Description: Instantly connects the Subtext text messaging service with Paid Memberships Pro.
 * Version: 0.9.7
 * Author: Fourth Estate
 * Author URI: https://www.fourthestate.org
 * Text Domain: fe-subtext-pmp
 * Domain Path: /languages
 */
 /*

 * Copyright 2022 Fourth Estate®
 * (email : support@fourthestate.org)
 * GPLv2 Full license details in license.txt

  You must have your Subtext API key, and Campaign ID set in the Subtext Connector for this plugin to work.
	The plugin will automatically add and remove members to subtext on subscription, unsubscription, or manually opt-in and opt-out.
	You do not need to activate this plugin with Subtext.

	This plugin will only work if you have an active Subtext publisher account.

  This plugin requires the following plugins to function:
	* Paid Memberships Pro
	* Paid Memberships Pro - Shipping Add On
*/

defined( 'ABSPATH' ) || die( 'File cannot be accessed directly' );

define( 'FE_SUBTEXT_VERSION', dirname( __FILE__ ) );
define( 'FE_SUBTEXT_DIR', dirname( __FILE__ ) );
define( 'FE_SUBTEXT_BASENAME', plugin_basename( __FILE__ ) );
define( 'FE_SUBTEXT_URL', plugins_url( '', __FILE__ ) );

function pluginprefix_activate() {
    if ( is_plugin_active( 'paid-memberships-pro/paid-memberships-pro.php') ) {

    }
}
register_activation_hook( __FILE__, 'pluginprefix_activate' );

function fe_subtext_activate( $network_wide ) {
    //replace this with your dependent plugin
    $dependencies = array(
        'paid-memberships-pro/paid-memberships-pro.php' => 'Paid Memberships Pro',
        'pmpro-shipping/pmpro-shipping.php' => 'Paid Memberships Pro - Shipping Add On',
    );

    foreach ( $dependencies as $dependency => $label ) {
        $pmp_plugin_error = false;

        if ( ! file_exists( WP_PLUGIN_DIR . '/' . $dependency ) ) {
            $pmp_plugin_error = true;
        }

        if ( ! is_plugin_active( $dependency ) ) {
            $pmp_plugin_error = true;
        }

        if ( $pmp_plugin_error ) {
            echo '<h3>' . __( 'You need to install ' . $label . ' to use this plugin.', 'fe-subtext-pmp' ) . '</h3>';

            //Adding @ before will prevent XDebug output
            @trigger_error(__('You need to install and activate ' . $label . ' to use this plugin.', 'fe-subtext-pmp'), E_USER_ERROR);
        }
    }
}

register_activation_hook(__FILE__, 'fe_subtext_activate');

class Subtext_For_PMP {
    public function init()
    {
        require_once FE_SUBTEXT_DIR . '/includes/functions.php';

        /**
         * Subtext API
         */
        require_once FE_SUBTEXT_DIR . '/includes/api/abstract-subtext-api.php';
        require_once FE_SUBTEXT_DIR . '/includes/api/class-external-subscribers.php';
        require_once FE_SUBTEXT_DIR . '/includes/api/class-subtext-subscriber.php';

        require_once FE_SUBTEXT_DIR . '/includes/admin/class-settings.php';
        require_once FE_SUBTEXT_DIR . '/includes/class-edit-profile.php';

        // $subscriber = new Subtext_Subscriber( wp_get_current_user() );
        // // $subscribers = $external_subscribers->get_external_subcriber_by_id( '68f84258-859b-4540-84aa-1c76ed43fcf6' );
        // var_dump( $subscriber->get_subtext_data() ); exit;
        // $subscriber = $subtext_api->get_external_subcriber_by_id( '68f84258-859b-4540-84aa-1c76ed43fcf6' );
        // var_dump( $subscriber ); exit;
        // $subscriber = $subtext_api->create_external_subscriber( array(
        //     'phone_number' => '+19029055337',
        //     'external_id' => '222',
        //     'first_name' => 'From API',
        //     'last_name' => 'Lastname',
        //     'email' => 'fromapi@gmail.com',
        // ) );
        // var_dump( $subscriber ); exit;
        // $subscriber = $subtext_api->unsubscribe( '85825cfc-d3fa-4859-a7c5-db15d20748dc' );
        // var_dump( $subscriber ); exit;
        // $subscriber = $subtext_api->resubscribe( '85825cfc-d3fa-4859-a7c5-db15d20748dc' );
        // var_dump( $subscriber ); exit;

        $this->register_hooks();
    }

    public function register_hooks()
    {
        add_filter( 'fespmp_settings', array( $this, 'get_plugin_settings' ), 0 );
        // add_action( 'init', function() {
        //     var_dump( wp_get_current_user()->membership_level->ID ); exit;
        //     $subscriber = new Subtext_Subscriber( wp_get_current_user() );
        //     // $subscribers = $external_subscribers->get_external_subcriber_by_id( '68f84258-859b-4540-84aa-1c76ed43fcf6' );
        //     var_dump( $subscriber->get_subtext_data() ); exit;
        // });
    }

    public function get_plugin_settings()
    {
        return get_option( 'fe_subtext_pmp_settings' );
    }
}

(new Subtext_For_PMP())->init();
