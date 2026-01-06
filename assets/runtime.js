/* global wpTemplateI18n */
(function () {
	'use strict';

	if (typeof wpTemplateI18n === 'undefined') return;

	var translations = wpTemplateI18n.translations || {};

	window.__ =
		window.__ ||
		function (key, domain) {
			domain = domain || 'default';
			if (translations[domain] && translations[domain][key])
				return translations[domain][key];
			return key;
		};

	window.getCurrentLanguage =
		window.getCurrentLanguage ||
		function () {
			return wpTemplateI18n.current_lang || 'en';
		};

	// Handle dropdown language switcher(s)
	document.addEventListener('change', function (e) {
		var el = e && e.target;
		if (!el || !el.matches || !el.matches('select.language-switcher')) return;

		var code = String(el.value || '').trim();
		if (!code) return;
		if (code === (wpTemplateI18n.current_lang || 'en')) return;

		try {
			var url = new URL(window.location.href);
			url.searchParams.set('lang', code);
			window.location.href = url.toString();
		} catch (err) {
			// Fallback for older browsers
			window.location.href =
				window.location.pathname + '?lang=' + encodeURIComponent(code);
		}
	});
})();

// Simpler translation helper with placeholder interpolation
window.__t = window.__t || function(key, domain, placeholders) {
 = domain || 'default';
text = window.__(key, domain);
(placeholders && typeof placeholders === 'object') {
s(placeholders).forEach(function(placeholder) {
regex = new RegExp('\\{' + placeholder + '\\}', 'g');
= text.replace(regex, String(placeholders[placeholder]));
 text;
};
