(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-specific JavaScript source
	 * should reside in this file.
	 *
	 * Note that this assume you're going to use jQuery, so it prepares
	 * the $ function reference to be used within the scope of this
	 * function.
	 *
	 * From here, you're able to define handlers for when the DOM is
	 * ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * Or when the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and so on.
	 *
	 * Remember that ideally, we should not attach any more than a single DOM-ready or window-load handler
	 * for any particular page. Though other scripts in WordPress core, other plugins, and other themes may
	 * be doing this, we should try to minimize doing that in our own work.
	 */


function onPlayerReady(event) {
  var embedCode = event.target.getVideoEmbedCode();
  event.target.playVideo();
  if (document.getElementById('embed-code')) {
    document.getElementById('embed-code').innerHTML = embedCode;
  }
}

var params = {
    allowScriptAccess: "always"
};
var atts = {
    id: "myytplayer"
};

var video = swfobject.embedSWF("http://www.youtube.com/v/elvOZm0d4H0?enablejsapi=1&playerapiid=ytplayer&version=3&rel=0&autoplay=1&controls=1", "ytapiplayer", "450", "250", "8", null, null, params, atts);

onYouTubePlayerReady = function (playerId) {
    ytplayer = document.getElementById("myytplayer");
    ytplayer.addEventListener("onStateChange", "onPlayerStateChange");
};

onPlayerStateChange = function (state) {
    if (state === 0) {
        alert("Stack Overflow rocks!");
    }
};

console.log("loaded");


})( jQuery );
