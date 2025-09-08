#!/bin/bash
# Kill rapide de tous les processus

pkill -f "php artisan serve"
pkill -f "vite"
pkill -f "npm run dev"
pkill -f "node.*studiosunisdb"
pkill -f "node.*vite"

# Force kill des ports
lsof -ti:8000 | xargs kill -9 2>/dev/null
lsof -ti:5173 | xargs kill -9 2>/dev/null
lsof -ti:5174 | xargs kill -9 2>/dev/null

echo "✅ Tous les processus ont été arrêtés"
echo ""
echo "Processus restants (devrait être vide):"
ps aux | grep -E "php artisan|vite|npm run" | grep -v grep
