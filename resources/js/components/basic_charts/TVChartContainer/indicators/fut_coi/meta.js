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
    "is_price_study": true,
    "isCustomIndicator": true,
    isTVScript: true,
    isTVScriptStub: true,
    "plots": [{"id": "plot_0", "type": "line"}],
    "defaults": {
        "styles": {
            "plot_0": {
                "linestyle": 0,
                "visible": true,
                "linewidth": 2, // Make the line thinner
                "plottype": 2, // Plot type is Line
                "trackPrice": true, // Show price line
                "transparency": 40,
                "color": "#0ea91f" // Set the plotted line color to dark red
            }
        },
        "precision": 2, // Precision is set to one digit, e.g. 777.7
        "inputs": {}
    },

    "styles": {
        "plot_0": {
            "title": title, // Output name will be displayed in the Style window
            "histogramBase": 0,
        }
    },
    "inputs": [],
};
