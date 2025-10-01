# Correctif de pagination - Affichage de 50 résultats

## Problème résolu

La page de gestion des demandes utilisateurs (`/archive/archivist/manageuserwants/`) n'affichait que 10 résultats au lieu de respecter le paramètre `nb_row_per_page` configuré pour chaque utilisateur dans la table `idpuserpagessettings`.

## Cause du problème

La pagination était désactivée dans Bootstrap Table (`pagination: false`), ce qui empêchait l'envoi des paramètres `limit` et `offset` au serveur. Le serveur utilisait donc sa valeur par défaut de 10 résultats.

## Solution appliquée

### 1. Modification du fichier JavaScript

**Fichier modifié :** `src/bs/IDP/BackofficeBundle/Resources/public/js/IDPUserSettingsManagement.1.2.0.0.js`

**Ligne 251 :**
```javascript
// AVANT
pagination: false, /*true,*/

// APRÈS
pagination: true,
```

### 2. Configuration du nombre de résultats par page

Pour modifier le nombre de résultats affichés pour un utilisateur, utilisez l'URL suivante :

```
https://demo-archivetesdocs.ideesculture.fr/backoffice/usersettings/modifypage/?page=7&field=1&value=50
```

**Paramètres :**
- `page=7` : Page "Valider Transfert Prestataire" (voir `IDPUserPagesSettings::PAGE_VALID_TRANSFER_PROVIDER`)
- `field=1` : Champ `nb_row_per_page` (voir `IDPUserPagesSettings::USER_SETTINGS_MODIF_PAGE_NB_ROW_PER_PAGE`)
- `value=50` : Nombre de résultats souhaités (10, 25, 50, 100, etc.)

### 3. Pages concernées (page_id)

Les différentes pages qui peuvent être configurées :
- `7` = PAGE_VALID_TRANSFER_PROVIDER
- `8` = PAGE_VALID_TRANSFER_INTERMEDIATE
- `9` = PAGE_VALID_TRANSFER_INTERNAL
- `10` = PAGE_VALID_DELIVER_WITHOUT_PREPARATION
- `11` = PAGE_VALID_DELIVER_WITH_PREPARATION
- `12` = PAGE_VALID_RETURN
- `13` = PAGE_VALID_EXIT
- `14` = PAGE_VALID_DELETE
- `15` = PAGE_VALID_RELOC_PROVIDER
- (etc.)

Voir le fichier `src/bs/IDP/BackofficeBundle/Entity/IDPUserPagesSettings.php` lignes 21-48 pour la liste complète.

## Déploiement

### Sur le serveur local

Exécutez le script de déploiement :
```bash
./deploy-pagination-fix.sh
```

### Sur le serveur de production

1. Transférez les fichiers modifiés vers le serveur :
   - `web/bundles/bsidpbackoffice/js/IDPUserSettingsManagement.1.2.0.0.js`
   - Tous les fichiers `web/js/*_IDPUserSettingsManagement*.js`
   - Tous les fichiers principaux `web/js/[hash].js` qui ont été reconstruits

2. Videz le cache du navigateur (Ctrl+Shift+R ou Cmd+Shift+R)

3. Testez la page : https://demo-archivetesdocs.ideesculture.fr/archive/archivist/manageuserwants/

## Vérification

### Vérifier que le paramètre a été modifié en base de données

```sql
SELECT ups.id, ups.user_id, u.login, ups.page_id, ups.nb_row_per_page
FROM idpuserpagessettings ups
LEFT JOIN bsusers u ON u.id = ups.user_id
WHERE ups.page_id = 7;
```

### Vérifier que l'API renvoie bien 50 résultats

Testez l'URL directement :
```
https://demo-archivetesdocs.ideesculture.fr/archive/archivist/json/loaddatas/?search=&sort=service&order=asc&uastate=0&uawhat=0&uawhere=0&uawith=0&uahow=0&special=&filterprovider=-1&limit=50&offset=0
```

Elle devrait maintenant renvoyer 50 résultats au lieu de 10.

### Vérifier dans la console du navigateur

1. Ouvrez la console développeur (F12)
2. Allez dans l'onglet "Network" / "Réseau"
3. Rechargez la page de gestion des demandes
4. Trouvez la requête vers `/archive/archivist/json/loaddatas/`
5. Vérifiez que les paramètres incluent `limit=50`

## Fichiers modifiés

- `src/bs/IDP/BackofficeBundle/Resources/public/js/IDPUserSettingsManagement.1.2.0.0.js`
- `web/bundles/bsidpbackoffice/js/IDPUserSettingsManagement.1.2.0.0.js`
- `web/js/*_IDPUserSettingsManagement*.js` (tous les fichiers compilés)
- Fichiers principaux reconstruits : `web/js/[hash].js`

## Date de modification

2025-10-01
