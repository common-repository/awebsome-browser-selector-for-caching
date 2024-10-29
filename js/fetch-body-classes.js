jQuery( function ($) {
	var bodyclass = null;
	var cookiename = 'awebsome-bs-cache-body-classes';
	if ( false && $.cookie && ( bodyclass = $.cookie( cookiename ) ) ) {	// TODO remove false from this statement once awebsome gets fixed http://wordpress.org/support/topic/bug-report-with-solution-ie-doesnt-get-added-to-body-class?replies=4#post-4074429
		// we have the classes in a cookie ... use them to add to the body
		$( 'body' ).addClass( bodyclass );
		return;
	}
	
	//don't have the classes yet ... get them via AJAX
	$.get( AwebsomeBSForCaching.ajaxurl, { action:'awebsome-bs-for-caching' }, function ( response ) {
		var classes = response + ' awebsome-bs-for-caching';	// adds one more class to the body so we know this is working
		$( 'body' ).addClass( classes );
		if ($.cookie)
			$.cookie( cookiename, classes, { path:'/' } ); // set cookie so we don't have to keep refetching this junk
	} );
} );