import React, {Component} from 'react';
import ReactDOM from 'react-dom';
import {Provider} from 'react-redux';
import store from "./store";
import ComponentContainer from "../common/ComponentContainer";
import CalculatorIndex from "./views/CalculatorIndex";

class ComponentIndex extends Component {
    render() {
        return (
            <ComponentContainer>
                <CalculatorIndex/>
            </ComponentContainer>
        );
    }
}

if (document.getElementById('div_risk_calculator')) {
    ReactDOM.render(
        <Provider store={store}>
            <ComponentIndex/>
        </Provider>
        , document.getElementById('div_risk_calculator'));
}
