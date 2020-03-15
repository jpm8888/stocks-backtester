import React, {Component} from 'react';

class ComponentDateInput extends Component {

    constructor(props) {
        super(props);
        this.state = {}
    }

    render() {
        const label = this.props.label;
        const required = (this.props.required) ? this.props.required : false;
        const RequiredView = (required) ? (<span style={{color : 'red'}}> <sup>*</sup></span>) : (<div></div>);

        const hint = (this.props.hint) ? this.props.hint : "";
        const className = (this.props.className) ? this.props.className : "col-md-4";
        return (
            <div className={className}>
                <div className="form-group">
                    <label style={{fontWeight: 'bold'}}>{label}{RequiredView}</label>
                    <input {...this.props} autoComplete="off" type="date" className="form-control"/>
                    <small className="form-text text-muted">{hint}</small>
                </div>
            </div>
        );
    }
}

export default ComponentDateInput;
