<?php
	$settings = get_option( 'fe_subtext_pmp_settings' );

	$membership_levels = pmpro_getAllLevels( true, true );
?>
	<style>
		.popup-trigger {
			cursor: pointer;
		}
		.popup {
			position: fixed;
			top: 0;
			left: 160px;
			right: 0;
			bottom: 0;
			background: #00000063;
			display: none;
		}
		.popup.open {
			display: block;
		}
		.popup .popup-content {
			max-width: 900px;
			width: 100%;
			display: flex;
			position: relative;
			margin: 5rem auto;
		}
		.popup .popup-content img { width: 100%; }
		.popup .popup-content a.close {
			color: #fff;
			font-size: 40px;
			position: absolute;
			top: -30px;
			right: -30px;
			z-index: 1;
			cursor: pointer;
		}
	</style>
	<h1 class="wp-heading-inline">
		<?php esc_html_e( 'Subtext Connector for Paid Memberships Pro', 'fe-subtext-pmp' ); ?>
	</h1>
	<form method='POST'>

		<table>
			<tbody class="form-table">
				<tr>
					<th scope="row" valign="top">
						<label for="subtext_api_key"><?php esc_attr_e( 'Subtext API Key', 'fe-subtext-pmp' ); ?></label>
					</th>
					<td>
						<input type="text" name="subtext_api_key" id="subtext_api_key" value="<?php echo esc_attr( $settings['subtext_api_key'] ); ?>"/>
						<p>The Subtext API uses your campaign’s secret key to authenticate requests. You can view and manage your campaign’s secret key on the Campaign tab of your Subtext Campaign Dashboard. <br><a class="popup-trigger" data-target="#subtext-api-key">Where to find this?</a></p>
					</td>
				</tr>
				<tr>
					<th scope="row" valign="top">
					<label for="subtext_campaign_id"><?php esc_attr_e( 'Campaign ID', 'fe-subtext-pmp' ); ?></label>
					</th>
					<td>
						<input type="text" name="subtext_campaign_id" id="subtext_campaign_id" value="<?php echo esc_attr( $settings['subtext_campaign_id'] ); ?>"/>
						<p>The Subtext id of the Campaign resource. <br><a class="popup-trigger" data-target="#subtext-campaign-id">Where to find this?</a></p>
					</td>
				</tr>
			</tbody>
		</table>

		<p class="submit"><input type="submit" name="fe_subtext_pmp_save_settings" value="<?php esc_attr_e( 'Save Settings', 'fe-subtext-pmp' ); ?>" class="button button-primary" /></p>
	</form>

	<div class="popup">
		<div class="popup-content">
			<a class="close">&times;</a>
			<img id="subtext-api-key" src="<?php echo esc_url( FE_SUBTEXT_URL . '/assets/img/subtext-api-key.jpg' ); ?>" alt="api key">
			<img id="subtext-campaign-id" src="<?php echo esc_url( FE_SUBTEXT_URL . '/assets/img/subtext-campaign-id.jpg'); ?>" alt="campaign id">
		</div>
	</div>

	<script>
		(function($) {
			$(document).on('click', '.popup .popup-content a.close', function(e) {
				$('.popup').removeClass('open');
			});
			$(document).on('click', '.popup-trigger', function(e) {
				e.preventDefault();
				$('.popup').addClass('open');
				$('.popup .popup-content img').hide();
				$($(this).data('target')).show();
			});
		})(jQuery)
	</script>
	<?php
?>
