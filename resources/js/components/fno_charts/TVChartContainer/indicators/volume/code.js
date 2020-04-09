class CustomVolume {
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

            console.log(this.PineJS.Std);
        };

        this.main = function(context, inputCallback) {
            this._context = context;
            this._input = inputCallback;
            this._context.select_sym(1);
            // let change_cum_fut_oi = this.PineJS.Std.close(this._context);
            // let volume_15 = this.PineJS.Std.open(this._context);
            // let volume_10 = this.PineJS.Std.high(this._context);
            // let volume_5 = this.PineJS.Std.low(this._context);
            let volume = this.PineJS.Std.volume(this._context);


            // signal = sma(macd, 9)
            //let sma = this.PineJS.Std.sma(volume, 15);


            // let change_cum_ce_oi = this.PineJS.Std.high(this._context);
            // let cum_ce_oi = this.PineJS.Std.high(this._context);
            // return [volume, volume_15, volume_10, volume_5];
            return [volume];
        }
    }
}

module.exports.CustomVolume = CustomVolume;
