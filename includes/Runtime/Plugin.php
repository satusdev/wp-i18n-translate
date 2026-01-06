<?php

namespace I18nTranslate\Runtime;

use I18nTranslate\Db\Installer;
use I18nTranslate\I18n\Locale;
use I18nTranslate\I18n\PublicApi;
use I18nTranslate\I18n\TitleKeySupport;
use I18nTranslate\Http\Endpoints;
use I18nTranslate\Admin\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Plugin {
	private static ?Plugin $instance = null;
	private ?Services $services = null;

	public static function instance(): Plugin {
		if ( self::$instance === null ) {
			self::$instance = new Plugin();
		}
		return self::$instance;
	}

	public function boot(): void {
		$this->register_activation_hooks();
		add_action( 'plugins_loaded', [ $this, 'load' ], 1 );
	}

	public function load(): void {
		require_once I18N_TRANSLATE_PATH . 'includes/Db/Installer.php';
		require_once I18N_TRANSLATE_PATH . 'includes/I18n/compat.php';
		require_once I18N_TRANSLATE_PATH . 'includes/I18n/Locale.php';
		require_once I18N_TRANSLATE_PATH . 'includes/I18n/PublicApi.php';
		require_once I18N_TRANSLATE_PATH . 'includes/I18n/TitleKeySupport.php';
		require_once I18N_TRANSLATE_PATH . 'includes/Http/Endpoints.php';
		require_once I18N_TRANSLATE_PATH . 'includes/Admin/LanguagesPage.php';
		require_once I18N_TRANSLATE_PATH . 'includes/Admin/TranslationsPage.php';
		require_once I18N_TRANSLATE_PATH . 'includes/Admin/UsagePage.php';
                require_once I18N_TRANSLATE_PATH . 'includes/Admin/SettingsPage.php';
		require_once I18N_TRANSLATE_PATH . 'includes/Admin/DashboardPage.php';
		require_once I18N_TRANSLATE_PATH . 'includes/Admin/ScannerPage.php';
		require_once I18N_TRANSLATE_PATH . 'includes/Admin/ImportExportPage.php';
		require_once I18N_TRANSLATE_PATH . 'includes/Admin/Admin.php';
		require_once I18N_TRANSLATE_PATH . 'includes/Runtime/Services.php';
		require_once I18N_TRANSLATE_PATH . 'includes/Runtime/Strings.php';
		require_once I18N_TRANSLATE_PATH . 'includes/Runtime/Render.php';
		require_once I18N_TRANSLATE_PATH . 'includes/Runtime/LanguageSwitcherWidget.php';

		( new Installer() )->maybe_upgrade();
		$this->services = new Services();

		( new Locale() )->register();
		( new PublicApi() )->register();
		( new TitleKeySupport() )->register();
		( new Endpoints() )->register();

		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_frontend' ], 5 );
		add_action( 'widgets_init', function() {
			register_widget( \I18nTranslate\Runtime\LanguageSwitcherWidget::class );
		} );

		if ( is_admin() ) {
			( new Admin() )->register();
		}

		$this->register_blocks();
	}

	public function locale(): Locale {
		return $this->services()->locale();
	}

	public function strings(): Strings {
		return $this->services()->strings();
	}

	public function render(): Render {
		return $this->services()->render();
	}

	private function services(): Services {
		if ( $this->services === null ) {
			$this->services = new Services();
		}
		return $this->services;
	}

	private function register_activation_hooks(): void {
		register_activation_hook( I18N_TRANSLATE_PATH . 'i18n-translate.php', function() {
			require_once I18N_TRANSLATE_PATH . 'includes/Db/Installer.php';
			( new Installer() )->install();
		} );
	}

	private function register_blocks(): void {
		if ( function_exists( 'register_block_type' ) ) {
			register_block_type( I18N_TRANSLATE_PATH . 'blocks/language-switcher' );
		}
	}

	public function enqueue_frontend(): void {
		wp_register_script( 'i18n-translate-runtime', I18N_TRANSLATE_URL . 'assets/runtime.js', [], I18N_TRANSLATE_VERSION, true );

		wp_localize_script( 'i18n-translate-runtime', 'wpTemplateI18n', [
			'translations'  => function_exists( 'json_i18n_get_translations' ) ? json_i18n_get_translations() : [],
			'current_lang'  => function_exists( 'json_i18n_get_current_language' ) ? json_i18n_get_current_language() : 'en',
			'languages'     => function_exists( 'json_i18n_get_available_languages' ) ? json_i18n_get_available_languages() : [],
			'ajax_url'      => admin_url( 'admin-ajax.php' ),
			'nonce'         => wp_create_nonce( 'wp_template_nonce' ),
		] );

		wp_enqueue_script( 'i18n-translate-runtime' );
	}
}
