import cardListItem from "./cardListItem";
import navButton from "./navButton";

customElements.define("j-card-li", cardListItem, { extends: "li" });
customElements.define("j-nav-button", navButton, { extends: "button" });
