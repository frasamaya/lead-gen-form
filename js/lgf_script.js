( function( $ ) {
  $( document ).ready( function() {
    $( '#lgf_form' ).submit(function(e){
    	e.preventDefault();
    	var postData = $(this).serializeArray();
    	var $button = $( '.lgf_button' );
    	$( $button ).text( "Loading..." )
      var data = {
        'action' : 'lgf_send_form',
        'nonce'  : $button.data('nonce'),
        'post_id': $button.data( 'post_id' ),
        'data'	 : postData
	    };
	    $.post( settings.ajaxurl, data, function( response ) {
    			$( $button ).text( settings.send_label );
    			$( '#lgf_form' ).append('<div class="notice">Your data has been submitted successfully</div>');
    			setTimeout(function(){ $('.notice').remove(); }, 2000);
	    } );
    })
  });
})( jQuery );
