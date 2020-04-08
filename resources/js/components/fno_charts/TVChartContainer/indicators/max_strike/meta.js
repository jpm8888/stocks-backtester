export const id = "MaxStrike"; //id should be same as title without spaces
export const title = "MaxStrike";
export const shortDesc = "Max Strike";

export const plotType = 3;

export const configMaxStrike = {
    "_metainfoVersion": 40,
    "id": `${id}@tv-basicstudies-1`,
    "scriptIdPart": "",
    "name": `${id}`,
    "description": id, //should be same as id
    "shortDescription": shortDesc,
    "is_hidden_study": false,
    "is_price_study": true,
    "isCustomIndicator": true,
    isTVScript: true,
    isTVScriptStub: false,
    "plots": [{"id": "PE", "type": "line"}, {"id": "CE", "type": "line"}],
    "defaults": {
        "styles": {
            "PE": {
                "linestyle": 0,
                "visible": true,
                "linewidth": 2,
                "plottype": plotType,
                "trackPrice": false,
                "transparency": 0,
                "color": "#FD4659"
            },
            "CE": {
                "linestyle": 0,
                "visible": true,
                "linewidth": 2,
                "plottype": plotType,
                "trackPrice": false,
                "transparency": 0,
                "color": "#32BF84"
            }
        },
        "precision": 2, // Precision is set to one digit, e.g. 777.7
        "inputs": {}
    },

    "styles": {
        "PE": {
            "title": 'Max Strike Put',
            "histogramBase": 1,
        },
        "CE": {
            "title": 'Max Strike Call',
            "histogramBase": 1,
        }
    },
    "inputs": [10],
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
