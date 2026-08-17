var FusionPageBuilder = FusionPageBuilder || {};

( function() {


	jQuery( document ).ready( function() {

		// Woo Sorting Component View.
		FusionPageBuilder.fusion_woo_sorting = FusionPageBuilder.ElementView.extend( {

			/**
			 * Runs after view DOM is patched.
			 *
			 * @since 3.15.0
			 * @return {void}
			 */
			afterPatch: function() {
				const $orderButton  = this.$el.find( '.awb-order' );
				const $countWrapper = this.$el.find( '.awb-count-wrapper' );
				const $viewWrapper  = this.$el.find( '.awb-view-wrapper' );
				const elementType   = this.model.get( 'element_type' );
				const values        = jQuery.extend( true, {}, fusionAllElements[ elementType ].defaults, _.fusionCleanParameters( this.model.get( 'params' ) ) );

				// Order utton.
				if ( $orderButton.length ) {
					if ( 'yes' === values.display_order_button ) {
						$orderButton.show();
					} else {
						$orderButton.hide();
					}
				}

				// Button labels.
				if ( $countWrapper.length ) {
					if ( 'yes' === values.product_count_label ) {
						$countWrapper.children( 'span' ).show();
					} else {
						$countWrapper.children( 'span' ).hide();
					}
				}

				if ( $viewWrapper.length ) {
					if ( 'yes' === values.product_view_label ) {
						$viewWrapper.children( 'span' ).show();
					} else {
						$viewWrapper.children( 'span' ).hide();
					}
				}

				this.$el.find( '.select-arrow' ).each( function() {
					if ( 0 < jQuery( this ).prev().innerHeight() ) {
						jQuery( this ).css( {
							height: jQuery( this ).prev().innerHeight(),
							width: jQuery( this ).prev().innerHeight(),
							'line-height': jQuery( this ).prev().innerHeight() + 'px'
						} );
					}
				} );
			},

			/**
			 * Modify template attributes.
			 *
			 * @since 3.2
			 * @param {Object} atts - The attributes.
			 * @return {Object}
			 */
			filterTemplateAtts: function( atts ) {
				var attributes = {};

				// Validate values.
				this.validateValues( atts.values );
				this.values = atts.values;
				this.extras = atts.extras;

				// Any extras that need passed on.
				attributes.cid    = this.model.get( 'cid' );
				attributes.attr   = this.buildAttr( atts.values );
				attributes.output = this.buildOutput( atts );
				attributes.query_data = atts.query_data;
				attributes.values = atts.values;

				return attributes;
			},

			/**
			 * Modifies the values.
			 *
			 * @since  3.2
			 * @param  {Object} values - The values object.
			 * @return {void}
			 */
			validateValues: function( values ) {
				if ( 'undefined' !== typeof values.margin_top && '' !== values.margin_top ) {
					values.margin_top = _.fusionGetValueWithUnit( values.margin_top );
				}

				if ( 'undefined' !== typeof values.margin_right && '' !== values.margin_right ) {
					values.margin_right = _.fusionGetValueWithUnit( values.margin_right );
				}

				if ( 'undefined' !== typeof values.margin_bottom && '' !== values.margin_bottom ) {
					values.margin_bottom = _.fusionGetValueWithUnit( values.margin_bottom );
				}

				if ( 'undefined' !== typeof values.margin_left && '' !== values.margin_left ) {
					values.margin_left = _.fusionGetValueWithUnit( values.margin_left );
				}
			},

			/**
			 * Builds attributes.
			 *
			 * @since  3.2
			 * @param  {Object} values - The values object.
			 * @return {Object}
			 */
			buildAttr: function( values ) {
				var attr         = _.fusionVisibilityAtts( values.hide_on_mobile, {
						class: 'fusion-woo-sorting fusion-woo-sorting-' + this.model.get( 'cid' ),
						style: ''
					} );

				if ( values.elements.includes( 'count' ) && 'buttons' === values.product_count_layout ) {
					attr[ 'class' ] += ' awb-count-button-layout-' + values.product_count_button_layout;
				}

				if ( values.elements.includes( 'count' ) ) {
					attr[ 'class' ] += ' awb-view-button-layout-' + values.product_view_button_layout;
				}

				if ( '' !== values[ 'class' ] ) {
					attr[ 'class' ] += ' ' + values[ 'class' ];
				}

				if ( '' !== values.id ) {
					attr.id = values.id;
				}

				attr.style += this.getStyleVariables( values );

				attr = _.fusionAnimations( values, attr );

				return attr;
			},

			/**
			 * Builds output.
			 *
			 * @since  3.2
			 * @param  {Object} values - The values object.
			 * @return {String}
			 */
			buildOutput: function( atts ) {
				var output = '';

				if ( 'undefined' !== typeof atts.markup && 'undefined' !== typeof atts.markup.output && 'undefined' === typeof atts.query_data ) {
					output = jQuery( jQuery.parseHTML( atts.markup.output ) ).html();
					output = ( 'undefined' === typeof output ) ? atts.markup.output : output;
				} else if ( 'undefined' !== typeof atts.query_data && 'undefined' !== typeof atts.query_data.output ) {
					output = atts.query_data.output;
				}

				return output;
			},

			/**
			 * Gets style variables.
			 *
			 * @since 3.9
			 * @param  {Object} values - The values object.
			 * @return {String}
			 */
			getStyleVariables: function( values ) {
				var customVars = [];
				const cssVarsOptions = [
					'element_flex_direction',
					'element_flex_direction_medium',
					'element_flex_direction_small',
					'element_justify_content',
					'element_justify_content_medium',
					'element_justify_content_small',
					'element_align_items',
					'element_align_items_medium',
					'element_align_items_small',

					'dropdown_bg_color',
					'dropdown_hover_bg_color',
					'dropdown_text_color',
					'dropdown_text_hover_color',
					'dropdown_border_color',
					'dropdown_border_hover_color',

					'product_count_button_label_text_color',
					'product_count_text_button_sep_color',
					'product_count_bg_color',
					'product_count_bg_hover_color',
					'product_count_text_color',
					'product_count_text_hover_color',
					'product_count_border_color',
					'product_count_border_hover_color',

					'product_view_button_label_text_color',
					'product_view_bg_color',
					'product_view_bg_hover_color',
					'product_view_text_color',
					'product_view_text_hover_color',
					'product_view_border_color',
					'product_view_border_hover_color',
				];

				cssVarsOptions.width                                = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.width_medium                         = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.width_small                          = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.height                               = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.height_medium                        = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.height_small                         = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.column_gap                           = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.column_gap_medium                    = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.column_gap_small                     = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.row_gap                              = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.row_gap_medium                       = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.row_gap_small                        = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.margin_top                           = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.margin_right                         = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.margin_bottom                        = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.margin_left                          = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.margin_top_medium                    = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.margin_right_medium                  = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.margin_bottom_medium                 = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.margin_left_medium                   = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.margin_top_small                     = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.margin_right_small                   = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.margin_bottom_small                  = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.margin_left_small                    = { 'callback': _.fusionGetValueWithUnit };

				cssVarsOptions.dropdown_height                      = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.dropdown_width                       = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.select_font_size                     = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.dropdown_border_size                 = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.dropdown_border_radius               = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.order_spacing                        = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.order_font_size                      = { 'callback': _.fusionGetValueWithUnit };

				cssVarsOptions.product_count_width                  = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.product_count_height                 = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.product_count_button_label_font_size = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.product_count_select_font_size       = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.product_count_button_size            = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.product_count_border_size            = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.product_count_border_radius          = { 'callback': _.fusionGetValueWithUnit };

				cssVarsOptions.product_view_button_label_font_size  = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.product_view_button_size             = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.product_view_font_size               = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.product_view_border_size             = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.product_view_border_radius           = { 'callback': _.fusionGetValueWithUnit };

				customVars.product_count_text_button_sep = "'" + values.product_count_text_button_sep + "'";

				// Dropdown Typo.
				cssVarsOptions.dropdown_font_size      = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.push( 'dropdown_line_height' );
				cssVarsOptions.dropdown_letter_spacing = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.push( 'dropdown_text_transform' );

				// Font family and weight.
				jQuery.each( _.fusionGetFontStyle( 'dropdown_font', values, 'object' ), function( rule, value ) {
					customVars[ 'dropdown-' + rule ] = value;
				} );

				// Product Count Typo.
				cssVarsOptions.product_count_font_size      = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.push( 'product_count_line_height' );
				cssVarsOptions.product_count_letter_spacing = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.push( 'product_count_text_transform' );

				// Font family and weight.
				jQuery.each( _.fusionGetFontStyle( 'product_count_font', values, 'object' ), function( rule, value ) {
					customVars[ 'product-count-' + rule ] = value;
				} );

				return this.getCssVarsForOptions( cssVarsOptions ) + this.getCustomCssVars( customVars );
			}

		} );
	} );
}( jQuery ) );
