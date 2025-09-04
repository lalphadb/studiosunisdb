import { chromium } from 'playwright';

async function testStudiosDB() {
  console.log('🚀 Démarrage des tests complets StudiosDB v5 Pro\n');

  const browser = await chromium.launch({ headless: false });
  const context = await browser.newContext();
  const page = await context.newPage();

  // Fonction utilitaire pour gérer les erreurs
  async function safeClick(selector, description) {
    try {
      const element = await page.$(selector);
      if (element) {
        await element.click();
        await page.waitForLoadState('networkidle', { timeout: 5000 });
        console.log(`    ✅ ${description} - OK`);
        return true;
      } else {
        console.log(`    ❌ ${description} - Élément non trouvé`);
        return false;
      }
    } catch (error) {
      console.log(`    ❌ ${description} - Erreur: ${error.message}`);
      return false;
    }
  }

  // Fonction utilitaire pour remplir un champ en toute sécurité
  async function safeFill(selector, value, description) {
    try {
      const element = await page.$(selector);
      if (element) {
        await element.fill(value);
        console.log(`    ✅ ${description} - OK`);
        return true;
      } else {
        console.log(`    ⚠️ ${description} - Champ non trouvé`);
        return false;
      }
    } catch (error) {
      console.log(`    ⚠️ ${description} - Erreur: ${error.message}`);
      return false;
    }
  }

  try {
    // Configuration
    const baseUrl = 'http://localhost:8001';
    const testResults = {
      navigation: [],
      membres: [],
      cours: [],
      erreurs: []
    };

    // 1. CONNEXION
    console.log('🔐 Test de connexion...');
    await page.goto(`${baseUrl}/login`, { waitUntil: 'networkidle' });

    // Attendre que le formulaire de connexion soit chargé
    await page.waitForSelector('input[name="email"]', { timeout: 10000 });

    await safeFill('input[name="email"]', 'superadmin@test.com', 'Remplissage email');
    await safeFill('input[name="password"]', 'password', 'Remplissage mot de passe');

    const loginSuccess = await safeClick('button[type="submit"]', 'Clic bouton connexion');

    if (loginSuccess) {
      // Attendre la redirection
      await page.waitForURL('**/dashboard**', { timeout: 10000 });
      console.log('✅ Connexion réussie\n');
    } else {
      console.log('❌ Échec de connexion\n');
      testResults.erreurs.push('Connexion échouée');
      return;
    }

    // 2. TEST DE NAVIGATION
    console.log('🧭 Test de navigation entre les pages...');

    const pagesToTest = [
      { name: 'Dashboard', url: '/dashboard', selector: '.dashboard' },
      { name: 'Membres', url: '/membres', selector: '.membres-index' },
      { name: 'Cours', url: '/cours', selector: '.cours-index' },
      { name: 'Présences', url: '/presences', selector: '.presences-index' },
      { name: 'Paiements', url: '/paiements', selector: '.paiements-index' },
      { name: 'Ceintures', url: '/ceintures', selector: '.ceintures-index' },
      { name: 'Statistiques', url: '/statistiques', selector: '.statistiques-index' },
      { name: 'Administration', url: '/admin', selector: '.admin-index' }
    ];

    for (const pageTest of pagesToTest) {
      try {
        console.log(`  → Test ${pageTest.name}...`);
        await page.goto(`${baseUrl}${pageTest.url}`, { waitUntil: 'networkidle', timeout: 10000 });

        // Attendre que la page se charge
        await page.waitForLoadState('networkidle', { timeout: 5000 });

        // Vérifier si on est sur la bonne page
        const currentUrl = page.url();
        const isOnPage = currentUrl.includes(pageTest.url) || currentUrl.includes(pageTest.name.toLowerCase());

        if (isOnPage) {
          testResults.navigation.push(`✅ ${pageTest.name} - OK`);
          console.log(`    ✅ ${pageTest.name} accessible`);
        } else {
          testResults.navigation.push(`❌ ${pageTest.name} - Redirection vers ${currentUrl}`);
          console.log(`    ❌ ${pageTest.name} - Problème de redirection`);
        }
      } catch (error) {
        testResults.navigation.push(`❌ ${pageTest.name} - Erreur: ${error.message}`);
        testResults.erreurs.push(`Navigation ${pageTest.name}: ${error.message}`);
        console.log(`    ❌ ${pageTest.name} - Erreur: ${error.message}`);
      }
    }

    console.log('\n📋 Test des liens de navigation...');

    // Tester les liens dans la sidebar
    try {
      const sidebarSelectors = ['nav a[href]', '.sidebar a[href]', '.navigation a[href]', 'aside a[href]'];
      let sidebarLinks = [];

      for (const selector of sidebarSelectors) {
        const links = await page.$$(selector);
        if (links.length > 0) {
          sidebarLinks = links;
          break;
        }
      }

      console.log(`  → ${sidebarLinks.length} liens trouvés dans la navigation`);

      for (const link of sidebarLinks.slice(0, 8)) { // Tester les 8 premiers liens
        try {
          const href = await link.getAttribute('href');
          if (href && !href.startsWith('#') && !href.startsWith('javascript:') && href !== '/' && !href.includes('logout')) {
            console.log(`    → Test du lien: ${href}`);
            await link.click();
            await page.waitForLoadState('networkidle', { timeout: 5000 });
            console.log(`    ✅ Lien ${href} - OK`);

            // Revenir à la page précédente pour continuer les tests
            await page.goBack();
            await page.waitForLoadState('networkidle', { timeout: 3000 });
          }
        } catch (error) {
          console.log(`    ❌ Lien défaillant (${href}): ${error.message}`);
        }
      }
    } catch (error) {
      console.log(`    ❌ Erreur test liens: ${error.message}`);
    }

    // 3. TEST CRUD MEMBRES
    console.log('\n👥 Test CRUD Membres...');

    // Aller à la page membres
    await page.goto(`${baseUrl}/membres`, { waitUntil: 'networkidle' });
    await page.waitForLoadState('networkidle');

    // Tester création membre
    try {
      console.log('  → Test création membre...');

      // Chercher le bouton "Créer" ou "Nouveau"
      const createSelectors = [
        'a[href*="create"]',
        'a[href*="nouveau"]',
        'button[type="submit"]:has-text("Créer")',
        'button[type="submit"]:has-text("Nouveau")',
        '.btn-primary',
        '[data-action="create"]'
      ];

      let createButton = null;
      for (const selector of createSelectors) {
        createButton = await page.$(selector);
        if (createButton) break;
      }

      if (createButton) {
        await createButton.click();
        await page.waitForLoadState('networkidle');

        // Vérifier qu'on est sur la page de création
        const currentUrl = page.url();
        if (currentUrl.includes('create') || currentUrl.includes('nouveau')) {
          console.log('    ✅ Page création membre accessible');

          // Tester le formulaire
          const formExists = await page.$('form');
          if (formExists) {
            console.log('    ✅ Formulaire de création trouvé');

            // Remplir quelques champs de test
            await safeFill('input[name="prenom"]', 'Test', 'Champ prénom');
            await safeFill('input[name="nom"]', 'Utilisateur', 'Champ nom');
            await safeFill('input[name="email"]', 'test@example.com', 'Champ email');
          } else {
            console.log('    ❌ Formulaire non trouvé');
          }
        } else {
          console.log('    ❌ Redirection création échouée');
        }
      } else {
        console.log('    ❌ Bouton création non trouvé');
        testResults.membres.push('❌ Bouton création membre manquant');
      }
    } catch (error) {
      console.log(`    ❌ Erreur création membre: ${error.message}`);
      testResults.membres.push(`❌ Création membre: ${error.message}`);
      testResults.erreurs.push(`Création membre: ${error.message}`);
    }

    // Tester suppression membre
    try {
      console.log('  → Test suppression membre...');

      // Retourner à la liste
      await page.goto(`${baseUrl}/membres`, { waitUntil: 'networkidle' });
      await page.waitForLoadState('networkidle');

      // Chercher des boutons de suppression
      const deleteSelectors = [
        'button[type="submit"]:has-text("Supprimer")',
        'a:has-text("Supprimer")',
        '.delete-btn',
        'button.btn-danger',
        '[data-action="delete"]',
        '.fa-trash',
        '.glyphicon-trash'
      ];

      let deleteButtons = [];
      for (const selector of deleteSelectors) {
        const buttons = await page.$$(selector);
        if (buttons.length > 0) {
          deleteButtons = buttons;
          break;
        }
      }

      if (deleteButtons.length > 0) {
        console.log('    ✅ Boutons suppression trouvés');

        // Tester un bouton (sans vraiment supprimer pour éviter les problèmes)
        const firstDeleteBtn = deleteButtons[0];
        const isVisible = await firstDeleteBtn.isVisible();

        if (isVisible) {
          console.log('    ✅ Bouton suppression visible');
          testResults.membres.push('✅ Suppression membre - Bouton visible');
        } else {
          console.log('    ⚠️ Bouton suppression masqué');
        }
      } else {
        console.log('    ❌ Aucun bouton suppression trouvé');
        testResults.membres.push('❌ Boutons suppression manquants');
      }
    } catch (error) {
      console.log(`    ❌ Erreur test suppression: ${error.message}`);
      testResults.membres.push(`❌ Test suppression: ${error.message}`);
    }

    // 4. TEST CRUD COURS
    console.log('\n📚 Test CRUD Cours...');

    // Aller à la page cours
    await page.goto(`${baseUrl}/cours`, { waitUntil: 'networkidle' });
    await page.waitForLoadState('networkidle');

    // Tester création cours
    try {
      console.log('  → Test création cours...');

      const createSelectors = [
        'a[href*="create"]',
        'a[href*="nouveau"]',
        'button[type="submit"]:has-text("Créer")',
        'button[type="submit"]:has-text("Nouveau")',
        '.btn-primary',
        '[data-action="create"]'
      ];

      let createButton = null;
      for (const selector of createSelectors) {
        createButton = await page.$(selector);
        if (createButton) break;
      }

      if (createButton) {
        await createButton.click();
        await page.waitForLoadState('networkidle');

        const currentUrl = page.url();
        if (currentUrl.includes('create') || currentUrl.includes('nouveau')) {
          console.log('    ✅ Page création cours accessible');

          const formExists = await page.$('form');
          if (formExists) {
            console.log('    ✅ Formulaire de création trouvé');

            // Tester quelques champs
            await safeFill('input[name="nom"]', 'Cours Test Playwright', 'Champ nom cours');
            await safeFill('select[name="jour_semaine"]', 'lundi', 'Champ jour semaine');
          }
        } else {
          console.log('    ❌ Redirection création cours échouée');
        }
      } else {
        console.log('    ❌ Bouton création cours non trouvé');
        testResults.cours.push('❌ Bouton création cours manquant');
      }
    } catch (error) {
      console.log(`    ❌ Erreur création cours: ${error.message}`);
      testResults.cours.push(`❌ Création cours: ${error.message}`);
      testResults.erreurs.push(`Création cours: ${error.message}`);
    }

    // Tester suppression cours
    try {
      console.log('  → Test suppression cours...');

      await page.goto(`${baseUrl}/cours`, { waitUntil: 'networkidle' });
      await page.waitForLoadState('networkidle');

      const deleteSelectors = [
        'button[type="submit"]:has-text("Supprimer")',
        'a:has-text("Supprimer")',
        '.delete-btn',
        'button.btn-danger',
        '[data-action="delete"]',
        '.fa-trash',
        '.glyphicon-trash'
      ];

      let deleteButtons = [];
      for (const selector of deleteSelectors) {
        const buttons = await page.$$(selector);
        if (buttons.length > 0) {
          deleteButtons = buttons;
          break;
        }
      }

      if (deleteButtons.length > 0) {
        console.log('    ✅ Boutons suppression cours trouvés');
        testResults.cours.push('✅ Suppression cours - Boutons visibles');
      } else {
        console.log('    ❌ Aucun bouton suppression cours trouvé');
        testResults.cours.push('❌ Boutons suppression cours manquants');
      }
    } catch (error) {
      console.log(`    ❌ Erreur test suppression cours: ${error.message}`);
      testResults.cours.push(`❌ Test suppression cours: ${error.message}`);
    }

    // 5. RAPPORT FINAL
    console.log('\n📊 RAPPORT FINAL DES TESTS\n');

    console.log('🧭 NAVIGATION:');
    testResults.navigation.forEach(result => console.log(`  ${result}`));

    console.log('\n👥 MEMBRES:');
    if (testResults.membres.length === 0) {
      console.log('  ⚠️ Aucun test spécifique effectué');
    } else {
      testResults.membres.forEach(result => console.log(`  ${result}`));
    }

    console.log('\n📚 COURS:');
    if (testResults.cours.length === 0) {
      console.log('  ⚠️ Aucun test spécifique effectué');
    } else {
      testResults.cours.forEach(result => console.log(`  ${result}`));
    }

    if (testResults.erreurs.length > 0) {
      console.log('\n❌ ERREURS DÉTECTÉES:');
      testResults.erreurs.forEach(error => console.log(`  ${error}`));
    }

    console.log('\n🎯 RECOMMANDATIONS:');
    console.log('  1. Vérifier les routes manquantes pour les pages non accessibles');
    console.log('  2. Ajouter les boutons CRUD manquants avec des classes CSS appropriées');
    console.log('  3. Tester les formulaires avec validation côté serveur');
    console.log('  4. Vérifier les permissions utilisateur pour les actions CRUD');
    console.log('  5. Améliorer la gestion des erreurs JavaScript côté frontend');
    console.log('  6. Ajouter des indicateurs de chargement pour les actions asynchrones');
    console.log('  7. Uniformiser les styles des boutons et formulaires');

    // Résumé exécutif
    const totalTests = testResults.navigation.length + testResults.membres.length + testResults.cours.length;
    const erreursCount = testResults.erreurs.length;

    console.log('\n📈 RÉSUMÉ EXÉCUTIF:');
    console.log(`  Tests exécutés: ${totalTests}`);
    console.log(`  Erreurs détectées: ${erreursCount}`);
    console.log(`  Taux de succès: ${((totalTests - erreursCount) / totalTests * 100).toFixed(1)}%`);

    if (erreursCount === 0) {
      console.log('  🎉 Tous les tests sont passés ! L\'application semble fonctionnelle.');
    } else {
      console.log('  ⚠️ Des améliorations sont nécessaires pour optimiser l\'expérience utilisateur.');
    }

  } catch (error) {
    console.error('❌ Erreur générale:', error.message);
    console.error('Stack trace:', error.stack);
  } finally {
    console.log('\n🔚 Fermeture du navigateur...');
    await browser.close();
  }
}

// Lancer les tests
testStudiosDB().catch(console.error);
