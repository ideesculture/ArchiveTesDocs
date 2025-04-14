<?php

namespace bs\Core\UsersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Stream;

use bs\Core\UsersBundle\SessionMng\bsCoreUserSession;
use bs\Core\TranslationBundle\Translation\bsTranslationIDsCommon;


class UserSpaceUserFileController extends Controller
{
    const USERFILES_BASEDIR = __DIR__ . '/../../../../../var/tmp/IDPUserFiles/';
    const CHUNK_SIZE = 1024 * 1024;

    private function jsonResponse($return, $code = 200)
    {
        $response = new Response(json_encode($return), $code);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function viewMainScreenAction(Request $request)
    {

        $bsUserSession = new bsCoreUserSession($request->getSession(), $this->getDoctrine());
        if (!$bsUserSession->isUserLogged())
            return $this->redirect($this->generateUrl('bs_core_user_login'));

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses')->findOneBy(array('id' => 1));
        if ($globalStatuses && $globalStatuses->getReconciliationInProgress() > 0) {
            return $this->jsonResponse(array('message' => 'Acces interdit, rapprochement des stocks en cours !'), 403);
        }

        $userScale = $bsUserSession->getUserScale();

        $userFilesResume = $this->getDoctrine()
            ->getRepository('bsCoreUsersBundle:IDPUserFiles')
            ->getUserFilesResume($bsUserSession->getUserId());
        $userFilesList = $this->getDoctrine()
            ->getRepository('bsCoreUsersBundle:IDPUserFiles')
            ->getAllUserFiles($bsUserSession->getUserId());

        $language = $bsUserSession->getUserExtension()->getLanguage();
        $headTranslations = $this->getDoctrine()
            ->getRepository('bsCoreTranslationBundle:bsTranslation')
            ->getArrayTranslation(bsTranslationIDsCommon::T_HEAD_BASE, $language);

        return $this->render('bsCoreUsersBundle:userSpace:userFileMainScreen.html.twig', array(
            'curruser' => $bsUserSession->getUser(),
            'headTranslations' => $headTranslations,
            'currentMenu' => 0,
            'userscale' => $userScale,
            'userFilesResume' => $userFilesResume,
            'userFilesList' => $userFilesList
        ));
    }

    public function deleteFileAction(Request $request)
    {

        $bsUserSession = new bsCoreUserSession($request->getSession(), $this->getDoctrine());
        if (!$bsUserSession->isUserLogged())
            return $this->redirect($this->generateUrl('bs_core_user_login'));

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses')->findOneBy(array('id' => 1));
        if ($globalStatuses && $globalStatuses->getReconciliationInProgress() > 0) {
            return $this->jsonResponse(array('message' => 'Acces interdit, rapprochement des stocks en cours !'), 403);
        }

        $parameters = $request->query;
        $fileId = null;
        if ($parameters->has('fileid'))
            $fileId = $parameters->get('fileid');

        if (!$fileId) {
            return $this->jsonResponse(array('message' => 'Parameter FileId is missing !'), 400);
        }

        $fileDb = $this->getDoctrine()
            ->getRepository('bsCoreUsersBundle:IDPUserFiles')
            ->findOneBy(['id' => $fileId]);
        if (!$fileDb) {
            return $this->jsonResponse(array('message' => 'Parameter FileId is not valid !'), 410);
        }

        if ($fileDb->getInprogress()) {
            return $this->jsonResponse(array('message' => 'Vous ne pouvez pas supprimer un fichier en cours de création !'), 403);
        }

        if ($fileDb->getUserid() != $bsUserSession->getUserId()) {
            return $this->jsonResponse(array('message' => 'Vous ne pouvez pas supprimer le fichier d\'un autre utilisateur !'), 403);
        }

        $fullName = UserSpaceUserFileController::USERFILES_BASEDIR . $fileDb->getFilename();
        if (!file_exists($fullName)) {
            return $this->jsonResponse(array('message' => 'File not found in directory ! -> ' . $fullName), 404);
        }

        // Remove file
        unlink($fullName);
        // Remove entry in DB
        $em = $this->getDoctrine()->getManager();
        $em->remove($fileDb);
        $em->flush();

        return $this->jsonResponse(array('message' => 'File deleted with success.'), 200);

    }

    public function downloadFileAction(Request $request)
    {
        $logger = $this->container->getParameter('kernel.environment') == 'dev' ?$this->container->get('logger'):null;

        $bsUserSession = new bsCoreUserSession($request->getSession(), $this->getDoctrine());
        if (!$bsUserSession->isUserLogged())
            return $this->redirect($this->generateUrl('bs_core_user_login'));

        // Reconciliation in progress
        $globalStatuses = $this->getDoctrine()->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses')->findOneBy(array('id' => 1));
        if ($globalStatuses && $globalStatuses->getReconciliationInProgress() > 0)
            return $this->render('bsCoreUsersBundle:bsUsers:error.html.twig', array(
                'error_redirect' => 'bs_core_userspace_userfile_viewmainscreen',
                'error_wait_time' => IDPConstants::ERROR_NO_REDIRECTION,
                'error_title' => 'Acces interdit',
                'error_message' => 'Rapprochement des stocks en cours !',
                'error_type' => 99));

        $parameters = $request->query;
        $fileId = null;
        if ($parameters->has('fileid'))
            $fileId = $parameters->get('fileid');

        if( $logger ) $logger->info( '-> FileID asked: '.$fileId );

        if (!$fileId)
            return $this->render('bsCoreUsersBundle:bsUsers:error.html.twig', array(
                'error_redirect' => 'bs_core_userspace_userfile_viewmainscreen',
                'error_wait_time' => IDPConstants::ERROR_NO_REDIRECTION,
                'error_title' => 'Erreur serveur',
                'error_message' => 'Une erreur serveur est survenue, code [E04001] !',
                'error_type' => 99));

        $fileDb = $this->getDoctrine()
            ->getRepository('bsCoreUsersBundle:IDPUserFiles')
            ->findOneBy(['id' => $fileId]);
        if (!$fileDb)
            return $this->render('bsCoreUsersBundle:bsUsers:error.html.twig', array(
                'error_redirect' => 'bs_core_userspace_userfile_viewmainscreen',
                'error_wait_time' => IDPConstants::ERROR_NO_REDIRECTION,
                'error_title' => 'Erreur serveur',
                'error_message' => 'Une erreur serveur est survenue, code [E04002] !',
                'error_type' => 99));

        if( $logger ) $logger->info( '-> UserFile found ' );

        if ($fileDb->getUserid() != $bsUserSession->getUserId()) {
            return $this->render('bsCoreUsersBundle:bsUsers:error.html.twig', array(
                'error_redirect' => 'bs_core_userspace_userfile_viewmainscreen',
                'error_wait_time' => IDPConstants::ERROR_NO_REDIRECTION,
                'error_title' => 'Erreur',
                'error_message' => 'Vous ne pouvez pas télécharger le fichier d\'un autre utilisateur !',
                'error_type' => 99));
        }
        if( $logger ) $logger->info( '-> ok same user ' );

        if ($fileDb->getInprogress())
            return $this->render('bsCoreUsersBundle:bsUsers:error.html.twig', array(
                'error_redirect' => 'bs_core_userspace_userfile_viewmainscreen',
                'error_wait_time' => IDPConstants::ERROR_NO_REDIRECTION,
                'error_title' => 'Erreur',
                'error_message' => 'Vous ne pouvez pas télécharger un fichier en cours de création !',
                'error_type' => 99));

        if( $logger ) $logger->info( '-> ok not in progress ');

        $fullName = UserSpaceUserFileController::USERFILES_BASEDIR . $fileDb->getFilename();
        if (!file_exists($fullName))
            return $this->render('bsCoreUsersBundle:bsUsers:error.html.twig', array(
                'error_redirect' => 'bs_core_userspace_userfile_viewmainscreen',
                'error_wait_time' => IDPConstants::ERROR_NO_REDIRECTION,
                'error_title' => 'Erreur serveur',
                'error_message' => 'Une erreur serveur est survenue, code [E04003] !',
                'error_type' => 99));

        if( $logger ) $logger->info( '-> ok file exists : '. $fullName );

        $fileDb->setNbdownload($fileDb->getNbdownload() + 1);
        $em = $this->getDoctrine()->getManager();
        $em->persist($fileDb);
        $em->flush();

        if( $logger ) $logger->info( '-> UpdateUserFile nbDownload ok ');

        $stream = new Stream($fullName);
        $response = new BinaryFileResponse($stream);

        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $fileDb->getFilename()
        );
        $response->headers->set('Content-Disposition', $disposition);
        $response->headers->set('Cache-Control', 'private, max-age=0, must-revalidate');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Content-Length', filesize($fullName));

        if( $logger ) $logger->info( '-> send response ' );

        return $response;
    }
}