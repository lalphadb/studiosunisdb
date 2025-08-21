#!/bin/bash

echo "🔍 Analyse du bundle StudiosDB..."
echo "================================"

# Couleurs
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Seuils en KB
CHUNK_WARNING=200
CHUNK_ERROR=500
TOTAL_WARNING=800
TOTAL_ERROR=1200

# Analyser chaque chunk
echo -e "\n📦 Chunks JavaScript:"
for file in public/build/assets/*.js; do
    if [ -f "$file" ]; then
        size_kb=$(du -k "$file" | cut -f1)
        filename=$(basename "$file")
        
        if [ $size_kb -gt $CHUNK_ERROR ]; then
            echo -e "${RED}❌ $filename: ${size_kb}KB (>500KB)${NC}"
        elif [ $size_kb -gt $CHUNK_WARNING ]; then
            echo -e "${YELLOW}⚠️  $filename: ${size_kb}KB (>200KB)${NC}"
        else
            echo -e "${GREEN}✅ $filename: ${size_kb}KB${NC}"
        fi
    fi
done

# Total JS
echo -e "\n📊 Résumé:"
total_js=$(du -ck public/build/assets/*.js 2>/dev/null | grep total | cut -f1)
if [ ! -z "$total_js" ]; then
    if [ $total_js -gt $TOTAL_ERROR ]; then
        echo -e "${RED}Total JS: ${total_js}KB (>1200KB - Action requise!)${NC}"
    elif [ $total_js -gt $TOTAL_WARNING ]; then
        echo -e "${YELLOW}Total JS: ${total_js}KB (>800KB - Attention)${NC}"
    else
        echo -e "${GREEN}Total JS: ${total_js}KB (Optimal)${NC}"
    fi
fi

# Total CSS
total_css=$(du -ck public/build/assets/*.css 2>/dev/null | grep total | cut -f1)
if [ ! -z "$total_css" ]; then
    echo -e "Total CSS: ${total_css}KB"
fi

# Nombre de chunks
num_chunks=$(ls -1 public/build/assets/*.js 2>/dev/null | wc -l)
echo -e "\n🎯 Code splitting: $num_chunks chunks créés"

if [ $num_chunks -gt 5 ]; then
    echo -e "${GREEN}✅ Bon code splitting détecté${NC}"
else
    echo -e "${YELLOW}⚠️  Code splitting peut être amélioré${NC}"
fi

echo -e "\n================================"
echo "✨ Analyse terminée"
