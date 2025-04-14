<?php

// src/bs/IDP/ArchiveBundle/Command/ImportAsyncCommand.php
namespace bs\IDP\ArchiveBundle\Command;

use \DateTime;

use MongoDB\BSON\Timestamp;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

use bs\IDP\ArchiveBundle\Entity\IDPImport;
use bs\IDP\ArchiveBundle\Entity\IDPImportComm;
use bs\IDP\BackofficeBundle\Entity\IDPGlobalStatuses;
use bs\IDP\ArchiveBundle\Entity\IDPArchive;
use bs\IDP\ArchiveBundle\Entity\IDPAuditCompleteUa;

use bs\IDP\ArchiveBundle\ConstantDefinition\IDPConstants;

class ImportHFCommand extends ContainerAwareCommand
{
    const IDX_STATUS = 0;
    const IDX_SERVICE = 1;
    const IDX_LEGALENTITY = 2;
    const IDX_BUDGETCODE = 3;
    const IDX_ACTIVITY = 4; // DocumentNature
    const IDX_DOCUMENTTYPE = 5;
    const IDX_DESCRIPTION1 = 6;
    const IDX_DESCRIPTION2 = 7;
    const IDX_CLOSUREDATE = 8;
    const IDX_DESTRUCTIONDATE = 9;
    const IDX_DOCUMENTNUMBER = 10;
    const IDX_BOXNUMBER = 11;
    const IDX_CONTAINERNUMBER = 12;
    const IDX_PROVIDER = 13;
    const IDX_ORDERNUMBER = 14;

    const IDX_LIMITDATEMIN = 15;
    const IDX_LIMITDATEMAX = 16;
    const IDX_LIMITNUMMIN = 17;
    const IDX_LIMITNUMMAX = 18;
    const IDX_LIMITALPHAMIN = 19;
    const IDX_LIMITALPHAMAX = 20;
    const IDX_LIMITALPHANUMMIN = 21;
    const IDX_LIMITALPHANUMMAX = 22;

    const IDX_LOCALIZATION = 23; // Free or normal one, depends on STATUS
    const IDX_ASKWHO = 24;
    const IDX_ASKWHERE = 25;
    const IDX_ASKWHEN = 26;

    const IDX_NAME = 27;

    const NB_COLUMN = 28;

    protected function configure()
    {
        // Typicall use
        // php bin/console app:importhf-file ImportFile.csv --blocksize=100

        // Typical debug use
        // php bin/console app:importhf-file ImportFile.csv --debug --debuglvl=5 --stopOnError --blocksize=100

        $this
            ->setName('app:importhf-file')
            ->setDescription('Import a file into database at high frequency')
            ->addArgument('filename', InputArgument::REQUIRED, 'name of file to import, must be in web/import/ directory')
            ->addOption('blocksize', 'b', InputOption::VALUE_REQUIRED, 'Size of Block for flushing to DB [1-1000]', 1 )
            ->addOption('debug', 'd', InputOption::VALUE_NONE, 'debug mode activated')
            ->addOption('debuglvl', 'l', InputOption::VALUE_REQUIRED, 'Verbosity of debug [1-9]', 1 )
            ->addOption('stopOnError', 's', InputOption::VALUE_NONE, 'When error occur in debug mode, waits for user input to continue')
        ;
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filename = $input->getArgument('filename');
        $debugMode = ($input->getOption('debug')?true:false);
        $verbose = $input->getOption('debuglvl');
        if( $verbose < 1 ) $verbose = 1; if( $verbose > 9 ) $verbose = 9;
        $stopOnError = ($input->getOption('stopOnError')?true:false);
        $blockSize = $input->getOption('blocksize');
        if( $blockSize < 1 ) $blockSize = 1; if( $blockSize > 1000 ) $blockSize = 1000;

        $output->writeln( 'filename = '.$filename );
        $output->writeln( 'debugMode = '.($debugMode?'ON':'OFF') );
        $output->writeln( 'verbose = '.$verbose );
        $output->writeln( 'stopOnError = '.($stopOnError?'ON':'OFF') );
        $output->writeln( 'blocksize = '.$blockSize );

        $beginTime = time();
        $createdAt = date("Y-m-d H:i:s");
        $timestamp = $beginTime;
        $countUntilFlush = 0;

        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion('Type any key to continue !', false);
        if( $output && $debugMode ) {
            $helper->ask($input, $output, $question);
        }

        if( $debugMode && $verbose >= 1 ) $output->writeln('<info>Import HF v1.0</info>');

        $em = $this->getContainer()->get('doctrine')->getManager();
        $conn = $em->getConnection();

        $globalStatuses = $this->getContainer()->get('doctrine')->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses')->findOneBy(array('id' => 1 ));
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 0 ) {
            $output->writeln( '<error>Reconciliation in progress, cannot import !</error>' );
            return;
        }

        if( $debugMode && $verbose >= 1 ) $output->writeln('Import is starting');
        $this->beginImport( $em, $globalStatuses );

        if( $debugMode && $verbose >= 5 ) $output->writeln('Create import structure');
        $import = $this->createNewImport( $em, $filename );
        $import_id = $import->getId();
        $now = new DateTime();
        $begin = $now->getTimestamp();
        $this->updateGlobal( $em, $globalStatuses, $import );

        if( $debugMode && $verbose >= 5 ) $output->write('Verification of file existence : ');
        $fullName = __DIR__.'/../../../../../web/import/' . $filename;
        if( !file_exists( $fullName ) ){
            $output->writeln( '<error>Error</error>, file not found ! File='.$filename );
            $this->updateImport( $em, $import, IDPImport::IDP_IMPORT_STATUS_ERROR );
            $this->endImport( $em, $globalStatuses, $import );
            return;
        }
        if( $debugMode && $verbose >= 5) $output->writeln( '<info>OK</info>');

        if( $debugMode && $verbose >= 5 ) $output->write('Get the fileSize to calculate percent : ');
        $fileSize = filesize( $fullName );
        if( $fileSize <= 0 ){
            $output->writeln( '<error>Error</error>, file empty !');
            $this->updateImport( $em, $import, IDPImport::IDP_IMPORT_STATUS_ERROR );
            $this->endImport( $em, $globalStatuses, $import );
            return;
        }
        if( $debugMode && $verbose >= 5) $output->writeln( '<info>OK</info>');

        $percent = 0;
        $alreadyDone = 0;
        $lineNumber = 0;
        $erreur = 0;

        if( $debugMode && $verbose >= 5 ) $output->write('Verify file encoding : ');
        if( !mb_check_encoding(file_get_contents($fullName), 'UTF-8')){
            $output->writeln( '<error>Error</error>, File encoding is not UTF-8' );
            $this->updateImport( $em, $import, IDPImport::IDP_IMPORT_STATUS_ERROR );
            $this->endImport( $em, $globalStatuses, $import );
            return;
        } if( $debugMode && $verbose >= 5) $output->writeln( '<info>OK</info>');

        if( $debugMode && $verbose >= 5 ) $output->write('Open file to begin treatment : ');
        $file = fopen( $fullName, "r" );
        if( !$file ){
            $output->writeln( '<error>Error</error>, cannot open the file ! [Error code='.error_get_last()['message'] );
            $this->updateImport( $em, $import, IDPImport::IDP_IMPORT_STATUS_ERROR );
            $this->endImport( $em, $globalStatuses, $import );
            return;
        } if( $debugMode && $verbose >= 5) $output->writeln( '<info>OK</info>');

        // Authorized statuses
        $authorizedStatuses = [ 'DTA', 'DISI', 'DISINT', 'DISP', 'CONI', 'CONINT', 'CONP' ];
        // Get all statuses
        $statuses = $this->getContainer()->get('doctrine')
            ->getRepository('bsIDPArchiveBundle:IDPArchivesStatus')
            ->findAll();

        if( $debugMode && $verbose >= 5 ) $output->write('Fast mode, upload in memory of usefull tables : ');

        $services = $this->getContainer()->get('doctrine')
            ->getRepository('bsIDPBackofficeBundle:IDPServices')
            ->findAll();
        $legalEntities = $this->getContainer()->get('doctrine')
            ->getRepository('bsIDPBackofficeBundle:IDPLegalEntities')
            ->findAll();
        $budgetCodes = $this->getContainer()->get('doctrine')
            ->getRepository('bsIDPBackofficeBundle:IDPBudgetCodes')
            ->findAll();
        $activities = $this->getContainer()->get('doctrine')
            ->getRepository('bsIDPBackofficeBundle:IDPDocumentNatures')
            ->findAll();
        $documentTypes = $this->getContainer()->get('doctrine')
            ->getRepository('bsIDPBackofficeBundle:IDPDocumentTypes')
            ->findAll();
        $descriptions1 = $this->getContainer()->get('doctrine')
            ->getRepository('bsIDPBackofficeBundle:IDPDescriptions1')
            ->findAll();
        $descriptions2 = $this->getContainer()->get('doctrine')
            ->getRepository('bsIDPBackofficeBundle:IDPDescriptions2')
            ->findAll();
        $providers = $this->getContainer()->get('doctrine')
            ->getRepository('bsIDPBackofficeBundle:IDPProviders')
            ->findAll();
        $localizations = $this->getContainer()->get('doctrine')
            ->getRepository('bsIDPBackofficeBundle:IDPLocalizations')
            ->findAll();

        if( $debugMode && $verbose >= 5) $output->writeln( '<info>OK</info>');

        // Treat each line of file until end of it
        if( $debugMode && $verbose >= 1 ) $output->writeln('Beginning of lines treatment');

        // Query for Inserting ARCHIVES
        $archivesQueryBEGIN = "INSERT INTO IDPArchive (`status_id`,`service_id`, `legalentity_id`, `budgetcode_id`, 
            `documentnature_id`,`documenttype_id`,`description1_id`,`description2_id`,`closureyear`,`destructionyear`,
            `documentnumber`, `boxnumber`, `containernumber`, `provider_id`, `ordernumber`, `name`, `limitdatemin`,
            `limitdatemax`, `limitnummin`, `limitnummax`, `limitalphamin`, `limitalphamax`, `limitalphanummin`,
            `limitalphanummax`, `localization_id`, `localizationfree`, `precisionwho`, `precisiondate`, `precisionaddress_id`,
            `objecttype`, `futureobjecttype`, `import_id`, `serviceentrydate`, `createdat`, `containerasked`, `boxasked`) VALUES ";
        $archivesQuery = $archivesQueryBEGIN;
        $idpArchive_ID = $this->getContainer()->get('doctrine')
            ->getRepository('bsIDPArchiveBundle:IDPArchive')
            ->getNextID();

        // Query for Import Messages
        $importCommQueryBEGIN = "INSERT INTO IDPImportComm (`import_id`, `percent`, `message`, `rawline`, `status`, `alreadyRead`) VALUES ";
        $importCommQuery = $importCommQueryBEGIN;

        $idpAuditCompleteUAQueryBEGIN = "INSERT INTO IDPAuditCompleteUa (`id`, `service_id`, `legal_entity_id`, `localization`,
            `provider_id`, `budget_code_id`, `document_nature_id`, `document_type_id`) VALUES ";
        $idpAuditCompleteUAQuery = $idpAuditCompleteUAQueryBEGIN;
        $idpAuditCompleteUA_ID = $this->getContainer()->get('doctrine')
            ->getRepository('bsIDPArchiveBundle:IDPAuditCompleteUa')
            ->getNextID();

        $idpAuditQueryBEGIN = "INSERT INTO IDPAudit (`userId`, `timestamp`, `field`, `new_str`, `new_int`, `old_str`,
            `old_int`, `entity`, `entity_id`, `action`, `complet_ua_id`, `objectType`) VALUES ";
        $idpAuditQuery = $idpAuditQueryBEGIN;

        $errorCountBeforeFlush = 0;
        $archiveValidCountBeforeFlush = 0;

        // Try to minimize loading of service config by remebring last one
        $lastServiceID = -1;
        $setting = null;
        $lastAskWhere = null;
        $deliver = null;

        while( !feof( $file ) ){
            $countUntilFlush++;
            if( $output ){
                if ($lineNumber % 100 == 0) $output->write('o');
            }

            $flushToDB = ($countUntilFlush > $blockSize);
            if( $flushToDB ) {

                if( $debugMode && $verbose >= 9 ){
                    $output->writeln( 'QUERIES' );
                    $output->writeln( 'count= '.$countUntilFlush. ' / blocksize= '.$blockSize. ' / $errorCount= '.$errorCountBeforeFlush );
                    $output->writeln( '-------------------------------------' );
                    $output->writeln( $archivesQuery );
                    $output->writeln( '-------------------------------------' );
                    $output->writeln( $importCommQuery );
                    $output->writeln( '-------------------------------------' );
                    $output->writeln( $idpAuditCompleteUAQuery );
                    $output->writeln( '-------------------------------------' );
                    $output->writeln( $idpAuditQuery );
                    $output->writeln( '-------------------------------------' );
                    $helper->ask($input, $output, $question);
                }

                // Write all temporary queries to DB
                // $statement = $connection->prepare($queries);
                // $statement->execute();

                $importCommQuery[strlen($importCommQuery)-1] = ' '; // Just removing unusefull comma

                if( $archiveValidCountBeforeFlush>0 ) {
                    $statement = $conn->prepare($archivesQuery);
                    $statement->execute();
                    $statement = $conn->prepare($idpAuditCompleteUAQuery);
                    $statement->execute();
                    $statement = $conn->prepare($idpAuditQuery);
                    $statement->execute();
                }
                if( $errorCountBeforeFlush>0 ){
                    $statement = $conn->prepare($importCommQuery);
                    $statement->execute();
                }

                // And free queries
                $archivesQuery = $archivesQueryBEGIN;
                $importCommQuery = $importCommQueryBEGIN;
                $idpAuditCompleteUAQuery = $idpAuditCompleteUAQueryBEGIN;
                $idpAuditQuery = $idpAuditQueryBEGIN;
                $errorCountBeforeFlush = 0;
                $archiveValidCountBeforeFlush = 0;
                $countUntilFlush = 1;
            }

            $line = fgets( $file );
            $lineNumber++;
            if( $debugMode && $verbose >= 8 ) $output->write('Line['.$lineNumber.'] ');

            if( !$line ){
                if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Read error. error code = '.error_get_last()['message'] );
                $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne '.$lineNumber.': Erreur de lecture. [Code erreur='.error_get_last()['message'].']')."'".',NULL,0,0),';
                $erreur++; $errorCountBeforeFlush++;
                fclose( $file );
                break;
            }
            $alreadyDone += strlen( $line );
            $percent = (int)(( $alreadyDone / $fileSize )*100 );

            $now = new DateTime();
            $estimated = new DateTime();
            $estimated->setTimestamp(((($now->getTimestamp()-$begin)/$alreadyDone)*$fileSize)+$begin);

            // On packetSize only
            $this->updateImport( $em, $import, IDPImport::IDP_IMPORT_STATUS_IN_PROGRESS, $percent, $estimated, $lineNumber, ($lineNumber-$erreur), $erreur );

            $lineArray = explode( ';', $line );
            if( $debugMode && $verbose >= 9 ){
                $output->writeln( 'IDX_STATUS: '.$lineArray[self::IDX_STATUS] );
                $output->writeln( 'IDX_SERVICE: '.$lineArray[self::IDX_SERVICE] );
                $output->writeln( 'IDX_LEGALENTITY: '.$lineArray[self::IDX_LEGALENTITY] );
                $output->writeln( 'IDX_BUDGETCODE: '.$lineArray[self::IDX_BUDGETCODE] );
                $output->writeln( 'IDX_ACTIVITY: '.$lineArray[self::IDX_ACTIVITY] );
                $output->writeln( 'IDX_DOCUMENTTYPE: '.$lineArray[self::IDX_DOCUMENTTYPE] );
                $output->writeln( 'IDX_DESCRIPTION1: '.$lineArray[self::IDX_DESCRIPTION1] );
                $output->writeln( 'IDX_DESCRIPTION2: '.$lineArray[self::IDX_DESCRIPTION2] );
                $output->writeln( 'IDX_CLOSUREDATE: '.$lineArray[self::IDX_CLOSUREDATE] );
                $output->writeln( 'IDX_DESTRUCTIONDATE: '.$lineArray[self::IDX_DESTRUCTIONDATE] );
                $output->writeln( 'IDX_DOCUMENTNUMBER: '.$lineArray[self::IDX_DOCUMENTNUMBER] );
                $output->writeln( 'IDX_BOXNUMBER: '.$lineArray[self::IDX_BOXNUMBER] );
                $output->writeln( 'IDX_CONTAINERNUMBER: '.$lineArray[self::IDX_CONTAINERNUMBER] );
                $output->writeln( 'IDX_PROVIDER: '.$lineArray[self::IDX_PROVIDER] );
                $output->writeln( 'IDX_ORDERNUMBER: '.$lineArray[self::IDX_ORDERNUMBER] );
                $output->writeln( 'IDX_LIMITDATEMIN: '.$lineArray[self::IDX_LIMITDATEMIN] );
                $output->writeln( 'IDX_LIMITDATEMAX: '.$lineArray[self::IDX_LIMITDATEMAX] );
                $output->writeln( 'IDX_LIMITNUMMIN: '.$lineArray[self::IDX_LIMITNUMMIN] );
                $output->writeln( 'IDX_LIMITNUMMAX: '.$lineArray[self::IDX_LIMITNUMMAX] );
                $output->writeln( 'IDX_LIMITALPHAMIN: '.$lineArray[self::IDX_LIMITALPHAMIN] );
                $output->writeln( 'IDX_LIMITALPHAMAX: '.$lineArray[self::IDX_LIMITALPHAMAX] );
                $output->writeln( 'IDX_LIMITALPHANUMMIN: '.$lineArray[self::IDX_LIMITALPHANUMMIN] );
                $output->writeln( 'IDX_LIMITALPHANUMMAX: '.$lineArray[self::IDX_LIMITALPHANUMMAX] );
                $output->writeln( 'IDX_LOCALIZATION: '.$lineArray[self::IDX_LOCALIZATION] );
                $output->writeln( 'IDX_ASKWHO: '.$lineArray[self::IDX_ASKWHO] );
                $output->writeln( 'IDX_ASKWHERE: '.$lineArray[self::IDX_ASKWHERE] );
                $output->writeln( 'IDX_ASKWHEN: '.$lineArray[self::IDX_ASKWHEN] );
                $output->writeln( 'IDX_NAME: '.$lineArray[self::IDX_NAME] );
                if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
            }

            $nbColumn = 0;
            for( $idx = 0; $idx < count($lineArray); $idx++ ) {
                $lineArray[$idx] = trim( $lineArray[$idx] );
                $nbColumn++;
            }

            if( $nbColumn < self::NB_COLUMN ){
                if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Wrong number of columns. found='.$nbColumn. ' / needed='.self::NB_COLUMN );
                $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne '.$lineNumber.': Erreur, il n\'y a pas le bon nombre de colonnes.')."'".',NULL,0,0),';
                $erreur++; $errorCountBeforeFlush++;
                if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                continue;	// Go to next line
            }

            // Order number is mandatory and is the primary key (so must not already exist)
            $ordernumber = null;
            if( strlen( $lineArray[self::IDX_ORDERNUMBER] ) <= 0 ){
                if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, No order number found !' );
                $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne '.$lineNumber.': Erreur, il n\'y a pas de numéro d\'ordre indiqué.')."'".',NULL,0,0),';
                $erreur++; $errorCountBeforeFlush++;
                if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                continue;	// Go to next line
            }
            if( strlen( $lineArray[self::IDX_ORDERNUMBER] ) != 9 ){
                if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Bad order number, it must be a 9 characters alphanumeric entry !' );
                $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne '.$lineNumber.': Erreur, le numéro d\'ordre doit contenir 9 caractères.')."'".',NULL,0,0),';
                $erreur++; $errorCountBeforeFlush++;
                if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                continue;	// Go to next line
            }
            $archive = $em->getRepository( 'bsIDPArchiveBundle:IDPArchive')->findOneBy( array( 'ordernumber' => $lineArray[self::IDX_ORDERNUMBER]) );
            if( $archive ){
                if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, The order number specified already exists in the DB, and it must be unique !' );
                $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne '.$lineNumber.': Erreur, il existe déjà une archive avec le numéro d\'ordre indiqué.')."'".',NULL,0,0),';
                $erreur++; $errorCountBeforeFlush++;
                if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                continue;	// Go to next line
            }
            $ordernumber = $lineArray[self::IDX_ORDERNUMBER];

            // Status is mandatory
            if( strlen( $lineArray[self::IDX_STATUS] ) <= 0 ){
                if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, No status found !' );
                $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne '.$lineNumber.': Erreur, le statut est obligatoire.')."'".',NULL,0,0),';
                $erreur++; $errorCountBeforeFlush++;
                if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                continue;	// Go to next line
            }
            // Status must be in the authorized ones
            if( !in_array( $lineArray[self::IDX_STATUS], $authorizedStatuses ) ){
                if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, The status found is not allowed ! '.$lineArray[self::IDX_STATUS] );
                $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne '.$lineNumber.': Erreur, le statut indiqué n\'est pas autorisé. ['. $lineArray[self::IDX_STATUS].']')."'".',NULL,0,0),';
                $erreur++; $errorCountBeforeFlush++;
                if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                continue;	// Go to next line
            }
            $status = $this->getStatus( $lineArray[self::IDX_STATUS], $statuses );
            if( $status == null ){
                if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, The status found is not a valid status ! '.$lineArray[self::IDX_STATUS] );
                $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne '.$lineNumber.': Erreur, le statut indiqué est inconnu. ['. $lineArray[self::IDX_STATUS].']')."'".',NULL,0,0),';
                $erreur++; $errorCountBeforeFlush++;
                if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                continue;	// Go to next line
            }

            // Service is mandatory
            if( strlen( $lineArray[self::IDX_SERVICE] ) <= 0 ){
                if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, No service found !' );
                $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne '.$lineNumber.': Erreur, le service est obligatoire.')."'".',NULL,0,0),';
                $erreur++; $errorCountBeforeFlush++;
                if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                continue;	// Go to next line
            }
            // Verify Service existence
            $service = null;
            foreach( $services as $serviceLocal )
                if( strcmp( $serviceLocal->getLongname(), $lineArray[self::IDX_SERVICE] ) == 0 ){
                    $service = $serviceLocal;
                    break;
                }

            if( !$service ){
                if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Unknown service ! '.$lineArray[self::IDX_SERVICE] );
                $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne '.$lineNumber.': Erreur service inconnu. ['.$lineArray[self::IDX_SERVICE].']')."'".',NULL,0,0),';
                $erreur++; $errorCountBeforeFlush++;
                if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                continue;	// Go to next line
            }

            if( $service->getId() != $lastServiceID ) {
                // Get settings (depends with service) (difficult to optimize for fastMode)
                $setting = $this->getContainer()->get('doctrine')
                    ->getRepository('bsIDPBackofficeBundle:IDPServiceSettings')
                    ->arrayFindOneByService($service->getId());

                if (!$setting) {
                    if ($debugMode && $verbose >= 7) $output->write('X');
                    if ($debugMode && $verbose >= 8) $output->writeln('<error>Error</error>, Service configuration not found the service ! ' . $lineArray[self::IDX_SERVICE]);
                    $importCommQuery .= '(' . $import_id . ',' . $percent . ',' . "'".$this->sanitizeString('Ligne ' . $lineNumber . ': Erreur de configuration, aucune configuration identifiée pour ce service !')."'" . ',NULL,0,0),';
                    $erreur++; $errorCountBeforeFlush++;
                    if ($debugMode && $stopOnError) $helper->ask($input, $output, $question);
                    continue;    // Go to next line
                }
                $setting = $setting[0];

                $lastServiceID = $service->getId();
            }

            // Legal entity is mandatory
            if( strlen( $lineArray[self::IDX_LEGALENTITY] ) <= 0 ){
                if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Legal entity not found ' );
                $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne '.$lineNumber.': Erreur, l\'entité légale est obligatoire.')."'".',NULL,0,0),';
                $erreur++; $errorCountBeforeFlush++;
                if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                continue;	// Go to next line
            }
            // Verify Legal Entity existence and link with Service
            $foundLE = false;
            $legalEntity = null;
            foreach( $legalEntities as $legalEntityLocal )
                if( strcmp( $legalEntityLocal->getLongname(), $lineArray[self::IDX_LEGALENTITY] ) == 0 ){
                    $foundLE = true;
                    foreach( $legalEntityLocal->getServices() as $legalEntityLocalService )
                        if( $legalEntityLocalService->getId() == $service->getId() ) {
                            $legalEntity = $legalEntityLocal;
                            break;
                        }
                }

            if( !$legalEntity ){
                if( !$foundLE ) {
                    if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Unknown legal entity [' . $lineArray[self::IDX_LEGALENTITY] . ']' );
                    $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne ' . $lineNumber . ': Erreur entité légale inconnue. [' . $lineArray[self::IDX_LEGALENTITY] . ']')."'".',NULL,0,0),';
                }else {
                    if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Legal entity found but not linked to the service [' . $lineArray[self::IDX_LEGALENTITY] . ',' . $lineArray[self::IDX_SERVICE] . ']' );
                    $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne ' . $lineNumber . ': Erreur entité légale existante mais non liée au service. [' . $lineArray[self::IDX_LEGALENTITY] . ',' . $lineArray[self::IDX_SERVICE] . ']')."'".',NULL,0,0),';
                }
                $erreur++; $errorCountBeforeFlush++;
                if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                continue;
            }

            // If budget code is set to visible and mandatory, it must be no-empty and must exist and must be linked with service
            // If budget code is set to visible and facultative, it could be empty, but if not must exist and must be linked with service
            // If budget code is set to invisible, just ignore it and set it to blank
            $budgetcode = null;
            if( $setting['view_budgetcode'] != 0 ){ // visible
                if( $setting['mandatory_budgetcode'] != 0 ){
                    if( strlen( $lineArray[self::IDX_BUDGETCODE] ) <= 0 ){
                        if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Budget code not found ' );
                        $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne '.$lineNumber.': Erreur, le code budgétaire est obligatoire par configuration du service.')."'".',NULL,0,0),';
                        $erreur++; $errorCountBeforeFlush++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                        continue;	// Go to next line
                    }
                }
                if( strlen( $lineArray[self::IDX_BUDGETCODE] ) > 0 ){
                    $foundBC = false;
                    $budgetcode = null;
                    foreach( $budgetCodes as $budgetCodeLocal )
                        if( strcmp( $lineArray[self::IDX_BUDGETCODE], $budgetCodeLocal->getLongname() ) == 0 ){
                            $foundBC = true;
                            foreach( $budgetCodeLocal->getServices() as $budgetCodeLocalService )
                                if( $budgetCodeLocalService->getId() == $service->getId() ){
                                    $budgetcode = $budgetCodeLocal;
                                    break;
                                }
                        }

                    if( !$budgetcode ){
                        if( !$foundBC ) {
                            if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Unknown budget code [' . $lineArray[self::IDX_BUDGETCODE] . ']' );
                            $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne ' . $lineNumber . ': Erreur code budgétaire inconnu. [' . $lineArray[self::IDX_BUDGETCODE] . ']')."'".',NULL,0,0),';
                        }else {
                            if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Budget code found but not linked to the service [' . $lineArray[self::IDX_BUDGETCODE] . ',' . $lineArray[self::IDX_SERVICE] . ']' );
                            $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne ' . $lineNumber . ': Erreur code budgétaire existant mais non lié au service. [' . $lineArray[self::IDX_BUDGETCODE] . ',' . $lineArray[self::IDX_SERVICE] . ']')."'".',NULL,0,0),';
                        }
                        $erreur++; $errorCountBeforeFlush++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                        continue;
                    }
                }
            }

            $activity = null;		// Document nature
            if( $setting['view_documentnature'] != 0 ){ // visible
                if( $setting['mandatory_documentnature'] != 0 ){
                    if( strlen( $lineArray[self::IDX_ACTIVITY] ) <= 0 ){
                        if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Activity code not found ' );
                        $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne '.$lineNumber.': Erreur, l\'activité est obligatoire par configuration du service.')."'".',NULL,0,0),';
                        $erreur++; $errorCountBeforeFlush++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                        continue;	// Go to next line
                    }
                }
                if( strlen( $lineArray[self::IDX_ACTIVITY] ) > 0 ){
                    $foundA = false;
                    $activity = null;
                    foreach ($activities as $activityLocal) {
                        if (strcmp($activityLocal->getLongname(), $lineArray[self::IDX_ACTIVITY]) == 0) {
                            $foundA = true;
                            foreach( $activityLocal->getServices() as $activityLocalService )
                                if ($activityLocalService->getId() == $service->getId()) {
                                    $activity = $activityLocal;
                                    break;
                                }
                        }
                    }
                    if( !$activity ){
                        if( !$foundA ) {
                            if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Unknown activity [' . $lineArray[self::IDX_ACTIVITY] . ']' );
                            $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne ' . $lineNumber . ': Erreur activité inconnue. [' . $lineArray[self::IDX_ACTIVITY] . ']')."'".',NULL,0,0),';
                        }else {
                            if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Activity found but not linked to the service [' . $lineArray[self::IDX_ACTIVITY] . ',' . $lineArray[self::IDX_SERVICE] . ']' );
                            $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne ' . $lineNumber . ': Erreur activité existante mais non lié au service. [' . $lineArray[self::IDX_ACTIVITY] . ',' . $lineArray[self::IDX_SERVICE] . ']')."'".',NULL,0,0),';
                        }
                        $erreur++; $errorCountBeforeFlush++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                        continue;
                    }
                }
            }

            $documenttype = null;		// Document type depends on activity
            if( $setting['view_documenttype'] != 0 ){ // visible
                if( $setting['mandatory_documenttype'] != 0 ){
                    if( strlen( $lineArray[self::IDX_DOCUMENTTYPE] ) <= 0 ){
                        if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Document type not found ' );
                        $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne '.$lineNumber.': Erreur, le type de document est obligatoire par configuration du service.')."'".',NULL,0,0),';
                        $erreur++; $errorCountBeforeFlush++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                        continue;	// Go to next line
                    }
                }
                if( strlen( $lineArray[self::IDX_DOCUMENTTYPE] ) > 0 ){
                    $foundDT = false;
                    $documenttype = null;
                    foreach ($documentTypes as $documentTypeLocal) {
                        if (strcmp($documentTypeLocal->getLongname(), $lineArray[self::IDX_DOCUMENTTYPE]) == 0) {
                            $foundDT = true;
                            foreach( $documentTypeLocal->getDocumentNatures() as $documentTypeLocalDocumentNature )
                                if ($documentTypeLocalDocumentNature->getId() == $activity->getId()) {
                                    $documenttype = $documentTypeLocal;
                                    break;
                                }
                        }
                    }
                    if( !$documenttype ){
                        if( !$foundDT ) {
                            if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Unknown Document type [' . $lineArray[self::IDX_DOCUMENTTYPE] . ']' );
                            $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne ' . $lineNumber . ': Erreur type de document inconnu. [' . $lineArray[self::IDX_DOCUMENTTYPE] . ']')."'".',NULL,0,0),';
                        }else {
                            if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Document type found but not linked with the activity [' . $lineArray[self::IDX_DOCUMENTTYPE] . ',' . $lineArray[self::IDX_ACTIVITY] . ']' );
                            $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne ' . $lineNumber . ': Erreur type de document existant mais non lié à l\'activité. [' . $lineArray[self::IDX_DOCUMENTTYPE] . ',' . $lineArray[self::IDX_ACTIVITY] . ']')."'".',NULL,0,0),';
                        }
                        $erreur++; $errorCountBeforeFlush++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                        continue;
                    }
                }
            }

            $description1 = null;		// Description1 depends on Service
            $name = $setting['name_description1'] != null ? $setting['name_description1'] : 'Description 1';
            if( $setting['view_description1'] != 0 ){ // visible
                if( $setting['mandatory_description1'] != 0 ){
                    if( strlen( $lineArray[self::IDX_DESCRIPTION1] ) <= 0 ){
                        if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Description 1 not found ' );
                        $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne '.$lineNumber.': Erreur, le(la) '.$name.' est obligatoire par configuration du service.')."'".',NULL,0,0),';
                        $erreur++; $errorCountBeforeFlush++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                        continue;	// Go to next line
                    }
                }
                if( strlen( $lineArray[self::IDX_DESCRIPTION1] ) > 0 ){
                    $foundD1 = false;
                    $description1 = null;
                    foreach ($descriptions1 as $description1Local) {
                        if( strcmp( $description1Local->getLongname(), $lineArray[self::IDX_DESCRIPTION1] ) == 0 ){
                            $foundD1 = true;
                            foreach( $description1Local->getServices() as $description1LocalService )
                                if( $description1LocalService->getId() == $service->getId() ){
                                    $description1 = $description1Local;
                                    break;
                                }
                        }
                    }
                    if( !$description1 ){
                        if( !$foundD1 ) {
                            if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Unknown description 1 [' . $lineArray[self::IDX_DESCRIPTION1] . ']' );
                            $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne ' . $lineNumber . ': Erreur ' . $name . ' inconnu. [' . $lineArray[self::IDX_DESCRIPTION1] . ']')."'".',NULL,0,0),';
                        }else {
                            if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Description 1 found but not linked with the service [' . $lineArray[self::IDX_DESCRIPTION1] . ',' . $lineArray[self::IDX_SERVICE] . ']' );
                            $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne ' . $lineNumber . ': Erreur ' . $name . ' existant mais non lié au service. [' . $lineArray[self::IDX_DESCRIPTION1] . ',' . $lineArray[self::IDX_SERVICE] . ']')."'".',NULL,0,0),';
                        }
                        $erreur++; $errorCountBeforeFlush++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                        continue;
                    }
                }
            }

            $description2 = null;		// Description2 depends on Service
            $name = $setting['name_description2'] != null ? $setting['name_description2'] : 'Description 2';
            if( $setting['view_description2'] != 0 ){ // visible
                if( $setting['mandatory_description2'] != 0 ){
                    if( strlen( $lineArray[self::IDX_DESCRIPTION2] ) <= 0 ){
                        if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Description 2 not found ' );
                        $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne '.$lineNumber.': Erreur, le(la) '.$name.' est obligatoire par configuration du service.')."'".',NULL,0,0),';
                        $erreur++; $errorCountBeforeFlush++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                        continue;	// Go to next line
                    }
                }
                if( strlen( $lineArray[self::IDX_DESCRIPTION2] ) > 0 ){
                    $foundD2 = false;
                    $description2 = null;
                    foreach( $descriptions2 as $description2Local ){
                        if( strcmp( $description2Local->getLongname(), $lineArray[self::IDX_DESCRIPTION2] ) == 0 ){
                            $foundD2 = true;
                            foreach( $description2Local->getServices() as $description2LocalService )
                                if( $description2LocalService->getId() == $service->getId() ){
                                    $description2 = $description2Local;
                                    break;
                                }
                        }
                    }
                    if( !$description2 ){
                        if( !$foundD2 ) {
                            if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Unknown description 2 [' . $lineArray[self::IDX_DESCRIPTION2] . ']' );
                            $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne ' . $lineNumber . ': Erreur ' . $name . ' inconnu. [' . $lineArray[self::IDX_DESCRIPTION2] . ']')."'".',NULL,0,0),';
                        }else {
                            if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Description 2 found but not linked with the service [' . $lineArray[self::IDX_DESCRIPTION2] . ',' . $lineArray[self::IDX_SERVICE] . ']' );
                            $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne ' . $lineNumber . ': Erreur ' . $name . ' existant mais non lié au service. [' . $lineArray[self::IDX_DESCRIPTION2] . ',' . $lineArray[self::IDX_SERVICE] . ']')."'".',NULL,0,0),';
                        }
                        $erreur++; $errorCountBeforeFlush++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                        continue;
                    }
                }
            }

            // Closure date is mandatory
            $closuredate = null;
            if( strlen( $lineArray[self::IDX_CLOSUREDATE] ) <= 0 ){
                if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Closure date not found ' );
                $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne '.$lineNumber.': Erreur la date de clôture est obligatoire.')."'".',NULL,0,0),';
                $erreur++; $errorCountBeforeFlush++;
                if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                continue;
            }
            $validFormat = true;
            if( strlen( $lineArray[self::IDX_CLOSUREDATE] ) != 4 ) $validFormat = false;
            if( !is_numeric( $lineArray[self::IDX_CLOSUREDATE] ) ) $validFormat = false;
            if( !$validFormat ){
                if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Closure date format expected is YYYY ' );
                $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne '.$lineNumber.': Erreur Le format de la date de clôture est incorrect, le format attendu est AAAA.')."'".',NULL,0,0),';
                $erreur++; $errorCountBeforeFlush++;
                if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                continue;
            }
            $closuredate = intval ( $lineArray[self::IDX_CLOSUREDATE] );

            // Destruction date is mandatory
            $destructiondate = null;
            if( strlen( $lineArray[self::IDX_DESTRUCTIONDATE] ) <= 0 ){
                if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Destruction date not found ' );
                $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne '.$lineNumber.': Erreur la date de destruction est obligatoire.')."'".',NULL,0,0),';
                $erreur++; $errorCountBeforeFlush++;
                if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                continue;
            }
            $validFormat = true;
            if( strlen( $lineArray[self::IDX_DESTRUCTIONDATE] ) != 4 ) $validFormat = false;
            if( !is_numeric( $lineArray[self::IDX_DESTRUCTIONDATE] ) ) $validFormat = false;
            if( !$validFormat ){
                if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Destruction date format expected is YYYY ' );
                $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne '.$lineNumber.': Erreur Le format de la date de destruction est incorrect, le format attendu est AAAA.')."'".',NULL,0,0),';
                $erreur++; $errorCountBeforeFlush++;
                if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                continue;
            }
            $destructiondate = intval ( $lineArray[self::IDX_DESTRUCTIONDATE] );

            // Document number is not mandatory
            $documentnumber = null;
            if( $setting['view_filenumber'] != 0 ) { // visible
                if ($setting['mandatory_filenumber'] != 0) {
                    if (strlen($lineArray[self::IDX_DOCUMENTNUMBER]) <= 0) {
                        if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Document number not found ' );
                        $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne ' . $lineNumber . ': Erreur, Le numéro de document est obligatoire par configuration du service.')."'".',NULL,0,0),';
                        $erreur++; $errorCountBeforeFlush++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                        continue;    // Go to next line
                    }
                }
                $tempDoc = trim( $lineArray[self::IDX_DOCUMENTNUMBER] );
                $documentnumber = empty( $tempDoc ) ? null : $tempDoc;
            }

            // Box number
            $boxnumber = null;
            if( $setting['view_boxnumber'] != 0 ) { // visible
                if ($setting['mandatory_boxnumber'] != 0) {
                    if (strlen($lineArray[self::IDX_BOXNUMBER]) <= 0) {
                        if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Box number not found ' );
                        $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne ' . $lineNumber . ': Erreur, Le numéro de boîte est obligatoire par configuration du service.')."'".',NULL,0,0),';
                        $erreur++; $errorCountBeforeFlush++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                        continue;    // Go to next line
                    }
                }
                $tempBox = trim( $lineArray[self::IDX_BOXNUMBER] );
                $boxnumber = empty( $tempBox ) ? null : $tempBox;
            }

            // Container number
            $containernumber = null;
            if( $setting['view_containernumber'] != 0 ) { // visible
                if ($setting['mandatory_containernumber'] != 0) {
                    if (strlen($lineArray[self::IDX_CONTAINERNUMBER]) <= 0) {
                        if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Container number not found ' );
                        $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne ' . $lineNumber . ': Erreur, Le numéro de conteneur est obligatoire par configuration du service.')."'".',NULL,0,0),';
                        $erreur++; $errorCountBeforeFlush++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                        continue;    // Go to next line
                    }
                }
                $tempContainer = trim( $lineArray[self::IDX_CONTAINERNUMBER] );
                $containernumber = empty( $tempContainer ) ? null : $tempContainer;
            }

            // Provider
            $provider = null;		// Provider
            if( $setting['view_provider'] != 0 ){ // visible
                if( $setting['mandatory_provider'] != 0 ){
                    if( strlen( $lineArray[self::IDX_PROVIDER] ) <= 0 ){
                        if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Provider not found ' );
                        $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne '.$lineNumber.': Erreur, le compte prestataire est obligatoire par configuration du service.')."'".',NULL,0,0),';
                        $erreur++; $errorCountBeforeFlush++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                        continue;	// Go to next line
                    }
                }
                if( strlen( $lineArray[self::IDX_PROVIDER] ) > 0 ){
                    $foundP = false;
                    $provider =  null;
                    foreach( $providers as $providerLocal ){
                        if( strcmp( $providerLocal->getLongname(), $lineArray[self::IDX_PROVIDER] ) == 0 ){
                            $foundP = true;
                            foreach( $providerLocal->getServices() as $providerLocalService )
                                if( $providerLocalService->getId() == $service->getId() ){
                                    $provider = $providerLocal;
                                    break;
                                }
                        }
                    }
                    if( !$provider ){
                        if( !$foundP ) {
                            if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Unknown provider [' . $lineArray[self::IDX_PROVIDER] . ']' );
                            $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne ' . $lineNumber . ': Erreur prestataire inconnu. [' . $lineArray[self::IDX_PROVIDER] . ']')."'".',NULL,0,0),';
                        }else {
                            if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Provider found but not linked with the service [' . $lineArray[self::IDX_PROVIDER] . ',' . $lineArray[self::IDX_SERVICE] . ']' );
                            $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne ' . $lineNumber . ': Erreur prestataire existant mais non lié au service. [' . $lineArray[self::IDX_PROVIDER] . ',' . $lineArray[self::IDX_SERVICE] . ']')."'".',NULL,0,0),';
                        }
                        $erreur++; $errorCountBeforeFlush++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                        continue;
                    }
                } else {
                    if( (strcmp( $lineArray[self::IDX_STATUS], 'DISP' )==0)||(strcmp( $lineArray[self::IDX_STATUS], 'CONP')==0)){
                        if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Provider not found ' );
                        $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne '.$lineNumber.': Erreur, le compte prestataire est obligatoire en statut '.$lineArray[self::IDX_STATUS].'.')."'".',NULL,0,0),';
                        $erreur++; $errorCountBeforeFlush++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                        continue;
                    }
                }
            }

            // Date limits
            $limitdatemin = null;
            $limitdatemax = null;
            if( $setting['view_limitsdate'] != 0 ){ // visible
                if( $setting['mandatory_limitsdate'] != 0 ){
                    if( ( strlen( $lineArray[self::IDX_LIMITDATEMIN] ) <= 0 )||( strlen( $lineArray[self::IDX_LIMITDATEMAX] ) <= 0 )){
                        if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Date limits not found ' );
                        $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne '.$lineNumber.': Erreur, les limites calendaires sont obligatoires par configuration du service.')."'".',NULL,0,0),';
                        $erreur++; $errorCountBeforeFlush++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                        continue;	// Go to next line
                    }
                } else {
                    if(( strlen( $lineArray[self::IDX_LIMITDATEMIN] ) <= 0 )xor( strlen( $lineArray[self::IDX_LIMITDATEMAX] ) <= 0 )){
                        if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Only one date limit found ' );
                        $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne '.$lineNumber.': Erreur, il faut les deux limites calendaires.')."'".',NULL,0,0),';
                        $erreur++; $errorCountBeforeFlush++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                        continue;	// Go to next line
                    }
                }
                if(!(( strlen( $lineArray[self::IDX_LIMITDATEMIN] ) <= 0 )and( strlen( $lineArray[self::IDX_LIMITDATEMAX] ) <= 0 ))) {
                    $limitdatemin = $this->parseDate($lineArray[self::IDX_LIMITDATEMIN]);

                    if ($limitdatemin == null) {
                        if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Date limit min format is not DD/MM/YYYY ' );
                        $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne ' . $lineNumber . ': Erreur, la borne calendaire inférieure n\'est pas au format attendu JJ/MM/AAAA. [' . $lineArray[self::IDX_LIMITDATEMIN] . ']')."'".',NULL,0,0),';
                        $erreur++; $errorCountBeforeFlush++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                        continue;    // Go to next line
                    }
                    $limitdatemax = $this->parseDate($lineArray[self::IDX_LIMITDATEMAX]);
                    if ($limitdatemax == null) {
                        if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Date limit max format is not DD/MM/YYYY ' );
                        $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne ' . $lineNumber . ': Erreur, la borne calendaire supérieure n\'est pas au format attendu JJ/MM/AAAA. [' . $lineArray[self::IDX_LIMITDATEMAX] . ']')."'".',NULL,0,0),';
                        $erreur++; $errorCountBeforeFlush++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                        continue;    // Go to next line
                    }
                    if (!$this->verifyDateOrder($limitdatemin, $limitdatemax)) {
                        if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Date limit min is upper than max ' );
                        $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne ' . $lineNumber . ': Erreur, la borne calendaire inférieure n\'est pas antérieure à la borne calendaire supérieure.')."'".',NULL,0,0),';
                        $erreur++; $errorCountBeforeFlush++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                        continue;    // Go to next line
                    }
                }
            }

            // Numeric limits
            $limitnummin = null;
            $limitnummax = null;

            if( $setting['view_limitsnum'] != 0 ){ // visible
                if( $setting['mandatory_limitsnum'] != 0 ){ // mandatory
                    if( ( strlen( $lineArray[self::IDX_LIMITNUMMIN] ) <= 0 )||( strlen( $lineArray[self::IDX_LIMITNUMMAX] ) <= 0 )){
                        if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Numeric limits not found ' );
                        $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne '.$lineNumber.': Erreur, les limites numériques sont obligatoires par configuration du service.')."'".',NULL,0,0),';
                        $erreur++; $errorCountBeforeFlush++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                        continue;	// Go to next line
                    }
                } else {
                    if(( strlen( $lineArray[self::IDX_LIMITNUMMIN] ) <= 0 )xor( strlen( $lineArray[self::IDX_LIMITNUMMAX] ) <= 0 )){
                        if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Both numeric limits needed ' );
                        $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne '.$lineNumber.': Erreur, il faut les deux limites numériques.')."'".',NULL,0,0),';
                        $erreur++; $errorCountBeforeFlush++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                        continue;	// Go to next line
                    }
                }
                if( !(( strlen( $lineArray[self::IDX_LIMITNUMMIN] ) <= 0 )and( strlen( $lineArray[self::IDX_LIMITNUMMAX] ) <= 0 ))) {
                    if( !is_numeric($lineArray[self::IDX_LIMITNUMMIN]) ){
                        if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Numeric limit min must be a number ' );
                        $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne '.$lineNumber.': Erreur, la limite numérique inférieure doit être un nombre entier.')."'".',NULL,0,0),';
                        $erreur++; $errorCountBeforeFlush++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                        continue;	// Go to next line
                    }
                    if( !is_numeric($lineArray[self::IDX_LIMITNUMMAX]) ){
                        if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Numeric limit max must be a number ' );
                        $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne '.$lineNumber.': Erreur, la limite numérique supérieure doit être un nombre entier.')."'".',NULL,0,0),';
                        $erreur++; $errorCountBeforeFlush++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                        continue;	// Go to next line
                    }

                    $limitnummin = intval($lineArray[self::IDX_LIMITNUMMIN]);
                    $limitnummax = intval($lineArray[self::IDX_LIMITNUMMAX]);
                    if ($limitnummin > $limitnummax) {
                        if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Numeric limit min is greater than max ! ' );
                        $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne ' . $lineNumber . ': Erreur, La limite numérique inférieure est plus grande que la limite numérique supérieure.')."'".',NULL,0,0),';
                        $erreur++; $errorCountBeforeFlush++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                        continue;    // Go to next line
                    }
                }
            }

            // Alpha limits
            $limitalphamin = null;
            $limitalphamax = null;
            if( $setting['view_limitsalpha'] != 0 ){ // visible
                if( $setting['mandatory_limitsalpha'] != 0 ){
                    if( ( strlen( $lineArray[self::IDX_LIMITALPHAMIN] ) <= 0 )||( strlen( $lineArray[self::IDX_LIMITALPHAMAX] ) <= 0 )){
                        if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Alphabetic limits not found ' );
                        $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne '.$lineNumber.': Erreur, les limites alphabétiques sont obligatoires par configuration du service.')."'".',NULL,0,0),';
                        $erreur++; $errorCountBeforeFlush++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                        continue;	// Go to next line
                    }
                } else {
                    if(( strlen( $lineArray[self::IDX_LIMITALPHAMIN] ) <= 0 )xor( strlen( $lineArray[self::IDX_LIMITALPHAMAX] ) <= 0 )){
                        if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Both alphabetic limits are needed ' );
                        $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne '.$lineNumber.': Erreur, il faut les deux limites alphabétiques.')."'".',NULL,0,0),';
                        $erreur++; $errorCountBeforeFlush++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                        continue;	// Go to next line
                    }
                }
                if(!(( strlen( $lineArray[self::IDX_LIMITALPHAMIN] ) <= 0 )and( strlen( $lineArray[self::IDX_LIMITALPHAMAX] ) <= 0 ))) {
                    if( !ctype_alpha( $lineArray[self::IDX_LIMITALPHAMIN] )){
                        if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Alphabetic limit min must only have letters ' );
                        $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne '.$lineNumber.': Erreur, la limite alphabétique inférieure ne doit contenir que des lettres.')."'".',NULL,0,0),';
                        $erreur++; $errorCountBeforeFlush++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                        continue;	// Go to next line
                    }
                    if( !ctype_alpha( $lineArray[self::IDX_LIMITALPHAMAX] )){
                        if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Alphabetic limit max must only have letters ' );
                        $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne '.$lineNumber.': Erreur, la limite alphabétique supérieure ne doit contenir que des lettres.')."'".',NULL,0,0),';
                        $erreur++; $errorCountBeforeFlush++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                        continue;	// Go to next line
                    }
                    $limitalphamin = $lineArray[self::IDX_LIMITALPHAMIN];
                    $limitalphamax = $lineArray[self::IDX_LIMITALPHAMAX];
                    if ($limitalphamin > $limitalphamax) {
                        if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Alphabetic limit min must be greater than max ' );
                        $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne ' . $lineNumber . ': Erreur, La limitie alphabétique inférieure est plus grande que la limite alphabétique supérieure.')."'".',NULL,0,0),';
                        $erreur++; $errorCountBeforeFlush++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                        continue;    // Go to next line
                    }
                }
            }

            // Alphanum limits
            $limitalphanummin = null;
            $limitalphanummax = null;
            if( $setting['view_limitsalphanum'] != 0 ){ // visible
                if( $setting['mandatory_limitsalphanum'] != 0 ){
                    if( ( strlen( $lineArray[self::IDX_LIMITALPHANUMMIN] ) <= 0 )||( strlen( $lineArray[self::IDX_LIMITALPHANUMMAX] ) <= 0 )){
                        if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Alpha-numeric limits not found ' );
                        $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne '.$lineNumber.': Erreur, les limites alpha-numériques sont obligatoires par configuration du service.')."'".',NULL,0,0),';
                        $erreur++; $errorCountBeforeFlush++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                        continue;	// Go to next line
                    }
                } else {
                    if(( strlen( $lineArray[self::IDX_LIMITALPHANUMMIN] ) <= 0 )xor( strlen( $lineArray[self::IDX_LIMITALPHANUMMAX] ) <= 0 )){
                        if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Both alpha-numeric limits are needed ' );
                        $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne '.$lineNumber.': Erreur, il faut les deux limites alpha-numériques.')."'".',NULL,0,0),';
                        $erreur++; $errorCountBeforeFlush++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                        continue;	// Go to next line
                    }
                }
                if(!(( strlen( $lineArray[self::IDX_LIMITALPHANUMMIN] ) <= 0 )and( strlen( $lineArray[self::IDX_LIMITALPHANUMMAX] ) <= 0 ))) {
                    $limitalphanummin = $lineArray[self::IDX_LIMITALPHANUMMIN];
                    $limitalphanummax = $lineArray[self::IDX_LIMITALPHANUMMAX];
                }
            }

            // Localisation, mandatory if not NAV (localization_free if DISI, DISINT, CONI, CONINT)
            $localization = null;
            $localizationfree = null;
            if( strcmp( $lineArray[self::IDX_STATUS], 'NAV' )!=0 ){
                if( strlen( $lineArray[self::IDX_LOCALIZATION] ) <= 0 ){
                    if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Localization not found ' );
                    $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne '.$lineNumber.': Erreur, Le champ localisation est obligatoire pour les statuts DISI, DISINT, DISP, CONI, CONINT et CONP.')."'".',NULL,0,0),';
                    $erreur++; $errorCountBeforeFlush++;
                    if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                    continue;	// Go to next line
                }

                if( (strcmp( $lineArray[self::IDX_STATUS], 'DISI')==0)||(strcmp( $lineArray[self::IDX_STATUS], 'DISINT')==0)
                    ||(strcmp( $lineArray[self::IDX_STATUS], 'CONI')==0)||(strcmp( $lineArray[self::IDX_STATUS], 'CONINT')==0)){
                    $localizationfree = $lineArray[ self::IDX_LOCALIZATION ];
                }

                if( (strcmp( $lineArray[self::IDX_STATUS], 'DISP' )==0)||(strcmp( $lineArray[self::IDX_STATUS], 'CONP')==0)){
                    if( $provider != null ) {
                        if( $provider->getLocalization() != null ) {
                            $foundL = false;

                            $localization = null;
                            foreach( $localizations as $localizationLocal ){
                                if( strcmp( $localizationLocal->getLongname(), $lineArray[self::IDX_LOCALIZATION] ) == 0 ){
                                    $foundL = true;
                                    if( $provider->getLocalization()->getId() == $localizationLocal->getId() ){
                                        $localization = $localizationLocal;
                                        break;
                                    }
                                }
                            }
                            if (!$localization) {
                                if (!$foundL) {
                                    if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Localization unknown [' . $lineArray[self::IDX_LOCALIZATION] . ']' );
                                    $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne ' . $lineNumber . ': Erreur localisation inconnue. [' . $lineArray[self::IDX_LOCALIZATION] . ']')."'".',NULL,0,0),';
                                }else {
                                    if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Localization found but not linked with the provider [' . $lineArray[self::IDX_LOCALIZATION] . ',' . $lineArray[self::IDX_SERVICE] . ']' );
                                    $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne ' . $lineNumber . ': Erreur localisation existante mais non lié au compte prestataire. [' . $lineArray[self::IDX_LOCALIZATION] . ',' . $lineArray[self::IDX_SERVICE] . ']')."'".',NULL,0,0),';
                                }
                                $erreur++; $errorCountBeforeFlush++;
                                if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                                continue;
                            }
                        } else {
                            if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Configuration error between provider and localization ' );
                            $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne ' . $lineNumber . ': Erreur de configuration entre le compte prestataire et la localisation. [' . $lineArray[self::IDX_PROVIDER] .']['. $lineArray[self::IDX_LOCALIZATION] . ']')."'".',NULL,0,0),';
                            $erreur++; $errorCountBeforeFlush++;
                            if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                            continue;
                        }
                    } else {
                        if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Localization impossible because provider not found [' . $lineArray[self::IDX_LOCALIZATION] . ']' );
                        $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne ' . $lineNumber . ': Erreur localisation impossible car compte prestataire absent. [' . $lineArray[self::IDX_LOCALIZATION] . ']')."'".',NULL,0,0),';
                        $erreur++; $errorCountBeforeFlush++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                        continue;
                    }
                }
            }

            // Ask who
            $askwho = null;
            if( (strcmp($lineArray[self::IDX_STATUS],'CONI')==0) || (strcmp($lineArray[self::IDX_STATUS],'CONINT')==0) || (strcmp($lineArray[self::IDX_STATUS],'CONP')==0) ){
                if( strlen( $lineArray[self::IDX_ASKWHO] ) <= 0 ){
                    if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Asking Who field not found ' );
                    $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne '.$lineNumber.': Erreur, Le champ nom du demandeur est obligatoire en statut '.$lineArray[self::IDX_STATUS].'.')."'".',NULL,0,0),';
                    $erreur++; $errorCountBeforeFlush++;
                    if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                    continue;	// Go to next line
                }
                $askwho = $lineArray[self::IDX_ASKWHO];
            }

            // Ask where (deliver address)
            $askwhere = null;
            if( (strcmp($lineArray[self::IDX_STATUS],'CONI')==0) || (strcmp($lineArray[self::IDX_STATUS],'CONINT')==0) || (strcmp($lineArray[self::IDX_STATUS],'CONP')==0) ) {
                if ( strlen($lineArray[self::IDX_ASKWHERE]) <= 0) {
                    if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Adress field not found ' );
                    $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne ' . $lineNumber . ': Erreur, Le champ adresse est obligatoire en statut'.$lineArray[self::IDX_STATUS].'.')."'".',NULL,0,0),';
                    $erreur++; $errorCountBeforeFlush++;
                    if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                    continue;    // Go to next line
                }
            }
            if( strlen($lineArray[self::IDX_ASKWHERE]) != 0) {
                if( strcmp( $lastAskWhere, $lineArray[self::IDX_ASKWHERE] ) != 0 ) {
                    $deliver = $em->getRepository('bsIDPBackofficeBundle:IDPDeliverAddress')->findOneByLongname($lineArray[self::IDX_ASKWHERE]);
                    $lastAskWhere = $lineArray[self::IDX_ASKWHERE];
                }
                if( !$deliver ){
                    if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Adress not found ' );
                    $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne '.$lineNumber.': Erreur Adresse inconnue. ['.$lineArray[self::IDX_ASKWHERE].']')."'".',NULL,0,0),';
                    $erreur++; $errorCountBeforeFlush++;
                    if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                    continue;	// Go to next line
                }
                $askwhere = $deliver;
            }

            // Ask when
            $askwhen = null;
            if( (strcmp($lineArray[self::IDX_STATUS],'CONI')==0) || (strcmp($lineArray[self::IDX_STATUS],'CONINT')==0) || (strcmp($lineArray[self::IDX_STATUS],'CONP')==0) ) {
                if (strlen($lineArray[self::IDX_ASKWHEN]) <= 0) {
                    if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Date field not found ' );
                    $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne ' . $lineNumber . ': Erreur, Le champ date de consultation est obligatoire en statut'.$lineArray[self::IDX_STATUS].'.')."'".',NULL,0,0),';
                    $erreur++; $errorCountBeforeFlush++;
                    if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                    continue;    // Go to next line
                }
            }
            if( strlen($lineArray[self::IDX_ASKWHEN]) != 0) {
                $askwhen = $this->parseDate($lineArray[self::IDX_ASKWHEN]);
                if ($askwhen == null) {
                    if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Consultation date format invalid, expected DD/MM/YYYY ' );
                    $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne ' . $lineNumber . ': Erreur, la date de consultation n\'est pas au format attendu JJ/MM/AAAA. [' . $lineArray[self::IDX_ASKWHEN] . ']')."'".',NULL,0,0),';
                    $erreur++; $errorCountBeforeFlush++;
                    if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                    continue;    // Go to next line
                }
            }

            //name is mandatory
            $name = null;
            if( strlen( $lineArray[self::IDX_NAME] ) <= 0 ){
                if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Archive name not found ' );
                $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne '.$lineNumber.': Erreur, le nom d\'archive est obligatoire.')."'".',NULL,0,0),';
                $erreur++; $errorCountBeforeFlush++;
                if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                continue;	// Go to next line
            }
            if( strlen( $lineArray[self::IDX_NAME] ) > 1000 ){
                if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Archive name length cannot be greater thant 10000 characters ' );
                $importCommQuery .= '('.$import_id.','.$percent.','."'".$this->sanitizeString('Ligne '.$lineNumber.': Erreur, Le nom d\'archive est supérieur à 1000 caractères.')."'".',NULL,0,0),';
                $erreur++; $errorCountBeforeFlush++;
                if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                continue;	// Go to next line
            }
            $name = $lineArray[self::IDX_NAME];

            // ===========================================================

            if( $debugMode && $verbose >= 8) $output->writeln( '<info>OK</info>' );

            $object_type =
                ( $containernumber != null && strlen( $containernumber ) > 0 ) ? IDPConstants::AUDIT_OBJECT_TYPE_CONTAINER :
                    ( ( $boxnumber != null && strlen( $boxnumber ) > 0 ) ? IDPConstants::AUDIT_OBJECT_TYPE_BOX :
                        ( ( $documentnumber != null && strlen( $documentnumber ) > 0 ) ? IDPConstants::AUDIT_OBJECT_TYPE_DOCUMENT :
                            IDPConstants::AUDIT_OBJECT_TYPE_UNKNOWN ) );

            // Everything OK, just add this archive to DB
            // `status_id`,`service_id`, `legalentity_id`, `budgetcode_id`,
            // `documentnature_id`,`documenttype_id`,`description1_id`,`description2_id`,`closureyear`,`destructionyear`,
            // `documentnumber`, `boxnumber`, `containernumber`, `provider_id`, `ordernumber`, `name`, `limitdatemin`,
            // `limitdatemax`, `limitnummin`, `limitnummax`, `limitalphamin`, `limitalphamax`, `limitalphanummin`,
            // `limitalphanummax`, `localization_id`, `localizationfree`, `precisionwho`, `precisiondate`, `precisionaddress_id`,
            // `objecttype`, `futureobjecttype`, `import_id`, `serviceentrydate`, `createdat`, `containerasked`, `boxasked`

            $archivesQuery .= ($archiveValidCountBeforeFlush>0?',':'').
                '('.$status->getID().','.$service->getId().','.$legalEntity->getId().','.
                (($budgetcode!=null)?$budgetcode->getId():'NULL').','.
                (($activity!=null)?$activity->getId():'NULL').','.
                (($documenttype!=null)?$documenttype->getId():'NULL').','.
                (($description1!=null)?$description1->getId():'NULL').','.
                (($description2!=null)?$description2->getId():'NULL').','.
                $closuredate.','.$destructiondate.','.
                (($documentnumber!=null)?"'".$this->sanitizeString($documentnumber)."'":'NULL').','.
                (($boxnumber!=null)?"'".$this->sanitizeString($boxnumber)."'":'NULL').','.
                (($containernumber!=null)?"'".$this->sanitizeString($containernumber)."'":'NULL').','.
                (($provider!=null)?$provider->getId():'NULL').','.
                (($ordernumber!=null)?"'".$ordernumber."'":'NULL').','.
                (($name!=null)?"'".$this->sanitizeString($name)."'":'NULL').','.
                (($limitdatemin!=null)?"'".$lineArray[self::IDX_LIMITDATEMIN]."'":'NULL').','.
                (($limitdatemax!=null)?"'".$lineArray[self::IDX_LIMITDATEMAX]."'":'NULL').','.
                (($limitnummin!=null)?$limitnummin:'NULL').','.
                (($limitnummax!=null)?$limitnummax:'NULL').','.
                (($limitalphamin!=null)?"'".$this->sanitizeString($limitalphamin)."'":'NULL').','.
                (($limitalphamax!=null)?"'".$this->sanitizeString($limitalphamax)."'":'NULL').','.
                (($limitalphanummin!=null)?"'".$this->sanitizeString($limitalphanummin)."'":'NULL').','.
                (($limitalphanummax!=null)?"'".$this->sanitizeString($limitalphanummax)."'":'NULL').','.
                (($localization!=null)?$localization->getId():'NULL').','.
                (($localizationfree!=null)?"'".$this->sanitizeString($localizationfree)."'":'NULL').','.
                (($askwho!=null)?"'".$this->sanitizeString($askwho)."'":'NULL').','.
                (($askwhen!=null)?"'".$askwhen->format('Y-m-d H:m:s')."'":'NULL').','.
                (($askwhere!=null)?$askwhere->getId():'NULL').','.
                (($object_type!=null)?$object_type:'NULL').','.
                (($object_type!=null)?$object_type:'NULL').','.
                $import_id.','."$timestamp,'$createdAt',0,0)";

            // Add an archive import/creation to audit table
            $statusStr = $status->getShortname();
            $auditLocalization = 0;
            if((strcmp($statusStr, 'DISI')==0)||(strcmp($statusStr, 'CONI')==0))
                $auditLocalization = 1;
            else if((strcmp($statusStr, 'DISINT')==0)||(strcmp($statusStr, 'CONINT')==0))
                $auditLocalization = 2;
            else if((strcmp($statusStr, 'DISP')==0)||(strcmp($statusStr, 'CONP')==0))
                $auditLocalization = 3;

            // `id`, `service_id`, `legal_entity_id`, `localization`,
            // `provider_id`, `budget_code_id`, `document_nature_id`, `document_type_id`
            $idpAuditCompleteUAQuery .= ($archiveValidCountBeforeFlush>0?',':'').
                '('.$idpAuditCompleteUA_ID.','.
                $service->getId().','.
                $legalEntity->getId().','.
                $auditLocalization.','.
                (($provider!=null)?$provider->getId():'NULL').','.
                (($budgetcode!=null)?$budgetcode->getId():'NULL').','.
                (($activity!=null)?$activity->getId():'NULL').','.
                (($documenttype!=null)?$documenttype->getId():'NULL').')';

            // `userId`, `timestamp`, `field`, `new_str`, `new_int`, `old_str`,
            // `old_int`, `entity`, `entity_id`, `action`, `complete_ua_id`, `objectType`
            $idpAuditQuery .= ($archiveValidCountBeforeFlush>0?',':'').'('.
                'NULL,'.                                    // user_id
                $timestamp.','.                             // timestamp
                IDPConstants::FIELD_NA.','.                 // field
                'NULL,'.                                    // new string value
                'NULL,'.                                    // new int value
                'NULL,'.                                    // old string value
                'NULL,'.                                    // old int value
                IDPConstants::ENTITY_ARCHIVE.','.           // entity
                $idpArchive_ID.','.                         // id of entity concerned
                IDPConstants::AUDIT_ACTION_IMPORT.','.      // action
                $idpAuditCompleteUA_ID.','.                 // link to complete UA values
                ($object_type?$object_type:'NULL').')';                           // type of object during movement

            $idpAuditCompleteUA_ID++;
            $idpArchive_ID++;
            $archiveValidCountBeforeFlush++;
        }

        $importCommQuery .= '('.$import_id.',100,'."'".$this->sanitizeString('Nombre de lignes traitées: '.$lineNumber.' / Nombre de lignes avec erreur: '.$erreur)."'".',NULL,1,0),';
        $errorCountBeforeFlush++;

        $importCommQuery[strlen($importCommQuery)-1] = ' '; // Just removing unusefull comma

        if( $countUntilFlush != 0 ) {
            $statement = $conn->prepare($archivesQuery);
            $statement->execute();
            $statement = $conn->prepare($idpAuditCompleteUAQuery);
            $statement->execute();
            $statement = $conn->prepare($idpAuditQuery);
            $statement->execute();
        }
        if( $errorCountBeforeFlush>0 ){
            $statement = $conn->prepare($importCommQuery);
            $statement->execute();
        }

        $output->writeln('');
        $endTime = time();
        if( $output ){
            $secs = $endTime - $beginTime;
            $h = $secs / 3600 % 24;
            $m = $secs / 60 % 60;
            $s = $secs % 60;

            $output->writeln( "Import time = $h h: $m m: $s s" );
        }

        if( $debugMode && $verbose >= 1) $output->writeln( 'Treated lines <info>'.$lineNumber.'</info> / Error lines <error>'.$erreur.'</error>' );

        $this->updateImport( $em, $import,
            ($erreur>0?IDPImport::IDP_IMPORT_STATUS_ERROR:IDPImport::IDP_IMPORT_STATUS_END),
            100, null, $lineNumber, $lineNumber-$erreur, $erreur);
        $this->endImport( $em, $globalStatuses, $import );

        if( $debugMode && $verbose >= 1) $output->writeln( 'End of Importation' );

        return;

    }

    // Parse a string into a Date. String format must be DD/MM/YYYY
    private function parseDate( $txtDate ){
        if( $txtDate == null )
            return null;
        if( strlen($txtDate) != 10 )
            return null;
        $date = null;
        try {
            $date = DateTime::createFromFormat('d/m/Y', $txtDate);
        } catch( Exception $e ){
            return null;
        }
        return $date;
    }

    private function verifyDateOrder( $datemin, $datemax ){
        $min = intval ($datemin->format('Y')) * 10000 + intval ($datemin->format('m')) * 100 + intval ($datemin->format('d'));
        $max = intval ($datemax->format('Y')) * 10000 + intval ($datemax->format('m')) * 100 + intval ($datemax->format('d'));
        return ($min<=$max);
    }

    private function getStatus( $txtStatus, $listStatuses ){
        if(( $listStatuses == null )||( $txtStatus == null )||( strlen( $txtStatus ) <= 0 ))
            return null;

        foreach ($listStatuses as $status)
            if( $status->getShortname() == strtoupper( $txtStatus ) )
                return $status;

        return null;
    }

    private function beginImport( $em, $globalStatuses ){
        $globalStatuses->setImportInProgress( true );
        $em->persist($globalStatuses);
        $em->flush();
    }
    private function updateGlobal( $em, $globalStatuses, $import ){
        $globalStatuses->setCurrentImportId( $import->getId() );
        $em->persist($globalStatuses);
        $em->flush();
    }
    private function endImport( $em, $globalStatuses, $import ){
        $globalStatuses->setImportInProgress( false );
        $now = new DateTime();
        $import->setDateEnd( $now );
        $em->persist($globalStatuses);
        $em->persist($import);
        $em->flush();
    }
    private function createNewImport( $em, $filename ){
        $now = new DateTime();

        $import = new IDPImport();
        $import->setFilename($filename);
        $import->setProgress( 0 );
        $import->setStatus( IDPImport::IDP_IMPORT_STATUS_START );
        $import->setDateBegin( $now );

        $em->persist( $import );
        $em->flush();

        return( $import );
    }
    private function updateImport( $em, $import, $status=null, $progress=null, $estimated_end=null, $nbLinesFile=null, $nbLinesImported=null, $nbLinesError=null ){

        if( $status != null )
            $import->setStatus($status);

        if( $status != IDPImport::IDP_IMPORT_STATUS_END ){
            if( $progress != null )
                $import->setProgress( $progress );
            if( $estimated_end != null )
                $import->setEstimatedEnd( $estimated_end );
            if( $nbLinesFile != null )
                $import->setNbLinesFile( $nbLinesFile );
            if( $nbLinesImported != null )
                $import->setNbLinesImported( $nbLinesImported );
            if( $nbLinesError != null )
                $import->setNbLinesError( $nbLinesError );
        }

        $em->persist( $import );
        $em->flush();
    }

    private function sanitizeString( $input ){
        $bad = array("'");
        $good = array("''");
        return str_replace($bad, $good, $input);
    }
}

?>