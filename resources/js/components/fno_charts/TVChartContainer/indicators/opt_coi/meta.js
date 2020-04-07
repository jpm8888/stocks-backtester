export const id = "OptionCOI"; //id should be same as title without spaces
export const title = "OptionCOI";
export const shortDesc = "Option COI";

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
                "plottype": 2,
                "trackPrice": false,
                "transparency": 0,
                "color": "#FD4659"
            },
            "CE": {
                "linestyle": 0,
                "visible": true,
                "linewidth": 2,
                "plottype": 2,
                "trackPrice": false,
                "transparency": 0,
                "color": "#32BF84"
            }
        },
        "precision": 4, // Precision is set to one digit, e.g. 777.7
        "inputs": {}
    },

    "styles": {
        "PE": {
            "title": 'Change COI Put %', // Output name will be displayed in the Style window
            "histogramBase": 1,
        },
        "CE": {
            "title": 'Change COI CE %', // Output name will be displayed in the Style window
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
