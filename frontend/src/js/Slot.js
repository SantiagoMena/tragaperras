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
      let { resultados: { resultados, juegosExtra }, gananciaTotal } = data;
      let { tablero, ganancias } = resultados;
      let { elementos } = tablero;

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
      ).then(() => this.onSpinEnd({ganancias, gananciaTotal, juegosExtra}));

      
    });

  }

  onSpinStart() {
    this.spinButton.disabled = true;

    console.log("SPIN START");
  }

  onSpinEnd({ganancias, gananciaTotal, juegosExtra}) {
    this.spinButton.disabled = false;

    this.imprimirGanancias({ganancias});

    if( typeof juegosExtra !== 'undefined' && juegosExtra.length > 0 ) {

      alert(`Bonus! Tiene ${ juegosExtra.length } juegos extra!`);
      return window.setTimeout(() => this.spinJuegosExtra({juegosExtra, gananciaTotal}), 200);

    } else {
      alert(`Ganancia! Tiene $${ gananciaTotal } de ganancia total!`);
    }


    console.log("SPIN END");

    if (this.autoPlayCheckbox.checked)
      return window.setTimeout(() => this.spin(), 200);
  }

  spinJuegosExtra({juegosExtra, gananciaTotal}) {
    this.onSpinStart();

    this.currentSymbols = this.nextSymbols;

    let { resultados } = juegosExtra.shift();
    
    let { tablero, ganancias } = resultados;
    let { elementos } = tablero;

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
    ).then(() => this.onSpinEnd({ganancias, gananciaTotal, juegosExtra}));


  }

  imprimirGanancias({ganancias}) {
    console.log(ganancias);
    console.log(Object.values(ganancias));

    if( Object.values(ganancias).length > 0 ) {
      Object.values(ganancias).forEach(gananciaObj => {
        let { ganancia, elemento: { id }, linea: { numero, apariciones } } = gananciaObj;
  
        alert(`Ganaste: $${ ganancia } con el elemento "${ id }" en la linea #${ numero } con ${ apariciones } apariciones!`)
      });
    }
  }
}
