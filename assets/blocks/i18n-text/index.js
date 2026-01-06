( function( blocks, element, blockEditor, components ) {
	const { registerBlockType } = blocks;
	const { createElement: el, Fragment } = element;
	const { InspectorControls, useBlockProps } = blockEditor;
	const { PanelBody, TextControl, SelectControl } = components;

	registerBlockType( 'i18n-translate/text', {
		edit: function( props ) {
			const { attributes, setAttributes } = props;
			const { translationKey, fallback, tagName } = attributes;
			const blockProps = useBlockProps();

			return el( Fragment, {},
				el( InspectorControls, {},
					el( PanelBody, { title: 'Translation Settings', initialOpen: true },
						el( TextControl, {
							label: 'Translation Key',
							help: 'Enter the key from your Translations page (e.g., home.title)',
							value: translationKey,
							onChange: function( val ) { setAttributes( { translationKey: val } ); }
						}),
						el( TextControl, {
							label: 'Fallback Text',
							help: 'Shown if translation is not found',
							value: fallback,
							onChange: function( val ) { setAttributes( { fallback: val } ); }
						}),
						el( SelectControl, {
							label: 'HTML Tag',
							value: tagName,
							options: [
								{ label: 'Span', value: 'span' },
								{ label: 'Paragraph', value: 'p' },
								{ label: 'Heading 1', value: 'h1' },
								{ label: 'Heading 2', value: 'h2' },
								{ label: 'Heading 3', value: 'h3' },
								{ label: 'Div', value: 'div' }
							],
							onChange: function( val ) { setAttributes( { tagName: val } ); }
						})
					)
				),
				el( tagName || 'span', blockProps,
					translationKey ? '[' + translationKey + ']' : 'Enter translation key in sidebar →'
				)
			);
		},
		save: function() {
			return null; // Server-side render
		}
	});
} )( window.wp.blocks, window.wp.element, window.wp.blockEditor, window.wp.components );
