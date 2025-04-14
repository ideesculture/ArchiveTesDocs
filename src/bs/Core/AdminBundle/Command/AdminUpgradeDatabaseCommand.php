<?php

// src/bs/Core/AdminBundle/Command/AdminUpgradeDatabaseCommand.php
namespace bs\Core\AdminBundle\Command;

use bs\IDP\ArchiveBundle\ConstantDefinition\IDPConstants;
use bs\IDP\ArchiveBundle\Entity\IDPArchivesStatus;
use \DateTime;

use MongoDB\BSON\Timestamp;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use bs\Core\AdminBundle\Entity\bsAdminconfig;

use bs\IDP\BackofficeBundle\Entity\IDPGlobalStatuses;

use bs\Core\AdminBundle\Update\bsUpdateToCommonFunctions;
//use bs\Core\AdminBundle\Update\bsUpdateTo1106;

class AdminUpgradeDatabaseCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('admin:upgrade-database')
            ->setDescription('Upgrade database datas from a version to another.')

//            ->addArgument('filename', InputArgument::REQUIRED, 'name of file to import, must be in web/import/archimage dir')
//            ->addArgument('debug', InputArgument::OPTIONAL, 'debug mode activated')
        ;
    }
    private function outputHeader( OutputInterface $output ){
        $output->writeln( '--------------------------------------------------------' );
        $output->writeln( ' ARCHIMAGE upgrading procedure v0.9.5');
        $output->writeln( '--------------------------------------------------------' );
        $output->writeln( '' );
    }
    private function outputSoftwareError( OutputInterface $output ){
        $output->writeln( '<error>/!\ CRITICAL ERROR </error>' );
        $output->writeln( '<error> An error occured with upgrading software procedure </error>');
    }
    private function outputDatabaseError( OutputInterface $output ){
        $output->writeln( '<error>/!\ CRITICAL ERROR </error>' );
        $output->writeln( '<error> An error occured with upgrading database procedure </error>');
    }
    private function ouputFooter( OutputInterface $output ){
        $output->writeln( '' );
        $output->writeln( '--------------------------------------------------------' );
        $output->writeln( '<info> ARCHIMAGE upgrading procedure ended correctly </info>');
        $output->writeln( '<info> ARCHIMAGE is now ready to be used !</info>');
        $output->writeln( '--------------------------------------------------------' );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln( '<error>/!\ CRITICAL ERROR </error>' );
        $output->writeln( '<error>Only fresh new install version, cannot upgrade from previous</error>' );

/*        $globalStatuses = $this->getContainer()->get('doctrine')->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses')->findOneBy(array('id' => 1 ));
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 0 ) {
            $output->writeln( '<error>Reconciliation in progress (can it be ?), no upgrade can be performed !' );
            return;
        }

        $this->outputHeader( $output );

        $em = $this->getContainer()->get('doctrine')->getManager();
        $bsAdminconfig = $this->getContainer()->get('doctrine')
            ->getRepository('bsCoreAdminBundle:bsAdminconfig')
            ->findOneById( 1 );

        if( !$bsAdminconfig ){
            $output->writeln( '<error>/!\ CRITICAL ERROR </error>' );
            $output->writeln( '<error>No Admin Config found !</error>' );
            return;
        }

        $current_software_version = $bsAdminconfig->getSoftwareversion();
        $target_software_version = bsAdminconfig::CURRENT_SOFTWARE_VERSION;

        $current_database_version = $bsAdminconfig->getDatabaseversion();
        $target_database_version = bsAdminconfig::CURRENT_DATABASE_VERSION;

        $error = 0;

        $output->writeln('Software update from <info>'.$current_software_version.'</info> to <info>'.$target_software_version.'</info>');
        $error += $this->softwareUpdate( $output, $current_software_version, $target_software_version );
        if( $error < 0 ){
            $this->outputSoftwareError( $output );
            return;
        }
        $bsAdminconfig->setSoftwaremajorversion( $bsAdminconfig::CURRENT_SOFTWARE_MAJOR_VERSION );
        $bsAdminconfig->setSoftwareminorversion( $bsAdminconfig::CURRENT_SOFTWARE_MINOR_VERSION );
        $bsAdminconfig->setSoftwarereleaseversion( $bsAdminconfig::CURRENT_SOFTWARE_RELEASE_VERSION );
        $em->persist( $bsAdminconfig );
        $em->flush();

        $output->writeln('Database update from <info>'.$current_database_version.'</info> to <info>'.$target_database_version.'</info>');
        $error += $this->databaseUpdate( $output, $current_database_version, $target_database_version );
        if( $error < 0 ){
            $this->outputDatabaseError( $output );
            return;
        }
        $bsAdminconfig->setDatabasemajorversion( $bsAdminconfig::CURRENT_DATABASE_MAJOR_VERSION );
        $bsAdminconfig->setDatabaseminorversion( $bsAdminconfig::CURRENT_DATABASE_MINOR_VERSION );
        $bsAdminconfig->setDatabasereleaseversion( $bsAdminconfig::CURRENT_DATABASE_RELEASE_VERSION );
        $em->persist( $bsAdminconfig );
        $em->flush();

        $this->ouputFooter( $output );*/

        return;

    }

    private function softwareUpdate( $output, $current, $target ){
        if( $current > $target ) {
            $output->writeln('<error>Cannot retro-upgrade software !</error>');
            return 0;
        }
        if( $current == $target ) {
            $output->writeln('Software already up to date !');
            return 0;
        }

        if( $current < 100000 ) { // update only from 1+
            $output->writeln(' ERROR SW UPDATE : update only operationnal from 1.0.0 ');
            $output->writeln(' current version: '. $current );
            $output->writeln(' target version: '. $target );
            return -1;
        }

        // No software update

        return 0;
    }

    private function databaseUpdate( $output, $current, $target ){
        if( $current > $target ) {
            $output->writeln('<error>Cannot retro-upgrade database !</error>');
            return 0;
        }
        if( $current == $target ) {
            $output->writeln('Database already up to date !');
            return 0;
        }

        if( $current < 100000 ) { // update only from 1+
            $output->writeln(' ERROR BDD UPDATE : update only operationnal from 1.0.0 ');
            $output->writeln(' current version: '. $current );
            $output->writeln(' target version: '. $target );
            return -1;
        }

        $em = $this->getContainer()->get('doctrine')->getManager();
        $doctrine = $this->getContainer()->get('doctrine');

        if( $current == 100000 ) { // update from 1.0.0
            $output->writeln('Upgrading from <info>1.0.0</info>');

/*            $bsUpdateTo1106 = new bsUpdateTo1106();
            if( $bsUpdateTo1106->updateE307( $output, $em, $doctrine ) < 0 ) return -1;

            $current = 101006;*/

            return 0;
        }

        return -1;
    }

}

?>