<?php
namespace bs\IDP\ArchiveBundle\Common;

use Symfony\Component\HttpFoundation\Response;

use bs\Core\UsersBundle\IDPUsersFile;
use bs\IDP\BackofficeBundle\Entity\IDPGlobalSettings;

use \xlswriter\XLSXWriter;


class IDPExportTableCommon
{
    const EXPORT_STREAM = 0;
    const EXPORT_FILE = 1;

    public function makeExportFile( $doctrine, $listId, $userServices, $xpfct, $xpsearch, $xpwhat, $xpwhere, $xphow, $xpwith, $xpstate, $filterprovider, $listColumn, $exportType, $streamOrFile, $userFile = null, $output = null ){
        // Construct Query with these parameters
        if( $output ) $output->writeln( "Get Query" );
        $em = $doctrine->getEntityManager();
        $query = $em->getRepository( 'bsIDPArchiveBundle:IDPArchive' )
            ->getExportQuery( $userServices, $xpfct, $xpsearch, $xpwhat, $xpwhere, $xphow, $xpwith, $xpstate, $filterprovider );

        // First get back datas in an iterate object
        $iterableResult = $query->iterate();

        switch( $exportType ) {
            case IDPGlobalSettings::EXPORT_TYPE_IDP:

                // Second open an handle in php memory to write result into
                $handle = fopen('php://memory', 'r+');
                $header = array();

                // Third, write into $handle csv first line and rows iterably
                fputcsv($handle, IDPExportTableCommon::flatenColumnTitle($listColumn));
                while (false != ($row = $iterableResult->next())) {

                    if (($listId == null) || in_array($row[0]->getId(), $listId)) {
                        $rowArray = IDPExportTableCommon::flatenArchiveObject($row[0], $listColumn);

                        fputcsv($handle, $rowArray);
                    }
                    $em->detach($row[0]);
                }

                // Four, send the stream
                rewind($handle);
                $content = stream_get_contents($handle);

                //return null;
            if( $streamOrFile == self::EXPORT_STREAM ) {
                return new Response($content, 200, array(
                    'Content-Type' => 'application/force-download',
                    'Content-Disposition' => 'attachment; filename="export.idp"'));
            } else {
                // save file
                // return ok
            }
                break;
            case IDPGlobalSettings::EXPORT_TYPE_XLS:
                if( $output ) $output->writeln( "Create XLSWrite object" );

                $xlsWriter = new \XLSXWriter();
                $xlsWriter->setAuthor('Archimage');

                $xlsWriter->writeSheetRow('Archimage', IDPExportTableCommon::flatenColumnTitle($listColumn));

                $count = 0;
                while( false != ( $row = $iterableResult->next() )){

                    if (($listId == null) || in_array($row[0]->getId(), $listId)) {
                        $rowArray = IDPExportTableCommon::flatenArchiveObject( $row[0], $listColumn );

                        $xlsWriter->writeSheetRow('Archimage', $rowArray);
                    }

                    $em->detach( $row[0] );

                    $count++;
                    if( $count >= 100 ){
                        $count = 0;
                        if( $output ) $output->write( "." );
                    }
                }

                if( $streamOrFile == self::EXPORT_STREAM ) {
                    $content = $xlsWriter->writeToString();
                    return new Response($content, 200, array(
                        'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'Content-Disposition' => 'attachment; filename="export.xlsx"',
                        'Content-Transfer-Encoding: binary',
                        'Cache-Control: must-revalidate',
                        'Pragma: public'));
                } else {
                    // Save file to disk
                    $fullFilename = __DIR__ . '/../../../../../var/tmp/IDPUserFiles/' . $userFile->getFilename();
                    $xlsWriter->writeToFile($fullFilename);
                    if( $output ) $output->writeln('');
                    if( $output ) $output->writeln($userFile->getFilename().' written to disk !');

                    return filesize( $fullFilename );
                }
                break;
        }

        // TODO: manage better error response
        return 0;
    }

    public function flatenColumnTitle( $visible ){
        $response = array();

        // TODO get back translated names
//		if( empty($visible) || in_array( 'id', $visible ) )
//			array_push( $response, 'id' );
        if( empty($visible) || in_array( 'service', $visible ) )
            array_push( $response, 'Service' );
        if( empty($visible) || in_array( 'ordernumber', $visible ) )
            array_push( $response, 'n° d\'ordre' );
        if( empty($visible) || in_array( 'legalentity', $visible ) )
            array_push( $response, 'Entité légale' );
        if( empty($visible) || in_array( 'name', $visible ) )
            array_push( $response, 'Libellé' );
        if( empty($visible) || in_array( 'budgetcode', $visible ) )
            array_push( $response, 'Code budgétaire' );
        if( empty($visible) || in_array( 'documentnature', $visible ) )
            array_push( $response, 'Activité' );
        if( empty($visible) || in_array( 'documenttype', $visible ) )
            array_push( $response, 'Type de document' );
        if( empty($visible) || in_array( 'description1', $visible ) )
            array_push( $response, 'Descriptif 1' );	// TODO get back settings
        if( empty($visible) || in_array( 'description2', $visible ) )
            array_push( $response, 'Descriptif2' );
        if( empty($visible) || in_array( 'documentnumber', $visible ) )
            array_push( $response, 'N° Dossier' );
        if( empty($visible) || in_array( 'boxnumber', $visible ) )
            array_push( $response, 'N° Boîte' );
        if( empty($visible) || in_array( 'containernumber', $visible ) )
            array_push( $response, 'N° Conteneur' );
        if( empty($visible) || in_array( 'provider', $visible ) )
            array_push( $response, 'Compte prestataire' );
        if( empty($visible) || in_array( 'status', $visible ) )
            array_push( $response, 'Etat' );
        if( empty($visible) || in_array( 'localization', $visible ) )
            array_push( $response, 'Localisation' );
        if( empty($visible) || in_array( 'localizationfree', $visible ) )
            array_push( $response, 'Localisation libre' );
        if( empty($visible) || in_array( 'limitdatemin', $visible ) )
            array_push( $response, 'Limite Date min' );
        if( empty($visible) || in_array( 'limitdatemax', $visible ) )
            array_push( $response, 'Limite Date max' );
        if( empty($visible) || in_array( 'limitnummin', $visible ) )
            array_push( $response, 'Limite Num. min' );
        if( empty($visible) || in_array( 'limitnummax', $visible ) )
            array_push( $response, 'Limite Num. max' );
        if( empty($visible) || in_array( 'limitalphamin', $visible ) )
            array_push( $response, 'Limite Alpha. min' );
        if( empty($visible) || in_array( 'limitalphamax', $visible ) )
            array_push( $response, 'Limite Alpha. max' );
        if( empty($visible) || in_array( 'limitalphanummin', $visible ) )
            array_push( $response, 'Limite Alphanum. min' );
        if( empty($visible) || in_array( 'limitalphanummax', $visible ) )
            array_push( $response, 'Limite Alphanum. max' );
        if( empty($visible) || in_array( 'closureyear', $visible ) )
            array_push( $response, 'Date de clôture' );
        if( empty($visible) || in_array( 'destructionyear', $visible ) )
            array_push( $response, 'Date de destruction' );
        if( empty($visible) || in_array( 'oldlocalization', $visible ) )
            array_push( $response, 'Ancienne localisation' );
        if( empty($visible) || in_array( 'oldlocalizationfree', $visible ) )
            array_push( $response, 'Ancienne localisation libre' );
        if( empty($visible) || in_array( 'precisiondate', $visible ) )
            array_push( $response, 'Date' );
        if( empty($visible) || in_array( 'precisionaddress', $visible ) )
            array_push( $response, 'Adresse' );
        if( empty($visible) || in_array( 'precisionfloor', $visible ) )
            array_push( $response, 'Etage' );
        if( empty($visible) || in_array( 'precisionoffice', $visible ) )
            array_push( $response, 'Bureau' );
        if( empty($visible) || in_array( 'precisionwho', $visible ) )
            array_push( $response, 'Demandeur' );
        if( empty($visible) || in_array( 'precisioncomment', $visible ) )
            array_push( $response, 'Commentaires' );
        if( empty($visible) || in_array( 'unlimited', $visible ) )
            array_push( $response, 'Conservation illimitée' );
        if( empty($visible) || in_array( 'unlimitedcomments', $visible ) )
            array_push( $response, 'Commentaires illimitée' );

        return $response;
    }

    public function flatenArchiveObject( $ao, $visible ){
        $response = array();

//		if( empty($visible) || in_array( 'id', $visible ) )
//			array_push( $response, $ao->getId() );
        if( empty($visible) || in_array( 'service', $visible ) )
            array_push( $response, ($ao->getService()!=null?$ao->getService()->getLongname():' ') );
        if( empty($visible) || in_array( 'ordernumber', $visible ) )
            array_push( $response, ($ao->getOrdernumber()!=null?$ao->getOrdernumber():' ') );
        if( empty($visible) || in_array( 'legalentity', $visible ) )
            array_push( $response, ($ao->getLegalentity()!=null?$ao->getLegalentity()->getLongname():' ') );
        if( empty($visible) || in_array( 'name', $visible ) )
            array_push( $response, preg_replace('/[\r\n]+/',' ', $ao->getName()) );          // Remove carriage return and new line
        if( empty($visible) || in_array( 'budgetcode', $visible ) )
            array_push( $response, ($ao->getBudgetcode()!=null?$ao->getBudgetcode()->getLongname():' ') );
        if( empty($visible) || in_array( 'documentnature', $visible ) )
            array_push( $response, ($ao->getDocumentnature()!=null?$ao->getDocumentnature()->getLongname():' ') );
        if( empty($visible) || in_array( 'documenttype', $visible ) )
            array_push( $response, ($ao->getDocumenttype()!=null?$ao->getDocumenttype()->getLongname():' ') );
        if( empty($visible) || in_array( 'description1', $visible ) )
            array_push( $response, ($ao->getDescription1()!=null?$ao->getDescription1()->getLongname():' ') );
        if( empty($visible) || in_array( 'description2', $visible ) )
            array_push( $response, ($ao->getDescription2()!=null?$ao->getDescription2()->getLongname():' ') );
        if( empty($visible) || in_array( 'documentnumber', $visible ) )
            array_push( $response, ($ao->getDocumentnumber()!=null?$ao->getDocumentnumber():' ') );
        if( empty($visible) || in_array( 'boxnumber', $visible ) )
            array_push( $response, ($ao->getBoxnumber()!=null?$ao->getBoxnumber():' ') );
        if( empty($visible) || in_array( 'containernumber', $visible ) )
            array_push( $response, ($ao->getContainernumber()!=null?$ao->getContainernumber():' ') );
        if( empty($visible) || in_array( 'provider', $visible ) )
            array_push( $response, ($ao->getProvider()!=null?$ao->getProvider()->getLongname():' ') );
        if( empty($visible) || in_array( 'status', $visible ) )
            array_push( $response, ($ao->getStatus()!=null?$ao->getStatus()->getLongname():' ') );
        if( empty($visible) || in_array( 'localization', $visible ) )
            array_push( $response, ($ao->getLocalization()!=null?$ao->getLocalization()->getLongname():' ') );
        if( empty($visible) || in_array( 'localizationfree', $visible ) )
            array_push( $response, ($ao->getLocalizationfree()!=null?$ao->getLocalizationfree():' ') );
        if( empty($visible) || in_array( 'limitdatemin', $visible ) )
            array_push( $response, ($ao->getLimitdatemin()!=null?$ao->getLimitdatemin()->format('d/m/Y'):' ') );
        if( empty($visible) || in_array( 'limitdatemax', $visible ) )
            array_push( $response, ($ao->getLimitdatemax()!=null?$ao->getLimitdatemax()->format('d/m/Y'):' ') );
        if( empty($visible) || in_array( 'limitnummin', $visible ) )
            array_push( $response, ($ao->getLimitnummin()!=null?$ao->getLimitnummin():' ') );
        if( empty($visible) || in_array( 'limitnummax', $visible ) )
            array_push( $response, ($ao->getLimitnummax()!=null?$ao->getLimitnummax():' ') );
        if( empty($visible) || in_array( 'limitalphamin', $visible ) )
            array_push( $response, ($ao->getLimitalphamin()!=null?$ao->getLimitalphamin():' ') );
        if( empty($visible) || in_array( 'limitalphamax', $visible ) )
            array_push( $response, ($ao->getLimitalphamax()!=null?$ao->getLimitalphamax():' ') );
        if( empty($visible) || in_array( 'limitalphanummin', $visible ) )
            array_push( $response, ($ao->getLimitalphanummin()!=null?$ao->getLimitalphanummin():' ') );
        if( empty($visible) || in_array( 'limitalphanummax', $visible ) )
            array_push( $response, ($ao->getLimitalphanummax()!=null?$ao->getLimitalphanummax():' ') );
        if( empty($visible) || in_array( 'closureyear', $visible ) )
            array_push( $response, ($ao->getClosureyear()!=null?$ao->getClosureyear():' ') );
        if( empty($visible) || in_array( 'destructionyear', $visible ) )
            array_push( $response, ($ao->getDestructionyear()!=null?$ao->getDestructionyear():' ') );
        if( empty($visible) || in_array( 'oldlocalization', $visible ) )
            array_push( $response, ($ao->getOldlocalization()!=null?$ao->getOldlocalization()->getLongname():' ') );
        if( empty($visible) || in_array( 'oldlocalizationfree', $visible ) )
            array_push( $response, ($ao->getOldlocalizationfree()!=null?$ao->getOldlocalizationfree():' ') );
        if( empty($visible) || in_array( 'precisiondate', $visible ) )
            array_push( $response, ($ao->getPrecisiondate()!=null?$ao->getPrecisiondate()->format('d/m/Y'):' ') );
        if( empty($visible) || in_array( 'precisionaddress', $visible ) )
            array_push( $response, ($ao->getPreicisionwhere()!=null?$ao->getPreicisionwhere()->getLongname():' ') );
        if( empty($visible) || in_array( 'precisionfloor', $visible ) )
            array_push( $response, ($ao->getPrecisionfloor()!=null?$ao->getPrecisionfloor():' ') );
        if( empty($visible) || in_array( 'precisionoffice', $visible ) )
            array_push( $response, ($ao->getPrecisionoffice()!=null?$ao->getPrecisionoffice():' ') );
        if( empty($visible) || in_array( 'precisionwho', $visible ) )
            array_push( $response, ($ao->getPrecisionwho()!=null?$ao->getPrecisionwho():' ') );
        if( empty($visible) || in_array( 'precisioncomment', $visible ) )
            array_push( $response, ($ao->getPrecisioncomment()!=null?$ao->getPrecisioncomment():' ') );
        if( empty($visible) || in_array( 'unlimited', $visible ) )
            array_push( $response, ($ao->getUnlimited()!=null?$ao->getUnlimited()==1?'actif':'inactif':'inactif') );
        if( empty($visible) || in_array( 'unlimitedcomments', $visible ) )
            array_push( $response, ($ao->getUnlimited()!=null?$ao->getUnlimited()==1?$ao->getUnlimitedcomments()!=null?$ao->getUnlimitedcomments():' ':' ':' ') );

        return $response;
    }

}