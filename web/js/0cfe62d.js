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

var provider_id = window.IDP_DATA.idp_provider_id;
var all_services = [];
var $_translations = null;

$(document).ready(function(){
	$_translations = JSON.parse( window.IDP_CONST.bs_translations );

	$("#waitAjax").show();
});


$(function(){
	$('#LinksListTable')
	.on('check.bs.table', function( e, row ){
		setLinkSelected( row );
	})
	.on('uncheck.bs.table', function( e, row ){
		unsetLinkSelected( row );
	})
	.on('check-all.bs.table', function( e ){
		setAllLinkSelected( );
	})
	.on('uncheck-all.bs.table', function( e ){
		unsetAllLinkSelected( );
	})
	.on('load-success.bs.table', function( e, data ){
		loadProviderLink( data );
	})
});

function setLinkSelected( row ){

	$("#waitAjax").show();
	$.ajax({
		type: "GET",
		url: window.ARCHIVIST_JSON_URLS.bs_idp_archivist_json_providers_links_set,
		data: { providerId: provider_id, serviceId: row['id'] },
		cache: false,
		success: function( data, status ){
			$("#waitAjax").hide();
		},
		error: function( xhr, ajaxOptions, throwError ){
			$('#LinksListTable').bootstrapTable('uncheck', all_services.indexOf( row['id'] ) );
			alert( $_translations[4] );
			$("#waitAjax").hide();
		}
	});

}

function unsetLinkSelected( row ){

	$("#waitAjax").show();
	$.ajax({
		type: "GET",
		url: window.ARCHIVIST_JSON_URLS.bs_idp_archivist_json_providers_links_unset,
		data: { providerId: provider_id, serviceId: row['id'] },
		cache: false,
		success: function( data, status ){
			$("#waitAjax").hide();
		},
		error: function( xhr, ajaxOptions, throwError ){
			$('#LinksListTable').bootstrapTable('check', all_services.indexOf( row['id'] ) );
			alert( $_translations[4] );
			$("#waitAjax").hide();
		}
	});

}

function setAllLinkSelected( ){

	$("#waitAjax").show();
	$.ajax({
		type: "GET",
		url: window.ARCHIVIST_JSON_URLS.bs_idp_archivist_json_providers_links_set,
		data: { providerId: provider_id, serviceId: -1 },
		cache: false,
		success: function( data, status ){
			$("#waitAjax").hide();
		},
		error: function( xhr, ajaxOptions, throwError ){
			alert( $_translations[4] );
			$("#waitAjax").hide();
			location.reload();
		}
	});

}

function unsetAllLinkSelected( ){

	$("#waitAjax").show();
	$.ajax({
		type: "GET",
		url: window.ARCHIVIST_JSON_URLS.bs_idp_archivist_json_providers_links_unset,
		data: { providerId: provider_id, serviceId: -1 },
		cache: false,
		success: function( data, status ){
			$("#waitAjax").hide();
		},
		error: function( xhr, ajaxOptions, throwError ){
			alert( $_translations[4] );
			$("#waitAjax").hide();
			location.reload();
		}
	});

}

function loadProviderLink( $data ){

	// make an array of all services Ids
	for (index = 0; index < $data.length; ++index)
	all_services[index] = parseInt($data[index]['id']);

	// Get links of legal entity
	$.ajax({
		type: "GET",
		url: window.ARCHIVIST_JSON_URLS.bs_idp_archivist_json_providers_links_list,
		data: { providerId: provider_id },
		cache: false,
		success: updateLinkList,
		error: function (xhr, ajaxOptions, thrownError) {
			alert( $_translations[5] );
		}
	});

}

function updateLinkList( $data ){

	// Check all services of legal entity received
	for (index = 0; index < $data.length; ++index) {
		$serviceIDCurrent = $data[index]['serviceID'];
		$result = all_services.indexOf( parseInt( $serviceIDCurrent ));
		if( $result >= 0 ){
			$('#LinksListTable').bootstrapTable('check', $result );
		}
	}

	$("#waitAjax").hide();

}

