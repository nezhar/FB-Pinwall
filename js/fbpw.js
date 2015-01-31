jQuery(function() {
	
	var $ = jQuery;

	var $container = $('.fbpw').masonry();
	// layout Masonry again after all images have loaded
	$container.imagesLoaded( function() {
	  $container.masonry();
	});


	//Todo Function for Fluid image resize

});