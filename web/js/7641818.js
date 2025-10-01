
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


function printTable( $onlySelection, $tableObj, $fctCall, $whereAmI = null ){
    $listColumn = [];
    $columns = $tableObj.bootstrapTable('getOptions').columns[0];

    $options = $tableObj.bootstrapTable('getOptions');
    $cardView = ($options['cardView']==true?'1':'0');

    for( $i = 0; $i < $columns.length; $i++ ){
        $column = $columns[$i];
        if ( $column['visible'] == true && $column['field'] != 'state' )
            $listColumn.push( [ $column['field'], $column['title'] ] );
    }

    if( $cardView == '0' && $column.length > 8 ){
        bootbox.alert( {
            message: "Il y a trop de colonnes à imprimer, veuillez choisir le mode carte, ou l'export !",
            className: "boxErrorOne"
        });
        return;
    }

    if( $onlySelection ) {
        $listId = []; // null for all / list for select
        var $selection = $tableObj.bootstrapTable('getSelections');
        if( $selection.length <= 0 )
            return;
        for ($i = 0; $i < $selection.length; $i++)
            $listId.push($selection[$i]['id']);

        $searchParameters = null;

        // Ask server to send back the print page processed
        get( window.JSON_URLS.bs_idp_archive_print_table, {
            'listId': JSON.stringify( $listId ),
            'listColumn': JSON.stringify( $listColumn ),
            'xpsearch': JSON.stringify( $searchParameters ),
            'format': 0,
            'fctCall': $fctCall,
            'cardview': $cardView }, true );

    } else {
        $listId = null;

        // New for asynchronous print
        $searchParameters = [ null, null, null, null, null, null, null, null, null, null, null, null, null, null, null,
            null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null];

        switch( $fctCall ){
            case 1: // Transfer
                $searchParameters[20] = $options.searchText; // 'special': $options.searchText
                break;
            case 2: // Consult, Return, Exit, Reloc, Delete
                $filters = getFilters();
                if( $('#service option:selected').val() == -1 ) // No service selected
                    $service = null;
                else
                    $service = $('#service option:selected').val();

                $searchParameters[0] = $service;
                $searchParameters[1] = $('#legalentity option:selected').val();
                $searchParameters[2] = $('#description1 option:selected').val();
                $searchParameters[3] = $('#description2 option:selected').val();
                $searchParameters[4] = $('#name').val();
                $searchParameters[5] = $('#limitnum').val();
                $searchParameters[6] = $('#limitalpha').val();
                $searchParameters[7] = $('#limitalphanum').val();
                $searchParameters[8] = $('#limitdate').val();
                $searchParameters[9] = $('#ordernumber').val();
                $searchParameters[10] = $('#budgetcode option:selected').val();
                $searchParameters[11] = $('#documentnature option:selected').val();
                $searchParameters[12] = $('#documenttype option:selected').val();
                $searchParameters[13] = $('#closureyear').prop("value");
                $searchParameters[14] = $('#destructionyear').prop("value");
                $searchParameters[15] = $('#documentnumber').val();
                $searchParameters[16] = $('#boxnumber').val();
                $searchParameters[17] = $('#containernumber').val();
                $searchParameters[18] = $('#provider option:selected').val();
                $searchParameters[19] = $('#unlimited').val();
                $searchParameters[20] = $options.searchText;

                if ($filters) {
                    $searchParameters[21] = $filters['filterstatus'];
                    $searchParameters[22] = $filters['filterwhere'];
                    $searchParameters[23] = $filters['filterwith'];
                    $searchParameters[24] = $filters['filterlocalization'];
                }
                break;
            case 3:
                $f_prov = -1;
                if( $xpstate == UASTATE_MANAGEPROVIDER )
                    $f_prov = $filter_provider;
                else {
                    if( $xpstate == UASTATE_MANAGECLOSE && $uawhat == UAWHAT_TRANSFER && $uawhere == UAWHERE_PROVIDER )
                        $f_prov = $filter_provider;
                }
                $searchParameters[20] = $options.searchText;
                $searchParameters[25] = $xpstate;
                $searchParameters[26] = $uawhat;
                $searchParameters[27] = $uawhere;
                $searchParameters[28] = $uahow;
                $searchParameters[29] = null /* $uawith */ ;
                $searchParameters[30] = $f_prov;
                break;
        }

        // Just ask the server to begin the print process, and return to ihm
        $.ajax({
            type: "GET",
            url: window.JSON_URLS.bs_idp_archive_print_table_offline,
            data: {
                'listId': JSON.stringify( null ),
                'listColumn': JSON.stringify( $listColumn ),
                'xpsearch': JSON.stringify( $searchParameters ),
                'format': 0,
                'whereAmI': $whereAmI,
                'cardview': $cardView },
            cache: false,
            success: function( data, status ){
                $('#userFileResume').html( '<a href="' + window.JSON_URLS.bs_core_userspace_userfile_viewmainscreen + '"><i class="fad fa-file text-primary"></i>&nbsp;<i class="fal fa-compact-disc fa-spin text-primary"></i></a>' );
                bootbox.alert( {
                    message: "L'impression va être générée et sera disponible dans votre espace utilisateur dès que terminée.",
                    className: "boxInfoOne"
                } );
            },
            error: function( xhr, ajaxOptions, throwError ){
                if( xhr.status == 409 )
                    bootbox.alert( {
                        message: xhr.responseJSON.message,
                        className: "boxErrorOne"
                    } );
                else
                    bootbox.alert( {
                        message: "Une erreur serveur est survenue. <br>["+xhr.status+"] - "+xhr.responseJSON.message,
                        className: "boxSysErrorOne"
                    } );
            }
        });
    }

}
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
/**
 * Created by Cyril on 26/01/2016.
 */

// Work for Consult, Return, Exit and Retreive pages, with file partial.overlay.viewArchive.html.twig

// Function to init overlay view.
var currentOverlayViewUA = null;

var currentCommentsUnlimited = '';
var $_buttonsOverlay = null;

function initOverlay( ){

    $('#divViewLblLocalization').hide();
    $('#divViewSelectLocalization').hide();
    $('#divViewLblLocalizationfree').hide();
    $('#divViewInputLocalizationfree').hide();
    $('#divViewNothingLine1').hide();
    $('#divViewLblOldLocalization').hide();
    $('#divViewSelectOldLocalization').hide();
    $('#divViewLblOldLocalizationfree').hide();
    $('#divViewInputOldLocalizationfree').hide();
    $('#divLine0').hide();

    $('#frm_service').attr('disabled', true);
    $('#frm_service').html("<option value selected=\"selected\">N/A</option>");
    $('#frm_name').attr('disabled', true);
    $('#frm_limitnum').attr('disabled', true);
    $('#frm_limitalpha').attr('disabled', true);
    $('#frm_limitalphanum').attr('disabled', true);
    $('#frm_limitdate').attr('disabled', true);
    $('#frm_closureyear').attr('disabled', true);
    $('#frm_destructionyear').attr('disabled', true);
    $('#frm_ordernumber').attr('disabled', true);
    $('#frm_documentnumber').attr('disabled', true);
    $('#frm_boxnumber').attr('disabled', true);
    $('#frm_legalentity').attr('disabled', true);
    $('#frm_legalentity').html("<option value selected=\"selected\">N/A</option>");
    $('#frm_documentnature').attr('disabled', true);
    $('#frm_documentnature').html("<option value selected=\"selected\">N/A</option>");
    $('#frm_documenttype').attr('disabled', true);
    $('#frm_documenttype').html("<option value selected=\"selected\">N/A</option>");
    $('#frm_description1').attr('disabled', true);
    $('#frm_description1').html("<option value selected=\"selected\">N/A</option>");
    $('#frm_description2').attr('disabled', true);
    $('#frm_description2').html("<option value selected=\"selected\">N/A</option>");
    $('#frm_budgetcode').attr('disabled', true);
    $('#frm_budgetcode').html("<option value selected=\"selected\">N/A</option>");
    $('#frm_provider').attr('disabled', true);
    $('#frm_provider').html("<option value selected=\"selected\">N/A</option>");
    $('#frm_localization').attr('disabled', true);
    $('#frm_localization').html('<option value selected="selected">N/A</option>');
    $('#frm_localizationfree').attr('disabled', true);
    $('#frm_oldlocalization').attr('disabled', true);
    $('#frm_oldlocalization').html('<option value selected="selected">N/A</option>');
    $('#frm_oldlocalizationfree').attr('disabled', true);
}
function initOverlayLocalization( $localizationType, $oldLocalizationType ){
    switch( $localizationType ){
        case 0:
            $('#divViewLblLocalization').show();
            $('#divViewSelectLocalization').show();
            break;
        case 1:
            $('#divViewLblLocalizationfree').show();
            $('#divViewInputLocalizationfree').show();
            break;
        default:
            $('#divViewNothingLine1').show();
            break;
    }
    switch( $oldLocalizationType ){
        case 0:
            $('#divLine0').show();
            $('#divViewLblOldLocalization').show();
            $('#divViewSelectOldLocalization').show();
            break;
        case 1:
            $('#divLine0').show();
            $('#divViewLblOldLocalizationfree').show();
            $('#divViewInputOldLocalizationfree').show();
            break;
    }
}

// Function launched when user double click on a row in the result table
// @param row : on which the user clicked
// @param initLocalization: how to set localization fields (select / input / nothing)
// @param buttons: buttons to show MODIF(1) / DELETE(2) / CANCEL(4) / PRINT(8) / PRINTTAG(16), transmit sum of buttons value
function dblClickRow( row, $initLocalization, $initOldLocalization, $buttons ){

    currentOverlayViewUA = row;

    adminidlist = row['adminidlist'].split(",");
    getAjaxSettings(adminidlist[0], row, $initLocalization, $initOldLocalization, $buttons );
}
// Retreives the Settings based on the selected service
function getAjaxSettings( $serviceId, rowData, $initLocalization, $initOldLocalization, $buttons ){
    var $dataObj = {
        'serviceid': $serviceId
    };

    $.ajax({
        type: "GET",
        url: window.JSON_URLS.bs_idp_backoffice_ajax_settings,
        data: $dataObj,
        cache: false,
        success: function ( data ){
            $_settings = data;
            setView( rowData, $initLocalization, $initOldLocalization,  $buttons );
        },
        error: function ( xhr, ajaxOptions, thrownError ){
            alert( 'Error in getting settings' );
        }
    })
}
// Modify the overlay view according to new datas received
function setView( rowData, $initLocalization, $initOldLocalization, $buttons ){

    setHideField();
    clearViewClass();

    if( rowData != null ) {
        initViewWindow( rowData, $initLocalization, $initOldLocalization, $buttons );
    }
}
// Based on $_settings received (depends on service of the archive in the row) set vixibility of all fields
function setHideField( ){
    // Hide un-ask fields
    if( !$_settings.view_budgetcode ){
        $('#divViewLblBudgetcode').hide();
        $('#divViewSelectBudgetcode').hide();
    } else {
        $('#divViewLblBudgetcode').show();
        $('#divViewSelectBudgetcode').show();
        if( $_settings.mandatory_budgetcode ){
            $('#lbl_budgetcode').html( $_overlay[5] + '<span class="text-danger">*</span>' );
        }
    }
    if( !$_settings.view_documentnature ){
        $('#divViewLblDocumentnature').hide();
        $('#divViewSelectDocumentnature').hide();
        // Document type depends on Document nature, so hide it also
        $('#divViewLblDocumenttype').hide();
        $('#divViewSelectDocumenttype').hide();
    } else {
        $('#divViewLblDocumentnature').show();
        $('#divViewSelectDocumentnature').show();
        if( $_settings.mandatory_documentnature ){
            $('#lbl_documentnature').html( $_overlay[7] + '<span class="text-danger">*</span>' );
        }
    }
    if( !$_settings.view_documenttype ){
        $('#divViewLblDocumenttype').hide();
        $('#divViewSelectDocumenttype').hide();
    } else {
        if( $_settings.view_documentnature ){
            $('#divViewLblDocumenttype').show();
            $('#divViewSelectDocumenttype').show();
        }
        if( $_settings.mandatory_documenttype ){
            $('#lbl_documenttype').html( $_overlay[8] + '<span class="text-danger">*</span>' );
        }
    }
    if( !$_settings.view_description1 ){
        $('#divViewLblDescription1').hide();
        $('#divViewSelectDescription1').hide();
    } else {
        $('#divViewLblDescription1').show();
        $('#divViewSelectDescription1').show();
        $('#lbl_description1').html( $_settings.name_description1 + ($_settings.mandatory_description1?'<span class="text-danger">*</span>':''));
    }
    if( !$_settings.view_description2 ){
        $('#divViewLblDescription2').hide();
        $('#divViewSelectDescription2').hide();
    } else {
        $('#divViewLblDescription2').show();
        $('#divViewSelectDescription2').show();
        $('#lbl_description2').html( $_settings.name_description2 + ($_settings.mandatory_description2?'<span class="text-danger">*</span>':''));
    }
    if( !$_settings.view_documentnature && !$_settings.view_description1 && !$_settings.view_description2 ){
        $('#divBlockDescription').hide();
    } else {
        $('#divBlockDescription').show();
    }

    if( !$_settings.view_limitsdate ){
        $('#divViewLblLimitsdatemin').hide();
        $('#divViewLblLimitsdatemax').hide();
        $('#divViewInputLimitsdatemin').hide();
        $('#divViewInputLimitsdatemax').hide();
    } else {
        $('#divViewLblLimitsdatemin').show();
        $('#divViewLblLimitsdatemax').show();
        $('#divViewInputLimitsdatemin').show();
        $('#divViewInputLimitsdatemax').show();
        if( $_settings.mandatory_limitsdate ){
            $('#lbl_limitsdatemin').html( $_overlay[21] + '<span class="text-danger">*</span>');
            $('#lbl_limitsdatemax').html( $_overlay[22] + '<span class="text-danger">*</span>');
        }
    }
    if( !$_settings.view_limitsnum ){
        $('#divViewLblLimitsnummin').hide();
        $('#divViewLblLimitsnummax').hide();
        $('#divViewInputLimitsnummin').hide();
        $('#divViewInputLimitsnummax').hide();
    } else {
        $('#divViewLblLimitsnummin').show();
        $('#divViewLblLimitsnummax').show();
        $('#divViewInputLimitsnummin').show();
        $('#divViewInputLimitsnummax').show();
        if( $_settings.mandatory_limitsnum ){
            $('#lbl_limitsnummin').html( $_overlay[23] + '<span class="text-danger">*</span>');
            $('#lbl_limitsnummax').html( $_overlay[24] + '<span class="text-danger">*</span>');
        }
    }
    if( !$_settings.view_limitsalpha ){
        $('#divViewLblLimitsalphamin').hide();
        $('#divViewLblLimitsalphamax').hide();
        $('#divViewInputLimitsalphamin').hide();
        $('#divViewInputLimitsalphamax').hide();
    } else {
        $('#divViewLblLimitsalphamin').show();
        $('#divViewLblLimitsalphamax').show();
        $('#divViewInputLimitsalphamin').show();
        $('#divViewInputLimitsalphamax').show();
        if( $_settings.mandatory_limitsalpha ){
            $('#lbl_limitsalphamin').html( $_overlay[25] + '<span class="text-danger">*</span>');
            $('#lbl_limitsalphamax').html( $_overlay[26] + '<span class="text-danger">*</span>')
        }
    }
    if( !$_settings.view_limitsalphanum ){
        $('#divViewLblLimitsalphanummin').hide();
        $('#divViewLblLimitsalphanummax').hide();
        $('#divViewInputLimitsalphanummin').hide();
        $('#divViewInputLimitsalphanummax').hide();
    } else {
        $('#divViewLblLimitsalphanummin').show();
        $('#divViewLblLimitsalphanummax').show();
        $('#divViewInputLimitsalphanummin').show();
        $('#divViewInputLimitsalphanummax').show();
        if( $_settings.mandatory_limitsalphanum ){
            $('#lbl_limitsalphanummin').html( $_overlay[27] + '<span class="text-danger">*</span>');
            $('#lbl_limitsalphanummax').html( $_overlay[28] + '<span class="text-danger">*</span>');
        }
    }
    if( !$_settings.view_limitsdate && !$_settings.view_limitsalpha && !$_settings.view_limitsnum && !$_settings.view_limitsalphanum ){
        $('#divViewBlockLimits').hide();
    } else {
        $('#divViewBlockLimits').show();
    }

    if( !$_settings.view_filenumber ){
        $('#divViewLblFilenumber').hide();
        $('#divViewInputFilenumber').hide();
    } else {
        $('#divViewLblFilenumber').show();
        $('#divViewInputFilenumber').show();
        if( $_settings.mandatory_filenumber ){
            $('#lbl_filenumber').html( $_overlay[15] + '<span class="text-danger">*</span>');
        }
    }
    if( !$_settings.view_boxnumber ){
        $('#divViewLblBoxnumber').hide();
        $('#divViewInputBoxnumber').hide();
    } else {
        $('#divViewLblBoxnumber').show();
        $('#divViewInputBoxnumber').show();
        if( $_settings.mandatory_boxnumber ){
            $('#lbl_boxnumber').html( $_overlay[16] + '<span class="text-danger">*</span>');
        }
    }
    if( !$_settings.view_containernumber ){
        $('#divViewLblContainernumber').hide();
        $('#divViewInputContainernumber').hide();
    } else {
        $('#divViewLblContainernumber').show();
        $('#divViewInputContainernumber').show();
        if( $_settings.mandatory_containernumber ){
            $('#lbl_containernumber').html( $_overlay[17] + '<span class="text-danger">*</span>');
        }
    }
    if( !$_settings.view_provider ){
        $('#divViewLblProvider').hide();
        $('#divViewSelectProvider').hide();
    } else {
        $('#divViewLblProvider').show();
        $('#divViewSelectProvider').show();
        if( $_settings.mandatory_provider ){
            $('#lbl_provider').html( $_overlay[18] + '<span class="text-danger">*</span>');
        }
    }
    if( !$_settings.view_filenumber && !$_settings.view_boxnumber && !$_settings.view_containernumber && !$_settings.view_provider ){
        $('#divViewBlockProviderdatas').hide();
    } else {
        $('#divViewBlockProviderdatas').show();
    }

    $('#divLine0').hide();
    $('#divViewLblOldLocalization').hide();
    $('#divViewSelectOldLocalization').hide();
    $('#divViewLblOldLocalizationfree').hide();
    $('#divViewInputOldLocalizationfree').hide();
    $('#divViewLblLocalization').hide();
    $('#divViewSelectLocalization').hide();
    $('#divViewLblLocalizationfree').hide();
    $('#divViewInputLocalizationfree').hide();


}
// Set datas from the archive in the according fields of the overlay view
function initViewWindow( rowData, $initLocalization, $initOldLocalization, $buttons ){

    $('#frm_name').prop('disabled', false);
    updateView(rowData);

    $_buttonsOverlay = $buttons;
    if( $buttons ) {
        if ($buttons & 1)
            $('#divModif').show();
        else
            $('#divModif').hide();
        $('#divSubmitModif').hide();

        if ($buttons & 2)
            $('#divDelete').show();
        else
            $('#divDelete').hide();
        if ($buttons & 4)
            $('#divCancel').show();
        else
            $('#divCancel').hide();
        if ($buttons & 8)
            $('#divPrint').show();
        else
            $('#divPrint').hide();
        if ($buttons & 16)
            $('#divPrintTag').show();
        else
            $('#divPrintTag').hide();
    }

    switch( $initLocalization ){
        case 0: // UAWHERE_PROVIDER
            initOverlayLocalization( 0, $initOldLocalization );
            break;
        case 1:
        case 2:
            initOverlayLocalization( 1, $initOldLocalization );
            break;
        default:
            initOverlayLocalization( null, null );
            break;
    }

    $('#frm_ordernumber').prop('disabled', true);
    $('#frm_service').prop('disabled', true);
    $('#frm_legalentity').prop('disabled', true);
    $('#frm_budgetcode').prop('disabled', true);
    $('#frm_documentnature').prop('disabled', true);
    $('#frm_documenttype').prop('disabled', true);
    $('#frm_description1').prop('disabled', true);
    $('#frm_description2').prop('disabled', true);
    $('#frm_closureyear').prop('disabled', true);
    $('#frm_destructionyear').prop('disabled', true);
    $('#frm_documentnumber').prop('disabled', true);
    $('#frm_boxnumber').prop('disabled', true);
    $('#frm_containernumber').prop('disabled', true);
    $('#frm_provider').prop('disabled', true);
    $('#frm_name').prop('disabled', true);
    $('#frm_limitdatemin').prop('disabled', true);
    $('#frm_limitdatemax').prop('disabled', true);
    $('#frm_limitnummin').prop('disabled', true);
    $('#frm_limitnummax').prop('disabled', true);
    $('#frm_limitalphamin').prop('disabled', true);
    $('#frm_limitalphamax').prop('disabled', true);
    $('#frm_limitalphanummin').prop('disabled', true);
    $('#frm_limitalphanummax').prop('disabled', true);
    $('#frm_localization').prop('disabled', true);
    $('#frm_localizationfree').prop('disabled', true);
    $('#frm_oldlocalization').prop('disabled', true);
    $('#frm_oldlocalizationfree').prop('disabled', true);

    $('#frm_unlimited').prop('disabled', true );
    $('#btn_commentsunlimited').prop('disabled', false );

    $('#viewArchive').show();
}
// Clear the state colors of modification verification
// Only used when modification is allowed
function clearViewClass() {
    $('#divViewSelectService').removeClass('has-success');
    $('#divViewSelectService').removeClass('has-error');

    $('#divViewSelectLegalentity').removeClass('has-success');
    $('#divViewSelectLegalentity').removeClass('has-error');

    $('#frm_name').removeClass('has-success');
    $('#frm_name').removeClass('has-error');

    $('#divViewInputClosureyear').removeClass('has-success');
    $('#divViewInputClosureyear').removeClass('has-error');

    $('#divViewInputDestructionyear').removeClass('has-success');
    $('#divViewInputDestructionyear').removeClass('has-error');

    if( $_settings.view_budgetcode  ){
        $('#divViewSelectBudgetcode').removeClass('has-success');
        $('#divViewSelectBudgetcode').removeClass('has-error');
    }

    if( $_settings.view_documentnature  ){
        $('#divViewSelectDocumentnature').removeClass('has-success');
        $('#divViewSelectDocumentnature').removeClass('has-error');
    }

    if( $_settings.view_documentnature  ){
        $('#divViewSelectDocumenttype').removeClass('has-success');
        $('#divViewSelectDocumenttype').removeClass('has-error');
    }

    if( $_settings.view_description1  ){
        $('#divViewSelectDescription1').removeClass('has-success');
        $('#divViewSelectDescription1').removeClass('has-error');
    }

    if( $_settings.view_description2  ){
        $('#divViewSelectDescription2').removeClass('has-success');
        $('#divViewSelectDescription2').removeClass('has-error');
    }
    if( $_settings.view_limitsdate  ){
        $('#divViewInputLimitsdatemin').removeClass('has-success')
        $('#divViewInputLimitsdatemin').removeClass('has-error');
        $('#divViewInputLimitsdatemax').removeClass('has-success');
        $('#divViewInputLimitsdatemax').removeClass('has-error');
    }

    if( $_settings.view_limitsnum  ){
        $('#divViewInputLimitsnummin').removeClass('has-success')
        $('#divViewInputLimitsnummin').removeClass('has-error');
        $('#divViewInputLimitsnummax').removeClass('has-error')
        $('#divViewInputLimitsnummax').removeClass('has-success');
    }

    if( $_settings.view_limitsalpha  ){
        $('#divViewInputLimitsalphamin').removeClass('has-success')
        $('#divViewInputLimitsalphamin').removeClass('has-error');
        $('#divViewInputLimitsalphamax').removeClass('has-error')
        $('#divViewInputLimitsalphamax').removeClass('has-success');
    }

    if( $_settings.view_limitsalphanum  ){
        $('#divViewInputLimitsalphanummin').removeClass('has-success')
        $('#divViewInputLimitsalphanummin').removeClass('has-error');
        $('#divViewInputLimitsalphanummax').removeClass('has-error')
        $('#divViewInputLimitsalphanummax').removeClass('has-success');
    }

    if( $_settings.view_filenumber  ){
        $('#divViewInputFilenumber').removeClass('has-success');
        $('#divViewInputFilenumber').removeClass('has-error');
    }

    if( $_settings.view_boxnumber  ){
        $('#divViewInputBoxnumber').removeClass('has-success');
        $('#divViewInputBoxnumber').removeClass('has-error');
    }

    if( $_settings.view_containernumber  ){
        $('#divViewInputContainernumber').removeClass('has-success');
        $('#divViewInputContainernumber').removeClass('has-error');
    }

    if( $_settings.view_provider  ){
        $('#divViewSelectProvider').removeClass('has-success');
        $('#divViewSelectProvider').removeClass('has-error');
    }

    $('#divViewSelectLocalization').removeClass('has-success');
    $('#divViewSelectLocalization').removeClass('has-error');
    $('#divViewInputLocalizationfree').removeClass('has-success');
    $('#divViewInputLocalizationfree').removeClass('has-error');

    $('#form_name').removeClass('has-success');
    $('#form_name').removeClass('has-error');
}

function date2string( myDate ){
    var dateString = myDate.date;
    var yyyy = dateString.substr( 0, 4 );
    var mm = dateString.substr( 5, 2 );
    var dd  = dateString.substr( 8, 2 );
    return dd + '/' + mm + '/' + yyyy ; // padding
}
// Update the view with datas of the archive selected
function updateView( row ){

    adminidlist = row['adminidlist'].split(",");

    // update id
    $('#frm_id').val( row['id'] );
    // update order_number
    $('#frm_ordernumber').val( row['ordernumber'] );
    // update service with default value, all list will be updated concordingly
    $defaultService =  adminidlist[0];
    $defaultLegalEntity =  adminidlist[1];
    $defaultBudgetCode =  adminidlist[2];
    $defaultDocumentNature =  adminidlist[3];
    $defaultDocumentType =  adminidlist[4];
    $defaultDescription1 =  adminidlist[5];
    $defaultDescription2 =  adminidlist[6];
    $defaultProvider =  adminidlist[7];
    $defaultLocalization = adminidlist[8];
    $defaultOldLocalization = adminidlist[9];
    updateServices( $defaultService );
    // Update Closure year
    $('#frm_closureyear').val( row['closureyear'] );
    // Update Destruction year
    $('#frm_destructionyear').val( row['destructionyear'] );
    // Update Document numbe
    if( row['documentnumber'] != '-' )
        $('#frm_documentnumber').val( row['documentnumber'] );
    else
        $('#frm_documentnumber').val('');
    // Update Box Number
    if( row['boxnumber'] != '-' )
        $('#frm_boxnumber').val( row['boxnumber'] );
    else
        $('#frm_boxnumber').val('');
    // Update Container number
    if( row['containernumber'] != '-' )
        $('#frm_containernumber').val( row['containernumber'] );
    else
        $('#frm_containernumber').val( '' );
    // Update name
    //$('#frm_name').html( row['name'] );
    $('#frm_name').val( row['name'] );
    // Update limit date min & max
    if( typeof( row['limitdatemin'] ) === 'object' )
        $('#frm_limitdatemin').val(date2string(row['limitdatemin']));
    else
        if( row['limitdatemin'] != '-' )
            $('#frm_limitdatemin').val( row['limitdatemin'] );
        else
            $('#frm_limitdatemin').val('');
    if( typeof(row['limitdatemax'])==='object')
        $('#frm_limitdatemax').val(date2string(row['limitdatemax']));
    else
        if( row['limitdatemax'] != '-' )
            $('#frm_limitdatemax').val( row['limitdatemax'] );
        else
            $('#frm_limitdatemax').val( '' );
    // Update limit num min & max
    if( row['limitnummin'] != '-' )
        $('#frm_limitnummin').val( row['limitnummin'] );
    else
        $('#frm_limitnummin').val('');
    if( row['limitnummax'] != '-' )
        $('#frm_limitnummax').val( row['limitnummax'] );
    else
        $('#frm_limitnummax').val('');
    // Update limit alpha min & max
    if( row['limitalphamin'] != '-' )
        $('#frm_limitalphamin').val( row['limitalphamin'] );
    else
        $('#frm_limitalphamin').val('');
    if( row['limitalphamax'] != '-' )
        $('#frm_limitalphamax').val( row['limitalphamax'] );
    else
        $('#frm_limitalphamax').val('');
    // Update limit alphanum min & max
    if( row['limitalphanummin'] != '-' )
        $('#frm_limitalphanummin').val( row['limitalphanummin'] );
    else
        $('#frm_limitalphanummin').val('');
    if( row['limitalphanummax'] != '-' )
        $('#frm_limitalphanummax').val( row['limitalphanummax'] );
    else
        $('#frm_limitalphanummax').val('');
    // Update localisation free
    if( row['localizationfree'] != '-' )
        $('#frm_localizationfree').val( row['localizationfree'] );
    else
        $('#frm_localizationfree').val('');
    if( row['oldlocalizationfree'] != '-' )
        $('#frm_oldlocalizationfree').val( row['oldlocalizationfree'] );
    else
        $('#frm_oldlocalizationfree').val('');

    // Update unlimited
    if( row['unlimited'] == 'actif' ){
        $('#frm_unlimited').prop('checked', true );
        $('#btn_commentsunlimited').show();
        currentCommentsUnlimited = row['unlimitedcomments'];
    } else {
        $('#frm_unlimited').prop('checked', false );
        $('#btn_commentsunlimited').hide();
        currentCommentsUnlimited = '-';
    }
}

$('#btn_commentsunlimited').click( function( event ){
    event.preventDefault();

    currentCommentsUnlimited = (currentCommentsUnlimited===null)?'-':currentCommentsUnlimited;
    bootbox.dialog( {
        size: "small",
        title: "Commentaires",
        className: "bringToFront,boxInfoOne",
        message: currentCommentsUnlimited  });
});

// Used with modify action enabled
$('#frm_unlimited').change(function(event){
    event.preventDefault();

    if ($('#frm_unlimited').is(':checked')){
        $('#btn_commentsunlimited').show();
        $('#frm_destructionyear').prop('disabled', true);
        bootbox.prompt({
            size: "small",
            title: "Commentaires d'illimité ?",
            className: "bowQuestionTwo",
            callback: function(result){ currentCommentsUnlimited = result; }
        });
    } else {
        $('#btn_commentsunlimited').hide();
        $('#frm_destructionyear').prop('disabled', false);
        currentCommentsUnlimited = '';
    }
})

// On Cancel button hit, just hide the view; everything is done when showing
$('#divCancel').click(function( event ){
    event.preventDefault();

    $('#viewArchive').hide();
    $('#divPrint').show();
    if ($_buttonsOverlay & 16)
        $('#divPrintTag').show();
    currentOverlayViewUA = null;
});

// On Modif button hit, enable all modifiable elements
$('#divModif').click(function( event ){
    event.preventDefault();

    $('#frm_ordernumber').prop('disabled', true);
    $('#frm_closureyear').prop('disabled', false);
    $('#frm_destructionyear').prop('disabled', false);
    $('#frm_documentnumber').prop('disabled', false);
    $('#frm_boxnumber').prop('disabled', false);
    $('#frm_containernumber').prop('disabled', false);
    $('#frm_name').prop('disabled', false);
    $('#frm_limitdatemin').prop('disabled', false);
    $('#frm_limitdatemax').prop('disabled', false);
    $('#frm_limitnummin').prop('disabled', false);
    $('#frm_limitnummax').prop('disabled', false);
    $('#frm_limitalphamin').prop('disabled', false);
    $('#frm_limitalphamax').prop('disabled', false);
    $('#frm_limitalphanummin').prop('disabled', false);
    $('#frm_limitalphanummax').prop('disabled', false);
    $('#frm_localization').prop('disabled', false);
    $('#frm_localizationfree').prop('disabled', false);
    $('#frm_unlimited').prop('disabled', false);
    $('#btn_commentsunlimited').prop('disabled', false);
    /* Old localization cannot be modified !
    $('#frm_oldlocalization').prop('disabled', false);
    $('#frm_oldlocalizationfree').prop('disabled', false);
    */
    $('#divModif').hide();
    $('#divPrint').hide();
    $('#divPrintTag').hide();

    // Modify 'disabled' linked to coherency
    $('#frm_service').prop('disabled', false)
    if( $("#frm_service option:selected").val() != "" ){
        $('#frm_legalentity').prop('disabled', false);
        $('#frm_budgetcode').prop('disabled', false);
        $('#frm_description1').prop('disabled', false);
        $('#frm_description2').prop('disabled', false);
        $('#frm_provider').prop('disabled', false);
        $('#frm_localization').prop('diabled', false);
    }
    if( $("#frm_legalentity option:selected").val() != "" )
        $('#frm_documentnature').prop('disabled', false);
    if( $("#frm_documentnature option:selected").val() != "" )
        $('#frm_documenttype').prop('disabled', false);

    // BZ#29 disabled provider and localization everywhere except transfer page, reloc page (in user part) and transfer tab and reloc tab (in archivist part)
    if( window.IDP_CONST.bs_idp_current_page != 1 &&
        window.IDP_CONST.bs_idp_current_page != 6 &&
        !( window.IDP_CONST.bs_idp_current_page >= 7 && $uawhat == UAWHAT_TRANSFER ) &&
        !( window.IDP_CONST.bs_idp_current_page >= 7 && $uawhat == UAWHAT_RELOC )){

        $('#frm_provider').attr('disabled', true);
        $('#frm_localization').attr('disabled', true);
        $('#frm_localizationfree').attr('disabled', true);
    }

    // BZ#38 Disabled field during relocalisation
    if( currentOverlayViewUA != null ) {
        if ( $.inArray(currentOverlayViewUA['statuscaps'], RELOC_FIELD_DISABLED_STATUS ) >= 0 ) {
            $('#frm_documentnumber').prop('disabled', true);
            $('#frm_boxnumber').prop('disabled', true);
            $('#frm_containernumber').prop('disabled', true);
            $('#frm_provider').prop('disabled', true);
        }
    }

    $('#divSubmitModif').show();

});

function initLists(){
    $.ajax({
        type: "POST",
        url: window.JSON_URLS.bs_idp_archive_form_initlists,
        data: null,
        cache: false,
        success:
        updateLists,
        error: function (xhr, ajaxOptions, thrownError) {
            alert( 'Error' );
        }
    });

}

//------------------------------------------------------------------------------------
// Verify if all UAs have the localization (or localizationfree) field filled
//
// -> $listUAs: list of UAs to verify
// -> $bFreeOrLink: verify LocalizationFree (true) or Localization (false)
// <- Boolean: true at least one UAs have localization field missing, otherwise false
function isLocalizationMissing( $listUAs, $bFreeOrLink ){
    $localizationMissing = false;
    $listUAs.forEach( function( $elem ){
        if( $bFreeOrLink ){
            if( $elem['localizationfree'] == '' || $elem['localizationfree'] === null || $elem['localizationfree'] == '-' ) {
                $localizationMissing = true;
                return;
            }
        } else {
            if( $elem['localization'] == '' || $elem['localization'] === null || $elem['localization'] == '-' ) {
                $localizationMissing = true;
                return;
            }
        }
    });
    return $localizationMissing;
}

//------------------------------------------------------------------------------------
// Verify if all UAs have the provider field filled
//
// -> $listUAs: list of UAs to verify
// <- Boolean: true at least one UAs have provider field missing, otherwise false
function isProviderMissing( $listUAs ){
    $providerMissing = false;
    $listUAs.forEach( function( $elem ){
        if( $elem['provider'] == '' || $elem['provider'] === null || $elem['provider'] == '-' ) {
            $providerMissing = true;
            return;
        }
    });
    return $providerMissing;
}

//----------------------------------------------------------------------------------------------
// Test we can do on the client side before sending request ACTION to backoffice
function is_ClientSide_Exceptions_Verified( ){
    switch( $uawhat ){
        case UAWHAT_TRANSFER:
        case UAWHAT_RELOC:
            switch( $uawhere ){
                case UAWHERE_INTERNAL:
                case UAWHERE_INTERMEDIATE:
                    return !isLocalizationMissing( $actionList, true );
                case UAWHERE_PROVIDER:
                    if( !isLocalizationMissing( $actionList, false ) )
                        return !isProviderMissing( $actionList );
                    else
                        return false;
            }
            break;

        case UAWHAT_CONSULT:
        case UAWHAT_RETURN:
        case UAWHAT_EXIT:
        case UAWHAT_DESTROY:
            return true;
    }
}
function make_ClientSide_Exceptions_ErrorMsg( ){
    switch( $uawhat ) {
        case UAWHAT_TRANSFER:
            switch ($uawhere) {
                case UAWHERE_INTERNAL:
                case UAWHERE_INTERMEDIATE:
                    return "Certaines archives n'ont pas de localisation. Merci de bien vouloir renseigner cette information pour procéder au transfert.";
                case UAWHERE_PROVIDER:
                    return "Certaines archives n'ont pas de localisation et/ou de compte prestataire. Merci de bien vouloir renseigner ces informations pour procéder au transfert.";
            }
            break;
        case UAWHAT_RELOC:
            switch ($uawhere) {
                case UAWHERE_INTERNAL:
                case UAWHERE_INTERMEDIATE:
                    return "Certaines archives n'ont pas de localisation. Merci de bien vouloir renseigner cette information pour procéder à la relocalisation.";
                case UAWHERE_PROVIDER:
                    return "Certaines archives n'ont pas de localisation et/ou de compte prestataire. Merci de bien vouloir renseigner ces informations pour procéder à la relocalisation.";
            }
            break;
    }
    return "";
}

//----------------------------------------------------------------------------------------------
// Manage Actions
function clickAction(){
    if( is_ClientSide_Exceptions_Verified() ) {

        $('#waitAjax').show();
        is_basket_verified( );

    } else {
        $errorMsg = make_ClientSide_Exceptions_ErrorMsg();

        $('#errorTitle').html( "Erreur" );
        $('#errorMsg').html( $errorMsg );
        $('#modalErrorMessage').modal( 'show' );
    }
}
function is_basket_verified( ){
    $dataObject = {
        'idslist': JSON.stringify($actionListID),
        'uastate': $xpstate,
        'uawhat': $uawhat,
        'uawhere': $uawhere
    };

    $.ajax({
        type: 'GET',
        url: window.JSON_URLS.bs_idp_archivist_json_is_basket_verified,
        data: $dataObject,
        cache: false,
        success: function ($response) {
            verify_Test05_Response( $response );
        },
        error: function( xhr, ajaxOptions, thrownError ){
            $('#waitAjax').hide();
            $message = xhr.responseJSON;
            bootbox.alert( {
                message: $message?$message.message:"Erreur Serveur",
                className: "boxSysErrorOne"
            });
        }
    });
}
function verify_Test05_Response( $response ){
    if( $response['result05'] == 'NOK' ){
        bootbox.dialog({
            message: $response['message05'],
            title: 'Erreur',
            className: "boxQuestionTwo",
            closeButton: false,
            buttons: {
                "Non": { label: $_translations[99], className: "btn-danger", callback: function() { $('#waitAjax').hide(); } },
                "Oui": { label: $_translations[98], className: "btn-success", callback: function() { verify_NextTest_Response( $response ); } }
            } });
    } else {
        verify_NextTest_Response( $response );
    }
}
function verify_NextTest_Response( $response ){
    if ($response['status'] == 'NOK') {
        $('#waitAjax').hide();
        bootbox.alert( {
            message: $response['message'],
            className: "boxErrorOne"
        });
    } else
        continue_After_BasketTests( true );
}
// Shift & Ctrl Select
var $lastRowClicked = -1;
var $lastActionUsed = -1; // -1 = No action, 0 = Select, 1 = unselect
var $multipleCheckInProgress = false;
var $shiftIsPressed = false;

var $_recheck = false;
var $_idArrayToReckeck = [];

// Main Table Events Response
function mainTableCheckRow( $checkbox ) {
    manageMultipleSelectUnselect(getRowIndexFromCheckbox($checkbox), 0);
    enableAddBasketButton();
}

function mainTableCheckAllRows(){
    resetMultipleSelect();
    enableAddBasketButton();
}

function mainTableUncheckRow( $checkbox ){
    manageMultipleSelectUnselect( getRowIndexFromCheckbox( $checkbox ), 1 );
    enableAddBasketButton();
}

function mainTableUncheckAllRows( ){
    resetMultipleSelect();
    enableAddBasketButton();
}

function mainTableDblClickRow( $row ){
    if( $_currentPage <= 6 || $_currentPage == 35 ){    // User Pages and Unlimited
        dblClickRow($row, null, null, $_currentButtons);
    } else {    // Archivist Page
        $oldLoc = null;
        if( $uawhat == UAWHAT_RELOC ){ if( RELOC_OLD_P.indexOf( $row['statuscaps']) >= 0 ) $oldLoc = 0; else $oldLoc = 1; }
        dblClickRow( $row, $uawhere, $oldLoc, $_currentButtons );
    }
}

function mainTablePageChange( $pageNo, $pageSize ){
    if( $pageSize != $_lastPageSize ) {
        $_lastPageSize = $pageSize;
        saveUserSettings_PageSize($_currentPage, $pageSize);
    }
    if( $pageNo != $_lastPageOffset ) {
        $_lastPageOffset = $pageNo;
    }
    doSearch();
    resetMultipleSelect();
}

function mainTableResetView( ){
    resetMultipleSelect();
}

function mainTablePostBody( $data ){
    // If Refresh after divLinkLocalization / divLinkBox or divLinkContainer, reCheck all
    if( $_recheck && $data ){
        for( $i=0; $i<$data.length; $i++){
            if( $_idArrayToReckeck.indexOf( $data[$i]['id'] ) >= 0 )
                $data[$i]['state'] = true;
        }
        $_idArrayToReckeck = [];
        $_recheck = false;
        // Hack to show checked ==> double toggle !!
        $_mainTable.bootstrapTable('toggleView');
        $_mainTable.bootstrapTable('toggleView');
    }
}

function resetMultipleSelect( ){
    $lastRowClicked = -1;
    $lastActionUsed = -1;
}

$(document).keydown(function(event){
    if(event.keyCode=="16")
        $shiftIsPressed = true;
});
$(document).keyup(function(event){
    if(event.keyCode=="16")
        $shiftIsPressed = false;
});


/* function to manage multiple select, ie ALT + check */
function manageMultipleSelectUnselect( rowIndex, action ){
    if( !$multipleCheckInProgress ) {
        if( $shiftIsPressed ) {
            if( $lastActionUsed == action ) { // i.e. 0=check, 1=uncheck
                if ($lastRowClicked == -1) {
                    $lastRowClicked = rowIndex;
                } else {
                    $multipleCheckInProgress = true; // to avoid interference of check event during ALT checking
                    if ($lastRowClicked <= rowIndex) {
                        var $minRow = $lastRowClicked;
                        var $maxRow = rowIndex;
                    } else {
                        var $minRow = rowIndex;
                        var $maxRow = $lastRowClicked;
                    }
                    if ($maxRow - $minRow >= 2) {
                        for (var $idxRow = $minRow + 1; $idxRow < $maxRow; $idxRow++)
                            if( action == 0 )
                                $_mainTable.bootstrapTable('check', $idxRow);
                            else
                                $_mainTable.bootstrapTable('uncheck', $idxRow);
                    }
                    $multipleCheckInProgress = false;
                }
            } else {
                $lastActionUsed = action;
                $lastRowClicked = rowIndex;
            }
        } else {
            $lastRowClicked = rowIndex;
            $lastActionUsed = action;
        }
    }
}

/* on client side index is for all of fame; with server side, index is only in current page */
function getRowIndexFromCheckbox( checkbox ){
    var $BTOptions = $_mainTable.bootstrapTable( 'getOptions' );
    var $BTOffset = 0;
    if( $BTOptions.sidePagination === "client" )
        $BTOffset = ($BTOptions.pageNumber-1)*$BTOptions.pageSize;
    if( $BTOptions.cardView )
        var $trRow = checkbox.parent().parent().parent(); // In cardViewMode
    else
        var $trRow = checkbox.parent().parent(); // In tableViewMode
    return $trRow.index()+$BTOffset;
}

var $UATableList = null;

function initUATableList( $table ){
    $UATableList = $table;
    $('#divUncheckAll').hide();
}

$('#divCheckAll').click( function() {
    if( $UATableList ) {
        $UATableList.bootstrapTable('checkAll');
        $('#divCheckAll').hide();
        $('#divUncheckAll').show();
    } else
        bootbox.alert( {
            message: 'Une erreur de configuration est survenue !',
            className: "boxSysErrorOne"
        })
});
$('#divUncheckAll').click( function() {
    if( $UATableList ) {
        $UATableList.bootstrapTable('uncheckAll');
        $('#divCheckAll').show();
        $('#divUncheckAll').hide();
    } else
        bootbox.alert( {
            message: 'Une erreur de configuration est survenue !',
            className: "boxSysErrorOne"
        })
});

//=====================================================================================================
// ARCHIVIST OPTIMIZATION MODAL
// Goal:
//      This renders the Optimization modal popup
// Entry point: function updatePopupOptimizationModal_v3
//-----------------------------------------------------------------------------------------------------
// Remarks:
//      Version 03 ( Take care of Box in Container and Master PreCheck if all insiders are checked )
//                  Algorythm is rewritten completely from v2
//=====================================================================================================
// Algorythm:
//=====================================================================================================

//-----------------------------------------------------------------------------------------------------
// Constants for this module
const DEBUG_OPTIMIZATION_MODAL = true;        // debugging mode
//.....................................................................................................
// Allowed statuses to be checked
const NOT_DEL_STATUS_ALLOWED = [ 'DISP', 'GLAP', 'GPAP', 'GRLPDAI', 'GRLPDAINT', 'CLAP', 'CPAP',
    'CRLPDAI', 'CRLPDAINT' ];
const DEL_STATUS_ALLOWED = [ 'DISP', 'GDAP', 'CDAP' ];
//.....................................................................................................
// Type of structs
const OPTIM_CONTAINER   = 3;
const OPTIM_SUBBOX      = 2;
const OPTIM_BOX         = 1;
const OPTIM_OTHER       = 0;

// Default Texts
const OPTIM_CONTAINER_LABEL = 'Conteneur';
const OPTIM_BOX_LABEL       = 'Boîte';
const OPTIM_DOC_LABEL       = 'Dossier';
const OPTIM_ROA_LABEL       = 'Archives non optimisables';
const OPTIM_NAME_LABEL      = 'Libellé';

//.....................................................................................................
var OPTIM_b_First_Panel             = true;             // first panel is open by default, others are closed
var OPTIM_statuses_allowed          = null;
var OPTIM_UA_Selected_In_Basket     = null;
var OPTIM_del_Mode                  = false;

// Update $('#OptimisationModalBody') or $('#DelOptimisationModalBody')

//-----------------------------------------------------------------------------------------------------
// Entry Point

//.....................................................................................................
// function:    updatePopupOptimizationModal_v3
// purpose:     Generates the popup dialog for optimization
// parameters:
//      - $optimizedList: List of UAs needed for optimization (calculated by the server), we assume that server returns
//                        this list sorted by container, then boxes, then documents, then service,
//      - $alreadyCheckedListID: List of IDs of UAs selected by the user
//      - $delMode: true for delMode
// return:  N/A

function updatePopupOptimizationModal_v3( $optimizedList, $alreadyCheckedListID, $delMode ) {
    if (DEBUG_OPTIMIZATION_MODAL) console.log('ENTER updatePopupOptimizationModal_v3');
    OPTIM_b_First_Panel = true;

    OPTIM_del_Mode = $delMode;
    OPTIM_statuses_allowed = OPTIM_del_Mode ? DEL_STATUS_ALLOWED : NOT_DEL_STATUS_ALLOWED;
    OPTIM_UA_Selected_In_Basket = $alreadyCheckedListID;

    let $optimModal = OPTIM_del_Mode ? $('#DelOptimisationModalBody') : $('#OptimisationModalBody');

    //..................................................................
    // Analyse objects and prepare listObject
    let $listObjects = analyseAndPrepareListObjects( $optimizedList );

    //..................................................................
    // Analyse objects and calculate scripts, cannot be done in first pass
    if( !OPTIM_del_Mode )
        $listObjects = analyseAndCalculateScripts( $listObjects );

    //..................................................................
    // Render objects
    let $html = renderObjects( $listObjects );
    $optimModal.html( $html );
}

//-----------------------------------------------------------------------------------------------------
//  Functions used by entry point

//.....................................................................................................
// function:  analyseAndPrepareListObjects
// Purpose: analyse all UA, and makes structs to prepare rendering
// Parameters:
//      - $optimizedList: List of UAs needed for optimization (calculated by the server), we assume that server returns
//                        this list sorted by container, then boxes, then documents, then service,

function analyseAndPrepareListObjects( $optimizedList ){
    let $currentContainer = createNewOptimStruct( OPTIM_CONTAINER );
    let $currentSubBox = createNewOptimStruct( OPTIM_SUBBOX );
    let $currentBox = createNewOptimStruct( OPTIM_BOX );
    let $otherUAs = createNewOptimStruct( OPTIM_OTHER );
    $otherUAs = initOptimStruct( null, $otherUAs );
    var $listObjects = { 'containers': [], 'boxes': [], 'others': $otherUAs };
    let $currentSUID = null;

    //..................................................................
    // Iteration through optimized list given by server
    for( let $i = 0, $len = $optimizedList.length; $i < $len; $i++ ){

        let $UALine = $optimizedList[$i];
        if (DEBUG_OPTIMIZATION_MODAL) console.log('== Current Line: ' + $i + ' ==');
        if (DEBUG_OPTIMIZATION_MODAL) console.log( debugLogUALine( $UALine ));

        if( $UALine['containernumber'] != null ){
            // This line is in a container
            if (DEBUG_OPTIMIZATION_MODAL) console.log('UA in container : ' + $UALine['containernumber'] );

            if( $UALine['containernumber'] == $currentContainer.identification && $UALine['suid'] == $currentSUID ){
                // This line is in the same container than previous one
                if (DEBUG_OPTIMIZATION_MODAL) console.log('same container than previous UA !' );

                if( $UALine['boxnumber'] != null ){
                    // This line is in a subbox
                    if (DEBUG_OPTIMIZATION_MODAL) console.log('UA in subbox : ' + $UALine['boxnumber'] );

                    if( $UALine['boxnumber'] == $currentSubBox.identification ){
                        // This line is in the same subbox than previous one
                        if (DEBUG_OPTIMIZATION_MODAL) console.log('same subbox than previous UA !' );

                        // Add line to current subbox
                        $currentSubBox = addUAtoOptimStruct( $UALine, $currentSubBox );
                        if (DEBUG_OPTIMIZATION_MODAL) console.log('Add line to subbox !' );

                    } else {
                        // This line is not in the same subbox than previous one
                        if (DEBUG_OPTIMIZATION_MODAL) console.log('This UA is in a new subbox !' );

                        // Add current subbox to container if exist
                        if( $currentSubBox.identification ) {
                            $currentContainer = addSubboxToContainer( $currentSubBox, $currentContainer);
                            $currentSubBox = createNewOptimStruct( OPTIM_SUBBOX );
                            if (DEBUG_OPTIMIZATION_MODAL) console.log('Add previous subbox to container and create new one !' );
                        }
                        $currentSubBox = initOptimStruct( $UALine, $currentSubBox );

                        $currentSubBox = addUAtoOptimStruct( $UALine, $currentSubBox );
                        if (DEBUG_OPTIMIZATION_MODAL) console.log('Add line to new subbox !' );

                    }

                } else {
                    // This line is not in a subbox
                    if (DEBUG_OPTIMIZATION_MODAL) console.log('UA not in subbox' );

                    // Add current subbox to container if exist
                    if( $currentSubBox.identification ) {
                        $currentContainer = addSubboxToContainer( $currentSubBox, $currentContainer );
                        $currentSubBox = createNewOptimStruct( OPTIM_SUBBOX );
                        if (DEBUG_OPTIMIZATION_MODAL) console.log('Add previous subbox to container and create new one !' );
                    }

                    // Add line to others of container
                    $currentContainer = addUAtoOptimStruct( $UALine, $currentContainer );
                    if (DEBUG_OPTIMIZATION_MODAL) console.log('Add line to others in container !' );

                }

            } else {
                // This line is not in the same container than previous one
                if (DEBUG_OPTIMIZATION_MODAL) console.log('This UA is in a new container !' );

                if( $currentContainer.identification ) {
                    OPTIM_b_First_Panel = false;
                    // Add previous subbox to previous container if exist
                    if ($currentSubBox.identification) {
                        $currentContainer = addSubboxToContainer( $currentSubBox, $currentContainer );
                        $currentSubBox = createNewOptimStruct( OPTIM_SUBBOX );
                        if (DEBUG_OPTIMIZATION_MODAL) console.log('Add previous subbox to container and create new one if needed !');
                    }
                    // Add previous container to list of containers
                    $listObjects = addObjectToList( $currentContainer, $listObjects );
                    $currentContainer = createNewOptimStruct( OPTIM_CONTAINER );
                    if (DEBUG_OPTIMIZATION_MODAL) console.log('Add previous container to list and create new one !');
                }
                $currentContainer = initOptimStruct( $UALine, $currentContainer );

                if( $UALine['boxnumber'] != null ){
                    // This line is in a subbox
                    $currentSubBox = initOptimStruct( $UALine, $currentSubBox );
                    if (DEBUG_OPTIMIZATION_MODAL) console.log('UA in subbox : ' + $UALine['boxnumber'] );

                    // It must be a new subbox because it is a new container !
                    $currentSubBox = addUAtoOptimStruct( $UALine, $currentSubBox );
                    if (DEBUG_OPTIMIZATION_MODAL) console.log('Add line to new subbox !' );

                } else {
                    // This line is not in a subbox
                    if (DEBUG_OPTIMIZATION_MODAL) console.log('This UA is not in a subbox !' );

                    // Add line to others of container
                    $currentContainer = addUAtoOptimStruct( $UALine, $currentContainer );
                    if (DEBUG_OPTIMIZATION_MODAL) console.log('Add line to others in container !' );
                }

            }

        } else {
            // This line is not in a container
            if (DEBUG_OPTIMIZATION_MODAL) console.log('UA not in container' );

            // Verify if there is a subox / container to close
            if( $currentContainer.identification ){
                if( $currentSubBox.identification ){
                    $currentContainer = addSubboxToContainer( $currentSubBox, $currentContainer );
                    $currentSubBox = createNewOptimStruct( OPTIM_SUBBOX );
                    if (DEBUG_OPTIMIZATION_MODAL) console.log('Add previous subbox to container and create new one !');
                }
                // Add previous container to list of containers
                $listObjects = addObjectToList( $currentContainer, $listObjects );
                $currentContainer = createNewOptimStruct( OPTIM_CONTAINER );
                OPTIM_b_First_Panel = false;
                if (DEBUG_OPTIMIZATION_MODAL) console.log('Add previous container to list and create new one !');
            }

            if( $UALine['boxnumber'] != null ){
                // This line is in a box
                if (DEBUG_OPTIMIZATION_MODAL) console.log('UA in box : ' + $UALine['boxnumber'] );

                if( $UALine['boxnumber'] == $currentBox.identification && $UALine['suid'] == $currentSUID ){
                    // This line is in the same box
                    if (DEBUG_OPTIMIZATION_MODAL) console.log('UA in same box' );

                    $currentBox = addUAtoOptimStruct( $UALine, $currentBox );
                    if (DEBUG_OPTIMIZATION_MODAL) console.log('Add line to box' );

                } else {
                    // This line is not in the same box
                    if (DEBUG_OPTIMIZATION_MODAL) console.log('UA not in same box' );

                    // Verify if there is a previous box
                    if( $currentBox.identification ){
                        $listObjects = addObjectToList( $currentBox, $listObjects );
                        $currentBox = createNewOptimStruct( OPTIM_BOX );
                        OPTIM_b_First_Panel = false;
                        if (DEBUG_OPTIMIZATION_MODAL) console.log('Add previous box to list and create a new one' );
                    }
                    $currentBox = initOptimStruct( $UALine, $currentBox );

                    $currentBox = addUAtoOptimStruct( $UALine, $currentBox );
                    if (DEBUG_OPTIMIZATION_MODAL) console.log('Add line to box' );

                }

            } else {
                // This line is not in a box
                if (DEBUG_OPTIMIZATION_MODAL) console.log('UA not in box' );

                // Verify it there is a subbox / container to close
                if( $currentContainer.identification ){
                    OPTIM_b_First_Panel = false;
                    if( $currentSubBox.identification ){
                        $currentContainer = addSubboxToContainer( $currentSubBox, $currentContainer );
                        $currentSubBox = createNewOptimStruct( OPTIM_SUBBOX );
                        if (DEBUG_OPTIMIZATION_MODAL) console.log('Add previous subbox to container and create new one !');
                    }
                    // Add previous container to list of containers
                    $listObjects['containers'].push( $currentContainer );
                    $currentContainer = createNewOptimStruct( OPTIM_CONTAINER );
                    if (DEBUG_OPTIMIZATION_MODAL) console.log('Add previous container to list and create new one !');
                }

                // Verify if there is a box to close
                if( $currentBox.identification ){
                    $listObjects = addObjectToList( $currentBox, $listObjects );
                    $currentBox = createNewOptimStruct( OPTIM_BOX );
                    OPTIM_b_First_Panel = false;
                }

                // Add line to others
                $otherUAs = addUAtoOptimStruct( $UALine, $otherUAs );
                if (DEBUG_OPTIMIZATION_MODAL) console.log('Add line to others !');

            }

        }

        $currentSUID = $UALine['suid'];
    } // End of for loop

    // Verify it there is a subbox / container to close
    if( $currentContainer.identification ){
        if( $currentSubBox.identification ){
            $currentContainer = addSubboxToContainer( $currentSubBox, $currentContainer );
            if (DEBUG_OPTIMIZATION_MODAL) console.log('Add previous subbox to container !');
        }
        // Add previous container to list of containers
        $listObjects = addObjectToList( $currentContainer, $listObjects );
        if (DEBUG_OPTIMIZATION_MODAL) console.log('Add previous container to list  !');
    }

    // Verify if there is a box to close
    if( $currentBox.identification ){
        $listObjects = addObjectToList( $currentBox, $listObjects );
        if (DEBUG_OPTIMIZATION_MODAL) console.log('Add previous box to list  !');
    }

    return $listObjects;
}

//.....................................................................................................
// Function create new struct
// Params:
//      - structType: type of struct we want to create

function createNewOptimStruct( $structType, $UALine ){
    var $emptyNewStruct = {
        identification  : null,                 // Identification of object
        type            : $structType,          // Type of structure we are dealing with
        size            : 0,                    // size of package (avoid calculating length of lines[]
        lines           : [],                   // UA in this object

        output          : {                     // output struct to help generate dialog
            checkboxID  : '',                   // id of the checkbox (document / box / subbox / container)
            header      : {                     //
                begin   : '',
                end     : ''
            },
            content     : {
                begin   : ''
            },
            script      : {                     //
                begin   : '',
                on      : '',
                off     : ''
            }
        }
    };
    if( $structType != OPTIM_OTHER ){           // If we are describing a package, add a verification structure to it
        $emptyNewStruct['verif'] = {
            bAllInBasket            : true,     // All UA in the package have been asked in basket by customer
            bOneNotWellIdentified   : false,    // There is at least one UA not well identified in this package
            bOneNotAuthorized       : false,    // There is at least one UA not authorized to be checked in this package
            bAsked                  : false     // This package has the Asked status (boxasked or containerasked)
        };

        if( $structType == OPTIM_CONTAINER )    // If we are describing a container, add subboxes list to it
            $emptyNewStruct['subboxes'] = [];
    }
    return $emptyNewStruct;
}
//.....................................................................................................
// Function initialize struct
// Params:
//      - $UALine: line to init optim struct
//      - $optimStruct: struct to be initialized

function initOptimStruct( $UALine, $optimStruct ){

    $optimStruct.identification = ( $optimStruct.type == OPTIM_CONTAINER ) ?
        $UALine['containernumber'] :
        ( $optimStruct.type == OPTIM_BOX || $optimStruct.type == OPTIM_SUBBOX ) ?
            $UALine['boxnumber'] :
            'ROA';

    let $nameTrimed = '';
    let $panelID = '';
    let $panelHeadID = '';
    let $panelCheckboxID = '';
    let $panelCollapseID = '';
    let $nameIdentification = '';
    let $valueCheckbox = -1;

    switch( $optimStruct.type ){
        case OPTIM_CONTAINER:
        case OPTIM_SUBBOX:
            $nameTrimed = $UALine['containernumber'].replace(/\s+/g, '') + '_' + $UALine['suid'] +
                (( $optimStruct.type == OPTIM_SUBBOX ) ? '_SB_' + $UALine['boxnumber'] : '');
            $panelID = 'pContainer_' + $nameTrimed;
            $panelHeadID = 'phContainer_' + $nameTrimed;
            $panelCheckboxID = 'C_' + $nameTrimed;
            $panelCollapseID = 'pcContainer_List_' + $nameTrimed;
            $nameIdentification = ' ' + (( $optimStruct.type == OPTIM_CONTAINER ) ? OPTIM_CONTAINER_LABEL : OPTIM_BOX_LABEL )
                + ' ' + $optimStruct.identification;
            break;
        case OPTIM_BOX:
            $nameTrimed = $UALine['boxnumber'].replace(/\s+/g, '') + '_' + $UALine['suid'];
            $panelID = 'pBox_' + $nameTrimed;
            $panelHeadID = 'phBox_' + $nameTrimed;
            $panelCheckboxID = 'B_' + $nameTrimed;
            $panelCollapseID = 'pcBox_List_' + $nameTrimed;
            $nameIdentification = ' ' + OPTIM_BOX_LABEL + ' ' + $optimStruct.identification;
            break;
        default:
            $panelID = 'pRoa';
            $panelHeadID = 'phRoa';
            $panelCollapseID = 'pcRoa_List';
            $nameIdentification = ' ' + OPTIM_ROA_LABEL + ' ';
            break;
    }

    let $color = 'default';
    if( $optimStruct.type == OPTIM_CONTAINER ) $color = 'success';
    if( $optimStruct.type == OPTIM_BOX || $optimStruct.type == OPTIM_SUBBOX ) $color = 'info';

    $optimStruct.output.checkboxID = $panelCheckboxID;

    $optimStruct.output.header.begin = '<div class="panel panel-'+$color+' mb5" id="' + $panelID + '"> ' +
        '<div class="panel-heading" role="tab" id="' + $panelHeadID + '"> ' +
        '<h4 class="panel-title">';
    if( $optimStruct.type != OPTIM_OTHER )
        $optimStruct.output.header.begin += '<input type="checkbox" id="' + $panelCheckboxID + '" value="' + $valueCheckbox + '" ';

    $optimStruct.output.header.end = ( $optimStruct.type != OPTIM_OTHER ) ? '/>' : '';
    $optimStruct.output.header.end += '<a class="' + ( !OPTIM_b_First_Panel ? 'collapsed' : '' ) +
        '" data-toggle="collapse" data-parent="#OptimizationModalBody" href="#' + $panelCollapseID +
        '" aria-expanded="' + ( OPTIM_b_First_Panel ? 'true' : 'false' ) +
        '" aria-controls="' + $panelCollapseID + '">' +
        $nameIdentification + '</a></h4></div>';

    $optimStruct.output.content.begin = '<div id="' + $panelCollapseID + '" class="panel-collapse collapse list-group p2 ' +
        ( OPTIM_b_First_Panel ? 'in' : '' ) +
        '" role="tabpanel" aria-labelledby="' + $panelHeadID + '">';

    if( $optimStruct.type != OPTIM_OTHER )
        $optimStruct.output.script.begin = "<script>$('#" + $panelCheckboxID + "').change(function(){ if(this.checked){ ";

    return $optimStruct;
}

//.....................................................................................................
// Function add line to struct with verification
// Params:
//      - $UALine: line to add
//      - $optimStruct: struct to add into

function addUAtoOptimStruct( $UALine, $optimStruct ){

    $optimStruct.size++;
    $optimStruct.lines.push( $UALine );

    // Update verif states of current struct:
    if( $optimStruct.type != OPTIM_OTHER ){
        let $bWellIdentified = true;
        let $bAuthorized = true;

        // bAllInBasket            All UA in the package have been asked in basket by customer
        if( OPTIM_UA_Selected_In_Basket.indexOf($UALine['id']) < 0 )
            $optimStruct.verif.bAllInBasket = false;

        // bOneNotWellIdentified    There is at least one UA not well identified in this package
        // ==> to be compared with package size, if package size > 1, all package MUST be asked
        switch( $optimStruct.type ){
            case OPTIM_CONTAINER:
                if(( $UALine['boxnumber'] == null || $UALine['boxnumber'] == '' )&&
                    ( $UALine['documentnumber'] == null || $UALine['boxnumber'] == '' )) {
                    $optimStruct.verif.bOneNotWellIdentified = true;
                    $bWellIdentified = false;
                }
                break;
            case OPTIM_SUBBOX:
            case OPTIM_BOX:
                if( $UALine['documentnumber'] == null || $UALine['documentnumber'] == '' ) {
                    $optimStruct.verif.bOneNotWellIdentified = true;
                    $bWellIdentified = false;
                }
                break;
        }

        // bOneNotAuthorized        There is at least one UA not authorized to be checked in this package
        if( $.inArray($UALine['statuscaps'], OPTIM_statuses_allowed ) < 0 ) {
            $optimStruct.verif.bOneNotAuthorized = true;
            $bAuthorized = false;
        }

        // bAsked                   This package has the Asked status (boxasked or containerasked)
        if( $optimStruct.type == OPTIM_CONTAINER && $UALine['containerasked'] )
            $optimStruct.verif.bAsked = true;
        if( $optimStruct.type == OPTIM_BOX && $UALine['boxasked'] )
            $optimStruct.verif.bAsked = true;
        // If container is asked, subboxes are asked by inheritance
        if( $optimStruct.type == OPTIM_SUBBOX && ( $UALine['containerasked'] || $UALine['boxasked'] ) )
            $optimStruct.verif.bAsked = true;
    }

    return $optimStruct;
}

//.....................................................................................................
// Function add subbox to container with verification
// Params:
//      - $subbox: an optimstruct representing a subbox
//      - $container: an optimstruct representing a container

function addSubboxToContainer( $subbox, $container ){

    if( $subbox.type != OPTIM_SUBBOX || $container.type != OPTIM_CONTAINER )
        return null;

    // Consider subbox as a "line" for calculation
    $container.size++;
    $container.subboxes.push( $subbox );

    // B#345: If subbox flag 'bAllInBasket' is false, set bAllInBasket to false for container also
    if( !$subbox.verif.bAllInBasket )
        $container.verif.bAllInBasket = false;

    // B#345: If subbox has only 1 line, and set as NotWellIdentified, just change that because it's well identified in fact
    verifyBOneNotWellIdentified( $subbox );

    return $container;
}

//.....................................................................................................
// Function add object to listobject in the right section
// Params:
//      - $object: an optimstruct representing the object to add
//      - $listobject: the listobject to add into

function addObjectToList( $object, $list ){

    if( $object.type == OPTIM_CONTAINER || $object == OPTIM_BOX )
        $object = verifyBOneNotWellIdentified( $object );           // B#345

    switch( $object.type ){
        case OPTIM_BOX:
            $list['boxes'].push( $object );
            break;
        case OPTIM_CONTAINER:
            $list['containers'].push( $object );
            break;
        default:
            break;
    }

    return $list;
}

//.....................................................................................................
// Function verify bOneNotWellIdentified coherency
// Params:
//      - $object: an optimstruct representing a object to verify

function verifyBOneNotWellIdentified( $optimstruct ){

    // B#345: If subbox has only 1 line, and set as NotWellIdentified, just change that because it's well identified in fact
    if( $optimstruct.verif.bOneNotWellIdentified && $optimstruct.size == 1 )
        $optimstruct.verif.bOneNotWellIdentified = false;

    return $optimstruct;
}

//.....................................................................................................
// Script calculation Part
//.....................................................................................................
//
// Params:
//      - $listObjects: all objects containers (with eventually subboxes), boxes and documents; prepared, ordered, grouped and analyzed
function analyseAndCalculateScripts( $listObjects ){
   // Calculate scripts for Containers
    if( $listObjects['containers'] && $listObjects['containers'].length > 0 )
        $listObjects['containers'].forEach( function( $container, $index ){
            this[$index] = calculateContainerScript( $container );
        });

   // Calculate scripts for boxes
    if( $listObjects['boxes'] && $listObjects['boxes'].length > 0 )
        $listObjects['boxes'].forEach( function( $box, $index ){
            this[$index] = calculateBoxScript( $box );
        });

    return $listObjects;
}

//.....................................................................................................
// function
// When container checked, all boxes are unchecked and grayed, and all lines are checked (if possible) and grayed
// When container unchecked, only well formed boxes are ungrayed, bad formed are grayed and checked
function calculateContainerScript( $container ) {

    $container.subboxes.forEach( function( $subbox, $index ){
        $container.output.script.on += " $('#" + $subbox.output.checkboxID + "').prop('checked', false ); ";
        $container.output.script.on += " $('#" + $subbox.output.checkboxID + "').prop('disabled', true ); ";

        if( $subbox.verif.bOneNotWellIdentified )   // We must re-check the subbox because one ua is not well identified
            $container.output.script.off += " $('#" + $subbox.output.checkboxID + "').prop('checked', true ); ";

        if( !$subbox.verif.bOneNotWellIdentified )  // We can enabled box only if there is not one bad identified
            $container.output.script.off += " $('#" + $subbox.output.checkboxID + "').prop('disabled', false ); ";

        $subbox.lines.forEach( function( $line ){
            if( $.inArray($line['statuscaps'], OPTIM_statuses_allowed) >= 0 )
                $container.output.script.on += " $('#cb" + $line['id'] + "').prop('checked', true); ";
            $container.output.script.on += " $('#cb" + $line['id'] + "').prop('disabled', true); ";

            $container.output.script.off += " $('#cb" + $line['id'] + "').prop('checked', false); ";

            if( !$subbox.verif.bOneNotWellIdentified )
                $container.output.script.off += " $('#cb" + $line['id'] + "').prop('disabled', false); ";
        });

        // Calculate subboxes scripts
        this[$index] = calculateBoxScript( $subbox );

    });
    $container.lines.forEach( function( $line ){
        if( $.inArray($line['statuscaps'], OPTIM_statuses_allowed) >= 0 ){
            $container.output.script.on += createUAToggleScript( $line, true, $container.verif.bOneNotWellIdentified );
            $container.output.script.off += createUAToggleScript( $line, false, $container.verif.bOneNotWellIdentified );
        }
    });

}

//.....................................................................................................
// function
function calculateBoxScript( $box ) {

    $box.lines.forEach( function ( $line ){
        if( $.inArray($line['statuscaps'], OPTIM_statuses_allowed) >= 0 ) {     // Authorized
            $box.output.script.on += createUAToggleScript( $line, true, $box.verif.bOneNotWellIdentified );
            $box.output.script.off += createUAToggleScript( $line, false, $box.verif.bOneNotWellIdentified );
        }
    });
}

//.....................................................................................................
// function
function createUAToggleScript( $ua, $bOnOff, $oneNotWellIdentified ){
    var $html_script = '';

    $html_script += " $('#cb" + $ua['id'] + "').prop('checked', " + ($bOnOff ? 'true' : 'false') + "); ";
    if( !$oneNotWellIdentified )
        $html_script += " $('#cb" + $ua['id'] + "').prop('disabled', " + ($bOnOff ? 'true' : 'false') + "); ";

    return $html_script;
}

//.....................................................................................................
// Rendering Part
//.....................................................................................................
// Function to render the list object into the popup
// Params:
//      - $listObjects: all objects containers (with eventually subboxes), boxes and documents; prepared, ordered, grouped and analyzed

function renderObjects( $listObjects ){
    var $html = '';

    // Render containers
    if( $listObjects['containers'] && $listObjects['containers'].length > 0 )
        $listObjects['containers'].forEach( function( $container ){
            $html += renderContainer( $container );
        });

    // Render boxes
    if( $listObjects['boxes'] && $listObjects['boxes'].length > 0 )
        $listObjects['boxes'].forEach( function( $box ){
            $html += renderBox( $box, false );
        });

    // Render others
    $html += renderOtherUAs( $listObjects['others'] ) ;

    return $html;
}

//.....................................................................................................
function renderContainer( $container ) {
    var $content_container = $container.output.header.begin;

    if( $container.verif.bAllInBasket || $container.verif.bAsked )
        $content_container += ' checked="checked" ';
    if( OPTIM_del_Mode || $container.verif.bOneNotAuthorized /*|| ( $container.verif.bOneNotWellIdentified && $container.size > 1 )*/ )
        $content_container += ' disabled="disabled" ';

    let $bAboveChecked = $container.verif.bAllInBasket || $container.verif.bAsked;

    $content_container += $container.output.header.end;

    $content_container += $container.output.content.begin;

    $container.subboxes.forEach( function( $subbox ){
        $content_container += renderBox( $subbox, $bAboveChecked );
    });

    $container.lines.forEach( function( $line ){
        let $bInBasket = ( OPTIM_UA_Selected_In_Basket.indexOf( $line['id']) >= 0 );
        let $bAllowed = ( $.inArray( $line['statuscaps'], OPTIM_statuses_allowed ) >= 0 );

        $content_container += renderOneLine( $line['id'], $line['documentnumber'], $line['name'], $line['statuscaps'],
            $bInBasket, $bAllowed, $bAboveChecked );
    });

    $content_container += '</div></div>';

    $content_container += $container.output.script.begin + $container.output.script.on + ' } else { ' + $container.output.script.off + ' }});</script>';

    return $content_container;
}

//.....................................................................................................
function renderBox( $box, $aboveChecked ){
    var $content_box = $box.output.header.begin;

    if( !$aboveChecked && ( $box.verif.bAllInBasket || $box.verif.bAsked ) )
        $content_box += ' checked="checked" ';
    if( OPTIM_del_Mode || $aboveChecked || $box.verif.bOneNotAuthorized /*|| ( $box.verif.bOneNotWellIdentified && $box.size > 1 )*/ )
        $content_box += ' disabled="disabled" ';

    let $bAboveChecked = $box.verif.bAllInBasket || $box.verif.bAsked  || $aboveChecked;

    $content_box += $box.output.header.end;

    $content_box += $box.output.content.begin;

    $box.lines.forEach( function( $line ){
        let $bInBasket = ( OPTIM_UA_Selected_In_Basket.indexOf( $line['id']) >= 0 );
        let $bAllowed = ( $.inArray( $line['statuscaps'], OPTIM_statuses_allowed ) >= 0 );

        $content_box += renderOneLine( $line['id'], $line['documentnumber'], $line['name'], $line['statuscaps'],
            $bInBasket, $bAllowed, $bAboveChecked );
    });

    $content_box += '</div></div>';

    $content_box += $box.output.script.begin + $box.output.script.on + ' } else { ' + $box.output.script.off + ' }});</script>';

    return $content_box;
}

//.....................................................................................................
function renderOtherUAs( $others ){
    if( $others.size <= 0 )
        return '';

    var $content_otherUAs = $others.output.header.begin;

    if( OPTIM_del_Mode )
        $content_otherUAs += ' disabled="disabled" ';

    $content_otherUAs += $others.output.header.end;

    $content_otherUAs += $others.output.content.begin;

    $others.lines.forEach( function( $line ){
        let $bInBasket = ( OPTIM_UA_Selected_In_Basket.indexOf( $line['id']) >= 0 );
        let $bAllowed = ( $.inArray( $line['statuscaps'], OPTIM_statuses_allowed ) >= 0 );

        $content_otherUAs += renderOneLine( $line['id'], $line['documentnumber'], $line['name'], $line['statuscaps'],
            $bInBasket, $bAllowed, false );
    });

    $content_otherUAs += '</div></div>';

    return $content_otherUAs;
}

//.....................................................................................................
function renderOneLine( $id, $document, $label, $status, $inBasket, $allowed, $aboveChecked ){
    var $content_line = '<span class="list-group-item ';

    // Color depending on line state
    $content_line += $inBasket ? 'text-primary' : $allowed ? 'text-default' : 'text-danger' ;

    // Checkbox
    $content_line += '"><input type="checkbox" id="cb' + $id + '" value="' + $id + '" ';
    if( $inBasket ) $content_line += ' checked="checked" ';
    if( !$allowed || $aboveChecked ) $content_line += ' disabled="disabled" ';
    $content_line += '/>&nbsp;';

    // Label
    let $bFirst = true;
    if( $document != null && $document.length > 0 ){
        if( $bFirst ) $bFirst = false;
        $content_line += OPTIM_DOC_LABEL + ': ' + $document;
    }
    if( !$bFirst ) $content_line += ' / ';
    $content_line += OPTIM_NAME_LABEL + ': ' + $label;

    if( !$allowed ) $content_line += ' [' + $status + '] ';

    $content_line += '</span>';

    return $content_line;
}

//.....................................................................................................
function debugLogUALine( $UALine ){
    console.log( 'id:' + $UALine['id'] + ' | suid:' + $UALine['suid'] + ' | provider:' + $UALine['provider'] + ' | container:'
    + $UALine['containernumber'] + ' | box:' + $UALine['boxnumber'] + ' | document:' + $UALine['documentnumber']
    + ' | name:' + $UALine['name'] + ' | status:' + $UALine['statuscaps'] + ' | C,B:' + $UALine['containerasked'] + ',' + $UALine['boxasked']);
}

function exportArray( $table, $whereAmI, $state, $what, $where, $how, $filter_provider, $listUAs ){
    if( $table.bootstrapTable('getData').length <= 0 )
        return;

    $listColumn = [];
    $columns = $table.bootstrapTable('getOptions').columns[0];
    for( $i = 0; $i < $columns.length; $i++ ){
        $column = $columns[$i];
        if ( $column['visible'] == true && $column['field'] != 'state' )
            $listColumn.push( $column['field'] );
    }

    if( $listUAs ){
        $listId = []; // null for all / list for select
        var $selection = $table.bootstrapTable('getSelections');
        if( $selection.length <= 0 )
            return;
        for ($i = 0; $i < $selection.length; $i++)
            $listId.push($selection[$i]['id']);
    } else
        $listId = null;

    if( $listUAs ) {

        get(window.JSON_URLS.bs_idp_archive_export, {
            'listId': JSON.stringify( $listId ),
            'listColumn': JSON.stringify( $listColumn ),
            'xpsearch': JSON.stringify( null ),
            'whereAmI': null
        }, false);

    } else {
        // New for asynchronous print
        $searchParameters = [ null, null, null, null, null, null, null, null, null, null, null, null, null, null, null,
            null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null];


        switch ($whereAmI) {
            case 1:     // Transfert
                $searchParameters[20] = $options.searchText; // 'special': $options.searchText
                break;
            case 2:     // Consult
            case 3:     // Return
            case 4:     // Exit
            case 5:     // Destroy
            case 28:    // Reloc
                if ($('#service option:selected').val() == -1) // No service selected
                    $service = null;
                else
                    $service = $('#service option:selected').val();

                $searchParameters[0] = $service;
                $searchParameters[1] = $('#legalentity option:selected').val();
                $searchParameters[2] = $('#description1 option:selected').val();
                $searchParameters[3] = $('#description2 option:selected').val();
                $searchParameters[4] = $('#name').val();
                $searchParameters[5] = $('#limitnum').val();
                $searchParameters[6] = $('#limitalpha').val();
                $searchParameters[7] = $('#limitalphanum').val();
                $searchParameters[8] = $('#limitdate').val();
                $searchParameters[9] = $('#ordernumber').val();
                $searchParameters[10] = $('#budgetcode option:selected').val();
                $searchParameters[11] = $('#documentnature option:selected').val();
                $searchParameters[12] = $('#documenttype option:selected').val();
                $searchParameters[13] = $('#closureyear').prop("value");
                $searchParameters[14] = $('#destructionyear').prop("value");
                $searchParameters[15] = $('#documentnumber').val();
                $searchParameters[16] = $('#boxnumber').val();
                $searchParameters[17] = $('#containernumber').val();
                $searchParameters[18] = $('#provider option:selected').val();
                $searchParameters[19] = $('#unlimited').val();
                $searchParameters[20] = $options.searchText;

                if ($filters) {
                    $searchParameters[21] = $filters['filterstatus'];
                    $searchParameters[22] = $filters['filterwhere'];
                    $searchParameters[23] = $filters['filterwith'];
                    $searchParameters[24] = $filters['filterlocalization'];
                }
                break;
            case 6:     // Manage user wants, Manage Provider wants, Close user wants
                $f_prov = -1;
                if( $xpstate == UASTATE_MANAGEPROVIDER )
                    $f_prov = $filter_provider;
                else {
                    if( $xpstate == UASTATE_MANAGECLOSE && $uawhat == UAWHAT_TRANSFER && $uawhere == UAWHERE_PROVIDER )
                        $f_prov = $filter_provider;
                }
                $searchParameters[20] = $options.searchText;
                $searchParameters[25] = $state;
                $searchParameters[26] = $what;
                $searchParameters[27] = $where;
                $searchParameters[28] = $how;
                $searchParameters[29] = null /* $uawith */ ;
                $searchParameters[30] = $f_prov;
        }

        // Just ask the server to begin the export process, and return to ihm
        $.ajax({
            type: "GET",
            url: window.JSON_URLS.bs_idp_archive_export_offline,
            data: {
                'listId': JSON.stringify( null ),
                'listColumn': JSON.stringify( $listColumn ),
                'xpsearch': JSON.stringify( $searchParameters ),
                'whereAmI': $whereAmI },
            cache: false,
            success: function( data, status ){
                $('#userFileResume').html( '<a href="' + window.JSON_URLS.bs_core_userspace_userfile_viewmainscreen + '"><i class="fad fa-file text-primary"></i>&nbsp;<i class="fal fa-compact-disc fa-spin text-primary"></i></a>' );
                bootbox.alert( {
                    message: "L'export va être généré et sera disponible dans votre espace utilisateur dès que terminé.",
                    className: "boxInfoOne"
                } );
            },
            error: function( xhr, ajaxOptions, throwError ){
                if( xhr.status == 409 )
                    bootbox.alert( {
                        message: xhr.responseJSON.message,
                        className: "boxErrorOne"
                    } );
                else
                    bootbox.alert( {
                        message: "Une erreur serveur est survenue. <br>["+xhr.status+"] - "+xhr.responseJSON.message,
                        className: "boxSysErrorOne"
                    } );
            }
        });
    }
}

function exportAll( ){
    // Just ask the server to begin the export process, and return to ihm
    $.ajax({
        type: "GET",
        url: window.JSON_URLS.bs_idp_export_all_offline,
        data: {
            'listId': JSON.stringify( null ),
            'listColumn': JSON.stringify( null ),
            'xpsearch': JSON.stringify( null ),
            'whereAmI': 0 },
        cache: false,
        success: function( data, status ){
            $('#userFileResume').html( '<a href="' + window.JSON_URLS.bs_core_userspace_userfile_viewmainscreen + '"><i class="fad fa-file text-primary"></i>&nbsp;<i class="fal fa-compact-disc fa-spin text-primary"></i></a>' );
            bootbox.alert( {
                message: "L'export va être généré et sera disponible dans votre espace utilisateur dès que terminé.",
                classNAme: "boxInfoOne"
            } );
        },
        error: function( xhr, ajaxOptions, throwError ){
            if( xhr.status == 409 )
                bootbox.alert( {
                    message: xhr.responseJSON.message,
                    className: "boxErrorOne"
                } );
            else
                bootbox.alert( {
                    message: "Une erreur serveur est survenue. <br>["+xhr.status+"] - "+xhr.responseJSON.message,
                    className: "boxSysErrorOne"
                } );
        }
    });
}


/**
 * Created by Cyril on 12/10/2015.
 */
var $services = [];
var $legalEntities = [];
var $budgetCodes = [];
var $documentNatures = [];
var $documentTypes = [];
var $descriptions1 = [];
var $descriptions2 = [];
var $providers = [];
var $localizations = [];
var $_tabletranslation = null;

var $_currentPage = PAGE_MANAGE_TRANSFER;
var $_currentButtons = 13;
var $_currentFCT = FCT_ARCHIVIST;
var $_mainTable = $('#listarchives');


var $defaultService = -1;
var $defaultLegalEntity = -1;
var $defaultBudgetCode = -1;
var $defaultDocumentNature = -1;
var $defaultDocumentType = -1;
var $defaultDescription1 = -1;
var $defaultDescription2 = -1;
var $defaultProvider = -1;
var $defaultLocalization =  -1;

var pollTimer;

var SERVICE_ID = 0;
var SERVICE_NAME = 1;
var SERVICE_LEGALENTITIES_IDX = 2;
var SERVICE_BUDGETCODES_IDX = 3;
var SERVICE_DESCRIPTIONS1_IDX = 4;
var SERVICE_DESCRIPTIONS2_IDX = 5;
var SERVICE_PROVIDERS_IDX = 6;
var SERVICE_DOCUMENTNATURES_IDX = 7;
var LEGALENTITY_ID = 0;
var LEGALENTITY_NAME = 1;
var DOCUMENTNATURE_ID = 0;
var DOCUMENTNATURE_NAME = 1;
var DOCUMENTNATURE_DOCUMENTTYPES_IDX = 2;
var BUDGETCODE_ID = 0;
var BUDGETCODE_NAME = 1;
var DOCUMENTTYPE_ID = 0;
var DOCUMENTTYPE_NAME = 1;
var DOCUMENTTYPE_KEEPALIVEDURATION = 2;
var DESCRIPTION1_ID = 0;
var DESCRIPTION1_NAME = 1;
var DESCRIPTION2_ID = 0;
var DESCRIPTION2_NAME = 1;
var PROVIDER_ID = 0;
var PROVIDER_NAME = 1;
var PROVIDER_LOCALIZATION_IDX = 2;
var LOCALIZATION_ID = 0;
var LOCALIZATION_NAME = 1;

var RELOC_OLD_P = [ 'CRLPDAI', 'CRLPDAINT', 'CRLPDI', 'CRLPDINT', 'CRLPCAI', 'CRLPCAINT', 'CRLPCI', 'CRLPCINT' ];

var RELOC_CONNECTOR_NEW = [ 'GRLIDAP', 'GRLINTDAP' ];
var RELOC_CONNECTOR_CONSULT = [ 'GRLPDAI', 'GRLPDAINT' ];

$xpstate = UASTATE_MANAGEPROVIDER;
$uawhat = UAWHAT_TRANSFER;
$uawhat_asked = -1;
$uawhere = UAWHERE_TRANSFER;
$uawhere_asked = -1;
$uahow = 0;
$uawith = 0;
$filter_provider = -1;

$actionList = [];	// Store Archive for Action purpose (uawhat)
$actionListID = [];	// Store only IDs to simplify some treatments
$optiListID = [];
$actionListObject = [];
$currentNumberChecked = 0;
$optimizedlist = [];

var $_commonsettings = null;
var $_settings = null;
var $_translations = null;
var $_overlay = null;

function btnwhatClean(){
    $('#btnwhat_transfer').removeClass( 'active' );
    $('#btnwhat_deliver').removeClass( 'active' );
    $('#btnwhat_return').removeClass( 'active' );
    $('#btnwhat_exit').removeClass( 'active' );
    $('#btnwhat_destroy').removeClass( 'active' );
    $('#btnwhat_reloc').removeClass( 'active' );
}
function btnwhereClean(){
    $('#btnwhere_new').removeClass( 'active' );
    $('#btnwhere_consult').removeClass( 'active' );
}

function switchCurrentPageAndButtons(){
    switch( $uawhat ){
        case UAWHAT_TRANSFER:
            $_currentPage = PAGE_MANAGE_TRANSFER;
            break;
        case UAWHAT_CONSULT:
            $_currentPage = PAGE_MANAGE_DELIVER;
            break;
        case UAWHAT_RETURN:
            $_currentPage = PAGE_MANAGE_RETURN;
            break;
        case UAWHAT_EXIT:
            $_currentPage = PAGE_MANAGE_EXIT;
            break;
        case UAWHAT_DESTROY:
            $_currentPage = PAGE_MANAGE_DELETE;
            break;
        case UAWHAT_RELOC:
            $_currentPage = PAGE_MANAGE_RELOC;
            break;
    }
    // now get back new user settings and change Column config ONLY
    $dataStr = "page=" + $_currentPage;
    $.ajax({
        type: "GET",
        url: window.JSON_URLS.bs_idp_backoffice_ajax_usersettings_get,
        data: $dataStr,
        cache: false,
        success: function( $response ) {
            // parameters : $table, $url, $userSettings, $tabletranslation, $commonSettings, $sidePagination, $method, $caller
            initMainTabColumns( false, $('#listarchives'), window.JSON_URLS.bs_idp_archivist_json_loaddatas, $response.data, $_tabletranslation, $_commonsettings, 'server', 'get', 8 );
            if( $uawhat == UAWHAT_RELOC )
                $('#btnwhere').show();
            else
                $('#btnwhere').hide();
            $('#listarchives').bootstrapTable('refresh');
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $message = xhr.responseJSON;
            bootbox.alert( {
                message: $message?$message.message:"Erreur Serveur",
                className: "boxSysErrorOne"
            });
        }
    });
}

function updateLists( data ){
    if( data.length == 9 ) { // we get all lists
        $services = data[0];
        $legalEntities = data[1];
        $documentNatures = data[2];
        $documentTypes = data[3];
        $descriptions1 = data[4];
        $descriptions2 = data[5];
        $budgetCodes = data[6];
        $providers = data[7];
        $localizations = data[8];

        initSelectProviderList( $localizations );

        return true;
    }
    return false;
}

$(document).ready(function(){

    $_commonsettings = JSON.parse( window.IDP_CONST.bs_idp_commonsettings );
    $_translations = JSON.parse( window.IDP_CONST.bs_translations );
    $_overlay = JSON.parse( window.IDP_CONST.bs_overlay );

    $_tabletranslation = JSON.parse( window.IDP_CONST.bs_tabletranslation );

    $('#btnwhere').hide();

    $('#table-action').bootstrapTable({
        showHeader: false,
        showColumns: false,
        pagination: false,
        height: 100,
        columns: [
            { field: 'name', title: 'Nom', sortable: true, visible: false },
            { field: 'id', title: 'ID', visible: false },
            { field: 'ordernumber', title: 'N° d\'ordre', sortable: true, visible: true },
            { field: 'operate', formatter: 'operateFormatter', events: 'operateActionEvents', title: 'Action', align: 'center', width: '30' }
        ]
    });

    // initOverlay
    initOverlay( );

    $.ajax({
        type: "POST",
        url: window.JSON_URLS.bs_idp_archive_form_initlists,
        data: null,
        cache: false,
        success:
            updateLists,
        error: function (xhr, ajaxOptions, thrownError) {
            $message = xhr.responseJSON;
            bootbox.alert( {
                message: $message?$message.message:"Erreur Serveur",
                className: "boxSysErrorOne"
            });
        }
    });

    // Activate Datepicker on both Date fields
    $('#frm_limitdatemin').datepicker( {
        'format': 'dd/mm/yyyy',
        'autoclose': true
    })
        .on( 'changeDate', function( event ){
            $('#frm_limitdatemin').datepicker( 'hide' );
        });
    $('#frm_limitdatemax').datepicker({
        'format': 'dd/mm/yyyy',
        'autoclose': true
    })
        .on( 'changeDate', function( event ){
            $('#frm_limitdatemax').datepicker( 'hide' );
        });

    // Since confModal is essentially a nested modal it's enforceFocus method
    // must be no-op'd or the following error results
    // "Uncaught RangeError: Maximum call stack size exceeded"
    // But then when the nested modal is hidden we reset modal.enforceFocus
    // Solution from: http://stackoverflow.com/questions/21059598/implementing-jquery-datepicker-in-bootstrap-modal
    var enforceModalFocusFn = $.fn.modal.Constructor.prototype.enforceFocus;
    $.fn.modal.Constructor.prototype.enforceFocus = function() {};

    $('#btnAction').click( function(){
        if( $actionListID.length > 0 )
            clickAction();
    });

    // Activate tooltip on buttons
    $('[data-toggle="tooltip"]').tooltip( );

    // Get back User Settings to init Main Tab
    $dataStr = "page=" + $_currentPage;
    $.ajax({
        type: "GET",
        url: window.JSON_URLS.bs_idp_backoffice_ajax_usersettings_get,
        data: $dataStr,
        cache: false,
        success: function( $response ) {
            initMainTab( $('#listarchives'), window.JSON_URLS.bs_idp_archivist_json_loaddatas, $response.data, $_tabletranslation, $_commonsettings, 'server', 'get', 8 );
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $message = xhr.responseJSON;
            bootbox.alert( {
                message: $message?$message.message:"Erreur Serveur",
                className: "boxSysErrorOne"
            });
        }
    });

    // Update ProviderConnector Form
    $.ajax({
        type: 'GET',
        url: window.JSON_URLS.bs_idp_archivist_json_providerconnectorbackup_get,
        data: '',
        cache: false,
        success: function( $response ){
            updateProviderConnectorForm( $response.datas );
        },
        error: function( xhr, ajaxOptions, thrownError ){
            $message = xhr.responseJSON;
            bootbox.alert( {
                message: $message?$message.message:"Erreur Serveur",
                className: "boxSysErrorOne"
            });
        }
    });
});

$('#addToAction').click( function( event ){
    event.preventDefault();
    if( !$('#addToAction').hasClass('disabled') )
        clickAddToAction();
});
$('#clearAction').click( function( event ){
    event.preventDefault();
    if( !$('#clearAction').hasClass("disabled")) {
        $actionList = [];
        $actionListID = [];
        $optiListID = [];
        onClickClearBasket( $('#table-action') );
    }
});

function onClickClearBasket( $basketTable ){
    $basketTable.bootstrapTable('removeAll');
    $('#listarchives').bootstrapTable('refresh');

    verifyAndDisableAddButtons();
    updateBtnActionCancelState();
    verifyAndEnableEmptyBasketButton();
}

function postQueryParams( params ){
    params.uastate = UASTATE_MANAGEPROVIDER;
    params.uawhat = $uawhat;
    params.uawhere = $uawhat==UAWHAT_RELOC?$uawhere:-1;
    params.uawith = -1; // $uawith;
    params.uahow = -1;
    params.special = $special;
    if( $("#filter_provider option:selected").val() != "" )
        params.filterprovider = $("#filter_provider option:selected").val();
    else
        params.filterprovider = -1;

    return params; //JSON.stringify( params );
}
function stateFormatter( value, row, index ){
    if( row['locked'] )
        return { disabled: true };

    var $inActionList = $actionListID.indexOf( row['id'] );

    if(  $inActionList >= 0  )
        return { disabled: true };
    else
        return { disabled: false };
}
function rowStyle( row, index ){
    if( row['locked'] )
        return { classes: 'locked' };

    var $inActionList = $actionListID.indexOf( row['id'] );

    if( $inActionList >= 0 )
        return { classes: 'info' };

    return { classes: '' };
}
function operateFormatter( value, row, index ){
    return [
        '<a class="remove" href="javascript:void(0)" title="Supprimer">', '<i class="far fa-times"></i>', '</a>'
    ].join('');
}
window.operateActionEvents = {
    'click .remove': function( e, value, row, index ){
        var $elemId = row['id'];
        var $removeIdx = -1;
        // remove line from $actionList with id
        $actionList.forEach( function( $elem, $index ){
            if( $elem['id'] == $elemId )
                $removeIdx = $index;
        });
        // Remove item from lists
        if( $removeIdx >= 0 )
            $actionList.splice( $removeIdx, 1 );
        $removeIdx = $actionListID.indexOf( $elemId );
        if( $removeIdx >= 0 )
            $actionListID.splice( $removeIdx, 1 );

        $('#table-action').bootstrapTable('load', $actionList );
        $('#listarchives').bootstrapTable('refresh');
        updateBtnActionCancelState();
        verifyAndEnableEmptyBasketButton();
    }
};
function updateBtnActionCancelState(){
    if( $actionList.length > 0 )
        $('#btnAction').removeClass( 'disabled' );
    else
        $('#btnAction').addClass( 'disabled' );

}
function verifyAndEnableEmptyBasketButton() {
    if( $actionList.length > 0 ){
        $('#clearAction').removeClass( 'disabled' );
    } else {
        $('#clearAction').addClass( 'disabled' );
    }
}

function enableAddBasketButton(){
    $('#addToAction').removeClass('disabled');
    $('#addToCancel').removeClass('disabled');
}


$('#btnConfirmModalConfirm').click(function(){

    $('#ConfirmModal').modal('hide');
    $actionList = [];
    $actionListID = [];
    $optiListID = [];
    // Update action & cancel visualisation
    $('#table-action').bootstrapTable('load', $actionList );

    switch($uawhat_asked){
        case UAWHAT_TRANSFER:
            changeWhatIntoTransfer();
            break;
        case UAWHAT_CONSULT:
            changeWhatIntoDeliver();
            break;
        case UAWHAT_RETURN:
            changeWhatIntoReturn();
            break;
        case UAWHAT_DESTROY:
            changeWhatIntoDestroy();
            break;
        case UAWHAT_EXIT:
            changeWhatIntoExit();
            break;
        case UAWHAT_RELOC:
            changeWhatIntoReloc();
            break;
    } // switch
});

function makeConfirmText( ){
    var $text = $_translations[54] + " '<strong>";
    var $bFirst = true;
    var $bCount = 0;
    if( $actionList.length > 0 ){
        $bFirst = false;
        $bCount++;
        switch( $uawhat ){
            case UAWHAT_TRANSFER: $text += $_translations[55]; break;
            case UAWHAT_CONSULT: $text += $_translations[56]; break;
            case UAWHAT_RETURN: $text += $_translations[57]; break;
            case UAWHAT_DESTROY: $text += $_translations[58]; break;
            case UAWHAT_EXIT: $text += $_translations[59]; break;
            case UAWHAT_RELOC: $text += $_translations[60]; break;
        }
    }
    if( $bCount == 1 )
        $text += "</strong>' " + $_translations[68];
    else
        $text += "</strong>' " + $_translations[69];
    $text += " <br/> "+ $_translations[70];
    return $text;
}

// Buttons UAWHERE management
$('#btnwhere_new').click( function(){
   if( $uawhere != UAWHERE_TRANSFER ){
       if( $actionList.length > 0 ){
           bootbox.alert( {
               message: $_translations[145],
               className: "boxErrorOne"
           } );
       } else
           changeWhereIntoNew();
   }
});
function changeWhereIntoNew( ){
    btnwhereClean();
    $('#btnwhere_new').addClass( 'active' );
    $uawhere = UAWHERE_TRANSFER;

    $('#divLinkBox').show();
    $('#divLinkContainer').show();
    resetMultipleSelect( );

    $('#listarchives').bootstrapTable('refresh');
};
$('#btnwhere_consult').click( function(){
    if( $uawhere != UAWHERE_CONSULT ){
        if( $actionList.length > 0 ){
            bootbox.alert( {
                message: $_translations[146],
                className: "boxErrorOne"
            } );
        } else
            changeWhereIntoConsult();
    }
});
function changeWhereIntoConsult( ){
    btnwhereClean();
    $('#btnwhere_consult').addClass( 'active' );
    $uawhere = UAWHERE_CONSULT;

    $('#divLinkBox').hide();
    $('#divLinkContainer').hide();
    resetMultipleSelect( );

    $('#listarchives').bootstrapTable('refresh');
};

// Buttons UAWHAT management
$('#btnwhat_transfer').click(function(){
    if( $uawhat != UAWHAT_TRANSFER ){
        if( $actionList.length > 0   ){
            $uawhat_asked = UAWHAT_TRANSFER;
            $('#confirmModalText').html( makeConfirmText() );
            $('#ConfirmModal').modal('show');
        } else
            changeWhatIntoTransfer();
    }
});
function changeWhatIntoTransfer(){
    btnwhatClean();
    $('#btnwhat_transfer').addClass( 'active' );
    $uawhat = UAWHAT_TRANSFER;

    $('#divLinkContainer').show();
    $('#divLinkBox').show();
    $('#titleAction').html( $_translations[26] );
    $('#btnAction').html( $_translations[28] );
    $('#textDoAction').html('&nbsp;&nbsp;&nbsp;' + $_translations[27] );

    resetMultipleSelect( );
    switchCurrentPageAndButtons();
    verifyAndEnableEmptyBasketButton();
};

$('#btnwhat_deliver').click(function(){
    if( $uawhat != UAWHAT_CONSULT ){
        if( $actionList.length > 0   ){
            $uawhat_asked = UAWHAT_CONSULT;
            $('#confirmModalText').html( makeConfirmText() );
            $('#ConfirmModal').modal('show');
        } else
            changeWhatIntoDeliver();
    }
});
function changeWhatIntoDeliver(){
    btnwhatClean();
    $('#btnwhat_deliver').addClass( 'active' );
    $uawhat = UAWHAT_CONSULT;

    $('#divLinkContainer').hide();
    $('#divLinkBox').hide();
    $('#titleAction').html( $_translations[71] );
    $('#btnAction').html( $_translations[72] );
    $('#textDoAction').html('&nbsp;&nbsp;&nbsp;' + $_translations[74] );

    resetMultipleSelect( );
    switchCurrentPageAndButtons();
    verifyAndEnableEmptyBasketButton();
};

$('#btnwhat_return').click(function(){
    if( $uawhat != UAWHAT_RETURN ){
        if( $actionList.length > 0   ){
            $uawhat_asked = UAWHAT_RETURN;
            $('#confirmModalText').html( makeConfirmText() );
            $('#ConfirmModal').modal('show');
        } else
            changeWhatIntoReturn();
    }
});
function changeWhatIntoReturn(){
    btnwhatClean();
    $('#btnwhat_return').addClass( 'active' );
    $uawhat = UAWHAT_RETURN;

    $('#divLinkContainer').hide();
    $('#divLinkBox').hide();
    $('#titleAction').html( $_translations[76] );
    $('#btnAction').html( $_translations[77] );
    $('#textDoAction').html('&nbsp;&nbsp;&nbsp;' + $_translations[79] );

    resetMultipleSelect( );
    switchCurrentPageAndButtons();
    verifyAndEnableEmptyBasketButton();
};

$('#btnwhat_exit').click(function(){
    if( $uawhat != UAWHAT_EXIT ){
        if( $actionList.length > 0   ){
            $uawhat_asked = UAWHAT_EXIT;
            $('#confirmModalText').html( makeConfirmText() );
            $('#ConfirmModal').modal('show');
        } else
            changeWhatIntoExit();
    }
});
function changeWhatIntoExit(){
    btnwhatClean();
    $('#btnwhat_exit').addClass( 'active' );
    $uawhat = UAWHAT_EXIT;

    $('#divLinkContainer').hide();
    $('#divLinkBox').hide();
    $('#titleAction').html( $_translations[81] );
    $('#btnAction').html( $_translations[82] );
    $('#textDoAction').html('&nbsp;&nbsp;&nbsp;' + $_translations[84] );

    resetMultipleSelect( );
    switchCurrentPageAndButtons();
    verifyAndEnableEmptyBasketButton();
};

$('#btnwhat_destroy').click(function(){
    if( $uawhat != UAWHAT_DESTROY ){
        if( $actionList.length > 0 ){
            $uawhat_asked = UAWHAT_DESTROY;
            $('#confirmModalText').html( makeConfirmText() );
            $('#ConfirmModal').modal('show');
        } else
            changeWhatIntoDestroy();
    }
});
function changeWhatIntoDestroy(){
    btnwhatClean();
    $('#btnwhat_destroy').addClass( 'active' );
    $uawhat = UAWHAT_DESTROY;

    $('#divLinkContainer').hide();
    $('#divLinkBox').hide();
    $('#titleAction').html( $_translations[86] );
    $('#btnAction').html( $_translations[87] );
    $('#textDoAction').html('&nbsp;&nbsp;&nbsp;' + $_translations[89] );

    resetMultipleSelect( );
    switchCurrentPageAndButtons();
    verifyAndEnableEmptyBasketButton();
};

$('#btnwhat_reloc').click(function(){
    if( $uawhat != UAWHAT_RELOC ){
        if( $actionList.length > 0  ){
            $uawhat_asked = UAWHAT_RELOC;
            $('#confirmModalText').html( makeConfirmText() );
            $('#ConfirmModal').modal('show');
        } else
            changeWhatIntoReloc();
    }
});
function changeWhatIntoReloc(){
    btnwhatClean();
    $('#btnwhat_reloc').addClass( 'active' );
    $uawhat = UAWHAT_RELOC;

    $('#divLinkContainer').show();
    $('#divLinkBox').show();
    $('#titleAction').html( $_translations[91] );
    $('#btnAction').html( $_translations[92] );
    $('#textDoAction').html('&nbsp;&nbsp;&nbsp;' + $_translations[94] );

    resetMultipleSelect( );
    switchCurrentPageAndButtons();
    verifyAndEnableEmptyBasketButton();
};

function updateServices( $initService ){
    var $serviceOptions = "<option value=\"\"></option>";
    var $i = 0;
    var $bSelected = false;
    $services.forEach(function($serviceLine){
        $selected = "";
        if( $serviceLine[SERVICE_ID] == parseInt( $initService ) ){
            $bSelected = true;
            $selected = " selected='selected' ";
        }
        $serviceOptions += "<option value=\"" + $serviceLine[SERVICE_ID] + "\" data=\"" + $i + "\" " + $selected + ">" + $serviceLine[SERVICE_NAME] + "</option> ";
        $i = $i + 1;
    });
    $('#frm_service').html( $serviceOptions );

    if( parseInt( $initService ) >= 0 ){
        updateLegalEntities( $defaultLegalEntity );
        updateBudgetCodes( $defaultBudgetCode );
        updateDescriptions1( $defaultDescription1 );
        updateDescriptions2( $defaultDescription2 );
        updateProviders( $defaultProvider );
        updateDocumentNatures( $defaultDocumentNature );
        updateLocalizations( $defaultLocalization, $defaultOldLocalization );
    }
}
function updateLegalEntities( $initLegalEntity ){
    // First test if service selected is really a service
    var $partialLegalEntities = "";
    if( $("#frm_service option:selected").val() != "" ){

        $partialLegalEntities = "<option value ";
        if( parseInt($initLegalEntity) <= 0 )
            $partialLegalEntities += "selected=\"selected\"";
        $partialLegalEntities += "></option>";

        // Construct list of legal entities choices based on service id
        var $serviceIdx = parseInt( $("#frm_service option:selected").attr('data') );
        var $listLegalEntities = $services[$serviceIdx][SERVICE_LEGALENTITIES_IDX];
        var $i = 0;
        $legalEntities.forEach(function($legalentityLine){
            if( $listLegalEntities.indexOf( $legalentityLine[LEGALENTITY_ID] ) >= 0 ){
                var $selected = "";
                if( $legalentityLine[LEGALENTITY_ID] == parseInt( $initLegalEntity ) )
                    $selected = "selected=\"selected\"";
                $partialLegalEntities += "<option value=\"" + $legalentityLine[LEGALENTITY_ID] + "\" data=\"" + $i + "\" " + $selected + ">" + $legalentityLine[LEGALENTITY_NAME] + "</option> ";
            }
            $i = $i + 1;
        });
        $('#frm_legalentity').attr('disabled', false);
    } else {
        $('#frm_legalentity').attr('disabled', true);
        $partialLegalEntities ="<option value selected=\"selected\">N/A</option>";
    }

    $("#frm_legalentity").html($partialLegalEntities);

}
function updateBudgetCodes( $initBudgetCode ){

    var $partialBudgetCodes = "";
    if( $("#frm_service option:selected").val() != "" ){

        $partialBudgetCodes ="<option value ";
        if( parseInt($initBudgetCode) <= 0 )
            $partialBudgetCodes += " selected=\"selected\"";
        $partialBudgetCodes += "></option>";

        // Construct list of budget codes choices based on service id
        var $serviceIdx = parseInt( $("#frm_service option:selected").attr('data') );
        var $listBudgetCodes = $services[$serviceIdx][SERVICE_BUDGETCODES_IDX];
        var $i = 0;
        $budgetCodes.forEach(function($budgetcodeLine){
            if( $listBudgetCodes.indexOf( $budgetcodeLine[BUDGETCODE_ID] ) >= 0 ){
                var $selected = "";
                if( $budgetcodeLine[BUDGETCODE_ID] == parseInt( $initBudgetCode ) )
                    $selected = "selected=\"selected\"";
                $partialBudgetCodes += "<option value=\"" + $budgetcodeLine[BUDGETCODE_ID] + "\" data=\"" + $i + "\" " + $selected + ">" + $budgetcodeLine[BUDGETCODE_NAME] + "</option> ";
            }
            $i = $i + 1;
        });
        $('#frm_budgetcode').attr('disabled', false);
    } else {
        $('#frm_budgetcode').attr('disabled', true);
        $partialBudgetCodes ="<option value selected=\"selected\">N/A</option>";
    }

    $("#frm_budgetcode").html($partialBudgetCodes);
}
function updateDocumentNatures( $initDocumentNature ){

    var $partialDocumentNatures = "";
    if( $("#frm_service option:selected").val() != "" ){

        $partialDocumentNatures = "<option value ";
        if( parseInt($initDocumentNature) <= 0 )
            $partialDocumentNatures += " selected=\"selected\"";
        $partialDocumentNatures += "></option>";
        // Construct list of document natures choices based on service id
        var $serviceIdx = parseInt( $("#frm_service option:selected").attr('data') );
        var $listDocumentNatures = $services[$serviceIdx][SERVICE_DOCUMENTNATURES_IDX];
        var $i = 0;
        $documentNatures.forEach(function($documentnatureLine){
            if( $listDocumentNatures.indexOf( $documentnatureLine[DOCUMENTNATURE_ID] ) >= 0 ){
                var $selected = "";
                if( $documentnatureLine[DOCUMENTNATURE_ID] == parseInt( $initDocumentNature ) )
                    $selected = "selected=\"selected\"";
                $partialDocumentNatures += "<option value=\"" + $documentnatureLine[DOCUMENTNATURE_ID] + "\" data=\"" + $i + "\" " + $selected + ">" + $documentnatureLine[DOCUMENTNATURE_NAME] + "</option> ";
            }
            $i = $i + 1;
        });
        $('#frm_documentnature').attr('disabled', false);
    } else {
        $('#frm_documentnature').attr('disabled', true);
        $partialDocumentNatures ="<option value selected=\"selected\">N/A</option>";
    }

    $("#frm_documentnature").html($partialDocumentNatures);

    if( parseInt( $initDocumentNature ) >= 0 )
        updateDocumentTypes( $defaultDocumentType );
}
function updateDocumentTypes( $initDocumentType ){

    var $partialDocumentTypes = "";
    if( $("#frm_documentnature option:selected").val() != "" ){

        $partialDocumentTypes ="<option value ";
        if( parseInt($initDocumentType) <= 0 )
            $partialDocumentTypes += " selected=\"selected\"";
        $partialDocumentTypes += "></option>";
        // Construct list of document types choices based on document nature id
        var $documentnatureIdx = parseInt( $("#frm_documentnature option:selected").attr('data') );
        var $listDocumentTypes = $documentNatures[$documentnatureIdx][DOCUMENTNATURE_DOCUMENTTYPES_IDX];
        var $i = 0;
        $documentTypes.forEach(function($documenttypeLine){
            if( $listDocumentTypes.indexOf( $documenttypeLine[DOCUMENTTYPE_ID] ) >= 0 ){
                var $selected = "";
                if( $documenttypeLine[DOCUMENTTYPE_ID] == parseInt( $initDocumentType ) )
                    $selected = "selected=\"selected\"";
                $partialDocumentTypes += "<option value=\"" + $documenttypeLine[DOCUMENTTYPE_ID] + "\" data=\"" + $i + "\" " + $selected + ">" + $documenttypeLine[DOCUMENTTYPE_NAME] + "</option> ";
            }
            $i = $i + 1;
        });
        $('#frm_documenttype').attr('disabled', false);
    } else {
        $('#frm_documenttype').attr('disabled', true);
        $partialDocumentTypes ="<option value selected=\"selected\">N/A</option>";
    }

    $("#frm_documenttype").html($partialDocumentTypes);

    if( parseInt( $initDocumentType ) >= 0 )
        updateDestructionYear();
}
function updateDescriptions1( $initDescription1 ){

    var $partialDescriptions1 = "";
    if( $("#frm_service option:selected").val() != "" ){

        $partialDescriptions1 ="<option value ";
        if( parseInt($initDescription1) <= 0 )
            $partialDescriptions1 += " selected=\"selected\"";
        $partialDescriptions1 += "></option>";
        // Construct list of descriptions1 choices based on service id
        var $serviceIdx = parseInt( $("#frm_service option:selected").attr('data') );
        var $listDescriptions1 = $services[$serviceIdx][SERVICE_DESCRIPTIONS1_IDX];
        var $i = 0;
        $descriptions1.forEach(function($descriptionLine){
            if( $listDescriptions1.indexOf( $descriptionLine[DESCRIPTION1_ID] ) >= 0 ){
                var $selected = "";
                if( $descriptionLine[DESCRIPTION1_ID] == parseInt( $initDescription1 ) )
                    $selected = "selected=\"selected\"";
                $partialDescriptions1 += "<option value=\"" + $descriptionLine[DESCRIPTION1_ID] + "\" data=\"" +  $i + "\" " + $selected + ">" + $descriptionLine[DESCRIPTION1_NAME] + "</option> ";
            }
            $i = $i + 1;
        });
        $('#frm_description1').attr('disabled', false);
    } else {
        $('#frm_description1').attr('disabled', true);
        $partialDescriptions1 ="<option value selected=\"selected\">N/A</option>";
    }

    $("#frm_description1").html($partialDescriptions1);
}
function updateDescriptions2( $initDescription2 ){

    var $partialDescriptions2 = "";
    if( $("#frm_service option:selected").val() != "" ){

        $partialDescriptions2 ="<option value ";
        if( parseInt($initDescription2) <= 0 )
            $partialDescriptions2 += " selected=\"selected\"";
        $partialDescriptions2 += "></option>";
        // Construct list of descriptions2 choices based on service id
        var $serviceIdx = parseInt( $("#frm_service option:selected").attr('data') );
        var $listDescriptions2 = $services[$serviceIdx][SERVICE_DESCRIPTIONS2_IDX];
        var $i = 0;
        $descriptions2.forEach(function($descriptionLine){
            if( $listDescriptions2.indexOf( $descriptionLine[DESCRIPTION2_ID] ) >= 0 ){
                var $selected = "";
                if( $descriptionLine[DESCRIPTION2_ID] == parseInt( $initDescription2 ) )
                    $selected = "selected=\"selected\"";
                $partialDescriptions2 += "<option value=\"" + $descriptionLine[DESCRIPTION2_ID] + "\" data=\"" + $i + "\"  " + $selected + ">" + $descriptionLine[DESCRIPTION2_NAME] + "</option> ";
            }
            $i = $i + 1;
        });
        $('#frm_description2').attr('disabled', false);
    } else {
        $('#frm_description2').attr('disabled', true);
        $partialDescriptions2 ="<option value selected=\"selected\">N/A</option>";
    }

    $("#frm_description2").html($partialDescriptions2);
}
function updateProviders( $initProvider ){

    var $partialProviders = "";
    if( $("#frm_service option:selected").val() != "" ){

        $partialProviders ="<option value ";
        if( parseInt($initProvider) <= 0 )
            $partialProviders += " selected=\"selected\"";
        $partialProviders += "></option>";
        // Construct list of providers choices based on service id
        var $serviceIdx = parseInt( $("#frm_service option:selected").attr('data') );
        var $listProviders = $services[$serviceIdx][SERVICE_PROVIDERS_IDX];
        var $i = 0;
        $providers.forEach(function($providerLine){
            if( $listProviders.indexOf( $providerLine[PROVIDER_ID] ) >= 0 ){
                var $selected = "";
                if( $providerLine[PROVIDER_ID] == parseInt( $initProvider ) )
                    $selected = "selected=\"selected\"";
                $partialProviders += "<option value=\"" + $providerLine[PROVIDER_ID] + "\" data=\"" + $i + "\" " + $selected + ">" + $providerLine[PROVIDER_NAME] + "</option> ";
            }
            $i = $i + 1;
        });
        $('#frm_provider').attr('disabled', false);
    } else {
        $('#frm_provider').attr('disabled', true);
        $partialProviders ="<option value selected=\"selected\">N/A</option>";
    }

    $("#frm_provider").html($partialProviders);
}
function updateLocalizations( $initLocalization ){

    $partialLocalizations = "";
    if( $("#frm_provider option:selected").val() != "" ){

        $partialLocalizations ="<option value ";
        if( parseInt($initLocalization) <= 0 )
            $partialLocalizations += " selected=\"selected\"";
        $partialLocalizations += "></option>";

        // Construct list of localizations choices based on provider id
        $providerIdx = parseInt( $("#frm_provider option:selected").attr('data') );
        $localizationIdx = $providers[$providerIdx][PROVIDER_LOCALIZATION_IDX];

        if( $localizationIdx >= 0 ) {
            $localizations.forEach( function( $localizationLine ){
                $selected = "";
                if( $localizationLine[LOCALIZATION_ID] == $localizationIdx ) {
                    if ($localizationLine[LOCALIZATION_ID] == parseInt($initLocalization))
                        $selected = "selected=\"selected\"";
                    $partialLocalizations += "<option value=\"" + $localizationLine[LOCALIZATION_ID] + "\"" + $selected + ">" + $localizationLine[LOCALIZATION_NAME] + "</option> ";
                }
            });
        }

        $('#frm_localization').attr('disabled', false);
    } else {
        $('#frm_localization').attr('disabled', true);
        $partialLocalizations ="<option value selected=\"selected\"></option>";
    }

    $("#frm_localization").html($partialLocalizations);
}

// On service select box change, update legal entities & budget codes with only available choices
$('#frm_service').change(function () {
    updateLegalEntities( -1 );
    updateBudgetCodes( -1 );
    updateDocumentNatures( -1 );
    updateDocumentTypes( -1 );
    updateDescriptions1( -1 );
    updateDescriptions2( -1 );
    updateProviders( -1 );

    getAjaxSettings( $('#frm_service option:selected').val(), null );
});
$('#frm_provider').change( function() {
    updateLocalizations( -1 );
});

// On DocumentNature select box change, update document types with available choices
$('#frm_documentnature').change(function(){
    updateDocumentTypes( -1 );
});

// On DocumentType select change, update the destruction year if needed
$('#frm_documenttype').change(function(){
    updateDestructionYear();
});

// Change Destruction year, if closure year change and input loss focus$
$('#frm_closureyear').blur(function(){
    updateDestructionYear();
});

function updateDestructionYear(){

    var $destructionTime = 0;
    if( $('#frm_documenttype option:selected').val != '' ) {
        var $documenttypeIdx = parseInt($("#frm_documenttype option:selected").attr('data'));
        if( !isNaN( $documenttypeIdx ) )
            var $destructionTime = $documentTypes[$documenttypeIdx][DOCUMENTTYPE_KEEPALIVEDURATION];
    }

    // If destruction time is 0, user should enter whatever he wants, instead destruction time is forced
    if($destructionTime == 0){
        $("#frm_destructionyear").prop('disabled', false);
    }
    else
    {
        $("#frm_destructionyear").prop('disabled', true);
        // TODO test empty field
        var $destructionYear = parseInt($("#frm_closureyear").val());
        $destructionYear += $destructionTime;
        $("#frm_destructionyear").val($destructionYear);
    }
}


// Update both Add button states accordingly to selections in main table
/*function updateAddButtonState(){
    if( $currentNumberChecked > 0 ){
        $('#addToAction').removeClass('disabled');
    } else {
        $('#addToAction').addClass('disabled');
    }
}*/
function verifyAndDisableAddButtons() {
    $selections = $_mainTable.bootstrapTable('getSelections');
    if( $selections.length <= 0 ){
        $('#addToAction').addClass('disabled');
        $('#addToCancel').addClass('disabled');
    }
}


// Add new selection to the Action list
function clickAddToAction(){
    var $selections = $('#listarchives').bootstrapTable( 'getSelections' );

    // TODO test if there is no unlimited archive in the list.

    // Scan to search new ones
    $selections.forEach( function( $elem ){
        var $inActionList = $actionListID.indexOf( $elem['id'] );
        if( $inActionList < 0 ){
            $actionListID.push( $elem['id'] );
            $actionList.push( $elem );
        }
    });

    // Refresh table-action with new list
    $('#table-action').bootstrapTable('load', $actionList );
    $('#listarchives').bootstrapTable('refresh');
    updateBtnActionCancelState();
    verifyAndEnableEmptyBasketButton();
    verifyAndDisableAddButtons();
    return true;
};

//--------------------------------------------------------------------------------------------------------------
// After verifying user_side exception do this
function continue_After_BasketTests( $action ){
    $('#waitAjax').hide();
    // Display modal for Provider Connector Complementary Datas
    showComplementaryProviderConnectorModal();
}

//.................................................................................
// If user validate the Provider Connector Datas Window
$('#ProviderConnectorModalBtnConfirm').click( function() {

    clearPCBView();
    if( verifyPCBMandatories() ) {
        // Hide modal form and switch on the ajax  wait screen
        $('#ProviderConnectorModal').modal('hide');
        //pollLatestOptiStatus();
        pollTimer = setInterval(pollLatestOptiStatus, 1000);
        $('#waitAjax').show();

        // Save datas for next time
        save_ProviderConnectorModalDatas();

        // Lock full container / box asked in basket (except in return mode)
        lock_Basket();
    } else {
        bootbox.alert( {
            title: 'Le formulaire comporte des erreurs',
            message: "Certains champs obligatoires n'ont pas été renseignés !",
            className: "boxErrorOne"
        } );
    }
});

//.................................................................................
// This function Locks full container / box asked in basket, if successfull call the switch_PreOptimisation function
function lock_Basket( ) {
    if( $uawhat == UAWHAT_RETURN )
        switch_PreOptimisation( );
    else {
        var $dataObject = {
            'ids': JSON.stringify($actionListID)
        };

        $.ajax({
            type: "GET",
            url: window.JSON_URLS.bs_idp_archivist_provider_connector_lock_basket,
            data: $dataObject,
            cache: false,
            success: function ($response) {
                switch_PreOptimisation();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $('#waitAjax').hide();
                $message = xhr.responseJSON;
                bootbox.alert( {
                    message: $message ? $message.message : $_translations[147],
                    className: "boxSysErrorOne"
                });
                clearInterval(pollTimer);
            }
        });
    }
}
//.................................................................................
// This function UnLocks full container / box asked in basket in case of errors during operation
function unlock_Basket( ){
    if( $uawhat != UAWHAT_RETURN )
        $('#waitAjax').hide();
    else {
        var $dataObject = {
            'ids': JSON.stringify($actionListID)
        };

        $.ajax({
            type: 'GET',
            url: window.JSON_URLS.bs_idp_archivist_provider_connector_unlock_basket,
            data: $dataObject,
            cache: false,
            success: function ($response) {
                $('#waitAjax').hide();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $('#waitAjax').hide();
                $message = xhr.responseJSON;
                bootbox.alert( {
                    message: $message ? $message.message : $_translations[147],
                    className: "boxSysErrorOne"
                });
            }
        });
    }
    $actionListObject = [];
    $optiListID = [];
}
//.................................................................................
// This function ungrays uas grayed for optimization
function ungray_UAs_Optimized( ){
    if( $optimizedlist.length > 0 ) {
        var $dataObject = {
            'idoptimizedlist': JSON.stringify($optimizedlist),
        };
        $.ajax({
            type: "GET",
            url: window.JSON_URLS.bs_idp_archivist_provider_connector_ungray,
            data: $dataObject,
            cache: false
        });
        $optimizedlist = [];
    }
}
//.................................................................................
// this function will orientate the process after lock_basket based on $uawhat
function switch_PreOptimisation( ){
    /*
    $bRelocConnectorConsult = $uachere == UAWHERE_CONSULT;
    // verify if we are in a Reloc Consult type
    $actionList.forEach( function( $elem ){
        if( $.inArray( $elem['statuscaps'], RELOC_CONNECTOR_CONSULT ) >= 0 )
            $bRelocConnectorConsult = true;
    });
    */

    // Depends on function, synopsis isn't the same for all
    switch( $uawhat ){
        case UAWHAT_TRANSFER:
        case UAWHAT_RETURN:
        case UAWHAT_EXIT:

            $optiListID = $actionListID;        // No optimization, so optimizedList = BasketList
            $actionListObject = null;           // React as if all objects were asked
            generate_PrePDF( );
            clearInterval(pollTimer);
            break;

        case UAWHAT_CONSULT: // = Consultation

            calculate_Optimization( false );
            break;

        case UAWHAT_DESTROY:

            calculate_Optimization( true );
            break;
        case UAWHAT_RELOC:
            if( $uawhere == UAWHERE_CONSULT ){  // Should react like Consult

                calculate_Optimization( false );

            } else { // Should react like Transfer

                $optiListID = $actionListID;
                $actionListObject = null;
                generate_PrePDF( );

            }
            break;
    }
}
//.................................................................................
// this function will orientate the process after an error occured based on $uawhat
function switch_PostOptimisation( ){
    $bRelocConnectorConsult = false;
    // verify if we are in a Reloc Consult type
    $actionList.forEach( function( $elem ){
        if( $.inArray( $elem['statuscaps'], RELOC_CONNECTOR_CONSULT ) >= 0 )
            $bRelocConnectorConsult = true;
    });

    // Depends on function, synopsis isn't the same for all
    switch( $uawhat ){
        case UAWHAT_TRANSFER:
        case UAWHAT_RETURN:
        case UAWHAT_EXIT:

            $optiListID = $actionListID;        // No optimization, so optimizedList = BasketList
            $actionListObject = null;           // React as if all objects were asked
            unlock_Basket();
            break;

        case UAWHAT_CONSULT: // = Consultation
        case UAWHAT_DESTROY:

            unmanage_Optimization( );
            break;

        case UAWHAT_RELOC:
            if( $bRelocConnectorConsult ){  // Should react like Consult

                unmanage_Optimization( );

            } else { // Should react like Transfer

                $optiListID = $actionListID;
                $actionListObject = null;
                unlock_Basket( );

            }
            break;
    }
}

//.................................................................................
// This function is called once Optimisation Window is accepted
$('#OptimisationModalBtnConfirm').click( function( event ) {
    $('#OptimisationModalBody input:checked').each(function () {
        $thisValue = parseInt($(this).attr('value'));
        if( $thisValue > 0)
            $optiListID.push( $thisValue );
        else {
            if( $thisValue != -2 )  // not subbox
                $actionListObject.push($(this).attr('id'));
            else    // For subbox, only if not grayed (and checked)
                if( !$(this).attr('disabled') )
                    $actionListObject.push($(this).attr('id'));
        }
    });

    manage_OptimizationChoices( false );
});

$('#DelOptimisationModalBtnConfirm').click( function( event ) {
    $('#DelOptimisationModalBody input:checked').each(function () {
        $thisValue = parseInt($(this).attr('value'));
        if( $thisValue > 0 )
            $optiListID.push( $thisValue );
    });

    manage_OptimizationChoices( true );
});
//.................................................................................
// This function is called when pre PDF is accepted
function doAction(  ){

    var $dataObject = {
        'localizationId': $("#filter_provider option:selected").val(),
        'uastate': UASTATE_MANAGEPROVIDER,
        'uawhat': $uawhat,
        'uawhere': $uawhere,
        'uawith': -1,
        'uahow': -1,
        'ids': JSON.stringify($optiListID),
        'objects': JSON.stringify($actionListObject),   // Full objetcs selected
        'contact' : $('#frm_pcb_contact').val(),
        'phone': $('#frm_pcb_phone').val(),
        'address': $('#frm_pcb_address').val(),
        'deliver': $('input[name=pcbdeliverradio]:checked', '#ProviderConnectorModalBody').val(),
        'disposal': $('input[name=pcbdisposalradio]:checked', '#ProviderConnectorModalBody').val(),
        'type': $('input[name=pcbtyperadio]:checked', '#ProviderConnectorModalBody').val(),
        'type2': $('input[name=pcbtype2radio]:checked', '#ProviderConnectorModalBody').val(),
        'remark': $('#frm_pcb_remark').val(),
        'name': $('#frm_pcb_name').val(),
        'firstname': $('#frm_pcb_firstname').val(),
        'function': $('#frm_pcb_function').val(),
        'pre': 0
    };

    $.ajax({
        type: "GET",
        url: window.JSON_URLS.bs_idp_archivist_json_action,
        data: $dataObject,
        cache: false,
        success: function ($response) {
            $('#waitAjax').hide();
            $actionList = [];
            $actionListID = [];
            $('#table-action').bootstrapTable('load', $actionList);
            $('#listarchives').bootstrapTable('refresh');
            updateBtnActionCancelState();
            // Launch pdf real generation

            get(window.JSON_URLS.bs_idp_archivist_print_provider_connector, $dataObject, true );
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $('#waitAjax').hide();
            $message = xhr.responseJSON;
            bootbox.alert( {
                message: $message?$message.message:$_translations[147],
                className: "boxSysErrorOne"
            });
        }
    });

    $optiListID = [];
    $actionListObject = [];
}

//.................................................................................
// This function send to server result of optimization choices window
function manage_OptimizationChoices( $delModal ){

    if( $optiListID.length > 0 ) {
        var $dataObject = {
            'uawhat': $uawhat,
            'uawhere': $uawhat==UAWHAT_RELOC?$uawhere:-1,
            'ids': JSON.stringify($optiListID),             // Only those selected in opti_modal (can be less or more)
            'basketids': JSON.stringify($actionListID),     // Old selected with basket
            'objects': !$delModal?JSON.stringify($actionListObject):null,   // Full objetcs selected
        };

        $.ajax({
            type: "GET",
            url: window.JSON_URLS.bs_idp_archivist_provider_connector_manage_optimization_choices,
            data: $dataObject,
            cache: false,
            success: function( $response ){

                generate_PrePDF( );
            },
            error: function( xhr, ajaxOptions, throwError ){
                $('#waitAjax').hide();
                $message = xhr.responseJSON;
                bootbox.alert( {
                    message: $message?$message.message:$_translations[147],
                    className: "boxSysErrorOne"
                });
                $actionListObject = [];
                unlock_Basket();
            }
        });
    } else {
        /*  Done by dialog disparition
        ungray_UAs_Optimized();
        */
    }

    if( !$delModal )
        $('#OptimisationModal').modal('hide');
    else
        $('#DelOptimisationModal').modal('hide');
}

//.................................................................................
// This function ask server for optimization archives to be displayed
function calculate_Optimization( $delModal ){
    // Send list of ids to server for optimisation
    var $dataObject = {
        'idlist': JSON.stringify($actionListID),
        'uawhat': $uawhat,
        'uawhere': -1
    };
    $.ajax({
        type: "GET",
        url: window.JSON_URLS.bs_idp_archivist_provider_connector_optimisation,
        data: $dataObject,
        cache: false,
        success: function($response) {
            // Get back list of (ids + optimisation) to show in optimisation popup
            $optimizedlist = [];
            $optimizedtemp = $response['optimizedlist'];
            for (var i = 0, len = $optimizedtemp.length; i < len; i++) {
                $optimizedlist.push( $optimizedtemp[i]['id'] );
            }

            $('#waitAjax').hide();
            clearInterval(pollTimer);
            //updatePopupOptimizationModal_v2($optimizedtemp, $actionListID, $delModal);
            updatePopupOptimizationModal_v3($optimizedtemp, $actionListID, $delModal);
            if( !$delModal ) {
                $('#OptimisationModal').modal('show');
            } else {
                $('#DelOptimisationModal').modal('show');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $('#waitAjax').hide();
            $message = xhr.responseJSON;
            bootbox.alert( {
                message: $message?$message.message:$_translations[147],
                className: "boxSysErrorOne"
            });
            clearInterval(pollTimer);
            unlock_Basket();
        }
    });
}
//.................................................................................
// This function ask server to undo optimization ordered lock
function unmanage_Optimization( ) {
    if ($optiListID.length > 0) {
        var $dataObject = {
            'uawhat': $uawhat,
            'uawhere': $uawhat == UAWHAT_RELOC ? $uawhere : -1,
            'ids': JSON.stringify($optiListID),             // Only those selected in opti_modal (can be less or more)
            'objects': JSON.stringify($actionListObject),   // Full objetcs selected
        };

        $.ajax({
            type: "GET",
            url: window.JSON_URLS.bs_idp_archivist_provider_connector_unmanage_optimization_choices,
            data: $dataObject,
            cache: false,
            success: function ($response) {
                unlock_Basket();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $message = xhr.responseJSON;
                bootbox.alert( {
                    message: $message?$message.message:$_translations[147],
                    className: "boxSysErrorOne"
                });
                unlock_Basket();
            }
        });
    } else
        unlock_Basket();
}

//.................................................................................
// This function ask server to generate preview of PDF Connector Provider
function generate_PrePDF( ){
    var $dataObject = {
        'localizationId': $("#filter_provider option:selected").val(),
        'uastate': UASTATE_MANAGEPROVIDER,
        'uawhat': $uawhat,
        'uawhere': $uawhere,
        'uawith': -1,
        'uahow': -1,
        'ids': JSON.stringify($optiListID),
        'basketids': JSON.stringify($actionListID),     // Old selected with basket
        'objects': JSON.stringify($actionListObject),   // Full objetcs selected
        'contact' : $('#frm_pcb_contact').val(),
        'phone': $('#frm_pcb_phone').val(),
        'address': $('#frm_pcb_address').val(),
        'deliver': $('input[name=pcbdeliverradio]:checked', '#ProviderConnectorModalBody').val(),
        'disposal': $('input[name=pcbdisposalradio]:checked', '#ProviderConnectorModalBody').val(),
        'type': $('input[name=pcbtyperadio]:checked', '#ProviderConnectorModalBody').val(),
        'type2': $('input[name=pcbtype2radio]:checked', '#ProviderConnectorModalBody').val(),
        'remark': $('#frm_pcb_remark').val(),
        'name': $('#frm_pcb_name').val(),
        'firstname': $('#frm_pcb_firstname').val(),
        'function': $('#frm_pcb_function').val(),
        'pre': 1                                    // ask serveur to generate pdf simulation
    };

    get(window.JSON_URLS.bs_idp_archivist_print_provider_connector, $dataObject, true );
    $('#waitAjax').hide();

    $btnText = $_translations[148];
    switch( $dataObject['uawhat'] ){
        case 0: $btnText += $_translations[149]; break;
        case 1: $btnText += $_translations[150]; break;
        case 2: $btnText += $_translations[151]; break;
        case 3: $btnText += $_translations[152]; break;
        case 4: $btnText += $_translations[153]; break;
        case 5: $btnText += $_translations[154]; break;
    }
    bootbox.dialog({
        message: $_translations[155],
        title: 'Connecteur prestataire',
        className: "boxQuestionTwo",
        closeButton: false,
        buttons: {
            "Non": { label: $_translations[139], className: "btn-default", callback:
                    function() {
                        switch_PostOptimisation();
                    }},
            "Oui": { label: $btnText, className: "btn-primary", callback:
                    function() {
                        doAction( );
                    }}
        }
    });
}


//====================================================

$('#divPrint').click(function(){
    var $params = new Array();
    $params['id'] = $('#frm_id').val();
    get(window.JSON_URLS.bs_idp_archive_print_sheet, $params, true );
});

// Export and Export Partial button management
$('#divExport').click(function( event ){
    event.preventDefault();

    $filter = ( $("#filter_provider option:selected").val() != "" ) ? $("#filter_provider option:selected").val() : -1;

    exportArray( $('#listarchives'), $_currentFCT, UASTATE_MANAGEPROVIDER, $uawhat, $uawhere, $uahow, $filter, false );
});
$('#divExportPartial').click( function( event ){
    event.preventDefault();

    $filter = ( $("#filter_provider option:selected").val() != "" ) ? $("#filter_provider option:selected").val() : -1;

    exportArray( $('#listarchives'), $_currentFCT, UASTATE_MANAGEPROVIDER, $uawhat, $uawhere, $uahow, $filter, true );
});


$('#divPrintList').click(function(){
    if( $('#listarchives').bootstrapTable('getData').length > 0 )
        printTable( false, $('#listarchives'), 3, window.IDP_CONST.bs_idp_current_page );
});
$('#divPrintPartialList').click(function(){
    if( $('#listarchives').bootstrapTable('getSelections').length > 0 )
        printTable( true, $('#listarchives'), 3 );
});

// Init Select box with all Localizations for filtering purpose
function initSelectProviderList( $localizationList ){
    var $partialLocalizations = ""; // "<option value='-1' selected='selected' >Tous</option>";

    var $bFirst = true;
    $localizationList.forEach(function($localizationLine) {
        var $selected = "";
        if( $bFirst ){
            $selected = "selected='selected'";
            $bFirst = false;
            $filter_provider = $localizationLine[LOCALIZATION_ID];
        }
        $partialLocalizations += "<option value='" + $localizationLine[LOCALIZATION_ID] + "' " + $selected + ">" + $localizationLine[LOCALIZATION_NAME] + "</option> ";

    });

    $("#filter_provider").html($partialLocalizations);
    $('#listarchives').bootstrapTable('refresh');
}

// Provider SelectBox management
$('#filter_provider').change( function( event ){
    event.preventDefault();
    if( $filter_provider != $('#filter_provider').val() ){
        if( $actionList.length > 0 ){
            $('#filter_provider').val( $filter_provider );
            bootbox.alert( {
                message: $_translations[159],
                className: "boxErrorOne"
            } );
        } else {
            $filter_provider = $('#filter_provider').val();
            $('#listarchives').bootstrapTable('refresh', {pageNumber: 1});
        }
    }

})


// -----------------------------------------------------------------------
// Manage Modal popup for "link with same box" button
$('#divLinkContainer').click(function(){
    // Activate only if something is selected
    if( $('#listarchives').bootstrapTable('getSelections').length > 0 ){
        $('#linkContainer_container').val( '' );
        $('#LinkContainerModal').modal('show');
    }
});
$('#btnLinkModalConfirm').click(function(){
   // Only if container field is not empty
    if( $('linkContainer_container').val() == '' ){
        bootbox.alert( {
            message: $_translations[101],
            className: "boxErrorOne"
        } );
    } else {
        var $selections = $('#listarchives').bootstrapTable( 'getSelections' );
        var $idlist = '';
        var $bFirst = true;
        $selections.forEach( function( $elem ){
            if( $bFirst )
                $bFirst = false;
            else
                $idlist += ',';

            $_idArrayToReckeck.push($elem['id']);
            $idlist += $elem['id'];
        });
        var $datas = {
            'idlist': $idlist,
            'containernumber': $('#linkContainer_container').val()
        };
        $.ajax({
            type: "GET",
            url: window.JSON_URLS.bs_idp_archivist_json_update_container,
            data: $datas,
            cache: false,
            success: function( ){
                $('#LinkContainerModal').modal('hide');
                $('#listarchives').bootstrapTable('refresh');
                $_recheck = true;   // Activate post reload rechecking
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $message = xhr.responseJSON;
                bootbox.alert( {
                    message: $message?$message.message:$_translations[147],
                    className: "boxSysErrorOne"
                });
                $('#LinkContainerModal').modal('hide');
            }
        });
    }
});

// -----------------------------------------------------------------------
// Manage Modal popup for "link with same box" button
$('#divLinkBox').click(function(){
    // Activate only if something is selected
    if( $('#listarchives').bootstrapTable('getSelections').length > 0 ){

        var $selections = $('#listarchives').bootstrapTable( 'getSelections' );
        $providerEmpty = true;
        $documentEmpty = true;
        $containerEmpty = true;
        $containerSame = true;
        $lastContainer = null;

        $selections.forEach( function( $elem ){
            if( $providerEmpty && $elem['provider_id'] != null )
                $providerEmpty = false;
            if( $documentEmpty && $elem['documentnumber'] != null )
                $documentEmpty = false;
            if( $containerEmpty && $elem['containernumber'] != null ){
                $containerEmpty = false;
                $lastContainer = $elem['containernumber'];
            }
            if( $containerSame && !$containerEmpty && $lastContainer != $elem['containernumber'] )
                $containerSame = false;
            $lastContainer = $elem['containernumber'];
        });

        $isOkToShow = false;
        if( $providerEmpty )
            $isOkToShow = true;
        else
            if( $documentEmpty )
                $isOkToShow = true;
            else
                if( $containerSame )
                    $isOkToShow = true;

        if( $isOkToShow ) {
            $('#linkBox_box').val('');
            $('#LinkBoxModal').modal('show');
        } else
            bootbox.alert( {
                message: $_translations[160],
                className: "boxErrorOne"
            } );
    }
});
$('#btnLinkModalBoxConfirm').click(function(){

    // Only if container field is not empty
    if( $('linkBox_box').val() == '' ){
        bootbox.alert( {
            message: $_translations[111],
            className: "boxErrorOne"
        } );
    } else {
        var $selections = $('#listarchives').bootstrapTable( 'getSelections' );
        var $idlist = '';
        var $bFirst = true;
        $selections.forEach( function( $elem ){
            if( $bFirst )
                $bFirst = false;
            else
                $idlist += ',';

            $_idArrayToReckeck.push($elem['id']);
            $idlist += $elem['id'];
        });
        var $datas = {
            'idlist': $idlist,
            'boxnumber': $('#linkBox_box').val()
        };
        $.ajax({
            type: "GET",
            url: window.JSON_URLS.bs_idp_archivist_json_update_box,
            data: $datas,
            cache: false,
            success: function( ){
                $('#LinkBoxModal').modal('hide');
                $('#listarchives').bootstrapTable('refresh');
                $_recheck = true;   // Activate post reload rechecking
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $message = xhr.responseJSON;
                bootbox.alert( {
                    message: $message?$message.message:$_translations[147],
                    className: "boxSysErrorOne"
                });
                $('#LinkBoxModal').modal('hide');
            }
        });
    }
});

// -----------------------------------------------------------------------
// Modification functions
$('#divSubmitModif').click(function(){
    onClickBtnSubmitModif();
    currentOverlayViewUA = null;
    return true;
});

function onClickBtnSubmitModif(){
    if( verifyMandatories() ){
        $dataObject = {
            'id': $('#frm_id').val(),
            'service': ( $("#frm_service option:selected").val() != "" )?parseInt( $("#frm_service option:selected").val() ):-1,
            'ordernumber': $('#frm_ordernumber').val(),
            'legalentity': ( $("#frm_legalentity option:selected").val() != "" )?parseInt( $("#frm_legalentity option:selected").val() ):-1,
            'budgetcode': ( $("#frm_budgetcode option:selected").val() != "" )?parseInt( $("#frm_budgetcode option:selected").val() ):-1,
            'documentnature': ( $("#frm_documentnature option:selected").val() != "" )?parseInt( $("#frm_documentnature option:selected").val() ):-1,
            'documenttype': ( $("#frm_documenttype option:selected").val() != "" )?parseInt( $("#frm_documenttype option:selected").val() ):-1,
            'description1': ( $("#frm_description1 option:selected").val() != "" )?parseInt( $("#frm_description1 option:selected").val() ):-1,
            'description2': ( $("#frm_description2 option:selected").val() != "" )?parseInt( $("#frm_description2 option:selected").val() ):-1,
            'provider': ( $("#frm_provider option:selected").val() != "" )?parseInt( $("#frm_provider option:selected").val() ):-1,
            'closureyear': $('#frm_closureyear').val(),
            'destructionyear': $('#frm_destructionyear').val(),
            'documentnumber': $('#frm_documentnumber').val(),
            'boxnumber': $('#frm_boxnumber').val(),
            'containernumber': $('#frm_containernumber').val(),
            'name': $('#frm_name').val(),
            'limitdatemin': $('#frm_limitdatemin').val(),
            'limitdatemax': $('#frm_limitdatemax').val(),
            'limitnummin': $('#frm_limitnummin').val(),
            'limitnummax': $('#frm_limitnummax').val(),
            'limitalphamin': $('#frm_limitalphamin').val(),
            'limitalphamax': $('#frm_limitalphamax').val(),
            'limitalphanummin': $('#frm_limitalphanummin').val(),
            'limitalphanummax': $('#frm_limitalphanummax').val(),
            'localization': ( $("#frm_localization option:selected").val() != "" )?parseInt($("#frm_localization option:selected").val()):-1,
            'localizationfree': $('#frm_localizationfree').val(),
            'unlimited': $('#frm_unlimited').prop('checked')?1:0,
            'commentsunlimited': currentCommentsUnlimited
        };

        $.ajax({
            type: "GET",
            url: window.JSON_URLS.bs_idp_archive_modify_ajax,
            data: $dataObject,
            cache: false,
            success: function($response) {
                $('#viewArchive').hide();
                $('#listarchives').bootstrapTable('refresh');
                $('#divPrint').show();
                if ($_buttonsOverlay & 16)
                    $('#divPrintTag').show();

            },
            error: function (xhr, ajaxOptions, thrownError) {
                $message = xhr.responseJSON;
                bootbox.alert( {
                    message: $message?$message.message:$_translations[147],
                    className: "boxSysErrorOne"
                });
                $('#viewArchive').hide();
            }
        });
    } else {
        $('#modalErrorMessage').modal( 'show' );
    }
    return true;
};

//....................................................................................................
// This function verifies all mandatory fields of the provider connector popup dialog
// Set to has-error or has-success depending for each field
function verifyPCBMandatories() {
    $retour = true;

    $text = $('#frm_pcb_contact').val();
    if( $text.trim() == '' ){
        $retour = false;
        $('#div_pcb_contact').addClass('has-error');
    } else
        $('#div_pcb_contact').addClass('has-success');

    $text = $('#frm_pcb_phone').val();
    if( $text.trim() == '' ){
        $retour = false;
        $('#div_pcb_phone').addClass('has-error');
    } else
        $('#div_pcb_phone').addClass('has-success');

    $text = $('#frm_pcb_address').val();
    if( $text.trim() == '' ){
        $retour = false;
        $('#div_pcb_address').addClass('has-error');
    } else
        $('#div_pcb_address').addClass('has-success');

    if( $uawhat == UAWHAT_DESTROY ) {
        $text = $('#frm_pcb_name').val();
        if ($text.trim() == '') {
            $retour = false;
            $('#div_pcb_name').addClass('has-error');
        } else
            $('#div_pcb_name').addClass('has-success');

        $text = $('#frm_pcb_firstname').val();
        if ($text.trim() == '') {
            $retour = false;
            $('#div_pcb_firstname').addClass('has-error');
        } else
            $('#div_pcb_firstname').addClass('has-success');

        $text = $('#frm_pcb_function').val();
        if ($text.trim() == '') {
            $retour = false;
            $('#div_pcb_function').addClass('has-error');
        } else
            $('#div_pcb_function').addClass('has-success');
    }

    return $retour;
}
//....................................................................................................
// This function cleans the PCB dialog for each field with error colours
function clearPCBView() {
    $('#div_pcb_contact').removeClass('has-success');
    $('#div_pcb_contact').removeClass('has-error');
    $('#div_pcb_phone').removeClass('has-success');
    $('#div_pcb_phone').removeClass('has-error');
    $('#div_pcb_address').removeClass('has-success');
    $('#div_pcb_address').removeClass('has-error');
    $('#div_pcb_name').removeClass('has-success');
    $('#div_pcb_name').removeClass('has-error');
    $('#div_pcb_firstname').removeClass('has-success');
    $('#div_pcb_firstname').removeClass('has-error');
    $('#div_pcb_function').removeClass('has-success');
    $('#div_pcb_function').removeClass('has-error');
}

function verifyMandatories() {
    clearViewClass();

    $retour = true;
    if( $('#frm_service option:selected').val() == '' ){
        $retour = false;
        $('#divViewSelectService').addClass('has-error');
    } else {
        $('#divViewSelectService').addClass('has-success');
    }
    if( $('#frm_legalentity option:selected').val() == '' ){
        $retour = false;
        $('#divViewSelectLegalentity').addClass('has-error');
    } else {
        $('#divViewSelectLegalentity').addClass('has-success');
    }
    if( $('#frm_name').val().trim() == ''){
        $retour = false;
        $('#form_name').addClass('has-error');
    } else {
        $('#form_name').addClass('has-success');
    }
    if( $('#frm_closureyear').val().trim() == '' ){
        $retour = false;
        $('#divViewInputClosureyear').addClass('has-error');
    } else {
        $('#divViewInputClosureyear').addClass('has-success');
    }
    if( $('#frm_destructionyear').val().trim() == ''){
        $retour = false;
        $('#divViewInputDestructionyear').addClass('has-error');
    } else {
        // verify only if destruction year is editable
        $documenttypeIdx = parseInt( $("#frm_documenttype option:selected").attr('data') );
        $destructionTime = $documenttypeIdx?$documentTypes[$documenttypeIdx][DOCUMENTTYPE_KEEPALIVEDURATION]:0;

        // If destruction time is 0, user should enter whatever he wants, instead destruction time is forced
        if($destructionTime == 0) {
            if (parseInt($('#frm_destructionyear').val().trim()) > 2199) {
                $retour = false;
                popError( $('#frm_destructionyear'), $_translations[161], 'top' );
                $('#divViewInputDestructionyear').addClass('has-error');
            } else
                $('#divViewInputDestructionyear').addClass('has-success');
        } else
            $('#divViewInputDestructionyear').addClass('has-success');
    }
    if( $_settings.view_budgetcode && $_settings.mandatory_budgetcode && $('#frm_budgetcode option:selected').val() == '' ){
        $retour = false;
        $('#divViewSelectBudgetcode').addClass('has-error');
    } else {
        $('#divViewSelectBudgetcode').addClass('has-success');
    }
    if( $_settings.view_documentnature && $_settings.mandatory_documentnature && $('#frm_documentnature option:selected').val() == '' ){
        $retour = false;
        $('#divViewSelectDocumentnature').addClass('has-error');
    } else {
        $('#divViewSelectDocumentnature').addClass('has-success');
    }
    if( $_settings.view_documentnature && $_settings.view_documenttype && $_settings.mandatory_documenttype && $('#frm_documenttype option:selected').val() == '' ){
        $retour = false;
        $('#divViewSelectDocumenttype').addClass('has-error');
    } else {
        $('#divViewSelectDocumenttype').addClass('has-success');
    }
    if( $_settings.view_description1 && $_settings.mandatory_description1 && $('#frm_description1 option:selected').val() == '' ){
        $retour = false;
        $('#divViewSelectDescription1').addClass('has-error');
    } else {
        $('#divViewSelectDescription1').addClass('has-success');
    }
    if( $_settings.view_description2 && $_settings.mandatory_description2 && $('#frm_description2 option:selected').val() == '' ){
        $retour = false;
        $('#divViewSelectDescription2').addClass('has-error');
    } else {
        $('#divViewSelectDescription2').addClass('has-success');
    }
    $test1 = /^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/.test( $('#frm_limitdatemin').val() );
    $test2 = /^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/.test( $('#frm_limitdatemax').val() );
    $test_RegexValidDate_DateMin = ($('#frm_limitdatemin').val() == '')?true:$test1;
    $test_RegexValidDate_DateMax = ($('#frm_limitdatemax').val() == '')?true:$test2;
    $empty1 = $('#frm_limitdatemin').val().trim() == '';
    $empty2 = $('#frm_limitdatemax').val().trim() == '';
    if( ( $_settings.mandatory_limitsdate && ( $empty1 || $empty2 ) ) ||
        ( !$_settings.mandatory_limitsdate && ( ( !$empty1 && $empty2 ) || ( $empty1 && !$empty2 ) ) ) ||
        ( !$test_RegexValidDate_DateMin || !$test_RegexValidDate_DateMax )){
        $retour = false;
        if( $empty1 || $test_RegexValidDate_DateMin == false ){
            $('#divViewInputLimitsdatemin').addClass('has-error');
        } else {
            $('#divViewInputLimitsdatemin').addClass('has-success');
        }
        if( $empty2 || $test_RegexValidDate_DateMax == false ){
            $('#divViewInputLimitsdatemax').addClass('has-error');
        } else {
            $('#divViewInputLimitsdatemax').addClass('has-success');
        }
    } else {
        $('#divViewInputLimitsdatemin').addClass('has-success');
        $('#divViewInputLimitsdatemax').addClass('has-success');
    }
    $test1 = /^[0-9]+$/.test( $('#frm_limitnummin').val() );
    $test2 = /^[0-9]+$/.test( $('#frm_limitnummax').val() );
    $test_RegexOnlyNumber_NumMin = ($('#frm_limitalphamin').val() == '')?true:$test1;
    $test_RegexOnlyNumber_NumMax = ($('#frm_limitalphamax').val() == '')?true:$test2;
    $empty1 = $('#frm_limitalphamin').val().trim() == '';
    $empty2 = $('#frm_limitalphamax').val().trim() == '';
    if( ( $_settings.mandatory_limitsnum && ( $empty1 && $empty2 ) ) ||
        ( !$_settings.mandatory_limitsnum && ( ( !$empty1 && $empty2 ) || ( $empty1 && !$empty2 ) ) ) ||
        ( !$test_RegexOnlyNumber_NumMin || !$test_RegexOnlyNumber_NumMax )){
        $retour = false;
        if( $empty1 || $test_RegexOnlyNumber_NumMin == false ){
            $('#divViewInputLimitsnummin').addClass('has-error');
        } else {
            $('#divViewInputLimitsnummin').addClass('has-success');
        }
        if( $empty2 || $test_RegexOnlyNumber_NumMax == false ){
            $('#divViewInputLimitsnummax').addClass('has-error');
        } else {
            $('#divViewInputLimitsnummax').addClass('has-success');
        }
    } else {
        $('#divViewInputLimitsnummin').addClass('has-success');
        $('#divViewInputLimitsnummax').addClass('has-success');
    }
    $test1 = /^[a-zA-Z]+$/.test( $('#frm_limitalphamin').val() );
    $test2 = /^[a-zA-Z]+$/.test( $('#frm_limitalphamax').val() );
    $test_RegexOnlyChar_AlphaMin = ($('#frm_limitalphamin').val() == '')?true:$test1;
    $test_RegexOnlyChar_AlphaMax = ($('#frm_limitalphamax').val() == '')?true:$test2;
    $empty1 = $('#frm_limitalphamin').val().trim() == '';
    $empty2 = $('#frm_limitalphamax').val().trim() == '';
    if( ( $_settings.mandatory_limitsalpha && ( $empty1 && $empty2 ) ) ||
        ( !$_settings.mandatory_limitsalpha && ( ( !$empty1 && $empty2 ) || ( $empty1 && !$empty2 ) ) ) ||
        ( !$test_RegexOnlyChar_AlphaMin || !$test_RegexOnlyChar_AlphaMax ) ){
        $retour = false;
        if( $empty1 || $test_RegexOnlyChar_AlphaMin == false ){
            $('#divViewInputLimitsalphamin').addClass('has-error');
        } else {
            $('#divViewInputLimitsalphamin').addClass('has-success');
        }
        if( $empty2 || $test_RegexOnlyChar_AlphaMax == false ){
            $('#divViewInputLimitsalphamax').addClass('has-error');
        } else {
            $('#divViewInputLimitsalphamax').addClass('has-success');
        }
    } else {
        $('#divViewInputLimitsalphamin').addClass('has-success');
        $('#divViewInputLimitsalphamax').addClass('has-success');
    }
    $empty1 = $('#frm_limitalphanummin').val().trim() == '';
    $empty2 = $('#frm_limitalphanummax').val().trim() == '';
    if( ( $_settings.mandatory_limitsalphanum && ( $empty1 && $empty2 ) ) ||
        ( !$_settings.mandatory_limitsalphanum && ( ( !$empty1 && $empty2 ) || ( $empty1 && !$empty2 ) ) ) ) {
        $retour = false;
        if( $empty1 ){
            $('#divViewInputLimitsalphanummin').addClass('has-error');
        } else {
            $('#divViewInputLimitsalphanummin').addClass('has-success');
        }
        if( $empty2 ){
            $('#divViewInputLimitsalphanummax').addClass('has-error');
        } else {
            $('#divViewInputLimitsalphanummax').addClass('has-success');
        }
    } else {
        $('#divViewInputLimitsalphanummin').addClass('has-success');
        $('#divViewInputLimitsalphanummax').addClass('has-success');
    }
    if( $_settings.view_filenumber && $_settings.mandatory_filenumber && $('#frm_documentnumber').val().trim() == '' ){
        $retour = false;
        $('#divViewInputFilenumber').addClass('has-error');
    } else {
        $('#divViewInputFilenumber').addClass('has-success');
    }
    if( $_settings.view_boxnumber && $_settings.mandatory_boxnumber && $('#frm_boxnumber').val().trim() == '' ){
        $retour = false;
        $('#divViewInputBoxnumber').addClass('has-error');
    } else {
        $('#divViewInputBoxnumber').addClass('has-success');
    }
    if( $_settings.view_containernumber && $_settings.mandatory_containernumber && $('#frm_containernumber').val().trim() == '' ){
        $retour = false;
        $('#divViewInputContainernumber').addClass('has-error');
    } else {
        $('#divViewInputContainernumber').addClass('has-success');
    }
    if( $_settings.view_provider && $_settings.mandatory_provider && $('#frm_provider option:selected').val() == '' ){
        $retour = false;
        $('#divViewSelectProvider').addClass('has-error');
    } else {
        $('#divViewSelectProvider').addClass('has-success');
    }
    if( true /* $uawhere == UAWHERE_PROVIDER */ ){ // Only in provider mode here
        if( $('#frm_localization option:selected').val() == '' ){
            $retour = false;
            $('#divViewSelectLocalization').addClass('has-error');
        } else {
            $('#divViewSelectLocalization').addClass('has-success');
        }
    } else {
        if( $('#frm_localizationfree').val().trim() == '' ){
            $retour = false;
            $('#divViewInputLocalizationfree').addClass('has-error');
        } else {
            $('#divViewInputLocalizationfree').addClass('has-success');
        }
    }
    return $retour;
};

// -----------------------------------------------------------------------
// Provider Connector

function showComplementaryProviderConnectorModal(){
    // Set all to unvisible
    $('#div_frm_deliver').hide();
    $('#div_frm_type').hide();
    $('#div_frm_type2').hide();
    $('#div_frm_disposal').hide();
    $('#div_frm_sign').hide();
    $('#frm_pcb_type1_1').prop('disabled', false );
    $('#frm_pcb_type1_2').prop('disabled', false );

    $bRelocConnectorConsult = false;
    // verify if we are in a Reloc Consult type
    $actionList.forEach( function( $elem ){
       if( $.inArray( $elem['statuscaps'], RELOC_CONNECTOR_CONSULT ) >= 0 )
           $bRelocConnectorConsult = true;
    });

    switch( $uawhat ){
        case UAWHAT_TRANSFER:
            break;
        case UAWHAT_CONSULT: // = Consultation
            $('#div_frm_deliver').show();
            $('#div_frm_type').show();
            $('#div_frm_disposal').show();
            $('#frm_pcb_type1_1').prop('checked', true );
            $('#frm_pcb_type1_2').prop('checked', false );
            $('#frm_pcb_type1_1').prop('disabled', true );
            $('#frm_pcb_type1_2').prop('disabled', true );
            break;
        case UAWHAT_RETURN:
            break;
        case UAWHAT_EXIT:
            $('#div_frm_deliver').show();
            $('#div_frm_type').show();
            $('#frm_pcb_type1_1').prop('checked', false );
            $('#frm_pcb_type1_2').prop('checked', true );
            $('#frm_pcb_type1_1').prop('disabled', true );
            $('#frm_pcb_type1_2').prop('disabled', true );
            break;
        case UAWHAT_DESTROY:
            $('#div_frm_type2').show();
            $('#div_frm_sign').show();
            break;
        case UAWHAT_RELOC:
            if( $bRelocConnectorConsult ) {  // It is like a consult
                $('#div_frm_deliver').show();
                $('#div_frm_type').show();
                $('#div_frm_disposal').show();
                $('#frm_pcb_type1_1').prop('checked', true);
                $('#frm_pcb_type1_2').prop('checked', false);
                $('#frm_pcb_type1_1').prop('disabled', true);
                $('#frm_pcb_type1_2').prop('disabled', true);

                // E#311
                $('#frm_pcb_disposal_1').prop('checked', true);
                $('#frm_pcb_disposal_1').prop('disabled', true);
                $('#frm_pcb_disposal_2').prop('disabled', true);
                $('#frm_pcb_disposal_3').prop('disabled', true);
                $('#frm_pcb_disposal_4').prop('disabled', true);
            }
            break;
        default:
            return true;
    }
    $('#ProviderConnectorModal').modal({
        backdrop: 'static',             // Don't close if click in dark
        keyboard: true
    });
    /*
    $('#ProviderConnectorModal').on('hidden.bs.modal', function () {
        // do something…
    });*/
    clearPCBView();
    $('#ProviderConnectorModal').modal('show');
    return true;
};

function updateProviderConnectorForm( $data ){
    $pcb = $data[0];

    if( $pcb['contact'] != null )
        $('#frm_pcb_contact').val( $pcb['contact'] );
    if( $pcb['phone'] != null )
        $('#frm_pcb_phone').val( $pcb['phone'] );
    if( $pcb['address'] != null )
        $('#frm_pcb_address').val( $pcb['address'] );
    if( $pcb['deliver'] != null ){
        if( $pcb['deliver'] == 0 )
            $('#frm_pcb_deliver_1').prop('checked', true);
        if( $pcb['deliver'] == 1 )
            $('#frm_pcb_deliver_2').prop('checked', true);
    }
    if( $pcb['type'] != null ){
        if( $pcb['type'] == 0 )
            $('#frm_pcb_type1_1').prop('checked', true);
        if( $pcb['type'] == 1 )
            $('#frm_pcb_type1_2').prop('checked', true);
    }
    if( $pcb['type2'] != null ){
        if( $pcb['type2'] == 0 )
            $('#frm_pcb_type2_1').prop('checked', true);
        if( $pcb['type2'] == 1 )
            $('#frm_pcb_type2_2').prop('checked', true);
    }
    if( $pcb['disposal'] != null ){
        if( $pcb['disposal'] == 0 )
            $('#frm_pcb_disposal_1').prop('checked', true);
        if( $pcb['disposal'] == 1 )
            $('#frm_pcb_disposal_2').prop('checked', true);
        if( $pcb['disposal'] == 2 )
            $('#frm_pcb_disposal_3').prop('checked', true);
        if( $pcb['disposal'] == 3 )
            $('#frm_pcb_disposal_4').prop('checked', true);
    }
    if( $pcb['remark'] != null )
        $('#frm_pcb_remark').html( $pcb['remark'] );
    if( $pcb['name'] != null )
        $('#frm_pcb_name').val( $pcb['name'] );
    if( $pcb['firstname'] != null )
        $('#frm_pcb_firstname').val( $pcb['firstname'] );
    if( $pcb['function'] != null )
        $('#frm_pcb_function').val( $pcb['function'] );
}



$( "#OptimisationModal" ).on( "hidden.bs.modal", function( event, ui ) {
    // Send ajax to unlock uas optimized
    ungray_UAs_Optimized();
} );
$( "#DelOptimisationModal" ).on( "hidden.bs.modal", function( event, ui ) {
    // Send ajax to unlock uas optimized
    ungray_UAs_Optimized();
} );
//=====================================================================================================
// Optimization Popup Rendering
const DEBUG_OPTIM_RENDERING = false;
//-----------------------------------------------------------------------------------------------------
// Function: updateVerificationStruct
// Goal: update verification structure
// Parameters:
//      $UALine: UALine to analyze
//      $verificationStruct: Struct to modify,
//      $alreadyCheckedListID: List of ID user put in basket
//      $statusesAllowedToBeChecked: List of status checkable
// Returns:
//      Nothing

function updateVerificationStruct( $UALine, $verificationStruct, $alreadyCheckedListID, $statusesAllowedToBeChecked ){
    if( $UALine['containerasked'] > 1 )     // 1 is for basket only not mandatory
        $verificationStruct.bContainerAsked = true;
    if( $UALine['boxasked'] > 1 )
        $verificationStruct.bBoxAsked = true;

    if( $verificationStruct.bContainerAsked || $verificationStruct.bBoxAsked ){
        $verificationStruct.bCheck = true;
        $verificationStruct.bDisable = true;
    } else {
        if( $alreadyCheckedListID.indexOf($UALine['id']) >= 0 )
            $verificationStruct.bCheck = true;
        else
            $verificationStruct.bAllChecked = false;
    }

    if( $.inArray($UALine['statuscaps'], $statusesAllowedToBeChecked) < 0 )
        $verificationStruct.bDisable = true;

    return $verificationStruct;
}
//-----------------------------------------------------------------------------------------------------
// Function: generateUALineFlow
// Goal: Create UA to update current flow
// Parameters:
//      $UALine: UALine to analyze
//      $alreadyCheckedListID: List of ID user put in basket
//      $statusesAllowedToBeChecked: List of status checkable
//      $verificationStruct: obvious,
// Returns: <span class="list-group-item text-COLOR"><input type="checkbox" id="cbID" value="ID" CHECKED="CHECKED"
//  DISABLED="DISABLED">TEXT OF UA</span>

function generateUALineFlow( $UALine, $alreadyCheckedListID, $statusesAllowedToBeChecked, $verificationStruct,
                             $entityType ){
    var $returnContent = '<span class="list-group-item ';
    // Colors ==> primary: if in basket / default: if in optimization and checkable / danger: otherwise
    $returnContent += ($alreadyCheckedListID.indexOf($UALine['id']) >= 0) ?
        'text-primary' :
        ($.inArray($UALine['statuscaps'], $statusesAllowedToBeChecked) >= 0) ?
            'text-default' : 'text-danger';
    $returnContent += '"><input type="checkbox" id="cb' + $UALine['id'] + '" value="' + $UALine['id'] + '"  ';
    // Checkbox state management
    if( $verificationStruct.bCheck )
        $returnContent += ' checked="checked" ';
    if( $verificationStruct.bDisable )
        $returnContent += ' disabled="disabled" ';

    $returnContent += '/>&nbsp;';

    // Generate label for this line
    let $bFirst = true;
    if( $UALine['boxnumber'] != null && $UALine['boxnumber'].length > 0 && $entityType <= 1 ){
        $bFirst = false;
        $returnContent += $_translations[163] + ' ' + $UALine['boxnumber'];
    }
    if( $UALine['documentnumber'] != null && $UALine['documentnumber'].length > 0 && $entityType <= 3 ){
        if( $bFirst )
            $bFirst = false;
        else
            $returnContent += ' / ';

        $returnContent += $_translations[164] + ' ' + $UALine['documentnumber'];
    }
    if( $bFirst )
        $bFirst = false;
    else
        $returnContent += ' / ';
    $returnContent += $_translations[165] + ': ' + $UALine['name'];

    // If not checkable, add Status to label
    if( !$verificationStruct.bCheck && $.inArray( $UALine['statuscaps'], $statusesAllowedToBeChecked ) < 0 )
        $returnContent += ' [' + $UALine['status'] + ']';
    $returnContent += '</span>';

    return $returnContent;
}

//-----------------------------------------------------------------------------------------------------
// Function: createEmptyEntity
// Goal: This function returns a empty entity object
function createEmptyEntity( ){
    var $emptyStruct = {
        identification : null,
        content : '',
        headerBegin : '',
        headerEnd: '',
        scriptBegin: '',
        toggleOn: '',
        toggleOff: '',
        entityType: 0,
        $checkboxID: ''
    };
    return $emptyStruct;
}
//-----------------------------------------------------------------------------------------------------
// Function: reinitVerificationStruct
// Goal: This function initialise verificationStruct
function reinitVerificationStruct( $verificationStruct ){
    $verificationStruct.bCheck = false;
    $verificationStruct.bDisable = false;
    $verificationStruct.bAllChecked = true;
    $verificationStruct.bContainerAsked = false;
    $verificationStruct.bBoxAsked = false;

    return $verificationStruct;
}

//-----------------------------------------------------------------------------------------------------
// Function: initializeEntity
// Goal: This function initialize entity object depending on entity type and UALine
// $entityType = 1 ==> Container / $entityType = 2 ==> Box / $entityType = 3 ==> subbox / $entityType = 4 ==> ROA
function initializeEntity( $entity, $entityType, $UALine, $verificationStruct  ) {
    if( $entity.identification || ( $.inArray($entityType, [1,2,3,4]) < 0 ))    // Only with a empty entity
        return null;

    $entity.identification = $entityType == 1 ? $UALine['containernumber'] : ( $entityType == 2 || $entityType == 3 ) ?
        $UALine['boxnumber'] : 'ROA';
    $entity.entityType = $entityType;

    var $nameTrimed = '';
    var $panelID = '';
    var $panelHeadID = '';
    var $panelCheckboxID = '';
    var $panelCollapseID = '';
    var $nameIdentification = '';
    var $valueCheckBox = -1;

    // Container
    if( $entityType == 1 ){
        $nameTrimed = $UALine['containernumber'].replace(/\s+/g, '') + '_' + $UALine['suid'];
        $panelID = 'pContainer_' + $nameTrimed;
        $panelHeadID = 'phContainer_' + $nameTrimed;
        $panelCheckboxID = 'C_' + $nameTrimed;
        $panelCollapseID = 'pcContainer_List_' + $nameTrimed;
        $nameIdentification = ' ' + $_translations[162] + ' ' + $entity.identification;

    // Box
    } else if( $entityType == 2 ){
        $nameTrimed = $UALine['boxnumber'].replace(/\s+/g, '') + '_' + $UALine['suid'];
        $panelID = 'pBox_' + $nameTrimed;
        $panelHeadID = 'phBox_' + $nameTrimed;
        $panelCheckboxID = 'C_' + $nameTrimed;
        $panelCollapseID = 'pcBox_List_' + $nameTrimed;
        $nameIdentification = ' ' + $_translations[163] + ' ' + $entity.identification;

    // Sub box
    } else if( $entityType == 3 ){
        $nameTrimed = $UALine['containernumber'].replace(/\s+/g, '') + '_' + $UALine['suid'] + '_SB_' +
            $UALine['boxnumber'];
        $panelID = 'pContainer_' + $nameTrimed;
        $panelHeadID = 'phContainer_' + $nameTrimed;
        $panelCheckboxID = 'C_' + $nameTrimed;
        $panelCollapseID = 'pcContainer_List_' + $nameTrimed;
        $nameIdentification = ' ' + $_translations[163] + ' ' + $entity.identification;
        $valueCheckBox = -2;

    // Other UAs
    } else {
        $panelID = 'pRoa';
        $panelHeadID = 'phRoa';
        $panelCollapseID = 'pcRoa_List';
        $nameIdentification = ' ' + $_translations[166] + ' ';

    }

    $entity.checkboxID = $panelCheckboxID;

    $entity.headerBegin = '<div class="panel panel-default mb5" id="' + $panelID + '"> '
        + '<div class="panel-heading" role="tab" id="' + $panelHeadID + '" > '
        + '<h4 class="panel-title">';
    if( $entityType != 4 )
        $entity.headerBegin += '<input type="checkbox" id="' + $panelCheckboxID + '" value="'+$valueCheckBox+'" ';

    $entity.headerEnd = ($entityType != 4) ? '/>' : '';
    $entity.headerEnd += '<a class="' + ( !$verificationStruct.bFirst ? 'collapsed' : '' )
        + '"  data-toggle="collapse" data-parent="#OptimisationModalBody" href="#' + $panelCollapseID
        + '" aria-expanded="' + ( $verificationStruct.bFirst ? 'true' : 'false' )
        + '" aria-controls="' + $panelCollapseID + '">'
        + $nameIdentification + '</a></h4></div>'
        + '<div id="' + $panelCollapseID + '" class="panel-collapse collapse list-group p2 '
        + ( $verificationStruct.bFirst ? 'in' : '' )
        + '" role="tabpanel" aria-labelledby="' + $panelHeadID + '">';

    if( $entityType != 4 )
        $entity.scriptBegin = "<script>$('#" + $panelCheckboxID + "').change(function(){ if( this.checked ){ ";

    return $entity;
}

//-----------------------------------------------------------------------------------------------------
// Function: closeAndRenderEntity
// Goal:
//      This function returns a renderer for a given entity structure
function closeAndRenderEntity( $entity, $verificationStruct, $delMode ){
    var $returnContent = '';

    $returnContent += $entity.headerBegin;

    if( $entity.entityType != 4 )
        $returnContent += /*$verificationStruct.bContainerAsked || $verificationStruct.bBoxAsked ||*/ $delMode ?
            ' checked="checked" disabled="disabled" ' :
            $verificationStruct.bAllChecked ? ' checked="checked" ' : '';
    else
        if( $delMode ) $returnContent += ' disabled="disabled" ';

    $returnContent += $entity.headerEnd;
    $returnContent += $entity.content + '</div></div>';

    // No Script for ROA, or for all if in delMode
    if( $entity.entityType != 4 && !$delMode)
        $returnContent += $entity.scriptBegin + $entity.toggleOn + ' } else { ' + $entity.toggleOff + ' } });</script>';

    return $returnContent;
}

//-----------------------------------------------------------------------------------------------------
// Function: createToggleScript
// Goal:
//      This function returns javascript code to toggle on/off this UA
function createToggleScript( $UALine, $bOnOff, $statusesAllowedToBeChecked, $alreadyCheckedListID ) {
    var $toggle = '';
    if( $bOnOff ){  // This is for toggleOn
        if( $.inArray( $UALine['statuscaps'], $statusesAllowedToBeChecked ) >= 0 )
            $toggle = " $('#cb" + $UALine['id'] + "').prop('checked', true); ";
        $toggle += " $('#cb" + $UALine['id'] + "').prop('disabled', true); ";
    } else { // This is for toggleOff
        if( $alreadyCheckedListID.indexOf( $UALine['id'] ) < 0 )
            $toggle = " $('#cb" + $UALine['id'] + "').prop('checked', false); ";
        $toggle += " $('#cb" + $UALine['id'] + "').prop('disabled', false); ";
    }
    return $toggle;
}

//-----------------------------------------------------------------------------------------------------
// Function: createContainerSubBoxScript
// Goal:
//      This function returns javascript code to manage subboxes when container is checked/unchecked
function createContainerSubBoxScript( $subBoxEntity, $bOnOff ) {
    var $toggle = '';
    if( $bOnOff ) {  // This is for toggleOn of Container
        //$toggle = " $('#" + $subBoxEntity.checkboxID + "').prop('checked', false); ";
        $toggle += " $('#" + $subBoxEntity.checkboxID + "').prop('disabled', true); ";
    } else { // This is for toggleOff of Container
        $toggle = " $('#" + $subBoxEntity.checkboxID + "').prop('disabled', false); ";
    }
    return $toggle;
}

//-----------------------------------------------------------------------------------------------------
// Function: updatePopupOptimizationModal_v2 (main function)
// Goal:
//      This function renders the Optimization modal popup
// Parameters:
//      $optimizedList: List of UAs needed for optimization (calculated by the server), we assume that server returns
//          this list sorted by container, then boxes, then documents, then service,
//      $alreadyCheckedListID: List of IDs of UAs selected by the user
//      $delMode: true or false
// Returns:
//      Nothing
// Remarks:
//      Version 02 ( Take care of Box in Container also + Algorythm rewrite
//      updateVerificationStruct() is called just before generateUALineFlow in order to use previous struct when
//          closing box or container
// Algorythm:
//      Parse each line one after one, if it's a container, add it to current one, if not and if it's a box, add it to
//      current one, otherwise add it to none optimizable UAs
//      Also take care of scripts (check), also take care of checkables

function updatePopupOptimizationModal_v2( $optimizedList, $alreadyCheckedListID, $delMode ) {
    if( DEBUG_OPTIM_RENDERING ) console.log( 'ENTER updatePopupOptimizationModal_v2' );
    // Verification structure to determine behavior along parsing
    var $containerVerifStruct = {
        bCheck: false,
        bDisable: false,
        bAllChecked: true,
        bContainerAsked: false,
        bBoxAsked: false,       // unused for container
        bFirst: true
    };
    var $boxVerifStruct = {
        bCheck: false,
        bDisable: false,
        bAllChecked: true,
        bContainerAsked: false, // unused for box
        bBoxAsked: false,
        bFirst: true
    };
    var $otherVerifStruct = {
        bCheck: false,
        bDisable: false,
        bAllChecked: true,
        bContainerAsked: false, // unused for other
        bBoxAsked: false,   // unused for other
        bFirst: true
    };

    var $mainContent = '';
    var $currentSUID = null;
    var $currentContainer = createEmptyEntity();
    var $currentSubBox = createEmptyEntity();
    var $currentBox = createEmptyEntity();
    var $otherUAs = createEmptyEntity();


    // TODO Repenser l'algorithme en ajoutant les lignes à une structure plus grande, et en faisant le rendu uniquement
    //  en cloture de cette structure et en propageant à la structure du dessus; sinon on ne peut pas gérer les checkbox
    //  correctement
    // document seul ==> ok
    // boite avec documents dedans ==> ok
    // boite seule ==> ne fonctionne pas
    // conteneur avec boite sans document ==> ne fonctionne pas ==> boite = document !!
    // conteneur avec boite avec document ==> ne fonctionne pas
    // conteneur sans boite sans document ==> ne fonctionne pas

    //
    if( !$delMode )
        var $statusesAllowedToBeChecked = ['DISP', 'GLAP', 'GPAP', 'GRLPDAI', 'GRLPDAINT', 'CLAP', 'CPAP', 'CRLPDAI', 'CRLPDAINT'];
    else
        var $statusesAllowedToBeChecked = ['DISP', 'GDAP', 'CDAP'];

    for (let $i = 0, $len = $optimizedList.length; $i < $len; $i++) {
        // Extract current line
        let $UALine = $optimizedList[$i];
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'Current Line: ' + $i );
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $UALine );
        // Verify if this line concerns a container
        if ($UALine['containernumber'] != null) {
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'We are in a Container !' );
            // Verify if we are in the same container
            if ($UALine['containernumber'] == $currentContainer.identification && $UALine['suid'] == $currentSUID) {
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'Same Container than before !' );
                // Verify if we are in a box
                if ($UALine['boxnumber'] != null) {
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'We are in a subBox of Container !' );
                    // Verify if we are in the same subbox
                    if ($currentSubBox.identification == $UALine['boxnumber']) {
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'Same subBox, so generate Line Flow in currentSubBox.content' );
                        $boxVerifStruct = updateVerificationStruct($UALine, $boxVerifStruct, $alreadyCheckedListID,
                            $statusesAllowedToBeChecked );
                        $containerVerifStruct = updateVerificationStruct($UALine, $containerVerifStruct,
                            $alreadyCheckedListID, $statusesAllowedToBeChecked );

                        $currentSubBox.content += generateUALineFlow($UALine, $alreadyCheckedListID,
                            $statusesAllowedToBeChecked, $boxVerifStruct, 3);
                        // Add script to SubBox And Container
                        $currentSubBox.toggleOn += createToggleScript( $UALine, true,
                            $statusesAllowedToBeChecked, $alreadyCheckedListID );
                        $currentSubBox.toggleOff += createToggleScript( $UALine, false,
                            $statusesAllowedToBeChecked, $alreadyCheckedListID );
                        $currentContainer.toggleOn += createToggleScript( $UALine, true,
                            $statusesAllowedToBeChecked, $alreadyCheckedListID );
                        $currentContainer.toggleOff += createToggleScript( $UALine, false,
                            $statusesAllowedToBeChecked, $alreadyCheckedListID );
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $currentSubBox  );
                    } else {
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'This is a new subBox !' );
                        // We changed subbox, close old one (if exists) create new one, and add to content
                        if ($currentSubBox.identification) {
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'There was a previous one, so close it first, render it inside currentContainer.content !' );
                            $currentContainer.content += closeAndRenderEntity($currentSubBox, $boxVerifStruct, $delMode);
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $currentContainer.content  );
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'Create a new empty subBox, and initialize it !' );
                            $currentSubBox = createEmptyEntity();
                            $currentSubBox = initializeEntity($currentSubBox, 3, $UALine, $boxVerifStruct );
                            // Add script to Container
                            $currentContainer.toggleOn += createContainerSubBoxScript( $currentSubBox, true );
                            $currentContainer.toggleOff += createContainerSubBoxScript( $currentSubBox, false );
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $currentSubBox );
                        }
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'Generate Line flow in current subbox.content' );
                        $boxVerifStruct = reinitVerificationStruct( $boxVerifStruct);
                        $boxVerifStruct = updateVerificationStruct($UALine, $boxVerifStruct, $alreadyCheckedListID,
                            $statusesAllowedToBeChecked );
                        $containerVerifStruct = updateVerificationStruct($UALine, $containerVerifStruct,
                            $alreadyCheckedListID, $statusesAllowedToBeChecked );

                        $currentSubBox.content += generateUALineFlow($UALine, $alreadyCheckedListID,
                            $statusesAllowedToBeChecked, $boxVerifStruct, 3);
                        // Add script to SubBox And Container
                        $currentSubBox.toggleOn += createToggleScript( $UALine, true,
                            $statusesAllowedToBeChecked, $alreadyCheckedListID );
                        $currentSubBox.toggleOff += createToggleScript( $UALine, false,
                            $statusesAllowedToBeChecked, $alreadyCheckedListID );
                        $currentContainer.toggleOn += createToggleScript( $UALine, true,
                            $statusesAllowedToBeChecked, $alreadyCheckedListID );
                        $currentContainer.toggleOff += createToggleScript( $UALine, false,
                            $statusesAllowedToBeChecked, $alreadyCheckedListID );
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $currentSubBox  );
                    }
                } else {
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'This is a simple line, so render it in current container' );
                    // This is just a simple line, close last subbox before adding a line to the container
                    if ($currentSubBox.identification) {
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'Last subbox is not rendered yet, so do it in currentContainer.content' );
                        $currentContainer.content += closeAndRenderEntity($currentSubBox, $boxVerifStruct, $delMode);
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $currentContainer.content  );
                        $currentSubBox = createEmptyEntity();
                    }
                    $containerVerifStruct = updateVerificationStruct($UALine, $containerVerifStruct,
                        $alreadyCheckedListID, $statusesAllowedToBeChecked );

                    $currentContainer.content += generateUALineFlow($UALine, $alreadyCheckedListID,
                        $statusesAllowedToBeChecked, $containerVerifStruct, 1);
                    // Add script to Container
                    $currentContainer.toggleOn += createToggleScript( $UALine, true,
                        $statusesAllowedToBeChecked, $alreadyCheckedListID );
                    $currentContainer.toggleOff += createToggleScript( $UALine, false,
                        $statusesAllowedToBeChecked, $alreadyCheckedListID );
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $currentContainer  );
                }

            } else {
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'This is not the same container than before' );
                // We changed container
                // Close subbox if one
                if ($currentSubBox.identification) {
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'There is a not closed subbox in the last one, so render it first in currentContainer.content' );
                    $currentContainer.content += closeAndRenderEntity($currentSubBox, $boxVerifStruct, $delMode);
                    $currentSubBox = createEmptyEntity();
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $currentContainer.content  );
                }
                // Close old container if exist
                if ($currentContainer.identification) {
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'And render the container into mainContent' );
                    $mainContent += closeAndRenderEntity($currentContainer, $containerVerifStruct, $delMode);
                    $currentContainer = createEmptyEntity();
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $mainContent );
                }

                $containerVerifStruct = reinitVerificationStruct( $containerVerifStruct );
                $boxVerifStruct = reinitVerificationStruct( $boxVerifStruct );
                if ($currentContainer.identification)
                    $containerVerifStruct.bFirst = false;

                $currentContainer = initializeEntity($currentContainer, 1, $UALine, $containerVerifStruct );
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $currentContainer );
                // Verify if we are in a subbox
                if ($UALine['boxnumber'] != null) {
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'And we are in a subbox, so create a new subbox also' );
                    $currentSubBox = initializeEntity($currentSubBox, 3, $UALine, $boxVerifStruct );
                    // Add script to Container
                    $currentContainer.toggleOn += createContainerSubBoxScript( $currentSubBox, true );
                    $currentContainer.toggleOff += createContainerSubBoxScript( $currentSubBox, false );
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $currentSubBox );
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'And render the new line into the subbox.content' );
                    $boxVerifStruct = updateVerificationStruct($UALine, $boxVerifStruct, $alreadyCheckedListID,
                        $statusesAllowedToBeChecked );
                    $containerVerifStruct = updateVerificationStruct($UALine, $containerVerifStruct,
                        $alreadyCheckedListID, $statusesAllowedToBeChecked );

                    $currentSubBox.content += generateUALineFlow($UALine, $alreadyCheckedListID,
                        $statusesAllowedToBeChecked, $boxVerifStruct, 3);
                    // Add script to SubBox And Container
                    $currentSubBox.toggleOn += createToggleScript( $UALine, true,
                        $statusesAllowedToBeChecked, $alreadyCheckedListID );
                    $currentSubBox.toggleOff += createToggleScript( $UALine, false,
                        $statusesAllowedToBeChecked, $alreadyCheckedListID );
                    $currentContainer.toggleOn += createToggleScript( $UALine, true,
                        $statusesAllowedToBeChecked, $alreadyCheckedListID );
                    $currentContainer.toggleOff += createToggleScript( $UALine, false,
                        $statusesAllowedToBeChecked, $alreadyCheckedListID );
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $currentSubBox  );
                } else {
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'It is just a document, so push it directly to the currentContainer.content' );
                    // It's just a document, so push it to container content
                    $containerVerifStruct = updateVerificationStruct($UALine, $containerVerifStruct,
                        $alreadyCheckedListID, $statusesAllowedToBeChecked );

                    $currentContainer.content += generateUALineFlow($UALine, $alreadyCheckedListID,
                        $statusesAllowedToBeChecked, $containerVerifStruct, 1);
                    // Add script to Container
                    $currentContainer.toggleOn += createToggleScript( $UALine, true,
                        $statusesAllowedToBeChecked, $alreadyCheckedListID );
                    $currentContainer.toggleOff += createToggleScript( $UALine, false,
                        $statusesAllowedToBeChecked, $alreadyCheckedListID );
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $currentContainer  );
                }
            }

        } else {
            // It's not a container, so verify if this line concerns a box
            if ($UALine['boxnumber'] != null) {
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'We are in a box !' );
                // Close subbox if one
                if ($currentSubBox.identification) {
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'There was a previous subbox not rendered, so render it into currentContainer.content' );
                    $currentContainer.content += closeAndRenderEntity($currentSubBox, $boxVerifStruct, $delMode);
                    $boxVerifStruct = reinitVerificationStruct( $boxVerifStruct );
                    $currentSubBox = createEmptyEntity();
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $currentContainer.content  );
                }
                // Close old container if exist
                if ($currentContainer.identification) {
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'And render the container to mainContent' );
                    $mainContent += closeAndRenderEntity($currentContainer, $containerVerifStruct, $delMode);
                    $containerVerifStruct = reinitVerificationStruct( $containerVerifStruct );
                    $currentContainer = createEmptyEntity();
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $mainContent );
                }
                // Verify if we are in the same box
                if ($UALine['boxnumber'] == $currentBox.identification && $UALine['suid'] == $currentSUID) {
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'We are in the same box than previously, so render line into currentBox.content' );
                    $boxVerifStruct = updateVerificationStruct($UALine, $boxVerifStruct, $alreadyCheckedListID,
                        $statusesAllowedToBeChecked );

                    $currentBox.content += generateUALineFlow($UALine, $alreadyCheckedListID,
                        $statusesAllowedToBeChecked, $boxVerifStruct, 2);
                    // Add script to Box
                    $currentBox.toggleOn += createToggleScript( $UALine, true,
                        $statusesAllowedToBeChecked, $alreadyCheckedListID );
                    $currentBox.toggleOff += createToggleScript( $UALine, false,
                        $statusesAllowedToBeChecked, $alreadyCheckedListID );
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $currentBox  );
                } else {
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'This is a new Box' );
                    // Close old Box if exist
                    if ($currentBox.identification) {
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'But there is an old box not closed, so render it into maintContent' );
                        $mainContent += closeAndRenderEntity($currentBox, $boxVerifStruct, $delMode);
                        $currentBox = createEmptyEntity();
                        $boxVerifStruct = reinitVerificationStruct( $boxVerifStruct );
                        $boxVerifStruct.bFirst = false;
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $mainContent );
                    }
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'And create a new box' );
                    $currentBox = initializeEntity($currentBox, 2, $UALine, $boxVerifStruct );
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $currentBox );
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'And render the new line into currentBox.content' );
                    $boxVerifStruct = updateVerificationStruct($UALine, $boxVerifStruct, $alreadyCheckedListID,
                        $statusesAllowedToBeChecked );

                    $currentBox.content += generateUALineFlow($UALine, $alreadyCheckedListID,
                        $statusesAllowedToBeChecked, $boxVerifStruct, 2);
                    // Add script to Box
                    $currentBox.toggleOn += createToggleScript( $UALine, true, $statusesAllowedToBeChecked,
                        $alreadyCheckedListID );
                    $currentBox.toggleOff += createToggleScript( $UALine, false, $statusesAllowedToBeChecked,
                        $alreadyCheckedListID );
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $currentBox  );
                }

            } else {
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'It is not a Container, nor a Box, just a simple line' );
                // It's not a container, nor a box, so it's a simple document
                // Close old container if exist
                if ($currentContainer.identification) {
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'Last container was not rendered yet'  );
                    // Close subbox before if one
                    if ($currentSubBox.identification) {
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'Last subbox of this container was not rendered yet, so do it into currentContainer.content first' );
                        $currentContainer.content += closeAndRenderEntity($currentSubBox, $boxVerifStruct, $delMode);
                        $boxVerifStruct = reinitVerificationStruct( $boxVerifStruct );
                        $currentSubBox = createEmptyEntity();
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $currentContainer.content  );
                    }
                    $mainContent += closeAndRenderEntity($currentContainer, $boxVerifStruct, $delMode);
                    $currentContainer = createEmptyEntity();
                    $containerVerifStruct = reinitVerificationStruct( $containerVerifStruct );
                    $containerVerifStruct.bFirst = false;
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'Render currentContainer in mainContent' );
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $mainContent );
                }
                // Close old Box if exist
                if ($currentBox.identification) {
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'Last Box was not rendered yet, so render it into mainContent' );
                    $mainContent += closeAndRenderEntity($currentBox, $boxVerifStruct, $delMode);
                    $currentBox = createEmptyEntity();
                    $boxVerifStruct = reinitVerificationStruct( $boxVerifStruct );
                    $boxVerifStruct.bFirst = false;
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $mainContent );
                }

                if (!$otherUAs.identification) {    // In fact first time we encourter UA not in container nor in box
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'First line of ROA, so initialize ROA entity' );
                    $otherUAs = initializeEntity($otherUAs, 4, null, $otherVerifStruct );
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $otherUAs );
                }
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'And render line into otherUAs.content'  );
                $otherVerifStruct = updateVerificationStruct($UALine, $otherVerifStruct, $alreadyCheckedListID,
                    $statusesAllowedToBeChecked );

                $otherUAs.content += generateUALineFlow($UALine, $alreadyCheckedListID, $statusesAllowedToBeChecked,
                    $otherVerifStruct, 4);
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $otherUAs.content );
            }
        }

        $currentSUID = $UALine['suid'];
    }

                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'All lines have been parsed, so render everything in mainContent' );
    // We parsed all lines, so close what needed to be closed
    if ($currentContainer.identification) {
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'There is a not closed container' );
        if ($currentSubBox.identification) {
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'There is a not closed subbox inside it, so render it first in currentContainer.content' );
            $currentContainer.content += closeAndRenderEntity($currentSubBox, $boxVerifStruct, $delMode);
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $currentContainer.content  );
        }
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'Render the container into mainContent' );
        $mainContent += closeAndRenderEntity($currentContainer, $containerVerifStruct, $delMode);
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $mainContent );
    } else if( $currentBox.identification ){
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'There is a not closed box, so render it into mainContent' );
        $mainContent += closeAndRenderEntity( $currentBox, $boxVerifStruct, $delMode );
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $mainContent );
    } else if( $otherUAs.identification ){
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'There are some UAs in the ROA section, so render it into mainContent'  );
        $mainContent += closeAndRenderEntity($otherUAs, $otherVerifStruct, $delMode);
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $mainContent );
    }

                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( 'Set OptimizsationModalBody with the mainContent' );
                                                                                                                        if( DEBUG_OPTIM_RENDERING ) console.log( $mainContent );
    if( !$delMode )
        $('#OptimisationModalBody').html( $mainContent );
    else
        $('#DelOptimisationModalBody').html( $mainContent );
}
//---------------------------------------------------------------------------------------------------------------------

function pollLatestOptiStatus() {

    $('#textopti').html( 'En attente statut serveur ...' );
    $respOK = false;

    var jqxhr = $.get( window.JSON_URLS.bs_idp_archivist_json_providerconnector_optimisationstatus, function($response){
        if( !$response ){
            clearInterval( pollTimer );
            return;
        }

        $percent = $response['percent'];
        $message = $response['message'];

        $('#pbopti').prop( 'style', 'width:'+$percent+'%;');
        $('#textopti').html( $message );

        $respOK = true;

        if( $percent >= 100 )
            clearInterval(pollTimer);
        })
        .fail(function() {
            clearInterval(pollTimer);
        });

    if( !$respOK )
        clearInterval(pollTimer);
}

function save_ProviderConnectorModalDatas( ){
    $dataProvConn = {
        'contact' : $('#frm_pcb_contact').val(),
        'phone': $('#frm_pcb_phone').val(),
        'address': $('#frm_pcb_address').val(),
        'deliver': $('input[name=pcbdeliverradio]:checked', '#ProviderConnectorModalBody').val(),
        'disposal': $('input[name=pcbdisposalradio]:checked', '#ProviderConnectorModalBody').val(),
        'type': $('input[name=pcbtyperadio]:checked', '#ProviderConnectorModalBody').val(),
        'type2': $('input[name=pcbtype2radio]:checked', '#ProviderConnectorModalBody').val(),
        'remark': $('#frm_pcb_remark').val(),
        'name': $('#frm_pcb_name').val(),
        'firstname': $('#frm_pcb_firstname').val(),
        'function': $('#frm_pcb_function').val()
    };

    // Backup ProviderConnector datas
    $.ajax({
        type: 'GET',
        url: window.JSON_URLS.bs_idp_archivist_json_providerconnectorbackup_set,
        data: $dataProvConn,
        cache: false
    });
}
