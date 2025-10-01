function post(path, params, external) {
	var form = document.createElement("form");
	form.setAttribute("method", "post");
	form.setAttribute("action", path);
	if( external )
	form.setAttribute("target", "_blanc" );

	for(var key in params) {
		if(params.hasOwnProperty(key)) {
			var hiddenField = document.createElement("input");
			hiddenField.setAttribute("type", "hidden");
			hiddenField.setAttribute("name", key);
			hiddenField.setAttribute("value", params[key]);

			form.appendChild(hiddenField);
		}
	}
	document.body.appendChild(form);
	form.submit();
}

function makeparams( $filename ){
	var params = new Array();

	params['filename'] = $filename;

	return params;
}

$('.fileBtn').click( function(){
	$params = makeparams( this.id );
	post(window.JSON_URLS.bs_idp_archive_importtreatmentdo, $params, false );
});