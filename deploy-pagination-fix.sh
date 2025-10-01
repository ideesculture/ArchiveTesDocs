#!/bin/bash
# Script de déploiement du correctif de pagination
# Ce script met à jour les fichiers JavaScript pour activer la pagination

echo "=== Déploiement du correctif de pagination ==="

# 1. Copier le fichier source modifié vers web/bundles
echo "1. Copie du fichier source vers web/bundles..."
cp src/bs/IDP/BackofficeBundle/Resources/public/js/IDPUserSettingsManagement.1.2.0.0.js \
   web/bundles/bsidpbackoffice/js/IDPUserSettingsManagement.1.2.0.0.js

# 2. Mettre à jour tous les fichiers compilés qui utilisent IDPUserSettingsManagement
echo "2. Mise à jour des fichiers compilés Assetic..."

# Liste des fichiers compilés à mettre à jour
for compiled in web/js/*_IDPUserSettingsManagement*.js; do
    if [ -f "$compiled" ]; then
        echo "   - Mise à jour de $compiled"
        cp web/bundles/bsidpbackoffice/js/IDPUserSettingsManagement.1.2.0.0.js "$compiled"
    fi
done

# 3. Reconstruire les fichiers principaux .js
echo "3. Reconstruction des fichiers principaux..."

# Pour chaque fichier principal, reconstruire à partir de ses composants
for base in $(ls web/js/*.js | grep -E "^web/js/[a-f0-9]{7}\.js$" | sed 's/web\/js\/\([a-f0-9]*\)\.js/\1/'); do
    components=$(ls web/js/${base}_*.js 2>/dev/null | sort -V)
    if [ ! -z "$components" ]; then
        echo "   - Reconstruction de ${base}.js"
        cat $components > web/js/${base}.js
    fi
done

echo ""
echo "=== Déploiement terminé ==="
echo ""
echo "Prochaines étapes sur le serveur de production :"
echo "1. Transférer les fichiers modifiés vers le serveur"
echo "2. Vider le cache du navigateur (Ctrl+Shift+R)"
echo "3. Tester l'URL : https://demo-archivetesdocs.ideesculture.fr/archive/archivist/manageuserwants/"
echo ""
echo "La pagination devrait maintenant afficher 50 résultats au lieu de 10."
