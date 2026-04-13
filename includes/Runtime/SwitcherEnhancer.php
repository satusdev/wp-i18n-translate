<?php

namespace I18nTranslate\Runtime;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * SwitcherEnhancer — يُضيف CSS/JS محسّن لمبدّل اللغة.
 *
 * مستقل تماماً: لا يُعدّل Render.php ولا أي كود ترجمة.
 * يُحمَّل بعد public.css الأصلي ويضيف فقط:
 *   • switcher.css  — تصميم حديث + RTL + dark mode
 *   • switcher.js   — dropdown بلوحة المفاتيح + auto-align
 *
 * @since 1.1.0
 */
final class SwitcherEnhancer {

    public function register(): void {
        add_action( 'wp_enqueue_scripts',    [ $this, 'enqueue' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin' ] );
    }

    /** تحميل الأصول في الـ frontend */
    public function enqueue(): void {
        wp_enqueue_style(
            'i18n-switcher-enhanced',
            I18N_TRANSLATE_URL . 'assets/switcher.css',
            [ 'i18n-translate-public' ],   // بعد الـ CSS الأصلي
            I18N_TRANSLATE_VERSION
        );

        wp_enqueue_script(
            'i18n-switcher-enhanced',
            I18N_TRANSLATE_URL . 'assets/switcher.js',
            [],
            I18N_TRANSLATE_VERSION,
            true   // في footer
        );
    }

    /** تحميل في صفحات الـ admin حيث يظهر المبدّل (Customizer مثلاً) */
    public function enqueue_admin(): void {
        $screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

        // تحميل في Customizer أو أي صفحة تحتوي على المبدّل.
        if ( $screen && in_array( $screen->id, [ 'customize', 'widgets', 'nav-menus' ], true ) ) {
            $this->enqueue();
        }
    }
}
