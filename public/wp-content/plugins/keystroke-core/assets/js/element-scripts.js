(function ($) {
    "use strict";

    var $document = $(document),
        $ajax_nonce = axil_portfolio_ajax.ajax_nonce,
        $axil_ajax_url = axil_portfolio_ajax.ajax_url,
        $window = $(window),
        isEditMode = false;

    /**
     * Undefined Check
     * @param $selector
     * @param $data_atts
     * @returns {string}
     */
    function mybe_note_undefined($selector, $data_atts) {
		return ($selector.data($data_atts) !== undefined) ? $selector.data($data_atts) : '';
	}

    /**
     * Filter Masonry
     * @param $scope
     * @param $
     * @param wrapper_selector
     * @param button_selector
     * @param list_selector
     * @param item_seclector
     */
    function axilFilterMasonryInit($scope, $, wrapper_selector, button_selector, list_selector, item_seclector){
        var mesonaryArea = $scope.find(wrapper_selector);
        mesonaryArea.each( function (index, elements) {
            var $this = $(this);
            var filterArea = $scope.find(wrapper_selector).eq(0),
                filterArea_unique_id = '.' + $this.data('unique-id'),
                selector = filterArea_unique_id + wrapper_selector;

            $(selector).imagesLoaded(function () {
                // filter items on button click
                $(selector + ' ' + button_selector).on('click', 'button', function (event) {
                    event.preventDefault();
                    $(this).siblings('.is-checked').removeClass('is-checked');
                    $(this).addClass('is-checked');

                    var filterValue = $(this).attr('data-filter');
                    $grid.isotope({
                        filter: filterValue
                    });
                });

                // init Isotope
                var $grid = $(selector + ' ' + list_selector).isotope({
                    itemSelector: selector + ' ' + item_seclector,
                    percentPosition: true,
                    transitionDuration: '0.5s',
                    layoutMode: 'fitRows',
                });
            });


        });
    }

	// SliderActivationWithSlick
    var SliderActivationWithSlick = function ($scope, $) {
        var slider_carousel_options = $scope.find('.slider-activation-with-slick').eq(0);
        if ( slider_carousel_options.length > 0) {
            var settings            = slider_carousel_options.data('settings');
            var arrows              = settings['arrows'];
            var dots                = settings['dots'];
            var autoplay            = settings['autoplay'];
            var autoplay_speed      = parseInt(settings['autoplay_speed']) || 3000;
            var animation_speed     = parseInt(settings['animation_speed']) || 300;
            var pause_on_hover      = settings['pause_on_hover'];
            slider_carousel_options.slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                dots: dots,
                centerMode: false,
                focusOnSelect: true,
                arrows: arrows,
                prevArrow: '<button class="rf-slide-prev"><i class="ti-angle-left"></i></button>',
                nextArrow: '<button class="rf-slide-next"><i class="ti-angle-right"></i></button>',
                infinite: true,
                autoplay: autoplay,
                autoplaySpeed: autoplay_speed,
                speed: animation_speed,
                pauseOnHover: pause_on_hover,
            });
            $('.slider-activation-withouticon').slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                dots: false,
                centerMode: false,
                focusOnSelect: true,
                arrows: false,
            });
        }
    }

    /**
     * Service active class move and tilt hover animation
     */
    var serviceHover = function ($scope, $) {
        var axil_service = $scope.find('.axil-service-area .axil-service').eq(0);
        if(axil_service.length > 0){
            $('.axil-service-area .axil-service').mouseenter(function() {
                var self=this;
                $(self).removeClass('axil-control');
                setTimeout(() => {
                    $('.axil-service-area .active').removeClass('active').addClass('axil-control');
                    $(self).removeClass('axil-control').addClass('active');
                    $('.axil-service.active .inner::before').css("opacity",0.1);
                }, 0);

            });
            var _tiltAnimation = $('.paralax-image')
            if (_tiltAnimation.length) {
                _tiltAnimation.tilt({
                    max: 12,
                    speed: 1e3,
                    easing: 'cubic-bezier(.03,.98,.52,.99)',
                    transition: !1,
                    perspective: 1e3,
                    scale: 1
                })
            }
        }

    }


    /**
     * Service active class move and tilt hover animation
     */
    var digitalAgencyBanner = function ($scope, $) {
        
        var _tiltAnimation = $('.paralax-image')
        if (_tiltAnimation.length) {
            _tiltAnimation.tilt({
                max: 12,
                speed: 1e3,
                easing: 'cubic-bezier(.03,.98,.52,.99)',
                transition: !1,
                perspective: 1e3,
                scale: 1
            })
        }
        
    }


    /**
     * Case Study Slider
     * @param $scope
     * @param $
     */
    var slickActive = function ($scope, $){

        $.fn.elExists = function () {
            return this.length > 0;
        };
        // Variables
        var $html = $('html'),
            $elementCarousel = $('.axil-carousel');

        if ($elementCarousel.elExists()) {
            var slickInstances = [];
            $elementCarousel.each(function (index, element) {
                var $this = $(this);
                // Carousel Options
                var $options = typeof $this.data('slick-options') !== 'undefined' ? $this.data('slick-options') : '';
                var $spaceBetween = $options.spaceBetween ? parseInt($options.spaceBetween) : 0,
                    $spaceBetween_xl = $options.spaceBetween_xl ? parseInt($options.spaceBetween_xl) : 0,
                    $isCustomArrow = $options.isCustomArrow ? $options.isCustomArrow : false,
                    $customPrev = $isCustomArrow === true ? ($options.customPrev ? $options.customPrev : '') : '',
                    $customNext = $isCustomArrow === true ? ($options.customNext ? $options.customNext : '') : '',
                    $vertical = $options.vertical ? $options.vertical : false,
                    $focusOnSelect = $options.focusOnSelect ? $options.focusOnSelect : false,
                    $asNavFor = $options.asNavFor ? $options.asNavFor : '',
                    $fade = $options.fade ? $options.fade : false,
                    $autoplay = $options.autoplay ? $options.autoplay : false,
                    $autoplaySpeed = $options.autoplaySpeed ? $options.autoplaySpeed : 5000,
                    $swipe = $options.swipe ? $options.swipe : false,
                    $adaptiveHeight = $options.adaptiveHeight ? $options.adaptiveHeight : false,

                    $arrows = $options.arrows ? $options.arrows : false,
                    $dots = $options.dots ? $options.dots : false,
                    $infinite = $options.infinite ? $options.infinite : false,
                    $centerMode = $options.centerMode ? $options.centerMode : false,
                    $centerPadding = $options.centerPadding ? $options.centerPadding : '',
                    $speed = $options.speed ? parseInt($options.speed) : 1000,
                    $prevArrow = $arrows === true ? ($options.prevArrow ? '<span class="' + $options.prevArrow.buttonClass + '"><i class="' + $options.prevArrow.iconClass + '"></i></span>' : '<button class="slick-prev">previous</span>') : '',
                    $nextArrow = $arrows === true ? ($options.nextArrow ? '<span class="' + $options.nextArrow.buttonClass + '"><i class="' + $options.nextArrow.iconClass + '"></i></span>' : '<button class="slick-next">next</span>') : '',
                    $slidesToShow = $options.slidesToShow ? parseInt($options.slidesToShow, 10) : 1,
                    $slidesToScroll = $options.slidesToScroll ? parseInt($options.slidesToScroll, 10) : 1;

                /*Responsive Variable, Array & Loops*/
                var $responsiveSetting = typeof $this.data('slick-responsive') !== 'undefined' ? $this.data('slick-responsive') : '',
                    $responsiveSettingLength = $responsiveSetting.length,
                    $responsiveArray = [];
                for (var i = 0; i < $responsiveSettingLength; i++) {
                    $responsiveArray[i] = $responsiveSetting[i];

                }

                // Adding Class to instances
                $this.addClass('slick-carousel-' + index);
                $this.parent().find('.slick-dots').addClass('dots-' + index);
                $this.parent().find('.slick-btn').addClass('btn-' + index);

                if ($spaceBetween != 0) {
                    $this.addClass('slick-gutter-' + $spaceBetween);
                }
                if ($spaceBetween_xl != 0) {
                    $this.addClass('slick-gutter-xl-' + $spaceBetween_xl);
                }
                $this.not('.slick-initialized').slick({
                    slidesToShow: $slidesToShow,
                    slidesToScroll: $slidesToScroll,
                    asNavFor: $asNavFor,
                    autoplay: $autoplay,
                    autoplaySpeed: $autoplaySpeed,
                    speed: $speed,
                    infinite: $infinite,
                    arrows: $arrows,
                    dots: $dots,
                    vertical: $vertical,
                    focusOnSelect: $focusOnSelect,
                    centerMode: $centerMode,
                    centerPadding: $centerPadding,
                    fade: $fade,
                    adaptiveHeight: $adaptiveHeight,
                    prevArrow: $prevArrow,
                    nextArrow: $nextArrow,
                    responsive: $responsiveArray,
                });
                if ($isCustomArrow === true) {
                    $($customPrev).on('click', function () {
                        $this.slick('slickPrev');
                    });
                    $($customNext).on('click', function () {
                        $this.slick('slickNext');
                    });
                }
            });

            // Updating the sliders in tab
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                $elementCarousel.slick('setPosition');
            });
        }
    }

    /**
     * Project/Portfolio
     * @param $scope
     * @param $
     */
    var axilProject = function ($scope, $){
        axilFilterMasonryInit($scope, $, '.axil-portfolio-area', '.messonry-button', '.mesonry-list', '.portfolio');
    }

    /**
     * Project/Portfolio
     * @param $scope
     * @param $
     */
    var axilTeam = function ($scope, $){
        axilFilterMasonryInit($scope, $, '.axil-team-area', '.messonry-button', '.mesonry-list', '.portfolio');
    }

    var sidebarsticky = function ($scope, $){
        var sidebar = new StickySidebar('.axil-scroll-nav', {
            topSpacing: 0,
            bottomSpacing: 0,
            containerSelector: '.axil-scroll-navigation',
            innerWrapperSelector: '.sidebar__inner'
        });

        $('body').scrollspy({ target: '.navbar-example2', offset: 100 });

    }

    /* Pricing Plan */
    var pricingPlan = function ($scope, $){

        var pricingArea = $scope.find('.axil-pricing');
        pricingArea.each(function (index, element) {
            var $this = $(this);
            var pricing = $scope.find('.axil-pricing').eq(0),
                pricing_unique_id = '.' + $this.data('unique-id'),
                selector = pricing_unique_id + ' .period-switcher';

            var switcher = $(selector);
            var value = switcher.val();

            if (value == "yearly") {
                $(pricing_unique_id + ' .heading.monthly').hide();
                $(pricing_unique_id + " .monthly-period").hide();
            } else if (value == "monthly") {
                $(pricing_unique_id + " .heading.yearly").hide();
                $(pricing_unique_id + " .yearly-period").hide();
            }

            switcher.change(function () {
                var value = $(this).val();

                if (value == "yearly") {
                    $(pricing_unique_id + ' .heading.monthly').hide();
                    $(pricing_unique_id + " .monthly-period").hide();

                    $(pricing_unique_id + " .heading.yearly").show();
                    $(pricing_unique_id + " .yearly-period").show();

                } else if (value == "monthly") {
                    $(pricing_unique_id + " .heading.yearly").hide();
                    $(pricing_unique_id + " .yearly-period").hide();

                    $(pricing_unique_id + ' .heading.monthly').show();
                    $(pricing_unique_id + " .monthly-period").show();
                }
            });

        });


    }

    // Init 
    $window.on('elementor/frontend/init', function () {
	    if(elementorFrontend.isEditMode()) {
	        isEditMode = true;
	    }
        elementorFrontend.hooks.addAction('frontend/element_ready/keystroke-carousel-slider.default', SliderActivationWithSlick);
        elementorFrontend.hooks.addAction('frontend/element_ready/keystroke-services.default', serviceHover);
        elementorFrontend.hooks.addAction('frontend/element_ready/keystroke-casestudy.default', slickActive);
        elementorFrontend.hooks.addAction('frontend/element_ready/keystroke-project.default', axilProject);
        elementorFrontend.hooks.addAction('frontend/element_ready/keystroke-team.default', axilTeam);
        elementorFrontend.hooks.addAction('frontend/element_ready/keystroke-brandcarousel.default', slickActive);
        elementorFrontend.hooks.addAction('frontend/element_ready/keystroke-services-navigation.default', sidebarsticky);
        elementorFrontend.hooks.addAction('frontend/element_ready/keystroke-pricing-plan.default', pricingPlan);
        elementorFrontend.hooks.addAction('frontend/element_ready/keystroke-banner.default', digitalAgencyBanner);
	});

    /**
     * setTime
     * @returns {number}
     */
    function setTime() {
        return 300;
    }

    /**
     * showTextLoading
     * @param selector
     */
    function showTextLoading(selector) {
        $('' + selector + '').addClass('text-loading');
        $('' + selector + '').addClass('disabled');
    }

    /**
     * hideTextLoading
     * @param selector
     */
    function hideTextLoading(selector) {
        $('' + selector + '').removeClass('text-loading');
        $('' + selector + '').removeClass('disabled');
    }


    /* Load All Portfolio */
    $document.on('click', '.load-more', function (e) {
        e.preventDefault();
        var _self = $(this);
        showTextLoading('.load-more');
        var $settings = _self.attr('data-settings');
        var $query = _self.attr('data-query');

        var settings = $.parseJSON($settings),
            query = $.parseJSON($query),
            paged = _self.attr('data-paged'),
            post_count = _self.attr('data-post-count'),
            $target = _self.data('target');

        $.ajax({
            url: $axil_ajax_url,
            dataType: "json",
            method: 'post',
            cache: false,
            data: {
                'action': 'axil_get_all_posts_content',
                'security': $ajax_nonce,
                'query': query,
                'settings': settings,
                'paged': paged,
                'post_count': post_count
            },
            success: function (resp) {
                var $html = resp;

                if (typeof($html.outputs) != "undefined" || ($html)) {
                    var postsCount = parseInt($html.posts_count),
                        total_posts = 0;
                    _self.attr('data-paged', parseInt(paged) + 1);
                    _self.attr('data-post-count', parseInt(postsCount));


                    var $isotope = $($target).data('isotope');
                    if ($isotope) {
                        var $items = $($html.outputs);
                        $items.each(function () {
                            var $content = $(this);
                            setTimeout(function () {
                                _self.parent().siblings('.portfolio-wrapper').append($content).isotope('insert', $content);
                                total_posts = $($target).find('.portfolio').length;
                            }, setTime());
                        });
                    } else {
                        _self.parent().siblings('.portfolio-wrapper').append($html.outputs);
                    }

                    setTimeout(function () {
                        if ($html.outputs == "" || (total_posts == $html.posts_count)) {
                            _self.remove();
                        }
                    }, setTime());
                    hideTextLoading('.load-more');
                }
            },
            error: function (errorThrown) {
                console.log(errorThrown);
            }
        });

    });


}(jQuery));