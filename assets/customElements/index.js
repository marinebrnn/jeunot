import clickableCard from "./clickableCard";
import navButton from "./navButton";

customElements.define("j-clickable-card", clickableCard);
customElements.define("j-nav-button", navButton, { extends: "button" });
