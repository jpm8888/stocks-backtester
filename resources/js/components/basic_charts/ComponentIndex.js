import React, {Component} from 'react';
import ReactDOM from 'react-dom';
import {Provider} from 'react-redux';
import store from "./store";
import TVChartContainer from "./TVChartContainer/TVChartContainer";
import SymbolsWatchlist from "./WatchLists/SymbolsWatchlist";


class ComponentIndex extends Component {
    render() {
        return (
            <div className={"row"}>
                <div className="col-md-10">
                    <TVChartContainer/>
                </div>
                <SymbolsWatchlist/>
            </div>
        );
    }
}

if (document.getElementById('div_basic_charts')) {
    ReactDOM.render(
        <Provider store={store}>
            <ComponentIndex/>
        </Provider>
        , document.getElementById('div_basic_charts'));
}
