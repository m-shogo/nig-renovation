"use strict";
(function ($) {
  // ==========================================================================
  // トップスライダー設定
  // ==========================================================================
  const topSlider = function () {
    // Swiperだと非アクティブタブ時にCSSのフェードアニメーションと同期がずれるので下記URLの方法で実装
    // https://allabout.co.jp/gm/gc/417216/2/
    const slide = '.tm_slide-container';
    function slideSwitch() {
      let active = $(slide).find('.slider-slide.active');
      if (active.length == 0) active = $(slide).find('.slider-slide:last-of-type');
      const next = active.next().length ? active.next() : $(slide).find('.slider-slide:first-of-type');
      active.addClass('last-active');
      next.css({ opacity: 0.0 }).addClass('active').animate({ opacity: 1.0 }, 1000, function () {
        active.removeClass('active last-active');
      });
    }
    $(window).on('load', function () {
      setInterval(slideSwitch, 8000);
    });
  };
  const researchSlider = function () {
    $(window).on('load', function () {
      const research_swiper = new Swiper('.research_swiper-container', {
        slidesPerView: 'auto',
        spaceBetween: 24,
        loop: false,
        speed: 1000,
        watchOverflow: true,
        scrollbar: {
          el: ".swiper-scrollbar",
          draggable: true,
          hide: false,
        },
        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev',
        },
        breakpoints: {
          1000: {
            spaceBetween: 32
          },
        },
      });
      const minH = $('.top_research-01 .main .list').height();
      $('.top_research-01 .main').css('min-height', minH);
    });
  };
  const scientistsSlider = function () {
    const slide = '.scientists_swiper-container';
    if ($(slide).find('.swiper-slide').length < 2) {
      $(slide).addClass('_1col');
    } else if ($(slide).find('.swiper-slide').length < 5) {
      $(slide).addClass('_4col');
    }
    $(window).on('load', function () {
      const scientists_swiper = new Swiper(slide, {
        slidesPerView: 'auto',
        spaceBetween: 16,
        loop: true,
        speed: 1000,
        watchOverflow: true,
        centeredSlides : true,
        autoplay: {
          delay: 2000,
          disableOnInteraction: false
        },
        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev',
        },
        breakpoints: {
          1000: {
            spaceBetween: 32,
          },
        },
      });
    });
  };

  // ==========================================================================
  // タブ切り替え設定 https://gist.github.com/sakunyo/4538094
  // ==========================================================================
  const dualTabSetting = function () {
    $('.module_tab-01').dualTab({
      selected: '_current',
      menus: 'li',
      body: '.module_tab-body'
    });
  };

  // ==========================================================================
  // 実行
  // ==========================================================================
  topSlider();
  researchSlider();
  scientistsSlider();
  dualTabSetting();

})(jQuery);
