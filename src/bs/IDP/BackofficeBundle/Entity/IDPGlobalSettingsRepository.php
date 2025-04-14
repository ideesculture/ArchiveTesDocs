<?php

namespace bs\IDP\BackofficeBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

/**
 * IDPGlobalSettingsRepository.php
 *
 */
class IDPGlobalSettingsRepository extends EntityRepository
{
    public function getPasswordSettings( ){
        $db_min_length = $this->findOneBy( [ 'name' => IDPGlobalSettings::PASSWORD_MIN_LENGTH ] );
        $min_length = IDPGlobalSettings::DEFAULT_PASSWORD_MIN_LENGTH;
        if( $db_min_length ) $min_length = $db_min_length->getIntValue( );

        $db_complexity = $this->findOneBy( [ 'name' => IDPGlobalSettings::PASSWORD_COMPLEXITY ] );
        $complexity = IDPGlobalSettings::DEFAULT_PASSWORD_COMPLEXITY;
        if( $db_complexity ) $complexity = $db_complexity->getIntValue( );

        return( [
            IDPGlobalSettings::PASSWORD_MIN_LENGTH => $min_length,
            IDPGlobalSettings::PASSWORD_COMPLEXITY => $complexity
        ] );
    }

    public function getExportType( ){
        $db_export_type = $this->findOneBy( [ 'name' => IDPGlobalSettings::EXPORT_TYPE ] );
        $export_type = IDPGlobalSettings::DEFAULT_EXPORT_TYPE;
        if( $db_export_type ) $export_type = $db_export_type->getIntValue( );

        return $export_type;
    }
}
