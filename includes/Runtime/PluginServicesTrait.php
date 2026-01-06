<?php

namespace I18nTranslate\Runtime;

use I18nTranslate\I18n\Locale;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

trait PluginServicesTrait {
	private ?Services $services = null;

	public function services(): Services {
		if ( $this->services === null ) {
			require_once I18N_TRANSLATE_PATH . 'includes/Runtime/Services.php';
			require_once I18N_TRANSLATE_PATH . 'includes/Runtime/Strings.php';
			require_once I18N_TRANSLATE_PATH . 'includes/Runtime/Render.php';
			$this->services = new Services();
		}
		return $this->services;
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
}
