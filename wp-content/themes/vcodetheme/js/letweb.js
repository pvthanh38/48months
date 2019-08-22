var Letweb = function () {
    this.el = {};
    this.state = {};
};

(function ($) {
    Letweb.prototype.selector = function () {
        this.el.body = $('body');
        this.el.tabTitles = this.el.body.find('.tabs-title a');
    };
    Letweb.prototype.tabs = function () {
        this.el.tabTitles.click(function (e) {
            e.preventDefault();
            e.stopPropagation();
            var _self = $(this);
            var href = _self.attr('href');
            var container = _self.closest('.tabs');
            container.find('.active').removeClass('active');
            _self.parent().addClass('active');
            $(href).addClass('active');
        });
    };
    Letweb.prototype.init = function () {
        this.selector();
        this.tabs();

        $('a[href^="#"]').click(function (e) {
            e.preventDefault();
            var _self = $(this);

            if (_self.data('vc-container')) return;

            if (!_self.hasClass('on-my-own')){
                var target = $($(this).attr('href'));
                var top = 0;
                if (target.length > 0) {
                    top = target.offset().top;
                }
                $('html, body').animate({scrollTop: top});
            }
        });
    };

    $(document).ready(function () {
        var lw = new Letweb();
        lw.init();
        window.Letweb = lw;
    });
})(jQuery);