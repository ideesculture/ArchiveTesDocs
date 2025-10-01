// Function to send a post to server
// post('/contact/', {name: 'Johnny Bravo'});
function post(path, params, external) {
    var form = document.createElement("form");
    form.setAttribute("method", "post");
    form.setAttribute("action", path);
    if( external )
        form.setAttribute("target", "_blanc" );

    for(var key in params) {
        if(params.hasOwnProperty(key)) {
            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", key);
            hiddenField.setAttribute("value", params[key]);

            form.appendChild(hiddenField);
        }
    }
    document.body.appendChild(form);
    form.submit();
};
// Function to send a get to server
// get('/contact/', {name: 'Johnny Bravo'});
function get(path, params, external) {
    var form = document.createElement("form");
    form.setAttribute("method", "get");
    form.setAttribute("action", path);
    if( external )
        form.setAttribute("target", "_blanc" );

    for(var key in params) {
        if(params.hasOwnProperty(key)) {
            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", key);
            hiddenField.setAttribute("value", params[key]);

            form.appendChild(hiddenField);
        }
    }
    document.body.appendChild(form);
    form.submit();
};

function popError( $ancre, $message, $where ){
    $ancre.popover({trigger:'manual', placement: $where, content: $message });
    $ancre.popover('show').addClass('has-error');
    $ancre.addClass( 'has-error' );
    $('body').click(function(){
        $ancre.popover('destroy');
        $ancre.removeClass( 'has-error' );
    });
}



// COMMON SETTINGS Constants

var COMMON_SERVICE_SETTINGS_BUDGET_CODE         = 1;
var COMMON_SERVICE_SETTINGS_DOCUMENT_NATURE     = 2;
var COMMON_SERVICE_SETTINGS_DOCUMENT_TYPE       = 3;
var COMMON_SERVICE_SETTINGS_DESCRIPTION_1       = 4;
var COMMON_SERVICE_SETTINGS_DESCRIPTION_2       = 5;
var COMMON_SERVICE_SETTINGS_DOCUMENT_NUMBER     = 6;
var COMMON_SERVICE_SETTINGS_BOX_NUMBER          = 7;
var COMMON_SERVICE_SETTINGS_CONTAINER_NUMBER    = 8;
var COMMON_SERVICE_SETTINGS_PROVIDER            = 9;
var COMMON_SERVICE_SETTINGS_LIMITS_DATE         = 10;
var COMMON_SERVICE_SETTINGS_LIMITS_NUM          = 11;
var COMMON_SERVICE_SETTINGS_LIMITS_ALPHA        = 12;
var COMMON_SERVICE_SETTINGS_LIMITS_ALPHANUM     = 13;

// PAGEs Constants

var PAGE_TRANSFER                           = 1;
var PAGE_CONSULT                            = 2;
var PAGE_RETURN                             = 3;
var PAGE_EXIT                               = 4;
var PAGE_DELETE                             = 5;
var PAGE_RELOC                              = 6;
var PAGE_VALID_TRANSFER_PROVIDER            = 7;
var PAGE_VALID_TRANSFER_INTERMEDIATE        = 8;
var PAGE_VALID_TRANSFER_INTERNAL            = 9;
var PAGE_VALID_DELIVER_WITHOUT_PREPARATION  = 10;
var PAGE_VALID_DELIVER_WITH_PREPARATION     = 11;
var PAGE_VALID_RETURN                       = 12;
var PAGE_VALID_EXIT                         = 13;
var PAGE_VALID_DELETE                       = 14;
var PAGE_VALID_RELOC_PROVIDER               = 15;
var PAGE_VALID_RELOC_INTERMEDIATE           = 16;
var PAGE_VALID_RELOC_INTERNAL               = 17;
var PAGE_MANAGE_TRANSFER                    = 18;
var PAGE_MANAGE_DELIVER                     = 19;
var PAGE_MANAGE_RETURN                      = 20;
var PAGE_MANAGE_EXIT                        = 21;
var PAGE_MANAGE_DELETE                      = 22;
var PAGE_MANAGE_RELOC                       = 23;
var PAGE_CLOSE_TRANSFER_PROVIDER            = 24;
var PAGE_CLOSE_TRANSFER_INTERMEDIATE        = 25;
var PAGE_CLOSE_TRANSFER_INTERNAL            = 26;
var PAGE_CLOSE_DELIVER_WITHOUT_PREPARATION  = 27;
var PAGE_CLOSE_DELIVER_WITH_PREPARATION     = 28;
var PAGE_CLOSE_RETURN                       = 29;
var PAGE_CLOSE_EXIT                         = 30;
var PAGE_CLOSE_DELETE                       = 31;
var PAGE_CLOSE_RELOC_PROVIDER               = 32;
var PAGE_CLOSE_RELOC_INTERMEDIATE           = 33;
var PAGE_CLOSE_RELOC_INTERNAL               = 34;
var PAGE_UNLIMITED                          = 35;
var PAGE_BDD_ENTRY_SERVICES                 = 36;
var PAGE_BDD_ENTRY_LEGAL_ENTITIES           = 37;
var PAGE_BDD_ENTRY_BUDGET_CODES             = 38;
var PAGE_BDD_ENTRY_ACTIVITIES               = 39;
var PAGE_BDD_ENTRY_DOCUMENT_TYPES           = 40;
var PAGE_BDD_ENTRY_DESCRIPTIONS_1           = 41;
var PAGE_BDD_ENTRY_DESCRIPTIONS_2           = 42;
var PAGE_BDD_ENTRY_ADRESSES                 = 43;
var PAGE_BDD_ENTRY_LOCALIZATIONS            = 44;
var PAGE_BDD_USERS                          = 45;
var PAGE_BDD_PROVIDERS                      = 46;

// FIELDs Constants

var FIELD_SERVICE                 = 1;
var FIELD_ORDER_NUMBER            = 2;
var FIELD_LEGAL_ENTITY            = 3;
var FIELD_NAME                    = 4;
var FIELD_BUDGET_CODE             = 5;
var FIELD_DOCUMENT_NATURE         = 6;
var FIELD_DOCUMENT_TYPE           = 7;
var FIELD_DESCRIPTION_1           = 8;
var FIELD_DESCRIPTION_2           = 9;
var FIELD_DOCUMENT_NUMBER         = 10;
var FIELD_BOX_NUMBER              = 11;
var FIELD_CONTAINER_NUMBER        = 12;
var FIELD_PROVIDER                = 13;
var FIELD_STATUS                  = 14;
var FIELD_ID                      = 15;
var FIELD_ADMINLIST               = 16;
var FIELD_STATUS_CAPS             = 17;
var FIELD_AUTHORIZED              = 18;
var FIELD_LOCALIZATION            = 19;
var FIELD_LOCALIZATION_FREE       = 20;
var FIELD_LIMIT_DATE_MIN          = 21;
var FIELD_LIMIT_DATE_MAX          = 22;
var FIELD_LIMIT_NUM_MIN           = 23;
var FIELD_LIMIT_NUM_MAX           = 24;
var FIELD_LIMIT_ALPHA_MIN         = 25;
var FIELD_LIMIT_ALPHA_MAX         = 26;
var FIELD_LIMIT_ALPHANUM_MIN      = 27;
var FIELD_LIMIT_ALPHANUM_MAX      = 28;
var FIELD_CLOSURE_YEAR            = 29;
var FIELD_DESTRUCTION_YEAR        = 30;
var FIELD_STATUS_CODE             = 31;
var FIELD_MODIFIED_AT             = 32;
var FIELD_OLD_LOCALIZATION        = 33;
var FIELD_OLD_LOCALIZATION_FREE   = 34;
var FIELD_PROVIDER_ID             = 35;
var FIELD_PRECISION_DATE          = 36;
var FIELD_PRECISION_ADDRESS       = 37;
var FIELD_PRECISION_FLOOR         = 38;
var FIELD_PRECISION_OFFICE        = 39;
var FIELD_PRECISION_WHO           = 40;
var FIELD_PRECISION_COMMENT       = 41;

var USER_SETTINGS_MODIF_COLUMN_VISIBLE         = 1;
var USER_SETTINGS_MODIF_COLUMN_SORTED          = 2;
var USER_SETTINGS_MODIF_COLUMN_SORT_TYPE_ASC   = 3;

var USER_SETTINGS_MODIF_PAGE_NB_ROW_PER_PAGE = 1;
var USER_SETTINGS_MODIF_PAGE_ARRAY_TYPE_LIST = 2;

// BZ#38 disabling field reloc status
var RELOC_FIELD_DISABLED_STATUS = new Array(
    'CRLPDAI', 'GRLPDAI', 'CRLPDI', 'CONRIDISP',
    'CRLPDAINT', 'GRLPDAINT', 'CRLPDINT', 'CONRINTDISP',
    'CRLPCAI', 'CRLPCI', 'CONRICONP',
    'CRLPCAINT', 'CRLPCINT', 'CONRINTCONP',
    'CRAPCONRIDISP', 'GRAPCONRIDISP', 'CRTPCONRIDISP',
    'CRAPCONRINTDISP', 'GRAPCONRINTDISP', 'CRTPCONRINTDISP',
    'CRAPCONRICONP', 'GRAPCONRICONP', 'CRTPCONRICONP',
    'CRAPCONRINTCONP', 'GRAPCONRINTCONP', 'CRTPCONRINTCONP'
);

// Password complexity constants:
var PWD_CPXTY_CHARS_LOWER = 1;
var PWD_CPXTY_CHARS_UPPER = 2;
var PWD_CPXTY_CHARS_SPECIAL = 4;
var PWD_CPXTY_NUMBERS = 8;


var UASTATE_NOTHING = -1;
var UASTATE_MANAGEUSER = 0;
var UASTATE_MANAGEPROVIDER = 1;
var UASTATE_MANAGECLOSE = 2;

var UAWHAT_TRANSFER = 0;
var UAWHAT_CONSULT = 1;
var UAWHAT_RETURN = 2;
var UAWHAT_EXIT = 3;
var UAWHAT_DESTROY = 4;
var UAWHAT_RELOC = 5;

var UAWHERE_PROVIDER = 0;
var UAWHERE_INTERMEDIATE = 1;
var UAWHERE_INTERNAL = 2;

var UAWHERE_TRANSFER = 0;
var UAWHERE_CONSULT = 1;

var UAWITH_CONTAINER = 0;
var UAWITH_BOX = 1;
var UAWITH_FILE = 2;
var UAWITH_NOTHING = 3;

var UAHOW_WITHOUTPREPARE = 0;
var UAHOW_WITHPREPARE = 1;

// FCT Constant for Export
var FCT_TRANSFER                = 1;
var FCT_CONSULT                 = 2;
var FCT_RETURN                  = 3;
var FCT_EXIT                    = 4;
var FCT_DELETE                  = 5;
var FCT_RELOC                   = 28;

var FCT_ARCHIVIST               = 6;
var $_currentPage = 0;
var $_currentPageButtons = 0;

var $_lastPageOffset = 0;
var $_lastPageSize = 10;
var $_lastSortColumn = 'id';
var $_lastSortOrder = 'asc';

var $special = '';

var $_table = null;


function saveUserSettings_Sort( $page, $name, $order ){
    $_lastSortOrder = $order;
    if( $_lastSortColumn != $name ) {
        $_lastSortColumn = $name;
        // Save new Column sort
        $dataStr = 'page=' + $page + '&column=' + $name + '&field=' + USER_SETTINGS_MODIF_COLUMN_SORTED + '&value=' + true + '&onlyone=' + true;
        $.ajax({
            type: "GET",
            url: window.JSON_URLS.bs_idp_backoffice_usersettings_modify_column,
            data: $dataStr,
            cache: false,
            success: function ($response) {
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert('[E01001] Error while saving user Settings !');
            }
        });
    }
    // Save new Column sort order
    $dataStr = 'page=' + $page + '&column=' + $name + '&field=' + USER_SETTINGS_MODIF_COLUMN_SORT_TYPE_ASC + '&value=' + ($order=='asc');
    $.ajax({
        type: "GET",
        url: window.JSON_URLS.bs_idp_backoffice_usersettings_modify_column,
        data: $dataStr,
        cache: false,
        success: function( $response ) {
            doSearch();
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert( '[E01002] Error while saving user Settings !' );
        }
    });
}

function saveUserSettings_ColumnVisible( $page, $column, $visible ){
    $dataStr = 'page=' + $page + '&column=' + $column + '&field=' + USER_SETTINGS_MODIF_COLUMN_VISIBLE + '&value=' + $visible;
    $.ajax({
        type: "GET",
        url: window.JSON_URLS.bs_idp_backoffice_usersettings_modify_column,
        data: $dataStr,
        cache: false,
        success: function ($response) {
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert('[E01005] Error while saving user Settings ! '.json_encode(thrownError));
        }
    });
}

function saveUserSettings_PageSize( $page, $size ){
    $_lastPageSize = $size;
    $dataStr = 'page=' + $page + '&field=' + USER_SETTINGS_MODIF_PAGE_NB_ROW_PER_PAGE + '&value=' + $size;
    $.ajax({
        type: "GET",
        url: window.JSON_URLS.bs_idp_backoffice_usersettings_modify_page,
        data: $dataStr,
        cache: false,
        success: function( $response ) {
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert( '[E01003] Error while saving user Settings !' );
        }
    });
}

function saveUserSettings_ViewMode( $page, $cardview ){
    $dataStr = 'page=' + $page + '&field=' + USER_SETTINGS_MODIF_PAGE_ARRAY_TYPE_LIST + '&value=' + !$cardview;
    $.ajax({
        type: "GET",
        url: window.JSON_URLS.bs_idp_backoffice_usersettings_modify_page,
        data: $dataStr,
        cache: false,
        success: function( $response ) {},
        error: function( xhr, ajaxOptions, thrownError){
            alert( '[E01004] Error while saving user Settings !' );
        }
    })
}

function saveUserSettings_ColumnsOrder( $page, $columns ){

    $dataStr = 'page=' + $page + '&neworder=' + JSON.stringify($columns);
    $.ajax({
        type: "GET",
        url: window.JSON_URLS.bs_idp_backoffice_usersettings_modify_column_order,
        data: $dataStr,
        cache: false,
        success: function ($response) {
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert('[E01006] Error while saving user Settings ! '.json_encode(thrownError));
        }
    });
}

function initMainTabEvents ( $table, $caller ){
    $table.bootstrapTable()
//        .on('all.bs.table', function (e, name, args) {
//            console.log(name);
//        })
        .on('check.bs.table', function(element , row, checkbox ){
            mainTableCheckRow( checkbox ); })
        .on('check-all.bs.table', function( ){
            mainTableCheckAllRows( ); })
        .on('uncheck.bs.table', function( row, element, checkbox ){
            mainTableUncheckRow( checkbox ); })
        .on('uncheck-all.bs.table', function(rows){
            mainTableUncheckAllRows( ); })
        .on('dbl-click-row.bs.table', function( field, row, element ){ 
            mainTableDblClickRow( row ); })
        .on('sort.bs.table', function( target, name, order ){
            saveUserSettings_Sort( $_currentPage, name, order ); })
//        .on('page-change.bs.table', function( target, pageNo, pageSize ){
//            mainTablePageChange( pageNo, pageSize ); })
        .on('toggle.bs.table', function( target, cardview ){
            saveUserSettings_ViewMode( $_currentPage, cardview ); })
        .on('column-switch.bs.table', function( $target, field, checked ){
            saveUserSettings_ColumnVisible( $_currentPage, field, checked ); })
        .on('reset-view.bs.table', function( ){
            mainTableResetView( ); })
        .on('post-body.bs.table', function( target, data ){
            mainTablePostBody( data ); })
        .on('search.bs.table', function( $target, $text ){
            switch( $caller ){
                case 1: // Transfer
                case 2: // Consult
                case 3: // Return
                case 4: // Exit
                case 5: // Delete
                case 6: // Reloc
                    refreshView( $text );
                    break;
                case 7: // Validate
                case 8: // Manage
                case 9: // Close
                    $table.bootstrapTable( 'Refresh' );
                    break;
            }
            })
        .on('reorder-column.bs.table', function( $target, $columns ){
            saveUserSettings_ColumnsOrder( $_currentPage, $columns );
        })
    ;
}
function refreshView( $text ){
    // get text from search
    $special = $text;
    // BZ#39 : Libellé, N° d'ordre, N° de conteneur, N° de boite, N° de doc.,dossier
    // name, ordernumber, containernumber, boxnumber, documentnumber

    // Launch search (for consult ... reloc)
    doSearch( );
}

function initMainTabColumns ( $init, $table, $url, $userSettings, $tabletranslation, $commonSettings, $sidePagination, $method ){

    $searchColumns = [ 'name', 'ordernumber', 'containernumber', 'boxnumber', 'documentnumber' ];

    $columns = [ { field: 'state', checkbox: true , formatter: 'stateFormatter' } ];

    $userColumnsSettings = $userSettings['userColumnsSettings'];
    $columnsSettings = $userSettings['columns'];
    $sortName = null;
    $sortOrder = 'asc';

    $userColumnsSettings.forEach(function($element, $index, $array){

        $column_id = $element['column_id'];
        $columnSetting = $columnsSettings[$column_id];

        $view_by_config = $columnSetting['view_by_config'];
        $config_idx = $columnSetting['config_idx'];
        $viewable = true;
        $has_custom_name = false;
        $custom_name = null;
        if( $view_by_config ){
            $viewable = $commonSettings[$config_idx]['ACTIVATED'];
            $has_custom_name = $commonSettings[$config_idx]['HAS_CUSTOM_NAME'];
            $custom_name = $commonSettings[$config_idx]['CUSTOM_NAME'];
        }

        // B#24 Column only visible if user is in service which can see it
        // commonSettings is union of services visibility of user
        // $column_coorespondance [ IDPColumn.id ] = IDPServiceSettings.id
        $column_correspondance = [ null, null, null, null, null, 1, 2, 3, 4, 5, 6, 7, 8, 9, null, null, null, null, null, null, null, 10, 10, 11, 11, 12, 12, 13, 13 ];
        if(( $column_id >= 5 && $column_id <= 13 )||( $column_id >= 21 && $column_id <= 28 )) // Only configurable columns are concerned
        {
            if( !$commonSettings[ $column_correspondance[ $column_id ] ][ 'ACTIVATED' ] )
                $viewable = false;
        }

        if( $viewable ) {
            $field_name = $columnSetting['field_name'];
            $titleTranslationId = $columnSetting['translation_id'];
            $title = $has_custom_name?$custom_name:$tabletranslation[$titleTranslationId];
            $visible = $element['visible'];
            $switchable = $element['switchable'];
            $sorted = $element['sorted'];
            if( $sorted && $sortName == null ) {
                $sortName = $field_name;
                $sortOrder = $element['sort_type_asc'] ? 'asc' : 'desc';
            }
            // Search Column
            if( $searchColumns.indexOf($field_name) >= 0 )
                $class = 'idp_search_column';
            else
                $class = 'idp_std_column';

            $columns.push({
                field: $field_name,
                title: $title,
                visible: $visible,
                switchable: $switchable,
                class: $class,
                sortable: true,
            });
        }
    });

    $_lastSortColumn = ($sortName!=null)?$sortName:'id';
    $_lastSortOrder = $sortOrder;

    if( $init === true ) {
        $options = {
            url: $url,
            data: [],
            rowStyle: rowStyle,
            search: true,
            searchOnEnterKey: true,
            reorderableColumns: true,
            showHeader: true,
            queryParams: 'postQueryParams',
            toolbar: '#tabletoolbar',
            toolbarAlign: 'left',
            showToggle: true,
            showColumns: true,
            smartDisplay: false,
            pagination: false, /*true,*/
            sidePagination: $sidePagination,
            method: $method,
            iconsPrefix: 'fas',
            icons: { columns: 'fa-pause',
                toggleOn: 'fa-exchange',
                toggleOff: 'fa-exchange'
            },
            cardView: !$userSettings.userPageSettings.array_type_list,
            sortName: $sortName,
            sortOrder: $sortOrder,
            pageSize: $userSettings.userPageSettings.nb_row_per_page,
            columns: $columns,
            idField: 'ordernumber'

        };
        $table.bootstrapTable($options);
    } else {
        $options = {
            cardView: !$userSettings.userPageSettings.array_type_list,
            sortName: $sortName,
            sortOrder: $sortOrder,
            pageSize: $userSettings.userPageSettings.nb_row_per_page,
            columns: $columns
        };
        $table.bootstrapTable('refreshOptions', $options);
    }

    initSearchTooltip( );
}

function initMainTab( $table, $url, $userSettings, $tabletranslation, $commonSettings, $sidePagination, $method, $caller ){
    $_table = $table;

    initMainTabColumns( true, $table, $url, $userSettings, $tabletranslation, $commonSettings, $sidePagination, $method  );

    initMainTabEvents( $table, $caller );

    // See IDPPartialCheckTab.js
    initUATableList( $table );
}
// For BDD Pages
function initBDDTab( $table, $userSettings, $pageOffset, $sort ){
    $_table = $table;

    $pageNumber = Math.floor( $pageOffset / $userSettings.userPageSettings.nb_row_per_page ) + 1;
    $options = {
        sortOrder: $sort,
        pageSize: $userSettings.userPageSettings.nb_row_per_page,
        pageNumber: $pageNumber
    };

    $table.bootstrapTable('refreshOptions', $options);

    $table.bootstrapTable()
        .on('page-change.bs.table', function( target, pageNo, pageSize ){
            if( pageSize != $_lastPageSize ) {
                $_lastPageSize = pageSize;
                saveUserSettings_PageSize($_currentPage, pageSize);
            }
        })
}

function initSearchTooltip( ){
    $('#list input:text').tooltip({html: true, title: "La recherche s'effectue pour les champs : <b>Libellé</b>, <b>N° d'ordre</b>, <b>N° Conteneur</b>, <b>N° Boîte</b>, <b>N° Dossier</b>"});
}
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

var $documentnatureID = 0;
$_translations = null;
var $_currentPage = PAGE_BDD_ENTRY_ACTIVITIES;
var $_lastPageSize = 0;

function actionFormatter( value, row, index ){
    $pageOffset = ($('#DocumentNaturesListTable').bootstrapTable('getOptions')['pageNumber']-1) * $('#DocumentNaturesListTable').bootstrapTable('getOptions')['pageSize']+index;
    $sortASC = ($('#DocumentNaturesListTable').bootstrapTable('getOptions')['sortOrder']=='asc')?'1':'0';
    $rightLink = window.ARCHIVIST_JSON_URLS.bs_idp_archivist_managedb_input_documentnatures_finetune;
    $rightLink += '?documentNatureId=' + row['id'];
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
		$documentnatureID = row['id'];
		$('#ModifyModal').modal( 'show' );

	},
	'click .remove': function (e, value, row, index){
		$('#SuppressModalText').html( $_translations[2]+' <b>' + row['longname'] + '</b> ?' );
		$documentnatureID = row['id'];
		$('#SuppressModal').modal('show');
	}
};

function onClickBtnSuppressModalConfirm(){
	$dataStr = "id=" + $documentnatureID;
	$url = window.ARCHIVIST_JSON_URLS.bs_idp_archivist_json_documentnatures_delete;
	$.ajax({
		type: "GET",
		url: $url,
		data: $dataStr,
		cache: false,
		success: function($response) {
			$('#DocumentNaturesListTable').bootstrapTable('refresh');
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert( $_translations[15] );
		}
	});

	return true;
}

function onClickBtnModifyModalConfirm(){
	$dataStr = "id=" + $documentnatureID + "&name=" + encodeURIComponent($('#frm_modify_name').val());
	$url = window.ARCHIVIST_JSON_URLS.bs_idp_archivist_json_documentnatures_modify;
	$.ajax({
		type: "GET",
		url: $url,
		data: $dataStr,
		cache: false,
		success: function( $response ){
			$('#DocumentNaturesListTable').bootstrapTable( 'refresh' );
		},
		error: function( xhr, ajaxOptions, thrownError){
			alert(  $_translations[15] );
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
            initBDDTab( $('#DeliverAddressListTable'), $response.data, $_pageOffset, $_sortASC );
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
            popError( $('#div_frm_name'), "Le nom de l'activité ne peut pas être vide.", 'top' );
            return false;
        } else {
            $dataStr = "name=" + encodeURIComponent($('#frm_name').val());
            $pageNumber = $('#DocumentNaturesListTable').bootstrapTable('getOptions')['pageNumber'];
            $url = window.ARCHIVIST_JSON_URLS.bs_idp_archivist_json_documentnatures_add;
            $.ajax({
                type: "GET",
                url: $url,
                data: $dataStr,
                cache: false,
                success: function ($response) {
                    $('#frm_name').val('');
                    $('#DocumentNaturesListTable').bootstrapTable('refresh', {'pageNumber': $pageNumber});
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