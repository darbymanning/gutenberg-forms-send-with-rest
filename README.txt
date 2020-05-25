=== Gutenberg Forms Send with REST ===
Contributors: darbymanning
Tags: gutenberg-forms, forms-gutenberg, api, rest, wp-api, wp-rest-api, json, wp, wordpress, wp-rest-api, wordpress-rest-api
Donate link: https://www.pth.org.uk/get-involved/make-a-donation/
Requires at least: 4.8
Requires PHP: 5.4
Tested up to: 5.4.1
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Creates a WordPress REST API (POST) endpoint which creates a Gutenberg Forms entry and sends an mail.

== Description ==
Creates a [WordPress REST API](https://developer.wordpress.org/rest-api/) (POST) endpoint which creates a
[Gutenberg Forms](https://wordpress.org/plugins/forms-gutenberg/) entry and sends an email.

This plugin makes it possible to create a Gutenberg Forms entry using the WordPress API. This allows us to create
form entries programmatically. Some possible use-cases for this are:

- Using WordPress as a headless CMS (ie. using a JavaScript framework such as React, Vue, Svelte)
- Integrating an existing WordPress backend to another app or service

**See details on GitHub:** [https://github.com/darbymanning/gutenberg-forms-send-with-rest](https://github.com/darbymanning/gutenberg-forms-send-with-rest)

== Installation ==
Note that this plugin naturally has a dependency of
[Gutenberg Forms](https://wordpress.org/plugins/forms-gutenberg/).

1. Upload `gutenberg-forms-to-rest.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Changelog ==

= 1.0 =
*26 May 2020*

* Initial release
