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
