<?php
namespace bs\IDP\ArchiveBundle\ConstantDefinition;

class IDPConstants
{
    // ERROR_WAIT_TIME
    const ERROR_WAIT_TIME                           = 5;

    const ERROR_NO_REDIRECTION                      = -1;

    //---------------------------------------------------------------------------------------------------------
    // AUDIT Actions
    const AUDIT_ACTION_CREATE                       = 1;
    const AUDIT_ACTION_MODIFY                       = 2;
    const AUDIT_ACTION_DELETE                       = 3;
    const AUDIT_ACTION_IMPORT                       = 4;

    // AUDIT Object Type
    const AUDIT_OBJECT_TYPE_UNKNOWN                 = NULL;
    const AUDIT_OBJECT_TYPE_CONTAINER               = 1;
    const AUDIT_OBJECT_TYPE_BOX                     = 2;
    const AUDIT_OBJECT_TYPE_DOCUMENT                = 3;
    
    // AUDIT Which_Entity
    const ENTITY_NA                           = 0;

    const ENTITY_ARCHIVE                      = 1;
    const ENTITY_ARCHIVESTATUS                = 2;        // Useless because not accessible by user
    const ENTITY_BUDGETCODES                  = 3;
    const ENTITY_BUDGETCODESERVICE            = 4;
    const ENTITY_DELIVERADDRESS               = 5;
    const ENTITY_DESCRIPTIONS1                = 6;
    const ENTITY_DESCRIPTION1SERVICE          = 7;
    const ENTITY_DESCRIPTIONS2                = 8;
    const ENTITY_DESCRIPTION2SERVICE          = 9;
    const ENTITY_DOCUMENTNATURES              = 10;
    const ENTITY_DOCUMENTNATURESERVICE        = 11;
    const ENTITY_DOCUMENTNATUREDOCUMENTTYPE   = 12;
    const ENTITY_DOCUMENTTYPES                = 13;
    const ENTITY_LEGALENTITIES                = 14;
    const ENTITY_LEGALENTITYSERVICE           = 15;
    const ENTITY_LOCALIZATIONS                = 16;
    const ENTITY_PROVIDERS                    = 17;
    const ENTITY_PROVIDERSERVICE              = 18;
    const ENTITY_SERVICES                     = 19;
    const ENTITY_SETTINGS                     = 20;
    const ENTITY_USERS                        = 21;
    const ENTITY_USERRIGHT                    = 22;
    const ENTITY_USERROLE                     = 23;
    const ENTITY_USERADDRESS                  = 24;
    const ENTITY_USEREXTENTIONS               = 25;
    const ENTITY_USERSERVICE                  = 26;
    
    // Audit where = entity field
    const FIELD_NA                                      = 0;
    // ENTITY_ARCHIVE
    const FIELD_ARCHIVE_OWNER                           = 1;
    const FIELD_ARCHIVE_LASTACTIONBY                    = 2;
    const FIELD_ARCHIVE_IMPORTID                        = 3;
    const FIELD_ARCHIVE_NAME                            = 4;
    const FIELD_ARCHIVE_STATUS                          = 5;
    const FIELD_ARCHIVE_ORDERNUMBER                     = 6;
    const FIELD_ARCHIVE_BUDGETCODE                      = 7;
    const FIELD_ARCHIVE_LOCALIZATION                    = 8;
    const FIELD_ARCHIVE_LOCALIZATIONFREE                = 9;
    const FIELD_ARCHIVE_OLDLOCALIZATION                 = 10;
    const FIELD_ARCHIVE_OLDLOCALIZATIONFREE             = 11;
    const FIELD_ARCHIVE_CLOSUREYEAR                     = 12;
    const FIELD_ARCHIVE_DESTRUCTIONYEAR                 = 13;
    const FIELD_ARCHIVE_SERVICE                         = 14;
    const FIELD_ARCHIVE_LEGALENTITY                     = 15;
    const FIELD_ARCHIVE_DOCUMENTNATURE                  = 16;
    const FIELD_ARCHIVE_DOCUMENTTYPE                    = 17;
    const FIELD_ARCHIVE_DESCRIPTION1                    = 18;
    const FIELD_ARCHIVE_DESCRIPTION2                    = 19;
    const FIELD_ARCHIVE_LIMITNUMMIN                     = 20;
    const FIELD_ARCHIVE_LIMITNUMMAX                     = 21;
    const FIELD_ARCHIVE_LIMITALPHANUMMIN                = 22;
    const FIELD_ARCHIVE_LIMITALPHANUMMAX                = 23;
    const FIELD_ARCHIVE_LIMITALPHAMIN                   = 24;
    const FIELD_ARCHIVE_LIMITALPHAMAX                   = 25;
    const FIELD_ARCHIVE_LIMITDATEMIN                    = 26;
    const FIELD_ARCHIVE_LIMITDATEMAX                    = 27;
    const FIELD_ARCHIVE_CREATEDAT                       = 28;
    const FIELD_ARCHIVE_MODIFIEDAT                      = 29;
    const FIELD_ARCHIVE_PRECISIONDATE                   = 30;
    const FIELD_ARCHIVE_PRECISIONWHEN                   = 31;
    const FIELD_ARCHIVE_PRECISIONFLOOR                  = 32;
    const FIELD_ARCHIVE_PRECISIONWHERE                  = 33;
    const FIELD_ARCHIVE_PRECISIONOFFICE                 = 34;
    const FIELD_ARCHIVE_PRECISIONWHO                    = 35;
    const FIELD_ARCHIVE_PRECISIONCOMMENT                = 36;
    const FIELD_ARCHIVE_DOCUMENTNUMBER                  = 37;
    const FIELD_ARCHIVE_BOXNUMBER                       = 38;
    const FIELD_ARCHIVE_CONTAINERNUMBER                 = 39;
    const FIELD_ARCHIVE_PROVIDER                        = 40;
    const FIELD_ARCHIVE_LOCKED                          = 41;
    const FIELD_ARCHIVE_LOCKBEGINTIME                   = 42;
    const FIELD_ARCHIVE_UNLIMITED                       = 118;
    const FIELD_ARCHIVE_UNLIMITEDCOMMENTS               = 119;
    const FIELD_ARCHIVE_SERVICEENTRYDATE                = 120;
    // ENTITY_BUDGETCODES
    const FIELD_BUDGETCODE_LONGNAME                     = 43;
    // ENTITY_BUDGETCODESERVICE
    const FIELD_BUDGETCODESERVICE_SERVICE               = 44;
    const FIELD_BUDGETCODESERVICE_BUDGETCODE            = 45;
    // ENTITY_DELIVERADDRESSES
    const FIELD_DELIVERADDRESS_LONGNAME                 = 46;
    // ENTITY_DESCRIPTIONS
    const FIELD_DESCRIPTION1_LONGNAME                   = 47;
    // ENTITY_DESCRIPTION1SERVICE
    const FIELD_DESCRIPTION1SERVICE_SERVICE             = 48;
    const FIELD_DESCRIPTION1SERVICE_DESCRIPTION1        = 49;
    // ENTITY_DESCRIPTIONS2
    const FIELD_DESCRIPTION2_LONGNAME                   = 50;
    // ENTITY_DESCRIPTION2SERVICE
    const FIELD_DESCRIPTION2SERVICE_SERVICE             = 51;
    const FIELD_DESCRIPTION2SERVICE_DESCRIPTION2        = 52;
    // ENTITY_DOCUMENTNATURE
    const FIELD_DOCUMENTNATURE_LONGNAME                 = 53;
    // ENTITY_DOCUMENTNATURESERVICE
    const FIELD_DOCUMENTNATURESERVICE_SERVICE           = 54;
    const FIELD_DOCUMENTNATURESERVICE_DOCUMENTNATURE    = 55;
    // ENTITY_DOCUMENTNATUREDOCUMENTTYPE
    const FIELD_DOCUMENTNATUREDOCUMENTTYPE_DOCUMENTNATURE   = 56;
    const FIELD_DOCUMENTNATUREDOCUMENTTYPE_DOCUMENTTYPE     = 57;
    // ENTITY_LEGALENTITIES
    const FIELD_LEGALENTITY_LONGNAME                    = 58;
    // ENTITY_LEGALENTITYSERVICE
    const FIELD_LEGALENTITYSERVICE_SERVICE              = 59;
    const FIELD_LEGALENTITYSERVICE_LEGALENTITY          = 60;
    // ENTITY_LOCALIZATIONS
    const FIELD_LOCALIZATION_LONGNAME                   = 61;
    const FIELD_LOCALIZATION_LOGO                       = 62;
    // ENTITY_PROVIDERS
    const FIELD_PROVIDER_LONGNAME                       = 63;
    const FIELD_PROVIDER_LOCALIZATION                   = 64;
    // ENTITY_PROVIDERSERVICE
    const FIELD_PROVIDERSERVICE_SERVICE                 = 65;
    const FIELD_PROVIDERSERVICE_PROVIDER                = 66;
    // ENTITY_SERVICES
    const FIELD_SERVICE_LONGNAME                        = 67;
    // ENTITY_SETTINGS
    const FIELD_SETTING_SERVICE                         = 68;
    const FIELD_SETTING_VIEWBUDGETCODE                  = 69;
    const FIELD_SETTING_MANDATORYBUDGETCODE             = 70;
    const FIELD_SETTING_VIEWDOCUMENTNATURE              = 71;
    const FIELD_SETTING_MANDATORYDOCUMENTNATURE         = 72;
    const FIELD_SETTING_VIEWDOCUMENTTYPE                = 73;
    const FIELD_SETTING_MANDATORYDOCUMENTTYPE           = 74;
    const FIELD_SETTING_VIEWDESCRIPTION1                = 75;
    const FIELD_SETTING_MANDATORYDESCRIPTION1           = 76;
    const FIELD_SETTING_VIEWDESCRIPTION2                = 77;
    const FIELD_SETTING_MANDATORYDESCRIPTION2           = 78;
    const FIELD_SETTING_VIEWLIMITSNUM                   = 79;
    const FIELD_SETTING_MANDATORYLIMITSNUM              = 80;
    const FIELD_SETTING_VIEWLIMITSALPHA                 = 81;
    const FIELD_SETTING_MANDATORYLIMITSALPHA            = 82;
    const FIELD_SETTING_VIEWLIMITSALPHANUM              = 83;
    const FIELD_SETTING_MANDATORYLIMITSALPHANUM         = 84;
    const FIELD_SETTING_VIEWLIMITSDATE                  = 85;
    const FIELD_SETTING_MANDATORYLIMITSDATE             = 86;
    const FIELD_SETTING_VIEWDOCUMENTNUMBER              = 87;
    const FIELD_SETTING_MANDATORYDOCUMENTNUMBER         = 88;
    const FIELD_SETTING_VIEWBOXNUMBER                   = 89;
    const FIELD_SETTING_MANDATORYBOXNUMBER              = 90;
    const FIELD_SETTING_VIEWCONTAINERNUMBER             = 91;
    const FIELD_SETTING_MANDATORYCONTAINERNUMBER        = 92;
    const FIELD_SETTING_VIEWPROVIDER                    = 93;
    const FIELD_SETTING_MANDATORYPROVIDER               = 94;
    const FIELD_SETTING_DEFAULTLANGUAGE                 = 95;
    const FIELD_SETTING_VIEWTRANSFERINTERNALBASKET      = 96;
    const FIELD_SETTING_VIEWTRANSFERINTERMEDIATEBASKET  = 97;
    const FIELD_SETTING_VIEWTRANSFERPROVIDERBASKET      = 98;
    const FIELD_SETTING_VIEWRELOCINTERNALBASKET         = 99;
    const FIELD_SETTING_VIEWRELOCINTERMEDIATEBASKET     = 100;
    const FIELD_SETTING_VIEWRELOCPROVIDERBASKET         = 101;
    // ENTITY_USER
    const FIELD_USER_FIRSTNAME                          = 102;
    const FIELD_USER_LASTNAME                           = 103;
    const FIELD_USER_LOGIN                              = 104;
    const FIELD_USER_PASSWORD                           = 105;
    // ENTITY USERRIGHTS
    const FIELD_USERRIGHT_RIGHT                         = 106;
    const FIELD_USERRIGHT_USER                          = 107;
    // ENTITY USERROLES
    const FIELD_USERROLE_ROLE                           = 108;
    const FIELD_USERROLE_USER                           = 109;
    // ENTITY USERADDRESSES
    const FIELD_USERADDRESS_ADDRESS                     = 110;
    const FIELD_USERADDRESS_USER                        = 111;
    // ENTITY USEREXTENTION
    const FIELD_USEREXTENTION_USER                      = 112;
    const FIELD_USEREXTENTION_INITIAL                   = 113;
    const FIELD_USEREXTENTION_UACOUNTER                 = 114;
    const FIELD_USEREXTENTION_LANGUAGE                  = 115;
    // ENTITY USERSERVICE
    const FIELD_USERSERVICE_SERVICE                     = 116;
    const FIELD_USERSERVICE_USER                        = 117;
    //--------------------------------------------------------------------------------------------------

    const UASTATE_MANAGEUSERWANTS                       = 0;
    const UASTATE_MANAGEPROVIDER                        = 1;
    const UASTATE_CLOSEUSERWANTS                        = 2;

    const UAWHAT_TRANSFER                               = 0;
    const UAWHAT_CONSULT                                = 1;
    const UAWHAT_RETURN                                 = 2;
    const UAWHAT_EXIT                                   = 3;
    const UAWHAT_DESTROY                                = 4;
    const UAWHAT_RELOC                                  = 5;

    const UAWHERE_TRANSFER                              = 0;
    const UAWHERE_CONSULT                               = 1;

    // for UAWHAT_TRANSFER
    const UAWHERE_PROVIDER                              = 0;
    const UAWHERE_INTERMEDIATE                          = 1;
    const UAWHERE_INTERNAL                              = 2;

    // For UAWHAT_CONSULT
    const UAWHERE_WITHPREPARATION                       = 0;
    const UAWHERE_WITHOUTPREPARATION                    = 1;

    //E#292 Constant definition
    public static $SAVE_ENTRYDATE_STATUS = [
        'CRLIDAINT', 'CRLIDAP', 'CRLIDAI', 'CRLINTDAINT', 'CRLINTDAP', 'CRLINTDAINT' ];
    public static $RESET_ENTRYDATE_STATUS = [
        'CRLIDINT', 'CRLIDP', 'CRLIDI', 'CRLINTDI', 'CRLINTDP', 'CRLINTDINT' ];



    public static $FILTER_STATUS = [
        // UASTATE = Manage User Wants
        [
            // A transférer
            [ 	// Prestataire, Intermédiaire, Interne
                [ 'DTRP' ],
                [ 'DTRINT' ],
                [ 'DTRI' ]
            ],
            // A livrer
            [ // Sans préparation, avec préparation spécifique
                [ 'CLAI', 'CLAINT', 'CLAP' ],
                [ 'CPAI', 'CPAINT', 'CPAP' ]
            ],
            // A retourner
            [ 'CRAI', 'CRAINT', 'CRAP', 'CRAPCONRIDISP', 'CRAPCONRINTDISP', 'CRAPCONRICONP', 'CRAPCONRINTCONP' ],
            // A sortir définitivement
            [ 'CSAI', 'CSAINT', 'CSAP' ],
            // A détruire
            [ 'CDAI', 'CDAINT', 'CDAP' ],
            // A relocaliser
            [
                [ 'CRLIDAP', 'CRLINTDAP' ],
                [ 'CRLIDAINT', 'CRLINTDAINT', 'CRLPDAINT', 'CRLICAINT', 'CRLINTCAINT', 'CRLPCAINT' ],
                [ 'CRLIDAI', 'CRLINTDAI', 'CRLPDAI', 'CRLICAI', 'CRLINTCAI', 'CRLPCAI' ]
            ]
        ],
        // UASTATE = Manage provider
        [
            // A transférer
            [ 'GDTRP' ],
            // A livrer
            [ 'GLAP', 'GPAP' ],
            // A retourner
            [ 'GRAP', 'GRAPCONRIDISP', 'GRAPCONRINTDISP', 'GRAPCONRICONP', 'GRAPCONRINTCONP' ],
            // A sortir définitivement
            [ 'GSAP' ],
            // A détruire
            [ 'GDAP' ],
            // A relocaliser
            [   // New, Consult
                [ 'GRLIDAP', 'GRLINTDAP' ],
                [ 'GRLPDAI', 'GRLPDAINT' ]
            ]
        ],
        // UASTATE = Close user wants
        [
            // A transférer
            [ // Prestataire, Intermédiaire, Interne
                [ 'CDTRP' ],
                [ 'CDTRINT' ],
                [ 'CDTRI' ]
            ],
            // A livrer
            [ // Sans préparation, avec préparation spécifique
                [ 'CLII', 'CLIINT', 'CLIP' ],
                [ 'CPRI', 'CPRINT', 'CPRP' ]
            ],
            // A retourner
            [ 'CRTI', 'CRTINT', 'CRTP', 'CRTPCONRIDISP', 'CRTPCONRINTDISP', 'CRTPCONRICONP', 'CRTPCONRINTCONP' ],
            // A sortir définitivement
            [ 'CSDI', 'CSDINT', 'CSDP' ],
            // A détruire
            [ 'CDEI', 'CDEINT', 'CDEP' ],
            // A relocaliser
            [
                [ 'CRLIDP', 'CRLINTDP' ],
                [ 'CRLIDINT', 'CRLINTDINT', 'CRLPDINT', 'CRLICINT', 'CRLINTCINT', 'CRLPCINT' ],
                [ 'CRLIDI', 'CRLINTDI', 'CRLPDI', 'CRLICI', 'CRLINTCI', 'CRLPCI' ]
            ]
        ]
    ];

    // List of all status where containernumber, boxnumber, documentnumber and provider are disabled during relocalisation
    // Same in IDPConstants.js
    public static $RELOC_FIELD_DISABLED_STATUS = [
        'CRLPDAI', 'GRLPDAI', 'CRLPDI', 'CONRIDISP',
        'CRLPDAINT', 'GRLPDAINT', 'CRLPDINT', 'CONRINTDISP',
        'CRLPCAI', 'CRLPCI', 'CONRICONP',
        'CRLPCAINT', 'CRLPCINT', 'CONRINTCONP',
        'CRAPCONRIDISP', 'GRAPCONRIDISP', 'CRTPCONRIDISP',
        'CRAPCONRINTDISP', 'GRAPCONRINTDISP', 'CRTPCONRINTDISP',
        'CRAPCONRICONP', 'GRAPCONRICONP', 'CRTPCONRICONP',
        'CRAPCONRINTCONP', 'GRAPCONRINTCONP', 'CRTPCONRINTCONP'
        ];

    // List of status for closure Internal, intermdiate, provider
    public static $CLOSURE_INTERNAL_STATUS = [
        'CDEI', 'CDTRI', 'CLII', 'CPRI', 'CSDI', 'CRTI', 'CRTPCONRIDISP', 'CRTPCONRICONP', 'CRLIDINT', 'CRLIDP', 'CRLIDI', 'CRLICI', 'CRLICINT'
    ];
    public static $CLOSURE_INTERMEDIATE_STATUS = [
        'CDEINT', 'CDTRINT', 'CLIINT', 'CPRINT', 'CSDINT', 'CRTINT', 'CRTPCONRINTDISP', 'CRTPCONRINTCONP', 'CRLINTDINT', 'CRLINTDP', 'CRLINTDI', 'CRLINTCI', 'CRLINTCINT'
    ];
    public static $CLOSURE_PROVIDER_STATUS = [
        'CDEP', 'CDTRP', 'CLIP', 'CPRP', 'CSDP', 'CRTP', 'CRLPDI', 'CRLPDINT', 'CRLPCI', 'CRLPCINT'
    ];

    // List of status where to unset LastActionBy when movement go to in Action or Cancel mode
    public static $UNSET_ACTIONBY_STATUS = [ 'DTA',
        'DISP', 'DISI', 'DISINT', 'CONI', 'CONINT', 'CONP', 'CONRICONP', 'CONRINTCONP', 'CONRIDISP', 'CONRINTDISP',
        'EDEI', 'EDEINT', 'EDEP', 'ESDI', 'ESDINT', 'ESDP'
    ];

    // List of count to be made for frontpage
    public static $COUNT_STATUS = [
        [ 'DTRI', 'DTRINT', 'DTRP', 'GDTRP', 'CDTRI', 'CDTRINT', 'CDTRP' ],                                                 // Demandes de transfert
        [ 'CLAI', 'CPAI', 'CLAINT', 'CPAINT', 'CLAP', 'CPAP', 'GLAP', 'GPAP', 'CLII', 'CPRI', 'CLIINT', 'CPRINT',           // Demandes de consultation
            'CLIP', 'CPRP' ],
        [ 'CRAI', 'CRAINT', 'CRAP', 'GRAP', 'CRTI', 'CRTINT', 'CRTP', 'CRAPCONRIDISP', 'GRAPCONRIDISP', 'CRTPCONRIDISP',    // Demandes de retour
            'CRAPCONRINTDISP', 'GRAPCONRINTDISP', 'CRTPCONRINTDISP', 'CRAPCONRICONP', 'GRAPCONRICONP', 'CRTPCONRICONP',
            'CRAPCONRINTCONP', 'GRAPCONRINTCONP', 'CRTPCONRINTCONP' ],
        [ 'CSAI', 'CSAINT', 'CSAP', 'GSAP', 'CSDI', 'CSDINT', 'CSDP' ],                                                     // Demandes de sortie définitive
        [ 'CDAI', 'CDAINT', 'CDAP', 'GDAP', 'CDEI', 'CDEINT', 'CDEP' ],                                                     // Demandes de destruction
        [ 'CRLIDAINT', 'CRLIDAP', 'CRLIDAI', 'GRLIDAP', 'CRLIDINT', 'CRLIDP', 'CRLIDI', 'CRLINTDAI', 'CRLINTDAP',           // Demandes de reloccalisation
            'CRLINTDAINT', 'GRLINTDAP', 'CRLINTDI', 'CRLINTDP', 'CRLINTDINT', 'CRLPDAI', 'CRLPDAINT', 'GRLPDAI',
            'GRLPDAINT', 'CRLPDI', 'CRLPDINT', 'CRLICAI', 'CRLICAINT', 'CRLICI', 'CRLICINT', 'CRLINTCAINT', 'CRLINTCAI',
            'CRLINTCINT', 'CRLINTCI', 'CRLPCAI', 'CRLPCAINT', 'CRLPCI', 'CRLPCINT' ]
    ];

    // List of statuses targetted where an optimization has potentially occured
    public static $MOVEMENT_WHERE_OBJECT_TYPE_IS_BASED_ON_OPTIMISATION = [ 'CLIP', 'CPRP', 'CRLPDI', 'CRLPDINT' ];
    // List of statuses targetted where the object type is obvious
    public static $MOVEMENT_WHERE_OBJECT_TYPE_IS_OBVIOUS = [ 'CDTRI', 'CDTRINT', 'CDEI', 'CDEINT',
        'CSDI', 'CSDINT', 'CRLIDINT', 'CRLIDI', 'CRLINTDI', 'CRLINTDINT', 'CRLICI', 'CRLICINT',
        'CRLINTCINT', 'CRLINTCI',
        'GDAP', 'GDTRP', 'GSAP', 'GRLIDAP', 'GRLINTDAP' ];

    // ERROR CODES RANGE
    // IDPUserSettingsManagement ==> E01000 to E01999

    // IDPReconciliation ==> E02000 to E02999

    // Print Consts
    public static $PRINT_OFFLINE_READABLE_NAME = [ '',
        'Transférer', 'Consulter', 'Retourner', 'Sortir définitivement', 'Détruire', 'Relocaliser', 'Illimités',
        'Valider Transférer Prestataire', 'Valider Transférer Intermédiaire', 'Valider Transférer Interne',
        'Valider Consulter Sans Préparation', 'Valider Consulter Avec Préparation', 'Valider Retourner',
        'Valider Sortir Définitivement', 'Valider Détruire', 'Valider Relocaliser Prestataire',
        'Valider Relocaliser Intermédiaire', 'Valider Relocaliser Interne', 'Gérer Transférer',
        'Gérer Consulter', 'Gérer Retourner', 'Gérer Sortir Définitivement', 'Gérer Détruire', 'Gérer Relocaliser',
        'Clôturer Transférer Prestataire', 'Clôturer Transférer Intermédiaire', 'Clôturer Transférer Interne',
        'Clôturer Consulter Sans Préparation', 'Clôturer Consulter Avec Préparation', 'Clôturer Retourner',
        'Clôturer Sortir Définitivement', 'Clôturer Relocaliser Prestataire', 'Clôturer Relocaliser Intermédiaire',
        'Clôturer Relocaliser Interne'
    ];

};


?>