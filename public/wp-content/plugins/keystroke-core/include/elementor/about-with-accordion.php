<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Axil_Elementor_Widget_AboutWithAccordion extends Widget_Base {

    use \Elementor\KeystrokeElementCommonFunctions;

    public function get_name() {
        return 'keystroke-about-with-accordion';
    }

    public function get_title() {
        return esc_html__( 'About With Accordion', 'keystroke' );
    }

    public function get_icon() {
        return 'axil-icon';
    }

    public function get_categories() {
        return [ 'keystroke' ];
    }

    public function get_keywords()
    {
        return ['about with accordion', 'accordion', 'keystroke'];
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

        $this->axil_section_title('about_with_accordion', 'About us', 'Why branding matters?', 'h2', 'Ut condimentum enim nec diam convallis mollis. Sed felis quam, semper dapibus purus sed, rhoncus ullamcorper lacus.', 'true', 'text-left');


        // Accordion group
        $this->start_controls_section(
            'accordion',
            [
                'label' => esc_html__('Accordion', 'keystroke'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'accordion_icon_type',
            [
                'label' => esc_html__('Select Icon Type', 'keystroke'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'icon',
                'options' => [
                    'image' => esc_html__('Image', 'keystroke'),
                    'icon' => esc_html__('Icon', 'keystroke'),
                    'text' => esc_html__('Text', 'keystroke'),
                ],
            ]
        );
        $repeater->add_control(
            'accordion_image',
            [
                'label' => esc_html__('Upload Image', 'keystroke'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'accordion_icon_type' => 'image'
                ]

            ]
        );
        $repeater->add_control(
            'accordion_number', [
                'label' => esc_html__('Number', 'keystroke'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('1', 'keystroke'),
                'label_block' => true,
                'condition' => [
                    'accordion_icon_type' => 'text'
                ]
            ]
        );


        if (axil_is_elementor_version('<', '2.6.0')) {
            $repeater->add_control(
                'accordion_icon',
                [
                    'show_label' => false,
                    'type' => Controls_Manager::ICON,
                    'label_block' => true,
                    'default' => 'fa fa-pen',
                    'condition' => [
                        'accordion_icon_type' => 'icon'
                    ]
                ]
            );
        } else {
            $repeater->add_control(
                'accordion_selected_icon',
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
                        'accordion_icon_type' => 'icon'
                    ]

                ]
            );
        }


        $repeater->add_control(
            'accordion_title', [
                'label' => esc_html__('Title', 'keystroke'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Accordion Title', 'keystroke'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'accordion_content',
            [
                'label' => esc_html__('Description', 'keystroke'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'rows' => 10,
                'default' => 'We design professional looking yet simple websites. Our designs are search engine and user friendly.',
                'placeholder' => esc_html__('Type your description here', 'keystroke'),
            ]
        );
        $this->add_control(
            'accordion_list',
            [
                'label' => esc_html__('Accordion List', 'keystroke'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'accordion_title' => esc_html__('Strategy', 'keystroke'),
                        'accordion_content' => esc_html__('Aenean hendrerit laoreet vehicula. Nullam convallis augue at enim gravida pellentesque.', 'keystroke'),
                    ],
                    [
                        'accordion_title' => esc_html__('Design', 'keystroke'),
                        'accordion_content' => esc_html__('Aenean hendrerit laoreet vehicula. Nullam convallis augue at enim gravida pellentesque.', 'keystroke'),
                    ],
                    [
                        'accordion_title' => esc_html__('Development', 'keystroke'),
                        'accordion_content' => esc_html__('Aenean hendrerit laoreet vehicula. Nullam convallis augue at enim gravida pellentesque.', 'keystroke'),
                    ],
                ],
                'title_field' => '{{{ accordion_title }}}',
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            '_about_with_accordion_left_content',
            [
                'label' => esc_html__( 'Left Content', 'keystroke' ),
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


        $this->axil_basic_style_controls('about_with_accordion_pre_title', 'Tag Line', '.section-title span.sub-title');
        $this->axil_basic_style_controls('about_with_accordion_title', 'Title', '.section-title .title');
        $this->axil_basic_style_controls('about_with_accordion_description', 'Description', '.section-title p');


        $this->axil_basic_style_controls('about_with_accordion_tab_title', 'Accordion - Title', '.axil-accordion .card .card-header h5 .btn.btn-link');
        $this->axil_basic_style_controls('about_with_accordion_tab_description', 'Accordion - Description', '.axil-accordion .card .card-body');


        $this->axil_section_style_controls('about_with_accordion_area', 'Area Style', '.axil-about-area.ax-section-gap.bg-color-white');



    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();

        $this->add_render_attribute('title_args', 'class', 'title move-up wow');

        $col_class = (!empty($settings['select_contact_form'])) ? "col-lg-6 col-xl-5 offset-xl-2 col-md-12 col-12 mt_md--40 mt_sm--40" : "col-lg-12 col-xl-12 col-md-12 col-12";

        ?>
        <!-- Start About Area  -->
        <div class="axil-about-area ax-section-gap bg-color-white">
            <div class="container">
                <div class="row">
                    <?php if(!empty($settings['select_contact_form'])){ ?>
                    <div class="col-lg-6 col-xl-5 col-md-12 col-12">
                        <div class="contact-form-wrapper">
                            <!-- Start Contact Form -->
                            <div class="axil-contact-form contact-form-style-1">
                                <?php echo do_shortcode( '[contact-form-7  id="'.$settings['select_contact_form'].'"]' ); ?>
                                <div class="shape-group">
                                    <div class="shape shape-01">
                                        <?php echo get_template_part('assets/images/others/shape-07'); ?>
                                    </div>
                                    <div class="shape shape-02">
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/others/shape-16.svg" alt="Shape Images">
                                    </div>
                                </div>
                                <!-- End Shape Group  -->
                            </div>
                            <!-- End Contact Form -->
                        </div>
                    </div>
                    <?php } ?>
                    <div class="<?php echo esc_attr($col_class); ?>">
                        <div class="axil-about-inner">
                            <div class="section-title <?php echo esc_attr($settings['axil_about_with_accordion_align']); ?>">
                                <?php $this->axil_section_title_render('about_with_accordion','extra08-color', $this->get_settings()); ?>
                            </div>

                            <?php if( $settings['accordion_list'] ){ ?>
                                <!-- Start Accordion Area  -->
                                <div id="accordion-<?php echo $this->get_id(); ?>" class="axil-accordion mt--15 mt_md--15 mt_sm--15">
                                    <?php foreach ($settings['accordion_list'] as $index => $accordion){

                                        $expanded = ( $index == 0 ) ? "true" : "false";
                                        $show = ( $index == 0 ) ? "show" : "";
                                        $collapsed = ( $index !== 0 ) ? "collapsed" : "";

                                        ?>
                                        <!-- Start Single Card  -->
                                        <div class="card elementor-repeater-item-<?php echo $accordion['_id']; ?>">
                                            <div class="card-header" id="headingOne-<?php echo esc_attr($index); ?>">
                                                <h5 class="mb-0">
                                                    <button class="btn btn-link <?php echo esc_attr($collapsed); ?>" data-toggle="collapse" data-target="#collapse-<?php echo esc_attr($index); ?>" aria-expanded="<?php echo esc_attr($expanded); ?>" aria-controls="collapse-<?php echo esc_attr($index); ?>">
                                                        <?php
                                                        if ($accordion['accordion_icon_type'] === 'icon') { ?>
                                                            <?php if (!empty($accordion['accordion_icon']) || !empty($accordion['accordion_selected_icon']['value'])) : ?>
                                                                <?php axil_render_icon($accordion, 'accordion_icon', 'accordion_selected_icon'); ?>
                                                            <?php endif; ?>
                                                        <?php } elseif ($accordion['accordion_icon_type'] === 'image') {
                                                            if (!empty($accordion['accordion_image'])) { ?>
                                                                <?php echo Group_Control_Image_Size::get_attachment_image_html($accordion, 'full', 'accordion_image'); ?>
                                                            <?php } ?>
                                                        <?php } else { ?>
                                                            <span><?php echo esc_html($accordion['accordion_number']); ?></span>
                                                        <?php }  ?>

                                                        <span><?php echo axil_kses_basic($accordion['accordion_title']); ?></span>
                                                    </button>
                                                </h5>
                                            </div>

                                            <div id="collapse-<?php echo esc_attr($index); ?>" class="collapse <?php echo esc_attr($show); ?>" aria-labelledby="headingOne-<?php echo esc_attr($index); ?>" data-parent="#accordion-<?php echo $this->get_id(); ?>">
                                                <div class="card-body"><?php echo axil_kses_intermediate($accordion['accordion_content']); ?></div>
                                            </div>
                                        </div>
                                        <!-- End Single Card  -->
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End About Area  -->
        <?php
    }

}

Plugin::instance()->widgets_manager->register_widget_type( new Axil_Elementor_Widget_AboutWithAccordion() );


