export default class extends HTMLElement {
  connectedCallback() {
    const input = this.querySelector("input");

    if (!input) {
      return;
    }

    // Create an element for the <svg> icon
    const tmpDiv = document.createElement("div");
    tmpDiv.innerHTML = this.dataset.iconSvg;
    const svg = tmpDiv.firstChild;

    // Create the toggle button
    const btn = document.createElement("button");
    btn.setAttribute("type", "button");
    btn.classList.add("j-btn", "j-btn--tertiary", "j-btn--icon");
    btn.appendChild(svg);
    btn.ariaLabel = this.dataset.ariaLabel;

    btn.addEventListener("click", () => {
      input.type = input.type === "password" ? "text" : "password";
    });

    // Add it besides the <input>
    this.appendChild(btn);
  }
}
