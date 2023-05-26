<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class keystroke_Elementor_Widget_PricingPlan extends Widget_Base {

    use \Elementor\KeystrokeElementCommonFunctions;

    public function get_name() {
        return 'keystroke-pricing-plan';
    }

    public function get_title() {
        return __( 'Pricing Plan', 'keystroke' );
    }

    public function get_icon() {
        return 'axil-icon';
    }

    public function get_categories() {
        return [ 'keystroke' ];
    }

    public function get_keywords()
    {
        return ['pricing', 'pricing plan', 'pricing table', 'price', 'keystroke'];
    }

    protected function _register_controls() {

        $this->axil_section_title('pricing_plan', 'pricing plan', 'From getting started', 'h2', 'In vel varius turpis, non dictum sem. Aenean in efficitur ipsum, in egestas ipsum. Mauris in mi ac tellus.');

        $this->start_controls_section(
            'keystroke_pricing_plan',
            [
                'label' => esc_html__( 'Pricing Plan', 'keystroke' ),
            ]
        );

        $repeater = new \Elementor\Repeater();
        $repeater->add_control(
            'pricing_title', [
                'label' => esc_html__( 'Title', 'keystroke' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Small Business' , 'keystroke' ),
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'pricing_sub_title', [
                'label' => esc_html__( 'Sub Title', 'keystroke' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'A beautiful, simple website' , 'keystroke' ),
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'pricing_currency',
            [
                'label' => esc_html__( 'Currency', 'keystroke' ),
                'type' => Controls_Manager::SELECT,
                'label_block' => false,
                'options' => [
                    '' => esc_html__( 'None', 'keystroke' ),
                    'baht' => '&#3647; ' . _x( 'Baht', 'Currency Symbol', 'keystroke' ),
                    'bdt' => '&#2547; ' . _x( 'BD Taka', 'Currency Symbol', 'keystroke' ),
                    'dollar' => '&#36; ' . _x( 'Dollar', 'Currency Symbol', 'keystroke' ),
                    'euro' => '&#128; ' . _x( 'Euro', 'Currency Symbol', 'keystroke' ),
                    'franc' => '&#8355; ' . _x( 'Franc', 'Currency Symbol', 'keystroke' ),
                    'guilder' => '&fnof; ' . _x( 'Guilder', 'Currency Symbol', 'keystroke' ),
                    'krona' => 'kr ' . _x( 'Krona', 'Currency Symbol', 'keystroke' ),
                    'lira' => '&#8356; ' . _x( 'Lira', 'Currency Symbol', 'keystroke' ),
                    'peseta' => '&#8359 ' . _x( 'Peseta', 'Currency Symbol', 'keystroke' ),
                    'peso' => '&#8369; ' . _x( 'Peso', 'Currency Symbol', 'keystroke' ),
                    'pound' => '&#163; ' . _x( 'Pound Sterling', 'Currency Symbol', 'keystroke' ),
                    'real' => 'R$ ' . _x( 'Real', 'Currency Symbol', 'keystroke' ),
                    'ruble' => '&#8381; ' . _x( 'Ruble', 'Currency Symbol', 'keystroke' ),
                    'rupee' => '&#8360; ' . _x( 'Rupee', 'Currency Symbol', 'keystroke' ),
                    'indian_rupee' => '&#8377; ' . _x( 'Rupee (Indian)', 'Currency Symbol', 'keystroke' ),
                    'shekel' => '&#8362; ' . _x( 'Shekel', 'Currency Symbol', 'keystroke' ),
                    'won' => '&#8361; ' . _x( 'Won', 'Currency Symbol', 'keystroke' ),
                    'yen' => '&#165; ' . _x( 'Yen/Yuan', 'Currency Symbol', 'keystroke' ),
                    'custom' => esc_html__( 'Custom', 'keystroke' ),
                ],
                'default' => 'dollar',
            ]
        );

        $repeater->add_control(
            'pricing_period_switcher',
            [
                'label' => esc_html__( 'Pricing Period Switcher', 'keystroke' ),
                'type' => Controls_Manager::SELECT,
                'label_block' => false,
                'options' => [
                    'yes' => esc_html__('Yes', 'keystroke'),
                    'no' => esc_html__('No', 'keystroke'),
                ],
                'default' => 'yes',
            ]
        );

        $repeater->add_control(
            'pricing_currency_custom',
            [
                'label' => esc_html__( 'Custom Symbol', 'keystroke' ),
                'type' => Controls_Manager::TEXT,
                'condition' => [
                    'pricing_currency' => 'custom',
                ],
                'dynamic' => [
                    'active' => true,
                ]
            ]
        );

        $repeater->add_control(
            'monthly_pricing_price',
            [
                'label' => esc_html__( 'Monthly Price', 'keystroke' ),
                'type' => Controls_Manager::TEXT,
                'default' => '50',
                'dynamic' => [
                    'active' => true
                ]
            ]
        );
        $repeater->add_control(
            'yearly_pricing_price',
            [
                'label' => esc_html__( 'Yearly Price', 'keystroke' ),
                'type' => Controls_Manager::TEXT,
                'default' => '500',
                'dynamic' => [
                    'active' => true
                ],
                'condition' => [
                    'pricing_period_switcher' => 'yes'
                ]
            ]
        );
        $repeater->add_control(
            'monthly_pricing_period_label',
            [
                'label' => esc_html__( 'Monthly Period Label', 'keystroke' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Monthly',
            ]
        );
        $repeater->add_control(
            'yearly_pricing_period_label',
            [
                'label' => esc_html__( 'Yearly Period Label', 'keystroke' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Yearly',
            ]
        );
        $repeater->add_control(
            'select_default_pricing_period',
            [
                'label' => esc_html__( 'Default Period', 'keystroke' ),
                'type' => Controls_Manager::SELECT,
                'label_block' => false,
                'options' => [
                    'monthly' => esc_html__('Monthly', 'keystroke'),
                    'yearly' => esc_html__('Yearly', 'keystroke'),
                ],
                'default' => 'monthly',
            ]
        );


        /**
         * Start Link
         */
        $repeater->add_control(
            'pricing_link_text',
            [
                'label' => esc_html__('Button Link Text', 'keystroke'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Get Started Today',
                'title' => esc_html__('Enter button text', 'keystroke'),
            ]
        );

        $repeater->add_control(
            'pricing_link_type',
            [
                'label' => esc_html__('Button Link Type', 'keystroke'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '1' => 'Custom Link',
                    '2' => 'Internal Page',
                ],
                'default' => '1',
            ]
        );
        $repeater->add_control(
            'pricing_link',
            [
                'label' => esc_html__('Button Link link', 'keystroke'),
                'type' => \Elementor\Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => esc_html__('https://your-link.com', 'keystroke'),
                'show_external' => true,
                'default' => [
                    'url' => '#',
                    'is_external' => true,
                    'nofollow' => true,
                ],
                'condition' => [
                    'pricing_link_type' => '1'
                ]
            ]
        );
        $repeater->add_control(
            'pricing_page_link',
            [
                'label' => esc_html__('Select Button Link Page', 'keystroke'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'label_block' => true,
                'options' => axil_get_all_pages(),
                'condition' => [
                    'pricing_link_type' => '2'
                ]
            ]
        );
        $repeater->add_control(
            'pricing_button_style',
            [
                'label' => esc_html__('Button Style', 'keystroke'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'btn-transparent' => esc_html__('Outline', 'keystroke'),
                    'btn-solid' => esc_html__('Solid', 'keystroke'),
                ],
                'default' => 'btn-solid'
            ]
        );

        $repeater->add_control(
            'pricing_button_size',
            [
                'label' => esc_html__('Button Size', 'keystroke'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'btn-extra-large' => esc_html__('Extra Large', 'keystroke'),
                    'btn-large' => esc_html__('Large', 'keystroke'),
                    'btn-medium' => esc_html__('Medium', 'keystroke'),
                    'btn-small' => esc_html__('Small', 'keystroke'),
                ],
                'default' => 'btn-large'
            ]
        );

        $repeater->add_control(
            'pricing_button_arrow_icon',
            [
                'label' => esc_html__('Arrow Icon', 'keystroke'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'keystroke'),
                'label_off' => esc_html__('Hide', 'keystroke'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        // End Link
        $repeater->add_control(
            'pricing_button_below_text', [
                'label' => esc_html__( 'Pricing Button Below Text', 'keystroke' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( '100% money back guarantee' , 'keystroke' ),
                'label_block' => true,
            ]
        );
        // End Link
        $repeater->add_control(
            'pricing_features_list', [
                'label' => esc_html__( 'Features List', 'keystroke' ),
                'description' => esc_html__( 'Create new line by Enter', 'keystroke' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => '50 Pages Responsive Website
                    Unlimited PPC Campaigns
                    Unlimited SEO Keywords
                    Unlimited Facebook
                    Unlimited Video Camplaigns',
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'pricing_featured',
            [
                'label' => esc_html__('Featured', 'keystroke'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'keystroke'),
                'label_off' => esc_html__('No', 'keystroke'),
                'return_value' => 'yes',
            ]
        );
        $repeater->add_control(
            'pricing_offset',
            [
                'label' => esc_html__( 'Add Offset', 'keystroke' ),
                'type' => Controls_Manager::SELECT,
                'label_block' => false,
                'options' => [
                    '' => esc_html__( 'None', 'keystroke' ),
                    '1' => esc_html__( 'Offset 1 column', 'keystroke' ),
                    '2' => esc_html__( 'Offset 2 column', 'keystroke' ),
                    '3' => esc_html__( 'Offset 3 column', 'keystroke' ),
                    '4' => esc_html__( 'Offset 4 column', 'keystroke' ),
                    '5' => esc_html__( 'Offset 5 column', 'keystroke' ),
                    '6' => esc_html__( 'Offset 6 column', 'keystroke' ),
                    '7' => esc_html__( 'Offset 7 column', 'keystroke' ),
                    '8' => esc_html__( 'Offset 8 column', 'keystroke' ),
                    '9' => esc_html__( 'Offset 9 column', 'keystroke' ),
                    '10' => esc_html__( 'Offset 10 column', 'keystroke' ),
                    '11' => esc_html__( 'Offset 11 column', 'keystroke' ),
                    '12' => esc_html__( 'Offset 12 column', 'keystroke' ),
                ],
                'default' => '',
            ]
        );
        // End Link

        // Repeater Control
        $this->add_control(
            'pricing_table',
            [
                'label' => esc_html__( 'Pricing table', 'keystroke' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'pricing_title' => esc_html__( 'Small Business', 'keystroke' ),
                        'monthly_pricing_price' => esc_html__( '46', 'keystroke' ),
                        'yearly_pricing_price' => esc_html__( '500', 'keystroke' ),
                        'pricing_featured' => 'yes',
                    ],
                    [
                        'pricing_title' => esc_html__( 'Corporate Business', 'keystroke' ),
                        'monthly_pricing_price' => esc_html__( '199', 'keystroke' ),
                        'yearly_pricing_price' => esc_html__( '900', 'keystroke' ),
                        'pricing_featured' => 'no',
                    ],
                ],
                'title_field' => '{{{ pricing_title }}}',
            ]
        );
        $this->add_control(
            'pricing_style',
            [
                'label' => esc_html__( 'Style', 'keystroke' ),
                'type' => Controls_Manager::SELECT,
                'label_block' => false,
                'options' => [
                    'one' => esc_html__('Style One', 'keystroke'),
                    'two' => esc_html__('Style Two', 'keystroke'),
                ],
                'default' => 'one',
            ]
        );
        $this->end_controls_section();

        // Columns Panel
        $this->axil_columns('pricing_columns', 'Columns', '5', '6', '12', '12');

        $this->axil_basic_style_controls('pricing_section_pre_title', 'Tag Line', '.section-title span.sub-title');
        $this->axil_basic_style_controls('pricing_section_title', 'Title', '.section-title .title');
        $this->axil_basic_style_controls('pricing_section_description', 'Description', '.section-title p');

        $this->axil_basic_style_controls('pricing_table_title', 'Table - Title', '.axil-pricing-table .pricing-header h4');
        $this->axil_basic_style_controls('pricing_table_description', 'Table - Description', '.axil-pricing-table .pricing-header p');
        $this->axil_basic_style_controls('pricing_table_price', 'Table - Price', '.axil-pricing-table .pricing-header .price-wrapper .price h2');
        $this->axil_basic_style_controls('pricing_table_period', 'Table - Period', '.axil-pricing-table .pricing-header .price-wrapper .price span');
        $this->axil_basic_style_controls('pricing_table_guarantee', 'Table - Money back guarantee', '.axil-pricing-table .pricing-header span.subtitle');
        $this->axil_basic_style_controls('pricing_table_fet_list', 'Table - Features List', '.axil-pricing-table .pricing-body .inner ul.list-style li');

        $this->axil_section_style_controls('pricing_table_area', 'Section Style', '.axil-pricing-table-area.pricing-shape-position');

    }

    private static function get_currency_symbol( $symbol_name ) {
        $symbols = [
            'baht' => '&#3647;',
            'bdt' => '&#2547;',
            'dollar' => '&#36;',
            'euro' => '&#128;',
            'franc' => '&#8355;',
            'guilder' => '&fnof;',
            'indian_rupee' => '&#8377;',
            'pound' => '&#163;',
            'peso' => '&#8369;',
            'peseta' => '&#8359',
            'lira' => '&#8356;',
            'ruble' => '&#8381;',
            'shekel' => '&#8362;',
            'rupee' => '&#8360;',
            'real' => 'R$',
            'krona' => 'kr',
            'won' => '&#8361;',
            'yen' => '&#165;',
        ];

        return isset( $symbols[ $symbol_name ] ) ? $symbols[ $symbol_name ] : '';
    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();

        $pricing_style = ($settings['pricing_style'] == 'two') ? "prcing-style-2" : "";
        $row_class = ($settings['pricing_style'] == 'two') ? "row--25" : "row--0";

        ?>
        <!-- Start Pricing Table Area -->
        <div class="axil-pricing-table-area pricing-shape-position ax-section-gap bg-color-lightest pb--165">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-title text-center">
                            <?php $this->axil_section_title_render('pricing_plan','extra04-color', $this->get_settings()); ?>
                        </div>
                    </div>
                </div>
                <?php if($settings['pricing_table']){ ?>
                <div class="row mt--20 mt_sm--0 <?php echo esc_attr($row_class); ?>">
                    <?php foreach ($settings['pricing_table'] as $table ){
                        $active = ($table['pricing_featured'] === 'yes') ? 'active' : '';
                        ?>
                        <!-- Start Single pricing table -->
                        <div data-unique-id="axil-pricing-table-<?php echo $table['_id']; ?>" class="axil-pricing axil-pricing-table-<?php echo $table['_id']; ?> elementor-repeater-item-<?php echo $table['_id']; ?> col-lg-<?php echo esc_attr($settings['axil_pricing_columns_for_desktop']); ?> col-md-<?php echo esc_attr($settings['axil_pricing_columns_for_laptop']); ?> col-sm-<?php echo esc_attr($settings['axil_pricing_columns_for_tablet']); ?> col-<?php echo esc_attr($settings['axil_pricing_columns_for_mobile']); ?> offset-lg-<?php echo esc_attr($table['pricing_offset']); ?>">
                            <div class="axil-pricing-table mt--40 move-up wow <?php echo esc_attr($pricing_style); ?> <?php echo esc_attr($active); ?>">
                                <div class="axil-pricing-inner">
                                    <div class="pricing-header">
                                        <?php if ($table['pricing_title']){ ?> <h4> <?php echo esc_html($table['pricing_title']); ?> </h4> <?php } ?>
                                        <?php if ($table['pricing_sub_title']){ ?> <p> <?php echo esc_html($table['pricing_sub_title']); ?> </p> <?php } ?>

                                        <div class="price-wrapper">
                                            <div class="price">
                                                <?php
                                                if ( $table['pricing_currency'] === 'custom' ) {
                                                    $currency = $table['pricing_currency_custom'];
                                                } else {
                                                    $currency = self::get_currency_symbol( $table['pricing_currency'] );
                                                }
                                                ?>
                                                <?php if ($currency){ ?> <h2 class="currency"> <?php echo esc_html($currency); ?> </h2> <?php } ?>
                                                <?php
                                                if($table['pricing_period_switcher'] == 'yes'){ ?>
                                                    <?php if ($table['monthly_pricing_price']){ ?> <h2 class="heading headin-h3 monthly"> <?php echo esc_html($table['monthly_pricing_price']); ?> </h2> <?php } ?>
                                                    <?php if ($table['monthly_pricing_period_label']){ ?> <span class="pricing-period monthly-period"> <?php echo esc_html($table['monthly_pricing_period_label']); ?> </span> <?php } ?>

                                                    <!-- yearly-->
                                                    <?php if ($table['yearly_pricing_price']){ ?> <h2 class="heading headin-h3 yearly"> <?php echo esc_html($table['yearly_pricing_price']); ?> </h2> <?php } ?>
                                                    <?php if ($table['yearly_pricing_period_label']){ ?> <span class="pricing-period yearly-period"> <?php echo esc_html($table['yearly_pricing_period_label']); ?> </span> <?php } ?>
                                                <?php } else { ?>
                                                    <?php if ($table['monthly_pricing_price']){ ?> <h2 class="heading headin-h3"> <?php echo esc_html($table['monthly_pricing_price']); ?> </h2> <?php } ?>
                                                    <?php if ($table['monthly_pricing_period_label']){ ?> <span class="pricing-period"> <?php echo esc_html($table['monthly_pricing_period_label']); ?> </span> <?php } ?>
                                                <?php } ?>
                                            </div>

                                            <?php if($table['pricing_period_switcher'] == 'yes'){
                                                $active_yearly = ($table['select_default_pricing_period'] == 'yearly') ?  ' selected ' : '';
                                                $active_monthly = ($table['select_default_pricing_period'] == 'monthly') ?  ' selected ' : '';
                                                ?>
                                                <div class="date-option">
                                                    <select class="period-switcher">
                                                        <?php if(!empty($table['yearly_pricing_period_label'])){ ?>
                                                            <option value="yearly" <?php echo esc_attr($active_yearly); ?> ><?php echo esc_html($table['yearly_pricing_period_label']) ?></option>
                                                        <?php } ?>
                                                        <?php if(!empty($table['monthly_pricing_period_label'])){ ?>
                                                            <option value="monthly" <?php echo esc_attr($active_monthly); ?> ><?php echo esc_html($table['monthly_pricing_period_label']) ?></option>
                                                        <?php } ?>

                                                    </select>
                                                </div>
                                            <?php } ?>
                                        </div>

                                        <?php
                                        // Link
                                        if ('2' == $table['pricing_link_type']) {
                                            $link = get_permalink($table['pricing_page_link']);
                                            $target = '_self';
                                            $rel = 'nofollow';
                                        } else {
                                            if (!empty($table['pricing_link']['url'])) {
                                                $link = $table['pricing_link']['url'];
                                            }
                                            if ($table['pricing_link']['is_external']) {
                                                $target = '_blank';
                                            }
                                            if (!empty($table['pricing_link']['nofollow'])) {
                                                $rel = 'nofollow';
                                            }
                                        }
                                        $arrow = ($table['pricing_button_arrow_icon'] == 'yes') ? '<span class="button-icon"></span>' : '';
                                        ?>
                                        <?php if (!empty($table['pricing_link_text'])): ?>
                                        <div class="pricing-get-button">
                                            <a class="axil-button <?php echo esc_attr($table['pricing_button_size']) ?> <?php echo esc_attr($table['pricing_button_style']) ?> "
                                               href="<?php echo esc_url($link); ?>"
                                               target="<?php echo esc_attr($target); ?>"
                                               rel="<?php echo esc_attr($rel); ?>">
                                                <span class="button-text"> <?php echo esc_html($table['pricing_link_text']); ?></span><?php echo wp_kses_post($arrow); ?>
                                            </a>
                                        </div>
                                        <?php endif ?>
                                        <?php if ($table['pricing_button_below_text']){ ?> <span class="subtitle"> <?php echo esc_html($table['pricing_button_below_text']); ?> </span> <?php } ?>
                                    </div>
                                    <?php  $lines = explode("\n", $table['pricing_features_list']); // or use PHP PHP_EOL constant ?>
                                    <?php if(!empty($lines)){ ?>
                                    <div class="pricing-body">
                                        <div class="inner">
                                            <ul class="list-style">
                                                <?php foreach ( $lines as $line ) {
                                                    echo '<li>'. trim( $line ) .'</li>';
                                                } ?>
                                            </ul>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <!-- End Single pricing table -->
                    <?php } ?>
                </div>
                <?php } ?>
            </div>
            <div class="shape-group">
                <div class="shape">
                    <span class="icon icon-shape-14"></span>
                </div>
            </div>
        </div>
        <!-- End Pricing Table Area -->
        <?php



    }

}

Plugin::instance()->widgets_manager->register_widget_type( new keystroke_Elementor_Widget_PricingPlan() );


