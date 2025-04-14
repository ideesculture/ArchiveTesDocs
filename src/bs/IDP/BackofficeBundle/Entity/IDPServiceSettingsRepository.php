<?php

namespace bs\IDP\BackofficeBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

/**
 * IDPServiceSettingsRepository
 *
 */
class IDPServiceSettingsRepository extends EntityRepository
{
	public function getArraySettings( ){
		$query = $this->getEntityManager()
        	->createQuery("SELECT s FROM bsIDPBackofficeBundle:IDPServiceSettings s WHERE s.id = 1")
			->setHint(Query::HINT_INCLUDE_META_COLUMNS, true);
		return $query->getArrayResult();
	}

    public function arrayFindOneByService( $serviceId ){
        $query = $this->getEntityManager()
            ->createQuery( "SELECT s FROM bsIDPBackofficeBundle:IDPServiceSettings s WHERE s.service_id = $serviceId" )
            ->setHint( Query::HINT_INCLUDE_META_COLUMNS, true );
        return $query->getArrayResult();
    }

    // Get back settings from multiple services
    public function getCommonSettings( $userSession ){
        if( !$userSession ) return null;

        $bFirst = true;
        $commonSettings = array();
        $defaultSettings = $this->arrayFindOneByService( 0 ); // Get defaultSettings
        if( !$defaultSettings ) return null;
        else $defaultSettings = $defaultSettings[0];

        $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_BUDGET_CODE] = array( 'ACTIVATED' => $defaultSettings['view_budgetcode'],
            'MANDATORY' => $defaultSettings['mandatory_budgetcode'], 'HAS_CUSTOM_NAME' => false, 'CUSTOM_NAME' => null );
        $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_DOCUMENT_NATURE] = array( 'ACTIVATED' => $defaultSettings['view_documentnature'],
            'MANDATORY' => $defaultSettings['mandatory_documentnature'], 'HAS_CUSTOM_NAME' => false, 'CUSTOM_NAME' => null );
        $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_DOCUMENT_TYPE] = array( 'ACTIVATED' => $defaultSettings['view_documenttype'],
            'MANDATORY' => $defaultSettings['mandatory_documenttype'], 'HAS_CUSTOM_NAME' => false, 'CUSTOM_NAME' => null );
        $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_DESCRIPTION_1] = array( 'ACTIVATED' => $defaultSettings['view_description1'],
            'MANDATORY' => $defaultSettings['mandatory_description1'], 'HAS_CUSTOM_NAME' => true, 'CUSTOM_NAME' => $defaultSettings['name_description1'] );
        $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_DESCRIPTION_2] = array( 'ACTIVATED' => $defaultSettings['view_description2'],
            'MANDATORY' => $defaultSettings['mandatory_description2'], 'HAS_CUSTOM_NAME' => true, 'CUSTOM_NAME' => $defaultSettings['name_description2'] );
        $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_DOCUMENT_NUMBER] = array( 'ACTIVATED' => $defaultSettings['view_filenumber'],
            'MANDATORY' => $defaultSettings['mandatory_filenumber'], 'HAS_CUSTOM_NAME' => false, 'CUSTOM_NAME' => null );
        $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_BOX_NUMBER] = array( 'ACTIVATED' => $defaultSettings['view_boxnumber'],
            'MANDATORY' => $defaultSettings['mandatory_boxnumber'], 'HAS_CUSTOM_NAME' => false, 'CUSTOM_NAME' => null );
        $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_CONTAINER_NUMBER] = array( 'ACTIVATED' => $defaultSettings['view_containernumber'],
            'MANDATORY' => $defaultSettings['mandatory_containernumber'], 'HAS_CUSTOM_NAME' => false, 'CUSTOM_NAME' => null );
        $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_PROVIDER] = array( 'ACTIVATED' => $defaultSettings['view_provider'],
            'MANDATORY' => $defaultSettings['mandatory_provider'], 'HAS_CUSTOM_NAME' => false, 'CUSTOM_NAME' => null );
        $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_LIMITS_DATE] = array( 'ACTIVATED' => $defaultSettings['view_limitsdate'],
            'MANDATORY' => $defaultSettings['mandatory_limitsdate'], 'HAS_CUSTOM_NAME' => false, 'CUSTOM_NAME' => null );
        $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_LIMITS_NUM] = array( 'ACTIVATED' => $defaultSettings['view_limitsnum'],
            'MANDATORY' => $defaultSettings['mandatory_limitsnum'], 'HAS_CUSTOM_NAME' => false, 'CUSTOM_NAME' => null );
        $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_LIMITS_ALPHA] = array( 'ACTIVATED' => $defaultSettings['view_limitsalpha'],
            'MANDATORY' => $defaultSettings['mandatory_limitsalpha'], 'HAS_CUSTOM_NAME' => false, 'CUSTOM_NAME' => null );
        $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_LIMITS_ALPHANUM] = array( 'ACTIVATED' => $defaultSettings['view_limitsalphanum'],
            'MANDATORY' => $defaultSettings['mandatory_limitsalphanum'], 'HAS_CUSTOM_NAME' => false, 'CUSTOM_NAME' => null );


        foreach( $userSession->getUserServices() as $userService ){
            if( $bFirst ){
                $bFirst = false;
                $serviceSettings = $this->arrayFindOneByService( $userService->getService()->getId() );
                if( !$serviceSettings ) return null;
                else {
                    $serviceSettings = $serviceSettings[0];
                    $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_BUDGET_CODE] = array( 'ACTIVATED' => $serviceSettings['view_budgetcode'],
                        'MANDATORY' => $serviceSettings['mandatory_budgetcode'], 'HAS_CUSTOM_NAME' => false, 'CUSTOM_NAME' => null );
                    $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_DOCUMENT_NATURE] = array( 'ACTIVATED' => $serviceSettings['view_documentnature'],
                        'MANDATORY' => $serviceSettings['mandatory_documentnature'], 'HAS_CUSTOM_NAME' => false, 'CUSTOM_NAME' => null );
                    $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_DOCUMENT_TYPE] = array( 'ACTIVATED' => $serviceSettings['view_documenttype'],
                        'MANDATORY' => $serviceSettings['mandatory_documenttype'], 'HAS_CUSTOM_NAME' => false, 'CUSTOM_NAME' => null );
                    $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_DESCRIPTION_1] = array( 'ACTIVATED' => $serviceSettings['view_description1'],
                        'MANDATORY' => $serviceSettings['mandatory_description1'], 'HAS_CUSTOM_NAME' => true, 'CUSTOM_NAME' => $serviceSettings['name_description1'] );
                    $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_DESCRIPTION_2] = array( 'ACTIVATED' => $serviceSettings['view_description2'],
                        'MANDATORY' => $serviceSettings['mandatory_description2'], 'HAS_CUSTOM_NAME' => true, 'CUSTOM_NAME' => $serviceSettings['name_description2'] );
                    $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_DOCUMENT_NUMBER] = array( 'ACTIVATED' => $serviceSettings['view_filenumber'],
                        'MANDATORY' => $serviceSettings['mandatory_filenumber'], 'HAS_CUSTOM_NAME' => false, 'CUSTOM_NAME' => null );
                    $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_BOX_NUMBER] = array( 'ACTIVATED' => $serviceSettings['view_boxnumber'],
                        'MANDATORY' => $serviceSettings['mandatory_boxnumber'], 'HAS_CUSTOM_NAME' => false, 'CUSTOM_NAME' => null );
                    $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_CONTAINER_NUMBER] = array( 'ACTIVATED' => $serviceSettings['view_containernumber'],
                        'MANDATORY' => $serviceSettings['mandatory_containernumber'], 'HAS_CUSTOM_NAME' => false, 'CUSTOM_NAME' => null );
                    $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_PROVIDER] = array( 'ACTIVATED' => $serviceSettings['view_provider'],
                        'MANDATORY' => $serviceSettings['mandatory_provider'], 'HAS_CUSTOM_NAME' => false, 'CUSTOM_NAME' => null );
                    $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_LIMITS_DATE] = array( 'ACTIVATED' => $serviceSettings['view_limitsdate'],
                        'MANDATORY' => $serviceSettings['mandatory_limitsdate'], 'HAS_CUSTOM_NAME' => false, 'CUSTOM_NAME' => null );
                    $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_LIMITS_NUM] = array( 'ACTIVATED' => $serviceSettings['view_limitsnum'],
                        'MANDATORY' => $serviceSettings['mandatory_limitsnum'], 'HAS_CUSTOM_NAME' => false, 'CUSTOM_NAME' => null );
                    $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_LIMITS_ALPHA] = array( 'ACTIVATED' => $serviceSettings['view_limitsalpha'],
                        'MANDATORY' => $serviceSettings['mandatory_limitsalpha'], 'HAS_CUSTOM_NAME' => false, 'CUSTOM_NAME' => null );
                    $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_LIMITS_ALPHANUM] = array( 'ACTIVATED' => $serviceSettings['view_limitsalphanum'],
                        'MANDATORY' => $serviceSettings['mandatory_limitsalphanum'], 'HAS_CUSTOM_NAME' => false, 'CUSTOM_NAME' => null );
                }

            } else {
                $serviceSettings = $this->arrayFindOneByService( $userService->getService()->getId() );
                if( !$serviceSettings ) return null;
                else $serviceSettings = $serviceSettings[0];
                $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_BUDGET_CODE]['ACTIVATED'] |= $serviceSettings['view_budgetcode'];
                $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_BUDGET_CODE]['MANDATORY'] |= $serviceSettings['mandatory_budgetcode'];
                $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_DOCUMENT_NATURE]['ACTIVATED'] |= $serviceSettings['view_documentnature'];
                $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_DOCUMENT_NATURE]['MANDATORY'] |= $serviceSettings['mandatory_documentnature'];
                $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_DOCUMENT_TYPE]['ACTIVATED'] |= $serviceSettings['view_documenttype'];
                $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_DOCUMENT_TYPE]['MANDATORY'] |= $serviceSettings['mandatory_documenttype'];
                $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_DESCRIPTION_1]['ACTIVATED'] |= $serviceSettings['view_description1'];
                $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_DESCRIPTION_1]['MANDATORY'] |= $serviceSettings['mandatory_description1'];
                if( $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_DESCRIPTION_1]['CUSTOM_NAME'] != $serviceSettings['name_description1'] ){
                    $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_DESCRIPTION_1]['CUSTOM_NAME'] = null;
                    $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_DESCRIPTION_1]['HAS_CUSTOM_NAME'] = false;
                }
                $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_DESCRIPTION_2]['ACTIVATED'] |= $serviceSettings['view_description2'];
                $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_DESCRIPTION_2]['MANDATORY'] |= $serviceSettings['mandatory_description2'];
                if( $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_DESCRIPTION_2]['CUSTOM_NAME'] != $serviceSettings['name_description2'] ){
                    $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_DESCRIPTION_2]['CUSTOM_NAME'] = null;
                    $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_DESCRIPTION_2]['HAS_CUSTOM_NAME'] = false;
                }
                $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_DOCUMENT_NUMBER]['ACTIVATED'] |= $serviceSettings['view_filenumber'];
                $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_DOCUMENT_NUMBER]['MANDATORY'] |= $serviceSettings['mandatory_filenumber'];
                $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_BOX_NUMBER]['ACTIVATED'] |= $serviceSettings['view_boxnumber'];
                $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_BOX_NUMBER]['MANDATORY'] |= $serviceSettings['mandatory_boxnumber'];
                $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_CONTAINER_NUMBER]['ACTIVATED'] |= $serviceSettings['view_containernumber'];
                $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_CONTAINER_NUMBER]['MANDATORY'] |= $serviceSettings['mandatory_containernumber'];
                $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_PROVIDER]['ACTIVATED'] |= $serviceSettings['view_provider'];
                $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_PROVIDER]['MANDATORY'] |= $serviceSettings['mandatory_provider'];
                $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_LIMITS_DATE]['ACTIVATED'] |= $serviceSettings['view_limitsdate'];
                $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_LIMITS_DATE]['MANDATORY'] |= $serviceSettings['mandatory_limitsdate'];
                $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_LIMITS_NUM]['ACTIVATED'] |= $serviceSettings['view_limitsnum'];
                $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_LIMITS_NUM]['MANDATORY'] |= $serviceSettings['mandatory_limitsnum'];
                $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_LIMITS_ALPHA]['ACTIVATED'] |= $serviceSettings['view_limitsalpha'];
                $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_LIMITS_ALPHA]['MANDATORY'] |= $serviceSettings['mandatory_limitsalpha'];
                $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_LIMITS_ALPHANUM]['ACTIVATED'] |= $serviceSettings['view_limitsalphanum'];
                $commonSettings[IDPServiceSettings::COMMON_SERVICE_SETTINGS_LIMITS_ALPHANUM]['MANDATORY'] |= $serviceSettings['mandatory_limitsalphanum'];
            }
        }

        return $commonSettings;
    }

    // $services is a "IN string" like (1,3,4)
    public function getAllIn( $services ){
        $query = $this->getEntityManager()
            ->createQuery("SELECT s FROM bsIDPBackofficeBundle:IDPServiceSettings s WHERE s.service_id IN " . $services )
            ->setHint(Query::HINT_INCLUDE_META_COLUMNS, true);
        return $query->getArrayResult();
    }
}
