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


$('#btn_input').click( function() {
	window.location.replace( window.MENU_JSON_URLS.bs_idp_archivist_managedb_input_services );
});

$('#btn_input_service').click( function() {
	window.location.replace( window.MENU_JSON_URLS.bs_idp_archivist_managedb_input_services );
});

$('#btn_input_legalentity').click( function() {
	window.location.replace( window.MENU_JSON_URLS.bs_idp_archivist_managedb_input_legalentities );
})

$('#btn_input_budgetcode').click( function() {
	window.location.replace( window.MENU_JSON_URLS.bs_idp_archivist_managedb_input_budgetcodes );
})

$('#btn_input_documentnature').click( function() {
	window.location.replace( window.MENU_JSON_URLS.bs_idp_archivist_managedb_input_documentnatures );
})

$('#btn_input_description1').click( function() {
	window.location.replace( window.MENU_JSON_URLS.bs_idp_archivist_managedb_input_descriptions1 );
})

$('#btn_input_description2').click( function() {
	window.location.replace( window.MENU_JSON_URLS.bs_idp_archivist_managedb_input_descriptions2 );
})

$('#btn_input_deliveraddress').click( function() {
	window.location.replace( window.MENU_JSON_URLS.bs_idp_archivist_managedb_input_deliveraddress );
})

$('#btn_input_documenttype').click( function() {
	window.location.replace( window.MENU_JSON_URLS.bs_idp_archivist_managedb_input_documenttypes );
})

$('#btn_provider').click( function() {
	window.location.replace( window.MENU_JSON_URLS.bs_idp_archivist_managedb_providers );
})

$('#btn_users').click( function() {
	window.location.replace( window.MENU_JSON_URLS.bs_core_users_admin_list );
})

$('#btn_visibility').click( function() {
	window.location.replace( window.MENU_JSON_URLS.bs_idp_backoffice_manage_visibility );
})

$('#btn_input_localization').click( function() {
    window.location.replace( window.MENU_JSON_URLS.bs_idp_archivist_managedb_input_localizations );
})

$('#btn_settings').click( function() {
    window.location.replace( window.MENU_JSON_URLS.bs_idp_backoffice_manage_globalsettings_passwords );
})

function dbrowstyle(row, index) {
    if( row['cansuppress'] == true )
        return { classes: 'danger' };
    else
        return { };
}

var $_translations = null;

var $view_budgetcode = 1;
var $mandatory_budgetcode = 1;
var $view_documentnature = 1;
var $mandatory_documentnature = 1;
var $view_documenttype = 1;
var $mandatory_documenttype = 1;
var $view_description1 = 1;
var $mandatory_description1 = 1;
var $name_description1 = 'Descriptif 1';
var $view_description2 = 1;
var $mandatory_description2 = 1;
var $name_description2 = 'Descriptif 2';
var $view_limitsnum = 1;
var $mandatory_limitsnum = 1;
var $view_limitsalpha = 1;
var $mandatory_limitsalpha = 1;
var $view_limitsalphanum = 1;
var $mandatory_limitsalphanum = 1;
var $view_limitsdate = 1;
var $mandatory_limitsdate = 1;
var $view_filenumber = 1;
var $mandatory_filenumber = 1;
var $view_boxnumber = 1;
var $mandatory_boxnumber = 1;
var $view_containernumber = 1;
var $mandatory_containernumber = 1;
var $view_provider = 1;
var $mandatory_provider = 1;
var $default_language = 0;
var $view_transfer_internal_basket = 1;
var $view_transfer_intermediate_basket = 1;
var $view_transfer_provider_basket = 1;
var $view_reloc_internal_basket = 1;
var $view_reloc_intermediate_basket = 1;
var $view_reloc_provider_basket = 1;

var SETTINGSFIELD_VIEWBUDGETCODE 			= 0;
var SETTINGSFIELD_MANDATORYBUDGETCODE		= 1;
var SETTINGSFIELD_VIEWDOCUMENTNATURE		= 2;
var SETTINGSFIELD_MANDATORYDOCUMENTNATURE	= 3;
var SETTINGSFIELD_VIEWDOCUMENTTYPE			= 4;
var SETTINGSFIELD_MANDATORYDOCUMENTTYPE		= 5;
var SETTINGSFIELD_VIEWDESCRIPTION1			= 6;
var SETTINGSFIELD_MANDATORYDESCRIPTION1		= 7;
var SETTINGSFIELD_NAMEDESCRIPTION1			= 8;
var SETTINGSFIELD_VIEWDESCRIPTION2			= 9;
var SETTINGSFIELD_MANDATORYDESCRIPTION2		= 10;
var SETTINGSFIELD_NAMEDESCRIPTION2			= 11;
var SETTINGSFIELD_VIEWLIMITSNUM				= 12;
var SETTINGSFIELD_MANDATORYLIMITSNUM		= 13;
var SETTINGSFIELD_VIEWLIMITSALPHA			= 14;
var SETTINGSFIELD_MANDATORYLIMITSALPHA		= 15;
var SETTINGSFIELD_VIEWLIMITSALPHANUM		= 16;
var SETTINGSFIELD_MANDATORYLIMITSALPHANUM	= 17;
var SETTINGSFIELD_VIEWLIMITSDATE			= 18;
var SETTINGSFIELD_MANDATORYLIMITSDATE		= 19;
var SETTINGSFIELD_VIEWFILENUMBER			= 20;
var SETTINGSFIELD_MANDATORYFILENUMBER		= 21;
var SETTINGSFIELD_VIEWBOXNUMBER				= 22;
var SETTINGSFIELD_MANDATORYBOXNUMBER		= 23;
var SETTINGSFIELD_VIEWCONTAINERNUMBER		= 24;
var SETTINGSFIELD_MANDATORYCONTAINERNUMBER	= 25;
var SETTINGSFIELD_VIEWPROVIDER				= 26;
var SETTINGSFIELD_MANDATORYPROVIDER			= 27;
var SETTINGSFIELD_DEFAULTLANGUAGE			= 28;
var SETTINGSFIELD_VIEWTRANSFERINTERNALBASKET = 29;
var SETTINGSFIELD_VIEWTRANSFERINTERMEDIATEBASKET = 30;
var SETTINGSFIELD_VIEWTRANSFERPROVIDERBASKET = 31;
var SETTINGSFIELD_VIEWRELOCINTERNALBASKET = 32;
var SETTINGSFIELD_VIEWRELOCINTERMEDIATEBASKET = 33;
var SETTINGSFIELD_VIEWRELOCPROVIDERBASKET = 34;
var SETTINGSFIELD_ALLSERVICESATONCE = 99;

var SERVICE_ID = 0;
var SERVICE_NAME = 1

$(document).ready(function(){
	$_translations = JSON.parse( window.IDP_CONST.bs_translations );

    $('#serviceConfig').prop( 'disabled', true );
    getServicesList();

    var $allServiceAtOnce = JSON.parse( window.IDP_CONST.bs_idp_allserviceatonce );
    if( $allServiceAtOnce ){
    	$('#all_in_once').prop('checked', true );
        $('#serviceConfig').prop( 'disabled', true );
        $('#serviceConfig').css({ 'background-color': '#DDD' });
	}
});

// Retreives list of allowed services for the current user
// (gets also all other lists, but they are not used here)
function getServicesList(){
    var $dataObj = null;

    $.ajax({
        type: "POST",
        url: window.JSON_URLS.bs_idp_archive_form_initlists,
        data: $dataObj,
        cache: false,
        success: function( data ){
            var $allowed_services = data[0];
            initServicesSelect( $allowed_services );
            $('#serviceConfig').prop( 'disabled', false );

            getAjaxSettings( $('#serviceConfig option:selected').val() );
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert( 'Error in getting services' );
        }
    });
}

// Intialize the select box with all services received
function initServicesSelect( $servicesList ){
    var $serviceOptions = "";
    var $i = 0;
    var $bSelected = false;
    $servicesList.forEach(function($serviceLine){
        var $selectedStr = "";
        if( !$bSelected ){
            $bSelected = true;
            $selectedStr = " selected='selected' ";
        }
        $serviceOptions += "<option value=\"" + $serviceLine[SERVICE_ID] + "\" data=\"" + $i + "\" " + $selectedStr + ">" + $serviceLine[SERVICE_NAME] + "</option> ";
        $i = $i+1;
    });
    $('#serviceConfig').html( $serviceOptions );
}

// Retreives the Settings based on the selected service
function getAjaxSettings( $serviceId ){
    $('#waitAjax').show();
    var $dataObj = {
        'serviceid': $serviceId
    };

    $.ajax({
        type: "GET",
        url: window.JSON_URLS.bs_idp_backoffice_ajax_settings,
        data: $dataObj,
        cache: false,
        success: function ( data ){
            if( getSettings( data ) )
                setView( );
            $('#waitAjax').hide();
        },
        error: function ( xhr, ajaxOptions, thrownError ){
            $('#waitAjax').hide();
            alert( 'Error in getting settings' );
        }
    })
}

function getSettings( $settings ){

	if('view_budgetcode' in $settings)
		$view_budgetcode = $settings.view_budgetcode;
	else return false;

	if('mandatory_budgetcode' in $settings)
		$mandatory_budgetcode = $settings.mandatory_budgetcode;
	else return false;

	if('view_documentnature' in $settings)
		$view_documentnature = $settings.view_documentnature;
	else return false;

	if('mandatory_documentnature' in $settings)
		$mandatory_documentnature = $settings.mandatory_documentnature;
	else return false;

	if('view_documenttype' in $settings)
		$view_documenttype = $settings.view_documenttype;
	else return false;

	if('mandatory_documenttype' in $settings)
		$mandatory_documenttype = $settings.mandatory_documenttype;
	else return false;

	if('view_description1' in $settings)
		$view_description1 = $settings.view_description1;
	else return false;

	if('mandatory_description1' in $settings)
		$mandatory_description1 = $settings.mandatory_description1;
	else return false;

	if('name_description1' in $settings)
		$name_description1 = $settings.name_description1;
	else return false;

	if('view_description2' in $settings)
		$view_description2 = $settings.view_description2;
	else return false;

	if('mandatory_description2' in $settings)
		$mandatory_description2 = $settings.mandatory_description2;
	else return false;

	if('name_description2' in $settings)
		$name_description2 = $settings.name_description2;
	else return false;

	if('view_limitsnum' in $settings)
		$view_limitsnum = $settings.view_limitsnum;
	else return false;

	if('mandatory_limitsnum' in $settings)
		$mandatory_limitsnum = $settings.mandatory_limitsnum;
	else return false;

	if('view_limitsalpha' in $settings)
		$view_limitsalpha = $settings.view_limitsalpha;
	else return false;

	if('mandatory_limitsalpha' in $settings)
		$mandatory_limitsalpha = $settings.mandatory_limitsalpha;
	else return false;

	if('view_limitsalphanum' in $settings)
		$view_limitsalphanum = $settings.view_limitsalphanum;
	else return false;

	if('mandatory_limitsalphanum' in $settings)
		$mandatory_limitsalphanum = $settings.mandatory_limitsalphanum;
	else return false;

	if('view_limitsdate' in $settings)
		$view_limitsdate = $settings.view_limitsdate;
	else return false;

	if('mandatory_limitsdate' in $settings)
		$mandatory_limitsdate = $settings.mandatory_limitsdate;
	else return false;

	if('view_filenumber' in $settings)
		$view_filenumber = $settings.view_filenumber;
	else return false;

	if('mandatory_filenumber' in $settings)
		$mandatory_filenumber = $settings.mandatory_filenumber;
	else return false;

	if('view_boxnumber' in $settings)
		$view_boxnumber = $settings.view_boxnumber;
	else return false;

	if('mandatory_boxnumber' in $settings)
		$mandatory_boxnumber = $settings.mandatory_boxnumber;
	else return false;

	if('view_containernumber' in $settings)
		$view_containernumber = $settings.view_containernumber;
	else return false;

	if('mandatory_containernumber' in $settings)
		$mandatory_containernumber = $settings.mandatory_containernumber;
	else return false;

	if('view_provider' in $settings)
		$view_provider = $settings.view_provider;
	else return false;

	if('mandatory_provider' in $settings)
		$mandatory_provider = $settings.mandatory_provider;
	else return false;

	if('default_language' in $settings)
		$default_language = parseInt( $settings.default_language );
	else return false;

	if('view_transfer_internal_basket' in $settings)
		$view_transfer_internal_basket = $settings.view_transfer_internal_basket;
	else return false;

    if('view_transfer_intermediate_basket' in $settings)
        $view_transfer_intermediate_basket = $settings.view_transfer_intermediate_basket;
    else return false;

    if('view_transfer_provider_basket' in $settings)
        $view_transfer_provider_basket = $settings.view_transfer_provider_basket;
    else return false;

    if('view_reloc_internal_basket' in $settings)
        $view_reloc_internal_basket = $settings.view_reloc_internal_basket;
    else return false;

    if('view_reloc_intermediate_basket' in $settings)
        $view_reloc_intermediate_basket = $settings.view_reloc_intermediate_basket;
    else return false;

    if('view_reloc_provider_basket' in $settings)
        $view_reloc_provider_basket = $settings.view_reloc_provider_basket;
    else return false;

	return true;
};

function activeButton( $button, $type, $disabled ){
	if( !$disabled ){
		$button.removeClass( 'btn-default' );
		$button.removeClass( 'disabled' );

		if( $type === 'view' )
			$button.addClass( 'btn-success' );
		else
			$button.addClass( 'btn-info' );
	} else {
		if( $type === 'view' )
			$button.removeClass( 'btn-success' );
		else
			$button.removeClass( 'btn-info' );
		$button.addClass( 'btn-default' );
		$button.addClass( 'disabled' );
	}
	if( $type === 'view' )
		$button.html( $_translations[3] );
	else
		$button.html( $_translations[4] );
}
function inactiveButton( $button, $type, $disabled ){
	if( !$disabled ){
		$button.removeClass( 'btn-default' );
		$button.removeClass( 'disabled' );

		if( $type === 'view' )
			$button.addClass( 'btn-danger' );
		else
			$button.addClass( 'btn-warning' );
	} else {
		if( $type === 'view' )
			$button.removeClass( 'btn-danger' );
		else
			$button.removeClass( 'btn-warning' );
		$button.addClass( 'btn-default' );
		$button.addClass( 'disabled' );
	}
	if( $type === 'view' )
		$button.html( $_translations[19] );
	else
		$button.html( $_translations[20] );
}

function setView( ){
	if( $view_budgetcode ){
		activeButton( $('#viewBudgetcode'), 'view', false );
		if( $mandatory_budgetcode )
			activeButton( $('#mandatoryBudgetcode'), 'mandatory', false );
		else
			inactiveButton( $('#mandatoryBudgetcode'), 'mandatory', false );

	} else {
		inactiveButton( $('#viewBudgetcode'), 'view', false );
		if( $mandatory_budgetcode )
			activeButton( $('#mandatoryBudgetcode'), 'mandatory', true );
		else
			inactiveButton( $('#mandatoryBudgetcode'), 'mandatory', true );

	}

	if( $view_documentnature ){
		activeButton( $('#viewDocumentnature'), 'view', false );
		if( $mandatory_documentnature )
			activeButton( $('#mandatoryDocumentnature'), 'mandatory', false );
		else
			inactiveButton( $('#mandatoryDocumentnature'), 'mandatory', false );


		if( $view_documenttype ){
			activeButton( $('#viewDocumenttype'), 'view', false );
			if( $mandatory_documenttype )
				activeButton( $('#mandatoryDocumenttype'), 'mandatory', false );
			else
				inactiveButton( $('#mandatoryDocumenttype'), 'mandatory', false );
		} else {
			inactiveButton( $('#viewDocumenttype'), 'view', false );
			if( $mandatory_documenttype )
				activeButton( $('#mandatoryDocumenttype'), 'mandatory', true );
			else
				inactiveButton( $('#mandatoryDocumenttype'), 'mandatory', true );
		}
	} else {
		inactiveButton( $('#viewDocumentnature'), 'view', false );
		if( $mandatory_documentnature )
			activeButton( $('#mandatoryDocumentnature'), 'mandatory', true );
		else
			inactiveButton( $('#mandatoryDocumentnature'), 'mandatory', true );


		if( $view_documenttype )
			activeButton( $('#viewDocumenttype'), 'view', true );
		else
			inactiveButton( $('#viewDocumenttype'), 'view', true );

		if( $mandatory_documenttype )
			activeButton( $('#mandatoryDocumenttype'), 'mandatory', true );
		else
			inactiveButton( $('#mandatoryDocumenttype'), 'mandatory', true );
	}

	$('#nameDescription1').val( $name_description1 );
	if( $view_description1 ){
		activeButton( $('#viewDescription1'), 'view', false );
		if( $mandatory_description1 )
			activeButton( $('#mandatoryDescription1'), 'mandatory', false );
		else
			inactiveButton( $('#mandatoryDescription1'), 'mandatory', false );
		$('#nameDescription1').prop( 'disabled', false );
	} else {
		inactiveButton( $('#viewDescription1'), 'view', false );
		if( $mandatory_description1 )
			activeButton( $('#mandatoryDescription1'), 'mandatory', true );
		else
			inactiveButton( $('#mandatoryDescription1'), 'mandatory', true );
		$('#nameDescription1').prop( 'disabled', true );
	}

	$('#nameDescription2').val( $name_description2 );
	if( $view_description2 ){
		activeButton( $('#viewDescription2'), 'view', false );
		if( $mandatory_description2 )
			activeButton( $('#mandatoryDescription2'), 'mandatory', false );
		else
			inactiveButton( $('#mandatoryDescription2'), 'mandatory', false );
		$('#nameDescription2').prop( 'disabled', false );
	} else {
		inactiveButton( $('#viewDescription2'), 'view', false );
		if( $mandatory_description2 )
			activeButton( $('#mandatoryDescription2'), 'mandatory', true );
		else
			inactiveButton( $('#mandatoryDescription2'), 'mandatory', true );
		$('#nameDescription2').prop( 'disabled', true );
	}

	if( $view_limitsnum ){
		activeButton( $('#viewLimitsnum'), 'view', false );
		if( $mandatory_limitsnum )
			activeButton( $('#mandatoryLimitsnum'), 'mandatory', false );
		else
			inactiveButton( $('#mandatoryLimitsnum'), 'mandatory', false );

	} else {
		inactiveButton( $('#viewLimitsnum'), 'view', false );
		if( $mandatory_limitsnum )
			activeButton( $('#mandatoryLimitsnum'), 'mandatory', true );
		else
			inactiveButton( $('#mandatoryLimitsnum'), 'mandatory', true );
	}

	if( $view_limitsalpha ){
		activeButton( $('#viewLimitsalpha'), 'view', false );
		if( $mandatory_limitsalpha )
			activeButton( $('#mandatoryLimitsalpha'), 'mandatory', false );
		else
			inactiveButton( $('#mandatoryLimitsalpha'), 'mandatory', false );

	} else {
		inactiveButton( $('#viewLimitsalpha'), 'view', false );
		if( $mandatory_limitsalpha )
			activeButton( $('#mandatoryLimitsalpha'), 'mandatory', true );
		else
			inactiveButton( $('#mandatoryLimitsalpha'), 'mandatory', true );
	}

	if( $view_limitsalphanum ){
		activeButton( $('#viewLimitsalphanum'), 'view', false );
		if( $mandatory_limitsalphanum )
			activeButton( $('#mandatoryLimitsalphanum'), 'mandatory', false );
		else
			inactiveButton( $('#mandatoryLimitsalphanum'), 'mandatory', false );

	} else {
		inactiveButton( $('#viewLimitsalphanum'), 'view', false );
		if( $mandatory_limitsalphanum )
			activeButton( $('#mandatoryLimitsalphanum'), 'mandatory', true );
		else
			inactiveButton( $('#mandatoryLimitsalphanum'), 'mandatory', true );
	}

	if( $view_limitsdate ){
		activeButton( $('#viewLimitsdate'), 'view', false );
		if( $mandatory_limitsdate )
			activeButton( $('#mandatoryLimitsdate'), 'mandatory', false );
		else
			inactiveButton( $('#mandatoryLimitsdate'), 'mandatory', false );

	} else {
		inactiveButton( $('#viewLimitsdate'), 'view', false );
		if( $mandatory_limitsdate )
			activeButton( $('#mandatoryLimitsdate'), 'mandatory', true );
		else
			inactiveButton( $('#mandatoryLimitsdate'), 'mandatory', true );
	}

	if( $view_filenumber ){
		activeButton( $('#viewFilenumber'), 'view', false );
		if( $mandatory_filenumber )
			activeButton( $('#mandatoryFilenumber'), 'mandatory', false );
		else
			inactiveButton( $('#mandatoryFilenumber'), 'mandatory', false );

	} else {
		inactiveButton( $('#viewFilenumber'), 'view', false );
		if( $mandatory_filenumber )
			activeButton( $('#mandatoryFilenumber'), 'mandatory', true );
		else
			inactiveButton( $('#mandatoryFilenumber'), 'mandatory', true );
	}

	if( $view_boxnumber ){
		activeButton( $('#viewBoxnumber'), 'view', false );
		if( $mandatory_boxnumber )
			activeButton( $('#mandatoryBoxnumber'), 'mandatory', false );
		else
			inactiveButton( $('#mandatoryBoxnumber'), 'mandatory', false );

	} else {
		inactiveButton( $('#viewBoxnumber'), 'view', false );
		if( $mandatory_boxnumber )
			activeButton( $('#mandatoryBoxnumber'), 'mandatory', true );
		else
			inactiveButton( $('#mandatoryBoxnumber'), 'mandatory', true );
	}

	if( $view_containernumber ){
		activeButton( $('#viewContainernumber'), 'view', false );
		if( $mandatory_containernumber )
			activeButton( $('#mandatoryContainernumber'), 'mandatory', false );
		else
			inactiveButton( $('#mandatoryContainernumber'), 'mandatory', false );

	} else {
		inactiveButton( $('#viewContainernumber'), 'view', false );
		if( $mandatory_containernumber )
			activeButton( $('#mandatoryContainernumber'), 'mandatory', true );
		else
			inactiveButton( $('#mandatoryContainernumber'), 'mandatory', true );
	}

	if( $view_provider ){
		activeButton( $('#viewProvider'), 'view', false );
		if( $mandatory_provider )
			activeButton( $('#mandatoryProvider'), 'mandatory', false );
		else
			inactiveButton( $('#mandatoryProvider'), 'mandatory', false );

	} else {
		inactiveButton( $('#viewProvider'), 'view', false );
		if( $mandatory_provider )
			activeButton( $('#mandatoryProvider'), 'mandatory', true );
		else
			inactiveButton( $('#mandatoryProvider'), 'mandatory', true );
	}

	if( $view_transfer_internal_basket ){
		activeButton( $('#viewTransferInternalBasket'), 'view', false );
	} else {
		inactiveButton( $('#viewTransferInternalBasket'), 'view', false );
	}

    if( $view_transfer_intermediate_basket ){
        activeButton( $('#viewTransferIntermediateBasket'), 'view', false );
    } else {
        inactiveButton( $('#viewTransferIntermediateBasket'), 'view', false );
    }

    if( $view_transfer_provider_basket ){
        activeButton( $('#viewTransferProviderBasket'), 'view', false );
    } else {
        inactiveButton( $('#viewTransferProviderBasket'), 'view', false );
    }

    /* B#75: ask for common configuration between trasnfer and reloc, keep it here in case of
    if( $view_reloc_internal_basket ){
        activeButton( $('#viewRelocInternalBasket'), 'view', false );
    } else {
        inactiveButton( $('#viewRelocInternalBasket'), 'view', false );
    }

    if( $view_reloc_intermediate_basket ){
        activeButton( $('#viewRelocIntermediateBasket'), 'view', false );
    } else {
        inactiveButton( $('#viewRelocIntermediateBasket'), 'view', false );
    }

    if( $view_reloc_provider_basket ){
        activeButton( $('#viewRelocProviderBasket'), 'view', false );
    } else {
        inactiveButton( $('#viewRelocProviderBasket'), 'view', false );
    }
    */

    $("#defaultLanguage").val($default_language);
}

function switchButtonVisible( $view, $viewvalue, $mandatory, $mandatoryvalue, $mandatoryfield ){
	$view.removeClass( 'btn-danger' );
	$view.removeClass( 'btn-success' );
	if( $viewvalue ){
		$view.addClass( 'btn-danger' );
		$view.html( $_translations[19] );
		// force to not mandatory
		if( $mandatoryvalue ){
            $mandatory.html( $_translations[20] );
            ajax_updatefield( $mandatoryfield, 0, false );
		}
		$mandatory.removeClass( 'btn-info' );
		$mandatory.removeClass( 'btn-warning' );
		$mandatory.addClass( 'btn-default' );
		$mandatory.addClass( 'disabled' );
	} else {
		$view.addClass( 'btn-success' );
		$view.html( $_translations[3] );
		$mandatory.removeClass( 'btn-default' );
		$mandatory.removeClass( 'disabled' );
		if( $mandatoryvalue ){
			$mandatory.addClass( 'btn-info' );
		} else {
			$mandatory.addClass( 'btn-warning' );
		}
	}
}
function switchButtonMandatory( $mandatory, $mandatoryvalue ){
	$mandatory.removeClass( 'btn-info' );
	$mandatory.removeClass( 'btn-warning' );
	if( $mandatoryvalue ){
		$mandatory.addClass( 'btn-warning' );
		$mandatory.html( $_translations[20] );
	} else {
		$mandatory.addClass( 'btn-info' );
		$mandatory.html( $_translations[4] );
	}
}

function switchViewBudgetcode() {
	switchButtonVisible( $('#viewBudgetcode'), $view_budgetcode, $('#mandatoryBudgetcode'), $mandatory_budgetcode, SETTINGSFIELD_MANDATORYBUDGETCODE );
	$view_budgetcode = !$view_budgetcode;
	if( !$view_budgetcode ) $mandatory_budgetcode = false;
}
$('#viewBudgetcode').on('click', function(){
	// ajax new value to update settings
	ajax_updatefield( SETTINGSFIELD_VIEWBUDGETCODE, $view_budgetcode?0:1, true );
});

function switchMandatoryBudgetcode() {
	switchButtonMandatory( $('#mandatoryBudgetcode'), $mandatory_budgetcode );
	$mandatory_budgetcode = !$mandatory_budgetcode;
}
$('#mandatoryBudgetcode').on('click', function(){
	// ajax new value to update settings
	ajax_updatefield( SETTINGSFIELD_MANDATORYBUDGETCODE, $mandatory_budgetcode?0:1, true );
});

function switchViewDocumentnature() {
	switchButtonVisible( $('#viewDocumentnature'), $view_documentnature, $('#mandatoryDocumentnature'), $mandatory_documentnature, SETTINGSFIELD_MANDATORYDOCUMENTNATURE );
	$view_documentnature = !$view_documentnature;

	if( $view_documentnature ){
		if( $view_documenttype ){
			activeButton( $('#viewDocumenttype'), 'view', false );
			if( $mandatory_documenttype )
			activeButton( $('#mandatoryDocumenttype'), 'mandatory', false );
			else
			inactiveButton( $('#mandatoryDocumenttype'), 'mandatory', false );
		} else {
			inactiveButton( $('#viewDocumenttype'), 'view', false );
			if( $mandatory_documenttype )
			activeButton( $('#mandatoryDocumenttype'), 'mandatory', true );
			else
			inactiveButton( $('#mandatoryDocumenttype'), 'mandatory', true );
		}
	} else {
        $mandatory_documentnature = false;
        ajax_updatefield( SETTINGSFIELD_VIEWDOCUMENTTYPE, 0, false );
        $view_documentnature = false;
        ajax_updatefield( SETTINGSFIELD_MANDATORYDOCUMENTTYPE, 0, false );
        $mandatory_documentnature = false;

        $('#viewDocumenttype').removeClass( 'btn-success' );
        $('#viewDocumenttype').addClass( 'btn-danger' );
        $('#viewDocumenttype').html( $_translations[19] );
        $('#mandatoryDocumenttype').removeClass( 'btn-info' );
        $('#mandatoryDocumenttype').addClass( 'btn-warning' );
        $('#mandatoryDocumenttype').html( $_translations[20] );

		inactiveButton( $('#viewDocumenttype'), 'view', true );
		inactiveButton( $('#mandatoryDocumenttype'), 'mandatory', true );
	}
}
$('#viewDocumentnature').on('click', function(){
	// ajax new value to update settings
	ajax_updatefield( SETTINGSFIELD_VIEWDOCUMENTNATURE, $view_documentnature?0:1, true );
});

function switchMandatoryDocumentnature(){
	switchButtonMandatory( $('#mandatoryDocumentnature'), $mandatory_documentnature );
	$mandatory_documentnature = !$mandatory_documentnature;
}
$('#mandatoryDocumentnature').on('click', function(){
	// ajax new value to update settings
	ajax_updatefield( SETTINGSFIELD_MANDATORYDOCUMENTNATURE, $mandatory_documentnature?0:1, true );
});

function switchViewDocumenttype(){
	switchButtonVisible( $('#viewDocumenttype'), $view_documenttype, $('#mandatoryDocumenttype'), $mandatory_documenttype, SETTINGSFIELD_MANDATORYDOCUMENTTYPE );
	$view_documenttype = !$view_documenttype;
	if( !$view_documenttype ) $mandatory_documenttype = false;
}
$('#viewDocumenttype').on('click', function(){
	// ajax new value to update settings
	ajax_updatefield( SETTINGSFIELD_VIEWDOCUMENTTYPE, $view_documenttype?0:1, true );
});

function switchMandatoryDocumenttype(){
	switchButtonMandatory( $('#mandatoryDocumenttype'), $mandatory_documenttype );
	$mandatory_documenttype = !$mandatory_documenttype;
}
$('#mandatoryDocumenttype').on('click', function(){
	// ajax new value to update settings
	ajax_updatefield( SETTINGSFIELD_MANDATORYDOCUMENTTYPE, $mandatory_documenttype?0:1, true );
});

function switchViewDescription1(){
	switchButtonVisible( $('#viewDescription1'), $view_description1, $('#mandatoryDescription1'), $mandatory_description1, SETTINGSFIELD_MANDATORYDESCRIPTION1 );
	$view_description1 = !$view_description1;
	if( $view_description1 )
		$('#nameDescription1').prop( 'disabled', false );
	else {
        $('#nameDescription1').prop('disabled', true);
        $mandatory_description1 = false;
    }
}
$('#viewDescription1').on('click', function(){
	// ajax new value to update settings
	ajax_updatefield( SETTINGSFIELD_VIEWDESCRIPTION1, $view_description1?0:1, true );
});

function switchMandatoryDescription1(){
	switchButtonMandatory( $('#mandatoryDescription1'), $mandatory_description1 );
	$mandatory_description1 = !$mandatory_description1;
}
$('#mandatoryDescription1').on('click', function(){
	// ajax new value to update settings
	ajax_updatefield( SETTINGSFIELD_MANDATORYDESCRIPTION1, $mandatory_description1?0:1, true );
});

$('#nameDescription1').focusout( function(){
	// ajax new value to update settings
	if( $('#nameDescription1').val().trim().length <= 0 )
		popError( $('#divNameDescription1'), 'Le nom du descriptif 1 ne peut pas être vide.', 'top' );
	else
		ajax_updatefield( SETTINGSFIELD_NAMEDESCRIPTION1, $('#nameDescription1').val(), true );
});

function switchViewDescription2(){
	switchButtonVisible( $('#viewDescription2'), $view_description2, $('#mandatoryDescription2'), $mandatory_description2, SETTINGSFIELD_VIEWDESCRIPTION2 );
	$view_description2 = !$view_description2;
	if( $view_description2 )
		$('#nameDescription2').prop( 'disabled', false );
	else {
        $('#nameDescription2').prop('disabled', true);
        $mandatory_description2 = false;
    }
}
$('#viewDescription2').on('click', function(){
	// ajax new value to update settings
	ajax_updatefield( SETTINGSFIELD_VIEWDESCRIPTION2, $view_description2?0:1, true );
});

function switchMandatoryDescription2() {
	switchButtonMandatory( $('#mandatoryDescription2'), $mandatory_description2 );
	$mandatory_description2 = !$mandatory_description2;
}
$('#mandatoryDescription2').on('click', function(){
	// ajax new value to update settings
	ajax_updatefield( SETTINGSFIELD_MANDATORYDESCRIPTION2, $mandatory_description2?0:1, true );
});

$('#nameDescription2').focusout( function(){
	// ajax new value to update settings
	if( $('#nameDescription2').val().trim().length <= 0 )
		popError( $('#divNameDescription2'), 'Le nom du descriptif 2 ne peut pas être vide.', 'bottom');
	else
		ajax_updatefield( SETTINGSFIELD_NAMEDESCRIPTION2, $('#nameDescription2').val(), true );
});

function switchViewLimitsnum(){
	switchButtonVisible( $('#viewLimitsnum'), $view_limitsnum, $('#mandatoryLimitsnum'), $mandatory_limitsnum, SETTINGSFIELD_MANDATORYLIMITSNUM );
	$view_limitsnum = !$view_limitsnum;
	if( !$view_limitsnum ) $mandatory_limitsnum = false;
}
$('#viewLimitsnum').on('click', function(){
	// ajax new value to update settings
	ajax_updatefield( SETTINGSFIELD_VIEWLIMITSNUM, $view_limitsnum?0:1, true );
});

function switchMandatoryLimitsnum(){
	switchButtonMandatory( $('#mandatoryLimitsnum'), $mandatory_limitsnum );
	$mandatory_limitsnum = !$mandatory_limitsnum;
}
$('#mandatoryLimitsnum').on('click', function(){
	// ajax new value to update settings
	ajax_updatefield( SETTINGSFIELD_MANDATORYLIMITSNUM, $mandatory_limitsnum?0:1, true );
});

function switchViewLimitsalpha(){
	switchButtonVisible( $('#viewLimitsalpha'), $view_limitsalpha, $('#mandatoryLimitsalpha'), $mandatory_limitsalpha, SETTINGSFIELD_MANDATORYLIMITSALPHA );
	$view_limitsalpha = !$view_limitsalpha;
	if( !$view_limitsalpha ) $mandatory_limitsalpha = false;
}
$('#viewLimitsalpha').on('click', function(){
	// ajax new value to update settings
	ajax_updatefield( SETTINGSFIELD_VIEWLIMITSALPHA, $view_limitsalpha?0:1, true );
});

function switchMandatoryLimitsalpha(){
	switchButtonMandatory( $('#mandatoryLimitsalpha'), $mandatory_limitsalpha );
	$mandatory_limitsalpha = !$mandatory_limitsalpha;
}
$('#mandatoryLimitsalpha').on('click', function(){
	// ajax new value to update settings
	ajax_updatefield( SETTINGSFIELD_MANDATORYLIMITSALPHA, $mandatory_limitsalpha?0:1, true );
});

function switchViewLimitsalphanum(){
	switchButtonVisible( $('#viewLimitsalphanum'), $view_limitsalphanum, $('#mandatoryLimitsalphanum'), $mandatory_limitsalphanum, SETTINGSFIELD_MANDATORYLIMITSALPHANUM );
	$view_limitsalphanum = !$view_limitsalphanum;
	if( !$view_limitsalphanum ) $mandatory_limitsalphanum = false;
}
$('#viewLimitsalphanum').on('click', function(){
	// ajax new value to update settings
	ajax_updatefield( SETTINGSFIELD_VIEWLIMITSALPHANUM, $view_limitsalphanum?0:1, true );
});

function switchMandatoryLimitsalphanum(){
	switchButtonMandatory( $('#mandatoryLimitsalphanum'), $mandatory_limitsalphanum );
	$mandatory_limitsalphanum = !$mandatory_limitsalphanum;
}
$('#mandatoryLimitsalphanum').on('click', function(){
	// ajax new value to update settings
	ajax_updatefield( SETTINGSFIELD_MANDATORYLIMITSALPHANUM, $mandatory_limitsalphanum?0:1, true );
});

function switchViewLimitsdate(){
	switchButtonVisible( $('#viewLimitsdate'), $view_limitsdate, $('#mandatoryLimitsdate'), $mandatory_limitsdate, SETTINGSFIELD_MANDATORYLIMITSDATE );
	$view_limitsdate = !$view_limitsdate;
	if( !$view_limitsdate ) $mandatory_limitsdate = false;
}
$('#viewLimitsdate').on('click', function(){
	// ajax new value to update settings
	ajax_updatefield( SETTINGSFIELD_VIEWLIMITSDATE, $view_limitsdate?0:1, true );
});

function switchMandatoryLimitsdate(){
	switchButtonMandatory( $('#mandatoryLimitsdate'), $mandatory_limitsdate );
	$mandatory_limitsdate = !$mandatory_limitsdate;
}
$('#mandatoryLimitsdate').on('click', function(){
	// ajax new value to update settings
	ajax_updatefield( SETTINGSFIELD_MANDATORYLIMITSDATE, $mandatory_limitsdate?0:1, true );
});

function switchViewFilenumber() {
	switchButtonVisible( $('#viewFilenumber'), $view_filenumber, $('#mandatoryFilenumber'), $mandatory_filenumber, SETTINGSFIELD_MANDATORYFILENUMBER );
	$view_filenumber = !$view_filenumber;
	if( !$view_filenumber ) $mandatory_filenumber = false;
}
$('#viewFilenumber').on('click', function(){
	// ajax new value to update settings
	ajax_updatefield( SETTINGSFIELD_VIEWFILENUMBER, $view_filenumber?0:1, true );
});

function switchMandatoryFilenumber(){
	switchButtonMandatory( $('#mandatoryFilenumber'), $mandatory_filenumber );
	$mandatory_filenumber = !$mandatory_filenumber;
}
$('#mandatoryFilenumber').on('click', function(){
	// ajax new value to update settings
	ajax_updatefield( SETTINGSFIELD_MANDATORYFILENUMBER, $mandatory_filenumber?0:1, true );
});

function switchViewBoxnumber() {
	switchButtonVisible( $('#viewBoxnumber'), $view_boxnumber, $('#mandatoryBoxnumber'), $mandatory_boxnumber, SETTINGSFIELD_MANDATORYBOXNUMBER );
	$view_boxnumber = !$view_boxnumber;
	if( !$view_boxnumber ) $mandatory_boxnumber = false;
}
$('#viewBoxnumber').on('click', function(){
	// ajax new value to update settings
	ajax_updatefield( SETTINGSFIELD_VIEWBOXNUMBER, $view_boxnumber?0:1, true );
});

function switchMandatoryBoxnumber(){
	switchButtonMandatory( $('#mandatoryBoxnumber'), $mandatory_boxnumber );
	$mandatory_boxnumber = !$mandatory_boxnumber;
}
$('#mandatoryBoxnumber').on('click', function(){
	// ajax new value to update settings
	ajax_updatefield( SETTINGSFIELD_MANDATORYBOXNUMBER, $mandatory_boxnumber?0:1, true );
});

function switchViewContainernumber() {
	switchButtonVisible( $('#viewContainernumber'), $view_containernumber, $('#mandatoryContainernumber'), $mandatory_containernumber, SETTINGSFIELD_MANDATORYCONTAINERNUMBER );
	$view_containernumber = !$view_containernumber;
	if( !$view_containernumber ) $mandatory_containernumber = false;
}
$('#viewContainernumber').on('click', function(){
	// ajax new value to update settings
	ajax_updatefield( SETTINGSFIELD_VIEWCONTAINERNUMBER, $view_containernumber?0:1, true );
});

function switchMandatoryContainernumber(){
	switchButtonMandatory( $('#mandatoryContainernumber'), $mandatory_containernumber );
	$mandatory_containernumber = !$mandatory_containernumber;
}
$('#mandatoryContainernumber').on('click', function(){
	// ajax new value to update settings
	ajax_updatefield( SETTINGSFIELD_MANDATORYCONTAINERNUMBER, $mandatory_containernumber?0:1, true );
});

function switchViewProvider() {
	switchButtonVisible( $('#viewProvider'), $view_provider, $('#mandatoryProvider'), $mandatory_provider, SETTINGSFIELD_MANDATORYPROVIDER );
	$view_provider = !$view_provider;
	if( !$view_provider ) $mandatory_provider = false;
}
$('#viewProvider').on('click', function(){
	// ajax new value to update settings
	ajax_updatefield( SETTINGSFIELD_VIEWPROVIDER, $view_provider?0:1, true );
});

function switchMandatoryProvider(){
	switchButtonMandatory( $('#mandatoryProvider'), $mandatory_provider );
	$mandatory_provider = !$mandatory_provider;
}
$('#mandatoryProvider').on('click', function(){
	// ajax new value to update settings
	ajax_updatefield( SETTINGSFIELD_MANDATORYPROVIDER, $mandatory_provider?0:1, true );
});

$('#defaultLanguage').on('change', function(){
	// ajax new value to update settings
	ajax_updatefield( SETTINGSFIELD_DEFAULTLANGUAGE, $('#defaultLanguage option:selected').val(), true );
});

$('#serviceConfig').on('change', function(){
    // get back settings of new service selected
    cleanAll();
    getAjaxSettings( $('#serviceConfig option:selected').val() );
});

function switchViewTransferIntermediateBasket( ){
    cleanOne( $('#viewTransferIntermediateBasket'), null );
	if( $view_transfer_intermediate_basket )
		inactiveButton( $('#viewTransferIntermediateBasket'), 'view', false );
	else
		activeButton( $('#viewTransferIntermediateBasket'), 'view', false );
	$view_transfer_intermediate_basket = !$view_transfer_intermediate_basket;
}
$('#viewTransferIntermediateBasket').on('click', function(){
	if( !$view_transfer_internal_basket && !$view_transfer_provider_basket && $view_transfer_intermediate_basket )
		bootbox.alert( {
			message: "Il ne peut pas y avoir aucun panier visible !",
			className: "boxErrorOne"
		} );
	else
		// ajax new value to update settings
		ajax_updatefield( SETTINGSFIELD_VIEWTRANSFERINTERMEDIATEBASKET, $view_transfer_intermediate_basket?0:1, true );
});

function switchViewTransferInternalBasket( ){
    cleanOne( $('#viewTransferInternalBasket'), null );
    if( $view_transfer_internal_basket )
        inactiveButton( $('#viewTransferInternalBasket'), 'view', false );
    else
        activeButton( $('#viewTransferInternalBasket'), 'view', false );
    $view_transfer_internal_basket = !$view_transfer_internal_basket;
}
$('#viewTransferInternalBasket').on('click', function(){
    if( $view_transfer_internal_basket && !$view_transfer_provider_basket && !$view_transfer_intermediate_basket )
        bootbox.alert( {
			message: "Il ne peut pas y avoir aucun panier visible !",
			className: "boxErrorOne"
		} );
    else
	    // ajax new value to update settings
    	ajax_updatefield( SETTINGSFIELD_VIEWTRANSFERINTERNALBASKET, $view_transfer_internal_basket?0:1, true );
});

function switchViewTransferProviderBasket( ){
    cleanOne( $('#viewTransferProviderBasket'), null );
    if( $view_transfer_provider_basket )
        inactiveButton( $('#viewTransferProviderBasket'), 'view', false );
    else
        activeButton( $('#viewTransferProviderBasket'), 'view', false );
    $view_transfer_provider_basket = !$view_transfer_provider_basket;
}
$('#viewTransferProviderBasket').on('click', function(){
    if( !$view_transfer_internal_basket && $view_transfer_provider_basket && !$view_transfer_intermediate_basket )
        bootbox.alert( {
			message: "Il ne peut pas y avoir aucun panier visible !",
			className: "boxErrorOne"
		} );
    else
	    // ajax new value to update settings
    	ajax_updatefield( SETTINGSFIELD_VIEWTRANSFERPROVIDERBASKET, $view_transfer_provider_basket?0:1, true );
});

function cleanOne( $view, $mandatory ){
	if( $view ) {
        $view.removeClass('btn-danger');
        $view.removeClass('btn-success');
        $view.removeClass('btn-default');
        $view.html($_translations[3]);
    }
    if( $mandatory ) {
        $mandatory.removeClass('btn-info');
        $mandatory.removeClass('btn-warning');
        $mandatory.removeClass('btn-default');
        $view.html($_translations[3]);
    }
}
function cleanAll(){
    cleanOne( $('#viewBudgetcode'), $('#mandatoryBudgetcode') );
    cleanOne( $('#viewDocumentnature'), $('#mandatoryDocumentnature') );
    cleanOne( $('#viewDocumenttype'), $('#mandatoryDocumenttype') );
    cleanOne( $('#viewDescription1'), $('#mandatoryDescription1') );
    cleanOne( $('#viewDescription2'), $('#mandatoryDescription2') );
    cleanOne( $('#viewLimitsnum'), $('#mandatoryLimitsnum') );
    cleanOne( $('#viewLimitsalpha'), $('#mandatoryLimitsalpha') );
    cleanOne( $('#viewLimitsalphanum'), $('#mandatoryLimitsalphanum') );
    cleanOne( $('#viewLimitsdate'), $('#mandatoryLimitsdate') );
    cleanOne( $('#viewFilenumber'), $('#mandatoryFilenumber') );
    cleanOne( $('#viewBoxnumber'), $('#mandatoryBoxnumber') );
    cleanOne( $('#viewContainernumber'), $('#mandatoryContainernumber') );
    cleanOne( $('#viewProvider'), $('#mandatoryProvider') );
    cleanOne( $('#viewTransferInternalBasket'), null );
    cleanOne( $('#viewTransferIntermediateBasket'), null );
    cleanOne( $('#viewTransferProviderBasket'), null );
    cleanOne( $('#viewRelocInternalBasket'), null );
    cleanOne( $('#viewRelocIntermediateBasket'), null );
    cleanOne( $('#viewRelocProviderBasket'), null );
}

function ajax_updatefield( $field, $value, $callswitch ){
    $serviceId = $('#serviceConfig option:selected').val();
	$dataObj = {
		'settingsfield': $field,
		'value': $value,
        'serviceid': $serviceId
	}
	$.ajax({
		type: "GET",
		url: window.JSON_URLS.bs_idp_backoffice_set_settings,
		data: $dataObj,
		cache: false,
		success: function( ){
			switch( $field ){
				case SETTINGSFIELD_VIEWBUDGETCODE:
					if( $callswitch ) switchViewBudgetcode();
					break;
				case SETTINGSFIELD_MANDATORYBUDGETCODE:
                    if( $callswitch ) switchMandatoryBudgetcode();
					break;
				case SETTINGSFIELD_VIEWDOCUMENTNATURE:
                    if( $callswitch ) switchViewDocumentnature();
					break;
				case SETTINGSFIELD_MANDATORYDOCUMENTNATURE:
                    if( $callswitch ) switchMandatoryDocumentnature();
					break;
				case SETTINGSFIELD_VIEWDOCUMENTTYPE:
                    if( $callswitch ) switchViewDocumenttype();
					break;
				case SETTINGSFIELD_MANDATORYDOCUMENTTYPE:
                    if( $callswitch ) switchMandatoryDocumenttype();
					break;
				case SETTINGSFIELD_VIEWDESCRIPTION1:
                    if( $callswitch ) switchViewDescription1();
					break;
				case SETTINGSFIELD_MANDATORYDESCRIPTION1:
                    if( $callswitch ) switchMandatoryDescription1();
					break;
				case SETTINGSFIELD_NAMEDESCRIPTION1:
					break;
				case SETTINGSFIELD_VIEWDESCRIPTION2:
                    if( $callswitch ) switchViewDescription2();
					break;
				case SETTINGSFIELD_MANDATORYDESCRIPTION2:
                    if( $callswitch ) switchMandatoryDescription2();
					break;
				case SETTINGSFIELD_NAMEDESCRIPTION2:
					break;
				case SETTINGSFIELD_VIEWLIMITSNUM:
                    if( $callswitch ) switchViewLimitsnum();
					break;
				case SETTINGSFIELD_MANDATORYLIMITSNUM:
                    if( $callswitch ) switchMandatoryLimitsnum();
					break;
				case SETTINGSFIELD_VIEWLIMITSALPHA:
                    if( $callswitch ) switchViewLimitsalpha();
					break;
				case SETTINGSFIELD_MANDATORYLIMITSALPHA:
                    if( $callswitch ) switchMandatoryLimitsalpha();
					break;
				case SETTINGSFIELD_VIEWLIMITSALPHANUM:
                    if( $callswitch ) switchViewLimitsalphanum();
					break;
				case SETTINGSFIELD_MANDATORYLIMITSALPHANUM:
                    if( $callswitch ) switchMandatoryLimitsalphanum();
					break;
				case SETTINGSFIELD_VIEWLIMITSDATE:
                    if( $callswitch ) switchViewLimitsdate();
					break;
				case SETTINGSFIELD_MANDATORYLIMITSDATE:
                    if( $callswitch ) switchMandatoryLimitsdate();
					break;
				case SETTINGSFIELD_VIEWFILENUMBER:
                    if( $callswitch ) switchViewFilenumber();
					break;
				case SETTINGSFIELD_MANDATORYFILENUMBER:
                    if( $callswitch ) switchMandatoryFilenumber();
					break;
				case SETTINGSFIELD_VIEWBOXNUMBER:
                    if( $callswitch ) switchViewBoxnumber();
					break;
				case SETTINGSFIELD_MANDATORYBOXNUMBER:
                    if( $callswitch ) switchMandatoryBoxnumber();
					break;
				case SETTINGSFIELD_VIEWCONTAINERNUMBER:
                    if( $callswitch ) switchViewContainernumber();
					break;
				case SETTINGSFIELD_MANDATORYCONTAINERNUMBER:
                    if( $callswitch ) switchMandatoryContainernumber();
					break;
				case SETTINGSFIELD_VIEWPROVIDER:
                    if( $callswitch ) switchViewProvider();
					break;
				case SETTINGSFIELD_MANDATORYPROVIDER:
                    if( $callswitch ) switchMandatoryProvider();
					break;
				case SETTINGSFIELD_DEFAULTLANGUAGE:
					break;
				case SETTINGSFIELD_VIEWTRANSFERINTERNALBASKET:
					if( $callswitch ) switchViewTransferInternalBasket();
					break;
                case SETTINGSFIELD_VIEWTRANSFERINTERMEDIATEBASKET:
                    if( $callswitch ) switchViewTransferIntermediateBasket();
                    break;
                case SETTINGSFIELD_VIEWTRANSFERPROVIDERBASKET:
                    if( $callswitch ) switchViewTransferProviderBasket();
                    break;
                case SETTINGSFIELD_VIEWRELOCINTERNALBASKET:
                    if( $callswitch ) switchViewRelocInternalBasket();
                    break;
                case SETTINGSFIELD_VIEWRELOCINTERMEDIATEBASKET:
                    if( $callswitch ) switchViewRelocIntermediateBasket();
                    break;
                case SETTINGSFIELD_VIEWRELOCPROVIDERBASKET:
                    if( $callswitch ) switchViewRelocProviderBasket();
                    break;

				case SETTINGSFIELD_ALLSERVICESATONCE:
                    $('#waitAjax').hide();
					if( $callswitch ) break;
					break;
			}
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert( 'Error' );
		}
	});
}

$('#all_in_once').on('click', function() {
	// If checked, disabled select list and ask server to copy all configuration same as current one; otherwise enable select list
	// In both case, ask server to keep checkbox state in mind

	if( $('#all_in_once').is(':checked') ){
		$('#serviceConfig').prop( 'disabled', true );
		$('#serviceConfig').css({ 'background-color': '#DDD' });
		$('#waitAjax').show();
		ajax_updatefield( SETTINGSFIELD_ALLSERVICESATONCE, 1, false );

	} else {
        $('#serviceConfig').prop( 'disabled', false );
        $('#serviceConfig').css({ 'background-color': '#FFF' });
        $('#waitAjax').show();
        ajax_updatefield( SETTINGSFIELD_ALLSERVICESATONCE, 0, false );

	}
});
