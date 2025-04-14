<?php

namespace bs\IDP\ArchiveBundle\Command;

use bs\Core\UsersBundle\Controller\UserSpaceUserFileController;
use bs\IDP\ArchiveBundle\ConstantDefinition\IDPConstants;
use \DateTime;

use MongoDB\BSON\Timestamp;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use bs\IDP\ArchiveBundle\Common\IDPPrintTableCommon;

use bs\Core\UsersBundle\Entity\bsUsers;
use bs\Core\UsersBundle\Entity\bsUserServices;
use bs\Core\UsersBundle\Entity\IDPUserFiles;

class PrintOfflineCommand extends ContainerAwareCommand
{
    // For test purpose, command for all archives visible in Consulter
    // php bin/console app:print-table-offline 1 [] "[[name,Libellé];[ordernumber,N° d'ordre];[service,Service]]" 0 "[;;;;;;;;;;;;;1900,2199;1900,2199;;;;;2;;5;0;0;;;;;;;]" 2 0 debug


    // Index of xpsearch argument
    const IDX_SERVICE = 0;
    const IDX_LEGALENTITY = 1;
    const IDX_DESCRIPTION1 = 2;
    const IDX_DESCRIPTION2 = 3;
    const IDX_NAME = 4;
    const IDX_LIMITNUM = 5;
    const IDX_LIMITALPHANUM = 6;
    const IDX_LIMITALPHA = 7;
    const IDX_LIMITDATE = 8;
    const IDX_ORDERNUMBER = 9;
    const IDX_BUDGETCODE = 10;
    const IDX_DOCUMENTNATURE = 11;
    const IDX_DOCUMENTTYPE = 12;
    const IDX_CLOSUREYEAR = 13;
    const IDX_DESTRUCTIONYEAR = 14;
    const IDX_DOCUMENTNUMBER = 15;
    const IDX_BOXNUMBER = 16;
    const IDX_CONTAINERNUMBER = 17;
    const IDX_PROVIDER = 18;
    const IDX_UNLIMITED = 19;
    const IDX_SEARCHTEXT = 20;
    const IDX_FILTERSTATUS = 21;
    const IDX_FILTERWHERE = 22;
    const IDX_FILTERWITH = 23;
    const IDX_FILTERLOCALIZATION = 24;
    const IDX_XPSTATE = 25;
    const IDX_UAWHAT = 26;
    const IDX_UAWHERE = 27;
    const IDX_UAHOW = 28;
    const IDX_UAWITH = 29;
    const IDX_FPROV = 30;

    protected function configure()
    {
        $this
            ->setName('app:print-table-offline')
            ->setDescription('Print table offline, and save it in the user space')
            ->addArgument('userId', InputArgument::REQUIRED, 'user who ask for print')
            ->addArgument('listId', InputArgument::REQUIRED, 'list of id to print out, empty/null for all')
            ->addArgument('listColumn', InputArgument::REQUIRED, 'list of Column to print out')
            ->addArgument('format', InputArgument::REQUIRED, 'format')
            ->addArgument('xpsearch', InputArgument::REQUIRED, 'search specific term')
            ->addArgument('whereAmI', InputArgument::REQUIRED, 'where called from')
            ->addArgument('cardView', InputArgument::REQUIRED, 'card view mode')
            ->addArgument('debug', InputArgument::OPTIONAL, 'debug mode activated')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $userId = $input->getArgument('userId');
        $listId = $this->myParseToArray($input->getArgument('listId'));
        $listColumn = $this->myParseToArray($input->getArgument('listColumn'));
        $format = $input->getArgument('format');
        $xpsearch = $this->myParseToArray($input->getArgument('xpsearch'));
        $whereAmI = $input->getArgument('whereAmI');
        $cardView = $input->getArgument('cardView');
        $debugMode = ($input->getArgument('debug')?true:false);

        if( $debugMode ){
            $output->writeln( '<info>--------------------------------</info>' );
            $output->writeln( '<info>Archimage Print Offline Process</info>' );
            $output->writeln( '<info>--------------------------------</info>' );
            $output->writeln( '' );
        }

        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getManager();

        // Verify if user exists
        if( $debugMode )
            $output->writeln( 'Verify User existence ' );
        $user = $doctrine->getRepository('bsCoreUsersBundle:bsUsers')->findOneBy([ 'id' => $userId ]);
        if( !$user ){
            if( $debugMode )
                $output->writeln( '<error>KO !</error>' );
            return -1;
        }

        // TODO: Clearly need to harmonize between all sources to have the same process everywhere and not this shit
        if( $whereAmI >= 7 )
            $whereAmI = IDPPrintTableCommon::preciseWhereAmI( $whereAmI, $xpsearch );
        $fctCall = IDPPrintTableCommon::computeCallFromWhereAmI( $whereAmI );

        // Create UserFile in progress
        if( $debugMode )
            $output->writeln( 'Create UserFile in progress in DB' );
        $userFile = new IDPUserFiles();
        $userFile->setUserId( $userId );
        $userFile->setName( "Impression du tableau page " . IDPConstants::$PRINT_OFFLINE_READABLE_NAME[$whereAmI] );
        $userFile->setFilename( $userId. "_PrintTable_" . $whereAmI . '_' . time() . '.pdf' );
        $userFile->setFiletype( IDPUserFiles::FILETYPE_PDF );
        $userFile->setFiletime( time() );
        $userFile->setInProgress( true );
        $em->persist( $userFile );
        $em->flush();


        if( $debugMode )
            $output->writeln( 'Load services allowed for this user' );
        // Get services allowed for this user
        $userServices = $doctrine->getRepository('bsCoreUsersBundle:IDPUserServices')->findBy(
            array('user' => $userId ), null );


        $printTableCommon = new IDPPrintTableCommon();

        $colvis = $printTableCommon->makeTitleColumn( $listColumn, $cardView, null );
        $titleColumn = $colvis['title'];
        $columnVisible = $colvis['visible'];

        $servicesAllowed = $printTableCommon->makeServiceAllowed( $fctCall, $userServices );

        $sarch = $this->translateXPSearch( $xpsearch );
        $listUAS = $printTableCommon->getListUA( $doctrine, $userServices, $listId, $sarch, $servicesAllowed, $fctCall, $columnVisible, null );

        $fileSize = $printTableCommon->makePDFFile( $cardView, $listUAS, $titleColumn, $userFile );

        // End of UserFile, set file in right directory
        if( $debugMode )
            $output->writeln( 'End of generation' );

        $userFile->setInProgress( false );
        $userFile->setFilesize( $fileSize );
        $em->persist( $userFile );
        $em->flush();

        return 0;
    }

    protected function translateXPSearch( $xpsearch ){

        $retSearch = [];

        $retSearch['service'] = $xpsearch[0];
        $retSearch['legalentity'] = $xpsearch[1];
        $retSearch['description1'] = $xpsearch[2];
        $retSearch['description2'] = $xpsearch[3];
        $retSearch['name'] = $xpsearch[4];
        $retSearch['limitnum'] = $xpsearch[5];
        $retSearch['limitalpha'] = $xpsearch[6];
        $retSearch['limitalphanum'] = $xpsearch[7];
        $retSearch['limitdate'] = $xpsearch[8];
        $retSearch['ordernumber'] = $xpsearch[9];
        $retSearch['budgetcode'] = $xpsearch[10];
        $retSearch['documentnature'] = $xpsearch[11];
        $retSearch['documenttype'] = $xpsearch[12];
        $retSearch['closureyear'] = $xpsearch[13];
        $retSearch['destructionyear'] = $xpsearch[14];
        $retSearch['documentnumber'] = $xpsearch[15];
        $retSearch['boxnumber'] = $xpsearch[16];
        $retSearch['containernumber'] = $xpsearch[17];
        $retSearch['provider'] = $xpsearch[18];
        $retSearch['unlimited'] = $xpsearch[19];
        $retSearch['special'] = $xpsearch[20];
        $retSearch['filterstatus'] = $xpsearch[21];
        $retSearch['filterwhere'] = $xpsearch[22];
        $retSearch['filterwith'] = $xpsearch[23];
        $retSearch['filterlocalization'] = $xpsearch[24];
        $retSearch['xpstate'] = $xpsearch[25];
        $retSearch['xpwhat'] = $xpsearch[26];
        $retSearch['xpwhere'] = $xpsearch[27];
        $retSearch['xphow'] = $xpsearch[28];
        $retSearch['xpwith'] = $xpsearch[29];
        $retSearch['filterprovider'] = $xpsearch[30];

        return $retSearch;
    }

    protected function preciseWhereAmI( $whereAmI, $XPSearch ){
        $retour = -1;
        switch( $whereAmI ){
            case 7: // Valider les demandes utilisateurs
                switch( $XPSearch[self::IDX_UAWHAT] ){
                    case IDPConstants::UAWHAT_TRANSFER:
                        switch( $XPSearch[self::IDX_UAWHERE] ){
                            case IDPConstants::UAWHERE_PROVIDER: $retour = 7; break;
                            case IDPConstants::UAWHERE_INTERMEDIATE: $retour = 8; break;
                            case IDPConstants::UAWHERE_INTERNAL: $retour = 9; break;
                        }
                        break;
                    case IDPConstants::UAWHAT_CONSULT:
                        switch( $XPSearch[self::IDX_UAWHERE] ){
                            case IDPConstants::UAWHERE_WITHOUTPREPARATION: $retour = 10; break;
                            case IDPConstants::UAWHERE_WITHPREPARATION: $retour = 11; break;
                        }
                    case IDPConstants::UAWHAT_RETURN: $retour = 12; break;
                    case IDPConstants::UAWHAT_EXIT: $retour = 13; break;
                    case IDPConstants::UAWHAT_DESTROY: $retour = 14; break;
                    case IDPConstants::UAWHAT_RELOC:
                        switch( $XPSearch[self::IDX_UAWHERE] ){
                            case IDPConstants::UAWHERE_PROVIDER: $retour = 15; break;
                            case IDPConstants::UAWHERE_INTERMEDIATE: $retour = 16; break;
                            case IDPConstants::UAWHERE_INTERNAL: $retour = 17; break;
                        }
                }
                break;
            case 8: // Gérer les demandes prestataire
                switch( $XPSearch[self::IDX_UAWHAT] ){
                    case IDPConstants::UAWHAT_TRANSFER: $retour = 18; break;
                    case IDPConstants::UAWHAT_CONSULT: $retour = 19; break;
                    case IDPConstants::UAWHAT_RETURN: $retour = 20; break;
                    case IDPConstants::UAWHAT_EXIT: $retour = 21; break;
                    case IDPConstants::UAWHAT_DESTROY: $retour = 22; break;
                    case IDPConstants::UAWHAT_RELOC: $retour = 23; break;
                }
                break;
            case 7: // Clôturer les demandes des utilisateurs
                switch( $XPSearch[self::IDX_UAWHAT] ){
                    case IDPConstants::UAWHAT_TRANSFER:
                        switch( $XPSearch[self::IDX_UAWHERE] ){
                            case IDPConstants::UAWHERE_PROVIDER: $retour = 24; break;
                            case IDPConstants::UAWHERE_INTERMEDIATE: $retour = 25; break;
                            case IDPConstants::UAWHERE_INTERNAL: $retour = 26; break;
                        }
                        break;
                    case IDPConstants::UAWHAT_CONSULT:
                        switch( $XPSearch[self::IDX_UAWHERE] ){
                            case IDPConstants::UAWHERE_WITHOUTPREPARATION: $retour = 27; break;
                            case IDPConstants::UAWHERE_WITHPREPARATION: $retour = 28; break;
                        }
                    case IDPConstants::UAWHAT_RETURN: $retour = 29; break;
                    case IDPConstants::UAWHAT_EXIT: $retour = 30; break;
                    case IDPConstants::UAWHAT_DESTROY: $retour = 31; break;
                    case IDPConstants::UAWHAT_RELOC:
                        switch( $XPSearch[self::IDX_UAWHERE] ){
                            case IDPConstants::UAWHERE_PROVIDER: $retour = 32; break;
                            case IDPConstants::UAWHERE_INTERMEDIATE: $retour = 33; break;
                            case IDPConstants::UAWHERE_INTERNAL: $retour = 34; break;
                        }
                }
                break;
        }
        return $retour;
    }
    protected function computeCallFromWhereAmI( $whereAmI ){
        if( $whereAmI ==  1 ) return 1;
        elseif( $whereAmI < 7 ) return 2;
        else return 3;
    }

    // ================================================================================================================
    // Strange but json_encode in javascript plus json_decode in php after a getArgument doesn't work, so I made my own decode function
    // [[ , ];[ , ]] ou [ ; ; ; ]
    protected function myParseToArray( $string ){
        if( empty($string) || (strlen($string)<=2) )
            return null;
        if((strlen($string)>2) && ( $string[0] === '[' )&&( $string[strlen($string)-1] === ']' )){
            $arr = explode( ";", substr( $string, 1, strlen($string)-2 ) );
            foreach( $arr as $key=>$item ){
                // There is a subarray
                if((strlen($item)>=2) && ( $item[0] === '[' )&&( $item[strlen($item)-1] === ']' ))
                    if( strlen($item) == 2 )
                        $arr[$key] = null;
                    else
                        $arr[$key] = explode( ",", substr( $item, 1, strlen($item)-2 ) );
            }
            return $arr;
        } else
            return null;
    }

}

?>