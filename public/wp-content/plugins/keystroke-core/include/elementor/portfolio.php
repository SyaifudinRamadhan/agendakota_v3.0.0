<?php
namespace Elementor;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class keystroke_Elementor_Widget_Project extends Widget_Base
{

    use \Elementor\KeystrokeElementCommonFunctions;

    public function get_name()
    {
        return 'keystroke-project';
    }

    public function get_title()
    {
        return __('Project', 'keystroke');
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
        return ['project', 'portfolio', 'keystroke'];
    }

    protected function _register_controls()
    {

        $this->axil_section_title('projects', 'our projects', 'Some of our finest work.', 'h2', '', 'true', 'text-left');

        $this->axil_query_controls('project_query', 'Project', 'project', 'project-cat');

        $this->start_controls_section(
            '_project_filter',
            [
                'label' => esc_html__('Extra Options', 'keystroke'),
            ]
        );
        $this->add_control(
            'project_filter',
            [
                'label'        => esc_html__('Filter ?', 'keystroke'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Yes', 'keystroke'),
                'label_off'    => esc_html__('No', 'keystroke'),
                'return_value' => 'yes',
                'separator'    => 'before',
                'default'      => 'yes'
            ]
        );
        $this->add_control(
            'project_filter_all_button_label',
            [
                'label' => esc_html__('All Button Label', 'keystroke'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('All', 'keystroke'),
                'placeholder' => esc_html__('Type all project button label here', 'keystroke'),
                'condition' => [
                    'project_filter' => 'yes'
                ]
            ]
        );
        $this->add_control(
            'project_load_more_button',
            [
                'label'        => esc_html__('Load More Button ?', 'keystroke'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Yes', 'keystroke'),
                'label_off'    => esc_html__('No', 'keystroke'),
                'return_value' => 'yes',
                'separator'    => 'before',
                'default'      => 'yes'
            ]
        );
        $this->add_control(
            'project_project_button_label',
            [
                'label' => esc_html__('More Projects Button Label', 'keystroke'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Discover More Projects', 'keystroke'),
                'placeholder' => esc_html__('Type Discover More Projects label here', 'keystroke'),
                'condition' => [
                    'project_load_more_button' => 'yes'
                ]
            ]
        );
        $this->end_controls_section();


        $this->axil_basic_style_controls('project_title_pre_title', 'Tag Line', '.section-title span.sub-title');
        $this->axil_basic_style_controls('project_title_title', 'Title', '.section-title .title');
        $this->axil_basic_style_controls('project_title_description', 'Description', '.section-title p');

        $this->axil_basic_style_controls('project_title', 'Project Title', '.portfolio .inner .port-overlay-info .hover-action .title');
        $this->axil_basic_style_controls('project_category', 'Project Category', '.portfolio .inner .port-overlay-info .hover-action span.category');

        $this->start_controls_section(
            '_project_box',
            [
                'label' => esc_html__('Project Box', 'keystroke'),
            ]
        );
        $this->add_control(
            'axil_project_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'keystroke' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .portfolio .inner .thumb::before' => 'border-color: {{VALUE}} !important;',
                ],
            ]
        );
        $this->end_controls_section();
        
        $this->axil_link_controls_style('project_button_style', 'Discover More Button', '.axil-button', 'btn-size-md');
        $this->axil_section_style_controls('project_area', 'Project Area', '.axil-portfolio-area');

    }

    protected function render($instance = [])
    {

        $settings = $this->get_settings_for_display();
        $this->add_render_attribute('title_args', 'class', 'title wow mb--0');

        /**
         * Setup the post arguments.
         */
        $query_args = Axil_Helper::get_query_args('project', 'project-cat', $this->get_settings());

        // The Query
        $project_query = new \WP_Query($query_args);
        $elemid = 'portfolio-' . rand(000000, 999999);

        $row_class = ($settings['project_filter'] == 'yes') ? "col-lg-5 col-md-12" : "col-12";

        ?>

        <!-- Start Portfolio Area -->
        <div class="axil-portfolio-area ax-section-gap bg-color-lightest filter-area-<?php echo esc_attr($this->get_id()); ?>" data-unique-id="filter-area-<?php echo esc_attr($this->get_id()); ?>">
            <div class="container axil-masonary-wrapper">
                <div class="row align-items-end">
                    <div class="<?php echo esc_attr($row_class); ?>">
                        <div class="section-title <?php echo esc_attr($settings['axil_projects_align']); ?>">
                            <?php $this->axil_section_title_render('projects','extra07-color', $this->get_settings()); ?>
                        </div>
                    </div>
                    <?php if($settings['project_filter'] == 'yes'){
                        $category_list = '';
                        if (!empty($settings['category'])) {
                            $category_list = implode(" ", $settings['category']);
                        }
                        $category_list_value = explode(" ", $category_list);
                        ?>
                        <?php if ($category_list_value && !is_wp_error($category_list_value)): ?>
                            <div class="col-lg-7 col-md-12 mt_md--20 mt_sm--20">
                                <div class="messonry-button text-left text-lg-right" data-isotope-id="#<?php echo $elemid ?>">
                                    <button data-filter="*" class="is-checked"><span
                                                class="filter-text"><?php echo esc_html($settings['project_filter_all_button_label']); ?></span>
                                    </button>
                                    <?php if (!empty($settings['category'])) {
                                        foreach ($category_list_value as $category) {
                                            $categoryName = get_term_by('slug', $category, 'project-cat');
                                            ?>
                                            <button data-filter=".<?php echo esc_attr($category); ?>"><span
                                                        class="filter-text"><?php echo esc_html($categoryName->name); ?></span>
                                            </button>
                                        <?php }
                                    } else {
                                        $terms = get_terms(array(
                                            'taxonomy' => 'project-cat',
                                            'hide_empty' => true,
                                        ));
                                        if ($terms && !is_wp_error($terms)) {
                                            foreach ($terms as $term) { ?>
                                            <button data-filter=".<?php echo esc_attr($term->slug); ?>"><span
                                                        class="filter-text"><?php echo esc_html($term->name); ?></span>
                                                </button><?php
                                            }
                                        }
                                    } ?>
                                </div>
                            </div>
                        <?php endif ?>
                    <?php } ?>
                </div>
                <?php $i = 1;
                ?>
                <?php if ($project_query->have_posts()) { ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <div  id="<?php echo esc_attr($elemid) ?>" class="mesonry-list grid-metro3 mt--20 portfolio-wrapper">
                                <?php while ($project_query->have_posts()) {
                                    global $post;
                                    $project_query->the_post();
                                    $terms = get_the_terms($post->ID, 'project-cat');
                                    if ($terms && !is_wp_error($terms)) {
                                        $termsList = array();
                                        foreach ($terms as $category) {
                                            $termsList[] = $category->slug;
                                        }
                                        $termsAssignedCat = join(" ", $termsList);
                                    } else {
                                        $termsAssignedCat = '';
                                    }

                                    $active = ($i == 1) ? 'active' : '';
                                    ?>
                                    <!-- Start Single Portfolio -->
                                    <div class="portfolio portfolio_style--1 portfolio-33-33 cat--1 cat--2 <?php echo esc_attr($active); ?> <?php echo esc_attr($termsAssignedCat); ?>">
                                        <div class="inner">
                                            <div class="thumb">
                                                <a href="<?php the_permalink(); ?>">
                                                    <?php the_post_thumbnail('axil-project-thumb'); ?>
                                                </a>
                                            </div>
                                            <div class="port-overlay-info">
                                                <div class="hover-action">

                                                    <h4 class="title"><a
                                                                href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                                    </h4>

                                                    <?php if ($terms && !is_wp_error($terms)): ?>
                                                        <span class="category">
                                                        <?php foreach ($terms as $term) { ?>
                                                            <span><?php echo esc_html($term->name); ?></span>
                                                        <?php } ?>
                                                        </span>
                                                    <?php endif ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Single Portfolio -->
                                <?php
                                    $i++;
                                } ?>
                                <?php wp_reset_postdata(); ?>
                            </div>

                            <?php if($settings['project_load_more_button'] == 'yes'){ ?>
                                <div class="view-all-portfolio-button text-center">
                                    <?php $arrow = ($settings['axil_project_button_style_button_arrow_icon'] == 'yes') ? '<span class="button-icon"></span>' : '';
                                    if ($settings['project_project_button_label']) {
                                        $postCount = $project_query->found_posts;
                                        if($postCount>$settings['posts_per_page']) { ?>
                                            <a href="javascript:void(0)"
                                               data-query="<?php echo esc_attr(json_encode($query_args)); ?>"
                                               data-actions="axil_get_all_posts"
                                               data-settings="<?php echo esc_attr(json_encode($settings)); ?>"
                                               data-paged="1"
                                               data-post-count="<?php echo esc_attr($settings['posts_per_page']); ?>"
                                               data-target="#<?php echo esc_attr($elemid) ?>"
                                               class="mt--60 mt_sm--30 mt_md--30 axil-button wow slideFadeInUp load-more <?php echo esc_attr($settings['axil_project_button_style_button_style']); ?> <?php echo esc_attr($settings['axil_project_button_style_button_size']); ?>"><span class="button-text"><?php echo esc_html($settings['project_project_button_label']); ?></span><?php echo wp_kses_post($arrow) ?> </a>
                                            <?php
                                        }
                                    } ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
        <!-- End Portfolio Area -->
        <?php
    }
}

Plugin::instance()->widgets_manager->register_widget_type(new keystroke_Elementor_Widget_Project());


