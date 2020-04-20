export const id = "OptionCOI"; //id should be same as title without spaces
export const title = "OptionCOI";
export const shortDesc = "Option COI";
export const plotType = 2;

export const configOptionCoi = {
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
    "plots": [{"id": "PE", "type": "line"}, {"id": "CE", "type": "line"}],
    "defaults": {
        "styles": {
            "PE": {
                "linestyle": 0,
                "visible": true,
                "linewidth": 2,
                "plottype": {plotType},
                "trackPrice": false,
                "transparency": 0,
                "color": "#32BF84"
            },
            "CE": {
                "linestyle": 0,
                "visible": true,
                "linewidth": 2,
                "plottype": {plotType},
                "trackPrice": false,
                "transparency": 0,
                "color": "#FD4659"
            }
        },
        "precision": 0, // Precision is set to one digit, e.g. 777.7
        "inputs": {}
    },

    "styles": {
        "PE": {
            "title": 'COI Put',
            "histogramBase": 0,
        },
        "CE": {
            "title": 'COI CE',
            "histogramBase": 0,
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
