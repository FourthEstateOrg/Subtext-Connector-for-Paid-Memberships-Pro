<?php

if ( ! class_exists( 'FESPMP_Settings' ) ) {
    class FESPMP_Settings
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
            add_action( 'admin_menu', array( $this, 'add_submenu_page' ), 60 );
			add_action( 'admin_init', array( $this, 'save_settings' ) );
			add_action( 'admin_notices', array( $this, 'save_notice' ), 10 );
			add_action( 'pmpro_membership_level_after_other_settings', array( $this, 'pmpro_membership_level_after_other_settings' ), 20 );
			add_action( "pmpro_save_membership_level", array( $this, "pmpro_save_membership_level" ) );
        }

		/**
		 * Add a Course page for settings under the Memberships menu.
		 */
		public function add_submenu_page() {
			// Course settings page under Memberships menu.
			add_submenu_page(
				'pmpro-dashboard',
				esc_html__('Subtext for Paid Membership Pro', 'fe-subtext-pmp'),
				esc_html__('Subtext Connector', 'fe-subtext-pmp'),
				'manage_options',
				'fe-subtext-pmp-settings',
				array( $this, 'view_settings' )
			);
		}

		public function view_settings() {
			require_once FE_SUBTEXT_DIR . '/templates/admin/settings.php';
		}

		public function save_settings() {
			// Check permissions.
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			// Check if form is being submitted.
			if ( ! isset( $_REQUEST['fe_subtext_pmp_save_settings'] ) ) {
				return;
			}

			$settings = array(
				'subtext_api_key' => sanitize_text_field( $_POST['subtext_api_key'] ),
				'subtext_campaign_id' => sanitize_text_field( $_POST['subtext_campaign_id'] ),
				'activated_membership_levels' => sanitize_text_field( $_POST['activated_membership_levels'] ),
			);


			update_option( 'fe_subtext_pmp_settings', $settings );
		}


		public function save_notice() {
			if ( isset( $_REQUEST['fe_subtext_pmp_save_settings'] ) ) {
                echo wp_kses(
                    sprintf( "<div class='updated'><p>%s</p></div>", esc_html__( 'Settings saved successfully.', 'fe-subtext-pmp') ),
                    array(
                        'div' => array(
                            'class' => array(),
                        ),
                        'p' => array(),
                    ),
                );
			}
		}

		/**
		 * Add checkbox to enable subtext opt-in on some levels.
		 */
		public function pmpro_membership_level_after_other_settings() {
			$level_id = isset( $_REQUEST['edit'] ) ? intval( $_REQUEST['edit'] ) : 0;
			if ( $level_id > 0 ) {
				$allow_subtext = fespmp_is_level_allowed_subtext( $level_id );
			} else {
				$allow_subtext = false;
			}
			?>
			<h3 class="topborder"><?php	 esc_html_e( 'Subtext Connector', 'fe-subtext-pmp' ); ?></h3>
			<table>
				<tbody class="form-table">
				<tr>
					<th scope="row" valign="top"><label
								for="allow_subtext"><?php esc_html_e( 'Allow Subtext subscription:', 'fe-subtext-pmp' ); ?></label></th>
					<td>
						<input type="checkbox" id="allow_subtext" name="allow_subtext" value="1" <?php checked( $allow_subtext, 1 ); ?> />
						<label for="allow_subtext"><?php esc_html_e( 'Check this if you want users in this level to subscibe or unsubscribe to your Subtext campaign.', 'fe-subtext-pmp' ); ?></label>
					</td>
				</tr>
				</tbody>
			</table>
			<?php
		}

		/**
		 * Save subtext opt-in feature setting when the level is saved/added
		 */
		function pmpro_save_membership_level( $level_id ) {
			$settings = get_option( 'fe_subtext_pmp_settings' );

			if ( isset( $_REQUEST['allow_subtext'] ) ) {
				$settings['activated_membership_levels'][] = $level_id;
			} else {
                if ( isset( $settings['activated_membership_levels'] ) && count( $settings['activated_membership_levels'] ) > 0 ) {
                    $settings['activated_membership_levels'] = array_diff(
                        $settings['activated_membership_levels'], 
                        array( $level_id ),
                    );
                }
			}

			update_option( 'fe_subtext_pmp_settings', $settings );
		}
    }

    FESPMP_Settings::get_instance();
}
