import { defineConfig, devices } from '@playwright/test';

/**
 * =================================================================
 * 🎭 CONFIGURATION PLAYWRIGHT STUDIOSDB - MODE VISUEL TEMPS RÉEL  
 * =================================================================
 * Configuration optimisée pour tests visuels en temps réel
 * Modes disponibles :
 * - headed : fenêtre visible
 * - UI mode : panneau interactif 
 * - Inspector : pas à pas + DOM viewer
 * - Slow motion : pour mieux suivre les actions
 * =================================================================
 */

export default defineConfig({
  // Répertoire des tests
  testDir: './tests/e2e',
  
  // Configuration globale des tests
  fullyParallel: true,
  forbidOnly: !!process.env.CI,
  retries: process.env.CI ? 2 : 0,
  workers: process.env.CI ? 1 : undefined,
  reporter: [
    ['html'],
    ['json', { outputFile: 'test-results/results.json' }],
    ['junit', { outputFile: 'test-results/results.xml' }]
  ],

  // =====================================================
  // 🎯 CONFIGURATION MODE VISUEL (TEMPS RÉEL)
  // =====================================================
  use: {
    // 🖥️ Mode headed par défaut - FENÊTRE VISIBLE
    headless: false,
    
    // 🐌 Slow motion pour mieux suivre (200ms entre actions)
    slowMo: 200,
    
    // 📹 Enregistrement vidéo
    video: {
      mode: 'on-first-retry',
      size: { width: 1280, height: 720 }
    },
    
    // 📸 Screenshots automatiques
    screenshot: {
      mode: 'only-on-failure',
      fullPage: true
    },
    
    // 🔍 Traces pour debug (consultables après)
    trace: 'on-first-retry',
    
    // 🌐 Configuration navigateur
    viewport: { width: 1280, height: 720 },
    ignoreHTTPSErrors: true,
    
    // 🚀 URL de base (StudiosDB)
    baseURL: 'http://localhost:8000',
    
    // ⏱️ Timeouts
    actionTimeout: 5000,
    navigationTimeout: 30000,
  },

  // =====================================================
  // 🎭 PROJETS (NAVIGATEURS)
  // =====================================================
  projects: [
    {
      name: 'chromium',
      use: { 
        ...devices['Desktop Chrome'],
        // Mode headed spécifique à Chrome
        launchOptions: {
          headless: false,
          slowMo: 200,
          args: [
            '--start-maximized',
            '--disable-web-security',
            '--disable-dev-shm-usage'
          ]
        }
      },
    },

    {
      name: 'firefox',
      use: { 
        ...devices['Desktop Firefox'],
        launchOptions: {
          headless: false,
          slowMo: 200
        }
      },
    },

    {
      name: 'webkit',
      use: { 
        ...devices['Desktop Safari'],
        launchOptions: {
          headless: false,
          slowMo: 200
        }
      },
    },

    // Tests mobiles
    {
      name: 'Mobile Chrome',
      use: { 
        ...devices['Pixel 5'],
        launchOptions: {
          headless: false,
          slowMo: 300 // Plus lent pour mobile
        }
      },
    },

    {
      name: 'Mobile Safari',
      use: { 
        ...devices['iPhone 12'],
        launchOptions: {
          headless: false,
          slowMo: 300
        }
      },
    },
  ],

  // =====================================================
  // 🚀 SERVEUR DE DÉVELOPPEMENT
  // =====================================================
  webServer: {
    command: 'php artisan serve --host=localhost --port=8000',
    port: 8000,
    reuseExistingServer: !process.env.CI,
    stdout: 'ignore',
    stderr: 'pipe',
  },

  // =====================================================
  // 📁 RÉPERTOIRES
  // =====================================================
  outputDir: './test-results',
  
  // Patterns à ignorer
  testIgnore: [
    '**/node_modules/**',
    '**/vendor/**',
    '**/.git/**'
  ],

  // =====================================================
  // 🎯 COMMANDES SPÉCIALES TEMPS RÉEL
  // =====================================================
  // Pour lancer ces modes, utilisez :
  // 
  // 🖥️  Mode headed (fenêtre visible) :
  // npx playwright test --headed
  //
  // 🎮 UI Mode (panneau interactif) :
  // npx playwright test --ui
  //
  // 🔍 Inspector (pas à pas) :
  // PWDEBUG=1 npx playwright test
  //
  // 🐌 Super slow motion :
  // npx playwright test --headed --project=chromium --workers=1 --timeout=0
  //
  // 📊 Avec traces :
  // npx playwright test --trace=on
  // npx playwright show-trace test-results/
  // =====================================================
});
