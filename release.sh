# Script de release automatique
cat > scripts/release.sh << 'EOF'
#!/bin/bash

# StudiosDB Release Script
set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

echo -e "${GREEN}StudiosDB Enterprise - Release Management${NC}"
echo "=========================================="

# Vérifier qu'on est sur la bonne branche
CURRENT_BRANCH=$(git branch --show-current)
if [[ "$CURRENT_BRANCH" != "main" && "$CURRENT_BRANCH" != "master" && "$CURRENT_BRANCH" != "refonte/v4-clean" ]]; then
    echo -e "${RED}Erreur: Les releases doivent être faites depuis main/master${NC}"
    exit 1
fi

# Lire la version actuelle
CURRENT_VERSION=$(cat VERSION)
echo -e "Version actuelle: ${YELLOW}$CURRENT_VERSION${NC}"

# Demander le type de release
echo ""
echo "Type de release:"
echo "1) Patch (correction de bugs)"
echo "2) Minor (nouvelles fonctionnalités)"
echo "3) Major (changements non rétrocompatibles)"
echo "4) Build (révision interne)"
read -p "Choisissez (1-4): " RELEASE_TYPE

# Calculer la nouvelle version
IFS='.' read -ra VERSION_PARTS <<< "$CURRENT_VERSION"
MAJOR=${VERSION_PARTS[0]}
MINOR=${VERSION_PARTS[1]}
PATCH=${VERSION_PARTS[2]}
BUILD=${VERSION_PARTS[3]}

case $RELEASE_TYPE in
    1)
        PATCH=$((PATCH + 1))
        BUILD=0
        TYPE_NAME="patch"
        ;;
    2)
        MINOR=$((MINOR + 1))
        PATCH=0
        BUILD=0
        TYPE_NAME="minor"
        ;;
    3)
        MAJOR=$((MAJOR + 1))
        MINOR=0
        PATCH=0
        BUILD=0
        TYPE_NAME="major"
        ;;
    4)
        BUILD=$((BUILD + 1))
        TYPE_NAME="build"
        ;;
    *)
        echo -e "${RED}Option invalide${NC}"
        exit 1
        ;;
esac

NEW_VERSION="$MAJOR.$MINOR.$PATCH.$BUILD"
echo -e "Nouvelle version: ${GREEN}$NEW_VERSION${NC}"

# Demander confirmation
read -p "Confirmer la release v$NEW_VERSION? (y/n): " CONFIRM
if [[ "$CONFIRM" != "y" ]]; then
    echo "Release annulée"
    exit 0
fi

# Mettre à jour le fichier VERSION
echo $NEW_VERSION > VERSION

# Demander le message de commit
echo ""
echo "Entrez les changements principaux (terminez avec une ligne vide):"
CHANGES=""
while IFS= read -r line; do
    [[ -z "$line" ]] && break
    CHANGES="${CHANGES}- ${line}\n"
done

# Mettre à jour le CHANGELOG
DATE=$(date +%Y-%m-%d)
CHANGELOG_ENTRY="## [$NEW_VERSION] - $DATE

### ${TYPE_NAME^}
$CHANGES
"

# Insérer dans le CHANGELOG après la ligne ## [
sed -i "/^## \[/i\\$CHANGELOG_ENTRY" CHANGELOG.md

# Commit les changements
git add VERSION CHANGELOG.md
git commit -m "chore: release v$NEW_VERSION

Type: $TYPE_NAME
$CHANGES"

# Créer le tag
TAG_MESSAGE="Release v$NEW_VERSION

Type: $TYPE_NAME
Date: $DATE

Changes:
$CHANGES"

git tag -a "v$NEW_VERSION" -m "$TAG_MESSAGE"

echo ""
echo -e "${GREEN}✓ Release v$NEW_VERSION créée avec succès!${NC}"
echo ""
echo "Pour pousser les changements:"
echo "  git push origin $CURRENT_BRANCH"
echo "  git push origin v$NEW_VERSION"
echo ""
echo "Pour voir le tag:"
echo "  git show v$NEW_VERSION"
EOF

chmod +x scripts/release.sh
