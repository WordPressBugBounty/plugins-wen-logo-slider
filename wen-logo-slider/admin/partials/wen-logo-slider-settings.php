<?php
global $post;
$wlsp_settings = get_post_meta( $post->ID, 'wen_logo_slider_settings', true );

if ( empty( $wlsp_settings ) ) {
	$wlsp_settings = array();
}

$wlsp_global_settings = get_option( 'wen_logo_slider_settings' );
if ( empty( $wlsp_global_settings ) ) {
	$wlsp_global_settings = array();
}

$defaults = $this->settings_default_args();
if ( ! empty( $wlsp_global_settings ) ) {
	$defaults = array_merge( $defaults, $wlsp_global_settings );
}


if ( ! empty( $wlsp_settings ) ) {
	$settings_args = array_merge( $defaults, $wlsp_settings );
} else {
	if ( ! empty( $wlsp_global_settings ) ) {
		$settings_args = array_merge( $defaults, $wlsp_global_settings );
	} else {
		$settings_args = $defaults;
	}
} ?>
<?php wp_nonce_field( 'ws_logo_slider_settings_nonce_action', 'ws_logo_slider_settings_nonce_field' ); ?>	
<?php $this->settings_template( $settings_args );
