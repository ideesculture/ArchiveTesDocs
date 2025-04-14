<?php
namespace bs\IDP\ArchiveBundle\Common;

use \fpdf\FPDF;
use \fpdf2file\FPDF2File;

use bs\Core\UsersBundle\IDPUsersFile;

class IDPPrintTableCommon
{


    // ---------------------------------------------------------------------------------------------------------
    // Constants
    // Global page for Table print definition, height, width and margins
    // TP = Table Page
    const TP_PAGE_HEIGHT          = 210;
    const TP_PAGE_WIDTH           = 297;
    const TP_TOP_MARGIN           = 13;
    const TP_LEFT_MARGIN          = 12;
    const TP_BOTTOM_MARGIN        = 13;
    const TP_RIGHT_MARGIN         = 12;
    const TP_DEFAULT_FONT         = 'OpenSans';

    // Table format to print
    const TP_FORMAT_ARRAY           = 0;
    const TP_FORMAT_CARDVIEW        = 1;
    // Table max columns accepted
    const TP_MAX_COLUMN             = 8;        // Only in array mode, not in cardview
    const TP_MAX_STR_LENGTH         = 100;
    // Wrap mode
    const TP_WRAP_ON_SPACE          = 0;
    const TP_WRAP_STRICT            = 1;
    // Alignement
    const TP_H_ALIGN_LEFT           = 0;
    const TP_H_ALIGN_CENTER         = 1;
    const TP_H_ALIGN_RIGHT          = 2;
    const TP_V_ALIGN_TOP            = 0;
    const TP_V_ALIGN_MIDDLE         = 1;
    const TP_V_ALIGN_BOTTOM         = 2;
    // Padding
    const TP_ARRAY_TEXT_PADDING     = 1;
    const TP_ARRAY_TITLE_SIZE       = 10;
    const TP_ARRAY_TITLE_MAX_LINES  = 3;
    const TP_ARRAY_LINE_MAX_LINES   = 3;
    const TP_ARRAY_NO_MAX_LINES     = 999;
    const TP_ARRAY_LINE_SIZE        = 10;

    const TP_CARD_TITLE_SIZE        = 10;
    const TP_CARD_LINE_SIZE         = 10;
    const TP_CARD_TEXT_PADDING      = 1;

    public function makeTitleColumn( $listColumn, $cardview, $logger){
        $titleColumn = [];
        $columnVisible = [];
        $nbColumn = 0;
        foreach( $listColumn as $column )
            if( $cardview || $nbColumn < self::TP_MAX_COLUMN ) {
                $titleColumn[] = utf8_decode($column[1]);
                $columnVisible[] = $column[0];
                $nbColumn++;
            }
        if( $logger ) {
            $logger->info(' - Columns - ');
            $logger->info('$titleColumn: ' . json_encode($titleColumn));
            $logger->info('$columnVisible: ' . json_encode($columnVisible));
            $logger->info('$nbColumn: ' . json_encode($nbColumn));
        }

        $ret = [];
        $ret['title'] = $titleColumn;
        $ret['visible'] = $columnVisible;

        return $ret;
    }

    public function makeServiceAllowed( $fctCall, $userServices ){
        if( $fctCall == 2 ) {
            $servicesAllowed = array();
            foreach ($userServices as $userService)
                array_push($servicesAllowed, $userService->getService()->getId());
        } else
            $servicesAllowed = null;

        return $servicesAllowed;
    }

    public function getListUA( $doctrine, $userServices, $listId, $xpsearch, $servicesAllowed, $fctCall, $columnVisible, $logger ){
        $archives = null;
        $archives = $doctrine->getRepository( 'bsIDPArchiveBundle:IDPArchive' )
            ->getPrintArchives( $userServices, $listId, $xpsearch, $servicesAllowed, $fctCall, $logger );
        if( $logger ) $logger->info( json_encode($archives) );

        // Generate array for printing (ie remove unwanted columns and only put text, not object)
        $listUAS = null;
        if( $archives ){
            // Retreive linked table objects
            $serviceNames = $doctrine->getRepository('bsIDPBackofficeBundle:IDPServices')
                ->getAllIndexedOnID();
            $legalentities = $doctrine->getRepository('bsIDPBackofficeBundle:IDPLegalEntities')
                ->getAllIndexedOnID();
            $documentnatures = $doctrine->getRepository('bsIDPBackofficeBundle:IDPDocumentNatures')
                ->getAllIndexedOnID();
            $documenttypes = $doctrine->getRepository('bsIDPBackofficeBundle:IDPDocumentTypes')
                ->getAllIndexedOnID();
            $documentdescription1 = $doctrine->getRepository('bsIDPBackofficeBundle:IDPDescriptions1')
                ->getAllIndexedOnID();
            $documentdescription2 = $doctrine->getRepository('bsIDPBackofficeBundle:IDPDescriptions2')
                ->getAllIndexedOnID();
            $documentbudgetcodes = $doctrine->getRepository('bsIDPBackofficeBundle:IDPBudgetCodes')
                ->getAllIndexedOnID();
            $providers = $doctrine->getRepository('bsIDPBackofficeBundle:IDPProviders')
                ->getAllIndexedOnID();
            $status = $doctrine->getRepository('bsIDPArchiveBundle:IDPArchivesStatus')
                ->getAllIndexedOnID();
            $localizations = $doctrine->getRepository('bsIDPBackofficeBundle:IDPLocalizations')
                ->getAllIndexedOnID();
            $deliverAddresses = $doctrine->getRepository('bsIDPBackofficeBundle:IDPDeliverAddress')
                ->getAllIndexedOnID();

            // Parse each one to make JSON array
            foreach( $archives as $dtaElem )
            {
                if( $logger ) {
                    $logger->info('ua: '.json_encode($dtaElem));
                }

                $service_id = array_key_exists('service_id',$dtaElem)?$dtaElem['service_id']:'-1';
                $legalentity_id = array_key_exists('legalentity_id',$dtaElem)?$dtaElem['legalentity_id']:'-1';
                $budgetcode_id = array_key_exists('budgetcode_id',$dtaElem)?$dtaElem['budgetcode_id']:'-1';
                $documentnature_id = array_key_exists('documentnature_id',$dtaElem)?$dtaElem['documentnature_id']:'-1';
                $documenttype_id = array_key_exists('documenttype_id',$dtaElem)?$dtaElem['documenttype_id']:'-1';
                $description1_id = array_key_exists('description1_id',$dtaElem)?$dtaElem['description1_id']:'-1';
                $description2_id = array_key_exists('description2_id',$dtaElem)?$dtaElem['description2_id']:'-1';
                $provider_id = array_key_exists('provider_id',$dtaElem)?$dtaElem['provider_id']:'-1';
                $localization_id = array_key_exists( 'localization_id',$dtaElem)?$dtaElem['localization_id']:'-1';
                $old_localization_id = array_key_exists('old_localization_id',$dtaElem)?$dtaElem['old_localization_id']:'-1';
                $precision_address_id = array_key_exists('precision_address_id',$dtaElem)?$dtaElem['precision_address_id']:'-1';

                $elemLongStatus = array_key_exists('status_id',$dtaElem)?$status[intval($dtaElem['status_id'])]['longname']:'';

                $uaLine = array( );
                foreach( $columnVisible as $column ) {
                    if( $logger ) {
                        $logger->info('column: '.$column);
                    }

                    if( $column == 'name' )
                        $uaLine[] = empty($dtaElem['name']) ? '-' : $this->convertText( $dtaElem['name'] );
                    if( $column == 'status' )
                        $uaLine[] = $elemLongStatus ;
                    if( $column == 'ordernumber' )
                        $uaLine[] = empty($dtaElem['ordernumber']) ? '-' : $dtaElem['ordernumber'];
                    if( $column == 'closureyear' )
                        $uaLine[] = is_null($dtaElem['closureyear']) ? '-' : $dtaElem['closureyear'];
                    if( $column == 'destructionyear' )
                        $uaLine[] = is_null($dtaElem['destructionyear']) ? '-' : $dtaElem['destructionyear'];
                    if( $column == 'limitnummin' )
                        $uaLine[] = is_null($dtaElem['limitnummin']) ? '-' : $dtaElem['limitnummin'];
                    if( $column == 'limitnummax' )
                        $uaLine[] = is_null($dtaElem['limitnummax']) ? '-' : $dtaElem['limitnummax'];
                    if( $column == 'limitdatemin' )
                        $uaLine[] = is_null($dtaElem['limitdatemin']) ? '-' : $dtaElem['limitdatemin']->format('d/m/Y');
                    if( $column == 'limitdatemax' )
                        $uaLine[] = is_null($dtaElem['limitdatemax']) ? '-' : $dtaElem['limitdatemax']->format('d/m/Y');
                    if( $column == 'limitalphamin' )
                        $uaLine[] = empty($dtaElem['limitalphamin']) ? '-' : $dtaElem['limitalphamin'];
                    if( $column == 'limitalphamax' )
                        $uaLine[] = empty($dtaElem['limitalphamax']) ? '-' : $dtaElem['limitalphamax'];
                    if( $column == 'limitalphanummin' )
                        $uaLine[] = empty($dtaElem['limitalphanummin']) ? '-' : $dtaElem['limitalphanummin'];
                    if( $column == 'limitalphanummax' )
                        $uaLine[] = empty($dtaElem['limitalphanummax']) ? '-' : $dtaElem['limitalphanummax'];
                    if( $column == 'service' )
                        $uaLine[] = array_key_exists('service_id', $dtaElem) ?
                            array_key_exists( intval($dtaElem['service_id']), $serviceNames) ? $serviceNames[intval($dtaElem['service_id'])]['longname'] : '-' : '-';
                    if( $column == 'legalentity' )
                        $uaLine[] = array_key_exists('legalentity_id', $dtaElem) ?
                            array_key_exists( intval($dtaElem['legalentity_id']), $legalentities) ? $legalentities[intval($dtaElem['legalentity_id'])]['longname'] : '-': '-';
                    if( $column == 'documentnature' )
                        $uaLine[] = array_key_exists('documentnature_id', $dtaElem) ?
                            array_key_exists( intval($dtaElem['documentnature_id']), $documentnatures) ? $documentnatures[intval($dtaElem['documentnature_id'])]['longname'] : '-': '-';
                    if( $column == 'documenttype' )
                        $uaLine[] = array_key_exists('documenttype_id', $dtaElem) ?
                            array_key_exists( intval($dtaElem['documenttype_id']), $documenttypes) ? $documenttypes[intval($dtaElem['documenttype_id'])]['longname'] : '-': '-';
                    if( $column == 'description1' )
                        $uaLine[] = array_key_exists('description1_id', $dtaElem) ?
                            array_key_exists( intval($dtaElem['description1_id']), $documentdescription1 ) ? $documentdescription1[intval($dtaElem['description1_id'])]['longname'] : '-': '-';
                    if( $column == 'description2' )
                        $uaLine[] = array_key_exists('description2_id', $dtaElem) ?
                            array_key_exists( intval($dtaElem['description2_id']), $documentdescription2 ) ? $documentdescription2[intval($dtaElem['description2_id'])]['longname'] : '-': '-';
                    if( $column == 'budgetcode' )
                        $uaLine[] = array_key_exists('budgetcode_id', $dtaElem) ?
                            array_key_exists( intval($dtaElem['budgetcode_id']), $documentbudgetcodes ) ? $documentbudgetcodes[intval($dtaElem['budgetcode_id'])]['longname'] : '-': '-';
                    if( $column == 'documentnumber' )
                        $uaLine[] = empty($dtaElem['documentnumber']) ? '-' : $dtaElem['documentnumber'];
                    if( $column == 'boxnumber' )
                        $uaLine[] = empty($dtaElem['boxnumber']) ? '-' : $dtaElem['boxnumber'];
                    if( $column == 'containernumber' )
                        $uaLine[] = empty($dtaElem['containernumber']) ? '-' : $dtaElem['containernumber'];
                    if( $column == 'provider' )
                        $uaLine[] = array_key_exists('provider_id', $dtaElem) ?
                            array_key_exists( intval($dtaElem['provider_id']), $providers ) ? $providers[intval($dtaElem['provider_id'])]['longname'] : '-': '-';
                    if( $column == 'localization' )
                        $uaLine[] = array_key_exists('localization_id', $dtaElem) ?
                            array_key_exists( intval($dtaElem['localization_id']), $localizations ) ? $localizations[intval($dtaElem['localization_id'])]['longname'] : '-': '-';
                    if( $column == 'oldlocalization' )
                        $uaLine[] = array_key_exists('oldlocalization_id', $dtaElem) ?
                            array_key_exists( intval($dtaElem['oldlocalization_id']), $localizations ) ? $localizations[intval($dtaElem['oldlocalization_id'])]['longname'] : '-': '-';
                    if( $column == 'localizationfree' )
                        $uaLine[] = empty($dtaElem['localizationfree']) ? '-' : $dtaElem['localizationfree'];
                    if( $column == 'oldlocalizationfree' )
                        $uaLine[] = empty($dtaElem['oldlocalizationfree']) ? '-' : $dtaElem['oldlocalizationfree'];
                    if( $column == 'precisiondate' )
                        $uaLine[] = is_null($dtaElem['precisiondate']) ? '-' : $dtaElem['precisiondate']->format('d/m/Y');
                    if( $column == 'precisionfloor' )
                        $uaLine[] = empty($dtaElem['precisionfloor']) ? '-' : $dtaElem['precisionfloor'];
                    if( $column == 'precisionoffice' )
                        $uaLine[] = empty($dtaElem['precisionoffice']) ? '-' : $dtaElem['precisionoffice'];
                    if( $column == 'precisionwho' )
                        $uaLine[] = empty($dtaElem['precisionwho']) ? '-' : $dtaElem['precisionwho'];
                    if( $column == 'precisioncomment' )
                        $uaLine[] = empty($dtaElem['precisioncomment']) ? '-' : $dtaElem['precisioncomment'];
                    if( $column == 'precisionaddress' )
                        $uaLine[] = array_key_exists('precisionaddress_id', $dtaElem) ?
                            array_key_exists( intval($dtaElem['precisionaddress_id']), $deliverAddresses ) ? $deliverAddresses[intval($dtaElem['precisionaddress_id'])]['longname'] : '-' : '-';
                }

                if( $logger ) {
                    $logger->info(' - New Line -');
                    $logger->info('$uaLine:'.json_encode($uaLine));
                }
                $listUAS[] = $uaLine;
            }
        } else {
            return null;
        }

        if( $logger ) {
            $logger->info(json_encode($archives));
            $logger->info(json_encode($listUAS));
        }

        return $listUAS;
    }

    // .......................................................................................................
    // .......................................................................................................
    // .......................................................................................................

    // .......................................................................................................
    // This function prints a table in a pdf file
    public function makePDFFile( $cardview, $listUAS, $titleColumn, $userFile ){
        $fullFilename = __DIR__ . '/../../../../../var/tmp/IDPUserFiles/' . $userFile->getFilename();

        $pdf2 = new \FPDF2File( 'L', 'mm', array( self::TP_PAGE_WIDTH, self::TP_PAGE_HEIGHT ) );
        $pdf2->Open( $fullFilename );

        $pdf2->SetMargins( 0, 0, 0 );
        $pdf2->SetAutoPageBreak( false, 0 );

        $pdf2->AddFont( self::TP_DEFAULT_FONT, '', 'OpenSans-Regular.php' );
        $pdf2->AddFont( self::TP_DEFAULT_FONT, 'B', 'OpenSans-Bold.php' );
        $pdf2->AddFont( self::TP_DEFAULT_FONT, 'I', 'OpenSans-Italic.php' );
        $pdf2->AddFont( self::TP_DEFAULT_FONT, 'BI', 'OpenSans-BoldItalic.php' );

//        $pdf2->AliasNbPages();        // Does not work with PDF2FILE

        $pdf2->AddPage( 'L', array( self::TP_PAGE_WIDTH, self::TP_PAGE_HEIGHT ) );

        $pdf2->SetFont( self::TP_DEFAULT_FONT,'B',10);
        $pdf2->SetTextColor( 0, 0, 0 );
        $pdf2->SetDrawColor( 0, 0, 0 );

        if( $cardview ){
            $this->TP_printCardLines( self::TP_TOP_MARGIN, $listUAS, $titleColumn, $pdf2, true, null );

        } else { // Table view
            // En tête
            $y = $this->TP_printArrayTitles(self::TP_TOP_MARGIN, $titleColumn, $pdf2, true, null );
//return null;
            // Lines
            $y = $this->TP_printArrayLines($y, $listUAS, $titleColumn, $pdf2, true, null );
            if ($y < 0)
                return null;
        }

        if ($pdf2->PageNo() > 1)
            $this->TP_PrintFooter( $pdf2, true, null );

        // Generate pdf and send it

        $pdf2->Output();
        return filesize( $fullFilename );
    }

    // .......................................................................................................
    // This function prints a table in a pdf stream
    public function makePDFStream( $cardview, $listUAS, $titleColumn, $logger){
        $pdf = new \FPDF( 'L', 'mm', array( self::TP_PAGE_WIDTH, self::TP_PAGE_HEIGHT ) );
        $pdf->SetMargins( 0, 0, 0 );
        $pdf->SetAutoPageBreak( false, 0 );

        $pdf->AddFont( self::TP_DEFAULT_FONT, '', 'OpenSans-Regular.php' );
        $pdf->AddFont( self::TP_DEFAULT_FONT, 'B', 'OpenSans-Bold.php' );
        $pdf->AddFont( self::TP_DEFAULT_FONT, 'I', 'OpenSans-Italic.php' );
        $pdf->AddFont( self::TP_DEFAULT_FONT, 'BI', 'OpenSans-BoldItalic.php' );

        $pdf->AliasNbPages();

        $pdf->AddPage( 'L', array( self::TP_PAGE_WIDTH, self::TP_PAGE_HEIGHT ) );

        $pdf->SetFont( self::TP_DEFAULT_FONT,'B',10);
        $pdf->SetTextColor( 0, 0, 0 );
        $pdf->SetDrawColor( 0, 0, 0 );

        if( $cardview ){
            $this->TP_printCardLines( self::TP_TOP_MARGIN, $listUAS, $titleColumn, $pdf, false, $logger );

        } else { // Table view
            // En tête
            $y = $this->TP_printArrayTitles(self::TP_TOP_MARGIN, $titleColumn, $pdf, false, $logger );
//return null;
            // Lines
            $y = $this->TP_printArrayLines($y, $listUAS, $titleColumn, $pdf, false, $logger );
            if ($y < 0)
                return null;
        }

        if ($pdf->PageNo() > 1)
            $this->TP_PrintFooter( $pdf, false, $logger );

        // Generate pdf and send it

        return $pdf->Output('S' );
    }

    // .......................................................................................................
    // This function prints the  lines in cardview mode for TP pdf
    // -> $y:       $y position of top of array lines
    // -> $listUAS     :      array of lines
    // -> $titles:             array of titles
    // -> $pdf:             pdf object to work with
    // <- result:           new Y position
    private function TP_printCardLines( $y, $listUAS, $titles, $pdf, $file, $logger ) {
        if(( $titles == null ) || ( $pdf == null ) || ( $listUAS == null ) || ( $y < 0 ) || ( $y > self::TP_PAGE_HEIGHT ) )
            return -1;
        if( !is_array( $titles ) || !is_array( $listUAS ) )
            return -2;

        $n = sizeof( $titles );
        if( $n <= 0 )
            return -3;

        $pdf->SetTextColor( 0, 0, 0 );
        $pdf->SetDrawColor( 0, 0, 0 );

        $tableWidth = self::TP_PAGE_WIDTH-self::TP_LEFT_MARGIN-self::TP_RIGHT_MARGIN;

        $pdf->SetFont( self::TP_DEFAULT_FONT, 'B', self::TP_CARD_TITLE_SIZE );
        $titleColumnWidth = $this->TP_calculateColumnWidthInCardview( $titles, $pdf );
        $textColumnWidth = $tableWidth - $titleColumnWidth;
        $lineHeight = $this->convertPt2mm( self::TP_CARD_TITLE_SIZE ) + 2 * self::TP_CARD_TEXT_PADDING;

        $currentY = $y;

        foreach ( $listUAS as $ua ) {
            $pdf->Line( self::TP_LEFT_MARGIN, $currentY, self::TP_PAGE_WIDTH - self::TP_RIGHT_MARGIN, $currentY );
            for( $i = 0; $i < sizeof($ua); $i++ ){

                $pdf->SetFont( self::TP_DEFAULT_FONT, '', self::TP_CARD_LINE_SIZE );
                $text = utf8_decode( $ua[$i] );
                $textColumnHeight = ( $this->convertPt2mm( self::TP_CARD_LINE_SIZE ) + 2 * self::TP_CARD_TEXT_PADDING ) *
                    $this->calculateStringNbLine( $text, $textColumnWidth, $pdf, self::TP_WRAP_STRICT, self::TP_ARRAY_LINE_MAX_LINES, $logger );
                if( $textColumnHeight <= 0 )
                    $textColumnHeight = $lineHeight;

                if( $currentY + $textColumnHeight > self::TP_PAGE_HEIGHT - self::TP_BOTTOM_MARGIN ){
                    $pdf->Line( self::TP_LEFT_MARGIN, $currentY, self::TP_PAGE_WIDTH - self::TP_RIGHT_MARGIN, $currentY );
                    $this->TP_PrintFooter( $pdf, $file, $logger );
                    $pdf->AddPage( 'L', array( self::TP_PAGE_WIDTH, self::TP_PAGE_HEIGHT ) );
                    $currentY = self::TP_TOP_MARGIN;
                    $pdf->Line( self::TP_LEFT_MARGIN, $currentY, self::TP_PAGE_WIDTH - self::TP_RIGHT_MARGIN, $currentY );
                }

                // Print Column Title
                $pdf->SetFont( self::TP_DEFAULT_FONT, 'B', self::TP_CARD_TITLE_SIZE );
                $text = $titles[$i];
                $pdf->Text( self::TP_LEFT_MARGIN + self::TP_CARD_TEXT_PADDING, $currentY + $lineHeight - self::TP_CARD_TEXT_PADDING, $text );
                // Print Text Column
                $pdf->SetFont( self::TP_DEFAULT_FONT, '', self::TP_CARD_LINE_SIZE );
                $text = utf8_decode( $ua[$i] );
                $this->printMultiLineTextWithWidthConstraint(self::TP_LEFT_MARGIN + $titleColumnWidth + self::TP_CARD_TEXT_PADDING , $currentY, $text,
                    $textColumnWidth, $textColumnHeight, $pdf, self::TP_WRAP_STRICT, self::TP_H_ALIGN_LEFT, self::TP_V_ALIGN_TOP,
                    self::TP_ARRAY_LINE_MAX_LINES, self::TP_CARD_LINE_SIZE, $logger );

                $pdf->Line( self::TP_LEFT_MARGIN, $currentY, self::TP_LEFT_MARGIN, $currentY + $textColumnHeight );
                $pdf->Line( self::TP_PAGE_WIDTH - self::TP_RIGHT_MARGIN, $currentY, self::TP_PAGE_WIDTH - self::TP_RIGHT_MARGIN, $currentY + $textColumnHeight );

                $currentY += $textColumnHeight;
            }
            $pdf->Line( self::TP_LEFT_MARGIN, $currentY, self::TP_PAGE_WIDTH - self::TP_RIGHT_MARGIN, $currentY );
        }
    }
    // .......................................................................................................
    // This function calculates the maximum width needed to print titles
    private function TP_calculateColumnWidthInCardview( $titles, $pdf ){
        $titleWidth = 0;
        foreach ( $titles as $title ){
            $tempTitleWidth = $pdf->getStringWidth( $title );
            if( $tempTitleWidth > $titleWidth )
                $titleWidth = $tempTitleWidth;
        }
        return $titleWidth;
    }
    // .......................................................................................................
    // This function prints the array of lines in TP pdf
    // -> $y:       $y position of top of array lines
    // -> $listUAS     :      array of lines
    // -> $titles:             array of titles
    // -> $pdf:             pdf object to work with
    // <- result:           new Y position
    private function TP_printArrayLines( $y, $listUAS, $titles, $pdf, $file, $logger ){
        if( $logger ) {
            $logger->info('-- TP_printArrayLines --');
            $logger->info(' - $y = '.$y );
            $logger->info(' - $listUAS = '.utf8_decode(json_encode($listUAS)) );
            $logger->info(' - $titles = '.utf8_decode(json_encode($titles)) );
        }
        if(( $titles == null ) || ( $pdf == null ) || ( $listUAS == null ) || ( $y < 0 ) || ( $y > self::TP_PAGE_HEIGHT ) )
            return -1;
        if( !is_array( $titles ) || !is_array( $listUAS ) )
            return -2;

        $n = sizeof( $titles );
        if( $n <= 0 )
            return -3;

        $pdf->SetFont( self::TP_DEFAULT_FONT, '', self::TP_ARRAY_LINE_SIZE );
        $pdf->SetTextColor( 0, 0, 0 );
        $pdf->SetDrawColor( 0, 0, 0 );

        $tableWidth = self::TP_PAGE_WIDTH-self::TP_LEFT_MARGIN-self::TP_RIGHT_MARGIN;
        $cellWidth = $tableWidth / $n;

        $current_y = $y;

        foreach( $listUAS as $ua ){
            if( $logger ) {
                $logger->info('  > New Line with new UA '.utf8_decode(json_encode($ua)) );
            }

            $nbLines = $this->calculateArrayStringNbLines( $ua, $cellWidth - 2 * self::TP_ARRAY_TEXT_PADDING, $pdf, self::TP_WRAP_STRICT, self::TP_ARRAY_LINE_MAX_LINES, $logger );
            $cellHeight = ( $this->convertPt2mm( self::TP_ARRAY_TITLE_SIZE ) + 2 * self::TP_ARRAY_TEXT_PADDING ) * $nbLines;


            if( $logger ) {
                $logger->info('  > $nbLines calculated with calculateArrayStringNbLines = '.$nbLines );
            }


            if( $current_y + $cellHeight > self::TP_PAGE_HEIGHT - self::TP_BOTTOM_MARGIN ){
                $this->TP_PrintFooter( $pdf, $file, $logger );
                $pdf->AddPage( 'L', array( self::TP_PAGE_WIDTH, self::TP_PAGE_HEIGHT ) );
                $current_y = $this->TP_printArrayTitles( self::TP_TOP_MARGIN, $titles, $pdf, $file, $logger );

                $pdf->SetFont( self::TP_DEFAULT_FONT, '', self::TP_ARRAY_LINE_SIZE );
            }

            $pdf->Rect( self::TP_LEFT_MARGIN, $current_y, $tableWidth, $cellHeight, 'D' );

            for( $i = 0; $i < $n; $i++ ) {
                if( $logger ) {
                    $logger->info('  > New Cell : '.$i );
                }

                if( $i > 0 )
                    $pdf->Line(self::TP_LEFT_MARGIN + $i * $cellWidth, $current_y, self::TP_LEFT_MARGIN + $i * $cellWidth, $current_y + $cellHeight);
                if( $i < sizeof( $ua ) ) {
                    $text = utf8_decode( $ua[$i] );
                    $this->printMultiLineTextWithWidthConstraint(self::TP_LEFT_MARGIN + $i * $cellWidth, $current_y, $text,
                        $cellWidth, $cellHeight, $pdf, self::TP_WRAP_STRICT, self::TP_H_ALIGN_LEFT, self::TP_V_ALIGN_TOP,
                        self::TP_ARRAY_LINE_MAX_LINES, self::TP_ARRAY_LINE_SIZE, $logger );
                }
            }

            $current_y += $cellHeight;
        }

        return $current_y;
    }

    // .......................................................................................................
    // This function prints the array title in TP pdf
    // -> $y:       $y position of top of array title
    // -> $titles     :      array of titles
    // -> $pdf:             pdf object to work with
    // <- result:           new Y position
    private function TP_printArrayTitles( $y, $titles, $pdf, $file, $logger ){
        if( $logger ){
            $logger->info('-- TP_printArrayTitles --');
            $logger->info(' - $y = '.$y );
            $logger->info(' - $titles = '.utf8_decode(json_encode($titles)) );
        }
        if(( $titles == null ) || ( $pdf == null ) || ( $y < 0 ) || ( $y > self::TP_PAGE_HEIGHT ) )
            return -1;
        if( !is_array( $titles ) )
            return -2;

        $n = sizeof( $titles );
        if( $n <= 0 )
            return -3;

        $pdf->SetFont( self::TP_DEFAULT_FONT, 'B', self::TP_ARRAY_TITLE_SIZE );
        $pdf->SetTextColor( 0, 0, 0 );
        $pdf->SetDrawColor( 0, 0, 0 );
        $pdf->SetFillColor( 217, 217, 217 );

        $tableWidth = self::TP_PAGE_WIDTH-self::TP_LEFT_MARGIN-self::TP_RIGHT_MARGIN;
        $cellWidth = $tableWidth / $n;
        $cellHeight = ( $this->convertPt2mm( self::TP_ARRAY_TITLE_SIZE ) + 2 * self::TP_ARRAY_TEXT_PADDING ) *
            $this->calculateArrayStringNbLines( $titles, $cellWidth - 2 * self::TP_ARRAY_TEXT_PADDING, $pdf, self::TP_WRAP_ON_SPACE, self::TP_ARRAY_TITLE_MAX_LINES, $logger );

        if( $logger ){
            $logger->info(' > $tableWidth = '.$tableWidth );
            $logger->info(' > $cellWidth = '.$cellWidth );
            $logger->info(' > $cellHeight = '.$cellHeight );
        }

        $pdf->Rect( self::TP_LEFT_MARGIN, self::TP_TOP_MARGIN, $tableWidth, $cellHeight, 'DF' );
        for( $i = 0; $i < $n; $i++ ) {
            if( $i > 0 )
                $pdf->Line(self::TP_LEFT_MARGIN + $i * $cellWidth, self::TP_TOP_MARGIN, self::TP_LEFT_MARGIN + $i * $cellWidth, self::TP_TOP_MARGIN + $cellHeight);
            $text = $titles[$i];
            $this->printMultiLineTextWithWidthConstraint( self::TP_LEFT_MARGIN + $i * $cellWidth, self::TP_TOP_MARGIN, $text,
                $cellWidth, $cellHeight, $pdf, self::TP_WRAP_ON_SPACE, self::TP_H_ALIGN_CENTER, self::TP_V_ALIGN_MIDDLE,
                self::TP_ARRAY_TITLE_MAX_LINES, self::TP_ARRAY_TITLE_SIZE, $logger );
        }

        return $y + $cellHeight;
    }
    // .......................................................................................................
    // This function print a text with a multi-line method, with width and height constraint
    // -> $x:               x coordinate of top left of cell where to print
    // -> $y:               y coordinate of top left of celle where to print
    // -> $string:          text to print
    // -> $max_width:       max width of cell without padding
    // -> $max_height:      max height of cell without padding
    // -> $pdf:             pdf object to work with
    // -> $wrap_mode:       how to wrap text (0 = on space, 1 = strictly)
    // -> $h_align:         how to align horizontaly (0: left, 1: middle, 2: right)
    // -> $v_align:         how to align verticaly
    // -> $max_lines:       maximum of lines allowed
    // -> $font_size:       font size
    // <- output:           < 0 error code / >= 0 ok
    private function printMultiLineTextWithWidthConstraint( $x, $y, $string, $max_width, $max_height, $pdf, $wrap_mode, $h_align, $v_align, $max_lines, $font_size, $logger ){
        if( $logger ) {
            $logger->info('-- printMultiLineTextWithWidthConstraint --');
            /*            $logger->info(' - $x = '.$y );
                        $logger->info(' - $y = '.$y );
                        $logger->info(' - $string = '.$string );
                        $logger->info(' - $max_width = '.$max_width );
                        $logger->info(' - $max_height = '.$max_height );
                        $logger->info(' - $wrap_mode = '.$wrap_mode );
                        $logger->info(' - $h_align = '.$h_align );
                        $logger->info(' - $v_align = '.$v_align );
                        $logger->info(' - $max_lines = '.$max_lines );
                        $logger->info(' - $font_size = '.$font_size );*/
        }
        $nbLines = $this->calculateStringNbLine( $string, $max_width - 2 * self::TP_ARRAY_TEXT_PADDING, $pdf, $wrap_mode, $max_lines, $logger );
        $height = $nbLines * ( $this->convertPt2mm( $font_size ) + 2 * self::TP_ARRAY_TEXT_PADDING );
        $lines = $this->generateLinesArray(  $string, $max_width - 2 * self::TP_ARRAY_TEXT_PADDING, $pdf, $wrap_mode, $max_lines, $logger );

        if( $logger ) {
            $logger->info('  > Calculated $nblines = '.$nbLines);
            $logger->info('  > Calculated $height = '.$height);
            $logger->info('  > Calculated $lines array = '.utf8_decode(json_encode($lines)) );
        }

        if( $lines != null ){
            $current_line_nb = 0;
            foreach ( $lines as $line ){
                switch( $h_align ){
                    case self::TP_H_ALIGN_LEFT:
                        $text_x = $x + self::TP_ARRAY_TEXT_PADDING;
                        break;
                    case self::TP_H_ALIGN_CENTER:
                        $text_x = $x + ( $max_width - $pdf->getStringWidth( $line ) ) / 2;
                        break;
                    case self::TP_H_ALIGN_RIGHT:
                        $text_x = $x + $max_width - self::TP_ARRAY_TEXT_PADDING - $pdf->getStringWidth( $line );
                        break;
                    default:
                        return -1;
                }
                switch( $v_align ){
                    case self::TP_V_ALIGN_TOP:
                        $text_y = $y + ( $current_line_nb + 1 ) * ( $this->convertPt2mm( $font_size ) + 2 * self::TP_ARRAY_TEXT_PADDING ) - self::TP_ARRAY_TEXT_PADDING;
                        break;
                    case self::TP_V_ALIGN_MIDDLE:
                        $text_y = $y + ( $max_height - $height ) / 2 + ( $current_line_nb + 1 ) * ( $this->convertPt2mm( $font_size ) + 2 * self::TP_ARRAY_TEXT_PADDING ) - self::TP_ARRAY_TEXT_PADDING;
                        break;
                    case self::TP_V_ALIGN_BOTTOM:
                        $text_y = $y + $max_height - ( $nbLines - $current_line_nb - 1 ) * ( $this->convertPt2mm( $font_size ) + 2 * self::TP_ARRAY_TEXT_PADDING ) - self::TP_ARRAY_TEXT_PADDING;
                        break;
                    default:
                        return -2;
                }

                $pdf->Text( $text_x, $text_y, $line );
                $current_line_nb++;
            }
        }
    }
    // .......................................................................................................
    // This function calculate the number of lines needed to print the widest title
    // -> $titles:          array ot titles
    // -> $max_width:       max width allowed for one cell
    // -> $pdf:             pdf object to work with
    // -> $wrap_mode:       how to wrap text (0 = on space, 1 = strict )
    // -> $max_lines:       number of lines not to overrsult
    // <- result:           < 0 error / >= 0 number of lines needed
    private function calculateArrayStringNbLines( $titles, $max_width, $pdf, $wrap_mode, $max_lines, $logger ){
        if( $logger ){
            $logger->info('-- calculateArrayStringNbLines --');
            $logger->info(' - $titles = '.json_encode($titles) );
            $logger->info(' - $max_width = '.$max_width );
            $logger->info(' - $wrap_mode = '.$wrap_mode );
            $logger->info(' - $max_lines = '.$max_lines );
        }
        if(( $titles == null ) || ( $pdf == null ) || ( $max_width <= 0 ) || ( $max_lines < 0 ))
            return -1;
        if( $max_lines == 0 )
            return 0;

        $nbLines = 0;

        $i = 0;
        switch( $wrap_mode ){
            case self::TP_WRAP_ON_SPACE:
            case self::TP_WRAP_STRICT:
                foreach ( $titles as $title ){
                    $tempNbLines = $this->calculateStringNbLine( $title, $max_width, $pdf, $wrap_mode, $max_lines, $logger );
                    if( $tempNbLines > $nbLines )
                        $nbLines = $tempNbLines;
                    if( $logger ) {
                        $logger->info(' > $nbLines = '.$nbLines);
                    }
                }
                return ( $nbLines > $max_lines ? $max_lines: $nbLines );
                break;
            default:
                return -3;
                break;
        }
    }
    // .......................................................................................................
    // This function generate the array of lines to print with a text constraint
    // -> $string:          text to print
    // -> $max_width:       max width allowed for one cell
    // -> $pdf:             pdf object to work with
    // -> $wrap_mode:       how to wrap text (0 = on space, 1 = strict )
    // -> $max_lines:       number of lines not to overrsult
    // <- result:           null=error / [] lines needed
    private function generateLinesArray( $string, $max_width, $pdf, $wrap_mode, $max_lines, $logger ){
        if( $logger ){
            $logger->info( '-- generateLinesArray --' );
            $logger->info( ' - $string = '.$string );
            $logger->info( ' - $max_width = '.$max_width );
            $logger->info( ' - $wrap_mode = '.$wrap_mode );
            $logger->info( ' - $max_lines = '.$max_lines );
        }
        if(( $pdf == null ) || ( $max_width <= 0 ) || ( $max_lines < 0 ))
            return null;
        if(( $max_lines == 0 )||( $string == null ))
            return null;

        $lines = [];

        if( $logger ) $logger->info( ' > width = '.$pdf->getStringWidth( $string ) );

        if( $pdf->getStringWidth( $string ) <= $max_width ) {
            $lines[] = $string;
        } else {
            $tempText = '';
            $nbLines = 0;
            switch ($wrap_mode) {
                case self::TP_WRAP_ON_SPACE:
                    $titleWraped = explode(' ', $string);
                    $spaceWidth = $pdf->getStringWidth(' ');
                    $tempWidth = 0;
                    foreach ($titleWraped as $singleWord) {
                        $tempWidth += ($tempWidth > 0 ? $spaceWidth : 0) + $pdf->getStringWidth($singleWord);
                        if ($tempWidth > $max_width) {
                            if ($nbLines < $max_lines)
                                $lines[] = $tempText;
                            $nbLines++;
                            $tempText = $singleWord;
                            $tempWidth = $pdf->getStringWidth($singleWord); // Put the word on the next line
                        } else
                            $tempText .= (strlen($tempText) > 0 ? ' ' : '') . $singleWord;
                    }
                    if (strlen($tempText) > 0)
                        if ($nbLines < $max_lines)
                            $lines[] = $tempText;
                    break;
                case self::TP_WRAP_STRICT:
                    $tempWidth = 0;
                    for ($i = 0; $i < strlen($string); $i++) {
                        $tempWidth += $pdf->getStringWidth($string[$i]);
                        if ($tempWidth > $max_width) {
                            if ($nbLines < $max_lines)
                                $lines[] = $tempText;
                            $nbLines++;
                            if ($string[$i] != ' ') {
                                $tempWidth = $pdf->getStringWidth($string[$i]);
                                $tempText = $string[$i];
                            } else { // remove space on begin of new line
                                $tempWidth = 0;
                                $tempText = '';
                            }
                        } else
                            $tempText .= $string[$i];
                    }
                    if( strlen($tempText) > 0 )
                        if( $nbLines < $max_lines )
                            $lines[] = $tempText;
                    break;
                default:
                    return -3;
                    break;
            }
        }
        return $lines;
    }
    // .......................................................................................................
    // This function calculate the number of lines needed to print a text
    // -> $string:          text to print
    // -> $max_width:       max width allowed for one cell
    // -> $pdf:             pdf object to work with
    // -> $wrap_mode:       how to wrap text (0 = on space, 1 = strict )
    // -> $max_lines:       number of lines not to overrsult
    // <- result:           < 0 error / >= 0 number of lines needed
    private function calculateStringNbLine( $string, $max_width, $pdf, $wrap_mode, $max_lines, $logger ){
        if( $logger ){
            $logger->info('-- calculateStringNbLine --');
            $logger->debug(' - $string = '. $string );
            $logger->debug(' - $max_width = '.$max_width );
            $logger->debug(' - $wrap_mode = '.$wrap_mode );
            $logger->debug(' - $max_lines = '.$max_lines );
        }
        if(( $pdf == null ) || ( $max_width <= 0 ) || ( $max_lines < 0 ))
            return -1;
        if(( $max_lines == 0 )||( $string == null ))
            return 0;

        $nbLines = 1;
        if( $pdf->getStringWidth( $string ) <= $max_width ) {
            if( $logger ) $logger->debug( ' > length: '.$pdf->getStringWidth( $string ).' less than $max_width ==> return 1' );
            return 1;
        }
        switch( $wrap_mode ){
            case self::TP_WRAP_ON_SPACE:
                $titleWraped = explode ( ' ', $string );
                $spaceWidth = $pdf->getStringWidth( ' ' );
                $tempWidth = 0;
                $i = 0;
                if( $logger ) {
                    $logger->debug(' > TP_WRAP_ON_SPACE mode' );
                    $logger->debug(' > $titleWraped = '.json_encode($titleWraped) );
                }
                foreach( $titleWraped as $singleWord ) {
                    $tempWidth += ($tempWidth > 0 ? $spaceWidth : 0) + $pdf->getStringWidth($singleWord);
                    if( $tempWidth > $max_width ){
                        $nbLines++;
                        $tempWidth = $pdf->getStringWidth($singleWord); // Put the word on the next line
                    }
                    if( $logger ) {
                        $logger->debug(' > Calculate new width with word :' . $singleWord);
                        $logger->debug(' > new_width: '.$tempWidth. ' --> nbLines: '.$nbLines );
                    }
                }
                //$nbLines++;
                break;
            case self::TP_WRAP_STRICT:
                if( $logger ) {
                    $logger->debug(' > TP_WRAP_STRICT mode' );
                }
                $tempWidth = 0;
                for( $i = 0; $i < strlen( $string ); $i++ ){
                    $tempWidth += $pdf->getStringWidth( $string[$i] );
                    if( $tempWidth > $max_width ){
                        $nbLines++;
                        $tempWidth = $pdf->getStringWidth( $string[$i] );
                    }
                    if( $logger ) {
                        $logger->debug(' > Calculate new width with letter :' . $string[$i] );
                        $logger->debug(' > new_width: '.$tempWidth. ' --> nbLines: '.$nbLines );
                    }
                }
                //$nbLines++;
                break;
            default:
                return -3;
                break;
        }
        return( $nbLines > $max_lines ? $max_lines : $nbLines );
    }
    // .......................................................................................................
    // This function calculate the offset to center the text in the cell horizontaly
    // If text size > cell width return 0
    // -> $cellWidth:       width of cell to center in
    // -> $text     :       text to be centered
    // -> $pdf:             pdf object to work with
    // <- result:           offset X
    private function calculateOffsetForCenterX( $cellWidth, $text, $pdf ){
        $textWidth = $pdf->getStringWidth( $text );
        if( $textWidth >= $cellWidth )
            return 0;

        return ( $cellWidth - $textWidth ) / 2;
    }
    // .......................................................................................................
    // This function calculate the offset to center the text in the cell verticaly
    // If text height > cell height return 0
    // -> $cellHeight:       height of cell to center in
    // -> $textsize     :     current text size
    // <- result:           offset X
    private function calculateOffsetForCenterY( $cellHeight, $textsize ){
        $textHeight = $this->convertPt2mm( $textsize );
        if( $textHeight >= $cellHeight )
            return 0;

        return ( $cellHeight - $textHeight ) / 2;
    }
    // .......................................................................................................
    // This function adds a new page to the pdf
    private function TP_AddPage( $pdf ){
        $this->printFooter( $pdf );
        $pdf->AddPage( 'L', array( self::TP_PAGE_WIDTH, self::TP_PAGE_HEIGHT ) );
    }
    // .......................................................................................................
    // This function print the footer to the pdf
    private function TP_PrintFooter( $pdf, $file, $logger ){
        $pdf->setFont( self::TP_DEFAULT_FONT, '', 10 );
        if( $file )
            $text = 'Page ' . $pdf->PageNo();
        else
            $text = 'Page ' . $pdf->PageNo() . '/{nb}';
        $size = $pdf->getStringWidth( $text );
        $pdf->Text( self::TP_PAGE_WIDTH - self::TP_RIGHT_MARGIN - $size,
            self::TP_PAGE_HEIGHT - self::TP_BOTTOM_MARGIN + $this->convertPt2mm(10), $text );
    }

    //---------------------------------------------------------------------------------------------------------
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
        $pdf->SetFont( self::TP_DEFAULT_FONT,'',11 );
        $pdf->SetTextColor( 0, 0, 0 );
    }


    //========================================================================================================
    // Usefull function
    // TODO: share everywhere where used

    // .......................................................................................................
    // Convert Text
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

    // .......................................................................................................
    // Convert a point size (font size) into mm (used in pdf position)
    private function convertPt2mm( $pt ){
        $mm = ( $pt / 72 ) * 25.4;
        return intval( $mm );
    }




    // .......................................................................................................
    // .......................................................................................................
    public function preciseWhereAmI( $whereAmI, $XPSearch ){
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
    public function computeCallFromWhereAmI( $whereAmI ){
        if( $whereAmI ==  1 ) return 1;
        elseif( $whereAmI < 7 ) return 2;
        else return 3;
    }

    // ================================================================================================================
    // Strange but json_encode in javascript plus json_decode in php after a getArgument doesn't work, so I made my own decode function
    // [[ , ];[ , ]] ou [ ; ; ; ]
    public function myParseToArray( $string ){
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