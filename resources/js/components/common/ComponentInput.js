import React, {Component} from 'react';

class ComponentInput extends Component {

    constructor(props){
        super(props);
        this.state = {

        }
    }

    render() {
        const label = this.props.label;
        const required = (this.props.required) ? this.props.required : false;
        const RequiredView = (required) ? (<span className="red">*</span>) : (<div></div>);

        const hint = (this.props.hint) ? this.props.hint : "";

        return (
            <div className="col">
                <div className="form-group">
                    <label>{label}{RequiredView}</label>
                    <input {...this.props} autoComplete="off" type="text" className="form-control" />
                    <small className="form-text text-muted">{hint}</small>
                </div>
            </div>
        );
    }
}

export default ComponentInput;