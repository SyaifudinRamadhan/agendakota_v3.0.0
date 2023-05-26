<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class keystroke_Elementor_Widget_Corporate_Agency_Banner extends Widget_Base {

    use \Elementor\KeystrokeElementCommonFunctions;

    public function get_name() {
        return 'keystroke-corporate-agency-banner';
    }

    public function get_title() {
        return __( 'Corporate Agency Banner', 'keystroke' );
    }

    public function get_icon() {
        return 'axil-icon';
    }

    public function get_categories() {
        return [ 'keystroke' ];
    }

    public function get_keywords()
    {
        return ['banner', 'hero area', 'slider', 'top section', 'corporate agency banner', 'keystroke'];
    }
    public function get_axil_contact_form(){
        if ( ! class_exists( 'WPCF7' ) ) {
            return;
        }
        $axil_cfa         = array();
        $axil_cf_args     = array( 'posts_per_page' => -1, 'post_type'=> 'wpcf7_contact_form' );
        $axil_forms       = get_posts( $axil_cf_args );
        $axil_cfa         = ['0' => esc_html__( 'Select Form', 'keystroke' ) ];
        if( $axil_forms ){
            foreach ( $axil_forms as $axil_form ){
                $axil_cfa[$axil_form->ID] = $axil_form->post_title;
            }
        }else{
            $axil_cfa[ esc_html__( 'No contact form found', 'keystroke' ) ] = 0;
        }
        return $axil_cfa;
    }

    protected function _register_controls() {

        $this->axil_section_title('corporate_agency_banner', '', 'Technology <br /> & design studio', 'h1', '');

        $this->start_controls_section(
            'keystroke_corporate_agency_banner',
            [
                'label' => esc_html__( 'View Showcase Button', 'keystroke' ),
            ]
        );
        $this->axil_link_controls('corporate_agency_banner_button', 'View Showcase', 'View Showcase');
        $this->end_controls_section();

        $this->start_controls_section(
            'keystroke_corporate_agency_banner_shortcode',
            [
                'label' => esc_html__( 'Contact Form', 'keystroke' ),
            ]
        );

        $this->add_control(
            'select_contact_form',
            [
                'label'   => esc_html__( 'Select Form', 'keystroke' ),
                'type'    => Controls_Manager::SELECT,
                'default' => '0',
                'options' => $this->get_axil_contact_form(),
            ]
        );

        $this->add_responsive_control(
            'keystroke_corporate_agency_banner_box_position_vertical',
            [
                'label'       => __( 'Box Vertical Position', 'keystroke' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'default' => [
                    'unit' => '%'
                ],
                'range'       => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors'   => [
                    '{{WRAPPER}} .axil-slide.slide-style-5 .contact-form-wrapper' => 'bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'keystroke_corporate_agency_banner_box_position_horizontal',
            [
                'label'       => __( 'Box Horizontal Position', 'keystroke' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'default' => [
                    'unit' => '%'
                ],
                'range'       => [
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'selectors'   => [
                    '{{WRAPPER}} .axil-slide.slide-style-5 .contact-form-wrapper' => 'right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();


        $this->axil_basic_style_controls('corporate_agency_banner_pre_title', 'Tag Line', '.slide-style-5 span.sub-title');
        $this->axil_basic_style_controls('corporate_agency_banner_title', 'Title', '.slide-style-5 .axil-display-1');
        $this->axil_basic_style_controls('corporate_agency_banner_description', 'Description', '.slide-style-5 p.subtitle-2');
        $this->axil_link_controls_style('corporate_agency_banner_button_style', 'Button', '.axil-button');
        $this->axil_basic_style_controls('corporate_agency_banner_form_title', 'Form Title', '.contact-form-wrapper .title');
        $this->axil_section_style_controls('corporate_agency_banner_area', 'Background', '.axil-slider-area .axil-slide.slide-style-5');

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();
        $this->add_render_attribute('title_args', 'class', 'axil-display-1');

        ?>
        <!-- Start Slider Area -->
        <div class="axil-slider-area axil-slide-activation position-relative">
            <!-- Start Single Slide -->
            <div class="axil-slide slide-style-5 theme-gradient-8 d-flex align-items-center">
                <div class="container">
                    <div class="row align-items-center w-100">
                        <div class="col-lg-7 col-12">
                            <div class="content">
                                <div class="inner">

                                    <?php $this->axil_section_title_render('corporate_agency_banner','', $this->get_settings()); ?>

                                    <?php
                                    // Link
                                    if ('2' == $settings['axil_corporate_agency_banner_button_link_type']) {
                                        $this->add_render_attribute('axil_corporate_agency_banner_button_link', 'href', get_permalink($settings['axil_corporate_agency_banner_button_page_link']));
                                        $this->add_render_attribute('axil_corporate_agency_banner_button_link', 'target', '_self');
                                        $this->add_render_attribute('axil_corporate_agency_banner_button_link', 'rel', 'nofollow');
                                    } else {
                                        if (!empty($settings['axil_corporate_agency_banner_button_link']['url'])) {
                                            $this->add_render_attribute('axil_corporate_agency_banner_button_link', 'href', $settings['axil_corporate_agency_banner_button_link']['url']);
                                        }
                                        if ($settings['axil_corporate_agency_banner_button_link']['is_external']) {
                                            $this->add_render_attribute('axil_corporate_agency_banner_button_link', 'target', '_blank');
                                        }
                                        if (!empty($settings['axil_corporate_agency_banner_button_link']['nofollow'])) {
                                            $this->add_render_attribute('axil_corporate_agency_banner_button_link', 'rel', 'nofollow');
                                        }
                                    }
                                    $arrow = ($settings['axil_corporate_agency_banner_button_style_button_arrow_icon'] == 'yes') ? '<span class="button-icon"></span>' : '';
                                    // Button
                                    if (!empty($settings['axil_corporate_agency_banner_button_link']['url']) || isset($settings['axil_corporate_agency_banner_button_link_type'])) {

                                        $this->add_render_attribute('axil_corporate_agency_banner_button_link_style', 'class', ' axil-button  ');
                                        // Style
                                        if (!empty($settings['axil_corporate_agency_banner_button_style_button_style'])) {
                                            $this->add_render_attribute('axil_corporate_agency_banner_button_link_style', 'class', '' . $settings['axil_corporate_agency_banner_button_style_button_style'] . '');
                                        }
                                        // Size
                                        if (!empty($settings['axil_corporate_agency_banner_button_style_button_size'])) {
                                            $this->add_render_attribute('axil_corporate_agency_banner_button_link_style', 'class', $settings['axil_corporate_agency_banner_button_style_button_size']);
                                        }
                                        // Link
                                        $button_html = '<a ' . $this->get_render_attribute_string('axil_corporate_agency_banner_button_link_style') . ' ' . $this->get_render_attribute_string('axil_corporate_agency_banner_button_link') . '>' . '<span class="button-text">' . $settings['axil_corporate_agency_banner_button_text'] . '</span>' . $arrow . '</a>';
                                        echo $button_html;
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if(!empty($settings['select_contact_form'])){ ?>
                    <!-- Start Contact Form -->
                    <?php if( !empty($settings['select_contact_form']) ){ ?> <div class="contact-form-wrapper"><div class="axil-contact-form contact-form-style-1"> <?php
                        echo do_shortcode( '[contact-form-7  id="'.$settings['select_contact_form'].'"]' );
                        ?> </div></div> <?php
                    } else {
                        echo '<div class="alert alert-info"><p>' . __('Please Select contact form.', 'keystroke' ). '</p></div>';
                    } ?>
                <?php } ?>
            </div>
            <!-- End Single Slide -->
        </div>
        <!-- End Slider Area -->
        <?php



    }

}

Plugin::instance()->widgets_manager->register_widget_type( new keystroke_Elementor_Widget_Corporate_Agency_Banner() );


