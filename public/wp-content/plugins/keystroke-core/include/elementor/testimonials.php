<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class keystroke_Elementor_Widget_Testimonials extends Widget_Base {

    use \Elementor\KeystrokeElementCommonFunctions;

    public function get_name() {
        return 'keystroke-testimonials';
    }

    public function get_title() {
        return __( 'Testimonials', 'keystroke' );
    }

    public function get_icon() {
        return 'axil-icon';
    }

    public function get_categories() {
        return [ 'keystroke' ];
    }

    public function get_keywords()
    {
        return ['testimonials', 'review', 'client review', 'keystroke'];
    }

    protected function _register_controls() {

        $this->axil_section_title('testimonial', 'testimonials', 'From getting started', 'h2', 'In vel varius turpis, non dictum sem. Aenean in efficitur ipsum, in egestas ipsum. Mauris in mi ac tellus.', 'true', 'text-center');

        $this->start_controls_section(
            '_testimonial_source_logo',
            [
                'label' => esc_html__( 'Review Source Logo', 'keystroke' ),
            ]
        );
        $this->add_control(
            'testimonial_source_logo',
            [
                'label' => esc_html__( 'Review Source Logo', 'keystroke' ),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ]
            ]
        );
        $this->add_control(
            'testimonial_bg_shape_1',
            [
                'label'        => esc_html__('Background Shape', 'keystroke'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Yes', 'keystroke'),
                'label_off'    => esc_html__('No', 'keystroke'),
                'return_value' => 'yes',
                'separator'    => 'before',
                'default'      => 'yes'
            ]
        );

        $this->end_controls_section();

        // Review group
        $this->start_controls_section(
            'review_list',
            [
                'label' => esc_html__( 'Review List', 'keystroke' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();


        $repeater->add_control(
            'reviewer_image',
            [
                'label' => esc_html__( 'Reviewer Image', 'keystroke' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'dynamic' => [
                    'active' => true,
                ]
            ]
        );
        $repeater->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
                'default' => 'full',
                'exclude' => ['custom'],
                'separator' => 'none',
            ]
        );
        $repeater->add_control(
            'reviewer_name', [
                'label' => esc_html__( 'Reviewer Name', 'keystroke' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Martha Maldonado' , 'keystroke' ),
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'reviewer_title', [
                'label' => esc_html__( 'Reviewer Title', 'keystroke' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Martha Maldonado' , 'keystroke' ),
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'review_content',
            [
                'label' => esc_html__( 'Review Content', 'keystroke' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'rows' => 10,
                'default' => 'Donec metus lorem, vulputate at sapien sit amet, auctor iaculis lorem. In vel hendrerit nisi. Vestibulum eget risus velit.',
                'placeholder' => esc_html__( 'Type your review content here', 'keystroke' ),
            ]
        );
        $repeater->add_control(
            'review_link_text',
            [
                'label' => esc_html__( 'Review Link Text', 'keystroke' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Read Project Case Study',
                'title' => esc_html__( 'Enter link text', 'keystroke' ),
            ]
        );

        $repeater->add_control(
            'review_link_type',
            [
                'label' => esc_html__( 'Review Link Type', 'keystroke' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '1' => 'Custom Link',
                    '2' => 'Internal Page',
                ],
                'default' => '1',
            ]
        );
        $repeater->add_control(
            'review_link',
            [
                'label' => esc_html__( 'Review Link link', 'keystroke' ),
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
                    'review_link_type' => '1'
                ]
            ]
        );
        $repeater->add_control(
            'review_page_link',
            [
                'label' => esc_html__( 'Select Review Link Page', 'keystroke' ),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'label_block' => true,
                'options' => axil_get_all_pages(),
                'condition' => [
                    'review_link_type' => '2'
                ]
            ]
        );
        $this->add_control(
            'reviews_list',
            [
                'label' => esc_html__( 'Review List', 'keystroke' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' =>  $repeater->get_controls(),
                'default' => [
                    [
                        'reviewer_name' => esc_html__( 'Martha Maldonado', 'keystroke' ),
                        'reviewer_title' => esc_html__( 'Executive Chairman @ Google', 'keystroke' ),
                        'review_content' => esc_html__( 'Donec metus lorem, vulputate at sapien sit amet, auctor iaculis lorem. In vel hendrerit nisi. Vestibulum eget risus velit.', 'keystroke' ),
                    ],
                    [
                        'reviewer_name' => esc_html__( 'Martha Maldonado', 'keystroke' ),
                        'reviewer_title' => esc_html__( 'Executive Chairman @ Google', 'keystroke' ),
                        'review_content' => esc_html__( 'Donec metus lorem, vulputate at sapien sit amet, auctor iaculis lorem. In vel hendrerit nisi. Vestibulum eget risus velit.', 'keystroke' ),
                    ],

                ],
                'title_field' => '{{{ reviewer_name }}}',
            ]
        );

        $this->end_controls_section();


        $this->axil_basic_style_controls('testimonial_section_before_title', 'Tag Line', '.section-title span.sub-title');
        $this->axil_basic_style_controls('testimonial_section_title', 'Title', '.section-title .title');
        $this->axil_basic_style_controls('testimonial_section_description', 'Description', '.section-title p');

        $this->axil_basic_style_controls('testimonial_name', 'Testimonial - Name', '.axil-testimonial .clint-info-wrapper .client-info h4.title');
        $this->axil_basic_style_controls('testimonial_title', 'Testimonial - Title', '.axil-testimonial .clint-info-wrapper .client-info span');
        $this->axil_basic_style_controls('testimonial_content', 'Testimonial - Contenet', '.axil-testimonial .description p');
        $this->axil_basic_style_controls('testimonial_link', 'Testimonial - Link', '.axil-testimonial .description a.axil-link-button');

        $this->axil_section_style_controls('testimonial_area', 'Area Background', '.axil-testimonial-area.ax-section-gap.bg-color-lightest');


    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();

        ?>
        <!-- Start Testimonial Area -->
        <div class="axil-testimonial-area ax-section-gap bg-color-lightest">
            <?php if($settings['testimonial_bg_shape_1'] == 'yes'){ ?>
                <div class="shape-group">
                    <span class="icon icon-shape-01"></span>
                </div>
            <?php } ?>

            <div class="container">
                <?php if(!empty($settings['testimonial_source_logo']['id'])){ ?>
                    <div class="row align-items-center">
                        <div class="col-lg-8 col-md-8 col-sm-8 col-12">
                            <div class="section-title <?php echo esc_attr($settings['axil_testimonial_align']); ?>">
                                <?php $this->axil_section_title_render('testimonial','extra05-color', $this->get_settings()); ?>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-12 mt_mobile--20">
                            <div class="axil-social-share text-left text-sm-right">
                                <?php echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'full', 'testimonial_source_logo' ); ?>
                            </div>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="section-title <?php echo esc_attr($settings['axil_testimonial_align']); ?>">
                                <?php $this->axil_section_title_render('testimonial','extra05-color', $this->get_settings()); ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>


                <?php if($settings['reviews_list']){ ?>
                    <div class="testimonial-activation pb--10">
                        <div class="row axil-testimonial-single">
                            <?php 
                            $i = 1;
                            foreach ($settings['reviews_list'] as $item){ 
                                
                                $active = ($i == 1 ) ? "active" : "" ;
                                ?>
                                <!-- Start Single Testimonial -->
                                <div class="col-lg-6 mt--60 mt_sm--30 mt_md--30 move-up wow">
                                    <div class="axil-testimonial testimonial axil-control <?php echo esc_attr($active); ?>">
                                        <div class="inner">
                                            <div class="clint-info-wrapper">
                                                <?php if ( ! empty( $item['reviewer_image']['url'] ) ) : ?>
                                                    <div class="thumb">
                                                        <?php echo Group_Control_Image_Size::get_attachment_image_html( $item, 'thumbnail', 'reviewer_image' ); ?>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="client-info">
                                                    <?php if(!empty($item['reviewer_name'])){ ?> <h4 class="title"><?php echo esc_html($item['reviewer_name']); ?></h4> <?php } ?>
                                                    <?php if(!empty($item['reviewer_title'])){ ?> <span><?php echo esc_html($item['reviewer_title']); ?></span> <?php } ?>
                                                </div>
                                            </div>
                                            <div class="description">
                                                <?php if(!empty($item['review_content'])){ ?> <p class="subtitle-3"><?php echo esc_html($item['review_content']); ?></p> <?php } ?>

                                                <?php
                                                // Link
                                                if ('2' == $item['review_link_type']) {
                                                    $link = get_permalink($item['review_page_link']);
                                                    $target = '_self';
                                                    $rel = 'nofollow';
                                                } else {
                                                    if (!empty($item['review_link']['url'])) {
                                                        $link = $item['review_link']['url'];
                                                    }
                                                    if ($item['review_link']['is_external']) {
                                                        $target = '_blank';
                                                    }
                                                    if (!empty($item['review_link']['nofollow'])) {
                                                        $rel = 'nofollow';
                                                    }
                                                }
                                                ?>
                                                <?php if (!empty($item['review_link_text'])): ?>
                                                    <a href="<?php echo esc_url($link); ?>"
                                                       target="<?php echo esc_attr($target); ?>"
                                                       rel="<?php echo esc_attr($rel); ?>"
                                                       class="axil-link-button"><?php echo esc_html($item['review_link_text']); ?></a>
                                                <?php endif ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Single Testimonial -->
                            <?php $i++; } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
        <!-- End Testimonial Area -->
        <?php
    }

}

Plugin::instance()->widgets_manager->register_widget_type( new keystroke_Elementor_Widget_Testimonials() );


