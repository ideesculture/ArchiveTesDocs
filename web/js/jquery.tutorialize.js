/*
 * Tutorialize - jQuery Plugin
 * Copyright (c) 2015 Gael Stucki (http://www.cvwanted.com/gael_stucki?rp-lg=en)
 * Documentation (http://gamaki-studio.com/envato/tutorialize)
 * Licensed under Regular license
 */

/**
 * Tutorialize is a jQuery plugin that provides a simple and nice
 * way to create tutorials that guide visitors around your site.
 *
 * Tutorialize has been tested in the following browsers:
 * - IE 9
 * - Firefox 2, 3
 * - Opera 9, 10
 * - Safari 3, 4
 * - Chrome 1, 2
 *
 * @name Tutorialize
 * @type jQuery
 * @requires jQuery v1.8.3
 * @cat Plugins/WebPage
 * @author Gael Stucki (http://www.cvwanted.com/gael_stucki)
 * @version 1.3.1
 */
;(function ($) {

	var _instance = [];

	/*
	 * PUBLIC function to init tutorialize plugin.
	 */
	$.tutorialize = function (options, instanceName) {

		if (!instanceName){
			$.tutorialize.obj.init(options);
		}else{
			$.tutorialize._instance(options, instanceName);
		}

	};

	/*
	 * PUBLIC function to clean remember storage
	 */
	$.tutorialize.cleanRemember = function (instanceName) {
		
		if (!instanceName){
			$.tutorialize.obj.cleanRemember();
		}else{
			if (!$.tutorialize.instanceExist) return;
			_instance[instanceName].cleanRemember();
		}

	};

	/*
	 * PUBLIC function to get current slide index
	 */
	$.tutorialize.getCurrentIndex = function (instanceName) {
		
		if (!instanceName){
			return $.tutorialize.obj.currentSlideIdx;
		}else{
			if (!$.tutorialize.instanceExist) return;
			return _instance[instanceName].currentSlideIdx;
		}

	};

	/*
	 * PUBLIC function to get total slides
	 */
	$.tutorialize.getTotal = function (instanceName) {
		
		if (!instanceName){
			return $.tutorialize.obj.options.slides.length;
		}else{
			if (!$.tutorialize.instanceExist) return;
			return _instance[instanceName].options.slides.length;
		}

	};

	/*
	 * PUBLIC function to check instance existing
	 */
	$.tutorialize.instanceExist = function (instanceName) {

		if (!_instance[instanceName]) return false;
		return true;

	};

	/*
	 * PUBLIC function to run the next slide
	 */
	$.tutorialize.next = function (instanceName) {
		
		if (!instanceName){
			$.tutorialize.obj.nextSlide();
		}else{
			if (!$.tutorialize.instanceExist) return;
			_instance[instanceName].nextSlide();
		}

	};

	/*
	 * PUBLIC function to run the previous slide
	 */
	$.tutorialize.prev = function (instanceName) {

		if (!instanceName){
			$.tutorialize.obj.prevSlide();
		}else{
			if (!$.tutorialize.instanceExist) return;
			_instance[instanceName].prevSlide();
		}

	};

	/*
	 * PUBLIC function to start the tutorialize
	 */
	$.tutorialize.start = function (instanceName) {

		if (!instanceName){
			$.tutorialize.obj.start();
		}else{
			if (!$.tutorialize.instanceExist) return;
			_instance[instanceName].start();
		}

	};

	/*
	 * PUBLIC function to start the tutorialize at a specific index
	 */
	$.tutorialize.startAt = function (slideIdx, instanceName) {
		
		if (!instanceName){
			$.tutorialize.obj.start(slideIdx);
		}else{
			if (!$.tutorialize.instanceExist) return;
			_instance[instanceName].start(slideIdx);
		}

	};

	/*
	 * PUBLIC function to stop the tutorialize
	 */
	$.tutorialize.stop = function (instanceName) {
		
		if (!instanceName){
			$.tutorialize.obj.stop();
		}else{
			if (!$.tutorialize.instanceExist) return;
			_instance[instanceName].stop();
		}

	};

	/*
	 * PRIVATE function to init multiple tutorialize plugin in parallel
	 */
	$.tutorialize._instance = function (options, instanceName) {

		var tutorialize = Object.create($.tutorialize.obj);
		tutorialize.init(options, instanceName);
		_instance[instanceName] = $.data(this, 'tutorialize', tutorialize);
    return _instance[instanceName];

	};

	/*
	 * tutorialize default options
	 */
	$.tutorialize.defaults = {
		slides: [],
		arrowOffset: 0,
		arrowPath: '../img/arrow-blue.png',
		arrowSize: 44,
		autoScroll: true,
		bgColor: '#11a7df',
		borderRadius: '5px',
		buttonBgColor: '#0d8fbf',
		buttonFontColor: '#fff',
		effectDelay: 500,
		fontColor: '#fff',
		fontSize: '13px',
		height: 'auto',
		keyboardNavigation: true,
		labelEnd: 'End',
		labelNext: 'Next',
		labelPrevious: 'Previous',
		labelStart: 'Start',
		minWidth: 80,
		minWidthCSS: 160,
		overlayColor: '#000',
		overlayMode: 'none',
		overlayOpacity: 0.5,
		overlayPadding: 0,
		padding: 20,
		remember: false,
		rememberKeyName: 'tutorialize-storage',
		rememberOnceOnly: false,
		runEnd: 'stop',
		runMode: 'manual',
		runDuration: 8000,
		showButtonClose: true,
		showButtonNext: true,
		showButtonPrevious: true,
		theme: '',
		width: 200,
		onStart: null,
		onStop: null
	};

	/*
	 * Main tutorialize object
	 */
	$.tutorialize.obj = {
		
		_scrollTopOffset: 100,

		currentSlide: null,
		currentSlideIdx: -1,
		initialized: false,
		instanceName: 'instance0',
		options: null,
		stSlidePlay: null,
		lastSlide: null,
		lastSlideScrollPosition: 0,
		tmpCurrentSlidePosition:'',
		tmpWinWidth:-1,
		$tutorializeElements: null,

		init: function (options, instanceName) {

			// Global
			this.options = $.extend({}, $.tutorialize.defaults, options);

			if (instanceName) this.instanceName = instanceName;
			this.options.rememberKeyName = this.options.rememberKeyName+'.'+this.instanceName;
			
			// Check
			this.check();

			// Dom
			this.initDom();

		},

		initDom: function (options) {
			
			var self = this,
					stResizeEnd = null,
					$win = $(window);
			
			if ($('#tutorialize-elements[data-instance="'+this.instanceName+'"]').length === 0) $('body').prepend('<div id="tutorialize-elements" data-instance="'+this.instanceName+'"></div>');
			this.$tutorializeElements = $('#tutorialize-elements[data-instance="'+this.instanceName+'"]');

			$win.resize(function() {

				self.moveSlideOnResize();
				if (self.currentSlide.overlayMode!='all') self.moveOverlayOnResize();

			});

			$win.scroll(function() {
  				if (self.currentSlide.overlayMode!='all') self.moveOverlayOnResize();
			});

			$(document).on("keyup", function (e) {

				if (!self.options.keyboardNavigation) return;

				$slideControl = self.$tutorializeElements.find('.tutorialize-slide .tutorialize-slide-control');
					
				// Esc Key
				if (e.keyCode == 27) self.stop();  

  			// Next Key
			  if (e.keyCode == 39) $slideControl.find('li.next span').trigger('click');

			  // Prev Key
			  if (e.keyCode == 37 && self.currentSlideIdx!==0) $slideControl.find('li.prev span').trigger('click');

			});

		},

		cleanRemember: function(){
			localStorage.removeItem(this.options.rememberKeyName);
		},

		calculateSlideOverlay: function($selector, move){

			if (!move && (this.currentSlide.overlayMode === 'all' || $selector.selector === 'html') ){

				this.$tutorializeElements.prepend('<div class="tutorialize-slide-overlay"></div>');
				var $slideOverlay = $('.tutorialize-slide-overlay');
				$slideOverlay.css({ top:0, left:0, right:0, bottom:0, opacity: this.currentSlide.overlayOpacity, 'background-color': this.currentSlide.overlayColor});

			}else{
				
				var domValues = this.getDOMValues(),
						selectorLeft = Math.round($selector.offset().left - this.currentSlide.overlayPadding),
						selectorTop = Math.round($selector.offset().top - this.currentSlide.overlayPadding - domValues.body.scrollTop),
						selectorWidth = Math.round($selector.outerWidth() + (this.currentSlide.overlayPadding*2) ),
						selectorHeight = Math.round($selector.outerHeight() + (this.currentSlide.overlayPadding*2) );

				if (!move) this.$tutorializeElements.prepend('<div class="tutorialize-slide-overlay top"></div>' +'<div class="tutorialize-slide-overlay left"></div>' +'<div class="tutorialize-slide-overlay right"></div>' +'<div class="tutorialize-slide-overlay bottom"></div>');

				var $slideOverlayTop = $('.tutorialize-slide-overlay.top'),
						$slideOverlayLeft = $('.tutorialize-slide-overlay.left'),
						$slideOverlayRight = $('.tutorialize-slide-overlay.right'),
						$slideOverlayBottom = $('.tutorialize-slide-overlay.bottom'),
						bottomTop = selectorTop+selectorHeight;

				$slideOverlayTop.css({ top:0, left:0, right:0, height:selectorTop, opacity: this.currentSlide.overlayOpacity, 'background-color': this.currentSlide.overlayColor });
				$slideOverlayLeft.css({ top:selectorTop, left:0, height: selectorHeight, width:selectorLeft, opacity: this.currentSlide.overlayOpacity, 'background-color': this.currentSlide.overlayColor });
				$slideOverlayRight.css({ top:selectorTop, left:(selectorLeft+selectorWidth), right:0, height: selectorHeight, opacity: this.currentSlide.overlayOpacity, 'background-color': this.currentSlide.overlayColor });
				$slideOverlayBottom.css({ top:bottomTop, left:0, right:0, bottom:0, opacity: this.currentSlide.overlayOpacity, 'background-color': this.currentSlide.overlayColor });

			}

		},

		check: function(){

			if (!this.options.slides || this.options.slides.length===0){
				alert('[Tutorialize Error] Slides undefined or Slides empty!');
				return;
			}

			if (this.options.remember && typeof(Storage) === "undefined" ){
				this.options.remember = false;
				alert('[Tutorialize Error] Your browser doesn\'t support web storage');
				return;
			}
			
		},

		checkSlidePositionExceed: function(position, $slide){
		
			var domValues = this.getDOMValues(),
					scrollLeft = domValues.body.scrollLeft,
					scrollTop = domValues.body.scrollTop,
					positionExceed = [],
					heightExceedTop = scrollTop > position.top,
					heightExceedBottom = (position.top + $slide.outerHeight() - scrollTop) > domValues.win.height,
					widthExceedLeft = scrollLeft > position.left,
					widthExceedRight = (position.left + $slide.outerWidth()) > domValues.win.width;
			
			//if (heightExceedTop) positionExceed.push('top');
			//if (heightExceedBottom) positionExceed.push('bottom');
			if (widthExceedLeft) positionExceed.push('left');
			if (widthExceedRight) positionExceed.push('right');

			return positionExceed.length ? positionExceed : false;

		},

		getArrowPosition: function(){

			var css = {},
					negArrowSize = this.currentSlide.arrowSize*-1;
			
			switch (this.currentSlide.position){

				case 'center-center':
					break;

				case 'top-left':
					css = {
						'background-position': '0 -'+(this.currentSlide.arrowSize*6)+'px',
						bottom: negArrowSize,
						left: '10%'
					}
					break;

				case 'top-center':
					css = {
						'background-position': '0 -'+(this.currentSlide.arrowSize*7)+'px',
						bottom: negArrowSize,
						left: '50%',
						'margin-left': (this.currentSlide.arrowSize/2)*-1
					}
					break;

				case 'top-right':
					css = {
						'background-position': '0 -'+(this.currentSlide.arrowSize*8)+'px',
						bottom: negArrowSize,
						right: '10%'
					}
					break;

				case 'left-top':
					css = {
						'background-position': '0 -'+(this.currentSlide.arrowSize*3)+'px',
						right: negArrowSize,
						top: '10%'
					}
					break;

				case 'left-center':
					css = {
						'background-position': '0 -'+(this.currentSlide.arrowSize*4)+'px',
						'margin-top': (this.currentSlide.arrowSize/2)*-1, 
						right: negArrowSize,
						top: '50%'
					}
					break;

				case 'left-bottom':
					css = {
						bottom: '10%',
						'background-position': '0 -'+(this.currentSlide.arrowSize*5)+'px',
						right: negArrowSize,
					}
					break;	

				case 'right-top':
					css = {
						left: negArrowSize,
						top: '10%'
					}
					break;

				case 'right-center':
					css = {
						'background-position': '0 -'+(this.currentSlide.arrowSize*1)+'px',
						left: negArrowSize,
						'margin-top': (this.currentSlide.arrowSize/2)*-1, 
						top: '50%'
					}
					break;

				case 'right-bottom':
					css = {
						'background-position': '0 -'+(this.currentSlide.arrowSize*2)+'px',
						left: negArrowSize,
						bottom: '10%'
					}
					break;

				case 'bottom-left':
					css = {
						'background-position': '0 -'+(this.currentSlide.arrowSize*9)+'px',
						top: negArrowSize,
						left: '10%'
					}
					break;

				case 'bottom-center':
					css = {
						'background-position': '0 -'+(this.currentSlide.arrowSize*10)+'px',
						top: negArrowSize,
						left: '50%',
						'margin-left': (this.currentSlide.arrowSize/2)*-1
					}
					break;

				case 'bottom-right':
					css = {
						'background-position': '0 -'+(this.currentSlide.arrowSize*11)+'px',
						top: negArrowSize,
						right: '10%'
					}
					break;

			}

			return css;

		},

		getDOMValues: function(){

			var $win = $(window),
					$doc = $(document),
					$body = $('body'),
					domValues = {
						body:{
							scrollLeft: document.documentElement.scrollLeft ? document.documentElement.scrollLeft : $body.scrollLeft(), // documentElement for IE and Old Browser
							scrollTop: document.documentElement.scrollTop ? document.documentElement.scrollTop : $body.scrollTop() // documentElement for IE and Old Browser
						},
						doc:{
							height: $doc.height(),
							width: $doc.width()
						},
						win:{
							height: $win.height(),
							width: $win.width()
						}
					};

			return domValues;

		},

		getRemember: function(){

			if (!this.options.remember) return;
			return localStorage.getItem(this.options.rememberKeyName) ? JSON.parse(localStorage.getItem(this.options.rememberKeyName)) : false;

		},

		getSlideDefaultValues: function(slide){
		
			slide.arrowOffset = slide.arrowOffset ? slide.arrowOffset : this.options.arrowOffset;
			slide.arrowPath = slide.arrowPath ? slide.arrowPath : this.options.arrowPath;
			slide.arrowSize = slide.arrowSize ? slide.arrowSize : this.options.arrowSize;
			slide.bgColor = slide.bgColor ? slide.bgColor : this.options.bgColor;
			slide.borderRadius = slide.borderRadius ? slide.borderRadius : this.options.borderRadius;
			slide.buttonBgColor = slide.buttonBgColor ? slide.buttonBgColor : this.options.buttonBgColor;
			slide.buttonFontColor = slide.buttonFontColor ? slide.buttonFontColor : this.options.buttonFontColor;
			slide.fontColor = slide.fontColor ? slide.fontColor : this.options.fontColor;
			slide.fontSize = slide.fontSize ? slide.fontSize : this.options.fontSize;
			slide.height = slide.height ? slide.height : this.options.height;
			slide.minWidth = slide.minWidth ? slide.minWidth : this.options.minWidth;
			slide.minWidthCSS = slide.minWidthCSS ? slide.minWidthCSS : this.options.minWidthCSS;
			slide.overlayColor = slide.overlayColor ? slide.overlayColor : this.options.overlayColor;
			slide.overlayMode = slide.overlayMode ? slide.overlayMode : this.options.overlayMode;
			slide.overlayOpacity = slide.overlayOpacity ? slide.overlayOpacity : this.options.overlayOpacity;
			slide.overlayPadding = slide.overlayPadding ? slide.overlayPadding : this.options.overlayPadding;
			slide.padding = slide.padding ? slide.padding : this.options.padding;
			slide.position = (slide.position && ['center-center', 'top-left', 'top-center', 'top-right', 'left-top', 'left-center', 'left-bottom', 'right-top', 'right-center', 'right-bottom', 'bottom-left', 'bottom-center', 'bottom-right'].indexOf(slide.position)!=-1) ? (slide.selector === 'html' ? 'center-center' : slide.position) : 'center-center';
			slide.selector = slide.selector ? slide.selector : 'html';
			slide.width = slide.width ? slide.width : this.options.width;

			return slide;			

		},

		getSlidePosition: function($selector, $slide){

			var domValues = this.getDOMValues(),
					selectorLeft = $selector.offset().left,
					selectorTop = $selector.css('position') === 'fixed' ? ($selector.position().top-domValues.body.scrollTop) : $selector.offset().top,
					selectorWidth = $selector.outerWidth(),
					selectorHeight = $slide.attr('data-selector') === 'html' ? domValues.win.height : $selector.outerHeight(),
					slideWidth = $slide.outerWidth(),
					slideHeight = $slide.outerHeight(),
					slideTop, slideLeft = 0,
					slidePosition = $slide.attr('data-position'),
					slidePositionSplitted = slidePosition.split('-'),
					returnData = {};

			switch (slidePosition){

				case 'center-center':
					slideLeft = selectorLeft + ( (selectorWidth/2) - (slideWidth/2) );
					slideTop = selectorTop + ( (selectorHeight/2) - (slideHeight/2) );
					break;

				case 'top-left':
					slideLeft = selectorLeft;
					slideTop = selectorTop - slideHeight;
					break;

				case 'top-center':
					slideLeft = selectorLeft + ( (selectorWidth/2) - (slideWidth/2) );
					slideTop = selectorTop - slideHeight;
					break;

				case 'top-right':
					slideLeft = (selectorLeft + selectorWidth) - slideWidth;
					slideTop = selectorTop - slideHeight;
					break;

				case 'left-top':
					slideLeft = selectorLeft - slideWidth;
					slideTop = selectorTop;
					break;

				case 'left-center':
					slideLeft = selectorLeft - slideWidth;
					slideTop = selectorTop + ( (selectorHeight/2) - (slideHeight/2) );
					break;

				case 'left-bottom':
					slideLeft = selectorLeft - slideWidth;
					slideTop = (selectorTop + selectorHeight) - slideHeight;
					break;	

				case 'right-top':
					slideLeft = selectorLeft + selectorWidth;
					slideTop = selectorTop;
					break;

				case 'right-center':
					slideLeft = selectorLeft + selectorWidth;
					slideTop = selectorTop + ( (selectorHeight/2) - (slideHeight/2) );
					break;

				case 'right-bottom':
					slideLeft = selectorLeft + selectorWidth;
					slideTop = (selectorTop + selectorHeight) - slideHeight;
					break;

				case 'bottom-left':
					slideLeft = selectorLeft;
					slideTop = selectorTop + selectorHeight;
					break;

				case 'bottom-center':
					slideLeft = selectorLeft + ( (selectorWidth/2) - (slideWidth/2) );
					slideTop = selectorTop + selectorHeight;
					break;

				case 'bottom-right':
					slideLeft = (selectorLeft + selectorWidth) - slideWidth;
					slideTop = selectorTop + selectorHeight;
					break;

			}

			if (slidePositionSplitted[0] === 'left'){
				slideLeft -= (this.currentSlide.arrowSize + this.currentSlide.arrowOffset);
			}else if (slidePositionSplitted[0] === 'right'){
				slideLeft += (this.currentSlide.arrowSize + this.currentSlide.arrowOffset);
			}else if (slidePositionSplitted[0] === 'top'){
				slideTop -= (this.currentSlide.arrowSize + this.currentSlide.arrowOffset);
			}else if (slidePositionSplitted[0] === 'bottom'){
				slideTop += (this.currentSlide.arrowSize + this.currentSlide.arrowOffset);
			}

			returnData = {
				left: slideLeft,
				slidePosition: slidePosition,
				top: slideTop	
			}

			// Check Exceed
			var positionExceed;
			if (positionExceed = this.checkSlidePositionExceed(returnData, $slide)){
				
				if (!this.tmpCurrentSlidePosition) this.tmpCurrentSlidePosition = this.currentSlide.position; 
				this.tmpWinWidth = domValues.win.width;
			
				if (positionExceed.indexOf('left') !== -1 || positionExceed.indexOf('right') !== -1){

					if ($slide.width() >= this.currentSlide.minWidth){

						if ($slide.width() < this.currentSlide.minWidthCSS) $slide.addClass('minWidthCSS');
						$slide.width($slide.width()-10);
					
					}else{
						
						$slide.removeClass('minWidthCSS');
						$slide.attr('data-position', 'center-center');
						$slide.width(this.currentSlide.width);
				
					}

					return this.getSlidePosition($selector, $slide);

				}

			}else{

				if (this.tmpCurrentSlidePosition) $slide.attr('data-position', this.tmpCurrentSlidePosition);
			
				if (domValues.win.width > this.tmpWinWidth && $slide.width() < this.currentSlide.width){

					if ($slide.width() >= this.currentSlide.minWidthCSS) $slide.removeClass('minWidthCSS');
					$slide.width($slide.width()+10);
					return this.getSlidePosition($selector, $slide);

				}

			}

			return returnData;

		},

		moveOverlayOnResize: function(){
			
			var self = this;

			this.$tutorializeElements.find('.tutorialize-slide').each(function(idx){

				var $slide = $(this),
						$selector = $($slide.attr('data-selector'));

				self.calculateSlideOverlay($selector, true);

			});

		},

		moveSlideOnResize: function(){
			
			var self = this;

			this.$tutorializeElements.find('.tutorialize-slide').each(function(idx){

				var $slide = $(this),
						$slideArrow = $slide.find('.tutorialize-slide-arrow'),
						$slideSelector = $($slide.attr('data-selector'));

				self.setSlide_Position($slideSelector, $slide, $slideArrow);
		
			});

		},

		nextSlide: function(slideIdx){
			
			var self = this,
					lastSlide = this.currentSlideIdx >= this.options.slides.length-1;

			if (lastSlide && this.options.runEnd === 'stop'){

				this.setRemember(0, this.options.rememberOnceOnly);
				this.stop();
				return;

			}

			this.currentSlideIdx = slideIdx ? slideIdx : ( lastSlide ? 0 : this.currentSlideIdx+1 );
			
			this.lastSlide = this.currentSlide;
			this.currentSlide = this.getSlideDefaultValues(this.options.slides[this.currentSlideIdx]);
			this.setRemember(this.currentSlideIdx);
			this.runSlide();
			
			if (this.options.runMode === 'auto'){

				self.stSlidePlay = setTimeout(function(){ 
					self.nextSlide();
				}, this.options.runDuration);

			}

		},

		prevSlide: function(){

			this.currentSlideIdx === 0 ? this.currentSlideIdx=this.options.slides.length-1 : this.currentSlideIdx--;
			this.lastSlide = this.currentSlide;
			this.currentSlide = this.getSlideDefaultValues(this.options.slides[this.currentSlideIdx]);
			this.setRemember(this.currentSlideIdx);
			this.runSlide();		

		},

		runSlide: function(){

			var self = this,
					domValues = this.getDOMValues();

			// Re Init Temp Var
			this.tmpCurrentSlidePosition = '';
					
			// Slide Append
			this.$tutorializeElements.empty().append(this.runSlide_BuildSlide());
			
			var $slide = this.$tutorializeElements.find('.tutorialize-slide'),
					$slideArrow = $slide.find('.tutorialize-slide-arrow'),
					$slideClose = $slide.find('.tutorialize-slide-close'),
					$slideControl = $slide.find('.tutorialize-slide-control'),
					$slideSelector = $(this.currentSlide.selector);

			// Fix auto width
			if (this.currentSlide.width === 'auto') this.currentSlide.width = $slide.width();

			// CSS
			$slide.css({
				'background-color': this.currentSlide.bgColor,
				'border-radius': this.currentSlide.borderRadius,
				color: this.currentSlide.fontColor,
				'font-size': this.currentSlide.fontSize,
				height: this.currentSlide.height=='auto' ? 'auto' : this.currentSlide.height,
				padding: this.currentSlide.padding,
				position: ($slideSelector.css('position') === 'fixed' || (this.currentSlide.position === 'center-center' && this.currentSlide.selector === 'html') ) ? 'fixed' : 'absolute',
				width: this.currentSlide.width=='auto' ? $slide.width() : this.currentSlide.width
			});

			$slideArrow.css({
				'background-image': 'url("'+this.currentSlide.arrowPath+'")',
				height: this.currentSlide.arrowSize,
				width: this.currentSlide.arrowSize
			});

			$slideClose.css({
				'background-color': this.currentSlide.buttonBgColor
			});

			$slideControl.find('li').css({
				'background-color': this.currentSlide.buttonBgColor
			});

			$slideControl.find('li span').css({
				color: this.currentSlide.buttonFontColor
			});

			// Position
			var position = this.setSlide_Position($slideSelector, $slide, $slideArrow);

			// Overlay
			if (this.currentSlide.overlayMode!='none') this.calculateSlideOverlay($slideSelector);

			// Scroll
			var scrollDirTop = position.top >= this.lastSlideScrollPosition ? false : true,
					scrollTopValue = scrollDirTop ? position.top : position.top + $slide.height();

			if (this.options.autoScroll && this.currentSlide.selector !== 'html' && $slideSelector.css('position') !== 'fixed' && (domValues.body.scrollTop > scrollTopValue || scrollTopValue > ( domValues.win.height+domValues.body.scrollTop) ) ){
				
				scrollTopValue = scrollDirTop ? scrollTopValue-this._scrollTopOffset : (scrollTopValue+this._scrollTopOffset)-domValues.win.height;
				$('html,body').animate({scrollTop: scrollTopValue}, this.options.effectDelay);
			
			}
			
			this.lastSlideScrollPosition = position.top;

			// Slide Effect
			if (!this.lastSlide || this.lastSlide.position!==this.currentSlide.position){
				$slide.fadeIn(this.options.effectDelay);
			}else{
				var elements = '.tutorialize-slide-content, .tutorialize-slide-control, .tutorialize-slide-title';
				$slide.show().find(elements).hide().fadeIn(this.options.effectDelay);
			}

			// Callback
			if ($.isFunction(this.currentSlide.onSlide)) this.currentSlide.onSlide.apply(this, [this.currentSlideIdx, this.currentSlide, $slide]);

			// Slide Events
			$slideControl.find('li.prev span').on('click', function(e){
				e.preventDefault();

				clearTimeout(self.stSlidePlay);

				if ($.isFunction(self.currentSlide.onPrev)){
					self.currentSlide.onPrev.apply(this, [self.currentSlideIdx, self.currentSlide, $slide]);
				}else{
					self.prevSlide();
				}

			});

			$slideControl.find('li.next span').on('click', function(e){
				e.preventDefault();

				clearTimeout(self.stSlidePlay);
				
				if ($.isFunction(self.currentSlide.onNext)){
					self.currentSlide.onNext.apply(this, [self.currentSlideIdx, self.currentSlide, $slide]);
				}else{
					self.nextSlide();
				}
				
			});

			$slideClose.on('click', function(e){
				e.preventDefault();

				self.stop();
				
			});

		},

		runSlide_BuildControls: function(){

			var slidesLength = this.options.slides.length,
					control = '<ul class="tutorialize-slide-control">' +
										'<li class="counter">'+(this.currentSlideIdx+1)+'/'+slidesLength+'</li>' +
										'<li class="next'+(!this.options.showButtonNext ? ' hide' : '')+'"><span>'+( (this.currentSlideIdx===0 && slidesLength>1) ? this.options.labelStart : ( (this.currentSlideIdx!=slidesLength-1) ? this.options.labelNext : this.options.labelEnd) )+'</span></li>'+
										'<li class="prev'+(!this.options.showButtonPrevious || !this.currentSlideIdx ? ' hide' : '')+'"><span>'+this.options.labelPrevious+'</span></li>' +
										'</ul>';

			return control;

		},

		runSlide_BuildSlide: function(){
			
			return '<div class="tutorialize-slide '+this.options.theme+'" data-selector="'+this.currentSlide.selector+'" data-position="'+this.currentSlide.position+'">' +
						 '<span class="tutorialize-slide-arrow"></span>' +
						 (this.options.showButtonClose ? '<span class="tutorialize-slide-close">x</span>' : '') +
						 (this.currentSlide.title ? '<div class="tutorialize-slide-title">'+this.currentSlide.title+'</div>' : '') +
						 '<div class="tutorialize-slide-content">'+this.currentSlide.content+'</div>' +
						 this.runSlide_BuildControls() +
						 '</div>';

		},

		setRemember: function(currentSlideIdx, onceOnly){

			if (!this.options.remember) return;

			localStorage.setItem(this.options.rememberKeyName, JSON.stringify({
				currentSlideIdx:currentSlideIdx,
				onceOnly: onceOnly ? onceOnly : false
			}));

		},

		setSlide_Position: function($slideSelector, $slide, $slideArrow){

			var	position = this.getSlidePosition($slideSelector, $slide);
					this.currentSlide.position = position.slidePosition;
					arrowPosition = this.getArrowPosition();

			$slide.css({ 
				top: position.top, 
				left: position.left
			});
			
			$slideArrow.css({
				'background-position':'0 0',
				top:'auto', 
				left:'auto', 
				right:'auto', 
				bottom:'auto',
				margin:'auto'
			}).css(arrowPosition);

			if (this.currentSlide.position === 'center-center')
				$slideArrow.hide();
			else
				$slideArrow.show();

			return position;

		},

		start: function(slideIdx){

			var self = this,
					remember = this.getRemember();

			this.currentSlideIdx = remember ? remember.currentSlideIdx-1 : -1
		
			if (!remember || !remember.onceOnly){

				self.nextSlide(slideIdx); 
				if ($.isFunction(self.options.onStart)) self.options.onStart.apply(self, [self.currentSlideIdx, self.currentSlide, this.$tutorializeElements.find('.tutorialize-slide')]);

			}

		},

		stop: function(){

			if ($.isFunction(this.options.onStop)) this.options.onStop.apply(this, [this.currentSlideIdx, this.currentSlide, this.$tutorializeElements.find('.tutorialize-slide')]);
			clearTimeout(this.stSlidePlay);
			this.$tutorializeElements.remove();
			$(document).off("keyup");

		}

	}

})(jQuery);
