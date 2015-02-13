jQuery(function() {
	
	var $ = jQuery;

	var $container = $('.fbpw').masonry();

	// layout Masonry again after all images have loaded
	$container.imagesLoaded( function() {
		resizeThumbs();
		$container.masonry();
	});

	$(window).resize(function() {
		resizeThumbs();
		$container.masonry();
	});

	$('.fbpw-image').colorbox(
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

	function resizeThumbs() {
		var w = $('.fbpw').width();
		var p = parseInt($('.fbpw img').css('margin-left'))+parseInt($('.fbpw img').css('margin-left'));

		if (w>800) d = 4;
		else if (w>600) d = 3;
		else if (w>400) d = 2;
		else if (w>200) d = 1;

		$('.fbpw img').css("max-width", parseInt((w-p*d)/d));
	}

});