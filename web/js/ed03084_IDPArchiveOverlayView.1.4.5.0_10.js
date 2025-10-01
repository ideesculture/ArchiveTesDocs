/**
 * Created by Cyril on 26/01/2016.
 */

// Work for Consult, Return, Exit and Retreive pages, with file partial.overlay.viewArchive.html.twig

// Function to init overlay view.
var currentOverlayViewUA = null;

var currentCommentsUnlimited = '';
var $_buttonsOverlay = null;

function initOverlay( ){

    $('#divViewLblLocalization').hide();
    $('#divViewSelectLocalization').hide();
    $('#divViewLblLocalizationfree').hide();
    $('#divViewInputLocalizationfree').hide();
    $('#divViewNothingLine1').hide();
    $('#divViewLblOldLocalization').hide();
    $('#divViewSelectOldLocalization').hide();
    $('#divViewLblOldLocalizationfree').hide();
    $('#divViewInputOldLocalizationfree').hide();
    $('#divLine0').hide();

    $('#frm_service').attr('disabled', true);
    $('#frm_service').html("<option value selected=\"selected\">N/A</option>");
    $('#frm_name').attr('disabled', true);
    $('#frm_limitnum').attr('disabled', true);
    $('#frm_limitalpha').attr('disabled', true);
    $('#frm_limitalphanum').attr('disabled', true);
    $('#frm_limitdate').attr('disabled', true);
    $('#frm_closureyear').attr('disabled', true);
    $('#frm_destructionyear').attr('disabled', true);
    $('#frm_ordernumber').attr('disabled', true);
    $('#frm_documentnumber').attr('disabled', true);
    $('#frm_boxnumber').attr('disabled', true);
    $('#frm_legalentity').attr('disabled', true);
    $('#frm_legalentity').html("<option value selected=\"selected\">N/A</option>");
    $('#frm_documentnature').attr('disabled', true);
    $('#frm_documentnature').html("<option value selected=\"selected\">N/A</option>");
    $('#frm_documenttype').attr('disabled', true);
    $('#frm_documenttype').html("<option value selected=\"selected\">N/A</option>");
    $('#frm_description1').attr('disabled', true);
    $('#frm_description1').html("<option value selected=\"selected\">N/A</option>");
    $('#frm_description2').attr('disabled', true);
    $('#frm_description2').html("<option value selected=\"selected\">N/A</option>");
    $('#frm_budgetcode').attr('disabled', true);
    $('#frm_budgetcode').html("<option value selected=\"selected\">N/A</option>");
    $('#frm_provider').attr('disabled', true);
    $('#frm_provider').html("<option value selected=\"selected\">N/A</option>");
    $('#frm_localization').attr('disabled', true);
    $('#frm_localization').html('<option value selected="selected">N/A</option>');
    $('#frm_localizationfree').attr('disabled', true);
    $('#frm_oldlocalization').attr('disabled', true);
    $('#frm_oldlocalization').html('<option value selected="selected">N/A</option>');
    $('#frm_oldlocalizationfree').attr('disabled', true);
}
function initOverlayLocalization( $localizationType, $oldLocalizationType ){
    switch( $localizationType ){
        case 0:
            $('#divViewLblLocalization').show();
            $('#divViewSelectLocalization').show();
            break;
        case 1:
            $('#divViewLblLocalizationfree').show();
            $('#divViewInputLocalizationfree').show();
            break;
        default:
            $('#divViewNothingLine1').show();
            break;
    }
    switch( $oldLocalizationType ){
        case 0:
            $('#divLine0').show();
            $('#divViewLblOldLocalization').show();
            $('#divViewSelectOldLocalization').show();
            break;
        case 1:
            $('#divLine0').show();
            $('#divViewLblOldLocalizationfree').show();
            $('#divViewInputOldLocalizationfree').show();
            break;
    }
}

// Function launched when user double click on a row in the result table
// @param row : on which the user clicked
// @param initLocalization: how to set localization fields (select / input / nothing)
// @param buttons: buttons to show MODIF(1) / DELETE(2) / CANCEL(4) / PRINT(8) / PRINTTAG(16), transmit sum of buttons value
function dblClickRow( row, $initLocalization, $initOldLocalization, $buttons ){

    currentOverlayViewUA = row;

    adminidlist = row['adminidlist'].split(",");
    getAjaxSettings(adminidlist[0], row, $initLocalization, $initOldLocalization, $buttons );
}
// Retreives the Settings based on the selected service
function getAjaxSettings( $serviceId, rowData, $initLocalization, $initOldLocalization, $buttons ){
    var $dataObj = {
        'serviceid': $serviceId
    };

    $.ajax({
        type: "GET",
        url: window.JSON_URLS.bs_idp_backoffice_ajax_settings,
        data: $dataObj,
        cache: false,
        success: function ( data ){
            $_settings = data;
            setView( rowData, $initLocalization, $initOldLocalization,  $buttons );
        },
        error: function ( xhr, ajaxOptions, thrownError ){
            alert( 'Error in getting settings' );
        }
    })
}
// Modify the overlay view according to new datas received
function setView( rowData, $initLocalization, $initOldLocalization, $buttons ){

    setHideField();
    clearViewClass();

    if( rowData != null ) {
        initViewWindow( rowData, $initLocalization, $initOldLocalization, $buttons );
    }
}
// Based on $_settings received (depends on service of the archive in the row) set vixibility of all fields
function setHideField( ){
    // Hide un-ask fields
    if( !$_settings.view_budgetcode ){
        $('#divViewLblBudgetcode').hide();
        $('#divViewSelectBudgetcode').hide();
    } else {
        $('#divViewLblBudgetcode').show();
        $('#divViewSelectBudgetcode').show();
        if( $_settings.mandatory_budgetcode ){
            $('#lbl_budgetcode').html( $_overlay[5] + '<span class="text-danger">*</span>' );
        }
    }
    if( !$_settings.view_documentnature ){
        $('#divViewLblDocumentnature').hide();
        $('#divViewSelectDocumentnature').hide();
        // Document type depends on Document nature, so hide it also
        $('#divViewLblDocumenttype').hide();
        $('#divViewSelectDocumenttype').hide();
    } else {
        $('#divViewLblDocumentnature').show();
        $('#divViewSelectDocumentnature').show();
        if( $_settings.mandatory_documentnature ){
            $('#lbl_documentnature').html( $_overlay[7] + '<span class="text-danger">*</span>' );
        }
    }
    if( !$_settings.view_documenttype ){
        $('#divViewLblDocumenttype').hide();
        $('#divViewSelectDocumenttype').hide();
    } else {
        if( $_settings.view_documentnature ){
            $('#divViewLblDocumenttype').show();
            $('#divViewSelectDocumenttype').show();
        }
        if( $_settings.mandatory_documenttype ){
            $('#lbl_documenttype').html( $_overlay[8] + '<span class="text-danger">*</span>' );
        }
    }
    if( !$_settings.view_description1 ){
        $('#divViewLblDescription1').hide();
        $('#divViewSelectDescription1').hide();
    } else {
        $('#divViewLblDescription1').show();
        $('#divViewSelectDescription1').show();
        $('#lbl_description1').html( $_settings.name_description1 + ($_settings.mandatory_description1?'<span class="text-danger">*</span>':''));
    }
    if( !$_settings.view_description2 ){
        $('#divViewLblDescription2').hide();
        $('#divViewSelectDescription2').hide();
    } else {
        $('#divViewLblDescription2').show();
        $('#divViewSelectDescription2').show();
        $('#lbl_description2').html( $_settings.name_description2 + ($_settings.mandatory_description2?'<span class="text-danger">*</span>':''));
    }
    if( !$_settings.view_documentnature && !$_settings.view_description1 && !$_settings.view_description2 ){
        $('#divBlockDescription').hide();
    } else {
        $('#divBlockDescription').show();
    }

    if( !$_settings.view_limitsdate ){
        $('#divViewLblLimitsdatemin').hide();
        $('#divViewLblLimitsdatemax').hide();
        $('#divViewInputLimitsdatemin').hide();
        $('#divViewInputLimitsdatemax').hide();
    } else {
        $('#divViewLblLimitsdatemin').show();
        $('#divViewLblLimitsdatemax').show();
        $('#divViewInputLimitsdatemin').show();
        $('#divViewInputLimitsdatemax').show();
        if( $_settings.mandatory_limitsdate ){
            $('#lbl_limitsdatemin').html( $_overlay[21] + '<span class="text-danger">*</span>');
            $('#lbl_limitsdatemax').html( $_overlay[22] + '<span class="text-danger">*</span>');
        }
    }
    if( !$_settings.view_limitsnum ){
        $('#divViewLblLimitsnummin').hide();
        $('#divViewLblLimitsnummax').hide();
        $('#divViewInputLimitsnummin').hide();
        $('#divViewInputLimitsnummax').hide();
    } else {
        $('#divViewLblLimitsnummin').show();
        $('#divViewLblLimitsnummax').show();
        $('#divViewInputLimitsnummin').show();
        $('#divViewInputLimitsnummax').show();
        if( $_settings.mandatory_limitsnum ){
            $('#lbl_limitsnummin').html( $_overlay[23] + '<span class="text-danger">*</span>');
            $('#lbl_limitsnummax').html( $_overlay[24] + '<span class="text-danger">*</span>');
        }
    }
    if( !$_settings.view_limitsalpha ){
        $('#divViewLblLimitsalphamin').hide();
        $('#divViewLblLimitsalphamax').hide();
        $('#divViewInputLimitsalphamin').hide();
        $('#divViewInputLimitsalphamax').hide();
    } else {
        $('#divViewLblLimitsalphamin').show();
        $('#divViewLblLimitsalphamax').show();
        $('#divViewInputLimitsalphamin').show();
        $('#divViewInputLimitsalphamax').show();
        if( $_settings.mandatory_limitsalpha ){
            $('#lbl_limitsalphamin').html( $_overlay[25] + '<span class="text-danger">*</span>');
            $('#lbl_limitsalphamax').html( $_overlay[26] + '<span class="text-danger">*</span>')
        }
    }
    if( !$_settings.view_limitsalphanum ){
        $('#divViewLblLimitsalphanummin').hide();
        $('#divViewLblLimitsalphanummax').hide();
        $('#divViewInputLimitsalphanummin').hide();
        $('#divViewInputLimitsalphanummax').hide();
    } else {
        $('#divViewLblLimitsalphanummin').show();
        $('#divViewLblLimitsalphanummax').show();
        $('#divViewInputLimitsalphanummin').show();
        $('#divViewInputLimitsalphanummax').show();
        if( $_settings.mandatory_limitsalphanum ){
            $('#lbl_limitsalphanummin').html( $_overlay[27] + '<span class="text-danger">*</span>');
            $('#lbl_limitsalphanummax').html( $_overlay[28] + '<span class="text-danger">*</span>');
        }
    }
    if( !$_settings.view_limitsdate && !$_settings.view_limitsalpha && !$_settings.view_limitsnum && !$_settings.view_limitsalphanum ){
        $('#divViewBlockLimits').hide();
    } else {
        $('#divViewBlockLimits').show();
    }

    if( !$_settings.view_filenumber ){
        $('#divViewLblFilenumber').hide();
        $('#divViewInputFilenumber').hide();
    } else {
        $('#divViewLblFilenumber').show();
        $('#divViewInputFilenumber').show();
        if( $_settings.mandatory_filenumber ){
            $('#lbl_filenumber').html( $_overlay[15] + '<span class="text-danger">*</span>');
        }
    }
    if( !$_settings.view_boxnumber ){
        $('#divViewLblBoxnumber').hide();
        $('#divViewInputBoxnumber').hide();
    } else {
        $('#divViewLblBoxnumber').show();
        $('#divViewInputBoxnumber').show();
        if( $_settings.mandatory_boxnumber ){
            $('#lbl_boxnumber').html( $_overlay[16] + '<span class="text-danger">*</span>');
        }
    }
    if( !$_settings.view_containernumber ){
        $('#divViewLblContainernumber').hide();
        $('#divViewInputContainernumber').hide();
    } else {
        $('#divViewLblContainernumber').show();
        $('#divViewInputContainernumber').show();
        if( $_settings.mandatory_containernumber ){
            $('#lbl_containernumber').html( $_overlay[17] + '<span class="text-danger">*</span>');
        }
    }
    if( !$_settings.view_provider ){
        $('#divViewLblProvider').hide();
        $('#divViewSelectProvider').hide();
    } else {
        $('#divViewLblProvider').show();
        $('#divViewSelectProvider').show();
        if( $_settings.mandatory_provider ){
            $('#lbl_provider').html( $_overlay[18] + '<span class="text-danger">*</span>');
        }
    }
    if( !$_settings.view_filenumber && !$_settings.view_boxnumber && !$_settings.view_containernumber && !$_settings.view_provider ){
        $('#divViewBlockProviderdatas').hide();
    } else {
        $('#divViewBlockProviderdatas').show();
    }

    $('#divLine0').hide();
    $('#divViewLblOldLocalization').hide();
    $('#divViewSelectOldLocalization').hide();
    $('#divViewLblOldLocalizationfree').hide();
    $('#divViewInputOldLocalizationfree').hide();
    $('#divViewLblLocalization').hide();
    $('#divViewSelectLocalization').hide();
    $('#divViewLblLocalizationfree').hide();
    $('#divViewInputLocalizationfree').hide();


}
// Set datas from the archive in the according fields of the overlay view
function initViewWindow( rowData, $initLocalization, $initOldLocalization, $buttons ){

    $('#frm_name').prop('disabled', false);
    updateView(rowData);

    $_buttonsOverlay = $buttons;
    if( $buttons ) {
        if ($buttons & 1)
            $('#divModif').show();
        else
            $('#divModif').hide();
        $('#divSubmitModif').hide();

        if ($buttons & 2)
            $('#divDelete').show();
        else
            $('#divDelete').hide();
        if ($buttons & 4)
            $('#divCancel').show();
        else
            $('#divCancel').hide();
        if ($buttons & 8)
            $('#divPrint').show();
        else
            $('#divPrint').hide();
        if ($buttons & 16)
            $('#divPrintTag').show();
        else
            $('#divPrintTag').hide();
    }

    switch( $initLocalization ){
        case 0: // UAWHERE_PROVIDER
            initOverlayLocalization( 0, $initOldLocalization );
            break;
        case 1:
        case 2:
            initOverlayLocalization( 1, $initOldLocalization );
            break;
        default:
            initOverlayLocalization( null, null );
            break;
    }

    $('#frm_ordernumber').prop('disabled', true);
    $('#frm_service').prop('disabled', true);
    $('#frm_legalentity').prop('disabled', true);
    $('#frm_budgetcode').prop('disabled', true);
    $('#frm_documentnature').prop('disabled', true);
    $('#frm_documenttype').prop('disabled', true);
    $('#frm_description1').prop('disabled', true);
    $('#frm_description2').prop('disabled', true);
    $('#frm_closureyear').prop('disabled', true);
    $('#frm_destructionyear').prop('disabled', true);
    $('#frm_documentnumber').prop('disabled', true);
    $('#frm_boxnumber').prop('disabled', true);
    $('#frm_containernumber').prop('disabled', true);
    $('#frm_provider').prop('disabled', true);
    $('#frm_name').prop('disabled', true);
    $('#frm_limitdatemin').prop('disabled', true);
    $('#frm_limitdatemax').prop('disabled', true);
    $('#frm_limitnummin').prop('disabled', true);
    $('#frm_limitnummax').prop('disabled', true);
    $('#frm_limitalphamin').prop('disabled', true);
    $('#frm_limitalphamax').prop('disabled', true);
    $('#frm_limitalphanummin').prop('disabled', true);
    $('#frm_limitalphanummax').prop('disabled', true);
    $('#frm_localization').prop('disabled', true);
    $('#frm_localizationfree').prop('disabled', true);
    $('#frm_oldlocalization').prop('disabled', true);
    $('#frm_oldlocalizationfree').prop('disabled', true);

    $('#frm_unlimited').prop('disabled', true );
    $('#btn_commentsunlimited').prop('disabled', false );

    $('#viewArchive').show();
}
// Clear the state colors of modification verification
// Only used when modification is allowed
function clearViewClass() {
    $('#divViewSelectService').removeClass('has-success');
    $('#divViewSelectService').removeClass('has-error');

    $('#divViewSelectLegalentity').removeClass('has-success');
    $('#divViewSelectLegalentity').removeClass('has-error');

    $('#frm_name').removeClass('has-success');
    $('#frm_name').removeClass('has-error');

    $('#divViewInputClosureyear').removeClass('has-success');
    $('#divViewInputClosureyear').removeClass('has-error');

    $('#divViewInputDestructionyear').removeClass('has-success');
    $('#divViewInputDestructionyear').removeClass('has-error');

    if( $_settings.view_budgetcode  ){
        $('#divViewSelectBudgetcode').removeClass('has-success');
        $('#divViewSelectBudgetcode').removeClass('has-error');
    }

    if( $_settings.view_documentnature  ){
        $('#divViewSelectDocumentnature').removeClass('has-success');
        $('#divViewSelectDocumentnature').removeClass('has-error');
    }

    if( $_settings.view_documentnature  ){
        $('#divViewSelectDocumenttype').removeClass('has-success');
        $('#divViewSelectDocumenttype').removeClass('has-error');
    }

    if( $_settings.view_description1  ){
        $('#divViewSelectDescription1').removeClass('has-success');
        $('#divViewSelectDescription1').removeClass('has-error');
    }

    if( $_settings.view_description2  ){
        $('#divViewSelectDescription2').removeClass('has-success');
        $('#divViewSelectDescription2').removeClass('has-error');
    }
    if( $_settings.view_limitsdate  ){
        $('#divViewInputLimitsdatemin').removeClass('has-success')
        $('#divViewInputLimitsdatemin').removeClass('has-error');
        $('#divViewInputLimitsdatemax').removeClass('has-success');
        $('#divViewInputLimitsdatemax').removeClass('has-error');
    }

    if( $_settings.view_limitsnum  ){
        $('#divViewInputLimitsnummin').removeClass('has-success')
        $('#divViewInputLimitsnummin').removeClass('has-error');
        $('#divViewInputLimitsnummax').removeClass('has-error')
        $('#divViewInputLimitsnummax').removeClass('has-success');
    }

    if( $_settings.view_limitsalpha  ){
        $('#divViewInputLimitsalphamin').removeClass('has-success')
        $('#divViewInputLimitsalphamin').removeClass('has-error');
        $('#divViewInputLimitsalphamax').removeClass('has-error')
        $('#divViewInputLimitsalphamax').removeClass('has-success');
    }

    if( $_settings.view_limitsalphanum  ){
        $('#divViewInputLimitsalphanummin').removeClass('has-success')
        $('#divViewInputLimitsalphanummin').removeClass('has-error');
        $('#divViewInputLimitsalphanummax').removeClass('has-error')
        $('#divViewInputLimitsalphanummax').removeClass('has-success');
    }

    if( $_settings.view_filenumber  ){
        $('#divViewInputFilenumber').removeClass('has-success');
        $('#divViewInputFilenumber').removeClass('has-error');
    }

    if( $_settings.view_boxnumber  ){
        $('#divViewInputBoxnumber').removeClass('has-success');
        $('#divViewInputBoxnumber').removeClass('has-error');
    }

    if( $_settings.view_containernumber  ){
        $('#divViewInputContainernumber').removeClass('has-success');
        $('#divViewInputContainernumber').removeClass('has-error');
    }

    if( $_settings.view_provider  ){
        $('#divViewSelectProvider').removeClass('has-success');
        $('#divViewSelectProvider').removeClass('has-error');
    }

    $('#divViewSelectLocalization').removeClass('has-success');
    $('#divViewSelectLocalization').removeClass('has-error');
    $('#divViewInputLocalizationfree').removeClass('has-success');
    $('#divViewInputLocalizationfree').removeClass('has-error');

    $('#form_name').removeClass('has-success');
    $('#form_name').removeClass('has-error');
}

function date2string( myDate ){
    var dateString = myDate.date;
    var yyyy = dateString.substr( 0, 4 );
    var mm = dateString.substr( 5, 2 );
    var dd  = dateString.substr( 8, 2 );
    return dd + '/' + mm + '/' + yyyy ; // padding
}
// Update the view with datas of the archive selected
function updateView( row ){

    adminidlist = row['adminidlist'].split(",");

    // update id
    $('#frm_id').val( row['id'] );
    // update order_number
    $('#frm_ordernumber').val( row['ordernumber'] );
    // update service with default value, all list will be updated concordingly
    $defaultService =  adminidlist[0];
    $defaultLegalEntity =  adminidlist[1];
    $defaultBudgetCode =  adminidlist[2];
    $defaultDocumentNature =  adminidlist[3];
    $defaultDocumentType =  adminidlist[4];
    $defaultDescription1 =  adminidlist[5];
    $defaultDescription2 =  adminidlist[6];
    $defaultProvider =  adminidlist[7];
    $defaultLocalization = adminidlist[8];
    $defaultOldLocalization = adminidlist[9];
    updateServices( $defaultService );
    // Update Closure year
    $('#frm_closureyear').val( row['closureyear'] );
    // Update Destruction year
    $('#frm_destructionyear').val( row['destructionyear'] );
    // Update Document numbe
    if( row['documentnumber'] != '-' )
        $('#frm_documentnumber').val( row['documentnumber'] );
    else
        $('#frm_documentnumber').val('');
    // Update Box Number
    if( row['boxnumber'] != '-' )
        $('#frm_boxnumber').val( row['boxnumber'] );
    else
        $('#frm_boxnumber').val('');
    // Update Container number
    if( row['containernumber'] != '-' )
        $('#frm_containernumber').val( row['containernumber'] );
    else
        $('#frm_containernumber').val( '' );
    // Update name
    //$('#frm_name').html( row['name'] );
    $('#frm_name').val( row['name'] );
    // Update limit date min & max
    if( typeof( row['limitdatemin'] ) === 'object' )
        $('#frm_limitdatemin').val(date2string(row['limitdatemin']));
    else
        if( row['limitdatemin'] != '-' )
            $('#frm_limitdatemin').val( row['limitdatemin'] );
        else
            $('#frm_limitdatemin').val('');
    if( typeof(row['limitdatemax'])==='object')
        $('#frm_limitdatemax').val(date2string(row['limitdatemax']));
    else
        if( row['limitdatemax'] != '-' )
            $('#frm_limitdatemax').val( row['limitdatemax'] );
        else
            $('#frm_limitdatemax').val( '' );
    // Update limit num min & max
    if( row['limitnummin'] != '-' )
        $('#frm_limitnummin').val( row['limitnummin'] );
    else
        $('#frm_limitnummin').val('');
    if( row['limitnummax'] != '-' )
        $('#frm_limitnummax').val( row['limitnummax'] );
    else
        $('#frm_limitnummax').val('');
    // Update limit alpha min & max
    if( row['limitalphamin'] != '-' )
        $('#frm_limitalphamin').val( row['limitalphamin'] );
    else
        $('#frm_limitalphamin').val('');
    if( row['limitalphamax'] != '-' )
        $('#frm_limitalphamax').val( row['limitalphamax'] );
    else
        $('#frm_limitalphamax').val('');
    // Update limit alphanum min & max
    if( row['limitalphanummin'] != '-' )
        $('#frm_limitalphanummin').val( row['limitalphanummin'] );
    else
        $('#frm_limitalphanummin').val('');
    if( row['limitalphanummax'] != '-' )
        $('#frm_limitalphanummax').val( row['limitalphanummax'] );
    else
        $('#frm_limitalphanummax').val('');
    // Update localisation free
    if( row['localizationfree'] != '-' )
        $('#frm_localizationfree').val( row['localizationfree'] );
    else
        $('#frm_localizationfree').val('');
    if( row['oldlocalizationfree'] != '-' )
        $('#frm_oldlocalizationfree').val( row['oldlocalizationfree'] );
    else
        $('#frm_oldlocalizationfree').val('');

    // Update unlimited
    if( row['unlimited'] == 'actif' ){
        $('#frm_unlimited').prop('checked', true );
        $('#btn_commentsunlimited').show();
        currentCommentsUnlimited = row['unlimitedcomments'];
    } else {
        $('#frm_unlimited').prop('checked', false );
        $('#btn_commentsunlimited').hide();
        currentCommentsUnlimited = '-';
    }
}

$('#btn_commentsunlimited').click( function( event ){
    event.preventDefault();

    currentCommentsUnlimited = (currentCommentsUnlimited===null)?'-':currentCommentsUnlimited;
    bootbox.dialog( {
        size: "small",
        title: "Commentaires",
        className: "bringToFront,boxInfoOne",
        message: currentCommentsUnlimited  });
});

// Used with modify action enabled
$('#frm_unlimited').change(function(event){
    event.preventDefault();

    if ($('#frm_unlimited').is(':checked')){
        $('#btn_commentsunlimited').show();
        $('#frm_destructionyear').prop('disabled', true);
        bootbox.prompt({
            size: "small",
            title: "Commentaires d'illimité ?",
            className: "bowQuestionTwo",
            callback: function(result){ currentCommentsUnlimited = result; }
        });
    } else {
        $('#btn_commentsunlimited').hide();
        $('#frm_destructionyear').prop('disabled', false);
        currentCommentsUnlimited = '';
    }
})

// On Cancel button hit, just hide the view; everything is done when showing
$('#divCancel').click(function( event ){
    event.preventDefault();

    $('#viewArchive').hide();
    $('#divPrint').show();
    if ($_buttonsOverlay & 16)
        $('#divPrintTag').show();
    currentOverlayViewUA = null;
});

// On Modif button hit, enable all modifiable elements
$('#divModif').click(function( event ){
    event.preventDefault();

    $('#frm_ordernumber').prop('disabled', true);
    $('#frm_closureyear').prop('disabled', false);
    $('#frm_destructionyear').prop('disabled', false);
    $('#frm_documentnumber').prop('disabled', false);
    $('#frm_boxnumber').prop('disabled', false);
    $('#frm_containernumber').prop('disabled', false);
    $('#frm_name').prop('disabled', false);
    $('#frm_limitdatemin').prop('disabled', false);
    $('#frm_limitdatemax').prop('disabled', false);
    $('#frm_limitnummin').prop('disabled', false);
    $('#frm_limitnummax').prop('disabled', false);
    $('#frm_limitalphamin').prop('disabled', false);
    $('#frm_limitalphamax').prop('disabled', false);
    $('#frm_limitalphanummin').prop('disabled', false);
    $('#frm_limitalphanummax').prop('disabled', false);
    $('#frm_localization').prop('disabled', false);
    $('#frm_localizationfree').prop('disabled', false);
    $('#frm_unlimited').prop('disabled', false);
    $('#btn_commentsunlimited').prop('disabled', false);
    /* Old localization cannot be modified !
    $('#frm_oldlocalization').prop('disabled', false);
    $('#frm_oldlocalizationfree').prop('disabled', false);
    */
    $('#divModif').hide();
    $('#divPrint').hide();
    $('#divPrintTag').hide();

    // Modify 'disabled' linked to coherency
    $('#frm_service').prop('disabled', false)
    if( $("#frm_service option:selected").val() != "" ){
        $('#frm_legalentity').prop('disabled', false);
        $('#frm_budgetcode').prop('disabled', false);
        $('#frm_description1').prop('disabled', false);
        $('#frm_description2').prop('disabled', false);
        $('#frm_provider').prop('disabled', false);
        $('#frm_localization').prop('diabled', false);
    }
    if( $("#frm_legalentity option:selected").val() != "" )
        $('#frm_documentnature').prop('disabled', false);
    if( $("#frm_documentnature option:selected").val() != "" )
        $('#frm_documenttype').prop('disabled', false);

    // BZ#29 disabled provider and localization everywhere except transfer page, reloc page (in user part) and transfer tab and reloc tab (in archivist part)
    if( window.IDP_CONST.bs_idp_current_page != 1 &&
        window.IDP_CONST.bs_idp_current_page != 6 &&
        !( window.IDP_CONST.bs_idp_current_page >= 7 && $uawhat == UAWHAT_TRANSFER ) &&
        !( window.IDP_CONST.bs_idp_current_page >= 7 && $uawhat == UAWHAT_RELOC )){

        $('#frm_provider').attr('disabled', true);
        $('#frm_localization').attr('disabled', true);
        $('#frm_localizationfree').attr('disabled', true);
    }

    // BZ#38 Disabled field during relocalisation
    if( currentOverlayViewUA != null ) {
        if ( $.inArray(currentOverlayViewUA['statuscaps'], RELOC_FIELD_DISABLED_STATUS ) >= 0 ) {
            $('#frm_documentnumber').prop('disabled', true);
            $('#frm_boxnumber').prop('disabled', true);
            $('#frm_containernumber').prop('disabled', true);
            $('#frm_provider').prop('disabled', true);
        }
    }

    $('#divSubmitModif').show();

});

function initLists(){
    $.ajax({
        type: "POST",
        url: window.JSON_URLS.bs_idp_archive_form_initlists,
        data: null,
        cache: false,
        success:
        updateLists,
        error: function (xhr, ajaxOptions, thrownError) {
            alert( 'Error' );
        }
    });

}
