<?php
namespace Elementor;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class keystroke_Elementor_Widget_Teams extends Widget_Base
{

    use \Elementor\KeystrokeElementCommonFunctions;

    public function get_name()
    {
        return 'keystroke-team';
    }

    public function get_title()
    {
        return __('Teams', 'keystroke');
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
        return ['team', 'our team', 'keystroke'];
    }

    protected function _register_controls()
    {

        $this->axil_section_title('teams', '', 'Select department:', 'h2', '', 'true', 'text-left');

        $this->axil_query_controls('team_query', 'Teams', 'team', 'team-cat');

        $this->start_controls_section(
            '_team_filter',
            [
                'label' => esc_html__('Extra Options', 'keystroke'),
            ]
        );
        $this->add_control(
            'team_filter_all_button_label',
            [
                'label' => esc_html__('All Button Label', 'keystroke'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('All', 'keystroke'),
                'placeholder' => esc_html__('Type all team button label here', 'keystroke'),
            ]
        );
        $this->end_controls_section();


        $this->axil_basic_style_controls('team_title_pre_title', 'Section - Tag Line', '.section-title span.sub-title');
        $this->axil_basic_style_controls('team_title_title', 'Section - Title', '.section-title .title');
        $this->axil_basic_style_controls('team_title_description', 'Section - Description', '.section-title p');

        $this->axil_basic_style_controls('team_title', 'Name', '.axil-team .inner .content .title');
        $this->axil_basic_style_controls('team_category', 'Designation', '.axil-team .inner .content p.subtitle');

        $this->start_controls_section(
            '_team_box',
            [
                'label' => esc_html__('Teams Box', 'keystroke'),
            ]
        );
        $this->add_control(
            'axil_team_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'keystroke' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .axil-team .inner .thumbnail::before' => 'border-color: {{VALUE}} !important;',
                ],
            ]
        );
        $this->end_controls_section();

        $this->axil_section_style_controls('team_area', 'Teams Area', '.axil-team-area');

    }

    protected function render($instance = [])
    {

        $settings = $this->get_settings_for_display();
        $this->add_render_attribute('title_args', 'class', 'title wow mb--0');

        /**
         * Setup the post arguments.
         */
        $query_args = Axil_Helper::get_query_args('team', 'team-cat', $this->get_settings());

        // The Query
        $team_query = new \WP_Query($query_args);

        ?>

        <!-- Start Portfolio Area -->
        <div class="axil-team-area ax-section-gap bg-color-lightest filter-area-<?php echo esc_attr($this->get_id()); ?>" data-unique-id="filter-area-<?php echo esc_attr($this->get_id()); ?>">
            <div class="container axil-masonary-wrapper">
                <div class="row align-items-end">
                    <div class="col-lg-5 col-md-12">
                        <div class="section-title">
                            <?php $this->axil_section_title_render('teams','extra07-color', $this->get_settings()); ?>
                        </div>
                    </div>

                    <?php

                    $category_list = '';
                    if (!empty($settings['category'])) {
                        $category_list = implode(" ", $settings['category']);
                    }
                    $category_list_value = explode(" ", $category_list);
                    ?>
                    <?php if ($category_list_value && !is_wp_error($category_list_value)): ?>
                        <div class="col-lg-7 col-md-12 mt_md--20 mt_sm--20">
                            <div class="messonry-button text-left text-lg-right">
                                <button data-filter="*" class="is-checked"><span
                                        class="filter-text"><?php echo esc_html($settings['team_filter_all_button_label']); ?></span>
                                </button>
                                <?php if (!empty($settings['category'])) {
                                    foreach ($category_list_value as $category) {
                                        $categoryName = get_term_by('slug', $category, 'team-cat');
                                        ?>
                                        <button data-filter=".<?php echo esc_attr($category); ?>"><span
                                                class="filter-text"><?php echo esc_html($categoryName->name); ?></span>
                                        </button>
                                    <?php }
                                } else {
                                    $terms = get_terms(array(
                                        'taxonomy' => 'team-cat',
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
                </div>
                <?php $i = 1; ?>
                <?php if ($team_query->have_posts()) { ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mesonry-list grid-metro4 mt--20">
                                <?php while ($team_query->have_posts()) {
                                    global $post;
                                    $team_query->the_post();
                                    $terms = get_the_terms($post->ID, 'team-cat');
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
                                    <div class="portfolio portfolio_style--1 axil-control portfolio-33-33 <?php echo esc_attr($active); ?> <?php echo esc_attr($termsAssignedCat); ?>">
                                        <div class="axil-team">
                                            <div class="inner">
                                                <div class="thumbnail paralax-image">
                                                    <a href="<?php the_permalink(); ?>">
                                                        <?php the_post_thumbnail('axil-team-thumb'); ?>
                                                    </a>
                                                </div>
                                                <div class="content">
                                                    <h4 class="title"><a
                                                            href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                                    </h4>
                                                    <?php if (!empty(get_field('team_designation'))): ?>
                                                        <p class="subtitle"> <?php echo esc_html(get_field('team_designation')); ?> </p>
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

                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
        <!-- End Portfolio Area -->
        <?php
    }
}

Plugin::instance()->widgets_manager->register_widget_type(new keystroke_Elementor_Widget_Teams());


