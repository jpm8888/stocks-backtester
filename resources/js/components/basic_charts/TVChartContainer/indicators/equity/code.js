class Equity {
    constructor() {
        this.init = function(context, inputCallback) {
            this._context = context;
            this._input = inputCallback;
            let ticker = this.PineJS.Std.ticker(this._context);
            const regex = /(["'])(?:(?=(\\?))\2.)*?\1/g;
            const found = ticker.match(regex);
            let symbol = "";
            if (found && found.length > 0){
                symbol = found[0].split('"').join("");
            }else{
                console.log('No symbol ->' + symbol);
            }
            this._context.new_sym(symbol, this.PineJS.Std.period(this._context), this.PineJS.Std.period(this._context));
        };

        this.main = function(context, inputCallback) {
            this._context = context;
            this._input = inputCallback;
            this._context.select_sym(1);
            let v = this.PineJS.Std.close(this._context);
            return [v];
        }
    }
}

module.exports.Equity = Equity;
