<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Axil_Elementor_Widget_Services extends Widget_Base {

    use \Elementor\KeystrokeElementCommonFunctions;

    public function get_name() {
        return 'keystroke-services';
    }
    
    public function get_title() {
        return esc_html__( 'Services', 'keystroke' );
    }

    public function get_icon() {
        return 'axil-icon';
    }

    public function get_categories() {
        return [ 'keystroke' ];
    }

    public function get_keywords()
    {
        return ['services', 'services box', 'keystroke'];
    }

    protected function _register_controls() {

        $this->axil_section_title('services', 'what we can do for you', 'Services we can help you with', 'h2', 'In vel varius turpis, non dictum sem. Aenean in efficitur ipsum, in egestas ipsum. Mauris in mi ac tellus.', 'true', 'text-center');

        $this->start_controls_section(
            'services_layout_panel',
            [
                'label' => esc_html__( 'Services Layout', 'keystroke' ),
            ]
        );
        $this->add_control(
            'layout',
            [
                'label' => esc_html__( 'Select Layout', 'keystroke' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'layout-1',
                'options' => [
                    'layout-1' => esc_html('Layout One', 'keystroke'),
                    'layout-2' => esc_html('Layout Two', 'keystroke'),
                ]
            ]
        );

        $this->end_controls_section();

        // Services group
        $this->start_controls_section(
			'services',
			[
				'label' => esc_html__( 'Services', 'keystroke' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'service_icon_type',
            [
                'label' => esc_html__('Select Icon Type', 'keystroke'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'icon',
                'options' => [
                    'image' => esc_html__('Image', 'keystroke'),
                    'icon' => esc_html__('Icon', 'keystroke'),
                ],
            ]
        );
        $repeater->add_control(
            'service_image',
            [
                'label' => esc_html__('Upload Image', 'keystroke'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'service_icon_type' => 'image'
                ]

            ]
        );


        if ( axil_is_elementor_version( '<', '2.6.0' ) ) {
            $repeater->add_control(
                'services_icon',
                [
                    'show_label' => false,
                    'type' => Controls_Manager::ICON,
                    'label_block' => true,
                    'default' => 'fa fa-pen',
                    'condition' => [
                        'service_icon_type' => 'icon'
                    ]
                ]
            );
        } else {
            $repeater->add_control(
                'services_selected_icon',
                [
                    'show_label' => false,
                    'type' => Controls_Manager::ICONS,
                    'fa4compatibility' => 'icon',
                    'label_block' => true,
                    'default' => [
                        'value' => 'fas fa-pen-nib',
                        'library' => 'fa-solid',
                    ],
                    'condition' => [
                        'service_icon_type' => 'icon'
                    ]

                ]
            );  
        }
        $repeater->add_control(
            'services_icon_bg_color',
            [
                'label' => esc_html__( 'Service Icon Background Color', 'keystroke' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .axil-service .inner .icon .icon-inner' => 'background: {{VALUE}};',
                ],
            ]
        );

        $repeater->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'services_icon_shape_bg_color',
				'label' => esc_html__( 'Service Icon Background Shape Color', 'keystroke' ),
				'types' => [ 'classic', 'gradient'],
				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .axil-service .inner .icon::before',
			]
        );

        $repeater->add_control(
			'services_title', [
				'label' => esc_html__( 'Title', 'keystroke' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Service Title' , 'keystroke' ),
				'label_block' => true,
			]
		);

        $repeater->add_control(
			'services_content',
			[
				'label' => esc_html__( 'Description', 'keystroke' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'rows' => 10,
				'default' => 'We design professional looking yet simple websites. Our designs are search engine and user friendly.',
				'placeholder' => esc_html__( 'Type your description here', 'keystroke' ),
			]
		);
        $repeater->add_control(
            'services_link_text',
            [
                'label' => esc_html__( 'Service Link Text', 'keystroke' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Read More',
                'title' => esc_html__( 'Enter button text', 'keystroke' ),
            ]
        );
        
        $repeater->add_control(
            'services_link_type',
            [
                'label' => esc_html__( 'Service Link Type', 'keystroke' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '1' => 'Custom Link',
                    '2' => 'Internal Page',
                ],
                'default' => '1',
            ]
        );
        $repeater->add_control(
            'services_link',
            [
                'label' => esc_html__( 'Service Link link', 'keystroke' ),
                'type' => \Elementor\Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => esc_html__( 'https://your-link.com', 'keystroke' ),
                'show_external' => true,
                'default' => [
                    'url' => '#',
                    'is_external' => true,
                    'nofollow' => true,
                ],
                'condition' => [
                    'services_link_type' => '1'
                ]
            ]
        );
        $repeater->add_control(
            'services_page_link',
            [
                'label' => esc_html__( 'Select Service Link Page', 'keystroke' ),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'label_block' => true,
                'options' => axil_get_all_pages(),
                'condition' => [
                    'services_link_type' => '2'
                ]
            ]
        );
		$this->add_control(
			'service_list',
			[
				'label' => esc_html__( 'Services List', 'keystroke' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' =>  $repeater->get_controls(),
				'default' => [
                    [
                        'services_title' => esc_html__( 'Title #1', 'keystroke' ),
                        'services_content' => esc_html__( 'We design professional looking yet simple websites. Our designs are search engine and user friendly.', 'keystroke' ),
                        'services_link_text' => esc_html__( 'Learn More', 'keystroke' ),
                    ],
                    [
                        'services_title' => esc_html__( 'Title #2', 'keystroke' ),
                        'services_content' => esc_html__( 'We design professional looking yet simple websites. Our designs are search engine and user friendly.', 'keystroke' ),
                        'services_link_text' => esc_html__( 'Learn More', 'keystroke' ),
                    ],
                    [
                        'services_title' => esc_html__( 'Title #3', 'keystroke' ),
                        'services_content' => esc_html__( 'We design professional looking yet simple websites. Our designs are search engine and user friendly.', 'keystroke' ),
                        'services_link_text' => esc_html__( 'Learn More', 'keystroke' ),
                    ],
                    [
                        'services_title' => esc_html__( 'Title #4', 'keystroke' ),
                        'services_content' => esc_html__( 'We design professional looking yet simple websites. Our designs are search engine and user friendly.', 'keystroke' ),
                        'services_link_text' => esc_html__( 'Learn More', 'keystroke' ),
                    ],
                    [
                        'services_title' => esc_html__( 'Title #5', 'keystroke' ),
                        'services_content' => esc_html__( 'We design professional looking yet simple websites. Our designs are search engine and user friendly.', 'keystroke' ),
                        'services_link_text' => esc_html__( 'Learn More', 'keystroke' ),
                    ],
                    [
                        'services_title' => esc_html__( 'Title #6', 'keystroke' ),
                        'services_content' => esc_html__( 'We design professional looking yet simple websites. Our designs are search engine and user friendly.', 'keystroke' ),
                        'services_link_text' => esc_html__( 'Learn More', 'keystroke' ),
                    ],
                ],
				'title_field' => '{{{ services_title }}}',
			]
        );
        $this->add_control(
            'style',
            [
                'label' => esc_html__( 'Select Layout', 'keystroke' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'style-1',
                'options' => [
                    'style-1' => esc_html('Style One', 'keystroke'),
                    'style-2' => esc_html('Style Two', 'keystroke'),
                ]
            ]
        );
    
		$this->end_controls_section();

        $this->axil_basic_style_controls('section_title_pre_title', 'Tag Line', '.section-title span.sub-title');
        $this->axil_basic_style_controls('section_title_title', 'Title', '.section-title .title');
        $this->axil_basic_style_controls('section_title_description', 'Description', '.section-title p');

        $this->axil_basic_style_controls('service_title', 'Service Box Title', '.axil-service .inner .content .title');
        $this->axil_basic_style_controls('service_content', 'Service Box Content', '.axil-service .inner .content p');

        $this->axil_section_style_controls('services_area', 'Service Area', '.axil-service-area');


        
    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();
        $this->add_render_attribute('title_args', 'class', 'title wow');
        $this->add_render_attribute('title_args', 'data-wow-duration', '1s');
        $this->add_render_attribute('title_args', 'data-wow-delay', '500ms');

        $section_title_col_class = ($settings['layout'] == 'layout-2') ? 'col-lg-8 col-xl-6' : 'col-lg-12';
        $section_title_class = ($settings['layout'] == 'layout-2') ? 'section-title ' . $settings['axil_services_align'] : 'section-title ' . $settings['axil_services_align'];

        $style = ($settings['style'] == 'style-2') ? "text-left" : "text-center";

        ?>
        <!-- Start Service Area -->
        <div class="axil-service-area ax-section-gap bg-color-white <?php echo esc_attr($settings['layout']); ?>">
                <div class="container">
                    <div class="row">
                        <div class="<?php echo esc_attr($section_title_col_class); ?>">
                            <div class="<?php echo esc_attr($section_title_class); ?>">
                                <?php $this->axil_section_title_render('services','extra08-color', $this->get_settings()); ?>
                            </div>
                        </div>
                    </div>
                    <?php
                        if ( $settings['service_list'] ) {
                            echo '<div class="row">';
                            $i = 1;
                            foreach (  $settings['service_list'] as $service ) {
                                $active = ($i == 1) ? 'active' : '';
                                ?>
                                <!-- Start Single Service -->
                                <div class="col-lg-4 col-md-6 col-sm-6 col-12 move-up wow service-item elementor-repeater-item-<?php echo $service['_id']; ?>">
                                    <div class="axil-service <?php echo esc_attr($style); ?> axil-control paralax-image <?php echo esc_attr($active); ?>">
                                        <div class="inner">
                                            <div class="icon">
                                                <div class="icon-inner">
                                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/layer.png" alt="Icon Images">

                                                    <?php if($service['service_icon_type'] !== 'image'){ ?>
                                                        <?php if ( ! empty( $service['services_icon'] ) || ! empty( $service['services_selected_icon']['value'] ) ) : ?>
                                                            <div class="image-2">
                                                                <?php axil_render_icon( $service, 'services_icon', 'services_selected_icon' ); ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    <?php } else {
                                                        if (!empty($service['service_image'])){ ?>
                                                            <div class="image-2">
                                                                <?php echo Group_Control_Image_Size::get_attachment_image_html($service, 'full', 'service_image'); ?>
                                                            </div>
                                                        <?php } ?>
                                                    <?php } ?>

                                                </div>
                                            </div>
                                            <?php
                                            // Link
                                            if ('2' == $service['services_link_type']) {
                                                $link = get_permalink($service['services_page_link']);
                                                $target = '_self';
                                                $rel = 'nofollow';
                                            } else {
                                                $link = !empty($service['services_link']['url']) ? $service['services_link']['url'] : '';
                                                $target = ($service['services_link']['is_external']) ? '_blank' : '';
                                                $rel = !empty($service['services_link']['nofollow']) ? 'nofollow' : '';
                                            }
                                            ?>
                                            <div class="content">
                                                <h4 class="title wow">
                                                    <?php if(!empty($link)){ ?> <a href="<?php echo esc_url($link); ?>"
                                                                                   target="<?php echo esc_attr($target); ?>"
                                                                                   rel="<?php echo esc_attr($rel); ?>"> <?php } ?>
                                                    <?php echo esc_html( $service['services_title'] ) ?>
                                                        <?php if(!empty($link)){ ?> </a> <?php } ?>
                                                </h4>
                                                <p class="wow"><?php echo axil_kses_intermediate( $service['services_content'] ) ?></p>



                                                <?php if (!empty($link)): ?>
                                                    <a href="<?php echo esc_url($link); ?>"
                                                       target="<?php echo esc_attr($target); ?>"
                                                       rel="<?php echo esc_attr($rel); ?>"
                                                       class="axil-button" data-hover="<?php echo esc_html($service['services_link_text']); ?>"><?php echo esc_html($service['services_link_text']); ?></a>
                                                <?php endif ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Single Service -->
                            <?php $i++; }
                            echo '</div>';
                        }
                    ?>
                </div>
            </div>
        <!-- End Service Area -->
        <?php

    }

}

Plugin::instance()->widgets_manager->register_widget_type( new Axil_Elementor_Widget_Services() );


