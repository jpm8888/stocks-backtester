import React, {Component} from 'react';
import ReactDOM from 'react-dom';
import {Provider} from 'react-redux';
import store from "./store";
import ComponentContainer from "../common/ComponentContainer";
import Filter from "./views/Filter";

class ComponentIndex extends Component {
    render() {
        return (
            <ComponentContainer>
                <Filter/>
            </ComponentContainer>
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
