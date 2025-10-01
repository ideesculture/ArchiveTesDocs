var FILTER_STATUS_NONE = 0;
var FILTER_STATUS_AVAILABLE = 1;
var FILTER_STATUS_TRANSFER = 2;
var FILTER_STATUS_CONSULT = 4;
var FILTER_STATUS_RETURN = 8;
var FILTER_STATUS_EXIT = 16;
var FILTER_STATUS_DELETE = 32;
var FILTER_STATUS_RELOC = 64;
var FILTER_STATUS_INCONSULT = 128;
var FILTER_STATUS_DTA = 256;

var FILTER_WHERE_NONE = 0;
var FILTER_WHERE_INTERNAL = 1;
var FILTER_WHERE_INTERMEDIATE = 2;
var FILTER_WHERE_PROVIDER = 4;

var FILTER_WITH_NONE = 0;
var FILTER_WITH_DOCUMENT = 1;
var FILTER_WITH_BOX = 2;
var FILTER_WITH_CONTAINER = 4;

var $_filterTranslations = null;
var $_localizationsIDs = [];

function initFilters( ){
    $_filterTranslations = JSON.parse( window.IDP_CONST.bs_filtertranslations );

    $list_script_ckeck = '';
    $list_script_unckeck = '';

    $strLocalizations = '';
    $localizations.forEach(function( $localization ){
        $strLocalizations += "<input type='checkbox' id='localization_"+$localization[LOCALIZATION_ID]+"' value='"+$localization[LOCALIZATION_ID]+"' class='localization'>";
        $strLocalizations += "<label for='localization_"+$localization[LOCALIZATION_ID]+"'>&nbsp;"+$localization[LOCALIZATION_NAME]+"</label><br/>";

        $_localizationsIDs.push( $localization[LOCALIZATION_ID] );
    });
    $('#localizationColumnChoices').html( $strLocalizations );

    switch( $_currentPage ){
        case PAGE_TRANSFER:
            $('#filterStatusDTA').prop('checked', true);
            break;
        case PAGE_CONSULT:
            $('#filterStatusConsult').prop('checked', true);
            $('#filterStatusAvailable').prop('checked', true);
            break;
        case PAGE_DELETE:
            // $('#filterStatusDelete').prop('checked', true);
            $('#filterStatusAvailable').prop('checked', true);
            break;
        case PAGE_EXIT:
            $('#filterStatusExit').prop('checked', true);
            $('#filterStatusAvailable').prop('checked', true);
            break;
        case PAGE_RETURN:
            // $('#filterStatusReturn').prop('checked', true);
            $('#filterStatusConsult').prop('checked', true );
            // $('#filterStatusAvailable').prop('checked', true);
            break;
        case PAGE_RELOC:
            $('#filterStatusReloc').prop('checked', true);
            $('#filterStatusAvailable').prop('checked', true);
            $('#filterStatusConsult').prop('checked', true );
            break;
        case PAGE_UNLIMITED:
            $('#filterStatusAvailable').prop('checked', true);
            $('#filterStatusConsult').prop('checked', true);
            $('#filterStatusExit').prop('checked', true);
            $('#filterStatusReloc').prop('checked', true);
            break;
    }
}

$('#filterStatusAll').change( function( event ){
    event.preventDefault();

    if( $('#filterStatusAll').is(":checked") )
        $('.status').prop('checked', true );
    else
        $('.status').prop('checked', false );
});
$('#filterWhereAll').change( function( event ){
    event.preventDefault();

    if( $('#filterWhereAll').is(":checked") )
        $('.where').prop('checked', true );
    else
        $('.where').prop('checked', false );
});
$('#filterWithAll').change( function( event ){
    event.preventDefault();

    if( $('#filterWithAll').is(":checked") )
        $('.with').prop('checked', true );
    else
        $('.with').prop('checked', false );
});
$('#filterLocalizationAll').change( function( event ){
   event.preventDefault();

   if( $('#filterLocalizationAll').is(':checked') )
       $('.localization').prop('checked', true );
   else
       $('.localization').prop('checked', false );
});

function setFullFilters(  ){

    $('#filterStatusTransfer').prop('checked', true);
    $('#filterStatusAvailable').prop('checked', true);
    $('#filterStatusInConsult').prop('checked', true);
    $('#filterStatusConsult').prop('checked', true);
    $('#filterStatusReturn').prop('checked', true);
    $('#filterStatusExit').prop('checked', true);
    $('#filterStatusDelete').prop('checked', true);
    $('#filterStatusReloc').prop('checked', true);

    $('#filterWhereInternal').prop('checked', true);
    $('#filterWhereIntermediate').prop('checked', true);
    $('#filterWhereProvider').prop('checked', true);

    $('#filterWithDocument').prop('checked', false);
    $('#filterWithBox').prop('checked', false);
    $('#filterWithContainer').prop('checked', false);

    $localizations.forEach(function( $localization ) {
        $checkstr  = 'localization_' + $localization[LOCALIZATION_ID];
        $($checkstr).prop('checked', true);
    });
}

function getFilters( ){
    $_filters = {
        'filterstatus': FILTER_STATUS_NONE,
        'filterwhere': FILTER_WHERE_NONE,
        'filterwith': FILTER_WITH_NONE,
        'filterlocalization': ''
    };

    if( $('#filterStatusDTA').prop('checked') == true )
        $_filters['filterstatus'] += FILTER_STATUS_DTA;
    if( $('#filterStatusAvailable').prop('checked') == true )
        $_filters['filterstatus'] += FILTER_STATUS_AVAILABLE;
    if( $('#filterStatusTransfer').prop('checked') == true )
        $_filters['filterstatus'] += FILTER_STATUS_TRANSFER;
    if( $('#filterStatusConsult').prop('checked') == true )
        $_filters['filterstatus'] += FILTER_STATUS_CONSULT;
    if( $('#filterStatusReturn').prop('checked') == true )
        $_filters['filterstatus'] += FILTER_STATUS_RETURN;
    if( $('#filterStatusExit').prop('checked') == true )
        $_filters['filterstatus'] += FILTER_STATUS_EXIT;
    if( $('#filterStatusDelete').prop('checked') == true )
        $_filters['filterstatus'] += FILTER_STATUS_DELETE;
    if( $('#filterStatusReloc').prop('checked') == true )
        $_filters['filterstatus'] += FILTER_STATUS_RELOC;
    if( $('#filterStatusInConsult').prop('checked') == true )
        $_filters['filterstatus'] += FILTER_STATUS_INCONSULT;

    if( $('#filterWhereInternal').prop('checked') == true )
        $_filters['filterwhere'] += FILTER_WHERE_INTERNAL;
    if( $('#filterWhereIntermediate').prop('checked') == true )
        $_filters['filterwhere'] += FILTER_WHERE_INTERMEDIATE;
    if( $('#filterWhereProvider').prop('checked') == true )
        $_filters['filterwhere'] += FILTER_WHERE_PROVIDER;

    if( $('#filterWithDocument').prop('checked') == true )
        $_filters['filterwith'] += FILTER_WITH_DOCUMENT;
    if( $('#filterWithBox').prop('checked') == true )
        $_filters['filterwith'] += FILTER_WITH_BOX;
    if( $('#filterWithContainer').prop('checked') == true )
        $_filters['filterwith'] += FILTER_WITH_CONTAINER;

    $_localizationsIDs.forEach(function( $localizationID ){
        $checkstr = '#localization_'+$localizationID;
        if( $($checkstr).prop('checked') == true ) {
            var $str = $_filters['filterlocalization'];
            $_filters['filterlocalization'] += ($str.length <= 0 ? '' : ',') + $localizationID;
        }
    });

    return $_filters;
}

$('#btnChangeFilter').click(function( $event ){
    $event.preventDefault();

    // Clear contextual text search
    $('#listsearchTable').bootstrapTable('resetSearch', '');

    // Clear all the searchs options
    resetSearch( );

    // do the Search
    doSearch( );
})