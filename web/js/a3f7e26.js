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
};
// Function to send a get to server
// get('/contact/', {name: 'Johnny Bravo'});
function get(path, params, external) {
    var form = document.createElement("form");
    form.setAttribute("method", "get");
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
};

function popError( $ancre, $message, $where ){
    $ancre.popover({trigger:'manual', placement: $where, content: $message });
    $ancre.popover('show').addClass('has-error');
    $ancre.addClass( 'has-error' );
    $('body').click(function(){
        $ancre.popover('destroy');
        $ancre.removeClass( 'has-error' );
    });
}


var $services = [];
var $legalEntities = [];
var $budgetCodes = [];
var $documentNatures = [];
var $documentTypes = [];
var $descriptions1 = [];
var $descriptions2 = [];
var $providers = [];

var $defaultService = -1;
var $defaultLegalEntity = -1;
var $defaultBudgetCode = -1;
var $defaultDocumentNature = -1;
var $defaultDocumentType = -1;
var $defaultDescription1 = -1;
var $defaultDescription2 = -1;
var $defaultProvider = -1;

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

var UAFIELD_SERVICE 			= 0;
var UAFIELD_LEGALENTITY 		= 1;
var UAFIELD_BUDGETCODE 		= 2;
var UAFIELD_DOCUMENTNATURE	= 3;
var UAFIELD_DOCUMENTTYPE		= 4;
var UAFIELD_DESCRIPTION1		= 5;
var UAFIELD_DESCRIPTION2		= 6;
var UAFIELD_CLOSUREYEAR		= 7;
var UAFIELD_DESTRUCTIONYEAR	= 8;
var UAFIELD_DOCUMENTNUMBER	= 9;
var UAFIELD_BOXNUMBER			=10;
var UAFIELD_CONTAINERNUMBER	=11;
var UAFIELD_PROVIDER			=12;
var UAFIELD_NAME				=13;
var UAFIELD_LIMITDATEMIN		=14;
var UAFIELD_LIMITDATEMAX		=15;
var UAFIELD_LIMITNUMMIN		=16;
var UAFIELD_LIMITNUMMAX		=17;
var UAFIELD_LIMITALPHAMIN		=18;
var UAFIELD_LIMITALPHAMAX		=19;
var UAFIELD_LIMITALPHANUMMIN	=20;
var UAFIELD_LIMITALPHANUMMAX	=21;

var $limitdatemin_valid = false;
var $limitdatemax_valid = false;

var $_settings = null;

// Retreives the Settings based on the selected service
function getAjaxSettings( $serviceId ){
	if( $serviceId ) {
        var $dataObj = {
            'serviceid': $serviceId
        };

        $.ajax({
            type: "GET",
            url: window.JSON_URLS.bs_idp_backoffice_ajax_settings,
            data: $dataObj,
            cache: false,
            success: function (data) {
                $_settings = data;
                setView();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert('Error in getting settings');
            }
        })
    }
}

function setView( ){
    // Hide un-ask fields
    if( !$_settings.view_budgetcode ){
        $('#divLblBudgetcode').hide();
        $('#divSelectBudgetcode').hide();
    } else {
        $('#divLblBudgetcode').show();
        $('#divSelectBudgetcode').show();
        $('#lbl_budgetcode').html( $_translations[11] + ($_settings.mandatory_budgetcode?'<span class="text-danger">*</span>':'') );
    }
    if( !$_settings.view_documentnature ){
        $('#divLblDocumentnature').hide();
        $('#divSelectDocumentnature').hide();
        // Document type depends on Document nature, so hide it also
        $('#divLblDocumenttype').hide();
        $('#divSelectDocumenttype').hide();
    } else {
        $('#divLblDocumentnature').show();
        $('#divSelectDocumentnature').show();
        $('#lbl_documentnature').html( $_translations[13] + ($_settings.mandatory_documentnature?'<span class="text-danger">*</span>':'') );
    }
    if( !$_settings.view_documenttype ){
        $('#divLblDocumenttype').hide();
        $('#divSelectDocumenttype').hide();
    } else {
        if( $_settings.view_documentnature ) {
            $('#divLblDocumenttype').show();
            $('#divSelectDocumenttype').show();
        }
        $('#lbl_documenttype').html( $_translations[14] + ($_settings.mandatory_documenttype?'<span class="text-danger">*</span>':'') );
    }
    if( !$_settings.view_description1 ){
        $('#divLblDescription1').hide();
        $('#divSelectDescription1').hide();
    } else {
        $('#divLblDescription1').show();
        $('#divSelectDescription1').show();
        $('#lbl_description1').html( $_settings.name_description1 + ($_settings.mandatory_description1?'<span class="text-danger">*</span>':''));
    }
    if( !$_settings.view_description2 ){
        $('#divLblDescription2').hide();
        $('#divSelectDescription2').hide();
    } else {
        $('#divLblDescription2').show();
        $('#divSelectDescription2').show();
        $('#lbl_description2').html( $_settings.name_description2 + ($_settings.mandatory_description2?'<span class="text-danger">*</span>':''));
    }
    if( !$_settings.view_documentnature && !$_settings.view_description1 && !$_settings.view_description2 ){
        $('#divBlockDescription').hide();
    } else {
        $('#divBlockDescription').show();
    }

    if( !$_settings.view_limitsdate ){
        $('#divLblLimitsdatemin').hide();
        $('#divLblLimitsdatemax').hide();
        $('#divInputLimitsdatemin').hide();
        $('#divInputLimitsdatemax').hide();
        $('#frm_limitdatemin').val('');
        $('#frm_limitdatemax').val('');
    } else {
        $('#divLblLimitsdatemin').show();
        $('#divLblLimitsdatemax').show();
        $('#divInputLimitsdatemin').show();
        $('#divInputLimitsdatemax').show();
        $('#lbl_limitsdatemin').html( $_translations[27] + ($_settings.mandatory_limitsdate?'<span class="text-danger">*</span>':'') );
        $('#lbl_limitsdatemax').html( $_translations[28] + ($_settings.mandatory_limitsdate?'<span class="text-danger">*</span>':'') );
    }
    if( !$_settings.view_limitsnum ){
        $('#divLblLimitsnummin').hide();
        $('#divLblLimitsnummax').hide();
        $('#divInputLimitsnummin').hide();
        $('#divInputLimitsnummax').hide();
        $('#frm_limitnummin').val('');
        $('#frm_limitnummax').val('');
    } else {
        $('#divLblLimitsnummin').show();
        $('#divLblLimitsnummax').show();
        $('#divInputLimitsnummin').show();
        $('#divInputLimitsnummax').show();
        $('#lbl_limitsnummin').html( $_translations[29] + ($_settings.mandatory_limitsnum?'<span class="text-danger">*</span>':'') );
        $('#lbl_limitsnummax').html( $_translations[30] + ($_settings.mandatory_limitsnum?'<span class="text-danger">*</span>':'') );
    }
    if( !$_settings.view_limitsalpha ){
        $('#divLblLimitsalphamin').hide();
        $('#divLblLimitsalphamax').hide();
        $('#divInputLimitsalphamin').hide();
        $('#divInputLimitsalphamax').hide();
        $('#frm_limitalphamin').val('');
        $('#frm_limitalphamax').val('');
    } else {
        $('#divLblLimitsalphamin').show();
        $('#divLblLimitsalphamax').show();
        $('#divInputLimitsalphamin').show();
        $('#divInputLimitsalphamax').show();
        $('#lbl_limitsalphamin').html( $_translations[31] + ($_settings.mandatory_limitsalpha?'<span class="text-danger">*</span>':'') );
        $('#lbl_limitsalphamax').html( $_translations[32] + ($_settings.mandatory_limitsalpha?'<span class="text-danger">*</span>':'') );
    }
    if( !$_settings.view_limitsalphanum ){
        $('#divLblLimitsalphanummin').hide();
        $('#divLblLimitsalphanummax').hide();
        $('#divInputLimitsalphanummin').hide();
        $('#divInputLimitsalphanummax').hide();
        $('#frm_limitalphanummin').val('');
        $('#frm_limitalphanummax').val('');
    } else {
        $('#divLblLimitsalphanummin').show();
        $('#divLblLimitsalphanummax').show();
        $('#divInputLimitsalphanummin').show();
        $('#divInputLimitsalphanummax').show();
        $('#lbl_limitsalphanummin').html( $_translations[33] + ($_settings.mandatory_limitsalphanum?'<span class="text-danger">*</span>':'') );
        $('#lbl_limitsalphanummax').html( $_translations[34] + ($_settings.mandatory_limitsalphanum?'<span class="text-danger">*</span>':'') );
    }
    if( !$_settings.view_limitsdate && !$_settings.view_limitsalpha && !$_settings.view_limitsnum && !$_settings.view_limitsalphanum ){
        $('#divBlockLimits').hide();
    } else {
        $('#divBlockLimits').show();
    }

    if( !$_settings.view_filenumber ){
        $('#divLblFilenumber').hide();
        $('#divInputFilenumber').hide();
        $('#frm_documentnumber').val('');
    } else {
        $('#divLblFilenumber').show();
        $('#divInputFilenumber').show();
        $('#lbl_filenumber').html( $_translations[21] + ($_settings.mandatory_filenumber?'<span class="text-danger">*</span>':'') );
    }
    if( !$_settings.view_boxnumber ){
        $('#divLblBoxnumber').hide();
        $('#divInputBoxnumber').hide();
        $('#frm_boxnumber').val('');
    } else {
        $('#divLblBoxnumber').show();
        $('#divInputBoxnumber').show();
        $('#lbl_boxnumber').html( $_translations[22] + ($_settings.mandatory_boxnumber?'<span class="text-danger">*</span>':'') );
    }
    if( !$_settings.view_containernumber ){
        $('#divLblContainernumber').hide();
        $('#divInputContainernumber').hide();
        $('#frm_containernumber').val('');
    } else {
        $('#divLblContainernumber').show();
        $('#divInputContainernumber').show();
        $('#lbl_containernumber').html( $_translations[23] + ($_settings.mandatory_containernumber?'<span class="text-danger">*</span>':'') );
    }
    if( !$_settings.view_provider ){
        $('#divLblProvider').hide();
        $('#divSelectProvider').hide();
    } else {
        $('#divLblProvider').show();
        $('#divSelectProvider').show();
        $('#lbl_provider').html( $_translations[24] + ($_settings.mandatory_provider?'<span class="text-danger">*</span>':'') );
    }
    if( !$_settings.view_filenumber && !$_settings.view_boxnumber && !$_settings.view_containernumber && !$_settings.view_provider ){
        $('#divBlockProviderdatas').hide();
    } else {
        $('#divBlockProviderdatas').show();
    }
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

		$serviceOptions = "<option value=\"-1\"></option>";
		i = 0;
		$bSelected = false;
		$services.forEach(function($serviceLine){
			$selected = "";
			if( $serviceLine[SERVICE_ID] == parseInt( $defaultService ) ){
				$bSelected = true;
				$selected = " selected='selected' ";
			}
			$serviceOptions += "<option value=\"" + $serviceLine[SERVICE_ID] + "\" data=\"" + i + "\" " + $selected + ">" + $serviceLine[SERVICE_NAME] + "</option> ";
			i = i+1;
		});
		$('#frm_service').html( $serviceOptions );

		$('#frm_service').attr('disabled', false);
		$('#frm_name').attr('disabled', false);
		$('#frm_limitnum').attr('disabled', false);
		$('#frm_limitalpha').attr('disabled', false);
		$('#frm_limitalphanum').attr('disabled', false);
		$('#frm_limitdate').attr('disabled', false);
		$('#frm_closureyear').attr('disabled', false);
		$('#frm_destructionyear').attr('disabled', false);
		$('#frm_documentnumber').attr('disabled', false);
		$('#frm_boxnumber').attr('disabled', false);
		$('#frm_containernumber').attr('disabled', false);

		if( $bSelected ){
			updateLegalEntitiesList( $defaultLegalEntity );
			updateBudgetCodesList( $defaultBudgetCode );
			updateDescriptions1List( $defaultDescription1 );
			updateDescriptions2List( $defaultDescription2 );
			updateProvidersList( $defaultProvider );
			updateDocumentNaturesList( $defaultDocumentNature );
		}

		return true;
	}

	$('#ajaxError').html( $_translations[39] );
	$('#ajaxError').removeClass('hidden');
	return false;
}

$(document).ready(function(){

	// get archive in 'modification
	$ua = JSON.parse( window.IDP_CONST.bs_idp_archive )[0];
    $_translations = JSON.parse( window.IDP_CONST.bs_translations );

	// retreive default values
	$defaultService = ('service_id' in $ua)?parseInt( $ua.service_id ):-1;
	$defaultLegalEntity = ('legalentity_id' in $ua)?parseInt( $ua.legalentity_id ):-1;
	$defaultBudgetCode = ('budgetcode_id' in $ua)?parseInt( $ua.budgetcode_id ):-1;
	$defaultDocumentNature = ('documentnature_id' in $ua)?parseInt( $ua.documentnature_id ):-1;
	$defaultDocumentType = ('documenttype_id' in $ua)?parseInt( $ua.documenttype_id ):-1;
	$defaultDescription1 = ('description1_id' in $ua)?parseInt( $ua.description1_id ):-1;
	$defaultDescription2 = ('description2_id' in $ua)?parseInt( $ua.description2_id ):-1;
	$defaultProvider = ('provider_id' in $ua)?parseInt( $ua.provider_id ):-1;

	// set default values to all fields (except lists)
	$('#frm_id').val( $ua.id );

	$('#frm_ordernumber').val( $ua.ordernumber );
	$('#frm_closureyear').val( $ua.closureyear );
	$('#frm_destructionyear').val( $ua.destructionyear );
	$('#frm_documentnumber').val( $ua.documentnumber );
	$('#frm_boxnumber').val( $ua.boxnumber );
	$('#frm_containernumber').val( $ua.containernumber );
	$('#frm_name').html( $ua.name );

	if( $ua.limitdatemin ){
		$datelimitmin = $ua.limitdatemin.date;
		$('#frm_limitdatemin').val( $datelimitmin.substr( 8, 2 ) + "/" + $datelimitmin.substr( 5, 2 ) + "/" + $datelimitmin.substr( 0, 4 )  );
	}
	if( $ua.limitdatemax ){
		$datelimitmax = $ua.limitdatemax.date;
		$('#frm_limitdatemax').val( $datelimitmax.substr( 8, 2 ) + "/" + $datelimitmax.substr( 5, 2 ) + "/" + $datelimitmax.substr( 0, 4 ) );
	}

	$('#frm_limitnummin').val( $ua.limitnummin );
	$('#frm_limitnummax').val( $ua.limitnummax );
	$('#frm_limitalphamin').val( $ua.limitalphamin );
	$('#frm_limitalphamax').val( $ua.limitalphamax );
	$('#frm_limitalphanummin').val( $ua.limitalphanummin );
	$('#frm_limitalphanummax').val( $ua.limitalphanummax );


	$('#frm_service').attr('disabled', true);
	$('#frm_service').html("<option value selected=\"selected\"></option>");
	$('#frm_legalentity').html("<option value selected=\"selected\"></option>");
	$('#frm_documentnature').attr('disabled', true);
	$('#frm_documentnature').html("<option value selected=\"selected\"></option>");
	$('#frm_documenttype').attr('disabled', true);
	$('#frm_documenttype').html("<option value selected=\"selected\"></option>");
	$('#frm_description1').attr('disabled', true);
	$('#frm_description1').html("<option value selected=\"selected\"></option>");
	$('#frm_description2').attr('disabled', true);
	$('#frm_description2').html("<option value selected=\"selected\"></option>");
	$('#frm_budgetcode').attr('disabled', true);
	$('#frm_budgetcode').html("<option value selected=\"selected\"></option>");
	$('#frm_provider').attr('disabled', true);
	$('#frm_provider').html("<option value selected=\"selected\"></option>");

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

	$.ajax({
		type: "POST",
		url: window.JSON_URLS.bs_idp_archive_form_initlists,
		data: null,
		cache: false,
		success: updateLists,
		error: function (xhr, ajaxOptions, thrownError) {
			$('#ajaxError').html( window.IDP_CONST.bs_translation_39 );
			$('#ajaxError').removeClass('hidden');
		}
	});

	// Activate Datepicker on both Date fields
    $('#frm_limitdatemin').datepicker( {
        'format': 'dd/mm/yyyy',
        'autoclose': true
    })
        .on( 'changeDate', function( event ){
            $('#frm_limitdatemin').datepicker( 'hide' );
        })
        .on( 'hide', function( event ){
            focusBackupLimitDate();
        });
    $('#frm_limitdatemax').datepicker({
        'format': 'dd/mm/yyyy',
        'autoclose': true
    })
        .on( 'changeDate', function( event ){
            $('#frm_limitdatemax').datepicker( 'hide' );
        })
        .on( 'hide', function( event ){
            focusBackupLimitDate();
        });

    // Get back settings
    getAjaxSettings( $defaultService<0?0:$defaultService );

});

function popError( $ancre, $message, $where ){
	$ancre.addClass('has-error');
	$ancre.popover({trigger:'manual', placement: $where, content: $message });
	$ancre.popover('show').addClass('has-error');
	$ancre.click(function(){ $ancre.popover('hide'); });
}

function updateLegalEntitiesList( $initList ){
	// First test if service selected is really a service
	$partialLegalEntities = "";
	if( $("#frm_service option:selected").val() != "" ){

		$partialLegalEntities = "<option value ";
		if( parseInt($initList) <= 0 )
			$partialLegalEntities += "selected=\"selected\"";
		$partialLegalEntities += "> </option>";

		// Construct list of legal entities choices based on service id
		$serviceIdx = parseInt( $("#frm_service option:selected").attr('data') );
		$listLegalEntities = $services[$serviceIdx][SERVICE_LEGALENTITIES_IDX];
		$i = 0;
		$legalEntities.forEach(function($legalentityLine){
			if( $listLegalEntities.indexOf( $legalentityLine[LEGALENTITY_ID] ) >= 0 ){
				$selected = "";
				if( $legalentityLine[LEGALENTITY_ID] == parseInt( $initList ) )
					$selected = "selected=\"selected\"";
				$partialLegalEntities += "<option value=\"" + $legalentityLine[LEGALENTITY_ID] + "\" data=\"" + $i + "\" " + $selected + ">" + $legalentityLine[LEGALENTITY_NAME] + "</option> ";
			}
			$i = $i + 1;
		});
		$('#frm_legalentity').attr('disabled', false);
	} else {
		$('#frm_legalentity').attr('disabled', true);
		$partialLegalEntities = "<option value selected=\"selected\"> </option>";
	}

	$("#frm_legalentity").html($partialLegalEntities);

}

function updateBudgetCodesList( $initList ){

	$partialBudgetCodes = "";
	if( $("#frm_service option:selected").val() != "" ){

		$partialBudgetCodes ="<option value ";
		if( parseInt($initList) <= 0 )
			$partialBudgetCodes += " selected=\"selected\"";
		$partialBudgetCodes += "> </option>";

		// Construct list of budget codes choices based on service id
		$serviceIdx = parseInt( $("#frm_service option:selected").attr('data') );
		$listBudgetCodes = $services[$serviceIdx][SERVICE_BUDGETCODES_IDX];
		$iu = 0;
		$budgetCodes.forEach(function($budgetcodeLine){
			if( $listBudgetCodes.indexOf( $budgetcodeLine[BUDGETCODE_ID] ) >= 0 ){
				$selected = "";
				if( $budgetcodeLine[BUDGETCODE_ID] == parseInt( $initList ) )
					$selected = "selected=\"selected\"";
				$partialBudgetCodes += "<option value=\"" + $budgetcodeLine[BUDGETCODE_ID] + "\" data=\"" + $i + "\" " + $selected + ">" + $budgetcodeLine[BUDGETCODE_NAME] + "</option> ";
			}
			$i = $i + 1;
		});
		$('#frm_budgetcode').attr('disabled', false);
	} else {
		$('#frm_budgetcode').attr('disabled', true);
		$partialBudgetCodes ="<option value selected=\"selected\"> </option>";
	}

	$("#frm_budgetcode").html($partialBudgetCodes);
}

function updateDocumentNaturesList( $initList ){

	$partialDocumentNatures = "";
	if( $("#frm_service option:selected").val() != "" ){

		$partialDocumentNatures = "<option value ";
		if( parseInt($initList) <= 0 )
			$partialDocumentNatures += " selected=\"selected\"";
		$partialDocumentNatures += "> </option>";
		// Construct list of document natures choices based on service id
		$serviceIdx = parseInt( $("#frm_service option:selected").attr('data') );
		$listDocumentNatures = $services[$serviceIdx][SERVICE_DOCUMENTNATURES_IDX];
		$i = 0;
		$documentNatures.forEach(function($documentnatureLine){
			if( $listDocumentNatures.indexOf( $documentnatureLine[DOCUMENTNATURE_ID] ) >= 0 ){
				$selected = "";
				if( $documentnatureLine[DOCUMENTNATURE_ID] == parseInt( $initList ) )
					$selected = "selected=\"selected\"";
				$partialDocumentNatures += "<option value=\"" + $documentnatureLine[DOCUMENTNATURE_ID] + "\" data=\"" + $i + "\" " + $selected + ">" + $documentnatureLine[DOCUMENTNATURE_NAME] + "</option> ";
			}
			$i = $i + 1;
		});
		$('#frm_documentnature').attr('disabled', false);
	} else {
		$('#frm_documentnature').attr('disabled', true);
		$partialDocumentNatures ="<option value selected=\"selected\"> </option>";
	}

	$("#frm_documentnature").html($partialDocumentNatures);

	if( parseInt( $initList ) >= 0 )
		updateDocumentTypesList( $defaultDocumentType );

}

function updateDocumentTypesList( $initList ){

	$partialDocumentTypes = "";
	if( $("#frm_documentnature option:selected").val() != "" ){

		$partialDocumentTypes ="<option value ";
		if( parseInt($initList) <= 0 )
			$partialDocumentTypes += " selected=\"selected\"";
		$partialDocumentTypes += "> </option>";
		// Construct list of document types choices based on document nature id
		$documentnatureIdx = parseInt( $("#frm_documentnature option:selected").attr('data') );
		$listDocumentTypes = $documentNatures[$documentnatureIdx][DOCUMENTNATURE_DOCUMENTTYPES_IDX];
		$i = 0;
		$documentTypes.forEach(function($documenttypeLine){
			if( $listDocumentTypes.indexOf( $documenttypeLine[DOCUMENTTYPE_ID] ) >= 0 ){
				$selected = "";
				if( $documenttypeLine[DOCUMENTTYPE_ID] == parseInt( $initList ) )
					$selected = "selected=\"selected\"";
				$partialDocumentTypes += "<option value=\"" + $documenttypeLine[DOCUMENTTYPE_ID] + "\" data=\"" + $i + "\" " + $selected + ">" + $documenttypeLine[DOCUMENTTYPE_NAME] + "</option> ";
			}
			$i = $i + 1;
		});
		$('#frm_documenttype').attr('disabled', false);
	} else {
		$('#frm_documenttype').attr('disabled', true);
		$partialDocumentTypes ="<option value selected=\"selected\"> </option>";
	}

	$("#frm_documenttype").html($partialDocumentTypes);

	if( parseInt( $initList ) >= 0 )
		updateDestructionYear();
}

function updateDescriptions1List( $initList ){

	$partialDescriptions1 = "";
	if( $("#frm_service option:selected").val() != "" ){

		$partialDescriptions1 ="<option value ";
		if( parseInt($initList) <= 0 )
			$partialDescriptions1 += " selected=\"selected\"";
		$partialDescriptions1 += "> </option>";
		// Construct list of descriptions1 choices based on service id
		$serviceIdx = parseInt( $("#frm_service option:selected").attr('data') );
		$listDescriptions1 = $services[$serviceIdx][SERVICE_DESCRIPTIONS1_IDX];
		$i = 0;
		$descriptions1.forEach(function($descriptionLine){
			if( $listDescriptions1.indexOf( $descriptionLine[DESCRIPTION1_ID] ) >= 0 ){
				$selected = "";
				if( $descriptionLine[DESCRIPTION1_ID] == parseInt( $initList ) )
					$selected = "selected=\"selected\"";
				$partialDescriptions1 += "<option value=\"" + $descriptionLine[DESCRIPTION1_ID] + "\" data=\"" +  $i + "\" " + $selected + ">" + $descriptionLine[DESCRIPTION1_NAME] + "</option> ";
			}
			$i = $i + 1;
		});
		$('#frm_description1').attr('disabled', false);
	} else {
		$('#frm_description1').attr('disabled', true);
		$partialDescriptions1 ="<option value selected=\"selected\"> </option>";
	}

	$("#frm_description1").html($partialDescriptions1);
}

function updateDescriptions2List( $initList ){

	$partialDescriptions2 = "";
	if( $("#frm_service option:selected").val() != "" ){

		$partialDescriptions2 ="<option value ";
		if( parseInt($initList) <= 0 )
			$partialDescriptions2 += " selected=\"selected\"";
		$partialDescriptions2 += "> </option>";
		// Construct list of descriptions2 choices based on service id
		$serviceIdx = parseInt( $("#frm_service option:selected").attr('data') );
		$listDescriptions2 = $services[$serviceIdx][SERVICE_DESCRIPTIONS2_IDX];
		$i = 0;
		$descriptions2.forEach(function($descriptionLine){
			if( $listDescriptions2.indexOf( $descriptionLine[DESCRIPTION2_ID] ) >= 0 ){
				$selected = "";
				if( $descriptionLine[DESCRIPTION2_ID] == parseInt( $initList ) )
					$selected = "selected=\"selected\"";
				$partialDescriptions2 += "<option value=\"" + $descriptionLine[DESCRIPTION2_ID] + "\" data=\"" + $i + "\"  " + $selected + ">" + $descriptionLine[DESCRIPTION2_NAME] + "</option> ";
			}
			$i = $i + 1;
		});
		$('#frm_description2').attr('disabled', false);
	} else {
		$('#frm_description2').attr('disabled', true);
		$partialDescriptions2 ="<option value selected=\"selected\"> </option>";
	}

	$("#frm_description2").html($partialDescriptions2);
}

function updateProvidersList( $initList ){

	$partialProviders = "";
	if( $("#frm_service option:selected").val() != "" ){

		$partialProviders ="<option value ";
		if( parseInt($initList) <= 0 )
			$partialProviders += " selected=\"selected\"";
		$partialProviders += "> </option>";
		// Construct list of providers choices based on service id
		$serviceIdx = parseInt( $("#frm_service option:selected").attr('data') );
		$listProviders = $services[$serviceIdx][SERVICE_PROVIDERS_IDX];
		$i = 0;
		$providers.forEach(function($providerLine){
			if( $listProviders.indexOf( $providerLine[PROVIDER_ID] ) >= 0 ){
				$selected = "";
				if( $providerLine[PROVIDER_ID] == parseInt( $initList ) )
					$selected = "selected=\"selected\"";
				$partialProviders += "<option value=\"" + $providerLine[PROVIDER_ID] + "\" data=\"" + $i + "\" " + $selected + ">" + $providerLine[PROVIDER_NAME] + "</option> ";
			}
			$i = $i + 1;
		});
		$('#frm_provider').attr('disabled', false);
	} else {
		$('#frm_provider').attr('disabled', true);
		$partialProviders ="<option value selected=\"selected\"> </option>";
	}

	$("#frm_provider").html($partialProviders);
}

function updateDestructionYear(){

	$documenttypeIdx = parseInt( $("#frm_documenttype option:selected").attr('data') );
	if( $documenttypeIdx ) {
        $destructionTime = $documentTypes[$documenttypeIdx][DOCUMENTTYPE_KEEPALIVEDURATION];

        // If destruction time is 0, user should enter whatever he wants, instead destruction time is forced
        if ($destructionTime == 0) {
            $("#frm_destructionyear").prop('disabled', false);
        } else {
            $("#frm_destructionyear").prop('disabled', true);
            // TODO test empty field
            $destructionYear = parseInt($("#frm_closureyear").val());
            $destructionYear += $destructionTime;
            $("#frm_destructionyear").val($destructionYear);
        }
    }
}

function ajax_updatefield( $field, $value ){
	$dataObj = {
		'uaid': window.IDP_CONST.bs_idp_archive_id,
		'uafield': $field,
		'value': $value
	}
	$.ajax({
		type: "GET",
		url: window.JSON_URLS.bs_idp_archive_updatefield_json,
		data: $dataObj,
		cache: false,
		success: function( ){
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert( "Error when updating archive="+window.IDP_CONST.bs_idp_archive_id+" field="+$field+" with value="+$value+" thrownError="+throwError );
		}
	});
}

// On service select box change, update legal entities & budget codes with only available choices
$('#frm_service').change(function () {
	updateLegalEntitiesList( -1 );
	updateBudgetCodesList( -1 );
	updateDocumentNaturesList( -1 );
	updateDocumentTypesList( -1 );
    $("#frm_destructionyear").prop('disabled', false);
	updateDescriptions1List( -1 );
	updateDescriptions2List( -1 );
	updateProvidersList( -1 );

	// ajax new value to update archive
	ajax_updatefield( UAFIELD_SERVICE, parseInt( $("#frm_service option:selected").val() ) );

    // Get back settings
	if( $("#frm_service option:selected").val() < 0 )
        getAjaxSettings( 0 );
	else
    	getAjaxSettings( $("#frm_service option:selected").val() );
});

// On legalentity select box change, update document natures & document types with only available choices
$('#frm_legalentity').change(function () {
	// ajax new value to update archive
	ajax_updatefield( UAFIELD_LEGALENTITY, parseInt( $("#frm_legalentity option:selected").val() ) );
});

$('#frm_budgetcode').change(function () {
	// ajax new value to update archive
	ajax_updatefield( UAFIELD_BUDGETCODE, parseInt( $("#frm_budgetcode option:selected").val() ) );
})

// On DocumentNature select box change, update document types with available choices
$('#frm_documentnature').change(function(){
	updateDocumentTypesList( -1 );

	// ajax new value to update archive
	ajax_updatefield( UAFIELD_DOCUMENTNATURE, parseInt( $("#frm_documentnature option:selected").val() ) );
});

// On DocumentType select change, update the destruction year if needed
$('#frm_documenttype').change(function(){
	updateDestructionYear();

	// ajax new value to update archive
	ajax_updatefield( UAFIELD_DOCUMENTTYPE, parseInt( $("#frm_documenttype option:selected").val() ) );
	ajax_updatefield( UAFIELD_DESTRUCTIONYEAR, parseInt( $("#frm_destructionyear").val() ) );
});

$('#frm_description1').change(function(){
	// ajax new value to update archive
	ajax_updatefield( UAFIELD_DESCRIPTION1, parseInt( $("#frm_description1 option:selected").val() ) );
})

$('#frm_description2').change(function(){
	// ajax new value to update archive
	ajax_updatefield( UAFIELD_DESCRIPTION2, parseInt( $("#frm_description2 option:selected").val() ) );
})

// Change Destruction year, if closure year change and input loss focus$
$('#frm_closureyear').blur(function(){
	updateDestructionYear();

	// ajax new value to update archive
	ajax_updatefield( UAFIELD_CLOSUREYEAR, parseInt( $("#frm_closureyear").val() ) );
	ajax_updatefield( UAFIELD_DESTRUCTIONYEAR, parseInt( $('#frm_destructionyear').val() ) );
});
$('#frm_destructionyear').blur(function(){
	// verify only if destruction year is editable
    $documenttypeIdx = parseInt( $("#frm_documenttype option:selected").attr('data') );
    $destructionTime = $documenttypeIdx?$documentTypes[$documenttypeIdx][DOCUMENTTYPE_KEEPALIVEDURATION]:0;

    // If destruction time is 0, user should enter whatever he wants, instead destruction time is forced
    if($destructionTime == 0) {
        if( parseInt($('#frm_destructionyear').val()) > 2199 ){
            popError( $('#frm_destructionyear'), "L'année de destruction ne peut pas être supérieure à 2199", 'top' );
        } else {
            ajax_updatefield( UAFIELD_DESTRUCTIONYEAR, parseInt( $('#frm_destructionyear').val() ) );
        }
    }

});

$('#frm_documentnumber').blur(function(){
	// ajax new value to update archive
	ajax_updatefield( UAFIELD_DOCUMENTNUMBER, $("#frm_documentnumber").val() );
});

$('#frm_boxnumber').blur(function(){
	// ajax new value to update archive
	ajax_updatefield( UAFIELD_BOXNUMBER, $("#frm_boxnumber").val() );
});

$('#frm_containernumber').blur(function(){
	// ajax new value to update archive
	ajax_updatefield( UAFIELD_CONTAINERNUMBER, $("#frm_containernumber").val() );
});

$('#frm_provider').change(function(){
	// ajax new value to update archive
	ajax_updatefield( UAFIELD_PROVIDER, parseInt( $("#frm_provider option:selected").val() ) );
});

$('#frm_name').blur(function(){
	// ajax new value to update archive
	ajax_updatefield( UAFIELD_NAME, $("#frm_name").val() );
});

$('#frm_limitalphamin').blur(function(){
	// ajax new value to update archive
	ajax_updatefield( UAFIELD_LIMITALPHAMIN, $("#frm_limitalphamin").val() );
});

$('#frm_limitalphamax').blur(function(){
	// ajax new value to update archive
	ajax_updatefield( UAFIELD_LIMITALPHAMAX, $("#frm_limitalphamax").val() );
});

$('#frm_limitnummin').blur(function(){
	// ajax new value to update archive
	ajax_updatefield( UAFIELD_LIMITNUMMIN, $("#frm_limitnummin").val() );
});

$('#frm_limitnummax').blur(function(){
	// ajax new value to update archive
	ajax_updatefield( UAFIELD_LIMITNUMMAX, $("#frm_limitnummax").val() );
});

$('#frm_limitalphanummin').blur(function(){
	// ajax new value to update archive
	ajax_updatefield( UAFIELD_LIMITALPHANUMMIN, $("#frm_limitalphanummin").val() );
});

$('#frm_limitalphanummax').blur(function(){
	// ajax new value to update archive
	ajax_updatefield( UAFIELD_LIMITALPHANUMMAX, $("#frm_limitalphanummax").val() );
});

$('#frm_limitdatemin').blur(function(){
    focusBackupLimitDate();
});
$('#frm_limitdatemin').blur(function(){
    focusBackupLimitDate();
});

function isDateValid( $date_str ){
	var max_day_per_month = [ 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31 ];
	// Format expected dd/mm/yyyy (so 10 characters)
	if( $date_str.length != 10 )
		return false;
	// so 3 blocks
	$date_split = $date_str.split('/');
	if( $date_split.length != 3 )
		return false;
	// with numbers in each ones
	if( isNaN(parseInt($date_split[0]))||isNaN(parseInt($date_split[1]))||isNaN(parseInt($date_split[2])) )
		return false;
	// month could only be 1-12
	if( parseInt($date_split[1])<1 || parseInt($date_split[1])>12 )
		return false;
	// day could only be 1-31
	$day_max = max_day_per_month[parseInt($date_split[1])-1];
	if( parseInt($date_split[0])==2 && ($date_split[2]%4)*4 == $date_split[2] )
		$day_max = 29;
	if( parseInt($date_split[0])<1 || parseInt($date_split[0])>$day_max )
		return false;

	return true;
}

function focusBackupLimitDate( ){
	if( $("#frm_limitdatemin").val()=='' || isDateValid( $("#frm_limitdatemin").val() ) )
		ajax_updatefield( UAFIELD_LIMITDATEMIN, $("#frm_limitdatemin").val() );

	if( $("#frm_limitdatemax").val()=='' || isDateValid( $("#frm_limitdatemax").val() ) )
		ajax_updatefield( UAFIELD_LIMITDATEMAX, $("#frm_limitdatemax").val() );
}

function makeparams( $from ){
	var params = new Array();
	params['from'] = $from;
	params['token'] = $('#frm_token').val();
	params['id'] = $('#frm_id').val();
	params['service'] = $('#frm_service option:selected').val();
	params['legal_entity'] = $('#frm_legalentity option:selected').val();
	params['budget_code'] = $('#frm_budgetcode option:selected').val();
	params['document_nature'] = $('#frm_documentnature option:selected').val();
	params['document_type'] = $('#frm_documenttype option:selected').val();
	params['description1'] = $('#frm_description1 option:selected').val();
	params['description2'] = $('#frm_description2 option:selected').val();
	params['closure_year'] = $('#frm_closureyear').val();
	params['destruction_year'] = $('#frm_destructionyear').val();
	params['document_number'] = $('#frm_documentnumber').val();
	params['box_number'] = $('#frm_boxnumber').val();
	params['container_number'] = $('#frm_containernumber').val();
	params['provider'] = $('#frm_provider option:selected').val();
	params['order_number'] = $('#frm_ordernumber').val();
	params['name'] = $('#frm_name').val();
	params['limit_date_min'] = $('#frm_limitdatemin').val();
	params['limit_date_max'] = $('#frm_limitdatemax').val();
	params['limit_num_min'] = $('#frm_limitnummin').val();
	params['limit_num_max'] = $('#frm_limitnummax').val();
	params['limit_alpha_min'] = $('#frm_limitalphamin').val();
	params['limit_alpha_max'] = $('#frm_limitalphamax').val();
	params['limit_alphanum_min'] = $('#frm_limitalphanummin').val();
	params['limit_alphanum_max'] = $('#frm_limitalphanummax').val();

	return params;
}

$('#divSave').click( function() {
	$params = makeparams( 'save' );
	post(window.JSON_URLS.bs_idp_archive_donew, $params, false );
});

$('#divValidate').click( function() {
	$verify = verifyMandatories( );
	if( $verify == null ){
		$params = makeparams( 'validate' );
		post(window.JSON_URLS.bs_idp_archive_donew, $params, false );
	} else {
		$('#textErrorMessage').html( $verify );
		$('#modalErrorMessage').modal( 'show' );
	}

});

$('#divPrint').click( function() {
    focusBackupLimitDate();
    var $params = new Array();
    $params['id'] = $('#frm_id').val();
    get(window.JSON_URLS.bs_idp_archive_print_sheet, $params, true );});

$('#divPrintTag').click( function() {
    focusBackupLimitDate();
	$('#modalTagChoice').modal( 'show' );
});
$('#btnTag1').click( function() {
	var $params = new Array();
	$params['id'] = $('#frm_id').val();
	$params['position'] = 1;

	$('#modalTagChoice').modal( 'hide' );
	post(window.JSON_URLS.bs_idp_archive_print_tag, $params, true );
})
$('#btnTag2').click( function() {
	var $params = new Array();
	$params['id'] = $('#frm_id').val();
	$params['position'] = 2;

	$('#modalTagChoice').modal( 'hide' );
	post(window.JSON_URLS.bs_idp_archive_print_tag, $params, true );
})
$('#btnTag3').click( function() {
	var $params = new Array();
	$params['id'] = $('#frm_id').val();
	$params['position'] = 3;

	$('#modalTagChoice').modal( 'hide' );
	post(window.JSON_URLS.bs_idp_archive_print_tag, $params, true );
})
$('#btnTag4').click( function() {
	var $params = new Array();
	$params['id'] = $('#frm_id').val();
	$params['position'] = 4;

	$('#modalTagChoice').modal( 'hide' );
	post(window.JSON_URLS.bs_idp_archive_print_tag, $params, true );
})

$('#divModify').click( function() {
	$verify = verifyMandatories( );
	if( $verify == null ){
		$params = makeparams( 'modify' );
		post(window.JSON_URLS.bs_idp_archive_donew, $params, false );
	} else {
		$('#textErrorMessage').html( $verify );
		$('#modalErrorMessage').modal( 'show' );
	}
});

function verifyMandatories( ) {
	$retour = true;
	if( $('#frm_service option:selected').val() == '' || $('#frm_service option:selected').val() == '-1'){
		$retour = false;
		$('#divSelectService').removeClass('has-success');
		$('#divSelectService').addClass('has-error');
	} else {
		$('#divSelectService').removeClass('has-error');
		$('#divSelectService').addClass('has-success');
	}
	if( $('#frm_legalentity option:selected').val() == '' ){
		$retour = false;
		$('#divSelectLegalentity').removeClass('has-success');
		$('#divSelectLegalentity').addClass('has-error');
	} else {
		$('#divSelectLegalentity').removeClass('has-error');
		$('#divSelectLegalentity').addClass('has-success');
	}
	if( $('#frm_name').val().trim() == ''){
		$retour = false;
		$('#form_name').removeClass('has-success');
		$('#form_name').addClass('has-error');
	} else {
		$('#form_name').removeClass('has-error');
		$('#form_name').addClass('has-success');
	}
	if( $('#frm_closureyear').val().trim() == '' ){
		$retour = false;
		$('#divInputClosureyear').removeClass('has-success');
		$('#divInputClosureyear').addClass('has-error');
	} else {
		$('#divInputClosureyear').removeClass('has-error');
		$('#divInputClosureyear').addClass('has-success');
	}
	if( $('#frm_destructionyear').val().trim() == ''){
		$retour = false;
		$('#divInputDestructionyear').removeClass('has-success');
		$('#divInputDestructionyear').addClass('has-error');
	} else {
        // verify only if destruction year is editable
        $documenttypeIdx = parseInt( $("#frm_documenttype option:selected").attr('data') );
        $destructionTime = $documenttypeIdx?$documentTypes[$documenttypeIdx][DOCUMENTTYPE_KEEPALIVEDURATION]:0;

        // If destruction time is 0, user should enter whatever he wants, instead destruction time is forced
        if($destructionTime == 0) {
            if (parseInt($('#frm_destructionyear').val().trim()) > 2199) {
                $retour = false;
                $('#divInputDestructionyear').removeClass('has-success');
                $('#divInputDestructionyear').addClass('has-error');
            } else {
                $('#divInputDestructionyear').removeClass('has-error');
                $('#divInputDestructionyear').addClass('has-success');
            }
        } else {
            $('#divInputDestructionyear').removeClass('has-error');
            $('#divInputDestructionyear').addClass('has-success');
        }
	}
	if( $_settings.view_budgetcode && $_settings.mandatory_budgetcode && $('#frm_budgetcode option:selected').val() == '' ){
		$retour = false;
		$('#divSelectBudgetcode').removeClass('has-success');
		$('#divSelectBudgetcode').addClass('has-error');
	} else {
		$('#divSelectBudgetcode').removeClass('has-error');
		$('#divSelectBudgetcode').addClass('has-success');
	}
	if( $_settings.view_documentnature && $_settings.mandatory_documentnature && $('#frm_documentnature option:selected').val() == '' ){
		$retour = false;
		$('#divSelectDocumentnature').removeClass('has-success');
		$('#divSelectDocumentnature').addClass('has-error');
	} else {
		$('#divSelectDocumentnature').removeClass('has-error');
		$('#divSelectDocumentnature').addClass('has-success');
	}
	if( $_settings.view_documentnature && $_settings.view_documenttype && $_settings.mandatory_documenttype && $('#frm_documenttype option:selected').val() == '' ){
		$retour = false;
		$('#divSelectDocumenttype').removeClass('has-success');
		$('#divSelectDocumenttype').addClass('has-error');
	} else {
		$('#divSelectDocumenttype').removeClass('has-error');
		$('#divSelectDocumenttype').addClass('has-success');
	}
	if( $_settings.view_description1 && $_settings.mandatory_description1 && $('#frm_description1 option:selected').val() == '' ){
		$retour = false;
		$('#divSelectDescription1').removeClass('has-success');
		$('#divSelectDescription1').addClass('has-error');
	} else {
		$('#divSelectDescription1').removeClass('has-error');
		$('#divSelectDescription1').addClass('has-success');
	}
	if( $_settings.view_description2 && $_settings.mandatory_description2 && $('#frm_description2 option:selected').val() == '' ){
		$retour = false;
		$('#divSelectDescription2').removeClass('has-success');
		$('#divSelectDescription2').addClass('has-error');
	} else {
		$('#divSelectDescription2').removeClass('has-error');
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
			$('#divInputLimitsdatemin').removeClass('has-success')
			$('#divInputLimitsdatemin').addClass('has-error');
		} else {
			$('#divInputLimitsdatemin').removeClass('has-error')
			$('#divInputLimitsdatemin').addClass('has-success');
		}
		if( $empty2 || $test_RegexValidDate_DateMax == false ){
			$('#divInputLimitsdatemax').removeClass('has-success');
			$('#divInputLimitsdatemax').addClass('has-error');
		} else {
			$('#divInputLimitsdatemax').removeClass('has-error')
			$('#divInputLimitsdatemax').addClass('has-success');
		}
	} else {
		$('#divInputLimitsdatemin').removeClass('has-error')
		$('#divInputLimitsdatemin').addClass('has-success');
		$('#divInputLimitsdatemax').removeClass('has-error');
		$('#divInputLimitsdatemax').addClass('has-success');
	}
    $test1 = /^[0-9]+$/.test( $('#frm_limitnummin').val() );
    $test2 = /^[0-9]+$/.test( $('#frm_limitnummax').val() );
    $test_RegexOnlyNumber_NumMin = ($('#frm_limitnummin').val() == '')?true:$test1;
    $test_RegexOnlyNumber_NumMax = ($('#frm_limitnummin').val() == '')?true:$test2;
    $empty1 = $('#frm_limitnummin').val().trim() == '';
    $empty2 = $('#frm_limitnummin').val().trim() == '';
	if( ( $_settings.mandatory_limitsnum && ( $empty1 && $empty2 ) ) ||
        ( !$_settings.mandatory_limitsnum && ( ( !$empty1 && $empty2 ) || ( $empty1 && !$empty2 ) ) ) ||
        ( !$test_RegexOnlyNumber_NumMin || !$test_RegexOnlyNumber_NumMax )){
		$retour = false;
		if( $empty1 || $test_RegexOnlyNumber_NumMin == false ){
			$('#divInputLimitsnummin').removeClass('has-success')
			$('#divInputLimitsnummin').addClass('has-error');
		} else {
			$('#divInputLimitsnummin').removeClass('has-error')
			$('#divInputLimitsnummin').addClass('has-success');
		}
		if( $empty2  || $test_RegexOnlyNumber_NumMax == false ){
			$('#divInputLimitsnummax').removeClass('has-success');
			$('#divInputLimitsnummax').addClass('has-error');
		} else {
			$('#divInputLimitsnummax').removeClass('has-error')
			$('#divInputLimitsnummax').addClass('has-success');
		}
	} else {
		$('#divInputLimitsnummin').removeClass('has-error')
		$('#divInputLimitsnummin').addClass('has-success');
		$('#divInputLimitsnummax').removeClass('has-error');
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
			$('#divInputLimitsalphamin').removeClass('has-success')
			$('#divInputLimitsalphamin').addClass('has-error');
		} else {
			$('#divInputLimitsalphamin').removeClass('has-error')
			$('#divInputLimitsalphamin').addClass('has-success');
		}
		if( $empty2 || $test_RegexOnlyChar_AlphaMax == false ){
			$('#divInputLimitsalphamax').removeClass('has-success');
			$('#divInputLimitsalphamax').addClass('has-error');
		} else {
			$('#divInputLimitsalphamax').removeClass('has-error')
			$('#divInputLimitsalphamax').addClass('has-success');
		}
	} else {
		$('#divInputLimitsalphamin').removeClass('has-error')
		$('#divInputLimitsalphamin').addClass('has-success');
		$('#divInputLimitsalphamax').removeClass('has-error');
		$('#divInputLimitsalphamax').addClass('has-success');
	}
    $empty1 = $('#frm_limitalphanummin').val().trim() == '';
    $empty2 = $('#frm_limitalphanummax').val().trim() == '';
	if( ( $_settings.mandatory_limitsalphanum && ( $empty1 && $empty2 ) ) ||
        ( !$_settings.mandatory_limitsalphanum && ( ( !$empty1 && $empty2 ) || ( $empty1 && !$empty2 ) ) ) ) {
		$retour = false;
		if( $empty1 ){
			$('#divInputLimitsalphanummin').removeClass('has-success')
			$('#divInputLimitsalphanummin').addClass('has-error');
		} else {
			$('#divInputLimitsalphanummin').removeClass('has-error')
			$('#divInputLimitsalphanummin').addClass('has-success');
		}
		if( $empty2 ){
			$('#divInputLimitsalphanummax').removeClass('has-success');
			$('#divInputLimitsalphanummax').addClass('has-error');
		} else {
			$('#divInputLimitsalphanummax').removeClass('has-error')
			$('#divInputLimitsalphanummax').addClass('has-success');
		}
	} else {
		$('#divInputLimitsalphanummin').removeClass('has-error')
		$('#divInputLimitsalphanummin').addClass('has-success');
		$('#divInputLimitsalphanummax').removeClass('has-error');
		$('#divInputLimitsalphanummax').addClass('has-success');
	}
	if( $_settings.view_filenumber && $_settings.mandatory_filenumber && $('#frm_documentnumber').val().trim() == '' ){
		$retour = false;
		$('#divInputFilenumber').removeClass('has-success');
		$('#divInputFilenumber').addClass('has-error');
	} else {
		$('#divInputFilenumber').removeClass('has-error');
		$('#divInputFilenumber').addClass('has-success');
	}
	if( $_settings.view_boxnumber && $_settings.mandatory_boxnumber && $('#frm_boxnumber').val().trim() == '' ){
		$retour = false;
		$('#divInputBoxnumber').removeClass('has-success');
		$('#divInputBoxnumber').addClass('has-error');
	} else {
		$('#divInputBoxnumber').removeClass('has-error');
		$('#divInputBoxnumber').addClass('has-success');
	}
	if( $_settings.view_containernumber && $_settings.mandatory_containernumber && $('#frm_containernumber').val().trim() == '' ){
		$retour = false;
		$('#divInputContainernumber').removeClass('has-success');
		$('#divInputContainernumber').addClass('has-error');
	} else {
		$('#divInputContainernumber').removeClass('has-error');
		$('#divInputContainernumber').addClass('has-success');
	}
	if( $_settings.view_provider && $_settings.mandatory_provider && $('#frm_provider option:selected').val().trim() == '' ){
		$retour = false;
		$('#divSelectProvider').removeClass('has-success');
		$('#divSelectProvider').addClass('has-error');
	} else {
		$('#divSelectProvider').removeClass('has-error');
		$('#divSelectProvider').addClass('has-success');
	}

	$msg = null;
	if( !$retour )
		$msg = "Un des champs obligatoires n'est pas saisi !";

	$retourborne = true;

	$nummin = $('#frm_limitnummin').val();
	$nummax = $('#frm_limitnummax').val();
	if( $('#frm_limitnummin').val().trim() != '' && $('#frm_limitnummax').val().trim() != '' && $test_RegexOnlyNumber_NumMin != false && $test_RegexOnlyNumber_NumMax != false ){
		if( parseInt($nummax) < parseInt($nummin) ){
			$retourborne = false;
			$('#divInputLimitsnummin').addClass('has-error')
			$('#divInputLimitsnummin').removeClass('has-success');
			$('#divInputLimitsnummax').addClass('has-error');
			$('#divInputLimitsnummax').removeClass('has-success');
		} else {
			$('#divInputLimitsnummin').removeClass('has-error')
			$('#divInputLimitsnummin').addClass('has-success');
			$('#divInputLimitsnummax').removeClass('has-error');
			$('#divInputLimitsnummax').addClass('has-success');
		}
	}
	$datemin = $('#frm_limitdatemin').val();
	$datemax = $('#frm_limitdatemax').val();
	if( $('#frm_limitdatemin').val().trim() != '' && $('#frm_limitdatemax').val().trim() != '' && $test_RegexValidDate_DateMin != false && $test_RegexValidDate_DateMax != false ){
		if( compareStringDate( $datemin, $datemax ) < 0 ){
			$retourborne = false;
			$('#divInputLimitsdatemin').addClass('has-error')
			$('#divInputLimitsdatemin').removeClass('has-success');
			$('#divInputLimitsdatemax').addClass('has-error');
			$('#divInputLimitsdatemax').removeClass('has-success');
		} else {
			$('#divInputLimitsdatemin').removeClass('has-error')
			$('#divInputLimitsdatemin').addClass('has-success');
			$('#divInputLimitsdatemax').removeClass('has-error');
			$('#divInputLimitsdatemax').addClass('has-success');
		}
	}
	$alphamin = $('#frm_limitalphamin').val();
	$alphamax = $('#frm_limitalphamax').val();
	if( $('#frm_limitalphamin').val().trim() != '' && $('#frm_limitalphamax').val().trim() != '' && $test_RegexOnlyChar_AlphaMin != false && $test_RegexOnlyChar_AlphaMax != false ){
		if( $alphamax.localeCompare( $alphamin ) < 0 ){
			$retourborne = false;
			$('#divInputLimitsalphamin').addClass('has-error')
			$('#divInputLimitsalphamin').removeClass('has-success');
			$('#divInputLimitsalphamax').addClass('has-error');
			$('#divInputLimitsalphamax').removeClass('has-success');
		} else {
			$('#divInputLimitsalphamin').removeClass('has-error')
			$('#divInputLimitsalphamin').addClass('has-success');
			$('#divInputLimitsalphamax').removeClass('has-error');
			$('#divInputLimitsalphamax').addClass('has-success');
		}
	}
	$alphanummin = $('#frm_limitalphanummin').val();
	$alphanummax = $('#frm_limitalphanummax').val();
	if( $('#frm_limitalphanummin').val().trim() != '' && $('#frm_limitalphanummax').val().trim() != '' ){
		if( $alphanummax.localeCompare( $alphanummin ) < 0 ){
			$retourborne = false;
			$('#divInputLimitsalphanummin').addClass('has-error')
			$('#divInputLimitsalphanummin').removeClass('has-success');
			$('#divInputLimitsalphanummax').addClass('has-error');
			$('#divInputLimitsalphanummax').removeClass('has-success');
		} else {
			$('#divInputLimitsalphanummin').removeClass('has-error')
			$('#divInputLimitsalphanummin').addClass('has-success');
			$('#divInputLimitsalphanummax').removeClass('has-error');
			$('#divInputLimitsalphanummax').addClass('has-success');
		}
	}

	if( !$retourborne )
		$msg = "Certaines bornes saisies ne sont pas dans le bon ordre !";


	return $msg;
}

function compareStringDate( $date1, $date2 ){
	if( !$date1 || !$date2 || $date1.length != 10 || $date2.length != 10 )
		return null;
	// assume dates are dd/mm/yyyy
	$sub11 = $date1.substring( 6, 10 );
	$sub12 = $date1.substring( 3, 5 );
	$sub13 = $date1.substring( 0, 2 );
	$idate1 = 10000* parseInt( $sub11 )+100*parseInt( $sub12 )+parseInt( $sub13 );
	$sub21 = $date2.substring( 6, 10 );
	$sub22 = $date2.substring( 3, 5 );
	$sub23 = $date2.substring( 0, 2 );
	$idate2 = 10000*parseInt( $sub21 )+100*parseInt( $sub22 )+parseInt( $sub23 );

	return $idate2 - $idate1;
};
// IDPArchiveAutoSaveFields.js
// E307: Users can choose which fields are saved for next UA input

var $_user_asf;

var $_AutoSaveFieldsState = {
    'asf_service': true,
    'asf_legalentity': true,
    'asf_budgetcode': true,
    'asf_documentnature': true,
    'asf_documenttype': true,
    'asf_description1': true,
    'asf_description2': true,
    'asf_closureyear': true,
    'asf_destructionyear': true,
    'asf_filenumber': false,
    'asf_boxnumber': false,
    'asf_containernumber': false,
    'asf_provider': true,
    'asf_limitsdate': false,
    'asf_limitsnum': false,
    'asf_limitsalpha': false,
    'asf_limitsalphanum': false,
    'asf_name': false
};

var $_AutoSaveFields = {
    'asf_service': $('#lbl_service'),
    'asf_legalentity': $('#lbl_legalentity'),
    'asf_budgetcode': $('#lbl_budgetcode'),
    'asf_documentnature': $('#lbl_documentnature'),
    'asf_documenttype': $('#lbl_documenttype'),
    'asf_description1': $('#lbl_description1'),
    'asf_description2': $('#lbl_description2'),
    'asf_closureyear': $('#lbl_closureyear'),
    'asf_destructionyear': $('#lbl_destructionyear'),
    'asf_filenumber': $('#lbl_filenumber'),
    'asf_boxnumber': $('#lbl_boxnumber'),
    'asf_containernumber': $('#lbl_containernumber'),
    'asf_provider': $('#lbl_provider'),
    'asf_limitsdate': $('#lbl_limitsdatemin'),
    'asf_limitsnum': $('#lbl_limitsnummin'),
    'asf_limitsalpha': $('#lbl_limitsalphamin'),
    'asf_limitsalphanum': $('#lbl_limitsalphanummin'),
    'asf_name': $('#lbl_name')
};

var $_AutoSaveFieldsId = {
    'asf_service': 1,
    'asf_legalentity': 2,
    'asf_budgetcode': 3,
    'asf_documentnature': 4,
    'asf_documenttype': 5,
    'asf_description1': 6,
    'asf_description2': 7,
    'asf_closureyear': 8,
    'asf_destructionyear': 9,
    'asf_filenumber': 10,
    'asf_boxnumber': 11,
    'asf_containernumber': 12,
    'asf_provider': 13,
    'asf_limitsdate': 14,
    'asf_limitsnum': 15,
    'asf_limitsalpha': 16,
    'asf_limitsalphanum': 17,
    'asf_name': 18
};

function invertAndUpdate( $fieldname ){
    $_AutoSaveFieldsState[$fieldname] = !$_AutoSaveFieldsState[$fieldname];
    setAutoSaveFields_IHM( $fieldname );
    ajax_update_asf( $fieldname, $_AutoSaveFieldsState[$fieldname] );
}

function setAutoSaveFields_IHM( $fieldname ){
    if( $_AutoSaveFieldsState[$fieldname] ){
        $_AutoSaveFields[$fieldname].addClass( 'idp_saved' );
    } else {
        $_AutoSaveFields[$fieldname].removeClass( 'idp_saved' );
    }
}

function ajax_update_asf( $field, $value ){
    $dataObj = {
        'user_id': window.IDP_CONST.bs_user_id,
        'field_id': $_AutoSaveFieldsId[$field],
        'new_value': $value
    }
    $.ajax({
        type: "GET",
        url: window.JSON_URLS.bs_core_user_asf_update,
        data: $dataObj,
        cache: false,
        success: function( ){
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert( "Error" );
        }
    });
}


$('#lbl_service').on('click', function(e){
    invertAndUpdate('asf_service' );
});
$('#lbl_legalentity').on('click', function(e){
    invertAndUpdate('asf_legalentity' );
});
$('#lbl_budgetcode').on('click', function(e){
    invertAndUpdate('asf_budgetcode' );
});
$('#lbl_documentnature').on('click', function(e){
    invertAndUpdate('asf_documentnature' );
});
$('#lbl_documenttype').on('click', function(e){
    invertAndUpdate('asf_documenttype' );
});
$('#lbl_description1').on('click', function(e){
    invertAndUpdate('asf_description1' );
});
$('#lbl_description2').on('click', function(e){
    invertAndUpdate('asf_description2' );
});
$('#lbl_closureyear').on('click', function(e){
    invertAndUpdate('asf_closureyear' );
});
$('#lbl_destructionyear').on('click', function(e){
    invertAndUpdate('asf_destructionyear' );
});
$('#lbl_filenumber').on('click', function(e){
    invertAndUpdate('asf_filenumber' );
});
$('#lbl_boxnumber').on('click', function(e){
    invertAndUpdate('asf_boxnumber' );
});
$('#lbl_containernumber').on('click', function(e){
    invertAndUpdate('asf_containernumber' );
});
$('#lbl_provider').on('click', function(e){
    invertAndUpdate('asf_provider' );
});
$('#lbl_limitsdatemin').on('click', function(e){
    invertAndUpdate('asf_limitsdate' );
});
$('#lbl_limitsnummin').on('click', function(e){
    invertAndUpdate('asf_limitsnum' );
});
$('#lbl_limitsalphamin').on('click', function(e){
    invertAndUpdate('asf_limitsalpha' );
});
$('#lbl_limitsalphanummin').on('click', function(e){
    invertAndUpdate('asf_limitsalphanum' );
});
$('#lbl_name').on('click', function(e){
    invertAndUpdate('asf_name' );
});

$(document).ready(function() {
    $_user_asf = JSON.parse(window.IDP_CONST.user_asf_array);
    if( $_user_asf ) {
        $_AutoSaveFieldsState = $_user_asf[0];
    }
    // Init View
    for( var $fieldName in $_AutoSaveFieldsState ){
        if(( $fieldName != 'id' )&&( $fieldName != 'user_id' ))
            setAutoSaveFields_IHM( $fieldName );
    }
});
/**
 * Created by Cyril on 09/11/2015.
 */

$(document).ready(function() {
});

$('#btnImLost').on('click', function(e){
    e.preventDefault();

    $('#archives').collapse({'toggle': false});
    $('#fournitures').collapse({'toggle': false});
    if( window.IDP_CONST.bs_idp_userscale < 100 ) {
        $('#gestion').collapse({'toggle':false});
    }

    var _slides = [];
    _slides.push({
        /* 1 */
        content: "Vous vous trouvez actuellement sur la page de saisie. Ici, vous pouvez enregistrer les informations descriptives d'une unité d'archives avant d'effectuer une demande de transfert auprès de l'archiviste.",
        selector: 'html',
        title: "Archimage - Je suis perdu(e)",
        overlayMode: 'all'
    });
    _slides.push({
        /* 2 */
        content: "Voici les différentes rubriques de la page d'accueil. La partie dans laquelle vous vous trouvez est indiquée grâce à une coloration bleu clair. Vous pouvez à tout moment vous déplacer dans une autre partie du logiciel en cliquant sur une rubrique différente.",
        selector: '#left-menu',
        title: "Archimage - Je suis perdu(e)",
        overlayMode: 'focus',
        position: 'right-center'
       });
    _slides.push({
        /* 3 */
        content: "Vous pouvez accéder à la page d'accueil en cliquant sur ce bouton.",
        selector: '#btnAccueil',
        title: "Archimage - Je suis perdu(e)",
        overlayMode: 'focus',
        position: 'right-top'
    });
    _slides.push({
        /* 4 */
        content: "Cette rubrique concerne les demandes accessibles à l'utilisateur.",
        selector: '#btnArchives', // html
        title: "Archimage - Je suis perdu(e)",
        overlayMode: 'focus',   // all
        position: 'right-center'
    });
    _slides.push({
        /* 5 */
        content: "Cette rubrique concerne exclusivement la gestion des fournitures, que ce soit au niveau de l'utilisateur ou au niveau de l'archiviste.",
        selector: '#btnFournitures', // html
        title: "Archimage - Je suis perdu(e)",
        overlayMode: 'focus',   // all
        position: 'right-center'
    });

    if( window.IDP_CONST.bs_idp_userscale < 100 ) {
        _slides.push({
            /* 6 */
            content: "Cette rubrique concerne toutes les fonctions accessibles à l'archiviste.",
            selector: '#btnGestion', // html
            title: "Archimage - Je suis perdu(e)",
            overlayMode: 'focus',   // all
            position: 'right-center'
        });
    }

    _slides.push({
        /* 7 */
        content: "Voici la fiche descriptive de l'unité d'archives. Tous les champs comportant <span class='text-danger'>*</span> sont obligatoires, les autres sont facultatifs.",
        selector: '#frmUA', // html
        title: "Archimage - Je suis perdu(e)",
        overlayMode: 'focus',   // all
        position: 'left-center'
    });
    _slides.push({
        /* 8 */
        content: "C'est un numéro unique attribué automatiquement à chaque unité d'archives. Il permet de vous identifier en tant que créateur et de retrouver facilement vos documents.",
        selector: '#form_ordernumber', // html
        title: "Archimage - Je suis perdu(e)",
        overlayMode: 'focus',   // all
        position: 'bottom-center'
    });
    _slides.push({
        /* 9 */
        content: "Cette partie comprend les informations concernant le propriétaire de l'unité d'archives.",
        selector: '#zoneProprietaire', // html
        title: "Archimage - Je suis perdu(e)",
        overlayMode: 'focus',   // all
        position: 'right-top'
    });
    _slides.push({
        /* 10 */
        content: "Il s'agit du ou des service(s) pour lesquels vous travaillez.",
        selector: '#divSelectService', // html
        title: "Archimage - Je suis perdu(e)",
        overlayMode: 'focus',   // all
        position: 'right-center'
    });
    _slides.push({
        /* 11 */
        content: "Il s'agit de l'entité légale à laquelle appartient l'unité d'archives.",
        selector: '#divSelectLegalentity', // html
        title: "Archimage - Je suis perdu(e)",
        overlayMode: 'focus',   // all
        position: 'right-center'
    });
    if( $_settings.view_budgetcode ) {
        _slides.push({
            /* 12 */
            content: "Il s'agit du code budgétaire auquel est rattachée l'unité d'archives.",
            selector: '#divSelectBudgetcode', // html
            title: "Archimage - Je suis perdu(e)",
            overlayMode: 'focus',   // all
            position: 'right-center'
        });
    }
    if( $_settings.view_documentnature || $_settings.view_description1 || $_settings.view_description2 ){
        _slides.push({
            /* 13 */
            content: "Cette partie comprend les informations descriptives de l'unité d'archives.",
            selector: '#zoneDescriptives', // html
            title: "Archimage - Je suis perdu(e)",
            overlayMode: 'focus',   // all
            position: 'right-top'
        });
        if( $_settings.view_documentnature ){
            _slides.push({
                /* 14 */
                content: "Il s'agit du type d'activité/métier auquel appartient l'unité d'archives.",
                selector: '#divSelectDocumentnature', // html
                title: "Archimage - Je suis perdu(e)",
                overlayMode: 'focus',   // all
                position: 'right-center'
            });
            if( $_settings.view_documenttype ){
                _slides.push({
                    /* 15 */
                    content: "Il s'agit du type de dossier/document de l'activité sélectionnée précédemment. Si votre entreprise dispose d'une charte d'archivage, le calcul de la durée de conservation du document pourra se faire automatiquement.",
                    selector: '#divSelectDocumenttype', // html
                    title: "Archimage - Je suis perdu(e)",
                    overlayMode: 'focus',   // all
                    position: 'right-center'
                });
            }
        }
        if( $_settings.view_description1 || $_settings.view_description2 ){
            _slides.push({
                /* 16 et 17 */
                content: "Cette partie comprend les informations descriptives de l'unité d'archives.",
                selector: '#zoneDescription', // html
                title: "Archimage - Je suis perdu(e)",
                overlayMode: 'focus',   // all
                position: 'right-center'
            });
        }
    }
    _slides.push({
        /* 18 */
        content: "Cette partie comprend les éléments relatifs à la durée de conservation de l'unité d'archives.",
        selector: '#zoneDuree', // html
        title: "Archimage - Je suis perdu(e)",
        overlayMode: 'focus',   // all
        position: 'right-center'
    });
    _slides.push({
        /* 19 */
        content: "Elle correspond à la date/l'année à partir de laquelle débute la durée de conservation légale de l'unité d'archives.",
        selector: '#divInputClosureyear', // html
        title: "Archimage - Je suis perdu(e)",
        overlayMode: 'focus',   // all
        position: 'right-center'
    });
    _slides.push({
        /* 20 */
        content: "Elle correspond à la date/l'année de destruction possible de l'unité d'archives (elle doit cependant respecter la durée légale de conservation).",
        selector: '#divInputDestructionyear', // html
        title: "Archimage - Je suis perdu(e)",
        overlayMode: 'focus',   // all
        position: 'right-center'
    });

    if( $_settings.view_filenumber || $_settings.view_boxnumber || $_settings.view_containernumber || $_settings.view_provider ) {
        _slides.push({
            /* 21 */
            content: "Cette partie comprend les éléments permettant d'identifier le prestataire d'archivage.",
            selector: '#zonePrestataire', // html
            title: "Archimage - Je suis perdu(e)",
            overlayMode: 'focus',   // all
            position: 'right-center'
        });
        if( $_settings.view_filenumber ){
            _slides.push({
                /* 22 */
                content: "Il s'agit du numéro de code-barres prestataire apposé sur le document ou le dossier à archiver ou d'un numéro provisoire.",
                selector: '#divInputFilenumber', // html
                title: "Archimage - Je suis perdu(e)",
                overlayMode: 'focus',   // all
                position: 'right-center'
            });
        }
        if( $_settings.view_boxnumber ){
            _slides.push({
                /* 23 */
                content: "Il s'agit du numéro de code-barres prestataire apposé sur la boite d'archives ou d'un numéro provisoire.",
                selector: '#divInputBoxnumber', // html
                title: "Archimage - Je suis perdu(e)",
                overlayMode: 'focus',   // all
                position: 'right-center'
            });
        }
        if( $_settings.view_containernumber ){
            _slides.push({
                /* 24 */
                content: "Il s'agit du numéro de code-barres prestataire apposé sur le conteneur d'archives ou d'un numéro provisoire.",
                selector: '#divInputContainernumber', // html
                title: "Archimage - Je suis perdu(e)",
                overlayMode: 'focus',   // all
                position: 'right-center'
            });
        }
        if( $_settings.view_provider ){
            _slides.push({
                /* 25 */
                content: "Il s'agit du code client prestataire chez lequel l'unité d'archives sera envoyée.",
                selector: '#divSelectProvider', // html
                title: "Archimage - Je suis perdu(e)",
                overlayMode: 'focus',   // all
                position: 'right-center'
            });
        }
    }

    _slides.push({
        /* 26 */
        content: "Il s'agit de la description de l'unité d'archives. Il est préférable qu'elle soit concise et suffisamment précise, pour que vous retrouviez facilement vos documents.",
        selector: '#frm_name', // html
        title: "Archimage - Je suis perdu(e)",
        overlayMode: 'focus',   // all
        position: 'left-center'
    });

    if( $_settings.view_limitsdate || $_settings.view_limitsalpha || $_settings.view_limitsnum || $_settings.view_limitsalphanum ) {
        _slides.push({
            /* 27 */
            content: "Cette partie permet de borner l'unité d'archives.",
            selector: '#div2Limits', // html
            title: "Archimage - Je suis perdu(e)",
            overlayMode: 'focus',   // all
            position: 'left-center'
        });
        if( $_settings.view_limitsdate ){
            _slides.push({
                /* 28 */
                content: "Il s'agit d'un intervalle de dates. Un calendrier vous est proposé pour sélectionner vos dates.",
                selector: '#zoneLimitDate', // html
                title: "Archimage - Je suis perdu(e)",
                overlayMode: 'focus',   // all
                position: 'left-center'
            });
        }
        if( $_settings.view_limitsnum ){
            _slides.push({
                /* 29 */
                content: "Il s'agit d'un intervalle numérique.",
                selector: '#zoneLimitNum', // html
                title: "Archimage - Je suis perdu(e)",
                overlayMode: 'focus',   // all
                position: 'left-center'
            });
        }
        if( $_settings.view_limitsalpha ){
            _slides.push({
                /* 30 */
                content: "Il s'agit d'un intervalle alphabétique.",
                selector: '#zoneLimitAlpha', // html
                title: "Archimage - Je suis perdu(e)",
                overlayMode: 'focus',   // all
                position: 'left-center'
            });
        }
        if( $_settings.view_limitsalphanum ){
            _slides.push({
                /* 31 */
                content: "Il s'agit d'un intervalle alphanumérique.",
                selector: '#zoneLimitAlphanum', // html
                title: "Archimage - Je suis perdu(e)",
                overlayMode: 'focus',   // all
                position: 'left-center'
            });
        }
    }

    _slides.push({
        /* 32 */
        content: "Ce bouton vous permet de valider définitivement la saisie de l'unité d'archives et de l'envoyer dans la partie 'Transférer'. Une partie des informations seront sauvegardées pour faciliter la saisie de votre prochaine unité d'archives.",
        selector: '#divValidate', // html
        title: "Archimage - Je suis perdu(e)",
        overlayMode: 'focus',   // all
        position: 'top-center'
    });

    _slides.push({
        /* 33 */
        content: "Ce bouton vous permet d'imprimer la fiche de saisie de l'unité d'archives.",
        selector: '#divPrint', // html
        title: "Archimage - Je suis perdu(e)",
        overlayMode: 'focus',   // all
        position: 'top-center'
    });

    _slides.push({
        /* 34 */
        content: "Ce bouton vous permet d'imprimer l'étiquette autocollante reprenant les informations de l'unité d'archives. Vous pourrez ensuite l'apposer dessus.",
        selector: '#divPrintTag', // html
        title: "Archimage - Je suis perdu(e)",
        overlayMode: 'focus',   // all
        position: 'top-center'
    });


    $.tutorialize({
        slides: _slides,
        showClose: true,
        keyboardNavitation: true,
        labelClose: 'Fermer',
        labelEnd: 'Fin',
        labelNext: 'Suivant',
        labelPrevious: 'Précédent',
        labelStart: 'Commencer',
        arrowPath: '/img/arrow-blue.png',
        onStart: function(){
            $('#fournitures').collapse('show');
            if( window.IDP_CONST.bs_idp_userscale < 100 ) {
                $('#gestion').collapse('show');
            }
        },
        onStop: function(){
            $('#fournitures').collapse('hide');
            if( window.IDP_CONST.bs_idp_userscale < 100 ) {
                $('#gestion').collapse('hide');
            }
        }
    });

    $.tutorialize.start();

    $('#archives').collapse({'toggle': true});
    $('#fournitures').collapse({'toggle': true});
    if( window.IDP_CONST.bs_idp_userscale < 100 ) {
        $('#gestion').collapse({'toggle':true});
    }

});