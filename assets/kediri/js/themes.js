$(document).ready(function(e){
	if($('#header-navigation').length){
		$('#header-navigation').affix({
		  offset: {
		    top: $('#header-navigation').position().top
		  }
		});
	}
	
	if($('.slider-main .owl-carousel').length){
		var slider = $(".slider-main .owl-carousel").owlCarousel({
		    			items:1,
		    			loop:true,
		    			center: true,
		    			nav:true,
		    			navText:['<span class="fa fa-chevron-left"></span>','<span class="fa fa-chevron-right"></span>'],
		    			autoplay:true,
		    			autoplayHoverPause:true,
		    			animateOut: 'fadeOut',
		   				animateIn: 'fadeIn',
		   				/*
						   onChange:function(event){
																  $(".slider-main .owl-carousel").find('.owl-item').find('.slider-caption').hide();//.removeClass('hide-caption');
															   console.log('Y');
												   },
													  onChanged:function(event){
																 var ele = event.target;																  var $ele = $(ele);
																  $(".slider-main .owl-carousel").find('.owl-item.active').find('.slider-caption').show();//.removeClass('hide-caption');
																 console.log("X");
													 }*/
						   
		    		});
	}
	if($('.count-to').length){
		$('.count-to').each(function () {
			var $this = $(this);
			$this.one('inview',function(e){
			    $this.prop('Counter',0).animate({
			        Counter: $(this).text()
			    }, {
			        duration: 4000,
			        easing: 'swing',
			        step: function (now) {
			            $this.find('.count-object').text(Math.ceil(now));
			        }
			    });
		    });
		});
	}
	
	if($('.slider-testimonial>.slide').length){
		$('.slider-testimonial>.slide').slick({
			infinite: true,
			slidesToShow: 1,
			slidesToScroll: 1,
			arrows: false,
			autoplay: true,
			pauseOnHover: true,
			dots: false,
			mobileFirst: true,
			swipeToSlide: true,
			responsive: [
						    {
						      breakpoint: 720,
						      settings: {
						        slidesToShow: 2
						      }
						    }
					    ]
	      });
	}
	
	if($('.the-row-isotop').length){
		// init Masonry
		var $grid = $('.the-row-isotop').masonry({
		  itemSelector: '.the-grid-isotop',
		  // use element for option
		  columnWidth: '.the-grid-isotop',
		  percentPosition: true
		});
		// layout Masonry after each image loads
		$grid.imagesLoaded().progress( function() {
		  $grid.masonry('layout');
		});
	}
});
