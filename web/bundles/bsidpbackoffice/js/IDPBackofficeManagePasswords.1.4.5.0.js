
$(document).ready(function(){
    // Init values
    $_password_global_settings = JSON.parse( window.IDP_CONST.password_global_settings );
    $('#passwordMinLength').val( $_password_global_settings['PASSWORD_MIN_LENGTH'] );
    if( $_password_global_settings['PASSWORD_COMPLEXITY'] & PWD_CPXTY_CHARS_LOWER )
        $('#pwdcpxt_lower').attr('checked', true);
    if( $_password_global_settings['PASSWORD_COMPLEXITY'] & PWD_CPXTY_CHARS_UPPER )
        $('#pwdcpxt_upper').attr('checked', true);
    if( $_password_global_settings['PASSWORD_COMPLEXITY'] & PWD_CPXTY_CHARS_SPECIAL )
        $('#pwdcpxt_special').attr('checked', true);
    if( $_password_global_settings['PASSWORD_COMPLEXITY'] & PWD_CPXTY_NUMBERS )
        $('#pwdcpxt_number').attr('checked', true);

});

$('#passwordMinLength').blur( function( event ){
    event.preventDefault();
    verifyAll();
});
$('#pwdcpxt_lower').change( function( event ){
    event.preventDefault();
    verifyAll();
});
$('#pwdcpxt_upper').change( function( event ){
    event.preventDefault();
    verifyAll();
});
$('#pwdcpxt_special').change( function( event ){
    event.preventDefault();
    verifyAll();
});
$('#pwdcpxt_number').change( function( event ){
    event.preventDefault();
    verifyAll();
});

$('#btnApply').click( function( event ){
    event.preventDefault();
    if( verifyAll() ){
        $('#waitAjax').show();
        $.ajax({
            type: "GET",
            url: window.JSON_URLS.bs_idp_backoffice_update_globalsettings_passwords,
            data: {
                'password_min_length': $('#passwordMinLength').val().trim(),
                'password_complexity': calculatePasswordComplexity()
            },
            cache: false,
            success: function ( ){
                $('#waitAjax').hide();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $('#waitAjax').hide();
                bootbox.alert( {
                    message: 'Une erreur système est survenue, merci de réessayer ultérieurement !',
                    className: "boxSysErrorOne"
                } );
            }
        });
    }
});

function verifyAll( ){
    $validation = verifyPasswordComplexityEntry() & verifyPasswordMinLengthEntry();
    if( $validation ){
        $('#btnApply').addClass( 'btnlike' );
        $('#btnApply').removeClass( 'btnlikedisabled' );
        $('#btnApply').addClass( 'btn-primary' );
        $('#btnApply').removeClass( 'btn-default' );
    } else {
        $('#btnApply').removeClass( 'btnlike' );
        $('#btnApply').addClass( 'btnlikedisabled' );
        $('#btnApply').addClass( 'btn-default' );
        $('#btnApply').removeClass( 'btn-primary' );
    }
    return $validation;
}

function verifyPasswordMinLengthEntry( ){
    $value = $('#passwordMinLength').val().trim();
    if( isNaN( $value ) ) {
        popError($('#divPasswordMinLength'), "La longueur minimale doit être un nombre !", 'right');
        return false;
    }
    $pwd_min_length = countPasswordComplexityEntry();
    if( $pwd_min_length == 0 )
        $pwd_min_length = 1;
    if( $value < $pwd_min_length ){
        popError( $('#divPasswordMinLength'), "Avec cette complexité, la longueur minimale doit au moins être égale à "+$pwd_min_length, 'right' );
        return false;
    }
    return true;
}

function calculatePasswordComplexity( ){
    $complexity = 0;
    if( $('#pwdcpxt_lower').is(':checked') ) $complexity += PWD_CPXTY_CHARS_LOWER;
    if( $('#pwdcpxt_upper').is(':checked') ) $complexity += PWD_CPXTY_CHARS_UPPER;
    if( $('#pwdcpxt_special').is(':checked') ) $complexity += PWD_CPXTY_CHARS_SPECIAL;
    if( $('#pwdcpxt_number').is(':checked') ) $complexity += PWD_CPXTY_NUMBERS;
    return $complexity;
}
function countPasswordComplexityEntry( ){
    pwd_cpxty_value = $(":checkbox:checked").length;
    return pwd_cpxty_value;
}
function verifyPasswordComplexityEntry( ){
    if( countPasswordComplexityEntry() <= 0 ){
        popError( $('#divPasswordComplexity'), "La complexité doit comporter au moins un critère !", 'right' );
        return false;
    }
    return true;
}