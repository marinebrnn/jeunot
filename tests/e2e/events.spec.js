// @ts-check
import { test, expect } from "@playwright/test";

test.use({ storageState: "playwright/.auth/mathieu.json" });

test("Event unregister confirmation dialog", async ({ page }) => {
  await page.goto("/events/89f72b23-55e9-4975-b640-da24890095b7");

  const unregisterBtn = page.getByRole("button", {
    name: "Oui, me désinscrire",
  });
  await expect(unregisterBtn).not.toBeVisible();

  const triggerBtn = page.getByRole("button", {
    name: "Annuler mon inscription",
  });
  await triggerBtn.click();
  await expect(unregisterBtn).toBeVisible();

  const closeBtn = page.getByRole("button", { name: "Fermer" });
  await closeBtn.click();
  await expect(unregisterBtn).not.toBeVisible();
  await triggerBtn.click();

  await page.getByRole("button", { name: "Non, rester inscrit·e" }).click();
  await expect(unregisterBtn).not.toBeVisible();

  // Still on page
  await expect(page).toHaveURL("/events/89f72b23-55e9-4975-b640-da24890095b7");
});
