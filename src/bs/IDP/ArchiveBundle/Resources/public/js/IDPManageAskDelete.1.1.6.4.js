// ------------------------------------------------------------------------------------------------------------------
// Definitions
// ------------------------------------------------------------------------------------------------------------------
// Basket lists
var $listToDelete = [];
var $IDlistToDelete = [];

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
var $_currentPage = PAGE_DELETE;
var $_currentButtons = 12;
var $_currentFCT = FCT_DELETE;
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

	// Hide filter ConsultReturn
	$('#filterConsultReturn').hide();

    // Hide unused buttons
    $('#divPrintTags').hide();
    $('#divDeleteUAs').hide();
    $('#divSetUnlimited').hide();
    $('#divUnsetUnlimited').hide();
});

// ------------------------------------------------------------------------------------------------------------------
// Basket Addition / Removal Management
// ------------------------------------------------------------------------------------------------------------------
$('#addToDelete').click( function( event ){
    event.preventDefault();
    if( !$( '#addToDelete' ).hasClass( "disabled" ) ){
        onclickAddToBasket( $listToDelete, $IDlistToDelete, $('#table-delete') );
        $('#listsearchTable').bootstrapTable('load', $resultSearch );
    }
});

$('#clearDelete').click( function( event ){
    event.preventDefault();
    if( !$('#clearDelete').hasClass("disabled")) {
        $listToDelete = [];
        $IDlistToDelete = [];
        onClickClearBasket( $('#table-delete') );
    }
});

// ------------------------------------------------------------------------------------------------------------------
// Basket Events
// ------------------------------------------------------------------------------------------------------------------
window.operateDeleteEvents = {
	'click .remove': function( e, value, row, index ) {
        $listToDelete.splice(index, 1);
        $IDlistToDelete.splice($IDlistToDelete.indexOf(row.id), 1);
        $('#listsearchTable').bootstrapTable('load', $resultSearch);
        $('#table-delete').bootstrapTable('load', $listToDelete);
        verifyAndEnableDoItButton();
        verifyAndEnableEmptyBasketButton();
    }
};

// ------------------------------------------------------------------------------------------------------------------
// Basket Buttons Management
// ------------------------------------------------------------------------------------------------------------------
function enableAddBasketButton(){
    $('#addToDelete').removeClass('disabled');
}

function updateBtnActionCancelState() {
}

function verifyAndDisableAddButtons() {
	$selections = $('#listsearchTable').bootstrapTable('getSelections');
	if( $selections.length <= 0 ){
		$('#addToDelete').addClass('disabled');
	}
}

function verifyAndEnableDoItButton() {
	if( $listToDelete.length > 0 ){
		$('#btnAskDelete').removeClass( 'disabled' );
	} else {
		$('#btnAskDelete').addClass( 'disabled' );
	}
}

function verifyAndEnableEmptyBasketButton() {
    if( $listToDelete.length > 0 ){
        $('#clearDelete').removeClass( 'disabled' );
    } else {
        $('#clearDelete').addClass( 'disabled' );
    }
}


$('#btnAskDelete').click(function( event ){
    event.preventDefault();

    if( $listToDelete.length > 0 ) {
        $archiveids = {
            delete: $.map($listToDelete, function ($row) {
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
	$inActionList = $IDlistToDelete.indexOf( row['id'] ) + 1;

	return ( $inActionList > 0 )
}

function insideRowStyle( row, index ){
	$inActionList = $IDlistToDelete.indexOf( row['id'] ) + 1;

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
    $('#table-delete').bootstrapTable({
        data: $listToDelete,
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
            { field: 'name', title: $_translations[7], sortable: true, visible: false },
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
            { field: 'operate', formatter: 'operateFormatter', events: 'operateDeleteEvents', title: 'Action', align: 'center', width: '30' }
        ]
    });
}
