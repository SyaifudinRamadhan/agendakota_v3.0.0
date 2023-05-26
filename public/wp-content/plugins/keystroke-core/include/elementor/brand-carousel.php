<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class keystroke_Elementor_Widget_BrandCarousel extends Widget_Base {

    use \Elementor\KeystrokeElementCommonFunctions;

    public function get_name() {
        return 'keystroke-brandcarousel';
    }

    public function get_title() {
        return __( 'Brand Carousel', 'keystroke' );
    }

    public function get_icon() {
        return 'axil-icon';
    }

    public function get_categories() {
        return [ 'keystroke' ];
    }

    public function get_keywords()
    {
        return ['brand', 'logo', 'client logo', 'brand carousel', 'keystroke'];
    }

    protected function _register_controls() {

        $this->axil_section_title('brandcarousel', '', 'We’ve built solutions for...', 'h5', '');
        $this->start_controls_section(
            'keystroke_brandcarousel',
            [
                'label' => esc_html__( 'Client Logo', 'keystroke' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'brandcarousel_logo_image',
            [
                'label' => esc_html__('Logo', 'keystroke'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'dynamic' => [
                    'active' => true,
                ]
            ]
        );

        $repeater->add_control(
            'brandcarousel_logo_link',
            [
                'label' => esc_html__( 'Website Url', 'keystroke' ),
                'type' => \Elementor\Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => esc_html__( 'https://your-link.com', 'keystroke' ),
                'show_external' => true,
                'default' => [
                    'url' => '#',
                    'is_external' => false,
                    'nofollow' => false,
                ],
            ]
        );

        $repeater->add_control(
            'brandcarousel_logo_name',
            [
                'label' => esc_html__('Brand Name', 'keystroke'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Brand Name', 'keystroke'),
            ]
        );

        $this->add_control(
            'brandcarousel_logo_list',
            [
                'show_label' => false,
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ brandcarousel_logo_name }}}',
                'default' => [
                    ['brandcarousel_logo_image' => ['url' => Utils::get_placeholder_image_src()]],
                    ['brandcarousel_logo_image' => ['url' => Utils::get_placeholder_image_src()]],
                    ['brandcarousel_logo_image' => ['url' => Utils::get_placeholder_image_src()]],
                    ['brandcarousel_logo_image' => ['url' => Utils::get_placeholder_image_src()]],
                    ['brandcarousel_logo_image' => ['url' => Utils::get_placeholder_image_src()]],
                    ['brandcarousel_logo_image' => ['url' => Utils::get_placeholder_image_src()]],
                    ['brandcarousel_logo_image' => ['url' => Utils::get_placeholder_image_src()]],
                    ['brandcarousel_logo_image' => ['url' => Utils::get_placeholder_image_src()]],
                    ['brandcarousel_logo_image' => ['url' => Utils::get_placeholder_image_src()]],
                ]
            ]
        );

        $this->end_controls_section();

        $this->axil_basic_style_controls('brandcarousel_pre_title', 'Tag Line', '.section-title span.sub-title');
        $this->axil_basic_style_controls('brandcarousel_title', 'Title', '.section-title .title');
        $this->axil_basic_style_controls('brandcarousel_description', 'Description', '.section-title p');

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();

        ?>
        <!-- Start Client Logo Area  -->
        <div class="axil-client-area bg-shape-image-position bg-color-white axil-bg-oval axil-client-style-one">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-title mb--60 text-center">
                            <?php $this->axil_section_title_render('brandcarousel','', $this->get_settings()); ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="client-logo">
                            <ul class="client-list axil-carousel liststyle d-flex justify-content-center text-center justify-content-md-between" data-slick-options='{
                                    "spaceBetween": 0,
                                    "slidesToShow": 6,
                                    "slidesToScroll": 1,
                                    "arrows": false,
                                    "infinite": true,
                                    "centerMode":true,
                                    "dots": false
                                }' data-slick-responsive='[
                                    {"breakpoint":769, "settings": {"slidesToShow": 4}},
                                    {"breakpoint":756, "settings": {"slidesToShow": 4}},
                                    {"breakpoint":660, "settings": {"slidesToShow": 4}},
                                    {"breakpoint":600, "settings": {"slidesToShow": 3}},
                                    {"breakpoint":481, "settings": {"slidesToShow": 2}}
                                ]'>

                                <?php foreach ($settings['brandcarousel_logo_list'] as $brandcarousel){
                                    $target = $brandcarousel['brandcarousel_logo_link']['is_external'] ? ' target="_blank"' : '';
                                    $nofollow = $brandcarousel['brandcarousel_logo_link']['nofollow'] ? ' rel="nofollow"' : '';
                                    ?>
                                    <li>
                                        <?php if ($brandcarousel['brandcarousel_logo_link']['url']){ ?>
                                        <a href="<?php echo esc_url($brandcarousel['brandcarousel_logo_link']['url']); ?>" <?php echo esc_attr($target); ?> <?php echo esc_attr($nofollow);  ?>>
                                        <?php } ?>
                                            <?php echo Group_Control_Image_Size::get_attachment_image_html( $brandcarousel, 'full', 'brandcarousel_logo_image' ); ?>
                                        <?php if ($brandcarousel['brandcarousel_logo_link']['url']){ ?>
                                        </a>
                                        <?php } ?>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-shape-image">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/others/background-shape.svg" alt="Bg images">
            </div>
        </div>
        <!-- End Client Logo Area  -->
        <?php


    }

}

Plugin::instance()->widgets_manager->register_widget_type( new keystroke_Elementor_Widget_BrandCarousel() );


