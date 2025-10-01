var pollTimer;

function pollLatestReconciliationStatus() {

    $.get( window.JSON_URLS.bs_idp_archive_reconciliation_getstatus ).done( function( response ){

        if( "data" in response ){
            $data = response['data'];

            if( $data['rec'] == 'OK' ) {
                $('#rec_realfilename').html( $data['rec_realfilename'] );

                switch( $data['status'] ) {
                    case 1:
                        $('#rec_status').html("Analyse en cours");
                        $('#step1').show();

                        $('#rec_datebeginstep1').html( $data['rec_datebeginstep1'] );
                        $('#rec_estimatedendstep1').html( $data['rec_estimatedendstep1'] );
                        $percent = parseInt( $data[ 'rec_percentstep1' ] );
                        $('#rec_percentstep1').css('width', ( $percent ) + '%' );
                        $('#rec_percentstep1').html( 'Analyse en cours: ' + $percent + '% effectué' + ( $percent > 1 ? 's' : '' ) );

                        $percent = parseInt( $data[ 'rec_percentstep2' ] );
                        if( $percent > 0 ) $('#step2').show(); else $('#step2').hide();
                        $('#rec_datebeginstep2').html( $data['rec_datebeginstep2'] );
                        $('#rec_estimatedendstep2').html( $data['rec_estimatedendstep2'] );
                        $('#rec_percentstep2').css('width', ( $percent ) + '%' );
                        $('#rec_percentstep2').html( 'Analyse en cours: ' + $percent + '% effectué' + ( $percent > 1 ? 's' : '' ) );
                        break;

                    case 2:
                        $('#rec_status').html("Création des fichiers résultats en cours");
                        $('#step1').hide();
                        $('#step2').hide();
                        break;

                    case 51:
                        $('#rec_status').html("Copie du fichier à analyser en cours");
                        $('#step1').hide();
                        $('#step2').hide();
                        break;
                    case 52:
                        $('#rec_status').html("Vérification du fichier en cours");
                        $('#step1').hide();
                        $('#step2').hide();
                        break;
                    case 53:
                        $('#rec_status').html("Instantané de la Base de données en cours");
                        $('#step1').hide();
                        $('#step2').hide();
                        break;
                    case 60:
                        $('#rec_status').html("Réinitialisation de la fonction de rapprochement en cours");
                        $('#step1').hide();
                        $('#step2').hide();
                        break;
                    default:
                        window.location.reload(false);
                        break;
                }

            } else {
                alert('Erreur serveur.');
                clearInterval(pollTimer);
            }

        } else {
            alert('Erreur serveur');
            clearInterval(pollTimer);
        }
    });
}

$(document).ready( function(){

    $('#step1').hide();
    $('#step2').hide();

    // Call it first time at launch
    pollLatestReconciliationStatus();
    // Then every 2s
    pollTimer = setInterval( pollLatestReconciliationStatus, 2000 );

});