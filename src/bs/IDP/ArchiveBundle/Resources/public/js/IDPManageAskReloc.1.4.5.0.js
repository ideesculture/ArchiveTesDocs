// ------------------------------------------------------------------------------------------------------------------
// Definitions
// ------------------------------------------------------------------------------------------------------------------
// Basket lists
var $listToRelocInternal = [];
var $IDlistToRelocInternal = [];
var $listToRelocIntermediate = [];
var $IDlistToRelocIntermediate = [];
var $listToRelocProvider = [];
var $IDlistToRelocProvider = [];

// Settings
var $_commonsettings = null;
var $_settings = null;

// Translations
var $_translations = null;
var $_tabletranslation = null;
var $_resultTranslations = null;
var $_overlay = null;
var $_precisionTranslation = null;

// Current page & buttons
var $_currentPage = PAGE_RELOC;
var $_currentButtons = 12;
var $_currentFCT = FCT_RELOC;
var $_mainTable = $('#listsearchTable');

// ------------------------------------------------------------------------------------------------------------------
// Initialization
// ------------------------------------------------------------------------------------------------------------------
$(document).ready(function(){

    $_translations = JSON.parse( window.IDP_CONST.bs_translations );
    $_resultTranslations = JSON.parse( window.IDP_CONST.bs_resulttranslations );
    $_overlay = JSON.parse( window.IDP_CONST.bs_overlay );
    $_searchTranslations = JSON.parse( window.IDP_CONST.bs_searchtranslations );
    $_tabletranslation = JSON.parse( window.IDP_CONST.bs_tabletranslation );
    $_precisionTranslations = JSON.parse( window.IDP_CONST.bs_precisionTranslations );

    $_commonsettings = JSON.parse( window.IDP_CONST.bs_idp_commonsettings );

    // See IDPArchiveSearch.js for details
    initSearch( $_commonsettings );

    // Initialization of Baskets table name, depends on page
    initSideTables();

    // Since confModal is essentially a nested modal it's enforceFocus method
    // must be no-op'd or the following error results
    // "Uncaught RangeError: Maximum call stack size exceeded"
    // But then when the nested modal is hidden we reset modal.enforceFocus
    // Solution from: http://stackoverflow.com/questions/21059598/implementing-jquery-datepicker-in-bootstrap-modal
    var enforceModalFocusFn = $.fn.modal.Constructor.prototype.enforceFocus;
    $.fn.modal.Constructor.prototype.enforceFocus = function() {};

    // overlay
    initOverlay( );
    initOverlayLocalization( null );

    // Initialization of Baskets visibility, depends on Service configuration
    initBasketView();

    // Activate Datepicker on both Date fields
    $('#frm_limitdatemin').datepicker({'format': 'dd/mm/yyyy'});
    $('#frm_limitdatemax').datepicker({'format': 'dd/mm/yyyy'});

    // Hide unused buttons
    $('#divPrintTags').hide();
    $('#divDeleteUAs').hide();
    $('#divSetUnlimited').hide();
    $('#divUnsetUnlimited').hide();
});

// ------------------------------------------------------------------------------------------------------------------
// Basket Addition / Removal Management
// ------------------------------------------------------------------------------------------------------------------
$('#addToRelocInternal').click( function( event ){
    event.preventDefault();
    if( !$( '#addToRelocInternal' ).hasClass( "disabled" ) ){
        onclickAddToBasket( $listToRelocInternal, $IDlistToRelocInternal, $('#table-relocInternal') );
        $('#listsearchTable').bootstrapTable('load', $resultSearch );
    }
});

$('#addToRelocIntermediate').click( function( event ){
    event.preventDefault();
    if( !$( '#addToRelocIntermediate' ).hasClass( "disabled" ) ){
        onclickAddToBasket( $listToRelocIntermediate, $IDlistToRelocIntermediate, $('#table-relocIntermediate') );
        $('#listsearchTable').bootstrapTable('load', $resultSearch );
    }
});

$('#addToRelocProvider').click( function( event ){
    event.preventDefault();
    if( !$( '#addToRelocProvider' ).hasClass( "disabled" ) ){
        onclickAddToBasket( $listToRelocProvider, $IDlistToRelocProvider, $('#table-relocProvider') );
        $('#listsearchTable').bootstrapTable('load', $resultSearch );
    }
});

$('#clearRelocInternal').click( function( event ){
    event.preventDefault();
    if( !$('#clearRelocInternal').hasClass("disabled")) {
        $listToRelocInternal = [];
        $IDlistToRelocInternal = [];
        onClickClearBasket( $('#table-relocInternal') );
    }
});

$('#clearRelocIntermediate').click( function( event ){
    event.preventDefault();
    if( !$('#clearRelocIntermediate').hasClass("disabled")) {
        $listToRelocIntermediate = [];
        $IDlistToRelocIntermediate = [];
        onClickClearBasket( $('#table-relocIntermediate') );
    }
});

$('#clearRelocProvider').click( function( event ){
    event.preventDefault();
    if( !$('#clearRelocProvider').hasClass("disabled")) {
        $listToRelocProvider = [];
        $IDlistToRelocProvider = [];
        onClickClearBasket( $('#table-relocProvider') );
    }
});

// ------------------------------------------------------------------------------------------------------------------
// Basket Events
// ------------------------------------------------------------------------------------------------------------------
window.operateRelocInternalEvents = {
    'click .remove': function( e, value, row, index ){
        $listToRelocInternal.splice( index, 1 );
        $IDlistToRelocInternal.splice( $IDlistToRelocInternal.indexOf( row.id ), 1 );
        $('#listsearchTable').bootstrapTable('load', $resultSearch );
        $('#table-relocInternal').bootstrapTable('load', $listToRelocInternal );
        verifyAndEnableDoItButton();
        verifyAndEnableEmptyBasketButton();
    }
};
window.operateRelocIntermediateEvents = {
    'click .remove': function( e, value, row, index ){
        $listToRelocIntermediate.splice( index, 1 );
        $IDlistToRelocIntermediate.splice( $IDlistToRelocIntermediate.indexOf( row.id ), 1 );
        $('#listsearchTable').bootstrapTable('load', $resultSearch );
        $('#table-relocIntermediate').bootstrapTable('load', $listToRelocIntermediate );
        verifyAndEnableDoItButton();
        verifyAndEnableEmptyBasketButton();
    }
};
window.operateRelocProviderEvents = {
    'click .remove': function( e, value, row, index ){
        $listToRelocProvider.splice( index, 1 );
        $IDlistToRelocProvider.splice( $IDlistToRelocProvider.indexOf( row.id ), 1 );
        $('#listsearchTable').bootstrapTable('load', $resultSearch );
        $('#table-relocProvider').bootstrapTable('load', $listToRelocProvider );
        verifyAndEnableDoItButton();
        verifyAndEnableEmptyBasketButton();
    }
};

// ------------------------------------------------------------------------------------------------------------------
// Basket Buttons Management
// ------------------------------------------------------------------------------------------------------------------
function enableAddBasketButton(){
    $('#addToRelocInternal').removeClass('disabled');
    $('#addToRelocIntermediate').removeClass('disabled');
    $('#addToRelocProvider').removeClass('disabled');
}

function updateBtnActionCancelState() {
}

function verifyAndDisableAddButtons() {
    $selections = $('#listsearchTable').bootstrapTable('getSelections');
    if( $selections.length <= 0 ){
        $('#addToRelocInternal').addClass('disabled');
        $('#addToRelocIntermediate').addClass('disabled');
        $('#addToRelocProvider').addClass('disabled');
        return;
    }
    $internal = false;
    $intermediate = false;
    $provider = false;
    $selections.forEach(function($currentItem){
        /* All archives can be relocated to Internal since 0.8.0
        if( $currentItem.statuscaps == 'DISI' || $currentItem.statuscaps == 'CONI' )
            $internal = true;
        */
        /* All archives can be relocated to Intermediate since 0.8.0
        if( $currentItem.statuscaps == 'DISINT' || $currentItem.statuscaps == 'CONINT' )
            $intermediate = true;
        */
        if( $currentItem.statuscaps == 'DISP' || $currentItem.statuscaps == 'CONI' ||
            $currentItem.statuscaps == 'CONINT' || $currentItem.statuscaps == 'CONP' )
            $provider = true;
    });
    if( $internal ) $('#addToRelocInternal').addClass('disabled');
    if( $intermediate ) $('#addToRelocIntermediate').addClass('disabled');
    if( $provider ) $('#addToRelocProvider').addClass('disabled');
}

function verifyAndEnableDoItButton() {
    if( $listToRelocInternal.length > 0 || $listToRelocIntermediate.length > 0 || $listToRelocProvider.length > 0 ){
        $('#btnAskReloc').removeClass( 'disabled' );
    } else {
        $('#btnAskReloc').addClass( 'disabled' );
    }
}

function verifyAndEnableEmptyBasketButton() {
    if( $listToRelocInternal.length > 0 ){
        $('#clearRelocInternal').removeClass( 'disabled' );
    } else {
        $('#clearRelocInternal').addClass( 'disabled' );
    }
    if( $listToRelocIntermediate.length > 0 ){
        $('#clearRelocIntermediate').removeClass( 'disabled' );
    } else {
        $('#clearRelocIntermediate').addClass( 'disabled' );
    }
    if( $listToRelocProvider.length > 0 ){
        $('#clearRelocProvider').removeClass( 'disabled' );
    } else {
        $('#clearRelocProvider').addClass( 'disabled' );
    }
}

$('#btnAskReloc').click(function( event ){
    event.preventDefault();

    if( $listToRelocInternal.length > 0 || $listToRelocIntermediate.length > 0 || $listToRelocProvider.length > 0 ) {
        $archiveids = {
            internal: $.map($listToRelocInternal, function ($row) {
                return $row.id;
            }),
            intermediate: $.map($listToRelocIntermediate, function ($row) {
                return $row.id;
            }),
            provider: $.map($listToRelocProvider, function ($row) {
                return $row.id;
            })
        };
        $('#form_ids').val(JSON.stringify($archiveids));

        $('#actionModalDialog').modal('show');
    }
});

// ------------------------------------------------------------------------------------------------------------------
// Main Table design
// ------------------------------------------------------------------------------------------------------------------
function insideStateFormatter( value, row, index ){
    $inActionList = $IDlistToRelocInternal.indexOf( row['id'] ) + $IDlistToRelocIntermediate.indexOf( row['id'] ) +  $IDlistToRelocProvider.indexOf( row['id'] ) + 3;

    return ( $inActionList > 0 )
}

function insideRowStyle( row, index ){
    $inActionList = $IDlistToRelocInternal.indexOf( row['id'] ) + $IDlistToRelocIntermediate.indexOf( row['id'] ) +  $IDlistToRelocProvider.indexOf( row['id'] ) + 3 ;

    return ( $inActionList > 0 )
}

// ------------------------------------------------------------------------------------------------------------------
// Main Table Special functions
// ------------------------------------------------------------------------------------------------------------------
function postQueryParams( params ) {
    return params;
}

// ------------------------------------------------------------------------------------------------------------------
// Basket Table initialization
// ------------------------------------------------------------------------------------------------------------------
function initSideTables(){
    $('#table-relocInternal').bootstrapTable({
        data: $listToRelocInternal,
        sortName: "name",
        sortOrder: "asc",
        pagination: false,
        undefinedText: "aucune archive",
        showHeader: false,
        height: 100,
        columns: [
            { field: 'service', title: 'Service', sortable: true, visible: false },
            { field: 'ordernumber', title: 'N° d\'ordre', sortable: true, visible: true },
            { field: 'legalentity', title: 'Entité légale', sortable: true, visible: false },
            { field: 'name', title: $_translations[11], sortable: true, visible: false },
            { field: 'id', title: 'ID', visible: false },
            { field: 'documentnature', title: 'Nature de document', visible: false },
            { field: 'documenttype', title: 'Type de document', visible: false },
            { field: 'description1', title: 'Descriptif 1', visible: false },
            { field: 'description2', title: 'Descriptif 2', visible: false },
            { field: 'budgetcode', title: 'Code budgétaire', visible: false },
            { field: 'documentnumber', title: 'N° document', visible: false },
            { field: 'boxnumber', title: 'N° de boîte', visible: false },
            { field: 'containernumber', title: 'N° conteneur', visible: false },
            { field: 'provider', title: 'Prestataire', visible: false },
            { field: 'limitdatemin', title: 'Date min', visible: false },
            { field: 'limitdatemax', title: 'datemax', visible: false },
            { field: 'limitnummin', title: 'Num. min', visible: false },
            { field: 'limitnummax', title: 'Num. max', visible: false },
            { field: 'limitalphamin', title: 'Alpha. min', visible: false },
            { field: 'limitalphamax', title: 'Alpha max', visible: false },
            { field: 'limitalphanummin', title: 'Alphanum. min', visible: false },
            { field: 'limitalphanummax', title: 'Alphanum. max', visible: false },
            { field: 'operate', formatter: 'operateFormatter', events: 'operateRelocInternalEvents', title: 'Action', align: 'center', width: '30' }
        ]
    });
    $('#table-relocIntermediate').bootstrapTable({
        data: $listToRelocIntermediate,
        sortName: "name",
        sortOrder: "asc",
        pagination: false,
        undefinedText: "aucune archive",
        showHeader: false,
        height: 100,
        columns: [
            { field: 'service', title: 'Service', sortable: true, visible: false },
            { field: 'ordernumber', title: 'N° d\'ordre', sortable: true, visible: true },
            { field: 'legalentity', title: 'Entité légale', sortable: true, visible: false },
            { field: 'name', title: $_translations[11], sortable: true, visible: false },
            { field: 'id', title: 'ID', visible: false },
            { field: 'documentnature', title: 'Nature de document', visible: false },
            { field: 'documenttype', title: 'Type de document', visible: false },
            { field: 'description1', title: 'Descriptif 1', visible: false },
            { field: 'description2', title: 'Descriptif 2', visible: false },
            { field: 'budgetcode', title: 'Code budgétaire', visible: false },
            { field: 'documentnumber', title: 'N° document', visible: false },
            { field: 'boxnumber', title: 'N° de boîte', visible: false },
            { field: 'containernumber', title: 'N° conteneur', visible: false },
            { field: 'provider', title: 'Prestataire', visible: false },
            { field: 'limitdatemin', title: 'Date min', visible: false },
            { field: 'limitdatemax', title: 'datemax', visible: false },
            { field: 'limitnummin', title: 'Num. min', visible: false },
            { field: 'limitnummax', title: 'Num. max', visible: false },
            { field: 'limitalphamin', title: 'Alpha. min', visible: false },
            { field: 'limitalphamax', title: 'Alpha max', visible: false },
            { field: 'limitalphanummin', title: 'Alphanum. min', visible: false },
            { field: 'limitalphanummax', title: 'Alphanum. max', visible: false },
            { field: 'operate', formatter: 'operateFormatter', events: 'operateRelocIntermediateEvents', title: 'Action', align: 'center', width: '30' }
        ]
    });
    $('#table-relocProvider').bootstrapTable({
        data: $listToRelocProvider,
        sortName: "name",
        sortOrder: "asc",
        pagination: false,
        undefinedText: "aucune archive",
        showHeader: false,
        height: 100,
        columns: [
            { field: 'service', title: 'Service', sortable: true, visible: false },
            { field: 'ordernumber', title: 'N° d\'ordre', sortable: true, visible: true },
            { field: 'legalentity', title: 'Entité légale', sortable: true, visible: false },
            { field: 'name', title: $_translations[11], sortable: true, visible: false },
            { field: 'id', title: 'ID', visible: false },
            { field: 'documentnature', title: 'Nature de document', visible: false },
            { field: 'documenttype', title: 'Type de document', visible: false },
            { field: 'description1', title: 'Descriptif 1', visible: false },
            { field: 'description2', title: 'Descriptif 2', visible: false },
            { field: 'budgetcode', title: 'Code budgétaire', visible: false },
            { field: 'documentnumber', title: 'N° document', visible: false },
            { field: 'boxnumber', title: 'N° de boîte', visible: false },
            { field: 'containernumber', title: 'N° conteneur', visible: false },
            { field: 'provider', title: 'Prestataire', visible: false },
            { field: 'limitdatemin', title: 'Date min', visible: false },
            { field: 'limitdatemax', title: 'datemax', visible: false },
            { field: 'limitnummin', title: 'Num. min', visible: false },
            { field: 'limitnummax', title: 'Num. max', visible: false },
            { field: 'limitalphamin', title: 'Alpha. min', visible: false },
            { field: 'limitalphamax', title: 'Alpha max', visible: false },
            { field: 'limitalphanummin', title: 'Alphanum. min', visible: false },
            { field: 'limitalphanummax', title: 'Alphanum. max', visible: false },
            { field: 'operate', formatter: 'operateFormatter', events: 'operateRelocProviderEvents', title: 'Action', align: 'center', width: '30' }
        ]
    });
}

function initBasketView(){
    // Init baskets view
    $.ajax({
        type: 'GET',
        url: window.JSON_URLS.bs_idp_backoffice_basket_settings,
        data: null,
        cache: false,
        success: function( $response ){
            // B#75: ask to be same config as trasnfer page. Didn't delete _reloc_ params in case of ...
            if( $response['view_transfer_internal_basket'] == 1 )
                $('#askRelocInternal').removeClass( 'hidden' );
            if( $response['view_transfer_intermediate_basket'] == 1 )
                $('#askRelocIntermediate').removeClass( 'hidden' );
            if( $response['view_transfer_provider_basket'] == 1 )
                $('#askRelocProvider').removeClass( 'hidden' );
        },
        error: function( xhr, ajaxOptions, thrownError ){
            bootbox.alert({
                message: 'Error while retreiving basket configuration !',
                className: 'boxSysErrorOne' });
        }
    });

}