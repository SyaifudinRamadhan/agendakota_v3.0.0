<?php

namespace Elementor;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class keystroke_Elementor_Widget_Funfact extends Widget_Base
{

    use \Elementor\KeystrokeElementCommonFunctions;

    public function get_name()
    {
        return 'keystroke-funfact';
    }

    public function get_title()
    {
        return __('Funfact', 'keystroke');
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
        return ['funfact', 'counter', 'number', 'keystroke'];
    }

    protected function _register_controls()
    {

        $this->axil_section_title('funfacts', 'experts in field', 'Design startup movement');

        $this->start_controls_section(
            'funfact_layout_panel',
            [
                'label' => esc_html__( 'Funfact Layout', 'keystroke' ),
            ]
        );
        $this->add_control(
            'layout',
            [
                'label' => esc_html__( 'Select Layout', 'keystroke' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'layout-1',
                'options' => [
                    'layout-1' => esc_html('Style One', 'keystroke'),
                    'layout-2' => esc_html('Style Two', 'keystroke'),
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'funfacts_button_area',
            [
                'label' => esc_html__('Button', 'keystroke'),
                'condition' => [
                    'layout' => 'layout-2',
                ]
            ]
        );
        $this->axil_link_controls('funfacts_section_button', 'Careers', 'Careers');
        $this->end_controls_section();

        // Funfact group
        $this->start_controls_section(
            'funfact',
            [
                'label' => esc_html__('Funfact List', 'keystroke'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();


        $repeater->add_control(
            'funfact_icon_type',
            [
                'label' => esc_html__('Select Icon Type', 'keystroke'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'image',
                'options' => [
                    'image' => esc_html__('Image', 'keystroke'),
                    'icon' => esc_html__('Icon', 'keystroke'),
                ],
            ]
        );

        $repeater->add_control(
            'funfact_image',
            [
                'label' => esc_html__('Upload Image', 'keystroke'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'funfact_icon_type' => 'image'
                ]

            ]
        );
        if (axil_is_elementor_version('<', '2.6.0')) {
            $repeater->add_control(
                'funfact_icon',
                [
                    'show_label' => false,
                    'type' => Controls_Manager::ICON,
                    'label_block' => true,
                    'default' => 'fa fa-pen',
                    'condition' => [
                        'funfact_icon_type' => 'icon'
                    ]
                ]
            );
        } else {
            $repeater->add_control(
                'funfact_selected_icon',
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
                        'funfact_icon_type' => 'icon'
                    ]
                ]
            );
        }
        $repeater->add_control(
            'funfact_icon_color',
            [
                'label' => esc_html__('Icon Color', 'keystroke'),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'funfact_icon_type' => 'icon'
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .axil-counterup.counter-first .icon img' => 'color: {{VALUE}};',
                    '{{WRAPPER}} {{CURRENT_ITEM}} .axil-counterup.counter-first .icon i' => 'color: {{VALUE}};',
                ],
            ]
        );
        $repeater->add_control(
            'funfact_icon_bg_color',
            [
                'label' => esc_html__('Icon Background Color', 'keystroke'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .axil-counterup .icon' => 'background: {{VALUE}};',
                ],
            ]
        );


        $repeater->add_control(
            'funfact_title', [
                'label' => esc_html__('Title', 'keystroke'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Funfact Title', 'keystroke'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'funfact_number',
            [
                'label' => esc_html__('Number', 'keystroke'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '50',
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'funfact_number_sup',
            [
                'label' => esc_html__('Select Funfact Number Sup', 'keystroke'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'counter-plus',
                'options' => [
                    'counter-none' => esc_html__('None', 'keystroke'),
                    'counter-plus' => esc_html__('Plus(+)', 'keystroke'),
                    'counter-percentage' => esc_html__('Percentage(%)', 'keystroke'),
                    'counter-k' => esc_html__('Thousand(K)', 'keystroke'),
                ],
                'label_block' => true,
            ]
        );

        $this->add_control(
            'funfact_list',
            [
                'label' => esc_html__('Funfacts List', 'keystroke'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'funfact_title' => esc_html__('Years of operation', 'keystroke'),
                        'funfact_number' => '50',
                    ],
                    [
                        'funfact_title' => esc_html__('Projects deliverd', 'keystroke'),
                        'funfact_number' => '360',
                    ],
                    [
                        'funfact_title' => esc_html__('Specialist', 'keystroke'),
                        'funfact_number' => '600',
                    ],
                    [
                        'funfact_title' => esc_html__('Years of operation', 'keystroke'),
                        'funfact_number' => '64',
                    ],
                ],
                'title_field' => '{{{ funfact_title }}}',
            ]
        );
        $this->end_controls_section();

        /**
         * Start Style Tab
         */
        $this->axil_basic_style_controls('funfact_title_pre_title', 'Tag Line', '.section-title span.sub-title');
        $this->axil_basic_style_controls('funfact_title_title', 'Title', '.section-title .title');
        $this->axil_basic_style_controls('funfact_title_description', 'Description', '.section-title p');

        // Link Style Control
        $this->axil_link_controls_style('funfacts_section_button_style', 'Careers Button', '.axil-button');

        $this->axil_basic_style_controls('funfact_number', 'Number', '.axil-counterup h3.count');
        $this->axil_basic_style_controls('funfact_title', 'Title', '.axil-counterup p');

        $this->axil_section_style_controls('funfact_area', 'Area Style', '.axil-counterup-area');

    }

    protected function render($instance = [])
    {

        $settings = $this->get_settings_for_display();
        $this->add_render_attribute('title_args', 'class', 'title wow');


        if ( $settings['layout'] == 'layout-2' ) { ?>
            <!-- Start Counterup Area -->
            <div class="axil-counterup-area ax-section-gap bg-color-white funfact-layout-2">
                <div class="container">
                    <div class="row align-items-center">
                        <!-- Start Section Title -->
                        <div class="col-lg-5 col-12">
                            <div class="section-title text-left">
                                <?php $this->axil_section_title_render('funfacts','extra08-color', $this->get_settings()); ?>


                                <div class="view-all-portfolio-button mt--40">
                                    <?php
                                    // Link
                                    if ('2' == $settings['axil_funfacts_section_button_link_type']) {
                                        $this->add_render_attribute('axil_funfacts_section_button_link', 'href', get_permalink($settings['axil_funfacts_section_button_page_link']));
                                        $this->add_render_attribute('axil_funfacts_section_button_link', 'target', '_self');
                                        $this->add_render_attribute('axil_funfacts_section_button_link', 'rel', 'nofollow');
                                    } else {
                                        if (!empty($settings['axil_funfacts_section_button_link']['url'])) {
                                            $this->add_render_attribute('axil_funfacts_section_button_link', 'href', $settings['axil_funfacts_section_button_link']['url']);
                                        }
                                        if ($settings['axil_funfacts_section_button_link']['is_external']) {
                                            $this->add_render_attribute('axil_funfacts_section_button_link', 'target', '_blank');
                                        }
                                        if (!empty($settings['axil_funfacts_section_button_link']['nofollow'])) {
                                            $this->add_render_attribute('axil_funfacts_section_button_link', 'rel', 'nofollow');
                                        }
                                    }
                                    $arrow = ($settings['axil_funfacts_section_button_style_button_arrow_icon'] == 'yes') ? '<span class="button-icon"></span>' : '';
                                    // Button
                                    if (!empty($settings['axil_funfacts_section_button_link']['url']) || isset($settings['axil_funfacts_section_button_link_type'])) {

                                        $this->add_render_attribute('axil_funfacts_section_button_link_style', 'class', ' axil-button wow slideFadeInUp ');
                                        // Style
                                        if (!empty($settings['axil_funfacts_section_button_style_button_style'])) {
                                            $this->add_render_attribute('axil_funfacts_section_button_link_style', 'class', '' . $settings['axil_funfacts_section_button_style_button_style'] . '');
                                        }
                                        // Size
                                        if (!empty($settings['axil_funfacts_section_button_style_button_size'])) {
                                            $this->add_render_attribute('axil_funfacts_section_button_link_style', 'class', $settings['axil_funfacts_section_button_style_button_size']);
                                        }
                                        // Link
                                        $button_html = '<a ' . $this->get_render_attribute_string('axil_funfacts_section_button_link_style') . ' ' . $this->get_render_attribute_string('axil_funfacts_section_button_link') . '>' . '<span class="button-text">' . $settings['axil_funfacts_section_button_text'] . '</span>' . $arrow . '</a>';
                                        echo $button_html;
                                    }
                                    ?>
                                </div>

                            </div>
                        </div>
                        <!-- End Section Title -->
                        <?php if ($settings['funfact_list']) { ?>
                            <div class="col-lg-6 offset-xl-1 col-12 mt_md--40 mt_sm--40">
                                <div class="row">
                                    <?php foreach ($settings['funfact_list'] as $funfact) { ?>
                                        <!-- Start Counterup Area -->
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-6  funfact-item elementor-repeater-item-<?php echo $funfact['_id']; ?>">
                                            <div class="axil-counterup mt--60 text-center counter-first move-up wow">
                                                <?php if($funfact['funfact_icon_type'] !== 'image'){ ?>
                                                    <?php if (!empty($funfact['funfact_icon']) || !empty($funfact['funfact_selected_icon']['value'])) : ?>
                                                        <div class="icon">
                                                            <?php axil_render_icon($funfact, 'funfact_icon', 'funfact_selected_icon'); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php } else {
                                                    if (!empty($funfact['funfact_image'])){ ?>
                                                        <div class="icon">
                                                            <?php echo Group_Control_Image_Size::get_attachment_image_html($funfact, 'full', 'funfact_image'); ?>
                                                        </div>
                                                    <?php } ?>
                                                <?php } ?>
                                                <?php if (!empty($funfact['funfact_number'])) { ?>
                                                    <h3 class="count <?php echo $funfact['funfact_number_sup'] ?>"><?php echo esc_html($funfact['funfact_number']); ?></h3>
                                                <?php } ?>
                                                <?php if (!empty($funfact['funfact_title'])) { ?>
                                                    <p><?php echo esc_html($funfact['funfact_title']); ?></p>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <!-- End Counterup Area -->
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>

                    </div>
                </div>
            </div>
            <!-- End Counterup Area -->
        <?php } else { ?>
            <!-- Start Counterup Area -->
            <div class="axil-counterup-area ax-section-gap bg-color-white">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="section-title text-center">
                                <?php $this->axil_section_title_render('funfacts','extra08-color', $this->get_settings()); ?>
                            </div>
                        </div>
                    </div>

                    <?php if ($settings['funfact_list']) { ?>
                        <div class="row funfact-wrap justify-content-center">
                            <?php foreach ($settings['funfact_list'] as $funfact) { ?>
                                <!-- Start Counterup Area -->
                                <div class="col-lg-3 col-md-6 col-sm-6 col-6  funfact-item elementor-repeater-item-<?php echo $funfact['_id']; ?>">
                                    <div class="axil-counterup mt--60 text-center counter-first move-up wow">
                                        <?php if($funfact['funfact_icon_type'] !== 'image'){ ?>
                                            <?php if (!empty($funfact['funfact_icon']) || !empty($funfact['funfact_selected_icon']['value'])) : ?>
                                                <div class="icon">
                                                    <?php axil_render_icon($funfact, 'funfact_icon', 'funfact_selected_icon'); ?>
                                                </div>
                                            <?php endif; ?>
                                        <?php } else {
                                            if (!empty($funfact['funfact_image'])){ ?>
                                                <div class="icon">
                                                    <?php echo Group_Control_Image_Size::get_attachment_image_html($funfact, 'full', 'funfact_image'); ?>
                                                </div>
                                            <?php } ?>
                                        <?php } ?>
                                        <?php if (!empty($funfact['funfact_number'])) { ?>
                                            <h3 class="count <?php echo $funfact['funfact_number_sup'] ?>"><?php echo esc_html($funfact['funfact_number']); ?></h3>
                                        <?php } ?>
                                        <?php if (!empty($funfact['funfact_title'])) { ?>
                                            <p><?php echo esc_html($funfact['funfact_title']); ?></p>
                                        <?php } ?>
                                    </div>
                                </div>
                                <!-- End Counterup Area -->
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <!-- End Counterup Area -->
        <?php }

    }

}

Plugin::instance()->widgets_manager->register_widget_type(new keystroke_Elementor_Widget_Funfact());


