
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
var modify = window.IDP_DATA.modify;
var user_role = window.IDP_DATA.idp_user_role;
var default_services = null;
var default_addresses = null;
var $_translations = null;
var $_password_global_settings = null;

var init_services_page = true;
var init_addresses_page = true;

var BSUFIELD_FIRSTNAME 			  = 0;
var BSUFIELD_NAME                 = 1;
var BSUFIELD_LOGIN                = 2;
var BSUFIELD_PASSWORD             = 3;
var BSUFIELD_INITIAL              = 4;
var BSUFIELD_ROLE                 = 5;
var BSUFIELD_SERVICES             = 6;
var BSUFIELD_ADDRESSES            = 7;

var isValidFormField_FirstName = false;
var isValidFormField_LastName = false;
var isValidFormField_Login = false;
var isValidFormField_Password = false;
var isValidFormField_Initials = false;
var isValidFormField_Role = false;
var isValidFormField_Services = false;
var isValidFormField__Adresses = false;

function updateRoleList( data ){

    $roleOptions = "<option value=\"\">"+$_translations[15]+"</option>";
    i = 0;
    $bSelected = false;
    data.forEach(function($roleLine){
        $selected = "";
        if( modify == 'MODIFY' ){
            if( $roleLine['id'] == parseInt( user_role ) ){
                $bSelected = true;
                $selected = " selected='selected' ";
                isValidFormField_Role = true;
            }
        }
        $roleOptions += "<option value=\"" + $roleLine['id'] + "\" data=\"" + i + "\" " + $selected + ">" + $roleLine['description'] + "</option> ";
        i = i+1;
    });
    $('#form_userRole').html( $roleOptions );
    verifyForm( 7 );
}

$(document).ready(function(){
	$_translations = JSON.parse( window.IDP_CONST.bs_translations );
    $_password_global_settings = JSON.parse( window.IDP_CONST.password_global_settings );

	if( modify == 'MODIFY' ){
		default_services = $('#form_userServices').val().split(',');
		for (index = 0; index < default_services.length; ++index)
			default_services[index] = parseInt(default_services[index]);
		default_addresses = $('#form_userAddresses').val().split(',');
		for( index = 0; index < default_addresses.length; ++index)
		    default_addresses[index] = parseInt(default_addresses[index]);
	}

	$.ajax({
		type: "POST",
		url: window.JSON_URLS.bs_core_admin_get_roles_list,
		data: null,
		cache: false,
		success: updateRoleList,
		error: function (xhr, ajaxOptions, thrownError) {
			alert( $_translations[16] );
		}
	});

	// Initialize Initials behavior
    if( modify == 'MODIFY' ){
        isValidFormField_Initials = true;
        $('#btnGenerateInitials').hide();
        $('#btnVerifyInitials').show();
    } else {
        $('#btnGenerateInitials').show();
        $('#btnVerifyInitials').hide();
    }
    verifyForm( ( modify != 'MODIFY' )?0:1 );

    if( modify == 'MODIFY' ) {
        verifyLoginUnicity();
        if( $('#form_userInitials').val().trim().length <= 0 )
            $('#btnVerifyInitials').addClass('disabled');
        else
            $('#btnVerifyInitials').removeClass('disabled');
    }
});

function verifyForm( $calledFrom ){
    // First unset everything
    if( $calledFrom == 0 || $calledFrom == 1 ) { // i.e. Initialisation
        $('#btnGenerateVerifyInitials').addClass('disabled');
        $('#form_userInitials').prop('readonly',true);
        $('#form_addUserBtn').prop('disabled', true);
        $('#frm_userFirstname').removeClass('has-success');
        $('#frm_userFirstname').removeClass('has-error');
        $('#iconFrmFirstname').removeClass('fa-check');
        $('#iconFrmFirstname').removeClass('fa-exclamation-triangle');
        $('#frm_userLastname').removeClass('has-success');
        $('#frm_userLastname').removeClass('has-error');
        $('#iconFrmLastname').removeClass('fa-check');
        $('#iconFrmLastname').removeClass('fa-exclamation-triangle');
        $('#frm_userLogin').removeClass('has-success');
        $('#frm_userLogin').removeClass('has-error');
        $('#iconFrmLogin').removeClass('fa-check');
        $('#iconFrmLogin').removeClass('fa-exclamation-triangle');
        $('#frm_userPassword').removeClass('has-success');
        $('#frm_userPassword').removeClass('has-error');
        $('#iconFrmPassword').removeClass('fa-check');
        $('#iconFrmPassword').removeClass('fa-exclamation-triangle');
        $('#frm_userInitials').removeClass('has-success');
        $('#frm_userInitials').removeClass('has-error');
        $('#iconFrmInitials').removeClass('fa-check');
        $('#iconFrmInitials').removeClass('fa-exclamation-triangle');
        $('#frm_userRole').removeClass('has-success');
        $('#frm_userRole').removeClass('has-error');
        $('#iconFrmRole').removeClass('fa-check');
        $('#iconFrmRole').removeClass('fa-exclamation-triangle');
        $('#navtabServices').removeClass('fa-check');
        $('#navtabServices').removeClass('fa-exclamation-triangle');
        $('#navtabAdresses').removeClass('fa-check');
        $('#navtabAdresses').removeClass('fa-exclamation-triangle');
    }

    // Compute field validation
    isValidFormField_FirstName = ($('#form_userFirstname').val()!=null?$('#form_userFirstname').val().trim().length > 0:false);
    isValidFormField_LastName = ($('#form_userLastname').val()!=null?$('#form_userLastname').val().trim().length > 0:false);
    // Login is only computed during onChange, because of server validation process time
    $passwordVerificationStatus = verifyPassword($('#form_userPassword').val().trim(), $_password_global_settings );
    isValidFormField_Password = false;
    if( modify == 'MODIFY') {
        if ($('#form_userPassword').val().trim().length == 0)  // In modification no password = no modification, so it is not an error
            isValidFormField_Password = true;
        else
            isValidFormField_Password = ($passwordVerificationStatus == 0);
    } else
        isValidFormField_Password = ($passwordVerificationStatus == 0);
    isValidFormField_Role = ($('#form_userRole').val()!=null?$('#form_userRole').val().trim().length > 0:false);
    isValidFormField_Services = $('#ServicesListTable').bootstrapTable('getSelections').length > 0;
    isValidFormField__Adresses = $('#AddressesListTable').bootstrapTable('getSelections').length > 0;

    // Button Generate Initials verification (only in creation mode)
    if( modify != 'MODIFY' ) {
        if (isValidFormField_FirstName && isValidFormField_LastName)
            $('#btnGenerateInitials').removeClass('disabled');
        else
            $('#btnGenerateInitials').addClass('disabled');
    }

    // Button AddUser verification
    if( isValidFormField_FirstName && isValidFormField_LastName && isValidFormField_Login && isValidFormField_Password &&
        isValidFormField_Role && isValidFormField_Initials && isValidFormField_Services && isValidFormField__Adresses )
        $('#form_addUserBtn').prop('disabled', false);
    else
        $('#form_addUserBtn').prop('disabled', true);

    // Specific Field verification
    switch( $calledFrom ){
        case 0: // Init Add
            $('#frm_userFirstname').addClass('has-error');
            $('#iconFrmFirstname').addClass('fa-exclamation-triangle');
            $('#frm_userLastname').addClass('has-error');
            $('#iconFrmLastname').addClass('fa-exclamation-triangle');
            $('#frm_userLogin').addClass('has-error');
            $('#iconFrmLogin').addClass('fa-exclamation-triangle');
            $('#frm_userPassword').addClass('has-error');
            $('#iconFrmPassword').addClass('fa-exclamation-triangle');
            $('#frm_userInitials').addClass('has-error');
            $('#iconFrmInitials').addClass('fa-exclamation-triangle');
            $('#frm_userRole').addClass('has-error');
            $('#iconFrmRole').addClass('fa-exclamation-triangle');
            $('#navtabServices').addClass('fa-exclamation-triangle');
            $('#navtabAdresses').addClass('fa-exclamation-triangle');
            break;
        case 1: // Init Modify
            setFormFieldValidationInfo( $('#frm_userFirstname'), $('#iconFrmFirstname'), isValidFormField_FirstName );
            setFormFieldValidationInfo( $('#frm_userFirstname'), $('#iconFrmFirstname'), isValidFormField_FirstName );
            setFormFieldValidationInfo( $('#frm_userLastname'), $('#iconFrmLastname'), isValidFormField_LastName );
            setFormFieldValidationInfo( $('#frm_userLogin'), $('#iconFrmLogin'), isValidFormField_Login );
            setFormFieldValidationInfo( $('#frm_userPassword'), $('#iconFrmPassword'), isValidFormField_Password );
            setFormFieldValidationInfo( $('#frm_userRole'), $('#iconFrmRole'), isValidFormField_Role );
            setFormFieldValidationInfo( $('#frm_userInitials'), $('#iconFrmInitials'), isValidFormField_Initials );
            setFormFieldValidationInfo( null, $('#navtabServices'), isValidFormField_Services );
            setFormFieldValidationInfo( null, $('#navtabAdresses'), isValidFormField__Adresses );
            break;
        case 2: // Firstname modified
            setFormFieldValidationInfo( $('#frm_userFirstname'), $('#iconFrmFirstname'), isValidFormField_FirstName );
            break;
        case 3: // Lastname modified
            setFormFieldValidationInfo( $('#frm_userLastname'), $('#iconFrmLastname'), isValidFormField_LastName );
            break;
        case 4: // Login modified
            setFormFieldValidationInfo( $('#frm_userLogin'), $('#iconFrmLogin'), isValidFormField_Login );
            break;
        case 5: // Password modified
            setFormFieldValidationInfo( $('#frm_userPassword'), $('#iconFrmPassword'), isValidFormField_Password );
            $errormsg = getErrorMessage( $passwordVerificationStatus );
            if( !isValidFormField_Password ) {
                showErrorMessage( $('#frm_userPassword'), $errormsg );
            } else {
                destroyErrorMessage( $('#frm_userPassword') );
            }
            break;
        case 6: // Initials modified
            setFormFieldValidationInfo( $('#frm_userInitials'), $('#iconFrmInitials'), isValidFormField_Initials );
            break;
        case 7: // Role modified
            setFormFieldValidationInfo( $('#frm_userRole'), $('#iconFrmRole'), isValidFormField_Role );
            break;
        case 8: // Tab Services
            setFormFieldValidationInfo( null, $('#navtabServices'), isValidFormField_Services );
            break;
        case 9: // Tab Adresses
            setFormFieldValidationInfo( null, $('#navtabAdresses'), isValidFormField__Adresses );
            break;
    }
}

function setFormFieldValidationInfo( divField, iconField, valid ){
    if( divField != null ) {
        divField.removeClass('has-success');
        divField.removeClass('has-error');
    }
    if( iconField != null ) {
        iconField.removeClass('fa-check formOK');
        iconField.removeClass('fa-exclamation-triangle formNOK');
    }
    if( valid ) {
        if( divField != null ) divField.addClass('has-success');
        if( iconField != null ) iconField.addClass('fa-check formOK');
    } else {
        if( divField != null ) divField.addClass('has-error');
        if( iconField != null ) iconField.addClass('fa-exclamation-triangle formNOK');
    }
}
function dump(obj) {
    var out = '';
    for (var i in obj) {
        out += i + ": " + obj[i] + "\n";
    }
    alert(out);
}

$(function(){
	$('#ServicesListTable')
	.on('check.bs.table', function( e, row ){
        if( modify == 'MODIFY' ) {
            if( !init_services_page ) {
                ajax_updatefield(BSUFIELD_SERVICES, row.id, 1);
            }
        } else {
            setServicesSelected($('#ServicesListTable').bootstrapTable('getSelections'));
        }
        verifyForm( 8 );
	})
	.on('uncheck.bs.table', function( e, row ){
        if( modify == 'MODIFY' ) {
            if( !init_services_page ) {
                ajax_updatefield(BSUFIELD_SERVICES, row.id, 0);
            }
        } else {
            setServicesSelected($('#ServicesListTable').bootstrapTable('getSelections'));
        }
        verifyForm( 8 );
	})
	.on('check-all.bs.table', function( e ){
        if( modify == 'MODIFY' ) {
            if( !init_services_page ) {
                $allServicesList = $('#ServicesListTable').bootstrapTable('getSelections');
                $allServicesList.forEach(function($serviceLine){
                    ajax_updatefield(BSUFIELD_SERVICES, $serviceLine["id"], 1);
                });
            }
        } else {
            setServicesSelected($('#ServicesListTable').bootstrapTable('getSelections'));
        }
        verifyForm( 8 );
	})
	.on('uncheck-all.bs.table', function( e ){
        if( modify == 'MODIFY' ) {
            if( !init_services_page ) {
                $allServicesList = $('#ServicesListTable').bootstrapTable('getData');
                $allServicesList.forEach(function($serviceLine){
                    ajax_updatefield(BSUFIELD_SERVICES, $serviceLine["id"], 0);
                });
            }
        } else {
            setServicesSelected($('#ServicesListTable').bootstrapTable('getSelections'));
        }
        verifyForm( 8 );
	})
	.on('load-success.bs.table', function( e, data ){
		selectServices( data );
        init_services_page = false;
        verifyForm( 8 );
	})
});

$(function(){
    $('#AddressesListTable')
        .on('check.bs.table', function( e, row ){
            if( modify == 'MODIFY' ) {
                if( !init_addresses_page ) {
                    ajax_updatefield(BSUFIELD_ADDRESSES, row.id, 1);
                }
            } else {
                setAddressesSelected($('#AddressesListTable').bootstrapTable('getSelections'));
            }
            verifyForm( 9 );
        })
        .on('uncheck.bs.table', function( e, row ){
            if( modify == 'MODIFY' ) {
                if( !init_addresses_page ) {
                    ajax_updatefield(BSUFIELD_ADDRESSES, row.id, 0);
                }
            } else {
                setAddressesSelected($('#AddressesListTable').bootstrapTable('getSelections'));
            }
            verifyForm( 9 );
        })
        .on('check-all.bs.table', function( e ){
            if( modify == 'MODIFY' ) {
                if( !init_addresses_page ) {
                    $allAddressesList = $('#AddressesListTable').bootstrapTable('getSelections');
                    $allAddressesList.forEach(function($addressLine){
                        ajax_updatefield(BSUFIELD_ADDRESSES, $addressLine["id"], 1);
                    });
                }
            } else {
                setAddressesSelected($('#AddressesListTable').bootstrapTable('getSelections'));
            }
            verifyForm( 9 );
        })
        .on('uncheck-all.bs.table', function( e ){
            if( modify == 'MODIFY' ) {
                if( !init_addresses_page ) {
                    $allAddressesList = $('#AddressesListTable').bootstrapTable('getData');
                    $allAddressesList.forEach(function($addressLine){
                        ajax_updatefield(BSUFIELD_ADDRESSES, $addressLine["id"], 0);
                    });
                }
            } else {
                setAddressesSelected($('#AddressesListTable').bootstrapTable('getSelections'));
            }
            verifyForm( 9 );
        })
        .on('load-success.bs.table', function( e, data ){
            selectAddresses( data );
            init_addresses_page = false;
            verifyForm( 9 );
        })
});

function setServicesSelected( $ServicesList ){
	$str = "";
	$first = true;
	$ServicesList.forEach(function($serviceLine){
		if( !$first )
			$str += ",";
		else
			$first = false;
		$str += $serviceLine["id"];
	});
	$('#form_userServices').val($str);
}

function selectServices( $data ){
	if( default_services == null )
		return true;
	for (index = 0; index < $data.length; ++index) {
		$serviceIdCurrent = $data[index]['id'];
		$result = default_services.indexOf( parseInt( $serviceIdCurrent ));
		if( $result >= 0 ){
			$('#ServicesListTable').bootstrapTable('check', index );
		}
	}
}

function setAddressesSelected( $AddressesList ){
    $str = "";
    $first = true;
    $AddressesList.forEach(function($addressLine){
        if( !$first )
            $str += ",";
        else
            $first = false;
        $str += $addressLine["id"];
    });
    $('#form_userAddresses').val($str);
}

function selectAddresses( $data ){
    if( default_addresses == null )
        return true;
    for (index = 0; index < $data.length; ++index) {
        $addressIdCurrent = $data[index]['id'];
        $result = default_addresses.indexOf( parseInt( $addressIdCurrent ));
        if( $result >= 0 ){
            $('#AddressesListTable').bootstrapTable('check', index );
        }
    }
}

function ajax_updatefield( $field, $value, $param ){
    $dataObj = {
        'bsuid': window.IDP_CONST.bsuser_id,
        'bsufield': $field,
        'value': $value,
		'param' : $param
    }
    $.ajax({
        type: "GET",
        url: window.JSON_URLS.bs_core_admin_json_updatefield_user,
        data: $dataObj,
        cache: false,
        success: function( ){
        },
        error: function (xhr, ajaxOptions, thrownError) {
            bootbox.alert({
                message: "Une erreur serveur est survenue, merci de réessayer ultérieurement ! [user="+window.IDP_CONST.bsuser_id+" field="+$field+" with value="+$value+" and param="+$param+" thrownError="+throwError+"]",
                className: "boxSysErrorOne" });
        }
    });
}

$('#form_userFirstname').keyup(function( event ){
    event.preventDefault();
    verifyForm( 2 );
});
$('#form_userFirstname').change(function ( event ) {
    // ajax new value to update user only if in modify mode
    event.preventDefault();
    if( modify == 'MODIFY' )
        ajax_updatefield(BSUFIELD_FIRSTNAME, $("#form_userFirstname").val(), null);
});
$('#form_userLastname').keyup(function( event ){
    event.preventDefault();
    verifyForm( 3 );
});
$('#form_userLastname').change(function ( event ) {
    event.preventDefault();
    if( modify == 'MODIFY' )
        ajax_updatefield(BSUFIELD_NAME, $("#form_userLastname").val(), null);
});
$('#form_userLogin').keyup(function( event ){
    event.preventDefault();
    verifyForm( 4 );
});
$('#form_userLogin').change(function ( event ) {
    event.preventDefault();

    verifyLoginUnicity();
});
function verifyLoginUnicity() {
    $('#waitAjax').show();
    $dataObj = {
        'login': $('#form_userLogin').val(),
        'uid': window.IDP_CONST.bsuser_id
    }
    $.ajax({
        type: "GET",
        url: window.JSON_URLS.bs_core_admin_json_verify_login_unicity,
        data: $dataObj,
        cache: false,
        success: function ($response) {
            $('#waitAjax').hide();
            if ($response['message'] == 'NOK') {
                isValidFormField_Login = false;
                bootbox.alert({
                    message: "Ce login existe déjà pour un autre compte, merci d’en saisir un autre.",
                    className: "boxErrorOne"});
            } else {
                isValidFormField_Login = true;
                if (modify == 'MODIFY')
                    ajax_updatefield(BSUFIELD_LOGIN, $("#form_userLogin").val(), null);
            }
            verifyForm(4);
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $('#waitAjax').hide();
            bootbox.alert({
                message: "Une erreur serveur est survenue, merci de réessayer ultérieurement ! [" + throwError + "]",
                className: "boxSysErrorOne"
            });
        }
    });
}
$('#form_userPassword').keyup(function( event ){
    event.preventDefault();
    verifyForm( 5 );
});
$('#form_userPassword').change(function ( event ) {
    event.preventDefault();
    if( modify == 'MODIFY' )
        ajax_updatefield(BSUFIELD_PASSWORD, $("#form_userPassword").val(), null);
});
$('#form_userInitials').keyup(function( event ){
    event.preventDefault();
    $('#btnVerifyInitials').addClass('disabled');
    if( $('#form_userInitials').val().trim().length > 0 )
        $('#btnVerifyInitials').removeClass('disabled' );
    isValidFormField_Initials = false;
    verifyForm( 6 );
});
$('#form_userInitials').change(function ( event ) {
    event.preventDefault();
    if( modify == 'MODIFY' ) {
        ajax_updatefield(BSUFIELD_INITIAL, $("#form_userInitials").val(), null);
    }
});
$('#form_userRole').change(function ( event ) {
    event.preventDefault();
    if( modify == 'MODIFY' ) {
        ajax_updatefield(BSUFIELD_ROLE, $("#form_userRole option:selected").val(), null);
    }
    verifyForm( 7 );
});

$('#btnGenerateInitials').click( function( event ){
    event.preventDefault();
    if( $('#btnGenerateInitials').hasClass('disabled') )
        return;

    $('#waitAjax').show();
    $dataObj = {
        'firstname': $('#form_userFirstname').val().trim(),
        'lastname': $('#form_userLastname').val().trim()
    }
    $.ajax({
        type: "GET",
        url: window.JSON_URLS.bs_core_admin_json_generate_initials,
        data: $dataObj,
        cache: false,
        success: function( $response ){
            if( $response['message'] == 'NOK' ){
                $('#form_userInitials').prop( 'readonly', false );
                $('#btnGenerateInitials').hide();
                $('#btnVerifyInitials').show();
                $('#btnVerifyInitials').addClass('disabled');
                bootbox.alert({
                    message: "Le serveur n’est pas en mesure de générer une combinaison d’initiales unique, merci de saisir vous-même une autre combinaison de 4 lettres.",
                    className: "boxErrorOne"
                } );
            } else {
                $('#form_userInitials').val( $response['initials'] );
                isValidFormField_Initials = true;
                verifyForm( 6 );
                $('#btnGenerateInitials').hide();
                $('#btnVerifyInitials').show();
                $('#btnVerifyInitials').addClass('disabled');
            }
            $('#waitAjax').hide();
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $('#waitAjax').hide();
            bootbox.alert( {
                message: "Une erreur serveur est survenue, merci de réessayer ultérieurement ! ["+throwError+"]",
                className: "boxSysErrorOne"
            } );
        }
    });
})

$('#btnVerifyInitials').click( function( event ){
    event.preventDefault();
    if( $('#btnVerifyInitials').hasClass('disabled') )
        return;

    if( $('#form_userInitials').val().trim().length != 4 ){
        bootbox.alert( {
            message: "Les initiales doivent faire 4 caractères !",
            className: "boxErrorOne"
        } );
        return;
    }

    $('#waitAjax').show();
    $dataObj = {
        'initials': $('#form_userInitials').val().trim(),
        'uid': window.IDP_CONST.bsuser_id
    }
    $.ajax({
        type: "GET",
        url: window.JSON_URLS.bs_core_admin_json_verify_initials,
        data: $dataObj,
        cache: false,
        success: function( $response ){
            if( $response['message'] == 'NOK' ){
                bootbox.alert( {
                    message: "Les initiales saisies sont déjà utilisées, merci d'en essayer de nouvelles.",
                    className: "boxErrorOne"
                } );
            } else {
                isValidFormField_Initials = true;
                verifyForm( 6 );
            }
            $('#waitAjax').hide();
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $('#waitAjax').hide();
            bootbox.alert( {
                message: "Une erreur serveur est survenue, merci de réessayer ultérieurement ! ["+throwError+"]",
                className: "boxSysErrorOne"
            } );
        }
    });
})

$('#form_addUserBtn').on('click', function(e){
    $('#waitAjax').show();
});
$('#btn_input').click( function() {
	window.location.replace( window.MENU_JSON_URLS.bs_idp_archivist_managedb_input_services );
});

$('#btn_input_service').click( function() {
	window.location.replace( window.MENU_JSON_URLS.bs_idp_archivist_managedb_input_services );
});

$('#btn_input_legalentity').click( function() {
	window.location.replace( window.MENU_JSON_URLS.bs_idp_archivist_managedb_input_legalentities );
})

$('#btn_input_budgetcode').click( function() {
	window.location.replace( window.MENU_JSON_URLS.bs_idp_archivist_managedb_input_budgetcodes );
})

$('#btn_input_documentnature').click( function() {
	window.location.replace( window.MENU_JSON_URLS.bs_idp_archivist_managedb_input_documentnatures );
})

$('#btn_input_description1').click( function() {
	window.location.replace( window.MENU_JSON_URLS.bs_idp_archivist_managedb_input_descriptions1 );
})

$('#btn_input_description2').click( function() {
	window.location.replace( window.MENU_JSON_URLS.bs_idp_archivist_managedb_input_descriptions2 );
})

$('#btn_input_deliveraddress').click( function() {
	window.location.replace( window.MENU_JSON_URLS.bs_idp_archivist_managedb_input_deliveraddress );
})

$('#btn_input_documenttype').click( function() {
	window.location.replace( window.MENU_JSON_URLS.bs_idp_archivist_managedb_input_documenttypes );
})

$('#btn_provider').click( function() {
	window.location.replace( window.MENU_JSON_URLS.bs_idp_archivist_managedb_providers );
})

$('#btn_users').click( function() {
	window.location.replace( window.MENU_JSON_URLS.bs_core_users_admin_list );
})

$('#btn_visibility').click( function() {
	window.location.replace( window.MENU_JSON_URLS.bs_idp_backoffice_manage_visibility );
})

$('#btn_input_localization').click( function() {
    window.location.replace( window.MENU_JSON_URLS.bs_idp_archivist_managedb_input_localizations );
})

$('#btn_settings').click( function() {
    window.location.replace( window.MENU_JSON_URLS.bs_idp_backoffice_manage_globalsettings_passwords );
})

function dbrowstyle(row, index) {
    if( row['cansuppress'] == true )
        return { classes: 'danger' };
    else
        return { };
}
