// ------------------------------------------------------------------------------------------------------------------
// Definitions
// ------------------------------------------------------------------------------------------------------------------
// Basket lists
var $DTAListToService = [];
var $IDListToService = [];
var $DTAListToIntermediate = [];
var $IDListToIntermediate = [];
var $DTAListToProvider = [];
var $IDListToProvider = [];

// Settings
var $_commonsettings = null;
var $_settings = null;

// Translations
var $_translations = null;
var $_tabletranslation = null;
var $_resultTranslations = null;
var $_overlay = null;

// Current page & buttons
var $_currentPage = PAGE_TRANSFER;
var $_currentButtons = 29;
var $_currentFCT = FCT_TRANSFER;
var $_mainTable = $('#listsearchTable');

// ------------------------------------------------------------------------------------------------------------------
// Initialization
// ------------------------------------------------------------------------------------------------------------------
$(document).ready(function(){

	$_translations = JSON.parse( window.IDP_CONST.bs_translations );
	$_resultTranslations = JSON.parse( window.IDP_CONST.bs_resulttranslations );
    $_overlay = JSON.parse( window.IDP_CONST.bs_overlay );
    $_searchTranslations = JSON.parse( window.IDP_CONST.bs_resulttranslations );
    $_tabletranslation = JSON.parse( window.IDP_CONST.bs_tabletranslation );
    $_precisionTranslations = JSON.parse( window.IDP_CONST.bs_precisionTranslations );

    $_commonsettings = JSON.parse( window.IDP_CONST.bs_idp_commonsettings );

    // See initSearch.js for details
	initSearch( $_commonsettings );

	// Initialization of Baskets table name, depends on page
    initSideTables( );

	// Since confModal is essentially a nested modal it's enforceFocus method
	// must be no-op'd or the following error results
	// "Uncaught RangeError: Maximum call stack size exceeded"
	// But then when the nested modal is hidden we reset modal.enforceFocus
	// Solution from: http://stackoverflow.com/questions/21059598/implementing-jquery-datepicker-in-bootstrap-modal
	var enforceModalFocusFn = $.fn.modal.Constructor.prototype.enforceFocus;
	$.fn.modal.Constructor.prototype.enforceFocus = function() {};

    // Init overlay View
    initOverlay();
    initOverlayLocalization( null );

    // Initialization of dropdow list with Service dependance
    initLists();

    // Initialization of Baskets visibility, depends on Service configuration
    initBasketView();

    // Activate Datepicker on both Date fields
    $('#frm_limitdatemin').datepicker( {'format': 'dd/mm/yyyy', 'autoclose': true })
        .on( 'changeDate', function( event ){ $('#frm_limitdatemin').datepicker( 'hide' ); });
    $('#frm_limitdatemax').datepicker({ 'format': 'dd/mm/yyyy', 'autoclose': true })
        .on( 'changeDate', function( event ){ $('#frm_limitdatemax').datepicker( 'hide' ); });

    // Activate tooltip on buttons
    $('[data-toggle="tooltip"]').tooltip();

    // Hide unused Buttons
    $('#divSetUnlimited').hide();
    $('#divUnsetUnlimited').hide();

    // Hide unused tabMenu
    $('#litabsearch').hide();
    $('#litabfilter').hide();
});

// ------------------------------------------------------------------------------------------------------------------
// Basket Addition / Removal Management
// ------------------------------------------------------------------------------------------------------------------
$('#addToService').click( function( event ){
    event.preventDefault();
    if( !$( '#addToService' ).hasClass( "disabled" ) ){
        onclickAddToBasket( $DTAListToService, $IDListToService, $('#table-transfer-service') );
        $('#listsearchTable').bootstrapTable('load', $resultSearch );
    }
});

$('#addToIntermediate').click(function( event ){
    event.preventDefault();
    if( !$( '#addToIntermediate' ).hasClass( "disabled" ) ){
        onclickAddToBasket( $DTAListToIntermediate, $IDListToIntermediate, $('#table-transfer-intermediate') );
        $('#listsearchTable').bootstrapTable('load', $resultSearch );
    }
});

$('#addToProvider').click(function( event ){
    event.preventDefault();
    if( !$( '#addToProvider' ).hasClass( "disabled" ) ){
        onclickAddToBasket( $DTAListToProvider, $IDListToProvider, $('#table-transfer-provider') );
        $('#listsearchTable').bootstrapTable('load', $resultSearch );
    }
});

$('#clearService').click( function( event ){
    event.preventDefault();
    if( !$('#clearService').hasClass("disabled")) {
        $DTAListToService = [];
        $IDListToService = [];
        onClickClearBasket( $('#table-transfer-service') );
    }
});

$('#clearIntermediate').click( function( event ){
    event.preventDefault();
    if( !$('#clearIntermediate').hasClass("disabled")) {
        $DTAListToIntermediate = [];
        $IDListToIntermediate = [];
        onClickClearBasket($('#table-transfer-intermediate'));
    }
});

$('#clearProvider').click( function( event ){
    event.preventDefault();
    if( !$('#clearProvider').hasClass("disabled")) {
        $DTAListToProvider = [];
        $IDListToProvider = [];
        onClickClearBasket($('#table-transfer-provider'));
    }
});

// ------------------------------------------------------------------------------------------------------------------
// Basket Events
// ------------------------------------------------------------------------------------------------------------------
window.operateServiceEvents = {
    'click .remove': function( e, value, row, index ){
        $DTAListToService.splice( index, 1 );
        $('#listsearchTable').bootstrapTable('uncheckAll');
        $IDListToService.splice( $IDListToService.indexOf( row.id ), 1 );
        $('#table-transfer-service').bootstrapTable('load', $DTAListToService );
        verifyAndEnableDoItButton();
        verifyAndEnableEmptyBasketButton();
        $('#listsearchTable').bootstrapTable('load', $resultSearch );
    }
};
window.operateIntermediateEvents = {
    'click .remove': function( e, value, row, index ){
        $DTAListToIntermediate.splice( index, 1 );
        $('#listsearchTable').bootstrapTable('uncheckAll');
        $IDListToIntermediate.splice( $IDListToIntermediate.indexOf( row.id ), 1 );
        $('#table-transfer-intermediate').bootstrapTable('load', $DTAListToIntermediate );
        verifyAndEnableDoItButton();
        verifyAndEnableEmptyBasketButton();
        $('#listsearchTable').bootstrapTable('load', $resultSearch );
    }
};
window.operateProviderEvents = {
    'click .remove': function( e, value, row, index ){
        $DTAListToProvider.splice( index, 1 );
        $('#listsearchTable').bootstrapTable('uncheckAll');
        $IDListToProvider.splice( $IDListToProvider.indexOf( row.id ), 1 );
        $('#table-transfer-provider').bootstrapTable('load', $DTAListToProvider );
        verifyAndEnableDoItButton();
        verifyAndEnableEmptyBasketButton();
        $('#listsearchTable').bootstrapTable('load', $resultSearch );
    }
};

// ------------------------------------------------------------------------------------------------------------------
// Basket Buttons Management
// ------------------------------------------------------------------------------------------------------------------
function enableAddBasketButton(){
    $('#addToService').removeClass('disabled');
    $('#addToIntermediate').removeClass('disabled');
    $('#addToProvider').removeClass('disabled')
}

function updateBtnActionCancelState() {
}

function verifyAndDisableAddButtons() {
	$selections = $('#listsearchTable').bootstrapTable('getSelections');
	if( $selections.length <= 0 ){
		$('#addToService').addClass('disabled');
		$('#addToIntermediate').addClass('disabled');
		$('#addToProvider').addClass('disabled')
	}
}

function verifyAndEnableDoItButton() {
	if( $DTAListToService.length > 0 || $DTAListToIntermediate.length > 0 || $DTAListToProvider.length > 0 ){
		$('#btnTransfer').removeClass( 'disabled' );
	} else {
		$('#btnTransfer').addClass( 'disabled' );
	}
}
function verifyAndEnableEmptyBasketButton() {
    if( $DTAListToService.length > 0 ){
        $('#clearService').removeClass( 'disabled' );
    } else {
        $('#clearService').addClass( 'disabled' );
    }
	if( $DTAListToIntermediate.length > 0 ) {
        $('#clearIntermediate').removeClass( 'disabled' );
	} else {
		$('#clearIntermediate').addClass( 'disabled' );
	}
	if( $DTAListToProvider.length > 0 ) {
        $('#clearProvider').removeClass( 'disabled' );
    } else {
        $('#clearProvider').addClass( 'disabled' );
    }
}

$('#btnTransfer').click(function( event ){
    event.preventDefault();

    if( $DTAListToService.length > 0 || $DTAListToIntermediate.length > 0 || $DTAListToProvider.length > 0 ) {

        $archiveids = {
            service: $.map($DTAListToService, function ($row) {
                return $row.id;
            }),
            intermediate: $.map($DTAListToIntermediate, function ($row) {
                return $row.id;
            }),
            provider: $.map($DTAListToProvider, function ($row) {
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
    $inActionList = $IDListToProvider.indexOf( row['id'] ) + $IDListToIntermediate.indexOf( row['id'] ) + $IDListToService.indexOf( row['id'] ) + 3;

    return ( $inActionList > 0 )
}

function insideRowStyle( row, index ){
    $inActionList = $IDListToProvider.indexOf( row['id'] ) + $IDListToIntermediate.indexOf( row['id'] ) + $IDListToService.indexOf( row['id'] ) + 3;

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
function initSideTables( ){
    $('#table-transfer-service').bootstrapTable({
        data: $DTAListToIntermediate,
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
            { field: 'name', title: $_translations[9], sortable: true, visible: false },
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
            { field: 'operate', formatter: 'operateFormatter', events: 'operateServiceEvents', title: 'Action', align: 'center', width: '30' }
        ]
    });
    $('#table-transfer-intermediate').bootstrapTable({
        data: $DTAListToProvider,
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
            { field: 'name', title: $_translations[9], sortable: true, visible: false },
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
            { field: 'operate', formatter: 'operateFormatter', events: 'operateIntermediateEvents', title: 'Action', align: 'center', width: '30' }
        ]
    });
    $('#table-transfer-provider').bootstrapTable({
        data: $DTAListToService,
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
            { field: 'name', title: $_translations[9], sortable: true, visible: false },
            { field: 'id', title: 'ID', visible: false, switchable: false },
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
            { field: 'operate', formatter: 'operateFormatter', events: 'operateProviderEvents', title: 'Action', align: 'center', width: '30' }
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
            if( $response['view_transfer_internal_basket'] == 1 )
                $('#transferservice').removeClass( 'hidden' );
            if( $response['view_transfer_intermediate_basket'] == 1 )
                $('#transferintermediaire').removeClass( 'hidden' );
            if( $response['view_transfer_provider_basket'] == 1 )
                $('#transferprestataire').removeClass( 'hidden' );
        },
        error: function( xhr, ajaxOptions, thrownError ){
            bootbox.alert( {
                message: 'Error while retreiving basket configuration !',
                className: "boxSysErrorOne"
            } );
        }
    });
}




/*
String.format = function() {
    // The string containing the format items (e.g. "{0}")
    // will and always has to be the first argument.
    var theString = arguments[0];

    // start with the second argument (i = 1)
    for (var i = 1; i < arguments.length; i++) {
        // "gm" = RegEx options for Global search (more than one instance)
        // and for Multiline search
        var regEx = new RegExp("\\{" + (i - 1) + "\\}", "gm");
        theString = theString.replace(regEx, arguments[i]);
    }

    return theString;
}
*/