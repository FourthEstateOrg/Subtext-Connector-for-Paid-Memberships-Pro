<?php

if ( ! class_exists( 'FESPMP_Edit_Profile' ) ) {
    class FESPMP_Edit_Profile
    {
        private static $instance = null;

        public static function get_instance()
        {
            if ( null === self::$instance ) {
                self::$instance = new self();
            }

            return self::$instance;
        }
        
        public function __construct()
        {
            add_action( 'pmpro_show_user_profile', array( $this, 'add_subtext_opt_in' ), 999 );
            add_action( 'pmpro_personal_options_update', array( $this, 'add_subtext_opt_in_save' ) );
            add_action( 'user_register', array( $this, 'subscribe_to_subtext_by_default' ) );
            add_action( "pmpro_after_change_membership_level", array( $this, "my_pmpro_after_change_membership_level" ), 10, 2);
        }

        public function add_subtext_opt_in( $current_user ) {        
            if ( fespmp_is_user_allowed_for_subtext() ) {
                require_once FE_SUBTEXT_DIR . '/templates/edit_profile_subtext_section.php';
            }
        }

        public function add_subtext_opt_in_save( $user_id ) {
            $user = wp_get_current_user();
            $subscriber = new Subtext_Subscriber( $user );

            if ( isset( $_POST['subtext_opt_in'] ) ) {
                update_user_meta( $user_id, 'subtext_opt_in', sanitize_text_field( $_POST['subtext_opt_in'] ) );
                if ( false === $subscriber->get_subtext_data() ) {
                    // Create subscriber to subtext
                    $phone = sanitize_text_field( $_POST['sphone'] );
                    $first_name = sanitize_text_field( $_POST['first_name'] );
                    $last_name = sanitize_text_field( $_POST['last_name'] );
                    if ( ! empty( $phone ) ) {
                        $data = array(
                            'phone_number' => $phone,
                            'external_id' => $user_id . 234,
                            'first_name' => $first_name,
                            'last_name' => $last_name,
                            'email' => $user->user_email,
                        );
                        $subtext_result = $subscriber->create_external_subscriber( $data );

                        if ( isset( $subtext_result['external_subscriber'] ) ) {
                            update_user_meta( $user_id, 'subtext_uuid', $subtext_result['external_subscriber']['subtext_uuid'] );
                        }
                    }
                } else {
                    $subscriber->resubscribe();
                }
            } else {
                delete_user_meta( $user_id, 'subtext_opt_in' );
                $subscriber->unsubscribe();
            }
        }

        public function subscribe_to_subtext_by_default( $user_id )
        {
            if ( fespmp_is_user_allowed_for_subtext( $user = get_user_by( 'ID', $user_id ), sanitize_text_field( $_POST['level'] ) ) ) {
                $subscriber = new Subtext_Subscriber( $user );
                // Create subscriber to subtext
                $phone = sanitize_text_field( $_POST['sphone'] );
                $first_name = sanitize_text_field( $_POST['first_name'] );
                $last_name = sanitize_text_field( $_POST['last_name'] );
                if ( empty( $phone ) ) {
                    $phone = get_user_meta( $user_id, 'pmpro_sphone', true );
                }
                if ( empty( $first_name ) ) {
                    $first_name = get_user_meta( $user_id, 'first_name', true );
                }
                if ( empty( $last_name ) ) {
                    $last_name = get_user_meta( $user_id, 'last_name', true );
                }
                if ( ! empty( $phone ) ) {
                    $data = array(
                        'phone_number' => $phone,
                        'external_id' => $user_id,
                        'first_name' => $first_name,
                        'last_name' => $last_name,
                        'email' => $user->user_email,
                    );
                    $subtext_result = $subscriber->create_external_subscriber( $data );

                    if ( isset( $subtext_result['external_subscriber'] ) ) {
                        update_user_meta( $user_id, 'subtext_opt_in', 1 );
                        update_user_meta( $user_id, 'subtext_uuid', $subtext_result['external_subscriber']['subtext_uuid'] );
                    }
                }
            }
        }

        public function my_pmpro_after_change_membership_level($level_id, $user_id)
        {
            $user = get_user_by('ID', $user_id);
            //are they cancelling? and don't do this from admin (e.g. when admin's are changing levels)
            if(empty($level_id) && !is_admin())
            {
                //only delete non-admins
                if(!user_can($user_id, "manage_options"))
                {
                    $subscriber = new Subtext_Subscriber( $user );
                    delete_user_meta( $user_id, 'subtext_opt_in' );
                    $subscriber->unsubscribe();
                }
            } else if ( fespmp_is_user_allowed_for_subtext( $user, $level_id ) ) {
                $subscriber = new Subtext_Subscriber( $user );
                if ( false === $subscriber->get_subtext_data() ) {
                    // Create subscriber to subtext
                    $phone = sanitize_text_field( $_POST['sphone'] );
                    $first_name = sanitize_text_field( $_POST['first_name'] );
                    $last_name = sanitize_text_field( $_POST['last_name'] );
                    if ( empty( $phone ) ) {
                        $phone = get_user_meta( $user_id, 'pmpro_sphone', true );
                    }
                    if ( empty( $first_name ) ) {
                        $first_name = get_user_meta( $user_id, 'first_name', true );
                    }
                    if ( empty( $last_name ) ) {
                        $last_name = get_user_meta( $user_id, 'last_name', true );
                    }
                    if ( ! empty( $phone ) ) {
                        $data = array(
                            'phone_number' => $phone,
                            'external_id' => $user_id,
                            'first_name' => $first_name,
                            'last_name' => $last_name,
                            'email' => $user->user_email,
                        );
                        $subtext_result = $subscriber->create_external_subscriber( $data );

                        if ( isset( $subtext_result['external_subscriber'] ) ) {
                            update_user_meta( $user_id, 'subtext_opt_in', 1 );
                            update_user_meta( $user_id, 'subtext_uuid', $subtext_result['external_subscriber']['subtext_uuid'] );
                        }
                    }
                } else {
                    update_user_meta( $user_id, 'subtext_opt_in', 1 );
                    $subscriber->resubscribe();
                }
            }
        }
    }

    FESPMP_Edit_Profile::get_instance();
}
