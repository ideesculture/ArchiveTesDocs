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