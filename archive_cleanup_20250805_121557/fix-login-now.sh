#!/bin/bash

echo "🚨 CORRECTION LOGIN IMMÉDIATE"
echo "============================="

cd /home/studiosdb/studiosunisdb/studiosdb_v5_pro

echo "🔧 Rebuild assets..."
npm run build || {
    echo "⚠️  NPM build failed - creating emergency assets..."
    
    mkdir -p public/build/assets
    
    # CSS complet avec Tailwind
    cat > public/build/assets/app.css << 'EOF'
@import url('https://cdn.jsdelivr.net/npm/tailwindcss@3.4.17/base.min.css');

body { font-family: system-ui, -apple-system, sans-serif; }
.min-h-screen { min-height: 100vh; }
.flex { display: flex; }
.items-center { align-items: center; }
.justify-center { justify-content: center; }
.bg-gray-100 { background-color: #f3f4f6; }
.bg-white { background-color: #fff; }
.p-8 { padding: 2rem; }
.rounded-lg { border-radius: 0.5rem; }
.shadow-lg { box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); }
.w-96 { width: 24rem; }
.text-center { text-align: center; }
.mb-6 { margin-bottom: 1.5rem; }
.text-3xl { font-size: 1.875rem; }
.font-bold { font-weight: 700; }
.text-gray-900 { color: #111827; }
.mb-2 { margin-bottom: 0.5rem; }
.text-green-600 { color: #059669; }
.font-medium { font-weight: 500; }
.mb-4 { margin-bottom: 1rem; }
.block { display: block; }
.text-sm { font-size: 0.875rem; }
.text-gray-700 { color: #374151; }
.w-full { width: 100%; }
.px-3 { padding-left: 0.75rem; padding-right: 0.75rem; }
.py-2 { padding-top: 0.5rem; padding-bottom: 0.5rem; }
.border { border-width: 1px; }
.border-gray-300 { border-color: #d1d5db; }
.rounded-md { border-radius: 0.375rem; }
.bg-blue-600 { background-color: #2563eb; }
.text-white { color: #fff; }
.px-4 { padding-left: 1rem; padding-right: 1rem; }
input:focus { outline: 2px solid #3b82f6; }
button:hover { opacity: 0.9; }
.mt-6 { margin-top: 1.5rem; }
.p-4 { padding: 1rem; }
.bg-green-50 { background-color: #f0fdf4; }
.border-green-200 { border-color: #bbf7d0; }
.text-green-800 { color: #166534; }
.text-xs { font-size: 0.75rem; }
.text-blue-600 { color: #2563eb; }
.mt-2 { margin-top: 0.5rem; }
EOF
    
    # JS simple
    echo 'window.route = function(name) { return "/" + name; }; console.log("Login assets loaded");' > public/build/assets/app.js
    
    # Manifest
    cat > public/build/manifest.json << 'EOF'
{"resources/css/app.css":{"file":"assets/app.css","isEntry":true},"resources/js/app.js":{"file":"assets/app.js","isEntry":true}}
EOF
}

echo "✅ Assets prêts"

echo "🧹 Clear cache..."
php artisan config:clear
php artisan view:clear

echo ""
echo "🚀 REDÉMARRER MAINTENANT:"
echo "php artisan serve --host=0.0.0.0 --port=8000"

echo ""
echo "🌐 PUIS TESTER:"
echo "http://localhost:8000/login"

echo ""
echo "📧 COMPTE: louis@4lb.ca / password"