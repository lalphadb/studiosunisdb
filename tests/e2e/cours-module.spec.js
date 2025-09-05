import { test, expect } from '@playwright/test';

/**
 * Tests complets du module Cours - StudiosDB v7
 * Couvre: CRUD + Duplication + Validation + UI/UX
 */

test.describe('Module Cours - Tests complets', () => {
  
  test.beforeEach(async ({ page }) => {
    // Connexion avec utilisateur superadmin
    await page.goto('http://127.0.0.1:8000/login');
    
    await page.fill('input[name="email"]', 'admin@studiosdb.ca');
    await page.fill('input[name="password"]', 'password123');
    await page.click('button[type="submit"]');
    
    // Attendre redirection dashboard
    await expect(page).toHaveURL(/.*dashboard/);
    await page.waitForTimeout(1000);
  });

  test('01 - Navigation vers module Cours', async ({ page }) => {
    // Navigation depuis dashboard
    await page.click('a[href="/cours"]');
    await expect(page).toHaveURL(/.*cours$/);
    
    // Vérifier éléments UI essentiels
    await expect(page.locator('h1')).toContainText('Cours');
    await expect(page.locator('text=Nouveau cours')).toBeVisible();
    
    // Vérifier table responsive
    await expect(page.locator('table')).toBeVisible();
    await expect(page.locator('th:has-text("Nom")')).toBeVisible();
    await expect(page.locator('th:has-text("Actions")')).toBeVisible();
  });

  test('02 - Création nouveau cours', async ({ page }) => {
    await page.goto('http://127.0.0.1:8000/cours');
    
    // Cliquer bouton Nouveau cours
    await page.click('text=Nouveau cours');
    await expect(page).toHaveURL(/.*cours\/create/);
    
    // Remplir formulaire complet
    await page.fill('input[name="nom"]', 'Test Playwright Karaté');
    await page.selectOption('select[name="niveau"]', 'debutant');
    await page.fill('input[name="age_min"]', '6');
    await page.fill('input[name="age_max"]', '12');
    await page.fill('input[name="places_max"]', '20');
    
    // Horaires
    await page.selectOption('select[name="jour_semaine"]', 'samedi');
    await page.fill('input[name="heure_debut"]', '10:00');
    await page.fill('input[name="heure_fin"]', '11:00');
    
    // Dates - vérifier calendrier visible
    const dateDebut = page.locator('input[name="date_debut"]');
    await dateDebut.fill('2025-09-13');
    
    const dateFin = page.locator('input[name="date_fin"]');  
    await dateFin.fill('2025-12-20');
    
    // Tarification
    await page.selectOption('select[name="type_tarif"]', 'mensuel');
    await page.fill('input[name="montant"]', '75.00');
    
    // Description
    await page.fill('textarea[name="description"]', 'Cours test créé via Playwright pour validation module.');
    
    // Soumission
    await page.click('button[type="submit"]');
    
    // Vérifier redirection et succès
    await expect(page).toHaveURL(/.*cours\/\d+$/);
    await expect(page.locator('text=Test Playwright Karaté')).toBeVisible();
  });

  test('03 - Affichage détaillé cours', async ({ page }) => {
    await page.goto('http://127.0.0.1:8000/cours');
    
    // Cliquer sur premier cours disponible
    await page.click('table tbody tr:first-child td:first-child a');
    
    // Vérifier page de détail
    await expect(page).toHaveURL(/.*cours\/\d+$/);
    
    // Vérifier sections présentes
    await expect(page.locator('text=Informations')).toBeVisible();
    await expect(page.locator('text=Horaires')).toBeVisible();
    await expect(page.locator('text=Actions')).toBeVisible();
    
    // Vérifier boutons d'action
    await expect(page.locator('text=Modifier')).toBeVisible();
    await expect(page.locator('text=Dupliquer')).toBeVisible();
    await expect(page.locator('text=Sessions multiples')).toBeVisible();
  });

  test('04 - Édition cours existant', async ({ page }) => {
    await page.goto('http://127.0.0.1:8000/cours');
    
    // Accéder à l'édition
    await page.click('table tbody tr:first-child td:first-child a');
    await page.click('text=Modifier');
    
    await expect(page).toHaveURL(/.*cours\/\d+\/edit/);
    
    // Modifier nom
    await page.fill('input[name="nom"]', 'Cours Modifié Playwright');
    
    // Modifier niveau
    await page.selectOption('select[name="niveau"]', 'intermediaire');
    
    // Modifier tarif
    await page.selectOption('select[name="type_tarif"]', 'trimestriel');
    await page.fill('input[name="montant"]', '200.00');
    
    // Vérifier calendriers visibles (CSS corrigé)
    const calendriers = page.locator('input[type="date"], input[type="time"]');
    await expect(calendriers.first()).toBeVisible();
    
    // Soumission
    await page.click('button[type="submit"]');
    
    // Vérifier mise à jour
    await expect(page).toHaveURL(/.*cours\/\d+$/);
    await expect(page.locator('text=Cours Modifié Playwright')).toBeVisible();
  });

  test('05 - Duplication cours', async ({ page }) => {
    await page.goto('http://127.0.0.1:8000/cours');
    
    // Accéder aux détails d'un cours
    await page.click('table tbody tr:first-child td:first-child a');
    
    // Tester duplication générale
    page.on('dialog', dialog => dialog.accept()); // Auto-accepter confirmation
    await page.click('text=Dupliquer');
    
    // Vérifier retour liste avec succès
    await expect(page).toHaveURL(/.*cours$/);
    await expect(page.locator('text=dupliqué avec succès')).toBeVisible();
    
    // Vérifier nouveau cours créé (avec "Copie" dans le nom)
    await expect(page.locator('text=(Copie)')).toBeVisible();
  });

  test('06 - Sessions multiples', async ({ page }) => {
    await page.goto('http://127.0.0.1:8000/cours');
    
    // Accéder aux détails
    await page.click('table tbody tr:first-child td:first-child a');
    
    // Tester sessions multiples
    await page.click('text=Sessions multiples');
    await expect(page).toHaveURL(/.*cours\/\d+\/sessions/);
    
    // Sélectionner plusieurs jours
    await page.check('input[value="lundi"]');
    await page.check('input[value="mercredi"]');
    
    // Remplir horaires
    await page.fill('input[name="heure_debut"]', '19:00');
    await page.fill('input[name="heure_fin"]', '20:00');
    
    // Dates période
    await page.fill('input[name="date_debut"]', '2025-09-15');
    await page.fill('input[name="date_fin"]', '2025-12-15');
    
    // Soumission
    await page.click('button[type="submit"]');
    
    // Vérifier création multiple
    await expect(page).toHaveURL(/.*cours$/);
    await expect(page.locator('text=session(s) créée(s)')).toBeVisible();
  });

  test('07 - Validation erreurs formulaire', async ({ page }) => {
    await page.goto('http://127.0.0.1:8000/cours/create');
    
    // Soumission formulaire vide
    await page.click('button[type="submit"]');
    
    // Vérifier erreurs de validation HTML5
    const nomInput = page.locator('input[name="nom"]');
    expect(await nomInput.evaluate(el => el.validationMessage)).toBeTruthy();
    
    // Remplir partiellement et tester validation serveur
    await page.fill('input[name="nom"]', 'Test Erreurs');
    await page.selectOption('select[name="niveau"]', 'debutant');
    // Omettre âge minimum requis
    await page.click('button[type="submit"]');
    
    // Devrait rester sur la page avec erreurs
    await expect(page).toHaveURL(/.*cours\/create/);
  });

  test('08 - Actions hover-only', async ({ page }) => {
    await page.goto('http://127.0.0.1:8000/cours');
    
    // Vérifier que les actions sont invisibles par défaut
    const firstRow = page.locator('table tbody tr:first-child');
    const actions = firstRow.locator('.opacity-0');
    
    // Actions invisibles initialement
    await expect(actions.first()).toHaveClass(/opacity-0/);
    
    // Hover pour révéler actions
    await firstRow.hover();
    
    // Actions maintenant visibles
    await expect(actions.first()).toHaveClass(/group-hover:opacity-100/);
  });

  test('09 - Responsive design', async ({ page }) => {
    // Test mobile
    await page.setViewportSize({ width: 375, height: 667 });
    await page.goto('http://127.0.0.1:8000/cours');
    
    // Table doit être scrollable horizontalement
    const table = page.locator('table');
    await expect(table).toBeVisible();
    
    // Vérifier overflow-x-auto appliqué
    const tableContainer = page.locator('div.overflow-x-auto');
    await expect(tableContainer).toBeVisible();
    
    // Test tablet
    await page.setViewportSize({ width: 768, height: 1024 });
    await page.reload();
    
    // Stats cards doivent être en grille responsive
    const statsCards = page.locator('[class*="grid-cols"]');
    await expect(statsCards).toBeVisible();
  });

  test('10 - Performance et chargement', async ({ page }) => {
    const startTime = Date.now();
    
    await page.goto('http://127.0.0.1:8000/cours');
    
    // Page doit charger en moins de 3 secondes
    const loadTime = Date.now() - startTime;
    expect(loadTime).toBeLessThan(3000);
    
    // Vérifier éléments critiques chargés
    await expect(page.locator('h1')).toBeVisible();
    await expect(page.locator('table')).toBeVisible();
    
    // Test navigation rapide
    const navStart = Date.now();
    await page.click('text=Nouveau cours');
    await expect(page).toHaveURL(/.*cours\/create/);
    const navTime = Date.now() - navStart;
    expect(navTime).toBeLessThan(2000);
  });

  test('11 - Test calendriers CSS visibilité', async ({ page }) => {
    await page.goto('http://127.0.0.1:8000/cours/create');
    
    // Vérifier inputs date/time visibles
    const dateInputs = page.locator('input[type="date"]');
    const timeInputs = page.locator('input[type="time"]');
    
    await expect(dateInputs.first()).toBeVisible();
    await expect(timeInputs.first()).toBeVisible();
    
    // Vérifier CSS calendar-enhanced appliqué
    const enhancedInputs = page.locator('.calendar-enhanced');
    await expect(enhancedInputs.first()).toBeVisible();
    
    // Test interaction calendrier
    await dateInputs.first().click();
    // Note: calendrier natif du navigateur s'ouvre, difficile à tester
    // On vérifie juste que l'input reste fonctionnel
    await dateInputs.first().fill('2025-09-15');
    expect(await dateInputs.first().inputValue()).toBe('2025-09-15');
  });

  test('12 - Nettoyage données test', async ({ page }) => {
    await page.goto('http://127.0.0.1:8000/cours');
    
    // Rechercher et supprimer cours de test
    const testRows = page.locator('table tbody tr:has-text("Test Playwright"), table tbody tr:has-text("(Copie)")');
    
    const count = await testRows.count();
    for (let i = 0; i < count; i++) {
      const row = testRows.nth(i);
      await row.hover();
      
      // Cliquer bouton supprimer (si visible)
      const deleteBtn = row.locator('button[title*="supprimer"], button[title*="Supprimer"]');
      if (await deleteBtn.isVisible()) {
        page.on('dialog', dialog => dialog.accept());
        await deleteBtn.click();
        await page.waitForTimeout(500);
      }
    }
  });
});
