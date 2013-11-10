(function($) {

	$(".scroll-triggered-box > *").last().css({
		'margin-bottom': 0,
		'padding-bottom': 0
	});

	// loop through boxes
	$(".scroll-triggered-box").each(function() {

		var $box = $(this);
		var triggerPoint = $box.data('trigger');
		var triggerPercentage = (triggerPoint == 'percentage') ? ($box.data('trigger-percentage') / 100) : 0.8;
		var triggerElement = $box.data('trigger-element');

		var checkBoxCriteria = function() {
			console.log("Checking..");

			var scrollY = $(window).scrollTop();
			var $triggerElement;
			var triggered = false;

			if(triggerPoint == 'element' && ($triggerElement = $(triggerElement)) && $triggerElement.length > 0) {
				triggered = ((scrollY + $(window).height()) >= triggerElement.offset().top);
			} else {
				triggered = ((scrollY + $(window).height()) >= (triggerPercentage * $("body").height()));
			}

			// show box when criteria for this box is matched
			if(triggered) {
				// remove listen event
				$(window).unbind('scroll', onScrollEvent);

				// show box
				$box.fadeIn();
			}

		}

		var onScrollEvent = function() {
			poll(checkBoxCriteria, 100);
		}

		// listen to scroll event
		$(window).bind('scroll', onScrollEvent);

		// listen to close button
		$box.find(".stb-close").click(function() {
			// hide box
			$box.fadeOut();

			// set cookie
			if($box.data('cookie') > 0) {
				var cookie_expiration_time = parseInt($box.data('cookie'));
				$.cookie('stb_box_' + $box.data('box-id'), true, { expires: cookie_expiration_time, path: '/' });
			}
			
		});

	});

	var poll = (function(){
	    var timer = 0;
	    return function(callback, ms){
	        clearTimeout(timer);
	        timer = setTimeout(callback, ms);
	    };
	})();

})(jQuery);

// jQuery Cookie Plugin
(function(e){if(typeof define==="function"&&define.amd){define(["jquery"],e)}else{e(jQuery)}})(function(e){function n(e){return u.raw?e:encodeURIComponent(e)}function r(e){return u.raw?e:decodeURIComponent(e)}function i(e){return n(u.json?JSON.stringify(e):String(e))}function s(e){if(e.indexOf('"')===0){e=e.slice(1,-1).replace(/\\"/g,'"').replace(/\\\\/g,"\\")}try{e=decodeURIComponent(e.replace(t," "));return u.json?JSON.parse(e):e}catch(n){}}function o(t,n){var r=u.raw?t:s(t);return e.isFunction(n)?n(r):r}var t=/\+/g;var u=e.cookie=function(t,s,a){if(s!==undefined&&!e.isFunction(s)){a=e.extend({},u.defaults,a);if(typeof a.expires==="number"){var f=a.expires,l=a.expires=new Date;l.setDate(l.getDate()+f)}return document.cookie=[n(t),"=",i(s),a.expires?"; expires="+a.expires.toUTCString():"",a.path?"; path="+a.path:"",a.domain?"; domain="+a.domain:"",a.secure?"; secure":""].join("")}var c=t?undefined:{};var h=document.cookie?document.cookie.split("; "):[];for(var p=0,d=h.length;p<d;p++){var v=h[p].split("=");var m=r(v.shift());var g=v.join("=");if(t&&t===m){c=o(g,s);break}if(!t&&(g=o(g))!==undefined){c[m]=g}}return c};u.defaults={};e.removeCookie=function(t,n){if(e.cookie(t)===undefined){return false}e.cookie(t,"",e.extend({},n,{expires:-1}));return!e.cookie(t)}})