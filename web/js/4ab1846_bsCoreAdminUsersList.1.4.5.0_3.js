var $userID = 0;
$_translations = null;
var $_currentPage = PAGE_BDD_USERS;
var $_lastPageSize = 0;

function actionFormatter( value, row, index ){
	if( row['id'] != window.IDP_CONST.bs_currentuserid ) { // Cannot edit, remove or grant rights for currentuser
        return [
            '<a class="edit ml10" href="javascript:void(0)" title="' + $_translations[12] + '">',
            '<i class="fal fa-edit"></i>',
            '</a>',
            '<a class="remove ml10" href="javascript:void(0)" title="' + $_translations[13] + '">',
            '<i class="far fa-times"></i>',
            '</a>',
            '<a class="right ml10" href="javascript:void(0)" title="' + $_translations[14] + '">',
            '<i class="fal fa-cog"></i>',
            '</a>'
        ].join('');
    }
}

window.actionEvents = {
	'click .edit': function (e, value, row, index){
		var formModify = document.createElement("form");
		formModify.setAttribute( "method", "POST" );
		formModify.setAttribute( "action", window.JSON_URLS.bs_core_users_admin_modify);
		var hiddenField = document.createElement("input");
		hiddenField.setAttribute("type", "hidden");
		hiddenField.setAttribute("name", "userID");
		hiddenField.setAttribute("value", row['id'] );
		formModify.appendChild( hiddenField );
		document.body.appendChild( formModify );
		formModify.submit();
	},
	'click .remove': function (e, value, row, index){
		$('#SuppressModalText').html( $_translations[4]+' <b>' + row['login'] + '</b> ?' );
		$userID = row['id'];
		$('#SuppressModal').modal('show');
	},
	'click .right': function( e, value, row, index){
		var formRight = document.createElement("form");
		formRight.setAttribute( "method", "POST");
		formRight.setAttribute( "action", window.JSON_URLS.bs_core_users_admin_finetune);
		var hiddenField = document.createElement('input');
		hiddenField.setAttribute("type", "hidden");
		hiddenField.setAttribute("name", "userID");
		hiddenField.setAttribute("value", row['id']);
		formRight.appendChild( hiddenField );
		document.body.appendChild( formRight );
		formRight.submit();
	}
};

function userstablestyle(row, index) {
    if( row['connected'] == true ){
    	$now = Math.floor(Date.now() / 1000); // php timestamp is in second, javascript now() is in millisecond
    	if( row['lastaction'] + window.IDP_CONST.sesslifetime < $now ){ // not really connected anymore
    		return { };
		} else {
            if ( row['phpsessid'] == window.IDP_CONST.curr_phpsessid ) {	// Connected but same session
                return { classes: 'success' };
            } else {							// Connected elsewhere
            	return { classes: 'danger' };
			}
        }
	}
    else
        return { };
}

function onClickBtnSuppressModalConfirm(){
    $('#waitAjax').show();
	$dataStr = "id=" + $userID;
	$.ajax({
		type: "GET",
		url: window.JSON_URLS.bs_core_admin_json_delete_user,
		data: $dataStr,
		cache: false,
		success: function($response) {
			ids = [$userID];
			$('#UsersListTable').bootstrapTable('remove', { field: 'id', values: ids });
            $('#waitAjax').hide();
		},
		error: function (xhr, ajaxOptions, thrownError) {
            $('#waitAjax').hide();
			bootbox.alert( {
                message: $_translations[15],
                className: "boxSysErrorOne"
            } );
		}
	});

	return true;
}

$(document).ready(function(){
	$_translations = JSON.parse( window.IDP_CONST.bs_translations );

    // Get back User Settings to init Main Tab
    $dataStr = "page=" + $_currentPage;
    $.ajax({
        type: "GET",
        url: window.JSON_URLS.bs_idp_backoffice_ajax_usersettings_get,
        data: $dataStr,
        cache: false,
        success: function($response) {
            $_lastPageSize = $response.data.userPageSettings.nb_row_per_page;
            initBDDTab( $('#UsersListTable'), $response.data, 0, 'asc'  );
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert( 'Error while retreiving user Settings !' );
        }
    });

    $('#btnSuppressModalConfirm').click( function(){
        $('#SuppressModal').modal('hide');
		onClickBtnSuppressModalConfirm();
		return true;
	})

	$('#UsersListTable')
        .on('check.bs.table', function( e, row ){
            toggleChangePass( row );
        })
        .on('uncheck.bs.table', function( e, row ){
            toggleChangePass( row );
        })
		.on('dbl-click-row.bs.table', function( e, row ){
			unlockUser( row );
		})
});

function toggleChangePass( row ) {
    $.ajax({
        type: "POST",
        url: window.JSON_URLS.bs_core_admin_json_toggle_changepass,
        data: { userId: row['id'] },
        cache: false,
        success: function (data, status) {
        },
        error: function (xhr, ajaxOptions, throwError) {
            alert("Une erreur est survenue, merci de ré-essayer ultérieurement !");
        }
    });
}

function unlockUser( row ){
    if( row['connected'] == true ){
        if( row['id'] == window.IDP_CONST.bs_currentuserid ){
            bootbox.alert( {
                message: 'Vous ne pouvez pas vous débloquer vous-même !',
                className: "boxErrorOne"
            } );
            return;
        }

        $message = "Etes-vous sûr de vouloir déconnecter l'utilisateur " + row['login'] + " ?";
        bootbox.confirm( {
			message: $message,
			buttons: {
				confirm: {label: 'Oui', className: 'btn-success'},
				cancel: {label: 'Non', className: 'btn-danger'}
			},
            className: "boxQuestionTwo",
			callback: function( result ) {
                if (result) {
                    // Ask server to unlock user
                    $.ajax({
                        type: "POST",
                        url: window.JSON_URLS.bs_core_admin_json_unlock_user,
                        data: {userId: row['id']},
                        cache: false,
                        success: function (data, status) {
                            // Refresh page to update status
                            $('#UsersListTable').bootstrapTable('refresh');
                            bootbox.alert( {
                                message: "Utilisateur " + row['login'] + " déconnecté !",
                                className: "boxInfoOne"
                            });
                        },
                        error: function (xhr, ajaxOptions, throwError) {
                            bootbox.alert( {
                                message: "Une erreur est survenue, merci de ré-essayer ultérieurement !",
                                className: "boxSysErrorOne"
                            });
                        }
                    });
                }
            }
        });
    }
    else
        return;
}
