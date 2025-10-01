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

var $_currentPage = PAGE_CLOSE_TRANSFER_PROVIDER;
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

var SERVICE_ID = 0;
var SERVICE_NAME = 1
var SERVICE_LEGALENTITIES_IDX = 2;
var SERVICE_BUDGETCODES_IDX = 3;
var SERVICE_DESCRIPTIONS1_IDX = 4;
var SERVICE_DESCRIPTIONS2_IDX = 5;
var SERVICE_PROVIDERS_IDX = 6;
var SERVICE_DOCUMENTNATURES_IDX = 7;
var LEGALENTITY_ID = 0;
var LEGALENTITY_NAME = 1
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

$xpstate = UASTATE_MANAGECLOSE;
$uawhat = UAWHAT_TRANSFER;
$uawhat_asked = -1;
$uawhere = UAWHERE_PROVIDER;
$uawith = UAWITH_NOTHING;
$uawith_asked = -1;
$uahow = UAHOW_WITHOUTPREPARE;
$filter_provider = -1;


$actionList = [];	// Store Archive for Action purpose (uawhat)
$actionListID = [];	// Store only IDs to simplify some treatments
$currentNumberChecked = 0;

var $_commonsettings = null;
var $_settings = null;
var $_translations = null;
var $_overlay = null;

// Function to send a post to server
// post('/contact/', {name: 'Johnny Bravo'});
function post(path, params, external) {
    var form = document.createElement("form");
    form.setAttribute("method", "post");
    form.setAttribute("action", path);
    if( external )
        form.setAttribute("target", "_blanc" );

    for(var key in params) {
        if(params.hasOwnProperty(key)) {
            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", key);
            hiddenField.setAttribute("value", params[key]);

            form.appendChild(hiddenField);
        }
    }
    document.body.appendChild(form);
    form.submit();
}


function btnwhatClean(){
    $('#btnwhat_transfer').removeClass( 'active' );
    $('#btnwhat_deliver').removeClass( 'active' );
    $('#btnwhat_return').removeClass( 'active' );
    $('#btnwhat_exit').removeClass( 'active' );
    $('#btnwhat_destroy').removeClass( 'active' );
    $('#btnwhat_reloc').removeClass( 'active' );
}
function btnwhereClean(){
    $('#btnwhere_provider').removeClass( 'active' );
    $('#btnwhere_intermediate').removeClass( 'active' );
    $('#btnwhere_internal').removeClass( 'active' );
}
function btnhowClean(){
    $('#btnhow_withoutprepare').removeClass('active');
    $('#btnhow_withprepare').removeClass('active');
}
function btnwithClean(){
    /*
     $('#btnwith_container').removeClass( 'active' );
     $('#btnwith_box').removeClass( 'active' );
     $('#btnwith_file').removeClass( 'active' );
     $('#btnwith_nothing').removeClass( 'active' );
     */
}


function switchCurrentPageAndButtons(){
    switch( $uawhat ){
        case UAWHAT_TRANSFER:
            switch( $uawhere ){
                case UAWHERE_PROVIDER:
                    $_currentPage = PAGE_CLOSE_TRANSFER_PROVIDER;
                    break;
                case UAWHERE_INTERMEDIATE:
                    $_currentPage = PAGE_CLOSE_TRANSFER_INTERMEDIATE;
                    break;
                case UAWHERE_INTERNAL:
                    $_currentPage = PAGE_CLOSE_TRANSFER_INTERNAL;
                    break;
            }
            break;
        case UAWHAT_CONSULT:
            switch( $uahow ){
                case UAHOW_WITHOUTPREPARE:
                    $_currentPage = PAGE_CLOSE_DELIVER_WITHOUT_PREPARATION;
                    break;
                case UAHOW_WITHPREPARE:
                    $_currentPage = PAGE_CLOSE_DELIVER_WITH_PREPARATION;
                    break;
            }
            break;
        case UAWHAT_RETURN:
            $_currentPage = PAGE_CLOSE_RETURN;
            break;
        case UAWHAT_EXIT:
            $_currentPage = PAGE_CLOSE_EXIT;
            break;
        case UAWHAT_DESTROY:
            $_currentPage = PAGE_CLOSE_DELETE;
            break;
        case UAWHAT_RELOC:
            switch( $uawhere ){
                case UAWHERE_PROVIDER:
                    $_currentPage = PAGE_CLOSE_RELOC_PROVIDER;
                    break;
                case UAWHERE_INTERMEDIATE:
                    $_currentPage = PAGE_CLOSE_RELOC_INTERMEDIATE;
                    break;
                case UAWHERE_INTERNAL:
                    $_currentPage = PAGE_CLOSE_RELOC_INTERNAL;
                    break;
            }
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
            initMainTabColumns( false, $('#listarchives'), window.JSON_URLS.bs_idp_archivist_json_loaddatas, $response.data, $_tabletranslation, $_commonsettings, 'server', 'get', 9 );
            $('#listarchives').bootstrapTable('refresh');
        },
        error: function (xhr, ajaxOptions, thrownError) {
            bootbox.alert( {
                message: $_translations[90],
                className: "boxSysErrorOne"
            } );
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
        success: updateLists,
        error: function (xhr, ajaxOptions, thrownError) {
            bootbox.alert( {
                message: $_translations[90],
                className: "boxSysErrorOne"
            } );
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

    $('#btnSuppressModalConfirm').click( function(){
        onClickBtnSuppressModalConfirm();
        return true;
    });

    $('#divSubmitModif').click(function(){
        onClickBtnSubmitModif();
        currentOverlayViewUA = null;
        return true;
    });

    $('#divDelete').click( function(){
        $('#SuppressModalText').html( $_translations[53] + ' ' + $('#frm_name').html() );
        $('#SuppressModal').modal('show');
    });

    $('#btnAction').click( function(){
        clickAction();
    });

    // Activate tooltip on buttons
    $('[data-toggle="tooltip"]').tooltip();

    // Get back User Settings to init Main Tab
    $dataStr = "page=" + $_currentPage;
    $.ajax({
        type: "GET",
        url: window.JSON_URLS.bs_idp_backoffice_ajax_usersettings_get,
        data: $dataStr,
        cache: false,
        success: function( $response ) {
            initMainTab( $('#listarchives'), window.JSON_URLS.bs_idp_archivist_json_loaddatas, $response.data, $_tabletranslation, $_commonsettings, 'server', 'get', 9 );
        },
        error: function (xhr, ajaxOptions, thrownError) {
            bootbox.alert( {
                message: $_translations[90],
                className: "boxSysErrorOne"
            } );
        }
    });

});

$('#addToAction').click( function( event ){
    event.preventDefault();
    if( !$('#addToAction').hasClass( 'disabled' ) )
        clickAddToAction();
})
$('#clearAction').click( function( event ){
    event.preventDefault();
    if( !$('#clearAction').hasClass("disabled")) {
        $actionList = [];
        $actionListID = [];
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
    params.uastate = UASTATE_MANAGECLOSE;
    params.uawhat = $uawhat;
    params.uawhere = $uawhere;
    params.uawith = 0; // $uawith;
    params.uahow = $uahow;
    params.special = $special;
    if( $uawhat == UAWHAT_TRANSFER && $uawhere == UAWHERE_PROVIDER ) {
        if ($("#filter_provider option:selected").val() != "")
            params.filterprovider = $("#filter_provider option:selected").val();
        else
            params.filterprovider = -1;
    } else
        params.filterprovider = -1;

    return params; //JSON.stringify( params );
}
function stateFormatter( value, row, index ){
    if( row['locked'] )
        return { disabled: true };

    $inActionList = $actionListID.indexOf( row['id'] );

    if( $inActionList >= 0 )
        return { disabled: true };
    else
        return { disabled: false };
}
function rowStyle( row, index ){
    if( row['locked'] )
        return { disabled: true };

    $inActionList = $actionListID.indexOf( row['id'] );

    if( $inActionList >= 0 )
        return { classes: 'info' };

    return { classes: '' };
}
function operateFormatter( value, row, index ){
    return [
        '<a class="remove" href="javascript:void(0)" title="'+$_translations[54]+'">', '<i class="far fa-times"></i>', '</a>'
    ].join('');
}
window.operateActionEvents = {
    'click .remove': function( e, value, row, index ){
        $elemId = row['id'];
        $removeIdx = -1;
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


$('#btnConfirmModalConfirm').click(function(){

    $('#ConfirmModal').modal('hide');
    $actionList = [];
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

    switch($uawith_asked){
        case UAWITH_BOX:
            changeWithIntoBox();
            break;
        case UAWITH_CONTAINER:
            changeWithIntoContainer();
            break;
        case UAWITH_FILE:
            changeWithIntoFile();
            break;
        case UAWITH_NOTHING:
            changeWithIntoNothing();
            break;
    }
})

function makeConfirmText( ){
    $text = $_translations[55] + " '<strong>";
    $bFirst = true;
    $bCount = 0;
    if( $actionList.length > 0 ){
        $bFirst = false;
        $bCount++;
        switch( $uawhat ){
            case UAWHAT_TRANSFER: $text += $_translations[26]; break;
            case UAWHAT_CONSULT: $text += $_translations[57]; break;
            case UAWHAT_RETURN: $text += $_translations[62]; break;
            case UAWHAT_DESTROY: $text += $_translations[67]; break;
            case UAWHAT_EXIT: $text += $_translations[72]; break;
            case UAWHAT_RELOC: $text += $_translations[77]; break;
        }
    }
    if( $bCount == 1 )
        $text += "</strong>' " + ' ' + $_translations[82];
    else
        $text += "</strong>' " + ' ' + $_translations[83];
    $text += " <br/> "+ $_translations[84];
    return $text;
}

// Buttons UAWHAT management
$('#btnwhat_transfer').click(function(){
    if( $uawhat != UAWHAT_TRANSFER ){
        if( $actionList.length > 0  ){
            $uawhat_asked = UAWHAT_TRANSFER;
            $uawith_asked = -1;
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

    $('#btnhow').addClass('hidden');
    $('#btnnothing').addClass('hidden');
    $('#btnwhere').removeClass('hidden');
    $('#btnwith').removeClass('hidden');
    $('#btnnothing2').addClass('hidden');

    $('#titleAction').html( $_translations[25] );
    $('#btnAction').html( $_translations[27] );
    $('#textDoAction').html('&nbsp;&nbsp;&nbsp;' + $_translations[26] );

    if( $uawhere == UAWHERE_PROVIDER )
        $('#selectproviderfilter').show();

    resetMultipleSelect( );
    switchCurrentPageAndButtons();
    verifyAndEnableEmptyBasketButton();
};

$('#btnwhat_deliver').click(function(){
    if( $uawhat != UAWHAT_CONSULT ){
        if( $actionList.length > 0  ){
            $uawhat_asked = UAWHAT_CONSULT;
            $uawith_asked = -1;
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

    $('#btnwhere').addClass('hidden');
    $('#btnnothing').addClass('hidden');
    $('#btnhow').removeClass('hidden');
    $('#btnwith').addClass('hidden');
    $('#btnnothing2').removeClass('hidden');

    $('#titleAction').html( $_translations[56] );
    $('#btnAction').html( $_translations[58] );
    $('#textDoAction').html('&nbsp;&nbsp;&nbsp;' + $_translations[57] );

    $('#selectproviderfilter').hide();

    resetMultipleSelect( );
    switchCurrentPageAndButtons();
    verifyAndEnableEmptyBasketButton();
};

$('#btnwhat_return').click(function(){
    if( $uawhat != UAWHAT_RETURN ){
        if( $actionList.length > 0   ){
            $uawhat_asked = UAWHAT_RETURN;
            $uawith_asked = -1;
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

    $('#btnwhere').addClass('hidden');
    $('#btnhow').addClass('hidden');
    $('#btnnothing').removeClass('hidden');
    $('#btnwith').addClass('hidden');
    $('#btnnothing2').removeClass('hidden');

    $('#titleAction').html( $_translations[61] );
    $('#btnAction').html( $_translations[63] );
    $('#textDoAction').html('&nbsp;&nbsp;&nbsp;' + $_translations[62] );

    $('#selectproviderfilter').hide();

    resetMultipleSelect( );
    switchCurrentPageAndButtons();
    verifyAndEnableEmptyBasketButton();
};

$('#btnwhat_exit').click(function(){
    if( $uawhat != UAWHAT_EXIT ){
        if( $actionList.length > 0   ){
            $uawhat_asked = UAWHAT_EXIT;
            $uawith_asked = -1;
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

    $('#btnwhere').addClass('hidden');
    $('#btnhow').addClass('hidden');
    $('#btnnothing').removeClass('hidden');
    $('#btnwith').addClass('hidden');
    $('#btnnothing2').removeClass('hidden');

    $('#titleAction').html( $_translations[71] );
    $('#btnAction').html( $_translations[73] );
    $('#textDoAction').html('&nbsp;&nbsp;&nbsp;' + $_translations[72] );

    $('#selectproviderfilter').hide();

    resetMultipleSelect( );
    switchCurrentPageAndButtons();
    verifyAndEnableEmptyBasketButton();
};

$('#btnwhat_destroy').click(function(){
    if( $uawhat != UAWHAT_DESTROY ){
        if( $actionList.length > 0   ){
            $uawhat_asked = UAWHAT_DESTROY;
            $uawith_asked = -1;
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

    $('#btnwhere').addClass('hidden');
    $('#btnhow').addClass('hidden');
    $('#btnnothing').removeClass('hidden');
    $('#btnwith').addClass('hidden');
    $('#btnnothing2').removeClass('hidden');

    $('#titleAction').html( $_translations[66] );
    $('#btnAction').html( $_translations[68] );
    $('#textDoAction').html('&nbsp;&nbsp;&nbsp;' + $_translations[67] );

    $('#selectproviderfilter').hide();

    resetMultipleSelect( );
    switchCurrentPageAndButtons();
    verifyAndEnableEmptyBasketButton();
};

$('#btnwhat_reloc').click(function(){
    if( $uawhat != UAWHAT_RELOC ){
        if( $actionList.length > 0   ){
            $uawhat_asked = UAWHAT_RELOC;
            $uawith_asked = -1;
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

    $('#btnhow').addClass('hidden');
    $('#btnnothing').addClass('hidden');
    $('#btnwhere').removeClass('hidden');
    $('#btnwith').removeClass('hidden');
    $('#btnnothing2').addClass('hidden');

    $('#titleAction').html( $_translations[76] );
    $('#btnAction').html( $_translations[78] );
    $('#textDoAction').html('&nbsp;&nbsp;&nbsp;' + $_translations[77] );

    $('#selectproviderfilter').hide();

    resetMultipleSelect( );
    switchCurrentPageAndButtons();
    verifyAndEnableEmptyBasketButton();
};

$('#btnwhere_provider').click(function(){
    if( $uawhere != UAWHERE_PROVIDER ){
        btnwhereClean();
        $('#btnwhere_provider').addClass( 'active' );
        $uawhere = UAWHERE_PROVIDER;

        $('#selectproviderfilter').show();

        $('#listarchives').bootstrapTable( 'showColumn', 'localization' );
        $('#listarchives').bootstrapTable( 'hideColumn', 'localizationfree' );
        resetMultipleSelect( );
        switchCurrentPageAndButtons();
        verifyAndEnableEmptyBasketButton();
    }
});
$('#btnwhere_intermediate').click(function(){
    if( $uawhere != UAWHERE_INTERMEDIATE ){
        btnwhereClean();
        $('#btnwhere_intermediate').addClass( 'active' );
        $uawhere = UAWHERE_INTERMEDIATE;

        $('#selectproviderfilter').hide();

        $('#listarchives').bootstrapTable( 'showColumn', 'localizationfree' );
        $('#listarchives').bootstrapTable( 'hideColumn', 'localization' );
        resetMultipleSelect( );
        switchCurrentPageAndButtons();
        verifyAndEnableEmptyBasketButton();
    }
});
$('#btnwhere_internal').click(function(){
    if( $uawhere != UAWHERE_INTERNAL ){
        btnwhereClean();
        $('#btnwhere_internal').addClass( 'active' );
        $uawhere = UAWHERE_INTERNAL;

        $('#selectproviderfilter').hide();

        $('#listarchives').bootstrapTable( 'showColumn', 'localizationfree' );
        $('#listarchives').bootstrapTable( 'hideColumn', 'localization' );
        resetMultipleSelect( );
        switchCurrentPageAndButtons();
        verifyAndEnableEmptyBasketButton();
    }
});
$('#btnhow_withoutprepare').click(function(){
    if( $uahow != UAHOW_WITHOUTPREPARE ){
        btnhowClean();
        $('#btnhow_withoutprepare').addClass('active');
        $uahow = UAHOW_WITHOUTPREPARE;

        resetMultipleSelect( );
        switchCurrentPageAndButtons();
        verifyAndEnableEmptyBasketButton();
    }
});
$('#btnhow_withprepare').click(function(){
    if( $uahow != UAHOW_WITHPREPARE ){
        btnhowClean();
        $('#btnhow_withprepare').addClass('active');
        $uahow = UAHOW_WITHPREPARE;

        resetMultipleSelect( );
        switchCurrentPageAndButtons();
        verifyAndEnableEmptyBasketButton();
    }
});

$('#btnwith_container').click(function(){
    if( $uawith != UAWITH_CONTAINER ){
        if( $actionList.length > 0   ){
            $uawith_asked = UAWITH_CONTAINER;
            $uawhat_asked = -1;
            $('#confirmModalText').html( makeConfirmText() );
            $('#ConfirmModal').modal('show');
        } else
            changeWithIntoContainer();
    }
});
function changeWithIntoContainer(){
    btnwithClean();
    $('#btnwith_container').addClass( 'active' );
    $uawith = UAWITH_CONTAINER;

    resetMultipleSelect( );
    switchCurrentPageAndButtons();
    verifyAndEnableEmptyBasketButton();
};

$('#btnwith_box').click(function(){
    if( $uawith != UAWITH_BOX ){
        if( $actionList.length > 0   ){
            $uawith_asked = UAWITH_BOX;
            $uawhat_asked = -1;
            $('#confirmModalText').html( makeConfirmText() );
            $('#ConfirmModal').modal('show');
        } else
            changeWithIntoBox();
    }
});
function changeWithIntoBox(){
    btnwithClean();
    $('#btnwith_box').addClass( 'active' );
    $uawith = UAWITH_BOX;

    resetMultipleSelect( );
    switchCurrentPageAndButtons();
    verifyAndEnableEmptyBasketButton();
};

$('#btnwith_file').click(function(){
    if( $uawith != UAWITH_FILE ){
        if( $actionList.length > 0   ){
            $uawith_asked = UAWITH_FILE;
            $uawhat_asked = -1;
            $('#confirmModalText').html( makeConfirmText() );
            $('#ConfirmModal').modal('show');
        } else
            changeWithIntoFile();
    }
});
function changeWithIntoFile(){
    btnwithClean();
    $('#btnwith_file').addClass( 'active' );
    $uawith = UAWITH_FILE;

    resetMultipleSelect( );
    switchCurrentPageAndButtons();
    verifyAndEnableEmptyBasketButton();
};

$('#btnwith_nothing').click(function(){
    if( $uawith != UAWITH_NOTHING ){
        if( $actionList.length > 0   ){
            $uawith_asked = UAWITH_NOTHING;
            $uawhat_asked = -1;
            $('#confirmModalText').html( makeConfirmText() );
            $('#ConfirmModal').modal('show');
        } else
            changeWithIntoNothing();
    }
})
function changeWithIntoNothing(){
    btnwithClean();
    $('#btnwith_nothing').addClass( 'active' );
    $uawith = UAWITH_NOTHING;

    resetMultipleSelect( );
    switchCurrentPageAndButtons();
    verifyAndEnableEmptyBasketButton();
};

function updateServices( $initService ){
    $serviceOptions = "<option value=\"\"></option>";
    i = 0;
    $bSelected = false;
    $services.forEach(function($serviceLine){
        $selected = "";
        if( $serviceLine[SERVICE_ID] == parseInt( $initService ) ){
            $bSelected = true;
            $selected = " selected='selected' ";
        }
        $serviceOptions += "<option value=\"" + $serviceLine[SERVICE_ID] + "\" data=\"" + i + "\" " + $selected + ">" + $serviceLine[SERVICE_NAME] + "</option> ";
        i = i+1;
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
    $partialLegalEntities = "";
    if( $("#frm_service option:selected").val() != "" ){

        $partialLegalEntities = "<option value ";
        if( parseInt($initLegalEntity) >= 0 )
            $partialLegalEntities += "selected=\"selected\"";
        $partialLegalEntities += "></option>";

        // Construct list of legal entities choices based on service id
        $serviceIdx = parseInt( $("#frm_service option:selected").attr('data') );
        $listLegalEntities = $services[$serviceIdx][SERVICE_LEGALENTITIES_IDX];
        $i = 0;
        $legalEntities.forEach(function($legalentityLine){
            if( $listLegalEntities.indexOf( $legalentityLine[LEGALENTITY_ID] ) >= 0 ){
                $selected = "";
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

    $partialBudgetCodes = "";
    if( $("#frm_service option:selected").val() != "" ){

        $partialBudgetCodes ="<option value ";
        if( parseInt($initBudgetCode) >= 0 )
            $partialBudgetCodes += " selected=\"selected\"";
        $partialBudgetCodes += "></option>";

        // Construct list of budget codes choices based on service id
        $serviceIdx = parseInt( $("#frm_service option:selected").attr('data') );
        $listBudgetCodes = $services[$serviceIdx][SERVICE_BUDGETCODES_IDX];
        $iu = 0;
        $budgetCodes.forEach(function($budgetcodeLine){
            if( $listBudgetCodes.indexOf( $budgetcodeLine[BUDGETCODE_ID] ) >= 0 ){
                $selected = "";
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

    $partialDocumentNatures = "";
    if( $("#frm_service option:selected").val() != "" ){

        $partialDocumentNatures = "<option value ";
        if( parseInt($initDocumentNature) >= 0 )
            $partialDocumentNatures += " selected=\"selected\"";
        $partialDocumentNatures += "></option>";
        // Construct list of document natures choices based on service id
        $serviceIdx = parseInt( $("#frm_service option:selected").attr('data') );
        $listDocumentNatures = $services[$serviceIdx][SERVICE_DOCUMENTNATURES_IDX];
        $i = 0;
        $documentNatures.forEach(function($documentnatureLine){
            if( $listDocumentNatures.indexOf( $documentnatureLine[DOCUMENTNATURE_ID] ) >= 0 ){
                $selected = "";
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

    $partialDocumentTypes = "";
    if( $("#frm_documentnature option:selected").val() != "" ){

        $partialDocumentTypes ="<option value ";
        if( parseInt($initDocumentType) >= 0 )
            $partialDocumentTypes += " selected=\"selected\"";
        $partialDocumentTypes += "></option>";
        // Construct list of document types choices based on document nature id
        $documentnatureIdx = parseInt( $("#frm_documentnature option:selected").attr('data') );
        $listDocumentTypes = $documentNatures[$documentnatureIdx][DOCUMENTNATURE_DOCUMENTTYPES_IDX];
        $i = 0;
        $documentTypes.forEach(function($documenttypeLine){
            if( $listDocumentTypes.indexOf( $documenttypeLine[DOCUMENTTYPE_ID] ) >= 0 ){
                $selected = "";
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

    $partialDescriptions1 = "";
    if( $("#frm_service option:selected").val() != "" ){

        $partialDescriptions1 ="<option value ";
        if( parseInt($initDescription1) >= 0 )
            $partialDescriptions1 += " selected=\"selected\"";
        $partialDescriptions1 += "></option>";
        // Construct list of descriptions1 choices based on service id
        $serviceIdx = parseInt( $("#frm_service option:selected").attr('data') );
        $listDescriptions1 = $services[$serviceIdx][SERVICE_DESCRIPTIONS1_IDX];
        $i = 0;
        $descriptions1.forEach(function($descriptionLine){
            if( $listDescriptions1.indexOf( $descriptionLine[DESCRIPTION1_ID] ) >= 0 ){
                $selected = "";
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

    $partialDescriptions2 = "";
    if( $("#frm_service option:selected").val() != "" ){

        $partialDescriptions2 ="<option value ";
        if( parseInt($initDescription2) >= 0 )
            $partialDescriptions2 += " selected=\"selected\"";
        $partialDescriptions2 += "></option>";
        // Construct list of descriptions2 choices based on service id
        $serviceIdx = parseInt( $("#frm_service option:selected").attr('data') );
        $listDescriptions2 = $services[$serviceIdx][SERVICE_DESCRIPTIONS2_IDX];
        $i = 0;
        $descriptions2.forEach(function($descriptionLine){
            if( $listDescriptions2.indexOf( $descriptionLine[DESCRIPTION2_ID] ) >= 0 ){
                $selected = "";
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

    $partialProviders = "";
    if( $("#frm_service option:selected").val() != "" ){

        $partialProviders ="<option value ";
        if( parseInt($initProvider) >= 0 )
            $partialProviders += " selected=\"selected\"";
        $partialProviders += "></option>";
        // Construct list of providers choices based on service id
        $serviceIdx = parseInt( $("#frm_service option:selected").attr('data') );
        $listProviders = $services[$serviceIdx][SERVICE_PROVIDERS_IDX];
        $i = 0;
        $providers.forEach(function($providerLine){
            if( $listProviders.indexOf( $providerLine[PROVIDER_ID] ) >= 0 ){
                $selected = "";
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
function updateLocalizations( $initLocalization, $initOldLocalization ){

    $partialLocalizations = "";
    $partialOldLocalizations = "";

    if( $("#frm_provider option:selected").val() != "" ){

        $partialLocalizations = "<option value ";
        $partialOldLocalizations = $partialLocalizations;

        if( parseInt($initLocalization) <= 0 )
            $partialLocalizations += " selected=\"selected\"";
        if( parseInt($initOldLocalization) <= 0 )
            $partialOldLocalizations += " selected=\"selected\"";

        $partialLocalizations += "></option>";
        $partialOldLocalizations += "></options>";

        // Construct list of localizations choices based on provider id
        $providerIdx = parseInt( $("#frm_provider option:selected").attr('data') );
        $localizationIdx = $providers[$providerIdx][PROVIDER_LOCALIZATION_IDX];

        if( $localizationIdx >= 0 ) {
            $localizations.forEach( function( $localizationLine ){
                $selected = "";
                $oldSelected = "";
                if( $localizationLine[LOCALIZATION_ID] == $localizationIdx ) {
                    if ($localizationLine[LOCALIZATION_ID] == parseInt($initLocalization))
                        $selected = " selected=\"selected\"";
                    if( $localizationLine[LOCALIZATION_ID] == parseInt($initOldLocalization))
                        $oldSelected = " selected=\"selected\"";

                    $partialLocalizations += "<option value=\"" + $localizationLine[LOCALIZATION_ID] + "\"" + $selected + ">" + $localizationLine[LOCALIZATION_NAME] + "</option> ";
                    $partialOldLocalizations += "<option value=\"" + $localizationLine[LOCALIZATION_ID] + "\"" + $oldSelected + ">" + $localizationLine[LOCALIZATION_NAME] + "</option>";
                }
            });
        }

        $('#frm_localization').attr('disabled', false);
        $('#frm_oldlocalization').attr('disabled', true);
    } else {
        $('#frm_localization').attr('disabled', true);
        $partialLocalizations ="<option value selected=\"selected\"></option>";
        $('#frm_oldlocalization').attr('disabled', true );
        $partialOldLocalizations = "<option value selected=\"selected\"></option>";
    }

    $("#frm_localization").html($partialLocalizations);
    $('#frm_oldlocalization').html($partialOldLocalizations);
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
        $documenttypeIdx = parseInt($("#frm_documenttype option:selected").attr('data'));
        if( !isNaN( $documenttypeIdx ) )
            $destructionTime = $documentTypes[$documenttypeIdx][DOCUMENTTYPE_KEEPALIVEDURATION];
    }

    // If destruction time is 0, user should enter whatever he wants, instead destruction time is forced
    if($destructionTime == 0){
        $("#frm_destructionyear").prop('disabled', false);
    }
    else
    {
        $("#frm_destructionyear").prop('disabled', true);
        // TODO test empty field
        $destructionYear = parseInt($("#frm_closureyear").val());
        $destructionYear += $destructionTime;
        $("#frm_destructionyear").val($destructionYear);
    }
}

function onClickBtnSuppressModalConfirm(){
    $dataStr = "id=" + $('#frm_id').val();
    $.ajax({
        type: "POST",
        url: window.JSON_URLS.bs_idp_archive_delete_ajax,
        data: $dataStr,
        cache: false,
        success: function($response) {
            $('#SuppressModal').modal('hide');
            $('#viewArchive').hide();
            $('#listarchives').bootstrapTable('refresh');
        },
        error: function (xhr, ajaxOptions, thrownError) {
            bootbox.alert( {
                message: $_translations[85],
                className: "boxSysErrorOne"
            } );
            $('#SuppressModal').modal('hide');
        }
    });

    return true;
}

// Update both Add button states accordingly to selections in main table
function verifyAndDisableAddButtons() {
    $selections = $_mainTable.bootstrapTable('getSelections');
    if( $selections.length <= 0 ){
        $('#addToAction').addClass('disabled');
        $('#addToCancel').addClass('disabled');
    }
}
// Add new selection to the Action list
function clickAddToAction(){
    $selections = $('#listarchives').bootstrapTable( 'getSelections' );

    // Scan to search new ones
    $selections.forEach( function( $elem ){
        $inActionList = $actionListID.indexOf( $elem['id'] );
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

function enableAddBasketButton(){
    $('#addToAction').removeClass('disabled');
    $('#addToCancel').removeClass('disabled');
}

//--------------------------------------------------------------------------------------------------------------
function continue_After_BasketTests( $action ){
    json_action();
}

function json_action( ){
    $dataObject = {
        'uastate': UASTATE_MANAGECLOSE,
        'uawhat': $uawhat,
        'uawhere': $uawhere,
        'uawith': 0, //$uawith,
        'uahow': $uahow,
        'ids': JSON.stringify( $actionListID )
    };

    $.ajax({
        type: "GET",
        url: window.JSON_URLS.bs_idp_archivist_json_action,
        data: $dataObject,
        cache: false,
        success: function($response) {
            $actionList = [];
            $actionListID = [];
            $('#table-action').bootstrapTable('load', $actionList );
            $('#listarchives').bootstrapTable('refresh');
            updateBtnActionCancelState();
            $('#waitAjax').hide();
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $('#waitAjax').hide();
            bootbox.alert( {
                message: $_translations[85],
                className: "boxSysErrorOne"
            } );
        }
    });
}

function verifyMandatories() {
    clearViewClass();

    $retour = true;
    if( $('#frm_service option:selected').val() == '' ){
        $retour = false;
        $('#divSelectService').addClass('has-error');
    } else {
        $('#divSelectService').addClass('has-success');
    }
    if( $('#frm_legalentity option:selected').val() == '' ){
        $retour = false;
        $('#divSelectLegalentity').addClass('has-error');
    } else {
        $('#divSelectLegalentity').addClass('has-success');
    }
    if( $('#frm_name').val().trim() == ''){
        $retour = false;
        $('#form_name').addClass('has-error');
    } else {
        $('#form_name').addClass('has-success');
    }
    if( $('#frm_closureyear').val().trim() == '' ){
        $retour = false;
        $('#divInputClosureyear').addClass('has-error');
    } else {
        $('#divInputClosureyear').addClass('has-success');
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
                popError( $('#frm_destructionyear'), $_translations[91], 'top' );
                $('#divViewInputDestructionyear').addClass('has-error');
            } else
                $('#divViewInputDestructionyear').addClass('has-success');
        } else
            $('#divViewInputDestructionyear').addClass('has-success');
    }
    if( $_settings.view_budgetcode && $_settings.mandatory_budgetcode && $('#frm_budgetcode option:selected').val() == '' ){
        $retour = false;
        $('#divSelectBudgetcode').addClass('has-error');
    } else {
        $('#divSelectBudgetcode').addClass('has-success');
    }
    if( $_settings.view_documentnature && $_settings.mandatory_documentnature && $('#frm_documentnature option:selected').val() == '' ){
        $retour = false;
        $('#divSelectDocumentnature').addClass('has-error');
    } else {
        $('#divSelectDocumentnature').addClass('has-success');
    }
    if( $_settings.view_documentnature && $_settings.view_documenttype && $_settings.mandatory_documenttype && $('#frm_documenttype option:selected').val() == '' ){
        $retour = false;
        $('#divSelectDocumenttype').addClass('has-error');
    } else {
        $('#divSelectDocumenttype').addClass('has-success');
    }
    if( $_settings.view_description1 && $_settings.mandatory_description1 && $('#frm_description1 option:selected').val() == '' ){
        $retour = false;
        $('#divSelectDescription1').addClass('has-error');
    } else {
        $('#divSelectDescription1').addClass('has-success');
    }
    if( $_settings.view_description2 && $_settings.mandatory_description2 && $('#frm_description2 option:selected').val() == '' ){
        $retour = false;
        $('#divSelectDescription2').addClass('has-error');
    } else {
        $('#divSelectDescription2').addClass('has-success');
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
            $('#divInputLimitsdatemin').addClass('has-error');
        } else {
            $('#divInputLimitsdatemin').addClass('has-success');
        }
        if( $empty2 || $test_RegexValidDate_DateMax == false ){
            $('#divInputLimitsdatemax').addClass('has-error');
        } else {
            $('#divInputLimitsdatemax').addClass('has-success');
        }
    } else {
        $('#divInputLimitsdatemin').addClass('has-success');
        $('#divInputLimitsdatemax').addClass('has-success');
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
            $('#divInputLimitsnummin').addClass('has-error');
        } else {
            $('#divInputLimitsnummin').addClass('has-success');
        }
        if( $empty2 || $test_RegexOnlyNumber_NumMax == false ){
            $('#divInputLimitsnummax').addClass('has-error');
        } else {
            $('#divInputLimitsnummax').addClass('has-success');
        }
    } else {
        $('#divInputLimitsnummin').addClass('has-success');
        $('#divInputLimitsnummax').addClass('has-success');
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
            $('#divInputLimitsalphamin').addClass('has-error');
        } else {
            $('#divInputLimitsalphamin').addClass('has-success');
        }
        if( $empty2 || $test_RegexOnlyChar_AlphaMax == false ){
            $('#divInputLimitsalphamax').addClass('has-error');
        } else {
            $('#divInputLimitsalphamax').addClass('has-success');
        }
    } else {
        $('#divInputLimitsalphamin').addClass('has-success');
        $('#divInputLimitsalphamax').addClass('has-success');
    }
    $empty1 = $('#frm_limitalphanummin').val().trim() == '';
    $empty2 = $('#frm_limitalphanummax').val().trim() == '';
    if( ( $_settings.mandatory_limitsalphanum && ( $empty1 && $empty2 ) ) ||
        ( !$_settings.mandatory_limitsalphanum && ( ( !$empty1 && $empty2 ) || ( $empty1 && !$empty2 ) ) ) ) {
        $retour = false;
        if( $empty1 ){
            $('#divInputLimitsalphanummin').addClass('has-error');
        } else {
            $('#divInputLimitsalphanummin').addClass('has-success');
        }
        if( $empty2 ){
            $('#divInputLimitsalphanummax').addClass('has-error');
        } else {
            $('#divInputLimitsalphanummax').addClass('has-success');
        }
    } else {
        $('#divInputLimitsalphanummin').addClass('has-success');
        $('#divInputLimitsalphanummax').addClass('has-success');
    }
    if( $_settings.view_filenumber && $_settings.mandatory_filenumber && $('#frm_documentnumber').val().trim() == '' ){
        $retour = false;
        $('#divInputFilenumber').addClass('has-error');
    } else {
        $('#divInputFilenumber').addClass('has-success');
    }
    if( $_settings.view_boxnumber && $_settings.mandatory_boxnumber && $('#frm_boxnumber').val().trim() == '' ){
        $retour = false;
        $('#divInputBoxnumber').addClass('has-error');
    } else {
        $('#divInputBoxnumber').addClass('has-success');
    }
    if( $_settings.view_containernumber && $_settings.mandatory_containernumber && $('#frm_containernumber').val().trim() == '' ){
        $retour = false;
        $('#divInputContainernumber').addClass('has-error');
    } else {
        $('#divInputContainernumber').addClass('has-success');
    }
    if( $_settings.view_provider && $_settings.mandatory_provider && $('#frm_provider option:selected').val() == '' ){
        $retour = false;
        $('#divSelectProvider').addClass('has-error');
    } else {
        $('#divSelectProvider').addClass('has-success');
    }
    if( $uawhere == UAWHERE_PROVIDER ){
        if( $('#frm_localization option:selected').val() == '' ){
            $retour = false;
            $('#divSelectLocalization').addClass('has-error');
        } else {
            $('#divSelectLocalization').addClass('has-success');
        }
    } else {
        if( $('#frm_localizationfree').val().trim() == '' ){
            $retour = false;
            $('#divInputLocalizationfree').addClass('has-error');
        } else {
            $('#divInputLocalizationfree').addClass('has-success');
        }
    }
    return $retour;
}


$('#divPrint').click(function(){
    var $params = new Array();
    $params['id'] = $('#frm_id').val();
    get(window.JSON_URLS.bs_idp_archive_print_sheet, $params, true );
});

// Export and Export Partial button management
$('#divExport').click(function( event ){
    event.preventDefault();

    exportArray( $('#listarchives'), $_currentFCT, UASTATE_MANAGECLOSE, $uawhat, $uawhere, $uahow, -1, false );
});
$('#divExportPartial').click( function( event ){
    event.preventDefault();

    exportArray( $('#listarchives'), $_currentFCT, UASTATE_MANAGECLOSE, $uawhat, $uawhere, $uahow, -1, true );
});


$('#divPrintList').click(function(){
    if( $('#listarchives').bootstrapTable('getData').length > 0 )
        printTable( false, $('#listarchives'), 3, window.IDP_CONST.bs_idp_current_page );
});
$('#divPrintPartialList').click(function(){
    if( $('#listarchives').bootstrapTable('getSelections').length > 0 )
        printTable( true, $('#listarchives'), 3 );
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
                bootbox.alert( {
                    message: $_translations[87],
                    className: "boxSysErrorOne"
                } );
                $('#viewArchive').hide();
            }
        });
    } else {
        $('#modalErrorMessage').modal( 'show' );
    }
    return true;
};

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
                message: $_translations[94],
                className: "boxSysErrorOne"
            } );
        } else {
            $filter_provider = $('#filter_provider').val();
            $('#listarchives').bootstrapTable('refresh', {pageNumber: 1});
        }
    }

})
