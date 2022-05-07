<?php
    $field_key = 'subtext_opt_in';
    $label = 'Subscribe to text message news and updates. Powered by Subtext.';
    $opt_in_value = get_user_meta( get_current_user_id(),  $field_key, true );

?>
<div class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_member_profile_edit-field pmpro_member_profile_edit-field- ' . $field_key, 'pmpro_member_profile_edit-field- ' . $field_key ) ); ?>">
    <label for="<?php echo esc_attr( $field_key ); ?>">
        <input type="checkbox" name="<?php echo esc_attr( $field_key ); ?>" id="<?php echo esc_attr( $field_key ); ?>" value="1" <?php echo esc_attr( checked( true, $opt_in_value ) ); ?>/>
        <?php esc_html_e( $label ); ?>
    </label>
</div>
