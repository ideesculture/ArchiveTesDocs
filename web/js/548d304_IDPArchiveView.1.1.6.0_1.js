var $services = [];
var $legalEntities = [];
var $budgetCodes = [];
var $documentNatures = [];
var $documentTypes = [];
var $descriptions1 = [];
var $descriptions2 = [];
var $providers = [];
var $localizations = [];

var $defaultService = -1;
var $defaultLegalEntity = -1;
var $defaultBudgetCode = -1;
var $defaultDocumentNature = -1;
var $defaultDocumentType = -1;
var $defaultDescription1 = -1;
var $defaultDescription2 = -1;
var $defaultProvider = -1;
var defaultLocalization = -1;

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
var UAFIELD_UNLIMITED = 22;
var UAFIELD_COMMENTSUNLIMITED = 23;


var $limitdatemin_valid = false;
var $limitdatemax_valid = false;

var $_settings = null;

// Function to send a post to server
// post('/contact/', {name: 'Johnny Bravo'});
function post(path, params) {
	var form = document.createElement("form");
	form.setAttribute("method", "post");
	form.setAttribute("action", path);

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

		$serviceOptions = "<option value=\"\"></option>";
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

		if( $bSelected ){
			updateLegalEntitiesList( $defaultLegalEntity );
			updateBudgetCodesList( $defaultBudgetCode );
			updateDescriptions1List( $defaultDescription1 );
			updateDescriptions2List( $defaultDescription2 );
			updateProvidersList( $defaultProvider );
			updateDocumentNaturesList( $defaultDocumentNature );
		}

		window.print();

		window.opener = false;
		self.close();
		return true;
	}

	$('#ajaxError').html( window.IDP_CONST.bs_translation_39 );
	$('#ajaxError').removeClass('hidden');
	return false;
}

$(document).ready(function(){

	// get archive in 'modification'
	$_settings = JSON.parse( window.IDP_CONST.bs_idp_settings )[0];
	$ua = JSON.parse( window.IDP_CONST.bs_idp_archive )[0];

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
	$('#frm_containerncontainernumber').val( $ua.containernumber );
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
	$('#frm_limitnummin').attr('disabled', true);
	$('#frm_limitnummax').attr('disabled', true);
	$('#frm_limitalphamin').attr('disabled', true);
	$('#frm_limitalphamax').attr('disabled', true);
	$('#frm_limitalphanummin').attr('disabled', true);
	$('#frm_limitalphanummax').attr('disabled', true);
	$('#frm_limitdatemax').attr('disabled', true);
	$('#frm_limitdatemin').attr('disabled', true);
	$('#frm_closureyear').attr('disabled', true);
	$('#frm_destructionyear').attr('disabled', true);
	$('#frm_ordernumber').attr('disabled', true);
	$('#frm_documentnumber').attr('disabled', true);
	$('#frm_boxnumber').attr('disabled', true);
    $('#frm_containernumber').attr('disabled', true);
	$('#frm_legalentity').attr('disabled', true);
    $('#frm_unlimited').attr('disabled', true);
    $('#btn_commentsunlimited').hide();

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
	$('#frm_limitdatemin').datepicker({'format': 'dd/mm/yyyy'});
	$('#frm_limitdatemax').datepicker({'format': 'dd/mm/yyyy'});

	// Hide un-ask fields
	if( !$_settings.view_budgetcode ){
		$('#divLblBudgetcode').hide();
		$('#divSelectBudgetcode').hide();
	} else {
		if( $_settings.mandatory_budgetcode ){
			$text = $('#lbl_budgetcode').html() + '<span class="text-danger">*</span>';
			$('#lbl_budgetcode').html( $text );
		}
	}
	if( !$_settings.view_documentnature ){
		$('#divLblDocumentnature').hide();
		$('#divSelectDocumentnature').hide();
		// Document type depends on Document nature, so hide it also
		$('#divLblDocumenttype').hide();
		$('#divSelectDocumenttype').hide();
	} else {
		if( $_settings.mandatory_documentnature ){
			$text = $('#lbl_documentnature').html() + '<span class="text-danger">*</span>';
			$('#lbl_documentnature').html( $text );
		}
	}
	if( !$_settings.view_documenttype ){
		$('#divLblDocumenttype').hide();
		$('#divSelectDocumenttype').hide();
	} else {
		if( $_settings.mandatory_documenttype ){
			$text = $('#lbl_documenttype').html() + '<span class="text-danger">*</span>';
			$('#lbl_documenttype').html( $text );
		}
	}
	if( !$_settings.view_description1 ){
		$('#divLblDescription1').hide();
		$('#divSelectDescription1').hide();
	} else {
		$('#lbl_description1').html( $_settings.name_description1 + ($_settings.mandatory_description1?'<span class="text-danger">*</span>':''));
	}
	if( !$_settings.view_description2 ){
		$('#divLblDescription2').hide();
		$('#divSelectDescription2').hide();
	} else {
		$('#lbl_description2').html( $_settings.name_description2 + ($_settings.mandatory_description2?'<span class="text-danger">*</span>':''));
	}
	if( !$_settings.view_documentnature && !$_settings.view_description1 && !$_settings.view_description2 ){
		$('#divBlockDescription').hide();
	}

	if( !$_settings.view_limitsdate ){
		$('#divLblLimitsdatemin').hide();
		$('#divLblLimitsdatemax').hide();
		$('#divInputLimitsdatemin').hide();
		$('#divInputLimitsdatemax').hide();
	} else {
		if( $_settings.mandatory_limitsdate ){
			$text = $('#lbl_limitsdatemin').html() + '<span class="text-danger">*</span>';
			$('#lbl_limitsdatemin').html( $text );
			$text = $('#lbl_limitsdatemax').html() + '<span class="text-danger">*</span>';
			$('#lbl_limitsdatemax').html( $text );
		}
	}
	if( !$_settings.view_limitsnum ){
		$('#divLblLimitsnummin').hide();
		$('#divLblLimitsnummax').hide();
		$('#divInputLimitsnummin').hide();
		$('#divInputLimitsnummax').hide();
	} else {
		if( $_settings.mandatory_limitsnum ){
			$text = $('#lbl_limitsnummin').html() + '<span class="text-danger">*</span>';
			$('#lbl_limitsnummin').html( $text );
			$text = $('#lbl_limitsnummax').html() + '<span class="text-danger">*</span>';
			$('#lbl_limitsnummax').html( $text );
		}
	}
	if( !$_settings.view_limitsalpha ){
		$('#divLblLimitsalphamin').hide();
		$('#divLblLimitsalphamax').hide();
		$('#divInputLimitsalphamin').hide();
		$('#divInputLimitsalphamax').hide();
	} else {
		if( $_settings.mandatory_limitsalpha ){
			$text = $('#lbl_limitsalphamin').html() + '<span class="text-danger">*</span>';
			$('#lbl_limitsalphamin').html( $text );
			$text = $('#lbl_limitsalphamax').html() + '<span class="text-danger">*</span>';
			$('#lbl_limitsalphamax').html( $text )
		}
	}
	if( !$_settings.view_limitsalphanum ){
		$('#divLblLimitsalphanummin').hide();
		$('#divLblLimitsalphanummax').hide();
		$('#divInputLimitsalphanummin').hide();
		$('#divInputLimitsalphanummax').hide();
	} else {
		if( $_settings.mandatory_limitsalphanum ){
			$text = $('#lbl_limitsalphanummin').html() + '<span class="text-danger">*</span>';
			$('#lbl_limitsalphanummin').html( $text );
			$text = $('#lbl_limitsalphanummax').html() + '<span class="text-danger">*</span>';
			$('#lbl_limitsalphanummax').html( $text );
		}
	}
	if( !$_settings.view_limitsdate && !$_settings.view_limitsalpha && !$_settings.view_limitsnum && !$_settings.view_limitsalphanum ){
		$('#divBlockLimits').hide();
	}

	if( !$_settings.view_filenumber ){
		$('#divLblFilenumber').hide();
		$('#divInputFilenumber').hide();
	} else {
		if( $_settings.mandatory_filenumber ){
			$text = $('#lbl_filenumber').html() + '<span class="text-danger">*</span>';
			$('#lbl_filenumber').html( $text );
		}
	}
	if( !$_settings.view_boxnumber ){
		$('#divLblBoxnumber').hide();
		$('#divInputBoxnumber').hide();
	} else {
		if( $_settings.mandatory_boxnumber ){
			$text = $('#lbl_boxnumber').html() + '<span class="text-danger">*</span>';
			$('#lbl_boxnumber').html( $text );
		}
	}
	if( !$_settings.view_containernumber ){
		$('#divLblContainernumber').hide();
		$('#divInputContainernumber').hide();
	} else {
		if( $_settings.mandatory_containernumber ){
			$text = $('#lbl_containernumber').html() + '<span class="text-danger">*</span>';
			$('#lbl_containernumber').html( $text );
		}
	}
	if( !$_settings.view_provider ){
		$('#divLblProvider').hide();
		$('#divSelectProvider').hide();
	} else {
		if( $_settings.mandatory_provider ){
			$text = $('#lbl_provider').html() + '<span class="text-danger">*</span>';
			$('#lbl_provider').html( $text );
		}
	}
	if( !$_settings.view_filenumber && !$_settings.view_boxnumber && !$_settings.view_containernumber && !$_settings.view_provider ){
		$('#divBlockProviderdatas').hide();
	}

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
		if( parseInt($initList) >= 0 )
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
		//$('#frm_legalentity').attr('disabled', false);
	} else {
		// $('#frm_legalentity').attr('disabled', true);
		$partialLegalEntities ="<option value selected=\"selected\"> </option>";
	}

	$("#frm_legalentity").html($partialLegalEntities);

}

function updateBudgetCodesList( $initList ){

	$partialBudgetCodes = "";
	if( $("#frm_service option:selected").val() != "" ){

		$partialBudgetCodes ="<option value ";
		if( parseInt($initList) >= 0 )
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
		//$('#frm_budgetcode').attr('disabled', false);
	} else {
		// $('#frm_budgetcode').attr('disabled', true);
		$partialBudgetCodes ="<option value selected=\"selected\"> </option>";
	}

	$("#frm_budgetcode").html($partialBudgetCodes);
}

function updateDocumentNaturesList( $initList ){

	$partialDocumentNatures = "";
	if( $("#frm_service option:selected").val() != "" ){

	    $partialDocumentNatures = "<option value ";
	    if( parseInt($initList) >= 0 )
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
	    //$('#frm_documentnature').attr('disabled', false);
    } else {
	    // $('#frm_documentnature').attr('disabled', true);
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
	    if( parseInt($initList) >= 0 )
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
	    //$('#frm_documenttype').attr('disabled', false);
	} else {
	    // $('#frm_documenttype').attr('disabled', true);
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
		if( parseInt($initList) >= 0 )
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
		//$('#frm_description1').attr('disabled', false);
	} else {
		// $('#frm_description1').attr('disabled', true);
		$partialDescriptions1 ="<option value selected=\"selected\"> </option>";
	}

	$("#frm_description1").html($partialDescriptions1);
}

function updateDescriptions2List( $initList ){

	$partialDescriptions2 = "";
	if( $("#frm_service option:selected").val() != "" ){

		$partialDescriptions2 ="<option value ";
		if( parseInt($initList) >= 0 )
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
		//$('#frm_description2').attr('disabled', false);
	} else {
		// $('#frm_description2').attr('disabled', true);
		$partialDescriptions2 ="<option value selected=\"selected\"> </option>";
	}

	$("#frm_description2").html($partialDescriptions2);
}

function updateProvidersList( $initList ){

	$partialProviders = "";
	if( $("#frm_service option:selected").val() != "" ){

		$partialProviders ="<option value ";
		if( parseInt($initList) >= 0 )
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
		//$('#frm_provider').attr('disabled', false);
	} else {
		// $('#frm_provider').attr('disabled', true);
		$partialProviders ="<option value selected=\"selected\"> </option>";
	}

	$("#frm_provider").html($partialProviders);
}

function updateDestructionYear(){

	$documenttypeIdx = parseInt( $("#frm_documenttype option:selected").attr('data') );
	$destructionTime = $documentTypes[$documenttypeIdx][DOCUMENTTYPE_KEEPALIVEDURATION];

	// If destruction time is 0, user should enter whatever he wants, instead destruction time is forced
	if($destructionTime == 0){
		//$("#frm_destructionyear").prop('disabled', false);
	}
	else
	{
		// $("#frm_destructionyear").prop('disabled', true);
		// TODO test empty field
		$destructionYear = parseInt($("#frm_closureyear").val());
		$destructionYear += $destructionTime;
		$("#frm_destructionyear").val($destructionYear);
	}
}
