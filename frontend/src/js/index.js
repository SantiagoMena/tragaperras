import Slot from "./Slot.js";
import '../css/animate.min.css';

const config = {
  inverted: false, // true: reels spin from top to bottom; false: reels spin from bottom to top
};

const slot = new Slot(document.getElementById("slot"), config);
