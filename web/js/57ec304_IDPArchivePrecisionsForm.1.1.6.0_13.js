var _PRECISION_DATE_MANDATORY = [ PAGE_TRANSFER, PAGE_CONSULT, PAGE_DELETE, PAGE_EXIT, PAGE_RELOC, PAGE_RETURN ];
var _PRECISION_WHO_MANDATORY = [ PAGE_TRANSFER, PAGE_CONSULT, PAGE_DELETE, PAGE_EXIT, PAGE_RELOC, PAGE_RETURN ];
var _PRECISION_WHERE_MANDATORY = [ PAGE_TRANSFER, PAGE_CONSULT, PAGE_EXIT, PAGE_RETURN ];
var _PRECISION_FLOOR_MANDATORY = [ PAGE_TRANSFER, PAGE_CONSULT, PAGE_EXIT, PAGE_RETURN ];
var _PRECISION_OFFICE_MANDATORY = [ PAGE_TRANSFER, PAGE_CONSULT, PAGE_EXIT, PAGE_RETURN ];
var _PRECISION_COMMENT_MANDATORY = [ PAGE_CONSULT ];
// Precision Form Validation
$('form').submit(function() {
    if( _PRECISION_DATE_MANDATORY.indexOf( $_currentPage ) >= 0 ) {
        if ($('#form_precisiondate').val() == '') {
            popError($('#form_precisiondate'), $_precisionTranslations[20], 'bottom');
            return false;
        }
        $d = new Date();
        $d.setHours(0, 0, 0, 0); // Set to last midnight
        $nowTimestamp = $d.getTime();
        $formDate = $('#form_precisiondate').val().split("/");
        $dateEntered = new Date($formDate[2], $formDate[1] - 1, $formDate[0]);
        $enteredTimestamp = $dateEntered.getTime();
        if ($enteredTimestamp < $nowTimestamp) {
            switch( $_currentPage ) {
                case PAGE_TRANSFER: popError ($('#form_precisiondate'), $_precisionTranslations[33], 'bottom'); break;
                case PAGE_CONSULT: popError ($('#form_precisiondate'), $_precisionTranslations[27], 'bottom'); break;
                case PAGE_DELETE: popError ($('#form_precisiondate'), $_precisionTranslations[30], 'bottom'); break;
                case PAGE_EXIT: popError ($('#form_precisiondate'), $_precisionTranslations[29], 'bottom'); break;
                case PAGE_RELOC: popError ($('#form_precisiondate'), $_precisionTranslations[31], 'bottom'); break;
                case PAGE_RETURN: popError ($('#form_precisiondate'), $_precisionTranslations[28], 'bottom'); break;
            }
            return false;
        }
    }
    if( _PRECISION_WHO_MANDATORY.indexOf( $_currentPage ) >= 0 ) {
        if( $('#form_precisionwho').val() == '' ) {
            popError($('#form_precisionwho'), $_precisionTranslations[22], 'bottom');
            return false;
        }
    }
    if( _PRECISION_WHERE_MANDATORY.indexOf( $_currentPage ) >= 0 ) {
        if( $('#form_precisionwhere option:selected').val() == '' ) {
            popError($('#form_precisionwhere'), $_precisionTranslations[23], 'bottom');
            return false;
        }
    }
    if( _PRECISION_FLOOR_MANDATORY.indexOf( $_currentPage ) >= 0 ) {
        if( $('#form_precisionfloor').val() == '' ) {
            popError($('#form_precisionfloor'), $_precisionTranslations[24], 'bottom');
            return false;
        }
    }
    if( _PRECISION_OFFICE_MANDATORY.indexOf( $_currentPage ) >= 0 ) {
        if ($('#form_precisionoffice').val() == '') {
            popError($('#form_precisionoffice'), $_precisionTranslations[25], 'top');
            return false;
        }
    }
    if( _PRECISION_COMMENT_MANDATORY.indexOf( $_currentPage ) >= 0 ) {
        if ($listToDeliverPrepare && ($listToDeliverPrepare.length > 0) && ($('#form_precisioncomment').val() == '')) {
            popError($('#form_precisioncomment'), $_precisionTranslations[32], 'top');
            return false;
        }
    }
    return true;
});

// Date Time Picker Initialization & Behavior
var datePicker = $('#form_precisiondate').datepicker({'format': 'dd/mm/yyyy'})
    .on('changeDate', function(ev){ datePicker.datepicker('hide'); });
$('.datepicker').css("z-index","100000");

// Error message showing
function popError( $ancre, $message, $where ){
    $ancre.popover({trigger:'manual', placement: $where, content: $message });
    $ancre.popover('show').addClass('has-error');
    $ancre.click(function(){ $ancre.popover('hide'); });
}
