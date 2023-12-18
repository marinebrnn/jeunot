export default class extends HTMLElement {
  connectedCallback() {
    const dialog = document.querySelector(`#${this.dataset.dialogId}`);
    const triggerButton = this.querySelector("button");

    if (!dialog || !triggerButton) {
      return;
    }

    triggerButton.addEventListener("click", (event) => {
      event.preventDefault();
      dialog.showModal();
    });

    const formSubmitValue = this.dataset.formSubmitValue;
    const form = document.querySelector(`#${this.dataset.formId}`);

    if (formSubmitValue && form) {
      dialog.addEventListener("close", () => {
        if (dialog.returnValue === formSubmitValue) {
          form.requestSubmit();
        }
      });
    }
  }
}
