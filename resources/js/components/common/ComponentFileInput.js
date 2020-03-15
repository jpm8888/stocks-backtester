import React, {Component} from 'react';

class ComponentFileInput extends Component {

    constructor(props) {
        super(props);
        this.state = {}
    }

    render() {
        const label = this.props.label;
        const required = (this.props.required) ? this.props.required : false;
        const RequiredView = (required) ? (<span style={{color : 'red'}}> <sup>*</sup></span>) : (<div></div>);

        const hint = (this.props.hint) ? this.props.hint : "";

        return (
            <div className="col-md-4">
                <div className="form-group">
                    <label style={{fontWeight: 'bold'}}>{label}{RequiredView}</label>
                    <input {...this.props} autoComplete="off" type="file" className="form-control"/>
                    <small className="form-text text-muted">{hint}</small>
                </div>
            </div>
        );
    }
}

export default ComponentFileInput;
