# Refonte UI ArchiveTesDocs — Plan de mise en œuvre

> **Périmètre strict : vues (Twig) + CSS.** Pas de refonte du backend, des contrôleurs,
> ni du JavaScript métier (jQuery conservé). L'objectif est que l'UI colle aux maquettes
> https://gautiermichelin.github.io/archivetesdocs-mockups/ (clonées en local :
> `/var/www/archivetesdocs-mockups`).

## 1. État des lieux

### Existant
- Symfony 3 / PHP 7.0 — **81 templates Twig** : 50 étendent `base.html.twig`,
  4 `mini.html.twig` (dont le dashboard), 1 `light.html.twig`.
- **Bootstrap 3** (grid + composants + JS), jQuery 3.2.1 + jQuery UI, FontAwesome 5,
  `main.css` custom de 2 272 lignes, `color-scheme.css`.
- Layout actuel : navbar haute (logo + compte + logout) + menu accordéon gauche
  (`col-md-2`) + contenu (`col-md-10`).
- Le JS métier (IDP*.js) cible des **id** (`#badgeNbTransfer`, `#waitAjax`…) et des
  comportements Bootstrap (`data-toggle`, modals, collapse) → à préserver.

### Maquettes (source de vérité : `src/App.jsx` du repo mockups)
- **Design tokens** (`const C`) : navy `#0F1B2D`, blue `#2563EB`, bluePale `#EFF6FF`,
  blueBorder `#BFDBFE`, gold `#D97706`, goldLight `#FEF3C7`, goldBorder `#FDE68A`,
  sand `#FEFBF6` (fond), gris g50→g700, red/redPale, green/greenPale.
- **Typo** : DM Sans (300-700, corps) + **Lexend Deca 700** (titres h1 24px).
  (Le README des maquettes mentionne Playfair Display : obsolète, le code fait foi.)
- **Layout cible** : sidebar unique blanche 250px (logo ATD en haut, sections
  « Archives » / « Gestion », chip utilisateur en bas) + contenu sur fond sable,
  padding 32. **La navbar haute disparaît** (logout/compte migrent dans la sidebar).
- **Composants récurrents** : Card (radius 12, border g200) · Btn (5 variantes :
  primary/secondary/ghost/danger/gold, radius 8) · TabBar (soulignement bleu 2px) ·
  pills (radius 20) · tables (th uppercase 10.5-11px g500 sur g50, zébrage, bordures
  g100) · badges de statut colorés (pill 20) · `code` sur g100 · SearchInput avec
  icône · FormField (label 13px 500) + Input/Select (radius 8, focus ring bleu pâle) ·
  modale (radius 16, header navy, footer g50, backdrop blur).
- **Icônes** : la maquette utilise des SVG type Feather ; en pratique on **garde
  FontAwesome 5** (déjà chargé, utilisé partout) en mappant les pictos équivalents.
- À ignorer dans les maquettes : le sélecteur jaune « Maquette — Vue » et le bouton
  « [AVANT] » (outillage de démo).

### Correspondance maquettes ↔ templates
| # | Vue maquette | Template(s) principal(aux) | Layout |
|---|---|---|---|
| 1 | Accueil (dashboard compteurs + actions) | `DashboardBundle/.../Default/index.html.twig` | mini |
| 2 | Utilisateurs (onglets + table) | `UsersBundle/.../admin/user_list.html.twig` (+ add/finetune) | base |
| 3 | Services / référentiels (onglets + liste + ajout) | `ArchiveBundle/.../Archivist/managedb_input_*.html.twig` (9 onglets) + `partial.menumanagedb` | base |
| 4 | File de transfert (table + panneau latéral) | `Archivist/manageuserwants` + écrans `manage_provider_wants`, `transferScreen` | base |
| 5 | Modale demandes | modale du dashboard (`index.html.twig` + JS existant) | mini |
| 6 | Saisie d'archive (formulaire en cards) | `Default/new.html.twig` (+ `partial.precisions`) | base |

## 2. Stratégie — thème par-dessus Bootstrap 3, pas de remplacement

Principe : **ne pas retirer Bootstrap 3** (le JS métier et 45+ écrans non maquettés en
dépendent). On construit un **thème « ATD 2025 »** qui :
1. définit les design tokens en **variables CSS** (`:root`) ;
2. **surcharge** les composants Bootstrap (btn, panel→card, table, nav-tabs, modal,
   badge, form-control) pour qu'ils ressemblent aux maquettes → les écrans non
   maquettés profitent automatiquement du lifting ;
3. restructure le **layout global** (base + mini) : sidebar unique, fond sable ;
4. retouche le **markup Twig** écran par écran uniquement là où la structure diffère
   vraiment (dashboard en cards, formulaire de saisie en sections, panneau transfert).

Fichiers nouveaux :
- `web/css/atd-theme.css` — tokens + surcharges Bootstrap + composants (cards, pills,
  badges, tabs, tables, modales, formulaires, sidebar).
- `web/css/fonts/` — **DM Sans + Lexend Deca auto-hébergées** (woff2 ; pas de Google
  Fonts en prod : RGPD + perf).
- Chargé **après** `main.css` dans `base/mini/light.html.twig` → la cascade fait le
  gros du travail, `main.css` n'est pas réécrit d'un bloc (on en retirera les morceaux
  morts en fin de chantier).

## 3. Phases

### P0 — Socle (fondation, sans changement visuel majeur)
- [ ] Fonts locales (DM Sans 300-700, Lexend Deca 700) + `@font-face`.
- [ ] `atd-theme.css` : variables `:root`, reset doux, typo de base.
- [ ] Branchement dans les 3 layouts (`base`, `mini`, `light`).
- **Validation** : captures avant/après de tous les écrans → aucune casse.

### P1 — Layout global : sidebar unique (le plus gros gain visuel, 1 seul fichier)
- [ ] `base.html.twig` : fusion navbar + menu gauche → sidebar 250px
      (logo, sections Archives/Gestion — liens et `currentMenu` inchangés —,
      fichiers utilisateur, chip user + logout en bas). Fond `--sand` du contenu.
- [ ] `mini.html.twig` : même sidebar (le dashboard l'affiche dans la maquette).
- [ ] Responsive : sidebar rabattable < 992px (collapse Bootstrap déjà dispo).
- **Contrainte** : conserver les `id` (`#left-menu`, `#hrefLogout`, `#userFileResume`,
  `#btnParamAccount`…) que le JS/tutorialize référencent.
- **Validation** : navigation complète en préprod + captures.

### P2 — Surcharge des composants Bootstrap (bénéficie à TOUS les écrans)
- [ ] `.btn` et variantes → Btn maquette (radius 8, palettes primary/secondary/danger/gold).
- [ ] `.panel` → Card (radius 12, border g200, header typographié).
- [ ] `.table` + `bootstrap-table`/`bsTable` → en-têtes uppercase g500/g50, zébrage, hover.
- [ ] `.nav-tabs` → TabBar (soulignement bleu) ; pills de sous-filtres.
- [ ] `.modal` → radius 16, header navy, footer g50 (celle du dashboard = maquette 5).
- [ ] `.form-control`, labels, `.badge`, alerts, pagination.
- **Validation** : passage en revue des 7 écrans capturés + écrans admin.

### P3 — Écrans maquettés, retouches structurelles Twig
1. **Accueil** (`index.html.twig`) : compteurs en grille de 6 stat-cards (icône +
   libellé + chiffre), actions en 2 colonnes de cards cliquables, 3 cards secondaires.
   Les `id` des badges/compteurs et des `li`/boutons cliqués par le JS restent.
2. **Utilisateurs** (`user_list.html.twig`) : carte-table (header : titre + recherche +
   bouton « Nouvel utilisateur »), badges de rôle colorés, actions ghost à droite.
3. **Services/référentiels** (`managedb_input_*`) : onglets TabBar, liste zébrée avec
   avatar-lettre, carte « Ajouter » en pied. (9 écrans quasi identiques → un seul
   pattern à décliner, envisager un partial commun.)
4. **File de transfert** (`manageuserwants` & co) : pills de sous-onglets, table dense,
   panneau latéral 250px « Transfert » / « Transfert à annuler » (zones pointillées).
5. **Modale demandes** : restyler la modale Bootstrap existante du dashboard.
6. **Saisie** (`new.html.twig`) : formulaire en cards thématiques 2 colonnes
   (Propriétaire / Descriptif / Conservation / Contenants + Libellé / Bornes à droite),
   badge N° d'ordre en haut à droite, barre d'actions en pied. **Écran le plus lourd**
   (autosave par champ, champs conditionnels par service) → en dernier.

### P4 — Généralisation et nettoyage
- [ ] Balayage des ~45 écrans restants (recherche, statistiques, imports, prints…) :
      ajustements ponctuels là où la surcharge ne suffit pas.
- [ ] Écran de login (harmonisation avec le thème — attention aux personnalisations
      par instance : logo client).
- [ ] Purge de `main.css` (règles mortes), suppression des styles inline hérités.
- [ ] `print.css` inchangé (les éditions ne bougent pas).

## 4. Méthode de travail et validation

- **Instance de développement** : `secourscatholique2.ideesculture.cloud`
  (`/var/www/secourscatholique2`) — URL non diffusée, liberté totale. Les données de
  la base de démo y ont été recopiées (123 UA réalistes) ; en fin de chantier, la base
  sera re-clonée depuis la prod `secourscatholique`.
- **Backups intermédiaires** : avant chaque étape risquée, dump dans
  `/var/www/secourscatholique2-backups/` (`AAAAMMJJ_HHMMSS_<etape>.sql.gz`) pour
  pouvoir revenir en arrière pendant les devs.
- **Branche** : `ui-refonte` sur le repo `ideesculture/ArchiveTesDocs`
  (commits non cosignés). Merge dans `main` en fin de chantier.
- **Recette finale** : validation sur secourscatholique2 → déploiement prod
  `secourscatholique` (attention : personnalisations locales — logo login — à ne pas
  écraser) et instance démo.
- **Cible navigateurs** : navigateurs modernes uniquement (~4-5 ans). **Toute trace
  d'IE est supprimée** (commentaires conditionnels, hacks CSS/JS legacy) ; aucun fix
  pour navigateur ancien.
- **Captures automatisées 1400px** : `node /home/debian/ui-capture/capture.js`
  (utilisateur dédié `capture` sur secourscatholique2 ; `BASE=<url>` pour changer
  d'instance ; sortie `/home/debian/ui-capture/shots/`). Captures de référence
  « avant » déjà prises.
- Après chaque phase : `cache:clear` prod + captures + comparaison côte à côte.
- **Plan consultable en ligne** : https://secourscatholique2.ideesculture.cloud/REFONTE-UI.html
  (régénéré depuis ce fichier via `marked`).

## 5. Contraintes et risques

| Risque | Parade |
|---|---|
| JS métier couplé aux id/classes Bootstrap (modals, collapse, bootbox, tutorialize) | Ne jamais renommer les `id` ; conserver les classes BS sur les éléments scriptés ; tests manuels des flux ajax après chaque écran |
| 9 écrans `managedb_input_*` dupliqués | Factoriser le pattern dans un partial Twig unique |
| Fonts Google = dépendance externe/RGPD | Auto-hébergement woff2 |
| Iconographie maquette (Feather) ≠ FontAwesome | Garder FA5, mapper les pictos ; pas d'ajout de lib |
| Régressions sur écrans non maquettés | La surcharge P2 est conservatrice ; balayage systématique en P4 avec captures |
| Impression (fiches, étiquettes) | `print.css` et gabarits d'impression hors périmètre |

## 6. Jalons proposés

1. **J1** : P0 + P1 (socle + sidebar) → l'app « change de visage » partout.
2. **J2** : P2 (composants) + P3.1 Accueil + P3.5 modale → démo montrable.
3. **J3** : P3.2 Utilisateurs + P3.3 Services/référentiels.
4. **J4** : P3.4 File de transfert.
5. **J5** : P3.6 Saisie.
6. **J6** : P4 balayage + nettoyage → recette complète préprod, puis prod.
