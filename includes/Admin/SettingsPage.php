<?php

namespace I18nTranslate\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SettingsPage {

	public function render(): void {
		if ( ! current_user_can( 'i18n_translate_manage' ) ) {
			wp_die( esc_html__( 'You do not have permission to access this page.', 'i18n-translate' ) );
		}

		$languages = json_i18n_get_available_languages();
		$default_lang = get_option( 'i18n_translate_default_language', 'en' );
		$auto_detect = get_option( 'i18n_translate_auto_detect', '1' );
		?>
		<div class="wrap i18n-admin">
			<div class="i18n-header">
				<h1><?php esc_html_e( 'Settings', 'i18n-translate' ); ?></h1>
			</div>

			<form method="post" action="options.php">
				<?php settings_fields( 'i18n_translate_settings' ); ?>
				
				<div class="i18n-card">
					<div class="i18n-card-body">
						<div class="i18n-form-group">
							<label for="i18n_translate_default_language">
								<?php esc_html_e( 'Default Language', 'i18n-translate' ); ?>
							</label>
							<div class="i18n-text-muted">
								<?php esc_html_e( 'The language to serve when no other language is detected.', 'i18n-translate' ); ?>
							</div>
							<select name="i18n_translate_default_language" id="i18n_translate_default_language" class="i18n-select" style="max-width: 400px;">
								<?php foreach ( $languages as $code => $lang ) : ?>
									<option value="<?php echo esc_attr( $code ); ?>" <?php selected( $default_lang, $code ); ?>>
										<?php echo esc_html( ( $lang['flag'] ?? '' ) . ' ' . ( $lang['native_name'] ?? $lang['name'] ) . ' (' . $code . ')' ); ?>
									</option>
								<?php endforeach; ?>
							</select>
						</div>

						<div class="i18n-form-group">
							<label for="i18n_translate_auto_detect">
								<?php esc_html_e( 'Auto Detect Language', 'i18n-translate' ); ?>
							</label>
							<div class="i18n-text-muted">
								<?php esc_html_e( 'Automatically detect user language based on browser settings.', 'i18n-translate' ); ?>
							</div>
							<div class="i18n-toggle-wrapper">
								<label class="i18n-toggle">
									<input type="hidden" name="i18n_translate_auto_detect" value="0">
									<input type="checkbox" name="i18n_translate_auto_detect" id="i18n_translate_auto_detect" value="1" <?php checked( $auto_detect, '1' ); ?>>
									<span class="i18n-toggle-slider"></span>
								</label>
								<span><?php esc_html_e( 'Enable auto-detection', 'i18n-translate' ); ?></span>
							</div>
						</div>

						<div style="margin-top: 20px;">
							<button type="submit" class="i18n-btn i18n-btn-primary">
								<?php esc_html_e( 'Save Settings', 'i18n-translate' ); ?>
							</button>
						</div>
					</div>
				</div>
			</form>
		</div>
		<?php
	}
}
