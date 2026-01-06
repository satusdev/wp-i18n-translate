<?php

namespace I18nTranslate\Runtime;

use I18nTranslate\I18n\Locale;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Services {
	private Locale $locale;
	private Strings $strings;
	private Render $render;

	public function __construct() {
		$this->locale  = new Locale();
		$this->strings = new Strings();
		$this->render  = new Render();
	}

	public function locale(): Locale {
		return $this->locale;
	}

	public function strings(): Strings {
		return $this->strings;
	}

	public function render(): Render {
		return $this->render;
	}
}
