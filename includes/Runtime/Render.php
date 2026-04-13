<?php

namespace I18nTranslate\Runtime;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Render {

	public function language_switcher( string $style = 'dropdown', bool $show_flags = true, bool $show_names = true, string $extra_class = '' ): string {
		$args = apply_filters( 'json_i18n_language_switcher_args', [
			'style'      => $style,
			'show_flags' => $show_flags,
			'show_names' => $show_names,
		] );

		$style       = (string) ( $args['style'] ?? 'dropdown' );
		$show_flags  = (bool)   ( $args['show_flags'] ?? true );
		$show_names  = (bool)   ( $args['show_names'] ?? true );
		$extra_class = $extra_class ? ' ' . sanitize_html_class( $extra_class ) : '';

		$languages = json_i18n_get_available_languages();
		$current   = json_i18n_get_current_language();

		if ( empty( $languages ) ) {
			return '';
		}

		$current_url = $this->get_current_url_without_lang();

		$items = [];
		foreach ( $languages as $code => $lang ) {
			$url    = add_query_arg( 'i18n_lang', $code, $current_url );
			$name   = $lang['native_name'] ?? $lang['name'] ?? $code;
			$active = ( $code === $current );
			$items[] = [
				'code'   => $code,
				'name'   => $name,
				'url'    => $url,
				'active' => $active,
				'flag'   => $lang['flag'] ?? '',
			];
		}

		switch ( $style ) {
			case 'list':
				return $this->render_list_style( $items, $show_flags, $show_names, $extra_class );
			case 'inline':
				return $this->render_inline_style( $items, $show_flags, $show_names, $extra_class );
			case 'flags-only':
				return $this->render_flags_only( $items, $extra_class );
			case 'names-only':
				return $this->render_names_only( $items, $extra_class );
			case 'dropdown':
			default:
				return $this->render_dropdown_style( $items, $show_flags, $show_names, $extra_class );
		}
	}


	private function get_current_url_without_lang(): string {
		global $wp;
		return home_url( add_query_arg( [], $wp->request ) );
	}


	private function svg_chevron(): string {
		return '<svg class="i18n-chevron-icon" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"></polyline></svg>';
	}

	private function svg_check(): string {
		return '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"></polyline></svg>';
	}

	private function render_item_content( array $item, bool $show_flags, bool $show_names ): string {
		$html = '';
		if ( $show_flags && ! empty( $item['flag'] ) ) {
			$html .= '<span class="i18n-flag flag-' . esc_attr( $item['code'] ) . '">' . $item['flag'] . '</span>';
		}
		if ( $show_names ) {
			$html .= '<span class="i18n-lang-label">' . esc_html( $item['name'] ) . '</span>';
		}
		if ( ! $show_flags && ! $show_names ) {
			$html .= '<span class="i18n-lang-label">' . esc_html( strtoupper( $item['code'] ) ) . '</span>';
		}
		return $html;
	}


	private function render_dropdown_style( array $items, bool $show_flags, bool $show_names, string $extra_class ): string {
		$current = current( array_filter( $items, fn( $i ) => $i['active'] ) ) ?: $items[0];

		ob_start();
		?>
		<div class="i18n-language-switcher dropdown<?php echo $extra_class; ?>" data-i18n-switcher="dropdown">
			<button class="i18n-lang-trigger" type="button"
				aria-haspopup="listbox"
				aria-expanded="false">
				<?php if ( $show_flags && ! empty( $current['flag'] ) ) : ?>
					<span class="i18n-flag flag-<?php echo esc_attr( $current['code'] ); ?>"><?php echo $current['flag']; ?></span>
				<?php endif; ?>
				<?php if ( $show_names ) : ?>
					<span class="i18n-lang-name"><?php echo esc_html( $current['name'] ); ?></span>
				<?php elseif ( ! $show_flags ) : ?>
					<span class="i18n-lang-name"><?php echo esc_html( strtoupper( $current['code'] ) ); ?></span>
				<?php endif; ?>
				<?php echo $this->svg_chevron(); ?>
			</button>
			<ul class="i18n-lang-menu" role="listbox" aria-label="<?php esc_attr_e( 'Select language', 'i18n-translate' ); ?>">
				<?php foreach ( $items as $item ) : ?>
					<li role="option" aria-selected="<?php echo $item['active'] ? 'true' : 'false'; ?>" class="<?php echo $item['active'] ? 'active' : ''; ?>">
						<a href="<?php echo esc_url( $item['url'] ); ?>" lang="<?php echo esc_attr( $item['code'] ); ?>">
							<?php echo $this->render_item_content( $item, $show_flags, $show_names ); ?>
							<?php if ( $item['active'] ) : ?>
								<span class="i18n-active-check"><?php echo $this->svg_check(); ?></span>
							<?php endif; ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php
		return ob_get_clean();
	}


	private function render_list_style( array $items, bool $show_flags, bool $show_names, string $extra_class ): string {
		ob_start();
		?>
		<ul class="i18n-language-switcher list<?php echo $extra_class; ?>" role="list">
			<?php foreach ( $items as $item ) : ?>
				<li class="<?php echo $item['active'] ? 'active' : ''; ?>" role="listitem">
					<a href="<?php echo esc_url( $item['url'] ); ?>" lang="<?php echo esc_attr( $item['code'] ); ?>"
						<?php echo $item['active'] ? 'aria-current="true"' : ''; ?>>
						<?php echo $this->render_item_content( $item, $show_flags, $show_names ); ?>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php
		return ob_get_clean();
	}

	private function render_inline_style( array $items, bool $show_flags, bool $show_names, string $extra_class ): string {
		ob_start();
		?>
		<nav class="i18n-language-switcher inline<?php echo $extra_class; ?>" aria-label="<?php esc_attr_e( 'Language selector', 'i18n-translate' ); ?>">
			<?php foreach ( $items as $item ) : ?>
				<a href="<?php echo esc_url( $item['url'] ); ?>"
					class="i18n-inline-item<?php echo $item['active'] ? ' active' : ''; ?>"
					lang="<?php echo esc_attr( $item['code'] ); ?>"
					<?php echo $item['active'] ? 'aria-current="true"' : ''; ?>>
					<?php echo $this->render_item_content( $item, $show_flags, $show_names ); ?>
				</a>
			<?php endforeach; ?>
		</nav>
		<?php
		return ob_get_clean();
	}

	private function render_flags_only( array $items, string $extra_class ): string {
		ob_start();
		?>
		<nav class="i18n-language-switcher flags-only<?php echo $extra_class; ?>" aria-label="<?php esc_attr_e( 'Language selector', 'i18n-translate' ); ?>">
			<?php foreach ( $items as $item ) : ?>
				<a href="<?php echo esc_url( $item['url'] ); ?>"
					class="i18n-flag-link<?php echo $item['active'] ? ' active' : ''; ?>"
					title="<?php echo esc_attr( $item['name'] ); ?>"
					lang="<?php echo esc_attr( $item['code'] ); ?>"
					<?php echo $item['active'] ? 'aria-current="true"' : ''; ?>>
					<?php if ( ! empty( $item['flag'] ) ) : ?>
						<span class="i18n-flag flag-<?php echo esc_attr( $item['code'] ); ?>"><?php echo $item['flag']; ?></span>
					<?php else : ?>
						<span class="i18n-lang-code"><?php echo esc_html( strtoupper( $item['code'] ) ); ?></span>
					<?php endif; ?>
				</a>
			<?php endforeach; ?>
		</nav>
		<?php
		return ob_get_clean();
	}


	private function render_names_only( array $items, string $extra_class ): string {
		ob_start();
		?>
		<nav class="i18n-language-switcher names-only<?php echo $extra_class; ?>" aria-label="<?php esc_attr_e( 'Language selector', 'i18n-translate' ); ?>">
			<?php foreach ( $items as $item ) : ?>
				<a href="<?php echo esc_url( $item['url'] ); ?>"
					class="i18n-name-link<?php echo $item['active'] ? ' active' : ''; ?>"
					lang="<?php echo esc_attr( $item['code'] ); ?>"
					<?php echo $item['active'] ? 'aria-current="true"' : ''; ?>>
					<?php echo esc_html( $item['name'] ); ?>
				</a>
			<?php endforeach; ?>
		</nav>
		<?php
		return ob_get_clean();
	}
}