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
}