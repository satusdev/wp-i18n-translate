<?php

namespace I18nTranslate\Runtime;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class LanguageSwitcherWidget extends \WP_Widget {
	public function __construct() {
		parent::__construct(
			'i18n_translate_language_switcher',
			__( 'Language Switcher (i18n)', 'i18n-translate' ),
			[ 'description' => __( 'Outputs the i18n language switcher dropdown.', 'i18n-translate' ) ]
		);
	}

	public function widget( $args, $instance ): void {
		echo $args['before_widget'] ?? '';

		$title = '';
		if ( isset( $instance['title'] ) ) {
			$title = sanitize_text_field( (string) $instance['title'] );
		}

		if ( $title !== '' ) {
			echo ( $args['before_title'] ?? '' ) . esc_html( $title ) . ( $args['after_title'] ?? '' );
		}

		if ( function_exists( 'i18n_translate' ) ) {
			echo i18n_translate()->render()->language_switcher( 'dropdown', true, true );
		}

		echo $args['after_widget'] ?? '';
	}

	public function form( $instance ): void {
		$title = '';
		if ( is_array( $instance ) && isset( $instance['title'] ) ) {
			$title = sanitize_text_field( (string) $instance['title'] );
		}

		$field_id   = $this->get_field_id( 'title' );
		$field_name = $this->get_field_name( 'title' );
		?>
		<p>
			<label for="<?php echo esc_attr( $field_id ); ?>"><?php esc_html_e( 'Title:', 'i18n-translate' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $field_id ); ?>" name="<?php echo esc_attr( $field_name ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ): array {
		$instance = is_array( $old_instance ) ? $old_instance : [];
		$instance['title'] = isset( $new_instance['title'] ) ? sanitize_text_field( (string) $new_instance['title'] ) : '';
		return $instance;
	}
}
