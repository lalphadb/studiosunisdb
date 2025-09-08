import { defineConfig, devices } from '@playwright/test';

/**
 * =================================================================
 * 🎭 CONFIGURATION PLAYWRIGHT STUDIOSDB - MODE HEADLESS (SERVEUR)
 * =================================================================
 * Configuration pour environnement serveur sans X11
 * =================================================================
 */

export default defineConfig({
  testDir: './tests/e2e',
  fullyParallel: false,
  forbidOnly: !!process.env.CI,
  retries: 0,
  workers: 1,
  reporter: [
    ['html'],
    ['list']
  ],

  // =====================================================
  // 🖥️ MODE HEADLESS POUR SERVEUR
  // =====================================================
  use: {
    // Mode headless (pas de fenêtre)
    headless: true,
    
    // Screenshots et vidéos
    video: 'retain-on-failure',
    screenshot: 'only-on-failure',
    trace: 'retain-on-failure',
    
    // Configuration navigateur
    viewport: { width: 1280, height: 720 },
    ignoreHTTPSErrors: true,
    
    // URL de base (StudiosDB)
    baseURL: 'http://localhost:8000',
    
    // Timeouts
    actionTimeout: 10000,
    navigationTimeout: 30000,
  },

  projects: [
    {
      name: 'chromium',
      use: { 
        ...devices['Desktop Chrome'],
        launchOptions: {
          headless: true,
        }
      },
    },
  ],

  webServer: {
    command: 'php artisan serve --host=localhost --port=8000',
    port: 8000,
    reuseExistingServer: true,
    stdout: 'ignore',
    stderr: 'pipe',
  },

  outputDir: './test-results',
});
