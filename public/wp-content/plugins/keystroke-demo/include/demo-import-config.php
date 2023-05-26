<?php
/**
 * @param $options
 * dialog
 */
function keystroke_confirmation_dialog_options($options)
{
    return array_merge($options, array(
        'width' => 500,
        'dialogClass' => 'wp-dialog',
        'resizable' => false,
        'height' => 'auto',
        'modal' => true,
    ));
}

add_filter('pt-ocdi/confirmation_dialog_options', 'keystroke_confirmation_dialog_options', 10, 1);

/**
 * keystroke_import_files
 * @return array
 */
function keystroke_import_files()
{
    $demo_location = KEYSTROKE_DEMO_CONTENT_URL;
    $preview_image_location = KEYSTROKE_DEMO_PREVIEW_IMAGE_URL;
    $preview_url = 'http://axilthemes.com/themes/keystroke';
    return array(
        array(
            'import_file_name' => 'Digital Agency',
            'import_file_url' => $demo_location . 'content.xml',
            'import_widget_file_url' => $demo_location . 'widget.wie',
            'import_customizer_file_url' => $demo_location . 'customizer.dat',
            'import_redux' => array(
                array(
                    'file_url' => $demo_location . 'redux_options.json',
                    'option_name' => 'axil_options',
                )
            ),
            'import_preview_image_url' => $preview_image_location . 'digital-agency.jpg',
            'preview_url' => $preview_url,
            'import_notice' => esc_html__('After you import this demo, you will have setup all content.', 'keystroke'),
        ),
        array(
            'import_file_name' => 'Creative Agency',
            'import_file_url' => $demo_location . 'content.xml',
            'import_widget_file_url' => $demo_location . 'widget.wie',
            'import_customizer_file_url' => $demo_location . 'customizer.dat',
            'import_redux' => array(
                array(
                    'file_url' => $demo_location . 'redux_options.json',
                    'option_name' => 'axil_options',
                )
            ),
            'import_preview_image_url' => $preview_image_location . 'creative-agency.jpg',
            'preview_url' => $preview_url . '/creative-agency/',
            'import_notice' => esc_html__('After you import this demo, you will have setup all content.', 'keystroke'),
        ),
        array(
            'import_file_name' => 'Personal Portfolio',
            'import_file_url' => $demo_location . 'content.xml',
            'import_widget_file_url' => $demo_location . 'widget.wie',
            'import_customizer_file_url' => $demo_location . 'customizer.dat',
            'import_redux' => array(
                array(
                    'file_url' => $demo_location . 'redux_options.json',
                    'option_name' => 'axil_options',
                )
            ),
            'import_preview_image_url' => $preview_image_location . 'personal-portfolio.jpg',
            'preview_url' => $preview_url . '/personal-portfolio/',
            'import_notice' => esc_html__('After you import this demo, you will have setup all content.', 'keystroke'),
        ),
        array(
            'import_file_name' => 'Startup',
            'import_file_url' => $demo_location . 'content.xml',
            'import_widget_file_url' => $demo_location . 'widget.wie',
            'import_customizer_file_url' => $demo_location . 'customizer.dat',
            'import_redux' => array(
                array(
                    'file_url' => $demo_location . 'redux_options.json',
                    'option_name' => 'axil_options',
                )
            ),
            'import_preview_image_url' => $preview_image_location . 'startup.jpg',
            'preview_url' => $preview_url . '/startup/',
            'import_notice' => esc_html__('After you import this demo, you will have setup all content.', 'keystroke'),
        ),
        array(
            'import_file_name' => 'Corporate Agency',
            'import_file_url' => $demo_location . 'content.xml',
            'import_widget_file_url' => $demo_location . 'widget.wie',
            'import_customizer_file_url' => $demo_location . 'customizer.dat',
            'import_redux' => array(
                array(
                    'file_url' => $demo_location . 'redux_options.json',
                    'option_name' => 'axil_options',
                )
            ),
            'import_preview_image_url' => $preview_image_location . 'corporate-agency.jpg',
            'preview_url' => $preview_url . '/corporate-agency/',
            'import_notice' => esc_html__('After you import this demo, you will have setup all content.', 'keystroke'),
        )
    );
}

add_filter('pt-ocdi/import_files', 'keystroke_import_files');

/**
 * keystroke_before_widgets_import
 * @param $selected_import
 */
function keystroke_before_widgets_import($selected_import)
{

    // Remove 'Hello World!' post
    wp_delete_post(1, true);
    // Remove 'Sample page' page
    wp_delete_post(2, true);

    $sidebars_widgets = get_option('sidebars_widgets');
    $sidebars_widgets['sidebar'] = array();
    update_option('sidebars_widgets', $sidebars_widgets);

}

add_action('pt-ocdi/before_widgets_import', 'keystroke_before_widgets_import');

/*
 * Automatically assign
 * "Front page",
 * "Posts page" and menu
 * locations after the importer is done
 */
function keystroke_after_import_setup($selected_import)
{

    $demo_imported = get_option('keystroke_demo_imported');

    $cpt_support = get_option('elementor_cpt_support');
    $elementor_disable_color_schemes = get_option('elementor_disable_color_schemes');
    $elementor_disable_typography_schemes = get_option('elementor_disable_typography_schemes');
    $elementor_container_width = get_option('elementor_container_width');


    //check if option DOESN'T exist in db
    if (!$cpt_support) {
        $cpt_support = ['page', 'post', 'project', 'case-study', 'elementor_disable_color_schemes']; //create array of our default supported post types
        update_option('elementor_cpt_support', $cpt_support); //write it to the database
    }
    if (empty($elementor_disable_color_schemes)) {
        update_option('elementor_disable_color_schemes', 'yes'); //update database
    }
    if (empty($elementor_disable_typography_schemes)) {
        update_option('elementor_disable_typography_schemes', 'yes'); //update database
    }
    if (empty($elementor_container_width)) {
        update_option('elementor_container_width', '1290'); //update database
    }

    $elementor_general_settings = array(
        'container_width' => (!empty($elementor_container_width)) ? $elementor_container_width : '1260',
    );
    update_option('_elementor_general_settings', $elementor_general_settings); //update database

    // Update Global Css Options For Elementor
    $currentTime = strtotime("now");
    $elementor_global_css = array(
        'time' => $currentTime,
        'fonts' => array()
    );
    update_option('_elementor_global_css', $elementor_global_css); //update database

    update_option('keystroke_elementor_custom_setting_imported', 'elementor_custom_setting_imported');

    update_option('mc4wp_default_form_id', '147'); //update database


    if (empty($demo_imported)) {

        // Home page selected
        if ('Digital Agency' === $selected_import['import_file_name']) {
            $front_page_id = get_page_by_title('Digital Agency');
            update_option('keystroke_theme_active_demo', $selected_import['import_file_name']);
        } elseif ('Creative Agency' === $selected_import['import_file_name']) {
            $front_page_id = get_page_by_title('Creative Agency');
            update_option('keystroke_theme_active_demo', $selected_import['import_file_name']);
        } elseif ('Personal Portfolio' === $selected_import['import_file_name']) {
            $front_page_id = get_page_by_title('Personal Portfolio');
            update_option('keystroke_theme_active_demo', $selected_import['import_file_name']);
        } elseif ('Startup' === $selected_import['import_file_name']) {
            $front_page_id = get_page_by_title('Startup');
            update_option('keystroke_theme_active_demo', $selected_import['import_file_name']);
        } elseif ('Corporate Agency' === $selected_import['import_file_name']) {
            $front_page_id = get_page_by_title('Corporate Agency');
            update_option('keystroke_theme_active_demo', $selected_import['import_file_name']);
        }

        $blog_page_id = get_page_by_title('Blog');
        update_option('show_on_front', 'page');
        update_option('page_on_front', $front_page_id->ID);
        update_option('page_for_posts', $blog_page_id->ID);

        update_option('keystroke_demo_imported', 'imported');
    }

    // Set Menu As Primary && Off Canvus Menu
    $main_menu = get_term_by('name', 'Primary', 'nav_menu');
    $offcanvus_menu = get_term_by('name', 'Primary', 'nav_menu');
    $footerbottom = get_term_by('name', 'Footer Bottom Menu', 'nav_menu');
    set_theme_mod('nav_menu_locations', array(
        'primary' => $main_menu->term_id,
        'offcanvas' => $offcanvus_menu->term_id,
        'footerbottom' => $footerbottom->term_id
    ));

}

add_action('pt-ocdi/after_import', 'keystroke_after_import_setup');


/**
 * time_for_one_ajax_call
 * @return int
 */
function keystroke_change_time_of_single_ajax_call()
{
    return 20;
}

add_action('pt-ocdi/time_for_one_ajax_call', 'keystroke_change_time_of_single_ajax_call');


// To make demo imported items selected
add_action('admin_footer', 'keystroke_pt_ocdi_add_scripts');
function keystroke_pt_ocdi_add_scripts()
{
    $demo_imported = get_option('keystroke_theme_active_demo');
    if (!empty($demo_imported)) {
        ?>
        <script>
            jQuery(document).ready(function ($) {
                $('.ocdi__gl-item.js-ocdi-gl-item').each(function () {
                    var ocdi_theme_title = $(this).data('name');
                    var current_ocdi_theme_title = '<?php echo strtolower($demo_imported); ?>';
                    if (ocdi_theme_title == current_ocdi_theme_title) {
                        $(this).addClass('active_demo');
                        return false;
                    }
                });
            });
        </script>
        <?php
    }
}

/**
 * Remove ads
 */
add_filter('pt-ocdi/disable_pt_branding', '__return_true');



