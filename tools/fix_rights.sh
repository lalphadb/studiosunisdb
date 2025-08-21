#!/usr/bin/env bash
set -euo pipefail

# Répertoire projet (détecte artisan)
PROJ="${1:-$(pwd)}"
[[ -f "$PROJ/artisan" ]] || { echo "❌ Lance depuis la racine du projet (artisan introuvable)"; exit 1; }

# Utilisateur courant et groupe
U="$(id -un)"; G="$(id -gn)"

echo "— Fix ownership sur $PROJ (user=$U group=$G)"
sudo chown -R "$U:$G" "$PROJ"

echo "— Permissions (dirs=775, files=664)"
find "$PROJ" -type d -exec chmod 775 {} \;
find "$PROJ" -type f -exec chmod 664 {} \;

echo "— Exécution pour scripts"
chmod +x "$PROJ"/tools/*.sh 2>/dev/null || true

echo "— Dossiers Laravel (writable)"
mkdir -p "$PROJ/storage" "$PROJ/bootstrap/cache"
chmod -R 775 "$PROJ/storage" "$PROJ/bootstrap/cache"

# ACL pour www-data (serveur web) et pour l'utilisateur courant (MCP/Claude/Copilote tournent sous $U)
if command -v setfacl >/dev/null 2>&1; then
  echo "— ACL www-data sur storage/ et bootstrap/cache/"
  sudo setfacl -R -m u:www-data:rwX -m d:u:www-data:rwX "$PROJ/storage" "$PROJ/bootstrap/cache"
  sudo setfacl -R -m u:"$U":rwX -m d:u:"$U":rwX "$PROJ"
fi

# Supprime le flag immuable si présent (rare mais bloquant rename)
if command -v lsattr >/dev/null 2>&1 && command -v chattr >/dev/null 2>&1; then
  if lsattr -R "$PROJ" 2>/dev/null | grep -q '^-..i'; then
    echo "— Flag immuable détecté, tentative de retrait (sudo chattr -i -R)"
    sudo chattr -i -R "$PROJ" || true
  fi
fi

# Git safe.directory (au cas où)
if command -v git >/dev/null 2>&1 && [ -d "$PROJ/.git" ]; then
  git config --global --add safe.directory "$PROJ" || true
fi

echo "✅ Droits corrigés."
