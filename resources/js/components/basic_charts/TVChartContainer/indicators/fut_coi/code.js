
export default class FutureCoi {
    constructor(PineJS) {
        this.PineJS = PineJS;
        console.log('FFU');

    }

    init(context, inputCallback) {
        this._context = context;
        this._input = inputCallback;

        var symbol = "AXISBANK";
        this._context.new_sym(symbol, this.PineJS.Std.period(this._context), this.PineJS.Std.period(this._context));
        console.log('HOLAINIT');
    }

    main(context, inputCallback) {
        this._context = context;
        this._input = inputCallback;

        this._context.select_sym(1);

        var v = this.PineJS.Std.close(this._context);
        console.log('HOLAMAIN');
        return [v];

    }

}



// export function futureCoi(PineJS) {
//
//
//     this.init = function(context, inputCallback) {
//         this._context = context;
//         this._input = inputCallback;
//
//         var symbol = "AXISBANK";
//         this._context.new_sym(symbol, PineJS.Std.period(this._context), PineJS.Std.period(this._context));
//     };
//
//     this.main = function(context, inputCallback) {
//         this._context = context;
//         this._input = inputCallback;
//
//         this._context.select_sym(1);
//
//         var v = PineJS.Std.close(this._context);
//         return [v];
//     }
// }
