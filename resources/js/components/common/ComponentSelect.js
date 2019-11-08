import React, {Component} from 'react';

class ComponentSelect extends Component {
    render() {
        const label = this.props.label;
        const options = this.props.options.map((item, index) => {
            return <option key={'op_' + item.name + index} value={item.id}>{item.name}</option>
        });

        return (
            <div className="col-md-4">
                <div className="input-group mb-3">
                    <div className="input-group-prepend">
                        <label className="input-group-text">{label}</label>
                    </div>
                    <select className="custom-select">
                        {options}
                    </select>
                </div>
            </div>
        );
    }
}

export default ComponentSelect;
