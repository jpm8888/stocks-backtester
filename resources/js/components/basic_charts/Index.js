import React, {Component} from 'react';
import ReactDOM from 'react-dom';
import ComponentContainer from "../common/ComponentContainer";
import {TVChartContainer} from "./TVChartContainer";


class Index extends Component {
    render() {
        return (
            <div>
                <div className="col-md-10">
                    <TVChartContainer/>
                </div>
                <div className="col-md-2">

                </div>
            </div>
        );
    }
}

if (document.getElementById('div_basic_charts')) {
    ReactDOM.render(<Index/>
        , document.getElementById('div_basic_charts'));
}
