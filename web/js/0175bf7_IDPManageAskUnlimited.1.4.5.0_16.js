// ------------------------------------------------------------------------------------------------------------------
// Definitions
// ------------------------------------------------------------------------------------------------------------------
// Settings
var $_commonsettings = null;
var $_settings = null;

// Translations
var $_translations = null;
var $_resultTranslations = null;
var $_overlay = null;

// Current page & buttons
var $_currentPage = PAGE_UNLIMITED;
var $_currentButtons = 12;
var $_currentFCT = FCT_ARCHIVIST;
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

    $_commonsettings = JSON.parse( window.IDP_CONST.bs_idp_commonsettings );

    // See IDPArchiveSearch.js for details
    initSearch( $_commonsettings );

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

    // Activate Datepicker on both Date fields
    $('#frm_limitdatemin').datepicker({'format': 'dd/mm/yyyy'});
    $('#frm_limitdatemax').datepicker({'format': 'dd/mm/yyyy'});

    // Hide unused buttons
    $('#divPrintTags').hide();
    $('#divDeleteUAs').hide();
});

// ------------------------------------------------------------------------------------------------------------------
// Main Table design
// ------------------------------------------------------------------------------------------------------------------
function insideStateFormatter( value, row, index ){
    return false;
}

function insideRowStyle( row, index ){
    if( row['unlimited'] == 'actif' ) return true;
    return false;
}

// ------------------------------------------------------------------------------------------------------------------
// Main Table Special functions
// ------------------------------------------------------------------------------------------------------------------
function postQueryParams( params ) {
    return params;
}

// ------------------------------------------------------------------------------------------------------------------
// Basket Buttons Management ==> No Basket
// ------------------------------------------------------------------------------------------------------------------
function enableAddBasketButton(){
}

function updateBtnActionCancelState() {
}

function verifyAndDisableAddButtons() {
}

function verifyAndEnableDoItButton() {
}

// ------------------------------------------------------------------------------------------------------------------
// Unlimited management
// ------------------------------------------------------------------------------------------------------------------
$('#divSetUnlimited').click( function( event ){
    event.preventDefault();

    if( getIdSelected(  $('#listsearchTable' ) ) === null ){
        bootbox.alert( {
            message: $_translations[4],
            className: "boxSysErrorOne"
        } );
    } else {
        $('#CommentsUnlimitedText').val('');

        $('#CommentsUnlimitedModal').modal( 'show' );
        $('#CommentsUnlimitedText').focus();
    }
});

$('#CommentsUnlimitedValidate').click( function( event ){
    event.preventDefault();

    $('#CommentsUnlimitedModal').modal( 'hide' );
    $('#waitAjax').show();

    $archiveIdsList = getIdSelected( $('#listsearchTable') );

    $dataObject = {
        'idlist': $archiveIdsList,
        'unlimited': 1,
        'comments': $('#CommentsUnlimitedText').val()
    };

    $.ajax({
        type: "GET",
        url: window.JSON_URLS.bs_idp_archivist_json_update_unlimited,
        data: $dataObject,
        cache: false,
        success: function( ){
            $('#waitAjax').hide();
            $('#searchBtn').click();
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $('#waitAjax').hide();
            bootbox.alert({
                message: $_translations[5],
                className: "bosSysErrorOne"
            });
        }

    });
});

$('#divUnsetUnlimited').click( function( event ){
    event.preventDefault();

    $archiveIdsList = getIdSelected( $('#listsearchTable') );

    if( $archiveIdsList === null ){
        bootbox.alert( {
            message: $_translations[4],
            className: "boxSysErrorOne"
        } );
    } else {
        bootbox.confirm({
            size: "small",
            message: $_translations[6],
            className: "boxQuestionTwo",
            buttons: {
                confirm: { label: $_translations[7], className: 'btn-success' },
                cancel: { label: $_translations[8], className: 'btn-danger' }
            },
            callback: function (result) { /* result is a boolean; true = OK, false = Cancel*/
                if (result) {
                    $dataObject = {
                        'idlist': $archiveIdsList,
                        'unlimited': 0,
                        'comments': ''
                    };
                    $('#waitAjax').show();

                    $.ajax({
                        type: "GET",
                        url: window.JSON_URLS.bs_idp_archivist_json_update_unlimited,
                        data: $dataObject,
                        cache: false,
                        success: function( ){
                            $('#waitAjax').hide();
                            $('#searchBtn').click();
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            $('#waitAjax').hide();
                            bootbox.alert( {
                                message: $_translations[5],
                                className: "boxSysErrorOne"
                            } );
                        }
                    });
                }
            }
        });
    }
});

function getIdSelected( $table ){
    var $selections = $table.bootstrapTable( 'getSelections' );
    var $idlist = '';
    var $bFirst = true;
    $selections.forEach( function( $elem ){
        if( $bFirst )
            $bFirst = false;
        else
            $idlist += ',';

        $idlist += $elem['id'];
    });
    return $bFirst?null:$idlist;
}