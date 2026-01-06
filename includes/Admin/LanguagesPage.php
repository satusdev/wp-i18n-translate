<?php

namespace I18nTranslate\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class LanguagesPage {
	private const SLUG = 'i18n-translate';

	/**
	 * Language category constants
	 */
	private const CATEGORY_POPULAR = 'popular';
	private const CATEGORY_EUROPEAN = 'european';
	private const CATEGORY_ASIAN = 'asian';
	private const CATEGORY_MIDDLE_EAST = 'middle_east';
	private const CATEGORY_AFRICAN = 'african';
	private const CATEGORY_AMERICAS = 'americas';

	/**
	 * Complete WordPress language presets organized by region
	 */
	private const PRESETS = [
		// Popular Languages
		'en' => ['code' => 'en', 'locale' => 'en_US', 'name' => 'English', 'native_name' => 'English', 'flag' => '🇺🇸', 'rtl' => false, 'category' => 'popular'],
		'es' => ['code' => 'es', 'locale' => 'es_ES', 'name' => 'Spanish', 'native_name' => 'Español', 'flag' => '🇪🇸', 'rtl' => false, 'category' => 'popular'],
		'fr' => ['code' => 'fr', 'locale' => 'fr_FR', 'name' => 'French', 'native_name' => 'Français', 'flag' => '🇫🇷', 'rtl' => false, 'category' => 'popular'],
		'de' => ['code' => 'de', 'locale' => 'de_DE', 'name' => 'German', 'native_name' => 'Deutsch', 'flag' => '🇩🇪', 'rtl' => false, 'category' => 'popular'],
		'pt_BR' => ['code' => 'pt_BR', 'locale' => 'pt_BR', 'name' => 'Portuguese (Brazil)', 'native_name' => 'Português do Brasil', 'flag' => '🇧🇷', 'rtl' => false, 'category' => 'popular'],
		'zh_CN' => ['code' => 'zh_CN', 'locale' => 'zh_CN', 'name' => 'Chinese (Simplified)', 'native_name' => '简体中文', 'flag' => '🇨🇳', 'rtl' => false, 'category' => 'popular'],
		'ja' => ['code' => 'ja', 'locale' => 'ja', 'name' => 'Japanese', 'native_name' => '日本語', 'flag' => '🇯🇵', 'rtl' => false, 'category' => 'popular'],
		'ru' => ['code' => 'ru', 'locale' => 'ru_RU', 'name' => 'Russian', 'native_name' => 'Русский', 'flag' => '🇷🇺', 'rtl' => false, 'category' => 'popular'],
		'ar' => ['code' => 'ar', 'locale' => 'ar', 'name' => 'Arabic', 'native_name' => 'العربية', 'flag' => '🇸🇦', 'rtl' => true, 'category' => 'popular'],
		'ko' => ['code' => 'ko', 'locale' => 'ko_KR', 'name' => 'Korean', 'native_name' => '한국어', 'flag' => '🇰🇷', 'rtl' => false, 'category' => 'popular'],
		'it' => ['code' => 'it', 'locale' => 'it_IT', 'name' => 'Italian', 'native_name' => 'Italiano', 'flag' => '🇮🇹', 'rtl' => false, 'category' => 'popular'],
		'nl' => ['code' => 'nl', 'locale' => 'nl_NL', 'name' => 'Dutch', 'native_name' => 'Nederlands', 'flag' => '🇳🇱', 'rtl' => false, 'category' => 'popular'],
		'pl' => ['code' => 'pl', 'locale' => 'pl_PL', 'name' => 'Polish', 'native_name' => 'Polski', 'flag' => '🇵🇱', 'rtl' => false, 'category' => 'popular'],
		'tr' => ['code' => 'tr', 'locale' => 'tr_TR', 'name' => 'Turkish', 'native_name' => 'Türkçe', 'flag' => '🇹🇷', 'rtl' => false, 'category' => 'popular'],
		'hi' => ['code' => 'hi', 'locale' => 'hi_IN', 'name' => 'Hindi', 'native_name' => 'हिन्दी', 'flag' => '🇮🇳', 'rtl' => false, 'category' => 'popular'],

		// European Languages
		'en_GB' => ['code' => 'en_GB', 'locale' => 'en_GB', 'name' => 'English (UK)', 'native_name' => 'English (UK)', 'flag' => '🇬🇧', 'rtl' => false, 'category' => 'european'],
		'pt_PT' => ['code' => 'pt_PT', 'locale' => 'pt_PT', 'name' => 'Portuguese (Portugal)', 'native_name' => 'Português', 'flag' => '🇵🇹', 'rtl' => false, 'category' => 'european'],
		'da' => ['code' => 'da', 'locale' => 'da_DK', 'name' => 'Danish', 'native_name' => 'Dansk', 'flag' => '🇩🇰', 'rtl' => false, 'category' => 'european'],
		'sv' => ['code' => 'sv', 'locale' => 'sv_SE', 'name' => 'Swedish', 'native_name' => 'Svenska', 'flag' => '🇸🇪', 'rtl' => false, 'category' => 'european'],
		'nb' => ['code' => 'nb', 'locale' => 'nb_NO', 'name' => 'Norwegian (Bokmål)', 'native_name' => 'Norsk bokmål', 'flag' => '🇳🇴', 'rtl' => false, 'category' => 'european'],
		'nn' => ['code' => 'nn', 'locale' => 'nn_NO', 'name' => 'Norwegian (Nynorsk)', 'native_name' => 'Norsk nynorsk', 'flag' => '🇳🇴', 'rtl' => false, 'category' => 'european'],
		'fi' => ['code' => 'fi', 'locale' => 'fi', 'name' => 'Finnish', 'native_name' => 'Suomi', 'flag' => '🇫🇮', 'rtl' => false, 'category' => 'european'],
		'el' => ['code' => 'el', 'locale' => 'el', 'name' => 'Greek', 'native_name' => 'Ελληνικά', 'flag' => '🇬🇷', 'rtl' => false, 'category' => 'european'],
		'cs' => ['code' => 'cs', 'locale' => 'cs_CZ', 'name' => 'Czech', 'native_name' => 'Čeština', 'flag' => '🇨🇿', 'rtl' => false, 'category' => 'european'],
		'hu' => ['code' => 'hu', 'locale' => 'hu_HU', 'name' => 'Hungarian', 'native_name' => 'Magyar', 'flag' => '🇭🇺', 'rtl' => false, 'category' => 'european'],
		'ro' => ['code' => 'ro', 'locale' => 'ro_RO', 'name' => 'Romanian', 'native_name' => 'Română', 'flag' => '🇷🇴', 'rtl' => false, 'category' => 'european'],
		'bg' => ['code' => 'bg', 'locale' => 'bg_BG', 'name' => 'Bulgarian', 'native_name' => 'Български', 'flag' => '🇧🇬', 'rtl' => false, 'category' => 'european'],
		'uk' => ['code' => 'uk', 'locale' => 'uk', 'name' => 'Ukrainian', 'native_name' => 'Українська', 'flag' => '🇺🇦', 'rtl' => false, 'category' => 'european'],
		'sk' => ['code' => 'sk', 'locale' => 'sk_SK', 'name' => 'Slovak', 'native_name' => 'Slovenčina', 'flag' => '🇸🇰', 'rtl' => false, 'category' => 'european'],
		'hr' => ['code' => 'hr', 'locale' => 'hr', 'name' => 'Croatian', 'native_name' => 'Hrvatski', 'flag' => '🇭🇷', 'rtl' => false, 'category' => 'european'],
		'sr' => ['code' => 'sr', 'locale' => 'sr_RS', 'name' => 'Serbian', 'native_name' => 'Српски', 'flag' => '🇷🇸', 'rtl' => false, 'category' => 'european'],
		'sl' => ['code' => 'sl', 'locale' => 'sl_SI', 'name' => 'Slovenian', 'native_name' => 'Slovenščina', 'flag' => '🇸🇮', 'rtl' => false, 'category' => 'european'],
		'lt' => ['code' => 'lt', 'locale' => 'lt_LT', 'name' => 'Lithuanian', 'native_name' => 'Lietuvių kalba', 'flag' => '🇱🇹', 'rtl' => false, 'category' => 'european'],
		'lv' => ['code' => 'lv', 'locale' => 'lv', 'name' => 'Latvian', 'native_name' => 'Latviešu valoda', 'flag' => '🇱🇻', 'rtl' => false, 'category' => 'european'],
		'et' => ['code' => 'et', 'locale' => 'et', 'name' => 'Estonian', 'native_name' => 'Eesti', 'flag' => '🇪🇪', 'rtl' => false, 'category' => 'european'],
		'ca' => ['code' => 'ca', 'locale' => 'ca', 'name' => 'Catalan', 'native_name' => 'Català', 'flag' => '🏴󠁥󠁳󠁣󠁴󠁿', 'rtl' => false, 'category' => 'european'],
		'eu' => ['code' => 'eu', 'locale' => 'eu', 'name' => 'Basque', 'native_name' => 'Euskara', 'flag' => '🏴', 'rtl' => false, 'category' => 'european'],
		'gl' => ['code' => 'gl', 'locale' => 'gl_ES', 'name' => 'Galician', 'native_name' => 'Galego', 'flag' => '🏴', 'rtl' => false, 'category' => 'european'],
		'ga' => ['code' => 'ga', 'locale' => 'ga', 'name' => 'Irish', 'native_name' => 'Gaeilge', 'flag' => '🇮🇪', 'rtl' => false, 'category' => 'european'],
		'cy' => ['code' => 'cy', 'locale' => 'cy', 'name' => 'Welsh', 'native_name' => 'Cymraeg', 'flag' => '🏴󠁧󠁢󠁷󠁬󠁳󠁿', 'rtl' => false, 'category' => 'european'],
		'is' => ['code' => 'is', 'locale' => 'is_IS', 'name' => 'Icelandic', 'native_name' => 'Íslenska', 'flag' => '🇮🇸', 'rtl' => false, 'category' => 'european'],
		'sq' => ['code' => 'sq', 'locale' => 'sq', 'name' => 'Albanian', 'native_name' => 'Shqip', 'flag' => '🇦🇱', 'rtl' => false, 'category' => 'european'],
		'mk' => ['code' => 'mk', 'locale' => 'mk_MK', 'name' => 'Macedonian', 'native_name' => 'Македонски', 'flag' => '🇲🇰', 'rtl' => false, 'category' => 'european'],
		'bs' => ['code' => 'bs', 'locale' => 'bs_BA', 'name' => 'Bosnian', 'native_name' => 'Bosanski', 'flag' => '🇧🇦', 'rtl' => false, 'category' => 'european'],
		'be' => ['code' => 'be', 'locale' => 'bel', 'name' => 'Belarusian', 'native_name' => 'Беларуская', 'flag' => '🇧🇾', 'rtl' => false, 'category' => 'european'],
		'mt' => ['code' => 'mt', 'locale' => 'mt_MT', 'name' => 'Maltese', 'native_name' => 'Malti', 'flag' => '🇲🇹', 'rtl' => false, 'category' => 'european'],
		'lb' => ['code' => 'lb', 'locale' => 'lb_LU', 'name' => 'Luxembourgish', 'native_name' => 'Lëtzebuergesch', 'flag' => '🇱🇺', 'rtl' => false, 'category' => 'european'],
		'fr_BE' => ['code' => 'fr_BE', 'locale' => 'fr_BE', 'name' => 'French (Belgium)', 'native_name' => 'Français de Belgique', 'flag' => '🇧🇪', 'rtl' => false, 'category' => 'european'],
		'nl_BE' => ['code' => 'nl_BE', 'locale' => 'nl_BE', 'name' => 'Dutch (Belgium)', 'native_name' => 'Nederlands (België)', 'flag' => '🇧🇪', 'rtl' => false, 'category' => 'european'],
		'de_AT' => ['code' => 'de_AT', 'locale' => 'de_AT', 'name' => 'German (Austria)', 'native_name' => 'Deutsch (Österreich)', 'flag' => '🇦🇹', 'rtl' => false, 'category' => 'european'],
		'de_CH' => ['code' => 'de_CH', 'locale' => 'de_CH', 'name' => 'German (Switzerland)', 'native_name' => 'Deutsch (Schweiz)', 'flag' => '🇨🇭', 'rtl' => false, 'category' => 'european'],
		'fr_CH' => ['code' => 'fr_CH', 'locale' => 'fr_CH', 'name' => 'French (Switzerland)', 'native_name' => 'Français de Suisse', 'flag' => '🇨🇭', 'rtl' => false, 'category' => 'european'],
		'it_CH' => ['code' => 'it_CH', 'locale' => 'it_CH', 'name' => 'Italian (Switzerland)', 'native_name' => 'Italiano (Svizzera)', 'flag' => '🇨🇭', 'rtl' => false, 'category' => 'european'],

		// Asian Languages
		'zh_TW' => ['code' => 'zh_TW', 'locale' => 'zh_TW', 'name' => 'Chinese (Traditional)', 'native_name' => '繁體中文', 'flag' => '🇹🇼', 'rtl' => false, 'category' => 'asian'],
		'zh_HK' => ['code' => 'zh_HK', 'locale' => 'zh_HK', 'name' => 'Chinese (Hong Kong)', 'native_name' => '香港中文', 'flag' => '🇭🇰', 'rtl' => false, 'category' => 'asian'],
		'th' => ['code' => 'th', 'locale' => 'th', 'name' => 'Thai', 'native_name' => 'ไทย', 'flag' => '🇹🇭', 'rtl' => false, 'category' => 'asian'],
		'vi' => ['code' => 'vi', 'locale' => 'vi', 'name' => 'Vietnamese', 'native_name' => 'Tiếng Việt', 'flag' => '🇻🇳', 'rtl' => false, 'category' => 'asian'],
		'id' => ['code' => 'id', 'locale' => 'id_ID', 'name' => 'Indonesian', 'native_name' => 'Bahasa Indonesia', 'flag' => '🇮🇩', 'rtl' => false, 'category' => 'asian'],
		'ms' => ['code' => 'ms', 'locale' => 'ms_MY', 'name' => 'Malay', 'native_name' => 'Bahasa Melayu', 'flag' => '🇲🇾', 'rtl' => false, 'category' => 'asian'],
		'tl' => ['code' => 'tl', 'locale' => 'tl', 'name' => 'Filipino', 'native_name' => 'Filipino', 'flag' => '🇵🇭', 'rtl' => false, 'category' => 'asian'],
		'bn' => ['code' => 'bn', 'locale' => 'bn_BD', 'name' => 'Bengali', 'native_name' => 'বাংলা', 'flag' => '🇧🇩', 'rtl' => false, 'category' => 'asian'],
		'ta' => ['code' => 'ta', 'locale' => 'ta_IN', 'name' => 'Tamil', 'native_name' => 'தமிழ்', 'flag' => '🇮🇳', 'rtl' => false, 'category' => 'asian'],
		'te' => ['code' => 'te', 'locale' => 'te', 'name' => 'Telugu', 'native_name' => 'తెలుగు', 'flag' => '🇮🇳', 'rtl' => false, 'category' => 'asian'],
		'mr' => ['code' => 'mr', 'locale' => 'mr', 'name' => 'Marathi', 'native_name' => 'मराठी', 'flag' => '🇮🇳', 'rtl' => false, 'category' => 'asian'],
		'gu' => ['code' => 'gu', 'locale' => 'gu', 'name' => 'Gujarati', 'native_name' => 'ગુજરાતી', 'flag' => '🇮🇳', 'rtl' => false, 'category' => 'asian'],
		'kn' => ['code' => 'kn', 'locale' => 'kn', 'name' => 'Kannada', 'native_name' => 'ಕನ್ನಡ', 'flag' => '🇮🇳', 'rtl' => false, 'category' => 'asian'],
		'ml' => ['code' => 'ml', 'locale' => 'ml_IN', 'name' => 'Malayalam', 'native_name' => 'മലയാളം', 'flag' => '🇮🇳', 'rtl' => false, 'category' => 'asian'],
		'pa' => ['code' => 'pa', 'locale' => 'pa_IN', 'name' => 'Punjabi', 'native_name' => 'ਪੰਜਾਬੀ', 'flag' => '🇮🇳', 'rtl' => false, 'category' => 'asian'],
		'ne' => ['code' => 'ne', 'locale' => 'ne_NP', 'name' => 'Nepali', 'native_name' => 'नेपाली', 'flag' => '🇳🇵', 'rtl' => false, 'category' => 'asian'],
		'si' => ['code' => 'si', 'locale' => 'si_LK', 'name' => 'Sinhala', 'native_name' => 'සිංහල', 'flag' => '🇱🇰', 'rtl' => false, 'category' => 'asian'],
		'km' => ['code' => 'km', 'locale' => 'km', 'name' => 'Khmer', 'native_name' => 'ភាសាខ្មែរ', 'flag' => '🇰🇭', 'rtl' => false, 'category' => 'asian'],
		'my' => ['code' => 'my', 'locale' => 'my_MM', 'name' => 'Burmese', 'native_name' => 'ဗမာစာ', 'flag' => '🇲🇲', 'rtl' => false, 'category' => 'asian'],
		'lo' => ['code' => 'lo', 'locale' => 'lo', 'name' => 'Lao', 'native_name' => 'ພາສາລາວ', 'flag' => '🇱🇦', 'rtl' => false, 'category' => 'asian'],
		'mn' => ['code' => 'mn', 'locale' => 'mn', 'name' => 'Mongolian', 'native_name' => 'Монгол', 'flag' => '🇲🇳', 'rtl' => false, 'category' => 'asian'],
		'kk' => ['code' => 'kk', 'locale' => 'kk', 'name' => 'Kazakh', 'native_name' => 'Қазақ тілі', 'flag' => '🇰🇿', 'rtl' => false, 'category' => 'asian'],
		'uz' => ['code' => 'uz', 'locale' => 'uz_UZ', 'name' => 'Uzbek', 'native_name' => 'Oʻzbek', 'flag' => '🇺🇿', 'rtl' => false, 'category' => 'asian'],
		'tg' => ['code' => 'tg', 'locale' => 'tg', 'name' => 'Tajik', 'native_name' => 'Тоҷикӣ', 'flag' => '🇹🇯', 'rtl' => false, 'category' => 'asian'],
		'ky' => ['code' => 'ky', 'locale' => 'ky_KY', 'name' => 'Kyrgyz', 'native_name' => 'Кыргызча', 'flag' => '🇰🇬', 'rtl' => false, 'category' => 'asian'],
		'az' => ['code' => 'az', 'locale' => 'az', 'name' => 'Azerbaijani', 'native_name' => 'Azərbaycan dili', 'flag' => '🇦🇿', 'rtl' => false, 'category' => 'asian'],
		'hy' => ['code' => 'hy', 'locale' => 'hy', 'name' => 'Armenian', 'native_name' => 'Հայերեն', 'flag' => '🇦🇲', 'rtl' => false, 'category' => 'asian'],
		'ka' => ['code' => 'ka', 'locale' => 'ka_GE', 'name' => 'Georgian', 'native_name' => 'ქართული', 'flag' => '🇬🇪', 'rtl' => false, 'category' => 'asian'],

		// Middle Eastern Languages
		'he' => ['code' => 'he', 'locale' => 'he_IL', 'name' => 'Hebrew', 'native_name' => 'עברית', 'flag' => '🇮🇱', 'rtl' => true, 'category' => 'middle_east'],
		'fa' => ['code' => 'fa', 'locale' => 'fa_IR', 'name' => 'Persian', 'native_name' => 'فارسی', 'flag' => '🇮🇷', 'rtl' => true, 'category' => 'middle_east'],
		'ur' => ['code' => 'ur', 'locale' => 'ur', 'name' => 'Urdu', 'native_name' => 'اردو', 'flag' => '🇵🇰', 'rtl' => true, 'category' => 'middle_east'],
		'ps' => ['code' => 'ps', 'locale' => 'ps', 'name' => 'Pashto', 'native_name' => 'پښتو', 'flag' => '🇦🇫', 'rtl' => true, 'category' => 'middle_east'],
		'ckb' => ['code' => 'ckb', 'locale' => 'ckb', 'name' => 'Kurdish (Sorani)', 'native_name' => 'کوردی', 'flag' => '🏴', 'rtl' => true, 'category' => 'middle_east'],
		'ku' => ['code' => 'ku', 'locale' => 'ku', 'name' => 'Kurdish (Kurmanji)', 'native_name' => 'Kurdî', 'flag' => '🏴', 'rtl' => false, 'category' => 'middle_east'],
		'ar_SA' => ['code' => 'ar_SA', 'locale' => 'ar', 'name' => 'Arabic (Saudi)', 'native_name' => 'العربية السعودية', 'flag' => '🇸🇦', 'rtl' => true, 'category' => 'middle_east'],
		'ar_EG' => ['code' => 'ar_EG', 'locale' => 'ar', 'name' => 'Arabic (Egypt)', 'native_name' => 'العربية المصرية', 'flag' => '🇪🇬', 'rtl' => true, 'category' => 'middle_east'],
		'ar_MA' => ['code' => 'ar_MA', 'locale' => 'ar', 'name' => 'Arabic (Morocco)', 'native_name' => 'العربية المغربية', 'flag' => '🇲🇦', 'rtl' => true, 'category' => 'middle_east'],
		'ar_DZ' => ['code' => 'ar_DZ', 'locale' => 'ary', 'name' => 'Arabic (Algeria)', 'native_name' => 'الدارجة الجزائرية', 'flag' => '🇩🇿', 'rtl' => true, 'category' => 'middle_east'],

		// African Languages
		'sw' => ['code' => 'sw', 'locale' => 'sw', 'name' => 'Swahili', 'native_name' => 'Kiswahili', 'flag' => '🇰🇪', 'rtl' => false, 'category' => 'african'],
		'am' => ['code' => 'am', 'locale' => 'am', 'name' => 'Amharic', 'native_name' => 'አማርኛ', 'flag' => '🇪🇹', 'rtl' => false, 'category' => 'african'],
		'af' => ['code' => 'af', 'locale' => 'af', 'name' => 'Afrikaans', 'native_name' => 'Afrikaans', 'flag' => '🇿🇦', 'rtl' => false, 'category' => 'african'],
		'ha' => ['code' => 'ha', 'locale' => 'ha', 'name' => 'Hausa', 'native_name' => 'Hausa', 'flag' => '🇳🇬', 'rtl' => false, 'category' => 'african'],
		'yo' => ['code' => 'yo', 'locale' => 'yo', 'name' => 'Yoruba', 'native_name' => 'Yorùbá', 'flag' => '🇳🇬', 'rtl' => false, 'category' => 'african'],
		'zu' => ['code' => 'zu', 'locale' => 'zu', 'name' => 'Zulu', 'native_name' => 'isiZulu', 'flag' => '🇿🇦', 'rtl' => false, 'category' => 'african'],
		'ig' => ['code' => 'ig', 'locale' => 'ig', 'name' => 'Igbo', 'native_name' => 'Igbo', 'flag' => '🇳🇬', 'rtl' => false, 'category' => 'african'],
		'so' => ['code' => 'so', 'locale' => 'so', 'name' => 'Somali', 'native_name' => 'Soomaaliga', 'flag' => '🇸🇴', 'rtl' => false, 'category' => 'african'],
		'rw' => ['code' => 'rw', 'locale' => 'rw', 'name' => 'Kinyarwanda', 'native_name' => 'Ikinyarwanda', 'flag' => '🇷🇼', 'rtl' => false, 'category' => 'african'],

		// Americas Languages
		'en_CA' => ['code' => 'en_CA', 'locale' => 'en_CA', 'name' => 'English (Canada)', 'native_name' => 'English (Canada)', 'flag' => '🇨🇦', 'rtl' => false, 'category' => 'americas'],
		'en_AU' => ['code' => 'en_AU', 'locale' => 'en_AU', 'name' => 'English (Australia)', 'native_name' => 'English (Australia)', 'flag' => '🇦🇺', 'rtl' => false, 'category' => 'americas'],
		'en_NZ' => ['code' => 'en_NZ', 'locale' => 'en_NZ', 'name' => 'English (New Zealand)', 'native_name' => 'English (New Zealand)', 'flag' => '🇳🇿', 'rtl' => false, 'category' => 'americas'],
		'es_MX' => ['code' => 'es_MX', 'locale' => 'es_MX', 'name' => 'Spanish (Mexico)', 'native_name' => 'Español de México', 'flag' => '🇲🇽', 'rtl' => false, 'category' => 'americas'],
		'es_AR' => ['code' => 'es_AR', 'locale' => 'es_AR', 'name' => 'Spanish (Argentina)', 'native_name' => 'Español de Argentina', 'flag' => '🇦🇷', 'rtl' => false, 'category' => 'americas'],
		'es_CL' => ['code' => 'es_CL', 'locale' => 'es_CL', 'name' => 'Spanish (Chile)', 'native_name' => 'Español de Chile', 'flag' => '🇨🇱', 'rtl' => false, 'category' => 'americas'],
		'es_CO' => ['code' => 'es_CO', 'locale' => 'es_CO', 'name' => 'Spanish (Colombia)', 'native_name' => 'Español de Colombia', 'flag' => '🇨🇴', 'rtl' => false, 'category' => 'americas'],
		'es_PE' => ['code' => 'es_PE', 'locale' => 'es_PE', 'name' => 'Spanish (Peru)', 'native_name' => 'Español de Perú', 'flag' => '🇵🇪', 'rtl' => false, 'category' => 'americas'],
		'es_VE' => ['code' => 'es_VE', 'locale' => 'es_VE', 'name' => 'Spanish (Venezuela)', 'native_name' => 'Español de Venezuela', 'flag' => '🇻🇪', 'rtl' => false, 'category' => 'americas'],
		'fr_CA' => ['code' => 'fr_CA', 'locale' => 'fr_CA', 'name' => 'French (Canada)', 'native_name' => 'Français du Canada', 'flag' => '🇨🇦', 'rtl' => false, 'category' => 'americas'],
	];

	/**
	 * Category labels for display
	 */
	private const CATEGORY_LABELS = [
		'popular' => 'Popular',
		'european' => 'European',
		'asian' => 'Asian',
		'middle_east' => 'Middle East',
		'african' => 'African',
		'americas' => 'Americas',
	];

	public function render(): void {
		if ( ! current_user_can( 'i18n_translate_manage' ) ) {
			wp_die( esc_html__( 'You do not have permission to access this page.', 'i18n-translate' ) );
		}

		$languages = $this->get_languages_all();
		$languages_json = wp_json_encode( array_values( $languages ) );
		$presets_json = wp_json_encode( array_values( self::PRESETS ) );
		$categories_json = wp_json_encode( self::CATEGORY_LABELS );
		?>
		<script>
			window.i18nLanguagesData = <?php echo $languages_json; ?>;
			window.i18nPresetsData = <?php echo $presets_json; ?>;
			window.i18nCategoriesData = <?php echo $categories_json; ?>;
		</script>
		<div class="wrap i18n-admin i18n-languages-page" x-data="languagesApp()">
			
			<!-- Hero Section -->
			<div class="i18n-page-hero">
				<div class="i18n-hero-content">
					<div class="i18n-hero-icon">
						<span class="dashicons dashicons-translation"></span>
					</div>
					<div class="i18n-hero-text">
						<h1><?php esc_html_e( 'Languages', 'i18n-translate' ); ?></h1>
						<p><?php esc_html_e( 'Manage the languages available on your multilingual website', 'i18n-translate' ); ?></p>
					</div>
				</div>
				<button type="button" class="i18n-btn i18n-btn-primary i18n-btn-lg" @click="openModal()">
					<span class="dashicons dashicons-plus-alt2"></span>
					<?php esc_html_e( 'Add Custom Language', 'i18n-translate' ); ?>
				</button>
			</div>

			<!-- Stats Bar -->
			<div class="i18n-stats-bar">
				<div class="i18n-stat-item">
					<span class="i18n-stat-value" x-text="languages.length">0</span>
					<span class="i18n-stat-label"><?php esc_html_e( 'Total Languages', 'i18n-translate' ); ?></span>
				</div>
				<div class="i18n-stat-item i18n-stat-success">
					<span class="i18n-stat-value" x-text="languages.filter(l => l.enabled).length">0</span>
					<span class="i18n-stat-label"><?php esc_html_e( 'Active', 'i18n-translate' ); ?></span>
				</div>
				<div class="i18n-stat-item i18n-stat-muted">
					<span class="i18n-stat-value" x-text="languages.filter(l => !l.enabled).length">0</span>
					<span class="i18n-stat-label"><?php esc_html_e( 'Disabled', 'i18n-translate' ); ?></span>
				</div>
				<div class="i18n-stat-item i18n-stat-info">
					<span class="i18n-stat-value" x-text="languages.filter(l => l.rtl).length">0</span>
					<span class="i18n-stat-label"><?php esc_html_e( 'RTL', 'i18n-translate' ); ?></span>
				</div>
			</div>

			<!-- Success/Error Messages -->
			<div x-show="message" x-transition class="i18n-notice" :class="messageType === 'success' ? 'i18n-notice-success' : 'i18n-notice-error'">
				<span x-text="message"></span>
				<button type="button" @click="message = ''">&times;</button>
			</div>

			<!-- Your Languages Section -->
			<div class="i18n-section-header" x-show="languages.length > 0">
				<h2>
					<span class="dashicons dashicons-flag"></span>
					<?php esc_html_e( 'Your Languages', 'i18n-translate' ); ?>
				</h2>
			</div>

			<!-- Languages Grid -->
			<div class="i18n-lang-grid" x-show="languages.length > 0">
				<template x-for="lang in languages" :key="lang.code">
					<div class="i18n-lang-card" :class="{ 'i18n-lang-card-disabled': !lang.enabled, 'i18n-lang-card-rtl': lang.rtl }">
						<div class="i18n-lang-card-glow"></div>
						<div class="i18n-lang-card-inner">
							<div class="i18n-lang-card-header">
								<div class="i18n-lang-flag-wrap">
									<span class="i18n-lang-flag" x-text="lang.flag || '🌐'"></span>
								</div>
								<div class="i18n-lang-badges">
									<span x-show="lang.rtl" class="i18n-badge i18n-badge-rtl">RTL</span>
									<span class="i18n-badge" :class="lang.enabled ? 'i18n-badge-enabled' : 'i18n-badge-disabled'" x-text="lang.enabled ? '<?php esc_attr_e( 'Active', 'i18n-translate' ); ?>' : '<?php esc_attr_e( 'Disabled', 'i18n-translate' ); ?>'"></span>
								</div>
							</div>
							<div class="i18n-lang-card-body">
								<h3 class="i18n-lang-name" x-text="lang.name"></h3>
								<p class="i18n-lang-native" x-text="lang.native_name"></p>
								<div class="i18n-lang-codes">
									<span class="i18n-lang-code">
										<span class="i18n-code-label"><?php esc_html_e( 'Code', 'i18n-translate' ); ?></span>
										<code x-text="lang.code"></code>
									</span>
									<span class="i18n-lang-code">
										<span class="i18n-code-label"><?php esc_html_e( 'Locale', 'i18n-translate' ); ?></span>
										<code x-text="lang.locale"></code>
									</span>
								</div>
							</div>
							<div class="i18n-lang-card-footer">
								<label class="i18n-switch">
									<input type="checkbox" :checked="lang.enabled" @change="toggleLanguage(lang.code, $event.target.checked)">
									<span class="i18n-switch-slider"></span>
								</label>
								<div class="i18n-lang-actions">
									<button type="button" class="i18n-action-btn i18n-action-edit" @click="editLanguage(lang)" :title="'<?php esc_attr_e( 'Edit', 'i18n-translate' ); ?>'">
										<span class="dashicons dashicons-edit"></span>
									</button>
									<button type="button" class="i18n-action-btn i18n-action-delete" @click="confirmDelete(lang)" :title="'<?php esc_attr_e( 'Delete', 'i18n-translate' ); ?>'">
										<span class="dashicons dashicons-trash"></span>
									</button>
								</div>
							</div>
						</div>
					</div>
				</template>
			</div>

			<!-- Empty State -->
			<div x-show="languages.length === 0" class="i18n-empty-hero">
				<div class="i18n-empty-icon-wrap">
					<span class="dashicons dashicons-translation"></span>
				</div>
				<h2><?php esc_html_e( 'No Languages Configured', 'i18n-translate' ); ?></h2>
				<p><?php esc_html_e( 'Get started by adding languages from the presets below, or create a custom language.', 'i18n-translate' ); ?></p>
			</div>

			<!-- Add Languages Section -->
			<div class="i18n-add-section">
				<div class="i18n-add-header">
					<div class="i18n-add-title">
						<span class="dashicons dashicons-plus-alt"></span>
						<h2><?php esc_html_e( 'Add Languages', 'i18n-translate' ); ?></h2>
					</div>
					<p><?php esc_html_e( 'Choose from over 100 WordPress-supported languages', 'i18n-translate' ); ?></p>
				</div>

				<!-- Search Box -->
				<div class="i18n-preset-search">
					<span class="dashicons dashicons-search"></span>
					<input 
						type="text" 
						x-model="presetSearch" 
						placeholder="<?php esc_attr_e( 'Search languages...', 'i18n-translate' ); ?>"
						@input="filterPresets()"
					>
					<button type="button" x-show="presetSearch" @click="presetSearch = ''; filterPresets()" class="i18n-search-clear">&times;</button>
				</div>

				<!-- Category Tabs -->
				<div class="i18n-category-tabs">
					<button 
						type="button" 
						class="i18n-category-tab"
						:class="{ 'i18n-category-tab-active': activeCategory === 'all' }"
						@click="setCategory('all')"
					>
						<span class="dashicons dashicons-admin-site-alt3"></span>
						<?php esc_html_e( 'All', 'i18n-translate' ); ?>
						<span class="i18n-tab-count" x-text="presets.length"></span>
					</button>
					<template x-for="cat in categoryList" :key="cat.key">
						<button 
							type="button" 
							class="i18n-category-tab"
							:class="{ 'i18n-category-tab-active': activeCategory === cat.key }"
							@click="setCategory(cat.key)"
						>
							<span x-text="cat.label"></span>
							<span class="i18n-tab-count" x-text="presets.filter(p => p.category === cat.key).length"></span>
						</button>
					</template>
				</div>

				<!-- Presets Grid -->
				<div class="i18n-presets-grid">
					<template x-for="preset in filteredPresets" :key="preset.code">
						<button 
							type="button" 
							class="i18n-preset-item"
							:class="{ 
								'i18n-preset-added': isAdded(preset.code),
								'i18n-preset-rtl': preset.rtl 
							}"
							@click="addPreset(preset)"
							:disabled="isAdded(preset.code)"
						>
							<span class="i18n-preset-flag" x-text="preset.flag"></span>
							<span class="i18n-preset-info">
								<span class="i18n-preset-name" x-text="preset.name"></span>
								<span class="i18n-preset-native" x-text="preset.native_name"></span>
							</span>
							<span class="i18n-preset-status">
								<span x-show="isAdded(preset.code)" class="i18n-preset-check">
									<span class="dashicons dashicons-yes-alt"></span>
								</span>
								<span x-show="!isAdded(preset.code)" class="i18n-preset-add">
									<span class="dashicons dashicons-plus"></span>
								</span>
							</span>
						</button>
					</template>
				</div>

				<!-- No Results -->
				<div x-show="filteredPresets.length === 0" class="i18n-no-results">
					<span class="dashicons dashicons-info-outline"></span>
					<p><?php esc_html_e( 'No languages match your search.', 'i18n-translate' ); ?></p>
				</div>
			</div>

			<!-- Add/Edit Modal -->
			<div class="i18n-modal-overlay" x-show="showModal" x-cloak x-transition.opacity @click.self="closeModal()">
				<div class="i18n-modal i18n-modal-modern" @click.stop>
					<div class="i18n-modal-header">
						<h2 x-text="editingLang ? '<?php esc_attr_e( 'Edit Language', 'i18n-translate' ); ?>' : '<?php esc_attr_e( 'Add Custom Language', 'i18n-translate' ); ?>'"></h2>
						<button type="button" class="i18n-modal-close" @click="closeModal()">&times;</button>
					</div>
					<form @submit.prevent="saveLanguage()">
						<div class="i18n-modal-body">
							<div class="i18n-form-row">
								<div class="i18n-form-group">
									<label for="lang-code"><?php esc_html_e( 'Code', 'i18n-translate' ); ?> <span class="required">*</span></label>
									<input type="text" id="lang-code" x-model="form.code" placeholder="en" required :readonly="editingLang !== null">
									<p class="i18n-form-hint"><?php esc_html_e( 'Short code (e.g., en, fr, ar)', 'i18n-translate' ); ?></p>
								</div>
								<div class="i18n-form-group">
									<label for="lang-locale"><?php esc_html_e( 'Locale', 'i18n-translate' ); ?> <span class="required">*</span></label>
									<input type="text" id="lang-locale" x-model="form.locale" placeholder="en_US" required>
									<p class="i18n-form-hint"><?php esc_html_e( 'WordPress locale (e.g., en_US, fr_FR)', 'i18n-translate' ); ?></p>
								</div>
							</div>
							<div class="i18n-form-row">
								<div class="i18n-form-group">
									<label for="lang-name"><?php esc_html_e( 'Name', 'i18n-translate' ); ?> <span class="required">*</span></label>
									<input type="text" id="lang-name" x-model="form.name" placeholder="English" required>
								</div>
								<div class="i18n-form-group">
									<label for="lang-native"><?php esc_html_e( 'Native Name', 'i18n-translate' ); ?> <span class="required">*</span></label>
									<input type="text" id="lang-native" x-model="form.native_name" placeholder="English" required>
								</div>
							</div>
							<div class="i18n-form-row">
								<div class="i18n-form-group">
									<label for="lang-flag"><?php esc_html_e( 'Flag Emoji', 'i18n-translate' ); ?></label>
									<input type="text" id="lang-flag" x-model="form.flag" placeholder="🇺🇸">
								</div>
								<div class="i18n-form-group">
									<label for="lang-order"><?php esc_html_e( 'Sort Order', 'i18n-translate' ); ?></label>
									<input type="number" id="lang-order" x-model="form.sort_order" placeholder="10">
								</div>
							</div>
							<div class="i18n-form-row i18n-form-toggles">
								<label class="i18n-toggle-label">
									<input type="checkbox" x-model="form.rtl">
									<span class="i18n-toggle-text">
										<strong><?php esc_html_e( 'RTL Language', 'i18n-translate' ); ?></strong>
										<small><?php esc_html_e( 'Right-to-Left text direction', 'i18n-translate' ); ?></small>
									</span>
								</label>
								<label class="i18n-toggle-label">
									<input type="checkbox" x-model="form.enabled">
									<span class="i18n-toggle-text">
										<strong><?php esc_html_e( 'Enable Language', 'i18n-translate' ); ?></strong>
										<small><?php esc_html_e( 'Make available on frontend', 'i18n-translate' ); ?></small>
									</span>
								</label>
							</div>
						</div>
						<div class="i18n-modal-footer">
							<button type="button" class="i18n-btn i18n-btn-secondary" @click="closeModal()"><?php esc_html_e( 'Cancel', 'i18n-translate' ); ?></button>
							<button type="submit" class="i18n-btn i18n-btn-primary" :disabled="saving">
								<span x-show="saving" class="i18n-spinner"></span>
								<span x-text="saving ? '<?php esc_attr_e( 'Saving...', 'i18n-translate' ); ?>' : '<?php esc_attr_e( 'Save Language', 'i18n-translate' ); ?>'"></span>
							</button>
						</div>
					</form>
				</div>
			</div>

			<!-- Delete Confirmation Modal -->
			<div class="i18n-modal-overlay" x-show="showDeleteModal" x-cloak x-transition.opacity @click.self="showDeleteModal = false">
				<div class="i18n-modal i18n-modal-sm i18n-modal-danger" @click.stop>
					<div class="i18n-modal-header i18n-modal-header-danger">
						<h2><?php esc_html_e( 'Delete Language', 'i18n-translate' ); ?></h2>
						<button type="button" class="i18n-modal-close" @click="showDeleteModal = false">&times;</button>
					</div>
					<div class="i18n-modal-body">
						<div class="i18n-delete-warning">
							<span class="dashicons dashicons-warning"></span>
						</div>
						<p><?php esc_html_e( 'Are you sure you want to delete this language?', 'i18n-translate' ); ?></p>
						<p class="i18n-delete-name"><strong x-text="deletingLang?.name"></strong></p>
						<p class="i18n-text-muted"><?php esc_html_e( 'This will also delete all translations for this language. This action cannot be undone.', 'i18n-translate' ); ?></p>
					</div>
					<div class="i18n-modal-footer">
						<button type="button" class="i18n-btn i18n-btn-secondary" @click="showDeleteModal = false"><?php esc_html_e( 'Cancel', 'i18n-translate' ); ?></button>
						<button type="button" class="i18n-btn i18n-btn-danger" @click="deleteLanguage()" :disabled="deleting">
							<span x-show="deleting" class="i18n-spinner"></span>
							<span x-text="deleting ? '<?php esc_attr_e( 'Deleting...', 'i18n-translate' ); ?>' : '<?php esc_attr_e( 'Delete', 'i18n-translate' ); ?>'"></span>
						</button>
					</div>
				</div>
			</div>
		</div>

		<script>
		function languagesApp() {
			return {
				languages: window.i18nLanguagesData || [],
				presets: window.i18nPresetsData || [],
				categoryList: Object.entries(window.i18nCategoriesData || {}).map(([key, label]) => ({ key, label })),
				activeCategory: 'all',
				presetSearch: '',
				filteredPresets: [],
				showModal: false,
				showDeleteModal: false,
				editingLang: null,
				deletingLang: null,
				saving: false,
				deleting: false,
				message: '',
				messageType: 'success',
				form: {
					code: '',
					locale: '',
					name: '',
					native_name: '',
					flag: '',
					rtl: false,
					enabled: true,
					sort_order: 999
				},

				init() {
					console.log('Languages App Init', { presets: this.presets.length, categories: this.categoryList });
					this.filterPresets();
				},

				filterPresets() {
					let result = this.presets;
					
					// Filter by category
					if (this.activeCategory !== 'all') {
						result = result.filter(p => p.category === this.activeCategory);
					}
					
					// Filter by search
					if (this.presetSearch.trim()) {
						const search = this.presetSearch.toLowerCase().trim();
						result = result.filter(p => 
							p.name.toLowerCase().includes(search) ||
							p.native_name.toLowerCase().includes(search) ||
							p.code.toLowerCase().includes(search) ||
							p.locale.toLowerCase().includes(search)
						);
					}
					
					this.filteredPresets = result;
				},

				setCategory(category) {
					this.activeCategory = category;
					this.filterPresets();
				},

				isAdded(code) {
					return this.languages.some(l => l.code === code);
				},

				async addPreset(preset) {
					if (this.isAdded(preset.code)) return;
					
					const formData = {
						...preset,
						enabled: 1,
						rtl: preset.rtl ? 1 : 0,
						sort_order: this.languages.length + 1
					};

					try {
						const response = await fetch(i18nTranslate.ajaxUrl, {
							method: 'POST',
							headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
							body: new URLSearchParams({
								action: 'i18n_save_language',
								nonce: i18nTranslate.nonce,
								...formData
							})
						});
						const data = await response.json();
						if (data.success) {
							this.languages.push({ ...preset, enabled: true, sort_order: formData.sort_order });
							this.showMessage(preset.name + ' <?php echo esc_js( __( 'added!', 'i18n-translate' ) ); ?>', 'success');
						} else {
							this.showMessage(data.data?.message || '<?php echo esc_js( __( 'Error adding language', 'i18n-translate' ) ); ?>', 'error');
						}
					} catch (e) {
						this.showMessage('<?php echo esc_js( __( 'Network error', 'i18n-translate' ) ); ?>', 'error');
					}
				},

				openModal() {
					this.editingLang = null;
					this.form = {
						code: '',
						locale: '',
						name: '',
						native_name: '',
						flag: '',
						rtl: false,
						enabled: true,
						sort_order: 999
					};
					this.showModal = true;
				},

				editLanguage(lang) {
					this.editingLang = lang;
					this.form = {
						code: lang.code,
						locale: lang.locale,
						name: lang.name,
						native_name: lang.native_name,
						flag: lang.flag || '',
						rtl: !!lang.rtl,
						enabled: !!lang.enabled,
						sort_order: lang.sort_order || 999
					};
					this.showModal = true;
				},

				closeModal() {
					this.showModal = false;
					this.editingLang = null;
				},

				confirmDelete(lang) {
					this.deletingLang = lang;
					this.showDeleteModal = true;
				},

				async toggleLanguage(code, enabled) {
					try {
						const response = await fetch(i18nTranslate.ajaxUrl, {
							method: 'POST',
							headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
							body: new URLSearchParams({
								action: 'i18n_toggle_language',
								nonce: i18nTranslate.nonce,
								code: code,
								enabled: enabled ? 1 : 0
							})
						});
						const data = await response.json();
						if (data.success) {
							const lang = this.languages.find(l => l.code === code);
							if (lang) lang.enabled = enabled;
							this.showMessage('<?php echo esc_js( __( 'Language updated', 'i18n-translate' ) ); ?>', 'success');
						} else {
							this.showMessage(data.data?.message || '<?php echo esc_js( __( 'Error updating language', 'i18n-translate' ) ); ?>', 'error');
						}
					} catch (e) {
						this.showMessage('<?php echo esc_js( __( 'Network error', 'i18n-translate' ) ); ?>', 'error');
					}
				},

				async saveLanguage() {
					this.saving = true;
					try {
						const response = await fetch(i18nTranslate.ajaxUrl, {
							method: 'POST',
							headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
							body: new URLSearchParams({
								action: 'i18n_save_language',
								nonce: i18nTranslate.nonce,
								...this.form,
								rtl: this.form.rtl ? 1 : 0,
								enabled: this.form.enabled ? 1 : 0
							})
						});
						const data = await response.json();
						if (data.success) {
							if (this.editingLang) {
								const idx = this.languages.findIndex(l => l.code === this.form.code);
								if (idx !== -1) {
									this.languages[idx] = { ...this.form };
								}
							} else {
								this.languages.push({ ...this.form });
							}
							this.languages.sort((a, b) => (a.sort_order || 999) - (b.sort_order || 999));
							this.closeModal();
							this.showMessage('<?php echo esc_js( __( 'Language saved', 'i18n-translate' ) ); ?>', 'success');
						} else {
							this.showMessage(data.data?.message || '<?php echo esc_js( __( 'Error saving language', 'i18n-translate' ) ); ?>', 'error');
						}
					} catch (e) {
						this.showMessage('<?php echo esc_js( __( 'Network error', 'i18n-translate' ) ); ?>', 'error');
					}
					this.saving = false;
				},

				async deleteLanguage() {
					if (!this.deletingLang) return;
					this.deleting = true;
					try {
						const response = await fetch(i18nTranslate.ajaxUrl, {
							method: 'POST',
							headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
							body: new URLSearchParams({
								action: 'i18n_delete_language',
								nonce: i18nTranslate.nonce,
								code: this.deletingLang.code
							})
						});
						const data = await response.json();
						if (data.success) {
							this.languages = this.languages.filter(l => l.code !== this.deletingLang.code);
							this.showDeleteModal = false;
							this.deletingLang = null;
							this.showMessage('<?php echo esc_js( __( 'Language deleted', 'i18n-translate' ) ); ?>', 'success');
						} else {
							this.showMessage(data.data?.message || '<?php echo esc_js( __( 'Error deleting language', 'i18n-translate' ) ); ?>', 'error');
						}
					} catch (e) {
						this.showMessage('<?php echo esc_js( __( 'Network error', 'i18n-translate' ) ); ?>', 'error');
					}
					this.deleting = false;
				},

				showMessage(msg, type) {
					this.message = msg;
					this.messageType = type;
					setTimeout(() => { this.message = ''; }, 4000);
				}
			};
		}
		</script>
		<?php
	}

	private function get_languages_all(): array {
		global $wpdb;
		$table = $wpdb->prefix . 'i18n_languages';

		$rows = $wpdb->get_results( "SELECT code, locale, name, native_name, rtl, flag, enabled, sort_order FROM {$table} ORDER BY sort_order ASC, code ASC", ARRAY_A );
		$out  = [];
		foreach ( (array) $rows as $row ) {
			$code = isset( $row['code'] ) ? (string) $row['code'] : '';
			if ( $code === '' ) {
				continue;
			}
			$row['rtl']     = (bool) $row['rtl'];
			$row['enabled'] = (bool) $row['enabled'];
			$row['sort_order'] = (int) $row['sort_order'];
			$out[ $code ] = $row;
		}
		return $out;
	}
}
