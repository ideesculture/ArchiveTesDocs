// Must be included after IDPArchiveSearch.js

var $currentArchiveView = null;


$(document).ready(function(){

});


function initManageView(){
    $('#listsearchTable').bootstrapTable({})
        .on('click-row.bs.table', function( e, row, $element ){ $currentArchiveView = row['id']; } )
        .on('check.bs.table', function(e, row ){ $currentArchiveView = row['id']; })
};

$('#divCancel').click(function(){
    $('#viewArchive').hide();
});
