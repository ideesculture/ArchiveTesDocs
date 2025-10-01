
// COMMON SETTINGS Constants

var COMMON_SERVICE_SETTINGS_BUDGET_CODE         = 1;
var COMMON_SERVICE_SETTINGS_DOCUMENT_NATURE     = 2;
var COMMON_SERVICE_SETTINGS_DOCUMENT_TYPE       = 3;
var COMMON_SERVICE_SETTINGS_DESCRIPTION_1       = 4;
var COMMON_SERVICE_SETTINGS_DESCRIPTION_2       = 5;
var COMMON_SERVICE_SETTINGS_DOCUMENT_NUMBER     = 6;
var COMMON_SERVICE_SETTINGS_BOX_NUMBER          = 7;
var COMMON_SERVICE_SETTINGS_CONTAINER_NUMBER    = 8;
var COMMON_SERVICE_SETTINGS_PROVIDER            = 9;
var COMMON_SERVICE_SETTINGS_LIMITS_DATE         = 10;
var COMMON_SERVICE_SETTINGS_LIMITS_NUM          = 11;
var COMMON_SERVICE_SETTINGS_LIMITS_ALPHA        = 12;
var COMMON_SERVICE_SETTINGS_LIMITS_ALPHANUM     = 13;

// PAGEs Constants

var PAGE_TRANSFER                           = 1;
var PAGE_CONSULT                            = 2;
var PAGE_RETURN                             = 3;
var PAGE_EXIT                               = 4;
var PAGE_DELETE                             = 5;
var PAGE_RELOC                              = 6;
var PAGE_VALID_TRANSFER_PROVIDER            = 7;
var PAGE_VALID_TRANSFER_INTERMEDIATE        = 8;
var PAGE_VALID_TRANSFER_INTERNAL            = 9;
var PAGE_VALID_DELIVER_WITHOUT_PREPARATION  = 10;
var PAGE_VALID_DELIVER_WITH_PREPARATION     = 11;
var PAGE_VALID_RETURN                       = 12;
var PAGE_VALID_EXIT                         = 13;
var PAGE_VALID_DELETE                       = 14;
var PAGE_VALID_RELOC_PROVIDER               = 15;
var PAGE_VALID_RELOC_INTERMEDIATE           = 16;
var PAGE_VALID_RELOC_INTERNAL               = 17;
var PAGE_MANAGE_TRANSFER                    = 18;
var PAGE_MANAGE_DELIVER                     = 19;
var PAGE_MANAGE_RETURN                      = 20;
var PAGE_MANAGE_EXIT                        = 21;
var PAGE_MANAGE_DELETE                      = 22;
var PAGE_MANAGE_RELOC                       = 23;
var PAGE_CLOSE_TRANSFER_PROVIDER            = 24;
var PAGE_CLOSE_TRANSFER_INTERMEDIATE        = 25;
var PAGE_CLOSE_TRANSFER_INTERNAL            = 26;
var PAGE_CLOSE_DELIVER_WITHOUT_PREPARATION  = 27;
var PAGE_CLOSE_DELIVER_WITH_PREPARATION     = 28;
var PAGE_CLOSE_RETURN                       = 29;
var PAGE_CLOSE_EXIT                         = 30;
var PAGE_CLOSE_DELETE                       = 31;
var PAGE_CLOSE_RELOC_PROVIDER               = 32;
var PAGE_CLOSE_RELOC_INTERMEDIATE           = 33;
var PAGE_CLOSE_RELOC_INTERNAL               = 34;
var PAGE_UNLIMITED                          = 35;
var PAGE_BDD_ENTRY_SERVICES                 = 36;
var PAGE_BDD_ENTRY_LEGAL_ENTITIES           = 37;
var PAGE_BDD_ENTRY_BUDGET_CODES             = 38;
var PAGE_BDD_ENTRY_ACTIVITIES               = 39;
var PAGE_BDD_ENTRY_DOCUMENT_TYPES           = 40;
var PAGE_BDD_ENTRY_DESCRIPTIONS_1           = 41;
var PAGE_BDD_ENTRY_DESCRIPTIONS_2           = 42;
var PAGE_BDD_ENTRY_ADRESSES                 = 43;
var PAGE_BDD_ENTRY_LOCALIZATIONS            = 44;
var PAGE_BDD_USERS                          = 45;
var PAGE_BDD_PROVIDERS                      = 46;

// FIELDs Constants

var FIELD_SERVICE                 = 1;
var FIELD_ORDER_NUMBER            = 2;
var FIELD_LEGAL_ENTITY            = 3;
var FIELD_NAME                    = 4;
var FIELD_BUDGET_CODE             = 5;
var FIELD_DOCUMENT_NATURE         = 6;
var FIELD_DOCUMENT_TYPE           = 7;
var FIELD_DESCRIPTION_1           = 8;
var FIELD_DESCRIPTION_2           = 9;
var FIELD_DOCUMENT_NUMBER         = 10;
var FIELD_BOX_NUMBER              = 11;
var FIELD_CONTAINER_NUMBER        = 12;
var FIELD_PROVIDER                = 13;
var FIELD_STATUS                  = 14;
var FIELD_ID                      = 15;
var FIELD_ADMINLIST               = 16;
var FIELD_STATUS_CAPS             = 17;
var FIELD_AUTHORIZED              = 18;
var FIELD_LOCALIZATION            = 19;
var FIELD_LOCALIZATION_FREE       = 20;
var FIELD_LIMIT_DATE_MIN          = 21;
var FIELD_LIMIT_DATE_MAX          = 22;
var FIELD_LIMIT_NUM_MIN           = 23;
var FIELD_LIMIT_NUM_MAX           = 24;
var FIELD_LIMIT_ALPHA_MIN         = 25;
var FIELD_LIMIT_ALPHA_MAX         = 26;
var FIELD_LIMIT_ALPHANUM_MIN      = 27;
var FIELD_LIMIT_ALPHANUM_MAX      = 28;
var FIELD_CLOSURE_YEAR            = 29;
var FIELD_DESTRUCTION_YEAR        = 30;
var FIELD_STATUS_CODE             = 31;
var FIELD_MODIFIED_AT             = 32;
var FIELD_OLD_LOCALIZATION        = 33;
var FIELD_OLD_LOCALIZATION_FREE   = 34;
var FIELD_PROVIDER_ID             = 35;
var FIELD_PRECISION_DATE          = 36;
var FIELD_PRECISION_ADDRESS       = 37;
var FIELD_PRECISION_FLOOR         = 38;
var FIELD_PRECISION_OFFICE        = 39;
var FIELD_PRECISION_WHO           = 40;
var FIELD_PRECISION_COMMENT       = 41;

var USER_SETTINGS_MODIF_COLUMN_VISIBLE         = 1;
var USER_SETTINGS_MODIF_COLUMN_SORTED          = 2;
var USER_SETTINGS_MODIF_COLUMN_SORT_TYPE_ASC   = 3;

var USER_SETTINGS_MODIF_PAGE_NB_ROW_PER_PAGE = 1;
var USER_SETTINGS_MODIF_PAGE_ARRAY_TYPE_LIST = 2;

// BZ#38 disabling field reloc status
var RELOC_FIELD_DISABLED_STATUS = new Array(
    'CRLPDAI', 'GRLPDAI', 'CRLPDI', 'CONRIDISP',
    'CRLPDAINT', 'GRLPDAINT', 'CRLPDINT', 'CONRINTDISP',
    'CRLPCAI', 'CRLPCI', 'CONRICONP',
    'CRLPCAINT', 'CRLPCINT', 'CONRINTCONP',
    'CRAPCONRIDISP', 'GRAPCONRIDISP', 'CRTPCONRIDISP',
    'CRAPCONRINTDISP', 'GRAPCONRINTDISP', 'CRTPCONRINTDISP',
    'CRAPCONRICONP', 'GRAPCONRICONP', 'CRTPCONRICONP',
    'CRAPCONRINTCONP', 'GRAPCONRINTCONP', 'CRTPCONRINTCONP'
);

// Password complexity constants:
var PWD_CPXTY_CHARS_LOWER = 1;
var PWD_CPXTY_CHARS_UPPER = 2;
var PWD_CPXTY_CHARS_SPECIAL = 4;
var PWD_CPXTY_NUMBERS = 8;


var UASTATE_NOTHING = -1;
var UASTATE_MANAGEUSER = 0;
var UASTATE_MANAGEPROVIDER = 1;
var UASTATE_MANAGECLOSE = 2;

var UAWHAT_TRANSFER = 0;
var UAWHAT_CONSULT = 1;
var UAWHAT_RETURN = 2;
var UAWHAT_EXIT = 3;
var UAWHAT_DESTROY = 4;
var UAWHAT_RELOC = 5;

var UAWHERE_PROVIDER = 0;
var UAWHERE_INTERMEDIATE = 1;
var UAWHERE_INTERNAL = 2;

var UAWHERE_TRANSFER = 0;
var UAWHERE_CONSULT = 1;

var UAWITH_CONTAINER = 0;
var UAWITH_BOX = 1;
var UAWITH_FILE = 2;
var UAWITH_NOTHING = 3;

var UAHOW_WITHOUTPREPARE = 0;
var UAHOW_WITHPREPARE = 1;

// FCT Constant for Export
var FCT_TRANSFER                = 1;
var FCT_CONSULT                 = 2;
var FCT_RETURN                  = 3;
var FCT_EXIT                    = 4;
var FCT_DELETE                  = 5;
var FCT_RELOC                   = 28;

var FCT_ARCHIVIST               = 6;
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
var $_password_global_settings = null;

$(document).ready(function() {
    $_password_global_settings = JSON.parse(window.IDP_CONST.password_global_settings);
});

$('#inputChangeMdpNew').keyup( function( event ){
    $passwordVerificationStatus = verifyPassword($('#inputChangeMdpNew').val().trim(), $_password_global_settings );

    $errormsg = getErrorMessage( $passwordVerificationStatus );
    if( $errormsg != null ) {
        showErrorMessage( $('#inputChangeMdpNew'), $errormsg );
    } else {
        destroyErrorMessage( $('#inputChangeMdpNew') );
    }
});

$('#btnValidateChangeMdp').click( function( event ){
    $passwordVerificationStatus = verifyPassword($('#inputChangeMdpNew').val().trim(), $_password_global_settings );
    if( $passwordVerificationStatus != 0 ){
        bootbox.alert( {
            message: getErrorMessage( $passwordVerificationStatus ),
            className: "boxSysErrorOne"
        } );
        return;
    }
    if( $('#inputChangeMdpNew').val == '' ) {
        bootbox.alert( {
            message: 'Le nouveau mot de passe ne peut pas être vide !',
            className: "boxErrorOne"
        });
        return;
    }
    if( $('#inputChangeMdpNew').val() != $('#inputChangeMdpConfirm').val() ){
        bootbox.alert( {
            message: 'La confirmation est différente du nouveau mot de passe !',
            className: "boxErrorOne"
        });
        return;
    }

    $dataObj = {
        'cuid' : window.IDP_CONST.cuid,
        'oldpwd' : $('#inputChangeMdpOld').val(),
        'newpwd' : $('#inputChangeMdpNew').val()
    };

    get( window.JSON_URLS.bs_core_user_change_mdp, $dataObj, false );
});

$('#btnCancelChangeMdp').click( function( event ){
    // Return to home
    window.location.href = window.JSON_URLS.bs_idp_dashboard_homepage;
});