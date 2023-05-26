<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class keystroke_Elementor_Widget_Latest_Stories extends Widget_Base {

    use \Elementor\KeystrokeElementCommonFunctions;

    public function get_name() {
        return 'keystroke-latest_stories';
    }

    public function get_title() {
        return __( 'Latest Stories', 'keystroke' );
    }

    public function get_icon() {
        return 'axil-icon';
    }

    public function get_categories() {
        return [ 'keystroke' ];
    }

    public function get_keywords()
    {
        return ['blog', 'news', 'post', 'stories', 'keystroke'];
    }

    protected function _register_controls() {

        $this->axil_section_title('latest_stories', 'whats going on', 'Latest stories', 'h2');
        $this->axil_query_controls('latest_stories_query', 'Latest Stories');

        $this->start_controls_section(
            '_latest_stories_extra',
            [
                'label' => esc_html__('Extra Options', 'keystroke'),
            ]
        );
        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'latest_post_size',
                'default' => 'axil-latest-story-thumb',
                'exclude' => ['custom'],
                'separator' => 'none',
            ]
        );
        $this->add_control(
            'excerpt_limit',
            [
                'label' => esc_html__( 'Excerpt Limit', 'keystroke' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                        'step' => 1,
                    ]
                ],
                'default' => [
                    'size' => 100,
                ],
            ]
        );

        $this->end_controls_section();

        $this->axil_basic_style_controls('latest_stories_pre_title', 'Tag Line', '.section-title span.sub-title');
        $this->axil_basic_style_controls('latest_stories_title', 'Title', '.section-title .title');
        $this->axil_basic_style_controls('latest_stories_description', 'Description', '.section-title p');

        $this->axil_basic_style_controls('latest_psot_category', 'Post - Category', '.axil-blog .content .inner span.category');
        $this->axil_basic_style_controls('latest_psot_title', 'Post - Title', '.axil-blog .content .inner h5.title');
        $this->axil_basic_style_controls('latest_psot_description', 'Post - Description', '.axil-blog .content .inner p');

        $this->axil_section_style_controls('latest_post_area', 'Post Section', '.axil-blog-area');

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();
        /**
         * Setup the post arguments.
         */
        $query_args = Axil_Helper::get_query_args('post', 'category', $this->get_settings());

        // The Query
        $latest_post_query = new \WP_Query($query_args);

       $limit = ($settings['excerpt_limit']) ? $settings['excerpt_limit']['size'] : '';


        ?>

        <!-- Start Blog Area -->
        <div class="axil-blog-area ax-section-gap bg-color-lightest">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-title text-center">
                            <?php $this->axil_section_title_render('latest_stories','extra04-color', $this->get_settings()); ?>
                        </div>
                    </div>
                </div>
                <?php if ($latest_post_query->have_posts()) { ?>
                    <div class="row blog-list-wrapper mt--20">
                        <?php
                        $temp = 1;
                        while ($latest_post_query->have_posts()) {
                            $latest_post_query->the_post();

                            $active = ( $temp === 1 ) ? 'active' : '';
                            ?>
                            <!-- Start Blog Area -->
                            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="axil-blog axil-control mt--40 <?php echo esc_attr($active) ?>">
                                    <div class="content">
                                        <div class="content-wrap">
                                            <div class="inner">
                                                <?php
                                                $post_categories = get_the_category(get_the_ID());
                                                foreach ($post_categories as $category) { ?>
                                                    <span class="category"><?php echo esc_html($category->name); ?></span>
                                                <?php } ?>
                                                <h5 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                                                <?php if ($limit != 0){ ?>
                                                    <p><?php echo axil_limit_content_chr( get_the_excerpt(), $limit ); ?></p>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="thumbnail">
                                        <div class="image">
                                            <?php the_post_thumbnail($settings['latest_post_size_size']); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Blog Area -->
                        <?php  $temp++; } ?>
                        <?php wp_reset_postdata(); ?>
                    </div>
                <?php } ?>

            </div>
        </div>
        <!-- End Blog Area -->

        <?php


    }

}

Plugin::instance()->widgets_manager->register_widget_type( new keystroke_Elementor_Widget_Latest_Stories() );


