<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class keystroke_Elementor_Widget_Services_Navigation extends Widget_Base {

    use \Elementor\KeystrokeElementCommonFunctions;

    public function get_name() {
        return 'keystroke-services-navigation';
    }

    public function get_title() {
        return __( 'Services Navigation', 'keystroke' );
    }

    public function get_icon() {
        return 'axil-icon';
    }

    public function get_categories() {
        return [ 'keystroke' ];
    }

    public function get_keywords()
    {
        return ['services navigation', 'navigation', 'keystroke'];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'keystroke_services_navigation',
            [
                'label' => esc_html__( 'Services Navigation', 'keystroke' ),
            ]
        );
        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'navigation_title', [
                'label' => esc_html__( 'Title', 'keystroke' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Section Name' , 'keystroke' ),
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'navigation_id', [
                'label' => esc_html__( 'Section ID', 'keystroke' ),
                'description' => esc_html__( 'Add ID without #', 'keystroke' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'navigations',
            [
                'label' => esc_html__( 'Services Navigations', 'keystroke' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'navigation_title' => esc_html__( 'Section Name #1', 'keystroke' ),
                    ],
                    [
                        'navigation_title' => esc_html__( 'Section Name #2', 'keystroke' ),
                    ],
                    [
                        'navigation_title' => esc_html__( 'Section Name #3', 'keystroke' ),
                    ],
                    [
                        'navigation_title' => esc_html__( 'Section Name #4', 'keystroke' ),
                    ],
                ],
                'title_field' => '{{{ navigation_title }}}',
            ]
        );

        $this->end_controls_section();

        // Start Style Tabs
        $this->axil_basic_style_controls('navigations_title', 'Link Title', '.axil-scroll-navigation .nav .nav-item a');
        $this->axil_basic_style_controls('navigations_title_active', 'Link Title Active', '.axil-scroll-navigation .nav .nav-item a.active');
        $this->axil_basic_style_controls('navigations_title_hover', 'Link Title Hover', '.axil-scroll-navigation .nav .nav-item a:hover');
        $this->axil_basic_style_controls('navigations_area', 'Area Style', 'nav.axil-scroll-nav.navbar.navbar-example2');

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();

        if (!array($settings['navigations'])){
            return;
        }

        

        if($settings['navigations']){ ?>
            <!-- Start Navigation Nav  -->
            <nav class="axil-scroll-nav navbar navbar-example2">
                <ul class="nav nav-pills justify-content-center sidebar__inner">
                    <?php
                    foreach ($settings['navigations'] as $index => $nav ){
                        $active = ($index == 0) ? 'active' : '';
                        ?><li class="nav-item"><a class="nav-link smoth-animation <?php echo esc_attr($active); ?>" href="#<?php echo esc_attr($nav['navigation_id']) ?>"><?php echo esc_html($nav['navigation_title']) ?></a></li><?php
                    }
                    ?>
                </ul>
            </nav>
            <!-- End Navigation Nav  -->
        <?php }
    }

}

Plugin::instance()->widgets_manager->register_widget_type( new keystroke_Elementor_Widget_Services_Navigation() );


