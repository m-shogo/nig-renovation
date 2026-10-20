$(function(){

    //pc表示の時にsub-menuを再表示する
    function media() {
        // 横幅を取得
        // var width = $(window).width();
        var width = window.innerWidth;
        if(width > 750) {
            $('#main-nav .sub-menu').show();
        } else {
            // $('#main-nav .sub-menu').hide();
        }
    }

    $(window).on("resize", function(){ media(); });



    /////////////////////////////////////////////////////////////


    //メニューの▼要素追加
    $('#main-nav li.menu-item-has-children > a').append('<span class="_naviArrow">▼</span>');

    /////////////////////////////////////////////////////////////


    //大メニューの開閉
    // Elements
    // var elm_button = $('#main-nav ._naviArrow');
    var elm_button = $('#main-nav > li');

    // Vars

    // init
    function init() {
        elm_button.live('click', function(e){

            $(this).children('a').toggleClass('is-open');
            $(this).children('._subCategory').slideToggle();

            e.stopPropagation();
        });
    }

    init();

    ///////////////////////////////////////////////////////////////


    //サブメニューの開閉
    // Elements
    var elm_button2 = $('#main-nav ._subCategory .menu-item-has-children');

    // Vars

    // init
    function init2() {
        elm_button2.live('click', function(e){
            $(this).children('a').toggleClass('is-open');
            $(this).children('.sub-menu').slideToggle();

            e.stopPropagation();
        });
    }

    init2();

    ///////////////////////////////////////////////////////////////


    //子をクリック時に親のイベント発生阻止=リンクを生かす
    // Elements
    // var elm_button3 = $('#main-nav .sub-menu li a');
    var elm_button3 = $('#main-nav ._subCategory li a');

    // // Vars

    // // init
    function init3() {

        elm_button3.live('click', function(e){
            // $check_anker_href2 = $(this).attr('href');
            // if($check_anker_href2 != '#') {
            //     e.stopPropagation();
            // }

            $check_anker_href2 = $(this).parent().hasClass('nolink');
            if(!$check_anker_href2) {
                e.stopPropagation();
            }
        });

    }

    init3();

    ///////////////////////////////////////////////////////////////
    
    //▼をクリック時にリンク先に飛ばさずに開閉させる
    // Elements
    var elm_button4 = $('#main-nav ._naviArrow');

    // // Vars

    // // init
    function init4() {

        elm_button4.live('click', function(e){

            $(this).parent('a').toggleClass('is-open');
            $(this).parent().parent().children('._subCategory').slideToggle();
            $(this).parent().parent().children('.sub-menu').slideToggle();

            e.stopPropagation();
            e.preventDefault();
        });

    }

    init4();

    ///////////////////////////////////////////////////////////////



});