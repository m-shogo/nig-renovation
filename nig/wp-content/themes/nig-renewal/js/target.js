"use strict";
(function ($) {
  // ==========================================================================
  // トップスライダー設定
  // ==========================================================================
  const targetTopSlider = function () {
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
  targetTopSlider();
  dualTabSetting();

})(jQuery);
