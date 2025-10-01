var $budgetcodeID = 0;
var $_translations = null;
var $_currentPage = PAGE_BDD_ENTRY_BUDGET_CODES;
var $_lastPageSize = 0;

function actionFormatter( value, row, index ){
    $pageOffset = ($('#BudgetCodesListTable').bootstrapTable('getOptions')['pageNumber']-1) * $('#BudgetCodesListTable').bootstrapTable('getOptions')['pageSize']+index;
    $sortASC = ($('#BudgetCodesListTable').bootstrapTable('getOptions')['sortOrder']=='asc')?'1':'0';
    $rightLink = window.ARCHIVIST_JSON_URLS.bs_idp_archivist_managedb_input_budgetcodes_finetune;
    $rightLink += '?budgetCodeId=' + row['id'];
    $rightLink += '&pageOffset=' + $pageOffset;
    $rightLink += '&sortASC=' + $sortASC;

	if( row['cansuppress'] == true )
	return [
	'<a class="edit ml10" href="javascript:void(0)" title="'+$_translations[13]+'">',
	'<i class="fal fa-edit"></i>',
	'</a>',
	'<a class="right ml10" href="'+$rightLink+'" title="'+$_translations[16]+'">',
	'<i class="fal fa-cog"></i>',
	'</a>',
	'<a class="remove ml10" href="javascript:void(0)" title="'+$_translations[14]+'">',
	'<i class="far fa-times"></i>',
	'</a>'
	].join('');
	else
	return [
	'<a class="edit ml10" href="javascript:void(0)" title="'+$_translations[13]+'">',
	'<i class="fal fa-edit"></i>',
	'</a>',
	'<a class="right ml10" href="'+$rightLink+'" title="'+$_translations[16]+'">',
	'<i class="fal fa-cog"></i>',
	'</a>'
	].join('');
}

window.actionEvents = {
	'click .edit': function (e, value, row, index){
		$('#frm_modify_name').val( row['longname'] );
		$budgetcodeID = row['id'];
		$('#ModifyModal').modal( 'show' );

	},
	'click .remove': function (e, value, row, index){
		$('#SuppressModalText').html( $_translations[2]+' <b>' + row['longname'] + '</b> ?' );
		$budgetcodeID = row['id'];
		$('#SuppressModal').modal('show');
	}
};

function onClickBtnSuppressModalConfirm(){
	$dataStr = "id=" + $budgetcodeID;
	$url = window.ARCHIVIST_JSON_URLS.bs_idp_archivist_json_budgetcodes_delete;
	$.ajax({
		type: "GET",
		url: $url,
		data: $dataStr,
		cache: false,
		success: function($response) {
			$('#BudgetCodesListTable').bootstrapTable('refresh');
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert( $_translations[15] );
		}
	});

	return true;
}

function onClickBtnModifyModalConfirm(){
	$dataStr = "id=" + $budgetcodeID + "&name=" + encodeURIComponent($('#frm_modify_name').val());
	$url = window.ARCHIVIST_JSON_URLS.bs_idp_archivist_json_budgetcodes_modify;
	$.ajax({
		type: "GET",
		url: $url,
		data: $dataStr,
		cache: false,
		success: function( $response ){
			$('#BudgetCodesListTable').bootstrapTable( 'refresh' );
		},
		error: function( xhr, ajaxOptions, thrownError){
			alert( $_translations[15] );
		}
	});
	return true;
}

$(document).ready(function(){
	$_translations = JSON.parse( window.IDP_CONST.bs_translations );
    $_pageOffset = window.IDP_CONST.page_offset;
    $_sortASC = window.IDP_CONST.page_sortASC == '1' ? 'asc' : 'desc';

    // Get back User Settings to init Main Tab
    $dataStr = "page=" + $_currentPage;
    $.ajax({
        type: "GET",
        url: window.ARCHIVIST_JSON_URLS.bs_idp_backoffice_ajax_usersettings_get,
        data: $dataStr,
        cache: false,
        success: function($response) {
            $_lastPageSize = $response.data.userPageSettings.nb_row_per_page;
            initBDDTab( $('#BudgetCodesListTable'), $response.data, $_pageOffset, $_sortASC );
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert( 'Error while retreiving user Settings !' );
        }
    });

    $('#btnSuppressModalConfirm').click( function(){
		onClickBtnSuppressModalConfirm();
		$('#SuppressModal').modal('hide');
		return true;
	})

	$('#btnModifyModalConfirm').click( function(){
		onClickBtnModifyModalConfirm();
		$('#ModifyModal').modal( 'hide' );
		return true;
	})

	$('#frm_modify_name').keydown(function (e){
		if(e.keyCode == 13){
			$('#btnModifyModalConfirm').click();
		}
	})

    $('#frm_name').keydown( function( event ){
        if( event.keyCode == 13 ){
            $('#btn_add').click();
        }
    });

    $('#frmAdd').on('submit', function( event ){	// catch auto-submit with enter on some browser, and do nothing
        return false;
    });

    $('#btn_add').click( function(){
        if( $('#frm_name').val()==null || $('#frm_name').val().trim().length <= 0 ){
            popError( $('#div_frm_name'), "Le nom du code budgétaire ne peut pas être vide.", 'top' );
            return false;
        } else {
            $dataStr = "name=" + encodeURIComponent($('#frm_name').val());
            $pageNumber = $('#BudgetCodesListTable').bootstrapTable('getOptions')['pageNumber'];
            $url = window.ARCHIVIST_JSON_URLS.bs_idp_archivist_json_budgetcodes_add;
            $.ajax({
                type: "GET",
                url: $url,
                data: $dataStr,
                cache: false,
                success: function ($response) {
                    $('#frm_name').val('');
                    $('#BudgetCodesListTable').bootstrapTable('refresh', {'pageNumber': $pageNumber});
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    $('#frm_name').val('');
                    alert($_translations[15]);
                }
            });
            return true;
        }
	});
});