var $_translations = null;

$(document).ready(function() {
    $_translations = JSON.parse( window.IDP_CONST.bs_translations );

    $('#sauvegardee').click(function () {
        window.location.href = "{{ path('bs_idp_archive_new') }}";
    });
    $('#validee').click(function () {
        window.location.href = "{{ path('bs_idp_archive_transferscreen') }}";
    });
});

$('#liNbTransfer').on('click', function(e) {
    e.preventDefault();
    showDetailledInformation( 0 );
});
$('#liNbConsult').on('click', function(e) {
    e.preventDefault();
    showDetailledInformation( 1 );
});
$('#liNbReturn').on('click', function(e) {
    e.preventDefault();
    showDetailledInformation( 2 );
});
$('#liNbExit').on('click', function(e) {
    e.preventDefault();
    showDetailledInformation( 3 );
});
$('#liNbDestroy').on('click', function(e) {
    e.preventDefault();
    showDetailledInformation( 4 );
});
$('#liNbReloc').on('click', function(e) {
    e.preventDefault();
    showDetailledInformation( 5 );
});

function showDetailledInformation( $whatInformation ){
    // Hide everything while ajax calling
    $('#waitAjax').show();

    // List of translation correspondance for Main Dashboard
    $CURRENT_OPERATION_TRANSLATION = [
        2, 3, 4, 5, 6, 27
    ];
    $titleNb = $CURRENT_OPERATION_TRANSLATION[$whatInformation];

    // Ajax Call
    $dataStr = "which=" + $whatInformation;
    $.ajax({
        type: "GET",
        url: window.JSON_URLS.bs_idp_archive_detailled_information_ajax,
        data: $dataStr,
        cache: false,
        success: function( $response ) {
            updateDetailledInformationModal( $response, $titleNb );
            $('#waitAjax').hide();
            $('#DetailledInformationModal').modal('show');
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $('#waitAjax').hide();
            bootbox.alert( {
                message: 'Une erreur est survenue lors de la récupération des informations !',
                className: "boxSysErrorOne"
            } );
        }
    });
}

function updateDetailledInformationModal( $data, $titleNb ){
    $formattedDatas = '<table class="table table-striped" id="DetailledInformationTable">'+
        '<tr> <th>N° d\'ordre</th> <th>Libellé</th> </tr> \n';

    if( $data ){
        $data.forEach(function( $archive ) {
            $formattedDatas += '<tr>'+
                '<td>'+$archive['ordernumber']+'</td>'+
                '<td>'+$archive['name']+'</td>';
        });
    }

    $formattedDatas += '</table>';

    $('#DetailledInformationLabel').html( $_translations[$titleNb])
    $('#DetailledInformationContent').html( $formattedDatas );
}