=== Plugin Name ===
Contributors: jrevillini
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=QBHP7AUYRQ5S2
Tags: css, browser, user agent, sniffer, caching, cache
Requires at least: 3.5.1
Tested up to: 3.5.1
Stable tag: 1.0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This is an add-on plugin which lets Awebsome! Broswer Selector work on sites that employ caching.

== Description ==

= Quick, Non-technical Overview = 
If you use the Awebsome! Broswer Selector plugin AND a caching plugin (e.g. WP SuperCache), the wrong body classes will be served to your site visitors and could cause your site to render incorrectly. This plugin overcomes that limitation of ABS!

= Technical Details =
ABS adds classes to the body of your site using Wordpress hooks; this is a problem for caching sites because you do not want those classes cached and served to other clients as they will be inaccurate (e.g. a Mozilla Firefox user could get served a page with body classes that indicate that they use Safari). The result can range from rendering your site incorrectly to the wrong code being executed. This plugin leverages some of the functionality of ABS to generate accurate classes *dynamically* on every page load and add them to the body class attribute using JavaScript and AJAX (leverages jQuery).

= Please Note = 
This plugin *must* be installed in conjunction with ABS 2.2 or higher.

[Awebsome! Broswer Selector for Caching Plugin Page](http://wp.me/Pioil-Rs)

== Installation ==

1. Install the plugin from your wordpress plugin manager or upload the plugin ZIP file to the plugin manager.
1. Activate the plugin.
1. Recommended: Clear your Wordpress cache; follow your caching plugin's documentation for instructions on how to do this.

== Frequently Asked Questions ==

= How do I know it's working? =

Inspect the classes applied to the body tag and make sure you see 'awebsome-bs-for-caching' in there somewhere.

There are several ways to do this:

* JavaScript URL bar method - while on your site, in the address bar, paste this code and hit return: `javascript:(function(){alert(document.getElementsByTagName('body')[0].getAttribute('class'))})();void(0);`
* JavaScript Console method - paste this into the JS console and hit return: `(function(){alert(document.getElementsByTagName('body')[0].getAttribute('class'))})();`

== Screenshots ==

This plugin has no user interface. Just activate it and you're all set.

== Changelog ==

= 1.0.2
* BUGFIX http/https select for ajax call for browser classes

= 1.0.1 = 
* removed a debugging function I accidentally left outside the class scope

= 1.0 =
* Initial release