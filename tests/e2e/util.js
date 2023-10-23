// @ts-check

/**
 * @typedef {import('@playwright/test').Page} Page
 * @typedef {import('@playwright/test').Locator} Locator
 */

/**
 * @param {Locator} locator
 * @returns {Promise<[string, string|null][]>}
 */
export async function getLinks(locator) {
  return await locator
    .getByRole("link")
    .evaluateAll((links) =>
      links.map((link) => [
        link.textContent?.trim() || "",
        link.getAttribute("href"),
      ]),
    );
}

/**
 * @param {Page} page
 * @returns {[Locator, Locator, Locator]}
 */
export function getNavElements(page) {
  return [
    page.getByRole("navigation", { name: "Navigation principale" }),
    page.getByRole("button", { name: "Ouvrir la navigation principale" }),
    page.getByRole("button", { name: "Fermer la navigation principale" }),
  ];
}
