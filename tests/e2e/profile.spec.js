// @ts-check
import { test, expect } from "@playwright/test";

test.use({ storageState: "playwright/.auth/mathieu.json" });

test("Profile delete confirmation dialog", async ({ page }) => {
  await page.goto("/app/profile/edit");

  const deleteBtn = page.getByRole("button", {
    name: "Oui, supprimer mon compte",
  });
  await expect(deleteBtn).not.toBeVisible();

  const triggerBtn = page.getByRole("button", { name: "Supprimer mon compte" });
  await triggerBtn.click();
  await expect(deleteBtn).toBeVisible();

  const closeBtn = page.getByRole("button", { name: "Fermer" });
  await closeBtn.click();
  await expect(deleteBtn).not.toBeVisible();
  await triggerBtn.click();

  await page.getByRole("button", { name: "Non, garder mon compte" }).click();
  await expect(deleteBtn).not.toBeVisible();

  // Still on page
  await expect(page).toHaveURL("/app/profile/edit");
});
