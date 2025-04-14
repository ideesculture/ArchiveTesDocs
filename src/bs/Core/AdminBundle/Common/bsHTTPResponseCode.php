<?php
namespace bs\IDP\ArchiveBundle\Common;

class bsHTTPResponseCode
{
    // 1xx: Information
    const HTTP_100_Continue                         = 100;  // Attente de la suite de la requête
    const HTTP_101_SwitchingProtocol                = 101;  // Acceptation du changement de protocole
    const HTTP_102_Processing                       = 102;  // WebDAV RFC 2518: Traitement en cours
    const HTTP_103_EarlyHints                       = 103;  // RFC 8297: (expérimental) dans l'attente de la réponse définitive, le serveur retourne des liens que le client peut commencer à télécharger

    // 2xx: Succès
    const HTTP_200_Ok                               = 200;  // Requête traitée avec succès. La réponse dépendra de la méthode de requête utilisée
    const HTTP_201_Created                          = 201;  // Requête traitée avec succès et création d'un document
    const HTTP_202_Accepted                         = 202;  // Requête traitée, mais sans garantie de résultat
    const HTTP_203_NonAuthoritativeInformation      = 203;  // Information retournée, mais générée par une source non certifiée
    const HTTP_204_NoContent                        = 204;  // Requête traitée avec succès mais pas d'information à renvoyer
    const HTTP_205_ResetContent                     = 205;  // Requête traitée avec succès, la page courante peut être effacée.
    const HTTP_206_PartialContent                   = 206;  // Une partie sulement de la ressource a été transmise
    const HTTP_207_MultiStatus                      = 207;  // WebDAV: Réponse multiple
    const HTTP_208_AlreadyReported                  = 208;  // WebDAV: Le document a été envoyé précédemment dans cette collection
    const HTTP_210_ContentDifferent                 = 210;  // WebDAV: La copie de la ressource côté client diffère de celle du serveur (contenu ou propriétés)
    const HTTP_226_IMUsed                           = 226;  // RFC 3229: Le serveur a accompli la requête pour la ressource, et la réponse est une réprésenation du résultat d'une ou plusieurs manipulations d'instances appliquées à l'instance actuelle

    // 3xx: Redirection
    const HTTP_300_MultipleChoices                  = 300;  // L'URI demandée se rapporte à plusieurs ressources
    const HTTP_301_MovedPermanently                 = 301;  // Document déplacé de façon permanente
    const HTTP_302_Found                            = 302;  // Document déplacé de façon temporaire
    const HTTP_303_SeeOther                         = 303;  // La réponse à cette requête est ailleurs
    const HTTP_304_NotModified                      = 304;  // Document non modifié depuis la dernière requête
    const HTTP_305_UseProxy                         = 305;  // La requête doit être ré-adressée au proxy
    const HTTP_306_SwitchProxy                      = 306;  // Code utilisé par une ancienne version de la RFC 2616 , à présent réservé. Elle signifiait "Les requêtes suivantes doivent utiliser le proxy spécifié"
    const HTTP_307_TemporaryRedirect                = 307;  // La requête doit être redirigée temporairement vers l'URI spécifiée
    const HTTP_308_PermanentRedirect                = 308;  // La requête doit être redirigée définitivement vers l'URI spécifiée
    const HTTP_310_TooManyRedirects                 = 310;  // La requête doit être redirigée de trop nombreuses fois, ou est victime d'une boucle de redirection

    // 4xx: Erreur du client web
    const HTTP_400_BadRequest                       = 400;  // La syntaxe de la requête est erronée
    const HTTP_401_Unauthorized                     = 401;  // Une authentification est nécessaire pour éccéder à la ressource
    const HTTP_402_PaymentRequired                  = 402;  // Paiement requis pour accéder à la ressource
    const HTTP_403_Forbidden                        = 403;  // Le serveur a compris la requête, mais refuse de l'exécuter. Contrairement à l'erreur 401, s'authentifier ne fera aucune différence. Sur les serveurs où l'authentification est requise, cela signifie généralement que l'authentification a été acceptée mais que les droits d'accès ne permettent pas au client d'accéder à la ressource.
    const HTTP_404_NotFound                         = 404;  // Ressource non trouvée
    const HTTP_405_MethodNotFound                   = 405;  // Méthode de requête non autorisée
    const HTTP_406_NotAcceptable                    = 406;  // La ressource demandée n'est pas disponible dans un format qui respecterait les en-têtes "Accept" de la requête
    const HTTP_407_ProxyAuthenticationRequired      = 407;  // Accès à la ressource autorisé par identifiaction avec le proxy
    const HTTP_408_RequestTimeOut                   = 408;  // Temps d'attente d'une requête du client, écoulé côté serveur. D'après les spécifications HTTP "Le client n'a pas produit de requête dans le délai que le serveur était prêt à attendre. Le client PEUT répéter la demande sans modification à tout moment ultérieur"
    const HTTP_409_Conflict                         = 409;  // La requête ne peut pas être traitée en l'état actuel
    const HTTP_410_Gone                             = 410;  // La ressource n'est plus disponible et aucune adresse de redirection n'est connue
    const HTTP_411_LengthRequired                   = 411;  // La longueur de la requête n'a pas été précisée
    const HTTP_412_PreconditionFailed               = 412;  // Préconditions envoyées par la requête non vérifiées
    const HTTP_413_RequestEntityTooLarge            = 413;  // Traitement abandonné dû à une requête trop importante
    const HTTP_414_RequestURITooLong                = 414;  // URI trop longue
    const HTTP_415_UnsupportedMediaType             = 415;  // Format de requête non supporté pour une méthode et une ressource donnée
    const HTTP_416_RequestedRangeUnsatisfiable      = 416;  // Champs d'en-tête de requête "range" incorrect
    const HTTP_417_ExpectationFailed                = 417;  // Comportement attendu et défini dans l'en-tête de la requête insatisfaisante
    const HTTP_418_ImATeapot                        = 418;  // "Je suis une théière": Ce code est défini dans la RFC 2324 datée du premier avril 1998, Hyper Text Coffee Pot Control Protocol
    const HTTP_421_BadMappingMisdirectedRequest     = 421;  // La requête a été envoyée à un serveur qui n'est pas capable de produire une réponse (par exemple, car une connexion a été réutilisée)
    const HTTP_422_UnprocessableEntity              = 422;  // WebDAV: L'entité fournie avec la requête est incompréhensible ou incomplète
    const HTTP_423_Locked                           = 423;  // WebDAV: L'opération ne peut avoir lieu car la ressource est vérouillée
    const HTTP_424_MethodFailure                    = 424;  // WebDAV: Une méthode de la transaction a échoué
    const HTTP_425_UnorderedCollection              = 425;  // WebDAV RFC 3648: Ce code est défini dans le brouillon WebDAV Advanced Collections Protocol, mais est absent de Web Distributed Authoring and Versioning (WebDAV) Ordered Collections Protocol
    const HTTP_426_UpgradeRequired                  = 426;  // RFC 2817: Le client devrait changer de protocole, par exemple au profit de TLS/1.0
    const HTTP_428_PreconditionRequired             = 428;  // RFC 6585: La requête doit être conditionnelle
    const HTTP_429_TooManyRequests                  = 429;  // RFC 6585: Le client a émis trop de requêtes dans un délai donné
    const HTTP_431_RequestHeaderFieldsTooLarge      = 431;  // RFC 6585: Les entêtes HTTP émises dépassent la taille maximale admise par le serveur
    const HTTP_444_NoResponse                       = 444;  // Indique que le serveur n'a retourné aucune information vers le client et a fermé la connexion
    const HTTP_449_RetryWith                        = 449;  // Code défini par Microsoft. La requête devrait être renvoyée après avoir effectué une action
    const HTTP_450_BlockedByWindowsParentalControls = 450;  // Code défini par Microsoft. Cette erreur est produite lorsque les outils de contrôle parental de Windows sont activés et bloquent l'accès à la page
    const HTTP_451_UnavailableForLegalReasons       = 451;  // Ce code d'erreur indique que la ressource demandée est inaccessible pour des raisons d'ordre légal
    const HTTP_456_UnrecoverableError               = 456;  // WebDAV: Erreur irrécupérable
    const HTTP_495_SSLCertificateError              = 495;  // Une extension de l'erreur 400 Bad Request, utilisée lorsque le client a fourni un certificat invalide
    const HTTP_496_SSLCertificateRequired           = 496;  // Une extension de l'erreur 400 Bad Request, utilisée lorsqu'un certificat client requis n'est pas fourni
    const HTTP_497_HTTPRequestSentToHTTPSPort       = 497;  // Une extension de l'erreur 400 Bad Request, utilisée lorsque le client envoie une requête HTTP vers le port 443 normalement destiné aux requêtes HTTPS
    const HTTP_498_TokenExpiredInvalid              = 498;  // Le jeton a expiré ou est invalide
    const HTTP_499_ClientClosedRequest              = 499;  // Le client a fermé la connexion avant de recevoir la réponse. Cette erreur se produit quand le traitement est trop long côté serveur

    // 5xx Erreur du serveur / du serveur d'application
    const HTTP_500_InternalServerError              = 500;  // Erreur interne du serveur
    const HTTP_501_NotImplemented                   = 501;  // Fonctionnalité réclamée non supportée par le serveur
    const HTTP_502_BadGatewayOrProxyError           = 502;  // En agissant en tant que serveur proxy ou passerelle, le serveur a reçu une réponse invalide depuis le serveur distant
    const HTTP_503_ServiceUnavailable               = 503;  // Service temporairement indisponible ou en maintenance
    const HTTP_504_GatewayTimeout                   = 504;  // Temps d'attente d'une réponse d'un serveur à un serveur intermédiaire écoulé
    const HTTP_505_HTTPVersionNotSupported          = 505;  // Version HTTP non gérée par le serveur
    const HTTP_506_VariantAlsoNegotiates            = 506;  // RFC 2295: Erreur de négociation. Transparent content negociation
    const HTTP_507_InsufficientStorage              = 507;  // WebDAV: Espace insuffisant pour modifier les propriétés ou construire la collection
    const HTTP_508_LoopDetected                     = 508;  // WebDAV: Boucle dans une mise en relation de ressources (RFC 5842)
    const HTTP_509_BandwithLimitExceeded            = 509;  // Utilisé par de nombreux serveurs pour indiquer un dépassement de quota
    const HTTP_510_NotExtended                      = 510;  // RFC 2774: La requête ne respecte pas la politique d'accès aux ressources HTTP étendues
    const HTTP_511_NetworkAuthenticationRequired    = 511;  // RFC 6585: L eclient doit s'authentifier pour accéder au réseau. Utilisé par les portails captifs pour rediriger les clients vers la page d'authentification
    
}