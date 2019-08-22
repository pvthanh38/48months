(function ($) {
    $(document).ready(function () {
        $('a[href^="#"]').click(function (e) {
            var target = $($(this).attr('href'));
            e.preventDefault();
            var top = 0;
            if (target.length > 0){
                top = target.offset().top;
            }
            $('html, body').animate({scrollTop: top});
        });
    });
})(jQuery);