// (c) Archimage - IDP Consulting 2016
// Author: Cyril PATRIGEON - BeSecure Labs

var $resultSearch = [];

var $_searchTranslations = null;
var $_searchInitialized = false;

function setServiceSelectList( ){
    $serviceOptions = "<option selected=\"selected\" data=\"-1\" value=\"-1\">"+$_searchTranslations[27]+"</option>";
    $i = 0;
    $services.forEach(function($serviceLine){
        $serviceOptions += "<option value=\"" + $serviceLine[SERVICE_ID] + "\" data=\"" + $i + "\">" + $serviceLine[SERVICE_NAME] + "</option> ";
        $i = $i + 1;
    });
    $('#service').attr('disabled', false);
    $('#service').html( $serviceOptions );
}

function setLegalEntitySelectList( ){

    $partialLegalEntities = "";
    $serviceSelected = parseInt( $("#service option:selected").attr('data') );

    $partialLegalEntities = "<option value selected=\"selected\" data=\"-1\" >"+$_searchTranslations[28]+"</option>";

    // Construct list of legal entities choices based on service id
    $listLegalEntities = [];
    if( $serviceSelected >= 0 )
        $listLegalEntities = $services[$serviceSelected][SERVICE_LEGALENTITIES_IDX];

    $i = 0;
    $legalEntities.forEach(function($legalentityLine){
        if( $serviceSelected < 0 || $listLegalEntities.indexOf( $legalentityLine[LEGALENTITY_ID] ) >= 0 ){
            $partialLegalEntities += "<option value=\"" + $legalentityLine[LEGALENTITY_ID] + "\" data=\"" + $i + "\" >" + $legalentityLine[LEGALENTITY_NAME] + "</option> ";
        }
        $i = $i + 1;
    });
    $('#legalentity').attr('disabled', false);
    $("#legalentity").html($partialLegalEntities);
}

function setDocumentNatureSelectList( ){

    $partialDocumentNature = "";
    $serviceSelected = parseInt( $('#service option:selected').attr('data') );

    $partialDocumentNature = "<option value selected=\"selected\" data=\"-1\" >"+$_searchTranslations[29]+"</option> ";

    $listDocumentNatures = [];
    if( $serviceSelected >= 0 )
        $listDocumentNatures = $services[$serviceSelected][SERVICE_DOCUMENTNATURES_IDX];

    $i = 0;
    $documentNatures.forEach(function($documentnatureLine){
        if( $serviceSelected < 0 || $listDocumentNatures.indexOf( $documentnatureLine[DOCUMENTNATURE_ID] ) >= 0 ){
            $partialDocumentNature += "<option value=\""+ $documentnatureLine[DOCUMENTNATURE_ID] +"\" data=\""+ $i +"\" >" + $documentnatureLine[DOCUMENTNATURE_NAME] + "</option>";
        }
        $i = $i + 1;
    });
    $('#documentnature').attr('disabled', false);
    $("#documentnature").html($partialDocumentNature);
}

function setDocumentTypeSelectList( ){

    $partialDocumentType = "";
    $documentnatureSelected = parseInt( $('#documentnature option:selected').attr('data') );

    $partialDocumentType = "<option value selected=\"selected\" data=\"-1\" >"+$_searchTranslations[30]+"</option> ";

    $listDocumentTypes = [];
    if( $documentnatureSelected >= 0 )
        $listDocumentTypes = $documentnatures[$documentnatureSelected][DOCUMENTNATURE_DOCUMENTTYPES_IDX];

    $i = 0;
    $documentTypes.forEach(function($documenttypeLine){
        if( $documentnatureSelected < 0 || $listDocumentTypes.indexOf( $documenttypeLine[DOCUMENTTYPE_ID] ) >= 0 ){
            $partialDocumentType += "<option value=\""+ $documenttypeLine[DOCUMENTTYPE_ID] +"\" data=\""+ $i +"\" >" + $documenttypeLine[DOCUMENTTYPE_NAME] + "</option>";
        }
        $i = $i + 1;
    });
    $('#documenttype').attr('disabled', false);
    $("#documenttype").html($partialDocumentType);
}

function setDescription1SelectList( ){

    $partialDescriptions1 = "";
    $serviceSelected = parseInt( $("#service option:selected").attr('data') );

    $partialDescriptions1 = "<option value selected=\"selected\" data=\"-1\" >"+$_searchTranslations[31]+"</option>";

    // Construct list of legal entities choices based on service id
    $listDescriptions1 = [];
    if( $serviceSelected >= 0 )
        $listDescriptions1 = $services[$serviceSelected][SERVICE_DESCRIPTIONS1_IDX];

    $i = 0;
    $descriptions1.forEach(function($description1Line){
        if( $serviceSelected < 0 || $listDescriptions1.indexOf( $description1Line[DESCRIPTION1_ID] ) >= 0 ){
            $partialDescriptions1 += "<option value=\"" + $description1Line[DESCRIPTION1_ID] + "\" data=\"" + $i + "\" >" + $description1Line[DESCRIPTION1_NAME] + "</option> ";
        }
        $i = $i + 1;
    });
    $('#description1').attr('disabled', false);
    $("#description1").html($partialDescriptions1);
}

function setDescription2SelectList( ){

    $partialDescriptions2 = "";
    $serviceSelected = parseInt( $("#service option:selected").attr('data') );

    $partialDescriptions2 = "<option value selected=\"selected\" data=\"-1\" >"+$_searchTranslations[32]+"</option>";

    // Construct list of legal entities choices based on service id
    $listDescriptions2 = [];
    if( $serviceSelected >= 0 )
        $listDescriptions2 = $services[$serviceSelected][SERVICE_DESCRIPTIONS2_IDX];

    $i = 0;
    $descriptions2.forEach(function($description2Line){
        if( $serviceSelected < 0 || $listDescriptions2.indexOf( $description2Line[DESCRIPTION2_ID] ) >= 0 ){
            $partialDescriptions2 += "<option value=\"" + $description2Line[DESCRIPTION2_ID] + "\" data=\"" + $i + "\" >" + $description2Line[DESCRIPTION2_NAME] + "</option> ";
        }
        $i = $i + 1;
    });
    $('#description2').attr('disabled', false);
    $("#description2").html($partialDescriptions2);
}

function setBudgetCodeSelectList( ){

    $partialBudgetCodes = "";
    $serviceSelected = parseInt( $("#service option:selected").attr('data') );

    $partialBudgetCodes = "<option value selected=\"selected\" data=\"-1\" >"+$_searchTranslations[33]+"</option>";

    // Construct list of legal entities choices based on service id
    $listBudgetCodes = [];
    if( $serviceSelected >= 0 )
        $listBudgetCodes = $services[$serviceSelected][SERVICE_BUDGETCODES_IDX];

    $i = 0;
    $budgetCodes.forEach(function($budgetcodeLine){
        if( $serviceSelected < 0 || $listBudgetCodes.indexOf( $budgetcodeLine[BUDGETCODE_ID] ) >= 0 ){
            $partialBudgetCodes += "<option value=\"" + $budgetcodeLine[BUDGETCODE_ID] + "\" data=\"" + $i + "\" >" + $budgetcodeLine[BUDGETCODE_NAME] + "</option> ";
        }
        $i = $i + 1;
    });
    $('#budgetcode').attr('disabled', false);
    $("#budgetcode").html($partialBudgetCodes);
}

function setProviderSelectList( ){

    $partialProviders = "";
    $serviceSelected = parseInt( $("#service option:selected").attr('data') );

    $partialProviders = "<option value selected=\"selected\" data=\"-1\" >"+$_searchTranslations[34]+"</option>";

    // Construct list of legal entities choices based on service id
    $listProviders = [];
    if( $serviceSelected >= 0 )
        $listProviders = $services[$serviceSelected][SERVICE_PROVIDERS_IDX];

    $i = 0;
    $providers.forEach(function($providerLine){
        if( $serviceSelected < 0 || $listProviders.indexOf( $providerLine[PROVIDER_ID] ) >= 0 ){
            $partialProviders += "<option value=\"" + $providerLine[PROVIDER_ID] + "\" data=\"" + $i + "\" >" + $providerLine[PROVIDER_NAME] + "</option> ";
        }
        $i = $i + 1;
    });
    $('#provider').attr('disabled', false);
    $("#provider").html($partialProviders);
}

function updateSearchLists( data ){

    updateLists( data );

    setServiceSelectList();
    setLegalEntitySelectList();
    setDocumentNatureSelectList();
    setDocumentTypeSelectList();
    setDescription1SelectList();
    setDescription2SelectList();
    setBudgetCodeSelectList();
    setProviderSelectList();

    $('#name').attr('disabled', false);
    $('#limitnum').attr('disabled', false);
    $('#limitalpha').attr('disabled', false);
    $('#limitalphanum').attr('disabled', false);
    $('#limitdate').attr('disabled', false);
//    $('#closureyear').attr('disabled', false);
//    $('#destructionyear').attr('disabled', false);
    $('#ordernumber').attr('disabled', false);
    $('#documentnumber').attr('disabled', false);
    $('#boxnumber').attr('disabled', false);
    $('#containernumber').attr('disabled', false);

    $('#searchBtn').removeClass( 'disabled' );
    $('#imgAjaxLoad').addClass('hidden');
    $('#txtBtn').removeClass('hidden');

    return true;
}

function onModifyServiceSelect( ){

    setLegalEntitySelectList();
    setDocumentNatureSelectList();
    setDocumentTypeSelectList();
    setDescription1SelectList();
    setDescription2SelectList();
    setBudgetCodeSelectList();
    setProviderSelectList();
}

function onModifyDocumentNatureSelect(){

    setDocumentTypeSelectList();
}

function setSliders( $closureMin, $closureMax, $closureFrom, $closureTo, $destructionMin, $destructionMax, $destructionFrom, $destructionTo ){

    $('#minClosureYear').html( $closureMin  );
    $('#maxClosureYear').html( $closureMax );
    $('#closureyear').val( ""+$closureFrom+","+$closureTo );
    $('#closureyear-range').slider({
        range: true,
        min: $closureMin,
        max: $closureMax,
        values: [ $closureFrom, $closureTo ],
        slide: function( event, ui ) {
            $( "#minClosureYear" ).html( ui.values[ 0 ] );
            $( "#maxClosureYear" ).html( ui.values[ 1 ] );
            $('#closureyear').val( ""+ui.values[ 0 ]+","+ui.values[ 1 ] );
        }
    });

    $('#minDestructionYear').html( $destructionMin   );
    $('#maxDestructionYear').html(  $destructionTo );
    $('#destructionyear').val( ""+$destructionFrom+","+$destructionTo );
    $('#destructionyear-range').slider({
        range: true,
        min: $destructionMin,
        max: $destructionMax,
        values: [ $destructionFrom, $destructionTo ],
        slide: function( event, ui ) {
            $( "#minDestructionYear" ).html( ui.values[ 0 ] );
            $( "#maxDestructionYear" ).html( ui.values[ 1 ] );
            $('#destructionyear').val( ""+ui.values[ 0 ]+","+ui.values[ 1 ] );
        }
    });

}

function stateFormatter( value, row, index ){

    if( $resultSearch[index]['locked'] )
        return { disabled: true };

    if( $resultSearch[index]['authorized'] )
        if( insideStateFormatter( value, row, index) )
            return { disabled: true };
        else
            return { disabled: false };
    else
        return { disabled: true };

}
function rowStyle( row, index ){

    if( $resultSearch[index]['locked'] )
        return { classes: 'locked idp_std_style' };

    if( $resultSearch[index]['authorized'] )
        if( insideRowStyle( row, index) )
            return { classes: 'info idp_std_style' };
        else
            return { classes: 'idp_std_style' };  //return { classes: 'success' };
    else
        return { classes: 'warning idp_std_style' };

}
function fctCellStyle( value, row, index ){

    if( $resultSearch[index]['locked'] )
        return { classes: 'locked' };

    if( $resultSearch[index]['authorized'] )
        if( insideRowStyle( row, index) )
            return { classes: 'info' };
        else
            return { };  //return { classes: 'success' };
    else
        return { classes: 'warning' };

}

function initSearchTable( functionToCall ){

    // Get back User Settings to init Main Tab
    $dataStr = "page=" + $_currentPage;
    $.ajax({
        type: "GET",
        url: window.JSON_URLS.bs_idp_backoffice_ajax_usersettings_get,
        data: $dataStr,
        cache: false,
        success: function($response) {
            // parameters : $table, $url, $userSettings, $tabletranslation, $commonSettings, $sidePagination, $method, $caller
            initMainTab( $('#listsearchTable'), null, $response.data, $_tabletranslation, $_commonsettings, 'server', 'get', $_currentPage )
            functionToCall();
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert( 'Error while retreiving user Settings !' );
        }
    });

}

function initSearchSelectBox( functionToCall ){
    // Ask datas for select box from server
    $.ajax({
        type: "POST",
        url: window.JSON_URLS.bs_idp_archive_form_initlists,
        data: null,
        cache: false,
        success: function( data ) {
            updateSearchLists( data );
            initSearchTable( functionToCall );
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $('#ajaxError').html( $_searchTranslations[35] );
            $('#ajaxError').removeClass('hidden');
        }
    });
}

function initSearchSlidersAndTooltips( ){

    $anneeMin = 1900;
    $anneeMax = 2199;
    /*
    if( $_currentPage == PAGE_DELETE ) {
        var $now = new Date();
        $anneeMax = parseInt( $now.getFullYear() );
    }
    */

    // Closure min, max, from, to / destruction min, max, from, to
    setSliders( $anneeMin, $anneeMax, $anneeMin, $anneeMax, $anneeMin, $anneeMax, $anneeMin, $anneeMax );

    $('#limitdate').datepicker({'format': 'dd/mm/yyyy'});

    // Activate tooltip on buttons
    $('[data-toggle="tooltip"]').tooltip();

    // Set unlimited select to "no matter"
    $('#unlimited').val(2);

}

function initSearch( $commonSettings ){
    initSearchSlidersAndTooltips();
    initSearchSelectBox( endInit );
    initSearchViewable( $commonSettings );
};

function endInit(){
    // See IDPArchiveFilter.js for details
    initFilters();

    // See IDPManageView.js for details
    initManageView();

    // Launch first search with default values
    doSearch();

    // Init a search tooltip
    $('#list input:text').tooltip({html: true, title: "La recherche s'effectue pour les champs : <b>Libellé</b>, <b>N° d'ordre</b>, <b>N° Conteneur</b>, <b>N° Boîte</b>, <b>N° Dossier</b>"});
}

$('#cancelSearchBtn').click( function( $event ){
    $event.preventDefault();

    // reset all fields to default values
    resetSearch();

    // And do the search
    doSearch();
})

function resetSearch( ){
    // Re-init service, normally all synchronized list will follow
    $('#service').val(-1);
    setLegalEntitySelectList();
    setDocumentNatureSelectList();
    setDocumentTypeSelectList();
    setDescription1SelectList();
    setDescription2SelectList();
    setBudgetCodeSelectList();
    setProviderSelectList();
    // Reste sliders
    $anneeMin = 1900;
    $anneeMax = 2199;
    /*
    if( $_currentPage == PAGE_DELETE ) {
        var $now = new Date();
        $anneeMax = parseInt( $now.getFullYear() );
    }
    */
    setSliders( $anneeMin, $anneeMax, $anneeMin, $anneeMax, $anneeMin, $anneeMax, $anneeMin, $anneeMax );
    // Reset all the other fields
    $('#documentnumber').val('');
    $('#boxnumber').val('');
    $('#containernumber').val('');
    $('#ordernumber').val('');
    $('#name').val('');
    $('#limitalpha').val('');
    $('#limitalphanum').val('');
    $('#limitdate').val('');
    $('#limitnum').val('');
    $('#unlimited').val(2);
}

$('#searchBtn').click(function( $event ){
    $event.preventDefault();

    // Set all filters to true
    setFullFilters();

    // Clear contextual text search
    $('#listsearchTable').bootstrapTable('resetSearch', '');

    // And do the search
    doSearch()
});

//................................................................
// Callbacks for bsTablePagination
function onPageSizeChange( newPageSize ){
    // For security when pageSize changes, reset pageOffset to 1
    $_lastPageOffset = 1;
    mainTablePageChange( $_lastPageOffset, newPageSize );
}
function onPageChange( newPage ){
    $_lastPageOffset = newPage;
    doSearch();
    resetMultipleSelect();
}
//................................................................

function doSearch( ){

    // TODO verify consistancy of inputs
    // limitnum should be a numeric value
    // limitdate should be a date with dd/mm/YYYY format

    $('#searchBtn').addClass( 'disabled' );
    $('#imgAjaxLoad').removeClass('hidden');
    $('#txtBtn').addClass('hidden');
    $('#ajaxInfo').addClass('hidden');
    $('#ajaxError').addClass('hidden');

    $filters = getFilters();

    $serviceSelected = $('#service option:selected').val() == -1 ? '' : $('#service option:selected').val();

    // Get back datas from inputs to feed search parameters
    $searchParameters = {
        'wheretosearch': $('#wheretosearch').val(),			// filter
        'callFrom': window.IDP_CONST.bs_idp_menu_activated,
        'service': $serviceSelected,
        'legalentity': $('#legalentity option:selected').val(),
        'description1': $('#description1 option:selected').val(),
        'description2': $('#description2 option:selected').val(),
        'name': $('#name').val(),
        'limitnum': $('#limitnum').val(),
        'limitalpha': $('#limitalpha').val(),
        'limitalphanum': $('#limitalphanum').val(),
        'limitdate': $('#limitdate').val(),
        'ordernumber': $('#ordernumber').val(),
        'budgetcode': $('#budgetcode option:selected').val(),
        'documentnature': $('#documentnature option:selected').val(),
        'documenttype': $('#documenttype option:selected').val(),
        'closureyear': $('#closureyear').prop("value"),
        'destructionyear': $('#destructionyear').prop("value"),
        'documentnumber': $('#documentnumber').val(),
        'boxnumber': $('#boxnumber').val(),
        'containernumber': $('#containernumber').val(),
        'provider': $('#provider option:selected').val(),
        'unlimited': $('#unlimited').val(),
        'special': $_table.bootstrapTable('getOptions').searchText,
        'pageOffset': $_lastPageOffset,
        'pageSize': $_lastPageSize,
        'sortAsc': $_lastSortOrder,
        'sortColumn': $_lastSortColumn
    };

    if( $filters ) {
//        Object.assign( $searchParameters, $filters );

        $searchParameters['filterstatus'] = $filters['filterstatus'];
        $searchParameters['filterwhere'] = $filters['filterwhere'];
        $searchParameters['filterwith'] = $filters['filterwith'];
        $searchParameters['filterlocalization'] = $filters['filterlocalization'];

    }

    // Send ajax request for results
    $.ajax({
        type: "GET",
        url: window.JSON_URLS.bs_idp_archive_search_ajax,
        data: $searchParameters,
        cache: false,
        success: function( data, textStatus, jqXHR ){
            $resultSearch = data['rows'];
            $('#listsearchTable').bootstrapTable('load', $resultSearch );

            $total_rows = parseInt( data['total'] );

            // Call the bsTable_Pagination object with accurate datas
            let $pagination = new bsTable_Pagination( $_lastPageSize, $total_rows, $_lastPageOffset, 'bsTablePagination' );
            $pagination.setEventCallbacks( 'on_page_size_change', onPageSizeChange );
            $pagination.setEventCallbacks( 'on_page_change', onPageChange );
            $pagination.render( );

/*
            if( jqXHR.status == 206 ){
                alert( "Attention seuls les 500 premiers résultats s'affichent." );
            }
*/
            $('#searchBtn').removeClass( 'disabled' );
            $('#imgAjaxLoad').addClass('hidden');
            $('#txtBtn').removeClass('hidden');

            $('#tabfilter').removeClass('active');
            $('#litabfilter').removeClass('active');
            $('#tabsearch').removeClass('active');
            $('#litabsearch').removeClass('active');
            $('#tabresults').addClass('active');
            $('#litabresults').addClass('active');
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $('#ajaxError').html( $_searchTranslations[35] );
            $('#ajaxError').removeClass('hidden');
        },
        timeout: 10000
    });
};

$('#service').change(function( event ){
    event.preventDefault();

    onModifyServiceSelect();
});
$('#documentnature').change(function( event ){
    event.preventDefault();

    onModifyDocumentNatureSelect();
});
$('#unlimited').change( function( event ){
    event.preventDefault();

    if( $('#unlimited').val() == 0 ) // Unlimited
        $( "#destructionyear-range" ).slider( "option", "disabled", true );
    else // limited or no matters
        $( "#destructionyear-range" ).slider( "option", "disabled", false );
});

$('#divPrintPartialList').click(function( event ){
    event.preventDefault();

    if( $('#listsearchTable').bootstrapTable('getSelections').length > 0 )
        printTable( true, $('#listsearchTable'), 2 );
});
$('#divPrintList').click(function( event ){
    event.preventDefault();

    //if( $('#listsearchTable').bootstrapTable('getSelections').length > 0 )
        printTable( false, $('#listsearchTable'), 2, window.IDP_CONST.bs_idp_current_page );
});

var $lastRowClicked = -1;
var $lastActionUsed = -1; // -1 = No action, 0 = Select, 1 = unselect
var $multipleCheckInProgress = false;
var $shiftIsPressed = false;
$(document).keydown(function( event ){
    //event.preventDefault();

    if(event.keyCode=="16")
        $shiftIsPressed = true;
});
$(document).keyup(function( event ){
    //event.preventDefault();

    if(event.keyCode=="16")
        $shiftIsPressed = false;
});
/* function to manage multiple select, ie ALT + check */
function manageMultipleSelectUnselect( rowIndex, action ){
    if( !$multipleCheckInProgress ) {
        if( $shiftIsPressed ) {
            if( $lastActionUsed == action ) { // i.e. 0=check, 1=uncheck
                if ($lastRowClicked == -1) {
                    $lastRowClicked = rowIndex;
                } else {
                    $multipleCheckInProgress = true; // to avoid interference of check event during ALT checking
                    if ($lastRowClicked <= rowIndex) {
                        var $minRow = $lastRowClicked;
                        var $maxRow = rowIndex;
                    } else {
                        var $minRow = rowIndex;
                        var $maxRow = $lastRowClicked;
                    }
                    if ($maxRow - $minRow >= 2) {
                        for (var $idxRow = $minRow + 1; $idxRow < $maxRow; $idxRow++)
                            if( action == 0 )
                                $('#listsearchTable').bootstrapTable('check', $idxRow);
                            else
                                $('#listsearchTable').bootstrapTable('uncheck', $idxRow);
                    }
                    $multipleCheckInProgress = false;
                }
            } else {
                $lastActionUsed = action;
                $lastRowClicked = rowIndex;
            }
        } else {
            $lastRowClicked = rowIndex;
            $lastActionUsed = action;
        }
    }
}
function getRowIndexFromCheckbox( checkbox ){
    var $BTOptions = $('#listsearchTable').bootstrapTable( 'getOptions' );
    if( $BTOptions.cardView )
        var $trRow = checkbox.parent().parent().parent(); // In cardViewMode
    else
        var $trRow = checkbox.parent().parent(); // In tableViewMode
    return $trRow.index();
}

function initSearchViewable( $commonSettings ){
    if( !$commonSettings ) return;

    // Budget Code
    if( !$commonSettings[1]['ACTIVATED'] ) {
        $('#divLblBudgetCode').addClass('hidden');
        $('#divSelectBudgetcode').addClass('hidden');
    } else {
        $('#divLblBudgetCode').removeClass('hidden');
        $('#divSelectBudgetcode').removeClass('hidden');
    }

    // Document Nature (ie Activity)
    if( !$commonSettings[2]['ACTIVATED'] ) {
        $('#divLblDocumentnature').addClass('hidden');
        $('#divSelectDocumentnature').addClass('hidden');
    } else {
        $('#divLblDocumentnature').removeClass('hidden');
        $('#divSelectDocumentnature').removeClass('hidden');
    }

    // Document Type
    if( !$commonSettings[3]['ACTIVATED'] ) {
        $('#divLblDocumenttype').addClass('hidden');
        $('#divSelectDocumenttype').addClass('hidden');
    } else {
        $('#divLblDocumenttype').removeClass('hidden');
        $('#divSelectDocumenttype').removeClass('hidden');
    }

    // Description1
    if( !$commonSettings[4]['ACTIVATED'] ) {
        $('#divLblDescription1').addClass('hidden');
        $('#divSelectDescription1').addClass('hidden');
    } else {
        $('#divLblDescription1').removeClass('hidden');
        $('#divSelectDescription1').removeClass('hidden');
    }

    // Description2
    if( !$commonSettings[5]['ACTIVATED'] ) {
        $('#divLblDescription2').addClass('hidden');
        $('#divSelectDescription2').addClass('hidden');
    } else {
        $('#divLblDescription2').removeClass('hidden');
        $('#divSelectDescription2').removeClass('hidden');
    }

    if(( $commonSettings[2]['ACTIVATED'] )||( $commonSettings[3]['ACTIVATED'] )||( $commonSettings[4]['ACTIVATED'] )||( $commonSettings[5]['ACTIVATED'] )) {
        $('#divBlockInformation').removeClass( 'hidden' );
    } else {
        $('#divBlockInformation').addClass( 'hidden' );
    }

    // Document / File Number
    if( !$commonSettings[6]['ACTIVATED'] ) {
        $('#divLblFilenumber').addClass('hidden');
        $('#divInputFilenumber').addClass('hidden');
    } else {
        $('#divLblFilenumber').removeClass('hidden');
        $('#divInputFilenumber').removeClass('hidden');
    }

    // Box Number
    if( !$commonSettings[7]['ACTIVATED'] ) {
        $('#divLblBoxnumber').addClass('hidden');
        $('#divInputBoxnumber').addClass('hidden');
    } else {
        $('#divLblBoxnumber').removeClass('hidden');
        $('#divInputBoxnumber').removeClass('hidden');
    }

    // Container Number
    if( !$commonSettings[8]['ACTIVATED'] ) {
        $('#divLblContainernumber').addClass('hidden');
        $('#divInputContainernumber').addClass('hidden');
    } else {
        $('#divLblContainernumber').removeClass('hidden');
        $('#divInputContainernumber').removeClass('hidden');
    }

    // Provider
    if( !$commonSettings[9]['ACTIVATED'] ) {
        $('#divLblProvider').addClass('hidden');
        $('#divSelectProvider').addClass('hidden');
    } else {
        $('#divLblProvider').removeClass('hidden');
        $('#divSelectProvider').removeClass('hidden');
    }

    if(( $commonSettings[6]['ACTIVATED'] )||( $commonSettings[7]['ACTIVATED'] )||( $commonSettings[8]['ACTIVATED'] )||( $commonSettings[9]['ACTIVATED'] )) {
        $('#divBlockProviderDatas').removeClass( 'hidden' );
    } else {
        $('#divBlockProviderDatas').addClass( 'hidden' );
    }

    // Limits date
    if( !$commonSettings[10]['ACTIVATED'] ) {
        $('#divLblLimitsdate').addClass('hidden');
        $('#divInputLimitsdate').addClass('hidden');
    } else {
        $('#divLblLimitsdate').removeClass('hidden');
        $('#divInputLimitsdate').removeClass('hidden');
    }

    // Limits numeric
    if( !$commonSettings[11]['ACTIVATED'] ) {
        $('#divLblLimitsnum').addClass('hidden');
        $('#divInputLimitsnum').addClass('hidden');
    } else {
        $('#divLblLimitsnum').removeClass('hidden');
        $('#divInputLimitsnum').removeClass('hidden');
    }

    // Limits alphabetic
    if( !$commonSettings[12]['ACTIVATED'] ) {
        $('#divLblLimitsalpha').addClass('hidden');
        $('#divInputLimitsalpha').addClass('hidden');
    } else {
        $('#divLblLimitsalpha').removeClass('hidden');
        $('#divInputLimitsalpha').removeClass('hidden');
    }

    // Limits alphanumeric
    if( !$commonSettings[13]['ACTIVATED'] ) {
        $('#divLblLimitsalphanum').addClass('hidden');
        $('#divInputLimitsalphanum').addClass('hidden');
    } else {
        $('#divLblLimitsalphanum').removeClass('hidden');
        $('#divInputLimitsalphanum').removeClass('hidden');
    }

    if(( $commonSettings[10]['ACTIVATED'] )||( $commonSettings[11]['ACTIVATED'] )||( $commonSettings[12]['ACTIVATED'] )||( $commonSettings[13]['ACTIVATED'] )) {
        $('#divBlockLimits').removeClass( 'hidden' );
    } else {
        $('#divBlockLimits').addClass( 'hidden' );
    }
}