export default class extends HTMLElement {
  connectedCallback() {
    const button = this.querySelector("button");
    const dialog = document.querySelector(`#${this.dataset.dialogId}`);

    if (!button || !dialog) {
      return;
    }

    button.addEventListener("click", () => {
      dialog.close("close");
    });
  }
}
