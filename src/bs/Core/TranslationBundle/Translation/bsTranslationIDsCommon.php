<?php
namespace bs\Core\TranslationBundle\Translation;

class bsTranslationIDsCommon {

	// --== TRANSLATION FOR SYSTEM NOTIFICATION ==--
	const T_SYSTEM = 0;

	const T_SYSTEM_ACCESSDENIED = 1; // Acc�s interdit, vous n\'avez pas les droits suffisants pour acc�der �
	const T_SYSTEM_SERVICEIDERROR = 2; // 'System Error : Service Id Unknown'
	const T_SYSYEM_LEGALENTITYIDERROR = 3; // 'System Error : Legal Entity Id Unknown'
	const T_SYSTEM_BUDGETCODEIDERROR = 4; // 'System Error : Budget Code Id Unknown'
	const T_SYSTEM_DOCUMENTNATUREIDERROR= 5; // 'System Error : Document Nature Id Unknown'
	const T_SYSTEM_DOCUMENTTYPEIDERROR = 6; // 'System Error : Document Type Id Unknown'
	const T_SYSTEM_DESCRIPTION1IDERROR = 7; // 'System Error : Description 1 Id Unknown'
	const T_SYSTEM_DESCRIPTION2IDERROR = 8; // 'System Error : Description 2 Id Unknown'
	const T_SYSTEM_PROVIDERIDERROR = 9; // 'System Error : Provider Id Unknown'
	const T_SYSTEM_ARCHIVEIDERROR = 10; // 'System Error : Archive Id Unknown'
	const T_SYSTEM_SUCCESSARCHIVEVALIDATE = 11; // "L'archive a �t� valid�e avec succ�s"
	const T_SYSTEM_SUCCESSARCHIVESAVE = 12; // "L'archive a �t� sauvegard�e avec succ�s"
	const T_SYSTEM_ERRORPOSTREQUIRED = 13; // 'System ERROR, POST required '
	const T_SYSTEM_ERRORARCHIVEMODIFYUNKNOWN = 14; // "L'archive demand�e en modification n'existe pas !"
	const T_SYSTEM_SUCCESSMODIFYARCHIVE = 15; // "La modification de l'archive demand�e est effectu�e"
	const T_SYSTEM_SUCCESSASKTRANSFER = 16; // "Votre demande de transfert a bien �t� enregistr�e, vos archives seront prises en charge prochainement"
	const T_SYSTEM_SUCCESSASKCONSULT = 17; // "Votre demande de consultation a bien �t� enregistr�e, vos archives seront prises en charge prochainement"
	const T_SYSTEM_SUCCESSASKRETURN = 18; // "Votre demande de retour a bien �t� enregistr�e, vos archives seront prises en charge prochainement"
	const T_SYSTEM_SUCCESSASKEXIT = 19; // "Votre demande de r�cup�ration d�finitive a bien �t� enregistr�e, vos archives seront prises en charge prochainement"
	const T_SYSTEM_SUCCESSASKDELETE = 20; // "Votre demande de destruction a bien �t� enregistr�e, vos archives seront prises en charge prochainement"

	// --== TRANSLATION IDs for DASHBOARD Bundle ==--

	// mini.html.twig sentences
	const T_HEAD_MINI = 1;

	const T_MINI_PAGETITLE = 1; // IDP Consulting Archive Management Tool
	const T_MINI_TITLE = 2; // IDP Consulting
	const T_MINI_HELP = 3; // Aide
	const T_MINI_IMLOST = 4; // Je suis perdu
	const T_MINI_PARAMETER = 5; // Param�tres
	const T_MINI_DISCONNECT = 6; // D�connexion
	const T_MINI_COPYRIGHT = 7; // IDP Consulting 2015

	// base.html.twig
	const T_HEAD_BASE = 2;

	const T_BASE_PAGETITLE = 1; // IDP Consulting Archive Management Tool
	const T_BASE_TITLE = 2; // IDP Consulting
	const T_BASE_HELP = 3; // Aide
	const T_BASE_IMLOST = 4; // Je suis perdu(e)
	const T_BASE_PARAMETER = 5; // Param�tres
	const T_BASE_DISCONNECT = 6; // D�connexion
	const T_BASE_DASHBOARD = 7; // Accueil
	const T_BASE_ARCHIVE = 8; // Archives
	const T_BASE_ENTER = 9; // Saisir
	const T_BASE_TRANSFER = 10; // Transf�rer
	const T_BASE_CONSULT = 11; // Consulter
	const T_BASE_RETURN = 12; // Retourner
	const T_BASE_EXIT = 13; // R�cup�rer
	const T_BASE_DELETE = 14; // D�truire
	const T_BASE_FURNITURES = 15; // Fournitures
	const T_BASE_ORDER = 16; // Commander
	const T_BASE_ORDERED = 17; // En cours
	const T_BASE_MANAGEORDER = 18; // G�rer les commandes
	const T_BASE_MANAGEUA = 19; // Gestion des archives
	const T_BASE_MANAGEUSERWANTS = 20; // G�rer les demandes des utilisateurs
	const T_BASE_MANAGEDB = 21; // G�rer la base archives
	const T_BASE_STATISTICS = 22; // Consulter les statistiques
	const T_BASE_EXPORT = 23; // Exporter toutes les donn�es
	const T_BASE_COPYRIGHT = 24; // IDP Consulting 2015
	const T_BASE_IMPORT = 25; // Importer des donn�es
    const T_BASE_RELOC = 26; // Relocaliser

}

?>