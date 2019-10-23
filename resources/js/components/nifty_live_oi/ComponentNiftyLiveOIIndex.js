import React, {Component} from 'react';
import ReactDOM from "react-dom";

export default class ComponentNiftyLiveOIIndex extends Component {
    render() {
        return (
            <div className="container">
                <div className="row justify-content-center">
                    <div className="col-md-8">
                        <div className="card">
                            <div className="card-header">Example1 Component</div>

                            <div className="card-body">I'm an example component!</div>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}

if (document.getElementById('div_nifty_live_oi')) {
    ReactDOM.render(<ComponentNiftyLiveOIIndex />, document.getElementById('div_nifty_live_oi'));
}
