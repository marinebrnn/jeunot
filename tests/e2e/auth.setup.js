// @ts-check
const { test: setup } = require("@playwright/test");

setup("authenticate mathieu", async ({ page }) => {
  await page.goto("/login");
  await page.fill("input[name='email']", "mathieu@fairness.coop");
  await page.fill("input[name='password']", "password123");
  await page.getByRole("button", { name: "Se connecter" }).click();
  await page.waitForURL("/app");
  await page.context().storageState({ path: "playwright/.auth/mathieu.json" });
});
