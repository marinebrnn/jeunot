import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  static targets = ["this"];

  removeElement() {
    this.thisTarget.remove();
  }
}
