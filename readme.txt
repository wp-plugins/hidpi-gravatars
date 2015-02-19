=== HiDPI Gravatars ===
Contributors: miqrogroove
Tags: retina, hidpi, gravatar, gravatars, avatar, avatars, iPad, iPhone, Kindle
Requires at least: 2.8
Tested up to: 4.1
Stable tag: 1.4

Enables high resolution Gravatar images on any browser that supports them.

== Description ==

Automatically replaces the standard resolution Gravatars with HiDPI (Retina) Gravatars using HTML (when supported) or Javascript (as needed).

You need this plugin if you want blog comments to look crisp and clear on Retina, HD, and similar devices!

== Installation ==

1. Upload the `hidpi-gravatars` directory to your `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

This is a zero-configuration plugin.  There are no settings.

Deactivation removes everything except the files you uploaded.  There is no "uninstall" necessary.

Personal avatar note:  For best results when uploading a new avatar, use an image at least 128 x 128 pixels in size.  The old "standard" size of 80 pixels will be inadequate on Retina displays.

== Changelog ==

= 1.4 =
* Enhanced for newer browsers, released 19 February 2015.
* Added detection of srcset support in new browsers.
* Javascript manipulation is minimized when srcset can be used.
* Falls back gracefully to JS code in older HiDPI browsers.
* Upgrade Note: Cached pages will not be HiDPI until regenerated.  Please check your settings if you have a server-side cache plugin.

= 1.3 =
* Compatibility improvement, released 23 November 2012.
* Better detection of which pages have Gravatars.
* Added a definable trigger for theme and plugin authors to use as needed.
* Added troubleshooting documentation.
* WordPress 3.5-RC1 tested.
* WordPress 3.6-RC1 tested 16 July 2013.
* WordPress 3.7.1 tested 31 October 2013.
* WordPress 3.8-RC1 tested 7 December 2013.
* WordPress 3.9-RC1 tested 9 April 2014.
* WordPress 4.0-beta4 tested 21 August 2014.
* WordPress 4.1-RC1 tested 13 December 2014.

= 1.2 =
* Bug fixes, released 16 November 2012.
* Added support for the Gravatars in admin ajax actions, such as comment replies.
* Added support for the Mystery Man setting.

= 1.1 =
* Compatibility fix, released 10 November 2012.
* Adjusted to include devices that have a 1.5:1 pixel ratio.
* Tested on Kindle Fire HD emulator.

= 1.0 =
* Stable version, released 31 October 2012.
* Added support for the Gravatars on the admin bar.

= 0.2 =
* Beta version, released 31 October 2012.

== Theme Requirements ==

You may not omit the [wp_head](http://codex.wordpress.org/Function_Reference/wp_head) and [wp_footer](http://codex.wordpress.org/Function_Reference/wp_footer) template tags for this plugin to work correctly.

== Cache Compatibility ==

HiDPI Gravatars is designed to be fully compatible with page caching plugins such as WP Super Cache.

Pages that were cached prior to activating HiDPI Gravatars will need to be refreshed.  Empty the cache to make sure the new Gravatars will appear.

== Other Gravatar Plugins ==

HiDPI Gravatars might not detect customized Gravatar functions in other plugins.  For extra flexibility, I made it possible for a theme or plugin to trigger the HiDPI Gravatars on a specific page using this code:  

`
<?php define('MIQRO_HIDPI_THIS_PAGE', TRUE); ?>

`
For most websites this will *not* be necessary.

Plugin authors: If you are not implementing the WordPress [get_avatar](http://codex.wordpress.org/Function_Reference/get_avatar) filter, then you will need to add my code (as above) whenever a Gravatar is output.  Either method should make your plugin compatible.  Note, however, only the get_avatar filter is ajax compatible.

In any case, HiDPI Gravatars is *not* compatible with any Gravatar cache plugins.