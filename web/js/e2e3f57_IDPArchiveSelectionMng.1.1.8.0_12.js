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
