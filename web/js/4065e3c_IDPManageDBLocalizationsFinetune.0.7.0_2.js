var localizations_id = window.IDP_DATA.idp_localizations_id;
var $_translations = null;

$(document).ready(function(){
    $_translations = JSON.parse( window.IDP_CONST.bs_translations );
});

$('#LogoForm').on("submit",function(event) {
    event.preventDefault();

    // var LogoForm = $("#LogoForm")[0];
    var LogoForm = document.forms.namedItem("logoform");

    // Update button text.
    $("#waitAjax").show();
    $('#sendLogoBtn').html( 'Téléchargement en cours...' );
    $('#sendLogoBtn').prop('disabled', true);

    $url =  window.ARCHIVIST_JSON_URLS.bs_idp_archivist_json_localizations_change_logo;

    $formData = new FormData(LogoForm);

    $.ajax({
        type: 'POST',
        url: $url,
        data: $formData,
        dataType: 'json',
        cache: false,
        processData: false, // Don't process the files
        contentType: false, // Set content type to false as jQuery will tell the server its a query string request
        success: function (data, textStatus, jqXHR) {

            // Change logo in viewDiv
            var logoFilename = data.filename;
            var logoDivHTML = '<img class="logo" src="/img/providers/' + logoFilename + '" />';
            $('#LogoDiv').html( logoDivHTML );

            $('#logoInputFile').val("");
            $('#sendLogoBtn').html( 'Envoyer le logo' );
            $('#sendLogoBtn').prop('disabled', false );
            $("#waitAjax").hide();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Une erreur est survenue lors du téléchargement du fichier ! ');
            $('#logoInputFile').val("");
            $('#sendLogoBtn').html( 'Envoyer le logo' );
            $('#sendLogoBtn').prop('disabled', false);
            $("#waitAjax").hide();
        }
    });

});
