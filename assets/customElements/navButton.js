export default class extends HTMLButtonElement {
  connectedCallback() {
    const closeBtn = document.querySelector(
      "[data-nav-button-target=closeBtn]",
    );
    const modal = document.querySelector("[data-nav-button-target=modal]");

    this.addEventListener("click", () => {
      this.setAttribute("aria-expanded", true);
      modal.hidden = false;
      closeBtn.focus();
    });

    closeBtn.addEventListener("click", () => {
      this.setAttribute("aria-expanded", false);
      modal.hidden = true;
      this.focus();
    });
  }
}
