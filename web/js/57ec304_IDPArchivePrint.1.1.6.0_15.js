
$('#divPrint').click(function(){
    var $params = new Array();
    $params['id'] = $('#frm_id').val();
    get(window.JSON_URLS.bs_idp_archive_print_sheet, $params, true );
});