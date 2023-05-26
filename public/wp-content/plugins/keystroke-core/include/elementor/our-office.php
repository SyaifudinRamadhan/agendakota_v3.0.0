<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class keystroke_Elementor_Widget_Offices_Location extends Widget_Base {

    use \Elementor\KeystrokeElementCommonFunctions;

    public function get_name() {
        return 'keystroke-offices-location';
    }

    public function get_title() {
        return __( 'Offices Location', 'keystroke' );
    }

    public function get_icon() {
        return 'axil-icon';
    }

    public function get_categories() {
        return [ 'keystroke' ];
    }

    public function get_keywords()
    {
        return ['office', 'location', 'office location', 'address', 'keystroke'];
    }

    protected function _register_controls() {

        $this->axil_section_title('office_location', 'who we are', 'Our office', 'h2', '', 'true', 'text-center');



        // office_locations group
        $this->start_controls_section(
            'office_location',
            [
                'label' => esc_html__( 'Office locations', 'keystroke' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'office_location_icon_type',
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
            'office_location_image',
            [
                'label' => esc_html__('Upload Image', 'keystroke'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'office_location_icon_type' => 'image'
                ]

            ]
        );


        if ( axil_is_elementor_version( '<', '2.6.0' ) ) {
            $repeater->add_control(
                'office_location_icon',
                [
                    'show_label' => false,
                    'type' => Controls_Manager::ICON,
                    'label_block' => true,
                    'default' => 'fa fa-pen',
                    'condition' => [
                        'office_location_icon_type' => 'icon'
                    ]
                ]
            );
        } else {
            $repeater->add_control(
                'office_location_selected_icon',
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
                        'office_location_icon_type' => 'icon'
                    ]

                ]
            );
        }

        $repeater->add_control(
            'office_location_title', [
                'label' => esc_html__( 'Title', 'keystroke' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Location name' , 'keystroke' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'office_location_content',
            [
                'label' => esc_html__( 'Address', 'keystroke' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'rows' => 10,
                'default' => '435 Pouros Locks United States',
                'placeholder' => esc_html__( 'Type your address here', 'keystroke' ),
            ]
        );
        $repeater->add_control(
            'office_location_link_text',
            [
                'label' => esc_html__( 'Office location Link Text', 'keystroke' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Read More',
                'title' => esc_html__( 'Enter button text', 'keystroke' ),
            ]
        );

        $repeater->add_control(
            'office_location_link_type',
            [
                'label' => esc_html__( 'Office location Link Type', 'keystroke' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '1' => 'Custom Link',
                    '2' => 'Internal Page',
                ],
                'default' => '1',
            ]
        );
        $repeater->add_control(
            'office_location_link',
            [
                'label' => esc_html__( 'Office location Link link', 'keystroke' ),
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
                    'office_location_link_type' => '1'
                ]
            ]
        );
        $repeater->add_control(
            'office_location_page_link',
            [
                'label' => esc_html__( 'Select office location Link Page', 'keystroke' ),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'label_block' => true,
                'options' => axil_get_all_pages(),
                'condition' => [
                    'office_location_link_type' => '2'
                ]
            ]
        );
        $this->add_control(
            'office_location_list',
            [
                'label' => esc_html__( 'Office Locations List', 'keystroke' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' =>  $repeater->get_controls(),
                'default' => [

                    [
                        'office_location_title' => esc_html__( 'Virginia-HQ', 'keystroke' ),
                        'office_location_content' => esc_html__( '435 Pouros Locks United States', 'keystroke' ),
                        'office_location_link_text' => esc_html__( 'View on Map', 'keystroke' ),
                    ],
                    [
                        'office_location_title' => esc_html__( 'Nevada', 'keystroke' ),
                        'office_location_content' => esc_html__( '46 Watsica Creek Suite 071 United States', 'keystroke' ),
                        'office_location_link_text' => esc_html__( 'View on Map', 'keystroke' ),
                    ],
                    [
                        'office_location_title' => esc_html__( 'Columbia', 'keystroke' ),
                        'office_location_content' => esc_html__( '7140 Wehner Tunnel Washington, D.C', 'keystroke' ),
                        'office_location_link_text' => esc_html__( 'View on Map', 'keystroke' ),
                    ],
                    [
                        'office_location_title' => esc_html__( 'New Mexico', 'keystroke' ),
                        'office_location_content' => esc_html__( '10 Maggie Valleys, United States', 'keystroke' ),
                        'office_location_link_text' => esc_html__( 'View on Map', 'keystroke' ),
                    ],

                ],
                'title_field' => '{{{ office_location_title }}}',
            ]
        );

        $this->end_controls_section();

        // Columns Panel
        $this->axil_columns('office_location_columns', 'Columns', '3', '6', '6', '12');

        $this->axil_basic_style_controls('office_location__pre_title', 'Tag Line', '.section-title span.sub-title');
        $this->axil_basic_style_controls('office_location__title', 'Title', '.section-title .title');
        $this->axil_basic_style_controls('office_location__description', 'Description', '.section-title p');

        $this->axil_basic_style_controls('office_location_title', 'Location Box Title', '.axil-office-location .content h4.title');
        $this->axil_basic_style_controls('office_location_content', 'Location Box Content', '.axil-office-location .content p');

        $this->axil_section_style_controls('office_location__area', 'Location Area', '.axil-office-location-area');

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();


        ?>
        <!-- Start Office Location  -->
        <div class="axil-office-location-area ax-section-gap bg-color-lightest">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-title <?php echo esc_attr($settings['axil_office_location_align']); ?>">
                            <?php $this->axil_section_title_render('office_location','extra04-color', $this->get_settings()); ?>
                        </div>
                    </div>
                </div>
                <div class="row mt--30">

                    <?php foreach ($settings['office_location_list'] as $index => $location){ ?>
                        <!-- Start Single Location  -->
                        <div class="col-lg-<?php echo esc_attr($settings['axil_office_location_columns_for_desktop']); ?> col-md-<?php echo esc_attr($settings['axil_office_location_columns_for_laptop']); ?> col-sm-<?php echo esc_attr($settings['axil_office_location_columns_for_tablet']); ?> col-<?php echo esc_attr($settings['axil_office_location_columns_for_mobile']); ?>">
                            <div class="axil-office-location mt--30 wow move-up">
                                <?php if($location['office_location_icon_type'] !== 'image'){ ?>
                                    <?php if ( ! empty( $location['office_location_icon'] ) || ! empty( $location['office_location_selected_icon']['value'] ) ) : ?>
                                        <div class="thumbnail">
                                            <?php axil_render_icon( $location, 'office_location_icon', 'office_location_selected_icon' ); ?>
                                        </div>
                                    <?php endif; ?>
                                <?php } else {
                                    if (!empty($location['office_location_image'])){ ?>
                                        <div class="thumbnail">
                                            <?php echo Group_Control_Image_Size::get_attachment_image_html($location, 'full', 'office_location_image'); ?>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                                <div class="content">
                                    <h4 class="title"><?php echo axil_kses_basic($location['office_location_title']); ?></h4>
                                    <p><?php echo axil_kses_intermediate($location['office_location_content']); ?></p>
                                    <?php
                                    // Link
                                    if ('2' == $location['office_location_link_type']) {
                                        $link = get_permalink($location['review_page_link']);
                                        $target = '_self';
                                        $rel = 'nofollow';
                                    } else {
                                        if (!empty($location['office_location_link']['url'])) {
                                            $link = $location['office_location_link']['url'];
                                        }
                                        if ($location['office_location_link']['is_external']) {
                                            $target = '_blank';
                                        }
                                        if (!empty($location['office_location_link']['nofollow'])) {
                                            $rel = 'nofollow';
                                        }
                                    }
                                    ?>
                                    <?php if (!empty($location['office_location_link_text'])): ?>
                                        <a href="<?php echo esc_url($link); ?>"
                                           target="<?php echo esc_attr($target); ?>"
                                           rel="<?php echo esc_attr($rel); ?>"
                                           class="axil-button btn-transparent" data-hover="<?php echo esc_html($location['office_location_link_text']); ?>"><span class="button-text"><?php echo esc_html($location['office_location_link_text']); ?></span><span class="button-icon"></span></a>
                                    <?php endif ?>
                                </div>
                            </div>
                        </div>
                        <!-- End Single Location  -->
                    <?php } ?>

                </div>
            </div>
        </div>
        <!-- End Office Location  -->
        <?php

    }

}

Plugin::instance()->widgets_manager->register_widget_type( new keystroke_Elementor_Widget_Offices_Location() );


