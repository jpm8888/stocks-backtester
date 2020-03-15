import React, {Component} from 'react';

class ComponentTextArea extends Component {

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
        const rows = (this.props.rows) ? this.props.rows : 6;

        return (
            <div className={className}>
                <div className="form-group">
                    <label style={{fontWeight: 'bold'}}>{label}{RequiredView}</label>
                    <textarea {...this.props} rows={rows} autoComplete="off" className="form-control"/>
                    <small className="form-text text-muted">{hint}</small>
                </div>
            </div>
        );
    }
}

export default ComponentTextArea;
