var PASSWORD_NO_ERROR = 0;
var PASSWORD_ERROR_MIN_LENGTH = -1;
var PASSWORD_ERROR_CHARS_LOWER_NEEDED = -2;
var PASSWORD_ERROR_CHARS_UPPER_NEEDED = -3;
var PASSWORD_ERROR_CHARS_SPECIAL_NEEDED = -4;
var PASSWORD_ERROR_NUMBERS_NEEDED = -5;

var PASSWORD_ERROR_MSG_MIN_LENGTH = 'Le mot de passe est trop court !';
var PASSWORD_ERROR_MSG_CHARS_LOWER = 'Le mot de passe doit contenir au moins une lettre minuscule !';
var PASSWORD_ERROR_MSG_CHARS_UPPER = 'Le mot de passe doit contenir au moins une lettre majuscule !';
var PASSWORD_ERROR_MSG_CHARS_SPECIAL = 'Le mot de passe doit contenir au moins un caractère spécial !';
var PASSWORD_ERROR_MSG_NUMBERS = 'Le mot de passe doit contenir au moins une chiffre !';

var $password_error_msg_on = false;

function verifyPassword( $password, $passwordParameters ){
    $password_min_length = $passwordParameters['PASSWORD_MIN_LENGTH'];
    $password_complexity = $passwordParameters['PASSWORD_COMPLEXITY'];

    $regex_chars_lower = new RegExp( '[a-z]' );
    $regex_chars_upper = new RegExp( '[A-Z]' );
    $regex_chars_special = new RegExp( '[^ \\w]' );
    $regex_numbers = new RegExp( '[0-9]' );

    if( $password.length < $password_min_length )
        return PASSWORD_ERROR_MIN_LENGTH;

    if( $password_complexity & PWD_CPXTY_CHARS_LOWER )
        if( !$regex_chars_lower.test($password) )
            return PASSWORD_ERROR_CHARS_LOWER_NEEDED;

    if( $password_complexity & PWD_CPXTY_CHARS_UPPER )
        if( !$regex_chars_upper.test($password) )
            return PASSWORD_ERROR_CHARS_UPPER_NEEDED;

    if( $password_complexity & PWD_CPXTY_CHARS_SPECIAL )
        if( !$regex_chars_special.test($password) )
            return PASSWORD_ERROR_CHARS_SPECIAL_NEEDED;

    if( $password_complexity & PWD_CPXTY_NUMBERS )
        if( !$regex_numbers.test($password) )
            return PASSWORD_ERROR_NUMBERS_NEEDED;

    return PASSWORD_NO_ERROR;
}

function getErrorMessage( $password_Verification_Status ){
    switch ($password_Verification_Status) {
        case PASSWORD_ERROR_MIN_LENGTH:
            return PASSWORD_ERROR_MSG_MIN_LENGTH;
            break;
        case PASSWORD_ERROR_CHARS_LOWER_NEEDED:
            return PASSWORD_ERROR_MSG_CHARS_LOWER;
            break;
        case PASSWORD_ERROR_CHARS_UPPER_NEEDED:
            return PASSWORD_ERROR_MSG_CHARS_UPPER;
            break;
        case PASSWORD_ERROR_CHARS_SPECIAL_NEEDED:
            return PASSWORD_ERROR_MSG_CHARS_SPECIAL;
            break;
        case PASSWORD_ERROR_NUMBERS_NEEDED:
            return PASSWORD_ERROR_MSG_NUMBERS;
            break;
    }
    return null;
}

function showErrorMessage( $ancre, $errormsg ){
    if ($errormsg != null) {
        if (!$password_error_msg_on) { // Not on screen, so create it
            $password_error_msg_on = true;
            $ancre.popover({
                trigger: 'manual',
                placement: 'right',
                content: $errormsg
            });
            $ancre.popover('show');
            $('body').click(function () {
                if( $password_error_msg_on ) {
                    $password_error_msg_on = false;
                    $ancre.popover('destroy');
                }
            });
        } else {    // Already on screen, so just change the text
            var popover = $ancre.data('bs.popover');
            popover.options.content = $errormsg;
            popover
                .tip()
                .find('.popover-content')
                .html($errormsg);
        }
    }
}
function destroyErrorMessage( $ancre ){
    if( $password_error_msg_on ) {
        $password_error_msg_on = false;
        $ancre.popover('destroy');
    }
}