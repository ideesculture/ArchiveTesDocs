// -- Link to same localization --
$('#divLinkLocalization').click( function( event ){
    event.preventDefault();

    // Activate only if something is selected
    if( $('#listarchives').bootstrapTable('getSelections').length > 0 ) {
        if ($uawhere == UAWHERE_PROVIDER) {
            $('#waitAjax').show();
            updateProvLocModal();

        } else {
            $('#divModalLblLocProvider').hide();
            $('#divModalSelectLocProvider').hide();
            $('#divModalLblLocalization').hide();
            $('#divModalSelectLocalization').hide();
            $('#divModalLblLocalizationfree').show();
            $('#divModalInputLocalizationfree').show();
            $('#frm_samelocalizationfree').val('');
            $('#LocalizationModal').modal('show');
        }
    }
});

$('#btnLocalizationModalConfirm').click(function(){

    // Only if localization field is not empty
    if( ( ( $uawhere == UAWHERE_PROVIDER ) && ( $('#frm_samelocalization option:selected').val() == '' ) )
        ||( ( $uawhere != UAWHERE_PROVIDER ) && ( $('#frm_samelocalizationfree').val() == '' ) ) ){
        bootbox.alert( {
            message: $_translations[98],
            className: "boxErrorOne"
        } );
    } else {
        var $selections = $('#listarchives').bootstrapTable( 'getSelections' );
        var $idlist = '';
        var $bFirst = true;
        $selections.forEach( function( $elem ){
            if( $bFirst )
                $bFirst = false;
            else
                $idlist += ',';

            $_idArrayToReckeck.push($elem['id']);
            $idlist += $elem['id'];
        });
        var $datas = {
            'idlist': $idlist,
            'uawhere': $uawhere,
            'provider_id': $('#frm_samelocprovider option:selected').val(),
            'localization_id': $('#frm_samelocalization option:selected').val(),
            'localizationfree': $('#frm_samelocalizationfree').val()
        };
        $.ajax({
            type: "GET",
            url: window.JSON_URLS.bs_idp_archivist_json_update_localization,
            data: $datas,
            cache: false,
            success: function( ){
                $('#LocalizationModal').modal('hide');
                $('#listarchives').bootstrapTable('refresh');
                $_recheck = true;   // Activate post reload rechecking
            },
            error: function (xhr, ajaxOptions, thrownError) {
                bootbox.alert( {
                    message: $_translations[87],
                    className: "boxSysErrorOne"
                } );
                $('#LocalizationModal').modal('hide');
            }
        });
    }
});

function updateProvLocModal( ){
    // Rebuild ID list from Selection
    $UASSelected = $('#listarchives').bootstrapTable('getSelections');
    var $idlist = '';
    var $bFirst = true;
    $UASSelected.forEach( function( $ua ){
        if( $bFirst )
            $bFirst = false;
        else
            $idlist += ',';

        $idlist += $ua['id'];
    });
    // Call server to get back allowed providers
    var $datas = {
        'idslist': $idlist
    }
    $.ajax({
        type: 'GET',
        url: window.JSON_URLS.bs_idp_archivist_json_allowed_providers,
        data: $datas,
        cache: false,
        success: function( $response ){
            // Update ProvLoc Modal
            if( $response['commonProviders'] == null ){
                $('#waitAjax').hide();
                bootbox.alert( {
                    message: "Cette action est impossible car les archives n'ont pas de compte prestataire en commun.",
                    className: "boxErrorOne"
                } );
            } else {
                updateModalLocalizations( $response['commonProviders'] );
                // and show it
                $('#waitAjax').hide();
                $('#divModalLblLocProvider').show();
                $('#divModalSelectLocProvider').show();
                $('#divModalLblLocalization').show();
                $('#divModalSelectLocalization').show();
                $('#divModalLblLocalizationfree').hide();
                $('#divModalInputLocalizationfree').hide();
                $('#frm_samelocalizationfree').val('');
                $('#LocalizationModal').modal('show');
            }
        },
        error: function( ){
            $('#waitAjax').hide();
            bootbox.alert( {
                message: $_translations[87],
                className: "boxSysErrorOne"
            } );
        }
    });
};

function updateModalLocalizations( $providers ){

    $bFirst = true;
    $allowedLocalizations = [];
    $partialModalLocProvider = "<option value ></option>";
    $providers.forEach( function ($providerLine){
        $partialModalLocProvider += "<option value=\"" + $providerLine['id'] + "\" data=\"" +
            $providerLine['localization_id'] + "\" " +
            ($bFirst?"selected=\"selected\" ":"") + ">" +
            $providerLine['name'] + "</option>";
        if( $bFirst ){
            $bFirst = false;
            $allowedLocalizations = $providerLine['localization_id'];
        }
    });

    $partialModalLocalizations = "<option value selected=\"selected\"></option>";

    $localizations.forEach(function ($localizationLine) {
        if ($allowedLocalizations == $localizationLine[LOCALIZATION_ID] ) {
            $partialModalLocalizations += "<option value=\"" + $localizationLine[LOCALIZATION_ID] + "\">" + $localizationLine[LOCALIZATION_NAME] + "</option>";
        }
    });

    $('#frm_samelocprovider').html($partialModalLocProvider);
    $('#frm_samelocalization').html($partialModalLocalizations);
}

$('#frm_samelocprovider').change( function(){
    $provider_loc_id = parseInt( $("#frm_samelocprovider option:selected").attr('data') );

    $partialModalLocalizations = "<option value selected=\"selected\"></option>";
    $localizations.forEach(function ($localizationLine) {
        if ($localizationLine[LOCALIZATION_ID] == $provider_loc_id) {
            $partialModalLocalizations += "<option value=\"" + $localizationLine[LOCALIZATION_ID] + "\">" + $localizationLine[LOCALIZATION_NAME] + "</option>";
        }
    });

    $('#frm_samelocalization').html($partialModalLocalizations);
});