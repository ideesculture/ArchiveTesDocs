// Function to send a post to server
// post('/contact/', {name: 'Johnny Bravo'});
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
};
// Function to send a get to server
// get('/contact/', {name: 'Johnny Bravo'});
function get(path, params, external) {
    var form = document.createElement("form");
    form.setAttribute("method", "get");
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
};

function popError( $ancre, $message, $where ){
    $ancre.popover({trigger:'manual', placement: $where, content: $message });
    $ancre.popover('show').addClass('has-error');
    $ancre.addClass( 'has-error' );
    $('body').click(function(){
        $ancre.popover('destroy');
        $ancre.removeClass( 'has-error' );
    });
}

