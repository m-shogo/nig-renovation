$(function(){
    // Elements
    var elm_button = $('.NaviButton');

    // Vars

    // init
    function init() {
        elm_button.on('click', function(){
            $('body').toggleClass('is-naviOpened');
            $('#main-nav .sub-menu').hide();
            $('._subCategory a').removeClass('is-open');
        });
    }

    init();
});