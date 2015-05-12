module.exports = (function() {
	'use strict';

	var $ = window.jQuery,
		console  = window.console || { log: function() { }},
		isLoggedIn = $(document.body).hasClass('logged-in'),
		startTime = new Date().getTime();
	// Box Object
	var Box = function( config, events ) {
		this.id 		= config.id;
		this.title 		= config.title;
		this.element 	= config.element;
		this.$element 	= $(config.element);

		// store config values
		this.config = config;
		this.events = events;

		// store ref to overlay
		this.overlay = document.getElementById('stb-overlay');

		// state
		this.visible 	= false;
		this.closed 	= false;
		this.triggered 	= false;
		this.triggerHeight = 0;
		this.cookieSet = false;

		// if a trigger was given, calculate some values which might otherwise be expensive)
		if( this.config.autoShow && this.config.trigger !== '' ) {
			this.triggerHeight = this.calculateTriggerHeight( config.triggerPercentage, config.triggerElementSelector );
			this.cookieSet = this.isCookieSet();
		}

		// further initialise the box
		this.init();
	};

	// initialise the box
	Box.prototype.init = function() {
		var box = this;
		// attach event to "close" icon inside box
		this.$element.find('.stb-close').click(function() {
			box.dismiss();
		});

		// find all links & forms in this box
		this.$links = this.$element.find('a');
		this.$forms = this.$element.find('form');

		this.$links.click(function(e) {
			box.events.trigger('box.interactions.link', [ box, e.target ] );
		});

		this.$forms.submit(function(e) {
			box.events.trigger('box.interactions.form', [ box, e.target ]);
		});

		// attach event to all links referring #stb-{box_id}
		$('a[href="#' + this.$element.attr('id') +'"]').click(function() {
			box.toggle();
			return false;
		});

		// auto-show the box if box is referenced from URL
		if( this.locationHashRefersBox() ) {
			window.setTimeout(function() {
				box.show();
			}, 300);
		}
	};


	// set (calculate) custom box styling depending on box options
	Box.prototype.setCustomBoxStyling = function() {

		// reset element to its initial state
		this.element.style.overflowY = 'auto';
		this.element.style.maxHeight = 'none';

		// get new dimensions
		var windowHeight = window.innerHeight;
		var boxHeight = this.$element.outerHeight();

		// does box + margin fit on screen?
		if( ( boxHeight + 40 ) > windowHeight ) {

			// add scrollbar to box and limit height
			this.element.style.maxHeight = ( windowHeight - 40 ) + "px";
			this.element.style.overflowY = 'scroll';

		}

		// set new top margin for boxes which are centered
		if( this.config.position === 'center' ) {
			var newTopMargin = ( ( windowHeight - boxHeight ) / 2 );
			if( newTopMargin < 20 ) newTopMargin = 20;
			this.element.style.marginTop = newTopMargin + "px";
		}

	};

	// toggle visibility of the box
	Box.prototype.toggle = function(show) {

		// revert visibility if no explicit argument is given
		if( typeof( show ) === "undefined" ) {
			show = ! this.visible;
		}

		// do nothing if element is being animated
		if( this.$element.is(':animated') ) {
			return false;
		}

		// is box already at desired visibility?
		if( show === this.visible ) {
			return false;
		}

		// set new visibility status
		this.visible = show;

		// fadein / fadeout the overlay if position is "center"
		this.setCustomBoxStyling();

		if( this.config.position === 'center' ) {
			$(this.overlay).fadeToggle('slow');
		}

		// trigger event
		this.events.trigger('box.' + ( show ? 'show' : 'hide' ), [ this ] );

		// show or hide box using selected animation
		if( this.config.animation === 'fade' ) {
			this.$element.fadeToggle( 'slow' );
		} else {
			this.$element.slideToggle( 'slow' );
		}

		return true;
	};

	// show the box
	Box.prototype.show = function() {
		return this.toggle(true);
	};

	// hide the box
	Box.prototype.hide = function() {
		return this.toggle(false);
	};

	// calculate trigger height
	Box.prototype.calculateTriggerHeight = function( triggerPercentage, triggerElementSelector ) {

		if( this.config.trigger === 'element' ) {
			var $triggerElement = $(triggerElementSelector).first();
			if( $triggerElement.length > 0 ) {
				// return top offset of element
				return $triggerElement.offset().top;
			} else {
				// element was not found, disable box.
				return 0;
			}
		}

		// calcate % of page height
		return ( triggerPercentage / 100 * $(document).height() );
	};

	// set cookie that disables automatically showing the box
	Box.prototype.setCookie = function() {
		if(this.config.cookieTime > 0) {
			var expiryDate = new Date();
			expiryDate.setDate( expiryDate.getDate() + this.cookieTime );
			document.cookie = 'stb_box_'+ this.id + '=true; expires='+ expiryDate.toUTCString() +'; path=/';
		}
	};

	// checks whether window.location.hash equals the box element ID of that of any
	Box.prototype.locationHashRefersBox = function() {

		if( ! window.location.hash || 0 === window.location.hash.length ) {
			return false;
		}

		var elementId = window.location.hash.substring(1);
		if( elementId === this.element.id ) {
			return true;
		} else if( this.element.querySelector('#' + elementId) ) {
			return true;
		}

		return false;
	};

	// is this box enabled?
	Box.prototype.mayAutoShow = function() {

		// don't show if autoShow is disabled
		if( ! this.config.autoShow ) {
			return false;
		}

		// don't show if box was closed before
		if( this.closed ) {
			return false;
		}

		// don't show if triggerHeight is 0 (element not found or percentage set to 0)
		if( this.triggerHeight === 0 ) {
			return false;
		}

		// check if box fits on given minimum screen width
		if( this.config.minimumScreenWidth > 0 && window.innerWidth < this.config.minimumScreenWidth ) {
			return false;
		}

		// don't show if page just loaded ( 500ms)
		// todo: make an option out of this
		var currentTime = new Date().getTime();
		if( ( startTime + 500 ) > currentTime ) {
			return false;
		}

		// rely on cookie value (show if not set, don't show if set)
		return ! this.cookieSet;
	};

	Box.prototype.mayAutoHide = function() {

		// check if autoHide was allowed from config
		if( ! this.config.autoHide ) {
			return false;
		}

		// only allow autoHide when box has been autoshown (triggered)
		return this.triggered;
	};

	Box.prototype.isCookieSet = function() {
		// always show on test mode
		if( isLoggedIn && this.config.testMode ) {
			return false;
		}

		// check for cookie
		if( this.config.cookieTime === 0 ) {
			return false;
		}

		var cookieSet = document.cookie.replace(new RegExp("(?:(?:^|.*;)\\s*" + 'stb_box_' + this.id + "\\s*\\=\\s*([^;]*).*$)|^.*$"), "$1") === "true";
		return cookieSet;

	};

	// disable the box
	Box.prototype.dismiss = function() {
		this.hide();
		this.setCookie();
		this.closed = true;
		this.events.trigger('box.dismiss', [ this ]);
	};

	return Box;
})();