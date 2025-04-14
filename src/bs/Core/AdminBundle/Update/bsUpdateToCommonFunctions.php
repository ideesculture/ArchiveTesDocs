<?php
namespace bs\Core\AdminBundle\Update;


use bs\IDP\ArchiveBundle\ConstantDefinition\IDPConstants;
use bs\IDP\ArchiveBundle\Entity\IDPArchivesStatus;
use \DateTime;

use MongoDB\BSON\Timestamp;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use bs\Core\TranslationBundle\Entity\bsTranslation;

use bs\Core\AdminBundle\Entity\bsAdminconfig;
use bs\IDP\BackofficeBundle\Entity\IDPColumns;
use bs\IDP\BackofficeBundle\Entity\IDPUserColumnsSettings;

class bsUpdateToCommonFunctions
{
    // Modification = array of [ pageNumber, sentenceNumber, textModified ]
    public function updateFrenchTextInDatabase( $modifications, $output, $em, $doctrine ){
        $i = 1;
        foreach( $modifications as $modif ) {
            $ps = $doctrine->getRepository('bsCoreTranslationBundle:bsTranslation')->findOneBy(array('page' => $modif[0], 'sentence' => $modif[1], 'language' => 0));
            if (!$ps) {
                $output->writeln('<error>/!\ Error page '.$modif[0].' sentence '.$modif[1].' not found !</error>');
                return -$i;
            }
            $output->writeln( ' Modify p:'.$modif[0].',s:'.$modif[1].',l:0 to : "<info>'.$modif[2].'</info>"' );
            $ps->setTranslation($modif[2]);
            $em->persist($ps);
            $em->flush();
            $i++;
        }
        return 0;
    }
    // creation = array of [ 'page'=>pageNumber, 'sentence'=>sentenceNumber, 'text'=>newText ]
    public function createTextInDatabase( $creations, $output, $em, $doctrine ){    // Should verify if (page,sentence) doesn't exist before adding it, if it exists, must update instead of add
        $i = 1;
        foreach( $creations as $create ){
            $ps = $doctrine->getRepository('bsCoreTranslationBundle:bsTranslation')->findOneBy(array('page' => $create['page'], 'sentence' => $create['sentence'], 'language' => 0));
            if( !$ps ) {
                $translationFR = new bsTranslation();
                $translationFR->setPage($create['page']);
                $translationFR->setSentence($create['sentence']);
                $translationFR->setLanguage(0);
                $translationFR->setTranslation($create['text']);
                $em->persist($translationFR);
                $translationUS = new bsTranslation();
                $translationUS->setPage($create['page']);
                $translationUS->setSentence($create['sentence']);
                $translationUS->setLanguage(1);
                $translationUS->setTranslation('US' . $create['text']);
                $em->persist($translationUS);
                $output->writeln(' Add new text in BDD [' . $create['page'] . ',' . $create['sentence'] . ']: "<info>' . $create['text'] . '</info>"');
            } else {
                $ps->setTranslation( $create['text'] );
                $em->persist( $ps );
                $output->writeln(' Text already exist, so modify it [' . $create['page'] . ',' . $create['sentence'] . ']: "<info>' . $create['text'] . '</info>"');
            }
        }
        $em->flush();
        return 0;
    }
    public function verifyAndEliminateDoubles( $listToVerify, $output, $em, $doctrine ){
        $idx = 1;
        foreach( $listToVerify as $verify ) {
            $translations = $doctrine->getRepository('bsCoreTranslationBundle:bsTranslation')->findBy(array('page' => $verify[0], 'sentence' => $verify[1], 'language' => $verify[2]));
            if (!$translations) {
                $output->writeln('<error>/!\ Error p:' . $verify[0] . ',s:' . $verify[1] . ',l:' . $verify[2] . ' not found !</error>');
                return -$idx;
            }
            if (count($translations) > 1) {
                $output->writeln('Found more than one for p:' . $verify[0] . ',s:' . $verify[1] . ',l:' . $verify[2] . ' = <info>' . count($translations) . '</info> ==> remove doubles');
                for ($i = 1; $i < count($translations); $i++)
                    $em->remove($translations[$i]);
            }
            $idx++;
        }
        $em->flush();
        return 0;
    }
}
