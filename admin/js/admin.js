( function ( $ ) {

    function generate_shortcode() {
        const shortCodeName = $( '#hlgf-shortcode' ).data( 'shortcodename' );
        const attributes = [];
        $( '.hlgf-box .field-name' ).each( function () {
            const fieldName = $( this ).data( 'type' );
            $( this ).find( 'input' ).each( function () {
                const val = $( this ).val().trim();
                const name = $( this ).attr( 'name' );
                if ( name == 'label' ||  val != '' ){
                    attributes.push( fieldName + '_' + name + '="' + val + '"' );
                }
                
            } );
        } );

        $( '#hlgf-shortcode' ).text( '[' + shortCodeName + ' ' + (attributes.join(" ")) + ']' );
    }
 
    $( document ).ready( function() {

        generate_shortcode();

        $( '.hlgf-container' ).on( 'keyup', 'input', generate_shortcode );
    
    } );


} )( jQuery );