/**
 * @name: YouTube Playlist Main JS
 * @author: Winterle.P
 * Date: 14.10.2015
 * Time: 13:30
 *
 * @description: Typo3 Extension to manage YouTube Playlists Players
 *
 * @requirements: jQuery 1.11
 */

var Tx_Youtube_Playlist = function(){
	"use strict";
	// always create a valid instance and dont overwrite the globals
	if (!(this instanceof Tx_Youtube_Playlist)) {
		return new Tx_Youtube_Playlist();
	}

	this.init();
};

Tx_Youtube_Playlist.prototype.container = null;
Tx_Youtube_Playlist.prototype.videoItems = null;
Tx_Youtube_Playlist.prototype.player = null;
Tx_Youtube_Playlist.prototype.playerUrlPrefix = "//www.youtube.com/embed/";
Tx_Youtube_Playlist.prototype.playerUrlPostfix = "?autoplay=1&autohide=1&modestbranding=0&rel=0&showinfo=0&color=white";

Tx_Youtube_Playlist.prototype.init = function() {
	"use strict";
	var that = this;
	this.container = jQuery( ".yt_pl" );
	this.videoItems = this.container.find( ".videoItem" );
	this.playerContainer = this.container.find(".yt_pl_main .yt_pl_video_player_container");
	this.player = this.playerContainer.find(".yt_pl_video_player");
	this.playerInformation = this.playerContainer.find(".yt_pl_video_information");
	this.additionalLists = this.container.find(".yt_pl_additional .yt_pl_additionalList");

	this.additionalLists.each(function(index, additionalList) {
		additionalList = jQuery( additionalList );
		var videoContainerFrame = additionalList.find(".yt_pl_additionalList_videos");
		var videoContainerContent = additionalList.find( ".yt_pl_additionalList_videos section" );
		var buttons = additionalList.find(".yt_pl_additionalList_control .yt_pl_buttons");
		var buttonLeft = buttons.find( ".yt_pl_button.left" );
		var buttonRight = buttons.find( ".yt_pl_button.right" );
		var sly = new Sly( videoContainerFrame, {
			horizontal: true,
			itemNav: "basic",
			smart: true,
			nextPage: buttonRight,
			prevPage: buttonLeft,
			speed: 400,
			moveBy: 100
		} );
		sly.init();
	});



	this.videoItems.each( function ( index, elem ) {
		elem = jQuery( elem );
		//add click event
		elem.click( function () {
			that.videoItems.removeClass("playing");
			elem.addClass( "playing" );

			// set the new videoID
			var videoId = elem.attr("data-id");
			var videoTitle = elem.find(".yt_pl_video_title" ).text();
			var videoDescription = elem.find( ".yt_pl_video_description" ).text();
			that.player.attr("src", that.playerUrlPrefix + videoId + that.playerUrlPostfix);
			that.playerInformation.find( ".yt_pl_video_title" ).html( videoTitle );
			that.playerInformation.find( ".yt_pl_video_description" ).html( videoDescription );
		} );
	} );
};


jQuery(document).ready(function() {
	"use strict";

	var youtubePlaylist = new Tx_Youtube_Playlist();
});