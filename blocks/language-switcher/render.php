<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$style      = isset( $attributes['style'] ) ? (string) $attributes['style'] : 'dropdown';
$show_flags = isset( $attributes['showFlags'] ) ? (bool) $attributes['showFlags'] : true;
$show_names = isset( $attributes['showNames'] ) ? (bool) $attributes['showNames'] : true;

return i18n_translate()->render()->language_switcher( $style, $show_flags, $show_names );
