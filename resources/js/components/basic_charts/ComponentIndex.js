import React, {Component} from 'react';
import ReactDOM from 'react-dom';
import {Provider} from 'react-redux';
import store from "./store";
import TVChartContainer from "./TVChartContainer/TVChartContainer";
import SymbolsWatchlist from "./WatchLists/SymbolsWatchlist";


class ComponentIndex extends Component {
    render() {
        return (
            <div style={{width : '100%'}}>
                <div style={{width : '80%', float : 'left'}}>
                    <TVChartContainer user_id={this.props.user_id}/>
                </div>
                <SymbolsWatchlist/>
            </div>
        );
    }
}

if (document.getElementById('div_fno_charts')) {
    let id = document.getElementById('div_fno_charts').getAttribute('data');
    ReactDOM.render(
        <Provider store={store}>
            <ComponentIndex user_id={id}/>
        </Provider>
        , document.getElementById('div_fno_charts'));
}
