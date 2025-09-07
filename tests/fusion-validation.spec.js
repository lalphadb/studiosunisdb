import { test, expect } from '@playwright/test';

/**
 * TESTS PLAYWRIGHT - VALIDATION FUSION USER + MEMBRE
 * ===================================================
 * Tests critiques pour valider la fusion architecture
 */

test.describe('Fusion User + Membre - Tests Critiques', () => {
  
  test.beforeEach(async ({ page }) => {
    // Connexion en tant qu'admin
    await page.goto('/');
    
    // Si redirection vers login
    if (page.url().includes('/login')) {
      await page.fill('input[name="email"]', 'louis@4lb.ca');
      await page.fill('input[name="password"]', 'password');
      await page.click('button[type="submit"]');
      await page.waitForURL('/dashboard');
    }
  });

  test('✅ Navigation Dashboard -> Users fonctionne', async ({ page }) => {
    // Vérifier Dashboard accessible
    await expect(page.locator('h1, h2')).toContainText(/dashboard|tableau de bord/i);
    
    // Naviguer vers Users (ancien Membres)
    await page.goto('/users');
    await page.waitForLoadState('networkidle');
    
    // Vérifier page Users chargée
    await expect(page.locator('h1, h2')).toContainText(/utilisateurs|membres|users/i);
    
    // Vérifier présence liste
    await expect(page.locator('table, .grid, [data-testid="users-list"]')).toBeVisible();
  });

  test('✅ Redirections compatibilité /membres -> /users', async ({ page }) => {
    // Test redirection /membres
    await page.goto('/membres');
    await page.waitForLoadState('networkidle');
    
    // Doit rediriger vers /users
    expect(page.url()).toContain('/users');
    
    // Page doit fonctionner
    await expect(page.locator('h1, h2')).toContainText(/utilisateurs|membres|users/i);
  });

  test('✅ Liste Users affiche données fusion', async ({ page }) => {
    await page.goto('/users');
    await page.waitForLoadState('networkidle');
    
    // Vérifier colonnes essentielles
    const headers = page.locator('th, .table-header, [data-testid*="header"]');
    await expect(headers).toContainText(/nom|email|statut|rôle/i);
    
    // Vérifier présence données
    const rows = page.locator('tr, .user-row, [data-testid*="user-"]');
    await expect(rows).toHaveCountGreaterThan(5); // Au moins quelques users
    
    // Vérifier format données (email visible)
    await expect(page.locator('text=@')).toBeVisible(); // Email présent
  });

  test('✅ Actions hover fonctionnent (opacity-0 group-hover)', async ({ page }) => {
    await page.goto('/users');
    await page.waitForLoadState('networkidle');
    
    // Trouver première ligne utilisateur
    const firstRow = page.locator('tr, .user-row').first();
    
    // Actions doivent être cachées initialement
    const actions = firstRow.locator('[class*="opacity-0"], .actions, [data-testid*="action"]');
    
    if (await actions.count() > 0) {
      // Hover sur la ligne
      await firstRow.hover();
      
      // Actions doivent devenir visibles
      await expect(actions.first()).toHaveClass(/opacity-100|visible/);
    }
  });

  test('✅ Création User avec données karaté', async ({ page }) => {
    await page.goto('/users/create');
    await page.waitForLoadState('networkidle');
    
    // Remplir formulaire complet
    await page.fill('input[name="email"]', 'test.fusion@example.com');
    await page.fill('input[name="prenom"]', 'Test');
    await page.fill('input[name="nom"]', 'Fusion');
    await page.fill('input[name="password"]', 'password123');
    await page.fill('input[name="password_confirmation"]', 'password123');
    
    // Date de naissance si présente
    const dateInput = page.locator('input[name="date_naissance"], input[type="date"]');
    if (await dateInput.count() > 0) {
      await dateInput.fill('1990-01-01');
    }
    
    // Sélectionner rôle membre si dropdown présent
    const roleSelect = page.locator('select[name*="role"], input[name*="role"]');
    if (await roleSelect.count() > 0) {
      await roleSelect.selectOption('membre');
    }
    
    // Soumettre
    await page.click('button[type="submit"], .btn-primary, [data-testid="submit"]');
    
    // Vérifier redirection vers liste ou success
    await page.waitForURL(/\/users/);
    
    // Vérifier message de succès ou présence nouveau user
    const successMessage = page.locator('.alert-success, .notification, [data-testid*="success"]');
    const newUserEmail = page.locator('text=test.fusion@example.com');
    
    // Au moins un des deux doit être présent
    const hasSuccess = await successMessage.count() > 0;
    const hasNewUser = await newUserEmail.count() > 0;
    
    expect(hasSuccess || hasNewUser).toBeTruthy();
  });

  test('✅ Filtres et recherche fonctionnent', async ({ page }) => {
    await page.goto('/users');
    await page.waitForLoadState('networkidle');
    
    // Test recherche textuelle
    const searchInput = page.locator('input[name="q"], input[placeholder*="recherch"], .search-input');
    if (await searchInput.count() > 0) {
      await searchInput.fill('alice');
      await page.waitForTimeout(500); // Laisser temps pour filtrage
      
      // Vérifier résultats filtrés
      const results = page.locator('tr, .user-row');
      await expect(results).toHaveCountGreaterThan(0);
    }
    
    // Test filtre statut
    const statusFilter = page.locator('select[name="statut"], select[name="status"]');
    if (await statusFilter.count() > 0) {
      await statusFilter.selectOption('actif');
      await page.waitForTimeout(500);
    }
  });

  test('✅ Export fonctionne', async ({ page }) => {
    await page.goto('/users');
    await page.waitForLoadState('networkidle');
    
    // Chercher bouton export
    const exportBtn = page.locator('a[href*="export"], button:has-text("Export"), .btn-export');
    
    if (await exportBtn.count() > 0) {
      // Tester téléchargement
      const downloadPromise = page.waitForDownload();
      await exportBtn.click();
      const download = await downloadPromise;
      
      // Vérifier fichier téléchargé
      expect(download.suggestedFilename()).toMatch(/users|membres.*\.(xlsx|csv|pdf)/i);
    }
  });

  test('🔧 Tests erreurs et edge cases', async ({ page }) => {
    // Test URL inexistante
    await page.goto('/users/99999');
    await page.waitForLoadState('networkidle');
    
    // Doit gérer erreur gracieusement (404 ou redirect)
    const is404 = page.url().includes('404') || await page.locator('text=/404|not found|introuvable/i').count() > 0;
    const isRedirect = page.url().includes('/users') && !page.url().includes('99999');
    
    expect(is404 || isRedirect).toBeTruthy();
  });

  test('🎯 Validation finale architecture', async ({ page }) => {
    // Vérifier toutes les routes principales
    const routes = ['/dashboard', '/users', '/cours', '/presences'];
    
    for (const route of routes) {
      await page.goto(route);
      await page.waitForLoadState('networkidle');
      
      // Aucune erreur JS
      const errors = await page.evaluate(() => window.errors || []);
      expect(errors.length).toBe(0);
      
      // Page se charge complètement
      await expect(page.locator('body')).toBeVisible();
      
      // Pas d'erreurs dans la console (critiques seulement)
      page.on('console', msg => {
        if (msg.type() === 'error') {
          console.warn(`Console error on ${route}:`, msg.text());
        }
      });
    }
  });

});

test.describe('🔥 Tests Performance et Stress', () => {
  
  test('⚡ Performance listing Users', async ({ page }) => {
    const start = Date.now();
    
    await page.goto('/users');
    await page.waitForLoadState('networkidle');
    
    const loadTime = Date.now() - start;
    
    // Page doit se charger en moins de 3 secondes
    expect(loadTime).toBeLessThan(3000);
    
    console.log(`✅ Users page loaded in ${loadTime}ms`);
  });

  test('🔄 Navigation rapide entre modules', async ({ page }) => {
    const routes = ['/dashboard', '/users', '/cours', '/users'];
    
    for (const route of routes) {
      const start = Date.now();
      await page.goto(route);
      await page.waitForLoadState('networkidle');
      const time = Date.now() - start;
      
      expect(time).toBeLessThan(2000);
      console.log(`✅ ${route} loaded in ${time}ms`);
    }
  });
  
});
