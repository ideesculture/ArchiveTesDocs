var $services = [];
var $legalEntities = [];
var $budgetCodes = [];
var $documentNatures = [];
var $documentTypes = [];
var $descriptions1 = [];
var $descriptions2 = [];
var $providers = [];
var $localizations = [];

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
var LOCALIZATION_ID = 0;
var LOCALIZATION_NAME = 1;

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
        $localizations = data[8]; // Unused here

		return true;
	}
	return false;
}
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

	}
}
function updateLegalEntities( $initLegalEntity ){
	// First test if service selected is really a service
	$partialLegalEntities = "";
	if( $("#frm_service option:selected").val() != "" ){

		$partialLegalEntities = "<option value ";
		if( parseInt($initLegalEntity) < 0 )
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
		if( parseInt($initBudgetCode) < 0 )
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
	    if( parseInt($initDocumentNature) < 0 )
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
	    if( parseInt($initDocumentType) < 0 )
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
		if( parseInt($initDescription1) < 0 )
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
		if( parseInt($initDescription2) < 0 )
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
function updateDestructionYear(){
/*
	$documenttypeIdx = parseInt( $("#frm_documenttype option:selected").attr('data') );
	$destructionTime = $documentTypes[$documenttypeIdx][DOCUMENTTYPE_KEEPALIVEDURATION];

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
	*/
}
