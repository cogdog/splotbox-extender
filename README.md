# SPLOTbox Extender Plugin

A WordPress plugin that extends the range of media sites supported by the SPLOTbox theme.

by Alan Levine https://cog.dog or http://cogdogblog.com/

-----
*If this kind of stuff has any value to you, please consider supporting me so I can do more!*

[![Support me on Patreon](http://cogdog.github.io/images/badge-patreon.png)](https://patreon.com/cogdog) [![Support me on via PayPal](http://cogdog.github.io/images/badge-paypal.png)](https://paypal.me/cogdog)

----- 

## What is this?

The [SPLOTbox WordPress theme](https://github.com/cogdog/splotbox) supports collection of media from external media services simply by providing a URL to where it exists there (e.g. the URL to a YouTube video page or a Giphy entry). 

"Out of the box" this theme is able to embed media for a number of the ones [WordPress supports by Embed](https://wordpress.org/support/article/embeds/#okay-so-what-sites-can-i-embed-from) plus the following that have been added to the theme by custom code

* Internet Archive (audio or video)
* Adobe Spark Pages
* Adobe Spark Video

The aim has to provide support in the SPLOTbox theme for a reasonable number of media sites of common interest. 

This plugin provides a way to include support for additional services not currently used in SPLOTBox. You can see it in action at the main SPLOTbox demo site http://splot.ca/box

Adding this kind of support will require some proficiency in PHP and perhaps even regular expressions for pattern matching / replacement. Gotta get you code hands dirty with this one. 

This can happen in three ways, with examples detailed more below.

1. Add support for [others that WordPress supports ](https://wordpress.org/support/article/embeds/#okay-so-what-sites-can-i-embed-from) that are not made available in the theme. Perhaps it is [Daily Motion](http://dailymotion.com) for video. Why are they not all in there? Well the list is long and would clutter the interface, and many of them are pretty niche interest for general use. Yes, this developer has executed some editorial decisions on the SPLOTbox theme.
2. Create custom code to add support for additional sites. This is a case where the URL for a media item can be parsed to identify the source, e.g. for Internet Archive built into the theme. The other requirement is that this URL can be parsed to extract a single item reference ID that can be used to build the iframe embed code.

For example, the way this is done built in for audio/video from the internet archive (all urls include `archive.org/details`), a link for a Tom and Jerry video

    https://archive.org/details/Jolly_Fish_1932

uses the following embed code:

    <iframe src="https://archive.org/embed/Jolly_Fish_1932" 
     width="640" height="480" frameborder="0" 
     webkitallowfullscreen="true" mozallowfullscreen="true" 
     allowfullscreen></iframe>

So we can see the pattern for creating the embed code is to use the URL for `src=""` but replace `details` with `embed`.

3. For services that offer oEmbed URLs, you can add code that adds these sites as additional providers (e.k. [Kaltura media servers](https://www.kaltura.org)). Again, each source needs to be added in this plugin. 


## More Template than Plugin

On it's own, adding this plugin will not do anything for your SPLOTbox! 

It needs to be customized to add support for the sites being added, before being uploaded to your site. Proceed at your own risk. 

### Register Name of the Service

Modify `splotboxplus_supports()` to include all services added in this plugin. This is used to indicate support on the sharing form (see [example](http://splot.ca/box)). 
```
function splotboxplus_supports() {
	// Names of all sites supported via by this plugin
	// $supports = array('Metacafe', 'Transistor', 'Imgur', );

	// e.g.
	// $supports = array('Metacafe', 'Transistor', 'Imgur', 'Big Kaltura' );

	$supports = array('Animoto', 'Metacafe', 'Transistor', 'Imgur', 'Daily Motion', 'BC Campus Kaltura', 'KPU Kaltura');

	return $supports;
}
```

If there are no values here, then there's no reason to use the plugin!


### Identify URL Pattern For Type of Media
To verify URLs and indicate the media type (audio, video, or image) add to the appropriate function a URL pattern than indicates a site as a media type.

```
function splotboxplus_video_allowables() {
	// Add domain match strings to identify supported video type URLs
	// e.g. $allowables = array('animoto.com', 'dailymotion.com', 'metacafe.com', 'video.bigu.ca/media');
	// $allowables = array(); for none

	$allowables = array('animoto.com', 'dailymotion.com', 'metacafe.com', 'video.bccampus.ca/media', 'media.kpu.ca/media');

	return $allowables;
}


function splotboxplus_audio_allowables() {
	// Add domain match strings to identify supported audio type URLs
	// e.g.
	// $allowables = array('share.transistor.fm');
	// $allowables = array(); for none

	$allowables = array('share.transistor.fm');

	return $allowables;
}

function splotboxplus_image_allowables() {
	// Add domain match strings to identify supported image type URLs
	// $allowables = array(); for none
	// $allowables = array('imgur.com')

	$allowables = array('imgur.com');

	return $allowables;
}
```

Any of these can be undone by using

```
	// $allowables = array(); for none
```


### Identify URL Pattern For WordPress Embed or oEmbed

This function requires a URL pattern that identifies a web address as one supported by WordPress built in embed support OR one added later in the plugin as an oEmbed Provider. In this example, we have a mix of both types.

```
function splotboxplus_embed_allowables() {
	// add domain fragments to identify WordPress supported embeddable media beyond
	// YouTube, vimeo, soundcloud, TED, giphy
	// from https://wordpress.org/support/article/embeds/#okay-so-what-sites-can-i-embed-from

	// as well as ones added as oembed providers via splotboxplus_add_oembed_handlers()

	// e.g. $allowables = array('dailymotion.com', 'imgur.com', 'video.bccampus.ca/media');
	// $allowables = array(); for none

	$allowables = array('dailymotion.com', 'animoto.com', 'imgur.com', 'video.bccampus.ca/media', 'media.kpu.ca/media');

	return $allowables;
}
```

This can be undone by using

```
	// $allowables = array(); for none
```

### Add Support for oEmbed Providers

Any service using it's own oEmbed provider needs to be added as well. The trickiest part here is getting the pattern match! Each additional server needs an entry. These examples are for Kaltura media servers (using the patterns that allow users to enter the URL for the content, no need to fish for oEmbed URLs)

```
// here we set up oEmbed providers
// comment this line out if no oEmbed providers are used or to disable
add_action( 'init', 'splotboxplus_add_oembed_handlers');


function splotboxplus_add_oembed_handlers(){
	// add/edit this statement as needed to match the oembed format of whatever service is added
	//    c.f. https://developer.wordpress.org/reference/functions/wp_oembed_add_provider/
	//
	//    e.g.
	// wp_oembed_add_provider( 'https://video.bccampus.ca/media/*', 'https://video.bccampus.ca/oembed/', false );

	// BC Campus Kaltura
    wp_oembed_add_provider( 'https://video.bccampus.ca/media/*', 'https://video.bccampus.ca/oembed/', false );

    // KPU Kaltura
    wp_oembed_add_provider( 'https://media.kpu.ca/media/*', 'https://media.kpu.ca/oembed/', false );
}

```

Enabling oEmbed from Kaltura requires [administrative settings on the server](https://knowledge.kaltura.com/help/grab-embed-option-added-to-kaf-based-applications). 

## Extending For WordPress Supported Embed sites



### Extending For Other Sites With URLs that can be Pattern Matched to Embed `

These qre for sites not supported by WordPress embeds nor offer an oEmbed provider. Support can be achieved by custom functions to parse a URL for a specifif media site in way to determine it's embed code.

This requires a bit more code work. For this example, we are going to add support to the for the Podcast service [Transistor.fm](http://transistor.fm) - see this in action at Chad Flinn's [Open Pedagogy Playlist](http://openpedagogyplaylist.com/).


For each service we are writing a custom link to embed interpreter, we add an entry to the function `splotboxplus_get_mediaplayer( $url )` each one is invoked by pattern matching the incoming URL

```
function  splotboxplus_get_mediaplayer( $url ) {
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
```

### Get the gist of it

* [splotbox-extender-splotca.php](https://gist.github.com/cogdog/3c26a103c020b1835c38547db6a534fd) You can look at version of the SPLOTbox Extender plugin in use at http://splot.ca/box


