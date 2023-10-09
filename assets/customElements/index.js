import cardListItem from "./cardListItem";
import dialog from "./dialog";

customElements.define("j-card-li", cardListItem, { extends: "li" });
customElements.define("j-dialog", dialog, { extends: "dialog" });
