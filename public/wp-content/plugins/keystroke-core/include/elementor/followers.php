<?php

namespace Elementor;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class keystroke_Elementor_Widget_Followers extends Widget_Base
{

    use \Elementor\KeystrokeElementCommonFunctions;

    public function get_name()
    {
        return 'keystroke-followers';
    }

    public function get_title()
    {
        return __('Followers', 'keystroke');
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
        return ['followers', 'social icons', 'keystroke'];
    }

    protected function _register_controls()
    {

        $this->start_controls_section(
            'keystroke_followers',
            [
                'label' => esc_html__('Followers', 'keystroke'),
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'followers_title', [
                'label' => esc_html__('Title', 'keystroke'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Followers Title', 'keystroke'),
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'followers_link',
            [
                'label' => esc_html__('Link', 'keystroke'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => esc_html__('https://your-link.com', 'keystroke'),
                'show_external' => true,
                'default' => [
                    'url' => '',
                    'is_external' => true,
                    'nofollow' => true,
                ],
            ]
        );
        $repeater->add_control(
            'followers_icon', [
                'label' => esc_html__('Add icons markup', 'keystroke'),
                'description' => esc_html__('Add icon markup like: <i class="fab fa-dribbble"></i>', 'keystroke'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '<i class="fab fa-dribbble"></i>',
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'followers_icon_color',
            [
                'label' => esc_html__('Color', 'keystroke'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} a i' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );
        $repeater->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'followers_icon_color_shadow',
                'label' => esc_html__( 'Box Shadow', 'keystroke' ),
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} a i',
            ]
        );
        $repeater->add_control(
            'followers_custom_class', [
                'label' => esc_html__('Add custom class', 'keystroke'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'dribbble',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'followers',
            [
                'label' => esc_html__('Repeater Followers', 'keystroke'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'followers_title' => esc_html__('60k Followers', 'keystroke'),
                        'followers_icon' => '<i class="fab fa-dribbble"></i>',
                        'followers_icon_color' => '#EA4C89',
                        'followers_custom_class' => 'dribbble',
                    ],
                    [
                        'followers_title' => esc_html__('30k Followers', 'keystroke'),
                        'followers_icon' => '<i class="fab fa-behance"></i>',
                        'followers_icon_color' => '#0067FF',
                        'followers_custom_class' => 'behance',
                    ],
                    [
                        'followers_title' => esc_html__('13k Followers', 'keystroke'),
                        'followers_icon' => '<i class="fab fa-linkedin-in"></i>',
                        'followers_icon_color' => '#0177AC',
                        'followers_custom_class' => 'linkedin',
                    ],

                ],
                'title_field' => '{{{ followers_title }}}',
            ]
        );

        $this->end_controls_section();


        $this->axil_section_style_controls('followers_area', 'Area Background', '.axil-testimonial-area.pb--130.bg-color-white');

    }

    protected function render($instance = [])
    {

        $settings = $this->get_settings_for_display();

        if ($settings['followers']) { ?>
            <div class="axil-testimonial-area pb--120 bg-color-white">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="axil-followers">
                                <ul class="followers-list">
                                    <?php
                                    foreach ($settings['followers'] as $item) {
                                        $target = $item['followers_link']['is_external'] ? ' target="_blank"' : '';
                                        $nofollow = $item['followers_link']['nofollow'] ? ' rel="nofollow"' : '';
                                        ?>
                                        <li class="<?php echo esc_attr($item['followers_custom_class']); ?> elementor-repeater-item-<?php echo $item['_id']; ?>"><a
                                                    href="<?php echo esc_url($item['followers_link']['url']); ?>" <?php echo esc_attr($target); ?> <?php echo esc_attr($nofollow); ?>><?php echo keystroke_escapeing($item['followers_icon']); ?>
                                                <span><?php echo esc_html($item['followers_title']); ?></span></a></li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php
    }

}

Plugin::instance()->widgets_manager->register_widget_type(new keystroke_Elementor_Widget_Followers());


