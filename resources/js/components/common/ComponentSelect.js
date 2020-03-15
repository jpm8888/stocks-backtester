import React, {Component} from 'react';

class ComponentSelect extends Component {
    render() {
        const label = this.props.label;
        const options = this.props.options.map((item, index) => {
            return <option key={'op_' + item.name + index} value={item.id}>{item.name}</option>
        });

        const disabled = (this.props.disabled);

        const required = (this.props.required) ? this.props.required : false;
        const RequiredView = (required) ? (<span style={{color : 'red'}}> <sup>*</sup></span>) : (<div></div>);

        const className = (this.props.className) ? this.props.className : "col-md-4";

        return (
            <div className={className}>
                <div className="form-group">
                    <label style={{fontWeight: 'bold'}}>{label}{RequiredView}</label>
                    <select className="form-control" disabled={disabled} value={this.props.value} onChange={this.props.onChange}>
                        <option key={'op_'} value={''}>{'Select'}</option>
                        {options}
                    </select>
                </div>
            </div>
        );
    }
}

export default ComponentSelect;
