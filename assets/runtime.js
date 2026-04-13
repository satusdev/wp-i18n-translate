/* global wpTemplateI18n */
/* WP i18n Translate — Frontend Runtime */
(function () {
	'use strict';

	/* ── Translation helpers ───────────────────────── */
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

	/* ── Dropdown Language Switcher ────────────────── */

	/**
	 * Open a specific switcher panel.
	 * @param {Element} switcher
	 */
	function openSwitcher(switcher) {
		var trigger = switcher.querySelector('.i18n-lang-trigger');
		if (!trigger) return;
		switcher.classList.add('is-open');
		trigger.setAttribute('aria-expanded', 'true');
	}

	/**
	 * Close a specific switcher panel.
	 * @param {Element} switcher
	 */
	function closeSwitcher(switcher) {
		var trigger = switcher.querySelector('.i18n-lang-trigger');
		if (!trigger) return;
		switcher.classList.remove('is-open');
		trigger.setAttribute('aria-expanded', 'false');
	}

	/**
	 * Close all open switchers, optionally excluding one.
	 * @param {Element|null} except
	 */
	function closeAll(except) {
		document.querySelectorAll('[data-i18n-switcher="dropdown"].is-open').forEach(function (sw) {
			if (sw !== except) closeSwitcher(sw);
		});
	}

	/**
	 * Navigate menu items with ArrowUp / ArrowDown.
	 * @param {Element} menu
	 * @param {number}  dir  -1 = up, +1 = down
	 */
	function moveFocus(menu, dir) {
		var links = Array.from(menu.querySelectorAll('a'));
		if (!links.length) return;
		var current = menu.querySelector('a:focus');
		var idx = links.indexOf(current);
		var next = links[Math.max(0, Math.min(links.length - 1, idx + dir))];
		if (next) next.focus();
	}

	/* ── Event delegation ──────────────────────────── */

	// Click on trigger button → toggle
	document.addEventListener('click', function (e) {
		var trigger = e.target && e.target.closest && e.target.closest('.i18n-lang-trigger');
		if (!trigger) return;

		var switcher = trigger.closest('[data-i18n-switcher="dropdown"]');
		if (!switcher) return;

		var isOpen = switcher.classList.contains('is-open');
		closeAll(null);
		if (!isOpen) {
			openSwitcher(switcher);
			// Focus first item for keyboard users
			var firstLink = switcher.querySelector('.i18n-lang-menu a');
			if (firstLink) {
				// Small delay so the CSS transition doesn't block focus
				setTimeout(function () { firstLink.focus(); }, 20);
			}
		}
	});

	// Click outside → close all
	document.addEventListener('click', function (e) {
		if (!e.target) return;
		var inside = e.target.closest && e.target.closest('[data-i18n-switcher="dropdown"]');
		if (!inside) closeAll(null);
	});

	// Keyboard support inside the menu
	document.addEventListener('keydown', function (e) {
		var key = e.key;

		// Escape → close focused switcher
		if (key === 'Escape') {
			var open = document.querySelector('[data-i18n-switcher="dropdown"].is-open');
			if (!open) return;
			var trigger = open.querySelector('.i18n-lang-trigger');
			closeSwitcher(open);
			if (trigger) trigger.focus();
			return;
		}

		// Arrow keys inside menu
		if (key === 'ArrowDown' || key === 'ArrowUp') {
			var menu = e.target && e.target.closest && e.target.closest('.i18n-lang-menu');
			if (!menu) return;
			e.preventDefault();
			moveFocus(menu, key === 'ArrowDown' ? 1 : -1);
		}

		// Tab → close when leaving switcher
		if (key === 'Tab') {
			// Use setTimeout so focus has moved before we check
			setTimeout(function () {
				var focused = document.activeElement;
				document.querySelectorAll('[data-i18n-switcher="dropdown"].is-open').forEach(function (sw) {
					if (!sw.contains(focused)) closeSwitcher(sw);
				});
			}, 0);
		}
	});

	/* ── <select> fallback (legacy markup) ────────── */
	document.addEventListener('change', function (e) {
		var el = e && e.target;
		if (!el || !el.matches || !el.matches('select.language-switcher')) return;

		var code = String(el.value || '').trim();
		if (!code) return;
		if (code === (wpTemplateI18n.current_lang || 'en')) return;

		try {
			var url = new URL(window.location.href);
			url.searchParams.set('i18n_lang', code);
			url.searchParams.delete('lang');
			window.location.href = url.toString();
		} catch (_) {
			window.location.href =
				window.location.pathname + '?i18n_lang=' + encodeURIComponent(code);
		}
	});
})();

/* ── Translation helper with placeholder interpolation ── */
window.__t =
	window.__t ||
	function (key, domain, placeholders) {
		domain = domain || 'default';
		var text = window.__(key, domain);
		if (placeholders && typeof placeholders === 'object') {
			Object.keys(placeholders).forEach(function (placeholder) {
				var regex = new RegExp('\\{' + placeholder + '\\}', 'g');
				text = text.replace(regex, String(placeholders[placeholder]));
			});
		}
		return text;
	};
