export default class extends HTMLElement {
  connectedCallback() {
    const closeBtn = document.querySelector(
      "[data-nav-button-target=closeBtn]",
    );
    const modal = document.querySelector("[data-nav-button-target=modal]");

    const triggerBtn = this.querySelector("button");

    triggerBtn.addEventListener("click", () => {
      triggerBtn.setAttribute("aria-expanded", true);
      modal.hidden = false;
      closeBtn.focus();
    });

    closeBtn.addEventListener("click", () => {
      triggerBtn.setAttribute("aria-expanded", false);
      modal.hidden = true;
      triggerBtn.focus();
    });
  }
}
