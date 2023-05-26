<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class keystroke_Elementor_Widget_ProjectBrief extends Widget_Base {

    use \Elementor\KeystrokeElementCommonFunctions;

    public function get_name() {
        return 'keystroke-project-brief';
    }

    public function get_title() {
        return __( 'Project Brief', 'keystroke' );
    }

    public function get_icon() {
        return 'axil-icon';
    }

    public function get_categories() {
        return [ 'keystroke' ];
    }

    public function get_keywords()
    {
        return ['project brief', 'Video', 'case study single', 'keystroke'];
    }

    protected function _register_controls() {


        $this->axil_section_title('project_brief', '', 'Project brief', 'h2', 'Nulla et velit gravida, facilisis quam a, molestie ante. Mauris placerat suscipit dui, eget maximus tellus blandit a. Praesent non tellus sed ligula commodo blandit in et mauris. Quisque efficitur ipsum ut dolor molestie pellentesque. <br>

Nulla pharetra hendrerit mi quis dapibus. Quisque luctus, tortor a venenatis fermentum, est lacus feugiat nisl, id pharetra odio enim eget libero.', 'true', 'text-left');



        $this->start_controls_section(
            'keystroke_project_brief_image_and_video',
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

        $this->add_control(
            'keystroke_pb_thumb_bg_shape',
            [
                'label' => esc_html__('Thumbnail Background Shape?', 'keystroke'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'keystroke'),
                'label_off' => esc_html__('Hide', 'keystroke'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        $this->add_responsive_control(
            'keystroke_project_thumb_position',
            [
                'label' => esc_html__( 'Thumbnail Position', 'keystroke' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'keystroke' ),
                        'icon' => 'fa fa-arrow-left',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'keystroke' ),
                        'icon' => 'fa fa-arrow-right',
                    ],
                    'bottom' => [
                        'title' => esc_html__( 'Bottom', 'keystroke' ),
                        'icon' => 'fa fa-arrow-down',
                    ],
                ],
                'default' => 'left',
                'toggle' => false,
            ]
        );

        $this->end_controls_section();

        $this->axil_basic_style_controls('project_brief_pre_title', 'Tag Line', '.axil-project-b span.sub-title');
        $this->axil_basic_style_controls('project_brief_title', 'Title', '.axil-project-b .title');
        $this->axil_basic_style_controls('project_brief_description', 'Description', '.axil-project-b p');

        $this->axil_section_style_controls('project_brief_area', 'Section Style', '.axil-project-b');

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();
        $this->add_render_attribute('keystroke_video_popup_image', 'class', 'image w-100 paralax-image');

        ?>
        <!-- Start Project Brief  -->
        <?php if($settings['keystroke_project_thumb_position'] == 'right'){ ?>
            <!-- Start Project Brief  -->
            <div class="axil-project-b axil-project-brief project-bief-styles order-style-2 ax-section-gap bg-color-lightest">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-6 col-xl-5 col-md-12 col-12 order-2 order-lg-1 mt_md--30 mt_sm--30">
                            <div class="content">
                                <div class="inner move-up wow <?php echo esc_attr($settings['axil_project_brief_align']); ?>">
                                    <?php $this->axil_section_title_render('project_brief','', $this->get_settings()); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xl-6 offset-xl-1 col-md-12 col-12 order-1 order-lg-2">
                            <div class="thumbnail position-relative">
                                <?php
                                if($settings['keystroke_video_popup_image']['id']){
                                    echo wp_get_attachment_image($settings['keystroke_video_popup_image']['id'], $settings['keystroke_video_popup_image_size_size'], '', array("class" => "image w-100 paralax-image"));
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
                                <?php if($settings['keystroke_pb_thumb_bg_shape'] == 'yes'){ ?>
                                    <div class="shape-group shape-01">
                                        <span class="icon icon-contact-01"></span>
                                    </div>
                                    <div class="shape-group shape-02">
                                        <span class="icon icon-contact-03"></span>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- End Project Brief  -->
        <?php } elseif( $settings['keystroke_project_thumb_position'] == 'bottom' ) { ?>
            <!-- Start Project Solutions Area  -->
            <div class="axil-project-b axil-project-solutions-area shape-group-position ax-section-gap bg-color-white">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-8 offset-lg-2">
                            <div class="content">
                                <div class="section-title-inner <?php echo esc_attr($settings['axil_project_brief_align']); ?>">
                                    <?php $this->axil_section_title_render('project_brief','', $this->get_settings()); ?>
                                </div>
                                <div class="thumbnail mt--60 move-up wow position-relative">
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
                <?php if($settings['keystroke_pb_thumb_bg_shape'] == 'yes'){ ?>
                    <div class="shape-group">
                        <div class="shape">
                            <span class="icon icon-shape-19"></span>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <!-- End Project Solutions Area  -->
        <?php } else { ?>
             <div class="axil-project-b axil-project-brief project-bief-styles ax-section-gap bg-color-white">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-6 col-xl-6 col-md-12 col-12">
                            <div class="thumbnail position-relative">
                                <?php
                                if($settings['keystroke_video_popup_image']['id']){
                                    echo wp_get_attachment_image($settings['keystroke_video_popup_image']['id'], $settings['keystroke_video_popup_image_size_size'], '', array("class" => "image w-100 paralax-image"));
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
                                <?php if($settings['keystroke_pb_thumb_bg_shape'] == 'yes'){ ?>
                                    <div class="shape-group shape-01">
                                        <span class="icon icon-contact-01"></span>
                                    </div>
                                    <div class="shape-group shape-02">
                                        <span class="icon icon-contact-03"></span>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xl-5 offset-xl-1 col-md-12 col-12">
                            <div class="content mt_md--30 mt_sm--30">
                                <div class="inner move-up wow <?php echo esc_attr($settings['axil_project_brief_align']); ?>">
                                    <?php $this->axil_section_title_render('project_brief','', $this->get_settings()); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
             </div>
        <?php } ?>
        <!-- End Project Brief  -->
        <?php
    }

}

Plugin::instance()->widgets_manager->register_widget_type( new keystroke_Elementor_Widget_ProjectBrief() );


