<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Axil_Elementor_Widget_ProtfolioDetails extends Widget_Base {

    use \Elementor\KeystrokeElementCommonFunctions;

    public function get_name() {
        return 'keystroke-portfolio-details';
    }

    public function get_title() {
        return esc_html__( 'Portfolio Details', 'keystroke' );
    }

    public function get_icon() {
        return 'axil-icon';
    }

    public function get_categories() {
        return [ 'keystroke' ];
    }

    public function get_keywords()
    {
        return ['portfolio details', 'work details', 'project single', 'project details'];
    }

    protected function _register_controls() {

        $this->axil_section_title('portfolio_details_left', '', 'Creative agency', 'h2', 'Donec metus lorem, vulputate at sapien sit amet, auctor iaculis lorem. In vel hendrerit nisi. Vestibulum eget risus velit. Aliquam tristique libero at dui sodales. <br> et placerat orci lobortis. Maecenas ipsum neque, elementum id dignissim et, imperdiet vitae mauris.', 'true', 'text-left');
        $this->start_controls_section(
            '_portfolio_details_left_content',
            [
                'label' => esc_html__( 'Launch The Site Button', 'keystroke' ),
            ]
        );
        $this->axil_link_controls('launch_the_site_button', 'Launch The Site Button', 'Launch The Site ');
        $this->end_controls_section();

        $this->axil_section_title('portfolio_details_right', '', 'We delivered', 'h3', 'Ut condimentum enim nec diam convallis mollis. Sed felis quam, semper dapibus purus sed, rhoncus ullamcorper lacus.', 'true', 'text-left');

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

        $this->axil_basic_style_controls('portfolio_details_pre_title', 'Tag Line', '.section-title span.sub-title');
        $this->axil_basic_style_controls('portfolio_details_title', 'Title', '.section-title .title');
        $this->axil_basic_style_controls('portfolio_details_description', 'Description', '.section-title p');

        // Link Style Control
        $this->axil_link_controls_style('launch_the_site_button_style', 'Careers Button', '.axil-button', 'btn-large', 'btn-transparent');

        $this->axil_basic_style_controls('portfolio_details_tab_title', 'Accordion - Title', '.axil-accordion .card .card-header h5 .btn.btn-link');
        $this->axil_basic_style_controls('portfolio_details_tab_description', 'Accordion - Description', '.axil-accordion .card .card-body');


        $this->axil_section_style_controls('portfolio_details_area', 'Area Style', '.axil-about-area.ax-section-gap.bg-color-white');



    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();

        $this->add_render_attribute('title_args', 'class', 'title move-up wow');

        ?>
        <!-- Start Portfolio Details Area  -->
        <div class="axil-portfolio-details ax-section-gap bg-color-white">
            <div class="container">
                <div class="row">
                    <div class="col-xl-7 col-lg-6 col-sm-12 col-12">
                        <div class="portfolio-wrapper">

                            <div class="section-title <?php echo esc_attr($settings['axil_portfolio_details_left_align']); ?>">

                                <?php $this->axil_section_title_render('portfolio_details_left','extra04-color', $this->get_settings()); ?>

                                <?php
                                // Link
                                if ('2' == $settings['axil_launch_the_site_button_link_type']) {
                                    $this->add_render_attribute('axil_launch_the_site_button_link', 'href', get_permalink($settings['axil_launch_the_site_button_page_link']));
                                    $this->add_render_attribute('axil_launch_the_site_button_link', 'target', '_self');
                                    $this->add_render_attribute('axil_launch_the_site_button_link', 'rel', 'nofollow');
                                } else {
                                    if (!empty($settings['axil_launch_the_site_button_link']['url'])) {
                                        $this->add_render_attribute('axil_launch_the_site_button_link', 'href', $settings['axil_launch_the_site_button_link']['url']);
                                    }
                                    if ($settings['axil_launch_the_site_button_link']['is_external']) {
                                        $this->add_render_attribute('axil_launch_the_site_button_link', 'target', '_blank');
                                    }
                                    if (!empty($settings['axil_launch_the_site_button_link']['nofollow'])) {
                                        $this->add_render_attribute('axil_launch_the_site_button_link', 'rel', 'nofollow');
                                    }
                                }
                                $arrow = ($settings['axil_launch_the_site_button_style_button_arrow_icon'] == 'yes') ? '<span class="button-icon"></span>' : '';
                                // Button
                                if (!empty($settings['axil_launch_the_site_button_link']['url']) || isset($settings['axil_launch_the_site_button_link_type'])) {

                                    $this->add_render_attribute('axil_launch_the_site_button_link_style', 'class', ' axil-button wow slideFadeInUp mt--40');
                                    // Style
                                    if (!empty($settings['axil_launch_the_site_button_style_button_style'])) {
                                        $this->add_render_attribute('axil_launch_the_site_button_link_style', 'class', '' . $settings['axil_launch_the_site_button_style_button_style'] . '');
                                    }
                                    // Size
                                    if (!empty($settings['axil_launch_the_site_button_style_button_size'])) {
                                        $this->add_render_attribute('axil_launch_the_site_button_link_style', 'class', $settings['axil_launch_the_site_button_style_button_size']);
                                    }
                                    // Link
                                    $button_html = '<a ' . $this->get_render_attribute_string('axil_launch_the_site_button_link_style') . ' ' . $this->get_render_attribute_string('axil_launch_the_site_button_link') . '>' . '<span class="button-text">' . $settings['axil_launch_the_site_button_text'] . '</span>' . $arrow . '</a>';
                                    echo $button_html;
                                }
                                ?>

                            </div>
                        </div>
                    </div>
                    <div class="col-xl-5 col-lg-6 col-sm-12 col-12 mt_md--40 mt_sm--40">
                        <div class="axil-about-inner">
                            <div class="section-title <?php echo esc_attr($settings['axil_portfolio_details_right_align']); ?>">
                                <?php $this->axil_section_title_render('portfolio_details_right','extra08-color', $this->get_settings()); ?>
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
        <!-- End Portfolio Details Area  -->
        <?php
    }

}

Plugin::instance()->widgets_manager->register_widget_type( new Axil_Elementor_Widget_ProtfolioDetails() );


