=== RSSCB Feed ===
Contributors: michaelhall, bankofcanada
Tags: rss, feeds
Requires at least: 3.0
Tested up to: 3.5.2
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add an RSSCB option to your website feeds

== Description ==

Adds an RSSCB option to feeds.

RSS-CB is an extension of the RSS standard designed to add machine readable information to feeds.
See: http://www.cbwiki.net/wiki/index.php/RSS-CBMain

== Installation ==

1. Upload `plugin-name.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go to your site and add the URL parameter ?feed=rsscb

== Frequently Asked Questions ==

= What is RSS-CB? =

RSS-CB is an extension of the RSS standard designed to add machine readable information to feeds.
It is used by central banks to facilitate content syndication.
See: http://www.cbwiki.net/wiki/index.php/RSS-CBMain

= Can I make RSS CB my default feed? =

Yes! To change your default feed, just add the following code to your theme:

    add_filter('default_feed', function () { return 'rsscb'; });

== Changelog ==

= 1.0 =
* Initial public version
* Adds "RSSCB" feed
* Adds shortcode "rsscb_link"
