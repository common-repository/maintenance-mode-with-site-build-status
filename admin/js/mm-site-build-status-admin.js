(function( $ ) {
	'use strict';
	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	$( document ).ready( function() {
		/*--------------------------------------------------------------
		## Displays error message if Add Stage has no value
		--------------------------------------------------------------*/
		function displayErrorMessage( text, classes ) {
			var formattedClasses = classes ? ' ' + classes : '';
			return '<div class="red-error-message' + formattedClasses + '">' + text + '</div>';
		}

		/*--------------------------------------------------------------
		## Removes Site Build Option from admin screen
		--------------------------------------------------------------*/
		function removeSiteBuildStage( e ) {
			var siteBuildStageContainer = $( this ).closest( '.site-build-stage' );
			siteBuildStageContainer.remove();
		}
		// Add initial event listeners to removeSiteBuildStage buttons
		$( '.remove-site-build-stage' ).on( 'click', removeSiteBuildStage );

		/*--------------------------------------------------------------
		## Adds Site Build Option on admin screen
		--------------------------------------------------------------*/
		function addSiteBuildStage( e ) {
			e.preventDefault();

			// REFACTOR INPUT VALIDATION WITH BETTER SYSTEM AS PLUGIN GROWS

			// Input validation
			var lastInput = $( '.site-build-stage__name' ).last();
			var lastInputHasValue = lastInput.val().length <= 0;
			var siteBuildNameError = $( '.red-error-message.site-build-stage__name--error' );

			if ( lastInputHasValue ) {
				var noValueErrorMessage = 'Please enter a site build stage.';

					if ( siteBuildNameError.length <= 0 ) {
						$( displayErrorMessage( noValueErrorMessage, 'site-build-stage__name--error' ) ).insertAfter( e.target );
						lastInput.addClass( 'red-error' );
					}

				return false;
			} else if ( siteBuildNameError.length > 0 ) {
				siteBuildNameError.remove();
				lastInput.removeClass( 'red-error' );
			}
			// END REFACTOR INPUT VALIDATION WITH BETTER SYSTEM AS PLUGIN GROWS

			// Data from PHP
			var emptyInput = $( site_build_stage.empty_input );
			var removeButton = $( site_build_stage.remove_button );
			var progressSelect = $( site_build_stage.progress_select );
			var linkSelect = $( site_build_stage.link_select );

			// Current Site Build Stage Number
			var currentSiteBuildStageNumber = $('.site-build-stage').length - 1;

			// Create an input in WP Admin for our new site build stage from the last site build stage input
			var newSiteBuildStage = $( '.site-build-stage' ).last();

			// Empty Site Build Input Modification
			var newSiteBuildStageInput = newSiteBuildStage.find( 'input' );
			var newSiteBuildStageInputLabel = newSiteBuildStageInput.parent( 'label' );

			// Empty Link Input Modification
			var newSiteBuildStageLinkInput = linkSelect.find( 'input' );

			// Add name attribute for WordPress admin get_option() value
			newSiteBuildStageInput.attr('name', 'define_site_build_stages[' + currentSiteBuildStageNumber + '][name]');
			progressSelect.attr('name', 'define_site_build_stages[' + currentSiteBuildStageNumber + '][progress]');
			newSiteBuildStageLinkInput.attr('name', 'define_site_build_stages[' + currentSiteBuildStageNumber + '][link]');

			// Insert remove button and select element to new input
			// removeButton.on( 'click', removeSiteBuildStage ).insertAfter( newSiteBuildStageInputLabel );
			// progressSelect.insertAfter( newSiteBuildStageInputLabel );
			// linkSelect.insertAfter( progressSelect );
			newSiteBuildStage.append( progressSelect );
			newSiteBuildStage.append( linkSelect );
			newSiteBuildStage.append( removeButton.on( 'click', removeSiteBuildStage ) );

			// Insert empty input before "Add Stage" button
			emptyInput.insertBefore( $( this ) );
		}
		// Add initial event listeners to addSiteBuildStage buttons
		$( '.add-site-build-stage' ).on( 'click', addSiteBuildStage );

	});

})( jQuery );
