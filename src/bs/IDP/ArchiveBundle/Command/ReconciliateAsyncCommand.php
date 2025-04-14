<?php

// src/bs/IDP/ArchiveBundle/Command/ImportAsyncCommand.php
namespace bs\IDP\ArchiveBundle\Command;

use bs\IDP\ArchiveBundle\Controller\ReconciliationController;
use \DateTime;

use MongoDB\BSON\Timestamp;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use bs\IDP\ArchiveBundle\Entity\IDPReconciliation;
use bs\IDP\ArchiveBundle\Entity\IDPReconciliationComm;
use bs\IDP\ArchiveBundle\Entity\IDPReconciliationFile;

use \xlswriter\XLSXWriter;

class ReconciliateAsyncCommand extends ContainerAwareCommand
{
    // IN: If at least 1 UA with status IN_STATUS or all UAs flag OFF
    //     (FR): Au moins 1 DISP OU tous les Flags OFF
    const IN_STATUS = ['DISP'];
    // OUT: If no UA with status OUT_STATUS and at least 1 UA flag ON
    //      (FR) Aucun DISP et au moins 1 flag ON
    const OUT_STATUS = ['DISP'];
    // DESTROY: If all UAs with status DESTROY_STATUS
    //          (FR) tous DESTROY
    const DESTROY_STATUS = ['EDEP'];
    // EXIT: If all UAs with status EXIT_STATUS
    //       (FR) tous EXIT
    const EXIT_STATUS = ['ESDP'];

    const IODE_UNKNOWN = 0;
    const IODE_IN = 1;
    const IODE_OUT = 2;
    const IODE_DESTROY = 3;
    const IODE_EXIT = 4;

    const IODE_STR = [ 'UNKNOWN', 'IN', 'OUT', 'DESTROY', 'EXIT' ];

    const CBD_CONTAINER = 0;
    const CBD_BOX = 1;
    const CBD_DOCUMENT = 2;

    protected function configure()
    {
        $this
            ->setName('app:reconciliate-file')
            ->setDescription('Reconciliate a file with database')
            ->addArgument('localization', InputArgument::REQUIRED, 'id of localization to compute the file')
            ->addArgument('filename', InputArgument::REQUIRED, 'name of file to analyse, must be in web/import/archimage dir')
            ->addArgument('debug', InputArgument::OPTIONAL, 'debug mode activated')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $localizationId = $input->getArgument('localization');
        $filename = $input->getArgument('filename');
        $debugMode = ($input->getArgument('debug')?true:false);

        if( $debugMode ){
            $output->writeln( '<info>--------------------------------</info>' );
            $output->writeln( '<info>Archimage Reconciliation Process</info>' );
            $output->writeln( '<info>--------------------------------</info>' );
            $output->writeln( '' );
        }

        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getManager();

        $globalStatuses = $doctrine->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses')->findOneBy(array('id' => 1));

        //..............................................................................................................
        if( $debugMode ) $output->writeln( '<info>== Verifications ==</info>' );

        // Verify Reconciliation in progress
        if( $debugMode ) $output->write( 'Verification reconciliation in progress' );
        // Continue only if globalstatus exist and in file uploaded mode or in debug mode and no reconciliation
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() != 51 ) // Should occur only after upload file
                if( !$debugMode || $globalStatuses->getReconciliationInProgress() != 0 ){ // For DebugMode, we consider file is uploaded
                    if( $debugMode ) $output->writeln( ' ==> <error>KO</error>' );
                    return 200; }
        if( $debugMode ) $output->writeln( ' ==> <info>OK</info>' );

        $this->updateGlobalStatus( $globalStatuses, IDPReconciliation::VERIFICATION_IN_PROGRESS, $em );
        $reconciliation = $doctrine->getRepository('bsIDPArchiveBundle:IDPReconciliation')->findOneBy(array('id' => $globalStatuses->getCurrentReconciliationId() ));

        // Verify import
        if( $debugMode ) $output->write( 'Verification import in progress' );
        if( $globalStatuses && $globalStatuses->getImportInProgress() || $globalStatuses->getCancelInProgress() ){
            if( $debugMode ) $output->writeln( ' ==> <error>KO</error>' );
            return 201; }
        if( $debugMode ) $output->writeln( ' ==> <info>OK</info>' );

        // Verify localization parameter coherency
        if( $debugMode ) $output->write( 'Verification localization parameter coherency' );
        $localization = $doctrine->getRepository('bsIDPBackofficeBundle:IDPLocalizations')->findOneBy(array('id' => $localizationId ));
        if( !$localization ){
            if( $debugMode ) $output->writeln( ' ==> <error>KO</error>' );
            $this->updateGlobalStatus( $globalStatuses, IDPReconciliation::ERROR_LOCALIZATION_WRONG, $em );
            return 202; }
        if( $debugMode ) $output->writeln( ' ==> <info>OK</info>' );

        // Verify file existence
        if( $debugMode ) $output->write( 'Verify file existence' );
        $fullName = __DIR__.'/../../../../../web/import/' . $filename;
        if( !file_exists( $fullName ) ){
            if( $debugMode ) $output->writeln( ' ==> <error>KO</error>' );
            $this->updateGlobalStatus( $globalStatuses, IDPReconciliation::ERROR_FILE_NOT_FOUND, $em );
            return 203; }
        if( $debugMode ) $output->writeln( ' ==> <info>OK</info>' );

        // Verify file structure (only verify it's a text file, structure of each line is verified during process)
        if( $debugMode ) $output->write( 'Verify file type' );
        if( mime_content_type( $fullName ) != "text/plain" ){
            if( $debugMode ) $output->writeln( ' ==> <error>KO</error>' );
            $this->updateGlobalStatus( $globalStatuses, IDPReconciliation::ERROR_FILE_IS_NOT_TEXT, $em );
            return 204; }
        if( $debugMode ) $output->writeln( ' ==> <info>OK</info>' );

        // Get the fileSize to calculate percent
        if( $debugMode ) $output->write( 'Verify file size' );
        $fileSize = filesize( $fullName );
        if( $fileSize <= 0 ){
            if( $debugMode ) $output->writeln( ' ==> <error>KO</error>' );
            $this->updateGlobalStatus( $globalStatuses, IDPReconciliation::ERROR_FILE_EMPTY, $em );
            return 205; }
        if( $debugMode ) $output->writeln( ' ==> <info>OK</info>' );

        //..............................................................................................................
        // Copy Tables (IDPArchive & IDPDeletedArchive)
        if( $debugMode ) $output->writeln( '<info>== Tables copy ==</info>' );

        $this->updateGlobalStatus( $globalStatuses, IDPReconciliation::DATABASE_COPY_IN_PROGRESS, $em );
        // Verify if tables copies are not already present
        if( $debugMode ) $output->write( 'Verify if IDPArchiveCopy is not already there' );
        $sqlReturn = $this->executeSQLQuery( 'SHOW TABLES LIKE "IDPArchiveCopy";', $em );
        if( !$sqlReturn || $sqlReturn->rowCount() != 0 ){
            if( $debugMode ) $output->writeln( ' ==> <error>KO</error>' );
            $this->updateGlobalStatus( $globalStatuses, IDPReconciliation::ERROR_DATABASE_COPY_ALREADY_EXISTS, $em );
            return 206; }
        if( $debugMode ) $output->writeln( ' ==> <info>OK</info>' );

        if( $debugMode ) $output->write( 'Verify if IDPDeletedArchiveCopy is not already there' );
        $sqlReturn = $this->executeSQLQuery( 'SHOW TABLES LIKE "IDPDeletedArchiveCopy";', $em );
        if( !$sqlReturn || $sqlReturn->rowCount() != 0 ){
            if( $debugMode ) $output->writeln( ' ==> <error>KO</error>' );
            $this->updateGlobalStatus( $globalStatuses, IDPReconciliation::ERROR_DATABASE_COPY_ALREADY_EXISTS, $em );
            return 207; }
        if( $debugMode ) $output->writeln( ' ==> <info>OK</info>' );

        // Create Empty IDPArchiveCopy and copy all datas from IDPArchive into it
        if( $debugMode ) $output->write( 'Create IDPArchiveCopy' );
        if( !$this->executeSQLQuery('CREATE TABLE `IDPArchiveCopy` LIKE `IDPArchive`;', $em ) ){
            if( $debugMode ) $output->writeln( ' ==> <error>KO</error>' );
            $this->updateGlobalStatus( $globalStatuses, IDPReconciliation::ERROR_DATABASE_COPY, $em );
            return 208; }
        if( $debugMode ) $output->writeln( ' ==> <info>OK</info>' );

        if( $debugMode ) $output->write( 'Copy all datas from IDPArchive into it' );
        if( !$this->executeSQLQuery('INSERT INTO `IDPArchiveCopy` SELECT * FROM `IDPArchive`;', $em ) ){
            if( $debugMode ) $output->writeln( ' ==> <error>KO</error>' );
            $this->updateGlobalStatus( $globalStatuses, IDPReconciliation::ERROR_DATABASE_COPY, $em );
            return 209; }
        if( $debugMode ) $output->writeln( ' ==> <info>OK</info>' );

        if( $debugMode ) $output->write( 'Create IDPDeletedArchiveCopy' );
        if( !$this->executeSQLQuery('CREATE TABLE `IDPDeletedArchiveCopy` LIKE `IDPDeletedArchive`;', $em ) ){
            if( $debugMode ) $output->writeln( ' ==> <error>KO</error>' );
            $this->updateGlobalStatus( $globalStatuses, IDPReconciliation::ERROR_DATABASE_COPY, $em );
            return 210; }
        if( $debugMode ) $output->writeln( ' ==> <info>OK</info>' );

        if( $debugMode ) $output->write( 'Copy all datas from IDPDeletedArchive into it' );
        if( !$this->executeSQLQuery('INSERT INTO `IDPDeletedArchiveCopy` SELECT * FROM `IDPDeletedArchive`;', $em ) ){
            if( $debugMode ) $output->writeln( ' ==> <error>KO</error>' );
            $this->updateGlobalStatus( $globalStatuses, IDPReconciliation::ERROR_DATABASE_COPY, $em );
            return 211; }
        if( $debugMode ) $output->writeln( ' ==> <info>OK</info>' );

        //..............................................................................................................
        // Treatment / Step 1
        $this->updateGlobalStatus( $globalStatuses, IDPReconciliation::TREATMENT_IN_PROGRESS, $em );
        if( $debugMode ) $output->writeln( '<info>== Treatment ==</info>' );

        if( ($err = $this->analyseReconciliationStep1( $localization, $fullName, $reconciliation, $em, $doctrine, $debugMode, $output )) < 0 ){
            if( $debugMode ) $output->writeln( '<error>CRITICAL ERROR during Step 1</error>' );
            $this->updateGlobalStatus( $globalStatuses, IDPReconciliation::ERROR_STEP1 - $err, $em );
            return 211 - $err; }

        // Treatment / Step 2
        if( $err = $this->analyseReconciliationStep2( $localization, $reconciliation, $em, $doctrine, $debugMode, $output ) < 0 ){
            if( $debugMode ) $output->writeln( '<error>CRITICAL ERROR during Step 2</error>' );
            $this->updateGlobalStatus( $globalStatuses, IDPReconciliation::ERROR_STEP2 - $err, $em );
            return 215 - $err; }

        //..............................................................................................................
        // End of treatment, make files
        if( $debugMode ) $output->writeln( '<info>== Result Files generation ==</info>' );

        // Files are now ready
        $this->updateGlobalStatus( $globalStatuses, IDPReconciliation::RECONCILIATION_READY, $em );
        if( $err = $this->generateResultFiles( $reconciliation, $em, $doctrine, $debugMode, $output ) < 0 ){
            if( $debugMode ) $output->writeln( '<error>CRITICAL ERROR during Result Files Generation</error>' );
            $this->updateGlobalStatus( $globalStatuses, IDPReconciliation::ERROR_FILE_GENERATION - $err, $em );
            return 219 - $err; }

        //..............................................................................................................
        if( $debugMode ) $output->writeln( ' END OF PROCESS ' );
        return 0;
    }

    private function updateGlobalStatus( $globalStatuses, $new_status, $em ){
        $globalStatuses->setReconciliationInProgress( $new_status );
        if( $new_status == 0 )
            $globalStatuses->setCurrentReconciliationId( 0 );
        $em->persist( $globalStatuses );
        $em->flush();
    }
    private function executeSQLQuery( $rawQuery, $em ){
        $statement = $em->getConnection()->prepare($rawQuery);
        if( !$statement ) return null;
        if( !$statement->execute() ) return null;
        return $statement;
    }

    // -----------------------------------------------------------------------------------------------------------------
    // FIRST STEP, analyse BDD with file
    private function analyseReconciliationStep1( $localization, $reconciliationFilename, $reconciliationStruct, $em, $doctrine, $debug, $output ){
        // Open file
        if( $debug ) $output->write( 'Opening file' );
        $fileSize = filesize( $reconciliationFilename );
        $fileHandle = fopen( $reconciliationFilename, "r");
        if( !$fileHandle ){
            if( $debug ) $output->writeln( ' ==> <info>OK</info>' );
            return -1; }
        if( $debug ) $output->writeln( ' ==> <info>OK</info>' );

        $beginTime = time();    // Timestamp of beginning
        $nbLineTreated = 0;
        $nbBytesTreated = 0;
        $percentTreated = 0;
        $estimatedEndTime = null;

        $readFromFile = [];

        $result = [];
        $resultComm = null;

        // Get usefull tables for quick analyse
        if( $debug ) $output->write( 'Get Providers' );
        $providers = $doctrine->getRepository('bsIDPBackofficeBundle:IDPProviders')->getAllIndexedOnID();
        if( !$providers ){
            if( $debug ) $output->writeln( ' ==> <error>KO</error>' );
            return -2; }
        if( $debug ) $output->writeln( ' ==> <info>OK</info>' );

        if( $debug ) $output->write( 'Get Statuses' );
        $statuses = $doctrine->getRepository('bsIDPArchiveBundle:IDPArchivesStatus')->getAllIndexedOnID();
        if( !$statuses ){
            if( $debug ) $output->writeln( ' ==> <error>KO</error>' );
            return -3; }
        if( $debug ) $output->writeln( ' ==> <info>OK</info>' );


        while( ( $lineFromFile = fgets( $fileHandle ) ) !== false ){
            // Calculate progression
            $percentTreated = (int)(100 * $nbBytesTreated / $fileSize);
            $currentTime = time(); // Timestamp of now
            if( $nbBytesTreated != 0 )
                $estimatedEndTime = $beginTime + (($currentTime - $beginTime) * $fileSize / $nbBytesTreated);
            if( $percentTreated != $reconciliationStruct->getPercentStep1() ) {
                $reconciliationStruct->setPercentStep1( $percentTreated );
                $reconciliationStruct->setNbLinesTreated( $nbLineTreated );
                $date = new DateTime();
                $date->setTimestamp( $estimatedEndTime );
                $reconciliationStruct->setEstimatedEndStep1( $date );
                $em->persist( $reconciliationStruct );
                $em->flush();
                $this->saveAllReconciliationComm( $result, $em );
                $this->saveAllReconciliationFileRead( $readFromFile, $em );
                $result = [];
                $readFromFile = [];
            }

            $nbLineTreated++;
            $nbBytesTreated += strlen( $lineFromFile );

            if( $resultComm != null ) $result[] = $resultComm;

            // process the line read.
            $lineCleaned = preg_replace('/\r|\n/', "", $lineFromFile); // Remove EOL
            $output->write( 'Line '.$nbLineTreated.' ==> '.$lineCleaned.' | ' );
            $nodes = explode( ';', $lineCleaned );
            if( count( $nodes ) != 3 ){
                $output->writeln( ' ==> <error>syntax error</error>' );
                $resultComm = $this->createNewResultComm( true, $nbLineTreated );
                continue; }

            // Add new line to be stored in db for step 2
            $readFromFile[] = $this->createNewReadFromFile( $nodes[0], $nodes[1], $nodes[2] );

            if( !in_array( $nodes[2], [ 'IN', 'OUT', 'DESTROY', 'EXIT' ] ) ){
                $output->writeln( ' ==> <error>status unknown</error> ['.$nodes[2].']' );
                $resultComm = $this->createNewResultComm( true, $nbLineTreated );
                continue; }

            // Provider check
            $output->write( 'P' );
            $providerID = $this->findProvider( $providers, $nodes[0] );
            if( !$providerID ){
                $output->writeln( ' ==> <error>provider not found</error>' );
                $resultComm = $this->createNewResultComm( true, $nbLineTreated, $nodes[0], $nodes[1], $nodes[2] );
                continue; }
            $output->write( ':<info>OK</info>' );

            // Triple check and get back list of UA (for DESTROY and EXIT, search also in DeletedArchivesCopy)
            $output->write( ' T' );
            $UAList = $this->verifyTriple( $localization->getId(), $localization->getLongname(), $providerID, $nodes[0], $nodes[1], $nodes[2], $em, $debug, $output, $statuses );
            if( !$UAList ){
                $output->writeln( ' ==> <error>triple not found</error>' );
                $resultComm = $this->createNewResultComm( true, $nbLineTreated, $nodes[0], $nodes[1], $nodes[2], true );
                continue; }
            $output->write( ':<info>OK</info>' );

            // Analyse $UAList
            $output->write( ' A[' );
            if( $nodes[2] == 'IN' ){
                $output->write( 'IN]' );
                if( !$this->analyseIN( $UAList, $debug, $output ) ){
                    $output->writeln( ' ==> <error>KO</error>');
                    $resultComm = $this->createNewResultComm( true, $nbLineTreated, $nodes[0], $nodes[1], $nodes[2], true, true, false, '*', 'No DISP or 1 flag ON' );
                }
                else{
                    $output->writeln( ' ==> <info>OK</info>' );
                    $resultComm = $this->createNewResultComm( true, $nbLineTreated, $nodes[0], $nodes[1], $nodes[2], true, true, true );
                }

            } elseif( $nodes[2] == 'OUT' ){
                $output->write( 'OUT]' );
                if( ( $idx = $this->analyseOUT( $UAList, $debug, $output ) ) >= 0 ){
                    $ko_st = 'No DISP';
//                    if( !$UAList[$idx]['containerasked'] ) $ko_st = 'container asked flag off';
//                    elseif( !$UAList[$idx]['boxasked'] ) $ko_st = 'box asked flag off';
//                    else $ko_st = $UAList[$idx]['status'];
                    $output->writeln( ' ==> <error>KO</error> '.$UAList[$idx]['ordernumber'].' '.$ko_st);
                    $resultComm = $this->createNewResultComm( true, $nbLineTreated, $nodes[0], $nodes[1], $nodes[2], true, true, false, $UAList[$idx]['ordernumber'], $ko_st );
                }
                else{
                    $output->writeln( ' ==> <info>OK</info>' );
                    $resultComm = $this->createNewResultComm( true, $nbLineTreated, $nodes[0], $nodes[1], $nodes[2], true, true, true );
                }

            } elseif( $nodes[2] == 'DESTROY' ){
                $output->write( 'DESTROY]' );
                if( ( $idx = $this->analyseDESTROY( $UAList, $debug, $output ) ) >= 0 ){
                    $output->writeln( ' ==> <error>KO</error> '.$UAList[$idx]['ordernumber'].' '.$UAList[$idx]['status'] );
                    $resultComm = $this->createNewResultComm( true, $nbLineTreated, $nodes[0], $nodes[1], $nodes[2], true, true, false, $UAList[$idx]['ordernumber'], $UAList[$idx]['status'] );
                }
                else{
                    $output->writeln( ' ==> <info>OK</info>' );
                    $resultComm = $this->createNewResultComm( true, $nbLineTreated, $nodes[0], $nodes[1], $nodes[2], true, true, true );
                }

            } else {    // EXIT
                $output->write( 'EXIT]' );
                if( ( $idx = $this->analyseEXIT( $UAList, $debug, $output ) ) >= 0 ){
                    $output->writeln( ' ==> <error>KO</error> '.$UAList[$idx]['ordernumber'].' '.$UAList[$idx]['status'] );
                    $resultComm = $this->createNewResultComm( true, $nbLineTreated, $nodes[0], $nodes[1], $nodes[2], true, true, false, $UAList[$idx]['ordernumber'], $UAList[$idx]['status'] );
                }
                else{
                    $output->writeln( ' ==> <info>OK</info>' );
                    $resultComm = $this->createNewResultComm( true, $nbLineTreated, $nodes[0], $nodes[1], $nodes[2], true, true, true );
                }

            }
        }

        $reconciliationStruct->setPercentStep1( 100 );
        $reconciliationStruct->setNbLinesTreated( $nbLineTreated );
        $date = new DateTime();
        $date->setTimestamp( $currentTime );
        $reconciliationStruct->setDateEndStep1( $date );
        $em->persist( $reconciliationStruct );
        $em->flush();

        if( $resultComm != null ) $result[] = $resultComm;
        $this->saveAllReconciliationComm( $result, $em );
        $this->saveAllReconciliationFileRead( $readFromFile, $em );

        fclose($fileHandle);
        return $nbLineTreated;
    }

    // -----------------------------------------------------------------------------------------------------------------
    // SECOND STEP, analyse file with BDD
    // All lines of file have been saved in database during step 1 in IDPReconciliationFile table
    private function analyseReconciliationStep2( $localization, $reconciliationStruct, $em, $doctrine, $debug, $output )
    {
        $beginTime = time();    // Timestamp of beginning
        $date = new DateTime();
        $date->setTimestamp( $beginTime );
        $reconciliationStruct->setDateBeginStep2( $date );

        $nbEntryTreated = 0;
        $nbTotalUAsToTreat = 0;
        $percentTreated = 0;
        $estimatedEndTime = null;

        $result = [];
        $resultComm = null;

        $alreadyDoneProviders = [];
        $alreadyDoneContainers = [];
        $alreadyDoneBoxes = [];
        $alreadyDoneDocuments = [];

        // Get usefull tables for quick analyse
        if( $debug ) $output->write( 'Get Providers' );
        $providers = $doctrine->getRepository('bsIDPBackofficeBundle:IDPProviders')->getAllIndexedOnID();
        if( !$providers ){
            if( $debug ) $output->writeln( ' ==> <error>KO</error>' );
            return -1; }
        if( $debug ) $output->writeln( ' ==> <info>OK</info>' );

        if( $debug ) $output->write( 'Get Statuses' );
        $statuses = $doctrine->getRepository('bsIDPArchiveBundle:IDPArchivesStatus')->getAllIndexedOnID();
        if( !$statuses ){
            if( $debug ) $output->writeln( ' ==> <error>KO</error>' );
            return -2; }
        if( $debug ) $output->writeln( ' ==> <info>OK</info>' );

        // Count archives to be treated (needed for percentage)
        if( $debug ) $output->write( 'Count UAs to treat = ' );
        $nbTotalUAsToTreat = $this->countUAsToTreat( $localization->getId(), $localization->getLongname(), $em );
        if( $debug ) $output->writeln( ' ==> <info>'.$nbTotalUAsToTreat.'</info>' );

        // First iterate in UAsCopy
        if( $debug ) $output->write( 'Get IDPArchiveCopy iterator' );
        $rawQuery = "SELECT ordernumber, status_id, containerasked, boxasked, containernumber, boxnumber, documentnumber, provider_id FROM IDPArchiveCopy WHERE localization_id=".
            $localization->getId()." OR oldlocalization_id=".$localization->getId()." ORDER BY provider_id ASC, containernumber ASC, boxnumber ASC, documentnumber ASC";
        $statement = $this->executeSQLQuery( $rawQuery, $em );
        if( !$statement ){
            if( $debug ) $output->writeln( ' ==> <error>KO</error>' );
            return -3; }
        if( $debug ) $output->writeln( ' ==> <info>OK</info>' );

        while( $line = $statement->fetch() ){
            // Calculate progression
            $percentTreated = (int)(100 * $nbEntryTreated / $nbTotalUAsToTreat);
            $currentTime = time(); // Timestamp of now
            if( $nbEntryTreated != 0 )
                $estimatedEndTime = $beginTime + (($currentTime - $beginTime) * $nbTotalUAsToTreat / $nbEntryTreated);
            if( $percentTreated != $reconciliationStruct->getPercentStep2() ) {
                $reconciliationStruct->setPercentStep2( $percentTreated );
                $reconciliationStruct->setNbEntriesTreated( $nbEntryTreated );
                $date = new DateTime();
                $date->setTimestamp( $estimatedEndTime );
                $reconciliationStruct->setEstimatedEndStep2( $date );
                $em->persist( $reconciliationStruct );
                $em->flush();
                $this->saveAllReconciliationComm( $result, $em );
                $result = [];
            }
            $nbEntryTreated++;

            // Process line
            if( $debug ) $output->write( 'Entry '.$nbEntryTreated. ' UA:'. $line['ordernumber']. ' P:' );
            $provider = $providers[$line['provider_id']];
            $providerName = $provider['longname'];

            $status = $statuses[$line['status_id']];
            // Verify if provider if found in file
            if( $this->isProviderInFile( $providerName, $em ) <= 0 ){
                if( $debug ) $output->write('<error>KO</error>');
                if( !in_array( $providerName, $alreadyDoneProviders ) ){
                    $alreadyDoneProviders[] = $providerName;
                    if( $debug ) $output->write( '*' );
                    $result[] = $this->createNewResultComm( false, 0, $providerName,
                        null, null, false, false, false,
                        $line['ordernumber'] );
                }
                if( $debug ) $output->writeln( '' );
                continue;
            } else {
                if( $debug ) $output->write('<info>OK</info>');
                // Create remember struct
                if( !array_key_exists( $providerName, $alreadyDoneContainers ) ){
                    $alreadyDoneContainers[$providerName] = []; $output->write( '<info>c</info>' );}
                if( !array_key_exists( $providerName, $alreadyDoneBoxes ) ){
                    $alreadyDoneBoxes[$providerName] = []; $output->write( '<info>b</info>' );}
                if( !array_key_exists( $providerName, $alreadyDoneDocuments ) ){
                    $alreadyDoneDocuments[$providerName] = []; $output->write( '<info>d</info>' );}
            }

            // If there is a container number, verify it
            if( strlen( $line['containernumber' ] ) > 0 ) {
                if( $debug ) $output->write(' C:');
                if( !in_array($line['containernumber'], $alreadyDoneContainers[$providerName] ) ){                      // Verify if not already analyzed

                    if ($this->isContainerInFile($providerName, $line['containernumber'], $em) <= 0) {                  // Verify if container is also in file
                        if( $debug ) $output->write( '<error>KO</error>' );                                                        // We found an error in reconsiliation
                        $resultComm = $this->createNewResultComm(false, 0, $providerName,
                            $line['containernumber'], null, true, false, false,
                            $line['ordernumber']);
                    } else {
                        if( $debug ) $output->write('<info>OK</info>');                                                              // Container is also in file, so analyse if status is coherent

                        if( $debug ) $output->write( '[A:' );
                        $resultIODE = $this->analyseObjectCBD( $localization, $provider, $line['containernumber'], self::CBD_CONTAINER, $statuses, $em, $doctrine, $debug, $output);
                        if( $debug ) $output->write( 'DB='.$resultIODE['db'].'|FILE='.$resultIODE['file'].' >' );
                        if( $resultIODE['idem'] ) {
                            $resultComm = $this->createNewResultComm( false, 0, $providerName,
                                $line['containernumber'], self::IODE_STR[$resultIODE['db']], true, true, true );
                            if( $debug ) $output->write('<info>OK</info>]');
                        } else {
                            $resultComm = $this->createNewResultComm( false, 0, $providerName,
                                $line['containernumber'],  self::IODE_STR[$resultIODE['db']], true, true, false,
                                $line['ordernumber'], self::IODE_STR[$resultIODE['file']] );
                            if( $debug ) $output->write('<error>KO</error>]');
                        }
                    }

                    if( $debug ) $output->write( '<info>+</info>' );
                    $alreadyDoneContainers[$providerName][] = $line['containernumber'];                                 // Add it to already verified
                } else
                    if( $debug ) $output->write( '*');                                                                               // Container already treated, so just ignore it
            }
            if( $resultComm != null ){ $result[] = $resultComm; $resultComm = null; }

            // If there is a box number, verify it (process is analog to container, so no more explanation)
            if( strlen( $line['boxnumber' ] ) > 0 ) {
                if( $debug ) $output->write(' B:');
                if( !in_array($line['boxnumber'], $alreadyDoneBoxes[$providerName] ) ){

                    if( $this->isBoxInFile($providerName, $line['boxnumber'], $em ) <= 0) {
                        if( $debug ) $output->write( '<error>KO</error>' );
                        $resultComm = $this->createNewResultComm(false, 0, $providerName,
                            $line['boxnumber'], null, true, false, false,
                            $line['ordernumber']);
                    } else {
                        if( $debug ) $output->write('<info>OK</info>');

                        if( $debug ) $output->write( '[A:' );
                        $resultIODE = $this->analyseObjectCBD( $localization, $provider, $line['boxnumber'], self::CBD_BOX, $statuses, $em, $doctrine, $debug, $output);
                        if( $debug ) $output->write( 'DB='.$resultIODE['db'].'|FILE='.$resultIODE['file'].' >' );
                        if( $resultIODE['idem'] ) {
                            $resultComm = $this->createNewResultComm( false, 0, $providerName,
                                $line['boxnumber'], self::IODE_STR[$resultIODE['db']], true, true, true );
                            if( $debug ) $output->write('<info>OK</info>]');
                        } else {
                            $resultComm = $this->createNewResultComm( false, 0, $providerName,
                                $line['boxnumber'],  self::IODE_STR[$resultIODE['db']], true, true, false,
                                $line['ordernumber'], self::IODE_STR[$resultIODE['file']] );
                            if( $debug ) $output->write('<error>KO</error>]');
                        }
                    }

                    if( $debug ) $output->write( '<info>+</info>' );
                    $alreadyDoneBoxes[$providerName][] = $line['boxnumber'];
                } else
                    if( $debug ) $output->write( '*');
            }
            if( $resultComm != null ){ $result[] = $resultComm; $resultComm = null; }

            // If there is a document number, verify it (process is analog to container, so no more explanation)
            if( strlen( $line['documentnumber' ] ) > 0 ) {
                if( $debug ) $output->write(' D:');
                if( !in_array($line['documentnumber'], $alreadyDoneDocuments[$providerName] ) ){

                    if( $this->isDocumentInFile($providerName, $line['documentnumber'], $em ) <= 0) {
                        if( $debug ) $output->write( '<error>KO</error>' );
                        $resultComm = $this->createNewResultComm(false, 0, $providerName,
                            $line['documentnumber'], null, true, false, false,
                            $line['ordernumber']);
                    } else {
                        if( $debug ) $output->write('<info>OK</info>');

                        if( $debug ) $output->write( '[A:' );
                        $resultIODE = $this->analyseObjectCBD( $localization, $provider, $line['documentnumber'], self::CBD_DOCUMENT, $statuses, $em, $doctrine, $debug, $output);
                        if( $debug ) $output->write( 'DB='.$resultIODE['db'].'|FILE='.$resultIODE['file'].' >' );
                        if( $resultIODE['idem'] ) {
                            $resultComm = $this->createNewResultComm( false, 0, $providerName,
                                $line['documentnumber'], self::IODE_STR[$resultIODE['db']], true, true, true );
                            if( $debug ) $output->write('<info>OK</info>]');
                        } else {
                            $resultComm = $this->createNewResultComm( false, 0, $providerName,
                                $line['documentnumber'],  self::IODE_STR[$resultIODE['db']], true, true, false,
                                $line['ordernumber'], self::IODE_STR[$resultIODE['file']] );
                            if( $debug ) $output->write('<error>KO</error>]');
                        }
                    }

                    if( $debug ) $output->write( '<info>+</info>' );
                    $alreadyDoneDocuments[$providerName][] = $line['documentnumber'];
                } else
                    if( $debug ) $output->write( '*');
            }
            if( $resultComm != null ){ $result[] = $resultComm; $resultComm = null; }

            if( $debug ) $output->writeln( ' .' );
            // Ligne suivante

            // DEBUG
            // if( $nbEntryTreated > 2 ) return 3;
        }

        $reconciliationStruct->setPercentStep2( 100 );
        $reconciliationStruct->setNbEntriesTreated( $nbEntryTreated );
        $date = new DateTime();
        $date->setTimestamp( $currentTime );
        $reconciliationStruct->setDateEndStep2( $date );
        $em->persist( $reconciliationStruct );
        $em->flush();

        if( $resultComm != null ) $result[] = $resultComm;
        $this->saveAllReconciliationComm( $result, $em );

        return $nbEntryTreated;
    }

    // .................................................................................................................
    private function saveAllReconciliationComm( $allReconciliationComms, $em ){
        foreach( $allReconciliationComms as $item )
            $em->persist( $item );
        $em->flush();
    }
    private function saveAllReconciliationFileRead( $allReadFromFile, $em ){
        foreach( $allReadFromFile as $item )
            $em->persist( $item );
        $em->flush();
    }
    private function createNewResultComm( $way_archimage_provider, $provider_file_line_number,
                                       $provider = null,
                                       $container_box_document = null,
                                       $global_status = null,
                                       $ok_provider = false,
                                       $ok_triple = false,
                                       $ok_status = false,
                                       $ko_ua_num_order = null,
                                       $ko_status = null  ){
        $newReconciliationComm = new IDPReconciliationComm();
        $newReconciliationComm->setWayArchimageProvider( $way_archimage_provider );
        $newReconciliationComm->setProviderFileLineNumber( $provider_file_line_number );
        $newReconciliationComm->setProvider( $provider );
        $newReconciliationComm->setContainerBoxDocument( $container_box_document );
        $newReconciliationComm->setGlobalStatus( $global_status );
        $newReconciliationComm->setOkProvider( $ok_provider );
        $newReconciliationComm->setOkTriple( $ok_triple );
        $newReconciliationComm->setOkStatus( $ok_status );
        $newReconciliationComm->setKoUaNumOrder( $ko_ua_num_order );
        $newReconciliationComm->setKoStatus( $ko_status );
        return $newReconciliationComm;
    }
    private function createNewReadFromFile( $provider, $cbd, $status ){
        $newReadFromFile = new IDPReconciliationFile();
        $newReadFromFile->setProvider( $provider );
        $newReadFromFile->setCBD( $cbd );
        $newReadFromFile->setStatus( $status );
        return $newReadFromFile;
    }
    private function findProvider( $providers, $providerName ){
        foreach( $providers as $provider ){
            if( $provider['longname'] == $providerName )
                return $provider['id'];
        }
        return null;
    }
    private function verifyTriple( $localizationID, $localization, $providerID, $provider, $cbd, $pStatus, $em, $debug, $output, $statuses ){
        // First search with container
        $rawQuery1 = "SELECT ordernumber, status_id, containerasked, boxasked FROM IDPArchiveCopy WHERE localization_id=".$localizationID." AND provider_id=".$providerID." AND containernumber='".$cbd."'";
        $rawQuery2 = "SELECT ordernumber, status FROM IDPDeletedArchiveCopy WHERE localization='".$localization."' AND provider='".$provider."' AND containernumber='".$cbd."'";
        $statement1 = $this->executeSQLQuery( $rawQuery1, $em );
        $statement2 = $this->executeSQLQuery( $rawQuery2, $em );
        if( $statement1 ) $uas1 = $statement1->fetchAll(); else $uas1 = null;
        if( $statement2 ) $uas2 = $statement2->fetchAll(); else $uas2 = null;
        $uas = $this->makeUAsList( $uas1, $uas2, $statuses );
        if( count($uas) != 0 ){
            return $uas; }

        // Not found, so search for boxes
        $rawQuery1 = "SELECT ordernumber, status_id, containerasked, boxasked FROM IDPArchiveCopy WHERE localization_id=".$localizationID." AND provider_id=".$providerID." AND boxnumber='".$cbd."'";
        $rawQuery2 = "SELECT ordernumber, status FROM IDPDeletedArchiveCopy WHERE localization='".$localization."' AND provider='".$provider."' AND boxnumber='".$cbd."'";
        $statement1 = $this->executeSQLQuery( $rawQuery1, $em );
        $statement2 = $this->executeSQLQuery( $rawQuery2, $em );
        if( $statement1 ) $uas1 = $statement1->fetchAll(); else $uas1 = null;
        if( $statement2 ) $uas2 = $statement2->fetchAll(); else $uas2 = null;
        $uas = $this->makeUAsList( $uas1, $uas2, $statuses );
        if( count($uas) != 0 ){
            return $uas; }

        // Not found, so search for documents
        $rawQuery1 = "SELECT ordernumber, status_id, containerasked, boxasked FROM IDPArchiveCopy WHERE localization_id=".$localizationID." AND provider_id=".$providerID." AND documentnumber='".$cbd."'";
        $rawQuery2 = "SELECT ordernumber, status FROM IDPDeletedArchiveCopy WHERE localization='".$localization."' AND provider='".$provider."' AND documentnumber='".$cbd."'";
        $statement1 = $this->executeSQLQuery( $rawQuery1, $em );
        $statement2 = $this->executeSQLQuery( $rawQuery2, $em );
        if( $statement1 ) $uas1 = $statement1->fetchAll(); else $uas1 = null;
        if( $statement2 ) $uas2 = $statement2->fetchAll(); else $uas2 = null;
        $uas = $this->makeUAsList( $uas1, $uas2, $statuses );
        if( count($uas) != 0 ){
            return $uas; }

        // Not found, so nothing
        return null;
    }
    private function makeUAsList( $uas1, $uas2, $statuses ){
        $UAs = [];

        if( $uas1 != null )
            foreach( $uas1 as $ua ){
                $UAs[] = [ 'ordernumber' => $ua['ordernumber'], 'status' => $statuses[$ua['status_id']]['shortname'], 'containerasked' => $ua['containerasked'], 'boxasked' => $ua['boxasked'] ];
            }
        if( $uas2 != null )
            foreach( $uas2 as $ua ){
                // In DeletedArchiveCopy, container or box must have been asked in totality so, set to true
                $UAs[] = [ 'ordernumber' => $ua['ordernumber'], 'status' => $ua['status'], 'containerasked' => 1, 'boxasked' => 1 ];
            }

        return $UAs;
    }
    private function countUAsToTreat( $localizationID, $localization, $em ){
        $rawQuery1 = "SELECT COUNT(*) FROM IDPArchiveCopy WHERE localization_id=".$localizationID;
        $rawQuery2 = "SELECT COUNT(*) FROM IDPDeletedArchiveCopy WHERE localization='".$localization."'";
        $statement1 = $this->executeSQLQuery( $rawQuery1, $em );
        $statement2 = $this->executeSQLQuery( $rawQuery2, $em );
        if( $statement1 ){
            $temp = $statement1->fetch();
            $uas1 = $temp['COUNT(*)']; }
        else $uas1 = 0;
        if( $statement2 ){
            $temp = $statement2->fetch();
            $uas2 = $temp['COUNT(*)']; }
        else $uas2 = 0;
        return $uas1+$uas2;
    }
    private function isProviderInFile( $providerName, $em ){
        $rawQuery = "SELECT COUNT(*) FROM IDPReconciliationFile WHERE provider='".$providerName."'";
        $statement = $this->executeSQLQuery( $rawQuery, $em );
        if( $statement ) {
            $temp = $statement->fetch();
            return $temp['COUNT(*)'];
        } else
            return -1;
    }
    private function isObjectInFile( $providerName, $objectName, $em ){
        $rawQuery = "SELECT COUNT(*) FROM IDPReconciliationFile WHERE provider='".$providerName."' AND cbd='".$objectName."'";
        $statement = $this->executeSQLQuery( $rawQuery, $em );
        if( $statement ){
            $temp = $statement->fetch();
            return $temp['COUNT(*)'];
        } else
            return 0;
    }
    private function isContainerInFile( $providerName, $container, $em ){
        return $this->isObjectInFile( $providerName, $container, $em );
    }
    private function isBoxInFile( $providerName, $box, $em ){
        return $this->isObjectInFile( $providerName, $box, $em );
    }
    private function isDocumentInFile( $providerName, $document, $em ){
        return $this->isObjectInFile( $providerName, $document, $em );
    }

    /* UNUSED FUNCTION | BUT ALGO COULD BE USEFULL
    // make a struct when reading file, for second step analyse.
    // array = [ 'provider1' => [ 'cbd1' => 'status1', 'cbd2' => 'status2' ], 'provider2' => [ 'cbda' => 'statusa' ] ]
    private function addLineToArray( $computedArray, $provider, $cbd, $status, $debug, $output ){
        if( array_key_exists( $provider, $computedArray ) ){
            if( !array_key_exists( $cbd, $computedArray[$provider] ) ) {
                $computedArray[$provider][$cbd] = $status;
            }
        } else {
            $computedArray[$provider] = [];
            $computedArray[$provider][$cbd] = $status;
        }
        return $computedArray;
    }
    */

    // IN: If at least 1 UA with status IN_STATUS or all UAs flag OFF
    // return true = Analyse IN positive / false otherwise
    private function analyseIN( $UAList, $debug, $output ){
        foreach( $UAList as $idx=>$ua ){
            if( $ua['containerasked'] || $ua['boxasked'] )              // We found 1 UA with flag on (so not all OFF) => it's positive result for IN
                return true;
            if( in_array( $ua['status'], self::IN_STATUS ) )    // We found 1 UA with status DISP => it's a positive result for IN
                return true;
        }
        return false;
    }
    // OUT: If no UA with status OUT_STATUS and at least 1 UA flag ON
    // return -1 = Analyse OUT positive / otherwise idx (>= 0)
    private function analyseOUT( $UAList, $debug, $output ){
        foreach( $UAList as $idx=>$ua ){
            if( $ua['containerasked'] || $ua['boxasked'] )
                return $idx;                                              // We found 1 flag on, so it's an error
            if( in_array( $ua['status'], self::OUT_STATUS ) ) // We found 1 UA with status DISP => it's an error for OUT
                return $idx;
        }
        return -1;  // array is 0 index, so return less than 0 for good analyse, otherwise return index of first error
    }
    // DESTROY: If all UAs with status DESTROY_STATUS
    // return -1 = Analyse OUT positive / otherwise idx (>= 0)
    private function analyseDESTROY( $UAList, $debug, $output ){
        foreach( $UAList as $idx=>$ua ){
            if( !in_array( $ua['status'], self::DESTROY_STATUS ) )
                return $idx;
        }
        return -1;
    }
    // EXIT: If all UAs with status EXIT_STATUS
    // return -1 = Analyse OUT positive / otherwise idx (>= 0)
    private function analyseEXIT( $UAList, $debug, $output ){
        foreach( $UAList as $idx=>$ua ){
            if( !in_array( $ua['status'], self::EXIT_STATUS ) )
                return $idx;
        }
        return -1;
    }

    private function analyseObjectCBD( $localization, $provider, $cbdNumber, $cbdType, $statuses, $em, $doctrine, $debug, $output){
        $result = [ 'idem' => false, 'db' => 0, 'file' => 0 ];
        // Get all UAs with provider and cbd
        $rawQuery1 = "SELECT ordernumber, status_id, containerasked, boxasked FROM IDPArchiveCopy WHERE localization_id=".$localization->getId()." AND provider_id=".$provider['id']." AND ";
        $rawQuery2 = "SELECT ordernumber, status FROM IDPDeletedArchiveCopy WHERE localization='".$localization->getLongname()."' AND provider='".$provider['longname']."' AND ";
        if( $cbdType == self::CBD_CONTAINER ) {
            $rawQuery1 .= "containernumber='" . $cbdNumber . "'";
            $rawQuery2 .= "containernumber='".$cbdNumber."'";
        } elseif( $cbdType == self::CBD_BOX ) {
            $rawQuery1 .= "boxnumber='" . $cbdNumber . "'";
            $rawQuery2 .= "boxnumber='".$cbdNumber."'";
        } elseif( $cbdType == self::CBD_DOCUMENT ){
            $rawQuery1 .= "documentnumber='" . $cbdNumber . "'";
            $rawQuery2 .= "documentnumber='".$cbdNumber."'";
        } else
            return $result;

        $statement1 = $this->executeSQLQuery( $rawQuery1, $em );
        $statement2 = $this->executeSQLQuery( $rawQuery2, $em );
        if( $statement1 ) $uas1 = $statement1->fetchAll(); else $uas1 = null;
        if( $statement2 ) $uas2 = $statement2->fetchAll(); else $uas2 = null;
        $uas = $this->makeUAsList( $uas1, $uas2, $statuses );

        $iode = $this->calculateIODE( $uas, $cbdType );

        // Compare iode with entry in file
        $fileIODE = '';
        $rawQuery = "SELECT status FROM IDPReconciliationFile WHERE provider='".$provider['longname']."' AND cbd='".$cbdNumber."'";
        $statement = $this->executeSQLQuery( $rawQuery, $em );
        if( $statement ){
            $temp = $statement->fetch();
            if( $temp['status'] == 'IN' ) $fileIODE = self::IODE_IN;
            elseif( $temp['status'] == 'OUT' ) $fileIODE = self::IODE_OUT;
            elseif( $temp['status'] == 'DESTROY' ) $fileIODE = self::IODE_DESTROY;
            elseif( $temp['status'] == 'EXIT' ) $fileIODE = self::IODE_EXIT;
            else $fileIODE = self::IODE_UNKNOWN;
        }

        $result['db'] = $iode;
        $result['file'] = $fileIODE;

        if(( $iode == $fileIODE ) && ( $iode != self::IODE_UNKNOWN ))
            $result['idem'] = true;

        return $result;
    }
    private function calculateIODE( $UAList, $cbdType ){
        $calc_iode = [ 'IN' => 0, 'OUT' => 0, 'NOT_DESTROY' => 0,  'NOT_EXIT' => 0, 'FLAG_ON' => 0 ];
        foreach( $UAList as $ua ){
            if( $cbdType == self::CBD_CONTAINER && $ua['containerasked'] ) $calc_iode['FLAG_ON']++;
            if( $cbdType == self::CBD_BOX && $ua['boxasked'] ) $calc_iode['FLAG_ON']++;
            if( in_array( $ua['status'], self::IN_STATUS ) ) $calc_iode['IN']++;
            if( in_array( $ua['status'], self::OUT_STATUS ) ) $calc_iode['OUT']++;
            if( !in_array( $ua['status'], self::DESTROY_STATUS ) ) $calc_iode['NOT_DESTROY']++;
            if( !in_array( $ua['status'], self::EXIT_STATUS ) ) $calc_iode['NOT_EXIT']++;
        }
        if( $calc_iode['FLAG_ON'] == 0 || $calc_iode['IN'] >= 1 )
            return self::IODE_IN;
        if( $calc_iode['FLAG_ON'] >= 1 && $calc_iode['OUT'] == 0 )
            return self::IODE_OUT;
        if( $calc_iode['NOT_DESTROY'] == 0 )
            return self::IODE_DESTROY;
        if( $calc_iode['NOT_EXIT'] == 0 )
            return self::IODE_EXIT;
        return self::IODE_UNKNOWN;
    }


    private function generateResultFiles( $reconciliation, $em, $doctrine, $debugMode, $output ){
        // Result filenames will be: originalName-result-step1.csv / originalName-result-step1.xls / originalName-result-step2.csv / originalName-result-step2.xls

        if( $debugMode ) $output->writeln( '== STEP1 ==' );
        if( $debugMode ) $output->write( 'Get sql statement ' );
        $rawQuery = "SELECT provider, container_box_document, global_status, ok_provider, ok_triple, ok_status, ko_ua_num_order, ko_status ";
        $rawQuery .= "FROM IDPReconciliationComm WHERE way_archimage_provider=1";
        $statement = $this->executeSQLQuery( $rawQuery, $em );
        if( !$statement ) {
            if( $debugMode ) $output->writeln( '<error>KO</error>' );
            return -1;
        }
        if( $debugMode ) $output->writeln( '<info>OK</info>' );

        // Create file .csv
        $filename = $this->getContainer()->get('kernel')->getRootDir() . '/../web/import/archimage/' . $reconciliation->getResultFilename() . 'result-step1.idp';
        if( $debugMode ) $output->write( 'Create empty file '.$filename.' ' );
        $filestream = fopen( $filename, 'w' );
        if( !$filestream ) {
            if ($debugMode) $output->writeln('<error>KO</error>');
            return -2;
        }
        if( $debugMode ) $output->writeln( '<info>OK</info>' );

        if( $debugMode ) $output->write( 'Write Headers ' );
        $csvArray = [ 'Compte client', 'Code', 'Statut Prestataire', 'OK Cpte Client', 'OK Triplet', 'OK Statut', 'UA Num ordre', 'UA Statut' ];
        if( !fputcsv( $filestream, $csvArray, ';' ) ){
            if ($debugMode) $output->writeln('<error>KO</error>');
            fclose( $filestream );
            return -3;
        }
        if( $debugMode ) $output->writeln( '<info>OK</info>' );

        if( $debugMode ) $output->write( 'Create XLS File ' );
        $xlsWriter = new \XLSXWriter();
        $xlsWriter->setAuthor('Archimage');
        // Titles
        $xlsWriter->writeSheetRow('Result', $csvArray );
        if( $debugMode ) $output->writeln( '<info>OK</info>' );


        if( $debugMode ) $output->write( 'Write Lines ' );
        while( $line = $statement->fetch() ){
            if( $debugMode ) $output->write( '.' );
            $csvArray = [
                $line['provider'],
                $line['container_box_document'],
                $line['global_status'],
                $line['ok_provider']?'OK':'KO',
                $line['ok_triple']?'OK':'KO',
                $line['ok_status']?'OK':'KO',
                $line['ko_ua_num_order'],
                $line['ko_status']
                ];
            if( !fputcsv( $filestream, $csvArray, ';' ) ){
                if ($debugMode) $output->writeln('<error>KO</error>');
                fclose( $filestream );
                return -3;
            }
            $xlsWriter->writeSheetRow('Result', $csvArray );
        }
        if( $debugMode ) $output->writeln( '<info>OK</info>' );
        fclose( $filestream );

        $filename = $this->getContainer()->get('kernel')->getRootDir() . '/../web/import/archimage/' . $reconciliation->getResultFilename() . 'result-step1.xlsx';
        if( $debugMode ) $output->write( 'Write XLS to File '.$filename.' ' );
        $xlsWriter->writeToFile( $filename );
        if( $debugMode ) $output->writeln( '<info>OK</info>' );

        if( $debugMode ) $output->writeln( '== STEP2 ==' );
        if( $debugMode ) $output->write( 'Get sql statement ' );
        $rawQuery = "SELECT provider, container_box_document, global_status, ok_provider, ok_triple, ok_status, ko_ua_num_order, ko_status ";
        $rawQuery .= "FROM IDPReconciliationComm WHERE way_archimage_provider=0";
        $statement = $this->executeSQLQuery( $rawQuery, $em );
        if( !$statement ) {
            if( $debugMode ) $output->writeln( '<error>KO</error>' );
            return -1;
        }
        if( $debugMode ) $output->writeln( '<info>OK</info>' );

        // Create file .csv
        $filename = $this->getContainer()->get('kernel')->getRootDir() . '/../web/import/archimage/' . $reconciliation->getResultFilename() . 'result-step2.idp';
        if( $debugMode ) $output->write( 'Create empty file '.$filename.' ' );
        $filestream = fopen( $filename, 'w' );
        if( !$filestream ) {
            if ($debugMode) $output->writeln('<error>KO</error>');
            return -2;
        }
        if( $debugMode ) $output->writeln( '<info>OK</info>' );

        if( $debugMode ) $output->write( 'Write Headers ' );
        $csvArray = [ 'Compte client', 'Code', 'Statut Archimage', 'OK Cpte Client', 'OK Triplet', 'OK Statut', 'UA Num ordre', 'Statut Fichier' ];
        if( !fputcsv( $filestream, $csvArray, ';' ) ){
            if ($debugMode) $output->writeln('<error>KO</error>');
            fclose( $filestream );
            return -3;
        }
        if( $debugMode ) $output->writeln( '<info>OK</info>' );

        if( $debugMode ) $output->write( 'Create XLS File ' );
        $xlsWriter = new \XLSXWriter();
        $xlsWriter->setAuthor('Archimage');
        // Titles
        $xlsWriter->writeSheetRow('Result', $csvArray );
        if( $debugMode ) $output->writeln( '<info>OK</info>' );

        if( $debugMode ) $output->write( 'Write Lines ' );
        while( $line = $statement->fetch() ){
            if( $debugMode ) $output->write( '.' );
            $csvArray = [
                $line['provider'],
                $line['container_box_document'],
                $line['global_status'],
                $line['ok_provider']?'OK':'KO',
                $line['ok_triple']?'OK':'KO',
                $line['ok_status']?'OK':'KO',
                $line['ko_ua_num_order'],
                $line['ko_status']
            ];
            if( !fputcsv( $filestream, $csvArray, ';' ) ){
                if ($debugMode) $output->writeln('<error>KO</error>');
                fclose( $filestream );
                return -3;
            }
            $xlsWriter->writeSheetRow('Result', $csvArray );
        }
        if( $debugMode ) $output->writeln( '<info>OK</info>' );
        fclose( $filestream );

        $filename = $this->getContainer()->get('kernel')->getRootDir() . '/../web/import/archimage/' . $reconciliation->getResultFilename() . 'result-step2.xlsx';
        if( $debugMode ) $output->write( 'Write XLS to File '.$filename.' ' );
        $xlsWriter->writeToFile( $filename );
        if( $debugMode ) $output->writeln( '<info>OK</info>' );

        return 0;
    }
}

?>