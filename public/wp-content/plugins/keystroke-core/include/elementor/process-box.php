<?php

namespace Elementor;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class Keystroke_Elementor_Widget_Process_Box extends Widget_Base
{

    use \Elementor\KeystrokeElementCommonFunctions;

    public function get_name()
    {
        return 'keystroke_process_box';
    }

    public function get_title()
    {
        return __('Process Box', 'keystroke');
    }

    public function get_icon()
    {
        return 'axil-icon';
    }

    public function get_categories()
    {
        return ['keystroke'];
    }

    public function get_keywords()
    {
        return ['process box', 'process', 'step', 'step box', 'keystroke'];
    }

    protected function _register_controls()
    {

        $this->axil_section_title('process', 'process', 'This is our way', 'h2', 'In vel varius turpis, non dictum sem. Aenean in efficitur ipsum, in egestas ipsum. Mauris in mi ac tellus.', 'true', 'text-left');

        // Process group
        $this->start_controls_section(
            'process',
            [
                'label' => esc_html__('Process', 'keystroke'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'process_icon_type',
            [
                'label' => esc_html__('Select Icon Type', 'keystroke'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'text',
                'options' => [
                    'image' => esc_html__('Image', 'keystroke'),
                    'icon' => esc_html__('Icon', 'keystroke'),
                    'text' => esc_html__('Text', 'keystroke'),
                ],
            ]
        );
        $repeater->add_control(
            'process_image',
            [
                'label' => esc_html__('Upload Image', 'keystroke'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'process_icon_type' => 'image'
                ]

            ]
        );
        $repeater->add_control(
            'process_number', [
                'label' => esc_html__('Number', 'keystroke'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('1', 'keystroke'),
                'label_block' => true,
                'condition' => [
                    'process_icon_type' => 'text'
                ]
            ]
        );


        if (axil_is_elementor_version('<', '2.6.0')) {
            $repeater->add_control(
                'process_icon',
                [
                    'show_label' => false,
                    'type' => Controls_Manager::ICON,
                    'label_block' => true,
                    'default' => 'fa fa-pen',
                    'condition' => [
                        'process_icon_type' => 'icon'
                    ]
                ]
            );
        } else {
            $repeater->add_control(
                'process_selected_icon',
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
                        'process_icon_type' => 'icon'
                    ]

                ]
            );
        }
        $repeater->add_control(
            'process_icon_bg_color',
            [
                'label' => esc_html__('Process Icon Background Color', 'keystroke'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .axil-service-style--3 .icon' => 'background: {{VALUE}};',
                ],
            ]
        );


        $repeater->add_control(
            'process_title', [
                'label' => esc_html__('Title', 'keystroke'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Process Title', 'keystroke'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'process_content',
            [
                'label' => esc_html__('Description', 'keystroke'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'rows' => 10,
                'default' => 'We design professional looking yet simple websites. Our designs are search engine and user friendly.',
                'placeholder' => esc_html__('Type your description here', 'keystroke'),
            ]
        );
        $this->add_control(
            'process_list',
            [
                'label' => esc_html__('Process List', 'keystroke'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'process_title' => esc_html__('1. Discuss', 'keystroke'),
                        'process_number' => esc_html__('1', 'keystroke'),
                        'process_content' => esc_html__('Donec metus lorem, vulputate at sapien sit amet, auctor iaculis lorem. In vel hendrerit nisi. Vestibulum eget risus velit.', 'keystroke'),
                    ],
                    [
                        'process_title' => esc_html__('2. Mapping', 'keystroke'),
                        'process_number' => esc_html__('2', 'keystroke'),
                        'process_content' => esc_html__('Donec metus lorem, vulputate at sapien sit amet, auctor iaculis lorem. In vel hendrerit nisi. Vestibulum eget risus velit.', 'keystroke'),
                    ],
                    [
                        'process_title' => esc_html__('3. Execution', 'keystroke'),
                        'process_number' => esc_html__('3', 'keystroke'),
                        'process_content' => esc_html__('Donec metus lorem, vulputate at sapien sit amet, auctor iaculis lorem. In vel hendrerit nisi. Vestibulum eget risus velit.', 'keystroke'),
                    ],
                ],
                'title_field' => '{{{ process_title }}}',
            ]
        );

        $this->end_controls_section();

        $this->axil_basic_style_controls('section_title_pre_title', 'Tag Line', '.section-title span.sub-title');
        $this->axil_basic_style_controls('section_title_title', 'Title', '.section-title .title');
        $this->axil_basic_style_controls('section_title_description', 'Description', '.section-title p');

        $this->axil_basic_style_controls('process_title', 'Process Box Title', '.axil-service-style--3 .content .title');
        $this->axil_basic_style_controls('process_content', 'Process Box Content', '.axil-service-style--3 .content p');

        $this->axil_section_style_controls('process_area', 'Process Area', '.axil-service-area.ax-section-gap.bg-color-white');


    }

    protected function render($instance = [])
    {

        $settings = $this->get_settings_for_display();
        $settings = $this->get_settings_for_display();
        $this->add_render_attribute('title_args', 'class', 'title wow');
        $this->add_render_attribute('title_args', 'data-wow-duration', '1s');
        $this->add_render_attribute('title_args', 'data-wow-delay', '500ms');

        ?>
        <!-- Start Our Service Area  -->
        <div class="axil-service-area ax-section-gap bg-color-white">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-xl-12">
                        <div class="section-title <?php echo esc_attr($settings['axil_process_align']); ?>">
                            <?php $this->axil_section_title_render('process', 'extra04-color', $this->get_settings()); ?>
                        </div>
                    </div>
                </div>

                <?php
                if ($settings['process_list']) {
                    echo '<div class="row">';
                    foreach ($settings['process_list'] as $process) {
                        ?>
                        <!-- Start Single Service  -->
                        <div class="col-lg-4 col-md-6 col-12 mt--50 mt_md--40 mt_sm--30 move-up wow elementor-repeater-item-<?php echo $process['_id']; ?>">
                            <div class="axil-service-style--3">
                                <div class="icon">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/layer.png"
                                         alt="Icon Images">
                                    <?php
                                    if ($process['process_icon_type'] === 'icon') { ?>
                                        <?php if (!empty($process['process_icon']) || !empty($process['process_selected_icon']['value'])) : ?>
                                            <div class="text">
                                                <?php axil_render_icon($process, 'process_icon', 'process_selected_icon'); ?>
                                            </div>
                                        <?php endif; ?>
                                    <?php } elseif ($process['process_icon_type'] === 'image') {
                                        if (!empty($process['process_image'])) { ?>
                                            <div class="text">
                                                <?php echo Group_Control_Image_Size::get_attachment_image_html($process, 'full', 'process_image'); ?>
                                            </div>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <div class="text"><?php echo esc_html($process['process_number']); ?></div>
                                    <?php }  ?>
                                </div>
                                <div class="content">
                                    <h4 class="title"><?php echo esc_html($process['process_title']); ?></h4>
                                    <p><?php echo esc_html($process['process_content']); ?></p>
                                </div>
                            </div>
                        </div>
                        <!-- End Single Service  -->
                    <?php }
                    echo '</div>';
                }
                ?>
            </div>
        </div>
        <!-- End Our Service Area  -->
        <?php

    }

}

Plugin::instance()->widgets_manager->register_widget_type(new Keystroke_Elementor_Widget_Process_Box());


