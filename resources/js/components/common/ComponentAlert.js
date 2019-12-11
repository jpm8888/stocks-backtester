import React, {Component} from 'react';

class ComponentAlert extends Component {
    render() {
        let type = (this.props.type) ? this.props.type : 'primary';
        let msg = (this.props.msg) ? this.props.msg : "";
        if (msg === "") return <div></div>

        return (
            <div className={"alert alert-" + type} role="alert">
                {msg}
            </div>
        );
    }
}

export default ComponentAlert;
