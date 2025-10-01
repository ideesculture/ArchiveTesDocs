// Archive Suppression behavior
$('#btnSuppressModalConfirm').click( function(){
    onClickBtnSuppressModalConfirm();
    $('#SuppressModal').modal('hide');
    return true;
});

function onClickBtnSuppressModalConfirm(){
    $dataStr = "id=" + currentArchiveView;
    $.ajax({
        type: "POST",
        url: window.JSON_URLS.bs_idp_archive_delete_ajax,
        data: $dataStr,
        cache: false,
        success: function($response) {
            ids = [$currentArchiveView];
            $('#listsearchTable').bootstrapTable('remove', { field: 'id', values: $ids });
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert( $_translations[41] );
        }
    });

    return true;
}

function getAllWantedIds(){
    // Get selections from DTA list
    var $selection = $('#listsearchTable').bootstrapTable('getSelections');
    // get all id in simple string list, separator is |
    $stringList = '';
    $bFirst = true;
    for( $i=0; $i< $selection.length; $i++ ){
        if( !$bFirst )
            $stringList += '|';
        else
            $bFirst = false;

        $stringList += $selection[$i].id;
    }
    return $stringList;

}

$('#divDeleteUAs').click(function(){
    var $selection = $('#listsearchTable').bootstrapTable('getSelections');
    if( $selection.length <= 0 ){
        bootbox.dialog({
            message: "Vous devez sélectionner une ou plusieurs archives au préalable !",
            title: "Avertissement !",
            className: "boxQuestionTwo",
            closeButton: false,
            buttons: { "OK": { label: "OK", className: "btn-success" } } });
    } else {
        bootbox.dialog({
            message: "Etes-vous sûr(e) de vouloir supprimer ces archives ?",
            title: "Attention !",
            className: "boxQuestionTwo",
            closeButton: false,
            buttons: {
                "Oui": { label: "Oui", className: "btn-success", callback: function() { doDeleteUAs(); } },
                "Non": { label: "Non", className: "btn-danger", }
            } });
    }
});
function doDeleteUAs(){

    var $dataStr = "id=" + getAllWantedIds();
    $.ajax({
        type: "POST",
        url: window.JSON_URLS.bs_idp_archive_delete_ajax,
        data: $dataStr,
        cache: false,
        success: function($response) {
            $('#listsearchTable').bootstrapTable('refresh');
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert( 'Error Ajax' );
        }
    });
}