<?php

// src/bs/IDP/ArchiveBundle/Command/ImportAsyncCommand.php
namespace bs\IDP\ArchiveBundle\Command;

use bs\IDP\ArchiveBundle\Entity\IDPAuditCompleteUa;
use \DateTime;

use MongoDB\BSON\Timestamp;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

use bs\IDP\ArchiveBundle\Entity\IDPImportComm;
use bs\IDP\BackofficeBundle\Entity\IDPGlobalStatuses;
use bs\IDP\ArchiveBundle\Entity\IDPImport;
use bs\IDP\ArchiveBundle\Entity\IDPArchive;

use bs\IDP\ArchiveBundle\ConstantDefinition\IDPConstants;

class ImportAsyncCommand extends ContainerAwareCommand
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
        // php bin/console app:import-file ImportFile.csv --fast --debug --debuglvl=5 --stopOnError --blocksize=25

		$this
		    ->setName('app:import-file')
		    ->setDescription('Import a file into database')
		    ->addArgument('filename', InputArgument::REQUIRED, 'name of file to import, must be in web/import/ directory')
            ->addOption('fast', 'f', InputOption::VALUE_NONE, 'fast mode activated (memory usage increased)')
            ->addOption('debug', 'd', InputOption::VALUE_NONE, 'debug mode activated')
            ->addOption('debuglvl', 'l', InputOption::VALUE_REQUIRED, 'Verbosity of debug [1-9]', 1 )
            ->addOption('stopOnError', 's', InputOption::VALUE_NONE, 'When error occur in debug mode, waits for user input to continue')
            ->addOption('blocksize', 'b', InputOption::VALUE_REQUIRED, 'Size of Block for flushing to DB [1-1000]', 1 )
		;
	}

	private function addDBEntry( $em, $import, $percent, $status, $message, $rawline = null, $output = null, $outputEnabled = false, $flushToDB = true ){

		$idpImportComm = new IDPImportComm();
		$idpImportComm->setImportId( $import->getId() );
		$idpImportComm->setPercent( $percent );
		$idpImportComm->setStatus( $status );
		$idpImportComm->setMessage( $message );
		if( $rawline )
		    $idpImportComm->setRawLine( $rawline );
		$idpImportComm->setAlreadyRead( 0 );

		$em->persist($idpImportComm);
		if( $flushToDB )
            $em->flush();


		if( $outputEnabled && $output ){
			$output->writeln( $percent . ': ' . ($message==null?'':$message) );
		}
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

	protected function execute(InputInterface $input, OutputInterface $output)
	{
        $filename = $input->getArgument('filename');
        $fastMode = ($input->getOption('fast')?true:false);
        $debugMode = ($input->getOption('debug')?true:false);
        $verbose = $input->getOption('debuglvl');
        if( $verbose < 1 ) $verbose = 1; if( $verbose > 9 ) $verbose = 9;
        $stopOnError = ($input->getOption('stopOnError')?true:false);
        $blockSize = $input->getOption('blocksize');
        if( $blockSize < 1 ) $blockSize = 1; if( $blockSize > 1000 ) $blockSize = 1000;

        $output->writeln( 'filename = '.$filename );
        $output->writeln( 'fastMode = '.($fastMode?'ON':'OFF') );
        $output->writeln( 'debugMode = '.($debugMode?'ON':'OFF') );
        $output->writeln( 'verbose = '.$verbose );
        $output->writeln( 'stopOnError = '.($stopOnError?'ON':'OFF') );
        $output->writeln( 'blocksize = '.$blockSize );

        $beginTime = time();
        $countUntilFlush = 0;

        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion('Type any key to continue !', false);
        if( $output && $debugMode ) {
            $helper->ask($input, $output, $question);
        }

        if( $debugMode && $verbose >= 1 ) $output->writeln('<info>Import v1.0</info>');

        $em = $this->getContainer()->get('doctrine')->getManager();

	    $globalStatuses = $this->getContainer()->get('doctrine')->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses')->findOneBy(array('id' => 1 ));
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 0 ) {
            if( $debugMode && $verbose >= 1) $output->writeln( '<error>Reconciliation in progress, cannot import !</error>' );
            return;
        }

        if( $debugMode && $verbose >= 1 ) $output->writeln('Import is starting');
        $this->beginImport( $em, $globalStatuses );

        if( $debugMode && $verbose >= 5 ) $output->writeln('Create import structure');
        $import = $this->createNewImport( $em, $filename );
        $now = new DateTime();
        $begin = $now->getTimestamp();
        $this->updateGlobal( $em, $globalStatuses, $import );

        if( $debugMode && $verbose >= 5 ) $output->write('Verification of file existence : ');
		$fullName = __DIR__.'/../../../../../web/import/' . $filename;
		if( !file_exists( $fullName ) ){
            if( $debugMode && $verbose >= 1) $output->writeln( '<error>Error</error>, file not found ! File='.$filename );
			$this->addDBEntry( $em, $import, 0, 0, 'Erreur: fichier introuvable '.$filename, null, null, true );
			$this->updateImport( $em, $import, IDPImport::IDP_IMPORT_STATUS_ERROR );
			$this->endImport( $em, $globalStatuses, $import );
			return;
		}
        if( $debugMode && $verbose >= 5) $output->writeln( '<info>OK</info>');

        if( $debugMode && $verbose >= 5 ) $output->write('Get the fileSize to calculate percent : ');
		$fileSize = filesize( $fullName );
		if( $fileSize <= 0 ){
            if( $debugMode && $verbose >= 1) $output->writeln( '<error>Error</error>, file empty !');
			$this->addDBEntry( $em, $import, 0, 0, 'Erreur: fichier vide ', null, null, true );
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
            if( $debugMode && $verbose >= 1) $output->writeln( '<error>Error</error>, File encoding is not UTF-8' );
            $this->addDBEntry( $em, $import, 0, 0, 'Erreur: l\'encodage du fichier n\'est pas UTF-8', null, null, true );
            $this->updateImport( $em, $import, IDPImport::IDP_IMPORT_STATUS_ERROR );
            $this->endImport( $em, $globalStatuses, $import );
            return;
        } if( $debugMode && $verbose >= 5) $output->writeln( '<info>OK</info>');

        if( $debugMode && $verbose >= 5 ) $output->write('Open file to begin treatment : ');
        $file = fopen( $fullName, "r" );
		if( !$file ){
            if( $debugMode && $verbose >= 1) $output->writeln( '<error>Error</error>, cannot open the file ! [Error code='.error_get_last()['message'] );
			$this->addDBEntry( $em, $import, 0, 0, 'Erreur: impossible d\'ouvrir le fichier '.$filename.' [Code erreur='.error_get_last()['message'].']', null, null, true );
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

        // Fast mode, we will put in memory all link tables
        if( $fastMode ){
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
        }

		// Treat each line of file until end of it
        if( $debugMode && $verbose >= 1 ) $output->writeln('Beginning of lines treatment');

		while( !feof( $file ) ){
            $countUntilFlush++;
            $flushToDB = ($countUntilFlush >= $blockSize);
            if( $countUntilFlush >= $blockSize)
                $countUntilFlush = 0;

			$line = fgets( $file );
			$lineNumber++;
            if( $debugMode && $verbose >= 8 ) $output->write('Line['.$lineNumber.'] ');

			if( !$line ){
                if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Read error. error code = '.error_get_last()['message'] );
                $this->addDBEntry( $em, $import, $percent, 0, 'Ligne '.$lineNumber.': Erreur de lecture. [Code erreur='.error_get_last()['message'].']', null, null, true, $flushToDB );
				$erreur++;
				fclose( $file );
				break;
			}
			$alreadyDone += strlen( $line );
			$percent = (int)(( $alreadyDone / $fileSize )*100 );

            $now = new DateTime();
            $estimated = new DateTime();
            $estimated->setTimestamp(((($now->getTimestamp()-$begin)/$alreadyDone)*$fileSize)+$begin);

			$this->addDBEntry( $em, $import, $percent, 1, 'Analyse de la ligne '.$lineNumber, null, null, true, $flushToDB );
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
				$this->addDBEntry( $em, $import, $percent, 0, 'Ligne '.$lineNumber.': Erreur, il n\'y a pas le bon nombre de colonnes.', $line, null, true, $flushToDB );
				$erreur++;
				if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
				continue;	// Go to next line
			}

            // Order number is mandatory and is the primary key (so must not already exist)
            $ordernumber = null;
            if( strlen( $lineArray[self::IDX_ORDERNUMBER] ) <= 0 ){
                if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, No order number found !' );
                $this->addDBEntry( $em, $import, $percent, 0, 'Ligne '.$lineNumber.': Erreur, il n\'y a pas de numéro d\'ordre indiqué.', $line, null, true, $flushToDB );
                $erreur++;
                if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                continue;	// Go to next line
            }
            if( strlen( $lineArray[self::IDX_ORDERNUMBER] ) != 9 ){
                if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Bad order number, it must be a 9 characters alphanumeric entry !' );
                $this->addDBEntry( $em, $import, $percent, 0, 'Ligne '.$lineNumber.': Erreur, le numéro d\'ordre doit contenir 9 caractères.', $line, null, true, $flushToDB );
                $erreur++;
                if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                continue;	// Go to next line
            }
            $archive = $em->getRepository( 'bsIDPArchiveBundle:IDPArchive')->findOneBy( array( 'ordernumber' => $lineArray[self::IDX_ORDERNUMBER]) );
            if( $archive ){
                if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, The order number specified already exists in the DB, and it must be unique !' );
                $this->addDBEntry( $em, $import, $percent, 0, 'Ligne '.$lineNumber.': Erreur, il existe déjà une archive avec le numéro d\'ordre indiqué.', $line, null, true, $flushToDB );
                $erreur++;
                if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                continue;	// Go to next line
            }
            $ordernumber = $lineArray[self::IDX_ORDERNUMBER];

            // Status is mandatory
			if( strlen( $lineArray[self::IDX_STATUS] ) <= 0 ){
                if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, No status found !' );
				$this->addDBEntry( $em, $import, $percent, 0, 'Ligne '.$lineNumber.': Erreur, le statut est obligatoire.', $line, null, true, $flushToDB );
				$erreur++;
                if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
				continue;	// Go to next line
			}
			// Status must be in the authorized ones
            if( !in_array( $lineArray[self::IDX_STATUS], $authorizedStatuses ) ){
                if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, The status found is not allowed ! '.$lineArray[self::IDX_STATUS] );
                $this->addDBEntry( $em, $import, $percent, 0, 'Ligne '.$lineNumber.': Erreur, le statut indiqué n\'est pas autorisé. ['. $lineArray[self::IDX_STATUS].']', $line, null, true, $flushToDB );
                $erreur++;
                if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                continue;	// Go to next line
            }
			$status = $this->getStatus( $lineArray[self::IDX_STATUS], $statuses );
			if( $status == null ){
                if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, The status found is not a valid status ! '.$lineArray[self::IDX_STATUS] );
				$this->addDBEntry( $em, $import, $percent, 0, 'Ligne '.$lineNumber.': Erreur, le statut indiqué est inconnu. ['. $lineArray[self::IDX_STATUS].']', $line, null, true, $flushToDB );
				$erreur++;
                if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
				continue;	// Go to next line
			}

			// Service is mandatory
			if( strlen( $lineArray[self::IDX_SERVICE] ) <= 0 ){
                if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, No service found !' );
				$this->addDBEntry( $em, $import, $percent, 0, 'Ligne '.$lineNumber.': Erreur, le service est obligatoire.', $line, null, true, $flushToDB );
				$erreur++;
                if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
				continue;	// Go to next line
			}
			// Verify Service existence
            if( !$fastMode )
			    $service = $em->getRepository( 'bsIDPBackofficeBundle:IDPServices')->findOneByLongname( $lineArray[self::IDX_SERVICE] );
            else{
                $service = null;
                foreach( $services as $serviceLocal )
                    if( strcmp( $serviceLocal->getLongname(), $lineArray[self::IDX_SERVICE] ) == 0 ){
                        $service = $serviceLocal;
                        break;
                    }
            }

			if( !$service ){
                if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Unknown service ! '.$lineArray[self::IDX_SERVICE] );
				$this->addDBEntry( $em, $import, $percent, 0, 'Ligne '.$lineNumber.': Erreur service inconnu. ['.$lineArray[self::IDX_SERVICE].']', $line, null, true, $flushToDB );
				$erreur++;
                if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
				continue;	// Go to next line
			}

            // Get settings (depends with service) (difficult to optimize for fastMode)
            $setting = $this->getContainer()->get('doctrine')
                ->getRepository('bsIDPBackofficeBundle:IDPServiceSettings')
                ->arrayFindOneByService( $service->getId() );

            if( !$setting ){
                if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Service configuration not found the service ! '.$lineArray[self::IDX_SERVICE] );
                $this->addDBEntry( $em, $import, $percent, 0, 'Ligne '.$lineNumber.': Erreur de configuration, aucune configuration identifiée pour ce service !', $line, null, true, $flushToDB );
                $erreur++;
                if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                continue;	// Go to next line
            }
            $setting = $setting[0];

            // Legal entity is mandatory
			if( strlen( $lineArray[self::IDX_LEGALENTITY] ) <= 0 ){
                if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Legal entity not found ' );
				$this->addDBEntry( $em, $import, $percent, 0, 'Ligne '.$lineNumber.': Erreur, l\'entité légale est obligatoire.', $line, null, true, $flushToDB );
				$erreur++;
                if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
				continue;	// Go to next line
			}
			// Verify Legal Entity existence and link with Service
            $foundLE = false;
            if( !$fastMode )
			    $legalEntity = $em->getRepository( 'bsIDPBackofficeBundle:IDPLegalEntities')->findOneWithConstraint( $lineArray[self::IDX_LEGALENTITY], $service->getId() );
            else {
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
            }

			if( !$legalEntity ){
			    if( !$fastMode )
                    $foundLE = $em->getRepository( 'bsIDPBackofficeBundle:IDPLegalEntities' )->findOneByLongname( $lineArray[self::IDX_LEGALENTITY] );

				if( !$foundLE ) {
                    if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Unknown legal entity [' . $lineArray[self::IDX_LEGALENTITY] . ']' );
                    $this->addDBEntry($em, $import, $percent, 0, 'Ligne ' . $lineNumber . ': Erreur entité légale inconnue. [' . $lineArray[self::IDX_LEGALENTITY] . ']', $line, null, true, $flushToDB);
                }else {
                    if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Legal entity found but not linked to the service [' . $lineArray[self::IDX_LEGALENTITY] . ',' . $lineArray[self::IDX_SERVICE] . ']' );
                    $this->addDBEntry($em, $import, $percent, 0, 'Ligne ' . $lineNumber . ': Erreur entité légale existante mais non liée au service. [' . $lineArray[self::IDX_LEGALENTITY] . ',' . $lineArray[self::IDX_SERVICE] . ']', $line, null, true, $flushToDB);
                }
                $erreur++;
                if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
				continue;
			} else {
			    if( !$fastMode )
				    $legalEntity = $legalEntity[0];
			}

			// If budget code is set to visible and mandatory, it must be no-empty and must exist and must be linked with service
			// If budget code is set to visible and facultative, it could be empty, but if not must exist and must be linked with service
			// If budget code is set to invisible, just ignore it and set it to blank
			$budgetcode = null;
			if( $setting['view_budgetcode'] != 0 ){ // visible
				if( $setting['mandatory_budgetcode'] != 0 ){
					if( strlen( $lineArray[self::IDX_BUDGETCODE] ) <= 0 ){
                        if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Budget code not found ' );
						$this->addDBEntry( $em, $import, $percent, 0, 'Ligne '.$lineNumber.': Erreur, le code budgétaire est obligatoire par configuration du service.', $line, null, true, $flushToDB );
						$erreur++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
						continue;	// Go to next line
					}
				}
				if( strlen( $lineArray[self::IDX_BUDGETCODE] ) > 0 ){
				    $foundBC = false;
				    if( $fastMode )
					    $budgetcode = $em->getRepository( 'bsIDPBackofficeBundle:IDPBudgetCodes')->findOneWithConstraint( $lineArray[self::IDX_BUDGETCODE], $service->getId() );
				    else{
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
                    }
					if( !$budgetcode ){
					    if( !$fastMode )
					        $foundBC = $em->getRepository( 'bsIDPBackofficeBundle:IDPBudgetCodes' )->findOneByLongname( $lineArray[self::IDX_BUDGETCODE] );

						if( !$foundBC ) {
                            if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Unknown budget code [' . $lineArray[self::IDX_BUDGETCODE] . ']' );
                            $this->addDBEntry($em, $import, $percent, 0, 'Ligne ' . $lineNumber . ': Erreur code budgétaire inconnu. [' . $lineArray[self::IDX_BUDGETCODE] . ']', $line, null, true, $flushToDB);
                        }else {
                            if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Budget code found but not linked to the service [' . $lineArray[self::IDX_BUDGETCODE] . ',' . $lineArray[self::IDX_SERVICE] . ']' );
                            $this->addDBEntry($em, $import, $percent, 0, 'Ligne ' . $lineNumber . ': Erreur code budgétaire existant mais non lié au service. [' . $lineArray[self::IDX_BUDGETCODE] . ',' . $lineArray[self::IDX_SERVICE] . ']', $line, null, true, $flushToDB);
                        }
						$erreur++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
						continue;
					} else {
					    if( !$fastMode )
						    $budgetcode = $budgetcode[0];
					}
				}
			}

			$activity = null;		// Document nature
			if( $setting['view_documentnature'] != 0 ){ // visible
				if( $setting['mandatory_documentnature'] != 0 ){
					if( strlen( $lineArray[self::IDX_ACTIVITY] ) <= 0 ){
                        if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Activity code not found ' );
                        $this->addDBEntry( $em, $import, $percent, 0, 'Ligne '.$lineNumber.': Erreur, l\'activité est obligatoire par configuration du service.', $line, null, true, $flushToDB );
						$erreur++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
						continue;	// Go to next line
					}
				}
				if( strlen( $lineArray[self::IDX_ACTIVITY] ) > 0 ){
				    $foundA = false;
				    if( !$fastMode )
					    $activity = $em->getRepository( 'bsIDPBackofficeBundle:IDPDocumentNatures')->findOneWithConstraint( $lineArray[self::IDX_ACTIVITY], $service->getId() );
				    else {
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
                    }
					if( !$activity ){
					    if( !$fastMode )
					        $foundA = $em->getRepository( 'bsIDPBackofficeBundle:IDPDocumentNatures' )->findOneByLongname( $lineArray[self::IDX_ACTIVITY] );
						if( !$foundA ) {
                            if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Unknown activity [' . $lineArray[self::IDX_ACTIVITY] . ']' );
                            $this->addDBEntry($em, $import, $percent, 0, 'Ligne ' . $lineNumber . ': Erreur activité inconnue. [' . $lineArray[self::IDX_ACTIVITY] . ']', $line, null, true, $flushToDB);
                        }else {
                            if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Activity found but not linked to the service [' . $lineArray[self::IDX_ACTIVITY] . ',' . $lineArray[self::IDX_SERVICE] . ']' );
                            $this->addDBEntry($em, $import, $percent, 0, 'Ligne ' . $lineNumber . ': Erreur activité existante mais non lié au service. [' . $lineArray[self::IDX_ACTIVITY] . ',' . $lineArray[self::IDX_SERVICE] . ']', $line, null, true, $flushToDB);
                        }
						$erreur++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
						continue;
					} else {
					    if( !$fastMode)
						    $activity = $activity[0];
					}
				}
			}

			$documenttype = null;		// Document type depends on activity
			if( $setting['view_documenttype'] != 0 ){ // visible
				if( $setting['mandatory_documenttype'] != 0 ){
					if( strlen( $lineArray[self::IDX_DOCUMENTTYPE] ) <= 0 ){
                        if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Document type not found ' );
						$this->addDBEntry( $em, $import, $percent, 0, 'Ligne '.$lineNumber.': Erreur, le type de document est obligatoire par configuration du service.', $line, null, true, $flushToDB );
						$erreur++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
						continue;	// Go to next line
					}
				}
				if( strlen( $lineArray[self::IDX_DOCUMENTTYPE] ) > 0 ){
				    $foundDT = false;
				    if( !$fastMode )
					    $documenttype = $em->getRepository( 'bsIDPBackofficeBundle:IDPDocumentTypes')->findOneWithConstraint( $lineArray[self::IDX_DOCUMENTTYPE], $activity->getId() );
				    else {
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
                    }
					if( !$documenttype ){
					    if( !$fastMode )
					        $foundDT = $em->getRepository( 'bsIDPBackofficeBundle:IDPDocumentTypes' )->findOneByLongname( $lineArray[self::IDX_DOCUMENTTYPE] );
						if( !$foundDT ) {
                            if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Unknown Document type [' . $lineArray[self::IDX_DOCUMENTTYPE] . ']' );
                            $this->addDBEntry($em, $import, $percent, 0, 'Ligne ' . $lineNumber . ': Erreur type de document inconnu. [' . $lineArray[self::IDX_DOCUMENTTYPE] . ']', $line, null, true, $flushToDB);
                        }else {
                            if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Document type found but not linked with the activity [' . $lineArray[self::IDX_DOCUMENTTYPE] . ',' . $lineArray[self::IDX_ACTIVITY] . ']' );
                            $this->addDBEntry($em, $import, $percent, 0, 'Ligne ' . $lineNumber . ': Erreur type de document existant mais non lié à l\'activité. [' . $lineArray[self::IDX_DOCUMENTTYPE] . ',' . $lineArray[self::IDX_ACTIVITY] . ']', $line, null, true, $flushToDB);
                        }
						$erreur++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
						continue;
					} else {
					    if( !$fastMode )
						    $documenttype =  $documenttype[0];
					}
				}
			}

			$description1 = null;		// Description1 depends on Service
            $name = $setting['name_description1'] != null ? $setting['name_description1'] : 'Description 1';
			if( $setting['view_description1'] != 0 ){ // visible
				if( $setting['mandatory_description1'] != 0 ){
					if( strlen( $lineArray[self::IDX_DESCRIPTION1] ) <= 0 ){
                        if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Description 1 not found ' );
						$this->addDBEntry( $em, $import, $percent, 0, 'Ligne '.$lineNumber.': Erreur, le(la) '.$name.' est obligatoire par configuration du service.', $line, null, true, $flushToDB );
						$erreur++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
						continue;	// Go to next line
					}
				}
				if( strlen( $lineArray[self::IDX_DESCRIPTION1] ) > 0 ){
				    $foundD1 = false;
				    if( !$fastMode )
					    $description1 = $em->getRepository( 'bsIDPBackofficeBundle:IDPDescriptions1')->findOneWithConstraint( $lineArray[self::IDX_DESCRIPTION1], $service->getId() );
				    else {
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
                    }
					if( !$description1 ){
					    if( !$fastMode )
					        $foundD1 = $em->getRepository( 'bsIDPBackofficeBundle:IDPDescriptions1' )->findOneByLongname( $lineArray[self::IDX_DESCRIPTION1] );
						if( !$foundD1 ) {
                            if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Unknown description 1 [' . $lineArray[self::IDX_DESCRIPTION1] . ']' );
                            $this->addDBEntry($em, $import, $percent, 0, 'Ligne ' . $lineNumber . ': Erreur ' . $name . ' inconnu. [' . $lineArray[self::IDX_DESCRIPTION1] . ']', $line, null, true, $flushToDB);
                        }else {
                            if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Description 1 found but not linked with the service [' . $lineArray[self::IDX_DESCRIPTION1] . ',' . $lineArray[self::IDX_SERVICE] . ']' );
                            $this->addDBEntry($em, $import, $percent, 0, 'Ligne ' . $lineNumber . ': Erreur ' . $name . ' existant mais non lié au service. [' . $lineArray[self::IDX_DESCRIPTION1] . ',' . $lineArray[self::IDX_SERVICE] . ']', $line, null, true, $flushToDB);
                        }
						$erreur++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
						continue;
					} else {
					    if( !$fastMode)
						    $description1 = $description1[0];
					}
				}
			}

			$description2 = null;		// Description2 depends on Service
            $name = $setting['name_description2'] != null ? $setting['name_description2'] : 'Description 2';
			if( $setting['view_description2'] != 0 ){ // visible
				if( $setting['mandatory_description2'] != 0 ){
					if( strlen( $lineArray[self::IDX_DESCRIPTION2] ) <= 0 ){
                        if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Description 2 not found ' );
						$this->addDBEntry( $em, $import, $percent, 0, 'Ligne '.$lineNumber.': Erreur, le(la) '.$name.' est obligatoire par configuration du service.', $line, null, true, $flushToDB );
						$erreur++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
						continue;	// Go to next line
					}
				}
				if( strlen( $lineArray[self::IDX_DESCRIPTION2] ) > 0 ){
				    $foundD2 = false;
				    if( !$fastMode )
					    $description2 = $em->getRepository( 'bsIDPBackofficeBundle:IDPDescriptions2')->findOneWithConstraint( $lineArray[self::IDX_DESCRIPTION2], $service->getId() );
				    else {
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
                    }
					if( !$description2 ){
					    if( !$fastMode )
					        $foundD2 = $em->getRepository( 'bsIDPBackofficeBundle:IDPDescriptions2' )->findOneByLongname( $lineArray[self::IDX_DESCRIPTION2] );
						if( !$foundD2 ) {
                            if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Unknown description 2 [' . $lineArray[self::IDX_DESCRIPTION2] . ']' );
                            $this->addDBEntry($em, $import, $percent, 0, 'Ligne ' . $lineNumber . ': Erreur ' . $name . ' inconnu. [' . $lineArray[self::IDX_DESCRIPTION2] . ']', $line, null, true, $flushToDB);
                        }else {
                            if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Description 2 found but not linked with the service [' . $lineArray[self::IDX_DESCRIPTION2] . ',' . $lineArray[self::IDX_SERVICE] . ']' );
                            $this->addDBEntry($em, $import, $percent, 0, 'Ligne ' . $lineNumber . ': Erreur ' . $name . ' existant mais non lié au service. [' . $lineArray[self::IDX_DESCRIPTION2] . ',' . $lineArray[self::IDX_SERVICE] . ']', $line, null, true, $flushToDB);
                        }
						$erreur++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
						continue;
					} else {
					    if( !$fastMode )
						    $description2 = $description2[0];
					}
				}
			}

			// Closure date is mandatory
            $closuredate = null;
            if( strlen( $lineArray[self::IDX_CLOSUREDATE] ) <= 0 ){
                if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Closure date not found ' );
                $this->addDBEntry( $em, $import, $percent, 0, 'Ligne '.$lineNumber.': Erreur la date de clôture est obligatoire.', $line, null, true, $flushToDB );
                $erreur++;
                if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                continue;
            }
            $validFormat = true;
            if( strlen( $lineArray[self::IDX_CLOSUREDATE] ) != 4 ) $validFormat = false;
            if( !is_numeric( $lineArray[self::IDX_CLOSUREDATE] ) ) $validFormat = false;
            if( !$validFormat ){
                if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Closure date format expected is YYYY ' );
                $this->addDBEntry( $em, $import, $percent, 0, 'Ligne '.$lineNumber.': Erreur Le format de la date de clôture est incorrect, le format attendu est AAAA.', $line, null, true, $flushToDB );
                $erreur++;
                if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                continue;
            }
            $closuredate = intval ( $lineArray[self::IDX_CLOSUREDATE] );

            // Destruction date is mandatory
            $destructiondate = null;
            if( strlen( $lineArray[self::IDX_DESTRUCTIONDATE] ) <= 0 ){
                if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Destruction date not found ' );
                $this->addDBEntry( $em, $import, $percent, 0, 'Ligne '.$lineNumber.': Erreur la date de destruction est obligatoire.', $line, null, true, $flushToDB );
                $erreur++;
                if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                continue;
            }
            $validFormat = true;
            if( strlen( $lineArray[self::IDX_DESTRUCTIONDATE] ) != 4 ) $validFormat = false;
            if( !is_numeric( $lineArray[self::IDX_DESTRUCTIONDATE] ) ) $validFormat = false;
            if( !$validFormat ){
                if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Destruction date format expected is YYYY ' );
                $this->addDBEntry( $em, $import, $percent, 0, 'Ligne '.$lineNumber.': Erreur Le format de la date de destruction est incorrect, le format attendu est AAAA.', $line, null, true, $flushToDB );
                $erreur++;
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
                        $this->addDBEntry($em, $import, $percent, 0, 'Ligne ' . $lineNumber . ': Erreur, Le numéro de document est obligatoire par configuration du service.', $line, null, true, $flushToDB);
                        $erreur++;
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
                        $this->addDBEntry($em, $import, $percent, 0, 'Ligne ' . $lineNumber . ': Erreur, Le numéro de boîte est obligatoire par configuration du service.', $line, null, true, $flushToDB);
                        $erreur++;
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
                        $this->addDBEntry($em, $import, $percent, 0, 'Ligne ' . $lineNumber . ': Erreur, Le numéro de conteneur est obligatoire par configuration du service.', $line, null, true, $flushToDB);
                        $erreur++;
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
                        $this->addDBEntry( $em, $import, $percent, 0, 'Ligne '.$lineNumber.': Erreur, le compte prestataire est obligatoire par configuration du service.', $line, null, true, $flushToDB );
                        $erreur++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                        continue;	// Go to next line
                    }
                }
                if( strlen( $lineArray[self::IDX_PROVIDER] ) > 0 ){
                    $foundP = false;
                    if( !$fastMode )
                        $provider = $em->getRepository( 'bsIDPBackofficeBundle:IDPProviders')->findOneWithConstraint( $lineArray[self::IDX_PROVIDER], $service->getId() );
                    else{
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
                    }
                    if( !$provider ){
                        if( !$fastMode )
                            $foundP = $em->getRepository( 'bsIDPBackofficeBundle:IDPProviders' )->findOneByLongname( $lineArray[self::IDX_PROVIDER] );
                        if( !$foundP ) {
                            if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Unknown provider [' . $lineArray[self::IDX_PROVIDER] . ']' );
                            $this->addDBEntry($em, $import, $percent, 0, 'Ligne ' . $lineNumber . ': Erreur prestataire inconnu. [' . $lineArray[self::IDX_PROVIDER] . ']', $line, null, true, $flushToDB);
                        }else {
                            if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Provider found but not linked with the service [' . $lineArray[self::IDX_PROVIDER] . ',' . $lineArray[self::IDX_SERVICE] . ']' );
                            $this->addDBEntry($em, $import, $percent, 0, 'Ligne ' . $lineNumber . ': Erreur prestataire existant mais non lié au service. [' . $lineArray[self::IDX_PROVIDER] . ',' . $lineArray[self::IDX_SERVICE] . ']', $line, null, true, $flushToDB);
                        }
                        $erreur++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                        continue;
                    } else {
                        if( !$fastMode )
                            $provider = $provider[0];
                    }
                } else {
                    if( (strcmp( $lineArray[self::IDX_STATUS], 'DISP' )==0)||(strcmp( $lineArray[self::IDX_STATUS], 'CONP')==0)){
                        if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Provider not found ' );
                        $this->addDBEntry( $em, $import, $percent, 0, 'Ligne '.$lineNumber.': Erreur, le compte prestataire est obligatoire en statut '.$lineArray[self::IDX_STATUS].'.', $line, null, true, $flushToDB );
                        $erreur++;
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
                        $this->addDBEntry( $em, $import, $percent, 0, 'Ligne '.$lineNumber.': Erreur, les limites calendaires sont obligatoires par configuration du service.', $line, null, true, $flushToDB );
                        $erreur++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                        continue;	// Go to next line
                    }
                } else {
                    if(( strlen( $lineArray[self::IDX_LIMITDATEMIN] ) <= 0 )xor( strlen( $lineArray[self::IDX_LIMITDATEMAX] ) <= 0 )){
                        if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Only one date limit found ' );
                        $this->addDBEntry( $em, $import, $percent, 0, 'Ligne '.$lineNumber.': Erreur, il faut les deux limites calendaires.', $line, null, true, $flushToDB );
                        $erreur++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                        continue;	// Go to next line
                    }
                }
                if(!(( strlen( $lineArray[self::IDX_LIMITDATEMIN] ) <= 0 )and( strlen( $lineArray[self::IDX_LIMITDATEMAX] ) <= 0 ))) {
                    $limitdatemin = $this->parseDate($lineArray[self::IDX_LIMITDATEMIN]);

                    if ($limitdatemin == null) {
                        if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Date limit min format is not DD/MM/YYYY ' );
                        $this->addDBEntry($em, $import, $percent, 0, 'Ligne ' . $lineNumber . ': Erreur, la borne calendaire inférieure n\'est pas au format attendu JJ/MM/AAAA. [' . $lineArray[self::IDX_LIMITDATEMIN] . ']', $line, null, true, $flushToDB);
                        $erreur++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                        continue;    // Go to next line
                    }
                    $limitdatemax = $this->parseDate($lineArray[self::IDX_LIMITDATEMAX]);
                    if ($limitdatemax == null) {
                        if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Date limit max format is not DD/MM/YYYY ' );
                        $this->addDBEntry($em, $import, $percent, 0, 'Ligne ' . $lineNumber . ': Erreur, la borne calendaire supérieure n\'est pas au format attendu JJ/MM/AAAA. [' . $lineArray[self::IDX_LIMITDATEMAX] . ']', $line, null, true, $flushToDB);
                        $erreur++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                        continue;    // Go to next line
                    }
                    if (!$this->verifyDateOrder($limitdatemin, $limitdatemax)) {
                        if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Date limit min is upper than max ' );
                        $this->addDBEntry($em, $import, $percent, 0, 'Ligne ' . $lineNumber . ': Erreur, la borne calendaire inférieure n\'est pas antérieure à la borne calendaire supérieure.', $line, null, true, $flushToDB);
                        $erreur++;
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
                        $this->addDBEntry( $em, $import, $percent, 0, 'Ligne '.$lineNumber.': Erreur, les limites numériques sont obligatoires par configuration du service.', $line, null, true, $flushToDB );
                        $erreur++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                        continue;	// Go to next line
                    }
                } else {
                    if(( strlen( $lineArray[self::IDX_LIMITNUMMIN] ) <= 0 )xor( strlen( $lineArray[self::IDX_LIMITNUMMAX] ) <= 0 )){
                        if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Both numeric limits needed ' );
                        $this->addDBEntry( $em, $import, $percent, 0, 'Ligne '.$lineNumber.': Erreur, il faut les deux limites numériques.', $line, null, true, $flushToDB );
                        $erreur++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                        continue;	// Go to next line
                    }
                }
                if( !(( strlen( $lineArray[self::IDX_LIMITNUMMIN] ) <= 0 )and( strlen( $lineArray[self::IDX_LIMITNUMMAX] ) <= 0 ))) {
                    if( !is_numeric($lineArray[self::IDX_LIMITNUMMIN]) ){
                        if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Numeric limit min must be a number ' );
                        $this->addDBEntry( $em, $import, $percent, 0, 'Ligne '.$lineNumber.': Erreur, la limite numérique inférieure doit être un nombre entier.', $line, null, true, $flushToDB );
                        $erreur++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                        continue;	// Go to next line
                    }
                    if( !is_numeric($lineArray[self::IDX_LIMITNUMMAX]) ){
                        if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Numeric limit max must be a number ' );
                        $this->addDBEntry( $em, $import, $percent, 0, 'Ligne '.$lineNumber.': Erreur, la limite numérique supérieure doit être un nombre entier.', $line, null, true, $flushToDB );
                        $erreur++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                        continue;	// Go to next line
                    }

                    $limitnummin = intval($lineArray[self::IDX_LIMITNUMMIN]);
                    $limitnummax = intval($lineArray[self::IDX_LIMITNUMMAX]);
                    if ($limitnummin > $limitnummax) {
                        if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Numeric limit min is greater than max ! ' );
                        $this->addDBEntry($em, $import, $percent, 0, 'Ligne ' . $lineNumber . ': Erreur, La limite numérique inférieure est plus grande que la limite numérique supérieure.', $line, null, true, $flushToDB);
                        $erreur++;
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
                        $this->addDBEntry( $em, $import, $percent, 0, 'Ligne '.$lineNumber.': Erreur, les limites alphabétiques sont obligatoires par configuration du service.', $line, null, true, $flushToDB );
                        $erreur++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                        continue;	// Go to next line
                    }
                } else {
                    if(( strlen( $lineArray[self::IDX_LIMITALPHAMIN] ) <= 0 )xor( strlen( $lineArray[self::IDX_LIMITALPHAMAX] ) <= 0 )){
                        if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Both alphabetic limits are needed ' );
                        $this->addDBEntry( $em, $import, $percent, 0, 'Ligne '.$lineNumber.': Erreur, il faut les deux limites alphabétiques.', $line, null, true, $flushToDB );
                        $erreur++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                        continue;	// Go to next line
                    }
                }
                if(!(( strlen( $lineArray[self::IDX_LIMITALPHAMIN] ) <= 0 )and( strlen( $lineArray[self::IDX_LIMITALPHAMAX] ) <= 0 ))) {
                    if( !ctype_alpha( $lineArray[self::IDX_LIMITALPHAMIN] )){
                        if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Alphabetic limit min must only have letters ' );
                        $this->addDBEntry( $em, $import, $percent, 0, 'Ligne '.$lineNumber.': Erreur, la limite alphabétique inférieure ne doit contenir que des lettres.', $line, null, true, $flushToDB );
                        $erreur++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                        continue;	// Go to next line
                    }
                    if( !ctype_alpha( $lineArray[self::IDX_LIMITALPHAMAX] )){
                        if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Alphabetic limit max must only have letters ' );
                        $this->addDBEntry( $em, $import, $percent, 0, 'Ligne '.$lineNumber.': Erreur, la limite alphabétique supérieure ne doit contenir que des lettres.', $line, null, true, $flushToDB );
                        $erreur++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                        continue;	// Go to next line
                    }
                    $limitalphamin = $lineArray[self::IDX_LIMITALPHAMIN];
                    $limitalphamax = $lineArray[self::IDX_LIMITALPHAMAX];
                    if ($limitalphamin > $limitalphamax) {
                        if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Alphabetic limit min must be greater than max ' );
                        $this->addDBEntry($em, $import, $percent, 0, 'Ligne ' . $lineNumber . ': Erreur, La limitie alphabétique inférieure est plus grande que la limite alphabétique supérieure.', $line, null, true, $flushToDB);
                        $erreur++;
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
                        $this->addDBEntry( $em, $import, $percent, 0, 'Ligne '.$lineNumber.': Erreur, les limites alpha-numériques sont obligatoires par configuration du service.', $line, null, true, $flushToDB );
                        $erreur++;
                        if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                        continue;	// Go to next line
                    }
                } else {
                    if(( strlen( $lineArray[self::IDX_LIMITALPHANUMMIN] ) <= 0 )xor( strlen( $lineArray[self::IDX_LIMITALPHANUMMAX] ) <= 0 )){
                        if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Both alpha-numeric limits are needed ' );
                        $this->addDBEntry( $em, $import, $percent, 0, 'Ligne '.$lineNumber.': Erreur, il faut les deux limites alpha-numériques.', $line, null, true, $flushToDB );
                        $erreur++;
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
                    $this->addDBEntry( $em, $import, $percent, 0, 'Ligne '.$lineNumber.': Erreur, Le champ localisation est obligatoire pour les statuts DISI, DISINT, DISP, CONI, CONINT et CONP.', $line, null, true, $flushToDB );
                    $erreur++;
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

                            if( !$fastMode )
                                $localization = $em->getRepository('bsIDPBackofficeBundle:IDPLocalizations')->findOneWithConstraint($lineArray[self::IDX_LOCALIZATION], $provider->getLocalization()->getId());
                            else {
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
                            }
                            if (!$localization) {
                                if( !$fastMode )
                                    $foundL = $em->getRepository('bsIDPBackofficeBundle:IDPLocalizations')->findOneByLongname($lineArray[self::IDX_LOCALIZATION]);
                                if (!$foundL) {
                                    if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Localization unknown [' . $lineArray[self::IDX_LOCALIZATION] . ']' );
                                    $this->addDBEntry($em, $import, $percent, 0, 'Ligne ' . $lineNumber . ': Erreur localisation inconnue. [' . $lineArray[self::IDX_LOCALIZATION] . ']', $line, null, true, $flushToDB);
                                }else {
                                    if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Localization found but not linked with the provider [' . $lineArray[self::IDX_LOCALIZATION] . ',' . $lineArray[self::IDX_SERVICE] . ']' );
                                    $this->addDBEntry($em, $import, $percent, 0, 'Ligne ' . $lineNumber . ': Erreur localisation existante mais non lié au compte prestataire. [' . $lineArray[self::IDX_LOCALIZATION] . ',' . $lineArray[self::IDX_SERVICE] . ']', $line, null, true, $flushToDB);
                                }
                                $erreur++;
                                if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                                continue;
                            } else {
                                if( !$fastMode )
                                    $localization = $localization[0];
                            }
                        } else {
                            if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Configuration error between provider and localization ' );
                            $this->addDBEntry($em, $import, $percent, 0, 'Ligne ' . $lineNumber . ': Erreur de configuration entre le compte prestataire et la localisation. [' . $lineArray[self::IDX_PROVIDER] .']['. $lineArray[self::IDX_LOCALIZATION] . ']', $line, null, true, $flushToDB);
                            $erreur++;
                            if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                            continue;
                        }
                    } else {
                        if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Localization impossible because provider not found [' . $lineArray[self::IDX_LOCALIZATION] . ']' );
                        $this->addDBEntry($em, $import, $percent, 0, 'Ligne ' . $lineNumber . ': Erreur localisation impossible car compte prestataire absent. [' . $lineArray[self::IDX_LOCALIZATION] . ']', $line, null, true, $flushToDB);
                        $erreur++;
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
                    $this->addDBEntry( $em, $import, $percent, 0, 'Ligne '.$lineNumber.': Erreur, Le champ nom du demandeur est obligatoire en statut '.$lineArray[self::IDX_STATUS].'.', $line, null, true, $flushToDB );
                    $erreur++;
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
                    $this->addDBEntry($em, $import, $percent, 0, 'Ligne ' . $lineNumber . ': Erreur, Le champ adresse est obligatoire en statut'.$lineArray[self::IDX_STATUS].'.', $line, null, true, $flushToDB);
                    $erreur++;
                    if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                    continue;    // Go to next line
                }
            }
            if( strlen($lineArray[self::IDX_ASKWHERE]) != 0) {
                $deliver = $em->getRepository( 'bsIDPBackofficeBundle:IDPDeliverAddress')->findOneByLongname( $lineArray[self::IDX_ASKWHERE] );
                if( !$deliver ){
                    if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Adress not found ' );
                    $this->addDBEntry( $em, $import, $percent, 0, 'Ligne '.$lineNumber.': Erreur Adresse inconnue. ['.$lineArray[self::IDX_ASKWHERE].']', $line, null, true, $flushToDB );
                    $erreur++;
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
                    $this->addDBEntry($em, $import, $percent, 0, 'Ligne ' . $lineNumber . ': Erreur, Le champ date de consultation est obligatoire en statut'.$lineArray[self::IDX_STATUS].'.', $line, null, true, $flushToDB);
                    $erreur++;
                    if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                    continue;    // Go to next line
                }
            }
            if( strlen($lineArray[self::IDX_ASKWHEN]) != 0) {
                $askwhen = $this->parseDate($lineArray[self::IDX_ASKWHEN]);
                if ($askwhen == null) {
                    if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Consultation date format invalid, expected DD/MM/YYYY ' );
                    $this->addDBEntry($em, $import, $percent, 0, 'Ligne ' . $lineNumber . ': Erreur, la date de consultation n\'est pas au format attendu JJ/MM/AAAA. [' . $lineArray[self::IDX_ASKWHEN] . ']', $line, null, true, $flushToDB);
                    $erreur++;
                    if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                    continue;    // Go to next line
                }
            }

            //name is mandatory
            $name = null;
            if( strlen( $lineArray[self::IDX_NAME] ) <= 0 ){
                if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Archive name not found ' );
                $this->addDBEntry( $em, $import, $percent, 0, 'Ligne '.$lineNumber.': Erreur, le nom d\'archive est obligatoire.', $line, null, true, $flushToDB );
                $erreur++;
                if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                continue;	// Go to next line
            }
            if( strlen( $lineArray[self::IDX_NAME] ) > 1000 ){
                if( $debugMode && $verbose >= 7 ) $output->write('X'); if( $debugMode && $verbose >= 8) $output->writeln( '<error>Error</error>, Archive name length cannot be greater thant 10000 characters ' );
                $this->addDBEntry( $em, $import, $percent, 0, 'Ligne '.$lineNumber.': Erreur, Le nom d\'archive est supérieur à 1000 caractères.', $line, null, true, $flushToDB );
                $erreur++;
                if( $debugMode && $stopOnError ) $helper->ask($input, $output, $question);
                continue;	// Go to next line
            }
            $name = $lineArray[self::IDX_NAME];

            // ===========================================================

            if( $output ){
                if( $lineNumber % $blockSize == 0) $output->write('.');
            }
            if( $debugMode && $verbose >= 8) $output->writeln( '<info>OK</info>' );

			// Everything OK, just add this archive to DB
			$archive = new IDPArchive( );
            $archive->setImportId( $import->getId() );

            $archive->setServiceentrydate( $begin );
            $archive->setCreatedat( $now );

            $archive->setStatus( $status );
			$archive->setService( $service );
			$archive->setLegalentity( $legalEntity );
			$archive->setBudgetcode( $budgetcode );
			$archive->setDocumentnature( $activity );
			$archive->setDocumenttype( $documenttype );
			$archive->setDescription1( $description1 );
			$archive->setDescription2( $description2 );
            $archive->setClosureyear( $closuredate );
            $archive->setDestructionyear( $destructiondate );
            $archive->setDocumentnumber( $documentnumber );
            $archive->setBoxnumber( $boxnumber );
            $archive->setContainernumber( $containernumber );
			$archive->setProvider( $provider );
            $archive->setOrdernumber( $ordernumber );
            $archive->setName( $name );
            $archive->setLimitdatemin( $limitdatemin );
            $archive->setLimitdatemax( $limitdatemax );
			$archive->setLimitnummin( $limitnummin );
			$archive->setLimitnummax( $limitnummax );
			$archive->setLimitalphamin( $limitalphamin );
			$archive->setLimitalphamax( $limitalphamax );
			$archive->setLimitalphanummin( $limitalphanummin );
			$archive->setLimitalphanummax( $limitalphanummax );
			$archive->setLocalization( $localization );
			$archive->setLocalizationfree( $localizationfree );
			$archive->setPrecisionwho( $askwho );
			$archive->setPrecisiondate( $askwhen );
			$archive->setPrecisionwhere( $askwhere );

			// For Audit purpose
            $object_type =
                ( $archive->getContainernumber() != null && strlen( $archive->getContainernumber() ) > 0 ) ? IDPConstants::AUDIT_OBJECT_TYPE_CONTAINER :
                ( ( $archive->getBoxnumber() != null && strlen( $archive->getBoxnumber() ) > 0 ) ? IDPConstants::AUDIT_OBJECT_TYPE_BOX :
                ( ( $archive->getDocumentnumber() != null && strlen( $archive->getDocumentnumber() ) > 0 ) ? IDPConstants::AUDIT_OBJECT_TYPE_DOCUMENT :
                IDPConstants::AUDIT_OBJECT_TYPE_UNKNOWN ) );
            $archive->setObjecttype( $object_type );
            $archive->setFutureobjecttype( null );

            $em->persist( $archive );
            if( $flushToDB )
                $em->flush();

            // Add an archive import/creation to audit table
            $this->addImportCompleteAuditTrace( $em, $archive, $flushToDB );

            $this->addDBEntry( $em, $import, $percent, 1, 'Archive importée avec succès !', $line, null, true, $flushToDB );

		}

        $endTime = time();
        if( $output ){
            $secs = $endTime - $beginTime;
            $h = $secs / 3600 % 24;
            $m = $secs / 60 % 60;
            $s = $secs % 60;
            $output->writeln( "Import time = $h h: $m m: $s s" );
        }

        if( $debugMode && $verbose >= 1) $output->writeln( 'Treated lines <info>'.$lineNumber.'</info> / Error lines <error>'.$erreur.'</error>' );
		$this->addDBEntry( $em, $import, 100, 1, 'Nombre de lignes traitées: '.$lineNumber.' / Nombre de lignes avec erreur: '.$erreur, null, null, true, $flushToDB );
		$this->addDBEntry( $em, $import, 100, 1, 'Import terminé', null, null, true, $flushToDB );

        $this->updateImport( $em, $import,
            ($erreur>0?IDPImport::IDP_IMPORT_STATUS_ERROR:IDPImport::IDP_IMPORT_STATUS_END),
            100, null, $lineNumber, $lineNumber-$erreur, $erreur);
        $this->endImport( $em, $globalStatuses, $import );

        if( $debugMode && $verbose >= 1) $output->writeln( 'End of Importation' );

        if( $blockSize > 1 && $countUntilFlush != 0 )     // In case of blocks, force flush at the end for partial last block size
            $em->flush();
		return;

	}

	// Add traces to audit log; both CompleteUA and Audit
    private function addImportCompleteAuditTrace( $em, $archive, $flushToDB = true ){

        $audit_complete_ua = new IDPAuditCompleteUa();
        $audit_complete_ua->setServiceid( $archive->getService()===null?null:$archive->getService()->getId() );
        $audit_complete_ua->setLegalentityid( $archive->getLegalentity()===null?null:$archive->getLegalentity()->getId() );
        $audit_complete_ua->setDocumentnatureid( $archive->getDocumentnature()===null?null:$archive->getDocumentnature()->getId() );
        $audit_complete_ua->setDocumenttypeid( $archive->getDocumenttype()===null?null:$archive->getDocumenttype()->getId() );
        $audit_complete_ua->setBudgetcodeid( $archive->getBudgetcode()===null?null:$archive->getBudgetcode()->getId() );
        $audit_complete_ua->setProviderid( $archive->getProvider()===null?null:$archive->getProvider()->getId() );

        //'DTA', 'DISI', 'DISINT', 'DISP', 'CONI', 'CONINT', 'CONP'
        if((strcmp($archive->getStatus()->getShortname(), 'DISI')==0)||(strcmp($archive->getStatus()->getShortname(), 'CONI')==0))
            $audit_complete_ua->setLocalization( 1 );
        else if((strcmp($archive->getStatus()->getShortname(), 'DISINT')==0)||(strcmp($archive->getStatus()->getShortname(), 'CONINT')==0))
            $audit_complete_ua->setLocalization( 2 );
        else if((strcmp($archive->getStatus()->getShortname(), 'DISP')==0)||(strcmp($archive->getStatus()->getShortname(), 'CONP')==0))
            $audit_complete_ua->setLocalization( 3 );
        else
            $audit_complete_ua->setLocalization(0 );
        $em->persist( $audit_complete_ua );

        $this->getContainer()->get('doctrine')->getRepository('bsIDPArchiveBundle:IDPAudit')->addOneAuditEntry(
            IDPConstants::AUDIT_ACTION_IMPORT,      // action
            null,                                   // user_id
            IDPConstants::FIELD_NA,                 // field
            IDPConstants::ENTITY_ARCHIVE,           // entity
            $archive->getId(),                      // id of entity concerned
            null,                                   // new string value
            null,                                   // new int value
            null,                                   // old string value
            null,                                   // old int value
            $audit_complete_ua->getId(),            // link to complete UA values
            $archive->getObjecttype(),              // type of object during movement
            $flushToDB                              // Flush to DB on this trace
        );
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
}

?>