import { test, expect } from '@playwright/test';

test.describe('Cours Creation Tests', () => {
  test.beforeEach(async ({ page }) => {
    // Aller à la page de connexion et se connecter comme superadmin
    await page.goto('http://127.0.0.1:8001/login');
    await page.fill('input[name="email"]', 'superadmin@studiosdb.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    
    // Attendre la redirection vers dashboard
    await expect(page).toHaveURL(/dashboard/);
  });

  test('Création cours privé sans instructeur', async ({ page }) => {
    console.log('🎯 Test: Création cours privé sans instructeur');
    
    // Aller à la page de création de cours
    await page.goto('http://127.0.0.1:8001/cours/create');
    await expect(page.locator('h1')).toContainText('Nouveau Cours');
    
    // Remplir le formulaire
    await page.fill('input[name="nom"]', 'Cours Privé Test Playwright');
    
    // Sélectionner niveau privé
    await page.selectOption('select[name="niveau"]', 'prive');
    
    // Âges
    await page.fill('input[name="age_min"]', '10');
    await page.fill('input[name="age_max"]', '15');
    
    // Places
    await page.fill('input[name="places_max"]', '1');
    
    // Laisser instructeur vide (test principal)
    // await page.selectOption('select[name="instructeur_id"]', ''); // Reste vide
    
    // Horaires
    await page.selectOption('select[name="jour_semaine"]', 'lundi');
    await page.fill('input[name="heure_debut"]', '19:00');
    await page.fill('input[name="heure_fin"]', '20:00');
    
    // Dates (même jour pour cours privé 1h)
    await page.fill('input[name="date_debut"]', '2025-09-15');
    await page.fill('input[name="date_fin"]', '2025-09-15');
    
    // Tarification
    await page.selectOption('select[name="type_tarif"]', 'horaire');
    await page.fill('input[name="montant"]', '65.00');
    
    // Description
    await page.fill('textarea[name="description"]', 'Cours privé test automatisé avec Playwright');
    
    // Soumettre le formulaire
    console.log('📝 Soumission du formulaire...');
    await page.click('button[type="submit"]');
    
    // Vérifier la redirection vers la liste des cours (succès)
    await expect(page).toHaveURL(/cours$/, { timeout: 10000 });
    
    // Vérifier que le cours apparaît dans la liste
    await expect(page.locator('text=Cours Privé Test Playwright')).toBeVisible();
    
    console.log('✅ Cours créé avec succès sans instructeur');
  });

  test('Création cours avec tous les niveaux', async ({ page }) => {
    console.log('🎯 Test: Validation tous les niveaux enum');
    
    const niveaux = [
      { value: 'tous', label: 'Tous niveaux' },
      { value: 'debutant', label: 'Débutant' },
      { value: 'intermediaire', label: 'Intermédiaire' },
      { value: 'avance', label: 'Avancé' },
      { value: 'prive', label: 'Privé' },
      { value: 'competition', label: 'Compétition' },
      { value: 'a_la_carte', label: 'À la carte' }
    ];
    
    for (const niveau of niveaux) {
      console.log(`🔍 Test niveau: ${niveau.label}`);
      
      await page.goto('http://127.0.0.1:8001/cours/create');
      
      // Formulaire minimal pour tester seulement le niveau
      await page.fill('input[name="nom"]', `Test ${niveau.label}`);
      await page.selectOption('select[name="niveau"]', niveau.value);
      await page.fill('input[name="age_min"]', '5');
      await page.fill('input[name="places_max"]', '10');
      await page.selectOption('select[name="jour_semaine"]', 'samedi');
      await page.fill('input[name="heure_debut"]', '10:00');
      await page.fill('input[name="heure_fin"]', '11:00');
      await page.fill('input[name="date_debut"]', '2025-09-20');
      await page.selectOption('select[name="type_tarif"]', 'mensuel');
      await page.fill('input[name="montant"]', '50.00');
      
      // Soumettre
      await page.click('button[type="submit"]');
      
      // Vérifier succès (pas d'erreur enum)
      await expect(page).toHaveURL(/cours$/, { timeout: 5000 });
      await expect(page.locator(`text=Test ${niveau.label}`)).toBeVisible();
    }
    
    console.log('✅ Tous les niveaux enum testés avec succès');
  });

  test('Validation dates même jour', async ({ page }) => {
    console.log('🎯 Test: Validation dates même jour (cours 1h)');
    
    await page.goto('http://127.0.0.1:8001/cours/create');
    
    // Cours test même jour
    await page.fill('input[name="nom"]', 'Test Même Jour');
    await page.selectOption('select[name="niveau"]', 'prive');
    await page.fill('input[name="age_min"]', '10');
    await page.fill('input[name="places_max"]', '1');
    await page.selectOption('select[name="jour_semaine"]', 'mercredi');
    await page.fill('input[name="heure_debut"]', '14:00');
    await page.fill('input[name="heure_fin"]', '15:00');
    
    // MÊME DATE début et fin
    await page.fill('input[name="date_debut"]', '2025-09-17');
    await page.fill('input[name="date_fin"]', '2025-09-17');
    
    await page.selectOption('select[name="type_tarif"]', 'horaire');
    await page.fill('input[name="montant"]', '75.00');
    
    await page.click('button[type="submit"]');
    
    // Doit réussir (pas d'erreur validation dates)
    await expect(page).toHaveURL(/cours$/, { timeout: 5000 });
    await expect(page.locator('text=Test Même Jour')).toBeVisible();
    
    console.log('✅ Validation même jour fonctionne');
  });

  test('Test erreur dates incohérentes', async ({ page }) => {
    console.log('🎯 Test: Erreur validation dates incohérentes');
    
    await page.goto('http://127.0.0.1:8001/cours/create');
    
    await page.fill('input[name="nom"]', 'Test Erreur Dates');
    await page.selectOption('select[name="niveau"]', 'debutant');
    await page.fill('input[name="age_min"]', '6');
    await page.fill('input[name="places_max"]', '8');
    await page.selectOption('select[name="jour_semaine"]', 'jeudi');
    
    // ERREUR: heure fin AVANT heure début
    await page.fill('input[name="heure_debut"]', '15:00');
    await page.fill('input[name="heure_fin"]', '14:00'); // Incohérent
    
    await page.fill('input[name="date_debut"]', '2025-09-18');
    await page.selectOption('select[name="type_tarif"]', 'mensuel');
    await page.fill('input[name="montant"]', '60.00');
    
    await page.click('button[type="submit"]');
    
    // Doit afficher erreur validation
    await expect(page.locator('text=heure de fin doit être après')).toBeVisible();
    
    console.log('✅ Validation erreur dates fonctionne');
  });
});
