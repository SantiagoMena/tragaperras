const cache = {};

export default class Symbol {
  constructor(name = Symbol.random(), className = "icon-class") {
    this.name = name;

    if (cache[name]) {
      this.img = cache[name].cloneNode();
    } else {
      this.img = new Image();
      this.img.src = require(`../assets/symbols/${name}.svg`).default;

      cache[name] = this.img;
    }

    this.img.classList.add(className);
  }
  

  static preload() {
    Symbol.symbols.forEach((symbol) => new Symbol(symbol));
  }

  static get symbols() {
    return [
      "at_at",
      "c3po",
      "darth_vader",
      "death_star",
      "falcon",
      "r2d2",
      "stormtrooper",
      "tie_ln",
      "yoda",
    ];
  }

  static random(hard = false) {
    if(hard) {
      return this.symbols[hard];
    }
    return this.symbols[Math.floor(Math.random() * this.symbols.length)];
  }
}
