<?php
// This file is based on wp-includes/js/tinymce/langs/wp-langs.php

if ( ! defined( 'ABSPATH' ) )
    exit;

if ( ! class_exists( '_WP_Editors' ) )
    require( ABSPATH . WPINC . '/class-wp-editor.php' );

function wen_logo_slider_tinymce_plugin_translation() {
    $strings = array(
        'button_title' => __( 'WEN Logo Slider', 'wen-logo-slider' ),
        'popup_title'  => __( 'WEN Logo Slider Shortcode Generator', 'wen-logo-slider' ),
    );
    $locale = _WP_Editors::$mce_locale;
    $translated = 'tinyMCE.addI18n("' . $locale . '.wen_logo_slider", ' . json_encode( $strings ) . ");\n";

     return $translated;
}

$strings = wen_logo_slider_tinymce_plugin_translation();

