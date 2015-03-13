'use strict';

var tag = document.createElement('script');

tag.src = "https://www.youtube.com/iframe_api";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
var ytplayer;
function onYouTubeIframeAPIReady() {
        ytplayer = new YT.Player('player', {
          events: {
            'onReady': onYouTubePlayerReady,
            'onStateChange': onPlayerStateChange
          }
        });
      }

function play() {
  if (ytplayer) {
    ytplayer.playVideo();
  }
}

function onYouTubePlayerReady(event) {
 event.target.playVideo();
}
 var done = false;
      
function onPlayerStateChange(newState) {
	  if(newState.data==5)
		{ newState='video cued'; }
		else if(newState.data==3)
		{ newState='buffering'; }
	  else if(newState.data==2)
		{ newState='paused'; }
		else if(newState.data==1)
		{ newState='playing'; }
		else if(newState.data==0)
		{ newState='ended'; }
		else if(newState.data==-1)
		{ newState='unstarted'; }
		else
		{ newState='Status uncertain'; } 

		console.log("Player's new state: " + newState + " \n\ntime: " + eventDate + " \n\ntitle: " + ytplayer.getVideoUrl());

		var uRL = window.ltG.priv.fGetCurURL();
  	var pageTitle = window.ltG.priv.fGetCurPageTitle();
  	var msg = window.ltG.fGenClickMsg(uRL, newState);
  	window.ltG.priv.fSendMsg(msg);
 }

function loadnewvideo(videoid)
{
ytplayer.loadVideoById(""+videoid+"", 5, "large");
}


var eventDate = new Date();
