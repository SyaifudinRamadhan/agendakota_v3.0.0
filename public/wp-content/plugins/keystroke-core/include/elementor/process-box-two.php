<?php

namespace Elementor;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class Keystroke_Elementor_Widget_Process_Box_Two extends Widget_Base
{

    use \Elementor\KeystrokeElementCommonFunctions;

    public function get_name()
    {
        return 'keystroke_process_box_two';
    }

    public function get_title()
    {
        return __('Process Box Two', 'keystroke');
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
        return ['process box tow', 'process two', 'step', 'step box', 'keystroke'];
    }

    protected function _register_controls()
    {

        $this->axil_section_title('process_two', 'process', 'Our logo design process', 'h2', 'Our comprehensive logo design strategy ensures a perfectly crafted logo for your business.', 'true', 'text-center');

        // Process group
        $this->start_controls_section(
            'process_two',
            [
                'label' => esc_html__('Process', 'keystroke'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'process_two_image',
            [
                'label' => esc_html__('Upload Image', 'keystroke'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );
        $repeater->add_control(
            'process_two_number', [
                'label' => esc_html__('Number', 'keystroke'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('1', 'keystroke'),
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'process_two_before_title',
            [
                'label' => esc_html__('Tag Line', 'keystroke'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('our four step process', 'keystroke'),
                'placeholder' => esc_html__('Type Before Heading Text', 'keystroke'),
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'process_two_before_title_color',
            [
                'label' => esc_html__('Color', 'keystroke'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .section-title span.sub-title' => 'color: {{VALUE}} !important;',
                ],
            ]
        );
        $repeater->add_control(
            'process_two_before_title_bg_color',
            [
                'label' => esc_html__('Background Color', 'keystroke'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .section-title span.sub-title::before' => 'background: {{VALUE}} !important;',
                ],
            ]
        );
        $repeater->add_control(
            'process_two_title', [
                'label' => esc_html__('Title', 'keystroke'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Process Title', 'keystroke'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'process_two_content',
            [
                'label' => esc_html__('Description', 'keystroke'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'rows' => 10,
                'default' => 'Donec metus lorem, vulputate at sapien sit amet, auctor iaculis lorem. In vel hendrerit nisi. Vestibulum eget risus velit. Aliquam tristique libero at dui sodales, et placerat orci lobortis. Maecenas ipsum neque, elementum id dignissim et, imperdiet vitae mauris.',
                'placeholder' => esc_html__('Type your description here', 'keystroke'),
            ]
        );
        $this->add_control(
            'process_two_list',
            [
                'label' => esc_html__('Process List', 'keystroke'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'process_two_title' => esc_html__('Discover', 'keystroke'),
                        'process_two_number' => esc_html__('1', 'keystroke'),
                        'process_two_content' => esc_html__('Donec metus lorem, vulputate at sapien sit amet, auctor iaculis lorem. In vel hendrerit nisi. Vestibulum eget risus velit. Aliquam tristique libero at dui sodales, et placerat orci lobortis. Maecenas ipsum neque, elementum id dignissim et, imperdiet vitae mauris.', 'keystroke'),
                    ],
                    [
                        'process_two_title' => esc_html__('Prototype', 'keystroke'),
                        'process_two_number' => esc_html__('2', 'keystroke'),
                        'process_two_content' => esc_html__('Donec metus lorem, vulputate at sapien sit amet, auctor iaculis lorem. In vel hendrerit nisi. Vestibulum eget risus velit. Aliquam tristique libero at dui sodales, et placerat orci lobortis. Maecenas ipsum neque, elementum id dignissim et, imperdiet vitae mauris.', 'keystroke'),
                    ],
                    [
                        'process_two_title' => esc_html__('Development', 'keystroke'),
                        'process_two_number' => esc_html__('3', 'keystroke'),
                        'process_two_content' => esc_html__('Donec metus lorem, vulputate at sapien sit amet, auctor iaculis lorem. In vel hendrerit nisi. Vestibulum eget risus velit. Aliquam tristique libero at dui sodales, et placerat orci lobortis. Maecenas ipsum neque, elementum id dignissim et, imperdiet vitae mauris.', 'keystroke'),
                    ],
                    [
                        'process_two_title' => esc_html__('Build', 'keystroke'),
                        'process_two_number' => esc_html__('4', 'keystroke'),
                        'process_two_content' => esc_html__('Donec metus lorem, vulputate at sapien sit amet, auctor iaculis lorem. In vel hendrerit nisi. Vestibulum eget risus velit. Aliquam tristique libero at dui sodales, et placerat orci lobortis. Maecenas ipsum neque, elementum id dignissim et, imperdiet vitae mauris.', 'keystroke'),
                    ],
                ],
                'title_field' => '{{{ process_two_title }}}',
            ]
        );

        $this->end_controls_section();

        $this->axil_basic_style_controls('section_title_pre_title', 'Section - Tag Line', '.section-title span.sub-title');
        $this->axil_basic_style_controls('section_title_title', 'Section - Title', '.section-title .title');
        $this->axil_basic_style_controls('section_title_description', 'Section - Description', '.section-title p');

        $this->axil_basic_style_controls('process_two_title', 'Process Box Title', '.axil-working-process .content .title');
        $this->axil_basic_style_controls('process_two_content', 'Process Box Content', '.axil-working-process .content p');

        $this->axil_section_style_controls('process_two_area', 'Process Area', '.axil-service-area.ax-section-gap.bg-color-white');


    }

    protected function render($instance = [])
    {

        $settings = $this->get_settings_for_display();
        $settings = $this->get_settings_for_display();
        $this->add_render_attribute('title_args', 'class', 'title wow');
        $this->add_render_attribute('title_args', 'data-wow-duration', '1s');
        $this->add_render_attribute('title_args', 'data-wow-delay', '500ms');

        ?>
        <!-- Start Working Process  -->
        <div class="axil-working-process-area ax-section-gap theme-gradient-4">
            <div class="container">

                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-title mb--100 mb_sm--40 mb_md--40 <?php echo esc_attr($settings['axil_process_two_align']); ?>">
                            <?php $this->axil_section_title_render('process_two', 'extra08-color', $this->get_settings()); ?>
                        </div>
                    </div>
                </div>

                <?php if($settings['process_two_list']){ ?>
                <div class="row">
                    <div class="col-lg-12">
                        <?php foreach ($settings['process_two_list'] as $index => $process){

                            $even = ($index % 2 !== 0) ? 'order-2 order-lg-1' : '';
                            $text_align = ($index % 2 !== 0) ? 'text-left' : '';

                            ?>
                            <!-- Start Working Process  -->
                            <div class="axil-working-process mb--100 mb_md--50 mb_sm--40 <?php echo esc_attr($text_align); ?> elementor-repeater-item-<?php echo $process['_id']; ?>">
                                <?php if (($index % 2 == 0) && !empty($process['process_two_image'])) { ?>
                                    <div class="thumbnail">
                                        <div class="image paralax-image">
                                            <?php echo Group_Control_Image_Size::get_attachment_image_html($process, 'full', 'process_two_image'); ?>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="content <?php echo esc_attr($even); ?>">
                                    <div class="inner">
                                        <div class="section-title">
                                            <?php if(!empty($process['process_two_number'])){ ?>
                                                <span class="process-step-number"><?php echo esc_html($process['process_two_number']); ?></span>
                                            <?php } ?>

                                            <?php if(!empty($process['process_two_before_title'])){ ?>
                                                <span class="sub-title extra04-color"><?php echo esc_html($process['process_two_before_title']); ?></span>
                                            <?php } ?>
                                            <?php if(!empty($process['process_two_title'])){ ?>
                                                <h2 class="title"><?php echo esc_html($process['process_two_title']); ?></h2>
                                            <?php } ?>
                                            <?php if(!empty($process['process_two_content'])){ ?>
                                                <p class="subtitle-2"><?php echo axil_kses_advance($process['process_two_content']); ?></p>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <?php if (($index % 2 !== 0) && !empty($process['process_two_image'])) { ?>
                                    <div class="thumbnail order-1 order-lg-2">
                                        <div class="image paralax-image">
                                            <?php echo Group_Control_Image_Size::get_attachment_image_html($process, 'full', 'process_two_image'); ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <!-- End Working Process  -->
                        <?php } ?>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
        <!-- End Working Process  -->
        <?php

    }

}

Plugin::instance()->widgets_manager->register_widget_type(new Keystroke_Elementor_Widget_Process_Box_Two());


