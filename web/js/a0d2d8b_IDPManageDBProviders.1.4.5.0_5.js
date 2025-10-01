var LOCALIZATION_ID = 0;
var LOCALIZATION_NAME = 1;

var $providerID = 0;
var $_translations = null
var $_currentPage = PAGE_BDD_PROVIDERS;
var $_lastPageSize = 0;

function actionFormatter( value, row, index ){
    $pageOffset = ($('#ProvidersListTable').bootstrapTable('getOptions')['pageNumber']-1) * $('#ProvidersListTable').bootstrapTable('getOptions')['pageSize']+index;
    $sortASC = ($('#ProvidersListTable').bootstrapTable('getOptions')['sortOrder']=='asc')?'1':'0';
    $rightLink = window.ARCHIVIST_JSON_URLS.bs_idp_archivist_managedb_providers_finetune;
    $rightLink += '?providerId=' + row['id'];
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
	'<i class="fal fa-times"></i>',
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

        $('#viewInputModifyLocalization').removeClass('has-success');
        $('#viewInputModifyLocalization').removeClass('has-error');
        $('#viewInputModifyName').removeClass('has-success');
        $('#viewInputModifyName').removeClass('has-error');
		$('#frm_modify_name').val( row['longname'] );
        $('#frm_modify_localization option').removeAttr("selected");
        $('#frm_modify_localization option')
            .filter(function(i, e) { return $(e).text() == row['localization']})
            .prop('selected', true);
		$providerID = row['id'];
		$('#ModifyModal').modal( 'show' );

	},
	'click .remove': function (e, value, row, index){
		$('#SuppressModalText').html( $_translations[2]+' <b>' + row['longname'] + '</b> ?' );
		$providerID = row['id'];
		$('#SuppressModal').modal('show');
	}
};

function onClickBtnSuppressModalConfirm(){
	$dataStr = "id=" + $providerID;
	$url = window.ARCHIVIST_JSON_URLS.bs_idp_archivist_json_providers_delete;
	$.ajax({
		type: "GET",
		url: $url,
		data: $dataStr,
		cache: false,
		success: function($response) {
			$('#ProvidersListTable').bootstrapTable('refresh');
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert( $_translations[15] );
		}
	});

	return true;
}

function onClickBtnModifyModalConfirm(){
    // Clean integrity indication
    $('#viewInputModifyLocalization').removeClass('has-success');
    $('#viewInputModifyLocalization').removeClass('has-error');
    $('#viewInputModifyName').removeClass('has-success');
    $('#viewInputModifyName').removeClass('has-error');
    // Verify integrity
    if( $('#frm_modify_localization option:selected').val().trim().length <= 0 || $('#frm_modify_name').val().trim().length <= 0 ){
        if( $('#frm_modify_localization option:selected').val().trim().length <= 0 )
            $('#viewInputModifyLocalization').addClass('has-error');
        else
            $('#viewInputModifyLocalization').addClass('has-success');
        if( $('#frm_modify_name').val().trim().length <= 0 )
            $('#viewInputModifyName').addClass('has-error');
        else
            $('#viewInputModifyName').addClass('has-success');
        bootbox.alert( {
            message: 'Aucun des champs Compte prestataire et Localisation ne peut être vide !',
            className: "boxErrorOne"
        } );
        return false;
    }
    $dataStr = "id=" + $providerID + "&localization_id=" + $('#frm_modify_localization option:selected').val() + "&name=" + encodeURIComponent($('#frm_modify_name').val()) ;
	$url = window.ARCHIVIST_JSON_URLS.bs_idp_archivist_json_providers_modify;
	$.ajax({
		type: "GET",
		url: $url,
		data: $dataStr,
		cache: false,
		success: function( $response ){
			$('#ProvidersListTable').bootstrapTable( 'refresh' );
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
            initBDDTab( $('#ProvidersListTable'), $response.data, $_pageOffset, $_sortASC );
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
        if( onClickBtnModifyModalConfirm() )
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
        // Clean integrity indication
        $('#viewAddName').removeClass('has-success');
        $('#viewAddName').removeClass('has-error');
        $('#viewAddLocalization').removeClass('has-success');
        $('#viewAddLocalization').removeClass('has-error');
        // Verify integrity
        if( $('#frm_localization option:selected').val().trim().length <= 0 || $('#frm_name').val().trim().length <= 0 ){
            if( $('#frm_localization option:selected').val().trim().length <= 0 ) {
                popError( $('#viewAddLocalization'), 'La localisation associée au compte prestataire ne peut pas être vide.', 'top' );
            } else
                $('#viewAddLocalization').addClass('has-success');
            if( $('#frm_name').val().trim().length <= 0 ) {
                popError( $('#viewAddName'), 'Le nom du compte prestataire ne peut pas être vide.', 'top' );
            } else
                $('#viewAddName').addClass('has-success');
            return false;
        }

        $dataStr = "name=" + encodeURIComponent($('#frm_name').val()) + "&localization_id=" + $('#frm_localization option:selected').val();
        $pageNumber = $('#ProvidersListTable').bootstrapTable('getOptions')['pageNumber'];
        $url = window.ARCHIVIST_JSON_URLS.bs_idp_archivist_json_providers_add;
		$.ajax({
			type: "GET",
			url: $url,
			data: $dataStr,
			cache: false,
			success: function( $response ){
				$('#frm_name').val( '' ) ;
				$('#ProvidersListTable').bootstrapTable('refresh', {'pageNumber': $pageNumber});
			},
			error: function( xhr, ajaxOptions, thrownError ){
				$('#frm_name').val( '' ) ;
				alert( $_translations[15] );
			}
		});
		return true;
	});

    $.ajax({
        type: "POST",
        url: window.ARCHIVIST_JSON_URLS.bs_idp_archive_form_initlists,
        data: null,
        cache: false,
        success: updateLocalizationList,
        error: function (xhr, ajaxOptions, thrownError) {
            alert( 'Error' );
        }
    });
});

function updateLocalizationList( data ){
    if( data.length == 9 ) { // we get all lists
        var $localizations = data[8];

        var $partialLocalizations = "";

        $partialLocalizations ="<option value selected=\"selected\" ></option>";

        // Construct list of localizations choices based on service id
        $localizations.forEach(function($localizationLine){
            $partialLocalizations += "<option value=\"" + $localizationLine[LOCALIZATION_ID] + "\" >" + $localizationLine[LOCALIZATION_NAME] + "</option> ";
        });

        $("#frm_modify_localization").html($partialLocalizations);
        $('#frm_localization').html($partialLocalizations);

        return true;
    }
    return false;

}