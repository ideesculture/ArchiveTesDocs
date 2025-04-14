<?php

namespace bs\Core\AdminBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use bs\IDP\ArchiveBundle\ConstantDefinition\IDPConstants;
use bs\IDP\BackofficeBundle\Entity\IDPGlobalStatuses;

use bs\Core\AdminBundle\Update\bsUpdateTo1106;

class AdminTestUpgradeCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('admin:verify-upgrade')
            ->setDescription('Test a specific upgrade command.')

            ->addArgument('testnumber', InputArgument::REQUIRED, 'number of the upgrade to verify')
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'force test of this upgrade')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $globalStatuses = $this->getContainer()->get('doctrine')->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses')->findOneBy(array('id' => 1 ));
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 0 ) {
            $output->writeln( '<error>Reconciliation in progress (can it be ?), no upgrade test can be performed !' );
            return;
        }

        $output->writeln( '--------------------------------------------------------' );
        $output->writeln( ' ARCHIMAGE 1+ verify upgrade');
        $output->writeln( '--------------------------------------------------------' );
        $output->writeln( '' );

        $testnumber = $input->getArgument('testnumber');
        $forceOpt = $input->getOption('force');
        $force = false;
        if( $forceOpt )
            $force = true;

        if( $force )
            $output->writeln( '<info>Force attribute set, no database version verification</info>' );

        $em = $this->getContainer()->get('doctrine')->getManager();
        $doctrine = $this->getContainer()->get('doctrine');
        $bsAdminconfig = $this->getContainer()->get('doctrine')
            ->getRepository('bsCoreAdminBundle:bsAdminconfig')
            ->findOneById( 1 );

        if( !$bsAdminconfig ){
            $output->writeln( '/!\ CRITICAL ERROR ' );
            $output->writeln( 'No Admin Config found !' );
            return;
        }

        $current_software_version = $bsAdminconfig->getSoftwareversion();
        $current_database_version = $bsAdminconfig->getDatabaseversion();
        $error_message = '<error>This upgrade number cannot be tested on this version :'.$current_database_version.', version 1+ needed!</error>';
        $bsUpdateTo1106 = new bsUpdateTo1106();
        $bsUpdateTo1200 = new bsUpdateTo1200();

        if( $current_database_version < 100000 ){
            $output->writeln( $error_message );
        } else {
            switch( $testnumber ) {
                // 1.0.0
                case 305:
                    if ($current_database_version == 100000 || $force)
                        $bsUpdateTo1106->updateE305($output, $em, $doctrine);
                    else
                        $output->writeln( $error_message );
                    break;

                default:
                    $output->writeln( '<error>This upgrade number doesn\'t exist :'.$testnumber.'</error>' );
                    break;
            }
        }

        $output->writeln( '' );
        $output->writeln( '--------------------------------------------------------' );
        $output->writeln( ' ARCHIMAGE endof verify upgrade');
        $output->writeln( '--------------------------------------------------------' );

        return;

    }

}

?>