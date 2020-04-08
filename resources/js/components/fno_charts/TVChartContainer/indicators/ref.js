window.test = function(PineJS) {
    return {
        name: "Equity",
        metainfo: {
            _metainfoVersion: 40,
            id: "Equity@tv-basicstudies-1",
            scriptIdPart: "",
            name: "Equity",
            description: "test",
            shortDescription: "test script",
            is_hidden_study: false,
            is_price_study: true,
            isCustomIndicator: true,
            plots: [{
                "id": "plot_fibtlow",
                "type": "line",
            }, {
                "id": "plot_2",
                "type": "colorer",
                "target": "plot_fibtlow",
                "palette": 'paletteId1',
            }, {
                "id": "plot_fibthigh",
                "type": "line",
            }, {
                "id": "plot_3",
                "type": "colorer",
                "target": "plot_fibthigh",
                "palette": 'paletteId1',
            }, {
                "id": "plot_0",
                "type": "line",
            }, {
                "id": "plot_1",
                "type": "colorer",
                "target": "plot_0",
                "palette": 'paletteId1',
            }, ],
            palettes: {
                paletteId1: {
                    valToIndex: {
                        0: 0,
                        1: 1,
                    },
                    colors: {
                        0: {
                            name: 'First color',
                        },
                        1: {
                            name: 'Second color',
                        },
                    },
                },
            },
            defaults: {
                palettes: {
                    paletteId1: {
                        colors: {
                            0: {
                                color: '#FFAA00',
                                width: 1,
                                style: 1,
                            },
                            1: {
                                color: '#0055FF',
                                width: 3,
                                style: 1,
                            },
                        },
                    },
                },
                styles: {
                    plot_0: {
                        linestyle: 0, //0 (solid), 1 (dotted), 2 (dashed), 3 (large dashed)
                        visible: true,
                        linewidth: 1,
                        plottype: 2, //line with breaks
                        trackPrice: false, // Show price line?
                        transparency: 0, // Plot transparency, in percent.
                        color: "red" // Plot color in #RRGGBB format
                    },
                },
                precision: 4,
                inputs: {},
            },
            styles: {
                plot_0: {
                    title: "close value",
                    histogramBase: 0,
                },
            },
            inputs: [],
        },
        constructor: function() {
            this.main = function(context, inputCallback) {

                // try {
                this._context = context;
                this._input = inputCallback;
                var close = this._context.new_var(PineJS.Std.close(this._context));
                var fibTlow = PineJS.Std.ema(close, 377, this._context);
                var fibTHigh = PineJS.Std.ema(close, 843, this._context);

                var spanColor2 = 1
                if (fibTlow >= fibTHigh) {
                    spanColor2 = 0
                }

                return [{
                    offset: 0,
                    value: fibTlow,
                }, {
                    offset: 0,
                    value: 1,
                }, {
                    offset: 0,
                    value: fibTHigh,
                }, {
                    value: 1,
                    offset: 0,
                }, {
                    value: close,
                    offset: 5,
                }, {
                    value: spanColor2,
                    offset: 5,
                }];
            }
        }
    }
}
