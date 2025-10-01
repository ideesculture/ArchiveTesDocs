var documenttype_id = window.IDP_DATA.idp_documenttype_id;
var all_documentnatures = [];
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
		loadDocumentTypeLink( data );
	})
});

function setLinkSelected( row ){

	$("#waitAjax").show();
	$.ajax({
		type: "GET",
		url: window.ARCHIVIST_JSON_URLS.bs_idp_archivist_json_documenttypes_links_set,
		data: { documentTypeId: documenttype_id, documentNatureId: row['id'] },
		cache: false,
		success: function( data, status ){
			$("#waitAjax").hide();
		},
		error: function( xhr, ajaxOptions, throwError ){
			$('#LinksListTable').bootstrapTable('uncheck', all_documentnatures.indexOf( row['id'] ) );
			alert( $_translations[4] );
			$("#waitAjax").hide();
		}
	});

}

function unsetLinkSelected( row ){

	$("#waitAjax").show();
	$.ajax({
		type: "GET",
		url: window.ARCHIVIST_JSON_URLS.bs_idp_archivist_json_documenttypes_links_unset,
		data: { documentTypeId: documenttype_id, documentNatureId: row['id'] },
		cache: false,
		success: function( data, status ){
			$("#waitAjax").hide();
		},
		error: function( xhr, ajaxOptions, throwError ){
			$('#LinksListTable').bootstrapTable('check', all_documentnatures.indexOf( row['id'] ) );
			alert( $_translations[4] );
			$("#waitAjax").hide();
		}
	});

}

function setAllLinkSelected( ){

	$("#waitAjax").show();
	$.ajax({
		type: "GET",
		url: window.ARCHIVIST_JSON_URLS.bs_idp_archivist_json_documenttypes_links_set,
		data: { documentTypeId: documenttype_id, documentNatureId: -1 },
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
		url: window.ARCHIVIST_JSON_URLS.bs_idp_archivist_json_documenttypes_links_unset,
		data: { documentTypeId: documenttype_id, documentNatureId: -1 },
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

function loadDocumentTypeLink( $data ){

	// make an array of all document natures Ids
	for (index = 0; index < $data.length; ++index)
	all_documentnatures[index] = parseInt($data[index]['id']);

	// Get links of Document type
	$.ajax({
		type: "GET",
		url: window.ARCHIVIST_JSON_URLS.bs_idp_archivist_json_documenttypes_links_list,
		data: { documentTypeId: documenttype_id },
		cache: false,
		success: updateLinkList,
		error: function (xhr, ajaxOptions, thrownError) {
			alert( $_translations[5] );
		}
	});

}

function updateLinkList( $data ){

	// Check all document natures of document type received
	for (index = 0; index < $data.length; ++index) {
		$documentnatureIDCurrent = $data[index]['documentNatureID'];
		$result = all_documentnatures.indexOf( parseInt( $documentnatureIDCurrent ));
		if( $result >= 0 ){
			$('#LinksListTable').bootstrapTable('check', $result );
		}
	}

	$("#waitAjax").hide();

}

