#!/usr/bin/env node

/**
 * Vérificateur de configuration Playwright pour StudiosDB
 * Ce script vérifie que tout est prêt pour lancer les tests frontend
 */

const { execSync } = require('child_process');
const fs = require('fs');
const path = require('path');

console.log('🔍 Vérification de la configuration Playwright...\n');

// Vérifications
const checks = [
  {
    name: 'Node.js installé',
    check: () => {
      try {
        const version = execSync('node --version', { encoding: 'utf8' }).trim();
        return { success: true, message: `Node.js ${version}` };
      } catch (error) {
        return { success: false, message: 'Node.js non trouvé' };
      }
    }
  },
  {
    name: 'npm installé',
    check: () => {
      try {
        const version = execSync('npm --version', { encoding: 'utf8' }).trim();
        return { success: true, message: `npm ${version}` };
      } catch (error) {
        return { success: false, message: 'npm non trouvé' };
      }
    }
  },
  {
    name: 'Playwright installé',
    check: () => {
      try {
        const version = execSync('npx playwright --version', { encoding: 'utf8' }).trim();
        return { success: true, message: version };
      } catch (error) {
        return { success: false, message: 'Playwright non installé' };
      }
    }
  },
  {
    name: 'Script de test existe',
    check: () => {
      const scriptPath = path.join(__dirname, 'complete-frontend-test.js');
      if (fs.existsSync(scriptPath)) {
        return { success: true, message: 'Script trouvé' };
      } else {
        return { success: false, message: 'Script manquant' };
      }
    }
  },
  {
    name: 'Navigateurs Playwright',
    check: () => {
      try {
        // Vérifier si Chromium est installé
        const chromiumPath = path.join(__dirname, 'node_modules', 'playwright', 'browsers', 'chromium');
        if (fs.existsSync(chromiumPath)) {
          return { success: true, message: 'Navigateurs installés' };
        } else {
          return { success: false, message: 'Navigateurs non installés' };
        }
      } catch (error) {
        return { success: false, message: 'Impossible de vérifier' };
      }
    }
  }
];

// Exécuter les vérifications
let allGood = true;

checks.forEach(({ name, check }) => {
  const result = check();
  const status = result.success ? '✅' : '❌';
  console.log(`${status} ${name}: ${result.message}`);

  if (!result.success) {
    allGood = false;
  }
});

console.log('\n' + '='.repeat(50));

// Résultat final
if (allGood) {
  console.log('🎉 Configuration Playwright OK !');
  console.log('\nPour lancer les tests :');
  console.log('  node complete-frontend-test.js');
} else {
  console.log('⚠️  Configuration incomplète. Corrections nécessaires :');
  console.log('\nInstallation manuelle :');
  console.log('  npm install playwright');
  console.log('  npx playwright install');
}

console.log('\n📖 Voir README-frontend-tests.md pour plus d\'informations');
console.log('='.repeat(50));
