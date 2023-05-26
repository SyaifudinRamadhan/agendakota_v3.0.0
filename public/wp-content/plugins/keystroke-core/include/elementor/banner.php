<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Axil_Elementor_Widget_Banner extends Widget_Base {

    use \Elementor\KeystrokeElementCommonFunctions;

    public function get_name() {
        return 'keystroke-banner';
    }
    
    public function get_title() {
        return __( 'Digital Agency Banner', 'keystroke' );
    }

    public function get_icon() {
        return 'axil-icon';
    }

    public function get_categories() {
        return [ 'keystroke' ];
    }

    public function get_keywords()
    {
        return ['banner', 'hero area', 'slider', 'top section', 'digital agency banner', 'keystroke'];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'axil_banner',
            [
                'label' => esc_html__( 'Banner', 'keystroke' ),
            ]
        );

        $this->add_control(
            'axil_banner_title',
            [
                'label' => esc_html__('Title', 'keystroke'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => esc_html__('Build stunning websites & apps.', 'keystroke'),
                'row' => 5,
                'title' => esc_html__('Enter your title here.', 'keystroke'),
            ]
        );
        $this->add_control(
            'axil_banner_title_tag',
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
            'axil_banner_sub_title',
            [
                'label' => esc_html__('Sub Title', 'keystroke'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => esc_html__('Create live segments and target the right people for
                messages based on their behaviors.', 'keystroke'),
                'description' => axil_get_allowed_html_desc( 'intermediate' ),
                'row' => 10,
                'title' => esc_html__('Enter your sub title content here.', 'keystroke'),
            ]
        );

        $this->axil_link_controls('banner_button', 'View Showcase');


        $this->add_control(
            'axil_banner_image',
            [
                'label' => esc_html__('Choose Banner Top Image', 'keystroke'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],

            ]
        );
        $this->add_control(
            'axil_banner_box_image',
            [
                'label' => esc_html__('Choose Banner Box Image', 'keystroke'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],

            ]
        );


        $this->add_control(
			'axil_image_shape_1',
			[
				'label'        => esc_html__('Shape One', 'keystroke'),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Yes', 'keystroke'),
				'label_off'    => esc_html__('No', 'keystroke'),
				'return_value' => 'yes',
				'separator'    => 'before',
				'default'      => 'yes'
			]
        );
        
        $this->add_control(
			'axil_image_shape_2',
			[
				'label'        => esc_html__('Shape Two', 'keystroke'),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Yes', 'keystroke'),
				'label_off'    => esc_html__('No', 'keystroke'),
				'return_value' => 'yes',
				'separator'    => 'before',
				'default'      => 'yes'
			]
        );
        $this->add_control(
			'axil_image_shape_3',
			[
				'label'        => esc_html__('Shape Three', 'keystroke'),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Yes', 'keystroke'),
				'label_off'    => esc_html__('No', 'keystroke'),
				'return_value' => 'yes',
				'separator'    => 'before',
				'default'      => 'yes'
			]
		);


        $this->end_controls_section();

        // Start Style Tabs
        $this->axil_basic_style_controls('axil_banner_title', 'Title', '.axil-display-1');
        $this->axil_basic_style_controls('axil_banner_subtitle', 'Sub Title', '.subtitle-3');
        $this->axil_link_controls_style('banner_button_style', 'Button', '.axil-button');
        $this->axil_section_style_controls('banner', 'Banner Area', '.axil-slider-area .axil-slide');

            
        
    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();
        $this->add_inline_editing_attributes('axil_banner_title_tag', 'none');
        $this->add_render_attribute('axil_banner_title_class', 'class', 'axil-display-1 ');


        ?>
        <!-- Start Slider Area -->
        <div class="axil-slider-area axil-slide-activation">

            <!-- Start Single Slide -->
            <div class="theme-gradient">
                <div class="axil-slide slide-style-default  slider-fixed-height d-flex align-items-center paralax-area">
                    <div class="container">
                        <div class="row align-items-center pt_md--60 mt_sm--60">
                            <div class="col-lg-7 col-12 order-2 order-lg-1">
                                <div class="content pt_md--30 pt_sm--30">

                                    <?php if ($settings['axil_banner_title_tag']) : ?>
                                        <<?php echo tag_escape($settings['axil_banner_title_tag']); ?> <?php echo $this->get_render_attribute_string('axil_banner_title_class'); ?>><?php echo esc_html($settings['axil_banner_title']); ?></<?php echo tag_escape($settings['axil_banner_title_tag']) ?>>
                                    <?php endif; ?>
                                    <?php if (!empty($settings['axil_banner_sub_title'])) { ?>
                                        <p class="subtitle-3"><?php echo axil_kses_intermediate( $settings['axil_banner_sub_title'] ); ?></p>
                                    <?php } ?>

                                    <?php 

                                    // Link
                                    if ('2' == $settings['axil_banner_button_link_type']) {
                                        $this->add_render_attribute('axil_banner_button_link', 'href', get_permalink($settings['axil_banner_button_page_link']));
                                        $this->add_render_attribute('axil_banner_button_link', 'target', '_self');
                                        $this->add_render_attribute('axil_banner_button_link', 'rel', 'nofollow');
                                    } else {
                                        if (!empty($settings['axil_banner_button_link']['url'])) {
                                            $this->add_render_attribute('axil_banner_button_link', 'href', $settings['axil_banner_button_link']['url']);
                                        }
                                        if ($settings['axil_banner_button_link']['is_external']) {
                                            $this->add_render_attribute('axil_banner_button_link', 'target', '_blank');
                                        }
                                        if (!empty($settings['axil_banner_button_link']['nofollow'])) {
                                            $this->add_render_attribute('axil_banner_button_link', 'rel', 'nofollow');
                                        }
                                    }
                                    $arrow = ($settings['axil_banner_button_style_button_arrow_icon'] == 'yes') ? '<span class="button-icon"></span>' : '';
                                    // Button
                                    if (!empty($settings['axil_banner_button_link']['url']) || isset($settings['axil_banner_button_link_type'])) {

                                        $this->add_render_attribute('axil_banner_button_link', 'class', ' axil-button  ');
                                        // Style
                                        if (!empty($settings['axil_banner_button_style_button_style'])) {
                                            $this->add_render_attribute('axil_banner_button_link', 'class', '' . $settings['axil_banner_button_style_button_style'] . '');
                                        }
                                        // Size
                                        if (!empty($settings['axil_banner_button_style_button_size'])) {
                                            $this->add_render_attribute('axil_banner_button_link', 'class', $settings['axil_banner_button_style_button_size']);
                                        }
                                        // Link
                                        $button_html = '<a ' . $this->get_render_attribute_string('axil_banner_button_link') . '>' . '<span class="button-text">' . $settings['axil_banner_button_text'] . '</span>' . $arrow . '</a>';
                                    }
                                    if (!empty($settings['axil_banner_button_text'])) {
                                        echo $button_html;
                                    }

                                    ?>

                                </div>

                            </div>
                            <div class="col-lg-5 col-12 order-1 order-lg-2">
                                <div class="topskew-thumbnail-group text-left text-lg-right">

                                    <?php if(!empty($settings['axil_banner_box_image'])){?> 
                                        <div class="thumbnail paralax-image">
                                        <?php echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'full', 'axil_banner_box_image' ); ?>
                                        </div>
                                    <?php } ?>
                                    <?php if(!empty($settings['axil_banner_image'])){?> 
                                        <div class="image-group">
                                            <?php echo wp_get_attachment_image( $settings['axil_banner_image']['id'], 'full', '', array( "class" => "paralax-image" ) ); ?>
                                        </div>
                                     <?php } ?>
                                   
                                    <div class="shape-group">

                                    <?php if($settings['axil_image_shape_1']){ ?>
                                        <div class="shape shape-1 paralax--1">
                                            <span class="icon icon-breadcrumb-2"></span>
                                        </div>
                                    <?php } ?>

                                    <?php if($settings['axil_image_shape_2']){ ?>
                                        <div class="shape shape-2 customOne">
                                            <span class="icon icon-shape-06"></span>
                                        </div>
                                    <?php } ?>

                                    <?php if($settings['axil_image_shape_3']){ ?>
                                        <div class="shape shape-3 paralax--3">
                                            <span class="icon icon-shape-04"></span>
                                        </div>
                                    <?php } ?>
                                        
                                        
                                    </div>
                                </div>
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

Plugin::instance()->widgets_manager->register_widget_type( new Axil_Elementor_Widget_Banner() );


