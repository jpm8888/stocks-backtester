import React, {Component} from 'react';
import ReactDOM from 'react-dom';
import {Provider} from 'react-redux';
import store from "./store";
import ComponentContainer from "../common/ComponentContainer";
import ComponentCard from "../common/ComponentCard";
import ComponentInput from "../common/ComponentInput";
import ComponentRow from "../common/ComponentRow";

class ComponentIndex extends Component {
    render() {
        return (
            <ComponentContainer>
                <ComponentCard>
                    <ComponentRow>
                        <ComponentInput label={"Test"} hint={"Test"}/>
                        <ComponentInput label={"Test"} hint={"Test"}/>
                    </ComponentRow>
                </ComponentCard>
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
