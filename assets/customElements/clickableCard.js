export default class extends HTMLElement {
  connectedCallback() {
    const linkSelector = '[data-clickable-card-target="link"]';

    const link = this.querySelector(linkSelector);

    if (!link) {
      console.warn(`no ${linkSelector} found`, this);
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
  }
}
