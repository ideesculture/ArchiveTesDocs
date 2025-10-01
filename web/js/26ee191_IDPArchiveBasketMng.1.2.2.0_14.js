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

