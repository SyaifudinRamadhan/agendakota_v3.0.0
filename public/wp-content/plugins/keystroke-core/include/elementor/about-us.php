<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Axil_Elementor_Widget_AboutUS extends Widget_Base {

    use \Elementor\KeystrokeElementCommonFunctions;

    public function get_name() {
        return 'keystroke-about-us';
    }
    
    public function get_title() {
        return esc_html__( 'About Us', 'keystroke' );
    }

    public function get_icon() {
        return 'axil-icon';
    }

    public function get_categories() {
        return [ 'keystroke' ];
    }

    public function get_keywords()
    {
        return ['about', 'content', 'keystroke'];
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
            '_about_content',
            [
                'label' => esc_html__( 'About Content', 'keystroke' ),
            ]
        );
        $this->add_control(
            'pre_title',
            [
                'label' => esc_html__( 'Tag Line', 'keystroke' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'about us',
                'placeholder' => esc_html__( 'Type Pre Heading Text', 'keystroke' ),
                'label_block' => true,
            ]
        );
        $this->add_control(
            'title',
            [
                'label' => esc_html__( 'Title', 'keystroke' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'We do design, code & develop.',
                'placeholder' => esc_html__( 'Type Heading Text', 'keystroke' ),
                'label_block' => true,
            ]
        );
        $this->add_control(
            'title_tag',
            [
                'label' => esc_html__( 'Title HTML Tag', 'keystroke' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'h1'  => [
                        'title' => esc_html__( 'H1', 'keystroke' ),
                        'icon' => 'eicon-editor-h1'
                    ],
                    'h2'  => [
                        'title' => esc_html__( 'H2', 'keystroke' ),
                        'icon' => 'eicon-editor-h2'
                    ],
                    'h3'  => [
                        'title' => esc_html__( 'H3', 'keystroke' ),
                        'icon' => 'eicon-editor-h3'
                    ],
                    'h4'  => [
                        'title' => esc_html__( 'H4', 'keystroke' ),
                        'icon' => 'eicon-editor-h4'
                    ],
                    'h5'  => [
                        'title' => esc_html__( 'H5', 'keystroke' ),
                        'icon' => 'eicon-editor-h5'
                    ],
                    'h6'  => [
                        'title' => esc_html__( 'H6', 'keystroke' ),
                        'icon' => 'eicon-editor-h6'
                    ]
                ],
                'default' => 'h2',
                'toggle' => false,
            ]
        );

        $this->add_control(
            'desctiption',
            [
                'label' => esc_html__( 'Description', 'keystroke' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => 'Nulla et velit gravida, facilisis quam a, molestie ante. Mauris placerat suscipit dui, eget maximus tellus blandit a. Praesent non tellus sed ligula commodo blandit in et mauris. Quisque efficitur ipsum ut dolor molestie pellentesque. Nulla pharetra hendrerit mi quis dapibus. Quisque luctus, tortor a venenatis fermentum, est lacus feugiat nisl, id pharetra odio enim eget libero.',
                'placeholder' => esc_html__( 'Type section description here', 'keystroke' ),
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
        $this->end_controls_section();

        $this->axil_basic_style_controls('section_title_pre_title', 'Tag Line', '.section-title span.sub-title');
        $this->axil_basic_style_controls('section_title_title', 'Title', '.section-title .title');
        $this->axil_basic_style_controls('section_title_description', 'Description', '.section-title p');




        
    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();
        $this->add_render_attribute('title_args', 'class', 'title move-up wow');

        ?>
        <!-- Start About Us Area -->
        <div class="axil-about-us-area ax-section-gap bg-color-white axil-shape-position">
            <div class="container">
                <div class="row">


                    <div class="col-lg-6 col-xl-6 col-md-12 col-12">
                        <div class="axil-about-inner">
                            <div class="section-title text-left">
                                <?php if (!empty($settings['pre_title'])) { ?>
                                    <span class="sub-title extra08-color move-up wow"><?php echo axil_kses_intermediate( $settings['pre_title'] ); ?></span>
                                <?php } ?>
                                <?php if ($settings['title_tag']) : ?>
                                <<?php echo tag_escape($settings['title_tag']); ?> <?php echo $this->get_render_attribute_string('title_args'); ?>><?php echo esc_html($settings['title']); ?></<?php echo tag_escape($settings['title_tag']) ?>>
                                <?php endif; ?>
                                <?php if (!empty($settings['desctiption'])) { ?>
                                    <p class="subtitle-2 mb--50 mb_lg--20 mb_md--20 mb_sm--15 move-up wow"><?php echo axil_kses_intermediate( $settings['desctiption'] ); ?></p>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-xl-5 offset-xl-1 col-md-12 col-12 mt_md--30 mt_sm--30">
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

                </div>
            </div>
            <!-- Start Shape Group  -->
            <div class="axil-shape-group">
                <div class="shape shape-1">
                    <span class="icon icon-contact-03"></span>
                </div>
                <div class="shape shape-2">
                    <span class="icon icon-shape-03"></span>
                </div>
            </div>
            <!-- End Shape Group  -->
        </div>
        <!-- End About Us Area -->
        <?php
    }
}

Plugin::instance()->widgets_manager->register_widget_type( new Axil_Elementor_Widget_AboutUS() );