// @ts-check
import { test, expect } from "@playwright/test";
import { getLinks, getNavElements } from "./util";

test("Mobile home nav", async ({ page }) => {
  await page.goto("/");

  const [nav, navBtn, closeNavBtn] = getNavElements(page);

  await expect(nav).not.toBeVisible();
  await expect(navBtn).toBeVisible();
  await expect(closeNavBtn).not.toBeVisible();

  await navBtn.click();

  await expect(nav).toBeVisible();
  await expect(navBtn).toBeVisible(); // But hidden by modal
  await expect(closeNavBtn).toBeVisible();

  expect(await getLinks(nav)).toEqual([
    ["Accueil", "/"],
    ["Événements", "/events"],
    ["Se connecter", "/login"],
    ["Créer un compte", "/register"],
    ["Aide", "#"],
    ["Politique de confidentialité", "#"],
    ["Conditions générales d'utilisation", "#"],
  ]);

  await closeNavBtn.click();
  await expect(nav).not.toBeVisible();
  await expect(navBtn).toBeVisible();
  await expect(closeNavBtn).not.toBeVisible();
});

test.describe("authenticated", () => {
  test.use({ storageState: "playwright/.auth/mathieu.json" });

  test("Mobile app nav", async ({ page }) => {
    await page.goto("/app");

    const [nav, navBtn, closeNavBtn] = getNavElements(page);

    await expect(nav).not.toBeVisible();
    await expect(navBtn).toBeVisible();
    await expect(closeNavBtn).not.toBeVisible();

    await navBtn.click();

    await expect(nav).toBeVisible();
    await expect(navBtn).toBeVisible(); // But hidden by modal
    await expect(closeNavBtn).toBeVisible();

    expect(await getLinks(nav)).toEqual([
      ["Mon espace", "/app"],
      ["Événements", "/events"],
      ["Mon profil", "/app/profile/0b507871-8b5e-4575-b297-a630310fc06e"],
      ["Aide", "#"],
      ["Se déconnecter", "/logout"],
      ["Politique de confidentialité", "#"],
      ["Conditions générales d'utilisation", "#"],
    ]);

    await closeNavBtn.click();
    await expect(nav).not.toBeVisible();
    await expect(navBtn).toBeVisible();
    await expect(closeNavBtn).not.toBeVisible();
  });
});
