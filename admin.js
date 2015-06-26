jQuery( document ).ready( function( $ ) {

	$( 'form#implementing-wordpress-nonces' ).submit( function( e ) {

		// Prevent form submission
		e.preventDefault();

		// Submit form via AJAX
		$.post(
	        ajaxurl, // Set by WordPress
	        {
	        	'action': 'implementing_wp_nonces',
	        	'nonce':  implementing_wordpress_nonces.nonce,
	        	'implementing_wordpress_nonces': $( 'input#implementing_wordpress_nonces' ).val()
	        },
	        function(response) {
	        	if ( response == 1 ) {
	            	alert( 'Settings Saved' );
	            } else {
	            	alert( 'Invalid nonce specified' );
	            }
	        }
	    );
    });

} );