# SPLOTbox Extender Plugin

A WordPress plugin that extends the range of media sites supported by the SPLOTbox theme.

by Alan Levine https://cog.dog or http://cogdogblog.com/

-----
*If this kind of stuff has any value to you, please consider supporting me so I can do more!*

[![Support me on Patreon](http://cogdog.github.io/images/badge-patreon.png)](https://patreon.com/cogdog) [![Support me on via PayPal](http://cogdog.github.io/images/badge-paypal.png)](https://paypal.me/cogdog)

----- 

## What is this?

The SPLOTbox theme supports collection of media from external media services simply by providing a URL to where it exists there (e.g. the URL to a YouTube video page or a Giphy entry). "Out of the box" this theme is able to embed media for a number of the ones [WordPress supports by Embed](https://wordpress.org/support/article/embeds/#okay-so-what-sites-can-i-embed-from) plus the following that have been added to the theme by custom code

* Internet Archive (audio or video)
* Adobe Spark Pages
* Adobe Spark Video

The aim has to provide support for a reasonable number of media sites of common interest. But often, a site may want to either add additional ones from the [WordPress supported list](https://wordpress.org/support/article/embeds/#okay-so-what-sites-can-i-embed-from) or write the special code needed to add support for other sites.

For the former case, it's a matter of choosing the medis site to add, perhaps it is Daily Motion for video

For the latter case, the URL for a given piece of media on the site should be able to be parsed for an id string that can be substituted into the embed code the site provided. For example, the way this is done built in for audio/video from the internet archive, a link for a Tom and Jerry video

    https://archive.org/details/Jolly_Fish_1932

uses the following embed code:

    <iframe src="https://archive.org/embed/Jolly_Fish_1932" 
     width="640" height="480" frameborder="0" 
     webkitallowfullscreen="true" mozallowfullscreen="true" 
     allowfullscreen></iframe>

So we can see the pattern for creating the embed code is to use the URL for `src=""` but replace `details` with `embed`.

This plugin provides a way to include support for additional services not currently used in SPLOTBox. You can see it in action at the main SPLOTbox demo site http://splot.ca/box

Adding this kind of support will require some proficiency in PHP and perhaps even regular expressions for pattern matching / replacement. Gotta get you code hands dirty with this one.

## More Template than Plugin

On it's own, adding this plugin will not do anything for your SPLOTbox! It needs to be customized to add support for the sites being added, before being uploaded to your site. Proceed at your own risk. If you seek support for your own customizations, contact me.

## Extending For WordPress Supported Embed sites

This section describes the code needed to add support from the [list of WordPress Embed supported sites](https://wordpress.org/support/article/embeds/#okay-so-what-sites-can-i-embed-from) for example, video from Daily Motion (http://dailymotion.com/) and Animoto (http://animoto.com/) and images/gifs from Imgur (http://imgur.com).

We first modify the `splotboxplus_supports()` function - this provides an array of the names of all services added by this plugin. The order does not matter (the theme will sort alphabetically)

````
function splotboxplus_supports() {
	/* array of names of all sites added via this plugin, used for display on share form
       called by SPLOTbox includes/media.php --> splotbox_supports()
	   $supports = array(); for none
	*/
	
	$supports = array('Animoto', 'Imgur', 'Daily Motion');
	return $supports;
}
````

Next, we modify `splotboxplus_video_allowables()` to list the URL fragments that should be identified as video format (the order does not matter). This would be the URL patterns for content from MetaCafe and Daily Motion.

````
function splotboxplus_video_allowables() {
	/* add the domain fragments to identify supported video type URLs
	   called by SPLOTbox includes/media.php --> url_is_video ( $url )
	   
	   $allowables = array(); for none
	*/
	
	$allowables = array('animoto.com', 'dailymotion.com');

	return $allowables;
}
````

Then, we modify `splotboxplus_image_allowables()` to include the URL fragment for the one image site we are adding.

````
function splotboxplus_image_allowables() {
	/* add domain fragments to identify supported image type URLs
	   called by SPLOTbox includes/media.php --> url_is_image ( $url )
	   
	   $allowables = array(); for none
	*/
	
	$allowables = array('imgur.com')

	return $allowables;
}
````

Now we make sure all of the WordPress supported services we ae added are included in the `splotboxplus_embed_allowables()` function

````
function splotboxplus_embed_allowables() {
	/* add domain fragments to identify string match supported embeddable media beyond
	   ones supported by SPLOTbox
	   e.g. from https://wordpress.org/support/article/embeds/#okay-so-what-sites-can-i-embed-from
	   called by SPLOTbox includes/media.php --> is_url_embeddable( $url )
	
	   $allowables = array(); for none
	*/

	$allowables = array('dailymotion.com', 'animoto.com', 'imgur.com');

	return $allowables;
}

````

Any of these can be undone by changing the return value for `$supports` or `$allowables` to be `array()`.


## Extending For Other Sites With URLs that can be Pattern Matched to Embed `

Setting these up requires a bit more code work. For this example, we are going to add support to the for the Podcast service [Transistor.fm](http://transistor.fm) - see this in action at  Chad Flinn's [Open Pedagogy Playlist](http://openpedagogyplaylist.com/).

If we are adding it to the ones above, we would update the `splotboxplus_supports()` function to now read:

````
function splotboxplus_supports() {
	/* array of names of all sites added via this plugin, used for display on share form
       called by SPLOTbox includes/media.php --> splotbox_supports()
	   $supports = array(); for none
	*/
	
	$supports = array('Animoto', 'Transitor.fm', 'Imgur', 'Daily Motion');
	return $supports;
}
````

If this was the only service we were adding, this would look like:

````
function splotboxplus_supports() {
	/* array of names of all sites added via this plugin, used for display on share form
       called by SPLOTbox includes/media.php --> splotbox_supports()
	   $supports = array(); for none
	*/
	
	$supports = array('Transitor.fm');
	return $supports;
}
````

Since this is an audio service, we need to make sure it's URL pattern is included in the `splotboxplus_audio_allowables()` function:

````
function splotboxplus_audio_allowables() {
	/* add domain fragments to identify supported audio type URLs
	   called by SPLOTbox includes/media.php --> url_is_audio ( $url )
	   
	   $allowables = array(); for none
	*/
	$allowables = array('share.transistor.fm');

	return $allowables;
}
````

Finally, for each service we are writing a custom link to embed interpreter, we add an entry to the function `splotboxplus_get_videoplayer( $url )` (ignore the 'video' in the function name, it can be for any kind of service for which an embed code can be written).

````
function  splotboxplus_get_videoplayer( $url ) {
	/*	Custom functions for creating embed codes from URLs, e.g. for 
	    ones not supported directly by WordPress. Generally this is parsing
	    the media URL for codes used to return an iframe HYTML to embed content.
	    
	    Somewhat modeled after https://codex.wordpress.org/Function_Reference/wp_embed_register_handler
	    w/o using filters.
	    
	    Mote: the function has "video" in it but can be any media site thatb provides embed code  
	*/

	// transistor convert url to embed
	 if ( is_in_url( 'share.transistor.fm', $url ) ) {
	
		// substition to get embed URL
		$embed_url = str_replace ( '.fm/s/' , '.fm/e/' , $url );
	
		return ('<iframe src="' . $embed_url . '" width="100%" height="180" frameborder="0" scrolling="no" seamless="true" style="width:100%; height:180px;"></iframe>');
		
	} 
	
	// none used
	return '';

}
````

## Get the gist of it

* [splotbox-extender-splotca.php](https://gist.github.com/cogdog/3c26a103c020b1835c38547db6a534fd) You can look at version of the SPLOTbox Extender plugin in use at http://splot.ca/box
