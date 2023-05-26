<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class keystroke_Elementor_Widget_FAQ extends Widget_Base {

    use \Elementor\KeystrokeElementCommonFunctions;

    public function get_name() {
        return 'keystroke-faq';
    }

    public function get_title() {
        return __( 'FAQ', 'keystroke' );
    }

    public function get_icon() {
        return 'axil-icon';
    }
    public function get_categories() {
        return [ 'keystroke' ];
    }

    public function get_keywords()
    {
        return ['accordion', 'tab', 'faq'];
    }

    protected function _register_controls() {

        $this->axil_section_title('accordion', 'we\'ve got answers', 'Frequently asked questions', 'h2', 'Aenean hendrerit laoreet vehicula. Nullam convallis augue at enim gravida pellentesque.', true);

        $this->start_controls_section(
            '_accordion',
            [
                'label' => esc_html__( 'Accordion', 'keystroke' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'accordion_title', [
                'label' => esc_html__( 'Accordion Item', 'keystroke' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'This is accordion item title' , 'keystroke' ),
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'accordion_description',
            [
                'label' => esc_html__('Description', 'keystroke'),
                'description' => axil_get_allowed_html_desc( 'intermediate' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => 'Vivamus magna est, placerat et dignissim et, elementum quis lacus. Nulla laoreet pharetra vehicula. Vestibulum euismod augue ac velit consectetur, ac tincidunt ante hendrerit. Sed lacinia elementum felis, ut tempus ipsum blandit non.',
                'label_block' => true,
            ]
        );
        $this->add_control(
            'accordions',
            [
                'label' => esc_html__( 'Repeater Accordion', 'keystroke' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'accordion_title' => esc_html__( 'This is accordion item title #1', 'keystroke' ),
                    ],
                    [
                        'accordion_title' => esc_html__( 'This is accordion item title #2', 'keystroke' ),
                    ],
                    [
                        'accordion_title' => esc_html__( 'This is accordion item title #3', 'keystroke' ),
                    ],
                ],
                'title_field' => '{{{ accordion_title }}}',
            ]
        );

        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();

        ?>
        <!-- Start Faq Area  -->
        <div class="axil-faq-area ax-section-gap bg-color-white">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-title  mb--30 <?php echo esc_attr($settings['axil_accordion_align']); ?>">
                            <?php $this->axil_section_title_render('accordion','extra04-color', $this->get_settings()); ?>
                        </div>
                    </div>
                </div>
                <?php if($settings['accordions']){ ?>
                    <div class="row mt--30">
                        <div class="col-lg-8 offset-lg-2">
                            <!-- Start Accordion Area  -->
                            <div id="accordion-<?php echo esc_attr($this->get_id()); ?>" class="axil-accordion--2">

                            <?php foreach ( $settings['accordions'] as $index => $item){
                                $collapsed = ($index !== '0' ) ? 'collapsed' : '';
                                $aria_expanded = ($index == '0' ) ? "true" : "false";
                                $show = ($index == '0' ) ? "show" : "";
                                ?>

                                <!-- Start Single Card  -->
                                <div class="card">
                                    <div class="card-header" id="heading-<?php echo esc_attr($this->get_id()); ?>-<?php echo esc_attr($index); ?>">
                                        <a href="#" class="btn btn-link d-block text-left <?php echo esc_attr($collapsed); ?>" data-toggle="collapse" data-target="#collapse-<?php echo esc_attr($this->get_id()); ?>-<?php echo esc_attr($index); ?>" aria-expanded=<?php echo esc_attr($aria_expanded); ?> aria-controls="collapse-<?php echo esc_attr($this->get_id()); ?>-<?php echo esc_attr($index); ?>"><?php echo esc_html($item['accordion_title']); ?></a>
                                    </div>
                                    <div id="collapse-<?php echo esc_attr($this->get_id()); ?>-<?php echo esc_attr($index); ?>" class="collapse <?php echo esc_attr($show); ?>" aria-labelledby="heading-<?php echo esc_attr($this->get_id()); ?>-<?php echo esc_attr($index); ?>" data-parent="#accordion-<?php echo esc_attr($this->get_id()); ?>">
                                        <div class="card-body"><?php echo axil_kses_intermediate($item['accordion_description']); ?></div>
                                    </div>
                                </div>
                                <!-- End Single Card  -->

                            <?php } ?>
                            </div>
                            <!-- End Accordion Area  -->

                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
        <!-- End Faq Area  -->
        <?php

    }

}

Plugin::instance()->widgets_manager->register_widget_type( new keystroke_Elementor_Widget_FAQ() );


