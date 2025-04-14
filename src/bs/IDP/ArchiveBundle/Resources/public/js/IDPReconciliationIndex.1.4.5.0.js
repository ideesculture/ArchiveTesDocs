Dropzone.autoDiscover = false;

$(document).ready( function(){
    $('#dropzone').hide();
    $.ajax({
        type: "POST",
        url: window.JSON_URLS.bs_idp_backoffice_get_localizations_list,
        data: null,
        cache: false,
        success: function( $response ) {
            var $partialLocalizations = "";
            var $localizationList = $response;

            var $bFirst = true;
            $localizationList.forEach(function($localizationLine) {
                var $selected = "";
                if( $bFirst ){
                    $selected = "selected='selected'";
                    $bFirst = false;
                }
                $partialLocalizations += "<option value='" + $localizationLine['id'] + "' " + $selected + ">" + $localizationLine['longname'] + "</option> ";

            });

            $("#selectLocalization").html($partialLocalizations);

            $('#dropzone').show();

        },
        error: function (xhr, ajaxOptions, thrownError) {
            bootbox.alert( {
                message: "Une erreur serveur est survenue !",
                className: "boxSysErrorOne"
            } );
        }
    });
});

var reconciliation_dropzone = new Dropzone(".dropzone", {
    url: window.JSON_URLS.bs_idp_archive_reconciliation_upload,
    dictDefaultMessage: 'Posez votre fichier ici ou cliquez pour ouvrir la fenêtre dialogue.',
    dictFallbackMessage: 'Votre navigateur n\'est pas compatible avec cette fonctionnalité!',
    dictFileTooBig: 'Ce fichier est trop volumineux (taille maximum autorisée: {{maxFilesize}} Mo) !',
    dictInvalidFileType: 'Ce type de fichier n\'est pas autorisé ! Seuls les fichiers CSV sont acceptés.',
    dictResponseError: 'Le serveur a rencontré une erreur {{statusCode}}.',
    maxFiles: 1,
    acceptedFiles: '.csv',
    parallelUploads: 1,
    maxFilesize: 2,  // in Mb
    //    chunking: true,
    //    forceChunking: true,
    //    chunkSize: 2000000, // in b
    init: function () {
        this.on("sending", function (file, xhr, formData) {
            // send additional data with the file as POST data if needed.
            // formData.append("key", "value");
            $dataStr = "status=BEGIN";
            $.ajax({
                type: "GET",
                url: window.JSON_URLS.bs_idp_archive_reconciliation_setstatus,
                data: $dataStr,
                cache: false,
                success: function ($response) {
                    $('.dropzone').prop('disabled', true);
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    // $message = xhr.responseJSON;
                    // alert( "Erreur Serveur" );
                }
            });
            formData.append("localization", $('#selectLocalization').val());
        });
        this.on("success", function (file, response) {
            // end of upload auto-redirect to status page
            window.location.reload(false);
        });
        this.on("error", function (file, response) {
            // TODO: send Ajax to server to stop reconciliation and show error to user
            bootbox.alert({
                message: response.message,
                className: "boxErrorOne",
                callback: function () {
                    window.location.reload(false);
                }
            });
        });
    }
});
