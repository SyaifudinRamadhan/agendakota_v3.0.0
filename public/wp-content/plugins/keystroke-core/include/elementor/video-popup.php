<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Keystroke_Elementor_Widget_Video_Popup extends Widget_Base {

    use \Elementor\KeystrokeElementCommonFunctions;

    public function get_name() {
        return 'keystroke-video-popup';
    }

    public function get_title() {
        return __( 'Video Popup', 'keystroke' );
    }

    public function get_icon() {
        return 'axil-icon';
    }

    public function get_categories() {
        return [ 'keystroke' ];
    }

    public function get_keywords()
    {
        return ['video', 'project video', 'video popup', 'keystroke'];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'keystroke_project_video_popup',
            [
                'label' => esc_html__( 'Image and Video', 'keystroke' ),
            ]
        );

        $this->add_control(
            'keystroke_video_popup_image',
            [
                'label' => esc_html__( 'Choose Image', 'keystroke' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'keystroke_video_popup_image_size',
                'default' => 'full',
                'exclude' => [
                    'custom'
                ]
            ]
        );
        $this->add_control(
            'keystroke_video_popup_video_url',
            [
                'label' => esc_html__( 'Video Link', 'keystroke' ),
                'description' => 'Video url example: https://www.youtube.com/watch?v=Pj_geat9hvI',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'https://www.youtube.com/watch?v=Pj_geat9hvI',
                'placeholder' => esc_html__( 'Enter your youtube video url hare', 'keystroke' ),
            ]
        );

        $this->end_controls_section();

        $this->axil_section_style_controls('video_popup_area', 'Section Style', '.axil-video-area');

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();
        $this->add_render_attribute('keystroke_video_popup_image', 'class', 'image w-100 paralax-image');

        ?>
        <!-- Start Project Solutions Area  -->
        <div class="axil-video-area bg-color-lightest ax-section-gap">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="content">
                            <div class="thumbnail move-up wow position-relative">
                                <?php
                                if($settings['keystroke_video_popup_image']['id']){
                                    echo wp_get_attachment_image($settings['keystroke_video_popup_image']['id'], $settings['keystroke_video_popup_image_size_size'], '', array("class" => "w-100 paralax-image"));
                                }else{
                                    echo Group_Control_Image_Size::get_attachment_image_html($settings, 'full', 'keystroke_video_popup_image');
                                }
                                ?>
                                <?php if(!empty($settings['keystroke_video_popup_video_url'])){ ?>
                                    <div class="video-button position-to-top">
                                        <a class="play__btn video-btn" href="<?php echo esc_url($settings['keystroke_video_popup_video_url']); ?>"><span
                                                class="triangle"></span></a>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Project Solutions Area  -->
        <?php

    }

}

Plugin::instance()->widgets_manager->register_widget_type( new Keystroke_Elementor_Widget_Video_Popup() );


