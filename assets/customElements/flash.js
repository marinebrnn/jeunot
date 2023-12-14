export default class extends HTMLElement {
  connectedCallback() {
    setTimeout(() => this.remove(), 5000);
  }
}
