<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class keystroke_Elementor_Widget_CTA_style_two extends Widget_Base {

    use \Elementor\KeystrokeElementCommonFunctions;

    public function get_name() {
        return 'keystroke-cta_style_two';
    }

    public function get_title() {
        return __( 'CTA - Style Two', 'keystroke' );
    }

    public function get_icon() {
        return 'axil-icon';
    }

    public function get_categories() {
        return [ 'keystroke' ];
    }

    public function get_keywords()
    {
        return ['cta tow', 'call to action style two', 'keystroke'];
    }
    protected function _register_controls() {

        $this->axil_section_title('cta_style_two_content', '', 'Interested in collaborations?', 'h2', '');

        $this->start_controls_section(
            'keystroke_calltoaction_style-two',
            [
                'label' => esc_html__( 'Call To Action', 'keystroke' ),
            ]
        );
        $this->axil_link_controls('cta_style_two_button', 'Estimate Project', 'Estimate Project');
        $this->end_controls_section();

        // Start Style Tabs
        $this->axil_basic_style_controls('cta_style_two_pre_title', 'Tag Line', '.axil-call-to-action span.sub-title');
        $this->axil_basic_style_controls('cta_style_two_title', 'Title', '.axil-call-to-action .title');
        $this->axil_basic_style_controls('cta_style_two_description', 'Description', '.axil-call-to-action .subtitle-2');
        $this->axil_link_controls_style('cta_style_two_button_style', 'CTA Button', '.axil-button');
        $this->axil_section_style_controls('cta_style_two_box', 'CTA Box', '.axil-call-to-action.callaction-style-2 .inner::after');
        $this->axil_section_style_controls('cta_style_two_area', 'CTA Area', '.axil-call-to-action.callaction-style-2');

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();

        ?>
        <!-- Start Call To Action  -->
        <div class="axil-call-to-action callaction-style-2 pt--110 pt_sm--60 pt_md--80 bg-color-extra09">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="inner">
                            <div class="text">
                                <?php $this->axil_section_title_render('cta_style_two_content', '', $this->get_settings()); ?>
                            </div>
                            <?php
                            // Link
                            if ('2' == $settings['axil_cta_style_two_button_link_type']) {
                                $this->add_render_attribute('axil_cta_style_two_button_link', 'href', get_permalink($settings['axil_cta_style_two_button_page_link']));
                                $this->add_render_attribute('axil_cta_style_two_button_link', 'target', '_self');
                                $this->add_render_attribute('axil_cta_style_two_button_link', 'rel', 'nofollow');
                            } else {
                                if (!empty($settings['axil_cta_style_two_button_link']['url'])) {
                                    $this->add_render_attribute('axil_cta_style_two_button_link', 'href', $settings['axil_cta_style_two_button_link']['url']);
                                }
                                if ($settings['axil_cta_style_two_button_link']['is_external']) {
                                    $this->add_render_attribute('axil_cta_style_two_button_link', 'target', '_blank');
                                }
                                if (!empty($settings['axil_cta_style_two_button_link']['nofollow'])) {
                                    $this->add_render_attribute('axil_cta_style_two_button_link', 'rel', 'nofollow');
                                }
                            }
                            $arrow = ($settings['axil_cta_style_two_button_style_button_arrow_icon'] == 'yes') ? '<span class="button-icon"></span>' : '';
                            // Button
                            if (!empty($settings['axil_cta_style_two_button_link']['url']) || isset($settings['axil_cta_style_two_button_link_type'])) {

                                $this->add_render_attribute('axil_cta_style_two_button_link_style', 'class', ' axil-button wow slideFadeInUp ');
                                // Style
                                if (!empty($settings['axil_cta_style_two_button_style_button_style'])) {
                                    $this->add_render_attribute('axil_cta_style_two_button_link_style', 'class', '' . $settings['axil_cta_style_two_button_style_button_style'] . '');
                                }
                                // Size
                                if (!empty($settings['axil_cta_style_two_button_style_button_size'])) {
                                    $this->add_render_attribute('axil_cta_style_two_button_link_style', 'class', $settings['axil_cta_style_two_button_style_button_size']);
                                }
                                // Link
                                $button_html = '<a ' . $this->get_render_attribute_string('axil_cta_style_two_button_link_style') . ' ' . $this->get_render_attribute_string('axil_cta_style_two_button_link') . '>' . '<span class="button-text">' . $settings['axil_cta_style_two_button_text'] . '</span>' . $arrow . '</a>';

                            }
                            ?>
                            <?php if ( $settings['axil_cta_style_two_button_text'] ) : ?>
                                <div class="button-wrapper">
                                    <p><?php echo $button_html ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Call To Action  -->
        <?php

    }

}

Plugin::instance()->widgets_manager->register_widget_type( new keystroke_Elementor_Widget_CTA_style_two() );


