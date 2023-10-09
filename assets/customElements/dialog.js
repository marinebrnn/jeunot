export default class extends HTMLDialogElement {
  connectedCallback() {
    const dialogTrigger = this.dataset.dialogTrigger;

    if (dialogTrigger) {
      const trigger = document.querySelector(dialogTrigger);

      if (!trigger) {
        throw new Error(`element ${dialogTrigger} not found`);
      }

      trigger.addEventListener("click", () => {
        this.showModal();
      });
    }

    // By default, when clicking the back button, browsers show modals
    // as open if that's the state they were before navigation.
    // This is fine to get back to previous work, but we may want to
    // have a modal always be closed initially, e.g. a navigation menu.
    if (this.dataset.closed === "true") {
      this.close();
    }
  }
}
