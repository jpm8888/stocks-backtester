class MaxStrike {
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

            symbol = 'FOI:' + symbol;


            this._context.new_sym(symbol, this.PineJS.Std.period(this._context), this.PineJS.Std.period(this._context));
        };

        this.main = function(context, inputCallback) {
            this._context = context;
            this._input = inputCallback;
            this._context.select_sym(1);
            // let change_cum_fut_oi = this.PineJS.Std.close(this._context);
            // let change_cum_pe_oi = this.PineJS.Std.open(this._context);
            let max_pe_oi_strike = this.PineJS.Std.low(this._context);
            let max_ce_oi_strike = this.PineJS.Std.volume(this._context);
            return [max_pe_oi_strike, max_ce_oi_strike];
        }
    }
}

module.exports.MaxStrike = MaxStrike;
