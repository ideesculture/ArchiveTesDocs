

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
            'localization': -1,
            'localizationfree': '',
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
                $('#listDTATable').bootstrapTable('refresh');
                $('#divPrint').show();
                if ($_buttonsOverlay & 16)
                    $('#divPrintTag').show();

            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert( 'Error Ajax' );
                $('#viewArchive').hide();
            }
        });
    } else {
        $('#modalErrorMessage').modal( 'show' );
    }
    return true;
};

function clearViewClass() {
    $('#divViewSelectService').removeClass('has-success');
    $('#divViewSelectService').removeClass('has-error');

    $('#divViewSelectLegalentity').removeClass('has-success');
    $('#divViewSelectLegalentity').removeClass('has-error');

    $('#form_name').removeClass('has-success');
    $('#form_name').removeClass('has-error');

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
                popError( $('#frm_destructionyear'), "L'année de destruction ne peut pas être supérieure à 2199", 'top' );
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
    return $retour;
}

$('#divSubmitModif').click(function(){
    onClickBtnSubmitModif();
    currentOverlayViewUA = null;
    return true;
});
