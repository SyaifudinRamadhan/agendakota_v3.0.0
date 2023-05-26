<?php

class ajax_requests
{

    protected $ajax_onoce;

    public function __construct()
    {
        $this->ajax_onoce = 'axil-feature-plugin';
        add_action('wp_enqueue_scripts', array($this, 'axil_ajax_enqueue'));

        /* Get All Portfolio Load More */
        add_action('wp_ajax_nopriv_axil_get_all_posts_content', array($this, 'axil_get_all_posts_content'));
        add_action('wp_ajax_axil_get_all_posts_content', array($this, 'axil_get_all_posts_content'));

    }

    function axil_ajax_enqueue()
    {
        wp_enqueue_script( 'keystroke-core-ajax', KEYSTROKE_ADDONS_URL . 'assets/js/ajax-scripts.js', array('jquery', 'imagesloaded'), null, true );
        $params = array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'ajax_nonce' => wp_create_nonce($this->ajax_onoce),
        );
        wp_localize_script('keystroke-core-ajax', 'axil_portfolio_ajax', $params);
    }

    /* Portfolio Load More */
    function axil_get_all_posts_content()
    {
        check_ajax_referer($this->ajax_onoce, 'security');

        if (isset($_POST) && !empty($_POST)) {

            $page = isset($_POST['paged']) ? (int) $_POST['paged'] : 1;
            $posts_per_page = (isset($_POST['settings']['posts_per_page']) && !empty($_POST['settings']['posts_per_page'])) ? $_POST['settings']['posts_per_page'] : "6";
            $orderby = (isset($_POST['query']['orderby']) && !empty($_POST['query']['orderby'])) ? $_POST['query']['orderby'] : "ID";
            $order = (isset($_POST['query']['order']) && !empty($_POST['query']['order'])) ? $_POST['query']['order'] : "ASC";

            $offset = $page * (int)$posts_per_page;
            $args = array(
                'post_type' => 'project',
                'posts_per_page' => (int) $posts_per_page,
                'orderby' => $orderby,
                'order' => $order,
                'offset' => $offset,
                'ignore_sticky_posts' => true,
            );

            $query = new WP_Query($args);



            $return = array();
            $found_posts = (int)$query->found_posts;
            $return['posts_count'] = $found_posts;

            if ($query->have_posts()) {
                ob_start();

                $taxonomy = 'project-cat';
                $termsArgs = array('taxonomy' => $taxonomy, 'hide_empty' => false);
                $categories = get_terms($termsArgs);

                if (!empty($categories) && !is_wp_error($categories)) {
                    $termID = 1;
                    foreach ($categories as $c => $cat) {
                        $mixitupcats[$cat->slug] = $cat->name;
                        $catsTerms[$cat->slug] = $cat->term_id;
                        $termID++;
                    }
                }
                while ($query->have_posts()) {
                    $query->the_post();

                    global $post;
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
                    ?>

                    <!-- Start Single Portfolio -->
                    <div class="portfolio portfolio_style--1 portfolio-33-33 cat--1 cat--2 <?php echo esc_attr($termsAssignedCat); ?>">
                        <div class="inner">
                            <div class="thumb">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('axil-project-thumb'); ?>
                                </a>
                            </div>
                            <div class="port-overlay-info">
                                <div class="hover-action">
                                    <h4 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a> </h4>
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

                }
            }
            $return['outputs'] .= ob_get_clean();
            echo json_encode($return);
            die();
        }

    }

}

new ajax_requests();
