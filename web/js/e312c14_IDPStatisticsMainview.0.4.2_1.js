var $services = [];
var $legalentities = [];
var $budgetcodes = [];
var $providers = [];
var $localizations = [];
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
var PROVIDER_LOCALIZATION_IDX = 2;
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
			$('#ajaxError').html( $_translations[21] + ' [#12005]' );
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
			$('#ajaxError').html( $_translations[21] + ' [#12006]');
			$('#ajaxError').removeClass('hidden');
		}
	});


	$('#graphictype').change(function(){
		onChangeGraphictype();
	});

	$('#move').change( function(){
		onParametersChange();
	});
	$('#service').change( function(){
		onParametersChange();
	});
	$('#localization').change( function(){
		onParametersChange();
	});
	$('#contain').change( function(){
		onParametersChange();
	})

	$('#btnDetailledView').click( function(){
		window.location.replace( window.JSON_URLS.bs_idp_statistics_detailledview );
	})
});

function onParametersChange() {
	// Set new parameters$
	$parameters = {
		begin: -1,	// now
		begintype: 1,
		length: 12,
		budgetcode: -1,
		legalentity: -1,
		where: -1, // all
		provider: -1,
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
			$('#ajaxError').html( $_translations[21] + ' [#12007]');
			$('#ajaxError').removeClass('hidden');
		}
	});

}

function updateChartGraphic( $data, $type ){

	$('#divStatisticsChart').html( '' );

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
				label: $_translations[23]
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

function updateLists( data ){
	if( data.length == 9 ) { // we get all lists
		$services = data[0];
		$legalentities = data[1];
		$budgetcodes = data[6];
		$providers = data[7];
        $localizations = data[8];

		setServiceSelectList();
        setLocalizationSelectList();

		return true;
	}

	alert( $_translations[21] + ' [#12008]' );
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