(function($) {

    var rellaImporter = function(id, options) {
        var $this = this;

        // Id import
        $this.id = id;
        // list type import
        $this.options = options;


        this.init = function() {

            var self = this,
                message,
                start = $('.rella-popup-getting-started'),
                actions = this.options.slice();
            start.hide();
            $('#rela-import-complete').hide();
            $('#rella-import-emoj').hide();

            start.after(_.template($('#tmpl-demo-import-modules').html())({ modules: this.options }));
            //alert( $('.import-id').attr('data-import-id') );
            var data = new FormData();

            data.append('action', 'ocdi_import_demo_data');
            //data.append( 'security', boo.ajax_nonce );
            data.append('selected', 2);
            data.append('selections', options);
            runImport($this.options, $this.id);

            //ajaxCall( data );

        };

        this.init();

    };

    $.fn.getParameterByName = function(name, url) {
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    }

    function runImport(options, id) {
        var count = 0;
        ajaxRun('rella_' + options[options.length - options.length], options, id, function() {
            count++;
            ajaxRun('rella_' + options[count], options, id, function() {
                count++;
                ajaxRun('rella_' + options[count], options, id, function() {
                    count++;
                    ajaxRun('rella_' + options[count], options, id, function() {
                        count++;
                        ajaxRun('rella_' + options[count], options, id, function() {
                            count++;
                            ajaxRun('rella_' + options[count], options, id);
                        });
                    });
                });
            })
        });
    }

    function getImportedPosts() {
        var total, imported, precent, newtotal, newtotal1;
        $.ajax({
            url: ajaxurl,
            type: 'GET',
            data: {
                action: 'rella_total_imported',
            },
        }).done(function(resp) {
            total = parseInt(resp.match(/(?:(?!\|).)*/i)[0]);
            if (resp.indexOf('|')) {
                newtotal = resp.substring(0, resp.indexOf('|'));
                newtotal1 = resp.replace(newtotal, '');
            } else {
                newtotal1 = total;
            }
            if (newtotal1.match(/(\d+)(?!.*\d)/i)) {
                imported = parseInt(newtotal1.match(/(\d+)(?!.*\d)/i)[0]);
                precent = parseInt((imported * 100) / total);
            } else {
                precent = parseInt('100');
            }

            $('#rella-loader').find('div').css('width', precent - 1 + '%');
            $('#rella-loader').find('.text').text(precent - 1 + '%');
            return false;
        });
        return false;

    }

    function getProgress() {
        $.ajax({
            url: ajaxurl,
            type: 'GET',
            data: {
                action: 'rella_progress_imported',
            },
        }).done(function(resp) {
            $('#rella-progress').text(resp);
            return false;
        });
        return false;
    }

    function ajaxRun(action, options, demo, callback) {
        var ajaxupdater, ajaxprogress;
        ajaxupdater = setInterval(getImportedPosts, 5000);

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: action,
                demo: demo,
                content: ($('#rella-import-content').is(':checked') ? 1 : 0),
                media: ($('#rella-import-media').is(':checked') ? 1 : 0)
            },
            beforeSend: function(jq) {
                $('#rela-import-loader').show();
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'rella_reset_logs',
                    },
                })
                ajaxprogress = setInterval(getProgress, 5000);
            },
            success: function(d) {

            },
            complete: function() {

                if (typeof callback === 'function' && !action.match('undefined')) {
                    callback();
                }
                clearInterval(ajaxupdater);
                clearInterval(ajaxprogress);
            },
        }).done(function(r) {
            console.log(r)
            if ('rella_' + options[options.length - 1] === action) {
                clearInterval(ajaxupdater);
                clearInterval(ajaxprogress);
                var popup = $('#rella-popup');
                $('#rella-loader').find('div').css('min-width', '100%');
                $('#rella-loader').find('.text').text("100%");
                setTimeout(function() {
                    $('#rella-import-emoj').show();
                    $('#rela-import-complete').show();
                    $('#rella-loader').hide();
                    $('#rella-progress').hide();
                    setTimeout(function() { popup.remove(); }, 10000);

                }, 800)
                return false;
            }
        });
    }

    function ajaxCall(data) {


        $.ajax({
                method: 'POST',
                url: ajaxurl,
                data: data,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#rela-import-loader').show();
                }
            })
            .done(function(response) {
                if ('undefined' !== typeof response.status && 'newAJAX' === response.status) {
                    ajaxCall(data);

                } else if ('undefined' !== typeof response.status && 'customizerAJAX' === response.status) {
                    // Fix for data.set and data.delete, which they are not supported in some browsers.
                    var newData = new FormData();
                    newData.append('action', 'ocdi_import_customizer_data');
                    //newData.append( 'security', ocdi.ajax_nonce );

                    // Set the wp_customize=on only if the plugin filter is set to true.
                    //if ( true === ocdi.wp_customize_on ) {
                    newData.append('wp_customize', 'on');

                    //}

                    ajaxCall(newData);
                } else if ('undefined' !== typeof response.status && 'afterAllImportAJAX' === response.status) {
                    // Fix for data.set and data.delete, which they are not supported in some browsers.
                    var newData = new FormData();
                    newData.append('action', 'ocdi_after_import_data');

                    //newData.append( 'security', ocdi.ajax_nonce );
                    ajaxCall(newData);

                } else if ('undefined' !== typeof response.message) {
                    //$( '#rela-import-complete' ).append( '<p>' + response.message + '</p>' );

                    $('#rela-import-complete').show();
                    $('#rela-import-loader').hide();
                    var popup = $('#rella-popup');

                    setTimeout(function() { popup.remove(); }, 10000);
                } else {
                    //$( '#rela-import-complete' ).append( '<div class="notice  notice-error  is-dismissible"><p>' + response + '</p></div>' );

                    $('#rela-import-complete').show();
                    $('#rela-import-loader').hide();
                    var popup = $('#rella-popup');

                    setTimeout(function() { popup.remove(); }, 10000);

                }
            })
            .fail(function(error) {

                $('#rela-import-complete').append('<div class="notice  notice-error  is-dismissible"><p>Error: ' + error.statusText + ' (' + error.status + ')' + '</p></div>');
                $('#rela-import-loader').hide();
                var popup = $('#rella-popup');

                setTimeout(function() { popup.remove(); }, 10000);

            });
    }

    function reset_wp(callback) {
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'rella_reset_wp'
            },
            beforeSend: function() {
                $('#rella-smily-loader').addClass('is-active');

            }
        }).done(function(re) {
            console.log(re);
            $('#rella-smily-loader').removeClass('is-active');
            if (typeof callback === 'function') {
                callback();
            }
        });
    }

    function reset_confirm_message(callback) {
	    if (typeof callback === 'function') {
	        callback();
	    }
/*
        $.confirm({
            title: rella_admin_messages.reset_title,
            content: rella_admin_messages.reset_message,
            buttons: {
                confirm: {
                    text: rella_admin_messages.reset_confirm,
                    btnClass: 'btn-green rella-reset-button',
                    keys: ['enter', 'shift'],
                    action: function() {
                        $.confirm({
                            title: rella_admin_messages.reset_final_title,
                            content: rella_admin_messages.reset_final_message,
                            buttons: {
                                confirm: {
                                    text: rella_admin_messages.reset_final_confirm,
                                    btnClass: 'btn-green rella-reset-button',
                                    keys: ['enter', 'shift'],
                                    action: function() {
                                        reset_wp(function() {
                                            if (typeof callback === 'function') {
                                                callback();
                                            }
                                        })
                                    }
                                },
                                cancel: function() {

                                }
                            },
                        })
                    }
                },
                continue: {
                    text: rella_admin_messages.reset_continue,
                    btnClass: 'btn-blue',
                    action: function() {
                        if (typeof callback === 'function') {
                            callback();
                        }
                    }
                },
                cancel: function() {

                }
            },
        });
*/
    }

    function initPopUp(demo) {

        // Popup
        var popup = $('#rella-popup');

        // CLose
        popup.on('click', '.rella-popup-close', function() {
            popup.remove();
        });

        popup.on('click', '.agree', function() {

            var $this = $(this),
                parent = $this.parent();

            $this.is(':checked') ? parent.addClass('checked') : parent.removeClass('checked')

        })

        // Import Now
        popup.on('click', '.rella-import-demo', function() {
            var btn = $(this);

            if (!btn.prev().children('input').is(':checked')) {
                var agreeBox = btn.prev();

                agreeBox.removeClass('rella-shake error');

                setTimeout(function() {
                    agreeBox.addClass('rella-shake error');
                }, 50)

                return;
            }

            var options = [];

            btn.parent().find('.import-option-group .import-option :checked').each(function() {
                options.push($(this).val());
            });

            var importer = new rellaImporter(demo, options, demo);
        });
    }
    var rellaAdmin = {

        init: function() {
            this.initTabs();
            this.initDemo();
            this.rippleLink();
            this.initIconpicker();
        },

        initDemo: function() {

            $('.rella-card-demo').on('click', '.rella-import-popup', function() {
                //exit if we found any error message
                if ($('.rella-error-message').length) {
                    return;
                }
                var id = $(this).data('demo-id'),

                    demo = rella_demos[id];
                var demo_id = $(this).attr('data-import-id');

                $.ajax({
                    url: ajaxurl,
                    type: 'GET',
                    data: {
                        action: 'rella_prepare_demo_package',
                        demo: id
                    },
                    beforeSend: function() {
                        $('#rella-smily-loader').addClass('is-active');

                    }
                }).done(function(resp) {
                    var jsonresp = JSON.parse(resp);
                    console.log(jsonresp);
                    if (jsonresp.stat === 1) {
                        $.ajax({
                            url: ajaxurl,
                            type: 'POST',
                            data: {
                                action: 'rella_require_plugins',
                                demo: id
                            },
                        }).done(function(re) {
                            $('#rella-smily-loader').removeClass('is-active');
                            ret = JSON.parse(re);
                            if (ret.stat == 1) {
                                $(document.body).append(_.template($('#tmpl-demo-popup').html())(demo));
                                initPopUp(id);

                            } else if (ret.stat == 0) {
                                message = message = "The selected demo requires the following plugins <strong>" + ret.plugins.toString() + "</strong> without them, some of the content may not get imported.";
                                reset_confirm_message(function() {
                                    $.confirm({
                                        title: 'Missing Requires',
                                        content: message,
                                        buttons: {
                                            confirm: {
                                                text: 'I understand keep importing',
                                                btnClass: 'btn-blue',
                                                keys: ['enter', 'shift'],
                                                action: function() {
                                                    $(document.body).append(_.template($('#tmpl-demo-popup').html())(demo));
                                                    initPopUp(id);
                                                }
                                            },
                                            cancel: function() {

                                            }
                                        }
                                    });
                                })

                            }
                        })
                    } else {
                        $('#rella-smily-loader').removeClass('is-active');
                        var $title = 'Error:';
                        var $content = 'Please, Activate Infinite Addons plugin';
                        if( ( jsonresp.stat === 0 ) && ( jsonresp.message != null ) ) {
                          $title = 'Error';
                          $content = jsonresp.message;
                        }
                        $.confirm({
                            title: $title,
                            content: $content,
                            buttons: {
                                confirm: {
                                    text: 'Ok',
                                    btnClass: 'btn-blue',
                                    keys: ['enter', 'shift'],
                                    action: function() {}
                                },
                                cancel: function() {

                                }
                            }
                        });
                    }
                });




                return false;
            });
        },

        initIconpicker: function() {
	        
	        if ( $.isFunction($.fn.fontIconPicker) ) {
    			var iconInput = $('.rella-icon-picker');
				iconInput.fontIconPicker();
			}

        },

        initTabs: function() {

            var activeTab = $().getParameterByName('page');

            $('.rella-nav-tabs').find('li').each(function() {
                var that = $(this);
                if (that.data('tab') == activeTab) {
                    that.addClass('is-active');
                }
            })

        },

        // https://codepen.io/440design/pen/iEztk
        rippleLink: function() {

            $('body').on('click', '.vc_ui-tabs-line-trigger', function(e) {

                var ink,
                    d,
                    x,
                    y;

                if ($(this).find(".ink").length === 0) {
                    $(this).prepend("<span class='ink'></span>");
                }

                ink = $(this).find(".ink");
                ink.removeClass("animate");

                if (!ink.height() && !ink.width()) {
                    d = Math.max($(this).outerWidth(), $(this).outerHeight());
                    ink.css({ height: d, width: d });
                }

                x = e.pageX - $(this).offset().left - ink.width() / 2;
                y = e.pageY - $(this).offset().top - ink.height() / 2;

                ink.css({ top: y + 'px', left: x + 'px' }).addClass("animate");
            });

        }
    };

    jQuery(document).ready(function() {
        rellaAdmin.init();
    });

})(jQuery);
