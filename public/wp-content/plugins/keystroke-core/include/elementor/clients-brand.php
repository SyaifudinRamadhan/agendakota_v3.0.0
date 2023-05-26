<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class keystroke_Elementor_Widget_ClientBrands extends Widget_Base {

    use \Elementor\KeystrokeElementCommonFunctions;

    public function get_name() {
        return 'keystroke-clientbrands';
    }

    public function get_title() {
        return __( 'Client Brands', 'keystroke' );
    }

    public function get_icon() {
        return 'axil-icon';
    }

    public function get_categories() {
        return [ 'keystroke' ];
    }

    public function get_keywords()
    {
        return ['client', 'brand', 'brand logo', 'client logo', 'keystroke'];
    }

    protected function _register_controls() {

        $this->axil_section_title('client_brands', 'top clients', 'We’ve built solutions for...', 'h2', 'Nulla facilisi. Nullam in magna id dolor blandit rutrum eget.');

        $this->start_controls_section(
            '_section_client_logo',
            [
                'label' => esc_html__( 'Client Logo', 'keystroke' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'client_logo_image',
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
            'client_logo_link',
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
            'client_logo_name',
            [
                'label' => esc_html__('Brand Name', 'keystroke'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Brand Name', 'keystroke'),
            ]
        );

        $this->add_control(
            'client_logo_list',
            [
                'show_label' => false,
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ client_logo_name }}}',
                'default' => [
                    ['client_logo_image' => ['url' => Utils::get_placeholder_image_src()]],
                    ['client_logo_image' => ['url' => Utils::get_placeholder_image_src()]],
                    ['client_logo_image' => ['url' => Utils::get_placeholder_image_src()]],
                    ['client_logo_image' => ['url' => Utils::get_placeholder_image_src()]],
                    ['client_logo_image' => ['url' => Utils::get_placeholder_image_src()]],
                    ['client_logo_image' => ['url' => Utils::get_placeholder_image_src()]],
                ]
            ]
        );

        $this->end_controls_section();

        $this->axil_basic_style_controls('client_logo_pre_title', 'Tag Line', '.section-title span.sub-title');
        $this->axil_basic_style_controls('client_logo_title', 'Title', '.section-title .title');
        $this->axil_basic_style_controls('client_logo_description', 'Description', '.section-title p');

        $this->axil_section_style_controls('client_logo_area', 'Area Background', '.axil-brand-area.ax-section-gap.bg-color-white');

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();
        $this->add_render_attribute('title_args', 'class', 'title wow');
        ?>

        <!-- Start Brand Area -->
        <div class="axil-brand-area ax-section-gap bg-color-white axil-client-style-two">
            <div class="container">
                <div class="row align-items-center justify-content-between">
                    <div class="col-xl-4 col-lg-4 col-md-12 col-12">
                        <div class="section-title">
                            <?php $this->axil_section_title_render('client_brands','extra06-color', $this->get_settings()); ?>
                        </div>
                    </div>
                    <div class="col-xl-8 col-lg-8 mt_md--20 mt_sm--20">
                        <div class="axil-brand-logo-wrapper">
                            <ul class="brand-list liststyle d-flex flex-wrap justify-content-center">
                                <?php foreach ($settings['client_logo_list'] as $cllogo){
                                    $target = $cllogo['client_logo_link']['is_external'] ? ' target="_blank"' : '';
                                    $nofollow = $cllogo['client_logo_link']['nofollow'] ? ' rel="nofollow"' : '';
                                    ?>
                                    <li>
                                        <?php if ($cllogo['client_logo_link']['url']){ ?>
                                        <a href="<?php echo esc_url($cllogo['client_logo_link']['url']); ?>" <?php echo esc_attr($target); ?> <?php echo esc_attr($nofollow);  ?>>
                                        <?php } ?>

                                            <?php echo Group_Control_Image_Size::get_attachment_image_html( $cllogo, 'full', 'client_logo_image' ); ?>

                                        <?php if ($cllogo['client_logo_link']['url']){ ?>
                                        </a>
                                        <?php } ?>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Brand Area -->

        <?php

    }

}

Plugin::instance()->widgets_manager->register_widget_type( new keystroke_Elementor_Widget_ClientBrands() );


