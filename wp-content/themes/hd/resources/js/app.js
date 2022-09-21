/**jshint esversion: 6 */
import './_foundation';

import {nanoid} from 'nanoid';
import random from "lodash/random";
import isEmpty from "lodash/isEmpty";
import toString from "lodash/toString";

/** current-device */
import device from "current-device";
const is_mobile = () => device.mobile();
const is_tablet = () => device.tablet();

//require("jquery.marquee");

/** Fancybox */
import { Fancybox } from "@fancyapps/ui";
Fancybox.bind(".wp-block-gallery .blocks-gallery-item a, [id|=\"gallery\"] a", {
    groupAll: true, // Group all items
});

Fancybox.bind(".fcy-popup, .fcy-video", {});

/** Create deferred YT object */
// const YTdeferred = $.Deferred();
// window.onYouTubeIframeAPIReady = function () {
//     YTdeferred.resolve(window.YT);
// };

/** AOS */
//import AOS from 'aos';
//AOS.init();

/** jquery */
$(() => {

    /**review custom*/
    let glsr_review_author = $('.glsr-review-author');
    glsr_review_author.each(function (index, value) {
        let $this = $(this);
        let tmp = $this.find('span').text().split(/[\-]/);
        if (  Array.isArray(tmp) && typeof tmp[1] !== 'undefined' ) {
            let html = '<span>' + tmp[0] + '<span>' + tmp[1] + '</span></span>';
            $this.html(html);
        }
    });

    /** */
    $(document).on('click','ul.tabs-heading a',function(e) {
        $(this).closest('.tabs-wrapper').find('.desc-inner').removeAttr("style");
        $(this).closest('.tabs-wrapper').find('.viewmore-wrapper').fadeOut();
    });

    /** */
    $(document).on('click','.woocommerce.widget_price_filter>span:first-child, .woocommerce.widget_layered_nav>span:first-child',function(e) {
        e.preventDefault();
        $(this).toggleClass('is-active');
    });

    /** */
    $('.variations_form').each(function () {

        // when variation is found, do something
        $(this).on('found_variation', function (event, variation) {
            if (variation.price_html !== '') {
                $(".product-detail-inner p.single-price").html(variation.price_html);
            }
        });
    });

    /** */
    let product_desc = $('.product-desc-inner');
    product_desc.each((index, el) => {
        const _rand = nanoid(8);
        $(el).attr('id', 'desc_' + _rand);

        let _height = $(el).outerHeight(false);
        if (_height > 1000) {
            $(el).css({'height':'900px','transition':'0.3s'});
            let _viewmore_html = '<div class="viewmore-wrapper"><a class="btn-viewmore" title="' + HD.lg.view_more + '" data-src="#desc_' + _rand + '" data-modal="false" href="javascript:;" data-glyph-after="">' + HD.lg.view_more + '</a></div>';
            $(el).append(_viewmore_html);

            //...
            $("#desc_" + _rand).find('.btn-viewmore').on('click', function (e) {
                e.preventDefault();

                //$(el).addClass('is-open');
                $(el).css({'height':'auto','transition':'0.3s'});
                $("#desc_" + _rand).find('.viewmore-wrapper').fadeOut();
            })

            //...
            //Fancybox.bind("#desc-inner .btn-viewmore", {});
        }
    });

    /** Remove empty P tags created by WP inside of Accordion and Orbit */
    $('.accordion p:empty, .orbit p:empty').remove();

    /** Adds Flex Video to YouTube and Vimeo Embeds */
    $('iframe[src*="youtube.com"], iframe[src*="vimeo.com"]').each(() => {
        if ($(this).innerWidth() / $(this).innerHeight() > 1.5) {
            $(this).wrap("<div class='widescreen responsive-embed'/>");
        } else {
            $(this).wrap("<div class='responsive-embed'/>");
        }
    });

    /** toggle menu */
    const _toggle_menu = $(".toggle_menu");
    _toggle_menu.find("li.is-active.has-submenu-toggle").find(".submenu-toggle").trigger('click');

    /** */
    const wpg__image = $('.wpg__image');
    wpg__image.find('a').on('click', function (e) {
        e.preventDefault();
        $(this).next('.image-popup').trigger('click');
    });

    /** */
    const wpg__thumb = $('.wpg__thumb');
    wpg__thumb.find('a').on('click', function (e) {
        e.preventDefault();
    });

    //...
    const _qty_controls = () => {

        /**qty*/
        $('.input-number-increment').off('click').on('click', function (e) {
            e.preventDefault();
            let $input = $(this).parents('.input-number-group').find('.qty');
            let val = parseInt($input.val(), 10);
            $input.val(val + 1);

            let update_cart = $('button[name="update_cart"]');
            if (update_cart.length > 0) {
                update_cart.prop('disabled', false)
            }
        });
        $('.input-number-decrement').off('click').on('click', function (e) {
            e.preventDefault();
            let $input = $(this).parents('.input-number-group').find('.qty');
            let val = parseInt($input.val(), 10);
            if (val > 1) {
                $input.val(val - 1);

                let update_cart = $('button[name="update_cart"]');
                if (update_cart.length > 0) {
                    update_cart.prop('disabled', false)
                }
            }
        });
    }

    // run
    _qty_controls();

    /** ajaxComplete */
    $( document ).ajaxComplete(function( event, xhr, settings ) {
        _qty_controls();
    });

    /** */
    const onload_events = () => {

        /** */
        // const videos_gallery = $('section.videos-gallery');
        // let gal_target_height = videos_gallery.find('.video-wrapper').find('.item-first').outerHeight(false);
        //
        // let _port_size = getViewportSize(window);
        // if (device.desktop() || _port_size.w >= 782) {
        //     videos_gallery.find('.video-wrapper').find('.item-list>ul').css({
        //         'max-height': gal_target_height + 'px',
        //         'height': 'auto',
        //     });
        // }

        /** */
        //const woocommerce_product_gallery = $('.woocommerce-product-gallery');
        //let woo_target_height = woocommerce_product_gallery.find('.flex-viewport').outerHeight(false);
        // woocommerce_product_gallery.find('.flex-control-thumbs').css({
        //     'max-height': woo_target_height + 'px',
        //     'height': 'auto',
        // });
    }

    $(window).on('load', () => { onload_events(); });

    /** Orientation JavaScript Callback*/
    device.onChangeOrientation(() => { onload_events(); });
});

/** DOMContentLoaded */
document.addEventListener( 'DOMContentLoaded', () => {

    /*attribute target="_blank" is not W3C compliant*/
    const _blanks = [...document.querySelectorAll('a._blank, a.blank, a[target="_blank"]')];
    _blanks.forEach((el, index) => {
        el.removeAttribute('target');
        el.setAttribute('target', '_blank');
        if (!1 === el.hasAttribute('rel')) {
            el.setAttribute('rel', 'noopener noreferrer nofollow');
        }
    });
});

/** vars */
const getParameters = (URL) => JSON.parse('{"' + decodeURI(URL.split("?")[1]).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g, '":"') + '"}');
const touchSupported = () => { ('ontouchstart' in window || window.DocumentTouch && document instanceof window.DocumentTouch); };

/**
 * https://stackoverflow.com/questions/1248081/how-to-get-the-browser-viewport-dimensions
 *
 * @param w
 * @returns {{w: *, h: *}}
 */
function getViewportSize(w) {

    /* Use the specified window or the current window if no argument*/
    w = w || window;

    /* This works for all browsers except IE8 and before*/
    if (w.innerWidth != null) return {w: w.innerWidth, h: w.innerHeight};

    /* For IE (or any browser) in Standards mode*/
    let d = w.document;
    if ("CSS1Compat" === document.compatMode)
        return {
            w: d.documentElement.clientWidth,
            h: d.documentElement.clientHeight
        };

    /* For browsers in Quirks mode*/
    return {w: d.body.clientWidth, h: d.body.clientHeight};
}

/**
 * @param cname
 * @returns {unknown}
 */
const getCookie = (cname) => (
    document.cookie.match('(^|;)\\s*' + cname + '\\s*=\\s*([^;]+)')?.pop() || ''
)

/**
 * @param cname
 * @param cvalue
 * @param exdays
 */
function setCookie(cname, cvalue, exdays) {
    let d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    let expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}

/**
 * @param url
 * @param $delay
 */
function redirect(url = null, $delay = 10) {
    setTimeout(function () {
        if (url === null || url === '' || typeof url === "undefined") {
            document.location.assign(window.location.href);
        } else {
            url = url.replace(/\s+/g, '');
            document.location.assign(url);
        }
    }, $delay);
}

/**
 * @param page
 * @param title
 * @param url
 */
function pushState(page, title, url) {
    if ("undefined" !== typeof history.pushState) {
        history.pushState({page: page}, title, url);
    } else {
        window.location.assign(url);
    }
}

/** import Swiper bundle with all modules installed */
import { Swiper } from 'swiper/bundle';

/** wc product gallery */
const spg_swiper = [...document.querySelectorAll('.swiper-product-gallery')];
spg_swiper.forEach((el, index) => {
    const _rand = nanoid(8),
        _class = 'swiper-product-gallery-' + _rand;

    el.classList.add(_class);

    let w_images = el.querySelector('.swiper-images');
    let w_thumbs = el.querySelector('.swiper-thumbs');

    let swiper_images = false;
    let swiper_thumbs = false;

    /** wpg thumbs */
    if (w_thumbs) {
        w_thumbs.querySelector('.swiper-button-prev').classList.add('prev-thumbs-' + _rand);
        w_thumbs.querySelector('.swiper-button-next').classList.add('next-thumbs-' + _rand);
        w_thumbs.classList.add('thumbs-' + _rand);

        let thumbs_options = {
            spaceBetween: 5,
            slidesPerView: 4,
            watchSlidesProgress: !0,
            mousewheelControl: !0,
            breakpoints: {
                640: {
                    spaceBetween: 10,
                    slidesPerView: 5,
                },
                1024: {
                    spaceBetween: 10,
                    slidesPerView: 6,
                },
            },
            navigation: {
                prevEl: '.prev-thumbs-' + _rand,
                nextEl: '.next-thumbs-' + _rand,
            },
        }

        //if ( is_mobile() || is_tablet() ) {
        //thumbs_options.cssMode = !0;
        //}

        swiper_thumbs = new Swiper('.thumbs-' + _rand, thumbs_options);
    }

    /** wpg images */
    if (w_images) {
        w_images.querySelector('.swiper-button-prev').classList.add('prev-images-' + _rand);
        w_images.querySelector('.swiper-button-next').classList.add('next-images-' + _rand);
        w_images.classList.add('images-' + _rand);

        let images_options = {
            // effect: "fade",
            speed: 200, // default : 300
            slidesPerView: 'auto',
            spaceBetween: 10,
            watchSlidesProgress: !0,
            breakpoints: {
                640: {
                    spaceBetween: 15,
                }
            },
            navigation: {
                prevEl: '.prev-images-' + _rand,
                nextEl: '.next-images-' + _rand,
            },
        };

        if (swiper_thumbs) {
            images_options.thumbs = {
                swiper: swiper_thumbs,
            }
        }

        //if ( is_mobile() || is_tablet() ) {
        //images_options.cssMode = !0;
        //}

        swiper_images = new Swiper('.images-' + _rand, images_options);

        /** */
        Fancybox.bind('[data-rel="lightbox"]', {
            groupAll: true,
            on: {
                load: (fancybox, slide) => {
                    //console.log(`#${slide.index} slide is loaded!`);

                    // Update position of the slider
                    swiper_images.slideTo(slide.index - 1);
                },
            },
        });

        /** variation image */
        let firstImage = w_images.querySelector('.swiper-images-first img');
        firstImage.removeAttribute('srcset');

        let firstImageSrc = firstImage.getAttribute('src');
        let imagePopupSrc = w_images.querySelector('.swiper-images-first .image-popup');

        /** */
        let firstThumb = false;
        let firstThumbSrc = false;
        let dataLargeImage = false;

        if (swiper_thumbs) {
            firstThumb = w_thumbs.querySelector('.swiper-thumbs-first img');
            firstThumb.removeAttribute('srcset');

            firstThumbSrc = firstThumb.getAttribute('src');
            dataLargeImage = firstThumb.getAttribute('data-large_image');
        }

        /** */
        const variations_form = $('form.variations_form');
        variations_form.on('found_variation', function( event, variation ) {
            if( variation.image.src ) {

                firstImage.setAttribute('src', variation.image.src);
                imagePopupSrc.setAttribute('data-src', variation.image.full_src);
                if (swiper_thumbs) {
                    firstThumb.setAttribute('src', variation.image.gallery_thumbnail_src);
                }

                swiper_images.slideTo(0);
            }
        });

        variations_form.on( 'reset_image', function() {

            firstImage.setAttribute('src', firstImageSrc);
            imagePopupSrc.setAttribute('data-src', dataLargeImage);
            if (swiper_thumbs) {
                firstThumb.setAttribute('src', firstThumbSrc);
            }

            swiper_images.slideTo(0);
        });

        /** */
        const reset_variations = $( '.reset_variations' );
        //reset_variations.on( 'click', function() {});
    }
});

/** swiper container */
const w_swiper = [...document.querySelectorAll('.w-swiper')];
w_swiper.forEach((el, index) => {
    const _rand = nanoid(12),
        _class = 'swiper-' + _rand,
        _next_class = 'next-' + _rand,
        _prev_class = 'prev-' + _rand,
        _pagination_class = 'pagination-' + _rand,
        _scrollbar_class = 'scrollbar-' + _rand;

    el.classList.add(_class);

    /** swiper controls */
    let _controls = el.closest('.swiper-section').querySelector('.swiper-controls');
    if (_controls == null) {
        _controls = document.createElement("div");
        _controls.classList.add('swiper-controls');
        el.after(_controls);
    }

    /** swiper options */
    const el_swiper_wrapper = el.querySelector('.swiper-wrapper');
    let _obj_options = JSON.parse(el_swiper_wrapper.dataset.options);

    if (isEmpty(_obj_options)) {
        _obj_options = {
            autoview: !0,
            loop: !0,
            autoplay: !0,
            navigation: !0,
        };
    }

    /** init options*/
    let _result_options = {};

    //_result_options.lazy = !0;
    _result_options.grabCursor = !0;
    _result_options.allowTouchMove = !0;
    _result_options.threshold = 0.5;
    _result_options.hashNavigation = !1;

    /** responsive view*/
    let _desktop_data = 0,
        _tablet_data = 0,
        _mobile_data = 0;

    if ("desktop" in _obj_options) {
        _desktop_data = _obj_options.desktop;
    }

    if ("tablet" in _obj_options) {
        _tablet_data = _obj_options.tablet;
    }

    if ("mobile" in _obj_options) {
        _mobile_data = _obj_options.mobile;
    }

    if ( !_desktop_data || !_tablet_data || !_mobile_data ) {
        _result_options.autoview = !0;
    }

    /** gap */
    _result_options.spaceBetween = 0;
    if ("gap" in _obj_options) {
        _result_options.spaceBetween = 20;
    } else if ("smallgap" in _obj_options) {
        _result_options.spaceBetween = parseInt(_obj_options.smallgap);
    }

    /** autoview */
    if ("autoview" in _obj_options) {
        _result_options.slidesPerView = 'auto';
        _result_options.loopedSlides = 12;
        if ("gap" in _obj_options) {
            _result_options.breakpoints = {
                640: { spaceBetween: 30 }
            };
        } else if ("smallgap" in _obj_options) {
            _result_options.breakpoints = {
                640: { spaceBetween: parseInt(_obj_options.smallgap) }
            };
        }

    } else {
        _result_options.slidesPerView = parseInt(_mobile_data);
        if ("gap" in _obj_options) {
            _result_options.breakpoints = {
                640: {
                    spaceBetween: 30,
                    slidesPerView: parseInt(_tablet_data)
                },
                1024: {
                    spaceBetween: 30,
                    slidesPerView: parseInt(_desktop_data)
                },
            };
        } else if ("smallgap" in _obj_options) {
            _result_options.breakpoints = {
                640: {
                    spaceBetween: parseInt(_obj_options.smallgap),
                    slidesPerView: parseInt(_tablet_data)
                },
                1024: {
                    spaceBetween: parseInt(_obj_options.smallgap),
                    slidesPerView: parseInt(_desktop_data)
                },
            };
        } else {
            _result_options.breakpoints = {
                640: { slidesPerView: parseInt(_tablet_data) },
                1024: { slidesPerView: parseInt(_desktop_data) },
            };
        }
    }
    if ("autoview" in _obj_options || _result_options.slidesPerView > 1) {
        _result_options.watchSlidesVisibility = !0;
    }

    /** centered*/
    if ("centered" in _obj_options) {
        _result_options.centeredSlides = !0;
    }

    /** speed*/
    if ("speed" in _obj_options) {
        _result_options.speed = parseInt(_obj_options.speed);
    } else {
        _result_options.speed = random(600, 1200);
    }

    /** observer*/
    if ("observer" in _obj_options) {
        _result_options.observer = !0;
        _result_options.observeParents = !0;
    }

    /** group*/
    if ("group" in _obj_options && !("autoview" in _obj_options)) {
        _result_options.slidesPerGroupSkip = !0;
        _result_options.loopFillGroupWithBlank = !0;
        _result_options.slidesPerGroup = parseInt(_obj_options.group);
    }

    /** fade*/
    if ("fade" in _obj_options) {
        _result_options.effect = 'fade';
        _result_options.fadeEffect = { crossFade: !0 };
    }

    /** autoheight*/
    if ("autoheight" in _obj_options) {
        _result_options.autoHeight = !0;
    }

    /** freemode*/
    if ("freemode" in _obj_options) {
        _result_options.freeMode = !0;
    }

    /** loop*/
    if ("loop" in _obj_options && !("row" in _obj_options)) {
        _result_options.loop = !0;
        _result_options.loopFillGroupWithBlank = !0;
    }

    /** autoplay*/
    if ("autoplay" in _obj_options) {
        if ("delay" in _obj_options) {
            _result_options.autoplay = {
                disableOnInteraction: !1,
                delay: parseInt(_obj_options.delay),
            };
        } else {
            _result_options.autoplay = {
                disableOnInteraction: !1,
                delay: random(5500, 6500),
            };
        }
        if ("reverse" in _obj_options) {
            _result_options.reverseDirection = !0;
        }
    }

    /** row*/
    if ("row" in _obj_options) {
        _result_options.direction = 'horizontal';
        _result_options.loop = !1;
        _result_options.grid = {
            rows: parseInt(_obj_options.row),
            fill: 'row',
        };
    }

    /**navigation*/
    if ("navigation" in _obj_options) {
        const _section = el.closest('.swiper-section');
        let _btn_prev = _section.querySelector('.swiper-button-prev');
        let _btn_next = _section.querySelector('.swiper-button-next');

        if (_btn_prev && _btn_next) {
            _btn_prev.classList.add(_prev_class);
            _btn_next.classList.add(_next_class);
        } else {
            _btn_prev = document.createElement("div");
            _btn_next = document.createElement("div");

            _btn_prev.classList.add('swiper-button', 'swiper-button-prev', _prev_class);
            _btn_next.classList.add('swiper-button', 'swiper-button-next', _next_class);

            _controls.appendChild(_btn_prev);
            _controls.appendChild(_btn_next);

            _btn_prev.setAttribute("data-glyph", "");
            _btn_next.setAttribute("data-glyph", "");
        }

        _result_options.navigation = {
            nextEl: '.' + _next_class,
            prevEl: '.' + _prev_class,
        };
    }

    /** pagination */
    if ("pagination" in _obj_options) {
        const _section = el.closest('.swiper-section');
        let _pagination = _section.querySelector('.swiper-pagination');
        if (_pagination) {
            _pagination.classList.add(_pagination_class);
        } else {
            let _pagination = document.createElement("div");

            _pagination.classList.add('swiper-pagination', _pagination_class);
            _controls.appendChild(_pagination);
        }

        if (_obj_options.pagination === 'fraction') {
            _result_options.pagination = {
                el: '.' + _pagination_class,
                type: 'fraction',
            };
        } else if (_obj_options.pagination === 'progressbar') {
            _result_options.pagination = {
                el: '.' + _pagination_class,
                type: "progressbar",
            };
        } else if (_obj_options.pagination === 'dynamic') {
            _result_options.pagination = {
                dynamicBullets: !0,
                el: '.' + _pagination_class,
            };
        } else {
            _result_options.pagination = {
                dynamicBullets: !1,
                el: '.' + _pagination_class,
            };
        }

        _result_options.pagination.clickable = !0;
    }

    /** scrollbar */
    if ("scrollbar" in _obj_options) {
        let _swiper_scrollbar = document.createElement("div");
        _swiper_scrollbar.classList.add('swiper-scrollbar', _scrollbar_class);
        _controls.appendChild(_swiper_scrollbar);
        _result_options.scrollbar = {
            hide: !0,
            el: '.' + _scrollbar_class,
        };
    }

    /** vertical*/
    if ("vertical" in _obj_options) {
        _result_options.direction = 'vertical';
    }

    /**parallax*/
    if ("parallax" in _obj_options) {
        _result_options.parallax = !0;
    }

    /**_marquee**/
    if ("marquee" in _obj_options) {
        _result_options.centeredSlides = !0;
        _result_options.autoplay = {
            delay: 1,
            disableOnInteraction: !1
        };
        _result_options.loop = !0;
        _result_options.allowTouchMove = !0;
    }

    /**progress*/
    if ("progressbar" in _obj_options) {
        let _swiper_progress = document.createElement("div");
        _swiper_progress.classList.add('swiper-progress');
        _result_options.appendChild(_swiper_progress);
    }

    /**cssMode*/
    if (!("row" in _obj_options)
        && !("marquee" in _obj_options)
        && !("centered" in _obj_options)
        && !("freemode" in _obj_options)
        && !("progressbar" in _obj_options)
        && ( is_mobile() || is_tablet() )
        && !el.classList.contains('sync-swiper')) {
        _result_options.cssMode = !0; /* API CSS Scroll Snap */
    }

    /** progress dom*/
    let _swiper_progress = _controls.querySelector('.swiper-progress');

    /** init*/
    _result_options.on = {
        init: function () {
            let t = this;
            if ("parallax" in _obj_options) {
                t.autoplay.stop();
                t.touchEventsData.formElements = "*";
                const parallax = el.querySelectorAll('.--bg');
                [].slice.call(parallax).map((elem) => {
                    let p = elem.dataset.swiperParallax.replace("%", "");
                    if (!p) {
                        p = 95;
                    }
                    elem.dataset.swiperParallax = toString(p / 100 * t.width);
                });
            }

            if ("progressbar" in _obj_options) {
                _swiper_progress.classList.add('progress');
            }
        },

        slideChange: function () {
            if ("progressbar" in _obj_options) {
                _swiper_progress.classList.remove('progress');
            }

            /** sync*/
            let t = this;
            if (el.classList.contains('sync-swiper')) {
                const el_closest = el.closest('section.section');
                const sync_swipers = Array.from(el_closest.querySelectorAll('.sync-swiper:not(.sync-exclude)'));
                sync_swipers.forEach((item, i) => {
                    let _local_swiper = item.swiper;
                    if ("loop" in _obj_options) {
                        _local_swiper.slideToLoop(t.activeIndex, parseInt(_obj_options.speed), true);
                    } else {
                        _local_swiper.slideTo(t.activeIndex, parseInt(_obj_options.speed), true);
                    }
                });
            }
        },

        slideChangeTransitionEnd: function () {
            if ("progressbar" in _obj_options) {
                _swiper_progress.classList.add('progress');
            }
        }
    };

    /**console.log(_obj_options);*/
    let _swiper = new Swiper('.' + _class, _result_options);
    if (!("autoplay" in _obj_options) && !("marquee" in _obj_options)) {
        _swiper.autoplay.stop();
    }

    /** now add mouseover and mouseout events to pause and resume the autoplay;*/
    el.addEventListener('mouseover', () => {
        _swiper.autoplay.stop();
    });
    el.addEventListener('mouseout', () => {
        if ("autoplay" in _obj_options) {
            _swiper.autoplay.start();
        }
    });
});