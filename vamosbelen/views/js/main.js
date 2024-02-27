(function ($) {
    "use strict";
    var iOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;

    var isMobile = {
        Android: function () {
            return navigator.userAgent.match(/Android/i);
        },
        BlackBerry: function () {
            return navigator.userAgent.match(/BlackBerry/i);
        },
        iOS: function () {
            return navigator.userAgent.match(/iPhone|iPad|iPod/i);
        },
        Opera: function () {
            return navigator.userAgent.match(/Opera Mini/i);
        },
        Windows: function () {
            return navigator.userAgent.match(/IEMobile/i);
        },
        any: function () {
            return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
        }
    }

    // Declarando funciones globales

    function parallax() {
        $('.bg--parallax').each(function () {
            var el = $(this),
                xpos = "50%",
                windowHeight = $(window).height();
            if (isMobile.any()) {
                $(this).css('background-attachment', 'scroll');
            } else {
                $(window).scroll(function () {
                    var current = $(window).scrollTop(),
                        top = el.offset().top,
                        height = el.outerHeight();
                    if (top + height < current || top > current + windowHeight) {
                        return;
                    }
                    el.css('backgroundPosition', xpos + " " + Math.round((top - current) * 0.2) + "px");
                });
            }
        });
    }

    function backgroundImage() {
        var databackground = $('[data-background]');
        databackground.each(function () {
            if ($(this).attr('data-background')) {
                var image_path = $(this).attr('data-background');
                $(this).css({
                    'background': 'url(' + image_path + ')'
                });
            }
        });
    }

    function siteToggleAction() {
        var navSidebar = $('.navigation--sidebar'),
            filterSidebar = $('.ps-filter--sidebar');
        $('.menu-toggle-open').on('click', function (e) {
            e.preventDefault();
            $(this).toggleClass('active')
            navSidebar.toggleClass('active');
            $('.ps-site-overlay').toggleClass('active');
        });

        $('.ps-toggle--sidebar').on('click', function (e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $(this).toggleClass('active');
            $(this).siblings('a').removeClass('active');
            $(url).toggleClass('active');
            $(url).siblings('.ps-panel--sidebar').removeClass('active');
            $('.ps-site-overlay').toggleClass('active');
        });

        $('#filter-sidebar').on('click', function (e) {
            e.preventDefault();
            filterSidebar.addClass('active');
            $('.ps-site-overlay').addClass('active');
        });

        $('.ps-filter--sidebar .ps-filter__header .ps-btn--close').on('click', function (e) {
            e.preventDefault();
            filterSidebar.removeClass('active');
            $('.ps-site-overlay').removeClass('active');
        });

        $('body').on("click", function (e) {
            if ($(e.target).siblings(".ps-panel--sidebar").hasClass('active')) {
                $('.ps-panel--sidebar').removeClass('active');
                $('.ps-site-overlay').removeClass('active');
            }
        });
    }

    function subMenuToggle() {
        $('.menu--mobile .menu-item-has-children > .sub-toggle').on('click', function (e) {
            e.preventDefault();
            var current = $(this).parent('.menu-item-has-children')
            $(this).toggleClass('active');
            current.siblings().find('.sub-toggle').removeClass('active');
            current.children('.sub-menu').slideToggle(350);
            current.siblings().find('.sub-menu').slideUp(350);
            if (current.hasClass('has-mega-menu')) {
                current.children('.mega-menu').slideToggle(350);
                current.siblings('.has-mega-menu').find('.mega-menu').slideUp(350);
            }

        });
        $('.menu--mobile .has-mega-menu .mega-menu__column .sub-toggle').on('click', function (e) {
            e.preventDefault();
            var current = $(this).closest('.mega-menu__column')
            $(this).toggleClass('active');
            current.siblings().find('.sub-toggle').removeClass('active');
            current.children('.mega-menu__list').slideToggle(350);
            current.siblings().find('.mega-menu__list').slideUp(350);
        });
        var listCategories = $('.ps-list--categories');
        if (listCategories.length > 0) {
            $('.ps-list--categories .menu-item-has-children > .sub-toggle').on('click', function (e) {
                e.preventDefault();
                var current = $(this).parent('.menu-item-has-children')
                $(this).toggleClass('active');
                current.siblings().find('.sub-toggle').removeClass('active');
                current.children('.sub-menu').slideToggle(350);
                current.siblings().find('.sub-menu').slideUp(350);
                if (current.hasClass('has-mega-menu')) {
                    current.children('.mega-menu').slideToggle(350);
                    current.siblings('.has-mega-menu').find('.mega-menu').slideUp(350);
                }

            });
        }
    }

    function stickyHeader() {
        var header = $('.header'),
            scrollPosition = 0,
            checkpoint = 50;
        header.each(function () {
            if ($(this).data('sticky') === true) {
                var el = $(this);
                $(window).scroll(function () {

                    var currentPosition = $(this).scrollTop();
                    if (currentPosition > checkpoint) {
                        el.addClass('header--sticky');
                    } else {
                        el.removeClass('header--sticky');
                    }
                });
            }
        })

        var stickyCart = $('#cart-sticky');
        if (stickyCart.length > 0) {
            $(window).scroll(function () {
                var currentPosition = $(this).scrollTop();
                if (currentPosition > checkpoint) {
                    stickyCart.addClass('active');
                } else {
                    stickyCart.removeClass('active');
                }
            });
        }
    }

    function owlCarouselConfig() {
        var target = $('.owl-slider');
        if (target.length > 0) {
            target.each(function () {
                var el = $(this),
                    dataAuto = el.data('owl-auto'),
                    dataLoop = el.data('owl-loop'),
                    dataSpeed = el.data('owl-speed'),
                    dataGap = el.data('owl-gap'),
                    dataNav = el.data('owl-nav'),
                    dataDots = el.data('owl-dots'),
                    dataAnimateIn = (el.data('owl-animate-in')) ? el.data('owl-animate-in') : '',
                    dataAnimateOut = (el.data('owl-animate-out')) ? el.data('owl-animate-out') : '',
                    dataDefaultItem = el.data('owl-item'),
                    dataItemXS = el.data('owl-item-xs'),
                    dataItemSM = el.data('owl-item-sm'),
                    dataItemMD = el.data('owl-item-md'),
                    dataItemLG = el.data('owl-item-lg'),
                    dataItemXL = el.data('owl-item-xl'),
                    dataNavLeft = (el.data('owl-nav-left')) ? el.data('owl-nav-left') : "<i class='icon-chevron-left'></i>",
                    dataNavRight = (el.data('owl-nav-right')) ? el.data('owl-nav-right') : "<i class='icon-chevron-right'></i>",
                    duration = el.data('owl-duration'),
                    datamouseDrag = (el.data('owl-mousedrag') == 'on') ? true : false;
                if (target.children('div, span, a, img, h1, h2, h3, h4, h5, h5').length >= 2) {
                    el.owlCarousel({
                        animateIn: dataAnimateIn,
                        animateOut: dataAnimateOut,
                        margin: dataGap,
                        autoplay: dataAuto,
                        autoplayTimeout: dataSpeed,
                        autoplayHoverPause: true,
                        loop: dataLoop,
                        nav: dataNav,
                        mouseDrag: datamouseDrag,
                        touchDrag: true,
                        autoplaySpeed: duration,
                        navSpeed: duration,
                        dotsSpeed: duration,
                        dragEndSpeed: duration,
                        navText: [dataNavLeft, dataNavRight],
                        dots: dataDots,
                        items: dataDefaultItem,
                        responsive: {
                            0: {
                                items: dataItemXS
                            },
                            480: {
                                items: dataItemSM
                            },
                            768: {
                                items: dataItemMD
                            },
                            992: {
                                items: dataItemLG
                            },
                            1200: {
                                items: dataItemXL
                            },
                            1680: {
                                items: dataDefaultItem
                            }
                        }
                    });
                }

            });
        }
    }

    function masonry($selector) {
        var masonry = $($selector);
        if (masonry.length > 0) {
            if (masonry.hasClass('filter')) {
                masonry.imagesLoaded(function () {
                    masonry.isotope({
                        columnWidth: '.grid-sizer',
                        itemSelector: '.grid-item',
                        isotope: {
                            columnWidth: '.grid-sizer'
                        },
                        filter: "*"
                    });
                });
                var filters = masonry.closest('.masonry-root').find('.ps-masonry-filter > li > a');
                filters.on('click', function (e) {
                    e.preventDefault();
                    var selector = $(this).attr('href');
                    filters.find('a').removeClass('current');
                    $(this).parent('li').addClass('current');
                    $(this).parent('li').siblings('li').removeClass('current');
                    $(this).closest('.masonry-root').find('.ps-masonry').isotope({
                        itemSelector: '.grid-item',
                        isotope: {
                            columnWidth: '.grid-sizer'
                        },
                        filter: selector
                    });
                    return false;
                });
            } else {
                masonry.imagesLoaded(function () {
                    masonry.masonry({
                        columnWidth: '.grid-sizer',
                        itemSelector: '.grid-item'
                    });
                });
            }
        }
    }

    function mapConfig() {
        var map = $('#contact-map');
        if (map.length > 0) {
            map.gmap3({
                address: map.data('address'),
                zoom: map.data('zoom'),
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                scrollwheel: false
            }).marker(function (map) {
                return {
                    position: map.getCenter(),
                    icon: 'img/marker.png',
                };
            }).infowindow({
                content: map.data('address')
            }).then(function (infowindow) {
                var map = this.get(0);
                var marker = this.get(1);
                marker.addListener('click', function () {
                    infowindow.open(map, marker);
                });
            });
        } else {
            return false;
        }
    }

    function slickConfig() {
        var product = $('.ps-product--detail');
        if (product.length > 0) {
            var primary = product.find('.ps-product__gallery'),
                second = product.find('.ps-product__variants'),
                vertical = product.find('.ps-product__thumbnail').data('vertical');
            primary.slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                asNavFor: '.ps-product__variants',
                fade: true,
                dots: false,
                infinite: false,
                arrows: primary.data('arrow'),
                prevArrow: "<a href='#'><i class='fa fa-angle-left'></i></a>",
                nextArrow: "<a href='#'><i class='fa fa-angle-right'></i></a>",
            });
            second.slick({
                slidesToShow: second.data('item'),
                slidesToScroll: 1,
                infinite: false,
                arrows: second.data('arrow'),
                focusOnSelect: true,
                prevArrow: "<a href='#'><i class='fa fa-angle-up'></i></a>",
                nextArrow: "<a href='#'><i class='fa fa-angle-down'></i></a>",
                asNavFor: '.ps-product__gallery',
                vertical: vertical,
                responsive: [
                    {
                        breakpoint: 1200,
                        settings: {
                            arrows: second.data('arrow'),
                            slidesToShow: 4,
                            vertical: false,
                            prevArrow: "<a href='#'><i class='fa fa-angle-left'></i></a>",
                            nextArrow: "<a href='#'><i class='fa fa-angle-right'></i></a>"
                        }
                    },
                    {
                        breakpoint: 992,
                        settings: {
                            arrows: second.data('arrow'),
                            slidesToShow: 4,
                            vertical: false,
                            prevArrow: "<a href='#'><i class='fa fa-angle-left'></i></a>",
                            nextArrow: "<a href='#'><i class='fa fa-angle-right'></i></a>"
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 3,
                            vertical: false,
                            prevArrow: "<a href='#'><i class='fa fa-angle-left'></i></a>",
                            nextArrow: "<a href='#'><i class='fa fa-angle-right'></i></a>"
                        }
                    },
                ]
            });
        }
    }

    function tabs() {
        $('.ps-tab-list  li > a ').on('click', function (e) {
            e.preventDefault();
            var target = $(this).attr('href');
            $(this).closest('li').siblings('li').removeClass('active');
            $(this).closest('li').addClass('active');
            $(target).addClass('active');
            $(target).siblings('.ps-tab').removeClass('active');
        });
        $('.ps-tab-list.owl-slider .owl-item a').on('click', function (e) {
            e.preventDefault();
            var target = $(this).attr('href');
            $(this).closest('.owl-item').siblings('.owl-item').removeClass('active');
            $(this).closest('.owl-item').addClass('active');
            $(target).addClass('active');
            $(target).siblings('.ps-tab').removeClass('active');
        });
    }

    function rating() {
        $('select.ps-rating').each(function () {
            var readOnly;
            if ($(this).attr('data-read-only') == 'true') {
                readOnly = true
            } else {
                readOnly = false;
            }
            $(this).barrating({
                theme: 'fontawesome-stars',
                readonly: readOnly,
                emptyValue: '0'
            });
        });
    }

    function productLightbox() {
        var product = $('.ps-product--detail');
        if (product.length > 0) {
            $('.ps-product__gallery').lightGallery({
                selector: '.item a',
                thumbnail: true,
                share: false,
                fullScreen: false,
                autoplay: false,
                autoplayControls: false,
                actualSize: false
            });
            if (product.hasClass('ps-product--sticky')) {
                $('.ps-product__thumbnail').lightGallery({
                    selector: '.item a',
                    thumbnail: true,
                    share: false,
                    fullScreen: false,
                    autoplay: false,
                    autoplayControls: false,
                    actualSize: false
                });
            }
        }
        $('.ps-gallery--image').lightGallery({
            selector: '.ps-gallery__item',
            thumbnail: true,
            share: false,
            fullScreen: false,
            autoplay: false,
            autoplayControls: false,
            actualSize: false
        });
        $('.ps-video').lightGallery({
            thumbnail: false,
            share: false,
            fullScreen: false,
            autoplay: false,
            autoplayControls: false,
            actualSize: false
        });
    }

    function backToTop() {
        var scrollPos = 0;
        var element = $('#back2top');
        $(window).scroll(function () {
            var scrollCur = $(window).scrollTop();
            if (scrollCur > scrollPos) {
                // scroll down
                if (scrollCur > 500) {
                    element.addClass('active');
                } else {
                    element.removeClass('active');
                }
            } else {
                // scroll up
                element.removeClass('active');
            }

            scrollPos = scrollCur;
        });

        element.on('click', function () {
            $('html, body').animate({
                scrollTop: '0px'
            }, 800);
        });
    }

    function filterSlider() {
        var el = $('.ps-slider');
        var min = el.siblings().find('.ps-slider__min');
        var max = el.siblings().find('.ps-slider__max');
        var defaultMinValue = el.data('default-min');
        var defaultMaxValue = el.data('default-max');
        var maxValue = el.data('max');
        var step = el.data('step');
        if (el.length > 0) {
            el.slider({
                min: 0,
                max: maxValue,
                step: step,
                range: true,
                values: [defaultMinValue, defaultMaxValue],
                slide: function (event, ui) {
                    var values = ui.values;
                    min.text('$' + values[0]);
                    max.text('$' + values[1]);
                }
            });
            var values = el.slider("option", "values");
            min.text('$' + values[0]);
            max.text('$' + values[1]);
        } else {
            // return false;
        }
    }

    function modalInit() {
        var modal = $('.ps-modal');
        if (modal.length) {
            if (modal.hasClass('active')) {
                $('body').css('overflow-y', 'hidden');
            }
        }
        modal.find('.ps-modal__close, .ps-btn--close').on('click', function (e) {
            e.preventDefault();
            $(this).closest('.ps-modal').removeClass('active');
        });
        $('.ps-modal-link').on('click', function (e) {
            e.preventDefault();
            var target = $(this).attr('href');
            $(target).addClass('active');
            $("body").css('overflow-y', 'hidden');
        });
        $('.ps-modal').on("click", function (event) {
            if (!$(event.target).closest(".ps-modal__container").length) {
                modal.removeClass('active');
                $("body").css('overflow-y', 'auto');
            }
        });
    }

    function searchInit() {
        var searchbox = $('.ps-search');
        $('.ps-search-btn').on('click', function (e) {
            e.preventDefault();
            searchbox.addClass('active');
        });
        searchbox.find('.ps-btn--close').on('click', function (e) {
            e.preventDefault();
            searchbox.removeClass('active');
        });
    }

    function countDown() {
        var time = $(".ps-countdown");
        time.each(function () {
            var el = $(this),
                value = $(this).data('time');
            var countDownDate = new Date(value).getTime();
            var timeout = setInterval(function () {
                var now = new Date().getTime(),
                    distance = countDownDate - now;
                var days = Math.floor(distance / (1000 * 60 * 60 * 24)),
                    hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)),
                    minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)),
                    seconds = Math.floor((distance % (1000 * 60)) / 1000);
                el.find('.days').html(days);
                el.find('.hours').html(hours);
                el.find('.minutes').html(minutes);
                el.find('.seconds').html(seconds);
                if (distance < 0) {
                    clearInterval(timeout);
                    el.closest('.ps-section').hide();
                }
            }, 1000);
        });
    }

    function productFilterToggle() {
        $('.ps-filter__trigger').on('click', function (e) {
            e.preventDefault();
            var el = $(this);
            el.find('.ps-filter__icon').toggleClass('active');
            el.closest('.ps-filter').find('.ps-filter__content').slideToggle();
        });
        if ($('.ps-sidebar--home').length > 0) {
            $('.ps-sidebar--home > .ps-sidebar__header > a').on('click', function (e) {
                e.preventDefault();
                $(this).closest('.ps-sidebar--home').children('.ps-sidebar__content').slideToggle();
            })
        }
    }

    function mainSlider() {
        var homeBanner = $('.ps-carousel--animate');
        homeBanner.slick({
            autoplay: true,
            speed: 1000,
            lazyLoad: 'progressive',
            arrows: false,
            fade: true,
            dots: true,
            prevArrow: "<i class='slider-prev ba-back'></i>",
            nextArrow: "<i class='slider-next ba-next'></i>"
        });
    }

    function subscribePopup() {
        var subscribe = $('#subscribe'),
            time = subscribe.data('time');
        setTimeout(function () {
            if (subscribe.length > 0) {
                subscribe.addClass('active');
                $('body').css('overflow', 'hidden');
            }
        }, time);
        $('.ps-popup__close').on('click', function (e) {
            e.preventDefault();
            $(this).closest('.ps-popup').removeClass('active');
            $('body').css('overflow', 'auto');
        });
        $('#subscribe').on("click", function (event) {
            if (!$(event.target).closest(".ps-popup__content").length) {
                subscribe.removeClass('active');
                $("body").css('overflow-y', 'auto');
            }
        });
    }

    function stickySidebar() {
        var sticky = $('.ps-product--sticky'),
            stickySidebar, checkPoint = 992,
            windowWidth = $(window).innerWidth();
        if (sticky.length > 0) {
            stickySidebar = new StickySidebar('.ps-product__sticky .ps-product__info', {
                topSpacing: 20,
                bottomSpacing: 20,
                containerSelector: '.ps-product__sticky',
            });
            if ($('.sticky-2').length > 0) {
                var stickySidebar2 = new StickySidebar('.ps-product__sticky .sticky-2', {
                    topSpacing: 20,
                    bottomSpacing: 20,
                    containerSelector: '.ps-product__sticky',
                });
            }
            if (checkPoint > windowWidth) {
                stickySidebar.destroy();
                stickySidebar2.destroy();
            }
        } else {
            return false;
        }
    }

    function accordion() {
        var accordion = $('.ps-accordion');
        accordion.find('.ps-accordion__content').hide();
        $('.ps-accordion.active').find('.ps-accordion__content').show();
        accordion.find('.ps-accordion__header').on('click', function (e) {
            e.preventDefault();
            if ($(this).closest('.ps-accordion').hasClass('active')) {
                $(this).closest('.ps-accordion').removeClass('active');
                $(this).closest('.ps-accordion').find('.ps-accordion__content').slideUp(350);

            } else {
                $(this).closest('.ps-accordion').addClass('active');
                $(this).closest('.ps-accordion').find('.ps-accordion__content').slideDown(350);
                $(this).closest('.ps-accordion').siblings('.ps-accordion').find('.ps-accordion__content').slideUp();
            }
            $(this).closest('.ps-accordion').siblings('.ps-accordion').removeClass('active');
            $(this).closest('.ps-accordion').siblings('.ps-accordion').find('.ps-accordion__content').slideUp();
        });
    }

    function progressBar() {
        var progress = $('.ps-progress');
        progress.each(function (e) {
            var value = $(this).data('value');
            $(this).find('span').css({
                width: value + "%"
            })
        });
    }

    function customScrollbar() {
        $('.ps-custom-scrollbar').each(function () {
            var height = $(this).data('height');
            $(this).slimScroll({
                height: height + 'px',
                alwaysVisible: true,
                color: '#000000',
                size: '6px',
                railVisible: true,
            });
        })
    }

    function select2Cofig() {
        $('select.ps-select').select2({
            placeholder: $(this).data('placeholder'),
            minimumResultsForSearch: -1
        });

        $('.select2').select2();
    }

    function carouselNavigation() {
        var prevBtn = $('.ps-carousel__prev'),
            nextBtn = $('.ps-carousel__next');
        prevBtn.on('click', function (e) {
            e.preventDefault();
            var target = $(this).attr('href');
            $(target).trigger('prev.owl.carousel', [1000]);
        });
        nextBtn.on('click', function (e) {
            e.preventDefault();
            var target = $(this).attr('href');
            $(target).trigger('next.owl.carousel', [1000]);
        });
    }

    function dateTimePicker() {
        $('.ps-datepicker').datepicker();
    }


    // Funcion de paginacion
    function pagination() {
        var target = $('.pagination');

        if (target.length > 0) {
            target.each(function () {
                var el = $(this),
                    totalPages = el.data("total-pages"),
                    currentPage = el.data("current-page"),
                    urlPage = el.data("url-page");

                el.twbsPagination({
                    totalPages: totalPages,
                    startPage: currentPage,
                    visiblePages: 3,
                    first: '<i class="fas fa-angle-double-left"></i>',
                    last: '<i class="fas fa-angle-double-right"></i>',
                    prev: '<i class="fas fa-angle-left"></i>',
                    next: '<i class="fas fa-angle-right"></i>'
                }).on("page", function (evt, page) {

                    if (urlPage.includes("&", 1)) {
                        urlPage = urlPage.replace(`&${currentPage}`, `&${page}`);
                        window.location = `${urlPage}#showcase`;
                    } else {
                        window.location = `${urlPage}&${page}#showcase`;
                    }
                })
            })
        }
    }

    // Funcion preload

    const preload = function () {
        var preloadFalse = $(".preload-false");
        let preloadTrue = $(".preload-true");

        if (preloadFalse.length > 0) {

            preloadFalse.each(function (i) {

                let element = $(this);
                $(element).ready(function () {

                    $(preloadTrue[i]).remove();
                    $(element).css({ "opacity": 1, "height": "auto" });

                });
            });
        }
    }


    // Validacion formularios bootstrap 4

    function validateBS4() {

        (function () {
            'use strict';
            window.addEventListener('load', function () {
                // Get the forms we want to add validation styles to
                var forms = document.getElementsByClassName('needs-validation');
                // Loop over them and prevent submission
                var validation = Array.prototype.filter.call(forms, function (form) {
                    form.addEventListener('submit', function (event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();

    }

    // Capturar el email login

    const rememberLogin = function () {
        if (localStorage.getItem("emailRem") != null) {
            $('[name="login-email"]').val(localStorage.getItem("emailRem"));
        }

        if (localStorage.getItem("checkRem") != null && localStorage.getItem("checkRem")) {
            $('#remember-me').attr("checked", true);
        }
    }

    // DataTable

    const dataTable = function () {
        // Datatable lado cliente
        let targetDtClient = $(".dt-responsive.dt-client");

        if (targetDtClient.length > 0) {
            $(targetDtClient).DataTable({

                "order": []

            });
        }

        // Datatable lado servidor para productos

        let targetDtServerProducts = $(".dt-responsive.dt-server-products");

        if (targetDtServerProducts.length > 0) {
            $(targetDtServerProducts).DataTable({

                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": $("#path").val() + "ajax/data-products.php?id-store=" + $("#id-store").val(),
                    "type": "POST"
                },
                "columns": [
                    { "data": "id_product" },
                    { "data": "actions", "orderable": false },
                    { "data": "feedback", "orderable": false },
                    { "data": "state", "orderable": false },
                    { "data": "image_product", "orderable": false },
                    { "data": "name_product" },
                    { "data": "name_category" },
                    { "data": "name_subcategory" },
                    { "data": "price_product" },
                    { "data": "shipping_product" },
                    { "data": "stock_product" },
                    { "data": "delivery_time_product" },
                    { "data": "offer_product", "orderable": false },
                    { "data": "summary_product", "orderable": false },
                    { "data": "specifications_product", "orderable": false },
                    { "data": "details_product", "orderable": false },
                    { "data": "description_product", "orderable": false },
                    { "data": "gallery_product", "orderable": false },
                    { "data": "top_banner_product", "orderable": false },
                    { "data": "default_banner_product", "orderable": false },
                    { "data": "horizontal_slider_product", "orderable": false },
                    { "data": "vertical_slider_product", "orderable": false },
                    { "data": "video_product", "orderable": false },
                    { "data": "tags_product", "orderable": false },
                    { "data": "views_product" },
                    { "data": "sales_product" },
                    { "data": "reviews_product", "orderable": false },
                    { "data": "date_created_product" }
                ]
            });
        }


        // Datatable lado servidor para ordenes

        let targetDtServerOrders = $(".dt-responsive.dt-server-orders");

        if (targetDtServerOrders.length > 0) {
            $(targetDtServerOrders).DataTable({

                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": $("#path").val() + "ajax/data-orders.php?id-store=" + $("#id-store").val() + "&token=" + localStorage.getItem("token_user"),
                    "type": "POST"
                },
                "columns": [
                    { "data": "id_order" },
                    { "data": "status_order" },
                    { "data": "displayname_user" },
                    { "data": "email_order" },
                    { "data": "country_order" },
                    { "data": "city_order" },
                    { "data": "address_order", "orderable": false },
                    { "data": "phone_order", "orderable": false },
                    { "data": "name_product" },
                    { "data": "quantity_order" },
                    { "data": "details_order", "orderable": false },
                    { "data": "price_order" },
                    { "data": "process_order", "orderable": false },
                    { "data": "date_created_order" }
                ]
            });
        }


        // Datatable lado servidor para disputas

        let targetDtServerDisputes = $(".dt-responsive.dt-server-disputes");

        if (targetDtServerDisputes.length > 0) {
            $(targetDtServerDisputes).DataTable({

                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": $("#path").val() + "ajax/data-disputes.php?id-store=" + $("#id-store").val() + "&token=" + localStorage.getItem("token_user"),
                    "type": "POST"
                },
                "columns": [
                    { "data": "id_dispute" },
                    { "data": "id_order_dispute" },
                    { "data": "displayname_user" },
                    { "data": "email_user" },
                    { "data": "content_dispute", "orderable": false },
                    { "data": "answer_dispute", "orderable": false },
                    { "data": "date_answer_dispute" },
                    { "data": "date_created_dispute" }
                ]
            });
        }

        // Datatable lado servidor para mensajes

        let targetDtServerMessages = $(".dt-responsive.dt-server-messages");

        if (targetDtServerMessages.length > 0) {
            $(targetDtServerMessages).DataTable({

                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": $("#path").val() + "ajax/data-messages.php?id-store=" + $("#id-store").val() + "&token=" + localStorage.getItem("token_user"),
                    "type": "POST"
                },
                "columns": [
                    { "data": "id_message" },
                    { "data": "name_product" },
                    { "data": "displayname_user" },
                    { "data": "email_user" },
                    { "data": "content_message", "orderable": false },
                    { "data": "answer_message", "orderable": false },
                    { "data": "date_answer_message" },
                    { "data": "date_created_message" }
                ]
            });
        }

        // Datatable lado servidor para las ventas

        let targetDtServerSales = $(".dt-responsive.dt-server-sales");

        if (targetDtServerSales.length > 0) {

            let betweeen1 = $("[name='between1']").val();
            let betweeen2 = $("[name='between2']").val();

            $(targetDtServerSales).DataTable({

                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": $("#path").val() + "ajax/data-sales.php?id-store=" + $("#id-store").val() + "&between1=" + betweeen1 + "&between2=" + betweeen2 + "&token=" + localStorage.getItem("token_user"),
                    "type": "POST"
                },
                "columns": [
                    { "data": "date_created_sale" },
                    { "data": "name_product_sale" },
                    { "data": "quantity_order" },
                    { "data": "unit_price_sale" },
                    { "data": "commission_sale" },
                    { "data": "total", "orderable": false },
                ]
            });
        }
    }


    // Summernote

    const summer = function () {
        let target = $(".summernote");

        if (target.length > 0) {
            $(target).summernote({
                placeholder: "",
                tabSize: 2,
                height: 400,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
        }
    }

    // Editar contenido de summernote

    const editSummer = function () {
        let target = $(".edit-summernote");

        if (target.length > 0) {

            target.each(function (i) {

                let el = $(this);

                $(el).ready(function () {

                    let settings = {
                        "url": $("#url-api").val() + `products?linkTo=id_product&equalTo=${$(el).attr("id-product")}&select=description_product`,
                        "method": "GET",
                        "timeout": 0,
                    };

                    $.ajax(settings).done(function (response) {

                        if (response.status == 200) {
                            $(el).summernote('pasteHTML', response.result[0].description_product)
                        }

                    });

                });

            });

        }
    }


    // Shape share
    const share = function () {
        let target = $(".social-share");

        if (target.length > 0) {
            $(target).shapeShare();
        }
    }

    // Ejecutar funciones globales

    $(function () {
        backgroundImage();
        owlCarouselConfig();
        siteToggleAction();
        subMenuToggle();
        masonry('.ps-masonry');
        productFilterToggle();
        tabs();
        slickConfig();
        productLightbox();
        rating();
        backToTop();
        stickyHeader();
        filterSlider();
        mapConfig();
        modalInit();
        searchInit();
        countDown();
        mainSlider();
        parallax();
        stickySidebar();
        accordion();
        progressBar();
        customScrollbar();
        select2Cofig();
        carouselNavigation();
        dateTimePicker();
        $('[data-toggle="tooltip"]').tooltip();
        pagination();
        preload();
        validateBS4();
        rememberLogin();
        dataTable();
        summer();
        editSummer();
        share();
    });

    $(window).on('load', function () {
        $('body').addClass('loaded');
        // subscribePopup();
    });

    $.scrollUp({
        scrollText: '',
        scrollSpeed: 1000
    })

})(jQuery);

// Funciones propias

// Funcion para evitar que se envie un formulario


// document.getElementById('form-message').addEventListener("submit", function (event) {
//     let idUser = $("[name='id-user']").val();

//     if (idUser == "") {
//         notieAlert(3, "you must be logged in to send a message");
//         event.preventDefault();
//     }
// })

// Funcion para ordenar productos

const sortProducts = function (event) {

    let url = event.target.value.split("+")[0];
    let sort = event.target.value.split("+")[1];

    let endUrl = url.split("&")[0];

    window.location = `${endUrl}&1&${sort}#showcase`;
}

// Funcion para crear cookies en javascript

const setCookie = function (name, value, exp) {
    let now = new Date();

    now.setTime(now.getTime() + (exp * 24 * 60 * 60 * 1000));

    let expDate = `expires=${now.toUTCString()}`;

    document.cookie = `${name}=${value}; ${expDate}`;
}

// Funcion para almacenar en cookie la tabulacion de la vitrina

$(document).on("click", ".ps-tab-list li", function () {
    setCookie("tab", $(this).attr("type"), 1);
});

// Funcion para buscar productos

$(document).on("click", ".btn-search", function () {
    let path = $(this).attr("path");
    console.log(path);
    let search = $(this).parent().children(".input-search").val().toLowerCase();

    let match = /^[a-z0-9ñÑáéíóú ]*$/;

    if (match.test(search)) {
        let searchTest = search.replace(/[ ]/g, "_");
        searchTest = searchTest.replace(/[ñ]/g, "n");
        searchTest = searchTest.replace(/[á]/g, "a");
        searchTest = searchTest.replace(/[é]/g, "e");
        searchTest = searchTest.replace(/[í]/g, "i");
        searchTest = searchTest.replace(/[ó]/g, "o");
        searchTest = searchTest.replace(/[ú]/g, "u");

        window.location = `${path}${searchTest}`;
    } else {
        $(this).parent().children(".input-search").val("");
    }
});

// Funcion para buscar con la tecla enter

let inputSearch = $(".input-search");
let btnSearch = $(".btn-search");

for (let i = 0; i < inputSearch.length; i++) {
    $(inputSearch[i]).keyup(function (event) {
        event.preventDefault();

        if (event.keyCode == 13 && $(inputSearch[i]).val() != "") {
            let path = $(btnSearch[i]).attr("path");
            let search = $(this).val().toLowerCase();

            let match = /^[a-z0-9ñÑáéíóú ]*$/;

            if (match.test(search)) {
                let searchTest = search.replace(/[ ]/g, "_");
                searchTest = searchTest.replace(/[ñ]/g, "n");
                searchTest = searchTest.replace(/[á]/g, "a");
                searchTest = searchTest.replace(/[é]/g, "e");
                searchTest = searchTest.replace(/[í]/g, "i");
                searchTest = searchTest.replace(/[ó]/g, "o");
                searchTest = searchTest.replace(/[ú]/g, "u");

                window.location = `${path}${searchTest}`;
            } else {
                $(this).val("");
            }
        }
    })
}

// Funcion para cambiar la cantidad de compra del producto desde el info producto

const changeQuantityProductInfo = function (quantity, move, stock, index) {
    let number = 1;

    if (Number(quantity) > stock - 1) {
        quantity = stock - 1;
    }

    if (move == "up") {
        number = Number(quantity) + 1;
    }

    if (move == "down" && Number(quantity) > 1) {
        number = Number(quantity) - 1;
    }

    $("#quantity-" + index).val(number);

    $("[quantity-sc]").attr("quantity-sc", number);
}

// Funcion para cambiar la cantidad de compra del producto desde el carrito de compras

const changeQuantityShoppingCart = function (quantity, move, stock, index) {
    let number = 1;

    if (Number(quantity) > stock - 1) {
        quantity = stock - 1;
    }

    if (move == "up") {
        number = Number(quantity) + 1;
    }

    if (move == "down" && Number(quantity) > 1) {
        number = Number(quantity) - 1;
    }

    $("#quantity-" + index).val(number);

    $("[quantity-sc]").attr("quantity-sc", number);

    totalP(index);
}

// Funcion para validamos imagenes

const validateImageJS = function (event, input) {
    // Obtenemos la imagen subida
    let image = event.target.files[0];

    // Validamos el formato
    if (image['type'] !== "image/jpeg" && image['type'] !== "image/png") {

        sweetAlert("error", "The image must be in JPG or PNG format");
        return;

    }

    // Validamos el tamaño
    else if (image['size'] > 2000000) {
        sweetAlert("error", "The image must not weigh more than 2MB");
        return
    }

    // Mostramos la imagen temporal
    else {
        let data = new FileReader();

        data.readAsDataURL(image);

        $(data).on("load", function (event) {
            let path = event.target.result;
            $("." + input).attr("src", path);
        });

    }
}

// Funcion para validar correo electronico repetido

function validateDataRepeat(event, type) {

    let table;
    let linkTo;
    let select;

    if (type == "email") {
        table = "users";
        linkTo = "email_user";
        select = "email_user,method_user";
    }

    if (type == "store") {
        table = "stores";
        linkTo = "name_store";
        select = "name_store";
    }

    if (type == "product") {
        table = "products";
        linkTo = "name_product";
        select = "name_product";
    }

    let settings = {
        "url": $("#url-api").val() + `${table}?linkTo=${linkTo}&equalTo=${event.target.value}&select=${select}`,
        "method": "GET",
        "timeout": 0,
    };

    // Cuando la peticion AJAX devuelve error
    $.ajax(settings).error(function (response) {

        if (response.status == 404) {
            if (type == "email") {
                validateJS(event, "email");
            }

            if (type == "product") {
                validateJS(event, "text&number");
                createUrl(event, "url-product");
            }

            if (type == "store") {
                validateJS(event, "text&number");
                createUrl(event, "url-store");
            }
        }

    });

    $.ajax(settings).done(function (response) {
        if (response.status == 200) {
            $(event.target).parent().addClass("was-validated");

            if (type == "email") {
                $(event.target).parent().children(".invalid-feedback").html("The email already exists in the database with the method" + response.result[0].method_user);
            }

            if (type == "store" || type == "product") {
                $(event.target).parent().children(".invalid-feedback").html(`The name ${type} already existis in the database and was registered`);
            }

            event.target.value = "";

            return;
        }
    });
}

// Funcion para validar formulario

function validateJS(event, type) {

    // Validamos texto
    if (type == "text") {

        const pattern = /^[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}$/;

        if (!pattern.test(event.target.value)) {
            $(event.target).parent().addClass("was-validated");
            $(event.target).parent().children(".invalid-feedback").html("Do not use numbers or special characters");
            event.target.value = "";

            return;
        }
    }

    // Validamos numeros
    if (type == "numbers") {

        const pattern = /^[.\\,\\0-9]{1,}$/;

        if (!pattern.test(event.target.value)) {
            $(event.target).parent().addClass("was-validated");
            $(event.target).parent().children(".invalid-feedback").html("Do not use letters or special characters");
            event.target.value = "";

            return;
        }
    }

    // Validamos texto y numeros
    if (type == "text&number") {

        const pattern = /^[0-9A-Za-zñÑáéíóúÁÉÍÓÚ ]{3,}$/;

        if (!pattern.test(event.target.value)) {
            $(event.target).parent().addClass("was-validated");
            $(event.target).parent().children(".invalid-feedback").html("It must contain a minimum of 3 characters and not use special characters");
            event.target.value = "";

            return;
        }
    }

    // Validamos correos electronicos
    if (type == "email") {

        const pattern = /^[^0-9][.a-zA-Z0-9_]+([.][.a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/;

        if (!pattern.test(event.target.value)) {
            $(event.target).parent().addClass("was-validated");
            $(event.target).parent().children(".invalid-feedback").html("The email is misspelled");
            event.target.value = "";

            return;
        }
    }

    // Validamos contraseñas
    if (type == "password") {

        const pattern = /^[#\\=\\$\\;\\*\\_\\?\\¿\\!\\¡\\:\\.\\,\\0-9a-zA-Z]{4,}$/;

        if (!pattern.test(event.target.value)) {
            $(event.target).parent().addClass("was-validated");
            $(event.target).parent().children(".invalid-feedback").html("Password invalid");
            event.target.value = "";

            return;
        }
    }

    // Validamos telefono
    if (type == "phone") {

        const pattern = /^[-\\(\\)\\0-9 ]{1,}$/;

        if (!pattern.test(event.target.value)) {
            $(event.target).parent().addClass("was-validated");
            $(event.target).parent().children(".invalid-feedback").html("The phone is mispelled");
            event.target.value = "";

            return;
        }
    }

    // Validamos parrafos
    if (type == "paragraphs") {

        const pattern = /^[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}$/;

        if (!pattern.test(event.target.value)) {
            $(event.target).parent().addClass("was-validated");
            $(event.target).parent().children(".invalid-feedback").html("The input is mispelled");
            event.target.value = "";

            return;
        }
    }
}

// Funcion para recordar email en el login

const remember = function (event) {
    if (event.target.checked) {
        localStorage.setItem("emailRem", $('[name="login-email"]').val());
        localStorage.setItem("checkRem", true);
    } else {
        localStorage.removeItem("emailRem");
        localStorage.removeItem("checkRem");
    }
}

// Funcion para agregar productos a la lista de deseos

const addWishlist = function (urlProduct, urlApi) {

    // Validamos si existe un localstorage para validar que el usuario este logueado
    if (localStorage.getItem("token_user") != null) {

        let token = localStorage.getItem("token_user");

        // Revisamos que el token coincida con la base de datos
        let settings = {
            "url": urlApi + `users?linkTo=token_user&equalTo=${token}&select=id_user,wishlist_user`,
            "method": "GET",
            "timeout": 0,
        };

        // Cuando la peticion AJAX devuelve con error

        $.ajax(settings).error(function (response) {
            if (response.responseJSON.status == 404) {

                notieAlert(3, "The token user is not correct");
                return;
            }
        });

        // Cuando la peticion AJAX devuelve el resultado correcto

        $.ajax(settings).done(function (response) {
            if (response.status == 200) {

                let id = response.result[0].id_user;
                let wishlist = JSON.parse(response.result[0].wishlist_user);

                let noRepeat = 0;

                // Preguntamos primero si existe un producto en la lista de deseos

                if (wishlist != null && wishlist.length > 0) {
                    wishlist.forEach(list => {

                        if (list == urlProduct) {
                            noRepeat--;
                        } else {
                            noRepeat++;
                        }

                    });


                    // Preguntamos si no ha agregado este producto a la lista de deseos

                    if (wishlist.length != noRepeat) {
                        notieAlert(3, "This product exists on your wishlist");
                    } else {

                        wishlist.push(urlProduct);

                        let settings = {
                            "url": urlApi + `users?id=${id}&nameId=id_user&token=${token}`,
                            "method": "PUT",
                            "timeout": 0,
                            "headers": {
                                "Content-Type": "application/x-www-form-urlencode"
                            },
                            "data": {
                                "wishlist_user": JSON.stringify(wishlist)
                            }
                        };

                        // Cuando la peticion AJAX devuelve el resultado correcto

                        $.ajax(settings).done(function (response) {

                            if (response.status == 200) {

                                let totalWishlist = Number($(".total-wishlist").html());

                                $(".total-wishlist").html(totalWishlist + 1);

                                notieAlert(1, "Product added to wishlist");
                            }

                        })
                    }

                } else {
                    // Cuando no exista lista de deseos inicialmente
                    let settings = {
                        "url": urlApi + `users?id=${id}&nameId=id_user&token=${token}`,
                        "method": "PUT",
                        "timeout": 0,
                        "headers": {
                            "Content-Type": "application/x-www-form-urlencode"
                        },
                        "data": {
                            "wishlist_user": '["' + urlProduct + '"]'
                        }
                    };

                    // Cuando la peticion AJAX devuelve el resultado correcto

                    $.ajax(settings).done(function (response) {

                        if (response.status == 200) {

                            let totalWishlist = Number($(".total-wishlist").html());

                            $(".total-wishlist").html(totalWishlist + 1);

                            notieAlert(1, "Product added to wishlist");
                        }

                    })
                }
            }
        });

    } else {
        notieAlert(3, "The user must be logged in.");
    }
}


// Funcion para agregar dos productos a la lista de deseos

const addWishlist2 = function (urlProduct1, urlProduct2, urlApi) {
    addWishlist(urlProduct1, urlApi);

    setTimeout(() => {
        addWishlist(urlProduct2, urlApi);
    }, 1000);
}


// Funcion para remover productos de la lista de deseos

const removeWishlist = function (urlProduct, urlApi, urlDomain) {
    sweetAlert("confirm", "Are you sure to delete this item?").then(resp => {
        if (resp) {

            // Revisar que el token coincida con la base de datos
            let settings = {
                "url": urlApi + `users?linkTo=token_user&equalTo=${localStorage.getItem("token_user")}&select=id_user,wishlist_user`,
                "method": "GET",
                "timeout": 0,
            };

            $.ajax(settings).done(function (response) {

                if (response.status == 200) {
                    let id = response.result[0].id_user;
                    let wishlist = JSON.parse(response.result[0].wishlist_user);

                    wishlist.forEach((list, index) => {

                        if (list == urlProduct) {
                            wishlist.splice(index, 1);
                        }
                    });

                    let settings = {
                        "url": urlApi + `users?id=${id}&nameId=id_user&token=${localStorage.getItem("token_user")}`,
                        "method": "PUT",
                        "timeout": 0,
                        "headers": {
                            "Content-Type": "application/x-www-form-urlencode"
                        },
                        "data": {
                            "wishlist_user": JSON.stringify(wishlist)
                        }
                    };

                    $.ajax(settings).done(function (response) {

                        if (response.status == 200) {

                            let totalWishlist = Number($(".total-wishlist").html());

                            $(".total-wishlist").html(totalWishlist - 1);

                            sweetAlert("success", "Product delete to wishlist", `${urlDomain}account&wishlist`);
                        }

                    })
                }
            });
        }
    });
}


// Funcion para agregar productos al carrito de compras

const addShoppingCart = function (urlProduct, urlApi, currentUrl, tag) {

    // Traer la informacion relacionada a ese producto
    let settings = {
        "url": urlApi + `products?linkTo=url_product&equalTo=${urlProduct}&select=stock_product,specifications_product`,
        "method": "GET",
        "timeout": 0
    }

    $.ajax(settings).done(function (response) {

        if (response.status == 200) {

            // Preguntamos que el producto tenga stock

            if (response.result[0].stock_product == 0) {
                notieAlert(3, "This product is out of stock");
                return;
            }

            // Validamos existencia de detalles

            if (tag.getAttribute("details-sc") != "") {
                var details = tag.getAttribute("details-sc");
            } else {
                var details = "";
            }

            // Validamos existencia de cantidad

            if (tag.getAttribute("quantity-sc") != "") {
                var quantity = tag.getAttribute("quantity-sc");
            } else {
                var quantity = 1;
            }

            // Preguntamos si detalles viene vacio

            if (details == "") {
                if (response.result[0].specifications_product != null) {

                    let detailsProduct = JSON.parse(response.result[0].specifications_product);
                    details = '[{';

                    for (const i in detailsProduct) {
                        let propety = Object.keys(detailsProduct[i]).toString();

                        details += `"${propety}": "${detailsProduct[i][propety][0]}",`;
                    }

                    details = details.slice(0, -1)

                    details += '}]';
                }
            } else {

                let newDetail = JSON.parse(details);

                if (response.result[0].specifications_product != null) {

                    let detailsProduct = JSON.parse(response.result[0].specifications_product);
                    details = '[{';

                    for (const i in detailsProduct) {
                        let propety = Object.keys(detailsProduct[i]).toString();

                        details += `"${propety}": "${detailsProduct[i][propety][0]}",`;
                    }

                    details = details.slice(0, -1)

                    details += '}]';
                }

                for (const i in JSON.parse(details)[0]) {

                    if (newDetail[0][i] == undefined) {

                        Object.assign(newDetail[0], { [i]: JSON.parse(details)[0][i] });

                    }
                }

                details = JSON.stringify(newDetail);
            }

            // Preguntamos si ya existe una cookie de lista de shopping cart

            let myCookies = document.cookie;
            let listCookies = myCookies.split(";");
            let count = 0;

            for (const i in listCookies) {
                list = listCookies[i].search("list-sc");

                // Si list es superior a -1 es porque encontro la cookie

                if (list > -1) {
                    count--;
                    arrayListSC = JSON.parse(listCookies[i].split("=")[1]);
                } else {
                    count++;
                }
            }

            // Trabajamos sobre la cookie que ya existe

            if (count != listCookies.length) {
                if (arrayListSC != undefined) {

                    // Preguntar si el producto existe

                    let count = 0;
                    let index = null;

                    for (const i in arrayListSC) {

                        if (arrayListSC[i].product == urlProduct &&
                            arrayListSC[i].details == details.toString()) {

                            count--;
                            index = i;
                        } else {
                            count++;
                        }
                    }

                    if (count == arrayListSC.length) {
                        arrayListSC.push({
                            "product": urlProduct,
                            "details": details,
                            "quantity": quantity
                        });
                    } else {
                        arrayListSC[index].quantity = Number(arrayListSC[index].quantity);
                        quantity = Number(quantity);

                        arrayListSC[index].quantity += quantity;
                    }



                    // Creamos la cookie

                    setCookie("list-sc", JSON.stringify(arrayListSC), 1);
                    sweetAlert("success", "Product added to Shopping Cart", currentUrl);
                }
            } else {

                // Creamos una cookie desde cero

                var arrayListSC = [];

                arrayListSC.push({
                    "product": urlProduct,
                    "details": details,
                    "quantity": quantity
                });

                setCookie("list-sc", JSON.stringify(arrayListSC), 1);
                sweetAlert("success", "Product added to Shopping Cart", currentUrl);

            }
        }
    });
}


// Funcion para remover productos del carrito de compras

const removeShoppingCart = function (urlProduct, currentUrl) {

    sweetAlert("confirm", "Are you sure to delete this item?").then(resp => {

        if (resp) {
            // Preguntamos si ya existe una cookie de lista de shopping cart

            let myCookies = document.cookie;
            let listCookies = myCookies.split(";");
            let count = 0;

            for (const i in listCookies) {
                list = listCookies[i].search("list-sc");

                // Si list es superior a -1 es porque encontro la cookie

                if (list > -1) {
                    count--;
                    arrayListSC = JSON.parse(listCookies[i].split("=")[1]);
                } else {
                    count++;
                }
            }

            // Trabajamos sobre la cookie que ya existe

            if (count != listCookies.length) {
                if (arrayListSC != "[]") {

                    arrayListSC.forEach((list, index) => {

                        if (list.product == urlProduct) {
                            arrayListSC.splice(index, 1);
                        }

                    });

                    setCookie("list-sc", JSON.stringify(arrayListSC), 1);
                    sweetAlert("success", "Product removed from Shopping Cart", currentUrl);

                }
            }
        }
    });
}


// Seleccionar detalles al producto

$(document).on("click", ".details", function () {

    let details = $(this).attr("detail-type");
    let value = $(this).attr("detail-value");

    let detailsLength = $(`.details.${details}`);

    for (let i = 0; i < detailsLength.length; i++) {
        $(detailsLength[i]).css({ "border": "1px solid #bbb" });
    }

    $(this).css({ "border": "3px solid #bbb" });


    // Preguntar si el usuario a agregado detalles
    if ($("[details-sc]").attr("details-sc") != "") {

        let detailsSC = JSON.parse($("[details-sc]").attr("details-sc"));

        for (const i in detailsSC) {
            detailsSC[i][details] = value;
            $("[details-sc]").attr("details-sc", JSON.stringify(detailsSC));

        }

    } else {
        $("[details-sc]").attr("details-sc", '[{\"' + details + '\":\"' + value + '\"}]')
    }

});

// Funcion para agregar dos productos al carrito de compras

const addShoppingCart2 = function (urlProduct1, urlProduct2, urlApi, currentUrl, tag) {
    addShoppingCart(urlProduct1, urlApi, currentUrl, tag);

    setTimeout(() => {
        addShoppingCart(urlProduct2, urlApi, currentUrl, tag);
    }, 1000);
}


// Definir subtotal y el total del carrito de compras

let price = $(".ps-product__price span");
let shipping = $(".shipping span");
let quantity = $(".quantity input");
let subtotal = $(".subtotal");
let totalPrice = $(".total-price span");
let listSC = $(".list-sc");

const totalP = function (index) {

    let total = 0;
    let arrayListSC = [];

    if (price.length > 0) {
        price.each(function (i) {

            // Calculando el precio de envio luego de cambiar la cantidad

            if (index != null) {
                $(shipping[index]).html((Number($(shipping[index]).attr("current-shipping")) * Number($(quantity[index]).val())));
            } else {

                // Calculando precio de envio inicial
                $(shipping[i]).html((Number($(shipping[i]).attr("current-shipping")) * Number($(quantity[i]).val())));
            }

            // Calculando los subtotales

            let sub = (Number($(price[i]).html()) * Number($(quantity[i]).val())) + Number($(shipping[i]).html());
            total += sub;

            $(subtotal[i]).html(sub.toFixed(2));

            // Definir lo que actualizaremos en la cookie
            arrayListSC.push({
                "product": $(listSC[i]).attr("url"),
                "details": $(listSC[i]).attr("details"),
                "quantity": $(quantity[i]).val()
            });
        });

        // Calculando el total
        $(totalPrice.html(total.toFixed(2)));

        // Actualizando la cookie del carrito de compras
        setCookie("list-sc", JSON.stringify(arrayListSC), 1);
    }
}

// Ejecutar el totalP unicamente cuando cargue la pagina del shopping cart
$(window).on("load", function () {

    let currentUrl = window.location.href;

    if (currentUrl.split("/")[3] == "shopping-cart") {
        totalP(null);
    }
});

// Agregar codigo telefonico

const changeCountry = function (event) {

    $(".dial-code").html(event.target.value.split("_")[1]);

}


// Capturando el total unicamente cuando se carga el dom
let total = $(".total-order").attr("total");


// Funcion para capturar metodo de pago

let paymentMethod = $('[name="payment-method"]').val();

const changePaymentMethod = function (event) {

    paymentMethod = event.target.value;

}


// Funcion para procesar el checkout

const checkout = function () {

    var forms = document.getElementsByClassName('needs-validation');

    var validation = Array.prototype.filter.call(forms, function (form) {

        if (form.checkValidity()) {
            return [""];
        }
    });

    if (validation.length > 0) {


        if (paymentMethod == "paypal") {

            setTimeout(() => {
                // Abrir ventana modal para incorporar el boton de pago de Paypal
                sweetAlert("html", `<div id="paypal-button-container"></div>`, null);

                // Renderizamos el boton de paypal
                window.paypal.Buttons({

                    // Creando la orden y definiendo el precio total a pagar
                    createOrder: function (data, actions) {
                        return actions.order.create({
                            purchase_units: [{
                                amount: {
                                    value: total
                                }
                            }]
                        });
                    },

                    // Cuando la transaccion fue completada y aprobada
                    onApprove: function (data, actions) {
                        return actions.order.capture().then(function (details) {

                            if (details.status == "COMPLETED") {

                                // Generar la orden en la base de datos en caso de que la orden este en estado COMPLETADO
                                newOrder("paypal", "pending", details.id, total);
                                sweetAlert('loading', 'Processing payment...');
                            }

                            return false;
                        });
                    },

                    // Cuando la transaccion fue cancelada
                    onCancel: function (data) {
                        sweetAlert("error", "The transaction has been canceled", null);
                        return false;
                    },

                    // Cuando ocurre un error en la transaccion
                    onError: function (err) {
                        sweetAlert("error", "An error ocurred while making the transaction", null);
                        return false;
                    }

                }).render("#paypal-button-container");
            }, 1000);





        }

        if (paymentMethod == "payu") {

            setTimeout(() => {
                newOrder("payu", "test", null, total);
            }, 1000);


        }

        if (paymentMethod == "mercado-pago") {

            let newTotal = 0;

            /*=============================================
            COnvertir a moneda local para Mercado Pago
            =============================================*/

            // https://free.currconv.com/api/v7/currencies?apiKey=[YOUR API KEY]

            // let settings = {
            //     "url": "https://free.currconv.com/api/v7/convert?q=USD_COP&compact=ultra&apiKey=cf2b1e499a7e50da66db",
            //     "method": "GET",
            //     "timeout": 0
            // };

            // $.ajax(settings).error(function (response) {
            //     if (response.status == 400) {

            //         sweetAlert("error", `Error converting local currency`, null);

            //         return;
            //     }
            // });

            // $.ajax(settings).done(function (response) {
            // newTotal = Math.round(response["USD_COP"] * total);

            // })


            const mp = new MercadoPago("TEST-e4ec5ed0-a296-4b27-a378-e89f9464734d");

            let formMP = `

                <img src="img/payment-method/mp_logo.png" style="width:200px" class="pb-3" />

                <form id="form-checkout" class"needs-validation" novalidate>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="far fa-credit-card"></i></span>
                        </div>
                        <div id="form-checkout__cardNumber" class="container form-control" required></div>
                    </div>

                    <div class="form-row">
                        
                        <div class="col">

                            <div class="input-group mb-3">
                                <div id="form-checkout__expirationDate" class="container form-control" required></div>
                            </div>

                        </div>

                        <div class="col">

                            <div class="input-group mb-3">
                                <div id="form-checkout__securityCode" class="container form-control" required></div>
                            </div>

                        </div>

                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="far fa-user"></i></span>
                        </div>
                        <input type="text" id="form-checkout__cardholderName" class="form-control" required />
                    </div>

                    <div class="input-group mb-3">
                        
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-university"></i></span>
                        </div>

                        <select id="form-checkout__issuer" class="form-control" required></select>

                    </div>

                    <select id="form-checkout__installments" class="form-control mb-3" required></select>

                    <select id="form-checkout__identificationType" class="form-control mb-3" required></select>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                        </div>

                        <input type="text" id="form-checkout__identificationNumber" class="form-control"  required/>

                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                        </div>

                        <input type="email" id="form-checkout__cardholderEmail" class="form-control" required />

                    </div>

                    <button type="submit" id="form-checkout__submit" class="btn btn-primary btn-lg btn-block">Pagar</button>

                    <div class="input-group mb-3">
                        <progress value="0" class="mt-3 w-100 progress-bar">Cargando...</progress>
                    </div>

                </form>`;


            setTimeout(() => {

                sweetAlert("html", formMP, null);

                const cardForm = mp.cardForm({
                    amount: "10000",
                    iframe: true,
                    form: {
                        id: "form-checkout",
                        cardNumber: {
                            id: "form-checkout__cardNumber",
                            placeholder: "Numero de tarjeta",
                        },
                        expirationDate: {
                            id: "form-checkout__expirationDate",
                            placeholder: "MM/YY",
                        },
                        securityCode: {
                            id: "form-checkout__securityCode",
                            placeholder: "CVV/CVC",
                        },
                        cardholderName: {
                            id: "form-checkout__cardholderName",
                            placeholder: "Titular de la tarjeta",
                        },
                        issuer: {
                            id: "form-checkout__issuer",
                            placeholder: "Banco emisor",
                        },
                        installments: {
                            id: "form-checkout__installments",
                            placeholder: "Cuotas",
                        },
                        identificationType: {
                            id: "form-checkout__identificationType",
                            placeholder: "Tipo de documento",
                        },
                        identificationNumber: {
                            id: "form-checkout__identificationNumber",
                            placeholder: "Número del documento",
                        },
                        cardholderEmail: {
                            id: "form-checkout__cardholderEmail",
                            placeholder: "E-mail",
                        },
                    },
                    callbacks: {
                        onFormMounted: error => {
                            if (error) return sweetAlert("error", "Error processing payment", null);
                            console.log("Form mounted");
                        },
                        onSubmit: event => {
                            event.preventDefault();

                            const {
                                paymentMethodId: payment_method_id,
                                issuerId: issuer_id,
                                cardholderEmail: email,
                                amount,
                                token,
                                installments,
                                identificationNumber,
                                identificationType,
                            } = cardForm.getCardFormData();

                            setTimeout(() => {

                                let response = {
                                    token,
                                    issuer_id,
                                    payment_method_id,
                                    transaction_amount: Number(amount),
                                    installments: Number(installments),
                                    payer: {
                                        email,
                                        identification: {
                                            type: identificationType,
                                            number: identificationNumber,
                                        },
                                    },
                                };

                                sweetAlert('loading', 'Processing payment...');

                                newOrder("mercado-pago", "test", null, response);

                            }, 1000);
                        },
                        onFetching: (resource) => {
                            console.log("Fetching resource: ", resource);

                            // Animate progress bar
                            const progressBar = document.querySelector(".progress-bar");
                            progressBar.removeAttribute("value");

                            return () => {
                                progressBar.setAttribute("value", "0");
                            };
                        }
                    },
                });

            }, 1000);


        }

        return false;

    } else {
        return false;
    }
}


// Dar formato a fecha

const formatDate = function (date) {
    let day = date.getDate();
    let month = date.getMonth() + 1;
    let year = date.getFullYear();

    return `${year}-${month}-${day}`;
}

// Crear la orden para base de datos

// Capturar la url de la tienda
let urlStoreClass = $(".url-store");
let urlStore = [];

urlStoreClass.each(i => {
    urlStore.push($(urlStoreClass[i]).val());
});


// Capturare el id del usuario
let idUser = $("#id-user").val();


// Capturar el id del producto
let idProductClass = $(".id-product");
let idProduct = [];

idProductClass.each(i => {
    idProduct.push($(idProductClass[i]).val());
});


// Capturar los detalles de la orden
let detailsOrderClass = $(".details-order");
let detailsOrder = [];

detailsOrderClass.each(i => {
    detailsOrder.push($(detailsOrderClass[i]).html().replace(/\s+/gi, ''));
});


// Capturar la cantidad de la orden
let quantityOrderClass = $(".quantity-order");
let quantityOrder = [];

quantityOrderClass.each(i => {
    quantityOrder.push($(quantityOrderClass[i]).html());
});


// Capturar el precio de la orden
let salesProductClass = $(".sales-product");
let salesProduct = [];

salesProductClass.each(i => {
    salesProduct.push($(salesProductClass[i]).val());
});

// Capturar las ventas del producto
let stockProductClass = $(".stock-product");
let stockProduct = [];

stockProductClass.each(i => {
    stockProduct.push($(stockProductClass[i]).val());
});

// Capturar el stock del producto
let priceOrderClass = $(".price-order");
let priceOrder = [];

priceOrderClass.each(i => {
    priceOrder.push($(priceOrderClass[i]).html());
});

// Capturar tiempo de entrega del producto
let deliveryTimeClass = $(".delivery-time");
let deliveryTime = [];

deliveryTimeClass.each(i => {
    deliveryTime.push($(deliveryTimeClass[i]).val());
});


const newOrder = function (payMethod, payStatus, payId, payTotal) {

    // Capturar primero el id de la tienda
    let idStoreClass = $(".id-store");
    let idStore = [];

    idStoreClass.each(i => {
        idStore.push($(idStoreClass[i]).val());
    });

    // Capturar informacion del usuario
    let emailOrder = $("#email-order").val();
    let countryOrder = $("#country-order").val().split("_")[0];
    let cityOrder = $("#city-order").val();
    let phoneOrder = $("#country-order").val().split("_")[1] + "_" + $("#phone-order").val();
    let addressOrder = $("#address-order").val();
    let infoOrder = $("#info-order").val();



    // Preguntamos si ya existe una cookie de cupones

    let myCookies = document.cookie;
    let listCookies = myCookies.split(";");

    let arrayCouponsMP = [];

    for (const f in listCookies) {
        list = listCookies[f].search("coupons-mp");

        // Si list es mayor a -1 es porque si existe la cookie
        if (list > -1) {
            let cuponsMP = listCookies[f].split("=")[1];
            arrayCouponsMP = JSON.parse(decodeURIComponent(cuponsMP));
        }
    }

    // Preguntar si el usuario desea guardar su direccion
    let saveAddress = $("#create-account")[0].checked;

    if (saveAddress) {
        let settings = {
            "url": $("#url-api").val() + "users?id=" + idUser + "&nameId=id_user&token=" + localStorage.getItem("token_user"),
            "method": "PUT",
            "timeout": 0,
            "headers": {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            "data": {
                "country_user": countryOrder,
                "city_user": cityOrder,
                "phone_user": phoneOrder,
                "address_user": addressOrder
            }
        };

        $.ajax(settings).done(function (response) { });
    }

    // Crear la descripcion de la compra
    let nameProduct = $(".name-product");

    let description = "";

    nameProduct.each(i => {
        description += $(nameProduct[i]).html() + " x " + quantityOrder[i] + ", ";
    })

    description = description.slice(0, -2);


    // Variable para avisar cuando finaliza el foreach
    let forEachEnd = 0;


    // Variables para almacenar los id de las ordenes y las ventas

    let idOrder = [];
    let idSale = [];

    // Recorremos los id de productos para generar la orden
    idProduct.forEach((value, i) => {

        // Generar el tiempo de entrega de cada producto
        let moment = Math.ceil(Number(deliveryTime[i] / 2));

        let sentDate = new Date();
        sentDate.setDate(sentDate.getDate() + moment);

        let deliveredDate = new Date();
        deliveredDate.setDate(deliveredDate.getDate() + Number(deliveryTime[i]));

        // Crear el proceso de entrega de la orden
        let processOrder = [
            {
                "stage": "reviewed",
                "status": "ok",
                "comment": "We have received your order, we start delivery process",
                "date": formatDate(new Date())
            },
            {
                "stage": "sent",
                "status": "pending",
                "comment": "",
                "date": formatDate(sentDate)
            },
            {
                "stage": "delivered",
                "status": "pending",
                "comment": "",
                "date": formatDate(deliveredDate)
            }
        ]

        // Subir la orden a la base de datos
        let settings = {
            "url": $("#url-api").val() + "orders?token=" + localStorage.getItem("token_user"),
            "method": "POST",
            "timeout": 0,
            "headers": {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            "data": {
                "id_store_order": idStore[i],
                "id_user_order": idUser,
                "id_product_order": value,
                "details_order": detailsOrder[i],
                "quantity_order": quantityOrder[i],
                "price_order": priceOrder[i],
                "email_order": emailOrder,
                "country_order": countryOrder,
                "city_order": cityOrder,
                "phone_order": phoneOrder,
                "address_order": addressOrder,
                "notes_order": infoOrder,
                "process_order": JSON.stringify(processOrder),
                "status_order": payStatus,
                "date_created_order": formatDate(new Date())
            }
        };

        $.ajax(settings).done(function (response) {

            if (response.status == 200) {

                idOrder.push(response.result.lastId);

                // CREAR COMISIONES
                var unitPrice = 0;
                var commissionPrice = 0;
                var count = 0;

                if (arrayCouponsMP.length > 0) {

                    arrayCouponsMP.forEach(value => {

                        if (value == urlStore[i]) {
                            count--;
                        } else {
                            count++;
                        }
                    });
                }

                if (arrayCouponsMP.length == count) {

                    // Crear comision organica
                    unitPrice = (Number(priceOrder[i]) * 0.75).toFixed(2);
                    commissionPrice = (Number(priceOrder[i]) * 0.25).toFixed(2);
                } else {

                    // Crear comision por cupon
                    unitPrice = (Number(priceOrder[i]) * 0.95).toFixed(2);
                    commissionPrice = (Number(priceOrder[i]) * 0.05).toFixed(2);
                }

                // Crear y subir la venta a la base de datos
                let settings = {
                    "url": $("#url-api").val() + "sales?token=" + localStorage.getItem("token_user"),
                    "method": "POST",
                    "timeout": 0,
                    "headers": {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    "data": {
                        "id_order_sale": response.result.lastId,
                        "unit_price_sale": unitPrice,
                        "commission_sale": commissionPrice,
                        "payment_method_sale": payMethod,
                        "id_payment_sale": payId,
                        "status_sale": payStatus,
                        "date_created_sale": formatDate(new Date())
                    }
                };

                $.ajax(settings).done(function (response) {

                    if (response.status == 200) {

                        idSale.push(response.result.lastId);

                        forEachEnd++;

                        // Avisar cuando ya finaliza la creacion de las ordenes y las ventas

                        if (forEachEnd == idProduct.length) {
                            // Cuando finaliza el checkout con paypal y la creacion de ordenes y ventas
                            if (payMethod == "paypal") {

                                // Aumentar venta del producto y disminuir stock
                                let settings = {
                                    "url": $("#url-api").val() + "products?id=" + value + "&nameId=id_product&token=" + localStorage.getItem("token_user"),
                                    "method": "PUT",
                                    "timeout": 0,
                                    "headers": {
                                        "Content-Type": "application/x-www-form-urlencoded"
                                    },
                                    "data": {
                                        "sales_product": Number(salesProduct[i]) + Number(quantityOrder[i]),
                                        "stock_product": Number(stockProduct[i]) - Number(quantityOrder[i])
                                    }
                                };

                                $.ajax(settings).done(function (response) {

                                    if (response.status == 200) {
                                        document.cookie = "list-sc=; max-age= 0";
                                        sweetAlert("success", "The purchase has been exeecuted succesfully", $("#url").val() + "account&my-shopping#profile-user");

                                        return;
                                    }
                                });
                            }

                            // Cuando finaliza el checkout con payu y la creacion de ordenes y ventas
                            if (payMethod == "payu") {

                                // Variables de payu
                                let action = "https://sandbox.checkout.payulatam.com/ppp-web-gateway-payu/";
                                let merchantId = 508029;
                                let accountId = 512321;
                                let referenceCode = Math.ceil(Math.random() * 1000000);
                                let typeMoney = "USD";
                                let apiKey = "4Vj8eK4rloUd272L48hsrarnUA";
                                let signature = hex_md5(apiKey + "~" + merchantId + "~" + referenceCode + "~" + total + "~" + typeMoney);
                                let test = 1;
                                let url = $("#url").val() + "checkout";

                                // Formulario de payu
                                let formPayu = `
                                            <img src="img/payment-method/payu_logo.png" alt="payu-logo" width="100" />

                                            <form method="post" action="${action}">
                                                <input name="merchantId" type="hidden" value="${merchantId}"   >
                                                <input name="accountId" type="hidden" value="${accountId}" >
                                                <input name="description" type="hidden" value="${description}"  >
                                                <input name="referenceCode" type="hidden" value="${referenceCode}" >
                                                <input name="amount" type="hidden" value="${total}"   >
                                                <input name="tax" type="hidden" value="0"  >
                                                <input name="taxReturnBase" type="hidden" value="0" >
                                                <input name="currency" type="hidden" value="${typeMoney}" >
                                                <input name="signature" type="hidden" value="${signature}"  >
                                                <input name="test" type="hidden" value="${test}" >
                                                <input name="buyerEmail" type="hidden" value="${emailOrder}" >
                                                <input name="responseUrl" type="hidden" value="${url}" >
                                                <input name="confirmationUrl" type="hidden" value="${url}" >
                                                <input name="Submit" type="submit" class="ps-btn p-0 px-5" value="Next" >
                                            </form>`;

                                sweetAlert("html", formPayu, null);

                                // Crear cookies para modificar la base de datos luego del pago con payu
                                setCookie("id-product", JSON.stringify(idProduct), 1);
                                setCookie("quantity-order", JSON.stringify(quantityOrder), 1);
                                setCookie("id-order", JSON.stringify(idOrder), 1);
                                setCookie("id-sale", JSON.stringify(idSale), 1);

                            }

                            // Cuando finaliza el checkout con mercado pago y la creacion de ordenes y ventas
                            if (payMethod == "mercado-pago") {

                                payTotal.description = description;
                                payTotal.payer.email = emailOrder;

                                // Crear cookies para modificar la base de datos luego del pago con mercado pago
                                setCookie("id-product", JSON.stringify(idProduct), 1);
                                setCookie("quantity-order", JSON.stringify(quantityOrder), 1);
                                setCookie("id-order", JSON.stringify(idOrder), 1);
                                setCookie("id-sale", JSON.stringify(idSale), 1);
                                setCookie("mp", JSON.stringify(payTotal), 1);


                                window.location = $("#url").val() + "checkout";
                            }
                        }
                    }
                });
            }
        });
    });
}


// Funcion para mover el scroll hasta terminos y condiciones

const goTerms = function () {
    $("html, body").animate({
        scrollTop: $("#tab-content").offset().top - 50
    });
}

// Funcion para mostrar el formulario de crear tienda al acpetar los terminos y condiciones

const changeAcceptTerms = function (event) {

    if (event.target.checked) {
        $("#create-store").tab("show");
        $(".btn-create-store").removeClass("disabled");

        // Mover el scroll hasta el formulario de crear tienda
        $("html, body").animate({
            scrollTop: $("#create-store").offset().top - 75
        });
    } else {
        $("#create-store").removeClass("active");
        $(".btn-create-store").addClass("disabled");
    }
}


// Funcion para crear URLS

const createUrl = function (event, input) {

    let value = event.target.value;

    value = value.toLowerCase();

    value = value.replace(/[ ]/g, "-");
    value = value.replace(/[ñ]/g, "n");
    value = value.replace(/[á]/g, "a");
    value = value.replace(/[é]/g, "e");
    value = value.replace(/[í]/g, "i");
    value = value.replace(/[ó]/g, "o");
    value = value.replace(/[ú]/g, "u");

    $(`[name=${input}]`).val(value);
}


// Funcion para validar el formulario de la tienda y continuar con la creacion del producto

const validateFormStore = function () {
    // Validar que el formulario de la tienda este completo
    let formStore = $(".form-store");
    let error = 0;

    formStore.each(i => {
        if ($(formStore[i]).val().trim() === "" || $(formStore[i]).val() == undefined) {

            $(formStore[i]).parent().addClass("was-validated");
            error++;
        }
    });

    if (error > 0) {
        notieAlert(3, "There are fields in the store creation that are not correct");

        return;
    }

    // Habilitar el modulo de creacion de producto
    $("#create-product").tab("show");
    $(".btn-create-product").removeClass("disabled");

    $("html, body").animate({
        scrollTop: $("#create-product").offset().top - 75
    });

}


// Traer subcategorias segun la categoria seleccionada

const changeCategory = function (event) {

    if (event.target.value != "") {

        $(".subcategory-product").show();

        let idCategory = event.target.value.split("_")[0];

        let settings = {
            "url": $("#url-api").val() + "subcategories?linkTo=id_category_subcategory&equalTo=" + idCategory + "&select=id_subcategory,name_subcategory,title_list_subcategory",
            "method": "GET",
            "timeout": 0,
        }

        $.ajax(settings).done(function (response) {

            let optSubcategory = $(".opt-subcategory");

            optSubcategory.each(i => {
                $(optSubcategory[i]).remove();
            });

            response.result.forEach(value => {
                $("[name='subcategory-product']").append(`<option class="opt-subcategory" value="${value.id_subcategory}_${value.title_list_subcategory}">${value.name_subcategory}</option>`)
            })

        });
    }
}


// Funcion para añadir inputs (entradas) al formulario del producto

const addInput = function (elem, type) {
    let inputs = $("." + type);

    if (inputs.length < 5) {

        // Adicionar entrada del resumen del producto
        if (type == "input-summary") {
            $(elem).before(
                `<div class="form-group__content input-group mb-3 input-summary">

                    <div class="input-group-append">
                        <span class="input-group-text">
                            <button type="button" class="btn btn-danger" onclick="removeInput(${inputs.length}, 'input-summary')">&times;</button>
                        </span>
                    </div>

                    <input type="text" name="summary-product_${inputs.length}" class="form-control" pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')" required>

                    <div class="valid-feedback">Valid</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>`
            );
        }

        // Adicionar entrada del detalle del producto
        if (type == "input-details") {
            $(elem).before(
                `<div class="row mb-3 input-details">

                <!-- Title detail -->
                <div class="col-12 col-lg-6 form-group__content input-group">

                    <div class="input-group-append">
                        <span class="input-group-text">
                            <button type="button" class="btn btn-danger" onclick="removeInput(${inputs.length}, 'input-details')">&times;</button>
                        </span>
                    </div>

                    <div class="input-group-append">
                        <span class="input-group-text">
                            Type:
                        </span>
                    </div>

                    <input type="text" name="details-title-product_${inputs.length}" id="details-product" class="form-control" pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')" required>

                    <div class="valid-feedback">Valid</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <!-- Value detail -->
                <div class="col-12 col-lg-6 form-group__content input-group">

                    <div class="input-group-append">
                        <span class="input-group-text">
                            Value:
                        </span>
                    </div>

                    <input type="text" name="details-value-product_${inputs.length}" id="details-product" class="form-control" pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')" required>

                    <div class="valid-feedback">Valid</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
            </div>`
            );
        }

        if (type == "input-specifications") {
            $(elem).before(
                `<div class="row mb-3 input-specifications">

                    <!-- Title especificacion -->
                    <div class="col-12 col-lg-6 form-group__content input-group">

                        <div class="input-group-append">
                            <span class="input-group-text">
                                <button type="button" class="btn btn-danger" onclick="removeInput(${inputs.length}, 'input-specifications')">&times;</button>
                            </span>
                        </div>

                        <div class="input-group-append">
                            <span class="input-group-text">
                                Type:
                            </span>
                        </div>

                        <input type="text" name="specifications-title-product_${inputs.length}" class="form-control" pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')">

                    </div>

                    <!-- Value especificacion -->
                    <div class="col-12 col-lg-6 form-group__content input-group">
                        <input type="text" name="specifications-value-product_${inputs.length}" class="form-control tags-input" data-role="tagsinput" placeholder="Type And Press Enter" pattern='[-\\(\\)=&$;_*\\"#¿!¡:,\\.0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event, 'paragraphs')">
                    </div>
                </div>`
            );

            addTagInput();

        }

        $("[name=" + type + "]").val(inputs.length + 1);

    } else {
        notieAlert(2, "Maximum 5 entries allowed");
        return;
    }
}


// Funcion para remover inputs (entradas) al formulario del producto

const removeInput = function (index, type) {
    let inputs = $("." + type);

    if (inputs.length > 1) {

        inputs.each(i => {

            if (i == index) {
                $(inputs[i]).remove();
            }
        });

        $("[name=" + type + "]").val(inputs.length - 1);

    } else {
        notieAlert(3, "At least one entry must exist");
        return;
    }
}

// Tags inputs

const addTagInput = function () {
    let target = $(".tags-input");

    if (target.length > 0) {
        $(target).tagsinput();
    }
}

addTagInput();


// Dropzone (galeria de imagenes)

Dropzone.autoDiscover = false;

let arrayFiles = [];
let countArrayFiles = 0;

$(".dropzone").dropzone({
    url: "/",
    addRemoveLinks: true,
    acceptedFiles: "image/jpeg, image/png",
    maxFilesize: 2,
    maxFiles: 10,
    init: function () {
        this.on("addedfile", function (file) {

            countArrayFiles++;

            setTimeout(() => {
                arrayFiles.push({
                    "file": file.dataURL,
                    "type": file.type,
                    "width": file.width,
                    "height": file.height
                });

                $("[name='gallery-product']").val(JSON.stringify(arrayFiles));
            }, 1000 * countArrayFiles);
        });

        this.on("removedfile", function (file) {

            countArrayFiles++;

            setTimeout(() => {

                let index = arrayFiles.indexOf({
                    "file": file.dataURL,
                    "type": file.type,
                    "width": file.width,
                    "height": file.height
                });

                arrayFiles.splice(index, 1);

                $("[name='gallery-product']").val(JSON.stringify(arrayFiles));

            }, 1000 * countArrayFiles);
        });

        myDropzone = this;
        $(".save-btn").click(function () {

            if (arrayFiles.length > 0) {
                myDropzone.processQueue();
            } else {
                notieAlert(3, "The gallery product cannot be empty");
            }

        });
    }
});


// Edicion de galeria

let arrayfilesOld;

if ($("[name='gallery-product-old']").length > 0 && $("[name='gallery-product-old']").val() != "") {
    arrayfilesOld = JSON.parse($("[name='gallery-product-old']").val());


}

let arrayFilesDelete = [];

const removeGallery = function (elem) {

    $(elem).parent().remove();

    let index = arrayfilesOld.indexOf($(elem).attr("remove"));

    arrayfilesOld.splice(index, 1);

    $("[name='gallery-product-old']").val(JSON.stringify(arrayfilesOld));

    arrayFilesDelete.push($(elem).attr("remove"));

    $("[name='detele-gallery-product']").val(JSON.stringify(arrayFilesDelete));
}


// Elegir tipo de oferta

const changeOffer = function (type) {
    if (type.target.value == "Discount") {
        $(".type-offer").html("Percent %:");
    } else {
        $(".type-offer").html("Price $:");
    }
}


// Funcion para cambiar el estado del producto

const changeState = function (event, idProduct, idView) {

    let state;

    if (event.target.checked) {
        state = "show";


    } else {
        state = "hidden";
    }

    let token = localStorage.getItem("token_user");

    let settings = {
        "url": $("#url-api").val() + "products?id=" + idProduct + "&nameId=id_product&token=" + token,
        "method": "PUT",
        "timeout": 0,
        "headers": {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        "data": {
            "state_product": state
        }
    };

    $.ajax(settings).done(function (response) {
        if (response.status == 200) {

        }
    })

}


// Funcion para remover productos de la tienda

const removeProduct = function (idProduct) {

    sweetAlert("confirm", "Are you sure to delete this product?", null).then(resp => {

        if (resp) {

            let data = new FormData();
            data.append("id-product", idProduct);

            $.ajax({
                url: $("#path").val() + "ajax/delete-products.php",
                method: "POST",
                data: data,
                contentType: false,
                cache: false,
                processData: false,
                success: function (response) {

                    let token = localStorage.getItem("token_user");

                    let settings = {
                        "url": $("#url-api").val() + "products?id=" + idProduct + "&nameId=id_product&token=" + token,
                        "method": "DELETE",
                        "timeout": 0,
                        "headers": {
                            "Content-Type": "application/x-www-form-urlencoded"
                        }
                    };

                    $.ajax(settings).done(function (response) {
                        if (response.status == 200) {
                            sweetAlert("success", "The product has been delete", $("#path").val() + "account&my-store");
                        }
                    })

                }
            })

        }

    });

}

// Funcion para formatear fecha
const formatDate2 = function (fechaString) {



    let partes = fechaString.split("-");

    if (partes[1].length < 2) {
        let cero = "0";
        partes[1] = cero += partes[1];
    }

    if (partes[2].length < 2) {
        let cero = "0";
        partes[2] = cero += partes[2];
    }

    return partes[0] + "-" + partes[1] + "-" + partes[2];

}

// Funcion para actualizar la orden

$(document).on("click", ".next-process", function () {

    // Limpiamos la ventana modal
    $(".order-body").html("");

    // Capturamos el id de la orden
    let idOrder = $(this).attr("id-order");

    // Capturamos el id de la tienda
    let idStore = $(this).attr("id-store");

    // Capturamos el cliente de la orden
    let clientOrder = $(this).attr("client-order");

    // Capturamos el email de la orden
    let emailOrder = $(this).attr("email-order");

    // Capturamos el producto de la orden
    let productOrder = $(this).attr("product-order");

    // Capturamos el proceso de la orden
    let processOrder = JSON.parse(atob($(this).attr("process-order")));

    // Nombramos la ventana modal con el id de la orden
    $(".modal-title span").html(`Order N. ${idOrder}`);

    // Quitamos la opcion de llenar el campo de recibido si no se ha enviado el producto
    if (processOrder[1].status == "pending") {
        processOrder.splice(2, 1);
    }

    // Informacion dinamica que aparecera en la ventana modal
    processOrder.forEach((value, index) => {

        let date = "";
        let status = "";
        let comment = "";

        if (value.status == "ok") {
            date = `
            <div class="col-10">
                <input type="date" class="form-control" value="${formatDate2(value.date)}" readonly>
            </div>`;

            status = `
            <div class="col-10 mt-3">
                <div class="text-uppercase" >${value.status}</div>
            </div>`;

            comment = `
            <div class="col-10">
                <textarea class="form-control" readonly >${value.comment}</textarea>
            </div>`;
        } else {
            date = `
            <div class="col-10">
                <input type="date" class="form-control" name="date" value="${formatDate2(value.date)}" required>
            </div>`;

            status = `
            <div class="col-10 mt-3">

                <input type="hidden" name="stage" value="${value.stage}">
                <input type="hidden" name="process-order" value="${$(this).attr("process-order")}">

                <input type="hidden" name="id-order" value="${idOrder}">
                <input type="hidden" name="id-store" value="${idStore}">

                <input type="hidden" name="client-order" value="${clientOrder}">
                <input type="hidden" name="email-order" value="${emailOrder}">
                <input type="hidden" name="product-order" value="${productOrder}">

                <div class="custom-control custom-radio custom-control-inline">
                    <input id="status-pending" value="pending" name="status" checked type="radio" class="custom-control-input">
                    <label class="custom-control-label" for="status-pending">Pending</label>
                </div>

                <div class="custom-control custom-radio custom-control-inline">
                    <input id="status-ok" value="ok" name="status" type="radio" class="custom-control-input">
                    <label class="custom-control-label" for="status-ok">Ok</label>
                </div>
            </div>`;

            comment = `
            <div class="col-10">
                <textarea class="form-control" name="comment" required>${value.comment}</textarea>
            </div>`;
        }

        $(".order-body").append(
            `<div class='card-header text-uppercase'>${value.stage}</div>
            
                <div class="card-body">

                    <div class="form-row">
                        <div class="col-2 text-right">

                            <label class="p-3 lead">Date:</label>

                        </div>
                        ${date}
                    </div>

                    <div class="form-row">

                        <div class="col-2 text-right">

                            <label class="p-3 lead">Status:</label>

                        </div>
                        ${status}
                    </div>

                    <div class="form-row">

                        <div class="col-2 text-right">

                            <label class="p-3 lead">Comment:</label>

                        </div>
                        ${comment}
                    </div>

            </div>`
        );

    });

    $("#next-process").modal();

});


// Funcion para crear una disputa

$(document).on("click", ".open-dispute", function () {

    // Limpiamos la ventana modal
    $(".order-body").html("");

    // Capturamos el id de la orden
    $("[name='id-order']").val($(this).attr("id-order"));

    // Capturamos el cliente de la orden
    $("[name='id-user']").val($(this).attr("id-user"));

    // Capturamos el email de la orden
    $("[name='id-store']").val($(this).attr("id-store"));

    // Capturamos el producto de la orden
    $("[name='email-store']").val($(this).attr("email-store"));

    // Capturamos el nombre de la tienda
    $("[name='name-store']").val($(this).attr("name-store"));

    // Abrir la ventana modal
    $("#new-dispute").modal();
});


// Funcion para responder disputa

$(document).on("click", ".answer-dispute", function () {

    // Capturamos el id de la disputa
    $("[name='id-dispute']").val($(this).attr("id-dispute"));

    // Capturamos el nombre del cliente
    $("[name='client-dispute']").val($(this).attr("client-dispute"));

    // Capturamos el email del cliente
    $("[name='email-dispute']").val($(this).attr("email-dispute"));

    // Abrir la ventana modal
    $("#answer-dispute").modal();

});

// Funcion para responder mensaje

$(document).on("click", ".answer-message", function () {

    // Capturamos el id de la disputa
    $("[name='id-message']").val($(this).attr("id-message"));

    // Capturamos el nombre del cliente
    $("[name='client-message']").val($(this).attr("client-message"));

    // Capturamos el email del cliente
    $("[name='email-message']").val($(this).attr("email-message"));

    // Capturamos el url del producto
    $("[name='url-product']").val($(this).attr("url-product"));

    // Abrir la ventana modal
    $("#answer-message").modal();

});

// Funcion para calificar el producto

$(document).on("click", ".new-review", function () {

    // Capturamos el id del producto
    $("[name='id-product']").val($(this).attr("id-product"));

    // Capturamos el id del usuario
    $("[name='id-user']").val($(this).attr("id-user"));

    // Abrir la ventana modal
    $("#new-review").modal();

});


// Funcion para cambiar el idioma

const changeLang = function (lang) {

    localStorage.setItem("yt-widget", `{"lang": "${lang}", "active": true}`);

    window.open(window.location.href, "_top");
}