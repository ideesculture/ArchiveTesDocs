
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
//====================================================================================================================
// bsTablePagination.js v1.1.8
// @author: C. Patrigeon [BeSecureLabs]
//====================================================================================================================

/**
 * @class bsTable_Pagination
 * @description Manage a pagination bar with pageSize and callbacks
 * @param pageSize: selected size of a page
 * @param nbElements: maximum number of elements in the table (necessary to calculate last page)
 * @param currentPage: current page (1 based)
 * @param idToPrintInto: div ID to output the html
 */
function bsTable_Pagination( pageSize, nbElements, currentPage, idToPrintInto ) {
    // Allowed page Sizes
    this.page_size_list = [10, 25, 50, 100];
    // If selected page size is not in allowed list, set to default one
    this.page_size = Math.abs(pageSize);
    if( this.page_size_list.indexOf( this.page_size ) < 0 ) this.page_size = 10;

    // Store nbElements and currentPage, and verify currentPage is 1 based
    this.nb_elements = Math.abs(nbElements);
    this.current_page = Math.abs(currentPage);
    if( this.current_page <= 0 ) this.current_page = 1;     // Page is 1 based

    // Calculate first line and last line shown
    this.first_line_shown = ((this.current_page-1)*this.page_size)+1;
    this.last_line_shown = this.first_line_shown + this.page_size -1;
    if( this.last_line_shown > this.nb_elements ) this.last_line_shown = this.nb_elements;

    // Calculate page the page list
    this.nb_pages = Math.ceil( this.nb_elements / this.page_size );
    if( this.current_page > this.nb_pages ) this.current_page = this.nb_pages;

    // Get html object to print into
    this.id_to_print_into = idToPrintInto;
    this._obj = document.getElementById( this.id_to_print_into );
    // if( this.nb_elements == 0 ) this._obj = null;           // Si pas d'éléments, pas de pagination

    // default callbacks are useless function.
    this.on_page_size_change = function( pageSize ){};
    this.on_page_change = function( pageNumber ){};
    /**
     * @function setEventCallbacks
     * @description modify default callbacks
     * @param callbackName: callback name to be modified
     * @param callbackFunction: new callback function
     * TODO: verify second param is a function
     */
    this.setEventCallbacks = function( callbackName, callbackFunction ){
        if( callbackName == 'on_page_size_change' ) this.on_page_size_change = callbackFunction;
        if( callbackName == 'on_page_change' ) this.on_page_change = callbackFunction;
    };

    // -------------------------------------------------------------------------------------------------------------

    /**
     * @function _on_page_size_change
     * @description internal callback when pageSize changes. call user callback if necessary
     * @param event
     * @private
     */
    this._on_page_size_change = function( event ){
        event.preventDefault();
        let $element = $(event.currentTarget);
        let newPageSize = parseInt( $element[0].innerText );
        if( newPageSize != this.page_size ) {
            this.page_size = newPageSize;
            this.on_page_size_change(newPageSize);
        }
    };
    /**
     * @function _on_page_pre_click
     * @description internal callback when previous is clicked. change the current page and call user callback
     * @param event
     * @private
     */
    this._on_page_pre_click = function( event ){
        event.preventDefault();
        let newPage = this.current_page - 1;
        if( newPage < 1 ) newPage = this.nb_pages;

        this.current_page = newPage;
        this.on_page_change( newPage );
    };
    /**
     * @function _on_page_next_click
     * @description internal callback when next is clicked. change the current page and call user callback
     * @param event
     * @private
     */
    this._on_page_next_click = function( event ){
        event.preventDefault();
        let newPage = this.current_page + 1;
        if( newPage > this.nb_pages ) newPage = 1;

        this.current_page = newPage;
        this.on_page_change( newPage );
    };
    /**
     * @function _on_page_click
     * @description internal callback when a page number is clicked. change the current page and call user callback
     * @param event
     * @private
     */
    this._on_page_click = function( event ){
        event.preventDefault();
        let $element = $(event.currentTarget.firstChild);
        let newPage = parseInt( $element[0].innerText );
        if( newPage != this.current_page ) {
            this.current_page = newPage;
            this.on_page_change(newPage);
        }
    };

    // Construct the visible pages list
    this.pages_list = null;
    if( this.nb_pages >= 2 ){
        this.page_range = 1;    // Page range around current page
        this.range_start = this.current_page - this.page_range;
        this.range_end = this.current_page + this.page_range;
        // If current page is near end, extend page range to the right
        if( this.range_end >= this.nb_pages -1 ){
            this.range_end = this.nb_pages;
            this.range_start = this.range_end - 4;
            if( this.range_start < 1 ) this.range_start = 1;
        }
        // If current page is near beginning, extend page range to the left
        if( this.range_start <= 2 ){
            this.range_start = 1;
            this.range_end = this.range_start + 4;
            if( this.range_end > this.nb_pages ) this.range_end = this.nb_pages;
        }

        // Page list is a array of
        //  - 'type': type of item, in 'pre' (previous), 'next', 'page', 'separator'
        //  - 'active': is this the active item (i.e. the current_page)
        //  - 'value': what is the value of this item (i.e. the page number)
        this.pages_list = [];

        // Previous page
        this.pages_list.push( {'type': 'pre', 'active': false, 'value': 0 } );

        // First page
        if( this.range_start > 1 )
            this.pages_list.push( {'type': 'page', 'active': false, 'value': 1 } );

        // First Separator
        if( this.range_start > 3 )
            this.pages_list.push( {'type': 'separator', 'active': false, 'value': -1 } );
        if( this.range_start == 3 )
            this.pages_list.push( {'type': 'page', 'active': false, 'value': 2 } );

        // Range
        for( i = this.range_start; i <= this.range_end; i++ ){
            this.pages_list.push( {'type': 'page', 'active': this.current_page==i, 'value': i } );
        }

        // Last separator
        if( this.range_end < this.nb_pages - 2 )
            this.pages_list.push( {'type': 'separator', 'active': false, 'value': -1 } );
        if( this.range_end == this.nb_pages - 2 )
            this.pages_list.push( {'type': 'page', 'active': false , 'value': this.nb_pages - 1 } );

        // Last page
        if( this.range_end < this.nb_pages )
            this.pages_list.push( {'type': 'page', 'active': false, 'value': this.nb_pages } );

        // Next page
        this.pages_list.push( {'type': 'next', 'active': false, 'value': 0 } );

    }

    /**
     * @function render
     * @description create and print the html for the pagination
     */
    this.render = function( ){
        // No obj, how to print inside ?
        if( this._obj == null ) return;

        let $_this = this;  // backup the object, because of strange behovior inside loops/enumeration for 'this'

        if( this.nb_elements <= 0 ){
            $_this._obj.innerHTML = '';
            return;
        }

        // Encapsulation for remodel purpose
        let $html =
            "<div class='bsTable_TablePagination'>" +
            "   <div class='pull-left bsTable_PaginationDetail'>";

        // The current range show
        $html +=
            "       <span class='bsTable_PaginationInfo'>" +
            "           Affichage des lignes "+$_this.first_line_shown+" à "+$_this.last_line_shown+" sur "+$_this.nb_elements+" lignes au total " +
            "       </span>";

        // The current pageSize and pageSizes list to select into
        $html +=
            "       <span class='bsTable_PageList'>" +
            "           <span class='btn-group dropdown dropup'>" +
            "               <button class='btn btn-default dropdown-toggle' type='button' data-toggle='dropdown'>" +
            "                   <span class='bsTable_PageSize'>"+$_this.page_size+"</span><span class='caret'></span>" +
            "               </button>" +
            "               <ul class='dropdown-menu' role='menu'>";
        this.page_size_list.forEach( function( element ){
            $html +=  "                   <li role='menuitem' class='bsTable_PageSize "+(parseInt($_this.page_size)==parseInt(element)?'active':'')+"'><a href='#'>"+element+"</a></li>"
        });
        $html +=
            "               </ul>" +
            "           </span>" +
            "           lignes par page" +
            "       </span>" +
            "   </div>";

        // Hide page number selection for only one page
        if( $_this.pages_list != null ) {
            $html +=
                "   <div class='pull-right pagination bsTable_Pagination'>" +
                "       <ul class='pagination bsTable_Pagination'>";

            // Generate all elements in PageList
            this.pages_list.forEach( function( pageElement ){
                let itemClass = 'bsTable_PageItem ';
                if( pageElement['type'] == 'pre' )
                    itemClass += 'bsTable_PagePre ';
                if( pageElement['type'] == 'next' )
                    itemClass += 'bsTable_PageNext ';
                if( pageElement['type'] == 'page' )
                    itemClass += 'bsTable_Page ';
                if( pageElement['type'] == 'separator' )
                    itemClass += 'bsTable_PageSeparator disabled ';

                if( pageElement['active'] )
                    itemClass += 'active';

                let itemText = '';
                if( pageElement['type'] == 'pre' ) itemText = '&lt;';
                if( pageElement['type'] == 'next' ) itemText = '&gt;';
                if( pageElement['type'] == 'separator' ) itemText = '...';
                if( pageElement['type'] == 'page' ) itemText = pageElement['value'];


                $html += "           <li class='"+itemClass+"'><a class='bsTable_PageLink' href='#'>"+itemText+"</a></li>";
            });
            // Close everything
            $html +=
                "       </ul>" +
                "   </div>";
        }
        $html +=
            "</div>";

        // update the object html code
        $_this._obj.innerHTML = $html;

        // Affect event management      // TODO : done with jquery, try to pass to vanilla instead
        $('li.bsTable_PageSize a').off('click').on('click', function(e){
           return $_this._on_page_size_change(e);
        });
        $('.bsTable_PagePre').off('click').on('click', function(e){
           return $_this._on_page_pre_click(e);
        });
        $('.bsTable_PageNext').off('click').on('click', function(e){
            return $_this._on_page_next_click(e);
        });
        $('.bsTable_Page').off('click').on('click', function(e){
            return $_this._on_page_click(e);
        });
    };
};



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
var $services = [];
var $legalEntities = [];
var $budgetCodes = [];
var $documentNatures = [];
var $documentTypes = [];
var $descriptions1 = [];
var $descriptions2 = [];
var $providers = [];
var $localizations = [];

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
var LOCALIZATION_ID = 0;
var LOCALIZATION_NAME = 1;

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
        $localizations = data[8]; // Unused here

		return true;
	}
	return false;
}
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

	}
}
function updateLegalEntities( $initLegalEntity ){
	// First test if service selected is really a service
	$partialLegalEntities = "";
	if( $("#frm_service option:selected").val() != "" ){

		$partialLegalEntities = "<option value ";
		if( parseInt($initLegalEntity) < 0 )
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
		if( parseInt($initBudgetCode) < 0 )
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
	    if( parseInt($initDocumentNature) < 0 )
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
	    if( parseInt($initDocumentType) < 0 )
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
		if( parseInt($initDescription1) < 0 )
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
		if( parseInt($initDescription2) < 0 )
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
		if( parseInt($initProvider) >= 0 )
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
function updateDestructionYear(){
/*
	$documenttypeIdx = parseInt( $("#frm_documenttype option:selected").attr('data') );
	$destructionTime = $documentTypes[$documenttypeIdx][DOCUMENTTYPE_KEEPALIVEDURATION];

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
	*/
}

// (c) Archimage - IDP Consulting 2016
// Author: Cyril PATRIGEON - BeSecure Labs

var $resultSearch = [];

var $_searchTranslations = null;
var $_searchInitialized = false;

function setServiceSelectList( ){
    $serviceOptions = "<option selected=\"selected\" data=\"-1\" value=\"-1\">"+$_searchTranslations[27]+"</option>";
    $i = 0;
    $services.forEach(function($serviceLine){
        $serviceOptions += "<option value=\"" + $serviceLine[SERVICE_ID] + "\" data=\"" + $i + "\">" + $serviceLine[SERVICE_NAME] + "</option> ";
        $i = $i + 1;
    });
    $('#service').attr('disabled', false);
    $('#service').html( $serviceOptions );
}

function setLegalEntitySelectList( ){

    $partialLegalEntities = "";
    $serviceSelected = parseInt( $("#service option:selected").attr('data') );

    $partialLegalEntities = "<option value selected=\"selected\" data=\"-1\" >"+$_searchTranslations[28]+"</option>";

    // Construct list of legal entities choices based on service id
    $listLegalEntities = [];
    if( $serviceSelected >= 0 )
        $listLegalEntities = $services[$serviceSelected][SERVICE_LEGALENTITIES_IDX];

    $i = 0;
    $legalEntities.forEach(function($legalentityLine){
        if( $serviceSelected < 0 || $listLegalEntities.indexOf( $legalentityLine[LEGALENTITY_ID] ) >= 0 ){
            $partialLegalEntities += "<option value=\"" + $legalentityLine[LEGALENTITY_ID] + "\" data=\"" + $i + "\" >" + $legalentityLine[LEGALENTITY_NAME] + "</option> ";
        }
        $i = $i + 1;
    });
    $('#legalentity').attr('disabled', false);
    $("#legalentity").html($partialLegalEntities);
}

function setDocumentNatureSelectList( ){

    $partialDocumentNature = "";
    $serviceSelected = parseInt( $('#service option:selected').attr('data') );

    $partialDocumentNature = "<option value selected=\"selected\" data=\"-1\" >"+$_searchTranslations[29]+"</option> ";

    $listDocumentNatures = [];
    if( $serviceSelected >= 0 )
        $listDocumentNatures = $services[$serviceSelected][SERVICE_DOCUMENTNATURES_IDX];

    $i = 0;
    $documentNatures.forEach(function($documentnatureLine){
        if( $serviceSelected < 0 || $listDocumentNatures.indexOf( $documentnatureLine[DOCUMENTNATURE_ID] ) >= 0 ){
            $partialDocumentNature += "<option value=\""+ $documentnatureLine[DOCUMENTNATURE_ID] +"\" data=\""+ $i +"\" >" + $documentnatureLine[DOCUMENTNATURE_NAME] + "</option>";
        }
        $i = $i + 1;
    });
    $('#documentnature').attr('disabled', false);
    $("#documentnature").html($partialDocumentNature);
}

function setDocumentTypeSelectList( ){

    $partialDocumentType = "";
    $documentnatureSelected = parseInt( $('#documentnature option:selected').attr('data') );

    $partialDocumentType = "<option value selected=\"selected\" data=\"-1\" >"+$_searchTranslations[30]+"</option> ";

    $listDocumentTypes = [];
    if( $documentnatureSelected >= 0 )
        $listDocumentTypes = $documentnatures[$documentnatureSelected][DOCUMENTNATURE_DOCUMENTTYPES_IDX];

    $i = 0;
    $documentTypes.forEach(function($documenttypeLine){
        if( $documentnatureSelected < 0 || $listDocumentTypes.indexOf( $documenttypeLine[DOCUMENTTYPE_ID] ) >= 0 ){
            $partialDocumentType += "<option value=\""+ $documenttypeLine[DOCUMENTTYPE_ID] +"\" data=\""+ $i +"\" >" + $documenttypeLine[DOCUMENTTYPE_NAME] + "</option>";
        }
        $i = $i + 1;
    });
    $('#documenttype').attr('disabled', false);
    $("#documenttype").html($partialDocumentType);
}

function setDescription1SelectList( ){

    $partialDescriptions1 = "";
    $serviceSelected = parseInt( $("#service option:selected").attr('data') );

    $partialDescriptions1 = "<option value selected=\"selected\" data=\"-1\" >"+$_searchTranslations[31]+"</option>";

    // Construct list of legal entities choices based on service id
    $listDescriptions1 = [];
    if( $serviceSelected >= 0 )
        $listDescriptions1 = $services[$serviceSelected][SERVICE_DESCRIPTIONS1_IDX];

    $i = 0;
    $descriptions1.forEach(function($description1Line){
        if( $serviceSelected < 0 || $listDescriptions1.indexOf( $description1Line[DESCRIPTION1_ID] ) >= 0 ){
            $partialDescriptions1 += "<option value=\"" + $description1Line[DESCRIPTION1_ID] + "\" data=\"" + $i + "\" >" + $description1Line[DESCRIPTION1_NAME] + "</option> ";
        }
        $i = $i + 1;
    });
    $('#description1').attr('disabled', false);
    $("#description1").html($partialDescriptions1);
}

function setDescription2SelectList( ){

    $partialDescriptions2 = "";
    $serviceSelected = parseInt( $("#service option:selected").attr('data') );

    $partialDescriptions2 = "<option value selected=\"selected\" data=\"-1\" >"+$_searchTranslations[32]+"</option>";

    // Construct list of legal entities choices based on service id
    $listDescriptions2 = [];
    if( $serviceSelected >= 0 )
        $listDescriptions2 = $services[$serviceSelected][SERVICE_DESCRIPTIONS2_IDX];

    $i = 0;
    $descriptions2.forEach(function($description2Line){
        if( $serviceSelected < 0 || $listDescriptions2.indexOf( $description2Line[DESCRIPTION2_ID] ) >= 0 ){
            $partialDescriptions2 += "<option value=\"" + $description2Line[DESCRIPTION2_ID] + "\" data=\"" + $i + "\" >" + $description2Line[DESCRIPTION2_NAME] + "</option> ";
        }
        $i = $i + 1;
    });
    $('#description2').attr('disabled', false);
    $("#description2").html($partialDescriptions2);
}

function setBudgetCodeSelectList( ){

    $partialBudgetCodes = "";
    $serviceSelected = parseInt( $("#service option:selected").attr('data') );

    $partialBudgetCodes = "<option value selected=\"selected\" data=\"-1\" >"+$_searchTranslations[33]+"</option>";

    // Construct list of legal entities choices based on service id
    $listBudgetCodes = [];
    if( $serviceSelected >= 0 )
        $listBudgetCodes = $services[$serviceSelected][SERVICE_BUDGETCODES_IDX];

    $i = 0;
    $budgetCodes.forEach(function($budgetcodeLine){
        if( $serviceSelected < 0 || $listBudgetCodes.indexOf( $budgetcodeLine[BUDGETCODE_ID] ) >= 0 ){
            $partialBudgetCodes += "<option value=\"" + $budgetcodeLine[BUDGETCODE_ID] + "\" data=\"" + $i + "\" >" + $budgetcodeLine[BUDGETCODE_NAME] + "</option> ";
        }
        $i = $i + 1;
    });
    $('#budgetcode').attr('disabled', false);
    $("#budgetcode").html($partialBudgetCodes);
}

function setProviderSelectList( ){

    $partialProviders = "";
    $serviceSelected = parseInt( $("#service option:selected").attr('data') );

    $partialProviders = "<option value selected=\"selected\" data=\"-1\" >"+$_searchTranslations[34]+"</option>";

    // Construct list of legal entities choices based on service id
    $listProviders = [];
    if( $serviceSelected >= 0 )
        $listProviders = $services[$serviceSelected][SERVICE_PROVIDERS_IDX];

    $i = 0;
    $providers.forEach(function($providerLine){
        if( $serviceSelected < 0 || $listProviders.indexOf( $providerLine[PROVIDER_ID] ) >= 0 ){
            $partialProviders += "<option value=\"" + $providerLine[PROVIDER_ID] + "\" data=\"" + $i + "\" >" + $providerLine[PROVIDER_NAME] + "</option> ";
        }
        $i = $i + 1;
    });
    $('#provider').attr('disabled', false);
    $("#provider").html($partialProviders);
}

function updateSearchLists( data ){

    updateLists( data );

    setServiceSelectList();
    setLegalEntitySelectList();
    setDocumentNatureSelectList();
    setDocumentTypeSelectList();
    setDescription1SelectList();
    setDescription2SelectList();
    setBudgetCodeSelectList();
    setProviderSelectList();

    $('#name').attr('disabled', false);
    $('#limitnum').attr('disabled', false);
    $('#limitalpha').attr('disabled', false);
    $('#limitalphanum').attr('disabled', false);
    $('#limitdate').attr('disabled', false);
//    $('#closureyear').attr('disabled', false);
//    $('#destructionyear').attr('disabled', false);
    $('#ordernumber').attr('disabled', false);
    $('#documentnumber').attr('disabled', false);
    $('#boxnumber').attr('disabled', false);
    $('#containernumber').attr('disabled', false);

    $('#searchBtn').removeClass( 'disabled' );
    $('#imgAjaxLoad').addClass('hidden');
    $('#txtBtn').removeClass('hidden');

    return true;
}

function onModifyServiceSelect( ){

    setLegalEntitySelectList();
    setDocumentNatureSelectList();
    setDocumentTypeSelectList();
    setDescription1SelectList();
    setDescription2SelectList();
    setBudgetCodeSelectList();
    setProviderSelectList();
}

function onModifyDocumentNatureSelect(){

    setDocumentTypeSelectList();
}

function setSliders( $closureMin, $closureMax, $closureFrom, $closureTo, $destructionMin, $destructionMax, $destructionFrom, $destructionTo ){

    $('#minClosureYear').html( $closureMin  );
    $('#maxClosureYear').html( $closureMax );
    $('#closureyear').val( ""+$closureFrom+","+$closureTo );
    $('#closureyear-range').slider({
        range: true,
        min: $closureMin,
        max: $closureMax,
        values: [ $closureFrom, $closureTo ],
        slide: function( event, ui ) {
            $( "#minClosureYear" ).html( ui.values[ 0 ] );
            $( "#maxClosureYear" ).html( ui.values[ 1 ] );
            $('#closureyear').val( ""+ui.values[ 0 ]+","+ui.values[ 1 ] );
        }
    });

    $('#minDestructionYear').html( $destructionMin   );
    $('#maxDestructionYear').html(  $destructionTo );
    $('#destructionyear').val( ""+$destructionFrom+","+$destructionTo );
    $('#destructionyear-range').slider({
        range: true,
        min: $destructionMin,
        max: $destructionMax,
        values: [ $destructionFrom, $destructionTo ],
        slide: function( event, ui ) {
            $( "#minDestructionYear" ).html( ui.values[ 0 ] );
            $( "#maxDestructionYear" ).html( ui.values[ 1 ] );
            $('#destructionyear').val( ""+ui.values[ 0 ]+","+ui.values[ 1 ] );
        }
    });

}

function stateFormatter( value, row, index ){

    if( $resultSearch[index]['locked'] )
        return { disabled: true };

    if( $resultSearch[index]['authorized'] )
        if( insideStateFormatter( value, row, index) )
            return { disabled: true };
        else
            return { disabled: false };
    else
        return { disabled: true };

}
function rowStyle( row, index ){

    if( $resultSearch[index]['locked'] )
        return { classes: 'locked idp_std_style' };

    if( $resultSearch[index]['authorized'] )
        if( insideRowStyle( row, index) )
            return { classes: 'info idp_std_style' };
        else
            return { classes: 'idp_std_style' };  //return { classes: 'success' };
    else
        return { classes: 'warning idp_std_style' };

}
function fctCellStyle( value, row, index ){

    if( $resultSearch[index]['locked'] )
        return { classes: 'locked' };

    if( $resultSearch[index]['authorized'] )
        if( insideRowStyle( row, index) )
            return { classes: 'info' };
        else
            return { };  //return { classes: 'success' };
    else
        return { classes: 'warning' };

}

function initSearchTable( functionToCall ){

    // Get back User Settings to init Main Tab
    $dataStr = "page=" + $_currentPage;
    $.ajax({
        type: "GET",
        url: window.JSON_URLS.bs_idp_backoffice_ajax_usersettings_get,
        data: $dataStr,
        cache: false,
        success: function($response) {
            // parameters : $table, $url, $userSettings, $tabletranslation, $commonSettings, $sidePagination, $method, $caller
            initMainTab( $('#listsearchTable'), null, $response.data, $_tabletranslation, $_commonsettings, 'server', 'get', $_currentPage )
            functionToCall();
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert( 'Error while retreiving user Settings !' );
        }
    });

}

function initSearchSelectBox( functionToCall ){
    // Ask datas for select box from server
    $.ajax({
        type: "POST",
        url: window.JSON_URLS.bs_idp_archive_form_initlists,
        data: null,
        cache: false,
        success: function( data ) {
            updateSearchLists( data );
            initSearchTable( functionToCall );
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $('#ajaxError').html( $_searchTranslations[35] );
            $('#ajaxError').removeClass('hidden');
        }
    });
}

function initSearchSlidersAndTooltips( ){

    $anneeMin = 1900;
    $anneeMax = 2199;
    /*
    if( $_currentPage == PAGE_DELETE ) {
        var $now = new Date();
        $anneeMax = parseInt( $now.getFullYear() );
    }
    */

    // Closure min, max, from, to / destruction min, max, from, to
    setSliders( $anneeMin, $anneeMax, $anneeMin, $anneeMax, $anneeMin, $anneeMax, $anneeMin, $anneeMax );

    $('#limitdate').datepicker({'format': 'dd/mm/yyyy'});

    // Activate tooltip on buttons
    $('[data-toggle="tooltip"]').tooltip();

    // Set unlimited select to "no matter"
    $('#unlimited').val(2);

}

function initSearch( $commonSettings ){
    initSearchSlidersAndTooltips();
    initSearchSelectBox( endInit );
    initSearchViewable( $commonSettings );
};

function endInit(){
    // See IDPArchiveFilter.js for details
    initFilters();

    // See IDPManageView.js for details
    initManageView();

    // Launch first search with default values
    doSearch();

    // Init a search tooltip
    $('#list input:text').tooltip({html: true, title: "La recherche s'effectue pour les champs : <b>Libellé</b>, <b>N° d'ordre</b>, <b>N° Conteneur</b>, <b>N° Boîte</b>, <b>N° Dossier</b>"});
}

$('#cancelSearchBtn').click( function( $event ){
    $event.preventDefault();

    // reset all fields to default values
    resetSearch();

    // And do the search
    doSearch();
})

function resetSearch( ){
    // Re-init service, normally all synchronized list will follow
    $('#service').val(-1);
    setLegalEntitySelectList();
    setDocumentNatureSelectList();
    setDocumentTypeSelectList();
    setDescription1SelectList();
    setDescription2SelectList();
    setBudgetCodeSelectList();
    setProviderSelectList();
    // Reste sliders
    $anneeMin = 1900;
    $anneeMax = 2199;
    /*
    if( $_currentPage == PAGE_DELETE ) {
        var $now = new Date();
        $anneeMax = parseInt( $now.getFullYear() );
    }
    */
    setSliders( $anneeMin, $anneeMax, $anneeMin, $anneeMax, $anneeMin, $anneeMax, $anneeMin, $anneeMax );
    // Reset all the other fields
    $('#documentnumber').val('');
    $('#boxnumber').val('');
    $('#containernumber').val('');
    $('#ordernumber').val('');
    $('#name').val('');
    $('#limitalpha').val('');
    $('#limitalphanum').val('');
    $('#limitdate').val('');
    $('#limitnum').val('');
    $('#unlimited').val(2);
}

$('#searchBtn').click(function( $event ){
    $event.preventDefault();

    // Set all filters to true
    setFullFilters();

    // Clear contextual text search
    $('#listsearchTable').bootstrapTable('resetSearch', '');

    // And do the search
    doSearch()
});

//................................................................
// Callbacks for bsTablePagination
function onPageSizeChange( newPageSize ){
    // For security when pageSize changes, reset pageOffset to 1
    $_lastPageOffset = 1;
    mainTablePageChange( $_lastPageOffset, newPageSize );
}
function onPageChange( newPage ){
    $_lastPageOffset = newPage;
    doSearch();
    resetMultipleSelect();
}
//................................................................

function doSearch( ){

    // TODO verify consistancy of inputs
    // limitnum should be a numeric value
    // limitdate should be a date with dd/mm/YYYY format

    $('#searchBtn').addClass( 'disabled' );
    $('#imgAjaxLoad').removeClass('hidden');
    $('#txtBtn').addClass('hidden');
    $('#ajaxInfo').addClass('hidden');
    $('#ajaxError').addClass('hidden');

    $filters = getFilters();

    $serviceSelected = $('#service option:selected').val() == -1 ? '' : $('#service option:selected').val();

    // Get back datas from inputs to feed search parameters
    $searchParameters = {
        'wheretosearch': $('#wheretosearch').val(),			// filter
        'callFrom': window.IDP_CONST.bs_idp_menu_activated,
        'service': $serviceSelected,
        'legalentity': $('#legalentity option:selected').val(),
        'description1': $('#description1 option:selected').val(),
        'description2': $('#description2 option:selected').val(),
        'name': $('#name').val(),
        'limitnum': $('#limitnum').val(),
        'limitalpha': $('#limitalpha').val(),
        'limitalphanum': $('#limitalphanum').val(),
        'limitdate': $('#limitdate').val(),
        'ordernumber': $('#ordernumber').val(),
        'budgetcode': $('#budgetcode option:selected').val(),
        'documentnature': $('#documentnature option:selected').val(),
        'documenttype': $('#documenttype option:selected').val(),
        'closureyear': $('#closureyear').prop("value"),
        'destructionyear': $('#destructionyear').prop("value"),
        'documentnumber': $('#documentnumber').val(),
        'boxnumber': $('#boxnumber').val(),
        'containernumber': $('#containernumber').val(),
        'provider': $('#provider option:selected').val(),
        'unlimited': $('#unlimited').val(),
        'special': $_table.bootstrapTable('getOptions').searchText,
        'pageOffset': $_lastPageOffset,
        'pageSize': $_lastPageSize,
        'sortAsc': $_lastSortOrder,
        'sortColumn': $_lastSortColumn
    };

    if( $filters ) {
//        Object.assign( $searchParameters, $filters );

        $searchParameters['filterstatus'] = $filters['filterstatus'];
        $searchParameters['filterwhere'] = $filters['filterwhere'];
        $searchParameters['filterwith'] = $filters['filterwith'];
        $searchParameters['filterlocalization'] = $filters['filterlocalization'];

    }

    // Send ajax request for results
    $.ajax({
        type: "GET",
        url: window.JSON_URLS.bs_idp_archive_search_ajax,
        data: $searchParameters,
        cache: false,
        success: function( data, textStatus, jqXHR ){
            $resultSearch = data['rows'];
            $('#listsearchTable').bootstrapTable('load', $resultSearch );

            $total_rows = parseInt( data['total'] );

            // Call the bsTable_Pagination object with accurate datas
            let $pagination = new bsTable_Pagination( $_lastPageSize, $total_rows, $_lastPageOffset, 'bsTablePagination' );
            $pagination.setEventCallbacks( 'on_page_size_change', onPageSizeChange );
            $pagination.setEventCallbacks( 'on_page_change', onPageChange );
            $pagination.render( );

/*
            if( jqXHR.status == 206 ){
                alert( "Attention seuls les 500 premiers résultats s'affichent." );
            }
*/
            $('#searchBtn').removeClass( 'disabled' );
            $('#imgAjaxLoad').addClass('hidden');
            $('#txtBtn').removeClass('hidden');

            $('#tabfilter').removeClass('active');
            $('#litabfilter').removeClass('active');
            $('#tabsearch').removeClass('active');
            $('#litabsearch').removeClass('active');
            $('#tabresults').addClass('active');
            $('#litabresults').addClass('active');
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $('#ajaxError').html( $_searchTranslations[35] );
            $('#ajaxError').removeClass('hidden');
        },
        timeout: 10000
    });
};

$('#service').change(function( event ){
    event.preventDefault();

    onModifyServiceSelect();
});
$('#documentnature').change(function( event ){
    event.preventDefault();

    onModifyDocumentNatureSelect();
});
$('#unlimited').change( function( event ){
    event.preventDefault();

    if( $('#unlimited').val() == 0 ) // Unlimited
        $( "#destructionyear-range" ).slider( "option", "disabled", true );
    else // limited or no matters
        $( "#destructionyear-range" ).slider( "option", "disabled", false );
});

$('#divPrintPartialList').click(function( event ){
    event.preventDefault();

    if( $('#listsearchTable').bootstrapTable('getSelections').length > 0 )
        printTable( true, $('#listsearchTable'), 2 );
});
$('#divPrintList').click(function( event ){
    event.preventDefault();

    //if( $('#listsearchTable').bootstrapTable('getSelections').length > 0 )
        printTable( false, $('#listsearchTable'), 2, window.IDP_CONST.bs_idp_current_page );
});

var $lastRowClicked = -1;
var $lastActionUsed = -1; // -1 = No action, 0 = Select, 1 = unselect
var $multipleCheckInProgress = false;
var $shiftIsPressed = false;
$(document).keydown(function( event ){
    //event.preventDefault();

    if(event.keyCode=="16")
        $shiftIsPressed = true;
});
$(document).keyup(function( event ){
    //event.preventDefault();

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
                                $('#listsearchTable').bootstrapTable('check', $idxRow);
                            else
                                $('#listsearchTable').bootstrapTable('uncheck', $idxRow);
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
function getRowIndexFromCheckbox( checkbox ){
    var $BTOptions = $('#listsearchTable').bootstrapTable( 'getOptions' );
    if( $BTOptions.cardView )
        var $trRow = checkbox.parent().parent().parent(); // In cardViewMode
    else
        var $trRow = checkbox.parent().parent(); // In tableViewMode
    return $trRow.index();
}

function initSearchViewable( $commonSettings ){
    if( !$commonSettings ) return;

    // Budget Code
    if( !$commonSettings[1]['ACTIVATED'] ) {
        $('#divLblBudgetCode').addClass('hidden');
        $('#divSelectBudgetcode').addClass('hidden');
    } else {
        $('#divLblBudgetCode').removeClass('hidden');
        $('#divSelectBudgetcode').removeClass('hidden');
    }

    // Document Nature (ie Activity)
    if( !$commonSettings[2]['ACTIVATED'] ) {
        $('#divLblDocumentnature').addClass('hidden');
        $('#divSelectDocumentnature').addClass('hidden');
    } else {
        $('#divLblDocumentnature').removeClass('hidden');
        $('#divSelectDocumentnature').removeClass('hidden');
    }

    // Document Type
    if( !$commonSettings[3]['ACTIVATED'] ) {
        $('#divLblDocumenttype').addClass('hidden');
        $('#divSelectDocumenttype').addClass('hidden');
    } else {
        $('#divLblDocumenttype').removeClass('hidden');
        $('#divSelectDocumenttype').removeClass('hidden');
    }

    // Description1
    if( !$commonSettings[4]['ACTIVATED'] ) {
        $('#divLblDescription1').addClass('hidden');
        $('#divSelectDescription1').addClass('hidden');
    } else {
        $('#divLblDescription1').removeClass('hidden');
        $('#divSelectDescription1').removeClass('hidden');
    }

    // Description2
    if( !$commonSettings[5]['ACTIVATED'] ) {
        $('#divLblDescription2').addClass('hidden');
        $('#divSelectDescription2').addClass('hidden');
    } else {
        $('#divLblDescription2').removeClass('hidden');
        $('#divSelectDescription2').removeClass('hidden');
    }

    if(( $commonSettings[2]['ACTIVATED'] )||( $commonSettings[3]['ACTIVATED'] )||( $commonSettings[4]['ACTIVATED'] )||( $commonSettings[5]['ACTIVATED'] )) {
        $('#divBlockInformation').removeClass( 'hidden' );
    } else {
        $('#divBlockInformation').addClass( 'hidden' );
    }

    // Document / File Number
    if( !$commonSettings[6]['ACTIVATED'] ) {
        $('#divLblFilenumber').addClass('hidden');
        $('#divInputFilenumber').addClass('hidden');
    } else {
        $('#divLblFilenumber').removeClass('hidden');
        $('#divInputFilenumber').removeClass('hidden');
    }

    // Box Number
    if( !$commonSettings[7]['ACTIVATED'] ) {
        $('#divLblBoxnumber').addClass('hidden');
        $('#divInputBoxnumber').addClass('hidden');
    } else {
        $('#divLblBoxnumber').removeClass('hidden');
        $('#divInputBoxnumber').removeClass('hidden');
    }

    // Container Number
    if( !$commonSettings[8]['ACTIVATED'] ) {
        $('#divLblContainernumber').addClass('hidden');
        $('#divInputContainernumber').addClass('hidden');
    } else {
        $('#divLblContainernumber').removeClass('hidden');
        $('#divInputContainernumber').removeClass('hidden');
    }

    // Provider
    if( !$commonSettings[9]['ACTIVATED'] ) {
        $('#divLblProvider').addClass('hidden');
        $('#divSelectProvider').addClass('hidden');
    } else {
        $('#divLblProvider').removeClass('hidden');
        $('#divSelectProvider').removeClass('hidden');
    }

    if(( $commonSettings[6]['ACTIVATED'] )||( $commonSettings[7]['ACTIVATED'] )||( $commonSettings[8]['ACTIVATED'] )||( $commonSettings[9]['ACTIVATED'] )) {
        $('#divBlockProviderDatas').removeClass( 'hidden' );
    } else {
        $('#divBlockProviderDatas').addClass( 'hidden' );
    }

    // Limits date
    if( !$commonSettings[10]['ACTIVATED'] ) {
        $('#divLblLimitsdate').addClass('hidden');
        $('#divInputLimitsdate').addClass('hidden');
    } else {
        $('#divLblLimitsdate').removeClass('hidden');
        $('#divInputLimitsdate').removeClass('hidden');
    }

    // Limits numeric
    if( !$commonSettings[11]['ACTIVATED'] ) {
        $('#divLblLimitsnum').addClass('hidden');
        $('#divInputLimitsnum').addClass('hidden');
    } else {
        $('#divLblLimitsnum').removeClass('hidden');
        $('#divInputLimitsnum').removeClass('hidden');
    }

    // Limits alphabetic
    if( !$commonSettings[12]['ACTIVATED'] ) {
        $('#divLblLimitsalpha').addClass('hidden');
        $('#divInputLimitsalpha').addClass('hidden');
    } else {
        $('#divLblLimitsalpha').removeClass('hidden');
        $('#divInputLimitsalpha').removeClass('hidden');
    }

    // Limits alphanumeric
    if( !$commonSettings[13]['ACTIVATED'] ) {
        $('#divLblLimitsalphanum').addClass('hidden');
        $('#divInputLimitsalphanum').addClass('hidden');
    } else {
        $('#divLblLimitsalphanum').removeClass('hidden');
        $('#divInputLimitsalphanum').removeClass('hidden');
    }

    if(( $commonSettings[10]['ACTIVATED'] )||( $commonSettings[11]['ACTIVATED'] )||( $commonSettings[12]['ACTIVATED'] )||( $commonSettings[13]['ACTIVATED'] )) {
        $('#divBlockLimits').removeClass( 'hidden' );
    } else {
        $('#divBlockLimits').addClass( 'hidden' );
    }
}
var FILTER_STATUS_NONE = 0;
var FILTER_STATUS_AVAILABLE = 1;
var FILTER_STATUS_TRANSFER = 2;
var FILTER_STATUS_CONSULT = 4;
var FILTER_STATUS_RETURN = 8;
var FILTER_STATUS_EXIT = 16;
var FILTER_STATUS_DELETE = 32;
var FILTER_STATUS_RELOC = 64;
var FILTER_STATUS_INCONSULT = 128;
var FILTER_STATUS_DTA = 256;

var FILTER_WHERE_NONE = 0;
var FILTER_WHERE_INTERNAL = 1;
var FILTER_WHERE_INTERMEDIATE = 2;
var FILTER_WHERE_PROVIDER = 4;

var FILTER_WITH_NONE = 0;
var FILTER_WITH_DOCUMENT = 1;
var FILTER_WITH_BOX = 2;
var FILTER_WITH_CONTAINER = 4;

var $_filterTranslations = null;
var $_localizationsIDs = [];

function initFilters( ){
    $_filterTranslations = JSON.parse( window.IDP_CONST.bs_filtertranslations );

    $list_script_ckeck = '';
    $list_script_unckeck = '';

    $strLocalizations = '';
    $localizations.forEach(function( $localization ){
        $strLocalizations += "<input type='checkbox' id='localization_"+$localization[LOCALIZATION_ID]+"' value='"+$localization[LOCALIZATION_ID]+"' class='localization'>";
        $strLocalizations += "<label for='localization_"+$localization[LOCALIZATION_ID]+"'>&nbsp;"+$localization[LOCALIZATION_NAME]+"</label><br/>";

        $_localizationsIDs.push( $localization[LOCALIZATION_ID] );
    });
    $('#localizationColumnChoices').html( $strLocalizations );

    switch( $_currentPage ){
        case PAGE_TRANSFER:
            $('#filterStatusDTA').prop('checked', true);
            break;
        case PAGE_CONSULT:
            $('#filterStatusConsult').prop('checked', true);
            $('#filterStatusAvailable').prop('checked', true);
            break;
        case PAGE_DELETE:
            // $('#filterStatusDelete').prop('checked', true);
            $('#filterStatusAvailable').prop('checked', true);
            break;
        case PAGE_EXIT:
            $('#filterStatusExit').prop('checked', true);
            $('#filterStatusAvailable').prop('checked', true);
            break;
        case PAGE_RETURN:
            // $('#filterStatusReturn').prop('checked', true);
            $('#filterStatusConsult').prop('checked', true );
            // $('#filterStatusAvailable').prop('checked', true);
            break;
        case PAGE_RELOC:
            $('#filterStatusReloc').prop('checked', true);
            $('#filterStatusAvailable').prop('checked', true);
            $('#filterStatusConsult').prop('checked', true );
            break;
        case PAGE_UNLIMITED:
            $('#filterStatusAvailable').prop('checked', true);
            $('#filterStatusConsult').prop('checked', true);
            $('#filterStatusExit').prop('checked', true);
            $('#filterStatusReloc').prop('checked', true);
            break;
    }
}

$('#filterStatusAll').change( function( event ){
    event.preventDefault();

    if( $('#filterStatusAll').is(":checked") )
        $('.status').prop('checked', true );
    else
        $('.status').prop('checked', false );
});
$('#filterWhereAll').change( function( event ){
    event.preventDefault();

    if( $('#filterWhereAll').is(":checked") )
        $('.where').prop('checked', true );
    else
        $('.where').prop('checked', false );
});
$('#filterWithAll').change( function( event ){
    event.preventDefault();

    if( $('#filterWithAll').is(":checked") )
        $('.with').prop('checked', true );
    else
        $('.with').prop('checked', false );
});
$('#filterLocalizationAll').change( function( event ){
   event.preventDefault();

   if( $('#filterLocalizationAll').is(':checked') )
       $('.localization').prop('checked', true );
   else
       $('.localization').prop('checked', false );
});

function setFullFilters(  ){

    $('#filterStatusTransfer').prop('checked', true);
    $('#filterStatusAvailable').prop('checked', true);
    $('#filterStatusInConsult').prop('checked', true);
    $('#filterStatusConsult').prop('checked', true);
    $('#filterStatusReturn').prop('checked', true);
    $('#filterStatusExit').prop('checked', true);
    $('#filterStatusDelete').prop('checked', true);
    $('#filterStatusReloc').prop('checked', true);

    $('#filterWhereInternal').prop('checked', true);
    $('#filterWhereIntermediate').prop('checked', true);
    $('#filterWhereProvider').prop('checked', true);

    $('#filterWithDocument').prop('checked', false);
    $('#filterWithBox').prop('checked', false);
    $('#filterWithContainer').prop('checked', false);

    $localizations.forEach(function( $localization ) {
        $checkstr  = 'localization_' + $localization[LOCALIZATION_ID];
        $($checkstr).prop('checked', true);
    });
}

function getFilters( ){
    $_filters = {
        'filterstatus': FILTER_STATUS_NONE,
        'filterwhere': FILTER_WHERE_NONE,
        'filterwith': FILTER_WITH_NONE,
        'filterlocalization': ''
    };

    if( $('#filterStatusDTA').prop('checked') == true )
        $_filters['filterstatus'] += FILTER_STATUS_DTA;
    if( $('#filterStatusAvailable').prop('checked') == true )
        $_filters['filterstatus'] += FILTER_STATUS_AVAILABLE;
    if( $('#filterStatusTransfer').prop('checked') == true )
        $_filters['filterstatus'] += FILTER_STATUS_TRANSFER;
    if( $('#filterStatusConsult').prop('checked') == true )
        $_filters['filterstatus'] += FILTER_STATUS_CONSULT;
    if( $('#filterStatusReturn').prop('checked') == true )
        $_filters['filterstatus'] += FILTER_STATUS_RETURN;
    if( $('#filterStatusExit').prop('checked') == true )
        $_filters['filterstatus'] += FILTER_STATUS_EXIT;
    if( $('#filterStatusDelete').prop('checked') == true )
        $_filters['filterstatus'] += FILTER_STATUS_DELETE;
    if( $('#filterStatusReloc').prop('checked') == true )
        $_filters['filterstatus'] += FILTER_STATUS_RELOC;
    if( $('#filterStatusInConsult').prop('checked') == true )
        $_filters['filterstatus'] += FILTER_STATUS_INCONSULT;

    if( $('#filterWhereInternal').prop('checked') == true )
        $_filters['filterwhere'] += FILTER_WHERE_INTERNAL;
    if( $('#filterWhereIntermediate').prop('checked') == true )
        $_filters['filterwhere'] += FILTER_WHERE_INTERMEDIATE;
    if( $('#filterWhereProvider').prop('checked') == true )
        $_filters['filterwhere'] += FILTER_WHERE_PROVIDER;

    if( $('#filterWithDocument').prop('checked') == true )
        $_filters['filterwith'] += FILTER_WITH_DOCUMENT;
    if( $('#filterWithBox').prop('checked') == true )
        $_filters['filterwith'] += FILTER_WITH_BOX;
    if( $('#filterWithContainer').prop('checked') == true )
        $_filters['filterwith'] += FILTER_WITH_CONTAINER;

    $_localizationsIDs.forEach(function( $localizationID ){
        $checkstr = '#localization_'+$localizationID;
        if( $($checkstr).prop('checked') == true ) {
            var $str = $_filters['filterlocalization'];
            $_filters['filterlocalization'] += ($str.length <= 0 ? '' : ',') + $localizationID;
        }
    });

    return $_filters;
}

$('#btnChangeFilter').click(function( $event ){
    $event.preventDefault();

    // Clear contextual text search
    $('#listsearchTable').bootstrapTable('resetSearch', '');

    // Clear all the searchs options
    resetSearch( );

    // do the Search
    doSearch( );
})
// Must be included after IDPArchiveSearch.js

var $currentArchiveView = null;


$(document).ready(function(){

});


function initManageView(){
    $('#listsearchTable').bootstrapTable({})
        .on('click-row.bs.table', function( e, row, $element ){ $currentArchiveView = row['id']; } )
        .on('check.bs.table', function(e, row ){ $currentArchiveView = row['id']; })
};

$('#divCancel').click(function(){
    $('#viewArchive').hide();
});

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

var _PRECISION_DATE_MANDATORY = [ PAGE_TRANSFER, PAGE_CONSULT, PAGE_DELETE, PAGE_EXIT, PAGE_RELOC, PAGE_RETURN ];
var _PRECISION_WHO_MANDATORY = [ PAGE_TRANSFER, PAGE_CONSULT, PAGE_DELETE, PAGE_EXIT, PAGE_RELOC, PAGE_RETURN ];
var _PRECISION_WHERE_MANDATORY = [ PAGE_TRANSFER, PAGE_CONSULT, PAGE_EXIT, PAGE_RETURN ];
var _PRECISION_FLOOR_MANDATORY = [ PAGE_TRANSFER, PAGE_CONSULT, PAGE_EXIT, PAGE_RETURN ];
var _PRECISION_OFFICE_MANDATORY = [ PAGE_TRANSFER, PAGE_CONSULT, PAGE_EXIT, PAGE_RETURN ];
var _PRECISION_COMMENT_MANDATORY = [ PAGE_CONSULT ];
// Precision Form Validation
$('form').submit(function() {
    if( _PRECISION_DATE_MANDATORY.indexOf( $_currentPage ) >= 0 ) {
        if ($('#form_precisiondate').val() == '') {
            popError($('#form_precisiondate'), $_precisionTranslations[20], 'bottom');
            return false;
        }
        $d = new Date();
        $d.setHours(0, 0, 0, 0); // Set to last midnight
        $nowTimestamp = $d.getTime();
        $formDate = $('#form_precisiondate').val().split("/");
        $dateEntered = new Date($formDate[2], $formDate[1] - 1, $formDate[0]);
        $enteredTimestamp = $dateEntered.getTime();
        if ($enteredTimestamp < $nowTimestamp) {
            switch( $_currentPage ) {
                case PAGE_TRANSFER: popError ($('#form_precisiondate'), $_precisionTranslations[33], 'bottom'); break;
                case PAGE_CONSULT: popError ($('#form_precisiondate'), $_precisionTranslations[27], 'bottom'); break;
                case PAGE_DELETE: popError ($('#form_precisiondate'), $_precisionTranslations[30], 'bottom'); break;
                case PAGE_EXIT: popError ($('#form_precisiondate'), $_precisionTranslations[29], 'bottom'); break;
                case PAGE_RELOC: popError ($('#form_precisiondate'), $_precisionTranslations[31], 'bottom'); break;
                case PAGE_RETURN: popError ($('#form_precisiondate'), $_precisionTranslations[28], 'bottom'); break;
            }
            return false;
        }
    }
    if( _PRECISION_WHO_MANDATORY.indexOf( $_currentPage ) >= 0 ) {
        if( $('#form_precisionwho').val() == '' ) {
            popError($('#form_precisionwho'), $_precisionTranslations[22], 'bottom');
            return false;
        }
    }
    if( _PRECISION_WHERE_MANDATORY.indexOf( $_currentPage ) >= 0 ) {
        if( $('#form_precisionwhere option:selected').val() == '' ) {
            popError($('#form_precisionwhere'), $_precisionTranslations[23], 'bottom');
            return false;
        }
    }
    if( _PRECISION_FLOOR_MANDATORY.indexOf( $_currentPage ) >= 0 ) {
        if( $('#form_precisionfloor').val() == '' ) {
            popError($('#form_precisionfloor'), $_precisionTranslations[24], 'bottom');
            return false;
        }
    }
    if( _PRECISION_OFFICE_MANDATORY.indexOf( $_currentPage ) >= 0 ) {
        if ($('#form_precisionoffice').val() == '') {
            popError($('#form_precisionoffice'), $_precisionTranslations[25], 'top');
            return false;
        }
    }
    if( _PRECISION_COMMENT_MANDATORY.indexOf( $_currentPage ) >= 0 ) {
        if ($listToDeliverPrepare && ($listToDeliverPrepare.length > 0) && ($('#form_precisioncomment').val() == '')) {
            popError($('#form_precisioncomment'), $_precisionTranslations[32], 'top');
            return false;
        }
    }
    return true;
});

// Date Time Picker Initialization & Behavior
var datePicker = $('#form_precisiondate').datepicker({'format': 'dd/mm/yyyy'})
    .on('changeDate', function(ev){ datePicker.datepicker('hide'); });
$('.datepicker').css("z-index","100000");

// Error message showing
function popError( $ancre, $message, $where ){
    $ancre.popover({trigger:'manual', placement: $where, content: $message });
    $ancre.popover('show').addClass('has-error');
    $ancre.click(function(){ $ancre.popover('hide'); });
}

// Add and Remove Function
function onclickAddToBasket( $backupList, $IDList, $basketTable ){

    // Get selections from UAs list
    var $selection = $('#listsearchTable').bootstrapTable('getSelections');
    // Backup them in global variable
    for( $i=0; $i< $selection.length; $i++ ){
        if( $IDList.indexOf( $selection[$i].id ) < 0 ) {
            $backupList.push( $selection[$i] );
            $IDList.push( $selection[$i].id );
        }
    }
    // Add selection to Basket
    $basketTable.bootstrapTable('load', $backupList );
    // Uncheck All from main Table
    $_mainTable.bootstrapTable( 'uncheckAll' );

    // Recalculate Gray / Active and other buttons, basket and ... status
    verifyAndDisableAddButtons();
    verifyAndEnableDoItButton();
    verifyAndEnableEmptyBasketButton();
}

function onClickClearBasket( $basketTable ){
    $basketTable.bootstrapTable('removeAll');
    $('#listsearchTable').bootstrapTable('load', $resultSearch);

    // Recalculate Gray / Active and other buttons, basket and ... status
    verifyAndDisableAddButtons();
    verifyAndEnableDoItButton();
    verifyAndEnableEmptyBasketButton();
}

// Suppress behavior
function operateFormatter( value, row, index ){
    return [
        '<a class="remove" href="javascript:void(0)" title="Supprimer">', '<i class="far fa-times"></i>', '</a>'
    ].join('');
}



$('#divPrint').click(function(){
    var $params = new Array();
    $params['id'] = $('#frm_id').val();
    get(window.JSON_URLS.bs_idp_archive_print_sheet, $params, true );
});

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



// Buttons Export and Export Partial
$('#divExport').click(function( event ){
    event.preventDefault();

    exportArray( $('#listsearchTable'), $_currentFCT, null, null, null, null, -1, false );
});

$('#divExportPartial').click( function( event ){
    event.preventDefault();

    exportArray( $('#listsearchTable'), $_currentFCT, null, null, null, null, -1, true );
});



// ------------------------------------------------------------------------------------------------------------------
// Definitions
// ------------------------------------------------------------------------------------------------------------------
// Basket lists
var $listToReturn = [];
var $IDlistToReturn = [];

// Settings
var $_commonsettings = null;
var $_settings = null;

// Translations
var $_translations = null;
var $_tabletranslation = null;
var $_resultTranslations = null;
var $_overlay = null;
var $_precisionTranslation = null;

// Current page & buttons
var $_currentPage = PAGE_RETURN;
var $_currentButtons = 12;
var $_currentFCT = FCT_RETURN;
var $_mainTable = $('#listsearchTable');

// ------------------------------------------------------------------------------------------------------------------
// Initialization
// ------------------------------------------------------------------------------------------------------------------
$(document).ready(function(){

    $_translations = JSON.parse( window.IDP_CONST.bs_translations );
    $_resultTranslations = JSON.parse( window.IDP_CONST.bs_resulttranslations );
    $_overlay = JSON.parse( window.IDP_CONST.bs_overlay );
    $_searchTranslations = JSON.parse( window.IDP_CONST.bs_searchtranslations );
    $_tabletranslation = JSON.parse( window.IDP_CONST.bs_tabletranslation );
    $_precisionTranslations = JSON.parse( window.IDP_CONST.bs_precisionTranslations );

    $_commonsettings = JSON.parse( window.IDP_CONST.bs_idp_commonsettings );

    // See IDPArchiveSearch.js for details
    initSearch( $_commonsettings );

    // Initialization of Baskets table name, depends on page
    initSideTables();

	// Since confModal is essentially a nested modal it's enforceFocus method
	// must be no-op'd or the following error results
	// "Uncaught RangeError: Maximum call stack size exceeded"
	// But then when the nested modal is hidden we reset modal.enforceFocus
	// Solution from: http://stackoverflow.com/questions/21059598/implementing-jquery-datepicker-in-bootstrap-modal
	var enforceModalFocusFn = $.fn.modal.Constructor.prototype.enforceFocus;
	$.fn.modal.Constructor.prototype.enforceFocus = function() {};

    // overlay
    initOverlay( );
    initOverlayLocalization( null );

    // Initialization of dropdow list with Service dependance
    initLists();

	// Activate Datepicker on both Date fields
	$('#frm_limitdatemin').datepicker({'format': 'dd/mm/yyyy'});
	$('#frm_limitdatemax').datepicker({'format': 'dd/mm/yyyy'});

    // Hide unused buttons
    $('#divPrintTags').hide();
    $('#divDeleteUAs').hide();
    $('#divSetUnlimited').hide();
    $('#divUnsetUnlimited').hide();

    // Hide unused tabMenu
    $('#litabsearch').hide();
    $('#litabfilter').hide();
});

// ------------------------------------------------------------------------------------------------------------------
// Basket Addition / Removal Management
// ------------------------------------------------------------------------------------------------------------------
$('#addToReturn').click( function( event ){
    event.preventDefault();
    if( !$( '#addToReturn' ).hasClass( "disabled" ) ){
        onclickAddToBasket( $listToReturn, $IDlistToReturn, $('#table-return') );
        $('#listsearchTable').bootstrapTable('load', $resultSearch );
    }
});

$('#clearReturn').click( function( event ){
    event.preventDefault();
    if( !$('#clearReturn').hasClass("disabled")) {
        $listToReturn = [];
        $IDlistToReturn = [];
        onClickClearBasket( $('#table-return') );
    }
});

// ------------------------------------------------------------------------------------------------------------------
// Basket Events
// ------------------------------------------------------------------------------------------------------------------
window.operateReturnEvents = {
	'click .remove': function( e, value, row, index ){
		$listToReturn.splice( index, 1 );
		$IDlistToReturn.splice( $IDlistToReturn.indexOf( row.id ), 1 );
		$('#listsearchTable').bootstrapTable('load', $resultSearch);
		$('#table-return').bootstrapTable('load', $listToReturn );
		verifyAndEnableDoItButton();
        verifyAndEnableEmptyBasketButton();
	}
};

// ------------------------------------------------------------------------------------------------------------------
// Basket Buttons Management
// ------------------------------------------------------------------------------------------------------------------
function enableAddBasketButton(){
    $('#addToReturn').removeClass('disabled');
}

function updateBtnActionCancelState() {
}

function verifyAndDisableAddButtons() {
	$selections = $('#listsearchTable').bootstrapTable('getSelections');
	if( $selections.length <= 0 ){
		$('#addToReturn').addClass('disabled');
	}
}

function verifyAndEnableDoItButton() {
	if( $listToReturn.length > 0 ){
		$('#btnAskReturn').removeClass( 'disabled' );
	} else {
		$('#btnAskReturn').addClass( 'disabled' );
	}
}

function verifyAndEnableEmptyBasketButton() {
    if( $listToReturn.length > 0 ){
        $('#clearReturn').removeClass( 'disabled' );
    } else {
        $('#clearReturn').addClass( 'disabled' );
    }
}

$('#btnAskReturn').click(function( event ){
    event.preventDefault();

    if( $listToReturn.length > 0 ) {
        $archiveids = {
            return: $.map($listToReturn, function ($row) {
                return $row.id;
            })
        };
        $('#form_ids').val(JSON.stringify($archiveids));

        $('#actionModalDialog').modal('show');
    }
});

// ------------------------------------------------------------------------------------------------------------------
// Main Table design
// ------------------------------------------------------------------------------------------------------------------
function insideStateFormatter( value, row, index ){
	$inActionList = $IDlistToReturn.indexOf( row['id'] ) + 1;

	return ( $inActionList > 0 )
}

function insideRowStyle( row, index ){
	$inActionList = $IDlistToReturn.indexOf( row['id'] ) + 1;

	return ( $inActionList > 0 )
}

// ------------------------------------------------------------------------------------------------------------------
// Main Table Special functions
// ------------------------------------------------------------------------------------------------------------------
function postQueryParams( params ) {
    return params;
}

// ------------------------------------------------------------------------------------------------------------------
// Basket Table initialization
// ------------------------------------------------------------------------------------------------------------------
function initSideTables(){
    $('#table-return').bootstrapTable({
        data: $listToReturn,
        sortName: "name",
        sortOrder: "asc",
        pagination: false,
        undefinedText: "aucune archive",
        showHeader: false,
        height: 100,
        columns: [
            { field: 'service', title: 'Service', sortable: true, visible: false },
            { field: 'ordernumber', title: 'N° d\'ordre', sortable: true, visible: true },
            { field: 'legalentity', title: 'Entité légale', sortable: true, visible: false },
            { field: 'name', title: $_translations[10], sortable: true, visible: false },
            { field: 'id', title: 'ID', visible: false },
            { field: 'documentnature', title: 'Nature de document', visible: false },
            { field: 'documenttype', title: 'Type de document', visible: false },
            { field: 'description1', title: 'Descriptif 1', visible: false },
            { field: 'description2', title: 'Descriptif 2', visible: false },
            { field: 'budgetcode', title: 'Code budgétaire', visible: false },
            { field: 'documentnumber', title: 'N° document', visible: false },
            { field: 'boxnumber', title: 'N° de boîte', visible: false },
            { field: 'containernumber', title: 'N° conteneur', visible: false },
            { field: 'provider', title: 'Prestataire', visible: false },
            { field: 'limitdatemin', title: 'Date min', visible: false },
            { field: 'limitdatemax', title: 'datemax', visible: false },
            { field: 'limitnummin', title: 'Num. min', visible: false },
            { field: 'limitnummax', title: 'Num. max', visible: false },
            { field: 'limitalphamin', title: 'Alpha. min', visible: false },
            { field: 'limitalphamax', title: 'Alpha max', visible: false },
            { field: 'limitalphanummin', title: 'Alphanum. min', visible: false },
            { field: 'limitalphanummax', title: 'Alphanum. max', visible: false },
            { field: 'operate', formatter: 'operateFormatter', events: 'operateReturnEvents', title: 'Action', align: 'center', width: '30' }
        ]
    });
}
