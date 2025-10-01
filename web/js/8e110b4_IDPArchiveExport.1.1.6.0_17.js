
// Buttons Export and Export Partial
$('#divExport').click(function( event ){
    event.preventDefault();

    exportArray( $('#listsearchTable'), $_currentFCT, null, null, null, null, -1, false );
});

$('#divExportPartial').click( function( event ){
    event.preventDefault();

    exportArray( $('#listsearchTable'), $_currentFCT, null, null, null, null, -1, true );
});


