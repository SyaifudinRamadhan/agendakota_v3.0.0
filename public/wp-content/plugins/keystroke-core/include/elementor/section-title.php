<?php

namespace Elementor;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class Keystroke_Elementor_Widget_Section_Title extends Widget_Base
{

    use \Elementor\KeystrokeElementCommonFunctions;

    public function get_name()
    {
        return 'keystroke_section_title';
    }

    public function get_title()
    {
        return __('Section Title', 'keystroke');
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
        return ['section title', 'keystroke'];
    }

    protected function _register_controls()
    {

        $this->axil_section_title('section_title', 'Tag line', 'This is sample title', 'h2', 'In vel varius turpis, non dictum sem. Aenean in efficitur ipsum, in egestas ipsum. Mauris in mi ac tellus.', 'true', 'text-left');

        $this->axil_basic_style_controls('section_title_pre_title', 'Tag Line', '.section-title span.sub-title');
        $this->axil_basic_style_controls('section_title_title', 'Title', '.section-title .title');
        $this->axil_basic_style_controls('section_title_description', 'Description', '.section-title p');

        $this->axil_section_style_controls('section_title_area', 'Section Title  Area', '.axil-section-title-area.section-title');

    }

    protected function render($instance = [])
    {

        $settings = $this->get_settings_for_display();
        $this->add_render_attribute('title_args', 'class', 'title wow');
        $this->add_render_attribute('title_args', 'data-wow-duration', '1s');
        $this->add_render_attribute('title_args', 'data-wow-delay', '500ms');
        ?>
        <div class="axil-section-title-area section-title <?php echo esc_attr($settings['axil_section_title_align']); ?>">
            <?php $this->axil_section_title_render('section_title', 'extra04-color', $this->get_settings()); ?>
        </div>
        <?php
    }

}

Plugin::instance()->widgets_manager->register_widget_type(new Keystroke_Elementor_Widget_Section_Title());


