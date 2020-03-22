import React, {Component} from 'react';
import ReactDOM from 'react-dom';
import {Provider} from 'react-redux';
import store from "./store";
import ComponentContainer from "../common/ComponentContainer";
import UploadView from "./views/UploadView";
import ImportView from "./views/ImportView";
import LogView from "./views/LogView";

class ComponentIndex extends Component {
    render() {
        return (
            <ComponentContainer>
                <UploadView/>
                <ImportView/>
                <LogView/>
            </ComponentContainer>
        );
    }
}

if (document.getElementById('div_import_csv_xlsx')) {
    ReactDOM.render(
        <Provider store={store}>
            <ComponentIndex/>
        </Provider>
        , document.getElementById('div_import_csv_xlsx'));
}
