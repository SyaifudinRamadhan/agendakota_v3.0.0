<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class keystroke_Elementor_Widget_CaseStudy_Project extends Widget_Base {

    use \Elementor\KeystrokeElementCommonFunctions;

    public function get_name() {
        return 'keystroke-casestudy-project';
    }

    public function get_title() {
        return __( 'Case Study Project', 'keystroke' );
    }

    public function get_icon() {
        return 'axil-icon';
    }

    public function get_categories() {
        return [ 'keystroke' ];
    }

    public function get_keywords()
    {
        return ['case study project', 'project', 'keystroke'];
    }

    protected function _register_controls() {

        $this->axil_section_title('casestudy_project', 'case study', 'Selected projects', 'h2', '');
        $this->axil_query_controls('casestudy_project_query', 'Case Study', 'case-study', 'case-studies-cat');

        $this->start_controls_section(
            'casestudy_project_extra',
            [
                'label' => esc_html__('Others Options', 'keystroke'),
            ]
        );
        $this->axil_link_controls('casestudy_project_button', 'Discover More Projects Button', 'Discover More Projects');
        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'casestudy_image_size',
                'default' => 'axil-casestudy-project-thumb',
                'exclude' => ['custom'],
                'separator' => 'none',
            ]
        );

        $this->add_control(
            'casestudy_first_project_featured',
            [
                'label' => esc_html__('First Case Study Featured?', 'keystroke'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'keystroke'),
                'label_off' => esc_html__('No', 'keystroke'),
                'return_value' => 'yes',
            ]
        );
        $this->add_control(
            'casestudy_first_project_featured_brand',
            [
                'label' => esc_html__('Upload Featured Case Study Brand Image', 'keystroke'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'casestudy_first_project_featured' => 'yes'
                ]

            ]
        );

        $this->end_controls_section();

        $this->axil_basic_style_controls('casestudy_project_pre_title', 'Section - Tag Line', '.section-title span.sub-title');
        $this->axil_basic_style_controls('casestudy_project_title', 'Section - Title', '.section-title .title');
        $this->axil_basic_style_controls('casestudy_project_description', 'Section - Description', '.section-title p');

        $this->axil_basic_style_controls('casestudy_project_box_title', 'Project Title', '.axil-case-study .content .inner .title');
        $this->axil_basic_style_controls('casestudy_project_box_category', 'Project Category', '.axil-case-study .content .inner span.category');

        $this->axil_link_controls_style('casestudy_project_button_style', 'Discover More Button', '.axil-button', 'btn-size-md');
        $this->axil_section_style_controls('casestudy_project', 'Case Study Area', '.axil-case-study-area');

    }

    protected function render( $instance = [] ) {

        $settings = $this->get_settings_for_display();

        /**
         * Setup the post arguments.
         */
        $query_args = Axil_Helper::get_query_args('case-study', 'case-studies-cat', $this->get_settings());

        // The Query
        $query = new \WP_Query($query_args);

        ?>
        <!-- Start Case Study Area -->
        <div class="axil-case-study-area ax-section-gap bg-color-lightest">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-title text-left">
                            <?php $this->axil_section_title_render('casestudy_project','extra07-color', $this->get_settings()); ?>
                        </div>
                    </div>
                </div>
                <?php
                $i = 1;
                if ($query->have_posts()) { ?>
                    <div class="row row--45">

                        <?php
                        while ($query->have_posts()) {
                            $query->the_post();
                            global $post;
                            $terms = get_the_terms($post->ID, 'case-studies-cat');

                            $featured_class = '';
                            $col_class = 'col-lg-6 col-md-6 col-sm-6 col-12 move-up wow';
                            if($settings['casestudy_first_project_featured'] == 'yes'){
                                $featured_class = ($i == 1) ? 'with-mokup-images theme-gradient-5' : '';
                                $col_class = ($i == 1) ? 'col-lg-12 col-12 move-up wow' : 'col-lg-6 col-md-6 col-sm-6 col-12 move-up wow';
                            }

                            ?>
                            <!-- Start Single Case Study  -->
                            <div class="<?php echo esc_attr($col_class); ?>">
                                <div class="axil-case-study <?php echo esc_attr($featured_class); ?>">

                                    <?php if($settings['casestudy_first_project_featured'] == 'yes' && $i == 1){ ?>

                                    <?php } else { ?>
                                        <div class="thumbnail">
                                            <a href="<?php the_permalink(); ?>">
                                                <?php the_post_thumbnail($settings['casestudy_image_size_size'], ['class' => 'w-100 paralax-image', 'title' => get_the_title() ]); ?>
                                            </a>
                                        </div>
                                    <?php } ?>

                                    <div class="content">
                                        <div class="inner">
                                            <?php if ($terms && !is_wp_error($terms)): ?>
                                                <span class="category wow">
                                                        <?php foreach ($terms as $term) { ?>
                                                            <span><?php echo esc_html($term->name); ?></span>
                                                        <?php } ?>
                                                        </span>
                                            <?php endif ?>
                                            <h4 class="wow title" data-splitting><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                        </div>

                                        <?php
                                        if (!empty($settings['casestudy_first_project_featured_brand']) && $i == 1){ ?>
                                            <div class="project-featured-brand">
                                                <?php
                                                // Get image 'thumbnail' by ID
                                                echo wp_get_attachment_image( $settings['casestudy_first_project_featured_brand']['id'], 'full' );
                                                ?>

                                            </div>
                                        <?php } ?>
                                    </div>

                                    <?php if($settings['casestudy_first_project_featured'] == 'yes' && $i == 1){ ?>
                                        <div class="mockup">
                                            <?php the_post_thumbnail($settings['casestudy_image_size_size'], ['class' => 'paralax-image w-100', 'title' => get_the_title() ]); ?>
                                        </div>
                                    <?php } ?>

                                </div>
                            </div>
                            <!-- End Single Case Study  -->
                        <?php $i++; } ?>
                        <?php wp_reset_postdata(); ?>
                    </div>
                <?php } ?>

                <?php if (!empty($settings['axil_casestudy_project_button_text'])) { ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="case-study-btn text-center mt--60 move-up wow mt_md--30 mt_sm--30">
                                <?php

                                // Link
                                if ('2' == $settings['axil_casestudy_project_button_link_type']) {
                                    $this->add_render_attribute('axil_casestudy_project_button_link', 'href', get_permalink($settings['axil_casestudy_project_button_page_link']));
                                    $this->add_render_attribute('axil_casestudy_project_button_link', 'target', '_self');
                                    $this->add_render_attribute('axil_casestudy_project_button_link', 'rel', 'nofollow');
                                } else {
                                    if (!empty($settings['axil_casestudy_project_button_link']['url'])) {
                                        $this->add_render_attribute('axil_casestudy_project_button_link', 'href', $settings['axil_casestudy_project_button_link']['url']);
                                    }
                                    if ($settings['axil_casestudy_project_button_link']['is_external']) {
                                        $this->add_render_attribute('axil_casestudy_project_button_link', 'target', '_blank');
                                    }
                                    if (!empty($settings['axil_casestudy_project_button_link']['nofollow'])) {
                                        $this->add_render_attribute('axil_casestudy_project_button_link', 'rel', 'nofollow');
                                    }
                                }
                                $arrow = ($settings['axil_casestudy_project_button_style_button_arrow_icon'] == 'yes') ? '<span class="button-icon"></span>' : '';
                                // Button
                                if (!empty($settings['axil_casestudy_project_button_link']['url']) || isset($settings['axil_casestudy_project_button_link_type'])) {

                                    $this->add_render_attribute('axil_casestudy_project_button_link', 'class', ' axil-button wow slideFadeInUp ');
                                    // Style
                                    if (!empty($settings['axil_casestudy_project_button_style_button_style'])) {
                                        $this->add_render_attribute('axil_casestudy_project_button_link', 'class', '' . $settings['axil_casestudy_project_button_style_button_style'] . '');
                                    }
                                    // Size
                                    if (!empty($settings['axil_casestudy_project_button_style_button_size'])) {
                                        $this->add_render_attribute('axil_casestudy_project_button_link', 'class', $settings['axil_casestudy_project_button_style_button_size']);
                                    }
                                    // Link
                                    $button_html = '<a ' . $this->get_render_attribute_string('axil_casestudy_project_button_link') . '>' . '<span class="button-text">' . $settings['axil_casestudy_project_button_text'] . '</span>' . $arrow . '</a>';
                                }
                                if (!empty($settings['axil_casestudy_project_button_text'])) {
                                    echo $button_html;
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>

            </div>
        </div>
        <!-- End Case Study Area -->
        <?php
    }

}

Plugin::instance()->widgets_manager->register_widget_type( new keystroke_Elementor_Widget_CaseStudy_Project() );


