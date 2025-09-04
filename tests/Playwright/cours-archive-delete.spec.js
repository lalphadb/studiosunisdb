// Test Playwright minimal pour suppression / archivage cours (ESM)
// Pré-requis: user superadmin@test.com / password
import { test, expect } from '@playwright/test';

async function login(page){
  await page.goto('http://127.0.0.1:8001/login', { waitUntil: 'domcontentloaded' });
  await page.locator('#email').fill('superadmin@test.com');
  await page.locator('#password').fill('password');
  await Promise.all([
    page.waitForURL('**/dashboard'),
    page.click('button:has-text("Se connecter")')
  ]);
}

test.describe('Cours suppression / archivage', () => {
  test('Archiver puis voir dans Archives et suppression définitive', async ({ page }) => {
    test.setTimeout(60000);
    await login(page);
    await page.goto('http://127.0.0.1:8001/cours');

    // Compter lignes initiales
  const rowsInitial = await page.locator('tbody tr').count();
    expect(rowsInitial).toBeGreaterThan(0);

  // Archiver un cours actif (Cancel => archive soft delete)
  await page.once('dialog', dialog => dialog.dismiss());
  await page.locator('[data-testid="btn-delete-cours"]').first().click();

  // Passer en mode Archives
  await page.locator('[data-testid="archives-toggle"] button:has-text("Archives")').click();
  await page.waitForURL('**/cours?archives=1');

  // Compter les lignes archivées (ligne barrée)
  const archivedRowsLocator = page.locator('tr.line-through');
  await expect(archivedRowsLocator.first()).toBeVisible();
  const archivedCountBefore = await archivedRowsLocator.count();

  // Suppression définitive du premier archivé
  await page.once('dialog', dialog => dialog.accept());
  await archivedRowsLocator.first().locator('[data-testid="btn-delete-cours"]').click();
  await page.waitForURL('**/cours'); // après delete redirect index actifs

  // Revenir aux archives pour recompter
  await page.locator('[data-testid="archives-toggle"] button:has-text("Archives")').click();
  await page.waitForURL('**/cours?archives=1');
  const archivedCountAfter = await page.locator('tr.line-through').count();
  expect(archivedCountAfter).toBe(archivedCountBefore - 1);

  // Revenir actifs et vérifier que total actif <= initial - 1
  await page.locator('[data-testid="archives-toggle"] button:has-text("Actifs")').click();
  await page.waitForURL('**/cours');
  const rowsAfter = await page.locator('tbody tr').count();
  expect(rowsAfter).toBeLessThanOrEqual(rowsInitial - 1);
  });
});
