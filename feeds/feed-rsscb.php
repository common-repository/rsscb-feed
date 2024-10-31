<?php
/**
 * iCal Feed Template for displaying iCal Posts feed.
 */
global $post, $wp_query;

$taxonomies = array();// get the list of taxonomy terms for the title
$taxs = get_taxonomies();
foreach ($taxs as $tax_slug => $tax_obj) {
    if ($wp_query->query_vars[$tax_slug]) {
        $terms = $wp_query->query_vars[$tax_slug];
        if (!is_array($terms)) {
            $terms = explode(',', $terms);
        }
        foreach ($terms as $term) {
            $term_obj = get_term_by('slug', $term, $tax_slug);
            array_push($taxonomies, $term_obj->name);
        }
    }
}
$feed = array(
    'title' => get_bloginfo('name'),
    'date' => date('c', strtotime('now')),
    'description' => '',
    'language' => 'en',
    'url' => htmlspecialchars(($_SERVER['HTTPS'] ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'])
);
if (count($taxonomies) > 0) {
    sort($taxonomies); // alphabetical listing (doesn't handle accents in french properly though)
    $feed['title'] = implode(', ', $taxonomies). ' - ' . get_bloginfo('name');
}

$feed = $feedDescription = apply_filters('rsscb_feed_properties', $feed);

// this allows themes to overwrite the output
// so that different posts can have different outputs
// (<cb:news>, <cb-event>, <cb-speech>, <cb-paper>)
$itemCallable = apply_filters('rsscb_feed_item_callback', null);

header("Content-Type: application/rss+xml; charset=UTF-8");
echo '<?xml version="1.0"?>';
?>
<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns="http://purl.org/rss/1.0/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:dcterms="http://purl.org/dc/terms/" xmlns:cb="http://www.cbwiki.net/wiki/index.php/Specification_1.2/" xmlns:georss="http://www.georss.org/georss" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.w3.org/1999/02/22-rdf-syntax-ns# rdf.xsd">
<channel rdf:about="<?= $feed['url'] ?>">
    <title><?= $feed['title'] ?></title>
    <link><?= $feed['url'] ?></link>
    <description><?= $feed['description'] ?></description>
    <items>
        <rdf:Seq>
        <?php 
        while ($wp_query->have_posts()) {
            $wp_query->the_post();
            ?>
                <rdf:li rdf:resource="<?php echo get_permalink($post->ID); ?>"/>
            <?php 
        }
        $wp_query->rewind_posts();
        ?>
        </rdf:Seq>
    </items>
    <dc:language><?= $feed['language'] ?></dc:language>
    <dc:date><?= $feed['date'] ?></dc:date>
</channel>
<?php
while (have_posts()) {
    the_post();
    if (is_callable($itemCallable)) {
        call_user_func($itemCallable);
    } else {
        $url = get_permalink($post->ID);
        $title = get_the_title($post->ID);
        $excerpt = get_the_excerpt();
        $date = date('c', strtotime($post->post_date));
        $occurrenceDate = get_the_time('Y-m-d', $post->ID);
        ?>
        <item rdf:about="<?= $url ?>">
            <title><?= $title ?></title>
            <link><?= $url ?></link>
            <description><?= $excerpt ?></description>
            <dc:date><?= $date ?></dc:date>
            <dc:language><?= $feed['language'] ?></dc:language>
            <cb:news rdf:parseType="Resource">
                <rdf:type rdf:resource="http://www.cbwiki.net/wiki/index.php/RSS-CB_1.2_RDF_Schema#Announcement"/>
                <cb:simpleTitle><?= $title ?></cb:simpleTitle>
                <cb:occurrenceDate><?= $date ?></cb:occurrenceDate>
            </cb:news>
        </item>
        <?php
    }
}
?>
</rdf:RDF>
