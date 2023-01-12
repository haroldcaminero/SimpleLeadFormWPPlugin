( function ( $ ) {
    console.log(' i am erady');
    let formObject = null;

    function validateEmail(email){
        if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email)){
            return (true)
        }
        return (false)
    }

    function validate_form() {
        formObject.find( '.error' ).remove();
        const errors = [];
        formObject.find( '.validate' ).each( function () {
            const name = $( this ).attr( 'name' );
            const labelName = $( this ).closest( '.hlgf-form-input' ).find( 'label' ).text();
            const value = $( this ).val().trim();
            const validate = $( this ).data( 'validate' );
            const _this = $(this);
            if( validate ) {
                const validateArr = validate.split(",");
                validateArr.forEach( function ( val ) {
                    if ( val === 'required' && value == '' ) {
                        withError = true;
                        $( `<div class="error">${labelName} is required.</div>` ).insertAfter( _this );
                        errors.push(name);
                        return;
                    }
                    if ( val === 'email' && !validateEmail(val)) {
                        $( `<div class="error">${labelName} must be a valid email address.</div>` ).insertAfter( _this );
                        errors.push(name);
                        return;
                    }
                } )
            }
        } );
        return errors;
    }

    $( document ).ready( function () {
        formObject = $( '#hlgf-form' );
        $( '#hlgf-form-nonce' ).val( hlfa_ajax.security );
        formObject.submit( function(e) {
            e.preventDefault();
            const errors = validate_form();
            if( errors.length === 0 ) {
                
                $.ajax({
                    url: hlfa_ajax.ajaxurl,
                    type: 'POST',
                    data: formObject.serialize(),
                    dataType: 'json',
                    success: function (response) { 
                        if( response.success == 'yes' ){
                            $(' <p style="color: green; ">Submitted. Thank you.</p> ').insertAfter( formObject );
                            formObject.remove();
                        }
                        
                    },
                    complete: function(){
                    },
                });	
                
            }
           
        } );
    } );



} )( jQuery );