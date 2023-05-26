<?php
/**
 * Plugin Name: Keystroke Core
 * Plugin URI: #
 * Description: Keystroke core.
 * Version: 1.0.7
 * Author: Axilthemes
 * Author URI: http://axilthemes.com/
 * Text Domain: keystroke
*/

/**
* Define
*/
define( 'KEYSTROKE_ADDONS_VERSION', '1.0.4' );
define( 'KEYSTROKE_ADDONS_URL', plugins_url( '/', __FILE__ ) );
define( 'KEYSTROKE_ADDONS_DIR', dirname( __FILE__ ) );
define( 'KEYSTROKE_ADDONS_PATH', plugin_dir_path( __FILE__ ) );
define( 'KEYSTROKE_ELEMENTS_PATH', KEYSTROKE_ADDONS_DIR . '/include/elementor');

/**
 * Include all files
 */
include_once(KEYSTROKE_ADDONS_DIR. '/include/custom-post.php');
include_once(KEYSTROKE_ADDONS_DIR. '/include/social-share.php');
include_once(KEYSTROKE_ADDONS_DIR. '/include/widgets/custom-widget-register.php');
include_once(KEYSTROKE_ADDONS_DIR. '/include/common-functions.php');
include_once(KEYSTROKE_ADDONS_DIR. '/include/allow-svg.php');
include_once(KEYSTROKE_ADDONS_DIR. '/include/ajax_requests.php');

/**
 * Load Custom Addonss
 */
if ( in_array('elementor/elementor.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ){
    function keystrokeLoadCustomElements(){
        if ( is_dir( KEYSTROKE_ELEMENTS_PATH ) && $keystroke_cc_dirhandle = opendir( KEYSTROKE_ELEMENTS_PATH )) {
            while ( $keystroke_cc_file = readdir( $keystroke_cc_dirhandle )) {
                if ( !in_array( $keystroke_cc_file, array('.', '..') )) {
                    $keystroke_cc_file_contents = file_get_contents( KEYSTROKE_ELEMENTS_PATH . '/' . $keystroke_cc_file );
                    $keystroke_cc_php_file_tokens = token_get_all( $keystroke_cc_file_contents );
                    require_once( KEYSTROKE_ELEMENTS_PATH . '/' . $keystroke_cc_file );
                }
            }
        }
    }
    add_action('elementor/widgets/widgets_registered','keystrokeLoadCustomElements');
}

/**
 * Add Category
 */
if ( in_array('elementor/elementor.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ){
    function keystroke_elementor_category ( $elements_manager ) {
        $elements_manager->add_category(
            'keystroke',
            array(
                'title' => esc_html__( 'Keystroke Addons', 'keystroke' ),
                'icon'  => 'eicon-banner',
            )
        );
    }
    add_action( 'elementor/elements/categories_registered', 'keystroke_elementor_category' );
}



/**
 * Escapeing
 */
if ( !function_exists('keystroke_escapeing')) {
    function keystroke_escapeing($html){
        return $html;
    }
}

/**
 * Load module's scripts and styles if any module is active.
 */
function keystroke_element_enqueue(){


    // wp_enqueue_style('essential_addons_elementor-css',KEYSTROKE_ADDONS_URL.'assets/css/keystroke.css');
   
        if (is_rtl()) {  
             wp_enqueue_script('keystroke-element-scripts', KEYSTROKE_ADDONS_URL . 'assets/js/rtl/element-scripts.js', array('jquery', 'imagesloaded'),'1.0', true);
        } else {
             wp_enqueue_script('keystroke-element-scripts', KEYSTROKE_ADDONS_URL . 'assets/js/element-scripts.js', array('jquery', 'imagesloaded'),'1.0', true);
        }

}
add_action( 'wp_enqueue_scripts', 'keystroke_element_enqueue' );


function keystroke_enqueue_editor_scripts(){
   

     if (is_rtl()) {  
              wp_enqueue_style('keystroke-element-addons-editor', KEYSTROKE_ADDONS_URL . 'assets/css/rtl/editor.css', null, '1.0');
        } else {
              wp_enqueue_style('keystroke-element-addons-editor', KEYSTROKE_ADDONS_URL . 'assets/css/editor.css', null, '1.0');
        }


}
add_action( 'elementor/editor/after_enqueue_scripts', 'keystroke_enqueue_editor_scripts');



/**
 * Check elementor version
 *
 * @param string $version
 * @param string $operator
 * @return bool
 */
function axil_is_elementor_version( $operator = '<', $version = '2.6.0' ) {
    return defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, $version, $operator );
}



/**
 * Render icon html with backward compatibility
 *
 * @param array $settings
 * @param string $old_icon_id
 * @param string $new_icon_id
 * @param array $attributes
 */
function axil_render_icon( $settings = [], $old_icon_id = 'icon', $new_icon_id = 'selected_icon', $attributes = [] ) {
    // Check if its already migrated
    $migrated = isset( $settings['__fa4_migrated'][ $new_icon_id ] );
    // Check if its a new widget without previously selected icon using the old Icon control
    $is_new = empty( $settings[ $old_icon_id ] );

    $attributes['aria-hidden'] = 'true';

    if ( axil_is_elementor_version( '>=', '2.6.0' ) && ( $is_new || $migrated ) ) {
        \Elementor\Icons_Manager::render_icon( $settings[ $new_icon_id ], $attributes );
    } else {
        if ( empty( $attributes['class'] ) ) {
            $attributes['class'] = $settings[ $old_icon_id ];
        } else {
            if ( is_array( $attributes['class'] ) ) {
                $attributes['class'][] = $settings[ $old_icon_id ];
            } else {
                $attributes['class'] .= ' ' . $settings[ $old_icon_id ];
            }
        }
        printf( '<i %s></i>', \Elementor\Utils::render_html_attributes( $attributes ) );
    }
}

/**
 * Remove Auto p form contact form 7
 */
add_filter( 'wpcf7_autop_or_not', '__return_false' );