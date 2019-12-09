import React, {Component} from 'react';
import Select from "react-select";

export default class ComponentMultiSelect extends Component {
    /*
    * id, name pair should be passed as props.
    * */

    constructor(props){
        super(props);
        this.state = {

        }
    }


    render() {
        const label = this.props.label;
        const disabled = (this.props.disabled);
        const options = this.props.options.map((item) => {
            return ({
                value : item.id,
                label : item.name,
            })
        });

        const required = (this.props.required) ? this.props.required : false;
        const RequiredView = (required) ? (<span className="red">*</span>) : (<div></div>);
        const isMulti = (this.props.isMulti);
        return (
            <div className="col">
                <div className="form-group">
                    <label>{label}{RequiredView}</label>
                    <Select name={this.props.name}
                            isMulti={isMulti}
                            placeholder={label}
                            isDisabled={disabled}
                            value={this.props.value}
                            onChange={this.props.onChange}
                            options={options}>
                    </Select>
                </div>
            </div>
        );
    }
}
