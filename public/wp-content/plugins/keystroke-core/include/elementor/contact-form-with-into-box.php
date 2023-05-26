<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class keystroke_Elementor_Widget_Contact_FWSB extends Widget_Base {

    use \Elementor\KeystrokeElementCommonFunctions;

    public function get_name() {
        return 'keystroke-contact-form-with-info-box';
    }

    public function get_title() {
        return __( 'Contact FWSB', 'keystroke' );
    }

    public function get_icon() {
        return 'axil-icon';
    }

    public function get_categories() {
        return [ 'keystroke' ];
    }

    public function get_keywords()
    {
        return ['contact', 'contact from', 'keystroke'];
    }
    public function get_axil_contact_form(){
        if ( ! class_exists( 'WPCF7' ) ) {
            return;
        }
        $axil_cfa         = array();
        $axil_cf_args     = array( 'posts_per_page' => -1, 'post_type'=> 'wpcf7_contact_form' );
        $axil_forms       = get_posts( $axil_cf_args );
        $axil_cfa         = ['0' => esc_html__( 'Select Form', 'keystroke' ) ];
        if( $axil_forms ){
            foreach ( $axil_forms as $axil_form ){
                $axil_cfa[$axil_form->ID] = $axil_form->post_title;
            }
        }else{
            $axil_cfa[ esc_html__( 'No contact form found', 'keystroke' ) ] = 0;
        }
        return $axil_cfa;
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'keystroke_contact_form_with_info_box',
            [
                'label' => esc_html__( 'Contact FWSB', 'keystroke' ),
            ]
        );

        $this->add_control(
            'select_contact_form',
            [
                'label'   => esc_html__( 'Select Form', 'keystroke' ),
                'type'    => Controls_Manager::SELECT,
                'default' => '0',
                'options' => $this->get_axil_contact_form(),
            ]
        );

        $this->add_control(
            'contact_form_bg_shape',
            [
                'label'        => esc_html__('Doted Shape ?', 'keystroke'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Yes', 'keystroke'),
                'label_off'    => esc_html__('No', 'keystroke'),
                'return_value' => 'yes',
                'separator'    => 'before',
                'default'      => 'yes'
            ]
        );

        $this->end_controls_section();



        // phone_number_boxs group
        $this->start_controls_section(
            'phone_number_box',
            [
                'label' => esc_html__( 'Phone Number Box', 'keystroke' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'phone_number_box_icon_type',
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

        $this->add_control(
            'phone_number_box_image',
            [
                'label' => esc_html__('Upload Image', 'keystroke'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'phone_number_box_icon_type' => 'image'
                ]

            ]
        );

        if ( axil_is_elementor_version( '<', '2.6.0' ) ) {
            $this->add_control(
                'phone_number_box_icon',
                [
                    'show_label' => false,
                    'type' => Controls_Manager::ICON,
                    'label_block' => true,
                    'default' => 'fa fa-phone',
                    'condition' => [
                        'phone_number_box_icon_type' => 'icon'
                    ]
                ]
            );
        } else {
            $this->add_control(
                'phone_number_box_selected_icon',
                [
                    'show_label' => false,
                    'type' => Controls_Manager::ICONS,
                    'fa4compatibility' => 'icon',
                    'label_block' => true,
                    'default' => [
                        'value' => 'fas fa-phone',
                        'library' => 'fa-solid',
                    ],
                    'condition' => [
                        'phone_number_box_icon_type' => 'icon'
                    ]

                ]
            );
        }

        $this->add_control(
            'phone_number_box_title', [
                'label' => esc_html__( 'Title', 'keystroke' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Phone' , 'keystroke' ),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'phone_number_box_content',
            [
                'label' => esc_html__( 'Address', 'keystroke' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'rows' => 10,
                'default' => 'Our customer care is open from Mon-Fri, 10:00 am to 6:00 pm',
                'placeholder' => esc_html__( 'Type your address here', 'keystroke' ),
            ]
        );

        $this->add_control(
            'phone_number', [
                'label' => esc_html__( 'Phone Number', 'keystroke' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( '(123) 456 7890' , 'keystroke' ),
                'label_block' => true,
            ]
        );

        $this->end_controls_section();

        // Email  group
        $this->start_controls_section(
            'email_number_box',
            [
                'label' => esc_html__( 'Email Number Box', 'keystroke' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'email_number_box_icon_type',
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

        $this->add_control(
            'email_number_box_image',
            [
                'label' => esc_html__('Upload Image', 'keystroke'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'email_number_box_icon_type' => 'image'
                ]

            ]
        );

        if ( axil_is_elementor_version( '<', '2.6.0' ) ) {
            $this->add_control(
                'email_number_box_icon',
                [
                    'show_label' => false,
                    'type' => Controls_Manager::ICON,
                    'label_block' => true,
                    'default' => 'fa fa-envelope',
                    'condition' => [
                        'email_number_box_icon_type' => 'icon'
                    ]
                ]
            );
        } else {
            $this->add_control(
                'email_number_box_selected_icon',
                [
                    'show_label' => false,
                    'type' => Controls_Manager::ICONS,
                    'fa4compatibility' => 'icon',
                    'label_block' => true,
                    'default' => [
                        'value' => 'fal fa-envelope',
                        'library' => 'fa-solid',
                    ],
                    'condition' => [
                        'email_number_box_icon_type' => 'icon'
                    ]

                ]
            );
        }

        $this->add_control(
            'email_number_box_title', [
                'label' => esc_html__( 'Title', 'keystroke' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Email' , 'keystroke' ),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'email_number_box_content',
            [
                'label' => esc_html__( 'Address', 'keystroke' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'rows' => 10,
                'default' => 'Our support team will get back to in 48-h during standard business hours.',
                'placeholder' => esc_html__( 'Type your address here', 'keystroke' ),
            ]
        );

        $this->add_control(
            'email_number', [
                'label' => esc_html__( 'Email Number', 'keystroke' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'example@gmail.com' , 'keystroke' ),
                'label_block' => true,
            ]
        );
        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();

        ?>
        <!-- Start Contact Area  -->
        <div class="axil-contact-area axil-shape-position ax-section-gap bg-color-white">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-12 col-xl-5 col-12">
                        <div class="contact-form-wrapper">
                            <!-- Start Contact Form -->
                            <?php if( !empty($settings['select_contact_form']) ){ ?> <div class="axil-contact-form contact-form-style-1"> <?php
                                echo do_shortcode( '[contact-form-7  id="'.$settings['select_contact_form'].'"]' );
                                ?> </div> <?php
                            } else {
                                echo '<div class="alert alert-info"><p>' . __('Please Select contact form.', 'keystroke' ). '</p></div>';
                            } ?>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-12 col-xl-6 offset-xl-1 col-12 mt_md--40 mt_sm--40">
                        <div class="axil-address-wrapper">


                            <!-- Start Single Address  -->
                            <div class="axil-address wow move-up">
                                <div class="inner">
                                    <?php if($settings['phone_number_box_icon_type'] !== 'image'){ ?>
                                        <?php if ( ! empty( $settings['phone_number_box_icon'] ) || ! empty( $settings['phone_number_box_selected_icon']['value'] ) ) : ?>
                                            <div class="icon">
                                                <?php axil_render_icon( $settings, 'phone_number_box_icon', 'phone_number_box_selected_icon' ); ?>
                                            </div>
                                        <?php endif; ?>
                                    <?php } else {
                                        if (!empty($settings['phone_number_box_image'])){ ?>
                                            <div class="thumbnail">
                                                <?php echo Group_Control_Image_Size::get_attachment_image_html($settings, 'full', 'phone_number_box_image'); ?>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>
                                    <div class="content">
                                        <?php if($settings['phone_number_box_title']){ ?>
                                            <h4 class="title"><?php echo esc_html($settings['phone_number_box_title']); ?></h4>
                                        <?php } ?>
                                        <?php if($settings['phone_number_box_content']){ ?>
                                            <p><?php echo esc_html($settings['phone_number_box_content']); ?></p>
                                        <?php } ?>
                                        <?php if($settings['phone_number']){
                                            $phoneNumber = preg_replace("/[^0-9+]/", '', $settings['phone_number']);
                                            ?>
                                            <p><a class="axil-link" href="tel:<?php echo wp_kses_post($phoneNumber); ?>"><?php echo esc_html($settings['phone_number']); ?></a></p>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <!-- End Single Address  -->

                            <!-- Start Single Address  -->
                            <div class="axil-address wow move-up mt--60 mt_sm--30 mt_md--30">
                                <div class="inner">
                                    <?php if($settings['email_number_box_icon_type'] !== 'image'){ ?>
                                        <?php if ( ! empty( $settings['email_number_box_icon'] ) || ! empty( $settings['email_number_box_selected_icon']['value'] ) ) : ?>
                                            <div class="icon">
                                                <?php axil_render_icon( $settings, 'email_number_box_icon', 'email_number_box_selected_icon' ); ?>
                                            </div>
                                        <?php endif; ?>
                                    <?php } else {
                                        if (!empty($settings['email_number_box_image'])){ ?>
                                            <div class="thumbnail">
                                                <?php echo Group_Control_Image_Size::get_attachment_image_html($settings, 'full', 'email_number_box_image'); ?>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>
                                    <div class="content">
                                        <?php if($settings['email_number_box_title']){ ?>
                                            <h4 class="title"><?php echo esc_html($settings['email_number_box_title']); ?></h4>
                                        <?php } ?>
                                        <?php if($settings['email_number_box_content']){ ?>
                                            <p><?php echo esc_html($settings['email_number_box_content']); ?></p>
                                        <?php } ?>
                                        <?php if($settings['email_number']){
                                            ?>
                                            <p><a class="axil-link" href="mailto:<?php echo wp_kses_post($settings['email_number']); ?>"><?php echo esc_html($settings['email_number']); ?></a></p>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <!-- End Single Address  -->

                        </div>
                    </div>

                </div>
            </div>

            <?php
            if( $settings['contact_form_bg_shape'] == 'yes' ){ ?>
                <div class="shape-group">
                    <div class="shape shape-01">
                        <span class="icon icon-contact-01"></span>
                    </div>
                    <div class="shape shape-02">
                        <span class="icon icon-contact-02"></span>
                    </div>
                    <div class="shape shape-03">
                        <span class="icon icon-contact-03"></span>
                    </div>
                </div>
            <?php } ?>

        </div>
        <!-- End Contact Area  -->
        <?php


    }

}

Plugin::instance()->widgets_manager->register_widget_type( new keystroke_Elementor_Widget_Contact_FWSB() );


