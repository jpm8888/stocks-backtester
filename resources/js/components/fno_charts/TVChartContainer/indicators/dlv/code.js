class Delivery {
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

            symbol = 'OTHER:' + symbol;


            this._context.new_sym(symbol, this.PineJS.Std.period(this._context), this.PineJS.Std.period(this._context));
        };

        this.main = function(context, inputCallback) {
            this._context = context;
            this._input = inputCallback;
            this._context.select_sym(1);
            let value = this.PineJS.Std.open(this._context);
            // let change_cum_pe_oi = this.PineJS.Std.open(this._context);
            // let change_cum_ce_oi = this.PineJS.Std.high(this._context);
            // let cum_ce_oi = this.PineJS.Std.high(this._context);
            // let color = (change_cum_fut_oi >= 0) ? 1 : 0;
            return [value];
        }
    }
}

module.exports.Delivery = Delivery;
