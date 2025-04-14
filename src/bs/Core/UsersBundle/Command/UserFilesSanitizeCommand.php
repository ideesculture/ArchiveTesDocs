<?php

namespace bs\Core\UsersBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UserFilesSanitizeCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('user-files:sanitize')
            ->setDescription('Remove user-files older than 7 days.')

            ->addOption('test', 't', InputOption::VALUE_NONE, 'does not really remove files, only show then in console.')
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
        $output->writeln( ' ARCHIMAGE User-Files 7 days deletion program');
        $output->writeln( '--------------------------------------------------------' );
        $output->writeln( '' );

        $testOpt = $input->getOption('test');
        $test = false;
        if( $testOpt )
            $test = true;

        if( $test )
            $output->writeln( '<info>Test attribute set, no files will be really deleted</info>' );

        $em = $this->getContainer()->get('doctrine')->getManager();
        $doctrine = $this->getContainer()->get('doctrine');

        $userFilesList = $doctrine->getRepository('bsCoreUsersBundle:IDPUserFiles')
            ->getAllUserFiles( -1 );

        if( !$userFilesList ){
            $output->writeln( 'No user-files at all' );
            return 0;
        }

        // Get current timestamp
        $now = time();
        $sevenDays = 7 * 24*60*60;

        $count = 0;
        $removed = 0;

        foreach ( $userFilesList as $userFile ){
            $count++;
            if( !$userFile->getInprogress() ) {
                if ($now - $userFile->getFiletime() >= $sevenDays) {
                    $removed++;
                    $output->write('> ' . $userFile->getFilename());
                    if (!$test) {
                        $fullname = __DIR__ . '/../../../../../var/tmp/IDPUserFiles/' . $userFile->getFilename();
                        unlink($fullname);
                        $em->remove($userFile);
                        $output->writeln(' <error>REMOVED</error>');
                    } else
                        $output->writeln(' <info>SHOULD BE REMOVED</info>');
                }
            }
        }
        if( !$test )
            $em->flush();

        $output->writeln( ''.$removed.'/'.$count.' files removed' );
        $output->writeln( '--------------------------------------------------------' );

        return 0;

    }

}

?>