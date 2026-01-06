<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$key = $attributes['translationKey'] ?? '';
$fallback = $attributes['fallback'] ?? '';
$tag = $attributes['tagName'] ?? 'span';

if ( empty( $key ) ) {
	return;
}

$translation = json_i18n_translate( $key, $fallback );
$wrapper_attributes = get_block_wrapper_attributes();

printf(
	'<%1$s %2$s>%3$s</%1$s>',
	esc_attr( $tag ),
	$wrapper_attributes,
	esc_html( $translation )
);
