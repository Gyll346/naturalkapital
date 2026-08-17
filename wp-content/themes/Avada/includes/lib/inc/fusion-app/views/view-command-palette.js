/* global fusionAllElements, fusionMultiElements, fusionBuilderText, fusionAppConfig, FusionApp, FusionEvents, FusionPageBuilderApp, FusionPageBuilderViewManager, Fuse */
var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		FusionPageBuilder.CommandPalette = window.wp.Backbone.View.extend( {

			tagName: 'div',
			className: 'awb-command-palette',

			events: {
				'input .awb-command-palette__search': 'onSearch',
				'keydown .awb-command-palette__search': 'onKeyDown',
				'click .awb-command-palette__item': 'onItemClick',
				'click .awb-command-palette__item-help': 'onHelpClick',
				'mousemove .awb-command-palette__item': 'onItemHover',
				'click .awb-command-palette__backdrop': 'close'
			},

			isOpen: false,
			activeIndex: 0,
			results: [],
			actions: [],
			elements: [],
			contextualActions: [],
			fuse: null,
			_libraryItems: null,
			_libraryFuse: null,
			_libraryFetching: false,

			// Shared between element registry and nav-item flattening.
			structuralIcons: {
				'fusion_builder_container': 'fusiona-container',
				'fusion_builder_column':    'fusiona-column',
				'fusion_builder_row_inner': 'fusiona-columns'
			},

			// One source of truth for prefix characters / matchers.
			// `strip` = how many leading chars to drop to get the search query.
			prefixTable: [
				{ test: function( q ) { return '/' === q.charAt( 0 ) || '+' === q.charAt( 0 ); }, strip: 1, kind: 'element' },
				{ test: function( q ) { return '@' === q.charAt( 0 ); }, strip: 1, kind: 'page-element' },
				{ test: function( q ) { return '>' === q.charAt( 0 ); }, strip: 1, kind: 'action' },
				{ test: function( q ) { return '#' === q.charAt( 0 ); }, strip: 1, kind: 'library-element' },
				{ test: function( q ) { return '?' === q.charAt( 0 ); }, strip: 1, kind: 'shortcut-ref' },
				{ test: function( q ) { return /^PO:/i.test( q ); }, strip: 3, kind: 'po-option' },
				{ test: function( q ) { return /^GO:/i.test( q ); }, strip: 3, kind: 'to-option' }
			],

			initialize: function() {
				this.actions = this.buildActionRegistry();
				this.elements = this.buildElementRegistry();
				this.results = this.defaultResults();
				this.activeIndex = this.firstSelectableIndex();

				this.fuse = new Fuse( this.actions.concat( this.elements ), {
					keys: [ 'label', 'keywords' ],
					threshold: 0.4,
					ignoreLocation: true
				} );

				// Debounced search runner — cheap guard against big nav trees + multiple Fuse searches per keystroke.
				this._debouncedRunSearch = _.debounce( _.bind( this._runSearch, this ), 80 );

				this.render();
			},

			// Unwrap Fuse results (newer Fuse wraps hits in { item, ... }; older returns raw).
			_fuseSearch: function( fuse, query, limit ) {
				var hits = fuse.search( query );
				if ( limit ) {
					hits = hits.slice( 0, limit );
				}
				return _.map( hits, function( r ) { return r.item ? r.item : r; } );
			},

			_openOptionRun: function( fieldId, context ) {
				return function() {
					if ( FusionApp.sidebarView ) {
						FusionApp.sidebarView.openOption( fieldId, context );
					}
				};
			},

			t: function( key, fallback ) {
				if ( 'undefined' !== typeof fusionBuilderText && 'undefined' !== typeof fusionBuilderText[ key ] ) {
					return fusionBuilderText[ key ];
				}
				return fallback;
			},

			buildActionRegistry: function() {
				var self = this;

				return [
					{
						id: 'theme-options',
						label: self.t( 'command_theme_options', 'Open Global Options' ),
						keywords: 'global theme options settings to go',
						icon: 'fusiona-cog',
						run: function() {
							if ( FusionApp.sidebarView && 'function' === typeof FusionApp.sidebarView.togglePanelState ) {
								FusionApp.sidebarView.togglePanelState( 'to', true );
							}
						}
					},
					{
						id: 'page-options',
						label: self.t( 'command_page_options', 'Open Page Options' ),
						keywords: 'page options settings po',
						icon: 'fusiona-page-options',
						run: function() {
							if ( FusionApp.sidebarView && 'function' === typeof FusionApp.sidebarView.togglePanelState ) {
								FusionApp.sidebarView.togglePanelState( 'po', true );
							}
						}
					},
					{
						id: 'open-navigator',
						label: self.t( 'command_navigator', 'Open Navigator' ),
						keywords: 'navigator structure tree elements eo outline',
						icon: 'fusiona-navigator',
						run: function() {
							if ( FusionApp.sidebarView && 'function' === typeof FusionApp.sidebarView.togglePanelState ) {
								FusionApp.sidebarView.togglePanelState( 'eo', true );
							}
						}
					},
					{
						id: 'open-seo',
						label: self.t( 'command_open_seo', 'Open SEO Options' ),
						keywords: 'seo title meta description search engine',
						icon: 'fusiona-page-options',
						run: function() {
							if ( FusionApp.sidebarView && 'function' === typeof FusionApp.sidebarView.openOption ) {
								FusionApp.sidebarView.openOption( 'seo_title', 'po' );
							}
						}
					},					
					{
						id: 'code-fields',
						label: self.t( 'command_code_fields', 'Open Code Fields' ),
						keywords: 'code fields javascript tracking head body scripts inject',
						icon: 'fusiona-code',
						run: function() {
							if ( FusionApp.sidebarView && 'function' === typeof FusionApp.sidebarView.openOption ) {
								FusionApp.sidebarView.openOption( 'tracking_code', 'po' );
							}
						}
					},					
					{
						id: 'custom-css',
						label: self.t( 'command_custom_css', 'Open Custom CSS' ),
						keywords: 'css styles custom code',
						icon: 'fusiona-code',
						run: function() {
							if ( FusionApp.sidebarView && 'function' === typeof FusionApp.sidebarView.openOption ) {
								FusionApp.sidebarView.openOption( '_fusion_builder_custom_css', 'po' );
							}
						}
					},
					{
						id: 'scroll-to-top',
						label: self.t( 'command_scroll_to_top', 'Scroll to Top' ),
						keywords: 'scroll top beginning page start',
						icon: 'fusiona-arrow-up',
						run: function() {
							var $preview = jQuery( '#fb-preview' );
							if ( $preview.length ) {
								$preview.contents().find( 'html, body' ).animate( { scrollTop: 0 }, 400 );
							}
						}
					},
					{
						id: 'toggle-sidebar',
						label: self.t( 'command_toggle_sidebar', 'Toggle Sidebar' ),
						keywords: 'sidebar panel hide show toggle',
						icon: 'fusiona-sidebar-icon',
						run: function() {
							if ( FusionApp.sidebarView && 'function' === typeof FusionApp.sidebarView.togglePanel ) {
								FusionApp.sidebarView.togglePanel();
							}
						}
					},
					{
						id: 'open-library',
						label: self.t( 'command_library', 'Open Library' ),
						keywords: 'library elements columns containers studio',
						icon: 'fusiona-drive',
						run: function() { jQuery( '.fusion-builder-open-library' ).trigger( 'click' ); }
					},
					{
						id: 'undo',
						label: self.t( 'undo', 'Undo' ),
						keywords: 'undo back history revert',
						icon: 'fusiona-undo',
						run: function() { FusionEvents.trigger( 'fusion-history-undo' ); }
					},
					{
						id: 'redo',
						label: self.t( 'redo', 'Redo' ),
						keywords: 'redo forward history',
						icon: 'fusiona-undo',
						iconFlipped: true,
						run: function() { FusionEvents.trigger( 'fusion-history-redo' ); }
					},
					{
						id: 'open-preferences',
						label: self.t( 'command_preferences', 'Open Preferences' ),
						keywords: 'preferences settings options builder',
						icon: 'fusiona-preferences',
						run: function() { jQuery( '.fusion-builder-preferences a' ).trigger( 'click' ); }
					},					
					{
						id: 'clear-layout',
						label: self.t( 'command_clear_layout', 'Clear Layout' ),
						keywords: 'clear delete reset layout empty',
						icon: 'fusiona-trash-o',
						run: function() { jQuery( '.fusion-builder-clear-layout' ).trigger( 'click' ); }
					},
					{
						id: 'desktop',
						label: self.t( 'command_desktop_view', 'Desktop View' ),
						keywords: 'desktop large view device responsive',
						icon: 'fusiona-desktop',
						run: function() { jQuery( '.fusion-builder-preview-desktop' ).trigger( 'click' ); }
					},
					{
						id: 'tablet',
						label: self.t( 'command_tablet_view', 'Tablet View' ),
						keywords: 'tablet medium view device responsive',
						icon: 'fusiona-tablet',
						run: function() { jQuery( '.fusion-builder-preview-tablet.portrait' ).trigger( 'click' ); }
					},
					{
						id: 'mobile',
						label: self.t( 'command_mobile_view', 'Mobile View' ),
						keywords: 'mobile small phone view device responsive',
						icon: 'fusiona-mobile',
						run: function() { jQuery( '.fusion-builder-preview-mobile.portrait' ).trigger( 'click' ); }
					},					
					{
						id: 'preview',
						label: self.t( 'command_preview', 'Preview' ),
						keywords: 'preview view live',
						icon: 'fusiona-eye',
						run: function() { jQuery( '.fusion-toolbar-nav li.preview a' ).trigger( 'click' ); }
					},					
					{
						id: 'save-page',
						label: self.t( 'command_save_page', 'Save Page' ),
						keywords: 'save publish update page',
						icon: 'fusiona-published',
						run: function() {
							var $btn = jQuery( '.fusion-builder-save-page' );
							if ( ! $btn.data( 'disabled' ) ) {
								$btn.trigger( 'click' );
							}
						}
					},
					{
						id: 'save-template',
						label: self.t( 'command_save_template', 'Save as Template' ),
						keywords: 'template reusable save',
						icon: 'fusiona-template',
						run: function() { jQuery( '.fusion-builder-save-template' ).trigger( 'click' ); }
					},
					{
						id: 'view-frontend',
						label: self.t( 'command_view_frontend', 'View Page on Frontend' ),
						keywords: 'view frontend page open preview live',
						icon: 'fusiona-external-link-alt',
						run: function() {
							if ( 'undefined' !== typeof FusionApp.previewWindow ) {
								const url = new URL( FusionApp.previewWindow.location.href );
								url.search = '';							
								window.open( url.toString(), '_blank' );
							}
						}
					},
					{
						id: 'open-backend-new-tab',
						label: self.t( 'command_open_backend', 'Open Backend Builder' ),
						keywords: 'backend admin new tab open builder',
						icon: 'fusiona-external-link-alt',
						run: function() {
							var url = jQuery( '.fusion-exit-builder-list .exit-to-back-end a' ).attr( 'href' );
							if ( url ) {
								window.open( url, '_blank' );
							}
						}
					},
					{
						id: 'exit',
						label: self.t( 'command_exit_to_dashboard', 'Exit To Dashboard' ),
						keywords: 'exit close back end leave',
						icon: 'fusiona-close-fb',
						run: function() { jQuery( '.fusion-exit-builder-list .exit-to-dashboard a' ).trigger( 'click' ); }
					}					
				].map( function( a ) { a.group = 'action'; return a; } );
			},

			// Returns context-sensitive actions available only when an element/column/container has open settings.
			buildContextualActions: function() {
				var self       = this,
					activeView = this.getActiveEditView();

				if ( ! activeView || ! activeView.model ) {
					return [];
				}

				return [ {
					id:       'duplicate-element',
					label:    self.t( 'command_duplicate_element', 'Duplicate Active Element' ),
					keywords: 'duplicate clone copy element column container',
					icon:     'fusiona-file-add',
					group:    'action',
					run: function() {
						var view        = self.getActiveEditView(),
							elementType;

						if ( ! view || ! view.model ) {
							return;
						}

						elementType = view.model.get( 'element_type' );

						if ( 'fusion_builder_container' === elementType && 'function' === typeof view.cloneContainer ) {
							view.cloneContainer( undefined );
						} else if ( ( 'fusion_builder_column' === elementType || 'fusion_builder_column_inner' === elementType ) && 'function' === typeof view.cloneColumn ) {
							view.cloneColumn( undefined, true );
						} else if ( 'function' === typeof view.cloneElement ) {
							view.cloneElement( undefined, true );
						}
					}
				} ];
			},

			buildElementRegistry: function() {
				var self  = this,
					items = [];

				if ( 'undefined' === typeof fusionAllElements ) {
					return items;
				}

				var isFormContext = self._isFormContext();

				_.each( fusionAllElements, function( element, key ) {
					if ( ! element || ! element.name || ! element.shortcode ) {
						return;
					}
					if ( self.isStructuralElement( element ) ) {
						return;
					}
					// Form fields only make sense inside a fusion_form post.
					if ( self._isFormElement( element.shortcode ) && ! isFormContext ) {
						return;
					}

					items.push( {
						id: 'insert-' + key,
						label: element.name,
						keywords: ( element.name + ' ' + element.shortcode ).toLowerCase(),
						icon: element.icon || self.structuralIcons[ element.shortcode ] || 'fusiona-plus',
						group: 'element',
						elementType: element.shortcode,
						help_url: element.help_url || '',
						run: function() {
							self.insertElement( element );
						}
					} );
				} );

				items.sort( function( a, b ) {
					if ( isFormContext ) {
						var aForm = self._isFormElement( a.elementType || '' ),
							bForm = self._isFormElement( b.elementType || '' );
						if ( aForm !== bForm ) {
							return aForm ? -1 : 1;
						}
					}
					return a.label.localeCompare( b.label );
				} );

				return items;
			},

			// Shortcodes never shown in the palette (auto-managed structures, deprecated, or only valid via parent settings).
			excludedShortcodes: [
				'fusion_builder_blank_page',
				'fusion_builder_column_inner',
				'fusion_builder_inline',
				'fusion_builder_row',
				'fusion_circle_info',
				'fusion_content_box',
				'fusion_counter_box',
				'fusion_counter_circle',
				'fusion_flip_box',
				'fusion_gallery_image',
				'fusion_image',
				'fusion_image_hotspot_point',
				'fusion_li_item',
				'fusion_openstreetmap_marker',
				'fusion_pricing_column',
				'fusion_pricing_footer',
				'fusion_pricing_price',
				'fusion_pricing_row',
				'fusion_slide',
				'fusion_tab',
				'fusion_testimonial',
				'fusion_toggle',
				'fusion_woo_checkout_form'
			],

			_isFormContext: function() {
				return 'fusion_form' === jQuery( '#fb-preview' ).attr( 'data-post-type' );
			},

			_isFormElement: function( shortcode ) {
				return 0 === shortcode.indexOf( 'fusion_form_' ) || 'fusion_builder_form_step' === shortcode;
			},

			isStructuralElement: function( element ) {
				if ( -1 !== this.excludedShortcodes.indexOf( element.shortcode ) ) {
					return true;
				}

				// Allow containers, columns and nested columns through; they get custom insertion logic.
				var allowedStructural = [ 'fusion_builder_container', 'fusion_builder_column', 'fusion_builder_row_inner' ];
				if ( -1 !== allowedStructural.indexOf( element.shortcode ) ) {
					return false;
				}

				// Skip remaining child-only elements (inserted only via their parent's UI).
				if ( element.is_child || element.child_element ) {
					return true;
				}

				return false;
			},

			// View whose settings are currently rendered in the eo panel, or null.
			getActiveEditView: function() {
				var cid = jQuery( '#fusion-builder-sections-eo .fusion_builder_module_settings' ).attr( 'data-cid' );
				if ( ! cid || 'undefined' === typeof FusionPageBuilderViewManager ) {
					return null;
				}
				return FusionPageBuilderViewManager.getView( cid ) || null;
			},

			// Walk parent chain until a view with one of the given element_types is found.
			getAncestorView: function( view, types ) {
				while ( view && view.model ) {
					if ( -1 !== types.indexOf( view.model.get( 'element_type' ) ) ) {
						return view;
					}
					var parentCid = view.model.get( 'parent' );
					if ( ! parentCid ) {
						return null;
					}
					view = FusionPageBuilderViewManager.getView( parentCid );
				}
				return null;
			},

			// Scroll a view into the visible area (using the builder's scrollHighlight) and, if the preference allows, open its settings panel.
			openNewView: function( view, openSettings ) {
				if ( ! view ) {
					return;
				}
				if ( 'function' === typeof view.scrollHighlight ) {
					view.scrollHighlight();
				}
				if ( openSettings && 'off' !== FusionApp.preferencesData.open_settings && 'function' === typeof view.settings ) {
					view.settings();
				}
			},

			// Snapshot all current CIDs of the given element_type in the view manager.
			snapCids: function( elementType ) {
				var cids = {};
				_.each( FusionPageBuilderViewManager.getViews(), function( v, cid ) {
					if ( v && v.model && elementType === v.model.get( 'element_type' ) ) {
						cids[ cid ] = true;
					}
				} );
				return cids;
			},

			// After shortcodesToBuilder, find the first view of elementType not in preCids, then open it.
			openNewStructuralView: function( elementType, preCids ) {
				var self = this;
				setTimeout( function() {
					_.each( FusionPageBuilderViewManager.getViews(), function( v, cid ) {
						if ( v && v.model && elementType === v.model.get( 'element_type' ) && ! preCids[ cid ] ) {
							self.openNewView( v, true );
						}
					} );
				}, 0 );
			},

			insertElement: function( element ) {
				if ( 'fusion_builder_container' === element.shortcode ) {
					return this.insertContainer();
				}
				if ( 'fusion_builder_column' === element.shortcode ) {
					return this.insertColumn();
				}

				var self = this,
					activeView = this.getActiveEditView(),
					columnView = null,
					targetElement,
					params = {},
					elementParams,
					currentModel;

				// If an element is currently being edited, insert directly after it in its column.
				if ( activeView && activeView.model ) {
					var activeType = activeView.model.get( 'element_type' );
					if ( 'fusion_builder_column' === activeType || 'fusion_builder_column_inner' === activeType ) {
						columnView = activeView;
					} else if ( 'fusion_builder_container' !== activeType ) {
						columnView = this.getAncestorView( activeView, [ 'fusion_builder_column', 'fusion_builder_column_inner' ] );
						if ( columnView ) {
							targetElement = activeView.$el;
						}
					}
				}

				if ( ! columnView ) {
					columnView = this.findTargetColumnView();
				}

				if ( ! columnView ) {
					window.alert( this.t( 'command_palette_no_target_column', 'No column available to insert into. Add a container and column first.' ) );
					return;
				}

				_.each( element.params, function( param ) {
					params[ param.param_name ] = ( _.isObject( param.value ) ) ? param[ 'default' ] : param.value;
				} );

				elementParams = {
					type: 'element',
					added: 'manually',
					cid: FusionPageBuilderViewManager.generateCid(),
					element_type: element.shortcode,
					params: params,
					parent: columnView.model.get( 'cid' ),
					view: columnView,
					allow_generator: element.allow_generator,
					inline_editor: ( FusionPageBuilderApp.inlineEditorHelpers && 'function' === typeof FusionPageBuilderApp.inlineEditorHelpers.inlineEditorAllowed ) ? FusionPageBuilderApp.inlineEditorHelpers.inlineEditorAllowed( element.shortcode ) : false,
					multi: element.multi,
					child_ui: element.child_ui
				};

				if ( targetElement && targetElement.length ) {
					elementParams.targetElement = targetElement;
					elementParams.targetElementPosition = 'after';
					elementParams.at_index = FusionPageBuilderApp.getCollectionIndex( targetElement );
				}

				currentModel = columnView.collection.add( [ elementParams ] );

				// Scroll to the new element (settings open automatically via view-column.js when added:'manually').
				( function( cid ) {
					setTimeout( function() {
						var newView = FusionPageBuilderViewManager.getView( cid );
						if ( newView && 'function' === typeof newView.scrollHighlight ) {
							newView.scrollHighlight();
						}
					}, 0 );
				}( elementParams.cid ) );

				if ( 'undefined' !== typeof window.fusionGlobalManager ) {
					window.fusionGlobalManager.handleMultiGlobal( {
						currentModel: currentModel[ 0 ],
						handleType: 'save',
						attributes: currentModel[ 0 ].attributes
					} );
				}

				FusionEvents.trigger( 'fusion-history-save-step', ( fusionBuilderText.added || 'Added' ) + ' ' + element.name + ' ' + ( fusionBuilderText.element || 'element' ) );
				FusionEvents.trigger( 'fusion-content-changed' );
				FusionEvents.trigger( 'fusion-element-added' );
			},

			insertColumn: function() {
				var activeView = this.getActiveEditView(),
					rowView = null,
					targetColumnView = null,
					shortcode,
					preCids;

				if ( activeView && activeView.model ) {
					var activeType = activeView.model.get( 'element_type' );
					if ( 'fusion_builder_column' === activeType || 'fusion_builder_column_inner' === activeType ) {
						targetColumnView = activeView;
						rowView = FusionPageBuilderViewManager.getView( activeView.model.get( 'parent' ) );
					} else if ( 'fusion_builder_container' !== activeType ) {
						targetColumnView = this.getAncestorView( activeView, [ 'fusion_builder_column', 'fusion_builder_column_inner' ] );
						if ( targetColumnView ) {
							rowView = FusionPageBuilderViewManager.getView( targetColumnView.model.get( 'parent' ) );
						}
					}
				}

				if ( ! rowView ) {
					rowView = this.findFallbackRowView();
				}

				if ( ! rowView ) {
					window.alert( this.t( 'command_palette_no_target_column', 'No column available to insert into. Add a container and column first.' ) );
					return;
				}

				shortcode = '[fusion_builder_column type="1_1" align_self="auto" content_layout="column" align_content="flex-start" valign_content="flex-start" content_wrap="wrap" spacing="" center_content="no" link="" target="_self" min_height="" hide_on_mobile="small-visibility,medium-visibility,large-visibility" sticky_display="normal,sticky" type="1_1"][/fusion_builder_column]';

				if ( 'function' === typeof FusionPageBuilderApp.shortcodesToBuilder ) {
					preCids = this.snapCids( 'fusion_builder_column' );
					if ( targetColumnView && targetColumnView.$el && targetColumnView.$el.length ) {
						FusionPageBuilderApp.shortcodesToBuilder( shortcode, rowView.model.get( 'cid' ), undefined, undefined, targetColumnView.$el, 'after' );
					} else {
						FusionPageBuilderApp.shortcodesToBuilder( shortcode, rowView.model.get( 'cid' ) );
					}
					this.openNewStructuralView( 'fusion_builder_column', preCids );
					FusionEvents.trigger( 'fusion-history-save-step', ( fusionBuilderText.added || 'Added' ) + ' ' + this.t( 'column', 'Column' ) );
					FusionEvents.trigger( 'fusion-content-changed' );
					FusionEvents.trigger( 'fusion-element-added' );
				}
			},

			insertContainer: function() {
				var activeView = this.getActiveEditView(),
					targetContainerView = null,
					shortcode,
					preCids;

				if ( activeView && activeView.model ) {
					if ( 'fusion_builder_container' === activeView.model.get( 'element_type' ) ) {
						targetContainerView = activeView;
					} else {
						targetContainerView = this.getAncestorView( activeView, [ 'fusion_builder_container' ] );
					}
				}

				if ( ! targetContainerView ) {
					targetContainerView = this.findLastContainerView();
				}

				shortcode = '[fusion_builder_container hundred_percent="no" hundred_percent_height="no" hundred_percent_height_scroll="no" hundred_percent_height_center_content="yes" equal_height_columns="no" menu_anchor="" hide_on_mobile="small-visibility,medium-visibility,large-visibility" status="published" border_style="solid" type="flex"][fusion_builder_row][fusion_builder_column type="1_1" align_self="auto" content_layout="column" align_content="flex-start" valign_content="flex-start" content_wrap="wrap" spacing="" center_content="no" link="" target="_self" min_height="" hide_on_mobile="small-visibility,medium-visibility,large-visibility" sticky_display="normal,sticky" type="1_1"][/fusion_builder_column][/fusion_builder_row][/fusion_builder_container]';

				if ( 'function' === typeof FusionPageBuilderApp.shortcodesToBuilder ) {
					preCids = this.snapCids( 'fusion_builder_container' );
					if ( targetContainerView && targetContainerView.model ) {
						FusionPageBuilderApp.targetContainerCID = targetContainerView.model.get( 'cid' );
					}
					FusionPageBuilderApp.shortcodesToBuilder( shortcode );
					this.openNewStructuralView( 'fusion_builder_container', preCids );
					FusionEvents.trigger( 'fusion-history-save-step', ( fusionBuilderText.added || 'Added' ) + ' ' + this.t( 'container', 'Container' ) );
					FusionEvents.trigger( 'fusion-content-changed' );
					FusionEvents.trigger( 'fusion-element-added' );
				}
			},

			findFallbackRowView: function() {
				var views, columnView, rowView = null;

				if ( 'undefined' === typeof FusionPageBuilderViewManager ) {
					return null;
				}

				views = FusionPageBuilderViewManager.getViews();

				if ( 'undefined' !== typeof FusionPageBuilderApp && FusionPageBuilderApp.parentColumnId && views[ FusionPageBuilderApp.parentColumnId ] ) {
					columnView = views[ FusionPageBuilderApp.parentColumnId ];
					if ( columnView && columnView.model && views[ columnView.model.get( 'parent' ) ] ) {
						return views[ columnView.model.get( 'parent' ) ];
					}
				}

				_.each( views, function( view ) {
					if ( view && view.model && 'fusion_builder_row' === view.model.get( 'element_type' ) ) {
						rowView = view;
					}
				} );

				return rowView;
			},

			findLastContainerView: function() {
				var containerView = null;

				if ( 'undefined' === typeof FusionPageBuilderViewManager ) {
					return null;
				}

				_.each( FusionPageBuilderViewManager.getViews(), function( view ) {
					if ( view && view.model && 'fusion_builder_container' === view.model.get( 'element_type' ) ) {
						containerView = view;
					}
				} );

				return containerView;
			},

			findTargetColumnView: function() {
				var preferredCid,
					views,
					targetView = null;

				if ( 'undefined' !== typeof FusionPageBuilderApp && FusionPageBuilderApp.parentColumnId ) {
					preferredCid = FusionPageBuilderApp.parentColumnId;
				}

				if ( 'undefined' === typeof FusionPageBuilderViewManager ) {
					return null;
				}

				views = FusionPageBuilderViewManager.getViews();

				if ( preferredCid && views[ preferredCid ] && this.isColumnView( views[ preferredCid ] ) ) {
					return views[ preferredCid ];
				}

				_.each( views, function( view ) {
					if ( this.isColumnView( view ) ) {
						targetView = view;
					}
				}, this );

				return targetView;
			},

			isColumnView: function( view ) {
				if ( ! view || ! view.model || ! view.collection ) {
					return false;
				}
				var type = view.model.get( 'element_type' );
				return 'fusion_builder_column' === type || 'fusion_builder_column_inner' === type;
			},

			// Returns palette items for child elements of the currently-active multi-element parent, or [].
			buildChildElements: function() {
				var self = this,
					activeView = this.getActiveEditView(),
					items = [],
					activeType,
					childShortcode,
					childElement,
					parentElement;

				if ( ! activeView || ! activeView.model || 'undefined' === typeof fusionMultiElements ) {
					return items;
				}

				activeType = activeView.model.get( 'element_type' );

				if ( ! ( activeType in fusionMultiElements ) ) {
					return items;
				}

				childShortcode = fusionMultiElements[ activeType ];
				childElement   = fusionAllElements[ childShortcode ];
				parentElement  = fusionAllElements[ activeType ];

				if ( ! childElement || ! childElement.name ) {
					return items;
				}

				items.push( {
					id: 'insert-child-' + childShortcode,
					label: childElement.name,
					keywords: ( childElement.name + ' ' + childShortcode + ' add child ' + ( parentElement ? parentElement.name : '' ) ).toLowerCase(),
					icon: childElement.icon || 'fusiona-plus',
					group: 'element',
					run: ( function( view ) {
						return function() {
							if ( 'function' === typeof view.addChildElement ) {
								view.addChildElement( null );
							}
						};
					}( activeView ) )
				} );

				return items;
			},

			_fetchLibraryItems: function() {
				if ( null !== this._libraryItems || this._libraryFetching ) {
					return;
				}
				if ( 'undefined' === typeof fusionAppConfig || ! fusionAppConfig.fusion_load_nonce ) {
					return;
				}
				this._libraryFetching = true;
				var self = this;
				jQuery.ajax( {
					type: 'POST',
					url: fusionAppConfig.ajaxurl,
					data: {
						action:             'awb_get_library_items',
						fusion_load_nonce:  fusionAppConfig.fusion_load_nonce
					}
				} ).done( function( response ) {
					var data;
					try { data = JSON.parse( response ); } catch ( e ) { data = []; }
					self._libraryItems = data;
					self._libraryFuse  = new Fuse( data, {
						keys:             [ 'title' ],
						threshold:        0.3,
						minMatchCharLength: 2,
						ignoreLocation:   true
					} );
					self._libraryFetching = false;
					// If palette is still open and '#' prefix is active, refresh results.
					var $search = self.$( '.awb-command-palette__search' );
					if ( self.isOpen && $search.length && '#' === $search.val().charAt( 0 ) ) {
						self.onSearch( { currentTarget: $search[ 0 ] } );
					}
				} ).fail( function() {
					self._libraryFetching = false;
					self._libraryItems = [];
				} );
			},

			_showLibraryLoader: function() {
				this._$libraryLoader = jQuery(
					'<div class="awb-library-insert-loader">' +
					'<div class="awb-library-insert-loader__spinner"></div>' +
					'<span>' + this.escapeHtml( this.t( 'command_palette_library_inserting', 'Inserting\u2026' ) ) + '</span>' +
					'</div>'
				).appendTo( 'body' );
			},

			_hideLibraryLoader: function() {
				if ( this._$libraryLoader ) {
					this._$libraryLoader.remove();
					this._$libraryLoader = null;
				}
			},

			_libraryParentCID: function( type ) {
				// Sections and columns are top-level; pass false so shortcodesToBuilder inserts at root.
				if ( 'section' === type || 'column' === type ) {
					return false;
				}
				// For element type, try to find the active column's CID.
				var activeView  = this.getActiveEditView(),
					columnView  = null;
				if ( activeView && activeView.model ) {
					var activeType = activeView.model.get( 'element_type' );
					if ( 'fusion_builder_column' === activeType || 'fusion_builder_column_inner' === activeType ) {
						columnView = activeView;
					} else if ( 'fusion_builder_container' !== activeType ) {
						columnView = this.getAncestorView( activeView, [ 'fusion_builder_column', 'fusion_builder_column_inner' ] );
					}
				}
				return columnView ? columnView.model.get( 'cid' ) : false;
			},

			_makeLibraryRun: function( item ) {
				var self = this;
				return function() {
					if ( 'undefined' === typeof fusionAppConfig || ! fusionAppConfig.fusion_load_nonce ) {
						return;
					}
					var parentCID = self._libraryParentCID( item.type );
					self._showLibraryLoader();
					jQuery.ajax( {
						type: 'POST',
						url:  fusionAppConfig.ajaxurl,
						data: {
							action:             'fusion_builder_load_layout',
							fusion_load_nonce:  fusionAppConfig.fusion_load_nonce,
							fusion_is_global:   item.is_global ? 1 : 0,
							fusion_layout_id:   item.id
						}
					} ).done( function( data ) {
						self._hideLibraryLoader();
						var dataObj;
						try { dataObj = JSON.parse( data ); } catch ( e ) { return; }
						if ( dataObj && dataObj.post_content && FusionPageBuilderApp && 'function' === typeof FusionPageBuilderApp.shortcodesToBuilder ) {
							FusionPageBuilderApp.shortcodesToBuilder( dataObj.post_content, parentCID );
						}
					} ).fail( function() {
						self._hideLibraryLoader();
					} );
				};
			},

			searchLibraryElements: function( query ) {
				var self = this,
					raw, items;

				if ( null === this._libraryItems ) {
					if ( query ) {
						return [ { id: null, label: this.t( 'command_palette_library_loading', 'Loading library\u2026' ), group: 'library-element', icon: 'fusiona-spinner' } ];
					}
					return [];
				}

				if ( query && this._libraryFuse ) {
					raw = this._fuseSearch( this._libraryFuse, query );
				} else {
					raw = this._libraryItems.slice( 0, 20 );
				}

				items = _.map( raw, function( item ) {
					return {
						id:         'lib-' + item.id,
						label:      item.title,
						group:      'library-element',
						icon:       item.is_global ? 'fusiona-globe' : 'fusiona-drive',
						is_global:  item.is_global,
						lib_type:   item.type,
						_libraryId: item.id,
						run:        self._makeLibraryRun( item )
					};
				} );

				return items;
			},

			getShortcutReference: function( query ) {
				var mod = 'Cmd/Ctrl',
					lq  = query.toLowerCase(),

					prefixes = [
						{ label: this.t( 'command_palette_elements_heading', 'Insert Element' ),         group: 'prefix-ref', icon: 'fusiona-plus',            kbd: '[+]  or  [/]' },
						{ label: this.t( 'command_palette_library_heading', 'Insert Library Element' ),  group: 'prefix-ref', icon: 'fusiona-drive',           kbd: '[#]' },
						{ label: this.t( 'command_palette_page_elements_heading', 'Find On Page' ),      group: 'prefix-ref', icon: 'fusiona-navigator',       kbd: '[@]' },
						{ label: this.t( 'command_palette_actions_heading', 'Commands' ),                group: 'prefix-ref', icon: 'fusiona-arrow-right',     kbd: '[>]' },
						{ label: this.t( 'command_palette_po_options_heading', 'Page Options' ),         group: 'prefix-ref', icon: 'fusiona-settings',        kbd: '[PO:]' },
						{ label: this.t( 'command_palette_to_options_heading', 'Global Options' ),       group: 'prefix-ref', icon: 'fusiona-cog',             kbd: '[GO:]' },
						{ label: this.t( 'command_ref_shortcut_reference', 'This Reference' ),           group: 'prefix-ref', icon: 'fusiona-question-circle', kbd: '[?]' }
					],

					hotkeys = [
						{ label: this.t( 'command_save_page', 'Save Page' ),                    group: 'kbd-ref', icon: 'fusiona-published',      kbd: mod + ' + S' },
						{ label: this.t( 'undo', 'Undo' ),                                      group: 'kbd-ref', icon: 'fusiona-undo',            kbd: mod + ' + Z' },
						{ label: this.t( 'redo', 'Redo' ),                                      group: 'kbd-ref', icon: 'fusiona-undo',            kbd: mod + ' + Y', iconFlipped: true },
						{ label: this.t( 'command_save_template', 'Save as Template' ),         group: 'kbd-ref', icon: 'fusiona-template',        kbd: mod + ' + Shift + S' },
						{ label: this.t( 'command_custom_css', 'Open Custom CSS' ),             group: 'kbd-ref', icon: 'fusiona-code',            kbd: mod + ' + Shift + C' },
						{ label: this.t( 'command_preview', 'Preview' ),                        group: 'kbd-ref', icon: 'fusiona-eye',             kbd: 'Shift + P' },
						{ label: this.t( 'command_toggle_sidebar', 'Toggle Sidebar' ),          group: 'kbd-ref', icon: 'fusiona-sidebar-icon',    kbd: 'Shift + T' },
						{ label: this.t( 'command_desktop_view', 'Desktop View' ),              group: 'kbd-ref', icon: 'fusiona-desktop',         kbd: mod + ' + 1' },
						{ label: this.t( 'command_tablet_view', 'Tablet View' ),                group: 'kbd-ref', icon: 'fusiona-tablet',          kbd: mod + ' + 3' },
						{ label: this.t( 'command_mobile_view', 'Mobile View' ),                group: 'kbd-ref', icon: 'fusiona-mobile',          kbd: mod + ' + 2' },
						{ label: this.t( 'command_clear_layout', 'Clear Layout' ),              group: 'kbd-ref', icon: 'fusiona-trash-o',         kbd: mod + ' + D' },
						{ label: this.t( 'command_ref_command_palette', 'Command Palette' ),    group: 'kbd-ref', icon: 'fusiona-search',          kbd: mod + ' + K  /  F1' }
					],

					all = prefixes.concat( hotkeys );

				if ( lq ) {
					all = _.filter( all, function( item ) {
						return -1 !== item.label.toLowerCase().indexOf( lq );
					} );
				}

				return _.map( all, function( item, i ) {
					return {
						id:          'ref-' + i,
						label:       item.label,
						group:       item.group,
						icon:        item.icon,
						iconFlipped: item.iconFlipped || false,
						kbd:         item.kbd,
						run:         function() {}
					};
				} );
			},

			// Cache of flattened navigator items, built per open() session. Reset in open().
			_flatNavCache: null,

			_getFlatNavItems: function() {
				if ( null !== this._flatNavCache ) {
					return this._flatNavCache;
				}
				var items = [];
				this._flattenNavItems(
					FusionPageBuilderApp.navigator ? FusionPageBuilderApp.navigator.get( 'navigatorItems' ) : null,
					items
				);
				this._flatNavCache = items;
				return items;
			},

			searchPageElements: function( query ) {
				var lq = query.toLowerCase();
				return _.filter( this._getFlatNavItems(), function( item ) {
					return -1 !== item.label.toLowerCase().indexOf( lq );
				} );
			},

			_flattenNavItems: function( navItems, result ) {
				var self = this;

				if ( ! Array.isArray( navItems ) ) {
					return;
				}

				_.each( navItems, function( item ) {
					var elType = item.type,
						elDef  = fusionAllElements[ elType ] || {},
						icon   = elDef.icon || self.structuralIcons[ elType ] || 'fusiona-plus',
						view   = item.view;

					result.push( {
						id:    'page-el-' + view.cid,
						label: item.name,
						group: 'page-element',
						icon:  icon,
						run:   ( function( v ) {
							return function() {
								if ( 'function' === typeof v.scrollHighlight ) {
									v.scrollHighlight();
								}
							};
						}( view ) )
					} );

					if ( item.children && item.children.length ) {
						self._flattenNavItems( item.children, result );
					}
				} );
			},

			searchOptions: function( query, context, limit ) {
				if ( ! FusionApp.sidebarView ) {
					return [];
				}
				if ( ! this._poFuse ) {
					this._buildOptionFuse();
				}
				limit = limit || 10;
				if ( 'po' === context ) {
					return this._fuseSearch( this._poFuse, query, limit );
				}
				if ( 'to' === context ) {
					return this._fuseSearch( this._toFuse, query, limit );
				}
				return this._fuseSearch( this._poFuse, query, limit )
					.concat( this._fuseSearch( this._toFuse, query, limit ) );
			},

			_buildOptionFuse: function() {
				var self      = this,
					poItems   = [],
					toItems   = [],
					skipTypes = { 'info': true, 'raw': true, 'sub-section': true, 'accordion': true, 'hidden': true },
					fuseOpts  = { keys: [ 'label' ], threshold: 0.3, minMatchCharLength: 2, ignoreLocation: true };

				_.each( FusionApp.sidebarView.getFlatPoObject(), function( field, fieldId ) {
					if ( ! field.label || 'string' !== typeof field.label || skipTypes[ field.type ] ) {
						return;
					}
					poItems.push( {
						id:    'po-opt-' + fieldId,
						label: field.label,
						group: 'po-option',
						icon:  'fusiona-page-options',
						run:   self._openOptionRun( fieldId, 'po' )
					} );
				} );

				_.each( FusionApp.sidebarView.getFlatToObject(), function( field, fieldId ) {
					if ( ! field.label || 'string' !== typeof field.label || skipTypes[ field.type ] ) {
						return;
					}
					toItems.push( {
						id:    'to-opt-' + fieldId,
						label: field.label,
						group: 'to-option',
						icon:  'fusiona-cog',
						run:   self._openOptionRun( fieldId, 'to' )
					} );
				} );

				this._poFuse = new Fuse( poItems, fuseOpts );
				this._toFuse = new Fuse( toItems, fuseOpts );
			},

			defaultResults: function() {
				var recents           = this.loadRecents(),
					contextualActions = this.contextualActions || [];
				// Order: recents → contextual actions → regular actions (all share group:'action', one heading).
				return recents.concat( contextualActions ).concat( this.actions.slice() );
			},

			firstSelectableIndex: function() {
				for ( var i = 0; i < this.results.length; i++ ) {
					if ( this.results[ i ] && this.results[ i ].id ) {
						return i;
					}
				}
				return 0;
			},

			render: function() {
				var placeholder = this.t( 'command_palette_placeholder', 'Type a command\u2026' ),
					html = '';

				html += '<div class="awb-command-palette__backdrop"></div>';
				html += '<div class="awb-command-palette__panel" role="dialog" aria-label="Command Palette">';
				html += '<input type="text" class="awb-command-palette__search" placeholder="' + this.escapeHtml( placeholder ) + '" aria-autocomplete="list" autocomplete="off" spellcheck="false" />';
				html += '<ul class="awb-command-palette__results" role="listbox"></ul>';
				html += '</div>';

				this.$el.html( html );
				this.$el.hide();
				jQuery( 'body' ).append( this.$el );
				this.renderResults();

				return this;
			},

			renderResults: function() {
				var $list = this.$( '.awb-command-palette__results' ),
					html = '',
					currentGroup = null,
					groupHeadings = {
						'recent':          { text: this.t( 'command_palette_recent_heading', 'Recent' ),            hint: '' },
						'action':          { text: this.t( 'command_palette_actions_heading', 'Actions' ),          hint: '[>]' },
						'element':         { text: this.t( 'command_palette_elements_heading', 'Insert Element' ),  hint: '[+]' },
						'library-element': { text: this.t( 'command_palette_library_heading', 'Insert Library Element' ), hint: '[#]' },
						'page-element':    { text: this.t( 'command_palette_page_elements_heading', 'Find On Page' ), hint: '[@]' },
						'po-option':       { text: this.t( 'command_palette_po_options_heading', 'Page Options' ),  hint: '[PO:]' },
						'to-option':       { text: this.t( 'command_palette_to_options_heading', 'Global Options' ), hint: '[GO:]' },
						'prefix-ref':      { text: this.t( 'command_ref_prefixes_heading', 'Palette Prefixes' ),   hint: '[?]' },
						'kbd-ref':         { text: this.t( 'command_ref_kbd_heading', 'Keyboard Shortcuts' ),       hint: '[?]' }
					};

				if ( 0 === this.results.length ) {
					html = '<li class="awb-command-palette__empty">' + this.escapeHtml( this.t( 'command_palette_empty', 'No matching commands' ) ) + '</li>';
				} else {
					_.each( this.results, function( item, index ) {
						if ( item.group !== currentGroup ) {
							currentGroup = item.group;
							var h = groupHeadings[ currentGroup ] || { text: currentGroup, hint: '' };
							html += '<li class="awb-command-palette__group-heading" role="presentation">' +
								this.escapeHtml( h.text ) +
								( h.hint ? ' <span class="awb-command-palette__group-hint">' + this.escapeHtml( h.hint ) + '</span>' : '' ) +
								'</li>';
						}

						var activeClass  = ( index === this.activeIndex ) ? ' is-active' : '',
							iconClass    = item.icon ? item.icon : 'fusiona-plus',
							flippedClass = item.iconFlipped ? ' is-flipped' : '',
							refClass     = item.kbd ? ' is-ref' : '';

						html += '<li class="awb-command-palette__item' + activeClass + refClass + '" data-index="' + index + '" role="option">' +
							'<span class="awb-command-palette__item-icon ' + iconClass + flippedClass + '" aria-hidden="true"></span>' +
							'<span class="awb-command-palette__item-label">' + this.escapeHtml( item.label ) + '</span>' +
							( item.kbd
								? '<kbd class="awb-command-palette__item-kbd">' + this.escapeHtml( item.kbd ) + '</kbd>'
								: ( item.help_url
									? '<a class="awb-command-palette__item-help" href="' + this.escapeHtml( item.help_url ) + '" target="_blank" rel="noopener noreferrer" tabindex="-1" title="' + this.escapeHtml( this.t( 'command_palette_docs', 'Documentation' ) ) + '"><i class="fusiona-question-circle" aria-hidden="true"></i></a>'
									: ''
								)
							) +
							'</li>';
					}, this );
				}

				$list.html( html );
			},

			open: function() {
				if ( this.isOpen ) {
					return;
				}
				this.isOpen = true;
				this._flatNavCache = null;

				this._fetchLibraryItems();

				var childElements     = this.buildChildElements(),
					contextualActions = this.buildContextualActions(),
					allItems          = this.actions.concat( contextualActions ).concat( this.elements ).concat( childElements );

				this.contextualActions = contextualActions;

				this.fuse = new Fuse( allItems, {
					keys: [ 'label', 'keywords' ],
					threshold: 0.4,
					ignoreLocation: true
				} );

				this.results = this.defaultResults();
				if ( childElements.length ) {
					this.results = this.results.concat( childElements );
				}

				this.activeIndex = this.firstSelectableIndex();
				this.renderResults();
				this.$el.show();
				this.$( '.awb-command-palette__search' ).val( '' ).trigger( 'focus' );
			},

			close: function() {
				if ( ! this.isOpen ) {
					return;
				}
				this.isOpen = false;
				var self   = this,
					$panel = this.$( '.awb-command-palette__panel' );
				$panel.addClass( 'is-closing' );
				setTimeout( function() {
					self.$el.hide();
					$panel.removeClass( 'is-closing' );
				}, 150 );
			},

			toggle: function() {
				if ( this.isOpen ) {
					this.close();
				} else {
					this.open();
				}
			},

			onSearch: function( event ) {
				this._lastQuery = jQuery( event.currentTarget ).val();
				this._debouncedRunSearch();
			},

			_resolvePrefix: function( query ) {
				for ( var i = 0; i < this.prefixTable.length; i++ ) {
					var p = this.prefixTable[ i ];
					if ( p.test( query ) ) {
						return { kind: p.kind, searchQuery: query.slice( p.strip ).trim() };
					}
				}
				return null;
			},

			_runSearch: function() {
				var query = this._lastQuery || '',
					match,
					prefix,
					searchQuery,
					raw,
					fuseElements,
					fuseActions;

				if ( '' === query ) {
					this.results = this.defaultResults();
				} else {
					match = this._resolvePrefix( query );
					prefix = match ? match.kind : null;
					searchQuery = match ? match.searchQuery : query;

					if ( 'element' === prefix ) {
						if ( searchQuery ) {
							raw = this._fuseSearch( this.fuse, searchQuery );
							this.results = _.filter( raw, function( r ) { return 'element' === r.group; } );
						} else {
							this.results = this.elements.slice();
						}
					} else if ( 'page-element' === prefix ) {
						// Empty searchQuery matches all page elements via indexOf('') === 0.
						this.results = this.searchPageElements( searchQuery );
					} else if ( 'action' === prefix ) {
						if ( searchQuery ) {
							raw = this._fuseSearch( this.fuse, searchQuery );
							this.results = _.filter( raw, function( r ) { return 'action' === r.group; } );
						} else {
							this.results = ( this.contextualActions || [] ).concat( this.actions.slice() );
						}
					} else if ( 'po-option' === prefix ) {
						this.results = searchQuery ? this.searchOptions( searchQuery, 'po', 20 ) : [];
					} else if ( 'to-option' === prefix ) {
						this.results = searchQuery ? this.searchOptions( searchQuery, 'to', 20 ) : [];
					} else if ( 'library-element' === prefix ) {
						this.results = this.searchLibraryElements( searchQuery );
					} else if ( 'shortcut-ref' === prefix ) {
						this.results = this.getShortcutReference( searchQuery );
					} else {
						// No prefix — search all categories.
						raw          = this._fuseSearch( this.fuse, query );
						fuseElements = _.filter( raw, function( r ) { return 'element' === r.group; } );
						fuseActions  = _.filter( raw, function( r ) { return 'element' !== r.group; } );
						this.results = fuseElements
							.concat( null !== this._libraryItems ? this.searchLibraryElements( query ).slice( 0, 10 ) : [] )
							.concat( this.searchPageElements( query ) )
							.concat( fuseActions )
							.concat( this.searchOptions( query ) );
					}
				}

				this.activeIndex = this.firstSelectableIndex();
				this.renderResults();
			},

			onKeyDown: function( event ) {
				switch ( event.keyCode ) {
				case 27: // Esc.
					event.preventDefault();
					this.close();
					break;
				case 13: // Enter.
					event.preventDefault();
					this.execute( this.activeIndex );
					break;
				case 40: // Down.
					event.preventDefault();
					this.move( 1 );
					break;
				case 38: // Up.
					event.preventDefault();
					this.move( -1 );
					break;
				}
			},

			move: function( delta ) {
				var len = this.results.length,
					$next,
					top,
					parentScroll,
					parentHeight;

				if ( ! len ) {
					return;
				}

				this.activeIndex = ( this.activeIndex + delta + len ) % len;
				this.$( '.awb-command-palette__item' ).removeClass( 'is-active' );
				$next = this.$( '.awb-command-palette__item[data-index="' + this.activeIndex + '"]' ).addClass( 'is-active' );

				if ( $next.length ) {
					top = $next[ 0 ].offsetTop;
					parentScroll = $next.parent()[ 0 ].scrollTop;
					parentHeight = $next.parent()[ 0 ].clientHeight;

					if ( top < parentScroll ) {
						$next.parent()[ 0 ].scrollTop = top;
					} else if ( top + $next.outerHeight() > parentScroll + parentHeight ) {
						$next.parent()[ 0 ].scrollTop = top + $next.outerHeight() - parentHeight;
					}
				}
			},

			onItemClick: function( event ) {
				var index = parseInt( jQuery( event.currentTarget ).attr( 'data-index' ), 10 );
				this.execute( index );
			},

			onHelpClick: function( event ) {
				// Let the <a> navigate; just prevent the parent item click from also running.
				event.stopPropagation();
			},

			onItemHover: function( event ) {
				var index = parseInt( jQuery( event.currentTarget ).attr( 'data-index' ), 10 );

				if ( index !== this.activeIndex ) {
					this.activeIndex = index;
					this.$( '.awb-command-palette__item' ).removeClass( 'is-active' );
					jQuery( event.currentTarget ).addClass( 'is-active' );
				}
			},

			execute: function( index ) {
				var item = this.results[ index ];

				if ( ! item ) {
					return;
				}

				this.saveRecent( item );
				this.close();

				try {
					item.run();
				} catch ( e ) {
					if ( window.console && window.console.error ) {
						window.console.error( 'Command palette action failed:', item.id, e );
					}
				}
			},

			saveRecent: function( item ) {
				if ( ! item || ! item.id || 'page-element' === item.group ) {
					return;
				}
				try {
					var stored  = JSON.parse( localStorage.getItem( 'awb_command_palette_recents' ) || '[]' ),
						group   = 'recent' === item.group ? ( item._originalGroup || 'action' ) : item.group,
						entry   = {
							id:           item.id,
							label:        item.label,
							icon:         item.icon || '',
							group:        group,
							help_url:     item.help_url || '',
							iconFlipped:  !! item.iconFlipped,
							is_global:    item.is_global || false,
							lib_type:     item.lib_type || ''
						};

					stored = _.filter( stored, function( r ) { return r.id !== item.id; } );
					stored.unshift( entry );
					localStorage.setItem( 'awb_command_palette_recents', JSON.stringify( stored.slice( 0, 5 ) ) );
				} catch ( e ) {}
			},

			loadRecents: function() {
				var self = this,
					stored;
				try {
					stored = JSON.parse( localStorage.getItem( 'awb_command_palette_recents' ) || '[]' );
				} catch ( e ) {
					return [];
				}
				return _.filter( _.map( stored, function( r ) {
					return self.rehydrateItem( r );
				} ), Boolean );
			},

			rehydrateItem: function( stored ) {
				var run, found, fieldId;

				if ( 0 === stored.id.indexOf( 'insert-' ) ) {
					found = _.find( this.elements, function( e ) { return e.id === stored.id; } );
					if ( ! found ) {
						return null;
					}
					run = found.run;
				} else if ( 0 === stored.id.indexOf( 'po-opt-' ) ) {
					fieldId = stored.id.slice( 7 );
					run = this._openOptionRun( fieldId, 'po' );
				} else if ( 0 === stored.id.indexOf( 'to-opt-' ) ) {
					fieldId = stored.id.slice( 7 );
					run = this._openOptionRun( fieldId, 'to' );
				} else if ( 0 === stored.id.indexOf( 'lib-' ) ) {
					run = this._makeLibraryRun( {
						id:        parseInt( stored.id.slice( 4 ), 10 ),
						is_global: stored.is_global || false,
						type:      stored.lib_type || 'element'
					} );
				} else {
					found = _.find( this.actions, function( a ) { return a.id === stored.id; } );
					if ( ! found ) {
						return null;
					}
					run = found.run;
				}

				return {
					id:            stored.id,
					label:         stored.label,
					icon:          stored.icon,
					group:         'recent',
					_originalGroup: stored.group,
					help_url:      stored.help_url || '',
					iconFlipped:   stored.iconFlipped || false,
					run:           run
				};
			},

			escapeHtml: function( str ) {
				return String( str )
					.replace( /&/g, '&amp;' )
					.replace( /</g, '&lt;' )
					.replace( />/g, '&gt;' )
					.replace( /"/g, '&quot;' );
			}
		} );
	} );
}( jQuery ) );
