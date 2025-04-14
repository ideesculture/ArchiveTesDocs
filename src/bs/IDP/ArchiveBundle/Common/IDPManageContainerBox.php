<?php
namespace bs\IDP\ArchiveBundle\Common;

use bs\IDP\ArchiveBundle\bsIDPArchiveBundle;
use bs\IDP\ArchiveBundle\ConstantDefinition\IDPConstants;

class IDPManageContainerBox
{
    public $detailledList = null;
    public $containerBoxStruct = null;


    //===============================================================================================================
    // Exception management functions

    //----------------------------------------------------------------------------------------------------------------
    // Exception 05: Verify if there is at least one ua with missing identification: i.e container and box and document are empty
    // this function assumes $detailledList has been filled, otherwise return true (test fail)
    public function is_OneUAIdentification_Missing( $logger ){
        if( $logger ) $logger->info( '-> is_OneUAIdentification_Missing ( )' );
        if( !$this->detailledList ) return true;

        foreach( $this->detailledList as $detailledUA ) {
            if( empty($detailledUA['containernumber']) && empty($detailledUA['boxnumber']) && empty($detailledUA['documentnumber']) )
                return true;
        }

        return false;
    }

    //---------------------------------------------------------------------------------------------------------------
    // Exception 06: Verify if all UAs in a same containeur (or box) are well identified, and if not verify they are all in basket
    // this function assumes $detailledList and  $containerBoxStruct have been filled, otherwise return true (test fail)
    public function is_OneUAIdentification_Wrong( $logger ){
        if( $logger ) $logger->info( '-> is_OneUAIdentification_Wrong ( )' );
        if( !$this->detailledList || !$this->containerBoxStruct ) return true;

        if( $logger ) $logger->info( '  => $containerBoxStruct : '.json_encode($this->containerBoxStruct) );

        // First verify containers
        foreach( $this->containerBoxStruct['containerList'] as $container ){
            // Search mismatch only if nb In Basket different from nb in BDD, otherwise they are all already selected
            if( sizeof($container['listUAsInBasket']) != sizeof($container['listUAsInBDD']) ){
                // There is one UA with no Box and no Doc, so full container is needed
                if( $container['oneUADocAndBoxIsNull'] )
                    return true;
                // Otherwise verify box by box
                foreach( $container['BList'] as $bdItem ){
                    // We found one not well identified and all are not in basket, but at least one is in the basket
                    if( $bdItem['oneUADocIsNull'] === true && sizeof($bdItem['UAsListInBasket']) != sizeof($bdItem['UAsListInBDD']) && sizeof($bdItem['UAsListInBasket']) > 0 )
                        return true;
                }
            }
        }

        // Second verify boxes
        foreach( $this->containerBoxStruct['boxList'] as $box ){
            if( sizeof($box['listUAsInBasket']) != sizeof($box['listUAsInBDD']) ){
                foreach( $box['BList'] as $bdItem ){
                    if( $bdItem['oneUADocIsNull'] === true && sizeof($bdItem['UAsListInBasket']) != sizeof($bdItem['UAsListInBDD']) )
                        return true;
                }
            }
        }

        return false;
    }

    //---------------------------------------------------------------------------------------------------------------
    // Exception 07: Verify if previous demand of full container/box is maintained
    // this function assumes $detailledList and  $containerBoxStruct have been filled, otherwise return true (test fail)
    public function is_OneUAMissing_FullPreviousDemand( $logger )
    {
        if ($logger) $logger->info('-> is_OneUAMissing_FullPreviousDemand ( )');
        if (!$this->detailledList || !$this->containerBoxStruct) return true;

        // First Verify containers
        foreach( $this->containerBoxStruct['containerList'] as $container ){
            // error cannot occur when full container is already asked
            if( sizeof($container['listUAsInBasket']) != sizeof($container['listUAsInBDD'] ) ){
                if( $container['CBIsLocked'] )
                    return true;
                // Verify if boxes inside container are locked
                foreach( $container['BList'] as $bdItem ){
                    if( sizeof($bdItem['UAsListInBasket']) != sizeof($bdItem['UAsListInBDD']) )
                        if( $bdItem['BoxIsLocked'] )
                            return true;
                }
            }
        }

        // Second verify boxes
        foreach( $this->containerBoxStruct['boxList'] as $box ){
            if( sizeof($box['listUAsInBasket']) != sizeof($box['listUAsInBDD']) ){
                if( $box['CBIsLocked'] )
                    return true;
            }
        }

        return false;
    }

    //---------------------------------------------------------------------------------------------------------------
    // Exception 08: Verify if order is respected 1st Container, then Boxes, then rest of UAs
    // this function assumes $detailledList and  $containerBoxStruct have been filled, otherwise return true (test fail)
    public function is_OneUAMissing_InOrderedDemand( $logger ){
        if ($logger) $logger->info('-> is_OneUAMissing_FullPreviousDemand ( )');
        if (!$this->detailledList || !$this->containerBoxStruct) return true;

        // First Verify containers
        foreach( $this->containerBoxStruct['containerList'] as $container ) {
            // Error cannot occur when full container is already asked
            if( sizeof($container['listUAsInBasket']) != sizeof($container['listUAsInBDD']) ){
                // Error could only appear if container is first order
                if( $container['nbUAsOrderedBDD'] > 0 && $container['nbUAsOrderedBDD'] != $container['nbUAsOrderedBasket'] )
                    return true;
                // Perhaps there is a box inside this container asked first
                foreach( $container['BList'] as $bdItem ){
                    // Error could not appear if box is already fully asked
                    if( sizeof($bdItem['UAsListInBasket']) != sizeof($bdItem['UAsListInBDD']) )
                        if( $bdItem['nbUAsOrderedBDD'] > 0 && $bdItem['nbUAsOrderedBDD'] != $bdItem['nbUAsOrderedBasket'] )
                            return true;
                }
            }
        }

        // Second verify boxes
        foreach( $this->containerBoxStruct['boxList'] as $box ){
            if( sizeof($box['listUAsInBasket']) != sizeof($box['listUAsInBDD']) ){
                if( $box['nbUAsOrderedBDD'] > 0 && $box['nbUAsOrderedBDD'] != $box['nbUAsOrderedBasket'] )
                    return true;
            }
        }

        return false;
    }

    //------------------------------------------------------------------------------------
    // Exception 02: Verify if all UAs of same container / box are selected (with Status perimeter)
    // this function assumes $detailledList and  $containerBoxStruct have been filled, otherwise return true (test fail)
    public function is_OneUA_Missing_InBasket( $logger ){
        if( $logger ) $logger->info( '-> is_OneUA_Missing_InBasket' );
        if( !$this->detailledList || !$this->containerBoxStruct ) {
            if( $logger ) $logger->info( ' > Structures not initialized' );
            return true;
        } else {
            if ($logger) $logger->info(' > Structure containerBox :');
            if ($logger) $logger->info( json_encode($this->containerBoxStruct));
        }

        // Search in containers
        if( $logger ) $logger->info( ' > Search in containers' );
        foreach( $this->containerBoxStruct['containerList'] as $container ){
            if( sizeof($container['listUAsInBasket']) != sizeof($container['listUAsInBDD']) ) {
                if( $logger ) $logger->info( ' > Problem found in container: '.json_encode($container['identification']) );
                return true;
            }
        }

        // Search in boxes
        if( $logger ) $logger->info( ' > Search in boxes' );
        foreach( $this->containerBoxStruct['boxList'] as $box ){
            if( sizeof($box['listUAsInBasket']) != sizeof($box['listUAsInBDD']) ) {
                if( $logger ) $logger->info( ' > Problem found in box: '.json_encode($box['identification']) );
                return true;
            }
        }

        return false;
    }



    //===============================================================================================================
    // Utility functions

    //----------------------------------------------------------------------------------------------------------------
    // This function remove from basked list all items with status not in list
    public function keepOnlyTheseInBasket( $basketIDsList, $keepStatuses ){
        if( empty($keepStatuses) )
            return $basketIDsList;

        // If detailled list is not filled, cannot proceed, so fill it first
        if( empty($this->detailledList) ){
            if( !$this->retreive_AllDatasFor_IDsInList( $basketIDsList, null,null ) )
                return null;
        }

        $restrictedIDsList = [];
        foreach( $this->detailledList as $ua ){
            if( in_array( $ua['status'], $keepStatuses ) )
                $restrictedIDsList[] = $ua['id'];
        }

        return empty($restrictedIDsList)?null:$restrictedIDsList;
    }




    //===============================================================================================================
    // Manage Provider State called functions


    //----------------------------------------------------------------------------------------------------------------
    // Function called to lock or unlock full container (or box) selected in basket (ie add 1 only one time)
    // LOCK :  0 -> 1 / 1 -> 1 / 2 -> 3 / 3 -> 3
    // UNLOCK: 0 -> 0 / 1 -> 0 / 2 -> 2 / 3 -> 2
    public function lockUnlock_FullContainerBox_InBasket( $lock, $doctrine, $logger )
    {
        if ($logger) $logger->info('-> lock_FullContainerBox_InBasket');
        if (!$this->detailledList || !$this->containerBoxStruct) {
            if ($logger) $logger->info(' > Structures not initialized');
            return false;
        }

        $containerLockUnlock_1 = [];
        // Search in containers
        if ($logger) $logger->info(' > Search in containers');
        foreach ($this->containerBoxStruct['containerList'] as $container) {
            if( sizeof($container['listUAsInBasket']) == sizeof($container['listUAsInBDD']) )
                foreach( $container['listUAsInBasket'] as $ua )
                    if( $lock ) {
                        if (($ua['containerasked'] == 0) || ($ua['containerasked'] == 2))
                            $containerLockUnlock_1[] = $ua['id'];
                    } else {
                        if(( $ua['containerasked'] == 1 ) || ( $ua['containerasked'] == 3 ))
                            $containerLockUnlock_1[] = $ua['id'];
                    }
        }
        if( $logger ) $logger->info( json_encode($containerLockUnlock_1 ) );

        $boxLockUnlock_1 = [];
        // Search in containers
        if ($logger) $logger->info(' > Search in boxes');
        foreach ($this->containerBoxStruct['boxList'] as $box) {
            if( sizeof($box['listUAsInBasket']) == sizeof($box['listUAsInBDD']) )
                foreach( $box['listUAsInBasket'] as $ua )
                    if( $lock ) {
                        if (($ua['boxasked'] == 0) || ($ua['boxasked'] == 2))
                            $boxLockUnlock_1[] = $ua['id'];
                    } else {
                        if(( $ua['boxasked'] == 1 ) || ( $ua['boxasked'] == 3 ))
                            $boxLockUnlock_1[] = $ua['id'];
                    }
        }

        if( !empty( $containerLockUnlock_1 ) ){
            $doctrine->getRepository( 'bsIDPArchiveBundle:IDPArchive' )
                ->manageListOfContainerAsked( $containerLockUnlock_1, $lock, 1 );
        }
        if( !empty( $boxLockUnlock_1 ) ){
            $doctrine->getRepository( 'bsIDPArchiveBundle:IDPArchive' )
                ->manageListOfBoxAsked( $boxLockUnlock_1, $lock, 1 );
        }

        return true;
    }

    //----------------------------------------------------------------------------------------------------------------
    // Function called to verify if full container locked after basket selection are still full in Opti
    // detailledList && containerBoxStruct are filled with basket datas
    public function manage_Lost_FullContainerBox( $optiIDsList, $doctrine, $logger ){
        if ($logger) $logger->info('-> manage_Lost_FullContainerBox');
        if (!$this->detailledList || !$this->containerBoxStruct) {
            if ($logger) $logger->info(' > Structures not initialized');
            return false;
        }

        $tempContainerUnlock = [];
        if ($logger) $logger->info(' > Search in containers');
        foreach ($this->containerBoxStruct['containerList'] as $container) {
            if( sizeof($container['listUAsInBasket']) == sizeof($container['listUAsInBDD']) )
                foreach( $container['listUAsInBasket'] as $ua )
                    if( !in_array( $ua['id'], $optiIDsList ) ) {
                        $tempContainerUnlock = array_merge($tempContainerUnlock, $container['listUAsInBasket']);
                        break;  // we found one in this container, so could pass to next container
                    }
        }
        // Treat $tempContainerUnlock because it's a mess, only need ids
        $containerUnlock_1 = [];
        if( !empty($tempContainerUnlock) )
            foreach( $tempContainerUnlock as $ua )
                $containerUnlock_1[] = $ua['id'];

        $tempBoxUnlock = [];
        if ($logger) $logger->info(' > Search in box');
        foreach ($this->containerBoxStruct['boxList'] as $box) {
            if( sizeof($box['listUAsInBasket']) == sizeof($box['listUAsInBDD']) )
                foreach( $box['listUAsInBasket'] as $ua )
                    if( !in_array( $ua['id'], $optiIDsList ) ) {
                        $tempBoxUnlock = array_merge($tempBoxUnlock, $box['listUAsInBasket']);
                        break;  // we found one in this box, so could pass to next box
                    }
        }
        // Treat $tempBoxUnlock because it's a mess, only need ids
        $boxUnlock_1 = [];
        if( !empty($tempBoxUnlock) )
            foreach( $tempBoxUnlock as $ua )
                $boxUnlock_1[] = $ua['id'];

        // And now decrease these with 1
        if( !empty( $containerUnlock_1 ) ){
            $doctrine->getRepository( 'bsIDPArchiveBundle:IDPArchive' )
                ->manageListOfContainerAsked( $containerUnlock_1, false, 1 );
        }
        if( !empty( $boxUnlock_1 ) ){
            $doctrine->getRepository( 'bsIDPArchiveBundle:IDPArchive' )
                ->manageListOfBoxAsked( $boxUnlock_1, false, 1 );
        }
        return true;
    }

    //----------------------------------------------------------------------------------------------------------------
    // function called to verify if full container are asked during Optimization to remember for next time
    // detailledList && containerBoxStruct are filled with optimization datas
    public function manageUnmanage_FullContainerBox_InOptimization( $manage, $objects, $doctrine, $logger ){
        if ($logger) $logger->info('-> manage_FullContainerBox_InOptimization');
        if (!$this->detailledList || !$this->containerBoxStruct) {
            if ($logger) $logger->info(' > Structures not initialized');
            return false;
        }

        if( empty($objects) )   // Nothing to optimize, so everything went well :)
            return true;

        $containerOptiToLockUnlock = [];
        $boxOptiToLockUnlock = [];

        foreach( $this->detailledList as $ua ){
            if( $ua['containernumber'] ){
                if( in_array( 'C_'.$ua['containernumber'].'_'.$ua['service'], $objects ) )
                    if( $manage ) {
                        if ($ua['containerasked'] < 2)  // If 2 or 3 it's already set
                            $containerOptiToLockUnlock[] = $ua['id'];
                    } else {
                        if( $ua['containerasked'] >= 2 )  // If 0 or 1 it's already unset)
                            $containerOptiToLockUnlock[] = $ua['id'];
                    }
            } else if ( $ua['boxnumber'] ){
                if( in_array( 'B_'.$ua['boxnumber'].'_'.$ua['service'], $objects ) )
                    if( $manage ) {
                        if ($ua['boxasked'] < 2)
                            $boxOptiToLockUnlock[] = $ua['id'];
                    } else {
                        if( $ua['boxasked'] >= 2 )
                            $boxOptiToLockUnlock[] = $ua['id'];
                    }
            }
        }

        // And now decrease these with 1
        if( !empty( $containerOptiToLockUnlock ) ){
            $doctrine->getRepository( 'bsIDPArchiveBundle:IDPArchive' )
                ->manageListOfContainerAsked( $containerOptiToLockUnlock, $manage, 2 );
        }
        if( !empty( $boxOptiToLockUnlock ) ){
            $doctrine->getRepository( 'bsIDPArchiveBundle:IDPArchive' )
                ->manageListOfBoxAsked( $boxOptiToLockUnlock, $manage, 2 );
        }
        return true;
    }

    //========================================================================================================
    // Structure initialization function

    //----------------------------------------------------------------------------------------------------------------
    // This function reset all datas to null
    public function resetDatas(){
        $this->detailledList = null;
        $this->containerBoxStruct = null;
    }

    //----------------------------------------------------------------------------------------------------------------
    // -- retreive_AllDatasFor_IDsInList
    // This function get back all datas from BDD for IDs given in list
    public function retreive_AllDatasFor_IDsInList( $ids, $doctrine, $logger ){
        if( $logger ) $logger->info( '-> retreive_AllDatasFor_IDsInList ( '. json_encode($ids) . ')' );

        $this->detailledList = $doctrine->getRepository( 'bsIDPArchiveBundle:IDPArchive' )->getAllInListForArchivistVerification( $ids );

        return( !empty($this->detailledList) );
    }

    //----------------------------------------------------------------------------------------------------------------
    // -- create_ContainerBox_Structure --
    // This function create the containerBox structure needed for Exception06 calculation
    // Normally should be called after detailledList fill
    //
    // -> ids: list of IDs selected in Basket
    // -> listStatus: list of Status to restrict BDD research
    // <- containerBox structure
    public function create_ContainerBox_Structure( $ids, $listStatus, $doctrine, $logger )
    {
        if($logger) $logger->info('..............................................................................');
        if($logger) $logger->info('-> create_ContainerBox_Structure ( ' . json_encode($ids) . ')');

        if( empty($this->detailledList) )
            return false;
        if($logger) $logger->info(' > detailledList : ' . json_encode($this->detailledList) . ')');

        $containerList = [];
        $boxList = [];

        // Make structure based on basket contenance, first analyse basket, then BDD
        foreach( $this->detailledList as $detailledUA ) {
            if ($logger) $logger->info(' > Analyze this UA : ' . json_encode($detailledUA) );
            //..............................................................................
            // We are talking of containers
            if( !empty($detailledUA['containernumber']) ){
                // Make the SECIndexKey
                $SECIndexKey = "S_".$detailledUA['service'].
                    "|E_" . $detailledUA['serviceentrydate'] .
                    "|C_" . $detailledUA['containernumber'];
                if ($logger) $logger->info(' > We are talking of container : ' . json_encode($SECIndexKey) );

                // If key doesn't exist, create the empty structure for it
                if( !array_key_exists( $SECIndexKey, $containerList ) ) {
                    $containerList[$SECIndexKey] = $this->create_Empty_SECBSubStructure( $detailledUA['service'], $detailledUA['serviceentrydate'] );
                    $containerList[$SECIndexKey]['identification']['container'] = $detailledUA['containernumber'];
                }
                // In all case fill the structure accordingly
                $containerList[$SECIndexKey]['listUAsInBasket'][] = $detailledUA;
                // Verify if container is locked
                if( $detailledUA['containerasked'] & 1 )
                    $containerList[$SECIndexKey]['CBIsLocked'] = true;
                if( $detailledUA['containerasked'] & 2 )
                    $containerList[$SECIndexKey]['nbUAsOrderedBasket']++;

                if( empty($detailledUA['boxnumber']) && empty($detailledUA['documentnumber'] ) )
                    $containerList[$SECIndexKey]['oneUADocAndBoxIsNull'] = true;

                // Fill the Box Sub Struct
                $containerList[$SECIndexKey]['BList'] = $this->verify_BList_AndUpdate( $detailledUA, $containerList[$SECIndexKey], false, true, $logger );

                //if ($logger) $logger->info('  > BList After verify_BList_AndUpdate : ' . json_encode($containerList[$SECIndexKey]['BList']) );

            } else {
                //..............................................................................
                // We are talking of boxes
                if( !empty($detailledUA['boxnumber']) ){
                    // Make the $SEBIndexKey
                    $SEBIndexKey = "S_".$detailledUA['service'].
                        "|E_" . $detailledUA['serviceentrydate'] .
                        "|B_" . $detailledUA['boxnumber'];

                    // If key doesn't exist, create the empty structure for it
                    if( !array_key_exists( $SEBIndexKey, $boxList ) ){
                        $boxList[$SEBIndexKey] = $this->create_Empty_SECBSubStructure( $detailledUA['service'], $detailledUA['serviceentrydate'] );
                        $boxList[$SEBIndexKey]['identification']['box'] = $detailledUA['boxnumber'];
                    }
                    // In all case fill the structure accordingly
                    $boxList[$SEBIndexKey]['listUAsInBasket'][] = $detailledUA;
                    // Verify if Box is locked
                    if( $detailledUA['boxasked'] & 1 )
                        $boxList[$SEBIndexKey]['CBIsLocked'] = true;
                    if( $detailledUA['boxasked'] & 2 )
                        $boxList[$SEBIndexKey]['nbUAsOrderedBasket']++;
                    // Fill the Box sub struct
                    $boxList[$SEBIndexKey]['BList'] = $this->verify_BList_AndUpdate( $detailledUA, $boxList[$SEBIndexKey], true, true, $logger );

                    //if ($logger) $logger->info('  > BList After verify_BList_AndUpdate : ' . json_encode($containerList[$SEBIndexKey]['BList']) );

                } else {
                    //..............................................................................
                    // What we are talking about ! Alone Documents
                }
            }
        }


        if($logger) $logger->info('..............................................................................');
        if ($logger) $logger->info(' > $containerList After Basket Analysis : ' . json_encode($containerList) );
        if ($logger) $logger->info(' > $boxList After Basket Analysis : ' . json_encode($boxList) );
        if($logger) $logger->info('..............................................................................');


        // Now we have analysed basket, time to search in BDD to fill the structure with datas
        //if ($logger) $logger->info(' > search in BDD for containers');
        foreach ( $containerList as $key => $item ){
            $itemId = $item['identification'];
            if ($logger) $logger->info('  * container: '.json_encode($itemId) );

            $UAsInBDD = $doctrine->getRepository( 'bsIDPArchiveBundle:IDPArchive' )
                ->getUasWhereSameContainerOrBox( $itemId['container'], null, $itemId['serviceID'], $itemId['serviceEntryDate'], $listStatus );
            if($logger) $logger->info('  * found and parse => '. json_encode($UAsInBDD) );

            foreach( $UAsInBDD as $ua ){
                $containerList[$key]['BList'] = $this->verify_BList_AndUpdate( $ua, $containerList[$key], false, false, $logger );

                $containerList[$key]['listUAsInBDD'][] = $ua;

                if( $ua['containerasked'] & 1 )
                    $containerList[$key]['CBIsLocked'] = true;
                if( $ua['containerasked'] & 2 )
                    $containerList[$key]['nbUAsOrderedBDD']++;

                if( empty($ua['boxnumber']) && empty($ua['documentnumber'] ) )
                    $containerList[$key]['oneUADocAndBoxIsNull'] = true;

                if($logger) $logger->info('  * parse ua '. json_encode($ua) );
                if($logger) $logger->info('  * result => '. json_encode($containerList[$key]) );
            }

        }
        //if ($logger) $logger->info(' > search in BDD for boxes');
        foreach ( $boxList as $key => $item ){
            $itemId = $item['identification'];
            $UAsInBDD = $doctrine->getRepository( 'bsIDPArchiveBundle:IDPArchive' )
                ->getUasWhereSameContainerOrBox( null, $itemId['box'], $itemId['serviceID'], $itemId['serviceEntryDate'], $listStatus );

            foreach( $UAsInBDD as $ua ){
                $boxList[$key]['BList'] = $this->verify_BList_AndUpdate( $ua, $boxList[$key], true, false, $logger );

                $boxList[$key]['listUAsInBDD'][] = $ua;

                if( $ua['boxasked'] & 1 )
                    $boxList[$key]['CBIsLocked'] = true;
                if( $ua['boxasked'] & 2 )
                    $boxList[$key]['nbUAsOrderedBDD']++;
            }
        }

        $this->containerBoxStruct = [ 'containerList' => $containerList, 'boxList' => $boxList ];
        return true;
    }

    //..............................................................................................................
    // create_Empty_SECBSubStructure
    private function create_Empty_SECBSubStructure( $service, $serviceEntryDate ){
        return [
            'identification' => [                       // Identification of container (or box when container is null)
                'container' => null,                        // only for containers
                'box' => null,                              // only for boxes
                'serviceID' => $service,                    // ID of service
                'serviceEntryDate' => $serviceEntryDate     // Date (ms) of entry
            ],
            'listUAsInBasket'=> [],                     // List of UAs in this container (box) already in Basket
            'listUAsInBDD' => [],                       // List of UAs in this container in BDD
            'BList' => [],                              // Box list for containers
            'oneUADocAndBoxIsNull' => false,            // Found at least one UAs where doc and box ref are null
            'CBIsLocked' => false,                      // This Container (or Box) is locked ie it is asked completely
            'nbUAsOrderedBasket' => 0,                  // Count nb UAs with containerAsked == 2 (or BoxAsked == 2 (if Box)) in basket
            'nbUAsOrderedBDD' => 0                      // Count nb UAs with containerAsked == 2 (or BoxAsked == 2 (if Box)) in BDD
        ];
    }
    //.............................................................................................................
    private function create_Empty_BSubStructure( ){
        return [
            'oneUADocIsNull' => false,              // In Box, there is at least one ua with Doc ref null
            'UAsListInBasket' => [],                // List of uas of this box already inside Basket
            'UAsListInBDD' => [],                   // List of uas of this box in BDD
            'BoxIsLocked' => false,                 // Sub Box is locked ie it is asked completely
            'nbUAsOrderedBasket' => 0,              // Count nb UAs with boxAsked == 2 in basket
            'nbUAsOrderedBDD' => 0                  // Count nb UAs with boxAsked == 2 in BDD
        ];
    }
    //.............................................................................................................
    private function create_B_Index( $box ){
        return (($box!=null) ? "B_".$box : "EMPTY");
    }
    //.............................................................................................................
    // isBox => true = box, false = container
    private function verify_BList_AndUpdate( $ua, $SECB, $isBox, $isBasket, $logger ){
        if ($logger) $logger->info('-> verify_BList_AndUpdate ');
        /*
        if ($logger) $logger->info(' > $ua : '.json_encode($ua));
        if ($logger) $logger->info(' > $SECB : '.json_encode($SECB));
        if ($logger) $logger->info(' > $isBasket : '.json_encode($isBasket));
        */

        $BList = $SECB['BList'];
        if( empty($ua['boxnumber']) && empty($ua['documentnumber'] )) {
            return $BList;
        }

        $BIndex = $this->create_B_Index( $ua['boxnumber'] );
        if ($logger) $logger->info(' > BIndex: ['.$BIndex.']' );
        if( !array_key_exists( $BIndex, $BList ) ){
            //if ($logger) $logger->info('   Not found BDIndex in BList: '.json_encode($BList) );
            $BList[$BIndex] = $this->create_Empty_BSubStructure();
        }

        //if ($logger) $logger->info('   Add UA in structure' );
        if( $isBasket )
            $BList[$BIndex]['UAsListInBasket'][] = $ua;
        else
            $BList[$BIndex]['UAsListInBDD'][] = $ua;

        //if ($logger) $logger->info('   Manage oneUADocIsNull and oneUADocAndBoxIsNull' );
        if( empty($ua['documentnumber'] ) ) {
            $BList[$BIndex]['oneUADocIsNull'] = true;
        }

        // Verify only if container call
        if( !$isBox ) {
            // Verify if full box is asked
            if(( $ua['boxasked'] == 1 ) || ( $ua['boxasked'] == 3 ))       // 3 == 1 + 2
                $BDList[$BIndex]['BoxIsLocked'] = true;

            // Verify if this uas have precedence
            if(( $ua['boxasked'] == 2 ) || ( $ua['boxasked'] == 3 )) {
                if( $isBasket )
                    $BDList[$BIndex]['nbUAsOrderedBasket']++;
                else
                    $BDList[$BIndex]['nbUAsOrderedBDD']++;
            }
        }

        return $BList;
    }


    //========================================================================================================
    // Datas configuration Functions

    //----------------------------------------------------------------------------------------------------------------
    // This function return the statuses to keep in basket for Exception 2 based on state, what and where. To keep all return null
    public function getKeepStatusForBasketException02( $UAState, $UAWhat, $UAWhere ){
        if ( $UAState == IDPConstants::UASTATE_MANAGEUSERWANTS && $UAWhat == IDPConstants::UAWHAT_DESTROY )
            return ['CDAP'];
        if ( $UAState == IDPConstants::UASTATE_CLOSEUSERWANTS && $UAWhat == IDPConstants::UAWHAT_DESTROY )
            return ['CDEP'];
        if ( $UAState == IDPConstants::UASTATE_MANAGEUSERWANTS && $UAWhat == IDPConstants::UAWHAT_RELOC && $UAWhere == IDPConstants::UAWHERE_INTERNAL )
            return ['CRLIDAI', 'CRLINTDAI'];
        if ( $UAState == IDPConstants::UASTATE_MANAGEUSERWANTS && $UAWhat == IDPConstants::UAWHAT_RELOC && $UAWhere == IDPConstants::UAWHERE_INTERMEDIATE )
            return ['CRLIDAINT', 'CRLINTDAINT'];
        if ( $UAState == IDPConstants::UASTATE_CLOSEUSERWANTS && $UAWhat == IDPConstants::UAWHAT_RELOC && $UAWhere == IDPConstants::UAWHERE_INTERNAL )
            return ['CRLIDI', 'CRLINTDI'];
        if ( $UAState == IDPConstants::UASTATE_CLOSEUSERWANTS && $UAWhat == IDPConstants::UAWHAT_RELOC && $UAWhere == IDPConstants::UAWHERE_INTERMEDIATE )
            return ['CRLINTDI', 'CRLINTDINT'];

        return null;
    }

    //----------------------------------------------------------------------------------------------------------------
    // This function returns the exceptions to run for a triplet (state, what, where)
    public function get_Exceptions_Schema( $UAState, $UAWhat, $UAWhere ){
        if( $UAState == IDPConstants::UASTATE_MANAGEUSERWANTS ) {
            if( $UAWhat == IDPConstants::UAWHAT_TRANSFER )
                return ['E02'];
            if( $UAWhat == IDPConstants::UAWHAT_CONSULT )
                return ['E06'];
            if( $UAWhat == IDPConstants::UAWHAT_RETURN )
                return ['E08'];
            if( $UAWhat == IDPConstants::UAWHAT_EXIT )
                return ['E06'];
            if( $UAWhat == IDPConstants::UAWHAT_DESTROY )
                return ['E06', 'E02'];
            if( $UAWhat == IDPConstants::UAWHAT_RELOC ) {
                if( $UAWhere == IDPConstants::UAWHERE_INTERNAL )
                    return ['E06', 'E02'];
                if( $UAWhere == IDPConstants::UAWHERE_INTERMEDIATE )
                    return ['E06', 'E02'];
                if( $UAWhere == IDPConstants::UAWHERE_PROVIDER )
                    return ['E02'];
            }
        }

        if( $UAState == IDPConstants::UASTATE_MANAGEPROVIDER ) {
            if( $UAWhat == IDPConstants::UAWHAT_TRANSFER )
                return ['E05', 'E02'];
            if( $UAWhat == IDPConstants::UAWHAT_CONSULT )
                return ['E06'];
            if( $UAWhat == IDPConstants::UAWHAT_RETURN )
                return ['E08'];
            if( $UAWhat == IDPConstants::UAWHAT_EXIT )
                return ['E06'];
            if( $UAWhat == IDPConstants::UAWHAT_DESTROY )
                return ['E02'];
            if( $UAWhat == IDPConstants::UAWHAT_RELOC ) {
                if( $UAWhere == IDPConstants::UAWHERE_TRANSFER )
                    return ['E05', 'E02'];
                if( $UAWhere == IDPConstants::UAWHERE_CONSULT )
                    return ['E06'];
            }
        }

        if( $UAState == IDPConstants::UASTATE_CLOSEUSERWANTS ) {
            if ($UAWhat == IDPConstants::UAWHAT_TRANSFER) {
                if( $UAWhere == IDPConstants::UAWHERE_INTERNAL )
                    return ['E02'];
                if( $UAWhere == IDPConstants::UAWHERE_INTERMEDIATE )
                    return ['E02'];
                if( $UAWhere == IDPConstants::UAWHERE_PROVIDER )
                    return ['E05', 'E02'];
            }
            if ($UAWhat == IDPConstants::UAWHAT_CONSULT)
                return ['E06', 'E07'];
            if ($UAWhat == IDPConstants::UAWHAT_RETURN)
                return ['E07', 'E08'];
            if ($UAWhat == IDPConstants::UAWHAT_EXIT)
                return ['E06', 'E07'];
            if ($UAWhat == IDPConstants::UAWHAT_DESTROY)
                return ['E06', 'E02'];
            if ($UAWhat == IDPConstants::UAWHAT_RELOC) {
                if( $UAWhere == IDPConstants::UAWHERE_INTERNAL )
                    return ['E06', 'E07', 'E02'];
                if( $UAWhere == IDPConstants::UAWHERE_INTERMEDIATE )
                    return ['E06', 'E07', 'E02'];
                if( $UAWhere == IDPConstants::UAWHERE_PROVIDER )
                    return ['E05', 'E02'];
            }
        }

        return null;
    }

    //----------------------------------------------------------------------------------------------------------------
    // This function returns the statuses of search for exceptions except n°2
    // null is all statuses (no perimeter)
    public function get_Exceptions_Perimeter( $UAState, $UAWhat, $UAWhere ){
        if(( $UAWhat == IDPConstants::UAWHAT_RELOC ) ||
            ( $UAWhat == IDPConstants::UAWHAT_DESTROY ) ||
            ( $UAWhat == IDPConstants::UAWHAT_CONSULT ))
        return null;    // Search everywhere

        return null;
    }

    //----------------------------------------------------------------------------------------------------------------
    // This function returns the statuses of search for exception n°2
    public function get_Exception02_Perimeter( $UAState, $UAWhat, $UAWhere ){
        // Transfer
        if( $UAState == IDPConstants::UASTATE_MANAGEUSERWANTS && $UAWhat == IDPConstants::UAWHAT_TRANSFER ) // All UAWhere have same perimeter
            return ['DTA', 'DTRI', 'DTRINT', 'DTRP'];
        if( $UAState == IDPConstants::UASTATE_MANAGEPROVIDER && $UAWhat == IDPConstants::UAWHAT_TRANSFER )
            return ['DTA', 'DTRI', 'DTRINT', 'GDTRP', 'GDTRP'];
        if( $UAState == IDPConstants::UASTATE_CLOSEUSERWANTS && $UAWhat == IDPConstants::UAWHAT_TRANSFER )
            return ['DTA', 'DTRI', 'DTRINT', 'DTRP', 'GDTRP', 'CDTRI', 'CDTRINT', 'CDTRP'];

        // Destroy
        if( $UAWhat == IDPConstants::UAWHAT_DESTROY )
            return null;    // null = everywhere

        // Consult
        // Return
        // Exit
        // ==> No Exception02

        // Reloc
        if( $UAWhat == IDPConstants::UAWHAT_RELOC ) // All except Transfer
            return ['DISI', 'DISINT', 'DISP',
                'CLAI', 'CPAI', 'CLAINT', 'CPAINT', 'CLAP', 'CPAP', 'GLAP', 'GPAP', 'CLII', 'CPRI', 'CLIINT', 'CPRINT', 'CLIP', 'CPRP',
                'CONI', 'CONINT', 'CONP',
                'CRAI', 'CRAINT', 'CRAP', 'GRAP', 'CRTI', 'CRTINT', 'CRTP',
                'CDAI', 'CDAINT', 'CDAP', 'GDAP', 'CDEI', 'CDEINT', 'CDEP',
                'CSAI', 'CSAINT', 'CSAP', 'GSAP', 'CSDI', 'CSDINT', 'CSDP',
                'CRLIDAINT', 'CRLIDAP', 'CRLIDAI', 'GRLIDAP', 'CRLIDINT', 'CRLIDP', 'CRLIDI',
                'CRLINTDAI', 'CRLINTDAP', 'CRLINTDAINT', 'GRLINTDAP', 'CRLINTDI', 'CRLINTDP', 'CRLINTDINT',
                'CRLPDAI', 'CRLPDAINT', 'GRLPDAI', 'GRLPDAINT', 'CRLPDI', 'CRLPDINT', 'CONRIDISP', 'CONRINTDISP',
                'CRLICAI', 'CRLICAINT', 'CRLICI', 'CRLICINT',
                'CRLINTCAINT', 'CRLINTCAI', 'CRLINTCINT', 'CRLINTCI',
                'CRLPCAI', 'CRLPCAINT', 'CRLPCI', 'CRLPCINT', 'CONRICONP', 'CONRINTCONP',
                'CRAPCONRIDISP', 'CRAPCONRINTDISP', 'GRAPCONRIDISP', 'GRAPCONRINTDISP', 'CRTPCONRIDISP', 'CRTPCONRINTDISP',
                'CRAPCONRICONP', 'CRAPCONRINTCONP', 'GRAPCONRICONP', 'GRAPCONRINTCONP', 'CRTPCONRICONP', 'CRTPCONRINTCONP'
                ];

        return null;
    }


    //========================================================================================================
    // Error messages

    // Exception 05
    public function get_Exception05_ErrorMessage( $UAState, $UAWhat, $UAWhere ){
        // Manage provider - Transfer - Provider
        // Close - Transfer - Provider
        if(( $UAState == IDPConstants::UASTATE_MANAGEPROVIDER && $UAWhat == IDPConstants::UAWHAT_TRANSFER && $UAWhere == IDPConstants::UAWHERE_PROVIDER ) ||
            ( $UAState == IDPConstants::UASTATE_CLOSEUSERWANTS && $UAWhat == IDPConstants::UAWHAT_TRANSFER && $UAWhere == IDPConstants::UAWHERE_PROVIDER ))
            return "Au moins une unité d'archives n'a pas de numéro d'identifiant de contenant, souhaitez-vous quand même effectuer le transfert ?";

        // Manage Provider - Reloc - Transfer
        // Close Provider - Reloc - Provider
        if(( $UAState == IDPConstants::UASTATE_MANAGEPROVIDER && $UAWhat == IDPConstants::UAWHAT_RELOC && $UAWhere == IDPConstants::UAWHERE_TRANSFER ) ||
            ( $UAState == IDPConstants::UASTATE_CLOSEUSERWANTS && $UAWhat == IDPConstants::UAWHAT_RELOC && $UAWhere == IDPConstants::UAWHERE_PROVIDER ))
            return "Au moins une unité d'archives n'a pas de numéro d'identifiant de contenant, souhaitez-vous quand même effectuer la relocalisation ?";

        // Default message
        return "Exception 05 - Default Error Message";
    }

    // Exception 06
    public function get_Exception06_ErrorMessage( $UAState, $UAWhat, $UAWhere ){
        if(( $UAState == IDPConstants::UASTATE_MANAGEUSERWANTS && $UAWhat == IDPConstants::UAWHAT_DESTROY ) ||
            ( $UAState == IDPConstants::UASTATE_CLOSEUSERWANTS && $UAWhat == IDPConstants::UAWHAT_DESTROY ))
            return "Une des demandes concerne un contenant avec une identification incomplète. Vous devez sélectionner la totalité du contenant pour détruire les archives.";

        if(( $UAState == IDPConstants::UASTATE_MANAGEUSERWANTS && $UAWhat == IDPConstants::UAWHAT_CONSULT && $UAWhere == IDPConstants::UAWHERE_WITHOUTPREPARATION ) ||
            ( $UAState == IDPConstants::UASTATE_MANAGEPROVIDER && $UAWhat == IDPConstants::UAWHAT_CONSULT ) ||
            ( $UAState == IDPConstants::UASTATE_CLOSEUSERWANTS && $UAWhat == IDPConstants::UAWHAT_CONSULT && $UAWhere == IDPConstants::UAWHERE_WITHOUTPREPARATION ) ||
            ( $UAState == IDPConstants::UASTATE_MANAGEUSERWANTS && $UAWhat == IDPConstants::UAWHAT_CONSULT && $UAWhere == IDPConstants::UAWHERE_WITHPREPARATION ) ||
            ( $UAState == IDPConstants::UASTATE_CLOSEUSERWANTS && $UAWhat == IDPConstants::UAWHAT_CONSULT && $UAWhere == IDPConstants::UAWHERE_WITHPREPARATION ))
            return "Une des demandes concerne un contenant avec une identification incomplète. Vous devez sélectionner la totalité du contenant pour consulter les archives.";

        if(( $UAState == IDPConstants::UASTATE_MANAGEUSERWANTS && $UAWhat == IDPConstants::UAWHAT_EXIT ) ||
            ( $UAState == IDPConstants::UASTATE_MANAGEPROVIDER && $UAWhat == IDPConstants::UAWHAT_EXIT ) ||
            ( $UAState == IDPConstants::UASTATE_CLOSEUSERWANTS && $UAWhat == IDPConstants::UAWHAT_EXIT ))
            return "Une des demandes concerne un contenant avec une identification incomplète. Vous devez sélectionner la totalité du contenant pour sortir définitivement les archives.";

        if(( $UAState == IDPConstants::UASTATE_MANAGEUSERWANTS && $UAWhat == IDPConstants::UAWHAT_RELOC && $UAWhere == IDPConstants::UAWHERE_INTERNAL ) ||
            ( $UAState == IDPConstants::UASTATE_MANAGEUSERWANTS && $UAWhat == IDPConstants::UAWHAT_RELOC && $UAWhere == IDPConstants::UAWHERE_INTERMEDIATE ) ||
            ( $UAState == IDPConstants::UASTATE_MANAGEPROVIDER && $UAWhat == IDPConstants::UAWHAT_RELOC && $UAWhere == IDPConstants::UAWHERE_CONSULT ) ||
            ( $UAState == IDPConstants::UASTATE_CLOSEUSERWANTS && $UAWhat == IDPConstants::UAWHAT_RELOC && $UAWhere == IDPConstants::UAWHERE_INTERNAL ) ||
            ( $UAState == IDPConstants::UASTATE_CLOSEUSERWANTS && $UAWhat == IDPConstants::UAWHAT_RELOC && $UAWhere == IDPConstants::UAWHERE_INTERMEDIATE ))
            return "Une des demandes concerne un contenant avec une identification incomplète. Vous devez sélectionner la totalité du contenant pour relocaliser les archives.";

        // Default message
        return "Exception 06 - Default Error Message";
    }

    // Exception 02
    public function get_Exception02_ErrorMessage( $UAState, $UAWhat, $UAWhere ){
        if(( $UAState == IDPConstants::UASTATE_MANAGEUSERWANTS && $UAWhat == IDPConstants::UAWHAT_TRANSFER && $UAWhere == IDPConstants::UAWHERE_INTERNAL ) ||
            ( $UAState == IDPConstants::UASTATE_CLOSEUSERWANTS && $UAWhat == IDPConstants::UAWHAT_TRANSFER && $UAWhere == IDPConstants::UAWHERE_INTERNAL ) ||
            ( $UAState == IDPConstants::UASTATE_MANAGEUSERWANTS && $UAWhat == IDPConstants::UAWHAT_TRANSFER && $UAWhere == IDPConstants::UAWHERE_INTERMEDIATE ) ||
            ( $UAState == IDPConstants::UASTATE_CLOSEUSERWANTS && $UAWhat == IDPConstants::UAWHAT_TRANSFER && $UAWhere == IDPConstants::UAWHERE_INTERMEDIATE ) ||
            ( $UAState == IDPConstants::UASTATE_MANAGEUSERWANTS && $UAWhat == IDPConstants::UAWHAT_TRANSFER && $UAWhere == IDPConstants::UAWHERE_PROVIDER ) ||
            ( $UAState == IDPConstants::UASTATE_MANAGEPROVIDER && $UAWhat == IDPConstants::UAWHAT_TRANSFER ) ||
            ( $UAState == IDPConstants::UASTATE_CLOSEUSERWANTS && $UAWhat == IDPConstants::UAWHAT_TRANSFER && $UAWhere == IDPConstants::UAWHERE_PROVIDER ))
            return "Vous devez sélectionner la totalité du contenant pour transférer les archives.";

        if(( $UAState == IDPConstants::UASTATE_MANAGEUSERWANTS && $UAWhat == IDPConstants::UAWHAT_DESTROY ) ||
            ( $UAState == IDPConstants::UASTATE_MANAGEPROVIDER && $UAWhat == IDPConstants::UAWHAT_DESTROY ) ||
            ( $UAState == IDPConstants::UASTATE_CLOSEUSERWANTS && $UAWhat == IDPConstants::UAWHAT_DESTROY ))
            return "Une des demandes concerne un contenant complet. Vous devez sélectionner la totalité du contenant pour détruire les archives.";

        if(( $UAState == IDPConstants::UASTATE_MANAGEUSERWANTS && $UAWhat == IDPConstants::UAWHAT_RELOC && $UAWhere == IDPConstants::UAWHERE_INTERNAL ) ||
            ( $UAState == IDPConstants::UASTATE_MANAGEUSERWANTS && $UAWhat == IDPConstants::UAWHAT_RELOC && $UAWhere == IDPConstants::UAWHERE_INTERMEDIATE ) ||
            ( $UAState == IDPConstants::UASTATE_MANAGEUSERWANTS && $UAWhat == IDPConstants::UAWHAT_RELOC && $UAWhere == IDPConstants::UAWHERE_PROVIDER ) ||
            ( $UAState == IDPConstants::UASTATE_MANAGEPROVIDER && $UAWhat == IDPConstants::UAWHAT_RELOC && $UAWhere == IDPConstants::UAWHERE_TRANSFER ) ||
            ( $UAState == IDPConstants::UASTATE_CLOSEUSERWANTS && $UAWhat == IDPConstants::UAWHAT_RELOC && $UAWhere == IDPConstants::UAWHERE_INTERNAL ) ||
            ( $UAState == IDPConstants::UASTATE_CLOSEUSERWANTS && $UAWhat == IDPConstants::UAWHAT_RELOC && $UAWhere == IDPConstants::UAWHERE_INTERMEDIATE ) ||
            ( $UAState == IDPConstants::UASTATE_CLOSEUSERWANTS && $UAWhat == IDPConstants::UAWHAT_RELOC && $UAWhere == IDPConstants::UAWHERE_PROVIDER ))
            return "Vous devez sélectionner la totalité du contenant pour relocaliser les archives.";

        // Default message
        return "Exception 02 - Default Error Message";
    }

    // Exception 07
    public function get_Exception07_ErrorMessage( $UAState, $UAWhat, $UAWhere ){
        if( $UAState == IDPConstants::UASTATE_CLOSEUSERWANTS && $UAWhat == IDPConstants::UAWHAT_CONSULT ) // Both with or without preparation
            return "Une des demandes concerne un contenant complet. Vous devez sélectionner la totalité du contenant pour consulter les archives.";

        if( $UAState == IDPConstants::UASTATE_CLOSEUSERWANTS && $UAWhat == IDPConstants::UAWHAT_RETURN )
            return "Une des demandes concerne un contenant complet. Vous devez sélectionner la totalité du contenant pour retourner les archives.";

        if( $UAState == IDPConstants::UASTATE_CLOSEUSERWANTS && $UAWhat == IDPConstants::UAWHAT_EXIT )
            return "Une des demandes concerne un contenant complet. Vous devez sélectionner la totalité du contenant pour sortir définitivement les archives.";

        if(( $UAState == IDPConstants::UASTATE_CLOSEUSERWANTS && $UAWhat == IDPConstants::UAWHAT_RELOC && $UAWhere == IDPConstants::UAWHERE_INTERNAL ) ||
            ( $UAState == IDPConstants::UASTATE_CLOSEUSERWANTS && $UAWhat == IDPConstants::UAWHAT_RELOC && $UAWhere == IDPConstants::UAWHERE_INTERMEDIATE ))
            return "Une des demandes concerne un contenant complet. Vous devez sélectionner la totalité du contenant pour relocaliser les archives.";

        // Default message
        return "Exception 07 - Default Error Message";
    }

    // Exception 08
    public function get_Exception08_ErrorMessage( $UAState, $UAWhat, $UAWhere ){
        if(( $UAState == IDPConstants::UASTATE_MANAGEUSERWANTS && $UAWhat == IDPConstants::UAWHAT_RETURN ) ||
            ( $UAState == IDPConstants::UASTATE_MANAGEPROVIDER && $UAWhat == IDPConstants::UAWHAT_RETURN ) ||
            ( $UAState == IDPConstants::UASTATE_CLOSEUSERWANTS && $UAWhat == IDPConstants::UAWHAT_RETURN ))
            return "Vous devez d'abord retourner le contenant le plus grand, c'est-à-dire celui dans lequel étaient contenues les unités d'archives plus petites, avant de pouvoir retourner le reste des éléments.";


        // Default message
        return "Exception 08 - Default Error Message";
    }


}