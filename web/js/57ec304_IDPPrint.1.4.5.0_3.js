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