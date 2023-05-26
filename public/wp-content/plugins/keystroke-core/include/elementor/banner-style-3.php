<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Axil_Elementor_Widget_BannerStyleThree extends Widget_Base {

    use \Elementor\KeystrokeElementCommonFunctions;

    public function get_name() {
        return 'keystroke-banner-style-three';
    }

    public function get_title() {
        return __( 'Startup Banner', 'keystroke' );
    }

    public function get_icon() {
        return 'axil-icon';
    }

    public function get_categories() {
        return [ 'keystroke' ];
    }

    public function get_keywords()
    {
        return ['banner', 'hero area', 'slider', 'top section', 'startup banner', 'keystroke'];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'axil_banner_style_three',
            [
                'label' => esc_html__( 'Banner', 'keystroke' ),
            ]
        );

        $this->add_control(
            'axil_banner_style_three_title',
            [
                'label' => esc_html__('Title', 'keystroke'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => esc_html__('Build stunning websites & apps.', 'keystroke'),
                'row' => 5,
                'title' => esc_html__('Enter your title here.', 'keystroke'),
            ]
        );
        $this->add_control(
            'axil_banner_style_three_title_tag',
            [
                'label' => __('Title HTML Tag', 'keystroke'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'h1' => [
                        'title' => __('H1', 'keystroke'),
                        'icon' => 'eicon-editor-h1'
                    ],
                    'h2' => [
                        'title' => __('H2', 'keystroke'),
                        'icon' => 'eicon-editor-h2'
                    ],
                    'h3' => [
                        'title' => __('H3', 'keystroke'),
                        'icon' => 'eicon-editor-h3'
                    ],
                    'h4' => [
                        'title' => __('H4', 'keystroke'),
                        'icon' => 'eicon-editor-h4'
                    ],
                    'h5' => [
                        'title' => __('H5', 'keystroke'),
                        'icon' => 'eicon-editor-h5'
                    ],
                    'h6' => [
                        'title' => __('H6', 'keystroke'),
                        'icon' => 'eicon-editor-h6'
                    ]
                ],
                'default' => 'h1',
                'toggle' => false,
            ]
        );
        $this->add_control(
            'axil_banner_style_three_sub_title',
            [
                'label' => esc_html__('Sub Title', 'keystroke'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => esc_html__('Create live segments and target the right people for messages based on their behaviors.', 'keystroke'),
                'description' => axil_get_allowed_html_desc( 'intermediate' ),
                'row' => 10,
                'title' => esc_html__('Enter your sub title content here.', 'keystroke'),
            ]
        );

        $this->axil_link_controls('banner_button_style_three', 'See Our Projects');

        $this->add_control(
            'axil_banner_style_three_feture_image_one',
            [
                'label' => esc_html__('Choose Image One', 'keystroke'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],

            ]
        );

        $this->add_control(
            'axil_banner_style_three_feture_image_two',
            [
                'label' => esc_html__('Choose Image Two', 'keystroke'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],

            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'axil_banner_style_three_banner_social_networks',
            [
                'label' => esc_html__('Social Networks', 'keystroke'),
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'banner_three_social_networks_title', [
                'label' => esc_html__('Title', 'keystroke'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Facebook', 'keystroke'),
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'banner_three_social_networks_link',
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
            'banner_three_social_networks_icon', [
                'label' => esc_html__('Add icons markup', 'keystroke'),
                'description' => esc_html__('Add icon markup like: <i class="fab fa-facebook-f"></i>', 'keystroke'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '<i class="fab fa-facebook-f"></i>',
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'banner_three_social_networks_custom_class', [
                'label' => esc_html__('Add custom class', 'keystroke'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'banner_three_social_networks',
            [
                'label' => esc_html__('Repeater Followers', 'keystroke'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'banner_three_social_networks_title' => esc_html__('Facebook', 'keystroke'),
                        'banner_three_social_networks_icon' => '<i class="fab fa-facebook-f"></i>',
                    ],
                    [
                        'banner_three_social_networks_title' => esc_html__('Twitter', 'keystroke'),
                        'banner_three_social_networks_icon' => '<i class="fab fa-twitter"></i>',
                    ],
                    [
                        'banner_three_social_networks_title' => esc_html__('Linkedin', 'keystroke'),
                        'banner_three_social_networks_icon' => '<i class="fab fa-linkedin-in"></i>',
                    ],

                ],
                'title_field' => '{{{ banner_three_social_networks_title }}}',
            ]
        );

        $this->end_controls_section();

        // Start Style Tabs
        $this->axil_basic_style_controls('axil_banner_style_three_title', 'Title', '.axil-display-1');
        $this->axil_basic_style_controls('axil_banner_style_three_subtitle', 'Sub Title', '.axil-slide.slide-style-4 .content p');
        $this->axil_link_controls_style('banner_button_style_three_button_style', 'Button', '.axil-button');
        $this->axil_section_style_controls('banner_style_three', 'Banner Area', '.axil-slider-area .axil-slide');



    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();
        $this->add_inline_editing_attributes('axil_banner_style_three_title_tag', 'none');
        $this->add_render_attribute('axil_banner_style_three_title_class', 'class', 'axil-display-1 layer1');


        ?>
        <!-- Start Slider Area -->
        <div class="axil-slider-area axil-slide-activation">
            <!-- Start Single Slide -->
            <div class="axil-slide slide-style-4 theme-gradient-4 slider-fixed-height d-flex align-items-center">
                <div class="container">
                    <div class="row align-items-center pt--180 pt_sm--40 pt_md--40">
                        <div class="col-lg-8 col-12 order-2 order-lg-1 mt_md--40 mt_sm--30">
                            <div class="content">
                                <?php if ($settings['axil_banner_style_three_title_tag']) : ?>
                                    <<?php echo tag_escape($settings['axil_banner_style_three_title_tag']); ?> <?php echo $this->get_render_attribute_string('axil_banner_style_three_title_class'); ?>><?php echo esc_html($settings['axil_banner_style_three_title']); ?></<?php echo tag_escape($settings['axil_banner_style_three_title_tag']) ?>>
                                <?php endif; ?>
                                <?php if (!empty($settings['axil_banner_style_three_sub_title'])) { ?>
                                    <p class="layer2" ><?php echo axil_kses_intermediate( $settings['axil_banner_style_three_sub_title'] ); ?></p>
                                <?php } ?>

                                <div class="slider-button">
                                    <?php
                                    // Link
                                    if ('2' == $settings['axil_banner_button_style_three_link_type']) {
                                        $this->add_render_attribute('axil_banner_button_style_three_link', 'href', get_permalink($settings['axil_banner_button_style_three_page_link']));
                                        $this->add_render_attribute('axil_banner_button_style_three_link', 'target', '_self');
                                        $this->add_render_attribute('axil_banner_button_style_three_link', 'rel', 'nofollow');
                                    } else {
                                        if (!empty($settings['axil_banner_button_style_three_link']['url'])) {
                                            $this->add_render_attribute('axil_banner_button_style_three_link', 'href', $settings['axil_banner_button_style_three_link']['url']);
                                        }
                                        if ($settings['axil_banner_button_style_three_link']['is_external']) {
                                            $this->add_render_attribute('axil_banner_button_style_three_link', 'target', '_blank');
                                        }
                                        if (!empty($settings['axil_banner_button_style_three_link']['nofollow'])) {
                                            $this->add_render_attribute('axil_banner_button_style_three_link', 'rel', 'nofollow');
                                        }
                                    }
                                    $arrow = ($settings['axil_banner_button_style_three_button_style_button_arrow_icon'] == 'yes') ? '<span class="button-icon"></span>' : '';
                                    // Button
                                    if (!empty($settings['axil_banner_button_style_three_link']['url']) || isset($settings['axil_banner_button_style_three_link_type'])) {

                                        $this->add_render_attribute('axil_banner_button_style_three_link', 'class', ' axil-button btn-extra07-color ');
                                        // Style
                                        if (!empty($settings['axil_banner_button_style_three_button_style_button_style'])) {
                                            $this->add_render_attribute('axil_banner_button_style_three_link', 'class', '' . $settings['axil_banner_button_style_three_button_style_button_style'] . '');
                                        }
                                        // Size
                                        if (!empty($settings['axil_banner_button_style_three_button_style_button_size'])) {
                                            $this->add_render_attribute('axil_banner_button_style_three_link', 'class', $settings['axil_banner_button_style_three_button_style_button_size']);
                                        }
                                        // Link
                                        $button_html = '<a ' . $this->get_render_attribute_string('axil_banner_button_style_three_link') . '>' . '<span class="button-text">' . $settings['axil_banner_button_style_three_text'] . '</span>' . $arrow . '</a>';
                                    }
                                    if (!empty($settings['axil_banner_button_style_three_text'])) {
                                        echo $button_html;
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 order-1 order-lg-2">
                            <div class="thumbnail d-flex">
                                <?php if($settings['axil_banner_style_three_feture_image_one']['url']){?>
                                    <div class="image image-one">
                                        <?php echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'full', 'axil_banner_style_three_feture_image_one', '', array( "class" => "paralax-image" )  ); ?>
                                    </div>
                                <?php } ?>
                                <?php if($settings['axil_banner_style_three_feture_image_two']['url']){?>
                                    <div class="image image-two">
                                        <?php echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'full', 'axil_banner_style_three_feture_image_two', '', array( "class" => "paralax-image" )  ); ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <?php if($settings['banner_three_social_networks']){ ?>
                            <div class="col-lg-12 order-3 order-lg-3">
                                <div class="follow-us">
                                    <ul class="social-share social-share-style-2">
                                        <?php
                                        foreach ($settings['banner_three_social_networks'] as $item) {
                                            $target = $item['banner_three_social_networks_link']['is_external'] ? ' target="_blank"' : '';
                                            $nofollow = $item['banner_three_social_networks_link']['nofollow'] ? ' rel="nofollow"' : '';
                                            ?>
                                            <li class="<?php echo esc_attr($item['banner_three_social_networks_custom_class']); ?> elementor-repeater-item-<?php echo $item['_id']; ?>"><a
                                                        href="<?php echo esc_url($item['banner_three_social_networks_link']['url']); ?>" <?php echo esc_attr($target); ?> <?php echo esc_attr($nofollow); ?>><?php echo keystroke_escapeing($item['banner_three_social_networks_icon']); ?>
                                                    <span><?php echo esc_html($item['banner_three_social_networks_title']); ?></span></a></li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <!-- End Single Slide -->
        </div>
        <!-- End Slider Area -->
        <?php


    }

}

Plugin::instance()->widgets_manager->register_widget_type( new Axil_Elementor_Widget_BannerStyleThree() );


