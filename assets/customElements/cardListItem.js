export default class extends HTMLLIElement {
  connectedCallback() {
    const link = this.querySelector('[data-card-li-target="link"]');

    if (!link) {
      return;
    }

    // A click on the card should trigger link navigation.
    // But we also want to allow the user to select text.
    // Detect if the user may actually be selecting text by looking at
    // the time between mousedown and mouseup.
    // Source: https://inclusive-components.design/cards/

    let down = 0;
    let up = 0;

    this.onmousedown = () => {
      down = +new Date();
    };

    this.onmouseup = () => {
      up = +new Date();
      if (up - down < 200) {
        link.click();
      }
    };

    // Show the card as clickable.
    this.style.cursor = "pointer";
  }
}
