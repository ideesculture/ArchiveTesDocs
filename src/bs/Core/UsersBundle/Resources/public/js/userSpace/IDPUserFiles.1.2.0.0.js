
// Function activated on each delete button
$('td.action_delete a').on('click', function(event){
   event.preventDefault();

   let $element = $(event.currentTarget);
   let $fileId = $element.data('id');
   let $filename = $element.data('filename');

    bootbox.dialog({
        message: "Etes-vous sûr de vouloir supprimer le fichier '"+$filename+"'",
        title: 'Suppression fichier',
        className: "boxQuestionTwo",
        closeButton: false,
        buttons: {
            "Oui": { label: "Supprimer", className: "btn-primary", callback:
                    function() {
                        // Activate wait Screen
                        $('#waitAjax').show();

                        $.ajax({
                            type: "GET",
                            url: window.JSON_URLS.bs_core_userspace_userfile_deletefile,
                            data: { fileid: parseInt($fileId) },
                            cache: false,
                            success: function( data, status ){
                                document.location.reload(true);
                                $("#waitAjax").hide();
                            },
                            error: function( xhr, ajaxOptions, throwError ){
                                bootbox.alert( {
                                    message: "Une erreur serveur est survenue. <br>["+xhr.status+"] - "+xhr.responseJSON.message,
                                    className: "boxSysErrorOne"
                                } );
                                $("#waitAjax").hide();
                            }
                        });
                    }},
            "Non": { label: "Annuler", className: "btn-default", callback: function() { } }
        }
    });
});

