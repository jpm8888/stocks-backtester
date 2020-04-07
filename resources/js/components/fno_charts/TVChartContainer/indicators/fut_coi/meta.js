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
    "plots": [{"id": "plot_0", "type": "line"}, {"id": "plot_1", "type": "line"}, {"id": "plot_2", "type": "line"}],
    "defaults": {
        "styles": {
            "plot_0": {
                "linestyle": 0,
                "visible": true,
                "linewidth": 2, // Make the line thinner
                "plottype": 2, // Plot type is Area
                "trackPrice": false, // Show price line
                "transparency": 40,
                "color": "#247AFD" // Set the plotted line color to dark red
            },
            "plot_1": {
                "linestyle": 0,
                "visible": true,
                "linewidth": 2, // Make the line thinner
                "plottype": 2, // Plot type is Area
                "trackPrice": false, // Show price line
                "transparency": 40,
                "color": "#FD4659" // Set the plotted line color to dark red
            },
            "plot_2": {
                "linestyle": 0,
                "visible": true,
                "linewidth": 2, // Make the line thinner
                "plottype": 2, // Plot type is Area
                "trackPrice": false, // Show price line
                "transparency": 40,
                "color": "#32BF84" // Set the plotted line color to dark red
            }
        },
        "precision": 4, // Precision is set to one digit, e.g. 777.7
        "inputs": {}
    },

    "styles": {
        "plot_0": {
            "title": title, // Output name will be displayed in the Style window
            "histogramBase": 0,
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
