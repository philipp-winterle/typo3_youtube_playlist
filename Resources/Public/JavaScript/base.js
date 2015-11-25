jQuery;

define(
	//The name of this module
	"tx_youtube_playlist_base",

	//The array of dependencies
	["jquery"],

	//The function to execute when all dependencies have loaded. The
	//arguments to this function are the array of dependencies mentioned
	//above.
	function () {
		jQuery = TYPO3.jQuery;

		/**
		 * This file is for usage with youtube playlist only
		 * Copyright: Philipp Winterle
		 */
		TYPO3.jQuery( document ).ready( function ( ) {
			"use strict";
			var container = TYPO3.jQuery( ".yt_pl .yt_pl_playlists_selector" );
			var listElements = container.find( "li" );
			var selectedPlaylists = [];

			var initialize = function() {
				selectedPlaylists = getSelectedPlaylists();

				// Initialize the selector
				listElements.each( function ( index, elem ) {
					elem = TYPO3.jQuery( elem );

					var playlistId = elem.attr( "data-id" );
					if ( selectedPlaylists.indexOf(playlistId) !== -1) {
						elem.addClass( "selected" );
					}

					// interate over the elements and set the counter if available
					setSelectedVideoOrderNumber(  );

					elem.click( function () {
						elem.toggleClass( "selected" );
						var indexInArray = selectedPlaylists.indexOf( playlistId );
						if ( indexInArray === -1 ) {
							selectedPlaylists.push( playlistId );
						} else {
							selectedPlaylists.splice( indexInArray, 1 );
						}

						setSelectedVideoOrderNumber(  );

						setSelectedPlaylistsValue( selectedPlaylists );
					} );

				} );
			};

			var getSelectedPlaylists = function () {
				var playlistsString = TYPO3.jQuery( "#yt_pl_selectedPlaylists" ).val();
				var playListArray = [];
				if ( typeof playlistsString.split === "function" && playlistsString.length > 0 ) {
					playListArray = playlistsString.split( "," );
				}
				return playListArray;
			};

			var setSelectedPlaylistsValue = function(playlistArray) {
				var playlistsString;
				if (typeof playlistArray.join === "function") {
					playlistsString = playlistArray.join( "," );
				}
				TYPO3.jQuery("#yt_pl_selectedPlaylists" ).val(playlistsString);
			};

			var setSelectedVideoOrderNumber = function() {
				listElements.each(function(index,elem) {
					elem = TYPO3.jQuery( elem );
					var playlistId = elem.attr( "data-id" );
					var indexInArray = selectedPlaylists.indexOf( playlistId );
					if ( indexInArray !== -1 ) {
						elem.find( ".yt_pl_thumbnail .yt_pl_selection_number" ).html( indexInArray + 1 );
					} else {
						elem.find( ".yt_pl_thumbnail .yt_pl_selection_number" ).html( "" );
					}
				});
			};

			// Start
			initialize();
		} );
	}
);