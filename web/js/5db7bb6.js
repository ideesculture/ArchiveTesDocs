var $roleID = 0;
var $_translations = null;

function actionFormatter( value, row, index ){
	return [
	'<a class="edit ml10" href="javascript:void(0)" title="'+$_translations[12]+'">',
	'<i class="fal fa-edit"></i>',
	'</a>',
	'<a class="remove ml10" href="javascript:void(0)" title="'+$_translations[13]+'">',
	'<i class="far fa-times"></i>',
	'</a>',
	'<a class="right ml10" href="javascript:void(0)" title="'+$_translations[14]+'">',
	'<i class="fal fa-cog"></i>',
	'</a>'
	].join('');
}

window.actionEvents = {
	'click .edit': function (e, value, row, index){
		var formModify = document.createElement("form");
		formModify.setAttribute( "method", "POST" );
		formModify.setAttribute( "action", window.JSON_URLS.bs_core_roles_admin_modify);
		var hiddenField = document.createElement("input");
		hiddenField.setAttribute("type", "hidden");
		hiddenField.setAttribute("name", "roleID");
		hiddenField.setAttribute("value", row['id'] );
		formModify.appendChild( hiddenField );
		document.body.appendChild( formModify );
		formModify.submit();
	},
	'click .remove': function (e, value, row, index){
		$('#SuppressModalText').html( $_translations[4]+' <b>' + row['name'] + '</b> ?' );
		$roleID = row['id'];
		$('#SuppressModal').modal('show');
	},
	'click .right': function( e, value, row, index){
		var formRight = document.createElement("form");
		formRight.setAttribute( "method", "POST");
		formRight.setAttribute( "action", window.JSON_URLS.bs_core_roles_admin_finetune);
		var hiddenField = document.createElement('input');
		hiddenField.setAttribute("type", "hidden");
		hiddenField.setAttribute("name", "roleID");
		hiddenField.setAttribute("value", row['id']);
		formRight.appendChild( hiddenField );
		document.body.appendChild( formRight );
		formRight.submit();
	}
};

function onClickBtnSuppressModalConfirm(){
	$dataStr = "id=" + $roleID;
	$.ajax({
		type: "POST",
		url: window.JSON_URLS.bs_core_admin_json_delete_role,
		data: $dataStr,
		cache: false,
		success: function($response) {
			ids = [$roleID];
			$('#RolesListTable').bootstrapTable('remove', { field: 'id', values: ids });
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert( $_translations[15] );
		}
	});

	return true;
}

$(document).ready(function(){
	$_translations = JSON.parse( window.IDP_CONST.bs_translations );

	$('#btnSuppressModalConfirm').click( function(){
		onClickBtnSuppressModalConfirm();
		$('#SuppressModal').modal('hide');
		return true;
	})
});