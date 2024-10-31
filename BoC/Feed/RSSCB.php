<?php

namespace BoC\Feed;

/****** need to figure out how to get /feed/rss-cb/ in the url instead of ?feed=rss-cb *******/
class RSSCB
{

    /**
     * Add the rsscb feed, shortcode and textdomain
     */
    public static function init()
    {
        add_action('do_feed_rsscb', array(__CLASS__, 'doFeed'));
        add_shortcode('rsscb_link', array(__CLASS__, 'shortcode'));

        add_filter(
            'init',
            function () {
                load_plugin_textdomain('rsscb-feed', false, dirname(plugin_basename(__FILE__)) . '/languages/');
            }
        );
    }

    /**
     * Creates a url linking to a rsscb feed.
     * @param  array  $tax_terms An associative array of taxonomy terms
     * @return string            The url of the feed
     */
    public static function url ($tax_terms = array())
    {
        $link = home_url('/');
        $link .= '?feed=rsscb';
        $taxonomies = get_taxonomies();
        foreach ($tax_terms as $tax => $terms) { // add all of the terms in each taxonomy
            if (!array_key_exists($tax, $taxonomies)) {
                continue;
            }
            $link .= "&$tax=";
            if (is_array($terms)) {
                $link .= implode('+', $terms);
            } else {
                $link .= $terms;
            }
        }
        return $link;
    }
    
    /**
     * handles [rsscb_link] shortcode instances.
     * Examples: 
     * [rsscb_link /]                      creates a link with all posts
     * [rsscb_link content_type=speeches]  creates a link using posts that have a content_type of "speeches"
     *
     * @param  array  $atts    attributes that the shortcode includes.
     * @param  string $content the content of the shortcode between the begin and end tags
     * @return string          a link tag pointing to the feed
     */
    public static function shortcode ($atts, $content = null)
    {
        if (!is_array($atts)) {
            $atts = array();
        }
        if ($content == null) {
            $content = __('RSS-CB feed', 'rsscb-feed');
        }
        return '<a href="' . RSSCB::url($atts) . '" class="rsscb">' . $content . '</a>';
    }

    /**
     * Loads the iCalendar template
     */
    public static function doFeed()
    {
        load_template(RSSCB_FEEDS_DIR . '/feed-rsscb.php');
    }
}
