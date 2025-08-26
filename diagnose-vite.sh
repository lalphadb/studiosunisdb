#!/bin/bash

echo "🔍 Diagnostic npm run dev"
echo "========================="
echo ""

# Vérifier les versions
echo "📦 Checking versions:"
which node 2>/dev/null && node --version || echo "❌ Node not found in PATH"
which npm 2>/dev/null && npm --version || echo "❌ npm not found in PATH"
echo ""

# Vérifier node_modules
echo "📋 Checking node_modules:"
if [ -d "node_modules" ]; then
    echo "✅ node_modules exists"
    if [ -f "node_modules/.bin/vite" ]; then
        echo "✅ vite binary found"
    else
        echo "❌ vite binary missing"
    fi
else
    echo "❌ node_modules missing - run 'npm install'"
fi
echo ""

# Essayer npm run dev avec sortie d'erreur
echo "🚀 Trying npm run dev:"
echo "----------------------"
npm run dev 2>&1 | head -20
echo ""

# Si ça échoue, essayer directement
if [ $? -ne 0 ]; then
    echo "🔧 Trying direct vite:"
    echo "---------------------"
    node node_modules/vite/bin/vite.js --version 2>&1
    echo ""
fi

# Vérifier les ports
echo "🌐 Checking port 5173:"
lsof -i:5173 2>/dev/null && echo "⚠️ Port 5173 is in use" || echo "✅ Port 5173 is free"
echo ""

echo "========================="
echo "✨ Diagnostic complete"
