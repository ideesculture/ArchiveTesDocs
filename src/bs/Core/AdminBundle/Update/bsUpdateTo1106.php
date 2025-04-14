<?php
namespace bs\Core\AdminBundle\Update;

use bs\Core\UsersBundle\Entity\bsRights;
use bs\IDP\BackofficeBundle\Entity\IDPGlobalSettings;
use \DateTime;
use MongoDB\BSON\Timestamp;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use bs\IDP\ArchiveBundle\ConstantDefinition\IDPConstants;
use bs\IDP\ArchiveBundle\Entity\IDPArchivesStatus;
use bs\Core\AdminBundle\Update\bsUpdateToCommonFunctions;

use bs\IDP\BackofficeBundle\Entity\IDPMainSettings;
use bs\Core\UsersBundle\Entity\IDPUserAutoSaveFields;

// Class example for further update command

class bsUpdateTo1106
{
    public function updateEXXX( $output, $em, $doctrine )
    {
        $output->writeln('<comment>- E#305 Configure All Services at Once</comment>');
/*
        $output->writeln( '<info>Add '.IDPMainSettings::ALL_SERVICES_CONFIGURED_AT_ONCE.' default value to database</info>' );
        $idpMaintSetting = new IDPMainSettings();
        $idpMaintSetting->setName( IDPMainSettings::ALL_SERVICES_CONFIGURED_AT_ONCE );
        $idpMaintSetting->setIntValue( IDPMainSettings::MAIN_SETTINGS_DEFAULT_VALUES[ IDPMainSettings::ALL_SERVICES_CONFIGURED_AT_ONCE ] );
        $em->persist( $idpMaintSetting );
        $em->flush();

        $output->writeln( '<error>----                                      I M P O R T A N T                                           ---</error>' );
        $output->writeln( '<error>You need to update manually IDPServiceSettings from IDPSettings table, and remove IDPSettings table after</error>' );
        $output->writeln( '<error>---------------------------------------------------------------------------------------------------------</error>' );

        $output->writeln( '<info>Update complete</info>' );*/
        return 0;
    }

}
