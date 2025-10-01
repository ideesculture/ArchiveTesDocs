var $services = [];
var $legalentities = [];
var $budgetcodes = [];
var $providers = [];
var $localizations = []
var $_translations = null;

var SERVICE_ID = 0;
var SERVICE_NAME = 1
var SERVICE_LEGALENTITIES_IDX = 2;
var SERVICE_BUDGETCODES_IDX = 3;
var SERVICE_PROVIDERS_IDX = 4;
var LEGALENTITY_ID = 0;
var LEGALENTITY_NAME = 1
var BUDGETCODE_ID = 0;
var BUDGETCODE_NAME = 1;
var PROVIDER_ID = 0;
var PROVIDER_NAME = 1;
var LOCALIZATION_ID = 0;
var LOCALIZATION_NAME = 1;

var defData = null;
var chart = null;
var _currentType = 'bar';

$(document).ready(function(){
	$_translations = JSON.parse( window.IDP_CONST.bs_translations );

	// Ask datas for select box from server
	$.ajax({
		type: "POST",
		url: window.JSON_URLS.bs_idp_archive_form_initlists,
		data: null,
		cache: false,
		success: updateLists,
		error: function (xhr, ajaxOptions, thrownError) {
			$('#ajaxError').html( $_translations[21] + ' [#12001]' );
			$('#ajaxError').removeClass('hidden');
		}
	});


	// Set initial parameters
	$parameters = {
		begin: -1,	// now
		begintype: 1,
		length: 12,
		budgetcode: -1,
		legalentity: -1,
		where: -1, // all
		provider: -1,
		service: -1,
		contain: -1,
		move: 1,
        localization: -1
	}

	// Ajax them to server
	$.ajax({
		type: "GET",
		url: window.JSON_URLS.bs_idp_statistics_askdatas,
		data: $parameters,
		cache: false,
		success: updateChart,
		error: function (xhr, ajaxOptions, thrownError) {
			$('#ajaxError').html( $_translations[21] + ' [#12002]' );
			$('#ajaxError').removeClass('hidden');
		}
	});


	$('#graphictype').change(function(){
		onChangeGraphictype();
	});

	$('#move').change( function(){
		if( isDateValid( $('#begin').val() )){
			onParametersChange();
			$('#divInputBegin').removeClass( 'has-error' );
		} else
			$('#divInputBegin').addClass( 'has-error' );
	});
	$('#service').change( function(){
		if( isDateValid( $('#begin').val() )){
			onParametersChange();
			$('#divInputBegin').removeClass( 'has-error' );
		} else
			$('#divInputBegin').addClass( 'has-error' );
	});
	$('#provider').change( function(){
		if( isDateValid( $('#begin').val() )){
			onParametersChange();
			$('#divInputBegin').removeClass( 'has-error' );
		} else
			$('#divInputBegin').addClass( 'has-error' );
	});
    $('#localization').change( function(){
        if( isDateValid( $('#begin').val() )){
            onParametersChange();
            $('#divInputBegin').removeClass( 'has-error' );
        } else
            $('#divInputBegin').addClass( 'has-error' );
    });
	$('#contain').change( function(){
		if( isDateValid( $('#begin').val() )){
			onParametersChange();
			$('#divInputBegin').removeClass( 'has-error' );
		} else
			$('#divInputBegin').addClass( 'has-error' );
	})
	$('#budgetcode').change( function(){
		if( isDateValid( $('#begin').val() )){
			onParametersChange();
			$('#divInputBegin').removeClass( 'has-error' );
		} else
			$('#divInputBegin').addClass( 'has-error' );
	})
	$('#legalentity').change( function(){
		if( isDateValid( $('#begin').val() )){
			onParametersChange();
			$('#divInputBegin').removeClass( 'has-error' );
		} else
			$('#divInputBegin').addClass( 'has-error' );
	})
	$('#where').change( function(){
		if( isDateValid( $('#begin').val() )){
			onParametersChange();
			$('#divInputBegin').removeClass( 'has-error' );
		} else
			$('#divInputBegin').addClass( 'has-error' );
	})
	$('#begintype').change( function(){
		if( isDateValid( $('#begin').val() ) ){
			onParametersChange();
			$('#divInputBegin').removeClass( 'has-error' );
		} else
			$('#divInputBegin').addClass( 'has-error' );
	})
	$('#begin').focusout( function(){
		if( isDateValid( $('#begin').val() ) ){
			onParametersChange();
			$('#divInputBegin').removeClass( 'has-error' );
		}
		else
			$('#divInputBegin').addClass( 'has-error' );
	})
	$('#length').focusout( function(){
		if( isDateValid( $('#begin').val() )){
			$length = parseInt( $('#length').val() );
			if( $length < 1 || $length > 24 )
			$length = 12;
			$('#length').val( $length );
			onParametersChange();
			$('#divInputBegin').removeClass( 'has-error' );
		} else
			$('#divInputBegin').addClass( 'has-error' );
	})

	// Add date change action

	$('#btnMainView').click( function(){
		window.location.replace( window.JSON_URLS.bs_idp_statistics_mainview );
	})
});

function isDateValid( $date_str ){
	// Format expected mm/yy (so 5 characters) or yyyy (so 4 characters)
	if( $date_str.length != 4 && $date_str.length != 5 && $date_str.length != 0 )
		return false;

	if( $('#begintype option:selected').val() == 1 ){
		if( $date_str.length == 0 )
			return true;
		// so 2 blocks or empty
		$date_split = $date_str.split('/');
		if( $date_split.length != 2 )
			return false;
		// with numbers in each ones
		if( isNaN(parseInt($date_split[0]))||isNaN(parseInt($date_split[1])) )
			return false;
		// month could only be 1-12
		if( parseInt($date_split[0])<1 || parseInt($date_split[0])>12 )
			return false;

		return true;
	}
	if( $('#begintype option:selected').val() == 2 ){
		if( $date_str.length == 0 )
			return true;
		$date_split = $date_str.split('/');
		if( $date_split.length > 1 )
			return false;
		// only 1 block with number
		if( isNaN(parseInt($date_str)) )
			return false;

		return true;
	}
	return false;
}
function decodeBeginDate( $date_str ){
	// Format expected mm/yy (so 5 characters) or yyyy (so 4 characters)
	if( $date_str.length != 4 && $date_str.length != 5 && $date_str.length != 0 )
		return -1;

	if( $('#begintype option:selected').val() == 1 ){
		if( $date_str.length == 0 ){
			var today = new Date();
			var mm = today.getMonth()+1; //January is 0!
			var yy = today.getYear() - 100;

			return yy*100 + mm;
		}
		// so 2 blocks mm/yy
		$date_split = $date_str.split('/');
		if( $date_split.length != 2 )
			return -1;
		// with numbers in each ones
		if( isNaN(parseInt($date_split[0]))||isNaN(parseInt($date_split[1])) )
			return -1;
		// month could only be 1-12
		if( parseInt($date_split[0])<1 || parseInt($date_split[0])>12 )
			return -1;

		return parseInt($date_split[0]) + 100*parseInt($date_split[1]);
	}
	if( $('#begintype option:selected').val() == 2 ){
		if( $date_str.length == 0 )
			return -1;
		$date_split = $date_str.split('/');
		if( $date_split.length > 1 )
			return -1;

		// only 1 block with number
		if( isNaN(parseInt($date_str)) )
			return -1;

		return parseInt($date_str);
	}
	return -1;

}

function onParametersChange() {
	// Set new parameters$
	$parameters = {
		begin: ($('#begin').val()==''?-1:decodeBeginDate($('#begin').val())),	// now
		begintype: $('#begintype option:selected').val(),
		length: $('#length').val(),
		budgetcode: $('#budgetcode option:selected').val(),
		legalentity: $('#legalentity option:selected').val(),
		where: $('#where option:selected').val(), // all
		provider: $('#provider option:selected').val(),
		service: $('#service option:selected').val(),
		contain: $('#contain option:selected').val(),
		move: $('#move option:selected').val(),
        localization: $('#localization option:selected').val()
	}

	// Ajax them to server
	$.ajax({
		type: "GET",
		url: window.JSON_URLS.bs_idp_statistics_askdatas,
		data: $parameters,
		cache: false,
		success: updateChart,
		error: function (xhr, ajaxOptions, thrownError) {
			$('#ajaxError').html( $_translations[21] + ' [#12003]' );
			$('#ajaxError').removeClass('hidden');
		}
	});

}

function updateChartGraphic( $data, $type ){

	$('#divStatisticsChart').html( '' );
	$x = $_translations[23];
	if( $('#begintype option:selected').val() == 2 )
		$x = $_translations[28];

	chart = new tauCharts.Chart({
		guide: {
			showGridLines: 'xy',
			y: {
				label: {
					text: $_translations[22],
					padding: 50
				}
			},
			x: {
				label: $x
			}
		},
		data: $data,
		type: $type,
		x: 'date',
		y: 'quantity',
		color: 'type',
		plugins: [
		tauCharts.api.plugins.get('legend')()
		]
	});

	chart.renderTo( '#divStatisticsChart');
}

function setServiceSelectList( ){
	$serviceOptions = "<option value=\"-1\" selected=\"selected\" data=\"-1\" >"+$_translations[11]+"</option>";
	$i = 0;
	$services.forEach(function($serviceLine){
		$serviceOptions += "<option value=\"" + $serviceLine[SERVICE_ID] + "\" data=\"" + $i + "\" >" + $serviceLine[SERVICE_NAME] + "</option> ";
		$i = $i + 1;
	});
	$('#service').attr('disabled', false);
	$('#service').html( $serviceOptions );
}

function setProviderSelectList( ){

	$partialProviders = "";

	$partialProviders = "<option value=\"-1\" selected=\"selected\" data=\"-1\" >"+$_translations[11]+"</option>";

	$providers.forEach(function($providerLine){
		$partialProviders += "<option value=\"" + $providerLine[PROVIDER_ID] + "\" >" + $providerLine[PROVIDER_NAME] + "</option> ";
	});
	$('#provider').attr('disabled', false);
	$("#provider").html($partialProviders);
}

function setLocalizationSelectList( ){

    $partialLocalizations = "";
    $partialLocalizations = "<option value=\"-1\" selected=\"selected\" data=\"-1\" >"+$_translations[11]+"</option>";

    $localizations.forEach(function($localizationLine){
        $partialLocalizations += "<option value=\"" + $localizationLine[LOCALIZATION_ID] + "\" >"
            + $localizationLine[LOCALIZATION_NAME] + "</option> ";
    });
    $('#localization').attr('disabled', false);
    $("#localization").html($partialLocalizations);
}


function setLegalEntitySelectList( ){

	$partialLegalEntities = "";
	$partialLegalEntities = "<option value=\"-1\" selected=\"selected\" data=\"-1\" >"+$_translations[11]+"</option>";

	$legalentities.forEach(function($legalentityLine){
		$partialLegalEntities += "<option value=\"" + $legalentityLine[LEGALENTITY_ID] +  "\" >" + $legalentityLine[LEGALENTITY_NAME] + "</option> ";
	});
	$('#legalentity').attr('disabled', false);
	$("#legalentity").html($partialLegalEntities);
}

function setBudgetCodeSelectList( ){

	$partialBudgetCodes = "";
	$partialBudgetCodes = "<option value=\"-1\" selected=\"selected\" data=\"-1\" >"+$_translations[11]+"</option>";

	$budgetcodes.forEach(function($budgetcodeLine){
		$partialBudgetCodes += "<option value=\"" + $budgetcodeLine[BUDGETCODE_ID] + "\" >" + $budgetcodeLine[BUDGETCODE_NAME] + "</option> ";
	});
	$('#budgetcode').attr('disabled', false);
	$("#budgetcode").html($partialBudgetCodes);
}

function updateLists( data ){
	if( data.length == 9 ) { // we get all lists
		$services = data[0];
		$legalentities = data[1];
		$budgetcodes = data[6];
		$providers = data[7];
        $localizations = data[8];

		setServiceSelectList();
		setProviderSelectList();
        setLocalizationSelectList();
		setLegalEntitySelectList();
		setBudgetCodeSelectList();

		return true;
	}

	alert( $_translations[21] + ' [#12004]' );
	return false;
}

function updateChart( data ){
	defData = data;
	updateChartGraphic( defData, _currentType );
}

function onChangeGraphictype() {
	if( $('#graphictype option:selected').val() =='0' ){
		_currentType = 'bar';
	}
	if( $('#graphictype option:selected').val() =='1' ){
		_currentType = 'line';
	}
	if( $('#graphictype option:selected').val() =='2' ){
		_currentType = 'scatterplot';
	}

	updateChartGraphic( defData, _currentType );
}