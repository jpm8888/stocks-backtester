export const configFutCoi = {
  name: "Equity",
  metainfo: {
    "_metainfoVersion": 40,
    "id": "Equity@tv-basicstudies-1",
    "scriptIdPart": "",
    "name": "Equity",
    "description": "Equity",
    "shortDescription": "Equity",
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
        "title": "Equity value", // Output name will be displayed in the Style window
        "histogramBase": 0,
      }
    },
    "inputs": [],
  }
};
