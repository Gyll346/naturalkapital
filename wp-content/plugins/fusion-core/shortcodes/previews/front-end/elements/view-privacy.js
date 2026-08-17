/* global FusionApp */
/* jshint -W024 */
var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Privacy Element View.
		FusionPageBuilder.fusion_privacy = FusionPageBuilder.ElementView.extend( {

			/**
			 * Modify template attributes.
			 *
			 * @since 2.0.0
			 * @returns null
			 */
			filterTemplateAtts: function( atts ) {
				var attributes = {};

				this.values = atts.values;

				// Resolve necessary_opacity: element override or global default.
				if ( '' === atts.values.necessary_opacity ) {
					atts.values.necessary_opacity = '' + ( atts.extras.necessary_opacity_global || 0.7 );
				}

				// Build attributes.
				attributes.attr        = this.buildAttr( atts.values );
				attributes.contentAttr = this.buildContentAttr( atts.values );
				attributes.formAttr    = this.buildFormAttr( atts.values );
				attributes.embeds      = this.buildEmbeds( atts.values, atts.extras );

				attributes.buttonString      = atts.extras.button_string;
				attributes.cid               = this.model.get( 'cid' );
				attributes.output            = atts.values.element_content;
				attributes.privacyLayout     = atts.values.privacy_layout;
				attributes.titleSize         = atts.values.title_size;
				attributes.visibleGroups     = atts.values.visible_groups || '';
				var showWp = atts.values.show_wp_cookies;
				attributes.showWpCookies = ( 'on' === showWp || ( '' === showWp && atts.extras.show_wp_cookies_global ) );
				attributes.wpCookies         = atts.extras.wp_cookies;
				attributes.pluginCookies     = atts.extras.plugin_cookies || {};
				attributes.customSnippets    = atts.extras.custom_snippets;
				// Derive group maps from the raw repeater data so live-editor updates are reflected immediately.
				var privacyBarContent    = atts.extras.privacy_bar_content,
					groupTitles          = {},
					groupDescriptions    = {},
					contentCategoryTypes = [],
					customIndex          = 0;

				if ( privacyBarContent && privacyBarContent.title ) {
					jQuery.each( privacyBarContent.title, function( idx ) {
						var type = privacyBarContent.type && privacyBarContent.type[ idx ] ? privacyBarContent.type[ idx ] : 'custom',
							key  = 'custom' === type ? 'custom_' + customIndex++ : type;

						if ( privacyBarContent.title[ idx ] ) {
							groupTitles[ key ] = privacyBarContent.title[ idx ];
						}
						if ( privacyBarContent.description && privacyBarContent.description[ idx ] ) {
							groupDescriptions[ key ] = privacyBarContent.description[ idx ];
						}
						if ( key ) {
							contentCategoryTypes.push( key );
						}
					} );
					contentCategoryTypes = _.uniq( contentCategoryTypes );
				}

				attributes.groupTitles            = groupTitles;
				attributes.groupDescriptions      = groupDescriptions;
				attributes.categoryFallbacks      = atts.extras.category_fallbacks || {};
				attributes.contentCategoryTypes   = contentCategoryTypes;
				attributes.groupColumnsLayout     = atts.values.group_columns_layout || 'stacked';
				attributes.showGroupTitles        = 'yes' === atts.values.show_group_titles;
				attributes.showGroupDescription   = 'yes' === atts.values.show_group_description;
				attributes.showUpdateButton       = 'no' !== atts.values.show_update_button;
				attributes.updateButtonText       = atts.values.update_button_text || atts.extras.button_string;
				attributes.showAcceptAllButton    = 'yes' === atts.values.show_accept_all_button;
				attributes.acceptAllButtonText    = atts.values.accept_all_button_text || atts.extras.accept_all_string;
				attributes.showRejectAllButton    = 'yes' === atts.values.show_reject_all_button;
				attributes.rejectAllButtonText    = atts.values.reject_all_button_text || atts.extras.reject_all_string;

				// Resolve effective lifespan display.
				var showLifespan = atts.values.show_lifespan;
				attributes.showLifespan = ( 'on' === showLifespan || ( '' === showLifespan && atts.extras.show_lifespan_global ) );

				// Build lifespan string.
				var expiry = parseInt( atts.extras.privacy_expiry, 10 ) || 30;
				attributes.lifespanStr = expiry + ' ' + ( 1 === expiry ? 'day' : 'days' );

				// Resolve effective description display.
				var showDesc = atts.values.show_description;
				attributes.showDesc = ( 'on' === showDesc || ( '' === showDesc && atts.extras.show_desc_global ) );

				return attributes;
			},

			buildAttr: function( values ) {
				var attr = {
					class: 'fusion-privacy-element fusion-privacy-element-' + this.model.get( 'cid' ),
					style: ''
				};

				attr = _.fusionVisibilityAtts( values.hide_on_mobile, attr );

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
			 * Gets style variables.
			 *
			 * @since 3.15.3
			 * @param  {Object} values - The values object.
			 * @return {String}
			 */
			getStyleVariables: function( values ) { // eslint-disable-line no-unused-vars
				var cssVarsOptions = [
					'title_color',
					'title_line_height',
					'title_text_transform',
					'necessary_opacity',
					'text_color',
					'text_line_height',
					'text_text_transform',
					'lifespan_color',
					'desc_color',
					'group_columns_gap'
				];

				cssVarsOptions.margin_top            = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.margin_right           = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.margin_bottom          = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.margin_left            = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.margin_top_medium      = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.margin_right_medium    = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.margin_bottom_medium   = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.margin_left_medium     = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.margin_top_small       = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.margin_right_small     = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.margin_bottom_small    = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.margin_left_small      = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.padding_top            = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.padding_right          = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.padding_bottom         = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.padding_left           = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.padding_top_medium     = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.padding_right_medium   = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.padding_bottom_medium  = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.padding_left_medium    = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.padding_top_small      = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.padding_right_small    = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.padding_bottom_small   = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.padding_left_small     = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.title_font_size        = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.title_letter_spacing   = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.text_font_size         = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.text_letter_spacing    = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.desc_font_size              = { 'callback': _.fusionGetValueWithUnit };
				cssVarsOptions.group_columns_min_width     = { 'callback': _.fusionGetValueWithUnit };

				return this.getCssVarsForOptions( cssVarsOptions )
					+ this.getFontStylingVars( 'title_font', values )
					+ this.getFontStylingVars( 'text_font', values );
			},

			buildContentAttr: function() {
				var self = this,
					contentAttr = {
						class: 'fusion-privacy-form-intro'
					};

				contentAttr = _.fusionInlineEditor( {
					cid: self.model.get( 'cid' )
				}, contentAttr );

				return contentAttr;
			},

			buildFormAttr: function( values ) {
				return {
					id:     'fusion-privacy-form-' + this.model.get( 'cid' ),
					action: '',
					method: '',
					class:  'fusion-privacy-form fusion-privacy-form-' + values.form_field_layout
				};
			},

			/**
			 * Build the full embed list from all active embed type IDs.
			 * Each entry has: { id, label, selected, category, lifespan, description }.
			 */
			buildEmbeds: function( values, extras ) {
				var builtEmbeds = [],
					selection   = FusionApp.settings.privacy_embed_defaults;

				if ( ! extras.embed_type_ids || ! extras.embed_type_ids.length ) {
					return false;
				}

				_.each( extras.embed_type_ids, function( id ) {
					var embedSelected = '',
						embedLabel    = extras.all_embed_labels ? extras.all_embed_labels[ id ] : id,
						embedCategory = extras.all_embed_categories ? ( extras.all_embed_categories[ id ] || 'embeds' ) : 'embeds',
						embedDesc     = extras.all_embed_descriptions ? ( extras.all_embed_descriptions[ id ] || '' ) : '';

					if ( 'object' === typeof selection && -1 !== selection.indexOf( id ) ) {
						embedSelected = 'checked';
					}

					builtEmbeds.push( {
						id:          id,
						label:       embedLabel || id,
						selected:    embedSelected,
						category:    embedCategory,
						lifespan:    '',
						description: embedDesc
					} );
				} );

				return builtEmbeds.length ? builtEmbeds : false;
			}
		} );
	} );
}( jQuery ) );
