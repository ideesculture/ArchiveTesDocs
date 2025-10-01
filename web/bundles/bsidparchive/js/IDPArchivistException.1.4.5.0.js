//------------------------------------------------------------------------------------
// Verify if all UAs have the localization (or localizationfree) field filled
//
// -> $listUAs: list of UAs to verify
// -> $bFreeOrLink: verify LocalizationFree (true) or Localization (false)
// <- Boolean: true at least one UAs have localization field missing, otherwise false
function isLocalizationMissing( $listUAs, $bFreeOrLink ){
    $localizationMissing = false;
    $listUAs.forEach( function( $elem ){
        if( $bFreeOrLink ){
            if( $elem['localizationfree'] == '' || $elem['localizationfree'] === null || $elem['localizationfree'] == '-' ) {
                $localizationMissing = true;
                return;
            }
        } else {
            if( $elem['localization'] == '' || $elem['localization'] === null || $elem['localization'] == '-' ) {
                $localizationMissing = true;
                return;
            }
        }
    });
    return $localizationMissing;
}

//------------------------------------------------------------------------------------
// Verify if all UAs have the provider field filled
//
// -> $listUAs: list of UAs to verify
// <- Boolean: true at least one UAs have provider field missing, otherwise false
function isProviderMissing( $listUAs ){
    $providerMissing = false;
    $listUAs.forEach( function( $elem ){
        if( $elem['provider'] == '' || $elem['provider'] === null || $elem['provider'] == '-' ) {
            $providerMissing = true;
            return;
        }
    });
    return $providerMissing;
}

//----------------------------------------------------------------------------------------------
// Test we can do on the client side before sending request ACTION to backoffice
function is_ClientSide_Exceptions_Verified( ){
    switch( $uawhat ){
        case UAWHAT_TRANSFER:
        case UAWHAT_RELOC:
            switch( $uawhere ){
                case UAWHERE_INTERNAL:
                case UAWHERE_INTERMEDIATE:
                    return !isLocalizationMissing( $actionList, true );
                case UAWHERE_PROVIDER:
                    if( !isLocalizationMissing( $actionList, false ) )
                        return !isProviderMissing( $actionList );
                    else
                        return false;
            }
            break;

        case UAWHAT_CONSULT:
        case UAWHAT_RETURN:
        case UAWHAT_EXIT:
        case UAWHAT_DESTROY:
            return true;
    }
}
function make_ClientSide_Exceptions_ErrorMsg( ){
    switch( $uawhat ) {
        case UAWHAT_TRANSFER:
            switch ($uawhere) {
                case UAWHERE_INTERNAL:
                case UAWHERE_INTERMEDIATE:
                    return "Certaines archives n'ont pas de localisation. Merci de bien vouloir renseigner cette information pour procéder au transfert.";
                case UAWHERE_PROVIDER:
                    return "Certaines archives n'ont pas de localisation et/ou de compte prestataire. Merci de bien vouloir renseigner ces informations pour procéder au transfert.";
            }
            break;
        case UAWHAT_RELOC:
            switch ($uawhere) {
                case UAWHERE_INTERNAL:
                case UAWHERE_INTERMEDIATE:
                    return "Certaines archives n'ont pas de localisation. Merci de bien vouloir renseigner cette information pour procéder à la relocalisation.";
                case UAWHERE_PROVIDER:
                    return "Certaines archives n'ont pas de localisation et/ou de compte prestataire. Merci de bien vouloir renseigner ces informations pour procéder à la relocalisation.";
            }
            break;
    }
    return "";
}

//----------------------------------------------------------------------------------------------
// Manage Actions
function clickAction(){
    if( is_ClientSide_Exceptions_Verified() ) {

        $('#waitAjax').show();
        is_basket_verified( );

    } else {
        $errorMsg = make_ClientSide_Exceptions_ErrorMsg();

        $('#errorTitle').html( "Erreur" );
        $('#errorMsg').html( $errorMsg );
        $('#modalErrorMessage').modal( 'show' );
    }
}
function is_basket_verified( ){
    $dataObject = {
        'idslist': JSON.stringify($actionListID),
        'uastate': $xpstate,
        'uawhat': $uawhat,
        'uawhere': $uawhere
    };

    $.ajax({
        type: 'GET',
        url: window.JSON_URLS.bs_idp_archivist_json_is_basket_verified,
        data: $dataObject,
        cache: false,
        success: function ($response) {
            verify_Test05_Response( $response );
        },
        error: function( xhr, ajaxOptions, thrownError ){
            $('#waitAjax').hide();
            $message = xhr.responseJSON;
            bootbox.alert( {
                message: $message?$message.message:"Erreur Serveur",
                className: "boxSysErrorOne"
            });
        }
    });
}
function verify_Test05_Response( $response ){
    if( $response['result05'] == 'NOK' ){
        bootbox.dialog({
            message: $response['message05'],
            title: 'Erreur',
            className: "boxQuestionTwo",
            closeButton: false,
            buttons: {
                "Non": { label: $_translations[99], className: "btn-danger", callback: function() { $('#waitAjax').hide(); } },
                "Oui": { label: $_translations[98], className: "btn-success", callback: function() { verify_NextTest_Response( $response ); } }
            } });
    } else {
        verify_NextTest_Response( $response );
    }
}
function verify_NextTest_Response( $response ){
    if ($response['status'] == 'NOK') {
        $('#waitAjax').hide();
        bootbox.alert( {
            message: $response['message'],
            className: "boxErrorOne"
        });
    } else
        continue_After_BasketTests( true );
}