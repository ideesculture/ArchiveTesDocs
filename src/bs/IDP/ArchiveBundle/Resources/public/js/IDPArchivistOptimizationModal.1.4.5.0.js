//=====================================================================================================
// ARCHIVIST OPTIMIZATION MODAL
// Goal:
//      This renders the Optimization modal popup
// Entry point: function updatePopupOptimizationModal_v3
//-----------------------------------------------------------------------------------------------------
// Remarks:
//      Version 03 ( Take care of Box in Container and Master PreCheck if all insiders are checked )
//                  Algorythm is rewritten completely from v2
//=====================================================================================================
// Algorythm:
//=====================================================================================================

//-----------------------------------------------------------------------------------------------------
// Constants for this module
const DEBUG_OPTIMIZATION_MODAL = true;        // debugging mode
//.....................................................................................................
// Allowed statuses to be checked
const NOT_DEL_STATUS_ALLOWED = [ 'DISP', 'GLAP', 'GPAP', 'GRLPDAI', 'GRLPDAINT', 'CLAP', 'CPAP',
    'CRLPDAI', 'CRLPDAINT' ];
const DEL_STATUS_ALLOWED = [ 'DISP', 'GDAP', 'CDAP' ];
//.....................................................................................................
// Type of structs
const OPTIM_CONTAINER   = 3;
const OPTIM_SUBBOX      = 2;
const OPTIM_BOX         = 1;
const OPTIM_OTHER       = 0;

// Default Texts
const OPTIM_CONTAINER_LABEL = 'Conteneur';
const OPTIM_BOX_LABEL       = 'Boîte';
const OPTIM_DOC_LABEL       = 'Dossier';
const OPTIM_ROA_LABEL       = 'Archives non optimisables';
const OPTIM_NAME_LABEL      = 'Libellé';

//.....................................................................................................
var OPTIM_b_First_Panel             = true;             // first panel is open by default, others are closed
var OPTIM_statuses_allowed          = null;
var OPTIM_UA_Selected_In_Basket     = null;
var OPTIM_del_Mode                  = false;

// Update $('#OptimisationModalBody') or $('#DelOptimisationModalBody')

//-----------------------------------------------------------------------------------------------------
// Entry Point

//.....................................................................................................
// function:    updatePopupOptimizationModal_v3
// purpose:     Generates the popup dialog for optimization
// parameters:
//      - $optimizedList: List of UAs needed for optimization (calculated by the server), we assume that server returns
//                        this list sorted by container, then boxes, then documents, then service,
//      - $alreadyCheckedListID: List of IDs of UAs selected by the user
//      - $delMode: true for delMode
// return:  N/A

function updatePopupOptimizationModal_v3( $optimizedList, $alreadyCheckedListID, $delMode ) {
    if (DEBUG_OPTIMIZATION_MODAL) console.log('ENTER updatePopupOptimizationModal_v3');
    OPTIM_b_First_Panel = true;

    OPTIM_del_Mode = $delMode;
    OPTIM_statuses_allowed = OPTIM_del_Mode ? DEL_STATUS_ALLOWED : NOT_DEL_STATUS_ALLOWED;
    OPTIM_UA_Selected_In_Basket = $alreadyCheckedListID;

    let $optimModal = OPTIM_del_Mode ? $('#DelOptimisationModalBody') : $('#OptimisationModalBody');

    //..................................................................
    // Analyse objects and prepare listObject
    let $listObjects = analyseAndPrepareListObjects( $optimizedList );

    //..................................................................
    // Analyse objects and calculate scripts, cannot be done in first pass
    if( !OPTIM_del_Mode )
        $listObjects = analyseAndCalculateScripts( $listObjects );

    //..................................................................
    // Render objects
    let $html = renderObjects( $listObjects );
    $optimModal.html( $html );
}

//-----------------------------------------------------------------------------------------------------
//  Functions used by entry point

//.....................................................................................................
// function:  analyseAndPrepareListObjects
// Purpose: analyse all UA, and makes structs to prepare rendering
// Parameters:
//      - $optimizedList: List of UAs needed for optimization (calculated by the server), we assume that server returns
//                        this list sorted by container, then boxes, then documents, then service,

function analyseAndPrepareListObjects( $optimizedList ){
    let $currentContainer = createNewOptimStruct( OPTIM_CONTAINER );
    let $currentSubBox = createNewOptimStruct( OPTIM_SUBBOX );
    let $currentBox = createNewOptimStruct( OPTIM_BOX );
    let $otherUAs = createNewOptimStruct( OPTIM_OTHER );
    $otherUAs = initOptimStruct( null, $otherUAs );
    var $listObjects = { 'containers': [], 'boxes': [], 'others': $otherUAs };
    let $currentSUID = null;

    //..................................................................
    // Iteration through optimized list given by server
    for( let $i = 0, $len = $optimizedList.length; $i < $len; $i++ ){

        let $UALine = $optimizedList[$i];
        if (DEBUG_OPTIMIZATION_MODAL) console.log('== Current Line: ' + $i + ' ==');
        if (DEBUG_OPTIMIZATION_MODAL) console.log( debugLogUALine( $UALine ));

        if( $UALine['containernumber'] != null ){
            // This line is in a container
            if (DEBUG_OPTIMIZATION_MODAL) console.log('UA in container : ' + $UALine['containernumber'] );

            if( $UALine['containernumber'] == $currentContainer.identification && $UALine['suid'] == $currentSUID ){
                // This line is in the same container than previous one
                if (DEBUG_OPTIMIZATION_MODAL) console.log('same container than previous UA !' );

                if( $UALine['boxnumber'] != null ){
                    // This line is in a subbox
                    if (DEBUG_OPTIMIZATION_MODAL) console.log('UA in subbox : ' + $UALine['boxnumber'] );

                    if( $UALine['boxnumber'] == $currentSubBox.identification ){
                        // This line is in the same subbox than previous one
                        if (DEBUG_OPTIMIZATION_MODAL) console.log('same subbox than previous UA !' );

                        // Add line to current subbox
                        $currentSubBox = addUAtoOptimStruct( $UALine, $currentSubBox );
                        if (DEBUG_OPTIMIZATION_MODAL) console.log('Add line to subbox !' );

                    } else {
                        // This line is not in the same subbox than previous one
                        if (DEBUG_OPTIMIZATION_MODAL) console.log('This UA is in a new subbox !' );

                        // Add current subbox to container if exist
                        if( $currentSubBox.identification ) {
                            $currentContainer = addSubboxToContainer( $currentSubBox, $currentContainer);
                            $currentSubBox = createNewOptimStruct( OPTIM_SUBBOX );
                            if (DEBUG_OPTIMIZATION_MODAL) console.log('Add previous subbox to container and create new one !' );
                        }
                        $currentSubBox = initOptimStruct( $UALine, $currentSubBox );

                        $currentSubBox = addUAtoOptimStruct( $UALine, $currentSubBox );
                        if (DEBUG_OPTIMIZATION_MODAL) console.log('Add line to new subbox !' );

                    }

                } else {
                    // This line is not in a subbox
                    if (DEBUG_OPTIMIZATION_MODAL) console.log('UA not in subbox' );

                    // Add current subbox to container if exist
                    if( $currentSubBox.identification ) {
                        $currentContainer = addSubboxToContainer( $currentSubBox, $currentContainer );
                        $currentSubBox = createNewOptimStruct( OPTIM_SUBBOX );
                        if (DEBUG_OPTIMIZATION_MODAL) console.log('Add previous subbox to container and create new one !' );
                    }

                    // Add line to others of container
                    $currentContainer = addUAtoOptimStruct( $UALine, $currentContainer );
                    if (DEBUG_OPTIMIZATION_MODAL) console.log('Add line to others in container !' );

                }

            } else {
                // This line is not in the same container than previous one
                if (DEBUG_OPTIMIZATION_MODAL) console.log('This UA is in a new container !' );

                if( $currentContainer.identification ) {
                    OPTIM_b_First_Panel = false;
                    // Add previous subbox to previous container if exist
                    if ($currentSubBox.identification) {
                        $currentContainer = addSubboxToContainer( $currentSubBox, $currentContainer );
                        $currentSubBox = createNewOptimStruct( OPTIM_SUBBOX );
                        if (DEBUG_OPTIMIZATION_MODAL) console.log('Add previous subbox to container and create new one if needed !');
                    }
                    // Add previous container to list of containers
                    $listObjects = addObjectToList( $currentContainer, $listObjects );
                    $currentContainer = createNewOptimStruct( OPTIM_CONTAINER );
                    if (DEBUG_OPTIMIZATION_MODAL) console.log('Add previous container to list and create new one !');
                }
                $currentContainer = initOptimStruct( $UALine, $currentContainer );

                if( $UALine['boxnumber'] != null ){
                    // This line is in a subbox
                    $currentSubBox = initOptimStruct( $UALine, $currentSubBox );
                    if (DEBUG_OPTIMIZATION_MODAL) console.log('UA in subbox : ' + $UALine['boxnumber'] );

                    // It must be a new subbox because it is a new container !
                    $currentSubBox = addUAtoOptimStruct( $UALine, $currentSubBox );
                    if (DEBUG_OPTIMIZATION_MODAL) console.log('Add line to new subbox !' );

                } else {
                    // This line is not in a subbox
                    if (DEBUG_OPTIMIZATION_MODAL) console.log('This UA is not in a subbox !' );

                    // Add line to others of container
                    $currentContainer = addUAtoOptimStruct( $UALine, $currentContainer );
                    if (DEBUG_OPTIMIZATION_MODAL) console.log('Add line to others in container !' );
                }

            }

        } else {
            // This line is not in a container
            if (DEBUG_OPTIMIZATION_MODAL) console.log('UA not in container' );

            // Verify if there is a subox / container to close
            if( $currentContainer.identification ){
                if( $currentSubBox.identification ){
                    $currentContainer = addSubboxToContainer( $currentSubBox, $currentContainer );
                    $currentSubBox = createNewOptimStruct( OPTIM_SUBBOX );
                    if (DEBUG_OPTIMIZATION_MODAL) console.log('Add previous subbox to container and create new one !');
                }
                // Add previous container to list of containers
                $listObjects = addObjectToList( $currentContainer, $listObjects );
                $currentContainer = createNewOptimStruct( OPTIM_CONTAINER );
                OPTIM_b_First_Panel = false;
                if (DEBUG_OPTIMIZATION_MODAL) console.log('Add previous container to list and create new one !');
            }

            if( $UALine['boxnumber'] != null ){
                // This line is in a box
                if (DEBUG_OPTIMIZATION_MODAL) console.log('UA in box : ' + $UALine['boxnumber'] );

                if( $UALine['boxnumber'] == $currentBox.identification && $UALine['suid'] == $currentSUID ){
                    // This line is in the same box
                    if (DEBUG_OPTIMIZATION_MODAL) console.log('UA in same box' );

                    $currentBox = addUAtoOptimStruct( $UALine, $currentBox );
                    if (DEBUG_OPTIMIZATION_MODAL) console.log('Add line to box' );

                } else {
                    // This line is not in the same box
                    if (DEBUG_OPTIMIZATION_MODAL) console.log('UA not in same box' );

                    // Verify if there is a previous box
                    if( $currentBox.identification ){
                        $listObjects = addObjectToList( $currentBox, $listObjects );
                        $currentBox = createNewOptimStruct( OPTIM_BOX );
                        OPTIM_b_First_Panel = false;
                        if (DEBUG_OPTIMIZATION_MODAL) console.log('Add previous box to list and create a new one' );
                    }
                    $currentBox = initOptimStruct( $UALine, $currentBox );

                    $currentBox = addUAtoOptimStruct( $UALine, $currentBox );
                    if (DEBUG_OPTIMIZATION_MODAL) console.log('Add line to box' );

                }

            } else {
                // This line is not in a box
                if (DEBUG_OPTIMIZATION_MODAL) console.log('UA not in box' );

                // Verify it there is a subbox / container to close
                if( $currentContainer.identification ){
                    OPTIM_b_First_Panel = false;
                    if( $currentSubBox.identification ){
                        $currentContainer = addSubboxToContainer( $currentSubBox, $currentContainer );
                        $currentSubBox = createNewOptimStruct( OPTIM_SUBBOX );
                        if (DEBUG_OPTIMIZATION_MODAL) console.log('Add previous subbox to container and create new one !');
                    }
                    // Add previous container to list of containers
                    $listObjects['containers'].push( $currentContainer );
                    $currentContainer = createNewOptimStruct( OPTIM_CONTAINER );
                    if (DEBUG_OPTIMIZATION_MODAL) console.log('Add previous container to list and create new one !');
                }

                // Verify if there is a box to close
                if( $currentBox.identification ){
                    $listObjects = addObjectToList( $currentBox, $listObjects );
                    $currentBox = createNewOptimStruct( OPTIM_BOX );
                    OPTIM_b_First_Panel = false;
                }

                // Add line to others
                $otherUAs = addUAtoOptimStruct( $UALine, $otherUAs );
                if (DEBUG_OPTIMIZATION_MODAL) console.log('Add line to others !');

            }

        }

        $currentSUID = $UALine['suid'];
    } // End of for loop

    // Verify it there is a subbox / container to close
    if( $currentContainer.identification ){
        if( $currentSubBox.identification ){
            $currentContainer = addSubboxToContainer( $currentSubBox, $currentContainer );
            if (DEBUG_OPTIMIZATION_MODAL) console.log('Add previous subbox to container !');
        }
        // Add previous container to list of containers
        $listObjects = addObjectToList( $currentContainer, $listObjects );
        if (DEBUG_OPTIMIZATION_MODAL) console.log('Add previous container to list  !');
    }

    // Verify if there is a box to close
    if( $currentBox.identification ){
        $listObjects = addObjectToList( $currentBox, $listObjects );
        if (DEBUG_OPTIMIZATION_MODAL) console.log('Add previous box to list  !');
    }

    return $listObjects;
}

//.....................................................................................................
// Function create new struct
// Params:
//      - structType: type of struct we want to create

function createNewOptimStruct( $structType, $UALine ){
    var $emptyNewStruct = {
        identification  : null,                 // Identification of object
        type            : $structType,          // Type of structure we are dealing with
        size            : 0,                    // size of package (avoid calculating length of lines[]
        lines           : [],                   // UA in this object

        output          : {                     // output struct to help generate dialog
            checkboxID  : '',                   // id of the checkbox (document / box / subbox / container)
            header      : {                     //
                begin   : '',
                end     : ''
            },
            content     : {
                begin   : ''
            },
            script      : {                     //
                begin   : '',
                on      : '',
                off     : ''
            }
        }
    };
    if( $structType != OPTIM_OTHER ){           // If we are describing a package, add a verification structure to it
        $emptyNewStruct['verif'] = {
            bAllInBasket            : true,     // All UA in the package have been asked in basket by customer
            bOneNotWellIdentified   : false,    // There is at least one UA not well identified in this package
            bOneNotAuthorized       : false,    // There is at least one UA not authorized to be checked in this package
            bAsked                  : false     // This package has the Asked status (boxasked or containerasked)
        };

        if( $structType == OPTIM_CONTAINER )    // If we are describing a container, add subboxes list to it
            $emptyNewStruct['subboxes'] = [];
    }
    return $emptyNewStruct;
}
//.....................................................................................................
// Function initialize struct
// Params:
//      - $UALine: line to init optim struct
//      - $optimStruct: struct to be initialized

function initOptimStruct( $UALine, $optimStruct ){

    $optimStruct.identification = ( $optimStruct.type == OPTIM_CONTAINER ) ?
        $UALine['containernumber'] :
        ( $optimStruct.type == OPTIM_BOX || $optimStruct.type == OPTIM_SUBBOX ) ?
            $UALine['boxnumber'] :
            'ROA';

    let $nameTrimed = '';
    let $panelID = '';
    let $panelHeadID = '';
    let $panelCheckboxID = '';
    let $panelCollapseID = '';
    let $nameIdentification = '';
    let $valueCheckbox = -1;

    switch( $optimStruct.type ){
        case OPTIM_CONTAINER:
        case OPTIM_SUBBOX:
            $nameTrimed = $UALine['containernumber'].replace(/\s+/g, '') + '_' + $UALine['suid'] +
                (( $optimStruct.type == OPTIM_SUBBOX ) ? '_SB_' + $UALine['boxnumber'] : '');
            $panelID = 'pContainer_' + $nameTrimed;
            $panelHeadID = 'phContainer_' + $nameTrimed;
            $panelCheckboxID = 'C_' + $nameTrimed;
            $panelCollapseID = 'pcContainer_List_' + $nameTrimed;
            $nameIdentification = ' ' + (( $optimStruct.type == OPTIM_CONTAINER ) ? OPTIM_CONTAINER_LABEL : OPTIM_BOX_LABEL )
                + ' ' + $optimStruct.identification;
            break;
        case OPTIM_BOX:
            $nameTrimed = $UALine['boxnumber'].replace(/\s+/g, '') + '_' + $UALine['suid'];
            $panelID = 'pBox_' + $nameTrimed;
            $panelHeadID = 'phBox_' + $nameTrimed;
            $panelCheckboxID = 'B_' + $nameTrimed;
            $panelCollapseID = 'pcBox_List_' + $nameTrimed;
            $nameIdentification = ' ' + OPTIM_BOX_LABEL + ' ' + $optimStruct.identification;
            break;
        default:
            $panelID = 'pRoa';
            $panelHeadID = 'phRoa';
            $panelCollapseID = 'pcRoa_List';
            $nameIdentification = ' ' + OPTIM_ROA_LABEL + ' ';
            break;
    }

    let $color = 'default';
    if( $optimStruct.type == OPTIM_CONTAINER ) $color = 'success';
    if( $optimStruct.type == OPTIM_BOX || $optimStruct.type == OPTIM_SUBBOX ) $color = 'info';

    $optimStruct.output.checkboxID = $panelCheckboxID;

    $optimStruct.output.header.begin = '<div class="panel panel-'+$color+' mb5" id="' + $panelID + '"> ' +
        '<div class="panel-heading" role="tab" id="' + $panelHeadID + '"> ' +
        '<h4 class="panel-title">';
    if( $optimStruct.type != OPTIM_OTHER )
        $optimStruct.output.header.begin += '<input type="checkbox" id="' + $panelCheckboxID + '" value="' + $valueCheckbox + '" ';

    $optimStruct.output.header.end = ( $optimStruct.type != OPTIM_OTHER ) ? '/>' : '';
    $optimStruct.output.header.end += '<a class="' + ( !OPTIM_b_First_Panel ? 'collapsed' : '' ) +
        '" data-toggle="collapse" data-parent="#OptimizationModalBody" href="#' + $panelCollapseID +
        '" aria-expanded="' + ( OPTIM_b_First_Panel ? 'true' : 'false' ) +
        '" aria-controls="' + $panelCollapseID + '">' +
        $nameIdentification + '</a></h4></div>';

    $optimStruct.output.content.begin = '<div id="' + $panelCollapseID + '" class="panel-collapse collapse list-group p2 ' +
        ( OPTIM_b_First_Panel ? 'in' : '' ) +
        '" role="tabpanel" aria-labelledby="' + $panelHeadID + '">';

    if( $optimStruct.type != OPTIM_OTHER )
        $optimStruct.output.script.begin = "<script>$('#" + $panelCheckboxID + "').change(function(){ if(this.checked){ ";

    return $optimStruct;
}

//.....................................................................................................
// Function add line to struct with verification
// Params:
//      - $UALine: line to add
//      - $optimStruct: struct to add into

function addUAtoOptimStruct( $UALine, $optimStruct ){

    $optimStruct.size++;
    $optimStruct.lines.push( $UALine );

    // Update verif states of current struct:
    if( $optimStruct.type != OPTIM_OTHER ){
        let $bWellIdentified = true;
        let $bAuthorized = true;

        // bAllInBasket            All UA in the package have been asked in basket by customer
        if( OPTIM_UA_Selected_In_Basket.indexOf($UALine['id']) < 0 )
            $optimStruct.verif.bAllInBasket = false;

        // bOneNotWellIdentified    There is at least one UA not well identified in this package
        // ==> to be compared with package size, if package size > 1, all package MUST be asked
        switch( $optimStruct.type ){
            case OPTIM_CONTAINER:
                if(( $UALine['boxnumber'] == null || $UALine['boxnumber'] == '' )&&
                    ( $UALine['documentnumber'] == null || $UALine['boxnumber'] == '' )) {
                    $optimStruct.verif.bOneNotWellIdentified = true;
                    $bWellIdentified = false;
                }
                break;
            case OPTIM_SUBBOX:
            case OPTIM_BOX:
                if( $UALine['documentnumber'] == null || $UALine['documentnumber'] == '' ) {
                    $optimStruct.verif.bOneNotWellIdentified = true;
                    $bWellIdentified = false;
                }
                break;
        }

        // bOneNotAuthorized        There is at least one UA not authorized to be checked in this package
        if( $.inArray($UALine['statuscaps'], OPTIM_statuses_allowed ) < 0 ) {
            $optimStruct.verif.bOneNotAuthorized = true;
            $bAuthorized = false;
        }

        // bAsked                   This package has the Asked status (boxasked or containerasked)
        if( $optimStruct.type == OPTIM_CONTAINER && $UALine['containerasked'] )
            $optimStruct.verif.bAsked = true;
        if( $optimStruct.type == OPTIM_BOX && $UALine['boxasked'] )
            $optimStruct.verif.bAsked = true;
        // If container is asked, subboxes are asked by inheritance
        if( $optimStruct.type == OPTIM_SUBBOX && ( $UALine['containerasked'] || $UALine['boxasked'] ) )
            $optimStruct.verif.bAsked = true;
    }

    return $optimStruct;
}

//.....................................................................................................
// Function add subbox to container with verification
// Params:
//      - $subbox: an optimstruct representing a subbox
//      - $container: an optimstruct representing a container

function addSubboxToContainer( $subbox, $container ){

    if( $subbox.type != OPTIM_SUBBOX || $container.type != OPTIM_CONTAINER )
        return null;

    // Consider subbox as a "line" for calculation
    $container.size++;
    $container.subboxes.push( $subbox );

    // B#345: If subbox flag 'bAllInBasket' is false, set bAllInBasket to false for container also
    if( !$subbox.verif.bAllInBasket )
        $container.verif.bAllInBasket = false;

    // B#345: If subbox has only 1 line, and set as NotWellIdentified, just change that because it's well identified in fact
    verifyBOneNotWellIdentified( $subbox );

    return $container;
}

//.....................................................................................................
// Function add object to listobject in the right section
// Params:
//      - $object: an optimstruct representing the object to add
//      - $listobject: the listobject to add into

function addObjectToList( $object, $list ){

    if( $object.type == OPTIM_CONTAINER || $object == OPTIM_BOX )
        $object = verifyBOneNotWellIdentified( $object );           // B#345

    switch( $object.type ){
        case OPTIM_BOX:
            $list['boxes'].push( $object );
            break;
        case OPTIM_CONTAINER:
            $list['containers'].push( $object );
            break;
        default:
            break;
    }

    return $list;
}

//.....................................................................................................
// Function verify bOneNotWellIdentified coherency
// Params:
//      - $object: an optimstruct representing a object to verify

function verifyBOneNotWellIdentified( $optimstruct ){

    // B#345: If subbox has only 1 line, and set as NotWellIdentified, just change that because it's well identified in fact
    if( $optimstruct.verif.bOneNotWellIdentified && $optimstruct.size == 1 )
        $optimstruct.verif.bOneNotWellIdentified = false;

    return $optimstruct;
}

//.....................................................................................................
// Script calculation Part
//.....................................................................................................
//
// Params:
//      - $listObjects: all objects containers (with eventually subboxes), boxes and documents; prepared, ordered, grouped and analyzed
function analyseAndCalculateScripts( $listObjects ){
   // Calculate scripts for Containers
    if( $listObjects['containers'] && $listObjects['containers'].length > 0 )
        $listObjects['containers'].forEach( function( $container, $index ){
            this[$index] = calculateContainerScript( $container );
        });

   // Calculate scripts for boxes
    if( $listObjects['boxes'] && $listObjects['boxes'].length > 0 )
        $listObjects['boxes'].forEach( function( $box, $index ){
            this[$index] = calculateBoxScript( $box );
        });

    return $listObjects;
}

//.....................................................................................................
// function
// When container checked, all boxes are unchecked and grayed, and all lines are checked (if possible) and grayed
// When container unchecked, only well formed boxes are ungrayed, bad formed are grayed and checked
function calculateContainerScript( $container ) {

    $container.subboxes.forEach( function( $subbox, $index ){
        $container.output.script.on += " $('#" + $subbox.output.checkboxID + "').prop('checked', false ); ";
        $container.output.script.on += " $('#" + $subbox.output.checkboxID + "').prop('disabled', true ); ";

        if( $subbox.verif.bOneNotWellIdentified )   // We must re-check the subbox because one ua is not well identified
            $container.output.script.off += " $('#" + $subbox.output.checkboxID + "').prop('checked', true ); ";

        if( !$subbox.verif.bOneNotWellIdentified )  // We can enabled box only if there is not one bad identified
            $container.output.script.off += " $('#" + $subbox.output.checkboxID + "').prop('disabled', false ); ";

        $subbox.lines.forEach( function( $line ){
            if( $.inArray($line['statuscaps'], OPTIM_statuses_allowed) >= 0 )
                $container.output.script.on += " $('#cb" + $line['id'] + "').prop('checked', true); ";
            $container.output.script.on += " $('#cb" + $line['id'] + "').prop('disabled', true); ";

            $container.output.script.off += " $('#cb" + $line['id'] + "').prop('checked', false); ";

            if( !$subbox.verif.bOneNotWellIdentified )
                $container.output.script.off += " $('#cb" + $line['id'] + "').prop('disabled', false); ";
        });

        // Calculate subboxes scripts
        this[$index] = calculateBoxScript( $subbox );

    });
    $container.lines.forEach( function( $line ){
        if( $.inArray($line['statuscaps'], OPTIM_statuses_allowed) >= 0 ){
            $container.output.script.on += createUAToggleScript( $line, true, $container.verif.bOneNotWellIdentified );
            $container.output.script.off += createUAToggleScript( $line, false, $container.verif.bOneNotWellIdentified );
        }
    });

}

//.....................................................................................................
// function
function calculateBoxScript( $box ) {

    $box.lines.forEach( function ( $line ){
        if( $.inArray($line['statuscaps'], OPTIM_statuses_allowed) >= 0 ) {     // Authorized
            $box.output.script.on += createUAToggleScript( $line, true, $box.verif.bOneNotWellIdentified );
            $box.output.script.off += createUAToggleScript( $line, false, $box.verif.bOneNotWellIdentified );
        }
    });
}

//.....................................................................................................
// function
function createUAToggleScript( $ua, $bOnOff, $oneNotWellIdentified ){
    var $html_script = '';

    $html_script += " $('#cb" + $ua['id'] + "').prop('checked', " + ($bOnOff ? 'true' : 'false') + "); ";
    if( !$oneNotWellIdentified )
        $html_script += " $('#cb" + $ua['id'] + "').prop('disabled', " + ($bOnOff ? 'true' : 'false') + "); ";

    return $html_script;
}

//.....................................................................................................
// Rendering Part
//.....................................................................................................
// Function to render the list object into the popup
// Params:
//      - $listObjects: all objects containers (with eventually subboxes), boxes and documents; prepared, ordered, grouped and analyzed

function renderObjects( $listObjects ){
    var $html = '';

    // Render containers
    if( $listObjects['containers'] && $listObjects['containers'].length > 0 )
        $listObjects['containers'].forEach( function( $container ){
            $html += renderContainer( $container );
        });

    // Render boxes
    if( $listObjects['boxes'] && $listObjects['boxes'].length > 0 )
        $listObjects['boxes'].forEach( function( $box ){
            $html += renderBox( $box, false );
        });

    // Render others
    $html += renderOtherUAs( $listObjects['others'] ) ;

    return $html;
}

//.....................................................................................................
function renderContainer( $container ) {
    var $content_container = $container.output.header.begin;

    if( $container.verif.bAllInBasket || $container.verif.bAsked )
        $content_container += ' checked="checked" ';
    if( OPTIM_del_Mode || $container.verif.bOneNotAuthorized /*|| ( $container.verif.bOneNotWellIdentified && $container.size > 1 )*/ )
        $content_container += ' disabled="disabled" ';

    let $bAboveChecked = $container.verif.bAllInBasket || $container.verif.bAsked;

    $content_container += $container.output.header.end;

    $content_container += $container.output.content.begin;

    $container.subboxes.forEach( function( $subbox ){
        $content_container += renderBox( $subbox, $bAboveChecked );
    });

    $container.lines.forEach( function( $line ){
        let $bInBasket = ( OPTIM_UA_Selected_In_Basket.indexOf( $line['id']) >= 0 );
        let $bAllowed = ( $.inArray( $line['statuscaps'], OPTIM_statuses_allowed ) >= 0 );

        $content_container += renderOneLine( $line['id'], $line['documentnumber'], $line['name'], $line['statuscaps'],
            $bInBasket, $bAllowed, $bAboveChecked );
    });

    $content_container += '</div></div>';

    $content_container += $container.output.script.begin + $container.output.script.on + ' } else { ' + $container.output.script.off + ' }});</script>';

    return $content_container;
}

//.....................................................................................................
function renderBox( $box, $aboveChecked ){
    var $content_box = $box.output.header.begin;

    if( !$aboveChecked && ( $box.verif.bAllInBasket || $box.verif.bAsked ) )
        $content_box += ' checked="checked" ';
    if( OPTIM_del_Mode || $aboveChecked || $box.verif.bOneNotAuthorized /*|| ( $box.verif.bOneNotWellIdentified && $box.size > 1 )*/ )
        $content_box += ' disabled="disabled" ';

    let $bAboveChecked = $box.verif.bAllInBasket || $box.verif.bAsked  || $aboveChecked;

    $content_box += $box.output.header.end;

    $content_box += $box.output.content.begin;

    $box.lines.forEach( function( $line ){
        let $bInBasket = ( OPTIM_UA_Selected_In_Basket.indexOf( $line['id']) >= 0 );
        let $bAllowed = ( $.inArray( $line['statuscaps'], OPTIM_statuses_allowed ) >= 0 );

        $content_box += renderOneLine( $line['id'], $line['documentnumber'], $line['name'], $line['statuscaps'],
            $bInBasket, $bAllowed, $bAboveChecked );
    });

    $content_box += '</div></div>';

    $content_box += $box.output.script.begin + $box.output.script.on + ' } else { ' + $box.output.script.off + ' }});</script>';

    return $content_box;
}

//.....................................................................................................
function renderOtherUAs( $others ){
    if( $others.size <= 0 )
        return '';

    var $content_otherUAs = $others.output.header.begin;

    if( OPTIM_del_Mode )
        $content_otherUAs += ' disabled="disabled" ';

    $content_otherUAs += $others.output.header.end;

    $content_otherUAs += $others.output.content.begin;

    $others.lines.forEach( function( $line ){
        let $bInBasket = ( OPTIM_UA_Selected_In_Basket.indexOf( $line['id']) >= 0 );
        let $bAllowed = ( $.inArray( $line['statuscaps'], OPTIM_statuses_allowed ) >= 0 );

        $content_otherUAs += renderOneLine( $line['id'], $line['documentnumber'], $line['name'], $line['statuscaps'],
            $bInBasket, $bAllowed, false );
    });

    $content_otherUAs += '</div></div>';

    return $content_otherUAs;
}

//.....................................................................................................
function renderOneLine( $id, $document, $label, $status, $inBasket, $allowed, $aboveChecked ){
    var $content_line = '<span class="list-group-item ';

    // Color depending on line state
    $content_line += $inBasket ? 'text-primary' : $allowed ? 'text-default' : 'text-danger' ;

    // Checkbox
    $content_line += '"><input type="checkbox" id="cb' + $id + '" value="' + $id + '" ';
    if( $inBasket ) $content_line += ' checked="checked" ';
    if( !$allowed || $aboveChecked ) $content_line += ' disabled="disabled" ';
    $content_line += '/>&nbsp;';

    // Label
    let $bFirst = true;
    if( $document != null && $document.length > 0 ){
        if( $bFirst ) $bFirst = false;
        $content_line += OPTIM_DOC_LABEL + ': ' + $document;
    }
    if( !$bFirst ) $content_line += ' / ';
    $content_line += OPTIM_NAME_LABEL + ': ' + $label;

    if( !$allowed ) $content_line += ' [' + $status + '] ';

    $content_line += '</span>';

    return $content_line;
}

//.....................................................................................................
function debugLogUALine( $UALine ){
    console.log( 'id:' + $UALine['id'] + ' | suid:' + $UALine['suid'] + ' | provider:' + $UALine['provider'] + ' | container:'
    + $UALine['containernumber'] + ' | box:' + $UALine['boxnumber'] + ' | document:' + $UALine['documentnumber']
    + ' | name:' + $UALine['name'] + ' | status:' + $UALine['statuscaps'] + ' | C,B:' + $UALine['containerasked'] + ',' + $UALine['boxasked']);
}