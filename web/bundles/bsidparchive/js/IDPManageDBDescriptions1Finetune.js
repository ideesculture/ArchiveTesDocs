var description1_id = window.IDP_DATA.idp_description1_id;
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
		loadDescription1Link( data );
	})
});

function setLinkSelected( row ){

	$("#waitAjax").show();
	$.ajax({
		type: "GET",
		url: window.ARCHIVIST_JSON_URLS.bs_idp_archivist_json_descriptions1_links_set,
		data: { description1Id: description1_id, serviceId: row['id'] },
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
		url: window.ARCHIVIST_JSON_URLS.bs_idp_archivist_json_descriptions1_links_unset,
		data: { description1Id: description1_id, serviceId: row['id'] },
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
		url: window.ARCHIVIST_JSON_URLS.bs_idp_archivist_json_descriptions1_links_set,
		data: { description1Id: description1_id, serviceId: -1 },
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
		url: window.ARCHIVIST_JSON_URLS.bs_idp_archivist_json_descriptions1_links_unset,
		data: { description1Id: description1_id, serviceId: -1 },
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

function loadDescription1Link( $data ){

	// make an array of all services Ids
	for (index = 0; index < $data.length; ++index)
	all_services[index] = parseInt($data[index]['id']);

	// Get links of legal entity
	$.ajax({
		type: "GET",
		url: window.ARCHIVIST_JSON_URLS.bs_idp_archivist_json_descriptions1_links_list,
		data: { description1Id: description1_id },
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

