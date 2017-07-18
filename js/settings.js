(function ( $, window ) {

    var $form = $( '#autoversion-settings-form' );
    if ( !$form.length ) return;

    $( 'select', $form ).on( 'change', function() {
        var $select = $( this ),
            $version = $select.parent().find( 'span.version-number' ),
            $description = $version.parent().find( 'p.description' ).eq(0);

        $version[!parseInt( $select.val() ) ? 'hide' : 'show']();

        $description.html( $select.find( 'option:selected' ).attr( 'title' ) );
    } ).trigger( 'change' );

    $( 'input', $form ).on( 'input', function() {
        var $input = $( this );

        $input.closest( 'td' ).find( 'span.auto' )[!$input.val().trim() ? 'show' : 'hide']();
    } ).trigger( 'input' );

})( jQuery, this, this.document );
