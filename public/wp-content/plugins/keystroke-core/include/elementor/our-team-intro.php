<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Axil_Elementor_Widget_TeamIntro extends Widget_Base {

    use \Elementor\KeystrokeElementCommonFunctions;

    public function get_name() {
        return 'keystroke-team-intro';
    }

    public function get_title() {
        return esc_html__( 'Team Intro', 'keystroke' );
    }

    public function get_icon() {
        return 'axil-icon';
    }

    public function get_categories() {
        return [ 'keystroke' ];
    }

    public function get_keywords()
    {
        return ['team', 'team into', 'team with counter', 'team banner', 'keystroke'];
    }

    protected function _register_controls() {


        $this->axil_section_title('team_intro', 'Team Intro', 'Alone we can do so little; together we can do so much.', 'h2', 'Donec metus lorem, vulputate at sapien sit amet, auctor iaculis lorem. In vel hendrerit nisi. Vestibulum eget risus velit. Aliquam tristique libero.');

        $this->start_controls_section(
            '_team_intro_button',
            [
                'label' => esc_html__( 'Team Intro Button', 'keystroke' ),
            ]
        );

        $this->axil_link_controls('team_intro_button', 'Our Team Button', 'Our Team');
        $this->axil_link_controls('team_intro_careers_button', 'Careers Button', 'Careers');

        $this->end_controls_section();


        $this->start_controls_section(
            '_team_intro_image',
            [
                'label' => esc_html__( 'Team Intro Image', 'keystroke' ),
            ]
        );
        $this->add_control(
            'team_intro_image',
            [
                'label' => esc_html__( 'Team Intro Image', 'keystroke' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'dynamic' => [
                    'active' => true,
                ]
            ]
        );

        $this->add_control(
            'total_team_member',
            [
                'label' => esc_html__( 'Total Team Member', 'keystroke' ),
                'type' => Controls_Manager::TEXT,
                'default' => '20',
                'placeholder' => esc_html__( 'Type total number of team member here', 'keystroke' ),
            ]
        );

        $this->end_controls_section();

        $this->axil_basic_style_controls('section_title_pre_title', 'Tag Line', '.section-title span.sub-title');
        $this->axil_basic_style_controls('section_title_title', 'Title', '.section-title .title');
        $this->axil_basic_style_controls('section_title_description', 'Description', '.section-title p');

        $this->axil_link_controls_style('team_intro_button_style', 'Our Team Button', '.axil-button');
        $this->axil_link_controls_style('team_intro_careers_button_style', 'Careers Button', '.axil-button', 'btn-large', 'axil-link-button');
        $this->axil_section_style_controls('team_intro_area', 'Team Intro Section', '.axil-team-area');







    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();
        $this->add_render_attribute('title_args', 'class', 'title wow');

        // Link
        if ('2' == $settings['axil_team_intro_button_link_type']) {
            $this->add_render_attribute('axil_team_intro_button_link', 'href', get_permalink($settings['axil_team_intro_button_page_link']));
            $this->add_render_attribute('axil_team_intro_button_link', 'target', '_self');
            $this->add_render_attribute('axil_team_intro_button_link', 'rel', 'nofollow');
        } else {
            if (!empty($settings['axil_team_intro_button_link']['url'])) {
                $this->add_render_attribute('axil_team_intro_button_link', 'href', $settings['axil_team_intro_button_link']['url']);
            }
            if ($settings['axil_team_intro_button_link']['is_external']) {
                $this->add_render_attribute('axil_team_intro_button_link', 'target', '_blank');
            }
            if (!empty($settings['axil_team_intro_button_link']['nofollow'])) {
                $this->add_render_attribute('axil_team_intro_button_link', 'rel', 'nofollow');
            }
        }
        $arrow = ($settings['axil_team_intro_button_style_button_arrow_icon'] == 'yes') ? '<span class="button-icon"></span>' : '';
        // Button
        if (!empty($settings['axil_team_intro_button_link']['url']) || isset($settings['axil_team_intro_button_link_type'])) {

            $this->add_render_attribute('axil_team_intro_button_link_style', 'class', ' axil-button wow slideFadeInUp ');
            // Style
            if (!empty($settings['axil_team_intro_button_style_button_style'])) {
                $this->add_render_attribute('axil_team_intro_button_link_style', 'class', '' . $settings['axil_team_intro_button_style_button_style'] . '');
            }
            // Size
            if (!empty($settings['axil_team_intro_button_style_button_size'])) {
                $this->add_render_attribute('axil_team_intro_button_link_style', 'class', $settings['axil_team_intro_button_style_button_size']);
            }
            // Link
            $button_html = '<a ' . $this->get_render_attribute_string('axil_team_intro_button_link_style') . ' ' . $this->get_render_attribute_string('axil_team_intro_button_link') . '>' . '<span class="button-text">' . $settings['axil_team_intro_button_text'] . '</span>' . $arrow . '</a>';
            $button_number_html = '<a ' . $this->get_render_attribute_string('axil_team_intro_button_link') . '>' . '<span>' . $settings['total_team_member'] . '</span></a>';
        }


        // Link
        if ('2' == $settings['axil_team_intro_careers_button_link_type']) {
            $this->add_render_attribute('axil_team_intro_careers_button_link', 'href', get_permalink($settings['axil_team_intro_careers_button_page_link']));
            $this->add_render_attribute('axil_team_intro_careers_button_link', 'target', '_self');
            $this->add_render_attribute('axil_team_intro_careers_button_link', 'rel', 'nofollow');
        } else {
            if (!empty($settings['axil_team_intro_careers_button_link']['url'])) {
                $this->add_render_attribute('axil_team_intro_careers_button_link', 'href', $settings['axil_team_intro_careers_button_link']['url']);
            }
            if ($settings['axil_team_intro_careers_button_link']['is_external']) {
                $this->add_render_attribute('axil_team_intro_careers_button_link', 'target', '_blank');
            }
            if (!empty($settings['axil_team_intro_careers_button_link']['nofollow'])) {
                $this->add_render_attribute('axil_team_intro_careers_button_link', 'rel', 'nofollow');
            }
        }
        $arrow = ($settings['axil_team_intro_careers_button_style_button_arrow_icon'] == 'yes') ? '<span class="button-icon"></span>' : '';
        // Button
        if (!empty($settings['axil_team_intro_careers_button_link']['url']) || isset($settings['axil_team_intro_careers_button_link_type'])) {

            $this->add_render_attribute('axil_team_intro_careers_button_link_style', 'class', ' axil-button axil-link-button ');
            // Style
            if (!empty($settings['axil_team_intro_careers_button_style_button_style'])) {
                $this->add_render_attribute('axil_team_intro_careers_button_link_style', 'class', '' . $settings['axil_team_intro_careers_button_style_button_style'] . '');
            }
            // Size
            if (!empty($settings['axil_team_intro_careers_button_style_button_size'])) {
                $this->add_render_attribute('axil_team_intro_careers_button_link_style', 'class', $settings['axil_team_intro_careers_button_style_button_size']);
            }
            // Link
            $careers_button_html = '<a ' . $this->get_render_attribute_string('axil_team_intro_careers_button_link_style') . ' ' . $this->get_render_attribute_string('axil_team_intro_careers_button_link') . '>' . $settings['axil_team_intro_careers_button_text'] . '</a>';

        }
        ?>

        <!-- Start Team Area -->
        <div class="axil-team-area shape-position ax-section-gap bg-color-white">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-xl-6">
                        <div class="thumbnail">
                            <?php if ( ! empty( $settings['team_intro_image'] ) ) : ?>
                                <div class="image">
                                    <?php echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'full', 'team_intro_image' ); ?>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($settings['total_team_member'])) { ?>
                            <div class="total-team-button"> <?php echo $button_number_html; ?></div><?php } ?>
                        </div>
                    </div>
                    <div class="col-lg-5 col-xl-5 offset-xl-1 mt_md--40 mt_sm--40">
                        <div class="content">
                            <div class="inner">
                                <div class="section-title text-left">
                                    <?php $this->axil_section_title_render('team_intro','extra08-color', $this->get_settings()); ?>
                                    <div class="axil-button-group mt--40">
                                        <?php if (!empty($settings['axil_team_intro_button_text'])) {
                                            echo $button_html;
                                        } ?>
                                        <?php if (!empty($settings['axil_team_intro_careers_button_text'])) {
                                            echo $careers_button_html;
                                        } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="shape-group">
                <div class="shape shape-1 customOne">
                    <span class="icon icon-shape-06"></span>
                </div>
                <div class="shape shape-2">
                    <span class="icon icon-shape-13"></span>
                </div>
                <div class="shape shape-3">
                    <span class="icon icon-shape-14"></span>
                </div>
            </div>
        </div>
        <!-- End Team Area -->

        <?php
    }

}

Plugin::instance()->widgets_manager->register_widget_type( new Axil_Elementor_Widget_TeamIntro() );


