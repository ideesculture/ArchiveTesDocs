<?php

namespace bs\IDP\BackofficeBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

/**
 * IDPMainSettingsRepository.php
 *
 */
class IDPMainSettingsRepository extends EntityRepository
{
    public function getMainSetting( $settingName ){
        $db_value = $this->findOneBy( [ 'name' => $settingName ] );
        $all_at_once = IDPMainSettings::MAIN_SETTINGS_DEFAULT_VALUES[ $settingName ];
        if( $db_value ) $all_at_once = $db_value->getIntValue( );

        return( [
            IDPMainSettings::ALL_SERVICES_CONFIGURED_AT_ONCE => $all_at_once != 0 ? true : false
        ] );
    }
}
