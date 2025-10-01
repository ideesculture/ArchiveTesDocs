var pollTimer;

function pollLatestResponse() {

	/* STATUS
	 const IDP_IMPORT_STATUS_UNKNOWN = 0;
	 const IDP_IMPORT_STATUS_START = 1;
	 const IDP_IMPORT_STATUS_IN_PROGRESS = 2;
	 const IDP_IMPORT_STATUS_END = 10;

	 const IDP_IMPORT_STATUS_ERROR = 50;

	 const IDP_IMPORT_STATUS_CANCEL_IN_PROGRESS = 75;
	 const IDP_IMPORT_STATUS_CANCELED = 76;
	 const IDP_IMPORT_STATUS_DEFINITIVE_VALIDATION = 99;

	 */
	$.get(window.JSON_URLS.bs_idp_archive_ajaximporttreatmentsurvey, { importid: window.IDP_CONST.importid }).done(function (resp) {

		//respArr = json_decode( resp );
		percent = resp['percent'];
		status = resp['status'];
		messages = resp['messages'];
		estimated = resp['estimated'];

		if( percent != null ){
			if (status > 2)
			{
				clearInterval(pollTimer);
				$('#importProgress').css('width', '100%');
				$('#importProgress').html( 'Importation terminée' );
			}
			else
			{
				$('#importProgress').css('width', (parseInt(percent))+'%');
				$('#importProgress').html( (parseInt(percent))+'% : [fin estimée : '+estimated+']' );
			}
			oldText = $('#textPoll').html( );
			$text = '';
			for( $i = 0; $i < messages.length; $i++ )
			$text = $text + '<br/>' + messages[$i];
			$('#textPoll').html( oldText + $text );
		}
	});
}

$(document).ready( function(){

    pollLatestResponse();
	pollTimer = setInterval(pollLatestResponse, 2000);

})