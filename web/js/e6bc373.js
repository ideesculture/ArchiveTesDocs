
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

var FCT_ARCHIVIST               = 6;// Function to send a post to server
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

}var $_currentPage = 0;
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
            pagination: true,
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
}/**
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
// -- Link to same localization --
$('#divLinkLocalization').click( function( event ){
    event.preventDefault();

    // Activate only if something is selected
    if( $('#listarchives').bootstrapTable('getSelections').length > 0 ) {
        if ($uawhere == UAWHERE_PROVIDER) {
            $('#waitAjax').show();
            updateProvLocModal();

        } else {
            $('#divModalLblLocProvider').hide();
            $('#divModalSelectLocProvider').hide();
            $('#divModalLblLocalization').hide();
            $('#divModalSelectLocalization').hide();
            $('#divModalLblLocalizationfree').show();
            $('#divModalInputLocalizationfree').show();
            $('#frm_samelocalizationfree').val('');
            $('#LocalizationModal').modal('show');
        }
    }
});

$('#btnLocalizationModalConfirm').click(function(){

    // Only if localization field is not empty
    if( ( ( $uawhere == UAWHERE_PROVIDER ) && ( $('#frm_samelocalization option:selected').val() == '' ) )
        ||( ( $uawhere != UAWHERE_PROVIDER ) && ( $('#frm_samelocalizationfree').val() == '' ) ) ){
        bootbox.alert( {
            message: $_translations[98],
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
            'uawhere': $uawhere,
            'provider_id': $('#frm_samelocprovider option:selected').val(),
            'localization_id': $('#frm_samelocalization option:selected').val(),
            'localizationfree': $('#frm_samelocalizationfree').val()
        };
        $.ajax({
            type: "GET",
            url: window.JSON_URLS.bs_idp_archivist_json_update_localization,
            data: $datas,
            cache: false,
            success: function( ){
                $('#LocalizationModal').modal('hide');
                $('#listarchives').bootstrapTable('refresh');
                $_recheck = true;   // Activate post reload rechecking
            },
            error: function (xhr, ajaxOptions, thrownError) {
                bootbox.alert( {
                    message: $_translations[87],
                    className: "boxSysErrorOne"
                } );
                $('#LocalizationModal').modal('hide');
            }
        });
    }
});

function updateProvLocModal( ){
    // Rebuild ID list from Selection
    $UASSelected = $('#listarchives').bootstrapTable('getSelections');
    var $idlist = '';
    var $bFirst = true;
    $UASSelected.forEach( function( $ua ){
        if( $bFirst )
            $bFirst = false;
        else
            $idlist += ',';

        $idlist += $ua['id'];
    });
    // Call server to get back allowed providers
    var $datas = {
        'idslist': $idlist
    }
    $.ajax({
        type: 'GET',
        url: window.JSON_URLS.bs_idp_archivist_json_allowed_providers,
        data: $datas,
        cache: false,
        success: function( $response ){
            // Update ProvLoc Modal
            if( $response['commonProviders'] == null ){
                $('#waitAjax').hide();
                bootbox.alert( {
                    message: "Cette action est impossible car les archives n'ont pas de compte prestataire en commun.",
                    className: "boxErrorOne"
                } );
            } else {
                updateModalLocalizations( $response['commonProviders'] );
                // and show it
                $('#waitAjax').hide();
                $('#divModalLblLocProvider').show();
                $('#divModalSelectLocProvider').show();
                $('#divModalLblLocalization').show();
                $('#divModalSelectLocalization').show();
                $('#divModalLblLocalizationfree').hide();
                $('#divModalInputLocalizationfree').hide();
                $('#frm_samelocalizationfree').val('');
                $('#LocalizationModal').modal('show');
            }
        },
        error: function( ){
            $('#waitAjax').hide();
            bootbox.alert( {
                message: $_translations[87],
                className: "boxSysErrorOne"
            } );
        }
    });
};

function updateModalLocalizations( $providers ){

    $bFirst = true;
    $allowedLocalizations = [];
    $partialModalLocProvider = "<option value ></option>";
    $providers.forEach( function ($providerLine){
        $partialModalLocProvider += "<option value=\"" + $providerLine['id'] + "\" data=\"" +
            $providerLine['localization_id'] + "\" " +
            ($bFirst?"selected=\"selected\" ":"") + ">" +
            $providerLine['name'] + "</option>";
        if( $bFirst ){
            $bFirst = false;
            $allowedLocalizations = $providerLine['localization_id'];
        }
    });

    $partialModalLocalizations = "<option value selected=\"selected\"></option>";

    $localizations.forEach(function ($localizationLine) {
        if ($allowedLocalizations == $localizationLine[LOCALIZATION_ID] ) {
            $partialModalLocalizations += "<option value=\"" + $localizationLine[LOCALIZATION_ID] + "\">" + $localizationLine[LOCALIZATION_NAME] + "</option>";
        }
    });

    $('#frm_samelocprovider').html($partialModalLocProvider);
    $('#frm_samelocalization').html($partialModalLocalizations);
}

$('#frm_samelocprovider').change( function(){
    $provider_loc_id = parseInt( $("#frm_samelocprovider option:selected").attr('data') );

    $partialModalLocalizations = "<option value selected=\"selected\"></option>";
    $localizations.forEach(function ($localizationLine) {
        if ($localizationLine[LOCALIZATION_ID] == $provider_loc_id) {
            $partialModalLocalizations += "<option value=\"" + $localizationLine[LOCALIZATION_ID] + "\">" + $localizationLine[LOCALIZATION_NAME] + "</option>";
        }
    });

    $('#frm_samelocalization').html($partialModalLocalizations);
});var $UATableList = null;

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
}// Shift & Ctrl Select
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

var $_currentPage = PAGE_VALID_TRANSFER_PROVIDER;
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
var $defaultOldLocalization = -1;

var SERVICE_ID = 0;
var SERVICE_NAME = 1
var SERVICE_LEGALENTITIES_IDX = 2;
var SERVICE_BUDGETCODES_IDX = 3;
var SERVICE_DESCRIPTIONS1_IDX = 4;
var SERVICE_DESCRIPTIONS2_IDX = 5;
var SERVICE_PROVIDERS_IDX = 6;
var SERVICE_DOCUMENTNATURES_IDX = 7;
var LEGALENTITY_ID = 0;
var LEGALENTITY_NAME = 1
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

$xpstate = UASTATE_MANAGEUSER;
$uawhat = UAWHAT_TRANSFER;
$uawhat_asked = -1;
$uawhere = UAWHERE_PROVIDER;
$uawith = UAWITH_NOTHING;
$uawith_asked = -1;
$uahow = UAHOW_WITHOUTPREPARE;
$filter_provider = -1;

$actionList = [];	// Store Archive for Action purpose (uawhat)
$actionListID = [];	// Store only IDs to simplify some treatments
$cancelList = [];	// Store Archive to cancel ask (uawhat)
$cancelListID = [];
$currentNumberChecked = 0;

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
	$('#btnwhere_provider').removeClass( 'active' );
	$('#btnwhere_intermediate').removeClass( 'active' );
	$('#btnwhere_internal').removeClass( 'active' );
}
function btnhowClean(){
	$('#btnhow_withoutprepare').removeClass('active');
	$('#btnhow_withprepare').removeClass('active');
}
function btnwithClean(){
	/*
	$('#btnwith_container').removeClass( 'active' );
	$('#btnwith_box').removeClass( 'active' );
	$('#btnwith_file').removeClass( 'active' );
	$('#btnwith_nothing').removeClass( 'active' );
	*/
}

function switchCurrentPageAndButtons(){
    switch( $uawhat ){
        case UAWHAT_TRANSFER:
            switch( $uawhere ){
                case UAWHERE_PROVIDER:
                    $_currentPage = PAGE_VALID_TRANSFER_PROVIDER;
                    break;
                case UAWHERE_INTERMEDIATE:
                    $_currentPage = PAGE_VALID_TRANSFER_INTERMEDIATE;
                    break;
                case UAWHERE_INTERNAL:
                    $_currentPage = PAGE_VALID_TRANSFER_INTERNAL;
                    break;
            }
            break;
        case UAWHAT_CONSULT:
            switch( $uahow ){
                case UAHOW_WITHOUTPREPARE:
                    $_currentPage = PAGE_VALID_DELIVER_WITHOUT_PREPARATION;
                    break;
                case UAHOW_WITHPREPARE:
                    $_currentPage = PAGE_VALID_DELIVER_WITH_PREPARATION;
                    break;
            }
            break;
        case UAWHAT_RETURN:
            $_currentPage = PAGE_VALID_RETURN;
            break;
        case UAWHAT_EXIT:
            $_currentPage = PAGE_VALID_EXIT;
            break;
        case UAWHAT_DESTROY:
            $_currentPage = PAGE_VALID_DELETE;
            break;
        case UAWHAT_RELOC:
            switch( $uawhere ){
                case UAWHERE_PROVIDER:
                    $_currentPage = PAGE_VALID_RELOC_PROVIDER;
                    break;
                case UAWHERE_INTERMEDIATE:
                    $_currentPage = PAGE_VALID_RELOC_INTERMEDIATE;
                    break;
                case UAWHERE_INTERNAL:
                    $_currentPage = PAGE_CLOSE_RELOC_INTERNAL;
                    break;
            }
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
            initMainTabColumns( false, $('#listarchives'), window.JSON_URLS.bs_idp_archivist_json_loaddatas, $response.data, $_tabletranslation, $_commonsettings, 'server', 'get', 7 );

            $('#listarchives').bootstrapTable('refresh');
        },
        error: function (xhr, ajaxOptions, thrownError) {
            bootbox.alert( {
				message: $_translations[87],
				className: "boxSysErrorOne"
			}  );
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

	$('#table-cancel').bootstrapTable({
		showHeader: false,
		showColumns: false,
		pagination: false,
        height: 100,
		columns: [
            { field: 'name', title: 'Nom', sortable: true, visible: false },
            { field: 'id', title: 'ID', visible: false },
            { field: 'ordernumber', title: 'N° d\'ordre', sortable: true, visible: true },
            { field: 'operate', formatter: 'operateFormatter', events: 'operateCancelEvents', title: 'Action', align: 'center', width: '30' }
		]
	});

    // initOverlay
    initOverlay( );

	$.ajax({
		type: "POST",
		url: window.JSON_URLS.bs_idp_archive_form_initlists,
		data: null,
		cache: false,
		success: updateLists,
		error: function (xhr, ajaxOptions, thrownError) {
			bootbox.alert( {
				message: 'Error bs_idp_archive_form_initlists',
				className: "boxSysErrorOne"
			} );
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

	$('#btnSuppressModalConfirm').click( function(){
		onClickBtnSuppressModalConfirm();
		return true;
	});

	$('#divSubmitModif').click(function(){
		onClickBtnSubmitModif();
        currentOverlayViewUA = null;
		return true;
	});

	$('#divDelete').click( function(){
		$('#SuppressModalText').html( $_translations[70] + $('#frm_name').html() );
		$('#SuppressModal').modal('show');
	});

	$('#btnAction').click( function(){
		clickAction();
	});
	$('#btnCancel').click( function(){
        json_action_cancel( false );
		//clickCancel();
	})

    // Activate tooltip on buttons
    $('[data-toggle="tooltip"]').tooltip( {placement : 'bottom'} );

    // Only visible with reloc & transfer function
    $('#divLinkLocalization').show();

    // Get back User Settings to init Main Tab
    $dataStr = "page=" + $_currentPage;
    $.ajax({
        type: "GET",
        url: window.JSON_URLS.bs_idp_backoffice_ajax_usersettings_get,
        data: $dataStr,
        cache: false,
        success: function( $response ) {
            initMainTab( $('#listarchives'), window.JSON_URLS.bs_idp_archivist_json_loaddatas, $response.data, $_tabletranslation, $_commonsettings, 'server', 'get', 7 );
        },
        error: function (xhr, ajaxOptions, thrownError) {
            bootbox.alert( {
				message: $translations[87],
				className: "boxSysErrorOne"
			} );
        }
    });

    $('#selectproviderfilter').hide();

});

$('#addToAction').click( function( event ){
	event.preventDefault();
	if( !$('#addToAction').hasClass( "disabled") )
    	clickAddToAction();
})
$('#addToCancel').click( function( event ){
	event.preventDefault();
	if( !$('#addToCancel').hasClass( "disabled" ) )
    	clickAddToCancel();
})
$('#clearAction').click( function( event ){
    event.preventDefault();
    if( !$('#clearAction').hasClass("disabled")) {
        $actionList = [];
        $actionListID = [];
        onClickClearBasket( $('#table-action') );
    }
});
$('#clearCancel').click( function( event ){
    event.preventDefault();
    if( !$('#clearCancel').hasClass("disabled")) {
        $cancelList = [];
        $cancelListID = [];
        onClickClearBasket( $('#table-cancel') );
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
    params.uastate = UASTATE_MANAGEUSER;
	params.uawhat = $uawhat;
	params.uawhere = $uawhere;
	params.uawith = 0; // $uawith;
	params.uahow = $uahow;
	params.special = $special;
	/*
	if( $uawhat == UAWHAT_TRANSFER && $uawhere == UAWHERE_PROVIDER ) {
        if ($("#filter_provider option:selected").val() != "")
            params.filterprovider = $("#filter_provider option:selected").val();
        else
            params.filterprovider = -1;
    } else
    */
        params.filterprovider = -1;

    return params; //JSON.stringify( params );
}
function stateFormatter( value, row, index ){
    if( row['locked'] )
        return { disabled: true };

	$inActionList = $actionListID.indexOf( row['id'] );
	$inCancelList = $cancelListID.indexOf( row['id'] );

	if( ( $inActionList >= 0 )||( $inCancelList >= 0 ) )
		return { disabled: true };
	else
		return { disabled: false };
}
function rowStyle( row, index ){
    if( row['locked'] )
        return { disabled: true };

	$inActionList = $actionListID.indexOf( row['id'] );
	$inCancelList = $cancelListID.indexOf( row['id'] );

	if( $inActionList >= 0 )
		return { classes: 'info' };
	if( $inCancelList >= 0 )
		return { classes: 'warning' };

	return { classes: '' };
}
function operateFormatter( value, row, index ){
	return [
	'<a class="remove" href="javascript:void(0)" title="Supprimer">', '<i class="far fa-times"></i>', '</a>'
	].join('');
}
window.operateActionEvents = {
	'click .remove': function( e, value, row, index ){
		$elemId = row['id'];
		$removeIdx = -1;
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
window.operateCancelEvents = {
	'click .remove': function( e, value, row, index ){
		$elemId = row['id'];
		$removeIdx = -1;
		// remove line from $cancelList with id
		$cancelList.forEach( function( $elem, $index ){
			if( $elem['id'] == $elemId )
			$removeIdx = $index;
		});
		// Remove item from lists
		if( $removeIdx >= 0 )
			$cancelList.splice( $removeIdx, 1 );
		$removeIdx = $cancelListID.indexOf( $elemId );
		if( $removeIdx >= 0 )
			$cancelListID.splice( $removeIdx, 1 );

		$('#table-cancel').bootstrapTable('load', $cancelList );
		$('#listarchives').bootstrapTable('refresh');
		updateBtnActionCancelState();
        verifyAndEnableEmptyBasketButton();
	}
};

function enableAddBasketButton(){
    $('#addToAction').removeClass('disabled');
    $('#addToCancel').removeClass('disabled');
}

function updateBtnActionCancelState(){
    if( $actionList.length > 0 )
		$('#btnAction').removeClass( 'disabled' );
	else
		$('#btnAction').addClass( 'disabled' );

	if( $cancelList.length > 0 )
		$('#btnCancel').removeClass( 'disabled' );
	else
		$('#btnCancel').addClass( 'disabled' );
}
function verifyAndEnableEmptyBasketButton() {
    if( $actionList.length > 0 ){
        $('#clearAction').removeClass( 'disabled' );
    } else {
        $('#clearAction').addClass( 'disabled' );
    }
    if( $cancelList.length > 0 ){
        $('#clearCancel').removeClass( 'disabled' );
    } else {
        $('#clearCancel').addClass( 'disabled' );
    }
}


$('#btnConfirmModalConfirm').click(function(){

	$('#ConfirmModal').modal('hide');
	$actionList = [];
    $actionListID = [];
	$cancelList = [];
	$cancelListID = [];
	// Update action & cancel visualisation
	$('#table-action').bootstrapTable('load', $actionList );
	$('#table-cancel').bootstrapTable('load', $cancelList );

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

	switch($uawith_asked){
		case UAWITH_BOX:
			changeWithIntoBox();
			break;
		case UAWITH_CONTAINER:
			changeWithIntoContainer();
			break;
		case UAWITH_FILE:
			changeWithIntoFile();
			break;
		case UAWITH_NOTHING:
			changeWithIntoNothing();
			break;
	}
})

function makeConfirmText( ){
	$text = "Votre liste '<strong>";
	$bFirst = true;
	$bCount = 0;
	if( $actionList.length > 0 ){
		$bFirst = false;
		$bCount++;
		switch( $uawhat ){
			case UAWHAT_TRANSFER: $text += $_translations[58]; break;
			case UAWHAT_CONSULT: $text += $_translations[71]; break;
			case UAWHAT_RETURN: $text += $_translations[72]; break;
			case UAWHAT_DESTROY: $text += $_translations[73]; break;
			case UAWHAT_EXIT: $text += $_translations[74]; break;
            case UAWHAT_RELOC: $text += $_translations[92]; break;
		}
	}
	if( $cancelList.length > 0 ){
		$bCount++;
		if( !$bFirst )
			$text += "</strong>' "+$_translations[75]+" '<strong>";
		switch( $uawhat ){
			case UAWHAT_TRANSFER: $text += $_translations[59]; break;
			case UAWHAT_CONSULT: $text += $_translations[76]; break;
			case UAWHAT_RETURN: $text += $_translations[77]; break;
			case UAWHAT_DESTROY: $text += $_translations[78]; break;
			case UAWHAT_EXIT: $text += $_translations[79]; break;
            case UAWHAT_RELOC: $text += $_translations[93]; break;
		}
	}
	if( $bCount == 1 )
		$text += "</strong>' " + $_translations[80];
	else
		$text += "</strong>' " + $_translations[81];
	$text += " <br/> "+ $_translations[82];
	return $text;
}

// Buttons UAWHAT management
$('#btnwhat_transfer').click(function(){
	if( $uawhat != UAWHAT_TRANSFER ){
		if( $actionList.length > 0  || $cancelList.length > 0 ){
			$uawhat_asked = UAWHAT_TRANSFER;
			$uawith_asked = -1;
			$('#confirmModalText').html( makeConfirmText() );
			$('#ConfirmModal').modal('show');
		} else {
            changeWhatIntoTransfer();
        }
	}
});
function changeWhatIntoTransfer(){
	btnwhatClean();
	$('#btnwhat_transfer').addClass( 'active' );
	$uawhat = UAWHAT_TRANSFER;

	$('#btnhow').addClass('hidden');
	$('#btnnothing').addClass('hidden');
	$('#btnwhere').removeClass('hidden');
	$('#btnwith').removeClass('hidden');
	$('#btnnothing2').addClass('hidden');

    $('#divLinkLocalization').show();

    /*
    if( $uawhere == UAWHERE_PROVIDER )
        $('#selectproviderfilter').show();
    */
    $('#selectproviderfilter').hide();

	$('#titleAction').html( $_translations[57] );
	$('#btnAction').html( $_translations[116] );
	$('#btnCancel').html( $_translations[60] );
	$('#textDoAction').html('&nbsp;&nbsp;&nbsp;' + $_translations[58]);
	$('#textCancelAction').html('&nbsp;&nbsp;&nbsp;' + $_translations[59]);

    resetMultipleSelect( );
    switchCurrentPageAndButtons();
    verifyAndEnableEmptyBasketButton();
};

$('#btnwhat_deliver').click(function(){
	if( $uawhat != UAWHAT_CONSULT ){
		if( $actionList.length > 0  || $cancelList.length > 0 ){
			$uawhat_asked = UAWHAT_CONSULT;
			$uawith_asked = -1;
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

	$('#btnwhere').addClass('hidden');
	$('#btnnothing').addClass('hidden');
	$('#btnhow').removeClass('hidden');
	$('#btnwith').addClass('hidden');
	$('#btnnothing2').removeClass('hidden');

    $('#divLinkLocalization').hide();

    $('#selectproviderfilter').hide();

	$('#titleAction').html( $_translations[83] );
	$('#btnAction').html( $_translations[117] );
	$('#btnCancel').html( $_translations[118] );
	$('#textDoAction').html('&nbsp;&nbsp;&nbsp;' + $_translations[71] );
	$('#textCancelAction').html('&nbsp;&nbsp;&nbsp;' + $_translations[76] );

    resetMultipleSelect( );
    switchCurrentPageAndButtons();
    verifyAndEnableEmptyBasketButton();
};

$('#btnwhat_return').click(function(){
	if( $uawhat != UAWHAT_RETURN ){
		if( $actionList.length > 0  || $cancelList.length > 0 ){
			$uawhat_asked = UAWHAT_RETURN;
			$uawith_asked = -1;
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

	$('#btnwhere').addClass('hidden');
	$('#btnhow').addClass('hidden');
	$('#btnnothing').removeClass('hidden');
	$('#btnwith').addClass('hidden');
	$('#btnnothing2').removeClass('hidden');

    $('#divLinkLocalization').hide();

    $('#selectproviderfilter').hide();

	$('#titleAction').html( $_translations[84] );
	$('#btnAction').html( $_translations[121] );
	$('#btnCancel').html( $_translations[122] );
	$('#textDoAction').html('&nbsp;&nbsp;&nbsp;' + $_translations[72] );
	$('#textCancelAction').html('&nbsp;&nbsp;&nbsp;' + $_translations[77] );

    resetMultipleSelect( );
    switchCurrentPageAndButtons();
    verifyAndEnableEmptyBasketButton();
};

$('#btnwhat_exit').click(function(){
	if( $uawhat != UAWHAT_EXIT ){
		if( $actionList.length > 0  || $cancelList.length > 0 ){
			$uawhat_asked = UAWHAT_EXIT;
			$uawith_asked = -1;
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

	$('#btnwhere').addClass('hidden');
	$('#btnhow').addClass('hidden');
	$('#btnnothing').removeClass('hidden');
	$('#btnwith').addClass('hidden');
	$('#btnnothing2').removeClass('hidden');

    $('#divLinkLocalization').hide();

    $('#selectproviderfilter').hide();

	$('#titleAction').html( $_translations[85] );
	$('#btnAction').html( $_translations[123] );
	$('#btnCancel').html( $_translations[124] );
	$('#textDoAction').html('&nbsp;&nbsp;&nbsp;' + $_translations[74] );
	$('#textCancelAction').html('&nbsp;&nbsp;&nbsp;' + $_translations[79]);

    resetMultipleSelect( );
    switchCurrentPageAndButtons();
    verifyAndEnableEmptyBasketButton();
};

$('#btnwhat_destroy').click(function(){
	if( $uawhat != UAWHAT_DESTROY ){
		if( $actionList.length > 0  || $cancelList.length > 0 ){
			$uawhat_asked = UAWHAT_DESTROY;
			$uawith_asked = -1;
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

	$('#btnwhere').addClass('hidden');
	$('#btnhow').addClass('hidden');
	$('#btnnothing').removeClass('hidden');
	$('#btnwith').addClass('hidden');
	$('#btnnothing2').removeClass('hidden');

    $('#divLinkLocalization').hide();

    $('#selectproviderfilter').hide();

	$('#titleAction').html( $_translations[86] );
	$('#btnAction').html( $_translations[125] );
	$('#btnCancel').html( $_translations[126] );
	$('#textDoAction').html('&nbsp;&nbsp;&nbsp;' + $_translations[73]);
	$('#textCancelAction').html('&nbsp;&nbsp;&nbsp;' + $_translations[78] );

    resetMultipleSelect( );
    switchCurrentPageAndButtons();
    verifyAndEnableEmptyBasketButton();
};

$('#btnwhat_reloc').click(function(){
    if( $uawhat != UAWHAT_RELOC ){
        if( $actionList.length > 0  || $cancelList.length > 0 ){
            $uawhat_asked = UAWHAT_RELOC;
            $uawith_asked = -1;
            $('#confirmModalText').html( makeConfirmText() );
            $('#ConfirmModal').modal('show');
        } else {
            changeWhatIntoReloc();
        }
    }
});
function changeWhatIntoReloc(){
    btnwhatClean();
    $('#btnwhat_reloc').addClass( 'active' );
    $uawhat = UAWHAT_RELOC;

    $('#btnhow').addClass('hidden');
    $('#btnnothing').addClass('hidden');
    $('#btnwhere').removeClass('hidden');
    $('#btnwith').addClass('hidden');
    $('#btnnothing2').addClass('hidden');

    $('#divLinkLocalization').show();

    $('#selectproviderfilter').hide();

    $('#titleAction').html( $_translations[94] );
    $('#btnAction').html( $_translations[127] );
    $('#btnCancel').html( $_translations[128] );
    $('#textDoAction').html('&nbsp;&nbsp;&nbsp;' + $_translations[92]);
    $('#textCancelAction').html('&nbsp;&nbsp;&nbsp;' + $_translations[93]);

    resetMultipleSelect( );
    switchCurrentPageAndButtons();
    verifyAndEnableEmptyBasketButton();
};

// UAWHERE Btn management
$('#btnwhere_provider').click(function(){
	if( $uawhere != UAWHERE_PROVIDER ){
		btnwhereClean();
		$('#btnwhere_provider').addClass( 'active' );
		$uawhere = UAWHERE_PROVIDER;

		if( $uawhat != UAWHAT_TRANSFER )
		    $('#selectproviderfilter').show();

        $('#listarchives').bootstrapTable( 'showColumn', 'localization' );
        $('#listarchives').bootstrapTable( 'hideColumn', 'localizationfree' );

        resetMultipleSelect( );
        switchCurrentPageAndButtons();
        verifyAndEnableEmptyBasketButton();

		$('#divLinkLocalization').attr('title', $_translations[97] )
            .tooltip('fixTitle');
	}
});
$('#btnwhere_intermediate').click(function(){
	if( $uawhere != UAWHERE_INTERMEDIATE ){
		btnwhereClean();
		$('#btnwhere_intermediate').addClass( 'active' );
		$uawhere = UAWHERE_INTERMEDIATE;

		$('#selectproviderfilter').hide();

        $('#listarchives').bootstrapTable( 'showColumn', 'localizationfree' );
        $('#listarchives').bootstrapTable( 'hideColumn', 'localization' );

        resetMultipleSelect( );
        switchCurrentPageAndButtons();
        verifyAndEnableEmptyBasketButton();

        $('#divLinkLocalization').attr('title', $_translations[114] )
            .tooltip('fixTitle');
	}
});
$('#btnwhere_internal').click(function(){
	if( $uawhere != UAWHERE_INTERNAL ){
		btnwhereClean();
		$('#btnwhere_internal').addClass( 'active' );
		$uawhere = UAWHERE_INTERNAL;

		$('#selectproviderfilter').hide();

        $('#listarchives').bootstrapTable( 'showColumn', 'localizationfree' );
        $('#listarchives').bootstrapTable( 'hideColumn', 'localization' );

        resetMultipleSelect( );
        switchCurrentPageAndButtons();
        verifyAndEnableEmptyBasketButton();

        $('#divLinkLocalization').attr('title', $_translations[114] )
            .tooltip('fixTitle');
	}
});
$('#btnhow_withoutprepare').click(function(){
	if( $uahow != UAHOW_WITHOUTPREPARE ){
		btnhowClean();
		$('#btnhow_withoutprepare').addClass('active');
		$uahow = UAHOW_WITHOUTPREPARE;

        resetMultipleSelect( );
        switchCurrentPageAndButtons();
        verifyAndEnableEmptyBasketButton();
	}
});
$('#btnhow_withprepare').click(function(){
	if( $uahow != UAHOW_WITHPREPARE ){
		btnhowClean();
		$('#btnhow_withprepare').addClass('active');
		$uahow = UAHOW_WITHPREPARE;

        resetMultipleSelect( );
        switchCurrentPageAndButtons();
        verifyAndEnableEmptyBasketButton();
	}
});

$('#btnwith_container').click(function(){
	if( $uawith != UAWITH_CONTAINER ){
		if( $actionList.length > 0  || $cancelList.length > 0 ){
			$uawith_asked = UAWITH_CONTAINER;
			$uawhat_asked = -1;
			$('#confirmModalText').html( makeConfirmText() );
			$('#ConfirmModal').modal('show');
		} else
			changeWithIntoContainer();
	}
});
function changeWithIntoContainer(){
	btnwithClean();
	$('#btnwith_container').addClass( 'active' );
	$uawith = UAWITH_CONTAINER;

    resetMultipleSelect( );
    switchCurrentPageAndButtons();
    verifyAndEnableEmptyBasketButton();
};

$('#btnwith_box').click(function(){
	if( $uawith != UAWITH_BOX ){
		if( $actionList.length > 0  || $cancelList.length > 0 ){
			$uawith_asked = UAWITH_BOX;
			$uawhat_asked = -1;
			$('#confirmModalText').html( makeConfirmText() );
			$('#ConfirmModal').modal('show');
		} else
			changeWithIntoBox();
	}
});
function changeWithIntoBox(){
	btnwithClean();
	$('#btnwith_box').addClass( 'active' );
	$uawith = UAWITH_BOX;

    resetMultipleSelect( );
    switchCurrentPageAndButtons();
    verifyAndEnableEmptyBasketButton();
};

$('#btnwith_file').click(function(){
	if( $uawith != UAWITH_FILE ){
		if( $actionList.length > 0  || $cancelList.length > 0 ){
			$uawith_asked = UAWITH_FILE;
			$uawhat_asked = -1;
			$('#confirmModalText').html( makeConfirmText() );
			$('#ConfirmModal').modal('show');
		} else
			changeWithIntoFile();
	}
});
function changeWithIntoFile(){
	btnwithClean();
	$('#btnwith_file').addClass( 'active' );
	$uawith = UAWITH_FILE;

    resetMultipleSelect( );
    switchCurrentPageAndButtons();
    verifyAndEnableEmptyBasketButton();
};

$('#btnwith_nothing').click(function(){
	if( $uawith != UAWITH_NOTHING ){
		if( $actionList.length > 0  || $cancelList.length > 0 ){
			$uawith_asked = UAWITH_NOTHING;
			$uawhat_asked = -1;
			$('#confirmModalText').html( makeConfirmText() );
			$('#ConfirmModal').modal('show');
		} else
			changeWithIntoNothing();
	}
})
function changeWithIntoNothing(){
	btnwithClean();
	$('#btnwith_nothing').addClass( 'active' );
	$uawith = UAWITH_NOTHING;

    resetMultipleSelect( );
    switchCurrentPageAndButtons();
    verifyAndEnableEmptyBasketButton();
};

function updateServices( $initService ){
	$serviceOptions = "<option value=\"\"></option>";
	i = 0;
	$bSelected = false;
	$services.forEach(function($serviceLine){
		$selected = "";
		if( $serviceLine[SERVICE_ID] == parseInt( $initService ) ){
			$bSelected = true;
			$selected = " selected='selected' ";
		}
		$serviceOptions += "<option value=\"" + $serviceLine[SERVICE_ID] + "\" data=\"" + i + "\" " + $selected + ">" + $serviceLine[SERVICE_NAME] + "</option> ";
		i = i+1;
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
	$partialLegalEntities = "";
	if( $("#frm_service option:selected").val() != "" ){

		$partialLegalEntities = "<option value ";
		if( parseInt($initLegalEntity) <= 0 )
			$partialLegalEntities += "selected=\"selected\"";
		$partialLegalEntities += "></option>";

		// Construct list of legal entities choices based on service id
		$serviceIdx = parseInt( $("#frm_service option:selected").attr('data') );
		$listLegalEntities = $services[$serviceIdx][SERVICE_LEGALENTITIES_IDX];
		$i = 0;
		$legalEntities.forEach(function($legalentityLine){
			if( $listLegalEntities.indexOf( $legalentityLine[LEGALENTITY_ID] ) >= 0 ){
				$selected = "";
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

	$partialBudgetCodes = "";
	if( $("#frm_service option:selected").val() != "" ){

		$partialBudgetCodes ="<option value ";
		if( parseInt($initBudgetCode) <= 0 )
			$partialBudgetCodes += " selected=\"selected\"";
		$partialBudgetCodes += "></option>";

		// Construct list of budget codes choices based on service id
		$serviceIdx = parseInt( $("#frm_service option:selected").attr('data') );
		$listBudgetCodes = $services[$serviceIdx][SERVICE_BUDGETCODES_IDX];
		$iu = 0;
		$budgetCodes.forEach(function($budgetcodeLine){
			if( $listBudgetCodes.indexOf( $budgetcodeLine[BUDGETCODE_ID] ) >= 0 ){
				$selected = "";
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

	$partialDocumentNatures = "";
	if( $("#frm_service option:selected").val() != "" ){

		$partialDocumentNatures = "<option value ";
		if( parseInt($initDocumentNature) <= 0 )
			$partialDocumentNatures += " selected=\"selected\"";
		$partialDocumentNatures += "></option>";
		// Construct list of document natures choices based on service id
		$serviceIdx = parseInt( $("#frm_service option:selected").attr('data') );
		$listDocumentNatures = $services[$serviceIdx][SERVICE_DOCUMENTNATURES_IDX];
		$i = 0;
		$documentNatures.forEach(function($documentnatureLine){
			if( $listDocumentNatures.indexOf( $documentnatureLine[DOCUMENTNATURE_ID] ) >= 0 ){
				$selected = "";
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

	$partialDocumentTypes = "";
	if( $("#frm_documentnature option:selected").val() != "" ){

		$partialDocumentTypes ="<option value ";
		if( parseInt($initDocumentType) <= 0 )
			$partialDocumentTypes += " selected=\"selected\"";
		$partialDocumentTypes += "></option>";
		// Construct list of document types choices based on document nature id
		$documentnatureIdx = parseInt( $("#frm_documentnature option:selected").attr('data') );
		$listDocumentTypes = $documentNatures[$documentnatureIdx][DOCUMENTNATURE_DOCUMENTTYPES_IDX];
		$i = 0;
		$documentTypes.forEach(function($documenttypeLine){
			if( $listDocumentTypes.indexOf( $documenttypeLine[DOCUMENTTYPE_ID] ) >= 0 ){
				$selected = "";
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

	$partialDescriptions1 = "";
	if( $("#frm_service option:selected").val() != "" ){

		$partialDescriptions1 ="<option value ";
		if( parseInt($initDescription1) <= 0 )
			$partialDescriptions1 += " selected=\"selected\"";
		$partialDescriptions1 += "></option>";
		// Construct list of descriptions1 choices based on service id
		$serviceIdx = parseInt( $("#frm_service option:selected").attr('data') );
		$listDescriptions1 = $services[$serviceIdx][SERVICE_DESCRIPTIONS1_IDX];
		$i = 0;
		$descriptions1.forEach(function($descriptionLine){
			if( $listDescriptions1.indexOf( $descriptionLine[DESCRIPTION1_ID] ) >= 0 ){
				$selected = "";
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

	$partialDescriptions2 = "";
	if( $("#frm_service option:selected").val() != "" ){

		$partialDescriptions2 ="<option value ";
		if( parseInt($initDescription2) <= 0 )
			$partialDescriptions2 += " selected=\"selected\"";
		$partialDescriptions2 += "></option>";
		// Construct list of descriptions2 choices based on service id
		$serviceIdx = parseInt( $("#frm_service option:selected").attr('data') );
		$listDescriptions2 = $services[$serviceIdx][SERVICE_DESCRIPTIONS2_IDX];
		$i = 0;
		$descriptions2.forEach(function($descriptionLine){
			if( $listDescriptions2.indexOf( $descriptionLine[DESCRIPTION2_ID] ) >= 0 ){
				$selected = "";
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

	$partialProviders = "";
	if( $("#frm_service option:selected").val() != "" ){

		$partialProviders ="<option value ";
		if( parseInt($initProvider) <= 0 )
			$partialProviders += " selected=\"selected\"";
		$partialProviders += "></option>";
		// Construct list of providers choices based on service id
		$serviceIdx = parseInt( $("#frm_service option:selected").attr('data') );
		$listProviders = $services[$serviceIdx][SERVICE_PROVIDERS_IDX];
		$i = 0;
		$providers.forEach(function($providerLine){
			if( $listProviders.indexOf( $providerLine[PROVIDER_ID] ) >= 0 ){
				$selected = "";
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
function updateLocalizations( $initLocalization, $initOldLocalization ){

    $partialLocalizations = "";
    $partialOldLocalizations = "";

    if( $("#frm_provider option:selected").val() != "" ){

        $partialLocalizations = "<option value ";
        $partialOldLocalizations = $partialLocalizations;

        if( parseInt($initLocalization) <= 0 )
            $partialLocalizations += " selected=\"selected\"";
        if( parseInt($initOldLocalization) <= 0 )
            $partialOldLocalizations += " selected=\"selected\"";

        $partialLocalizations += "></option>";
        $partialOldLocalizations += "></options>";

        // Construct list of localizations choices based on provider id
        $providerIdx = parseInt( $("#frm_provider option:selected").attr('data') );
        $localizationIdx = $providers[$providerIdx][PROVIDER_LOCALIZATION_IDX];

        if( $localizationIdx >= 0 ) {
            $localizations.forEach( function( $localizationLine ){
                $selected = "";
                $oldSelected = "";
                if( $localizationLine[LOCALIZATION_ID] == $localizationIdx ) {
                    if ($localizationLine[LOCALIZATION_ID] == parseInt($initLocalization))
                        $selected = " selected=\"selected\"";
                    if( $localizationLine[LOCALIZATION_ID] == parseInt($initOldLocalization))
                        $oldSelected = " selected=\"selected\"";

                    $partialLocalizations += "<option value=\"" + $localizationLine[LOCALIZATION_ID] + "\"" + $selected + ">" + $localizationLine[LOCALIZATION_NAME] + "</option> ";
                    $partialOldLocalizations += "<option value=\"" + $localizationLine[LOCALIZATION_ID] + "\"" + $oldSelected + ">" + $localizationLine[LOCALIZATION_NAME] + "</option>";
                }
            });
        }

        $('#frm_localization').attr('disabled', false);
        $('#frm_oldlocalization').attr('disabled', true);
    } else {
        $('#frm_localization').attr('disabled', true);
        $partialLocalizations ="<option value selected=\"selected\"></option>";
        $('#frm_oldlocalization').attr('disabled', true );
        $partialOldLocalizations = "<option value selected=\"selected\"></option>";
    }

    $("#frm_localization").html($partialLocalizations);
    $('#frm_oldlocalization').html($partialOldLocalizations);
}

// On service select box change, update legal entities & budget codes with only available choices, and change form template
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
    updateLocalizations( -1, -1 );
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
        $documenttypeIdx = parseInt($("#frm_documenttype option:selected").attr('data'));
        if( !isNaN( $documenttypeIdx ) )
            $destructionTime = $documentTypes[$documenttypeIdx][DOCUMENTTYPE_KEEPALIVEDURATION];
    }

	// If destruction time is 0, user should enter whatever he wants, instead destruction time is forced
	if($destructionTime == 0){
		$("#frm_destructionyear").prop('disabled', false);
	}
	else
	{
		$("#frm_destructionyear").prop('disabled', true);
		// TODO test empty field
		$destructionYear = parseInt($("#frm_closureyear").val());
		$destructionYear += $destructionTime;
		$("#frm_destructionyear").val($destructionYear);
	}
}

function onClickBtnSuppressModalConfirm(){
	$dataStr = "id=" + $('#frm_id').val();
	$.ajax({
		type: "POST",
		url: window.JSON_URLS.bs_idp_archive_delete_ajax,
		data: $dataStr,
		cache: false,
		success: function($response) {
			$('#SuppressModal').modal('hide');
			$('#viewArchive').hide();
			$('#listarchives').bootstrapTable('refresh');
		},
		error: function (xhr, ajaxOptions, thrownError) {
			bootbox.alert( {
				message: $_translations[87],
				className: "boxSysErrorOne"
			} );
			$('#SuppressModal').modal('hide');
		}
	});

	return true;
}

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

                // If Object is in $actionList or $cancelList, update list
				updateActionOrCancelList( $dataObject );

                $('#divPrint').show();
                if ($_buttonsOverlay & 16)
                    $('#divPrintTag').show();

                $currentEditedRow = null;
			},
			error: function (xhr, ajaxOptions, thrownError) {
				bootbox.alert( {
					message: $_translations[87],
					className: "boxSysErrorOne"
				} );
				$('#viewArchive').hide();
                $currentEditedRow = null;
			}
		});
	} else {
        $('#errorTitle').html( $_translations[1] );
        $('#errorMsg').html( $_translations[2] );
		$('#modalErrorMessage').modal( 'show' );
	}
	return true;
}

// Update Action List or Cancel List with modified datas of object if inside
function updateActionOrCancelList( $dataObject ){
    if( $actionListID.indexOf( parseInt($dataObject['id']) ) >= 0 ){
        $actionList.forEach( function( $elem, $index ){
           if( $elem['id'] == $dataObject['id'] ){
               $elem['localization'] = $dataObject['localization'];
               $elem['localizationfree'] = $dataObject['localizationfree'];
               $elem['provider'] = $dataObject['provider'];
           }
        });
    }
}

/*
function mainTableAllRow( ){
	$selections =  $('#listarchives').bootstrapTable( 'getSelections' );
	if( $selections.length != $currentNumberChecked ){
		$currentNumberChecked = $selections.length;
	}
	updateAddButtonState();
}
*/

// Update both Add button states accordingly to selections in main table
/*function verifyAndDisableAddButtons() {
//function updateAddButtonState(){
	if( $currentNumberChecked > 0 ){
		$('#addToAction').removeClass('disabled');
		$('#addToCancel').removeClass('disabled');
	} else {
		$('#addToAction').addClass('disabled');
		$('#addToCancel').addClass('disabled');
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
	$selections = $('#listarchives').bootstrapTable( 'getSelections' );

    $canAddToAction = true;
	// Scan to search new ones
	$selections.forEach( function( $elem ){
		$inActionList = $actionListID.indexOf( $elem['id'] );
		$inCancelList = $cancelListID.indexOf( $elem['id'] );
		if(( $inCancelList < 0 )&&( $inActionList < 0 )){
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
// Add new selection to the Cancel list
function clickAddToCancel(){
	$selections = $('#listarchives').bootstrapTable( 'getSelections' );
	// Scan to search new ones
	$selections.forEach( function( $elem ){
		$inActionList = $actionListID.indexOf( $elem['id'] );
		$inCancelList = $cancelListID.indexOf( $elem['id'] );
		if(( $inCancelList < 0 )&&( $inActionList < 0 )){
			$cancelListID.push( $elem['id'] );
			$cancelList.push( $elem );
		}
	});
	// Refresh table-cancel with new list
	$('#table-cancel').bootstrapTable('load', $cancelList );
	$('#listarchives').bootstrapTable('refresh');

	updateBtnActionCancelState();
    verifyAndEnableEmptyBasketButton();
    verifyAndDisableAddButtons();
	return true;
};

//--------------------------------------------------------------------------------------------------------------
function continue_After_BasketTests( $action ){
    json_action_cancel( $action );
}

function json_action_cancel( $action ){
    if( $action ) {
        $listID = $actionListID ;
        $list = $actionList;
        $url = window.JSON_URLS.bs_idp_archivist_json_action;
        $table = $('#table-action');
    } else {
        $listID = $cancelListID ;
        $list = $cancelList;
        $url = window.JSON_URLS.bs_idp_archivist_json_cancel;
        $table = $('#table-cancel')
    }

    $dataObject = {
        'uastate': UASTATE_MANAGEUSER,
        'uawhat': $uawhat,
        'uawhere': $uawhere,
        'uawith': 0, //$uawith,
        'uahow': $uahow,
        'ids': JSON.stringify( $listID )
    };

    $.ajax({
        type: "GET",
        url: $url,
        data: $dataObject,
        cache: false,
        success: function ($response) {
            $('#waitAjax').hide();
            $list = [];
            $listID = [];
            $actionListID = [];
            $actionList = [];
            $cancelListID = [];
            $cancelList = [];
            $table.bootstrapTable('load', $list );

            $('#listarchives').bootstrapTable('refresh');
            updateBtnActionCancelState();
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $('#waitAjax').hide();
            bootbox.alert( {
				message: $_translations[87],
				className: "boxSysErrorOne"
			});
        }
    });
}

//--------------------------------------------------------------------------------------------------------------
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
                popError( $('#frm_destructionyear'), $_translations[129], 'top' );
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
    if( $_settings.view_provider && $_settings.mandatory_provider && $('#frm_provider option:selected').val().trim() == '' ){
        $retour = false;
        $('#divViewSelectProvider').addClass('has-error');
    } else {
        $('#divViewSelectProvider').addClass('has-success');
    }
    if( $uawhere == UAWHERE_PROVIDER ){ // Only in provider mode here
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


$('#divPrint').click(function(){
    var $params = new Array();
    $params['id'] = $('#frm_id').val();
    get(window.JSON_URLS.bs_idp_archive_print_sheet, $params, true );
});

// Export and Export Partial button management
$('#divExport').click(function( event ){
    event.preventDefault();

    /*
    if( $uawhat == UAWHAT_TRANSFER && $uawhere == UAWHERE_PROVIDER )
        $filter = ( $("#filter_provider option:selected").val() != "" ) ? $("#filter_provider option:selected").val() : -1;
    else
    */
        $filter = -1;

    exportArray( $('#listarchives'), $_currentFCT, UASTATE_MANAGEUSER, $uawhat, $uawhere, $uahow, $filter, false );
});
$('#divExportPartial').click( function( event ){
    event.preventDefault();

    /*
    if( $uawhat == UAWHAT_TRANSFER && $uawhere == UAWHERE_PROVIDER )
        $filter = ( $("#filter_provider option:selected").val() != "" ) ? $("#filter_provider option:selected").val() : -1;
    else
    */
        $filter = -1;

    exportArray( $('#listarchives'), $_currentFCT, UASTATE_MANAGEUSER, $uawhat, $uawhere, $uahow, $filter, true );
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
				message: $_translations[132],
				className: "boxSysErrorOne"
			} );
        } else {
            $filter_provider = $('#filter_provider').val();
            $('#listarchives').bootstrapTable('refresh', {pageNumber: 1});
        }
    }

})
