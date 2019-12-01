import React, {Component} from 'react';
import ReactDOM from 'react-dom';
import {Provider} from 'react-redux';
import store from "./store";
import ViewTable from "./views/ViewTable";
import ViewOptions from "./views/ViewOptions";

class ComponentIndex extends Component {
    render() {
        return (
            <div>
                <ViewOptions/>
                <ViewTable/>
            </div>
        );
    }
}

if (document.getElementById('div_bhavcopy_analyse')) {
    ReactDOM.render(
        <Provider store={store}>
            <ComponentIndex/>
        </Provider>
        , document.getElementById('div_bhavcopy_analyse'));
}
