import clickableCard from "./clickableCard";
import dialogClose from "./dialogClose";
import dialogTrigger from "./dialogTrigger";
import navButton from "./navButton";
import passwordInput from "./passwordInput";

customElements.define("j-clickable-card", clickableCard);
customElements.define("j-dialog-close", dialogClose);
customElements.define("j-dialog-trigger", dialogTrigger);
customElements.define("j-nav-button", navButton, { extends: "button" });
customElements.define("j-password-input", passwordInput);
