<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class keystroke_Elementor_Widget_BannerStyleTwo extends Widget_Base {

    use \Elementor\KeystrokeElementCommonFunctions;

    public function get_name() {
        return 'keystroke-bannerstyletwo';
    }

    public function get_title() {
        return __( 'Creative Agency Banner', 'keystroke' );
    }

    public function get_icon() {
        return 'axil-icon';
    }

    public function get_categories() {
        return [ 'keystroke' ];
    }

    public function get_keywords()
    {
        return ['banner', 'hero area', 'slider', 'top section', 'creative agency banner', 'keystroke'];
    }

    protected function _register_controls() {

        $this->axil_section_title('bannerstyletwo', '', 'Technology & design studio', 'h1', '');

        $this->start_controls_section(
            'keystroke_bannerstyletwo',
            [
                'label' => esc_html__( 'View Showcase Button', 'keystroke' ),
            ]
        );
        $this->axil_link_controls('bannerstyletwo_button', 'View Showcase', 'View Showcase');
        $this->end_controls_section();


        $this->axil_basic_style_controls('bannerstyletwo_pre_title', 'Tag Line', '.axil-slider-area .content span.sub-title');
        $this->axil_basic_style_controls('bannerstyletwo_title', 'Title', '.axil-slider-area .content .title');
        $this->axil_basic_style_controls('bannerstyletwo_description', 'Description', '.axil-slider-area .content p');

        $this->axil_link_controls_style('bannerstyletwo_button_style', 'Button', '.axil-button');
        $this->axil_section_style_controls('bannerstyletwo_area', 'Background Image', '.axil-slide.banner-technology.bg_image--1');
        $this->axil_section_style_controls('bannerstyletwo_area_overlay', 'Background Image Overlay', '.axil-slide.banner-technology.theme-gradient::after');

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();
        $this->add_render_attribute('title_args', 'class', 'axil-display-1');

        ?>
        <!-- Start Slider Area -->
        <div class="axil-slider-area axil-slide-activation">
            <!-- Start Single Slide -->
            <div class="axil-slide banner-technology bg_image bg_image--1 theme-gradient">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-12 col-12">
                            <div class="content text-center">

                                <?php $this->axil_section_title_render('bannerstyletwo','', $this->get_settings()); ?>

                                <?php
                                // Link
                                if ('2' == $settings['axil_bannerstyletwo_button_link_type']) {
                                    $this->add_render_attribute('axil_bannerstyletwo_button_link', 'href', get_permalink($settings['axil_bannerstyletwo_button_page_link']));
                                    $this->add_render_attribute('axil_bannerstyletwo_button_link', 'target', '_self');
                                    $this->add_render_attribute('axil_bannerstyletwo_button_link', 'rel', 'nofollow');
                                } else {
                                    if (!empty($settings['axil_bannerstyletwo_button_link']['url'])) {
                                        $this->add_render_attribute('axil_bannerstyletwo_button_link', 'href', $settings['axil_bannerstyletwo_button_link']['url']);
                                    }
                                    if ($settings['axil_bannerstyletwo_button_link']['is_external']) {
                                        $this->add_render_attribute('axil_bannerstyletwo_button_link', 'target', '_blank');
                                    }
                                    if (!empty($settings['axil_bannerstyletwo_button_link']['nofollow'])) {
                                        $this->add_render_attribute('axil_bannerstyletwo_button_link', 'rel', 'nofollow');
                                    }
                                }
                                $arrow = ($settings['axil_bannerstyletwo_button_style_button_arrow_icon'] == 'yes') ? '<span class="button-icon"></span>' : '';
                                // Button
                                if (!empty($settings['axil_bannerstyletwo_button_link']['url']) || isset($settings['axil_bannerstyletwo_button_link_type'])) {

                                    $this->add_render_attribute('axil_bannerstyletwo_button_link_style', 'class', ' axil-button ');
                                    // Style
                                    if (!empty($settings['axil_bannerstyletwo_button_style_button_style'])) {
                                        $this->add_render_attribute('axil_bannerstyletwo_button_link_style', 'class', '' . $settings['axil_bannerstyletwo_button_style_button_style'] . '');
                                    }
                                    // Size
                                    if (!empty($settings['axil_bannerstyletwo_button_style_button_size'])) {
                                        $this->add_render_attribute('axil_bannerstyletwo_button_link_style', 'class', $settings['axil_bannerstyletwo_button_style_button_size']);
                                    }
                                    // Link
                                    $button_html = '<a ' . $this->get_render_attribute_string('axil_bannerstyletwo_button_link_style') . ' ' . $this->get_render_attribute_string('axil_bannerstyletwo_button_link') . '>' . '<span class="button-text">' . $settings['axil_bannerstyletwo_button_text'] . '</span>' . $arrow . '</a>';
                                    echo $button_html;
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Single Slide -->
        </div>
        <!-- End Slider Area -->
        <?php


    }

}

Plugin::instance()->widgets_manager->register_widget_type( new keystroke_Elementor_Widget_BannerStyleTwo() );


