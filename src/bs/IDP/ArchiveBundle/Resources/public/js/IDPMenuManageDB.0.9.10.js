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
