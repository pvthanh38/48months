if (function($) {
	var rellaResponsiveAlignmentParam = Backbone.View.extend({
		events: {},
		$lg_offset_placeholder_value: !1,
		$lg_size_placeholder_value: !1,
		initialize: function() {
			_.bindAll(this, "setLgPlaceholders")
		},
		render: function() {
			return this
		},
		save: function() {
			var data = [];
			return this.$el.find(".vc_column_offset_field").each(function() {
				var $field = $(this);
				$field.is(":checkbox:checked") ? data.push($field.attr("name")) : $field.is("select") && "" !== $field.val() && data.push($field.val())
			}), data
		},
		setLgPlaceholders: function() {
			var offset = this.$lg_offset_placeholder_value.val().replace(/[^\d]/g, "");
			this.$lg_size.find("option:first").text(VcI8nColumnOffsetParam.inherit_default), this.$lg_offset.find("option:first").text(offset ? VcI8nColumnOffsetParam.inherit + offset : "")
		}
	});
	// VC Rella responsive aligment js
	vc.atts.responsive_alignment = {
		parse: function(param) {
			return this.content().find("input.wpb_vc_param_value." + param.param_name).data("rellaResponsiveAlignment").save().join(" ")
		},
		init: function(param, $field) {
			$('[data-column-offset="true"]', $field).each(function() {
				var $this = $(this);
				$this.find(".wpb_vc_param_value").data("rellaResponsiveAlignment", new rellaResponsiveAlignmentParam({
					el: $this
				}).render())
			})
		}
	}

}(window.jQuery), _.isUndefined(window.vc)) var vc = {
	atts: {}
};
(function($) {

	// OVerride VcBackendTtaViewInterface
	if ('VcBackendTtaViewInterface' in window) {

		window.VcBackendTtaViewInterface.prototype.clickAppendSection = function(e) {
			e.preventDefault();
			this.addSection(undefined, $(e.currentTarget).data('element_type'));
		};

		window.VcBackendTtaViewInterface.prototype.clickPrependSection = function(e) {
			e.preventDefault();
			this.addSection(!0, $(e.currentTarget).data('element_type'));
		};

		window.VcBackendTtaViewInterface.prototype.addSection = function(prepend, element) {
			var newTabTitle, params, shortcode;

			element = element || "vc_tta_section";

			return newTabTitle = this.defaultSectionTitle,
			params = {
				shortcode: element,
				params: {
					title: newTabTitle
				},
				parent_id: this.model.get("id"),
				order: _.isBoolean(prepend) && prepend ? vc.add_element_block_view.getFirstPositionIndex() : vc.shortcodes.getNextOrder(),
				prepend: prepend
			},
			shortcode = vc.shortcodes.create(params);
		};
	}

	// VC Gradient Parameter js
	vc.atts.gradient = {

		init: function(param, $field) {

			var value = this.model.get('params')[param.param_name],
			startingGradient = '',
			startingBgProps = '',
			bg = '';

			if ('undefined' !== typeof value) {
				value = this.model.get('params')[param.param_name].split('|');
				bg = '{"' + value[1].replace(/: /g, '":"').replace(/;/g, '","').slice(0, -2) + '}';

				startingGradient = value[0];
				startingBgProps = JSON.parse(bg);
			}

			var defaults = {
				interface: ['gradient', 'background'],
				targetInputElement: $field.find('.rella-gradient-css'),
				targetBgInputElement: $field.find('.rella-gradient-bg'),
				startingGradient: startingGradient,
				startingBgProps: startingBgProps
			};

			var options = $.extend(true, {}, defaults, param.settings);
			$field.find('.rella-gradient').icsge(options);
		},

		parse: function(param) {
			var $field = this.content().find(".wpb_vc_param_value[name=" + param.param_name + "]"),
			$css = $field.next('.rella-gradient-css'),
			$bg = $css.next('.rella-gradient-bg');

			return $css.val() + '|' + $bg.val();
		}
	};

	// VC rella slider parameter js
	vc.atts.rella_slider = {

		render: function(param, value) {
			return value;
		},

		init: function( param, $field ) {

			var $init_val = $field.find('input.wpb_vc_param_value').val();
			var $handle = $field.find('.rella-handle');

			$field.find('.rella-slider').slider({
				min: param.min,
				max: param.max,
				step: param.step,
				value: $init_val,

				create: function() {
					$handle.text( $( this ).slider( "value" ) );
				},
				slide: function( event, ui ) {
					$handle.text( ui.value );
				},
				change: function( event, ui ) {
					$field.find('input.wpb_vc_param_value').val( ui.value );
				}
			});

		}

	}

	// VC Select Preview parameter js
	vc.atts.select_preview = {

		render: function(param, value) {
			return value;
		},

		init: function(param, $field) {
			$field.find('.wpb_vc_param_value').imagepicker({
				show_label: true
			});
		},
		defaults: function(param) {
			var values;
			return _.isArray(param.value) || _.isString(param.value) ? _.isArray(param.value) ? (values = param.value[0]['value'], _.isArray(values) && values.length ? values[0]['value'] : values) : "" : (values = _.values(param.value)[0]['value'], values.label ? values.value : values);
		}

	};

	// VC Responsive CSS parameter js
	vc.atts.responsive_css_editor = {
		parse: function(param) {
			var $field = this.content().find('.wpb_vc_param_value[name=' + param.param_name + ']');
			var $block = $field.parent();
			var options = {},
			string_pieces,
			string;
			positions: ['top', 'right', 'bottom', 'left'],
			resolutions = ['desktop', 'tablet', 'mobile'],
			props = ['margin', 'padding', 'border'];

			// Desktop values
			options.margin_top_desktop = $block.find('[data-name="margin-top-desktop"]').val();
			options.margin_right_desktop = $block.find('[data-name="margin-right-desktop"]').val();
			options.margin_bottom_desktop = $block.find('[data-name="margin-bottom-desktop"]').val();
			options.margin_left_desktop = $block.find('[data-name="margin-left-desktop"]').val();

			options.border_top_desktop = $block.find('[data-name="border-top-desktop"]').val();
			options.border_right_desktop = $block.find('[data-name="border-right-desktop"]').val();
			options.border_bottom_desktop = $block.find('[data-name="border-bottom-desktop"]').val();
			options.border_left_desktop = $block.find('[data-name="border-left-desktop"]').val();

			options.padding_top_desktop = $block.find('[data-name="padding-top-desktop"]').val();
			options.padding_right_desktop = $block.find('[data-name="padding-right-desktop"]').val();
			options.padding_bottom_desktop = $block.find('[data-name="padding-bottom-desktop"]').val();
			options.padding_left_desktop = $block.find('[data-name="padding-left-desktop"]').val();

			// Tablet values
			options.margin_top_tablet = $block.find('[data-name="margin-top-tablet"]').val();
			options.margin_right_tablet = $block.find('[data-name="margin-right-tablet"]').val();
			options.margin_bottom_tablet = $block.find('[data-name="margin-bottom-tablet"]').val();
			options.margin_left_tablet = $block.find('[data-name="margin-left-tablet"]').val();

			options.border_top_tablet = $block.find('[data-name="border-top-tablet"]').val();
			options.border_right_tablet = $block.find('[data-name="border-right-tablet"]').val();
			options.border_bottom_tablet = $block.find('[data-name="border-bottom-tablet"]').val();
			options.border_left_tablet = $block.find('[data-name="border-left-tablet"]').val();

			options.padding_top_tablet = $block.find('[data-name="padding-top-tablet"]').val();
			options.padding_right_tablet = $block.find('[data-name="padding-right-tablet"]').val();
			options.padding_bottom_tablet = $block.find('[data-name="padding-bottom-tablet"]').val();
			options.padding_left_tablet = $block.find('[data-name="padding-left-tablet"]').val();

			// Mobile values
			options.margin_top_mobile = $block.find('[data-name="margin-top-mobile"]').val();
			options.margin_right_mobile = $block.find('[data-name="margin-right-mobile"]').val();
			options.margin_bottom_mobile = $block.find('[data-name="margin-bottom-mobile"]').val();
			options.margin_left_mobile = $block.find('[data-name="margin-left-mobile"]').val();

			options.border_top_mobile = $block.find('[data-name="border-top-mobile"]').val();
			options.border_right_mobile = $block.find('[data-name="border-right-mobile"]').val();
			options.border_bottom_mobile = $block.find('[data-name="border-bottom-mobile"]').val();
			options.border_left_mobile = $block.find('[data-name="border-left-mobile"]').val();

			options.padding_top_mobile = $block.find('[data-name="padding-top-mobile"]').val();
			options.padding_right_mobile = $block.find('[data-name="padding-right-mobile"]').val();
			options.padding_bottom_mobile = $block.find('[data-name="padding-bottom-mobile"]').val();
			options.padding_left_mobile = $block.find('[data-name="padding-left-mobile"]').val();

			string_pieces = _.map(options, function(value, key) {
				if (_.isString(value) && 0 < value.length) {
					return key + ':' + encodeURIComponent(value);
				}
			});
			string = $.grep(string_pieces, function(value) {
				return _.isString(value) && 0 < value.length;
			}).join('|');
			return string;
		},
		init: function(param, $field) {
			$('h3.rella-responsive-css-heading', $field).click(function(e) {
				$(this).parent('.rella-main-responsive-wrapper').addClass('active').siblings().removeClass('active');
			});
		},

	};

	// VC Advanced Rella Checkbox js
	vc.atts.rella_advanced_checkbox = {

		parse: function(param) {

			if ($(".rella-advanced-checkbox input").is(':checked')) {
				var $checked = 'checked="checked"';
			} else {
				var $checked = '';
			}

			var $rella_panel = $('#vc_ui-panel-edit-element'),
			$easy_mode_checkbox = $('<label class="rella-easy-mode-switch"><input type="checkbox" class="rella-easy-mode-checkbox" ' + $checked + ' value="true">Easy Mode<span><i class="fa fa-power-off"> </i></span></label>'),
			$easy_mode;

			$rella_panel.find('.rella-easy-mode-switch').remove();
			$rella_panel.find('.vc_ui-panel-header-controls').prepend($easy_mode_checkbox);
			$easy_mode = $easy_mode_checkbox.find('.rella-easy-mode-checkbox');

			var $advanced_checkbox = $(".rella-advanced-checkbox input");

			$easy_mode.on('change', function() {
				$advanced_checkbox.trigger('click');
			});

			var arr, newValue;
			return arr = [],
			newValue = "",
			$("input[name=" + param.param_name + "]", this.content()).each(function() {
				var self;
				self = $(this),
				this.checked && arr.push(self.attr("value"))

			}),
			0 < arr.length && (newValue = arr.join(",")),
			newValue
		},
		defaults: function(param) {
			return ""
		},
	};

	// VC Rella Checkbox js
	vc.atts.rella_checkbox = {

		parse: function(param) {
			var arr, newValue;
			return arr = [],
			newValue = "",
			$("input[name=" + param.param_name + "]", this.content()).each(function() {
				var self;
				self = $(this),
				this.checked && arr.push(self.attr("value"))
			}),
			0 < arr.length && (newValue = arr.join(",")),
			newValue
		},
		defaults: function(param) {
			return ""
		}
	};

	// VC Checkbox additional html markup js
	vc.atts.checkbox = {

		parse: function(param) {
			var arr, newValue;
			return arr = [],
			newValue = "",
			$("input[name=" + param.param_name + "]", this.content()).each(function() {
				var self;
				var span = $('<span class="checkbox"></span>');
				self = $(this),
				this.checked && arr.push(self.attr("value"))
				if (!self.siblings('span.checkbox').length) {
					span.insertAfter(self);
				}
			}),
			0 < arr.length && (newValue = arr.join(",")),
			newValue
		},
		defaults: function(param) {
			return ""
		}
	};

	$('body').on('click', '.vc_ui-tabs-line-trigger', function() {

		var $this = $(this),
		target = $this.attr('data-vc-ui-element-target');

		$(target).find('input[type=checkbox]').each(function() {

			var span = $('<span class="checkbox"></span>');

			if (!$(this).siblings('span.checkbox').length) {
				span.insertAfter($(this));
			}

		})

	});

	// run Rella functions after shortcode options changed in frontend editor

	// vc.events.on("shortcodes:ra_carousel", function(model, ev) {

		// $('#vc_inline-frame').contents().find('.carousel-items').children('.vc_element').wrap('<div class="carousel-item"></div>').end().find('.carousel-item').wrapAll('<div class="items-container"></div>');
		//
		// $('#vc_inline-frame').contents().find('.carousel-items').flickity({
		// 	cellSelector: '.vc_element'
		// });
		//
		// var item = $('<div class="carousel-cell"></div>')
		//
		// setTimeout(function() {
		//
		// 	$('#vc_inline-frame').contents().find('.carousel-items .items-container').flickity('append', item)
		//
		// }, 5000)


	// });
	// vc.events.on("shortcodes:ra_google_map", function(model, ev) {
	//
	// 	if ( ev === "update" && typeof rellaMaps !== typeof undefined ) {
	// 		$('[data-plugin-map]').rellaMaps();
	// 	};
	//
	// });
	// vc.events.on("shortcodes:vc_column",  function(model, ev) {
	// 	// console.log(model, ev)
	//
	// 	vc.events.trigger("app.render")
	// 	if ( ev === "update" && typeof RellaParallax !== typeof undefined ) {
	// 		$('[data-parallax]').RellaParallax();
	// 	};
	//
	// });

	// VC Boo Universe - theme predefined vc templates js
	vc.TemplateWindowUIPanelBackendEditor = vc.TemplatesPanelViewBackend.vcExtendUI(vc.HelperPanelViewHeaderFooter).vcExtendUI(vc.HelperTemplatesPanelViewSearch).extend({
		panelName: "template_window",
		showMessageDisabled: !1,
		initialize: function() {
			vc.TemplateWindowUIPanelBackendEditor.__super__.initialize.call(this), this.trigger("show", this.initTemplatesTabs, this)
		},
		show: function() {

			this.clearSearch(), vc.TemplateWindowUIPanelBackendEditor.__super__.show.call(this), $('.vc_edit-form-tab[data-tab="rella_templates"] .sortable_templates ul > li').each(function() {
				"all" == $(this).attr("data-sort") ? $(this).find(".count").html($('.vc_edit-form-tab[data-tab="rella_templates"] .vc_ui-template-list > .vc_ui-template').length) : $(this).find(".count").html($('.vc_edit-form-tab[data-tab="rella_templates"] .vc_ui-template-list > .vc_ui-template.' + $(this).attr("data-sort")).length)
			}), $('.vc_edit-form-tab[data-tab="rella_templates"] .sortable_templates li[data-sort="all"]').addClass("active").trigger("click"), $('.vc_edit-form-tab[data-tab="rella_templates"] .sortable_templates li').click(function() {
				$('.vc_edit-form-tab[data-tab="rella_templates"] .sortable_templates li').removeClass("active"), $(this).addClass("active");
				var t = $(this).attr("data-sort");
				$('.vc_edit-form-tab[data-tab="rella_templates"] .vc_ui-template-list > .vc_ui-template').removeClass("hidden"), "all" != t && $('.vc_edit-form-tab[data-tab="rella_templates"] .vc_ui-template-list > .vc_ui-template:not(.' + t + ")").addClass("hidden")
			}),
			$('.vc_ui-template', $(this.el) ).removeClass('is-loading').find('.vc-composer-icon').removeClass('vc-c-icon-sync').addClass('vc-c-icon-add');
			$('.vc_ui-control-button i', $(this.el) ).removeClass('rotating');
			$(this.el).on('click', '.vc_ui-template [data-template-handler]' ,function() {
				$(this).closest('.vc_ui-template').addClass('is-loading')
				if ( $(this).is('.vc_ui-control-button') ) {
					$(this).find('.vc-composer-icon').removeClass('vc-c-icon-add').addClass('vc-c-icon-sync rotating');
				} else {
					$(this).next('.vc_ui-list-bar-item-actions').find('.vc-composer-icon').removeClass('vc-c-icon-add').addClass('vc-c-icon-sync rotating');
				}

			})
		},
		initTemplatesTabs: function() {

			this.$el.find('[data-vc-ui-element="panel-tabs-controls"]').vcTabsLine("moveTabs")

		},
		showMessage: function(text, type) {

			var wrapperCssClasses;
			if (this.showMessageDisabled) return !1;
			wrapperCssClasses = "vc_col-xs-12 wpb_element_wrapper", this.message_box_timeout && this.$el.find("[data-vc-panel-message]").remove() && window.clearTimeout(this.message_box_timeout), this.message_box_timeout = !1;
			var $messageBox, messageBoxTemplate = vc.template('<div class="vc_message_box vc_message_box-standard vc_message_box-rounded vc_color-<%- color %>"><div class="vc_message_box-icon"><i class="fa fa fa-<%- icon %>"></i></div><p><%- text %></p></div>');
			switch (type) {
				case "error":
				$messageBox = $('<div class="' + wrapperCssClasses + '" data-vc-panel-message>').html(messageBoxTemplate({
					color: "danger",
					icon: "times",
					text: text
				}));
				break;
				case "warning":
				$messageBox = $('<div class="' + wrapperCssClasses + '" data-vc-panel-message>').html(messageBoxTemplate({
					color: "warning",
					icon: "exclamation-triangle",
					text: text
				}));
				break;
				case "success":
				$messageBox = $('<div class="' + wrapperCssClasses + '" data-vc-panel-message>').html(messageBoxTemplate({
					color: "success",
					icon: "check",
					text: text
				}))
			}
			$messageBox.prependTo(this.$el.find('[data-vc-ui-element="panel-edit-element-tab"].vc_row.vc_active')), $messageBox.fadeIn(), this.message_box_timeout = window.setTimeout(function() {
				$messageBox.remove()
			}, 6e3)
		},
		changeTab: function(e) {
			e.preventDefault(), e && !e.isClearSearch && this.clearSearch();
			var $tab = $(e.currentTarget);
			$tab.parent().hasClass("vc_active") || (this.$el.find('[data-vc-ui-element="panel-tabs-controls"] .vc_active:not([data-vc-ui-element="panel-tabs-line-dropdown"])').removeClass("vc_active"), $tab.parent().addClass("vc_active"), this.$el.find('[data-vc-ui-element="panel-edit-element-tab"].vc_active').removeClass("vc_active"), this.$el.find($tab.data("vcUiElementTarget")).addClass("vc_active"), this.$tabsMenu && this.$tabsMenu.vcTabsLine("checkDropdownContainerActive"))
		},
		setPreviewFrameHeight: function(templateID, height) {
			parseInt(height) < 100 && (height = 100), $('data-vc-template-preview-frame="' + templateID + '"').height(height)
		}
	}), vc.TemplateWindowUIPanelBackendEditor.prototype.events = $.extend(!0, vc.TemplateWindowUIPanelBackendEditor.prototype.events, {
		'click [data-vc-ui-element="button-save"]': "save",
		'click [data-vc-ui-element="button-close"]': "hide",
		'click [data-vc-ui-element="button-minimize"]': "toggleOpacity",
		"keyup [data-vc-templates-name-filter]": "searchTemplate",
		"search [data-vc-templates-name-filter]": "searchTemplate",
		"click .vc_template-save-btn": "saveTemplate",
		"click [data-template_id] [data-template-handler]": "loadTemplate",
		'click [data-vc-container=".vc_ui-list-bar"][data-vc-preview-handler]': "buildTemplatePreview",
		'click [data-vc-ui-delete="template-title"]': "removeTemplate",
		'click [data-vc-ui-element="panel-tab-control"]': "changeTab"
	}), vc.TemplateWindowUIPanelFrontendEditor = vc.TemplatesPanelViewFrontend.vcExtendUI(vc.HelperPanelViewHeaderFooter).vcExtendUI(vc.HelperTemplatesPanelViewSearch).extend({
		panelName: "template_window",
		showMessageDisabled: !1,
		show: function() {
			this.clearSearch(), vc.TemplateWindowUIPanelFrontendEditor.__super__.show.call(this), $('.vc_edit-form-tab[data-tab="rella_templates"] .sortable_templates ul > li').each(function() {
				"all" == $(this).attr("data-sort") ? $(this).find(".count").html($('.vc_edit-form-tab[data-tab="rella_templates"] .vc_ui-template-list > .vc_ui-template').length) : $(this).find(".count").html($('.vc_edit-form-tab[data-tab="rella_templates"] .vc_ui-template-list > .vc_ui-template.' + $(this).attr("data-sort")).length)
			}), $('.vc_edit-form-tab[data-tab="rella_templates"] .sortable_templates li[data-sort="all"]').addClass("active").trigger("click"), $('.vc_edit-form-tab[data-tab="rella_templates"] .sortable_templates li').click(function() {
				$('.vc_edit-form-tab[data-tab="rella_templates"] .sortable_templates li').removeClass("active"), $(this).addClass("active");
				var t = $(this).attr("data-sort");
				$('.vc_edit-form-tab[data-tab="rella_templates"] .vc_ui-template-list > .vc_ui-template').removeClass("hidden"), "all" != t && $('.vc_edit-form-tab[data-tab="rella_templates"] .vc_ui-template-list > .vc_ui-template:not(.' + t + ")").addClass("hidden")
			}),
			$('.vc_ui-template', $(this.el) ).removeClass('is-loading').find('.vc-composer-icon').removeClass('vc-c-icon-sync').addClass('vc-c-icon-add');
			$('.vc_ui-control-button i', $(this.el) ).removeClass('rotating');
			$(this.el).on('click', '.vc_ui-template [data-template-handler]' ,function() {
				$(this).closest('.vc_ui-template').addClass('is-loading')
				if ( $(this).is('.vc_ui-control-button') ) {
					$(this).find('.vc-composer-icon').removeClass('vc-c-icon-add').addClass('vc-c-icon-sync rotating');
				} else {
					$(this).next('.vc_ui-list-bar-item-actions').find('.vc-composer-icon').removeClass('vc-c-icon-add').addClass('vc-c-icon-sync rotating');
				}

			})
		},
		showMessage: function(text, type) {
			if (this.showMessageDisabled) return !1;
			this.message_box_timeout && this.$el.find("[data-vc-panel-message]").remove() && window.clearTimeout(this.message_box_timeout), this.message_box_timeout = !1;
			var $messageBox, wrapperCssClasses, messageBoxTemplate = vc.template('<div class="vc_message_box vc_message_box-standard vc_message_box-rounded vc_color-<%- color %>"><div class="vc_message_box-icon"><i class="fa fa fa-<%- icon %>"></i></div><p><%- text %></p></div>');
			switch (wrapperCssClasses = "vc_col-xs-12 wpb_element_wrapper", type) {
				case "error":
				$messageBox = $('<div class="' + wrapperCssClasses + '" data-vc-panel-message>').html(messageBoxTemplate({
					color: "danger",
					icon: "times",
					text: text
				}));
				break;
				case "warning":
				$messageBox = $('<div class="' + wrapperCssClasses + '" data-vc-panel-message>').html(messageBoxTemplate({
					color: "warning",
					icon: "exclamation-triangle",
					text: text
				}));
				break;
				case "success":
				$messageBox = $('<div class="' + wrapperCssClasses + '" data-vc-panel-message>').html(messageBoxTemplate({
					color: "success",
					icon: "check",
					text: text
				}))
			}
			$messageBox.prependTo(this.$el.find('[data-vc-ui-element="panel-edit-element-tab"].vc_row.vc_active')), $messageBox.fadeIn(), this.message_box_timeout = window.setTimeout(function() {
				$messageBox.remove()
			}, 6e3)
		},
		changeTab: function(e) {
			e.preventDefault(), e && !e.isClearSearch && this.clearSearch();
			var $tab = $(e.currentTarget);
			$tab.parent().hasClass("vc_active") || (this.$el.find('[data-vc-ui-element="panel-tabs-controls"] .vc_active:not([data-vc-ui-element="panel-tabs-line-dropdown"])').removeClass("vc_active"), $tab.parent().addClass("vc_active"), this.$el.find('[data-vc-ui-element="panel-edit-element-tab"].vc_active').removeClass("vc_active"), this.$el.find($tab.data("vcUiElementTarget")).addClass("vc_active"), this.$tabsMenu && this.$tabsMenu.vcTabsLine("checkDropdownContainerActive"))
		}
	}), $.fn.vcAccordion.Constructor.prototype.collapseTemplate = function(showCallback) {
		var $allTriggers, $activeTriggers, $this, $triggers;
		$this = this.$element;
		var i;
		if (i = 0, $allTriggers = this.getContainer().find("[data-vc-preview-handler]").each(function() {
			var accordion, $this;
			$this = $(this), accordion = $this.data("vc.accordion"), void 0 === accordion && ($this.vcAccordion(), accordion = $this.data("vc.accordion")), accordion && accordion.setIndex && accordion.setIndex(i++)
		}), $activeTriggers = $allTriggers.filter(function() {
			var $this, accordion;
			return $this = $(this), accordion = $this.data("vc.accordion"), accordion.getTarget().hasClass(accordion.activeClass)
		}), $triggers = $activeTriggers.filter(function() {
			return $this[0] !== this
		}), $triggers.length && $.fn.vcAccordion.call($triggers, "hide"), this.isActive()) $.fn.vcAccordion.call($this, "hide");
		else {
			$.fn.vcAccordion.call($this, "show");
			var $triggerPanel = $this.closest(".vc_ui-list-bar-item"),
			$wrapper = $this.closest("[data-template_id]"),
			$panel = $wrapper.closest("[data-vc-ui-element=panel-content]").parent();
			setTimeout(function() {
				if (Math.round($wrapper.offset().top - $panel.offset().top) < 0) {
					var posit = Math.round($wrapper.offset().top - $panel.offset().top + $panel.scrollTop() - $triggerPanel.height());
					$panel.animate({
						scrollTop: posit
					}, 400)
				}
				"function" == typeof showCallback && showCallback($wrapper, $panel)
			}, 400)
		}
	}

})(jQuery);
