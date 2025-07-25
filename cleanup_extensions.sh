#!/bin/bash

# 🧹 SCRIPT DE NETTOYAGE EXTENSIONS VS CODE - StudiosDB v5 Pro
# Supprime toutes les extensions redondantes et inutiles

echo "🧹 DÉBUT DU NETTOYAGE DES EXTENSIONS VS CODE"
echo "============================================="

# Extensions à supprimer - Doublons Laravel
echo "❌ Suppression des doublons Laravel..."
code --uninstall-extension absszero.vscode-laravel-goto 2>/dev/null
code --uninstall-extension ahinkle.laravel-model-snippets 2>/dev/null
code --uninstall-extension amirmarmul.laravel-blade-vscode 2>/dev/null
code --uninstall-extension arbitraer.laravel-blade-additional-snippets 2>/dev/null
code --uninstall-extension austenc.laravel-blade-spacer 2>/dev/null
code --uninstall-extension blanc-frederic.vs-phpcompanion 2>/dev/null
code --uninstall-extension cierra.livewire-vscode 2>/dev/null
code --uninstall-extension damianbal.vs-phpclassgen 2>/dev/null
code --uninstall-extension georgykurian.laravel-ide-helper 2>/dev/null
code --uninstall-extension glitchbl.laravel-create-view 2>/dev/null
code --uninstall-extension ihunte.laravel-blade-wrapper 2>/dev/null
code --uninstall-extension lennardv.livewire-goto-updated 2>/dev/null
code --uninstall-extension pgl.laravel-jump-controller 2>/dev/null

# Extensions à supprimer - Doublons PHP
echo "❌ Suppression des doublons PHP..."
code --uninstall-extension devsense.composer-php-vscode 2>/dev/null
code --uninstall-extension devsense.intelli-php-vscode 2>/dev/null
code --uninstall-extension devsense.phptools-vscode 2>/dev/null
code --uninstall-extension devsense.profiler-php-vscode 2>/dev/null
code --uninstall-extension kotfire.php-add-property 2>/dev/null
code --uninstall-extension marabesi.php-import-checker 2>/dev/null
code --uninstall-extension mehedidracula.php-constructor 2>/dev/null
code --uninstall-extension mehedidracula.php-namespace-resolver 2>/dev/null
code --uninstall-extension mrchetan.phpstorm-parameter-hints-in-vscode 2>/dev/null
code --uninstall-extension muath-ye.composer-intelephense 2>/dev/null
code --uninstall-extension onecentlin.phpunit-snippets 2>/dev/null
code --uninstall-extension phiter.phpstorm-snippets 2>/dev/null
code --uninstall-extension recca0120.vscode-phpunit 2>/dev/null
code --uninstall-extension rexshi.phpdoc-comment-vscode-plugin 2>/dev/null

# Extensions à supprimer - Doublons Frontend
echo "❌ Suppression des doublons Frontend..."
code --uninstall-extension octref.vetur 2>/dev/null
code --uninstall-extension dsznajder.es7-react-js-snippets 2>/dev/null
code --uninstall-extension csstools.postcss 2>/dev/null
code --uninstall-extension macieklad.tailwind-sass-syntax 2>/dev/null
code --uninstall-extension sibiraj-s.vscode-scss-formatter 2>/dev/null
code --uninstall-extension syler.sass-indented 2>/dev/null
code --uninstall-extension xabikos.javascriptsnippets 2>/dev/null

# Extensions à supprimer - Inutiles
echo "❌ Suppression des extensions inutiles..."
code --uninstall-extension pcbowers.alpine-intellisense 2>/dev/null
code --uninstall-extension damms005.devdb 2>/dev/null
code --uninstall-extension formulahendry.vscode-mysql 2>/dev/null

# Thèmes en trop
echo "❌ Suppression des thèmes en trop..."
code --uninstall-extension arifbudimanar.arifcode-theme 2>/dev/null
code --uninstall-extension arifbudimanar.arifcode-theme-exclusive 2>/dev/null
code --uninstall-extension arifbudimanar.arifcode-theme-windows 2>/dev/null
code --uninstall-extension smpl-ndrw.deep-dark-space 2>/dev/null

# Extensions utilitaires redondantes
echo "❌ Suppression des utilitaires redondants..."
code --uninstall-extension donjayamanne.githistory 2>/dev/null
code --uninstall-extension mrmlnc.vscode-duplicate 2>/dev/null
code --uninstall-extension ow.vscode-subword-navigation 2>/dev/null
code --uninstall-extension tyriar.lorem-ipsum 2>/dev/null
code --uninstall-extension vector-of-bool.gitflow 2>/dev/null
code --uninstall-extension waderyan.gitblame 2>/dev/null

echo ""
echo "✅ NETTOYAGE TERMINÉ !"
echo "====================="
echo ""
echo "📊 EXTENSIONS CONSERVÉES POUR STUDIOSDB V5 PRO:"
echo ""
echo "🚀 Laravel Core:"
echo "   - bmewburn.vscode-intelephense-client (PHP IntelliSense)"
echo "   - onecentlin.laravel-blade (Blade syntax)"
echo "   - ryannaddy.laravel-artisan (Artisan commands)"
echo "   - onecentlin.laravel5-snippets (Laravel snippets)"
echo "   - shufo.vscode-blade-formatter (Blade formatter)"
echo "   - nhedger.inertia (Inertia.js support)"
echo ""
echo "🎨 Vue 3 + Frontend:"
echo "   - vue.volar (Vue 3 official)"
echo "   - hollowtree.vue-snippets (Vue snippets)"
echo "   - antfu.vite (Vite support)"
echo "   - vitest.explorer (Vitest runner)"
echo ""
echo "💅 Tailwind CSS:"
echo "   - bradlc.vscode-tailwindcss (IntelliSense)"
echo "   - austenc.tailwind-docs (Documentation)"
echo "   - heybourn.headwind (Class sorting)"
echo ""
echo "🔧 Développement:"
echo "   - github.copilot & github.copilot-chat (IA)"
echo "   - eamodio.gitlens (Git enhanced)"
echo "   - mhutchie.git-graph (Git visualization)"
echo "   - esbenp.prettier-vscode (Code formatting)"
echo ""
echo "🎯 PHP Développement:"
echo "   - xdebug.php-debug (PHP debugging)"
echo "   - neilbrayfield.php-docblocker (PHP docs)"
echo "   - open-southeners.laravel-pint (Laravel Pint)"
echo ""
echo "Votre environnement est maintenant optimisé ! 🚀"
