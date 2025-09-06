import { test, expect } from '@playwright/test';

/**
 * =================================================================
 * 🎭 TESTS VISUELS MODULE MEMBRES - STUDIOSDB V7
 * =================================================================
 * Tests E2E du module Membres avec mode visuel temps réel
 * Utilisation : npx playwright test membres --headed --project=chromium
 * =================================================================
 */

test.describe('Module Membres - Tests Visuels', () => {
  
  // Configuration pour tous les tests de ce groupe
  test.beforeEach(async ({ page }) => {
    // Se connecter comme superadmin
    await page.goto('/login');
    await page.fill('[name="email"]', 'louis@4lb.ca');
    await page.fill('[name="password"]', 'password');
    await page.click('button[type="submit"]');
    
    // Attendre la redirection vers dashboard
    await page.waitForURL('/dashboard');
    await expect(page).toHaveTitle(/Dashboard/);
  });

  test('🏠 Navigation vers module Membres', async ({ page }) => {
    console.log('🎯 Test : Navigation vers module Membres');
    
    // Cliquer sur le menu Membres
    await page.click('a[href="/membres"]');
    
    // Vérifier qu'on arrive sur la page membres
    await page.waitForURL('/membres');
    await expect(page).toHaveTitle(/Gestion des Membres/);
    
    // Vérifier les éléments de l'interface
    await expect(page.locator('h1')).toContainText('Membres');
    await expect(page.locator('text=Gestion centralisée des membres')).toBeVisible();
    
    // Pause pour observer visuellement
    await page.pause();
  });

  test('📊 Vérification Stats Cards', async ({ page }) => {
    console.log('🎯 Test : Vérification des stats cards');
    
    await page.goto('/membres');
    
    // Vérifier les 4 stats cards
    const statsCards = page.locator('[data-testid="stat-card"], .grid > div:has(.text-xl)');
    await expect(statsCards).toHaveCount(4);
    
    // Vérifier les titres des stats
    await expect(page.locator('text=Total membres')).toBeVisible();
    await expect(page.locator('text=Membres actifs')).toBeVisible();
    await expect(page.locator('text=Nouveaux ce mois')).toBeVisible();
    await expect(page.locator('text=Présences aujourd\'hui')).toBeVisible();
    
    // Pause pour observer
    await page.pause();
  });

  test('🔍 Test filtres de recherche', async ({ page }) => {
    console.log('🎯 Test : Fonctionnalité de filtrage');
    
    await page.goto('/membres');
    
    // Tester le champ de recherche
    const searchInput = page.locator('input[placeholder*="Nom, email, téléphone"]');
    await expect(searchInput).toBeVisible();
    
    // Taper dans le champ de recherche
    await searchInput.fill('Alice');
    await page.waitForTimeout(500); // Attendre le debounce
    
    // Tester les dropdowns de filtres
    await page.selectOption('select[data-testid="filter-statut"], select:has(option[value="actif"])', 'actif');
    await page.selectOption('select:has(option[value="mineur"])', 'mineur');
    
    // Cliquer sur le bouton Filtrer
    await page.click('button:has-text("Filtrer")');
    
    // Pause pour observer les résultats
    await page.pause();
  });

  test('👁️ Test actions hover-only', async ({ page }) => {
    console.log('🎯 Test : Actions hover-only sur les lignes');
    
    await page.goto('/membres');
    
    // Attendre que le tableau soit chargé
    await page.waitForSelector('table tbody tr');
    
    // Sélectionner la première ligne du tableau
    const firstRow = page.locator('table tbody tr').first();
    await expect(firstRow).toBeVisible();
    
    // Vérifier que les actions sont cachées initialement (mobile = visible, desktop = hidden)
    const actionsContainer = firstRow.locator('.opacity-0, .md\\:opacity-0');
    
    // Hover sur la ligne pour révéler les actions
    await firstRow.hover();
    
    // Vérifier que les actions deviennent visibles
    await expect(firstRow.locator('a[title="Voir"], button[title="Voir"]')).toBeVisible();
    await expect(firstRow.locator('a[title="Modifier"], button[title="Modifier"]')).toBeVisible();
    
    // Pause pour observer l'effet hover
    await page.pause();
  });

  test('👤 Accès au profil d\'un membre', async ({ page }) => {
    console.log('🎯 Test : Accès au profil détaillé d\'un membre');
    
    await page.goto('/membres');
    
    // Attendre et cliquer sur la première ligne ou bouton "Voir"
    const firstRow = page.locator('table tbody tr').first();
    await firstRow.hover();
    
    // Cliquer sur le bouton "Voir" 
    await firstRow.locator('a[title="Voir"], a:has(svg):first').click();
    
    // Vérifier qu'on arrive sur la page show du membre
    await page.waitForURL(/\/membres\/\d+$/);
    
    // Vérifier les éléments du profil
    await expect(page.locator('h1')).toBeVisible();
    await expect(page.locator('text=Progression')).toBeVisible();
    await expect(page.locator('text=Informations Personnelles')).toBeVisible();
    
    // Pause pour observer le profil
    await page.pause();
  });

  test('🥋 Test système progression ceintures', async ({ page }) => {
    console.log('🎯 Test : Système de progression des ceintures');
    
    // Aller sur le profil du premier membre
    await page.goto('/membres');
    const firstRow = page.locator('table tbody tr').first();
    await firstRow.hover();
    await firstRow.locator('a[title="Voir"], a:has(svg):first').click();
    
    await page.waitForURL(/\/membres\/\d+$/);
    
    // Chercher le bouton "Faire Progresser"
    const progressButton = page.locator('button:has-text("Faire Progresser")');
    if (await progressButton.isVisible()) {
      await progressButton.click();
      
      // Vérifier que le modal s'ouvre
      await expect(page.locator('text=Faire Progresser')).toBeVisible();
      await expect(page.locator('select:has(option)')).toBeVisible();
      
      // Sélectionner une ceinture (si des options sont disponibles)
      const ceintureSelect = page.locator('select').first();
      const options = await ceintureSelect.locator('option').count();
      
      if (options > 1) {
        await ceintureSelect.selectOption({ index: 1 });
        
        // Ajouter des notes
        await page.fill('textarea', 'Test progression via Playwright - Mode visuel');
        
        // Pause pour observer le modal
        await page.pause();
        
        // Fermer le modal (pour ne pas modifier les données)
        await page.click('button:has-text("Annuler")');
      }
    }
    
    // Pause finale
    await page.pause();
  });

  test('📱 Test responsive mobile', async ({ page }) => {
    console.log('🎯 Test : Interface responsive mobile');
    
    // Définir une taille mobile
    await page.setViewportSize({ width: 375, height: 667 });
    
    await page.goto('/membres');
    
    // Vérifier que les colonnes masquées sur mobile ne sont pas visibles
    await expect(page.locator('th:has-text("Contact")')).toBeHidden();
    await expect(page.locator('th:has-text("Statut")')).toBeHidden();
    
    // Vérifier que les colonnes importantes restent visibles
    await expect(page.locator('th:has-text("Membre")')).toBeVisible();
    await expect(page.locator('th:has-text("Ceinture")')).toBeVisible();
    await expect(page.locator('th:has-text("Actions")')).toBeVisible();
    
    // Tester le scroll horizontal
    await page.locator('table').scrollIntoViewIfNeeded();
    
    // Pause pour observer l'interface mobile
    await page.pause();
  });

  test('➕ Test création nouveau membre', async ({ page }) => {
    console.log('🎯 Test : Création d\'un nouveau membre');
    
    await page.goto('/membres');
    
    // Cliquer sur "Nouveau membre"
    await page.click('a:has-text("Nouveau membre")');
    
    // Vérifier qu'on arrive sur la page de création
    await page.waitForURL('/membres/create');
    await expect(page.locator('h1')).toContainText('Nouveau membre');
    
    // Remplir le formulaire (juste pour tester l'interface)
    await page.fill('[name="prenom"]', 'Test');
    await page.fill('[name="nom"]', 'Playwright');
    await page.fill('[name="date_naissance"]', '1990-01-01');
    await page.fill('[name="email"]', 'test.playwright@example.com');
    
    // Pause pour observer le formulaire
    await page.pause();
    
    // Ne pas soumettre pour éviter de créer des données de test
    console.log('📝 Formulaire testé, pas de soumission pour éviter pollution BDD');
  });

  test('🎨 Test thème et design StudiosDB', async ({ page }) => {
    console.log('🎯 Test : Cohérence visuelle du thème StudiosDB');
    
    await page.goto('/membres');
    
    // Vérifier les couleurs du thème (slate/dark)
    const header = page.locator('header, h1').first();
    const bgColor = await header.evaluate(el => getComputedStyle(el).backgroundColor);
    console.log('🎨 Couleur header:', bgColor);
    
    // Vérifier les gradients sur les boutons
    const createButton = page.locator('a:has-text("Nouveau membre")');
    await expect(createButton).toHaveClass(/gradient/);
    
    // Vérifier les cards avec backdrop-blur
    const statsCards = page.locator('.backdrop-blur-sm').first();
    await expect(statsCards).toBeVisible();
    
    // Prendre des screenshots pour vérifier visuellement
    await page.screenshot({ 
      path: 'test-results/theme-verification.png',
      fullPage: true 
    });
    
    // Pause pour observation finale
    await page.pause();
  });

});

/**
 * =================================================================
 * 🚀 COMMANDES POUR LANCER LES TESTS EN MODE VISUEL
 * =================================================================
 * 
 * 🖥️ Mode headed (fenêtre visible) :
 * npx playwright test membres --headed
 * 
 * 🎮 UI Mode (panneau interactif) :
 * npx playwright test membres --ui
 * 
 * 🔍 Inspector (pas à pas + DOM) :
 * PWDEBUG=1 npx playwright test membres
 * 
 * 🐌 Super slow motion :
 * npx playwright test membres --headed --project=chromium --workers=1 --timeout=0
 * 
 * 📱 Test mobile :
 * npx playwright test membres --headed --project="Mobile Chrome"
 * 
 * 📊 Avec traces et vidéos :
 * npx playwright test membres --trace=on
 * npx playwright show-trace test-results/
 * 
 * =================================================================
 */
