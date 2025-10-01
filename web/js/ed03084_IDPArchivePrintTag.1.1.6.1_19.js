// Multiple Tags Window Management
$('#divPrintTags').click( function(){
    // Activate only if one check is present
    var $selection = $('#listsearchTable').bootstrapTable('getSelections');

    if( $selection.length > 0 )
        $('#modalTagsChoice').modal( 'show' );
});

$('#btnTags1').click( function() {
    var $params = new Array();
    $params['ids'] = getAllWantedIds();
    $params['position'] = 1;

    $('#modalTagChoice').modal( 'hide' );
    post(window.JSON_URLS.bs_idp_archive_print_tags, $params, true );
});

$('#btnTags2').click( function() {
    var $params = new Array();
    $params['ids'] = getAllWantedIds();
    $params['position'] = 2;

    $('#modalTagChoice').modal( 'hide' );
    post(window.JSON_URLS.bs_idp_archive_print_tags, $params, true );
});

$('#btnTags3').click( function() {
    var $params = new Array();
    $params['ids'] = getAllWantedIds();
    $params['position'] = 3;

    $('#modalTagChoice').modal( 'hide' );
    post(window.JSON_URLS.bs_idp_archive_print_tags, $params, true );
});

$('#btnTags4').click( function() {
    var $params = new Array();
    $params['ids'] = getAllWantedIds();
    $params['position'] = 4;

    $('#modalTagChoice').modal( 'hide' );
    post(window.JSON_URLS.bs_idp_archive_print_tags, $params, true );
});

// Single Tag Window Management
$('#divPrintTag').click( function() {
    $('#modalTagChoice').modal( 'show' );
});

$('#btnTag1').click( function() {
    var $params = new Array();
    $params['id'] = $('#frm_id').val();
    $params['position'] = 1;

    $('#modalTagChoice').modal( 'hide' );
    post(window.JSON_URLS.bs_idp_archive_print_tag, $params, true );
});

$('#btnTag2').click( function() {
    var $params = new Array();
    $params['id'] = $('#frm_id').val();
    $params['position'] = 2;

    $('#modalTagChoice').modal( 'hide' );
    post(window.JSON_URLS.bs_idp_archive_print_tag, $params, true );
});

$('#btnTag3').click( function() {
    var $params = new Array();
    $params['id'] = $('#frm_id').val();
    $params['position'] = 3;

    $('#modalTagChoice').modal( 'hide' );
    post(window.JSON_URLS.bs_idp_archive_print_tag, $params, true );
});

$('#btnTag4').click( function() {
    var $params = new Array();
    $params['id'] = $('#frm_id').val();
    $params['position'] = 4;

    $('#modalTagChoice').modal( 'hide' );
    post(window.JSON_URLS.bs_idp_archive_print_tag, $params, true );
});