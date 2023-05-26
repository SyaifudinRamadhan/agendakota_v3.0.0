<?php
/**
 * Plugin Name: Keystroke Demo
 * Plugin URI: #
 * Description: Keystroke demo.
 * Version: 1.0.0
 * Author: Axilthemes
 * Author URI: http://axilthemes.com/
 * Text Domain: keystroke
*/

/**
* Define
*/
define( 'KEYSTROKE_DEMO_VERSION', '1.0.0' );
define( 'KEYSTROKE_DEMO_URL', plugins_url( '/', __FILE__ ) );
define( 'KEYSTROKE_DEMO_DIR', dirname( __FILE__ ) );
define( 'KEYSTROKE_DEMO_PATH', plugin_dir_path( __FILE__ ) );
define( 'KEYSTROKE_DEMO_CONTENT_URL', KEYSTROKE_DEMO_URL . 'demo/' );
define( 'KEYSTROKE_DEMO_PREVIEW_IMAGE_URL', KEYSTROKE_DEMO_CONTENT_URL . 'preview/' );

/**
 * Include all files
 */
include_once(KEYSTROKE_DEMO_DIR. '/include/demo-import-config.php');
