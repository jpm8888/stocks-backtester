export const id = "CustomVolume"; //id should be same as title without spaces
export const title = "CustomVolume";
export const shortDesc = "CustomVolume";
export const plotType = 2;

export const configCustomVolume = {
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
    "plots": [{"id": "volume", "type": "line"}],
    "defaults": {
        "styles": {
            "volume": {
                "linestyle": 0,
                "visible": true,
                "linewidth": 2,
                "plottype": 5,
                "trackPrice": false,
                "transparency": 0,
                "color": "#00022E"
            },
            // "volume_15": {
            //     "linestyle": 0,
            //     "visible": true,
            //     "linewidth": 2,
            //     "plottype": plotType,
            //     "trackPrice": false,
            //     "transparency": 0,
            //     "color": "#FFAB0F"
            // },
            // "volume_10": {
            //     "linestyle": 0,
            //     "visible": true,
            //     "linewidth": 2,
            //     "plottype": plotType,
            //     "trackPrice": false,
            //     "transparency": 0,
            //     "color": "#247AFD"
            // },
            // "volume_5": {
            //     "linestyle": 0,
            //     "visible": true,
            //     "linewidth": 2,
            //     "plottype": plotType,
            //     "trackPrice": false,
            //     "transparency": 0,
            //     "color": "#FE46A5"
            // }
        },
        "precision": 0, // Precision is set to one digit, e.g. 777.7
        "inputs": {}
    },

    "styles": {
        "volume_15": {
            "title": 'volume_15',
            "histogramBase": 0,
        },
        "volume_10": {
            "title": 'volume_10',
            "histogramBase": 0,
        },
        "volume_5": {
            "title": 'volume_5',
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
