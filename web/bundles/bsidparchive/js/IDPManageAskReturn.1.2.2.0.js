// ------------------------------------------------------------------------------------------------------------------
// Definitions
// ------------------------------------------------------------------------------------------------------------------
// Basket lists
var $listToReturn = [];
var $IDlistToReturn = [];

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
var $_currentPage = PAGE_RETURN;
var $_currentButtons = 12;
var $_currentFCT = FCT_RETURN;
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

    // Initialization of dropdow list with Service dependance
    initLists();

	// Activate Datepicker on both Date fields
	$('#frm_limitdatemin').datepicker({'format': 'dd/mm/yyyy'});
	$('#frm_limitdatemax').datepicker({'format': 'dd/mm/yyyy'});

    // Hide unused buttons
    $('#divPrintTags').hide();
    $('#divDeleteUAs').hide();
    $('#divSetUnlimited').hide();
    $('#divUnsetUnlimited').hide();

    // Hide unused tabMenu
    $('#litabsearch').hide();
    $('#litabfilter').hide();
});

// ------------------------------------------------------------------------------------------------------------------
// Basket Addition / Removal Management
// ------------------------------------------------------------------------------------------------------------------
$('#addToReturn').click( function( event ){
    event.preventDefault();
    if( !$( '#addToReturn' ).hasClass( "disabled" ) ){
        onclickAddToBasket( $listToReturn, $IDlistToReturn, $('#table-return') );
        $('#listsearchTable').bootstrapTable('load', $resultSearch );
    }
});

$('#clearReturn').click( function( event ){
    event.preventDefault();
    if( !$('#clearReturn').hasClass("disabled")) {
        $listToReturn = [];
        $IDlistToReturn = [];
        onClickClearBasket( $('#table-return') );
    }
});

// ------------------------------------------------------------------------------------------------------------------
// Basket Events
// ------------------------------------------------------------------------------------------------------------------
window.operateReturnEvents = {
	'click .remove': function( e, value, row, index ){
		$listToReturn.splice( index, 1 );
		$IDlistToReturn.splice( $IDlistToReturn.indexOf( row.id ), 1 );
		$('#listsearchTable').bootstrapTable('load', $resultSearch);
		$('#table-return').bootstrapTable('load', $listToReturn );
		verifyAndEnableDoItButton();
        verifyAndEnableEmptyBasketButton();
	}
};

// ------------------------------------------------------------------------------------------------------------------
// Basket Buttons Management
// ------------------------------------------------------------------------------------------------------------------
function enableAddBasketButton(){
    $('#addToReturn').removeClass('disabled');
}

function updateBtnActionCancelState() {
}

function verifyAndDisableAddButtons() {
	$selections = $('#listsearchTable').bootstrapTable('getSelections');
	if( $selections.length <= 0 ){
		$('#addToReturn').addClass('disabled');
	}
}

function verifyAndEnableDoItButton() {
	if( $listToReturn.length > 0 ){
		$('#btnAskReturn').removeClass( 'disabled' );
	} else {
		$('#btnAskReturn').addClass( 'disabled' );
	}
}

function verifyAndEnableEmptyBasketButton() {
    if( $listToReturn.length > 0 ){
        $('#clearReturn').removeClass( 'disabled' );
    } else {
        $('#clearReturn').addClass( 'disabled' );
    }
}

$('#btnAskReturn').click(function( event ){
    event.preventDefault();

    if( $listToReturn.length > 0 ) {
        $archiveids = {
            return: $.map($listToReturn, function ($row) {
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
	$inActionList = $IDlistToReturn.indexOf( row['id'] ) + 1;

	return ( $inActionList > 0 )
}

function insideRowStyle( row, index ){
	$inActionList = $IDlistToReturn.indexOf( row['id'] ) + 1;

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
    $('#table-return').bootstrapTable({
        data: $listToReturn,
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
            { field: 'name', title: $_translations[10], sortable: true, visible: false },
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
            { field: 'operate', formatter: 'operateFormatter', events: 'operateReturnEvents', title: 'Action', align: 'center', width: '30' }
        ]
    });
}
