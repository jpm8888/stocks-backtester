export const id = "PCR"; //id should be same as title without spaces
export const title = "PCR";
export const shortDesc = "PCR";
export const plotType = 2;

export const configPCR = {
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
    "plots": [{"id": "pcr", "type": "line"}],
    "defaults": {
        "styles": {
            "pcr": {
                "linestyle": 0,
                "visible": true,
                "linewidth": 2,
                "plottype": plotType,
                "trackPrice": false,
                "transparency": 0,
                "color": "#FD4659"
            }
        },
        "precision": 2, // Precision is set to one digit, e.g. 777.7
        "inputs": {}
    },

    "styles": {
        "pcr": {
            "title": 'Put Call Ratio',
            "histogramBase": 0,
        },
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
