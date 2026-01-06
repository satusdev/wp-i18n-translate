<?php

namespace I18nTranslate\Runtime;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Strings {
	public function translate( string $domain, string $key, string $default = '' ): string {
		$domain = $domain !== '' ? sanitize_text_field( $domain ) : 'default';
		$key    = sanitize_text_field( $key );

		if ( $key === '' ) {
			return $default !== '' ? $default : '';
		}

		$lang = json_i18n_get_current_language();

		$cache_key = "t:{$lang}:{$domain}:{$key}";
		$cached    = wp_cache_get( $cache_key, 'i18n_translate' );
		if ( $cached !== false ) {
			return (string) $cached;
		}

		global $wpdb;
		$strings_table = $wpdb->prefix . 'i18n_strings';
		$tr_table      = $wpdb->prefix . 'i18n_translations';

		$string_id = (int) $wpdb->get_var( $wpdb->prepare(
			"SELECT id FROM {$strings_table} WHERE domain = %s AND string_key = %s",
			$domain,
			$key
		) );

		if ( $string_id === 0 ) {
			$wpdb->insert( $strings_table, [
				'domain'       => $domain,
				'string_key'   => $key,
				'default_text' => $default,
			] );
			$string_id = (int) $wpdb->insert_id;
		}

		$translation = $wpdb->get_var( $wpdb->prepare(
			"SELECT translation_text FROM {$tr_table} WHERE string_id = %d AND lang_code = %s",
			$string_id,
			$lang
		) );

		if ( is_string( $translation ) && $translation !== '' ) {
			wp_cache_set( $cache_key, $translation, 'i18n_translate', HOUR_IN_SECONDS );
			return $translation;
		}

		$result = $default !== '' ? $default : $key;
		wp_cache_set( $cache_key, $result, 'i18n_translate', HOUR_IN_SECONDS );
		return $result;
	}

	public function get_translations_for_js( string $domain = '' ): array {
		$lang = json_i18n_get_current_language();

		$cache_key = 'js:' . $lang . ':' . ( $domain !== '' ? $domain : '*' );
		$cached    = wp_cache_get( $cache_key, 'i18n_translate' );
		if ( $cached !== false ) {
			return (array) $cached;
		}

		global $wpdb;
		$strings_table = $wpdb->prefix . 'i18n_strings';
		$tr_table      = $wpdb->prefix . 'i18n_translations';

		$params = [ $lang ];
		$where  = '';
		if ( $domain !== '' ) {
			$where   = ' AND s.domain = %s';
			$params[] = $domain;
		}

		$query = $wpdb->prepare(
			"SELECT s.domain, s.string_key, COALESCE(t.translation_text, s.default_text, s.string_key) AS value
			 FROM {$strings_table} s
			 LEFT JOIN {$tr_table} t ON t.string_id = s.id AND t.lang_code = %s
			 WHERE 1=1 {$where}",
			...$params
		);

		$rows = $wpdb->get_results( $query, ARRAY_A );
		$out  = [];
		foreach ( $rows as $row ) {
			$dom = $row['domain'] ?? 'default';
			if ( ! isset( $out[ $dom ] ) ) {
				$out[ $dom ] = [];
			}
			$out[ $dom ][ $row['string_key'] ] = $row['value'];
		}

		wp_cache_set( $cache_key, $out, 'i18n_translate', HOUR_IN_SECONDS );
		return $out;
	}
}
