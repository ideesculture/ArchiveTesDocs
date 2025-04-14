<?php

// src/bs/Core/AdminBundle/Command/AdminInstallDatabaseCommand.php
namespace bs\Core\AdminBundle\Command;

use \DateTime;
use MongoDB\BSON\Timestamp;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

use bs\IDP\ArchiveBundle\ConstantDefinition\IDPConstants;

use bs\Core\TranslationBundle\Entity\bsTranslation;

use bs\Core\UsersBundle\Entity\bsUsers;
use bs\Core\UsersBundle\Entity\bsRights;
use bs\Core\UsersBundle\Entity\bsRoles;
use bs\Core\UsersBundle\Entity\IDPUserExtensions;

use bs\IDP\BackofficeBundle\Entity\IDPUserPagesSettings;
use bs\IDP\BackofficeBundle\Entity\IDPUserColumnsSettings;
use bs\IDP\BackofficeBundle\Entity\IDPGlobalSettings;
use bs\IDP\BackofficeBundle\Entity\IDPGlobalStatuses;
use bs\IDP\BackofficeBundle\Entity\IDPServiceSettings;
use bs\IDP\BackofficeBundle\Entity\IDPMainSettings;
use bs\IDP\BackofficeBundle\Entity\IDPColumns;

use bs\IDP\ArchiveBundle\Entity\IDPArchivesStatus;

use bs\Core\AdminBundle\Translation\bsDefaultTranslation;

//// TO BE REVIEWED

class AdminInstallDatabaseCommand extends ContainerAwareCommand
{
    const tab = '                                                            ';

    // List of tables to test / initialize
    const DB_to_TEST = [
        // DB Configuration
        'bsIDPBackofficeBundle:IDPGlobalStatuses',
        'bsIDPBackofficeBundle:IDPMainSettings',
        'bsIDPBackofficeBundle:IDPGlobalSettings',
        'bsIDPBackofficeBundle:IDPServiceSettings',
        'bsIDPBackofficeBundle:IDPColumns',
        'bsCoreAdminBundle:bsAdminconfig',
        // DB User
        'bsCoreUsersBundle:bsUsers',
        'bsCoreUsersBundle:bsRoles',
        'bsCoreUsersBundle:bsRights',
        'bsCoreUsersBundle:IDPUserExtensions',
        'bsCoreUsersBundle:IDPUserServices',
        'bsCoreUsersBundle:IDPUserAddresses',
        'bsIDPBackofficeBundle:IDPUserPagesSettings',
        'bsIDPBackofficeBundle:IDPUserColumnsSettings',
        'bsCoreUsersBundle:IDPUserAutoSaveFields',
        'bsCoreUsersBundle:IDPUserFiles',
        // DB Archives Database
        'bsIDPBackofficeBundle:IDPServices',
        'bsIDPBackofficeBundle:IDPBudgetCodes',
        'bsIDPBackofficeBundle:IDPLegalEntities',
        'bsIDPBackofficeBundle:IDPDocumentNatures',
        'bsIDPBackofficeBundle:IDPDocumentTypes',
        'bsIDPBackofficeBundle:IDPDescriptions1',
        'bsIDPBackofficeBundle:IDPDescriptions2',
        'bsIDPBackofficeBundle:IDPProviders',
        'bsIDPBackofficeBundle:IDPLocalizations',
        'bsIDPBackofficeBundle:IDPDeliverAddress',
        // DB Archives
        'bsIDPArchiveBundle:IDPArchivesStatus',
        'bsIDPArchiveBundle:IDPArchive',
        'bsIDPArchiveBundle:IDPDeletedArchive',
        // DB Import
        'bsIDPArchiveBundle:IDPImport',
        'bsIDPArchiveBundle:IDPImportComm',
        // DB Audit
        'bsIDPArchiveBundle:IDPAudit',
        'bsIDPArchiveBundle:IDPAuditCompleteUa',
        // DB Reconciliation
        'bsIDPArchiveBundle:IDPReconciliation',
        'bsIDPArchiveBundle:IDPReconciliationComm',
        'bsIDPArchiveBundle:IDPReconciliationFile',
        // DB Others
        'bsIDPBackofficeBundle:IDPTempOpti',
        'bsIDPBackofficeBundle:IDPProviderConnectorBackup',
        'bsIDPDashboardBundle:bsMessages',
        'bsCoreTranslationBundle:bsTranslation',
        'bsIDPArchiveBundle:IDPStatistics'
    ];

    // IDPGlobalStatuses table description and default value
    const IDX_IMPORT_IN_PROGRESS = 0;
    const IDX_CANCEL_IN_PROGRESS = 1;
    const IDX_CURRENT_IMPORT_ID = 2;
    const IDX_RECONCILIATION_IN_PROGRESS = 3;
    const IDX_CURRENT_RECONCILIATION_ID = 4;
    const _IDPGlobalStatuses = [ false, false, 0, false, 0 ];

    // IDPMainSettings table description and default value
    const IDX_MS_NAME = 0;
    const IDX_MS_INT_VALUE = 1;
    const _IDPMainSettings = [
        [ 'ALL_SERVICES_CONFIGURED_AT_ONCE', 0 ]
    ];

    // IDPGlobalSettings table description and default value
    const IDX_GS_NAME = 0;
    const IDX_GS_INT_VALUE = 1;
    const _IDPGlobalSettings = [
        [ 'PASSWORD_MIN_LENGTH', 8 ],
        [ 'PASSWORD_COMPLEXITY', 15 ],
        [ 'EXPORT_TYPE', 2 ]
    ];

    // IDPServiceSettings table description and default value
    const IDX_S_SERVICE_ID = 0;
    const IDX_S_VIEW_BUDGETCODE = 1;
    const IDX_S_MANDATORY_BUDGETCODE = 2;
    const IDX_S_VIEW_DOCUMENTNATURE = 3;
    const IDX_S_MANDATORY_DOCUMENTNATURE = 4;
    const IDX_S_VIEW_DOCUMENTTYPE = 5;
    const IDX_S_MANDATORY_DOCUMENTTYPE = 6;
    const IDX_S_VIEW_DESCRIPTION1 = 7;
    const IDX_S_MANDATORY_DESCRIPTION1 = 8;
    const IDX_S_NAME_DESCRIPTION1 = 9;
    const IDX_S_VIEW_DESCRIPTION2 = 10;
    const IDX_S_MANDATORY_DESCRIPTION2 = 11;
    const IDX_S_NAME_DESCRIPTION2 = 12;
    const IDX_S_VIEW_LIMITSNUM = 13;
    const IDX_S_MANDATORY_LIMITSNUM = 14;
    const IDX_S_VIEW_LIMITSALPHA = 15;
    const IDX_S_MANDATORY_LIMITSALPHA = 16;
    const IDX_S_VIEW_LIMITSALPHANUM = 17;
    const IDX_S_MANDATORY_LIMITSALPHANUM = 18;
    const IDX_S_VIEW_LIMITSDATE = 19;
    const IDX_S_MANDATORY_LIMITSDATE = 20;
    const IDX_S_VIEW_FILENUMBER = 21;
    const IDX_S_MANDATORY_FILENUMBER = 22;
    const IDX_S_VIEW_BOXNUMBER = 23;
    const IDX_S_MANDATORY_BOXNUMBER = 24;
    const IDX_S_VIEW_CONTAINERNUMBER = 25;
    const IDX_S_MANDATORY_CONTAINERNUMBER = 26;
    const IDX_S_VIEW_PROVIDER = 27;
    const IDX_S_MANDATORY_PROVIDER = 28;
    const IDX_S_DEFAULT_LANGUAGE = 29;
    const IDX_S_VIEW_TRANSFER_INTERNAL_BASKET = 30;
    const IDX_S_VIEW_TRANSFER_INTERMEDIATE_BASKET = 31;
    const IDX_S_VIEW_TRANSFER_PROVIDER_BASKET = 32;
    const IDX_S_VIEW_RELOC_INTERNAL_BASKET = 33;
    const IDX_S_VIEW_RELOC_INTERMEDIATE_BASKET = 34;
    const IDX_S_VIEW_RELOC_PROVIDER_BASKET = 35;
    const _IDPServiceSettings = [
        0,                              // Service ID
        true, false,                     // View, Mandatory BudgetCode
        true, false,                     // View, Mandatory DocumentNature
        true, false,                     // View, Mandatory DocumentType
        true, false, 'Description 1',    // View, Mandatory, Name Description1
        true, false, 'Description 2',    // View, Mandatory, Name Description2
        true, false,                     // View, Mandatory LimitsNum
        true, false,                     // View, Mandatory LimitsAlpha
        true, false,                     // View, Mandatory LimitsAlphanum
        true, false,                     // View, Mandatory LimitsDate
        true, false,                     // View, Mandatory Filenumber
        true, false,                     // View, Mandatory Boxnumber
        true, false,                     // View, Mandatory Constainernumber
        true, false,                     // View, Mandatory Provider
        0,                              // Default Language
        true, true, true,               // View Transfer Internal, Intermediate, Provider Basket
        true, true, true];              // View Reloc Internal, Intermediate, Provider Basket

    // IDPColumns table description and default value
    const IDX_C_FIELD_NAME = 0;
    const IDX_C_TRANSLATION_ID = 1;
    const IDX_C_ORDER_IDX = 2;
    const IDX_C_VIEW_BY_CONFIG = 3;
    const IDX_C_CONFIG_IDX = 4;
    const _IDPColumns = [
        ['service', 1, 1, 0, 0 ],
        ['ordernumber', 2, 2, 0, 0 ],
        ['legalentity', 3, 3, 0, 0 ],
        ['name', 4, 4, 0, 0 ],
        ['budgetcode', 5, 5, 1, 1 ],
        ['documentnature', 6, 6, 1, 2 ],
        ['documenttype', 7, 7, 1, 3],
        ['description1', 8, 8, 1, 4],
        ['description2', 9, 9, 1, 5],
        ['documentnumber', 10, 10, 1, 6],
        ['boxnumber', 11, 11, 1, 7],
        ['containernumber', 12, 12, 1, 8],
        ['provider', 13, 13, 1, 9],
        ['status', 14, 14, 0, 0],
        ['id', 15, 15, 0, 0],
        ['adminlist', 16, 16, 0, 0],
        ['statuscaps', 17, 17, 0, 0],
        ['authorized', 18, 18, 0, 0],
        ['localization', 19, 19, 0, 0],
        ['localizationfree', 20, 20, 0, 0],
        ['limitdatemin', 21, 21, 1, 10],
        ['limitdatemax', 22, 22, 1, 10],
        ['limitnummin', 23, 23, 1, 11],
        ['limitnummax', 24, 24, 1, 11],
        ['limitalphamin', 25, 25, 1, 12],
        ['limitalphamax', 26, 26, 1, 12],
        ['limitalphanummin', 27, 27, 1, 13],
        ['limitalphanummax', 28, 28, 1, 13],
        ['closureyear', 29, 29, 0, 0],
        ['destructionyear', 30, 30, 0, 0],
        ['statuscode', 31, 31, 0, 0],
        ['modifiedat', 32, 32, 0, 0],
        ['oldlocalization', 33, 33, 0, 0],
        ['oldlocalizationfree', 34, 34, 0, 0],
        ['providerid', 35, 35, 0, 0],
        ['precisiondate', 36, 36, 0, 0],
        ['precisionaddress', 37, 37, 0, 0],
        ['precisionfloor', 38, 38, 0, 0],
        ['precisionoffice', 39, 39, 0, 0],
        ['precisionwho', 40, 40, 0, 0],
        ['precisioncomment', 41, 41, 0, 0],
        ['locked', 42, 42, 0, 0],
        ['unlimited', 43, 43, 0, 0],
        ['unlimitedcomments', 44, 44, 0, 0]
    ];

    // bsRights table description and default value
    const IDX_R_NAME = 0;
    const IDX_R_DESCRIPTION = 1;
    const IDX_R_SCALE = 2;
    const _bsRights = [
        ['RIGHT_ARCHIVE_NEW', 'Saisir', 0],
        ['RIGHT_TRANSFER', 'Transférer', 0],
        ['RIGHT_RELOC', 'Relocaliser', 0],
        ['RIGHT_CONSULT', 'Consulter une unité d\'archives', 0],
        ['RIGHT_DELETE', 'Détruire une unité d\'archives', 0],
        ['RIGHT_RETURN', 'Retourner une unité d\'archives', 0],
        ['RIGHT_EXIT', 'Sortir définitivement une unité d\'archives', 0],
        ['RIGHT_COMMAND', 'Passer une commande', 0],
        ['RIGHT_FOLLOW_COMMAND', 'Suivre vos commandes', 0],
        ['RIGHT_VALIDATE_USER_ASKS', 'Valider les demandes des utilisateurs', 0],
        ['RIGHT_MANAGE_PROVIDER_WANTS', 'Gérer les demandes des prestataires', 0],
        ['RIGHT_CLOSE_USER_WANTS', 'Clôturer les demandes des utilisateurs', 0],
        ['RIGHT_MANAGE_BDD', 'Gérer la base archive', 0],
        ['RIGHT_AUDIT', 'Accéder à l\'audit', 0],
        ['RIGHT_AUDIT_ADV', 'Accéder à l\'audit (noms)', 0],
        ['RIGHT_STAT', 'Consulter les statistiques', 0],
        ['RIGHT_UNLIMITED', 'Gérer les conservations illimitées', 0],
        ['RIGHT_ACTIVITY_SYNTHESIS', 'Voir la synthèse d\'activité', 0],
        ['RIGHT_EXPORT_ALL', 'Exporter toutes les données', 0],
        ['RIGHT_IMPORT', 'Importer les données', 0],
        ['RIGHT_IMPORTS_REPORT', 'Accéder au rapport des imports', 0],
        ['RIGHT_MANAGE_COMMAND', 'Gérer les commandes', 0],
        ['RIGHT_MANAGE_CATALOG', 'Gérer le catalogue', 0],
        ['RIGHT_MANAGE_USERS', 'Gérer les utilisateurs', 0],
        ['RIGHT_MANAGE_SETTINGS', 'Gérer les paramètres', 0],
        ['RIGHT_RECONCILIATION', 'Rapprocher les stocks', 0]
    ];

    // bsRoles table description and default value
    // Same table description (const columns) than bsRights
    const _bsRoles = [
        ['ROLE_USER', 'Utilisateur', 125],
        ['ROLE_SUPER_USER', 'Super utilisateur', 100],
        ['ROLE_ARCHIVIST', 'Archiviste', 75],
        ['ROLE_SUPER_ARCHIVIST', 'Super archiviste', 50],
        ['ROLE_ADMIN', 'Administrateur', 25],
        ['ROLE_SUPER_ADMIN', 'Super administrateur', 0]
    ];
    // Grid Roles / Rights table description and default value
    const IDX_RR_ROLE = 0;
    const IDX_RR_RIGHTS = 1;
    const _bsRolesRights = [
        'ROLE_USER' => ['RIGHT_ARCHIVE_NEW', 'RIGHT_TRANSFER', 'RIGHT_CONSULT', 'RIGHT_DELETE', 'RIGHT_RETURN', 'RIGHT_EXIT'],
        'ROLE_SUPER_USER' => ['RIGHT_ARCHIVE_NEW', 'RIGHT_TRANSFER', 'RIGHT_RELOC', 'RIGHT_CONSULT', 'RIGHT_DELETE',
            'RIGHT_RETURN', 'RIGHT_EXIT', 'RIGHT_COMMAND', 'RIGHT_FOLLOW_COMMAND'],
        'ROLE_ARCHIVIST' => ['RIGHT_ARCHIVE_NEW', 'RIGHT_TRANSFER', 'RIGHT_RELOC', 'RIGHT_CONSULT', 'RIGHT_DELETE',
            'RIGHT_RETURN', 'RIGHT_EXIT', 'RIGHT_COMMAND', 'RIGHT_FOLLOW_COMMAND', 'RIGHT_VALIDATE_USER_ASKS',
            'RIGHT_MANAGE_PROVIDER_WANTS', 'RIGHT_CLOSE_USER_WANTS', 'RIGHT_UNLIMITED', 'RIGHT_MANAGE_COMMAND'],
        'ROLE_SUPER_ARCHIVIST' => ['RIGHT_ARCHIVE_NEW', 'RIGHT_TRANSFER', 'RIGHT_RELOC', 'RIGHT_CONSULT', 'RIGHT_DELETE',
            'RIGHT_RETURN', 'RIGHT_EXIT', 'RIGHT_COMMAND', 'RIGHT_FOLLOW_COMMAND', 'RIGHT_VALIDATE_USER_ASKS',
            'RIGHT_MANAGE_PROVIDER_WANTS', 'RIGHT_CLOSE_USER_WANTS', 'RIGHT_AUDIT', 'RIGHT_UNLIMITED', 'RIGHT_STAT',
            'RIGHT_ACTIVITY_SYNTHESIS', 'RIGHT_MANAGE_COMMAND'],
        'ROLE_ADMIN' => ['RIGHT_ARCHIVE_NEW', 'RIGHT_TRANSFER', 'RIGHT_RELOC', 'RIGHT_CONSULT', 'RIGHT_DELETE',
            'RIGHT_RETURN', 'RIGHT_EXIT', 'RIGHT_COMMAND', 'RIGHT_FOLLOW_COMMAND', 'RIGHT_VALIDATE_USER_ASKS',
            'RIGHT_MANAGE_PROVIDER_WANTS', 'RIGHT_CLOSE_USER_WANTS', 'RIGHT_MANAGE_BDD', 'RIGHT_AUDIT',
            'RIGHT_AUDIT_ADV', 'RIGHT_UNLIMITED', 'RIGHT_STAT', 'RIGHT_ACTIVITY_SYNTHESIS', 'RIGHT_MANAGE_COMMAND'
             ],
        'ROLE_SUPER_ADMIN' => ['RIGHT_ARCHIVE_NEW', 'RIGHT_TRANSFER', 'RIGHT_RELOC', 'RIGHT_CONSULT', 'RIGHT_DELETE',
            'RIGHT_RETURN', 'RIGHT_EXIT', 'RIGHT_COMMAND', 'RIGHT_FOLLOW_COMMAND', 'RIGHT_VALIDATE_USER_ASKS',
            'RIGHT_MANAGE_PROVIDER_WANTS', 'RIGHT_CLOSE_USER_WANTS', 'RIGHT_MANAGE_BDD', 'RIGHT_AUDIT',
            'RIGHT_AUDIT_ADV', 'RIGHT_UNLIMITED', 'RIGHT_STAT', 'RIGHT_ACTIVITY_SYNTHESIS', 'RIGHT_EXPORT_ALL', 'RIGHT_IMPORT',
            'RIGHT_IMPORTS_REPORT', 'RIGHT_MANAGE_COMMAND', 'RIGHT_MANAGE_CATALOG', 'RIGHT_MANAGE_USERS',
            'RIGHT_MANAGE_SETTINGS', 'RIGHT_RECONCILIATION' ]
    ];
    // bsUsers table description and default value
    // Default Users for administration at beginning purpose
    // /!\ Make a password generation function instead
    const IDX_U_FIRSTNAME = 0;
    const IDX_U_LASTNAME = 1;
    const IDX_U_LOGIN = 2;
    const IDX_U_PASSWORD = 3;
    const IDX_U_LASTACTION = 4;
    const IDX_U_CONNECTED = 5;
    const IDX_U_CHANGEPASS = 6;
    const IDX_U_FAILEDCONNEXIONCOUNTER = 7;
    const IDX_U_PHPSESSID = 8;
    const IDX_U_DEFAULT_ROLE = 9;
    const _defaultUsers = [
        [ 'ADMIN', 'BESECURE', 'bsadmin', 'bsadmin', 0, false, true, 0, null, 'ROLE_SUPER_ADMIN' ],
        [ 'ADMIN', 'IDP', 'admin', 'admin', 0, false, true, 0, null, 'ROLE_SUPER_ADMIN' ]
    ];

    // IDPUserExtention default value
    const IDX_UX_INITIALS = 0;
    const IDX_UX_UACOUNTER = 1;
    const IDX_UX_LANGUAGE = 2;
    const _IPDUserExtentions = [
        'bsadmin' => ['*BS*', 0, 0],
        'admin' => ['*AD*', 0, 0]
    ];

    // IDPUserPagesSettings default value
    const _minPageID = 1;
    const _maxPageID = 46;
    const NB_ROW_PER_PAGE = 10;
    const ARRAY_TYPE_LIST = false;

    // IDPUserColumnsSettings
    const _maxPageWithColumnDefinition = 35;
    const _columnsPage = [
        1 => [ 'service', 'ordernumber', 'legalentity', 'name', 'containernumber' ],                                        // Transférer
        2 => [ 'service', 'ordernumber', 'legalentity', 'name', 'containernumber', 'localization', 'localizationfree' ],    // Consulter
        3 => [ 'service', 'ordernumber', 'legalentity', 'name', 'containernumber', 'precisiondate', 'precisionwho',         // Retourner
            'status' ],
        4 => [ 'service', 'ordernumber', 'legalentity', 'name', 'containernumber', 'localization', 'localizationfree',      // Sortir
            'status' ],
        5 => [ 'destructionyear', 'unlimited', 'service', 'ordernumber', 'legalentity', 'anem', 'containernumber',          // Détruire
            'localization', 'localizationfree', 'status' ],
        6 => [ 'service', 'ordernumber', 'legalentity', 'name', 'containernumber', 'localization', 'localizationfree',      // Relocaliser
            'status' ],
        7 => [ 'service', 'ordernumber', 'legalentity', 'name', 'containernumber', 'provider', 'localization',              // Valider / Transfert / Prestataire
            'precisiondate', 'precisionwho', 'precisionaddress', 'precisionfloor', 'precisionoffice', 'precisioncomment' ],
        8 => [ 'service', 'ordernumber', 'legalentity', 'name', 'containernumber', 'localizationfree', 'precisiondate',     // Valider / Transfert / Intermédiaire
            'precisionwho', 'precisionaddress', 'precisionfloor', 'precisionoffice', 'precisioncomment' ],
        9 => [ 'service', 'ordernumber', 'legalentity', 'name', 'containernumber', 'localizationfree', 'precisiondate',     // Valider / Transfert / Interne
            'precisionwho', 'precisionaddress', 'precisionfloor', 'precisionoffice', 'precisioncomment' ],
        10 => [ 'service', 'ordernumber', 'legalentity', 'name', 'containernumber', 'localization', 'localizationfree',     // Valider / Livraison / Sans preparation
            'precisiondate', 'precisionwho', 'precisionaddress', 'precisionfloor', 'precisionoffice', 'precisioncomment' ],
        11 => [ 'service', 'ordernumber', 'legalentity', 'name', 'containernumber', 'localization', 'localizationfree',     // Valider / Livraison / Avec preparation
            'precisiondate', 'precisionwho', 'precisionaddress', 'precisionfloor', 'precisionoffice', 'precisioncomment' ],
        12 => [ 'service', 'ordernumber', 'legalentity', 'name', 'containernumber', 'localization', 'localizationfree',     // Valider / Retour
            'precisiondate', 'precisionwho', 'precisionaddress', 'precisionfloor', 'precisionoffice', 'precisioncomment' ],
        13 => [ 'service', 'ordernumber', 'legalentity', 'name', 'containernumber', 'localization', 'localizationfree',     // Valider / Sortie
            'precisionwho', 'precisiondate', 'precisionaddress', 'precisionfloor', 'precisionoffice', 'precisioncomment' ],
        14 => [ 'destructionyear', 'unlimited', 'service', 'ordernumber', 'legalentity', 'name', 'containernumber',         // Valider / Détruire
            'localization', 'localizationfree', 'precisionwho', 'precisiondate', 'precisionaddress', 'precisionfloor',
            'precisionoffice', 'precisioncomment' ],
        15 => [ 'service', 'ordernumber', 'legalentity', 'name', 'containernumber', 'provider', 'localization',             // Valider / Relocaliser / Prestataire
            'precisiondate', 'precisionwho', 'precisionaddress', 'precisionfloor', 'precisionoffice', 'precisioncomment' ],
        16 => [ 'service', 'ordernumber', 'legalentity', 'name', 'containernumber', 'localization', 'localizationfree',     // Valider / Relocaliser / Intermédiaire
            'precisiondate', 'precisionwho', 'precisionaddress', 'precisionfloor', 'precisionoffice', 'precisioncomment' ],
        17 => [ 'service', 'ordernumber', 'legalentity', 'name', 'containernumber', 'localization', 'localizationfree',     // Valider / Relocaliser / Interne
            'precisiondate', 'precisionwho', 'precisionaddress', 'precisionfloor', 'precisionoffice', 'precisioncomment' ],
        18 => [ 'service', 'ordernumber', 'legalentity', 'name', 'containernumber', 'provider', 'precisiondate',            // Gérer / Transfert
            'precisionwho', 'precisionaddress', 'precisionfloor', 'precisionoffice', 'precisioncomment' ],
        19 => [ 'service', 'ordernumber', 'legalentity', 'name', 'containernumber', 'provider', 'precisiondate',            // Gérer / Livraison
            'precisionwho', 'precisionaddress', 'precisionfloor', 'precisionoffice', 'precisioncomment' ],
        20 => [ 'service', 'ordernumber', 'legalentity', 'name', 'containernumber', 'provider', 'precisiondate',            // Gérer / Retourner
            'precisionwho', 'precisionaddress', 'precisionfloor', 'precisionoffice', 'precisioncomment' ],
        21 => [ 'service', 'ordernumber', 'legalentity', 'name', 'containernumber', 'provider', 'precisionwho',             // Gérer / Sortir
            'precisiondate', 'precisionaddress', 'precisionfloor', 'precisionoffice', 'precisioncomment' ],
        22 => [ 'destructionyear', 'service', 'ordernumber', 'legalentity', 'name', 'containernumber', 'provider',          // Gérer / Détruire
            'precisionwho', 'precisiondate', 'precisionaddress', 'precisionfloor', 'precisionoffice', 'precisioncomment' ],
        23 => [ 'service', 'ordernumber', 'legalentity', 'name', 'ordernumber', 'provider', 'precisiondate',                // Gérer / Relocaliser
            'precisionwho', 'precisionaddress', 'precisionfloor', 'precisionoffice', 'precisioncomment' ],
        24 => [ 'service', 'ordernumber', 'legalentity', 'name', 'containernumber', 'provider', 'localization',             // Cloturer / Transfert / Prestataire
            'precisiondate', 'precisionwho', 'precisionaddress', 'precisionfloor', 'precisionoffice', 'precisioncomment' ],
        25 => [ 'service', 'ordernumber', 'legalentity', 'name', 'containernumber', 'localizationfree', 'precisiondate',    // Cloturer / Transfert / Intermédiaire
            'precisionwho', 'precisionaddress', 'precisionfloor', 'precisionoffice', 'precisioncomment' ],
        26 => [ 'service', 'ordernumber', 'legalentity', 'name', 'containernumber', 'localizationfree', 'precisiondate',    // Cloturer / Transfert / Interne
            'precisionwho', 'precisionaddress', 'precisionfloor', 'precisionoffice', 'precisioncomment' ],
        27 => [ 'service', 'ordernumber', 'legalentity', 'containernumber', 'localization', 'localizationfree',             // Cloturer / Livraison / Sans preparation
            'precisiondate', 'precisionwho', 'precisionaddress', 'precisionfloor', 'precisionoffice', 'precisioncomment' ],
        28 => [ 'service', 'ordernumber', 'legalentity', 'containernumber', 'localization', 'localizationfree',             // Cloturer / Livraison / Avec preparation
            'precisiondate', 'precisionwho', 'precisionaddress', 'precisionfloor', 'precisionoffice', 'precisioncomment' ],
        29 => [ 'service', 'ordernumber', 'legalentity', 'name', 'containernumber', 'localization', 'localizationfree',     // Cloturer / Retour
            'precisiondate', 'precisionwho', 'precisionaddress', 'precisionfloor', 'precisionoffice', 'precisioncomment' ],
        30 => [ 'service', 'ordernumber', 'legalentity', 'name', 'containernumber', 'localization', 'localizationfree',     // Cloturer / Sortir
            'precisionwho', 'precisiondate', 'precisionaddress', 'precisionfloor', 'precisionoffice', 'precisioncomment' ],
        31 => [ 'destructionyear', 'service', 'ordernumber', 'legalentity', 'name', 'containernumeber', 'localization',     // Cloturer / Destruction
            'localizationfree', 'precisiondate', 'precisionwho', 'precisionaddress', 'precisionfloor', 'precisionoffice',
            'precisioncomment' ],
        32 => [ 'service', 'ordernumber', 'legalentity', 'name', 'containernumber', 'provider', 'localization',             // Cloturer / Relocaliser / Prestataire
            'precisiondate', 'precisionwho', 'precisionaddress', 'precisionfloor', 'precisionoffice', 'precisioncomment' ],
        33 => [ 'service', 'ordernumber', 'legalentity', 'name', 'containernumber', 'localizationfree',                     // Cloturer / Relocaliser / Intermediaire
            'precisiondate', 'precisionwho', 'precisionaddress', 'precisionfloor', 'precisionoffice', 'precisioncomment' ],
        34 => [ 'service', 'ordernumber', 'legalentity', 'name', 'containernumber', 'localizationfree',                     // Cloturer / Relocaliser / Interne
            'precisiondate', 'precisionwho', 'precisionaddress', 'precisionfloor', 'precisionoffice', 'precisioncomment' ],
        35 => [ 'service', 'ordernumber', 'legaleentity', 'name', 'containernumber', 'destructionyear', 'unlimited',        // Gestion illimitée
            'unlimitedcomments' ]
    ];
    const _switchableColumns = [ 'service', 'ordernumber', 'legalentity', 'name', 'budgetcode', 'documentnature',
        'documenttype', 'description1', 'description2', 'documentnumber', 'boxnumber', 'containernumber', 'provider',
        'status', 'localization', 'localizationfree', 'limitdatemin', 'limitdatemax', 'limitnummin', 'limitnummax',
        'limitalphamin', 'limitalphamax', 'limitalphanummin', 'limitalphanummax', 'closureyear', 'destructionyear',
        'oldlocalization', 'oldlocalizationfree', 'precisiondate', 'precisionwho', 'precisionaddress', 'precisionfloor',
        'precisionoffice', 'precisioncomment', 'unlimited', 'unlimitedcomments' ];

    // IDPArchivesStatus table definition and default values
    const IDX_UAS_LONGNAME = 0;
    const IDX_UAS_SHORTNAME = 1;
    const _IDPArchivesStatus = [
        ["Nouvelle unité d'archives à valider", 'NAV'],
        ["Demande de transfert archiviste", 'DTA'],
        ["En transfert", 'DTRI'],
        ["En transfert", 'DTRINT'],
        ["En transfert", 'DTRP'],
        ["En transfert", 'GDTRP'],
        ["En transfert", 'CDTRI'],
        ["En transfert", 'CDTRINT'],
        ["En transfert", 'CDTRP'],
        ["Disponible", 'DISI'],
        ["Disponible", 'DISINT'],
        ["Disponible", 'DISP'],
        ["En attente de consultation", 'CLAI'],
        ["En attente de consultation", 'CPAI'],
        ["En attente de consultation", 'CLAINT'],
        ["En attente de consultation", 'CPAINT'],
        ["En attente de consultation", 'CLAP'],
        ["En attente de consultation", 'CPAP'],
        ["En attente de consultation", 'CLII'],
        ["En attente de consultation", 'CPRI'],
        ["En attente de consultation", 'CLIINT'],
        ["En attente de consultation", 'CPRINT'],
        ["En attente de consultation", 'GLAP'],
        ["En attente de consultation", 'GPAP'],
        ["En attente de consultation", 'CLIP'],
        ["En attente de consultation", 'CPRP'],
        ["En consultation", 'CONI'],
        ["En consultation", 'CONINT'],
        ["En consultation", 'CONP'],
        ["En attente de retour", 'CRAI'],
        ["En attente de retour", 'CRAINT'],
        ["En attente de retour", 'CRAP'],
        ["En attente de retour", 'CRTI'],
        ["En attente de retour", 'CRTINT'],
        ["En attente de retour", 'GRAP'],
        ["En attente de retour", 'CRTP'],
        ["En attente de sortie définitive", 'CSAI'],
        ["En attente de sortie définitive", 'CSAINT'],
        ["En attente de sortie définitive", 'CSAP'],
        ["En attente de sortie définitive", 'CSDI'],
        ["En attente de sortie définitive", 'CSDINT'],
        ["En attente de sortie définitive", 'GSAP'],
        ["En attente de sortie définitive", 'CSDP'],
        ["Sorti", 'ESDI'],
        ["Sorti", 'ESDINT'],
        ["Sorti", 'ESDP'],
        ["En attente de destruction", 'CDAI'],
        ["En attente de destruction", 'CDAINT'],
        ["En attente de destruction", 'CDAP'],
        ["En attente de destruction", 'CDEI'],
        ["En attente de destruction", 'CDEINT'],
        ["En attente de destruction", 'GDAP'],
        ["En attente de destruction", 'CDEP'],
        ["Détruit", 'EDEI'],
        ["Détruit", 'EDEINT'],
        ["Détruit", 'EDEP'],
        ["En attente de relocalisation", 'CRLIDAINT'],
        ["En attente de relocalisation", 'CRLIDAP'],
        ["En attente de relocalisation", 'CRLIDAI'],
        ["En attente de relocalisation", "GRLIDAP"],
        ["En attente de relocalisation", 'CRLIDINT'],
        ["En attente de relocalisation", 'CRLIDP'],
        ["En attente de relocalisation", 'CRLIDI'],
        ["En attente de relocalisation", 'CRLINTDAI'],
        ["En attente de relocalisation", 'CRLINTDAP'],
        ["En attente de relocalisation", 'CRLINTDAINT'],
        ["En attente de relocalisation", 'GRLINTDAP'],
        ["En attente de relocalisation", 'CRLINTDI'],
        ["En attente de relocalisation", 'CRLINTDP'],
        ["En attente de relocalisation", 'CRLINTDINT'],
        ["En attente de relocalisation", 'CRLPDAI'],
        ["En attente de relocalisation", 'CRLPDAINT'],
        ["En attente de relocalisation", 'GRLPDAI'],
        ["En attente de relocalisation", 'GRLPDAINT'],
        ["En attente de relocalisation", 'CRLPDI'],
        ["En attente de relocalisation", 'CRLPDINT'],
        ["En attente de relocalisation", 'CRLICAI'],
        ["En attente de relocalisation", 'CRLICAINT'],
        ["En attente de relocalisation", 'CRLICI'],
        ["En attente de relocalisation", 'CRLICINT'],
        ["En attente de relocalisation", 'CRLINTCAINT'],
        ["En attente de relocalisation", 'CRLINTCAI'],
        ["En attente de relocalisation", 'CRLINTCINT'],
        ["En attente de relocalisation", 'CRLINTCI'],
        ["En attente de relocalisation", 'CRLPCAI'],
        ["En attente de relocalisation", 'CRLPCAINT'],
        ["En attente de relocalisation", 'CRLPCI'],
        ["En attente de relocalisation", 'CRLPCINT'],
        ["En attente de relocalisation", 'CRAPCONRIDISP'],
        ["En attente de relocalisation", 'GRAPCONRIDISP'],
        ["En attente de relocalisation", 'CRTPCONRIDISP'],
        ["En attente de relocalisation", 'CRAPCONRINTDISP'],
        ["En attente de relocalisation", 'GRAPCONRINTDISP'],
        ["En attente de relocalisation", 'CRTPCONRINTDISP'],
        ["En attente de relocalisation", 'CRAPCONRICONP'],
        ["En attente de relocalisation", 'GRAPCONRICONP'],
        ["En attente de relocalisation", 'CRTPCONRICONP'],
        ["En attente de relocalisation", 'CRAPCONRINTCONP'],
        ["En attente de relocalisation", 'GRAPCONRINTCONP'],
        ["En attente de relocalisation", 'CRTPCONRINTCONP'],
        ["En consultation", 'CONRIDISP'],
        ["En consultation", 'CONRINTDISP'],
        ["En consultation", 'CONRICONP'],
        ["En consultation", 'CONRINTCONP']
    ];

    // bsTranslation table definition and default values
    const IDX_BST_PAGE = 0;
    const IDX_BST_SENTENCE = 1;
    const IDX_BST_LANGUAGE = 2;
    const IDX_BST_TRANSLATION = 3;
    // _bsTranslation is defined in bs/Core/AdminBundle/Translation/bsDefaultTranslation

    //----------------------------------------------------------------------------------------------------------------
    // COMMAND DEFINITION
    //----------------------------------------------------------------------------------------------------------------
    protected function configure()
    {
        $this
            ->setName('admin:install-database')
            ->setDescription('Install database datas for version 1.0.0') // ?? verify or do 1.0.0 install + update

//            ->addArgument('filename', InputArgument::REQUIRED, 'name of file to import, must be in web/import/archimage dir')
//            ->addArgument('debug', InputArgument::OPTIONAL, 'debug mode activated')
        ;
    }

    private function outputHeader( OutputInterface $output ){
        $output->writeln( '-------------------------------------------------------' );
        $output->writeln( ' ARCHIMAGE DATABASE INSTALLATION procedure v1.0.0');
        $output->writeln( '-------------------------------------------------------' );
        $output->writeln( '' );
    }

    private function outputFooter( OutputInterface $output ){
        $output->writeln( '' );
        $output->writeln( '--------------------------------------------------------' );
        $output->writeln( '<info> ARCHIMAGE DATABASE INSTALLATION procedure ended correctly </info>');
        $output->writeln( '<error> You have to check archimage 1.0.0 installation with admin:validate-install</error>');
        $output->writeln( '--------------------------------------------------------\a' );
    }

    private function verifyEmptyDatabase( $output ){
        $doctrine = $this->getContainer()->get('doctrine');

        $output->writeln( '<info>ARCHIMAGE DATABASE EMPTY VERIFICATION</info>');
        $globalVerification = true;

        foreach( self::DB_to_TEST as $db ) {
            $localtab = substr( self::tab, 1,strlen(self::tab)-strlen($db) );
            $stroutput = $db . $localtab;
            $emptyDBTest = $doctrine->getRepository( $db )->findAll();
            if ($emptyDBTest) {
                $stroutput = '<error>'. $stroutput .': KO</error>';
                $globalVerification = false;
            } else
                $stroutput .= ': <info>OK</info>';
            $output->writeln( $stroutput );
        }
        if( !$globalVerification ) return false;

        return true;
    }

    private function initIDPGlobalStatuses( $output ){
        $output->writeln( '<info>IDPGlobalStatuses initialization</info>');
        $idpGS = new IDPGlobalStatuses();
        $idpGS->setImportInProgress( self::_IDPGlobalStatuses[self::IDX_IMPORT_IN_PROGRESS] );
        $idpGS->setCancelInProgress( self::_IDPGlobalStatuses[self::IDX_CANCEL_IN_PROGRESS] );
        $idpGS->setCurrentImportId( self::_IDPGlobalStatuses[self::IDX_CURRENT_IMPORT_ID] );
        $idpGS->setReconciliationInProgress( self::_IDPGlobalStatuses[self::IDX_RECONCILIATION_IN_PROGRESS] );
        $idpGS->setCurrentReconciliationId( self::_IDPGlobalStatuses[self::IDX_CURRENT_RECONCILIATION_ID] );

        $em = $this->getContainer()->get('doctrine')->getManager();
        $em->persist( $idpGS );
        $em->flush();

        $output->writeln( '-------------------------------------------------------> <info>Success</info>');
        return true;
    }

    private function initIDPMainSettings( $output ){
        $output->writeln( '<info>IDPMainSettings initialization</info>');

        $em = $this->getContainer()->get('doctrine')->getManager();
        foreach( self::_IDPMainSettings as $_idpMS ){
            $idpMS = new IDPMainSettings();
            $idpMS->setName( $_idpMS[ self::IDX_MS_NAME ] );
            $idpMS->setIntValue( $_idpMS[ self::IDX_MS_INT_VALUE ] );
            $em->persist( $idpMS );
            $em->flush();

            $localtab = substr( self::tab, 1,strlen(self::tab)-strlen($_idpMS[ self::IDX_MS_NAME ]) );
            $output->writeln( $_idpMS[ self::IDX_MS_NAME ] . $localtab . ': <info>OK</info>' );
        }

        $output->writeln( '-------------------------------------------------------> <info>Success</info>');
        return true;
    }

    private function initIDPGlobalSettings( $output ){
        $output->writeln( '<info>IDPGlobalSettings initialization</info>');

        $em = $this->getContainer()->get('doctrine')->getManager();
        foreach( self::_IDPGlobalSettings as $_idpGS ){
            $idpGS = new IDPGlobalSettings();
            $idpGS->setName( $_idpGS[ self::IDX_GS_NAME ] );
            $idpGS->setIntValue( $_idpGS[ self::IDX_GS_INT_VALUE ] );
            $em->persist( $idpGS );
            $em->flush();

            $localtab = substr( self::tab, 1,strlen(self::tab)-strlen($_idpGS[ self::IDX_GS_NAME ]) );
            $output->writeln( $_idpGS[ self::IDX_GS_NAME ] . $localtab . ': <info>OK</info>' );
        }

        $output->writeln( '-------------------------------------------------------> <info>Success</info>');
        return true;
    }

    private function initIDPServiceSettings( $output ){
        $output->writeln( '<info>IDPServiceSettings initialization</info>');
        $idpS = new IDPServiceSettings();
        $idpS->setServiceid(self::_IDPServiceSettings[self::IDX_S_SERVICE_ID]);
        $idpS->setViewBudgetcode( self::_IDPServiceSettings[self::IDX_S_VIEW_BUDGETCODE] );
        $idpS->setMandatoryBudgetcode( self::_IDPServiceSettings[self::IDX_S_MANDATORY_BUDGETCODE] );
        $idpS->setViewDocumentnature( self::_IDPServiceSettings[self::IDX_S_VIEW_DOCUMENTNATURE]);
        $idpS->setMandatoryDocumentnature(self::_IDPServiceSettings[self::IDX_S_MANDATORY_DOCUMENTNATURE]);
        $idpS->setViewDocumenttype(self::_IDPServiceSettings[self::IDX_S_VIEW_DOCUMENTTYPE]);
        $idpS->setMandatoryDocumenttype(self::_IDPServiceSettings[self::IDX_S_MANDATORY_DOCUMENTTYPE]);
        $idpS->setViewDescription1(self::_IDPServiceSettings[self::IDX_S_VIEW_DESCRIPTION1]);
        $idpS->setMandatoryDescription1(self::_IDPServiceSettings[self::IDX_S_MANDATORY_DESCRIPTION1]);
        $idpS->setNameDescription1(self::_IDPServiceSettings[self::IDX_S_NAME_DESCRIPTION1]);
        $idpS->setViewDescription2(self::_IDPServiceSettings[self::IDX_S_VIEW_DESCRIPTION2]);
        $idpS->setMandatoryDescription2(self::_IDPServiceSettings[self::IDX_S_MANDATORY_DESCRIPTION2]);
        $idpS->setNameDescription2(self::_IDPServiceSettings[self::IDX_S_NAME_DESCRIPTION2]);
        $idpS->setViewLimitsnum(self::_IDPServiceSettings[self::IDX_S_VIEW_LIMITSNUM]);
        $idpS->setMandatoryLimitsnum(self::_IDPServiceSettings[self::IDX_S_MANDATORY_LIMITSNUM]);
        $idpS->setViewLimitsalpha(self::_IDPServiceSettings[self::IDX_S_VIEW_LIMITSALPHA]);
        $idpS->setMandatoryLimitsalpha(self::_IDPServiceSettings[self::IDX_S_MANDATORY_LIMITSALPHA]);
        $idpS->setViewLimitsalphanum(self::_IDPServiceSettings[self::IDX_S_VIEW_LIMITSALPHANUM]);
        $idpS->setMandatoryLimitsalphanum(self::_IDPServiceSettings[self::IDX_S_MANDATORY_LIMITSALPHANUM]);
        $idpS->setViewLimitsdate(self::_IDPServiceSettings[self::IDX_S_VIEW_LIMITSDATE]);
        $idpS->setMandatoryLimitsdate(self::_IDPServiceSettings[self::IDX_S_MANDATORY_LIMITSDATE]);
        $idpS->setViewFilenumber(self::_IDPServiceSettings[self::IDX_S_VIEW_FILENUMBER]);
        $idpS->setMandatoryFilenumber(self::_IDPServiceSettings[self::IDX_S_MANDATORY_FILENUMBER]);
        $idpS->setViewBoxnumber(self::_IDPServiceSettings[self::IDX_S_VIEW_BOXNUMBER]);
        $idpS->setMandatoryBoxnumber(self::_IDPServiceSettings[self::IDX_S_MANDATORY_BOXNUMBER]);
        $idpS->setViewContainernumber(self::_IDPServiceSettings[self::IDX_S_VIEW_CONTAINERNUMBER]);
        $idpS->setMandatoryContainernumber(self::_IDPServiceSettings[self::IDX_S_MANDATORY_CONTAINERNUMBER]);
        $idpS->setViewProvider(self::_IDPServiceSettings[self::IDX_S_VIEW_PROVIDER]);
        $idpS->setMandatoryProvider(self::_IDPServiceSettings[self::IDX_S_MANDATORY_PROVIDER]);
        $idpS->setDefaultLanguage(self::_IDPServiceSettings[self::IDX_S_DEFAULT_LANGUAGE]);
        $idpS->setViewTransferInternalBasket(self::_IDPServiceSettings[self::IDX_S_VIEW_TRANSFER_INTERNAL_BASKET]);
        $idpS->setViewTransferIntermediateBasket(self::_IDPServiceSettings[self::IDX_S_VIEW_TRANSFER_INTERMEDIATE_BASKET]);
        $idpS->setViewTransferProviderBasket(self::_IDPServiceSettings[self::IDX_S_VIEW_TRANSFER_PROVIDER_BASKET]);
        $idpS->setViewRelocInternalBasket(self::_IDPServiceSettings[self::IDX_S_VIEW_RELOC_INTERNAL_BASKET]);
        $idpS->setViewRelocIntermediateBasket(self::_IDPServiceSettings[self::IDX_S_VIEW_RELOC_INTERMEDIATE_BASKET]);
        $idpS->setViewRelocProviderBasket(self::_IDPServiceSettings[self::IDX_S_VIEW_RELOC_PROVIDER_BASKET]);

        $em = $this->getContainer()->get('doctrine')->getManager();
        $em->persist( $idpS );
        $em->flush();

        $output->writeln( '-------------------------------------------------------> <info>Success</info>');
        return true;
    }

    private function initIDPColumns( $output ){
        $output->writeln( '<info>IDPColumns initialization</info>');

        $bsColumnsTable = [];

        $em = $this->getContainer()->get('doctrine')->getManager();
        foreach( self::_IDPColumns as $_idpC ){
            $idpC = new IDPColumns();
            $idpC->setFieldname( $_idpC[ self::IDX_C_FIELD_NAME ] );
            $idpC->setTranslationid($_idpC[self::IDX_C_TRANSLATION_ID]);
            $idpC->setOrderidx($_idpC[self::IDX_C_ORDER_IDX]);
            $idpC->setViewbyconfig($_idpC[self::IDX_C_VIEW_BY_CONFIG]);
            $idpC->setConfigidx($_idpC[self::IDX_C_CONFIG_IDX]);
            $em->persist( $idpC );
            $em->flush();

            $bsColumnsTable[$_idpC[ self::IDX_C_FIELD_NAME ]] = $idpC;

            $localtab = substr( self::tab, 1,strlen(self::tab)-strlen($_idpC[ self::IDX_C_FIELD_NAME ]) );
            $output->writeln( $_idpC[ self::IDX_C_FIELD_NAME ] . $localtab . ': <info>OK</info>' );
        }

        $output->writeln( '-------------------------------------------------------> <info>Success</info>');
        return $bsColumnsTable;
    }

    private function initbsRights( $output ){
        $output->writeln( '<info>bsRights initialization</info>');

        $bsRightsTable = [];

        $em = $this->getContainer()->get('doctrine')->getManager();
        foreach( self::_bsRights as $_bsR ){
            $bsR = new bsRights();
            $bsR->setName( $_bsR[ self::IDX_R_NAME ] );
            $bsR->setDescription($_bsR[self::IDX_R_DESCRIPTION]);
            $bsR->setScale($_bsR[self::IDX_R_SCALE]);
            $em->persist( $bsR );
            $em->flush();

            $bsRightsTable[$_bsR[ self::IDX_R_NAME ]] = $bsR;

            $localtab = substr( self::tab, 1,strlen(self::tab)-strlen($_bsR[ self::IDX_R_NAME ]) );
            $output->writeln( $_bsR[ self::IDX_R_NAME ] . $localtab . ': <info>OK</info>' );
        }

        $output->writeln( '-------------------------------------------------------> <info>Success</info>');
        return $bsRightsTable;
    }

    private function initbsRoles( $bsRightsTable, $output ){
        $output->writeln( '<info>bsRoles initialization</info>');

        $bsRolesTable = [];

        $em = $this->getContainer()->get('doctrine')->getManager();
        foreach( self::_bsRoles as $_bsR ){
            $txt = $_bsR[ self::IDX_R_NAME ];
            $output->write( $txt );

            $bsR = new bsRoles();
            $bsR->setName( $_bsR[ self::IDX_R_NAME ] );
            $bsR->setDescription($_bsR[self::IDX_R_DESCRIPTION]);
            $bsR->setScale($_bsR[self::IDX_R_SCALE]);
            $em->persist( $bsR );
            $em->flush();

            // Affect Rights to Roles (and vice versa, strange Doctrine manner !
            $txtAffectation = ' ';
            $output->write( ' ' );

            $matrix = self::_bsRolesRights[$_bsR[self::IDX_R_NAME]];
            if( !empty($matrix) ){
                foreach( $matrix as $rightName ){
                    $bsR->addRight( $bsRightsTable[$rightName] );
                    $bsRightsTable[$rightName]->addRole( $bsR );
                    $em->persist( $bsR );
                    $em->persist( $bsRightsTable[$rightName] );
                    $txtAffectation.= '.';
                    $output->write( '.' );
                }
                $em->flush();
            }

            $bsRolesTable[$_bsR[self::IDX_R_NAME]] = $bsR;

            $localtab = substr( self::tab, 1,strlen(self::tab)-strlen($txt)-strlen($txtAffectation) );
            $output->writeln( $localtab . ': <info>OK</info>' );
        }

        $output->writeln( '-------------------------------------------------------> <info>Success</info>');
        return $bsRolesTable;
    }

    private function initDefaultUsers( $bsRightsTable, $bsRolesTable, $output, $input ){
        $em = $this->getContainer()->get('doctrine')->getManager();

        $output->writeln( '<info>Default Users initialization</info>');

        $bsUsersTable = [];

        foreach( self::_defaultUsers as $_user ){
            // generate password
            $password = $this->generatePassword(24 );

            $txt = $_user[self::IDX_U_LOGIN];
            $output->write( $txt );

            $user = new bsUsers();
            $user->setFirstname($_user[self::IDX_U_FIRSTNAME]);
            $user->setLastname($_user[self::IDX_U_LASTNAME]);
            $user->setLogin($_user[self::IDX_U_LOGIN]);
            $user->setPassword(md5($password));
            $user->setLastaction($_user[self::IDX_U_LASTACTION]);
            $user->setConnected($_user[self::IDX_U_CONNECTED]);
            $user->setChangepass($_user[self::IDX_U_CHANGEPASS]);
            $user->setFailedconnexioncounter($_user[self::IDX_U_FAILEDCONNEXIONCOUNTER]);
            $user->setPhpsessid($_user[self::IDX_U_PHPSESSID]);
            $em->persist($user);
            $em->flush();

            $txtAffectation = ' ';
            $output->write( ' ' );

            // Set Role
            $role = $bsRolesTable[$_user[self::IDX_U_DEFAULT_ROLE]];
            $user->addRole($role);
            $role->addUser($user);
            $em->persist($role);
            $em->persist($user);
            $em->flush();
            $txtAffectation .= 'X ';
            $output->write( 'X ' );

            // Set Rights
            $matrix = self::_bsRolesRights[$_user[self::IDX_U_DEFAULT_ROLE]];
            if( !empty($matrix) ) {
                foreach ($matrix as $rightName){
                    $right = $bsRightsTable[$rightName];
                    $user->addRight($right);
                    $right->addUser($user);
                    $em->persist($right);
                    $em->persist($user);

                    $txtAffectation .= '.';
                    $output->write( '.' );
                }
                $em->flush();
            }

            $bsUsersTable[$_user[self::IDX_U_LOGIN]] = $user;

            $localtab = substr( self::tab, 1,strlen(self::tab)-strlen($txt)-strlen($txtAffectation) );
            $output->writeln( $localtab . ': <info>OK</info>' );
            $output->writeln( '<error>PASSWORD: '.$password.'</error>' );
        }

        $output->writeln( '-------------------------------------------------------> <info>Success</info>\a');
        $helper = $this->getHelper( 'question' );
        $question = new ConfirmationQuestion('Note password for default users and validate to continue !', false );
        $helper->ask($input, $output, $question);

        return $bsUsersTable;
    }

    private function initIDPUserExtentions( $bsUsersTable, $output ){
        $em = $this->getContainer()->get('doctrine')->getManager();

        $output->writeln( '<info>Users Extention initialization</info>');

        foreach(self::_IPDUserExtentions as $key => $_idpUX){
            $idpUX = new IDPUserExtensions();
            $idpUX->setUser( $bsUsersTable[$key] );
            $idpUX->setInitials( $_idpUX[self::IDX_UX_INITIALS] );
            $idpUX->setUacounter( $_idpUX[self::IDX_UX_UACOUNTER]);
            $idpUX->setLanguage($_idpUX[self::IDX_UX_LANGUAGE]);
            $em->persist( $idpUX );
            $em->flush();

            $localtab = substr( self::tab, 1,strlen(self::tab)-strlen($bsUsersTable[$key]->getLogin()) );
            $output->writeln( $bsUsersTable[$key]->getLogin() . $localtab . ': <info>OK</info>' );
        }

        $output->writeln( '-------------------------------------------------------> <info>Success</info>');
        return true;
    }

    private function initIDPUserPagesSettings( $bsUsersTable, $output ){
        $em = $this->getContainer()->get('doctrine')->getManager();

        $output->writeln( '<info>User Pages Settings initialization</info>');

        $userIdTable = [ 0 ];
        foreach( $bsUsersTable as $user )
            $userIdTable[] = $user->getId();

        $usersPagesSettingsTable = [];

        foreach( $userIdTable as $userId ) {
            $userPSTable = [];
            $txtAffectation = ' ';
            $txt = 'User ' . $userId;
            $output->write( $txt );

            for ($pageId = self::_minPageID; $pageId <= self::_maxPageID; $pageId++) {
                $userPS = new IDPUserPagesSettings();
                $userPS->setUserid( $userId );
                $userPS->setPageid( $pageId );
                $userPS->setNbrowperpage( self::NB_ROW_PER_PAGE );
                $userPS->setArraytypelist( self::ARRAY_TYPE_LIST );
                $em->persist( $userPS );
                $userPSTable[] = $userPS;

                $txtAffectation .= '.';
                $output->write( '.' );
            }
            $em->flush();
            $usersPagesSettingsTable[$userId] = $userPSTable;

            $localtab = substr( self::tab, 1,strlen(self::tab)-strlen($txt)-strlen($txtAffectation) );
            $output->writeln( $localtab . ': <info>OK</info>' );
        }

        $output->writeln( '-------------------------------------------------------> <info>Success</info>');
        return $usersPagesSettingsTable;
    }

    private function initIDPUserColumnsSettings( $bsColumnsTable, $bsUserPagesSettingsTable, $output ){
        $em = $this->getContainer()->get('doctrine')->getManager();

        $output->writeln( '<info>User Columns Settings initialization</info>');

        foreach( $bsUserPagesSettingsTable as $userId => $bsUserPSTs ){
            $output->writeln( 'User '.$userId.' Columns Initialisation' );
            foreach( $bsUserPSTs as $bsUserPST ) {
                if ($bsUserPST->getPageId() <= self::_maxPageWithColumnDefinition) {
                    $txt = ' - Page ' . $bsUserPST->getPageId() . ' : ';
                    $output->write($txt);

                    $bFirst = true;
                    $idxV = 1;
                    $visibles = self::_columnsPage[$bsUserPST->getPageId()];
                    $idxS = sizeof($visibles) + 1;
                    foreach ($bsColumnsTable as $idpColumns) {
                        $userCS = new IDPUserColumnsSettings();
                        $userCS->setColumn($idpColumns);
                        $userCS->setUserpagesettings($bsUserPST);
                        $userCS->setUserid($userId);
                        $isVisible = in_array($idpColumns->getFieldname(), $visibles);
                        $userCS->setVisible($isVisible);
                        $userCS->setSorted($bFirst);
                        $userCS->setSorttypeasc(true);
                        $isSwitchable = in_array($idpColumns->getFieldname(), self::_switchableColumns);
                        $userCS->setSwitchable($isSwitchable);

                        $order = 0;
                        $c = '.';
                        if( $isVisible ){
                            $order = $idxV++;
                            $c = 'v';
                        } elseif( $isSwitchable ){
                            $order = $idxS++;
                            $c = 's';
                        }
                        $userCS->setColumnOrder($order);

                        $txt .= $c;
                        $output->write($c);

                        $em->persist($userCS);
                        $bFirst = false;
                    }
                    $em->flush();

                    $localtab = substr(self::tab, 1, strlen(self::tab) - strlen($txt));
                    $output->writeln($localtab . ': <info>OK</info>');
                }
            }
        }

        $output->writeln( '-------------------------------------------------------> <info>Success</info>');
        return true;
    }

    private function initIDPArchivesStatus( $output ){
        $output->writeln( '<info>IDPArchivesStatus initialization</info>');

        $em = $this->getContainer()->get('doctrine')->getManager();
        foreach( self::_IDPArchivesStatus as $_idpAS ){
            $idpAS = new IDPArchivesStatus();
            $idpAS->setLongname( $_idpAS[ self::IDX_UAS_LONGNAME ] );
            $idpAS->setShortname( $_idpAS[ self::IDX_UAS_SHORTNAME ] );
            $em->persist( $idpAS );
            $em->flush();

            $localtab = substr( self::tab, 1,strlen(self::tab)-strlen($_idpAS[ self::IDX_UAS_SHORTNAME ]) );
            $output->writeln( $_idpAS[ self::IDX_UAS_SHORTNAME ] . $localtab . ': <info>OK</info>' );
        }

        $output->writeln( '-------------------------------------------------------> <info>Success</info>');
        return true;
    }

    private function initbsTranslation( $output ){
        $output->writeln( '<info>bsTranslation initialization</info>');

        $em = $this->getContainer()->get('doctrine')->getManager();

        $lastPage = -1;

        foreach( bsDefaultTranslation::_bsTranslation as $_bsTranslate ){
            $bsTranslate = new bsTranslation();
            $bsTranslate->setPage( $_bsTranslate[ self::IDX_BST_PAGE ] );
            $bsTranslate->setSentence( $_bsTranslate[ self::IDX_BST_SENTENCE ] );
            $bsTranslate->setLanguage( $_bsTranslate[ self::IDX_BST_LANGUAGE ] );
            $bsTranslate->setTranslation( $_bsTranslate[ self::IDX_BST_TRANSLATION ] );
            $em->persist( $bsTranslate );
            $em->flush();

            if( $lastPage != $_bsTranslate[ self::IDX_BST_PAGE ] ) {
                if( $lastPage != 0 ) $output->writeln(' ');
                $output->write( "Page ". $_bsTranslate[ self::IDX_BST_PAGE ] . ": " );
                $lastPage = $_bsTranslate[ self::IDX_BST_PAGE ];
            }
            $output->write( '.' );
        }

        $output->writeln( '-------------------------------------------------------> <info>Success</info>');
        return true;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $globalStatuses = $this->getContainer()->get('doctrine')->getRepository('bsIDPBackofficeBundle:IDPGlobalStatuses')->findOneBy(array('id' => 1 ));
        if( $globalStatuses && $globalStatuses->getReconciliationInProgress() > 0 ) {
            $output->writeln( '<error>Reconciliation in progress (can it be ?), no install can be performed !' );
            return;
        }
        $this->outputHeader( $output );

        $em = $this->getContainer()->get('doctrine')->getManager();

        if( !$this->verifyEmptyDatabase( $output ) ){
            $output->writeln( '-------------------------------------------------------' );
            $output->writeln( '<error>/!\ CRITICAL ERROR </error>' );
            $output->writeln( '<error>The DATABASE is not EMPTY, it cannot be installed !</error>' );
            $output->writeln( '-------------------------------------------------------\a' );
            return;
        }
        $output->writeln( '' );

        if( !$this->initIDPGlobalStatuses( $output ) ){
            $output->writeln( '<error>/!\ CRITICAL ERROR </error>\a' );
            return;
        }
        $output->writeln( '' );

        if( !$this->initIDPMainSettings( $output ) ){
            $output->writeln( '<error>/!\ CRITICAL ERROR </error>\a' );
            return;
        }
        $output->writeln( '' );

        if( !$this->initIDPGlobalSettings( $output ) ){
            $output->writeln( '<error>/!\ CRITICAL ERROR </error>\a' );
            return;
        }
        $output->writeln( '' );

        if( !$this->initIDPServiceSettings( $output ) ){
            $output->writeln( '<error>/!\ CRITICAL ERROR </error>\a' );
            return;
        }
        $output->writeln( '' );

        $bsColumnsTable = $this->initIDPColumns( $output );
        if( empty( $bsColumnsTable) ){
            $output->writeln( '<error>/!\ CRITICAL ERROR </error>\a' );
            return;
        }
        $output->writeln( '' );

        $bsRightsTable = $this->initbsRights( $output );
        if( empty($bsRightsTable) ){
            $output->writeln( '<error>/!\ CRITICAL ERROR </error>\a' );
            return;
        }
        $output->writeln( '' );

        $bsRolesTable = $this->initbsRoles( $bsRightsTable, $output );
        if( empty($bsRolesTable) ){
            $output->writeln( '<error>/!\ CRITICAL ERROR </error>\a' );
            return;
        }
        $output->writeln( '' );

        $bsUsersTable = $this->initDefaultUsers( $bsRightsTable, $bsRolesTable, $output, $input );
        if( empty($bsUsersTable) ){
            $output->writeln( '<error>/!\ CRITICAL ERROR </error>\a' );
            return;
        }
        $output->writeln( '' );

        if( !$this->initIDPUserExtentions( $bsUsersTable, $output ) ){
            $output->writeln( '<error>/!\ CRITICAL ERROR </error>\a' );
            return;
        }
        $output->writeln( '' );

        $bsUserPagesSettingsTable = $this->initIDPUserPagesSettings( $bsUsersTable, $output );
        if( empty( $bsUserPagesSettingsTable) ){
            $output->writeln( '<error>/!\ CRITICAL ERROR </error>\a' );
            return;
        }
        $output->writeln( '' );

        if( !$this->initIDPUserColumnsSettings( $bsColumnsTable, $bsUserPagesSettingsTable, $output ) ){
            $output->writeln( '<error>/!\ CRITICAL ERROR </error>\a' );
            return;
        }
        $output->writeln( '' );

        if( !$this->initIDPArchivesStatus( $output ) ){
            $output->writeln( '<error>/!\ CRITICAL ERROR </error>\a' );
            return;
        }
        $output->writeln( '' );

        if( !$this->initbsTranslation( $output ) ){
            $output->writeln( '<error>/!\ CRITICAL ERROR </error>\a' );
            return;
        }
        $output->writeln( '' );

        $this->outputFooter( $output );

    }


    //----------------------------------------------------------------------------------------------------------------
    // Helper function
    //----------------------------------------------------------------------------------------------------------------
    function generatePassword( $length ) {

        // define variables used within the function
        $symbols = 'abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ1234567890!?@#-+$';   // l and O removed not to be confused with 1 and 0

        //strlen starts from 0 so to get number of characters deduct 1
        $symbols_length = strlen( $symbols ) - 1;

        $password = '';
        for( $i = 0; $i < $length; $i++ ){
            $n = rand( 0, $symbols_length );
            $password .= $symbols[$n];
        }

        return $password;
    }

}

?>