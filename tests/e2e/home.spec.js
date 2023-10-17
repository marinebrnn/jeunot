// @ts-check
import { test, expect } from "@playwright/test";

test.use({ storageState: "playwright/.auth/mathieu.json" });

test("Home page", async ({ page }) => {
  await page.goto("/");

  await expect(page.getByRole("heading", { level: 1 })).toBeVisible();
});
