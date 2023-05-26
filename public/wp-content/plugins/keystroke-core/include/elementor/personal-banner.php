<?php

namespace Elementor;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class keystroke_Elementor_Widget_Personal_Banner extends Widget_Base
{

    use \Elementor\KeystrokeElementCommonFunctions;

    public function get_name()
    {
        return 'keystroke-personal-banner';
    }

    public function get_title()
    {
        return __('Personal Portfolio Banner', 'keystroke');
    }

    public function get_icon()
    {
        return 'axil-icon';
    }

    public function get_categories()
    {
        return ['keystroke'];
    }

    public function get_keywords()
    {
        return ['banner', 'hero area', 'slider', 'top section', 'personal banner', 'personal portfolio', 'portfolio banner', 'keystroke'];
    }

    protected function _register_controls()
    {

        $this->axil_section_title('personal_banner', 'HARVEY LAWSON', 'Website and user interface designer', 'h1', '');

        $this->start_controls_section(
            'keystroke_personal_banner',
            [
                'label' => esc_html__('Button', 'keystroke'),
            ]
        );
        $this->axil_link_controls('personal_banner_button', 'Latest Work On Dribbble', 'Latest Work On Dribbble');
        $this->axil_link_controls('personal_banner_button_two', 'About Me', 'About Me');
        $this->end_controls_section();

        $this->start_controls_section(
            'keystroke_personal_banner_image_panel',
            [
                'label' => esc_html__('Thumbnail', 'keystroke'),
            ]
        );
        $this->add_control(
            'keystroke_personal_banner_image',
            [
                'label' => esc_html__('Choose Your Image', 'keystroke'),
                'description' => esc_html__('Recommended image size 1013X917px', 'keystroke'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],

            ]
        );
        $this->add_control(
            'keystroke_personal_banner_marque_image',
            [
                'label' => esc_html__('Choose Your Marque Image', 'keystroke'),
                'description' => esc_html__('Recommended image height 465px', 'keystroke'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],

            ]
        );
        $this->add_control(
            'keystroke_personal_banner_mouse_icon',
            [
                'label' => esc_html__('Show Scroll Down Button Icon', 'keystroke'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'keystroke'),
                'label_off' => esc_html__('No', 'keystroke'),
                'return_value' => 'yes',
                'separator' => 'before',
                'default' => 'yes'
            ]
        );
        $this->add_control(
            'keystroke_personal_banner_mouse_icon_id',
            [
                'label' => esc_html__('Add section ID', 'keystroke'),
                'type' => Controls_Manager::TEXT,
                'default' => 'sectionBottom',
                'label_block' => true,
                'condition' => [
                    'keystroke_personal_banner_mouse_icon' => 'yes'
                ]
            ]
        );
        $this->end_controls_section();


        // Style Tabs

        // Section Contetn
        $this->axil_basic_style_controls('personal_banner_pre_title', 'Tag Line', '.axil-slide.slide-style-3 .content span.title');
        $this->axil_basic_style_controls('personal_banner_title', 'Title', '.axil-slide.slide-style-3 .content .axil-display-1');
        $this->axil_basic_style_controls('personal_banner_description', 'Description', '.axil-slide.slide-style-3 .content p');

        // Link Style Control
        $this->axil_link_controls_style('personal_banner_button_style', 'Latest Work On Dribbble Button', '.axil-button');
        $this->axil_link_controls_style('personal_banner_button_two_style', 'About Me Button', '.axil-button', 'btn-large', 'axil-link-button');

        $this->axil_section_style_controls('personal_banner', 'Banner Area', '.axil-slide.slide-style-3.slider-fixed-height');
        $this->axil_section_style_controls('personal_banner_overlay', 'Banner Area Overlay', '.axil-slide.slide-style-3::before');

    }

    protected function render($instance = [])
    {

        $settings = $this->get_settings_for_display();
        $this->add_render_attribute('title_args', 'class', 'axil-display-1 ');

        ?>
        <!-- Start Slider Area -->
        <div class="axil-slider-area portfolio-slider axil-slide-activation fix">

            <!-- Start Single Slide -->
            <div class="axil-slide slide-style-3 theme-gradient-3 slider-fixed-height d-flex align-items-center">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-6 col-12">
                            <div class="content">

                                <?php if (!empty($settings['axil_personal_banner_before_title'])) { ?>
                                    <span class="title"><?php echo axil_kses_intermediate($settings['axil_personal_banner_before_title']); ?></span>
                                <?php } ?>
                                <?php if ($settings['axil_personal_banner_title_tag']) : ?>
                                    <<?php echo tag_escape($settings['axil_personal_banner_title_tag']); ?> <?php echo $this->get_render_attribute_string('title_args'); ?>><?php echo esc_html($settings['axil_personal_banner_title']); ?></<?php echo tag_escape($settings['axil_personal_banner_title_tag']) ?>>
                                <?php endif; ?>
                                <?php if (!empty($settings['axil_personal_banner_desctiption'])) { ?>
                                    <p class="subtitle-2 "><?php echo axil_kses_intermediate($settings['axil_personal_banner_desctiption']); ?></p>
                                <?php } ?>


                                <div class="button-group">
                                    <?php
                                    // Link
                                    if ('2' == $settings['axil_personal_banner_button_link_type']) {
                                        $this->add_render_attribute('axil_personal_banner_button_link', 'href', get_permalink($settings['axil_personal_banner_button_page_link']));
                                        $this->add_render_attribute('axil_personal_banner_button_link', 'target', '_self');
                                        $this->add_render_attribute('axil_personal_banner_button_link', 'rel', 'nofollow');
                                    } else {
                                        if (!empty($settings['axil_personal_banner_button_link']['url'])) {
                                            $this->add_render_attribute('axil_personal_banner_button_link', 'href', $settings['axil_personal_banner_button_link']['url']);
                                        }
                                        if ($settings['axil_personal_banner_button_link']['is_external']) {
                                            $this->add_render_attribute('axil_personal_banner_button_link', 'target', '_blank');
                                        }
                                        if (!empty($settings['axil_personal_banner_button_link']['nofollow'])) {
                                            $this->add_render_attribute('axil_personal_banner_button_link', 'rel', 'nofollow');
                                        }
                                    }
                                    $arrow = ($settings['axil_personal_banner_button_style_button_arrow_icon'] == 'yes') ? '<span class="button-icon"></span>' : '';
                                    // Button
                                    if (!empty($settings['axil_personal_banner_button_link']['url']) || isset($settings['axil_personal_banner_button_link_type'])) {

                                        $this->add_render_attribute('axil_personal_banner_button_link_style', 'class', ' axil-button  ');
                                        // Style
                                        if (!empty($settings['axil_personal_banner_button_style_button_style'])) {
                                            $this->add_render_attribute('axil_personal_banner_button_link_style', 'class', '' . $settings['axil_personal_banner_button_style_button_style'] . '');
                                        }
                                        // Size
                                        if (!empty($settings['axil_personal_banner_button_style_button_size'])) {
                                            $this->add_render_attribute('axil_personal_banner_button_link_style', 'class', $settings['axil_personal_banner_button_style_button_size']);
                                        }
                                        // Link
                                        $button_html = '<a ' . $this->get_render_attribute_string('axil_personal_banner_button_link_style') . ' ' . $this->get_render_attribute_string('axil_personal_banner_button_link') . '>' . '<span class="button-text">' . $settings['axil_personal_banner_button_text'] . '</span>' . $arrow . '</a>';
                                        echo $button_html;
                                    }
                                    ?>

                                    <?php
                                    // Link
                                    if ('2' == $settings['axil_personal_banner_button_two_link_type']) {
                                        $this->add_render_attribute('axil_personal_banner_button_two_link', 'href', get_permalink($settings['axil_personal_banner_button_two_page_link']));
                                        $this->add_render_attribute('axil_personal_banner_button_two_link', 'target', '_self');
                                        $this->add_render_attribute('axil_personal_banner_button_two_link', 'rel', 'nofollow');
                                    } else {
                                        if (!empty($settings['axil_personal_banner_button_two_link']['url'])) {
                                            $this->add_render_attribute('axil_personal_banner_button_two_link', 'href', $settings['axil_personal_banner_button_two_link']['url']);
                                        }
                                        if ($settings['axil_personal_banner_button_two_link']['is_external']) {
                                            $this->add_render_attribute('axil_personal_banner_button_two_link', 'target', '_blank');
                                        }
                                        if (!empty($settings['axil_personal_banner_button_two_link']['nofollow'])) {
                                            $this->add_render_attribute('axil_personal_banner_button_two_link', 'rel', 'nofollow');
                                        }
                                    }
                                    $arrow = ($settings['axil_personal_banner_button_two_style_button_arrow_icon'] == 'yes') ? '<span class="button-icon"></span>' : '';
                                    // Button
                                    if (!empty($settings['axil_personal_banner_button_two_link']['url']) || isset($settings['axil_personal_banner_button_two_link_type'])) {

                                        $this->add_render_attribute('axil_personal_banner_button_two_link_style', 'class', ' axil-button  ');
                                        // Style
                                        if (!empty($settings['axil_personal_banner_button_two_style_button_style'])) {
                                            $this->add_render_attribute('axil_personal_banner_button_two_link_style', 'class', '' . $settings['axil_personal_banner_button_two_style_button_style'] . '');
                                        }
                                        // Size
                                        if (!empty($settings['axil_personal_banner_button_two_style_button_size'])) {
                                            $this->add_render_attribute('axil_personal_banner_button_two_link_style', 'class', $settings['axil_personal_banner_button_two_style_button_size']);
                                        }
                                        // Link
                                        $button_html = '<a ' . $this->get_render_attribute_string('axil_personal_banner_button_two_link_style') . ' ' . $this->get_render_attribute_string('axil_personal_banner_button_two_link') . '>' . '<span class="button-text">' . $settings['axil_personal_banner_button_two_text'] . '</span>' . $arrow . '</a>';
                                        echo $button_html;
                                    }
                                    ?>
                                </div>

                                <?php if($settings['keystroke_personal_banner_mouse_icon'] == 'yes'){ ?>
                                    <div class="scroll-down_btn">
                                        <a id="scrollDown" class="axil-scrollbown smoth-animation"
                                           href="#<?php echo esc_attr($settings['keystroke_personal_banner_mouse_icon_id']); ?>"><span></span></a>
                                    </div>
                                <?php } ?>

                            </div>
                        </div>
                    </div>

                    <?php if ($settings['keystroke_personal_banner_marque_image']) { ?>
                        <div class="design-text marque-images" style="background:url('<?php echo esc_url($settings['keystroke_personal_banner_marque_image']['url']); ?>')"></div>
                    <?php } ?>

                </div>
            </div>
            <!-- End Single Slide -->

            <?php if (!empty($settings['keystroke_personal_banner_image'])) { ?>
                <div class="thumbnail">
                    <?php echo Group_Control_Image_Size::get_attachment_image_html($settings, 'full', 'keystroke_personal_banner_image'); ?>
                </div>
            <?php } ?>

        </div>
        <!-- End Slider Area -->
        <?php

    }

}

Plugin::instance()->widgets_manager->register_widget_type(new keystroke_Elementor_Widget_Personal_Banner());


