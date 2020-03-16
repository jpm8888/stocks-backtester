import React, {Component} from 'react';
import ReactDOM from 'react-dom';
// import { widget } from '../../charting_library/charting_library/charting_library.min';
import ComponentContainer from "../common/ComponentContainer";
import {TVChartContainer} from "./TVChartContainer";


class Index extends Component {
    render() {
        return (
            <ComponentContainer>
                <TVChartContainer/>
            </ComponentContainer>
        );
    }
}

if (document.getElementById('div_basic_charts')) {
    ReactDOM.render(<Index/>
        , document.getElementById('div_basic_charts'));
}
