import clickableCard from "./clickableCard";
import navButton from "./navButton";
import passwordInput from "./passwordInput";

customElements.define("j-clickable-card", clickableCard);
customElements.define("j-nav-button", navButton, { extends: "button" });
customElements.define("j-password-input", passwordInput);
