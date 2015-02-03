jQuery(function() {
	
	var $ = jQuery;

	var $container = $('.fbpw').masonry();
	// layout Masonry again after all images have loaded
	$container.imagesLoaded( function() {
	  $container.masonry();
	});

	$(".fbpw-image").colorbox(
		{
			rel:'fbpw-image',
			transition:"fade",
			reposition: true,
			scrolling: false,
			maxWidth: "95%",
			maxHeight: "95%",
			opacity: 1,
			fixed: true,
			current: ""
		}
	);

	//Todo Function for Fluid image resize

});