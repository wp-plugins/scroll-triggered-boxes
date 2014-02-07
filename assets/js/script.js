jQuery(window).load(function() {

	/*!
	 * jQuery Cookie Plugin v1.4.0
	 * https://github.com/carhartl/jquery-cookie
	 *
	 * Copyright 2013 Klaus Hartl
	 * Released under the MIT license
	 */
	(function($) {

		if($.cookie) { return; }

		var pluses = /\+/g;

		function encode(s) {
			return config.raw ? s : encodeURIComponent(s);
		}

		function decode(s) {
			return config.raw ? s : decodeURIComponent(s);
		}

		function stringifyCookieValue(value) {
			return encode(config.json ? JSON.stringify(value) : String(value));
		}

		function parseCookieValue(s) {
			if (s.indexOf('"') === 0) {
				s = s.slice(1, -1).replace(/\\"/g, '"').replace(/\\\\/g, '\\');
			}

			try {
				s = decodeURIComponent(s.replace(pluses, ' '));
				return config.json ? JSON.parse(s) : s;
			} catch(e) {}
		}

		function read(s, converter) {
			var value = config.raw ? s : parseCookieValue(s);
			return $.isFunction(converter) ? converter(value) : value;
		}

		var config = $.cookie = function (key, value, options) {

			// Write
			if (value !== undefined && !$.isFunction(value)) {
				options = $.extend({}, config.defaults, options);

				if (typeof options.expires === 'number') {
					var days = options.expires, t = options.expires = new Date();
					t.setTime(+t + days * 864e+5);
				}

				return (document.cookie = [
					encode(key), '=', stringifyCookieValue(value),
					options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
					options.path    ? '; path=' + options.path : '',
					options.domain  ? '; domain=' + options.domain : '',
					options.secure  ? '; secure' : ''
				].join(''));
			}

			// Read
			var result = key ? undefined : {};
			var cookies = document.cookie ? document.cookie.split('; ') : [];

			for (var i = 0, l = cookies.length; i < l; i++) {
				var parts = cookies[i].split('=');
				var name = decode(parts.shift());
				var cookie = parts.join('=');

				if (key && key === name) {
					result = read(cookie, value);
					break;
				}

				if (!key && (cookie = read(cookie)) !== undefined) {
					result[name] = cookie;
				}
			}

			return result;
		};

		config.defaults = {};

		$.removeCookie = function (key, options) {
			if ($.cookie(key) === undefined) {
				return false;
			}

			$.cookie(key, '', $.extend({}, options, { expires: -1 }));
			return !$.cookie(key);
		};

	})(jQuery);

	window.STB = (function($) {

		var windowHeight = $(window).height();
		var isLoggedIn = $("body").hasClass('logged-in');

		$(".stb-content").children().first().css({
			"margin-top": 0,
			"padding-top": 0
		}).end().last().css({
			'margin-bottom': 0,
			'padding-bottom': 0
		});

		// loop through boxes
		$(".scroll-triggered-box").each(function() {

			// vars
			var $box = $(this);
			var triggerMethod = $box.data('trigger');
			var animation = $box.data('animation');
			var timer = 0;
			var testMode = (parseInt($box.data('test-mode')) === 1);
			var id = $box.data('box-id');
			var autoHide = (parseInt($box.data('auto-hide')) === 1);

			if(triggerMethod == 'element') {
				var selector = $box.data('trigger-element');
				var $triggerElement = $(selector);
			}

			// calculate trigger height
			if(triggerMethod == 'element' && $triggerElement.length > 0) {
				var triggerHeight = $triggerElement.offset().top;
			} else {
				var triggerPercentage = (triggerMethod == 'percentage') ? (parseInt($box.data('trigger-percentage')) / 100) : 0.8;
				var triggerHeight = (triggerPercentage * $(document).height());
			}

			// functions
			var checkBoxCriteria = function() 
			{
				if(timer) { 
					clearTimeout(timer); 
				}

				timer = window.setTimeout(function() { 
					var scrollY = $(window).scrollTop();
					var triggered = ((scrollY + windowHeight) >= triggerHeight);

					// show box when criteria for this box is matched
					if(triggered) {

						// remove listen event
						if(!autoHide) {
							$(window).unbind('scroll', checkBoxCriteria);
						}

						toggleBox(true);
					} else {
						toggleBox(false);
					}

				}, 100);
			}

			var toggleBox = function(show) 
			{	
				if(!$box.is(':animated') && ( ( show && $box.is(':hidden') ) || ( ! show && $box.is(':visible') ) ) ) {
					console.log("Actually doing anything");
					// show box
					if(animation == 'fade') {
						$box.fadeToggle('slow');
					} else {
						$box.slideToggle('slow');
					}
				} 
			}

			// events

			// show box if cookie not set or if in test mode
			var cookieValue = $.cookie('stb_box_' + id);
			if(cookieValue == undefined || (isLoggedIn && testMode)) {
				$(window).bind('scroll', checkBoxCriteria);

				// init, check box criteria once
				checkBoxCriteria();

				// shows the box when hash refers an element inside the box
				if(window.location.hash && ($box.attr('id') == window.location.hash.substring(1) || (($element = $box.find(window.location.hash)) && $element.length > 0))) {
					setTimeout(function() { toggleBox(true); }, 100);
				}
			}		

			$box.find(".stb-close").click(function() {

				// hide box
				toggleBox(false);

				// set cookie
				var boxCookieTime = parseInt($box.data('cookie'));
				if(boxCookieTime > 0) {
					$.cookie('stb_box_' + id, true, { expires: boxCookieTime, path: '/' });
				}
				
			});
			
			// add link listener for this box
			$('a[href="#' + $box.attr('id') +'"]').click(function() { toggleBox(true); return false; });

		});

	return {}

	})(window.jQuery);

});

