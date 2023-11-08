// @ts-check
import { test, expect } from "@playwright/test";
import { getLinks, getNavElements } from "./util";

test("Desktop home nav", async ({ page }) => {
  await page.goto("/");

  const [nav, navBtn, closeNavBtn] = getNavElements(page);

  await expect(nav).toBeVisible();
  await expect(navBtn).not.toBeVisible();
  await expect(closeNavBtn).not.toBeVisible();

  expect(await getLinks(nav)).toEqual([
    ["Accueil", "/"],
    ["Événements", "/events"],
    ["Articles", "/posts"],
    ["Se connecter", "/login"],
    ["Créer un compte", "/register"],
  ]);
});

test.describe("authenticated", () => {
  test.use({ storageState: "playwright/.auth/mathieu.json" });

  test("Desktop app nav", async ({ page }) => {
    await page.goto("/app");

    const [nav, navBtn, closeNavBtn] = getNavElements(page);

    await expect(nav).toBeVisible();
    await expect(navBtn).not.toBeVisible();
    await expect(closeNavBtn).not.toBeVisible();

    expect(await getLinks(nav)).toEqual([
      ["Mon espace", "/app"],
      ["Événements", "/events"],
      ["Articles", "/posts"],
      ["Mon profil", "/app/profile/0b507871-8b5e-4575-b297-a630310fc06e"],
      ["Se déconnecter", "/logout"],
    ]);
  });
});
