( function ($) {

	$( document ).ready( function() {

		$( '#name-gen-button' ).click( function() {
			var genId = $( this ).data( 'gen' );
			generateName( genId );
		});

		function generateName( genId ) {
			$.ajax({
				type : "post",
				dataType : "json",
				url : gen_ajax.ajaxurl,
				data : { action: "generate_name", gen_id : genId },
				success: function( response ) {
					console.log( response );
					$( '#name-gen-result' ).text( '' );
					$( '#name-gen-result' ).html( response );
				},
				error: function( jqXHR, textStatus, errorThrown ) {
					console.log( jqXHR.responseText);
				}
			});
		};

	});

}( jQuery ) );
