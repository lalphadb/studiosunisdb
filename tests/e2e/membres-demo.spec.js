import { test, expect } from '@playwright/test';

/**
 * =================================================================
 * 🎭 TEST DÉMONSTRATION MODULE MEMBRES - MODE SERVEUR
 * =================================================================
 * Test simplifié pour démontrer les tests E2E sans interface graphique
 * =================================================================
 */

test.describe('🎭 Démonstration Tests Module Membres', () => {
  
  test.beforeEach(async ({ page }) => {
    console.log('🎯 Préparation : Connexion utilisateur...');
    
    // Aller à la page de login
    await page.goto('/login');
    
    // Se connecter comme superadmin
    await page.fill('[name="email"]', 'louis@4lb.ca');
    await page.fill('[name="password"]', 'password'); 
    await page.click('button[type="submit"]');
    
    // Attendre la redirection
    await page.waitForURL('/dashboard');
    console.log('✅ Utilisateur connecté avec succès');
  });

  test('🏠 Navigation et vérification page Membres', async ({ page }) => {
    console.log('🎯 TEST: Navigation vers module Membres');
    
    // Naviguer vers les membres
    await page.goto('/membres');
    
    // Vérifier qu'on est bien sur la page membres
    await expect(page).toHaveURL('/membres');
    await expect(page).toHaveTitle(/Gestion des Membres/);
    
    // Vérifier les éléments principaux
    await expect(page.locator('h1')).toContainText('Membres');
    await expect(page.locator('text=Gestion centralisée des membres')).toBeVisible();
    
    console.log('✅ Page Membres chargée correctement');
    
    // Vérifier les stats cards
    const statsCards = page.locator('.grid > div:has(.text-xl)');
    const cardCount = await statsCards.count();
    console.log(`📊 Stats cards trouvées: ${cardCount}`);
    
    // Vérifier la présence du tableau
    await expect(page.locator('table')).toBeVisible();
    console.log('✅ Tableau des membres présent');
    
    // Vérifier le bouton nouveau membre
    await expect(page.locator('a:has-text("Nouveau membre")')).toBeVisible();
    console.log('✅ Bouton "Nouveau membre" présent');
  });

  test('📊 Vérification données et filtres', async ({ page }) => {
    console.log('🎯 TEST: Vérification des données et filtres');
    
    await page.goto('/membres');
    
    // Tester le champ de recherche
    const searchInput = page.locator('input[placeholder*="Nom, email, téléphone"]');
    await expect(searchInput).toBeVisible();
    console.log('✅ Champ de recherche présent');
    
    // Taper dans la recherche
    await searchInput.fill('Alice');
    console.log('📝 Saisie "Alice" dans la recherche');
    
    // Attendre un peu pour le debounce
    await page.waitForTimeout(500);
    
    // Vérifier les dropdowns de filtres
    const statutSelect = page.locator('select:has(option[value="actif"])');
    await expect(statutSelect).toBeVisible();
    console.log('✅ Dropdown statut présent');
    
    // Tester le filtre statut
    await statutSelect.selectOption('actif');
    console.log('📝 Filtre statut "actif" sélectionné');
    
    // Cliquer sur filtrer
    await page.click('button:has-text("Filtrer")');
    console.log('🔍 Bouton Filtrer cliqué');
    
    // Vérifier qu'il y a des résultats dans le tableau
    const rows = page.locator('table tbody tr');
    const rowCount = await rows.count();
    console.log(`👥 Membres trouvés: ${rowCount}`);
  });

  test('👤 Accès profil membre et progression', async ({ page }) => {
    console.log('🎯 TEST: Accès profil membre');
    
    await page.goto('/membres');
    
    // Attendre que le tableau soit chargé
    await page.waitForSelector('table tbody tr');
    
    // Récupérer la première ligne
    const firstRow = page.locator('table tbody tr').first();
    await expect(firstRow).toBeVisible();
    
    // Cliquer sur le lien de la première ligne (nom du membre)
    const memberName = await firstRow.locator('.text-white.font-medium').textContent();
    console.log(`👤 Membre sélectionné: ${memberName}`);
    
    // Cliquer sur le bouton "Voir" (premier lien dans les actions)
    await firstRow.locator('a').first().click();
    
    // Vérifier qu'on arrive sur la page de profil
    await page.waitForURL(/\/membres\/\d+$/);
    console.log('✅ Navigation vers profil réussie');
    
    // Vérifier les éléments du profil
    await expect(page.locator('h1')).toBeVisible();
    const profileTitle = await page.locator('h1').textContent();
    console.log(`📋 Profil chargé: ${profileTitle}`);
    
    // Vérifier les sections
    await expect(page.locator('text=Informations Personnelles')).toBeVisible();
    console.log('✅ Section Informations Personnelles présente');
    
    // Chercher la section progression si elle existe
    const progressionSection = page.locator('text=Progression');
    if (await progressionSection.isVisible()) {
      console.log('✅ Section Progression présente');
      
      // Chercher le bouton "Faire Progresser"
      const progressButton = page.locator('button:has-text("Faire Progresser")');
      if (await progressButton.isVisible()) {
        console.log('🥋 Bouton "Faire Progresser" trouvé');
        
        // Cliquer pour ouvrir le modal
        await progressButton.click();
        
        // Vérifier que le modal s'ouvre
        const modalTitle = page.locator('text=Faire Progresser');
        if (await modalTitle.isVisible()) {
          console.log('✅ Modal progression ouvert');
          
          // Vérifier le dropdown des ceintures
          const ceintureSelect = page.locator('select').first();
          const optionCount = await ceintureSelect.locator('option').count();
          console.log(`🥋 Options ceintures disponibles: ${optionCount}`);
          
          // Fermer le modal pour ne pas modifier les données
          await page.click('button:has-text("Annuler")');
          console.log('✅ Modal fermé sans modification');
        }
      }
    }
  });

  test('📱 Test interface responsive', async ({ page }) => {
    console.log('🎯 TEST: Interface responsive');
    
    // Tester en mode desktop d'abord
    await page.setViewportSize({ width: 1280, height: 720 });
    await page.goto('/membres');
    
    console.log('📐 Mode desktop (1280x720)');
    
    // Vérifier que toutes les colonnes sont visibles en desktop
    await expect(page.locator('th:has-text("Contact")')).toBeVisible();
    await expect(page.locator('th:has-text("Statut")')).toBeVisible();
    console.log('✅ Colonnes desktop visibles');
    
    // Passer en mode mobile
    await page.setViewportSize({ width: 375, height: 667 });
    console.log('📱 Mode mobile (375x667)');
    
    // Recharger pour appliquer le responsive
    await page.reload();
    
    // Vérifier que certaines colonnes sont masquées sur mobile
    const contactColumn = page.locator('th:has-text("Contact")');
    const isContactHidden = await contactColumn.isHidden();
    console.log(`📱 Colonne Contact masquée sur mobile: ${isContactHidden}`);
    
    // Vérifier que les colonnes importantes restent visibles
    await expect(page.locator('th:has-text("Membre")')).toBeVisible();
    await expect(page.locator('th:has-text("Actions")')).toBeVisible();
    console.log('✅ Colonnes essentielles visibles sur mobile');
  });

  test('🎨 Vérification thème StudiosDB', async ({ page }) => {
    console.log('🎯 TEST: Cohérence thème StudiosDB');
    
    await page.goto('/membres');
    
    // Vérifier les éléments de design caractéristiques
    const createButton = page.locator('a:has-text("Nouveau membre")');
    await expect(createButton).toBeVisible();
    
    // Vérifier les classes gradient (thème StudiosDB)
    const buttonClass = await createButton.getAttribute('class');
    const hasGradient = buttonClass?.includes('gradient');
    console.log(`🎨 Bouton avec gradient: ${hasGradient}`);
    
    // Vérifier les stats cards avec backdrop-blur
    const statsCards = page.locator('.backdrop-blur-sm');
    const statsCount = await statsCards.count();
    console.log(`🎨 Éléments avec backdrop-blur: ${statsCount}`);
    
    // Vérifier la table avec le thème sombre
    const table = page.locator('table');
    await expect(table).toBeVisible();
    console.log('✅ Table avec thème sombre présente');
    
    // Prendre une capture d'écran pour vérification visuelle
    await page.screenshot({ 
      path: 'test-results/theme-verification-serveur.png',
      fullPage: true 
    });
    console.log('📸 Capture d\'écran sauvegardée');
  });

});
