<?php
// (c) Archimage - IDP Consulting 2018
// Author: Cyril PATRIGEON - BeSecure Labs
//

namespace bs\Core\AdminBundle\Security;

class IDPArchimageRights
{
    // ---------------------------------------------------------------------------------------------------------------
    // RIGHTS

    const RIGHT_ID = 0;
    const RIGHT_STR = 1;
    const RIGHT_LINK = 2;

    const RIGHT_ARCHIVE_NEW             = 0;
    const RIGHT_TRANSFER                = 1;
    const RIGHT_RELOC                   = 2;
    const RIGHT_CONSULT                 = 3;
    const RIGHT_DELETE                  = 4;
    const RIGHT_RETURN                  = 5;
    const RIGHT_EXIT                    = 6;
    const RIGHT_COMMAND                 = 7;
    const RIGHT_FOLLOW_COMMAND          = 8;
    const RIGHT_VALIDATE_USER_ASKS      = 9;
    const RIGHT_MANAGE_PROVIDER_WANTS   = 10;
    const RIGHT_CLOSE_USER_WANTS        = 11;
    const RIGHT_MANAGE_BDD              = 12;
    const RIGHT_AUDIT                   = 13;
    const RIGHT_AUDIT_ADV               = 14;
    const RIGHT_STAT                    = 15;
    const RIGHT_UNLIMITED               = 16;
    const RIGHT_ACTIVITY_SYNTHESIS      = 17;
    const RIGHT_EXPORT_ALL              = 18;
    const RIGHT_IMPORT                  = 19;
    const RIGHT_IMPORTS_REPORT          = 20;
    const RIGHT_MANAGE_COMMAND          = 21;
    const RIGHT_MANAGE_CATALOG          = 22;
    const RIGHT_MANAGE_USERS            = 23;
    const RIGHT_MANAGE_SETTINGS         = 24;
    const RIGHT_RECONCILIATION          = 25;

    const RIGHTS = [
        [ 'RIGHT_ARCHIVE_NEW',              'Saisir',                                       IDPArchimageRights::RIGHT_ARCHIVE_NEW ],
        [ 'RIGHT_TRANSFER',                 'Transférer',                                   IDPArchimageRights::RIGHT_TRANSFER ],
        [ 'RIGHT_RELOC',                    'Relocaliser',                                  IDPArchimageRights::RIGHT_RELOC ],
        [ 'RIGHT_CONSULT',                  "Consulter une unité d'archives",               IDPArchimageRights::RIGHT_CONSULT ],
        [ 'RIGHT_DELETE',                   "Détruire une unité d'archives",                IDPArchimageRights::RIGHT_DELETE ],
        [ 'RIGHT_RETURN',                   "Retourner une unité d'archives",               IDPArchimageRights::RIGHT_RETURN ],
        [ 'RIGHT_EXIT',                     "Sortir définitivement une unité d'archives",   IDPArchimageRights::RIGHT_EXIT ],
        [ 'RIGHT_COMMAND',                  'Passer une commande',                          IDPArchimageRights::RIGHT_COMMAND ],
        [ 'RIGHT_FOLLOW_COMMAND',           'Suivre vos commandes',                         IDPArchimageRights::RIGHT_FOLLOW_COMMAND ],
        [ 'RIGHT_VALIDATE_USER_ASKS',       'Valider les demandes des utilisateurs',        IDPArchimageRights::RIGHT_VALIDATE_USER_ASKS ],
        [ 'RIGHT_MANAGE_PROVIDER_WANTS',    'Gérer les demandes des prestataires',          IDPArchimageRights::RIGHT_MANAGE_PROVIDER_WANTS ],
        [ 'RIGHT_CLOSE_USER_WANTS',         'Clôturer les demandes des utilisateurs',       IDPArchimageRights::RIGHT_CLOSE_USER_WANTS ],
        [ 'RIGHT_MANAGE_BDD',               'Gérer la base archive',                        IDPArchimageRights::RIGHT_MANAGE_BDD ],
        [ 'RIGHT_AUDIT',                    "Accéder à l'audit",                            IDPArchimageRights::RIGHT_AUDIT ],
        [ 'RIGHT_AUDIT_ADV',                "Accéder à l'audit (noms)",                     IDPArchimageRights::RIGHT_AUDIT_ADV ],
        [ 'RIGHT_STAT',                     'Consulter les statistiques',                   IDPArchimageRights::RIGHT_STAT ],
        [ 'RIGHT_UNLIMITED',                'Gérer les conservations illimitées',           IDPArchimageRights::RIGHT_UNLIMITED ],
        [ 'RIGHT_ACTIVITY_SYNTHESIS',       "Voir la synthèse d'activité",                  IDPArchimageRights::RIGHT_ACTIVITY_SYNTHESIS ],
        [ 'RIGHT_EXPORT_ALL',               'Exporter toutes les données',                  IDPArchimageRights::RIGHT_EXPORT_ALL ],
        [ 'RIGHT_IMPORT',                   'Importer les données',                         IDPArchimageRights::RIGHT_IMPORT ],
        [ 'RIGHT_IMPORTS_REPORT',           'Accéder au rapport des imports',               IDPArchimageRights::RIGHT_IMPORTS_REPORT ],
        [ 'RIGHT_MANAGE_COMMAND',           'Gérer les commandes',                          IDPArchimageRights::RIGHT_MANAGE_COMMAND ],
        [ 'RIGHT_MANAGE_CATALOG',           'Gérer le catalogue',                           IDPArchimageRights::RIGHT_MANAGE_CATALOG ],
        [ 'RIGHT_MANAGE_USERS',             'Gérer les utilisateurs',                       IDPArchimageRights::RIGHT_MANAGE_USERS ],
        [ 'RIGHT_MANAGE_SETTINGS',          'Gérer les paramètres',                         IDPArchimageRights::RIGHT_MANAGE_SETTINGS ],
        [ 'RIGHT_RECONCILIATION',           'Rapprocher les stocks',                        IDPArchimageRights::RIGHT_RECONCILIATION ]
    ];

    // ---------------------------------------------------------------------------------------------------------------
    // ROLES
    const ROLE_ID = 0;
    const ROLE_STR = 1;
    const ROLE_SCALE = 2;
    const ROLE_DEFAULT_RIGHTS = 3;
    const ROLE_LINK = 4;

    const ROLE_USER = 0;
    const ROLE_SUPER_USER = 1;
    const ROLE_ARCHIVIST = 2;
    const ROLE_SUPER_ARCHIVIST = 3;
    const ROLE_ADMIN = 4;
    const ROLE_SUPER_ADMIN = 5;

    const ROLES = [
        [ 'ROLE_USER',              'Utilisateur',          125,  [ 0, 1, 3, 4, 5, 6 ],                                                                             IDPArchimageRights::ROLE_USER ],
        [ 'ROLE_SUPER_USER',        'Super utilisateur',    100,  [ 0, 1, 2, 3, 4, 5, 6, 7, 8 ],                                                                    IDPArchimageRights::ROLE_SUPER_USER ],
        [ 'ROLE_ARCHIVIST',         'Archiviste',           75,   [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 16, 21 ],                                                 IDPArchimageRights::ROLE_ARCHIVIST ],
        [ 'ROLE_SUPER_ARCHIVIST',   'Super archiviste',     50,   [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 13, 15 ,16 ,17, 21 ],                                     IDPArchimageRights::ROLE_SUPER_ARCHIVIST ],
        [ 'ROLE_ADMIN',             'Administrateur',       25,   [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 21, 25 ],                         IDPArchimageRights::ROLE_ADMIN ],
        [ 'ROLE_SUPER_ADMIN',       'Super administrateur', 0,    [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23 , 24, 25 ],IDPArchimageRights::ROLE_SUPER_ADMIN ]
    ];
};