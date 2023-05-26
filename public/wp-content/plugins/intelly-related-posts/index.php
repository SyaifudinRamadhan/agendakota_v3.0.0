<?php
/*
Plugin Name: Inline Related Posts
Plugin URI: http://intellywp.com/intelly-related-posts/
Description: Inline Related Posts AUTOMATICALLY inserts related posts INSIDE your content, capturing immediately the reader's attention
Author: Data443
Author URI: https://Data443.com/
Email: support@intellywp.com
Version: 3.0.9
Requires at least: 3.6.0
Requires PHP: 7.3
*/
define('IRP_PLUGIN_PREFIX', 'IRP_');
define('IRP_PLUGIN_FILE',__FILE__);
define('IRP_PLUGIN_SLUG', 'intelly-related-posts');
define('IRP_PLUGIN_NAME', 'Inline Related Posts');
define('IRP_PLUGIN_VERSION', '3.0.9');
define('IRP_PLUGIN_AUTHOR', 'Data443');
define('IRP_PLUGIN_ROOT', dirname(__FILE__).'/');
define('IRP_PLUGIN_IMAGES', plugins_url( 'assets/images/', __FILE__ ));
define('IRP_PLUGIN_ASSETS', plugins_url( 'assets/', __FILE__ ));

define('IRP_LOGGER', FALSE);
define('IRP_DEBUG_BLOCK', FALSE);
define('IRP_DISABLE_RELATED', FALSE);
define('IRP_QUERY_POSTS_OF_TYPE', 1);
define('IRP_QUERY_POST_TYPES', 2);
define('IRP_QUERY_CATEGORIES', 3);
define('IRP_QUERY_TAGS', 4);
define('IRP_DEFAULT_LINK_REL_ATTRIBUTE', 'dofollow');  // use dofollow or nofollow

define('IRP_ENGINE_SEARCH_CATEGORIES_TAGS', 0);
define('IRP_ENGINE_SEARCH_CATEGORIES', 1);
define('IRP_ENGINE_SEARCH_TAGS', 2);

define('IRP_PLUGIN_URI', plugins_url('/', __FILE__ ));
define('IRP_INTELLYWP_SITE', 'http://www.intellywp.com/');
define('IRP_INTELLYWP_ENDPOINT', IRP_INTELLYWP_SITE.'wp-content/plugins/intellywp-manager/data.php');
define('IRP_PAGE_FAQ', IRP_INTELLYWP_SITE.IRP_PLUGIN_SLUG);
define('IRP_PAGE_WORDPRESS', 'https://wordpress.org/plugins/'.IRP_PLUGIN_SLUG.'/');
define('IRP_PAGE_PREMIUM', IRP_INTELLYWP_SITE.IRP_PLUGIN_SLUG);
define('IRP_PAGE_SETTINGS', admin_url().'options-general.php?page='.IRP_PLUGIN_SLUG);

define('IRP_TAB_SETTINGS', 'settings');
define('IRP_TAB_SETTINGS_URI', IRP_PAGE_SETTINGS.'&tab='.IRP_TAB_SETTINGS);
define('IRP_TAB_ABOUT', 'about');
define('IRP_TAB_ABOUT_URI', IRP_PAGE_SETTINGS.'&tab='.IRP_TAB_ABOUT);
define('IRP_TAB_DOCS', 'docs');
define('IRP_TAB_DOCS_URI', 'http://intellywp.com/docs/category/inline-related-posts/');
define('IRP_TAB_WHATS_NEW', 'whatsnew');
define('IRP_TAB_WHATS_NEW_URI', IRP_PAGE_SETTINGS.'&tab='.IRP_TAB_WHATS_NEW);

include_once(dirname(__FILE__).'/autoload.php');
irp_include_php(dirname(__FILE__).'/includes/');

function irp_load_textdomain()
{
    load_plugin_textdomain(IRP_PLUGIN_SLUG, false, dirname( plugin_basename(__FILE__ )) . '/languages');
}
add_action('init', 'irp_load_textdomain');

// Load assets for wp-admin when gutenberg editor is active
function gutenberg_irp_shortcode_block() {
    wp_enqueue_script(
       'gutenberg-irp-shortcode-block-active-editor',
       plugins_url('shortcode-block.js', __FILE__),
       array('wp-blocks', 'wp-element')
    );
 }
 add_action('enqueue_block_editor_assets', 'gutenberg_irp_shortcode_block');

function irp_block_category($categories, $post) {
    return array_merge(
        $categories,
        array(
            array(
                'slug' => 'data443-category',
                'title' => __('Data443', 'intelly-related-posts'),
            ),
        )
    );
}
add_filter('block_categories', 'irp_block_category', 10, 2);

global $irp;
$irp=new IRP_Singleton();
$irp->init();
