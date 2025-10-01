$('#btnResetReconciliate').click( function( event ) {
    event.preventDefault();
    $('#waitAjax').show();

    $.get( window.JSON_URLS.bs_idp_archive_reconciliation_reset )
        .done( function( response ){
            window.location.replace( window.JSON_URLS.bs_idp_archive_reconciliation_index );
        })
        .fail( function( response ){
            alert( "Erreur serveur" );
            $('#waitAjax').hide();
        });
});