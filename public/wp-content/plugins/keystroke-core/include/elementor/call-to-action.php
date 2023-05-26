<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class keystroke_Elementor_Widget_CallToAction extends Widget_Base {

    use \Elementor\KeystrokeElementCommonFunctions;

    public function get_name() {
        return 'keystroke-call-to-action-one';
    }

    public function get_title() {
        return __( 'Call To Action', 'keystroke' );
    }

    public function get_icon() {
        return 'axil-icon';
    }

    public function get_categories() {
        return [ 'keystroke' ];
    }

    public function get_keywords()
    {
        return ['cta', 'call to action', 'keystroke'];
    }

    protected function _register_controls() {

        $this->axil_section_title('cta_content', 'Lets work together', 'Need a successful project?', 'h2', '');

        $this->start_controls_section(
            'keystroke_calltoaction',
            [
                'label' => esc_html__( 'Call To Action', 'keystroke' ),
            ]
        );
        $this->axil_link_controls('cta_button', 'Estimate Project', 'Estimate Project');

        $this->add_control(
            'below_cta_button',
            [
                'label' => __( 'Add Content CTA Button Below content', 'keystroke' ),
                'type' => Controls_Manager::TEXTAREA,
                'rows' => 5,
                'dynamic' => [
                    'active' => true,
                ]
            ]
        );

        $this->end_controls_section();

        // Start Style Tabs
        $this->axil_basic_style_controls('cta_pre_title', 'Tag Line', '.section-title span.sub-title');
        $this->axil_basic_style_controls('cta_title', 'Title', '.section-title .title');
        $this->axil_basic_style_controls('cta_description', 'Description', '.section-title p');
        $this->axil_link_controls_style('cta_button_style', 'CTA Button', '.axil-button');
        $this->axil_section_style_controls('cta_area', 'CTA Section', '.axil-call-to-action-area');

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();

        ?>
        <!-- Start Call To Action -->
        <div class="axil-call-to-action-area shape-position ax-section-gap theme-gradient">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="axil-call-to-action">
                            <div class="section-title text-center">
                                <?php $this->axil_section_title_render('cta_content', 'extra04-color', $this->get_settings()); ?>


                                <?php

                                // Link
                                if ('2' == $settings['axil_cta_button_link_type']) {
                                    $this->add_render_attribute('axil_cta_button_link', 'href', get_permalink($settings['axil_cta_button_page_link']));
                                    $this->add_render_attribute('axil_cta_button_link', 'target', '_self');
                                    $this->add_render_attribute('axil_cta_button_link', 'rel', 'nofollow');
                                } else {
                                    if (!empty($settings['axil_cta_button_link']['url'])) {
                                        $this->add_render_attribute('axil_cta_button_link', 'href', $settings['axil_cta_button_link']['url']);
                                    }
                                    if ($settings['axil_cta_button_link']['is_external']) {
                                        $this->add_render_attribute('axil_cta_button_link', 'target', '_blank');
                                    }
                                    if (!empty($settings['axil_cta_button_link']['nofollow'])) {
                                        $this->add_render_attribute('axil_cta_button_link', 'rel', 'nofollow');
                                    }
                                }
                                $arrow = ($settings['axil_cta_button_style_button_arrow_icon'] == 'yes') ? '<span class="button-icon"></span>' : '';
                                // Button
                                if (!empty($settings['axil_cta_button_link']['url']) || isset($settings['axil_cta_button_link_type'])) {

                                    $this->add_render_attribute('axil_cta_button_link_style', 'class', ' axil-button wow slideFadeInUp ');
                                    // Style
                                    if (!empty($settings['axil_cta_button_style_button_style'])) {
                                        $this->add_render_attribute('axil_cta_button_link_style', 'class', '' . $settings['axil_cta_button_style_button_style'] . '');
                                    }
                                    // Size
                                    if (!empty($settings['axil_cta_button_style_button_size'])) {
                                        $this->add_render_attribute('axil_cta_button_link_style', 'class', $settings['axil_cta_button_style_button_size']);
                                    }
                                    // Link
                                    $button_html = '<a ' . $this->get_render_attribute_string('axil_cta_button_link_style') . ' ' . $this->get_render_attribute_string('axil_cta_button_link') . '>' . '<span class="button-text">' . $settings['axil_cta_button_text'] . '</span>' . $arrow . '</a>';
                                 echo $button_html;
                                }
                                ?>
                                <?php if ( $settings['below_cta_button'] ) : ?>
                                    <div class="callto-action">
                                        <p><?php echo wp_kses_post( $settings['below_cta_button'] ); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="shape-group">
                <div class="shape shape-01">
                    <span class="icon icon-shape-14"></span>
                </div>
                <div class="shape shape-02">
                    <span class="icon icon-breadcrumb-1"></span>
                </div>
                <div class="shape shape-03">
                    <span class="icon icon-shape-10"></span>
                </div>
                <div class="shape shape-04">
                    <span class="icon icon-breadcrumb-2"></span>
                </div>
            </div>
        </div>
        <!-- End Call To Action -->
        <?php
    }

}

Plugin::instance()->widgets_manager->register_widget_type( new keystroke_Elementor_Widget_CallToAction() );


