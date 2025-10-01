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


