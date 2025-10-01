var role_id = window.IDP_DATA.idp_role_id;
var all_rights = [];

$(document).ready(function(){
	$("#waitAjax").show();
});


$(function(){
	$('#RightsListTable')
	.on('check.bs.table', function( e, row ){
		setRightSelected( row );
	})
	.on('uncheck.bs.table', function( e, row ){
		unsetRightSelected( row );
	})
	.on('check-all.bs.table', function( e ){
		setAllRightSelected( );
	})
	.on('uncheck-all.bs.table', function( e ){
		unsetAllRightSelected( );
	})

	.on('load-success.bs.table', function( e, data ){
		loadRoleRights( data );
	})
});

function setRightSelected( row ){
	$("#waitAjax").show();
	$.ajax({
		type: "POST",
		url: window.JSON_URLS.bs_core_admin_json_set_roleright,
		data: { roleId: role_id, rightId: row['id'] },
		cache: false,
		success: function( data, status ){
			$("#waitAjax").hide();
		},
		error: function( xhr, ajaxOptions, throwError ){
			$('#RightsListTable').bootstrapTable('uncheck', all_rights.indexOf( row['id'] ) );
			alert( "Une erreur est survenue, merci de ré-essayer ultérieurement !");
			$("#waitAjax").hide();
		}
	});
}

function unsetRightSelected( row ){
	$("#waitAjax").show();
	$.ajax({
		type: "POST",
		url: window.JSON_URLS.bs_core_admin_json_unset_roleright,
		data: { roleId: role_id, rightId: row['id'] },
		cache: false,
		success: function( data, status ){
			$("#waitAjax").hide();
		},
		error: function( xhr, ajaxOptions, throwError ){
			$('#RightsListTable').bootstrapTable('check', all_rights.indexOf( row['id'] ) );
			alert( "Une erreur est survenue, merci de ré-essayer ultérieurement !");
			$("#waitAjax").hide();
		}
	});
}

function setAllRightSelected( ){
	$("#waitAjax").show();
	$.ajax({
		type: "POST",
		url: window.JSON_URLS.bs_core_admin_json_set_roleright,
		data: { roleId: role_id, rightId: -1 },
		cache: false,
		success: function( data, status ){
			$("#waitAjax").hide();
		},
		error: function( xhr, ajaxOptions, throwError ){
			alert( "Une erreur est survenue, merci de ré-essayer ultérieurement !");
			$("#waitAjax").hide();
			location.reload();
		}
	});
}

function unsetAllRightSelected( ){
	$("#waitAjax").show();
	$.ajax({
		type: "POST",
		url: window.JSON_URLS.bs_core_admin_json_unset_roleright,
		data: { roleId: role_id, rightId: -1 },
		cache: false,
		success: function( data, status ){
			$("#waitAjax").hide();
		},
		error: function( xhr, ajaxOptions, throwError ){
			alert( "Une erreur est survenue, merci de ré-essayer ultérieurement !");
			$("#waitAjax").hide();
			location.reload();
		}
	});
}

function loadRoleRights( $data ){
	// make an array of all rights Ids
	for (index = 0; index < $data.length; ++index)
	all_rights[index] = parseInt($data[index]['id']);

	// Get rights of user
	$.ajax({
		type: "POST",
		url: window.JSON_URLS.bs_core_admin_json_get_rolerights_list,
		data: { roleId: role_id },
		cache: false,
		success: updateRightList,
		error: function (xhr, ajaxOptions, thrownError) {
			alert( "Une erreur est survenue lors de l'initialisation des paramètres de saisie, merci de ré-essayer ultérieurement !");
		}
	});
}

function updateRightList( $data ){
	// Check all rights of role received
	for (index = 0; index < $data.length; ++index) {
		$rightIdCurrent = $data[index]['id'];
		$result = all_rights.indexOf( parseInt( $rightIdCurrent ));
		if( $result >= 0 ){
			$('#RightsListTable').bootstrapTable('check', $result );
		}
	}

	$("#waitAjax").hide();
}

