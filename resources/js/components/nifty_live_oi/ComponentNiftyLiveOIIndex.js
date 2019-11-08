import React, {Component} from 'react';
import ReactDOM from 'react-dom';
import {Provider} from 'react-redux';
import store from "./store";
import ViewTable from "./views/ViewTable";
import ViewOptions from "./views/ViewOptions";

class ComponentNiftyLiveOIIndex extends Component {
    render() {
        return (
            <div>
                <ViewOptions/>
                <ViewTable/>
            </div>
        );
    }
}

if (document.getElementById('div_nifty_live_oi')) {
    ReactDOM.render(
        <Provider store={store}>
            <ComponentNiftyLiveOIIndex/>
        </Provider>
        , document.getElementById('div_nifty_live_oi'));
}
