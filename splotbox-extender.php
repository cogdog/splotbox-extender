<?php
/*
Plugin Name: SPLOTbox Extender for Starscapes
Plugin URI: https://github.com/cogdog/splotbox-extender
Description: Adds Kaltura support specifically for our friends at LCC
Version: 0.63
License: GPLv2
Author: Alan Levine
Author URI: https://cog.dog
*/

defined( 'ABSPATH' ) or die( 'Plugin file cannot be accessed directly.' );


// just a function we can check to see if this plugin is loaded
function splotboxplus_exists() {
	return TRUE;
}

function splotboxplus_supports( ) {
	// Names of all sites supported via by this plugin
	// $supports = array('Metacafe', 'Transistor', 'Imgur', );
	// $supports = array(); for none

	// e.g.
	// $supports = array('Metacafe', 'Transistor', 'Imgur', 'Big Kaltura^' );

	$supports = array('LCC Kaltura');

	return $supports;
}

function splotboxplus_video_allowables() {
	// Add domain match strings to identify supported video type URLs
	// e.g. $allowables = array('animoto.com', 'dailymotion.com', 'metacafe.com', 'video.bigu.ca/id');
	// $allowables = array(); for none

	$allowables = array('mediaspace.lcc.edu/media');

	return $allowables;
}


function splotboxplus_audio_allowables() {
	// Add domain match strings to identify supported audio type URLs
	// e.g.
	// $allowables = array('share.transistor.fm');
	// $allowables = array(); for none

	//$allowables = array('share.transistor.fm');

	return $allowables;
}

function splotboxplus_image_allowables() {
	// Add domain match strings to identify supported image type URLs
	// $allowables = array(); for none
	// $allowables = array('imgur.com')

	//$allowables = array('imgur.com');

	return $allowables;
}


function splotboxplus_embed_allowables() {
	// add domain fragments to identify WordPress supported embeddable media beyond
	// YouTube, vimeo, soundcloud, TED, giphy
	// from https://wordpress.org/support/article/embeds/#okay-so-what-sites-can-i-embed-from

	// as well as ones added as oembed providers via splotboxplus_add_oembed_handlers()

	// e.g. $allowables = array('dailymotion.com', 'imgur.com', 'video.bccampus.ca/id');
	// $allowables = array(); for none


	$allowables = array('mediaspace.lcc.edu/media');

	return $allowables;
}

// here we set up oEmbed providers
// comment this line out if no oEmbed providers used
add_action( 'init', 'splotboxplus_add_oembed_handlers');

function splotboxplus_add_oembed_handlers(){
	// add/edit this statement as needed to match the oembed format of whatever service is added
	//    c.f. https://developer.wordpress.org/reference/functions/wp_oembed_add_provider/
	//
	//    e.g.
	// wp_oembed_add_provider( 'https://video.bccampus.ca/id/*', 'https://video.bccampus.ca/oembed/', false );


	// LCC Kaltura
    wp_oembed_add_provider( 'https://mediaspace.lcc.edu/media/*', 'https://mediaspace.lcc.edu/oembed/', false );
}


function  splotboxplus_get_mediaplayer( $url ) {
	// convert media URL to embed code for sites not supported by automatic embeds
	// these will need to be constructed to find a match pattern via string replace or regex ex

	// begin check for each custom player type, here is a sample
	 if ( is_in_url( 'metacafe.com/watch', $url ) ) {

		// substition to get embed URL
		$metacafe_url = str_replace ( 'watch' , 'embed' , $url );

		return ('<iframe width="560" height="315" src="' . $metacafe_url . '?autostart=0" frameborder="0"  allowfullscreen></iframe>');
	}

	if ( is_in_url( 'share.transistor.fm', $url ) ) {

		// substition to get embed URL
		$embed_url = str_replace ( '.fm/s/' , '.fm/e/' , $url );

		return ('<iframe src="' . $embed_url . '" width="100%" height="180" frameborder="0" scrolling="no" seamless="true" style="width:100%; height:180px;"></iframe>');

	}

	// nothing else
	return '';
}

function  splotboxplus_get_videoplayer( $url ) {
	// catch for older versions of plugin that used this function name
	splotboxplus_get_mediaplayer( $url );
}
?>
