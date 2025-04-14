<?php

// src/bs/Core/AdminBundle/Command/AdminValidateInstallCommand.php
namespace bs\Core\AdminBundle\Command;

use bs\Core\UsersBundle\Entity\bsUsers;
use bs\Core\UsersBundle\Entity\IDPUserExtensions;
use bs\IDP\BackofficeBundle\Entity\IDPUserPagesSettings;
use \DateTime;
use MongoDB\BSON\Timestamp;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use bs\IDP\ArchiveBundle\ConstantDefinition\IDPConstants;

use bs\Core\UsersBundle\Entity\bsRights;
use bs\Core\UsersBundle\Entity\bsRoles;
use bs\IDP\BackofficeBundle\Entity\IDPUserColumnsSettings;

use bs\IDP\ArchiveBundle\Entity\IDPArchivesStatus;

use bs\IDP\BackofficeBundle\Entity\IDPGlobalSettings;
use bs\IDP\BackofficeBundle\Entity\IDPGlobalStatuses;
use bs\IDP\BackofficeBundle\Entity\IDPServiceSettings;
use bs\Core\AdminBundle\Entity\bsAdminconfig;
use bs\IDP\BackofficeBundle\Entity\IDPColumns;

use bs\Core\AdminBundle\Update\bsUpdateToCommonFunctions;

//// TO BE REVIEWED


class AdminValidateInstallCommand extends ContainerAwareCommand
{
    const tab = '                                                            ';

    const DB_to_TEST = [
        'bsCoreUsersBundle:bsRights' => 26,
        'bsCoreUsersBundle:bsRoles' => 6,
        'bsCoreUsersBundle:bsUsers' => 2,
        'bsIDPArchiveBundle:IDPArchivesStatus' => 104,
        'bsIDPBackofficeBundle:IDPColumns' => 44,
        'bsIDPBackofficeBundle:IDPMainSettings' => 1,
        'bsIDPBackofficeBundle:IDPGlobalSettings' => 3,
        'bsIDPBackofficeBundle:IDPGlobalStatuses' => 1,
        'bsIDPBackofficeBundle:IDPServiceSettings' => 1,
        'bsIDPBackofficeBundle:IDPUserColumnsSettings' => 4620,
        'bsCoreUsersBundle:IDPUserExtensions' => 2,
        'bsIDPBackofficeBundle:IDPUserPagesSettings' => 138,
        'bsCoreTranslationBundle:bsTranslation' => 2445
    ];

    //----------------------------------------------------------------------------------------------------------------
    // COMMAND DEFINITION
    //----------------------------------------------------------------------------------------------------------------
    protected function configure()
    {
        $this
            ->setName('admin:validate-install')
            ->setDescription('Validate database installation for version 1.0.0.')
        ;
    }

    private function outputHeader( OutputInterface $output ){
        $output->writeln( '-------------------------------------------------------' );
        $output->writeln( ' ARCHIMAGE DATABASE INSTALLATION VALIDATION  v1.0.0');
        $output->writeln( '-------------------------------------------------------' );
        $output->writeln( '' );
    }

    private function outputFooter( OutputInterface $output ){
        $output->writeln( '' );
        $output->writeln( '--------------------------------------------------------' );
        $output->writeln( '<info> ARCHIMAGE is now ready to be used </info>');
        $output->writeln( '--------------------------------------------------------' );
    }

    private function verifyEmptyDatabase( $output ){
        $doctrine = $this->getContainer()->get('doctrine');

        $output->writeln( '<info>ARCHIMAGE DATABASE VERIFICATION</info>');
        $globalVerification = true;

        foreach( self::DB_to_TEST as $db => $size ) {
            $localtab = substr( self::tab, 1,strlen(self::tab)-strlen($db) );
            $stroutput = $db . $localtab;

            $DBTest = $doctrine->getRepository( $db )->findAll();
            if(!$DBTest || sizeof($DBTest) != $size){
                $stroutput = '<error>'. $stroutput .': KO</error>';
                $globalVerification = false;
            } else
                $stroutput .= ': <info>OK</info>';
            $output->writeln( $stroutput );
        }
        if( !$globalVerification ) return false;

        return true;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $globalStatuses = $this->getContainer()->get('doctrine')->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses')->findOneBy(array('id' => 1 ));
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 0 ) {
            $output->writeln( '<error>Reconciliation in progress (can it be ?), no install validation can be performed !' );
            return;
        }

        $this->outputHeader( $output );

        $em = $this->getContainer()->get('doctrine')->getManager();

        if( !$this->verifyEmptyDatabase( $output ) ){
            $output->writeln( '-------------------------------------------------------' );
            $output->writeln( '<error>/!\ CRITICAL ERROR </error>' );
            $output->writeln( '<error>The DATABASE is not installed correctly !</error>\a' );
            $output->writeln( '-------------------------------------------------------' );
            return;
        }
        $output->writeln( '' );

        $bsAdminConfig = new bsAdminconfig();
        $bsAdminConfig->setDatabasemajorversion( bsAdminconfig::CURRENT_DATABASE_MAJOR_VERSION );
        $bsAdminConfig->setDatabaseminorversion( bsAdminconfig::CURRENT_DATABASE_MINOR_VERSION );
        $bsAdminConfig->setDatabasereleaseversion( bsAdminconfig::CURRENT_DATABASE_RELEASE_VERSION );
        $bsAdminConfig->setSoftwaremajorversion( bsAdminconfig::CURRENT_SOFTWARE_MAJOR_VERSION );
        $bsAdminConfig->setSoftwareminorversion( bsAdminconfig::CURRENT_SOFTWARE_MINOR_VERSION );
        $bsAdminConfig->setSoftwarereleaseversion( bsAdminconfig::CURRENT_SOFTWARE_RELEASE_VERSION );
        $em->persist( $bsAdminConfig );
        $em->flush();

        $output->writeln( '' );

        $this->outputFooter( $output );

    }

}

?>