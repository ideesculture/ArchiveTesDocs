// IDPArchiveAutoSaveFields.js
// E307: Users can choose which fields are saved for next UA input

var $_user_asf;

var $_AutoSaveFieldsState = {
    'asf_service': true,
    'asf_legalentity': true,
    'asf_budgetcode': true,
    'asf_documentnature': true,
    'asf_documenttype': true,
    'asf_description1': true,
    'asf_description2': true,
    'asf_closureyear': true,
    'asf_destructionyear': true,
    'asf_filenumber': false,
    'asf_boxnumber': false,
    'asf_containernumber': false,
    'asf_provider': true,
    'asf_limitsdate': false,
    'asf_limitsnum': false,
    'asf_limitsalpha': false,
    'asf_limitsalphanum': false,
    'asf_name': false
};

var $_AutoSaveFields = {
    'asf_service': $('#lbl_service'),
    'asf_legalentity': $('#lbl_legalentity'),
    'asf_budgetcode': $('#lbl_budgetcode'),
    'asf_documentnature': $('#lbl_documentnature'),
    'asf_documenttype': $('#lbl_documenttype'),
    'asf_description1': $('#lbl_description1'),
    'asf_description2': $('#lbl_description2'),
    'asf_closureyear': $('#lbl_closureyear'),
    'asf_destructionyear': $('#lbl_destructionyear'),
    'asf_filenumber': $('#lbl_filenumber'),
    'asf_boxnumber': $('#lbl_boxnumber'),
    'asf_containernumber': $('#lbl_containernumber'),
    'asf_provider': $('#lbl_provider'),
    'asf_limitsdate': $('#lbl_limitsdatemin'),
    'asf_limitsnum': $('#lbl_limitsnummin'),
    'asf_limitsalpha': $('#lbl_limitsalphamin'),
    'asf_limitsalphanum': $('#lbl_limitsalphanummin'),
    'asf_name': $('#lbl_name')
};

var $_AutoSaveFieldsId = {
    'asf_service': 1,
    'asf_legalentity': 2,
    'asf_budgetcode': 3,
    'asf_documentnature': 4,
    'asf_documenttype': 5,
    'asf_description1': 6,
    'asf_description2': 7,
    'asf_closureyear': 8,
    'asf_destructionyear': 9,
    'asf_filenumber': 10,
    'asf_boxnumber': 11,
    'asf_containernumber': 12,
    'asf_provider': 13,
    'asf_limitsdate': 14,
    'asf_limitsnum': 15,
    'asf_limitsalpha': 16,
    'asf_limitsalphanum': 17,
    'asf_name': 18
};

function invertAndUpdate( $fieldname ){
    $_AutoSaveFieldsState[$fieldname] = !$_AutoSaveFieldsState[$fieldname];
    setAutoSaveFields_IHM( $fieldname );
    ajax_update_asf( $fieldname, $_AutoSaveFieldsState[$fieldname] );
}

function setAutoSaveFields_IHM( $fieldname ){
    if( $_AutoSaveFieldsState[$fieldname] ){
        $_AutoSaveFields[$fieldname].addClass( 'idp_saved' );
    } else {
        $_AutoSaveFields[$fieldname].removeClass( 'idp_saved' );
    }
}

function ajax_update_asf( $field, $value ){
    $dataObj = {
        'user_id': window.IDP_CONST.bs_user_id,
        'field_id': $_AutoSaveFieldsId[$field],
        'new_value': $value
    }
    $.ajax({
        type: "GET",
        url: window.JSON_URLS.bs_core_user_asf_update,
        data: $dataObj,
        cache: false,
        success: function( ){
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert( "Error" );
        }
    });
}


$('#lbl_service').on('click', function(e){
    invertAndUpdate('asf_service' );
});
$('#lbl_legalentity').on('click', function(e){
    invertAndUpdate('asf_legalentity' );
});
$('#lbl_budgetcode').on('click', function(e){
    invertAndUpdate('asf_budgetcode' );
});
$('#lbl_documentnature').on('click', function(e){
    invertAndUpdate('asf_documentnature' );
});
$('#lbl_documenttype').on('click', function(e){
    invertAndUpdate('asf_documenttype' );
});
$('#lbl_description1').on('click', function(e){
    invertAndUpdate('asf_description1' );
});
$('#lbl_description2').on('click', function(e){
    invertAndUpdate('asf_description2' );
});
$('#lbl_closureyear').on('click', function(e){
    invertAndUpdate('asf_closureyear' );
});
$('#lbl_destructionyear').on('click', function(e){
    invertAndUpdate('asf_destructionyear' );
});
$('#lbl_filenumber').on('click', function(e){
    invertAndUpdate('asf_filenumber' );
});
$('#lbl_boxnumber').on('click', function(e){
    invertAndUpdate('asf_boxnumber' );
});
$('#lbl_containernumber').on('click', function(e){
    invertAndUpdate('asf_containernumber' );
});
$('#lbl_provider').on('click', function(e){
    invertAndUpdate('asf_provider' );
});
$('#lbl_limitsdatemin').on('click', function(e){
    invertAndUpdate('asf_limitsdate' );
});
$('#lbl_limitsnummin').on('click', function(e){
    invertAndUpdate('asf_limitsnum' );
});
$('#lbl_limitsalphamin').on('click', function(e){
    invertAndUpdate('asf_limitsalpha' );
});
$('#lbl_limitsalphanummin').on('click', function(e){
    invertAndUpdate('asf_limitsalphanum' );
});
$('#lbl_name').on('click', function(e){
    invertAndUpdate('asf_name' );
});

$(document).ready(function() {
    $_user_asf = JSON.parse(window.IDP_CONST.user_asf_array);
    if( $_user_asf ) {
        $_AutoSaveFieldsState = $_user_asf[0];
    }
    // Init View
    for( var $fieldName in $_AutoSaveFieldsState ){
        if(( $fieldName != 'id' )&&( $fieldName != 'user_id' ))
            setAutoSaveFields_IHM( $fieldName );
    }
});