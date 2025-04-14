<?php

namespace bs\IDP\ArchiveBundle\Controller;

use bs\IDP\ArchiveBundle\Common\IDPManageContainerBox;
use bs\IDP\ArchiveBundle\ConstantDefinition\IDPConstants;
use \DateTime;
use Symfony\Component\Process\Process;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\HttpFoundation\ResponseHeaderBag;

use bs\Core\UsersBundle\SessionMng\bsCoreUserSession;
use bs\Core\AdminBundle\Security\IDPArchimageRights;

use bs\IDP\ArchiveBundle\Entity\IDPArchive;
use bs\IDP\ArchiveBundle\Entity\IDPArchivesStatus;

use bs\IDP\ArchiveBundle\Translation\bsTranslationIDsArchive;
use bs\Core\TranslationBundle\Translation\bsTranslationIDsCommon;

use Symfony\Component\Filesystem\Filesystem;

use bs\IDP\BackofficeBundle\Entity\IDPGlobalStatuses;
use bs\IDP\ArchiveBundle\Common\IDPPrintTableCommon;

use \fpdf\FPDF;

class PrintController extends Controller
{
	const SEPARATOR = ' - ';
	const LIMITMINMAX = ' - ';
	const LIMITDATEMINMAX = '-';
	const CUTTYPE_FILL = 0;
	const CUTTYPE_SPACE = 1;

	public function printTagsAction( Request $request ){
		$bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
		if( !$bsUserSession->isUserLogged( $request->cookies->get('PHPSESSID') ) )
			return $this->redirect( $this->generateUrl('bs_core_user_login') );
        if( $bsUserSession->getUser()->getChangepass() )
            return $this->redirect( $this->generateUrl('bs_core_user_change_mdp_screen'));
		$language = $bsUserSession->getUserExtension()->getLanguage();
		$systemTranslations = $this->getDoctrine()
			->getRepository('bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsCommon::T_SYSTEM, $language );

		if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_ARCHIVE_NEW][IDPArchimageRights::RIGHT_ID] ) ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig' , array(
                'error_redirect' => 'bs_idp_dashboard_homepage',
                'error_wait_time' => IDPConstants::ERROR_WAIT_TIME,
                'error_title' => null,
                'error_message' => null,
                'error_type' => 1 ));
		}

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig', array(
                'error_redirect' => 'bs_idp_dashboard_homepage',
                'error_wait_time' => IDPConstants::ERROR_WAIT_TIME,
                'error_title' => null,
                'error_message' => null,
                'error_type' => 2 )); }

        $logger = $this->container->getParameter('kernel.environment') == 'dev' ?$this->container->get('logger'):null;

		/*
		   $translations = $this->getDoctrine()
		   ->getRepository('bsCoreTranslationBundle:bsTranslation')
		   ->getArrayTranslation( bsTranslationIDsArchive::T_PAGE_ADDUASCREEN, $language );
		*/
		$headTranslations = $this->getDoctrine()
			->getRepository('bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsCommon::T_HEAD_BASE, $language );

		// $_GET parameters
		//$parameters = $request->query;
		// $_POST parameters
		$parameters = $request->request;

		$archives_id = $parameters->get('ids');
		$archives_id = (strlen($archives_id)>0)?explode('|',$archives_id):null;

        if( $logger ) $logger->info( ' - archives ( '. json_encode($archives_id) . ')' );

        $position = $parameters->get('position');
		if (strlen($position)<=0) $position = 1; else $position = intval($position);

        if( $logger ) $logger->info( ' - position ( '. json_encode($position) . ')' );

		if( $archives_id == null )
			return -1;

		$pdf = new \FPDF( 'L', 'mm', array( 210, 297 ) );
		$pdf->SetMargins( 0, 0, 0 );
		$pdf->SetAutoPageBreak( true, 0 );
		$pdf->AddPage( 'L', array( 210, 297 ) );
        $pdf->AddFont('Roboto-Regular', '', 'Roboto-Regular.php');

		foreach( $archives_id as $archive_id )
		{
            if( $logger ) $logger->info( ' - Treat new archive ( '. json_encode($archive_id) . ')' );

			if( $position > 4 ){
				$position = 1;
				$pdf->AddPage( 'L', array( 210, 297) );
			}

			$uaarray = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPArchive')
				->getArray( $archive_id );
			if( !$uaarray )
				return -2;
			$uaarray = $uaarray[0];
            if( $logger ) $logger->info( ' - datas ( '. json_encode($uaarray) . ')' );

            // Get settings for the service of this archive
            $settings = $this->getDoctrine()
                ->getRepository('bsIDPBackofficeBundle:IDPServiceSettings')
                ->arrayFindOneByService( $uaarray['service_id'] );
            $setting = $settings[0];
            if( $logger ) $logger->info( ' - service settings ( '. json_encode($setting) . ')' );

            $uaToPrint = $this->extractDatasToPrint( $uaarray, $setting );
			if( $uaToPrint == null )
				return -3;

			$pdf = $this->makeTemplate( $pdf, $position, ($uaToPrint['provider']!=null) );

			$pdf = $this->fillTemplate( $pdf, $position, $uaToPrint );

			$position++;
		}

        $pdfOutput = $pdf->Output('S' );

        $response = new Response( $pdfOutput );
        $response->headers->set('Content-Type', 'application/pdf');
        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            'tag.pdf'
        );
        $response->headers->set('Content-Disposition', $disposition);
        $response->headers->set('Cache-Control', 'private, max-age=0, must-revalidate');
        $response->headers->set('Pragma', 'public');

        return $response;
    }
	public function printTagAction( Request $request ){
        $logger = ($this->container->getParameter('kernel.environment') == 'dev')?$this->container->get('logger'):null;
        if( $logger ) $logger->info( '-> Enter printTagAction');

		$bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
		if( !$bsUserSession->isUserLogged( $request->cookies->get('PHPSESSID') ) )
			return $this->redirect( $this->generateUrl('bs_core_user_login') );
        if( $bsUserSession->getUser()->getChangepass() )
            return $this->redirect( $this->generateUrl('bs_core_user_change_mdp_screen'));
		$language = $bsUserSession->getUserExtension()->getLanguage();
		$systemTranslations = $this->getDoctrine()
			->getRepository('bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsCommon::T_SYSTEM, $language );

		if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_ARCHIVE_NEW][IDPArchimageRights::RIGHT_ID] ) ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig' , array(
                'error_redirect' => 'bs_idp_dashboard_homepage',
                'error_wait_time' => IDPConstants::ERROR_WAIT_TIME,
                'error_title' => null,
                'error_message' => null,
                'error_type' => 1 ));
		}

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig', array(
                'error_redirect' => 'bs_idp_dashboard_homepage',
                'error_wait_time' => IDPConstants::ERROR_WAIT_TIME,
                'error_title' => null,
                'error_message' => null,
                'error_type' => 2 )); }

		/*
		$translations = $this->getDoctrine()
			->getRepository('bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsArchive::T_PAGE_ADDUASCREEN, $language );
		*/
		$headTranslations = $this->getDoctrine()
			->getRepository('bsCoreTranslationBundle:bsTranslation')
			->getArrayTranslation( bsTranslationIDsCommon::T_HEAD_BASE, $language );

		// $_GET parameters
		//$parameters = $request->query;
		// $_POST parameters
		$parameters = $request->request;

		$archive_id = $parameters->get('id');
		$archive_id = (strlen($archive_id)>0)?intval($archive_id):null;
		$position = $parameters->get('position');
		if (strlen($position)<=0) $position = 1; else $position = intval($position);

		if( $archive_id ){
			$uaarray = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPArchive')
				->getArray( $archive_id );
		} else {
			// ERROR
			return -1;
		}
        if( $logger ){
            $logger->info( ' POST parameters ' );
            $logger->info( ' $archive_id = ' . $archive_id );
            $logger->info( ' $position = ' . $position );
        }

		if( !$uaarray )
			return -2;
		$uaarray = $uaarray[0];

		// Get settings for the service of this archive
        $settings = $this->getDoctrine()
            ->getRepository('bsIDPBackofficeBundle:IDPServiceSettings')
            ->arrayFindOneByService( $uaarray['service_id'] );
        $setting = $settings[0];

        // Get back all texts to print this archive
		$uaToPrint = $this->extractDatasToPrint( $uaarray, $setting );
		if( $uaToPrint == null )
			return -3;

        if( $logger ){
            $logger->info( ' $uaToPrint ' );
            foreach( $uaToPrint as $key => $value )
                $logger->info( '['. $key .']= ' .$value );
        }

		$pdf = new \FPDF( 'L', 'mm', array( 210, 297 ) );
		$pdf->SetMargins( 0, 0, 0 );
		$pdf->SetAutoPageBreak( true, 0 );
		$pdf->AddPage( 'L', array( 210, 297 ) );
		$pdf->AddFont('Roboto-Regular', '', 'Roboto-Regular.php');

		$pdf = $this->makeTemplate( $pdf, $position, ($uaToPrint['provider']!=null) );

		$pdf = $this->fillTemplate( $pdf, $position, $uaToPrint );

		//return null; // Debug Only

        $pdfOutput = $pdf->Output('S' );

        $response = new Response( $pdfOutput );
        $response->headers->set('Content-Type', 'application/pdf');
        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            'tag.pdf'
        );
        $response->headers->set('Content-Disposition', $disposition);
        $response->headers->set('Cache-Control', 'private, max-age=0, must-revalidate');
        $response->headers->set('Pragma', 'public');

        return $response;
	}

	private function makeTemplate( $pdf, $position, $provider ){
		if( $position < 1 ) $position = 1;
		if( $position > 4 ) $position = 4;

		$delta = ( $position - 1 ) * 62;

		$pdf->SetFont( 'Roboto-Regular', '', 11 );
		$pdf->SetLineWidth( 0,2 );
		$pdf->SetDrawColor( 0, 0, 0 );
		$pdf->setTextColor( 0, 0, 0 );

		// Service / legal entity
		$pdf->SetXY( 34 + $delta , 6 );
        $pdf->setTextColor( 50, 130, 190 );
        $pdf->Cell( 17, 8, utf8_decode('Service'), 0, 0, 'L', false );
        $pdf->setTextColor( 90, 90, 90 );
        $pdf->SetXY( 49 + $delta , 6 );
        $pdf->Cell( 12, 8, utf8_decode('/ Entité légale'), 0, 0, 'L', false );
        $pdf->Rect( 28 + $delta, 14, 54, 10, 'D' );

		// Order number
		$pdf->SetXY( 28 + $delta, 23 );
		$pdf->Cell( 54, 8, utf8_decode( 'Numéro d\'ordre' ), 0, 0, 'C', false );
		$pdf->Rect( 28 + $delta, 29, 54, 5, 'D' );

		// Provider
		$pdf->SetXY( 28 + $delta, 33 );
		$pdf->Cell( 54, 8, utf8_decode( "Compte prestataire" ), 0, 0, 'C', false );
		$pdf->Rect( 28 + $delta, 39, 54, 5, 'D' );

        // Identifiants
        $pdf->setTextColor( 50, 130, 190 );
        $pdf->SetXY( 28 + $delta, 42 );
        $pdf->Cell( 54, 9, "Identifiants", 0, 0, 'C', false );
        $pdf->Rect( 28 + $delta, 49, 54, 16, 'D' );
        $pdf->SetXY( 28 + $delta, 48 );
        $pdf->Cell( 18, 8, "Conteneur", 0, 0, 'L', false );
        $pdf->SetXY( 28 + $delta, 53 );
        $pdf->Cell( 18, 8, utf8_decode("Boîte"), 0, 0, 'L', false );
        $pdf->SetXY( 28 + $delta, 58 );
        $pdf->Cell( 18, 8, "Dossier", 0, 0, 'L', false );

		// Libellé
        $pdf->setTextColor( 90, 90, 90 );
		$pdf->SetXY( 28 + $delta, 64 );
		$pdf->Cell( 54, 8, utf8_decode( 'Descriptif' ), 0, 0, 'C', false );
		$pdf->Rect( 28 + $delta, 70, 54, 115, 'D' );

		// Année de clôture / Destruction
		$pdf->SetXY( 27 + $delta, 184 );
		$pdf->Cell( 27, 8, utf8_decode( 'Année de clôture /' ), 0, 0, 'L', false );
        $pdf->setTextColor( 50, 130, 190 );
        $pdf->SetXY( 60 + $delta, 184 );
        $pdf->Cell( 17, 8, utf8_decode( 'Destruction' ), 0, 0, 'L', false );
		$pdf->Rect( 28 + $delta, 191, 54, 6, 'D' );
        $pdf->SetXY( 29 + $delta, 190 );
        $pdf->setTextColor( 0, 0, 0 );
        $pdf->Cell( 54, 8, '/', 0, 0, 'C', false );

		return $pdf;
	}

	private function fillTemplate( $pdf, $position, $ua ){
		if( $position < 1 ) $position = 1;
		if( $position > 4 ) $position = 4;

		$delta = ( $position - 1 ) * 62;

		$pdf->SetFont( 'Roboto-Regular', '', 11 );
		$pdf->SetLineWidth( 0,2 );
		$pdf->SetDrawColor( 0, 0, 0 );

        // Service
        $pdf->setTextColor( 50, 130, 190 );
        $pdf->SetFont( 'Roboto-Regular', '', 11 );
        $pdf->SetXY( 29 + $delta , 14 );
        $this->printMonoLine( $pdf, utf8_decode($this->convertText($ua['service'])), 21, 52 );

        // Entité légale
        $pdf->SetFont( 'Roboto-Regular', '', 11 );
        $pdf->setTextColor( 90, 90, 90 );
        $pdf->SetXY( 29 + $delta, 18 );
		$this->printMonoLine( $pdf, utf8_decode($this->convertText($ua['legalentity'])), 21, 52 );

        // Numéro d'ordre
        $pdf->setTextColor( 90, 90, 90 );
        $pdf->SetXY( 29 + $delta, 29 );
        $this->printMonoLine( $pdf, utf8_decode($this->convertText($ua['ordernumber'])), 21, 52 );

        // Compte prestataire
        $pdf->setTextColor( 90, 90, 90 );
        if( $ua['provider'] != null ){
			$pdf->SetXY( 29 + $delta, 39 );
			$this->printMonoLine( $pdf, utf8_decode($this->convertText($ua['provider'])), 21, 52 );
		}

        // $text = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc euismod tellus fringilla, sodales diam vel, vulputate velit. Pellentesque ornare, elit at suscipit aliquam, arcu ligula faucibus dui, in eleifend sapien lacus a urna. Donec magna dui, accumsan ut nibh vel, dictum elementum tellus. Integer commodo tincidunt enim viverra luctus. Proin ornare, erat quis blandit laoreet, sem massa consequat magna, sed vehicula ex lectus fringilla orci. Fusce congue iaculis tellus, nec tempor diam suscipit ut. Sed mattis, mauris eget pretium tincidunt, urna arcu hendrerit sapien, vitae dictum quam leo non erat.';

        // Identifiants
        $pdf->SetFont( 'Roboto-Regular', '', 11 );
        $pdf->setTextColor( 50, 130, 190 );
        $pdf->SetXY( 52 + $delta, 49 );
        $this->printMonoLine( $pdf, utf8_decode($this->convertText($ua['containernumber'])), 12, 29 );
        $pdf->SetXY( 52 + $delta, 54 );
        $this->printMonoLine( $pdf, utf8_decode($this->convertText($ua['boxnumber'])), 12, 29 );
        $pdf->SetXY( 52 + $delta, 59 );
        $this->printMonoLine( $pdf, utf8_decode($this->convertText($ua['documentnumber'])), 12, 29 );

        $line = 0;
        // Activité
        if( $ua['documentnature'] != null ) {
            $pdf->SetFont('Courier', '', 11);
            $pdf->setTextColor(50, 130, 190);
            $pdf->setXY( 29+$delta, 70+$line*5 );
            $pdf->Cell( 52, 7, utf8_decode( 'Activité' ), 0, 0, 'C', false );
            $line++;
            $pdf->SetFont('Courier', '', 11);
            $pdf->setTextColor(90, 90, 90);
            $pdf->setXY( 29+$delta, 70+$line*5 );
            $this->printMonoLine( $pdf, utf8_decode($this->convertText($ua['documentnature'])), 21, 52 );
            $line++;
        }

        // Type de document
        if( $ua['documenttype'] != null ) {
            $pdf->SetFont('Courier', '', 11);
            $pdf->setTextColor(50, 130, 190);
            $pdf->setXY( 29+$delta, 70+$line*5 );
            $pdf->Cell( 52, 7, utf8_decode( 'Type de document' ), 0, 0, 'C', false );
            $line++;
            $pdf->SetFont('Courier', '', 11);
            $pdf->setTextColor(90, 90, 90);
            $pdf->setXY( 29+$delta, 70+$line*5 );
            $this->printMonoLine( $pdf, utf8_decode($this->convertText($ua['documenttype'])), 21, 52 );
            $line++;
        }

        // Description1
        if( $ua['description1'] != null ) {
            $pdf->SetFont('Courier', '', 11);
            $pdf->setTextColor(50, 130, 190);
            $pdf->setXY( 29+$delta, 70+$line*5 );
            $pdf->Cell( 52, 7, utf8_decode( $this->convertText($ua['desc1_title'] )), 0, 0, 'C', false );
            $line++;
            $pdf->SetFont('Courier', '', 11);
            $pdf->setTextColor(90, 90, 90);
            $pdf->setXY( 29+$delta, 70+$line*5 );
            $this->printMonoLine( $pdf, utf8_decode($this->convertText($ua['description1'])), 21, 52 );
            $line++;
        }
        // Description2
        if( $ua['description2'] != null ) {
            $pdf->SetFont('Courier', '', 11);
            $pdf->setTextColor(50, 130, 190);
            $pdf->setXY( 29+$delta, 70+$line*5 );
            $pdf->Cell( 52, 7, utf8_decode( $this->convertText($ua['desc2_title'] )), 0, 0, 'C', false );
            $line++;
            $pdf->SetFont('Courier', '', 11);
            $pdf->setTextColor(90, 90, 90);
            $pdf->setXY( 29+$delta, 70+$line*5 );
            $this->printMonoLine( $pdf, utf8_decode($this->convertText($ua['description2'])), 21, 52 );
            $line++;
        }

        // Bornes
        if( $ua['limitsnum'] != null || $ua['limitsalpha'] != null || $ua['limitsalphanum'] != null || $ua['limitsdate'] != null ) {
            $pdf->SetFont('Courier', '', 11);
            $pdf->setTextColor(50, 130, 190);
            $pdf->setXY( 29+$delta, 70+$line*5 );
            $pdf->Cell( 52, 7, utf8_decode( 'Bornes' ), 0, 0, 'C', false );
            $line++;
            if( $ua['limitsdate'] != null ){
                $pdf->SetFont('Courier', '', 11);
                $pdf->setTextColor(90, 90, 90);
                $pdf->setXY( 29+$delta, 70+$line*5 );
                $this->printMonoLine( $pdf, utf8_decode($this->convertText($ua['limitsdate'])), 21, 52 );
                $line++;
            }
            if( $ua['limitsnum'] != null ){
                $pdf->SetFont('Courier', '', 11);
                $pdf->setTextColor(90, 90, 90);
                $pdf->setXY( 29+$delta, 70+$line*5 );
                $this->printMonoLine( $pdf, utf8_decode($this->convertText($ua['limitsnum'])), 21, 52 );
                $line++;
            }
            if( $ua['limitsalpha'] != null ){
                $pdf->SetFont('Courier', '', 11);
                $pdf->setTextColor(90, 90, 90);
                $pdf->setXY( 29+$delta, 70+$line*5 );
                $this->printMonoLine( $pdf, utf8_decode($this->convertText($ua['limitsalpha'])), 21, 52 );
                $line++;
            }
            if( $ua['limitsalphanum'] != null ){
                $pdf->SetFont('Courier', '', 11);
                $pdf->setTextColor(90, 90, 90);
                $pdf->setXY( 29+$delta, 70+$line*5 );
                $this->printMonoLine( $pdf, utf8_decode($this->convertText($ua['limitsalphanum'])), 21, 52 );
                $line++;
            }
        }

        // Budget code
        if( $ua['budgetcode'] != null ) {
            $pdf->SetFont('Courier', '', 11);
            $pdf->setTextColor(50, 130, 190);
            $pdf->setXY( 29+$delta, 70+$line*5 );
            $pdf->Cell( 52, 7, utf8_decode( 'Code budgétaire' ), 0, 0, 'C', false );
            $line++;
            $pdf->SetFont('Courier', '', 11);
            $pdf->setTextColor(90, 90, 90);
            $pdf->setXY( 29+$delta, 70+$line*5 );
            $this->printMonoLine( $pdf, utf8_decode($this->convertText($ua['budgetcode'])), 21, 52 );
            $line++;
        }

        if( $ua['label'] != null ){
            $pdf->SetFont('Courier', '', 11);
            $pdf->setTextColor(50, 130, 190);
            $pdf->setXY( 29+$delta, 70+$line*5 );
            $pdf->Cell( 52, 7, utf8_decode( 'Libellé' ), 0, 0, 'C', false );
            $line++;

            $pdf->SetFont('Courier', '', 11);
            $pdf->setTextColor(90, 90, 90);
            //$this->printMultiLine( $pdf, $text, 21, 23-$line, 28+$delta, 70+$line*5, 5, self::CUTTYPE_FILL, false, false);
            $this->printMultiLine( $pdf, utf8_decode($this->convertText($ua['label'])), 21, 23-$line, 28+$delta, 70+$line*5, 5, self::CUTTYPE_FILL, false, true);
        }



		// Année de clôture
        $pdf->SetFont('Roboto-Regular', '', 11);
        $pdf->setTextColor( 90, 90, 90 );
		$pdf->SetXY( 29 + $delta, 191 );
		$this->printMonoLine( $pdf, $ua['closureyear'], 18, 24 );

		$pdf->setTextColor( 50, 130, 190 );
        $pdf->SetFont( 'Roboto-Regular', '', 11);
		// Année de destruction
        $pdf->SetXY( 56 + $delta, 191 );
        if( $ua['unlimited'] == 1)
    		$this->printMonoLine( $pdf, utf8_decode('Illimitée'), 18, 24 );
        else
            $this->printMonoLine( $pdf, utf8_decode($ua['destructionyear']), 18, 24 );

		return $pdf;
	}

	private function printMonoLine( $pdf, $text, $maxLen, $cellSize, $HAlign = true ){
		$_text = $text;
		if( ($maxLen != -1 )  && ( strlen( $text ) > $maxLen ) )
			$_text = substr( $text, 0, $maxLen );
		$pdf->Cell( $cellSize, 6, $_text, 0, 0, $HAlign?'C':'L', false );
	}

	private function printMultiLine( $pdf, $text, $maxLen, $maxLines, $x, $y, $incY, $cutType, $VAlign = true, $HAlign = true, $manageReturn = false ){
		if( $cutType == self::CUTTYPE_FILL )
			$this->printMultiLineFill( $pdf, $text, $maxLen, $maxLines, $x, $y, $incY, $VAlign, $HAlign );
		if( $cutType == self::CUTTYPE_SPACE )
			$this->printMultiLineSpace( $pdf, $text, $maxLen, $maxLines, $x, $y, $incY, $VAlign, $HAlign, $manageReturn );
	}
	private function printMultiLineFill( $pdf, $text, $maxLen, $maxLines, $x, $y, $incY, $VAlign = true, $HAlign = true  ){
		$_text = $text;
		if( strlen( $text ) > $maxLen * $maxLines )
			$_text = substr( $text, 0, $maxLen * $maxLines );

		$maxL = ceil( strlen( $_text ) / $maxLen );
		if( $VAlign )
		    $debutL = floor(($maxLen - $maxL)/2) - 1;
		else
		    $debutL = 0;
		if( $debutL < 0 ) $debutL = 0;

		for( $i = 0; $i < $maxL; $i++ ){
			$subtext = substr( $text, $i*$maxLen, $maxLen );
			$pdf->SetXY( $x+($HAlign?1:0), $y + ($i+$debutL)*$incY );
			$pdf->Cell( 52, 5, $subtext, 0, 0, $HAlign?'C':'L', false );
		}
	}
	private function printMultiLineSpace( $pdf, $text, $maxLen, $maxLines, $x, $y, $incY, $VAlign = true, $HAlign = true, $manageReturn = false  ){
		$_currentLine = 0;
		$_lastSpace = 0;
		$_lastSpaceInLine = 0;
		$_currentPos = 0;
		$_currentPosInLine = 0;
        $_lastPrintedPos = 0;
		$_printLine = false;
		$_line = '';
		$_beginSpace = true;

        $_end = false;
        if( $text == null || strlen($text) <= 0 )
            $_end = true;
		while( !$_end ){

			// Copy next caracter only if not a space at begin of line
			if( $text[$_currentPos] == ' '){
				if( !$_beginSpace ){
					$_line .= $text[$_currentPos];
					$_currentPosInLine++;
				}
			} else {
				$_beginSpace = false;
				$_line .= $text[$_currentPos];
				$_currentPosInLine++;
			}

			// We found a space, so we remember the position
			if( $text[$_currentPos] == ' ' ){
				//if( $_lastSpace != $_currentPos - 1 )
					$_lastSpace = $_currentPos;
					$_lastSpaceInLine = $_currentPosInLine;
			}

			// We reach end of printable line
			if( $_currentPosInLine >= $maxLen ){
				if(( $_line[$_currentPosInLine-1] != ' ' )&&( $_lastSpaceInLine > 0 )){
					$_line = substr( $_line, 0, $_lastSpaceInLine );
                    $_lastPrintedPos = $_currentPos;
					$_currentPos -= ( $_currentPosInLine - $_lastSpaceInLine );

					$_line = str_pad( $_line, $maxLen );
				}
				$_printLine = true;
			}

			if( $manageReturn ){
			    if( ord($text[$_currentPos]) == 13 ){
			        if( ord($text[$_currentPos+1]) == 10 )
			            $_currentPos++;
                    $_printLine = true;
                }
            }

			// We have a new line to print
			if( $_printLine ){
				$pdf->SetXY( $x+($HAlign?1:0), $y + $_currentLine*$incY );
				$pdf->Cell( 52, 5, $_line , 0, 0, $HAlign?'C':'L', false );

//				$pdf->SetXY( $x + 82, $y + $_currentLine*$incY );
//				$pdf->Cell( 52, 5, 'CL:'.$_currentLine.',CPL:'.$_currentPosInLine.',LS:'.$_lastSpace.',LSL:'.$_lastSpaceInLine, 0, 0, 'C', false );

				$_currentLine++;
				$_currentPosInLine = 0;
				$_lastSpaceInLine = 0;
				$_line = '';
				$_beginSpace = true;
				$_printLine = false;
			}

			// We move forward in the text to print
			$_currentPos++;

			if(( $_currentPos >= strlen( $text ) )||($_currentLine >= $maxLines)) {
                $_end = true;
                if(($_currentPos != $_lastPrintedPos)&&($_currentLine < $maxLines) ){
                    // We have a last line to print
                    $_lastsize = (strlen($text)-$_lastPrintedPos)>=$maxLen?$maxLen:strlen($text)-$_lastPrintedPos;
                    $_lastLine = substr($_line,0,$_lastsize);

                    $pdf->SetXY( $x+($HAlign?1:0), $y + $_currentLine*$incY );
                    $pdf->Cell( 52, 5, $_line , 0, 0, $HAlign?'C':'L', false );
                }
            }
		}
	}

	private function extractDatasToPrint( $uaarray, $setting ){
		$uaToPrint = array();
        $logger = $this->container->getParameter('kernel.environment') == 'dev' ?$this->container->get('logger'):null;

        if( $logger ) $logger->info( '-> extractDatasToPrint ' );
        if( $logger ) $logger->info( ' - datas to treat = '.json_encode($uaarray) );

		if( !array_key_exists( 'service_id', $uaarray ) || ( $uaarray['service_id'] == null ))
			$uaToPrint['service'] = '';
		else {
			$service = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPServices')->find( intval($uaarray['service_id']) );
			if( !$service )
                $uaToPrint['service'] = '';
			else
			    $uaToPrint['service'] = $service->getLongname();
		}

		if( !array_key_exists( 'legalentity_id', $uaarray ) || ( $uaarray['legalentity_id'] == null ) )
			$uaToPrint['legalentity'] = '';
		else {
			$legalentity = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPLegalEntities')->find( intval($uaarray['legalentity_id']) );
			if( !$legalentity )
                $uaToPrint['legalentity'] = '';
			else
			    $uaToPrint['legalentity'] = $legalentity->getLongname();
		}

		if( !array_key_exists( 'ordernumber', $uaarray ))
			$uaToPrint['ordernumber'] = '';
		else
			$uaToPrint['ordernumber'] = $uaarray['ordernumber'];

        $uaToPrint['provider'] = '';
        $uaToPrint['documentnumber'] = '';
        $uaToPrint['boxnumber'] = '';
        $uaToPrint['containernumber'] = '';

        if( $setting['view_provider'] != 0 )
            if( array_key_exists( 'provider_id', $uaarray ) && ( $uaarray['provider_id'] != null ) )
            {
				$provider = $this->getDoctrine()->getRepository( 'bsIDPBackofficeBundle:IDPProviders')->find( intval($uaarray['provider_id']) );
				if( $provider )
                    $uaToPrint['provider'] = $provider->getLongname();
			}

        if( $setting['view_filenumber'] != 0 )
            if( array_key_exists( 'documentnumber', $uaarray) )
                $uaToPrint['documentnumber'] = $uaarray['documentnumber'];

        if( $setting['view_boxnumber'] != 0 )
            if( array_key_exists( 'boxnumber', $uaarray ) )
                $uaToPrint['boxnumber'] = $uaarray['boxnumber'];

        if( $setting['view_containernumber'] != 0 )
            if( array_key_exists( 'containernumber', $uaarray ))
                $uaToPrint['containernumber']  = $uaarray['containernumber'];

		if( !array_key_exists( 'closureyear', $uaarray ))
			$uaToPrint['closureyear'] = '';
		else
			$uaToPrint['closureyear'] = $uaarray['closureyear'];

        $unlimited = false;
        if( !array_key_exists( 'unlimited', $uaarray )) {
            $uaToPrint['unlimited'] = 0;
            $uaToPrint['unlimitedcomments'] = '';
            $unlimited = false;
        } else {
            $uaToPrint['unlimited'] = $uaarray['unlimited'];
            if( !array_key_exists( 'unlimitedcomments', $uaarray ) )
                $uaToPrint['unlimitedcomments'] = '';
            else
                $uaToPrint['unlimitedcomments'] = $uaarray['unlimitedcomments'];
            if( $uaToPrint['unlimited'] == 1 )
                $unlimited = true;
        }

		if( !array_key_exists( 'destructionyear', $uaarray ))
			$uaToPrint['destructionyear'] = $unlimited?'Illimitée':'';
		else
			$uaToPrint['destructionyear'] = $unlimited?'Illimitée':$uaarray['destructionyear'];

        $uaToPrint['documentnature'] = null;
		if( $setting['view_documentnature']  ){
			if( array_key_exists( 'documentnature_id', $uaarray ) && ( $uaarray['documentnature_id'] != null ) ){
				$documentnature = $this->getDoctrine()->getRepository( 'bsIDPBackofficeBundle:IDPDocumentNatures')->find( intval($uaarray['documentnature_id']));
				if( !$documentnature )
                    $uaToPrint['documentnature'] = '';
				else
                    $uaToPrint['documentnature'] = $documentnature->getLongname();
			}
		}

        $uaToPrint['documenttype'] = null;
		if( $setting['view_documenttype'] ){
			if( array_key_exists( 'documenttype_id', $uaarray ) && ( $uaarray['documenttype_id'] != null ) ){
				$documenttype = $this->getDoctrine()->getRepository( 'bsIDPBackofficeBundle:IDPDocumentTypes')->find( intval($uaarray['documenttype_id']));
				if( !$documenttype )
                    $uaToPrint['documenttype'] = '';
				else
                    $uaToPrint['documenttype'] = $documenttype->getLongname();
			}
		}

        $uaToPrint['description1'] = null;
		$uaToPrint['desc1_title'] = $setting['name_description1'];
		if( $setting['view_description1']  ){
			if( array_key_exists( 'description1_id', $uaarray ) && ( $uaarray['description1_id'] != null )){
				$description1 = $this->getDoctrine()->getRepository( 'bsIDPBackofficeBundle:IDPDescriptions1')->find( intval($uaarray['description1_id']));
				if( !$description1 )
                    $uaToPrint['description1'] = '';
				else
                    $uaToPrint['description1'] = $description1->getLongname();
			}
		}

        $uaToPrint['description2'] = null;
        $uaToPrint['desc2_title'] = $setting['name_description2'];
		if( $setting['view_description2']  ){
			if( array_key_exists( 'description2_id', $uaarray ) && ( $uaarray['description2_id'] != null ) ){
				$description2 = $this->getDoctrine()->getRepository( 'bsIDPBackofficeBundle:IDPDescriptions2')->find( intval($uaarray['description2_id']));
				if( !$description2 )
                    $uaToPrint['description2'] = '';
				else
                    $uaToPrint['description2'] = $description2->getLongname();
			}
		}

        $uaToPrint['limitsnum'] = null;
        $uaToPrint['limitsnummin'] = null;
        $uaToPrint['limitsnummax'] = null;
		if( $setting['view_limitsnum'] != 0 ){
			if( array_key_exists( 'limitnummin', $uaarray ) && array_key_exists( 'limitnummax', $uaarray ))
				if( ($uaarray['limitnummin'] != null) && ($uaarray['limitnummax'] != null) &&
                    (strlen( $uaarray['limitnummin']) > 0) && (strlen( $uaarray['limitnummax']) > 0 )){
                    $uaToPrint['limitsnum'] = $uaarray['limitnummin'] . self::LIMITMINMAX . $uaarray['limitnummax'];
                    $uaToPrint['limitsnummin'] = $uaarray['limitnummin'];
                    $uaToPrint['limitsnummax'] = $uaarray['limitnummax'];
				}
		}
        $uaToPrint['limitsalpha'] = null;
        $uaToPrint['limitsalphamin'] = null;
        $uaToPrint['limitsalphamax'] = null;
		if( $setting['view_limitsalpha'] != 0 ){
			if( array_key_exists( 'limitalphamin', $uaarray ) && array_key_exists( 'limitalphamax', $uaarray ))
				if( ($uaarray['limitalphamin'] != null) && ($uaarray['limitalphamax'] != null) &&
                    (strlen( $uaarray['limitalphamin']) > 0) && (strlen( $uaarray['limitalphamax']) > 0 )){
                    $uaToPrint['limitsalpha'] = $uaarray['limitalphamin'] . self::LIMITMINMAX . $uaarray['limitalphamax'];
                    $uaToPrint['limitsalphamin'] = $uaarray['limitalphamin'];
                    $uaToPrint['limitsalphamax'] = $uaarray['limitalphamax'];
				}
		}
        $uaToPrint['limitsalphanum'] = null;
        $uaToPrint['limitsalphanummin'] = null;
        $uaToPrint['limitsalphanummax'] = null;
		if( $setting['view_limitsalphanum'] != 0 ){
			if( array_key_exists( 'limitalphanummin', $uaarray ) && array_key_exists( 'limitalphanummax', $uaarray ))
				if( ($uaarray['limitalphanummin'] != null) && ($uaarray['limitalphanummax'] != null) &&
                    (strlen( $uaarray['limitalphanummin']) > 0) && (strlen( $uaarray['limitalphanummax']) > 0 )){
                    $uaToPrint['limitsalphanum'] = $uaarray['limitalphanummin'] . self::LIMITMINMAX . $uaarray['limitalphanummax'];
                    $uaToPrint['limitsalphanummin'] = $uaarray['limitalphanummin'];
                    $uaToPrint['limitsalphanummax'] = $uaarray['limitalphanummax'];
				}
		}
        $uaToPrint['limitsdate'] = null;
        $uaToPrint['limitsdatemin'] = null;
        $uaToPrint['limitsdatemax'] = null;
		if( $setting['view_limitsdate'] != 0 ){
			if( array_key_exists( 'limitdatemin', $uaarray ) && array_key_exists( 'limitdatemax', $uaarray ))
                if(( $uaarray['limitdatemin'] != null )&&($uaarray['limitdatemax'] != null)){
                    $uaToPrint['limitsdate'] = date_format( $uaarray['limitdatemin'], 'd/m/Y' ) . self::LIMITDATEMINMAX . date_format( $uaarray['limitdatemax'], 'd/m/Y' );
                    $uaToPrint['limitsdatemin'] = date_format( $uaarray['limitdatemin'], 'd/m/Y' );
                    $uaToPrint['limitsdatemax'] = date_format( $uaarray['limitdatemax'], 'd/m/Y' );
    			}
		}

        $uaToPrint['label'] = null;
		if( array_key_exists( 'name', $uaarray )){
            $uaToPrint['label'] = $uaarray['name'] . '   '; // spaces added because of a bug with accent ... TODO: elucidate this mystery
		}

        $uaToPrint['budgetcode'] = null;
        if( $setting['view_budgetcode'] ){
            if( array_key_exists( 'budgetcode_id', $uaarray ) && ( $uaarray['budgetcode_id'] != null ) ) {
                $budgetcode = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPBudgetCodes')->find(intval($uaarray['budgetcode_id']));
                if ( !$budgetcode )
                    $uaToPrint['budgetcode'] = '';
                else
                    $uaToPrint['budgetcode'] = $budgetcode->getLongname();
            }
        }
        if( $logger ) $logger->info( ' - Treat budget code = '.$uaToPrint['budgetcode'] );

		return $uaToPrint;
	}

	private function convertText( $text, $keepReturn = false )
    {
        if(( strlen($text) <= 0 ) || (strlen(mb_detect_encoding($text, mb_detect_order(), true)) <= 0))
            return $text;

        // Remove quotes from microsoft (left and right single and double quotes) and replace them with ascii quote
        $quotes = array(
            "\xC2\xAB"     => '"', // « (U+00AB) in UTF-8
            "\xC2\xBB"     => '"', // » (U+00BB) in UTF-8
            "\xE2\x80\x98" => "'", // ‘ (U+2018) in UTF-8
            "\xE2\x80\x99" => "'", // ’ (U+2019) in UTF-8
            "\xE2\x80\x9A" => "'", // ‚ (U+201A) in UTF-8
            "\xE2\x80\x9B" => "'", // ‛ (U+201B) in UTF-8
            "\xE2\x80\x9C" => '"', // “ (U+201C) in UTF-8
            "\xE2\x80\x9D" => '"', // ” (U+201D) in UTF-8
            "\xE2\x80\x9E" => '"', // „ (U+201E) in UTF-8
            "\xE2\x80\x9F" => '"', // ‟ (U+201F) in UTF-8
            "\xE2\x80\xB9" => "'", // ‹ (U+2039) in UTF-8
            "\xE2\x80\xBA" => "'", // › (U+203A) in UTF-8
            "\xC3\x86" => "AE", // (U+00C6) in UTF-8 AE collés
            "\xC3\xA6" => "ae", // (U+00E6) in UTF-8 ae collés
            "\xC5\x93" => "oe", // (U+0153) in UTF-8 oe collés
            "\xC5\x92" => "OE", // (U+0152) in UTF-8 OE collés
        );
        $temp = strtr($text, $quotes);

        if( !$keepReturn ) {
            $returns = array(
                "\n" => " ",
                "\r" => "",
                "\t" => " ",
            );
            $temp = strtr($temp, $returns);
        }

        // Now convert text in UTF-8
        $output = iconv(mb_detect_encoding($text, mb_detect_order(), true), "UTF-8", $temp);

        return $output;

    }

    // =========================================================================================================
    // Print Sheet section
    // Constants, public function to be called, private functions, ...
    // =========================================================================================================
    // ---------------------------------------------------------------------------------------------------------
    // Constants
    // Global page for sheet definition, height, width and margins
    // PSP = Provider Sheet Page
    const PSP_PAGE_ORIENTATION     = 'L';
    const PSP_PAGE_HEIGHT          = 210;
    const PSP_PAGE_WIDTH           = 297;
    const PSP_TOP_MARGIN           = 13;
    const PSP_LEFT_MARGIN          = 12;
    const PSP_BOTTOM_MARGIN        = 13;
    const PSP_RIGHT_MARGIN         = 12;
    const PSP_DEFAULT_FONT         = 'Roboto-Regular';

    const PSP_DEFAULT_FONT_SIZE    = 8;
    // ---------------------------------------------------------------------------------------------------------
    // Sheet print
    public function printSheetAction( Request $request ){
        $bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
        if( !$bsUserSession->isUserLogged( $request->cookies->get('PHPSESSID') ) )
            return $this->redirect( $this->generateUrl('bs_core_user_login') );
        if( $bsUserSession->getUser()->getChangepass() )
            return $this->redirect( $this->generateUrl('bs_core_user_change_mdp_screen'));

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig', array(
                'error_redirect' => 'bs_idp_dashboard_homepage',
                'error_wait_time' => IDPConstants::ERROR_WAIT_TIME,
                'error_title' => null,
                'error_message' => null,
                'error_type' => 2 )); }

        $logger = ($this->container->getParameter('kernel.environment') == 'dev')?$this->container->get('logger'):null;
        if( $logger ) $logger->info( '-> Enter printSheetAction - Retreive parameters ');

        // $_GET parameters
        $parameters = $request->query;
        // $_POST parameters
        // $parameters = $request->request;

        if( $parameters->has( 'id' ) )
            $id = $parameters->get('id');
        else
            $id = -1;

        $archive_id = ($id!=-1)?(strlen($id)>0)?intval($id):-1:-1;
        if( $archive_id ){
            $uaarray = $this->getDoctrine()->getRepository('bsIDPArchiveBundle:IDPArchive')
                ->getArray( $archive_id );
        } else {
            // ERROR
            return -1;
        }

        if( !$uaarray )
            return -2;
        $uaarray = $uaarray[0];

        // Get settings for the service of this archive
        $settings = $this->getDoctrine()
            ->getRepository('bsIDPBackofficeBundle:IDPServiceSettings')
            ->arrayFindOneByService( $uaarray['service_id'] );
        $setting = $settings[0];

        // Get back all texts to print this archive
        $uaToPrint = $this->extractDatasToPrint( $uaarray, $setting );
        if( $uaToPrint == null )
            return -3;

        if( $logger ) $logger->info( '- init pdf ');
        $pdf = new \FPDF( self::PSP_PAGE_ORIENTATION, 'mm', array( self::PSP_PAGE_WIDTH, self::PSP_PAGE_HEIGHT ) );
        $this->initPrintSheet( $pdf );

        if( $logger ) $logger->info( '- make template ');
        $this->makeSheetTemplate( $pdf, $setting, $uaToPrint );

        if( $logger ) $logger->info( '- fill template ');
        $this->fillSheetTemplate( $pdf, $setting, $uaToPrint );

        $pdfOutput = $pdf->Output('S' );

        $response = new Response( $pdfOutput );
        $response->headers->set('Content-Type', 'application/pdf');
        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            'uasheet.pdf'
        );
        $response->headers->set('Content-Disposition', $disposition);
        $response->headers->set('Cache-Control', 'private, max-age=0, must-revalidate');
        $response->headers->set('Pragma', 'public');
        //return null; // for debug purpose
        return $response;
    }
    // .......................................................................................................
    // This function initialize the print sheet pdf, with new fonts and new page
    // -> pdf:       pdf object to work with
    private function initPrintSheet( $pdf ){
        $pdf->SetMargins( 0, 0, 0 );
        $pdf->SetAutoPageBreak( false, 0 );

        $pdf->AddFont( self::PSP_DEFAULT_FONT, '', 'OpenSans-Regular.php' );
        $pdf->AddFont( self::PSP_DEFAULT_FONT, 'B', 'OpenSans-Bold.php' );
        $pdf->AddFont( self::PSP_DEFAULT_FONT, 'I', 'OpenSans-Italic.php' );
        $pdf->AddFont( self::PSP_DEFAULT_FONT, 'BI', 'OpenSans-BoldItalic.php' );
        $pdf->AddFont('Roboto-Regular', '', 'Roboto-Regular.php');

        $pdf->AddPage( self::PSP_PAGE_ORIENTATION, array( self::PSP_PAGE_WIDTH, self::PSP_PAGE_HEIGHT ) );
    }
    // .......................................................................................................
    // This function make the print sheet template
    // -> pdf:       pdf object to work with
    // -> settings:  the visibility settings
    // -> uaToPrint: datas to be printed, unlimited field change behavior of template
    private function makeSheetTemplate( $pdf, $setting, $uaToPrint ){
        // Archimage Logo
        $imgDIR = __DIR__.'/../../../../../web/img/';
        $fs = new Filesystem();
        if( $fs->exists( $imgDIR.'Logo.png' ) ) {
            $pdf->Image($imgDIR . 'Logo.png', self::PSP_LEFT_MARGIN, self::PSP_TOP_MARGIN, 53, 0);
        }
        // Main user logo
        if( $fs->exists( $imgDIR.'customer/logo.jpg' ) ) {
            $pdf->Image($imgDIR . 'customer/logo.jpg', 242, self::PSP_TOP_MARGIN, 40, 0);
        }


        $pdf->SetLineWidth( 0,2 );
        $pdf->SetDrawColor( 50,130,190 );
        $pdf->SetFillColor( 200,200,200 );
        $pdf->setTextColor( 0, 0, 0 );

        // sectionLe propriétaire
        $pdf->SetFont( self::PSP_DEFAULT_FONT, 'B', self::PSP_DEFAULT_FONT_SIZE );
        $pdf->SetXY( 10, 46 );
        $pdf->Cell( 59, 4, utf8_decode('Le propriétaire'), 0, 0, 'L', false );
        // Service
        $pdf->SetFont( self::PSP_DEFAULT_FONT, '', self::PSP_DEFAULT_FONT_SIZE );
        $pdf->SetXY( 22, 51 );
        $pdf->Cell( 46, 4, utf8_decode('Service*'), 0, 0, 'L', false );
        $pdf->Rect( 70, 51, 63, 4, 'D' );
        // Entité légale
        $pdf->SetFont( self::PSP_DEFAULT_FONT, '', self::PSP_DEFAULT_FONT_SIZE );
        $pdf->SetXY( 22, 56 );
        $pdf->Cell( 46, 4, utf8_decode('Entité légale*'), 0, 0, 'L', false );
        $pdf->Rect( 70, 56, 63, 4, 'D' );
        if( $setting['view_budgetcode'] ) {
            // Code budgétaire
            $pdf->SetFont(self::PSP_DEFAULT_FONT, '', self::PSP_DEFAULT_FONT_SIZE);
            $pdf->SetXY(22, 61);
            $text = 'Code budgétaire'.($setting['mandatory_budgetcode']?'*':'');
            $pdf->Cell(46, 4, utf8_decode($text), 0, 0, 'L', false);
            $pdf->Rect(70, 61, 63, 4, 'D');
        }

        if( $setting['view_documentnature'] || $setting['view_documenttype'] || $setting['view_description1'] || $setting['view_description2'] ) {
            // section : Les informations descriptives
            $pdf->SetFont(self::PSP_DEFAULT_FONT, 'B', self::PSP_DEFAULT_FONT_SIZE);
            $pdf->SetXY(10, 69);
            $pdf->Cell(59, 4, utf8_decode('Les informations descriptives'), 0, 0, 'L', false);
            if( $setting['view_documentnature'] ) {
                // Activité
                $pdf->SetFont(self::PSP_DEFAULT_FONT, '', self::PSP_DEFAULT_FONT_SIZE);
                $pdf->SetXY(22, 74);
                $text = 'Activité'.($setting['mandatory_documentnature']?'*':'');
                $pdf->Cell(46, 4, utf8_decode($text), 0, 0, 'L', false);
                $pdf->Rect(70, 74, 63, 4, 'D');
            }
            if( $setting['view_documenttype'] ) {
                // Type de document
                $pdf->SetFont(self::PSP_DEFAULT_FONT, '', self::PSP_DEFAULT_FONT_SIZE);
                $pdf->SetXY(22, 79);
                $text = 'Type de document'.($setting['mandatory_documenttype']?'*':'');
                $pdf->Cell(46, 4, utf8_decode($text), 0, 0, 'L', false);
                $pdf->Rect(70, 79, 63, 4, 'D');
            }
            if( $setting['view_description1'] ) {
                // Descriptif1
                $pdf->SetFont(self::PSP_DEFAULT_FONT, '', self::PSP_DEFAULT_FONT_SIZE);
                $pdf->SetXY(22, 84);
                $text = $setting['name_description1'].($setting['mandatory_description1']?'*':'');
                $pdf->Cell(46, 4, utf8_decode($text), 0, 0, 'L', false);
                $pdf->Rect(70, 84, 63, 4, 'D');
            }
            if( $setting['view_description2'] ) {
                // Descriptif2
                $pdf->SetFont(self::PSP_DEFAULT_FONT, '', self::PSP_DEFAULT_FONT_SIZE);
                $pdf->SetXY(22, 89);
                $text = $setting['name_description2'].($setting['mandatory_description2']?'*':'');
                $pdf->Cell(46, 4, utf8_decode($text), 0, 0, 'L', false);
                $pdf->Rect(70, 89, 63, 4, 'D');
            }
        }

        // section : La durée de conservation
        $pdf->SetFont( self::PSP_DEFAULT_FONT, 'B', self::PSP_DEFAULT_FONT_SIZE );
        $pdf->SetXY( 10, 99 );
        $pdf->Cell( 59, 4, utf8_decode('La durée de conservation'), 0, 0, 'L', false );
        // Clôture
        $pdf->SetFont( self::PSP_DEFAULT_FONT, '', self::PSP_DEFAULT_FONT_SIZE );
        $pdf->SetXY( 22, 104 );
        $pdf->Cell( 46, 4, utf8_decode('Année de clôture*'), 0, 0, 'L', false );
        $pdf->Rect( 70, 104, 32, 4, 'D' );
        // Illimité
        $pdf->SetFont( self::PSP_DEFAULT_FONT, '', self::PSP_DEFAULT_FONT_SIZE );
        $pdf->SetXY( 108, 104 );
        $pdf->Cell( 46, 4, utf8_decode('Illimitée'), 0, 0, 'L', false );
        $pdf->SetDrawColor( 0,0,0 );
        $pdf->Rect( 104, 104, 4, 4, 'D' );
        $pdf->SetDrawColor( 50,130,190 );
        // Destruction
        $pdf->SetFont( self::PSP_DEFAULT_FONT, '', self::PSP_DEFAULT_FONT_SIZE );
        $pdf->SetXY( 22, 109 );
        $pdf->Cell( 46, 4, utf8_decode('Année de destruction*'), 0, 0, 'L', false );
        $pdf->Rect( 70, 109, 32, 4, ( $uaToPrint['unlimited'] != 0 )?'FD':'D' );
        if( $uaToPrint['unlimited'] != 0 ) {
            // Commentaires Illimité
            $pdf->SetFont(self::PSP_DEFAULT_FONT, '', self::PSP_DEFAULT_FONT_SIZE);
            $pdf->SetXY(70, 146);
            $pdf->Cell(63, 4, utf8_decode('Commentaires illimitée'), 0, 0, 'L', false);
            $pdf->Rect(133, 146, 149, 4, 'D');
        }

        if( $setting['view_filenumber'] || $setting['view_boxnumber'] || $setting['view_containernumber'] || $setting['view_provider'] ) {
            // section : Les identifiants des contenants
            $pdf->SetFont(self::PSP_DEFAULT_FONT, 'B', self::PSP_DEFAULT_FONT_SIZE);
            $pdf->SetXY(10, 115);
            $pdf->Cell(59, 4, utf8_decode('Les identifiants des contenants'), 0, 0, 'L', false);
            if( $setting['view_filenumber'] ) {
                // N° de dossier
                $pdf->SetFont(self::PSP_DEFAULT_FONT, '', self::PSP_DEFAULT_FONT_SIZE);
                $pdf->SetXY(22, 120);
                $text = 'N° de dossier'.($setting['mandatory_filenumber']?'*':'');
                $pdf->Cell(46, 4, utf8_decode($text), 0, 0, 'L', false);
                $pdf->Rect(70, 120, 63, 4, 'D');
            }
            if( $setting['view_boxnumber'] ) {
                // N° de boîte
                $pdf->SetFont(self::PSP_DEFAULT_FONT, '', self::PSP_DEFAULT_FONT_SIZE);
                $pdf->SetXY(22, 125);
                $text = 'N° de boîte'.($setting['mandatory_boxnumber']?'*':'');
                $pdf->Cell(46, 4, utf8_decode($text), 0, 0, 'L', false);
                $pdf->Rect(70, 125, 63, 4, 'D');
            }
            if( $setting['view_containernumber'] ) {
                // N° de conteneur
                $pdf->SetFont(self::PSP_DEFAULT_FONT, '', self::PSP_DEFAULT_FONT_SIZE);
                $pdf->SetXY(22, 130);
                $text = 'N° de conteneur'.($setting['mandatory_containernumber']?'*':'');
                $pdf->Cell(46, 4, utf8_decode($text), 0, 0, 'L', false);
                $pdf->Rect(70, 130, 63, 4, 'D');
            }
            if( $setting['view_provider'] ) {
                // Compte prestataire
                $pdf->SetFont(self::PSP_DEFAULT_FONT, '', self::PSP_DEFAULT_FONT_SIZE);
                $pdf->SetXY(22, 135);
                $text = 'Compte prestataire'.($setting['mandatory_provider']?'*':'');
                $pdf->Cell(46, 4, utf8_decode('Compte prestataire'), 0, 0, 'L', false);
                $pdf->Rect(70, 135, 63, 4, 'D');
            }
        }

        // section : Les numéro d'ordre
        $pdf->SetFont( self::PSP_DEFAULT_FONT, 'B', self::PSP_DEFAULT_FONT_SIZE );
        $pdf->SetXY( 156, 29 );
        $pdf->Cell( 32, 4, utf8_decode("Le numéro d'ordre*"), 0, 0, 'L', false );
        $pdf->Rect( 190, 29, 26, 4, 'D' );

        // Section : Le libellé
        $pdf->SetFont( self::PSP_DEFAULT_FONT, 'B', self::PSP_DEFAULT_FONT_SIZE );
        $pdf->SetXY( 212, 46 );
        $pdf->Cell( 15, 4, utf8_decode("Le libellé*"), 0, 0, 'L', false );
        $pdf->Rect( 156, 51, 126, 58, 'D' );

        if( $setting['view_limitsdate'] || $setting['view_limitsnum'] || $setting['view_limitsalpha'] || $setting['view_limitsalphanum'] ) {
            // section : Les bornes
            $pdf->SetFont(self::PSP_DEFAULT_FONT, 'B', self::PSP_DEFAULT_FONT_SIZE);
            $pdf->SetXY(210, 110);
            $pdf->Cell(16, 4, utf8_decode('Les bornes'), 0, 0, 'L', false);
            if( $setting['view_limitsdate'] ) {
                // Date
                $pdf->SetFont(self::PSP_DEFAULT_FONT, '', self::PSP_DEFAULT_FONT_SIZE);
                $pdf->SetXY(164, 115);
                $text = 'Date de'.($setting['mandatory_limitsdate']?'*':'');
                $pdf->Cell(29, 4, utf8_decode($text), 0, 0, 'L', false);
                $pdf->Rect(194, 115, 31, 4, 'D');
                $pdf->SetXY(234, 115);
                $pdf->Cell(4, 4, utf8_decode('à'), 0, 0, 'L', false);
                $pdf->Rect(244, 115, 31, 4, 'D');
            }
            if( $setting['view_limitsnum'] ) {
                // Numérique
                $pdf->SetFont(self::PSP_DEFAULT_FONT, '', self::PSP_DEFAULT_FONT_SIZE);
                $pdf->SetXY(164, 120);
                $text = 'Num. de'.($setting['mandatory_limitsnum']?'*':'');
                $pdf->Cell(29, 4, utf8_decode($text), 0, 0, 'L', false);
                $pdf->Rect(194, 120, 31, 4, 'D');
                $pdf->SetXY(234, 120);
                $pdf->Cell(4, 4, utf8_decode('à'), 0, 0, 'L', false);
                $pdf->Rect(244, 120, 31, 4, 'D');
            }
            if( $setting['view_limitsalpha'] ) {
                // Alphabtique
                $pdf->SetFont(self::PSP_DEFAULT_FONT, '', self::PSP_DEFAULT_FONT_SIZE);
                $pdf->SetXY(164, 125);
                $text = 'Alpha. de'.($setting['mandatory_limitsalpha']?'*':'');
                $pdf->Cell(29, 4, utf8_decode($text), 0, 0, 'L', false);
                $pdf->Rect(194, 125, 31, 4, 'D');
                $pdf->SetXY(234, 125);
                $pdf->Cell(4, 4, utf8_decode('à'), 0, 0, 'L', false);
                $pdf->Rect(244, 125, 31, 4, 'D');
            }
            if( $setting['view_limitsalphanum'] ) {
                // Alphanumérique
                $pdf->SetFont(self::PSP_DEFAULT_FONT, '', self::PSP_DEFAULT_FONT_SIZE);
                $pdf->SetXY(164, 130);
                $text = 'Alphanum. de'.($setting['mandatory_limitsalphanum']?'*':'');
                $pdf->Cell(29, 4, utf8_decode($text), 0, 0, 'L', false);
                $pdf->Rect(194, 130, 31, 4, 'D');
                $pdf->SetXY(234, 130);
                $pdf->Cell(4, 4, utf8_decode('à'), 0, 0, 'L', false);
                $pdf->Rect(244, 130, 31, 4, 'D');
            }
        }
    }
    // .......................................................................................................
    // This function make the print sheet template
    // -> pdf:       pdf object to work with
    // -> settings:  the visibility settings
    // -> uadatas:   datas of ua to print
    private function fillSheetTemplate( $pdf, $setting, $uaToPrint ){
        $pdf->SetFont('Roboto-Regular', '', 8);

        // Service
        $pdf->setXY( 70, 50 );
        $this->printMonoLine( $pdf, utf8_decode($this->convertText($uaToPrint['service'])), 34, 46, false );
        // Entité légale
        $pdf->setXY( 70, 55 );
        $this->printMonoLine( $pdf, utf8_decode($this->convertText($uaToPrint['legalentity'])), 34, 46, false );
        if( $setting['view_budgetcode'] ) {
            // Code budgétaire
            $pdf->setXY(70, 60);
            $this->printMonoLine( $pdf, utf8_decode($this->convertText($uaToPrint['budgetcode'])), 34, 46, false );
        }

        if( $setting['view_documentnature'] ) {
            // Activité
            $pdf->setXY(70, 73);
            $this->printMonoLine( $pdf, utf8_decode($this->convertText($uaToPrint['documentnature'])), 34, 46, false );
        }
        if( $setting['view_documenttype'] ) {
            // Type de document
            $pdf->setXY(70, 78);
            $this->printMonoLine( $pdf, utf8_decode($this->convertText($uaToPrint['documenttype'])), 34, 46, false );
        }
        if( $setting['view_description1'] ) {
            // Descriptif 1
            $pdf->setXY(70, 83);
            $this->printMonoLine( $pdf, utf8_decode($this->convertText($uaToPrint['description1'])), 34, 46, false );
        }
        if( $setting['view_description2'] ) {
            // Descriptif 2
            $pdf->setXY(70, 88);
            $this->printMonoLine( $pdf, utf8_decode($this->convertText($uaToPrint['description2'])), 34, 46, false );
        }

        // Année de clôture
        $pdf->setXY( 70, 103 );
        $this->printMonoLine( $pdf, utf8_decode($this->convertText($uaToPrint['closureyear'])), 16, 46, false );
        if( $uaToPrint['unlimited'] != 0 ) {
            // Illimitée
            $pdf->SetDrawColor(0, 0, 0);
            $pdf->Line(104, 104, 108, 108);
            $pdf->Line(104, 108, 108, 104);
            $pdf->SetDrawColor(200, 200, 200);
        }
        // Année de destruction
        $pdf->setXY( 70, 108 );
        $this->printMonoLine( $pdf, utf8_decode($this->convertText($uaToPrint['destructionyear'])), 16, 46, false );
        if( $uaToPrint['unlimited'] != 0 ) {
            // Commentaires illimitée
            $pdf->setXY(133, 145);
            $this->printMonoLine( $pdf, utf8_decode($this->convertText($uaToPrint['unlimitedcomments'])), 78, 46, false );
        }

        if( $setting['view_filenumber'] ) {
            // N° de dossier
            $pdf->setXY(70, 119);
            $this->printMonoLine( $pdf, utf8_decode($this->convertText($uaToPrint['documentnumber'])), 34, 46, false );
        }
        if( $setting['view_boxnumber'] ) {
            // N° de boite
            $pdf->setXY(70, 124);
            $this->printMonoLine( $pdf, utf8_decode($this->convertText($uaToPrint['boxnumber'])), 34, 46, false );
        }
        if( $setting['view_containernumber'] ) {
            // N° de conteneur
            $pdf->setXY(70, 129);
            $this->printMonoLine( $pdf, utf8_decode($this->convertText($uaToPrint['containernumber'])), 34, 46, false );
        }
        if( $setting['view_provider'] ) {
            // Compte prestataire
            $pdf->setXY(70, 134);
            $this->printMonoLine( $pdf, utf8_decode($this->convertText($uaToPrint['provider'])), 34, 46, false );
        }

        // N° d'ordre
        $pdf->setXY( 190, 28 );
        $this->printMonoLine( $pdf, utf8_decode($this->convertText($uaToPrint['ordernumber'])), 9, 46, false );

        // Le libellé
        $pdf->setXY( 156, 50 );
        $this->printMultiLine( $pdf, utf8_decode($this->convertText($uaToPrint['label'], true)), 73, 19, 156, 51, 3, self::CUTTYPE_SPACE, false, false,true );

        if( $setting['view_limitsdate'] ) {
            // Date
            $pdf->setXY(194, 114);
            $this->printMonoLine( $pdf, utf8_decode($this->convertText($uaToPrint['limitsdatemin'])), 16, 46, false );
            $pdf->setXY(244, 114);
            $this->printMonoLine( $pdf, utf8_decode($this->convertText($uaToPrint['limitsdatemax'])), 16, 46, false );
        }
        if( $setting['view_limitsnum'] ) {
            // Num
            $pdf->setXY(194, 119);
            $this->printMonoLine( $pdf, utf8_decode($this->convertText($uaToPrint['limitsnummin'])), 16, 46, false );
            $pdf->setXY(244, 119);
            $this->printMonoLine( $pdf, utf8_decode($this->convertText($uaToPrint['limitsnummax'])), 16, 46, false );
        }
        if( $setting['view_limitsalpha'] ) {
            // Alpha
            $pdf->setXY(194, 124);
            $this->printMonoLine( $pdf, utf8_decode($this->convertText($uaToPrint['limitsalphamin'])), 16, 46, false );
            $pdf->setXY(244, 124);
            $this->printMonoLine( $pdf, utf8_decode($this->convertText($uaToPrint['limitsalphamax'])), 16, 46, false );
        }
        if( $setting['view_limitsalphanum'] ) {
            // Alphanum
            $pdf->setXY(194, 129);
            $this->printMonoLine( $pdf, utf8_decode($this->convertText($uaToPrint['limitsalphanummin'])), 16, 46, false );
            $pdf->setXY(244, 129);
            $this->printMonoLine( $pdf, utf8_decode($this->convertText($uaToPrint['limitsalphanummax'])), 16, 46, false );
        }

    }

    // =========================================================================================================
    // Provider connector section
    // Constants, public function to be called, private functions, ...
    // =========================================================================================================

    // TODO Better Error management
    // TODO Finalize constant use
    // TODO Prepare Translation support

    // ---------------------------------------------------------------------------------------------------------
    // Constants
    // Global page for provider connector definition, height, width and margins
    // PCP = Provider Connector Page
    const PCP_PAGE_HEIGHT          = 210;
	const PCP_PAGE_WIDTH           = 297;
	const PCP_TOP_MARGIN           = 13;
	const PCP_LEFT_MARGIN          = 12;
	const PCP_BOTTOM_MARGIN        = 13;
	const PCP_RIGHT_MARGIN         = 12;
	const PCP_DEFAULT_FONT         = 'OpenSans';

	const PCP_BLOCK_MARGIN = 10;
	const PCP_LINE_OFFSET = 5;      // Because box is top-left positionned, and text is bottom-left positionned
	// Logos Block
    const PCP_PROVIDER_LOGO_OFFSET_X = 178;
    const PCP_LOGO_HEIGHT = 30;
    // Date & Contact Block

    const PCP_DATEBLOCK_HEIGHT = 28;
    const PCP_PROVIDER_DATAS_OFFSET_X = 88;
    const PCP_PROVIDER_CONTACT_OFFSET_Y = 14;
    const PCP_PROVIDER_PHONE_OFFSET_Y = 22;
    const PCP_PROVIDER_ADDRESS_OFFSET_Y = 28;
    // Ask type Block
    const PCP_ASKTYPEBLOC_OFFSET_Y = 15;
    // Sub block common
    const PCP_BLOC_SUBBLOCLIST_OFFSET_Y = 15;
    const PCP_SUBBLOC_ITEMS_OFFSET_X = 3;
    private static $PCP_SUBBLOC_ITEMS_OFFSET_Y = array( 9, 15, 21, 27 );
    const PCP_SUBBLOC_CHECKBOX_SIZE = 4;
    const PCP_SUBBLOC_ITEM_1 = 0;
    const PCP_SUBBLOC_ITEM_2 = 1;
    const PCP_SUBBLOC_ITEM_3 = 2;
    const PCP_SUBBLOC_ITEM_4 = 3;
    // Sub block Type
    const PCP_SUBBLOC_TYPE_OFFSET_X = 88;
    // Sub block Disposal
    const PCP_SUBBLOC_DISPOSAL_OFFSET_X = 175;
	// Customer Block
    const PCP_BLOC_CUSTOMER_OFFSET_Y = 14;
    const PCP_CUSTOMER_NAME_OFFSET_X = 30;
    const PCP_CUSTOMER_ARRAY_OFFSET_X = 22;
    const PCP_CUSTOMER_ARRAY_OFFSET_Y = 10;
    // Totals Block
    const PCP_BLOC_TOTALS_OFFSET_Y = 14;
    const PCP_BLOCKTOTAL_HEIGHT = 5;
    // Remarks Block
    const PCP_BLOC_REMARKS_OFFSET_Y = 14;
    const PCP_REMARKBLOC_TEXT_OFFSET_X = 22;
    // Sign Block
    const PCP_BLOC_SIGN_OFFSET_Y = 14;
    const PCP_SIGNBLOC_NAME_OFFSET_Y = 17;
    const PCP_SIGNBLOC_FIRSTNAME_OFFSET_Y = 23;
    const PCP_SIGNBLOC_FUNCTION_OFFSET_Y = 29;
    const PCP_SIGNBLOC_VALUES_OFFSET_X = 28;
    const PCP_SIGNBLOC_SIGN_OFFSET_Y = 45;

    // ---------------------------------------------------------------------------------------------------------
    // bs_idp_archivist_print_provider_connector
    // Provider connector print
    public function printProviderConnectorAction( Request $request ){

        $bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
        if( !$bsUserSession->isUserLogged( $request->cookies->get('PHPSESSID') ) )
            return $this->redirect( $this->generateUrl('bs_core_user_login') );
        if( $bsUserSession->getUser()->getChangepass() )
            return $this->redirect( $this->generateUrl('bs_core_user_change_mdp_screen'));

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig', array(
                'error_redirect' => 'bs_idp_dashboard_homepage',
                'error_wait_time' => IDPConstants::ERROR_WAIT_TIME,
                'error_title' => null,
                'error_message' => null,
                'error_type' => 2 )); }

        /*
                $language = $bsUserSession->getUserExtension()->getLanguage();
                $systemTranslations = $this->getDoctrine()
                    ->getRepository('bsCoreTranslationBundle:bsTranslation')
                    ->getArrayTranslation( bsTranslationIDsCommon::T_SYSTEM, $language );

                if( !$bsUserSession->isUserGotRight( IDPArchimageRights::RIGHTS[IDPArchimageRights::RIGHT_ARCHIVE_NEW][IDPArchimageRights::RIGHT_ID] ) ){
                    $this->sendMessage( $systemTranslations[1] . IDPArchiveRights::STR_ARCHIVE_NEW . '.', 1, $bsUserSession->getUserId() );
                    return $this->redirect( $this->generateUrl('bs_idp_dashboard_homepage' ));
                }

                $translations = $this->getDoctrine()
                    ->getRepository('bsCoreTranslationBundle:bsTranslation')
                    ->getArrayTranslation( bsTranslationIDsArchive::T_PAGE_ADDUASCREEN, $language );
        */
        $logger = ($this->container->getParameter('kernel.environment') == 'dev')?$this->container->get('logger'):null;
        if( $logger ) $logger->info( '-> Enter printProviderConnectorAction - Retreive parameters ');

        // Admin User "Name"
        $bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
        if( $bsUserSession )
            $adminUserName = $bsUserSession->getUser()->getFirstname() . ' ' . $bsUserSession->getUser()->getLastname();
        else
            $adminUserName = 'Session Error';

        // $_GET parameters
        $parameters = $request->query;
        // $_POST parameters
        // $parameters = $request->request;

        if( $parameters->has( 'localizationId' ) )
            $localizationid = $parameters->get('localizationId');
        else
            $localizationid = -1;
        if( $parameters->has( 'uawhat' ) )
            $uawhat = $parameters->get('uawhat');
        else
            $uawhat = -1;
        if( $parameters->has( 'uawhere' ) )
            $uawhere = $parameters->get( 'uawhere' );
        else
            $uawhere = -1;
        if( $parameters->has( 'ids' ) )
            $ids = json_decode($parameters->get('ids'));
        else
            $ids = null;
        if( $parameters->has( 'objects' ) )
            $objects = json_decode( $parameters->get('objects') );
        else
            $objects = null;
        if( $parameters->has( 'contact' ) )
            $contact = $parameters->get('contact');
        else
            $contact = null;
        if( $parameters->has( 'phone' ) )
            $phone = $parameters->get('phone');
        else
            $phone = null;
        if( $parameters->has( 'address' ) )
            $address = $parameters->get('address');
        else
            $address = null;
        if( $parameters->has( 'deliver' ) )
            $deliver = $parameters->get('deliver');
        else
            $deliver = null;
        if( $parameters->has( 'disposal' ) )
            $disposal = $parameters->get('disposal');
        else
            $disposal = null;
        if( $parameters->has( 'type' ) )
            $type = $parameters->get('type');
        else
            $type = null;
        if( $parameters->has( 'type2' ) )
            $type2 = $parameters->get('type2');
        else
            $type2 = null;
        if( $parameters->has( 'remark' ) )
            $remark = $parameters->get('remark');
        else
            $remark = null;
        if( $parameters->has( 'name' ) )
            $name = $parameters->get('name');
        else
            $name = null;
        if( $parameters->has( 'firstname' ) )
            $firstname = $parameters->get('firstname');
        else
            $firstname = null;
        if( $parameters->has( 'function' ) )
            $function = $parameters->get('function');
        else
            $function = null;
        if( $parameters->has( 'pre' ) )
            $pre = $parameters->get( 'pre' )==1;
        else
            $pre = false;

        if( $logger ) $logger->info( '- init pdf ');
        $pdf = new \FPDF( 'L', 'mm', array( self::PCP_PAGE_WIDTH, self::PCP_PAGE_HEIGHT ) );
        $this->initPrintCustomerConnector( $pdf );

        if( $pre )
            $this->PrintWatermark( $pdf, "Connecteur prestataire provisoire" );

        if( $logger ) $logger->info( '- print logos ');
        $y = $this->printLogosBlock( self::PCP_TOP_MARGIN, $localizationid, $pdf );
        if( $y < 0 ) return $y;

        if( $logger ) $logger->info( '- print blocks (date, contact, type, list) ');
        $dateNow = new DateTime();
        $datas = [ 'date' => $dateNow->format('d/m/y'),
            'name' => utf8_decode( $contact ),
            'phone' => utf8_decode( $phone ),
            'address' => utf8_decode( $address ) ];
        $y = $this->printDateContactBlock( $y, $datas, $pdf );
        if( $y < 0 ) return $y-100;

        // For reloc, if where = new simulate transfer, otherwise simulate deliver
        $asktype = ( $uawhat != IDPConstants::UAWHAT_RELOC ? $uawhat : ( $uawhere == IDPConstants::UAWHERE_TRANSFER ? IDPConstants::UAWHAT_TRANSFER : IDPConstants::UAWHAT_CONSULT ) );
        if( $logger ) $logger->info( '- type of connector (uawhat: '.$uawhat.'), ($uawhere: '.$uawhere.') => asktype='.$asktype);
        $y = $this->printAskTypeBlock( $y , $asktype, $pdf );
        if( $y < 0 ) return $y-200;


        $checkeds = [ $deliver, $disposal, $type, $type2 ];
        $y = $this->printListBlocks( $y , 0, $asktype, $checkeds, $pdf );
        if( $y < 0 ) return $y-300;

// -----
        if( $logger ) $logger->info( '- Retreive all datas from DB ');
        // Get all archives sorted on provider, container, box, document
        $uas = $this->getDoctrine()
            ->getRepository( 'bsIDPArchiveBundle:IDPArchive' )
            ->getAllInListForConnectorProvider( $ids );

        // Objects are made only with optimization modalbox. In other cases, should react as if all ua = object selected
        if( $logger ) $logger->info( '- manage Object Optimization ');
        if( $objects != null ){
            $listFullObjectsAsked = $this->computeObjects( $objects );
        } else
            $listFullObjectsAsked = null;
        if( $logger ) $logger->info(json_encode($listFullObjectsAsked));

        if( $logger ) {
            $logger->info('--- Entry for computeListForPdf ---');
            $logger->info(json_encode($uas));
        }
        $datas = $this->computeListForPdf( $uas, $asktype, ($objects!=null?$listFullObjectsAsked:null), $uawhat==IDPConstants::UAWHAT_RELOC );

        if( $logger ) {
            $logger->info('--- DATAS from computeListForPdf ---');
            $logger->info(json_encode($datas));
        }

        $localization = $this->getDoctrine()
            ->getRepository( 'bsIDPBackofficeBundle:IDPLocalizations' )
            ->findOneBy( array( 'id' => $localizationid ) );
        $localizationName = !$localization ? '' : $localization->getLongname();

        if( $logger ) $logger->info( '- Print customers block ');
        $y = $this->printCustomersBlocks( $y , $datas['paccount'], $localizationName, $adminUserName, $pdf, $pre );
        if( $y < 0 ) return $y-400;

// -------
        if( $logger ) $logger->info( '- Print remarks block ');
        $y = $this->printRemarksBlock( $y , $remark, $pdf, $pre );
        if( $y < 0 ) return $y-600;

        if( $asktype == 4 ) {
            if( $logger ) $logger->info( '- Print Sign block ');
            $sign = [ utf8_decode( $name ), utf8_decode( $firstname ), utf8_decode( $function ) ];
            $y = $this->printSignBlock($y , $sign, $pdf, $pre);
            if ($y < 0) return $y-700;
        }

        if( $pdf->PageNo() > 1 )
            $this->printFooter( $pdf );

        // Generate pdf and send ith

        $pdfOutput = $pdf->Output('S' );

        $response = new Response( $pdfOutput );
        $response->headers->set('Content-Type', 'application/pdf');
        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            ($pre?'temporaryconnector.pdf':'providerconnector.pdf')
        );
        $response->headers->set('Content-Disposition', $disposition);
        $response->headers->set('Cache-Control', 'private, max-age=0, must-revalidate');
        $response->headers->set('Pragma', 'public');

        //return null; // for debug purpose
        return $response;

    }

    //.......................................................................................................
    // This function calculate all objects fully asked in optimization modal
    // Object is a list of C_Cnb_Snb and B_Bnb_Snb where C = container, B = Box and S = Service
    private function computeObjects( $objects ){
        $listContainers = [];
        $listSubBoxes = [];
        $listBoxes = [];

        foreach( $objects as $object ){
            if( $object[0] == 'C' ){
                $strExplod = explode( '_', $object );
                if( sizeof($strExplod) == 3 ) {
                    $listContainers[] = ['ObjectNumber' => intval($strExplod[1]), 'ServiceNumber' => intval($strExplod[2])];
                } else if( sizeof($strExplod) == 5 ){
                    $listSubBoxes[] = ['ObjectNumber' => intval($strExplod[4]), 'ServiceNumber' => intval($strExplod[2]), 'ParentNumber' => intval($strExplod[1]) ];
                }
            } elseif ( $objects[0] == 'B' ){
                $strExplod = explode( '_', $object );
                if(  sizeof($strExplod) >= 3 )
                    $listBoxes[] = [ 'ObjectNumber' => intval($strExplod[1]), 'ServiceNumber' => intval($strExplod[2]) ];
            }
        }

        return [ 'containers' => $listContainers, 'boxes' => $listBoxes, 'subboxes' => $listSubBoxes ];
    }

    // .......................................................................................................
    // This function initialize the print customer connector pdf, with new fonts and new page
    // -> pdf:       pdf object to work with
    private function initPrintCustomerConnector( $pdf ){
        $pdf->SetMargins( 0, 0, 0 );
        $pdf->SetAutoPageBreak( false, 0 );

        $pdf->AddFont( self::PCP_DEFAULT_FONT, '', 'OpenSans-Regular.php' );
        $pdf->AddFont( self::PCP_DEFAULT_FONT, 'B', 'OpenSans-Bold.php' );
        $pdf->AddFont( self::PCP_DEFAULT_FONT, 'I', 'OpenSans-Italic.php' );
        $pdf->AddFont( self::PCP_DEFAULT_FONT, 'BI', 'OpenSans-BoldItalic.php' );

        $pdf->AliasNbPages();

        $pdf->AddPage( 'L', array( self::PCP_PAGE_WIDTH, self::PCP_PAGE_HEIGHT ) );
    }
    // .......................................................................................................
    // This function prints the logo block with customer and provider logos
    // -> $localizationId:      Id of localization to retreive logo filename from DB
    // -> pdf:              pdf object to work with
    // <- output:           value: bottom of block / <=0 errors
    private function printLogosBlock( $y, $localizationId, $pdf ){
        $imgDIR = __DIR__.'/../../../../../web/img/';
        $bLogo = false;
        // Main user logo
        $fs = new Filesystem();
        if( $fs->exists( $imgDIR.'customer/logo.jpg' ) ) {
            $pdf->Image($imgDIR . 'customer/logo.jpg', self::PCP_LEFT_MARGIN, $y, 53, 0);
            $bLogo = true;
        }
        // Provider logo
        $pdf->SetFont( self::PCP_DEFAULT_FONT,'B',11 );
        $pdf->SetTextColor( 0, 0, 0 );

        $localization = $this->getDoctrine()
            ->getRepository( 'bsIDPBackofficeBundle:IDPLocalizations' )
            ->findOneBy( array( 'id' => $localizationId ) );
        if( $localization ) {
            $logoname = $localization->getLogo();
            if( $logoname && strlen($logoname) && $fs->exists( $imgDIR . 'providers/'.$logoname ) ) {
                $pdf->Image($imgDIR . 'providers/' . $logoname,
                    self::PCP_LEFT_MARGIN + self::PCP_PROVIDER_LOGO_OFFSET_X, $y, 84, 0);
                $bLogo = true;
            }
        }
        // else $pdf->Text( self::PCP_LEFT_MARGIN + self::PCP_PROVIDER_LOGO_OFFSET_X, self::PCP_TOP_MARGIN, 'logo localization error: '.$localizationId );

        return $y + ( $bLogo ? self::PCP_LOGO_HEIGHT : 0 );
    }
    // .......................................................................................................
    // This function prints the date, contact, phone and address fields
    // -> $y:       Top of block
    // -> $datas:   Datas to be printed
    // -> pdf:      pdf object to work with
    // <- output:   value: bottom of block / <=0 errors
    private function printDateContactBlock( $y, $datas, $pdf ){
        // Minimum data coherency verification
        if( $datas == null )
            return -1;
        if( !array_key_exists( 'date', $datas ) )
            return -2;
        if( $datas['date'] == null )
            return -3;
        if( !array_key_exists( 'name', $datas ) )
            return -4;
        if( $datas['name'] == null )
            return -5;
        if( !array_key_exists( 'phone', $datas ) )
            return -6;
        if( $datas['phone'] == null )
            return -7;
        if( !array_key_exists( 'address', $datas ) )
            return -8;
        if( $datas['address'] == null )
            return -9;

        $currentY = $y + self::PCP_BLOCK_MARGIN + self::PCP_LINE_OFFSET;

        // Date
        $pdf->SetFont( self::PCP_DEFAULT_FONT,'B',11 );
        $pdf->SetTextColor( 0, 0, 0 );

        $pdf->Text( self::PCP_LEFT_MARGIN, $currentY, "Date:" );

        $pdf->Text( self::PCP_LEFT_MARGIN + self::PCP_PROVIDER_DATAS_OFFSET_X, $currentY, $this->convertText( $datas['date'] ) );

        // Contact
        $pdf->Text( self::PCP_LEFT_MARGIN, $currentY + self::PCP_PROVIDER_CONTACT_OFFSET_Y, "Contact:" );

        $pdf->Text( self::PCP_LEFT_MARGIN + self::PCP_PROVIDER_DATAS_OFFSET_X,
            $currentY + self::PCP_PROVIDER_CONTACT_OFFSET_Y, $this->convertText( $datas['name'] ) );

        // Téléphone
        $pdf->Text( self::PCP_LEFT_MARGIN, $currentY + self::PCP_PROVIDER_PHONE_OFFSET_Y, utf8_decode("Téléphone:" ) );

        $pdf->Text( self::PCP_LEFT_MARGIN + self::PCP_PROVIDER_DATAS_OFFSET_X,
            $currentY + self::PCP_PROVIDER_PHONE_OFFSET_Y, $this->convertText( $datas['phone'] ) );

        // Adresse
        $pdf->Text( self::PCP_LEFT_MARGIN, $currentY + self::PCP_PROVIDER_ADDRESS_OFFSET_Y, "Adresse:" );

        $pdf->Text( self::PCP_LEFT_MARGIN + self::PCP_PROVIDER_DATAS_OFFSET_X,
            $currentY + self::PCP_PROVIDER_ADDRESS_OFFSET_Y, $this->convertText( $datas['address'] ) );

        return $currentY +  self::PCP_DATEBLOCK_HEIGHT;
    }
    // .......................................................................................................
    // This function prints the ask type block
    // -> y:         top of ask type block
    // -> asktype:   type of block to print
    // -> pdf:       pdf object to work with
    // <- output:    value: bottom of block / <=0 errors
    private function printAskTypeBlock( $y, $asktype, $pdf ){
        $currentY = $y + self::PCP_BLOCK_MARGIN + self::PCP_LINE_OFFSET;

        $pdf->SetFont( self::PCP_DEFAULT_FONT,'B',11 );
        $pdf->SetTextColor( 0, 0, 0 );
        $pdf->Text( self::PCP_LEFT_MARGIN, $currentY, "Demande:" );

        $pdf->SetFont( self::PCP_DEFAULT_FONT,'B',18);
        $pdf->SetTextColor( 255, 0, 0 );
        $asktype_text = 'Error';
        switch( $asktype ){
            case 0:
            case 5: $asktype_text = utf8_decode( 'Archives nouvelles' ); break;
            case 1: $asktype_text = utf8_decode( 'Consultation' ); break;
            case 2: $asktype_text = utf8_decode( 'Retour' ); break;
            case 3: $asktype_text = utf8_decode( 'Sortie définitive' ); break;
            case 4: $asktype_text = utf8_decode( 'Destruction - Envoi en AR obligatoire' ); break;
            default: return -1;
        }
        $pdf->Text( self::PCP_LEFT_MARGIN + self::PCP_PROVIDER_DATAS_OFFSET_X, $currentY, $asktype_text );

        return $currentY;
    }
    // .......................................................................................................
    // This function prints the sub block lists
    // -> y:         top of deliver sub block
    // -> providerId: id of provider to get back name if needed
    // -> asktype:   type of provider connector to determine sub list to choose
    // -> checkeds:  List of Which item is checked
    // -> pdf:       pdf object to work with
    // <- output:    value: bottom of block / <=0 errors
    private function printListBlocks( $y, $providerId, $asktype, $checkeds, $pdf ){
        if( $checkeds == null )
            return -10;
        if( !is_array( $checkeds ) )
            return -20;
        if( sizeof( $checkeds ) != 4 )
            return -30;

        $currentY = $y + self::PCP_BLOCK_MARGIN + self::PCP_LINE_OFFSET;

        switch( $asktype ){
            case 0: // Archives nouvelles
            case 5:
                return $y;

            case 1: // Consultation
                $y1 = $this->printDeliverSubBlock( $currentY, $checkeds[0], $pdf );
                $y2 = $this->printTypeSubBlock( $currentY, $checkeds[2], $pdf );
                $y3 = $this->printDisposalSubBlock( $currentY, $providerId, $checkeds[1], $pdf );
                if( $y1 < 0 ) return -40 + $y1;
                if( $y2 < 0 ) return -50 + $y2;
                if( $y3 < 0 ) return -60 + $y3;
                return max( $y1, max( $y2, $y3 ) );

            case 2: // Retour
                return $y;

            case 3: // Sortie définitive
                $y1 = $this->printDeliverSubBlock( $currentY, $checkeds[0], $pdf );
                $y2 = $this->printTypeSubBlock( $currentY, $checkeds[2], $pdf );
                if( $y1 < 0 ) return -80 + $y1;
                if( $y2 < 0 ) return -90 + $y2;
                return max( $y1, $y2 );

            case 4: // Destruction
                $y1 = $this->printType2SubBlock( $currentY, $checkeds[3], $pdf );
                if( $y1 < 0 ) return -110 + $y1;
                return $y1;

            default:
                return -120;
        }
    }
    // .......................................................................................................
    // This function prints the deliver sub block list
    // -> y:         top of deliver sub block
    // -> checked:   Which item is checked
    // -> pdf:       pdf object to work with
    // <- output:    value: bottom of block / <=0 errors
    private function printDeliverSubBlock( $y, $checked, $pdf ){
        if( $checked < 0 || $checked > 1 )
            return -1;

        $datas = [ 'title' => 'Livraison', 'list' => [
            [ 'text' => 'Urgent', 'checked' => ( $checked == self::PCP_SUBBLOC_ITEM_1 ) ],
            [ 'text' => 'Normal', 'checked' => ( $checked == self::PCP_SUBBLOC_ITEM_2 ) ] ] ];

        $newY = $this->printListSubBlock( self::PCP_LEFT_MARGIN, $y, $datas, $pdf );
        if( $newY < 0 ) return -2 + $newY;

        return $newY;
    }
    // .......................................................................................................
    // This function prints the type sub block list
    // -> y:         top of type sub block
    // -> checked:   Which item is checked
    // -> pdf:       pdf object to work with
    // <- output:    value: bottom of block / <=0 errors
    private function printTypeSubBlock( $y, $checked, $pdf ){
        if( $checked < 0 || $checked > 1 )
            return -1;

        $datas = [ 'title' => 'Type', 'list' => [
            [ 'text' => 'Sortie temporaire', 'checked' => ( $checked == self::PCP_SUBBLOC_ITEM_1 ) ],
            [ 'text' => utf8_decode( 'Sortie définitive' ), 'checked' => ( $checked == self::PCP_SUBBLOC_ITEM_2 ) ] ] ];

        $newY = $this->printListSubBlock( self::PCP_LEFT_MARGIN + self::PCP_SUBBLOC_TYPE_OFFSET_X, $y, $datas, $pdf );
        if( $newY < 0 ) return -2;

        return $newY;
    }
    // .......................................................................................................
    // This function prints the type destruction sub block list
    // -> y:         top of type sub block
    // -> checked:   Which item is checked
    // -> pdf:       pdf object to work with
    // <- output:    value: bottom of block / <=0 errors
    private function printType2SubBlock( $y, $checked, $pdf ){
        if( $checked < 0 || $checked > 1 )
            return -1;

        $datas = [ 'title' => 'Type', 'list' => [
            [ 'text' => 'Normal', 'checked' => ( $checked == self::PCP_SUBBLOC_ITEM_1 ) ],
            [ 'text' => utf8_decode( 'Sécurisé' ), 'checked' => ( $checked == self::PCP_SUBBLOC_ITEM_2 ) ] ] ];

        $newY = $this->printListSubBlock( self::PCP_LEFT_MARGIN + self::PCP_SUBBLOC_TYPE_OFFSET_X, $y, $datas, $pdf );
        if( $newY < 0 ) return -2;

        return $newY;
    }
    // .......................................................................................................
    // This function prints the disposal sub block list
    // -> y:         top of disposal sub block
    // -> id:        id of provider to retreive his name
    // -> checked:   Which item is checked
    // -> pdf:       pdf object to work with
    // <- output:    value: bottom of block / <=0 errors
    private function printDisposalSubBlock( $y, $id, $checked, $pdf ){
        if( $checked < 0 || $checked > 3 )
            return -1;

        // TODO get back provider's name from DB

        $datas = [ 'title' => utf8_decode( 'Mise à disposition' ), 'list' => [
            [ 'text' => 'Livraison', 'checked' => ( $checked == self::PCP_SUBBLOC_ITEM_1 ) ],
            [ 'text' => 'Sur place chez le prestataire', 'checked' => ( $checked == self::PCP_SUBBLOC_ITEM_2 ) ],
            [ 'text' => 'Transmission par image (mail)', 'checked' => ( $checked == self::PCP_SUBBLOC_ITEM_3 ) ],
            [ 'text' => utf8_decode( 'Transmission par image (sécurisé)' ), 'checked' => ( $checked == self::PCP_SUBBLOC_ITEM_4 ) ] ] ];

        $newY = $this->printListSubBlock( self::PCP_LEFT_MARGIN + self::PCP_SUBBLOC_DISPOSAL_OFFSET_X, $y, $datas, $pdf );
        if( $newY < 0 ) return -2;

        return $newY;
    }
    // .......................................................................................................
    // This function prints a sub block list
    // -> x:         left of list sub block
    // -> y:         top of list sub block
    // -> datas:     datas to print in list
    // -> pdf:       pdf object to work with
    // <- output:    value: bottom of block / <=0 errors
    private function printListSubBlock( $x, $y, $datas, $pdf ){
        // Minimum data coherency verification
        if( $datas == null )
            return -1;
        if( !array_key_exists( 'title', $datas ) )
            return -2;
        if( $datas['title'] == null )
            return -3;
        if( !array_key_exists( 'list', $datas ) )
            return -4;
        if( $datas['list'] == null )
            return -5;
        if( sizeof( $datas['list'] ) <= 0 )
            return -6;

        $pdf->SetFont( self::PCP_DEFAULT_FONT,'I',11 );
        $pdf->SetTextColor( 0, 0, 0 );
        $pdf->Text( $x, $y, $this->convertText( $datas['title'] ) );

        $i = 0;
        foreach( $datas['list'] as $line ){
            $pdf->Text( $x + self::PCP_SUBBLOC_CHECKBOX_SIZE + self::PCP_SUBBLOC_ITEMS_OFFSET_X,
                $y + self::$PCP_SUBBLOC_ITEMS_OFFSET_Y[$i], $line['text'] );
            $pdf->SetDrawColor( 0, 0, 0 );
            $pdf->Rect( $x, $y + self::$PCP_SUBBLOC_ITEMS_OFFSET_Y[$i] - self::PCP_SUBBLOC_CHECKBOX_SIZE,
                self::PCP_SUBBLOC_CHECKBOX_SIZE, self::PCP_SUBBLOC_CHECKBOX_SIZE, 'D' );
            if( $line['checked'] ){
                $pdf->Line( $x, $y + self::$PCP_SUBBLOC_ITEMS_OFFSET_Y[$i] - self::PCP_SUBBLOC_CHECKBOX_SIZE,
                    $x + self::PCP_SUBBLOC_CHECKBOX_SIZE, $y + self::$PCP_SUBBLOC_ITEMS_OFFSET_Y[$i] );
                $pdf->Line( $x, $y + self::$PCP_SUBBLOC_ITEMS_OFFSET_Y[$i], $x + self::PCP_SUBBLOC_CHECKBOX_SIZE,
                    $y + self::$PCP_SUBBLOC_ITEMS_OFFSET_Y[$i]  - self::PCP_SUBBLOC_CHECKBOX_SIZE );
            }
            $i++;
        }

        return $y + self::$PCP_SUBBLOC_ITEMS_OFFSET_Y[$i-1];
    }

    // .......................................................................................................
    // This function prints all customers blocks (auto change page if needed)
    // -> y:         top of customers blocks
    // -> data:      data of customers to be printed, list of [name and list]
    // -> pdf:       pdf object to work with
    // <- output:    value: bottom of block / <=0 errors
    private function printCustomersBlocks( $y, $datas, $localizationName, $adminUserName, $pdf, $pre )
    {
        $dev_mode = $this->container->getParameter('kernel.environment') == 'dev';

        $currentY = $y + self::PCP_BLOCK_MARGIN + self::PCP_LINE_OFFSET;

        if( $dev_mode ) $this->container->get('logger')->info( '-> Enter printCustomersBlocks with paramters (y:'.$y.', $datas:'.json_encode($datas).') - ');

        // Minimum data coherency verification
        if( $datas == null ){
            if( $dev_mode ) $this->container->get('logger')->info( '-< Exit printCustomersBlocks because $datas is null !' );
            return -1;
        }
        if( sizeof( $datas ) <= 0 ) {
            if ($dev_mode) $this->container->get('logger')->info('-< Exit printCustomersBlocks because $datas is empty !');
            return -2;
        }

        $cbfirst = true;
        foreach( $datas as $cblock ) {
            $currentY = $this->printCustomerBlock($currentY + ( $cbfirst ? 0 : self::PCP_BLOC_CUSTOMER_OFFSET_Y ), $cblock, $localizationName, $adminUserName, $pdf, $pre);
            $cbfirst = false;
            if ($currentY < 0){
                if( $dev_mode ) $this->container->get('logger')->info( '-< Exit printCustomersBlocks because printCustomerBlock failed !');
                return $currentY;
            }
        }
        if( $dev_mode ) $this->container->get('logger')->info( '-< Exit printCustomersBlocks successfully !');

        return $currentY;
    }
    // .......................................................................................................
    // This function prints a customer block (auto change page if needed)
    // -> y:         top of customer block
    // -> data:      data of customer to be printed, name and list
    // -> pdf:       pdf object to work with
    // <- output:    value: bottom of block / <=0 errors
    private function printCustomerBlock( $y, $datas, $localizationName, $adminUserName, $pdf, $pre ){
        $dev_mode = $this->container->getParameter('kernel.environment') == 'dev';
        if( $dev_mode ) $this->container->get('logger')->info( '-> Enter printCustomerBlock with paramters (y:'.$y.', $datas:'.json_encode($datas).') - ');

        // Minimum data coherency verification
        if( $datas == null ){
            if( $dev_mode ) $this->container->get('logger')->info( '-< Exit printCustomerBlock because $datas is null !' );
            return -1;
        }
        if( !array_key_exists( 'name', $datas ) ) {
            if ($dev_mode) $this->container->get('logger')->info('-< Exit printCustomerBlock because $datas doesnt contain "name" tag !');
            return -2;
        }
        if( $datas['name'] == null ) {
            if ($dev_mode) $this->container->get('logger')->info('-< Exit printCustomerBlock because $datas["name"] is null !');
            return -3;
        }
        if( !array_key_exists( 'list', $datas ) ) {
            if ($dev_mode) $this->container->get('logger')->info('-< Exit printCustomerBlock because $datas doesnt contain "list" tag !');
            return -4;
        }
        if( $datas['list'] == null ) {
            if ($dev_mode) $this->container->get('logger')->info('-< Exit printCustomerBlock because $datas["list"] is null !');
            return -5;
        }
        if( !array_key_exists( 'total', $datas ) ) {
            if ($dev_mode) $this->container->get('logger')->info('-< Exit printCustomerBlock because $datas doesnt contain "total" tag !');
            return -4;
        }
        if( $datas['total'] == null ) {
            if ($dev_mode) $this->container->get('logger')->info('-< Exit printCustomerBlock because $datas["total"] is null !');
            return -5;
        }

        // First print block Title

        $pdf->SetFont( self::PCP_DEFAULT_FONT,'B',11);
        $pdf->SetTextColor( 0, 0, 0 );
        $strCC = "Compte client " . $localizationName . ":";
        $pdf->Text( self::PCP_LEFT_MARGIN, $y, $strCC );
        $strCCl = $pdf->GetStringWidth( $strCC );
        $pdf->Text( self::PCP_LEFT_MARGIN + $strCCl + 1, $y, $this->convertText( $datas['name'] ) );

        // Print array
        $newY = $this->printCustomerArray( self::PCP_LEFT_MARGIN + self::PCP_CUSTOMER_ARRAY_OFFSET_X,
            $y + self::PCP_CUSTOMER_ARRAY_OFFSET_Y, $datas['list'], $adminUserName, $pdf, $pre );

        if( $newY <= 0 ){
            if( $dev_mode ) $this->container->get('logger')->info( '-< Exit printCustomerBlock because printCustomerArray failed !' );
            return -6;
        }

        if( $dev_mode ) $this->container->get('logger')->info( '- Print totals block ');
        $newY = $this->printTotalBlock( $newY , $datas['total'], $pdf, $pre );
        if( $newY < 0 ) return -7;

        if( $dev_mode ) $this->container->get('logger')->info( '-< Exit printCustomerBlock successfully !' );
        return $newY;
    }

    // .......................................................................................................
    // This function prints a customer array (auto change page if needed)
    // -> x:         left of array
    // -> y:         top of array
    // -> $array:    array of lines
    // -> $pdf:      pdf object to work with
    // <- output:   value: bottom of array / <=0 errors
    private function printCustomerArray( $x, $y, $array, $adminUserName, $pdf, $pre ){
        $dev_mode = $this->container->getParameter('kernel.environment') == 'dev';
        if( $dev_mode ) $this->container->get('logger')->info( '-> Enter printCustomerArray with parameters (x:'.$x.', y:'.$y.', $array:'.json_encode($array).') - ');

        // Verify if enough space for first line with header
        $textToVerify = (strlen($array[0][3])>0?$array[0][3]:'W');
        if( !$this->isTextWithWidthConstraintInPage( $y + 11, 48, self::PCP_PAGE_HEIGHT - self::PCP_BOTTOM_MARGIN, $this->convertText( $textToVerify ), 10, $pdf ) ) {
            $this->addPage($pdf, $pre);
            $currentYArray = self::PCP_TOP_MARGIN;
        } else
            $currentYArray = $y;
        $currentYArray = $this->printCustomerArrayHeader($x, $currentYArray, $pdf);

        // Array lines
        foreach( $array as $line ){
            $resultY = $this->printCustomerArrayLine( $x, $currentYArray, self::PCP_PAGE_HEIGHT - self::PCP_BOTTOM_MARGIN, $line, $adminUserName, $pdf, $pre );
            if( $resultY > 0 )
                $currentYArray = $resultY;
            elseif( $resultY == 0 ) {
                // New Page, Repeat header, re-print line
                $this->addPage( $pdf, $pre );
                $currentYArray = $this->printCustomerArrayHeader( $x, self::PCP_TOP_MARGIN, $pdf );
                $newResultY = $this->printCustomerArrayLine( $x, $currentYArray, self::PCP_PAGE_HEIGHT - self::PCP_BOTTOM_MARGIN, $line, $adminUserName, $pdf, $pre );
                if( $newResultY <= 0 ){
                    // Error
                    $pdf->Text(5, 5, "Error while generating Array !");
                    if( $dev_mode ) $this->container->get('logger')->info( '-< Exit printCustomerArray because printCustomerArrayLine failed !' );
                    return -2;
                }
                // New Page + Print Header + reprint line
                $currentYArray = $newResultY;
            }
            elseif( $resultY < 0 ) {
                // Error
                $pdf->Text(5, 5, "Error while generating Array !");
                if( $dev_mode ) $this->container->get('logger')->info( '-< Exit printCustomerArray because printCustomerArrayLine failed !');
                return -1;
            }
        }
        if( $dev_mode ) $this->container->get('logger')->info( '-< Exit printCustomerArray successfully !');
        return $currentYArray;
    }
    // .......................................................................................................
    // This function prints the header of a customer array
    // -> x:         left of array
    // -> y:         top of array
    // -> $arrayHeaders: array of headers labels
    // -> $pdf:      pdf object to work with
    // <- output:   value: bottom of header / <=0 errors
    private function printCustomerArrayHeader( $x, $y, $pdf ){
        $dev_mode = $this->container->getParameter('kernel.environment') == 'dev';
        if( $dev_mode ) $this->container->get('logger')->info( '-> Enter printCustomerArrayHeader with paramters (x:'.$x.', y:'.$y.') - ');

        $pdf->SetDrawColor( 0, 0, 0 );
        $pdf->SetFillColor( 217, 226, 243 );
        $pdf->Rect( $x, $y, 250, 10, 'DF' );
        $pdf->Line( $x + 50, $y, $x + 50, $y + 10 );
        $pdf->Line( $x + 100, $y, $x + 100, $y + 10 );
        $pdf->Line( $x + 150, $y, $x + 150, $y + 10 );
        $pdf->Line( $x + 200, $y, $x + 200, $y + 10 );

        $pdf->SetFont( self::PCP_DEFAULT_FONT,'B',11);
        $pdf->SetTextColor( 0, 0, 0 );
        $pdf->Text( $x + 4, $y + 6, utf8_decode( "Numéro de conteneur" ) );
        $pdf->Text( $x + 59, $y + 6, utf8_decode( "Numéro de boîte" ) );
        $pdf->Text( $x + 103, $y + 4, utf8_decode( "Numéro de document /" ) );
        $pdf->Text( $x + 117, $y + 9, "dossier" );
        $pdf->Text( $x + 160, $y + 6, utf8_decode( "Libellé indicatif" ) );
        $pdf->Text( $x + 206, $y + 6, "Nom du demandeur" );

        if( $dev_mode ) $this->container->get('logger')->info( '-< Exit printCustomerArrayHeader successfully !');
        return $y + 10;
    }

    // .......................................................................................................
    // This function prints a array line for providerconnector, verify if it can fit in rest of height
    // -> $x:        left of line (box)
    // -> $y:        top of line (box)
    // -> $maxPageY: limit Height of page
    // -> $arrayElements: array of elements to print in line
    // -> $pdf:      pdf object to work with
    // <- output:    value: line has been printed give bottom y of rect, 0: it doesn't because of lack of height, <0 errors
    private function printCustomerArrayLine( $x, $y, $maxPageY, $arrayElements, $adminUserName, $pdf, $pre ){
        $dev_mode = $this->container->getParameter('kernel.environment') == 'dev';
        if( $dev_mode ) $this->container->get('logger')->info( '-> Enter printCustomerArrayLine with paramters (x:'.$x.', y:'.$y.', $maxPageY:'.$maxPageY.', $arrayElements:'.json_encode($arrayElements).') - ');

        // Verify parameters
        if( !is_array( $arrayElements ) ) {
            if ($dev_mode) $this->container->get('logger')->info('-< Exit printCustomerArrayLine because $arrayElements isnt an array !');
            return -1;
        }
        if( sizeof( $arrayElements ) < 5 ) {
            if ($dev_mode) $this->container->get('logger')->info('-< Exit printCustomerArrayLine because $arrayElements doesnt have at least 5 elements, found:' . sizeof($arrayElements));
            return -2;
        }

        $pdf->SetDrawColor( 0, 0, 0 );
        $pdf->SetFont( self::PCP_DEFAULT_FONT,'',10);
        $pdf->SetTextColor( 0, 0, 0 );

        // Verify if there is enough space left
        if( strlen( $arrayElements[3] ) > 0 )
            $textToVerify = $arrayElements[3];
        else
            $textToVerify = 'W';
        $heightOfCell = $this->isTextWithWidthConstraintInPage( $y + 1, 48, $maxPageY, $this->convertText( $textToVerify ), 10, $pdf );
        if( $heightOfCell === false ) {
            if ($dev_mode) $this->container->get('logger')->info('-< Exit printCustomerArrayLine because text doesnt fit in rest of page !');
            return 0;
        }

        // Otherwise do the job
        $lineH = $this->convertPt2mm( 10 ) + 2; // One pixel top-margin, bottom-margin

        // Label, multi-line
        $nbLines = $this->printTextWithWidthConstraint( $x + 151, $y, 48, $this->convertText( $arrayElements[3] ), 10, $pdf, $pre, true, true, $heightOfCell );
        if( $nbLines <= 0 ) $nbLines = 1;
        // Who is asking
        $whoIsAsking = $this->convertText( $arrayElements[4] );
        if( empty( $whoIsAsking ) )
            $whoIsAsking = $adminUserName;
        $this->printTextWithWidthConstraint( $x + 201, $y, 48, $whoIsAsking, 10, $pdf, $pre, true, true, $heightOfCell );

        $pdf->SetFont( self::PCP_DEFAULT_FONT,'B',10);
        // Container number
        $this->printTextWithWidthConstraint( $x + 1, $y, 48, $this->convertText( $arrayElements[0] ), 10, $pdf, $pre, true, true, $heightOfCell );
        // Box number
        $this->printTextWithWidthConstraint( $x + 51, $y, 48, $this->convertText( $arrayElements[1] ), 10, $pdf, $pre, true, true, $heightOfCell );
        // Document number
        $this->printTextWithWidthConstraint( $x + 101, $y, 48, $this->convertText( $arrayElements[2] ), 10, $pdf, $pre, true, true, $heightOfCell );

        // Make Rect and lines
        $pdf->Rect( $x, $y, 250, $nbLines*$lineH );
        $pdf->Line( $x + 50, $y, $x + 50, $y + $nbLines*$lineH );
        $pdf->Line( $x + 100, $y, $x + 100, $y + $nbLines*$lineH );
        $pdf->Line( $x + 150, $y, $x + 150, $y + $nbLines*$lineH );
        $pdf->Line( $x + 200, $y, $x + 200, $y + $nbLines*$lineH );

        if( $dev_mode ) $this->container->get('logger')->info( '-< Exit printCustomerArrayLine successfully !');
        return ( $y + $nbLines*$lineH );
    }
    // .......................................................................................................
    // This function print the Total Block, add a page if needed
    // -> y:        top position of block
    // -> totals:   array of totals to print (must have 3)
    // -> pdf:      pdf object to work with
    // <- output:   value: the new y position / <=0 : error
    private function printTotalBlock( $y, $totals, $pdf, $pre ){
        if( $totals == null )
            return -1;
        if( sizeof( $totals ) != 3 )
            return -2;

        $currentY = $y + self::PCP_BLOCK_MARGIN;

        if( $currentY + 5 > self::PCP_PAGE_HEIGHT - self::PCP_BOTTOM_MARGIN ){
            $this->addPage( $pdf, $pre );
            $currentY = self::PCP_TOP_MARGIN;
        }

        $pdf->SetFont( self::PCP_DEFAULT_FONT,'B',10);
        $pdf->SetTextColor( 255, 0, 0 );

        $pdf->Text( self::PCP_LEFT_MARGIN, $currentY + 4, "Total" );
        $pdf->SetDrawColor( 0, 0, 0 );

        $pdf->Rect( self::PCP_LEFT_MARGIN + 22, $currentY, 150, 5, 'D' );
        $pdf->Line( self::PCP_LEFT_MARGIN + 72, $currentY, self::PCP_LEFT_MARGIN + 72, $currentY + 5 );
        $pdf->Line( self::PCP_LEFT_MARGIN + 122, $currentY, self::PCP_LEFT_MARGIN + 122, $currentY + 5 );

        // $pdf->Text( self::PCP_LEFT_MARGIN + 24, $currentY + 4, $totals[0] );
        $pdf->setXY( self::PCP_LEFT_MARGIN + 24, $currentY );
        $this->printMonoLine( $pdf, $totals[0], -1, 46, true );

        //$pdf->Text( self::PCP_LEFT_MARGIN + 74, $currentY + 4, $totals[1] );
        $pdf->setXY( self::PCP_LEFT_MARGIN + 74, $currentY );
        $this->printMonoLine( $pdf, $totals[1], -1, 46, true );

        //$pdf->Text( self::PCP_LEFT_MARGIN + 124, $currentY + 4, $totals[2] );
        $pdf->setXY( self::PCP_LEFT_MARGIN + 124, $currentY );
        $this->printMonoLine( $pdf, $totals[2], -1, 46, true );


        return $currentY + self::PCP_BLOCKTOTAL_HEIGHT;
    }
    // .......................................................................................................
    // This function prints the remark block, eventually add a new page if necessary
    // -> $y:        top position of block
    // -> $remark:   text to print
    // -> $pdf:      object to work with
    // <- output:    value: new y position / <=0 : error
    private function printRemarksBlock( $y, $remark, $pdf, $pre ){

        $currentY = $y + self::PCP_BLOCK_MARGIN + self::PCP_LINE_OFFSET;

        // Remarques
        $pdf->SetFont( self::PCP_DEFAULT_FONT, 'B', 11 );
        $pdf->SetTextColor( 0, 0, 0 );

        if( $currentY + 6 > self::PCP_PAGE_HEIGHT - self::PCP_BOTTOM_MARGIN ){
            $this->addPage( $pdf, $pre );
            $currentY = self::PCP_TOP_MARGIN;
        }

        $pdf->Text( self::PCP_LEFT_MARGIN, $currentY, "Remarques:" );
        $pdf->SetFont( self::PCP_DEFAULT_FONT, '', 11 );
        $currentY += 6;

        if( !empty($remark) )
            $currentY = $this->printTextWithWidthConstraint( self::PCP_LEFT_MARGIN + self::PCP_REMARKBLOC_TEXT_OFFSET_X, $currentY,
                self::PCP_PAGE_WIDTH - self::PCP_LEFT_MARGIN - self::PCP_RIGHT_MARGIN - self::PCP_REMARKBLOC_TEXT_OFFSET_X,
                $remark, 11, $pdf, $pre, false, false, 0, false, true );

        return $currentY;
    }
    // .......................................................................................................
    // This function prints the sign block, eventually add a new page if necessary, don't truncate this block !
    // -> $y:        top position of block
    // -> $datas:    text to print
    // -> $pdf:      object to work with
    // <- output:    value: new y position / <=0 : error
    private function printSignBlock( $y, $datas, $pdf, $pre ){
        if( $datas == null )
            return -1;
        if( sizeof( $datas ) != 3 )
            return -2;

        $currentY = $y + self::PCP_BLOCK_MARGIN + self::PCP_LINE_OFFSET;

        if( $currentY + self::PCP_SIGNBLOC_SIGN_OFFSET_Y > self::PCP_PAGE_HEIGHT - self::PCP_BOTTOM_MARGIN ){
            $this->addPage( $pdf, $pre );
            $currentY = self::PCP_TOP_MARGIN;
        }

        // Personne habilitée à valider les destructions
        $pdf->SetFont( self::PCP_DEFAULT_FONT, 'BU', 11 );
        $pdf->SetTextColor( 0, 0, 0 );
        $pdf->Text( self::PCP_LEFT_MARGIN, $currentY, utf8_decode( "Personne habilitée à valider les destructions" ) );
        // Nom
        $pdf->SetFont( self::PCP_DEFAULT_FONT, 'B', 11 );
        $pdf->SetTextColor( 0, 0, 0 );
        $pdf->Text( self::PCP_LEFT_MARGIN, $currentY + self::PCP_SIGNBLOC_NAME_OFFSET_Y, "Nom:" );
        $pdf->Text( self::PCP_LEFT_MARGIN + self::PCP_SIGNBLOC_VALUES_OFFSET_X, $currentY + self::PCP_SIGNBLOC_NAME_OFFSET_Y, $datas[0] );
        // Prénom
        $pdf->Text( self::PCP_LEFT_MARGIN, $currentY + self::PCP_SIGNBLOC_FIRSTNAME_OFFSET_Y, utf8_decode( "Prénom:" ) );
        $pdf->Text( self::PCP_LEFT_MARGIN + self::PCP_SIGNBLOC_VALUES_OFFSET_X, $currentY + self::PCP_SIGNBLOC_FIRSTNAME_OFFSET_Y, $datas[1] );
        // Fonction
        $pdf->Text( self::PCP_LEFT_MARGIN, $currentY + self::PCP_SIGNBLOC_FUNCTION_OFFSET_Y, "Fonction:" );
        $pdf->Text( self::PCP_LEFT_MARGIN + self::PCP_SIGNBLOC_VALUES_OFFSET_X, $currentY + self::PCP_SIGNBLOC_FUNCTION_OFFSET_Y, $datas[2] );

        // Signature et cachet de l'entreprise
        $pdf->Text( self::PCP_LEFT_MARGIN, $currentY + self::PCP_SIGNBLOC_SIGN_OFFSET_Y, "Signature et cachet de l'entreprise:" );

        return $currentY + self::PCP_SIGNBLOC_SIGN_OFFSET_Y;
    }
    // .......................................................................................................
    // This function verify if width constraint multi-line text could be printed in the rest of height
    // -> $y:        top position to begin (warning: text is base constraint !)
    // -> $maxW:     maximum width allowed (in pdf unit)
    // -> $maxPageW: maximum height allowed in page
    // -> $str:      string to output
    // -> $ptSize:   size in point of current Font chosen
    // -> $pdf:      pdf object to work with
    // <- output:    false if cannot, height to be used otherwise
    private function isTextWithWidthConstraintInPage( $y, $maxW, $maxPageY, $str, $ptSize, $pdf ){
        $dev_mode = $this->container->getParameter('kernel.environment') == 'dev';
        if( $dev_mode ) $this->container->get('logger')->info( '-> Enter isTextWithWidthConstraintInPage ($y:'.$y.', $maxW:'.$maxW.', $maxPageY:'.$maxPageY.', $str:'.$str.', $ptSize:'.$ptSize.')');
        $i = 0;
        $tmpTxt = '';
        $lineH = $this->convertPt2mm( $ptSize ) + 2; // One pixel top-margin, bottom-margin
        $nbLine = 0;

        // Nothing to print
        if( $str == null )
            return 0;
        // MaxWidth is too small
        if( $maxW < $pdf->getStringWidth( 'WW' ) )
            return 0;

        $nbLine = $this->calculateNbLinesToBeUsed( $maxW, $str, $ptSize, $pdf );

        $resultY = $y + $nbLine * $lineH;
        $out = ( $resultY <= $maxPageY )?($nbLine*$lineH):false;

        if( $dev_mode ) $this->container->get('logger')->info( '-< Exit with: $resultY:'.$resultY.', $nbLine:'.$nbLine.', $lineH:'.$lineH.', out:'.$out);

        return $out;
    }
    // .......................................................................................................
    // This function calculate how many lines are needed to print the text in the width given
    // -> $maxW:     maximum width allowed
    // -> $str:      string to output
    // -> $ptSize:   size in point of current Font chosen
    // -> $pdf:      pdf object to work with
    // <- output:    number of lines output will take
    private function calculateNbLinesToBeUsed( $maxW, $str, $ptSize, $pdf ){
        $i = 0;
        $nbLines = 0;
        $tmpTxt = '';

        $text = utf8_decode( $str );
        while( $i < strlen( $text ) ) {
            $tempSize = $pdf->getStringWidth($tmpTxt . $text[$i]);
            // Oversize max width given
            if ($tempSize > $maxW) {
                $nbLines++;
                $tmpTxt = '';
            }
            $tmpTxt .= $text[$i++];
        }

        if( strlen( $tmpTxt ) > 0 )
            $nbLines++;


        return $nbLines;
    }
    // .......................................................................................................
    // This function output a text with a width constraint.
    // -> $x:        left position to begin
    // -> $y:        top position to begin (warning: text is base constraint !)
    // -> $maxW:     maximum width allowed
    // -> $str:      string to output
    // -> $ptSize:   size in point of current Font chosen
    // -> $pdf:      pdf object to work with
    // -> $onlyOneLine: indicate if we should stop at first line or not
    // -> $autoChangePage: indicate if the function must change page itself
    // <- output:    number of lines output or newY (depends on autochangepage)
    private function printTextWithWidthConstraint( $x, $y, $maxW, $str, $ptSize, $pdf, $pre, $Wcentered = false, $Hcentered = false, $HCell = 0, $onlyOneLine = false, $autoChangePage = false ){
        $dev_mode = $this->container->getParameter('kernel.environment') == 'dev';
        if( $dev_mode ) $this->container->get('logger')->info( '-> Enter printTextWithWidthConstraint with paramters (x:'.$x.', y:'.$y.', $maxW:'.$maxW.', $str:'.$str.', $Wcentered:'.$Wcentered.', $Hcentered:'.$Hcentered.', $HCell:'.$HCell.') - ');

        $i = 0;
        $nbLines = 0;
        $tmpTxt = '';
        $autoChangePageCorrection = 0;

        $lineH = $this->convertPt2mm( $ptSize ) + 2; // One pixel top-margin, bottom-margin

        // Nothing to print
        if( $str == null )
            return 0;
        // MaxWidth is too small
        if( $maxW < $pdf->getStringWidth( 'WW' ) )
            return 0;

        if( $Hcentered ) {
            $deltaY = ($HCell - ($lineH * ($this->calculateNbLinesToBeUsed($maxW, $str, $ptSize, $pdf) ))) / 2;
        } else
            $deltaY = 0;
        if( $dev_mode ) $this->container->get('logger')->info( ' > $deltaY:'.$deltaY.', $lineH:'.$lineH.', calculateNbLinesToBeUsed:'.$this->calculateNbLinesToBeUsed( $maxW, $str, $ptSize, $pdf ) );

        $text = utf8_decode( $str );

        while( $i < strlen( $text ) ){
            $tempSize = $pdf->getStringWidth( $tmpTxt . $text[$i] );
            // Oversize max width given
            if( $tempSize > $maxW ){
                if( $autoChangePage ){
                    if( $y + ( $nbLines + 1 )*$lineH - 1 - $autoChangePageCorrection > self::PCP_PAGE_HEIGHT - self::PCP_BOTTOM_MARGIN ){
                        $this->addPage( $pdf, $pre );
                        $y = self::PCP_TOP_MARGIN;
                        $nbLines = 0;
                    }
                }
                $pdf->Text( $x, $y + $deltaY + ( $nbLines + 1 )*$lineH - 1 , $tmpTxt );
                $tmpTxt = '';
                $nbLines++;
                if( $onlyOneLine )
                    return 1;
            }
            $tmpTxt .= $text[$i++];
        }
        // On more line to print
        if( strlen( $tmpTxt ) > 0 ){
            if( $Wcentered ) $deltaX = ( $maxW - $tempSize ) / 2; else $deltaX = 0;
            $pdf->Text( $x + $deltaX, $y + $deltaY + ( $nbLines + 1 )*$lineH - 1 , $tmpTxt );
            $nbLines++;
        }

        if( !$autoChangePage )
            return $nbLines;
        else
            return ( $y + ( $nbLines + 1 )*$lineH - 1 );
    }
    private function addPage( $pdf, $pre ){
        $this->printFooter( $pdf );
        $pdf->AddPage( 'L', array( self::PCP_PAGE_WIDTH, self::PCP_PAGE_HEIGHT ) );
        if( $pre )
            $this->PrintWatermark( $pdf, "Connecteur prestataire provisoire" );
    }
    private function printFooter( $pdf ){
        $pdf->setFont( self::PCP_DEFAULT_FONT, '', 10 );
        $text = 'Page ' . $pdf->PageNo() . '/{nb}';
        $size = $pdf->getStringWidth( 'Page 1/1' );
        $pdf->Text( self::PCP_PAGE_WIDTH - self::PCP_RIGHT_MARGIN - $size,
            self::PCP_PAGE_HEIGHT - self::PCP_BOTTOM_MARGIN + $this->convertPt2mm(10), $text );
    }

    // Watermark functions
    private function PrintWatermark( $pdf, $text )
    {
        //Affiche le filigrane
        $pdf->SetFont('Arial','B',50);
        $pdf->SetTextColor(231,231,231);
        $pdf->Text(5, 25, $text);
        $pdf->Text(5, 50, $text);
        $pdf->Text(5, 75, $text);
        $pdf->Text(5, 100, $text);
        $pdf->Text(5, 125, $text);
        $pdf->Text(5, 150, $text);
        $pdf->Text(5, 175, $text);
        $pdf->SetFont( self::PCP_DEFAULT_FONT,'',11 );
        $pdf->SetTextColor( 0, 0, 0 );
    }
    // .......................................................................................................
    // Convert a point size (font size) into mm (used in pdf position)
    private function convertPt2mm( $pt ){
        $mm = ( $pt / 72 ) * 25.4;
        return intval( $mm );
    }

    // .......................................................................................................
    // Merge two arrays like needed for the customArrayMerge
    // -> $arrayA        : first array
    // -> $arrayB        : second array
    // <- output         : merged arrays
    //      (1) empty, empty                ==>     empty
    //      (2) empty, [a]                  ==>     [[a]]
    //      (3) empty, [[a],[b]]            ==>     [[a],[b]]
    //      (4) [a], empty                  ==>     [[a]]
    //      (5) [a], [b]                    ==>     [[a],[b]]
    //      (6) [a], [[b],[c]]              ==>     [[a],[b],[c]]
    //      (7) [[a],[b]], empty            ==>     [[a],[b]]
    //      (8) [[a],[b]], [c]              ==>     [[a],[b],[c]]
    //      (9) [[a],[b]], [[c],[d]]        ==>     [[a],[b],[c],[d]]
    private function customArrayMerge( $arrayA, $arrayB ){
        if( $arrayA == null )
            if( $arrayB == null )
                return null; //(1)
            else
                if( is_array( $arrayB ) )
                    if( !is_array($arrayB[0]))
                        return [$arrayB]; // (2)
                    else
                        return $arrayB; // (3)
                else
                    return null;
        else
            if( is_array($arrayA) )
                if( !is_array( $arrayA[0] ) )
                    if( $arrayB == null )
                        return [$arrayA];       // (4)
                    else
                        if( is_array( $arrayB ) )
                            if( !is_array( $arrayB[0] ) )
                                return [[$arrayA],[$arrayB]];  // (5)
                            else
                                return array_merge( [$arrayA], $arrayB ); // (6)
                        else
                            return null;
                else
                    if( $arrayB == null )
                        return $arrayA; // (7)
                    else
                        if( is_array( $arrayB ) )
                            if( !is_array( $arrayB[0] ) )
                                return array_merge( $arrayA, [$arrayB] );   // (8)
                            else
                                return array_merge( $arrayA, $arrayB ); // (9)
                        else
                            return null;
    }

    // .......................................................................................................
    // This function compute to determine if only one line should be drawn or multiple
    private function computeOnlyOneLine( $objName, $objSuid, $actionMode, $isThereOneUnLocked, $listObjectsToBeUsed, $objectParent ){
        $logger = $this->container->getParameter('kernel.environment') == 'dev'?$this->container->get('logger'):null;
        if($logger) $logger->info( '   > computeOnlyOneLine( $objName:'.$objName.', $objSuid:'.$objSuid.', $actionMode:'.$actionMode. ', $isThereOneUnlocked:'.$isThereOneUnLocked);
        if($logger) $logger->info( '$listObjectsToBeUsed: '.json_encode($listObjectsToBeUsed));

        if ($actionMode != IDPConstants::UAWHAT_RETURN) {
                if ( $actionMode == IDPConstants::UAWHAT_CONSULT ) { // Optimization modal
                    if( $listObjectsToBeUsed != null ) {

                        foreach ($listObjectsToBeUsed as $object) {

                            if( !$objectParent ) {  // We are in a container or boxe case
                                if ($objName == $object['ObjectNumber'] && $objSuid == $object['ServiceNumber']) {
                                    if ($logger) $logger->info(' ==> OnlyOneLine because UA found in Object : ' . json_encode($object));
                                    return true;
                                }
                            } else {    // We are in a subboxcase
                                if( $objName == $object['ObjectNumber'] && $objSuid == $object['ServiceNumber'] && $objectParent == $object['ParentNumber'] ){
                                    if ($logger) $logger->info(' ==> OnlyOneLine because UA found in Object : ' . json_encode($object));
                                    return true;
                                }
                            }
                        }
                        if ($logger) $logger->info(' ==> NOT OnlyOneLine because not in object');
                        return false;
                    } else {
                        if ($logger) $logger->info(' ==> NOT OnlyOneLine because not in object which is null');
                        return false;
                    }
                } else {
                    if($logger) $logger->info( ' ==> OnlyOneLine because no Optimization Modal' );
                    return true;
                }
        } else if (!$isThereOneUnLocked) {
            if($logger) $logger->info( ' ==> OnlyOneLine because one UA is locked' );
            return true;
        }

        if($logger) $logger->info( ' ==> NOT OnlyOneLine' );
        return false;
    }

    //........................................................................................................
    // This function verifies if a UA is in a subbox ask
    private function verifyInSubBoxes( $ua, $listObjectsToBeUsed ){
        if( !$listObjectsToBeUsed || !$listObjectsToBeUsed['subboxes'] )
            return false;

        foreach( $listObjectsToBeUsed['subboxes'] as $subbox ){
            if( $subbox['ObjectNumber'] == $ua['boxnumber'] && $subbox['ServiceNumber'] == intval($ua['suid']) && $subbox['ParentNumber'] == $ua['containernumber'] )
                return true;
        }

        return false;
    }

    // .......................................................................................................
    // This function compute the list given by the Db to print it as wanted
    // If all box of container are listed => print only one line for container, otherwise print all lines asked
    // If all document of box are listed => print only one line for box, otherwise print all lines asked
    // -> uas: [ id, puid, plongname, suid, containernumber, boxnumber, documentnumber, name, precisionwho, containerasked, boxasked ]
    // <- result:    array as needed
    // [ 'paccount' => [ 'name' => provider_name, 'list' => [ [ container, box, document, label, who, service ], [ ,,,, ] ], 'total' => [] ]
    //   'total' => [ nbcontainer, nbbox, nbdocument ] ]
    private function computeListForPdf( $uas, $actionMode, $listObjectsToBeUsed, $relocMode ){
        $dev_mode = $this->container->getParameter('kernel.environment') == 'dev';
        if( $dev_mode ) $this->container->get('logger')->info( ' > computeListForPdf ('.json_encode($uas).', '.$actionMode );

        $saveEntryUse = false;
        if( $actionMode == IDPConstants::UAWHERE_TRANSFER && $relocMode )
            $saveEntryUse = true;

        $providersUAs = [];
        $respaccount = [];
        $total = ['container'=>0, 'box'=>0, 'document'=>0];

        if( $dev_mode ) $this->container->get('logger')->info( ' - ZERO - aggregate uas in providers account ' );
        foreach( $uas as $ua ){
            if( !array_key_exists( $ua['puid'], $providersUAs ) ){
                $providersUAs[$ua['puid']] = [ $ua ];
            } else {
                $providersUAs[$ua['puid']][] = $ua;
            }
        }

        foreach( $providersUAs as $providerUAs ) {

            $subtotal = ['container'=>0, 'box'=>0, 'document'=>0];
            $containers = [];
            $boxes = [];
            $documents = [ 'listUAs' => [] ];

            $pname = null;

            if ($dev_mode) $this->container->get('logger')->info(' - FIRST - Treat all uas to construct containers, boxes and documents');
            foreach ($providerUAs as $ua) {
                if ($dev_mode) $this->container->get('logger')->info(' A01- Treat UA : ' . json_encode($ua));
                $pname = $ua['longname'];

                // Construct containers or boxes or documents
                if (!empty($ua['containernumber'])) {
                    // Verify if user ask for a subbox
                    if( $this->verifyInSubBoxes( $ua, $listObjectsToBeUsed ) ){
                        if ($dev_mode) $this->container->get('logger')->info(' A02- containernumber is not empty but it s a subbox ask -> push it into boxes ');
                        $indexKey = $ua['containernumber']. '|' . $ua['suid']. '|' . $ua['boxnumber'];
                        if (!array_key_exists($indexKey, $boxes)) {
                            $boxes[$indexKey] = ['nbInBdd' => 0, 'listUAs' => [], 'isThereOneUnLocked' => false, 'isThereOneLockedByOptimization' => false];
                            $boxes[$indexKey]['nbInBdd'] = $this->getDoctrine()
                                ->getRepository('bsIDPArchiveBundle:IDPArchive')
                                ->countUasWhereSameBox($ua['boxnumber'], $ua['containernumber'], $ua['suid'], $ua['serviceentrydate'], $saveEntryUse);
                        }
//                        if ($ua['boxasked'] < 2)
//                            $boxes[$indexKey]['isThereOneUnLocked'] = true;
//                        if ($ua['boxasked'] >= 2)
//                            $boxes[$indexKey]['isThereOneLockedByOptimization'] = true;
                        $boxes[$indexKey]['listUAs'][] = $ua;

                    } else {
                        if ($dev_mode) $this->container->get('logger')->info(' A02- containernumber is not empty -> push it into $containers ');
                        $indexKey = $ua['containernumber'] . '|' . $ua['suid'];
                        if (!array_key_exists($indexKey, $containers)) {
                            $containers[$indexKey] = ['nbInBdd' => 0, 'listUAs' => [], 'isThereOneUnLocked' => false, 'isThereOneLockedByOptimization' => false];
                            $containers[$indexKey]['nbInBdd'] = $this->getDoctrine()
                                ->getRepository('bsIDPArchiveBundle:IDPArchive')
                                ->countUasWhereSameContainer($ua['containernumber'], $ua['suid'], $ua['serviceentrydate'], $saveEntryUse);
                        }
                        if ($ua['containerasked'] < 2)
                            $containers[$indexKey]['isThereOneUnLocked'] = true;
                        if ($ua['containerasked'] >= 2)
                            $containers[$indexKey]['isThereOneLockedByOptimization'] = true;
                        $containers[$indexKey]['listUAs'][] = $ua;
                    }
                } else {
                    if (!empty($ua['boxnumber'])) {
                        if ($dev_mode) $this->container->get('logger')->info(' A03- boxnumber is not empty -> push it into $boxes ');
                        $indexKey = '|' . $ua['boxnumber'] . '|' . $ua['suid'];
                        if (!array_key_exists($indexKey, $boxes)) {
                            $boxes[$indexKey] = ['nbInBdd' => 0, 'listUAs' => [], 'isThereOneUnLocked' => false, 'isThereOneLockedByOptimization' => false];
                            $boxes[$indexKey]['nbInBdd'] = $this->getDoctrine()
                                ->getRepository('bsIDPArchiveBundle:IDPArchive')
                                ->countUasWhereSameBox($ua['boxnumber'], null, $ua['suid'], $ua['serviceentrydate'], $saveEntryUse);
                        }
                        if ($ua['boxasked'] < 2)
                            $boxes[$indexKey]['isThereOneUnLocked'] = true;
                        if ($ua['boxasked'] >= 2)
                            $boxes[$indexKey]['isThereOneLockedByOptimization'] = true;

                        $boxes[$indexKey]['listUAs'][] = $ua;
                    } else {
                        if ($dev_mode) $this->container->get('logger')->info(' A03- push it into $documents ');
                        $documents['listUAs'][] = $ua;
                    }
                }
            }
            if ($dev_mode) $this->container->get('logger')->info(' A99- result of first step');
            if ($dev_mode) $this->container->get('logger')->info(' A99- containers: ' . json_encode($containers));
            if ($dev_mode) $this->container->get('logger')->info(' A99- boxes: ' . json_encode($boxes));
            if ($dev_mode) $this->container->get('logger')->info(' A99- documents: ' . json_encode($documents));

            // Compute container array in order to obtain lines for pdf
            if ($dev_mode) $this->container->get('logger')->info(' - SECOND - Compute container array in order to obtain lines for pdf');
            $containerLines = [];
            $untreatedContainerLines = [];

            if (!empty($containers)) {
                foreach ($containers as $key => $container) {
                    if ($dev_mode) $this->container->get('logger')->info(' B01- Treat container : ' . json_encode($container));
                    // First verify if we have all uas of container
                    if ($container['nbInBdd'] == sizeof($container['listUAs'])) {
                        if ($dev_mode) $this->container->get('logger')->info(' B02- all uas are asked (nbInBdd=' . $container['nbInBdd'] . ', asked=' . sizeof($container['listUAs']) . ')');
                        // if we are not in Consult mode, this is a new container line to synthesis
                        $onlyOneLine = $this->computeOnlyOneLine( $container['listUAs'][0]['containernumber'],
                            $container['listUAs'][0]['suid'],
                            $actionMode,
                            $container['isThereOneUnLocked'],
                            $listObjectsToBeUsed==null?null:$listObjectsToBeUsed['containers'],
                            null );
//                        $onlyOneLine = false;
//                        if ($actionMode != IDPConstants::UAWHAT_RETURN) $onlyOneLine = true;
//                        else if (!$container['isThereOneUnLocked']) $onlyOneLine = true;
                        if ($onlyOneLine) {
                            if ($dev_mode) $this->container->get('logger')->info(' B03- We are in onlyOneLine mode, so make the line');
                            $newLine = [
                                $container['listUAs'][0]['containernumber'],        // container
                                '',   // box
                                '',  // document
                                ($container['nbInBdd'] > 1) ? '' : $container['listUAs'][0]['name'],    // label
                                $container['listUAs'][0]['precisionwho'],           // who
                                $container['listUAs'][0]['suid']];                  // service
                            $containerLines[] = $newLine;
                            if ($dev_mode) $this->container->get('logger')->info(' B04- newLine = ' . json_encode($newLine));
                        } else {
                            if ($dev_mode) $this->container->get('logger')->info(' B05- We are NOT in onlyOneLine mode, so parse untreated UAs');
                            if ( $container['isThereOneLockedByOptimization'] ) {
                                // Treat a full container ask in recap mode
                                $newLine = [
                                    $container['listUAs'][0]['containernumber'],
                                    '',
                                    '',
                                    ($container['nbInBdd'] > 1) ? '' : $container['listUAs'][0]['name'],
                                    $container['listUAs'][0]['precisionwho'],
                                    $container['listUAs'][0]['suid']];
                                $containerLines[] = $newLine;
                                if ($dev_mode) $this->container->get('logger')->info(' B06- newLine = ' . json_encode($newLine));
                            }
                            if ($container['nbInBdd'] > 0)
                                foreach ($container['listUAs'] as $ua)
                                    if ($ua['containerasked'] < 2)
                                        if (!array_key_exists($key, $untreatedContainerLines))
                                            $untreatedContainerLines[$key] = [$ua];
                                        else
                                            $untreatedContainerLines[$key][] = $ua;
                            if ($dev_mode) $this->container->get('logger')->info(' B07- untreatedUAs = ' . json_encode($untreatedContainerLines));
                        }
                    } else {
                        if (($actionMode == IDPConstants::UAWHAT_CONSULT || $actionMode == IDPConstants::UAWHAT_RETURN) && !$container['isThereOneUnLocked']) {
                            if ($dev_mode) $this->container->get('logger')->info(' B08- Treat a partial container ask in consult mode');
                            // Treat a partial container ask in consult mode can be a container ask in fact
                            if ( $container['isThereOneLockedByOptimization'] ) {
                                $newLine = [
                                    $container['listUAs'][0]['containernumber'],
                                    '',
                                    '',
                                    ($container['nbInBdd'] > 1) ? '' : $container['listUAs'][0]['name'],
                                    $container['listUAs'][0]['precisionwho'],
                                    $container['listUAs'][0]['suid']
                                ];
                                $containerLines[] = $newLine;
                                if ($dev_mode) $this->container->get('logger')->info(' B09- newLine = ' . json_encode($newLine));
                            }
                            foreach ($container['listUAs'] as $ua)
                                if ($ua['containerasked'] < 2)
                                    if (!array_key_exists($key, $untreatedContainerLines))
                                        $untreatedContainerLines[$key] = [$ua];
                                    else
                                        $untreatedContainerLines[$key][] = $ua;
                            if ($dev_mode) $this->container->get('logger')->info(' B10- untreatedUAs = ' . json_encode($untreatedContainerLines));

                        } else {
                            if ($dev_mode) $this->container->get('logger')->info(' B11- It is not a container line, push them in untretated');
                            // Treat a partial container ask in normal mode so it is not a container line
                            foreach ($container['listUAs'] as $ua)
                                if (!array_key_exists($key, $untreatedContainerLines))
                                    $untreatedContainerLines[$key] = [$ua];
                                else
                                    $untreatedContainerLines[$key][] = $ua;
                            if ($dev_mode) $this->container->get('logger')->info(' B12- untreatedUAs = ' . json_encode($untreatedContainerLines));
                        }
                    }
                }
                if ($dev_mode) $this->container->get('logger')->info(' B99- result of second step');
                if ($dev_mode) $this->container->get('logger')->info(' B99- containerLines: ' . json_encode($containerLines));
                if ($dev_mode) $this->container->get('logger')->info(' B99- untreatedUAs: ' . json_encode($untreatedContainerLines));
            }

            // Now we have all untreated lines of container in the $untreadtedContainerLines, we have to sort them into $boxes or $documents
            if ($dev_mode) $this->container->get('logger')->info(' - THIRD - all untreated lines are in $untreated array, sort them into $boxes or $documents');

            if (!empty($untreatedContainerLines)) {
                foreach ($untreatedContainerLines as $untreatedContainerLine) {
                    if ($dev_mode) $this->container->get('logger')->info(' C01- Treat container : ' . json_encode($untreatedContainerLine));

                    foreach ($untreatedContainerLine as $ua) {
                        if ($dev_mode) $this->container->get('logger')->info(' C02- Treat UA : ' . json_encode($ua));

                        if (!empty($ua['boxnumber'])) {
                            if ($dev_mode) $this->container->get('logger')->info(' C03- boxnumber is not empty -> push it into $boxes ');

                            $indexKey = $ua['containernumber'] . '|' . $ua['boxnumber'] . '|' . $ua['suid'];
                            if (!array_key_exists($indexKey, $boxes)) {
                                $boxes[$indexKey] = ['nbInBdd' => 0, 'listUAs' => [], 'isThereOneLocked' => false, 'isThereOneLockedByOptimization' => false];
                                $boxes[$indexKey]['nbInBdd'] = $this->getDoctrine()
                                    ->getRepository('bsIDPArchiveBundle:IDPArchive')
                                    ->countUasWhereSameBox($ua['boxnumber'], $ua['containernumber'], $ua['suid'], $ua['serviceentrydate'], $saveEntryUse);
                            }
                            if ($ua['boxasked'] < 2)
                                $boxes[$indexKey]['isThereOneUnLocked'] = true;
                            if ($ua['boxasked'] >= 2)
                                $boxes[$indexKey]['isThereOneLockedByOptimization'] = true;
                            $boxes[$indexKey]['listUAs'][] = $ua;
                        } else {
                            if ($dev_mode) $this->container->get('logger')->info(' C04- push it into $documents ');
                            $documents['listUAs'][] = $ua;
                        }
                    }
                }
                if ($dev_mode) $this->container->get('logger')->info(' C99- result of third step');
                if ($dev_mode) $this->container->get('logger')->info(' C99- boxes: ' . json_encode($boxes));
                if ($dev_mode) $this->container->get('logger')->info(' C99- documents: ' . json_encode($documents));
            }

            // Compute boxes array in order to obtain lines for pdf
            if ($dev_mode) $this->container->get('logger')->info(' - FOURTH - Compute box array in order to obtain lines for pdf');
            $boxLines = [];
            $untreatedBoxLines = [];

            if (!empty($boxes)) {
                foreach ($boxes as $key => $box) {
                    if ($dev_mode) $this->container->get('logger')->info(' D01- Treat box : ' . json_encode($box));
                    if ($box['nbInBdd'] == sizeof($box['listUAs'])) {
                        if ($dev_mode) $this->container->get('logger')->info(' D02- all uas are asked (nbInBdd=' . $box['nbInBdd'] . ', asked=' . sizeof($box['listUAs']) . ')');
                        $cNb = $box['listUAs'][0]['containernumber'];
                        $onlyOneLine = $this->computeOnlyOneLine( $box['listUAs'][0]['boxnumber'],
                            $box['listUAs'][0]['suid'],
                            $actionMode,
                            $box['isThereOneUnLocked'],
                            $listObjectsToBeUsed==null?null:($cNb==null?$listObjectsToBeUsed['boxes']:$listObjectsToBeUsed['subboxes']),
                            $cNb );
//                        $onlyOneLine = false;
//                        if ($actionMode != IDPConstants::UAWHAT_RETURN) $onlyOneLine = true;
//                        else if (!$box['isThereOneUnLocked']) $onlyOneLine = true;
                        if ($onlyOneLine) {
                            if ($dev_mode) $this->container->get('logger')->info(' D03- We are in onlyOneLine mode, so make the line');
                            $newLine = [
                                $box['listUAs'][0]['containernumber'],
                                $box['listUAs'][0]['boxnumber'],
                                '',
                                ($box['nbInBdd'] > 1) ? '' : $box['listUAs'][0]['name'],
                                $box['listUAs'][0]['precisionwho'],
                                $box['listUAs'][0]['suid']
                            ];
                            $boxLines[] = $newLine;
                            if ($dev_mode) $this->container->get('logger')->info(' D04- newLine = ' . json_encode($newLine));
                        } else {
                            if ($dev_mode) $this->container->get('logger')->info(' D05- We are NOT in onlyOneLine mode, so make a line only if all are unlocked and parse untreated UAs');
                            if ( $box['isThereOneLockedByOptimization'] ) {
                                $newLine = [
                                    $box['listUAs'][0]['containernumber'],
                                    $box['listUAs'][0]['boxnumber'],
                                    '',
                                    ($box['nbInBdd'] > 1) ? '' : $box['listUAs'][0]['name'],
                                    $box['listUAs'][0]['precisionwho'],
                                    $box['listUAs'][0]['suid']
                                ];
                                $boxLines[] = $newLine;
                                if ($dev_mode) $this->container->get('logger')->info(' D06- newLine = ' . json_encode($newLine));
                            }
                            if ($box['nbInBdd'] > 0)
                                foreach ($box['listUAs'] as $ua)
                                    if ($ua['boxasked'] < 2)
                                        if (!array_key_exists($key, $untreatedBoxLines))
                                            $untreatedBoxLines[$key] = [$ua];
                                        else
                                            $untreatedBoxLines[$key][] = $ua;
                            if ($dev_mode) $this->container->get('logger')->info(' D07- untreatedUAs = ' . json_encode($untreatedBoxLines));
                        }
                    } else {
                        if (($actionMode == IDPConstants::UAWHAT_CONSULT || $actionMode == IDPConstants::UAWHAT_RETURN) && !$box['isThereOneUnLocked']) {
                            if ($dev_mode) $this->container->get('logger')->info(' D08- Treat a partial container ask in consult mode');
                            if ( $box['isThereOneLockedByOptimization'] ) {
                                $newLine = [
                                    $box['listUAs'][0]['containernumber'],
                                    $box['listUAs'][0]['boxnumber'],
                                    '',
                                    ($box['nbInBdd'] > 1) ? '' : $box['listUAs'][0]['name'],
                                    $box['listUAs'][0]['precisionwho'],
                                    $box['listUAs'][0]['suid']
                                ];
                                $boxLines[] = $newLine;
                                if ($dev_mode) $this->container->get('logger')->info(' D09- newLine = ' . json_encode($newLine));
                            }
                            foreach ($box['listUAs'] as $ua)
                                if ($ua['boxasked'] < 2)
                                    if (!array_key_exists($key, $untreatedBoxLines))
                                        $untreatedBoxLines[$key] = [$ua];
                                    else
                                        $untreatedBoxLines[$key][] = $ua;
                            if ($dev_mode) $this->container->get('logger')->info(' D10- untreatedUAs = ' . json_encode($untreatedBoxLines));

                        } else {
                            if ($dev_mode) $this->container->get('logger')->info(' D11- It is not a box line, push them in untretated');
                            foreach ($box['listUAs'] as $ua)
                                if (!array_key_exists($key, $untreatedBoxLines))
                                    $untreatedBoxLines[$key] = [$ua];
                                else
                                    $untreatedBoxLines[$key][] = $ua;
                            if ($dev_mode) $this->container->get('logger')->info(' D12- untreatedUAs = ' . json_encode($untreatedBoxLines));

                        }
                    }
                }
                if ($dev_mode) $this->container->get('logger')->info(' D99- result of fourth step');
                if ($dev_mode) $this->container->get('logger')->info(' D99- boxLines: ' . json_encode($boxLines));
                if ($dev_mode) $this->container->get('logger')->info(' D99- untreatedUAs: ' . json_encode($untreatedBoxLines));
            }

            // Now we have all untreated lines of box in the $untreatedBoxLines, we have to push them into $documentLines
            if ($dev_mode) $this->container->get('logger')->info(' - FIFTH - all untreated lines are in $untreated array, push them into $documents');

            if (!empty($untreatedBoxLines)) {
                foreach ($untreatedBoxLines as $untreatedBoxLine) {
                    if ($dev_mode) $this->container->get('logger')->info(' E01- Treat boxline : ' . json_encode($untreatedBoxLine));
                    foreach ($untreatedBoxLine as $ua) {
                        if ($dev_mode) $this->container->get('logger')->info(' E02- Treat UA : ' . json_encode($ua));
                        $documents['listUAs'][] = $ua;
                    }
                }
                if ($dev_mode) $this->container->get('logger')->info(' E99- result of fifth step');
                if ($dev_mode) $this->container->get('logger')->info(' E99- documents: ' . json_encode($documents));
            }

            $subtotal['container'] += sizeof($containerLines);
            $subtotal['box'] += sizeof($boxLines);

            // Compute documents array in order to obtain lines for pdf
            if ($dev_mode) $this->container->get('logger')->info(' - SIXTH - Compute document array in order to obtain lines for pdf');
            $documentLines = [];

            if (!empty($documents['listUAs'])) {
                foreach ($documents['listUAs'] as $document) {
                    if ($dev_mode) $this->container->get('logger')->info(' F01- Treat document : ' . json_encode($document));
                    $newLine = [
                        $document['containernumber'],
                        $document['boxnumber'],
                        $document['documentnumber'],
                        $document['name'],
                        $document['precisionwho'],
                        $document['suid']
                    ];
                    $documentLines[] = $newLine;

                    if( !empty($document['documentnumber']) ){
                        if ($dev_mode) $this->container->get('logger')->info(' F02- newLine count as a document because docNb is not empty' );
                        $subtotal['document'] += 1;
                    } else if( !empty($document['boxnumber']) ){
                        if ($dev_mode) $this->container->get('logger')->info(' F02- newLine count as a box because boxNb is not empty' );
                        $subtotal['box'] += 1;
                    } else if( !empty($document['containernumber']) ){
                        if ($dev_mode) $this->container->get('logger')->info(' F02- newLine count as a container because contNb is not empty' );
                        $subtotal['container'] += 1;
                    } else {
                        if ($dev_mode) $this->container->get('logger')->info(' F02- newLine count as a document because evrything is empty' );
                        $subtotal['document'] += 1;
                    }

                    if ($dev_mode) $this->container->get('logger')->info(' F02- newLine = ' . json_encode($newLine));
                }
                if ($dev_mode) $this->container->get('logger')->info(' F99- result of sixth step');
                if ($dev_mode) $this->container->get('logger')->info(' F99- documentLines: ' . json_encode($documentLines));
            }

            // Now we can make the response
            if ($dev_mode) $this->container->get('logger')->info(' - SEVENTH - Create the response');

            // Merge all lines in one array
            $allLines = array_merge($containerLines, $boxLines);
            $allLines = array_merge($allLines, $documentLines);

            $respaccount[] = [ 'name' => $pname, 'list' => $allLines, 'total' => [ $subtotal['container'], $subtotal['box'], $subtotal['document'] ] ];

            $total['container'] += $subtotal['container'];
            $total['box'] += $subtotal['box'];
            $total['document'] += $subtotal['document'];
        }

        $response = [
            'paccount' => $respaccount,
            'total' => [
                $total['container'],
                $total['box'],
                $total['document']
            ]
        ];
        if( $dev_mode ) $this->container->get('logger')->info( ' G99- result of seventh step' );
        if( $dev_mode ) $this->container->get('logger')->info( ' F99- response: '.json_encode( $response ) );

        //return null;
        return $response;
    }


    // =========================================================================================================
    // Table print section
    // Constants, public function to be called, private functions, ...
    // bs_idp_archive_print_table
    // =========================================================================================================

    // TODO Better Error management
    // TODO Finalize constant use
    // TODO Prepare Translation support

    // ---------------------------------------------------------------------------------------------------------
    // All Table print offline (array mode or card-view mode)
    public function printTableOfflineAction( Request $request ){
        $output = array('message' => 'Archimage');

        $bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
        if( !$bsUserSession->isUserLogged( $request->cookies->get('PHPSESSID') ) )
            return new JsonResponse( $output, 403 );
        if( $bsUserSession->getUser()->getChangepass() )
            return new JsonResponse( $output, 403 );

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ) {
            $output['message'] = "Un rapprochement est en cours de calcul, il n'est pas possible d'effectuer une impression pendant cette action !";
            return new JsonResponse($output, 409);
        }

        $logger = ($this->container->getParameter('kernel.environment') == 'dev')?$this->container->get('logger'):null;

        // Verification if not already in print action
        $userFilesResume = $this->getDoctrine()
            ->getRepository('bsCoreUsersBundle:IDPUserFiles')
            ->getUserFilesResume( $bsUserSession->getUserId() );
        if( $userFilesResume['in_progress'] ) {
            $output['message'] = "Une autre impression ou un autre export est en cours, un(e) seul(e) impression/export à la fois est autorisé(e)!";
            return new JsonResponse($output, 409);
        }
        if( $userFilesResume['nb_files'] >= 10 ){
            $output['message'] = "Vous avez déjà 10 fichiers dans votre espace, libérez de la place avant de demander une génération supplémentaire !";
            return new JsonResponse($output, 409);
        }

        // $_GET parameters
        $parameters = $request->query;
        // $_POST parameters
        // $parameters = $request->request;

        if( $parameters->has( 'listId' ) )
            $listId = (array)json_decode( $parameters->get('listId') );
        else
            $listId = null;
        if( $parameters->has( 'listColumn' ) )
            $listColumn = (array)json_decode( $parameters->get( 'listColumn' ) );
        else
            $listColumn = null;
        if( $parameters->has( 'format' ) )
            $format = $parameters->get('format');
        else
            $format = self::TP_FORMAT_ARRAY;
        if( $parameters->has('xpsearch' ) )
            $xpsearch = (array)json_decode( $parameters->get('xpsearch') );
        else
            $xpsearch = null;
        if( $parameters->has('whereAmI' ) )
            $whereAmI = $parameters->get('whereAmI') ;
        else
            $whereAmI = null;
        if( $parameters->has('cardview' ) )
            $cardview = (intval($parameters->get('cardview'))!=0) ;
        else
            $cardview = false;

        // Pre-calculate array of array because implode doesn't manage it
        $collArr = "";
        if( $listColumn ){
            foreach( $listColumn as $column ) {
                if (strlen($collArr) > 0)
                    $collArr .= ";";
                $collArr .= "[" . implode(",", $column) . "]";
            }
        }

        // Launch command and send response
        $processCmd = "php ../bin/console app:print-table-offline ".
            $bsUserSession->getUserId()." ".
            "[". implode(";", $listId ) ."] ".
            "\"[".$collArr."]\" ".
            $format." ".
            "\"[".implode( ";", $xpsearch )."]\" ".
            $whereAmI." ".
            ($cardview?"1":"0");    // true false mng

        if( $logger ) $logger->info( $processCmd );

        $process = new Process( $processCmd );
        $process->start();

        sleep(1); // wait for process to start

        //check for errors and send them
        if (!$process->isRunning())
            if (!$process->isSuccessful()){
                // $output['uploaded'] = true;
                $output['message'] = "Oops! The process fininished with an error: ".$process->getExitCode();

//                $globalStatuses->setReconciliationInProgress( IDPReconciliation::ERROR_PROCESS_ASYNC );
//                $em->persist( $globalStatuses );
//                $em->flush();

                return new JsonResponse( $output, 403 );
            }

        //return null;

        return new JsonResponse( $output );
    }
        // ---------------------------------------------------------------------------------------------------------
    // Table print (array mode or card-view mode)
    public function printTableAction( Request $request ){
        $bsUserSession = new bsCoreUserSession( $request->getSession(), $this->getDoctrine() );
        if( !$bsUserSession->isUserLogged( $request->cookies->get('PHPSESSID') ) )
            return $this->redirect( $this->generateUrl('bs_core_user_login') );
        if( $bsUserSession->getUser()->getChangepass() )
            return $this->redirect( $this->generateUrl('bs_core_user_change_mdp_screen'));

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses' ) ->findOneBy( array( 'id' => 1 ) );
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 50 ){
            return $this->render( 'bsCoreUsersBundle:bsUsers:error.html.twig', array(
                'error_redirect' => 'bs_idp_dashboard_homepage',
                'error_wait_time' => IDPConstants::ERROR_WAIT_TIME,
                'error_title' => null,
                'error_message' => null,
                'error_type' => 2 )); }

        $logger = ($this->container->getParameter('kernel.environment') == 'dev')?$this->container->get('logger'):null;

        // $_GET parameters
        $parameters = $request->query;
        // $_POST parameters
        // $parameters = $request->request;

        if( $parameters->has( 'listId' ) )
            $listId = (array)json_decode( $parameters->get('listId') );
        else
            $listId = null;
        if( $parameters->has( 'listColumn' ) )
            $listColumn = (array)json_decode( $parameters->get( 'listColumn' ) );
        else
            $listColumn = null;
        if( $parameters->has( 'format' ) )
            $format = $parameters->get('format');
        else
            $format = self::TP_FORMAT_ARRAY;
        if( $parameters->has('xpsearch' ) )
            $xpsearch = (array)json_decode( $parameters->get('xpsearch') );
        else
            $xpsearch = null;
        if( $parameters->has('fctCall' ) )
            $fctCall = $parameters->get('fctCall') ;
        else
            $fctCall = null;
        if( $parameters->has('cardview' ) )
            $cardview = (intval($parameters->get('cardview'))!=0) ;
        else
            $cardview = false;

        if( $logger ) {
            $logger->info('--- Begin printTableAction ---');
            $logger->info('listId: '.json_encode($listId));
            $logger->info('listColumn: '.json_encode($listColumn));
            $logger->info('format: '.$format);
            $logger->info('xpsearch: '.json_encode($xpsearch));
            $logger->info('fctCall: '.$fctCall);
            $logger->info('cardview: '.json_encode($cardview));

            $logger->info('FILTER_STATUS: '.json_encode(IDPConstants::$FILTER_STATUS));
        }

        $printTableCommon = new IDPPrintTableCommon();

        $colvis = $printTableCommon->makeTitleColumn( $listColumn, $cardview, $logger );
        $titleColumn = $colvis['title'];
        $columnVisible = $colvis['visible'];

        $servicesAllowed = $printTableCommon->makeServiceAllowed( $fctCall, $bsUserSession->getUserServices() );

        $listUAS = $printTableCommon->getListUA( $this->getDoctrine(), $bsUserSession->getUserServices(), $listId, $xpsearch, $servicesAllowed, $fctCall, $columnVisible, $logger );

        $pdfOutput = $printTableCommon->makePDFStream( $cardview, $listUAS, $titleColumn, $logger);

        $response = new Response( $pdfOutput );
        $response->headers->set('Content-Type', 'application/pdf');
        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            'archivestable.pdf'
        );
        $response->headers->set('Content-Disposition', $disposition);
        $response->headers->set('Cache-Control', 'private, max-age=0, must-revalidate');
        $response->headers->set('Pragma', 'public');

        //return null;
        return $response;
    }


}

