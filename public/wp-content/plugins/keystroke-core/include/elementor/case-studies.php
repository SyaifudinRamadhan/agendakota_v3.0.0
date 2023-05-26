<?php

namespace Elementor;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class Axil_Elementor_Widget_CaseStudy extends Widget_Base
{

    use \Elementor\KeystrokeElementCommonFunctions;

    public function get_name()
    {
        return 'keystroke-casestudy';
    }

    public function get_title()
    {
        return esc_html__('Case Study', 'keystroke');
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
        return ['case study', 'project', 'keystroke'];
    }

    protected function _register_controls()
    {


        // CaseStudys group
        $this->start_controls_section(
            'casestudys',
            [
                'label' => esc_html__('Case Study', 'keystroke'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();


        $repeater->add_control(
            'pre_title',
            [
                'label' => esc_html__('Tag Line', 'keystroke'),
                'type' => Controls_Manager::TEXT,
                'default' => 'featured case study',
                'placeholder' => esc_html__('Type Pre Heading Text', 'keystroke'),
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'keystroke'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Logo, identity & web design for Uber',
                'placeholder' => esc_html__('Type Heading Text', 'keystroke'),
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'title_tag',
            [
                'label' => esc_html__('Title HTML Tag', 'keystroke'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'h1' => [
                        'title' => esc_html__('H1', 'keystroke'),
                        'icon' => 'eicon-editor-h1'
                    ],
                    'h2' => [
                        'title' => esc_html__('H2', 'keystroke'),
                        'icon' => 'eicon-editor-h2'
                    ],
                    'h3' => [
                        'title' => esc_html__('H3', 'keystroke'),
                        'icon' => 'eicon-editor-h3'
                    ],
                    'h4' => [
                        'title' => esc_html__('H4', 'keystroke'),
                        'icon' => 'eicon-editor-h4'
                    ],
                    'h5' => [
                        'title' => esc_html__('H5', 'keystroke'),
                        'icon' => 'eicon-editor-h5'
                    ],
                    'h6' => [
                        'title' => esc_html__('H6', 'keystroke'),
                        'icon' => 'eicon-editor-h6'
                    ]
                ],
                'default' => 'h2',
                'toggle' => false,
                'label_block' => true,

            ]
        );

        $repeater->add_control(
            'desctiption',
            [
                'label' => esc_html__('Description', 'keystroke'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => 'Donec metus lorem, vulputate at sapien sit amet, auctor iaculis lorem. In vel hendrerit nisi. Vestibulum eget risus velit. Aliquam tristique libero at dui sodales, et placerat orci lobortis. Maecenas ipsum neque, elementum id dignissim et, imperdiet vitae mauris.',
                'placeholder' => esc_html__('Type casestudy description here', 'keystroke'),
            ]
        );
        /**
         * Start Link
         */
        $repeater->add_control(
            'casestudy_link_text',
            [
                'label' => esc_html__('CaseStudy Link Text', 'keystroke'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Read Case Study',
                'title' => esc_html__('Enter button text', 'keystroke'),
            ]
        );

        $repeater->add_control(
            'casestudy_link_type',
            [
                'label' => esc_html__('CaseStudy Link Type', 'keystroke'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '1' => 'Custom Link',
                    '2' => 'Internal Page',
                ],
                'default' => '1',
            ]
        );
        $repeater->add_control(
            'casestudy_link',
            [
                'label' => esc_html__('CaseStudy Link link', 'keystroke'),
                'type' => \Elementor\Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => esc_html__('https://your-link.com', 'keystroke'),
                'show_external' => true,
                'default' => [
                    'url' => '#',
                    'is_external' => true,
                    'nofollow' => true,
                ],
                'condition' => [
                    'casestudy_link_type' => '1'
                ]
            ]
        );
        $repeater->add_control(
            'casestudy_page_link',
            [
                'label' => esc_html__('Select CaseStudy Link Page', 'keystroke'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'label_block' => true,
                'options' => axil_get_all_pages(),
                'condition' => [
                    'casestudy_link_type' => '2'
                ]
            ]
        );
        $repeater->add_control(
            'casestudy_button_style',
            [
                'label' => esc_html__('Button Style', 'keystroke'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'btn-transparent' => esc_html__('Outline', 'keystroke'),
                    'btn-solid' => esc_html__('Solid', 'keystroke'),
                ],
                'default' => 'btn-solid'
            ]
        );

        $repeater->add_control(
            'casestudy_button_size',
            [
                'label' => esc_html__('Button Size', 'keystroke'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'btn-extra-large' => esc_html__('Extra Large', 'keystroke'),
                    'btn-large' => esc_html__('Large', 'keystroke'),
                    'btn-medium' => esc_html__('Medium', 'keystroke'),
                    'btn-small' => esc_html__('Small', 'keystroke'),
                ],
                'default' => 'btn-large'
            ]
        );

        $repeater->add_control(
            'casestudy_button_arrow_icon',
            [
                'label' => esc_html__('Arrow Icon', 'keystroke'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'keystroke'),
                'label_off' => esc_html__('Hide', 'keystroke'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        /**
         * Start Funfact
         */
        $repeater->add_control(
            'casestudy_funfact_one_number',
            [
                'label' => esc_html__('CaseStudy Funfact One Number', 'keystroke'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '45',
                'title' => esc_html__('Enter funfact number', 'keystroke'),
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'casestudy_funfact_one_number_sup',
            [
                'label' => esc_html__('CaseStudy Funfact One Number Sup', 'keystroke'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'counter-percentage',
                'options' => [
                    'counter-none' => esc_html__('None', 'keystroke'),
                    'counter-percentage' => esc_html__('Percentage(%)', 'keystroke'),
                    'counter-k' => esc_html__('Thousand(K)', 'keystroke'),
                ],
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'casestudy_funfact_one_title',
            [
                'label' => esc_html__('CaseStudy Funfact One Title', 'keystroke'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'ROI increase',
                'title' => esc_html__('Enter funfact One Title', 'keystroke'),
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'casestudy_funfact_two_title_hr',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );
        $repeater->add_control(
            'casestudy_funfact_two_number',
            [
                'label' => esc_html__('CaseStudy Funfact Two Number', 'keystroke'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '19',
                'title' => esc_html__('Enter funfact number', 'keystroke'),
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'casestudy_funfact_two_number_sup',
            [
                'label' => esc_html__('CaseStudy Funfact Two Number Sup', 'keystroke'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'counter-k',
                'options' => [
                    'counter-none' => esc_html__('None', 'keystroke'),
                    'counter-percentage' => esc_html__('Percentage(%)', 'keystroke'),
                    'counter-k' => esc_html__('Thousand(K)', 'keystroke'),
                ],
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'casestudy_funfact_two_title',
            [
                'label' => esc_html__('CaseStudy Funfact Two Title', 'keystroke'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Monthly website visits',
                'title' => esc_html__('Enter funfact Two Title', 'keystroke'),
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'casestudy_thumbnail',
            [
                'label' => esc_html__('Case Study Thumbnail', 'keystroke'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],

            ]
        );
        $this->add_control(
            'casestudy_list',
            [
                'label' => esc_html__('CaseStudys List', 'keystroke'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'casestudy_title' => esc_html__('Logo, identity & web design for Uber #1', 'keystroke'),
                        'casestudy_content' => esc_html__('Donec metus lorem, vulputate at sapien sit amet, auctor iaculis lorem. In vel hendrerit nisi. Vestibulum eget risus velit. Aliquam tristique libero at dui sodales, et placerat orci lobortis. Maecenas ipsum neque, elementum id dignissim et, imperdiet vitae mauris.', 'keystroke'),
                        'casestudy_link_text' => esc_html__('Read Case Study', 'keystroke'),
                    ],
                    [
                        'casestudy_title' => esc_html__('Logo, identity & web design for Uber #2', 'keystroke'),
                        'casestudy_content' => esc_html__('Donec metus lorem, vulputate at sapien sit amet, auctor iaculis lorem. In vel hendrerit nisi. Vestibulum eget risus velit. Aliquam tristique libero at dui sodales, et placerat orci lobortis. Maecenas ipsum neque, elementum id dignissim et, imperdiet vitae mauris.', 'keystroke'),
                        'casestudy_link_text' => esc_html__('Read Case Study', 'keystroke'),
                    ],
                    [
                        'casestudy_title' => esc_html__('Logo, identity & web design for Uber #3', 'keystroke'),
                        'casestudy_content' => esc_html__('Donec metus lorem, vulputate at sapien sit amet, auctor iaculis lorem. In vel hendrerit nisi. Vestibulum eget risus velit. Aliquam tristique libero at dui sodales, et placerat orci lobortis. Maecenas ipsum neque, elementum id dignissim et, imperdiet vitae mauris.', 'keystroke'),
                        'casestudy_link_text' => esc_html__('Read Case Study', 'keystroke'),
                    ]
                ],
                'title_field' => '{{{ casestudy_title }}}',
            ]
        );
        $this->add_control(
            'style',
            [
                'label' => esc_html__( 'Select Layout', 'keystroke' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'carousel',
                'options' => [
                    'carousel' => esc_html('Carousel', 'keystroke'),
                    'normal' => esc_html('Normal', 'keystroke'),
                ]
            ]
        );

        $this->add_control(
            'casestudy_thumbnail_bg_shape',
            [
                'label'        => esc_html__('Doted Shape ?', 'keystroke'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Yes', 'keystroke'),
                'label_off'    => esc_html__('No', 'keystroke'),
                'return_value' => 'yes',
                'separator'    => 'before',
                'default'      => 'no'
            ]
        );

        $this->end_controls_section();

        $this->axil_basic_style_controls('casestudy_title_pre_title', 'Tag Line', '.section-title span.sub-title');
        $this->axil_basic_style_controls('casestudy_title_title', 'Title', '.section-title .title');
        $this->axil_basic_style_controls('casestudy_title_description', 'Description', '.section-title p');
        $this->axil_basic_style_controls('casestudy_title_funfact_number', 'FunFact Number', '.single-counterup.counterup-style-1 h3.count');
        $this->axil_basic_style_controls('casestudy_title_funfact_title', 'FunFact Title', '.single-counterup.counterup-style-1 p');
        $this->axil_section_style_controls('casestudy_area', 'CaseStudy Area', '.axil-featured-area');
    }

    protected function render($instance = [])
    {

        $settings = $this->get_settings_for_display();
        $this->add_render_attribute('title_args', 'class', 'title wow');
        $target = "";
        $rel = "";
        ?>

        <?php if ($settings['casestudy_list']) { ?>
            <?php if( $settings['style'] == 'normal'){ ?>
                <!-- Start Featured Area -->
                <div class="axil-featured-area ax-section-gap bg-color-lightest case-study-style-two">
                    <div class="container">
                        <?php foreach ($settings['casestudy_list'] as $index => $casestudy) { ?>

                        <!-- Start Single Feature  -->
                        <?php
                        $even = ($index % 2 !== 0) ? 'axil-featured-left ax-section-gapTop' : '';
                        $section_gap = ($index !== 0) ? 'ax-section-gapTop' : '';
                        $text_align = ($index % 2 !== 0) ? 'text-left' : '';

                        // Link
                        if ('2' == $casestudy['casestudy_link_type']) {
                            $link = get_permalink($casestudy['casestudy_page_link']);
                            $target = 'target="_self"';
                            $rel = 'rel="nofollow"';
                        } else {
                            if (!empty($casestudy['casestudy_link']['url'])) {
                                $link = $casestudy['casestudy_link']['url'];
                            }
                            if ($casestudy['casestudy_link']['is_external']) {
                                $target = 'target="_blank"';
                            }
                            if (!empty($casestudy['casestudy_link']['nofollow'])) {
                                $rel = 'rel="nofollow"';
                            }
                        }
                        $arrow = ($casestudy['casestudy_button_arrow_icon'] == 'yes') ? '<span class="button-icon"></span>' : '';
                        ?>

                        <?php if($index % 2 !== 0){ ?>
                            <!-- Start Single Feature  -->
                            <div class="row d-flex flex-wrap axil-featured axil-featured-left row--0 align-items-center  <?php echo esc_attr($section_gap); ?>">
                                <div class="col-lg-6 col-xl-5 col-md-12 col-12 mt_md--40 mt_sm--40 order-2 order-lg-1">
                                    <div class="inner">
                                        <div class="section-title text-left">
                                            <?php if (!empty($casestudy['pre_title'])) { ?>
                                                <span class="sub-title extra04-color wow" data-splitting><?php echo axil_kses_intermediate($casestudy['pre_title']); ?></span>
                                            <?php } ?>
                                            <<?php echo tag_escape($casestudy['title_tag']); ?> class="title">

                                            <?php if (!empty($link)){ ?>
                                            <a href="<?php echo esc_url($link); ?>"
                                               <?php echo esc_attr($target); ?>
                                                <?php echo esc_attr($rel); ?> >
                                                <?php } ?>

                                                <?php echo esc_html($casestudy['title']); ?>

                                            <?php if (!empty($link)){ ?>
                                            </a>
                                            <?php } ?>

                                        </<?php echo tag_escape($casestudy['title_tag']); ?>>

                                        <?php if (!empty($casestudy['desctiption'])) { ?>
                                            <p class="subtitle-2"><?php echo axil_kses_intermediate($casestudy['desctiption']); ?></p>
                                        <?php } ?>


                                        <?php if (!empty($casestudy['casestudy_link_text'])): ?>
                                            <a class="axil-button <?php echo esc_attr($casestudy['casestudy_button_size']) ?> <?php echo esc_attr($casestudy['casestudy_button_style']) ?> "
                                               href="<?php echo esc_url($link); ?>"
                                               <?php echo esc_attr($target); ?>
                                           <?php echo esc_attr($rel); ?> >
                                                <span class="button-text"> <?php echo esc_html($casestudy['casestudy_link_text']); ?></span><?php echo wp_kses_post($arrow); ?>
                                            </a>
                                        <?php endif ?>
                                        </div>
                                        <div class="axil-counterup-area d-flex flex-wrap separator-line-vertical">
                                            <?php if (!empty($casestudy['casestudy_funfact_one_number']) || !empty($casestudy['casestudy_funfact_one_title'])) { ?>
                                                <!-- Start Counterup -->
                                                <div class="single-counterup counterup-style-1">
                                                    <?php if (!empty($casestudy['casestudy_funfact_one_number'])) { ?>
                                                        <h3 class="count <?php echo esc_attr($casestudy['casestudy_funfact_one_number_sup']) ?>"><?php echo esc_html($casestudy['casestudy_funfact_one_number']); ?></h3>
                                                    <?php } ?>
                                                    <?php if (!empty($casestudy['casestudy_funfact_one_title'])) { ?>
                                                        <p><?php echo esc_html($casestudy['casestudy_funfact_one_title']); ?></p>
                                                    <?php } ?>
                                                </div>
                                                <!-- End Counterup -->
                                            <?php } ?>
                                            <?php if (!empty($casestudy['casestudy_funfact_two_number']) || !empty($casestudy['casestudy_funfact_two_title'])) { ?>
                                                <!-- Start Counterup -->
                                                <div class="single-counterup counterup-style-1">
                                                    <?php if (!empty($casestudy['casestudy_funfact_two_number'])) { ?>
                                                        <h3 class="count <?php echo esc_attr($casestudy['casestudy_funfact_two_number_sup']) ?> "><?php echo esc_html($casestudy['casestudy_funfact_two_number']); ?></h3>
                                                    <?php } ?>
                                                    <?php if (!empty($casestudy['casestudy_funfact_two_title'])) { ?>
                                                        <p><?php echo esc_html($casestudy['casestudy_funfact_two_title']); ?></p>
                                                    <?php } ?>
                                                </div>
                                                <!-- End Counterup -->
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <?php if (!empty($casestudy['casestudy_thumbnail'])) { ?>
                                    <div class="col-lg-6 col-xl-6 offset-xl-1 col-md-12 col-12 order-1 order-lg-2">
                                        <div class="thumbnail">
                                            <?php if (!empty($link)){ ?>
                                            <a href="<?php echo esc_url($link); ?>"
                                               <?php echo esc_attr($target); ?>
                                           <?php echo esc_attr($rel); ?> >
                                                <?php } ?>
                                                <?php echo wp_get_attachment_image( $casestudy['casestudy_thumbnail']['id'], 'axil-casestudy-thumb', '', array( "class" => "image w-100 paralax-image" ) ); ?>

                                                <?php if($settings['casestudy_thumbnail_bg_shape'] == 'yes'){ ?>
                                                    <div class="shape-group">
                                                        <div class="shape">
                                                            <span class="icon icon-breadcrumb-2"></span>
                                                        </div>
                                                    </div>
                                                <?php } ?>

                                                <?php if (!empty($link)){ ?>
                                            </a>
                                        <?php } ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <!-- End Single Feature  -->
                        <?php } else { ?>
                            <!-- Start Single Feature  -->
                            <div class="row d-flex flex-wrap axil-featured row--0  align-items-center <?php echo esc_attr($section_gap); ?>">
                                <?php if (!empty($casestudy['casestudy_thumbnail'])) { ?>
                                    <div class="col-lg-6 col-xl-6 col-md-12 col-12">
                                        <div class="thumbnail">
                                            <?php if (!empty($link)){ ?>
                                            <a href="<?php echo esc_url($link); ?>"
                                               <?php echo esc_attr($target); ?>
                                           <?php echo esc_attr($rel); ?> >
                                                <?php } ?>
                                                <?php echo wp_get_attachment_image( $casestudy['casestudy_thumbnail']['id'], 'axil-casestudy-thumb', '', array( "class" => "image w-100 paralax-image" ) ); ?>

                                                <?php if($settings['casestudy_thumbnail_bg_shape'] == 'yes'){ ?>
                                                    <div class="shape-group">
                                                        <div class="shape">
                                                            <?php echo get_template_part( 'assets/images/others/breadcrumb-2'); ?>
                                                        </div>
                                                    </div>
                                                <?php } ?>

                                                <?php if (!empty($link)){ ?>
                                            </a>
                                        <?php } ?>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="col-lg-6 col-xl-5 offset-xl-1 col-md-12 col-12 mt_md--40 mt_sm--40">
                                    <div class="inner">
                                        <div class="section-title text-left">
                                            <?php if (!empty($casestudy['pre_title'])) { ?>
                                                <span class="sub-title extra04-color wow"
                                                      data-splitting><?php echo axil_kses_intermediate($casestudy['pre_title']); ?></span>
                                            <?php } ?>
                                            <<?php echo tag_escape($casestudy['title_tag']); ?> class="title wow">
                                            <?php if (!empty($link)){ ?>
                                            <a href="<?php echo esc_url($link); ?>"
                                               <?php echo esc_attr($target); ?>
                                           <?php echo esc_attr($rel); ?> >
                                                <?php } ?>
                                                <?php echo esc_html($casestudy['title']); ?>
                                                <?php if (!empty($link)){ ?>
                                            </a>
                                            <?php } ?>
                                            </<?php echo tag_escape($casestudy['title_tag']); ?>>
                                            <?php if (!empty($casestudy['desctiption'])) { ?>
                                                <p class="subtitle-2 wow"
                                                   data-splitting><?php echo axil_kses_intermediate($casestudy['desctiption']); ?></p>
                                            <?php } ?>
                                            <?php if (!empty($casestudy['casestudy_link_text'])): ?>
                                                <a class="axil-button <?php echo esc_attr($casestudy['casestudy_button_size']) ?> <?php echo esc_attr($casestudy['casestudy_button_style']) ?> "
                                                   href="<?php echo esc_url($link); ?>"
                                                   <?php echo esc_attr($target); ?>
                                               <?php echo esc_attr($rel); ?> >
                                                    <span class="button-text"> <?php echo esc_html($casestudy['casestudy_link_text']); ?></span><?php echo wp_kses_post($arrow); ?>
                                                </a>
                                            <?php endif ?>
                                        </div>
                                        <div class="axil-counterup-area d-flex flex-wrap separator-line-vertical">
                                            <?php if (!empty($casestudy['casestudy_funfact_one_number']) || !empty($casestudy['casestudy_funfact_one_title'])) { ?>
                                                <!-- Start Counterup -->
                                                <div class="single-counterup counterup-style-1">
                                                    <?php if (!empty($casestudy['casestudy_funfact_one_number'])) { ?>
                                                        <h3 class="count <?php echo esc_attr($casestudy['casestudy_funfact_one_number_sup']) ?>"><?php echo esc_html($casestudy['casestudy_funfact_one_number']); ?></h3>
                                                    <?php } ?>
                                                    <?php if (!empty($casestudy['casestudy_funfact_one_title'])) { ?>
                                                        <p><?php echo esc_html($casestudy['casestudy_funfact_one_title']); ?></p>
                                                    <?php } ?>
                                                </div>
                                                <!-- End Counterup -->
                                            <?php } ?>
                                            <?php if (!empty($casestudy['casestudy_funfact_two_number']) || !empty($casestudy['casestudy_funfact_two_title'])) { ?>
                                                <!-- Start Counterup -->
                                                <div class="single-counterup counterup-style-1">
                                                    <?php if (!empty($casestudy['casestudy_funfact_two_number'])) { ?>
                                                        <h3 class="count <?php echo esc_attr($casestudy['casestudy_funfact_two_number_sup']) ?> "><?php echo esc_html($casestudy['casestudy_funfact_two_number']); ?></h3>
                                                    <?php } ?>
                                                    <?php if (!empty($casestudy['casestudy_funfact_two_title'])) { ?>
                                                        <p><?php echo esc_html($casestudy['casestudy_funfact_two_title']); ?></p>
                                                    <?php } ?>
                                                </div>
                                                <!-- End Counterup -->
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Single Feature  -->
                        <?php } ?> <!-- End odd/even  -->
                    <?php } ?> <!-- End foreach  -->
                    </div>
                </div>
                <!-- End Featured Area one -->
            <?php } else { ?>
                <!-- Start Featured Area -->
                <div class="axil-featured-area ax-section-gap bg-color-lightest">

                    <?php if($settings['casestudy_thumbnail_bg_shape'] == 'yes'){ ?>
                        <div class="shape-group">
                            <div class="shape">
                                <span class="icon icon-breadcrumb-2"></span>
                            </div>
                        </div>
                    <?php } ?>

                    <div class="container axil-featured-activation axil-carousel" data-slick-options='{
                                "spaceBetween": 0,
                                "slidesToShow": 1,
                                "slidesToScroll": 1,
                                "arrows": false,
                                "infinite": true,
                                "centerMode":true,
                                "dots": true
                            }' data-slick-responsive='[
                                {"breakpoint":769, "settings": {"slidesToShow": 1}},
                                {"breakpoint":756, "settings": {"slidesToShow": 1}},
                                {"breakpoint":481, "settings": {"slidesToShow": 1}}
                            ]'>

                        <?php foreach ($settings['casestudy_list'] as $casestudy) { ?>
                        <!-- Start Single Feature  -->

                        <?php
                        // Link
                        if ('2' == $casestudy['casestudy_link_type']) {
                            $link = get_permalink($casestudy['casestudy_page_link']);
                            $target = 'target="_self"';
                            $rel = 'rel="nofollow"';
                        } else {
                            if (!empty($casestudy['casestudy_link']['url'])) {
                                $link = $casestudy['casestudy_link']['url'];
                            }
                            if ($casestudy['casestudy_link']['is_external']) {
                                $target = 'target=_blank';
                            }
                            if (!empty($casestudy['casestudy_link']['nofollow'])) {
                                $rel = 'rel=nofollow';
                            }
                        }
                        $arrow = ($casestudy['casestudy_button_arrow_icon'] == 'yes') ? '<span class="button-icon"></span>' : '';
                        ?>

                        <div class="row d-flex flex-wrap axil-featured row--0">

                            <?php if (!empty($casestudy['casestudy_thumbnail'])) { ?>
                                <div class="col-lg-6 col-xl-6 col-md-12 col-12">
                                    <div class="thumbnail">
                                        <?php echo Group_Control_Image_Size::get_attachment_image_html($casestudy, 'axil-casestudy-thumb', 'casestudy_thumbnail'); ?>



                                    </div>
                                </div>
                            <?php } ?>
                            <div class="col-lg-6 col-xl-5 col-md-12 col-12 offset-xl-1 mt_md--40 mt_sm--40">
                                <div class="inner">
                                    <div class="section-title text-left">

                                        <?php if (!empty($casestudy['pre_title'])) { ?>
                                            <span class="sub-title extra04-color wow"
                                                  data-splitting><?php echo axil_kses_intermediate($casestudy['pre_title']); ?></span>
                                        <?php } ?>
                                        <<?php echo tag_escape($casestudy['title_tag']); ?> class="title wow">

                                        <?php if (!empty($link)){ ?>
                                        <a href="<?php echo esc_url($link); ?>"
                                           <?php echo esc_attr($target); ?>
                                           <?php echo esc_attr($rel); ?> >
                                            <?php } ?>


                                            <?php echo esc_html($casestudy['title']); ?>


                                            <?php if (!empty($link)){ ?>
                                        </a>
                                    <?php } ?>

                                    </<?php echo tag_escape($casestudy['title_tag']); ?>>

                                    <?php if (!empty($casestudy['desctiption'])) { ?>
                                        <p class="subtitle-2 wow"
                                           data-splitting><?php echo axil_kses_intermediate($casestudy['desctiption']); ?></p>
                                    <?php } ?>


                                    <?php if (!empty($casestudy['casestudy_link_text'])): ?>
                                        <a class="axil-button <?php echo esc_attr($casestudy['casestudy_button_size']) ?> <?php echo esc_attr($casestudy['casestudy_button_style']) ?> "
                                           href="<?php echo esc_url($link); ?>"
                                            <?php echo esc_attr($target); ?>
                                            <?php echo esc_attr($rel); ?> >
                                            <span class="button-text"> <?php echo esc_html($casestudy['casestudy_link_text']); ?></span><?php echo wp_kses_post($arrow); ?>
                                        </a>
                                    <?php endif ?>


                                </div>
                                <div class="axil-counterup-area d-flex flex-wrap separator-line-vertical">

                                    <?php if (!empty($casestudy['casestudy_funfact_one_number']) || !empty($casestudy['casestudy_funfact_one_title'])) { ?>
                                        <!-- Start Counterup -->
                                        <div class="single-counterup counterup-style-1">
                                            <?php if (!empty($casestudy['casestudy_funfact_one_number'])) { ?>
                                                <h3 class="count <?php echo esc_attr($casestudy['casestudy_funfact_one_number_sup']) ?>"><?php echo esc_html($casestudy['casestudy_funfact_one_number']); ?></h3>
                                            <?php } ?>
                                            <?php if (!empty($casestudy['casestudy_funfact_one_title'])) { ?>
                                                <p><?php echo esc_html($casestudy['casestudy_funfact_one_title']); ?></p>
                                            <?php } ?>
                                        </div>
                                        <!-- End Counterup -->
                                    <?php } ?>
                                    <?php if (!empty($casestudy['casestudy_funfact_two_number']) || !empty($casestudy['casestudy_funfact_two_title'])) { ?>
                                        <!-- Start Counterup -->
                                        <div class="single-counterup counterup-style-1">
                                            <?php if (!empty($casestudy['casestudy_funfact_two_number'])) { ?>
                                                <h3 class="count <?php echo esc_attr($casestudy['casestudy_funfact_two_number_sup']) ?> "><?php echo esc_html($casestudy['casestudy_funfact_two_number']); ?></h3>
                                            <?php } ?>
                                            <?php if (!empty($casestudy['casestudy_funfact_two_title'])) { ?>
                                                <p><?php echo esc_html($casestudy['casestudy_funfact_two_title']); ?></p>
                                            <?php } ?>
                                        </div>
                                        <!-- End Counterup -->
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Single Feature  -->
                    <?php } ?>

                </div>
                </div>
                <!-- End Featured Area two-->
            <?php } ?> <!-- layout -->
        <?php } ?> <!-- list -->
        <?php

    }

}

Plugin::instance()->widgets_manager->register_widget_type(new Axil_Elementor_Widget_CaseStudy());


