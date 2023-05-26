<?php

namespace Elementor;

use Elementor\Group_Control_Base;
use Elementor\REPEA;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

function axil_elementor_init()
{

    /**
     * Initialize EAE_Helper
     */
    new axil_Helper;

}

add_action('elementor/init', 'Elementor\axil_elementor_init');
/**
 * Get All Post Types
 */
function axil_get_post_types()
{

    $axil_cpts = get_post_types(array('public' => true, 'show_in_nav_menus' => true), 'object');
    $axil_exclude_cpts = array('elementor_library', 'attachment');
    foreach ($axil_exclude_cpts as $exclude_cpt) {
        unset($axil_cpts[$exclude_cpt]);
    }
    $post_types = array_merge($axil_cpts);
    foreach ($post_types as $type) {
        $types[$type->name] = $type->label;
    }
    return $types;
}

/**
 * Get all types of post.
 */
function axil_get_all_types_post($post_type)
{

    $posts_args = get_posts(array(
        'post_type' => $post_type,
        'orderby' => 'date',
        'order' => 'DESC',
        'post_status' => 'publish',
        'posts_per_page' => -1,
    ));

    $posts = array();

    if (!empty($posts_args) && !is_wp_error($posts_args)) {
        foreach ($posts_args as $post) {
            $posts[$post->ID] = $post->post_title;
        }
    }

    return $posts;
}

/**
 * Get all Pages
 */
if (!function_exists('axil_get_all_pages')) {
    function axil_get_all_pages()
    {

        $page_list = get_posts(array(
            'post_type' => 'page',
            'orderby' => 'date',
            'order' => 'DESC',
            'posts_per_page' => -1,
        ));

        $pages = array();

        if (!empty($page_list) && !is_wp_error($page_list)) {
            foreach ($page_list as $page) {
                $pages[$page->ID] = $page->post_title;
            }
        }

        return $pages;
    }
}

/**
 * Post Settings Parameter
 */
function axil_get_post_settings($settings)
{
    foreach ($settings as $key => $value) {
        $post_args[$key] = $value;
    }
    $post_args['post_status'] = 'publish';

    return $post_args;
}

/**
 * Get Post Thumbnail Size
 */
function axil_get_thumbnail_sizes()
{
    $sizes = get_intermediate_image_sizes();
    foreach ($sizes as $s) {
        $ret[$s] = $s;
    }
    return $ret;
}

/**
 * Post Orderby Options
 */
function axil_get_orderby_options()
{
    $orderby = array(
        'ID' => 'Post ID',
        'author' => 'Post Author',
        'title' => 'Title',
        'date' => 'Date',
        'modified' => 'Last Modified Date',
        'parent' => 'Parent Id',
        'rand' => 'Random',
        'comment_count' => 'Comment Count',
        'menu_order' => 'Menu Order',
    );
    return $orderby;
}

/**
 * Get Post Categories
 */
function axil_get_categories($taxonomy)
{
    $terms = get_terms(array(
        'taxonomy' => $taxonomy,
        'hide_empty' => true,
    ));
    $options = array();
    if (!empty($terms) && !is_wp_error($terms)) {
        foreach ($terms as $term) {
            $options[$term->slug] = $term->name;
        }
    }
    return $options;
}

/**
 * Get all Pages
 */
if (!function_exists('axil_get_pages')) {
    function axil_get_pages()
    {

        $page_list = get_posts(array(
            'post_type' => 'page',
            'orderby' => 'date',
            'order' => 'DESC',
            'posts_per_page' => -1,
        ));

        $pages = array();

        if (!empty($page_list) && !is_wp_error($page_list)) {
            foreach ($page_list as $page) {
                $pages[$page->ID] = $page->post_title;
            }
        }

        return $pages;
    }
}


/**
 * Get a list of all the allowed html tags.
 *
 * @param string $level Allowed levels are basic and intermediate
 * @return array
 */
function axil_get_allowed_html_tags($level = 'basic')
{
    $allowed_html = [
        'b' => [],
        'i' => [],
        'u' => [],
        'em' => [],
        'br' => [],
        'abbr' => [
            'title' => [],
        ],
        'span' => [
            'class' => [],
        ],
        'strong' => [],
    ];

    if ($level === 'intermediate') {
        $allowed_html['a'] = [
            'href' => [],
            'title' => [],
            'class' => [],
            'id' => [],
        ];
    }

    if ($level === 'advance') {
        $allowed_html['ul'] = [
            'class' => [],
            'id' => [],
        ];
        $allowed_html['ol'] = [
            'class' => [],
            'id' => [],
        ];
        $allowed_html['li'] = [
            'class' => [],
            'id' => [],
        ];
        $allowed_html['a'] = [
            'href' => [],
            'title' => [],
            'class' => [],
            'id' => [],
            'target' => [],
        ];

    }

    return $allowed_html;
}

/**
 * Strip all the tags except allowed html tags
 *
 * The name is based on inline editing toolbar name
 *
 * @param string $string
 * @return string
 */
function axil_kses_advance($string = '')
{
    return wp_kses($string, axil_get_allowed_html_tags('advance'));
}

/**
 * Strip all the tags except allowed html tags
 *
 * The name is based on inline editing toolbar name
 *
 * @param string $string
 * @return string
 */
function axil_kses_intermediate($string = '')
{
    return wp_kses($string, axil_get_allowed_html_tags('intermediate'));
}

/**
 * Strip all the tags except allowed html tags
 *
 * The name is based on inline editing toolbar name
 *
 * @param string $string
 * @return string
 */
function axil_kses_basic($string = '')
{
    return wp_kses($string, axil_get_allowed_html_tags('basic'));
}

/**
 * Get a translatable string with allowed html tags.
 *
 * @param string $level Allowed levels are basic and intermediate
 * @return string
 */
function axil_get_allowed_html_desc($level = 'basic')
{
    if (!in_array($level, ['basic', 'intermediate'])) {
        $level = 'basic';
    }

    $tags_str = '<' . implode('>,<', array_keys(axil_get_allowed_html_tags($level))) . '>';
    return sprintf(__('This input field has support for the following HTML tags: %1$s', 'keystroke'), '<code>' . esc_html($tags_str) . '</code>');
}

/**
 * Element Common Functions
 */
trait KeystrokeElementCommonFunctions
{

    /**
     * Create section title fields
     *
     * @param null $control_id
     * @param string $before_title
     * @param string $title
     * @param string $default_title_tag
     * @param string $description
     */
    protected function axil_section_title($control_id = null, $before_title = 'Tag Line', $title = 'Your Section Title', $default_title_tag = 'h2', $description = 'In vel varius turpis, non dictum sem. Aenean in efficitur ipsum, in egestas ipsum. Mauris in mi ac tellus.', $align_field = false, $align = 'text-center')
    {
        $this->start_controls_section(
            'axil_' . $control_id . '_section_title',
            [
                'label' => esc_html__('Section Title', 'keystroke'),
            ]
        );
        $this->add_control(
            'axil_' . $control_id . '_before_title',
            [
                'label' => esc_html__('Tag Line', 'keystroke'),
                'type' => Controls_Manager::TEXT,
                'default' => $before_title,
                'placeholder' => esc_html__('Type Before Heading Text', 'keystroke'),
                'label_block' => true,
            ]
        );
        $this->add_control(
            'axil_' . $control_id . '_title',
            [
                'label' => esc_html__('Title', 'keystroke'),
                'type' => Controls_Manager::TEXT,
                'default' => $title,
                'placeholder' => esc_html__('Type Heading Text', 'keystroke'),
                'label_block' => true,
            ]
        );
        $this->add_control(
            'axil_' . $control_id . '_title_tag',
            [
                'label' => esc_html__('Title HTML Tag', 'keystroke'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'h1' => [
                        'title' => esc_html__('H1', 'keystroke'),
                        'icon' => 'eicon-editor-h1'
                    ],
                    'h2' => [
                        'title' => esc_html__('H2', 'keystroke'),
                        'icon' => 'eicon-editor-h2'
                    ],
                    'h3' => [
                        'title' => esc_html__('H3', 'keystroke'),
                        'icon' => 'eicon-editor-h3'
                    ],
                    'h4' => [
                        'title' => esc_html__('H4', 'keystroke'),
                        'icon' => 'eicon-editor-h4'
                    ],
                    'h5' => [
                        'title' => esc_html__('H5', 'keystroke'),
                        'icon' => 'eicon-editor-h5'
                    ],
                    'h6' => [
                        'title' => esc_html__('H6', 'keystroke'),
                        'icon' => 'eicon-editor-h6'
                    ]
                ],
                'default' => $default_title_tag,
                'toggle' => false,
            ]
        );

        $this->add_control(
            'axil_' . $control_id . '_desctiption',
            [
                'label' => esc_html__('Description', 'keystroke'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => $description,
                'placeholder' => esc_html__('Type section description here', 'keystroke'),
            ]
        );
        if($align_field == true){
            $this->add_responsive_control(
                'axil_' . $control_id . '_align',
                [
                    'label' => esc_html__( 'Alignment', 'keystroke' ),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'text-left' => [
                            'title' => esc_html__( 'Left', 'keystroke' ),
                            'icon' => 'fa fa-align-left',
                        ],
                        'text-center' => [
                            'title' => esc_html__( 'Center', 'keystroke' ),
                            'icon' => 'fa fa-align-center',
                        ],
                        'text-right' => [
                            'title' => esc_html__( 'Right', 'keystroke' ),
                            'icon' => 'fa fa-align-right',
                        ],
                    ],
                    'default' => $align,
                    'toggle' => true,
                ]
            );
        }
        $this->end_controls_section();
    }

    /**
     * Render Section Title
     *
     * @param null $control_id
     * @param $settings
     */
    protected function axil_section_title_render($control_id = null, $before_title_class = 'extra05-color', $settings)
    {
        $this->add_render_attribute('title_args', 'class', 'title');
        ?>
            <?php if (!empty($settings['axil_'.$control_id.'_before_title'])) { ?>
                <span class="sub-title <?php echo esc_attr($before_title_class);?>" ><?php echo axil_kses_intermediate( $settings['axil_'.$control_id.'_before_title'] ); ?></span>
            <?php } ?>
            <?php if ($settings['axil_'.$control_id.'_title_tag']) : ?>
                <<?php echo tag_escape($settings['axil_'.$control_id.'_title_tag']); ?> <?php echo $this->get_render_attribute_string('title_args'); ?>><?php echo axil_kses_basic($settings['axil_'.$control_id.'_title']); ?></<?php echo tag_escape($settings['axil_'.$control_id.'_title_tag']) ?>>
            <?php endif; ?>

            <?php if (!empty($settings['axil_'.$control_id.'_desctiption'])) { ?>
                <p class="subtitle-2 "><?php echo axil_kses_intermediate( $settings['axil_'.$control_id.'_desctiption'] ); ?></p>
            <?php } ?>
        <?php
    }

    /**
     * [axil_query_controls description]
     * @param  [type] $control_id     [description]
     * @param  [type] $control_name   [description]
     * @param string $post_type [description]
     * @param string $taxonomy [description]
     * @param string $posts_per_page [description]
     * @param string $offset [description]
     * @param string $orderby [description]
     * @param string $order [description]
     * @return [type]                 [description]
     */
    protected function axil_query_controls($control_id = null, $control_name = null, $post_type = 'any', $taxonomy = 'category', $posts_per_page = '6', $offset = '0', $orderby = 'date', $order = 'desc')
    {

        $this->start_controls_section(
            'keystroke' . $control_id . '_query',
            [
                'label' => sprintf(esc_html__('%s Query', 'keystroke'), $control_name),
            ]
        );

        $this->add_control(
            'category',
            [
                'label' => esc_html__('Category', 'keystroke'),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => axil_get_categories($taxonomy),
                'label_block' => true
            ]
        );
        $this->add_control(
            'post__not_in',
            [
                'label' => esc_html__('Exclude', 'keystroke'),
                'type' => Controls_Manager::SELECT2,
                'options' => axil_get_all_types_post($post_type),
                'multiple' => true,
                'label_block' => true
            ]
        );
        $this->add_control(
            'posts_per_page',
            [
                'label' => esc_html__('Posts Per Page', 'keystroke'),
                'type' => Controls_Manager::NUMBER,
                'default' => $posts_per_page,
            ]
        );
        $this->add_control(
            'offset',
            [
                'label' => esc_html__('Offset', 'keystroke'),
                'type' => Controls_Manager::NUMBER,
                'default' => $offset,
            ]
        );
        $this->add_control(
            'orderby',
            [
                'label' => esc_html__('Order By', 'keystroke'),
                'type' => Controls_Manager::SELECT,
                'options' => axil_get_orderby_options(),
                'default' => $orderby,

            ]
        );
        $this->add_control(
            'order',
            [
                'label' => esc_html__('Order', 'keystroke'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'asc' => 'Ascending',
                    'desc' => 'Descending'
                ],
                'default' => $order,

            ]
        );
        $this->end_controls_section();

    }

    /**
     * [axil_basic_style_controls description]
     * @param  [string] $control_id       [description]
     * @param  [string] $control_name     [description]
     * @param  [string] $control_selector [description]
     * @return [styleing control]                   [ color, typography, padding, margin ]
     */
    protected function axil_basic_style_controls($control_id = null, $control_name = null, $control_selector = null)
    {
        $this->start_controls_section(
            'axil_' . $control_id . '_styling',
            [
                'label' => esc_html__($control_name, 'keystroke'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'axil_' . $control_id . '_color',
            [
                'label' => esc_html__('Color', 'keystroke'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ' . $control_selector => 'color: {{VALUE}} !important;',
                ],
            ]
        );
        $this->add_control(
            'axil_' . $control_id . '_bg_color',
            [
                'label' => esc_html__('Background Color', 'keystroke'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ' . $control_selector . '::before'  => 'background: {{VALUE}} !important;',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'axil_' . $control_id . '_typography',
                'label' => esc_html__('Typography', 'keystroke'),
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} ' . $control_selector,
            ]
        );
        $this->add_responsive_control(
            'axil_' . $control_id . '_padding',
            [
                'label' => esc_html__('Padding', 'keystroke'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} ' . $control_selector => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control(
            'axil_' . $control_id . '_margin',
            [
                'label' => esc_html__('Margin', 'keystroke'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} ' . $control_selector => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );
        $this->end_controls_section();
    }

    /**
     * [axil_section_style_controls description]
     * @param  [type] $control_id       [description]
     * @param  [type] $control_name     [description]
     * @param  [type] $control_selector [description]
     * @return [type]                   [description]
     */
    protected function axil_section_style_controls($control_id = null, $control_name = null, $control_selector = null)
    {
        $this->start_controls_section(
            'axil_' . $control_id . '_area_styling',
            [
                'label' => esc_html__($control_name, 'keystroke'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'axil_' . $control_id . 'area_background',
                'label' => esc_html__('Background', 'keystroke'),
                'types' => ['classic', 'gradient', 'video'],
                'selector' => '{{WRAPPER}} ' . $control_selector,
            ]
        );
        $this->add_responsive_control(
            'axil_' . $control_id . '_area_padding',
            [
                'label' => esc_html__('Padding', 'keystroke'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} ' . $control_selector => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control(
            'axil_' . $control_id . '_area_margin',
            [
                'label' => esc_html__('Margin', 'keystroke'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} ' . $control_selector => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );
        $this->end_controls_section();
    }

    /**
     * [axil_link_controls description]
     * @param string $control_id [description]
     * @param string $control_name [description]
     * @return [type]               [description]
     */
    protected function axil_link_controls($control_id = 'button', $control_name = 'Button', $default = 'Read More')
    {

        $this->add_control(
            'axil_' . $control_id . '_text',
            [
                'label' => esc_html__($control_name . ' Text', 'keystroke'),
                'type' => Controls_Manager::TEXT,
                'default' => $default,
                'title' => esc_html__('Enter button text', 'keystroke'),
                'label_block' => true
            ]
        );
        $this->add_control(
            'axil_' . $control_id . '_link_type',
            [
                'label' => esc_html__($control_name . ' Link Type', 'keystroke'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '1' => 'Custom Link',
                    '2' => 'Internal Page',
                ],
                'default' => '1',
                'label_block' => true
            ]
        );
        $this->add_control(
            'axil_' . $control_id . '_link',
            [
                'label' => esc_html__($control_name . ' link', 'keystroke'),
                'type' => Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => esc_html__('https://your-link.com', 'keystroke'),
                'show_external' => true,
                'default' => [
                    'url' => '#',
                    'is_external' => false,
                    'nofollow' => false,
                ],
                'condition' => [
                    'axil_' . $control_id . '_link_type' => '1'
                ],
                'label_block' => true
            ]
        );
        $this->add_control(
            'axil_' . $control_id . '_page_link',
            [
                'label' => esc_html__('Select ' . $control_name . ' Page', 'keystroke'),
                'type' => Controls_Manager::SELECT2,
                'label_block' => true,
                'options' => axil_get_all_pages(),
                'condition' => [
                    'axil_' . $control_id . '_link_type' => '2'
                ]
            ]
        );

    }

    /**
     * [axil_link_controls_style description]
     * @param string $control_id [description]
     * @param string $control_selector [description]
     * @return [type]                   [description]
     */
    protected function axil_link_controls_style($control_id = 'button_style', $control_name = 'Button', $control_selector = 'a', $default_size = 'btn-large', $default_style = 'btn-solid')
    {
        /**
         * Button One
         */
        $this->start_controls_section(
            'axil_' . $control_id . '_button',
            [
                'label' => esc_html__($control_name, 'keystroke'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'axil_' . $control_id . '_button_style',
            [
                'label' => esc_html__('Button Style', 'keystroke'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'btn-transparent' => esc_html__('Outline', 'keystroke'),
                    'btn-solid' => esc_html__('Solid', 'keystroke'),
                    'axil-link-button' => esc_html__('Link', 'keystroke'),
                ],
                'default' => $default_style
            ]
        );

        $this->add_control(
            'axil_' . $control_id . '_button_size',
            [
                'label' => esc_html__('Button Size', 'keystroke'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'btn-extra-large' => esc_html__('Extra Large', 'keystroke'),
                    'btn-large' => esc_html__('Large', 'keystroke'),
                    'btn-medium' => esc_html__('Medium', 'keystroke'),
                    'btn-small' => esc_html__('Small', 'keystroke'),
                ],
                'default' => $default_size,
                'condition' => array(
                    'axil_' . $control_id . '_button_style!' => 'axil-link-button',
                ),
            ]
        );

        $this->add_control(
            'axil_' . $control_id . '_button_arrow_icon',
            [
                'label' => esc_html__('Arrow Icon', 'keystroke'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'keystroke'),
                'label_off' => esc_html__('Hide', 'keystroke'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => array(
                        'axil_' . $control_id . '_button_style!' => 'axil-link-button',
                ),
            ]
        );
        $this->add_responsive_control(
            'axil_' . $control_id . '_margin',
            [
                'label' => esc_html__('Margin', 'keystroke'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} ' . $control_selector . '' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'axil_' . $control_id . '_typography',
                'selector' => '{{WRAPPER}} ' . $control_selector . '',
            ]
        );


        $this->start_controls_tabs('axil_' . $control_id . '_button_tabs');

        // Normal State Tab
        $this->start_controls_tab('axil_' . $control_id . '_btn_normal', ['label' => esc_html__('Normal', 'keystroke')]);

        $this->add_control(
            'axil_' . $control_id . '_btn_normal_text_color',
            [
                'label' => esc_html__('Text Color', 'keystroke'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ' . $control_selector . '' => 'color: {{VALUE}};',
                    '{{WRAPPER}} ' . $control_selector . ' .button-icon' => 'border-left-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'axil_' . $control_id . '_btn_normal_bg_color',
            [
                'label' => esc_html__('Background Color', 'keystroke'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ' . $control_selector . '::before' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'axil_' . $control_id . '_btn_normal_border_color',
            [
                'label' => esc_html__('Border Color', 'keystroke'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ' . $control_selector . '::after' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} ' . $control_selector . '::before' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} ' . $control_selector . '.axil-link-button::after' => 'background: {{VALUE}};',
                ],
            ]

        );

        $this->add_control(
            'axil_' . $control_id . '_btn_border_radius',
            [
                'label' => esc_html__('Border Radius', 'keystroke'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $control_selector . '' => 'border-radius: {{SIZE}}px;',
                    '{{WRAPPER}} ' . $control_selector . '::after' => 'border-radius: {{SIZE}}px;',
                    '{{WRAPPER}} ' . $control_selector . '::before' => 'border-radius: {{SIZE}}px;',
                ],
            ]
        );

        $this->end_controls_tab();

        // Hover State Tab
        $this->start_controls_tab('axil_' . $control_id . '_btn_hover', ['label' => esc_html__('Hover', 'keystroke')]);

        $this->add_control(
            'axil_' . $control_id . '_btn_hover_text_color',
            [
                'label' => esc_html__('Text Color', 'keystroke'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ' . $control_selector . ':hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} ' . $control_selector . ':hover .button-icon' => 'border-left-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'axil_' . $control_id . '_btn_hover_bg_color',
            [
                'label' => esc_html__('Background Color', 'keystroke'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ' . $control_selector . ':hover' => 'background: {{VALUE}};',
                    '{{WRAPPER}} ' . $control_selector . '.btn-transparent:after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'axil_' . $control_id . '_btn_hover_border_color',
            [
                'label' => esc_html__('Border Color', 'keystroke'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ' . $control_selector . ':hover::after' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} ' . $control_selector . '.axil-link-button:hover::after' => 'background: {{VALUE}};',
                ],
            ]

        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }


    /**
     * @param string $control_id
     * @param string $control_name
     * @param string $default_for_lg
     * @param string $default_for_md
     * @param string $default_for_sm
     * @param string $default_for_all
     */
    protected function axil_columns($control_id = 'columns_options', $control_name = 'Select Columns', $default_for_lg = '4', $default_for_md = '6', $default_for_sm = '6', $default_for_all = '12'){
        $this->start_controls_section(
            'axil_' . $control_id . 'columns_section',
            [
                'label' => esc_html__($control_name, 'keystroke'),
            ]
        );

        $this->add_control(
            'axil_' . $control_id . '_for_desktop',
            [
                'label' => esc_html__( 'Columns for Desktop', 'keystroke' ),
                'description' => esc_html__( 'Screen width equal to or greater than 992px', 'keystroke' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    12 => esc_html__( '1 Columns', 'keystroke' ),
                    6 => esc_html__( '2 Columns', 'keystroke' ),
                    4 => esc_html__( '3 Columns', 'keystroke' ),
                    3 => esc_html__( '4 Columns', 'keystroke' ),
                    5 => esc_html__( '5 Columns (col-5)', 'keystroke' ),
                    2 => esc_html__( '6 Columns', 'keystroke' ),
                    1 => esc_html__( '12 Columns', 'keystroke' ),
                ],
                'separator' => 'before',
                'default' => $default_for_lg,
                'style_transfer' => true,
            ]
        );
        $this->add_control(
            'axil_' . $control_id . '_for_laptop',
            [
                'label' => esc_html__( 'Columns for Laptop', 'keystroke' ),
                'description' => esc_html__( 'Screen width equal to or greater than 768px', 'keystroke' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    12 => esc_html__( '1 Columns', 'keystroke' ),
                    6 => esc_html__( '2 Columns', 'keystroke' ),
                    4 => esc_html__( '3 Columns', 'keystroke' ),
                    3 => esc_html__( '4 Columns', 'keystroke' ),
                    5 => esc_html__( '5 Columns (col-5)', 'keystroke' ),
                    2 => esc_html__( '6 Columns', 'keystroke' ),
                    1 => esc_html__( '12 Columns', 'keystroke' ),
                ],
                'separator' => 'before',
                'default' => $default_for_md,
                'style_transfer' => true,
            ]
        );
        $this->add_control(
            'axil_' . $control_id . '_for_tablet',
            [
                'label' => esc_html__( 'Columns for Tablet', 'keystroke' ),
                'description' => esc_html__( 'Screen width equal to or greater than 576px', 'keystroke' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    12 => esc_html__( '1 Columns', 'keystroke' ),
                    6 => esc_html__( '2 Columns', 'keystroke' ),
                    4 => esc_html__( '3 Columns', 'keystroke' ),
                    3 => esc_html__( '4 Columns', 'keystroke' ),
                    5 => esc_html__( '5 Columns (col-5)', 'keystroke' ),
                    2 => esc_html__( '6 Columns', 'keystroke' ),
                    1 => esc_html__( '12 Columns', 'keystroke' ),
                ],
                'separator' => 'before',
                'default' => $default_for_sm,
                'style_transfer' => true,
            ]
        );
        $this->add_control(
            'axil_' . $control_id . '_for_mobile',
            [
                'label' => esc_html__( 'Columns for Mobile', 'keystroke' ),
                'description' => esc_html__( 'Screen width less than 576px', 'keystroke' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    12 => esc_html__( '1 Columns', 'keystroke' ),
                    6 => esc_html__( '2 Columns', 'keystroke' ),
                    4 => esc_html__( '3 Columns', 'keystroke' ),
                    3 => esc_html__( '4 Columns', 'keystroke' ),
                    5 => esc_html__( '5 Columns (col-5)', 'keystroke' ),
                    2 => esc_html__( '6 Columns', 'keystroke' ),
                    1 => esc_html__( '12 Columns', 'keystroke' ),
                ],
                'separator' => 'before',
                'default' => $default_for_all,
                'style_transfer' => true,
            ]
        );

        $this->end_controls_section();
    }

}

/**
 * axil_Helper
 */
class Axil_Helper
{


    public static function get_query_args($posttype, $taxonomy, $settings)
    {

        if (get_query_var('paged')) {
            $paged = get_query_var('paged');
        } else if (get_query_var('page')) {
            $paged = get_query_var('page');
        } else {
            $paged = 1;
        }

        $category_list = '';
        if (!empty($settings['category'])) {
            $category_list = implode(", ", $settings['category']);
        }
        $category_list_value = explode(" ", $category_list);
        $post__not_in = '';
        if (!empty($settings['post__not_in'])) {
            $post__not_in = $settings['post__not_in'];
            $args['post__not_in'] = $post__not_in;
        }
        $posts_per_page = (!empty($settings['posts_per_page'])) ? $settings['posts_per_page'] : '-1';
        $orderby = (!empty($settings['orderby'])) ? $settings['orderby'] : 'post_date';
        $order = (!empty($settings['order'])) ? $settings['order'] : 'desc';
        $offset_value = (!empty($settings['offset'])) ? $settings['offset'] : '0';


        // number
        $off = (!empty($offset_value)) ? $offset_value : 0;
        $offset = $off + (($paged - 1) * $posts_per_page);
        $p_ids = array();

        // build up the array
        if (!empty($settings['post__not_in'])) {
            foreach ($settings['post__not_in'] as $p_idsn) {
                $p_ids[] = $p_idsn;
            }
        }

        $args = array(
            'post_type' => $posttype,
            'post_status' => 'publish',
            'posts_per_page' => $posts_per_page,
            'orderby' => $orderby,
            'order' => $order,
            'offset' => $offset,
            'paged' => $paged,
            'post__not_in' => $p_ids,
            'ignore_sticky_posts' => 1
        );
        if (!empty($settings['category'])) {
            $args['tax_query'][] = [
                'taxonomy' => $taxonomy,
                'field' => 'slug',
                'terms' => $category_list_value,
            ];
        }

        return $args;
    }

}


/**
 * @param $content
 * @param string $limit
 * @return string
 */
function axil_limit_content_chr( $content, $limit = '100' ) {
    return mb_strimwidth( strip_tags($content), 0, $limit, '...' );
}
