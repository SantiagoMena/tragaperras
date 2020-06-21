import Reel from "./Reel.js";
import Symbol from "./Symbol.js";
const axios = require('axios').default;

export default class Slot {
  constructor(domElement, config = {}) {
    Symbol.preload();

    this.currentSymbols = [
      ["death_star", "death_star", "death_star"],
      ["death_star", "death_star", "death_star"],
      ["death_star", "death_star", "death_star"],
      ["death_star", "death_star", "death_star"],
      ["death_star", "death_star", "death_star"],
    ];

    this.nextSymbols = [
      ["death_star", "death_star", "death_star"],
      ["death_star", "death_star", "death_star"],
      ["death_star", "death_star", "death_star"],
      ["death_star", "death_star", "death_star"],
      ["death_star", "death_star", "death_star"],
    ];

    this.container = domElement;

    this.reels = Array.from(this.container.getElementsByClassName("reel")).map(
      (reelContainer, idx) =>
        new Reel(reelContainer, idx, this.currentSymbols[idx])
    );

    this.spinButton = document.getElementById("spin");
    this.spinButton.addEventListener("click", () => this.spin());

    this.autoPlayCheckbox = document.getElementById("autoplay");

    if (config.inverted) {
      this.container.classList.add("inverted");
    }
  }

  spin() {
    this.onSpinStart();

    this.currentSymbols = this.nextSymbols;

    let partida = axios.get('http://localhost:8081');

    partida.then((response) => {
      let { data } = response;
      let { resultados: { resultados } } = data;
      let { tablero } = resultados;
      let { elementos } = tablero;


      console.log({elementos});

      this.nextSymbols = [
        [elementos[0][0].imagen, elementos[1][0].imagen, elementos[2][0].imagen],
        [elementos[0][1].imagen, elementos[1][1].imagen, elementos[2][1].imagen],
        [elementos[0][2].imagen, elementos[1][2].imagen, elementos[2][2].imagen],
        [elementos[0][3].imagen, elementos[1][3].imagen, elementos[2][3].imagen],
        [elementos[0][4].imagen, elementos[1][4].imagen, elementos[2][4].imagen],
      ];
  
      return Promise.all(
        this.reels.map((reel) => {
          reel.renderSymbols(this.nextSymbols[reel.idx]);
          return reel.spin();
        })
      ).then(() => this.onSpinEnd());

      
    });

  }

  onSpinStart() {
    this.spinButton.disabled = true;

    console.log("SPIN START");
  }

  onSpinEnd() {
    this.spinButton.disabled = false;

    console.log("SPIN END");

    if (this.autoPlayCheckbox.checked)
      return window.setTimeout(() => this.spin(), 200);
  }
}
