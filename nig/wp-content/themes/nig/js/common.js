'use strict';
// ==========================================================================
// OSやブラウザを判定してbodyにclassを付与
// ==========================================================================
const ua = navigator.userAgent.toLowerCase(),
  ver = navigator.appVersion.toLowerCase(),
  rootClass = document.documentElement.classList;
//ブラウザ判定
const isMSIE = ua.indexOf('msie') > -1 && ua.indexOf('opera') === -1, // IE(11以外)
  isIE6 = isMSIE && ver.indexOf('msie 6.') > -1, // IE6
  isIE7 = isMSIE && ver.indexOf('msie 7.') > -1, // IE7
  isIE8 = isMSIE && ver.indexOf('msie 8.') > -1, // IE8
  isIE9 = isMSIE && ver.indexOf('msie 9.') > -1, // IE9
  isIE10 = isMSIE && ver.indexOf('msie 10.') > -1, // IE10
  isIE11 = ua.indexOf('trident/7') > -1, // IE11
  isIE = isMSIE || isIE11, // IE
  isEdge = ua.indexOf('edge') > -1, // Edge
  isChrome = ua.indexOf('chrome') > -1 && ua.indexOf('edge') === -1, // Google Chrome
  isFirefox = ua.indexOf('firefox') > -1, // Firefox
  isSafari = ua.indexOf('safari') > -1 && ua.indexOf('chrome') === -1, // Safari
  isOpera = ua.indexOf('opera') > -1; // Opera
{
  //デバイス判定
  const _ua = (function (u) {
    return {
      Tablet:
        (u.indexOf('windows') !== -1 &&
          u.indexOf('touch') !== -1 &&
          u.indexOf('tablet pc') === -1) ||
        u.indexOf('ipad') !== -1 ||
        (u.indexOf('android') !== -1 && u.indexOf('mobile') === -1) ||
        (u.indexOf('firefox') !== -1 && u.indexOf('tablet') !== -1) ||
        u.indexOf('kindle') !== -1 ||
        u.indexOf('silk') !== -1 ||
        u.indexOf('playbook') !== -1,
      Mobile:
        (u.indexOf('windows') !== -1 && u.indexOf('phone') !== -1) ||
        u.indexOf('iphone') !== -1 ||
        u.indexOf('ipod') !== -1 ||
        (u.indexOf('android') !== -1 && u.indexOf('mobile') !== -1) ||
        (u.indexOf('firefox') !== -1 && u.indexOf('mobile') !== -1) ||
        u.indexOf('blackberry') !== -1,
    };
  })(window.navigator.userAgent.toLowerCase());

  if (_ua.Mobile) {
    rootClass.add('_device-mobile'); //スマホならつけるクラス
  } else if (_ua.Tablet) {
    rootClass.add('_device-tablet'); //タブレットならつけるクラス
  } else {
    rootClass.add('_device-pc'); //スマホ・タブレット以外ならつけるクラス
  }
  if (navigator.platform.indexOf('Win') !== -1) {
    rootClass.add('_os-win'); //Windowsならつけるクラス
  } else if (navigator.platform.toLowerCase().indexOf('mac') > -1) {
    rootClass.add('_os-mac'); //Windows以外ならつけるクラス
  }
  if (ua.indexOf('iPhone') > 0) {
    rootClass.add('_mobile-iphone'); //iPhoneならつけるクラス
  } else if (ua.indexOf('Android') > 0 && ua.indexOf('Mobile') > 0) {
    rootClass.add('_mobile-android'); //Androidのスマホならつけるクラス
  } else if (ua.indexOf('iPad') > 0) {
    rootClass.add('_mobile-ipad'); //iPadならつけるクラス
  }
  if (isOpera) {
    rootClass.add('_browser-opera'); //オペラならつけるクラス
  } else if (isIE) {
    rootClass.add('_browser-ie'); //IEならつけるクラス
  } else if (isChrome) {
    rootClass.add('_browser-chrome'); //Chromeならつけるクラス
  } else if (isSafari) {
    rootClass.add('_browser-safari'); //Safariならつけるクラス
  } else if (isEdge) {
    rootClass.add('_browser-edge'); //Edgeならつけるクラス
  } else if (isFirefox) {
    rootClass.add('_browser-firefox'); //Firefoxならつけるクラス
  }
}

// ==========================================================================
// CSS遅延読み込み（class="async"をrel="stylesheet"に置換）
// ==========================================================================
{
  const webFonts = document.querySelectorAll('.async');
  for (let i = 0, l = webFonts.length; i < l; i++) {
    webFonts[i].rel = 'stylesheet';
  }
}

// ==========================================================================
//【CSS】カスタムプロパティvw https://www.6666666.jp/html/20220127/
// ==========================================================================
const setVw = function () {
  const vw = document.documentElement.clientWidth / 100;
  document.documentElement.style.setProperty('--vw', `${vw}px`);
};
window.addEventListener('DOMContentLoaded', setVw);
window.addEventListener('resize', setVw);

(function ($) {
  // ==========================================================================
  // ターゲットが特定位置を過ぎたらclass付与（ヘッダー追従）https://terkel.jp/archives/2011/05/jquery-floating-widget-plugin/
  // ==========================================================================
  $.fn.floatingWidget = function () {
    return this.each(function () {
      const $this = $(this),
        $parent = $this.offsetParent(),
        $body = $('body'),
        $window = $(window),
        top =
          $this.offset().top -
          parseFloat($this.css('marginTop').replace(/auto/, 0)),
        // bottom = $parent.offset().top + $parent.height() - $this.outerHeight(true),
        floatingClass = '_fixed';
      // pinnedBottomClass = '_bottom';
      if ($parent.height() > $this.outerHeight(true)) {
        $window.on('load scroll', function () {
          let y = $window.scrollTop();
          if (y > top) {
            $body.addClass(floatingClass);
          } else {
            $body.removeClass(floatingClass);
          }
        });
      }
    });
  };

  // ==========================================================================
  // グローバルナビゲーションカレント制御
  // ==========================================================================
  const globalNavCurrent = function () {
    const nav = $('#global_navigation'); // 第二階層をデフォルトで折りたたみ
    let condIndex =
      /(http[s]?:\/\/(?:[\w-_]+\.?){1,6}(?::\d+)?\/?)|\/[0-9]*\/[0-9]*\/[\w]*\.html?|\/[\w]*\.html?/; // index 除去条件
    let path = location.href.replace(condIndex, '/'); // URL
    const checks = [path];

    // 文字列末尾から 1 ディレクトリずつ削り、URL を収集
    while (path && '/' !== path) {
      path = path.replace(/[^/]*\/?$/, '');
      checks.push(path);
    }

    // メニューの href 属性値をすべて取得
    let href = $('a', nav).map(function () {
      return $(this).attr('href').replace(condIndex, '/');
    });

    // URL と href 属性値の一致を判定
    // 一致した a 要素には class 属性値「current」を付与
    checkStart: for (let i = 0; i < checks.length; i++) {
      for (let j = 0; j < href.length; j++) {
        if (checks[i] === href[j]) {
          $('a', nav)
            .eq(j)
            .addClass('_current')
            .parents('li')
            .addClass('_parent');
          break checkStart;
        }
      }
    }
  };

  // ==========================================================================
  // ローカルナビゲーションカレント制御
  // ==========================================================================
  const localNavCurrent = () => {
    const $nav = $('#local_navigation');

    // index.html などを除去するためのパターン
    const urlPattern =
      /(http[s]?:\/\/(?:[\w-_]+\.?){1,6}(?::\d+)?\/?)|\/[0-9]*\/[0-9]*\/[\w-]+\.(?:html?)?/;

    // URL文字列を正規化
    const normalize = (url) => {
      return (
        url
          .replace(urlPattern, '/') // ドメイン / index.html などを除去
          .replace(/\/$/, '') || '/'
      ); // 末尾 / を揃える
    };

    // 現在ページの階層リストを生成
    const getCurrentPathHierarchy = () => {
      let path = normalize(
        `${location.protocol}//${location.host}${location.pathname}`,
      );
      const hierarchy = [path];
      while (path && path !== '/') {
        path = path.replace(/[^/]*\/?$/, '').replace(/\/$/, '') || '/';
        hierarchy.push(path);
      }
      return hierarchy;
    };

    // ナビ内リンクを抽出して正規化
    const getNavLinks = () =>
      $('a', $nav)
        .toArray()
        .map((el) => {
          const $link = $(el);
          const raw = $link.attr('href') || '';
          if (!raw || raw.startsWith('#') || raw.startsWith('javascript:'))
            return null;
          return { element: $link, href: normalize(raw) };
        })
        .filter(Boolean); // null を除外

    // クラス付与
    const setCurrentState = ($el) => {
      $el
        .addClass('_current')
        .parents('li')
        .addClass('_parent')
        .closest('.lnl_wrapper')
        .css('display', 'block')
        .prevAll('button')
        .addClass('_open');
    };

    // メイン処理
    const hierarchy = getCurrentPathHierarchy();
    const links = getNavLinks();

    let best = null;
    let depth = 0;
    for (const { element, href } of links) {
      if (hierarchy.includes(href)) {
        const d = href.split('/').filter(Boolean).length;
        if (d > depth) {
          best = element;
          depth = d;
        }
      }
    }
    if (best) setCurrentState(best);
  };

  // ==========================================================================
  // スムーススクロール制御（ヘッダー分の高さは「const header = $('#global_header').height() * 2.5;」で調整）
  // ==========================================================================
  const pageScroll = function () {
    $(document).ready(function () {
      //URLのハッシュ値を取得
      const urlHash = location.hash;
      //ハッシュ値があればページ内スクロール
      if ('' !== urlHash) {
        //スクロールを0に戻しておく
        $('body,html').stop().scrollTop(1);
        setTimeout(function () {
          //ロード時の処理を待ち、時間差でスクロール実行
          scrollToAnker(urlHash);
        }, 300);
      }

      //通常のクリック時
      $('a[href^="#"]')
        .not(
          'ul[class*="tab-head"] a[href^="#"], .lnl_title , .frm_repeat_buttons a',
        )
        .on('click', function () {
          //ページ内リンク先を取得
          const href = $(this).attr('href');
          //リンク先が#か空だったらhtmlに
          const hash = href === '#' || href === '' ? 'html' : href;
          //スクロール実行
          scrollToAnker(hash);
          //リンク無効化
          return false;
        });

      // 関数：スムーススクロール
      // 指定したアンカー(#ID)へアニメーションでスクロール
      function scrollToAnker(hash) {
        const target = $(hash);
        const w = $(window).width();
        let header = 0;
        let position = 0;
        if (w <= 768) {
          header = $('.headerNavigationInner').innerHeight() + 40;
          position = target.offset().top - header;
        } else {
          header = $('.headerNavigationInner').innerHeight() + 56;
          position = target.offset().top - header;
        }
        $('body,html').stop().animate({ scrollTop: position }, 300);
      }
    });
  };

  // ==========================================================================
  // SP用メニュー＆PCメガメニュー設定
  // ==========================================================================
  const toggleMenu = function () {
    const $body = $('body');
    // グローバルナビゲーション
    $('#gh_menu').on('click', function () {
      $body.toggleClass('_open-menu _open-bg');
    });
    $('#gn_close').on('click', function () {
      $body.removeClass('_open-menu _open-bg');
    });
    $(
      '.module_menu-01 > li [class*="mm_button"], .gf_links-01 > li .gfl_button,.menuListInner .mm_inner-01 ul > li [class*="mm_button"]',
    ).on('click', function () {
      $(this).next().next().toggleClass('_open').slideToggle(400);
      $(this).toggleClass('_open');
    });

    // グローバルメニューの動き
    $('.global_navigation .gn_links-01 > li._hasChild').on({
      mouseenter: function () {
        const height = $(this).find('.gnl_inner').outerHeight(true);
        if ($('html').hasClass('_pc')) {
          $body.addClass('_open-bg');
          $(this).find('.gnl_wrapper').first().css({
            height: 0,
            visibility: 'inherit',
            opacity: '1',
            'pointer-events': 'auto',
          });
          $(this)
            .find('.gnl_wrapper')
            .first()
            .stop(true, true)
            .animate({ height: height }, 400, 'swing');
        }
        //ここにはマウスを離したときの動作を記述
      },
      mouseleave: function () {
        if ($('html').hasClass('_pc')) {
          $body.removeClass('_open-bg');
          $(this)
            .find('.gnl_wrapper')
            .animate({ height: 0 }, 400, 'swing')
            .queue(function () {
              $(this).removeAttr('style');
              $(this).dequeue();
            });
        }
      },
    });

    // 検索
    $('#search').on('click', function () {
      if ($body.hasClass('_open-bg')) {
        $body.addClass('_open-search');
      } else {
        if ($body.hasClass('_open-search')) {
          $body.removeClass('_open-search').removeClass('_open-bg');
        } else {
          $body.addClass('_open-search').addClass('_open-bg');
        }
      }
    });

    $('#gns_close, #overlay').on('click', function () {
      $body
        .removeClass('_open-search')
        .removeClass('_open-menu')
        .removeClass('_open-bg');
    });

    // アーカイブ用のローカルナビゲーションを開閉式にする
    $('body:not(.page) #local_navigation .ln_links-01 > li > .lnl_title').on(
      'click',
      function () {
        $(this).next().toggleClass('_open').slideToggle(400);
        $(this).toggleClass('_open');
        return false;
      },
    );
    // アーカイブナビゲーション
    $('.an_links-01 > li > .anl_title').on('click', function () {
      $(this).next().toggleClass('_open').slideToggle(400);
      $(this).toggleClass('_open');
    });
    // アーカイブナビゲーション枠外クリック時に閉じる
    $(document).on('touchstart click', function (event) {
      if (!$(event.target).closest('.an_links-01').length) {
        $('.an_links-01 > li > .anl_title')
          .removeClass('_open')
          .next('.anl_wrapper')
          .removeClass('_open')
          .slideUp(400)
          .find('.anl_button, .anl_wrapper')
          .removeClass('_open')
          .siblings('.anl_wrapper')
          .slideUp(400);
      } else {
      }
    });
    // ウィンドウ幅によってクラス付与
    $(window).on('load resize', function () {
      const w = $(window).width();
      if (w <= 768) {
        $('html').addClass('_sp').removeClass('_pc _tablet');
      } else if (w < 1024) {
        $('html').addClass('_tablet').removeClass('_pc _sp');
      } else {
        $('html').addClass('_pc').removeClass('_tablet _sp');
      }
    });
  };

  // ==========================================================================
  // Class付与
  // ==========================================================================
  const addCss = function () {
    // .global_contents内の新窓リンクにclass追加
    const notIcon = $(
      '[class*="module_card-"] a, .global_contents p a.icon-none, .top_recommends-01 a',
    );
    $('.global_contents a:not([class])[target="_blank"]')
      .not(notIcon)
      .each(function () {
        $(this).addClass('icon-blank');
      });

    // エディタ内の表にclass追加
    $('.gc_main table:not([class])').each(function () {
      $(this).addClass('module_table-01');
    });

    // WordPressページネーションのclass付け替え
    $('ul.page-numbers')
      .addClass('module_pager-01')
      .removeClass('page-numbers');
    $('.module_pager-01 li.current')
      .prev('li')
      .addClass('current_prev')
      .prev('li')
      .addClass('current_prev2')
      .prev('li')
      .addClass('current_prev3')
      .prev('li')
      .addClass('current_prev4');
    $('.module_pager-01 li.current')
      .next('li')
      .addClass('current_next')
      .next('li')
      .addClass('current_next2')
      .next('li')
      .addClass('current_next3')
      .next('li')
      .addClass('current_next4');
  };

  // ==========================================================================
  // タグ追加・削除
  // ==========================================================================
  const initHTML = function () {
    // 空のpタグ削除
    $('.gc_main p').each(function () {
      let txt = $(this);
      if (txt.html().replace(/\s|&nbsp;/g, '').length === 0) {
        txt.remove();
      }
    });

    // 表にスクロール用ラッパー要素とclassを追加
    $('.module_table-01').wrap('<div class="module_table-wrap"></div>');
    $(window).on('load resize', function () {
      $('.module_table-wrap').each(function () {
        const $wrapperWidth = $(this).width();
        const $innerWidth = $(this).find('.module_table-01').width();
        if ($wrapperWidth < $innerWidth) {
          $(this).addClass('_scroll');
        } else {
          $(this).removeClass('_scroll');
        }
      });
    });

    // パスワード保護フォームinputにラッパー要素追加（疑似要素追加用）
    $('.module_password form input[type="submit"]').wrap(
      '<span class="mp_submit-wrap"><span class="mp_submit-inner"></span></span>',
    );
  };

  // ==========================================================================
  // モジュールモーダル設定 http://www.humaan.com/modaal/
  // ==========================================================================
  const moduleModal = function () {
    $('[class*="module_gallery"] a').modaal({
      type: 'image',
    });
  };

  // ==========================================================================
  // モジュールアコーディオン設定
  // ==========================================================================
  const moduleAccordion = function () {
    $('.module_accordion-01 > li').each(function () {
      const $list = $(this);
      const $button = $(this).find('.head');
      $list.find('.body').hide();
      $button.on('click', function () {
        if ($list.hasClass('_open')) {
          $list.removeClass('_open');
          $list.find('.body').slideUp(300);
        } else {
          // 項目を開いたときに他の項目を閉じる場合は下記を追加
          // $('.module_faqList-01 > li').removeClass('_open').find('.body').slideUp(300);
          $list.addClass('_open');
          $list.find('.body').slideDown(300);
        }
      });
    });
  };

  // ==========================================================================
  // メニューオープン時背景のスクロール禁止
  // ==========================================================================
  const contentFixed = function () {
    let scrollpos;
    $('#gh_menu').on('click', function () {
      if (!$('body').hasClass('_contentFixed')) {
        scrollpos = $(window).scrollTop();
        $('body').addClass('_contentFixed').css({ top: -scrollpos });
      } else {
        $('body').removeClass('_contentFixed').css({ top: 0 });
        window.scrollTo(0, scrollpos);
      }
    });
    $('#gn_close').on('click', function () {
      $('body').removeClass('_contentFixed').css({ top: 0 });
      window.scrollTo(0, scrollpos);
    });
  };

  // ==========================================================================
  // pageTop
  // ==========================================================================
  const pageTop = function () {
    const gf_pageTop = $('#js_gf_pageTop');
    const windowWidth = $(window).width();
    gf_pageTop.hide();
    $(window).on('scroll', function () {
      if ($(this).scrollTop() > 0) {
        gf_pageTop.fadeIn();
      } else {
        gf_pageTop.fadeOut();
      }
    });
  };

  // ==========================================================================
  // メニューオープン時文言変更
  // ==========================================================================
  // const menuTextChange = function () {
  //     let menuText = $("#menuButton").children(".menuText");
  //     $("#menuButton").on("click", function () {
  //         if ($("body").hasClass("_open-menu")) {
  //             menuText.text("CLOSE");
  //         } else {
  //             menuText.text("MENU");
  //         }
  //     });

  //     $(window).on("load resize", function () {
  //         menuText.text("MENU");
  //     });
  // };

  // ==========================================================================
  // .wp-lightbox-containerをラップ
  // ==========================================================================
  const lightboxWrap = function () {
    $('.block-editor_wrap figure.wp-lightbox-container img').each(function () {
      $(this).wrap('<div class="lightbox_wrap"></div>');
    });
  };

  // ==========================================================================
  // タブ
  // ==========================================================================
  const tab = function () {
    document.addEventListener('DOMContentLoaded', () => {
      document
        .querySelectorAll('.module_tab-wrapper')
        .forEach((tabWrapper, wrapperIndex) => {
          const panels = tabWrapper.querySelectorAll('.tab-panel');
          const buttonsContainer = tabWrapper.querySelector('.tab-buttons');

          panels.forEach((panel, index) => {
            // タイトルを取得（未指定なら Tab 1, 2...）
            const title = panel.dataset.title || `Tab ${index + 1}`;

            // ユニークIDを生成（日本語は slugify して短縮）
            const slug = title
              .toLowerCase()
              .normalize('NFKD') // 濁点など除去
              .replace(/[^\w\s-]/g, '') // 記号除去
              .trim()
              .replace(/\s+/g, '-') // 空白→ハイフン
              .slice(0, 20); // 長すぎるIDを防止

            const tabId = `tab-${wrapperIndex + 1}-${slug || index + 1}`;
            panel.id = tabId;

            // ボタン生成
            const button = document.createElement('button');
            button.className = 'tab-button';
            button.setAttribute('data-tab', tabId);
            button.setAttribute('type', 'button');
            button.innerText = title;

            button.addEventListener('click', () => {
              panels.forEach((p) => (p.style.display = 'none'));
              tabWrapper
                .querySelectorAll('.tab-button')
                .forEach((b) => b.classList.remove('active'));
              panel.style.display = '';
              button.classList.add('active');
            });

            buttonsContainer.appendChild(button);
          });

          // 初期状態：最初のパネルを表示
          if (panels.length > 0) {
            panels.forEach((p) => (p.style.display = 'none'));
            panels[0].style.display = '';
            const firstButton = buttonsContainer.querySelector('.tab-button');
            if (firstButton) firstButton.classList.add('active');
          }
        });
    });
  };

  // ==========================================================================
  // アーカイブナビゲーション
  // ==========================================================================
  const archiveNavigation = function () {
    $('#anl_button').on('click', function () {
      $(this).toggleClass('active');
      $(this).next('.anl_list').slideToggle(300);
    });
  };
  
  const module_menu = function () {
    // メニュー開閉（他を閉じる＆開いてるtitleを押したら閉じる）機能
    $('.module_menu-01 .title').on('click', function () {
      const $body = $('body');
      $body.removeClass('_open-search').removeClass('_open-bg');
      $('.global_search').slideUp(300);

      const $this = $(this);
      const $menuList = $this.next('.menuList');
      const $opened = $('.module_menu-01 .menuList:visible').not($menuList);

      if ($this.hasClass('_open')) {
        // 自分が開いている場合は閉じる
        $this.removeClass('_open');
        $menuList.slideUp(300);
      } else {
        // 他のメニューが開いていたら、まず閉じる
        $opened.prev('.title').removeClass('_open');
        $opened.slideUp(300);
        // 少し遅らせて開く（200ms後）
        setTimeout(function () {
          $this.addClass('_open');
          $menuList.slideDown(400);
        }, 300);
      }
    });

    // closeButton（×ボタン）でメニューを閉じる
    $('.module_menu-01 .closeButton').on('click', function () {
      // 親の.menuListを閉じる
      $(this).closest('.menuList').slideUp(300);
      // 対応する.titleボタンの「_open」クラスも外す
      $(this).closest('.menuList').prev('.title').removeClass('_open');
    });

    // 初期状態で全て閉じておく（必要なら）
    $('.module_menu-01 .menuList').hide();

    $('.gh_menu').on('click', function () {
      $('.module_menu-01 .menuList').slideUp(300);
      $('.module_menu-01 .title').removeClass('_open');
      const $body = $('body');
      // if ($body.hasClass('_open-search')) {
      //   $body.removeClass('_open-search').removeClass('_open-bg');
      //   $('.global_search').slideUp(300);
      // } else {
      //   $body.addClass('_open-search').addClass('_open-bg');
      //   $('.global_search').slideDown(300);
      // }
      
    });
  };

// menuCurrent: URLに応じて ._current を付与する処理
// ======================================================
function menuCurrent() {
  // 現在のページのパスを取得（例: "/researchers/"）
  var currentPath = window.location.pathname;

  // ① .menuList 内の a に _current を付ける
  $('.menuList a').each(function() {
    var $link = $(this);
    var href = $link.attr('href');
    if (!href) return; // href がない a はスキップ

    var linkPath;

    // 絶対URL（http から始まる）の場合
    if (/^https?:\/\//.test(href)) {
      try {
        var urlObj = new URL(href);
        // 別ドメインのリンク（外部リンク）は _current の対象外
        if (urlObj.hostname !== window.location.hostname) return;
        linkPath = urlObj.pathname; // パス部分だけ取り出す
      } catch (e) {
        linkPath = href; // パース失敗時はそのまま
      }
    } else {
      // 相対パス（/researchers/ など）の場合
      linkPath = href;
    }

    // 末尾のスラッシュを削って揃える（/researchers と /researchers/ を同一視）
    var normalizedCurrent = currentPath.replace(/\/+$/, '') || '/';
    var normalizedLink = linkPath.replace(/\/+$/, '') || '/';

    // パスが完全一致したら _current を付与
    if (normalizedCurrent === normalizedLink) {
      // 該当リンクに _current
      $link.addClass('_current');

      // そのリンクを含んでいる menuList にも _current を付けておく
      $link.closest('.menuList').addClass('_current');
    }
  });

  // ② _current が付いた .menuList の「一つ上の button.title」に _current を付与
  $('.menuList._current').each(function() {
    var $menuList = $(this);

    // 一つ前の兄弟要素の button.title を取得
    // 構造:
    // <button class="title">研究者の方</button>
    // <div class="menuList _current">...</div>
    var $button = $menuList.prev('button.title');

    if ($button.length > 0) {
      $button.addClass('_current');
    }
  });
}

  // ==========================================================================
  // single: 目次開閉
  // ==========================================================================
  const articleToc = function () {
    const tocModules = document.querySelectorAll('.js-article-toc');
    if (!tocModules.length) {
      return;
    }

    tocModules.forEach((toc) => {
      const list = toc.querySelector('.mt_list');
      const toggleButton = toc.querySelector('.js-article-toc-toggle');
      if (!list || !toggleButton) {
        return;
      }

      const openLabel = toggleButton.getAttribute('data-open-label') || 'Open';
      const closeLabel = toggleButton.getAttribute('data-close-label') || 'Close';
      let isAnimating = false;

      const setOpenState = (isOpen) => {
        toc.classList.toggle('_closed', !isOpen);
        toggleButton.textContent = isOpen ? closeLabel : openLabel;
        toggleButton.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
      };

      setOpenState(true);
      $(list).show().css('opacity', 1);

      toggleButton.addEventListener('click', () => {
        if (isAnimating) {
          return;
        }

        const isOpen = !toc.classList.contains('_closed');
        isAnimating = true;

        if (isOpen) {
          $(list).stop(true, true).animate(
            { height: 'hide', opacity: 0 },
            260,
            'swing',
            () => {
              setOpenState(false);
              isAnimating = false;
            },
          );
          return;
        }

        setOpenState(true);
        $(list).stop(true, true).css('opacity', 0).animate(
          { height: 'show', opacity: 1 },
          300,
          'swing',
          () => {
            list.style.height = '';
            list.style.opacity = '';
            isAnimating = false;
          },
        );
      });
    });
  };

  // ==========================================================================
  // single: ACFツールチップ
  // ==========================================================================
  const articleTooltip = function () {
    const scopes = Array.from(document.querySelectorAll('.js-tooltip-scope'));
    if (!scopes.length) {
      return;
    }

    const targetScopes = scopes.filter((scope) => {
      return !!(
        scope.querySelector('.js-tooltip-content') &&
        scope.querySelector('.js-tooltip-data')
      );
    });
    if (!targetScopes.length) {
      return;
    }

    const escapeRegExp = (value) =>
      value.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');

    const popover = document.createElement('div');
    popover.className = 'acf-tooltip-popover js-tooltip-popover';
    popover.setAttribute('aria-hidden', 'true');
    popover.innerHTML = `
      <button type="button" class="acf-tooltip-popover__close" aria-label="閉じる">&times;</button>
      <p class="acf-tooltip-popover__title js-tooltip-popover-title"></p>
      <div class="acf-tooltip-popover__text js-tooltip-popover-text"></div>
    `;
    document.body.appendChild(popover);

    const popoverTitle = popover.querySelector('.js-tooltip-popover-title');
    const popoverText = popover.querySelector('.js-tooltip-popover-text');
    const popoverClose = popover.querySelector('.acf-tooltip-popover__close');
    let activeTrigger = null;

    const placePopover = (trigger) => {
      if (!trigger) {
        return;
      }
      const triggerRect = trigger.getBoundingClientRect();
      const margin = 12;
      const viewportPadding = 8;

      popover.classList.add('_measuring');
      popover.classList.add('_open');
      const popoverRect = popover.getBoundingClientRect();
      popover.classList.remove('_measuring');

      const candidates = [
        {
          left: triggerRect.right + margin,
          top: triggerRect.bottom + margin,
        },
        {
          left: triggerRect.left - popoverRect.width - margin,
          top: triggerRect.bottom + margin,
        },
        {
          left: triggerRect.right + margin,
          top: triggerRect.top - popoverRect.height - margin,
        },
        {
          left: triggerRect.left - popoverRect.width - margin,
          top: triggerRect.top - popoverRect.height - margin,
        },
      ];

      const inViewport = (position) => (
        position.left >= viewportPadding &&
        position.top >= viewportPadding &&
        position.left + popoverRect.width <= window.innerWidth - viewportPadding &&
        position.top + popoverRect.height <= window.innerHeight - viewportPadding
      );

      let selected = candidates.find(inViewport);
      if (!selected) {
        selected = {
          left: Math.min(
            Math.max(candidates[0].left, viewportPadding),
            window.innerWidth - popoverRect.width - viewportPadding,
          ),
          top: Math.min(
            Math.max(candidates[0].top, viewportPadding),
            window.innerHeight - popoverRect.height - viewportPadding,
          ),
        };
      }

      popover.style.left = `${selected.left}px`;
      popover.style.top = `${selected.top}px`;
    };

    const closePopover = () => {
      popover.classList.remove('_open');
      popover.setAttribute('aria-hidden', 'true');
      activeTrigger = null;
    };

    const openPopover = (item, trigger) => {
      if (!popoverTitle || !popoverText) {
        return;
      }
      popoverTitle.textContent = item.title;
      popoverText.textContent = item.text;
      popover.setAttribute('aria-hidden', 'false');
      activeTrigger = trigger;
      placePopover(trigger);
    };

    popoverClose.addEventListener('click', closePopover);

    document.addEventListener('click', (event) => {
      if (!popover.classList.contains('_open')) {
        return;
      }
      if (event.target.closest('.js-tooltip-trigger')) {
        return;
      }
      if (event.target.closest('.js-tooltip-popover')) {
        return;
      }
      closePopover();
    });

    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape' && popover.classList.contains('_open')) {
        closePopover();
      }
    });

    window.addEventListener('resize', () => {
      if (popover.classList.contains('_open') && activeTrigger) {
        placePopover(activeTrigger);
      }
    });

    window.addEventListener('scroll', () => {
      if (popover.classList.contains('_open') && activeTrigger) {
        placePopover(activeTrigger);
      }
    });

    targetScopes.forEach((scope) => {
      const content = scope.querySelector('.js-tooltip-content');
      const dataNode = scope.querySelector('.js-tooltip-data');
      if (!content || !dataNode) {
        return;
      }

      let items = [];
      try {
        items = JSON.parse(dataNode.textContent || '[]');
      } catch (error) {
        return;
      }

      const tooltipItems = items
        .map((item) => ({
          id: item && item.id ? String(item.id) : '',
          title: item && item.title ? String(item.title) : '',
          text: item && item.text ? String(item.text) : '',
        }))
        .filter((item) => item.id && item.title && item.text)
        .sort((a, b) => b.title.length - a.title.length);

      if (!tooltipItems.length) {
        return;
      }

      const titlePattern = tooltipItems.map((item) => escapeRegExp(item.title)).join('|');
      if (!titlePattern) {
        return;
      }

      const matcher = new RegExp(titlePattern, 'gi');
      const titleMap = new Map();
      const englishTitleMap = new Map();
      const idMap = new Map();
      tooltipItems.forEach((item) => {
        titleMap.set(item.title, item);
        if (/[A-Za-z]/.test(item.title)) {
          const lowerTitle = item.title.toLowerCase();
          if (!englishTitleMap.has(lowerTitle)) {
            englishTitleMap.set(lowerTitle, item);
          }
        }
        idMap.set(item.id, item);
      });

      const shouldSkip = (textNode) => {
        if (!textNode || !textNode.parentElement) {
          return true;
        }
        return !!textNode.parentElement.closest(
          'a, button, script, style, textarea, .acf-tooltip-trigger, .js-tooltip-popover, [data-tooltip-ignore]',
        );
      };

      const walker = document.createTreeWalker(
        content,
        NodeFilter.SHOW_TEXT,
        {
          acceptNode(textNode) {
            const raw = textNode.nodeValue || '';
            if (!raw.trim() || shouldSkip(textNode)) {
              return NodeFilter.FILTER_REJECT;
            }
            matcher.lastIndex = 0;
            return matcher.test(raw)
              ? NodeFilter.FILTER_ACCEPT
              : NodeFilter.FILTER_REJECT;
          },
        },
      );

      const targetNodes = [];
      while (walker.nextNode()) {
        targetNodes.push(walker.currentNode);
      }

      targetNodes.forEach((textNode) => {
        const raw = textNode.nodeValue || '';
        matcher.lastIndex = 0;
        let match;
        let lastIndex = 0;
        let replaced = false;
        const fragment = document.createDocumentFragment();

        while ((match = matcher.exec(raw)) !== null) {
          const title = match[0];
          let item = titleMap.get(title);
          if (!item && /[A-Za-z]/.test(title)) {
            item = englishTitleMap.get(title.toLowerCase());
          }
          if (!item) {
            continue;
          }

          replaced = true;
          if (match.index > lastIndex) {
            fragment.appendChild(
              document.createTextNode(raw.slice(lastIndex, match.index)),
            );
          }

          const trigger = document.createElement('button');
          trigger.type = 'button';
          trigger.className = 'acf-tooltip-trigger js-tooltip-trigger';
          trigger.setAttribute('data-tooltip-id', item.id);
          trigger.textContent = title;
          fragment.appendChild(trigger);

          lastIndex = match.index + title.length;
        }

        if (!replaced) {
          return;
        }

        if (lastIndex < raw.length) {
          fragment.appendChild(document.createTextNode(raw.slice(lastIndex)));
        }
        textNode.parentNode.replaceChild(fragment, textNode);
      });

      content.addEventListener('click', (event) => {
        const trigger = event.target.closest('.js-tooltip-trigger');
        if (!trigger || !content.contains(trigger)) {
          return;
        }
        event.preventDefault();
        const tooltipId = trigger.getAttribute('data-tooltip-id');
        const item = tooltipId ? idMap.get(tooltipId) : null;
        if (!item) {
          return;
        }
        if (activeTrigger === trigger && popover.classList.contains('_open')) {
          closePopover();
          return;
        }
        openPopover(item, trigger);
      });
    });
  };

  // ==========================================================================
  // 実行
  // ==========================================================================
  $('#global_header').floatingWidget();
  globalNavCurrent();
  localNavCurrent();
  pageScroll();
  toggleMenu();
  addCss();
  initHTML();
  moduleModal();
  moduleAccordion();
  contentFixed();
  // pageTop();
  // menuTextChange();
  lightboxWrap();
  tab();
  archiveNavigation();
  module_menu();
  menuCurrent();
  articleToc();
  articleTooltip();
})(jQuery);
