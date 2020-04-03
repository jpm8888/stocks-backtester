export const id = "Equity"; //id should be same as title without spaces
export const title = "Equity";
export const shortDesc = "Short description";

export const configEquity = {
    "_metainfoVersion": 40,
    "id": `${id}@tv-basicstudies-1`,
    "scriptIdPart": "",
    "name": `${id}`,
    "description": id, //should be same as id
    "shortDescription": shortDesc,
    "is_hidden_study": false,
    "is_price_study": true,
    "isCustomIndicator": true,
    "plots": [{"id": "plot_0", "type": "line"}],
    "defaults": {
        "styles": {
            "plot_0": {
                "linestyle": 0,
                "visible": true,
                "linewidth": 1, // Make the line thinner
                "plottype": 2, // Plot type is Line
                "trackPrice": true, // Show price line
                "transparency": 40,
                "color": "#880000" // Set the plotted line color to dark red
            }
        },
        "precision": 1, // Precision is set to one digit, e.g. 777.7
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


// Plot type:
//    1 - Histogram
//    2 - Line
//    3 - Cross
//    4 - Area
//    5 - Columns
//    6 - Circles
//    7 - Line With Breaks
//    8 - Area With Breaks
