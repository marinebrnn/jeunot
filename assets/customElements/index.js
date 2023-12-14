import clickableCard from "./clickableCard";
import flash from "./flash";
import navButton from "./navButton";
import passwordInput from "./passwordInput";

customElements.define("j-clickable-card", clickableCard);
customElements.define("j-flash", flash);
customElements.define("j-nav-button", navButton, { extends: "button" });
customElements.define("j-password-input", passwordInput);
