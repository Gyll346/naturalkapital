var FusionPageBuilder = FusionPageBuilder || {};

( function( $ ) {

	$( document ).ready( function() {

		// Nested Column View
		FusionPageBuilder.NestedColumnView = FusionPageBuilder.BaseColumnView.extend( {

			template: FusionPageBuilder.template( $( '#fusion-builder-inner-column-template' ).html() ),

			events: {
				'click .fusion-builder-add-element': 'addModule',
				'click .fusion-builder-settings-column': 'showSettings',
				'click .fusion-builder-resize-inner-column': 'columnSizeDialog',
				'click .column-size': 'columnSize',
				'click .fusion-builder-remove-inner-column': 'removeColumn',
				'click .fusion-builder-clone-inner-column': 'cloneColumn',
				'paste .fusion-builder-column-name': 'renameColumn',
				'keydown .fusion-builder-column-name': 'renameColumn',
				'mousedown .fusion-builder-column-name': 'stopProp',
				'click .fusion-builder-column-name': 'stopProp'
			},

			stopProp: function( event ) {
				event.stopPropagation();
			}

		} );
	} );
}( jQuery ) );
