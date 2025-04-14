/**
 * Created by Cyril on 12/10/2015.
 */
var $services = [];
var $legalEntities = [];
var $budgetCodes = [];
var $documentNatures = [];
var $documentTypes = [];
var $descriptions1 = [];
var $descriptions2 = [];
var $providers = [];
var $localizations = [];
var $_tabletranslation = null;

var $_currentPage = PAGE_MANAGE_TRANSFER;
var $_currentButtons = 13;
var $_currentFCT = FCT_ARCHIVIST;
var $_mainTable = $('#listarchives');


var $defaultService = -1;
var $defaultLegalEntity = -1;
var $defaultBudgetCode = -1;
var $defaultDocumentNature = -1;
var $defaultDocumentType = -1;
var $defaultDescription1 = -1;
var $defaultDescription2 = -1;
var $defaultProvider = -1;
var $defaultLocalization =  -1;

var pollTimer;

var SERVICE_ID = 0;
var SERVICE_NAME = 1;
var SERVICE_LEGALENTITIES_IDX = 2;
var SERVICE_BUDGETCODES_IDX = 3;
var SERVICE_DESCRIPTIONS1_IDX = 4;
var SERVICE_DESCRIPTIONS2_IDX = 5;
var SERVICE_PROVIDERS_IDX = 6;
var SERVICE_DOCUMENTNATURES_IDX = 7;
var LEGALENTITY_ID = 0;
var LEGALENTITY_NAME = 1;
var DOCUMENTNATURE_ID = 0;
var DOCUMENTNATURE_NAME = 1;
var DOCUMENTNATURE_DOCUMENTTYPES_IDX = 2;
var BUDGETCODE_ID = 0;
var BUDGETCODE_NAME = 1;
var DOCUMENTTYPE_ID = 0;
var DOCUMENTTYPE_NAME = 1;
var DOCUMENTTYPE_KEEPALIVEDURATION = 2;
var DESCRIPTION1_ID = 0;
var DESCRIPTION1_NAME = 1;
var DESCRIPTION2_ID = 0;
var DESCRIPTION2_NAME = 1;
var PROVIDER_ID = 0;
var PROVIDER_NAME = 1;
var PROVIDER_LOCALIZATION_IDX = 2;
var LOCALIZATION_ID = 0;
var LOCALIZATION_NAME = 1;

var RELOC_OLD_P = [ 'CRLPDAI', 'CRLPDAINT', 'CRLPDI', 'CRLPDINT', 'CRLPCAI', 'CRLPCAINT', 'CRLPCI', 'CRLPCINT' ];

var RELOC_CONNECTOR_NEW = [ 'GRLIDAP', 'GRLINTDAP' ];
var RELOC_CONNECTOR_CONSULT = [ 'GRLPDAI', 'GRLPDAINT' ];

$xpstate = UASTATE_MANAGEPROVIDER;
$uawhat = UAWHAT_TRANSFER;
$uawhat_asked = -1;
$uawhere = UAWHERE_TRANSFER;
$uawhere_asked = -1;
$uahow = 0;
$uawith = 0;
$filter_provider = -1;

$actionList = [];	// Store Archive for Action purpose (uawhat)
$actionListID = [];	// Store only IDs to simplify some treatments
$optiListID = [];
$actionListObject = [];
$currentNumberChecked = 0;
$optimizedlist = [];

var $_commonsettings = null;
var $_settings = null;
var $_translations = null;
var $_overlay = null;

function btnwhatClean(){
    $('#btnwhat_transfer').removeClass( 'active' );
    $('#btnwhat_deliver').removeClass( 'active' );
    $('#btnwhat_return').removeClass( 'active' );
    $('#btnwhat_exit').removeClass( 'active' );
    $('#btnwhat_destroy').removeClass( 'active' );
    $('#btnwhat_reloc').removeClass( 'active' );
}
function btnwhereClean(){
    $('#btnwhere_new').removeClass( 'active' );
    $('#btnwhere_consult').removeClass( 'active' );
}

function switchCurrentPageAndButtons(){
    switch( $uawhat ){
        case UAWHAT_TRANSFER:
            $_currentPage = PAGE_MANAGE_TRANSFER;
            break;
        case UAWHAT_CONSULT:
            $_currentPage = PAGE_MANAGE_DELIVER;
            break;
        case UAWHAT_RETURN:
            $_currentPage = PAGE_MANAGE_RETURN;
            break;
        case UAWHAT_EXIT:
            $_currentPage = PAGE_MANAGE_EXIT;
            break;
        case UAWHAT_DESTROY:
            $_currentPage = PAGE_MANAGE_DELETE;
            break;
        case UAWHAT_RELOC:
            $_currentPage = PAGE_MANAGE_RELOC;
            break;
    }
    // now get back new user settings and change Column config ONLY
    $dataStr = "page=" + $_currentPage;
    $.ajax({
        type: "GET",
        url: window.JSON_URLS.bs_idp_backoffice_ajax_usersettings_get,
        data: $dataStr,
        cache: false,
        success: function( $response ) {
            // parameters : $table, $url, $userSettings, $tabletranslation, $commonSettings, $sidePagination, $method, $caller
            initMainTabColumns( false, $('#listarchives'), window.JSON_URLS.bs_idp_archivist_json_loaddatas, $response.data, $_tabletranslation, $_commonsettings, 'server', 'get', 8 );
            if( $uawhat == UAWHAT_RELOC )
                $('#btnwhere').show();
            else
                $('#btnwhere').hide();
            $('#listarchives').bootstrapTable('refresh');
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $message = xhr.responseJSON;
            bootbox.alert( {
                message: $message?$message.message:"Erreur Serveur",
                className: "boxSysErrorOne"
            });
        }
    });
}

function updateLists( data ){
    if( data.length == 9 ) { // we get all lists
        $services = data[0];
        $legalEntities = data[1];
        $documentNatures = data[2];
        $documentTypes = data[3];
        $descriptions1 = data[4];
        $descriptions2 = data[5];
        $budgetCodes = data[6];
        $providers = data[7];
        $localizations = data[8];

        initSelectProviderList( $localizations );

        return true;
    }
    return false;
}

$(document).ready(function(){

    $_commonsettings = JSON.parse( window.IDP_CONST.bs_idp_commonsettings );
    $_translations = JSON.parse( window.IDP_CONST.bs_translations );
    $_overlay = JSON.parse( window.IDP_CONST.bs_overlay );

    $_tabletranslation = JSON.parse( window.IDP_CONST.bs_tabletranslation );

    $('#btnwhere').hide();

    $('#table-action').bootstrapTable({
        showHeader: false,
        showColumns: false,
        pagination: false,
        height: 100,
        columns: [
            { field: 'name', title: 'Nom', sortable: true, visible: false },
            { field: 'id', title: 'ID', visible: false },
            { field: 'ordernumber', title: 'N° d\'ordre', sortable: true, visible: true },
            { field: 'operate', formatter: 'operateFormatter', events: 'operateActionEvents', title: 'Action', align: 'center', width: '30' }
        ]
    });

    // initOverlay
    initOverlay( );

    $.ajax({
        type: "POST",
        url: window.JSON_URLS.bs_idp_archive_form_initlists,
        data: null,
        cache: false,
        success:
            updateLists,
        error: function (xhr, ajaxOptions, thrownError) {
            $message = xhr.responseJSON;
            bootbox.alert( {
                message: $message?$message.message:"Erreur Serveur",
                className: "boxSysErrorOne"
            });
        }
    });

    // Activate Datepicker on both Date fields
    $('#frm_limitdatemin').datepicker( {
        'format': 'dd/mm/yyyy',
        'autoclose': true
    })
        .on( 'changeDate', function( event ){
            $('#frm_limitdatemin').datepicker( 'hide' );
        });
    $('#frm_limitdatemax').datepicker({
        'format': 'dd/mm/yyyy',
        'autoclose': true
    })
        .on( 'changeDate', function( event ){
            $('#frm_limitdatemax').datepicker( 'hide' );
        });

    // Since confModal is essentially a nested modal it's enforceFocus method
    // must be no-op'd or the following error results
    // "Uncaught RangeError: Maximum call stack size exceeded"
    // But then when the nested modal is hidden we reset modal.enforceFocus
    // Solution from: http://stackoverflow.com/questions/21059598/implementing-jquery-datepicker-in-bootstrap-modal
    var enforceModalFocusFn = $.fn.modal.Constructor.prototype.enforceFocus;
    $.fn.modal.Constructor.prototype.enforceFocus = function() {};

    $('#btnAction').click( function(){
        if( $actionListID.length > 0 )
            clickAction();
    });

    // Activate tooltip on buttons
    $('[data-toggle="tooltip"]').tooltip( );

    // Get back User Settings to init Main Tab
    $dataStr = "page=" + $_currentPage;
    $.ajax({
        type: "GET",
        url: window.JSON_URLS.bs_idp_backoffice_ajax_usersettings_get,
        data: $dataStr,
        cache: false,
        success: function( $response ) {
            initMainTab( $('#listarchives'), window.JSON_URLS.bs_idp_archivist_json_loaddatas, $response.data, $_tabletranslation, $_commonsettings, 'server', 'get', 8 );
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $message = xhr.responseJSON;
            bootbox.alert( {
                message: $message?$message.message:"Erreur Serveur",
                className: "boxSysErrorOne"
            });
        }
    });

    // Update ProviderConnector Form
    $.ajax({
        type: 'GET',
        url: window.JSON_URLS.bs_idp_archivist_json_providerconnectorbackup_get,
        data: '',
        cache: false,
        success: function( $response ){
            updateProviderConnectorForm( $response.datas );
        },
        error: function( xhr, ajaxOptions, thrownError ){
            $message = xhr.responseJSON;
            bootbox.alert( {
                message: $message?$message.message:"Erreur Serveur",
                className: "boxSysErrorOne"
            });
        }
    });
});

$('#addToAction').click( function( event ){
    event.preventDefault();
    if( !$('#addToAction').hasClass('disabled') )
        clickAddToAction();
});
$('#clearAction').click( function( event ){
    event.preventDefault();
    if( !$('#clearAction').hasClass("disabled")) {
        $actionList = [];
        $actionListID = [];
        $optiListID = [];
        onClickClearBasket( $('#table-action') );
    }
});

function onClickClearBasket( $basketTable ){
    $basketTable.bootstrapTable('removeAll');
    $('#listarchives').bootstrapTable('refresh');

    verifyAndDisableAddButtons();
    updateBtnActionCancelState();
    verifyAndEnableEmptyBasketButton();
}

function postQueryParams( params ){
    params.uastate = UASTATE_MANAGEPROVIDER;
    params.uawhat = $uawhat;
    params.uawhere = $uawhat==UAWHAT_RELOC?$uawhere:-1;
    params.uawith = -1; // $uawith;
    params.uahow = -1;
    params.special = $special;
    if( $("#filter_provider option:selected").val() != "" )
        params.filterprovider = $("#filter_provider option:selected").val();
    else
        params.filterprovider = -1;

    return params; //JSON.stringify( params );
}
function stateFormatter( value, row, index ){
    if( row['locked'] )
        return { disabled: true };

    var $inActionList = $actionListID.indexOf( row['id'] );

    if(  $inActionList >= 0  )
        return { disabled: true };
    else
        return { disabled: false };
}
function rowStyle( row, index ){
    if( row['locked'] )
        return { classes: 'locked' };

    var $inActionList = $actionListID.indexOf( row['id'] );

    if( $inActionList >= 0 )
        return { classes: 'info' };

    return { classes: '' };
}
function operateFormatter( value, row, index ){
    return [
        '<a class="remove" href="javascript:void(0)" title="Supprimer">', '<i class="far fa-times"></i>', '</a>'
    ].join('');
}
window.operateActionEvents = {
    'click .remove': function( e, value, row, index ){
        var $elemId = row['id'];
        var $removeIdx = -1;
        // remove line from $actionList with id
        $actionList.forEach( function( $elem, $index ){
            if( $elem['id'] == $elemId )
                $removeIdx = $index;
        });
        // Remove item from lists
        if( $removeIdx >= 0 )
            $actionList.splice( $removeIdx, 1 );
        $removeIdx = $actionListID.indexOf( $elemId );
        if( $removeIdx >= 0 )
            $actionListID.splice( $removeIdx, 1 );

        $('#table-action').bootstrapTable('load', $actionList );
        $('#listarchives').bootstrapTable('refresh');
        updateBtnActionCancelState();
        verifyAndEnableEmptyBasketButton();
    }
};
function updateBtnActionCancelState(){
    if( $actionList.length > 0 )
        $('#btnAction').removeClass( 'disabled' );
    else
        $('#btnAction').addClass( 'disabled' );

}
function verifyAndEnableEmptyBasketButton() {
    if( $actionList.length > 0 ){
        $('#clearAction').removeClass( 'disabled' );
    } else {
        $('#clearAction').addClass( 'disabled' );
    }
}

function enableAddBasketButton(){
    $('#addToAction').removeClass('disabled');
    $('#addToCancel').removeClass('disabled');
}


$('#btnConfirmModalConfirm').click(function(){

    $('#ConfirmModal').modal('hide');
    $actionList = [];
    $actionListID = [];
    $optiListID = [];
    // Update action & cancel visualisation
    $('#table-action').bootstrapTable('load', $actionList );

    switch($uawhat_asked){
        case UAWHAT_TRANSFER:
            changeWhatIntoTransfer();
            break;
        case UAWHAT_CONSULT:
            changeWhatIntoDeliver();
            break;
        case UAWHAT_RETURN:
            changeWhatIntoReturn();
            break;
        case UAWHAT_DESTROY:
            changeWhatIntoDestroy();
            break;
        case UAWHAT_EXIT:
            changeWhatIntoExit();
            break;
        case UAWHAT_RELOC:
            changeWhatIntoReloc();
            break;
    } // switch
});

function makeConfirmText( ){
    var $text = $_translations[54] + " '<strong>";
    var $bFirst = true;
    var $bCount = 0;
    if( $actionList.length > 0 ){
        $bFirst = false;
        $bCount++;
        switch( $uawhat ){
            case UAWHAT_TRANSFER: $text += $_translations[55]; break;
            case UAWHAT_CONSULT: $text += $_translations[56]; break;
            case UAWHAT_RETURN: $text += $_translations[57]; break;
            case UAWHAT_DESTROY: $text += $_translations[58]; break;
            case UAWHAT_EXIT: $text += $_translations[59]; break;
            case UAWHAT_RELOC: $text += $_translations[60]; break;
        }
    }
    if( $bCount == 1 )
        $text += "</strong>' " + $_translations[68];
    else
        $text += "</strong>' " + $_translations[69];
    $text += " <br/> "+ $_translations[70];
    return $text;
}

// Buttons UAWHERE management
$('#btnwhere_new').click( function(){
   if( $uawhere != UAWHERE_TRANSFER ){
       if( $actionList.length > 0 ){
           bootbox.alert( {
               message: $_translations[145],
               className: "boxErrorOne"
           } );
       } else
           changeWhereIntoNew();
   }
});
function changeWhereIntoNew( ){
    btnwhereClean();
    $('#btnwhere_new').addClass( 'active' );
    $uawhere = UAWHERE_TRANSFER;

    $('#divLinkBox').show();
    $('#divLinkContainer').show();
    resetMultipleSelect( );

    $('#listarchives').bootstrapTable('refresh');
};
$('#btnwhere_consult').click( function(){
    if( $uawhere != UAWHERE_CONSULT ){
        if( $actionList.length > 0 ){
            bootbox.alert( {
                message: $_translations[146],
                className: "boxErrorOne"
            } );
        } else
            changeWhereIntoConsult();
    }
});
function changeWhereIntoConsult( ){
    btnwhereClean();
    $('#btnwhere_consult').addClass( 'active' );
    $uawhere = UAWHERE_CONSULT;

    $('#divLinkBox').hide();
    $('#divLinkContainer').hide();
    resetMultipleSelect( );

    $('#listarchives').bootstrapTable('refresh');
};

// Buttons UAWHAT management
$('#btnwhat_transfer').click(function(){
    if( $uawhat != UAWHAT_TRANSFER ){
        if( $actionList.length > 0   ){
            $uawhat_asked = UAWHAT_TRANSFER;
            $('#confirmModalText').html( makeConfirmText() );
            $('#ConfirmModal').modal('show');
        } else
            changeWhatIntoTransfer();
    }
});
function changeWhatIntoTransfer(){
    btnwhatClean();
    $('#btnwhat_transfer').addClass( 'active' );
    $uawhat = UAWHAT_TRANSFER;

    $('#divLinkContainer').show();
    $('#divLinkBox').show();
    $('#titleAction').html( $_translations[26] );
    $('#btnAction').html( $_translations[28] );
    $('#textDoAction').html('&nbsp;&nbsp;&nbsp;' + $_translations[27] );

    resetMultipleSelect( );
    switchCurrentPageAndButtons();
    verifyAndEnableEmptyBasketButton();
};

$('#btnwhat_deliver').click(function(){
    if( $uawhat != UAWHAT_CONSULT ){
        if( $actionList.length > 0   ){
            $uawhat_asked = UAWHAT_CONSULT;
            $('#confirmModalText').html( makeConfirmText() );
            $('#ConfirmModal').modal('show');
        } else
            changeWhatIntoDeliver();
    }
});
function changeWhatIntoDeliver(){
    btnwhatClean();
    $('#btnwhat_deliver').addClass( 'active' );
    $uawhat = UAWHAT_CONSULT;

    $('#divLinkContainer').hide();
    $('#divLinkBox').hide();
    $('#titleAction').html( $_translations[71] );
    $('#btnAction').html( $_translations[72] );
    $('#textDoAction').html('&nbsp;&nbsp;&nbsp;' + $_translations[74] );

    resetMultipleSelect( );
    switchCurrentPageAndButtons();
    verifyAndEnableEmptyBasketButton();
};

$('#btnwhat_return').click(function(){
    if( $uawhat != UAWHAT_RETURN ){
        if( $actionList.length > 0   ){
            $uawhat_asked = UAWHAT_RETURN;
            $('#confirmModalText').html( makeConfirmText() );
            $('#ConfirmModal').modal('show');
        } else
            changeWhatIntoReturn();
    }
});
function changeWhatIntoReturn(){
    btnwhatClean();
    $('#btnwhat_return').addClass( 'active' );
    $uawhat = UAWHAT_RETURN;

    $('#divLinkContainer').hide();
    $('#divLinkBox').hide();
    $('#titleAction').html( $_translations[76] );
    $('#btnAction').html( $_translations[77] );
    $('#textDoAction').html('&nbsp;&nbsp;&nbsp;' + $_translations[79] );

    resetMultipleSelect( );
    switchCurrentPageAndButtons();
    verifyAndEnableEmptyBasketButton();
};

$('#btnwhat_exit').click(function(){
    if( $uawhat != UAWHAT_EXIT ){
        if( $actionList.length > 0   ){
            $uawhat_asked = UAWHAT_EXIT;
            $('#confirmModalText').html( makeConfirmText() );
            $('#ConfirmModal').modal('show');
        } else
            changeWhatIntoExit();
    }
});
function changeWhatIntoExit(){
    btnwhatClean();
    $('#btnwhat_exit').addClass( 'active' );
    $uawhat = UAWHAT_EXIT;

    $('#divLinkContainer').hide();
    $('#divLinkBox').hide();
    $('#titleAction').html( $_translations[81] );
    $('#btnAction').html( $_translations[82] );
    $('#textDoAction').html('&nbsp;&nbsp;&nbsp;' + $_translations[84] );

    resetMultipleSelect( );
    switchCurrentPageAndButtons();
    verifyAndEnableEmptyBasketButton();
};

$('#btnwhat_destroy').click(function(){
    if( $uawhat != UAWHAT_DESTROY ){
        if( $actionList.length > 0 ){
            $uawhat_asked = UAWHAT_DESTROY;
            $('#confirmModalText').html( makeConfirmText() );
            $('#ConfirmModal').modal('show');
        } else
            changeWhatIntoDestroy();
    }
});
function changeWhatIntoDestroy(){
    btnwhatClean();
    $('#btnwhat_destroy').addClass( 'active' );
    $uawhat = UAWHAT_DESTROY;

    $('#divLinkContainer').hide();
    $('#divLinkBox').hide();
    $('#titleAction').html( $_translations[86] );
    $('#btnAction').html( $_translations[87] );
    $('#textDoAction').html('&nbsp;&nbsp;&nbsp;' + $_translations[89] );

    resetMultipleSelect( );
    switchCurrentPageAndButtons();
    verifyAndEnableEmptyBasketButton();
};

$('#btnwhat_reloc').click(function(){
    if( $uawhat != UAWHAT_RELOC ){
        if( $actionList.length > 0  ){
            $uawhat_asked = UAWHAT_RELOC;
            $('#confirmModalText').html( makeConfirmText() );
            $('#ConfirmModal').modal('show');
        } else
            changeWhatIntoReloc();
    }
});
function changeWhatIntoReloc(){
    btnwhatClean();
    $('#btnwhat_reloc').addClass( 'active' );
    $uawhat = UAWHAT_RELOC;

    $('#divLinkContainer').show();
    $('#divLinkBox').show();
    $('#titleAction').html( $_translations[91] );
    $('#btnAction').html( $_translations[92] );
    $('#textDoAction').html('&nbsp;&nbsp;&nbsp;' + $_translations[94] );

    resetMultipleSelect( );
    switchCurrentPageAndButtons();
    verifyAndEnableEmptyBasketButton();
};

function updateServices( $initService ){
    var $serviceOptions = "<option value=\"\"></option>";
    var $i = 0;
    var $bSelected = false;
    $services.forEach(function($serviceLine){
        $selected = "";
        if( $serviceLine[SERVICE_ID] == parseInt( $initService ) ){
            $bSelected = true;
            $selected = " selected='selected' ";
        }
        $serviceOptions += "<option value=\"" + $serviceLine[SERVICE_ID] + "\" data=\"" + $i + "\" " + $selected + ">" + $serviceLine[SERVICE_NAME] + "</option> ";
        $i = $i + 1;
    });
    $('#frm_service').html( $serviceOptions );

    if( parseInt( $initService ) >= 0 ){
        updateLegalEntities( $defaultLegalEntity );
        updateBudgetCodes( $defaultBudgetCode );
        updateDescriptions1( $defaultDescription1 );
        updateDescriptions2( $defaultDescription2 );
        updateProviders( $defaultProvider );
        updateDocumentNatures( $defaultDocumentNature );
        updateLocalizations( $defaultLocalization, $defaultOldLocalization );
    }
}
function updateLegalEntities( $initLegalEntity ){
    // First test if service selected is really a service
    var $partialLegalEntities = "";
    if( $("#frm_service option:selected").val() != "" ){

        $partialLegalEntities = "<option value ";
        if( parseInt($initLegalEntity) <= 0 )
            $partialLegalEntities += "selected=\"selected\"";
        $partialLegalEntities += "></option>";

        // Construct list of legal entities choices based on service id
        var $serviceIdx = parseInt( $("#frm_service option:selected").attr('data') );
        var $listLegalEntities = $services[$serviceIdx][SERVICE_LEGALENTITIES_IDX];
        var $i = 0;
        $legalEntities.forEach(function($legalentityLine){
            if( $listLegalEntities.indexOf( $legalentityLine[LEGALENTITY_ID] ) >= 0 ){
                var $selected = "";
                if( $legalentityLine[LEGALENTITY_ID] == parseInt( $initLegalEntity ) )
                    $selected = "selected=\"selected\"";
                $partialLegalEntities += "<option value=\"" + $legalentityLine[LEGALENTITY_ID] + "\" data=\"" + $i + "\" " + $selected + ">" + $legalentityLine[LEGALENTITY_NAME] + "</option> ";
            }
            $i = $i + 1;
        });
        $('#frm_legalentity').attr('disabled', false);
    } else {
        $('#frm_legalentity').attr('disabled', true);
        $partialLegalEntities ="<option value selected=\"selected\">N/A</option>";
    }

    $("#frm_legalentity").html($partialLegalEntities);

}
function updateBudgetCodes( $initBudgetCode ){

    var $partialBudgetCodes = "";
    if( $("#frm_service option:selected").val() != "" ){

        $partialBudgetCodes ="<option value ";
        if( parseInt($initBudgetCode) <= 0 )
            $partialBudgetCodes += " selected=\"selected\"";
        $partialBudgetCodes += "></option>";

        // Construct list of budget codes choices based on service id
        var $serviceIdx = parseInt( $("#frm_service option:selected").attr('data') );
        var $listBudgetCodes = $services[$serviceIdx][SERVICE_BUDGETCODES_IDX];
        var $i = 0;
        $budgetCodes.forEach(function($budgetcodeLine){
            if( $listBudgetCodes.indexOf( $budgetcodeLine[BUDGETCODE_ID] ) >= 0 ){
                var $selected = "";
                if( $budgetcodeLine[BUDGETCODE_ID] == parseInt( $initBudgetCode ) )
                    $selected = "selected=\"selected\"";
                $partialBudgetCodes += "<option value=\"" + $budgetcodeLine[BUDGETCODE_ID] + "\" data=\"" + $i + "\" " + $selected + ">" + $budgetcodeLine[BUDGETCODE_NAME] + "</option> ";
            }
            $i = $i + 1;
        });
        $('#frm_budgetcode').attr('disabled', false);
    } else {
        $('#frm_budgetcode').attr('disabled', true);
        $partialBudgetCodes ="<option value selected=\"selected\">N/A</option>";
    }

    $("#frm_budgetcode").html($partialBudgetCodes);
}
function updateDocumentNatures( $initDocumentNature ){

    var $partialDocumentNatures = "";
    if( $("#frm_service option:selected").val() != "" ){

        $partialDocumentNatures = "<option value ";
        if( parseInt($initDocumentNature) <= 0 )
            $partialDocumentNatures += " selected=\"selected\"";
        $partialDocumentNatures += "></option>";
        // Construct list of document natures choices based on service id
        var $serviceIdx = parseInt( $("#frm_service option:selected").attr('data') );
        var $listDocumentNatures = $services[$serviceIdx][SERVICE_DOCUMENTNATURES_IDX];
        var $i = 0;
        $documentNatures.forEach(function($documentnatureLine){
            if( $listDocumentNatures.indexOf( $documentnatureLine[DOCUMENTNATURE_ID] ) >= 0 ){
                var $selected = "";
                if( $documentnatureLine[DOCUMENTNATURE_ID] == parseInt( $initDocumentNature ) )
                    $selected = "selected=\"selected\"";
                $partialDocumentNatures += "<option value=\"" + $documentnatureLine[DOCUMENTNATURE_ID] + "\" data=\"" + $i + "\" " + $selected + ">" + $documentnatureLine[DOCUMENTNATURE_NAME] + "</option> ";
            }
            $i = $i + 1;
        });
        $('#frm_documentnature').attr('disabled', false);
    } else {
        $('#frm_documentnature').attr('disabled', true);
        $partialDocumentNatures ="<option value selected=\"selected\">N/A</option>";
    }

    $("#frm_documentnature").html($partialDocumentNatures);

    if( parseInt( $initDocumentNature ) >= 0 )
        updateDocumentTypes( $defaultDocumentType );
}
function updateDocumentTypes( $initDocumentType ){

    var $partialDocumentTypes = "";
    if( $("#frm_documentnature option:selected").val() != "" ){

        $partialDocumentTypes ="<option value ";
        if( parseInt($initDocumentType) <= 0 )
            $partialDocumentTypes += " selected=\"selected\"";
        $partialDocumentTypes += "></option>";
        // Construct list of document types choices based on document nature id
        var $documentnatureIdx = parseInt( $("#frm_documentnature option:selected").attr('data') );
        var $listDocumentTypes = $documentNatures[$documentnatureIdx][DOCUMENTNATURE_DOCUMENTTYPES_IDX];
        var $i = 0;
        $documentTypes.forEach(function($documenttypeLine){
            if( $listDocumentTypes.indexOf( $documenttypeLine[DOCUMENTTYPE_ID] ) >= 0 ){
                var $selected = "";
                if( $documenttypeLine[DOCUMENTTYPE_ID] == parseInt( $initDocumentType ) )
                    $selected = "selected=\"selected\"";
                $partialDocumentTypes += "<option value=\"" + $documenttypeLine[DOCUMENTTYPE_ID] + "\" data=\"" + $i + "\" " + $selected + ">" + $documenttypeLine[DOCUMENTTYPE_NAME] + "</option> ";
            }
            $i = $i + 1;
        });
        $('#frm_documenttype').attr('disabled', false);
    } else {
        $('#frm_documenttype').attr('disabled', true);
        $partialDocumentTypes ="<option value selected=\"selected\">N/A</option>";
    }

    $("#frm_documenttype").html($partialDocumentTypes);

    if( parseInt( $initDocumentType ) >= 0 )
        updateDestructionYear();
}
function updateDescriptions1( $initDescription1 ){

    var $partialDescriptions1 = "";
    if( $("#frm_service option:selected").val() != "" ){

        $partialDescriptions1 ="<option value ";
        if( parseInt($initDescription1) <= 0 )
            $partialDescriptions1 += " selected=\"selected\"";
        $partialDescriptions1 += "></option>";
        // Construct list of descriptions1 choices based on service id
        var $serviceIdx = parseInt( $("#frm_service option:selected").attr('data') );
        var $listDescriptions1 = $services[$serviceIdx][SERVICE_DESCRIPTIONS1_IDX];
        var $i = 0;
        $descriptions1.forEach(function($descriptionLine){
            if( $listDescriptions1.indexOf( $descriptionLine[DESCRIPTION1_ID] ) >= 0 ){
                var $selected = "";
                if( $descriptionLine[DESCRIPTION1_ID] == parseInt( $initDescription1 ) )
                    $selected = "selected=\"selected\"";
                $partialDescriptions1 += "<option value=\"" + $descriptionLine[DESCRIPTION1_ID] + "\" data=\"" +  $i + "\" " + $selected + ">" + $descriptionLine[DESCRIPTION1_NAME] + "</option> ";
            }
            $i = $i + 1;
        });
        $('#frm_description1').attr('disabled', false);
    } else {
        $('#frm_description1').attr('disabled', true);
        $partialDescriptions1 ="<option value selected=\"selected\">N/A</option>";
    }

    $("#frm_description1").html($partialDescriptions1);
}
function updateDescriptions2( $initDescription2 ){

    var $partialDescriptions2 = "";
    if( $("#frm_service option:selected").val() != "" ){

        $partialDescriptions2 ="<option value ";
        if( parseInt($initDescription2) <= 0 )
            $partialDescriptions2 += " selected=\"selected\"";
        $partialDescriptions2 += "></option>";
        // Construct list of descriptions2 choices based on service id
        var $serviceIdx = parseInt( $("#frm_service option:selected").attr('data') );
        var $listDescriptions2 = $services[$serviceIdx][SERVICE_DESCRIPTIONS2_IDX];
        var $i = 0;
        $descriptions2.forEach(function($descriptionLine){
            if( $listDescriptions2.indexOf( $descriptionLine[DESCRIPTION2_ID] ) >= 0 ){
                var $selected = "";
                if( $descriptionLine[DESCRIPTION2_ID] == parseInt( $initDescription2 ) )
                    $selected = "selected=\"selected\"";
                $partialDescriptions2 += "<option value=\"" + $descriptionLine[DESCRIPTION2_ID] + "\" data=\"" + $i + "\"  " + $selected + ">" + $descriptionLine[DESCRIPTION2_NAME] + "</option> ";
            }
            $i = $i + 1;
        });
        $('#frm_description2').attr('disabled', false);
    } else {
        $('#frm_description2').attr('disabled', true);
        $partialDescriptions2 ="<option value selected=\"selected\">N/A</option>";
    }

    $("#frm_description2").html($partialDescriptions2);
}
function updateProviders( $initProvider ){

    var $partialProviders = "";
    if( $("#frm_service option:selected").val() != "" ){

        $partialProviders ="<option value ";
        if( parseInt($initProvider) <= 0 )
            $partialProviders += " selected=\"selected\"";
        $partialProviders += "></option>";
        // Construct list of providers choices based on service id
        var $serviceIdx = parseInt( $("#frm_service option:selected").attr('data') );
        var $listProviders = $services[$serviceIdx][SERVICE_PROVIDERS_IDX];
        var $i = 0;
        $providers.forEach(function($providerLine){
            if( $listProviders.indexOf( $providerLine[PROVIDER_ID] ) >= 0 ){
                var $selected = "";
                if( $providerLine[PROVIDER_ID] == parseInt( $initProvider ) )
                    $selected = "selected=\"selected\"";
                $partialProviders += "<option value=\"" + $providerLine[PROVIDER_ID] + "\" data=\"" + $i + "\" " + $selected + ">" + $providerLine[PROVIDER_NAME] + "</option> ";
            }
            $i = $i + 1;
        });
        $('#frm_provider').attr('disabled', false);
    } else {
        $('#frm_provider').attr('disabled', true);
        $partialProviders ="<option value selected=\"selected\">N/A</option>";
    }

    $("#frm_provider").html($partialProviders);
}
function updateLocalizations( $initLocalization ){

    $partialLocalizations = "";
    if( $("#frm_provider option:selected").val() != "" ){

        $partialLocalizations ="<option value ";
        if( parseInt($initLocalization) <= 0 )
            $partialLocalizations += " selected=\"selected\"";
        $partialLocalizations += "></option>";

        // Construct list of localizations choices based on provider id
        $providerIdx = parseInt( $("#frm_provider option:selected").attr('data') );
        $localizationIdx = $providers[$providerIdx][PROVIDER_LOCALIZATION_IDX];

        if( $localizationIdx >= 0 ) {
            $localizations.forEach( function( $localizationLine ){
                $selected = "";
                if( $localizationLine[LOCALIZATION_ID] == $localizationIdx ) {
                    if ($localizationLine[LOCALIZATION_ID] == parseInt($initLocalization))
                        $selected = "selected=\"selected\"";
                    $partialLocalizations += "<option value=\"" + $localizationLine[LOCALIZATION_ID] + "\"" + $selected + ">" + $localizationLine[LOCALIZATION_NAME] + "</option> ";
                }
            });
        }

        $('#frm_localization').attr('disabled', false);
    } else {
        $('#frm_localization').attr('disabled', true);
        $partialLocalizations ="<option value selected=\"selected\"></option>";
    }

    $("#frm_localization").html($partialLocalizations);
}

// On service select box change, update legal entities & budget codes with only available choices
$('#frm_service').change(function () {
    updateLegalEntities( -1 );
    updateBudgetCodes( -1 );
    updateDocumentNatures( -1 );
    updateDocumentTypes( -1 );
    updateDescriptions1( -1 );
    updateDescriptions2( -1 );
    updateProviders( -1 );

    getAjaxSettings( $('#frm_service option:selected').val(), null );
});
$('#frm_provider').change( function() {
    updateLocalizations( -1 );
});

// On DocumentNature select box change, update document types with available choices
$('#frm_documentnature').change(function(){
    updateDocumentTypes( -1 );
});

// On DocumentType select change, update the destruction year if needed
$('#frm_documenttype').change(function(){
    updateDestructionYear();
});

// Change Destruction year, if closure year change and input loss focus$
$('#frm_closureyear').blur(function(){
    updateDestructionYear();
});

function updateDestructionYear(){

    var $destructionTime = 0;
    if( $('#frm_documenttype option:selected').val != '' ) {
        var $documenttypeIdx = parseInt($("#frm_documenttype option:selected").attr('data'));
        if( !isNaN( $documenttypeIdx ) )
            var $destructionTime = $documentTypes[$documenttypeIdx][DOCUMENTTYPE_KEEPALIVEDURATION];
    }

    // If destruction time is 0, user should enter whatever he wants, instead destruction time is forced
    if($destructionTime == 0){
        $("#frm_destructionyear").prop('disabled', false);
    }
    else
    {
        $("#frm_destructionyear").prop('disabled', true);
        // TODO test empty field
        var $destructionYear = parseInt($("#frm_closureyear").val());
        $destructionYear += $destructionTime;
        $("#frm_destructionyear").val($destructionYear);
    }
}


// Update both Add button states accordingly to selections in main table
/*function updateAddButtonState(){
    if( $currentNumberChecked > 0 ){
        $('#addToAction').removeClass('disabled');
    } else {
        $('#addToAction').addClass('disabled');
    }
}*/
function verifyAndDisableAddButtons() {
    $selections = $_mainTable.bootstrapTable('getSelections');
    if( $selections.length <= 0 ){
        $('#addToAction').addClass('disabled');
        $('#addToCancel').addClass('disabled');
    }
}


// Add new selection to the Action list
function clickAddToAction(){
    var $selections = $('#listarchives').bootstrapTable( 'getSelections' );

    // TODO test if there is no unlimited archive in the list.

    // Scan to search new ones
    $selections.forEach( function( $elem ){
        var $inActionList = $actionListID.indexOf( $elem['id'] );
        if( $inActionList < 0 ){
            $actionListID.push( $elem['id'] );
            $actionList.push( $elem );
        }
    });

    // Refresh table-action with new list
    $('#table-action').bootstrapTable('load', $actionList );
    $('#listarchives').bootstrapTable('refresh');
    updateBtnActionCancelState();
    verifyAndEnableEmptyBasketButton();
    verifyAndDisableAddButtons();
    return true;
};

//--------------------------------------------------------------------------------------------------------------
// After verifying user_side exception do this
function continue_After_BasketTests( $action ){
    $('#waitAjax').hide();
    // Display modal for Provider Connector Complementary Datas
    showComplementaryProviderConnectorModal();
}

//.................................................................................
// If user validate the Provider Connector Datas Window
$('#ProviderConnectorModalBtnConfirm').click( function() {

    clearPCBView();
    if( verifyPCBMandatories() ) {
        // Hide modal form and switch on the ajax  wait screen
        $('#ProviderConnectorModal').modal('hide');
        //pollLatestOptiStatus();
        pollTimer = setInterval(pollLatestOptiStatus, 1000);
        $('#waitAjax').show();

        // Save datas for next time
        save_ProviderConnectorModalDatas();

        // Lock full container / box asked in basket (except in return mode)
        lock_Basket();
    } else {
        bootbox.alert( {
            title: 'Le formulaire comporte des erreurs',
            message: "Certains champs obligatoires n'ont pas été renseignés !",
            className: "boxErrorOne"
        } );
    }
});

//.................................................................................
// This function Locks full container / box asked in basket, if successfull call the switch_PreOptimisation function
function lock_Basket( ) {
    if( $uawhat == UAWHAT_RETURN )
        switch_PreOptimisation( );
    else {
        var $dataObject = {
            'ids': JSON.stringify($actionListID)
        };

        $.ajax({
            type: "GET",
            url: window.JSON_URLS.bs_idp_archivist_provider_connector_lock_basket,
            data: $dataObject,
            cache: false,
            success: function ($response) {
                switch_PreOptimisation();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $('#waitAjax').hide();
                $message = xhr.responseJSON;
                bootbox.alert( {
                    message: $message ? $message.message : $_translations[147],
                    className: "boxSysErrorOne"
                });
                clearInterval(pollTimer);
            }
        });
    }
}
//.................................................................................
// This function UnLocks full container / box asked in basket in case of errors during operation
function unlock_Basket( ){
    if( $uawhat != UAWHAT_RETURN )
        $('#waitAjax').hide();
    else {
        var $dataObject = {
            'ids': JSON.stringify($actionListID)
        };

        $.ajax({
            type: 'GET',
            url: window.JSON_URLS.bs_idp_archivist_provider_connector_unlock_basket,
            data: $dataObject,
            cache: false,
            success: function ($response) {
                $('#waitAjax').hide();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $('#waitAjax').hide();
                $message = xhr.responseJSON;
                bootbox.alert( {
                    message: $message ? $message.message : $_translations[147],
                    className: "boxSysErrorOne"
                });
            }
        });
    }
    $actionListObject = [];
    $optiListID = [];
}
//.................................................................................
// This function ungrays uas grayed for optimization
function ungray_UAs_Optimized( ){
    if( $optimizedlist.length > 0 ) {
        var $dataObject = {
            'idoptimizedlist': JSON.stringify($optimizedlist),
        };
        $.ajax({
            type: "GET",
            url: window.JSON_URLS.bs_idp_archivist_provider_connector_ungray,
            data: $dataObject,
            cache: false
        });
        $optimizedlist = [];
    }
}
//.................................................................................
// this function will orientate the process after lock_basket based on $uawhat
function switch_PreOptimisation( ){
    /*
    $bRelocConnectorConsult = $uachere == UAWHERE_CONSULT;
    // verify if we are in a Reloc Consult type
    $actionList.forEach( function( $elem ){
        if( $.inArray( $elem['statuscaps'], RELOC_CONNECTOR_CONSULT ) >= 0 )
            $bRelocConnectorConsult = true;
    });
    */

    // Depends on function, synopsis isn't the same for all
    switch( $uawhat ){
        case UAWHAT_TRANSFER:
        case UAWHAT_RETURN:
        case UAWHAT_EXIT:

            $optiListID = $actionListID;        // No optimization, so optimizedList = BasketList
            $actionListObject = null;           // React as if all objects were asked
            generate_PrePDF( );
            clearInterval(pollTimer);
            break;

        case UAWHAT_CONSULT: // = Consultation

            calculate_Optimization( false );
            break;

        case UAWHAT_DESTROY:

            calculate_Optimization( true );
            break;
        case UAWHAT_RELOC:
            if( $uawhere == UAWHERE_CONSULT ){  // Should react like Consult

                calculate_Optimization( false );

            } else { // Should react like Transfer

                $optiListID = $actionListID;
                $actionListObject = null;
                generate_PrePDF( );

            }
            break;
    }
}
//.................................................................................
// this function will orientate the process after an error occured based on $uawhat
function switch_PostOptimisation( ){
    $bRelocConnectorConsult = false;
    // verify if we are in a Reloc Consult type
    $actionList.forEach( function( $elem ){
        if( $.inArray( $elem['statuscaps'], RELOC_CONNECTOR_CONSULT ) >= 0 )
            $bRelocConnectorConsult = true;
    });

    // Depends on function, synopsis isn't the same for all
    switch( $uawhat ){
        case UAWHAT_TRANSFER:
        case UAWHAT_RETURN:
        case UAWHAT_EXIT:

            $optiListID = $actionListID;        // No optimization, so optimizedList = BasketList
            $actionListObject = null;           // React as if all objects were asked
            unlock_Basket();
            break;

        case UAWHAT_CONSULT: // = Consultation
        case UAWHAT_DESTROY:

            unmanage_Optimization( );
            break;

        case UAWHAT_RELOC:
            if( $bRelocConnectorConsult ){  // Should react like Consult

                unmanage_Optimization( );

            } else { // Should react like Transfer

                $optiListID = $actionListID;
                $actionListObject = null;
                unlock_Basket( );

            }
            break;
    }
}

//.................................................................................
// This function is called once Optimisation Window is accepted
$('#OptimisationModalBtnConfirm').click( function( event ) {
    $('#OptimisationModalBody input:checked').each(function () {
        $thisValue = parseInt($(this).attr('value'));
        if( $thisValue > 0)
            $optiListID.push( $thisValue );
        else {
            if( $thisValue != -2 )  // not subbox
                $actionListObject.push($(this).attr('id'));
            else    // For subbox, only if not grayed (and checked)
                if( !$(this).attr('disabled') )
                    $actionListObject.push($(this).attr('id'));
        }
    });

    manage_OptimizationChoices( false );
});

$('#DelOptimisationModalBtnConfirm').click( function( event ) {
    $('#DelOptimisationModalBody input:checked').each(function () {
        $thisValue = parseInt($(this).attr('value'));
        if( $thisValue > 0 )
            $optiListID.push( $thisValue );
    });

    manage_OptimizationChoices( true );
});
//.................................................................................
// This function is called when pre PDF is accepted
function doAction(  ){

    var $dataObject = {
        'localizationId': $("#filter_provider option:selected").val(),
        'uastate': UASTATE_MANAGEPROVIDER,
        'uawhat': $uawhat,
        'uawhere': $uawhere,
        'uawith': -1,
        'uahow': -1,
        'ids': JSON.stringify($optiListID),
        'objects': JSON.stringify($actionListObject),   // Full objetcs selected
        'contact' : $('#frm_pcb_contact').val(),
        'phone': $('#frm_pcb_phone').val(),
        'address': $('#frm_pcb_address').val(),
        'deliver': $('input[name=pcbdeliverradio]:checked', '#ProviderConnectorModalBody').val(),
        'disposal': $('input[name=pcbdisposalradio]:checked', '#ProviderConnectorModalBody').val(),
        'type': $('input[name=pcbtyperadio]:checked', '#ProviderConnectorModalBody').val(),
        'type2': $('input[name=pcbtype2radio]:checked', '#ProviderConnectorModalBody').val(),
        'remark': $('#frm_pcb_remark').val(),
        'name': $('#frm_pcb_name').val(),
        'firstname': $('#frm_pcb_firstname').val(),
        'function': $('#frm_pcb_function').val(),
        'pre': 0
    };

    $.ajax({
        type: "GET",
        url: window.JSON_URLS.bs_idp_archivist_json_action,
        data: $dataObject,
        cache: false,
        success: function ($response) {
            $('#waitAjax').hide();
            $actionList = [];
            $actionListID = [];
            $('#table-action').bootstrapTable('load', $actionList);
            $('#listarchives').bootstrapTable('refresh');
            updateBtnActionCancelState();
            // Launch pdf real generation

            get(window.JSON_URLS.bs_idp_archivist_print_provider_connector, $dataObject, true );
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $('#waitAjax').hide();
            $message = xhr.responseJSON;
            bootbox.alert( {
                message: $message?$message.message:$_translations[147],
                className: "boxSysErrorOne"
            });
        }
    });

    $optiListID = [];
    $actionListObject = [];
}

//.................................................................................
// This function send to server result of optimization choices window
function manage_OptimizationChoices( $delModal ){

    if( $optiListID.length > 0 ) {
        var $dataObject = {
            'uawhat': $uawhat,
            'uawhere': $uawhat==UAWHAT_RELOC?$uawhere:-1,
            'ids': JSON.stringify($optiListID),             // Only those selected in opti_modal (can be less or more)
            'basketids': JSON.stringify($actionListID),     // Old selected with basket
            'objects': !$delModal?JSON.stringify($actionListObject):null,   // Full objetcs selected
        };

        $.ajax({
            type: "GET",
            url: window.JSON_URLS.bs_idp_archivist_provider_connector_manage_optimization_choices,
            data: $dataObject,
            cache: false,
            success: function( $response ){

                generate_PrePDF( );
            },
            error: function( xhr, ajaxOptions, throwError ){
                $('#waitAjax').hide();
                $message = xhr.responseJSON;
                bootbox.alert( {
                    message: $message?$message.message:$_translations[147],
                    className: "boxSysErrorOne"
                });
                $actionListObject = [];
                unlock_Basket();
            }
        });
    } else {
        /*  Done by dialog disparition
        ungray_UAs_Optimized();
        */
    }

    if( !$delModal )
        $('#OptimisationModal').modal('hide');
    else
        $('#DelOptimisationModal').modal('hide');
}

//.................................................................................
// This function ask server for optimization archives to be displayed
function calculate_Optimization( $delModal ){
    // Send list of ids to server for optimisation
    var $dataObject = {
        'idlist': JSON.stringify($actionListID),
        'uawhat': $uawhat,
        'uawhere': -1
    };
    $.ajax({
        type: "GET",
        url: window.JSON_URLS.bs_idp_archivist_provider_connector_optimisation,
        data: $dataObject,
        cache: false,
        success: function($response) {
            // Get back list of (ids + optimisation) to show in optimisation popup
            $optimizedlist = [];
            $optimizedtemp = $response['optimizedlist'];
            for (var i = 0, len = $optimizedtemp.length; i < len; i++) {
                $optimizedlist.push( $optimizedtemp[i]['id'] );
            }

            $('#waitAjax').hide();
            clearInterval(pollTimer);
            //updatePopupOptimizationModal_v2($optimizedtemp, $actionListID, $delModal);
            updatePopupOptimizationModal_v3($optimizedtemp, $actionListID, $delModal);
            if( !$delModal ) {
                $('#OptimisationModal').modal('show');
            } else {
                $('#DelOptimisationModal').modal('show');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $('#waitAjax').hide();
            $message = xhr.responseJSON;
            bootbox.alert( {
                message: $message?$message.message:$_translations[147],
                className: "boxSysErrorOne"
            });
            clearInterval(pollTimer);
            unlock_Basket();
        }
    });
}
//.................................................................................
// This function ask server to undo optimization ordered lock
function unmanage_Optimization( ) {
    if ($optiListID.length > 0) {
        var $dataObject = {
            'uawhat': $uawhat,
            'uawhere': $uawhat == UAWHAT_RELOC ? $uawhere : -1,
            'ids': JSON.stringify($optiListID),             // Only those selected in opti_modal (can be less or more)
            'objects': JSON.stringify($actionListObject),   // Full objetcs selected
        };

        $.ajax({
            type: "GET",
            url: window.JSON_URLS.bs_idp_archivist_provider_connector_unmanage_optimization_choices,
            data: $dataObject,
            cache: false,
            success: function ($response) {
                unlock_Basket();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $message = xhr.responseJSON;
                bootbox.alert( {
                    message: $message?$message.message:$_translations[147],
                    className: "boxSysErrorOne"
                });
                unlock_Basket();
            }
        });
    } else
        unlock_Basket();
}

//.................................................................................
// This function ask server to generate preview of PDF Connector Provider
function generate_PrePDF( ){
    var $dataObject = {
        'localizationId': $("#filter_provider option:selected").val(),
        'uastate': UASTATE_MANAGEPROVIDER,
        'uawhat': $uawhat,
        'uawhere': $uawhere,
        'uawith': -1,
        'uahow': -1,
        'ids': JSON.stringify($optiListID),
        'basketids': JSON.stringify($actionListID),     // Old selected with basket
        'objects': JSON.stringify($actionListObject),   // Full objetcs selected
        'contact' : $('#frm_pcb_contact').val(),
        'phone': $('#frm_pcb_phone').val(),
        'address': $('#frm_pcb_address').val(),
        'deliver': $('input[name=pcbdeliverradio]:checked', '#ProviderConnectorModalBody').val(),
        'disposal': $('input[name=pcbdisposalradio]:checked', '#ProviderConnectorModalBody').val(),
        'type': $('input[name=pcbtyperadio]:checked', '#ProviderConnectorModalBody').val(),
        'type2': $('input[name=pcbtype2radio]:checked', '#ProviderConnectorModalBody').val(),
        'remark': $('#frm_pcb_remark').val(),
        'name': $('#frm_pcb_name').val(),
        'firstname': $('#frm_pcb_firstname').val(),
        'function': $('#frm_pcb_function').val(),
        'pre': 1                                    // ask serveur to generate pdf simulation
    };

    get(window.JSON_URLS.bs_idp_archivist_print_provider_connector, $dataObject, true );
    $('#waitAjax').hide();

    $btnText = $_translations[148];
    switch( $dataObject['uawhat'] ){
        case 0: $btnText += $_translations[149]; break;
        case 1: $btnText += $_translations[150]; break;
        case 2: $btnText += $_translations[151]; break;
        case 3: $btnText += $_translations[152]; break;
        case 4: $btnText += $_translations[153]; break;
        case 5: $btnText += $_translations[154]; break;
    }
    bootbox.dialog({
        message: $_translations[155],
        title: 'Connecteur prestataire',
        className: "boxQuestionTwo",
        closeButton: false,
        buttons: {
            "Non": { label: $_translations[139], className: "btn-default", callback:
                    function() {
                        switch_PostOptimisation();
                    }},
            "Oui": { label: $btnText, className: "btn-primary", callback:
                    function() {
                        doAction( );
                    }}
        }
    });
}


//====================================================

$('#divPrint').click(function(){
    var $params = new Array();
    $params['id'] = $('#frm_id').val();
    get(window.JSON_URLS.bs_idp_archive_print_sheet, $params, true );
});

// Export and Export Partial button management
$('#divExport').click(function( event ){
    event.preventDefault();

    $filter = ( $("#filter_provider option:selected").val() != "" ) ? $("#filter_provider option:selected").val() : -1;

    exportArray( $('#listarchives'), $_currentFCT, UASTATE_MANAGEPROVIDER, $uawhat, $uawhere, $uahow, $filter, false );
});
$('#divExportPartial').click( function( event ){
    event.preventDefault();

    $filter = ( $("#filter_provider option:selected").val() != "" ) ? $("#filter_provider option:selected").val() : -1;

    exportArray( $('#listarchives'), $_currentFCT, UASTATE_MANAGEPROVIDER, $uawhat, $uawhere, $uahow, $filter, true );
});


$('#divPrintList').click(function(){
    if( $('#listarchives').bootstrapTable('getData').length > 0 )
        printTable( false, $('#listarchives'), 3, window.IDP_CONST.bs_idp_current_page );
});
$('#divPrintPartialList').click(function(){
    if( $('#listarchives').bootstrapTable('getSelections').length > 0 )
        printTable( true, $('#listarchives'), 3 );
});

// Init Select box with all Localizations for filtering purpose
function initSelectProviderList( $localizationList ){
    var $partialLocalizations = ""; // "<option value='-1' selected='selected' >Tous</option>";

    var $bFirst = true;
    $localizationList.forEach(function($localizationLine) {
        var $selected = "";
        if( $bFirst ){
            $selected = "selected='selected'";
            $bFirst = false;
            $filter_provider = $localizationLine[LOCALIZATION_ID];
        }
        $partialLocalizations += "<option value='" + $localizationLine[LOCALIZATION_ID] + "' " + $selected + ">" + $localizationLine[LOCALIZATION_NAME] + "</option> ";

    });

    $("#filter_provider").html($partialLocalizations);
    $('#listarchives').bootstrapTable('refresh');
}

// Provider SelectBox management
$('#filter_provider').change( function( event ){
    event.preventDefault();
    if( $filter_provider != $('#filter_provider').val() ){
        if( $actionList.length > 0 ){
            $('#filter_provider').val( $filter_provider );
            bootbox.alert( {
                message: $_translations[159],
                className: "boxErrorOne"
            } );
        } else {
            $filter_provider = $('#filter_provider').val();
            $('#listarchives').bootstrapTable('refresh', {pageNumber: 1});
        }
    }

})


// -----------------------------------------------------------------------
// Manage Modal popup for "link with same box" button
$('#divLinkContainer').click(function(){
    // Activate only if something is selected
    if( $('#listarchives').bootstrapTable('getSelections').length > 0 ){
        $('#linkContainer_container').val( '' );
        $('#LinkContainerModal').modal('show');
    }
});
$('#btnLinkModalConfirm').click(function(){
   // Only if container field is not empty
    if( $('linkContainer_container').val() == '' ){
        bootbox.alert( {
            message: $_translations[101],
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
            'containernumber': $('#linkContainer_container').val()
        };
        $.ajax({
            type: "GET",
            url: window.JSON_URLS.bs_idp_archivist_json_update_container,
            data: $datas,
            cache: false,
            success: function( ){
                $('#LinkContainerModal').modal('hide');
                $('#listarchives').bootstrapTable('refresh');
                $_recheck = true;   // Activate post reload rechecking
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $message = xhr.responseJSON;
                bootbox.alert( {
                    message: $message?$message.message:$_translations[147],
                    className: "boxSysErrorOne"
                });
                $('#LinkContainerModal').modal('hide');
            }
        });
    }
});

// -----------------------------------------------------------------------
// Manage Modal popup for "link with same box" button
$('#divLinkBox').click(function(){
    // Activate only if something is selected
    if( $('#listarchives').bootstrapTable('getSelections').length > 0 ){

        var $selections = $('#listarchives').bootstrapTable( 'getSelections' );
        $providerEmpty = true;
        $documentEmpty = true;
        $containerEmpty = true;
        $containerSame = true;
        $lastContainer = null;

        $selections.forEach( function( $elem ){
            if( $providerEmpty && $elem['provider_id'] != null )
                $providerEmpty = false;
            if( $documentEmpty && $elem['documentnumber'] != null )
                $documentEmpty = false;
            if( $containerEmpty && $elem['containernumber'] != null ){
                $containerEmpty = false;
                $lastContainer = $elem['containernumber'];
            }
            if( $containerSame && !$containerEmpty && $lastContainer != $elem['containernumber'] )
                $containerSame = false;
            $lastContainer = $elem['containernumber'];
        });

        $isOkToShow = false;
        if( $providerEmpty )
            $isOkToShow = true;
        else
            if( $documentEmpty )
                $isOkToShow = true;
            else
                if( $containerSame )
                    $isOkToShow = true;

        if( $isOkToShow ) {
            $('#linkBox_box').val('');
            $('#LinkBoxModal').modal('show');
        } else
            bootbox.alert( {
                message: $_translations[160],
                className: "boxErrorOne"
            } );
    }
});
$('#btnLinkModalBoxConfirm').click(function(){

    // Only if container field is not empty
    if( $('linkBox_box').val() == '' ){
        bootbox.alert( {
            message: $_translations[111],
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
            'boxnumber': $('#linkBox_box').val()
        };
        $.ajax({
            type: "GET",
            url: window.JSON_URLS.bs_idp_archivist_json_update_box,
            data: $datas,
            cache: false,
            success: function( ){
                $('#LinkBoxModal').modal('hide');
                $('#listarchives').bootstrapTable('refresh');
                $_recheck = true;   // Activate post reload rechecking
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $message = xhr.responseJSON;
                bootbox.alert( {
                    message: $message?$message.message:$_translations[147],
                    className: "boxSysErrorOne"
                });
                $('#LinkBoxModal').modal('hide');
            }
        });
    }
});

// -----------------------------------------------------------------------
// Modification functions
$('#divSubmitModif').click(function(){
    onClickBtnSubmitModif();
    currentOverlayViewUA = null;
    return true;
});

function onClickBtnSubmitModif(){
    if( verifyMandatories() ){
        $dataObject = {
            'id': $('#frm_id').val(),
            'service': ( $("#frm_service option:selected").val() != "" )?parseInt( $("#frm_service option:selected").val() ):-1,
            'ordernumber': $('#frm_ordernumber').val(),
            'legalentity': ( $("#frm_legalentity option:selected").val() != "" )?parseInt( $("#frm_legalentity option:selected").val() ):-1,
            'budgetcode': ( $("#frm_budgetcode option:selected").val() != "" )?parseInt( $("#frm_budgetcode option:selected").val() ):-1,
            'documentnature': ( $("#frm_documentnature option:selected").val() != "" )?parseInt( $("#frm_documentnature option:selected").val() ):-1,
            'documenttype': ( $("#frm_documenttype option:selected").val() != "" )?parseInt( $("#frm_documenttype option:selected").val() ):-1,
            'description1': ( $("#frm_description1 option:selected").val() != "" )?parseInt( $("#frm_description1 option:selected").val() ):-1,
            'description2': ( $("#frm_description2 option:selected").val() != "" )?parseInt( $("#frm_description2 option:selected").val() ):-1,
            'provider': ( $("#frm_provider option:selected").val() != "" )?parseInt( $("#frm_provider option:selected").val() ):-1,
            'closureyear': $('#frm_closureyear').val(),
            'destructionyear': $('#frm_destructionyear').val(),
            'documentnumber': $('#frm_documentnumber').val(),
            'boxnumber': $('#frm_boxnumber').val(),
            'containernumber': $('#frm_containernumber').val(),
            'name': $('#frm_name').val(),
            'limitdatemin': $('#frm_limitdatemin').val(),
            'limitdatemax': $('#frm_limitdatemax').val(),
            'limitnummin': $('#frm_limitnummin').val(),
            'limitnummax': $('#frm_limitnummax').val(),
            'limitalphamin': $('#frm_limitalphamin').val(),
            'limitalphamax': $('#frm_limitalphamax').val(),
            'limitalphanummin': $('#frm_limitalphanummin').val(),
            'limitalphanummax': $('#frm_limitalphanummax').val(),
            'localization': ( $("#frm_localization option:selected").val() != "" )?parseInt($("#frm_localization option:selected").val()):-1,
            'localizationfree': $('#frm_localizationfree').val(),
            'unlimited': $('#frm_unlimited').prop('checked')?1:0,
            'commentsunlimited': currentCommentsUnlimited
        };

        $.ajax({
            type: "GET",
            url: window.JSON_URLS.bs_idp_archive_modify_ajax,
            data: $dataObject,
            cache: false,
            success: function($response) {
                $('#viewArchive').hide();
                $('#listarchives').bootstrapTable('refresh');
                $('#divPrint').show();
                if ($_buttonsOverlay & 16)
                    $('#divPrintTag').show();

            },
            error: function (xhr, ajaxOptions, thrownError) {
                $message = xhr.responseJSON;
                bootbox.alert( {
                    message: $message?$message.message:$_translations[147],
                    className: "boxSysErrorOne"
                });
                $('#viewArchive').hide();
            }
        });
    } else {
        $('#modalErrorMessage').modal( 'show' );
    }
    return true;
};

//....................................................................................................
// This function verifies all mandatory fields of the provider connector popup dialog
// Set to has-error or has-success depending for each field
function verifyPCBMandatories() {
    $retour = true;

    $text = $('#frm_pcb_contact').val();
    if( $text.trim() == '' ){
        $retour = false;
        $('#div_pcb_contact').addClass('has-error');
    } else
        $('#div_pcb_contact').addClass('has-success');

    $text = $('#frm_pcb_phone').val();
    if( $text.trim() == '' ){
        $retour = false;
        $('#div_pcb_phone').addClass('has-error');
    } else
        $('#div_pcb_phone').addClass('has-success');

    $text = $('#frm_pcb_address').val();
    if( $text.trim() == '' ){
        $retour = false;
        $('#div_pcb_address').addClass('has-error');
    } else
        $('#div_pcb_address').addClass('has-success');

    if( $uawhat == UAWHAT_DESTROY ) {
        $text = $('#frm_pcb_name').val();
        if ($text.trim() == '') {
            $retour = false;
            $('#div_pcb_name').addClass('has-error');
        } else
            $('#div_pcb_name').addClass('has-success');

        $text = $('#frm_pcb_firstname').val();
        if ($text.trim() == '') {
            $retour = false;
            $('#div_pcb_firstname').addClass('has-error');
        } else
            $('#div_pcb_firstname').addClass('has-success');

        $text = $('#frm_pcb_function').val();
        if ($text.trim() == '') {
            $retour = false;
            $('#div_pcb_function').addClass('has-error');
        } else
            $('#div_pcb_function').addClass('has-success');
    }

    return $retour;
}
//....................................................................................................
// This function cleans the PCB dialog for each field with error colours
function clearPCBView() {
    $('#div_pcb_contact').removeClass('has-success');
    $('#div_pcb_contact').removeClass('has-error');
    $('#div_pcb_phone').removeClass('has-success');
    $('#div_pcb_phone').removeClass('has-error');
    $('#div_pcb_address').removeClass('has-success');
    $('#div_pcb_address').removeClass('has-error');
    $('#div_pcb_name').removeClass('has-success');
    $('#div_pcb_name').removeClass('has-error');
    $('#div_pcb_firstname').removeClass('has-success');
    $('#div_pcb_firstname').removeClass('has-error');
    $('#div_pcb_function').removeClass('has-success');
    $('#div_pcb_function').removeClass('has-error');
}

function verifyMandatories() {
    clearViewClass();

    $retour = true;
    if( $('#frm_service option:selected').val() == '' ){
        $retour = false;
        $('#divViewSelectService').addClass('has-error');
    } else {
        $('#divViewSelectService').addClass('has-success');
    }
    if( $('#frm_legalentity option:selected').val() == '' ){
        $retour = false;
        $('#divViewSelectLegalentity').addClass('has-error');
    } else {
        $('#divViewSelectLegalentity').addClass('has-success');
    }
    if( $('#frm_name').val().trim() == ''){
        $retour = false;
        $('#form_name').addClass('has-error');
    } else {
        $('#form_name').addClass('has-success');
    }
    if( $('#frm_closureyear').val().trim() == '' ){
        $retour = false;
        $('#divViewInputClosureyear').addClass('has-error');
    } else {
        $('#divViewInputClosureyear').addClass('has-success');
    }
    if( $('#frm_destructionyear').val().trim() == ''){
        $retour = false;
        $('#divViewInputDestructionyear').addClass('has-error');
    } else {
        // verify only if destruction year is editable
        $documenttypeIdx = parseInt( $("#frm_documenttype option:selected").attr('data') );
        $destructionTime = $documenttypeIdx?$documentTypes[$documenttypeIdx][DOCUMENTTYPE_KEEPALIVEDURATION]:0;

        // If destruction time is 0, user should enter whatever he wants, instead destruction time is forced
        if($destructionTime == 0) {
            if (parseInt($('#frm_destructionyear').val().trim()) > 2199) {
                $retour = false;
                popError( $('#frm_destructionyear'), $_translations[161], 'top' );
                $('#divViewInputDestructionyear').addClass('has-error');
            } else
                $('#divViewInputDestructionyear').addClass('has-success');
        } else
            $('#divViewInputDestructionyear').addClass('has-success');
    }
    if( $_settings.view_budgetcode && $_settings.mandatory_budgetcode && $('#frm_budgetcode option:selected').val() == '' ){
        $retour = false;
        $('#divViewSelectBudgetcode').addClass('has-error');
    } else {
        $('#divViewSelectBudgetcode').addClass('has-success');
    }
    if( $_settings.view_documentnature && $_settings.mandatory_documentnature && $('#frm_documentnature option:selected').val() == '' ){
        $retour = false;
        $('#divViewSelectDocumentnature').addClass('has-error');
    } else {
        $('#divViewSelectDocumentnature').addClass('has-success');
    }
    if( $_settings.view_documentnature && $_settings.view_documenttype && $_settings.mandatory_documenttype && $('#frm_documenttype option:selected').val() == '' ){
        $retour = false;
        $('#divViewSelectDocumenttype').addClass('has-error');
    } else {
        $('#divViewSelectDocumenttype').addClass('has-success');
    }
    if( $_settings.view_description1 && $_settings.mandatory_description1 && $('#frm_description1 option:selected').val() == '' ){
        $retour = false;
        $('#divViewSelectDescription1').addClass('has-error');
    } else {
        $('#divViewSelectDescription1').addClass('has-success');
    }
    if( $_settings.view_description2 && $_settings.mandatory_description2 && $('#frm_description2 option:selected').val() == '' ){
        $retour = false;
        $('#divViewSelectDescription2').addClass('has-error');
    } else {
        $('#divViewSelectDescription2').addClass('has-success');
    }
    $test1 = /^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/.test( $('#frm_limitdatemin').val() );
    $test2 = /^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/.test( $('#frm_limitdatemax').val() );
    $test_RegexValidDate_DateMin = ($('#frm_limitdatemin').val() == '')?true:$test1;
    $test_RegexValidDate_DateMax = ($('#frm_limitdatemax').val() == '')?true:$test2;
    $empty1 = $('#frm_limitdatemin').val().trim() == '';
    $empty2 = $('#frm_limitdatemax').val().trim() == '';
    if( ( $_settings.mandatory_limitsdate && ( $empty1 || $empty2 ) ) ||
        ( !$_settings.mandatory_limitsdate && ( ( !$empty1 && $empty2 ) || ( $empty1 && !$empty2 ) ) ) ||
        ( !$test_RegexValidDate_DateMin || !$test_RegexValidDate_DateMax )){
        $retour = false;
        if( $empty1 || $test_RegexValidDate_DateMin == false ){
            $('#divViewInputLimitsdatemin').addClass('has-error');
        } else {
            $('#divViewInputLimitsdatemin').addClass('has-success');
        }
        if( $empty2 || $test_RegexValidDate_DateMax == false ){
            $('#divViewInputLimitsdatemax').addClass('has-error');
        } else {
            $('#divViewInputLimitsdatemax').addClass('has-success');
        }
    } else {
        $('#divViewInputLimitsdatemin').addClass('has-success');
        $('#divViewInputLimitsdatemax').addClass('has-success');
    }
    $test1 = /^[0-9]+$/.test( $('#frm_limitnummin').val() );
    $test2 = /^[0-9]+$/.test( $('#frm_limitnummax').val() );
    $test_RegexOnlyNumber_NumMin = ($('#frm_limitalphamin').val() == '')?true:$test1;
    $test_RegexOnlyNumber_NumMax = ($('#frm_limitalphamax').val() == '')?true:$test2;
    $empty1 = $('#frm_limitalphamin').val().trim() == '';
    $empty2 = $('#frm_limitalphamax').val().trim() == '';
    if( ( $_settings.mandatory_limitsnum && ( $empty1 && $empty2 ) ) ||
        ( !$_settings.mandatory_limitsnum && ( ( !$empty1 && $empty2 ) || ( $empty1 && !$empty2 ) ) ) ||
        ( !$test_RegexOnlyNumber_NumMin || !$test_RegexOnlyNumber_NumMax )){
        $retour = false;
        if( $empty1 || $test_RegexOnlyNumber_NumMin == false ){
            $('#divViewInputLimitsnummin').addClass('has-error');
        } else {
            $('#divViewInputLimitsnummin').addClass('has-success');
        }
        if( $empty2 || $test_RegexOnlyNumber_NumMax == false ){
            $('#divViewInputLimitsnummax').addClass('has-error');
        } else {
            $('#divViewInputLimitsnummax').addClass('has-success');
        }
    } else {
        $('#divViewInputLimitsnummin').addClass('has-success');
        $('#divViewInputLimitsnummax').addClass('has-success');
    }
    $test1 = /^[a-zA-Z]+$/.test( $('#frm_limitalphamin').val() );
    $test2 = /^[a-zA-Z]+$/.test( $('#frm_limitalphamax').val() );
    $test_RegexOnlyChar_AlphaMin = ($('#frm_limitalphamin').val() == '')?true:$test1;
    $test_RegexOnlyChar_AlphaMax = ($('#frm_limitalphamax').val() == '')?true:$test2;
    $empty1 = $('#frm_limitalphamin').val().trim() == '';
    $empty2 = $('#frm_limitalphamax').val().trim() == '';
    if( ( $_settings.mandatory_limitsalpha && ( $empty1 && $empty2 ) ) ||
        ( !$_settings.mandatory_limitsalpha && ( ( !$empty1 && $empty2 ) || ( $empty1 && !$empty2 ) ) ) ||
        ( !$test_RegexOnlyChar_AlphaMin || !$test_RegexOnlyChar_AlphaMax ) ){
        $retour = false;
        if( $empty1 || $test_RegexOnlyChar_AlphaMin == false ){
            $('#divViewInputLimitsalphamin').addClass('has-error');
        } else {
            $('#divViewInputLimitsalphamin').addClass('has-success');
        }
        if( $empty2 || $test_RegexOnlyChar_AlphaMax == false ){
            $('#divViewInputLimitsalphamax').addClass('has-error');
        } else {
            $('#divViewInputLimitsalphamax').addClass('has-success');
        }
    } else {
        $('#divViewInputLimitsalphamin').addClass('has-success');
        $('#divViewInputLimitsalphamax').addClass('has-success');
    }
    $empty1 = $('#frm_limitalphanummin').val().trim() == '';
    $empty2 = $('#frm_limitalphanummax').val().trim() == '';
    if( ( $_settings.mandatory_limitsalphanum && ( $empty1 && $empty2 ) ) ||
        ( !$_settings.mandatory_limitsalphanum && ( ( !$empty1 && $empty2 ) || ( $empty1 && !$empty2 ) ) ) ) {
        $retour = false;
        if( $empty1 ){
            $('#divViewInputLimitsalphanummin').addClass('has-error');
        } else {
            $('#divViewInputLimitsalphanummin').addClass('has-success');
        }
        if( $empty2 ){
            $('#divViewInputLimitsalphanummax').addClass('has-error');
        } else {
            $('#divViewInputLimitsalphanummax').addClass('has-success');
        }
    } else {
        $('#divViewInputLimitsalphanummin').addClass('has-success');
        $('#divViewInputLimitsalphanummax').addClass('has-success');
    }
    if( $_settings.view_filenumber && $_settings.mandatory_filenumber && $('#frm_documentnumber').val().trim() == '' ){
        $retour = false;
        $('#divViewInputFilenumber').addClass('has-error');
    } else {
        $('#divViewInputFilenumber').addClass('has-success');
    }
    if( $_settings.view_boxnumber && $_settings.mandatory_boxnumber && $('#frm_boxnumber').val().trim() == '' ){
        $retour = false;
        $('#divViewInputBoxnumber').addClass('has-error');
    } else {
        $('#divViewInputBoxnumber').addClass('has-success');
    }
    if( $_settings.view_containernumber && $_settings.mandatory_containernumber && $('#frm_containernumber').val().trim() == '' ){
        $retour = false;
        $('#divViewInputContainernumber').addClass('has-error');
    } else {
        $('#divViewInputContainernumber').addClass('has-success');
    }
    if( $_settings.view_provider && $_settings.mandatory_provider && $('#frm_provider option:selected').val() == '' ){
        $retour = false;
        $('#divViewSelectProvider').addClass('has-error');
    } else {
        $('#divViewSelectProvider').addClass('has-success');
    }
    if( true /* $uawhere == UAWHERE_PROVIDER */ ){ // Only in provider mode here
        if( $('#frm_localization option:selected').val() == '' ){
            $retour = false;
            $('#divViewSelectLocalization').addClass('has-error');
        } else {
            $('#divViewSelectLocalization').addClass('has-success');
        }
    } else {
        if( $('#frm_localizationfree').val().trim() == '' ){
            $retour = false;
            $('#divViewInputLocalizationfree').addClass('has-error');
        } else {
            $('#divViewInputLocalizationfree').addClass('has-success');
        }
    }
    return $retour;
};

// -----------------------------------------------------------------------
// Provider Connector

function showComplementaryProviderConnectorModal(){
    // Set all to unvisible
    $('#div_frm_deliver').hide();
    $('#div_frm_type').hide();
    $('#div_frm_type2').hide();
    $('#div_frm_disposal').hide();
    $('#div_frm_sign').hide();
    $('#frm_pcb_type1_1').prop('disabled', false );
    $('#frm_pcb_type1_2').prop('disabled', false );

    $bRelocConnectorConsult = false;
    // verify if we are in a Reloc Consult type
    $actionList.forEach( function( $elem ){
       if( $.inArray( $elem['statuscaps'], RELOC_CONNECTOR_CONSULT ) >= 0 )
           $bRelocConnectorConsult = true;
    });

    switch( $uawhat ){
        case UAWHAT_TRANSFER:
            break;
        case UAWHAT_CONSULT: // = Consultation
            $('#div_frm_deliver').show();
            $('#div_frm_type').show();
            $('#div_frm_disposal').show();
            $('#frm_pcb_type1_1').prop('checked', true );
            $('#frm_pcb_type1_2').prop('checked', false );
            $('#frm_pcb_type1_1').prop('disabled', true );
            $('#frm_pcb_type1_2').prop('disabled', true );
            break;
        case UAWHAT_RETURN:
            break;
        case UAWHAT_EXIT:
            $('#div_frm_deliver').show();
            $('#div_frm_type').show();
            $('#frm_pcb_type1_1').prop('checked', false );
            $('#frm_pcb_type1_2').prop('checked', true );
            $('#frm_pcb_type1_1').prop('disabled', true );
            $('#frm_pcb_type1_2').prop('disabled', true );
            break;
        case UAWHAT_DESTROY:
            $('#div_frm_type2').show();
            $('#div_frm_sign').show();
            break;
        case UAWHAT_RELOC:
            if( $bRelocConnectorConsult ) {  // It is like a consult
                $('#div_frm_deliver').show();
                $('#div_frm_type').show();
                $('#div_frm_disposal').show();
                $('#frm_pcb_type1_1').prop('checked', true);
                $('#frm_pcb_type1_2').prop('checked', false);
                $('#frm_pcb_type1_1').prop('disabled', true);
                $('#frm_pcb_type1_2').prop('disabled', true);

                // E#311
                $('#frm_pcb_disposal_1').prop('checked', true);
                $('#frm_pcb_disposal_1').prop('disabled', true);
                $('#frm_pcb_disposal_2').prop('disabled', true);
                $('#frm_pcb_disposal_3').prop('disabled', true);
                $('#frm_pcb_disposal_4').prop('disabled', true);
            }
            break;
        default:
            return true;
    }
    $('#ProviderConnectorModal').modal({
        backdrop: 'static',             // Don't close if click in dark
        keyboard: true
    });
    /*
    $('#ProviderConnectorModal').on('hidden.bs.modal', function () {
        // do something…
    });*/
    clearPCBView();
    $('#ProviderConnectorModal').modal('show');
    return true;
};

function updateProviderConnectorForm( $data ){
    $pcb = $data[0];

    if( $pcb['contact'] != null )
        $('#frm_pcb_contact').val( $pcb['contact'] );
    if( $pcb['phone'] != null )
        $('#frm_pcb_phone').val( $pcb['phone'] );
    if( $pcb['address'] != null )
        $('#frm_pcb_address').val( $pcb['address'] );
    if( $pcb['deliver'] != null ){
        if( $pcb['deliver'] == 0 )
            $('#frm_pcb_deliver_1').prop('checked', true);
        if( $pcb['deliver'] == 1 )
            $('#frm_pcb_deliver_2').prop('checked', true);
    }
    if( $pcb['type'] != null ){
        if( $pcb['type'] == 0 )
            $('#frm_pcb_type1_1').prop('checked', true);
        if( $pcb['type'] == 1 )
            $('#frm_pcb_type1_2').prop('checked', true);
    }
    if( $pcb['type2'] != null ){
        if( $pcb['type2'] == 0 )
            $('#frm_pcb_type2_1').prop('checked', true);
        if( $pcb['type2'] == 1 )
            $('#frm_pcb_type2_2').prop('checked', true);
    }
    if( $pcb['disposal'] != null ){
        if( $pcb['disposal'] == 0 )
            $('#frm_pcb_disposal_1').prop('checked', true);
        if( $pcb['disposal'] == 1 )
            $('#frm_pcb_disposal_2').prop('checked', true);
        if( $pcb['disposal'] == 2 )
            $('#frm_pcb_disposal_3').prop('checked', true);
        if( $pcb['disposal'] == 3 )
            $('#frm_pcb_disposal_4').prop('checked', true);
    }
    if( $pcb['remark'] != null )
        $('#frm_pcb_remark').html( $pcb['remark'] );
    if( $pcb['name'] != null )
        $('#frm_pcb_name').val( $pcb['name'] );
    if( $pcb['firstname'] != null )
        $('#frm_pcb_firstname').val( $pcb['firstname'] );
    if( $pcb['function'] != null )
        $('#frm_pcb_function').val( $pcb['function'] );
}



$( "#OptimisationModal" ).on( "hidden.bs.modal", function( event, ui ) {
    // Send ajax to unlock uas optimized
    ungray_UAs_Optimized();
} );
$( "#DelOptimisationModal" ).on( "hidden.bs.modal", function( event, ui ) {
    // Send ajax to unlock uas optimized
    ungray_UAs_Optimized();
} );
//=====================================================================================================
// Optimization Popup Rendering
const DEBUG_OPTIM_RENDERING = false;
//-----------------------------------------------------------------------------------------------------
// Function: updateVerificationStruct
// Goal: update verification structure
// Parameters:
//      $UALine: UALine to analyze
//      $verificationStruct: Struct to modify,
//      $alreadyCheckedListID: List of ID user put in basket
//      $statusesAllowedToBeChecked: List of status checkable
// Returns:
//      Nothing

function updateVerificationStruct( $UALine, $verificationStruct, $alreadyCheckedListID, $statusesAllowedToBeChecked ){
    if( $UALine['containerasked'] > 1 )     // 1 is for basket only not mandatory
        $verificationStruct.bContainerAsked = true;
    if( $UALine['boxasked'] > 1 )
        $verificationStruct.bBoxAsked = true;

    if( $verificationStruct.bContainerAsked || $verificationStruct.bBoxAsked ){
        $verificationStruct.bCheck = true;
        $verificationStruct.bDisable = true;
    } else {
        if( $alreadyCheckedListID.indexOf($UALine['id']) >= 0 )
            $verificationStruct.bCheck = true;
        else
            $verificationStruct.bAllChecked = false;
    }

    if( $.inArray($UALine['statuscaps'], $statusesAllowedToBeChecked) < 0 )
        $verificationStruct.bDisable = true;

    return $verificationStruct;
}
//-----------------------------------------------------------------------------------------------------
// Function: generateUALineFlow
// Goal: Create UA to update current flow
// Parameters:
//      $UALine: UALine to analyze
//      $alreadyCheckedListID: List of ID user put in basket
//      $statusesAllowedToBeChecked: List of status checkable
//      $verificationStruct: obvious,
// Returns: <span class="list-group-item text-COLOR"><input type="checkbox" id="cbID" value="ID" CHECKED="CHECKED"
//  DISABLED="DISABLED">TEXT OF UA</span>

function generateUALineFlow( $UALine, $alreadyCheckedListID, $statusesAllowedToBeChecked, $verificationStruct,
                             $entityType ){
    var $returnContent = '<span class="list-group-item ';
    // Colors ==> primary: if in basket / default: if in optimization and checkable / danger: otherwise
    $returnContent += ($alreadyCheckedListID.indexOf($UALine['id']) >= 0) ?
        'text-primary' :
        ($.inArray($UALine['statuscaps'], $statusesAllowedToBeChecked) >= 0) ?
            'text-default' : 'text-danger';
    $returnContent += '"><input type="checkbox" id="cb' + $UALine['id'] + '" value="' + $UALine['id'] + '"  ';
    // Checkbox state management
    if( $verificationStruct.bCheck )
        $returnContent += ' checked="checked" ';
    if( $verificationStruct.bDisable )
        $returnContent += ' disabled="disabled" ';

    $returnContent += '/>&nbsp;';

    // Generate label for this line
    let $bFirst = true;
    if( $UALine['boxnumber'] != null && $UALine['boxnumber'].length > 0 && $entityType <= 1 ){
        $bFirst = false;
        $returnContent += $_translations[163] + ' ' + $UALine['boxnumber'];
    }
    if( $UALine['documentnumber'] != null && $UALine['documentnumber'].length > 0 && $entityType <= 3 ){
        if( $bFirst )
            $bFirst = false;
        else
            $returnContent += ' / ';

        $returnContent += $_translations[164] + ' ' + $UALine['documentnumber'];
    }
    if( $bFirst )
        $bFirst = false;
    else
        $returnContent += ' / ';
    $returnContent += $_translations[165] + ': ' + $UALine['name'];

    // If not checkable, add Status to label
    if( !$verificationStruct.bCheck && $.inArray( $UALine['statuscaps'], $statusesAllowedToBeChecked ) < 0 )
        $returnContent += ' [' + $UALine['status'] + ']';
    $returnContent += '</span>';

    return $returnContent;
}

//-----------------------------------------------------------------------------------------------------
// Function: createEmptyEntity
// Goal: This function returns a empty entity object
function createEmptyEntity( ){
    var $emptyStruct = {
        identification : null,
        content : '',
        headerBegin : '',
        headerEnd: '',
        scriptBegin: '',
        toggleOn: '',
        toggleOff: '',
        entityType: 0,
        $checkboxID: ''
    };
    return $emptyStruct;
}
//-----------------------------------------------------------------------------------------------------
// Function: reinitVerificationStruct
// Goal: This function initialise verificationStruct
function reinitVerificationStruct( $verificationStruct ){
    $verificationStruct.bCheck = false;
    $verificationStruct.bDisable = false;
    $verificationStruct.bAllChecked = true;
    $verificationStruct.bContainerAsked = false;
    $verificationStruct.bBoxAsked = false;

    return $verificationStruct;
}

//-----------------------------------------------------------------------------------------------------
// Function: initializeEntity
// Goal: This function initialize entity object depending on entity type and UALine
// $entityType = 1 ==> Container / $entityType = 2 ==> Box / $entityType = 3 ==> subbox / $entityType = 4 ==> ROA
function initializeEntity( $entity, $entityType, $UALine, $verificationStruct  ) {
    if( $entity.identification || ( $.inArray($entityType, [1,2,3,4]) < 0 ))    // Only with a empty entity
        return null;

    $entity.identification = $entityType == 1 ? $UALine['containernumber'] : ( $entityType == 2 || $entityType == 3 ) ?
        $UALine['boxnumber'] : 'ROA';
    $entity.entityType = $entityType;

    var $nameTrimed = '';
    var $panelID = '';
    var $panelHeadID = '';
    var $panelCheckboxID = '';
    var $panelCollapseID = '';
    var $nameIdentification = '';
    var $valueCheckBox = -1;

    // Container
    if( $entityType == 1 ){
        $nameTrimed = $UALine['containernumber'].replace(/\s+/g, '') + '_' + $UALine['suid'];
        $panelID = 'pContainer_' + $nameTrimed;
        $panelHeadID = 'phContainer_' + $nameTrimed;
        $panelCheckboxID = 'C_' + $nameTrimed;
        $panelCollapseID = 'pcContainer_List_' + $nameTrimed;
        $nameIdentification = ' ' + $_translations[162] + ' ' + $entity.identification;

    // Box
    } else if( $entityType == 2 ){
        $nameTrimed = $UALine['boxnumber'].replace(/\s+/g, '') + '_' + $UALine['suid'];
        $panelID = 'pBox_' + $nameTrimed;
        $panelHeadID = 'phBox_' + $nameTrimed;
        $panelCheckboxID = 'C_' + $nameTrimed;
        $panelCollapseID = 'pcBox_List_' + $nameTrimed;
        $nameIdentification = ' ' + $_translations[163] + ' ' + $entity.identification;

    // Sub box
    } else if( $entityType == 3 ){
        $nameTrimed = $UALine['containernumber'].replace(/\s+/g, '') + '_' + $UALine['suid'] + '_SB_' +
            $UALine['boxnumber'];
        $panelID = 'pContainer_' + $nameTrimed;
        $panelHeadID = 'phContainer_' + $nameTrimed;
        $panelCheckboxID = 'C_' + $nameTrimed;
        $panelCollapseID = 'pcContainer_List_' + $nameTrimed;
        $nameIdentification = ' ' + $_translations[163] + ' ' + $entity.identification;
        $valueCheckBox = -2;

    // Other UAs
    } else {
        $panelID = 'pRoa';
        $panelHeadID = 'phRoa';
        $panelCollapseID = 'pcRoa_List';
        $nameIdentification = ' ' + $_translations[166] + ' ';

    }

    $entity.checkboxID = $panelCheckboxID;

    $entity.headerBegin = '<div class="panel panel-default mb5" id="' + $panelID + '"> '
        + '<div class="panel-heading" role="tab" id="' + $panelHeadID + '" > '
        + '<h4 class="panel-title">';
    if( $entityType != 4 )
        $entity.headerBegin += '<input type="checkbox" id="' + $panelCheckboxID + '" value="'+$valueCheckBox+'" ';

    $entity.headerEnd = ($entityType != 4) ? '/>' : '';
    $entity.headerEnd += '<a class="' + ( !$verificationStruct.bFirst ? 'collapsed' : '' )
        + '"  data-toggle="collapse" data-parent="#OptimisationModalBody" href="#' + $panelCollapseID
        + '" aria-expanded="' + ( $verificationStruct.bFirst ? 'true' : 'false' )
        + '" aria-controls="' + $panelCollapseID + '">'
        + $nameIdentification + '</a></h4></div>'
        + '<div id="' + $panelCollapseID + '" class="panel-collapse collapse list-group p2 '
        + ( $verificationStruct.bFirst ? 'in' : '' )
        + '" role="tabpanel" aria-labelledby="' + $panelHeadID + '">';

    if( $entityType != 4 )
        $entity.scriptBegin = "<script>$('#" + $panelCheckboxID + "').change(function(){ if( this.checked ){ ";

    return $entity;
}

//-----------------------------------------------------------------------------------------------------
// Function: closeAndRenderEntity
// Goal:
//      This function returns a renderer for a given entity structure
function closeAndRenderEntity( $entity, $verificationStruct, $delMode ){
    var $returnContent = '';

    $returnContent += $entity.headerBegin;

    if( $entity.entityType != 4 )
        $returnContent += /*$verificationStruct.bContainerAsked || $verificationStruct.bBoxAsked ||*/ $delMode ?
            ' checked="checked" disabled="disabled" ' :
            $verificationStruct.bAllChecked ? ' checked="checked" ' : '';
    else
        if( $delMode ) $returnContent += ' disabled="disabled" ';

    $returnContent += $entity.headerEnd;
    $returnContent += $entity.content + '</div></div>';

    // No Script for ROA, or for all if in delMode
    if( $entity.entityType != 4 && !$delMode)
        $returnContent += $entity.scriptBegin + $entity.toggleOn + ' } else { ' + $entity.toggleOff + ' } });</script>';

    return $returnContent;
}

//-----------------------------------------------------------------------------------------------------
// Function: createToggleScript
// Goal:
//      This function returns javascript code to toggle on/off this UA
function createToggleScript( $UALine, $bOnOff, $statusesAllowedToBeChecked, $alreadyCheckedListID ) {
    var $toggle = '';
    if( $bOnOff ){  // This is for toggleOn
        if( $.inArray( $UALine['statuscaps'], $statusesAllowedToBeChecked ) >= 0 )
            $toggle = " $('#cb" + $UALine['id'] + "').prop('checked', true); ";
        $toggle += " $('#cb" + $UALine['id'] + "').prop('disabled', true); ";
    } else { // This is for toggleOff
        if( $alreadyCheckedListID.indexOf( $UALine['id'] ) < 0 )
            $toggle = " $('#cb" + $UALine['id'] + "').prop('checked', false); ";
        $toggle += " $('#cb" + $UALine['id'] + "').prop('disabled', false); ";
    }
    return $toggle;
}

//-----------------------------------------------------------------------------------------------------
// Function: createContainerSubBoxScript
// Goal:
//      This function returns javascript code to manage subboxes when container is checked/unchecked
function createContainerSubBoxScript( $subBoxEntity, $bOnOff ) {
    var $toggle = '';
    if( $bOnOff ) {  // This is for toggleOn of Container
        //$toggle = " $('#" + $subBoxEntity.checkboxID + "').prop('checked', false); ";
        $toggle += " $('#" + $subBoxEntity.checkboxID + "').prop('disabled', true); ";
    } else { // This is for toggleOff of Container
        $toggle = " $('#" + $subBoxEntity.checkboxID + "').prop('disabled', false); ";
    }
    return $toggle;
}

//-----------------------------------------------------------------------------------------------------
// Function: updatePopupOptimizationModal_v2 (main function)
// Goal:
//      This function renders the Optimization modal popup
// Parameters:
//      $optimizedList: List of UAs needed for optimization (calculated by the server), we assume that server returns
//          this list sorted by container, then boxes, then documents, then service,
//      $alreadyCheckedListID: List of IDs of UAs selected by the user
//      $delMode: true or false
// Returns:
//      Nothing
// Remarks:
//      Version 02 ( Take care of Box in Container also + Algorythm rewrite
//      updateVerificationStruct() is called just before generateUALineFlow in order to use previous struct when
//          closing box or container
// Algorythm:
//      Parse each line one after one, if it's a container, add it to current one, if not and if it's a box, add it to
//      current one, otherwise add it to none optimizable UAs
//      Also take care of scripts (check), also take care of checkables

function updatePopupOptimizationModal_v2( $optimizedList, $alreadyCheckedListID, $delMode ) {
    if( DEBUG_OPTIM_RENDERING ) console.log( 'ENTER updatePopupOptimizationModal_v2' );
    // Verification structure to determine behavior along parsing
    var $containerVerifStruct = {
        bCheck: false,
        bDisable: false,
        bAllChecked: true,
        bContainerAsked: false,
        bBoxAsked: false,       // unused for container
        bFirst: true
    };
    var $boxVerifStruct = {
        bCheck: false,
        bDisable: false,
        bAllChecked: true,
        bContainerAsked: false, // unused for box
        bBoxAsked: false,
        bFirst: true
    };
    var $otherVerifStruct = {
        bCheck: false,
        bDisable: false,
        bAllChecked: true,
        bContainerAsked: false, // unused for other
        bBoxAsked: false,   // unused for other
        bFirst: true
    };

    var $mainContent = '';
    var $currentSUID = null;
    var $currentContainer = createEmptyEntity();
    var $currentSubBox = createEmptyEntity();
    var $currentBox = createEmptyEntity();
    var $otherUAs = createEmptyEntity();


    // TODO Repenser l'algorithme en ajoutant les lignes à une structure plus grande, et en faisant le rendu uniquement
    //  en cloture de cette structure et en propageant à la structure du dessus; sinon on ne peut pas gérer les checkbox
    //  correctement
    // document seul ==> ok
    // boite avec documents dedans ==> ok
    // boite seule ==> ne fonctionne pas
    // conteneur avec boite sans document ==> ne fonctionne pas ==> boite = document !!
    // conteneur avec boite avec document ==> ne fonctionne pas
    // conteneur sans boite sans document ==> ne fonctionne pas

    //
    if( !$delMode )
        var $statusesAllowedToBeChecked = ['DISP', 'GLAP', 'GPAP', 'GRLPDAI', 'GRLPDAINT', 'CLAP', 'CPAP', 'CRLPDAI', 'CRLPDAINT'];
    else
        var $statusesAllowedToBeChecked = ['DISP', 'GDAP', 'CDAP'];

    for (let $i = 0, $len = $optimizedList.length; $i < $len; $i++) {
        // Extract current line
        let $UALine = $optimizedList[$i];
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'Current Line: ' + $i );
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $UALine );
        // Verify if this line concerns a container
        if ($UALine['containernumber'] != null) {
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'We are in a Container !' );
            // Verify if we are in the same container
            if ($UALine['containernumber'] == $currentContainer.identification && $UALine['suid'] == $currentSUID) {
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'Same Container than before !' );
                // Verify if we are in a box
                if ($UALine['boxnumber'] != null) {
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'We are in a subBox of Container !' );
                    // Verify if we are in the same subbox
                    if ($currentSubBox.identification == $UALine['boxnumber']) {
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'Same subBox, so generate Line Flow in currentSubBox.content' );
                        $boxVerifStruct = updateVerificationStruct($UALine, $boxVerifStruct, $alreadyCheckedListID,
                            $statusesAllowedToBeChecked );
                        $containerVerifStruct = updateVerificationStruct($UALine, $containerVerifStruct,
                            $alreadyCheckedListID, $statusesAllowedToBeChecked );

                        $currentSubBox.content += generateUALineFlow($UALine, $alreadyCheckedListID,
                            $statusesAllowedToBeChecked, $boxVerifStruct, 3);
                        // Add script to SubBox And Container
                        $currentSubBox.toggleOn += createToggleScript( $UALine, true,
                            $statusesAllowedToBeChecked, $alreadyCheckedListID );
                        $currentSubBox.toggleOff += createToggleScript( $UALine, false,
                            $statusesAllowedToBeChecked, $alreadyCheckedListID );
                        $currentContainer.toggleOn += createToggleScript( $UALine, true,
                            $statusesAllowedToBeChecked, $alreadyCheckedListID );
                        $currentContainer.toggleOff += createToggleScript( $UALine, false,
                            $statusesAllowedToBeChecked, $alreadyCheckedListID );
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $currentSubBox  );
                    } else {
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'This is a new subBox !' );
                        // We changed subbox, close old one (if exists) create new one, and add to content
                        if ($currentSubBox.identification) {
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'There was a previous one, so close it first, render it inside currentContainer.content !' );
                            $currentContainer.content += closeAndRenderEntity($currentSubBox, $boxVerifStruct, $delMode);
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $currentContainer.content  );
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'Create a new empty subBox, and initialize it !' );
                            $currentSubBox = createEmptyEntity();
                            $currentSubBox = initializeEntity($currentSubBox, 3, $UALine, $boxVerifStruct );
                            // Add script to Container
                            $currentContainer.toggleOn += createContainerSubBoxScript( $currentSubBox, true );
                            $currentContainer.toggleOff += createContainerSubBoxScript( $currentSubBox, false );
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $currentSubBox );
                        }
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'Generate Line flow in current subbox.content' );
                        $boxVerifStruct = reinitVerificationStruct( $boxVerifStruct);
                        $boxVerifStruct = updateVerificationStruct($UALine, $boxVerifStruct, $alreadyCheckedListID,
                            $statusesAllowedToBeChecked );
                        $containerVerifStruct = updateVerificationStruct($UALine, $containerVerifStruct,
                            $alreadyCheckedListID, $statusesAllowedToBeChecked );

                        $currentSubBox.content += generateUALineFlow($UALine, $alreadyCheckedListID,
                            $statusesAllowedToBeChecked, $boxVerifStruct, 3);
                        // Add script to SubBox And Container
                        $currentSubBox.toggleOn += createToggleScript( $UALine, true,
                            $statusesAllowedToBeChecked, $alreadyCheckedListID );
                        $currentSubBox.toggleOff += createToggleScript( $UALine, false,
                            $statusesAllowedToBeChecked, $alreadyCheckedListID );
                        $currentContainer.toggleOn += createToggleScript( $UALine, true,
                            $statusesAllowedToBeChecked, $alreadyCheckedListID );
                        $currentContainer.toggleOff += createToggleScript( $UALine, false,
                            $statusesAllowedToBeChecked, $alreadyCheckedListID );
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $currentSubBox  );
                    }
                } else {
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'This is a simple line, so render it in current container' );
                    // This is just a simple line, close last subbox before adding a line to the container
                    if ($currentSubBox.identification) {
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'Last subbox is not rendered yet, so do it in currentContainer.content' );
                        $currentContainer.content += closeAndRenderEntity($currentSubBox, $boxVerifStruct, $delMode);
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $currentContainer.content  );
                        $currentSubBox = createEmptyEntity();
                    }
                    $containerVerifStruct = updateVerificationStruct($UALine, $containerVerifStruct,
                        $alreadyCheckedListID, $statusesAllowedToBeChecked );

                    $currentContainer.content += generateUALineFlow($UALine, $alreadyCheckedListID,
                        $statusesAllowedToBeChecked, $containerVerifStruct, 1);
                    // Add script to Container
                    $currentContainer.toggleOn += createToggleScript( $UALine, true,
                        $statusesAllowedToBeChecked, $alreadyCheckedListID );
                    $currentContainer.toggleOff += createToggleScript( $UALine, false,
                        $statusesAllowedToBeChecked, $alreadyCheckedListID );
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $currentContainer  );
                }

            } else {
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'This is not the same container than before' );
                // We changed container
                // Close subbox if one
                if ($currentSubBox.identification) {
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'There is a not closed subbox in the last one, so render it first in currentContainer.content' );
                    $currentContainer.content += closeAndRenderEntity($currentSubBox, $boxVerifStruct, $delMode);
                    $currentSubBox = createEmptyEntity();
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $currentContainer.content  );
                }
                // Close old container if exist
                if ($currentContainer.identification) {
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'And render the container into mainContent' );
                    $mainContent += closeAndRenderEntity($currentContainer, $containerVerifStruct, $delMode);
                    $currentContainer = createEmptyEntity();
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $mainContent );
                }

                $containerVerifStruct = reinitVerificationStruct( $containerVerifStruct );
                $boxVerifStruct = reinitVerificationStruct( $boxVerifStruct );
                if ($currentContainer.identification)
                    $containerVerifStruct.bFirst = false;

                $currentContainer = initializeEntity($currentContainer, 1, $UALine, $containerVerifStruct );
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $currentContainer );
                // Verify if we are in a subbox
                if ($UALine['boxnumber'] != null) {
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'And we are in a subbox, so create a new subbox also' );
                    $currentSubBox = initializeEntity($currentSubBox, 3, $UALine, $boxVerifStruct );
                    // Add script to Container
                    $currentContainer.toggleOn += createContainerSubBoxScript( $currentSubBox, true );
                    $currentContainer.toggleOff += createContainerSubBoxScript( $currentSubBox, false );
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $currentSubBox );
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'And render the new line into the subbox.content' );
                    $boxVerifStruct = updateVerificationStruct($UALine, $boxVerifStruct, $alreadyCheckedListID,
                        $statusesAllowedToBeChecked );
                    $containerVerifStruct = updateVerificationStruct($UALine, $containerVerifStruct,
                        $alreadyCheckedListID, $statusesAllowedToBeChecked );

                    $currentSubBox.content += generateUALineFlow($UALine, $alreadyCheckedListID,
                        $statusesAllowedToBeChecked, $boxVerifStruct, 3);
                    // Add script to SubBox And Container
                    $currentSubBox.toggleOn += createToggleScript( $UALine, true,
                        $statusesAllowedToBeChecked, $alreadyCheckedListID );
                    $currentSubBox.toggleOff += createToggleScript( $UALine, false,
                        $statusesAllowedToBeChecked, $alreadyCheckedListID );
                    $currentContainer.toggleOn += createToggleScript( $UALine, true,
                        $statusesAllowedToBeChecked, $alreadyCheckedListID );
                    $currentContainer.toggleOff += createToggleScript( $UALine, false,
                        $statusesAllowedToBeChecked, $alreadyCheckedListID );
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $currentSubBox  );
                } else {
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'It is just a document, so push it directly to the currentContainer.content' );
                    // It's just a document, so push it to container content
                    $containerVerifStruct = updateVerificationStruct($UALine, $containerVerifStruct,
                        $alreadyCheckedListID, $statusesAllowedToBeChecked );

                    $currentContainer.content += generateUALineFlow($UALine, $alreadyCheckedListID,
                        $statusesAllowedToBeChecked, $containerVerifStruct, 1);
                    // Add script to Container
                    $currentContainer.toggleOn += createToggleScript( $UALine, true,
                        $statusesAllowedToBeChecked, $alreadyCheckedListID );
                    $currentContainer.toggleOff += createToggleScript( $UALine, false,
                        $statusesAllowedToBeChecked, $alreadyCheckedListID );
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $currentContainer  );
                }
            }

        } else {
            // It's not a container, so verify if this line concerns a box
            if ($UALine['boxnumber'] != null) {
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'We are in a box !' );
                // Close subbox if one
                if ($currentSubBox.identification) {
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'There was a previous subbox not rendered, so render it into currentContainer.content' );
                    $currentContainer.content += closeAndRenderEntity($currentSubBox, $boxVerifStruct, $delMode);
                    $boxVerifStruct = reinitVerificationStruct( $boxVerifStruct );
                    $currentSubBox = createEmptyEntity();
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $currentContainer.content  );
                }
                // Close old container if exist
                if ($currentContainer.identification) {
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'And render the container to mainContent' );
                    $mainContent += closeAndRenderEntity($currentContainer, $containerVerifStruct, $delMode);
                    $containerVerifStruct = reinitVerificationStruct( $containerVerifStruct );
                    $currentContainer = createEmptyEntity();
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $mainContent );
                }
                // Verify if we are in the same box
                if ($UALine['boxnumber'] == $currentBox.identification && $UALine['suid'] == $currentSUID) {
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'We are in the same box than previously, so render line into currentBox.content' );
                    $boxVerifStruct = updateVerificationStruct($UALine, $boxVerifStruct, $alreadyCheckedListID,
                        $statusesAllowedToBeChecked );

                    $currentBox.content += generateUALineFlow($UALine, $alreadyCheckedListID,
                        $statusesAllowedToBeChecked, $boxVerifStruct, 2);
                    // Add script to Box
                    $currentBox.toggleOn += createToggleScript( $UALine, true,
                        $statusesAllowedToBeChecked, $alreadyCheckedListID );
                    $currentBox.toggleOff += createToggleScript( $UALine, false,
                        $statusesAllowedToBeChecked, $alreadyCheckedListID );
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $currentBox  );
                } else {
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'This is a new Box' );
                    // Close old Box if exist
                    if ($currentBox.identification) {
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'But there is an old box not closed, so render it into maintContent' );
                        $mainContent += closeAndRenderEntity($currentBox, $boxVerifStruct, $delMode);
                        $currentBox = createEmptyEntity();
                        $boxVerifStruct = reinitVerificationStruct( $boxVerifStruct );
                        $boxVerifStruct.bFirst = false;
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $mainContent );
                    }
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'And create a new box' );
                    $currentBox = initializeEntity($currentBox, 2, $UALine, $boxVerifStruct );
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $currentBox );
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'And render the new line into currentBox.content' );
                    $boxVerifStruct = updateVerificationStruct($UALine, $boxVerifStruct, $alreadyCheckedListID,
                        $statusesAllowedToBeChecked );

                    $currentBox.content += generateUALineFlow($UALine, $alreadyCheckedListID,
                        $statusesAllowedToBeChecked, $boxVerifStruct, 2);
                    // Add script to Box
                    $currentBox.toggleOn += createToggleScript( $UALine, true, $statusesAllowedToBeChecked,
                        $alreadyCheckedListID );
                    $currentBox.toggleOff += createToggleScript( $UALine, false, $statusesAllowedToBeChecked,
                        $alreadyCheckedListID );
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $currentBox  );
                }

            } else {
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'It is not a Container, nor a Box, just a simple line' );
                // It's not a container, nor a box, so it's a simple document
                // Close old container if exist
                if ($currentContainer.identification) {
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'Last container was not rendered yet'  );
                    // Close subbox before if one
                    if ($currentSubBox.identification) {
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'Last subbox of this container was not rendered yet, so do it into currentContainer.content first' );
                        $currentContainer.content += closeAndRenderEntity($currentSubBox, $boxVerifStruct, $delMode);
                        $boxVerifStruct = reinitVerificationStruct( $boxVerifStruct );
                        $currentSubBox = createEmptyEntity();
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $currentContainer.content  );
                    }
                    $mainContent += closeAndRenderEntity($currentContainer, $boxVerifStruct, $delMode);
                    $currentContainer = createEmptyEntity();
                    $containerVerifStruct = reinitVerificationStruct( $containerVerifStruct );
                    $containerVerifStruct.bFirst = false;
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'Render currentContainer in mainContent' );
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $mainContent );
                }
                // Close old Box if exist
                if ($currentBox.identification) {
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'Last Box was not rendered yet, so render it into mainContent' );
                    $mainContent += closeAndRenderEntity($currentBox, $boxVerifStruct, $delMode);
                    $currentBox = createEmptyEntity();
                    $boxVerifStruct = reinitVerificationStruct( $boxVerifStruct );
                    $boxVerifStruct.bFirst = false;
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $mainContent );
                }

                if (!$otherUAs.identification) {    // In fact first time we encourter UA not in container nor in box
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'First line of ROA, so initialize ROA entity' );
                    $otherUAs = initializeEntity($otherUAs, 4, null, $otherVerifStruct );
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $otherUAs );
                }
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'And render line into otherUAs.content'  );
                $otherVerifStruct = updateVerificationStruct($UALine, $otherVerifStruct, $alreadyCheckedListID,
                    $statusesAllowedToBeChecked );

                $otherUAs.content += generateUALineFlow($UALine, $alreadyCheckedListID, $statusesAllowedToBeChecked,
                    $otherVerifStruct, 4);
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $otherUAs.content );
            }
        }

        $currentSUID = $UALine['suid'];
    }

                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'All lines have been parsed, so render everything in mainContent' );
    // We parsed all lines, so close what needed to be closed
    if ($currentContainer.identification) {
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'There is a not closed container' );
        if ($currentSubBox.identification) {
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'There is a not closed subbox inside it, so render it first in currentContainer.content' );
            $currentContainer.content += closeAndRenderEntity($currentSubBox, $boxVerifStruct, $delMode);
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $currentContainer.content  );
        }
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'Render the container into mainContent' );
        $mainContent += closeAndRenderEntity($currentContainer, $containerVerifStruct, $delMode);
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $mainContent );
    } else if( $currentBox.identification ){
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'There is a not closed box, so render it into mainContent' );
        $mainContent += closeAndRenderEntity( $currentBox, $boxVerifStruct, $delMode );
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $mainContent );
    } else if( $otherUAs.identification ){
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'There are some UAs in the ROA section, so render it into mainContent'  );
        $mainContent += closeAndRenderEntity($otherUAs, $otherVerifStruct, $delMode);
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $mainContent );
    }

                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'Set OptimizsationModalBody with the mainContent' );
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $mainContent );
    if( !$delMode )
        $('#OptimisationModalBody').html( $mainContent );
    else
        $('#DelOptimisationModalBody').html( $mainContent );
}
//---------------------------------------------------------------------------------------------------------------------

function pollLatestOptiStatus() {

    $('#textopti').html( 'En attente statut serveur ...' );
    $respOK = false;

    var jqxhr = $.get( window.JSON_URLS.bs_idp_archivist_json_providerconnector_optimisationstatus, function($response){
        if( !$response ){
            clearInterval( pollTimer );
            return;
        }

        $percent = $response['percent'];
        $message = $response['message'];

        $('#pbopti').prop( 'style', 'width:'+$percent+'%;');
        $('#textopti').html( $message );

        $respOK = true;

        if( $percent >= 100 )
            clearInterval(pollTimer);
        })
        .fail(function() {
            clearInterval(pollTimer);
        });

    if( !$respOK )
        clearInterval(pollTimer);
}

function save_ProviderConnectorModalDatas( ){
    $dataProvConn = {
        'contact' : $('#frm_pcb_contact').val(),
        'phone': $('#frm_pcb_phone').val(),
        'address': $('#frm_pcb_address').val(),
        'deliver': $('input[name=pcbdeliverradio]:checked', '#ProviderConnectorModalBody').val(),
        'disposal': $('input[name=pcbdisposalradio]:checked', '#ProviderConnectorModalBody').val(),
        'type': $('input[name=pcbtyperadio]:checked', '#ProviderConnectorModalBody').val(),
        'type2': $('input[name=pcbtype2radio]:checked', '#ProviderConnectorModalBody').val(),
        'remark': $('#frm_pcb_remark').val(),
        'name': $('#frm_pcb_name').val(),
        'firstname': $('#frm_pcb_firstname').val(),
        'function': $('#frm_pcb_function').val()
    };

    // Backup ProviderConnector datas
    $.ajax({
        type: 'GET',
        url: window.JSON_URLS.bs_idp_archivist_json_providerconnectorbackup_set,
        data: $dataProvConn,
        cache: false
    });
}
