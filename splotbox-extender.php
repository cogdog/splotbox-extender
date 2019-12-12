<?php
/*
Plugin Name: SPLOTbox Extender
Plugin URI: https://github.com/cogdog/splotbox-extender
Description: With some elbow grease, you can extend the functionality of a SPLOTbox site to support more media sites than the original theme. This plugin is a template and should be edited for your own site use.
Version: 0.3
License: GPLv2
Author: Alan Levine
Author URI: https://cog.dog
*/

defined( 'ABSPATH' ) or die( 'Plugin file cannot be accessed directly.' );


// just a function we can check to see if this plugin is loaded
function splotboxplus_exists() {
	return TRUE;
}

// Customize the functions below to add support for other media sites


function splotboxplus_supports() {
	/* array of names of all sites added via this plugin, used for display on share form
       called by SPLOTbox includes/media.php --> splotbox_supports()
       
	   $supports = array('Metacafe', 'Transistor');
	   $supports = array(); for none
	*/
	
	$supports = array();
	
	return $supports;
}

function splotboxplus_video_allowables() {
	/* add the domain fragments to identify supported video type URLs
	   called by SPLOTbox includes/media.php --> url_is_video ( $url )
	   
	   $allowables = array('metacafe.com', 'someother.com/video);
	   $allowables = array(); for none
	*/
	
	$allowables = array();

	return $allowables;
}


function splotboxplus_audio_allowables() {
	/* add domain fragments to identify supported audio type URLs
	   called by SPLOTbox includes/media.php --> url_is_audio ( $url )
	   
	   $allowables = array('transistor.fm', 'soundsite.net/sounds);
	   $allowables = array(); for none
	*/
	$allowables = array();

	return $allowables;
}

function splotboxplus_image_allowables() {
	/* add domain fragments to identify supported image type URLs
	   called by SPLOTbox includes/media.php --> url_is_image ( $url )
	   
	   $allowables = array('imgur.com')
	   $allowables = array(); for none
	*/
	
	$allowables = array();

	return $allowables;
}

function splotboxplus_embed_allowables() {
	/* add domain fragments to identify string match supported embeddable media beyond
	   ones supported by SPLOTbox
	   e.g. from https://wordpress.org/support/article/embeds/#okay-so-what-sites-can-i-embed-from
	   called by SPLOTbox includes/media.php --> is_url_embeddable( $url )
	
	   $allowables = array('dailymotion.com', 'imgur.com');
	   $allowables = array(); for none
	*/

	$allowables = array();

	return $allowables;
}


function  splotboxplus_get_videoplayer( $url ) {
	/*	Custom functions for creating embed codes from URLs, e.g. for 
	    ones not supported directly by WordPress. Generally this is parsing
	    the media URL for codes used to return an iframe HYTML to embed content.
	    
	    Somewhat modeled after https://codex.wordpress.org/Function_Reference/wp_embed_register_handler
	    w/o using filters.
	    
	    Mote: the function has "video" in it but can be any media site thatb provides embed code  
	*/

	// The ones below are provided as examples
	
	/*
	// transistor convert url to embed
	 if ( is_in_url( 'share.transistor.fm', $url ) ) {
	
		// substition to get embed URL
		$embed_url = str_replace ( '.fm/s/' , '.fm/e/' , $url );
	
		return ('<iframe src="' . $embed_url . '" width="100%" height="180" frameborder="0" scrolling="no" seamless="true" style="width:100%; height:180px;"></iframe>');
		
	} 
	
	// metacafe convert url to embed
	 if ( is_in_url( 'metacafe.com/watch', $url ) ) {
	
		// substition to get embed URL
		$metacafe_url = str_replace ( 'watch' , 'embed' , $url );
	
		return ('<iframe width="560" height="315" src="' . $metacafe_url . '?autostart=0" frameborder="0"  allowfullscreen></iframe>');
		
	// Internet Archive (already supported by main SPLOTbox, code here for example onlu
	
	if ( is_in_url( 'archive.org', $url ) ) {
	
		$archiveorg_url = str_replace ( 'details' , 'embed' , $url );
	
		return ('<iframe src="' . $archiveorg_url . '" width="640" height="480" frameborder="0" webkitallowfullscreen="true" mozallowfullscreen="true" allowfullscreen></iframe>');
		
		*/
	} 
	
	
	// none used
	return '';

}
?>