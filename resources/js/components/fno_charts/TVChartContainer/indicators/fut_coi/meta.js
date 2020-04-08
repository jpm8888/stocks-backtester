export const id = "FutureCOI"; //id should be same as title without spaces
export const title = "FutureCOI";
export const shortDesc = "Futures COI";

export const configFutureCoi = {
    "_metainfoVersion": 40,
    "id": `${id}@tv-basicstudies-1`,
    "scriptIdPart": "",
    "name": `${id}`,
    "description": id, //should be same as id
    "shortDescription": shortDesc,
    "is_hidden_study": false,
    "is_price_study": false,
    "isCustomIndicator": true,
    isTVScript: false,
    isTVScriptStub: true,
    "plots": [{"id": "plot_0", "type": "line"}, {"id": "plot_1", "type": "color", palette: "palette_0", "target": "plot_0",}],
    palettes: {
        palette_0: {
            valToIndex: {
                0 : 0,
                1 : 1,
            },
            colors: {
                0 : { name: "Color 0" },
                1 : { name: "Color 1" }
            },
        }
    },
    "defaults": {
        "styles": {
            "plot_0": {
                "linestyle": 0,
                "visible": true,
                "linewidth": 2,
                "plottype": 5,
                "trackPrice": false,
                "transparency": 20,
                "color": "#247AFD"
            }
        },
        palettes: {
            palette_0: {
                colors: {
                    0 : { color: "#ff4c1e"},
                    1 : { color: "#16ff22" }
                }
            }
        },
        "precision": 0, // Precision is set to one digit, e.g. 777.7
        "inputs": {}
    },

    "styles": {
        "plot_0": {
            "title": title, // Output name will be displayed in the Style window
            "histogramBase": 1,
        }
    },
    "inputs": [],
};

// Plot type:
//    1 - Histogram
//    2 - Line
//    3 - Cross
//    4 - Area
//    5 - Columns
//    6 - Circles
//    7 - Line With Breaks
//    8 - Area With Breaks
